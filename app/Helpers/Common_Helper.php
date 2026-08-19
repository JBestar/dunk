<?php

  	// function is_login(){ 
    //   if(!isset($_SESSION['logged_in']))
    //     return false;
    //   else if(!isset($_COOKIE['logged']))
    //     return false;
    //   else if( $_SESSION['logged_in']==TRUE && $_COOKIE['logged'] === 'yes')
    //     return true;
    //   else return false;  
  	// }

		function is_login($checkCookie = false){ 
			if(!isset($_SESSION['logged_in']))
				return false;
			else if($checkCookie && !isset($_COOKIE['logged']))
				return false;
			else if($checkCookie && $_COOKIE['logged'] !== 'yes')
				return false;
			else if($_SESSION['logged_in'] == TRUE)
				return true;
			else return false;  
		}

    function is_Mobile(){
      $useragent=$_SERVER['HTTP_USER_AGENT'];
      if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
        return true;
      return false;
    }

    //사이드바의 선택상태 초기화 배렬을 반환해주는 함수
    function getSidebarLinkArray(){

      return [
               'mb_level' => '0',
               'confdropdownbtn' => " main-dropdown-active-btn",
               'confdropdown' => "style=\"display:block\"", 
               'conf_site' => '',
               'conf_game' => '',
               'conf_other' => '',
               'conf_password' => '',
               'conf_follow' => '',
               'conf_ebal' => '',
               'conf_pbal' => '',
               'userdropdownbtn' => " main-dropdown-active-btn",
               'userdropdown' => "style=\"display:block\"", 
               'user_member' => '',
               'user_ctrl' => '',
               'user_ctrl2' => '',
               'user_log' => '',
               'user_ip' => '',
               'user_block' => '',
               'bankdropdownbtn' => " main-dropdown-active-btn",
               'bankdropdown' => "style=\"display:block\"", 
               'bank_deposit' => '',
               'bank_withdraw' => '',
               'bank_point' => '',
               'bank_exchange' => '',
               'bank_transfer' => '',
               'resultdropdownbtn' => " main-dropdown-active-btn",
               'resultdropdown' => "style=\"display:block\"",
               'gameresult' => '',
               'gameedit' => '',
               'betdropdownbtn' => " main-dropdown-active-btn",
               'betdropdown' => "style=\"display:block\"", 
               'bet_realtime' => '',
               'bet_history' => '',
               'bet_calculate' => '',
               'boarddropdownbtn' => " main-dropdown-active-btn",
               'boarddropdown' => "style=\"display:block\"", 
               'board_notice' => '',
               'board_event' => '',
               'board_message' => ''
      ];

    }
    
    //회차유효성 체크 함수
    function isEnablePbRound(&$arrRoundInfo, $roundMin){
      
      if($arrRoundInfo['round_num'] < 1 || $arrRoundInfo['round_num'] > 1440/$roundMin)
        return false;
      $tempDate = explode('-', $arrRoundInfo['round_date']);
  
      if(!checkdate($tempDate[1], $tempDate[2], $tempDate[0]))
        return false;

      $nRoundNo = $arrRoundInfo['round_num'];
      $nSumMinutes = $nRoundNo * $roundMin ;
      $nHour = $nSumMinutes / 60;
      $nHour = (int)$nHour;
      $nMinute = $nSumMinutes % 60;
      
      $strNowDate = $arrRoundInfo['round_date'];
      
      $strRoundEnd = $strNowDate." ".$nHour.":".$nMinute.":"."0";
      $tmRoundEnd = strtotime($strRoundEnd);
      
      //현재시간설정      
      $tmRoundCurrent = time();
      
      if($tmRoundCurrent < $tmRoundEnd)
        return false;

      //회차 마감시간            
      $arrRoundInfo['round_time'] = date("Y-m-d H:i:s", $tmRoundEnd);        
      
      return true;
    }


    //회차번호로부터 회차시작시간과 마감시간, 배팅초과시간 계산하는 함수-파워볼, 파워사다리
    function getPbRoundInfo($bAdvance = false){

      $tmNow = time()+($bAdvance ? ADVANCE_SEC:0);
      
      $nHour = date("G",$tmNow);
      $nMin = date("i",$tmNow);

      $nSumMinutes = $nHour * 60 + $nMin ;
      $nRoundNo = floor($nSumMinutes / 5) ;
      $nRoundNo = $nRoundNo % 288 + 1;
      $arrRoundInfo['round_no'] = $nRoundNo;

      $strDate = "";
      if($nSumMinutes < 1440){
        $strDate = date( 'Y-m-d', $tmNow );
      }
      else {
        $strDate = date('Y-m-d', strtotime("+1 day", $tmNow));
      }

      $nSumMinutes = $nRoundNo * 5 ;
      $nHour = $nSumMinutes / 60;
      $nHour = floor($nHour);
      $nMinute = $nSumMinutes % 60;

      //현재시간설정      
      $tmRoundCurrent = date("Y-m-d H:i:s", $tmNow);        
      $arrRoundInfo['round_current'] = $tmRoundCurrent;

      //회차 마감시간설정
      $strRoundEnd = $strDate." ".$nHour.":".$nMinute.":"."0";
      $tmRoundEnd = strtotime($strRoundEnd);
      if($bAdvance)
        $tmRoundEnd -= ADVANCE_SEC;
      $arrRoundInfo['round_end'] = date("Y-m-d H:i:s", $tmRoundEnd);
      
      //회차 시작시간설정
      $tmRoundStart = strtotime("-5 minutes", $tmRoundEnd);
      $arrRoundInfo['round_start'] = date("Y-m-d H:i:s", $tmRoundStart);
      
      return $arrRoundInfo;
    }

     
    //회차번호로부터 회차시작시간과 마감시간, 배팅초과시간 계산하는 함수-보글파워볼, 보글사다리
    function getBRoundInfo($roundMin, $game=0){

      $tmNow = time();
      if($game == GAME_SPKN_BALL)
        $tmNow += 3*60;

      $nHour = date("G",$tmNow);
      $nMin = date("i",$tmNow);
      
      $nSumMinutes = $nHour * 60 + $nMin ;
      $nRoundNo = floor($nSumMinutes / $roundMin) ;
      $nRoundNo = $nRoundNo % (1440/$roundMin) + 1;
      $arrRoundInfo['round_no'] = $nRoundNo;

      $strDate = date( 'Y-m-d', $tmNow );

      $nSumMinutes = $nRoundNo * $roundMin ;
      $nHour = $nSumMinutes / 60;
      $nHour = floor($nHour);
      $nMinute = $nSumMinutes % 60;

      //현재시간설정      
      $tmRoundCurrent = date("Y-m-d H:i:s", $tmNow);        
      $arrRoundInfo['round_current'] = $tmRoundCurrent;

      //회차 마감시간설정
      $strRoundEnd = $strDate." ".$nHour.":".$nMinute.":"."0";
      $tmRoundEnd = strtotime($strRoundEnd);
      if($game == GAME_SPKN_BALL)
        $tmRoundEnd -= (3*60); 
      $arrRoundInfo['round_end'] = date("Y-m-d H:i:s", $tmRoundEnd);
      
      //회차 시작시간설정
      $tmRoundStart = strtotime("-".$roundMin." minutes", $tmRoundEnd);
      $arrRoundInfo['round_start'] = date("Y-m-d H:i:s", $tmRoundStart);
      
      return $arrRoundInfo;
    }

    
    //배팅시간으로부터 정확한 회차날자 얻기
    function getRoundDate($game, $strDateTime){
      //2021-01-33 23:33:33
      if(strlen($strDateTime) < 1)
        return "";
      
      $tmDate = strtotime($strDateTime);
      if($game == GAME_SPKN_BALL)
        $tmDate = strtotime("+3 minutes", $tmDate);

      $strDate = date("Y-m-d", $tmDate);

      return $strDate;

    }

    function getBetTimeRange($arrReqData, $db){
      $sCon = "";
      if (strlen($arrReqData['start']) <=10 && strlen($arrReqData['end']) <= 10) {
          $sCon = " bet_time >= ".$db->escape($arrReqData['start']." 00:00:00")." AND bet_time <= ".$db->escape($arrReqData['end']." 23:59:59");
      } else {
          $sCon = " bet_time >= ".$db->escape($arrReqData['start'].":00")." AND bet_time < ".$db->escape($arrReqData['end'].":00");
      }
      return $sCon;

    }

    
    function getTimeRange($key, $arrReqData, $db){
      $sCon = "";
      if (strlen($arrReqData['start']) <=10 && strlen($arrReqData['end']) <= 10) {
          $sCon = $key." >= ".$db->escape($arrReqData['start']." 00:00:00")." AND ".$key." <= ".$db->escape($arrReqData['end']." 23:59:59");
      } else {
          $sCon = $key." >= ".$db->escape($arrReqData['start'].":00")." AND ".$key." < ".$db->escape($arrReqData['end'].":00");
      }
      return $sCon;
    }

    function getStatRange($keyStart, $keyEnd, $arrReqData, $db){
      $sCon = "";
      if (strlen($arrReqData['start']) <=10 && strlen($arrReqData['end']) <= 10) {
          $sCon = $keyStart." >= ".$db->escape($arrReqData['start']." 00:00:00")." AND ".$keyEnd." <= ".$db->escape($arrReqData['end']." 23:59:59");
      } else {
          $sCon = $keyStart." >= ".$db->escape($arrReqData['start'].":00")." AND ".$keyEnd." < ".$db->escape($arrReqData['end'].":00");
      }
      return $sCon;
    }
    
    function getMemberState($objMember, $iGame){
      if(is_null($objMember))
            return false;
        else if($objMember->mb_state_active != STATE_ACTIVE)
            return false;
        else if($iGame > 0 && getStateByGame($objMember, $iGame) != PERMIT_OK) {
            return false;  
        }
        return true;
    }

    function getStateByGame($objMember, $iGame){

      switch($iGame){
          case GAME_PBG_BALL: return $objMember->mb_game_pb;
          case GAME_EVOL_BALL: return $objMember->mb_game_ps;
          case GAME_SPKN_BALL: return $objMember->mb_game_ks;
          case GAME_CASINO: return $objMember->mb_game_cs;
          case GAME_BOGLE_BALL: return $objMember->mb_game_bb;
          case GAME_BOGLE_LADDER: return $objMember->mb_game_bs;
          case GAME_SLOT_THEPLUS: 
          case GAME_SLOT_GSPLAY: 
          case GAME_SLOT_GOLD: 
          case GAME_SLOT_KGON: 
          case GAME_SLOT_STAR: return $objMember->mb_game_sl;
          case GAME_HOLD_CMS: return $objMember->mb_game_hl;
          case GAME_EOS5_BALL: 
          case GAME_EOS3_BALL:  return $objMember->mb_game_eo;
          case GAME_COIN5_BALL: 
          case GAME_COIN3_BALL:  return $objMember->mb_game_co;
          default: break;
      } 
      return 0;
    }
    
    function allMoney($member){
      $nMoney = 0;
      if(is_null($member))
        return $nMoney;

      $nMoney = floatval($member->mb_money) + $member->mb_live_money + $member->mb_slot_money + $member->mb_fslot_money
         + $member->mb_kgon_money + $member->mb_gslot_money + $member->mb_hslot_money + $member->mb_hold_money 
         + $member->mb_rave_money + $member->mb_treem_money + $member->mb_sigma_money;
      return floor($nMoney); // round($nMoney, NUM_POINT_CNT);
    }
    function allMoneySql($table=""){
      if(strlen($table) > 0)
        $table .= '.';
      return $table."mb_money+".$table."mb_live_money+".$table."mb_slot_money+".$table."mb_fslot_money+"
        .$table."mb_kgon_money+".$table."mb_gslot_money+".$table."mb_hslot_money+".$table."mb_hold_money+"
        .$table."mb_rave_money+".$table."mb_treem_money+".$table."mb_sigma_money";
    }
    function allEggSql($table=""){
      if(strlen($table) > 0)
        $table .= '.';
      return $table."mb_live_money+".$table."mb_slot_money+".$table."mb_fslot_money+".$table."mb_kgon_money+"
        .$table."mb_gslot_money+".$table."mb_hslot_money+".$table."mb_hold_money+".$table."mb_rave_money+"
        .$table."mb_treem_money+".$table."mb_sigma_money";
    }
    function allEgg($member){
      $nMoney = 0;
      if(is_null($member))
        return $nMoney;

      $nMoney = $member->mb_live_money + $member->mb_slot_money + $member->mb_fslot_money 
        + $member->mb_kgon_money + $member->mb_gslot_money + $member->mb_hslot_money + $member->mb_hold_money
        + $member->mb_rave_money + $member->mb_treem_money;
      return $nMoney;
    }

    function siteFurl(){
      return $_ENV['app.furl']."/";
    }

    function site_furl($url){
      if(substr($url, 0, 1) == "/")
        return $_ENV['app.furl'].$url;
      else return $_ENV['app.furl']."/".$url;
    }

    function diffDt($dt1, $dt2){
      return abs( strtotime($dt1) - strtotime($dt2) );
    }

    function isEBalMode($cnt = 0){
      if(array_key_exists('app.ebal', $_ENV) && $_ENV['app.ebal'] > $cnt )
        return true;
      return false;
    }

    function isPBalMode($cnt = 0){
      if(array_key_exists('app.pbal', $_ENV) && $_ENV['app.pbal'] > $cnt )
        return true;
      return false;
    }

    function calcDate($days=0){
      $tmNow = time();

      if($days > 0){
        $tmDate = strtotime("+".$days." days", $tmNow);
      } else if($days < 0){
        $days = 0 - $days;
        $tmDate = strtotime("-".$days." days", $tmNow);
      } else $tmDate =  $tmNow;

      return date("Y-m-d", $tmDate);;    
    }

    function validUserId($userId){
      return preg_match("/^[A-Za-z0-9_]{4,16}$/", $userId);
    }

    function validUserPw($userPw){
      $checkOk = true;
			$pwdLen = strlen($userPw);
      if($pwdLen < 8 || $pwdLen > 20 )
				$checkOk = false;

      if($checkOk)
        $checkOk = preg_match("/^[A-Za-z0-9\{\}\[\]\/?.,;:|\)\*~`!\^\-_\+<>@\#\$%&\\\=\(\'\"]{8,20}/", $userPw); //^[a-zA-Z\\d`~!@#$%^&*()-_=+]{8,20}$
      
      if($checkOk)
        $checkOk = preg_match("/[\{\}\[\]\/?.,;:|\)\*~`!\^\-_\+<>@\#\$%&\\\=\(\'\"]+/", $userPw); //^[a-zA-Z\\d`~!@#$%^&*()-_=+]{8,20}$
      return $checkOk;
    }
    
    function num_format($numVal,$afterPoint=2,$minAfterPoint=0,$thousandSep=",",$decPoint="."){
      // Same as number_format() but without unnecessary zeros.
      $ret = number_format($numVal,$afterPoint,$decPoint,$thousandSep);
      if($afterPoint!=$minAfterPoint){
        while(($afterPoint>$minAfterPoint) && (substr($ret,-1) =="0") ){
          // $minAfterPoint!=$minAfterPoint and number ends with a '0'
          // Remove '0' from end of string and set $afterPoint=$afterPoint-1
          $ret = substr($ret,0,-1);
          $afterPoint = $afterPoint-1;
        }
      }
      if(substr($ret,-1)==$decPoint) {$ret = substr($ret,0,-1);}
      return $ret;
    }

    function followRates(){
      return [10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 200, 300, 400, 500];
    }
    
    function pressCounts(){
      return [-1, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 20, 30, 40, 50];
    }

    function overBetRates(){
      return [10, 20, 30, 40, 50, 60, 70, 80, 90, 100];
    }

    function removeHostUrl($content){
      
      $urlHost = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https://'.$_SERVER['HTTP_HOST'] : 'http://'.$_SERVER['HTTP_HOST'];

      return str_replace('src="'.$urlHost."//", 'src="/', $content);

    }

    function getKeyObj($key, $range){
      $objCas = new \StdClass;
        
      $objCas->key = $key;
      $objCas->value = $range;

      return $objCas;
    }
    function getRangeKeys($gameId){
      
      $arrBatch = array();

      if($gameId == GAME_CASINO_KGON){
        $arrCas = array();
        array_push($arrCas, getKeyObj("top_games", "1,000~5,000,000") );
        array_push($arrCas, getKeyObj("top_games2", "1,000~10,000,000") );
        array_push($arrCas, getKeyObj("top_games3", "1,000~30,000,000") );
        array_push($arrCas, getKeyObj("top_games4", "1,000~50,000,000") );
        array_push($arrCas, getKeyObj("top_games5", "1,000~100,000,000") );
        $arrBatch['evolution_casino'] = $arrCas;

        $arrCas = array();
        array_push($arrCas, getKeyObj("lobby100", "1,000~1,000,000") );
        array_push($arrCas, getKeyObj("lobby300", "1,000~3,000,000") );
        array_push($arrCas, getKeyObj("lobby", "1,000~5,000,000") );
        array_push($arrCas, getKeyObj("lobby1000", "1,000~10,000,000") );
        array_push($arrCas, getKeyObj("lobby2000", "1,000~20,000,000") );
        $arrBatch['dreamgaming_casino'] = $arrCas;

        $arrCas = array();
        array_push($arrCas, getKeyObj("bota_casino1", "10,000~1,000,000") );
        array_push($arrCas, getKeyObj("bota_casino2", "10,000~3,000,000") );
        array_push($arrCas, getKeyObj("bota_casino3", "10,000~5,000,000") );
        array_push($arrCas, getKeyObj("bota_casino4", "10,000~10,000,000") );
        array_push($arrCas, getKeyObj("bota_casino6", "10,000~30,000,000") );
        $arrBatch['bota_casino'] = $arrCas;

        $arrCas = array();
        array_push($arrCas, getKeyObj("lobby100", "10,000~1,000,000") );
        array_push($arrCas, getKeyObj("lobby300", "10,000~3,000,000") );
        array_push($arrCas, getKeyObj("lobby500", "10,000~5,000,000") );
        array_push($arrCas, getKeyObj("lobby1000", "10,000~10,000,000") );
        array_push($arrCas, getKeyObj("lobby2000", "10,000~20,000,000") );
        array_push($arrCas, getKeyObj("lobby3000", "10,000~30,000,000") );
        array_push($arrCas, getKeyObj("lobby5000", "10,000~50,000,000") );
        $arrBatch['duwon_casino'] = $arrCas;

        $arrCas = array();
        array_push($arrCas, getKeyObj("lobby100", "10,000~1,000,000") );
        array_push($arrCas, getKeyObj("lobby300", "30,000~3,000,000") );
        array_push($arrCas, getKeyObj("lobby100", "50,000~5,000,000") );
        array_push($arrCas, getKeyObj("lobby1000", "100,000~10,000,000") );
        $arrBatch['wm_casino'] = $arrCas;
      
        $arrCas = array();
        array_push($arrCas, getKeyObj("lobby100", "1,000~1,000,000") );
        array_push($arrCas, getKeyObj("lobby300", "1,000~3,000,000") );
        array_push($arrCas, getKeyObj("lobby500", "1,000~5,000,000") );
        array_push($arrCas, getKeyObj("lobby1000", "1,000~10,000,000") );
        array_push($arrCas, getKeyObj("lobby3000", "1,000~30,000,000") );
        array_push($arrCas, getKeyObj("lobby5000", "1,000~50,000,000") );
        $arrBatch['orientalgame_casino'] = $arrCas;

        $arrCas = array();
        array_push($arrCas, getKeyObj("lobby10", "1,000~100,000") );
        array_push($arrCas, getKeyObj("lobby50", "2,000~500,000") );
        array_push($arrCas, getKeyObj("lobby150", "3,000~1,500,000") );
        array_push($arrCas, getKeyObj("lobby200", "5,000~2,000,000") );
        array_push($arrCas, getKeyObj("lobby1000", "10,000~10,000,000") );
        array_push($arrCas, getKeyObj("lobby2000", "20,000~20,000,000") );
        $arrBatch['bg_casino'] = $arrCas;

        $arrCas = array();
        array_push($arrCas, getKeyObj("lobby100", "1,000~1,000,000") );
        array_push($arrCas, getKeyObj("lobby300", "1,000~3,000,000") );
        array_push($arrCas, getKeyObj("lobby500", "5,000~5,000,000") );
        array_push($arrCas, getKeyObj("lobby1000", "5,000~10,000,000") );
        array_push($arrCas, getKeyObj("lobby2000", "10,000~20,000,000") );
        array_push($arrCas, getKeyObj("lobby3000", "10,000~30,000,000") );
        array_push($arrCas, getKeyObj("lobby5000", "25,000~50,000,000") );
        array_push($arrCas, getKeyObj("lobby10000", "50,000~100,000,000") );
        $arrBatch['vegas_casino'] = $arrCas;

      } else if($gameId == GAME_CASINO_TREEM){
        $arrCas = array();
        array_push($arrCas, getKeyObj("2", "10,000~10,000,000") );
        array_push($arrCas, getKeyObj("1", "10,000~20,000,000") );
        array_push($arrCas, getKeyObj("6", "10,000~40,000,000") );
        array_push($arrCas, getKeyObj("7", "10,000~60,000,000") );
        array_push($arrCas, getKeyObj("8", "10,000~100,000,000") );
        array_push($arrCas, getKeyObj("9", "10,000~200,000,000") );
        $arrBatch['evolution'] = $arrCas;

        $arrCas = array();
        array_push($arrCas, getKeyObj("pp20", "1,000~20,000,000") );
        array_push($arrCas, getKeyObj("pp50", "1,000~50,000,000") );
        array_push($arrCas, getKeyObj("pp100", "1,000~100,000,000") );
        $arrBatch['PragmaticPlay Live'] = $arrCas;
      }
      
      return $arrBatch;
    }

    function getConfById($arrConf, $configId){
      
      $objConfig = null;
      foreach($arrConf as $objConf){
        if($objConf->conf_id == $configId){
          $objConfig = $objConf;
          break;
        }
      }
      return $objConfig;
    }

    function generateString($length, $seed)  
    {  
        // $characters  = "0123456789";  
        $characters = "abcdefghijklmnopqrstuvwxyz";  
        // $characters .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";  
        
        $randStr = "";  
        $nmr_loops = $length;  
        
        while ($nmr_loops--)  
        {  
            mt_srand($seed++);
            $randStr .= $characters[mt_rand(0, strlen($characters) - 1)];  
        }  
        
        return $randStr;  
    }  
    
    function generateExtId(){
      $seed = microtime(true); //1709876543.123456 - 16 digits
      
      $randStr = generateString(48, $seed);
      $secs = intval($seed);
      return $randStr.intval($seed).intval(($seed-$secs)*1000000);
    }

?>
