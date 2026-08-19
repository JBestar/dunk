<?php
namespace App\Models;
use CodeIgniter\Model;

class HlBet_Model extends Model 
{
    protected $table = 'bet_holdem';
    protected $returnType = 'object'; 
    protected $allowedFields = [
        'bet_idx', 
        'bet_emp_fid', 
        'bet_mb_uid', 
        'bet_mb_fid', 
        'bet_round_no', 
        'bet_time', 
        'bet_money', 
        'bet_win_money',
        'bet_agent_id', 
        'bet_player_id', 
        'bet_table_code', 
        'bet_player_1', 
        'bet_player_2', 
        'bet_player_3', 
        'bet_player_4', 
        'bet_player_5', 
        'bet_player_6', 
        'bet_player_7', 
        'bet_player_8', 
        'bet_player_9', 
        'bet_community', 
    ];
    protected $primaryKey = 'bet_fid';
    private $mMemberTable = 'member';
    private $mRewardTable = 'bet_reward';
    private $mGameId = GAME_HOLD_CMS;

    function getBetAccount($objEmp, $arrReqData){
        
        if(is_null($objEmp)){
            return null;
        }
        $strCondition = " WHERE ";
        $strCondition.= getBetTimeRange($arrReqData, $this->db);
        $strCondition .= " AND bet_state = 0 ";
        if(strlen($arrReqData['user']) > 0){
            $strCondition.=" AND ( bet_mb_uid = ".$this->db->escape($arrReqData['user'])." OR bet_mb_fid = ".$this->db->escape($arrReqData['user']).") ";
        }
        $strSql = "";
        if($objEmp->mb_level < LEVEL_ADMIN){
            $strCondition.=" AND bet_mb_fid in ( SELECT mb_fid FROM  tbmember UNION ALL SELECT '".$objEmp->mb_fid."' AS mb_fid ) ";

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
        
        // writeLog($strSql);
        return $arrSum;
    }


    function search($objEmp, $arrReqData, $bAll)
    {

        if(is_null($objEmp)){
            $result = null;
            return $result;
        }

        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_nickname ";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid, r.mb_nickname ";

        $strWhere =" WHERE ".getBetTimeRange($arrReqData, $this->db);
        // if($objEmp->mb_level < LEVEL_ADMIN+2){
        //     $strWhere.=" AND bet_state = 0 ";
        // }
        if(strlen($arrReqData['user']) > 0){
            $strWhere.=" AND ( bet_mb_uid = ".$this->db->escape($arrReqData['user'])." OR bet_mb_fid = ".$this->db->escape($arrReqData['user']).") ";
        }
        if($objEmp->mb_level < LEVEL_ADMIN){
            $strWhere.=" AND bet_mb_uid in ( SELECT mb_uid FROM  tbmember UNION ALL SELECT '".$objEmp->mb_uid."' AS mb_uid ) ";
        }
        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
        $strWhere.=" ORDER BY bet_fid DESC LIMIT ".$nStartRow.", ".$arrReqData['count'];
        
        if($bAll){
            $fields = [ 'bet_idx', 'bet_emp_fid', 'bet_mb_uid', 'bet_mb_fid', 'bet_round_no', 'bet_time', 'bet_money', 
                'bet_win_money', 'bet_player_1', 'bet_player_2', 
                'bet_player_3',  'bet_player_4', 'bet_player_5', 'bet_player_6', 'bet_player_7', 'bet_player_8', 
                'bet_player_9',  'bet_community', 'bet_player_seat', 'bet_order' ];
        } else {
            $fields = [ 'bet_idx', 'bet_emp_fid', 'bet_mb_uid', 'bet_mb_fid', 'bet_round_no', 'bet_time', 'bet_money', 
            'bet_win_money'];
        }

        $strSql = "SELECT ".implode(", ", $fields);
        $strSql .= ", rw_mb_uid, rw_point ";
        $strSql .= " FROM ( ";

        $tbBetSearch = "bet_search";

        if($objEmp->mb_level < LEVEL_ADMIN){

            $strSql .= " WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSql .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
            $strSql .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
            $strSql .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

            $strSql .= " SELECT * FROM ".$this->table;  
            $strSql .=$strWhere.") ".$tbBetSearch;

            //Join bet_reward
            $strSql .= '  LEFT JOIN '.$this->mRewardTable.' ON '.$this->mRewardTable.".rw_game = '".$this->mGameId."' ";
                $strSql .= ' AND '.$tbBetSearch.'.bet_fid = '.$this->mRewardTable.'.rw_bet_id ';
                $strSql .= ' AND '.$this->mRewardTable.".rw_mb_uid = '".$objEmp->mb_uid."' ";
            
        } else{
            
            $strSql .= " SELECT * FROM ".$this->table;  
            $strSql .=$strWhere.") ".$tbBetSearch;

            //Join bet_reward
            $strSql .= '  LEFT JOIN '.$this->mRewardTable.' ON '.$this->mRewardTable.".rw_game = '".$this->mGameId."' ";
                $strSql .= ' AND '.$tbBetSearch.'.bet_fid = '.$this->mRewardTable.'.rw_bet_id ';
                $strSql .= ' AND '.$this->mRewardTable.".rw_mb_uid = ".$tbBetSearch.".bet_mb_uid ";
            
        }

        $strSql .= " ORDER BY bet_time DESC";

        // writeLog($strSql);
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        // writeLog("search End");
        
        return $result; 

    }


    function searchCount($objEmp, $arrReqData)
    {
        if(is_null($objEmp)){
            $result = new \StdClass;
            $result->count = 0;
            return $result;
        } 
        
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

        $strSql.=" WHERE ".getBetTimeRange($arrReqData, $this->db);
        // if($objEmp->mb_level < LEVEL_ADMIN+2){
        //     $strSql.=" AND bet_state = 0 ";
        // }
        if(strlen($arrReqData['user']) > 0){
            $strSql.=" AND ( bet_mb_uid = ".$this->db->escape($arrReqData['user'])." OR bet_mb_fid = ".$this->db->escape($arrReqData['user']).") ";
        }
        if($objEmp->mb_level < LEVEL_ADMIN){
            $strSql.=" AND bet_mb_uid in ( SELECT mb_uid FROM  tbmember UNION ALL SELECT '".$objEmp->mb_uid."' AS mb_uid ) ";
        }
        
        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 

    }

    function getBetSumByDay($arrReqInfo, $objConf){

        $arrSum = array();
        $strSql = " SELECT SUM(bet_money) AS bet_money_sum, SUM(bet_win_money) AS win_money_sum  FROM ".$this->table;
        $strSql .= " WHERE bet_time >= '".$arrReqInfo['start']."' "; //AND bet_time <= '".$arrReqInfo['end']."' ";
        $strSql.=" AND bet_state = 0 ";

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
           
        return $arrSum;
    }


}
