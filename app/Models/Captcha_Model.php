<?php 
namespace App\Models;

use CodeIgniter\Model;

class Captcha_Model extends Model {

    protected $table      = 'captcha';
    protected $primaryKey = 'cap_fid';

    protected $returnType = 'object'; 

    protected $allowedFields = ['cap_file', 'cap_sn', 'cap_type', 'cap_time']; 

    public function getByFile($file){
        $where = "cap_file = '".$file."' ";
        
        return $this->where($where)
                    ->findAll(); 
    }

    public function add($file, $sn){
        
        $data = [
            'cap_file' => $file,
            'cap_sn' => $sn,
            'cap_time' => date("Y-m-d H:i:s"),
        ];
        
        return $this->insert($data);
    }

    public function verify($file, $captcha){
            
        $arrCaptcha = $this->getByFile($file);
        
        if(is_null($arrCaptcha) || count($arrCaptcha) == 0){
            return RESULT_CAPTCHA_ERR;
        } 
        foreach($arrCaptcha as $objCaptcha){
            if($objCaptcha->cap_sn === $captcha){
                return RESULT_OK;
            }
        }
        return RESULT_CAPTCHA_ERR;
    }

}