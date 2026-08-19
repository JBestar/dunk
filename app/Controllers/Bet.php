<?php
namespace App\Controllers;
use App\Models\SlotPrd_Model;
use App\Models\CasPrd_Model;
use App\Models\ConfSite_Model;

class Bet extends StdController {

	/**

	 */
	public function index()
	{
				
		if(is_login())
		{
			$this->response->redirect($_ENV['app.furl'].'/bet/pbrealtime', 'refresh');
		}
		else {
			$this->response->redirect( $_ENV['app.furl'].'/pages/login', 'refresh');
		}	
		
	}

	public function pbrealtime(){
		
		$param = [
			'game_name' => "PBG",
			'game_id' => GAME_PBG_BALL,
		];

		$this->load_view_page('bet/pbrealtime', 'bet_realtime', LEVEL_ADMIN, $param);
	}

	public function dprealtime(){
		
		$param = [
			'game_name' => "동행볼",
			'game_id' => GAME_DHP_BALL,
		];
		$this->load_view_page('bet/pbrealtime', 'bet_realtime', LEVEL_ADMIN, $param);
	}

	public function eprealtime(){
		
		$param = [
			'game_name' => "에볼파워볼",
			'game_id' => GAME_EVOL_BALL,
		];
		$this->load_view_page('bet/pbrealtime', 'bet_realtime', LEVEL_ADMIN, $param);
	}

	public function skrealtime(){
		
		$param = [
			'game_name' => "스피드키노",
			'game_id' => GAME_SPKN_BALL,
		];
		$this->load_view_page('bet/knrealtime', 'bet_realtime', LEVEL_ADMIN, $param);
	}

	public function bbrealtime(){
		
		$param = [
			'game_name' => "보글볼",
			'game_id' => GAME_BOGLE_BALL,
		];

		$this->load_view_page('bet/pbrealtime', 'bet_realtime', LEVEL_ADMIN, $param);
	}

	public function bsrealtime(){
		
		$param = [
			'game_name' => "보사달",
			'game_id' => GAME_BOGLE_LADDER,
		];
		$this->load_view_page('bet/psrealtime', 'bet_realtime', LEVEL_ADMIN, $param);
	}

	public function e5realtime(){
		
		$param = [
			'game_name' => "EOS5분",
			'game_id' => GAME_EOS5_BALL,
		];

		$this->load_view_page('bet/pbrealtime', 'bet_realtime', LEVEL_ADMIN, $param);
	}
	
	public function e3realtime(){
		
		$param = [
			'game_name' => "EOS3분",
			'game_id' => GAME_EOS3_BALL,
		];

		$this->load_view_page('bet/pbrealtime', 'bet_realtime', LEVEL_ADMIN, $param);
	}

	public function r5realtime(){
		
		$param = [
			'game_name' => "코인5분",
			'game_id' => GAME_COIN5_BALL,
		];

		$this->load_view_page('bet/pbrealtime', 'bet_realtime', LEVEL_ADMIN, $param);
	}
	
	public function r3realtime(){
		
		$param = [
			'game_name' => "코인3분",
			'game_id' => GAME_COIN3_BALL,
		];

		$this->load_view_page('bet/pbrealtime', 'bet_realtime', LEVEL_ADMIN, $param);
	}

	public function pbhistory(){
		$param = [
			'game_name' => "PBG",
			'game_id' => GAME_PBG_BALL,
		];
		$this->load_view_page('bet/pbhistory', 'bet_history', LEVEL_MIN, $param);
	}

	public function dphistory(){
		$param = [
			'game_name' => "동행볼",
			'game_id' => GAME_DHP_BALL,
		];
		$this->load_view_page('bet/pbhistory', 'bet_history', LEVEL_MIN, $param);
	}

	public function ephistory(){
		$param = [
			'game_name' => "에볼파워볼",
			'game_id' => GAME_EVOL_BALL,
		];
		$this->load_view_page('bet/pbhistory', 'bet_history', LEVEL_MIN, $param);
	}
	
	public function skhistory(){
		$param = [
			'game_name' => "스피드키노",
			'game_id' => GAME_SPKN_BALL,
		];
		$this->load_view_page('bet/knhistory', 'bet_history', LEVEL_MIN, $param);
	}

	public function cshistory(){
		$modelCasprd = new CasPrd_Model();
		$arrPrd = [];

		$confsiteModel = new ConfSite_Model();
		$confs = $this->getSiteConf($confsiteModel);
		if(!$confs["evol_deny"]){
			$arrPrd +=  $modelCasprd->gets(GAME_CASINO_EVOL);
		}
		if(!$confs["cas_deny"]){
			$gameId = GAME_CASINO_KGON;
			if($_ENV['app.casino'] == APP_CASINO_STAR)
				$gameId = GAME_CASINO_STAR;
			else if($_ENV['app.casino'] == APP_CASINO_RAVE)
				$gameId = GAME_CASINO_RAVE;
			else if($_ENV['app.casino'] == APP_CASINO_TREEM)
				$gameId = GAME_CASINO_TREEM;
			else if($_ENV['app.casino'] == APP_CASINO_SIGMA)
				$gameId = GAME_CASINO_SIGMA;

			$arrKgon =  $modelCasprd->gets($gameId);
			foreach($arrKgon as $objPrd){
				array_push($arrPrd, $objPrd);
			}
		}

		$param = [
			'prds' => $arrPrd,
			'game_name' => "카지노",
			'game_id' => GAME_CASINO_EVOL,
		];
		$this->load_view_page('bet/cshistory', 'bet_history', 0, $param);
	}
	
	public function evhistory(){
		$modelCasprd = new CasPrd_Model();
		$arrPrd = [];

		$confsiteModel = new ConfSite_Model();
		$confs = $this->getSiteConf($confsiteModel);

		$prd = new \StdClass;
		$prd->vendor_id = 0;
		$prd->name = "에볼루션";
		array_push($arrPrd, $prd);

		$arrPrd +=  $modelCasprd->gets(GAME_CASINO_EVOL);

		$param = [
			'prds' => $arrPrd,
			'game_name' => "에볼루션",
			'game_id' => GAME_AUTO_EVOL,
		];
		$this->load_view_page('bet/cshistory', 'bet_history', 0, $param);
	}

	public function prhistory(){
		$modelCasprd = new CasPrd_Model();
		$arrPrd = [];

		$confsiteModel = new ConfSite_Model();
		$confs = $this->getSiteConf($confsiteModel);
		$prd = new \StdClass;
		$prd->vendor_id = 0;
		$prd->name = "프라그마틱";
		array_push($arrPrd, $prd);

		$param = [
			'prds' => $arrPrd,
			'game_name' => "프라그마틱",
			'game_id' => GAME_AUTO_PRAG,
		];
		$this->load_view_page('bet/cshistory', 'bet_history', 0, $param);
	}

	public function ebalhistory(){
		$confsiteModel = new ConfSite_Model();

		$param = [
			'game_title' => "에볼밸런스",
			'game_name' => "밸런스내역",
			'game_id' => GAME_AUTO_EVOL,
			'evpress' => $confsiteModel->getEvpressState(),
		];
		$this->load_view_page('ebal/ebalhistory', 'conf_ebal', LEVEL_ADMIN, $param);
	}
	
	public function pbalhistory(){
		$confsiteModel = new ConfSite_Model();

		$param = [
			'game_title' => "프라그밸런스",
			'game_name' => "밸런스내역",
			'game_id' => GAME_AUTO_PRAG,
			'evpress' => $confsiteModel->getEvpressState(),
		];
		$this->load_view_page('ebal/ebalhistory', 'conf_pbal', LEVEL_ADMIN, $param);
	}

	public function hlhistory(){
		$param = [
			'game_name' => "홀덤",
			'game_id' => GAME_HOLD_CMS,
		];
		$this->load_view_page('bet/hlhistory', 'bet_history', LEVEL_MIN, $param);
	}

	/*
	public function eordroom(){
		$confsiteModel = new ConfSite_Model();

		$param = [
			'game_name' => "실시간",
			'game_id' => GAME_AUTO_EVOL,
			'evpress' => $confsiteModel->getEvpressState(),
		];
		$this->load_view_page('ebal/eordroom', 'conf_ebal', LEVEL_ADMIN, $param);
	}
	*/

	public function ebethistory(){
		$confsiteModel = new ConfSite_Model();

		$param = [
			'game_title' => "에볼밸런스",
			'game_name' => "배팅내역",
			'game_id' => GAME_AUTO_EVOL,
			'evpress' => $confsiteModel->getEvpressState(),
		];
		$this->load_view_page('ebal/ebethistory', 'conf_ebal', LEVEL_ADMIN, $param);
	}

	public function pbethistory(){
		$confsiteModel = new ConfSite_Model();

		$param = [
			'game_title' => "프라그밸런스",
			'game_name' => "배팅내역",
			'game_id' => GAME_AUTO_PRAG,
			'evpress' => $confsiteModel->getEvpressState(),
		];
		$this->load_view_page('ebal/ebethistory', 'conf_pbal', LEVEL_ADMIN, $param);
	}

	public function bbhistory(){
		
		$param = [
			'game_name' => "보글볼",
			'game_id' => GAME_BOGLE_BALL,
		];
		$this->load_view_page('bet/pbhistory', 'bet_history', LEVEL_MIN, $param);
	}

	public function bshistory(){
		$param = [
			'game_name' => "보사달",
			'game_id' => GAME_BOGLE_LADDER,
		];
		$this->load_view_page('bet/pshistory', 'bet_history', LEVEL_MIN, $param);
	}
	
	public function e5history(){
		
		$param = [
			'game_name' => "EOS5분",
			'game_id' => GAME_EOS5_BALL,
		];
		$this->load_view_page('bet/pbhistory', 'bet_history', LEVEL_MIN, $param);
	}
	
	public function e3history(){
		
		$param = [
			'game_name' => "EOS3분",
			'game_id' => GAME_EOS3_BALL,
		];
		$this->load_view_page('bet/pbhistory', 'bet_history', LEVEL_MIN, $param);
	}

	public function r5history(){
		
		$param = [
			'game_name' => "코인5분",
			'game_id' => GAME_COIN5_BALL,
		];
		$this->load_view_page('bet/pbhistory', 'bet_history', LEVEL_MIN, $param);
	}

	
	public function r3history(){
		
		$param = [
			'game_name' => "코인3분",
			'game_id' => GAME_COIN3_BALL,
		];
		$this->load_view_page('bet/pbhistory', 'bet_history', LEVEL_MIN, $param);
	}

	public function xslhistory(){
		$modelSlotprd = new SlotPrd_Model();
		$gameId = GAME_SLOT_THEPLUS;
		if($_ENV['app.slot'] == APP_SLOT_KGON)
			$gameId = GAME_SLOT_KGON;
		else if($_ENV['app.slot'] == APP_SLOT_STAR)
			$gameId = GAME_SLOT_STAR;
		else if($_ENV['app.slot'] == APP_SLOT_RAVE)
			$gameId = GAME_SLOT_RAVE;
		else if($_ENV['app.slot'] == APP_SLOT_TREEM)
			$gameId = GAME_SLOT_TREEM;
		else if($_ENV['app.slot'] == APP_SLOT_SIGMA)
			$gameId = GAME_SLOT_SIGMA;
		$arrPrd = $modelSlotprd->gets($gameId);

		$param = [
			'game_name' => "정품슬롯",
			'game_id' => $gameId,
			'prds' => $arrPrd,
		];

		$this->load_view_page('bet/slhistory', 'bet_history', LEVEL_ADMIN, $param);
	}

	public function fslhistory(){
		
		$modelSlotprd = new SlotPrd_Model();

		$gameId = GAME_SLOT_GSPLAY;
		if($_ENV['app.fslot'] == APP_FSLOT_GOLD)
			$gameId = GAME_SLOT_GOLD;
		
		$arrPrd = $modelSlotprd->gets($gameId);

		$param = [
			'game_name' => "네츄럴슬롯",
			'game_id' => $gameId,
			'prds' => $arrPrd,
		];

		$this->load_view_page('bet/slhistory', 'bet_history', LEVEL_ADMIN, $param);
	}

	public function slhistory(){
		
		$modelSlotprd = new SlotPrd_Model();
		$gameId = GAME_SLOT_ALL;
		if($_ENV['app.type'] == APP_TYPE_2){
			if($_ENV['app.fslot'] == APP_FSLOT_GOLD)
				$arrPrd = $modelSlotprd->gets(GAME_SLOT_GOLD);
			else 
				$arrPrd = $modelSlotprd->gets(GAME_SLOT_GSPLAY);
		}
		else {
			if($_ENV['app.slot'] == APP_SLOT_THEPLUS)
				$arrPrd = $modelSlotprd->gets(GAME_SLOT_THEPLUS);
			else if($_ENV['app.slot'] == APP_SLOT_KGON)
				$arrPrd = $modelSlotprd->gets(GAME_SLOT_KGON);
			else if($_ENV['app.slot'] == APP_SLOT_STAR)
				$arrPrd = $modelSlotprd->gets(GAME_SLOT_STAR);
			else if($_ENV['app.slot'] == APP_SLOT_RAVE)
				$arrPrd = $modelSlotprd->gets(GAME_SLOT_RAVE);
			else if($_ENV['app.slot'] == APP_SLOT_TREEM)
				$arrPrd = $modelSlotprd->gets(GAME_SLOT_TREEM);
			else if($_ENV['app.slot'] == APP_SLOT_SIGMA)
				$arrPrd = $modelSlotprd->gets(GAME_SLOT_SIGMA);
		}

		$param = [
			'game_name' => "슬롯",
			'game_id' => $gameId,
			'prds' => $arrPrd,
		];

		$this->load_view_page('bet/slhistory', 'bet_history', 0, $param);
	}

	public function allcalculate(){
		$param = [
			'game_id' => 0,
		];
		$this->load_view_page('bet/calculate_all', 'bet_calculate', 0, $param);
	}

	public function pbcalculate(){
		
		$param = [
			'game_name' => "PBG",
			'game_id' => GAME_PBG_BALL,
		];
		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}

	public function dpcalculate(){
		$param = [
			'game_name' => "동행볼",
			'game_id' => GAME_DHP_BALL,
		];
		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}

	public function epcalculate(){
		$param = [
			'game_name' => "에볼파워볼",
			'game_id' => GAME_EVOL_BALL,
		];
		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}
	
	public function skcalculate(){
		$param = [
			'game_name' => "스피드키노",
			'game_id' => GAME_SPKN_BALL,
		];
		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}

	public function cscalculate(){
		$param = [
			'game_name' => "카지노",
			'game_id' => GAME_CASINO_EVOL,
		];
		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}

	public function evcalculate(){
		$param = [
			'game_name' => "에볼루션",
			'game_id' => GAME_AUTO_EVOL,
		];
		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}

	public function prcalculate(){
		$param = [
			'game_name' => "프라그마틱",
			'game_id' => GAME_AUTO_PRAG,
		];
		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}

	public function xslcalculate(){
		$gameId = GAME_SLOT_THEPLUS;
		if($_ENV['app.slot'] == APP_SLOT_KGON)
			$gameId = GAME_SLOT_KGON;
		else if($_ENV['app.slot'] == APP_SLOT_STAR)
			$gameId = GAME_SLOT_STAR;
		else if($_ENV['app.slot'] == APP_SLOT_RAVE)
			$gameId = GAME_SLOT_RAVE;
		else if($_ENV['app.slot'] == APP_SLOT_TREEM)
			$gameId = GAME_SLOT_TREEM;
		else if($_ENV['app.slot'] == APP_SLOT_SIGMA)
			$gameId = GAME_SLOT_SIGMA;

		$param = [
			'game_name' => "정품슬롯",
			'game_id' => $gameId,
		];

		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_ADMIN, $param);
	}
	
	public function fslcalculate(){
		$gameId = GAME_SLOT_GSPLAY;
		if($_ENV['app.fslot'] == APP_FSLOT_GOLD)
			$gameId = GAME_SLOT_GOLD;
		
		$param = [
			'game_name' => "네츄럴슬롯",
			'game_id' => $gameId,
		];

		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_ADMIN, $param);
	}

	public function slcalculate(){
		$param = [
			'game_name' => "슬롯",
			'game_id' => GAME_SLOT_ALL,
		];

		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}

	public function hlcalculate(){
		$param = [
			'game_name' => "홀덤",
			'game_id' => GAME_HOLD_CMS,
		];

		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}

	public function bbcalculate(){
		$param = [
			'game_name' => "보글볼",
			'game_id' => GAME_BOGLE_BALL,
		];
		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}

	public function bscalculate(){
		$param = [
			'game_name' => "보사달",
			'game_id' => GAME_BOGLE_LADDER,
		];
		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}
	
	public function e5calculate(){
		$param = [
			'game_name' => "EOS5분",
			'game_id' => GAME_EOS5_BALL,
		];
		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}
	
	public function e3calculate(){
		$param = [
			'game_name' => "EOS3분",
			'game_id' => GAME_EOS3_BALL,
		];
		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}
	
	public function r5calculate(){
		$param = [
			'game_name' => "코인5분",
			'game_id' => GAME_COIN5_BALL,
		];
		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}
	
	public function r3calculate(){
		$param = [
			'game_name' => "코인3분",
			'game_id' => GAME_COIN3_BALL,
		];
		$this->load_view_page('bet/calculate_game', 'bet_calculate', LEVEL_MIN, $param);
	}

}