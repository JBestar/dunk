<?php namespace App\Controllers;

use App\Models\SlotPrd_Model;
use App\Models\SlotGame_Model;

class Slot extends BaseController
{

    public function index()
    {
        $this->response->redirect(site_furl('/'));	
    }

	public function xslotlist()
	{
		$this->setLanguage();
		
		$prdCode = trim($this->request->getVar('prd'));
		$ip = trim($this->request->getVar('ip'));
		if(!is_login(true))
		{
			print "<script> alert('".lang("common.session_expired")."'); self.close(); </script>";

        } else if($_ENV['app.type'] == APP_TYPE_2){
			$this->response->redirect(site_furl('/fslotlist?prd='.$prdCode));	
		}  else {
			$this->sess_action();                
			$gameId = GAME_SLOT_THEPLUS;
			if($_ENV['app.slot'] == APP_SLOT_KGON)
				$gameId = GAME_SLOT_KGON;
			else if($_ENV['app.slot'] == APP_SLOT_STAR)
				$gameId = GAME_SLOT_STAR;
			else if($_ENV['app.slot'] == APP_SLOT_RAVE)
				$gameId = GAME_SLOT_RAVE;
			else if($_ENV['app.slot'] == APP_SLOT_TREEM)
				$gameId = GAME_SLOT_TREEM;
			else if($_ENV['app.slot'] == APP_SLOT_SIGMA)
				$gameId = GAME_SLOT_SIGMA;

			$modelSlotgame = new SlotGame_Model();
            $prdCode = trim($this->request->getVar('prd'));
            $objPrd = $this->modelSlotprd->getByCode($gameId, $prdCode);

			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //Slot 1
			$headInfo = $this->getSiteConf();

            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig) || is_null($objPrd))
				$iCreated = 0;
			// else if($objConfig->game_bet_permit != PERMIT_OK){
			// 	$iCreated = 4;									//Praparing
			// }
			else if($headInfo['slot_deny'])
                $iCreated = 3;									//Stop
			else if(!$this->modelMember->isPermitMember($objMember, $gameId))
				$iCreated = 3;									//Stop
			else {
				$iCreated = 1;
			}

			if($iCreated == 0){
				print "<script language=javascript> alert('".lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정생성중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('".lang("common.prepare")."'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('".lang("common.user_duplicated").lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 1){

				$games = $modelSlotgame->gets($gameId, $objPrd->code);

				writeLog("<XSLOT_PRD> Code:".$objPrd->code." ip=".$ip." Count:".count($games));

                echo view('home/slotlist', array("prd" => $objPrd->code, "games" => $games, "ip" => $ip));

			}
        }

    }

	public function xslot(){
		
		$prdCode = trim($this->request->getVar('prd'));
		$slotId = trim($this->request->getVar('game'));
		$ip = trim($this->request->getVar('ip'));

		if(!is_login())
		{
			print "<script> alert('".lang("common.session_expired")."'); self.close(); </script>";
        } else if($_ENV['app.type'] == APP_TYPE_2){
			$this->response->redirect(site_furl('/slot/xslotf?prd='.$prdCode.'&game='.$slotId));	
		} else {
			$this->sess_action();                

			$modelSlotgame = new SlotGame_Model();

			$gameId = GAME_SLOT_THEPLUS;
			if($_ENV['app.slot'] == APP_SLOT_KGON)
				$gameId = GAME_SLOT_KGON;
			else if($_ENV['app.slot'] == APP_SLOT_STAR)
				$gameId = GAME_SLOT_STAR;
			else if($_ENV['app.slot'] == APP_SLOT_RAVE)
				$gameId = GAME_SLOT_RAVE;
			else if($_ENV['app.slot'] == APP_SLOT_TREEM)
				$gameId = GAME_SLOT_TREEM;
			else if($_ENV['app.slot'] == APP_SLOT_SIGMA)
				$gameId = GAME_SLOT_SIGMA;
			$logHead = "<XSLOT>";
			
			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id, true);
			$objConfig = $this->modelConfgame->find($gameId);  //slot 1
			
			$objPrd = $this->modelSlotprd->getByCode($gameId, $prdCode);
			$objSlot = $modelSlotgame->getById($gameId, $prdCode, $slotId);
			$headInfo = $this->getSiteConf();
			$diffDt = diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_call) ;
			$sess = $this->modelSess->getByUid($objMember->mb_uid, false);

            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig))
				$iCreated = 0;
			else if($headInfo['slot_deny'])
                $iCreated = 3;									//Stop
			else if(!$this->modelMember->isPermitMember($objMember, $gameId))
				$iCreated = 3;									//Stop
			else if(is_null($objSlot) || is_null($objPrd))
				$iCreated = 6;
			// else if($objSlot->maintain == STATE_ACTIVE)
			// 	$iCreated = 7;
			else if($diffDt < DELAY_GAME)
				$iCreated = 8;	
			else if(!is_null($sess))
				$iCreated = 9;	
			else {
				$objFslot  = null;
					
				if($_ENV['app.type'] == APP_TYPE_1) {
					$fgameId = GAME_SLOT_GSPLAY;
					if($_ENV['app.fslot'] == APP_FSLOT_GOLD)
						$fgameId = GAME_SLOT_GOLD;

					$objFslot = $modelSlotgame->getByRef($fgameId, $objSlot->prd_code, $objSlot->uuid);

				}

				if(!is_null($objFslot)){
					writeLog("<FSLOT>".$objMember->mb_uid." PRD=".$objFslot->prd_code." NAME=".$objFslot->name_ko);
					$iCreated = 100;
				} else if($objConfig->game_bet_permit != PERMIT_OK){			//Preparing
					$iCreated = 4;									
				} else if($_ENV['app.slot'] == APP_SLOT_KGON || $_ENV['app.slot'] == APP_SLOT_STAR || 
					$_ENV['app.slot'] == APP_SLOT_RAVE || $_ENV['app.slot'] == APP_SLOT_TREEM ||
					$_ENV['app.slot'] == APP_SLOT_SIGMA)
					$iCreated = 101;
				else if($objMember->mb_slot_uid == ""){
					//Creation of Player
					$createId = createGameId(substr($_ENV['app.gm_prefix'], 0, 2)."_".$objMember->mb_fid);//."_".$objMember->mb_uid
					$arrResult =  $this->libApiSlot->createUser($createId);
					if($arrResult['status'] == 1){
						$objMember->mb_slot_uid = $createId;
						$this->modelMember->updateSlotInfo($objMember);
						$iCreated = 1;
	
						writeLog($logHead.$objMember->mb_uid."-CreateUser Sucess !!");
					} else {
						if(array_key_exists('resultCode', $arrResult) && $arrResult['resultCode'] == SLOTCODE_DOUBLE_USER) {
							usleep(500000);
							$arrResult = $this->libApiSlot->getUserInfo($createId);
							writeLog($logHead.$objMember->mb_uid."-Double UserInfo Status=".$arrResult['status']);
							if($arrResult['status'] == 1){
								$objMember->mb_slot_uid = $createId;
								$objMember->mb_slot_money = $arrResult['balance'];
								$this->modelMember->updateSlotInfo($objMember);
								$iCreated = 1;
	
							} else $iCreated = 5;								//Duplicated User
						}
						else $iCreated = 2;								//Fail in Creation of user
						writeLog($logHead.$objMember->mb_uid."-CreateUser resultCode=".$arrResult['resultCode']); 
					}
				} else {
					$iCreated = 1;
				}
			}
			
			if($iCreated == 0){
				print "<script language=javascript> alert('".lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정생성중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('".lang("common.prepare")."'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('".lang("common.user_duplicated").lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 6){
				print "<script language=javascript> alert('존재하지 않는 게임입니다.'); self.close(); </script>";
			} else if($iCreated == 7){
				print "<script language=javascript> alert('점검중입니다.'); self.close(); </script>";
			} else if($iCreated == 8){
				print "<script language=javascript> alert('".langTo($this->session->lang, "game_delay", DELAY_GAME-$diffDt)."'); self.close(); </script>";
			}  else if($iCreated == 9){
				print "<script language=javascript> alert('앱이 실행중이므로 게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 100){
				if($_ENV['app.fslot'] == APP_FSLOT_GOLD)
					$this->response->redirect(site_furl('/xslotg?prd='.$objFslot->prd_code.'&game='.$objFslot->uuid));	
				else 
					$this->response->redirect(site_furl('/xslotf?prd='.$objFslot->prd_code.'&game='.$objFslot->uuid));	
			} else if($iCreated == 101){
				writeLog("<XSLOT>".$objMember->mb_uid." PRD=".$objSlot->prd_code." NAME=".$objSlot->name_ko);

				if($_ENV['app.slot'] == APP_SLOT_KGON)
					$this->response->redirect(site_furl('/xslotk?prd='.$objSlot->prd_code.'&game='.$objSlot->uuid));	
				else if($_ENV['app.slot'] == APP_SLOT_STAR)
					$this->response->redirect(site_furl('/xsloth?prd='.$objSlot->prd_code.'&game='.$objSlot->uuid));	
				else if($_ENV['app.slot'] == APP_SLOT_RAVE)
					$this->response->redirect(site_furl('/xslotr?prd='.$objSlot->prd_code.'&game='.$objSlot->uuid));	
				else if($_ENV['app.slot'] == APP_SLOT_TREEM)
					$this->response->redirect(site_furl('/xslott?prd='.$objSlot->prd_code.'&game='.$objSlot->uuid));	
				else if($_ENV['app.slot'] == APP_SLOT_SIGMA)
					$this->response->redirect(site_furl('/xslots?prd='.$objSlot->prd_code.'&game='.$objSlot->uuid.'&ip='.$ip));	
			} else if($iCreated == 1){
				writeLog($logHead.$objMember->mb_uid."-Slot Game=>".$objSlot->name ); 
				$iResult = $this->alltoGame($objMember, $gameId);

				if($iResult != 1){ //Fail in transfering of money
					print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_TRANSFER.")'); self.close(); </script>";
				} else {
					$arrResult =  $this->libApiSlot->createSess($objMember->mb_slot_uid);
					if($arrResult['status'] != 1) {
						writeLog($logHead.$objMember->mb_uid."-CreateSess resultCode=".$arrResult['resultCode']); 
						print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_RESPONSE.")'); self.close(); </script>";
					}
					else{
						writeLog($logHead.$objMember->mb_uid."-CreateSess ID=".$arrResult['session']); 

						$arrResult =  $this->libApiSlot->getLink($arrResult['session'], $objSlot->uuid);

						if($arrResult['status'] == 1){
							writeLog($logHead.$objMember->mb_uid."-Link Sucess !!");
							writeLog($logHead.$arrResult['gameUrl']);
							$this->modelMember->updateBetTm($objMember);
							// echo view('slot/game', array("game" => GAME_CASINO_EVOL, "launch_url" => $arrResult['gameUrl']));	
							$this->response->redirect($arrResult['gameUrl']);
						} else {
							writeLog($logHead.$objMember->mb_uid."-Link resultCode=".$arrResult['resultCode']);
							if(array_key_exists('resultCode', $arrResult) && $arrResult['resultCode'] == SLOTCODE_USER_NONE){
								print "<script language=javascript> alert('".lang("common.user_exist").lang("common.administrator_ask")."'); self.close(); </script>";
							}
							else {
								print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_RESPONSE.")'); self.close(); </script>";
							} 
						}
					}
				}  
			}
			
		}
	}


	public function xslotf(){
		$this->setLanguage();
		$prdCode = trim($this->request->getVar('prd'));
		$slotId = trim($this->request->getVar('game'));
			
		if(!is_login())
		{
			print "<script> alert('".lang("common.session_expired")."'); self.close(); </script>";

        } else if($_ENV['app.fslot'] == APP_FSLOT_GOLD){
			$this->response->redirect(site_furl('/slot/xslotg?prd='.$prdCode.'&game='.$slotId));	
		} else {
			$this->sess_action();                
			$modelSlotgame = new SlotGame_Model();

			$gameId = GAME_SLOT_GSPLAY;
			$logHead = "<FSLOT>";
			
			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //Slot 2

			$objPrd = $this->modelSlotprd->getByCode($gameId, $prdCode);
			$objSlot = $modelSlotgame->getById($gameId, $prdCode, $slotId);
			$diffDt = diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_call) ;
			$sess = $this->modelSess->getByUid($objMember->mb_uid, false);

            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig))
				$iCreated = 0;
			else if($objConfig->game_bet_permit != PERMIT_OK){
				$iCreated = 4;									//Preparing
			}
			else if($_ENV['app.type'] == APP_TYPE_2 && !$this->modelMember->isPermitMember($objMember, $gameId))
				$iCreated = 3;									//Stop
			else if(is_null($objSlot) || is_null($objPrd)){
				$iCreated = 6;
			} 
			else if($_ENV['app.type'] == APP_TYPE_2 && $objSlot->maintain == STATE_ACTIVE){
				$iCreated = 7;
			} 
			else if($diffDt < DELAY_GAME)
				$iCreated = 8;
			else if(!is_null($sess))
				$iCreated = 9;
			else if($objMember->mb_fslot_id == 0){
				//Create Player
				$createId = createGameId(substr($_ENV['app.gm_prefix'], 0, 2)."_".$objMember->mb_fid);//."_".$objMember->mb_uid
				$arrResult = $this->libApiFslot->createUser($createId, $objMember->mb_nickname);
				if($arrResult['status'] == 1){
					$objMember->mb_fslot_id = $arrResult['gs_user_id'];
					$objMember->mb_fslot_uid = $createId;
					$this->modelMember->updateFslotInfo($objMember);
					$iCreated = 1;

					writeLog($logHead.$objMember->mb_uid."-CreateUser Sucess !!");
				} else {
					if(array_key_exists('error', $arrResult) && $arrResult['error'] == DOUBLE_USER){
						usleep(500000);
						$arrResult = $this->libApiFslot->getUserInfo($createId);
						writeLog($logHead.$objMember->mb_uid."-Double UserInfo Status=".$arrResult['status']);
                        if($arrResult['status'] == 1){
							$objMember->mb_fslot_id = $arrResult['gs_user_id'];
							$objMember->mb_fslot_uid = $arrResult['user_id'];
							$objMember->mb_fslot_money = $arrResult['balance'];
							$this->modelMember->updateFslotInfo($objMember);
							$iCreated = 1;

						} else 
							$iCreated = 5;								//Duplicated User
					} else $iCreated = 2;								//Fail in Creation of User
					if(array_key_exists('error', $arrResult))
						writeLog($logHead.$objMember->mb_uid."-CreateUser Error=".$arrResult['error']); 
				}
			} else {
				$iCreated = 1;
			}

			if($iCreated == 0){
				print "<script language=javascript> alert('".lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정생성중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('".lang("common.prepare")."'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('".lang("common.user_duplicated").lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 6){
				print "<script language=javascript> alert('존재하지 않는 게임입니다.'); self.close(); </script>";
			} else if($iCreated == 7){
				print "<script language=javascript> alert('점검중입니다.'); self.close(); </script>";
			} else if($iCreated == 8){
				print "<script language=javascript> alert('".langTo($this->session->lang, "game_delay", DELAY_GAME-$diffDt)."'); self.close(); </script>";
			} else if($iCreated == 9){
				print "<script language=javascript> alert('앱이 실행중이므로 게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 1){
				writeLog($logHead.$objMember->mb_uid." Game=>".$objSlot->prd_code.":".$objSlot->name_ko ); 

				$iResult = $this->alltoGame($objMember, $gameId);
				if($iResult == 1){
					$arrResult = $this->libApiFslot->auth($objMember->mb_fslot_uid, $objMember->mb_nickname, $objSlot);
					if($arrResult['status'] == 1){
						writeLog($logHead.$objMember->mb_uid."-Login Sucess !!");
						writeLog($logHead.$arrResult['launch_url']);
						$this->modelMember->updateBetTm($objMember);
						$this->response->redirect($arrResult['launch_url']);
						// echo view('slot/game', array("game" => $gameId, "launch_url" => $arrResult['launch_url']));	
					} else {
						if(array_key_exists('error', $arrResult) && $arrResult['error'] == INVALID_USER){
							print "<script language=javascript> alert('".lang("common.user_exist").lang("common.administrator_ask")."'); self.close(); </script>";
						}
						else {
							if(array_key_exists('error', $arrResult)) {
								writeLog($logHead.$objMember->mb_uid."launchError=".$arrResult['error']);
							}
							print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_RESPONSE.")'); self.close(); </script>";
						} 
					}
				} else { //Fail in Transfering of money
					print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_TRANSFER.")'); self.close(); </script>";
				}
				 
			}

		}
	}

	
	public function xslotg(){
		$this->setLanguage();
		if(!is_login())
		{
			print "<script> alert('".lang("common.session_expired")."'); self.close(); </script>";

        } else {
			$this->sess_action();                
			$modelSlotgame = new SlotGame_Model();

			$gameId = GAME_SLOT_GOLD;
			$logHead = "<GSLOT>";
			
			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //Slot 2

			$prdCode = trim($this->request->getVar('prd'));
			$slotId = trim($this->request->getVar('game'));
			$objPrd = $this->modelSlotprd->getByCode($gameId, $prdCode);
			$objSlot = $modelSlotgame->getById($gameId, $prdCode, $slotId);
			$diffDt = diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_call) ;
			$sess = $this->modelSess->getByUid($objMember->mb_uid, false);

            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig))
				$iCreated = 0;
			else if($objConfig->game_bet_permit != PERMIT_OK){
				$iCreated = 4;									//Preparing
			}
			else if($_ENV['app.type'] == APP_TYPE_2 && !$this->modelMember->isPermitMember($objMember, $gameId))
				$iCreated = 3;									//Stop
			else if(is_null($objSlot) || is_null($objPrd)){
				$iCreated = 6;
			} 
			else if($_ENV['app.type'] == APP_TYPE_2 && $objSlot->maintain == STATE_ACTIVE){
				$iCreated = 7;
			} 
			else if($diffDt < DELAY_GAME)
				$iCreated = 8;
			else if(!is_null($sess))
				$iCreated = 9;
			else if($objMember->mb_gslot_uid == ""){
				//Create Player
				$createId = createGameId(substr($_ENV['app.gm_prefix'], 0, 2)."_".$objMember->mb_fid);//."_".$objMember->mb_uid
				$arrResult = $this->libApiGslot->createUser($createId);
				if($arrResult['status'] == 1){
					$objMember->mb_gslot_uid = $createId;
					$objMember->mb_gslot_money = $arrResult['user_balance'];
					$this->modelMember->updateGslotInfo($objMember);
					$iCreated = 1;

					writeLog($logHead.$objMember->mb_uid."-CreateUser Sucess !!");
				} else {
					if(array_key_exists('msg', $arrResult) && $arrResult['msg'] == "DUPLICATED_USER"){
						usleep(500000);
						$arrResult = $this->libApiGslot->getUserInfo($createId);
						writeLog($logHead.$objMember->mb_uid."-Double UserInfo Status=".$arrResult['status']);
                        if($arrResult['status'] == 1){
							$objMember->mb_gslot_uid = $arrResult['user_list'][0]['user_code'];
							$objMember->mb_gslot_money = $arrResult['user_list'][0]['balance'];
							$this->modelMember->updateGslotInfo($objMember);
							$iCreated = 1;

						} else 
							$iCreated = 5;								//Duplicated User
					} else $iCreated = 2;								//Fail in Creation of User
					if(array_key_exists('msg', $arrResult))
						writeLog($logHead.$objMember->mb_uid."-CreateUser msg=".$arrResult['msg']); 
				}
			} else {
				$iCreated = 1;
			}

			if($iCreated == 0){
				print "<script language=javascript> alert('".lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정생성중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('".lang("common.prepare")."'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('".lang("common.user_duplicated").lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 6){
				print "<script language=javascript> alert('존재하지 않는 게임입니다.'); self.close(); </script>";
			} else if($iCreated == 7){
				print "<script language=javascript> alert('점검중입니다.'); self.close(); </script>";
			} else if($iCreated == 8){
				print "<script language=javascript> alert('".langTo($this->session->lang, "game_delay", DELAY_GAME-$diffDt)."'); self.close(); </script>";
			} else if($iCreated == 9){
				print "<script language=javascript> alert('앱이 실행중이므로 게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 1){
				writeLog($logHead.$objMember->mb_uid." Game=>".$objSlot->prd_code.":".$objSlot->name_ko ); 

				$iResult = $this->alltoGame($objMember, $gameId);
				if($iResult == 1){
					$arrResult = $this->libApiGslot->auth($objMember->mb_gslot_uid, $objSlot->provider, $objSlot->game_code);
					if($arrResult['status'] == 1){
						writeLog($logHead.$objMember->mb_uid."-Login Sucess !!");
						writeLog($logHead.$arrResult['launch_url']);
						$this->modelMember->updateBetTm($objMember);
						$this->response->redirect($arrResult['launch_url']);
						// echo view('slot/game', array("game" => $gameId, "launch_url" => $arrResult['launch_url']));	
					} else {
						if(array_key_exists('msg', $arrResult) && $arrResult['msg'] == "INVALID_USER"){
							print "<script language=javascript> alert('".lang("common.user_exist").lang("common.administrator_ask")."'); self.close(); </script>";
						}
						else {
							if(array_key_exists('msg', $arrResult)) {
								writeLog($logHead.$objMember->mb_uid."launch msg=".$arrResult['msg']);
							}
							print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_RESPONSE.")'); self.close(); </script>";
						} 
					}
				} else { //Fail in Transfering of money
					print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_TRANSFER.")'); self.close(); </script>";
				}
				 
			}

		}
	}

	public function xslotk(){
		$this->setLanguage();
		if(!is_login())
		{
			print "<script> alert('".lang("common.session_expired")."'); self.close(); </script>";

        } else {
			$this->sess_action();                
			$modelSlotgame = new SlotGame_Model();

			$gameId = GAME_SLOT_KGON;
			$logHead = "<KSLOT>";
			
			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //Slot 3

			$prdCode = trim($this->request->getVar('prd'));
			$slotId = trim($this->request->getVar('game'));
			$objPrd = $this->modelSlotprd->getByCode($gameId, $prdCode);
			$objSlot = $modelSlotgame->getById($gameId, $prdCode, $slotId);
			$diffDt = diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_call) ;
			$sess = $this->modelSess->getByUid($objMember->mb_uid, false);

            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig))
				$iCreated = 0;
			else if($objConfig->game_bet_permit != PERMIT_OK){
				$iCreated = 4;									//Preparing
			}
			// else if($_ENV['app.type'] == APP_TYPE_3 && !$this->modelMember->isPermitMember($objMember, $gameId))
			// 	$iCreated = 3;									//Stop
			else if(is_null($objSlot) || is_null($objPrd)){
				$iCreated = 6;
			} 
			// else if($_ENV['app.type'] == APP_TYPE_3 && $objSlot->maintain == STATE_ACTIVE){
			// 	$iCreated = 7;
			// } 
			else if($diffDt < DELAY_GAME)
				$iCreated = 8;
			else if(!is_null($sess))
				$iCreated = 9;
			else if($objMember->mb_kgon_id == 0){
				//Create Player
				$createId = createGameId(substr($_ENV['app.gm_prefix'], 0, 2)."_".$objMember->mb_fid);//."_".$objMember->mb_uid
				$arrResult = $this->libApiKgon->createUser($createId, $objMember->mb_nickname, $objMember->mb_uid);
				if($arrResult['status'] == 1){
					$objMember->mb_kgon_id = $arrResult['id'];
                    $objMember->mb_kgon_uid = $createId;
                    $this->modelMember->updateKgonInfo($objMember);
                    $iCreated = 1;

                    writeLog($logHead.$objMember->mb_uid."-CreateUser Sucess !!");
				} else {
					if(array_key_exists('code', $arrResult) && $arrResult['code'] == -500){
						$iCreated = 5;								//Duplicated User 
					} else {
						$iCreated = 2;								//Fail in Creation of User
					}

					if(array_key_exists('code', $arrResult))
					writeLog($logHead.$objMember->mb_uid."-CreateUser Error code=".$arrResult['code']." Msg=".$arrResult['msg']); 
				}
			} else {
				$iCreated = 1;
			}

			if($iCreated == 0){
				print "<script language=javascript> alert('".lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정생성중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('".lang("common.prepare")."'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('".lang("common.user_duplicated").lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 6){
				print "<script language=javascript> alert('존재하지 않는 게임입니다.'); self.close(); </script>";
			} else if($iCreated == 7){
				print "<script language=javascript> alert('점검중입니다.'); self.close(); </script>";
			} else if($iCreated == 8){
				print "<script language=javascript> alert('".langTo($this->session->lang, "game_delay", DELAY_GAME-$diffDt)."'); self.close(); </script>";
			} else if($iCreated == 9){
				print "<script language=javascript> alert('앱이 실행중이므로 게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 1){
				writeLog($logHead.$objMember->mb_uid." Game=>".$objSlot->prd_code.":".$objSlot->name_ko ); 

				$iResult = $this->alltoGame($objMember, $gameId);
				if($iResult == 1){
					$arrResult = $this->libApiKgon->auth($objMember->mb_kgon_uid, $objMember->mb_nickname, $objMember->mb_uid, $objSlot->provider, $objSlot->game_code);
					if($arrResult['status'] == 1){
						writeLog($logHead.$objMember->mb_uid."-Login Sucess !!");
						writeLog($logHead.$arrResult['url']);
						$this->modelMember->updateBetTm($objMember);
						$this->response->redirect($arrResult['url']);
					} else {
						if(array_key_exists('code', $arrResult)){
							$log = $logHead.$objMember->mb_uid."-Auth Error code=".$arrResult['code'];
							if(array_key_exists('msg', $arrResult))
								$log.=" msg=".$arrResult['msg'];
							writeLog($log); 
						} 
                        print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_RESPONSE.")'); self.close(); </script>";
					}
				} else { //Fail in Transfering of money
					print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_TRANSFER.")'); self.close(); </script>";
				}
				 
			}

		}
	}

	public function xsloth(){
		$this->setLanguage();
		if(!is_login())
		{
			print "<script> alert('".lang("common.session_expired")."'); self.close(); </script>";

        } else {
			$this->sess_action();                
			$modelSlotgame = new SlotGame_Model();

			$gameId = GAME_SLOT_STAR;
			$logHead = "<HSLOT>";
			
			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //Slot 4

			$prdCode = trim($this->request->getVar('prd'));
			$slotId = trim($this->request->getVar('game'));
			$objPrd = $this->modelSlotprd->getByCode($gameId, $prdCode);
			$objSlot = $modelSlotgame->getById($gameId, $prdCode, $slotId);
			$diffDt = diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_call) ;
			$sess = $this->modelSess->getByUid($objMember->mb_uid, false);

            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig))
				$iCreated = 0;
			else if($objConfig->game_bet_permit != PERMIT_OK){
				$iCreated = 4;									//Preparing
			}
			// else if($_ENV['app.type'] == APP_TYPE_3 && !$this->modelMember->isPermitMember($objMember, $gameId))
			// 	$iCreated = 3;									//Stop
			else if(is_null($objSlot) || is_null($objPrd)){
				$iCreated = 6;
			} 
			// else if($_ENV['app.type'] == APP_TYPE_3 && $objSlot->maintain == STATE_ACTIVE){
			// 	$iCreated = 7;
			// } 
			else if($diffDt < DELAY_GAME)
				$iCreated = 8;
			else if(!is_null($sess))
				$iCreated = 9;
			else if($objMember->mb_hslot_token == ""){
				writeLog($logHead.$objMember->mb_uid."-1");
				//Create Player
				$arrResult = $this->libApiHslot->createUser($objMember->mb_uid, $objMember->mb_nickname);

				if($arrResult['status'] == 1){

					$objMember->mb_hslot_token = $arrResult['data']['token'];
                    $objMember->mb_hslot_money = 0;
                    $this->modelMember->updateHslotInfo($objMember);
                    $iCreated = 1;

                    writeLog($logHead.$objMember->mb_uid."-CreateUser Sucess !!");
				} else {
					writeLog($logHead.$objMember->mb_uid."-2");

					$iCreated = 2;								//Fail in Creation of User
					if(array_key_exists('description', $arrResult))
						writeLog($logHead.$objMember->mb_uid."-CreateUser Error description=".$arrResult['description']); 
				}
				writeLog($logHead.$objMember->mb_uid."-3");

			} else {
				$iCreated = 1;
			}

			if($iCreated == 0){
				print "<script language=javascript> alert('".lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정생성중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('".lang("common.prepare")."'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('".lang("common.user_duplicated").lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 6){
				print "<script language=javascript> alert('존재하지 않는 게임입니다.'); self.close(); </script>";
			} else if($iCreated == 7){
				print "<script language=javascript> alert('점검중입니다.'); self.close(); </script>";
			} else if($iCreated == 8){
				print "<script language=javascript> alert('".langTo($this->session->lang, "game_delay", DELAY_GAME-$diffDt)."'); self.close(); </script>";
			} else if($iCreated == 9){
				print "<script language=javascript> alert('앱이 실행중이므로 게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 1){
				writeLog($logHead.$objMember->mb_uid." Game=>".$objSlot->prd_code.":".$objSlot->name_ko ); 

				$iResult = $this->alltoGame($objMember, $gameId);
				if($iResult == 1){
					$arrResult = $this->libApiHslot->auth($objMember->mb_hslot_token, $objSlot);
					if($arrResult['status'] == 1){
						writeLog($logHead.$objMember->mb_uid."-Login Sucess !!");
						writeLog($logHead.$arrResult['url']);
						$this->modelMember->updateBetTm($objMember);
						$this->response->redirect($arrResult['url']);
					} else {
						if(array_key_exists('description', $arrResult)){
							$log = $logHead.$objMember->mb_uid."-Auth Error description=".$arrResult['description'];
							writeLog($log); 
						} 
                        print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_RESPONSE.")'); self.close(); </script>";
					}
				} else { //Fail in Transfering of money
					print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_TRANSFER.")'); self.close(); </script>";
				}
				 
			}
		}
	}

	public function xslotr(){
		$this->setLanguage();
		if(!is_login())
		{
			print "<script> alert('".lang("common.session_expired")."'); self.close(); </script>";

        } else {
			$this->sess_action();                
			$modelSlotgame = new SlotGame_Model();

			$gameId = GAME_SLOT_RAVE;
			$logHead = "<RSLOT>";
			
			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //Slot 3

			$prdCode = trim($this->request->getVar('prd'));
			$slotId = trim($this->request->getVar('game'));
			$objPrd = $this->modelSlotprd->getByCode($gameId, $prdCode);
			$objSlot = $modelSlotgame->getById($gameId, $prdCode, $slotId);
			$diffDt = diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_call) ;
			$sess = $this->modelSess->getByUid($objMember->mb_uid, false);

            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig))
				$iCreated = 0;
			else if($objConfig->game_bet_permit != PERMIT_OK){
				$iCreated = 4;									//Preparing
			}
			// else if($_ENV['app.type'] == APP_TYPE_3 && !$this->modelMember->isPermitMember($objMember, $gameId))
			// 	$iCreated = 3;									//Stop
			else if(is_null($objSlot) || is_null($objPrd)){
				$iCreated = 6;
			} 
			// else if($_ENV['app.type'] == APP_TYPE_3 && $objSlot->maintain == STATE_ACTIVE){
			// 	$iCreated = 7;
			// } 
			else if($diffDt < DELAY_GAME)
				$iCreated = 8;
			else if(!is_null($sess))
				$iCreated = 9;
			else if($objMember->mb_rave_id == 0){
				//Create Player
				$arrResult = $this->libApiRave->createUser($objMember->mb_fid, $objMember->mb_nickname, $objMember->mb_uid);
				if($arrResult['status'] == 1){
					$objMember->mb_rave_id = $arrResult['userId'];
                    $objMember->mb_rave_uid = $arrResult['username'];
                    $this->modelMember->updateRaveInfo($objMember);
                    $iCreated = 1;

                    writeLog($logHead.$objMember->mb_uid."-CreateUser Sucess !!");
				} else {
                    $iCreated = 2;								//Fail in Creation of user
				}
			} else {
				$iCreated = 1;
			}

			if($iCreated == 0){
				print "<script language=javascript> alert('".lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정생성중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('".lang("common.prepare")."'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('".lang("common.user_duplicated").lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 6){
				print "<script language=javascript> alert('존재하지 않는 게임입니다.'); self.close(); </script>";
			} else if($iCreated == 7){
				print "<script language=javascript> alert('점검중입니다.'); self.close(); </script>";
			} else if($iCreated == 8){
				print "<script language=javascript> alert('".langTo($this->session->lang, "game_delay", DELAY_GAME-$diffDt)."'); self.close(); </script>";
			} else if($iCreated == 9){
				print "<script language=javascript> alert('앱이 실행중이므로 게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 1){
				writeLog($logHead.$objMember->mb_uid." Game=>".$objSlot->prd_code.":".$objSlot->name_ko ); 

				$iResult = $this->alltoGame($objMember, $gameId);
				if($iResult == 1){
                    $arrResult = $this->libApiRave->createToken($objMember->mb_rave_uid, $objSlot->provider);
                    if($arrResult['status'] == 1){
                        writeLog($logHead.$objMember->mb_uid." Token=".$arrResult['token']);
						$arrResult = $this->libApiRave->gameUrl($arrResult['token'], $objSlot->provider, $objSlot->game_code);

						if($arrResult['status'] == 1){
							writeLog($logHead.$objMember->mb_uid."-Login Sucess !!");
							writeLog($logHead.$arrResult['url']);
							$this->modelMember->updateBetTm($objMember);
							$this->response->redirect($arrResult['url']);
						} else {
							print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_RESPONSE.")'); self.close(); </script>";
						}
					} else {
						print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_RESPONSE.")'); self.close(); </script>";
					}
				} else { //Fail in Transfering of money
					print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_TRANSFER.")'); self.close(); </script>";
				}
				 
			}

		}
	}

	public function xslott(){
		$this->setLanguage();
		if(!is_login())
		{
			print "<script> alert('".lang("common.session_expired")."'); self.close(); </script>";

        } else {
			$this->sess_action();                
			$modelSlotgame = new SlotGame_Model();

			$gameId = GAME_SLOT_TREEM;
			$logHead = "<TSLOT>";
			
			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //Slot 3

			$prdCode = trim($this->request->getVar('prd'));
			$slotId = trim($this->request->getVar('game'));
			$objPrd = $this->modelSlotprd->getByCode($gameId, $prdCode);
			$objSlot = $modelSlotgame->getById($gameId, $prdCode, $slotId);
			$diffDt = diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_call) ;
			$sess = $this->modelSess->getByUid($objMember->mb_uid, false);

            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig))
				$iCreated = 0;
			else if($objConfig->game_bet_permit != PERMIT_OK){
				$iCreated = 4;									//Preparing
			}
			// else if($_ENV['app.type'] == APP_TYPE_3 && !$this->modelMember->isPermitMember($objMember, $gameId))
			// 	$iCreated = 3;									//Stop
			else if(is_null($objSlot) || is_null($objPrd)){
				$iCreated = 6;
			} 
			// else if($_ENV['app.type'] == APP_TYPE_3 && $objSlot->maintain == STATE_ACTIVE){
			// 	$iCreated = 7;
			// } 
			else if($diffDt < DELAY_GAME)
				$iCreated = 8;
			else if(!is_null($sess))
				$iCreated = 9;
			else if(strlen($objMember->mb_treem_uid) == 0){
				//Create Player
				$arrResult = $this->libApiTreem->createUser($objMember->mb_fid, $objMember->mb_uid);
                
                if($arrResult['status'] == 1){
                    $objMember->mb_treem_uid = $arrResult['username'];
                    $this->modelMember->updateTreemInfo($objMember);
                    $iCreated = 1;

                    writeLog($logHead.$objMember->mb_uid."-CreateUser Sucess !!");
                } else {
                    $iCreated = 2;								//Fail in Creation of user
                }
			} else {
				$iCreated = 1;
			}

			if($iCreated == 0){
				print "<script language=javascript> alert('".lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정생성중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('".lang("common.prepare")."'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('".lang("common.user_duplicated").lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 6){
				print "<script language=javascript> alert('존재하지 않는 게임입니다.'); self.close(); </script>";
			} else if($iCreated == 7){
				print "<script language=javascript> alert('점검중입니다.'); self.close(); </script>";
			} else if($iCreated == 8){
				print "<script language=javascript> alert('".langTo($this->session->lang, "game_delay", DELAY_GAME-$diffDt)."'); self.close(); </script>";
			} else if($iCreated == 9){
				print "<script language=javascript> alert('앱이 실행중이므로 게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 1){
				writeLog($logHead.$objMember->mb_uid." Game=>".$objSlot->prd_code.":".$objSlot->name_ko ); 

				$iResult = $this->alltoGame($objMember, $gameId);
				if($iResult == 1){
                    $arrResult = $this->libApiTreem->gameUrl($objMember->mb_treem_uid, $objMember->mb_uid, $objSlot->provider, $objSlot->game_code);
					if($arrResult['status'] == 1){
						writeLog($logHead.$arrResult['link']);
						$this->modelMember->updateBetTm($objMember);
						$this->response->redirect($arrResult['link']);
					} else {
						print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_RESPONSE.")'); self.close(); </script>";
					}
				} else { //Fail in Transfering of money
					print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_TRANSFER.")'); self.close(); </script>";
				}
				 
			}

		}
	}

	public function xslots(){
		$this->setLanguage();
		if(!is_login())
		{
			print "<script> alert('".lang("common.session_expired")."'); self.close(); </script>";

        } else {
			$this->sess_action();                
			$modelSlotgame = new SlotGame_Model();

			$gameId = GAME_SLOT_SIGMA;
			$logHead = "<SSLOT>";
			
			$user_id = $this->session->user_id;
			$sessId = $this->session->session_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //Slot 3

			$prdCode = trim($this->request->getVar('prd'));
			$slotId = trim($this->request->getVar('game'));
			$ip = trim($this->request->getVar('ip'));
			if(strlen($ip) < 1)
				$ip = $this->request->getIPAddress();

			$objPrd = $this->modelSlotprd->getByCode($gameId, $prdCode);
			$objSlot = $modelSlotgame->getById($gameId, $prdCode, $slotId);
			$diffDt = diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_call) ;
			$sess = $this->modelSess->getByUid($objMember->mb_uid, false);

            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig))
				$iCreated = 0;
			else if($objConfig->game_bet_permit != PERMIT_OK){
				$iCreated = 4;									//Preparing
			}
			// else if($_ENV['app.type'] == APP_TYPE_3 && !$this->modelMember->isPermitMember($objMember, $gameId))
			// 	$iCreated = 3;									//Stop
			else if(is_null($objSlot) || is_null($objPrd)){
				$iCreated = 6;
			} 
			// else if($_ENV['app.type'] == APP_TYPE_3 && $objSlot->maintain == STATE_ACTIVE){
			// 	$iCreated = 7;
			// } 
			else if($diffDt < DELAY_GAME)
				$iCreated = 8;
			else if(!is_null($sess))
				$iCreated = 9;
			else if(strlen($objMember->mb_sigma_uid) == 0){
				//Create Player
				$arrResult = $this->libApiSigma->createUser($objMember->mb_fid, $objMember->mb_uid);
                
                if($arrResult['status'] == 1){
                    $objMember->mb_sigma_uid = $arrResult['username'];
                    $this->modelMember->updateSigmaInfo($objMember);
                    $iCreated = 1;

                    writeLog($logHead.$objMember->mb_uid."-CreateUser Sucess !!");
                } else {
                    $iCreated = 2;								//Fail in Creation of user
                }
			} else {
				$iCreated = 1;
			}

			if($iCreated == 0){
				print "<script language=javascript> alert('".lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정생성중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('".lang("common.prepare")."'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('".lang("common.user_duplicated").lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 6){
				print "<script language=javascript> alert('존재하지 않는 게임입니다.'); self.close(); </script>";
			} else if($iCreated == 7){
				print "<script language=javascript> alert('점검중입니다.'); self.close(); </script>";
			} else if($iCreated == 8){
				print "<script language=javascript> alert('".langTo($this->session->lang, "game_delay", DELAY_GAME-$diffDt)."'); self.close(); </script>";
			} else if($iCreated == 9){
				print "<script language=javascript> alert('앱이 실행중이므로 게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 1){
				writeLog($logHead.$objMember->mb_uid." Game=>".$objSlot->prd_code.":".$objSlot->name_ko ); 

				$iResult = $this->alltoGame($objMember, $gameId);
				if($iResult == 1){
                    $arrResult = $this->libApiSigma->gameUrl($objMember->mb_sigma_uid, $objMember->mb_uid, $objSlot->provider, $objSlot->game_code, $ip, $sessId);
					if($arrResult['status'] == 1){
						writeLog($logHead.$arrResult['url']);
						$this->modelMember->updateBetTm($objMember);
						$this->response->redirect($arrResult['url']);
					} else {
						print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_RESPONSE.")'); self.close(); </script>";
					}
				} else { //Fail in Transfering of money
					print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_TRANSFER.")'); self.close(); </script>";
				}
			}
		}
	}

	public function fslotlist()
	{
		$this->setLanguage();
		$prdCode = trim($this->request->getVar('prd'));
						
		if(!is_login(true))
		{
			print "<script> alert('".lang("common.session_expired")."'); self.close(); </script>";

        } else if($_ENV['app.type'] == APP_TYPE_1){
			$this->response->redirect(site_furl('/xslot_list?prd='.$prdCode));	
		}  else {
			$this->sess_action();                
			$gameId = GAME_SLOT_GSPLAY;
			if($_ENV['app.fslot'] == APP_FSLOT_GOLD)
				$gameId = GAME_SLOT_GOLD;

			$modelSlotgame = new SlotGame_Model();
            $objPrd = $this->modelSlotprd->getByCode($gameId, $prdCode);

			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //Slot 1
			$headInfo = $this->getSiteConf();
			writeLog("<FSLOT PRD> Code:".$objPrd->code." Game:".$gameId);

            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig) || is_null($objPrd))
				$iCreated = 0;
			// else if($objConfig->game_bet_permit != PERMIT_OK){
			// 	$iCreated = 4;									//Preparing
			// }
			else if($headInfo['slot_deny'])
                $iCreated = 3;									//Stop
			else if(!$this->modelMember->isPermitMember($objMember, $gameId))
				$iCreated = 3;									//Stop
			else
				$iCreated = 1;

			if($iCreated == 0){
				print "<script language=javascript> alert('".lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정생성중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('".lang("common.prepare")."'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('".lang("common.user_duplicated").lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 1){

				$games = $modelSlotgame->gets($gameId, $objPrd->code);

				writeLog("<FSLOT PRD> Code:".$objPrd->code." Count:".count($games));

                echo view('home/slotlist', array("prd" => $objPrd->code, "games" => $games));

			}
        }

    }
}