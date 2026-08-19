<?php 
namespace App\Models;

use CodeIgniter\Model;

class Reward_Model extends Model {

    protected $table      = 'bet_reward_mn';
    protected $primaryKey = 'rw_fid';

    protected $returnType = 'object'; 
    protected $allowedFields = ['rw_game', 'rw_bet_id', 'rw_mb_fid', 'rw_mb_uid', 'rw_point', 'rw_time']; 

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

    public function deleteByBetId($game, $betId){
        
        $data = [
            'rw_game' => $game,
            'rw_bet_id' => $betId,
        ];
        
        return $this->where($data)
                    ->delete();

    }
}