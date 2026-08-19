<?php 
namespace App\Libraries;

use App\Models\ConfSite_Model;

class ApiKgon_Lib  {
    
    private $mHost = "";    //"https://dev-mw.kgonapi.com";
    private $mAgCode = "";  //'gosu1005';
    private $mAgToken = ""; //'5b9f83c60ef114ace4a4e4aa9da71102'; 

    function __construct()
    {
        $modelConfsite = new ConfSite_Model();
      
        $objConf = $modelConfsite->find(CONF_API_KGON);
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

        return ['Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($post),
            'Accept: */*',
            'k-username: '.$this->mAgCode,
            'k-secret: '.$this->mAgToken];
    }

    public function createUser($id, $name, $uid)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0);
        }

        $url = $this->mHost."/register";

        $post = "username=".$id;
        $post.= "&nickname=".$name;
        $post.= "&siteUsername=".$uid;

        $header =  $this->getHeader($post);
        
        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("code", $arrResult)) {
			if($arrResult['code'] == 0){
                $arrResult['status'] = 1;
                //"code": 0,
                //"id": 586       //gosu1005 회원
                
            } else { 
                $arrResult['status'] = 0;
                //"code": -500,
                //"msg": "ALREADY_USER_EXISTS"
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['msg'] = CONNECT_ERROR;
        }


        return $arrResult;
    }

    public function getAgentInfo()
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }

        $url = $this->mHost."/partner/balance";

        $post = "";
        $header =  $this->getHeader($post);

        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		
        $balance = -1;
		if(!is_null($arrResult) && array_key_exists("code", $arrResult)) {
			if($arrResult['code'] == 0){
				$arrResult['status'] = 1;
                // "code": 0,
    			// "balance": 10000000
                $balance = $arrResult['balance'];
            } else { //
                $arrResult['status'] = 0;
                //"code": 0,
                //"mg": ""
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['error'] = INTERNAL_ERROR;
        }

        return $arrResult;
    }

    public function getUserInfo($id)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0);
        }

        $url = $this->mHost."/balance";

        $post = "username=".$id;

        $header =  $this->getHeader($post);

        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("code", $arrResult)) {
			if($arrResult['code'] == 0){
                $arrResult['status'] = 1;
                if(array_key_exists('balanceSum', $arrResult)){
                    $arrResult['balance'] = $arrResult['balanceSum'];
                }
                //"code": 0,
                //"balance": 40000
            } else { //
                $arrResult['status'] = 0;
                //"code": -1,
                //"msg": "XXX"
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['msg'] = CONNECT_ERROR;
        }

        return $arrResult;
    }

    public function auth($id, $name, $uid, $vendorKey, $gameKey)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0);
        }

        $url = $this->mHost."/play";

        $post = "vendorKey=".$vendorKey;
        $post.= "&gameKey=".$gameKey;
        $post.= "&username=".$id;
        $post.= "&nickname=".$name;
        $post.= "&siteUsername=".$uid;
        $post.= "&amount=0";


        $header =  $this->getHeader($post);
        
        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("code", $arrResult)) {
			if($arrResult['code'] == 0){
                $arrResult['status'] = 1;
                // "code": 0,
                // "url": "http://games.vivogaming.com/?token=62694b48639e771c63158153&serverid=6401748&operatorID=3002828&application=lobby&language=KO",
                // "userId": 453,
                // "balance": "40000.0000"
            } else { //
                $arrResult['status'] = 0;
                //"code": -1,
                //"msg": "XXX"
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['code'] = CONNECT_ERROR;
        }


        return $arrResult;
    }


    public function addBalance($id, $balance)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0);
        }
        
        $url = $this->mHost."/deposit";
        $post = "username=".$id;
        $post.= "&amount=".intval($balance);
       
        $header =  $this->getHeader($post);

        $response = getCurlRequest($url, $header, $post, CURL_TIMEOUT_MAX);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("code", $arrResult)) {
			if($arrResult['code'] == 0){
                $arrResult['status'] = 1;
				$arrResult['amount'] = intval($balance);
                // "code": 0,
                // "balance": 50000
            } else { //
                $arrResult['status'] = 0;
                // writeLog($arrResult['msg']);
                //"code": -1,
                //"msg": "XXX"
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['msg'] = CONNECT_ERROR;
        }

        return $arrResult;
    }


    public function subBalance($id, $balance, $bAll=false)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0);
        }

        $url = $this->mHost."/withdraw";
        $post = "username=".$id;
        if($bAll){
            $post.= "&amount=1&all=y";
        }
        else{
            $post.= "&amount=".intval($balance);
            $post.= "&all=n";
        } 

        $header =  $this->getHeader($post);

        $response = getCurlRequest($url, $header, $post, CURL_TIMEOUT_MAX);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("code", $arrResult)) {
			if($arrResult['code'] == 0){
                $arrResult['status'] = 1;
                $arrResult['balance'] = $arrResult['balance'] - $arrResult['amount'];
                // "code": 0,
                // "balance": 40000,       //처리하기 전의 잔액
                // "amount": 10000         //처리된 금액
            } else { //
                $arrResult['status'] = 0;
                // writeLog($arrResult['msg']);
                //"code": -1,
                //"msg": "XXX"
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['msg'] = CONNECT_ERROR;
        }
        return $arrResult;
    }

}