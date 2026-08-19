<?php 
namespace App\Models;

use CodeIgniter\Model;

class Bet_Model extends Model {

    protected $table      = 'bet_pball';
    protected $primaryKey = 'bet_fid';

    protected $returnType = 'object'; 
    protected $allowedFields = ['bet_game', 'bet_state', 'bet_emp_fid', 'bet_mb_uid', 'bet_round_fid', 'bet_round_no', 
    'bet_time', 'bet_mode', 'bet_target', 'bet_ratio', 'bet_money', 'user_before_money', 'user_view_state', 
    'bet_fol_fid', 'bet_fol_uid' ];
    private $mMemberTable = 'member';

    public function gets($count){
        
        return $this->orderBy('bet_fid', 'DESC')
                    ->findAll($count, 0);
    }

    public function followBet($fid){
        $where = "bet_fol_fid = '".$fid."' ";
        return $this->where($where)
                    ->findAll(); 
    }
    
    public function register($arrBetData, $objMember){
        try {             
            $data = [  
                'bet_game' => $arrBetData['game'],
                'bet_state' => STATE_ACTIVE,
                'bet_emp_fid' => $objMember->mb_emp_fid,
                'bet_mb_uid' => $objMember->mb_uid,
                'bet_round_fid' => $arrBetData['roundid'],
                'bet_round_no' => $arrBetData['roundno'],
                'bet_time' => date("Y-m-d H:i:s"),    
                'bet_mode' => $arrBetData['mode'],
                'bet_target' => $arrBetData['target'],
                'bet_ratio' => $arrBetData['ratio'],
                'bet_money' => $arrBetData['amount'],
                'user_before_money' => $objMember->mb_money,
            ];
            if(array_key_exists('fol_fid', $arrBetData)) {
                $data['bet_fol_fid'] = $arrBetData['fol_fid'];
                $data['bet_fol_uid'] = $arrBetData['fol_uid'];
            }

            if($this->insert($data))
                return $this->insertID();
            else return 0;
        } catch (\Exception $e) {  
            return 0;
        }
        return 0;
    }

    function getBetStatist($objUser, $arrRoundData){
        
        if(is_null($objUser) || is_null($arrRoundData))
            return null;

        //3구멍베팅 회수
        $strSql = " SELECT bet_fid, bet_mode FROM ".$this->table;
        $strSql.= " WHERE bet_time > ".$this->db->escape($arrRoundData['round_date']);
        $strSql.= " AND bet_game = ".$this->db->escape($arrRoundData['game']);
        $strSql.= " AND bet_mb_uid = '".$objUser->mb_uid."' AND bet_round_no = ".$this->db->escape($arrRoundData['round_no']);
        $strSql.= " AND bet_state = '".STATE_ACTIVE."' AND bet_mode >= '31' AND bet_mode <= '38' ";
        $strSql.= " GROUP BY bet_mode ";

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();

        return $result;
    }

    public function searchCount($reqData){

        $where = " WHERE bet_mb_uid = ".$this->db->escape($reqData['user_id']);
        $where.= " AND bet_game = ".$this->db->escape($reqData['game']);
        $where.= " AND user_view_state = '0' ";
        if(array_key_exists('date', $reqData) ){
            $where.= "AND bet_time >= ".$this->db->escape($reqData['date']." 00:00:00");
            $where.= "AND bet_time <= ".$this->db->escape($reqData['date']." 23:59:59");
        } else {
            $where.= "AND bet_time >= '".date("Y-m-d H:i:s", strtotime("-1 day", time()))."' ";
        }
        
        $strSql = "SELECT count('bet_fid') as count FROM ".$this->table;
        $strSql .= $where;

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result->count; 

    }

    public function searchList($reqData){
        $getFields = ['bet_fid', 'bet_state', 'bet_emp_fid', 'bet_mb_uid', 'bet_round_fid', 'bet_round_no',
            'bet_time', 'bet_mode', 'bet_target', 'bet_ratio', 'bet_money', 'bet_result', 'bet_win_money'];

        $strTbColum = " ".implode(", ", $getFields);

        $where = " WHERE bet_mb_uid = ".$this->db->escape($reqData['user_id']);
        $where.= " AND user_view_state = '0' ";
        $where.= " AND bet_game = ".$this->db->escape($reqData['game']);
        if(array_key_exists('date', $reqData) ){
            $where.= "AND bet_time >= ".$this->db->escape($reqData['date']." 00:00:00");
            $where.= "AND bet_time <= ".$this->db->escape($reqData['date']." 23:59:59");
        } else {
            $where.= "AND bet_time >= '".date("Y-m-d H:i:s", strtotime("-1 day", time()))."' ";
        }
        $strSql = " SELECT ".$strTbColum." FROM ".$this->table;
        $strSql .= $where;

        $page = $reqData['page'];
        $count = $reqData['count'];
        if($page < 1)
            return NULL;
        if($count < 1)
            return NULL;
        if($count > 100)
            $count = 100;
        
        $nStartRow = ($page-1) * $count ;

        $strSql.=" ORDER BY bet_fid DESC LIMIT ".$nStartRow.", ".$count;

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        return $result;
    }


    public function getBetSumByMode($gameId, $arrRoundInfo, $objConf)
    {
        $fidPct = 'mb_game_pb_percent';
        $cntMode = 4;
        switch($gameId){
            case GAME_PBG_BALL:   
            case GAME_SPKN_BALL:  
            case GAME_DHP_BALL: 
            case GAME_BOGLE_BALL:  
            case GAME_EOS5_BALL:    
            case GAME_EOS3_BALL:   
            case GAME_COIN5_BALL:   
            case GAME_COIN3_BALL:  $cntMode = 4; break;
            case GAME_BOGLE_LADDER:$cntMode = 3; break;
            default: break;
        } 

        $arrSumData = [];
        $arrSum = [];
        $sqlSelect = ' SELECT SUM(bet_money_sum) AS bet_money_allsum FROM ( ';
        
        for ($i = 0; $i < $cntMode; $i++ ) {
            $iMode = $i + 1;
            if(is_null($objConf))
                $strSql = $sqlSelect;
            else $strSql = ' SELECT SUM(bet_money_sum * '.$fidPct.' DIV 100) AS bet_money_allsum FROM ( ';
            
            $strSql .= ' SELECT bet_mb_uid, bet_mode, bet_target, bet_ratio, SUM(bet_money) AS bet_money_sum, '.$fidPct.' FROM '.$this->table;
            $strSql .= ' JOIN '.$this->mMemberTable.' ON '.$this->mMemberTable.'.mb_uid = '.$this->table.'.bet_mb_uid ';
            $strSql .= " WHERE  bet_game=".$this->db->escape($arrRoundInfo['game']);
            $strSql .= " AND  bet_round_no=".$this->db->escape($arrRoundInfo['round_no'])." AND bet_state = '1' ";
            $strSql .= " AND bet_time > ".$this->db->escape($arrRoundInfo['round_start'])." AND bet_time < ".$this->db->escape($arrRoundInfo['round_end']);
            $strSql .= " AND bet_mode='".$iMode."' AND bet_target='P' GROUP BY bet_mb_uid ";
            $strSql .= ' ) tb_sum ';
            $objResult = $this->db->query($strSql)->getRow();

            // 유저별 베팅결과 합
            $nSum = 0;
            if (!is_null($objResult->bet_money_allsum)) {
                $nSum = $objResult->bet_money_allsum;
            }
            // 게임별 누르기율 계산
            if(!is_null($objConf))
                $nSum = $nSum * $objConf->game_percent_1 / 100;
            $arrSum[0] = (int) $nSum;

            if(is_null($objConf))
                $strSql = $sqlSelect;
            else $strSql = ' SELECT SUM(bet_money_sum * '.$fidPct.' DIV 100) AS bet_money_allsum FROM ( ';
            $strSql .= ' SELECT bet_mb_uid, bet_mode, bet_target, bet_ratio, SUM(bet_money) AS bet_money_sum, '.$fidPct.' FROM '.$this->table;
            $strSql .= ' JOIN '.$this->mMemberTable.' ON '.$this->mMemberTable.'.mb_uid = '.$this->table.'.bet_mb_uid ';
            $strSql .= " WHERE bet_round_no=".$this->db->escape($arrRoundInfo['round_no'])." AND bet_state = '1' ";
            $strSql .= " AND bet_time > ".$this->db->escape($arrRoundInfo['round_start'])." AND bet_time < ".$this->db->escape($arrRoundInfo['round_end']);
            $strSql .= " AND bet_mode='".$iMode."' AND bet_target='B' GROUP BY bet_mb_uid ";
            $strSql .= ' ) tb_sum ';
            $objResult = $this->db->query($strSql)->getRow();

            // 유저별 베팅결과 합
            $nSum = 0;
            if (!is_null($objResult->bet_money_allsum)) {
                $nSum = $objResult->bet_money_allsum;
            }
            // 게임별 누르기율 계산
            if(!is_null($objConf))
                $nSum = $nSum * $objConf->game_percent_1 / 100;
            $arrSum[1] = (int) $nSum;

            $arrSumData[$i] = $arrSum;
        }

        return $arrSumData;
    }


}
