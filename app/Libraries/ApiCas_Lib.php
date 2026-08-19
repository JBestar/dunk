<?php 
namespace App\Libraries;

use App\Models\ConfSite_Model;

class ApiCas_Lib  {
    
    private $mHost = "";    //"https://api.hpplaycasion.com";
    private $mAgCode = "";  //'manager001501';
    private $mAgToken = ""; //'cFpYK1dOdVVUd1lOM2pFZEU1ekZjdnlYUEdnNC9XWEtaVDk3dzI2bzQzUT06MGY2ZTE1MzQtMTM3Yi00MDhkLThhNGMtZjJkNmI3M2Y5Yzc0'; 

    function __construct()
    {
        $modelConfsite = new ConfSite_Model();
      
        $objConf = $modelConfsite->find(CONF_API_HPPLAY);
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
            return array('status' => 0);
        }

        $url = $this->mHost."/customer/user_create";

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
                //"user_id": 20080, // Newly Created Id In AAS
                //"name": "e01",    // $name
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

    public function getAgentInfo()
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }

        $url = $this->mHost."/account/info";

        $post = "";
        $header =  $this->getHeader($post);

        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		
        $balance = -1;
		if(!is_null($arrResult) && array_key_exists("status", $arrResult)) {
			if($arrResult['status'] == 1){
                // "status": 1,
				// "user_id": "69a0d021-cd55-4a65-b648-efa776e50178",
				// "mode": 1,
				// "name": "test102",
				// "balance": 3002950,
				// "createdAt": "2022-02-18T19:12:13.517",
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

    public function getUserInfo($id)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0);
        }

        $url = $this->mHost."/customer/get";

        $arrPost['user_id'] = $id;
        $post = json_encode($arrPost);
       
        $header =  $this->getHeader($post);

        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("status", $arrResult)) {
			if($arrResult['status'] == 1){
                //"status": 1,
                //"balance": 0,
                //"name": "e01",
                //"created_at": "2022-02-20 11:17:17",
                //"updated_at": "2022-02-23 09:39:19",
                //"user_id": 20080,
                //"site_id": "5250005"
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

    public function auth($id, $name, $balance)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0);
        }

        $url = $this->mHost."/account/login";

        $arrUserInfo['id'] = $id;
        $arrUserInfo['name'] = $name;
        $arrUserInfo['balance'] = $balance;
        $arrUserInfo['language'] = "ko";

        $arrPost['user'] = $arrUserInfo;
        $post = json_encode($arrPost);

        $header =  $this->getHeader($post);
        
        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("status", $arrResult)) {
			if($arrResult['status'] == 1){
                //"status": 1,
                //"user_id": 20080, // Created Id In AAS
                //"username": "e01",    // $name
                //"launch_url": "https://evo.hpplaycasion.com/account/login?security_token=R2VCR3VJTW9abjRqS083QVBTSE4vcDVKNDRibHZIS1lqbEFWTFNoZGcvbz06ZTAxNjlhMGQwMjEtY2Q1NS00YTY1LWI2NDgtZWZhNzc2ZTUwMTc4NTI1MDAwNQ==",
                //"error": ""
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


    public function addBalance($id, $balance)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0);
        }
        
        $url = $this->mHost."/customer/add_balance";

        $arrPost['user_id'] = $id;
        $arrPost['balance'] = intval($balance);    
        $post = json_encode($arrPost);
       
        $header =  $this->getHeader($post);

        $response = getCurlRequest($url, $header, $post, CURL_TIMEOUT_MAX);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("status", $arrResult)) {
			if($arrResult['status'] == 1){
                //"status": 1,
                //"user_id": 20080, // Created Id In AAS
                //"site_user": "e01",    // $id
                //"balance": 10000,
                //"amount": 1000
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


    public function subBalance($id, $balance)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0);
        }

        $url = $this->mHost."/customer/sub_balance";

        $arrPost['user_id'] = $id;
        $arrPost['balance'] = $balance;    
        $post = json_encode($arrPost);
       
        $header =  $this->getHeader($post);

        $response = getCurlRequest($url, $header, $post, CURL_TIMEOUT_MAX);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("status", $arrResult)) {
			if($arrResult['status'] == 1){
                //"status": 1,
                //"user_id": 20080, // Created Id In AAS
                //"site_user": "e01",    // $id
                //"balance": 10000,
                //"amount": 1000
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