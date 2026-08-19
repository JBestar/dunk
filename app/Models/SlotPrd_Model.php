<?php 
namespace App\Models;

use CodeIgniter\Model;

class SlotPrd_Model extends Model {

    protected $table      = 'slot_prd';
    protected $primaryKey = 'id';

    protected $returnType = 'object'; 
    protected $allowedFields = ['hidden', 'maintain'];

    public function gets($cat){
        $where = [
            'cat' => $cat,
            'maintain' => STATE_DISABLE,
        ];
        return $this->where($where)
                    ->orderBy('id', 'ASC')
                    ->findAll(); 
    }

    public function getByCode($cat, $where="", $order = ""){
        $strSql = "SELECT  * FROM ".$this->table." WHERE cat = '".$cat."' AND maintain = '0' ";
        if(strlen($where) > 0)
            $strSql.=" AND ".$where; 
        if(strlen($order) > 0)
            $strSql.=" ORDER BY ".$order; 

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();

        return $result; 
    }
    
    public function changeAct($arrReqData)
    {
        $data = [];

        if(array_key_exists('hidden', $arrReqData)){
            $data['hidden'] =  $arrReqData['hidden'];
            
            return $this->set($data)
             ->where('name', $arrReqData['name'])
             ->update();
        } else return false;
    
    }

    public function search($arrReqData){
        $gameId = $arrReqData['game'];

        $strSql = " SELECT * FROM ".$this->table;
        $strSql.= " WHERE maintain = '0' AND cat = ".$gameId;
        if(strlen($arrReqData['name']) > 0){
            $strSql.= " AND (name LIKE '%".$arrReqData['name']."%' OR name_kr LIKE '%".$arrReqData['name']."%' ) ";
        }

        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
        $strSql.=" ORDER BY id ASC LIMIT ".$nStartRow.", ".$arrReqData['count'];
        
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result; 

    }

    public function searchCount($arrReqData){
        $gameId = $arrReqData['game'];

        $strSql = "SELECT count(*) as count FROM ".$this->table;
        $strSql.= " WHERE maintain = '0' AND cat = ".$gameId;
        if(strlen($arrReqData['name']) > 0){
            $strSql.= " AND (name LIKE '%".$arrReqData['name']."%' OR name_kr LIKE '%".$arrReqData['name']."%' ) ";
        }

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 
    }


}