<?php
namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class Charge_Model extends Model 
{
    protected $table = 'member_charge';
    protected $returnType = 'object'; 
    protected $allowedFields = [
        'charge_emp_fid', 
        'charge_mb_uid', 
        'charge_mb_realname', 
        'charge_mb_phone', 
        'charge_money', 
        'charge_time_require', 
        'charge_action_state', 
        'charge_action_uid',
        'charge_time_process', 
        'charge_money_bonnus', 
        'charge_money_before', 
        'charge_money_after', 
        'charge_state_delete', 
        'charge_client_delete', 
        'charge_alarm_state',
    ];
    protected $primaryKey = 'charge_fid';
    private $mMemberTable = 'member';

    function gets(){
    	$strSql = "SELECT ".$this->table.".*, member.mb_nickname, member.mb_money FROM ".$this->table;
    	$strSql .= " JOIN member ON ".$this->table.".charge_mb_uid = member.mb_uid ";
    	$strSql .= " WHERE charge_state_delete = '0' ";
    	$strSql .= " ORDER BY charge_fid DESC ";
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result;  
    }

    function get($strChargeFid){
        return $this->where('charge_fid', $strChargeFid)->first();
    }

    
    public function register($data)
    {
        try {
            return $this->insert($data);
        } catch (\Exception $e) {  
            return false;
        }
        return false;

    }
    
	function deleteState($strChargeFid, $bDelete){

		$this->builder()->set('charge_state_delete', $bDelete?1:0);
		$this->builder()->where('charge_fid', $strChargeFid);
    	return $this->builder()->update();
    }

    function permit($objCharge){

		$this->builder()->set('charge_action_state', $objCharge->charge_action_state);
		$this->builder()->set('charge_action_uid', $objCharge->charge_action_uid);
		$this->builder()->set('charge_time_process', 'NOW()', false);
		$this->builder()->set('charge_money_after', $objCharge->charge_money_after);

		$this->builder()->where('charge_fid', $objCharge->charge_fid);
    	return $this->builder()->update();
    }

     function getWaitCnt(){
        $strSql = "SELECT COUNT(*)  AS charge_wait_cnt FROM ".$this->table;
        $strSql .= " JOIN member ON ".$this->table.".charge_mb_uid = member.mb_uid ";
        $strSql .= " WHERE charge_action_state = '".STATE_ACTIVE."'  AND charge_state_delete = '0' ";
        
        $objResult = $this-> db -> query($strSql)->getRow();
        if(is_null($objResult->charge_wait_cnt)) return 0;
        else return  $objResult->charge_wait_cnt;
    }

    function getMomentCnt(){
        $strSql = "SELECT COUNT(*)  AS charge_moment_cnt FROM ".$this->table;
        $strSql .= " JOIN member ON ".$this->table.".charge_mb_uid = member.mb_uid ";
        $strSql .= " WHERE charge_action_state = '".STATE_WAIT."'  AND charge_state_delete = '0' ";
        
        $objResult = $this-> db -> query($strSql)->getRow();
        if(is_null($objResult->charge_moment_cnt)) return 0;
        else return  $objResult->charge_moment_cnt;
    }

    //일충전금액
    function calcAdminCharge($arrReqData){
        
        $strSQL = "SELECT SUM(charge_money) AS charge_sum FROM ".$this->table;
        $strSQL.=" WHERE (charge_action_state = '".STATE_VERIFY."' OR charge_action_state = '".STATE_HOT."') ";
        if(array_key_exists('start', $arrReqData) && strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strSQL.=" AND charge_time_require >= ".$this->db->escape($arrReqData['start']." 00:00:00")." AND charge_time_require <= ".$this->db->escape($arrReqData['end']." 23:59:59") ; 
        }
        if(array_key_exists('mb_uid', $arrReqData) && strlen($arrReqData['mb_uid']) > 0){
            $strSQL.=" AND charge_mb_uid = ".$this->db->escape($arrReqData['mb_uid']);
        }
        $strSQL .= " AND charge_mb_uid NOT IN (SELECT mb_uid FROM ".$this->mMemberTable." WHERE mb_level >= ".LEVEL_ADMIN.") ";

        $objResult = $this -> db -> query($strSQL)->getRow();
        if(is_null($objResult->charge_sum)) return 0;
        else return  $objResult->charge_sum;        
    }  
    //유저충전금 통계
    function calcUserChargeStat($uid){

        $strSel = "SELECT SUM(charge_money) AS charge_sum FROM ".$this->table; 
        
        $strCon=" WHERE (charge_action_state = '".STATE_VERIFY."' OR charge_action_state = '".STATE_HOT."') ";
        $strCon.=" AND charge_mb_uid = ".$this->db->escape($uid);

        $tmNow = time();
        $strToday = date( 'Y-m-d', $tmNow );

        //Yesterday
        $tmYesterday = strtotime("-24 hours", $tmNow);
        $arrReqData['start'] = date("Y-m-d", $tmYesterday);
        $arrReqData['end'] = $arrReqData['start'];
        $strSQL = $strSel.$strCon;  
        $strSQL.= " AND ".getTimeRange("charge_time_require", $arrReqData, $this->db);
        //Today
        $arrReqData['start'] = $strToday;
        $strSQL.= " UNION ALL ".$strSel.$strCon;  
        $strSQL.=" AND charge_time_require >= '".$arrReqData['start']."'";
        //Week
        $tmWeek = strtotime("-6 days", $tmNow);
        $arrReqData['start'] = date("Y-m-d", $tmWeek);
        $strSQL.= " UNION ALL ".$strSel.$strCon;  
        $strSQL.=" AND charge_time_require >= '".$arrReqData['start']."'";
        //Month
        $arrReqData['start'] = date('Y-m')."-01";
        $strSQL.= " UNION ALL ".$strSel.$strCon;  
        $strSQL.=" AND charge_time_require >= '".$arrReqData['start']."'";
        //Total
        $strSQL.= " UNION ALL ".$strSel.$strCon;  

        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog($strSQL);

        return $this->db->query($strSQL)->getResult();       
    }  
    //충전금액 (하부포함)
    function calcChargeMoney($objEmp, $arrReqData){

        $strTbColum = " mb_fid, mb_uid, mb_emp_fid ";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_emp_fid ";

        $strSQL = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
        $strSQL .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
        $strSQL .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
        $strSQL .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

        $strSQL .= " SELECT SUM(charge_money) AS charge_money, charge_mb_uid FROM ".$this->table;
        $strSQL.=" WHERE (charge_action_state = '2'  OR charge_action_state = '5') ";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 )
            $strSQL.=" AND ".getTimeRange("charge_time_require", $arrReqData, $this->db);
        $strSQL .= " AND charge_mb_uid IN (SELECT mb_uid from tbmember UNION ALL SELECT '".$objEmp->mb_uid."' as mb_uid) ";
            
        // writeLog($strSQL);
        $objResult = $this -> db -> query($strSQL)->getRow();
        // writeLog("calcChargeMoney END");

        $nTotalCharge = 0;
        if(!is_null($objResult->charge_money)) $nTotalCharge += $objResult->charge_money;

        return $nTotalCharge;
        
    }


    function search($arrReqData)
    {
        $strSql = "SELECT ".$this->table.".*, member.mb_fid, member.mb_nickname, (".allMoneySql($this->mMemberTable).") AS mb_money FROM ".$this->table;
        $strSql .= " JOIN member ON ".$this->table.".charge_mb_uid = member.mb_uid ";
        $strSql .= " WHERE ( charge_state_delete = '0' ";
        if(array_key_exists('start', $arrReqData) && strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strSql.=" AND charge_time_require >= ".$this->db->escape($arrReqData['start']." 00:00:00")." AND charge_time_require <= ".$this->db->escape($arrReqData['end']." 23:59:59") ; 
        }
        if(strlen($arrReqData['mb_uid']) > 0){
            $strSql.=" AND charge_mb_uid = ".$this->db->escape($arrReqData['mb_uid']);
        }
        $strSql .= " ) OR charge_action_state IN (1, 4) ";

        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;

        $strSql.=" ORDER BY charge_fid DESC LIMIT ".$nStartRow.", ".$arrReqData['count'];

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result; 

    }


    function searchCount($arrReqData)
    {
        $strSql = "SELECT count(*) as count FROM ".$this->table;
        $strSql .= " WHERE ( charge_state_delete = '0' ";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strSql.=" AND charge_time_require >= ".$this->db->escape($arrReqData['start']." 00:00:00")." AND charge_time_require <= ".$this->db->escape($arrReqData['end']." 23:59:59") ; 
        }
        if(strlen($arrReqData['mb_uid']) > 0){
            $strSql.=" AND charge_mb_uid = ".$this->db->escape($arrReqData['mb_uid']);
        }
        $strSql .= " ) OR charge_action_state IN (1, 4) ";
        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 

    }


}