<?php

	include_once('libraries/Snoopy.php');
	include_once('helpers/Constant.php');
	include_once('helpers/Logic_Helper.php');
	include_once('helpers/MY_Helper.php');
	include_once('ServiceLogic.php');
	
	//서버가 기동할 동안 대기
	sleep(1);

	date_default_timezone_set('Asia/Seoul');

	$arrConfig = parse_ini_file("config/config.ini");

	//자료기지 접속
	$dbConn = connectDb($arrConfig);
	if ($dbConn->connect_error) {
	    echo "Connection failed.". $dbConn->connect_error;
	    sleep(50);
	    die("Connection failed: ");
	} 

	$appType = 0;
	if(array_key_exists('app_type', $arrConfig)){
		$appType=intval($arrConfig['app_type']);
	}
	$appSlot = 0;
	if(array_key_exists('app_slot', $arrConfig)){
		$appSlot=$arrConfig['app_slot'];
	}
	$appFslot = 0;
	if(array_key_exists('app_fslot', $arrConfig)){
		$appFslot=$arrConfig['app_fslot'];
	}
	$appCasino = 0;
	if(array_key_exists('app_casino', $arrConfig)){
		$appCasino=$arrConfig['app_casino'];
	}
	$proxyUrl = "";
	if(array_key_exists('proxy_url', $arrConfig)){
		$proxyUrl=$arrConfig['proxy_url'];
	}

	$tRootDir = dirname(__FILE__);
	
	if(!is_dir($tRootDir."/log")){
		mkdir($tRootDir."/log");
	}
	
    $fName = date( 'Y-m-d', time());
	$fLog = fopen($tRootDir."/log/acc_".$fName, "a") ;

	sleep(1);
	$objServLogic = new ServiceLogic($dbConn, $fLog);
	$bSlDeny = $objServLogic->getSiteConf(CONF_SLOT_DENY);
	$bCsDeny = $objServLogic->getSiteConf(CONF_CAS_DENY);
	
	//정산상태 
	$bPlus = false;
	$bKgon = false;
	$bStar = false;
	$bGsplay = false;
	$bGold = false;
	$bRave = false;
	$bTreem = false;
	$bSigmaCs = false;
	$bSigmaSl = false;

	if(!$bSlDeny){
		if($appType == APP_TYPE_1 || $appType == APP_TYPE_3){
			if($appSlot == APP_SLOT_THEPLUS)
				$bPlus = true;
			else if($appSlot == APP_SLOT_KGON)
				$bKgon = true;
			else if($appSlot == APP_SLOT_STAR)
				$bStar = true;
			else if($appSlot == APP_SLOT_RAVE)
				$bRave = true;
			else if($appSlot == APP_SLOT_TREEM)
				$bTreem = true;
			else if($appSlot == APP_SLOT_SIGMA)
				$bSigmaSl = true;
		}
		if($appType == APP_TYPE_1 || $appType == APP_TYPE_2){
			if($appFslot == APP_FSLOT_GSPLAY)
				$bGsplay = true;
			else if($appFslot == APP_FSLOT_GOLD)
				$bGold = true;
		}
	}
	if(!$bCsDeny){
		if($appCasino == APP_CASINO_KGON)
			$bKgon = true;
		else if($appCasino == APP_CASINO_STAR)
			$bStar = true;
		else if($appCasino == APP_CASINO_RAVE)
			$bRave = true;
		else if($appCasino == APP_CASINO_TREEM)
			$bTreem = true;
		else if($appCasino == APP_CASINO_SIGMA)
			$bSigmaCs = true;
	}
	

	$hGold = null;
	$hKgon = null;
	$hPlus = null;
	$hGsplay = null;
	$hStar = null;
	$hRave = null;
	$hTreem = null;
	$hSigma = null;

	$bGoldReg = false;
	$bKgonReg = false;
	$bPlusReg = false;
	$bGsplayReg = false;
	$bStarReg = false;
	$bRaveReg = false;
	$bTreemReg = false;
	$bSigmaReg = false;

	$ordGsplay = 0;
	$logHead = "<History>";
	
	$secSleep = 61;
	$secRepeat = 61;
	$tmTreemRetry = 0;
	$nTreemBackoff = 0;
	
	writeLog($fLog, $logHead."==============START==============");

	writeLog($fLog, $logHead."ThePlus=".$bPlus." KGON=".$bKgon." GSPlay=".$bGsplay." STAR=".$bStar
		." GOLD=".$bGold." RAVE=".$bRave." TREEM=".$bTreem." SIGMA=".$bSigmaCs );

	while(true){

		$tmNow = time();
		$nHour = date("G",$tmNow);
		$nMin = date("i",$tmNow);
		$nSec = date("s",$tmNow);

		//로그파일 
		if($nHour == 0 && $nMin == 0 && $nSec < $secSleep){

			$strDate = date( 'Y-m-d', $tmNow );
			if($fName !== $strDate){
				if($fLog) fclose($fLog);
				
				$fName = $strDate;
				$fLog = fopen($tRootDir."/log/acc_".$fName, "a") ;
		
				$objServLogic->fLog = $fLog;
				writeLog($fLog, $logHead."Log File--".$fName."");
			}
		}
		
		$nMinSum = $nHour*60 + $nMin;
		$nSecSum = ($nMin%10)*60 + $nSec;
		
		$bInsert = false;
				
		//THE PLUS
		if($bPlus && !$bPlusReg){
			if($hPlus == null){
				$hPlus = curl_multi_init();
				$curl = $objServLogic->curlSlotBets();
				if($curl)
					curl_multi_add_handle($hPlus, $curl);
				else {
					$hPlus = null;
					$bPlusReg = true;
				}
				writeLog($fLog, $logHead."THEPLUS-REQ-".$hPlus);
			}

			if($hPlus){
				$result = curlProc($hPlus, $fLog );
				if($result != null){
					$bPlusReg = true;
					// writeLog($fLog, $result);
					$bInsert = $objServLogic->registerSlotBets($result);
				}
			}
		}  
		
		//KGON
		if($bKgon && !$bKgonReg){
			if($hKgon == null){
				$hKgon = curl_multi_init();
				$curl = $objServLogic->curlKgonBets();
				if($curl)
					curl_multi_add_handle($hKgon, $curl);
				else {
					$hKgon = null;
					$bKgonReg = true;
				}
				writeLog($fLog, $logHead."KGON-REQ-".$hKgon);
			}
			if($hKgon){
				$result = curlProc($hKgon, $fLog );
				if($result != null){
					$bKgonReg = true;
					// writeLog($fLog, $result);
					$bInsert = $objServLogic->registerKgonBets($result);
				}
			}
		} 

		//GSPLAY
		if($bGsplay && !$bGsplayReg){
			if($hGsplay == null){
				$hGsplay = curl_multi_init();
				$curl = $objServLogic->curlFslotBets($ordGsplay);
				if($curl)
					curl_multi_add_handle($hGsplay, $curl);
				else {
					$hGsplay = null;
					$bGsplayReg = true;
				}
				writeLog($fLog, $logHead."GSPLAY-REQ-".$hGsplay);
			}
			if($hGsplay){
				$result = curlProc($hGsplay, $fLog );
				if($result != null){
					$bGsplayReg = true;
					// writeLog($fLog, $result);
					$bInsert = $objServLogic->registerFslotBets($result, $ordGsplay);
				}
			}
		} 

		//Star
		if($bStar && !$bStarReg){
			if($hStar == null){
				$hStar = curl_multi_init();
				$curl = $objServLogic->curlStarBets();
				if($curl)
					curl_multi_add_handle($hStar, $curl);
				else {
					$hStar = null;
					$bStarReg = true;
				}
				writeLog($fLog, $logHead."STAR-REQ-".$hStar);
			}
			if($hStar){
				$result = curlProc($hStar, $fLog );
				if($result != null){
					$bStarReg = true;
					// writeLog($fLog, $result);
					$bInsert = $objServLogic->registerStarBets($result);
				}
			}
		} 

		//Gold
		if($bGold && !$bGoldReg){
			if($hGold == null){
				$hGold = curl_multi_init();
				$curl = $objServLogic->curlGslotBets();
				if($curl)
					curl_multi_add_handle($hGold, $curl);
				else {
					$hGold = null;
					$bGoldReg = true;
				}
				writeLog($fLog, $logHead."GOLD-REQ-".$hGold);
			}
			if($hGold){
				$result = curlProc($hGold, $fLog );
				if($result != null){
					$bGoldReg = true;
					// writeLog($fLog, $result);
					$bInsert = $objServLogic->registerGslotBets($result);
				}
			}
		} 

		//RAVE
		if($bRave && !$bRaveReg){
			if($hRave == null){
				$hRave = curl_multi_init();
				$curl = $objServLogic->curlRaveBets();
				if($curl)
					curl_multi_add_handle($hRave, $curl);
				else {
					$hRave = null;
					$bRaveReg = true;
				}
				writeLog($fLog, $logHead."RAVE-REQ-".$hRave);
			}
			if($hRave){
				$result = curlProc2($hRave, $fLog );
				if($result != null){
					$bRaveReg = true;
					$bInsert = $objServLogic->registerRaveBets($result);
				}
			}
		}

		//Treem
		if($bTreem && !$bTreemReg){
			if($tmNow < $tmTreemRetry){
				$bTreemReg = true;
				$hTreem = null;
			} else if($hTreem == null){
				$hTreem = curl_multi_init();
				$curl = $objServLogic->curlTreemBetsRecentHourUtc($proxyUrl);
				if($curl)
					curl_multi_add_handle($hTreem, $curl);
				else {
					$hTreem = null;
					$bTreemReg = true;
				}
				writeLog($fLog, $logHead."TREEM-REQ-".$hTreem);
			}
			if($hTreem){
				$result = curlProc2($hTreem, $fLog );
				if($result != null){
					$bTreemReg = true;
					$nTreemCode = 0;
					if(array_key_exists('code', $result))
						$nTreemCode = intval($result['code']);
					if($nTreemCode == HTTP_CODE_429){
						$nTreemBackoff = min($nTreemBackoff + TREEM_MIN_INTERVAL, TREEM_BACKOFF_MAX);
						$tmTreemRetry = time() + $nTreemBackoff;
						writeLog($fLog, $logHead."TREEM 429 backoff=".$nTreemBackoff." next=".date('H:i:s', $tmTreemRetry));
					} else {
						$bInsert = $objServLogic->registerTreemBetsSafe($result, $proxyUrl);
						$nTreemBackoff = 0;
						$tmTreemRetry = time() + TREEM_MIN_INTERVAL;
					}
				}
			}
		}

		//Sigma
		if(($bSigmaCs || $bSigmaSl) && !$bSigmaReg){
			if($hSigma == null){
				$hSigma = curl_multi_init();
				$curl = $objServLogic->curlSigmaBets($bSigmaCs, $proxyUrl);
				if($curl)
					curl_multi_add_handle($hSigma, $curl);
				else {
					$hSigma = null;
					$bSigmaReg = true;
				}
				writeLog($fLog, $logHead."SIGMA-REQ-".$hSigma);
			}
			if($hSigma){
				$result = curlProc2($hSigma, $fLog );
				if($result != null){
					$bSigmaReg = true;
					$bInsert = $objServLogic->registerSigmaBets($bSigmaCs, $result, $proxyUrl);
				}
			}
		}

		if($hGold == null && $hKgon == null && $hPlus == null && $hGsplay == null && 
			$hStar == null && $hRave == null && $hTreem == null && $hSigma == null){
			$bGoldReg = false;
			$bKgonReg = false;
			$bPlusReg = false;
			$bGsplayReg = false;
			$bStarReg = false;
			$bRaveReg = false;
			$bTreemReg = false;
			$bSigmaReg = false;
			if($bSigmaSl)
				$bSigmaCs = !$bSigmaCs;

			if(!$bInsert)
				sleep($secSleep);
			else sleep($secRepeat);	//usleep(500000);
		}
		
	}
	
	sleep(100);
	
?>