<?php

class CasinoBet_Model {

	private $mDbConn ;
	private $mTableName ;
	private $mMemberTable;

	function __construct($dbConn)
	{
		$this->mDbConn = $dbConn;
		$this->mTableName = "bet_casino";
		$this->mMemberTable = "member";

	}

	public function getByIdx($gameId, $idx, $fid = 0){

		$strSql = "SELECT * FROM ".$this->mTableName;
    	$strSql.= " WHERE bet_fid >= '".$fid."' ";
    	$strSql.= " AND bet_game_id = '".$gameId."' ";
    	$strSql.= " AND bet_idx = '".$idx."' ";

    	$arrData = null;
    	if($objResult = $this->mDbConn->query($strSql)){
	    	if ($objResult->num_rows > 0) {
			  	while($arrRow = $objResult->fetch_assoc()) {
			    	$arrData = $arrRow;
			    	break;
		  		}
			}
			$objResult->free();
		}

		return $arrData;
    }

	public function getLastIdx(){
		// SELECT bet_idx FROM bet_casino ORDER BY bet_fid DESC LIMIT 1
        $strSql = "SELECT bet_idx FROM ".$this->mTableName;
    	$strSql.= " ORDER BY bet_fid DESC LIMIT 1 ";
    	
    	$arrData = null;
    	if($objResult = $this->mDbConn->query($strSql)){
	    	if ($objResult->num_rows > 0) {
			  	while($arrRow = $objResult->fetch_assoc()) {
			    	$arrData = $arrRow;
			    	break;
		  		}
			}
			$objResult->free();
		}

		if(!is_null($arrData)){
			return $arrData['bet_idx'];
		}
		return 0;
    }

	
	public function getLast(){
		// SELECT bet_idx FROM bet_casino ORDER BY bet_fid DESC LIMIT 1
        $strSql = "SELECT * FROM ".$this->mTableName;
    	$strSql.= " ORDER BY bet_fid DESC LIMIT 1 ";
    	
    	$arrData = null;
    	if($objResult = $this->mDbConn->query($strSql)){
	    	if ($objResult->num_rows > 0) {
			  	while($arrRow = $objResult->fetch_assoc()) {
			    	$arrData = $arrRow;
			    	break;
		  		}
			}
			$objResult->free();
		}

		return $arrData;
		
    }

	
	public function getByBet($bet, $fid = 0){

		$strSql = "SELECT * FROM ".$this->mTableName;
    	$strSql.= " WHERE bet_fid >= '".$fid."' ";
    	$strSql.= " AND bet_idx = '".$bet['txn_id']."' ";

    	$arrData = null;
    	if($objResult = $this->mDbConn->query($strSql)){
	    	if ($objResult->num_rows > 0) {
			  	while($arrRow = $objResult->fetch_assoc()) {
			    	$arrData = $arrRow;
			    	break;
		  		}
			}
			$objResult->free();
		}

		return $arrData;
    }

	
    public function insert($objMember, $bet){
    	
		// $strSql = "INSERT IGNORE INTO ".$this->mTableName." (bet_idx, bet_emp_fid, bet_mb_fid, bet_mb_uid, bet_round_no, bet_time, ";
		$strSql = "INSERT IGNORE INTO ".$this->mTableName." (bet_idx, bet_emp_fid, bet_mb_uid, bet_round_no, bet_time, ";
		$strSql.= " bet_money, bet_win_money, bet_agent_id, bet_player_id, bet_game_id, bet_game_type, bet_table_code, ";
		$strSql.= " bet_choice, bet_result, obj_id ) VALUES "; 
		
		//bet_idx
		$strSql.= " ( '".$bet['txn_id']."',";
		//bet_emp_fid
		$strSql.= " '".$objMember->mb_emp_fid."', ";
		//bet_mb_fid
		// $strSql.= " '".$objMember->mb_fid."', ";
		//bet_mb_uid
		$strSql.= " '".$objMember->mb_uid."', ";
		//bet_round_no
		$strSql.= " '".$bet['game_id']."', ";
		//bet_time
		$strSql.= " '".utcToLocal($bet['created_at'])."', "; //UTC "2022-03-15 08:30:27"		
		if($bet['type'] == 1){	//정산
			//bet_money
			$strSql.= " '0', ";
			//bet_win_money
			$strSql.= " '".$bet['amount']."', ";
		} else {				//베팅
			//bet_money
			$strSql.= " '".$bet['amount']."', ";
			//bet_win_money
			$strSql.= " '0', ";

		}
		//bet_agent_id
		$strSql.= " '".$bet['agent_id']."', "; 
		//bet_player_id
		$strSql.= " '".$bet['user_id']."', ";
		//bet_game_id
		$strSql.= " '0', "; //vendor : 에볼루션 카지노
		//bet_game_type
		$strSql.= " '".$bet['prd_type']."', "; //
		//bet_table_code
		$strSql.= " '".$bet['table_id']."', "; //게임방코드
		
		if($bet['type'] == 1){	//정산
			//bet_result
			$strSql.= " '', '".$bet['player_cards']."|".$bet['banker_cards']."', ";
		} else {
			//bet_choice
			if(strlen($bet['cause']) < 1)
				$bet['cause'] = "|";
			$strSql.= " '".$bet['cause']."', '', ";
		}
		//ObjectID
		if(array_key_exists('txn_no', $bet))
		{
			$strSql.= " '".$bet['txn_no']."' ";
		} else {
			$strSql.= " 0 ";
		}
		$strSql.= " )";

        try{

			if ($this->mDbConn->query($strSql) === TRUE) 
				return $this->mDbConn->insert_id;
		} catch(mysqli_sql_exception $exception){
			return 0;
		}
		return 0;
    } 
    

	public function update($fid, $bet){

		$strSql = "UPDATE ".$this->mTableName." SET ";	
		if($bet['type'] == 1){
        	$strSql.= " bet_win_money = '".$bet['amount']."' ";
			// if($bet['player_cards'] != "" || $bet['banker_cards'] != "")
			$strSql.= ", bet_result = '".$bet['player_cards']."|".$bet['banker_cards']."' ";
		}			//정산
		else{ 							//베팅
			if(strlen($bet['cause']) < 1)
				$bet['cause'] = "|";
			$strSql.= " bet_money = '".$bet['amount']."' ";
			$strSql.= ", bet_choice = '".$bet['cause']."' ";
		}
		
        $strSql.= " WHERE bet_fid = '".$fid."' ";

		return $this->mDbConn->query($strSql);
	}

	
    public function insertK($objMember, $bet){
    	
		// $strSql = "INSERT IGNORE INTO ".$this->mTableName." (bet_idx, bet_emp_fid, bet_mb_fid, bet_mb_uid, bet_round_no, bet_time, ";
		$strSql = "INSERT IGNORE INTO ".$this->mTableName." (bet_idx, bet_emp_fid, bet_mb_uid, bet_round_no, bet_time, ";
		$strSql.= " bet_money, bet_win_money, bet_agent_id, bet_player_id, bet_game_id, bet_game_type, bet_table_code, ";
		$strSql.= " bet_choice ) VALUES "; 
		
		//bet_idx
		$strSql.= " ( '".$bet['_id']."',";
		//bet_emp_fid
		$strSql.= " '".$objMember->mb_emp_fid."', ";
		//bet_mb_fid
		// $strSql.= " '".$objMember->mb_fid."', ";
		//bet_mb_uid
		$strSql.= " '".$objMember->mb_uid."', ";
		//bet_round_no
		$strSql.= " '".$bet['gameName']."', ";	//"Speed Baccarat 1"
		//bet_time		
		$strSql.= " '".$bet['createdAt']."', ";		// "createdAt": "2022-05-15 01:11:49",
        											// "utcCreatedAt": "2022-05-14T16:11:49.366Z"
		//bet_money
		$strSql.= " '".$bet['cash']."', ";
		//bet_win_money
		$strSql.= " '0', ";
		//bet_agent_id
		$strSql.= " '".$bet['agent_id']."', "; 
		//bet_player_id
		$strSql.= " '".$bet['user_id']."', ";
		//bet_game_id
		$strSql.= " '".$bet['vendorId']."', ";	//2-프라그마틱 카지노
		//bet_game_type
		$strSql.= " '".$bet['gameType']."', ";	//"baccarat"
		//bet_table_code
		$strSql.= " '".$bet['gameId']."', ";	//386-"Speed Baccarat 1"
		//bet_choice
		$strSql.= " '".$bet['beforeCash']."' ";
		$strSql.= " )";

		try{
			if ($this->mDbConn->query($strSql) === TRUE) 
				return $this->mDbConn->insert_id;
		} catch(mysqli_sql_exception $exception){
		}
		return 0;
    } 
    

	public function updateK($fid, $bet){

		$bUpdate = false;
		if(array_key_exists('detail', $bet) && $bet['detail'] ) {
			$bUpdate = $this->updateKSpec($fid, $bet);
		} 

		if($bUpdate)
			return true;

		$strSql = "UPDATE ".$this->mTableName." SET ";	
	
		//bet_round_no
		$strSql.= " bet_round_no ='".$bet['gameName']."', ";	//"Speed Baccarat 1"
		if($bet['type'] == "turn_win" || $bet['type'] == "turn_draw"){ 
			$strSql.= "  bet_win_money = '".$bet['cash']."', ";
		}
		//bet_result
		$strSql.= " bet_result = '".$bet['afterCash']."'";

		$strSql.= " WHERE bet_fid = '".$fid."' ";

		return $this->mDbConn->query($strSql);
		
	}

	
	public function updateKSpec($fid, $bet){

		$betSpec = "";
		if(array_key_exists('detail', $bet) && !is_null($bet['detail']) ) {

			if(array_key_exists('participants', $bet['detail']) ) {
				if(is_array($bet['detail']['participants']) && count($bet['detail']['participants']) > 0 && array_key_exists('bets', $bet['detail']['participants'][0]) ){
					foreach($bet['detail']['participants'][0]['bets'] as $detail){
						$betSpec.= $detail['code'].",".$detail['stake'].",";
						$betSpec.= $detail['payout']."#";
					}
				} 
			} else if(array_key_exists('betDetail', $bet['detail'])){
				$arrDetail = json_decode($bet['detail']['betDetail'], true);
				if(!is_null($arrDetail) && is_array($arrDetail)) {
					$arrBet = [];
					foreach(array_keys($arrDetail) as $key){

						if(count($arrBet) > 0 && in_array($key, array_keys($arrBet))){
							$arrBet[$key] .= ",".strval($arrDetail[$key]);
						} else {
							$arrBet[$key."W"] = strval($arrDetail[$key]);
						}
					}
					foreach(array_keys($arrBet) as $key){
						$side = str_replace("W", "", $key); 
						$betSpec.= $side.",".$arrBet[$key]."#";
					}
				}
			}
		} 

		if(strlen($betSpec) < 1)
			return false;

		$strSql = "UPDATE ".$this->mTableName." SET ";	
		
		//bet_round_no
		$strSql.= " bet_round_no ='".$bet['gameName']."', ";	//"Speed Baccarat 1"
		if($bet['type'] == "turn_win" || $bet['type'] == "turn_draw"){ 
        	$strSql.= "  bet_win_money = '".$bet['cash']."', ";
		}
		
		//bet_game_type
		$strSql.= " bet_game_type = '".$bet['gameType']."', ";
		//bet_table_code
		$strSql.= " bet_table_code = '".$bet['gameId']."', ";
		//bet_result
		if($bet['type'] == "turn_win" || $bet['type'] == "turn_draw"){ 
			$strSql.= " bet_result = '".$bet['afterCash']."', ";
		}
		// bet_spec
		$strSql.= " bet_spec = '".$betSpec."'";

        $strSql.= " WHERE bet_fid = '".$fid."' ";

		return $this->mDbConn->query($strSql);
	}

	public function insertH($objMember, $bet){
    	
		$strSql = "INSERT IGNORE INTO ".$this->mTableName." (bet_idx, bet_emp_fid, bet_mb_uid, bet_round_no, bet_time, ";
		$strSql.= " bet_money, bet_win_money, bet_agent_id, bet_player_id, bet_game_id, bet_game_type, bet_table_code, ";
		$strSql.= " bet_result ) VALUES "; 
		
		//bet_idx
		$strSql.= " ( '".$bet['transactionID']."',";
		//bet_emp_fid
		$strSql.= " '".$objMember->mb_emp_fid."', ";
		//bet_mb_uid
		$strSql.= " '".$objMember->mb_uid."', ";
		//bet_round_no
		$strSql.= " '".$bet['gameName']."', ";	//roundKey"Speed Baccarat 1"
		//bet_time		
		$strSql.= " '".$bet['regdate']."', ";		// "createdAt": "2022-05-15 01:11:49",
		//bet_money
		$strSql.= " '".$bet['bet']."', ";
		//bet_win_money
		$strSql.= " '".$bet['win']."', ";
		//bet_agent_id
		$strSql.= " '".$bet['agent_id']."', "; 
		//bet_player_id
		$strSql.= " '".$bet['playerID']."', ";
		//bet_game_id
		$strSql.= " '".$bet['prd_id']."', ";	//2-프라그마틱 카지노
		//bet_game_type
		$strSql.= " 'baccarat', ";	//"baccarat"
		//bet_table_code
		$strSql.= " '".$bet['gameName']."', ";	//386-"Speed Baccarat 1"
		//bet_result
		if(array_key_exists('balanceAfter', $bet))
			$strSql.= " '".$bet['balanceAfter']."' ";
		else $strSql.= " '' ";
		$strSql.= " )";

		
		if ($this->mDbConn->query($strSql) === TRUE) {
			return $this->mDbConn->insert_id;
		}
		return 0;
    } 


	public function insertR($objMember, $bet){
    	
		$strSql = "INSERT IGNORE INTO ".$this->mTableName." (bet_idx, bet_emp_fid, bet_mb_uid, bet_round_no, bet_time, ";
		$strSql.= " bet_money, bet_agent_id, bet_player_id, bet_game_id, bet_game_type, bet_table_code, ";
		$strSql.= " bet_result ) VALUES "; 
		
		//bet_idx
		$strSql.= " ( '".$bet['id']."',";
		//bet_emp_fid
		$strSql.= " '".$objMember->mb_emp_fid."', ";
		//bet_mb_uid
		$strSql.= " '".$objMember->mb_uid."', ";
		//bet_round_no
		$strSql.= " '".$bet['game']['title']."', ";	//roundKey"Speed Baccarat 1"
		//bet_time		
		$strSql.= " '".$bet['betAt']."', ";		// "createdAt": "2022-05-15 01:11:49",
		//bet_money
		$strSql.= " '".$bet['amount']."', ";
		// //bet_win_money
		// $strSql.= " '".$bet['win']."', ";
		//bet_agent_id
		$strSql.= " '".$bet['agent_id']."', "; 
		//bet_player_id
		$strSql.= " '".$bet['user']['id']."', ";
		//bet_game_id
		$strSql.= " '".$bet['prd_id']."', ";	//pragmatic
		//bet_game_type
		$strSql.= " '".$bet['game']['type']."', ";	//"baccarat"
		//bet_table_code
		$strSql.= " '".$bet['game']['code']."', ";	//386-"Speed Baccarat 1"
		//bet_result
		$strSql.= " '' ";
		$strSql.= " )";

		
		if ($this->mDbConn->query($strSql) === TRUE) {
			return $this->mDbConn->insert_id;
		}
		return 0;
    } 

	public function updateR($fid, $bet, $fLog=null){

		$betSpec = "";
		if(array_key_exists('detail', $bet)) {

			$betDetail = null;
			try{
				$betDetail = json_decode($bet['detail'], true);
				if(array_key_exists('detail', $betDetail) && array_key_exists('data', $betDetail['detail'])){
					$betDetail = $betDetail['detail']['data'];
					writeLog($fLog, "detail-1");
				} else $betDetail = null;


			} catch (Exception $e) {
				$betDetail = null;
			}

			if($betDetail == null){
				$betSpec = "";
			} else if(array_key_exists('participants', $betDetail)) {
				writeLog($fLog, "detail-2");
				if(is_array($betDetail['participants']) && count($betDetail['participants']) > 0 && array_key_exists('bets', $betDetail['participants'][0]) ){
					foreach($betDetail['participants'][0]['bets'] as $detail){
						$betSpec.= $detail['code'].",".$detail['stake'].",";
						$betSpec.= $detail['payout']."#";
					}
				} 
			} 
			if(strlen($betSpec) < 1 && !is_null($fLog)){
				writeLog($fLog, "detail Error=".$bet['detail']);
			}
		} 

		$strSql = "UPDATE ".$this->mTableName." SET ";	
	
		//bet_round_no
		$strSql.= " bet_round_no ='".$bet['game']['title']."', ";	//"Speed Baccarat 1"
		if($bet['amount'] > 0){ 
			$strSql.= "  bet_win_money = '".$bet['amount']."', ";
		}
		//bet_result
		$strSql.= " bet_result = '".$bet['id']."' ";
		if(strlen($betSpec) > 0){
			// bet_spec
			$strSql.= ", bet_spec = '".$betSpec."'";
		}

		$strSql.= " WHERE bet_fid = '".$fid."' ";

		return $this->mDbConn->query($strSql);
		
	}

	public function insertT($objMember, $bet, $fLog=null){
    	
		$betSpec = "";
		if(array_key_exists('external', $bet)) {

			$betDetail = null;
			try{
				if(array_key_exists('detail', $bet['external']) && !is_null($bet['external']['detail']) && array_key_exists('data', $bet['external']['detail'])){
					$betDetail = $bet['external']['detail']['data'];
					// writeLog($fLog, "detail-1");
				} else $betDetail = null;


			} catch (Exception $e) {
				$betDetail = null;
			}

			if($betDetail == null){
				$betSpec = "";
			} else if(array_key_exists('participants', $betDetail)) {
				// writeLog($fLog, "detail-2");
				if(is_array($betDetail['participants']) && count($betDetail['participants']) > 0 && array_key_exists('bets', $betDetail['participants'][0]) ){
					foreach($betDetail['participants'][0]['bets'] as $detail){
						$betSpec.= $detail['code'].",".$detail['stake'].",";
						$betSpec.= $detail['payout']."#";
					}
				} 
			} 
			if(strlen($betSpec) < 1 && !is_null($fLog)){
				// writeLog($fLog, "detail Error=".json_encode($bet['external']));
			}
		}

		$strSql = "INSERT IGNORE INTO ".$this->mTableName." (bet_idx, bet_emp_fid, bet_mb_uid, bet_round_no, bet_time, ";
		$strSql.= " bet_money, bet_agent_id, bet_player_id, bet_game_id, bet_game_type, bet_table_code, ";
		$strSql.= " bet_spec ) VALUES "; 
		
		//bet_idx
		$strSql.= " ( '".$bet['id']."',";
		//bet_emp_fid
		$strSql.= " '".$objMember->mb_emp_fid."', ";
		//bet_mb_uid
		$strSql.= " '".$objMember->mb_uid."', ";
		//bet_round_no
		$strSql.= " '".$bet['details']['game']['title']."', ";	//roundKey"Speed Baccarat 1"
		//bet_time		
		$strSql.= " '".$bet['created_at']."', ";		// "createdAt": "2022-05-15 01:11:49",
		//bet_money
		$strSql.= " '".$bet['amount']."', ";
		//bet_agent_id
		$strSql.= " '".$bet['agent_id']."', "; 
		//bet_player_id
		$strSql.= " '".$bet['user']['username']."', ";
		//bet_game_id
		$strSql.= " '".$bet['prd_id']."', ";	//pragmatic
		//bet_game_type
		$strSql.= " '".$bet['details']['game']['type']."', ";	//"baccarat"
		//bet_table_code
		$strSql.= " '".$bet['details']['game']['id']."', ";	//386-"Speed Baccarat 1"
		//bet_spec
		$strSql.= " '".$betSpec."' ";
		$strSql.= " )";

		
		if ($this->mDbConn->query($strSql) === TRUE) {
			return $this->mDbConn->insert_id;
		}
		return 0;
    } 

	public function updateT($fid, $bet, $fLog=null){

		$betSpec = "";
		if(array_key_exists('external', $bet)) {

			$betDetail = null;
			try{
				if(array_key_exists('detail', $bet['external']) && !is_null($bet['external']['detail']) && array_key_exists('data', $bet['external']['detail'])){
					$betDetail = $bet['external']['detail']['data'];
					// writeLog($fLog, "detail-1");
				} else $betDetail = null;


			} catch (Exception $e) {
				$betDetail = null;
			}

			if($betDetail == null){
				$betSpec = "";
			} else if(array_key_exists('participants', $betDetail)) {
				// writeLog($fLog, "detail-2");
				if(is_array($betDetail['participants']) && count($betDetail['participants']) > 0 && array_key_exists('bets', $betDetail['participants'][0]) ){
					foreach($betDetail['participants'][0]['bets'] as $detail){
						$betSpec.= $detail['code'].",".$detail['stake'].",";
						$betSpec.= $detail['payout']."#";
					}
				} 
			} 
			if(strlen($betSpec) < 1 && !is_null($fLog)){
				// writeLog($fLog, "detail Error=".json_encode($bet['external']));
			}
		}
		$strSql = "UPDATE ".$this->mTableName." SET ";	
	
		//bet_round_no
		$strSql.= " bet_round_no ='".$bet['details']['game']['title']."', ";	//"Speed Baccarat 1"
		if($bet['amount'] > 0){ 
			$strSql.= "  bet_win_money = '".$bet['amount']."', ";
		}
		//bet_result
		$strSql.= " bet_result = '".$bet['id']."' ";
		if(strlen($betSpec) > 0){
			// bet_spec
			$strSql.= ", bet_spec = '".$betSpec."'";
		}
		$strSql.= " WHERE bet_fid = '".$fid."' ";

		return $this->mDbConn->query($strSql);
		
	}

	public function insertS($objMember, $bet, $fLog=null){
    	
		$betSpec = "";
		if(array_key_exists('betting_data', $bet)) {

			$betDetail = null;
			try{
				$betting_data = json_decode($bet['betting_data'], true);
				if(array_key_exists('bets', $betting_data)){
					$betDetail = $betting_data['bets'];
					// writeLog($fLog, "detail-1");
				} else $betDetail = null;

			} catch (Exception $e) {
				$betDetail = null;
			}

			if($betDetail == null){
				$betSpec = "";
			} else {
				// writeLog($fLog, "detail-2");
				if(is_array($betDetail)){
					foreach($betDetail as $detail){
						if(array_key_exists('code', $detail) && array_key_exists('stake', $detail) && array_key_exists('payout', $detail)){
							$betSpec.= $detail['code'].",".$detail['stake'].",";
							$betSpec.= $detail['payout']."#";
						} 
					}
				} else {
					foreach ($betDetail as $side => $amount) {
						$betSpec.= $side.",".$amount.",";
						$betSpec.= $bet['win']."#";
					}
				}
			} 
			if(strlen($betSpec) < 1 && !is_null($fLog)){
				writeLog($fLog, "detail Error=".json_encode($bet['betting_data']));
			}
		}

		$strSql = "INSERT IGNORE INTO ".$this->mTableName." (bet_idx, bet_emp_fid, bet_mb_uid, bet_round_no, bet_time, acc_time, ";
		$strSql.= " bet_money, bet_win_money, bet_agent_id, bet_player_id, bet_game_id, bet_game_type, bet_table_code, ";
		$strSql.= " bet_result, bet_spec ) VALUES "; 
		
		//bet_idx
		$strSql.= " ( '".$bet['transaction_id']."',";
		//bet_emp_fid
		$strSql.= " '".$objMember->mb_emp_fid."', ";
		//bet_mb_uid
		$strSql.= " '".$objMember->mb_uid."', ";
		//bet_round_no
		$strSql.= " '".$bet['gameCode']."', ";	
		//bet_time		
		$strSql.= " '".$bet['startDate']."', ";	
		//acc_time		
		$strSql.= " '".$bet['endDate']."', ";		
		//bet_money
		$strSql.= " '".$bet['bet']."', ";
		//bet_win_money
		$strSql.= " '".$bet['win']."', ";
		//bet_agent_id
		$strSql.= " '".$bet['agent_id']."', "; 
		//bet_player_id
		$strSql.= " '".$bet['user_id']."', ";
		//bet_game_id
		$strSql.= " '".$bet['prd_id']."', ";	
		//bet_game_type
		$strSql.= " 'baccarat', ";	
		//bet_table_code
		$strSql.= " '".$bet['gameCode']."', ";	
		//bet_result
		$strSql.= " '".$bet['balance']."', ";
		//bet_spec
		$strSql.= " '".$betSpec."' ";
		$strSql.= " )";

		
		if ($this->mDbConn->query($strSql) === TRUE) {
			return $this->mDbConn->insert_id;
		}
		return 0;
    } 

}

?>