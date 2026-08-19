<?php 
namespace App\Models;

use CodeIgniter\Model;

class Block_Model extends Model {

    protected $table      = 'block_list';
    protected $primaryKey = 'block_fid';

    protected $returnType = 'object'; 
    protected $allowedFields = ['block_ip', 'block_state', 'block_error', 'block_updated']; 

    
    public function getByIp($ip, $bStop =  false){
        $where = "block_ip = ".$this->db->escape($ip);
        
        if($bStop)
            $where.= " AND block_state = '1' ";

        return $this->where($where)
                    ->first(); 
    }

    public function updateByFid($info){

        if(array_key_exists('block_ip', $info) && strlen($info['block_ip']) < 1)
            return false;

        return $this->update($info['block_fid'], $info);
    }

        
	function deleteByFid($fid){
    	return $this->delete($fid);
    }

    public function saveByIp($info){
        if(strlen($info['block_ip']) < 1)
            return false;

        $block = $this->getByIp($info['block_ip']);
        if(is_null($block)){

            return $this->insert($info);
        } else {
            return $this->update($block->block_fid, $info);
        }
    }
    
    function search($arrReqData)
    {
        $strSql = "SELECT ".$this->table.".* FROM ".$this->table;
        $strSql .= " WHERE block_fid > '0' ";
        if(strlen($arrReqData['ip']) > 0){
            $strSql.=" AND block_ip = ".$this->db->escape($arrReqData['ip']);
        }
        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;

        $strSql.=" ORDER BY block_updated DESC LIMIT ".$nStartRow.", ".$arrReqData['count'];

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result; 

    }


    function searchCount($arrReqData)
    {
        $strSql = "SELECT count(*) as count FROM ".$this->table;
        $strSql .= " WHERE block_fid > '0' ";
        if(strlen($arrReqData['ip']) > 0){
            $strSql.=" AND block_ip = ".$this->db->escape($arrReqData['ip']);
        }
        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 

    }

}