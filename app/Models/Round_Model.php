<?php 
namespace App\Models;

use CodeIgniter\Model;

class Round_Model extends Model {

    protected $table      = 'round_pball';
    protected $primaryKey = 'round_fid';

    protected $returnType = 'object'; 

    public function gets($count){
        
        return $this->orderBy('round_time', 'DESC')
                    ->findAll($count, 0);
    }

    
    public function searchCount($reqData){

        $where = "round_game = ".$this->db->escape($reqData['game']);
        if(array_key_exists('date', $reqData) ){
            $where.= " AND round_date = ".$this->db->escape($reqData['date']);
        }
        $max = 1000;
        $data = $this->where($where)
                     ->findAll($max); 
        return count($data);
    }

    public function searchList($reqData){
        
        $where = "round_game = ".$this->db->escape($reqData['game']);
        if(array_key_exists('date', $reqData) ){
            $where.= " AND round_date = ".$this->db->escape($reqData['date']);
        }
        
        $page = $reqData['page'];
        $count = $reqData['count'];
        
        if($page < 1)
            return NULL;
        if($count < 1)
            return NULL;
        return $this->where($where)
                    ->orderBy('round_time', 'DESC')
                    ->findAll($count, $count*($page-1)); 
    }
}
