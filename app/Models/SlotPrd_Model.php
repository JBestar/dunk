<?php 
namespace App\Models;

use CodeIgniter\Model;

class SlotPrd_Model extends Model {

    protected $table      = 'slot_prd';
    protected $primaryKey = 'id';

    protected $returnType = 'object'; 


    public function getByCode($cat, $prd){
        $where = [
            'cat' => $cat,
            'code' => $prd,
            'maintain' => STATE_DISABLE,
            'hidden' => STATE_DISABLE
        ];
        return $this->where($where)->first();
    }

    public function getByRef($cat, $prd){
        $where = [
            'cat' => $cat,
            'ref_code' => $prd,
            'maintain' => STATE_DISABLE,
            'hidden' => STATE_DISABLE
        ];
        return $this->where($where)->orderBy('code ASC')->findAll();
    }
    
    public function gets($cat){
        $where = "cat = '".$cat."' AND hidden = '".STATE_DISABLE."' AND maintain = '".STATE_DISABLE."'";
        return $this->where($where)
                    ->orderBy('idx ASC, id ASC')
                    ->findAll(); 
    }


}