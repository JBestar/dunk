<?php
namespace App\Models;
use CodeIgniter\Model;

class EbalLog_Model extends Model 
{
    protected $table = 'ebal_log';
    protected $returnType = 'object'; 
    protected $allowedFields = [
        'log_fid', 
        'log_mb_fid', 
        'log_mb_uid', 
        'log_data', 
        'log_type', 
        'log_memo', 
        'log_time', 
    ];
    protected $primaryKey = 'log_fid';
    
    public function register($data)
    {
        try {
            return $this->insert($data);
        } catch (\Exception $e) {  
            return false;
        }
        return false;

    }

    function search($arrReqData)
    {
        $strSql = "SELECT ".$this->table.".* FROM ".$this->table;
        $strSql.=" WHERE log_time >= '".$arrReqData['start']."' AND log_time <= '".$arrReqData['end']."'" ; 
        if(strlen($arrReqData['user']) > 0){
            $strSql.=" AND log_mb_uid = '".$arrReqData['user']."' ";
        }
        if(intval($arrReqData['type']) > 0){
            $strSql.=" AND log_type = '".$arrReqData['type']."' ";
        }
        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;

        $strSql.=" ORDER BY log_fid DESC LIMIT ".$nStartRow.", ".$arrReqData['count'];

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result; 

    }


    function searchCount($arrReqData)
    {
        $strSql = "SELECT count(*) as count FROM ".$this->table;
        $strSql.=" WHERE log_time >= '".$arrReqData['start']."' AND log_time <= '".$arrReqData['end']."'" ; 
        if(strlen($arrReqData['user']) > 0){
            $strSql.=" AND log_mb_uid = '".$arrReqData['user']."' ";
        }
        if(intval($arrReqData['type']) > 0){
            $strSql.=" AND log_type = '".$arrReqData['type']."' ";
        }
        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 

    }


}
