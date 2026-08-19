<?php 
namespace App\Models;

use CodeIgniter\Model;

class Transfer_Model extends Model {

    protected $table      = 'money_transfer';
    protected $primaryKey = 'trans_fid';

    protected $returnType = 'object'; 
    protected $allowedFields = ['trans_mb_fid', 'trans_mb_uid', 'trans_emp_fid', 'trans_amount', 
        'money_before', 'money_after', 'egg_before', 'egg_after', 'trans_type', 
        'trnas_time'];

    public function register($type, $objUser, $gPoint, $balance){
        if(is_null($objUser)) return false;    
        if($balance == 0) return true;

        $data = [
            'trans_mb_fid' => $objUser->mb_fid,
            'trans_mb_uid' => $objUser->mb_uid,
            'trans_emp_fid' => $objUser->mb_emp_fid,
            'trans_amount' => $balance,
            'money_before' => $objUser->mb_money,
            'money_after' => $objUser->mb_money - $balance,
            'egg_before' => $gPoint,
            'egg_after' => $gPoint + $balance,
            'trans_type' => $type,
            'trans_time' => date("Y-m-d H:i:s"),
        ];

        return $this->insert($data);
    }

    public function searchCount($reqData){

        $where = "trnas_mb_uid = '".$reqData['req_uid']."' ";
        $data = $this->where($where)
                     ->findAll(); 
        return count($data);
    }
 
    
    public function searchList($reqData){
        $where = "trans_mb_uid = '".$reqData['req_uid']."' ";

        $page = $reqData['page'];
        $count = $reqData['count'];
        if($page < 1)
            return NULL;
        if($count < 1)
            return NULL;

        return $this->where($where)
            ->orderBy('trans_fid', 'DESC')
            ->findAll($count, $count*($page-1)); 
    }

}