<?php
namespace App\Models;
use CodeIgniter\Model;

class MoneyHistory_Model extends Model 
{
    protected $table = 'money_history';
    protected $returnType = 'object'; 
    protected $allowedFields = [
        'money_mb_fid', 
        'money_mb_uid', 
        'money_mb_emp_fid', 
        'money_amount', 
        'money_before', 
        'money_after', 
        'money_change_type', 
        'money_bet_round', 
        'money_bet_mode', 
        'money_bet_target', 
        'money_update_time'
    ];
    protected $primaryKey = 'money_fid';
    private $mMemberTable = 'member';

    function registerCharge($objUser, $nChargeMoney)
    {
        if(is_null($objUser)) return false;    
        if($nChargeMoney < 1) return false;

        $this->builder()->set('money_mb_fid', $objUser->mb_fid);
        $this->builder()->set('money_mb_uid', $objUser->mb_uid);
        $this->builder()->set('money_mb_emp_fid', $objUser->mb_emp_fid);        
        $this->builder()->set('money_amount', $nChargeMoney);
        $this->builder()->set('money_before', allMoney($objUser));
        $this->builder()->set('money_after', allMoney($objUser)+$nChargeMoney);
        $this->builder()->set('money_change_type', MONEYCHANGE_CHARGE);     
        $this->builder()->set('money_update_time', 'NOW()', false);
        
        return $this->builder()->insert();
    }

    function cancelCharge($objUser, $nChargeMoney)
    {
        if(is_null($objUser)) return false;    
        if($nChargeMoney < 1) return false;

        $this->builder()->set('money_mb_fid', $objUser->mb_fid);
        $this->builder()->set('money_mb_uid', $objUser->mb_uid);
        $this->builder()->set('money_mb_emp_fid', $objUser->mb_emp_fid);        
        $this->builder()->set('money_amount', (-1) * $nChargeMoney);
        $this->builder()->set('money_before', allMoney($objUser));
        $this->builder()->set('money_after', allMoney($objUser)-$nChargeMoney);
        $this->builder()->set('money_change_type', MONEYCANCEL_CHARGE);     
        $this->builder()->set('money_update_time', 'NOW()', false);
        
        return $this->builder()->insert();
    }
    
    function registerExchange($objUser, $objExchange)
    {
        if(is_null($objUser)) return false;    
        if(is_null($objExchange)) return false;

        $this->builder()->set('money_mb_fid', $objUser->mb_fid);
        $this->builder()->set('money_mb_uid', $objUser->mb_uid);
        $this->builder()->set('money_mb_emp_fid', $objUser->mb_emp_fid);        
        $this->builder()->set('money_amount', (-1) * $objExchange->exchange_money);
        $this->builder()->set('money_before', $objExchange->exchange_money_before);
        $this->builder()->set('money_after', $objExchange->exchange_money_after);
        $this->builder()->set('money_change_type', MONEYCHANGE_EXCHANGE);     
        $this->builder()->set('money_update_time', $objExchange->exchange_time_require);
        
        return $this->builder()->insert();
    }
    
    function updateExchange($objUser, $objExchange, $admin){

        if(is_null($objUser)) return false;    
        if(is_null($objExchange)) return false;

        $strSql2 = "UPDATE ".$this->table." SET ";
        $strSql2.= " money_update_time='".date("Y-m-d H:i:s")."'";
        $strSql2.= ", money_bet_mode=".STATE_ACTIVE;
        $strSql2.= ", money_bet_target='".$admin."'";

        $strSql2.= " WHERE money_update_time >= '".$objExchange->exchange_time_require."'";
        $strSql2.= " AND money_mb_fid=".$objUser->mb_fid;
        $strSql2.= " AND money_change_type = ".MONEYCHANGE_EXCHANGE;
        $strSql2.= " AND money_amount = ".((-1) * $objExchange->exchange_money);

        $objUpdate = $this->db->query($strSql2);
        $affectedRows = $objUpdate->connID->affected_rows;

        $bResult = false;
        if($affectedRows > 0)
            $bResult = true;

        return $bResult;
    }


    function cancelExchange($objUser, $nExchangeMoney)
    {
        if(is_null($objUser)) return false;    
        if($nExchangeMoney < 1) return false;

        $this->builder()->set('money_mb_fid', $objUser->mb_fid);
        $this->builder()->set('money_mb_uid', $objUser->mb_uid);
        $this->builder()->set('money_mb_emp_fid', $objUser->mb_emp_fid);        
        $this->builder()->set('money_amount', $nExchangeMoney);
        $this->builder()->set('money_before', allMoney($objUser));
        $this->builder()->set('money_after', allMoney($objUser)+$nExchangeMoney);
        $this->builder()->set('money_change_type', MONEYCANCEL_EXCHANGE);     
        $this->builder()->set('money_update_time', 'NOW()', false);
        
        return $this->builder()->insert();
    }

    function registerTransfer($objUser, $userId, $nAmount, $type)
    {
        if(is_null($objUser)) return false;

        $this->builder()->set('money_mb_fid', $objUser->mb_fid);
        $this->builder()->set('money_mb_uid', $objUser->mb_uid);
        $this->builder()->set('money_mb_emp_fid', $objUser->mb_emp_fid);        
        $this->builder()->set('money_amount', $nAmount);
        $this->builder()->set('money_before', allMoney($objUser));
        $this->builder()->set('money_after', allMoney($objUser)+$nAmount);
        $this->builder()->set('money_change_type', $type);   
        $this->builder()->set('money_bet_target', $userId);     
        $this->builder()->set('money_update_time', 'NOW()', false);
        
        return $this->builder()->insert();
    }

    function registerWithdraw($objUser, $userId, $nAmount, $type)
    {
        if(is_null($objUser)) return false;

        $this->builder()->set('money_mb_fid', $objUser->mb_fid);
        $this->builder()->set('money_mb_uid', $objUser->mb_uid);
        $this->builder()->set('money_mb_emp_fid', $objUser->mb_emp_fid);        
        $this->builder()->set('money_amount', $nAmount);
        if($type == MONEYCHANGE_WITHDRAW)
        {
            $this->builder()->set('money_before', allMoney($objUser));
            $this->builder()->set('money_after', allMoney($objUser)+$nAmount);
        } else if($type == POINTHANGE_WITHDRAW)
        {
            $this->builder()->set('money_before', $objUser->mb_point);
            $this->builder()->set('money_after', $objUser->mb_point+$nAmount);
        } else return false;
        
        $this->builder()->set('money_change_type', $type);   
        $this->builder()->set('money_bet_target', $userId);     
        $this->builder()->set('money_update_time', 'NOW()', false);
        
        return $this->builder()->insert();
    }

    function registerAccountBet($objUser, $objBetInfo, $nMoney, $iType)
    {
        
        if(is_null($objUser)) return false;    
        
        $this->builder()->set('money_mb_fid', $objUser->mb_fid);
        $this->builder()->set('money_mb_uid', $objUser->mb_uid);
        $this->builder()->set('money_mb_emp_fid', $objUser->mb_emp_fid);        
        $this->builder()->set('money_amount', $nMoney);
        $this->builder()->set('money_before', allMoney($objUser));
        $this->builder()->set('money_after', allMoney($objUser)+$nMoney);
        $this->builder()->set('money_change_type', $iType);     
        $this->builder()->set('money_bet_round', $objBetInfo->bet_round_no);
        $this->builder()->set('money_bet_mode', $objBetInfo->bet_mode);
        $this->builder()->set('money_bet_target', $objBetInfo->bet_target);        
        $this->builder()->set('money_update_time', 'NOW()', false);
        
        return $this->builder()->insert();        
    }
    
    function registerAccountCsBet($objUser, $objBetInfo, $nMoney, $iType)
    {
        
        if(is_null($objUser)) return false;    
        
        $this->builder()->set('money_mb_fid', $objUser->mb_fid);
        $this->builder()->set('money_mb_uid', $objUser->mb_uid);
        $this->builder()->set('money_mb_emp_fid', $objUser->mb_emp_fid);        
        $this->builder()->set('money_amount', $nMoney);
        $this->builder()->set('money_before', allMoney($objUser));
        $this->builder()->set('money_after', allMoney($objUser)+$nMoney);
        $this->builder()->set('money_change_type', $iType);     
        $this->builder()->set('money_bet_round', $objBetInfo->bet_round_no);
        $this->builder()->set('money_bet_target', $objBetInfo->bet_table_code);        
        $this->builder()->set('money_update_time', 'NOW()', false);
        
        return $this->builder()->insert();        
    }
    
    function registerPointToMoney($objMember, $nPoint, $empId, $iType)
    {
        if($nPoint == 0)
            return true;

        try {             
            $data = [  
                'money_mb_fid' => $objMember->mb_fid,
                'money_mb_uid' => $objMember->mb_uid,
                'money_mb_emp_fid' => $objMember->mb_emp_fid,
                'money_amount' => $nPoint,
                'money_before' => allMoney($objMember),
                'money_after' => allMoney($objMember) + $nPoint,
                'money_change_type' => $iType,
                'money_bet_target'=> $empId,
                'money_update_time' => date("Y-m-d H:i:s")
            ];

            return $this->insert($data);
        } catch (\Exception $e) {  
            return false;
        }
        return false;
        
    }

    public function getRangeId($arrReqData){
        $range = [-1, -1];
        
        $strCond = ""; 
        $strCond .=" WHERE money_update_time >= ".$this->db->escape($arrReqData['start']." 00:00:00")." AND money_update_time <= ".$this->db->escape($arrReqData['end']." 23:59:59") ;
        
        $strSQL = " SELECT MIN(money_fid) AS min_fid, MAX(money_fid) AS max_fid FROM ".$this->table;
        $strSQL.= $strCond; 

        // writeLog($strSQL);
        $objResult = $this->db->query($strSQL)->getRow();
        // writeLog("getRangeId END");

        if (!is_null($objResult->min_fid) && !is_null($objResult->max_fid)) {
            $range[0] = $objResult->min_fid;
            $range[1] = $objResult->max_fid;
         }
         return $range;
     }

    function search($objEmp, $arrReqData)
    {
        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_nickname, mb_money, mb_live_money,";
        $strTbColum.= " mb_slot_money, mb_fslot_money, mb_kgon_money, mb_gslot_money, mb_hslot_money,";
        $strTbColum.= " mb_hold_money, mb_rave_money, mb_treem_money, mb_sigma_money ";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid , r.mb_nickname, r.mb_money,";
        $strTbRColum.= " r.mb_live_money, r.mb_slot_money, r.mb_fslot_money, r.mb_kgon_money, r.mb_gslot_money,";
        $strTbRColum.= " r.mb_hslot_money, r.mb_hold_money, r.mb_rave_money, r.mb_treem_money, r.mb_sigma_money ";

        $strSql = "";
        if($objEmp->mb_level < LEVEL_ADMIN){
            $strSql = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSql .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
            $strSql .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
            $strSql .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

            $strSql .= "SELECT * FROM ".$this->table;

            $strSql .="  JOIN (SELECT  * FROM tbmember UNION SELECT ".$strTbColum." FROM ".$this->mMemberTable." where mb_fid='".$objEmp->mb_fid."'";           
            $strSql .=" ) AS mb_table ";
            $strSql .=" ON ".$this->table.".money_mb_fid = mb_table.mb_fid ";

        } else {
            $strSql .= "SELECT * FROM ".$this->table;
            $strSql .="  JOIN ".$this->mMemberTable;           
            $strSql .=" ON ".$this->table.".money_mb_fid = ".$this->mMemberTable.".mb_fid AND ".$this->mMemberTable.".mb_level <=".$objEmp->mb_level;
        }

        $strSql.=" WHERE money_update_time >= ".$this->db->escape($arrReqData['start']." 00:00:00")." AND money_update_time <= ".$this->db->escape($arrReqData['end']." 23:59:59") ;
        if(strlen($arrReqData['user']) > 0){
            $strSql.=" AND money_mb_fid = ".$this->db->escape($arrReqData['user']);
        }
        if(intval($arrReqData['mode']) > 0){
            $strSql.=" AND money_change_type = ".$this->db->escape($arrReqData['mode']);
        } else if(intval($arrReqData['mode']) == -10){
            $modes = [MONEYCHANGE_CHARGE, MONEYCHANGE_EXCHANGE, POINTCHANGE_EXCHANGE, 
                MONEYCHANGE_TRANS_DEC,MONEYCHANGE_TRANS_INC,MONEYCHANGE_EXCHANGE_INC,MONEYCHANGE_EXCHANGE_DEC,
                MONEYCANCEL_EXCHANGE,MONEYCHANGE_INC,MONEYCHANGE_DEC,MONEYCHANGE_WITHDRAW,POINTHANGE_WITHDRAW,
                MONEYCHANGE_GIVE,MONEYCHANGE_CONVERT];
            $strModes = implode(", ", $modes);
            $strSql.=" AND money_change_type IN (".$strModes." )";
        } else {
            if($objEmp->mb_level < LEVEL_ADMIN+2){
                
                $modes = [MONEYCHANGE_CHARGE, MONEYCHANGE_EXCHANGE, POINTCHANGE_EXCHANGE, 
                MONEYCHANGE_TRANS_DEC,MONEYCHANGE_TRANS_INC,MONEYCHANGE_EXCHANGE_INC,MONEYCHANGE_EXCHANGE_DEC,
                MONEYCANCEL_EXCHANGE,MONEYCHANGE_INC,MONEYCHANGE_DEC,MONEYCHANGE_WITHDRAW,POINTHANGE_WITHDRAW,
                MONEYCHANGE_GIVE,MONEYCHANGE_CONVERT, 
                MONEYCHANGE_BET_PB, MONEYCHANGE_DENY_PB, MONEYCHANGE_WIN_PB, 
                MONEYCHANGE_BET_EB, MONEYCHANGE_DENY_EB, MONEYCHANGE_WIN_EB, 
                MONEYCHANGE_BET_SK, MONEYCHANGE_DENY_SK, MONEYCHANGE_WIN_SK, 
                MONEYCHANGE_BET_BB, MONEYCHANGE_DENY_BB, MONEYCHANGE_WIN_BB,
                MONEYCHANGE_BET_EO5, MONEYCHANGE_DENY_EO5, MONEYCHANGE_WIN_EO5, 
                MONEYCHANGE_BET_EO3, MONEYCHANGE_DENY_EO3, MONEYCHANGE_WIN_EO3,
                MONEYCHANGE_BET_CO5, MONEYCHANGE_DENY_CO5, MONEYCHANGE_WIN_CO5, 
                MONEYCHANGE_BET_CO3, MONEYCHANGE_DENY_CO5, MONEYCHANGE_WIN_CO3, 
                MONEYCHANGE_BET_EBAL, MONEYCHANGE_WIN_EBAL,
                MONEYCHANGE_BET_PBAL, MONEYCHANGE_WIN_PBAL,
            ];
                $strModes = implode(", ", $modes);
                $strSql.=" AND money_change_type IN (".$strModes." )";
            }
        }

        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
        $strSql.=" ORDER BY money_fid DESC LIMIT ".$nStartRow.", ".$arrReqData['count'];
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        // writeLog($strSql);
        
        return $result; 

    }


    function searchCount($objEmp, $arrReqData)
    {
        $tbMoneyStat = "money_history_st";
        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid ";
        $strSql = "";
        if($objEmp->mb_level < LEVEL_ADMIN){
            $strSql = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSql .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
            $strSql .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
            $strSql .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";
        }

        $strMode = "";
        if(intval($arrReqData['mode']) > 0){
            $strMode.="money_cnt_".trim($arrReqData['mode']);
        } else if(intval($arrReqData['mode']) == -10){
            $modes = [MONEYCHANGE_CHARGE, MONEYCHANGE_EXCHANGE, POINTCHANGE_EXCHANGE, 
                MONEYCHANGE_TRANS_DEC,MONEYCHANGE_TRANS_INC,MONEYCHANGE_EXCHANGE_INC,MONEYCHANGE_EXCHANGE_DEC, 
                MONEYCANCEL_EXCHANGE,MONEYCHANGE_INC,MONEYCHANGE_DEC,MONEYCHANGE_WITHDRAW,POINTHANGE_WITHDRAW,
                MONEYCHANGE_GIVE,MONEYCHANGE_CONVERT];
            for($i=0; $i<count($modes); $i++){
                if($i > 0)
                    $strMode.="+";
                $strMode.="money_cnt_".$modes[$i];
            }            
        } else {
            if($objEmp->mb_level >= LEVEL_ADMIN+2)
                $strMode.="money_cnt";
            else {
                $modes = [MONEYCHANGE_CHARGE, MONEYCHANGE_EXCHANGE, POINTCHANGE_EXCHANGE, 
                MONEYCHANGE_TRANS_DEC,MONEYCHANGE_TRANS_INC,MONEYCHANGE_EXCHANGE_INC,MONEYCHANGE_EXCHANGE_DEC,
                MONEYCANCEL_EXCHANGE,MONEYCHANGE_INC,MONEYCHANGE_DEC,MONEYCHANGE_WITHDRAW,POINTHANGE_WITHDRAW,
                MONEYCHANGE_GIVE,MONEYCHANGE_CONVERT, 
                MONEYCHANGE_BET_PB, MONEYCHANGE_DENY_PB, MONEYCHANGE_WIN_PB, 
                MONEYCHANGE_BET_EB, MONEYCHANGE_DENY_EB, MONEYCHANGE_WIN_EB, 
                MONEYCHANGE_BET_SK, MONEYCHANGE_DENY_SK, MONEYCHANGE_WIN_SK, 
                MONEYCHANGE_BET_BB, MONEYCHANGE_DENY_BB, MONEYCHANGE_WIN_BB,
                MONEYCHANGE_BET_EO5, MONEYCHANGE_DENY_EO5, MONEYCHANGE_WIN_EO5, 
                MONEYCHANGE_BET_EO3, MONEYCHANGE_DENY_EO3, MONEYCHANGE_WIN_EO3,
                MONEYCHANGE_BET_CO5, MONEYCHANGE_DENY_CO5, MONEYCHANGE_WIN_CO5, 
                MONEYCHANGE_BET_CO3, MONEYCHANGE_DENY_CO5, MONEYCHANGE_WIN_CO3, 
                MONEYCHANGE_BET_EBAL, MONEYCHANGE_WIN_EBAL,
                MONEYCHANGE_BET_PBAL, MONEYCHANGE_WIN_PBAL
            ];
                for($i=0; $i<count($modes); $i++){
                    if($i > 0)
                        $strMode.="+";
                    $strMode.="money_cnt_".$modes[$i];
                }
            }
        }
            
        $strSql .= "SELECT SUM(".$strMode.") as count  FROM ".$tbMoneyStat;
        if($objEmp->mb_level < LEVEL_ADMIN){
            $strSql .="  JOIN (SELECT  * FROM tbmember UNION SELECT ".$strTbColum." FROM ".$this->mMemberTable." where mb_fid='".$objEmp->mb_fid."'";           
            $strSql .=" ) AS mb_table ";
            $strSql .=" ON ".$tbMoneyStat.".money_mb_fid = mb_table.mb_fid ";
        } else {
            $strSql .="  JOIN ".$this->mMemberTable;           
            $strSql .=" ON ".$tbMoneyStat.".money_mb_fid = ".$this->mMemberTable.".mb_fid AND ".$this->mMemberTable.".mb_level <=".$objEmp->mb_level;
        }
        
        $strSql.=" WHERE money_start >= ".$this->db->escape($arrReqData['start']." 00:00:00")." AND money_end <= ".$this->db->escape($arrReqData['end']." 23:59:59") ;
        if(strlen($arrReqData['user']) > 0){
            $strSql.=" AND money_mb_fid = ".$this->db->escape($arrReqData['user']);
        }
        
        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        writeLog($strSql);

        return $result; 

    }



}