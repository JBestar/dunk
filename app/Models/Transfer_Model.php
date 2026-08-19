<?php
namespace App\Models;
use CodeIgniter\Model;

class Transfer_Model extends Model {
	
	protected $table = 'money_transfer';
    protected $returnType = 'object'; 
    protected $allowedFields = ['trans_mb_fid', 'trans_mb_uid', 'trans_emp_fid', 'trans_amount', 
        'money_before', 'money_after', 'egg_before', 'egg_after', 'trans_type', 
        'trans_time'];

    protected $primaryKey = 'money_fid';
    private $mMemberTable  = 'member';

    public function register($type, $objUser, $gPoint, $balance){
        if(is_null($objUser)) return false;    
        if($balance == 0) return true;

        $data = [
            'trans_mb_fid' => $objUser->mb_fid,
            'trans_mb_uid' => $objUser->mb_uid,
            'trans_emp_fid' => $objUser->mb_emp_fid,
            'trans_amount' => $balance,
            'money_before' => $objUser->mb_money,
            'money_after' => $objUser->mb_money - $balance,
            'egg_before' => $gPoint,
            'egg_after' => $gPoint + $balance,
            'trans_type' => $type,
            'trans_time' => date("Y-m-d H:i:s"),
        ];

        return $this->insert($data);
    }

    function search($objEmp, $arrReqData)
    {
        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_nickname, mb_money, mb_live_money,";
        $strTbColum.= " mb_slot_money, mb_fslot_money, mb_kgon_money, mb_gslot_money, mb_hslot_money,";
        $strTbColum.= " mb_hold_money, mb_rave_money, mb_treem_money, mb_sigma_money ";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid , r.mb_nickname, r.mb_money,";
        $strTbRColum.= " r.mb_live_money, r.mb_slot_money, r.mb_fslot_money, r.mb_kgon_money,";
        $strTbRColum.= " r.mb_gslot_money, r.mb_hslot_money, r.mb_hold_money, r.mb_rave_money,";
        $strTbRColum.= " r.mb_treem_money, r.mb_sigma_money ";

        $strSql = "";
        if($objEmp->mb_level < LEVEL_ADMIN){
            $strSql = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSql .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
            $strSql .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
            $strSql .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

            $strSql .= "SELECT * FROM ".$this->table;

            $strSql .="  JOIN (SELECT  * FROM tbmember UNION SELECT ".$strTbColum." FROM ".$this->mMemberTable." where mb_fid='".$objEmp->mb_fid."'";           
            $strSql .=" ) AS mb_table ";
            $strSql .=" ON ".$this->table.".trans_mb_fid = mb_table.mb_fid ";

        } else {
            $strSql .= "SELECT * FROM ".$this->table;
        }
        $strSql.=" WHERE trans_time >= ".$this->db->escape($arrReqData['start']." 00:00:00")." AND trans_time <= ".$this->db->escape($arrReqData['end']." 23:59:59") ;
        if(strlen($arrReqData['user']) > 0){
            $strSql.=" AND trans_mb_fid = ".$this->db->escape($arrReqData['user']);
        }
        if(intval($arrReqData['mode']) > 0){
            if(intval($arrReqData['mode']) == 1){
                $strSql.=" AND trans_type IN (".TRANS_SITE_EVOL.", ".TRANS_SITE_KGON.", ".TRANS_SITE_RAVE.", ".TRANS_SITE_TREEM.", ".TRANS_SITE_SIGMA.")";
            } else if(intval($arrReqData['mode']) == 2){
                $strSql.=" AND trans_type IN (".TRANS_EVOL_SITE.", ".TRANS_KGON_SITE.", ".TRANS_RAVE_SITE.", ".TRANS_TREEM_SITE.", ".TRANS_SIGMA_SITE.")";
            } else if(intval($arrReqData['mode']) == 3){
                $strSql.=" AND trans_type IN (".TRANS_SITE_PLUS.", ".TRANS_SITE_GSPL.", ".TRANS_SITE_GOLD.", ".TRANS_SITE_STAR.")";
            } else if(intval($arrReqData['mode']) == 4){
                $strSql.=" AND trans_type IN (".TRANS_PLUS_SITE.", ".TRANS_GOLD_SITE.", ".TRANS_STAR_SITE.", ".TRANS_SITE_STAR.")";
            } else if(intval($arrReqData['mode']) == 5){
                $strSql.=" AND trans_type IN (".TRANS_SITE_HOLD.")";
            } else if(intval($arrReqData['mode']) == 6){
                $strSql.=" AND trans_type IN (".TRANS_HOLD_SITE.")";
            } else if(intval($arrReqData['mode']) == 7){
                $strSql.=" AND trans_type IN (".RECOVER_EGG.")";
            }

        }

        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
        $strSql.=" ORDER BY trans_fid DESC LIMIT ".$nStartRow.", ".$arrReqData['count'];
        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        // writeLog($strSql);
        
        return $result; 

    }


    function searchCount($objEmp, $arrReqData)
    {
        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid";
        $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid ";
        $strSql = "";
        if($objEmp->mb_level < LEVEL_ADMIN){
            $strSql = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSql .= " ( SELECT ".$strTbColum." FROM ".$this->mMemberTable." WHERE mb_emp_fid = '".$objEmp->mb_fid."'";
            $strSql .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mMemberTable." r ";
            $strSql .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";
        }
        $strSql .= "SELECT count(*) as count  FROM ".$this->table;
        if($objEmp->mb_level < LEVEL_ADMIN){
            $strSql .="  JOIN (SELECT  * FROM tbmember UNION SELECT ".$strTbColum." FROM ".$this->mMemberTable." where mb_fid='".$objEmp->mb_fid."'";           
            $strSql .=" ) AS mb_table ";
            $strSql .=" ON ".$this->table.".trans_mb_uid = mb_table.mb_uid ";
        }
        $strSql.=" WHERE trans_time >= ".$this->db->escape($arrReqData['start']." 00:00:00")." AND trans_time <= ".$this->db->escape($arrReqData['end']." 23:59:59") ;
        if(strlen($arrReqData['user']) > 0){
            $strSql.=" AND trans_mb_fid = ".$this->db->escape($arrReqData['user']);
        }
        if(intval($arrReqData['mode']) > 0){
            if(intval($arrReqData['mode']) == 1){
                $strSql.=" AND trans_type IN (".TRANS_SITE_EVOL.", ".TRANS_SITE_KGON.", ".TRANS_SITE_RAVE.", ".TRANS_SITE_TREEM.", ".TRANS_SITE_SIGMA.")";
            } else if(intval($arrReqData['mode']) == 2){
                $strSql.=" AND trans_type IN (".TRANS_EVOL_SITE.", ".TRANS_KGON_SITE.", ".TRANS_RAVE_SITE.", ".TRANS_TREEM_SITE.", ".TRANS_SIGMA_SITE.")";
            } else if(intval($arrReqData['mode']) == 3){
                $strSql.=" AND trans_type IN (".TRANS_SITE_PLUS.", ".TRANS_SITE_GSPL.", ".TRANS_SITE_GOLD.", ".TRANS_SITE_STAR.")";
            } else if(intval($arrReqData['mode']) == 4){
                $strSql.=" AND trans_type IN (".TRANS_PLUS_SITE.", ".TRANS_GOLD_SITE.", ".TRANS_STAR_SITE.", ".TRANS_SITE_STAR.")";
            } else if(intval($arrReqData['mode']) == 5){
                $strSql.=" AND trans_type IN (".TRANS_SITE_HOLD.")";
            } else if(intval($arrReqData['mode']) == 6){
                $strSql.=" AND trans_type IN (".TRANS_HOLD_SITE.")";
            } else if(intval($arrReqData['mode']) == 7){
                $strSql.=" AND trans_type IN (".RECOVER_EGG.")";
            }
        }
        
        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        // writeLog($strSql);

        return $result; 

    }



}