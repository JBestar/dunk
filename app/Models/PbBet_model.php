<?php

namespace App\Models;

use CodeIgniter\Model;

class PbBet_Model extends Model
{
    protected $table = 'bet_pball';
    protected $returnType = 'object'; 
    protected $allowedFields = [
        'bet_game',
        'bet_state',
        'bet_emp_fid',
        'bet_mb_uid',
        'bet_round_fid',
        'bet_round_no',
        'bet_time',
        'bet_mode',
        'bet_target',
        'bet_ratio',
        'bet_money',
        'bet_bonnus',
        'bet_result',
        'bet_win_money',
        'user_before_money',
        'user_after_money',
        'user_view_state',
        'acc_time',
    ];
    protected $primaryKey = 'bet_fid';
    private $mMemberTable = 'member';
    private $mRewardTable = 'bet_reward_mn';

    public function gets($nCount)
    {
        
        $strSql = "SELECT * FROM ".$this->table." ORDER BY bet_time DESC LIMIT 0, ".$nCount;
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result;  
    }

    public function get($nRoundFid){        
        return $this->where(array('bet_round_fid'=>$nRoundFid))->findAll();
    }


    public function getByFid($nBetFid){        
        return $this->where(array('bet_fid'=>$nBetFid))->first();
    }

    public function getByUserId($strUserId, $nCount)
    {
        
        $strSql = "SELECT bet_state, bet_round_fid, bet_round_no, bet_time, bet_mode, bet_target, bet_money, bet_win_money  FROM ".$this->table;
        $strSql .=" WHERE bet_mb_uid='".$strUserId."' ORDER BY bet_time DESC LIMIT 0, ".$nCount;
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result;  
    }

    public function getBetSumByMode($arrRoundInfo, $objConf){

        $arrSumData = array();

	   $arrSum = array();
        for($i = 0; $i <4; $i ++){

            $iMode = $i+1;
            $strSql = ' SELECT SUM(bet_money_sum) AS bet_money_allsum FROM ( ';
            $strSql .= " SELECT bet_mb_uid, bet_mode, bet_target, bet_ratio, SUM(bet_money) AS bet_money_sum FROM ".$this->table;
            $strSql .= " JOIN ".$this->mMemberTable." ON ".$this->mMemberTable.".mb_uid = ".$this->table.".bet_mb_uid ";
            $strSql .= " WHERE bet_game=".$objConf->game_index." AND bet_round_no='".$arrRoundInfo['round_no']."' AND bet_state = '1' ";
            $strSql .= " AND bet_time > '".$arrRoundInfo['round_start']."' AND bet_time < '".$arrRoundInfo['round_end']."' ";
            $strSql .= " AND bet_mode='".$iMode."' AND bet_target='P' GROUP BY bet_mb_uid ";
            $strSql .= " ) tb_sum ";            
            $objResult = $this -> db -> query($strSql)->getRow();
            //유저별 배팅결과 합
            $nSum = 0;
            if(!is_null($objResult->bet_money_allsum)) {
                $nSum = $objResult->bet_money_allsum;
            }
            //게임별 누르기율 계산
            // $nSum = $nSum * $objConf->game_percent_1 / 100;
            $arrSum[0] = (int)$nSum;
            
            $strSql = " SELECT SUM(bet_money_sum) AS bet_money_allsum FROM ( ";
            $strSql .= " SELECT bet_mb_uid, bet_mode, bet_target, bet_ratio, SUM(bet_money) AS bet_money_sum FROM ".$this->table;
            $strSql .= " JOIN ".$this->mMemberTable." ON ".$this->mMemberTable.".mb_uid = ".$this->table.".bet_mb_uid ";
            $strSql .= " WHERE bet_game=".$objConf->game_index." AND bet_round_no='".$arrRoundInfo['round_no']."' AND bet_state = '1' ";
            $strSql .= " AND bet_time > '".$arrRoundInfo['round_start']."' AND bet_time < '".$arrRoundInfo['round_end']."' ";
            $strSql .= " AND bet_mode='".$iMode."' AND bet_target='B' GROUP BY bet_mb_uid ";
            $strSql .= " ) tb_sum ";
            $objResult = $this -> db -> query($strSql)->getRow();
        
            //유저별 배팅결과 합
            $nSum = 0;
            if(!is_null($objResult->bet_money_allsum)) {
                $nSum = $objResult->bet_money_allsum;
            }
            //게임별 누르기율 계산
            // $nSum = $nSum * $objConf->game_percent_1 / 100;
            $arrSum[1] = (int)$nSum;
        
            $arrSumData[$i] = $arrSum;
        }  
        $arrSum = array();
        for($i = 4; $i <29; $i ++){
           $iMode = $i+1;
            $strSql = " SELECT SUM(bet_money_sum) AS bet_money_allsum FROM ( ";
            $strSql .= " SELECT bet_mb_uid, bet_mode, bet_target, bet_ratio, SUM(bet_money) AS bet_money_sum FROM ".$this->table;
            $strSql .= " JOIN ".$this->mMemberTable." ON ".$this->mMemberTable.".mb_uid = ".$this->table.".bet_mb_uid ";
            $strSql .= " WHERE bet_game=".$objConf->game_index." AND bet_round_no='".$arrRoundInfo['round_no']."' AND bet_state = '1' ";
            $strSql .= " AND bet_time > '".$arrRoundInfo['round_start']."' AND bet_time < '".$arrRoundInfo['round_end']."' ";
            $strSql .= " AND bet_mode='".$iMode."' GROUP BY bet_mb_uid ";
            $strSql .= " ) tb_sum ";
            $objResult = $this -> db -> query($strSql)->getRow();
            //유저별 배팅결과 합
            $nSum = 0;
            if(!is_null($objResult->bet_money_allsum)) {
                $nSum = $objResult->bet_money_allsum;
            }
            //게임별 누르기율 계산
            // $nSum = $nSum * $objConf->game_percent_2 / 100;
            $arrSum[0] = (int)$nSum;
    
            $arrSumData[$i] = $arrSum;
        }


        return $arrSumData;  
    }

    public function getBetSumByDay($arrReqInfo){

        $arrSumData = array();

        $arrSum = array();
        $strSql = " SELECT SUM(bet_money) AS bet_money_sum, SUM(bet_win_money) AS win_money_sum  FROM ".$this->table;
        $strSql .= " WHERE bet_time >= '".$arrReqInfo['start']."' "; //"' AND bet_time <= '".$arrReqInfo['end'].
        $strSql .= " AND bet_mode>='1' AND bet_mode<='4' AND bet_state <> ".BET_STATE_TIE;
        $strSql .= " AND bet_mb_uid NOT IN (SELECT mb_uid FROM ".$this->mMemberTable." WHERE mb_level >= ".LEVEL_ADMIN." OR mb_state_test = ".STATE_ACTIVE.") ";
        $objResult = $this -> db -> query($strSql)->getRow();

        $nSum = 0;
        if(!is_null($objResult->bet_money_sum)) {
            $nSum = $objResult->bet_money_sum;
        }
        $arrSum[0] = $nSum;
        $nSum = 0;
        if(!is_null($objResult->win_money_sum)) {
            $nSum = $objResult->win_money_sum;
        }
        $arrSum[1] = $nSum;

        $arrSumData[0] = $arrSum;

        $arrSum = array();
        $strSql = " SELECT SUM(bet_money) AS bet_money_sum, SUM(bet_win_money) AS win_money_sum  FROM ".$this->table;
        $strSql .= " WHERE bet_time > '".$arrReqInfo['start']."' "; //AND bet_time < '".$arrReqInfo['end']."' "
        $strSql .= " AND bet_mode>='5' AND bet_mode<='38'  AND bet_state <> ".BET_STATE_TIE;
        $strSql .= " AND bet_mb_uid NOT IN (SELECT mb_uid FROM ".$this->mMemberTable." WHERE mb_level >= ".LEVEL_ADMIN." OR mb_state_test = ".STATE_ACTIVE.") ";
        $objResult = $this -> db -> query($strSql)->getRow();

        $nSum = 0;
        if(!is_null($objResult->bet_money_sum)) {
            $nSum = $objResult->bet_money_sum;
        }
        $arrSum[0] = $nSum;
        $nSum = 0;
        if(!is_null($objResult->win_money_sum)) {
            $nSum = $objResult->win_money_sum;
        }
        $arrSum[1] = $nSum;

        $arrSumData[1] = $arrSum;


        return $arrSumData;  
    }


    public function getBetAccount($objEmp, $arrReqData){

        if(is_null($objEmp)){
            return null;
        }
        $strCondition = " WHERE bet_state <> ".BET_STATE_TIE;
        $strCondition.= " AND bet_game = ".$arrReqData['game'];
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strCondition.=" AND ".getBetTimeRange($arrReqData, $this->db);
                        
        }
        if(strlen($arrReqData['user']) > 0){
            $strCondition.=" AND bet_mb_uid = '".$arrReqData['user']."' ";            
        }
        if(strlen($arrReqData['round']) > 0){
            $strCondition.=" AND bet_round_no = '".$arrReqData['round']."' ";            
        }
        if((int)$arrReqData['mode'] > 0){
            if($arrReqData['mode'] == 1)
                $strCondition.=" AND bet_mode >= 1 AND bet_mode <= 4 ";
            else if($arrReqData['mode'] == 2)
                $strCondition.=" AND bet_mode >= 5 AND bet_mode <= 20 ";
            else if($arrReqData['mode'] == 3)
                $strCondition.=" AND bet_mode >= 21 AND bet_mode <= 29 ";
            else if($arrReqData['mode'] == 4)
                $strCondition.=" AND bet_mode >= 31 AND bet_mode <= 38 ";
            else if($arrReqData['mode'] == 5)
                $strCondition.=" AND bet_mode = 30 ";
        }
        $strSql = "";
        if($objEmp->mb_level < LEVEL_ADMIN){
            $strCondition.=" AND bet_mb_uid in ( SELECT mb_uid FROM  tbmember UNION ALL SELECT '".$objEmp->mb_uid."' AS mb_uid ) ";

            $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_nickname ";
            $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid, r.mb_nickname ";

            $strSql = " WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSql .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
            $strSql .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
            $strSql .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";
        }
        //총배팅금, 적중금
        $arrSum = array();
        $strSql .= " SELECT SUM(bet_money) AS bet_money_sum, SUM(bet_win_money) AS win_money_sum, ";
        $strSql .= " SUM(CASE WHEN bet_win_money= 0 THEN bet_money ELSE 0 END) AS loss_money_sum, ";
        $strSql .= " SUM(CASE WHEN bet_win_money > 0 THEN bet_win_money-bet_money ELSE 0 END) AS benefit_money_sum ";
        $strSql .= " FROM ".$this->table;
        $strSql .= $strCondition;
        $objResult = $this -> db -> query($strSql)->getRow();

        $nSum = 0;
        if(!is_null($objResult->bet_money_sum)) {
            $nSum = $objResult->bet_money_sum;
        }
        $arrSum[0] = $nSum;
        $nSum = 0;
        if(!is_null($objResult->win_money_sum)) {
            $nSum = $objResult->win_money_sum;
        }
        $arrSum[1] = $nSum;
        //총미적중금
        $nSum = 0;
        if(!is_null($objResult->loss_money_sum)) {
            $nSum = $objResult->loss_money_sum;
        }
        $arrSum[2] = $nSum;
        //총당첨금
        $nSum = 0;
        if(!is_null($objResult->benefit_money_sum)) {
            $nSum = $objResult->benefit_money_sum;
        }
        $arrSum[3] = $nSum;
        
        return $arrSum;
    }


    public function search($objEmp, $arrReqData)
    {
        if(is_null($objEmp))
            return [];
            
        $strTbColum = ' mb_fid, mb_uid, mb_level, mb_emp_fid ';
        $strTbRColum = ' r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid ';

        $strWhere =" WHERE bet_game = ".$this->db->escape($arrReqData['game']);
        if (strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0) {
            $strWhere .= " AND ".getBetTimeRange($arrReqData, $this->db);
        }
        if (strlen($arrReqData['user']) > 0) {
            $strWhere .= " AND bet_mb_uid = ".$this->db->escape($arrReqData['user']);
        }
        if (strlen($arrReqData['round']) > 0) {
            $strWhere .= " AND bet_round_no = ".$this->db->escape($arrReqData['round']);
        }
        if ((int) $arrReqData['mode'] > 0) {
            $strWhere .= ' AND ';

            if (1 == $arrReqData['mode']) {
                $strWhere .= ' bet_mode >= 1 AND bet_mode <= 4 ';
            } elseif (2 == $arrReqData['mode']) {
                $strWhere .= ' bet_mode >= 5 AND bet_mode <= 20 ';
            } elseif (3 == $arrReqData['mode']) {
                $strWhere .= ' bet_mode >= 21 AND bet_mode <= 29 ';
            } else if($arrReqData['mode'] == 4)
                $strWhere.=" bet_mode >= 31 AND bet_mode <= 38 ";
            else if($arrReqData['mode'] == 5)
                $strWhere.=" bet_mode = 30 ";
        }
        if($objEmp->mb_level < LEVEL_ADMIN){
            $strWhere.=" AND bet_mb_uid in ( SELECT mb_uid FROM  tbmember UNION ALL SELECT '".$objEmp->mb_uid."' AS mb_uid ) ";
        }
        $nStartRow = ($arrReqData['page'] - 1) * $arrReqData['count'];
        $strWhere .= ' ORDER BY bet_fid DESC LIMIT '.$nStartRow.', '.$arrReqData['count'];

        $strSql = '';
        $strSql .= ' SELECT bet_fid, bet_game, bet_state, bet_emp_fid, bet_mb_uid, bet_round_fid, bet_round_no, bet_time, ';
        $strSql .= ' bet_mode, bet_target, bet_ratio, bet_money, bet_result, bet_win_money, rw_mb_uid, rw_point  ';
        $strSql .= " FROM ( ";

        $tbBetSearch = "bet_search";

        if ($objEmp->mb_level < LEVEL_ADMIN) {
            $strSql .= ' WITH RECURSIVE tbmember ('.$strTbColum.') AS';
            $strSql .= ' ( SELECT '.$strTbColum.' FROM '.$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
            $strSql .= ' UNION ALL SELECT '.$strTbRColum.' FROM '.$this->mMemberTable.' r ';
            $strSql .= ' INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )';

            $strSql .= " SELECT * FROM ".$this->table;  

            $strSql .=$strWhere.") ".$tbBetSearch;

            //Join bet_reward
            $strSql .= '  LEFT JOIN '.$this->mRewardTable.' ON '.$this->mRewardTable.".rw_game = ".$this->db->escape($arrReqData['game']);
                $strSql .= ' AND '.$tbBetSearch.'.bet_fid = '.$this->mRewardTable.'.rw_bet_id ';
                $strSql .= ' AND '.$this->mRewardTable.".rw_mb_uid = '".$objEmp->mb_uid."' ";
            
        } else {
            $strSql .= " SELECT * FROM ".$this->table;  
            $strSql .=$strWhere.") ".$tbBetSearch;

            //Join bet_reward
            $strSql .= '  LEFT JOIN '.$this->mRewardTable.' ON '.$this->mRewardTable.".rw_game = ".$this->db->escape($arrReqData['game']);
                $strSql .= ' AND '.$tbBetSearch.'.bet_fid = '.$this->mRewardTable.'.rw_bet_id ';
                $strSql .= ' AND '.$this->mRewardTable.".rw_mb_uid = ".$tbBetSearch.".bet_mb_uid ";
            
        }
        $strSql .= " ORDER BY bet_fid  DESC";
        
        $query = $this->db->query($strSql);
        $result = $query->getResult();
        // writeLog($strSql);

        return $result;

    }


    public function searchCount($objEmp, $arrReqData)
    {
        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid ";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid ";

        $strSql = "";
        if($objEmp->mb_level < LEVEL_ADMIN){

            $strSql = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSql .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
            $strSql .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
            $strSql .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

            $strSql .= "SELECT count(bet_fid) as count  FROM ".$this->table;

        } else {
            $strSql .= "SELECT count(bet_fid) as count  FROM ".$this->table;
        }

        $strSql.=" WHERE bet_game = ".$this->db->escape($arrReqData['game']);
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strSql.=" AND ".getBetTimeRange($arrReqData, $this->db);
        }
        if(strlen($arrReqData['user']) > 0){
            $strSql.= " AND ";
            $strSql.=" bet_mb_uid = ".$this->db->escape($arrReqData['user']);
        }
        if(strlen($arrReqData['round']) > 0){
            $strSql.= " AND ";
            $strSql.=" bet_round_no = ".$this->db->escape($arrReqData['round']);
        }
        if((int)$arrReqData['mode'] > 1){
            $strSql.= " AND ";
            
            if($arrReqData['mode'] == 1)
                $strSql.=" bet_mode >= 1 AND bet_mode <= 4 ";
            else if($arrReqData['mode'] == 2)
                $strSql.=" bet_mode >= 5 AND bet_mode <= 20 ";
            else if($arrReqData['mode'] == 3)
                $strSql.=" bet_mode >= 21 AND bet_mode <= 29 ";
            else if($arrReqData['mode'] == 4)
                $strSql.=" bet_mode >= 31 AND bet_mode <= 38 ";
            else if($arrReqData['mode'] == 5)
                $strSql.=" bet_mode = 30 ";
        }
        if($objEmp->mb_level < LEVEL_ADMIN){
            $strSql.= " AND ";
            $strSql.=" bet_mb_uid in ( SELECT mb_uid FROM  tbmember UNION ALL SELECT '".$objEmp->mb_uid."' AS mb_uid ) ";
        }

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 

    }

    //배팅 무효처리
    public function ignore($objBet)
    {
        $this->builder()->set('bet_state', 4);
        $this->builder()->set('bet_win_money', 0);
        $this->builder()->set('bet_result', "");
        $this->builder()->set('user_after_money', 0);

        $this->builder()->where('bet_fid', $objBet->bet_fid);
        
        return $this->builder()->update();
    }
    
    public function updateBetRound($objRoundInfo, $objBetInfo)
    {
        if(is_null($objRoundInfo)) return false;
        if(is_null($objBetInfo)) return false;
	    if($objRoundInfo->round_state == 0) return false;

        $nWinMoney = 0;
        $isWin = false;
        $gameId = $objBetInfo->bet_game;

        if($gameId == GAME_BOGLE_LADDER){
            switch(intval($objBetInfo->bet_mode)){
                case 1:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_1;
                    if($objBetInfo->bet_target == $objRoundInfo->round_result_1){
                        $isWin = true;                            
                    }
                    break;
                case 2:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_2;
                    if($objBetInfo->bet_target == $objRoundInfo->round_result_2){
                        $isWin = true;
                    }
                    break;
                case 3:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_3;
                    if($objBetInfo->bet_target == $objRoundInfo->round_result_3){
                        $isWin = true;
                    }
                    break;
                case 4:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_1.$objRoundInfo->round_result_2;
                    if($objRoundInfo->round_result_1 == "P" && $objRoundInfo->round_result_2 == "P"){
                        $isWin = true;
                    }
                    break;
                case 5:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_1.$objRoundInfo->round_result_2;
                    if($objRoundInfo->round_result_1 == "P" && $objRoundInfo->round_result_2 == "B"){
                        $isWin = true;
                    }
                    break;
                case 6:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_1.$objRoundInfo->round_result_2;
                    if($objRoundInfo->round_result_1 == "B" && $objRoundInfo->round_result_2 == "P"){
                        $isWin = true;
                    }
                    break;
                case 7:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_1.$objRoundInfo->round_result_2;
                    if($objRoundInfo->round_result_1 == "B" && $objRoundInfo->round_result_2 == "B"){
                        $isWin = true;
                    }
                    break;
                default:return false;
            }
        } else {

            //bet_state=2:Betting-loss 3:Betting-Earn 
            switch(intval($objBetInfo->bet_mode)){
                case 1:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_1;
                    if($objBetInfo->bet_target == $objRoundInfo->round_result_1){
                        $isWin = true;                            
                    }
                    break;
                case 2:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_2;
                    if($objBetInfo->bet_target == $objRoundInfo->round_result_2){
                        $isWin = true;
                    }
                    break;
                case 3:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_3;
                    if($objBetInfo->bet_target == $objRoundInfo->round_result_3){
                        $isWin = true;
                    }
                    break;
                case 4:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_4;
                    if($objBetInfo->bet_target == $objRoundInfo->round_result_4){
                        $isWin = true;
                    }
                    break;
                case 5:    //파워볼조합
                    $objBetInfo->bet_result = $objRoundInfo->round_result_1.$objRoundInfo->round_result_2;
                    if($objRoundInfo->round_result_1 == 'P' && $objRoundInfo->round_result_2 == 'P' ){
                        $isWin = true;
                    }
                    break;
                case 6:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_1.$objRoundInfo->round_result_2;
                    if($objRoundInfo->round_result_1 == 'P' && $objRoundInfo->round_result_2 == 'B' ){
                        $isWin = true;
                    }
                    break;
                case 7: 
                    $objBetInfo->bet_result = $objRoundInfo->round_result_1.$objRoundInfo->round_result_2;
                    if($objRoundInfo->round_result_1 == 'B' && $objRoundInfo->round_result_2 == 'P' ){
                        $isWin = true;
                    }
                    break;
                case 8:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_1.$objRoundInfo->round_result_2;
                    if($objRoundInfo->round_result_1 == 'B' && $objRoundInfo->round_result_2 == 'B' ){
                        $isWin = true;
                    }
                    break;
                case 9:    //일반볼조합
                    $objBetInfo->bet_result = $objRoundInfo->round_result_3.$objRoundInfo->round_result_4;
                    if($objRoundInfo->round_result_3 == 'P' && $objRoundInfo->round_result_4 == 'P' ){
                        $isWin = true;
                    }
                    break;
                case 10:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_3.$objRoundInfo->round_result_4;
                    if($objRoundInfo->round_result_3 == 'P' && $objRoundInfo->round_result_4 == 'B' ){
                        $isWin = true;
                    }
                    break;
                case 11:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_3.$objRoundInfo->round_result_4;
                    if($objRoundInfo->round_result_3 == 'B' && $objRoundInfo->round_result_4 == 'P' ){
                        $isWin = true;
                    }
                    break;
                case 12:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_3.$objRoundInfo->round_result_4;
                    if($objRoundInfo->round_result_3 == 'B' && $objRoundInfo->round_result_4 == 'B' ){
                        $isWin = true;
                    }
                    break;
                case 13:      //일반볼 + 파워볼 조합
                    $objBetInfo->bet_result = $objRoundInfo->round_result_3.$objRoundInfo->round_result_1;
                    if($objRoundInfo->round_result_3 == 'P' && $objRoundInfo->round_result_1 == 'P' ){
                        $isWin = true;
                    }
                    break;
                case 14:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_3.$objRoundInfo->round_result_1;
                    if($objRoundInfo->round_result_3 == 'P' && $objRoundInfo->round_result_1 == 'B' ){
                        $isWin = true;
                    }
                    break;
                case 15:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_3.$objRoundInfo->round_result_1;
                    if($objRoundInfo->round_result_3 == 'B' && $objRoundInfo->round_result_1 == 'P' ){
                        $isWin = true;
                    }
                    break;
                case 16:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_3.$objRoundInfo->round_result_1;
                    if($objRoundInfo->round_result_3 == 'B' && $objRoundInfo->round_result_1 == 'B' ){
                        $isWin = true;
                    }
                    break;
                case 17:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_4.$objRoundInfo->round_result_2;
                    if($objRoundInfo->round_result_4 == 'P' && $objRoundInfo->round_result_2 == 'P' ){
                        $isWin = true;
                    }
                    break;
                case 18:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_4.$objRoundInfo->round_result_2;
                    if($objRoundInfo->round_result_4 == 'P' && $objRoundInfo->round_result_2 == 'B' ){
                        $isWin = true;
                    }
                    break;
                case 19:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_4.$objRoundInfo->round_result_2;
                    if($objRoundInfo->round_result_4 == 'B' && $objRoundInfo->round_result_2 == 'P' ){
                        $isWin = true;
                    }
                    break;
                case 20:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_4.$objRoundInfo->round_result_2;
                    if($objRoundInfo->round_result_4 == 'B' && $objRoundInfo->round_result_2 == 'B' ){
                        $isWin = true;
                    }
                    break;
                case 21:   //일반볼 대중소
                    if($gameId == GAME_SPKN_BALL){
                        $objBetInfo->bet_result = $objRoundInfo->round_result_5;
                        if($objBetInfo->bet_target == $objRoundInfo->round_result_5){
                            $isWin = true;                            
                        }
                    } else {
                        $objBetInfo->bet_result = $objRoundInfo->round_result_3.$objRoundInfo->round_result_5;
                        if($objRoundInfo->round_result_3 == 'P' && $objRoundInfo->round_result_5 == 'L' ){
                            $isWin = true;
                        }
                    }
                    
                    break;
                case 22:
                    if($gameId == GAME_SPKN_BALL){
                        $objBetInfo->bet_result = $objRoundInfo->round_result_6;
                        if($objBetInfo->bet_target == $objRoundInfo->round_result_6){
                            $isWin = true;                            
                        }
                    } else {
                        $objBetInfo->bet_result = $objRoundInfo->round_result_3.$objRoundInfo->round_result_5;
                        if($objRoundInfo->round_result_3 == 'P' && $objRoundInfo->round_result_5 == 'M' ){
                            $isWin = true;
                        }
                    }
                    break;
                case 23:
                    if($gameId == GAME_SPKN_BALL){
                        $objBetInfo->bet_result = $objRoundInfo->round_result_5.$objRoundInfo->round_result_6;
                        if($objRoundInfo->round_result_5 == 'P' && $objRoundInfo->round_result_6 == 'P' ){
                            $isWin = true;
                        }
                    } else {
                        $objBetInfo->bet_result = $objRoundInfo->round_result_3.$objRoundInfo->round_result_5;
                        if($objRoundInfo->round_result_3 == 'P' && $objRoundInfo->round_result_5 == 'S' ){
                            $isWin = true;
                        }
                    }
                    break;
                case 24:
                    if($gameId == GAME_SPKN_BALL){
                        $objBetInfo->bet_result = $objRoundInfo->round_result_5.$objRoundInfo->round_result_6;
                        if($objRoundInfo->round_result_5 == 'P' && $objRoundInfo->round_result_6 == 'B' ){
                            $isWin = true;
                        }
                    } else {
                        $objBetInfo->bet_result = $objRoundInfo->round_result_3.$objRoundInfo->round_result_5;
                        if($objRoundInfo->round_result_3 == 'B' && $objRoundInfo->round_result_5 == 'L' ){
                            $isWin = true;
                        }
                    }
                    break;
                case 25:
                    if($gameId == GAME_SPKN_BALL){
                        $objBetInfo->bet_result = $objRoundInfo->round_result_5.$objRoundInfo->round_result_6;
                        if($objRoundInfo->round_result_5 == 'B' && $objRoundInfo->round_result_6 == 'P' ){
                            $isWin = true;
                        }
                    } else {
                        $objBetInfo->bet_result = $objRoundInfo->round_result_3.$objRoundInfo->round_result_5;
                        if($objRoundInfo->round_result_3 == 'B' && $objRoundInfo->round_result_5 == 'M' ){
                            $isWin = true;
                        }
                    }
                    break;
                case 26:
                    if($gameId == GAME_SPKN_BALL){
                        $objBetInfo->bet_result = $objRoundInfo->round_result_5.$objRoundInfo->round_result_6;
                        if($objRoundInfo->round_result_5 == 'B' && $objRoundInfo->round_result_6 == 'B' ){
                            $isWin = true;
                        }
                    } else {
                        $objBetInfo->bet_result = $objRoundInfo->round_result_3.$objRoundInfo->round_result_5;
                        if($objRoundInfo->round_result_3 == 'B' && $objRoundInfo->round_result_5 == 'S' ){
                            $isWin = true;
                        }
                    }
                    break;
                case 27:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_5;
                    if($objRoundInfo->round_result_5 == 'L' ){
                        $isWin = true;
                    }
                    break;
                case 28:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_5;
                    if($objRoundInfo->round_result_5 == 'M' ){
                        $isWin = true;
                    }
                    break;
                case 29:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_5;
                    if($objRoundInfo->round_result_5 == 'S' ){
                        $isWin = true;
                    }
                    break;
                case 30:
                    $objBetInfo->bet_result = $objRoundInfo->round_power;
                    if($objBetInfo->bet_target === $objRoundInfo->round_power ){
                        $isWin = true;
                    }
                    break;
                case 31:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_3.$objRoundInfo->round_result_4.$objRoundInfo->round_result_1;
                    if($objRoundInfo->round_result_3 == 'P' && $objRoundInfo->round_result_4 == 'P' && $objRoundInfo->round_result_1 == 'P' ){
                        $isWin = true;
                    }
                    break;
                case 32:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_3.$objRoundInfo->round_result_4.$objRoundInfo->round_result_1;
                    if($objRoundInfo->round_result_3 == 'P' && $objRoundInfo->round_result_4 == 'P' && $objRoundInfo->round_result_1 == 'B' ){
                        $isWin = true;
                    }
                    break;
                case 33:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_3.$objRoundInfo->round_result_4.$objRoundInfo->round_result_1;
                    if($objRoundInfo->round_result_3 == 'P' && $objRoundInfo->round_result_4 == 'B' && $objRoundInfo->round_result_1 == 'P' ){
                        $isWin = true;
                    }
                    break;
                case 34:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_3.$objRoundInfo->round_result_4.$objRoundInfo->round_result_1;
                    if($objRoundInfo->round_result_3 == 'P' && $objRoundInfo->round_result_4 == 'B' && $objRoundInfo->round_result_1 == 'B' ){
                        $isWin = true;
                    }
                    break;
                case 35:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_3.$objRoundInfo->round_result_4.$objRoundInfo->round_result_1;
                    if($objRoundInfo->round_result_3 == 'B' && $objRoundInfo->round_result_4 == 'P' && $objRoundInfo->round_result_1 == 'P' ){
                        $isWin = true;
                    }
                    break;
                case 36:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_3.$objRoundInfo->round_result_4.$objRoundInfo->round_result_1;
                    if($objRoundInfo->round_result_3 == 'B' && $objRoundInfo->round_result_4 == 'P' && $objRoundInfo->round_result_1 == 'B' ){
                        $isWin = true;
                    }
                    break;
                case 37:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_3.$objRoundInfo->round_result_4.$objRoundInfo->round_result_1;
                    if($objRoundInfo->round_result_3 == 'B' && $objRoundInfo->round_result_4 == 'B' && $objRoundInfo->round_result_1 == 'P' ){
                        $isWin = true;
                    }
                    break;
                case 38:
                    $objBetInfo->bet_result = $objRoundInfo->round_result_3.$objRoundInfo->round_result_4.$objRoundInfo->round_result_1;
                    if($objRoundInfo->round_result_3 == 'B' && $objRoundInfo->round_result_4 == 'B' && $objRoundInfo->round_result_1 == 'B' ){
                        $isWin = true;
                    }
                    break;
                default:return false;
            }
        }

        if($isWin){
            $objBetInfo->bet_state = 3;
            //bet_win_money
            $nWinMoney = $objBetInfo->bet_money * $objBetInfo->bet_ratio;
            $objBetInfo->bet_win_money = (int)$nWinMoney;
            //user_after_money
            $objBetInfo->user_after_money = ($objBetInfo->user_before_money + $objBetInfo->bet_win_money - $objBetInfo->bet_money);
        } else {
            $objBetInfo->bet_state = 2;
            $objBetInfo->bet_win_money = 0;
            //user_after_money
            $objBetInfo->user_after_money = ($objBetInfo->user_before_money - $objBetInfo->bet_money);

        }

        $this->builder()->set('bet_result', $objBetInfo->bet_result);
        $this->builder()->set('bet_state', $objBetInfo->bet_state); 
        $this->builder()->set('bet_win_money', $objBetInfo->bet_win_money);
        $this->builder()->set('user_after_money', $objBetInfo->user_after_money);
        $this->builder()->set('acc_time', 'NOW()', false);
        
        $this->builder()->where('bet_fid', $objBetInfo->bet_fid);
        return $this->builder()->update();        
    }

}