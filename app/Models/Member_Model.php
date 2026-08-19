<?php

namespace App\Models;

use CodeIgniter\Model;

class Member_Model extends Model
{
    protected $table = 'member';
    private $chargeTb = 'member_charge';
    protected $exchangeTb = 'member_exchange';
    protected $noticeTb = 'board_notice';
    private $historyTb = 'money_history_st';
    protected $rewardTb = 'bet_reward_st';
    protected $rewardMnTb = 'bet_reward_mn_st';
    protected $rewardPrTb = 'bet_reward_pr_st';
    protected $returnType = 'object'; 

    protected $allowedFields = [
        'mb_uid', 'mb_pwd', 'mb_level', 'mb_emp_fid', 'mb_emp_permit', 'mb_nickname', 'mb_email', 'mb_phone',
        'mb_bank_name', 'mb_bank_own', 'mb_bank_num', 'mb_bank_pwd', 'mb_time_join', 'mb_time_last', 'mb_time_bet', 'mb_time_call', 
        'mb_ip_join', 'mb_ip_last', 'mb_money', 'mb_point', 
        'mb_grade', 'mb_color', 'mb_memo', 'mb_state_active', 'mb_state_bet', 'mb_state_delete', 'mb_state_alarm', 'mb_state_view', 'mb_state_test', 
        'mb_game_pb', 'mb_game_ps', 'mb_game_ks', 'mb_game_bb', 'mb_game_bs', 'mb_game_cs', 'mb_game_sl', 'mb_game_eo', 'mb_game_co', 'mb_game_hl',
        'mb_game_pb_ratio', 'mb_game_pb2_ratio', 'mb_game_cs_ratio', 'mb_game_sl_ratio', 'mb_game_hl_ratio', 
        'mb_game_pb_percent', 'mb_game_pb2_percent', 
        'mb_blank_count', 'mb_range_ev', 'mb_press_ev', 'mb_pressat_ev', 'mb_follow_ev', 'mb_follow_en', 
        'mb_live_id', 'mb_live_uid', 'mb_live_money',
        'mb_slot_uid', 'mb_slot_money',
        'mb_fslot_id', 'mb_fslot_uid', 'mb_fslot_money',
        'mb_kgon_id', 'mb_kgon_uid', 'mb_kgon_money',
        'mb_gslot_uid', 'mb_gslot_money',
        'mb_hslot_token', 'mb_hslot_money',
        'mb_hold_uid', 'mb_hold_money',
        'mb_rave_id', 'mb_rave_uid', 'mb_rave_money',
        'mb_treem_uid', 'mb_treem_money',
        'mb_sigma_uid', 'mb_sigma_money',
    ];

    protected $primaryKey = 'mb_fid';
    
    private $getFields = ['mb_fid', 'mb_uid', 'mb_level','mb_emp_fid', 'mb_emp_permit', 'mb_nickname', 
        'mb_email', 'mb_phone', 'mb_bank_name', 'mb_bank_own', 'mb_bank_num', 'mb_bank_pwd',
        'mb_time_bet', 'mb_ip_join', 'mb_ip_last', 'mb_time_join', 'mb_time_last', 'mb_time_call', 
        'mb_money', 'mb_point', 'mb_grade', 'mb_color',
        'mb_state_active', 'mb_state_delete', 'mb_state_alarm', 'mb_state_view', 'mb_state_test', 
        'mb_game_pb', 'mb_game_ps', 'mb_game_ks', 'mb_game_bb', 'mb_game_bs', 'mb_game_cs', 'mb_game_sl', 'mb_game_eo', 'mb_game_co', 'mb_game_hl', 
        'mb_game_pb_ratio', 'mb_game_pb2_ratio', 'mb_game_cs_ratio', 'mb_game_sl_ratio', 'mb_game_hl_ratio', 
        'mb_game_pb_percent', 'mb_game_pb2_percent', 'mb_blank_count',
        'mb_live_id', 'mb_live_uid', 'mb_live_money', 
        'mb_slot_uid', 'mb_slot_money', 
        'mb_fslot_id', 'mb_fslot_uid', 'mb_fslot_money',
        'mb_kgon_id', 'mb_kgon_uid', 'mb_kgon_money',
        'mb_gslot_uid', 'mb_gslot_money',
        'mb_hslot_token', 'mb_hslot_money',
        'mb_hold_uid', 'mb_hold_money',
        'mb_rave_id', 'mb_rave_uid', 'mb_rave_money',
        'mb_treem_uid', 'mb_treem_money',
        'mb_sigma_uid', 'mb_sigma_money',
    ];

    private $fields = ['mb_fid', 'mb_uid', 'mb_level','mb_emp_fid','mb_nickname', 'mb_ip_last',
        'mb_money', 'mb_point', 'mb_grade', 'mb_color', 'mb_state_active', 'mb_state_delete', 'mb_state_alarm', 'mb_state_view', 'mb_state_test', 
        'mb_game_pb', 'mb_game_ps', 'mb_game_ks', 'mb_game_bb', 'mb_game_bs', 'mb_game_cs', 'mb_game_sl', 'mb_game_eo', 'mb_game_co', 'mb_game_hl', 
        'mb_game_pb_ratio', 'mb_game_pb2_ratio', 'mb_game_cs_ratio', 'mb_game_sl_ratio', 'mb_game_hl_ratio', 
        'mb_blank_count', 'mb_live_money', 'mb_slot_money', 'mb_fslot_money', 'mb_kgon_money', 'mb_gslot_money', 'mb_hslot_money', 'mb_hold_money', 
        'mb_rave_money', 'mb_treem_money', 'mb_sigma_money' ];

    
    protected $validationRules = [
        'mb_uid' => 'required|alpha_numeric|is_unique[member.mb_uid, mb_fid, {mb_fid}]',
        'mb_nickname' => 'required|min_length[2]|max_length[20]|is_unique[member.mb_nickname, mb_fid, {mb_fid}]',
        'mb_pwd' => 'required',
        'mb_level' => 'required',
        'mb_phone' => 'required|numeric',
        'mb_bank_pwd' => 'required',
        'mb_bank_name' => 'required',
    ];
    protected $validationMessages = [
        'mb_uid' => [
            'required' => '아이디는 필수입력사항입니다.',
            'alpha_numeric' => '아이디는 영문,숫자만 가능합니다.',
            'is_unique' => '아이디가 이미 존재합니다.',
        ],
        'mb_nickname' => [
            'required' => '닉네임은 필수입력사항입니다.',
            'min_length' => '닉네임은 최소 2글자 이상입니다.',
            'max_length' => '닉네임은 최대 20글자 이하입니다.',
            'is_unique' => '닉네임이 이미 존재합니다.',
        ],
        'mb_pwd' => [
            'required' => '비밀번호는 필수입력사항입니다.',
        ],
        'mb_level' => [
            'required' => '레벨은 필수입력사항입니다.',
        ],
        'mb_phone' => [
            'required' => '핸드폰번호는 필수입력사항입니다.',
            'numeric' => '핸드폰번호는 숫자만 가능합니다.',
        ],
        'mb_bank_pwd' => [
            'required' => '출금비번은 필수입력사항입니다.',
        ],
        'mb_bank_name' => [
            'required' => '은행번호는 필수입력사항입니다.',
        ],
    ];

    
    public function getByFid($fid){
        
        return $this->select($this->getFields)
                    ->find($fid);
    }
    
    public function getInfoByFid($strFid, $bAll = false)
    {
        if($bAll)
            return $this->find($strFid);
        else return $this->select($this->getFields)->find($strFid);
    }
    
    public function getInfo($strId, $bAll = false)
    {
        if($bAll)
            return $this->where('mb_uid', $strId)->first();
        else return $this->select($this->getFields)->where('mb_uid', $strId)->first();

    }

    public function getInfoByUid($strId)
    {
        return $this->select($this->fields)->where('mb_uid', $strId)->first();
    }

    public function getByNickname($strName)
    {
        return $this->select($this->getFields)->where('mb_nickname', $strName)->first();
    }

    public function getByBankOwner($owner, $bAll = false)
    {
        $where =" mb_state_active != '".PERMIT_DELETE."' ";
        $where.=" AND mb_bank_own = '".$owner."' ";

        return $this->select($this->getFields)->where($where)->first();
    }

    public function getByBankName($bank, $name){
        
        $where = "mb_bank_name = '".$bank."' ";
        $where.= "AND mb_bank_own = '".$name."' ";
        $where.= "AND mb_state_active != '".PERMIT_DELETE."' ";

        return $this->select($this->getFields)
                    ->where($where)
                    ->first(); 
        
    }
    public function login($strUserId, $strPwd)
    {
        return $this->where([
            'mb_uid' => $strUserId,
            'mb_pwd' => $strPwd ])
            ->first();
    }

    public function changePassword($strUserId, $arrPwd, &$query)
    {
        if (is_null($arrPwd)) {
            return 0;
        }
        if (!array_key_exists('password', $arrPwd)) {
            return 0;
        }
        if (!array_key_exists('password_new', $arrPwd)) {
            return 0;
        }
        if (!array_key_exists('password_newok', $arrPwd)) {
            return 0;
        }

        
        $objUser = $this->login($strUserId, $arrPwd['password']);
        if (is_null($objUser) ) {
            return 2;
        }

        // 이전 패스워드가 같은가 체크
        if (strlen($arrPwd['password_new']) > 0 && 0 != strcmp($arrPwd['password_new'], $arrPwd['password_newok'])) {
            return 0;
        }

        if(strlen($arrPwd['password_new']) < 1 && !array_key_exists('ip_addr', $arrPwd) )
            return 0; 

        if(strlen($arrPwd['password_new']) > 0)
            $data['mb_pwd'] =  $arrPwd['password_new'];

        if(array_key_exists('ip_addr', $arrPwd) ){
            $data['mb_ip_join'] = $arrPwd['ip_addr'];
            $data['mb_state_view'] = $arrPwd['ip_check'];

        }

        $this->builder()->set($data)
        ->where('mb_fid', $objUser->mb_fid)
        ->update();

        $query = $this->db->getLastQuery();
        return 1;
    }

    public function changeAlarmState($strUserId, $arrReqData)
    {
        if (is_null($arrReqData)) {
            return 0;
        }
        if (!array_key_exists('mb_state_alarm', $arrReqData)) {
            return false;
        }

        return $this->builder()->set('mb_state_alarm', $arrReqData['mb_state_alarm'])
        ->where('mb_uid', $strUserId)
        ->update();
    }

    public function updateCallTm($member){
        $data = [
            'mb_time_call' => date("Y-m-d H:i:s"),
        ];
        return $this->update($member->mb_fid, $data);
    }

    
    public function updateAssets(&$objUser, $inMoney , $inPoint = 0, $iChange=-1, $spec=""){

        if(is_null($objUser))
            return false;

        $inMoney = floatval($inMoney);
        $inPoint = floatval($inPoint);

        if($inMoney == 0 && $inPoint == 0)
            return true;
        $strSql1 = 'SELECT mb_money FROM '.$this->table;
        $strSql1 .= ' WHERE mb_fid='.$objUser->mb_fid;

        $strSql2 = "UPDATE ".$this->table." SET ";
        if($inMoney != 0){
            $strSql2.= "mb_money = mb_money";
            $strSql2.= $inMoney > 0 ? " + ":" ";
            $strSql2.= $inMoney;   
            $strSql2.= ", mb_change = ".$iChange;
            $strSql2.= ", mb_spec = '".$spec."'";
        }
        
        if($inPoint != 0){
            $strSql2.= $inMoney != 0 ? " , ":" ";

            $strSql2.= "mb_point = mb_point";
            $strSql2.= $inPoint > 0 ? " + ":" ";
            $strSql2.= $inPoint;
        }

        $strSql2.= " WHERE mb_fid=".$objUser->mb_fid;
        if($inMoney < 0){
            $strSql2.= " AND mb_money >= ".abs($inMoney);
        }

        $this->db->transBegin();

        $objMember = $this->db->query($strSql1)->getRow();
        $objUpdate = $this->db->query($strSql2);
        $affectedRows = $objUpdate->connID->affected_rows;

        $bResult = false;

        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            $bResult = false;
        } else {
            $this->db->transCommit();
            if(!is_null($objMember))
                $objUser->mb_money = $objMember->mb_money;
            if($affectedRows > 0)
                $bResult = true;
        }

        return $bResult;
    }

    public function trasferMoney($objSender, $objReceiver, $amount, $iChange1, $iChange2){

        $amount = floatval($amount);
        if($amount <= 0)
            return false;
        
        $this->db->transBegin();

        $strSql1 = 'UPDATE '.$this->table.' SET ';
        $strSql1 .= 'mb_money = mb_money-'.$amount;
        $strSql1 .= ', mb_change = '.$iChange1;
        $strSql1 .= ", mb_spec = '".$objReceiver->mb_uid."'";
        $strSql1 .= ' WHERE mb_fid='.$objSender->mb_fid;
        $this->db->query($strSql1);

        writeLog($objSender->mb_fid."->".$objReceiver->mb_uid." ".$iChange1);

        $strSql2 = 'UPDATE '.$this->table.' SET ';
        $strSql2 .= 'mb_money = mb_money+'.$amount;
        $strSql1 .= ', mb_change = '.$iChange2;
        $strSql1 .= ", mb_spec = '".$objSender->mb_uid."'";
        $strSql2 .= ' WHERE mb_fid='.$objReceiver->mb_fid;
        $this->db->query($strSql2);
        
        writeLog($objReceiver->mb_fid."->".$objSender->mb_uid." ".$iChange2);

        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            $bResult = false;
        } else {
            $this->db->transCommit();
            $bResult = true;
        }

        return $bResult;
        // return true;
    }

    public function updateState($objMember, $arrReqData)
    {

        if(array_key_exists('mb_state_view', $arrReqData))
            $this->builder()->set('mb_state_view', $arrReqData['mb_state_view']);
        else return false;

        $this->builder()->where('mb_fid', $objMember->mb_fid);

        return $this->builder()->update();
    }

     public function gameRange(&$arrReqData, $bRw = true)
     {
        if ($arrReqData['type'] == GAME_PBG_BALL || $arrReqData['type'] == GAME_DHP_BALL ||
            $arrReqData['type'] == GAME_BOGLE_BALL || $arrReqData['type'] == GAME_BOGLE_LADDER ||
            $arrReqData['type'] == GAME_EOS5_BALL || $arrReqData['type'] == GAME_EOS3_BALL ||
            $arrReqData['type'] == GAME_COIN5_BALL || $arrReqData['type'] == GAME_COIN3_BALL || 
            $arrReqData['type'] == GAME_SPKN_BALL) {
            $arrReqData['gm_range'] = $this->getBetRangeId($arrReqData, "bet_pball");
            if($bRw)
                $arrReqData['rw_range'] = $this->getRwRangeId($arrReqData, "bet_reward_mn");
        } elseif ($arrReqData['type'] == GAME_CASINO_EVOL ) {
            $tbName = "bet_casino";
            $arrReqData['gm_range'] = $this->getBetRangeId($arrReqData, $tbName);
            if($bRw)
                $arrReqData['rw_range'] = $this->getRwRangeId($arrReqData, "bet_reward");
        } elseif ($arrReqData['type'] == GAME_AUTO_EVOL ) {
            $tbName = "bet_ebal";
            $arrReqData['gm_range'] = $this->getBetRangeId($arrReqData, $tbName);
            if($bRw)
                $arrReqData['rw_range'] = $this->getRwRangeId($arrReqData, "bet_reward");
        }  elseif ($arrReqData['type'] == GAME_SLOT_THEPLUS || $arrReqData['type'] == GAME_SLOT_GSPLAY || 
            $arrReqData['type'] == GAME_SLOT_GOLD || $arrReqData['type'] == GAME_SLOT_KGON || $arrReqData['type'] == GAME_SLOT_STAR ||
            $arrReqData['type'] == GAME_SLOT_RAVE || $arrReqData['type'] == GAME_SLOT_TREEM || $arrReqData['type'] == GAME_SLOT_ALL ) {
            $arrReqData['gm_range'] = $this->getBetRangeId($arrReqData, "bet_slot");
            if($bRw)
                $arrReqData['rw_range'] = $this->getRwRangeId($arrReqData, "bet_reward");
        } elseif ($arrReqData['type'] == GAME_HOLD_CMS ) {
            $arrReqData['gm_range'] = $this->getBetRangeId($arrReqData, "bet_holdem");
            if($bRw)
                $arrReqData['rw_range'] = $this->getRwRangeId($arrReqData, "bet_reward");
        }
        
        
     }

     public function getBetRangeId($arrReqData, $tbName){
        $range = [-1, -1];
        
        $strCond = ""; 
        if (strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0) {
            $strCond = " WHERE ".getBetTimeRange($arrReqData, $this->db);
        }
        $strSQL = " SELECT MIN(bet_fid) AS min_fid, MAX(bet_fid) AS max_fid FROM ".$tbName;
        $strSQL.= $strCond; 

        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog($strSQL);
        $objResult = $this->db->query($strSQL)->getRow();
        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog("getBetRangeId END");

        if (!is_null($objResult->min_fid) && !is_null($objResult->max_fid)) {
            $range[0] = $objResult->min_fid;
            $range[1] = $objResult->max_fid;
         }
         return $range;
     }

     public function getBetMinId($arrReqData, $tbName){
        $range = [-1, -1];
        
        $strCond = ""; 
        if (strlen($arrReqData['start']) > 0) {
            $strCond = " WHERE bet_time >= '".$arrReqData['start']."' " ;
        }
        $strSQL = " SELECT MIN(bet_fid) AS min_fid FROM ".$tbName;
        $strSQL.= $strCond; 

        // writeLog($strSQL);
        $objResult = $this->db->query($strSQL)->getRow();
        // writeLog("getBetRangeId END");

        if (!is_null($objResult->min_fid)) {
            $range[0] = $objResult->min_fid;
         }
         
         return $range;
     }

     public function getRwRangeId($arrReqData, $tbName){
        $range = [-1, -1];
        
        $strCond = ""; 
        if (strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0) {
            $strCond = " WHERE ".getTimeRange("rw_time", $arrReqData, $this->db);
        }
        $strSQL = " SELECT MIN(rw_fid) AS min_fid, MAX(rw_fid) AS max_fid FROM ".$tbName;
        $strSQL.= $strCond; 

        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog($strSQL);
        $objResult = $this->db->query($strSQL)->getRow();
        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog("getRwRangeId END");

        if (!is_null($objResult->min_fid) && !is_null($objResult->max_fid)) {
            $range[0] = $objResult->min_fid;
            $range[1] = $objResult->max_fid;
         }
         return $range;
     }
     
    //유저별 충환전 총액
    public function calcTransfer(&$objUser){
        $strSQL = " SELECT SUM(charge_money) AS result_1 FROM ".$this->chargeTb;
        $strSQL.=" WHERE (charge_action_state = '".STATE_VERIFY."' OR charge_action_state = '".STATE_HOT."') "; 
        $strSQL.=" AND charge_mb_uid = '".$objUser->mb_uid."' ";
        $strSQL .= " UNION ALL (SELECT SUM(exchange_money) AS result_1 FROM ".$this->exchangeTb;
        $strSQL.=" WHERE (exchange_action_state = '".STATE_VERIFY."' OR exchange_action_state = '".STATE_HOT."') "; 
        $strSQL.=" AND exchange_mb_uid = '".$objUser->mb_uid."') ";
        $arrResult = $this->db->query($strSQL)->getResult();

        if(is_null($arrResult) || count($arrResult) != 2){
            $objUser->mb_money_charge = 0;
            $objUser->mb_money_exchange = 0;
        } else {
            $objUser->mb_money_charge = $arrResult[0]->result_1 != null ? $arrResult[0]->result_1 : 0 ;
            $objUser->mb_money_exchange = $arrResult[1]->result_1 != null ? $arrResult[1]->result_1 : 0 ;
        }
    }

    private function calcCommonSql($objEmp, $arrReqData){

        $getFields = ['mb_fid', 'mb_uid', 'mb_level', 'mb_emp_fid', 'mb_state_active', 'mb_money', 'mb_point', 
            'mb_live_money', 'mb_slot_money', 'mb_fslot_money', 'mb_kgon_money', 'mb_gslot_money', 'mb_hslot_money', 
            'mb_hold_money', 'mb_rave_money', 'mb_treem_money', 'mb_sigma_money'];
        $strTbColum = " ".implode(", ", $getFields);
        $strTbRColum = " r.".implode(", r.", $getFields);
  
        $strSQL = 'WITH RECURSIVE tbmember ('.$strTbColum.') AS';
        $strSQL .= ' ( SELECT '.$strTbColum.' FROM '.$this->table." WHERE "; 
        $strSQL .= " mb_fid = '".$objEmp->mb_fid."'";
        $strSQL .= ' UNION ALL SELECT '.$strTbRColum.' FROM '.$this->table.' r ';
        $strSQL .= ' INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )';
        //보유금액
        $strSQL .= " SELECT SUM(".allMoneySql().") AS result_1, ";
        $strSQL .= " SUM(CASE WHEN mb_fid = ".$objEmp->mb_fid." THEN ".allMoneySql()." ELSE 0 END) AS result_2 "; 
        $strSQL .= " FROM tbmember  WHERE mb_state_active != '".PERMIT_DELETE."' ";
        //포인트
        $strSQL .= " UNION ALL ( SELECT SUM(mb_point) AS result_1, ";
        $strSQL .= " SUM(CASE WHEN mb_fid = ".$objEmp->mb_fid." THEN mb_point ELSE 0 END) AS result_2 ";
        $strSQL .= " FROM tbmember  WHERE mb_state_active != '".PERMIT_DELETE."' )";
        //회원수
        $strSQL .= " UNION ALL ( SELECT COUNT(mb_fid) AS result_1, 0 AS result_2 ";
        $strSQL .= " FROM tbmember  WHERE mb_state_active != '".PERMIT_DELETE."' )";
        //충전금액
        $strSQL .= " UNION ALL ( SELECT SUM(charge_money) AS result_1, '0' AS result_2 FROM ".$this->chargeTb;
        $strSQL.=" WHERE (charge_action_state = '2'  OR charge_action_state = '5') ";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 )
            $strSQL.=" AND ".getTimeRange("charge_time_require", $arrReqData, $this->db);
        $strSQL .= " AND charge_mb_uid IN (SELECT mb_uid from tbmember ) )";
        //환전금액
        $strSQL .= " UNION ALL ( SELECT SUM(exchange_money) AS result_1, '0' AS result_2 FROM ".$this->exchangeTb;
        $strSQL.=" WHERE (exchange_action_state = '2'  OR exchange_action_state = '5') ";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 )
            $strSQL.=" AND ".getTimeRange("exchange_time_require", $arrReqData, $this->db);
        $strSQL .= " AND exchange_mb_uid IN (SELECT mb_uid from tbmember ) )";
        //지급 회수
        $strSQL .= " UNION ALL ( SELECT SUM(money_give) AS result_1, ";
        $strSQL .= " SUM(money_withdraw) AS result_2 FROM ".$this->historyTb;
        $strSQL .= " WHERE money_mb_fid IN (SELECT mb_fid from tbmember ) ";
        if(strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0 )
            $strSQL.=" AND ".getStatRange('money_start', 'money_end', $arrReqData, $this->db);
        $strSQL .= " )";
        // writeLog($strSQL);
        return $strSQL;
    }

    public function calculate($objEmp, $arrReqData, $confs){

        $strSQL = $this->calcCommonSql($objEmp, $arrReqData);
        $strWhereMem = " AND bet_mb_uid IN (SELECT mb_uid from tbmember) ";
        //배팅금액
        $strSQL .= ' UNION ALL ( SELECT SUM(bet_money) AS result_1, SUM(bet_win_money) AS result_2 ';
        $strSQL .= '  FROM ( SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_slot';
        // $strSQL .= " WHERE bet_fid >= ".$arrReqData['slot_range'][0]." AND bet_fid <= ".$arrReqData['slot_range'][1];
        $strSQL .= " WHERE ".getBetTimeRange($arrReqData, $this->db);
        $strSQL .= $strWhereMem;

        if(!$confs['pbg_deny'] || !$confs['bpg_deny'] || !$confs['eos5_deny'] || !$confs['eos3_deny'] || !$confs['coin5_deny'] || !$confs['coin3_deny']){
            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_pball ';
            // $strSQL .= " WHERE bet_fid >= ".$arrReqData['hpb_range'][0]." AND bet_fid <= ".$arrReqData['hpb_range'][1];
            $strSQL .= " WHERE ".getBetTimeRange($arrReqData, $this->db);
            $strSQL .= $strWhereMem." )";
        }
        
        if(isEBalMode()){
            $tbName = "bet_ebal_st";
            $strWhereMem2= " AND bet_mb_fid IN (SELECT mb_fid from tbmember) ";
            
            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM '.$tbName;
            $strSQL .= " WHERE ".getStatRange('bet_start', 'bet_end', $arrReqData, $this->db);
            $strSQL .= $strWhereMem2." )";
        }

        if(isPBalMode()){
            $tbName = "bet_prbal_st";
            $strWhereMem2= " AND bet_mb_fid IN (SELECT mb_fid from tbmember) ";
            
            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM '.$tbName;
            $strSQL .= " WHERE ".getStatRange('bet_start', 'bet_end', $arrReqData, $this->db);
            $strSQL .= $strWhereMem2." )";
        }

        if(!$confs['evol_deny'] || !$confs['cas_deny']){
            $tbName = "bet_casino";
            $strWhereMem2= $strWhereMem;
            $strWhere2 = " bet_money <> bet_win_money ";
            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM '.$tbName;
            $strSQL .= " WHERE ".getBetTimeRange($arrReqData, $this->db);
            $strSQL .= " AND ".$strWhere2." AND company_amount = 0 ";  //sum without Tie
            $strSQL .= $strWhereMem2." )";
        }
        
        if(!$confs['hold_deny']){
            $strSQL .= 'UNION ALL (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_holdem ';
            $strSQL .= " WHERE ".getBetTimeRange($arrReqData, $this->db);
            $strSQL .= $strWhereMem." AND bet_state = 0 )";
        }
        $strSQL .= " ) AS bet_table ) ";
        //적립포인트
        $rwPoint = "rw_point";
        if($arrReqData['rw_blank']){
            $rwPoint = "rw_point-rw_blank";
        }

        $strSQL .= " UNION ALL ( SELECT SUM(result_1) AS result_1, SUM(result_2) AS result_2 FROM (";
        
        $strSQL .= " SELECT SUM(".$rwPoint.") AS result_1, ";
        $strSQL .= " SUM(CASE WHEN rw_mb_fid = ".$objEmp->mb_fid." THEN ".$rwPoint." ELSE 0 END) AS result_2 FROM ".$this->rewardTb;
        $strSQL .= " WHERE ".getStatRange('rw_start', 'rw_end', $arrReqData, $this->db);
        $strSQL .= " AND rw_mb_fid IN (SELECT mb_fid from tbmember) ";
        
        $strSQL .= " UNION ALL SELECT SUM(".$rwPoint.") AS result_1, ";
        $strSQL .= " SUM(CASE WHEN rw_mb_fid = ".$objEmp->mb_fid." THEN ".$rwPoint." ELSE 0 END) AS result_2 FROM ".$this->rewardPrTb;
        $strSQL .= " WHERE ".getStatRange('rw_start', 'rw_end', $arrReqData, $this->db);
        $strSQL .= " AND rw_mb_fid IN (SELECT mb_fid from tbmember) ";

        $strSQL .= " UNION ALL SELECT SUM(".$rwPoint.") AS result_1, ";
        $strSQL .= " SUM(CASE WHEN rw_mb_fid = ".$objEmp->mb_fid." THEN ".$rwPoint." ELSE 0 END) AS result_2 FROM ".$this->rewardMnTb;
        $strSQL .= " WHERE ".getStatRange('rw_start', 'rw_end', $arrReqData, $this->db);
        $strSQL .= " AND rw_mb_fid IN (SELECT mb_fid from tbmember) ) AS rewardMnTb";
        $strSQL .= " ) ";

        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog($strSQL);
        $arrResult = $this->db->query($strSQL)->getResult();
        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog("calculate END");

        return $arrResult;

    }

    public function calculateByGame($objEmp, $arrReqData){

        $strSQL = $this->calcCommonSql($objEmp, $arrReqData);
        //배팅금액
        $rewardTb = $this->rewardTb;
        $strSQL .= ' UNION ALL ( SELECT SUM(bet_money) AS result_1, SUM(bet_win_money) AS result_2 FROM ';
        if ($arrReqData['type'] == GAME_PBG_BALL || $arrReqData['type'] == GAME_DHP_BALL || $arrReqData['type'] == GAME_BOGLE_BALL ||
            $arrReqData['type'] == GAME_BOGLE_LADDER || $arrReqData['type'] == GAME_EOS5_BALL || $arrReqData['type'] == GAME_EOS3_BALL ||
            $arrReqData['type'] == GAME_COIN5_BALL || $arrReqData['type'] == GAME_COIN3_BALL || $arrReqData['type'] == GAME_SPKN_BALL) {
            $strSQL .= ' bet_pball ';
            $rewardTb = $this->rewardMnTb;

        } elseif ($arrReqData['type'] == GAME_AUTO_EVOL ) {
            $strSQL .= "bet_ebal_st";
        } elseif ($arrReqData['type'] == GAME_AUTO_PRAG ) {
            $strSQL .= "bet_prbal_st";
            $rewardTb = $this->rewardPrTb;
        } elseif ($arrReqData['type'] == GAME_CASINO_EVOL ) {
            $strSQL .= "bet_casino";
        } elseif ($arrReqData['type'] == GAME_SLOT_THEPLUS || $arrReqData['type'] == GAME_SLOT_GSPLAY || $arrReqData['type'] == GAME_SLOT_GOLD 
            || $arrReqData['type'] == GAME_SLOT_KGON || $arrReqData['type'] == GAME_SLOT_STAR || $arrReqData['type'] == GAME_SLOT_RAVE
            || $arrReqData['type'] == GAME_SLOT_TREEM || $arrReqData['type'] == GAME_SLOT_ALL ) {
            $strSQL .= ' bet_slot ';
        } else if ($arrReqData['type'] == GAME_HOLD_CMS ) {
            $strSQL .= ' bet_holdem ';
        } else {
            return null;
        }
        if ($arrReqData['type'] == GAME_AUTO_EVOL || $arrReqData['type'] == GAME_AUTO_PRAG){
            $strSQL .= " WHERE ".getStatRange('bet_start', 'bet_end', $arrReqData, $this->db);
        } else $strSQL .= " WHERE ".getBetTimeRange($arrReqData, $this->db);
        // else $strSQL .= " WHERE bet_fid >= ".$arrReqData['gm_range'][0]." AND bet_fid <= ".$arrReqData['gm_range'][1];
        if ($arrReqData['type'] == GAME_SLOT_THEPLUS || $arrReqData['type'] == GAME_SLOT_GSPLAY || $arrReqData['type'] == GAME_SLOT_GOLD || 
            $arrReqData['type'] == GAME_SLOT_KGON || $arrReqData['type'] == GAME_SLOT_STAR  || $arrReqData['type'] == GAME_SLOT_RAVE ||
            $arrReqData['type'] == GAME_SLOT_TREEM){
            $strSQL .= " AND bet_game_id = '".$arrReqData['type']."' ";
        } else if ($arrReqData['type'] == GAME_AUTO_EVOL || $arrReqData['type'] == GAME_AUTO_PRAG ) {
            
        } else if ($arrReqData['type'] == GAME_CASINO_EVOL ) {
            $strSQL .= " AND company_amount = 0 ";
            $strSQL .= " AND bet_money <> bet_win_money ";  //sum without Tie
        } else if($arrReqData['type'] == GAME_HOLD_CMS){
            $strSQL .= " AND bet_state = 0 ";
        } else if ($arrReqData['type'] == GAME_PBG_BALL || $arrReqData['type'] == GAME_DHP_BALL || $arrReqData['type'] == GAME_BOGLE_BALL ||
            $arrReqData['type'] == GAME_BOGLE_LADDER || $arrReqData['type'] == GAME_EOS5_BALL || $arrReqData['type'] == GAME_EOS3_BALL ||
            $arrReqData['type'] == GAME_COIN5_BALL || $arrReqData['type'] == GAME_COIN3_BALL || $arrReqData['type'] == GAME_SPKN_BALL) {
            $strSQL .= " AND bet_game = ".$arrReqData['type'];
        } 

        if($arrReqData['type'] == GAME_AUTO_EVOL || $arrReqData['type'] == GAME_AUTO_PRAG){
            $strSQL .= " AND bet_mb_fid IN (SELECT mb_fid from tbmember) ) ";
        } else
            $strSQL .= " AND bet_mb_uid IN (SELECT mb_uid from tbmember) ) ";
        //포인트
        $rwPoint = "rw_point";
        if($arrReqData['rw_blank']){
            $rwPoint = "rw_point-rw_blank";
        }

        $strSQL .= " UNION ALL ( SELECT SUM(".$rwPoint.") AS result_1, ";
        $strSQL .= " SUM(CASE WHEN rw_mb_fid = ".$objEmp->mb_fid." THEN ".$rwPoint." ELSE 0 END) AS result_2 FROM ".$rewardTb;
        // $strSQL .= " WHERE rw_fid >= ".$arrReqData['rw_range'][0]." AND rw_fid <= ".$arrReqData['rw_range'][1];
        $strSQL .= " WHERE ".getStatRange('rw_start', 'rw_end', $arrReqData, $this->db);
        $strSQL .= " AND rw_mb_fid IN (SELECT mb_fid from tbmember) ";
        if($arrReqData['type'] == GAME_SLOT_ALL){
            $gameId1 = GAME_SLOT_THEPLUS;
            if($_ENV['app.slot'] == APP_SLOT_KGON)
                $gameId1 = GAME_SLOT_KGON;
            else if($_ENV['app.slot'] == APP_SLOT_STAR)
                $gameId1 = GAME_SLOT_STAR;
            else if($_ENV['app.slot'] == APP_SLOT_RAVE)
                $gameId1 = GAME_SLOT_RAVE;
            else if($_ENV['app.slot'] == APP_SLOT_TREEM)
                $gameId1 = GAME_SLOT_TREEM;

            $gameId2 = GAME_SLOT_GSPLAY;
            if($_ENV['app.fslot'] == APP_FSLOT_GOLD)
                $gameId2 = GAME_SLOT_GOLD;
        
            $strSQL.=" AND rw_game IN ( '".$gameId1."', '".$gameId2."') ";
        }
        else if($arrReqData['type'] > 0)
            $strSQL.=" AND rw_game = '".$arrReqData['type']."' ";
        $strSQL.= " ) ";

        writeLog($strSQL);
        $arrResult = $this->db->query($strSQL)->getResult();
        // writeLog("calcPoint END");

        return $arrResult;
    }
    
    // 배팅금액 (하부포함)
    public function calcUserBet($arrReqData, $confs)
    {
        $strCond = " WHERE bet_mb_uid = '".$arrReqData['mb_uid']."' ";
        if (array_key_exists('start', $arrReqData) && strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0) {
            $strCond .= " AND ".getBetTimeRange($arrReqData, $this->db);
        }

        $strSQL = ' SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money ';
        $strSQL .= '  FROM  (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_slot';
        $strSQL .= $strCond;

        if(!$confs['pbg_deny'] || !$confs['bpg_deny'] || !$confs['eos5_deny'] || !$confs['eos3_deny'] || !$confs['coin5_deny'] || !$confs['coin3_deny']){
            $strSQL .= 'UNION ALL SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_pball ';
            $strSQL .= $strCond;
        }

        if(!$confs['hold_deny']){
            $strSQL .= 'UNION ALL SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM bet_holdem ';
            $strSQL .= $strCond." AND bet_state = 0 ";
        }

        if(isEBalMode()){
            $strCond2 = " WHERE bet_mb_uid = '".$arrReqData['mb_uid']."' ";
            if (array_key_exists('start', $arrReqData) && strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0) {
                $strCond2 .= " AND ".getStatRange('bet_start', 'bet_end', $arrReqData, $this->db);
            }
            
            $tbName = "bet_ebal_st";
            $strSQL .= 'UNION ALL SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM '.$tbName;
            $strSQL .= $strCond2;
        }

        if(isPBalMode()){
            $strCond2 = " WHERE bet_mb_uid = '".$arrReqData['mb_uid']."' ";
            if (array_key_exists('start', $arrReqData) && strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0) {
                $strCond2 .= " AND ".getStatRange('bet_start', 'bet_end', $arrReqData, $this->db);
            }
            
            $tbName = "bet_prbal_st";
            $strSQL .= 'UNION ALL SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM '.$tbName;
            $strSQL .= $strCond2;
        }

        if(!$confs['evol_deny'] || !$confs['cas_deny']){
            $tbName = "bet_casino";
            $strSQL .= 'UNION ALL SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money FROM '.$tbName;
            $strSQL .= $strCond." AND company_amount = 0 AND ";
            $strSQL .= " bet_money <> bet_win_money ";
        }

        $strSQL .= ' ) bet_all';

        $objResult = $this->db->query($strSQL)->getRow();

        $arrBetData['bet_money'] = 0;          // 배팅머니
        $arrBetData['bet_win_money'] = 0;      // 적중머니

         if (!is_null($objResult->bet_money)) {
             $arrBetData['bet_money'] += $objResult->bet_money;
         }
        if (!is_null($objResult->bet_win_money)) {
            $arrBetData['bet_win_money'] += $objResult->bet_win_money;
        }

        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog("calcUserBet=".$strSQL);

        return $arrBetData;
    }

    public function statistUserBet($arrReqData, $confs){
        $strCond = " WHERE bet_mb_uid = '".$arrReqData['mb_uid']."' ";
        if (array_key_exists('start', $arrReqData) && strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0) {
            $strCond .= " AND ".getBetTimeRange($arrReqData, $this->db);
        }

         $strSQL = " SELECT bet_money, bet_win_money, bet_count, name_kr AS bet_name, '".GAME_SLOT_THEPLUS."' As bet_kind From ";
         $strSQL.= " (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, COUNT(*) AS bet_count, bet_game_type  FROM bet_slot ";
		 $strSQL.= $strCond." group by bet_game_type) AS bet_slot_g JOIN slot_prd on slot_prd.code = bet_slot_g.bet_game_type ";
         
        if(!$confs['pbg_deny'] || !$confs['bpg_deny'] || !$confs['eos5_deny'] || !$confs['eos3_deny'] || !$confs['coin5_deny'] || !$confs['coin3_deny']){
            $strSQL.= " UNION ALL SELECT bet_money, bet_win_money, bet_count, '미니게임' AS bet_name, '".GAME_PBG_BALL."' As bet_kind  From ";
            $strSQL.= " (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, COUNT(*) AS bet_count FROM bet_pball  ";
            $strSQL.= $strCond." ) AS bet_pb_g ";
        }
            
        if(!$confs['hold_deny']){
            $strSQL.= " UNION ALL SELECT bet_money, bet_win_money, bet_count, '홀덤' AS bet_name, '".GAME_HOLD_CMS."' As bet_kind  From ";
            $strSQL.= " (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, COUNT(*) AS bet_count FROM bet_holdem  ";
            $strSQL.= $strCond." AND bet_state = 0 ) AS bet_hl_g ";
        }

        if(isEBalMode()){
            $strCond2 = " WHERE bet_mb_uid = '".$arrReqData['mb_uid']."' ";
            if (array_key_exists('start', $arrReqData) && strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0) {
                $strCond2 .= " AND ".getStatRange('bet_start', 'bet_end', $arrReqData, $this->db);
            }
            $tbName = "bet_ebal_st";
            // $strCond.= " AND company_amount = 0 AND ";
            // $strCond .= " point_amount <> ".BET_STATE_TIE;
            $strSQL.= " UNION All SELECT bet_money, bet_win_money, bet_count, bet_name, '".GAME_CASINO_EVOL."' As bet_kind From ";
            $strSQL.= " (SELECT bet_casino_g.*, name_ko AS bet_name from (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, SUM(bet_cnt) AS bet_count, 0 AS bet_game_id FROM ".$tbName;
            $strSQL.=  $strCond2.") AS bet_casino_g ";
            $strSQL.= " JOIN casino_prd on casino_prd.vendor_id = bet_casino_g.bet_game_id ) AS bet_casino_g ";
        }

        if(isPBalMode()){
            $strCond2 = " WHERE bet_mb_uid = '".$arrReqData['mb_uid']."' ";
            if (array_key_exists('start', $arrReqData) && strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0) {
                $strCond2 .= " AND ".getStatRange('bet_start', 'bet_end', $arrReqData, $this->db);
            }
            $tbName = "bet_prbal_st";
            // $strCond.= " AND company_amount = 0 AND ";
            // $strCond .= " point_amount <> ".BET_STATE_TIE;
            $strSQL.= " UNION All SELECT bet_money, bet_win_money, bet_count, bet_name, '".GAME_CASINO_EVOL."' As bet_kind From ";
            $strSQL.= " (SELECT bet_casino_g.*, name_ko AS bet_name from (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, SUM(bet_cnt) AS bet_count, 0 AS bet_game_id FROM ".$tbName;
            $strSQL.=  $strCond2.") AS bet_casino_g ";
            $strSQL.= " JOIN casino_prd on casino_prd.vendor_id = bet_casino_g.bet_game_id ) AS bet_casino_g ";
        }

        if(!$confs['evol_deny'] || !$confs['cas_deny']){
            $tbName = "bet_casino";

            $strCond.= " AND company_amount = 0 AND ";
            $strCond .= " bet_money <> bet_win_money ";

            $strSQL.= " UNION All SELECT bet_money, bet_win_money, bet_count, bet_name, '".GAME_CASINO_EVOL."' As bet_kind From ";
            $strSQL.= " (SELECT bet_casino_g.*, name_ko AS bet_name from (SELECT SUM(bet_money) AS bet_money, SUM(bet_win_money) AS bet_win_money, COUNT(*) AS bet_count, bet_game_id FROM ".$tbName;
            $strSQL.=  $strCond." group by bet_game_id) AS bet_casino_g ";
            $strSQL.= " JOIN casino_prd on casino_prd.vendor_id = bet_casino_g.bet_game_id ) AS bet_casino_g ";
        }

        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog("statistUserBet=".$strSQL);

        return $this->db->query($strSQL)->getResult();
    }

    public function updateLogin($member)
    {
        $this->builder()->set('mb_time_last', 'NOW()', false);
        $this->builder()->set('mb_ip_last', $member->mb_ip_last);
        $this->builder()->where('mb_fid', $member->mb_fid);

        return $this->builder()->update();
    }

    
    public function updateLiveMoney($member){
        $data = [
            'mb_live_money' => $member->mb_live_money,
        ];
        return $this->update($member->mb_fid, $data);
    }
    
    public function updateKgonMoney($member){
        $data = [
            'mb_kgon_money' => $member->mb_kgon_money,
        ];
        return $this->update($member->mb_fid, $data);
    }

    public function updateSlotMoney($member){
        $data = [
            'mb_slot_money' => $member->mb_slot_money,
        ];
        return $this->update($member->mb_fid, $data);
    }
    
    public function updateFslotMoney($member){
        $data = [
            'mb_fslot_money' => $member->mb_fslot_money,
        ];
        return $this->update($member->mb_fid, $data);
    }

    public function updateGslotMoney($member){
        $data = [
            'mb_gslot_money' => $member->mb_gslot_money,
        ];
        return $this->update($member->mb_fid, $data);
    }

    public function updateHslotMoney($member){
        $data = [
            'mb_hslot_money' => $member->mb_hslot_money,
        ];
        return $this->update($member->mb_fid, $data);
    }

    public function updateHoldMoney($member){
        $data = [
            'mb_hold_money' => $member->mb_hold_money,
        ];
        return $this->update($member->mb_fid, $data);
    }

    public function updateRaveMoney($member){
        $data = [
            'mb_rave_money' => $member->mb_rave_money,
        ];
        return $this->update($member->mb_fid, $data);
    }

    public function updateTreemMoney($member){
        $data = [
            'mb_treem_money' => $member->mb_treem_money,
        ];
        return $this->update($member->mb_fid, $data);
    }

    public function updateSigmaMoney($member){
        $data = [
            'mb_sigma_money' => $member->mb_sigma_money,
        ];
        return $this->update($member->mb_fid, $data);
    }

    public function getMemberByLevel($strLevel, $bLowLev = false, $mbFid = 0)
    {

        $where =" mb_state_active != '".PERMIT_DELETE."' ";

        if ($bLowLev) {
            $where .= 'AND mb_level <= '.$strLevel;
        } else {
            $where .= 'AND mb_level = '.$strLevel;
        }
        if($mbFid > 0)
            $where .= " AND mb_fid = '".$mbFid."' ";
        
        return $this->where($where)->findAll();
        
    }


    public function getMemberByEmpFid($nEmpFid, $nReqLevel, $nEmpLev = LEVEL_MIN, $bLowLev = false, $mbFid=0)
    {
        if ($nEmpLev >= LEVEL_ADMIN) {
            return $this->getMemberByLevel($nReqLevel, $bLowLev, $mbFid);
        } else {
            $fields = ['mb_fid', 'mb_uid', 'mb_level','mb_emp_fid', 'mb_emp_permit', 'mb_nickname', 'mb_phone', 'mb_money', 'mb_point', 
                'mb_grade', 'mb_color', 'mb_memo', 'mb_state_active', 'mb_state_delete', 'mb_state_test', 
                'mb_game_pb_ratio', 'mb_game_pb2_ratio', 'mb_game_cs_ratio', 'mb_game_sl_ratio', 'mb_game_hl_ratio', 
                'mb_game_pb_percent', 'mb_game_pb2_percent',  
                'mb_range_ev', 'mb_press_ev', 'mb_pressat_ev', 'mb_follow_ev', 'mb_follow_en', 
                'mb_live_money', 'mb_slot_money', 'mb_fslot_money', 'mb_kgon_money', 'mb_gslot_money', 'mb_hslot_money', 'mb_hold_money', 'mb_rave_money'
            ]; 

            $strTbColum = " ".implode(", ", $fields);
            $strTbRColum = " r.".implode(", r.", $fields);

            $strSQL = 'WITH RECURSIVE tbmember ('.$strTbColum.') AS';
            $strSQL .= ' ( SELECT '.$strTbColum.' FROM '.$this->table." WHERE mb_emp_fid = '".$nEmpFid."'";
            $strSQL .= ' UNION ALL SELECT '.$strTbRColum.' FROM '.$this->table.' r ';
            $strSQL .= ' INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )';
            $strSQL .= ' SELECT * FROM tbmember ';
            $strSQL .=" WHERE mb_state_active != '".PERMIT_DELETE."' ";
            if ($bLowLev) {
                $strSQL .= " AND mb_level < '".$nReqLevel."' ";
            } else {
                $strSQL .= " AND mb_level = '".$nReqLevel."' ";
            }
            if($mbFid > 0)
                $strSQL .= " AND mb_fid = '".$mbFid."' ";


            return $this->db->query($strSQL)->getResult();
        }
    }

    private function getChildsRatio($mbFid){
        $strSQL = "SELECT MAX(mb_game_pb_ratio) AS mb_game_pb_ratio, MAX(mb_game_pb2_ratio) AS mb_game_pb2_ratio, ";
        $strSQL.= "  MAX(mb_game_cs_ratio) AS mb_game_cs_ratio, MAX(mb_game_sl_ratio) AS mb_game_sl_ratio, ";
        $strSQL.= "  MAX(mb_game_hl_ratio) AS mb_game_hl_ratio ";
        $strSQL.= " FROM ".$this->table." WHERE mb_emp_fid = '".$mbFid."' AND mb_state_active != '".PERMIT_DELETE."' ";
        
        // writeLog($strSQL);
        $objResult = $this->db->query($strSQL)->getRow();
        return $objResult;
    }

    private function checkGameRatio($objEmployee, $arrRegData, &$strError)
    {
        // 배당율 체크
        if(!is_null($objEmployee)){
            if (array_key_exists('mb_game_pb_ratio', $arrRegData) && $objEmployee->mb_game_pb_ratio < $arrRegData['mb_game_pb_ratio']) {
                $strError = "미니게임 단폴 배당율이 추천인설정값 ".$objEmployee->mb_game_pb_ratio."보다 클수 없습니다.";
                return 4;
            } elseif (array_key_exists('mb_game_pb2_ratio', $arrRegData) && $objEmployee->mb_game_pb2_ratio < $arrRegData['mb_game_pb2_ratio']) {
                $strError = "미니게임 조합 배당율이 추천인설정값 ".$objEmployee->mb_game_pb2_ratio."보다 클수 없습니다.";
                return 4;
            } elseif (array_key_exists('mb_game_cs_ratio', $arrRegData) && $objEmployee->mb_game_cs_ratio < $arrRegData['mb_game_cs_ratio']) {
                $strError = "카지노 배당율이 추천인설정값 ".$objEmployee->mb_game_cs_ratio."보다 클수 없습니다.";
                return 4;
            } elseif (array_key_exists('mb_game_sl_ratio', $arrRegData) && $objEmployee->mb_game_sl_ratio < $arrRegData['mb_game_sl_ratio']) {
                $strError = "슬롯 배당율이 추천인설정값 ".$objEmployee->mb_game_sl_ratio."보다 클수 없습니다.";
                return 4;
            } elseif (array_key_exists('mb_game_hl_ratio', $arrRegData) && $objEmployee->mb_game_hl_ratio < $arrRegData['mb_game_hl_ratio']) {
                $strError = "홀덤 배당율이 추천인설정값 ".$objEmployee->mb_game_hl_ratio."보다 클수 없습니다.";
                return 4;
            }
        }
        if(array_key_exists('mb_fid', $arrRegData) && $arrRegData['mb_fid'] > 0 ){
            $chRatio = $this->getChildsRatio($arrRegData['mb_fid']);

            if (array_key_exists('mb_game_pb_ratio', $arrRegData) && $chRatio->mb_game_pb_ratio != null && $chRatio->mb_game_pb_ratio > $arrRegData['mb_game_pb_ratio']) {
                $strError = "미니게임 단폴 배당율이 하위설정값 ".$chRatio->mb_game_pb_ratio."보다 작을수 없습니다.";
                return 5;
            } elseif (array_key_exists('mb_game_pb2_ratio', $arrRegData) && $chRatio->mb_game_pb2_ratio != null && $chRatio->mb_game_pb2_ratio > $arrRegData['mb_game_pb2_ratio']) {
                $strError = "미니게임 조합 배당율이 하위설정값 ".$chRatio->mb_game_pb2_ratio."보다 작을수 없습니다.";
                return 5;
            } elseif (array_key_exists('mb_game_cs_ratio', $arrRegData) && $chRatio->mb_game_cs_ratio != null && $chRatio->mb_game_cs_ratio > $arrRegData['mb_game_cs_ratio']) {
                $strError = "카지노 배당율이 하위설정값 ".$chRatio->mb_game_cs_ratio."보다 작을수 없습니다.";
                return 5;
            } elseif (array_key_exists('mb_game_sl_ratio', $arrRegData) && $chRatio->mb_game_sl_ratio != null && $chRatio->mb_game_sl_ratio > $arrRegData['mb_game_sl_ratio']) {
                $strError = "슬롯 배당율이 하위설정값 ".$chRatio->mb_game_sl_ratio."보다 작을수 없습니다.";
                return 5;
            } elseif (array_key_exists('mb_game_hl_ratio', $arrRegData) && $chRatio->mb_game_hl_ratio != null && $chRatio->mb_game_hl_ratio > $arrRegData['mb_game_hl_ratio']) {
                $strError = "홀덤 배당율이 하위설정값 ".$chRatio->mb_game_hl_ratio."보다 작을수 없습니다.";
                return 5;
            }
        }

        return 1;
    }

    private function setZeroGameRatio(&$arrRegData)
    {
        if(array_key_exists('mb_game_pb_ratio', $arrRegData)){
            if (strlen($arrRegData['mb_game_pb_ratio']) < 1) {
                $arrRegData['mb_game_pb_ratio'] = 0;
            }
            if (floatval($arrRegData['mb_game_pb_ratio']) < 0) {
                $arrRegData['mb_game_pb_ratio'] = 0;
            }
        }
        
        if(array_key_exists('mb_game_pb2_ratio', $arrRegData)){
            if (strlen($arrRegData['mb_game_pb2_ratio']) < 1) {
                $arrRegData['mb_game_pb2_ratio'] = 0;
            }
            if (floatval($arrRegData['mb_game_pb2_ratio']) < 0) {
                $arrRegData['mb_game_pb2_ratio'] = 0;
            }
        }

        if(array_key_exists('mb_game_cs_ratio', $arrRegData)){
            if (strlen($arrRegData['mb_game_cs_ratio']) < 1) {
                $arrRegData['mb_game_cs_ratio'] = 0;
            }
            if (floatval($arrRegData['mb_game_cs_ratio']) < 0) {
                $arrRegData['mb_game_cs_ratio'] = 0;
            }
        }

        if(array_key_exists('mb_game_sl_ratio', $arrRegData)){
            if (strlen($arrRegData['mb_game_sl_ratio']) < 1) {
                $arrRegData['mb_game_sl_ratio'] = 0;
            }
            if (floatval($arrRegData['mb_game_sl_ratio']) < 0) {
                $arrRegData['mb_game_sl_ratio'] = 0;
            }
        }
        
        if(array_key_exists('mb_game_hl_ratio', $arrRegData)){
            if (strlen($arrRegData['mb_game_hl_ratio']) < 1) {
                $arrRegData['mb_game_hl_ratio'] = 0;
            }
            if (floatval($arrRegData['mb_game_hl_ratio']) < 0) {
                $arrRegData['mb_game_hl_ratio'] = 0;
            }
        }
    }

    private function setZeroGamePercent(&$arrData){

        if (array_key_exists('mb_game_pb_percent', $arrData) && strlen($arrData['mb_game_pb_percent']) < 1) {
            $arrData['mb_game_pb_percent'] = 0;
        }
        if (array_key_exists('mb_game_pb2_percent', $arrData) && strlen($arrData['mb_game_pb2_percent']) < 1) {
            $arrData['mb_game_pb2_percent'] = 0;
        }
    }

    public function register($arrRegData, &$strError)
    {
        // 결과 -1: query error 0:오류 1:성공 3:추천인 오류 4:상위 배당율오류 5:하위 배당율오류 6:추천인 오류
        $objEmployee = null;
        if ($arrRegData['mb_level'] == LEVEL_COMPANY) {
            $arrRegData['mb_emp_fid'] = 0;
            // $objUser = $this->getByNickname(trim($arrData['mb_nickname']));
            // if (!is_null($objUser)) {
            //     return 12;
            // }
        } elseif ($arrRegData['mb_emp_fid'] > 0) {
            // 추천인 체크
            $objEmployee = $this->getInfoByFid($arrRegData['mb_emp_fid']);
            if (is_null($objEmployee)) {
                return 3;
            }

            if ($arrRegData['mb_level'] < LEVEL_MIN){
                return 3;
            }
            
            if($objEmployee->mb_state_test == STATE_ACTIVE)
                $arrRegData['mb_state_test'] = $objEmployee->mb_state_test; //상위가 테스트유저이면 하위도 테스트
            
        } else {
            return 0;
        }
        $this->setZeroGameRatio($arrRegData);
        // 배당율 체크
        $ratioResult = $this->checkGameRatio($objEmployee, $arrRegData, $strError);
        if ($ratioResult != 1) {
            return $ratioResult;
        }

        $objUser = $this->getInfo(trim($arrRegData['mb_uid']));
        if (!is_null($objUser) && $objUser->mb_state_active == PERMIT_DELETE) {
            if(array_key_exists('mb_state_alarm', $arrRegData) && $arrRegData['mb_state_alarm'] == 1) {
                $this->delete($objUser->mb_fid);
            }   
            else return 2;
        }

        // 자료기지 등록
        $arrRegData['mb_uid'] = trim($arrRegData['mb_uid']);
        $arrRegData['mb_nickname'] = trim($arrRegData['mb_nickname']);
        $arrRegData['mb_bank_name'] = trim($arrRegData['mb_bank_name']);
        $arrRegData['mb_bank_own'] = trim($arrRegData['mb_bank_own']);
        $arrRegData['mb_bank_num'] = trim($arrRegData['mb_bank_num']);
        $arrRegData['mb_bank_pwd'] = trim($arrRegData['mb_bank_pwd']);
        $arrRegData['mb_time_join'] = date('Y-m-d H:i:s');
        
        if(!array_key_exists('mb_state_active', $arrRegData)){
            $arrRegData['mb_state_active'] = PERMIT_REQ;
        }
        if($_ENV['mem.auto_permit']){
            $arrRegData['mb_state_active'] = PERMIT_OK;
        }

        $arrRegData['mb_game_pb'] = STATE_ACTIVE;
        $arrRegData['mb_game_ps'] = STATE_ACTIVE;
        $arrRegData['mb_game_ks'] = STATE_ACTIVE;
        $arrRegData['mb_game_bb'] = STATE_ACTIVE;
        $arrRegData['mb_game_bs'] = STATE_ACTIVE;
        $arrRegData['mb_game_cs'] = STATE_ACTIVE;
        $arrRegData['mb_game_sl'] = STATE_ACTIVE;
        $arrRegData['mb_game_eo'] = STATE_ACTIVE;
        $arrRegData['mb_game_co'] = STATE_ACTIVE;
        $arrRegData['mb_game_hl'] = STATE_ACTIVE;

        $objUser = $this->getByBankName($arrRegData['mb_bank_name'], $arrRegData['mb_bank_own']);
        if(!is_null($objUser)){
            $data['mb_state_delete'] = STATE_ACTIVE;
        }

        $result = $this->insert($arrRegData);
        if (!$result) {
            $strError = $this->errors();

            return -1;
        }

        return 1;
    }

    // function registerInfo($info, &$strError) {

    public function modifyMember($objMember, $arrData, &$strError, &$query)
    {
        // 결과 0:오류 1:성공 2:아이디중복 3:추천인 오류 4:파워볼 배당율오류 5:파워사다리 배당율오류 6:키노사다리 배당율 오류, 11:따라가기 오류 12 중복닉네임

        $objEmployee = null;
        $diffLv = 0;
        $fids = [$objMember->mb_fid];
        if ($arrData['mb_emp_fid'] == 0) {
            $diffLv = LEVEL_COMPANY - $objMember->mb_level;
        } elseif ($arrData['mb_emp_fid'] > 0) {
            // 추천인 체크
            $objEmployee = $this->getInfoByFid($arrData['mb_emp_fid']);
            if (is_null($objEmployee) || $objEmployee->mb_level < LEVEL_MIN) {
                return 3;
            }
            $diffLv = $objEmployee->mb_level - 1 - $objMember->mb_level;
        } else {
            return 0;
        }

        if(array_key_exists('mb_follow_ev', $arrData) && strlen($arrData['mb_follow_ev']) > 2){
            if(substr($arrData['mb_follow_ev'], 0, 2) == "1:"){
                $arrInfo = explode(":", $arrData['mb_follow_ev']);
                $objFollow = null;
                if(count($arrInfo) == 3)
                    $objFollow = $this->getInfo($arrInfo[1]);
                if(is_null($objFollow) || $objFollow->mb_state_active == PERMIT_DELETE)
                    return 11;
            }
        }

        if($objMember->mb_emp_fid != $arrData['mb_emp_fid']){  //추천인이 다른 경우

            if($objEmployee != null && $objEmployee->mb_fid == $objMember->mb_fid)
                    return 6;

            $minLv = LEVEL_COMPANY;
            $arrMem = $this->getMemberByEmpFid($objMember->mb_fid, $objMember->mb_level,  $objMember->mb_level, true);
            foreach($arrMem as $chMem) {
                if($objEmployee != null && $objEmployee->mb_fid == $chMem->mb_fid)
                    return 6;
                if($minLv > intval($chMem->mb_level)){
                    $minLv = $chMem->mb_level;
                }
                array_push($fids, $chMem->mb_fid);
            }

            if($diffLv < 0 && array_key_exists('app.level_limit', $_ENV) && intval($_ENV['app.level_limit']) > 0){
                // writeLog("MinLevel = ".$minLv);
                $minLv += $diffLv;
                // writeLog("MinLevel = ".$minLv);
                if($minLv < LEVEL_MAX - intval($_ENV['app.level_limit']) ){
                    
                    $strError = LEVEL_MAX - intval($_ENV['app.level_limit']) + $objMember->mb_level - $minLv; 
                    return 7;
                }
            }
        }

        if(array_key_exists('mb_state_test', $arrData)){ //테스트 유저
            if($objEmployee != null && $objEmployee->mb_state_test == STATE_ACTIVE){
                $arrData['mb_state_test'] = STATE_ACTIVE;
            }
        
            if($arrData['mb_state_test'] == STATE_ACTIVE){
                $this->updateChildMember($objMember->mb_fid, $arrData);
            }
        }

        $this->setZeroGameRatio($arrData);
        $this->setZeroGamePercent($arrData);

        if($objMember->mb_emp_fid == $arrData['mb_emp_fid']){
            $resultRatio = $this->checkGameRatio($objEmployee, $arrData, $strError);
            if ($resultRatio != 1) {
                return $resultRatio;
            }
        }
        
        $arrData['mb_bank_name'] = trim($arrData['mb_bank_name']);
        $arrData['mb_bank_own'] = trim($arrData['mb_bank_own']);
        $arrData['mb_bank_num'] = trim($arrData['mb_bank_num']);
        $arrData['mb_bank_pwd'] = trim($arrData['mb_bank_pwd']);

        if(array_key_exists('mb_money', $arrData))
            unset($arrData['mb_money']);

        if(array_key_exists('mb_point', $arrData))
            unset($arrData['mb_point']);

        $bResult = $this->update($arrData['mb_fid'], $arrData);
        
        if ($bResult) {
            
            $query = $this->db->getLastQuery();
            if($objMember->mb_emp_fid != $arrData['mb_emp_fid']){
                $query .= "; ". $this->modifyLevel($fids, $diffLv);
            }
            return 1;
        }
        $strError = $this->errors();
        // writeLog($strError);
        return -1;
    }
    
    public function updateChildMember($empFid, $arrData)
    {
        $fields = ['mb_fid', 'mb_uid', 'mb_level', 'mb_emp_fid'];
        $strTbColum = " ".implode(", ", $fields);
        $strTbRColum = " r.".implode(", r.", $fields);

        $strSQL = ' UPDATE '.$this->table." SET ";
        if(array_key_exists('mb_state_test', $arrData))
            $strSQL .= ' mb_state_test = '.$arrData['mb_state_test'];
        else return true;

        $strSQL .= ' WHERE mb_fid IN (';         
            $strSQL .= ' WITH RECURSIVE tbmember ('.$strTbColum.') AS';
            $strSQL .= ' ( SELECT '.$strTbColum.' FROM '.$this->table." WHERE mb_fid = '".$empFid."'";
            $strSQL .= ' UNION ALL SELECT '.$strTbRColum.' FROM '.$this->table.' r ';
            $strSQL .= ' INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )';
            $strSQL .= ' SELECT mb_fid FROM tbmember ) ';
        
        // writeLog($strSQL);

        $objUpdate = $this->db->query($strSQL);
        $affectedRows = $objUpdate->connID->affected_rows;
        return $affectedRows > 0;
    }

    public function modifyLevel($fids, $diffLv)
    {
        // if($diffLv == 0)
        //     return true;
        
        $strSql = "UPDATE ".$this->table." SET " ;
        if($diffLv >= 0)
            $strSql.=  " mb_level = mb_level + ".abs($diffLv);
        else
            $strSql.=  " mb_level = mb_level - ".abs($diffLv);
        $strSql.=  ", mb_game_pb_ratio = 0, mb_game_pb2_ratio=0, ";
        $strSql.=  " mb_game_cs_ratio = 0, mb_game_sl_ratio=0, ";
        $strSql.=  " mb_game_hl_ratio = 0 ";

        $strSql.= " WHERE mb_fid IN (".implode(", ", $fids).")";
        // writeLog($strSql);

        $bResult = $this->db->query($strSql);

        return $strSql;
    }

    public function modifyMemberRatio($objMember, $arrData, &$strError, &$query)
    {
        // 결과 0:오류 1:성공 2:아이디중복 3:추천인 오류 4:파워볼 배당율오류 5:파워사다리 배당율오류 6:키노사다리 배당율 오류

        if ($objMember->mb_level < LEVEL_COMPANY) {
            // 추천인 체크
            $objEmployee = $this->getInfoByFid($objMember->mb_emp_fid);
            if (is_null($objEmployee)) {
                return 0;
            }
            
            $this->setZeroGameRatio($arrData);
            $this->setZeroGamePercent($arrData);

            $resultRatio = $this->checkGameRatio($objEmployee, $arrData, $strError);
            if ($resultRatio != 1) {
                return $resultRatio;
            }
        } else {
            return 0;
        }

        // $this->builder()->set('mb_color', $arrData['mb_color']);
        if(array_key_exists('mb_state_delete', $arrData)){
            $this->builder()->set('mb_state_delete', $arrData['mb_state_delete']);
        }

        $this->builderSetGameRatioAndPercent($arrData);

        $this->builder()->where('mb_fid', $arrData['mb_fid']);
        $bResult = $this->builder()->update();
        $query = $this->db->getLastQuery();

        return 1;
    }

    private function builderSetGameRatioAndPercent($arrRegData)
    {
        if(array_key_exists('mb_game_pb_ratio', $arrRegData))
            $this->builder()->set('mb_game_pb_ratio', $arrRegData['mb_game_pb_ratio']);
        if(array_key_exists('mb_game_pb2_ratio', $arrRegData))
            $this->builder()->set('mb_game_pb2_ratio', $arrRegData['mb_game_pb2_ratio']);
        
        if(array_key_exists('mb_game_cs_ratio', $arrRegData))
            $this->builder()->set('mb_game_cs_ratio', $arrRegData['mb_game_cs_ratio']);
        if(array_key_exists('mb_game_sl_ratio', $arrRegData))
            $this->builder()->set('mb_game_sl_ratio', $arrRegData['mb_game_sl_ratio']);
        if(array_key_exists('mb_game_hl_ratio', $arrRegData))
            $this->builder()->set('mb_game_hl_ratio', $arrRegData['mb_game_hl_ratio']);

        if(array_key_exists('mb_game_pb_percent', $arrRegData))
            $this->builder()->set('mb_game_pb_percent', $arrRegData['mb_game_pb_percent']);
        if(array_key_exists('mb_game_pb2_percent', $arrRegData))
            $this->builder()->set('mb_game_pb2_percent', $arrRegData['mb_game_pb2_percent']);
    }

    public function updateMemberByFid($arrData, &$query)
    {
        $arrData['mb_fid'] = intval($arrData['mb_fid']);

        if (array_key_exists('mb_state_active', $arrData)) {
            $this->builder()->set('mb_state_active', $arrData['mb_state_active']);
        } elseif (array_key_exists('mb_game_pb', $arrData)) {
            $this->builder()->set('mb_game_pb', $arrData['mb_game_pb']);
        } elseif (array_key_exists('mb_game_ps', $arrData)) {
            $this->builder()->set('mb_game_ps', $arrData['mb_game_ps']);
        } elseif (array_key_exists('mb_game_ks', $arrData)) {
            $this->builder()->set('mb_game_ks', $arrData['mb_game_ks']);
        } elseif (array_key_exists('mb_game_cs', $arrData)) {
            $this->builder()->set('mb_game_cs', $arrData['mb_game_cs']);
        } elseif (array_key_exists('mb_game_sl', $arrData)) {
            $this->builder()->set('mb_game_sl', $arrData['mb_game_sl']);
        } elseif (array_key_exists('mb_game_bb', $arrData)) {
            $this->builder()->set('mb_game_bb', $arrData['mb_game_bb']);
        } elseif (array_key_exists('mb_game_bs', $arrData)) {
            $this->builder()->set('mb_game_bs', $arrData['mb_game_bs']);
        } elseif (array_key_exists('mb_game_eo', $arrData)) {
            $this->builder()->set('mb_game_eo', $arrData['mb_game_eo']);
        } elseif (array_key_exists('mb_game_co', $arrData)) {
            $this->builder()->set('mb_game_co', $arrData['mb_game_co']);
        } elseif (array_key_exists('mb_game_hl', $arrData)) {
            $this->builder()->set('mb_game_hl', $arrData['mb_game_hl']);
        } elseif (array_key_exists('mb_blank_count', $arrData)) {
            $this->builder()->set('mb_blank_count', $arrData['mb_blank_count']);
        } elseif (array_key_exists('mb_follow_ev', $arrData)) {
            $this->builder()->set('mb_follow_ev', $arrData['mb_follow_ev']);
        } else {
            return false;
        }

        $this->builder()->where('mb_fid', $arrData['mb_fid']);
        $bResult = $this->builder()->update();
        $query = $this->db->getLastQuery();
        
        return $bResult;
    }

    public function updateMemberByFids($arrData, &$query)
    {
        if (array_key_exists('mb_state_active', $arrData)) {
            $this->builder()->set('mb_state_active', $arrData['mb_state_active']);
        } else if (array_key_exists('mb_press_ev', $arrData)) {
            $this->builder()->set('mb_press_ev', $arrData['mb_press_ev']);
        } else if (array_key_exists('mb_pressat_ev', $arrData)) {
            $this->builder()->set('mb_pressat_ev', $arrData['mb_pressat_ev']);
        } else if (array_key_exists('mb_range_ev', $arrData)) {
            $this->builder()->set('mb_range_ev', $arrData['mb_range_ev']);
        } else if (array_key_exists('mb_state_view', $arrData)) {
            $this->builder()->set('mb_state_view', $arrData['mb_state_view']);
            if (array_key_exists('mb_state_test', $arrData))
                $this->builder()->where('mb_state_test', $arrData['mb_state_test']);
        } else if (array_key_exists('mb_follow_en', $arrData)) {
            $this->builder()->set('mb_follow_en', $arrData['mb_follow_en']);
        } else return false;

        if(count($arrData['mb_fids']) > 0)
            $this->builder()->whereIn('mb_fid', $arrData['mb_fids']);
        else $this->builder()->where('mb_level <', LEVEL_ADMIN);

        $bResult = $this->builder()->update();
        $query = $this->db->getLastQuery();
        
        return $bResult;
    }

    public function getEmpInfo($objMember, $arrReqData)
    {
        if ($objMember->mb_level < LEVEL_ADMIN) 
            return [];
            
        // 대기중인 회원수
        $strSQL = " SELECT  COUNT(*) AS result_1, 0 AS result_2 FROM ".$this->table;
            $strSQL .= " WHERE mb_level < '".LEVEL_ADMIN."' AND mb_state_active = '".PERMIT_REQ."'";
        //충전대기
        $strSQL .=" UNION ALL SELECT SUM(CASE WHEN charge_action_state = ".STATE_ACTIVE." THEN 1 ELSE 0 END) AS result_1,  SUM(CASE WHEN charge_action_state = ".STATE_WAIT." THEN 1 ELSE 0 END) AS result_2";
            $strSQL .=" FROM ".$this->chargeTb."  WHERE charge_state_delete = '0' ";
            // $strSQL .=" FROM ".$this->chargeTb." JOIN member ON member_charge.charge_mb_uid = member.mb_uid  WHERE charge_state_delete = '0' ";
        //환전대기
        $strSQL .=" UNION ALL SELECT SUM(CASE WHEN exchange_action_state = ".STATE_ACTIVE." THEN 1 ELSE 0 END) AS result_1,  SUM(CASE WHEN exchange_action_state = ".STATE_WAIT." THEN 1 ELSE 0 END) AS result_2";
            $strSQL .=" FROM ".$this->exchangeTb." WHERE exchange_state_delete = '0' ";
        //문의대기
        $strSQL .=" UNION ALL SELECT  COUNT(*) AS result_1, 0 AS result_2 FROM ".$this->noticeTb;
        $strSQL .=" WHERE notice_state_delete = '".STATE_DISABLE."' AND notice_type = '".NOTICE_CUSTOMER."' AND notice_read_count = '0' ";
        //관리자 보유금
        $strSQL .= " UNION ALL SELECT SUM(".allMoneySql().") AS result_1, SUM(mb_point) AS result_2 FROM ".$this->table;
        $strSQL .= " WHERE mb_level < ".LEVEL_ADMIN." AND mb_state_active <> ".PERMIT_DELETE." AND mb_state_test = ".STATE_DISABLE;
        //충전금액
        $strSQL .=" UNION ALL SELECT SUM(charge_money) AS result_1,  0 AS result_2 FROM ".$this->chargeTb;
        $strSQL.=" WHERE (charge_action_state = '".STATE_VERIFY."' OR charge_action_state = '".STATE_HOT."') ";
            $strSQL.=" AND charge_time_require >= ".$this->db->escape($arrReqData['start']." 00:00:00")." AND charge_time_require <= ".$this->db->escape($arrReqData['end']." 23:59:59") ; 
            $strSQL .= " AND charge_mb_uid NOT IN (SELECT mb_uid FROM ".$this->table." WHERE mb_level >= ".LEVEL_ADMIN." OR mb_state_test = ".STATE_ACTIVE." ) ";
        //환전금액
        $strSQL .=" UNION ALL SELECT SUM(exchange_money) AS result_1,  0 AS result_2 FROM ".$this->exchangeTb;
        $strSQL.=" WHERE (exchange_action_state = '".STATE_VERIFY."' OR exchange_action_state = '".STATE_HOT."') ";
        $strSQL.=" AND exchange_time_require >= ".$this->db->escape($arrReqData['start']." 00:00:00")." AND exchange_time_require <= ".$this->db->escape($arrReqData['end']." 23:59:59") ; 
        $strSQL .= " AND exchange_mb_uid NOT IN (SELECT mb_uid FROM ".$this->table." WHERE mb_level >= ".LEVEL_ADMIN." OR mb_state_test = ".STATE_ACTIVE.") ";
        //지급 회수
        $strSQL .= " UNION ALL SELECT SUM( money_give) AS result_1, ";
        $strSQL .= " SUM( money_withdraw) AS result_2 FROM ".$this->historyTb;
        $strSQL .= " WHERE money_mb_fid NOT IN (SELECT mb_fid FROM ".$this->table." WHERE mb_level >= ".LEVEL_ADMIN." OR mb_state_test = ".STATE_ACTIVE.") ";
        $strSQL.=" AND money_start >= ".$this->db->escape($arrReqData['start']." 00:00:00")." AND money_end <= ".$this->db->escape($arrReqData['end']." 23:59:59") ; 

        // if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
        //     writeLog($strSQL);
        $arrResult = $this->db->query($strSQL)->getResult();
        
        // if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
        //     writeLog("getEmpInfo End");

        return $arrResult;
    }

    // 관리자 보유금
    public function calcAdminMoney()
    {
        $strSQL = 'SELECT SUM('.allMoneySql().') AS emp_money, SUM(mb_point) AS emp_point FROM '.$this->table;
        $strSQL .= ' WHERE mb_level < '.LEVEL_ADMIN;
        $strSQL .=" AND mb_state_active != '".PERMIT_DELETE."' ";

        $objResult = $this->db->query($strSQL)->getRow();

        return $objResult;
    }

    // 게임별 머니
    public function calcGameEgg($iGame=0)
    {
        if($iGame == GAME_CASINO_EVOL){
            $strSQL = 'SELECT SUM(mb_live_money) AS mb_game_money FROM '.$this->table;
            $strSQL .= ' WHERE mb_live_id > 0 ';
        } else if($iGame == GAME_CASINO_KGON || $iGame == GAME_SLOT_KGON){
            $strSQL = 'SELECT SUM(mb_kgon_money) AS mb_game_money FROM '.$this->table;
            $strSQL .= ' WHERE mb_kgon_id > 0 ';
        } else if($iGame == GAME_SLOT_THEPLUS){
            $strSQL = 'SELECT SUM(mb_slot_money) AS mb_game_money FROM '.$this->table;
            $strSQL .= " WHERE LENGTH(mb_slot_uid) > 0 ";
        } else if($iGame == GAME_SLOT_GSPLAY){
            $strSQL = 'SELECT SUM(mb_fslot_money) AS mb_game_money FROM '.$this->table;
            $strSQL .= ' WHERE mb_fslot_id > 0';
        } else if($iGame == GAME_SLOT_GOLD){
            $strSQL = 'SELECT SUM(mb_gslot_money) AS mb_game_money FROM '.$this->table;
            $strSQL .= ' WHERE LENGTH(mb_gslot_uid) > 0';
        } else if($iGame == GAME_CASINO_STAR || $iGame == GAME_SLOT_STAR){
            $strSQL = 'SELECT SUM(mb_hslot_money) AS mb_game_money FROM '.$this->table;
            $strSQL .= ' WHERE LENGTH(mb_hslot_token) > 0';
        } else if($iGame == GAME_HOLD_CMS){
            $strSQL = 'SELECT SUM(mb_hold_money) AS mb_game_money FROM '.$this->table;
            $strSQL .= ' WHERE LENGTH(mb_hold_uid) > 0 ';
        } else if($iGame == GAME_CASINO_RAVE || $iGame == GAME_SLOT_RAVE){
            $strSQL = 'SELECT SUM(mb_rave_money) AS mb_game_money FROM '.$this->table;
            $strSQL .= ' WHERE mb_rave_id > 0 ';
        } else if($iGame == GAME_CASINO_TREEM || $iGame == GAME_SLOT_TREEM){
            $strSQL = 'SELECT SUM(mb_treem_money) AS mb_game_money FROM '.$this->table;
            $strSQL .= ' WHERE LENGTH(mb_treem_uid) > 0 ';
        } else if($iGame == GAME_CASINO_SIGMA || $iGame == GAME_SLOT_SIGMA){
            $strSQL = 'SELECT SUM(mb_sigma_money) AS mb_game_money FROM '.$this->table;
            $strSQL .= ' WHERE LENGTH(mb_sigma_uid) > 0 ';
        } else if($iGame == 0) {
            $strSQL = 'SELECT SUM(CASE WHEN mb_live_id > 0 THEN mb_live_money ELSE 0 END ) AS mb_live_money, ';
            $strSQL.= 'SUM(CASE WHEN mb_kgon_id > 0 THEN mb_kgon_money ELSE 0 END ) AS mb_kgon_money, ';
            $strSQL.= 'SUM(CASE WHEN LENGTH(mb_slot_uid) > 0 THEN mb_slot_money ELSE 0 END ) AS mb_slot_money, ';
            $strSQL.= 'SUM(CASE WHEN mb_fslot_id > 0 THEN mb_fslot_money ELSE 0 END ) AS mb_fslot_money, ';
            $strSQL.= 'SUM(CASE WHEN LENGTH(mb_gslot_uid) > 0 THEN mb_gslot_money ELSE 0 END ) AS mb_gslot_money, ';
            $strSQL.= 'SUM(CASE WHEN LENGTH(mb_hslot_token) > 0 THEN mb_hslot_money ELSE 0 END ) AS mb_hslot_money, ';
            $strSQL.= 'SUM(CASE WHEN LENGTH(mb_hold_uid) > 0 THEN mb_hold_money ELSE 0 END ) AS mb_hold_money, ';
            $strSQL.= 'SUM(CASE WHEN mb_rave_id > 0 THEN mb_rave_money ELSE 0 END ) AS mb_rave_money, ';
            $strSQL.= 'SUM(CASE WHEN LENGTH(mb_treem_uid) > 0 THEN mb_treem_money ELSE 0 END ) AS mb_treem_money, ';
            $strSQL.= 'SUM(CASE WHEN LENGTH(mb_sigma_uid) > 0 THEN mb_sigma_money ELSE 0 END ) AS mb_sigma_money ';
            $strSQL.= ' FROM '.$this->table;

            return $this->db->query($strSQL)->getRow();
        } else return null;

        $objResult = $this->db->query($strSQL)->getRow();

        return $objResult->mb_game_money;
    }
    
    public function searchCountByLevel($arrReqData, $iEmpFid)
    {
        $sqlBuilder = $this->builder()->selectCount('*', 'count');
        $sqlBuilder = $sqlBuilder->where('mb_level <', LEVEL_ADMIN);
        if ($arrReqData['mb_state'] != PERMIT_DELETE)
            $sqlBuilder = $sqlBuilder->where('mb_state_active !=', PERMIT_DELETE);
        if ($iEmpFid != 0)
            $sqlBuilder->where('mb_emp_fid', $iEmpFid);

        if ($arrReqData['mb_grade'] > 0){
            $sqlBuilder->where('mb_grade', $arrReqData['mb_grade']);
        }
        if ($arrReqData['mb_state'] >= 0){
            $sqlBuilder->where('mb_state_active', $arrReqData['mb_state']);
        }
        if (strlen($arrReqData['mb_uid']) > 0){
            $where = " mb_uid LIKE '%".$this->db->escapeLikeString($arrReqData['mb_uid'])."%' OR mb_fid = ".$this->db->escape($arrReqData['mb_uid']);

            $sqlBuilder->where($where);
        }
        return $sqlBuilder->get()->getRow();
    }

    public function searchCountByEmpFid($objUser, $arrReqData, $iEmpFid)
    {
        if($objUser->mb_level >= LEVEL_ADMIN)
        {
            return $this->searchCountByLevel($arrReqData, $iEmpFid);
        } else {
            $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_grade, mb_state_active ";
            $strTbRColum = " r.mb_fid, r.mb_uid, r.mb_level, r.mb_emp_fid, r.mb_grade, r.mb_state_active ";

            $strSQL = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSQL .= " ( SELECT ".$strTbColum." FROM ".$this->table." WHERE mb_emp_fid = '".$objUser->mb_fid."'";
            $strSQL .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->table." r ";
            $strSQL .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";
            $strSQL .= " SELECT COUNT(*) as count FROM tbmember WHERE ";
            $strSQL .= " mb_level < '".$objUser->mb_level."' ";
            $strSQL .=" AND mb_state_active != '".PERMIT_DELETE."' ";

            if ($iEmpFid != 0)
                $strSQL.=" AND mb_emp_fid = '".$iEmpFid."' ";

            if($arrReqData['mb_grade'] > 0){            
                $strSQL.=" AND mb_grade = ".$this->db->escape($arrReqData['mb_grade']);
            }
            if ($arrReqData['mb_state'] >= 0){
                $strSQL.=" AND mb_state_active = ".$this->db->escape($arrReqData['mb_state']);
            }
            if(strlen($arrReqData['mb_uid']) > 0){            
                $strSQL.=" AND mb_uid LIKE '%".$this->db->escapeLikeString($arrReqData['mb_uid'])."%'";
                
            }
            return $this -> db -> query($strSQL)->getRow();

        }
    }

    public function searchMemberByLevel($arrReqData, $iEmpFid)
    {
        $strTbColum = " ".implode(", ", $this->fields);
        $strTbColum.= ", block_ip, block_state ";

        $tbBlock = "block_list";

        $where = "";
        if ($iEmpFid != 0){
            $where .= " AND mb_emp_fid = '".$iEmpFid."'";
        }
        if (strlen($arrReqData['mb_uid']) > 0) {
            $where .= " AND ( mb_uid LIKE '%".$this->db->escapeLikeString($arrReqData['mb_uid'])."%' OR mb_fid = ".$this->db->escape($arrReqData['mb_uid'])." ) ";
        } else if(array_key_exists('mb_nickname', $arrReqData) && strlen($arrReqData['mb_nickname']) > 0){
            $where .= " AND mb_nickname LIKE '%".$this->db->escapeLikeString($arrReqData['mb_nickname'])."%'";
        } else if(array_key_exists('mb_bank_own', $arrReqData) && strlen($arrReqData['mb_bank_own']) > 0){
            $where .= " AND mb_bank_own LIKE '%".$this->db->escapeLikeString($arrReqData['mb_bank_own'])."%'";
        } else if(array_key_exists('mb_fid', $arrReqData) && strlen($arrReqData['mb_fid']) > 0){
            $where .= " AND mb_fid LIKE '%".$this->db->escapeLikeString($arrReqData['mb_fid'])."%'";
        }
        if ($arrReqData['mb_grade'] != 0){
            $where .= " AND mb_grade = ".$this->db->escape($arrReqData['mb_grade']);
        }
        if ($arrReqData['mb_state'] >= 0){
            $where .= " AND mb_state_active = ".$this->db->escape($arrReqData['mb_state']);
        }


        $strSQL = "SELECT ".$strTbColum." FROM ".$this->table;
        $strSQL.= ' LEFT JOIN '.$tbBlock.' ON '.$this->table.'.mb_ip_last = '.$tbBlock.'.block_ip ';
        $strSQL.= " WHERE mb_level < '".LEVEL_ADMIN."' ";
        $strSQL .=" AND mb_state_active != '".PERMIT_DELETE."' ";
        $strSQL.= $where;

        $strSQL .= " ORDER BY (CASE WHEN mb_state_active = ".PERMIT_REQ." OR mb_state_active = ".PERMIT_WAIT." THEN 0 ELSE 1 END) ";
        $strSQL .= " , mb_uid ASC ";
        
        $nStartRow = ($arrReqData['page'] - 1) * $arrReqData['count'];
        $strSQL .= ' LIMIT '.$nStartRow.', '.$arrReqData['count'];
        return $this->db->query($strSQL)->getResult();
    }

    public function searchMemberByEmpFid($objUser, $arrReqData, $iEmpFid)
    {
        if($objUser->mb_level >= LEVEL_ADMIN)
        {
            return $this->searchMemberByLevel($arrReqData, $iEmpFid);
        } else {

            $strTbColum = " ".implode(", ", $this->fields);
            $strTbRColum = " r.".implode(", r.", $this->fields);

            $strSQL = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSQL .= " ( SELECT ".$strTbColum." FROM ".$this->table." WHERE mb_emp_fid = '".$objUser->mb_fid."'";
            $strSQL .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->table." r ";
            $strSQL .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";
            $strSQL .= " SELECT * FROM tbmember ";
            $strSQL .= " WHERE mb_level < '".$objUser->mb_level."' ";
            $strSQL .=" AND mb_state_active != '".PERMIT_DELETE."' ";

            if ($iEmpFid != 0){
                $strSQL .= " AND mb_emp_fid = '".$iEmpFid."'";
            }
            if (strlen($arrReqData['mb_uid']) > 0) {
                $strSQL .= " AND mb_uid LIKE '%".$this->db->escapeLikeString($arrReqData['mb_uid'])."%'";
            } else if(array_key_exists('mb_nickname', $arrReqData) && strlen($arrReqData['mb_nickname']) > 0){
                $strSQL .= " AND mb_nickname LIKE '%".$this->db->escapeLikeString($arrReqData['mb_nickname'])."%'";
            } else if(array_key_exists('mb_fid', $arrReqData) && strlen($arrReqData['mb_fid']) > 0){
                $strSQL .= " AND mb_fid LIKE '%".$this->db->escapeLikeString($arrReqData['mb_fid'])."%'";
            }
            if ($arrReqData['mb_grade'] != 0){
                $strSQL .= " AND mb_grade = ".$this->db->escape($arrReqData['mb_grade']);
            }
            if ($arrReqData['mb_state'] >= 0){
                $strSQL .= " AND mb_state_active = ".$this->db->escape($arrReqData['mb_state']);
            }

            $strSQL .= " ORDER BY (CASE WHEN mb_state_active = ".PERMIT_REQ." OR mb_state_active = ".PERMIT_WAIT." THEN 0 ELSE 1 END) ";
            $strSQL .= " , mb_uid ASC ";

            $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
            $strSQL .= ' LIMIT '.$nStartRow.', '.$arrReqData['count'];
            
            // writeLog($strSQL);
            return $this -> db -> query($strSQL)->getResult();
          
        }
    }

    public function searchMemberTree($objUser, $arrReqData, $mbFid)
    {
        $level = $objUser->mb_level;
        $userFid = $objUser->mb_fid;
        if($objUser->mb_level >= LEVEL_ADMIN)
        {
            $level = LEVEL_ADMIN;
            $userFid = 0;
        } 

        $strTbColum = " ".implode(", ", $this->fields);
        $strTbRColum = " r.".implode(", r.", $this->fields);

        $strSQL = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
        $strSQL .= " ( SELECT ".$strTbColum." FROM ".$this->table." WHERE ";
        if($mbFid > 0){
            $strSQL .= " mb_fid = '". $mbFid ."'";
        } else{
            $strSQL .= " mb_emp_fid = '". $userFid ."'";
        }

        $strSQL .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->table." r ";
        $strSQL .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";
        $strSQL .= " SELECT * FROM tbmember ";
        $strSQL .= " WHERE mb_level < '".$level."' ";
        $strSQL .=" AND mb_state_active != '".PERMIT_DELETE."' ";

        $strSQL .= " ORDER BY mb_level DESC, mb_fid ASC ";

        // writeLog($strSQL);
        
        return $this -> db -> query($strSQL)->getResult();
          
    }
    
    public function searchMemberClass($objUser, $arrReqData, $mbFid, $confs)
    {
        $level = $objUser->mb_level;
        $userFid = $objUser->mb_fid;
        if($objUser->mb_level >= LEVEL_ADMIN)
        {
            $level = LEVEL_ADMIN;
            $userFid = 0;
        } 

        $fields = $this->getFields;
        if(!in_array("mb_pwd", $fields)){
            array_push($fields, "mb_pwd");
            array_push($fields, "mb_memo");
        }

        $strTbColum = " ".implode(", ", $fields);
        $strTbRColum = " r.".implode(", r.", $fields);

        
        $strSQL = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
        $strSQL .= " ( SELECT ".$strTbColum." FROM ".$this->table." WHERE ";
        if($mbFid > 0){
            $strSQL .= " mb_fid = '". $mbFid ."'";
        } else{
            $strSQL .= " mb_emp_fid = '". $userFid ."'";
        }

        $strSQL .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->table." r ";
        $strSQL .= " INNER JOIN tbmember ON r.mb_emp_fid = tbmember.mb_fid )";

        $strTbColum = ' mb_fid, mb_uid, mb_pwd, mb_level, mb_emp_fid, mb_nickname, mb_phone, ';
        $strTbColum.= ' mb_bank_name, mb_bank_own, mb_bank_num, mb_bank_pwd, mb_time_join, mb_time_last, ';
        $strTbColum.= ' ('.allMoneySql().') as mb_money, ';
        $strTbColum.= ' ('.allEggSql().') as mb_egg, ';
        $strTbColum.= ' mb_point, mb_grade, mb_color, mb_memo, mb_state_active, mb_state_delete, mb_state_test, ' ;
        $strTbColum .= ' mb_game_pb, mb_game_ps, mb_game_ks, mb_game_bb, mb_game_bs, mb_game_cs, mb_game_sl, mb_game_eo, mb_game_co, mb_game_hl, ';
        $strTbColum .= ' mb_game_pb_ratio, mb_game_pb2_ratio, mb_game_cs_ratio, ';
        $strTbColum .= ' mb_game_sl_ratio, mb_game_hl_ratio, ';
        $strTbColum .= ' mb_game_pb_percent, mb_game_pb2_percent, ';
        $strTbColum .= ' mb_blank_count ';
        $betSum = " (IFNULL(bet_sl.bet_sl_m, 0)";
        $winSum = " (IFNULL(bet_sl.bet_sl_w, 0)";

        if(!$confs['pbg_deny'] || !$confs['bpg_deny'] || !$confs['eos5_deny'] || !$confs['eos3_deny'] || !$confs['coin5_deny'] || !$confs['coin3_deny']){
            $betSum .= "+IFNULL(bet_pb.bet_pb_m, 0)";
            $winSum .= "+IFNULL(bet_pb.bet_pb_w, 0)";
        }
        if(!$confs['hold_deny']){
            $betSum .= "+IFNULL(bet_hl.bet_hl_m, 0)";
            $winSum .= "+IFNULL(bet_hl.bet_hl_w, 0)";
        }
        if(isEBalMode()){
            $betSum .= "+IFNULL(bet_ev.bet_ev_m, 0)";
            $winSum .= "+IFNULL(bet_ev.bet_ev_w, 0)";
        }
        if(isPBalMode()){
            $betSum .= "+IFNULL(bet_prg.bet_prg_m, 0)";
            $winSum .= "+IFNULL(bet_prg.bet_prg_w, 0)";
        }
        if(!$confs['evol_deny'] || !$confs['cas_deny']){
            $betSum .= "+IFNULL(bet_cs.bet_cs_m, 0)";
            $winSum .= "+IFNULL(bet_cs.bet_cs_w, 0)";
        }
        $betSum .= ") AS bet_sum";
        $winSum .= ") AS win_sum";
        $strTbColum .= ", ".$betSum.", ".$winSum.", ";
        $strTbColum.= " rw_point, chg_point ";

        $tbMember = "tbmember";
        $strSQL.= "SELECT ".$strTbColum." FROM ".$tbMember;
        $strSQL.= " LEFT JOIN ( select bet_mb_uid, sum(bet_money) AS bet_sl_m, sum(bet_win_money) AS bet_sl_w from bet_slot group by bet_mb_uid ) bet_sl ON bet_sl.bet_mb_uid = ".$tbMember.".mb_uid";

        if(!$confs['pbg_deny'] || !$confs['bpg_deny'] || !$confs['eos5_deny'] || !$confs['eos3_deny'] || !$confs['coin5_deny'] || !$confs['coin3_deny']){
            $strSQL.= " LEFT JOIN ( select bet_mb_uid, sum(bet_money) AS bet_pb_m, sum(bet_win_money) AS bet_pb_w from bet_pball group by bet_mb_uid ) bet_pb ON bet_pb.bet_mb_uid = ".$tbMember.".mb_uid";
        }
        if(!$confs['hold_deny']){
            $strSQL.= " LEFT JOIN ( select bet_mb_fid, sum(bet_money) AS bet_hl_m, sum(bet_win_money) AS bet_hl_w from bet_holdem group by bet_mb_fid ) bet_hl ON bet_hl.bet_mb_fid = ".$tbMember.".mb_fid";
        }
        if(isEBalMode()){
            $tbName = "bet_ebal_st";
            $strSQL.= " LEFT JOIN ( select bet_mb_uid, sum(bet_money) AS bet_ev_m, sum(bet_win_money) AS bet_ev_w from ".$tbName;
            $strSQL.= " group by bet_mb_uid ) bet_ev ON bet_ev.bet_mb_uid = ".$tbMember.".mb_uid";
        }
        if(isPBalMode()){
            $tbName = "bet_prbal_st";
            $strSQL.= " LEFT JOIN ( select bet_mb_uid, sum(bet_money) AS bet_prg_m, sum(bet_win_money) AS bet_prg_w from ".$tbName;
            $strSQL.= " group by bet_mb_uid ) bet_prg ON bet_prg.bet_mb_uid = ".$tbMember.".mb_uid";
        }
        if(!$confs['evol_deny'] || !$confs['cas_deny']){
            $tbName = "bet_casino";

            $strSQL.= " LEFT JOIN ( select bet_mb_uid, sum(bet_money) AS bet_cs_m, sum(bet_win_money) AS bet_cs_w from ".$tbName;
            $strSQL.= " WHERE company_amount = 0 AND ";
            $strSQL.= " bet_money <> bet_win_money ";
            $strSQL.= " group by bet_mb_uid ) bet_cs ON bet_cs.bet_mb_uid = ".$tbMember.".mb_uid";
        }
	    $strSQL.= " LEFT JOIN ( select rw_mb_fid, sum(rw_point) AS rw_point from ".$this->rewardTb." group by rw_mb_fid ) sum_reward ON sum_reward.rw_mb_fid = ".$tbMember.".mb_fid";
	    $strSQL.= " LEFT JOIN ( select money_mb_fid, sum(money_amount) AS chg_point from money_history where money_change_type = ".POINTCHANGE_EXCHANGE." group by money_mb_fid ) chg_point ON chg_point.money_mb_fid = ".$tbMember.".mb_fid";

        $strSQL.= " WHERE mb_level < '".$level."' ";
        $strSQL .=" AND mb_state_active != '".PERMIT_DELETE."' ";

        $strSQL .= " ORDER BY mb_level DESC, mb_fid ASC ";

        return $this -> db -> query($strSQL)->getResult();
          
    }
    
    public function getEmpMemberByFid($fid, $order = "DESC")
    {
        $strTbColum = " ".implode(", ", $this->getFields);
        $strTbRColum = " r.".implode(", r.", $this->getFields);

        $strSQL = 'WITH RECURSIVE tbmember ('.$strTbColum.') AS';
        $strSQL .= ' ( SELECT '.$strTbColum.' FROM '.$this->table." WHERE mb_fid = '".$fid."'";
        $strSQL .= ' UNION ALL SELECT '.$strTbRColum.' FROM '.$this->table.' r ';
        $strSQL .= ' INNER JOIN tbmember ON r.mb_fid = tbmember.mb_emp_fid )';
        $strSQL .= ' SELECT * FROM tbmember ';
        
        $strSQL .=  " ORDER BY mb_level ".$order;
        return $this->db->query($strSQL)->getResult();
    }

    public function isPermitMember($objMember, $iGame = 0){

        if(is_null($objMember))
            return false;

        if($objMember->mb_level >= LEVEL_ADMIN)
            return true;

        $arrMember = $this->getEmpMemberByFid($objMember->mb_fid);
        if(count($arrMember) < 1)
            return false;

        if($arrMember[0]->mb_level != LEVEL_COMPANY)
            return false;

        foreach($arrMember as $member){
            if(getMemberState($member, $iGame) === false)
                return false;
        }
        
        return true;
    }


    public function searchUserByLevel($arrReqData, $iEmpFid, $confs)
    {
        $strTbColum = ' mb_fid, mb_uid, mb_pwd, mb_level, mb_emp_fid, mb_nickname, mb_phone, ';
        $strTbColum.= ' mb_bank_name, mb_bank_own, mb_bank_num, mb_bank_pwd, mb_time_join, mb_time_last, ';
        $strTbColum.= ' ('.allMoneySql().') as mb_money, ';
        $strTbColum.= ' ('.allEggSql().') as mb_egg, ';
        $strTbColum.= ' mb_point, mb_grade, mb_color, mb_memo, mb_state_active, mb_state_delete, mb_state_test, ' ;
        $strTbColum .= ' mb_game_pb, mb_game_ps, mb_game_ks, mb_game_bb, mb_game_bs, mb_game_cs, mb_game_sl, mb_game_eo, mb_game_co, mb_game_hl, ';
        $strTbColum .= ' mb_game_pb_ratio, mb_game_pb2_ratio, mb_game_cs_ratio, ';
        $strTbColum .= ' mb_game_sl_ratio, mb_game_hl_ratio, ';
        $strTbColum .= ' mb_game_pb_percent, mb_game_pb2_percent, mb_blank_count';

        if ($arrReqData['mb_grade'] >= 0){
            $betSum = " (IFNULL(bet_sl.bet_sl_m, 0)";
            $winSum = " (IFNULL(bet_sl.bet_sl_w, 0)";

            if(!$confs['pbg_deny'] || !$confs['bpg_deny'] || !$confs['eos5_deny'] || !$confs['eos3_deny'] || !$confs['coin5_deny'] || !$confs['coin3_deny']){
                $betSum .= "+IFNULL(bet_pb.bet_pb_m, 0)";
                $winSum .= "+IFNULL(bet_pb.bet_pb_w, 0)";
            }
            if(!$confs['hold_deny']){
                $betSum .= "+IFNULL(bet_hl.bet_hl_m, 0)";
                $winSum .= "+IFNULL(bet_hl.bet_hl_w, 0)";
            }
            if(isEBalMode()){
                $betSum .= "+IFNULL(bet_ev.bet_ev_m, 0)";
                $winSum .= "+IFNULL(bet_ev.bet_ev_w, 0)";
            }
            if(isPBalMode()){
                $betSum .= "+IFNULL(bet_prg.bet_prg_m, 0)";
                $winSum .= "+IFNULL(bet_prg.bet_prg_w, 0)";
            }
            if(!$confs['evol_deny'] || !$confs['cas_deny']){
                $betSum .= "+IFNULL(bet_cs.bet_cs_m, 0)";
                $winSum .= "+IFNULL(bet_cs.bet_cs_w, 0)";
            }
            $betSum .= ") AS bet_sum";
            $winSum .= ") AS win_sum";
            $strTbColum .= ", ".$betSum.", ".$winSum.", ";
            $strTbColum.= " rw_point, chg_point ";
        }
        
        $where = "";
        if ($iEmpFid != 0){
            $where .= " AND mb_emp_fid = '".$iEmpFid."'";
        }
        if (strlen($arrReqData['mb_uid']) > 0) {
            $where .= " AND ( mb_uid LIKE '%".$arrReqData['mb_uid']."%' OR mb_fid = '".$arrReqData['mb_uid']."') ";
        } else if(array_key_exists('mb_nickname', $arrReqData) && strlen($arrReqData['mb_nickname']) > 0){
            $where .= " AND mb_nickname LIKE '%".$this->db->escapeLikeString($arrReqData['mb_nickname'])."%'";
        } else if(array_key_exists('mb_bank_own', $arrReqData) && strlen($arrReqData['mb_bank_own']) > 0){
            $where .= " AND mb_bank_own LIKE '%".$this->db->escapeLikeString($arrReqData['mb_bank_own'])."%'";
        } else if(array_key_exists('mb_fid', $arrReqData) && strlen($arrReqData['mb_fid']) > 0){
            $where .= " AND mb_fid LIKE '%".$this->db->escapeLikeString($arrReqData['mb_fid'])."%'";
        }
        if ($arrReqData['mb_grade'] > 0){
            $where .= " AND mb_grade = '".$arrReqData['mb_grade']."'";
        }
        if ($arrReqData['mb_state'] >= 0){
            $where .= " AND mb_state_active = '".$arrReqData['mb_state']."' ";
        }


        $strSQL = "SELECT ".$strTbColum." FROM ".$this->table;
        if ($arrReqData['mb_grade'] >= 0){

            $strSQL.= " LEFT JOIN ( select bet_mb_uid, sum(bet_money) AS bet_sl_m, sum(bet_win_money) AS bet_sl_w from bet_slot group by bet_mb_uid ) bet_sl ON bet_sl.bet_mb_uid = member.mb_uid";

            if(!$confs['pbg_deny'] || !$confs['bpg_deny'] || !$confs['eos5_deny'] || !$confs['eos3_deny'] || !$confs['coin5_deny'] || !$confs['coin3_deny']){
                $strSQL.= " LEFT JOIN ( select bet_mb_uid, sum(bet_money) AS bet_pb_m, sum(bet_win_money) AS bet_pb_w from bet_pball group by bet_mb_uid ) bet_pb ON bet_pb.bet_mb_uid = member.mb_uid";
            }
            if(!$confs['hold_deny']){
                $strSQL.= " LEFT JOIN ( select bet_mb_fid, sum(bet_money) AS bet_hl_m, sum(bet_win_money) AS bet_hl_w from bet_holdem group by bet_mb_fid ) bet_hl ON bet_hl.bet_mb_fid = member.mb_fid";
            }
            if(isEBalMode()){
                $tbName = "bet_ebal_st";
                $strSQL.= " LEFT JOIN ( select bet_mb_uid, sum(bet_money) AS bet_ev_m, sum(bet_win_money) AS bet_ev_w from ".$tbName;
                $strSQL.= " group by bet_mb_uid ) bet_ev ON bet_ev.bet_mb_uid = member.mb_uid";
            }
            if(isPBalMode()){
                $tbName = "bet_prbal_st";
                $strSQL.= " LEFT JOIN ( select bet_mb_uid, sum(bet_money) AS bet_prg_m, sum(bet_win_money) AS bet_prg_w from ".$tbName;
                $strSQL.= " group by bet_mb_uid ) bet_prg ON bet_prg.bet_mb_uid = member.mb_uid";
            }
            if(!$confs['evol_deny'] || !$confs['cas_deny']){
                $tbName = "bet_casino";
                $strSQL.= " LEFT JOIN ( select bet_mb_uid, sum(bet_money) AS bet_cs_m, sum(bet_win_money) AS bet_cs_w from ".$tbName;
                $strSQL.= " WHERE company_amount = 0 AND ";
                $strSQL.= " bet_money <> bet_win_money ";
                $strSQL.= " group by bet_mb_uid ) bet_cs ON bet_cs.bet_mb_uid = member.mb_uid";
            }
            $strSQL.= " LEFT JOIN ( select rw_mb_fid, sum(rw_point) AS rw_point from ".$this->rewardTb." group by rw_mb_fid ) sum_reward ON sum_reward.rw_mb_fid = member.mb_fid";
            $strSQL.= " LEFT JOIN ( select money_mb_fid, sum(money_amount) AS chg_point from money_history where money_change_type = ".POINTCHANGE_EXCHANGE." group by money_mb_fid ) chg_point ON chg_point.money_mb_fid = member.mb_fid";
        }
        $strSQL.= " WHERE mb_level < '".LEVEL_ADMIN."' ";
        if ($arrReqData['mb_state'] != PERMIT_DELETE)
            $strSQL .=" AND mb_state_active != '".PERMIT_DELETE."' ";
        $strSQL.= $where;

        $strSQL .= " ORDER BY (CASE WHEN mb_state_active = ".PERMIT_REQ." OR mb_state_active = ".PERMIT_WAIT." THEN 0 ELSE 1 END) ";
        if (strlen($arrReqData['order']) > 0 && strlen($arrReqData['dir']) > 0) {
            $strSQL .= " , ".$arrReqData['order']." ".$arrReqData['dir']." ";
        }
        else $strSQL .= " , mb_uid ASC ";
        
        $nStartRow = ($arrReqData['page'] - 1) * $arrReqData['count'];
        $strSQL .= ' LIMIT '.$nStartRow.', '.$arrReqData['count'];
        // writeLog($strSQL);

        return $this->db->query($strSQL)->getResult();
    }

    public function searchFollowers($followUid="")
    {
        $strTbColum = " mb_fid, mb_uid, mb_level, mb_emp_fid, mb_nickname, mb_follow_ev ";
        
        $search = "";
        if(strlen($followUid) > 0)
            $search = $followUid.":";

        $strSQL = " SELECT ".$strTbColum." FROM ".$this->table." WHERE ";
        $strSQL.=" mb_state_active != '".PERMIT_DELETE."' ";
        $strSQL.=" AND mb_follow_en = '".STATE_ACTIVE."' ";
        $strSQL.=" AND mb_follow_ev LIKE '1:".$search."%'";
            
        return $this->db->query($strSQL)->getResult();
    }

    public function setAutoStop($date){

        $strSQL = "UPDATE ".$this->table." SET mb_state_active = ".PERMIT_CANCEL." WHERE mb_uid IN ( ";
        $strSQL.= " SELECT mb_uid FROM member WHERE mb_state_active = ".PERMIT_OK." AND mb_level < ".LEVEL_ADMIN." AND ";
        $strSQL.= " mb_uid NOT IN (SELECT charge_mb_uid FROM member_charge WHERE charge_time_require > '".$date."' AND (charge_action_state = ".STATE_VERIFY." OR charge_action_state = ".STATE_HOT.") GROUP BY charge_mb_uid ";
        $strSQL.= " UNION ALL SELECT exchange_mb_uid FROM member_exchange WHERE exchange_time_require > '".$date."' AND (exchange_action_state = ".STATE_VERIFY." OR exchange_action_state = ".STATE_HOT.") GROUP BY exchange_mb_uid ";
        $strSQL.= " UNION ALL SELECT bet_mb_uid FROM bet_ebal_st WHERE bet_start > '".$date."' GROUP BY bet_mb_fid ";
        $strSQL.= " UNION ALL SELECT bet_mb_uid FROM bet_casino WHERE bet_time > '".$date."' GROUP BY bet_mb_uid ";
        $strSQL.= " UNION ALL SELECT bet_mb_uid FROM bet_slot WHERE bet_time > '".$date."' GROUP BY bet_mb_uid ";
        $strSQL.= " UNION ALL SELECT bet_mb_uid FROM bet_pball WHERE bet_time > '".$date."' GROUP BY bet_mb_uid) )";

        $objUpdate = $this->db->query($strSQL);
        return $objUpdate->connID->affected_rows;

    }

    public function updateTreemUsers($users){

        if(count($users))
        $strSQL = "UPDATE ".$this->table." SET mb_treem_money = CASE mb_treem_uid ";
        $ids = [];
        foreach($users as $user){
            $strSQL.= " WHEN '".$user['username']."' THEN ".$user['balance'];
            array_push($ids, $user['username']); 
        }
        $whereIn = " '".implode("', '", $ids)."'";

        $strSQL.= " END WHERE mb_treem_uid IN (".$whereIn.")";
        
        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog($strSQL);

        try {
            $objUpdate = $this->db->query($strSQL);

            return $objUpdate->connID->affected_rows;
        } catch (\Exception $e) {  
            return false;
        }
    }
}
