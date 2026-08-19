<?php namespace App\Controllers;

class Mini extends BaseController
{
    public function index()
    {
        if(!is_login(true))
		{
			$this->response->redirect(site_furl('/'));	

        } else {
            $this->setLanguage();
			$this->sess_action();                

            $user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$headInfo = $this->getSiteConf();
			$this->modelConfsite->readBetConf();

            $bPermit = true;
            $game = trim(strtoupper($this->request->getVar('gm')));
            $gameId = 0;
            $navInfo = getMiniInfo();
            if($game === "BGB"){
                $gameId = GAME_BOGLE_BALL;
                if($headInfo['bpg_deny'])
    				$bPermit = false;
                $navInfo['gm_bgb'] = 'active';
                $navInfo['gm_bg'] = 'active';
            } else if($game === "BGL"){
                $gameId = GAME_BOGLE_LADDER;
                if($headInfo['bpg_deny'])
    				$bPermit = false;
                $navInfo['gm_bgl'] = 'active';
                $navInfo['gm_bg'] = 'active';
            } else if($game === "EOS5"){
                $gameId = GAME_EOS5_BALL;
                if($headInfo['eos5_deny'])
    				$bPermit = false;
                $navInfo['gm_e5'] = 'active';
                $navInfo['gm_eos'] = 'active';
            } else if($game === "EOS3"){
                $gameId = GAME_EOS3_BALL;
                if($headInfo['eos3_deny'])
    				$bPermit = false;
                $navInfo['gm_e3'] = 'active';
                $navInfo['gm_eos'] = 'active';
            } else if($game === "COIN5"){
                $gameId = GAME_COIN5_BALL;
                if($headInfo['coin5_deny'])
    				$bPermit = false;
                $navInfo['gm_r5'] = 'active';
                $navInfo['gm_ro'] = 'active';
            } else if($game === "COIN3"){
                $gameId = GAME_COIN3_BALL;
                if($headInfo['coin3_deny'])
    				$bPermit = false;
                $navInfo['gm_r3'] = 'active';
                $navInfo['gm_ro'] = 'active';
            } else if($game === "PBG"){
                $gameId = GAME_PBG_BALL;
                if($headInfo['pbg_deny'])
    				$bPermit = false;
                $navInfo['gm_pbg'] = 'active';
            } else if($game === "SPKN"){
                $gameId = GAME_SPKN_BALL;
                if($headInfo['spk_deny'])
    				$bPermit = false;
                $navInfo['gm_spk'] = 'active';
            } else if($game === "EVP"){
                $gameId = GAME_EVOL_BALL;
                if($headInfo['evp_deny'])
    				$bPermit = false;
                $navInfo['gm_evp'] = 'active';
            } else if($game === "DHP"){
                $gameId = GAME_DHP_BALL;
                if($headInfo['dhp_deny'])
    				$bPermit = false;
                $navInfo['gm_dhp'] = 'active';
            } else 
                $bPermit = false;

			if(is_null($objMember))
				$bPermit = false;

            if($bPermit){
                $navInfo['gm_ref'] = $game;
                $navInfo['game_id'] = $gameId;
                $navInfo += getNavInfo($objMember);
                $navInfo['rate'] = $this->modelConfgame->find($gameId);

                echo view('mini/header', $headInfo);
                echo view('mini/navbar', $navInfo);	
                if($gameId == GAME_BOGLE_LADDER)
                    echo view('mini/pladd');	
                else if($gameId == GAME_SPKN_BALL)
                    echo view('mini/keno');	
                else echo view('mini/pball');	
                echo view('mini/pfooter');	
            } else {
                // $this->response->redirect(site_furl('/logout'));	
            }

        }
    }

    public function betlist()
    {
        if(!is_login(true))
		{
			$this->response->redirect(site_furl('/'));	

        } else {
            $this->setLanguage();
			$this->sess_action();                
            $user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$headInfo = $this->getSiteConf();

            $bPermit = true;
            $game = trim(strtoupper($this->request->getVar('gm')));
            $gameId = 0;
            $navInfo = getMiniInfo();
            if($game === "BGB"){
                $gameId = GAME_BOGLE_BALL;
            } else if($game === "BGL"){
                $gameId = GAME_BOGLE_LADDER;
            } else if($game === "EOS5"){
                $gameId = GAME_EOS5_BALL;
            } else if($game === "EOS3"){
                $gameId = GAME_EOS3_BALL;
            } else if($game === "COIN5"){
                $gameId = GAME_COIN5_BALL;
            } else if($game === "COIN3"){
                $gameId = GAME_COIN3_BALL;
            } else if($game === "PBG"){
                $gameId = GAME_PBG_BALL;
            } else if($game === "SPKN"){
                $gameId = GAME_SPKN_BALL;
            } else if($game === "DHP"){
                $gameId = GAME_DHP_BALL;
            } else 
                $bPermit = false;

            if(is_null($objMember))
				$bPermit = false;

            if($bPermit){
                $navInfo['gm_ref'] = $game;
                $navInfo['game_id'] = $gameId;
                $navInfo += getNavInfo($objMember);
                $navInfo['ls_bet'] = 'active';
				$dates = getDatesInfo();

                echo view('mini/header', $headInfo);
                echo view('mini/navbar', $navInfo);	
                echo view('mini/betlist', array('dates'=>$dates));	
            } else {
                // $this->response->redirect(site_furl('/logout'));	
            }
        }
    }

    public function rndlist()
    {
        if(!is_login(true))
		{
			$this->response->redirect(site_furl('/'));	

        } else {
            $this->setLanguage();
			$this->sess_action();                
            $user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$headInfo = $this->getSiteConf();

            $bPermit = true;
            $game = trim(strtoupper($this->request->getVar('gm')));
            $gameId = 0;
            $navInfo = getMiniInfo();
            if($game === "BGB"){
                $gameId = GAME_BOGLE_BALL;
            } else if($game === "BGL"){
                $gameId = GAME_BOGLE_LADDER;
            } else if($game === "EOS5"){
                $gameId = GAME_EOS5_BALL;
            } else if($game === "EOS3"){
                $gameId = GAME_EOS3_BALL;
            } else if($game === "COIN5"){
                $gameId = GAME_COIN5_BALL;
            } else if($game === "COIN3"){
                $gameId = GAME_COIN3_BALL;
            } else if($game === "PBG"){
                $gameId = GAME_PBG_BALL;
            } else if($game === "SPKN"){
                $gameId = GAME_SPKN_BALL;
            } else if($game === "DHP"){
                $gameId = GAME_DHP_BALL;
            } else 
                $bPermit = false;

            if(is_null($objMember))
				$bPermit = false;

            if($bPermit){
                $navInfo['gm_ref'] = $game;
                $navInfo['game_id'] = $gameId;
                $navInfo += getNavInfo($objMember);
                $navInfo['ls_rnd'] = 'active';
				$dates = getDatesInfo();

                echo view('mini/header', $headInfo);
                echo view('mini/navbar', $navInfo);	
                echo view('mini/rndlist', array('dates'=>$dates));	
            } else {
                // $this->response->redirect(site_furl('/logout'));	
            }
        }
    }

}