<?php

namespace App\Controllers;

use App\Models\ConfSite_Model;
use App\Models\Charge_Model;
use App\Models\Exchange_Model;
use App\Models\MemConf_Model;

class User extends StdController
{

	/**

	 */
	private function user_edit_page($url, $activePage, $mbFid, $userLevel){
		if (is_login() === false){
			return $this->response->redirect( $_ENV['app.furl'].'/pages/login');
		}
		$confsiteModel = new ConfSite_Model();
		$confsiteModel->readMemConf();
		$memConfModel = new MemConf_Model();

		$empUid = '';
		$bTrans = false;
		$bReturn = false;

		$objMember = null;
		$bPermit = false;
		$strUid = $this->session->user_id;
		$objAdmin = $this->modelMember->getInfo($strUid);
		if(!is_numeric($mbFid)){
			$bPermit = false;
		} else if($mbFid == 0){
			$bPermit = true;
		} else {
			

			$arrMem = $this->modelMember->getMemberByEmpFid($objAdmin->mb_fid, $objAdmin->mb_level,  $objAdmin->mb_level, true, $mbFid);
			if(count($arrMem) > 0)
				$objMember = reset($arrMem);					
			
			if ($objMember != null){
				$objMember->mb_point = floor($objMember->mb_point);
				if(isEBalMode() && $objAdmin->mb_level >= LEVEL_ADMIN){
					$objMember->mb_range_min = 0;
					$objMember->mb_range_max = 0;
					$objMember->mb_range_limit = 0;
					$info = explode(":", $objMember->mb_range_ev);
					if(count($info) >= 2){
						$objMember->mb_range_min = intval($info[0]);
						$objMember->mb_range_max = intval($info[1]);
					}
					$objMember->mb_press_active = false;
					$objMember->mb_press_amount = 0;
					$objMember->mb_press_count = 1;
					$info = explode(":", $objMember->mb_press_ev);
					if(count($info) >= 2){
						$objMember->mb_press_active = intval($info[0]) == STATE_ACTIVE;
						$objMember->mb_press_amount = intval($info[1]) ;
						if(count($info) >= 3)
							$objMember->mb_press_count = intval($info[2]) ;
					}

					$objMember->mb_pressat_active = false;
					$objMember->mb_pressat_amount = 0;
					$info = explode(":", $objMember->mb_pressat_ev);
					if(count($info) >= 2){
						$objMember->mb_pressat_active = intval($info[0]) == STATE_ACTIVE;
						$objMember->mb_pressat_amount = intval($info[1]) ;
					}

					$objMember->mb_follow_active = false;
					$objMember->mb_follow_id = "";
					$objMember->mb_follow_percent = 100;
					$info = explode(":", $objMember->mb_follow_ev);
					if(count($info) >= 2){
						$objMember->mb_follow_active = intval($info[0]);
						$objMember->mb_follow_id = trim($info[1]);
						if(count($info) >= 3)
							$objMember->mb_follow_percent = intval($info[2]);
					}
					$objMember->mb_overbet_percent = 100;
				}
				
				$objEmpMember = $this->modelMember->find($objMember->mb_emp_fid);
				if ($objEmpMember != null)
					$empUid = $objEmpMember->mb_uid;
				$bChild = $objMember->mb_emp_fid == $objAdmin->mb_fid;
				if($_ENV['mem.trans_deny'])				//이송 검사
					$bTrans = false;
				else if($_ENV['mem.trans_lv1'] && !$bChild)
					$bTrans = false;
				else
					$bTrans = true;

				if($objAdmin->mb_level < LEVEL_ADMIN && array_key_exists('app.sess_act', $_ENV) && $_ENV['app.sess_act'] == 1){
					$admConf = $memConfModel->getByMember($objAdmin->mb_fid);
					if(!is_null($admConf) ){
						$_ENV['mem.return_deny'] = $_ENV['mem.return_deny'] || ($admConf->conf_num_1 != STATE_ACTIVE); //$admConf->conf_num_1 == 1 => 하부회원 머니환수 가능
					} else $_ENV['mem.return_deny'] = true;
				}

				if($_ENV['mem.return_deny'])				//환수 검사
					$bReturn = false;
				else if($_ENV['mem.return_lv1'] && !$bChild)
					$bReturn = false;
				else
					$bReturn = true;

				if(count($_ENV['mem.trans_lvs']) > 0 && !in_array(intval($objAdmin->mb_level), $_ENV['mem.trans_lvs']) ){
					$bTrans = false;
					$bReturn = false;
				}

				$bPermit = true;
			}
		}
		
		if (!$bPermit){
			// $this->response->redirect( $_ENV['app.furl'].'/pages/nopermit');
			print "<script language=javascript> alert('접근권한이 없습니다.'); self.close(); </script>";
		}
		else {
			$follow_en = false;
			$press_en = 0;

			if($objAdmin->mb_level >= LEVEL_ADMIN){
				$confFollow = $confsiteModel->getConf(CONF_EVOLFOLLOW);
				if($confFollow != null){
					$follow_en = intval($confFollow->conf_active) == STATE_ACTIVE ;
					$info = explode('#', $confFollow->conf_idx);
					if(count($info) >= 1){
						$press_en = intval($info[0]);
					}
				}
			}
			$emps = [];
			if($objAdmin->mb_level >= LEVEL_ADMIN && array_key_exists('app.tree', $_ENV) && $_ENV['app.tree'] == 1 ){
				$url = 'user/user_detail'; 

				if(!is_null($objMember)){
					$chargeModel = new Charge_Model();
            		$exchangeModel = new Exchange_Model();

					$chargeStat = $chargeModel->calcUserChargeStat($objMember->mb_uid);
					$objMember->mb_charge_yest = $chargeStat[0]->charge_sum != null ? $chargeStat[0]->charge_sum : 0 ;
					$objMember->mb_charge_today = $chargeStat[1]->charge_sum != null ? $chargeStat[1]->charge_sum : 0 ;
					$objMember->mb_charge_week = $chargeStat[2]->charge_sum != null ? $chargeStat[2]->charge_sum : 0 ;
					$objMember->mb_charge_month = $chargeStat[3]->charge_sum != null ? $chargeStat[3]->charge_sum : 0 ;
					$objMember->mb_charge_total = $chargeStat[4]->charge_sum != null ? $chargeStat[4]->charge_sum : 0 ;

					$exchangeStat = $exchangeModel->calcUserExchangeStat($objMember->mb_uid);
					$objMember->mb_exchange_yest = $exchangeStat[0]->exchange_sum != null ? $exchangeStat[0]->exchange_sum : 0 ;
					$objMember->mb_exchange_today = $exchangeStat[1]->exchange_sum != null ? $exchangeStat[1]->exchange_sum : 0 ;
					$objMember->mb_exchange_week = $exchangeStat[2]->exchange_sum != null ? $exchangeStat[2]->exchange_sum : 0 ;
					$objMember->mb_exchange_month = $exchangeStat[3]->exchange_sum != null ? $exchangeStat[3]->exchange_sum : 0 ;
					$objMember->mb_exchange_total = $exchangeStat[4]->exchange_sum != null ? $exchangeStat[4]->exchange_sum : 0 ;

					if($objMember->mb_level >= LEVEL_ADMIN)
						$objMember->mb_ip_join = "";

					if(array_key_exists('app.sess_act', $_ENV) && $_ENV['app.sess_act'] == 1){
						$arrEmp = $this->modelMember->getEmpMemberByFid($objMember->mb_fid, "ASC");
						foreach($arrEmp as $objEmp){
							if($objEmp->mb_uid !== $objMember->mb_uid){
								$emp = new \stdClass();
								$emp->mb_uid = $objEmp->mb_uid;
								$emp->mb_nickname = $objEmp->mb_nickname;
								$emp->mb_fid = $objEmp->mb_fid;
								array_push($emps, $emp);
							}
						}
					}

					if(isEBalMode() && array_key_exists('app.sess_act', $_ENV) && $_ENV['app.sess_act'] == 1){
						$memConf = $memConfModel->getByMember($objMember->mb_fid);
						$arrAppInfo = [];
						$arrChargeInfo = [];
						$objMember->mb_transfer_subs = STATE_DISABLE;
						$objMember->mb_recommender_deny = STATE_DISABLE;
						if(!is_null($memConf) ){
							$arrAppInfo = explode('#', $memConf->conf_str_1); //오토앱 허용
							$arrChargeInfo = explode('#', $memConf->conf_str_5); //입금통장
							$objMember->mb_transfer_subs = $memConf->conf_num_1; //하부회원 머니환수
							$objMember->mb_recommender_deny = $memConf->conf_num_2; //추천인 사용불가
							$objMember->mb_range_limit = $memConf->conf_num_3;//베팅한도금액(하부포함)
							if($memConf->conf_num_4 > 0)
								$objMember->mb_overbet_percent = $memConf->conf_num_4;//넘기기금액(%)
						}

						$confAutoapp = $confsiteModel->getConf(CONF_AUTOAPPS);
						$objMember->mb_autoapps = [];
						$arrAutoApp = explode(';', $confAutoapp->conf_content);
						$i=0;
						foreach($arrAutoApp as $objInfo){
							$info = explode('#', $objInfo);
							if(count($info) < 2)
								continue;
							$app = new \stdClass();
							$app->ename = $info[0];
							$app->name = $info[1];
							$app->act = 0;
							if(count($arrAppInfo) > $i)
								$app->act = intval($arrAppInfo[$i]);
							$i++;
							array_push($objMember->mb_autoapps, $app);
						}

						if(count($arrChargeInfo) < 3)
							$arrChargeInfo = ['', '', ''];
						$objMember->mb_charge_info = $arrChargeInfo;
					}
				}
			} 

			$this->load_view_page(
				$url, 
				$activePage, 
				$userLevel, 
				[ 'objMember' => $objMember,
					'emp_uid' => $empUid,
					'trnas_en' => $bTrans, 
					'return_en' => $bReturn,
					'follow_en' => $follow_en,
					'press_en' => $press_en,
					'emps' => $emps,
				]
			);  
		}
		
	}

	public function index()
	{
		if(is_login())
		{
			$strUid = $this->session->user_id;
			$objMember = $this->modelMember->getInfo($strUid);
			if(array_key_exists('app.hold', $_ENV) && $_ENV['app.hold'] == 1){
				if(!is_null($objMember) && ($objMember->mb_level >= LEVEL_ADMIN || floatval($objMember->mb_game_hl_ratio) > 0) )
					$this->response->redirect($_ENV['app.furl'].'/user/member_list/0');
				else print "<script language=javascript> self.close(); </script>";
			} else if(array_key_exists('app.tree', $_ENV) && $_ENV['app.tree'] == 1){
				$this->response->redirect($_ENV['app.furl'].'/user/member_list/0');
			} else 
				$this->response->redirect($_ENV['app.furl'].'/user/member/0');
		}
		else {
			$this->response->redirect($_ENV['app.furl'].'/pages/login');
		}	
		
	}

	function member_log(){
		
		$this->load_view_page(
			'user/member_log', 
			'user_log', 
			LEVEL_ADMIN);
	}

	function member_connect(){
		
		$this->load_view_page(
			'user/member_connect', 
			'user_log', 
			LEVEL_ADMIN);
	}
	
	function member_ip(){
		
		$this->load_view_page(
			'user/member_log2', 
			'user_ip', 
			LEVEL_ADMIN);
	}

	function member_try(){
		
		$this->load_view_page(
			'user/member_try', 
			'user_log', 
			LEVEL_ADMIN);
	}

	function member_block(){
		
		$this->load_view_page(
			'user/member_block', 
			'user_block', 
			LEVEL_ADMIN);
	}

	function member($empFid){
		if (is_login() === false){
			return $this->response->redirect($_ENV['app.furl'].'/pages/login');
		}
		$strUid = $this->session->user_id;
		$objAdmin = $this->modelMember->getInfo($strUid);
		$admFid = 0;
		if($objAdmin->mb_level >= LEVEL_MASTER){
			$arrMem = $this->modelMember->getMemberByLevel(LEVEL_ADMIN);
			 if(count($arrMem) > 0){
				$objMember = reset($arrMem);	
				$admFid = $objMember->mb_fid;
			 }
				 
		}
		$objEmp = $this->modelMember->find($empFid);
		$empUid = "";
		if ($objEmp != null){
			$empUid = $objEmp->mb_uid;
		}
		$this->load_view_page(
			'user/member', 
			'user_member', 
			LEVEL_MIN, 
			['emp_uid' => $empUid, 'adm_fid' => $admFid]);
	}

	function member_list($empFid){
		if (is_login() === false){
			return $this->response->redirect($_ENV['app.furl'].'/pages/login');
		}
		$strUid = $this->session->user_id;
		$objAdmin = $this->modelMember->getInfo($strUid);
		$admFid = 0;

		if(array_key_exists('app.hold', $_ENV) && $_ENV['app.hold'] == 1 && !is_null($objAdmin) && $objAdmin->mb_level < LEVEL_ADMIN && floatval($objAdmin->mb_game_hl_ratio) == 0 ){
			print "<script language=javascript> self.close(); </script>";
		} else {
			if($objAdmin->mb_level >= LEVEL_MASTER){
				$arrMem = $this->modelMember->getMemberByLevel(LEVEL_ADMIN);
				 if(count($arrMem) > 0){
					$objMember = reset($arrMem);	
					$admFid = $objMember->mb_fid;
				 }
					 
			}
			$viewPath = 'user/member_list2'; 
			if($objAdmin->mb_level >= LEVEL_ADMIN){
				$viewPath = 'user/member_list'; 
			}
			$objEmp = $this->modelMember->find($empFid);
			$empUid = "";
			if ($objEmp != null){
				$empUid = $objEmp->mb_uid;
			}
			$this->load_view_page(
				$viewPath, 
				'user_member', 
				LEVEL_MIN, 
				['emp_uid' => $empUid, 'adm_fid' => $admFid]);
		}
		
	}

	public function member_edit($mbFid){

		$this->user_edit_page(
			'user/member_edit', 
			'user_member', 
			$mbFid,
			LEVEL_MIN);		
	}

	public function member_uid($mbUid){
		
			
		$objMember = $this->modelMember->getInfo($mbUid);
		if(is_null($objMember))
			print "<script language=javascript> alert('존재하지 않는 회원입니다.'); self.close(); </script>";
		else if($objMember->mb_level > LEVEL_ADMIN)
			print "<script language=javascript> alert('접근권한이 없습니다.'); self.close(); </script>";
		else 
			$this->user_edit_page(
				'user/member_edit', 
				'user_member', 
				$objMember->mb_fid,
				LEVEL_MIN);	

	}

	function member_ctrl($strEmpFid){
		if (is_login() === false){
			return $this->response->redirect($_ENV['app.furl'].'/pages/login');
		}
		$objEmp = $this->modelMember->find($strEmpFid);
		$strEmpUid = "";
		if ($objEmp != null){
			$strEmpUid = $objEmp->mb_uid;
		}
		$this->load_view_page(
			'user/member_ctrl', 
			'user_ctrl', 
			LEVEL_ADMIN, 
			['emp_uid' => $strEmpUid]);
	}

	function member_list2($strEmpFid){
		if (is_login() === false){
			return $this->response->redirect($_ENV['app.furl'].'/pages/login');
		}
		$objEmp = $this->modelMember->find($strEmpFid);
		$strEmpUid = "";
		if ($objEmp != null){
			$strEmpUid = $objEmp->mb_uid;
		}
		$this->load_view_page(
			'user/member_ctrl2', 
			'user_ctrl2', 
			LEVEL_ADMIN, 
			['emp_uid' => $strEmpUid]);
	}

	function member_class($strEmpFid){
		if (is_login() === false){
			return $this->response->redirect($_ENV['app.furl'].'/pages/login');
		}
		$objEmp = $this->modelMember->find($strEmpFid);
		$strEmpUid = "";
		if ($objEmp != null){
			$strEmpUid = $objEmp->mb_uid;
		}
		$this->load_view_page(
			'user/member_class', 
			'user_ctrl', 
			LEVEL_ADMIN, 
			['emp_uid' => $strEmpUid]);
	}

	public function member_detail($mbFid){
			
		$objMember = null;
		$bPermit = false;
		
		if(!is_numeric($mbFid)){
			$bPermit = false;
		} else {
			$strUid = $this->session->user_id;
			$objAdmin = $this->modelMember->getInfo($strUid);

			$arrMem = $this->modelMember->getMemberByEmpFid($objAdmin->mb_fid, $objAdmin->mb_level,  $objAdmin->mb_level, true, $mbFid);
			if(count($arrMem) > 0)
				$objMember = reset($arrMem);					
			
			$empUid = '';
			$bChild = false;
			if ($objMember != null) {
				$objEmpMember = $this->modelMember->find($objMember->mb_emp_fid);
				$objMember->mb_emp_uid = "";
				if ($objEmpMember != null)
					$objMember->mb_emp_uid = $objEmpMember->mb_uid;
				$bPermit = true;
			}
		}
		
		if (!$bPermit){
			// $this->response->redirect( $_ENV['app.furl'].'/pages/nopermit');
			print "<script language=javascript> alert('존재하지 않는 회원입니다.'); self.close(); </script>";
		}
		else {
			$this->load_view_page(
				'user/member_detail', 
				'user_ctrl',
				LEVEL_MIN, 
				[
					'objMember' => $objMember, 
				]);  
		}
	}
	
	function member_follow($mbFid){
		if (is_login() === false){
			return $this->response->redirect($_ENV['app.furl'].'/pages/login');
		}
		$objMaster = $this->modelMember->find($mbFid);
		if ($objMaster != null && $objMaster->mb_level <= LEVEL_ADMIN){

			$arrMember = $this->modelMember->searchFollowers($objMaster->mb_uid);

			$followers = [];
			foreach($arrMember as $objMember){
				$objMember->mb_follow_active = false;
				$objMember->mb_follow_id = "";
				$objMember->mb_follow_percent = 100;
				$info = explode(":", $objMember->mb_follow_ev);
				if(count($info) >= 2){
					$objMember->mb_follow_active = intval($info[0]);
					$objMember->mb_follow_id = trim($info[1]);
					if(count($info) >= 3)
						$objMember->mb_follow_percent = intval($info[2]);
				}

				if($objMember->mb_follow_active == 1 && $objMember->mb_follow_id === $objMaster->mb_uid)
					array_push($followers, $objMember);
			}
			
			
			$this->load_view_page(
				'user/member_follow', 
				'user_member', 
				LEVEL_ADMIN, 
				[	'master' => $objMaster,
					'followers' => $followers]);
		} else 
			print "<script language=javascript> alert('접근권한이 없습니다.'); self.close(); </script>";
		
	}

}