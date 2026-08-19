<?php 
namespace App\Libraries;

use App\Models\ConfSite_Model;

class ApiVacc_Lib  {
    
    private $mHost = "";    //"https://xk94rw26.com";
    private $mAgToken = ""; //'xxx'; 
    private $mEnable = 0; //'xxx'; 

    function __construct()
    {
        $modelConfsite = new ConfSite_Model();
      
        $objConf = $modelConfsite->find(CONF_API_VACC);
        if(!is_null($objConf)){
            $arrInfo = explode("#", $objConf->conf_content);
            if(count($arrInfo) >= 2){	//0-host, 1-ag_code, 2-ag_token
                $this->mHost = $arrInfo[0];
                $this->mAgToken = $arrInfo[1];
                $this->mEnable = intval($objConf->conf_active);
            }
        }
    }

    public function isActive(){
        return $this->mEnable == STATE_ACTIVE;
    }

    private function getHeader($post){

        return ['Content-Type: application/json',
            'Content-Length: ' . strlen($post),
            'Accept: */*',
            'API-KEY: '.$this->mAgToken];
    }

    public function getAccountInfo()
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }

        $url = $this->mHost."/v1/diposit/accountInfo";

        $header =  ['API-KEY: '.$this->mAgToken];

        $response = getCurlRequest($url, $header);
        
        $arrResult = json_decode($response, true);
		
        $balance = -1;
		if(!is_null($arrResult) && array_key_exists("code", $arrResult)) {
			if($arrResult['code'] == "GOOD"){
                $arrResult['status'] = 1;
                // "code": "GOOD",
                // "message": "[국민][홍길동]12341234"
                writeLog($arrResult['message']);
            } else { //
                $arrResult['status'] = 0;
                //"code": "BAD",
                // "message": "xxx"
                writeLog($arrResult['message']);
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['message'] = CONNECT_ERROR;
        }

        return $arrResult;
    }

    public function reqDeposit($id, $name, $amount)
    {

        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }

        $url = $this->mHost."/v1/diposit/register";

        $arrPost['depositAmount'] = $amount;
        $arrPost['depositName'] = $name;
        $arrPost['siteUserId'] = $id;
        $post = json_encode($arrPost);
        // writeLog($post);

        $header =  $this->getHeader($post);

        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		
        $balance = -1;
		if(!is_null($arrResult) && array_key_exists("code", $arrResult)) {
			if($arrResult['code'] == "GOOD"){
                $arrResult['status'] = 1;
                // "code": "GOOD",
                // "message": "입금신청 완료."
                writeLog($arrResult['message']);
            } else { //
                $arrResult['status'] = 0;
                //"code": "BAD",
                // "message": "xxx"
                writeLog($arrResult['message']);
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['message'] = CONNECT_ERROR;
        }

        return $arrResult;
    }

    
}