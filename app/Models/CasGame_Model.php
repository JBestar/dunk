<?php 
namespace App\Models;

use CodeIgniter\Model;

class CasGame_Model extends Model {

    protected $table      = 'casino_game';
    protected $primaryKey = 'fid';
    protected $allowedFields = ['tid', 'name']; 

    protected $returnType = 'object'; 
    
    public function search($word){
        if(strlen(trim($word)) < 1)
            return null;
        $where = "tid = '".$word."' ";
        $where.= "OR name LIKE '%".$word."%' ";
        
        return $this->where($where)
                    ->orderBy('fid', 'ASC')
                    ->findAll(); 
    }

}