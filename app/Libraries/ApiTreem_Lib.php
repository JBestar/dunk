<?php 
namespace App\Libraries;

use App\Models\ConfSite_Model;

class ApiTreem_Lib  {
    
    private $mHost = "";    
    private $mAgCode = "";  
    private $mAgToken = ""; 

    function __construct()
    {
        $modelConfsite = new ConfSite_Model();
      
        $objConf = $modelConfsite->find(CONF_API_TREEM);
        if(!is_null($objConf)){
            $arrInfo = explode("#", $objConf->conf_content);
            if(count($arrInfo) >= 3){	//0-host, 1-ag_code, 2-ag_token
                $this->mHost = $arrInfo[0];
                $this->mAgCode = $arrInfo[1];
                $this->mAgToken = $arrInfo[2];
            }
        }
    }

    private function getHeader($post=""){

        return ['Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer '.$this->mAgToken
        ];
    }

    public function getAgentInfo()
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }
        $logHead = "<ApiTreem_Lib> getAgentInfo() ";

        $url = $this->mHost."/my-info";

        $header =  $this->getHeader();

        $curlResult = getCurlRequestWithProxy($url, $header);
		
		if(!is_null($curlResult) && array_key_exists("code", $curlResult)) {
			if($curlResult['code'] == HTTP_CODE_200){
                // $curlResult['body'] =>
                // "id": 1,
                // "type": "유통",
                // "username": "testAgent",
                // "nickname": "테스트에이전트",
                // "callback_url": "http://callback_url.com",
                // "balance": 10000,
                // "created_at": "2021-07-03T15:16:53.000000Z"
                $arrResult = json_decode($curlResult['body'], true);
				$arrResult['status'] = 1;
            } else { //
                // $curlResult['body'] =>
                // 
                writeLog($logHead."Error result=".json_encode($curlResult));
                $arrResult['status'] = 0;
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['error'] = CONNECT_ERROR;
        }

        return $arrResult;
    }

    public function getUserInfo($uid)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }
        $logHead = "<ApiTreem_Lib> getUserInfo() ";

        $url = $this->mHost."/user?username=".$uid;

        $header =  $this->getHeader();

        $curlResult = getCurlRequestWithProxy($url, $header);
        
		if(!is_null($curlResult) && array_key_exists("code", $curlResult)) {
			if($curlResult['code'] == HTTP_CODE_200){
                // $curlResult['body'] =>
                // "id": 1,
                // "username": "test123",
                // "nickname": "test123",
                // "country": "KOR",
                // "currency_code": "KRW",
                // "token": null,
                // "balance": 0,
                // "point": 0,
                // "created_at": "2021-07-03T15:16:53.000000Z",
                // "updated_at": "2021-07-03T15:16:53.000000Z",
                // "last_access_at": null,
                // "agent_id": 1
                $arrResult = json_decode($curlResult['body'], true);
                if(array_key_exists('username', $arrResult) && array_key_exists('balance', $arrResult)) {
                    $arrResult['status'] = 1;
                } else {
                    writeLog($logHead."Error response=".$curlResult['body']);
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

    public function getUserList()
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }
        $logHead = "<ApiTreem_Lib> getUserList() ";

        $url = $this->mHost."/user-list";

        $header =  $this->getHeader();

        $curlResult = getCurlRequestWithProxy($url, $header);
        
		if(!is_null($curlResult) && array_key_exists("code", $curlResult)) {
			if($curlResult['code'] == HTTP_CODE_200){
                // $curlResult['body'] =>
                // [
                //     {
                //         "id": 1,
                //         "username": "test123",
                //         "nickname": "test123",
                //         "token": null,
                //         "balance": 0,
                //         "last_access_at": null
                //     },
                //     {
                //         "id": 2,
                //         "username": "test1234",
                //         "nickname": "test1234",
                //         "token": null,
                //         "balance": 0,
                //         "last_access_at": null
                //     }
                // ]
                $arrUser = json_decode($curlResult['body'], true);
                $arrResult['users'] = $arrUser;
                $arrResult['status'] = 1;
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

    public function createUser($fid, $nickname)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0);
        }

        $uid = createGameId(substr($_ENV['app.gm_prefix'], 0, 2).$fid);

        $url = $this->mHost."/user/create";
        $url.= "?username=$uid&nickname=$nickname";
        
        $logHead = "<ApiTreem_Lib> createUser() ";
        // writeLog($logHead."url=".$url);

        $header =  $this->getHeader();
        $post = "";
        $curlResult = getCurlRequestWithProxy($url, $header, $post);
        // writeLog($logHead."curl=".json_encode($curlResult));

		if(!is_null($curlResult) && array_key_exists("code", $curlResult)) {
			if($curlResult['code'] == HTTP_CODE_200){
                // $curlResult['body'] =>
                // "id": 1,
                // "username": "test123",
                // "nickname": "test123",
                // "country": "KOR",
                // "currency_code": "KRW",
                // "token": null,
                // "balance": 0,
                // "point": 0,
                // "created_at": "2021-07-03T15:16:53.000000Z",
                // "updated_at": "2021-07-03T15:16:53.000000Z",
                // "last_access_at": null,
                // "agent_id": 1
                $arrResult = json_decode($curlResult['body'], true);
				if(array_key_exists('username', $arrResult) && array_key_exists('nickname', $arrResult)) {
				    $arrResult['status'] = 1;
                } else {
                    writeLog($logHead."Error response=".$curlResult['body']);
                    $arrResult['status'] = 0;
                }
            } else { //
                // $curlResult['body'] =>
                // 
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

    public function gameUrl($uid, $nickname, $vendor, $gameId)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0);
        }

        $url = $this->mHost."/game-launch-link";
        $url.= "?username=$uid&nickname=$nickname";
        $url.= "&vendor=$vendor&game_id=$gameId";
        
        $logHead = "<ApiTreem_Lib> gameUrl() ";
        // writeLog($logHead."url=".$url);

        $header =  $this->getHeader();
        $curlResult = getCurlRequestWithProxy($url, $header);
        // writeLog($logHead."curl=".json_encode($curlResult));

		if(!is_null($curlResult) && array_key_exists("code", $curlResult)) {
			if($curlResult['code'] == HTTP_CODE_200){
                // $curlResult['body'] =>
                // "user": {
                //     "username": "username",
                //     "nickname": "nickname",
                //     "balance": 0,
                //     "last_access_at": "2023-02-22T08:32:17.000000Z",
                //     "token": "aI1tmH6EQSiK0rflv4RwAXxvsH6mb7qsKvv7Yp0W"
                // },
                // "userCreate": true,
                // "link": "https://link/?token=123"
                $arrResult = json_decode($curlResult['body'], true);
				if(array_key_exists('link', $arrResult)) {
				    $arrResult['status'] = 1;
                } else {
                    writeLog($logHead."Error response=".$curlResult['body']);
                    $arrResult['status'] = 0;
                }
            } else { //
                // $curlResult['body'] =>
                // 
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


    public function addBalance($uid, $amount)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }
        $amount = intval($amount);

        $url = $this->mHost."/user/add-balance";
        $url.= "?username=$uid&amount=$amount";

        $logHead = "<ApiTreem_Lib> addBalance() ";

        $header =  $this->getHeader();
        // writeLog($logHead.json_encode($header));
        $post = "";
        $curlResult = getCurlRequestWithProxy($url, $header, $post);
        // writeLog($logHead."curl=".json_encode($curlResult));

		if(!is_null($curlResult) && array_key_exists("code", $curlResult)) {
			if($curlResult['code'] == HTTP_CODE_200){
            // Success =>
                // "username": "e01",
                // "balance": 30000,
                // "amount": 30000,
                // "transaction_id": 86355697028,
                // "cached": false

                $arrResult = json_decode($curlResult['body'], true);
				if(array_key_exists('balance', $arrResult) && array_key_exists('amount', $arrResult)) {
                    $arrResult['status'] = 1;
                } else {
                    writeLog($logHead."Error response=".$curlResult['body']);
                    $arrResult['status'] = 0;
                }
            } else { //
                // $curlResult['body'] =>
                // "message": "username은(는)가 존재하지 않습니다.",
                // "errors": {
                //     "username": [
                //         "username은(는)가 존재하지 않습니다."
                //     ]
                // }
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


    public function subBalance($uid, $amount, $bAll=false)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'error'=>INTERNAL_ERROR);
        }

        if($bAll){
            $url = $this->mHost."/user/sub-balance-all";
            $url.= "?username=$uid";
        }
        else {
            $amount = intval($amount);
            $url = $this->mHost."/user/sub-balance";
            $url.= "?username=$uid&amount=$amount";
        }

        $logHead = "<ApiTreem_Lib> subBalance() ";

        $header =  $this->getHeader();
        // writeLog($logHead.json_encode($header));
        $post = "";
        $curlResult = getCurlRequestWithProxy($url, $header, $post);
        // writeLog($logHead."curl=".json_encode($curlResult));

		if(!is_null($curlResult) && array_key_exists("code", $curlResult)) {
			if($curlResult['code'] == HTTP_CODE_200){
                // "username": "test1",
                // "balance": 0,
                // "amount": -1000,
                // "transaction_id": 1,
                // "cached": false

                // "username": "e01",
                // "balance": 0,
                // "amount": 0,
                // "message": "유저의 잔액이 0원입니다.",
                // "cached": false
                $arrResult = json_decode($curlResult['body'], true);
                if($arrResult['amount'] != 0) {
                    $arrResult['status'] = 1;
                } else {
                    writeLog($logHead."Error response=".$curlResult['body']);
                    $arrResult['status'] = 0;
                }
            } else { 
                // $curlResult['body'] =>
                // "username": "test1",
                // "balance": 5000,
                // "requested_amount": 10000,
                // "message": "유저의 잔액이 부족합니다.",
                // "error": "요청한 금액이 유저의 잔액보다 큽니다.",
                // "cached": false
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