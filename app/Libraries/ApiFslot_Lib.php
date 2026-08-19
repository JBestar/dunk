<?php 
namespace App\Libraries;

use App\Models\ConfSite_Model;

class ApiFslot_Lib {
    
    private $mHost = "";    //"http://api.gsplay-777.com";
    private $mAgCode = "";  //'mg001001';
    private $mAgToken = ""; //'4ezfoiySXSn01xgeBfgwgDwOK8bvyB6l'; 

    function __construct()
    {
        $modelConfsite = new ConfSite_Model();
      
        $objConf = $modelConfsite->find(CONF_API_GSPLAY);
        if(!is_null($objConf)){
            $arrInfo = explode("#", $objConf->conf_content);
            if(count($arrInfo) >= 3){	//0-host, 1-ag_code, 2-ag_token
                $this->mHost = $arrInfo[0];
                $this->mAgCode = $arrInfo[1];
                $this->mAgToken = $arrInfo[2];
            }
        }
    }

    private function getHeader($post){

        return ['Content-Type: application/json',
            'Content-Length: ' . strlen($post),
            'Accept: */*',
            'ag-code: '.$this->mAgCode,
            'ag-token: '.$this->mAgToken];
    }

    public function createUser($id, $name)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }
        $url = $this->mHost."/user/create";

        $arrPost['id'] = $id;
        $arrPost['name'] = $name;
        $arrPost['balance'] = 0;
        $arrPost['language'] = "ko";
        $post = json_encode($arrPost);

        $header =  $this->getHeader($post);
        
        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("status", $arrResult)) {
			if($arrResult['status'] == 1){
                //"status": 1,
                //"gs_user_id": 20080, // Newly Created Id In AAS
                //"name": "지실장",    // $name
                //"balance": 0
            } else { 
                //"status": 0,
                //"error": "INVALID_ACCESS_TOKEN", DOUBLE_USER, INSUFFICIENT_FUNDS, INTERNAL_ERROR
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['error'] = CONNECT_ERROR;
        }


        return $arrResult;
    }

    
    public function getUserInfo($id)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }
        $url = $this->mHost."/user/info";

        $arrPost['user_id'] = $id;
        $post = json_encode($arrPost);
       
        $header =  $this->getHeader($post);

        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("status", $arrResult)) {
			if($arrResult['status'] == 1){
                //"status": 1,
                // "gs_user_id": 10,
                // "user_id": "5_e01",
                // "name": "지실장",
                // "balance": 0,
                // "created_at": "2022-03-26T08:12:16.000Z",
                // "updated_at": null
            } else { //
                //"status": 0,
                //"error": INVALID_ACCESS_TOKEN, INVALID_PARAMETER, INVALID_USER, INSUFFICIENT_FUNDS, INTERNAL_ERROR
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['error'] = CONNECT_ERROR;
        }

        return $arrResult;
    }

    public function getAgentInfo()
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }
        $url = $this->mHost."/agent/info";

        $post = "";
       
        $header =  $this->getHeader($post);

        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		$balance = -1;
		
		if(!is_null($arrResult) && array_key_exists("status", $arrResult)) {
			if($arrResult['status'] == 1){
                // "status": 1,
				// "agent_id": "69a0d021-cd55-4a65-b648-efa776e50178",
				// "agent_code": "test102",
				// "balance": 3002950,
				// "createdAt": "2022-02-18T19:12:13.517",
				// "updatedAt": "2022-02-18T19:12:13.517",
				// "callback_url": "http://test"
                $balance = $arrResult['balance'];

            } else { //
                //"status": 0,
                //"error": INVALID_ACCESS_TOKEN, INVALID_PARAMETER, INVALID_USER, INSUFFICIENT_FUNDS, INTERNAL_ERROR
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['error'] = CONNECT_ERROR;
        }

        return $arrResult;
    }
    public function auth($id, $name, $game)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }
        $url = $this->mHost."/game/get_url";

        $arrUserInfo['id'] = $id;
        $arrUserInfo['name'] = $name;
        $arrUserInfo['language'] = "ko";

        $arrPrdInfo['prd_id'] = $game->prd_code;
        $arrPrdInfo['game_id'] = $game->uuid;
        
        $arrPost['user'] = $arrUserInfo;
        $arrPost['prd'] = $arrPrdInfo;
        $post = json_encode($arrPost);

        $header =  $this->getHeader($post);
        
        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("status", $arrResult)) {
			if($arrResult['status'] == 1){
            // "status": 1,
            // "gs_user_id": 10,
            // "launch_url": "https://gsplay-tw1.pp-game.net/gs2c/html5Game.do?symbol=vs7776aztec&token=95454fd5f60b95180795638836822414"
            } else { //
                //"status": 0,
                //"error": INVALID_ACCESS_TOKEN, INVALID_PRODUCT, INVALID_PARAMETER, INVALID_USER, INTERNAL_ERROR
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['error'] = CONNECT_ERROR;
        }


        return $arrResult;
    }


    public function addBalance($id, $amount)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }
        $url = $this->mHost."/user/add_balance";

        $arrPost['user_id'] = $id;
        $arrPost['amount'] = intval($amount);  
        $post = json_encode($arrPost);
       
        $header =  $this->getHeader($post);

        $response = getCurlRequest($url, $header, $post, CURL_TIMEOUT_MAX);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("status", $arrResult)) {
			if($arrResult['status'] == 1){
                //"status": 1,
                // "balance": 10000,
                // "amount": 1000,
                // "gs_user_id": 10,
            } else { //
                //"status": 0,
                //"error": INVALID_ACCESS_TOKEN, INVALID_PARAMETER, INVALID_USER, INSUFFICIENT_FUNDS, INTERNAL_ERROR
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['error'] = CONNECT_ERROR;
        }

        return $arrResult;
    }


    public function subBalance($id, $amount)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }
        $url = $this->mHost."/user/sub_balance";

        $arrPost['user_id'] = $id;
        $arrPost['amount'] = $amount;    
        $post = json_encode($arrPost);
       
        $header =  $this->getHeader($post);

        $response = getCurlRequest($url, $header, $post, CURL_TIMEOUT_MAX);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("status", $arrResult)) {
			if($arrResult['status'] == 1){
                //"status": 1,
                // "balance": 10000,
                // "amount": 1000,
                // "gs_user_id": 10,
            } else { //
                //"status": 0,
                //"error": INVALID_ACCESS_TOKEN, INVALID_PARAMETER, INVALID_USER, INSUFFICIENT_FUNDS, INTERNAL_ERROR
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['error'] = CONNECT_ERROR;
        }

        return $arrResult;
    }

}