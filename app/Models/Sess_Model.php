<?php 
namespace App\Models;

use CodeIgniter\Model;

class Sess_Model extends Model {

    protected $table      = 'sess';
    protected $primaryKey = 'sess_fid';
    protected $returnType = 'object'; 

    protected $allowedFields = ['sess_id', 'sess_mb_fid', 'sess_mb_uid', 'sess_ip', 'sess_join', 'sess_update', 'sess_action']; 
    private $mMemberTable = 'member';

    public function add($member, $sessId){
        $this->deleteBySess($sessId);
        
        $dtNow = date("Y-m-d H:i:s");
        $data = [
            'sess_id' => $sessId,
            'sess_mb_fid' => $member->mb_fid,
            'sess_mb_uid' => $member->mb_uid,
            'sess_ip' => $member->mb_ip_last,
            'sess_join' => $dtNow,
            'sess_update' => $dtNow,
            'sess_action' => $dtNow,
        ];
        
        return $this->insert($data);
    }
    
    public function getBySess($sessId){
        
        return $this->where('sess_id', $sessId)
                    ->first();
    }

    
    public function getByUid($uid, $bSite = true){
        $where = "sess_mb_uid = '".$uid."' ";
        if($bSite)
            $where.= " AND sess_type = ".SESS_TYPE_SITE." ";
        else 
            $where.= " AND sess_type <> ".SESS_TYPE_SITE." ";

        return $this->where($where)
                    ->first();

    }

    public function updateLast($sessId){
        
        $data = [
            'sess_update' => date("Y-m-d H:i:s"),
        ];
        
        return $this->set($data)
                    ->where('sess_id', $sessId)
                    ->update();
    }

    public function updateAction($sessId){
        
        $data = [
            'sess_action' => date("Y-m-d H:i:s"),
        ];
        
        return $this->set($data)
                    ->where('sess_id', $sessId)
                    ->update();
    }
    
    public function deleteBySess($sess){
        
        $data = [
            'sess_id' => $sess,
        ];
        
        return $this->where($data)
                    ->delete();

    }

    public function deleteByMember($mb_fid){
        
        $data = [
            'sess_mb_fid' => $mb_fid,
        ];
        
        return $this->where($data)
                    ->delete();

    }

    public function deleteLast(){

        $tmMin = 2;
        $tmLast = strtotime("-".$tmMin." minutes", time());

        return $this->where('sess_update <', date("Y-m-d H:i:s", $tmLast))
                    ->delete();
    }

    
    function search($arrReqData, $mbLv=LEVEL_ADMIN)
    {
        $strSql = "SELECT ".$this->table.".*, member.mb_nickname, member.mb_level, ";
        $strSql .= " (".allMoneySql($this->mMemberTable).") AS mb_money, member.mb_point FROM ".$this->table;
        $strSql .= " JOIN member ON ".$this->table.".sess_mb_fid = member.mb_fid ";
        $strSql .= " WHERE mb_level < ".$mbLv;
        
        if(strlen($arrReqData['mb_uid']) > 0){
            $strSql.=" AND sess_mb_uid = ".$this->db->escape($arrReqData['mb_uid']);
        }
        if(intval($arrReqData['type']) > 0 && intval($arrReqData['type']) <= 2){
            $strSql.=" AND sess_type = ".$this->db->escape($arrReqData['type']);
        } else if(intval($arrReqData['type']) == 3){
            $strSql.=" AND sess_type <> ".SESS_TYPE_SITE;
        }
        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;

        $strSql.=" ORDER BY sess_fid ASC LIMIT ".$nStartRow.", ".$arrReqData['count'];

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result; 

    }


    function searchCount($arrReqData, $mbLv=LEVEL_ADMIN)
    {
        $strSql = "SELECT count(*) as count FROM ".$this->table;
        $strSql .= " JOIN member ON ".$this->table.".sess_mb_fid = member.mb_fid ";
        $strSql .= " WHERE mb_level < ".$mbLv;
        if(strlen($arrReqData['mb_uid']) > 0){
            $strSql.=" AND sess_mb_uid = ".$this->db->escape($arrReqData['mb_uid']);
        }
        if(intval($arrReqData['type']) > 0 && intval($arrReqData['type']) <= 2){
            $strSql.=" AND sess_type = ".$this->db->escape($arrReqData['type']);
        } else if(intval($arrReqData['type']) == 3){
            $strSql.=" AND sess_type <> ".SESS_TYPE_SITE;
        }
        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 

    }

    
    function searchPress($arrReqData, $mbLv=LEVEL_ADMIN)
    {
        $strSql = "SELECT ".$this->table.".*, member.mb_nickname, member.mb_level, ";
        $strSql .= " member.mb_money, member.mb_point, member.mb_state_view FROM ".$this->table;
        $strSql .= " JOIN member ON ".$this->table.".sess_mb_fid = member.mb_fid ";
        $strSql .= " WHERE mb_level < '".$mbLv."' AND sess_type <> ".SESS_TYPE_SITE;
        
        $strSql.=" ORDER BY sess_join ASC ";

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result; 

    }
}