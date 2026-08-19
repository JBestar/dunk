<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\ConfGame_Model;
use App\Models\MoneyHistory_Model;
use App\Models\PbBet_Model;
use App\Models\PbRound_Model;
use App\Models\CsBet_Model;
use App\Models\EbalBet_Model;

class PbApi extends BaseController {

	/**

	 */
	public function index()
	{
				
		if(is_login())
		{

		}
		else {
			$this->response->redirect( $_ENV['app.furl'].'/pages/login');
		}	
		
	}

	//배팅결과를 Ajax로 전송
	public function result(){ 
		
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login()) {
			$roundModel = new PbRound_Model();
			$arrResult = $roundModel->search($arrReqData);

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

	
	//배팅결과를 검색개수 Ajax로 전송
	public function resultcnt(){ 
			
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login()) {
			$roundModel = new PbRound_Model();
			$objCount = $roundModel->searchCount($arrReqData);
			
			$objResult = new \StdClass;
			$objResult->data = $objCount;		
			$objResult->status = "success";
		
			echo json_encode($objResult);
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 

	}

	//배팅리력결과를 Ajax로 전송
	public function betlist(){ 
		
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login()) {
			$bResult = true;
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);

			$betModel = new PbBet_Model();
			
			if($objAdmin->mb_level >= LEVEL_ADMIN && strlen(trim($arrReqData['emp'])) > 0){
				$objAdmin = $this->modelMember->getInfo(trim($arrReqData['emp']));
			}
			$arrBetResults = $betModel->search($objAdmin, $arrReqData);
			
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
	public function betlistcnt(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login()) {
			//model
			$bResult = true;

			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);

			$betModel = new PbBet_Model();

			$arrBetAccount = null;
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				if(strlen(trim($arrReqData['emp'])) > 0){
					$objAdmin = $this->modelMember->getInfo(trim($arrReqData['emp']));
				}
			} 
			$arrBetAccount = $betModel->getBetAccount($objAdmin, $arrReqData);
			$objCount = $betModel->searchCount($objAdmin, $arrReqData);
			
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


	//실시간배팅결과 합을 Ajax로 전송
	public function betrealtime(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);

		if(is_login()) {
			$bResult = true;
			$confgameModel = new ConfGame_Model();
			$betModel = new PbBet_Model();

			$objConf = $confgameModel->getByIndex($arrReqData['game']);
			if($arrReqData['game'] == GAME_PBG_BALL){

				$arrRoundInfo = getPbRoundInfo();
				$arrBetSum = $betModel->getBetSumByMode($arrRoundInfo, $objConf);
			} else if($arrReqData['game'] == GAME_DHP_BALL){

				$arrRoundInfo = getPbRoundInfo(true);
				$arrBetSum = $betModel->getBetSumByMode($arrRoundInfo, $objConf);
			} 
			// else if($arrReqData['game'] == GAME_EVOL_BALL){

			// 	$modelRound = new PbRound_Model();
			// 	$reqData['game'] = $arrReqData['game'];
			// 	$reqData['page'] = 1;
			// 	$reqData['count'] = 1;
			// 	$arrRound = $modelRound->searchList($reqData);
			// 	if(count($arrRound)>0){
			// 		$objRound = $arrRound[0]; 
			// 		$arrRoundInfo['round_no'] = $objRound->round_num;
			// 		$arrRoundInfo['round_date'] = $objRound->round_date;
			// 		$arrRoundInfo['round_current'] = date("Y-m-d H:i:s", time());
			// 		$arrRoundInfo['round_start'] = $objRound->round_time;

			// 		$tmRoundStart = strtotime($objRound->round_time);
			// 		$tmRoundEnd = strtotime("+".floor($objRound->round_period)." seconds", $tmRoundStart);
			// 		$arrRoundInfo['round_bet_end'] = date("Y-m-d H:i:s", $tmRoundEnd);

			// 		$arrRoundInfo['round_end'] = date("Y-m-d H:i:s", $tmRoundEnd);
			// 	}
				
			// 	$arrBetSum = $betModel->getBetSumByMode($arrRoundInfo, $objConf);
			// } 
			else if($arrReqData['game'] == GAME_BOGLE_BALL){

				$betModel = new PbBet_Model();
				$arrRoundInfo = getBRoundInfo(ROUND_2MIN);
				$arrBetSum = $betModel->getBetSumByMode($arrRoundInfo, $objConf);
			} else if($arrReqData['game'] == GAME_BOGLE_LADDER){

				$betModel = new PbBet_Model();
				$arrRoundInfo = getBRoundInfo(ROUND_3MIN);
				$arrBetSum = $betModel->getBetSumByMode($arrRoundInfo, $objConf);
			} else if($arrReqData['game'] >= GAME_EOS5_BALL && $arrReqData['game'] <= GAME_COIN3_BALL){

				$betModel = new PbBet_Model();
				if($arrReqData['game'] == GAME_EOS5_BALL || $arrReqData['game'] == GAME_COIN5_BALL)
					$arrRoundInfo = getBRoundInfo(ROUND_5MIN);
				else $arrRoundInfo = getBRoundInfo(ROUND_3MIN);
				$arrBetSum = $betModel->getBetSumByMode($arrRoundInfo, $objConf);
			} else if($arrReqData['game'] == GAME_SPKN_BALL){

				$betModel = new PbBet_Model();
				$arrRoundInfo = getBRoundInfo(ROUND_5MIN, $arrReqData['game']);
				$arrBetSum = $betModel->getBetSumByMode($arrRoundInfo, $objConf);
			} else $bResult = false;
			
				
			if($bResult){
				
				$objData = new \StdClass;
				$objData->roundid = $arrRoundInfo['round_no'];
				$objData->betend = $arrRoundInfo['round_end'];
				$objData->betsums = $arrBetSum;
				$objData->config = $objConf;
				
				$objResult = new \StdClass;
				$objResult->data = $objData;		
				$objResult->status = "success";
			
				echo json_encode($objResult);
			} else {
				$arrResult['status'] = "false";
				echo json_encode($arrResult);	
			}
		}
		else{
		
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		} 
		
	}

	//회차 결과 등록
	public function registerround(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		
		if(is_login()) {
			$bResult = true;
			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);

			$roundModel = new PbRound_Model();
		
			$iResult = 0;
			if($objUser->mb_level >=  LEVEL_ADMIN){
				// if(isEnableRound($arrReqData, ROUND_5MIN)){
					$iResult = $roundModel->register($arrReqData);	
					//2: 이미 등록된 회차
				// } else $iResult = 3;				//파라메터 오류
			} 
			if($iResult == 1)
				$arrResult['status'] = "success";
			else {
				$arrResult['status'] = "fail";
				$arrResult['data'] = $iResult;
			}

			echo json_encode($arrResult);
		} else{
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		}

	}
	
	//회차 결과 수정
	public function modifyround(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		
		if(is_login()) {

			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			
			$roundModel = new PbRound_Model();
			
			$bResult = false;
			if($objUser->mb_level >=  LEVEL_ADMIN)
				$bResult = $roundModel->modify($arrReqData);

			$arrResult['status'] = $bResult?"success":"fail";
			echo json_encode($arrResult);
		} else{
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		}

	}

//회차 무효 처리
	public function betignore(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login()) {
			$bResult = true;

			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			//model
			// $moneyhistoryModel = new MoneyHistory_Model();
			$iChangeType = 0;
			$betModel = new PbBet_Model();
			if($arrReqData['game'] == GAME_PBG_BALL ){
				$iChangeType = MONEYCHANGE_WIN_PB;
			} else if($arrReqData['game'] == GAME_DHP_BALL){
				$iChangeType = MONEYCHANGE_WIN_DH;
			} else if($arrReqData['game'] == GAME_SPKN_BALL){
				$iChangeType = MONEYCHANGE_WIN_SK;
			} else if($arrReqData['game'] == GAME_BOGLE_BALL){
				$iChangeType = MONEYCHANGE_WIN_BB;
			} else if($arrReqData['game'] == GAME_BOGLE_LADDER){
				$iChangeType = MONEYCHANGE_WIN_BS;
			}  else if($arrReqData['game'] == GAME_EOS5_BALL){
				$iChangeType = MONEYCHANGE_WIN_EO5;
			} else if($arrReqData['game'] == GAME_EOS3_BALL){
				$iChangeType = MONEYCHANGE_WIN_EO3;
			} else if($arrReqData['game'] == GAME_COIN5_BALL){
				$iChangeType = MONEYCHANGE_WIN_CO5;
			} else if($arrReqData['game'] == GAME_COIN3_BALL){
				$iChangeType = MONEYCHANGE_WIN_CO3;
			} else $bResult = false;
			
			if($bResult){

				$bResult = false;
				if($objUser->mb_level >=  LEVEL_ADMIN){
					for($i = 0; $i < count($arrReqData['data']); $i++) {
						$objBet = $betModel->getByFid($arrReqData['data'][$i]);
						if(!is_null($objBet) && $objBet->bet_state != BET_STATE_TIE){
							$bResult = $betModel->ignore($objBet); 
							if($bResult){
								$objBetUser = $this->modelMember->getInfo($objBet->bet_mb_uid);
								if($objBet->bet_state == 2 || $objBet->bet_state == 1){		//미적중 혹은 대기중
									$dtMoney = $objBet->bet_money;
								}
								else if($objBet->bet_state == 3){	//적중
									$dtMoney = $objBet->bet_money - $objBet->bet_win_money;
								}
								$dtPoint = 0;
								$bResult = $this->modelMember->updateAssets($objBetUser, $dtMoney, $dtPoint, $iChangeType);
								// if($bResult)
								// 	$moneyhistoryModel->registerAccountBet($objBetUser, $objBet, $dtMoney, $iChangeType);

							} 	
						}
						
					}
					
				}

				$arrResult['status'] = $bResult?"success":"fail";
				echo json_encode($arrResult);
			} else {
				$arrResult['status'] = "false";
				echo json_encode($arrResult);	
			}
		} else{
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		}

	}	



	//회차 결과 처리
	public function betprocess(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login()) {
			$bResult = true;

			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			$iChangeType = 0;
			//model
			$roundModel = new PbRound_Model();
			$betModel = new PbBet_Model();

			if($arrReqData['game'] == GAME_PBG_BALL ){
				$iChangeType = MONEYCHANGE_WIN_PB;
			} else if($arrReqData['game'] == GAME_DHP_BALL){
				$iChangeType = MONEYCHANGE_WIN_DH;
			} else if($arrReqData['game'] == GAME_SPKN_BALL){
				$iChangeType = MONEYCHANGE_WIN_SK;
			}  else if($arrReqData['game'] == GAME_BOGLE_BALL){
				$iChangeType = MONEYCHANGE_WIN_BB;
			} else if($arrReqData['game'] == GAME_BOGLE_LADDER){
				$iChangeType = MONEYCHANGE_WIN_BS;
			}  else if($arrReqData['game'] == GAME_EOS5_BALL){
				$iChangeType = MONEYCHANGE_WIN_EO5;
			} else if($arrReqData['game'] == GAME_EOS3_BALL){
				$iChangeType = MONEYCHANGE_WIN_EO3;
			} else if($arrReqData['game'] == GAME_COIN5_BALL){
				$iChangeType = MONEYCHANGE_WIN_CO5;
			} else if($arrReqData['game'] == GAME_COIN3_BALL){
				$iChangeType = MONEYCHANGE_WIN_CO3;
			} else $bResult = false;
				
			if($bResult){
				$bResult = false;
				if($objUser->mb_level >=  LEVEL_ADMIN){
					for($i = 0; $i < count($arrReqData['data']); $i++) {
						$objBet = $betModel->getByFid($arrReqData['data'][$i]);
						if(is_null($objBet)) 
							continue;
						
						if($objBet->bet_state == 1){			//대기중이라면
							$strDate = getRoundDate($arrReqData['game'], $objBet->bet_time);
							$objRoundInfo = $roundModel->getByDate($arrReqData['game'], $strDate, $objBet->bet_round_no);
							$bResult = $betModel->updateBetRound($objRoundInfo, $objBet);
	
							if($bResult){
								$objBetUser = $this->modelMember->getInfo($objBet->bet_mb_uid);
								if($objBet->bet_win_money > 0){		//적중
									$dtMoney = $objBet->bet_win_money;
									$bResult = $this->modelMember->updateAssets($objBetUser, $dtMoney, 0, $iChangeType);
									
								}
							} 	
						} else if($objBet->bet_state == 4){			//무효라면 
							$strDate = getRoundDate($arrReqData['game'], $objBet->bet_time);
							$objRoundInfo = $roundModel->getByDate($arrReqData['game'], $strDate, $objBet->bet_round_no);
							$bResult = $betModel->updateBetRound($objRoundInfo, $objBet);

							if($bResult){
								$objBetUser = $this->modelMember->getInfo($objBet->bet_mb_uid);
								if($objBet->bet_win_money > 0){		//적중
									$dtMoney = $objBet->bet_win_money - $objBet->bet_money;
								} else {
									$dtMoney = 0 - $objBet->bet_money;								
								}
								$dtPont = 0;
								$bResult = $this->modelMember->updateAssets($objBetUser, $dtMoney, $dtPont, 0, $iChangeType);
								
							}
						}
					}
				}

				$arrResult['status'] = "success";
			} else {
				$arrResult['status'] = "false";
			}
			echo json_encode($arrResult);
		} else{
			$arrResult['status'] = "logout";

			echo json_encode($arrResult);	
		}
	}	

	public function csbetprocess(){ 
		$jsonData = $_REQUEST['json_'];
		$arrReqData = json_decode($jsonData, true);
		if(is_login()) {
			$bResult = false;
			// $moneyhistoryModel = new MoneyHistory_Model();
			$csbetModel = new EbalBet_Model();
			$csbetModel->setType($arrReqData['game']);
			$iChangeType = MONEYCHANGE_WIN_EBAL;
			if($arrReqData['game'] == GAME_AUTO_PRAG)
				$iChangeType = MONEYCHANGE_WIN_PBAL;

			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			if(is_null($objUser) || $objUser->mb_level <  LEVEL_ADMIN){
				$bResult = false;
			} else {
				$state = intval($arrReqData['state']);
				for($i = 0; $i < count($arrReqData['data']); $i++) {
					$objBet = $csbetModel->find($arrReqData['data'][$i]);
					if(is_null($objBet)) 
						continue;

					if($objBet->point_amount == $state)
						continue;

					if($objBet->company_amount == 0)
						continue;

					$winMoney = 0;
					if($state == BET_STATE_WIN){
						if($objBet->bet_choice == BET_BANKER){
							$winMoney = $objBet->bet_money * RATE_BANKER;
							$objBet->bet_result = $objBet->bet_choice; 
						}
						else if($objBet->bet_choice == BET_TIE){
							$winMoney = $objBet->bet_money * RATE_TIE;
							$objBet->bet_result = "";
						}
						else{
							$winMoney = $objBet->bet_money * RATE_PLAYER;
							$objBet->bet_result = $objBet->bet_choice; 
						}
					} else if($state == BET_STATE_TIE){
						$winMoney = $objBet->bet_money;
						$objBet->bet_result = BET_TIE;
					} else if($state == BET_STATE_LOSS){
						$winMoney = 0;
						$objBet->bet_result = $objBet->bet_choice == BET_BANKER ? BET_PLAYER : BET_BANKER; 
					} else continue;

					$dtMoney = 0;
					if($objBet->point_amount == 0){
						$dtMoney = $winMoney - $objBet->bet_money;
					} else if($objBet->point_amount == BET_STATE_LOSS){
						// $dtMoney = $objBet->bet_money + $winMoney - $objBet->bet_money;
						$dtMoney = $winMoney;
					} else if($objBet->point_amount == BET_STATE_WIN){
						// $dtMoney = $objBet->bet_money - $objBet->bet_win_money + $winMoney - $objBet->bet_money;
						$dtMoney = $winMoney - $objBet->bet_win_money;
					} else if($objBet->point_amount == BET_STATE_TIE){
						$dtMoney = $winMoney - $objBet->bet_money;
					} else continue;

					$objBetUser = $this->modelMember->getInfo($objBet->bet_mb_uid);
					if(is_null($objBetUser))
						continue;

					if($this->modelMember->updateAssets($objBetUser, $dtMoney, 0, $iChangeType)){
						
						$objBet->bet_win_money = $winMoney;
						$objBet->point_amount = $state;
						$objBet->employee_amount = STATE_ACTIVE;  //proceed state

						// $moneyhistoryModel->registerAccountCsBet($objBetUser, $objBet, $dtMoney, MONEYCHANGE_DENY_EBAL);
						
						if(!$csbetModel->updateBet($objBet))
							continue;
					}

				}

				$bResult = true;
			}
			if($bResult)
				$arrResult['status'] = "success";
			else 
				$arrResult['status'] = "fail";

			echo json_encode($arrResult);
		} else {
			$arrResult['status'] = "logout";
			echo json_encode($arrResult);	
		}
	}



}