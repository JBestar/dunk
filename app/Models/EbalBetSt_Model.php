<?php
namespace App\Models;
use CodeIgniter\Model;

class EbalBetSt_Model extends Model 
{
    protected $table = 'bet_ebal_st';
    protected $returnType = 'object'; 
    protected $allowedFields = [
        'bet_mb_fid', 
        'bet_mb_uid', 
        'bet_teble_code',
        'bet_table_name',
        'bet_money', 
        'bet_win_money',
        'bet_loss_money',
        'bet_prof_money',
        'bet_balance', 
        'bet_win_balance',
        'bet_loss_balance',
        'bet_prof_balance',
        'bet_cnt',  //정상
        'bet_cnt_b',
        'bet_cnt_p',
        'bet_cnt_n', //미처리
        'bet_cnt_y', //처리
        'bet_start',
        'bet_end'
    ];
    // protected $primaryKey = 'bet_fid';
    private $mMemberTable = 'member';

    public function setType($gameId){
        if($gameId == GAME_AUTO_PRAG)
            $this->table = 'bet_prbal_st';
        else $this->table = 'bet_ebal_st';
    }

    function getBetAccount($objEmp, $arrReqData){
        
        // if(array_key_exists("state", $arrReqData) && $arrReqData['state'] > 0)
        //     return null;
        if(is_null($objEmp)){
            return null;
        }

        $strCondition = " WHERE ";
        $strCondition.= getStatRange('bet_start', 'bet_end', $arrReqData, $this->db);
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
        
        if(array_key_exists("state", $arrReqData) && $arrReqData['state'] == 1){ //proc 
            $strSql .= " SELECT SUM(bet_proc_money) AS bet_money_sum, SUM(bet_proc_win) AS win_money_sum ";
        } else if(array_key_exists("state", $arrReqData) && $arrReqData['state'] == 2){ //no proc 
            $strSql .= " SELECT SUM(bet_nproc_money) AS bet_money_sum ";
        } else {
            if(array_key_exists('type', $arrReqData) && intval($arrReqData['type']) == 1){               //only balance betting
                $strSql .= " SELECT SUM(bet_balance) AS bet_money_sum, SUM(bet_win_balance) AS win_money_sum, ";
                $strSql .= " SUM(bet_loss_balance) AS loss_money_sum, ";
                $strSql .= " SUM(CASE WHEN bet_win_balance > 0 THEN bet_win_balance-bet_balance ELSE 0 END) AS benefit_money_sum ";
            } else if(array_key_exists('type', $arrReqData) && intval($arrReqData['type']) == 0){        //only press betting
                $strSql .= " SELECT SUM(bet_money-bet_balance) AS bet_money_sum, SUM(bet_win_money-bet_win_balance) AS win_money_sum, ";
                $strSql .= " SUM(bet_loss_money-bet_loss_balance) AS loss_money_sum, ";
                $strSql .= " SUM(bet_prof_money-bet_prof_balance) AS benefit_money_sum ";
            } else {                                            //all betting
                $strSql .= " SELECT SUM(bet_money) AS bet_money_sum, SUM(bet_win_money) AS win_money_sum, ";
                $strSql .= " SUM(bet_loss_money) AS loss_money_sum, ";
                $strSql .= " SUM(bet_prof_money) AS benefit_money_sum ";
            }
        } 
        

        $strSql .= " FROM ".$this->table;
        $strSql .= $strCondition;
        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog($strSql);
        $objResult = $this -> db -> query($strSql)->getRow();
        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog("EbalBetSt>> account End");

        $nSum = 0;
        if(property_exists($objResult, 'bet_money_sum')) {
            if(!is_null($objResult->bet_money_sum)) {
                $nSum = $objResult->bet_money_sum;
            }
            $arrSum[0] = $nSum;
        }
        if(property_exists($objResult, 'win_money_sum')) {
            $nSum = 0;
            if(!is_null($objResult->win_money_sum)) {
                $nSum = $objResult->win_money_sum;
            }
            $arrSum[1] = $nSum;
        }
        //총미적중금
        if(property_exists($objResult, 'loss_money_sum')) {
            $nSum = 0;
            if(!is_null($objResult->loss_money_sum)) {
                $nSum = $objResult->loss_money_sum;
            }
            $arrSum[2] = $nSum;
        }
        //총당첨금
        if(property_exists($objResult, 'benefit_money_sum')) {
            $nSum = 0;
            if(!is_null($objResult->benefit_money_sum)) {
                $nSum = $objResult->benefit_money_sum;
            }
            $arrSum[3] = $nSum;
        }        
        
        return $arrSum;
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

            $strSql .= "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSql .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
            $strSql .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
            $strSql .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

        } 
        if(array_key_exists("state", $arrReqData) && $arrReqData['state'] == 1){                //처리
            $strSql .= "SELECT SUM(bet_cnt_y)";
        } elseif(array_key_exists("state", $arrReqData) && $arrReqData['state'] == 2){          //미처리
            $strSql .= "SELECT SUM(bet_cnt_n)";                                                 
        } else {
            if(array_key_exists('type', $arrReqData) && intval($arrReqData['type']) >= 0){
                if(intval($arrReqData['type']) == 1)                        //넘기기
                    $strSql .= "SELECT SUM(bet_cnt_b)";            
                else $strSql .= "SELECT SUM(bet_cnt_p)";                    //누르기
            } else $strSql .= "SELECT SUM(bet_cnt)";                        //정상
        }
        $strSql .= " as count FROM ".$this->table;

        $strSql .= " WHERE ";
        $strSql.= getStatRange('bet_start', 'bet_end', $arrReqData, $this->db);

        if(strlen($arrReqData['user']) > 0){
            $strSql.=" AND bet_mb_fid = ".$this->db->escape($arrReqData['user']);
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
            writeLog("EbalBetSt>> searchCount End");

        $result = $query -> getRow();
        
        return $result; 

    }

    function getBetSumByDay($arrReqInfo){

        $arrSum = array();

        $strSql = " SELECT SUM(bet_money) AS bet_money_sum, SUM(bet_win_money) AS win_money_sum  FROM ".$this->table;
        $strSql .= " WHERE bet_start >= '".$arrReqInfo['start']."' " ;//AND bet_time <= '".$arrReqInfo['end']."' ";
        $strSql .= " AND bet_mb_fid NOT IN (SELECT mb_fid FROM ".$this->mMemberTable." WHERE mb_level >= ".LEVEL_ADMIN." OR mb_state_test = ".STATE_ACTIVE." ) ";

        // writeLog($strSql);
        $objResult = $this -> db -> query($strSql)->getRow();
        // writeLog("BetSumByDay End");
        
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
