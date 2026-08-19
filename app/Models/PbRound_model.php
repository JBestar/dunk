<?php

namespace App\Models;

use CodeIgniter\Model;

class PbRound_Model extends Model
{
    protected $table = 'round_pball';
    protected $returnType = 'object'; 
    protected $allowedFields = [
        'round_game',
        'round_date',
        'round_num',
        'round_time',
        'round_state',
        'round_result_1',
        'round_result_2',
        'round_result_3',
        'round_result_4',
        'round_result_5',
        'round_result_6',
        'round_power',
        'round_normal',
    ];
    protected $primaryKey = 'round_fid';

    
    public function gets($nCount)
    {
        $strSql = 'SELECT * FROM '.$this->table.' ORDER BY round_time DESC LIMIT 0, '.$nCount;
        $query = $this->db->query($strSql);
        $result = $query->getResult();
        

        return $result;
    }

    public function get($nRoundFid)
    {
        return $this->where(['round_fid' => $nRoundFid])->first();
    }

    public function getByDate($game, $strDate, $nRoundNo)
    {
        if (strlen($strDate) < 1 || $nRoundNo < 1) {
            return null;
        }

        return $this->where(['round_game' => $game, 'round_num' => $nRoundNo, 'round_date' => $strDate])->first();
    }

    public function register($arrReqData)
    {
        // 2:이미 등록된 회차 4:회차번호, 일회차오류

        
        $objRound = $this->getByDate($arrReqData['round_date'], $arrReqData['round_num']);
        if (!is_null($objRound)) {
            return 2;
        }

        if($arrReqData['game'] == GAME_PBG_BALL){
            $objRound = $this->get($arrReqData['round_fid']);
            if(!is_null($objRound))
               return 2;

            $arrRound = $this->gets(1);
            if(count($arrRound) > 0){
                if($arrRound[0]->round_fid < $arrReqData['round_fid'])
                    return 4;
            }

            $this->builder()->set('round_fid', $arrReqData['round_fid']);
        }

        $this->builder()->set('round_date', $arrReqData['round_date']);
        $this->builder()->set('round_num', $arrReqData['round_num']);
        $this->builder()->set('round_time', $arrReqData['round_time']);
        $this->builder()->set('round_state', 1);
        $this->builder()->set('round_result_1', $arrReqData['round_result_1']);
        $this->builder()->set('round_result_2', $arrReqData['round_result_2']);
        $this->builder()->set('round_result_3', $arrReqData['round_result_3']);
        if(array_key_exists('round_result_4', $arrReqData))
            $this->builder()->set('round_result_4', $arrReqData['round_result_4']);
        if(array_key_exists('round_result_5', $arrReqData))
            $this->builder()->set('round_result_5', $arrReqData['round_result_5']);
        if(array_key_exists('round_result_6', $arrReqData))
            $this->builder()->set('round_result_6', $arrReqData['round_result_6']);
        if(array_key_exists('round_power', $arrReqData))
            $this->builder()->set('round_power', $arrReqData['round_power']);
        if(array_key_exists('round_normal', $arrReqData))
            $this->builder()->set('round_normal', $arrReqData['round_normal']);

        $bResult = $this->builder()->insert();

        return $bResult ? 1 : 0;
    }

    public function modify($arrReqData)
    {
        $this->builder()->set('round_state', 1);
        $this->builder()->set('round_result_1', $arrReqData['round_result_1']);
        $this->builder()->set('round_result_2', $arrReqData['round_result_2']);
        $this->builder()->set('round_result_3', $arrReqData['round_result_3']);
        if(array_key_exists('round_result_4', $arrReqData))
            $this->builder()->set('round_result_4', $arrReqData['round_result_4']);
        if(array_key_exists('round_result_5', $arrReqData))
            $this->builder()->set('round_result_5', $arrReqData['round_result_5']);
        if(array_key_exists('round_result_6', $arrReqData))
            $this->builder()->set('round_result_6', $arrReqData['round_result_6']);
        if(array_key_exists('round_power', $arrReqData))
            $this->builder()->set('round_power', $arrReqData['round_power']);
        if(array_key_exists('round_normal', $arrReqData))
            $this->builder()->set('round_normal', $arrReqData['round_normal']);

        $this->builder()->where('round_fid', $arrReqData['round_fid']);

        return $this->builder()->update();
    }

    public function search($arrReqData)
    {
        $strSql = 'SELECT * FROM '.$this->table;
        $strSql .= " WHERE round_game = ".$this->db->escape($arrReqData['game']);
        if (strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0) {
            $strSql .= " AND round_date >= ".$this->db->escape($arrReqData['start'])." AND round_date <= ".$this->db->escape($arrReqData['end']);
        }
        if (strlen($arrReqData['round']) > 0) {
            $strSql .= " AND round_num = ".$this->db->escape($arrReqData['round']);
        }
        $nStartRow = ($arrReqData['page'] - 1) * $arrReqData['count'];
        $strSql .= ' ORDER BY round_time DESC LIMIT '.$nStartRow.', '.$arrReqData['count'];
        
        $query = $this->db->query($strSql);
        $result = $query->getResult();

        return $result;
    }

    public function searchCount($arrReqData)
    {
        $strSql = 'SELECT count(*) as count  FROM '.$this->table;
        $strSql .= " WHERE round_game = ".$this->db->escape($arrReqData['game']);
        if (strlen($arrReqData['start']) > 0 && strlen($arrReqData['end']) > 0) {
            $strSql .= " AND round_date >= ".$this->db->escape($arrReqData['start'])." AND round_date <= ".$this->db->escape($arrReqData['end']);
        }
        if (strlen($arrReqData['round']) > 0) {
            $strSql .= " AND round_num = ".$this->db->escape($arrReqData['round']);
        }
        $query = $this->db->query($strSql);
        $result = $query->getRow();

        return $result;
    }

    public function searchList($reqData){
        
        $where = "round_game = ".$this->db->escape($reqData['game']);
        if(array_key_exists('date', $reqData) ){
            $where.= " AND round_date = ".$this->db->escape($reqData['date']);
        }
        
        $page = $reqData['page'];
        $count = $reqData['count'];
        
        if($page < 1)
            return NULL;
        if($count < 1)
            return NULL;
        return $this->where($where)
                    ->orderBy('round_time', 'DESC')
                    ->findAll($count, $count*($page-1)); 
    }
}
