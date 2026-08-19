<?php

namespace App\Controllers;

use App\Models\Charge_Model;
use App\Models\ConfGame_Model;
use App\Models\ConfSite_Model;
use App\Models\Exchange_Model;
use App\Models\Notice_Model;
use App\Models\PbBet_Model;
use App\Models\CsBet_Model;
use App\Models\SlBet_Model;
use App\Models\HlBet_Model;
use App\Models\EbalBet_Model;
use App\Models\EbalBetSt_Model;
use App\Models\EbalLog_Model;
use App\Models\MoneyHistory_Model;
use App\Models\SessLog_Model;
use App\Models\SessTry_Model;
use App\Models\Block_Model;
use App\Models\MemConf_Model;

use App\Libraries\ApiCas_Lib;
use App\Libraries\ApiSlot_Lib;
use App\Libraries\ApiFslot_Lib;

class UserApi extends BaseController
{
    public function index()
    {
        if (is_login()) {
            $this->response->redirect($_ENV['app.furl']);
        } else {
            $this->response->redirect($_ENV['app.furl'].'/pages/login');
        }
    }

    // 사용자 정보 변경
    public function addmember()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);
        if (is_login()) {
            $bPermit = false;
            
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);
            $arrData['mb_level'] = LEVEL_COMPANY;
            $arrData['mb_emp_fid'] = 0;
            if (strlen($arrData['mb_emp_uid']) > 0){
                $objEmp = $this->modelMember->getInfo($arrData['mb_emp_uid']);
                $minLevel = LEVEL_MIN;
                // writeLog("Level" . intval($_ENV['app.level_limit']));
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

                if ($objEmp == null || $objEmp->mb_level <= $minLevel || $recommenderDeny){
                    $arrResult['status'] = 'employee_error';
                    echo json_encode($arrResult);
                    return;
                }
                $arrData['mb_emp_fid'] = $objEmp->mb_fid;
                $arrData['mb_level'] = $objEmp->mb_level - 1;                
            }

            if (!is_null($objUser)) {
                if ($objUser->mb_level >= LEVEL_ADMIN) {
                    $bPermit = true;
                }
            }
            if ($bPermit) {
                $strError = '';
                $confsiteModel = new ConfSite_Model();
                $confsiteModel->readMemConf();

                $checkOk = validUserId($arrData['mb_uid']);
                if(!$checkOk){
                    $strError  = ["아이디는 4자~16자, 영문 또는 숫자만 사용 가능합니다."];
                } else {
                    $checkOk = validUserPw($arrData['mb_pwd']);
        
                    if(!$checkOk)
                        $strError  = ["비밀번호는 8자~20자, 특수문자 한개 이상 입력하셔야 합니다."];
                }

                if($checkOk)
                    $iResult = $this->modelMember->register($arrData, $strError);
                else $iResult = -1;

                if ( $iResult == 1 ) {
                    $objReqUser = $this->modelMember->getInfo(trim($arrData['mb_uid']));

                    if(!is_null($objReqUser) && isEBalMode() && array_key_exists('mb_state_view', $arrData) ){
                        $ebalLogModel = new EbalLog_Model();

                        $data =[
                            'log_mb_fid' => $objReqUser->mb_fid,
                            'log_mb_uid' => $objReqUser->mb_uid,
                            'log_data' => $arrData['mb_state_view'] == 1 ? "누르기":"넘기기",
                            'log_type' => EBAL_LOGTYPE_PRESSMANUAL,
                            'log_memo' => $objUser->mb_uid,
                            'log_time' => date("Y-m-d H:i:s"),
                        ];
                        $ebalLogModel->register($data);
                    }

                    $arrResult['status'] = STATUS_SUCCESS;
                } elseif ( $iResult == 4 || $iResult == 5 ) {
                    $arrResult['status'] = 'ratio_error';
                    $arrResult['error'] = $strError;
                } elseif ( $iResult == -1 ) {
                    $arrResult['status'] = 'val_error';
                    $arrResult['error'] = $strError;
                } else {
                    $arrResult['status'] = STATUS_FAIL;
                    $arrResult['error'] = $iResult;
                }
            } else {
                $arrResult['status'] = STATUS_NOPERMIT;
            }
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
        }
        echo json_encode($arrResult);
    }

    // 사용자 정보 수정
    public function modifymember()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            $bPermit = false;
            
            $strUid = $this->session->user_id;
            $objAdmin = $this->modelMember->getInfo($strUid);
            $objReqUser = $this->modelMember->getInfoByFid($arrData['mb_fid'], true);
            $arrData['mb_emp_fid'] = 0;
            if ($objAdmin->mb_level >= LEVEL_ADMIN && strlen($arrData['mb_emp_uid']) > 0){
                $objEmp = $this->modelMember->getInfo($arrData['mb_emp_uid']);

                $recommenderDeny = false;
                if(!is_null($objEmp) && $objEmp->mb_fid != $objReqUser->mb_emp_fid && array_key_exists('app.sess_act', $_ENV) && $_ENV['app.sess_act'] == 1){
                    $memConfModel = new MemConf_Model();
                    $memConf = $memConfModel->getByMember($objEmp->mb_fid);
                    if(!is_null($memConf) ){
                        $recommenderDeny = $memConf->conf_num_2 == STATE_ACTIVE;
                    }
                }

                if ($objEmp == null || $recommenderDeny){
                    $arrResult['status'] = 'employee_error';
                    echo json_encode($arrResult);
                    return;
                } 
                $arrData['mb_emp_fid'] = $objEmp->mb_fid;
            }
            
            if (!is_null($objAdmin) && !is_null($objReqUser)) {
                if ($objAdmin->mb_level > $objReqUser->mb_level) {
                    $bPermit = true;
                }
            }
            if ($bPermit) {
                $strError = '';
                $query = "";
                $iResult = 0;
                if ($objAdmin->mb_level >= LEVEL_ADMIN) {

                    $checkOk = true;
                    if($arrData['mb_pwd'] !== $objReqUser->mb_pwd){
                        $checkOk = validUserPw($arrData['mb_pwd']);
            
                        if(!$checkOk)
                            $strError  = ["비밀번호는 8자~20자, 특수문자 한개 이상 입력하셔야 합니다."];
                    }

                    if($checkOk)
                        $iResult = $this->modelMember->modifyMember($objReqUser, $arrData, $strError, $query);
                    else $iResult = -1;

                    if($iResult==1){

                        if(isEBalMode() && array_key_exists('mb_state_view', $arrData) ){

                            if($objReqUser->mb_state_view != $arrData['mb_state_view']){
                                $ebalLogModel = new EbalLog_Model();
    
                                $data =[
                                    'log_mb_fid' => $objReqUser->mb_fid,
                                    'log_mb_uid' => $objReqUser->mb_uid,
                                    'log_data' => $arrData['mb_state_view'] == 1 ? "누르기":"넘기기",
                                    'log_type' => EBAL_LOGTYPE_PRESSMANUAL,
                                    'log_memo' => $objAdmin->mb_uid,
                                    'log_time' => date("Y-m-d H:i:s"),
                                ];
                                $ebalLogModel->register($data);
                            }
                        }

                        if(array_key_exists('mb_autoapps', $arrData) ){
                            $memConfModel = new MemConf_Model();
                            $memConf = $memConfModel->getByMember($objReqUser->mb_fid);
                            if(!is_null($memConf) ){
                                $data =[
                                    'conf_num_1' => $arrData['mb_transfer_subs'],
                                    'conf_num_2' => $arrData['mb_recommender_deny'],
                                    'conf_num_3' => $arrData['mb_range_limit'],
                                    'conf_num_4' => $arrData['mb_overbet_percent'],
                                    'conf_str_1' => $arrData['mb_autoapps'],
                                    'conf_str_5' => $arrData['mb_charge_info'],
                                ];
                                $memConfModel->updateData($memConf, $data);
                            } else {
                                $data =[
                                    'conf_mb_fid' => $objReqUser->mb_fid,
                                    'conf_mb_uid' => $objReqUser->mb_uid,
                                    'conf_num_1' => $arrData['mb_transfer_subs'],
                                    'conf_num_2' => $arrData['mb_recommender_deny'],
                                    'conf_num_3' => $arrData['mb_range_limit'],
                                    'conf_num_4' => $arrData['mb_overbet_percent'],
                                    'conf_str_1' => $arrData['mb_autoapps'],
                                    'conf_str_5' => $arrData['mb_charge_info'],
                                ];
                                $memConfModel->register($data);
                            }
                        }

                    }

                } else {
                    $iResult = $this->modelMember->modifyMemberRatio($objReqUser, $arrData, $strError,  $query);
                }

                if ($iResult == 1) {
				    $this->modelModify->add($this->session->user_id, MOD_MB_INFO, $query, $this->request->getIPAddress());
                    $arrResult['status'] = STATUS_SUCCESS;
                } elseif ( $iResult == 4 || $iResult == 5 ) {
                    $arrResult['status'] = 'ratio_error';
                    $arrResult['error'] = $strError;
                } elseif ( $iResult == 7 ) {
                    $arrResult['status'] = 'level_error';
                    $arrResult['error'] = $strError;
                } elseif ( $iResult == -1 ) {
                    $arrResult['status'] = 'val_error';
                    $arrResult['error'] = $strError;
                } else {
                    $arrResult['status'] = STATUS_FAIL;
                    $arrResult['error'] = $iResult;
                }
            } else {
                $arrResult['status'] = STATUS_NOPERMIT;
            }
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
        }
        echo json_encode($arrResult);
    }

    // 사용자 정보 변경
    public function updatemember()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            $bPermit = false;
            
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);
            $objReqUser = $this->modelMember->getInfoByFid($arrData['mb_fid']);

            if (!is_null($objUser) && !is_null($objReqUser)) {
                if ($objUser->mb_level >= LEVEL_ADMIN) {
                    $bPermit = true;
                }
            }
            if ($bPermit) {
                $query = "";
                $bResult = $this->modelMember->updateMemberByFid($arrData, $query);
                if ($bResult) {
                    $this->modelModify->add($this->session->user_id, MOD_MB_STATE, $query, $this->request->getIPAddress());
                    $arrResult['status'] = STATUS_SUCCESS;
                } else {
                    $arrResult['status'] = STATUS_FAIL;
                }
            } else {
                $arrResult['status'] = STATUS_NOPERMIT;
            }
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
        }
        echo json_encode($arrResult);
    }
    // 사용자 삭제
    public function updatemembers()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            $bPermit = false;
            
            $strUid = $this->session->user_id;
            $objAdmin = $this->modelMember->getInfo($strUid);

            if (!is_null($objAdmin)) {
                if ($objAdmin->mb_level >= LEVEL_ADMIN) {
                    $bPermit = true;
                }
            }
            if ($bPermit) {
                $arrData['mb_fids'] = [];

                $query = '';
                $bResult = $this->modelMember->updateMemberByFids($arrData, $query);
                
                if ($bResult) {
                    $this->modelModify->add($this->session->user_id, MOD_MB_STATE, $query, $this->request->getIPAddress());
                    $arrResult['status'] = STATUS_SUCCESS;
                } else {
                    $arrResult['status'] = STATUS_FAIL;
                }
            } else {
                $arrResult['status'] = STATUS_NOPERMIT;
            }
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
        }
        echo json_encode($arrResult);
    }
    // 사용자 삭제
    public function deletemember()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            $bPermit = false;
            
            $strUid = $this->session->user_id;
            $objAdmin = $this->modelMember->getInfo($strUid);
            $objReqUser = $this->modelMember->getInfoByFid($arrData['mb_fid']);

            if (!is_null($objAdmin) && !is_null($objReqUser)) {
                if ($objAdmin->mb_level >= LEVEL_ADMIN) {
                    $bPermit = true;
                    if(array_key_exists('app.delete_level', $_ENV) && $objAdmin->mb_level < $_ENV['app.delete_level'])
                        $bPermit = false;
                }
            }
            if ($bPermit) {
                $arrData['mb_state_active'] = PERMIT_DELETE;
                $arrData['mb_fids'] = [$objReqUser->mb_fid];
                $arrMem = $this->modelMember->getMemberByEmpFid($objReqUser->mb_fid, $objReqUser->mb_level,  $objReqUser->mb_level, true);
                foreach($arrMem as $objMem)
                    array_push($arrData['mb_fids'], $objMem->mb_fid);

                $query = '';
                $bResult = $this->modelMember->updateMemberByFids($arrData, $query);
                
                if ($bResult) {
                    $this->modelModify->add($this->session->user_id, MOD_MB_STATE, $query, $this->request->getIPAddress());
                    $arrResult['status'] = STATUS_SUCCESS;
                } else {
                    $arrResult['status'] = STATUS_FAIL;
                }
            } else {
                $arrResult['status'] = STATUS_NOPERMIT;
            }
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
        }
        echo json_encode($arrResult);
    }

    // 사용자 강제아웃
    public function logoutmember()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            $bPermit = false;
            $confsiteModel = new ConfSite_Model();
            
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);
            $objReqUser = $this->modelMember->getInfoByFid($arrData['mb_fid']);

            if (!is_null($objUser) && !is_null($objReqUser)) {
                if ($objUser->mb_level >= LEVEL_ADMIN) {
                    $bPermit = true;
                }
            }
            if ($bPermit) {
                $siteConfs = $this->getSiteConf($confsiteModel);
                
                if(!$siteConfs['hold_deny']){
                    $this->libApiHold->logout($objReqUser->mb_hold_uid);
                }

                $bResult = $this->modelSess->deleteByMember($objReqUser->mb_fid);
                
                if ($bResult) {
                    $arrResult['status'] = STATUS_SUCCESS;
                } else {
                    $arrResult['status'] = STATUS_FAIL;
                }
            } else {
                $arrResult['status'] = STATUS_NOPERMIT;
            }
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
        }
        echo json_encode($arrResult);
    }

    public function wait_permit()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {

            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);
            $objReqUser = $this->modelMember->getInfoByFid($arrData['mb_fid']);

            $bPermit = false;
            $bResult = false;

            if (!is_null($objUser) && !is_null($objReqUser)) {
                if ($objReqUser->mb_state_active != PERMIT_REQ && $objReqUser->mb_state_active != PERMIT_WAIT) {
                    $bPermit = false;
                }
                elseif ($objUser->mb_level > $objReqUser->mb_level) {
                    $bPermit = true;
                }
            }

            if ($bPermit) {
			    $query = "";
                $bResult = $this->modelMember->updateMemberByFid($arrData, $query);

                if ($bResult) {
                    $this->modelModify->add($this->session->user_id, MOD_MB_STATE, $query, $this->request->getIPAddress());

                    $arrResult['status'] = STATUS_SUCCESS;
                } else {
                    $arrResult['status'] = STATUS_FAIL;
                }
            } else {
                $arrResult['status'] = STATUS_NOPERMIT;
            }
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
        }
        echo json_encode($arrResult);
    }

    public function delete_restore()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {

            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);
            $objReqUser = $this->modelMember->getInfoByFid($arrData['mb_fid']);

            $bPermit = false;

            $objEmp = null;
            if (!is_null($objUser) && !is_null($objReqUser)) {

                if($objReqUser->mb_level < LEVEL_COMPANY)
                    $objEmp = $this->modelMember->getInfoByFid($objReqUser->mb_emp_fid);
                else $objEmp = $objUser;

                if ($objUser->mb_level >= LEVEL_ADMIN && !is_null($objEmp) && $objEmp->mb_state_active != PERMIT_DELETE) {
                    $bPermit = true;
                    
                }
            }

            if ($bPermit) {
			    $query = "";

                $bResult = $this->modelMember->updateMemberByFid($arrData, $query);

                if ($bResult) {
                    $this->modelModify->add($this->session->user_id, MOD_MB_STATE, $query, $this->request->getIPAddress());

                    $arrResult['status'] = STATUS_SUCCESS;
                } else {
                    $arrResult['status'] = STATUS_FAIL;
                }
            } else if (!is_null($objReqUser) && $objReqUser->mb_state_active != PERMIT_DELETE) {
                $arrResult['status'] = STATUS_SUCCESS;
            } else if (!is_null($objEmp) && $objEmp->mb_state_active == PERMIT_DELETE) {
                $arrResult['status'] = 'usererror';
            } else {
                $arrResult['status'] = STATUS_NOPERMIT;
            }
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
        }
        echo json_encode($arrResult);
    }
    
    public function auto_stop()
    {
        if (is_login()) {

            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            $bPermit = false;

            if (!is_null($objUser) && $objUser->mb_level >= LEVEL_ADMIN) {
                $bPermit = true;
            }

            if ($bPermit) {
                $tmNow = time();
                $tmWeek = strtotime("-1 month", $tmNow);
                $dtLast = date("Y-m-d", $tmWeek);

                $affectedRows = $this->modelMember->setAutoStop($dtLast);

                $arrResult['msg'] = $affectedRows."명이 자동차단되었습니다.";
                $arrResult['status'] = STATUS_SUCCESS;
            } else {
                $arrResult['status'] = STATUS_NOPERMIT;
            }
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
        }
        echo json_encode($arrResult);
    }
    // 사용자정보
    public function assets()
    {
        if (is_login()) {
            $strUid = $this->session->user_id;
            // model
            
            $modelConfsite = new ConfSite_Model();
            $objUser = $this->modelMember->getInfoByUid($strUid);
            $sess_id = $this->session->session_id;
			$this->modelSess->deleteLast();
			$sess = $this->modelSess->getBySess($sess_id);

            $objResult = new \stdClass();

            $bPermit = true;
            if (is_null($objUser)) {
				$bPermit = false;
                writeLog("[assets] user = null (".$strUid.")");
            }
            else if($objUser->mb_level < LEVEL_ADMIN && $modelConfsite->IsMaintain())
				$bPermit = false;
            else if( !$this->modelMember->isPermitMember($objUser) )
				$bPermit = false;
            else if( is_null($sess) ){
                writeLog("[assets] session = null (".$sess_id.")");
				$bPermit = false;
            } else if(array_key_exists('app.sess_act', $_ENV) && $_ENV['app.sess_act'] == 1){
                $objConf = $modelConfsite->find(CONF_DELAY_PLAY);
				$delayOut = 0;
				$arrInfo = explode('#', $objConf->conf_idx);
				if(count($arrInfo) >= 2){
					if($objUser->mb_level < LEVEL_ADMIN)
						$delayOut = intval($arrInfo[0]);
					else
						$delayOut = intval($arrInfo[1]);
				}

				if($delayOut > 0 && diffDt(date('Y-m-d H:i:s'), $sess->sess_action) > $delayOut * 60){
					$bPermit = false;
					writeLog("[check_session] session_action = ".$sess->sess_action." (".$sess_id.")");
				}
                
			}
            
            if ($bPermit) {
        		// writeLog("[assets] ".$strUid." (".$sess_id.")");
				$this->modelSess->updateLast($sess_id);
                if($objUser->mb_level < LEVEL_ADMIN){
                    $this->modelMember->calcTransfer($objUser);
                    $objUser->mb_money_all = allMoney($objUser);
                    $objUser->mb_point = floor($objUser->mb_point);
                }
                else{
				    $objSess = $this->modelSess->getBySess($sess_id);
                    if($objSess != null)
                        $objUser->mb_ip_login = $objSess->sess_ip;
                }
                $objResult->data = $objUser;
                $objResult->status = STATUS_SUCCESS;
            } else {
                writeLog("[assets] logout (".$sess_id.")");
				$this->sess_destroy();
                $objResult->status = STATUS_LOGOUT;
            }

            echo json_encode($objResult);
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
            echo json_encode($arrResult);
        }
    }

    // 가입한 직원의 정보: 머니, 배당율
    public function empinfo()
    {
        if (is_login()) {
            $strUid = $this->session->user_id;
            // model
            $confsiteModel = new ConfSite_Model();

            $objUser = $this->modelMember->getInfo($strUid);
            $objResult = new \stdClass();
            if ($objUser->mb_level >= LEVEL_ADMIN) {
                $arrReqData['start'] = date('Y-m-d');
                $arrReqData['end'] = $arrReqData['start'];

                $arrEmpInfo = null;
                $arrResult = $this->modelMember->getEmpInfo($objUser, $arrReqData);
                if(!is_null($arrResult) && count($arrResult) == 8){
                    $arrEmpInfo['wait_user'] = $arrResult[0]->result_1 != null ? $arrResult[0]->result_1 : 0 ;		    //대기
                    $arrEmpInfo['wait_charge'] = $arrResult[1]->result_1 != null ? $arrResult[1]->result_1 : 0 ;		//충전대기
                    $arrEmpInfo['moment_charge'] = $arrResult[1]->result_2 != null ? $arrResult[1]->result_2 : 0 ;		//충전대기
                    $arrEmpInfo['wait_exchange'] = $arrResult[2]->result_1 != null ? $arrResult[2]->result_1 : 0 ;		//환전대기
                    $arrEmpInfo['moment_exchange'] = $arrResult[2]->result_2 != null ? $arrResult[2]->result_2 : 0 ;   //환전대기
                    $arrEmpInfo['new_message'] = $arrResult[3]->result_1 != null ? $arrResult[3]->result_1 : 0 ;		//문의대기
                    $arrEmpInfo['emp_money'] = $arrResult[4]->result_1 != null ? intval($arrResult[4]->result_1) : 0 ;		//보유머니
                    $arrEmpInfo['emp_point'] = $arrResult[4]->result_2 != null ? intval($arrResult[4]->result_2) : 0 ;		//포유포인트
                    $arrEmpInfo['emp_money_charge'] = $arrResult[5]->result_1 != null ? $arrResult[5]->result_1 : 0 ;     //충전금액
                    $arrEmpInfo['emp_money_exchange'] = $arrResult[6]->result_1 != null ? $arrResult[6]->result_1 : 0 ;		//환전금액
                    $arrEmpInfo['emp_money_give'] = $arrResult[7]->result_1 != null ? $arrResult[7]->result_1 : 0 ;     //지급
                    $arrEmpInfo['emp_money_withdraw'] = $arrResult[7]->result_2 != null ? $arrResult[7]->result_2 : 0 ;		//회수
                    $arrEmpInfo['evol_captcha'] = $confsiteModel->getCaptchaAlarm();		//회수
                }

                $arrSoundInfo = $confsiteModel->getSoundConf();

                $objResult->data = $arrEmpInfo;
                $objResult->sound = $arrSoundInfo;
                $objResult->status = STATUS_SUCCESS;
            } else {
                $objResult->status = STATUS_FAIL;
            }

            echo json_encode($objResult);
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
            echo json_encode($arrResult);
        }
    }

    // 게임별 당일 총배팅금, 총당첨금, 누르기금
    public function empbetinfo()
    {
        if (is_login()) {
            $strUid = $this->session->user_id;
            // model
            
            $confsiteModel = new ConfSite_Model();
            $confgameModel = new ConfGame_Model();

            $objUser = $this->modelMember->getInfo($strUid);
            $siteConfs = $this->getSiteConf($confsiteModel);

            $objResult = new \stdClass();
            if ($objUser->mb_level >= LEVEL_ADMIN) {
                $strDate = date('Y-m-d');
                $arrReqData['start'] = $strDate.' 00:00:00';
                $eggs = $this->modelMember->calcGameEgg();

                $arrSumData = [[0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0] ];
                
                if(!$siteConfs['pbg_deny'] || !$siteConfs['bpg_deny'] || !$siteConfs['eos5_deny'] || !$siteConfs['eos3_deny']
                    || !$siteConfs['coin5_deny'] || !$siteConfs['coin3_deny']){
                    $betModel = new PbBet_Model();

                    $arrSum = $betModel->getBetSumByDay($arrReqData);
                    $arrSumData[0][0] += $arrSum[0][0] + $arrSum[1][0];
                    $arrSumData[0][1] += $arrSum[0][1] + $arrSum[1][1];
                }
                
                if(isEBalMode() || !$siteConfs['evol_deny']){

                    if(isEBalMode()){
                        $arrReqData['type'] = GAME_AUTO_EVOL;
                        $betModel = new EbalBetSt_Model();
                        $betModel->setType($arrReqData['type']);
                        $arrSum = $betModel->getBetSumByDay($arrReqData);
                        $arrSumData[1][0] = $arrSum[0];
                        $arrSumData[1][1] = $arrSum[1];
                    } else {
                        $arrReqData['type'] = GAME_CASINO_EVOL;
                        $betModel = new CsBet_Model();
                        $arrReqData['gm_range'] = $this->modelMember->getBetMinId($arrReqData, $betModel->table);
                        $objConfPb = $confgameModel->getByIndex($arrReqData['type']);
                        $arrSum = $betModel->getBetSumByDay($arrReqData, $objConfPb);
                        $arrSumData[1][0] = $arrSum[0];
                        $arrSumData[1][1] = $arrSum[1];
                        $confId = CONF_API_HPPLAY;
                        $agConf = $confsiteModel->getConf($confId);
                        if($agConf != null)
                            $arrSumData[1][2] = $agConf->conf_active;
                        $arrSumData[1][3] = $eggs->mb_live_money;
                    }
                }
                if(!$siteConfs['slot_deny']){
                    $betModel = new SlBet_Model();

                    if($_ENV['app.type'] == APP_TYPE_1 || $_ENV['app.type'] = APP_TYPE_3){

                        $gameId = GAME_SLOT_THEPLUS;
                        $confId = CONF_API_THEPLUS;
                        $arrSumData[2][3] = $eggs->mb_slot_money;
                        if($_ENV['app.slot'] == APP_SLOT_KGON){
                            $gameId = GAME_SLOT_KGON;
                            $confId = CONF_API_KGON;
                            $arrSumData[2][3] = $eggs->mb_kgon_money;
                        } else if($_ENV['app.slot'] == APP_SLOT_STAR){
                            $gameId = GAME_SLOT_STAR;
                            $confId = CONF_API_STAR;
                            $arrSumData[2][3] = $eggs->mb_hslot_money;
                        } else if($_ENV['app.slot'] == APP_SLOT_RAVE){
                            $gameId = GAME_SLOT_RAVE;
                            $confId = CONF_API_RAVE;
                            $arrSumData[2][3] = $eggs->mb_rave_money;
                        } else if($_ENV['app.slot'] == APP_SLOT_TREEM){
                            $gameId = GAME_SLOT_TREEM;
                            $confId = CONF_API_TREEM;
                            $arrSumData[2][3] = $eggs->mb_treem_money;
                        } else if($_ENV['app.slot'] == APP_SLOT_SIGMA){
                            $gameId = GAME_SLOT_SIGMA;
                            $confId = CONF_API_SIGMA;
                            $arrSumData[2][3] = $eggs->mb_sigma_money;
                        }

                        $objConfPb = $confgameModel->getByIndex($gameId);
                        $arrSum = $betModel->getBetSumByDay($arrReqData, $objConfPb);
                        $arrSumData[2][0] = $arrSum[0];
                        $arrSumData[2][1] = $arrSum[1];

                        $agConf = $confsiteModel->getConf($confId);
                        if($agConf != null)
                            $arrSumData[2][2] = $agConf->conf_active;
                    }

                    if($_ENV['app.type'] == APP_TYPE_1 || $_ENV['app.type'] == APP_TYPE_2){
                        $gameId = GAME_SLOT_GSPLAY;
                        $confId = CONF_API_GSPLAY;
                        $arrSumData[3][3] = $eggs->mb_fslot_money;
                        if($_ENV['app.fslot'] == APP_FSLOT_GOLD){
                            $gameId = GAME_SLOT_GOLD;
                            $confId = CONF_API_GOLD;
                            $arrSumData[3][3] = $eggs->mb_gslot_money;
                        }
                        $objConfPb = $confgameModel->getByIndex($gameId);
                        $arrSum = $betModel->getBetSumByDay($arrReqData, $objConfPb);
                        $arrSumData[3][0] = $arrSum[0];
                        $arrSumData[3][1] = $arrSum[1];
                        
                        $agConf = $confsiteModel->getConf($confId);
                        if($agConf != null)
                            $arrSumData[3][2] = $agConf->conf_active;
                    }
                }
                if(!$siteConfs['cas_deny']){
                    $betModel = new CsBet_Model();

                    $objConfPb = $confgameModel->getByIndex(GAME_CASINO_KGON);
                    $arrReqData['gm_range'] = $this->modelMember->getBetMinId($arrReqData, $betModel->table);
                    $arrSum = $betModel->getBetSumByDay($arrReqData, $objConfPb);
                    $arrSumData[4][0] = $arrSum[0];
                    $arrSumData[4][1] = $arrSum[1];

                    $confId = CONF_API_KGON;
                    $arrSumData[4][3] = $eggs->mb_kgon_money;
                    if($_ENV['app.casino'] == APP_CASINO_STAR){
                        $confId = CONF_API_STAR;
                        $arrSumData[4][3] = $eggs->mb_hslot_money;
                    }
                    else if($_ENV['app.casino'] == APP_CASINO_RAVE){
                        $confId = CONF_API_RAVE;
                        $arrSumData[4][3] = $eggs->mb_rave_money;
                    }
                    else if($_ENV['app.casino'] == APP_CASINO_TREEM){
                        $confId = CONF_API_TREEM;
                        $arrSumData[4][3] = $eggs->mb_treem_money;
                    }
                    else if($_ENV['app.casino'] == APP_CASINO_SIGMA){
                        $confId = CONF_API_SIGMA;
                        $arrSumData[4][3] = $eggs->mb_sigma_money;
                    }
                    
                    $agConf = $confsiteModel->getConf($confId);
                    if($agConf != null)
                        $arrSumData[4][2] = $agConf->conf_active;

                }
                if(!$siteConfs['hold_deny']){
                    $betModel = new HlBet_Model();

                    $objConfPb = $confgameModel->getByIndex(GAME_HOLD_CMS);
                    $arrSum = $betModel->getBetSumByDay($arrReqData, $objConfPb);
                    $arrSumData[5][0] = $arrSum[0];
                    $arrSumData[5][1] = $arrSum[1];
				    $agConf = $confsiteModel->getConf(CONF_API_HOLD);
                    if($agConf != null)
                        $arrSumData[5][2] = $agConf->conf_active;
                    $arrSumData[5][3] = $eggs->mb_hold_money;

                }
                if(isPBalMode()){

                    $arrReqData['type'] = GAME_AUTO_PRAG;
                    if(isEBalMode()){
                        $betModel = new EbalBetSt_Model();
                        $betModel->setType($arrReqData['type']);
                        $arrSum = $betModel->getBetSumByDay($arrReqData);
                        $arrSumData[6][0] = $arrSum[0];
                        $arrSumData[6][1] = $arrSum[1];
                    } 
                }
                $objResult->data = $arrSumData;
                $objResult->status = STATUS_SUCCESS;
            } else {
                $objResult->status = STATUS_FAIL;
            }

            echo json_encode($objResult);
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
            echo json_encode($arrResult);
        }
    }

    // 유저의 충환전, 배팅총액
    public function userinfo()
    {
        $jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
        if (is_login()) {
            $strUid = $this->session->user_id;
            // model
            
            $chargeModel = new Charge_Model();
            $exchangeModel = new Exchange_Model();
            $confsiteModel = new ConfSite_Model();
            $siteConfs = $this->getSiteConf($confsiteModel);

            $objUser = $this->modelMember->getInfo($strUid);

            $objResult = new \stdClass();
            if ($objUser->mb_level >= LEVEL_ADMIN) {
                
                $arrInfo['charge_total'] = $chargeModel->calcAdminCharge($arrReqData);
                $arrInfo['discharge_total'] = $exchangeModel->calcAdminExchange($arrReqData);
                $arrBet = $this->modelMember->calcUserBet($arrReqData, $siteConfs);
                $arrInfo['bet_total'] = $arrBet['bet_money'];
                $arrInfo['win_total'] = $arrBet['bet_win_money'];

                $arrReqData['start'] = date('Y-m-d');
                $arrReqData['end'] = $arrReqData['start'];
                $arrInfo['charge_today'] = $chargeModel->calcAdminCharge($arrReqData);
                $arrInfo['discharge_today'] = $exchangeModel->calcAdminExchange($arrReqData);
                $arrBet = $this->modelMember->calcUserBet($arrReqData, $siteConfs);
                $arrInfo['bet_today'] = $arrBet['bet_money'];
                $arrInfo['win_today'] = $arrBet['bet_win_money'];

                $objResult->data = $arrInfo;
                $objResult->status = STATUS_SUCCESS;
            } else {
                $objResult->status = STATUS_FAIL;
            }

            echo json_encode($objResult);
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
            echo json_encode($arrResult);
        }
    }

    
    // 유저의 충환전, 배팅총액
    public function userbet()
    {
        $jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
        if (is_login()) {
            $strUid = $this->session->user_id;
            // model
            $confsiteModel = new ConfSite_Model();
            $siteConfs = $this->getSiteConf($confsiteModel);

            $objUser = $this->modelMember->getInfo($strUid);

            $objResult = new \stdClass();
            if ($objUser->mb_level >= LEVEL_ADMIN) {
                
                $arrReqData['start'] = date('Y-m-d');
                $arrReqData['end'] = $arrReqData['start'];
                $objResult->date = $arrReqData['start'];
                $objResult->data = $this->modelMember->statistUserBet($arrReqData, $siteConfs);
                $objResult->status = STATUS_SUCCESS;
            } else {
                $objResult->status = STATUS_FAIL;
            }

            echo json_encode($objResult);
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
            echo json_encode($arrResult);
        }
    }

    public function getmembers()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            // model
			$confsiteModel = new ConfSite_Model();

			if(array_key_exists('auto', $arrData) && !$arrData['auto']){
				$this->sess_action();                
			}
			$objResult = new \stdClass();
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);
			$empFid = 0;
            if (strlen($arrData['mb_emp_uid']) > 0){
                $objEmp = $this->modelMember->getInfo($arrData['mb_emp_uid']);
                if (!is_null($objEmp)){
                    $empFid = $objEmp->mb_fid;
                } else $empFid = -1;
            } 
            
            if($empFid >= 0){
                $arrMember = $this->modelMember->searchMemberByEmpFid($objUser, $arrData, $empFid);
                if (is_null($arrMember)) {
                    $arrMember = [];
                }
                foreach ($arrMember as $objMember) {
                    $objMember->mb_money_all = allMoney($objMember);
                    $objEmpInfo = $this->modelMember->find($objMember->mb_emp_fid);
                    if ($objEmpInfo != null){
                        $objMember->mb_empname = $objEmpInfo->mb_uid;
                    }
                    else {
                        $objMember->mb_empname = '';
                    }
                }
                
            } else {
                $arrMember = [];
            }

            $confs= $this->getSiteConf($confsiteModel);
            $confs['emp_level'] = $objUser->mb_level; 
            
            $objResult->status = STATUS_SUCCESS;
            $objResult->confs = $confs;
            $objResult->data = $arrMember;

            echo json_encode($objResult);
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
            echo json_encode($arrResult);
        }
    }

    public function getmembercnt()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            // model
            
			$objResult = new \stdClass();
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);
			$empFid = 0;
            if (strlen($arrData['mb_emp_uid']) > 0){
                $objEmp = $this->modelMember->getInfo($arrData['mb_emp_uid']);
                if (!is_null($objEmp)){
                    $empFid = $objEmp->mb_fid;
                } else $empFid = -1;
            } 
            if($empFid >= 0){
                $objCount = $this->modelMember->searchCountByEmpFid($objUser, $arrData, $empFid);
                $objResult->status = STATUS_SUCCESS;
                $objResult->data = $objCount;
            } else {
                $objCount = new \stdClass();
                $objCount->count = 0;
                $objResult->status = STATUS_SUCCESS;
                $objResult->data = $objCount;
            }
			
            echo json_encode($objResult);
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
            echo json_encode($arrResult);
        }
    }

    
    public function getmemberlist()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            // model
            
			if(array_key_exists('auto', $arrData) && !$arrData['auto']){
				$this->sess_action();                
			}

			$confsiteModel = new ConfSite_Model();
            $confs = $this->getSiteConf($confsiteModel);

			$objResult = new \stdClass();
            $strUid = $this->session->user_id;
            $objAdmin = $this->modelMember->getInfo($strUid);
			$empFid = 0;
            if (strlen($arrData['mb_emp_uid']) > 0){
                $objEmp = $this->modelMember->getInfo($arrData['mb_emp_uid']);
                if (!is_null($objEmp)){
                    $empFid = $objEmp->mb_fid;
                } else $empFid = -1;
            } 
            
            if($empFid >= 0 && $objAdmin->mb_level >= LEVEL_ADMIN){
                $arrMember = $this->modelMember->searchUserByLevel($arrData, $empFid, $confs);
                if (is_null($arrMember)) {
                    $arrMember = [];
                }
                foreach ($arrMember as $objMember) {
                    $objEmpInfo = $this->modelMember->find($objMember->mb_emp_fid);
                    if ($objEmpInfo != null){
                        $objMember->mb_empname = $objEmpInfo->mb_uid;
                    }
                    else {
                        $objMember->mb_empname = '';
                    }
                }
                
            } else {
                $arrMember = [];
            }
            
            $confs['emp_level'] = $objAdmin->mb_level; 
            $bDelete = true;
            if(array_key_exists('app.delete_level', $_ENV) && $objAdmin->mb_level < $_ENV['app.delete_level'])
                $bDelete = false;
            $confs['delete'] = $bDelete;
            $objResult->status = STATUS_SUCCESS;
            $objResult->confs = $confs;
            $objResult->data = $arrMember;

            echo json_encode($objResult);
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
            echo json_encode($arrResult);
        }
    }

    
    public function getmembertree()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            // model
			$confsiteModel = new ConfSite_Model();

			if(array_key_exists('auto', $arrData) && !$arrData['auto']){
				$this->sess_action();                
			}
			$objResult = new \stdClass();
            $strUid = $this->session->user_id;
            $objAdmin = $this->modelMember->getInfoByUid($strUid);
			$mbFid = 0;
            $arrData['search'] = trim($arrData['search']);
            $objUser = null;
            if (strlen($arrData['search']) > 0){
                if($arrData['mode'] == 0){
                    if($arrData['type'] == 1){
                        $objUser = $this->modelMember->getByNickname($arrData['search']);
                    } else if($arrData['type'] == 2){
                        $objUser = $this->modelMember->getInfoByFid($arrData['search']);
                    } else if($arrData['type'] == 3){
                        $objUser = $this->modelMember->getByBankOwner($arrData['search']);
                    } else {
                        $objUser = $this->modelMember->getInfo($arrData['search']);
                    }
                    
                    if(is_null($objUser)){
                        $mbFid = -1;
                    } else {
                        $arrMem = $this->modelMember->getMemberByEmpFid($objAdmin->mb_fid, $objAdmin->mb_level,  $objAdmin->mb_level, true, $objUser->mb_fid);
                        if(count($arrMem) > 0 || ($objAdmin->mb_level < LEVEL_ADMIN && $objAdmin->mb_fid == $objUser->mb_fid ) )
                            $mbFid = $objUser->mb_fid;
                        else $mbFid = -1;
                    }
                } else $mbFid = -2;
            } 
            
            $bTree = false;
            $arrUsers = [];
            if($mbFid >= 0){
                $bTree = true;

                $arrMember = $this->modelMember->searchMemberTree($objAdmin, $arrData, $mbFid);
                if(is_null($arrMember))
                    $arrMember = [];

                if($objAdmin->mb_level < LEVEL_ADMIN){
                    $objAdmin->mb_self = 1;
                    array_unshift($arrMember, $objAdmin);
                }

                foreach ($arrMember as $objMember) {
                    if($objAdmin->mb_level < LEVEL_ADMIN && !is_null($objUser) && $objUser->mb_fid != $objMember->mb_fid)
                        continue;

                    $objMember->mb_money_all = allMoney($objMember);
                    $objMember->mb_point = floor($objMember->mb_point);
                    $objEmpInfo = $this->modelMember->find($objMember->mb_emp_fid);
                    if ($objEmpInfo != null){
                        $objMember->mb_empname = $objEmpInfo->mb_uid;
                    }
                    else {
                        $objMember->mb_empname = '';
                    }
                    array_push($arrUsers, $objMember);
                }
                
            } else if($mbFid == -2){
                $empFid = 0;
                $arrData['mb_uid'] = "";
                $arrData['mb_grade'] = "";
                $arrData['mb_state'] = -1;
                $arrData['page'] = 1;
                $arrData['count'] = 1000;
                if($arrData['type'] == 1){
                    $arrData['mb_nickname'] = $arrData['search'];
                } else if($arrData['type'] == 2){
                    $arrData['mb_fid'] = $arrData['search'];
                } else if($arrData['type'] == 3){
                    $arrData['mb_bank_own'] = $arrData['search'];
                } else {
                    $arrData['mb_uid'] = $arrData['search'];
                }

                $arrMember = $this->modelMember->searchMemberByEmpFid($objAdmin, $arrData, $empFid);
                if (is_null($arrMember)) {
                    $arrMember = [];
                }
                foreach ($arrMember as $objMember) {
                    $objMember->mb_money_all = allMoney($objMember);
                    $objMember->mb_point = floor($objMember->mb_point);
                    $objEmpInfo = $this->modelMember->find($objMember->mb_emp_fid);
                    if ($objEmpInfo != null){
                        $objMember->mb_empname = $objEmpInfo->mb_uid;
                    }
                    else {
                        $objMember->mb_empname = '';
                    }
                    array_push($arrUsers, $objMember);
                }
            }

            $confs= $this->getSiteConf($confsiteModel);
            if($confs['evol_deny'])
                $confs['evol_deny'] = !isEBalMode();
            $confs['emp_level'] = $objAdmin->mb_level; 
            $confs['tree'] = $bTree;
            $bDelete = true;
            if(array_key_exists('app.delete_level', $_ENV) && $objAdmin->mb_level < $_ENV['app.delete_level'])
                $bDelete = false;
            $confs['delete'] = $bDelete;

            $objResult->status = STATUS_SUCCESS;
            $objResult->confs = $confs;
            $objResult->data = $arrUsers;

            echo json_encode($objResult);
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
            echo json_encode($arrResult);
        }
    }
    
    public function getmemberclass()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            // model
			$confsiteModel = new ConfSite_Model();
            $confs= $this->getSiteConf($confsiteModel);

			$objResult = new \stdClass();
            $strUid = $this->session->user_id;
            $objAdmin = $this->modelMember->getInfo($strUid);
			$mbFid = 0;
            $arrData['search'] = trim($arrData['search']);
            if (strlen($arrData['search']) > 0){
                if($arrData['mode'] == 0){
                    if($arrData['type'] == 1){
                        $objUser = $this->modelMember->getByNickname($arrData['search']);
                    } else if($arrData['type'] == 2){
                        $objUser = $this->modelMember->getInfoByFid($arrData['search']);
                    } else if($arrData['type'] == 3){
                        $objUser = $this->modelMember->getByBankOwner($arrData['search']);
                    } else 
                        $objUser = $this->modelMember->getInfo($arrData['search']);
                
                    if(is_null($objUser)){
                        $mbFid = -1;
                    } else {
                        $arrMem = $this->modelMember->getMemberByEmpFid($objAdmin->mb_fid, $objAdmin->mb_level,  $objAdmin->mb_level, true, $objUser->mb_fid);
                        if(count($arrMem) < 1)
                            $mbFid = -1;
                        else $mbFid = $objUser->mb_fid;
                    }
                } else $mbFid = -2;
                    
            } 
            
            $bTree = true;
            if($mbFid >= 0){
                $arrMember = $this->modelMember->searchMemberClass($objAdmin, $arrData, $mbFid, $confs);
                if (is_null($arrMember)) {
                    $arrMember = [];
                }
                foreach ($arrMember as $objMember) {
                    $objMember->mb_money = floor($objMember->mb_money);
                    $objMember->mb_point = floor($objMember->mb_point);
                    $objEmpInfo = $this->modelMember->find($objMember->mb_emp_fid);
                    if ($objEmpInfo != null){
                        $objMember->mb_empname = $objEmpInfo->mb_uid;
                    }
                    else {
                        $objMember->mb_empname = '';
                    }
                }
                
            } else if($mbFid == -2){
                $bTree = false;

                $empFid = 0;
                $arrData['mb_uid'] = "";
                $arrData['mb_grade'] = -1;
                $arrData['mb_state'] = -1;
                $arrData['order'] = "";
                $arrData['dir'] = "";
                $arrData['page'] = 1;
                $arrData['count'] = 1000;
                if($arrData['type'] == 1){
                    $arrData['mb_nickname'] = $arrData['search'];
                } else if($arrData['type'] == 2){
                    $arrData['mb_fid'] = $arrData['search'];
                } else if($arrData['type'] == 3){
                    $arrData['mb_bank_own'] = $arrData['search'];
                } else {
                    $arrData['mb_uid'] = $arrData['search'];
                }

                $arrMember = $this->modelMember->searchUserByLevel($arrData, $empFid, $confs);
                if (is_null($arrMember)) {
                    $arrMember = [];
                }
                foreach ($arrMember as $objMember) {
                    $objMember->mb_money = floor($objMember->mb_money);
                    $objMember->mb_point = floor($objMember->mb_point);
                    $objEmpInfo = $this->modelMember->find($objMember->mb_emp_fid);
                    if ($objEmpInfo != null){
                        $objMember->mb_empname = $objEmpInfo->mb_uid;
                    }
                    else {
                        $objMember->mb_empname = '';
                    }
                }
            } else {
                $arrMember = [];
            }

            $confs['emp_level'] = $objAdmin->mb_level; 
            $confs['tree'] = $bTree;
            $bDelete = true;
            if(array_key_exists('app.delete_level', $_ENV) && $objAdmin->mb_level < $_ENV['app.delete_level'])
                $bDelete = false;
            $confs['delete'] = $bDelete;
            
            $objResult->status = STATUS_SUCCESS;
            $objResult->confs = $confs;
            $objResult->data = $arrMember;

            echo json_encode($objResult);
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
            echo json_encode($arrResult);
        }
    }

    public function memberIds()
    {
        if (is_login()) {
            // model
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            $objResult = new \stdClass();

            if ($objUser->mb_level >= LEVEL_ADMIN) {
                $arrMember = $this->modelMember->getMemberByLevel(LEVEL_ADMIN, true);

                if (is_null($arrMember)) {
                    $arrMember = [];
                }

                $ids = [];
                foreach ($arrMember as $objMember) {
                    array_push($ids, $objMember->mb_uid);
                }

                $objResult->status = STATUS_SUCCESS;
                $objResult->data = $ids;
            } else {
                $objResult->status = STATUS_FAIL;
            }

            echo json_encode($objResult);
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
            echo json_encode($arrResult);
        }
    }

    
	public function loglist(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login())
		{

            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            if($objUser->mb_level  >= LEVEL_ADMIN) {
                $modelSesslog = new SessLog_Model();

                $arrData = $modelSesslog->search($arrReqData, $objUser->mb_level);
                    
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

	public function logcnt(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login())
		{
            
         
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            if($objUser->mb_level  >= LEVEL_ADMIN) {
                $modelSesslog = new SessLog_Model();
                $objCount = $modelSesslog->searchCount($arrReqData, $objUser->mb_level);

                $arrResult['data'] = $objCount;
                $arrResult['status'] = "success";
            } else {
                $arrResult['status'] = "fail";
            }
		}
		else{
			$arrResult['status'] = "logout";
		}
		echo json_encode($arrResult);
	}

	public function trylist(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login())
		{

            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            if($objUser->mb_level  >= LEVEL_ADMIN) {
                $modelSesstry = new SessTry_Model();

                $arrData = $modelSesstry->search($arrReqData, $objUser->mb_level);
                    
                $arrResult['level'] = $objUser->mb_level;
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

	public function trycnt(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login())
		{
            
         
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            if($objUser->mb_level  >= LEVEL_ADMIN) {
                $modelSesstry = new SessTry_Model();
                $objCount = $modelSesstry->searchCount($arrReqData, $objUser->mb_level);

                $arrResult['data'] = $objCount;
                $arrResult['status'] = "success";
            } else {
                $arrResult['status'] = "fail";
            }
		}
		else{
			$arrResult['status'] = "logout";
		}
		echo json_encode($arrResult);
	}

    public function conlist(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login())
		{

            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            if($objUser->mb_level  >= LEVEL_ADMIN) {

                if(array_key_exists('auto', $arrReqData) && !$arrReqData['auto']){
                    $this->sess_action();                
                }
                $arrData = $this->modelSess->search($arrReqData, $objUser->mb_level);
    
                $follow_en = false;
                if(isEBalMode()){
                    $confsiteModel = new ConfSite_Model();
                    $confFollow = $confsiteModel->getConf(CONF_EVOLFOLLOW);
                    if($confFollow != null)
                        $follow_en = intval($confFollow->conf_active) == STATE_ACTIVE ;
                }
                if($follow_en){
                    foreach($arrData as $objSess){
                        $cntFollow = 0;
                        $followers = $this->modelMember->searchFollowers();
                        foreach($followers as $follower){
                            
                            if(strpos($follower->mb_follow_ev, "1:".$objSess->sess_mb_uid) === 0)
                                $cntFollow ++;
                        }
                        $objSess->sess_follows = $cntFollow;
                    }
                }

                $arrResult['data'] = $arrData;
                $arrResult['follow'] = $follow_en;
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

	public function concnt(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login())
		{
         
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            if($objUser->mb_level  >= LEVEL_ADMIN) {
                $objCount = $this->modelSess->searchCount($arrReqData, $objUser->mb_level);

                $arrResult['data'] = $objCount;
                $arrResult['status'] = "success";
            } else {
                $arrResult['status'] = "fail";
            }
		}
		else{
			$arrResult['status'] = "logout";
		}
		echo json_encode($arrResult);
	}

	public function blocklist(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login())
		{
            
            
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            if($objUser->mb_level  >= LEVEL_ADMIN) {

                $modelBlock = new Block_Model();
                $arrData = $modelBlock->search($arrReqData);
                    
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

	public function blockcnt(){
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login())
		{
            

            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            if($objUser->mb_level  >= LEVEL_ADMIN) {

                $modelBlock = new Block_Model();
                $objCount = $modelBlock->searchCount($arrReqData);

                $arrResult['data'] = $objCount;
                $arrResult['status'] = "success";
            } else {
                $arrResult['status'] = "fail";
            }
		}
		else{
			$arrResult['status'] = "logout";
		}
		echo json_encode($arrResult);
	}

    
    // 블록아이피 추가
    public function add_block()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            $bPermit = false;
            
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            if($objUser->mb_level  >= LEVEL_ADMIN) {
                $modelBlock = new Block_Model();
                // $arrData['block_state'] = 1;
                $arrData['block_updated'] = date("Y-m-d H:i:s");

                $bResult = $modelBlock->saveByIp($arrData);

                if ($bResult) {
                    $arrResult['status'] = STATUS_SUCCESS;
                } else {
                    $arrResult['status'] = STATUS_FAIL;
                }
            } else {
                $arrResult['status'] = STATUS_NOPERMIT;
            }
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
        }
        echo json_encode($arrResult);
    }

    // 블록아이피 변경
    public function update_block()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            $bPermit = false;
            
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            if($objUser->mb_level  >= LEVEL_ADMIN) {
                $modelBlock = new Block_Model();

                $bResult = $modelBlock->updateByFid($arrData);

                if ($bResult) {
                    $arrResult['status'] = STATUS_SUCCESS;
                } else {
                    $arrResult['status'] = STATUS_FAIL;
                }
            } else {
                $arrResult['status'] = STATUS_NOPERMIT;
            }
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
        }
        echo json_encode($arrResult);
    }

    // 블록아이피 삭제
    public function delete_block()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            $bPermit = false;
            
            $strUid = $this->session->user_id;
            $objUser = $this->modelMember->getInfo($strUid);

            if($objUser->mb_level  >= LEVEL_ADMIN) {
                $modelBlock = new Block_Model();

                $bResult = $modelBlock->deleteByFid($arrData['block_fid']);

                if ($bResult) {
                    $arrResult['status'] = STATUS_SUCCESS;
                } else {
                    $arrResult['status'] = STATUS_FAIL;
                }
            } else {
                $arrResult['status'] = STATUS_NOPERMIT;
            }
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
        }
        echo json_encode($arrResult);
    }

    public function transfer()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            // model
            
            // $moneyhistoryModel = new MoneyHistory_Model();
            $confsiteModel = new ConfSite_Model();
            $confsiteModel->readMemConf();

            $strUid = $this->session->user_id;
            $objEmp = $this->modelMember->getInfo($strUid);
            $objMember = $this->modelMember->getInfoByFid($arrData['mb_fid'], true);

            $objResult = new \stdClass();

            if(is_null($objMember) || is_null($objEmp)){
                $objResult->status = STATUS_FAIL;
            } else if($arrData['amount'] <= 0 ){
                $objResult->status = STATUS_FAIL;
                $objResult->msg = '금액을 정확히 입력해주세요.';
            } else if($objEmp->mb_level >= LEVEL_ADMIN) {
                $objResult->status = STATUS_FAIL;
                
                if($arrData['type'] == 0 || $arrData['type'] == 4){          //Deposit or Payment
                    $iResult = 1;
                    if($_ENV['mem.delay_play'] > 0 && $_ENV['mem.depodeny_play'] &&  diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                        $iResult = 2;
                    } 

                    if($iResult == 1){
                        if($arrData['type'] == 0)
                            $iChangeType = MONEYCHANGE_INC;
                        else 
                            $iChangeType = MONEYCHANGE_GIVE;

                        if($this->modelMember->updateAssets($objMember, $arrData['amount'], 0, $iChangeType, $objEmp->mb_uid))
                        {
                            if($arrData['type'] == 0){
                                $chargeModel = new Charge_Model();

                                $data =[
                                    'charge_emp_fid' => $objMember->mb_emp_fid,
                                    'charge_mb_uid' => $objMember->mb_uid,
                                    'charge_mb_realname' => $objMember->mb_bank_own,
                                    'charge_mb_phone' => $objMember->mb_phone,
                                    'charge_money' => $arrData['amount'],
                                    'charge_time_require' => date("Y-m-d H:i:s"),
                                    'charge_action_state' => 5,
                                    'charge_action_uid' => $objEmp->mb_uid,
                                    'charge_time_process' => date("Y-m-d H:i:s"),
                                    'charge_money_before' => allMoney($objMember),
                                    'charge_money_after' => allMoney($objMember) + $arrData['amount'],
                                ];
                                $chargeModel->register($data);
                            } 
                            
                            // $moneyhistoryModel->registerTransfer($objMember, $objEmp->mb_uid, $arrData['amount'], $iChangeType);
                            $objResult->status = STATUS_SUCCESS;
                        }
                    } else if($iResult == 2) {
                        $objResult->status = STATUS_FAIL;
                        $objResult->msg = '회원이 게임플레이중이므로 충전 하실수 없습니다. '.intval(($_ENV['mem.delay_play']-diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet))/60+1)."분후 다시 시도해주세요.";
                    }
                } else if($arrData['type'] == 1 || $arrData['type'] == 5){           //Withdraw OR 

                    $iResult = 1;
                    if(floatval($objMember->mb_money) < $arrData['amount']){
                        if($_ENV['mem.delay_play'] > 0 && $_ENV['mem.withdeny_play'] && diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                            $iResult = 2;
                        } 
                        else 
                            $iResult = $this->alltoGame($objMember); 
                    } 
                    if($_ENV['mem.withdeny_play'] && $iResult == 1){
                        $sess = $this->modelSess->getByUid($objMember->mb_uid, false);
                        if(!is_null($sess))
                            $iResult = 4;
                    }

                    if($iResult == 1){
                        
                        if(floatval($objMember->mb_money) < $arrData['amount']){
                            $arrData['amount'] = $objMember->mb_money;
                        }
                        if($arrData['type'] == 1)
                            $iChangeType = MONEYCHANGE_DEC;
                        else $iChangeType = MONEYCHANGE_WITHDRAW;

                        if($arrData['amount'] > 0 && $this->modelMember->updateAssets($objMember, 0-$arrData['amount'], 0, $iChangeType, $objEmp->mb_uid))
                        {
                            if($arrData['type'] == 1){
                                $exchangeModel = new Exchange_Model();

                                $data =[
                                    'exchange_emp_fid' => $objMember->mb_emp_fid,
                                    'exchange_mb_uid' => $objMember->mb_uid,
                                    'exchange_mb_phone' => $objMember->mb_phone,
                                    'exchange_money' => $arrData['amount'],
                                    'exchange_time_require' => date("Y-m-d H:i:s"),
                                    'exchange_action_state' => 5,
                                    'exchange_action_uid' => $objEmp->mb_uid,
                                    'exchange_time_process' => date("Y-m-d H:i:s"),
                                    'exchange_bank_name' => $objMember->mb_bank_name,
                                    'exchange_bank_account' => $objMember->mb_bank_own,
                                    'exchange_bank_serial' => $objMember->mb_bank_num,
                                    'exchange_money_before' => allMoney($objMember),
                                    'exchange_money_after' => allMoney($objMember)-$arrData['amount']
    
                                ];
                                $exchangeModel->register($data);
                            } 
                            
                            // $moneyhistoryModel->registerTransfer($objMember, $objEmp->mb_uid, 0-$arrData['amount'], $iChangeType);
                            $objResult->status = STATUS_SUCCESS;
                        }
                    } else if($iResult == 2) {
                        $objResult->status = STATUS_FAIL;
                        $objResult->msg = '회원이 게임플레이중이므로 환전 하실수 없습니다. '.intval(($_ENV['mem.delay_play']-diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet))/60+1)."분후 다시 시도해주세요.";
                    } else if($iResult == 4) {
                        $objResult->status = STATUS_FAIL;
                        $objResult->msg = '회원이 앱실행중이므로 환전 하실수 없습니다. ';
                    } else {
                        $objResult->status = STATUS_FAIL;
                        $objResult->msg = '게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요..';

                    }
                    
                } else if($arrData['type'] == 6){          //Change Money to Point
                    $iResult = 1;
                    if(floatval($objMember->mb_money) < $arrData['amount']){
                        if($_ENV['mem.delay_play'] > 0 && $_ENV['mem.withdeny_play'] && diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                            $iResult = 2;
                        } 
                        else 
                            $iResult = $this->alltoGame($objMember); 
                    } 
                    if($_ENV['mem.withdeny_play'] && $iResult == 1){
                        $sess = $this->modelSess->getByUid($objMember->mb_uid, false);
                        if(!is_null($sess))
                            $iResult = 4;
                    }

                    if($iResult == 1){
                        
                        if(floatval($objMember->mb_money) < $arrData['amount']){
                            $arrData['amount'] = $objMember->mb_money;
                        }
                        if($arrData['amount'] > 0 && $this->modelMember->updateAssets($objMember, 0-$arrData['amount'], $arrData['amount'], MONEYCHANGE_CONVERT, $objEmp->mb_uid))
                        {
                            // $moneyhistoryModel->registerPointToMoney($objMember, 0-$arrData['amount'], $objEmp->mb_uid, MONEYCHANGE_CONVERT);
                            $objResult->status = STATUS_SUCCESS;
                        }
                    } else if($iResult == 2) {
                        $objResult->status = STATUS_FAIL;
                        $objResult->msg = '회원이 게임플레이중이므로 전환 하실수 없습니다. '.intval(($_ENV['mem.delay_play']-diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet))/60+1)."분후 다시 시도해주세요.";
                    } else if($iResult == 4) {
                        $objResult->status = STATUS_FAIL;
                        $objResult->msg = '회원이 앱실행중이므로 전환 하실수 없습니다. ';
                    } else {
                        $objResult->status = STATUS_FAIL;
                        $objResult->msg = '게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요..';
                    }

                }  else if($arrData['type'] == 7){               //환수

                    $iResult = 3;
                    $objEmp = null;
                    if(array_key_exists('mb_emp', $arrData)) {
                        
                        $arrEmp = $this->modelMember->getEmpMemberByFid($objMember->mb_fid);
						foreach($arrEmp as $objUser){
							if($objUser->mb_uid === $arrData['mb_emp']) {
                                $objEmp = $objUser;
                                $iResult = 1;
                                break;
                            }
						}
                    }

                    if($iResult == 1 && $arrData['amount'] > $objMember->mb_money){
                        if($_ENV['mem.delay_play'] > 0 && $_ENV['mem.withdeny_play'] && diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                            $iResult = 2;
                        } 
                        else $iResult = $this->alltoGame($objMember);
                    } 
                    if($_ENV['mem.withdeny_play'] && $iResult == 1){
                        $sess = $this->modelSess->getByUid($objMember->mb_uid, false);
                        if(!is_null($sess))
                            $iResult = 4;
                    }

                    if($iResult == 1){
                        if($arrData['amount'] > $objMember->mb_money){
                            $arrData['amount'] = $objMember->mb_money;
                        }
                        if($arrData['amount'] > 0 && $this->modelMember->updateAssets($objMember, 0-$arrData['amount'], 0, MONEYCHANGE_EXCHANGE_DEC, $objEmp->mb_uid)){

                            // $this->modelMember->trasferMoney($objMember, $objEmp, $arrData['amount'], MONEYCHANGE_EXCHANGE_DEC, MONEYCHANGE_EXCHANGE_INC
                            // $moneyhistoryModel->registerTransfer($objEmp, $objMember->mb_uid, $arrData['amount'], MONEYCHANGE_EXCHANGE_INC);
                            // $moneyhistoryModel->registerTransfer($objMember, $objEmp->mb_uid, 0-$arrData['amount'], MONEYCHANGE_EXCHANGE_DEC);
                            if($this->modelMember->updateAssets($objEmp, $arrData['amount'], 0, MONEYCHANGE_EXCHANGE_INC, $objMember->mb_uid))
                                $objResult->status = STATUS_SUCCESS;
                            else $this->modelMember->updateAssets($objMember, $arrData['amount'], 0, MONEYCHANGE_EXCHANGE_DEC, $objEmp->mb_uid);
                        } 
                    } else if($iResult == 2) {
                        $objResult->status = STATUS_FAIL;
                        $objResult->msg = '회원이 게임플레이중이므로 환수 하실수 없습니다. '.intval(($_ENV['mem.delay_play']-diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet))/60+1)."분후 다시 시도해주세요.";
                    } else if($iResult == 3) {
                        $objResult->status = STATUS_FAIL;
                        $objResult->msg = '거절되었습니다.';
                    } else if($iResult == 4) {
                        $objResult->status = STATUS_FAIL;
                        $objResult->msg = '회원이 앱실행중이므로 환수 하실수 없습니다. ';
                    } else {
                        $objResult->status = STATUS_FAIL;
                        $objResult->msg = '게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요..';
                    }
                }
                 
            } else {
                
                $objChMember = null;            //하부회원찾기
                $arrEmp = $this->modelMember->getMemberByEmpFid($objEmp->mb_fid, $objEmp->mb_level,  $objEmp->mb_level, true, $objMember->mb_fid);
                if(count($arrEmp) > 0)
                    $objChMember = reset($arrEmp);

                $objResult->status = STATUS_FAIL;
                
                if(is_null($objChMember)){
                    $objResult->status = STATUS_FAIL;
                }else if($arrData['type'] == 2){                      //이송
                    
                    if($_ENV['mem.trans_deny']){
                        $objResult->msg = '거절되었습니다.';
                    } else if($_ENV['mem.trans_lv1'] && $objChMember->mb_emp_fid !== $objEmp->mb_fid){
                        $objResult->msg = '거절되었습니다.';
                    } else if(count($_ENV['mem.trans_lvs']) > 0 && !in_array(intval($objEmp->mb_level), $_ENV['mem.trans_lvs']) ){ 
                        $objResult->msg = '거절되었습니다.';
                    } else {
                        $iResult = 1;
                        if($arrData['amount'] > $objEmp->mb_money){
                            if($_ENV['mem.delay_play'] > 0 && $_ENV['mem.depodeny_play'] && diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                                $iResult = 2;
                            } 
                            else $iResult = $this->alltoGame($objEmp);
                        } 
                        if($_ENV['mem.withdeny_play'] && $iResult == 1){
                            $sess = $this->modelSess->getByUid($objEmp->mb_uid, false);
                            if(!is_null($sess))
                                $iResult = 4;
                        }

                        if($iResult == 1){
                            if($arrData['amount'] > $objEmp->mb_money){
                                $objResult->msg = '이동금액이 보유금액을 초과하셧습니다.';
                            } else if($this->modelMember->updateAssets($objEmp, 0-$arrData['amount'], 0, MONEYCHANGE_TRANS_DEC, $objMember->mb_uid)){
                                
                                if($this->modelMember->updateAssets($objMember, $arrData['amount'], 0, MONEYCHANGE_TRANS_INC, $objEmp->mb_uid))
                                    $objResult->status = STATUS_SUCCESS;
                                else $this->modelMember->updateAssets($objEmp, $arrData['amount'], 0, MONEYCHANGE_TRANS_DEC, $objMember->mb_uid);
                            } 
                        } else if($iResult == 2) {
                            $objResult->status = STATUS_FAIL;
                            $objResult->msg = '회원이 게임플레이중이므로 이동 하실수 없습니다. '.intval(($_ENV['mem.delay_play']-diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet))/60+1)."분후 다시 시도해주세요.";
                        } else if($iResult == 4) {
                            $objResult->status = STATUS_FAIL;
                            $objResult->msg = '앱실행중이므로 이동 하실수 없습니다. ';
                        } else {
                            $objResult->status = STATUS_FAIL;
                            $objResult->msg = '게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요..';
                        }
                    }
                    
                } else if($arrData['type'] == 3){               //환수

                    if(array_key_exists('app.sess_act', $_ENV) && $_ENV['app.sess_act'] == 1){
                        $memConfModel = new MemConf_Model();
                        $memConf = $memConfModel->getByMember($objEmp->mb_fid);
                        if(!is_null($memConf) ){
                            $_ENV['mem.return_deny'] = $_ENV['mem.return_deny'] || ($memConf->conf_num_1 != 1);
                        } else $_ENV['mem.return_deny'] = true;
                    }

                    if($_ENV['mem.return_deny']){
                        $objResult->msg = '거절되었습니다.';
                    } else if($_ENV['mem.return_lv1'] && $objChMember->mb_emp_fid !== $objEmp->mb_fid){
                        $objResult->msg = '거절되었습니다.';
                    } else if(count($_ENV['mem.trans_lvs']) > 0 && !in_array(intval($objEmp->mb_level), $_ENV['mem.trans_lvs']) ){ 
                        $objResult->msg = '거절되었습니다.';
                    } else {
                        $iResult = 1;
                        if($arrData['amount'] > $objMember->mb_money){
                            if($_ENV['mem.delay_play'] > 0 && $_ENV['mem.withdeny_play'] && diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                                $iResult = 2;
                            } 
                            else $iResult = $this->alltoGame($objMember);
                        } 
                        if($_ENV['mem.withdeny_play'] && $iResult == 1){
                            $sess = $this->modelSess->getByUid($objMember->mb_uid, false);
                            if(!is_null($sess))
                                $iResult = 4;
                        }

                        if($iResult == 1){
                            if($arrData['amount'] > $objMember->mb_money){
                                $arrData['amount'] = $objMember->mb_money;
                            }
                            if($arrData['amount'] > 0  && $this->modelMember->updateAssets($objMember, 0-$arrData['amount'], 0, MONEYCHANGE_EXCHANGE_DEC, $objEmp->mb_uid)){
                                // $this->modelMember->trasferMoney($objMember, $objEmp, $arrData['amount'], MONEYCHANGE_EXCHANGE_DEC, MONEYCHANGE_EXCHANGE_INC)
                                // $moneyhistoryModel->registerTransfer($objEmp, $objMember->mb_uid, $arrData['amount'], MONEYCHANGE_EXCHANGE_INC);
                                // $moneyhistoryModel->registerTransfer($objMember, $objEmp->mb_uid, 0-$arrData['amount'], MONEYCHANGE_EXCHANGE_DEC);
                                if($this->modelMember->updateAssets($objEmp, $arrData['amount'], 0, MONEYCHANGE_EXCHANGE_INC, $objMember->mb_uid))
                                $objResult->status = STATUS_SUCCESS;
                            else $this->modelMember->updateAssets($objMember, $arrData['amount'], 0, MONEYCHANGE_EXCHANGE_DEC, $objEmp->mb_uid);
                            } 
                        } else if($iResult == 2) {
                            $objResult->status = STATUS_FAIL;
                            $objResult->msg = '회원이 게임플레이중이므로 환수 하실수 없습니다. '.intval(($_ENV['mem.delay_play']-diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet))/60+1)."분후 다시 시도해주세요.";
                        } else if($iResult == 4) {
                            $objResult->status = STATUS_FAIL;
                            $objResult->msg = '회원이 앱실행중이므로 환수 하실수 없습니다. ';
                        } else {
                            $objResult->status = STATUS_FAIL;
                            $objResult->msg = '게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요..';
                        }
                    }
                }
                
            }

            echo json_encode($objResult);
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
            echo json_encode($arrResult);
        }
    }

    //머니, 포인트 회수
    public function withdraw()
    {
        $jsonData = $_REQUEST['json_'];
        $arrData = json_decode($jsonData, true);

        if (is_login()) {
            // model
            
            $moneyhistoryModel = new MoneyHistory_Model();
            $confsiteModel = new ConfSite_Model();
            $confsiteModel->readMemConf();

            $strUid = $this->session->user_id;
            $objEmp = $this->modelMember->getInfo($strUid);
            $objMember = $this->modelMember->getInfoByFid($arrData['mb_fid'], true);

            $objResult = new \stdClass();

            if(is_null($objMember) || is_null($objEmp)){
                $objResult->status = STATUS_FAIL;
            } else if($objEmp->mb_level < LEVEL_ADMIN) {
                $objResult->status = STATUS_FAIL;
            } else {
                /*if($arrData['type'] == 0){              //collect all money
                    if($_ENV['mem.delay_play'] > 0 && $_ENV['mem.withdeny_play'] && diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                        $iResult = 2;
                    } 
                    else 
                        $iResult = $this->alltoGame($objMember);

                    if($iResult == 1){
                        $nAmount = 0-$objMember->mb_money;
                        if($nAmount == 0){
                            $objResult->status = STATUS_SUCCESS;
                        } else if( $this->modelMember->updateAssets($objMember, $nAmount, 0, MONEYCHANGE_WITHDRAW, $objEmp->mb_uid)){
                            // $moneyhistoryModel->registerWithdraw($objMember, $objEmp->mb_uid, $nAmount, MONEYCHANGE_WITHDRAW);
                            $objResult->status = STATUS_SUCCESS;
                        }
                    } else if($iResult == 2) {
                        $objResult->status = STATUS_FAIL;
                        $objResult->msg = '회원이 게임플레이중이므로 회수 하실수 없습니다. '.intval(($_ENV['mem.delay_play']-diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet))/60+1)."분후 다시 시도해주세요.";
                    } else {
                        $objResult->status = STATUS_FAIL;
                        $objResult->msg = '게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요..';
                    }
                    
                } else*/ if($arrData['type'] == 1){ //포인트회수
                    $nAmount = 0-$objMember->mb_point;
                    if($nAmount == 0){
                        $objResult->status = STATUS_SUCCESS;
                    } 
                    else if($this->modelMember->updateAssets($objMember, 0, $nAmount, POINTHANGE_WITHDRAW, $objEmp->mb_uid)){
                        $moneyhistoryModel->registerWithdraw($objMember, $objEmp->mb_uid, $nAmount, POINTHANGE_WITHDRAW);
                        $objResult->status = STATUS_SUCCESS;
                    }
                } else if($arrData['type'] == 2){ //포인트전환
                    $nAmount = floor($objMember->mb_point);
                    if($nAmount < 1){
                        $objResult->status = STATUS_SUCCESS;
                    } 
                    else if($this->modelMember->updateAssets($objMember, $nAmount, 0-$nAmount, POINTCHANGE_EXCHANGE, $objEmp->mb_uid)){
                        // $moneyhistoryModel->registerPointToMoney($objMember, $nAmount, $objEmp->mb_uid, POINTCHANGE_EXCHANGE);
                        $objResult->status = STATUS_SUCCESS;
                    }
                } else {
                    $objResult->status = STATUS_FAIL;
                }

            } 

            echo json_encode($objResult);
        } else {
            $arrResult['status'] = STATUS_LOGOUT;
            echo json_encode($arrResult);
        }
    }

    public function empIp()
    {
        if (is_login()) {
            // model
            $strUid = $this->session->user_id;
            $objEmp = $this->modelMember->getInfo($strUid);

            if(is_null($objEmp) || $objEmp->mb_level < LEVEL_ADMIN){
                $arrResult['status'] = STATUS_FAIL;
            } else{
                $arrInfo = [
                    'ip_addr'  => $objEmp->mb_ip_join,
                    'ip_check' => $objEmp->mb_state_view > 0 ? 1:0,
                    'fid' => $objEmp->mb_fid,
                    'money' => allMoney($objEmp),
                    'egg' => allEgg($objEmp),
                ];
                $arrResult['data'] = $arrInfo;
                $arrResult['status'] = STATUS_SUCCESS;
            }

        } else {
            $arrResult['status'] = STATUS_LOGOUT;
            
        }
        echo json_encode($arrResult);
    }

      
	public function egginfo(){
        $jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$result = new \StdClass;
		if(!is_login())
		{
            $result->status = STATUS_LOGOUT;
		} else {

            $objMember = $this->modelMember->getInfoByFid($arrReqData['mb_fid']);
			if(!is_null($objMember)){
				$this->allEgg($objMember);
                $result->money = allMoney($objMember);
                $result->point = $objMember->mb_point;
                $result->egg = allEgg($objMember);
                $result->status = STATUS_SUCCESS;
    
            } else {
                $result->status = STATUS_FAIL;

            }


		}
		echo json_encode($result);

	}
     
	public function eggcollect(){
        $jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$result = new \StdClass;
		if(!is_login())
		{
            $result->status = STATUS_LOGOUT;
		} else {
            $confsiteModel = new ConfSite_Model();
            
            $strUid = $this->session->user_id;
            $objEmp = $this->modelMember->getInfo($strUid);
			$bSelf = false;
            if(array_key_exists('self', $arrReqData) && $arrReqData['self'] == 1)
    			$bSelf = true;
            if($bSelf)
                $objMember = $objEmp;
            else
                $objMember = $this->modelMember->getInfoByFid($arrReqData['mb_fid']);


            if(!is_null($objMember) && ($objEmp->mb_level >= LEVEL_ADMIN || $bSelf)){
                $confsiteModel->readMemConf();

                if(!$bSelf && diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                    $iResult = 2;
                } else $iResult = $this->alltoGame($objMember);

                if($iResult == 1){
                    $result->money = allMoney($objMember);
                    $result->egg = allEgg($objMember);
                    $result->status = STATUS_SUCCESS;
                } else if($iResult == 2) {
                    $result->status = STATUS_FAIL;
                    $result->msg = '회원이 게임플레이중이므로 회수 하실수 없습니다. '.intval(($_ENV['mem.delay_play']-diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet))/60+1)."분후 다시 시도해주세요.";
                } else 
                    $result->status = STATUS_FAIL;
    
            } else {
                $result->status = STATUS_FAIL;

            }

		}
		echo json_encode($result);

	}

    public function eggrecovery(){
        $jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		$result = new \StdClass;
		if(!is_login())
		{
            $result->status = STATUS_LOGOUT;
		} else {
            $confsiteModel = new ConfSite_Model();
            
            $strUid = $this->session->user_id;
            $objEmp = $this->modelMember->getInfo($strUid);
			$gameId = intval($arrReqData['game_index']);
			
            if($objEmp->mb_level >= LEVEL_ADMIN){
                $confsiteModel->readMemConf();
                $arrMember = $this->modelMember->findAll();

                $iResult = 1;
                $balance = 0;
                if($gameId == GAME_CASINO_EVOL){

                    foreach($arrMember as $objMember){
                        if($objMember->mb_live_id > 0 && $objMember->mb_live_money > 0 ){
                            writeLog("<EVOL> Recovery Uid=".$objMember->mb_uid." Balance=".$objMember->mb_live_money);
                            if(diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                                $iResult = 2;
                            } else $iResult = $this->evtoMb($objMember);
                            if($iResult == 0)
                                break;
                            else usleep(500000);
                        } 
                    }

                    $arrResult = $this->libApiCas->getAgentInfo();
                    if($arrResult['status'] == 1){
                        $confsiteModel->setConfActive(CONF_API_HPPLAY, $arrResult['balance']);
                        writeLog("<EVOL> Agent Egg = ".$arrResult['balance']);
                        $balance = $arrResult['balance'];
                    }
                } else if($gameId == GAME_SLOT_THEPLUS){
                    foreach($arrMember as $objMember){
                        if($objMember->mb_slot_uid != "" && $objMember->mb_slot_money > 0 ){
                            writeLog("<SLOT> Recovery Uid=".$objMember->mb_uid." Balance=".$objMember->mb_slot_money);
                            if(diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                                $iResult = 2;
                            } else $iResult = $this->sltoMb($objMember);
                            if($iResult == 0)
                                break;
                            else usleep(500000);
                        } 
                    }

                    $arrResult = $this->libApiSlot->getAgentInfo();
                    if($arrResult['status'] == 1){
                        $confsiteModel->setConfActive(CONF_API_THEPLUS, $arrResult['balance']);
                        writeLog("<SLOT> Agent Egg = ".$arrResult['balance']);
                        $balance = $arrResult['balance'];
                    }
    
                } else if($gameId == GAME_SLOT_GSPLAY){
                    foreach($arrMember as $objMember){
                        if($objMember->mb_fslot_id > 0 && $objMember->mb_fslot_money > 0 ){
                            writeLog("<FSLOT> Recovery Uid=".$objMember->mb_uid." Balance=".$objMember->mb_fslot_money);
                            if(diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                                $iResult = 2;
                            } else $iResult = $this->fsltoMb($objMember);
                            if($iResult == 0)
                                break;
                            else usleep(500000);
                        } 
                    }

                    $arrResult = $this->libApiFslot->getAgentInfo();
                    if($arrResult['status'] == 1){
                        $confsiteModel->setConfActive(CONF_API_GSPLAY, $arrResult['balance']);
                        writeLog("<FSLOT> AGENT Egg = ".$arrResult['balance']);
                        $balance = $arrResult['balance'];
                    }
    
                } else if($gameId == GAME_SLOT_GOLD){
                    foreach($arrMember as $objMember){
                        if($objMember->mb_gslot_uid != "" && $objMember->mb_gslot_money > 0 ) {
                            writeLog("<GSLOT> Recovery Uid=".$objMember->mb_uid." Balance=".$objMember->mb_gslot_money);
                            if(diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                                $iResult = 2;
                            } else $iResult = $this->gsltoMb($objMember);
                            if($iResult == 0)
                                break;
                            else usleep(500000);
                        } 
                    }

                    $arrResult = $this->libApiGslot->getUserInfo();
                    if($arrResult['status'] == 1){
                        $confsiteModel->setConfActive(CONF_API_GOLD, $arrResult['balance']);
                        writeLog("<GSLOT> Agent Egg = ".$arrResult['balance']);
                        $balance = $arrResult['balance'];
                    }
                } else if($gameId == GAME_CASINO_KGON || $gameId == GAME_SLOT_KGON){
                    foreach($arrMember as $objMember){
                        if($objMember->mb_kgon_id > 0 && $objMember->mb_kgon_money > 0 ){
                            writeLog("<KGON> Recovery Uid=".$objMember->mb_uid." Balance=".$objMember->mb_kgon_money);
                            if(diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                                $iResult = 2;
                            } else $iResult = $this->kgtoMb($objMember);
                            if($iResult == 0)
                                break;
                            else usleep(500000);
                        } 
                    }
                    
                    $arrResult = $this->libApiKgon->getAgentInfo();
                    if($arrResult['status'] == 1){
                        $confsiteModel->setConfActive(CONF_API_KGON, $arrResult['balance']);
                        writeLog("<KGON> AGENT Egg = ".$arrResult['balance']);
                        $balance = $arrResult['balance'];
                    }
                } else if($gameId == GAME_SLOT_STAR){
                    foreach($arrMember as $objMember){
                        if($objMember->mb_hslot_token != "" && $objMember->mb_hslot_money > 0 ) {
                            writeLog("<HSLOT> Recovery Uid=".$objMember->mb_uid." Balance=".$objMember->mb_hslot_money);
                            if(diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                                $iResult = 2;
                            } else $iResult = $this->hsltoMb($objMember);
                            if($iResult == 0)
                                break;
                            else usleep(500000);
                        } 
                    }

                    $arrResult = $this->libApiHslot->getAgentInfo();
                    if($arrResult['status'] == 1){
                        $confsiteModel->setConfActive(CONF_API_STAR, $arrResult['balance']);
                        writeLog("<HSLOT> Agent Egg = ".$arrResult['balance']);
                        $balance = $arrResult['balance'];
                    }
                }  else if($gameId == GAME_HOLD_CMS){
                    foreach($arrMember as $objMember){
                        if($objMember->mb_hold_uid != "" && $objMember->mb_hold_money > 0 ) {
                            writeLog("<HOLDEM> Recovery Uid=".$objMember->mb_uid." Balance=".$objMember->mb_hold_money);
                            if(diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                                $iResult = 2;
                            } else $iResult = $this->holtoMb($objMember);
                            if($iResult == 0)
                                break;
                            else usleep(500000);
                        } 
                    }

                    $arrResult = $this->libApiHold->getUserInfo();
                    if($arrResult['status'] == 1){
                        $confsiteModel->setConfActive(CONF_API_HOLD, $arrResult['balance']);
                        writeLog("<HOLDEM> Agent Egg = ".$arrResult['balance']);
                        $balance = $arrResult['balance'];
                    }
                } else if($gameId == GAME_CASINO_RAVE || $gameId == GAME_SLOT_RAVE){
                    foreach($arrMember as $objMember){
                        if($objMember->mb_rave_id > 0 && $objMember->mb_rave_money > 0 ){
                            writeLog("<RAVE> Recovery Uid=".$objMember->mb_uid." Balance=".$objMember->mb_rave_money);
                            if(diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                                $iResult = 2;
                            } else $iResult = $this->rvtoMb($objMember);
                            if($iResult == 0)
                                break;
                            else usleep(500000);
                        } 
                    }
                    
                    $arrResult = $this->libApiRave->getAgentInfo();
                    if($arrResult['status'] == 1){
                        $confsiteModel->setConfActive(CONF_API_RAVE, $arrResult['balance']);
                        writeLog("<RAVE> AGENT Egg = ".$arrResult['balance']);
                        $balance = $arrResult['balance'];
                    }
                } else if($gameId == GAME_CASINO_TREEM || $gameId == GAME_SLOT_TREEM){
                    foreach($arrMember as $objMember){
                        if(strlen($objMember->mb_treem_uid) > 0 && $objMember->mb_treem_money > 0 ){
                            writeLog("<TREEM> Recovery Uid=".$objMember->mb_uid." Balance=".$objMember->mb_treem_money);
                            if(diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                                $iResult = 2;
                            } else $iResult = $this->trtoMb($objMember);
                            if($iResult == 0)
                                break;
                            else usleep(500000);
                        } 
                    }
                    
                    $arrResult = $this->libApiTreem->getAgentInfo();
                    if($arrResult['status'] == 1){
                        $confsiteModel->setConfActive(CONF_API_TREEM, $arrResult['balance']);
                        writeLog("<TREEM> AGENT Egg = ".$arrResult['balance']);
                        $balance = $arrResult['balance'];
                    }
                } else if($gameId == GAME_CASINO_SIGMA || $gameId == GAME_SLOT_SIGMA){
                    foreach($arrMember as $objMember){
                        if(strlen($objMember->mb_sigma_uid) > 0 && $objMember->mb_sigma_money > 0 ){
                            writeLog("<SIGMA> Recovery Uid=".$objMember->mb_uid." Balance=".$objMember->mb_sigma_money);
                            if(diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_bet) < $_ENV['mem.delay_play']){
                                $iResult = 2;
                            } else $iResult = $this->sgtoMb($objMember);
                            if($iResult == 0)
                                break;
                            else usleep(500000);
                        } 
                    }
                    
                    $arrResult = $this->libApiSigma->getAgentInfo();
                    if($arrResult['status'] == 1){
                        $confsiteModel->setConfActive(CONF_API_SIGMA, $arrResult['balance']);
                        writeLog("<SIGMA> AGENT Egg = ".$arrResult['balance']);
                        $balance = $arrResult['balance'];
                    }
                }

                $result->status = STATUS_SUCCESS;
			    $result->egg = $balance;
			    $result->useregg = $this->modelMember->calcGameEgg($gameId);
    
            } else {
                $result->status = STATUS_FAIL;

            }

		}
		echo json_encode($result);

	}

    
	public function resetchargeinfo(){
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
				$memConfModel = new MemConf_Model();
                $data =[
                    'conf_str_5' => "",
                ];
                $memConfModel->updateData(null, $data);

				$arrResult['status'] = "success";
			} else $arrResult['status'] = "nopermit";
		}
		else {
			$arrResult['status'] = "logout";			
		}
		echo json_encode($arrResult);	
	}

    // function massMember(){
    //     $infos = array (
    //         array( 'jpm9743','제이피엠','0000','0000','ssk ','jw8188','케이뱅크','100160278688','김종권','01099914051','4780','6301','0'),
    //         );
            
    //         writeLog("==============================================");


    //         $error = '';
    //         foreach($infos as $info){

    //             $result = $this->modelMember->registerInfo($info, $error);
    //             if($result == 1){
    //                 writeLog($info[0]."-success");
    //             } else {
    //                 writeLog($info[0]."-fail");
    //                 var_dump($error);
    //                 break;
    //             }

    //             usleep(100000);
    //             // break;
    //         }

    //         echo "End";
    // }


}
