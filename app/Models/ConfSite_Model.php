<?php 
namespace App\Models;

use CodeIgniter\Model;

class ConfSite_Model extends Model 
{
    protected $table = 'conf_site';
    protected $allowedFields = ['conf_memo', 'conf_content', 'conf_content_cn', 'conf_active', 'conf_update', 'conf_idx'];
    protected $primaryKey = 'conf_id';
    protected $returnType = 'object'; 

    public function getConf($conf_id)
    {
        return $this->find($conf_id);
    }

    public function getSiteName()
    {

        $objConf = $this->getConf(CONF_SITENAME);
        $strSiteName = "";
        if(!is_null($objConf)){
            $strSiteName = $objConf->conf_content;
        }
        return $strSiteName;
    }

    public function getMaintainConfig(){
        //점검관련 정보
        return $this->getConf(CONF_MAINTAIN);
    }

    
    public function getBetSite(){

        $data = ["", "", "", 0, 0];

        $objConfig = $this->where('conf_id', CONF_BETSITE)->first();
        if(!is_null($objConfig)){
            $info = explode('#', $objConfig->conf_content);
            if(count($info) >= 3){
                $data[0] = $info[0];   
                $data[1] = $info[1];   
                $data[2] = $info[2];   
                $data[3] = $objConfig->conf_active;
                $data[4] = intval($objConfig->conf_idx);
            }
        }

        return $data;
    }


    public function setBetSite($data){
        $arrBatch = array();

        $strContent = "";
        if(strlen($data['site'])<1) 
            $data['site']=" ";
        $strContent .= $data['site']."#";
        
        if(strlen($data['userid'])<1) 
            $data['userid']=" ";    
        $strContent .= $data['userid']."#";
        
        if(strlen($data['userpwd'])<1) 
            $data['userpwd']=" "; 
        $strContent .= $data['userpwd'];
        
        $arrBatch = array();
        $updateData = array();
        $updateData['conf_id'] = CONF_BETSITE;
        $updateData['conf_content'] = $strContent;
        $updateData['conf_active'] = $data['active'];
        $updateData['conf_idx'] = $data['type'];
        $arrBatch[] = $updateData;

        return  $this->builder()->updateBatch($arrBatch, 'conf_id');
    }

    public function getEvolSite($game){

        $arrBatch = array();
        if($game == GAME_AUTO_PRAG){
            $confIds = [CONF_PRAGSITE_1, CONF_PRAGRUN_1, CONF_PRAGSITE_2, CONF_PRAGRUN_2, CONF_PRAGSITE_3, CONF_PRAGRUN_3,
                CONF_PRAGSITE_4, CONF_PRAGRUN_4, CONF_PRAGSITE_5, CONF_PRAGRUN_5, CONF_PRAGSITE_6, CONF_PRAGRUN_6,
                CONF_PRAGSITE_7, CONF_PRAGRUN_7];  
        } else {
            $confIds = [CONF_EVOLSITE_1, CONF_EVOLRUN_1, CONF_EVOLSITE_2, CONF_EVOLRUN_2, CONF_EVOLSITE_3, CONF_EVOLRUN_3,
                CONF_EVOLSITE_4, CONF_EVOLRUN_4, CONF_EVOLSITE_5, CONF_EVOLRUN_5, CONF_EVOLSITE_6, CONF_EVOLRUN_6,
                CONF_EVOLSITE_7, CONF_EVOLRUN_7];  
        }
        $arrConf = $this->find($confIds);
        //0-site, 1-id, 2-pwd, 3-permit, 4-type, 5-end time, 6-팅김베팅, 7-money
        //8-min, 9-max, 10-상태, 11-팅김간격, 12-max user, 13-is signal, 14-connector
        //15-multi room, 16-follower, 17-captcha, 18-code, 19-전체방팅김베팅, 20-보험배팅한도
        $data = array();
        array_push($data, ["", "", "", 0, 0, 0, 0, 0, 0, 0, "", 20, 50, 0, 0, 0, 0, "", "", 0, 0 ]);
        array_push($data, ["", "", "", 0, 0, 0, 0, 0, 0, 0, "", 0, 0, 0, 0, 0, 0, "", "", 0, 0 ]);
        array_push($data, ["", "", "", 0, 0, 0, 0, 0, 0, 0, "", 0, 0, 0, 0, 0, 0, "", "", 0, 0 ]);
        array_push($data, ["", "", "", 0, 0, 0, 0, 0, 0, 0, "", 0, 0, 0, 0, 0, 0, "", "", 0, 0 ]);
        array_push($data, ["", "", "", 0, 0, 0, 0, 0, 0, 0, "", 0, 0, 0, 0, 0, 0, "", "", 0, 0 ]);
        array_push($data, ["", "", "", 0, 0, 0, 0, 0, 0, 0, "", 0, 0, 0, 0, 0, 0, "", "", 0, 0 ]);
        array_push($data, ["", "", "", 0, 0, 0, 0, 0, 0, 0, "", 0, 0, 0, 0, 0, 0, "", "", 0, 0 ]);
        
        $idx = 0;
        foreach($arrConf as $objConf){

			switch($objConf->conf_id){
				case CONF_EVOLSITE_1:	
				case CONF_EVOLSITE_2:	
                case CONF_EVOLSITE_3:	
                case CONF_EVOLSITE_4:	
                case CONF_EVOLSITE_5:	
                case CONF_EVOLSITE_6:	
                case CONF_EVOLSITE_7:
                case CONF_PRAGSITE_1:	
                case CONF_PRAGSITE_2:	
                case CONF_PRAGSITE_3:	
                case CONF_PRAGSITE_4:	
                case CONF_PRAGSITE_5:	
                case CONF_PRAGSITE_6:	
                case CONF_PRAGSITE_7:	
                    if($objConf->conf_id == CONF_EVOLSITE_1 || $objConf->conf_id == CONF_PRAGSITE_1)
                        $idx = 0;
                    else if($objConf->conf_id == CONF_EVOLSITE_2 || $objConf->conf_id == CONF_PRAGSITE_2)
                        $idx = 1;
                    else if($objConf->conf_id == CONF_EVOLSITE_3 || $objConf->conf_id == CONF_PRAGSITE_3)
                        $idx = 2;
                    else if($objConf->conf_id == CONF_EVOLSITE_4 || $objConf->conf_id == CONF_PRAGSITE_4)
                        $idx = 3;
                    else if($objConf->conf_id == CONF_EVOLSITE_5 || $objConf->conf_id == CONF_PRAGSITE_5)
                        $idx = 4;
                    else if($objConf->conf_id == CONF_EVOLSITE_6 || $objConf->conf_id == CONF_PRAGSITE_6)
                        $idx = 5;
                    else if($objConf->conf_id == CONF_EVOLSITE_7 || $objConf->conf_id == CONF_PRAGSITE_7)
                        $idx = 6;
                        
                    $info = explode(';', $objConf->conf_content);
                    if(count($info) >= 3){
                        $data[$idx][0] = $info[0];   
                        $data[$idx][1] = $info[1];   
                        $data[$idx][2] = $info[2];   
                        $objConf->conf_active = intval($objConf->conf_active); 
                        
                        $data[$idx][7] = -3; 
                        if(diffDt(date('Y-m-d H:i:s'), $objConf->conf_update) < 30){
                            $data[$idx][7] = $objConf->conf_active;
                        }
                        
                        $info = explode('#', $objConf->conf_idx);
                        if(count($info) >= 3){
                            $data[$idx][4] = intval($info[0]);
                            $data[$idx][5] = intval($info[1]);
                            $data[$idx][6] = intval($info[2]);
                            if(count($info) >= 5 && $idx == 0){
                                $data[$idx][8] = intval($info[3]);
                                $data[$idx][9] = intval($info[4]);
                                $data[$idx][11] = intval($info[5]);
                                $data[$idx][12] = intval($info[6]);
                            } 
                            if(count($info) >= 10){
                                $data[$idx][13] = intval($info[7]);
                                $data[$idx][15] = intval($info[8]);
                                $data[$idx][19] = intval($info[9]);
                                if(count($info) >= 11)
                                    $data[$idx][20] = intval($info[10]);
                            }
                        }
                    }
                    $data[$idx][17] = $objConf->conf_content_cn;
					break;
				case CONF_EVOLRUN_1:	
				case CONF_EVOLRUN_2:	
                case CONF_EVOLRUN_3:
                case CONF_EVOLRUN_4:
                case CONF_EVOLRUN_5:
                case CONF_EVOLRUN_6:
                case CONF_EVOLRUN_7:
                case CONF_PRAGRUN_1:	
                case CONF_PRAGRUN_2:	
                case CONF_PRAGRUN_3:
                case CONF_PRAGRUN_4:
                case CONF_PRAGRUN_5:
                case CONF_PRAGRUN_6:
                case CONF_PRAGRUN_7:
                    if($objConf->conf_id == CONF_EVOLRUN_1 || $objConf->conf_id == CONF_PRAGRUN_1)
                        $idx = 0;
                    else if($objConf->conf_id == CONF_EVOLRUN_2 || $objConf->conf_id == CONF_PRAGRUN_2)
                        $idx = 1;
                    else if($objConf->conf_id == CONF_EVOLRUN_3 || $objConf->conf_id == CONF_PRAGRUN_3)
                        $idx = 2;
                    else if($objConf->conf_id == CONF_EVOLRUN_4 || $objConf->conf_id == CONF_PRAGRUN_4)
                        $idx = 3;
                    else if($objConf->conf_id == CONF_EVOLRUN_5 || $objConf->conf_id == CONF_PRAGRUN_5)
                        $idx = 4;	
                    else if($objConf->conf_id == CONF_EVOLRUN_6 || $objConf->conf_id == CONF_PRAGRUN_6)
                        $idx = 5;	
                    else if($objConf->conf_id == CONF_EVOLRUN_7 || $objConf->conf_id == CONF_PRAGRUN_7)
                        $idx = 6;	
                    $data[$idx][3] = $objConf->conf_active;
                    $data[$idx][10] = $objConf->conf_idx;
                    $data[$idx][18] = $objConf->conf_content_cn;
					break;

                default:break;
			}
		}
        

        return $data;
    }

    public function setEvolSite($arrData){
        $arrBatch = array();

        for($i = 0; $i < count($arrData); $i ++){
            $data  = $arrData[$i];
            $strContent = "";
            if(strlen($data['site_ev'])<1) 
                $data['site_ev']=" ";
            $strContent .= $data['site_ev'].";";
            
            if(strlen($data['userid_ev'])<1) 
                $data['userid_ev']=" ";    
            $strContent .= $data['userid_ev'].";";
            
            if(strlen($data['userpwd_ev'])<1) 
                $data['userpwd_ev']=" "; 
            $strContent .= $data['userpwd_ev'];
    
            
            $updateData = array();
            $updateData['conf_content'] = $strContent;
            if($i == 0){
                if($data['game'] == GAME_AUTO_PRAG){
                    $confEvolSite = CONF_PRAGSITE_1;
                    $confEvolRun = CONF_PRAGRUN_1;
                } else {
                    $confEvolSite = CONF_EVOLSITE_1;
                    $confEvolRun = CONF_EVOLRUN_1;
                }
                $strContent = $data['type_ev']."#".$data['bet_ev']."#".$data['con_ev']."#".$data['bet_min']."#".$data['bet_max']."#".$data['con_min']."#".$data['user_max']."#".$data['is_signal']."#".$data['multiroom']."#".$data['conall_ev']."#".$data['bal_max'];
            } else if ($i == 1){
                if($data['game'] == GAME_AUTO_PRAG){
                    $confEvolSite = CONF_PRAGSITE_2;
                    $confEvolRun = CONF_PRAGRUN_2;
                } else {
                    $confEvolSite = CONF_EVOLSITE_2;
                    $confEvolRun = CONF_EVOLRUN_2;
                }
                $strContent = $data['type_ev']."#".$data['betmode_ev']."#".$data['con_ev']."#0#0#0#0#".$data['is_signal']."#".$data['multiroom']."#".$data['conall_ev']."#".$data['bal_max'];
            } else {
                $strContent = $data['type_ev']."##".$data['con_ev']."#0#0#0#0#".$data['is_signal']."#".$data['multiroom']."#".$data['conall_ev']."#".$data['bal_max'];
                if($i == 2){
                    if($data['game'] == GAME_AUTO_PRAG){
                        $confEvolSite = CONF_PRAGSITE_3;
                        $confEvolRun = CONF_PRAGRUN_3;
                    } else {
                        $confEvolSite = CONF_EVOLSITE_3;
                        $confEvolRun = CONF_EVOLRUN_3;
                    }
                } else if($i == 3){
                    if($data['game'] == GAME_AUTO_PRAG){
                        $confEvolSite = CONF_PRAGSITE_4;
                        $confEvolRun = CONF_PRAGRUN_4;
                    } else {
                        $confEvolSite = CONF_EVOLSITE_4;
                        $confEvolRun = CONF_EVOLRUN_4;
                    }
                } else if($i == 4){
                    if($data['game'] == GAME_AUTO_PRAG){
                        $confEvolSite = CONF_PRAGSITE_5;
                        $confEvolRun = CONF_PRAGRUN_5;
                    } else {
                        $confEvolSite = CONF_EVOLSITE_5;
                        $confEvolRun = CONF_EVOLRUN_5;
                    }
                } else if($i == 5){
                    if($data['game'] == GAME_AUTO_PRAG){
                        $confEvolSite = CONF_PRAGSITE_6;
                        $confEvolRun = CONF_PRAGRUN_6;
                    } else {
                        $confEvolSite = CONF_EVOLSITE_6;
                        $confEvolRun = CONF_EVOLRUN_6;
                    }
                } else if($i == 6){
                    if($data['game'] == GAME_AUTO_PRAG){
                        $confEvolSite = CONF_PRAGSITE_7;
                        $confEvolRun = CONF_PRAGRUN_7;
                    } else {
                        $confEvolSite = CONF_EVOLSITE_7;
                        $confEvolRun = CONF_EVOLRUN_7;
                    }
                }
            }
            $updateData['conf_id'] = $confEvolSite;
            $updateData['conf_idx'] = $strContent;
            $arrBatch[] = $updateData;

            $updateData = array();
            $updateData['conf_id'] = $confEvolRun;
            $updateData['conf_active'] = $data['active_ev'];
            $arrBatch[] = $updateData;
        }

        return  $this->builder()->updateBatch($arrBatch, 'conf_id');
    }
    
    public function getCaptchaAlarm(){

        $confIds = [CONF_EVOLSITE_1, CONF_EVOLRUN_1, CONF_EVOLSITE_2, CONF_EVOLRUN_2, CONF_EVOLSITE_3, CONF_EVOLRUN_3,
                CONF_EVOLSITE_4, CONF_EVOLRUN_4, CONF_EVOLSITE_5, CONF_EVOLRUN_5, CONF_EVOLSITE_6, CONF_EVOLRUN_6,
                CONF_EVOLSITE_7, CONF_EVOLRUN_7];  
        $arrConf = $this->find($confIds);

        $bAlarm = STATE_DISABLE;
        foreach($arrConf as $objConf){
            $confRunId  = 0;

            if($objConf->conf_id == CONF_EVOLSITE_1){
                $confRunId = CONF_EVOLRUN_1;
            } else if($objConf->conf_id == CONF_EVOLSITE_2){
                $confRunId = CONF_EVOLRUN_2;
            } else if($objConf->conf_id == CONF_EVOLSITE_3){
                $confRunId = CONF_EVOLRUN_3;
            } else if($objConf->conf_id == CONF_EVOLSITE_4){
                $confRunId = CONF_EVOLRUN_4;
            } else if($objConf->conf_id == CONF_EVOLSITE_5){
                $confRunId = CONF_EVOLRUN_5;
            } else if($objConf->conf_id == CONF_EVOLSITE_6){
                $confRunId = CONF_EVOLRUN_6;
            } else if($objConf->conf_id == CONF_EVOLSITE_7){
                $confRunId = CONF_EVOLRUN_7;
            } 
            if($confRunId == 0)
                continue;
            $confRun = getConfById($arrConf, $confRunId);
            if(is_null($confRun))
                continue;

            if(strpos($objConf->conf_idx, "23#") !== false && strlen($objConf->conf_content_cn) > 0 && strlen($confRun->conf_content_cn) == 0 ){
                $bAlarm = STATE_ACTIVE;
                break;
            }
		}

        return $bAlarm;
    }

    public function saveData($arrData){

        if($arrData == null) return false;
        if (!array_key_exists("sitename", $arrData)) return false;

        $arrBatch = array();
        $updateData = array();
        $updateData['conf_id'] = CONF_SITENAME;
        $updateData['conf_content'] = $arrData['sitename'];
        $arrBatch[] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = CONF_DOMAIN;
        $updateData['conf_content'] = $arrData['domainname'];
        $arrBatch[] = $updateData;
        
        $updateData = array();
        $updateData['conf_id'] = CONF_NOTICE_MAIN;
        $updateData['conf_content'] = $arrData['mainnotice'];
        $updateData['conf_active'] = $arrData['mainnotice_ok'];
        if(array_key_exists('mainnotice_cn', $arrData)){
            $updateData['conf_content_cn'] = $arrData['mainnotice_cn'];
        }
        $arrBatch[] = $updateData;
        
    
        if(array_key_exists('bank', $arrData)){
            $updateData = array();
            $updateData['conf_id'] = CONF_CHARGEINFO;
            $updateData['conf_content'] = $arrData['bank'];
            $arrBatch[] = $updateData;
        } else if(array_key_exists('bankapi', $arrData)){
            $updateData = array();
            $updateData['conf_id'] = CONF_API_VACC;
            $updateData['conf_content'] = $arrData['bankapi'];
            $arrBatch[] = $updateData;
        }

        $updateData = array();
        $updateData['conf_id'] = CONF_NOTICE_BANK;
        $updateData['conf_content'] = removeHostUrl($arrData['depositenotice']);
        $updateData['conf_active'] = $arrData['depositenotice_ok'];
        $updateData['conf_idx'] = $arrData['depositenotice_color'];
        if(array_key_exists('depositenotice_cn', $arrData)){
            $updateData['conf_content_cn'] = removeHostUrl($arrData['depositenotice_cn']);
        }
        $arrBatch[] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = CONF_NOTICE_URGENT;
        $updateData['conf_content'] = removeHostUrl($arrData['urgentnotice']);
        $updateData['conf_active'] = $arrData['urgentnotice_ok'];
        $updateData['conf_idx'] = $arrData['urgentnotice_color'];
        if(array_key_exists('urgentnotice_cn', $arrData)){
            $updateData['conf_content_cn'] = removeHostUrl($arrData['urgentnotice_cn']);
        }
        $arrBatch[] = $updateData;
    
        if(array_key_exists('chargemanual', $arrData)){
            $updateData = array();
            $updateData['conf_id'] = CONF_CHARGE_MANUAL;
            $updateData['conf_content'] = $arrData['chargemanual'];
            $arrBatch[] = $updateData;
        } else if(array_key_exists('exchange_delay', $arrData)){
            $updateData = array();
            $updateData['conf_id'] = CONF_CHARGE_MANUAL;
            $updateData['conf_idx'] = $arrData['exchange_delay'];
            $arrBatch[] = $updateData;
        }

        if(array_key_exists('discharmanual', $arrData)){
            $updateData = array();
            $updateData['conf_id'] = CONF_DISCHA_MANUAL;
            $updateData['conf_content'] = $arrData['discharmanual'];
            $arrBatch[] = $updateData;
        } else if(array_key_exists('bank_rest', $arrData)){
            $updateData = array();
            $updateData['conf_id'] = CONF_DISCHA_MANUAL;
            $updateData['conf_idx'] = $arrData['bank_rest'];
            $arrBatch[] = $updateData;
        }
        
        $updateData = array();
        $updateData['conf_id'] = CONF_CHARGEMACRO;
        $updateData['conf_content'] = removeHostUrl($arrData['bankmacro']);
        if(array_key_exists('bankmacro_cn', $arrData)){
            $updateData['conf_content_cn'] = removeHostUrl($arrData['bankmacro_cn']);
        }
        $arrBatch[] = $updateData;
        
        $updateData = array();
        $updateData['conf_id'] = CONF_MULTI_LOGIN;
        $updateData['conf_active'] = $arrData['multilog_ok'];
        $arrBatch[] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = CONF_TRANS_DENY;
        $updateData['conf_active'] = $arrData['trans_deny'];
        $arrBatch[] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = CONF_RETURN_DENY;
        $updateData['conf_active'] = $arrData['return_deny'];
        $arrBatch[] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = CONF_TRANS_LV1;
        $updateData['conf_active'] = $arrData['trans_lv1'];
        $arrBatch[] = $updateData;

        $updateData = array();
        $updateData['conf_id'] = CONF_RETURN_LV1;
        $updateData['conf_active'] = $arrData['return_lv1'];
        $arrBatch[] = $updateData;

        if(array_key_exists('chargeurl', $arrData)){
            $updateData = array();
            $updateData['conf_id'] = CONF_CHARGE_URL;
            $updateData['conf_content'] = $arrData['chargeurl'];
            $arrBatch[] = $updateData;
        }

        if(array_key_exists('teleid', $arrData)){
            $updateData = array();
            $updateData['conf_id'] = CONF_TELE_ID;
            $updateData['conf_content'] = $arrData['teleid'];
            $arrBatch[] = $updateData;
        }

        if(array_key_exists('autoapps_check', $arrData)){
            $updateData = array();
            $updateData['conf_id'] = CONF_AUTOAPPS;
            $updateData['conf_content'] = $arrData['autoapps_data'];
            $updateData['conf_idx'] = $arrData['autoapps_check'];
            $arrBatch[] = $updateData;
        }

        if(array_key_exists('logout_delay', $arrData)){
            $updateData = array();
            $updateData['conf_id'] = CONF_DELAY_PLAY;
            $updateData['conf_idx'] = $arrData['logout_delay'];
            $arrBatch[] = $updateData;
        }
        return  $this->builder()->updateBatch($arrBatch, 'conf_id');

    }
    public function saveAgent($arrData){
        $logHead = "<ConfSite_Model saveAgent()>";
        writeLog($logHead." started.");
        if($arrData == null) return false;
        if (!array_key_exists("game_id", $arrData)) return false;
        $gameId = intval($arrData['game_id']);
        if($gameId == GAME_CASINO_EVOL){
            $confId = CONF_API_HPPLAY;
		} else if($gameId == GAME_SLOT_THEPLUS){
            $confId = CONF_API_THEPLUS;
		} else if($gameId == GAME_SLOT_GSPLAY){
            $confId = CONF_API_GSPLAY;
		} else if($gameId == GAME_SLOT_GOLD){
            $confId = CONF_API_GOLD;
		} else if($gameId == GAME_CASINO_KGON || $gameId == GAME_SLOT_KGON){
            $confId = CONF_API_KGON;
		} else if($gameId == GAME_CASINO_STAR || $gameId == GAME_SLOT_STAR){
            $confId = CONF_API_STAR;
		} else if($gameId == GAME_HOLD_CMS){
            $confId = CONF_API_HOLD;
		} else if($gameId == GAME_CASINO_RAVE || $gameId == GAME_SLOT_RAVE){
            $confId = CONF_API_RAVE;
		} else if($gameId == GAME_CASINO_TREEM || $gameId == GAME_SLOT_TREEM){
            $confId = CONF_API_TREEM;	
            $confContent = "https://api.honorlink.org/api#{$arrData['agent_id']}#{$arrData['agent_token']}";
            $updateData = array();
            $updateData['conf_content'] = $confContent;
            $updateData['conf_update'] = date('Y-m-d H:i:s');
            $this->builder()->where('conf_id', $confId)->update($updateData);		
            writeLog($logHead."'conf_content' has changed with {$updateData['conf_content']}");
		} else if($gameId == GAME_CASINO_SIGMA || $gameId == GAME_SLOT_SIGMA){
            $confId = CONF_API_SIGMA;
        }
    }

    public function saveMaintainConfig($arrData){

        if($arrData == null) return false;
        if (!array_key_exists("maintain", $arrData)) return false;
        if (!array_key_exists("content", $arrData)) return false;
        
        $arrBatch = array();
        
        $updateData['conf_id'] = CONF_MAINTAIN;
        $updateData['conf_content'] = $arrData['content'];
        $updateData['conf_active'] = $arrData['maintain'];
        $arrBatch[0] = $updateData;
        return $this->builder()->updateBatch($arrBatch, 'conf_id');
    }

    public function getEvpressState(){

        $objConfig = $this->where('conf_id', CONF_EVOLPRESS)->first();
        if(!is_null($objConfig) && intval($objConfig->conf_content) == STATE_ACTIVE){
            return true;
        }

        return false;
    }

    public function getEvpressConfig(){

        $data = [0, 0, 0, 0, 0];

        $objConfig = $this->where('conf_id', CONF_EVOLPRESS)->first();
        if(!is_null($objConfig)){
            $info = explode('#', $objConfig->conf_idx);
            if(count($info) >= 4){
                $data[0] = $info[0];   
                $data[1] = $info[1];  
                $data[2] = $info[2];   
                $data[3] = $info[3];  
                if(count($info) >= 5){
                    $data[4] = $info[4];  
                }
            }
        }

        return $data;
    }

    public function saveEvpressConfig($arrData){

        if($arrData == null) return false;
        if (!array_key_exists("auto_press", $arrData)) return false;
        if (!array_key_exists("auto_percent", $arrData)) return false;
        if (!array_key_exists("fail_press", $arrData)) return false;
        if (!array_key_exists("fail_amount", $arrData)) return false;
        if (!array_key_exists("force_press", $arrData)) return false;
        
        $arrBatch = array();
        $updateData['conf_id'] = CONF_EVOLPRESS;
        $updateData['conf_idx'] = $arrData['auto_press']."#".$arrData['auto_percent']."#".$arrData['fail_press']."#".$arrData['fail_amount']."#".$arrData['force_press'];
        $arrBatch[0] = $updateData;
        return $this->builder()->updateBatch($arrBatch, 'conf_id');
    }

    public function getSoundConf(){

        $confIds = [CONF_SOUND_1, CONF_SOUND_2, CONF_SOUND_3, CONF_SOUND_4, CONF_SOUND_5];  
        $arrConf = $this->find($confIds);

        $arrSoundData = array();
        foreach($arrConf as $objConf){
            $arrSound = ["", 0];
            $arrSound[0] = $objConf->conf_content;
            $arrSound[1] = $objConf->conf_active;
            array_push($arrSoundData, $arrSound);
		}

        return $arrSoundData;
    }

    public function saveSoundConf($arrSoundData){
        if(count($arrSoundData) < 5)
            return false;
        
        $arrBatch = array();

        for($i=0 ; $i<4; $i++){

            $arrUpdateData = array();   
            
            $arrUpdateData['conf_id'] = CONF_SOUND_1+$i;
            $arrUpdateData['conf_content'] = $arrSoundData[$i][0];
            $arrUpdateData['conf_active'] = $arrSoundData[$i][1];
            array_push($arrBatch, $arrUpdateData);
            
        }
       
        $arrUpdateData = array();   
        $arrUpdateData['conf_id'] = CONF_SOUND_5;
        $arrUpdateData['conf_content'] = $arrSoundData[4][0];
        $arrUpdateData['conf_active'] = $arrSoundData[4][1];
        array_push($arrBatch, $arrUpdateData);
        
        return  $this->builder()->updateBatch($arrBatch, 'conf_id');
            
    }

    public function getSiteConf(){
        $confIds = [CONF_SITENAME, CONF_GAMEPER_FULL, CONF_BPG_DENY,  CONF_EVOL_DENY, CONF_SLOT_DENY, 
            CONF_CAS_DENY, CONF_EOS5_DENY, CONF_EOS3_DENY, CONF_COIN5_DENY, CONF_COIN3_DENY, 
            CONF_PBG_DENY, CONF_HOLD_DENY, CONF_EVOLFOLLOW, CONF_DHP_DENY, CONF_SPK_DENY];  
        return $this->find($confIds);
    }

    
    public function setConfActive($confId, $balance){
        
        $this->builder()->set('conf_active', $balance);
        $this->builder()->where('conf_id', $confId);
        
        return $this->builder()->update();
    }

    public function setConfContentCn($confId, $content){
        
        $this->builder()->set('conf_content_cn', $content);
        $this->builder()->where('conf_id', $confId);
        
        return $this->builder()->update();
    }
    public function setConfContent($confId, $content){
        
        $this->builder()->set('conf_content', $content);
        $this->builder()->where('conf_id', $confId);
        
        return $this->builder()->update();
    }    
    public function IsMultiLogin(){

        $objConf = $this->find(CONF_MULTI_LOGIN);
        
        if(!is_null($objConf) && $objConf->conf_active == STATE_ACTIVE) {
            return true;
        }
        return false;
    }

    
    public function IsMaintain(){

        $objConf = $this->find(CONF_MAINTAIN);
        
        if(!is_null($objConf) && $objConf->conf_active == STATE_ACTIVE) {
            return true;
        }
        return false;
    }

    
    public function readBetConf(){
        $confIds = [CONF_BET_NL_DENY, CONF_BET_NP_DENY, CONF_BET_N2P_DENY, CONF_BET_PN_DENY, CONF_BET_BLANK_EN];  
        $arrConf = $this->find($confIds);

        foreach($arrConf as $objConf){
			switch($objConf->conf_id){
				case CONF_BET_NL_DENY:	$_ENV['bet.nl_deny'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
				case CONF_BET_NP_DENY:	$_ENV['bet.np_deny'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
				case CONF_BET_N2P_DENY:	$_ENV['bet.n2p_deny'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
				case CONF_BET_PN_DENY: $_ENV['bet.pn_deny'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
                case CONF_BET_BLANK_EN: $_ENV['bet.blank_en'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
				default:break;
			}
		}
    }
    
    public function readMemConf(){
        $confIds = [CONF_TRANS_DENY, CONF_RETURN_DENY, CONF_TRANS_LV1, CONF_RETURN_LV1, 
            CONF_TRANS_LVS, CONF_DEPOSIT_PLAY, CONF_WITHDRAW_PLAY, CONF_DELAY_PLAY, CONF_AUTO_PERMIT];  
        $arrConf = $this->find($confIds);
        $_ENV['mem.trans_deny'] = false;
        $_ENV['mem.return_deny'] = false;
        $_ENV['mem.trans_lv1'] = false;
        $_ENV['mem.return_lv1'] = false;
        $_ENV['mem.depodeny_play'] = false;
        $_ENV['mem.withdeny_play'] = false;
        $_ENV['mem.trans_lvs'] = [];
        $_ENV['mem.delay_play'] = DELAY_PLAYING;
        $_ENV['mem.auto_permit'] = false;

        foreach($arrConf as $objConf){
			switch($objConf->conf_id){
				case CONF_TRANS_DENY:	$_ENV['mem.trans_deny'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
                case CONF_RETURN_DENY:	$_ENV['mem.return_deny'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
                case CONF_TRANS_LV1:	$_ENV['mem.trans_lv1'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
                case CONF_RETURN_LV1:	$_ENV['mem.return_lv1'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
                case CONF_DEPOSIT_PLAY:	$_ENV['mem.depodeny_play'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
                case CONF_WITHDRAW_PLAY:	$_ENV['mem.withdeny_play'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
                case CONF_TRANS_LVS:	
					$lvs = explode(',', $objConf->conf_content);
                    foreach($lvs as $lv){
                        $lv = trim($lv);
                        if(strlen($lv) > 0 && !in_array($lv, $_ENV['mem.trans_lvs']))
                            array_push($_ENV['mem.trans_lvs'], intval($lv));
                    }
                    break;
                case CONF_DELAY_PLAY:	$_ENV['mem.delay_play'] = intval($objConf->conf_active);
					break;
                case CONF_AUTO_PERMIT:	$_ENV['mem.auto_permit'] = $objConf->conf_active == STATE_ACTIVE?true:false;
					break;
				default:break;
			}
		}

    }
}