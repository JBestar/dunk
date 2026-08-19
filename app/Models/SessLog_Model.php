<?php 
namespace App\Models;

use CodeIgniter\Model;

class SessLog_Model extends Model {

    protected $table      = 'sess_log';
    protected $primaryKey = 'log_fid';

    protected $returnType = 'object'; 

    protected $allowedFields = ['log_mb_uid', 'log_ip', 'log_time']; 

    public function add($member){
        
        $data = [
            'log_mb_uid' => $member->mb_uid,
            'log_ip' => $member->mb_ip_last,
            'log_time' => date("Y-m-d H:i:s"),
        ];
        
        return $this->insert($data);
    }



    function search($arrReqData, $mbLv)
    {
        $tbBlock = "block_list";
        $tbSearch = "tbSearch";
        
        $strSql = "SELECT ".$tbSearch.".*, member.mb_fid, member.mb_nickname, member.mb_level, block_ip, block_state FROM ( ";
        
        $strSql.= " SELECT * FROM ".$this->table;
        $strSql .= " WHERE log_delete = '0' " ;
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strSql.=" AND log_time >= ".$this->db->escape($arrReqData['start']." 00:00:00")." AND log_time <= ".$this->db->escape($arrReqData['end']." 23:59:59") ; 
        }
        $search = trim($arrReqData['search']);
        if(strlen($search) > 0){
            if($arrReqData['type'] == 0)
                $strSql.=" AND log_mb_uid = ".$this->db->escape($search);
            else if($arrReqData['type'] == 3)
                $strSql.=" AND log_ip = ".$this->db->escape($search);
        }
        $strSql.=" AND log_mb_uid IN ( SELECT mb_uid FROM member WHERE mb_level <= ".$mbLv;

        if(strlen($search) > 0){
            if($arrReqData['type'] == 1)
                $strSql.=" AND mb_nickname = ".$this->db->escape($search);
            else if($arrReqData['type'] == 2)
                $strSql.=" AND mb_fid = ".$this->db->escape($search);
        }
        $strSql.=" ) ";

        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
        $strSql.=" ORDER BY log_fid DESC LIMIT ".$nStartRow.", ".$arrReqData['count'];

        $strSql .= ") AS ". $tbSearch." JOIN member ON ".$tbSearch.".log_mb_uid = member.mb_uid ";
        $strSql .= ' LEFT JOIN '.$tbBlock.' ON '.$tbSearch.'.log_ip = '.$tbBlock.'.block_ip ';
        $strSql.=" ORDER BY log_fid DESC";

        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog($strSql);

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result; 

    }


    function searchCount($arrReqData, $mbLv)
    {
        $strSql = "SELECT count(*) as count FROM ".$this->table;
        $strSql .= " WHERE log_delete = '0' " ;
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strSql.=" AND log_time >= ".$this->db->escape($arrReqData['start']." 00:00:00")." AND log_time <= ".$this->db->escape($arrReqData['end']." 23:59:59") ; 
        }
        $search = trim($arrReqData['search']);
        if(strlen($search) > 0){
            if($arrReqData['type'] == 0)
                $strSql.=" AND log_mb_uid = ".$this->db->escape($search);
            else if($arrReqData['type'] == 3)
                $strSql.=" AND log_ip = ".$this->db->escape($search);
        }
        $strSql.=" AND log_mb_uid IN ( SELECT mb_uid FROM member WHERE mb_level <= ".$mbLv;

        if(strlen($search) > 0){
            if($arrReqData['type'] == 1)
                $strSql.=" AND mb_nickname = ".$this->db->escape($search);
            else if($arrReqData['type'] == 2)
                $strSql.=" AND mb_fid = ".$this->db->escape($search);
        }
        $strSql.=" ) ";
        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog($strSql);

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 

    }


}