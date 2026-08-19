<?php 
namespace App\Models;

use CodeIgniter\Model;

class Reward_Model extends Model {

    protected $table      = 'bet_reward';
    protected $primaryKey = 'rw_fid';

    protected $returnType = 'object'; 
    protected $allowedFields = ['rw_game', 'rw_bet_id', 'rw_mb_fid', 'rw_point', 'rw_time']; 

    public function register($gameId, $betId, $arrRatio ){
        if(count($arrRatio) < 1)
            return 1;
        $batch = [];
        $dtNow = date("Y-m-d H:i:s");
        foreach($arrRatio as $ratio){
            $insert = [
                'rw_game' => $gameId,
                'rw_bet_id' => $betId,
                'rw_mb_fid' => $ratio['mb_fid'],
                'rw_mb_uid' => $ratio['mb_uid'],
                'rw_point' => $ratio['point'],
                'rw_time' => $dtNow,
            ];
            $batch[] = $insert;
        }
        
        return $this->insertBatch($batch);
    }

    public function calcPoint($objEmp, $arrReqData, $gameId = 0, $bBlank = false){

        $strSQL = ' SELECT SUM(rw_point) AS total_point FROM '.$this->table;

        $strSQL .= " WHERE rw_fid >= ".$arrReqData['rw_range'][0]." AND rw_fid <= ".$arrReqData['rw_range'][1];

        $strSQL.=" AND rw_mb_fid = '".$objEmp->mb_fid."' ";
        if($gameId == GAME_SLOT_ALL){
            $gameId1 = GAME_SLOT_THEPLUS;
            if($_ENV['app.slot'] == APP_SLOT_KGON)
                $gameId1 = GAME_SLOT_KGON;
            else if($_ENV['app.slot'] == APP_SLOT_STAR)
                $gameId1 = GAME_SLOT_STAR;

            $gameId2 = GAME_SLOT_GSPLAY;
            if($_ENV['app.fslot'] == APP_FSLOT_GOLD)
                $gameId2 = GAME_SLOT_GOLD;
        
            $strSQL.=" AND rw_game IN ( '".$gameId1."', '".$gameId2."') ";
        }
        else if($gameId > 0)
            $strSQL.=" AND rw_game = '".$gameId."' ";
        if($bBlank){
            $strSQL.=" AND rw_state = '0' ";
        }
        
        // writeLog($strSQL);
        $objResult = $this->db->query($strSQL)->getRow();
        // writeLog("calcPoint END");
        
        $pointTotal = 0;
        if(!is_null($objResult->total_point)) $pointTotal += $objResult->total_point;

        return $pointTotal;
    }
}