<?php 
namespace App\Libraries;

use App\Models\ConfSite_Model;

class ApiSigma_Lib  {
    
    private $mHost = "";    
    private $mAgCode = "";  
    private $mAgToken = ""; 

    function __construct()
    {
        $modelConfsite = new ConfSite_Model();
      
        $objConf = $modelConfsite->find(CONF_API_SIGMA);
        if(!is_null($objConf)){
            $arrInfo = explode("#", $objConf->conf_content);
            if(count($arrInfo) >= 3){	//0-host, 1-site_code, 2-api_token
                $this->mHost = $arrInfo[0];
                $this->mAgCode = $arrInfo[1];
                $this->mAgToken = $arrInfo[2];
            }
        }
    }

    private function getHeader(){

        return [
            "Authorization: ".$this->mAgToken
        ];
    }

    private function getFrontUrl($segment){

        return "$this->mHost/$segment?site_code=$this->mAgCode";
    }

    public function getAgentInfo()
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }
        $logHead = "<ApiSigma_Lib> getAgentInfo() ";

        $url = $this->getFrontUrl("agent");

        $header =  $this->getHeader();

        $curlResult = getCurlRequestWithProxy($url, $header);
		
		if(!is_null($curlResult) && array_key_exists("code", $curlResult)) {
			if($curlResult['code'] == HTTP_CODE_200){
                // $curlResult['body'] =>
                // "result": "OK",
                // "balance": "891101"
                $arrResult = json_decode($curlResult['body'], true);
				$arrResult['status'] = 1;
            } else { //
                writeLog($logHead."Error result=".json_encode($curlResult));
                $arrResult['status'] = 0;
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['error'] = CONNECT_ERROR;
        }

        return $arrResult;
    }

    public function createUser($id, $uid)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }

        $username = createGameId(substr($_ENV['app.gm_prefix'], 0, 2).$id);
        $arrResult['username'] = strtolower($username);
        $arrResult['status'] = 1;
        return $arrResult;
    }

    public function getUserInfo($id)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }
        $logHead = "<ApiSigma_Lib> getUserInfo() ";

        $url = $this->getFrontUrl("transfer_v2/balance");
        $url.= "&user_id=$id";

        $header =  $this->getHeader();

        $curlResult = getCurlRequestWithProxy($url, $header);
        // writeLog($logHead."result=".json_encode($curlResult));
        
		if(!is_null($curlResult) && array_key_exists("code", $curlResult)) {
			if($curlResult['code'] == HTTP_CODE_200){
                // $curlResult['body'] =>
                // "result": "OK",
                // "balance": "0.00"
                $arrResult = json_decode($curlResult['body'], true);
				if($arrResult['result'] == "OK")
                    $arrResult['status'] = 1;
                else {
                    writeLog($logHead."Error result=".$curlResult['body']);
                    $arrResult['status'] = 0;
                }
            } else { //
                // $curlResult['body'] =>
                writeLog($logHead."Error result=".json_encode($curlResult));
                $arrResult['status'] = 0;
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['error'] = CONNECT_ERROR;
        }

        // writeLog($logHead."result=".json_encode($arrResult));
        return $arrResult;
    }

    public function gameUrl($uid, $nickName, $vendorCode, $gameCode, $ip="", $session_token="", $betting_profile="")
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0);
        }
        $logHead = "<ApiSigma_Lib> gameUrl() ";

        $lobbyUrl = $_ENV['sigma_lobby_url'];
        $cashierUrl = $_ENV['sigma_cashier_url'];

        $url = $this->getFrontUrl("transfer_v2/play");
        $url.= "&user_id=$uid&nickname=$nickName&user_ip=".urlencode($ip);
        $url.= "&vendorCode=".urlencode($vendorCode)."&gameCode=".urlencode($gameCode)."&session_token=$session_token";
        // $url.= "&lobby=".$lobbyUrl."&cashier=".$cashierUrl;
        $url.= "&lobby=".urlencode($lobbyUrl)."&cashier=".urlencode($cashierUrl);
        if(strlen($betting_profile) > 0)
            $url.= "&betting_profile=$betting_profile";

        writeLog($logHead." url=".$url);
        $header =  $this->getHeader();
        
        $curlResult = getCurlRequestWithProxy($url, $header);

		if(!is_null($curlResult) && array_key_exists("code", $curlResult)) {
			if($curlResult['code'] == HTTP_CODE_200){
                // $curlResult['body'] =>
                // "result": "OK",
                // "url": "......"
                $arrResult = json_decode($curlResult['body'], true);
				if($arrResult['result'] == "OK" && array_key_exists("url", $arrResult))
                    $arrResult['status'] = 1;
                else {
                    writeLog($logHead."Error result=".$curlResult['body']);
                    $arrResult['status'] = 0;
                }
            } else { //
                writeLog($logHead."Error result=".json_encode($curlResult));
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
        $logHead = "<ApiSigma_Lib> addBalance() ";
        
        $amount = intval($amount);
        $seed = microtime(true);

        $extId = generateExtId();
        $url = $this->getFrontUrl("transfer_v2/deposit");
        $url.= "&user_id=$id";
        $url.= "&amount=$amount";
        $url.= "&external_id=$extId";
        writeLog($logHead." url=".$url);

        $header =  $this->getHeader();
        
        $curlResult = getCurlRequestWithProxy($url, $header);

		if(!is_null($curlResult) && array_key_exists("code", $curlResult)) {
			if($curlResult['code'] == HTTP_CODE_200){
                // "result": "OK",
                // "status": 0,
                // "balance": "100000.000",
                // "site_balance": "900000.000",
                // "transfer_id": 111

                // {
                //     "result": "Error",
                //     "status": 1,
                //     "msg": "Transfer already exist"
                // }
                $arrResult = json_decode($curlResult['body'], true);
                if($arrResult['result'] == "OK"){
                    $arrResult['amount'] = $amount;
                    $arrResult['status'] = 1;
                }
                else {
                    writeLog($logHead."Error result=".$curlResult['body']);
                    $arrResult['status'] = 0;
                }
            } else { //
                writeLog($logHead."Error result=".json_encode($curlResult));
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
        $logHead = "<ApiSigma_Lib> subBalance() ";

        $extId = generateExtId();
        $amount = intval($amount);
        if($bAll)
            $amount = 0;

        $url = $this->getFrontUrl("transfer_v2/withdraw");
        $url.= "&user_id=$id";
        $url.= "&amount=$amount";
        $url.= "&external_id=$extId";

        $header =  $this->getHeader();
        writeLog($logHead." url=".$url);
        
        $curlResult = getCurlRequestWithProxy($url, $header);

		if(!is_null($curlResult) && array_key_exists("code", $curlResult)) {
			if($curlResult['code'] == HTTP_CODE_200){
            // "result": "OK",
                // "status": 0,
                // "amount": "1000",
                // "balance": "9000",
                // "site_balance": 891101,
                // "transfer_id": 117

            // "result": "Error",
                // "status": 1,
                // "msg": "Not enough balance",
                // "amount": "10000",
                // "balance": 0,
                // "site_balance": "890101",
                // "transfer_id": 116
                $arrResult = json_decode($curlResult['body'], true);
				if($arrResult['result'] == "OK")
                    $arrResult['status'] = 1;
                else {
                    writeLog($logHead."Error result=".$curlResult['body']);
                    $arrResult['status'] = 0;
                }
            } else { //
                writeLog($logHead."Error result=".json_encode($curlResult));
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