<?php 
namespace App\Models;

use CodeIgniter\Model;

class Charge_Model extends Model {

    protected $table      = 'member_charge';
    protected $primaryKey = 'charge_fid';

    protected $returnType = 'object'; 
    protected $allowedFields = ['charge_emp_fid', 'charge_mb_uid', 'charge_mb_realname', 'charge_mb_phone', 
        'charge_money', 'charge_time_require', 'charge_action_state', 'charge_action_uid', 'charge_time_process',
        'charge_money_before', 'charge_money_after', 'charge_client_delete'];

    
    public function register($data)
    {
        try {
            return $this->insert($data);
        } catch (\Exception $e) {  
            return false;
        }
        return false;

    }

    public function wait($user_id){
        $where = "charge_mb_uid = '".$user_id."' ";
        $where.= " AND charge_client_delete = '".STATE_DISABLE."' ";
        $where.= " AND (charge_action_state = '".STATE_ACTIVE."' OR charge_action_state = '".STATE_WAIT."') ";

        $charge = $this->where($where)
                    ->first();

        return !is_null($charge);
    }

    public function deleteByClient($reqData){
        
        $where = " charge_mb_uid = ".$this->db->escape($reqData['req_uid'])." ";
        $where.= " AND charge_action_state != ".STATE_ACTIVE." AND charge_action_state != ".STATE_WAIT." ";
        
        if($reqData['charge_id'] > 0)
            $where.= " AND charge_fid = ".$this->db->escape($reqData['charge_id'])." ";
            
        return $this->set('charge_client_delete', STATE_ACTIVE)
                    ->where($where)
                    ->update();
    }

    public function searchCount($reqData){

        $where = " WHERE charge_time_require >= ".$this->db->escape($reqData['start_at']);
        $where.= " AND charge_time_require <= ".$this->db->escape($reqData['end_at']);
        $where.= " AND charge_mb_uid = ".$this->db->escape($reqData['req_uid']);
        $where.= " AND charge_client_delete = '".STATE_DISABLE."' ";

        $strSql = "SELECT count('charge_fid') as count FROM ".$this->table;
        $strSql .= $where;

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result->count; 
    }

    public function searchList($reqData){
        
        $getFields = ['charge_fid', 'charge_mb_uid', 'charge_mb_realname', 'charge_money', 'charge_time_require', 
            'charge_action_state', 'charge_action_uid', 'charge_time_process',
            'charge_money_before', 'charge_money_after' ]; 
        $strTbColum = " ".implode(", ", $getFields);
        
        $where= " WHERE charge_time_require >= ".$this->db->escape($reqData['start_at']);
        $where.= " AND charge_time_require <= ".$this->db->escape($reqData['end_at']);
        $where.= " AND charge_mb_uid = ".$this->db->escape($reqData['req_uid']);
        $where.= " AND charge_client_delete = '".STATE_DISABLE."' ";

        $strSql = " SELECT ".$strTbColum." FROM ".$this->table;
        $strSql .= $where;

        $page = $reqData['page'];
        $count = $reqData['count'];
        if($page < 1)
            return NULL;
        if($count < 1)
            return NULL;
        
        $nStartRow = ($page-1) * $count ;

        $strSql.=" ORDER BY charge_fid DESC LIMIT ".$nStartRow.", ".$count;

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        return $result;
    }

}
