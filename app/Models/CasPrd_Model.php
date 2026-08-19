<?php 
namespace App\Models;

use CodeIgniter\Model;

class CasPrd_Model extends Model {

    protected $table      = 'casino_prd';
    protected $primaryKey = 'fid';
    protected $allowedFields = ['key', 'lobby', 'hidden', 'maintain', 'range']; 

    protected $returnType = 'object'; 
    
    public function gets($cat = 0){
        $where = "is_enable = '".STATE_ACTIVE."' ";
        $where.= "AND hidden = '".STATE_DISABLE."' ";
        if($cat > 0){
            $where.= "AND cat = '".$cat."' ";
        }
        return $this->where($where)
                    ->orderBy('fid', 'ASC')
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
    
    public function changeAct($arrReqData)
    {
        $data = [];

        if(array_key_exists('hidden', $arrReqData)){
            $data['hidden'] =  $arrReqData['hidden'];
            return $this->update($arrReqData['fid'], $data);
        } else if(array_key_exists('maintain', $arrReqData)){
            $data['maintain'] =  $arrReqData['maintain'];
            return $this->update($arrReqData['fid'], $data);
        } else if(array_key_exists('key', $arrReqData) || array_key_exists('lobby', $arrReqData) || array_key_exists('range', $arrReqData)){
            return $this->update($arrReqData['fid'], $arrReqData);
        } else return false;
    
    }

    public function search($arrReqData){
        
        $strSql = " SELECT * FROM ".$this->table;
        $strSql.= " WHERE is_enable = '1' AND cat = ".$arrReqData['cat'];
        if(strlen($arrReqData['name']) > 0){
            $strSql.= " AND (`key` LIKE '%".$arrReqData['name']."%' OR `name` LIKE '%".$arrReqData['name']."%' ) ";
        }

        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
        $strSql.=" ORDER BY fid ASC LIMIT ".$nStartRow.", ".$arrReqData['count'];
        
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result; 

    }

    public function searchCount($arrReqData){
        
        $strSql = "SELECT count(*) as count FROM ".$this->table;
        $strSql.= " WHERE is_enable = '1' AND cat = ".$arrReqData['cat'];
        if(strlen($arrReqData['name']) > 0){
            $strSql.= " AND (`key` LIKE '%".$arrReqData['name']."%' OR `name` LIKE '%".$arrReqData['name']."%' ) ";
        }

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 
    }

}