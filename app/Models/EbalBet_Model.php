<?php
namespace App\Models;
use CodeIgniter\Model;

class EbalBet_Model extends Model 
{
    protected $table = 'bet_ebal';
    protected $returnType = 'object'; 
    protected $allowedFields = [
        'bet_idx', 
        'bet_emp_fid', 
        'bet_state', 
        'bet_mb_fid', 
        'bet_mb_uid', 
        'bet_mb_level', 
        'bet_round_no', 
        'bet_time', 
        'bet_money', 
        'bet_win_money',
        'bet_agent_id', 
        'bet_player_id', 
        'bet_game_id' ,
        'bet_game_type', 
        'bet_table_code', 
        'bet_table_name', 
        'bet_type',          //0-press or 1-over
        'bet_choice', 
        'bet_result', 
        'bet_balance', 
        'bet_win_balance',
        'point_amount',     //bet state - win loss tie
        'employee_amount',  //manual proc
        'company_amount',   //order proc state
        'org_id', 
    ];
    protected $primaryKey = 'bet_fid';
    private $mMemberTable = 'member';
    private $mRewardTable = 'bet_reward';

    public function setType($gameId){
        if($gameId == GAME_AUTO_PRAG){
            $this->table = 'bet_prbal';
            $this->mRewardTable = 'bet_reward_pr';
        } else {
            $this->table = 'bet_ebal';
            $this->mRewardTable = 'bet_reward';
        }
    }

    function getBetAccount($objEmp, $arrReqData){
        
        if(array_key_exists("state", $arrReqData) && $arrReqData['state'] > 0)
            return null;
        if(is_null($objEmp)){
            return null;
        }

        $strCondition = " WHERE ";
        $strCondition.= getBetTimeRange($arrReqData, $this->db);
        $strCondition .= " AND bet_mb_level = 0 AND (company_amount = 0 OR employee_amount = 1) AND point_amount <> ".BET_STATE_TIE;
        if(strlen($arrReqData['user']) > 0){
            $strCondition.=" AND bet_mb_fid = ".$this->db->escape($arrReqData['user']);
        }

        if(array_key_exists('room', $arrReqData) && strlen($arrReqData['room']) > 0) {
            $strCondition.=" AND bet_table_name = ".$this->db->escape($arrReqData['room']);
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
        
        if(array_key_exists('type', $arrReqData) && intval($arrReqData['type']) == 1){               //only balance betting
            $strSql .= " SELECT SUM(bet_balance) AS bet_money_sum, SUM(bet_win_balance) AS win_money_sum, ";
            $strSql .= " SUM(CASE WHEN bet_win_balance= 0 THEN bet_balance ELSE 0 END) AS loss_money_sum, ";
            $strSql .= " SUM(CASE WHEN bet_win_balance > 0 THEN bet_win_balance-bet_balance ELSE 0 END) AS benefit_money_sum ";
        } else if(array_key_exists('type', $arrReqData) && intval($arrReqData['type']) == 0){        //only press betting
            $strSql .= " SELECT SUM(bet_money-bet_balance) AS bet_money_sum, SUM(bet_win_money-bet_win_balance) AS win_money_sum, ";
            $strSql .= " SUM(CASE WHEN bet_win_money = 0 AND bet_money <> bet_balance THEN bet_money-bet_balance ELSE 0 END) AS loss_money_sum, ";
            $strSql .= " SUM(CASE WHEN bet_win_money > 0 AND bet_money <> bet_balance THEN bet_win_money-bet_money-bet_balance+bet_win_balance ELSE 0 END) AS benefit_money_sum ";
        } else {                                            //all betting
            $strSql .= " SELECT SUM(bet_money) AS bet_money_sum, SUM(bet_win_money) AS win_money_sum, ";
            $strSql .= " SUM(CASE WHEN bet_win_money= 0 THEN bet_money ELSE 0 END) AS loss_money_sum, ";
            $strSql .= " SUM(CASE WHEN bet_win_money > 0 THEN bet_win_money-bet_money ELSE 0 END) AS benefit_money_sum ";
        }

        $strSql .= " FROM ".$this->table;
        $strSql .= $strCondition;
        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog($strSql);
        $objResult = $this -> db -> query($strSql)->getRow();
        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog("EbalBet>> account End");

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


    function search($objEmp, $arrReqData)
    {
        if(is_null($objEmp)){
            $result = null;
            return $result;
        }

        $gameId = GAME_AUTO_EVOL;
        $gameName = '에볼루션';
        if($this->table == 'bet_prbal'){
            $gameId = GAME_AUTO_PRAG;
            $gameName = '프라그마틱';
        }

        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_nickname, mb_live_id ";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid, r.mb_nickname, r.mb_live_id ";

        $strWhere=" WHERE ";
        // $strWhere .= " bet_fid >= ".$arrReqData['gm_range'][0]." AND bet_fid <= ".$arrReqData['gm_range'][1];
        $strWhere .= getBetTimeRange($arrReqData, $this->db);
        if(array_key_exists("state", $arrReqData) && $arrReqData['state'] == 1){
            $strWhere.=" AND company_amount > 0 AND company_amount < 20 AND employee_amount = 1 "; //only manual proc
        } elseif(array_key_exists("state", $arrReqData) && $arrReqData['state'] == 2){
            $strWhere.=" AND company_amount > 0 AND company_amount < 20 AND employee_amount = 0 "; //only no proc
        } else {
            $strWhere.=" AND (company_amount = 0 OR employee_amount = 1) ";     //success proc Or manual proc
        }
        if(strlen($arrReqData['user']) > 0){
            $strWhere.=" AND bet_mb_fid = ".$this->db->escape($arrReqData['user']);
        }
        if(array_key_exists('type', $arrReqData) && intval($arrReqData['type']) >= 0){
            if(intval($arrReqData['type']) == 1)
                $strWhere.=" AND bet_type = '".$arrReqData['type']."' AND bet_balance > 0 ";            
            else $strWhere.=" AND bet_money <> bet_balance ";     
        }
        if(array_key_exists('room', $arrReqData) && strlen($arrReqData['room']) > 0)
            $strWhere.=" AND bet_table_name = '".$arrReqData['room']."' ";
        if($objEmp->mb_level < LEVEL_ADMIN){
            $strWhere.=" AND bet_mb_fid in ( SELECT mb_fid FROM  tbmember UNION ALL SELECT '".$objEmp->mb_fid."' AS mb_fid ) ";
        }

        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
        $strWhere.=" ORDER BY bet_fid DESC LIMIT ".$nStartRow.", ".$arrReqData['count'];
        
        $strSql = "";
        $strSql .= "SELECT bet_fid, bet_idx, bet_mb_uid, bet_round_no, bet_time, bet_money, bet_win_money, bet_player_id, bet_game_id, bet_game_type, bet_table_code, ";
        $strSql .= " bet_type, bet_choice, bet_result, bet_balance, bet_win_balance, point_amount, company_amount, obj_id, bet_follow, bet_balance_no, bet_auto_press, ";
        $strSql .= " bet_balance_rate, bet_table_name as game_name, rw_mb_uid, rw_point, '".$gameName."' as prd_name";
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
            $strSql .= '  LEFT JOIN '.$this->mRewardTable.' ON '.getTimeRange("rw_time", $arrReqData, $this->db);
            $strSql .= ' AND '.$this->mRewardTable.".rw_game = '".$gameId."' ";
            $strSql .= ' AND '.$tbBetSearch.'.bet_id = '.$this->mRewardTable.'.rw_bet_id ';
                $strSql .= ' AND '.$this->mRewardTable.".rw_mb_fid = '".$objEmp->mb_fid."' ";
            
        } else{
            
            $strSql .= " SELECT * FROM ".$this->table;  
        	$strSql .=$strWhere.") ".$tbBetSearch;

            //Join bet_reward
            $strSql .= '  LEFT JOIN '.$this->mRewardTable.' ON '.getTimeRange("rw_time", $arrReqData, $this->db);
            $strSql .= ' AND '.$this->mRewardTable.".rw_game = '".$gameId."' ";
                $strSql .= ' AND '.$tbBetSearch.'.bet_id = '.$this->mRewardTable.'.rw_bet_id ';
                $strSql .= ' AND '.$this->mRewardTable.".rw_mb_fid = ".$tbBetSearch.".bet_mb_fid ";
            
        }
        $strSql .= " ORDER BY bet_time DESC";

        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog($strSql);
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog("EbalBet>> search End");
        
        return $result; 

    }


    function searchCount($objEmp, $arrReqData)
    {
        
        if(is_null($objEmp)){
            $result = new \StdClass;
            $result->count = 0;
            return $result;
        }

        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_live_id ";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid, r.mb_live_id ";

         $strSql = "";
        if($objEmp->mb_level < LEVEL_ADMIN){


            $strSql = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSql .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
            $strSql .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
            $strSql .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";
        } 
        $strSql .= "SELECT count(bet_fid) as count  FROM ".$this->table;
        
        $strSql .= " WHERE ";
        // $strSql .= " bet_fid >= ".$arrReqData['gm_range'][0]." AND bet_fid <= ".$arrReqData['gm_range'][1];
        $strSql .= getBetTimeRange($arrReqData, $this->db);
        if(array_key_exists("state", $arrReqData) && $arrReqData['state'] == 1){
            $strSql.=" AND company_amount > 0 AND company_amount < 8 AND employee_amount = 1 ";
        } elseif(array_key_exists("state", $arrReqData) && $arrReqData['state'] == 2){
            $strSql.=" AND company_amount > 0 AND company_amount < 8 AND employee_amount = 0 ";
        } else {
            $strSql.=" AND (company_amount = 0 OR employee_amount = 1) ";
        }


        if(strlen($arrReqData['user']) > 0){
            $strSql.=" AND bet_mb_fid = ".$this->db->escape($arrReqData['user']);
        }
        if(array_key_exists('type', $arrReqData) && intval($arrReqData['type']) >= 0){
            if(intval($arrReqData['type']) == 1)
                $strSql.=" AND bet_type = '".$arrReqData['type']."' AND bet_balance > 0 ";            
            else $strSql.=" AND bet_money <> bet_balance ";     
        }
        if(array_key_exists('room', $arrReqData) && strlen($arrReqData['room']) > 0) {
            $strSql.=" AND bet_table_name = '".$arrReqData['room']."' ";
        }
        if($objEmp->mb_level < LEVEL_ADMIN){
            $strSql.=" AND bet_mb_fid in ( SELECT mb_fid FROM  tbmember UNION ALL SELECT '".$objEmp->mb_fid."' AS mb_fid ) ";
        }

        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog($strSql);
        $query = $this -> db -> query($strSql);
        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog("EbalBet>> searchCount End");

        $result = $query -> getRow();
        
        return $result; 

    }

    function getBetSumByDay($arrReqInfo, $objConf=null){

        $arrSum = array();

        // if($arrReqInfo['gm_range'][0] >= 0){
            $strSql = " SELECT SUM(bet_money) AS bet_money_sum, SUM(bet_win_money) AS win_money_sum  FROM ".$this->table;
            // $strSql .= " WHERE bet_fid >= ".$arrReqInfo['gm_range'][0]; //." AND bet_fid <= ".$arrReqInfo['gm_range'][1]
            $strSql .= " WHERE bet_time >= '".$arrReqInfo['start']."' " ;//AND bet_time <= '".$arrReqInfo['end']."' ";
            $strSql .= " AND bet_mb_level = 0 AND ( company_amount = 0 OR employee_amount = 1 ) ";  //exclude tie and success bet 
            $strSql .= " AND point_amount <> ".BET_STATE_TIE ;  //member level < admin level;
    
            // writeLog($strSql);
            $objResult = $this -> db -> query($strSql)->getRow();
            // writeLog("BetSumByDay End");
        // } else {
        //     $objResult= new \StdClass;
        //     $objResult->bet_money_sum = 0;
        //     $objResult->win_money_sum = 0;
        // }
        
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

    public function updateBet($objbet)
    {

        $this->builder()->set('bet_result', $objbet->bet_result);
        $this->builder()->set('bet_win_money', $objbet->bet_win_money);
        $this->builder()->set('point_amount', $objbet->point_amount);
        $this->builder()->set('employee_amount', $objbet->employee_amount);
        $this->builder()->set('acc_time', 'NOW()', false);
        
        $this->builder()->where('bet_fid', $objbet->bet_fid);
        return $this->builder()->update();        
    }

}
