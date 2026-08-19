<?php
namespace App\Models;
use CodeIgniter\Model;

class SlBet_Model extends Model 
{
    protected $table = 'bet_slot';
    protected $returnType = 'object'; 
    protected $allowedFields = [
        'bet_idx', 
        'bet_emp_fid', 
        'bet_mb_uid', 
        'bet_round_no', 
        'bet_time', 
        'bet_money', 
        'bet_win_money',
        'bet_agent_id', 
        'bet_player_id', 
        'bet_game_id' ,         //GAME_SLOT_THEPLUS || GAME_SLOT_GSPLAY || GAME_SLOT_GOLD
        'bet_game_type',        //Third Party No
        'bet_table_code',       //Slot Game Id
        'bet_choice', 
        'bet_result',
        'point_amount', 
        'company_amount',        
    ];
    protected $primaryKey = 'bet_fid';
    private $mMemberTable = 'member';
    private $mGameTable = 'slot_game';
    private $mPrdTable = 'slot_prd';
    private $mRewardTable = 'bet_reward';
    
    function getBetAccount($objEmp, $arrReqData){
        if(is_null($objEmp)){
            return null;
        }

        $strWhere = "";
        if($arrReqData['game'] == GAME_SLOT_ALL){
            $gameId1 = GAME_SLOT_THEPLUS;
            if($_ENV['app.slot'] == APP_SLOT_KGON)
                $gameId1 = GAME_SLOT_KGON;
            else if($_ENV['app.slot'] == APP_SLOT_STAR)
                $gameId1 = GAME_SLOT_STAR;
            else if($_ENV['app.slot'] == APP_SLOT_RAVE)
                $gameId1 = GAME_SLOT_RAVE;
            else if($_ENV['app.slot'] == APP_SLOT_TREEM)
                $gameId1 = GAME_SLOT_TREEM;
            else if($_ENV['app.slot'] == APP_SLOT_SIGMA)
                $gameId1 = GAME_SLOT_SIGMA;

            $gameId2 = GAME_SLOT_GSPLAY;
            if($_ENV['app.fslot'] == APP_FSLOT_GOLD)
                $gameId2 = GAME_SLOT_GOLD;

            $strWhere.=" WHERE bet_game_id IN ('".$gameId1."', '".$gameId2."') ";
        } else $strWhere=" WHERE bet_game_id = '".$arrReqData['game']."' ";

        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strWhere.=" AND ".getBetTimeRange($arrReqData, $this->db);
        }
        if(strlen($arrReqData['user']) > 0){
            $strWhere.=" AND bet_mb_uid = ".$this->db->escape($arrReqData['user']);
        }
        if(intval($arrReqData['mode']) > 0){
            if($arrReqData['game'] == GAME_SLOT_ALL){
                $strWhere.=" AND bet_game_type IN ( SELECT code FROM ".$this->mPrdTable." WHERE code = ".$this->db->escape($arrReqData['mode'])." OR ref_code = ".$this->db->escape($arrReqData['mode'])." ) ";
            } else {
                $strWhere.="  AND bet_game_type = ".$this->db->escape($arrReqData['mode']);
            }
        }
        $strSql = "";
        if($objEmp->mb_level < LEVEL_ADMIN){
            $strWhere.=" AND bet_mb_uid in ( SELECT mb_uid FROM  tbmember UNION ALL SELECT '".$objEmp->mb_uid."' AS mb_uid ) ";

            $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_nickname ";
            $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid, r.mb_nickname ";

            $strSql = " WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSql .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
            $strSql .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
            $strSql .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";
        }

        //총배팅금, 적중금
        $arrSum = array();
        $strSql .= " SELECT SUM(bet_money) AS bet_money_sum, SUM(bet_win_money) AS win_money_sum, SUM(company_amount) AS company_amount, ";
        $strSql .= " SUM(CASE WHEN bet_win_money= 0 THEN bet_money ELSE 0 END) AS loss_money_sum, ";
        $strSql .= " SUM(CASE WHEN bet_win_money > 0 THEN bet_win_money-bet_money ELSE 0 END) AS benefit_money_sum ";
        $strSql .= " FROM ".$this->table;
        $strSql .= $strWhere;
        // writeLog($strSql);
        $objResult = $this -> db -> query($strSql)->getRow();
        // writeLog("getBetAccount End");
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
        //총공배팅금
        $nSum = 0;
        if(!is_null($objResult->company_amount)) {
            $nSum = $objResult->company_amount;
        }
        $arrSum[4] = $nSum;
        
        return $arrSum;
    }


    function search($objEmp, $arrReqData)
    {
        if(is_null($objEmp)){
            $result = null;
            return $result;
        }

        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_nickname, mb_slot_uid, mb_fslot_id  ";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid, r.mb_nickname, r.mb_slot_uid, r.mb_fslot_id  ";
        
        $strWhere= "";
        if($arrReqData['game'] == GAME_SLOT_ALL){
            $gameId1 = GAME_SLOT_THEPLUS;
            if($_ENV['app.slot'] == APP_SLOT_KGON)
                $gameId1 = GAME_SLOT_KGON;
            else if($_ENV['app.slot'] == APP_SLOT_STAR)
                $gameId1 = GAME_SLOT_STAR;
            else if($_ENV['app.slot'] == APP_SLOT_RAVE)
                $gameId1 = GAME_SLOT_RAVE;
            else if($_ENV['app.slot'] == APP_SLOT_TREEM)
                $gameId1 = GAME_SLOT_TREEM;
            else if($_ENV['app.slot'] == APP_SLOT_SIGMA)
                $gameId1 = GAME_SLOT_SIGMA;

            $gameId2 = GAME_SLOT_GSPLAY;
            if($_ENV['app.fslot'] == APP_FSLOT_GOLD)
                $gameId2 = GAME_SLOT_GOLD;
                
            $strWhere.=" WHERE bet_game_id IN ('".$gameId1."', '".$gameId2."') ";
            
        } else $strWhere.=" WHERE bet_game_id = '".$arrReqData['game']."' ";
        
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strWhere.=" AND ".getBetTimeRange($arrReqData, $this->db);
        }
        if(strlen($arrReqData['user']) > 0){
            $strWhere.=" AND bet_mb_uid = ".$this->db->escape($arrReqData['user']);
        }
        if(intval($arrReqData['mode']) > 0){
            if($arrReqData['game'] == GAME_SLOT_ALL){
                $strWhere.=" AND bet_game_type IN ( SELECT code FROM ".$this->mPrdTable." WHERE code = ".$this->db->escape($arrReqData['mode'])." OR ref_code = ".$this->db->escape($arrReqData['mode'])." ) ";
            } else {
                $strWhere.=" AND bet_game_type = ".$this->db->escape($arrReqData['mode']);
            }
        }
        if($objEmp->mb_level < LEVEL_ADMIN){
            if(array_key_exists('bet.blank_en', $_ENV) && $_ENV['bet.blank_en']){
                $strWhere.=" AND point_amount = '0' ";
            }
            $strWhere.=" AND bet_mb_uid in ( SELECT mb_uid FROM  tbmember UNION ALL SELECT '".$objEmp->mb_uid."' AS mb_uid ) ";
        }
        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
        $strWhere.=" ORDER BY bet_fid DESC LIMIT ".$nStartRow.", ".$arrReqData['count'];

        $strSql = "SELECT bet_fid, bet_idx, bet_mb_uid, bet_round_no, bet_time, bet_money, bet_win_money, bet_player_id, bet_game_type, ";
        $strSql .= " bet_table_code, bet_choice, rw_mb_uid, rw_point,  ";
        $strSql .= $this->mPrdTable.".name_kr as prd_name, name_ko as game_name";
        $strSql .= " FROM ( ";

        $tbBetSearch = "bet_search";

        if($objEmp->mb_level < LEVEL_ADMIN){

                $strSql .= " WITH RECURSIVE tbmember (".$strTbColum.") AS";
                $strSql .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
                $strSql .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
                $strSql .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

                $strSql .= " SELECT * FROM ".$this->table;                
                // $strSql .="  JOIN (SELECT  * FROM tbmember UNION SELECT ".$strTbColum." FROM ".$this->mMemberTable." where mb_fid='".$objEmp->mb_fid."'";           
                // $strSql .=" ) AS mb_table ";
                // $strSql .=" ON ".$this->table.".bet_mb_uid = mb_table.mb_uid ";
                
            $strSql .=$strWhere.") ".$tbBetSearch;
            
            //Join bet_reward
            $strSql .= '  LEFT JOIN '.$this->mRewardTable.' ON '.$this->mRewardTable.".rw_game = ".$tbBetSearch.".bet_game_id ";
                $strSql .= ' AND '.$tbBetSearch.'.bet_fid = '.$this->mRewardTable.'.rw_bet_id ';
                $strSql .= ' AND '.$this->mRewardTable.".rw_mb_uid = '".$objEmp->mb_uid."' ";
            
        } else{
            $strSql .= " SELECT * FROM ".$this->table;  
            
            $strSql .=$strWhere.") ".$tbBetSearch;
            // $strSql .= " JOIN ".$this->mMemberTable." ON ".$tbBetSearch.".bet_mb_uid = ".$this->mMemberTable.".mb_uid ";
            
            //Join bet_reward
            $strSql .= " LEFT JOIN ".$this->mRewardTable." ON rw_state = '0' ";
            $strSql .= " AND ".$this->mRewardTable.".rw_game = ".$tbBetSearch.".bet_game_id ";
            $strSql .= " AND ".$tbBetSearch.'.bet_fid = '.$this->mRewardTable.'.rw_bet_id ';
            $strSql .= " AND ".$this->mRewardTable.".rw_mb_uid = ".$tbBetSearch.".bet_mb_uid ";
        
        }
        $strSql .= " LEFT JOIN ".$this->mGameTable." ON ".$tbBetSearch.".bet_table_code = ".$this->mGameTable.".game_code ";
        $strSql .= " AND ".$this->mGameTable.".prd_code = ".$tbBetSearch.".bet_game_type ";
        $strSql .= " AND ".$this->mGameTable.".cat = ".$tbBetSearch.".bet_game_id ";

        $strSql .= " LEFT JOIN ".$this->mPrdTable." ON ".$tbBetSearch.".bet_game_type = ".$this->mPrdTable.".code ";
        $strSql .= " AND ".$this->mPrdTable.".cat = ".$tbBetSearch.".bet_game_id ";
        $strSql .= " ORDER BY bet_time  DESC";
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

        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_slot_uid, mb_fslot_id ";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid, r.mb_slot_uid, r.mb_fslot_id ";

         $strSql = "";
        if($objEmp->mb_level < LEVEL_ADMIN){


            $strSql = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSql .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
            $strSql .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
            $strSql .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

            $strSql .= "SELECT count(bet_fid) as count  FROM ".$this->table;

            // $strSql .="  JOIN (SELECT  * FROM tbmember UNION SELECT ".$strTbColum." FROM ".$this->mMemberTable." where mb_fid='".$objEmp->mb_fid."'";         
            // $strSql .=" ) AS mb_table ";
            // $strSql .=" ON ".$this->table.".bet_mb_uid = mb_table.mb_uid ";
        } else {
            $strSql .= "SELECT count(bet_fid) as count  FROM ".$this->table;
            // $strSql .= " JOIN ".$this->mMemberTable." ON ".$this->table.".bet_mb_uid = ".$this->mMemberTable.".mb_uid ";
        }

        if($arrReqData['game'] == GAME_SLOT_ALL){
            $gameId1 = GAME_SLOT_THEPLUS;
            if($_ENV['app.slot'] == APP_SLOT_KGON)
                $gameId1 = GAME_SLOT_KGON;
            else if($_ENV['app.slot'] == APP_SLOT_STAR)
                $gameId1 = GAME_SLOT_STAR;
            else if($_ENV['app.slot'] == APP_SLOT_RAVE)
                $gameId1 = GAME_SLOT_RAVE;
            else if($_ENV['app.slot'] == APP_SLOT_TREEM)
                $gameId1 = GAME_SLOT_TREEM;
            else if($_ENV['app.slot'] == APP_SLOT_SIGMA)
                $gameId1 = GAME_SLOT_SIGMA;
            
            $gameId2 = GAME_SLOT_GSPLAY;
            if($_ENV['app.fslot'] == APP_FSLOT_GOLD)
                $gameId2 = GAME_SLOT_GOLD;
                
            $strSql.=" WHERE bet_game_id IN ('".$gameId1."', '".$gameId2."') ";

        } else $strSql.=" WHERE bet_game_id = ".$this->db->escape($arrReqData['game'])." ";
        
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strSql.=" AND ".getBetTimeRange($arrReqData, $this->db);
        }
        if(strlen($arrReqData['user']) > 0){
            $strSql.=" AND bet_mb_uid = ".$this->db->escape($arrReqData['user']);
        }
        if(intval($arrReqData['mode']) > 0){
            if($arrReqData['game'] == GAME_SLOT_ALL){
                $strSql.="  AND bet_game_type IN ( SELECT code FROM ".$this->mPrdTable." WHERE code = ".$this->db->escape($arrReqData['mode'])." OR ref_code = ".$this->db->escape($arrReqData['mode'])." ) ";
            } else {
                $strSql.="  AND bet_game_type = ".$this->db->escape($arrReqData['mode']);
            }
        }
        if($objEmp->mb_level < LEVEL_ADMIN){
            if(array_key_exists('bet.blank_en', $_ENV) && $_ENV['bet.blank_en']){
                $strSql.=" AND point_amount = '0' ";
            }
            $strSql.=" AND bet_mb_uid in ( SELECT mb_uid FROM  tbmember UNION ALL SELECT '".$objEmp->mb_uid."' AS mb_uid ) ";
        }
        // writeLog($strSql);

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        // writeLog("searchCount End");
        
        return $result; 

    }

    function getBetSumByDay($arrReqInfo, $objConf){

        $arrSum = array();
        $strSql = " SELECT SUM(bet_money) AS bet_money_sum, SUM(bet_win_money) AS win_money_sum  FROM ".$this->table;
        $strSql .= " WHERE bet_time >= '".$arrReqInfo['start']."' "; //AND bet_time <= '".$arrReqInfo['end']."' 
        $strSql .= " AND bet_game_id = ".$objConf->game_index." ";

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
           
        return $arrSum;
    }


}
