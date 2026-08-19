<?php
namespace App\Models;
use CodeIgniter\Model;

class Eorder_Model extends Model 
{
    protected $table = 'bet_order';
    protected $returnType = 'object'; 
    protected $allowedFields = [
        'ord_id', 
        'ord_mb_fid', 
        'ord_state', 
        'ord_game_type', 
        'ord_table_id', 
        'ord_table_name', 
        'ord_round_id', 
        'ord_tm_req', 
        'ord_tm_acc', 
        'ord_amount', 
        'ord_win_amount',
        'ord_choice',
        'ord_result',
    ];
    protected $primaryKey = 'ord_id';

    public function searchCount($reqData){

        $where = " WHERE ord_state <> ".BET_STATE_BIN;

        $strSql = "SELECT count('ord_id') as count FROM ".$this->table;
        $strSql .= $where;

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 
    }

    public function searchList($reqData){
        
        $where = " WHERE ord_state <> ".BET_STATE_BIN;

        $strTbColum = " ".implode(", ", $this->allowedFields);
        $strTbColum.= ", member.mb_uid, member.mb_nickname ";
        $strSql = " SELECT ".$strTbColum." FROM ".$this->table;
        $strSql .= " LEFT JOIN member ON ".$this->table.".ord_mb_fid = member.mb_fid ";
        $strSql .= $where;

        $page = $reqData['page'];
        $count = $reqData['count'];
        if($page < 1)
            return NULL;
        if($count < 1)
            return NULL;
        
        $nStartRow = ($page-1) * $count ;

        $strSql.=" ORDER BY ord_id DESC LIMIT ".$nStartRow.", ".$count;
        // writeLog($strSql);
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        return $result;
    }
    
    public function getStatsByRound(){
        
        
        $strSql = "SELECT tid, name, ord_choice, ord_amount FROM (SELECT tid, name  FROM casino_room WHERE OPEN = ".STATE_ACTIVE.") AS tbRoom "; 
        $strSql.= " LEFT JOIN (SELECT ord_table_id, ord_table_name, ord_round_id, ord_choice, SUM(ord_amount) AS ord_amount FROM ".$this->table;
        $strSql.= " WHERE ord_state = 1 AND ord_choice IN ('Player', 'Banker') GROUP BY ord_table_id, ord_choice) AS tbBet ";
        // $strSql.= " WHERE ord_state != 7 AND ord_choice IN ('Player', 'Banker') AND ord_round_id COLLATE utf8mb4_general_ci IN ( SELECT nid FROM casino_room WHERE OPEN = ".STATE_ACTIVE."  ) GROUP BY ord_table_id, ord_round_id, ord_choice) AS tbBet ";
        $strSql.= " ON tbRoom.tid COLLATE utf8mb4_general_ci = tbBet.ord_table_id ORDER BY name ASC, ord_choice DESC ";

        // writeLog($strSql);
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        return $result;
    }
}