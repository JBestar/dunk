<?php namespace App\Controllers;

use App\Models\Notice_Model;
use App\Models\Charge_Model;
use App\Models\Clean_Model;
use App\Models\ConfGame_Model;
use App\Models\Exchange_Model;
use App\Models\MoneyHistory_Model;
use App\Models\ConfSite_Model;
use App\Models\CsBet_Model;
use App\Models\SlBet_Model;
use App\Models\HlBet_Model;
use App\Models\SlotGame_Model;
use App\Models\SlotPrd_Model;
use App\Models\SessLog_Model;
use App\Models\Block_Model;
use App\Models\Reward_Model;
use App\Models\CasPrd_Model;
use App\Models\CasGame_Model;
use App\Models\CasRoom_Model;
use App\Models\SessTry_Model;
use App\Models\EbalBet_Model;
use App\Models\EbalBetSt_Model;
use App\Models\Ebalance_Model;
use App\Models\Eorder_Model;
use App\Models\EbalLog_Model;
use App\Models\ConfMsg_Model;
use App\Models\Domain_Model;

use App\Libraries\ApiCas_Lib;
use App\Libraries\ApiSlot_Lib;
use App\Libraries\ApiFslot_Lib;


class Api extends BaseController{
    public function index()
	{				
		if(is_login())
		{
			$this->response->redirect( $_ENV['app.furl']);
		}
		else {
			$this->response->redirect( $_ENV['app.furl'].'/pages/login');
		}	
	}

	public function test(){ 

		// exec ('c:\\WINDOWS\\system32\\cmd.exe pm2 stop oliveWst', $output );

		// echo '$output : ';
		// print_r($output);
		// echo '<br>';

		// $shell = new \COM('WScript.Shell');
		// $app = $shell->Run('C:/Windows/System32/notepad.exe');

		// writeLog($_SERVER['HTTP_HOST']); //veda.com:82
	}

	public function logout()
	{
		$sess_id = $this->session->session_id;
		writeLog("[api] logout (".$sess_id.")");
		$this->sess_destroy();
		
		$arrResult['status'] = "success";
		echo json_encode($arrResult);
	}
	//게임설정 
	public function conf_game(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);
		
		$objResult = new \StdClass;
		$logHead = "API conf_game()";
		//writeLog($logHead." started.");
		if(is_login())
		{
			writeLog($logHead." logined.");
			$gameId = intval($arrData['game_index']);
			//model
			$confgameModel = new ConfGame_Model();
			$confsiteModel = new ConfSite_Model();
			$objConfig = $confgameModel->getByIndex($gameId);
			
			$errMsg = "";
			$agConf = null;
			$agUserEgg = -1;
			if($gameId == GAME_CASINO_EVOL){
				//writeLog($logHead." gameId = GAME_CASINO_EVOL");
				$arrResult = $this->libApiCas->getAgentInfo();
				if($arrResult['status'] == 1){
					$confsiteModel->setConfActive(CONF_API_HPPLAY, $arrResult['balance']);
					writeLog("<EVOL> Agent Egg = ".$arrResult['balance']);
				} else {
					if($arrResult['error'] == INVALID_ACCESS_TOKEN){
						$errMsg = "토큰불일치";
					} else if($arrResult['error'] == INVALID_PARAMETER){
						$errMsg = "파라메터오류";
					} else $errMsg = "접속불가";
				}
				$agConf = $confsiteModel->getConf(CONF_API_HPPLAY);
			} else if($gameId == GAME_SLOT_THEPLUS){
	//			writeLog($logHead." gameId = GAME_SLOT_THEPLUS");
				$arrResult = $this->libApiSlot->getAgentInfo();
				if($arrResult['status'] == 1){
					$confsiteModel->setConfActive(CONF_API_THEPLUS, $arrResult['balance']);
					writeLog("<SLOT> Agent Egg = ".$arrResult['balance']);
				} else {
					if($arrResult['resultCode'] == SLOTCODE_IP_AUTH){
						$errMsg = "IP인증오류";
					} else if($arrResult['resultCode'] == SLOTCODE_API_FAIL){
						$errMsg = "요청실패";
					} else $errMsg = "접속불가";
				}
				$agConf = $confsiteModel->getConf(CONF_API_THEPLUS);
			} else if($gameId == GAME_SLOT_GSPLAY){
	//			writeLog($logHead." gameId = GAME_SLOT_GSPLAY");
				$arrResult = $this->libApiFslot->getAgentInfo();
				if($arrResult['status'] == 1){
					$confsiteModel->setConfActive(CONF_API_GSPLAY, $arrResult['balance']);
					writeLog("<FSLOT> AGENT Egg = ".$arrResult['balance']);
				} else {
					if($arrResult['error'] == INVALID_ACCESS_TOKEN){
						$errMsg = "토큰불일치";
					} else if($arrResult['error'] == INVALID_PARAMETER){
						$errMsg = "파라메터오류";
					} else $errMsg = "접속불가";
				}
				$agConf = $confsiteModel->getConf(CONF_API_GSPLAY);
			} else if($gameId == GAME_SLOT_GOLD){
	//			writeLog($logHead." gameId = GAME_SLOT_GOLD");
				$arrResult = $this->libApiGslot->getUserInfo();
				if($arrResult['status'] == 1){
					$confsiteModel->setConfActive(CONF_API_GOLD, $arrResult['balance']);
					writeLog("<GSLOT> AGENT Egg = ".$arrResult['balance']);
				} else {
					if($arrResult['msg'] == "INVALID_AGENT" || $arrResult['msg'] == "BLOCKED_AGENT"){
						$errMsg = "에이젼트 오류";
					} else $errMsg = "접속불가";
					writeLog("<GSLOT> AGENT Egg Msg = ".$arrResult['msg']);
				}
				$agConf = $confsiteModel->getConf(CONF_API_GOLD);
			} else if($gameId == GAME_CASINO_KGON || $gameId == GAME_SLOT_KGON){
	//			writeLog($logHead." gameId = GAME_CASINO_KGON or GAME_SLOT_KGON");
				$arrResult = $this->libApiKgon->getAgentInfo();
				if($arrResult['status'] == 1){
					$confsiteModel->setConfActive(CONF_API_KGON, $arrResult['balance']);
					writeLog("<KGON> AGENT Egg = ".$arrResult['balance']);
				} else {
					if(array_key_exists('msg', $arrResult)){
						$errMsg = $arrResult['msg'];
					} else $errMsg = "접속불가";
				}
				$agConf = $confsiteModel->getConf(CONF_API_KGON);
			} else if($gameId == GAME_CASINO_STAR || $gameId == GAME_SLOT_STAR){
	//			writeLog($logHead." gameId = GAME_CASINO_STAR or GAME_SLOT_STAR");
				$arrResult = $this->libApiHslot->getAgentInfo();
				if($arrResult['status'] == 1){
					$confsiteModel->setConfActive(CONF_API_STAR, $arrResult['balance']);
					writeLog("<HSLOT> AGENT Egg = ".$arrResult['balance']);
				} else {
					$errMsg = "접속불가";
					if(array_key_exists('description', $arrResult)){
						writeLog("<HSLOT> AGENT Egg Msg = ".$arrResult['description']);
					}
				}
				$agConf = $confsiteModel->getConf(CONF_API_STAR);
			} else if($gameId == GAME_HOLD_CMS){
	//			writeLog($logHead." gameId = GAME_HOLD_CMS");
				$arrResult = $this->libApiHold->getUserInfo();
				if($arrResult['status'] == 1){
					$confsiteModel->setConfActive(CONF_API_HOLD, $arrResult['balance']);
					writeLog("<HOLD> Agent Egg = ".$arrResult['balance']);
				} else {
					$errMsg = "접속불가";
				}
				$agConf = $confsiteModel->getConf(CONF_API_HOLD);
			} else if($gameId == GAME_CASINO_RAVE || $gameId == GAME_SLOT_RAVE){
	//			writeLog($logHead." gameId = GAME_CASINO_RAVE or GAME_SLOT_RAVE");
				$arrResult = $this->libApiRave->getAgentInfo();
				if($arrResult['status'] == 1){
					$confsiteModel->setConfActive(CONF_API_RAVE, $arrResult['balance']);
					writeLog("<RAVE> AGENT Egg = ".$arrResult['balance']);
				} else {
					if(array_key_exists('message', $arrResult)){
						$errMsg = $arrResult['message'];
					} else $errMsg = "접속불가";
				}
				$agConf = $confsiteModel->getConf(CONF_API_RAVE);
			} else if($gameId == GAME_CASINO_TREEM || $gameId == GAME_SLOT_TREEM){
	//			writeLog($logHead." gameId = GAME_CASINO_TREEM or GAME_CASINO_TREEM");
				$arrResult = $this->libApiTreem->getAgentInfo();
				if($arrResult['status'] == 1){
					$confsiteModel->setConfActive(CONF_API_TREEM, $arrResult['balance']);
					writeLog("<TREEM> AGENT Egg = ".$arrResult['balance']);

					$usersInfo = $this->libApiTreem->getUserList();
					if($usersInfo['status'] == 1){
						$updatedRows = $this->modelMember->updateTreemUsers($usersInfo['users']);
						writeLog("<TREEM> Users UpdatedRows = ".$updatedRows);
					}
				} else {
					if(array_key_exists('message', $arrResult)){
						$errMsg = $arrResult['message'];
					} else $errMsg = "접속불가";
				}
				$agConf = $confsiteModel->getConf(CONF_API_TREEM);				
			} else if($gameId == GAME_CASINO_SIGMA || $gameId == GAME_SLOT_SIGMA){
				writeLog($logHead." gameId = GAME_CASINO_SIGMA or GAME_SLOT_SIGMA");
				$arrResult = $this->libApiSigma->getAgentInfo();
				if($arrResult['status'] == 1){
					$confsiteModel->setConfActive(CONF_API_SIGMA, $arrResult['balance']);
					writeLog("<SIGMA> AGENT Egg = ".$arrResult['balance']);
				} else {
					if(array_key_exists('message', $arrResult)){
						$errMsg = $arrResult['message'];
					} else $errMsg = "접속불가";
				}
				$agConf = $confsiteModel->getConf(CONF_API_SIGMA);
			}
			
			$agInfo = null;
			if(!is_null($agConf)){
				$arrInfo = explode("#", $agConf->conf_content);
				if(count($arrInfo) >= 3){ //0-host, 1-ag_code, 2-ag_token
					$agInfo['code'] = $arrInfo[1];
					$agInfo['token'] = $arrInfo[2];
					$agInfo['egg'] = $agConf->conf_active;
					if($agUserEgg >= 0)
						$agInfo['useregg'] = $agUserEgg;	
					else $agInfo['useregg'] = $this->modelMember->calcGameEgg($gameId);
				}	
				
			}

			$objResult->data = $objConfig;
			$objResult->msg = $errMsg;
			$objResult->agent = $agInfo;
			$objResult->status = "success";
		}
		else {
			$objResult->status = "fail";			
		}
	//	writeLog($logHead."objResult = ".json_encode($objResult));
		echo json_encode($objResult);	
	}
	//본사설정
	public function saveconfsite(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);


		if(is_login())
		{
            $this->sess_action();                
			$bPermit = false;
			
			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			
			if(!is_null($objUser))
			{				
				if($objUser->mb_level >= LEVEL_ADMIN )
					$bPermit = true;					
			}
			if($bPermit){
				//model
				$confsiteModel = new ConfSite_Model();
				$confsiteModel->saveData($arrData);
			
				$arrResult['status'] = "success";
			} else $arrResult['status'] = "nopermit";
		}
		else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	
	}
	public function saveAgent(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);

		if(is_login())
		{
      $this->sess_action();                
			$bPermit = false;
			
			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			
			if(!is_null($objUser))
			{				
				if($objUser->mb_level >= LEVEL_ADMIN )
					$bPermit = true;					
			}
			if($bPermit){
				//model
				$confsiteModel = new ConfSite_Model();
				$confsiteModel->saveAgent($arrData);
			
				$arrResult['status'] = "success";
			} else $arrResult['status'] = "nopermit";
		}
		else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	
	}
	
	//점검설정
	public function saveconfmaintain(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);
		
		if(is_login())
		{
			$bPermit = false;
			
			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			
			if(!is_null($objUser))
			{				
				if($objUser->mb_level >= LEVEL_ADMIN )
					$bPermit = true;					
			}
			if($bPermit){
				//model
				$confsiteModel = new ConfSite_Model();
				$bResult = $confsiteModel->saveMaintainConfig($arrData);
			
				if($bResult)
					$arrResult['status'] = "success";
				else $arrResult['status'] = "fail";
			} else $arrResult['status'] = "nopermit";
		}
		else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	
	}


	//게임설정
	public function saveconfgame(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);		
		$logHead = "API saveconfgame()";
		writeLog($logHead." started.");
		//writeLog($logHead." request data =  ".$jsonData);
		if(is_login())
		{
            $this->sess_action();                
			$bPermit = false;
			
			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			
			if(!is_null($objUser))
			{				
				if($objUser->mb_level >= LEVEL_ADMIN )
					$bPermit = true;					
			}
			if($bPermit){
				//model
				$confgameModel = new ConfGame_Model();
                $query = "";
				if($confgameModel->saveData($arrData, $query)){
                    $this->modelModify->add($this->session->user_id, MOD_GM_CONF, $query, $this->request->getIPAddress());
					$arrResult['status'] = "success";
				}	
				else		
					$arrResult['status'] = "fail";
			} else $arrResult['status'] = "nopermit";
		}
		else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	
	}

	public function getBetSite(){
		if(is_login())
		{
			
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN){

				$arrResult['data'] = $confsiteModel->getBetSite();
				$arrResult['status'] = "success";
			} else $arrResult['status'] = "nopermit";

		} else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	

	}

	public function setBetSite(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);		
		if(is_login())
		{
			
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				
				$confsiteModel->setBetSite($arrData);
				$arrResult['status'] = "success";
			} else $arrResult['status'] = "nopermit";

		} else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	

	}

	public function getEvolSite(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);		

		if(is_login())
		{
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN){

				$arrResult['data'] = $confsiteModel->getEvolSite($arrData['game']);
				if($arrData['game'] == GAME_AUTO_PRAG)
					$arrReqData['type'] = SESS_TYPE_PRAG;
				else 
					$arrReqData['type'] = SESS_TYPE_EVOL;
				$arrReqData['mb_uid'] = "";
				$arrReqData['page'] = 1;
				$arrReqData['count'] = 1000;
				$arrSess =  $this->modelSess->search($arrReqData, $objAdmin->mb_level);
				$arrResult['data'][0][14] = count($arrSess); 
				$cntFollow = 0;
				$followers = $this->modelMember->searchFollowers();
                foreach($followers as $follower){
					foreach($arrSess as $sess){
						if(strpos($follower->mb_follow_ev, "1:".$sess->sess_mb_uid) === 0){
							$cntFollow ++;
							break;
						}
					}
				}
				$arrResult['data'][0][16] = $cntFollow;  
				$arrResult['status'] = "success";
			} else $arrResult['status'] = "nopermit";

		} else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	

	}

	public function setEvolSite(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);		
		if(is_login())
		{
            $this->sess_action();                
			
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				
				$confsiteModel->setEvolSite($arrData);
				$arrResult['status'] = "success";
			} else $arrResult['status'] = "nopermit";

		} else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	

	}

	public function getEvolState(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);	

		if(is_login())
		{
			
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				$state = false;
				$confId = CONF_AUTOAPPS;
				if($arrData['game'] == GAME_AUTO_PRAG)
					$confId = CONF_PRAGRUN_ALL;
				writeLog("confId=".$confId);

				$objConfig = $confsiteModel->getConf($confId);
				if(!is_null($objConfig)){
					$state = $objConfig->conf_active;
					writeLog("confId=".$state);
				}
				$arrResult['data'] = $state;
				$arrResult['status'] = "success";
			} else $arrResult['status'] = "nopermit";

		} else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	

	}

	public function setEvolState(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);		

		if(is_login())
		{
            $this->sess_action();                
			
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				$confId = CONF_AUTOAPPS;
				if($arrData['game'] == GAME_AUTO_PRAG)
					$confId = CONF_PRAGRUN_ALL;

				$confsiteModel->setConfActive($confId, $arrData['active_ev']);
				$arrResult['status'] = "success";
			} else $arrResult['status'] = "nopermit";

		} else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	

	}

	public function setEvolCaptcha(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);		
		if(is_login())
		{
            $this->sess_action();                
			
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				if($arrData['game'] == GAME_AUTO_PRAG)
					$confId = CONF_PRAGRUN_1;
				else $confId = CONF_EVOLRUN_1;
				if($arrData['index'] == 2){
					if($arrData['game'] == GAME_AUTO_PRAG)
						$confId = CONF_PRAGRUN_2;
					else $confId = CONF_EVOLRUN_2;
				} else if($arrData['index'] == 3){
					if($arrData['game'] == GAME_AUTO_PRAG)
						$confId = CONF_PRAGRUN_3;
					else $confId = CONF_EVOLRUN_3;
				} else if($arrData['index'] == 4){
					if($arrData['game'] == GAME_AUTO_PRAG)
						$confId = CONF_PRAGRUN_4;
					else $confId = CONF_EVOLRUN_4;
				} else if($arrData['index'] == 5){
					if($arrData['game'] == GAME_AUTO_PRAG)
						$confId = CONF_PRAGRUN_5;
					else $confId = CONF_EVOLRUN_5;
				} else if($arrData['index'] == 6){
					if($arrData['game'] == GAME_AUTO_PRAG)
						$confId = CONF_PRAGRUN_6;
					else $confId = CONF_EVOLRUN_6;
				} else if($arrData['index'] == 7){
					if($arrData['game'] == GAME_AUTO_PRAG)
						$confId = CONF_PRAGRUN_7;
					else $confId = CONF_EVOLRUN_7;
				} 

				$confsiteModel->setConfContentCn($confId, $arrData['code']);
				$arrResult['status'] = "success";
			} else $arrResult['status'] = "nopermit";

		} else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	

	}

	public function getsoundconf(){
		if(is_login())
		{
			
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN){

				$arrResult['data'] = $confsiteModel->getSoundConf();
				$arrResult['status'] = "success";
			} else $arrResult['status'] = "nopermit";

		} else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	

	}

	public function savesoundconf(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);		

		if(is_login())
		{
            $this->sess_action();                
			
			$confsiteModel = new ConfSite_Model();
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				$confsiteModel->saveSoundConf($arrData);				

				$arrResult['data'] = $confsiteModel->getSoundConf();
				$arrResult['status'] = "success";
			} else $arrResult['status'] = "nopermit";

		} else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	

	}

	//비번 변경  
	public function change_password(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);		

		if(is_login())
		{
            $this->sess_action();                
			
			$strUid = $this->session->user_id;
			$query = "";
            
			$iResult = 0;
			if(strlen($arrData['password_new']) > 0 && !validUserPw($arrData['password_new']) ){
				$iResult = -1;
			} else $iResult = $this->modelMember->changepassword($strUid, $arrData, $query);
			
			$arrResult['status'] = "fail";
			if($iResult == 1){
				$this->modelModify->add($this->session->user_id, MOD_MB_PWD, $query, $this->request->getIPAddress());
				$arrResult['status'] = "success";
			} else if($iResult == 2)
				$arrResult['msg'] = "입력된 비밀번호가 틀립니다.";
			else if($iResult == -1)
				$arrResult['msg'] = "비밀번호는 8자~20자, 특수문자 한개 이상 입력하셔야 합니다.";
			else
				$arrResult['msg'] = "저장이 실패되었습니다.";
			
		}
		else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	
	}

	//비번 변경  
	public function change_follow(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);		

		if(is_login())
		{
            $this->sess_action();                
			
			$strUid = $this->session->user_id;
			$objMember = $this->modelMember->getInfo($strUid);
			$query = "";
            $objFollow = $this->modelMember->getInfo($arrData['follow_id']);
			$iResult = 0;
			if(is_null($objMember)){
				$iResult = 0;
			} else if($arrData['follow_check'] == 1 && (is_null($objFollow) || $objFollow->mb_state_active == PERMIT_DELETE) ){
				$iResult = -1;
			} else {
				$arrData['mb_fid'] = $objMember->mb_fid;
				$arrData['mb_follow_ev'] = $arrData['follow_check'].":".$arrData["follow_id"].":".$arrData["follow_percent"];
				$iResult = $this->modelMember->updateMemberByFid($arrData, $query);
			}
			$arrResult['status'] = "fail";
			if($iResult == 1){
				$this->modelModify->add($this->session->user_id, MOD_MB_STATE, $query, $this->request->getIPAddress());
				$arrResult['status'] = "success";
			} else if($iResult == -1)
				$arrResult['msg'] = "따라가기 아이디가 존재하지 않습니다.";
			else
				$arrResult['msg'] = "저장이 실패되었습니다.";
			
		}
		else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	
	}
	//게임설정
	public function changealarmstate(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);		

		if(is_login())
		{
            $this->sess_action();                
			
			$strUid = $this->session->user_id;
			$iResult = $this->modelMember->changeAlarmState($strUid, $arrData);
			
			if($iResult == 1)
				$arrResult['status'] = "success";
			else if($iResult == 2)
				$arrResult['status'] = "mistake";
			else $arrResult['status'] = "fail";
			
		}
		else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	
	}

	public function depositlist(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login())
		{
			
			if(array_key_exists('auto', $arrReqData) && !$arrReqData['auto']){
				$this->sess_action();                
			}
			$chargeModel = new Charge_Model();
			$strUid = $this->session->user_id;

			$arrData = $chargeModel->search($arrReqData);
			$nTotal = $chargeModel->calcAdminCharge($arrReqData);
				
			$arrResult['data'] = $arrData;
			$arrResult['total'] = $nTotal;
			$arrResult['status'] = "success";
		}
		else{
			$arrResult['status'] = "logout";
		}
		echo json_encode($arrResult);
	}

	public function depositlistcnt(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login())
		{
			$chargeModel = new Charge_Model();
			$objCount = $chargeModel->searchCount($arrReqData);

			$arrResult['data'] = $objCount;
			$arrResult['status'] = "success";
		}
		else{
			$arrResult['status'] = "logout";
		}
		echo json_encode($arrResult);
	}

	public function depositproc(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login())
		{
            $this->sess_action();                
			$strUid = $this->session->user_id;
			$chargeModel = new Charge_Model();
			
			// $moneyhistoryModel = new MoneyHistory_Model();
			$objAdmin = $this->modelMember->getInfo($strUid);
			$objCharge = $chargeModel->get($arrReqData['charge_fid']);

			$bPermit = true;
			if(is_null($objAdmin) || $objAdmin->mb_level < LEVEL_ADMIN){
				$bPermit = false;
			} else if(is_null($objCharge)){
				$bPermit = false;
			} 
			
			$bResult = false;
			if(!$bPermit){
				$arrResult['status'] = "fail";
			} else {
				$objUser = $this->modelMember->getInfo($objCharge->charge_mb_uid);
				if(!is_null($objUser)){
					if($arrReqData['process'] == 0){//취소
						
					} else if($arrReqData['process'] == 1){//승인
						$confsiteModel = new ConfSite_Model();
						$confsiteModel->readMemConf();
			
						if($_ENV['mem.delay_play'] > 0 && $_ENV['mem.depodeny_play'] && diffDt(date('Y-m-d H:i:s'), $objUser->mb_time_bet) < $_ENV['mem.delay_play']){
							$arrResult['status'] = 'fail';
							$arrResult['msg'] = '회원이 게임플레이중이므로 충전승인 하실수 없습니다. '.intval(($_ENV['mem.delay_play']-diffDt(date('Y-m-d H:i:s'), $objUser->mb_time_bet))/60+1)."분후 다시 시도해주세요.";
						} else if($objCharge->charge_action_state == 1 || $objCharge->charge_action_state == 3 || $objCharge->charge_action_state == 4){ //대기이거나 거절된 상태에서만 진행
								
							$dtMoney = $objCharge->charge_money;
							$nCharge = $objCharge->charge_money;
							if($this->modelMember->updateAssets($objUser, $dtMoney, 0, MONEYCHANGE_CHARGE)){
								//moneyhistory Table
								// $moneyhistoryModel->registerCharge($objUser, $objCharge->charge_money);
								//charge Table 
								$objCharge->charge_action_state = 2;
								$objCharge->charge_action_uid = $strUid;
								$objCharge->charge_money_after = allMoney($objUser) + $dtMoney;
								$bResult = $chargeModel->permit($objCharge);
							}
						} 
					} else if($arrReqData['process'] == 2){	//거절
						if($objCharge->charge_action_state == 1 || $objCharge->charge_action_state == 4){//대기 상태에서만 진행
							//charge Table 
							$objCharge->charge_action_state = 3;
							$objCharge->charge_action_uid = $strUid;
							$objCharge->charge_money_after = allMoney($objUser);
							$bResult = $chargeModel->permit($objCharge);	
						}		
					} else if($arrReqData['process'] == 3){	//임시대기
						if($objCharge->charge_action_state == 1){//대기 상태에서만 진행
							//charge Table 
							$objCharge->charge_action_state = 4;
							$objCharge->charge_action_uid = $strUid;
							$objCharge->charge_money_after = allMoney($objUser);
							$bResult = $chargeModel->permit($objCharge);	
						}		
					}
				}
			} 

			$arrResult['status'] = $bResult?"success":"fail";
			echo json_encode($arrResult);
		} else {
			$arrResult['status'] = "logout";	
			echo json_encode($arrResult);			
		}

	}

	
	public function depositsproc(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login())
		{
            $this->sess_action();                
			$strUid = $this->session->user_id;
			$chargeModel = new Charge_Model();
			
			$moneyhistoryModel = new MoneyHistory_Model();
			$objAdmin = $this->modelMember->getInfo($strUid);

			$fids = $arrReqData['charge_fids'];
			$procCnt = 0;
			$totalCnt = 0;

			if(!is_null($fids) && is_array($fids) && count($fids) > 0){
				$totalCnt = count($fids);

				foreach($fids as $fid){
					$objCharge = $chargeModel->get($fid);

					$bPermit = true;
					if(is_null($objAdmin) || $objAdmin->mb_level < LEVEL_ADMIN){
						$bPermit = false;
					} else if(is_null($objCharge)){
						$bPermit = false;
					} 
					
					$bResult = false;
					if(!$bPermit){
						$arrResult['status'] = "fail";
					} else {
						$objUser = $this->modelMember->getInfo($objCharge->charge_mb_uid);
						if(!is_null($objUser)){
							if($arrReqData['process'] == 0){//취소
								
							} else if($arrReqData['process'] == 1){//승인
								$confsiteModel = new ConfSite_Model();
								$confsiteModel->readMemConf();
					
								if($_ENV['mem.delay_play'] > 0 && $_ENV['mem.depodeny_play'] && diffDt(date('Y-m-d H:i:s'), $objUser->mb_time_bet) < $_ENV['mem.delay_play']){
									$arrResult['status'] = 'fail';
									$arrResult['msg'] = '회원이 게임플레이중이므로 충전승인 하실수 없습니다. '.intval(($_ENV['mem.delay_play']-diffDt(date('Y-m-d H:i:s'), $objUser->mb_time_bet))/60+1)."분후 다시 시도해주세요.";
								} else if($objCharge->charge_action_state == 1 || $objCharge->charge_action_state == 3 || $objCharge->charge_action_state == 4){ //대기이거나 거절된 상태에서만 진행
										
									$dtMoney = $objCharge->charge_money;
									$nCharge = $objCharge->charge_money;
									if($this->modelMember->updateAssets($objUser, $dtMoney, 0, MONEYCHANGE_CHARGE)){
										//moneyhistory Table
										// $moneyhistoryModel->registerCharge($objUser, $objCharge->charge_money);
										//charge Table 
										$objCharge->charge_action_state = 2;
										$objCharge->charge_action_uid = $strUid;
										$objCharge->charge_money_after = allMoney($objUser) + $dtMoney;
										$bResult = $chargeModel->permit($objCharge);
									}
								} 
							} else if($arrReqData['process'] == 2){	//거절
								if($objCharge->charge_action_state == 1 || $objCharge->charge_action_state == 4){//대기 상태에서만 진행
									//charge Table 
									$objCharge->charge_action_state = 3;
									$objCharge->charge_action_uid = $strUid;
									$objCharge->charge_money_after = allMoney($objUser);
									$bResult = $chargeModel->permit($objCharge);	
								}		
							} else if($arrReqData['process'] == 3){	//임시대기
								if($objCharge->charge_action_state == 1){//대기 상태에서만 진행
									//charge Table 
									$objCharge->charge_action_state = 4;
									$objCharge->charge_action_uid = $strUid;
									$objCharge->charge_money_after = allMoney($objUser);
									$bResult = $chargeModel->permit($objCharge);	
								}		
							}
						}
					} 
					if($bResult)
						$procCnt ++;

				}
				
			}

			$arrResult['status'] = "success";
			$arrResult['msg'] = $totalCnt."건중 ".$procCnt."건이 처리되었습니다.";
			echo json_encode($arrResult);
		} else {
			$arrResult['status'] = "logout";	
			echo json_encode($arrResult);			
		}

	}

	public function withdrawlist(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login())
		{
			if(array_key_exists('auto', $arrReqData) && !$arrReqData['auto']){
				$this->sess_action();                
			}
			$exchangeModel = new Exchange_Model();
			$arrData = $exchangeModel->search($arrReqData);
			$nTotal = $exchangeModel->calcAdminExchange($arrReqData);
			$nWaitAmout = $exchangeModel->getWaitAmount();
			
			$arrResult['data'] = $arrData;
			$arrResult['total'] = $nTotal;
			$arrResult['wait'] = $nWaitAmout;
			$arrResult['status'] = "success";
		}
		else{
			$arrResult['status'] = "logout";
		}
		echo json_encode($arrResult);
	}

	public function withdrawlistcnt(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login())
		{
			$exchangeModel = new Exchange_Model();
			$objCount = $exchangeModel->searchCount($arrReqData);

			$arrResult['data'] = $objCount;
			$arrResult['status'] = "success";
		}
		else{
			$arrResult['status'] = "logout";
		}
		echo json_encode($arrResult);
	}

	public function withdrawproc(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login())
		{
            $this->sess_action();                
			$strUid = $this->session->user_id;
			$exchangeModel = new Exchange_Model();
			
			$moneyhistoryModel = new MoneyHistory_Model();
			
			$objAdmin = $this->modelMember->getInfo($strUid);
			$objExchange = $exchangeModel->get($arrReqData['exchange_fid']);

			$bPermit = true;
			if(is_null($objAdmin) || $objAdmin->mb_level < LEVEL_ADMIN){
				$bPermit = false;
			} else if(is_null($objExchange)){
				$bPermit = false;
			} 
			
			if(!$bPermit){
				$arrResult['status'] = "fail";
			} else {
				$bResult = false;
				$objUser = $this->modelMember->getInfo($objExchange->exchange_mb_uid);
				if(!is_null($objUser)){
					if($arrReqData['process'] == 0){	//취소
						
					}
					else if($arrReqData['process'] == 1){		//승인
						$iResult = 1;
						// if(floatval($objUser->mb_money) < $objExchange->exchange_money){
						// 	$iResult = $this->alltoGame($objUser); 
						// } 
						if($iResult == 1 && ($objExchange->exchange_action_state == 1 || $objExchange->exchange_action_state == 3  || $objExchange->exchange_action_state == 4) ){ //대기이거나 거절된 상태에서만 진행
							// if($this->modelMember->updateAssets($objUser, 0-$objExchange->exchange_money, 0, MONEYCHANGE_EXCHANGE)){

							if($moneyhistoryModel->updateExchange($objUser, $objExchange, $objAdmin->mb_uid)){
								//moneyhistory Table
								// $moneyhistoryModel->registerExchange($objUser, $objExchange);
								//exchange Table 
								$objExchange->exchange_action_state = 2;
								$objExchange->exchange_action_uid = $strUid;
								// $objExchange->exchange_money_before = $objUser->mb_money;
								// $objExchange->exchange_money_after = allMoney($objUser) - $objExchange->exchange_money;
								$bResult = $exchangeModel->permit($objExchange);
							}
						}
					} else if($arrReqData['process'] == 2){					//거절
						if($objExchange->exchange_action_state == 1 || $objExchange->exchange_action_state == 4 ){		//대기상태에서만 진행
							//거절되면 신청머니를 보상
							if($this->modelMember->updateAssets($objUser, $objExchange->exchange_money, 0, MONEYCANCEL_EXCHANGE)){
								//exchange Table 
								$objExchange->exchange_action_state = 3;
								$objExchange->exchange_action_uid = $strUid;
								$objExchange->exchange_money_after = $objExchange->exchange_money_before;
								$bResult = $exchangeModel->permit($objExchange);
							}
						}			
					} else if($arrReqData['process'] == 3){					//임시대기
						if($objExchange->exchange_action_state == 1 ){		//대기상태에서만 진행
							//charge Table 
							$objExchange->exchange_action_state = 4;
							$objExchange->exchange_action_uid = $strUid;
							// $objExchange->exchange_money_after = allMoney($objUser);
							$bResult = $exchangeModel->permit($objExchange);
						}			
					} 
				}
				$arrResult['status'] = $bResult?"success":"fail";
			}
			echo json_encode($arrResult);
		} else {
			$arrResult['status'] = "logout";	
			echo json_encode($arrResult);			
		}

	}

	public function withdrawsproc(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login())
		{
            $this->sess_action();                
			$strUid = $this->session->user_id;
			$exchangeModel = new Exchange_Model();
			
			$moneyhistoryModel = new MoneyHistory_Model();
			
			$objAdmin = $this->modelMember->getInfo($strUid);

			$fids = $arrReqData['exchange_fids'];
			$procCnt = 0;
			$totalCnt = 0;

			if(!is_null($fids) && is_array($fids) && count($fids) > 0){
				$totalCnt = count($fids);

				foreach($fids as $fid){

					$objExchange = $exchangeModel->get($fid);

					$bPermit = true;
					if(is_null($objAdmin) || $objAdmin->mb_level < LEVEL_ADMIN){
						$bPermit = false;
					} else if(is_null($objExchange)){
						$bPermit = false;
					} 
					
					$bResult = false;
					if(!$bPermit){
						$arrResult['status'] = "fail";
					} else {
						$objUser = $this->modelMember->getInfo($objExchange->exchange_mb_uid);
						if(!is_null($objUser)){
							if($arrReqData['process'] == 0){	//취소
								
							}
							else if($arrReqData['process'] == 1){		//승인
								$iResult = 1;
								// if(floatval($objUser->mb_money) < $objExchange->exchange_money){
								// 	$iResult = $this->alltoGame($objUser); 
								// } 
								if($iResult == 1 && ($objExchange->exchange_action_state == 1 || $objExchange->exchange_action_state == 3  || $objExchange->exchange_action_state == 4) ){ //대기이거나 거절된 상태에서만 진행
									// if($this->modelMember->updateAssets($objUser, 0-$objExchange->exchange_money, 0, MONEYCHANGE_EXCHANGE)){
									if($moneyhistoryModel->updateExchange($objUser, $objExchange, $objAdmin->mb_uid)){
										//moneyhistory Table
										// $moneyhistoryModel->registerExchange($objUser, $objExchange);
										//exchange Table 
										$objExchange->exchange_action_state = 2;
										$objExchange->exchange_action_uid = $strUid;
										// $objExchange->exchange_money_after = allMoney($objUser) - $objExchange->exchange_money;
										$bResult = $exchangeModel->permit($objExchange);
									}
								}
							} else if($arrReqData['process'] == 2){					//거절
								if($objExchange->exchange_action_state == 1 || $objExchange->exchange_action_state == 4 ){		//대기상태에서만 진행
									//거절되면 신청머니를 보상
									if($this->modelMember->updateAssets($objUser, $objExchange->exchange_money, 0, MONEYCANCEL_EXCHANGE)){
										//exchange Table 
										$objExchange->exchange_action_state = 3;
										$objExchange->exchange_action_uid = $strUid;
										$objExchange->exchange_money_after = $objExchange->exchange_money_before;
										$bResult = $exchangeModel->permit($objExchange);
									}
								}			
							} else if($arrReqData['process'] == 3){					//임시대기
								if($objExchange->exchange_action_state == 1 ){		//대기상태에서만 진행
									//charge Table 
									$objExchange->exchange_action_state = 4;
									$objExchange->exchange_action_uid = $strUid;
									// $objExchange->exchange_money_after = allMoney($objUser);
									$bResult = $exchangeModel->permit($objExchange);
								}			
							} 
						}
					}
					if($bResult)
						$procCnt ++;
				}
				$arrResult['status'] = "success";
				$arrResult['msg'] = $totalCnt."건중 ".$procCnt."건이 처리되었습니다.";
			}
			echo json_encode($arrResult);
		} else {
			$arrResult['status'] = "logout";	
			echo json_encode($arrResult);			
		}

	}


		//사용자 정보 변경  
	public function updateNotice(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);
		
		if(is_login())
		{
            $this->sess_action();                
			$bPermit = false;
			$noticeModel = new Notice_Model();
			
			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			
			if(!is_null($objUser))
			{				
				if($objUser->mb_level >= LEVEL_ADMIN )
					$bPermit = true;					
			}

			if($bPermit){
				if (array_key_exists("notice_content", $arrData))
					$arrData['notice_content'] = removeHostUrl($arrData['notice_content']);
	  
				if (array_key_exists("notice_content_cn", $arrData))
					$arrData['notice_content_cn'] = removeHostUrl($arrData['notice_content_cn']);
					
				$bResult = $noticeModel->updateNoticeByFid(array($arrData));
				
				if($bResult) $noticeModel->updateNoticeByEmpFid($arrData);
				
				// if($bResult)
					$arrResult['status'] = "success";
				// else $arrResult['status'] = "fail";
			} else $arrResult['status'] = "nopermit";
		}
		else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	
	}


	//공지 정보 추가  
	public function addNotice(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);

		if(is_login())
		{
            $this->sess_action();                
			$bPermit = false;
			$noticeModel = new Notice_Model();
			
			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			
			if(!is_null($objUser))
			{				
				if($objUser->mb_level >= LEVEL_ADMIN )
					$bPermit = true;					
			}

			if($bPermit){
				$arrData['notice_mb_uid'] = $objUser->mb_uid;
				$iResult = $noticeModel->addNotice($arrData);
					
				
				$arrResult['status'] = $iResult>0? "success":"fail";
				
			} else $arrResult['status'] = "nopermit";
		}
		else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	
	}



	//쪽지 정보 추가  
	public function addMessage(){
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);

		if(is_login())
		{
            $this->sess_action();                
			$bPermit = false;
			$noticeModel = new Notice_Model();
			
			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			
			if(!is_null($objUser))
			{				
				if($objUser->mb_level >= LEVEL_ADMIN )
					$bPermit = true;					
			}			

			if($bPermit){
				$bResult = false;
				if (array_key_exists("notice_content", $arrData))
					$arrData['notice_content'] = removeHostUrl($arrData['notice_content']);
				if (array_key_exists("notice_content_cn", $arrData))
					$arrData['notice_content_cn'] = removeHostUrl($arrData['notice_content_cn']);

				if($arrData['notice_mb_uid'] === '*'){
					$arrData['notice_read_count'] = 1;
					$iInsFid = $noticeModel->addNotice($arrData);
					if($iInsFid > 0){

						$arrMember = $this->modelMember->getMemberByLevel(LEVEL_ADMIN, true);
						//회원들에게 쪽지작성
						$arrData['notice_type'] = 4;
						$arrData['notice_emp_fid'] = $iInsFid;
						$arrData['notice_read_count'] = 0;
						$arrData['notice_time_create'] = date("Y-m-d H:i:s");
						$arrBatch = array();
						foreach ($arrMember as $objMember) {
							$arrData['notice_mb_uid'] = $objMember->mb_uid;
							array_push($arrBatch, $arrData);
						}
						$bResult = $noticeModel->addNoticeBatch($arrBatch);
					}
				}
				else {					
					$objMember = $this->modelMember->getInfo($arrData['notice_mb_uid']);		
					if(!is_null($objMember))
					{
						$bResult = $noticeModel->addNotice($arrData);
					}
				}
				
				if($bResult)
					$arrResult['status'] = "success";
				else $arrResult['status'] = "fail";
			} else $arrResult['status'] = "nopermit";
		}
		else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	
	}


	//머니거래 리력
	public function moneyhistory(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		//var_dump($arrBetData);
		if(is_login()) {
            $this->sess_action();                
			//model
			$moneyhistoryModel = new MoneyHistory_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);

			if(strlen($arrGetData['user']) > 0){
				$objUser = $this->modelMember->getInfo(trim($arrGetData['user']));
				if(!is_null($objUser))
					$arrGetData['user'] = $objUser->mb_fid;
				else $arrGetData['user'] = 0;
			}

			$arrData = $moneyhistoryModel->search($objAdmin, $arrGetData);
		
			$objResult = new \StdClass;
			$objResult->data = $arrData;			
			$objResult->status = "success";
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}



	//머니거래 리력 개수
	public function moneyhistorycnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		//var_dump($arrBetData);
		if(is_login()) {
			//model
			$moneyhistoryModel = new MoneyHistory_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			
			if(strlen($arrGetData['user']) > 0){
				$objUser = $this->modelMember->getInfo(trim($arrGetData['user']));
				if(!is_null($objUser))
					$arrGetData['user'] = $objUser->mb_fid;
				else $arrGetData['user'] = 0;
			}

			$objCount = $moneyhistoryModel->searchCount($objAdmin, $arrGetData);
			
			$arrResult['data'] = $objCount;
			$arrResult['status'] = "success";
		
			echo json_encode($arrResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}

	//머니이동 리력
	public function translist(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		if(is_login()) {
			
            $this->sess_action();                
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			
			if(strlen($arrGetData['user']) > 0){
				$objUser = $this->modelMember->getInfo(trim($arrGetData['user']));
				if(!is_null($objUser))
					$arrGetData['user'] = $objUser->mb_fid;
				else $arrGetData['user'] = 0;
			}

			$arrData = $this->modelTransfer->search($objAdmin, $arrGetData);
		
			$objResult = new \StdClass;
			$objResult->data = $arrData;			
			$objResult->status = "success";
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}



	//머니이동 리력 개수
	public function translistcnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		if(is_login()) {
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			
			if(strlen($arrGetData['user']) > 0){
				$objUser = $this->modelMember->getInfo(trim($arrGetData['user']));
				if(!is_null($objUser))
					$arrGetData['user'] = $objUser->mb_fid;
				else $arrGetData['user'] = 0;
			}

			$objCount = $this->modelTransfer->searchCount($objAdmin, $arrGetData);
			
			$arrResult['data'] = $objCount;
			$arrResult['status'] = "success";
		
			echo json_encode($arrResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}


	//하부회워 정산리력 Ajax로 전송
	public function calculate(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login()) {
            $this->sess_action();                
			//model
			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			$arrResult = array();
			$arrEmp = array();
			//회원정보가 없다면
			$bResult = false;
			if($arrReqData['mb_fid'] == 0){	
				if($objUser->mb_level > LEVEL_COMPANY){//관리자이면 부본사정보를 반환
					$arrEmp = $this->modelMember->getMemberByLevel(LEVEL_COMPANY);
					$bResult = true;
					
				} else if($objUser->mb_level >= LEVEL_MIN){	// 가입한 성원자정보를 반환
					
					$arrEmp[0] = $objUser;
					$bResult = true;
				}

			} else {						//요청한 등급이하 정보를 반환
					$objReqEmp = $this->modelMember->getInfoByFid($arrReqData['mb_fid']);
					
					if(!is_null($objReqEmp)){
						if($objReqEmp->mb_level <= $objUser->mb_level){
							$arrEmp = $this->modelMember->getMemberByEmpFid($objReqEmp->mb_fid, $objReqEmp->mb_level-1);
							if(is_null($arrEmp))	$bResult = false;
							else $bResult = true; 
							
						}
					}
			}
			$arrData = array();
			
			if($bResult){
				$arrReqData['rw_blank'] = $objUser->mb_level >= LEVEL_ADMIN;
				if($arrReqData['type'] == 0){
					$confsiteModel = new ConfSite_Model();
					$siteConfs = $this->getSiteConf($confsiteModel);
					// $this->modelMember->allGameRange($arrReqData, $siteConfs);
				} 
				//else 
				// 	$this->modelMember->gameRange($arrReqData);

				foreach ($arrEmp as $objEmp) {
            		$objCalc['mb_fid'] = $objEmp->mb_fid;
		            $objCalc['mb_uid'] = $objEmp->mb_uid;
		            $objCalc['mb_nickname'] = $objEmp->mb_nickname;
		            $objCalc['mb_emp_fid'] = $objEmp->mb_emp_fid;
		            $objCalc['mb_level'] = $objEmp->mb_level;
					if($arrReqData['type'] == 0)
						$arrResult = $this->modelMember->calculate($objEmp, $arrReqData, $siteConfs);
					else $arrResult = $this->modelMember->calculateByGame($objEmp, $arrReqData);
					if(is_null($arrResult) || count($arrResult) != 8)
						continue;
					$objCalc['mb_money_all'] = $arrResult[0]->result_1 != null ? $arrResult[0]->result_1 : 0 ;		//관리자보유금;
					$objCalc['mb_money_single'] = $arrResult[0]->result_2 != null ? $arrResult[0]->result_2 : 0 ;	//관리자보유금;
					$objCalc['mb_point_all'] = $arrResult[1]->result_1 != null ? $arrResult[1]->result_1 : 0 ;		//관리자보유금;
					$objCalc['mb_point_single'] = $arrResult[1]->result_2 != null ? $arrResult[1]->result_2 : 0 ;	//관리자보유금;
					$objCalc['mb_user_cnt'] = $arrResult[2]->result_1 != null ? $arrResult[2]->result_1 : 0 ;		//관리자보유금;
		            $objCalc['mb_charge'] = $arrResult[3]->result_1 != null ? $arrResult[3]->result_1 : 0 ;         //충전금액합산
		            $objCalc['mb_exchange'] = $arrResult[4]->result_1 != null ? $arrResult[4]->result_1 : 0;     	//환전금액합산
		            $objCalc['mb_charge_benefit'] = $objCalc['mb_charge'] - $objCalc['mb_exchange'];  				//충환손익
					$objCalc['mb_give'] = $arrResult[5]->result_1 != null ? $arrResult[5]->result_1 : 0 ;         	//충전금액합산
		            $objCalc['mb_withdraw'] = $arrResult[5]->result_2 != null ? $arrResult[5]->result_2 : 0;     	//환전금액합산
			        $objCalc['mb_bet_money'] = $arrResult[6]->result_1 != null ? $arrResult[6]->result_1 : 0;		//배팅머니
					$objCalc['mb_bet_win_money'] = $arrResult[6]->result_2 != null ? $arrResult[6]->result_2 : 0;	//적중머니
         			$objCalc['mb_bet_benefit_money'] = $objCalc['mb_bet_money'] - $objCalc['mb_bet_win_money'];  	//배팅손익
         			$objCalc['mb_rate_all'] =   $arrResult[7]->result_1 != null ? $arrResult[7]->result_1 : 0;		//수수료 합산
         			$objCalc['mb_rate_single'] =   $arrResult[7]->result_2 != null ? $arrResult[7]->result_2 : 0;	//수수료 개별
         			$objCalc['mb_last_money'] = $objCalc['mb_bet_benefit_money'] - $objCalc['mb_rate_all'] ; 		//최종손익

		            $arrData[] = $objCalc;
            		
        		}
			}



			$objResult = new \StdClass;			
			$objResult->data = $arrData;
			$objResult->level = $objUser->mb_level;
			$objResult->status = "success";
			$objResult->tree = array_key_exists('app.tree', $_ENV) && $_ENV['app.tree'] == 1;
		
			echo json_encode($objResult);
			
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}


	// //하부회워 정산리력 Ajax로 전송
	// public function calculategame(){ 
	// 	$jsonData = $_REQUEST['json_'];
	// 	$arrReqData = json_decode($jsonData, true);
	// 	// return var_dump($arrReqData);
	// 	if(is_login()) {
	// 		//model
			
	// 		$strUid = $this->session->user_id;
	// 		$objUser = $this->modelMember->getInfo($strUid);
	// 		$arrResult = array();
	// 		$arrEmp = array();
	// 		//회원정보가 없다면
	// 		$bResult = false;
	// 		if($arrReqData['mb_fid'] == 0){	
	// 			if($objUser->mb_level > LEVEL_COMPANY){//관리자이면 부본사정보를 반환
	// 				$arrEmp = $this->modelMember->getMemberByLevel(LEVEL_COMPANY);
	// 				$bResult = true;
					
	// 			} else if($objUser->mb_level >= LEVEL_MIN){	// 가입한 성원자정보를 반환
					
	// 				$arrEmp[0] = $objUser;
	// 				$bResult = true;
	// 			}

	// 		} else {						//요청한 등급이하 정보를 반환
	// 				$objReqEmp = $this->modelMember->getInfoByFid($arrReqData['mb_fid']);
	// 				if(!is_null($objReqEmp)){
	// 					if($objReqEmp->mb_level <= $objUser->mb_level){
	// 						$arrEmp = $this->modelMember->getMemberByEmpFid($objReqEmp->mb_fid, $objReqEmp->mb_level-1);
	// 						if(is_null($arrEmp))	$bResult = false;
	// 						else $bResult = true; 
	// 					}
	// 				}
	// 		}
	// 		$arrData = array();
			
	// 		if($bResult){
	// 			$arrReqData['rw_blank'] = $objUser->mb_level >= LEVEL_ADMIN;


	// 			foreach ($arrEmp as $objEmp) {
    //         		$objCalc['mb_fid'] = $objEmp->mb_fid;
	// 	            $objCalc['mb_uid'] = $objEmp->mb_uid;
	// 	            $objCalc['mb_nickname'] = $objEmp->mb_nickname;
	// 	            $objCalc['mb_emp_fid'] = $objEmp->mb_emp_fid;
	// 	            $objCalc['mb_level'] = $objEmp->mb_level;
	// 				$arrResult = $this->modelMember->calculateByGame($objEmp, $arrReqData);
	// 				if(is_null($arrResult) || count($arrResult) != 7)
	// 					continue;
	// 				$objCalc['mb_money_all'] = $arrResult[0]->result_1 != null ? $arrResult[0]->result_1 : 0 ;		//관리자보유금;
	// 				$objCalc['mb_money_single'] = $arrResult[0]->result_2 != null ? $arrResult[0]->result_2 : 0 ;	//관리자보유금;
	// 				$objCalc['mb_point_all'] = $arrResult[1]->result_1 != null ? $arrResult[1]->result_1 : 0 ;		//관리자보유금;
	// 				$objCalc['mb_point_single'] = $arrResult[1]->result_2 != null ? $arrResult[1]->result_2 : 0 ;	//관리자보유금;
	// 				$objCalc['mb_user_cnt'] = $arrResult[2]->result_1 != null ? $arrResult[2]->result_1 : 0 ;		//관리자보유금;
	// 				$objCalc['mb_charge'] = $arrResult[3]->result_1 != null ? $arrResult[3]->result_1 : 0 ;         //충전금액합산
	// 				$objCalc['mb_exchange'] = $arrResult[4]->result_1 != null ? $arrResult[4]->result_1 : 0;     	//환전금액합산
	// 				$objCalc['mb_charge_benefit'] = $objCalc['mb_charge'] - $objCalc['mb_exchange'];  				//충환손익
	// 				$objCalc['mb_bet_money'] = $arrResult[5]->result_1 != null ? $arrResult[5]->result_1 : 0;		//배팅머니
	// 				$objCalc['mb_bet_win_money'] = $arrResult[5]->result_2 != null ? $arrResult[5]->result_2 : 0;	//적중머니
	// 				$objCalc['mb_bet_benefit_money'] = $objCalc['mb_bet_money'] - $objCalc['mb_bet_win_money'];  	//배팅손익
	// 				$objCalc['mb_rate_all'] =   $arrResult[6]->result_1 != null ? $arrResult[6]->result_1 : 0;		//수수료 합산
	// 				$objCalc['mb_rate_single'] =   $arrResult[6]->result_2 != null ? $arrResult[6]->result_2 : 0;	//수수료 개별
	// 				$objCalc['mb_last_money'] = $objCalc['mb_bet_benefit_money'] - $objCalc['mb_rate_single'] ; 	//최종손익
	
	// 	            $arrData[] = $objCalc;
    //     		}
	// 		}

	// 		$objResult = new \StdClass;			
	// 		$objResult->data = $arrData;
	// 		$objResult->level = $objUser->mb_level;
	// 		$objResult->status = "success";
		
	// 		echo json_encode($objResult);
			
	// 	}
	// 	else{
		
	// 		$arrResult['status'] = "logout";

	// 		echo json_encode($arrResult);	
	// 	} 		
	// }



	//쪽지리스트를 Ajax로 전송
	public function getmessage(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		//var_dump($arrBetData);
		if(is_login()) {
            $this->sess_action();                
			//model
			$noticeModel = new Notice_Model();
			$arrResult = $noticeModel->searchMessage($arrGetData);
		
			$objResult = new \StdClass;
			$objResult->data = $arrResult;			
			$objResult->status = "success";
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}



	//쪽지 개수를 Ajax로 전송
	public function getmessagecnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		//var_dump($arrBetData);
		if(is_login()) {
			//model
			$noticModel = new Notice_Model();
			$objCount = $noticModel->searchMessageCnt($arrGetData);
			
			$arrResult['data'] = $objCount;
			$arrResult['status'] = "success";
		
			echo json_encode($arrResult);
		}
		else{
		
			$arrResult['status'] = "logout";
			echo json_encode($arrResult);	
		} 		
	}

	//배팅리력결과를 Ajax로 전송
	public function csbetlist(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);

		if(is_login()) {
			$tmNow = microtime(true) * 1000;

			if(array_key_exists('auto', $arrGetData) && !$arrGetData['auto']){
				$this->sess_action();                
			}

			//model
			if(array_key_exists('game', $arrGetData) && $arrGetData['game'] == GAME_AUTO_EVOL && isEBalMode()){
				$csbetModel = new EbalBet_Model();
				$csbetModel->setType($arrGetData['game']);
				if(strlen($arrGetData['user']) > 0){
					$objUser = $this->modelMember->getInfo(trim($arrGetData['user']));
					if(!is_null($objUser))
						$arrGetData['user'] = $objUser->mb_fid;
					else $arrGetData['user'] = 0;
				}
			} else if(array_key_exists('game', $arrGetData) && $arrGetData['game'] == GAME_AUTO_PRAG && isPBalMode()){
				$csbetModel = new EbalBet_Model();
				$csbetModel->setType($arrGetData['game']);
				if(strlen($arrGetData['user']) > 0){
					$objUser = $this->modelMember->getInfo(trim($arrGetData['user']));
					if(!is_null($objUser))
						$arrGetData['user'] = $objUser->mb_fid;
					else $arrGetData['user'] = 0;
				}
			} else {
				$csbetModel = new CsBet_Model();
				$arrGetData['type'] = GAME_CASINO_EVOL;
				$this->modelMember->gameRange($arrGetData);
			}
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);


			if($objAdmin->mb_level >= LEVEL_ADMIN && strlen(trim($arrGetData['emp'])) > 0){
				$objAdmin = $this->modelMember->getInfo(trim($arrGetData['emp']));
			} 
			$arrBetResults = $csbetModel->search($objAdmin, $arrGetData);
			
			$objResult = new \StdClass;
			$objResult->data = $arrBetResults;	
			$objResult->status = "success";
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}

	//배팅리력결과 개수를 Ajax로 전송
	public function csbetlistcnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);

		if(is_login()) {
			$tmNow = microtime(true) * 1000;
			//model
			if(array_key_exists('game', $arrGetData) && $arrGetData['game'] == GAME_AUTO_EVOL && isEBalMode()){
				$csbetModel = new EbalBetSt_Model();
				$csbetModel->setType($arrGetData['game']);
				if(strlen($arrGetData['user']) > 0){
					$objUser = $this->modelMember->getInfo(trim($arrGetData['user']));
					if(!is_null($objUser))
						$arrGetData['user'] = $objUser->mb_fid;
					else $arrGetData['user'] = 0;
				}
			} else if(array_key_exists('game', $arrGetData) && $arrGetData['game'] == GAME_AUTO_PRAG && isPBalMode()){
				$csbetModel = new EbalBetSt_Model();
				$csbetModel->setType($arrGetData['game']);
				if(strlen($arrGetData['user']) > 0){
					$objUser = $this->modelMember->getInfo(trim($arrGetData['user']));
					if(!is_null($objUser))
						$arrGetData['user'] = $objUser->mb_fid;
					else $arrGetData['user'] = 0;
				}
			} else {
				$csbetModel = new CsBet_Model();
				$arrGetData['type'] = GAME_CASINO_EVOL;
				$this->modelMember->gameRange($arrGetData, false);
			}
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			
			$arrBetAccount = null;
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				if(strlen(trim($arrGetData['emp'])) > 0){
					$objAdmin = $this->modelMember->getInfo(trim($arrGetData['emp']));
				}
			}
			$arrBetAccount = $csbetModel->getBetAccount($objAdmin, $arrGetData);
			$objCount = $csbetModel->searchCount($objAdmin, $arrGetData);
			
			$arrResult['data'] = $objCount;
			$arrResult['account'] = $arrBetAccount;
			$arrResult['status'] = "success";
		
			echo json_encode($arrResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}

	//배팅리력결과를 Ajax로 전송
	public function ebalancelist(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);

		if(is_login()) {
            $this->sess_action();                
			//model
			$ebetModel = new Ebalance_Model();
			$ebetModel->setType($arrGetData['game']);
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);

			$arrBetResults = null;
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				$arrBetResults = $ebetModel->searchList($arrGetData);
			} 
			
			$objResult = new \StdClass;
			$objResult->data = $arrBetResults;	
			$objResult->status = "success";
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}



	//배팅리력결과 개수를 Ajax로 전송
	public function ebalancecnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);

		if(is_login()) {
			//model
			$ebetModel = new Ebalance_Model();
			$ebetModel->setType($arrGetData['game']);

			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			
			$objCount = null;
			$arrBetAccount = null;
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				$objCount = $ebetModel->searchCount($arrGetData);
				$arrBetAccount = $ebetModel->getBetAccount($arrGetData);
			}			

			$arrResult['data'] = $objCount;
			$arrResult['account'] = $arrBetAccount;
			$arrResult['status'] = "success";
		
			echo json_encode($arrResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}

	//배팅리력결과를 Ajax로 전송
	public function eorderlist(){ 

		if(is_login()) {
            $this->sess_action();                
			//model
			$eorderModel = new Eorder_Model();
			$confsiteModel = new ConfSite_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);

			$stats = null;
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				$stats = $eorderModel->getStatsByRound();	
			}

			$objResult = new \StdClass;
			$objResult->data = $stats;	
			$objResult->status = "success";
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}
	//배팅리력결과를 Ajax로 전송
	public function eroomlist(){ 
		$jsonData = $_REQUEST['json_'];
		$arrData = json_decode($jsonData, true);

		if(is_login()) {
			//model
			$casRoomModel = new CasRoom_Model();
			$confsiteModel = new ConfSite_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);

			$arrRoom = null;
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				
				$arrRoom = $casRoomModel->gets($arrData['game']);	
			}

			$objResult = new \StdClass;
			$objResult->data = $arrRoom;	
			$objResult->status = "success";
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}

	public function eroomstate(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login()) {
			//model
			$casRoomModel = new CasRoom_Model();
			$confsiteModel = new ConfSite_Model();

			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				$casRoomModel->setState($arrReqData['id'], $arrReqData);
				$arrResult['status'] = "success";
			} else 
				$arrResult['status'] = "fail";
		
			echo json_encode($arrResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}

	//배팅리력결과를 Ajax로 전송
	public function slbetlist(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);

		if(is_login()) {
			//model
			$slbetModel = new SlBet_Model();
			$confsiteModel = new ConfSite_Model();
			$confsiteModel->readBetConf();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level >= LEVEL_ADMIN && strlen(trim($arrGetData['emp'])) > 0){
				$objAdmin = $this->modelMember->getInfo(trim($arrGetData['emp']));
			}
			$arrBetResults = $slbetModel->search($objAdmin, $arrGetData);
			
			$objResult = new \StdClass;
			$objResult->data = $arrBetResults;	
			$objResult->status = "success";
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}



	//배팅리력결과 개수를 Ajax로 전송
	public function slbetlistcnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);

		if(is_login()) {
			//model
			$slbetModel = new SlBet_Model();
			$confsiteModel = new ConfSite_Model();
			$confsiteModel->readBetConf();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			
			$arrBetAccount = null;
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				if(strlen(trim($arrGetData['emp'])) > 0){
					$objAdmin = $this->modelMember->getInfo(trim($arrGetData['emp']));
				}
			} 
			$arrBetAccount = $slbetModel->getBetAccount($objAdmin, $arrGetData);
			$objCount = $slbetModel->searchCount($objAdmin, $arrGetData);
			
			$arrResult['data'] = $objCount;
			$arrResult['account'] = $arrBetAccount;	
			$arrResult['status'] = "success";
		
			echo json_encode($arrResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}

	
	public function hlbetlist(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);

		if(is_login()) {
            $this->sess_action();                
			//model
			$hlbetModel = new HlBet_Model();
			$confsiteModel = new ConfSite_Model();
			$confsiteModel->readBetConf();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);

			$arrBetAccount = null;
			$lvAdmin = $objAdmin->mb_level;
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				if(strlen(trim($arrGetData['emp'])) > 0){
					$objAdmin = $this->modelMember->getInfo(trim($arrGetData['emp']));
				}
			}
			$arrBetAccount = $hlbetModel->getBetAccount($objAdmin, $arrGetData);
			$arrBetResults = $hlbetModel->search($objAdmin, $arrGetData, $lvAdmin>=LEVEL_ADMIN+1);
			
			$objResult = new \StdClass;
			$objResult->data = $arrBetResults;	
			$objResult->status = "success";
			$objResult->account = $arrBetAccount;	
			$objResult->level = $lvAdmin;
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}



	public function hlbetlistcnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);

		if(is_login()) {
			//model
			$hlbetModel = new HlBet_Model();
			$confsiteModel = new ConfSite_Model();
			$confsiteModel->readBetConf();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				if(strlen(trim($arrGetData['emp'])) > 0){
					$objAdmin = $this->modelMember->getInfo(trim($arrGetData['emp']));
				}
			} 
			$objCount = $hlbetModel->searchCount($objAdmin, $arrGetData);
			
			$arrResult['data'] = $objCount;
			$arrResult['status'] = "success";
		
			echo json_encode($arrResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 		
	}

	public function fslotlist(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$objResult = new \StdClass;
		if(is_login()) {
			//model
			$slgameModel = new SlotGame_Model();
			
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$objResult->status = "success";
				$objResult->data = $slgameModel->search($arrReqData);
				$objResult->app = $_ENV['app.type'];
			}
		
		}
		else{
			$objResult->status = "logout";

		}
		echo json_encode($objResult);

	}

	public function fslotcnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$objResult = new \StdClass;
		if(is_login()) {
			//model
			$slgameModel = new SlotGame_Model();
			
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$objResult->status = "success";
				$objResult->data = $slgameModel->searchCount($arrReqData);
			}
		
		}
		else{
			$objResult->status = "logout";

		}
		echo json_encode($objResult);
	}

	public function fslotset(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$objResult = new \StdClass;
		if(is_login()) {
			//model
			$slgameModel = new SlotGame_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$slgameModel->changeAct($arrReqData);

				$objResult->status = "success";
			}
		
		}
		else{
			$objResult->status = "logout";

		}
		echo json_encode($objResult);
	}

	
	public function xslotlist(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$objResult = new \StdClass;
		if(is_login()) {
			//model
			$slprdModel = new SlotPrd_Model();
			
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$objResult->status = "success";
				$objResult->data = $slprdModel->search($arrReqData);
			}
		
		}
		else{
			$objResult->status = "logout";

		}
		echo json_encode($objResult);

	}

	public function xslotcnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$objResult = new \StdClass;
		if(is_login()) {
			//model
			$slprdModel = new SlotPrd_Model();
			
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$objResult->status = "success";
				$objResult->data = $slprdModel->searchCount($arrReqData);
			}
		
		}
		else{
			$objResult->status = "logout";

		}
		echo json_encode($objResult);
	}

	public function xslotset(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$objResult = new \StdClass;
		if(is_login()) {
			//model
			$slprdModel = new SlotPrd_Model();
			
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$slprdModel->changeAct($arrReqData);

				$objResult->status = "success";
			}
		
		}
		else{
			$objResult->status = "logout";

		}
		echo json_encode($objResult);
	}
	
	public function kgonlist(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$objResult = new \StdClass;
		if(is_login()) {
			//model
			$casprdModel = new CasPrd_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$objResult->status = "success";
				$objResult->data = $casprdModel->search($arrReqData);
			}
		
		}
		else{
			$objResult->status = "logout";

		}
		echo json_encode($objResult);

	}

	public function kgoncnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$objResult = new \StdClass;
		if(is_login()) {
			//model
			$casprdModel = new CasPrd_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$objResult->status = "success";
				$objResult->data = $casprdModel->searchCount($arrReqData);
				if($arrReqData['cat'] == GAME_CASINO_KGON || $arrReqData['cat'] == GAME_CASINO_TREEM)
					$objResult->ranges = getRangeKeys($arrReqData['cat']);

			}
		
		}
		else{
			$objResult->status = "logout";

		}
		echo json_encode($objResult);
	}

	public function kgonset(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$objResult = new \StdClass;
		if(is_login()) {
            $this->sess_action();                
			//model
			$casprdModel = new CasPrd_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$casprdModel->changeAct($arrReqData);

				$objResult->status = "success";
			}
		
		}
		else{
			$objResult->status = "logout";

		}
		echo json_encode($objResult);
	}
	
	public function getevpress(){ 
		
		$objResult = new \StdClass;
		if(is_login()) {
			//model
			$confsiteModel = new ConfSite_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$objResult->data = $confsiteModel->getEvpressConfig();
				$objResult->status = "success";
			}
		}
		else{
			$objResult->status = "logout";
		}
		echo json_encode($objResult);
	}

	public function setevpress(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$objResult = new \StdClass;
		if(is_login()) {
            $this->sess_action();                
			//model
			$confsiteModel = new ConfSite_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$confsiteModel->saveEvpressConfig($arrReqData);
				
				$objResult->status = "success";
			}
		}
		else{
			$objResult->status = "logout";
		}
		echo json_encode($objResult);
	}

	public function changeevpress(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$objResult = new \StdClass;
		if(is_login()) {
            $this->sess_action();                
			//model
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			$objReqUser = $this->modelMember->getInfoByFid($arrReqData['mb_fid']);
			if($objAdmin->mb_level < LEVEL_ADMIN || is_null($objReqUser)){
				$objResult->status = "fail";
			} else {
				
				if($this->modelMember->updateState($objReqUser, $arrReqData)){
					$ebalLogModel = new EbalLog_Model();
					$data =[
						'log_mb_fid' => $objReqUser->mb_fid,
						'log_mb_uid' => $objReqUser->mb_uid,
						'log_data' => $arrReqData['mb_state_view'] == 1 ? "누르기":"넘기기",
						'log_type' => EBAL_LOGTYPE_PRESSMANUAL,
						'log_memo' => $objAdmin->mb_uid,
						'log_time' => date("Y-m-d H:i:s"),
					];
					$ebalLogModel->register($data);
				}
				
				$objResult->status = "success";
			}
		}
		else{
			$objResult->status = "logout";
		}
		echo json_encode($objResult);
	}

	public function evpresslist(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login())
		{

            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            if($objUser->mb_level  >= LEVEL_ADMIN) {

                $arrData = $this->modelSess->searchPress($arrReqData, $objUser->mb_level);
                    
                $arrResult['data'] = $arrData;
                $arrResult['status'] = "success";
            } else{
                $arrResult['status'] = "fail";
            }

		}
		else{
			$arrResult['status'] = "logout";
		}
		echo json_encode($arrResult);
	}

	public function eballoglist(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$objResult = new \StdClass;
		if(is_login()) {
			//model
			$ebalLogModel = new EbalLog_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$objResult->status = "success";
				$objResult->data = $ebalLogModel->search($arrReqData);
			}
		
		}
		else{
			$objResult->status = "logout";

		}
		echo json_encode($objResult);

	}

	public function eballogcnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$objResult = new \StdClass;
		if(is_login()) {
			//model
			$ebalLogModel = new EbalLog_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$objResult->status = "success";
				$objResult->data = $ebalLogModel->searchCount($arrReqData);
			}
		
		}
		else{
			$objResult->status = "logout";

		}
		echo json_encode($objResult);
	}

	public function domainlist(){

		$objResult = new \StdClass;
		if(is_login()) {
			//model
			$domainModel = new Domain_Model();
			
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);
			if($objAdmin->mb_level < LEVEL_ADMIN){
				$objResult->status = "fail";
			} else {
				$objResult->status = "success";
				$objResult->data = $domainModel->search();
			}
		}
		else{
			$objResult->status = "logout";

		}
		echo json_encode($objResult);

	}

	public function addDomain(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		if(is_login()) {
            $this->sess_action();                
			//model
			$domainModel = new Domain_Model();

			$domainModel->add($arrGetData);
		
			$objResult = new \StdClass;
			$objResult->status = "success";
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";
			echo json_encode($arrResult);	
		} 		
	}

	public function deleteDomain(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		if(is_login()) {
            $this->sess_action();                
			//model
			$domainModel = new Domain_Model();
			$arrResult = $domainModel->delete($arrGetData['conf_id']);
		
			$objResult = new \StdClass;
			$objResult->status = "success";
			$objResult->data = $arrResult;
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";
			echo json_encode($arrResult);	
		} 		
	}

	public function updateDomain(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		if(is_login()) {
            $this->sess_action();                
			//model
			$domainModel = new Domain_Model();
			$arrResult = $domainModel->updateInfo($arrGetData['conf_id'], $arrGetData);
		
			$objResult = new \StdClass;
			$objResult->status = "success";
			$objResult->data = $arrResult;
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";
			echo json_encode($arrResult);	
		} 		
	}
	//DB 정리
	public function cleanDb(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login()) {
			
			$cleanModel = new Clean_Model();

			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);

			$iResult = 0;

			if($arrReqData['type'] == 0 && $objAdmin->mb_level>LEVEL_MASTER){
				$iResult = $cleanModel->cleanDb();
				$this->modelModify->add($this->session->user_id, MOD_DB_DELETE, "Delete DB", $this->request->getIPAddress());
			} else if($arrReqData['type'] == 1  && $objAdmin->mb_level>LEVEL_MASTER){
				$iResult = $cleanModel->initDb();
				$this->modelModify->add($this->session->user_id, MOD_DB_DELETE, "Clear DB", $this->request->getIPAddress());
			} else if($arrReqData['type'] == 2 && $objAdmin->mb_level>=LEVEL_ADMIN){
				$iResult = $cleanModel->cleanPartition($arrReqData['date']);
				$this->modelModify->add($this->session->user_id, MOD_DB_DELETE, "drop partition", $this->request->getIPAddress());
			} 
			
			$arrResult['status'] = $iResult==1?"success":"fail";
			$arrResult['data'] = $iResult;

			echo json_encode($arrResult);	
		
		} else {
			$arrResult['status'] = "logout";
			echo json_encode($arrResult);	
		}
	}

	public function getmacro(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		//var_dump($arrBetData);
		if(is_login()) {
			//model
			$confMsgModel = new ConfMsg_Model();
			$arrResult = $confMsgModel->search($arrGetData);
		
			$objResult = new \StdClass;
			$objResult->data = $arrResult;			
			$objResult->status = "success";
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";
			echo json_encode($arrResult);	
		} 		
	}

	public function getmacrocnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		if(is_login()) {
			//model
			$confMsgModel = new ConfMsg_Model();
			$objCount = $confMsgModel->searchCount($arrGetData);
			
			$arrResult['data'] = $objCount;
			$arrResult['status'] = "success";
		
			echo json_encode($arrResult);
		}
		else{
		
			$arrResult['status'] = "logout";
			echo json_encode($arrResult);	
		} 		
	}

	public function addmacro(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		if(is_login()) {
            $this->sess_action();                
			//model
			$confMsgModel = new ConfMsg_Model();
			$arrResult = $confMsgModel->add($arrGetData);
		
			$objResult = new \StdClass;
			$objResult->status = "success";
			$objResult->data = $arrResult;
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";
			echo json_encode($arrResult);	
		} 		
	}
	
	public function modifymacro(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		if(is_login()) {
            $this->sess_action();                
			//model
			$confMsgModel = new ConfMsg_Model();
			$arrResult = $confMsgModel->modify($arrGetData['id'], $arrGetData);
		
			$objResult = new \StdClass;
			$objResult->status = "success";
			$objResult->data = $arrResult;
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";
			echo json_encode($arrResult);	
		} 		
	}

	public function deletemacro(){ 
		$jsonData = $_REQUEST['json_'];
		$arrGetData = json_decode($jsonData, true);
		if(is_login()) {
            $this->sess_action();                
			//model
			$confMsgModel = new ConfMsg_Model();
			$arrResult = $confMsgModel->delete($arrGetData['conf_id']);
		
			$objResult = new \StdClass;
			$objResult->status = "success";
			$objResult->data = $arrResult;
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";
			echo json_encode($arrResult);	
		} 		
	}

}
    