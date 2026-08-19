<?php 
namespace App\Libraries;

use App\Models\ConfSite_Model;

class ApiHold_Lib {
    
    private $mHost = "";    //"http://cms.tosan.click";
    private $mAgCode = "";  //'lucky';
    private $mAgToken = ""; //'8b6556144ba4e81f8405d95831640140c6f6fa925c5d8f9dc7f90ce6dc87120c'; 

    function __construct()
    {
        $modelConfsite = new ConfSite_Model();
      
        $objConf = $modelConfsite->find(CONF_API_HOLD);
        if(!is_null($objConf)){
            $arrInfo = explode("#", $objConf->conf_content);
            if(count($arrInfo) >= 3){	//0-host, 1-ag_code, 2-ag_token
                $this->mHost = $arrInfo[0];
                $this->mAgCode = $arrInfo[1];
                $this->mAgToken = $arrInfo[2];
            }
        }
    }

    public function createUser($id)
    {

        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>-1);
        }

        $url = $this->mHost."/interface/login";

        $arrPost['id'] = $id;
        $arrPost['pass'] = $id;
        $arrPost['store_key'] = $this->mAgToken;
        $post = json_encode($arrPost);

        $header =  ['Content-Type: application/json',
                'Content-Length: ' . strlen($post),
                'Accept: */*'];
        
        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("error", $arrResult)) {
			if($arrResult['error'] == 0){
                $arrResult['status'] = 1;
                // "result": {
                //     "id": "TLu_1",
                //     "name": null
                // },
                // "error": 0
            } else { 
                $arrResult['status'] = 0;
                // "result": null,
                // "error": 200000002
            }
		} else {
            $arrResult['status'] = 0;
        }

        return $arrResult;
    }


    public function getUserInfo($id="")
    {

        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>-1);
        }

        $url = $this->mHost."/interface/balance?t=".time();

        if($id == ""){
            $arrPost['id'] = $this->mAgCode;
        } else $arrPost['id'] = $id;
        $arrPost['store_key'] = $this->mAgToken;
        $post = json_encode($arrPost);

        $header =  ['Content-Type: application/json',
                'Content-Length: ' . strlen($post),
                'Accept: */*'];
        
        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("error", $arrResult)) {
			if($arrResult['error'] == 0){
                $arrResult['status'] = 1;
                $arrResult['balance'] = $arrResult['result']['balance'];

                if(array_key_exists("playing_balance", $arrResult['result']) && $arrResult['result']['playing_balance'] > 0)
                    $arrResult['balance'] += $arrResult['result']['playing_balance'];
                
                // "result": {
                //     "balance": 100000,
                //     "playing_balance": 0
                // },
                // "error": 0
            } else { //
                $arrResult['status'] = 0;
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
            return array('status' => 0, 'error'=>-1);
        }

        $url = $this->mHost."/interface/agent";

        $arrPost['store_key'] = $this->mAgToken;
        $post = json_encode($arrPost);

        $header =  ['Content-Type: application/json',
                'Content-Length: ' . strlen($post),
                'Accept: */*'];
        
        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		$balance = -1;
		
		if(!is_null($arrResult) && array_key_exists("error", $arrResult)) {
			if($arrResult['error'] == 0){
                $arrResult['status'] = 1;
                // "result": {
                //     "list": [
                //         {
                //             "id": "lucky",
                //             "rate": 0,
                //             "balance": 800000,
                //             "sub_store_count": 0
                //         }
                //     ]
                // },
                // "error": 0
                $balance = 0;
                if(count($arrResult['result']['list']) > 0 )
                    $balance = $arrResult['result']['list'][0]['balance'];
                $arrResult['balance'] = $balance;

            } else { //
                $arrResult['status'] = 0;
                //"status": 0,
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['error'] = CONNECT_ERROR;
        }

        return $arrResult;
    }

    public function logout($id){
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>-1);
        }

        $url = $this->mHost."/interface/logout/".$id;
        $header =  ['Content-Type: application/json',
                'Accept: */*'];

        $response = getCurlRequest($url, $header);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("error", $arrResult)) {
			if($arrResult['error'] == 0){
                $arrResult['status'] = 1;
                // "result": "TLu_1",
	            // "error": 0
                writeLog("holdem logout=". $arrResult['result']);
            } else { 
                $arrResult['status'] = 0;
                //"error": 0,
                writeLog("holdem logout error=". $arrResult['error']);
            }
		} else {
            writeLog("holdem logout no=". $response);
            $arrResult['status'] = 0;
            $arrResult['error'] = CONNECT_ERROR;
        }
        return $arrResult;
    }

    public function getLink($id)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>-1);
        }

        $url = $this->mHost."/interface/play";

        $arrPost['id'] = $id;
        $arrPost['pass'] = $id;
        $arrPost['store_key'] = $this->mAgToken;
        $post = json_encode($arrPost);

        $header =  ['Content-Type: application/json',
                'Content-Length: ' . strlen($post),
                'Accept: */*'];
        
        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("error", $arrResult)) {
			if($arrResult['error'] == 0){
                $arrResult['status'] = 1;
                $arrResult['url'] = $arrResult['result']['url'];
                // "result": {
                //     "url": "http://tosan.click?token=TiVgvVp71Jt2RQu9lrKU5swpx6zheVDOj4o4eYkwZXfpF09DV5rqn0BRpbj6lTF2aR5jdFvCJTlOfUIRaJygZw"
                // },
                // "error": 0
            } else { 
                $arrResult['status'] = 0;
                //"status": 0,
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['error'] = CONNECT_ERROR;
        }
        return $arrResult;

    }

    public function addBalance($id, $balance)
    {
        
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>-1);
        }

        $url = $this->mHost."/interface/transaction/deposit";

        $arrPost['store_key'] = $this->mAgToken;
        $arrPost['id'] = $id;
        $arrPost['balance'] = intval($balance);
        $post = json_encode($arrPost);

        $header =  ['Content-Type: application/json',
                'Content-Length: ' . strlen($post),
                'Accept: */*'];
        
        $response = getCurlRequest($url, $header, $post, CURL_TIMEOUT_MAX);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("error", $arrResult)) {
			if($arrResult['error'] == 0 && $arrResult['result']['after'] > 0){
                $arrResult['status'] = 1;
                $arrResult['balance'] = $arrResult['result']['after'];
				$arrResult['amount'] = intval($balance);
                // "result": {
                //     "id": "TLu_1",
                //     "balance": "100000",
                //     "after": 100000
                // },
                // "error": 0
            } else { //
                $arrResult['status'] = 0;
                //"status": 0,
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['error'] = CONNECT_ERROR;
        }

        return $arrResult;

    }

    public function subBalance($id, $balance)
    {
        
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>-1);
        }

        $url = $this->mHost."/interface/transaction/withdraw";

        $arrPost['store_key'] = $this->mAgToken;
        $arrPost['id'] = $id;
        $arrPost['balance'] = floatval($balance);
        $post = json_encode($arrPost);

        $header =  ['Content-Type: application/json',
                'Content-Length: ' . strlen($post),
                'Accept: */*'];
        
        $response = getCurlRequest($url, $header, $post, CURL_TIMEOUT_MAX);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("error", $arrResult)) {
			if($arrResult['error'] == 0 && $arrResult['result']['balance'] < 0 ){
                $arrResult['status'] = 1;
                $arrResult['balance'] = $arrResult['result']['after'];
                // "result": {
                //     "id": "TLu_1",
                //     "balance": -1000,
                //     "after": 194000
                // },
                // "error": 0
            } else { //
                $arrResult['status'] = 0;
                //"status": 0,
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['error'] = CONNECT_ERROR;
        }

        return $arrResult;

    }

}