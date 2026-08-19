<?php 
namespace App\Models;

use CodeIgniter\Model;

class SlotGame_Model extends Model {

    protected $table      = 'slot_game';
    protected $primaryKey = 'fid';

    protected $returnType = 'object'; 
    protected $allowedFields = [
        'act',
        'hidden',
        'maintain',
    ];
    protected $prdTable = 'slot_prd';

    public function getById($cat, $prd, $uuid){
        $where = [
            'cat' => $cat,
            'prd_code' => $prd,
            'uuid' => $uuid,
        ];
        return $this->where($where)->first();
    }

    public function gets($cat, $prd){
        $where = [
            'cat' => $cat,
            'prd_code' => $prd,
            'open' => PERMIT_OK
        ];
        
        return $this->where($where)
                    ->orderBy('fid', 'ASC')
                    ->findAll(); 
    }

    
    public function changeAct($arrReqData)
    {
        $data = [];

        if(array_key_exists('act', $arrReqData)){
            $data['act'] =  $arrReqData['act'];
            return $this->update($arrReqData['fid'], $data);
        } else if(array_key_exists('hidden', $arrReqData)){
            $data['hidden'] =  $arrReqData['hidden'];
            return $this->update($arrReqData['fid'], $data);

        } else if(array_key_exists('maintain', $arrReqData)){
            $data['maintain'] =  $arrReqData['maintain'];
            return $this->update($arrReqData['fid'], $data);
        } else return false;
    
    }

    public function search($arrReqData){
        $where = ""; 
        if(strlen($arrReqData['name']) > 0){
            $where = " AND ( name LIKE '%".$arrReqData['name']."%' OR name_ko LIKE '%".$arrReqData['name']."%' )";
        }

        $gameId2 = GAME_SLOT_GSPLAY;
        if($_ENV['app.fslot'] == APP_FSLOT_GOLD)
            $gameId2 = GAME_SLOT_GOLD;

        if($_ENV['app.type'] == APP_TYPE_2 ){
            $strSql = " SELECT * FROM  ".$this->table;
            $strSql.= " WHERE prd_code = ".$arrReqData['prd']." AND cat = ".$gameId2.$where;
        } else {
            $gameId1 = GAME_SLOT_THEPLUS;
            if($_ENV['app.slot'] == APP_SLOT_KGON)
                $gameId1 = GAME_SLOT_KGON;
            else if($_ENV['app.slot'] == APP_SLOT_STAR)
                $gameId1 = GAME_SLOT_STAR;
            else if($_ENV['app.slot'] == APP_SLOT_RAVE)
                $gameId1 = GAME_SLOT_RAVE;
            else if($_ENV['app.slot'] == APP_SLOT_TREEM)
                $gameId1 = GAME_SLOT_TREEM;
            else if($_ENV['app.slot'] == APP_SLOT_SIGMA)
                $gameId1 = GAME_SLOT_SIGMA;

            $strSql = " SELECT fslot_game.*, xslot_game.fid AS xslot_fid, xslot_game.name_ko AS xslot_name_ko, xslot_game.name AS xslot_name, xslot_game.img AS xslot_img ";
            $strSql.= " FROM ( SELECT * FROM ".$this->table." WHERE  prd_code = ".$arrReqData['prd']." AND OPEN = 1 AND cat = ".$gameId2.") AS fslot_game";
            $strSql.= " JOIN ( SELECT * FROM ".$this->table." WHERE OPEN = 1 AND cat = ".$gameId1.$where.") AS xslot_game";
            $strSql.= " ON fslot_game.ref_prd = xslot_game.prd_code AND fslot_game.ref_uuid = xslot_game.uuid ";
        }
        // if($_ENV['app.type'] == APP_TYPE_2 ){
        //     if($arrReqData['prd'] == 200)
        //         $strSql = " SELECT slot_game.*, COUNT(game_code) AS fslot_cnt FROM slot_game WHERE prd_code IN (200, 215) AND OPEN = 1 ".$where." GROUP BY game_code ";
        //     else 
        //         $strSql = " SELECT slot_game.*, 1 AS fslot_cnt FROM slot_game WHERE prd_code = ".$arrReqData['prd']." AND OPEN = 1  ".$where;
        // } else if($_ENV['app.type'] == APP_TYPE_4 ){
        //     $strSql = " SELECT xslot_game.*, fslot_game.fid AS fslot_fid, fslot_game.prd_code AS fslot_prd , fslot_game.cnt AS fslot_cnt, fslot_game.act AS fslot_act FROM ";
        //     $strSql.= " ( SELECT * FROM slot_game WHERE prd_code = ".$arrReqData['prd']." AND OPEN = 1 AND cat = ".GAME_SLOT_THEPLUS.$where.") AS xslot_game ";
        //     $strSql.= " JOIN (SELECT *, COUNT(NAME) AS cnt FROM slot_game WHERE prd_code IN ( SELECT code FROM slot_prd WHERE ref_code = ".$arrReqData['prd']." AND cat = ".GAME_SLOT_GOLD." ) AND OPEN = 1 AND cat = ".GAME_SLOT_GOLD." GROUP BY name_ko) AS fslot_game ";
        //     $strSql.= " ON xslot_game.name_ko = fslot_game.name_ko ";
        // } else if($_ENV['app.type'] == APP_TYPE_5 || $_ENV['app.type'] == APP_TYPE_7 || $_ENV['app.type'] == APP_TYPE_9 ){
        //     $gameId = GAME_SLOT_GOLD;
        //     if($_ENV['app.type'] == APP_TYPE_7)
        //         $gameId = GAME_SLOT_KGON;
        //     else if($_ENV['app.type'] == APP_TYPE_9)
        //         $gameId = GAME_SLOT_STAR;

        //     $strSql = " SELECT slot_game.*, 1 AS fslot_cnt FROM slot_game WHERE prd_code = ".$arrReqData['prd']." AND OPEN = 1 AND cat = ".$gameId.$where;
        // } else if($_ENV['app.type'] == APP_TYPE_6 ){
        //     $strSql = " SELECT xslot_game.*, fslot_game.fid AS fslot_fid, fslot_game.prd_code AS fslot_prd , fslot_game.cnt AS fslot_cnt, fslot_game.act AS fslot_act FROM ";
        //     $strSql.= " ( SELECT * FROM slot_game WHERE prd_code = ".$arrReqData['prd']." AND OPEN = 1 AND cat = ".GAME_SLOT_THEPLUS.$where.") AS xslot_game ";
        //     $strSql.= " JOIN (SELECT *, COUNT(NAME) AS cnt FROM slot_game WHERE prd_code IN ( SELECT code FROM slot_prd WHERE ref_code = ".$arrReqData['prd']." AND cat = ".GAME_SLOT_KGON." ) AND OPEN = 1 AND cat = ".GAME_SLOT_KGON." GROUP BY name) AS fslot_game ";
        //     $strSql.= " ON xslot_game.name = fslot_game.name ";
        // } else if($_ENV['app.type'] == APP_TYPE_8 ){
        //     $strSql = " SELECT xslot_game.*, fslot_game.fid AS fslot_fid, fslot_game.prd_code AS fslot_prd , fslot_game.cnt AS fslot_cnt, fslot_game.act AS fslot_act FROM ";
        //     $strSql.= " ( SELECT * FROM slot_game WHERE prd_code = ".$arrReqData['prd']." AND OPEN = 1 AND cat = ".GAME_SLOT_THEPLUS.$where.") AS xslot_game ";
        //     $strSql.= " JOIN (SELECT *, COUNT(NAME) AS cnt FROM slot_game WHERE prd_code IN ( SELECT code FROM slot_prd WHERE ref_code = ".$arrReqData['prd']." AND cat = ".GAME_SLOT_STAR." ) AND OPEN = 1 AND cat = ".GAME_SLOT_STAR."  GROUP BY name) AS fslot_game ";
        //     $strSql.= " ON xslot_game.name = fslot_game.name ";
        // } else {
        //     $strSql = " SELECT xslot_game.*, fslot_game.fid AS fslot_fid, fslot_game.prd_code AS fslot_prd , fslot_game.cnt AS fslot_cnt, fslot_game.act AS fslot_act FROM ";
        //     $strSql.= " ( SELECT * FROM slot_game WHERE prd_code = ".$arrReqData['prd']." AND OPEN = 1 AND ref_prd = 0 AND cat = ".GAME_SLOT_THEPLUS.$where.") AS xslot_game ";
        //     $strSql.= " JOIN (SELECT *, COUNT(NAME) AS cnt FROM slot_game WHERE prd_code IN ( SELECT code FROM slot_prd WHERE ref_code = ".$arrReqData['prd']." AND cat = ".GAME_SLOT_GSPLAY." ) AND OPEN = 1 AND cat = ".GAME_SLOT_GSPLAY."  GROUP BY name) AS fslot_game ";
        //     $strSql.= " ON xslot_game.name = fslot_game.name ";
            
        //     $strSql.= " UNION ALL SELECT xslot_game.*, fslot_game.fid AS fslot_fid, fslot_game.prd_code AS fslot_prd, 1 AS fslot_cnt, fslot_game.act AS fslot_act FROM ";
        //     $strSql.= " ( SELECT * FROM slot_game WHERE prd_code = ".$arrReqData['prd']." AND OPEN = 1 AND ref_prd > 0 ".$where.") AS xslot_game ";
        //     $strSql.= " JOIN slot_game AS fslot_game ON xslot_game.ref_prd = fslot_game.prd_code AND xslot_game.ref_uuid = fslot_game.uuid ";
        // }
        
        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
        $strSql.=" ORDER BY name_ko ASC LIMIT ".$nStartRow.", ".$arrReqData['count'];
        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog($strSql);

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result; 

    }

    public function searchCount($arrReqData){
        
        $where = ""; 
        if(strlen($arrReqData['name']) > 0){
            $where = " AND ( name LIKE '%".$arrReqData['name']."%' OR name_ko LIKE '%".$arrReqData['name']."%' )";
        }

        $gameId2 = GAME_SLOT_GSPLAY;
        if($_ENV['app.fslot'] == APP_FSLOT_GOLD)
            $gameId2 = GAME_SLOT_GOLD;

        if($_ENV['app.type'] == APP_TYPE_2 ){
            $strSql = " SELECT slot_game.fid FROM  ".$this->table;
            $strSql.= " WHERE prd_code = ".$arrReqData['prd']." AND cat = ".$gameId2.$where;
        } else {
            $gameId1 = GAME_SLOT_THEPLUS;
            if($_ENV['app.slot'] == APP_SLOT_KGON)
                $gameId1 = GAME_SLOT_KGON;
            else if($_ENV['app.slot'] == APP_SLOT_STAR)
                $gameId1 = GAME_SLOT_STAR;
            else if($_ENV['app.slot'] == APP_SLOT_RAVE)
                $gameId1 = GAME_SLOT_RAVE;
            else if($_ENV['app.slot'] == APP_SLOT_TREEM)
                $gameId1 = GAME_SLOT_TREEM;
            else if($_ENV['app.slot'] == APP_SLOT_SIGMA)
                $gameId1 = GAME_SLOT_SIGMA;
            
            $strSql = " SELECT fslot_game.fid ";
            $strSql.= " FROM ( SELECT * FROM ".$this->table." WHERE  prd_code = ".$arrReqData['prd']." AND OPEN = 1 AND cat = ".$gameId2.") AS fslot_game";
            $strSql.= " JOIN ( SELECT * FROM ".$this->table." WHERE OPEN = 1 AND cat = ".$gameId1.$where.") AS xslot_game";
            $strSql.= " ON fslot_game.ref_prd = xslot_game.prd_code AND fslot_game.ref_uuid = xslot_game.uuid ";
        }
        // if($_ENV['app.type'] == APP_TYPE_2 ){
        //     if($arrReqData['prd'] == 200)
        //         $strSql = " SELECT slot_game.fid FROM slot_game WHERE prd_code IN (200, 215) AND OPEN = 1 ".$where." GROUP BY game_code ";
        //     else 
        //         $strSql = " SELECT slot_game.fid FROM slot_game WHERE prd_code = ".$arrReqData['prd']." AND OPEN = 1 ".$where;
        // } else if($_ENV['app.type'] == APP_TYPE_4){
        //     $strSql = " SELECT xslot_game.fid FROM ";
        //     $strSql.= " ( SELECT * FROM slot_game WHERE prd_code = ".$arrReqData['prd']." AND OPEN = 1 AND cat = ".GAME_SLOT_THEPLUS.$where.") AS xslot_game ";
        //     $strSql.= " JOIN (SELECT *, COUNT(NAME) AS cnt FROM slot_game WHERE prd_code IN ( SELECT code FROM slot_prd WHERE ref_code = ".$arrReqData['prd']." AND cat = ".GAME_SLOT_GOLD." ) AND OPEN = 1 AND cat = ".GAME_SLOT_GOLD." GROUP BY name_ko) AS fslot_game ";
        //     $strSql.= " ON xslot_game.name_ko = fslot_game.name_ko ";
        // } else if($_ENV['app.type'] == APP_TYPE_5 || $_ENV['app.type'] == APP_TYPE_7 || $_ENV['app.type'] == APP_TYPE_9 ){
        //     $gameId = GAME_SLOT_GOLD;
        //     if($_ENV['app.type'] == APP_TYPE_7)
        //         $gameId = GAME_SLOT_KGON;
        //     else if($_ENV['app.type'] == APP_TYPE_9)
        //         $gameId = GAME_SLOT_STAR;
            
        //     $strSql = " SELECT slot_game.fid FROM slot_game WHERE prd_code = ".$arrReqData['prd']." AND OPEN = 1 AND cat = ".$gameId.$where;
        // } else if($_ENV['app.type'] == APP_TYPE_6){
        //     $strSql = " SELECT xslot_game.fid FROM ";
        //     $strSql.= " ( SELECT * FROM slot_game WHERE prd_code = ".$arrReqData['prd']." AND OPEN = 1 AND cat = ".GAME_SLOT_THEPLUS.$where.") AS xslot_game ";
        //     $strSql.= " JOIN (SELECT *, COUNT(NAME) AS cnt FROM slot_game WHERE prd_code IN ( SELECT code FROM slot_prd WHERE ref_code = ".$arrReqData['prd']." AND cat = ".GAME_SLOT_KGON." ) AND OPEN = 1 AND cat = ".GAME_SLOT_KGON." GROUP BY name) AS fslot_game ";
        //     $strSql.= " ON xslot_game.name = fslot_game.name ";
        // } else if($_ENV['app.type'] == APP_TYPE_8){
        //     $strSql = " SELECT xslot_game.fid FROM ";
        //     $strSql.= " ( SELECT * FROM slot_game WHERE prd_code = ".$arrReqData['prd']." AND OPEN = 1 AND cat = ".GAME_SLOT_THEPLUS.$where.") AS xslot_game ";
        //     $strSql.= " JOIN (SELECT *, COUNT(NAME) AS cnt FROM slot_game WHERE prd_code IN ( SELECT code FROM slot_prd WHERE ref_code = ".$arrReqData['prd']." AND cat = ".GAME_SLOT_STAR." ) AND OPEN = 1 AND cat = ".GAME_SLOT_STAR." GROUP BY name) AS fslot_game ";
        //     $strSql.= " ON xslot_game.name = fslot_game.name ";
        // } else {
        //     $strSql = " SELECT xslot_game.fid FROM ";
        //     $strSql.= " ( SELECT * FROM slot_game WHERE prd_code = ".$arrReqData['prd']." AND OPEN = 1 AND cat = ".GAME_SLOT_THEPLUS." AND ref_prd = 0 ".$where.") AS xslot_game ";
        //     $strSql.= " JOIN (SELECT *, COUNT(NAME) AS cnt FROM slot_game WHERE prd_code IN ( SELECT code FROM slot_prd WHERE ref_code = ".$arrReqData['prd']." AND cat = ".GAME_SLOT_GSPLAY." ) AND OPEN = 1 AND cat = ".GAME_SLOT_GSPLAY." GROUP BY name) AS fslot_game ";
        //     $strSql.= " ON xslot_game.name = fslot_game.name ";
            
        //     $strSql.= " UNION ALL SELECT xslot_game.fid FROM ";
        //     $strSql.= " ( SELECT * FROM slot_game WHERE prd_code = ".$arrReqData['prd']." AND OPEN = 1 AND ref_prd > 0 ".$where.") AS xslot_game ";
        //     $strSql.= " JOIN slot_game AS fslot_game ON xslot_game.ref_prd = fslot_game.prd_code AND xslot_game.ref_uuid = fslot_game.uuid ";
        // }

        if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT)
            writeLog($strSql);

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        $objResult = new \StdClass;
        $objResult->count = count($result);
        return $objResult; 
    }



}