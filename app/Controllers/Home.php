<?php

namespace App\Controllers;

use App\Models\MemConf_Model;
use App\Models\SlotPrd_Model;
use App\Models\SlotGame_Model;
use App\Models\Captcha_Model;
use App\Models\Domain_Model;

class Home extends BaseController
{
    public function index()
    {

        $this->setLanguage();
        $headInfo = $this->getSiteConf();
        $headInfo['lang'] = $this->session->lang;
        if($_ENV['app.name'] == APP_ATM && strpos($_SERVER['HTTP_HOST'], "xn--hi5b6a25g9xy.com") === 0){
		    $this->response->redirect(site_furl('/domain'));
        } else if(!is_login(true) && array_key_exists('app.login', $_ENV) && $_ENV['app.login'] == 1){
            echo view('home/login', $headInfo);
        } else {
            $objMember = null;
            $charges = [];
            $dischars = [];
            $captcha = "";

            if(is_login(true)){
                $user_id = $this->session->user_id;
                $objMember = $this->modelMember->getByUid($user_id);

                $this->sess_action();                
            } else {
                if(array_key_exists('main.jackpot', $_ENV) && $_ENV['main.jackpot'] == 1) {
                    $arrMember = $this->modelMember->getMemberByLevel(LEVEL_ADMIN, true);
                    if(count($arrMember) < 20){
                        $arrMember = generateMembers($arrMember, 10);
                    }
                    $charges = getExchangeList($arrMember, 8);
                    $dischars = getExchangeList($arrMember, 8);
                }

                if(array_key_exists('login.captcha', $_ENV) && $_ENV['login.captcha'] == 1 ){
		            $captchaModel = new Captcha_Model();
                    $captchaSource = PUBLICPATH."captcha_src".DIRECTORY_SEPARATOR;
                    $captchaPath = PUBLICPATH."download".DIRECTORY_SEPARATOR."captcha".DIRECTORY_SEPARATOR;
            
                    $arrCaptcha = [];
                    getFiles($captchaSource, "jpg", $arrCaptcha);
                            
                    $nCount = count($arrCaptcha);
                    $seed = microtime(true);
            
                    if($nCount > 0){
                        
                        mt_srand($seed); 
                        $index = mt_rand(0, $nCount-1);	
                        $captchaSrc = $arrCaptcha[$index];
            
                        $captcha =  $seed;
                        if (!file_exists($captchaPath)) {
                            mkdir($captchaPath, 0777, true);
                        }
                        if(file_exists($captchaPath.$captcha.".jpg")) {
                            unlink($captchaPath.$captcha.".jpg");
                        }
            
                        if( copy($captchaSource.$captchaSrc.".jpg", $captchaPath.$captcha.".jpg") ){
                            $captchaModel->add($captcha, $captchaSrc);
                        }
                    }
                    writeLog("captcha=".$captcha." src=".$captchaSrc);
                }
            }
            
            $navInfo = getNavInfo($objMember);
            $navInfo += $this->casinoPrd($headInfo);
            $navInfo += $this->slotPrd($headInfo);
            $navInfo['charges'] = $charges;
            $navInfo['dischars'] = $dischars;
            $navInfo['captcha'] = $captcha;

            $boards = array();
            $notice_main = '';
            $arrConf = $this->modelConfsite->getNoticeConf();  
            foreach($arrConf as $objConf){
                switch($objConf->conf_id){
                    case CONF_NOTICE_MAIN: $notice_main = $objConf->conf_content;
                        if(array_key_exists('app.lang', $_ENV) && intval($_ENV['app.lang']) > 0 ){
                            if($headInfo['lang'] == "cn" && strlen($objConf->conf_content_cn) > 0 )
                                $notice_main = $objConf->conf_content_cn;
                        }
                        break;
                    case CONF_NOTICE_BANK: 
                        if($objConf->conf_active == STATE_ACTIVE){
                            $board = new \StdClass;
                            $board->notice_title = lang("common.notice_deposit");
                            $board->notice_content = $objConf->conf_content;
                            if(array_key_exists('app.lang', $_ENV) && intval($_ENV['app.lang']) > 0 ){
                                if($headInfo['lang'] == "cn" && strlen($objConf->conf_content_cn) > 0 )
                                    $board->notice_content = $objConf->conf_content_cn;
                            }
                            $board->notice_color = $objConf->conf_idx;
                            $boards[] = $board;
                        }
                        break;
                    case CONF_NOTICE_URGENT: 
                        if($objConf->conf_active == STATE_ACTIVE){
                            $board = new \StdClass;
                            $board->notice_title = lang("common.notice_emergency");
                            $board->notice_content = $objConf->conf_content;
                            if(array_key_exists('app.lang', $_ENV) && intval($_ENV['app.lang']) > 0 ){
                                if($headInfo['lang'] == "cn" && strlen($objConf->conf_content_cn) > 0 )
                                    $board->notice_content = $objConf->conf_content_cn;
                            }
                            $board->notice_color = $objConf->conf_idx;
                            $boards[] = $board;
                        }
                        break;
                    default:break;
                }
            }
    
            $reqData['page'] = 1;
            $reqData['count'] = 4;
            $reqData['popup'] = 1;
            $notices = $this->modelNotice->searchBodList($reqData);
            foreach($notices as $notice){
                $notice->notice_color = '#333';
                if(array_key_exists('app.lang', $_ENV) && intval($_ENV['app.lang']) > 0 ){
                    if($headInfo['lang'] == "cn" && !isEmptyNotice($notice->notice_content_cn) ){
                        $notice->notice_title = $notice->notice_title_cn;
                        $notice->notice_content = $notice->notice_content_cn;
                    }
                }
                $boards[] = $notice;
            }

            $navInfo['notice_main'] = $notice_main;
            $navInfo['boards'] = $boards;
    
            if(!is_null($objMember) && $headInfo['apps_enable'] && array_key_exists('app.sess_act', $_ENV) && $_ENV['app.sess_act'] == 1){

                $memConfModel = new MemConf_Model();
                $memConf = $memConfModel->getByMember($objMember->mb_fid);
                $arrMemInfo = [];
                if(!is_null($memConf) ){
                    $arrMemInfo = explode('#', $memConf->conf_str_1);
                }
                $i=0;
                $showApps = [];
                foreach($headInfo['apps_auto'] as $app){
                    if($app->act == 1 && count($arrMemInfo) > $i){
                        $app->act = intval($arrMemInfo[$i]);
                        if($app->act == 0){
                            $app->path = "";
                        } else {
                            array_push($showApps, $app);
                        }
                    } else if($app->act == 1 && $objMember->mb_level >= LEVEL_ADMIN){
                        array_push($showApps, $app);
                    } else {
                        $app->act = 0;
                        $app->path = "";
                    }
                    $i++;
                }

                $headInfo['apps_auto'] = $showApps;
                if(count($headInfo['apps_auto']) == 0)
                    $headInfo['apps_enable'] = false;
                writeLog("[index] mb_uid=".$objMember->mb_uid." apps_auto=".count($headInfo['apps_auto']));
            }


            $navInfo['part_en'] = true;
            if(array_key_exists('app.hold', $_ENV) && $_ENV['app.hold'] == 1 &&
                !is_null($objMember) && $objMember->mb_level < LEVEL_ADMIN && floatval($objMember->mb_game_hl_ratio) == 0) {
                $navInfo['part_en'] = false;
            }

            echo view('home/main', $headInfo+$navInfo);
        }
    }

	public function domain(){
        if($_ENV['app.name'] == APP_ATM){
            $headInfo = $this->getSiteConf();
        
            $domainModel = new Domain_Model();
            $domains = [];
            $arrDomain = $domainModel->search();
            foreach($arrDomain as $objDomain){
                array_push($domains, $objDomain->conf_domain);
            }
    
            $headInfo['check_domain'] = "에이티엠.com";
            $headInfo['height'] = count($domains) * 60 + 230;
            $headInfo['domains'] = $domains;
            echo view('home/domain', $headInfo);
        } else 
		    $this->response->redirect(site_furl('/'));
        
	}

	public function getaddr(){
		$ip = $this->request->getIPAddress();
		echo "IP ADDRESS is <".$ip.">.";
	}

	public function logout(){

		$sess_id = $this->session->session_id;
		writeLog("[home] logout (".$sess_id.")");
        
		$this->sess_destroy();
		$this->response->redirect(site_furl('/'));
	}

    public function loginip(){
		$this->setLanguage();
        $headInfo = $this->getSiteConf();

        if(!is_login(true)){
            echo view('home/loginip', $headInfo);
        } else {
            $this->response->redirect(site_furl('/'));
        }
	}


    public function mypage()
    {
		$this->setLanguage();
        if($_ENV['app.name'] == APP_ATM && strpos($_SERVER['HTTP_HOST'], "xn--hi5b6a25g9xy.com") === 0){
		    $this->response->redirect(site_furl('/domain'));
        } else if(!is_login(true)){
            print "<script> alert('".lang("common.session_expired")."'); self.close(); </script>";
        } else{
            $this->sess_action();                

            $tab = $this->request->getVar('tab');
            $user_id = $this->session->user_id;
            $objMember = $this->modelMember->getByUid($user_id);
            $navInfo = getNavInfo($objMember);
            $navInfo['lang'] = $this->session->lang;

            if($tab != "my_qna" && $tab != "my_memo" && $tab != "notice" && $tab != "my_point"){
                $tab = "my_info";
            }
            $navInfo['tab'] = $tab;

            $tmNow = time();
            $navInfo['start_at'] = date('Y-m-d', strtotime("-1 month", $tmNow));
            $navInfo['end_at'] = date('Y-m-d', $tmNow);

            $arrSoundConf = $this->modelConfsite->getSoundConf();  
            $navInfo['alarm_name'] = $arrSoundConf[3]->conf_content;
            $navInfo['alarm_volume'] = $arrSoundConf[3]->conf_active;

            echo view('home/mypage', $navInfo);
        }

    }

	public function pt_login(){
		
        $this->response->redirect(site_furl("/pt"));
        // else {
		// 	$port = intval($_SERVER['SERVER_PORT']);
		// 	if($port > 0)
		// 		$port += 1;
		// 	else $port = '81';
		// 	$this->response->redirect('http://'.$_SERVER['SERVER_NAME'].':'.$port);
		// }
		
	}
}
