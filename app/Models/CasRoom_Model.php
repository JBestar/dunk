<?php 
namespace App\Models;

use CodeIgniter\Model;

class CasRoom_Model extends Model {

    protected $table      = 'casino_room';
    protected $primaryKey = 'fid';
    protected $allowedFields = ['tid', 'name', 'prd', 'nid', 'open', 'history', 'dealer', 'updated', 'stop']; 
    protected $getFields = ['fid', 'tid', 'name', 'prd', 'nid', 'open', 'history', 'dealer', 'updated', 'stop']; 

    protected $returnType = 'object'; 
    
    public function gets($game){
        
        $where = "enable = '".STATE_ACTIVE."' ";
        $where.= " AND prd = '".$game."' ";
        
        return $this->select($this->getFields)
                    ->where($where)
                    ->orderBy('name', 'ASC')
                    ->findAll(); 
    }

    public function setState($fid, $arrData){
        $arrData['stop'] = trim($arrData['stop']);

        return $this->update($fid, $arrData);

    }

}