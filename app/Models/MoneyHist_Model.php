<?php 
namespace App\Models;

use CodeIgniter\Model;

class MoneyHist_Model extends Model {

    protected $table      = 'money_history';
    protected $primaryKey = 'money_fid';

    protected $returnType = 'object'; 
    protected $allowedFields = ['money_mb_fid', 'money_mb_uid', 'money_mb_emp_fid', 'money_amount', 
        'money_before', 'money_after', 'money_change_type', 'money_bet_round', 'money_bet_mode',
        'money_bet_target', 'money_update_time'];

    
    function registerPointToMoney($objMember, $nPoint)
    {
        if($nPoint == 0)
            return true;

        try {             
            $data = [  
                'money_mb_fid' => $objMember->mb_fid,
                'money_mb_uid' => $objMember->mb_uid,
                'money_mb_emp_fid' => $objMember->mb_emp_fid,
                'money_amount' => $nPoint,
                'money_before' => allMoney($objMember),
                'money_after' => allMoney($objMember) + $nPoint ,
                'money_change_type' => POINTCHANGE_EXCHANGE,
                'money_bet_round' => $objMember->mb_point,
                'money_update_time' => date("Y-m-d H:i:s")
            ];

            return $this->insert($data);
        } catch (\Exception $e) {  
            return false;
        }
        return false;
        
    }

    
    function registerBet($objMember, $arrBetData, $type)
    {
        $amount = 0 - $arrBetData['amount'];
        
        try {             
            $data = [  
                'money_mb_fid' => $objMember->mb_fid,
                'money_mb_uid' => $objMember->mb_uid,
                'money_mb_emp_fid' => $objMember->mb_emp_fid,
                'money_amount' => $amount,
                'money_before' => allMoney($objMember),
                'money_after' => allMoney($objMember) + $amount ,
                'money_change_type' => $type,
                'money_bet_round'=> $arrBetData['roundno'],
                'money_bet_mode'=> $arrBetData['mode'],
                'money_bet_target'=> $arrBetData['target'],
                'money_update_time' => date("Y-m-d H:i:s")
            ];

            return $this->insert($data);
        } catch (\Exception $e) {  
            
            return false;
        }
        return false;
    }

    function register($objMember, $amount, $type)
    {
        try {             
            $data = [  
                'money_mb_fid' => $objMember->mb_fid,
                'money_mb_uid' => $objMember->mb_uid,
                'money_mb_emp_fid' => $objMember->mb_emp_fid,
                'money_amount' => $amount,
                'money_before' => allMoney($objMember),
                'money_after' => allMoney($objMember) + $amount ,
                'money_change_type' => $type,
                'money_update_time' => date("Y-m-d H:i:s")
            ];

            return $this->insert($data);
        } catch (\Exception $e) {  
            
            return false;
        }
        return false;
    }

    public function deleteByClient($reqData){
        
        $where = " money_mb_uid = ".$this->db->escape($reqData['req_uid'])." ";
        if(array_key_exists("type", $reqData) && intval($reqData['type']) > 0){
            $where.=" AND money_change_type = ".$this->db->escape($reqData['type']);
        }
        if($reqData['money_id'] > 0)
            $where.= " AND money_fid = ".$this->db->escape($reqData['money_id'])." ";
            
        return $this->set('money_bet_mode', STATE_ACTIVE)
                    ->where($where)
                    ->update();
    }
    
    public function searchCount($reqData){

        $where = " WHERE money_update_time >= ".$this->db->escape($reqData['start_at']);
        $where.= " AND money_update_time <= ".$this->db->escape($reqData['end_at']);
        $where.= " AND money_mb_uid = ".$this->db->escape($reqData['req_uid']);
        if(array_key_exists("type", $reqData) && intval($reqData['type']) > 0){
            $where.=" AND money_change_type = ".$this->db->escape($reqData['type']);
        }
        $where.= " AND money_bet_mode = '".STATE_DISABLE."' ";

        $strSql = "SELECT count('money_fid') as count FROM ".$this->table;
        $strSql .= $where;

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result->count; 
    }

    public function searchList($reqData){
        
        $getFields = ['money_mb_fid', 'money_mb_uid', 'money_mb_emp_fid', 'money_amount', 
            'money_before', 'money_after', 'money_change_type', 'money_bet_round', 'money_bet_mode',
            'money_bet_target', 'money_update_time'];
            
        $where = " WHERE money_update_time >= ".$this->db->escape($reqData['start_at']);
        $where.= " AND money_update_time <= ".$this->db->escape($reqData['end_at']);
        $where.= " AND money_mb_uid = ".$this->db->escape($reqData['req_uid']);
        if(array_key_exists("type", $reqData) && intval($reqData['type']) > 0){
            $where.=" AND money_change_type = ".$this->db->escape($reqData['type']);
        }
        $where.= " AND money_bet_mode = '".STATE_DISABLE."' ";

        $strTbColum = " ".implode(", ", $getFields);
        
        $strSql = " SELECT ".$strTbColum." FROM ".$this->table;
        $strSql .= $where;
        
        $page = $reqData['page'];
        $count = $reqData['count'];
        if($page < 1)
            return NULL;
        if($count < 1)
            return NULL;
        
        $nStartRow = ($page-1) * $count ;

        $strSql.=" ORDER BY money_fid DESC LIMIT ".$nStartRow.", ".$count;

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        return $result;
    }


}