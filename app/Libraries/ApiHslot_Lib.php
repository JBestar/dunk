<?php 
namespace App\Libraries;

use App\Models\ConfSite_Model;

class ApiHslot_Lib  {
    
    private $mHost = "";   
    private $mAgCode = "";  
    private $mAgToken = ""; 

    function __construct()
    {
        $modelConfsite = new ConfSite_Model();
      
        $objConf = $modelConfsite->find(CONF_API_STAR);
        if(!is_null($objConf)){
            $arrInfo = explode("#", $objConf->conf_content);
            if(count($arrInfo) >= 3){	//0-host, 1-ag_code, 2-ag_token
                $this->mHost = $arrInfo[0];
                $this->mAgCode = $arrInfo[1];
                $this->mAgToken = $arrInfo[2];
            }
        }
    }

    private function getHeader(){

        return [ 'secret_key: '.$this->mAgToken];
    }

    public function createUser($uid, $name)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'description'=>INTERNAL_ERROR);
        }

        $url = $this->mHost."/CreatePlayer";
        $url.= "?PlayerId=".$uid;
        $url.= "&NickName=".$name;

        $header =  $this->getHeader();
        
        $response = getCurlRequest($url, $header);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("state", $arrResult)) {
			if($arrResult['state'] == 0){
                $arrResult['status'] = 1;
                // "playerId": "userid",
                // "playerNickname": "nickname",
                // "gameId": "admin2810userid",
                // "token": "/j/nt123CGm123DFh123hsQfe13fVX/412gd2/WqMWE=",
            } else { 
                $arrResult['status'] = 0;
                //"state": 1,
                //"description": "ID Error"
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['description'] = CONNECT_ERROR;
        }


        return $arrResult;
    }

    public function getAgentInfo()
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'description'=>INTERNAL_ERROR);
        }

        $url = $this->mHost."/GetAgentMoney";

        $header =  $this->getHeader();
        
        $response = getCurlRequest($url, $header);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("state", $arrResult)) {
			if($arrResult['state'] == 0){
                $arrResult['status'] = 1;
                // "state": "0",
                // "description": "Success",
                // "balance": 390000
            } else { 
                $arrResult['status'] = 0;
                //"state": 1,
                //"description": "ID Error"
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['description'] = CONNECT_ERROR;
        }


        return $arrResult;
    }
    
    public function getUserInfo($token)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'description'=>INTERNAL_ERROR);
        }

        $url = $this->mHost."/GetBalance";
        $url.= "?Token=".$token;

        $header =  $this->getHeader();
        
        $response = getCurlRequest($url, $header);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("state", $arrResult)) {
			if($arrResult['state'] == 0){
                $arrResult['status'] = 1;
                // "state": "0",
                // "description": "Success",
                // "memberBalance": 10000,
                // "thirdPartyBalance": 0
                $arrResult['balance'] = $arrResult['memberBalance']+$arrResult['thirdPartyBalance'];
            } else { 
                $arrResult['status'] = 0;
                //"state": 1,
                //"description": "ID Error"
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['description'] = CONNECT_ERROR;
        }


        return $arrResult;
    }

    public function auth($token, $slot, $casino=null)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'description'=>INTERNAL_ERROR);
        }

        $url = $this->mHost."/GetGameURL";
        $url.= "?Token=".$token;
        if(is_null($casino)){
            $url.= "&ProviderCode=".$slot->prd_code;
            $url.= "&GameSeq=".$slot->uuid;
        } else {
            $url.= "&ProviderCode=".$casino->vendor_id;
            $url.= "&GameSeq=".$casino->lobby;
        }
        $url.= "&Platform=".(is_Mobile()?"mobile":"desktop");

        $header =  $this->getHeader();
        
        $response = getCurlRequest($url, $header);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("state", $arrResult)) {
			if($arrResult['state'] == 0){
                $arrResult['status'] = 1;
                // "state": "0",
                // "description": "Success",
                // "url": "https://veda-api.claretfox.com/launch?host_id=ae0de5c0779a5fc89b81af764e38274e&game_id=PSS-ON-00001&lang=ko-KR&access_token=eyJrZXkiOjM1ODM1OCwiaWQiOiI2ZDA1OWQ3NjgyNjQ5YiIsIm9wIjo1NzYsImMiOiIyNCIsImciOiJQU1MtT04tMDAwMDEiLCJkdCI6MTY3NDYxMDU0MTM5NH0="
            } else { 
                $arrResult['status'] = 0;
                //"state": 1,
                //"description": "ID Error"
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['description'] = CONNECT_ERROR;
        }

        return $arrResult;
    }


    public function addBalance($token, $balance)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'description'=>INTERNAL_ERROR);
        }
        
        $url = $this->mHost."/Deposit";
        $url.= "?Token=".$token;
        $url.= "&Amount=".intval($balance);

        $header =  $this->getHeader();
        
        $response = getCurlRequest($url, $header, null, CURL_TIMEOUT_MAX);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("state", $arrResult)) {
			if($arrResult['state'] == 0){
                $arrResult['status'] = 1;
				$arrResult['amount'] = intval($balance);
                // "state": "0",
                // "description": "Success",
                // "data": {
                //     "playerId": "jbestar",
                //     "amount": 10000
                // }
            } else { 
                $arrResult['status'] = 0;
                //"state": 1,
                //"description": "ID Error"
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['description'] = CONNECT_ERROR;
        }


        return $arrResult;
    }


    public function subBalance($token)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'description'=>INTERNAL_ERROR);
        }

        $url = $this->mHost."/Withdraw";
        $url.= "?Token=".$token;

        $header =  $this->getHeader();
        
        $response = getCurlRequest($url, $header, null, CURL_TIMEOUT_MAX);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("state", $arrResult)) {
			if($arrResult['state'] == 0){
                $arrResult['status'] = 1;
                $arrResult['balance'] = 0;
                $arrResult['amount'] = $arrResult['data']['amount'];
                // "state": "0",
                // "description": "Success",
                // "data": {
                //     "playerId": "jbestar",
                //     "amount": 10000
                // }
            } else { 
                $arrResult['status'] = 0;
                //"state": 1,
                //"description": "ID Error"
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['description'] = CONNECT_ERROR;
        }


        return $arrResult;
    }

}