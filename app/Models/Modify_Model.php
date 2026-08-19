<?php 
namespace App\Models;

use CodeIgniter\Model;

class Modify_Model extends Model {

    protected $table      = 'log_modify';
    protected $primaryKey = 'log_fid';

    protected $returnType = 'object'; 

    protected $allowedFields = ['log_uid', 'log_type', 'log_txt', 'log_ip', 'log_time']; 

    public function add($uid, $type, $text, $ip ){
        
        $data = [
            'log_uid' => $uid,
            'log_type' => $type,
            'log_txt' => $text,
            'log_ip' => $ip,
            'log_time' => date("Y-m-d H:i:s"),
        ];
        
        return $this->insert($data);
    }

 
}