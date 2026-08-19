<?php 
namespace App\Models;

use CodeIgniter\Model;

class Follow_Model extends Model {

    protected $table      = 'bet_follow';
    protected $primaryKey = 'fl_fid';

    protected $returnType = 'object'; 
    protected $allowedFields = ['fl_mb_fid', 
        'fl_pb_uid', 'fl_ps_uid', 'fl_bb_uid', 'fl_bs_uid', 'fl_e5_uid', 'fl_e3_uid', 'fl_c5_uid', 'fl_c3_uid', 'fl_sk_uid', 'fl_ev_uid', 
        'fl_pb_stop', 'fl_ps_stop', 'fl_bb_stop', 'fl_bs_stop', 'fl_e5_stop', 'fl_e3_stop', 'fl_c5_stop', 'fl_c3_stop', 'fl_sk_stop', 'fl_ev_stop',  
        'fl_pb_rate', 'fl_ps_rate', 'fl_bb_rate', 'fl_bs_rate', 'fl_e5_rate', 'fl_e3_rate', 'fl_c5_rate', 'fl_c3_rate', 'fl_sk_rate', 'fl_ev_rate', 'fl_update' ];

    public function get($fid){
        return $this->find($fid);
    }

    public function getByUser($mb_fid){
        return $this->where('fl_mb_fid', $mb_fid)
                    ->first(); 
    }

    public function getFollower($game, $uid){

        $where = "";
        if($game == GAME_PBG_BALL){
            $where = "fl_pb_uid = '".$uid."' ";
            $where.= " AND fl_pb_stop = '".STATE_DISABLE."' ";
        } else if($game == GAME_BOGLE_BALL){
            $where = "fl_bb_uid = '".$uid."' ";
            $where.= " AND fl_bb_stop = '".STATE_DISABLE."' ";
        } else if($game == GAME_BOGLE_LADDER){
            $where = "fl_bs_uid = '".$uid."' ";
            $where.= " AND fl_bs_stop = '".STATE_DISABLE."' ";
        } else if($game == GAME_EOS5_BALL){
            $where = "fl_e5_uid = '".$uid."' ";
            $where.= " AND fl_e5_stop = '".STATE_DISABLE."' ";
        } else if($game == GAME_EOS3_BALL){
            $where = "fl_e3_uid = '".$uid."' ";
            $where.= " AND fl_e3_stop = '".STATE_DISABLE."' ";
        } else if($game == GAME_COIN5_BALL){
            $where = "fl_c5_uid = '".$uid."' ";
            $where.= " AND fl_c5_stop = '".STATE_DISABLE."' ";
        } else if($game == GAME_COIN3_BALL){
            $where = "fl_c3_uid = '".$uid."' ";
            $where.= " AND fl_c3_stop = '".STATE_DISABLE."' ";
        } else if($game == GAME_SPKN_BALL){
            $where = "fl_sk_uid = '".$uid."' ";
            $where.= " AND fl_sk_stop = '".STATE_DISABLE."' ";
        } else if($game == GAME_DHP_BALL){
            $where = "fl_ev_uid = '".$uid."' ";
            $where.= " AND fl_ev_stop = '".STATE_DISABLE."' ";
        } else return [];

        return $this->where($where)
                    ->findAll(); 
    }

    public function saveByUser($info){
        if(strlen($info['fl_mb_fid']) < 1)
            return false;

        $follow = $this->getByUser($info['fl_mb_fid']);
        if(is_null($follow)){

            $this->insert($info);
        } else {
            $this->update($follow->fl_fid, $info);


        }
    }

}