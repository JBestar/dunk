<?php
namespace App\Controllers;

class Bank extends StdController {

	/**

	 */
	public function index()
	{
				
		if(is_login())
		{
			$this->response->redirect($_ENV['app.furl'].'/bank/deposit');
		}
		else {
			$this->response->redirect($_ENV['app.furl'].'/pages/login');
		}	
		
	}

	public function deposit(){
		$this->load_view_page('bank/deposit', 'bank_deposit', LEVEL_ADMIN);
	}

	public function withdraw(){
		$this->load_view_page('bank/withdraw', 'bank_withdraw', LEVEL_ADMIN);
	}

	public function exchange(){
		$strUid = $this->session->user_id;
		$objAdmin = $this->modelMember->getInfo($strUid);
		if ($objAdmin != null && ($objAdmin->mb_level >= LEVEL_ADMIN || $_ENV['app.name'] != APP_DUNK) )
			$this->load_view_page('bank/exchange', 'bank_exchange');
		else $this->response->redirect( $_ENV['app.furl'].'/pages/login');
	}

	public function transfer(){
		$this->load_view_page('bank/transfer', 'bank_transfer');
	}
}