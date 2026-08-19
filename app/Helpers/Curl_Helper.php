<?php

function getCurlRequest($url, $headers = null, $post = null, $timeout = 20){
    
    $timeoutConnect = 5;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);

    if(substr($url, 0, 5) == 'https'){
        if(array_key_exists('app.curl_cainfo', $_ENV) && strlen($_ENV['app.curl_cainfo']) > 0){
		    curl_setopt($curl, CURLOPT_CAINFO, $_ENV['app.curl_cainfo']);
        } else if(DIRECTORY_SEPARATOR == '\\' && file_exists(dirname(__FILE__) . '/cacert.pem')){
		    curl_setopt($curl, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
        }
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
	}
	else
	{
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	}
    
    if (!is_null($post)) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    }
    if (!is_null($headers)) {
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($curl, CURLOPT_USERAGENT, array_key_exists('app.curl_useragent', $_ENV) && strlen($_ENV['app.curl_useragent']) > 0 ? $_ENV['app.curl_useragent'] : 'Mozilla/5.0 (compatible; TEN-Admin/1.0)');
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeoutConnect);
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
    
    $response = curl_exec($curl);

    $result['error'] = "";
    if (curl_errno($curl)) {        
        $result['error'] = curl_error($curl);
        return "";            
    }

    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $result['header'] = substr($response, 0, $header_size);
    $result['body'] = substr( $response, $header_size );

    curl_close($curl);

    return $result['body'];
}

function getCurlRequest2($url, $headers = null, $post = null, $customRequest = null, $timeout = 20){
    
    $timeoutConnect = 5;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);

    if(substr($url, 0, 5) == 'https'){
        if(array_key_exists('app.curl_cainfo', $_ENV) && strlen($_ENV['app.curl_cainfo']) > 0){
		    curl_setopt($curl, CURLOPT_CAINFO, $_ENV['app.curl_cainfo']);
        } else if(DIRECTORY_SEPARATOR == '\\' && file_exists(dirname(__FILE__) . '/cacert.pem')){
		    curl_setopt($curl, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
        }
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
	}
	else
	{
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	}
    
    if($post && !empty($post)) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    }
    if($headers && !empty($headers)) {
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }
    if($customRequest && !empty($customRequest)) {
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $customRequest);
    }

    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($curl, CURLOPT_USERAGENT, array_key_exists('app.curl_useragent', $_ENV) && strlen($_ENV['app.curl_useragent']) > 0 ? $_ENV['app.curl_useragent'] : 'Mozilla/5.0 (compatible; TEN-Admin/1.0)');
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeoutConnect);
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
    
    $response = curl_exec($curl);

    $result['code'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $result['error'] = "";
    if (curl_errno($curl)) {        
        $result['error'] = curl_error($curl);
    }

    if($response === false){
        $response = "";
    }

    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    // $result['header'] = substr($response, 0, $header_size);
    $result['body'] = substr( $response, $header_size );

    curl_close($curl);

    return $result;
}

function getCurlRequestWithProxy($url, $headers = null, $post = null, $customRequest = null, $timeout = 20){
    
    $timeoutConnect = 5;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);

    if(substr($url, 0, 5) == 'https'){
        if(array_key_exists('app.curl_cainfo', $_ENV) && strlen($_ENV['app.curl_cainfo']) > 0){
		    curl_setopt($curl, CURLOPT_CAINFO, $_ENV['app.curl_cainfo']);
        } else if(DIRECTORY_SEPARATOR == '\\' && file_exists(dirname(__FILE__) . '/cacert.pem')){
		    curl_setopt($curl, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
        }
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
	}
	else
	{
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	}

    if( array_key_exists('app.proxy', $_ENV) && strlen($_ENV['app.proxy']) > 10 )
    {
        $proxyType = CURLPROXY_SOCKS5;
        if(array_key_exists('app.proxy_type', $_ENV)){
            if(strtolower($_ENV['app.proxy_type']) == 'http'){
                $proxyType = CURLPROXY_HTTP;
            } else if(strtolower($_ENV['app.proxy_type']) == 'socks5h' && defined('CURLPROXY_SOCKS5_HOSTNAME')){
                $proxyType = CURLPROXY_SOCKS5_HOSTNAME;
            }
        }
        $proxyUrl = $_ENV['app.proxy'];
        $proxyAuth = "jmaster:startend";
        if(array_key_exists('app.proxy_auth', $_ENV) && strlen($_ENV['app.proxy_auth']) > 0){
            $proxyAuth = $_ENV['app.proxy_auth'];
        }
        // Specify proxy type 
        curl_setopt($curl, CURLOPT_PROXYTYPE, $proxyType);
        // Set proxy server and port
        curl_setopt($curl, CURLOPT_PROXY, $proxyUrl);
        // Optional: Proxy authentication
        curl_setopt($curl, CURLOPT_PROXYUSERPWD, $proxyAuth);
    }
    
    if(!is_null($post)) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    } 

    if($headers && !empty($headers)) {
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }
    if($customRequest && !empty($customRequest)) {
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $customRequest);
    }

    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($curl, CURLOPT_USERAGENT, array_key_exists('app.curl_useragent', $_ENV) && strlen($_ENV['app.curl_useragent']) > 0 ? $_ENV['app.curl_useragent'] : 'Mozilla/5.0 (compatible; TEN-Admin/1.0)');
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeoutConnect);
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
    
    $response = curl_exec($curl);

    $result['code'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $result['error'] = "";
    if (curl_errno($curl)) {        
        $result['error'] = curl_error($curl);
    }

    if($response === false){
        $response = "";
    }

    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    // $result['header'] = substr($response, 0, $header_size);
    $result['body'] = substr( $response, $header_size );

    curl_close($curl);

    return $result;
}

function writeLog($contenet){ 
    
    if(!LOG_WRITE)
        return;

    $tmNow = time() ;
    $nHour = date("G",$tmNow);
    $nMin = date("i",$tmNow);
    $nSec = date("s",$tmNow);

    $sDate = date( 'Y-m-d', $tmNow);
    $fLog = fopen(LOG_FILE.$sDate, "a") ;

    $tContent = "[".$nHour.":".$nMin.":".$nSec."] ".$contenet."\r\n";

    fputs($fLog, $tContent);
    fclose($fLog);
}

?>
