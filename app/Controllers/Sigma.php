<?php namespace App\Controllers;

class Sigma extends BaseController
{

	public function api($subUrl=""){
        
        $reqtData = file_get_contents("php://input");

        $headers = apache_request_headers();
        writeLog("[sigma_api] subUrl=".$subUrl." headers====");
        $headers = getallheaders();
        foreach ($headers as $name => $value) {
            writeLog("$name: $value");
        }
        writeLog("[sigma_api] subUrl=".$subUrl." headers==========");

        writeLog("[sigma_api] subUrl=".$subUrl." reqData=".$reqtData);

        $arrResult['code'] = RESULT_OK;			
        $arrResult['status'] = STATUS_SUCCESS;
    
        if($subUrl == "balance"){
            echo "1000";
        } else if($subUrl == "url"){
            $this->req_agent();
        } else {
            echo json_encode($arrResult);
        }

    }

    public function req_agent(){

        $currentUrl = current_url();
        echo $currentUrl;
        // $domain = "";
        // $url = "$domain/my-info";

        // writeLog("[sigma_req_agent] url=".$url);

        // $Authorization = "";
        // $header = ['Content-Type: application/json',
        //     'Accept: application/json',
        //     'Authorization: Bearer '.$Authorization
        // ];
        // $response = getCurlRequest2($url, $header);

        // var_dump($response);
    }

}