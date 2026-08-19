<?php 
namespace App\Models;

use CodeIgniter\Model;

class CasPrd_Model extends Model {

    protected $table      = 'casino_prd';
    protected $primaryKey = 'fid';

    protected $returnType = 'object'; 
    
    public function gets($cat = 0){
        $where = "is_enable = '".STATE_ACTIVE."' ";
        $where.= "AND hidden = '".STATE_DISABLE."' ";
        if($cat > 0){
            $where.= "AND cat = '".$cat."' ";
        }
        return $this->where($where)
                    ->orderBy('cas_id', 'ASC')
                    ->findAll(); 
    }

    public function getById($cat, $id){
        $where = "is_enable = '".STATE_ACTIVE."' ";
        $where.= "AND hidden = '".STATE_DISABLE."' ";
        $where.= "AND cat = '".$cat."' ";
        $where.= "AND cas_id = '".$id."' ";

        return $this->where($where)
                    ->first();
    }
    
}