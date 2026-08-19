<?php 
namespace App\Models;

use CodeIgniter\Model;

class Block_Model extends Model {

    protected $table      = 'block_list';
    protected $primaryKey = 'block_fid';

    protected $returnType = 'object'; 
    protected $allowedFields = ['block_ip', 'block_state', 'block_error', 'block_updated']; 

    
    public function getByIp($ip){

        $where = "block_ip = ".$this->db->escape($ip);

        return $this->where($where)
                    ->first(); 
    }

    public function isBlockIp($ip){

        $where = "block_ip = ".$this->db->escape($ip)." AND block_state = ".STATE_ACTIVE;

        $block = $this->where($where)
                    ->first();
        return !is_null($block); 
    }

    public function saveByIp($ip){
        
        if(strlen($ip) < 1)
            return false;

        $data = [
            'block_ip' => $ip,
            'block_state' => STATE_ACTIVE,
            'block_updated' => date("Y-m-d H:i:s")
        ];

        $block = $this->getByIp($ip);
        if(is_null($block)){
            return $this->insert($data);
        } else {
            return $this->update($block->block_fid, $data);
        }
    }
    
}