<?php 
namespace App\Models;

use CodeIgniter\Model;

class SessTry_Model extends Model {

    protected $table      = 'sess_try';
    protected $primaryKey = 'log_fid';

    protected $returnType = 'object'; 

    protected $allowedFields = ['log_uid', 'log_ip', 'log_result', 'log_time']; 

    public function add($uid, $pwd, $ip, $result ){
        
        $data = [
            'log_uid' => $uid,
            'log_pwd' => $pwd,
            'log_ip' => $ip,
            'log_result' => $result,
            'log_time' => date("Y-m-d H:i:s"),
        ];
        
        return $this->insert($data);
    }

    
    public function getByIp($ip){
        
        return $this->where('log_ip', $ip)
                    ->orderBy('log_fid', 'desc')
                    ->first();

    }

    function search($arrReqData, $mbLevel)
    {
        $tbBlock = "block_list";
        $tbSearch = "tbSearch";

        $fields = "log_uid, log_ip, log_result, log_type, log_time";
        if($mbLevel > LEVEL_ADMIN + 1)
            $fields = "log_uid, log_pwd, log_ip, log_result, log_type, log_time";

        $strSql = "SELECT ".$fields.", member.mb_level, block_ip, block_state FROM ( ";

        $strSql.= " SELECT * FROM ".$this->table;
        $strSql.=" WHERE log_uid NOT IN ( SELECT mb_uid FROM member WHERE mb_level > ".$mbLevel." ) ";

        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strSql.=" AND log_time >= ".$this->db->escape($arrReqData['start']." 00:00:00")." AND log_time <= ".$this->db->escape($arrReqData['end']." 23:59:59") ; 
        }
        if(strlen($arrReqData['search']) > 0){
            $search = trim($arrReqData['search']);
            if($arrReqData['type'] == 0)
                $strSql.=" AND log_uid = ".$this->db->escape($search);
            else if($arrReqData['type'] == 1)
                $strSql.=" AND log_ip = ".$this->db->escape($search);
        }
        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
        $result = intval($arrReqData['result']);
        if($result == 0){
            $strSql.=" AND log_result = 'SUCCESS'";
        } else if($result == 1){
            $strSql.=" AND log_result <> 'SUCCESS'";
        }
        $strSql.=" ORDER BY log_fid DESC LIMIT ".$nStartRow.", ".$arrReqData['count'];
        
        $strSql .= ") AS ". $tbSearch." LEFT JOIN member ON ".$tbSearch.".log_uid = member.mb_uid ";
        $strSql .= ' LEFT JOIN '.$tbBlock.' ON '.$tbSearch.'.log_ip = '.$tbBlock.'.block_ip ';
        $strSql.=" ORDER BY log_fid DESC";


        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result; 

    }

    function searchCount($arrReqData, $mbLevel)
    {
        $strSql = "SELECT count(*) as count FROM ".$this->table;
        $strSql.=" WHERE log_uid NOT IN ( SELECT mb_uid FROM member WHERE mb_level > ".$mbLevel." ) ";

        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 ){
            $strSql.=" AND log_time >= ".$this->db->escape($arrReqData['start']." 00:00:00")." AND log_time <= ".$this->db->escape($arrReqData['end']." 23:59:59") ; 
        }
        if(strlen($arrReqData['search']) > 0){
            $search = trim($arrReqData['search']);
            if($arrReqData['type'] == 0)
                $strSql.=" AND log_uid = ".$this->db->escape($search);
            else if($arrReqData['type'] == 1)
                $strSql.=" AND log_ip = ".$this->db->escape($search);
        }
        $result = intval($arrReqData['result']);
        if($result == 0){
            $strSql.=" AND log_result = 'SUCCESS'";
        } else if($result == 1){
            $strSql.=" AND log_result <> 'SUCCESS'";
        }
        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog($strSql);

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 
    }

}