<?php 
namespace App\Libraries;

use App\Models\ConfSite_Model;

class ApiSlot_Lib {
    
    private $mHost = "";    //"http://ko1.api-theplus.com";
    private $mAgCode = "";  //'lk100';
    private $mAgToken = ""; //'8b6556144ba4e81f8405d95831640140c6f6fa925c5d8f9dc7f90ce6dc87120c'; 

    function __construct()
    {
        $modelConfsite = new ConfSite_Model();
      
        $objConf = $modelConfsite->find(CONF_API_THEPLUS);
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
            return array('status' => 0, 'resultCode'=>-1);
        }

        $url = $this->mHost."/custom/api/user/Create";

        $arrPost['key'] = $this->mAgCode;
        $arrPost['secret'] = $this->mAgToken;
        $arrPost['userID'] = $id;
        $post = json_encode($arrPost);

        $header =  ['Content-Type: application/json',
                'Content-Length: ' . strlen($post),
                'Accept: */*'];
        
        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("resultCode", $arrResult)) {
			if($arrResult['resultCode'] == 0){
                $arrResult['status'] = 1;
                // "status": 1,
                // "resultCode": "0",
                // "resultMessage": "OK"
            } else { 
                $arrResult['status'] = 0;
                //"status": 0,
            }
		} else {
            $arrResult['status'] = 0;
        }


        return $arrResult;
    }


    public function getUserInfo($id, $renew=false)
    {

        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'resultCode'=>-1);
        }

        $url = $this->mHost."/custom/api/user/GetBalance?t=".time();

        $arrPost['key'] = $this->mAgCode;
        $arrPost['secret'] = $this->mAgToken;
        $arrPost['userID'] = $id;
        $arrPost['isRenew'] = $renew?"true":"false";
        $post = json_encode($arrPost);

        $header =  ['Content-Type: application/json',
                'Content-Length: ' . strlen($post),
                'Accept: */*'];
        
        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("resultCode", $arrResult)) {
			if($arrResult['resultCode'] == 0){
                $arrResult['status'] = 1;
                // "resultCode": "0",
                // "resultMessage": "OK",
                // "balance": 0
            } else { //
                $arrResult['status'] = 0;
                //"status": 0,
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['resultCode'] = CONNECT_ERROR;
        }

        return $arrResult;
    }

    public function getAgentInfo()
    {

        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'resultCode'=>-1);
        }

        $url = $this->mHost."/custom/api/agent/GetCurrentEgg";

        $arrPost['key'] = $this->mAgCode;
        $arrPost['secret'] = $this->mAgToken;
        $post = json_encode($arrPost);

        $header =  ['Content-Type: application/json',
                'Content-Length: ' . strlen($post),
                'Accept: */*'];
        
        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		$balance = -1;
		
		if(!is_null($arrResult) && array_key_exists("resultCode", $arrResult)) {
			if($arrResult['resultCode'] == 0){
                $arrResult['status'] = 1;
                // "resultCode": "0",
                // "resultMessage": "OK",
                // "currentEgg": 0
                $balance = $arrResult['currentEgg'];
                $arrResult['balance'] = $balance;

            } else { //
                $arrResult['status'] = 0;
                //"status": 0,
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['resultCode'] = CONNECT_ERROR;
        }

        return $arrResult;
    }

    public function createSess($id)
    {

        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'resultCode'=>-1);
        }

        $url = $this->mHost."/custom/api/game/CreateSession";

        $arrPost['key'] = $this->mAgCode;
        $arrPost['secret'] = $this->mAgToken;
        $arrPost['userID'] = $id;
        $post = json_encode($arrPost);

        $header =  ['Content-Type: application/json',
                'Content-Length: ' . strlen($post),
                'Accept: */*'];
        
        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("resultCode", $arrResult)) {
			if($arrResult['resultCode'] == 0){
                $arrResult['status'] = 1;
                // "resultCode": "0",
                // "resultMessage": "OK",
                // "session": "99204A59A4CD56BBF7FBF3F9497B382A"
            } else { //
                $arrResult['status'] = 0;
                //"status": 0,
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['resultCode'] = CONNECT_ERROR;
        }

        return $arrResult;


    }


    public function getLink($sessId, $gameId)
    {

        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'resultCode'=>-1);
        }

        $url = $this->mHost."/custom/api/game/Link";

        $arrPost['key'] = $this->mAgCode;
        $arrPost['secret'] = $this->mAgToken;
        $arrPost['session'] = $sessId;
        $arrPost['gameID'] = $gameId;
        $post = json_encode($arrPost);

        $header =  ['Content-Type: application/json',
                'Content-Length: ' . strlen($post),
                'Accept: */*'];
        
        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("resultCode", $arrResult)) {
			if($arrResult['resultCode'] == 0){
                $arrResult['status'] = 1;
                // "resultCode": "0",
                // "resultMessage": "OK",
                // "gameUrl": "https://2vivo.com/flashrungame/RunBTechGame.aspx?token=1C06C8C160CE14366E024C4121285AB2_809553&operatorid=1000056&gameconfig=afterclass&config=bbtech_en&Room=30116"
            } else { //
                $arrResult['status'] = 0;
                //"status": 0,
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['resultCode'] = CONNECT_ERROR;
        }

        return $arrResult;


    }

    public function addBalance($id, $balance)
    {
        
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'resultCode'=>-1);
        }

        $url = $this->mHost."/custom/api/user/Deposit";

        $arrPost['key'] = $this->mAgCode;
        $arrPost['secret'] = $this->mAgToken;
        $arrPost['userID'] = $id;
        $arrPost['amount'] = intval($balance);
        $post = json_encode($arrPost);

        $header =  ['Content-Type: application/json',
                'Content-Length: ' . strlen($post),
                'Accept: */*'];
        
        $response = getCurlRequest($url, $header, $post, CURL_TIMEOUT_MAX);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("resultCode", $arrResult)) {
			if($arrResult['resultCode'] == 0){
                $arrResult['status'] = 1;
				$arrResult['amount'] = intval($balance);
                // "resultCode": "0",
                // "resultMessage": "OK",
                // "balance": "10000"
            } else { //
                $arrResult['status'] = 0;
                //"status": 0,
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['resultCode'] = CONNECT_ERROR;
        }

        return $arrResult;

    }

    public function subBalance($id, $balance)
    {
        
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'resultCode'=>-1);
        }

        $url = $this->mHost."/custom/api/user/WithdrawAll";

        $arrPost['key'] = $this->mAgCode;
        $arrPost['secret'] = $this->mAgToken;
        $arrPost['userID'] = $id;
        // $arrPost['amount'] = floatval($balance);
        $post = json_encode($arrPost);

        $header =  ['Content-Type: application/json',
                'Content-Length: ' . strlen($post),
                'Accept: */*'];
        
        $response = getCurlRequest($url, $header, $post, CURL_TIMEOUT_MAX);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("resultCode", $arrResult)) {
			if($arrResult['resultCode'] == 0 ){
                $arrResult['status'] = 1;
                // "resultCode": "0",
                // "resultMessage": "OK",
                // "balance": "10000"
            } else { //
                $arrResult['status'] = 0;
                //"status": 0,
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['resultCode'] = CONNECT_ERROR;
        }

        return $arrResult;

    }

}