<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PbRound_Model;

class Result extends StdController {

	/**


	 */
	public function index()
	{
		$this->response->redirect( $_ENV['app.furl'].'/result/pbresult');
		
	}
	private function result_edit_page($roundFid, $url, $activePage, $userLevel, $arrAddData = null){
		if (is_login() === false){
			return $this->response->redirect( $_ENV['app.furl'].'/pages/login');
		}
		$objRound = null;
		$roundModel = new PbRound_Model();
		
		$strUid = $this->session->user_id;
		$objUser = $this->modelMember->getInfo($strUid);
		if($objUser->mb_level >= $userLevel)
		{
			$objRound = null;
			if($roundFid > 0){
				$objRound = $roundModel->get($roundFid);
				if(is_null($objRound)){
					$this->response->redirect( $_ENV['app.furl'].'/pages/nopermit');		
				}									
			} else if($roundFid == 0){
				$objRound = null;					
			} else $this->response->redirect( $_ENV['app.furl'].'/pages/nopermit');	

			$arrData = [ 'objRound' => $objRound ];
			
			if ($arrAddData !== null)
				$arrData = $arrData + $arrAddData;
			$this->load_view_page($url, $activePage, LEVEL_ADMIN, $arrData);	
		}	
	}
	private function result_change_page($url, $date, $roundNo, $arrAddData = null){
		
		$arrData = [ 'strDate' => $date, 'strRoundNo' => $roundNo ];
			
		if ($arrAddData !== null)
			$arrData = $arrData + $arrAddData;

		$this->load_view_page($url, 'gameedit', LEVEL_ADMIN, $arrData);	
	}
	public function pbresult()
	{	
		$param = [
			'game_name' => "PBG",
			'game_id' => GAME_PBG_BALL,
		];	
		$this->load_view_page('result/bbresult', 'gameresult', LEVEL_ADMIN, $param);			
	}

	public function pbresult_edit($strRoundFid)
	{
		$param = [
			'game_name' => "PBG",
			'game_id' => GAME_PBG_BALL,
		];	

		$this->result_edit_page(
			$strRoundFid, 
			'result/bbresult_edit', //pbresult_edit 
			'gameresult',
			LEVEL_ADMIN,
			$param);
		return;	
	}

	public function dpresult()
	{
		$param = [
			'game_name' => "동행볼",
			'game_id' => GAME_DHP_BALL,
		];
		$this->load_view_page('result/bbresult', 'gameresult', LEVEL_ADMIN, $param);	
	}

	public function dpresult_edit($strRoundFid)
	{
		$param = [
			'game_name' => "동행볼",
			'game_id' => GAME_DHP_BALL,
		];	

		$this->result_edit_page(
			$strRoundFid, 
			'result/bbresult_edit', 
			'gameresult', 
			LEVEL_ADMIN,
			$param);	
	}

	public function epresult()
	{
		$param = [
			'game_name' => "에볼파워볼",
			'game_id' => GAME_EVOL_BALL,
		];
		$this->load_view_page('result/bbresult', 'gameresult', LEVEL_ADMIN, $param);	
	}

	public function epresult_edit($strRoundFid)
	{
		$param = [
			'game_name' => "에볼파워볼",
			'game_id' => GAME_EVOL_BALL,
		];	

		$this->result_edit_page(
			$strRoundFid, 
			'result/bbresult_edit', 
			'gameresult', 
			LEVEL_ADMIN,
			$param);	
	}
	
	public function skresult()
	{
		$param = [
			'game_name' => "스피드키노",
			'game_id' => GAME_SPKN_BALL,
		];
		$this->load_view_page('result/knresult', 'gameresult', LEVEL_ADMIN, $param);	
	}

	public function skresult_edit($strRoundFid)
	{
		$param = [
			'game_name' => "스피드키노",
			'game_id' => GAME_SPKN_BALL,
		];	

		$this->result_edit_page(
			$strRoundFid, 
			'result/knresult_edit', 
			'gameresult', 
			LEVEL_ADMIN,
			$param);	
	}

	public function bbresult()
	{
		$param = [
			'game_name' => "보글볼",
			'game_id' => GAME_BOGLE_BALL,
		];
		$this->load_view_page('result/bbresult', 'gameresult', LEVEL_ADMIN, $param);			
	}

	public function bbresult_edit($strRoundFid)
	{
		$param = [
			'game_name' => "보글볼",
			'game_id' => GAME_BOGLE_BALL,
		];
		
		$this->result_edit_page(
			$strRoundFid, 
			'result/bbresult_edit', 
			'gameresult', 
			LEVEL_ADMIN,
			$param);		
	}


	public function bsresult()
	{
		$param = [
			'game_name' => "보사달",
			'game_id' => GAME_BOGLE_LADDER,
		];	
		$this->load_view_page('result/psresult', 'gameresult', LEVEL_ADMIN, $param);	
	}

	public function bsresult_edit($strRoundFid)
	{
		$param = [
			'game_name' => "보사달",
			'game_id' => GAME_BOGLE_LADDER,
		];
		
		$this->result_edit_page(
			$strRoundFid, 
			'result/psresult_edit', 
			'gameresult', 
			LEVEL_ADMIN,
			$param);	
	}

	public function e5result()
	{	
		$param = [
			'game_name' => "EOS5분",
			'game_id' => GAME_EOS5_BALL,
		];	
		$this->load_view_page('result/bbresult', 'gameresult', LEVEL_ADMIN, $param);			
	}

	public function e5result_edit($strRoundFid)
	{
		$param = [
			'game_name' => "EOS5분",
			'game_id' => GAME_EOS5_BALL,
		];
		
		$this->result_edit_page(
			$strRoundFid, 
			'result/bbresult_edit', 
			'gameresult', 
			LEVEL_ADMIN,
			$param);	
	}
	public function e3result()
	{	
		$param = [
			'game_name' => "EOS3분",
			'game_id' => GAME_EOS3_BALL,
		];	
		$this->load_view_page('result/bbresult', 'gameresult', LEVEL_ADMIN, $param);			
	}

	public function e3result_edit($strRoundFid)
	{
		$param = [
			'game_name' => "EOS3분파워볼",
			'game_id' => GAME_EOS3_BALL,
		];

		$this->result_edit_page(
			$strRoundFid, 
			'result/bbresult_edit', 
			'gameresult', 
			LEVEL_ADMIN,
			$param);	
	}

	public function r5result()
	{	
		$param = [
			'game_name' => "코인5분",
			'game_id' => GAME_COIN5_BALL,
		];	
		$this->load_view_page('result/bbresult', 'gameresult', LEVEL_ADMIN, $param);			
	}

	public function r5result_edit($strRoundFid)
	{
		$param = [
			'game_name' => "코인5분파워볼",
			'game_id' => GAME_COIN5_BALL,
		];
		
		$this->result_edit_page(
			$strRoundFid, 
			'result/bbresult_edit', 
			'gameresult', 
			LEVEL_ADMIN,
			$param);	
	}
	public function r3result()
	{	
		$param = [
			'game_name' => "코인3분",
			'game_id' => GAME_COIN3_BALL,
		];	
		$this->load_view_page('result/bbresult', 'gameresult', LEVEL_ADMIN, $param);			
	}

	public function r3result_edit($strRoundFid)
	{
		$param = [
			'game_name' => "코인3분파워볼",
			'game_id' => GAME_COIN3_BALL,
		];
		
		$this->result_edit_page(
			$strRoundFid, 
			'result/bbresult_edit', 
			'gameresult', 
			LEVEL_ADMIN,
			$param);	
	}

	public function pbbetchange($strDate, $strRoundNo)
	{
		$param = [
			'game_name' => "PBG",
			'game_id' => GAME_PBG_BALL,
		];
		$this->result_change_page('result/pbbet_change', $strDate, $strRoundNo, $param);				
	}

	public function dpbetchange($strDate, $strRoundNo)
	{
		$param = [
			'game_name' => "동행볼",
			'game_id' => GAME_DHP_BALL,
		];
		$this->result_change_page('result/pbbet_change', $strDate, $strRoundNo, $param);		
	}

	public function epbetchange($strDate, $strRoundNo)
	{
		$param = [
			'game_name' => "에볼파워볼",
			'game_id' => GAME_EVOL_BALL,
		];
		$this->result_change_page('result/pbbet_change', $strDate, $strRoundNo, $param);		
	}

	public function skbetchange($strDate, $strRoundNo)
	{
		$param = [
			'game_name' => "스피드키노",
			'game_id' => GAME_SPKN_BALL,
		];
		$this->result_change_page('result/pbbet_change', $strDate, $strRoundNo, $param);		
	}

	public function bbbetchange($strDate, $strRoundNo)
	{
		$param = [
			'game_name' => "보글볼",
			'game_id' => GAME_BOGLE_BALL,
		];
		$this->result_change_page('result/pbbet_change', $strDate, $strRoundNo, $param);		
	}

	public function bsbetchange($strDate, $strRoundNo)
	{
		$param = [
			'game_name' => "보사달",
			'game_id' => GAME_BOGLE_LADDER,
		];	
		$this->result_change_page('result/psbet_change', $strDate, $strRoundNo, $param);			
	}
	
	public function e5betchange($strDate, $strRoundNo)
	{
		$param = [
			'game_name' => "EOS5분",
			'game_id' => GAME_EOS5_BALL,
		];	
		$this->result_change_page('result/pbbet_change', $strDate, $strRoundNo, $param);		
	}

	public function e3betchange($strDate, $strRoundNo)
	{
		$param = [
			'game_name' => "EOS3분",
			'game_id' => GAME_EOS3_BALL,
		];
		$this->result_change_page('result/pbbet_change', $strDate, $strRoundNo, $param);		
	}
	
	public function r5betchange($strDate, $strRoundNo)
	{
		$param = [
			'game_name' => "코인5분",
			'game_id' => GAME_COIN5_BALL,
		];	
		$this->result_change_page('result/pbbet_change', $strDate, $strRoundNo, $param);		
	}

	public function r3betchange($strDate, $strRoundNo)
	{
		$param = [
			'game_name' => "코인3분",
			'game_id' => GAME_COIN3_BALL,
		];
		$this->result_change_page('result/pbbet_change', $strDate, $strRoundNo, $param);		
	}
}