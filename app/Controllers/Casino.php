<?php namespace App\Controllers;

class Casino extends BaseController
{
    public function index()
    {
        $this->response->redirect(site_furl('/'));	
    }

    public function evl()
	{
		$this->setLanguage();
						
		if(!is_login())
		{
			print "<script> alert('".lang("common.session_expired")."'); self.close(); </script>";

        } else {
			$this->sess_action();                
            $logHead = "<CAS_EVOL>";
			$gameId = GAME_CASINO_EVOL;
			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //Evol config
			$headInfo = $this->getSiteConf();
			$objCas = $this->modelCasprd->getById($gameId, 0);
			$diffDt = diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_call) ;
			$sessAuto = null;
			if(array_key_exists('app.transEg', $_ENV) && $_ENV['app.transEg'] == 1){
				$sessAuto = $this->modelSess->getByUid($objMember->mb_uid, false);
			}
			writeLog($logHead.$objMember->mb_uid." Call Play");
            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig))
				$iCreated = 0;
			else if($objConfig->game_bet_permit != PERMIT_OK)
				$iCreated = 4;									//Prepare
            else if($headInfo['evol_deny'])
                $iCreated = 3;
			else if(is_null($objCas))
				$iCreated = 7;									//Error of game
			else if($objCas->maintain == STATE_ACTIVE || $objCas->hidden == STATE_ACTIVE)
				$iCreated = 7;										
			else if(!$this->modelMember->isPermitMember($objMember, GAME_CASINO_EVOL))   //Stop
				$iCreated = 3;									
			else if($diffDt < DELAY_GAME)
				$iCreated = 8;	
			else if(!is_null($sessAuto))
				$iCreated = 9;	
			else if($objMember->mb_live_id == 0){
				//Creation of Player
				$createId = createGameId(substr($_ENV['app.gm_prefix'], 0, 2)."_".$objMember->mb_fid);//."_".$objMember->mb_uid
				$arrResult = $this->libApiCas->createUser($createId, $objMember->mb_nickname);
                
                if($arrResult['status'] == 1){
                    $objMember->mb_live_id = $arrResult['user_id'];
                    $objMember->mb_live_uid = $createId;
                    $this->modelMember->updateLiveInfo($objMember);
                    $iCreated = 1;

                    writeLog($logHead.$objMember->mb_uid."-CreateUser Sucess !!");
                } else {
                    if(array_key_exists('error', $arrResult) && $arrResult['error'] == DOUBLE_USER){
                        usleep(500000);
						$arrResult = $this->libApiCas->getUserInfo($createId);
						writeLog($logHead.$objMember->mb_uid."-Double UserInfo Status=".$arrResult['status']);
                        if($arrResult['status'] == 1){
							$objMember->mb_live_id = $arrResult['user_id'];
							$objMember->mb_live_uid = $arrResult['site_id'];
							$objMember->mb_live_money = $arrResult['balance'];
							$this->modelMember->updateLiveInfo($objMember);
							$iCreated = 1;
						} else $iCreated = 5;								//Duplicate
					} else {
						$iCreated = 2;								//Fail in Creation of member
						if(array_key_exists('error', $arrResult))
		                    writeLog($logHead.$objMember->mb_uid."-CreateUser Error=".$arrResult['error']); 
					}
                }
			} else{
				$iCreated = 1;
			}

			if($iCreated == 0){
				print "<script language=javascript> alert('".lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정생성중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('".lang("common.game_stop")."'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('".lang("common.prepare")."'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('".lang("common.user_duplicated").lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 7){
				print "<script language=javascript> alert('".lang("common.inspection")."'); self.close(); </script>";
			} else if($iCreated == 8){
				print "<script language=javascript> alert('".langTo($this->session->lang, "game_delay", DELAY_GAME-$diffDt)."'); self.close(); </script>";
			} else if($iCreated == 9){
				print "<script language=javascript> alert('앱이 실행중이므로 게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 1){
                if(array_key_exists('app.transEg', $_ENV) && $_ENV['app.transEg'] == 1)
					$iResult = $this->alltoGame($objMember, GAME_CASINO_EVOL);
				else $iResult = 1;

				if($iResult == 1){
                    $arrResult = $this->libApiCas->auth($objMember->mb_live_uid, $objMember->mb_nickname, $objMember->mb_live_money);
                    if($arrResult['status'] == 1){
                        writeLog($logHead.$objMember->mb_uid."-Login Sucess !!");
                        writeLog($logHead.$arrResult['launch_url']);
						$this->modelMember->updateBetTm($objMember);
						// echo view('slot/game', array("game" => GAME_CASINO_EVOL, "launch_url" => $arrResult['launch_url']));	
                        $this->response->redirect($arrResult['launch_url']);
                    } else {
                        if(array_key_exists('error', $arrResult) && $arrResult['error'] == INVALID_USER){
                            print "<script language=javascript> alert('".lang("common.user_exist").lang("common.administrator_ask")."'); self.close(); </script>";
                        }
                        else {
                            print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_RESPONSE.")'); self.close(); </script>";
                        } 
                    }
                } else { //Fail in transfering of money
					print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_TRANSFER.")'); self.close(); </script>";
				}
			}
			
        }

    }

	public function cas_list() {
		$result = new \StdClass;
		if(!is_login())
		{
            $result->status = STATUS_LOGOUT;
		} else {
			$gameId = GAME_CASINO_KGON;
			if($_ENV['app.casino'] == APP_CASINO_STAR){
				$gameId = GAME_CASINO_STAR;
			} else if($_ENV['app.casino'] == APP_CASINO_RAVE){
				$gameId = GAME_CASINO_RAVE;
			} else if($_ENV['app.casino'] == APP_CASINO_TREEM){
				$gameId = GAME_CASINO_TREEM;
			} else if($_ENV['app.casino'] == APP_CASINO_SIGMA){
				$gameId = GAME_CASINO_SIGMA;
			} 
			$rows = $this->modelCasprd->gets($gameId);

			$games = [];
			foreach($rows as $row){
				$prd = new \stdClass();
				$prd->name = $row->name_ko;
				$prd->prd_id = $row->cas_id;
				$prd->maintain = $row->maintain;
				array_push($games, $prd);
			}
			$data['game_url'] = "/cas";
			$data['games'] = $games;

			$result->data = $data;
			$result->status = STATUS_SUCCESS;
		}
		echo json_encode($result);
	}

    public function cas()
	{
		$this->setLanguage();
		$prdId = trim($this->request->getVar('prd'));
		$ip = trim($this->request->getVar('ip'));
						
		if(!is_login())
		{
			print "<script> alert('".lang("common.session_expired")."'); self.close(); </script>";

        } else if($_ENV['app.casino'] == APP_CASINO_STAR){
			// $this->response->redirect(site_furl('/cas_h?prd='.$prdId));	
			$this->cas_h();
		} else if($_ENV['app.casino'] == APP_CASINO_RAVE){
			// $this->response->redirect(site_furl('/cas_r?prd='.$prdId));	
			$this->cas_r();
		} else if($_ENV['app.casino'] == APP_CASINO_TREEM){
			// $this->response->redirect(site_furl('/cas_t?prd='.$prdId));	
			$this->cas_t();
		} else if($_ENV['app.casino'] == APP_CASINO_SIGMA){
			// $this->response->redirect(site_furl("/cas_s?prd=.$prdId&ip=$ip"));
			$this->cas_s();
		} else {
			$this->sess_action();                
			$gameId = GAME_CASINO_KGON;
            $logHead = "<CAS_KGON>";

			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //Casino config
			$headInfo = $this->getSiteConf();
			$objCas = $this->modelCasprd->getById($gameId, $prdId);
			$diffDt = diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_call) ;
            $sessAuto = null;
			if(array_key_exists('app.transEg', $_ENV) && $_ENV['app.transEg'] == 1){
				$sessAuto = $this->modelSess->getByUid($objMember->mb_uid, false);
			}
			$sess = $this->modelSess->getBySess($this->session->session_id);

            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig) || is_null($sess))
				$iCreated = 0;
            else if(is_null($objCas))
				$iCreated = 6;									//Error of game
            else if(intval($sess->sess_spec) != 2 && ($objCas->maintain == STATE_ACTIVE || $objCas->hidden == STATE_ACTIVE))
				$iCreated = 7;
			else if($objConfig->game_bet_permit != PERMIT_OK)
				$iCreated = 4;									//Preparing
            else if($headInfo['cas_deny'])
                $iCreated = 3;									//Stop
			else if(!$this->modelMember->isPermitMember($objMember, $gameId))
				$iCreated = 3;									//Stop
			else if($diffDt < DELAY_GAME)
				$iCreated = 8;	
			else if(!is_null($sessAuto))
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
                    if(array_key_exists('code', $arrResult) && $arrResult['code'] == -500)
                        $iCreated = 5;								//Duplicated User
                    else $iCreated = 2;								//Fail in Creation of user
                    
                    if(array_key_exists('code', $arrResult))
                        writeLog($logHead.$objMember->mb_uid."-CreateUser Error code=".$arrResult['code']." Msg=".$arrResult['msg']); 
                }
			} else{
				$iCreated = 1;
			}

			if($iCreated == 0){
				print "<script language=javascript> alert('".lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정창조중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('".lang("common.game_stop")."'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('".lang("common.prepare")."'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('".lang("common.user_duplicated").lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 6){
				print "<script language=javascript> alert('게임을 정확히 선택해주세요.'); self.close(); </script>";
			} else if($iCreated == 7){
				print "<script language=javascript> alert('".lang("common.inspection")."'); self.close(); </script>";
			} else if($iCreated == 8){
				print "<script language=javascript> alert('".langTo($this->session->lang, "game_delay", DELAY_GAME-$diffDt)."'); self.close(); </script>";
			} else if($iCreated == 9){
				print "<script language=javascript> alert('앱이 실행중이므로 게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 1){
				if(array_key_exists('app.transEg', $_ENV) && $_ENV['app.transEg'] == 1)
					$iResult = $this->alltoGame($objMember, $gameId);
				else $iResult = 1;

                if($iResult != 1){
                    print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_TRANSFER.")'); self.close(); </script>";
                } else {
                    $arrResult = $this->libApiKgon->auth($objMember->mb_kgon_uid, $objMember->mb_nickname, $objMember->mb_uid, $objCas->key, $objCas->lobby);
                    if($arrResult['status'] == 1){
                        writeLog($logHead.$objMember->mb_uid."-Login Sucess !!");
                        writeLog($logHead.$arrResult['url']);
						$this->modelMember->updateBetTm($objMember);
						// echo view('slot/game', array("game" => $gameId, "launch_url" => $arrResult['url']));	
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
                }
				
			}
			
        }
    }

    public function casino()
	{
		$result = new \StdClass;
		$prdId = trim($this->request->getVar('prd'));
						
		if(!is_login())
		{
			$result->status = STATUS_LOGOUT;
        } else {
			$gameId = GAME_CASINO_KGON;
            $logHead = "<CAS_KGON>";

			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //Casino config
			$headInfo = $this->getSiteConf();
			$objCas = $this->modelCasprd->getById($gameId, $prdId);
			$diffDt = diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_call) ;
            $sessAuto = null;
			if(array_key_exists('app.transEg', $_ENV) && $_ENV['app.transEg'] == 1){
				$sessAuto = $this->modelSess->getByUid($objMember->mb_uid, false);
			}

            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig))
				$iCreated = 0;
            else if(is_null($objCas))
				$iCreated = 6;									//Error of game
            else if($objCas->maintain == STATE_ACTIVE || $objCas->hidden == STATE_ACTIVE)
				$iCreated = 7;
			else if($objConfig->game_bet_permit != PERMIT_OK)
				$iCreated = 4;									//Preparing
            else if($headInfo['cas_deny'])
                $iCreated = 3;									//Stop
			else if(!$this->modelMember->isPermitMember($objMember, $gameId))
				$iCreated = 3;									//Stop
			else if($diffDt < DELAY_GAME)
				$iCreated = 8;	
			else if(!is_null($sessAuto))
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
                    if(array_key_exists('code', $arrResult) && $arrResult['code'] == -500)
                        $iCreated = 5;								//Duplicated User
                    else $iCreated = 2;								//Fail in Creation of user
                    
                    if(array_key_exists('code', $arrResult))
                        writeLog($logHead.$objMember->mb_uid."-CreateUser Error code=".$arrResult['code']." Msg".$arrResult['msg']); 
                }
			} else{
				$iCreated = 1;
			}

			if($iCreated == 0){
				$result->status = STATUS_FAIL;
				$result->code = RESULT_FAIL;
			} else if($iCreated == 2){
				$result->status = STATUS_FAIL;
				$result->code = RESULT_FAIL;
			} else if($iCreated == 3){
				$result->status = STATUS_FAIL;
				$result->code = RESULT_STOP;
			} else if($iCreated == 4){
				$result->status = STATUS_FAIL;
				$result->code = RESULT_STOP;
			} else if($iCreated == 5){
				$result->status = STATUS_FAIL;
				$result->code = RESULT_EXIST_ID;
			} else if($iCreated == 6){
				$result->status = STATUS_FAIL;
				$result->code = RESULT_ERROR;
			} else if($iCreated == 7){
				$result->status = STATUS_FAIL;
				$result->code = RESULT_STOP;
			} else if($iCreated == 8){
				$result->status = STATUS_FAIL;
				$result->code = RESULT_STOP;
			} else if($iCreated == 9){
				$result->status = STATUS_FAIL;
				$result->code = RESULT_STOP;
			} else if($iCreated == 1){
				if(array_key_exists('app.transEg', $_ENV) && $_ENV['app.transEg'] == 1)
					$iResult = $this->alltoGame($objMember, $gameId);
				else $iResult = 1;
                if($iResult != 1){
					$result->status = STATUS_FAIL;
					$result->code = RESULT_ERROR;		
                } else {
                    $arrResult = $this->libApiKgon->auth($objMember->mb_kgon_uid, $objMember->mb_nickname, $objMember->mb_uid, $objCas->key, $objCas->lobby);
                    if($arrResult['status'] == 1){
                        writeLog($logHead.$objMember->mb_uid."-Login Sucess !!");
                        writeLog($logHead.$arrResult['url']);
						$this->modelMember->updateBetTm($objMember);
                        
						$result->status = STATUS_SUCCESS;
						$result->code = RESULT_OK;	
						$result->url = $arrResult['url'];	

                    } else {
                        if(array_key_exists('code', $arrResult)){
							$log = $logHead.$objMember->mb_uid."-Auth Error code=".$arrResult['code'];
							if(array_key_exists('msg', $arrResult))
								$log.=" msg=".$arrResult['msg'];
							writeLog($log); 
						}
						$result->status = STATUS_FAIL;
						$result->code = RESULT_ERROR;	
                    }
                }
				
			}
			
        }
		echo json_encode($result);	
    }

	public function cas_h()
	{
		$this->setLanguage();
						
		if(!is_login())
		{
			print "<script> alert('".lang("common.session_expired")."'); self.close(); </script>";

        } else {
			$this->sess_action();                
			$gameId = GAME_CASINO_STAR;
            $logHead = "<CAS_HI>";

			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //Casino config
			$headInfo = $this->getSiteConf();
			$prdId = trim($this->request->getVar('prd'));
			$objCas = $this->modelCasprd->getById($gameId, $prdId);
			if(is_null($objCas))
				$objCas = $this->modelCasprd->getById($gameId, 100);

			$diffDt = diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_call) ;
			$sessAuto = $this->modelSess->getByUid($objMember->mb_uid, false);
			$sess = $this->modelSess->getBySess($this->session->session_id);
            
            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig) || is_null($sess))
				$iCreated = 0;
            else if(is_null($objCas))
				$iCreated = 6;									//Error of game
            else if(intval($sess->sess_spec) != 2 && ($objCas->maintain == STATE_ACTIVE || $objCas->hidden == STATE_ACTIVE))
				$iCreated = 7;
			else if($objConfig->game_bet_permit != PERMIT_OK)
				$iCreated = 4;									//Preparing
            else if($headInfo['cas_deny'])
                $iCreated = 3;									//Stop
			else if(!$this->modelMember->isPermitMember($objMember, $gameId))
				$iCreated = 3;									//Stop
			else if($diffDt < DELAY_GAME)
				$iCreated = 8;	
			else if(!is_null($sessAuto))
				$iCreated = 9;	
			else if($objMember->mb_hslot_token == ""){
				//Create Player
				$arrResult = $this->libApiHslot->createUser($objMember->mb_uid, $objMember->mb_nickname);
                
                if($arrResult['status'] == 1){
                    $objMember->mb_hslot_token = $arrResult['data']['token'];
                    $objMember->mb_hslot_money = 0;
                    $this->modelMember->updateHslotInfo($objMember);
                    $iCreated = 1;

                    writeLog($logHead.$objMember->mb_uid."-CreateUser Sucess !!");
                } else {
                    $iCreated = 2;								//Fail in Creation of user
					if(array_key_exists('description', $arrResult))
						writeLog($logHead.$objMember->mb_uid."-CreateUser Error description=".$arrResult['description']); 
                }
			} else{
				$iCreated = 1;
			}

			if($iCreated == 0){
				print "<script language=javascript> alert('".lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정창조중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('".lang("common.game_stop")."'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('".lang("common.prepare")."'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('".lang("common.user_duplicated").lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 6){
				print "<script language=javascript> alert('게임을 정확히 선택해주세요.'); self.close(); </script>";
			} else if($iCreated == 7){
				print "<script language=javascript> alert('".lang("common.inspection")."'); self.close(); </script>";
			} else if($iCreated == 8){
				print "<script language=javascript> alert('".langTo($this->session->lang, "game_delay", DELAY_GAME-$diffDt)."'); self.close(); </script>";
			} else if($iCreated == 9){
				print "<script language=javascript> alert('앱이 실행중이므로 게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 1){
				if(array_key_exists('app.transEg', $_ENV) && $_ENV['app.transEg'] == 1)
					$iResult = $this->alltoGame($objMember, $gameId);
				else $iResult = 1;

                if($iResult != 1){
                    print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_TRANSFER.")'); self.close(); </script>";
                } else {
                    $arrResult = $this->libApiHslot->auth($objMember->mb_hslot_token, null, $objCas);
                    if($arrResult['status'] == 1){
                        writeLog($logHead.$objMember->mb_uid."-Login Sucess !!");
                        writeLog($logHead.$arrResult['url']);
						$this->modelMember->updateBetTm($objMember);
						// echo view('slot/game', array("game" => GAME_CASINO_KGON, "launch_url" => $arrResult['url']));	
                        $this->response->redirect($arrResult['url']);
                    } else {
                        if(array_key_exists('description', $arrResult)){
							$log = $logHead.$objMember->mb_uid."-Auth Error description=".$arrResult['description'];
							writeLog($log);  
						}
                        print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_RESPONSE.")'); self.close(); </script>";
                    }
                }
				
			}
			
        }

    }

	public function cas_r()
	{
		$this->setLanguage();
						
		if(!is_login())
		{
			print "<script> alert('".lang("common.session_expired")."'); self.close(); </script>";

        } else {
			$this->sess_action();                
			$gameId = GAME_CASINO_RAVE;
            $logHead = "<CAS_RAVE>";

			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //Casino config
			$headInfo = $this->getSiteConf();
			$prdId = trim($this->request->getVar('prd'));
			$objCas = $this->modelCasprd->getById($gameId, $prdId);
			$diffDt = diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_call) ;
			$sessAuto = $this->modelSess->getByUid($objMember->mb_uid, false);
			$sess = $this->modelSess->getBySess($this->session->session_id);
            
            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig) || is_null($sess))
				$iCreated = 0;
            else if(is_null($objCas))
				$iCreated = 6;									//Error of game
            else if(intval($sess->sess_spec) != 2 && ($objCas->maintain == STATE_ACTIVE || $objCas->hidden == STATE_ACTIVE))
				$iCreated = 7;
			else if($objConfig->game_bet_permit != PERMIT_OK)
				$iCreated = 4;									//Preparing
            else if($headInfo['cas_deny'])
                $iCreated = 3;									//Stop
			else if(!$this->modelMember->isPermitMember($objMember, $gameId))
				$iCreated = 3;									//Stop
			else if($diffDt < DELAY_GAME)
				$iCreated = 8;	
			else if(!is_null($sessAuto))
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
			} else{
				$iCreated = 1;
			}

			if($iCreated == 0){
				print "<script language=javascript> alert('".lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정창조중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('".lang("common.game_stop")."'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('".lang("common.prepare")."'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('".lang("common.user_duplicated").lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 6){
				print "<script language=javascript> alert('게임을 정확히 선택해주세요.'); self.close(); </script>";
			} else if($iCreated == 7){
				print "<script language=javascript> alert('".lang("common.inspection")."'); self.close(); </script>";
			} else if($iCreated == 8){
				print "<script language=javascript> alert('".langTo($this->session->lang, "game_delay", DELAY_GAME-$diffDt)."'); self.close(); </script>";
			} else if($iCreated == 9){
				print "<script language=javascript> alert('앱이 실행중이므로 게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 1){
				if(array_key_exists('app.transEg', $_ENV) && $_ENV['app.transEg'] == 1)
					$iResult = $this->alltoGame($objMember, $gameId);
				else $iResult = 1;

                if($iResult != 1){
                    print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_TRANSFER.")'); self.close(); </script>";
                } else {
                    $arrResult = $this->libApiRave->createToken($objMember->mb_rave_uid, $objCas->key);
                    if($arrResult['status'] == 1){
                        writeLog($logHead.$objMember->mb_uid." Token=".$arrResult['token']);
						$arrResult = $this->libApiRave->gameUrl($arrResult['token'], $objCas->key, $objCas->lobby);
						if($arrResult['status'] == 1){
							writeLog($logHead.$arrResult['url']);
							$this->modelMember->updateBetTm($objMember);
							$this->response->redirect($arrResult['url']);
						} else {
							print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_RESPONSE.")'); self.close(); </script>";
						}
                    } else {
                        print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_RESPONSE.")'); self.close(); </script>";
                    }
                }
				
			}
			
        }

    }

	public function cas_t()
	{
		$this->setLanguage();
						
		if(!is_login())
		{
			print "<script> alert('".lang("common.session_expired")."'); self.close(); </script>";

        } else {
			$this->sess_action();                
			$gameId = GAME_CASINO_TREEM;
            $logHead = "<CAS_TREEM>";

			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //Casino config
			$headInfo = $this->getSiteConf();
			$prdId = trim($this->request->getVar('prd'));
			$objCas = $this->modelCasprd->getById($gameId, $prdId);
			$diffDt = diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_call) ;
			$sessAuto = $this->modelSess->getByUid($objMember->mb_uid, false);
			$sess = $this->modelSess->getBySess($this->session->session_id);
            
            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig) || is_null($sess))
				$iCreated = 0;
            else if(is_null($objCas))
				$iCreated = 6;									//Error of game
            else if(intval($sess->sess_spec) != 2 && ($objCas->maintain == STATE_ACTIVE || $objCas->hidden == STATE_ACTIVE))
				$iCreated = 7;
			else if($objConfig->game_bet_permit != PERMIT_OK)
				$iCreated = 4;									//Preparing
            else if($headInfo['cas_deny'])
                $iCreated = 3;									//Stop
			else if(!$this->modelMember->isPermitMember($objMember, $gameId))
				$iCreated = 3;									//Stop
			else if($diffDt < DELAY_GAME)
				$iCreated = 8;	
			else if(!is_null($sessAuto))
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
			} else{
				$iCreated = 1;
			}

			if($iCreated == 0){
				print "<script language=javascript> alert('".lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정창조중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('".lang("common.game_stop")."'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('".lang("common.prepare")."'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('".lang("common.user_duplicated").lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 6){
				print "<script language=javascript> alert('게임을 정확히 선택해주세요.'); self.close(); </script>";
			} else if($iCreated == 7){
				print "<script language=javascript> alert('".lang("common.inspection")."'); self.close(); </script>";
			} else if($iCreated == 8){
				print "<script language=javascript> alert('".langTo($this->session->lang, "game_delay", DELAY_GAME-$diffDt)."'); self.close(); </script>";
			} else if($iCreated == 9){
				print "<script language=javascript> alert('앱이 실행중이므로 게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 1){
				if(array_key_exists('app.transEg', $_ENV) && $_ENV['app.transEg'] == 1)
					$iResult = $this->alltoGame($objMember, $gameId);
				else $iResult = 1;

                if($iResult != 1){
                    print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_TRANSFER.")'); self.close(); </script>";
                } else {
                    
					$arrResult = $this->libApiTreem->gameUrl($objMember->mb_treem_uid, $objMember->mb_uid, $objCas->key, $objCas->lobby, $objCas->range);
					if($arrResult['status'] == 1){
						writeLog($logHead.$arrResult['link']);
						$this->modelMember->updateBetTm($objMember);
						$this->response->redirect($arrResult['link']);
					} else {
						print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_RESPONSE.")'); self.close(); </script>";
					}
                }
				
			}
			
        }

    }

	public function cas_s()
	{
		$this->setLanguage();
						
		if(!is_login())
		{
			print "<script> alert('".lang("common.session_expired")."'); self.close(); </script>";

        } else {
			$this->sess_action();                
			$gameId = GAME_CASINO_SIGMA;
            $logHead = "<CAS_SIGMA>";
			$prdId = trim($this->request->getVar('prd'));
			$ip = trim($this->request->getVar('ip'));
			if(strlen($ip) < 1)
				$ip = $this->request->getIPAddress();

			$user_id = $this->session->user_id;
			$sessId = $this->session->session_id;

			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //Casino config
			$headInfo = $this->getSiteConf();
			$objCas = $this->modelCasprd->getById($gameId, $prdId);
			$diffDt = diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_call) ;
			$sessAuto = $this->modelSess->getByUid($objMember->mb_uid, false);
			$sess = $this->modelSess->getBySess($sessId);
            
            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig) || is_null($sess))
				$iCreated = 0;
            else if(is_null($objCas))
				$iCreated = 6;									//Error of game
            else if(intval($sess->sess_spec) != 2 && ($objCas->maintain == STATE_ACTIVE || $objCas->hidden == STATE_ACTIVE))
				$iCreated = 7;
			else if($objConfig->game_bet_permit != PERMIT_OK)
				$iCreated = 4;									//Preparing
            else if($headInfo['cas_deny'])
                $iCreated = 3;									//Stop
			else if(!$this->modelMember->isPermitMember($objMember, $gameId))
				$iCreated = 3;									//Stop
			else if($diffDt < DELAY_GAME)
				$iCreated = 8;	
			else if(!is_null($sessAuto))
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
			} else{
				$iCreated = 1;
			}

			if($iCreated == 0){
				print "<script language=javascript> alert('".lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정창조중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('".lang("common.game_stop")."'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('".lang("common.prepare")."'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('".lang("common.user_duplicated").lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 6){
				print "<script language=javascript> alert('게임을 정확히 선택해주세요.'); self.close(); </script>";
			} else if($iCreated == 7){
				print "<script language=javascript> alert('".lang("common.inspection")."'); self.close(); </script>";
			} else if($iCreated == 8){
				print "<script language=javascript> alert('".langTo($this->session->lang, "game_delay", DELAY_GAME-$diffDt)."'); self.close(); </script>";
			} else if($iCreated == 9){
				print "<script language=javascript> alert('앱이 실행중이므로 게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 1){
				if(array_key_exists('app.transEg', $_ENV) && $_ENV['app.transEg'] == 1)
					$iResult = $this->alltoGame($objMember, $gameId);
				else $iResult = 1;

                if($iResult != 1){
                    print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_TRANSFER.")'); self.close(); </script>";
                } else {
                    
					$arrResult = $this->libApiSigma->gameUrl($objMember->mb_sigma_uid, $objMember->mb_uid, $objCas->key, $objCas->lobby, $ip, $sessId);
					if($arrResult['status'] == 1){
						writeLog($logHead.$arrResult['url']);
						$this->modelMember->updateBetTm($objMember);
						$this->response->redirect($arrResult['url']);
					} else {
						print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_RESPONSE.")'); self.close(); </script>";
					}
                }
			}
        }
    }

	public function holdem()
	{
		$this->setLanguage();
						
		if(!is_login())
		{
			print "<script> alert('".lang("common.session_expired")."'); self.close(); </script>";

        } else {
			$this->sess_action();                
			$gameId = GAME_HOLD_CMS;
            $logHead = "<HOLDEM>";

			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //Holdem Config
			$headInfo = $this->getSiteConf();
			
			$diffDt = diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_call) ;
            
            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig))
				$iCreated = 0;
			else if($objConfig->game_bet_permit != PERMIT_OK)
				$iCreated = 4;									//Preparing
            else if($headInfo['hold_deny'])
                $iCreated = 3;									//Stop
			else if(!$this->modelMember->isPermitMember($objMember, $gameId))
				$iCreated = 3;									//Stop
			else if($diffDt < DELAY_GAME)
				$iCreated = 8;	
			else if($objMember->mb_hold_uid == ""){
				//Create Player
                // $createId = createGameId(substr($_ENV['app.gm_prefix'], 0, 2)."_".$objMember->mb_fid);//."_".$objMember->mb_uid
                $createId = createGameId(strtoupper(substr($_ENV['app.gm_prefix'], 0, 2))."_".$objMember->mb_uid);//."_".$objMember->mb_uid
				$objOther = $this->modelMember->getByHoldId($createId, $objMember->mb_fid);
				if(!is_null($objOther))
					$createId .= "_".$objMember->mb_fid;

				$arrResult = $this->libApiHold->createUser($createId, $objMember->mb_nickname);
                
                if($arrResult['status'] == 1){
                    $objMember->mb_hold_uid = $createId;
                    $objMember->mb_hold_money = 0;
                    $this->modelMember->updateHoldInfo($objMember);
                    $iCreated = 1;

                    writeLog($logHead.$objMember->mb_uid."-CreateUser Sucess !!");
                } else {
                    $iCreated = 2;								//Fail in Creation of member
					if(array_key_exists('error', $arrResult))
						writeLog($logHead.$objMember->mb_uid."-CreateUser Error=".$arrResult['error']); 
                }
			} else{
				$iCreated = 1;
			}

			if($iCreated == 0){
				print "<script language=javascript> alert('".lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정창조중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('".lang("common.game_stop")."'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('".lang("common.prepare")."'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('".lang("common.user_duplicated").lang("common.administrator_ask")."'); self.close(); </script>";
			} else if($iCreated == 6){
				print "<script language=javascript> alert('게임을 정확히 선택해주세요.'); self.close(); </script>";
			} else if($iCreated == 7){
				print "<script language=javascript> alert('".lang("common.inspection")."'); self.close(); </script>";
			} else if($iCreated == 8){
				print "<script language=javascript> alert('".langTo($this->session->lang, "game_delay", DELAY_GAME-$diffDt)."'); self.close(); </script>";
			} else if($iCreated == 9){
				print "<script language=javascript> alert('앱이 실행중이므로 게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 1){
				$iResult = $this->alltoGame($objMember, $gameId);

                if($iResult != 1){
                    print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_TRANSFER.")'); self.close(); </script>";
                } else {
					$this->libApiHold->logout($objMember->mb_hold_uid);
					usleep(500000);

                    $arrResult = $this->libApiHold->getLink($objMember->mb_hold_uid);
                    if($arrResult['status'] == 1){
                        writeLog($logHead.$objMember->mb_uid."-Login Sucess !!");
                        writeLog($logHead.$arrResult['url']);
						$this->modelMember->updateBetTm($objMember);
						// echo view('slot/game', array("game" => GAME_CASINO_KGON, "launch_url" => $arrResult['url']));	
                        $this->response->redirect($arrResult['url']);
                    } else {
                        if(array_key_exists('error', $arrResult)){
							$log = $logHead.$objMember->mb_uid."-Auth Error=".$arrResult['error'];
							writeLog($log);  
						}
                        print "<script language=javascript> alert('".lang("common.game_fail")."(".PLAY_FAIL_RESPONSE.")'); self.close(); </script>";
                    }
                }
				
			}
			
        }

    }
}