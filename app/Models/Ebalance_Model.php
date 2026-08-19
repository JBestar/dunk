<?php
namespace App\Models;
use CodeIgniter\Model;

class Ebalance_Model extends Model 
{
    protected $table = 'bet_balance';
    protected $returnType = 'object'; 
    protected $allowedFields = [
        'bet_id', 
        'bet_state', 
        'bet_site_name', 
        'bet_site_uid', 
        'bet_evol_uid', 
        'bet_game_type', 
        'bet_table_id', 
        'bet_table_name', 
        'bet_round_id', 
        'bet_tm_req', 
        'bet_amount', 
        'bet_win_amount',
        'bet_choice',
        'bet_result',
        'bet_player',
        'bet_banker',
        'bet_balance',
        'bet_type',
        'bet_mode',
        'bet_err',
        'bet_fail',

    ];
    protected $primaryKey = 'bet_id';

    public function setType($gameId){
        if($gameId == GAME_AUTO_PRAG)
            $this->table = 'bet_balance_pr';
        else $this->table = 'bet_balance';
    }

    function getBetAccount($reqData){
        
        if(array_key_exists("state", $reqData) && $reqData['state'] > 0)
            return null;

        $where = ""  ;
        $where.=" WHERE ".getTimeRange('bet_tm_req', $reqData, $this->db);
        $where.=" AND bet_fail = 0 ";
        if(strlen($reqData['user']) > 0){
            $where.=" AND bet_site_uid = ".$this->db->escape($reqData['user']);
        }
        if(strlen(trim($reqData['room'])) > 0){
            $where.=" AND bet_table_name LIKE '%".trim($reqData['room'])."%' ";
        }

        //총배팅금, 적중금
        $arrSum = array();
        $strSql = " SELECT ";
        $strSql .= " SUM(CASE WHEN bet_type=0 AND bet_result != 'Tie' THEN bet_amount ELSE 0 END ) AS bet_amount_sum, ";
        $strSql .= " SUM(CASE WHEN bet_type=0 AND bet_result != 'Tie' THEN bet_win_amount ELSE 0 END ) AS win_amount_sum, ";
        $strSql .= " SUM(CASE WHEN bet_type=2 THEN bet_amount ELSE 0 END ) AS bet_con_sum, ";
        $strSql .= " SUM(CASE WHEN bet_type=2 THEN bet_win_amount ELSE 0 END ) AS win_con_sum, ";
        // $strSql .= " SUM(CASE WHEN bet_type!=2 AND bet_result != 'Tie' THEN bet_player + bet_banker ELSE 0 END ) AS bet_user_sum, ";
        $strSql .= " SUM(CASE WHEN bet_type!=2 AND bet_result != 'Tie' THEN (bet_player + bet_banker - ABS(bet_player - bet_banker))/2 ELSE 0 END ) AS bet_bal_sum, ";
        $strSql .= " SUM(CASE WHEN bet_type!=2 AND bet_result = 'Banker' THEN (bet_player + bet_banker - ABS(bet_player - bet_banker))/2 ELSE 0 END ) AS bet_bal_banker ";
        // $strSql .= " SUM(CASE WHEN bet_type=0 AND bet_choice = 'Banker' AND bet_result = 'Banker' THEN FLOOR(bet_player DIV 1000)*50 ELSE 0 END) AS profit_sum1 ";

        // $strSql .= " SUM(CASE WHEN bet_type= 0 AND bet_result = 'Player' THEN bet_banker - FLOOR(bet_player DIV 1000)*1000 - bet_amount + bet_win_amount ELSE 0 END) AS profit_sum1, ";
        // $strSql .= " SUM(CASE WHEN bet_type= 0 AND bet_result = 'Banker' THEN bet_player - FLOOR(bet_banker DIV 1000)*950 - bet_amount + bet_win_amount ELSE 0 END) AS profit_sum2, ";
        // $strSql .= " SUM(CASE WHEN bet_type= 1 AND bet_result = 'Player' THEN bet_banker % 1000 ELSE 0 END) AS profit_sum3, ";
        // $strSql .= " SUM(CASE WHEN bet_type= 1 AND bet_result = 'Banker' THEN bet_player % 1000 + FLOOR(bet_player DIV 1000)*50 ELSE 0 END) AS profit_sum4 ";
        $strSql .= " FROM ".$this->table;

        $strSql .= $where;
        $objResult = $this -> db -> query($strSql)->getRow();
        
        // writeLog($strSql);
        //Total Betting money
        $nSum = 0;
        if(!is_null($objResult->bet_amount_sum)) {
            $nSum = intval($objResult->bet_amount_sum);
        }
        $arrSum[0] = $nSum;
        $nSum = 0;
        if(!is_null($objResult->win_amount_sum)) {
            $nSum = intval($objResult->win_amount_sum);
        }
        $arrSum[1] = $nSum;
        //Total user's Betting balane money
        $nSum = 0;
        if(!is_null($objResult->bet_bal_sum)) {
            $nSum = intval($objResult->bet_bal_sum);
        }
        $arrSum[2] = $nSum;
        //if result is banker, Total user's Betting balane money
        $nSum = 0;
        if(!is_null($objResult->bet_bal_banker)) {
            $nSum = intval($objResult->bet_bal_banker);
        }
        $arrSum[3] = $nSum;
        //totabl profit
        $arrSum[4] = $nSum * 0.05;
        //connect bet sum
        $nSum = 0;
        if(!is_null($objResult->bet_con_sum)) {
            $nSum = intval($objResult->bet_con_sum);
        }
        $arrSum[5] = $nSum;
        //connect bet win
        $nSum = 0;
        if(!is_null($objResult->win_con_sum)) {
            $nSum = intval($objResult->win_con_sum);
        }
        $arrSum[6] = $nSum;

        return $arrSum;
    }

    public function searchCount($reqData){

        $where = "";
        $where.=" WHERE  ".getTimeRange('bet_tm_req', $reqData, $this->db);
        if(strlen($reqData['bet']) > 0){
            if(intval($reqData['bet']) == 1)
                $where.=" AND bet_type IN (".BET_TYPE_ZERO.", ".BET_TYPE_NORMAL.") ";
            else if(intval($reqData['bet']) == 2)
                $where.=" AND bet_type = '".BET_TYPE_FORCE."' ";
        }
        if(strlen($reqData['user']) > 0){
            $where.=" AND bet_site_uid = ".$this->db->escape($reqData['user']);
        }
        if(array_key_exists("state", $reqData)){
            if($reqData['state'] == 0)
                $where.=" AND bet_fail = 0 "; //정상
            else
                $where.=" AND bet_fail <> 0 "; //실패
        }
        if(strlen(trim($reqData['room'])) > 0){
            $where.=" AND bet_table_name LIKE '%".trim($reqData['room'])."%' ";
        }

        $strSql = "SELECT count('bet_id') as count FROM ".$this->table;
        $strSql .= $where;

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 
    }

    public function searchList($reqData){
        
        $where = " "  ;
        $where.=" WHERE ".getTimeRange('bet_tm_req', $reqData, $this->db);
        if(strlen($reqData['bet']) > 0){
            if(intval($reqData['bet']) == 1)
                $where.=" AND bet_type IN (".BET_TYPE_ZERO.", ".BET_TYPE_NORMAL.") ";
            else if(intval($reqData['bet']) == 2)
                $where.=" AND bet_type = '".BET_TYPE_FORCE."' ";
        }
        if(strlen($reqData['user']) > 0){
            $where.=" AND bet_site_uid = ".$this->db->escape($reqData['user']);
        }
        if(array_key_exists("state", $reqData)){
            if($reqData['state'] == 0)
                $where.=" AND bet_fail = 0 "; //정상
            else
                $where.=" AND bet_fail <> 0 "; //실패
        }
        if(strlen(trim($reqData['room'])) > 0){
            $where.=" AND bet_table_name LIKE '%".trim($reqData['room'])."%' ";
        }

        $strTbColum = " ".implode(", ", $this->allowedFields);

        $strSql = " SELECT ".$strTbColum." FROM ".$this->table;
        $strSql .= $where;

        $page = $reqData['page'];
        $count = $reqData['count'];
        if($page < 1)
            return NULL;
        if($count < 1)
            return NULL;
        
        $nStartRow = ($page-1) * $count ;

        $strSql.=" ORDER BY bet_id DESC LIMIT ".$nStartRow.", ".$count;
        // writeLog($strSql);
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        return $result;
    }
}