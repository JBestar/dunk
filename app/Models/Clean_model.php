<?php
namespace App\Models;
use CodeIgniter\Model;

class Clean_Model extends Model {
	
    //디비정리
    public function cleanDb(){
        $tmNow = time();
        $strDate = date('Y-m-d', strtotime("-2 months", $tmNow));
        
        $strSql = " DELETE FROM bet_casino WHERE bet_time < '".$strDate."' ";
        $this -> db -> query($strSql);
        
        $strSql = " DELETE FROM bet_slot WHERE bet_time < '".$strDate."' ";
        $this -> db -> query($strSql);
        
        $strSql = " DELETE FROM bet_holdem WHERE bet_time < '".$strDate."' ";
        $this -> db -> query($strSql);

        $strSql = " DELETE FROM bet_reward WHERE rw_time < '".$strDate."' ";
        $this -> db -> query($strSql);
        
        $strSql = " DELETE FROM bet_reward_st WHERE rw_end < '".$strDate."' ";
        $this -> db -> query($strSql);

        $strSql = " DELETE FROM bet_reward_mn WHERE rw_time < '".$strDate."' ";
        $this -> db -> query($strSql);
        
        $strSql = " DELETE FROM bet_reward_mn_st WHERE rw_end < '".$strDate."' ";
        $this -> db -> query($strSql);

        $strSql = " DELETE FROM bet_pball WHERE bet_time < '".$strDate."' ";
        $this -> db -> query($strSql);
        
        $strSql = " DELETE FROM bet_ebal WHERE bet_time < '".$strDate."' ";
        $this -> db -> query($strSql);

        $strSql = " DELETE FROM bet_ebal_st WHERE bet_end < '".$strDate."' ";
        $this -> db -> query($strSql);

        $strSql = " DELETE FROM bet_balance WHERE bet_time < '".$strDate."' ";
        $this -> db -> query($strSql);

        $this->db->query("TRUNCATE sessions");
        
        return 1;
    }
    //디비초기화
    public function initDb(){
        $this->db->query("TRUNCATE bet_slot");
        $this->db->query("TRUNCATE bet_casino");
        $this->db->query("TRUNCATE bet_balance");
        $this->db->query("TRUNCATE bet_pball");
        $this->db->query("TRUNCATE bet_holdem");
        $this->db->query("TRUNCATE bet_reward");
        $this->db->query("TRUNCATE bet_reward_st");
        $this->db->query("TRUNCATE bet_reward_mn");
        $this->db->query("TRUNCATE bet_reward_mn_st");
        $this->db->query("TRUNCATE bet_reward_pr");
        $this->db->query("TRUNCATE bet_reward_pr_st");
        $this->db->query("TRUNCATE bet_follow");
        $this->db->query("TRUNCATE block_list");
        $this->db->query("TRUNCATE member_charge");
        $this->db->query("TRUNCATE member_exchange");
        $this->db->query("TRUNCATE money_history");
        $this->db->query("TRUNCATE money_history_st");
        $this->db->query("TRUNCATE money_transfer");
        $this->db->query("TRUNCATE sessions");
        $this->db->query("TRUNCATE sess");

        $this->db->query("TRUNCATE log_modify");
        $this->db->query("TRUNCATE sess_log");
        $this->db->query("TRUNCATE sess_try");
        $this->db->query("TRUNCATE captcha");
        $this->db->query("TRUNCATE bet_ebal");
        $this->db->query("TRUNCATE bet_ebal_st");
        $this->db->query("TRUNCATE bet_prbal");
        $this->db->query("TRUNCATE bet_prbal_st");
        $this->db->query("TRUNCATE bet_balance_pr");

        $tmNow = time();
        $strDate = date('Y-m-d', strtotime("-2 months", $tmNow));

        $strSql = " DELETE FROM round_pball WHERE round_time < '".$strDate."' ";
        $this -> db -> query($strSql);

        $strSql = " DELETE FROM board_notice WHERE notice_type != '".NOTICE_BOARD."' ";
        $this -> db -> query($strSql);

        $strSql = " DELETE FROM member WHERE mb_level < '".LEVEL_ADMIN."' ";
        $this -> db -> query($strSql);
        $this->db->query("TRUNCATE member_conf");

        return 1;
    }

    public function cleanPartition($date){

        if(calcDate() < $date){
            writeLog("Truncate Partition");
            $this->db->query("TRUNCATE bet_ebal");
            $this->db->query("TRUNCATE bet_ebal_st");
            $this->db->query("TRUNCATE bet_balance");
            $this->db->query("TRUNCATE bet_prbal");
            $this->db->query("TRUNCATE bet_prbal_st");
            $this->db->query("TRUNCATE bet_balance_pr");
            $this->db->query("TRUNCATE bet_reward");
            $this->db->query("TRUNCATE bet_reward_st");
            $this->db->query("TRUNCATE bet_reward_mn");
            $this->db->query("TRUNCATE bet_reward_mn_st");
            $this->db->query("TRUNCATE bet_reward_pr");
            $this->db->query("TRUNCATE bet_reward_pr_st");
            $this->db->query("TRUNCATE money_history");
            $this->db->query("TRUNCATE money_history_st");
            $this->db->query("TRUNCATE money_transfer");
            $this->db->query("TRUNCATE bet_casino");
            $this->db->query("TRUNCATE bet_slot");
            $this->db->query("TRUNCATE bet_holdem");
            $this->db->query("TRUNCATE bet_pball");
            $this->db->query("TRUNCATE round_pball");

            $this->db->query("TRUNCATE log_modify");
            $this->db->query("TRUNCATE sess_log");
            $this->db->query("TRUNCATE sess_try");
            $this->db->query("TRUNCATE captcha");

            $strSql = " DELETE FROM board_notice WHERE notice_type != '".NOTICE_BOARD."' ";
            $this -> db -> query($strSql);
        } else if(strlen($date) > 0) {

            $partName = "P_".str_replace("-", "", $date)."_000000";
            writeLog("clean Partition:".$partName);

            $table = "bet_ebal";
            $this->dropPartitions($table, $partName);

            $table = "bet_balance";
            $this->dropPartitions($table, $partName);

            $table = "bet_reward";
            $this->dropPartitions($table, $partName);

            $table = "bet_reward_mn";
            $this->dropPartitions($table, $partName);
            
            $table = "bet_prbal";
            $this->dropPartitions($table, $partName);

            $table = "bet_balance_pr";
            $this->dropPartitions($table, $partName);

            $table = "bet_reward_pr";
            $this->dropPartitions($table, $partName);

            $table = "money_history";
            $this->dropPartitions($table, $partName);

            $table = "bet_casino";
            $this->dropPartitions($table, $partName);

            $table = "bet_slot";
            $this->dropPartitions($table, $partName);

            $table = "bet_holdem";
            $this->dropPartitions($table, $partName);

            $table = "bet_pball";
            $this->dropPartitions($table, $partName);

            $table = "round_pball";
            $this->dropPartitions($table, $partName);

            $strSql = " DELETE FROM bet_reward_st WHERE rw_end < '".$date."' ";
            $this -> db -> query($strSql);
            
            $strSql = " DELETE FROM bet_reward_mn_st WHERE rw_end < '".$date."' ";
            $this -> db -> query($strSql);

            $strSql = " DELETE FROM bet_reward_pr_st WHERE rw_end < '".$date."' ";
            $this -> db -> query($strSql);

            $strSql = " DELETE FROM bet_ebal_st WHERE bet_end < '".$date."' ";
            $this -> db -> query($strSql);
            
            $strSql = " DELETE FROM bet_prbal_st WHERE bet_end < '".$date."' ";
            $this -> db -> query($strSql);

            $strSql = " DELETE FROM money_history_st WHERE money_end < '".$date."' ";
            $this -> db -> query($strSql);

            $strSql = " DELETE FROM money_transfer WHERE trans_time < '".$date."' ";
            $this -> db -> query($strSql);

            $strSql = " DELETE FROM board_notice WHERE notice_type != '".NOTICE_BOARD."' AND notice_time_create < '".$date."' ";
            $this -> db -> query($strSql);

            $strSql = " DELETE FROM log_modify WHERE log_time < '".$date."' ";
            $this -> db -> query($strSql);
            
            $strSql = " DELETE FROM sess_log WHERE log_time < '".$date."' ";
            $this -> db -> query($strSql);

            $strSql = " DELETE FROM sess_try WHERE log_time < '".$date."' ";
            $this -> db -> query($strSql);
            
            $strSql = " DELETE FROM captcha WHERE cap_time < '".$date."' ";
            $this -> db -> query($strSql);

        }
        writeLog("clean Partition END");

        return 1;
    }

    public function dropPartitions($table, $partName){
        $parts = $this->getPartition($table);
        for ($i=0; $i<count($parts)-2; $i++) {
            if($parts[$i]->PARTITION_NAME < $partName){
                $this->dropPartition($parts[$i]->TABLE_NAME, $parts[$i]->PARTITION_NAME);
            }
        }
    }

    public function getPartition($table){
        $sql = "SELECT TABLE_SCHEMA, TABLE_NAME, PARTITION_NAME, PARTITION_ORDINAL_POSITION, TABLE_ROWS FROM INFORMATION_SCHEMA.PARTITIONS";
        $sql.= " WHERE TABLE_NAME ='".$table."'";

        $query = $this -> db -> query($sql);
        $parts = $query -> getResult();

        $arrPart = [];
        foreach ($parts as $part) {
            if($part->TABLE_SCHEMA == $_ENV['database.default.database']){
                writeLog("DB:".$part->TABLE_SCHEMA." Table:".$part->TABLE_NAME." PART:".$part->PARTITION_NAME." ROWS:".$part->TABLE_ROWS);
                if(is_null($part->PARTITION_NAME))
                    continue;
                $arrPart[] = $part;
            }
        }
        return $arrPart;
    }
    
    public function dropPartition($table, $part){
        writeLog("Table:".$table." Drop Partition:".$part);

        $sql = "ALTER TABLE ".$_ENV['database.default.database'].".".$table." DROP PARTITION ".$part;

        $query = $this -> db -> query($sql);
        return $query -> getResult();
    }


}