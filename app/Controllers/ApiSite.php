<?php namespace App\Controllers;

use App\Models\MoneyHist_Model;
use App\Models\Reward_Model;

use App\Models\Round_Model;
use App\Models\Bet_Model;

class ApiSite extends BaseController
{
    // private $modelMoneyhist;
    private $modelReward;
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

        header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		
        // $this->modelMoneyhist = new MoneyHist_Model();
        $this->modelReward = new Reward_Model();
    }

    public function apibet(){
        $result = new \StdClass;

        //apibet?game=1&id=test&pwd=1234&balance=0|0|0|0|0|0|0|0|0|0|0
        $arrData['game'] = intval($this->request->getVar('game'));
        $arrData['id'] = trim($this->request->getVar('id'));
        $arrData['pwd'] = trim($this->request->getVar('pwd'));
        $arrData['balance'] = trim($this->request->getVar('balance'));

        $result->status = STATUS_FAIL;
        $result->code = 0;

        $objUser = $this->modelMember->login($arrData['id'], $arrData['pwd']);
        if(is_null($objUser)){
            $result->status = STATUS_FAIL;
            $result->code = 1;
        } else if( diffDt(date('Y-m-d H:i:s'), $objUser->mb_time_call) < DELAY_APIBET ) {
            $result->status = STATUS_FAIL;
            $result->code = 8;
        } else if(!checkApiUri($arrData)){
            $result->status = STATUS_FAIL;
            $result->code = RESULT_FAIL;
		} else if($this->modelConfsite->IsMaintain() || !$this->modelMember->isPermitMember($objUser)){
            $result->status = STATUS_FAIL;
            $result->code = RESULT_STOP;	

        } else {
            $this->modelMember->updateCallTm($objUser);
			$this->modelConfsite->readBetConf();

            $objUser->emp_state_active = STATE_ACTIVE;
            $objConfig = null;
            if($arrData['game'] == 1)
			    $objConfig = $this->modelConfgame->find(GAME_PBG_BALL);
            else if($arrData['game'] == 2)
                $objConfig = $this->modelConfgame->find(GAME_DHP_BALL);
            else if($arrData['game'] == 3)
                $objConfig = $this->modelConfgame->find(GAME_BOGLE_BALL);
            else if($arrData['game'] == 4)
                $objConfig = $this->modelConfgame->find(GAME_BOGLE_LADDER);
            else if($arrData['game'] == 5)
                $objConfig = $this->modelConfgame->find(GAME_EOS5_BALL);
            else if($arrData['game'] == 6)
                $objConfig = $this->modelConfgame->find(GAME_EOS3_BALL);
            else if($arrData['game'] == 7)
                $objConfig = $this->modelConfgame->find(GAME_COIN5_BALL);
            else if($arrData['game'] == 8)
                $objConfig = $this->modelConfgame->find(GAME_COIN3_BALL);
        
            
            $arrBalance = checkApiBalance($arrData['balance']);
            $fRate = 100 ; //floatval($arrData['rate']);

            $iResult = 0;
            $iSubResult = 0;

            $modelBet = new Bet_Model();
            $modelRound = new Round_Model();				

			if(is_null($objConfig)){
				$iSubResult = 9;
            } else if(count($arrBalance) < 1){
				$iSubResult = 2;
			} else if($arrData['game'] == 1 || $arrData['game'] == 2 || $arrData['game'] == 3 
                || $arrData['game'] == 5 || $arrData['game'] == 6
                || $arrData['game'] == 7 || $arrData['game'] == 8
                ) {	                                    
                if($arrData['game'] == 1){                  //PBG
                    $arrBetData['game'] = GAME_PBG_BALL;
				
                    $arrRoundInfo = $modelRound->gets(1);
                    $arrRoundData = getPbRoundTimes($objConfig);
                    $iMoneyType = MONEYCHANGE_BET_PB;
                } else if($arrData['game'] == 2){                      //Dhp
                    $arrBetData['game'] = GAME_DHP_BALL;

                    $arrRoundInfo = $modelRound->gets(1);
                    $arrRoundData = getPbRoundTimes($objConfig);
                    $iMoneyType = MONEYCHANGE_BET_DH;
                    
                } else if($arrData['game'] == 3){           //Bogle
                    $arrBetData['game'] = GAME_BOGLE_BALL;
                    
                    $arrRoundInfo = $modelRound->gets(1);
                    $arrRoundData = getBbRoundTimes($objConfig);
                    $iMoneyType = MONEYCHANGE_BET_BB;
                } else if($arrData['game'] == 5){           //EOS5M
                    $arrBetData['game'] = GAME_EOS5_BALL;
				
                    $arrRoundInfo = $modelRound->gets(1);
                    $arrRoundData = getPbRoundTimes($objConfig);
                    $iMoneyType = MONEYCHANGE_BET_EO5;
                } else if($arrData['game'] == 6){           //EOS3M
                    $arrBetData['game'] = GAME_EOS3_BALL;

                    $arrRoundInfo = $modelRound->gets(1);
                    $arrRoundData = getBsRoundTimes($objConfig);
                    $iMoneyType = MONEYCHANGE_BET_EO3;
                } else if($arrData['game'] == 7){           //Coin5M
                    $arrBetData['game'] = GAME_COIN5_BALL;
				
                    $arrRoundInfo = $modelRound->gets(1);
                    $arrRoundData = getPbRoundTimes($objConfig);
                    $iMoneyType = MONEYCHANGE_BET_CO5;
                } else if($arrData['game'] == 8){           //Coin3M
                    $arrBetData['game'] = GAME_COIN3_BALL;

                    $arrRoundInfo = $modelRound->gets(1);
                    $arrRoundData = getBsRoundTimes($objConfig);
                    $iMoneyType = MONEYCHANGE_BET_CO3;
                } 
                
                $arrBetData['roundno'] = $arrRoundData['round_no'];
				$arrBetData['roundid'] = count($arrRoundInfo) == 0 ? 1 : (int)($arrRoundInfo[0]->round_fid) + 1;
				
                //Valid Bet Time
				if(!isEnableBetTime($arrRoundData)) {
					$objUser->emp_state_active = STATE_DISABLE;
				}

                if(count($arrBalance) >= 8 ){
                    for($i = 0; $i <8; $i ++){
                        $iMode = (int)floor($i/2) + 1;
                        
                        $arrBetData['mode'] = $iMode;
                        $arrBetData['target'] = $i%2==0? "P":"B";
                        $arrBetData['amount'] = $arrBalance[$i];
                        
                        if($arrBetData['amount'] < 1)
                            continue;

                        $iBetResult = $this->apiGameBet($arrBetData, $objUser, $objConfig, $arrRoundData, $modelBet, $iMoneyType);
                        if($iBetResult == 1){
                            $iResult = 1;
                            $objUser->mb_money -= $arrBetData['amount'];
                        }
                        else {
                            if($iSubResult == 0)
                                $iSubResult = $iBetResult;
                        }
                        
                    }

                    $nCount = count($arrBalance);
                    if($nCount > 51)
                        $nCount = 51;
                    //Mix Bet
                    for($i = 8; $i <$nCount; $i ++){
                        $iMode = $i - 3;
                        if($iMode >= 30 && $iMode <= 37){
                            $arrBetData['mode'] = $iMode+1;
                            $arrBetData['target'] = "R";
                        }
                        else if($iMode >= 38 && $iMode <= 47){
                            $arrBetData['mode'] = 30;
                            $arrBetData['target'] = $iMode-38;
                        }
                        else{
                            $arrBetData['mode'] = $iMode;
                            $arrBetData['target'] = "R";
                        }
                        
                        $arrBetData['amount'] = $arrBalance[$i];
                        $arrBetData['amount'] = (int)round($arrBetData['amount'] * $fRate / 100);
                         
                        if($arrBetData['amount'] < 1)
                            continue;

                        $iBetResult = $this->apiGameBet($arrBetData, $objUser, $objConfig, $arrRoundData, $modelBet, $iMoneyType);
                        
                        if($iBetResult == 1){
                            $iResult = 1;
                            $objUser->mb_money -= $arrBetData['amount'];
                        }
                        else {
                            if($iSubResult == 0)
                                $iSubResult = $iBetResult;
                        }
                        
                    }
                }

				
			} else if( $arrData['game'] == 4 ){				
				if($arrData['game'] == 4){				//Bogleladder
                    $arrBetData['game'] = GAME_BOGLE_LADDER;
                    
                    $arrRoundInfo = $modelRound->gets(1);
                    $arrRoundData = getBsRoundTimes($objConfig);
                    $iMoneyType = MONEYCHANGE_BET_BS;
    
                }    
                
                $arrBetData['roundno'] = $arrRoundData['round_no'];
				$arrBetData['roundid'] = count($arrRoundInfo) == 0 ? 1 : (int)($arrRoundInfo[0]->round_fid) + 1;
                //Maintain
				if(!isEnableBetTime($arrRoundData)) {
					$objUser->emp_state_active = STATE_DISABLE;
				}

                if(count($arrBalance) >= 6 ){
                    for($i = 0; $i <6; $i ++){
                        $iMode = (int)floor($i/2) + 1;
                        
                        $arrBetData['mode'] = $iMode;
                        $arrBetData['target'] = $i%2==0? "P":"B";
                        $arrBetData['amount'] = $arrBalance[$i];
                        
                        if($arrBetData['amount'] < 1)
                            continue;

                        $iBetResult = $this->apiGameBet($arrBetData, $objUser, $objConfig, $arrRoundData, $modelBet, $iMoneyType);
                        if($iBetResult == 1){
                            $iResult = 1;
                            $objUser->mb_money -= $arrBetData['amount'];
                        }
                        else {
                            if($iSubResult == 0)
                                $iSubResult = $iBetResult;
                        }
                    }

                    $nCount = count($arrBalance);
                    if($nCount > 10)
                        $nCount = 10;
                    //Mix Bet
                    for($i = 6; $i <$nCount; $i ++){
                        $iMode = $i - 2;
                        $arrBetData['mode'] = $iMode;
                        $arrBetData['target'] = "R";
                        $arrBetData['amount'] = $arrBalance[$i];
                        $arrBetData['amount'] = (int)round($arrBetData['amount'] * $fRate / 100);
                         
                        if($arrBetData['amount'] < 1)
                            continue;

                        $iBetResult = $this->apiGameBet($arrBetData, $objUser, $objConfig, $arrRoundData, $modelBet, $iMoneyType);
                        if($iBetResult == 1){
                            $iResult = 1;
                            $objUser->mb_money -= $arrBetData['amount'];
                        }
                        else {
                            if($iSubResult == 0)
                                $iSubResult = $iBetResult;
                        }
                    }
                }
			
			} else {
				$result->asdf = $arrData['game'];

                $iSubResult = 9;           //No Exist Game
            }				

            $objUser = $this->modelMember->getByUid($objUser->mb_uid);
            $result->money = intval($objUser->mb_money);

            if($iResult == 1){
				$result->status = "success";
				$result->code = 0;
			} else{
				$result->status = "fail";				
                $result->code = $iSubResult;
            } 

        }

		echo json_encode($result);	

    }


    private function  apiGameBet($arrBetData, &$objUser, $objConfig, $arrRoundData, $modelBet, $iMoneyType){

        $iBetId = 0;
        $iResult = isEnableApiBet($arrBetData, $objUser, $objConfig, $arrRoundData);
        if($iResult == 1){
            $arrEmpRatio = $this->modelMember->getEmployeeRatio($objUser, $arrBetData['amount'], $arrBetData['game'], $arrBetData['mode']);
            //Change User money
            if($this->modelMember->updateAssets($objUser, 0-$arrBetData['amount'], 0, $iMoneyType)){
                $iBetId = $modelBet->register($arrBetData, $objUser);
                // $this->modelMoneyhist->registerBet($objUser, $arrBetData, $iMoneyType);
            }
        }

        if($iResult == 1 && $iBetId > 0){			
            // $this->modelMember->updateRewards($arrEmpRatio);
            $this->modelReward->register($arrBetData['game'], $iBetId, $arrEmpRatio);
        }
        return $iResult;
    }

    
}