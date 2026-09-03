<?php

include_once('models/SlotBet_Model.php');
include_once('models/CasinoBet_Model.php');

include_once('models/ConfSite_Model.php');
include_once('models/ConfGame_Model.php');
include_once('models/SlotPrd_Model.php');
include_once('models/CasinoPrd_Model.php');

include_once('models/Member_Model.php');
include_once('models/MoneyHistory_Model.php');
include_once('models/Reward_Model.php');
include_once('models/Transfer_Model.php');

class ServiceLogic
{
	// private $mSnoopy ;

	private $modelSlotBet;
	private $modelCasinoBet;

	private $modelMember;
	private $modelReward;
	private $modelTransfer;
	private $modelMoneyHistory;

	private $modelConfSite;
	private $modelConfGame;
	private $modelSlotPrd;
	
	public $fLog;
	
	function __construct($dbConn, $fLog){
		// $this->mSnoopy = new Snoopy();
		$this->fLog = $fLog;

		$this->modelCasinoBet = new CasinoBet_Model($dbConn);
		$this->modelSlotBet = new SlotBet_Model($dbConn);

		$this->modelConfSite = new ConfSite_Model($dbConn);
		$this->modelConfGame = new ConfGame_Model($dbConn);
		$this->modelSlotPrd = new SlotPrd_Model($dbConn);
		$this->modelCasinoPrd = new CasinoPrd_Model($dbConn);

		$this->modelMember = new Member_Model($dbConn);
		$this->modelMoneyHistory = new MoneyHistory_Model($dbConn);
		$this->modelReward = new Reward_Model($dbConn);
		$this->modelTransfer = new Transfer_Model($dbConn);
	}


	//Site config
	public function getSiteConf($confId)
	{		
		//게임배팅시간
		$objConfig = $this->modelConfSite->getById($confId);
		if(!is_null($objConfig) && $objConfig->conf_active > 0){
			return true;
		}
		return false;
		
	}
	
	//===========Theplus slot================
	
	public function curlSlotBets(){
		$gameId = GAME_SLOT_THEPLUS;
		$confId = CONF_API_THEPLUS;
		$logHead = "<SLOT> ";

		$objConf = $this->modelConfSite->getById($confId);
		
		if(is_null($objConf))
			return null;
		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3)	//0-host, 1-ag_code, 2-ag_token
			return null;

		$url = $arrInfo[0]."/system/api/GetBetWinLogByIndex";
		$arrIdx = getHistoryIdx($objConf->conf_idx);
		
		$arrPost['key'] = $arrInfo[1];
		$arrPost['secret'] = $arrInfo[2];
		$arrPost["pageSize"] = 500;
		$arrPost["objectID"] = $arrIdx['idx'] > 0 ? $arrIdx['idx'] + 1 : 0;
        
        $post = json_encode($arrPost);

		writeLog($this->fLog, $logHead."Request ID=".$arrPost["objectID"]);

		$header =  ['Content-Type: application/json',
            'Content-Length: ' . strlen($post),
            'Accept: */*'
		];

		return getCurl($url, $header, $post);
	}

	public function registerSlotBets($response){
		$gameId = GAME_SLOT_THEPLUS;
		$confId = CONF_API_THEPLUS;
		$logHead = "<SLOT> ";

		$arrPrd = $this->modelSlotPrd->getByCat($gameId);
		$objConf = $this->modelConfSite->getById($confId);
		
		if(is_null($response))
			return false;

		if(is_null($objConf))
			return false;

		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3 || count($arrPrd) < 1)	//0-host, 1-ag_code, 2-ag_token
			return false;

		$arrIdx = getHistoryIdx($objConf->conf_idx);
		
		$arrBet = null;
		$arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("resultCode", $arrResult)) {
			// writeLog($this->fLog, $logHead."resultCode-".$arrResult['resultCode']);
			if($arrResult['resultCode'] == 0){
                //"resultCode": "0",
                //"resultMessage": "OK", // Newly Created Id In AAS
                //"data": [],    
                $arrBet = $arrResult['data'];
            } else { 
                //"resultCode": "0",
            }
		} else {
        }

		if(is_null($arrBet)) {			
			return false;
		}

		$nBetCnt = count($arrBet);
		writeLog($this->fLog, $logHead."Bet Count-".count($arrBet));

		$lastIdx = -1;
		if(array_key_exists("lastObjectID", $arrResult) && $nBetCnt > 0)
			$lastIdx = $arrResult['lastObjectID'];

		$lastFid = 0;
		if($arrIdx['fid'] > FID_OFFSET){
			$lastFid = $arrIdx['fid'] - FID_OFFSET;
		} 
		writeLog($this->fLog, $logHead."Last Fid-".$lastFid);

		$objMember = null;
		$arrEmpPoint = array();
		$arrMemBlank = array();
		$arrMemBet = array();
		
		$arrLiveUids = [];
		foreach ($arrBet as $bet) {
			if(!in_array($bet['PlayerID'], $arrLiveUids))
				array_push($arrLiveUids, $bet['PlayerID']);
		}
		$arrMember = $this->modelMember->getMembersByLiveUids($arrLiveUids, $gameId);
		if(count($arrMember) > 0)
			writeLog($this->fLog, $logHead."***Member Count-".count($arrMember));
		foreach ($arrMember as $member) {
			$member->ratio = $this->modelMember->getEmployeeRatio($member, $gameId);
		}
		$bInsert = false;
		$rwLastFid = $this->modelReward->getLastFid($gameId);
		writeLog($this->fLog, $logHead."Reward LastFid=".$rwLastFid);
		if($rwLastFid > FID_OFFSET){
			$rwLastFid = $rwLastFid - FID_OFFSET;
		}

		foreach ($arrBet as $bet) {

			$objMember = findMemberByLiveId($arrMember, $bet['PlayerID'], $gameId);
			if(is_null($objMember))
				continue;

			$bet['game_id'] = $gameId;
			$betting = $this->modelSlotBet->getByXslot($gameId, $bet, $lastFid);	//베팅내역체크

			if($bet['Status'] !== "BET")			//청산
			{	
				if(!is_null($betting) &&  intval($betting['bet_win_money']) == $bet['Amount'])
					continue;
				
				$arrMemBet[$objMember->mb_fid] = strToLocal($bet['Date']);
				if(is_null($betting)){
					$objPrd = getPrd($arrPrd, $bet['ThirdParty']);
					$betId = $this->modelSlotBet->insertXslot($objMember, $bet);
					writeLog($this->fLog, $logHead."Insert ACCId=".$betId);
					if($betId > 0)
						$arrIdx['fid'] = $betId;
				} else {
					$betId = $betting['bet_fid'];
					$bResult = $this->modelSlotBet->updateXslot($betId, $bet);
					writeLog($this->fLog, $logHead."Update ACCId=".$betId."=>Result-".$bResult);
				}
			} else {								//베팅
				if(!is_null($betting) &&  intval($betting['bet_money']) == $bet['Amount'])
					continue;
				
				$bBlank = false;
				$nBlankPt = 0;

				$arrEmpRatio = calcEmpPoint($objMember->ratio, $bet['Amount'], strToLocal($bet['Date']));
				if($objMember->mb_blank_count > 0 && intval($objMember->mb_blank_current) >= intval($objMember->mb_blank_count)-1 ){
					$objMember->mb_blank_current = 0;
					$bBlank = true;
					$nBlankPt = calcCompPoint($objMember->ratio, $bet['Amount']);
					$arrMemBlank[$objMember->mb_fid] = $objMember->mb_blank_current;
					writeLog($this->fLog, $logHead."Blank Cross Id=".$objMember->mb_uid." Current=".$objMember->mb_blank_current." Comp=".$nBlankPt);
				} else {
					foreach($arrEmpRatio as $ratio){
						
						if(array_key_exists($ratio['mb_fid'], $arrEmpPoint ))
							$arrEmpPoint[$ratio['mb_fid']] += $ratio['point'];
						else 
							$arrEmpPoint[$ratio['mb_fid']] = $ratio['point'];	
					}
					if($objMember->mb_blank_count > 0){
						$objMember->mb_blank_current ++;
						$arrMemBlank[$objMember->mb_fid] = $objMember->mb_blank_current;
						writeLog($this->fLog, $logHead."Blank Id=".$objMember->mb_uid." Current=".$objMember->mb_blank_current);
					}
					
				}

				$arrMemBet[$objMember->mb_fid] = strToLocal($bet['Date']);
				$betId = 0;
				if(is_null($betting)){
					$objPrd = getPrd($arrPrd, $bet['ThirdParty']);
					$betId = $this->modelSlotBet->insertXslot($objMember, $bet, $bBlank, $nBlankPt);
					writeLog($this->fLog, $logHead."Insert BetId=".$betId);
					if($betId > 0)
						$arrIdx['fid'] = $betId;
				} else {
					$betId = $betting['bet_fid'];
					$bResult = $this->modelSlotBet->updateXslot($betId, $bet, $bBlank, $nBlankPt);
					writeLog($this->fLog, $logHead."Update BetId=".$betId."=>Result-".$bResult);

				}
					
				if($betId > 0){
					$this->modelReward->insert($gameId, $betId, $arrEmpRatio, $rwLastFid, $bBlank);
					$bInsert = true;
					writeLog($this->fLog, $logHead."Insert Reward Count=".count($arrEmpRatio));
				}
			}

			writeLog($this->fLog, $logHead."BET-".$bet['PlayerID']."=>".$bet['Amount']);
		}

		if($lastIdx > 0)
			$this->modelConfSite->updateLastIdx($objConf->conf_id, $lastIdx."#".$arrIdx['fid']);

		$this->modelMember->updateMemberBetTm($arrMemBet);
		$bResult = $this->modelMember->updateMemberBlank($arrMemBlank);
		// writeLog($this->fLog, $logHead."UpdateMemBlank-Count=".count($arrMemBlank)." Result=".$bResult);
		$bResult = $this->modelMember->addEmployeePoint($arrEmpPoint);
		// writeLog($this->fLog, $logHead."AddEmpPoint-Count=".count($arrEmpPoint)." Result=".$bResult);

		return $bInsert;
	}

	
	//===========Gsplay slot=================
	
	public function curlFslotBets(&$order) {
		$gameId = GAME_SLOT_GSPLAY;
		$confId = CONF_API_GSPLAY;
		$logHead = "<FSLOT> ";

		$arrPrd = $this->modelSlotPrd->getByCat($gameId);
		$objConf = $this->modelConfSite->getById($confId);
		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3 || count($arrPrd) < 1)	//0-host, 1-ag_code, 2-ag_token
			return null;

		$order ++ ;
		if($order > count($arrPrd) )
			$order = 1;
		$objPrd = $arrPrd[$order-1];			//Provider
		
		$url = $arrInfo[0]."/game/history_with_id";
		$arrIdx = getHistoryIdx($objConf->conf_idx);
		if($objPrd->history_id > 0)
			$arrIdx['idx'] = $objPrd->history_id;

		$arrPost['prd_id'] = $objPrd->code;			//provider code
		$arrPost['history_id'] = $arrIdx['idx'] > 0 ? $arrIdx['idx'] + 1 : 0;		//마지막 history_id
        $arrPost['offset'] = "0";
        $arrPost['limit'] = "100";
        $post = json_encode($arrPost);

		writeLog($this->fLog, $logHead."Request PRD=".$arrPost['prd_id']." ID=".$arrPost['history_id']);

		$header =  [
			'Content-Type: application/json',
            'Content-Length: ' . strlen($post),
            'Accept: */*',
            'ag-code: '.$arrInfo[1],
            'ag-token: '.$arrInfo[2]
		];

		return getCurl($url, $header, $post);
		
	}

	public function registerFslotBets($response, $order) {
		$gameId = GAME_SLOT_GSPLAY;
		$confId = CONF_API_GSPLAY;
		$logHead = "<FSLOT> ";

		$arrPrd = $this->modelSlotPrd->getByCat($gameId);
		$objConf = $this->modelConfSite->getById($confId);
		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3 || count($arrPrd) < 1)	//0-host, 1-ag_code, 2-ag_token
			return false;

		$objPrd = $arrPrd[$order-1];			//Prd얻기		
		$arrIdx = getHistoryIdx($objConf->conf_idx);
		
		$arrResult = json_decode($response, true);

		$arrBet = null;
		
		if(!is_null($arrResult) && array_key_exists("status", $arrResult)) {
			if($arrResult['status'] == 1){
                //"status": 1,
                //"total": 1, // Newly Created Id In AAS
                //"data": [],    
                $arrBet = $arrResult['data'];
            } else { 
                //"status": 0,
                //"error": "INVALID_ACCESS_TOKEN", DOUBLE_USER, INSUFFICIENT_FUNDS, INTERNAL_ERROR
            }
		} else {
            $arrResult['status'] = 0;
            
        }

		if(is_null($arrBet)) {			
			return false;
		}

		writeLog($this->fLog, $logHead."Bet Count-".count($arrBet));

		$lastIdx = 0;
		$lastFid = 0;
		if($arrIdx['fid'] > FID_OFFSET){
			$lastFid = $arrIdx['fid'] - FID_OFFSET;
		} 
		writeLog($this->fLog, $logHead."Last Fid-".$lastFid);

		$objMember = null;
		$arrEmpPoint = array();
		$arrMemBlank = array();
		$arrMemBet = array();
		
		$arrLiveUids = [];
		foreach ($arrBet as $bet) {
			if(!in_array($bet['gs_user_id'], $arrLiveUids))
				array_push($arrLiveUids, $bet['gs_user_id']);
		}

		$arrMember = $this->modelMember->getMembersByLiveUids($arrLiveUids, $gameId);
		if(count($arrMember) > 0)
			writeLog($this->fLog, $logHead."***Member Count-".count($arrMember));
		foreach ($arrMember as $member) {
			$member->ratio = $this->modelMember->getEmployeeRatio($member, $gameId);
		}
		$bInsert = false;
		$rwLastFid = $this->modelReward->getLastFid($gameId);
		writeLog($this->fLog, $logHead."Reward LastFid=".$rwLastFid);
		if($rwLastFid > FID_OFFSET){
			$rwLastFid = $rwLastFid - FID_OFFSET;
		}

		foreach ($arrBet as $bet) {

			$lastIdx = $bet['history_id'];
			
			$objMember = findMemberByLiveId($arrMember, $bet['gs_user_id'], $gameId);
			if(is_null($objMember))
				continue;
			
			writeLog($this->fLog, $logHead."BET-".$bet['history_id']."=>".$bet['bet_money']);

			// if($bet['bet_money'] == 0)
			// 	continue;

			$betting = $this->modelSlotBet->getByFslot($gameId, $bet, $lastFid);	//베팅내역체크
			if(!is_null($betting))
				continue;

			writeLog($this->fLog, $logHead."BET-FIND-".$bet['history_id']."=>".$bet['bet_money']);
			
			$bBlank = false;
			$nBlankPt = 0;

			$arrEmpRatio = calcEmpPoint($objMember->ratio, $bet['bet_money'], strToLocal($bet['created_at']));
			if($objMember->mb_blank_count > 0 && intval($objMember->mb_blank_current) >= intval($objMember->mb_blank_count)-1 ){
				$objMember->mb_blank_current = 0;
				$bBlank = true;
				$nBlankPt = calcCompPoint($objMember->ratio, $bet['bet_money']);
				$arrMemBlank[$objMember->mb_fid] = $objMember->mb_blank_current;
				writeLog($this->fLog, $logHead."Blank Cross Id=".$objMember->mb_uid." Current=".$objMember->mb_blank_current." Comp=".$nBlankPt);
			} else {
				foreach($arrEmpRatio as $ratio){
					
					if(array_key_exists($ratio['mb_fid'], $arrEmpPoint ))
						$arrEmpPoint[$ratio['mb_fid']] += $ratio['point'];
					else 
						$arrEmpPoint[$ratio['mb_fid']] = $ratio['point'];	
				}
				if($objMember->mb_blank_count > 0){
					$objMember->mb_blank_current ++;
					$arrMemBlank[$objMember->mb_fid] = $objMember->mb_blank_current;
					writeLog($this->fLog, $logHead."Blank Id=".$objMember->mb_uid." Current=".$objMember->mb_blank_current);
				}
			}
			$bet['game_id'] = $gameId;
			$bet['prd_id'] = $objPrd->code;
			$arrMemBet[$objMember->mb_fid] = strToLocal($bet['created_at']);

			$betId = $this->modelSlotBet->insertFslot($objMember, $bet, $bBlank, $nBlankPt);
			if($betId > 0){
				$this->modelReward->insert($gameId, $betId, $arrEmpRatio, $rwLastFid, $bBlank);
				$bInsert = true;
				$arrIdx['fid'] = $betId;
			}
			writeLog($this->fLog, $logHead."Insert BetId=".$betId);
			
		}

		if($lastIdx > 0){
			$this->modelSlotPrd->updateHistoryId($objPrd->cat, $objPrd->code, $lastIdx);
			$this->modelConfSite->updateLastIdx($objConf->conf_id, $arrIdx['idx']."#".$arrIdx['fid']);
		}

		$bResult = $this->modelMember->addEmployeePoint($arrEmpPoint);
		// writeLog($this->fLog, $logHead."AddEmpPoint-Count=".count($arrEmpPoint)." Result=".$bResult);
		$this->modelMember->updateMemberBetTm($arrMemBet);
		$bResult = $this->modelMember->updateMemberBlank($arrMemBlank);
		// writeLog($this->fLog, $logHead."UpdateMemBlank-Count=".count($arrMemBlank)." Result=".$bResult);
	
		return $bInsert;
	}

	//===========Gold slot================
		
	public function curlGslotBets(){
		$gameId = GAME_SLOT_GOLD;
		$confId = CONF_API_GOLD;
		$logHead = "<GSLOT> ";

		$objConf = $this->modelConfSite->getById($confId);
		
		if(is_null($objConf))
			return null;
		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3)	//0-host, 1-ag_code, 2-ag_token
			return null;

		$url = $arrInfo[0]."/get_date_log";
		$arrIdx = getHistoryDate($objConf->conf_idx);
		
        $arrPost['agent_code'] = trim($arrInfo[1]);
        $arrPost['agent_token'] = trim($arrInfo[2]);

		$strStart = "";
		$strEnd = "";

		if(strlen($arrIdx['idx']) > 0){
			$strStart = $arrIdx['idx'];
			$startAt = strtotime($arrIdx['idx']);
		} else {
			$startAt = time();
			$strStart = date("Y-m-d H:i:s", $startAt);     
		}
		$strEnd = date('Y-m-d H:i:s', strtotime("+1 day", $startAt));

		$arrPost['game_type'] = "slot";
		$arrPost['start'] = $strStart;
		$arrPost['end'] = $strEnd;
		$arrPost['page'] = 0;
		$arrPost['length'] = 1000;

        $post = json_encode($arrPost);

		writeLog($this->fLog, $logHead."Request Date=".$strStart."~".$strEnd);
		$this->modelConfSite->updateLastIdx($objConf->conf_id, $strStart."#".$arrIdx['fid']."#".$arrIdx['fid2']);

		$header =  ['Content-Type: application/json',
			'Content-Length: ' . strlen($post),
			'Accept: */*'
		];

		return getCurl($url, $header, $post);
	}

	public function registerGslotBets($response){
		$gameId = GAME_SLOT_GOLD;
		$confId = CONF_API_GOLD;
		$logHead = "<GSLOT> ";

		$arrPrd = $this->modelSlotPrd->getByCat($gameId);
		$objConf = $this->modelConfSite->getById($confId);
		
		if(is_null($response))
			return false;

		if(is_null($objConf))
			return false;

		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3 || count($arrPrd) < 1)	//0-host, 1-ag_code, 2-ag_token
			return false;

		$arrIdx = getHistoryDate($objConf->conf_idx);
		
		$arrBet = null;
		$arrResult = json_decode($response, true);
		// writeLog($this->fLog, $logHead."response=".$response);
		
		if(!is_null($arrResult) && array_key_exists("status", $arrResult)) {
			writeLog($this->fLog, $logHead."status-".$arrResult['status']);
			if($arrResult['status'] == 1){
				//"resultCode": "0",
				//"resultMessage": "OK", // Newly Created Id In AAS
				//"data": [],    
				if(array_key_exists('slot', $arrResult))
					$arrBet = $arrResult['slot'];
			} else { 
				//"resultCode": "0",
			}
		} else {
		}

		if(is_null($arrBet)) {			
			return false;
		}

		$nBetCnt = count($arrBet);
		writeLog($this->fLog, $logHead."Bet Count-".count($arrBet));

		$lastIdx = "";
		if($nBetCnt == 0){

			$tmNow = time();
			$startAt = strtotime("-1 day", $tmNow);

			if($arrIdx['idx'] < date('Y-m-d H:i:s', $startAt)){
				$lastIdx = date('Y-m-d H:i:s', strtotime("+1 day", strtotime($arrIdx['idx'])));
				writeLog($this->fLog, $logHead."lastIdx=".$lastIdx);
			} 
		}

		$lastFid = 0;
		if($arrIdx['fid'] > FID_OFFSET){
			$lastFid = $arrIdx['fid'] - FID_OFFSET;
		} 
		writeLog($this->fLog, $logHead."Last Fid-".$lastFid);

		$objMember = null;
		$arrEmpPoint = array();
		$arrMemBlank = array();
		$arrMemBet = array();
		
		$arrLiveUids = [];
		foreach ($arrBet as $bet) {
			writeLog($this->fLog, $logHead."type=".$bet['txn_type'].", user=".$bet['user_code'].", bet=".$bet['bet'].", win=".$bet['win'].", txn_id=".$bet['txn_id'].", created_at=".$bet['created_at']);

			if(!in_array($bet['user_code'], $arrLiveUids))
				array_push($arrLiveUids, $bet['user_code']);
		}
		$arrMember = $this->modelMember->getMembersByLiveUids($arrLiveUids, $gameId);
		if(count($arrMember) > 0)
			writeLog($this->fLog, $logHead."***Member Count-".count($arrMember));
		foreach ($arrMember as $member) {
			$member->ratio = $this->modelMember->getEmployeeRatio($member, $gameId);
		}
		$bInsert = false;
		$rwLastFid = $this->modelReward->getLastFid($gameId);
		writeLog($this->fLog, $logHead."Reward LastFid=".$rwLastFid);
		if($rwLastFid > FID_OFFSET*3){
			$rwLastFid = $rwLastFid - FID_OFFSET*3;
		}

		foreach ($arrBet as $bet) {

			$lastIdx = date('Y-m-d H:i:s', strtotime("+1 second", strtotime($bet['created_at'])));;

			$bet['provider'] = 0;
			$objMember = findMemberByLiveId($arrMember, $bet['user_code'], $gameId);
			if(is_null($objMember))
				continue;

			$bet['game_id'] = $gameId;
			$betting = $this->modelSlotBet->getByGslot($gameId, $bet, $lastFid);	//베팅내역체크

			if($bet['txn_type'] === "credit")			//청산
			{	
				if(!is_null($betting) &&  intval($betting['bet_win_money']) == $bet['win'])
					continue;
				
				$arrMemBet[$objMember->mb_fid] = strToLocal($bet['created_at']);
				if(is_null($betting)){
					$objPrd = getPrdByName($arrPrd, $bet['provider_code']);
					if(!is_null($objPrd))
						$bet['provider'] = $objPrd->code;
					$betId = $this->modelSlotBet->insertGslot($objMember, $bet);
					writeLog($this->fLog, $logHead."Insert ACCId=".$betId);
					if($betId > 0)
						$arrIdx['fid'] = $betId;
				} else {
					$betId = $betting['bet_fid'];
					$bResult = $this->modelSlotBet->updateGslot($betId, $bet);
					writeLog($this->fLog, $logHead."Update ACCId=".$betId."=>Result-".$bResult);
				}
			} else {								//베팅
				if(!is_null($betting) &&  intval($betting['bet_money']) == $bet['bet'])
					continue;
				
				$bBlank = false;
				$nBlankPt = 0;

				$arrEmpRatio = calcEmpPoint($objMember->ratio, $bet['bet'], strToLocal($bet['created_at']));
				if($objMember->mb_blank_count > 0 && intval($objMember->mb_blank_current) >= intval($objMember->mb_blank_count)-1 ){
					$objMember->mb_blank_current = 0;
					$bBlank = true;
					$nBlankPt = calcCompPoint($objMember->ratio, $bet['bet']);
					$arrMemBlank[$objMember->mb_fid] = $objMember->mb_blank_current;
					writeLog($this->fLog, $logHead."Blank Cross Id=".$objMember->mb_uid." Current=".$objMember->mb_blank_current." Comp=".$nBlankPt);
				} else {
					foreach($arrEmpRatio as $ratio){
						
						if(array_key_exists($ratio['mb_fid'], $arrEmpPoint ))
							$arrEmpPoint[$ratio['mb_fid']] += $ratio['point'];
						else 
							$arrEmpPoint[$ratio['mb_fid']] = $ratio['point'];	
					}
					if($objMember->mb_blank_count > 0){
						$objMember->mb_blank_current ++;
						$arrMemBlank[$objMember->mb_fid] = $objMember->mb_blank_current;
						writeLog($this->fLog, $logHead."Blank Id=".$objMember->mb_uid." Current=".$objMember->mb_blank_current);
					}
					
				}

				$arrMemBet[$objMember->mb_fid] = strToLocal($bet['created_at']);
				$betId = 0;
				if(is_null($betting)){
					$objPrd = getPrdByName($arrPrd, $bet['provider_code']);
					if(!is_null($objPrd))
						$bet['provider'] = $objPrd->code;
					$betId = $this->modelSlotBet->insertGslot($objMember, $bet, $bBlank, $nBlankPt);
					writeLog($this->fLog, $logHead."Insert BetId=".$betId);
					if($betId > 0)
						$arrIdx['fid'] = $betId;
				} else {
					$betId = $betting['bet_fid'];
					$bResult = $this->modelSlotBet->updateGslot($betId, $bet, $bBlank, $nBlankPt);
					writeLog($this->fLog, $logHead."Update BetId=".$betId."=>Result-".$bResult);

				}
					
				if($betId > 0){
					$this->modelReward->insert($gameId, $betId, $arrEmpRatio, $rwLastFid, $bBlank);
					$bInsert = true;
					writeLog($this->fLog, $logHead."Insert Reward Count=".count($arrEmpRatio));
				}
			}

			// writeLog($this->fLog, $logHead."BET-".$bet['user_code']."=>".$bet['bet']);
		}

		if(strlen($lastIdx) > 0)
			$this->modelConfSite->updateLastIdx($objConf->conf_id, $lastIdx."#".$arrIdx['fid']);

		$this->modelMember->updateMemberBetTm($arrMemBet);
		$bResult = $this->modelMember->updateMemberBlank($arrMemBlank);
		// writeLog($this->fLog, $logHead."UpdateMemBlank-Count=".count($arrMemBlank)." Result=".$bResult);
		$bResult = $this->modelMember->addEmployeePoint($arrEmpPoint);
		// writeLog($this->fLog, $logHead."AddEmpPoint-Count=".count($arrEmpPoint)." Result=".$bResult);

		return $bInsert;
	}

	//===========KGON================
		
	public function curlKgonBets(){
		$confId = CONF_API_KGON;
		$logHead = "<KGON> ";

		$objConf = $this->modelConfSite->getById($confId);
		
		if(is_null($objConf))
			return null;
		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3)	//0-host, 1-ag_code, 2-ag_token
			return null;

		$url = $arrInfo[0]."/transaction";
		$arrIdx = getHistoryDate($objConf->conf_idx);
		
		$post = "limmit=2000";
		// if($arrIdx['idx'] == ""){
		// 	$arrIdx['idx'] = date('Y-m-d\TH:i:s', strtotime("-10 hours", time())); 
		// } 
		if($arrIdx['idx'] !== "")
        	$post.= "&sdate=".$arrIdx['idx'];

		$header =  [
			'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($post),
            'Accept: */*',
            'k-username: '.$arrInfo[1],
            'k-secret: '.$arrInfo[2]
		];
        
		writeLog($this->fLog, $logHead."ReqSdate=".$arrIdx['idx']);

		return getCurl($url, $header, $post);
	}

	public function registerKgonBets($response){
		$gameSlotId = GAME_SLOT_KGON;
		$gameCasId = GAME_CASINO_KGON;
		$confId = CONF_API_KGON;
		$logHead = "<KGON> ";

		$arrCasPrd = $this->modelCasinoPrd->getByCat($gameCasId);
		$arrSlotPrd = $this->modelSlotPrd->getByCat($gameSlotId);
		$objConf = $this->modelConfSite->getById($confId);
		
		// $arrPrd = $this->modelSlotPrd->getByCat($gameSlotId);
		
		if(is_null($response))
			return false;

		if(is_null($objConf))
			return false;

		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3)	//0-host, 1-ag_code, 2-ag_token
			return false;

		$arrIdx = getHistoryDate($objConf->conf_idx);
		
		$arrBet = null;
		$arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("code", $arrResult)) {
			if($arrResult['code'] == 0){
				$arrResult['status'] = 1;
				//"code": 0,
                //"transactions": [],    
                $arrBet = $arrResult['transactions'];
            } else { 
				$arrResult['status'] = 0;
				//"code": 0,
                //"msg": ""
            }
		} else {
            $arrResult['status'] = 0;
            
        }

		if(is_null($arrBet)) {			
			return false;
		}

		$arrCasIds = [];
		foreach ($arrCasPrd as $casPrd) {
			array_push($arrCasIds, $casPrd->vendor_id);
		}

		$arrSlotIds = [];
		foreach ($arrSlotPrd as $slotPrd) {
			array_push($arrSlotIds, $slotPrd->code);
		}

		$nBetCnt = count($arrBet);
		writeLog($this->fLog, $logHead."Bet Count-".count($arrBet));

		$lastIdx = $arrIdx['idx'];
		$lastSlFid = 0;
		if($arrIdx['fid'] > FID_OFFSET){
			$lastSlFid = $arrIdx['fid'] - FID_OFFSET;
		}
		$lastCsFid = 0;
		if($arrIdx['fid2'] > FID_OFFSET){
			$lastCsFid = $arrIdx['fid2'] - FID_OFFSET;
		} 
		writeLog($this->fLog, $logHead."Last Fid-".$lastSlFid);

		$objMember = null;
		$arrEmpPoint = array();
		$arrMemBlank = array();
		$arrMemBet = array();
		
		$arrLiveUids = [];
		$slExist = false;
		$csExist = false;
		$logHead = "<KGON> BET ";
		foreach ($arrBet as $bet) {
			writeLog($this->fLog, $logHead.$bet['gameCategory'].", vendorId=".$bet['vendorId'].", ".$bet['siteUsername'].", ".$bet['refId']."=>".$bet['cash']);

			if($bet['gameCategory'] == "casino")
				$csExist = true;
			else if($bet['gameCategory'] == "slot")
				$slExist = true;
			else {
				if(in_array(strval($bet['vendorId']), $arrCasIds)){
					$bet['gameCategory'] = "casino";
					// writeLog($this->fLog, $logHead.$bet['gameCategory'].", vendorId=".$bet['vendorId'].", ".$bet['siteUsername'].", ".$bet['refId']."=>".$bet['cash']);
					$csExist = true;
				}
				else if(in_array(strval($bet['vendorId']), $arrSlotIds)){
					$bet['gameCategory'] = "slot";
					$slExist = true;
				}
			}
			if(!in_array($bet['siteUsername'], $arrLiveUids))
				array_push($arrLiveUids, $bet['siteUsername']);
		}
		$arrMember = $this->modelMember->getMembersByLiveUids($arrLiveUids, $gameSlotId);
		if(count($arrMember) > 0)
			writeLog($this->fLog, $logHead."***Member Count-".count($arrMember));
		foreach ($arrMember as $member) {
			if($slExist)
				$member->ratio_sl = $this->modelMember->getEmployeeRatio($member, $gameSlotId);
			if($csExist)
				$member->ratio_cs = $this->modelMember->getEmployeeRatio($member, $gameCasId);
		}
		$bInsert = false;
		$rwSlLastFid = $this->modelReward->getLastFid($gameSlotId);
		writeLog($this->fLog, $logHead."Reward LastFid=".$rwSlLastFid);
		if($rwSlLastFid > FID_OFFSET*3){
			$rwSlLastFid = $rwSlLastFid - FID_OFFSET*3;
		}

		$rwCsLastFid = $this->modelReward->getLastFid(GAME_CASINO_EVOL);
		writeLog($this->fLog, $logHead."Reward LastFid=".$rwCsLastFid);
		if($rwCsLastFid > FID_OFFSET*3){
			$rwCsLastFid = $rwCsLastFid - FID_OFFSET*3;
		}

		$lastIdx2 = "";
		$tmNow = time();
		$lastTime2 = date('Y-m-d H:i:s', strtotime("-10 minutes", $tmNow));

		foreach ($arrBet as $bet) {

			$lastIdx = $bet['utcCreatedAt'];
			
			$objMember = findMemberByLiveId($arrMember, $bet['siteUsername'], $gameSlotId);
			if(is_null($objMember)){
				$logHead = "<KGON>";
				writeLog($this->fLog, $logHead."No member-".$bet['gameCategory'].", ".$bet['siteUsername'].", ".$bet['refId']."=>".$bet['cash']);
				continue;
			}

			if(strlen($bet['gameCategory']) == 0){
				if( in_array(strval($bet['vendorId']), $arrCasIds)){
					$bet['gameCategory'] = "casino";
					// writeLog($this->fLog, $logHead.$bet['gameCategory'].", vendorId=".$bet['vendorId'].", ".$bet['siteUsername'].", ".$bet['refId']."=>".$bet['cash']);
				}
				else if(in_array(strval($bet['vendorId']), $arrSlotIds)){
					$bet['gameCategory'] = "slot";
				}
			}
			

			$bet['agent_id'] = $arrInfo[1];			//agent code			
			$bet['user_id'] = $objMember->mb_kgon_id;

			if($bet['gameCategory'] == "casino"){
				$logHead = "<KGON> CAS>> ";
				writeLog($this->fLog, $logHead.$bet['gameCategory'].", vendorId=".$bet['vendorId'].", ".$bet['siteUsername'].", bet-".$bet['refId']."=>".$bet['cash']);
				$bet['txn_id'] = $bet['refId'];
				$betting = $this->modelCasinoBet->getByBet($bet, $lastCsFid);	//베팅내역체크

				if($bet['type'] != "turn_bet")			//청산
				{		
					if(is_null($betting)){
						writeLog($this->fLog, $logHead."ACC-Not Found-".$bet['refId']."=>".$bet['cash']);
						continue;
					}

					$bSpec = false;
					$betId = $betting['bet_fid'];
					if(strlen(trim($betting['bet_result'])) > 0 ){
						writeLog($this->fLog, $logHead."ACC-Already-".$bet['refId']."=>".$bet['cash']);

						if($bet['vendorKey'] == "evolution_casino" || $bet['vendorKey'] == "dreamgaming_casino"){
							if(strlen(trim($betting['bet_spec'])) == 0 ){
								$bSpec = $this->modelCasinoBet->updateKSpec($betId, $bet);
								writeLog($this->fLog, $logHead."ACC-Update Spec1-".$bet['refId'].", createdAt=".$bet['createdAt'].", lastIdx2=".$lastIdx2."=>".$bSpec);

								if(!$bSpec && $bet['createdAt'] > $lastTime2 && ( strlen($lastIdx2) == 0 || $lastIdx2 > $bet['utcCreatedAt']) ){
									$lastIdx2 = $bet['utcCreatedAt'];
									writeLog($this->fLog, $logHead."ACC-Update Spec2-".$bet['refId'].", utcCreatedAt=".$bet['utcCreatedAt'].", lastIdx2=".$lastIdx2."=>".$bSpec);
								}
							}
						}
						continue;
					}

					if($bet['vendorKey'] == "evolution_casino" || $bet['vendorKey'] == "dreamgaming_casino"){
						if(strlen(trim($betting['bet_spec'])) == 0 ){
							$bSpec = $this->modelCasinoBet->updateKSpec($betId, $bet);
							writeLog($this->fLog, $logHead."ACC-Update Spec1-".$bet['refId'].", createdAt=".$bet['createdAt'].", lastIdx2=".$lastIdx2."=>".$bSpec);

							if(!$bSpec && $bet['createdAt'] > $lastTime2 && ( strlen($lastIdx2) == 0 || $lastIdx2 > $bet['utcCreatedAt'])){
								$lastIdx2 = $bet['utcCreatedAt'];
								writeLog($this->fLog, $logHead."ACC-Update Spec2-".$bet['refId'].", utcCreatedAt=".$bet['utcCreatedAt'].", lastIdx2=".$lastIdx2."=>".$bSpec);
							}
						}
					} 
					if(!$bSpec)
						$this->modelCasinoBet->updateK($betId, $bet);

					writeLog($this->fLog, $logHead."Update ACCId=".$betId);
					$arrMemBet[$objMember->mb_fid] = $bet['createdAt'];
					
					if($bet['type'] == "turn_draw"){	//타이라면
						
						$arrRewards = $this->modelReward->getByBetId(GAME_CASINO_EVOL, $betId, $rwCsLastFid);
						foreach($arrRewards as $objReward){
							writeLog($this->fLog, $logHead."Cancel RwId=".$objReward->rw_fid." mb_fid=".$objReward->rw_mb_fid);

							if(array_key_exists($objReward->rw_mb_fid, $arrEmpPoint )){
								// writeLog($this->fLog, $logHead."Cancel RwId=".$objReward->rw_fid." point1 =".$arrEmpPoint[$objReward->rw_mb_fid]);
								$arrEmpPoint[$objReward->rw_mb_fid] -= $objReward->rw_point;
								// writeLog($this->fLog, $logHead."Cancel RwId=".$objReward->rw_fid." point =".$arrEmpPoint[$objReward->rw_mb_fid]);
							}
							else {
								$arrEmpPoint[$objReward->rw_mb_fid] = 0-$objReward->rw_point;	
								// writeLog($this->fLog, $logHead."Cancel RwId=".$objReward->rw_fid." point =".$arrEmpPoint[$objReward->rw_mb_fid]);
							}
						}

						$this->modelReward->deleteByBetId(GAME_CASINO_EVOL, $betId, $rwCsLastFid);
					}

				} else	//베팅
				{
					if(!is_null($betting)){
						writeLog($this->fLog, $logHead."BET-Exist-".$bet['refId']."=>".$bet['cash']);
						continue;
					}
					
					$betId = $this->modelCasinoBet->insertK($objMember, $bet);
					writeLog($this->fLog, $logHead."Insert BetId=".$betId);
					$arrMemBet[$objMember->mb_fid] = $bet['createdAt'];
					if($betId > 0){
						
						if($bet['vendorKey'] == "evolution_casino" || $bet['vendorKey'] == "dreamgaming_casino"){
							$bSpec = $this->modelCasinoBet->updateKSpec($betId, $bet);
							writeLog($this->fLog, $logHead."BET-INSERT Spec1-".$bet['refId'].", createdAt=".$bet['createdAt'].", lastIdx2=".$lastIdx2."=>".$bSpec);

							if(!$bSpec && $bet['createdAt'] > $lastTime2 && ( strlen($lastIdx2) == 0 || $lastIdx2 > $bet['utcCreatedAt']) ){
								$lastIdx2 = $bet['utcCreatedAt'];
								writeLog($this->fLog, $logHead."BET-INSERT Spec2-".$bet['refId'].", utcCreatedAt=".$bet['utcCreatedAt'].", lastIdx2=".$lastIdx2."=>".$bSpec);
							}
						} 
						
						$arrIdx['fid2'] = $betId;
						$bInsert = true;
						writeLog($this->fLog, $logHead."BET-INSERT-".$bet['type']."-".$bet['_id']."=>".$bet['cash']);

						$arrEmpRatio = calcEmpPoint($objMember->ratio_cs, $bet['cash'], $bet['createdAt']);
						foreach($arrEmpRatio as $ratio){
							if(array_key_exists($ratio['mb_fid'], $arrEmpPoint ))
								$arrEmpPoint[$ratio['mb_fid']] += $ratio['point'];
							else 
								$arrEmpPoint[$ratio['mb_fid']] = $ratio['point'];	
						}
						$this->modelReward->insert(GAME_CASINO_EVOL, $betId, $arrEmpRatio, $rwCsLastFid);
					}
					
				}
			} else if($bet['gameCategory'] == "slot"){
				$logHead = "<KGON> SLOT>> ";

				writeLog($this->fLog, $logHead."bet-".$bet['refId']."=>".$bet['cash']);
				$bet['game_id'] = $gameSlotId;
				$betting = $this->modelSlotBet->getByKslot($gameSlotId, $bet, $lastSlFid);	//베팅내역체크
				
				if($bet['type'] != "turn_bet")			//청산
				{	
					if(is_null($betting)){
						writeLog($this->fLog, $logHead."ACC-Not Found-".$bet['refId']."=>".$bet['cash']);
						continue;
					}

					if(strlen(trim($betting['bet_result'])) > 0 ){
						writeLog($this->fLog, $logHead."ACC-Already-".$bet['refId']."=>".$bet['cash']);
						continue;
					}
					
					$arrMemBet[$objMember->mb_fid] = $bet['createdAt'];

					$betId = $betting['bet_fid'];
					$bResult = $this->modelSlotBet->updateKslot($betId, $bet);
					writeLog($this->fLog, $logHead."Update ACCId=".$betId."=>Result-".$bResult);
				} else {								//베팅
					if(!is_null($betting)){
						writeLog($this->fLog, $logHead."BET-Exist-".$bet['refId']."=>".$bet['cash']);
						continue;
					}
					
					$bBlank = false;
					$nBlankPt = 0;

					$arrEmpRatio = calcEmpPoint($objMember->ratio_sl, $bet['cash'], $bet['createdAt']);
					if($objMember->mb_blank_count > 0 && intval($objMember->mb_blank_current) >= intval($objMember->mb_blank_count)-1 ){
						$objMember->mb_blank_current = 0;
						$bBlank = true;
						$nBlankPt = calcCompPoint($objMember->ratio_sl, $bet['cash']);
						$arrMemBlank[$objMember->mb_fid] = $objMember->mb_blank_current;
						writeLog($this->fLog, $logHead."Blank Cross Id=".$objMember->mb_uid." Current=".$objMember->mb_blank_current." Comp=".$nBlankPt);
					} else {
						foreach($arrEmpRatio as $ratio){
							
							if(array_key_exists($ratio['mb_fid'], $arrEmpPoint ))
								$arrEmpPoint[$ratio['mb_fid']] += $ratio['point'];
							else 
								$arrEmpPoint[$ratio['mb_fid']] = $ratio['point'];	
						}
						if($objMember->mb_blank_count > 0){
							$objMember->mb_blank_current ++;
							$arrMemBlank[$objMember->mb_fid] = $objMember->mb_blank_current;
							writeLog($this->fLog, $logHead."Blank Id=".$objMember->mb_uid." Current=".$objMember->mb_blank_current);
						}
						
					}

					$arrMemBet[$objMember->mb_fid] = $bet['createdAt'];
					$betId = $this->modelSlotBet->insertKslot($objMember, $bet, $bBlank, $nBlankPt);
					writeLog($this->fLog, $logHead."Insert BetId=".$betId);
					if($betId > 0)
						$arrIdx['fid'] = $betId;
						
					if($betId > 0){
						$this->modelReward->insert($gameSlotId, $betId, $arrEmpRatio, $rwSlLastFid, $bBlank);
						$bInsert = true;
						writeLog($this->fLog, $logHead."Insert Reward Count=".count($arrEmpRatio));
					}
				}

			} else{
				$logHead = "<KGON>";
				writeLog($this->fLog, $logHead."No Category-".$bet['gameCategory'].", ".$bet['siteUsername'].", ".$bet['refId']."=>".$bet['cash']);
			}
			
		}
		writeLog($this->fLog, "<KGON> END lasdIdx=".$lastIdx." lastIdx2=".$lastIdx2);
		if(strlen($lastIdx2) > 0){

			$tmDt = strtotime($lastIdx2);
			$tmDt = strtotime("-9 hours", $tmDt);
			$tmDt = strtotime("-1 second", $tmDt);

			$lastIdx = date("Y-m-d", $tmDt)."T".date("H:i:s", $tmDt).".000Z";
		}

		if(strlen($lastIdx) > 0)
			$this->modelConfSite->updateLastIdx($objConf->conf_id, $lastIdx."#".$arrIdx['fid']."#".$arrIdx['fid2']);

		$this->modelMember->updateMemberBetTm($arrMemBet);
		$bResult = $this->modelMember->updateMemberBlank($arrMemBlank);
		// writeLog($this->fLog, $logHead."UpdateMemBlank-Count=".count($arrMemBlank)." Result=".$bResult);
		$bResult = $this->modelMember->addEmployeePoint($arrEmpPoint);
		// writeLog($this->fLog, $logHead."AddEmpPoint-Count=".count($arrEmpPoint)." Result=".$bResult);

		return $bInsert;
	}


	//===========STAR API=================
	
	public function curlStarBets() {
		$confId = CONF_API_STAR;
		$logHead = "<STAR> ";

		$objConf = $this->modelConfSite->getById($confId);
		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3)	//0-host, 1-ag_code, 2-ag_token
			return null;
		
		$strStart = "";
		$strEnd = "";
		$arrIdx = getHistoryDate($objConf->conf_idx);

		if(strlen($arrIdx['idx']) > 0){
			$strStart = $arrIdx['idx'];
			$startAt = strtotime($strStart);
			$strEnd = date('Y-m-d H:i:s', strtotime("+1 hour", $startAt));
		} else {
			$tmNow = time();
			$strStart = date("Y-m-d H:i:s", $tmNow);     
			$strEnd = date('Y-m-d H:i:s', strtotime("+1 hour", $tmNow));
		}

		$url = $arrInfo[0]."/BetWinHistory";
		$url.= "?startdate=".$strStart;
		$url.= "&enddate=".$strEnd;
		$url.= "&page=1";

		writeLog($this->fLog, $logHead."Request Date=".$strStart."~".$strEnd);

		$header =  [ 'secret_key: '.$arrInfo[2] ];

		return getCurl($url, $header);
		
	}

	public function registerStarBets($response) {
		$gameSlotId = GAME_SLOT_STAR;
		$gameCasId = GAME_CASINO_STAR;
		$confId = CONF_API_STAR;
		$logHead = "<STAR> ";

		$arrCasPrd = $this->modelCasinoPrd->getByCat($gameCasId);
		$arrSlotPrd = $this->modelSlotPrd->getByCat($gameSlotId);
		$objConf = $this->modelConfSite->getById($confId);
		
		$arrInfo = explode("#", $objConf->conf_content);
		if(count($arrInfo) < 3)	//0-host, 1-ag_code, 2-ag_token
			return null;

		$arrIdx = getHistoryDate($objConf->conf_idx);
		
		if(is_null($response))
			return false;
			
		if(is_null($objConf))
			return false;

		$arrResult = json_decode($response, true);

		$arrBet = null;
		
		if(!is_null($arrResult) && array_key_exists("state", $arrResult)) {
			if($arrResult['state'] == 0){
                //"status": 1,
                //"total": 1, // Newly Created Id In AAS
                //"data": [],    
                $arrBet = $arrResult['data'];
            } else { 
                //"status": 0,
                //"error": "INVALID_ACCESS_TOKEN", DOUBLE_USER, INSUFFICIENT_FUNDS, INTERNAL_ERROR
            }
		} else {
            $arrResult['status'] = 0;
            
        }

		if(is_null($arrBet)) {			
			return false;
		}

		writeLog($this->fLog, $logHead."Bet Count-".count($arrBet));
		// writeLog($this->fLog, $logHead."CsPrd Count-".count($arrCasPrd));

		$lastIdx = "";
		$lastSlFid = 0;
		if($arrIdx['fid'] > FID_OFFSET){
			$lastSlFid = $arrIdx['fid'] - FID_OFFSET;
		} 
		$lastCsFid = 0;
		if($arrIdx['fid2'] > FID_OFFSET){
			$lastCsFid = $arrIdx['fid2'] - FID_OFFSET;
		} 
		writeLog($this->fLog, $logHead."Last Fid-".$lastSlFid);

		$objMember = null;
		$arrEmpPoint = array();
		$arrMemBlank = array();
		$arrMemBet = array();
		
		$arrLiveUids = [];
		$slExist = false;
		$csExist = false;
		foreach ($arrBet as $bet) {
			if($bet['gameType'] != "slot")
				$csExist = true;
			else if($bet['gameType'] == "slot")
				$slExist = true;
			if(!in_array($bet['playerID'], $arrLiveUids))
				array_push($arrLiveUids, $bet['playerID']);
		}

		$arrMember = $this->modelMember->getMembersByLiveUids($arrLiveUids, $gameSlotId);
		if(count($arrMember) > 0)
			writeLog($this->fLog, $logHead."***Member Count-".count($arrMember));
		foreach ($arrMember as $member) {
			if($slExist)
				$member->ratio_sl = $this->modelMember->getEmployeeRatio($member, $gameSlotId);
			if($csExist)
				$member->ratio_cs = $this->modelMember->getEmployeeRatio($member, $gameCasId);
		}
		$bInsert = false;
		$rwSlLastFid = $this->modelReward->getLastFid($gameSlotId);
		writeLog($this->fLog, $logHead."Reward LastFid=".$rwSlLastFid);
		if($rwSlLastFid > FID_OFFSET*3){
			$rwSlLastFid = $rwSlLastFid - FID_OFFSET*3;
		}

		$rwCsLastFid = $this->modelReward->getLastFid(GAME_CASINO_EVOL);
		writeLog($this->fLog, $logHead."Reward LastFid=".$rwCsLastFid);
		if($rwCsLastFid > FID_OFFSET*3){
			$rwCsLastFid = $rwCsLastFid - FID_OFFSET*3;
		}

		$objPrd = null;
		$cnt = count($arrBet);
		for ($i = $cnt-1; $i>=0 ; $i--) {

			$bet = $arrBet[$i];
			
			$objMember = findMemberByLiveId($arrMember, $bet['playerID'], $gameSlotId);
			if(is_null($objMember))
				continue;

			if($bet['gameType'] != "slot"){
				$logHead = "<STAR> cas>>";
				
				$objPrd = getPrdByKey($arrCasPrd, $bet['providerName']);
				if(is_null($objPrd))
					continue;

				$betting = $this->modelCasinoBet->getByIdx($objPrd->vendor_id, $bet['transactionID'], $lastCsFid);	//베팅내역체크

				if(!is_null($betting))
					continue;

				if($lastIdx === "" || $lastIdx < $bet['regdate'])
					$lastIdx = $bet['regdate'];
					
				$bet['agent_id'] = $arrInfo[1];					//agend id
				$bet['prd_id'] = $objPrd->vendor_id;			//provider code
				$arrMemBet[$objMember->mb_fid] = $bet['regdate'];
	
				writeLog($this->fLog, $logHead."BET-".$bet['gameType']." transactionID=".$bet['transactionID']."=> bet=".$bet['bet']." win=".$bet['win']);
	
				$betId = $this->modelCasinoBet->insertH($objMember, $bet);
				if($betId > 0){
					writeLog($this->fLog, $logHead."Insert BetId=".$betId);
					$arrIdx['fid2'] = $betId;
					$bInsert = true;
	
					if($bet['bet'] > 0 && $bet['bet'] !== $bet['win']){
						$arrEmpRatio = calcEmpPoint($objMember->ratio_cs, $bet['bet'], $bet['regdate']);
						foreach($arrEmpRatio as $ratio){
							if(array_key_exists($ratio['mb_fid'], $arrEmpPoint ))
								$arrEmpPoint[$ratio['mb_fid']] += $ratio['point'];
							else 
								$arrEmpPoint[$ratio['mb_fid']] = $ratio['point'];	
						}
						$this->modelReward->insert(GAME_CASINO_EVOL, $betId, $arrEmpRatio, $rwCsLastFid);
			
					}
				}
			} else if($bet['gameType'] == "slot"){
				$logHead = "<STAR> slot>>";
				// writeLog($this->fLog, $logHead."BET-".$bet['transactionID']."=>".$bet['bet']);

				$objPrd = getPrdByName($arrSlotPrd, $bet['providerName']);
				if(is_null($objPrd))
					continue;

				$betting = $this->modelSlotBet->getByHslot($gameSlotId, $bet, $lastSlFid);	//베팅내역체크
				if(!is_null($betting))
					continue;
				
				if($lastIdx === "" || $lastIdx < $bet['regdate'])
					$lastIdx = $bet['regdate'];

				$bet['agent_code'] = $arrInfo[1];		//agend id
				$bet['prd_id'] = $objPrd->code;			//provider code
				$bet['game_id'] = $gameSlotId;				//game id


				$bBlank = false;
				$nBlankPt = 0;

				$arrEmpRatio = calcEmpPoint($objMember->ratio_sl, $bet['bet'], $bet['regdate']);
				if($objMember->mb_blank_count > 0 && intval($objMember->mb_blank_current) >= intval($objMember->mb_blank_count)-1 ){
					$objMember->mb_blank_current = 0;
					$bBlank = true;
					$nBlankPt = calcCompPoint($objMember->ratio_sl, $bet['bet']);
					$arrMemBlank[$objMember->mb_fid] = $objMember->mb_blank_current;
					writeLog($this->fLog, $logHead."Blank Cross Id=".$objMember->mb_uid." Current=".$objMember->mb_blank_current." Comp=".$nBlankPt);
				} else {
					foreach($arrEmpRatio as $ratio){
						
						if(array_key_exists($ratio['mb_fid'], $arrEmpPoint ))
							$arrEmpPoint[$ratio['mb_fid']] += $ratio['point'];
						else 
							$arrEmpPoint[$ratio['mb_fid']] = $ratio['point'];	
					}
					if($objMember->mb_blank_count > 0){
						$objMember->mb_blank_current ++;
						$arrMemBlank[$objMember->mb_fid] = $objMember->mb_blank_current;
						writeLog($this->fLog, $logHead."Blank Id=".$objMember->mb_uid." Current=".$objMember->mb_blank_current);
					}
				}
				$bet['game_id'] = $gameSlotId;
				$arrMemBet[$objMember->mb_fid] = $bet['regdate'];

				$betId = $this->modelSlotBet->insertHslot($objMember, $bet, $bBlank, $nBlankPt);
				if($betId > 0){
					$this->modelReward->insert($gameSlotId, $betId, $arrEmpRatio, $rwSlLastFid, $bBlank);
					$bInsert = true;
					$arrIdx['fid'] = $betId;
				}
				writeLog($this->fLog, $logHead."Insert BetId=".$betId);
			}
		}

		if(strlen($lastIdx) == 0){
			$tmNow = time();
			$strStart = date("Y-m-d H:i:s", $tmNow);     
			$arrIdx['idx'] = date('Y-m-d H:i:s', strtotime("-30 minutes", $tmNow));
			
		} else 
			$arrIdx['idx'] = $lastIdx; 

		writeLog($this->fLog, $logHead."lastIdx=".$arrIdx['idx']);

		$this->modelConfSite->updateLastIdx($objConf->conf_id, $arrIdx['idx']."#".$arrIdx['fid']."#".$arrIdx['fid2']);

		$bResult = $this->modelMember->addEmployeePoint($arrEmpPoint);
		// writeLog($this->fLog, $logHead."AddEmpPoint-Count=".count($arrEmpPoint)." Result=".$bResult);
		$this->modelMember->updateMemberBetTm($arrMemBet);
		$bResult = $this->modelMember->updateMemberBlank($arrMemBlank);
		// writeLog($this->fLog, $logHead."UpdateMemBlank-Count=".count($arrMemBlank)." Result=".$bResult);
	
		return $bInsert;
	}


	//===========Rave================
		
	public function curlRaveBets(){
		$confId = CONF_API_RAVE;
		$logHead = "<RAVE> ";

		$objConf = $this->modelConfSite->getById($confId);
		
		if(is_null($objConf))
			return null;
		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3)	//0-host, 1-ag_code, 2-ag_token
			return null;

		$arrIdx = getHistoryDate($objConf->conf_idx);
		if($arrIdx['idx'] == ""){
			$arrIdx['idx'] = date('Y-m-d H:i:s', strtotime("-10 hours", time())); 
		} else {
			$arrIdx['idx'] = date('Y-m-d H:i:s', strtotime($arrIdx['idx']));
		}
		$startAt = $arrIdx['idx'];
		$endAt = date('Y-m-d H:i:s', strtotime("+1 hour", strtotime($arrIdx['idx'])));

		$url = $arrInfo[0]."/bet?";
		$url.="start=".urlencode($startAt);
		$url.="&end=".urlencode($endAt);
		$url.="&pageIdx=0";
		$url.="&pageSize=1000";

		$header =  [
			'Content-Type: application/json',
			'Accept: application/json',
			'Authorization: Bearer '.$arrInfo[2]
		];
        
		writeLog($this->fLog, $logHead.$startAt."~".$endAt);
		$this->modelConfSite->updateLastIdx($objConf->conf_id, $startAt."#".$arrIdx['fid']."#".$arrIdx['fid2']);

		return getCurl($url, $header);
	}

	public function registerRaveBets($arrResult){
		$gameSlotId = GAME_SLOT_RAVE;
		$gameCasId = GAME_CASINO_RAVE;
		$confId = CONF_API_RAVE;
		$logHead = "<RAVE> ";

		$arrCasPrd = $this->modelCasinoPrd->getByCat($gameCasId);
		$arrSlotPrd = $this->modelSlotPrd->getByCat($gameSlotId);
		$objConf = $this->modelConfSite->getById($confId);
		
		// $arrPrd = $this->modelSlotPrd->getByCat($gameSlotId);
		
		if(is_null($arrResult))
			return false;

		if(is_null($objConf))
			return false;

		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3)	//0-host, 1-ag_code, 2-ag_token
			return false;

		$arrIdx = getHistoryDate($objConf->conf_idx);
		$arrBet = null;
		
		if(!array_key_exists("code", $arrResult)){
			writeLog($this->fLog, $logHead." Error=No Http Code");
		} else if($arrResult['code'] == HTTP_CODE_200){ 
			$arrBet = json_decode($arrResult['body'], true);
		} else {
			writeLog($this->fLog, $logHead." Error=".json_encode($arrResult));
		}

		if(is_null($arrBet)) {			
			return false;
		}

		$nBetCnt = count($arrBet);
		writeLog($this->fLog, $logHead."Bet Count-".count($arrBet));
		$lastIdx = "";
		if($nBetCnt == 0){

			$tmNow = time();
			$startAt = strtotime("-10 hours", $tmNow);

			if($arrIdx['idx'] < date('Y-m-d H:i:s', $startAt)){
				$lastIdx = date('Y-m-d H:i:s', strtotime("+1 hour", strtotime($arrIdx['idx'])));
				writeLog($this->fLog, $logHead."lastIdx=".$lastIdx);
			} 
		}

		$lastSlFid = 0;
		if($arrIdx['fid'] > FID_OFFSET){
			$lastSlFid = $arrIdx['fid'] - FID_OFFSET;
		}
		$lastCsFid = 0;
		if($arrIdx['fid2'] > FID_OFFSET){
			$lastCsFid = $arrIdx['fid2'] - FID_OFFSET;
		} 
		writeLog($this->fLog, $logHead."Last Fid-".$lastSlFid);

		$objMember = null;
		$arrEmpPoint = array();
		$arrMemBlank = array();
		$arrMemBet = array();
		
		$arrLiveUids = [];
		$slExist = false;
		$csExist = false;
		$logHead = "<RAVE> BET ";
		foreach ($arrBet as $bet) {
			writeLog($this->fLog, $logHead.$bet['game']['type'].", vendor=".$bet['game']['vendor'].", ".$bet['user']['name'].", ".$bet['id']."(".$bet['refererId'].")=>".$bet['amount']);

			if(strtolower($bet['game']['type']) == "slot")
				$slExist = true;
			else
				$csExist = true;
			
			if(!in_array($bet['user']['id'], $arrLiveUids))
				array_push($arrLiveUids, $bet['user']['id']);
		}
		$arrMember = $this->modelMember->getMembersByLiveUids($arrLiveUids, $gameSlotId);
		if(count($arrMember) > 0)
			writeLog($this->fLog, $logHead."Member Count-".count($arrMember));
		foreach ($arrMember as $member) {
			if($slExist)
				$member->ratio_sl = $this->modelMember->getEmployeeRatio($member, $gameSlotId);
			if($csExist)
				$member->ratio_cs = $this->modelMember->getEmployeeRatio($member, $gameCasId);
		}
		$bInsert = false;
		$rwSlLastFid = $this->modelReward->getLastFid($gameSlotId);
		writeLog($this->fLog, $logHead."Reward LastFid=".$rwSlLastFid);
		if($rwSlLastFid > FID_OFFSET*3){
			$rwSlLastFid = $rwSlLastFid - FID_OFFSET*3;
		}

		$rwCsLastFid = $this->modelReward->getLastFid(GAME_CASINO_EVOL);
		writeLog($this->fLog, $logHead."Reward LastFid=".$rwCsLastFid);
		if($rwCsLastFid > FID_OFFSET*3){
			$rwCsLastFid = $rwCsLastFid - FID_OFFSET*3;
		}

		foreach ($arrBet as $bet) {

			$lastIdx = date('Y-m-d H:i:s', strtotime("+1 second", strtotime($bet['createdAt'])));;

			$bet['createdAt'] = date('Y-m-d H:i:s', strtotime("+9 hours", strtotime($bet['createdAt'])));;
			$bet['betAt'] = date('Y-m-d H:i:s', strtotime("+9 hours", strtotime($bet['betAt'])));;

			$objMember = findMemberByLiveId($arrMember, $bet['user']['id'], $gameSlotId);
			if(is_null($objMember)){
				$logHead = "<RAVE>";
				writeLog($this->fLog, $logHead."No member-".$bet['game']['type'].", ".$bet['user']['name'].", ".$bet['id']."(".$bet['refererId'].")=>".$bet['amount']);
				continue;
			}

			$bet['agent_id'] = $arrInfo[1];			//agent code			

			if(strtolower($bet['game']['type']) != "slot"){
				$logHead = "<RAVE> CAS>> ";
				writeLog($this->fLog, $logHead.$bet['game']['type'].", vendor=".$bet['game']['vendor'].", ".$bet['user']['name'].", bet-".$bet['refererId']."=>".$bet['amount']);
				$bet['txn_id'] = $bet['refererId'];
				
				$objPrd = getPrdByKey($arrCasPrd, $bet['game']['vendor']);
				if(is_null($objPrd))
					continue;

				$bet['prd_id'] = $objPrd->vendor_id;			//provider code

				if(strtolower($bet['type']) != "bet")			//청산
				{		
					$betting = null;
					if($bet['refererId'] > 0)
						$betting = $this->modelCasinoBet->getByIdx($objPrd->vendor_id, $bet['refererId'], $lastCsFid);	//베팅내역체크
					if(is_null($betting)){
						writeLog($this->fLog, $logHead."ACC-Not Found refererId=".$bet['refererId']."=>".$bet['amount']);
						continue;
					}

					$betId = $betting['bet_fid'];
					// if(strlen(trim($betting['bet_result'])) > 0 ){
					if(trim($betting['bet_result']) == $bet['id']){
						writeLog($this->fLog, $logHead."ACC-Already refererId=".$bet['refererId']."=>".$bet['amount']);
						continue;
					}
					// $lastIdx = $createdAtUtc;
					$this->modelCasinoBet->updateR($betId, $bet, $this->fLog);

					writeLog($this->fLog, $logHead."Update ACCId=".$betId);
					$arrMemBet[$objMember->mb_fid] = $bet['betAt'];
					
					if($bet['amount'] == $betting["bet_money"]){	//타이라면
						
						$arrRewards = $this->modelReward->getByBetId(GAME_CASINO_EVOL, $betId, $rwCsLastFid);
						foreach($arrRewards as $objReward){
							writeLog($this->fLog, $logHead."Cancel RwId=".$objReward->rw_fid." mb_fid=".$objReward->rw_mb_fid);

							if(array_key_exists($objReward->rw_mb_fid, $arrEmpPoint )){
								// writeLog($this->fLog, $logHead."Cancel RwId=".$objReward->rw_fid." point1 =".$arrEmpPoint[$objReward->rw_mb_fid]);
								$arrEmpPoint[$objReward->rw_mb_fid] -= $objReward->rw_point;
								// writeLog($this->fLog, $logHead."Cancel RwId=".$objReward->rw_fid." point =".$arrEmpPoint[$objReward->rw_mb_fid]);
							}
							else {
								$arrEmpPoint[$objReward->rw_mb_fid] = 0-$objReward->rw_point;	
								// writeLog($this->fLog, $logHead."Cancel RwId=".$objReward->rw_fid." point =".$arrEmpPoint[$objReward->rw_mb_fid]);
							}
						}

						$this->modelReward->deleteByBetId(GAME_CASINO_EVOL, $betId, $rwCsLastFid);
					}

				} else	//베팅
				{
					$betting = $this->modelCasinoBet->getByIdx($objPrd->vendor_id, $bet['id'], $lastCsFid);	//베팅내역체크
					if(!is_null($betting)){
						writeLog($this->fLog, $logHead."BET-Exist-".$bet['id']."=>".$bet['amount']);
						continue;
					}
					// $lastIdx = $createdAtUtc;
					
					$betId = $this->modelCasinoBet->insertR($objMember, $bet);
					writeLog($this->fLog, $logHead."Insert BetId=".$betId);
					$arrMemBet[$objMember->mb_fid] = $bet['betAt'];
					if($betId > 0){
						
						$arrIdx['fid2'] = $betId;
						$bInsert = true;
						writeLog($this->fLog, $logHead."BET-INSERT-".$bet['type']."-".$bet['id']."=>".$bet['amount']);

						$arrEmpRatio = calcEmpPoint($objMember->ratio_cs, $bet['amount'], $bet['betAt']);
						foreach($arrEmpRatio as $ratio){
							if(array_key_exists($ratio['mb_fid'], $arrEmpPoint ))
								$arrEmpPoint[$ratio['mb_fid']] += $ratio['point'];
							else 
								$arrEmpPoint[$ratio['mb_fid']] = $ratio['point'];	
						}
						$this->modelReward->insert(GAME_CASINO_EVOL, $betId, $arrEmpRatio, $rwCsLastFid);
					}
					
				}
			} else {
				$logHead = "<RAVE> SLOT>> ";

				writeLog($this->fLog, $logHead."bet-".$bet['refererId']."=>".$bet['amount']);
				$bet['game_id'] = $gameSlotId;
				$objPrd = getPrdByName($arrSlotPrd, $bet['game']['vendor']);
				if(is_null($objPrd))
					continue;

				$bet['prd_id'] = $objPrd->code;			//provider code
				
				if(strtolower($bet['type']) != "bet")			//청산
				{	
					$betting = null;
					if($bet['refererId'] > 0)
						$betting = $this->modelSlotBet->getByRslot($gameSlotId, $bet['prd_id'], $bet['refererId'], $lastSlFid);	//베팅내역체크

					if(is_null($betting)){
						writeLog($this->fLog, $logHead."ACC-Not Found refererId=".$bet['refererId']."=>".$bet['amount']);
						continue;
					}

					// if(strlen(trim($betting['bet_result'])) > 0 ){
					if(trim($betting['bet_result']) == $bet['id']){
						writeLog($this->fLog, $logHead."ACC-Already refererId=".$bet['refererId']."=>".$bet['amount']);
						continue;
					}
					// $lastIdx = $createdAtUtc;
					$arrMemBet[$objMember->mb_fid] = $bet['createdAt'];

					$betId = $betting['bet_fid'];
					$bResult = $this->modelSlotBet->updateRslot($betId, $bet);
					writeLog($this->fLog, $logHead."Update ACCId=".$betId."=>Result-".$bResult);
				} else {								//베팅
					$betting = $this->modelSlotBet->getByRslot($gameSlotId, $bet['prd_id'], $bet['id'], $lastSlFid);	//베팅내역체크
					if(!is_null($betting)){
						writeLog($this->fLog, $logHead."BET-Exist-".$bet['id']."=>".$bet['amount']);
						continue;
					}
					
					// $lastIdx = $createdAtUtc;
					$bBlank = false;
					$nBlankPt = 0;

					$arrEmpRatio = calcEmpPoint($objMember->ratio_sl, $bet['amount'], $bet['betAt']);
					if($objMember->mb_blank_count > 0 && intval($objMember->mb_blank_current) >= intval($objMember->mb_blank_count)-1 ){
						$objMember->mb_blank_current = 0;
						$bBlank = true;
						$nBlankPt = calcCompPoint($objMember->ratio_sl, $bet['amount']);
						$arrMemBlank[$objMember->mb_fid] = $objMember->mb_blank_current;
						writeLog($this->fLog, $logHead."Blank Cross Id=".$objMember->mb_uid." Current=".$objMember->mb_blank_current." Comp=".$nBlankPt);
					} else {
						foreach($arrEmpRatio as $ratio){
							
							if(array_key_exists($ratio['mb_fid'], $arrEmpPoint ))
								$arrEmpPoint[$ratio['mb_fid']] += $ratio['point'];
							else 
								$arrEmpPoint[$ratio['mb_fid']] = $ratio['point'];	
						}
						if($objMember->mb_blank_count > 0){
							$objMember->mb_blank_current ++;
							$arrMemBlank[$objMember->mb_fid] = $objMember->mb_blank_current;
							writeLog($this->fLog, $logHead."Blank Id=".$objMember->mb_uid." Current=".$objMember->mb_blank_current);
						}
						
					}

					$arrMemBet[$objMember->mb_fid] = $bet['betAt'];
						
					$betId = $this->modelSlotBet->insertRslot($objMember, $bet, $bBlank, $nBlankPt);
					writeLog($this->fLog, $logHead."Insert BetId=".$betId);
						
					if($betId > 0){
						$arrIdx['fid'] = $betId;
						$this->modelReward->insert($gameSlotId, $betId, $arrEmpRatio, $rwSlLastFid, $bBlank);
						$bInsert = true;
						writeLog($this->fLog, $logHead."Insert Reward Count=".count($arrEmpRatio));
					}
				}

			} 
			
		}

		if(strlen($lastIdx) > 0)
			$this->modelConfSite->updateLastIdx($objConf->conf_id, $lastIdx."#".$arrIdx['fid']."#".$arrIdx['fid2']);
		writeLog($this->fLog, $logHead."lastIdx=".$lastIdx);

		$this->modelMember->updateMemberBetTm($arrMemBet);
		$bResult = $this->modelMember->updateMemberBlank($arrMemBlank);
		writeLog($this->fLog, $logHead."UpdateMemBlank-Count=".count($arrMemBlank)." Result=".$bResult);
		$bResult = $this->modelMember->addEmployeePoint($arrEmpPoint);
		writeLog($this->fLog, $logHead."AddEmpPoint-Count=".count($arrEmpPoint)." Result=".$bResult);

		return $bInsert;
	}

	//===========Treem================
		
	public function curlTreemBets($proxyUrl=""){
		$confId = CONF_API_TREEM;
		$logHead = "<TREEM> ";

		$objConf = $this->modelConfSite->getById($confId);
		
		if(is_null($objConf))
			return null;
		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3)	//0-host, 1-ag_code, 2-ag_token
			return null;

		$arrIdx = getHistoryDate($objConf->conf_idx);
		if($arrIdx['idx'] == ""){
			$arrIdx['idx'] = date('Y-m-d H:i:s', strtotime("-10 hours", time())); 
		} else {
			$arrIdx['idx'] = date('Y-m-d H:i:s', strtotime($arrIdx['idx']));
		}
		$startAt = $arrIdx['idx'];
		$endAt = date('Y-m-d H:i:s', strtotime("+1 hour", strtotime($arrIdx['idx'])));

		$url = $arrInfo[0]."/transactions?";
		$url.="start=".urlencode($startAt);
		$url.="&end=".urlencode($endAt);
		$url.="&page=1&perPage=1000&withDetails=1";

		$header =  [
			'Content-Type: application/json',
			'Accept: application/json',
			'Authorization: Bearer '.$arrInfo[2]
		];
        
		writeLog($this->fLog, $logHead.$startAt."~".$endAt);
		$this->modelConfSite->updateLastIdx($objConf->conf_id, $startAt."#".$arrIdx['fid']."#".$arrIdx['fid2']);

		return getCurlWithProxy($url, $proxyUrl, $header);
	}

	public function registerTreemBets($arrResult, $proxyUrl=""){
		$gameSlotId = GAME_SLOT_TREEM;
		$gameCasId = GAME_CASINO_TREEM;
		$confId = CONF_API_TREEM;
		$logHead = "<TREEM> ";

		$arrCasPrd = $this->modelCasinoPrd->getByCat($gameCasId);
		$arrSlotPrd = $this->modelSlotPrd->getByCat($gameSlotId);
		$objConf = $this->modelConfSite->getById($confId);
		
		$objGameCas = $this->modelConfGame->getById($gameCasId);
		$objGameSlot = $this->modelConfGame->getById($gameSlotId);

		if(is_null($arrResult))
			return false;

		if(is_null($objConf))
			return false;

		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3)	//0-host, 1-ag_code, 2-ag_token
			return false;

		$arrIdx = getHistoryDate($objConf->conf_idx);
		$arrTrans = null;
		
		if(!array_key_exists("code", $arrResult)){
			writeLog($this->fLog, $logHead." Error=No Http Code");
		} else if($arrResult['code'] == HTTP_CODE_200){ 
			$jsonData = json_decode($arrResult['body'], true);
			if(array_key_exists("data", $jsonData)){
				$arrTrans = $jsonData["data"];
			}
		} else {
			writeLog($this->fLog, $logHead." Error=".json_encode($arrResult));
		}

		if(is_null($arrTrans)) {			
			return false;
		}

		$arrCasIds = [];
		foreach ($arrCasPrd as $casPrd) {
			array_push($arrCasIds, $casPrd->key);
		}

		$arrSlotIds = [];
		foreach ($arrSlotPrd as $slotPrd) {
			array_push($arrSlotIds, $slotPrd->name);
		}

		$nBetCnt = count($arrTrans);
		writeLog($this->fLog, $logHead."Bet Count-".count($arrTrans));
		$lastIdx = "";
		if($nBetCnt == 0){

			$tmNow = time();
			$startAt = strtotime("-10 hours", $tmNow);

			if($arrIdx['idx'] < date('Y-m-d H:i:s', $startAt)){
				$lastIdx = date('Y-m-d H:i:s', strtotime("+1 hour", strtotime($arrIdx['idx'])));
				writeLog($this->fLog, $logHead."lastIdx=".$lastIdx);
			} 
		}

		$lastSlFid = 0;
		if($arrIdx['fid'] > FID_OFFSET){
			$lastSlFid = $arrIdx['fid'] - FID_OFFSET;
		}
		$lastCsFid = 0;
		if($arrIdx['fid2'] > FID_OFFSET){
			$lastCsFid = $arrIdx['fid2'] - FID_OFFSET;
		} 
		writeLog($this->fLog, $logHead."Last Fid-".$lastSlFid);

		$objMember = null;
		$arrEmpPoint = array();
		$arrMemBlank = array();
		$arrMemBet = array();
		$arrPendingRecover = [];
		
		$arrBet = [];
		$arrLiveUids = [];
		$slExist = false;
		$csExist = false;
		$logHead = "<TREEM_CHECK> ";
		foreach ($arrTrans as $bet) {
			$lastIdx = date('Y-m-d H:i:s', strtotime("-32399 second", strtotime($bet['created_at'])));

			if($bet['status'] != "success"){
				writeLog($this->fLog, $logHead.$bet['type'].">> status=".$bet['status'].", user=".$bet['user']['username']." amount=".$bet['amount'].", before=".$bet['before']);
			}
			if($bet['type'] != "bet" && $bet['type'] != "win"){
				writeLog($this->fLog, $logHead.$bet['type'].">> NotBet user=".$bet['user']['username']." amount=".$bet['amount'].", before=".$bet['before']);
				continue;
			}

			$bet['amount'] = abs($bet['amount']);
			if(!is_null($bet['details'])){
				writeLog($this->fLog, $logHead.$bet['type'].">> user=".$bet['user']['username']." amount=".$bet['amount'].", before=".$bet['before'].", id=".$bet['id']." referer_id=".$bet['referer_id']);

				if(in_array(strval($bet['details']['game']['vendor']), $arrCasIds)){
					$csExist = true;
					$bet['game_id'] = $gameCasId;
					array_push($arrBet, $bet);
					writeLog($this->fLog, $logHead.$bet['type'].">> user=".$bet['user']['username']." amount=".$bet['amount'].", before=".$bet['before'].", id=".$bet['id']." referer_id=".$bet['referer_id']);
				} else if(in_array(strval($bet['details']['game']['vendor']), $arrSlotIds)){
					$slExist = true;
					$bet['game_id'] = $gameSlotId;
					array_push($arrBet, $bet);
					writeLog($this->fLog, $logHead.$bet['type'].">> user=".$bet['user']['username']." amount=".$bet['amount'].", before=".$bet['before'].", id=".$bet['id']." referer_id=".$bet['referer_id']);
				}
			}

			if(!in_array($bet['user']['username'], $arrLiveUids))
				array_push($arrLiveUids, $bet['user']['username']);
		}
		$arrMember = $this->modelMember->getMembersByLiveUids($arrLiveUids, $gameSlotId);
		if(count($arrMember) > 0)
			writeLog($this->fLog, $logHead."Member Count-".count($arrMember));
		foreach ($arrMember as $member) {
			if($slExist)
				$member->ratio_sl = $this->modelMember->getEmployeeRatio($member, $gameSlotId);
			if($csExist)
				$member->ratio_cs = $this->modelMember->getEmployeeRatio($member, $gameCasId);
		}
		$bInsert = false;
		$rwSlLastFid = $this->modelReward->getLastFid($gameSlotId);
		writeLog($this->fLog, $logHead."Reward LastFid=".$rwSlLastFid);
		if($rwSlLastFid > FID_OFFSET*3){
			$rwSlLastFid = $rwSlLastFid - FID_OFFSET*3;
		}

		$rwCsLastFid = $this->modelReward->getLastFid(GAME_CASINO_EVOL);
		writeLog($this->fLog, $logHead."Reward LastFid=".$rwCsLastFid);
		if($rwCsLastFid > FID_OFFSET*3){
			$rwCsLastFid = $rwCsLastFid - FID_OFFSET*3;
		}


		foreach ($arrBet as $bet) {

			$bet['created_at'] = date('Y-m-d H:i:s', strtotime($bet['created_at']));

			$objMember = findMemberByLiveId($arrMember, $bet['user']['username'], $gameSlotId);
			if(is_null($objMember)){
				$logHead = "<TREEM>";
				writeLog($this->fLog, $logHead."No member-".$bet['type'].">> user=".$bet['user']['username']." amount=".$bet['amount'].", before=".$bet['before'].", id=".$bet['id']." referer_id=".$bet['referer_id']);
				continue;
			}

			$bet['agent_id'] = $arrInfo[1];			//agent code			

			if($bet['game_id'] == $gameCasId){
				$logHead = "<TREEM_CASINO> ";
				
				$objPrd = getPrdByKey($arrCasPrd, $bet['details']['game']['vendor']);
				if(is_null($objPrd))
					continue;
				writeLog($this->fLog, $logHead.$bet['type'].">> user=".$bet['user']['username']." amount=".$bet['amount'].", before=".$bet['before'].", id=".$bet['id'].", referer_id=".$bet['referer_id'].", created_at=".$bet['created_at']);

				$bet['prd_id'] = $objPrd->vendor_id;			//provider code

				if(strtolower($bet['type']) != "bet")			//청산
				{		
					$betting = null;
					if($bet['referer_id'] != null)
						$betting = $this->modelCasinoBet->getByIdx($objPrd->vendor_id, $bet['referer_id'], $lastCsFid);	//베팅내역체크
					if(is_null($betting)){
						writeLog($this->fLog, $logHead."ACC-Not Found referer_id=".$bet['referer_id']."=>".$bet['amount']);
						continue;
					}

					$betId = $betting['bet_fid'];
					if(trim($betting['bet_result']) == $bet['id']){
						writeLog($this->fLog, $logHead."ACC-Already referer_id=".$bet['referer_id']."=>".$bet['amount']);
						continue;
					}
					$this->modelCasinoBet->updateT($betId, $bet, $this->fLog);

					writeLog($this->fLog, $logHead."Update ACCId=".$betId);
					$arrMemBet[$objMember->mb_fid] = $bet['created_at'];
					
					if($bet['amount'] != $betting["bet_money"]){
						$bNeedTrans = false;
						if($objGameCas->game_percent_1 == 1){ 	//Recover Enabled
							if($objGameCas->game_percent_2 != 1){ //Win-Recover Enabled
								$bNeedTrans = true;
							} else { //Win-Recover Enabled
								if($bet['amount'] > $betting["bet_money"]){

									if($objGameCas->game_percent_3 == 1){ //PlayerWin-Recover Enabled
										if(strlen($betting["bet_spec"]) == 0 || strpos(strtolower($betting["bet_spec"]), 'player') !== false ){
											$bNeedTrans = true;
										} 
									} else {
										$bNeedTrans = true;
									}
								}
							} 
							
							writeLog($this->fLog, $logHead." referer_id=".$bet['referer_id']." bNeedTrans=".$bNeedTrans." bet_money=".$betting["bet_money"]." win_money=".$bet["amount"]." bet_spec=".$betting["bet_spec"]);
						}

						$arrEmpRatio = calcEmpPoint($objMember->ratio_cs, $betting['bet_money'], $betting['bet_time']);
						if($bNeedTrans){
							$betTotalPoint = 0;
							foreach($arrEmpRatio as $ratio){
								$betTotalPoint += $ratio['point'];
							}
							if($betTotalPoint > 1){
								$arrPendingRecover[] = [
									'betId' => $betId,
									'member_fid' => $objMember->mb_fid,
									'total_point' => $betTotalPoint,
									'arrEmpRatio' => $arrEmpRatio,
									'game_id' => GAME_CASINO_EVOL,
									'rwLastFid' => $rwCsLastFid,
								];
							}
						} else {
							$this->applyEmpRatioPoints($arrEmpPoint, $arrEmpRatio);
							$this->modelReward->insert(GAME_CASINO_EVOL, $betId, $arrEmpRatio, $rwCsLastFid);
						}
					}

				} else	//베팅
				{
					$betting = $this->modelCasinoBet->getByIdx($objPrd->vendor_id, $bet['id'], $lastCsFid);	//베팅내역체크
					if(!is_null($betting)){
						writeLog($this->fLog, $logHead."BET-Exist-".$bet['id']."=>".$bet['amount']);
						continue;
					}
					
					$betId = $this->modelCasinoBet->insertT($objMember, $bet, $this->fLog);
					writeLog($this->fLog, $logHead."Insert BetId=".$betId);
					$arrMemBet[$objMember->mb_fid] = $bet['created_at'];
					if($betId > 0){
						
						$arrIdx['fid2'] = $betId;
						$bInsert = true;
						writeLog($this->fLog, $logHead."BET-INSERT-".$bet['type']."-".$bet['id']."=>".$bet['amount']);
					}
					
				}
			} else {
				$logHead = "<TREEM_SLOT> ";

				// writeLog($this->fLog, $logHead."bet-".$bet['id']."=>".$bet['amount']);
				$bet['game_id'] = $gameSlotId;
				$objPrd = getPrdByName($arrSlotPrd, $bet['details']['game']['vendor']);
				if(is_null($objPrd))
					continue;

				$bet['prd_id'] = $objPrd->code;			//provider code
				
				if(strtolower($bet['type']) != "bet")			//청산
				{	
					$betting = null;
					if(!is_null($bet['referer_id']))
						$betting = $this->modelSlotBet->getByRslot($gameSlotId, $bet['prd_id'], $bet['referer_id'], $lastSlFid);	//베팅내역체크

					if(is_null($betting)){
						writeLog($this->fLog, $logHead."ACC-Not Found referer_id=".$bet['referer_id']."=>".$bet['amount']);
						continue;
					}

					// if(strlen(trim($betting['bet_result'])) > 0 ){
					if(trim($betting['bet_result']) == $bet['id']){
						writeLog($this->fLog, $logHead."ACC-Already referer_id=".$bet['referer_id']."=>".$bet['amount']);
						continue;
					}
					$arrMemBet[$objMember->mb_fid] = $bet['created_at'];

					$betId = $betting['bet_fid'];
					$bResult = $this->modelSlotBet->updateRslot($betId, $bet);
					writeLog($this->fLog, $logHead."Update ACCId=".$betId."=>Result-".$bResult);

					$bSlotBlank = (isset($betting['point_amount']) && intval($betting['point_amount']) == 1);
					if(!$bSlotBlank && $bet['amount'] != $betting["bet_money"]){
						$bNeedTrans = false;
						if(!is_null($objGameSlot) && $objGameSlot->game_percent_1 == 1){
							if($objGameSlot->game_percent_2 != 1){
								$bNeedTrans = true;
							} else {
								if($bet['amount'] > $betting["bet_money"]){
									if($objGameSlot->game_percent_3 == 1){
										$betSpec = isset($betting["bet_choice"]) ? $betting["bet_choice"] : "";
										if(strlen($betSpec) == 0 || strpos(strtolower($betSpec), 'player') !== false ){
											$bNeedTrans = true;
										}
									} else {
										$bNeedTrans = true;
									}
								}
							}
							writeLog($this->fLog, $logHead." referer_id=".$bet['referer_id']." bNeedTrans=".$bNeedTrans." bet_money=".$betting["bet_money"]." win_money=".$bet["amount"]);
						}

						$arrEmpRatio = calcEmpPoint($objMember->ratio_sl, $betting['bet_money'], $betting['bet_time']);
						if($bNeedTrans){
							$betTotalPoint = 0;
							foreach($arrEmpRatio as $ratio){
								$betTotalPoint += $ratio['point'];
							}
							if($betTotalPoint > 1){
								$arrPendingRecover[] = [
									'betId' => $betId,
									'member_fid' => $objMember->mb_fid,
									'total_point' => $betTotalPoint,
									'arrEmpRatio' => $arrEmpRatio,
									'game_id' => $gameSlotId,
									'rwLastFid' => $rwSlLastFid,
								];
							}
						} else {
							$this->applyEmpRatioPoints($arrEmpPoint, $arrEmpRatio);
							$this->modelReward->insert($gameSlotId, $betId, $arrEmpRatio, $rwSlLastFid);
						}
					}
				} else {								//베팅
					$betting = $this->modelSlotBet->getByRslot($gameSlotId, $bet['prd_id'], $bet['id'], $lastSlFid);	//베팅내역체크
					if(!is_null($betting)){
						writeLog($this->fLog, $logHead."BET-Exist-".$bet['id']."=>".$bet['amount']);
						continue;
					}
					
					$bBlank = false;
					$nBlankPt = 0;
					$arrEmpRatio = [];

					if($objMember->mb_blank_count > 0 && intval($objMember->mb_blank_current) >= intval($objMember->mb_blank_count)-1 ){
						$objMember->mb_blank_current = 0;
						$bBlank = true;
						$nBlankPt = calcCompPoint($objMember->ratio_sl, $bet['amount']);
						$arrMemBlank[$objMember->mb_fid] = $objMember->mb_blank_current;
						writeLog($this->fLog, $logHead."Blank Cross Id=".$objMember->mb_uid." Current=".$objMember->mb_blank_current." Comp=".$nBlankPt);
					} else {
						if($objMember->mb_blank_count > 0){
							$objMember->mb_blank_current ++;
							$arrMemBlank[$objMember->mb_fid] = $objMember->mb_blank_current;
							writeLog($this->fLog, $logHead."Blank Id=".$objMember->mb_uid." Current=".$objMember->mb_blank_current);
						}
					}

					$arrMemBet[$objMember->mb_fid] = $bet['created_at'];
						
					$betId = $this->modelSlotBet->insertTslot($objMember, $bet, $bBlank, $nBlankPt);
					writeLog($this->fLog, $logHead."Insert BetId=".$betId);
						
					if($betId > 0){
						$arrIdx['fid'] = $betId;
						if($bBlank){
							$this->modelReward->insert($gameSlotId, $betId, $arrEmpRatio, $rwSlLastFid, $bBlank);
							writeLog($this->fLog, $logHead."Insert Blank Reward");
						}
						$bInsert = true;
					}
				}

			} 
			
		}

		if(strlen($lastIdx) > 0)
			$this->modelConfSite->updateLastIdx($objConf->conf_id, $lastIdx."#".$arrIdx['fid']."#".$arrIdx['fid2']);
		writeLog($this->fLog, $logHead."lastIdx=".$lastIdx);

		$this->modelMember->updateMemberBetTm($arrMemBet);
		$bResult = $this->modelMember->updateMemberBlank($arrMemBlank);
		writeLog($this->fLog, $logHead."UpdateMemBlank-Count=".count($arrMemBlank)." Result=".$bResult);
		$this->processPendingRecoverTreem($arrPendingRecover, $arrEmpPoint, $arrMember, $arrInfo, $proxyUrl, $rwCsLastFid);
		$bResult = $this->modelMember->addEmployeePoint($arrEmpPoint);
		writeLog($this->fLog, $logHead."AddEmpPoint-Count=".count($arrEmpPoint)." Result=".$bResult);

		return $bInsert;
	}

	private function applyEmpRatioPoints(&$arrEmpPoint, $arrEmpRatio){
		foreach($arrEmpRatio as $ratio){
			if(array_key_exists($ratio['mb_fid'], $arrEmpPoint))
				$arrEmpPoint[$ratio['mb_fid']] += $ratio['point'];
			else
				$arrEmpPoint[$ratio['mb_fid']] = $ratio['point'];
		}
	}

	private function processPendingRecoverTreem($arrPendingRecover, &$arrEmpPoint, $arrMember, $arrInfo, $proxyUrl, $rwCsLastFid){
		$logHead = "<TREEM_CASINO> ";
		writeLog($this->fLog, $logHead."PendingRecover-Count=".count($arrPendingRecover));
		foreach($arrPendingRecover as $pending){
			if($pending['total_point'] <= 1)
				continue;
			$member = findMemberByFid($arrMember, $pending['member_fid']);
			if(is_null($member)){
				writeLog($this->fLog, $logHead."RecoverSkip betId=".$pending['betId']." member not found");
				continue;
			}
			if($this->tryRecoverFromMemberTreem($member, $pending['total_point'], $arrInfo, $proxyUrl, $logHead)){
				$this->applyEmpRatioPoints($arrEmpPoint, $pending['arrEmpRatio']);
				$recoverGameId = isset($pending['game_id']) ? $pending['game_id'] : GAME_CASINO_EVOL;
				$recoverRwFid = isset($pending['rwLastFid']) ? $pending['rwLastFid'] : $rwCsLastFid;
				$this->modelReward->insert($recoverGameId, $pending['betId'], $pending['arrEmpRatio'], $recoverRwFid);
				writeLog($this->fLog, $logHead."RecoverOk betId=".$pending['betId']." uid=".$member->mb_uid." point=".$pending['total_point']);
			} else {
				writeLog($this->fLog, $logHead."RecoverFail betId=".$pending['betId']." uid=".$member->mb_uid." point=".$pending['total_point']);
			}
			sleep(1);
		}
	}

	private function tryRecoverFromMemberTreem($member, $point, $arrInfo, $proxyUrl, $logHead){
		if(is_null($member) || $point <= 1)
			return false;

		if($member->mb_money >= $point){
			if($this->modelMember->updateAssets($member, 0-$point, 0, MONEYCHANGE_WITHDRAW, MONEYCHANGE_WITHDRAW_CUT))
				return true;
		}
		for($i=0; $i<3; $i++){
			$result = $this->withdrawTreemEgg($arrInfo, $member->mb_treem_uid, $point, $proxyUrl);
			writeLog($this->fLog, $logHead."TransPoint uid=".$member->mb_uid." point=".$point." result=".$result['status']);
			if($result['status'] == 1){
				if($member->mb_treem_money != $result['balance']){
					$member->mb_treem_money = $result['balance'];
					$bUpdated = $this->modelMember->updateGameMoney($member);
					writeLog($this->fLog, $logHead."uid=".$member->mb_uid.", balance=".$result['balance'].", updated=".$bUpdated);
				}
				writeLog($this->fLog, $logHead."TransPoint uid=".$member->mb_uid.", balance=".$result['balance'].", point=".$point);
				$this->modelTransfer->insertRow(RECOVER_TREEM, $member, $result['balance']+$point, 0-$point, $this->fLog);
				return true;
			}
			sleep(3);
		}
		return false;
	}

	//=============Treem withdraw ==============
	public function withdrawTreemEgg($arrAgentInfo, $uid, $amount, $proxyUrl) {	

		$url = $arrAgentInfo[0]."/user/sub-balance";
		$url.= "?username=$uid&amount=$amount";
        $post = "";

		$header =  ['Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer '.$arrAgentInfo[2]
        ];

		$logHead = "<TREEM> withdrawTreem() ";
		$curlResult = getCurlRequestWithProxy($url, $proxyUrl, $header, $post);
		$balance = -1;

		if(!is_null($curlResult) && array_key_exists("code", $curlResult)) {
			if($curlResult['code'] == HTTP_CODE_200){
                // $curlResult['body'] =>
                // "username": "test1",
                // "balance": 0,
                // "amount": -1000,
                // "transaction_id": 1,
                // "cached": false

                // "username": "e01",
                // "balance": 0,
                // "amount": 0,
                // "message": "유저의 잔액이 0원입니다.",
                // "cached": false
                $arrResult = json_decode($curlResult['body'], true);
				if($arrResult['amount'] != 0)
					$arrResult['status'] = 1;
				else 
					$arrResult['status'] = 0;
                writeLog($this->fLog, $logHead."body=".$curlResult['body']);
            } else { //
                // $curlResult['body'] =>
                // "code":403,
				// "error":"",
				// "body":"Access Denied"
                // 
                writeLog($this->fLog, $logHead."Error result=".json_encode($curlResult));
                $arrResult['status'] = 0;
            }

		} else {
            $arrResult['status'] = 0;
        }

		return $arrResult;
	}

	
	//===========Sigma================
		
	public function curlSigmaBets($isLive=true, $proxyUrl=""){
		$confId = CONF_API_SIGMA;
		$logHead = "<SIGMA> ";

		$objConf = $this->modelConfSite->getById($confId);
		
		if(is_null($objConf))
			return null;
		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3)	//0-host, 1-ag_code, 2-ag_token
			return null;

		$arrIdx = getHistory2Date($objConf->conf_idx);
		if($isLive){
			$timepoint = $arrIdx['idx2'];
			$type = "LIVE";
		} else {
			$timepoint = $arrIdx['idx'];
			$type = "SLOT";
		}

		$url = $arrInfo[0]."/transfer_v2/history?";
		$url.="site_code=".$arrInfo[1];
		$url.="&type=".$type;
		if(strlen($timepoint) > 0)
			$url.="&timepoint=".$timepoint;

		$header =  [
			'Authorization: '.$arrInfo[2]
		];
        
		writeLog($this->fLog, $logHead."url=".$url);

		return getCurlWithProxy($url, $proxyUrl, $header);
	}

	public function registerSigmaBets($isLive=true, $arrResult, $proxyUrl=""){
		$gameSlotId = GAME_SLOT_SIGMA;
		$gameCasId = GAME_CASINO_SIGMA;
		$confId = CONF_API_SIGMA;
		$logHead = "<SIGMA> ";

		$arrCasPrd = $this->modelCasinoPrd->getByCat($gameCasId);
		$arrSlotPrd = $this->modelSlotPrd->getByCat($gameSlotId);
		$objConf = $this->modelConfSite->getById($confId);
		
		$objGameCas = $this->modelConfGame->getById($gameCasId);

		if(is_null($arrResult))
			return false;

		if(is_null($objConf))
			return false;

		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3)	//0-host, 1-ag_code, 2-ag_token
			return false;

		$arrIdx = getHistory2Date($objConf->conf_idx);
		$arrBet = null;
		$nextTime = "";
		if(!array_key_exists("code", $arrResult)){
			writeLog($this->fLog, $logHead." Error=No Http Code");
		} else if($arrResult['code'] == HTTP_CODE_200){ 
			// "result": "OK",
			// "next_timepoint": "2025-11-06T10%3A20%3A28%2B09%3A00",
			// "data": [
			// ]
			$jsonData = json_decode($arrResult['body'], true);
			if($jsonData['result'] == "OK"){
				$nextTime = $jsonData["next_timepoint"];
				if($isLive)
					$arrIdx['idx2'] = $nextTime;
				else 
					$arrIdx['idx'] = $nextTime;

				$arrBet = $jsonData["data"];
			}
			
		} else {
			writeLog($this->fLog, $logHead." Error=".json_encode($arrResult));
		}

		if(is_null($arrBet)) {			
			return false;
		}

		$arrCasIds = [];
		foreach ($arrCasPrd as $casPrd) {
			array_push($arrCasIds, $casPrd->key);
		}

		$arrSlotIds = [];
		foreach ($arrSlotPrd as $slotPrd) {
			array_push($arrSlotIds, $slotPrd->name);
		}

		$nBetCnt = count($arrBet);
		writeLog($this->fLog, $logHead."Bet Count-".count($arrBet));
		
		$lastSlFid = 0;
		if($arrIdx['fid'] > FID_OFFSET){
			$lastSlFid = $arrIdx['fid'] - FID_OFFSET;
		}
		$lastCsFid = 0;
		if($arrIdx['fid2'] > FID_OFFSET){
			$lastCsFid = $arrIdx['fid2'] - FID_OFFSET;
		} 
		writeLog($this->fLog, $logHead."Last SlFid=".$lastSlFid." CsFid=".$lastCsFid);

		$objMember = null;
		$arrEmpPoint = array();
		$arrMemBlank = array();
		$arrMemBet = array();
		$arrPendingRecover = [];
		$arrLiveUids = [];
		$slExist = !$isLive;
		$csExist = $isLive;
		foreach ($arrBet as $bet) {
			if(!in_array($bet['user_id'], $arrLiveUids))
				array_push($arrLiveUids, $bet['user_id']);
		}
		$arrMember = $this->modelMember->getMembersByLiveUids($arrLiveUids, $gameSlotId);
		if(count($arrMember) > 0)
			writeLog($this->fLog, $logHead."***Member Count-".count($arrMember));
		foreach ($arrMember as $member) {
			if($slExist)
				$member->ratio_sl = $this->modelMember->getEmployeeRatio($member, $gameSlotId);
			if($csExist)
				$member->ratio_cs = $this->modelMember->getEmployeeRatio($member, $gameCasId);
		}
		$bInsert = false;
		$rwSlLastFid = $this->modelReward->getLastFid($gameSlotId);
		writeLog($this->fLog, $logHead."Reward LastFid=".$rwSlLastFid);
		if($rwSlLastFid > FID_OFFSET*3){
			$rwSlLastFid = $rwSlLastFid - FID_OFFSET*3;
		}

		$rwCsLastFid = $this->modelReward->getLastFid(GAME_CASINO_EVOL);
		writeLog($this->fLog, $logHead."Reward LastFid2=".$rwCsLastFid);
		if($rwCsLastFid > FID_OFFSET*3){
			$rwCsLastFid = $rwCsLastFid - FID_OFFSET*3;
		}

		foreach ($arrBet as $bet) {

			$objMember = findMemberByLiveId($arrMember, $bet['user_id'], $gameSlotId);
			if(is_null($objMember)){
				writeLog($this->fLog, $logHead."No member user_id=".$bet['user_id'].", vendorCode=".$bet['vendorCode'].", transaction_id=".$bet['transaction_id']);
				continue;
			}
			
			if($bet['cancel'] == 1){
				writeLog($this->fLog, $logHead."Cancel user_id=".$bet['user_id'].", vendorCode=".$bet['vendorCode'].", transaction_id=".$bet['transaction_id']);
			}

			if( in_array(strval($bet['vendorCode']), $arrCasIds)){
				$bet['gameCategory'] = "casino";
				$objPrd = getPrdByKey($arrCasPrd, $bet['vendorCode']);
				$bet['prd_id'] = $objPrd->vendor_id;					
			} else if(in_array(strval($bet['vendorCode']), $arrSlotIds)){
				$bet['gameCategory'] = "slot";
				$objPrd = getPrdByName($arrSlotPrd, $bet['vendorCode']);
				$bet['prd_id'] = $objPrd->code;					
			} else {
				writeLog($this->fLog, $logHead."No Category user_id=".$bet['user_id'].", vendorCode=".$bet['vendorCode'].", transaction_id=".$bet['transaction_id']);
				continue;
			}
			
			$bet['agent_id'] = $arrInfo[1];			//agent code			
			if($bet['gameCategory'] == "casino"){
				
				$betting = $this->modelCasinoBet->getByIdx($objPrd->vendor_id, $bet['transaction_id'], $lastCsFid);	//베팅내역체크
				if(!is_null($betting))
					continue;

				$logHead = "<SIGMA> CAS>> ";

				$betId = $this->modelCasinoBet->insertS($objMember, $bet, $this->fLog);
				writeLog($this->fLog, $logHead."Insert BetId=".$betId);
				$arrMemBet[$objMember->mb_fid] = $bet['startDate'];

				if($betId > 0 && $bet['bet'] != $bet['win']){
					$bNeedTrans = false;
					if($objGameCas->game_percent_1 == 1){ 	//Recover Enabled
						if($objGameCas->game_percent_2 != 1){ //Win-Recover Enabled
							$bNeedTrans = true;
						} else { //Win-Recover Enabled

							if($objGameCas->game_percent_3 == 1){ //PlayerWin-Recover Enabled
								if(strlen($bet["betting_data"]) == 0 || strpos(strtolower($bet["betting_data"]), 'player') !== false ){
									$bNeedTrans = true;
								} 
							} else {
								$bNeedTrans = true;
							}
						} 
						
						writeLog($this->fLog, $logHead." transaction_id=".$bet['transaction_id']." bNeedTrans=".$bNeedTrans." bet=".$bet["bet"]." win=".$bet["win"]);
					}
					
					$arrIdx['fid2'] = $betId;
					$bInsert = true;
					writeLog($this->fLog, $logHead."INSERT user_id=".$objMember->mb_uid.", vendorCode=".$bet['vendorCode'].", transaction_id=".$bet['transaction_id']);

					$arrEmpRatio = calcEmpPoint($objMember->ratio_cs, $bet['bet'], $bet['startDate']);
					if($bNeedTrans){
						$betTotalPoint = 0;
						foreach($arrEmpRatio as $ratio){
							$betTotalPoint += $ratio['point'];
						}
						if($betTotalPoint > 1){
							$arrPendingRecover[] = [
								'betId' => $betId,
								'member_fid' => $objMember->mb_fid,
								'total_point' => $betTotalPoint,
								'arrEmpRatio' => $arrEmpRatio,
							];
						}
					} else {
						$this->applyEmpRatioPoints($arrEmpPoint, $arrEmpRatio);
						$this->modelReward->insert(GAME_CASINO_EVOL, $betId, $arrEmpRatio, $rwCsLastFid);
					}
				}

			} else {
				$logHead = "<SIGMA> SLOT>> ";
				
				$betting = $this->modelSlotBet->getByRslot($gameSlotId, $bet['prd_id'], $bet['transaction_id'], $lastSlFid);	//베팅내역체크
				if(!is_null($betting))
					continue;

				$bBlank = false;
				$nBlankPt = 0;

				$arrEmpRatio = calcEmpPoint($objMember->ratio_sl, $bet['bet'], $bet['startDate']);
				if($objMember->mb_blank_count > 0 && intval($objMember->mb_blank_current) >= intval($objMember->mb_blank_count)-1 ){
					$objMember->mb_blank_current = 0;
					$bBlank = true;
					$nBlankPt = calcCompPoint($objMember->ratio_sl, $bet['bet']);
					$arrMemBlank[$objMember->mb_fid] = $objMember->mb_blank_current;
					writeLog($this->fLog, $logHead."Blank Cross Id=".$objMember->mb_uid." Current=".$objMember->mb_blank_current." Comp=".$nBlankPt);
				} else {
					foreach($arrEmpRatio as $ratio){
						
						if(array_key_exists($ratio['mb_fid'], $arrEmpPoint ))
							$arrEmpPoint[$ratio['mb_fid']] += $ratio['point'];
						else 
							$arrEmpPoint[$ratio['mb_fid']] = $ratio['point'];	
						
						// if(array_key_exists($objMember->mb_fid, $arrTransMember ))
						// 	$arrTransMember[$objMember->mb_fid] += $ratio['point'];
						// else 
						// 	$arrTransMember[$objMember->mb_fid] = $ratio['point'];
					}
					if($objMember->mb_blank_count > 0){
						$objMember->mb_blank_current ++;
						$arrMemBlank[$objMember->mb_fid] = $objMember->mb_blank_current;
						writeLog($this->fLog, $logHead."Blank Id=".$objMember->mb_uid." Current=".$objMember->mb_blank_current);
					}
				}
				$bet['game_id'] = $gameSlotId;
				$arrMemBet[$objMember->mb_fid] = $bet['startDate'];

				$betId = $this->modelSlotBet->insertSslot($objMember, $bet, $bBlank, $nBlankPt);
				if($betId > 0){
					$this->modelReward->insert($gameSlotId, $betId, $arrEmpRatio, $rwSlLastFid, $bBlank);
					$bInsert = true;
					$arrIdx['fid'] = $betId;
				}
				writeLog($this->fLog, $logHead."Insert BetId=".$betId);
			}
		}

		if(strlen($nextTime) > 0)
			$this->modelConfSite->updateLastIdx($objConf->conf_id, $arrIdx['idx']."#".$arrIdx['fid']."#".$arrIdx['idx2']."#".$arrIdx['fid2']);
		writeLog($this->fLog, $logHead."nextTime=".$nextTime);

		$this->modelMember->updateMemberBetTm($arrMemBet);
		$bResult = $this->modelMember->updateMemberBlank($arrMemBlank);
		writeLog($this->fLog, $logHead."UpdateMemBlank-Count=".count($arrMemBlank)." Result=".$bResult);
		$this->processPendingRecoverSigma($arrPendingRecover, $arrEmpPoint, $arrMember, $arrInfo, $proxyUrl, $rwCsLastFid);
		$bResult = $this->modelMember->addEmployeePoint($arrEmpPoint);
		writeLog($this->fLog, $logHead."AddEmpPoint-Count=".count($arrEmpPoint)." Result=".$bResult);

		return $bInsert;
	}

	private function processPendingRecoverSigma($arrPendingRecover, &$arrEmpPoint, $arrMember, $arrInfo, $proxyUrl, $rwCsLastFid){
		$logHead = "<SIGMA> CAS>> ";
		writeLog($this->fLog, $logHead."PendingRecover-Count=".count($arrPendingRecover));
		foreach($arrPendingRecover as $pending){
			if($pending['total_point'] <= 1)
				continue;
			$member = findMemberByFid($arrMember, $pending['member_fid']);
			if(is_null($member)){
				writeLog($this->fLog, $logHead."RecoverSkip betId=".$pending['betId']." member not found");
				continue;
			}
			if($this->tryRecoverFromMemberSigma($member, $pending['total_point'], $arrInfo, $proxyUrl, $logHead)){
				$this->applyEmpRatioPoints($arrEmpPoint, $pending['arrEmpRatio']);
				$this->modelReward->insert(GAME_CASINO_EVOL, $pending['betId'], $pending['arrEmpRatio'], $rwCsLastFid);
				writeLog($this->fLog, $logHead."RecoverOk betId=".$pending['betId']." uid=".$member->mb_uid." point=".$pending['total_point']);
			} else {
				writeLog($this->fLog, $logHead."RecoverFail betId=".$pending['betId']." uid=".$member->mb_uid." point=".$pending['total_point']);
			}
			sleep(1);
		}
	}

	private function tryRecoverFromMemberSigma($member, $point, $arrInfo, $proxyUrl, $logHead){
		if(is_null($member) || $point <= 1)
			return false;

		if($member->mb_money >= $point){
			if($this->modelMember->updateAssets($member, 0-$point, 0, MONEYCHANGE_WITHDRAW, MONEYCHANGE_WITHDRAW_CUT))
				return true;
		}
		for($i=0; $i<3; $i++){
			$result = $this->withdrawSigmaEgg($arrInfo, $member->mb_sigma_uid, $point, $proxyUrl);
			writeLog($this->fLog, $logHead."TransPoint uid=".$member->mb_uid." point=".$point." result=".$result['status']);
			if($result['status'] == 1){
				if($member->mb_sigma_money != $result['balance']){
					$member->mb_sigma_money = $result['balance'];
					$bUpdated = $this->modelMember->updateGameMoney($member);
					writeLog($this->fLog, $logHead."uid=".$member->mb_uid.", balance=".$result['balance'].", updated=".$bUpdated);
				}
				writeLog($this->fLog, $logHead."TransPoint uid=".$member->mb_uid.", balance=".$result['balance'].", point=".$point);
				$this->modelTransfer->insertRow(RECOVER_TREEM, $member, $result['balance']+$point, 0-$point, $this->fLog);
				return true;
			}
			sleep(3);
		}
		return false;
	}
	
	//=============Sigma withdraw ==============
	public function withdrawSigmaEgg($arrAgentInfo, $uid, $amount, $proxyUrl) {	

        $extId = generateExtId();
		$url = $arrAgentInfo[0]."/transfer_v2/withdraw?";
		$url.="site_code=".$arrAgentInfo[1];
		$url.= "&user_id=$uid&amount=$amount";
        $url.= "&external_id=$extId";

		$header =  [
            'Authorization: '.$arrAgentInfo[2]
        ];

		$logHead = "<TREEM> withdrawSigma() ";
		$curlResult = getCurlRequestWithProxy($url, $proxyUrl, $header);
		$balance = -1;

		if(!is_null($curlResult) && array_key_exists("code", $curlResult)) {
			if($curlResult['code'] == HTTP_CODE_200){
                // "result": "OK",
                // "status": 0,
                // "amount": "1000",
                // "balance": "9000",
                // "site_balance": 891101,
                // "transfer_id": 117

            // "result": "Error",
                // "status": 1,
                // "msg": "Not enough balance",
                // "amount": "10000",
                // "balance": 0,
                // "site_balance": "890101",
                // "transfer_id": 116
                $arrResult = json_decode($curlResult['body'], true);
				if($arrResult['result'] == "OK" && $arrResult['amount'] != 0)
                    $arrResult['status'] = 1;
                else {
                    writeLog($logHead."Error result=".$curlResult['body']);
                    $arrResult['status'] = 0;
                }
                $balance = $arrResult['balance'];
                writeLog($this->fLog, $logHead."body=".$curlResult['body']);
            } else { //
                // $curlResult['body'] =>
                // "code":403,
				// "error":"",
				// "body":"Access Denied"
                // 
                writeLog($this->fLog, $logHead."Error result=".json_encode($curlResult));
                $arrResult['status'] = 0;
            }

		} else {
            $arrResult['status'] = 0;
        }

		return $arrResult;
	}

}


?>
