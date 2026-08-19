<?php namespace App\Controllers;

// use App\Models\SessTb_Model;
use App\Models\SessLog_Model;
use App\Models\SessTry_Model;
use App\Models\MoneyHist_Model;
use App\Models\Exchange_Model;
use App\Models\Charge_Model;
use App\Models\Reward_Model;
use App\Models\Follow_Model;
use App\Models\Block_Model;
use App\Libraries\ApiVacc_Lib;
use App\Models\Captcha_Model;
use App\Models\MemConf_Model;

use App\Models\Round_Model;
use App\Models\Bet_Model;


class Api extends BaseController
{
	// public function test(){
	// 	if(!is_login())
	// 	{
    //         $result->status = STATUS_LOGOUT;
    //     } else {
	// 		$user_id = $this->session->user_id;
	// 		$objMember = $this->modelMember->getByUid($user_id);
	// 		$bResult = $this->modelMember->updateAssets($objMember, -100, 0, POINTCHANGE_EXCHANGE);
	// 		if($bResult)
	// 			echo "updateAssets = success";
	// 		else  
	// 			echo "updateAssets = fail";
	// 	}
	// }

	public function index(){
		$this->response->redirect(site_furl('/'));	
	}
		
	//User Login
	public function login(){ 
		
		$this->setLanguage();
		$user_id = $this->request->getVar('userid');
		$user_pw = $this->request->getVar('passwd');
		$user_ip = $this->request->getVar('ip');
		$type = intval($this->request->getVar('type'));
		writeLog("[login] param:".$user_id.", ".$user_pw.", ".$user_ip.", ".$type);

		if($type==0 && array_key_exists('login.captcha', $_ENV) && $_ENV['login.captcha'] == 1){
			$captchaCode = $this->request->getPost('captchacode');
			// writeLog("captchaCod=".$captchaCode);
			// if(strlen($captchaCode) > 0){
				$captchaImg = $this->request->getPost('captchasrc');
				$captchaModel = new Captcha_Model();
				$captchaOK = $captchaModel->verify($captchaImg, $captchaCode);
				if( $captchaOK != RESULT_OK){
					$arrResult['code'] = RESULT_CAPTCHA_ERR;			//Security Character is mistake
					$arrResult['status'] = STATUS_FAIL;
					$arrResult['msg'] = lang("common.security_character_mistake");
					echo json_encode($arrResult);
					return;
				}
			// }
		}

		if(strlen($user_ip) < 1 && $type != 1)
			$user_ip = $this->request->getIPAddress();

		$modelSessTry = new SessTry_Model();

		$sessTry = $modelSessTry->getByIp($user_ip);
		$iTry = 5;
		if(!is_null($sessTry)){
			$iTry = time() - strtotime($sessTry->log_time);
		}
		if($iTry < 3){
			$arrResult['status'] = STATUS_FAIL;
			$arrResult['code'] = RESULT_FAIL;	//Waiting 
			$arrResult['msg'] = lang("common.login_try").(3-$iTry).lang("common.seconds");
			echo json_encode($arrResult);
			return;
		}
		

		$isValid = validLoginValue($user_id, $user_pw);
		
		if(strlen($user_id) < 1 || strlen($user_pw) < 1 || !$isValid){
			if(strlen($user_id) > 0)
				$modelSessTry->add($user_id, $user_pw, $user_ip, TRYLOG_DENIED);
			writeLog("[login] check:".$user_id." ,".$user_pw." ");

			$arrResult['code'] = RESULT_FAIL;		
			$arrResult['status'] = STATUS_FAIL;
			$arrResult['msg'] = lang("common.login_fail");
			echo json_encode($arrResult);
			return;
		}

		$objMember = $this->modelMember->login($user_id, $user_pw);

		$modelBlock = new Block_Model();
		if($modelBlock->isBlockIp($user_ip)){
			$arrResult['status'] = STATUS_FAIL;
			$arrResult['code'] = RESULT_FAIL;	
			$arrResult['msg'] = lang("common.login_ip_block");
			$modelSessTry->add($user_id, $user_pw, $user_ip, TRYLOG_IPBLOCK);
		} else if($type==0 && (is_null($objMember) || $objMember->mb_level < LEVEL_ADMIN) && $this->modelConfsite->IsMaintain()){
			$arrResult['status'] = STATUS_FAIL;
			$arrResult['code'] = RESULT_MAINTAIN;	//Maintain
			$msg = $this->modelConfsite->msgMaintain();
			if(strlen($msg) < 1){
				$msg = lang("common.inspection");
				$arrResult['code'] = RESULT_FAIL;	
			}
			$arrResult['msg'] = $msg;
			$modelSessTry->add($user_id, $user_pw, $user_ip, TRYLOG_MAINTAIN);
		} else if(is_null($objMember) || $objMember->mb_state_active == PERMIT_DELETE) {
			$tryLog = TRYLOG_NONE;

			$nFails = 0;
			$strFail = lang("common.login_fail");;
			if(is_null($objMember)){
				$objMember = $this->modelMember->getByUid($user_id);
				if(is_null($objMember)){
					$tryLog = TRYLOG_NONE;
				} else if($objMember->mb_state_active == PERMIT_DELETE){
					$tryLog = TRYLOG_DELETED;
				} else {
					$tryLog = TRYLOG_FAIL;

					if(array_key_exists('app.login_try', $_ENV) && $_ENV['app.login_try'] > 0 && $objMember->mb_state_active == PERMIT_OK){
					
						$sessTrys = $modelSessTry->getByUid($user_id);
						$nFails = 1;
						foreach($sessTrys as $objTry){
							if($objTry->log_result !== TRYLOG_FAIL && $objTry->log_result !== TRYLOG_BLOCK)
								break;
							$nFails ++;
						}
						$strFail .= " (남은회수:".(intval($_ENV['app.login_try']) - $nFails)."회)"; 
						if($nFails >= $_ENV['app.login_try']){
							$tryLog =  TRYLOG_BLOCK;
							$modelBlock->saveByIp($user_ip);
							if($objMember->mb_level <= LEVEL_ADMIN)
								$this->modelMember->updateData($objMember, ['mb_state_active' => PERMIT_CANCEL]);
							$strFail = "과도한 로그인시도로 차단되었습니다.";
						}
					}
				}
			} else if($objMember->mb_state_active == PERMIT_DELETE){
				$tryLog = TRYLOG_DELETED;
			}
			
			$arrResult['code'] = RESULT_FAIL;		
			$arrResult['status'] = STATUS_FAIL;
			$arrResult['msg'] = $strFail;

			$modelSessTry->add($user_id, $user_pw, $user_ip, $tryLog);
		} else if( $objMember->mb_level < LEVEL_ADMIN && $type == 1 && strlen($user_ip) < 1){
			$arrResult['status'] = STATUS_FAIL;
			$arrResult['code'] = STATUS_FAIL;	
			$arrResult['msg'] = lang("common.login_refuse");
		} else {
			$this->modelSess->deleteLast();
		
			$sessId = $this->session->session_id;
			$sess = $this->modelSess->getByUid($objMember->mb_uid);

			$enMultiLogin = $this->modelConfsite->IsMultiLogin();
			$enAdminMulti = false;
			if(array_key_exists('app.login_multi', $_ENV) && intval($_ENV['app.login_multi']) == 1 )
				$enAdminMulti = true;

			if($objMember->mb_state_active == PERMIT_WAIT || $objMember->mb_state_active == PERMIT_REQ){
				$arrResult['status'] = STATUS_FAIL;
				$arrResult['code'] = RESULT_WAIT;
                $arrResult['msg'] = lang("common.login_wait");
				$modelSessTry->add($user_id, $user_pw, $user_ip, TRYLOG_WAIT);
			}
			 else if($objMember->mb_level >= LEVEL_ADMIN && $objMember->mb_state_view == STATE_ACTIVE && 
					!isValidIp($objMember->mb_ip_join, $user_ip)){
				$arrResult['status'] = STATUS_FAIL;
				$arrResult['code'] = RESULT_FAIL;	
				$arrResult['msg'] = lang("common.login_ip_permit");
				$modelSessTry->add($user_id, $user_pw, $user_ip, TRYLOG_IPDENIED);
			} else if($type != 2 && $objMember->mb_level < LEVEL_ADMIN && !$enMultiLogin && 
					!is_null($sess) && $sess->sess_id != $sessId ){
				$arrResult['status'] = STATUS_FAIL;
				$arrResult['code'] = RESULT_FAIL;	
				$arrResult['msg'] = lang("common.login_duplicate");
				$modelSessTry->add($user_id, $user_pw, $user_ip, TRYLOG_LOGINING);
			} else if($objMember->mb_level == LEVEL_ADMIN && !$enAdminMulti && $objMember->mb_state_view != STATE_ACTIVE 
					&& !$enMultiLogin && !is_null($sess) && $sess->sess_id != $sessId && $sess->sess_ip != $user_ip) {
				$arrResult['status'] = STATUS_FAIL;
				$arrResult['code'] = RESULT_FAIL;	
				$arrResult['msg'] = lang("common.login_duplicate");
				$modelSessTry->add($user_id, $user_pw, $user_ip, TRYLOG_LOGINING);
			} else if($this->modelMember->isPermitMember($objMember)){
				//Save Session
				$sessData = array('user_id' => $objMember->mb_uid, 'logged_in'=>TRUE );

				$this->session->set($sessData);
				$objMember->mb_ip_last = $user_ip;
				$this->modelMember->updateLogin($objMember);

				$this->modelSess->add($objMember, $sessId, $type);
				$modelSessLog = new SessLog_Model();
				$modelSessLog->add($objMember);
				//Add Try 
				$modelSessTry->add($user_id, $user_pw, $user_ip, TRYLOG_SUCCESS);

				writeLog("[login] success ".$user_id." (".$sessId.") ");

				$arrResult['status'] = STATUS_SUCCESS;
				$arrResult['code'] = RESULT_OK;//1-Success 2-Mistake 3-Stop
			} else{
				$modelSessTry->add($user_id, $user_pw, $user_ip, TRYLOG_IDBLOCK);
				$arrResult['status'] = STATUS_FAIL;
				$arrResult['code'] = RESULT_STOP;//Stop
                $arrResult['msg'] = lang("common.login_id_block");
			} 			
		}

		echo json_encode($arrResult);


	}
	
	public function logout(){
		$sess_id = $this->session->session_id;
		writeLog("[api] logout (".$sess_id.")");
		
		$this->sess_destroy();
		
		$arrResult['status'] = "success";
		echo json_encode($arrResult);
	}

	public function check_login(){ 

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = STATUS_LOGOUT;
		}
		else {
			$result->status = STATUS_SUCCESS;
		}
		echo json_encode($result);	

	}

	public function check_proposer(){
		$this->setLanguage();
		$arrData['proposer'] = $this->request->getVar('recommender_id');
		
		$result = new \StdClass;

		$objEmp = $this->modelMember->getByUid($arrData['proposer']);
		$minLevel = LEVEL_MIN;
		if(array_key_exists('app.level_limit', $_ENV) && intval($_ENV['app.level_limit']) > 0 ){
			$minLevel = LEVEL_MAX - intval($_ENV['app.level_limit']);
		}
		$recommenderDeny = false;
		if(!is_null($objEmp) && array_key_exists('app.sess_act', $_ENV) && $_ENV['app.sess_act'] == 1){
			$memConfModel = new MemConf_Model();
			$memConf = $memConfModel->getByMember($objEmp->mb_fid);
			if(!is_null($memConf) ){
				$recommenderDeny = $memConf->conf_num_2 == STATE_ACTIVE;
			}
		}

		if(is_null($objEmp)){
			$result->msg = lang("common.recommender_mistake");
			$result->status = STATUS_FAIL;
		} else if($objEmp->mb_level <= $minLevel){
			$result->msg = lang("common.recommender_nopermitted");
			$result->status = STATUS_FAIL;
		} else if($recommenderDeny || !$this->modelMember->isPermitMember($objEmp) || $objEmp->mb_level > LEVEL_COMPANY){
			$result->msg = lang("common.recommender_nopermitted");
			$result->status = STATUS_FAIL;
		} else {
			$result->status = STATUS_SUCCESS;
			$result->data = $objEmp->mb_uid;
		}
		echo json_encode($result);	
	}

	public function check_account(){
		$this->setLanguage();
		$arrData['member_id'] = $this->request->getVar('userid');
		$arrData['nickname'] = $this->request->getVar('nickname');
		
		$result = new \StdClass;
		$result->status = STATUS_SUCCESS;

		if(strlen($arrData['member_id']) > 0){
			$checkOk = validUserId($arrData['member_id']);
			if(!$checkOk){
				$result->status = STATUS_FAIL;
				$result->msg = lang("common.id_rule"); 
			} else {
				$objMember = $this->modelMember->getByUid($arrData['member_id']);
				if(!is_null($objMember) && $objMember->mb_state_active != PERMIT_DELETE ){
					$result->status = STATUS_FAIL;
					$result->msg = lang("common.id_duplicated"); 
				} 
			}
		}

		if($result->status == STATUS_SUCCESS && strlen($arrData['nickname']) > 0){
			$objMember = $this->modelMember->getByName($arrData['nickname']);
			if(!is_null($objMember) ){
				$result->status = STATUS_FAIL;
				$result->msg = lang("common.nickname_duplicated"); 
			}
		}
		echo json_encode($result);	
	}

	public function check_session(){

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = STATUS_LOGOUT;
		}
		else {

			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$sess_id = $this->session->session_id;
			$this->modelSess->deleteLast();
			$sess = $this->modelSess->getBySess($sess_id);
			
			$bPermit = true;
			if(is_null($objMember)){
				$bPermit = false;
				writeLog("[check_session] user = null (".$user_id.")");
			}
			else if($objMember->mb_level < LEVEL_ADMIN && $this->modelConfsite->IsMaintain()){
				$bPermit = false;
			}
			else if( !$this->modelMember->isPermitMember($objMember) ){
				$bPermit = false;
			}
			else if( is_null($sess) ){
				$bPermit = false;
				writeLog("[check_session] session = null (".$sess_id.")");
			} else if(array_key_exists('app.sess_act', $_ENV) && $_ENV['app.sess_act'] == 1){
				$objConf = $this->modelConfsite->find(CONF_DELAY_PLAY);
				$delayOut = 0;
				$arrInfo = explode('#', $objConf->conf_idx);
				if(count($arrInfo) >= 2){
					if($objMember->mb_level < LEVEL_ADMIN)
						$delayOut = intval($arrInfo[0]);
					else
						$delayOut = intval($arrInfo[1]);
				}

				if($delayOut > 0 && diffDt(date('Y-m-d H:i:s'), $sess->sess_action) > $delayOut * 60){
					$bPermit = false;
					writeLog("[check_session] session_action = ".$sess->sess_action." (".$sess_id.")");
				}
			}

			if(!$bPermit){
				writeLog("[check_session] logout (".$sess_id.")");
				$this->sess_destroy();
				$result->status = STATUS_LOGOUT;                
			} else{
				// writeLog("[check_session] ".$user_id." (".$sess_id.")");
				$this->modelSess->updateLast($sess_id);

				$objInfo = new \StdClass;
				$objInfo->money = allMoney($objMember);
				$objInfo->point = floor($objMember->mb_point); //round($objMember->mb_point, NUM_POINT_CNT);
				$objInfo->msg = $this->modelNotice->unreadMsg($objMember->mb_uid);
				$objInfo->cus = $this->modelNotice->unreadCus($objMember->mb_uid);

				$result->data = $objInfo;
				$result->status = STATUS_SUCCESS;

			} 

		}

		echo json_encode($result);	
	}

	public function check_notice(){
		
		$result = new \StdClass;

		if(!is_login())
		{
			$result->status = STATUS_LOGOUT;
		} else{
			$this->setLanguage();

			$notice_main = 0;
            $boards = array();
			
			$arrConf = $this->modelConfsite->getNoticeConf();  
			foreach($arrConf as $objConf){
				switch($objConf->conf_id){
					case CONF_NOTICE_MAIN: $notice_main = $objConf->conf_active;
						break;
					case CONF_NOTICE_BANK: 
						if($objConf->conf_active == STATE_ACTIVE){
                            $board = new \StdClass;
                            $board->notice_title = lang("common.notice_deposit"); 
                            $board->notice_content = $objConf->conf_content;
                            $board->notice_color = $objConf->conf_idx;
                            $boards[] = $board;
                        }
						break;
					case CONF_NOTICE_URGENT: 
						if($objConf->conf_active == STATE_ACTIVE){
                            $board = new \StdClass;
                            $board->notice_title = lang("common.notice_emergency"); 
                            $board->notice_content = $objConf->conf_content;
                            $board->notice_color = $objConf->conf_idx;
                            $boards[] = $board;
                        }
						break;
					default:break;
				}
			}

			$reqData['page'] = 1;
			$reqData['count'] = 4;
			$reqData['popup'] = 1;
			$notices = $this->modelNotice->searchBodList($reqData);
            foreach($notices as $notice){
                $notice->notice_color = '#333';
                $boards[] = $notice;
            }

			$result->notice_main = $notice_main; 
			$result->boards = $boards; 

			$result->status = STATUS_SUCCESS;	
		}
		echo json_encode($result);
	}
	
	public function check_pass(){
		$this->setLanguage();
		
		$result = new \StdClass;
		if(!is_login())
		{
			$result->msg = lang("common.session_expired"); 
			$result->status = STATUS_LOGOUT;
		}
		else {

			$user_id = $this->session->user_id;
			$user_pw = $this->request->getPost('user_pw');

			$objMember = $this->modelMember->login($user_id, $user_pw);
			$bPermit = true;
			if(is_null($objMember))
				$bPermit = false;
						
			if(!$bPermit){
				$result->msg = lang("common.password_mistake"); 
				$result->status = STATUS_FAIL;                
			} else{
				$result->status = STATUS_SUCCESS; 
			}

		}

		echo json_encode($result);	
	}

	
	public function change_pass(){
		$this->setLanguage();
		
		$result = new \StdClass;
		if(!is_login())
		{
			$result->msg = lang("common.session_expired");
			$result->status = STATUS_LOGOUT;
		}
		else {
			$this->sess_action();                
			$user_id = $this->session->user_id;
			$user_pw = $this->request->getPost('pwd_old');
			$user_newPw = $this->request->getPost('pwd_new');

			$objMember = $this->modelMember->login($user_id, $user_pw);
			$bPermit = true;
			if(is_null($objMember))
				$bPermit = false;
				
			if(!validUserPw($user_newPw)){
				$result->msg = lang("common.password_new_rule"); 
				$result->status = STATUS_FAIL;
			} else if(!$bPermit){
				$result->msg = lang("common.password_mistake_now"); 
				$result->status = STATUS_FAIL;                
			} else{
				$data = [
					'mb_pwd' => $user_newPw,
				];
				$this->modelMember->update($objMember->mb_fid, $data);

				$result->status = STATUS_SUCCESS; 
			}
		}

		echo json_encode($result);	
	}

	public function change_point()
	{
		
		$result = new \StdClass;
		if(!is_login())
		{
            $result->status = STATUS_LOGOUT;
        } else {
			$this->sess_action();                
			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);

			// $modelMoneyhist = new MoneyHist_Model();
			$reqAmount = intval($this->request->getVar('point'));
			if($reqAmount == 0)
				$reqAmount = $objMember->mb_point;

			$result->status = STATUS_FAIL;
			if($reqAmount > $objMember->mb_point){
				$result->status = STATUS_FAIL;
				$result->msg = lang("common.point_change_fail"); 
			} else if($reqAmount > 0 && $objMember->mb_point >= $reqAmount && $this->modelMember->updateAssets($objMember, $reqAmount, 0-$reqAmount, POINTCHANGE_EXCHANGE))
			{
				// $modelMoneyhist->registerPointToMoney($objMember, $reqAmount);
				
				if(intval($_ENV['app.slot'] ?? 0) == 5 && intval($_ENV['app.casino'] ?? 0) == 5){
					if(strlen($objMember->mb_treem_uid) > 0){
						$arrTreem = $this->libApiTreem->addBalance($objMember->mb_treem_uid, $reqAmount);

						if($arrTreem['status'] == 1 && array_key_exists('balance', $arrTreem)){
							if(!$this->modelMember->applyTreemPointChange($objMember, $reqAmount, $arrTreem['balance'])){
								writeLog("[change_point] TREEM money db update failed uid=".$objMember->mb_uid." amount=".$reqAmount." balance=".$arrTreem['balance']);
							}
						} else {
							writeLog("[change_point] TREEM addBalance failed uid=".$objMember->mb_uid." amount=".$reqAmount." result=".json_encode($arrTreem));
						}
					}
				}
				
				$result->status = STATUS_SUCCESS;
			}			

        }
		
		echo json_encode($result);

    }
		
	public function change_egg()
	{
		
		$result = new \StdClass;
		if(!is_login())
		{
            $result->status = STATUS_LOGOUT;
        } else {
			$this->sess_action();                
			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$iResult = $this->alltoGame($objMember);

			$result->status = STATUS_SUCCESS;
        }
		
		echo json_encode($result);

    }
	// public function change_acc(){
		
	// 	$result = new \StdClass;
	// 	if(!is_login())
	// 	{
	// 		$result->msg = lang("common.session_expired");
	// 		$result->status = STATUS_LOGOUT;
	// 	}
	// 	else {

	// 		$user_id = $this->session->user_id;
	// 		$bank_owner = trim($this->request->getPost('bank_owner'));
	// 		$bank_name = trim($this->request->getPost('bank_name'));
	// 		$bank_num = trim($this->request->getPost('bank_num'));
	// 		$bank_passwd = trim($this->request->getPost('bank_passwd'));

	// 		$objMember = $this->modelMember->getByUid($user_id, true);
	// 		$bPermit = true;
	// 		if(is_null($objMember)){
	// 			$result->msg = "회원정보가 유효하지 않습니다."; 
	// 			$result->status = STATUS_FAIL;
	// 		} else if($bank_passwd !== $objMember->mb_bank_pwd){
	// 			$result->msg = "출금비번이 틀림니다."; 
	// 			$result->status = STATUS_FAIL;
	// 		} else if($bank_owner == "" || $bank_name == "" || $bank_num == ""){
	// 			$result->msg = "계좌정보를 정확히 입력해 주세요."; 
	// 			$result->status = STATUS_FAIL;
	// 		} else {
	// 			$data = [
	// 				'mb_bank_name' => $bank_name,
	// 				'mb_bank_own' => $bank_owner,
	// 				'mb_bank_num' => $bank_num,
	// 			];
	// 			$this->modelMember->update($objMember->mb_fid, $data);

	// 			$result->status = STATUS_SUCCESS; 
	// 		}
	// 	}

	// 	echo json_encode($result);	
	// }

	public function change_lang()
	{
		
		$result = new \StdClass;
		$locale = $this->request->getVar('lang');
		$configApp = new \Config\App();
		if(!in_array($locale, $configApp->supportedLocales)){
			$locale = $configApp->defaultLocale;
			writeLog("defaultLocale=".$locale);
		}
		$this->session->set('lang', $locale);

		$result->status = STATUS_SUCCESS;
		
		echo json_encode($result);

    }

	public function register(){
		$this->setLanguage();
		$reqData['member_id'] = $this->request->getVar('userid');
		$reqData['password'] = $this->request->getVar('passwd');
		$reqData['nickname'] = $this->request->getVar('nickname');
		$reqData['bank_name'] = $this->request->getVar('bank_name');
		$reqData['name'] = $this->request->getVar('bank_owner');
		$reqData['account_number'] = $this->request->getVar('bank_account');
		$reqData['refund_password'] = $this->request->getVar('refund_password');
		$reqData['proposer'] = $this->request->getVar('agent_id');
		$reqData['contact'] = $this->request->getVar('phone');
		$reqData['ip'] = $this->request->getVar('ip');
		if(trim($reqData['ip']) < 1)
			$reqData['ip'] = $this->request->getIPAddress();

		$result = new \StdClass;
		$result->status = STATUS_FAIL;
		$iResult = RESULT_FAIL;

		$checkOk = validUserId($reqData['member_id']);
		if(!$checkOk){
			$result->msg = lang("common.id_rule");
		} else {
			$checkOk = validUserPw($reqData['password']);
			if(!$checkOk)
				$result->msg = lang("common.password_rule");
		}

		if($checkOk && array_key_exists('app.sess_act', $_ENV) && $_ENV['app.sess_act'] == 1){
			$objEmp = $this->modelMember->getByUid($reqData['proposer']);
			if(!is_null($objEmp)){
				$memConfModel = new MemConf_Model();
				$memConf = $memConfModel->getByMember($objEmp->mb_fid);
				if(!is_null($memConf) && $memConf->conf_num_2 == STATE_ACTIVE ){
					$checkOk = false;
					$result->msg = lang("common.recommender_nopermitted");
				}
			}
		}

		if($checkOk)
			$iResult = $this->modelMember->register($reqData);

		if($iResult == RESULT_OK){
			$result->status = STATUS_SUCCESS;
		} else if($iResult == RESULT_EXIST_ID) {
			$result->msg = lang("common.id_duplicated");
		} else if($iResult == RESULT_EXIST_NAME) {
			$result->msg = lang("common.nickname_duplicated");
		} else if($iResult == RESULT_EMP_ERROR) {
			$result->msg = lang("common.recommender_nopermit");
		} else {
			if(!property_exists($result, 'msg'))
				$result->msg = lang("common.signup_fail");
		}
		echo json_encode($result);

	}

	public function register_exchange(){
		$this->setLanguage();
		
		$reqData['c_price'] = intval($this->request->getPost("cash"));
		$reqData['bank_passwd'] = $this->request->getPost("bank_passwd");

		$result = new \StdClass;
		if(!is_login())
		{
			$result->msg = lang("common.session_expired");
            $result->status = STATUS_LOGOUT;
        } else {
			$this->sess_action();                
			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id, true);

			$reqData['c_price'] = intval($reqData['c_price']);
			$modelExchange = new Exchange_Model();
			$this->modelConfsite->readMemConf();

			$result->status = STATUS_SUCCESS;
			$bLimit = false;
			$strNow = date("Y-m-d H:i:s");        

			if(array_key_exists('app.tree', $_ENV) && intval($_ENV['app.tree']) == 1 ){
				$bLimit = true;

				$tmNow = time();
				
				$arrConf = $this->modelConfsite->getExchangePolicy();
				foreach($arrConf as $objConf){
					switch($objConf->conf_id){
						case CONF_CHARGE_MANUAL:	
							$confs = explode('#', $objConf->conf_idx);

							$exchanges = $modelExchange->last($objMember->mb_uid);

							if(count($exchanges) > 0 && count($confs) >= 2 && $confs[0] == 1 && floatval($confs[1]) > 0){
								writeLog("BankDelay Now=".$strNow.", lastExch=".$exchanges[0]->exchange_time_require);

								if(diffDt($strNow, $exchanges[0]->exchange_time_require) < floatval($confs[1]) * 3600){
									$result->status = STATUS_FAIL;
									$result->msg = langTo($this->session->lang, "withdrawal_delay", $confs[1]);
								}
							}
							break;
						case CONF_DISCHA_MANUAL:	
							$confs = explode('#', $objConf->conf_idx);

							if(count($confs) >= 3 && $confs[0] == 1 && strlen($confs[1]) > 3 && strlen($confs[2]) > 3 ){
								$strDate = date( 'Y-m-d', $tmNow );
								$strStart = $strDate." ".$confs[1];
								$strEnd = $strDate." ".$confs[2];

								writeLog("BankRest Now=".$strNow." Time=".$strStart."~".$strEnd);
								if($strNow >= $strStart || $strNow <= $strEnd){
									$result->status = STATUS_FAIL;
									$result->msg = lang("common.withdrawal_fail_bank");
								}
								
							}
							
							break;
						default:break;
					}
				}
				if($reqData['c_price'] % 10000 != 0){
					$result->status = STATUS_FAIL;
					$result->msg = lang("common.deposit_request_unit"); 
				} 
			}
			$sess = $this->modelSess->getByUid($objMember->mb_uid, false);

			if($bLimit && $result->status == STATUS_FAIL){
				$result->status = STATUS_FAIL;
			} else if($bLimit && $objMember->mb_state_delete == STATE_ACTIVE){
				$result->status = STATUS_FAIL;
				$result->msg = lang("common.withdrawal_cant");
			} else if($modelExchange->wait($user_id)){
				$result->status = STATUS_FAIL;
				$result->msg = lang("common.withdrawal_fail_wait");
			} else if($reqData['bank_passwd'] != $objMember->mb_bank_pwd) {
				$result->status = STATUS_FAIL;
				$result->msg = lang("common.withdrawal_fail_password");
			} else if($reqData['c_price'] > allMoney($objMember)){
				$result->status = STATUS_FAIL;
				$result->msg = lang("common.withdrawal_fail_money");
			} else if($reqData['c_price'] < 10000){
				$result->status = STATUS_FAIL;
				$result->msg = lang("common.withdrawal_fail_amount");
			} else if($_ENV['mem.withdeny_play'] && !is_null($sess)) {
				$result->status = STATUS_FAIL;
				$result->msg = lang("common.withdrawal_fail_app");
			} else if($_ENV['mem.delay_play'] > 0 && $_ENV['mem.withdeny_play'] &&  diffDt($strNow, $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
				$result->status = STATUS_FAIL;
				// writeLog($_ENV['mem.delay_play']." > ".diffDt($strNow, $objMember->mb_time_bet));
				$result->msg = langTo($this->session->lang, "withdrawal_deny", intval($_ENV['mem.delay_play']/60-diffDt($strNow, $objMember->mb_time_bet)/60)+1);
			} else {
				$iResult = 1;
				if($reqData['c_price'] > $objMember->mb_money){
					$iResult = $this->alltoGame($objMember, 0);
					if($iResult == 1)
						$objMember = $this->modelMember->getByUid($user_id, true);
				}

				if($iResult == 1){
					if($reqData['c_price'] > $objMember->mb_money){
						$result->msg = lang("common.game_fail")."(".PLAY_FAIL_TRANSFER.").";
						$result->status = STATUS_FAIL;
					} else if($reqData['c_price'] > 0 && $this->modelMember->updateAssets($objMember, 0-$reqData['c_price'], 0, MONEYCHANGE_EXCHANGE)){
						$data =[
							'exchange_emp_fid' => $objMember->mb_emp_fid,
							'exchange_mb_uid' => $objMember->mb_uid,
							'exchange_mb_phone' => $objMember->mb_phone,
							'exchange_money' => $reqData['c_price'],
							'exchange_time_require' => $strNow,
							'exchange_action_state' => STATE_ACTIVE,
							'exchange_bank_name' => $objMember->mb_bank_name,
							'exchange_bank_account' => $objMember->mb_bank_own,
							'exchange_bank_serial' => $objMember->mb_bank_num,
							'exchange_money_before' => allMoney($objMember),
							'exchange_money_after' => allMoney($objMember)-$reqData['c_price']
						];
		
						$modelExchange->register($data);

						$objInfo = new \StdClass;
						$objInfo->money = allMoney($objMember)-$reqData['c_price'];
						$objInfo->point = floor($objMember->mb_point);
				
						$result->data = $objInfo;
						$result->status = STATUS_SUCCESS;
						$result->msg = lang("common.withdrawal_ok");
						
					} else{
						$result->msg = lang("common.withdrawal_fail");
						$result->status = STATUS_FAIL;
					}
				} else{
					$result->msg = lang("common.game_fail").".(".PLAY_FAIL_TRANSFER.")";
					$result->status = STATUS_FAIL;
				}
				
			}
			
        }
		
		echo json_encode($result);
	}

	public function count_exchange()
	{
		$jsonData = $_REQUEST['json_'];
		$reqData = json_decode($jsonData, true);

		$result = new \StdClass;
		if(!is_login())
		{
            $result->status = STATUS_LOGOUT;		
        } else {
			$reqData['req_uid'] = $this->session->user_id;
			$modelExchange = new Exchange_Model();

			$count = $modelExchange->searchCount($reqData);

			$result->data = $count;
			$result->status = STATUS_SUCCESS;
        }
		
		echo json_encode($result);

    }


	public function page_exchange()
	{
		
		$reqData['start_at'] = $this->request->getVar('start_at');
		$reqData['end_at'] = $this->request->getVar('end_at');
		$reqData['count'] = $this->request->getVar('rowCount');
		$reqData['page'] = $this->request->getVar('page');

		$result = new \StdClass;
		if(!is_login())
		{
            $result->status = STATUS_LOGOUT;		
        } else {
			$this->sess_action();                
			$reqData['req_uid'] = $this->session->user_id;
			$modelExchange = new Exchange_Model();
			
			$result->totalRows = $modelExchange->searchCount($reqData);
			$result->rows = $modelExchange->searchList($reqData);

			$result->status = STATUS_SUCCESS;
        }
		
		echo json_encode($result);

    }
	
	
	public function delete_exchange()
	{
		$this->setLanguage();
		$result = new \StdClass;
		if(!is_login())
		{
			$result->msg = lang("common.session_expired");
            $result->status = STATUS_LOGOUT;		
        } else {
			$this->sess_action();                
			$reqData['req_uid'] = $this->session->user_id;
			$reqData['exchange_id'] = $this->request->getVar('idx');
			$modelExchange = new Exchange_Model();

			$bResult = $modelExchange->deleteByClient($reqData);
			
			if($bResult)
				$result->status = STATUS_SUCCESS;
			else {
				$result->msg = lang("common.delete_fail");
				$result->status = STATUS_FAIL;
			}
        }
		
		echo json_encode($result);
    }

	public function page_point()
	{
		$reqData['start_at'] = $this->request->getVar('start_at');
		$reqData['end_at'] = $this->request->getVar('end_at');
		$reqData['count'] = $this->request->getVar('rowCount');
		$reqData['page'] = $this->request->getVar('page');

		$result = new \StdClass;
		if(!is_login())
		{
            $result->status = STATUS_LOGOUT;		
        } else {
			$this->sess_action();                
			$reqData['req_uid'] = $this->session->user_id;
			$reqData['type'] = POINTCHANGE_EXCHANGE;
			$modelMoneyhist = new MoneyHist_Model();
			
			$result->totalRows = $modelMoneyhist->searchCount($reqData);
			$result->rows = $modelMoneyhist->searchList($reqData);

			$result->status = STATUS_SUCCESS;
        }
		
		echo json_encode($result);

    }

	public function delete_point()
	{
		$this->setLanguage();
		$result = new \StdClass;
		if(!is_login())
		{
			$result->msg = lang("common.session_expired");
            $result->status = STATUS_LOGOUT;		
        } else {
			$this->sess_action();                
			$reqData['req_uid'] = $this->session->user_id;
			$reqData['type'] = POINTCHANGE_EXCHANGE;
			$reqData['money_id'] = $this->request->getVar('idx');
			$modelMoneyhist = new MoneyHist_Model();

			$bResult = $modelMoneyhist->deleteByClient($reqData);
			
			if($bResult)
				$result->status = STATUS_SUCCESS;
			else {
				$result->msg = lang("common.delete_fail");
				$result->status = STATUS_FAIL;
			}
        }
		
		echo json_encode($result);
    }

	public function request_account3()
	{
		$this->setLanguage();
		$reqData['title'] = $this->request->getPost('title');
		$reqData['content'] = $this->request->getPost('content');

		$result = new \StdClass;
		if(!is_login())
		{
			$result->msg = lang("common.session_expired");
            $result->status = STATUS_LOGOUT;		
        } else {
			$this->sess_action();                

			$sAnswer = "<p> ".lang("common.deposit_account")." : &nbsp;";

			$libApiVacc = new ApiVacc_Lib();
			$arrResult['status'] = 1;

			if($libApiVacc->isActive()){
				$arrResult = $libApiVacc->getAccountInfo();
				if($arrResult['status'] == 1){
					$sAnswer .= "<b style='color:#ffff00'>".$arrResult['message']."</b>";
				} 
			} else {
				$objConf = $this->modelConfsite->find(CONF_CHARGEINFO);
				$arrInfo = explode("#", $objConf->conf_content);
				if(array_key_exists('app.sess_act', $_ENV) && $_ENV['app.sess_act'] == 1){
					$user_id = $this->session->user_id;
                	$objMember = $this->modelMember->getByUid($user_id);

					$memConfModel = new MemConf_Model();
					$memConf = $memConfModel->getByMember($objMember->mb_fid);
					$arrChargeInfo = [];
					if(!is_null($memConf) ){
						$arrChargeInfo = explode('#', $memConf->conf_str_5);
						if( count($arrChargeInfo) >= 3 && ( strlen($arrChargeInfo[0]) > 0 || strlen($arrChargeInfo[1]) > 0 || strlen($arrChargeInfo[2]) > 0) ){
							$arrInfo = $arrChargeInfo;
						}
					}
				}


				if(count($arrInfo) >= 1){
					if(strpos($arrInfo[0], 'http') !== false){
						$sAnswer .= "<a style='color:#ffff00' href='".trim($arrInfo[0])."' target='_blank'>".$arrInfo[0]."</a>";
					} else {
						$sAnswer .= "<b style='color:#ffff00'>".$arrInfo[0]."</b>";
					}
				}
				if(count($arrInfo) >= 2){
					$sAnswer .= "&nbsp;&nbsp;<b style='color:#ffff00'>".$arrInfo[1]."</b>";
				}
				if(count($arrInfo) >= 3){
					$sAnswer .= "&nbsp;&nbsp;<b style='color:#ffff00'>".$arrInfo[2]."</b>";
				} 
			}
			$sAnswer .= "</p>";

			if($arrResult['status'] == 0){
				$result->msg = lang("common.account_fail");
				$result->status = STATUS_FAIL;
			} else {
				$objConf = $this->modelConfsite->find(CONF_CHARGEMACRO);
				if(array_key_exists('app.lang', $_ENV) && intval($_ENV['app.lang']) > 0 ){
					if($this->session->lang == "cn"  && !isEmptyNotice($objConf->conf_content_cn)){
						$objConf->conf_content = $objConf->conf_content_cn;
					}
				}
				$sAnswer.= $objConf->conf_content;
	
				$data = [  
					'notice_type' => NOTICE_CUSTOMER,
					'notice_title' => $reqData['title'],
					'notice_content' => $reqData['content'],
					'notice_answer' => $sAnswer,
					'notice_mb_uid' => $this->session->user_id,
					'notice_read_count' => 1,
					'notice_state_active' => 1,
					'notice_time_create' => date("Y-m-d H:i:s")
				];
	
				$bResult = $this->modelNotice->registerNotice($data);
				
				if($bResult){
					$result->msg = lang("common.deposit_account_answer");
					$result->status = STATUS_SUCCESS;
				}
				else {
					$result->msg = lang("common.account_fail"); 
					$result->status = STATUS_FAIL;
				}
			}
			
        }
		
		echo json_encode($result);
	}

	public function register_charge(){
		
		$this->setLanguage();
		$reqData['c_price'] = intval($this->request->getPost('cash'));
		
		$result = new \StdClass;
		if(!is_login())
		{
			$result->msg = lang("common.session_expired");
            $result->status = STATUS_LOGOUT;
        } else {
			$this->sess_action();                
			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id, true);

			$modelCharge = new Charge_Model();

			if(array_key_exists('app.tree', $_ENV) && intval($_ENV['app.tree']) == 1 && $objMember->mb_state_delete == STATE_ACTIVE){
				$result->status = STATUS_FAIL;
				$result->msg = lang("common.deposit_cant"); 
			} else if(array_key_exists('app.tree', $_ENV) && intval($_ENV['app.tree']) == 1 && $reqData['c_price'] % 10000 != 0){
				$result->status = STATUS_FAIL;
				$result->msg = lang("common.deposit_request_unit"); 
			} else if($modelCharge->wait($user_id)){
				$result->status = STATUS_FAIL;
				$result->msg = lang("common.deposit_fail_wait");
			} else {
				$libApiVacc = new ApiVacc_Lib();

				$arrResult['status'] = 1;
				if($libApiVacc->isActive()){
					$arrResult = $libApiVacc->reqDeposit($objMember->mb_uid, $objMember->mb_bank_own, $reqData['c_price']);
				}

				if($arrResult['status'] == 0){
					if(array_key_exists('message', $arrResult))
						$result->msg = $arrResult['message'];
					else $result->msg = lang("common.deposit_fail");
					$result->status = STATUS_FAIL;
				} else {
					$data =[
						'charge_emp_fid' => $objMember->mb_emp_fid,
						'charge_mb_uid' => $objMember->mb_uid,
						'charge_mb_realname' => $objMember->mb_bank_own,
						'charge_mb_phone' => $objMember->mb_phone,
						'charge_money' => $reqData['c_price'],
						'charge_time_require' => date("Y-m-d H:i:s"),
						'charge_action_state' => STATE_ACTIVE,
						'charge_money_before' => allMoney($objMember)
	
					];
	
					if($modelCharge->register($data)){
						$result->status = STATUS_SUCCESS;
						$result->msg = lang("common.deposit_ok");
					}
					else{
						$result->msg = lang("common.deposit_fail");
						$result->status = STATUS_FAIL;
					}
				}

				
			}
			
        }
		
		echo json_encode($result);
	}


	public function count_charge()
	{
		$jsonData = $_REQUEST['json_'];
		$reqData = json_decode($jsonData, true);

		$result = new \StdClass;
		if(!is_login())
		{
            $result->status = STATUS_LOGOUT;		
        } else {
			$reqData['req_uid'] = $this->session->user_id;
			$modelCharge = new Charge_Model();

			$count = $modelCharge->searchCount($reqData);

			$result->data = $count;
			$result->status = STATUS_SUCCESS;
        }
		
		echo json_encode($result);

    }


	public function page_charge()
	{
		$reqData['start_at'] = $this->request->getVar('start_at');
		$reqData['end_at'] = $this->request->getVar('end_at');
		$reqData['count'] = $this->request->getVar('rowCount');
		$reqData['page'] = $this->request->getVar('page');

		$result = new \StdClass;
		if(!is_login())
		{
            $result->status = STATUS_LOGOUT;		
        } else {
			$this->sess_action();                
			$reqData['req_uid'] = $this->session->user_id;
			$modelCharge = new Charge_Model();

			$result->totalRows = $modelCharge->searchCount($reqData);
			$result->rows = $modelCharge->searchList($reqData);

			$result->status = STATUS_SUCCESS;
        }
		
		echo json_encode($result);
    }

	
	public function delete_charge()
	{
		$this->setLanguage();
		$result = new \StdClass;
		if(!is_login())
		{
			$result->msg = lang("common.session_expired");
            $result->status = STATUS_LOGOUT;		
        } else {
			$this->sess_action();                
			$reqData['req_uid'] = $this->session->user_id;
			$reqData['charge_id'] = $this->request->getVar('idx');
			$modelCharge = new Charge_Model();

			$bResult = $modelCharge->deleteByClient($reqData);
			
			if($bResult)
				$result->status = STATUS_SUCCESS;
			else {
				$result->msg = lang("common.delete_fail");
				$result->status = STATUS_FAIL;
			}
        }
		
		echo json_encode($result);
    }

	public function count_customer()
	{
		$jsonData = $_REQUEST['json_'];
		$reqData = json_decode($jsonData, true);

		$result = new \StdClass;
		if(!is_login())
		{
            $result->status = STATUS_LOGOUT;		
        } else {
			$reqData['send_uid'] = $this->session->user_id;

			$count = $this->modelNotice->searchCusCount($reqData);

			$result->data = $count;
			$result->status = STATUS_SUCCESS;
        }
		
		echo json_encode($result);

    }


	public function page_customer()
	{
		$reqData['start_at'] = $this->request->getVar('start_at');
		$reqData['end_at'] = $this->request->getVar('end_at');
		$reqData['count'] = $this->request->getVar('rowCount');
		$reqData['page'] = $this->request->getVar('page');

		$result = new \StdClass;
		if(!is_login())
		{
            $result->status = STATUS_LOGOUT;		
        } else {
			$this->sess_action();                
			$reqData['send_uid'] = $this->session->user_id;

			$arrNotice = $this->modelNotice->searchCusList($reqData);
			$result->unread = $this->modelNotice->unreadCus($reqData['send_uid']);
			foreach($arrNotice as $notice){
				if($notice->notice_state_active == STATE_ACTIVE){
					$reqData['notice_id'] = $notice->notice_fid;
					$this->modelNotice->readCus($reqData);
				}
			}

			$result->totalRows = $this->modelNotice->searchCusCount($reqData);
			$result->rows = $arrNotice;

			$result->status = STATUS_SUCCESS;
        }
		
		echo json_encode($result);

    }


	public function delete_customer()
	{
		$this->setLanguage();
		$result = new \StdClass;
		if(!is_login())
		{
			$result->msg = lang("common.session_expired");
            $result->status = STATUS_LOGOUT;		
        } else {
			$this->sess_action();                
			$reqData['send_uid'] = $this->session->user_id;
			$reqData['notice_id'] = $this->request->getVar('idx');
			$reqData['notice_type'] = NOTICE_CUSTOMER;

			$bResult = $this->modelNotice->deleteByClient($reqData);
			
			if($bResult)
				$result->status = STATUS_SUCCESS;
			else {
				$result->msg = lang("common.delete_fail");
				$result->status = STATUS_FAIL;
			}
				
        }
		
		echo json_encode($result);

    }

	
	public function write_customer()
	{
		$this->setLanguage();
		$result = new \StdClass;
		if(!is_login())
		{
			$result->msg = lang("common.session_expired");
            $result->status = STATUS_LOGOUT;		
        } else {
			$this->sess_action();                
			$data = [  
                'notice_type' => NOTICE_CUSTOMER,
                'notice_title' => $this->request->getVar('title'),
                'notice_content' => $this->request->getVar('contents'),
                'notice_mb_uid' => $this->session->user_id,
                'notice_time_create' => date("Y-m-d H:i:s")
            ];

			$bResult = $this->modelNotice->registerNotice($data);
			
			if($bResult)
				$result->status = STATUS_SUCCESS;
			else {
				$result->msg = lang("common.ask_fail");
				$result->status = STATUS_FAIL;
			}
        }
		echo json_encode($result);
    }

	public function count_message()
	{
		$jsonData = $_REQUEST['json_'];
		$reqData = json_decode($jsonData, true);

		$result = new \StdClass;
		if(!is_login())
		{
            $result->status = STATUS_LOGOUT;		
        } else {
			$reqData['send_uid'] = $this->session->user_id;

			$count = $this->modelNotice->searchMsgCount($reqData);

			$result->data = $count;
			$result->status = STATUS_SUCCESS;
        }
		
		echo json_encode($result);

    }


	public function page_message()
	{
		$reqData['start_at'] = $this->request->getVar('start_at');
		$reqData['end_at'] = $this->request->getVar('end_at');
		$reqData['count'] = $this->request->getVar('rowCount');
		$reqData['page'] = $this->request->getVar('page');

		$result = new \StdClass;
		if(!is_login())
		{
            $result->status = STATUS_LOGOUT;		
        } else {
			$this->sess_action();                
			$reqData['send_uid'] = $this->session->user_id;

			$result->totalRows = $this->modelNotice->searchMsgCount($reqData);
			$arrNotice = $this->modelNotice->searchMsgList($reqData);
            $result->unread = $this->modelNotice->unreadMsg($reqData['send_uid']);
			if(array_key_exists('app.lang', $_ENV) && intval($_ENV['app.lang']) > 0 ){
				if(!is_null($arrNotice) && count($arrNotice) > 0 && $this->session->lang != "ko"){
					foreach($arrNotice as $notice){
						if($this->session->lang == "cn"  && !isEmptyNotice($notice->notice_content_cn)){
							$notice->notice_title = $notice->notice_title_cn;
							$notice->notice_content = $notice->notice_content_cn;
						}
					}
				}
			}
			$result->rows = $arrNotice;
			$result->status = STATUS_SUCCESS;
        }
		
		echo json_encode($result);

    }


	public function delete_message()
	{
		$this->setLanguage();
		$result = new \StdClass;
		if(!is_login())
		{
			$result->msg = lang("common.session_expired");
            $result->status = STATUS_LOGOUT;		
        } else {
			$this->sess_action();                
			$reqData['send_uid'] = $this->session->user_id;
			$reqData['notice_id'] = $this->request->getVar('idx');

			$bResult = $this->modelNotice->deleteByClient($reqData);
			
			if($bResult)
				$result->status = STATUS_SUCCESS;
			else {
				$result->msg = lang("common.delete_fail");
				$result->status = STATUS_FAIL;
			}
				
        }
		
		echo json_encode($result);

    }


	public function check_message()
	{
		$result = new \StdClass;
		if(!is_login())
		{
            $result->status = STATUS_LOGOUT;		
        } else {
			$this->sess_action();                
			$reqData['send_uid'] = $this->session->user_id;
			$reqData['notice_id'] = $this->request->getVar('idx');

			$bResult = $this->modelNotice->readMsg($reqData);
			
			if($bResult)
				$result->status = STATUS_SUCCESS;
			else {
				$result->status = STATUS_FAIL;
			}
				
        }
		
		echo json_encode($result);
    }
	
	public function page_notice()
	{
		$reqData['start_at'] = $this->request->getVar('start_at');
		$reqData['end_at'] = $this->request->getVar('end_at');
		$reqData['count'] = $this->request->getVar('rowCount');
		$reqData['page'] = $this->request->getVar('page');

		$result = new \StdClass;
		if(!is_login())
		{
            $result->status = STATUS_LOGOUT;		
        } else {
			$this->sess_action();                

			$result->totalRows = $this->modelNotice->searchBodCount($reqData);
			$arrNotice = $this->modelNotice->searchBodList($reqData);

			if(array_key_exists('app.lang', $_ENV) && intval($_ENV['app.lang']) > 0 ){
				if(!is_null($arrNotice) && count($arrNotice) > 0 && $this->session->lang != "ko"){
					foreach($arrNotice as $notice){
						if($this->session->lang == "cn"  && !isEmptyNotice($notice->notice_content_cn) ){
							$notice->notice_title = $notice->notice_title_cn;
							$notice->notice_content = $notice->notice_content_cn;
						}
					}
				}
			}

			$result->rows = $arrNotice;
			$result->status = STATUS_SUCCESS;
        }
		
		echo json_encode($result);

    }

	public function round_current(){
		$jsonData = $_REQUEST['json_'];
		$reqData = json_decode($jsonData, true);
				
		if(!is_login())
		{
            $arrResult['status'] = STATUS_LOGOUT;		
        } else {
			$game_id = intval($reqData['game']);
			$objConf = $this->modelConfgame->find($game_id);
			if(!is_null($objConf)){
				$objConf->bet_confirm = $this->modelConfsite->isBetConfirm();
			}
			$arrResult['config'] = $objConf; 
			if(is_null($objConf)){
				$arrResult['status'] = STATUS_FAIL;		
			} else if($game_id == GAME_PBG_BALL || $game_id == GAME_EOS5_BALL || 
				$game_id == GAME_COIN5_BALL || $game_id == GAME_SPKN_BALL ){
				$arrRoundData = getPbRoundTimes($objConf);
				$arrRoundData['game'] = $game_id;
				$arrResult['data'] = $arrRoundData;
				$arrResult['status'] = STATUS_SUCCESS;		
			} else if($game_id == GAME_DHP_BALL){
				$arrRoundData = getPbRoundTimes($objConf, true);
				$arrRoundData['game'] = $game_id;
				$arrResult['data'] = $arrRoundData;
				$arrResult['status'] = STATUS_SUCCESS;		
			} 
			// else if($game_id == GAME_EVOL_BALL){
			// 	$modelRound = new Round_Model();
			// 	$reqData['game'] = $game_id;
			// 	$reqData['page'] = 1;
			// 	$reqData['count'] = 1;
			// 	$arrRound = $modelRound->searchList($reqData);
			// 	if(count($arrRound)>0){
			// 		$objRound = $arrRound[0]; 
			// 		$arrRoundData['round_no'] = $objRound->round_num;
			// 		$arrRoundData['round_date'] = $objRound->round_date;
			// 		$arrRoundData['round_current'] = date("Y-m-d H:i:s", time());
			// 		$arrRoundData['round_start'] = $objRound->round_time;

			// 		$tmRoundStart = strtotime($objRound->round_time);
			// 		$tmRoundEnd = strtotime("+".floor($objRound->round_period)." seconds", $tmRoundStart);
			// 		$arrRoundData['round_bet_end'] = date("Y-m-d H:i:s", $tmRoundEnd);

			// 		// $tmRoundEnd = strtotime("+".floor($objRound->round_period+20)." seconds", $tmRoundStart);
			// 		$arrRoundData['round_end'] = date("Y-m-d H:i:s", $tmRoundEnd);
			// 	}

			// 	$arrRoundData['game'] = $game_id;
			// 	$arrResult['data'] = $arrRoundData;
			// 	$arrResult['status'] = STATUS_SUCCESS;		
			// } 
			else if($game_id == GAME_BOGLE_BALL ){
				$arrRoundData = getBbRoundTimes($objConf);
				$arrRoundData['game'] = $game_id;
				$arrResult['data'] = $arrRoundData;
				$arrResult['status'] = STATUS_SUCCESS;		
			} else if($game_id == GAME_BOGLE_LADDER || $game_id == GAME_EOS3_BALL 
				|| $game_id == GAME_COIN3_BALL){
				$arrRoundData = getBsRoundTimes($objConf);
				$arrRoundData['game'] = $game_id;
				$arrResult['data'] = $arrRoundData;
				$arrResult['status'] = STATUS_SUCCESS;		
			} else {
				$arrResult['status'] = STATUS_FAIL;		
			}

        }
		
		echo json_encode($arrResult);
	}

	public function count_round(){
		$jsonData = $_REQUEST['json_'];
		$reqData = json_decode($jsonData, true);
				
		if(!is_login())
		{
            $arrResult['status'] = STATUS_LOGOUT;		
        } else {
			$modelRound = new Round_Model();
			$count = $modelRound->searchCount($reqData);
			$arrResult['data'] = $count;
			$arrResult['status'] = STATUS_SUCCESS;		
        }
		
		echo json_encode($arrResult);
	}


	public function page_round(){
		$jsonData = $_REQUEST['json_'];
		$reqData = json_decode($jsonData, true);
				
		if(!is_login())
		{
            $arrResult['status'] = STATUS_LOGOUT;		
        } else {
			$this->sess_action();                
			$game_id = intval($reqData['game']);
			$arrResult['game'] = $game_id;
			
			$modelRound = new Round_Model();
			$arrRound = $modelRound->searchList($reqData);
			$arrResult['data'] = $arrRound;
			$arrResult['status'] = STATUS_SUCCESS;		
		

        }
		
		echo json_encode($arrResult);
	}

	public function count_bet(){
		$jsonData = $_REQUEST['json_'];
		$reqData = json_decode($jsonData, true);
				
		if(!is_login())
		{
            $arrResult['status'] = STATUS_LOGOUT;		
        } else {
			$arrResult['game'] = intval($reqData['game']);
			$reqData['user_id'] = $this->session->user_id;	
			
			$modelBet = new Bet_Model();
			$count = $modelBet->searchCount($reqData);
			$arrResult['data'] = $count;
			$arrResult['status'] = STATUS_SUCCESS;		
		

        }
		
		echo json_encode($arrResult);
	}

	public function page_bet(){
		$jsonData = $_REQUEST['json_'];
		$reqData = json_decode($jsonData, true);
				
		if(!is_login())
		{
            $arrResult['status'] = STATUS_LOGOUT;		
        } else {
			$this->sess_action();                
			$arrResult['game'] = intval($reqData['game']);
			$reqData['user_id'] = $this->session->user_id;	
			
			$arrResult['cancel_enable'] = $this->modelConfsite->isBetCancelEnable();

			$modelBet = new Bet_Model();
			$arrBet = $modelBet->searchList($reqData);
			$arrResult['data'] = $arrBet;
			$arrResult['status'] = STATUS_SUCCESS;		
		

        }
		
		echo json_encode($arrResult);
	}

	

	function get_follow(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		
		if(is_login()) {

			$modelFollow = new Follow_Model();
			$user_id = $this->session->user_id;			
			$objUser = $this->modelMember->getByUid($user_id);
			$arrInfo = [
				'follow_id' => '',
				'follow_rate' => 0,
				'follow_stop' => 0
			];

			$objFollow = $modelFollow->getByUser($objUser->mb_fid); 
			if(!is_null($objFollow)){
				if($arrReqData['game'] == GAME_PBG_BALL ){
					$arrInfo['follow_id'] = $objFollow->fl_pb_uid;
					$arrInfo['follow_rate'] = $objFollow->fl_pb_rate;
					$arrInfo['follow_stop'] = $objFollow->fl_pb_stop;
				} else if($arrReqData['game'] == GAME_BOGLE_BALL){
					$arrInfo['follow_id'] = $objFollow->fl_bb_uid;
					$arrInfo['follow_rate'] = $objFollow->fl_bb_rate;
					$arrInfo['follow_stop'] = $objFollow->fl_bb_stop;
				} else if($arrReqData['game'] == GAME_BOGLE_LADDER){
					$arrInfo['follow_id'] = $objFollow->fl_bs_uid;
					$arrInfo['follow_rate'] = $objFollow->fl_bs_rate;
					$arrInfo['follow_stop'] = $objFollow->fl_bs_stop;
				} else if($arrReqData['game'] == GAME_EOS5_BALL){
					$arrInfo['follow_id'] = $objFollow->fl_e5_uid;
					$arrInfo['follow_rate'] = $objFollow->fl_e5_rate;
					$arrInfo['follow_stop'] = $objFollow->fl_e5_stop;
				} else if($arrReqData['game'] == GAME_EOS3_BALL){
					$arrInfo['follow_id'] = $objFollow->fl_e3_uid;
					$arrInfo['follow_rate'] = $objFollow->fl_e3_rate;
					$arrInfo['follow_stop'] = $objFollow->fl_e3_stop;
				} else if($arrReqData['game'] == GAME_COIN5_BALL){
					$arrInfo['follow_id'] = $objFollow->fl_c5_uid;
					$arrInfo['follow_rate'] = $objFollow->fl_c5_rate;
					$arrInfo['follow_stop'] = $objFollow->fl_c5_stop;
				} else if($arrReqData['game'] == GAME_COIN3_BALL){
					$arrInfo['follow_id'] = $objFollow->fl_c3_uid;
					$arrInfo['follow_rate'] = $objFollow->fl_c3_rate;
					$arrInfo['follow_stop'] = $objFollow->fl_c3_stop;
				} else if($arrReqData['game'] == GAME_SPKN_BALL){
					$arrInfo['follow_id'] = $objFollow->fl_sk_uid;
					$arrInfo['follow_rate'] = $objFollow->fl_sk_rate;
					$arrInfo['follow_stop'] = $objFollow->fl_sk_stop;
				} else if($arrReqData['game'] == GAME_DHP_BALL){
					$arrInfo['follow_id'] = $objFollow->fl_ev_uid;
					$arrInfo['follow_rate'] = $objFollow->fl_ev_rate;
					$arrInfo['follow_stop'] = $objFollow->fl_ev_stop;
				}
			}

			$arrResult['data'] = $arrInfo;
			$arrResult['status'] = STATUS_SUCCESS;
			echo json_encode($arrResult);
		}
		else{//logout		
			$arrResult['status'] = STATUS_LOGOUT;
			echo json_encode($arrResult);	
		}
	}

	function save_follow(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		
		if(is_login()) {
			$this->sess_action();                

			$modelFollow = new Follow_Model();
			$user_id = $this->session->user_id;			
			$objUser = $this->modelMember->getByUid($user_id);
			$arrInfo['fl_mb_fid'] = $objUser->mb_fid;
			$arrInfo['fl_update'] = date("Y-m-d H:i:s");
			if($arrReqData['game'] == GAME_PBG_BALL){
				$arrInfo['fl_pb_uid'] = $arrReqData['uid'];
				$arrInfo['fl_pb_rate'] = $arrReqData['rate'];
				$arrInfo['fl_pb_stop'] = $arrReqData['stop'];
				$modelFollow->saveByUser($arrInfo);
			} else if($arrReqData['game'] == GAME_BOGLE_BALL){
				$arrInfo['fl_bb_uid'] = $arrReqData['uid'];
				$arrInfo['fl_bb_rate'] = $arrReqData['rate'];
				$arrInfo['fl_bb_stop'] = $arrReqData['stop'];
				$modelFollow->saveByUser($arrInfo);
			} else if($arrReqData['game'] == GAME_BOGLE_LADDER){
				$arrInfo['fl_bs_uid'] = $arrReqData['uid'];
				$arrInfo['fl_bs_rate'] = $arrReqData['rate'];
				$arrInfo['fl_bs_stop'] = $arrReqData['stop'];
				$modelFollow->saveByUser($arrInfo);
			} else if($arrReqData['game'] == GAME_EOS5_BALL){
				$arrInfo['fl_e5_uid'] = $arrReqData['uid'];
				$arrInfo['fl_e5_rate'] = $arrReqData['rate'];
				$arrInfo['fl_e5_stop'] = $arrReqData['stop'];
				$modelFollow->saveByUser($arrInfo);
			} else if($arrReqData['game'] == GAME_EOS3_BALL){
				$arrInfo['fl_e3_uid'] = $arrReqData['uid'];
				$arrInfo['fl_e3_rate'] = $arrReqData['rate'];
				$arrInfo['fl_e3_stop'] = $arrReqData['stop'];
				$modelFollow->saveByUser($arrInfo);
			} else if($arrReqData['game'] == GAME_COIN5_BALL){
				$arrInfo['fl_c5_uid'] = $arrReqData['uid'];
				$arrInfo['fl_c5_rate'] = $arrReqData['rate'];
				$arrInfo['fl_c5_stop'] = $arrReqData['stop'];
				$modelFollow->saveByUser($arrInfo);
			} else if($arrReqData['game'] == GAME_COIN3_BALL){
				$arrInfo['fl_c3_uid'] = $arrReqData['uid'];
				$arrInfo['fl_c3_rate'] = $arrReqData['rate'];
				$arrInfo['fl_c3_stop'] = $arrReqData['stop'];
				$modelFollow->saveByUser($arrInfo);
			} else if($arrReqData['game'] == GAME_SPKN_BALL){
				$arrInfo['fl_sk_uid'] = $arrReqData['uid'];
				$arrInfo['fl_sk_rate'] = $arrReqData['rate'];
				$arrInfo['fl_sk_stop'] = $arrReqData['stop'];
				$modelFollow->saveByUser($arrInfo);
			} else if($arrReqData['game'] == GAME_DHP_BALL){
				$arrInfo['fl_ev_uid'] = $arrReqData['uid'];
				$arrInfo['fl_ev_rate'] = $arrReqData['rate'];
				$arrInfo['fl_ev_stop'] = $arrReqData['stop'];
				$modelFollow->saveByUser($arrInfo);
			}

			$arrResult['status'] = STATUS_SUCCESS;
			echo json_encode($arrResult);
		}
		else{//logout		
			$arrResult['status'] = STATUS_LOGOUT;
			echo json_encode($arrResult);	
		}
	}

	public function betting(){

		$jsonData = $_REQUEST['json_'];
		$arrBetData = json_decode($jsonData, true);
		$this->setLanguage();
		
		if(is_login()) {
			$this->sess_action();                
			// $modelMoneyhist = new MoneyHist_Model();
			$modelReward = new Reward_Model();

			$user_id = $this->session->user_id;			
			$objUser = $this->modelMember->getByUid($user_id);
			$objUser->emp_state_active = STATE_ACTIVE;

			if(!$this->modelMember->isPermitMember($objUser, $arrBetData['game'])){
				$objUser->emp_state_active = STATE_DISABLE;
			}

			//Check maintain
			if($this->modelConfsite->IsMaintain()) {
				$objUser->emp_state_active = STATE_DISABLE;
			}

			$objConfig = $this->modelConfgame->find($arrBetData['game']);
			$this->modelConfsite->readBetConf();

			$iResult = 0;
			$iBetId = 0;
			if(is_null($objConfig) || $objUser->emp_state_active == STATE_DISABLE){
				$iResult = 2;
			} else if($arrBetData['amount'] > $objUser->mb_money && $this->alltoGame($objUser) != 1){
				$iResult = 8;			//Fail in transfering money
			} else {
				$modelBet = new Bet_Model();
				$modelRound = new Round_Model();				
				if($arrBetData['game'] == GAME_PBG_BALL){						//PBG ball 
					$arrRoundData = getPbRoundTimes($objConfig);
					$iMoneyType = MONEYCHANGE_BET_PB;
				} else if($arrBetData['game'] == GAME_DHP_BALL){						//PBG ball 
					$arrRoundData = getPbRoundTimes($objConfig, true);
					$iMoneyType = MONEYCHANGE_BET_DH;
				} 
				// else if($arrBetData['game'] == GAME_EVOL_BALL){			//DH ball
				// 	$reqData['game'] = $arrBetData['game'];
				// 	$reqData['page'] = 1;
				// 	$reqData['count'] = 1;
				// 	$arrRound = $modelRound->searchList($reqData);
				// 	if(count($arrRound)>0){
				// 		$objRound = $arrRound[0]; 
				// 		$arrRoundData['round_no'] = $objRound->round_num;
				// 		$arrRoundData['round_date'] = $objRound->round_date;
				// 		$arrRoundData['round_current'] = date("Y-m-d H:i:s", time());
				// 		$arrRoundData['round_start'] = $objRound->round_time;

				// 		$tmRoundStart = strtotime($objRound->round_time);
				// 		$tmRoundEnd = strtotime("+".floor($objRound->round_period)." seconds", $tmRoundStart);
				// 		$arrRoundData['round_bet_end'] = date("Y-m-d H:i:s", $tmRoundEnd);

				// 		// $tmRoundEnd = strtotime("+".floor($objRound->round_period+20)." seconds", $tmRoundStart);
				// 		$arrRoundData['round_end'] = date("Y-m-d H:i:s", $tmRoundEnd);
				// 	}
				// 	$iMoneyType = MONEYCHANGE_BET_EB;
				// } 
				else if($arrBetData['game'] == GAME_BOGLE_BALL){				//Bogle Powerball 
					$arrRoundData = getBbRoundTimes($objConfig);
					$iMoneyType = MONEYCHANGE_BET_BB;
				} else if($arrBetData['game'] == GAME_BOGLE_LADDER){			//Bogleladder
					$arrRoundData = getBsRoundTimes($objConfig);
					$iMoneyType = MONEYCHANGE_BET_BS;
				} else if($arrBetData['game'] == GAME_EOS5_BALL){				//EOS5M
					$arrRoundData = getPbRoundTimes($objConfig);
					$iMoneyType = MONEYCHANGE_BET_EO5;
				} else if($arrBetData['game'] == GAME_EOS3_BALL){				//EOS3M
					$arrRoundData = getBsRoundTimes($objConfig);
					$iMoneyType = MONEYCHANGE_BET_EO3;
				} else if($arrBetData['game'] == GAME_COIN5_BALL){				//COIN5M
					$arrRoundData = getPbRoundTimes($objConfig);
					$iMoneyType = MONEYCHANGE_BET_CO5;
				} else if($arrBetData['game'] == GAME_COIN3_BALL){				//COIN3M
					$arrRoundData = getBsRoundTimes($objConfig);
					$iMoneyType = MONEYCHANGE_BET_CO3;
				} else if($arrBetData['game'] == GAME_SPKN_BALL){				//Keno Powerball
					$arrRoundData = getPbRoundTimes($objConfig);
					$iMoneyType = MONEYCHANGE_BET_SK;
				}
				//check condition
				if(!is_null($modelBet)){
					$nMode = (int)($arrBetData['mode']);
					if(array_key_exists('bet.n2p_4en', $_ENV) && $_ENV['bet.n2p_4en'] && $nMode >= 31 && $nMode <= 38) {
						$arrRoundData['game'] = $arrBetData['game'];
						$arrStatis = $modelBet->getBetStatist($objUser, $arrRoundData);
						// writeLog("N2P Bet Statist = ".count($arrStatis));
						if(!isEnableN2pBet($arrStatis, $nMode)){
							$iResult = 9;
							$modelBet = null;
						}
					}
				}

				if(!is_null($modelBet)){
					
					$arrRoundInfo = $modelRound->gets(1);
					$arrBetData['roundid'] = count($arrRoundInfo) == 0 ? 1 : (int)($arrRoundInfo[0]->round_fid) + 1;
					
					$iResult = isEnableBet($arrBetData, $objUser, $objConfig, $arrRoundData);
					if($iResult == 1){
						// $arrEmpRatio = $this->modelMember->getEmployeeRatio($objUser, $arrBetData['amount'], $arrBetData['game'], $arrBetData['mode']);
						//Success in Betting
						if($this->modelMember->updateAssets($objUser, 0-$arrBetData['amount'], 0, $iMoneyType)){
							$iBetId = $modelBet->register($arrBetData, $objUser);
							// $modelMoneyhist->registerBet($objUser, $arrBetData, $iMoneyType);
						}
					}
				}
			}
									
			
			if($iResult == 1 && $iBetId > 0){			
				// $this->modelMember->updateRewards($arrEmpRatio);			//Add Point 
				// $modelReward->register($arrBetData['game'], $iBetId, $arrEmpRatio);
			}

			$arrResult['data'] = $iResult;
			if($iResult == 1 && $iBetId > 0){
				$arrResult['status'] = STATUS_SUCCESS;	
				$arrResult['data'] = $iBetId;			
			}
			else if($iResult == 2 || $iResult == 3){
				$arrResult['status'] = "stop";
				$arrResult['msg'] = lang("common.bet_block");
			} else if($iResult == 4){
				$arrResult['status'] = STATUS_FAIL;
				$arrResult['msg'] = lang("common.bet_min_msg");
			} else if($iResult == 5){
				$arrResult['status'] = STATUS_FAIL;
				$arrResult['msg'] = lang("common.bet_max_msg");
			} else if($iResult == 6 || $iResult == 8){
				$arrResult['status'] = STATUS_FAIL;
				$arrResult['msg'] = lang("common.bet_amount_exceed");
			} else if($iResult == 7){
				$arrResult['status'] = STATUS_FAIL;
				$arrResult['msg'] = lang("common.win_max_exceed");
			} else if($iResult == 9){
				$arrResult['status'] = STATUS_FAIL;
				$arrResult['msg'] = lang("common.bet_hole");
			}
			else{
				$arrResult['status'] = STATUS_FAIL;
				$arrResult['msg'] = lang("common.bet_fail");
			}	
				

			echo json_encode($arrResult);
		}
		else{//logout		
			
			$arrResult['status'] = STATUS_LOGOUT;
			echo json_encode($arrResult);	
		}
	}

	
	public function bet_cancel(){
		$this->setLanguage();

		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		
		if(is_login()) {
			$this->sess_action();                
			// $modelMoneyhist = new MoneyHist_Model();
			$modelReward = new Reward_Model();

			$user_id = $this->session->user_id;			
			$objUser = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($arrReqData['game']);
			
			$cancelEnable = $this->modelConfsite->isBetCancelEnable();

			$iChangeType = 0;
			$modelBet = null;
			if(!$cancelEnable || is_null($objConfig) || !array_key_exists('fid', $arrReqData) || !array_key_exists('game', $arrReqData)){
				$modelBet = null;
			}
			else if($arrReqData['game'] == GAME_PBG_BALL){					//PBG 
				$modelBet = new Bet_Model();
				$arrRoundData = getPbRoundTimes($objConfig);
				$iChangeType = MONEYCHANGE_DENY_PB;
			} else if($arrReqData['game'] == GAME_DHP_BALL){				//DHP 
				$modelBet = new Bet_Model();
				$arrRoundData = getPbRoundTimes($objConfig, true);
				$iChangeType = MONEYCHANGE_DENY_DH;
			} else if($arrReqData['game'] == GAME_BOGLE_BALL){					//Bogle Powerball 
				$modelBet = new Bet_Model();
				$arrRoundData = getBbRoundTimes($objConfig);
				$iChangeType = MONEYCHANGE_DENY_BB;
			} else if($arrReqData['game'] == GAME_BOGLE_LADDER){				//Bogleladder
				$modelBet = new Bet_Model();
				$arrRoundData = getBsRoundTimes($objConfig);
				$iChangeType = MONEYCHANGE_DENY_BS;
			} else if($arrReqData['game'] == GAME_EOS5_BALL || $arrReqData['game'] == GAME_COIN5_BALL){				//EOS5
				$modelBet = new Bet_Model();
				$arrRoundData = getPbRoundTimes($objConfig);
				$iChangeType = $arrReqData['game'] == GAME_EOS5_BALL ? MONEYCHANGE_DENY_EO5 : MONEYCHANGE_DENY_CO5;
			} else if($arrReqData['game'] == GAME_SPKN_BALL){					//Keno ball
				$modelBet = new Bet_Model();
				$arrRoundData = getPbRoundTimes($objConfig);
				$iChangeType = MONEYCHANGE_DENY_SK;
			} else if($arrReqData['game'] == GAME_EOS3_BALL || $arrReqData['game'] == GAME_COIN3_BALL){				//EOS3
				$modelBet = new Bet_Model();
				$arrRoundData = getBsRoundTimes($objConfig);
				$iChangeType = $arrReqData['game'] == GAME_EOS3_BALL ? MONEYCHANGE_DENY_EO3 : MONEYCHANGE_DENY_CO3;
			}

			$iResult = 0;
			if(!is_null($modelBet)){
				$objBet = $modelBet->find($arrReqData['fid']);
				if(is_null($objBet) || $objBet->bet_mb_uid !== $user_id ){
					$iResult = 2;		//Error of Bet Id
				} else if($objBet->bet_state != 1){				//Finish Account
					$iResult = 1;
				} else if($arrReqData['game'] != GAME_EVOL_BALL && $objBet->bet_round_no != $arrRoundData['round_no']){
					$iResult = 3;		//Error of Bet Id
				} else if($arrReqData['game'] != GAME_EVOL_BALL && $objBet->bet_time < $arrRoundData['round_start'] || $objBet->bet_time > $arrRoundData['round_bet_end']){
					$iResult = 4;		//Error of Bet Id
				} else if($arrReqData['game'] != GAME_EVOL_BALL && !isEnableBetTime($arrRoundData)){
					$iResult = 5;		//Can't Cancel
				} else {
					if($modelBet->delete($objBet->bet_fid)){
						if( $objBet->bet_money > 0 && $this->modelMember->updateAssets($objUser, $objBet->bet_money, 0, $iChangeType)){
							// $modelReward->deleteByBetId($objBet->bet_game, $objBet->bet_fid);
							// $modelMoneyhist->register($objUser, $objBet->bet_money, $iChangeType);
						}
						$iResult = 1;		
					}
				}
			}

			$arrResult['data'] = $iResult;	

			if($iResult == 1){
				$arrResult['status'] = STATUS_SUCCESS;	
			} else if($iResult == 5){
				$arrResult['status'] = STATUS_FAIL;	
				$arrResult['msg'] = lang("common.bet_cancel_not");	
			} else {
				$arrResult['status'] = STATUS_FAIL;	
				$arrResult['msg'] = lang("common.reject");	
			}
			echo json_encode($arrResult);	

		}
		else{//logout		
			
			$arrResult['status'] = STATUS_LOGOUT;
			echo json_encode($arrResult);	
		}
	}

	public function bet_follow(){

		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		
		if(is_login()) {
			// $modelMoneyhist = new MoneyHist_Model();
			$modelReward = new Reward_Model();
			$modelFollow = new Follow_Model();

			$user_id = $this->session->user_id;			
			$objUser = $this->modelMember->getByUid($user_id);
			
			$arrFollow = $modelFollow->getFollower($arrReqData['game'], $user_id);
			
			$fids = [];
			foreach($arrFollow as $follow){
				$fids[] = $follow->fl_mb_fid;
			}

			$arrMember = $this->modelMember->getByFids($fids);
			$objConfig = $this->modelConfgame->find($arrReqData['game']);

			$iResult = 0;
			$iBetId = 0;
			//서버 점검상태 확인
			if($this->modelConfsite->IsMaintain()) {
				$iResult = 2;
			} else if(count($arrMember) < 1){
				$iResult = 1;
			} else {

				$arrBetData['game'] = $arrReqData['game'];
				$objBet = null;
				$iMoneyChangeType = 0;
				$modelBet = new Bet_Model();
				$modelRound = new Round_Model();	
				$objBet = $modelBet->find($arrReqData['betId']);

				if($arrBetData['game'] == GAME_PBG_BALL){					//PBG 
					$arrRoundData = getPbRoundTimes($objConfig);
					$iMoneyChangeType = MONEYCHANGE_BET_PB;
				} else if($arrBetData['game'] == GAME_DHP_BALL){					//DHP 
					$arrRoundData = getPbRoundTimes($objConfig, true);
					$iMoneyChangeType = MONEYCHANGE_BET_DH;
				} 
				// else if($arrBetData['game'] == GAME_EVOL_BALL){					//Evo 
				// 	$reqData['game'] = $arrBetData['game'];
				// 	$reqData['page'] = 1;
				// 	$reqData['count'] = 1;
				// 	$arrRound = $modelRound->searchList($reqData);
				// 	if(count($arrRound)>0){
				// 		$objRound = $arrRound[0]; 
				// 		$arrRoundData['round_no'] = $objRound->round_num;
				// 		$arrRoundData['round_date'] = $objRound->round_date;
				// 		$arrRoundData['round_current'] = date("Y-m-d H:i:s", time());
				// 		$arrRoundData['round_start'] = $objRound->round_time;

				// 		$tmRoundStart = strtotime($objRound->round_time);
				// 		$tmRoundEnd = strtotime("+".floor($objRound->round_period)." seconds", $tmRoundStart);
				// 		$arrRoundData['round_bet_end'] = date("Y-m-d H:i:s", $tmRoundEnd);

				// 		// $tmRoundEnd = strtotime("+".floor($objRound->round_period+20)." seconds", $tmRoundStart);
				// 		$arrRoundData['round_end'] = date("Y-m-d H:i:s", $tmRoundEnd);
				// 	}
				// 	$iMoneyChangeType = MONEYCHANGE_BET_EB;
				// } 
				else if($arrBetData['game'] == GAME_BOGLE_BALL){					//Boggle 
					$arrRoundData = getBbRoundTimes($objConfig);
					$iMoneyChangeType = MONEYCHANGE_BET_BB;
				} else if($arrBetData['game'] == GAME_BOGLE_LADDER){					//Boggle 
					$arrRoundData = getBsRoundTimes($objConfig);
					$iMoneyChangeType = MONEYCHANGE_BET_BS;
				} else if($arrBetData['game'] == GAME_EOS5_BALL){					//Eos5 
					$arrRoundData = getPbRoundTimes($objConfig);
					$iMoneyChangeType = MONEYCHANGE_BET_EO5;
				} else if($arrBetData['game'] == GAME_EOS3_BALL){					//Eos3 
					$arrRoundData = getBsRoundTimes($objConfig);
					$iMoneyChangeType = MONEYCHANGE_BET_EO3;
				} else if($arrBetData['game'] == GAME_COIN5_BALL){					//Coin5 
					$arrRoundData = getPbRoundTimes($objConfig);
					$iMoneyChangeType = MONEYCHANGE_BET_CO5;
				} else if($arrBetData['game'] == GAME_COIN3_BALL){					//Coin3 
					$arrRoundData = getBsRoundTimes($objConfig);
					$iMoneyChangeType = MONEYCHANGE_BET_CO3;
				} else if($arrBetData['game'] == GAME_SPKN_BALL){					//Keno 
					$arrRoundData = getPbRoundTimes($objConfig);
					$iMoneyChangeType = MONEYCHANGE_BET_SK;
				} 
				
				if(is_null($objBet)){
					$iResult = 0;
				} else {
					writeLog("followCnt=".count($arrMember)." MoneyType=".$iMoneyChangeType);
					
					$arrBetData['roundno'] = $objBet->bet_round_no;
					$arrBetData['roundid'] = $objBet->bet_round_fid;
					$arrBetData['mode'] = $objBet->bet_mode;
					$arrBetData['target'] = $objBet->bet_target;
					$arrBetData['amount'] = $objBet->bet_money;
					$arrBetData['ratio'] = $objBet->bet_ratio;
					$arrBetData['fol_fid'] = $objBet->bet_fid;
					$arrBetData['fol_uid'] = $objUser->mb_fid;

					foreach($arrMember as $member){
						$member->emp_state_active = STATE_ACTIVE;
						if(!$this->modelMember->isPermitMember($member, $arrBetData['game'])){
							continue;
						}
						$rate = findFollowRate($arrBetData['game'], $arrFollow, $member->mb_fid);
						$arrBetData['amount'] = intval($objBet->bet_money * $rate);

						$iResult = isEnableBet($arrBetData, $member, $objConfig, $arrRoundData);
						if($iResult == 1){
							// $arrEmpRatio = $this->modelMember->getEmployeeRatio($member, $arrBetData['amount'], $arrBetData['game'], $arrBetData['mode']);
							//Add Money hisotry and Update User money
							if($this->modelMember->updateAssets($member, 0-$arrBetData['amount'], 0, $iMoneyChangeType)){
								$iBetId = $modelBet->register($arrBetData, $member);
								// $modelMoneyhist->registerBet($member, $arrBetData, $iMoneyChangeType);
							}
						}

						if($iResult == 1 && $iBetId > 0){	
							// $this->modelMember->updateRewards($arrEmpRatio);
							// $modelReward->register($arrBetData['game'], $iBetId, $arrEmpRatio);
						}
								

					}	

				}
					
			}

			$arrResult['data'] = $iResult;
			if($iResult == 1 && $iBetId > 0){
				$arrResult['status'] = STATUS_SUCCESS;				
			}
			else if($iResult == 2 || $iResult == 3){
				$arrResult['status'] = "stop";
				// $arrResult['msg'] = "베팅이 차단되었습니다.";
			} else if($iResult == 4){
				$arrResult['status'] = STATUS_FAIL;
				// $arrResult['msg'] = "최소베팅금액보다 작은 금액으로는 베팅하실 수 없습니다.";
			} else if($iResult == 5){
				$arrResult['status'] = STATUS_FAIL;
				// $arrResult['msg'] = "최대베팅금액을 초과하셨습니다.";
			} else if($iResult == 6){
				$arrResult['status'] = STATUS_FAIL;
				// $arrResult['msg'] = "베팅금액이 보유금액을 초과하셨습니다.";
			} else if($iResult == 7){
				$arrResult['status'] = STATUS_FAIL;
				// $arrResult['msg'] = "최대적중금액을 초과하셨습니다.";
			}
			else{
				$arrResult['status'] = STATUS_FAIL;
				$arrResult['msg'] = "베팅이 실패되었습니다.";
			}	

			echo json_encode($arrResult);
		}
		else{//logout		
			
			$arrResult['status'] = STATUS_LOGOUT;
			echo json_encode($arrResult);	
		}
	}

	
	public function follow_cancel(){

		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		
		if(is_login()) {
			// $modelMoneyhist = new MoneyHist_Model();
			$modelReward = new Reward_Model();

			$user_id = $this->session->user_id;			
			$objUser = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($arrReqData['game']);
			
			$cancelEnable = $this->modelConfsite->isBetCancelEnable();
			$modelBet = new Bet_Model();

			$iChangeType = 0;
			if(!$cancelEnable || is_null($objConfig) || !array_key_exists('fid', $arrReqData) || !array_key_exists('game', $arrReqData)){
				$modelBet = null;
			}
			else if($arrReqData['game'] == GAME_PBG_BALL){					//PBG 
				$iChangeType = MONEYCHANGE_DENY_PB;
			} else if($arrReqData['game'] == GAME_DHP_BALL){					//DHP 
				$iChangeType = MONEYCHANGE_DENY_DH;
			} else if($arrReqData['game'] == GAME_BOGLE_BALL){					//Bogle Powerball 
				$iChangeType = MONEYCHANGE_DENY_BB;
			} else if($arrReqData['game'] == GAME_BOGLE_LADDER){				//Bogleladder
				$iChangeType = MONEYCHANGE_DENY_BS;
			} else if($arrReqData['game'] == GAME_EOS5_BALL){				//EOS5M
				$iChangeType = MONEYCHANGE_DENY_EO5;
			} else if($arrReqData['game'] == GAME_EOS3_BALL){				//EOS3M
				$iChangeType = MONEYCHANGE_DENY_EO3;
			} else if($arrReqData['game'] == GAME_COIN5_BALL){				//Coin5M
				$iChangeType = MONEYCHANGE_DENY_CO5;
			} else if($arrReqData['game'] == GAME_COIN3_BALL){				//Coin3M
				$iChangeType = MONEYCHANGE_DENY_CO3;
			} else if($arrReqData['game'] == GAME_SPKN_BALL){				//Keno
				$iChangeType = MONEYCHANGE_DENY_SK;
			} else $modelBet = null;

			$iResult = 0;
			if(!is_null($modelBet)){
				$objBet = $modelBet->find($arrReqData['fid']);
				if(!is_null($objBet) ){
					$iResult = 2;		//Error of Bet Id
				} else {
					$arrBet = $modelBet->followBet($arrReqData['fid']);

					foreach($arrBet as $objBet){
						if($objBet->bet_state != 1)				//Finsih Account
							continue;
						if($modelBet->delete($objBet->bet_fid)){
							$objMember = $this->modelMember->getByUid($objBet->bet_mb_uid);
							if(!is_null($objMember) && $objBet->bet_money > 0 && $this->modelMember->updateAssets($objMember, $objBet->bet_money, 0, $iChangeType)){
								// $modelMoneyhist->register($objMember, $objBet->bet_money, $iChangeType);
							}
						}	
					}
					
					$iResult = 1;

				}
			}

			$arrResult['data'] = $iResult;	

			if($iResult == 1){
				$arrResult['status'] = STATUS_SUCCESS;	
			} else if($iResult == 5){
				$arrResult['status'] = STATUS_FAIL;	
				$arrResult['msg'] = "베팅을 취소할수 없습니다.";	
			} else {
				$arrResult['status'] = STATUS_FAIL;	
				$arrResult['msg'] = "거절되었습니다.";	
			}
			echo json_encode($arrResult);	

		}
		else{//logout		
			
			$arrResult['status'] = STATUS_LOGOUT;
			echo json_encode($arrResult);	
		}
	}

	public function config(){

		$objConf = $this->modelConfgame->find(GAME_SPKN_BALL);
		$arrConf1 = array($objConf->game_ratio_1, $objConf->game_ratio_2, $objConf->game_ratio_3, $objConf->game_ratio_4);

		$objConf = $this->modelConfgame->find(GAME_DHP_BALL);
		$arrConf2 = array($objConf->game_ratio_1, $objConf->game_ratio_2, $objConf->game_ratio_3);
		
		$objConf = $this->modelConfgame->find(GAME_BOGLE_BALL);
		$arrConf3 = array($objConf->game_ratio_1, $objConf->game_ratio_2, $objConf->game_ratio_3, $objConf->game_ratio_4);
		
		$objConf = $this->modelConfgame->find(GAME_BOGLE_LADDER);
		$arrConf4 = array($objConf->game_ratio_1, $objConf->game_ratio_2, $objConf->game_ratio_3);

		$objConf = $this->modelConfgame->find(GAME_EOS5_BALL);
		$arrConf5 = array($objConf->game_ratio_1, $objConf->game_ratio_2, $objConf->game_ratio_3, $objConf->game_ratio_4);

		$objConf = $this->modelConfgame->find(GAME_EOS3_BALL);
		$arrConf6 = array($objConf->game_ratio_1, $objConf->game_ratio_2, $objConf->game_ratio_3, $objConf->game_ratio_4);

		$configData = array($arrConf1, $arrConf2, $arrConf3, $arrConf4, $arrConf5, $arrConf6);
		$objResult = new \StdClass;
		$objResult->data = $configData;			
		$objResult->status = STATUS_SUCCESS;
	
		echo json_encode($objResult);

	}

	public function balance(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		
		$objResult = new \StdClass;
		if(!array_key_exists('game', $arrReqData) || empty($arrReqData['game'])){
			$objResult->status = STATUS_FAIL;
		} else {

			$modelBet = null;
			$modelBet = new Bet_Model();
			if($arrReqData['game'] == 1){	
				$objConfig = $this->modelConfgame->find(GAME_PBG_BALL);
				$arrRoundData = getPbRoundTimes($objConfig);
			} else if($arrReqData['game'] == 2){	
				$objConfig = $this->modelConfgame->find(GAME_DHP_BALL);
				$arrRoundData = getPbRoundTimes($objConfig, true);
			} else if($arrReqData['game'] == 3){	
				$objConfig = $this->modelConfgame->find(GAME_BOGLE_BALL);
				$arrRoundData = getBbRoundTimes($objConfig);
			} else if($arrReqData['game'] == 4){	
				$objConfig = $this->modelConfgame->find(GAME_BOGLE_LADDER);
				$arrRoundData = getBsRoundTimes($objConfig);
			} else if($arrReqData['game'] == 5 ){	
				$objConfig = $this->modelConfgame->find(GAME_EOS5_BALL);
				$arrRoundData = getPbRoundTimes($objConfig);
			} else if($arrReqData['game'] == 6){	
				$objConfig = $this->modelConfgame->find(GAME_EOS3_BALL);
				$arrRoundData = getBsRoundTimes($objConfig);
			} else if($arrReqData['game'] == 7 ){	
				$objConfig = $this->modelConfgame->find(GAME_COIN5_BALL);
				$arrRoundData = getPbRoundTimes($objConfig);
			} else if($arrReqData['game'] == 8){	
				$objConfig = $this->modelConfgame->find(GAME_COIN3_BALL);
				$arrRoundData = getBsRoundTimes($objConfig);
			} else if($arrReqData['game'] == 9){	
				$objConfig = $this->modelConfgame->find(GAME_SPKN_BALL);
				$arrRoundData = getBsRoundTimes($objConfig);
			} else $modelBet = null;

			if(is_null($modelBet)){
				$objResult->status = STATUS_FAIL;
			} else {
				if($this->modelConfsite->IsGamePerFull()){
					$objConfig = null;
				}

				$arrRoundData['game'] = $arrReqData['game'];
				$data['roundno'] = $arrRoundData['round_no'];
				$data['balance'] = $modelBet->getBetSumByMode($arrRoundData, $objConfig);

				$objResult->data = $data;
				$objResult->status = STATUS_SUCCESS;
			}
		}
		echo json_encode($objResult);
	}

	public function egginfo(){
		$result = new \StdClass;
		if(!is_login())
		{
            $result->status = STATUS_LOGOUT;
		} else {
			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id, true);
			if(array_key_exists('app.reqEg', $_ENV) && $_ENV['app.reqEg'] == 1 ){
				if(!is_null($objMember))
					$this->allEgg($objMember);
			} 
			$result->status = STATUS_SUCCESS;
		}
		echo json_encode($result);

	}

	public function myinfo(){

		$result = new \StdClass;
		if(!is_login(true))
		{
			$result->status = STATUS_LOGOUT;
		}
		else {

			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id, true);
			$sess_id = $this->session->session_id;

			$objEmp = null;
			if($objMember->mb_emp_fid > 0)
				$objEmp = $this->modelMember->getByFid($objMember->mb_emp_fid);

			$userInfo = getUserInfo($objMember, $objEmp);
			$result->data = $userInfo;
			$result->status = STATUS_SUCCESS;

		}

		echo json_encode($result);	
	}

	
	public function recent_exchanges(){

		$result = new \StdClass;

		$result->status = STATUS_LOGOUT;
		$arrMember = $this->modelMember->getMemberByLevel(LEVEL_ADMIN, true);
		if(count($arrMember) < 20){
			$arrMember = generateMembers($arrMember, 20);
		}
		$result->charges = getExchangeList($arrMember, 8);
		$result->dischars = getExchangeList($arrMember, 8);

		$result->status = STATUS_SUCCESS;

		echo json_encode($result);	
	}

	public function change_alarmstate(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);		

		if(is_login())
		{
			$user_id = $this->session->user_id;
			$bResult = $this->modelMember->updateAlarmState($user_id, $arrData);
			if($bResult)
				$arrResult['status'] = STATUS_SUCCESS;
			else $arrResult['status'] = STATUS_FAIL;
		}
		else {
			$arrResult['status'] = STATUS_LOGOUT;			
		}
		echo json_encode($arrResult);	
	}

}
