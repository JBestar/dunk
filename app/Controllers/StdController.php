<?php

namespace App\Controllers;

use App\Models\ConfSite_Model;

class StdController extends BaseController
{
    protected function load_view_page($url, $activePage, $userLevel = 0, $arrAddData = null)
	{			
		if(is_login())
		{
            $this->sess_action();                

			$arrData = getSidebarLinkArray();
			$arrData['confdropdownbtn'] = " main-dropdown-active-btn";
			$arrData['confdropdown'] = "style='display:block'";
			$arrData[$activePage] = " sidebar-a-active";
			$arrNum = [
				"10"=> "",
				"20"=> "",
				"50"=> "",
				"100"=> "",
			];
			if(array_key_exists('app.tree', $_ENV) && $_ENV['app.tree'] == 1)
				$arrNum["100"] = "selected";
			else $arrNum["10"] = "selected";

			$arrData['select_nums'] = $arrNum;
			
			$confsiteModel = new ConfSite_Model();

			$strUid = $this->session->user_id;
			$objUser = $this->modelMember->getInfo($strUid);
			$arrData['mb_level'] = $objUser->mb_level;
			$arrData += $this->getSiteConf($confsiteModel);
			
			if ($arrAddData !== null)
				$arrData = $arrData + $arrAddData;
			
			if ($userLevel == 0){
				echo view($url, $arrData);
			}
			else {
				if($objUser->mb_level >= $userLevel){
					echo view($url, $arrData);
				} else  $this->response->redirect( $_ENV['app.furl'].'/pages/nopermit');
			}
		}
		else {
			$this->response->redirect( $_ENV['app.furl'].'/pages/login');
		}
	}
}
