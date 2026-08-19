<?php
namespace App\Models;
use CodeIgniter\Model;

class Exchange_Model extends Model {
	
	protected $table = 'member_exchange';
    protected $returnType = 'object'; 
    protected $allowedFields = [
        'exchange_emp_fid',
        'exchange_mb_uid', 
        'exchange_mb_phone', 
        'exchange_money', 
        'exchange_time_require', 
        'exchange_action_state', 
        'exchange_action_uid', 
        'exchange_time_process', 
        'exchange_bank_name', 
        'exchange_bank_account', 
        'exchange_bank_serial', 
        'exchange_money_before', 
        'exchange_money_after', 
        'exchange_state_delete', 
        'exchange_client_delete', 
        'exchange_alarm_state',
    ];
    protected $primaryKey = 'exchange_fid';
	private $mMemberTable = 'member';

    function gets(){
    	$strSql = "SELECT ".$this->table.".*, member.mb_nickname, member.mb_money FROM ".$this->table;
    	$strSql .= " JOIN member ON ".$this->table.".exchange_mb_uid = member.mb_uid ";
    	$strSql .= " WHERE exchange_state_delete = '0' ";
    	$strSql .= " ORDER BY exchange_fid DESC ";
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result;  
    }

    function get($strExchangeFid){
        return $this->where('exchange_fid', $strExchangeFid)->first();
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
    
	function deleteState($strExchangeFid, $bDelete){

		$this->builder()->set('exchange_state_delete', $bDelete?1:0);
		$this->builder()->where('exchange_fid', $strExchangeFid);
    	return $this->builder()->update();
    }

    function permit($objExchange){

		$this->builder()->set('exchange_action_state', $objExchange->exchange_action_state);
		$this->builder()->set('exchange_action_uid', $objExchange->exchange_action_uid);
		$this->builder()->set('exchange_time_process', 'NOW()', false);
		$this->builder()->set('exchange_money_after', $objExchange->exchange_money_after);

		$this->builder()->where('exchange_fid', $objExchange->exchange_fid);
    	return $this->builder()->update();
    }

    function getWaitCnt(){
        $strSql = "SELECT COUNT(*)  AS exchange_wait_cnt FROM ".$this->table;
        $strSql .= " JOIN member ON ".$this->table.".exchange_mb_uid = member.mb_uid ";
        $strSql .= " WHERE exchange_action_state = '".STATE_ACTIVE."'  AND exchange_state_delete = '0' ";
        
        $objResult = $this -> db -> query($strSql)->getRow();
        if(is_null($objResult->exchange_wait_cnt)) return 0;
        else return  $objResult->exchange_wait_cnt;

    }

    function getWaitAmount(){
        $strSql = "SELECT SUM(exchange_money) AS exchange_money FROM ".$this->table;
        $strSql .= " JOIN member ON ".$this->table.".exchange_mb_uid = member.mb_uid ";
        $strSql .= " WHERE ( exchange_action_state = '".STATE_ACTIVE."' OR exchange_action_state = '".STATE_WAIT."')  AND exchange_state_delete = '0' ";
        
        $objResult = $this -> db -> query($strSql)->getRow();
        if(is_null($objResult->exchange_money)) return 0;
        else return  $objResult->exchange_money;
    }
    
    function getMomentCnt(){
        $strSql = "SELECT COUNT(*)  AS exchange_moment_cnt FROM ".$this->table;
        $strSql .= " JOIN member ON ".$this->table.".exchange_mb_uid = member.mb_uid ";
        $strSql .= " WHERE exchange_action_state = '".STATE_WAIT."'  AND exchange_state_delete = '0' ";
        
        $objResult = $this -> db -> query($strSql)->getRow();
        if(is_null($objResult->exchange_moment_cnt)) return 0;
        else return  $objResult->exchange_moment_cnt;
    }

    //금일 환전금액
    function calcAdminExchange($arrReqData){
        
        $strSQL = "SELECT SUM(exchange_money) AS exchange_sum FROM ".$this->table;
        $strSQL.=" WHERE (exchange_action_state = '".STATE_VERIFY."' OR exchange_action_state = '".STATE_HOT."') ";
        if(array_key_exists('start', $arrReqData) && strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 )
            $strSQL.=" AND exchange_time_require >= ".$this->db->escape($arrReqData['start']." 00:00:00")." AND exchange_time_require <= ".$this->db->escape($arrReqData['end']." 23:59:59") ; 
        if(array_key_exists('mb_uid', $arrReqData) && strlen($arrReqData['mb_uid']) > 0)
            $strSQL.=" AND exchange_mb_uid = ".$this->db->escape($arrReqData['mb_uid']);

        $strSQL .= " AND exchange_mb_uid NOT IN (SELECT mb_uid FROM ".$this->mMemberTable." WHERE mb_level >= ".LEVEL_ADMIN.") ";

        $objResult = $this -> db -> query($strSQL)->getRow();
        if(is_null($objResult->exchange_sum)) return 0;
        else return  $objResult->exchange_sum;        
    }    
    //유저환전금 통계 
    function calcUserExchangeStat($uid){

        $strCon=" WHERE (exchange_action_state = '".STATE_VERIFY."' OR exchange_action_state = '".STATE_HOT."') ";
        $strCon.=" AND exchange_mb_uid = ".$this->db->escape($uid);

        $strSel = "SELECT SUM(exchange_money) AS exchange_sum FROM ".$this->table; //Tom

        $tmNow = time();
        $strToday = date( 'Y-m-d', $tmNow );

        //Yesterday
        $tmYesterday = strtotime("-24 hours", $tmNow);
        $arrReqData['start'] = date("Y-m-d", $tmYesterday);
        $arrReqData['end'] = $arrReqData['start'];
        $strSQL = $strSel.$strCon;  
        $strSQL.= " AND ".getTimeRange("exchange_time_require", $arrReqData, $this->db);
        //Today
        $arrReqData['start'] = $strToday;
        $strSQL.= " UNION ALL ".$strSel.$strCon;  
        $strSQL.=" AND exchange_time_require >= '".$arrReqData['start']."'";
        //Week
        $tmWeek = strtotime("-6 days", $tmNow);
        $arrReqData['start'] = date("Y-m-d", $tmWeek);
        $strSQL.= " UNION ALL ".$strSel.$strCon;  
        $strSQL.=" AND exchange_time_require >= '".$arrReqData['start']."'";
        //Month
        $arrReqData['start'] = date('Y-m')."-01";
        $strSQL.= " UNION ALL ".$strSel.$strCon;  
        $strSQL.=" AND exchange_time_require >= '".$arrReqData['start']."'";
        //Total
        $strSQL.= " UNION ALL ".$strSel.$strCon;  

        return $this->db->query($strSQL)->getResult();       
    }
    //환전금액 (하부포함)
    function calcExchangeMoney($objEmp, $arrReqData){

        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid ";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid ";

        $strSQL = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
        $strSQL .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
        $strSQL .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
        $strSQL .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

        $strSQL .= " SELECT SUM(exchange_money) AS exchange_money, exchange_mb_uid FROM ".$this->table;
        $strSQL.=" WHERE (exchange_action_state = '2' OR exchange_action_state = '5')  ";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 )
            $strSQL.=" AND ".getTimeRange("exchange_time_require", $arrReqData, $this->db);
        $strSQL .= " AND exchange_mb_uid IN (SELECT mb_uid from tbmember UNION ALL SELECT '".$objEmp->mb_uid."' as mb_uid) ";
        
        // writeLog($strSQL);
        $objResult = $this -> db -> query($strSQL)->getRow();
        // writeLog("calcExchangeMoney END");

        $nTotalExchange = 0;
        if(!is_null($objResult->exchange_money)) $nTotalExchange += $objResult->exchange_money;
        
        return $nTotalExchange;
         
    }


    function search($arrReqData)
    {
        $strSql = "SELECT ".$this->table.".*, member.mb_fid, member.mb_nickname, (".allMoneySql($this->mMemberTable).") AS mb_money FROM ".$this->table;
        $strSql .= " JOIN member ON ".$this->table.".exchange_mb_uid = member.mb_uid ";
        $strSql .= " WHERE ( exchange_state_delete = '0' ";
        if(array_key_exists('start', $arrReqData) && strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strSql.=" AND exchange_time_require >= ".$this->db->escape($arrReqData['start']." 00:00:00")." AND exchange_time_require <= ".$this->db->escape($arrReqData['end']." 23:59:59") ; 
        }
        if(strlen($arrReqData['mb_uid']) > 0){
            $strSql.=" AND exchange_mb_uid = ".$this->db->escape($arrReqData['mb_uid']);
        }
        $strSql .= " ) OR exchange_action_state IN (1, 4) ";

        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;

        $strSql.=" ORDER BY exchange_fid DESC LIMIT ".$nStartRow.", ".$arrReqData['count'];

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result; 

    }


    function searchCount($arrReqData)
    {
        $strSql = "SELECT count(*) as count FROM ".$this->table;
        $strSql .= " WHERE ( exchange_state_delete = '0' ";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strSql.=" AND exchange_time_require >= ".$this->db->escape($arrReqData['start']." 00:00:00")." AND exchange_time_require <= ".$this->db->escape($arrReqData['end']." 23:59:59") ; 
        }
        if(strlen($arrReqData['mb_uid']) > 0){
            $strSql.=" AND exchange_mb_uid = ".$this->db->escape($arrReqData['mb_uid']);
        }
        $strSql .= " ) OR exchange_action_state IN (1, 4) ";

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 

    }



}