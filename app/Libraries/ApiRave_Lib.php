<?php 
namespace App\Libraries;

use App\Models\ConfSite_Model;

class ApiRave_Lib  {
    
    private $mHost = "";    
    private $mAgCode = "";  
    private $mAgToken = ""; 

    function __construct()
    {
        $modelConfsite = new ConfSite_Model();
      
        $objConf = $modelConfsite->find(CONF_API_RAVE);
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
            'Accept: application/json',
            'Authorization: Bearer '.$this->mAgToken
        ];
    }

    public function getAgentInfo()
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }
        $logHead = "<ApiRave_Lib> getAgentInfo() ";

        $url = $this->mHost."/agent";

        $post = "";
        $header =  $this->getHeader($post);

        $curlResult = getCurlRequest2($url, $header, $post);
		
		if(!is_null($curlResult) && array_key_exists("code", $curlResult)) {
			if($curlResult['code'] == HTTP_CODE_200){
                // $curlResult['body'] =>
                // "agentId": 4496,
                // "loginId": "bsj001",
                // "balance": 100000
                $arrResult = json_decode($curlResult['body'], true);
				$arrResult['status'] = 1;
            } else { //
                // $curlResult['body'] =>
                // "resultCode": "ERROR_AUTH",
                // "message": "Error message",
                // "timestamp": "Server response time"
                // 
                writeLog($logHead."Error result=".json_encode($curlResult));
                // if(array_key_exists('body', $curlResult) && strlen($curlResult['body']) > 0){
                //     $arrResult = json_decode($curlResult['body'], true);
                // }
                $arrResult['status'] = 0;
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['error'] = CONNECT_ERROR;
        }

        return $arrResult;
    }

    public function createUser($id, $name, $uid)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }

        $url = $this->mHost."/user";

        $username = createGameId($this->mAgCode.substr($_ENV['app.gm_prefix'], 0, 2)."_".$id);
        $arrPost['username'] = $username;
        $nickname = str_pad(substr($_ENV['app.gm_prefix'], 0, 2)."_".$uid, 10, ".");
        $arrPost['nickname'] = $nickname;
        $post = json_encode($arrPost);

        $logHead = "<ApiRave_Lib> createUser() ";
        // writeLog($logHead."post=".$post);

        $header =  $this->getHeader($post);
        // writeLog($logHead.json_encode($header));
        
        $curlResult = getCurlRequest2($url, $header, $post);
		
        // writeLog($logHead."curl=".json_encode($curlResult));

		if(!is_null($curlResult) && array_key_exists("code", $curlResult)) {
			if($curlResult['code'] == HTTP_CODE_200){
                // $curlResult['body'] =>
                // "userId": 0,
                // "agentId": 0,
                // "username": "string",
                // "nickname": "string",
                // "balance": 0
                $arrResult = json_decode($curlResult['body'], true);
				$arrResult['status'] = 1;
            } else { //
                // $curlResult['body'] =>
                // "resultCode": "ERROR_AUTH",
                // "message": "Error message",
                // "timestamp": "Server response time"
                // 
                writeLog($logHead."Error result=".json_encode($curlResult));
                // if(array_key_exists('body', $curlResult) && strlen($curlResult['body']) > 0){
                //     $arrResult = json_decode($curlResult['body'], true);
                // }
                $arrResult['status'] = 0;
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['error'] = CONNECT_ERROR;
        }

        // writeLog($logHead."result=".json_encode($arrResult));
        return $arrResult;
    }

    public function getUserInfo($id)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }
        $logHead = "<ApiRave_Lib> getUserInfo() ";

        $url = $this->mHost."/user?username=".$id;

        $post = "";
        $header =  $this->getHeader($post);

        $curlResult = getCurlRequest2($url, $header, $post);
        
		if(!is_null($curlResult) && array_key_exists("code", $curlResult)) {
			if($curlResult['code'] == HTTP_CODE_200){
                // $curlResult['body'] =>
                // "userId": 0,
                // "agentId": 0,
                // "username": "string",
                // "nickname": "string",
                // "balance": 0
                $arrResult = json_decode($curlResult['body'], true);
				$arrResult['status'] = 1;
            } else { //
                // $curlResult['body'] =>
                // "resultCode": "ERROR_AUTH",
                // "message": "Error message",
                // "timestamp": "Server response time"
                // 
                writeLog($logHead."Error result=".json_encode($curlResult));
                // if(array_key_exists('body', $curlResult) && strlen($curlResult['body']) > 0){
                //     $arrResult = json_decode($curlResult['body'], true);
                // }
                $arrResult['status'] = 0;
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['error'] = CONNECT_ERROR;
        }

        // writeLog($logHead."result=".json_encode($arrResult));
        return $arrResult;
    }

    public function createToken($id, $vendorCode)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }

        $url = $this->mHost."/user/token";

        $arrPost['username'] = $id;
        $arrPost['vendorCode'] = $vendorCode;
        $post = json_encode($arrPost);

        $logHead = "<ApiRave_Lib> createToken() ";
        // writeLog($logHead."post=".$post);

        $header =  $this->getHeader($post);
        // writeLog($logHead.json_encode($header));
        
        $curlResult = getCurlRequest2($url, $header, $post, "PATCH");
        // writeLog($logHead."curl=".json_encode($curlResult));

		if(!is_null($curlResult) && array_key_exists("code", $curlResult)) {
			if($curlResult['code'] == HTTP_CODE_200){
                // $curlResult['body'] =>
                // "token": "token"
                $arrResult = json_decode($curlResult['body'], true);
				$arrResult['status'] = 1;
            } else { //
                // $curlResult['body'] =>
                // "resultCode": "ERROR_AUTH",
                // "message": "Error message",
                // "timestamp": "Server response time"
                // 
                writeLog($logHead."Error result=".json_encode($curlResult));
                // if(array_key_exists('body', $curlResult) && strlen($curlResult['body']) > 0){
                //     $arrResult = json_decode($curlResult['body'], true);
                // }
                $arrResult['status'] = 0;
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['error'] = CONNECT_ERROR;
        }

        // writeLog($logHead."result=".json_encode($arrResult));
        return $arrResult;
    }

    public function gameUrl($token, $vendorKey, $gameKey)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0);
        }

        $url = $this->mHost."/game/url?";
        $url.= "vendorCode=".$vendorKey;
        if(strlen($gameKey) > 0)
            $url.= "&gameCode=".$gameKey;
        $url.= "&token=".$token;
        $url.= "&lang=ko";

        $logHead = "<ApiRave_Lib> gameUrl() ";
        // writeLog($logHead."url=".$url);

        $post = "";
        $header =  $this->getHeader($post);
        
        $curlResult = getCurlRequest2($url, $header, $post);
        // writeLog($logHead."curl=".json_encode($curlResult));

		if(!is_null($curlResult) && array_key_exists("code", $curlResult)) {
			if($curlResult['code'] == HTTP_CODE_200){
                // $curlResult['body'] =>
                // "url": "......"
                $arrResult = json_decode($curlResult['body'], true);
				$arrResult['status'] = 1;
            } else { //
                // $curlResult['body'] =>
                // "resultCode": "ERROR_AUTH",
                // "message": "Error message",
                // "timestamp": "Server response time"
                // 
                writeLog($logHead."Error result=".json_encode($curlResult));
                // if(array_key_exists('body', $curlResult) && strlen($curlResult['body']) > 0){
                //     $arrResult = json_decode($curlResult['body'], true);
                // }
                $arrResult['status'] = 0;
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['error'] = CONNECT_ERROR;
        }

        // writeLog($logHead."result=".json_encode($arrResult));
        return $arrResult;
    }


    public function addBalance($id, $amount)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }
        
        $amount = intval($amount);

        $url = $this->mHost."/user/money/charge";

        $arrPost['username'] = $id;
        $arrPost['amount'] = $amount;
        $post = json_encode($arrPost);

        $logHead = "<ApiRave_Lib> addBalance() ";
        // writeLog($logHead."post=".$post);

        $header =  $this->getHeader($post);
        // writeLog($logHead.json_encode($header));
        
        $curlResult = getCurlRequest2($url, $header, $post, "PATCH");
        // writeLog($logHead."curl=".json_encode($curlResult));

		if(!is_null($curlResult) && array_key_exists("code", $curlResult)) {
			if($curlResult['code'] == HTTP_CODE_200){
                // "userId": 784649,
                // "agentId": 4496,
                // "username": "Tbsj001AT_1",
                // "nickname": "ATjbestar",
                // "amount": 10000,
                // "balance": 10000
                $arrResult = json_decode($curlResult['body'], true);
				$arrResult['status'] = 1;
            } else { //
                // $curlResult['body'] =>
                // "resultCode": "ERROR_AUTH",
                // "message": "Error message",
                // "timestamp": "Server response time"
                // 
                writeLog($logHead."Error result=".json_encode($curlResult));
                // if(array_key_exists('body', $curlResult) && strlen($curlResult['body']) > 0){
                //     $arrResult = json_decode($curlResult['body'], true);
                // }
                $arrResult['status'] = 0;
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['error'] = CONNECT_ERROR;
        }

        // writeLog($logHead."result=".json_encode($arrResult));
        return $arrResult;
    }


    public function subBalance($id, $amount, $bAll=false)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }

        $amount = intval($amount);
        if($bAll){
            $url = $this->mHost."/user/money/refund/all";
        }
        else $url = $this->mHost."/user/money/refund";

        $arrPost['username'] = $id;
        if(!$bAll)
            $arrPost['amount'] = $amount;
        $post = json_encode($arrPost);

        $logHead = "<ApiRave_Lib> subBalance() ";
        // writeLog($logHead."post=".$post);

        $header =  $this->getHeader($post);
        // writeLog($logHead.json_encode($header));
        
        $curlResult = getCurlRequest2($url, $header, $post, "PATCH");
        // writeLog($logHead."curl=".json_encode($curlResult));

		if(!is_null($curlResult) && array_key_exists("code", $curlResult)) {
			if($curlResult['code'] == HTTP_CODE_200){
                // "userId": 0,
                // "agentId": 0,
                // "username": "string",
                // "nickname": "string",
                // "balance": 0,
                // "amount": 0
                $arrResult = json_decode($curlResult['body'], true);
				$arrResult['status'] = 1;
            } else { //
                // $curlResult['body'] =>
                // "resultCode": "ERROR_AUTH",
                // "message": "Error message",
                // "timestamp": "Server response time"
                // 
                writeLog($logHead."Error result=".json_encode($curlResult));
                // if(array_key_exists('body', $curlResult) && strlen($curlResult['body']) > 0){
                //     $arrResult = json_decode($curlResult['body'], true);
                // }
                $arrResult['status'] = 0;
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['error'] = CONNECT_ERROR;
        }

        // writeLog($logHead."result=".json_encode($arrResult));
        return $arrResult;   
    }

}