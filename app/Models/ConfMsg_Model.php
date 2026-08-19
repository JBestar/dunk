<?php 
namespace App\Models;

use CodeIgniter\Model;

class ConfMsg_Model extends Model {

    protected $table      = 'conf_msg';
    protected $primaryKey = 'conf_id';

    protected $returnType = 'object'; 

    protected $allowedFields = ['conf_id', 'conf_memo', 'conf_content', 'conf_active', 'conf_update']; 

    public function add($arrData){
        
        $data = [
            'conf_memo' => trim($arrData['title']),
            'conf_content' => trim($arrData['content']),
            'conf_active' => STATE_ACTIVE,
            'conf_update' => date("Y-m-d H:i:s"),
        ];
        
        return $this->insert($data);
    }
    
    public function modify($fid, $arrData){
        
        $data = [
            'conf_memo' => trim($arrData['title']),
            'conf_content' => trim($arrData['content']),
            'conf_update' => date("Y-m-d H:i:s"),
        ];

        return $this->update($fid, $data);
    }

	function deleteByFid($fid){
    	return $this->delete($fid);
    }

    function search($arrReqData)
    {
        $strSql = "SELECT * FROM ".$this->table;
        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
        $strSql .= " WHERE conf_active = ".STATE_ACTIVE;
        $strSql.=" ORDER BY conf_id DESC LIMIT ".$nStartRow.", ".$arrReqData['count'];

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result; 

    }


    function searchCount($arrReqData)
    {
        $strSql = "SELECT count(conf_id) as count FROM ".$this->table;
        $strSql .= " WHERE conf_active = ".STATE_ACTIVE;
        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 

    }
 
}