
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
    <body oncontextmenu="return false" ondragstart="return false" onselectstart="return false" class="theme01 slot_black">
<?php else : ?>
    <body class="theme01 slot_black">
<?php endif ?>

    <script src="<?php echo site_furl('/js/mini/navbar.js?v=1'); ?>"></script>

    <div id="wrap">
        <div class="loading" style="display: none;"></div>
        
        <div class="alert_wrap basic_alert" id="basic_alert">
            <div class="alert_bot">
                <p class="question_ico" id="alert_content"></p>
                <div class="btn_wrap">
                    <a onclick="location.href='javascript:okAlert()';" class="btn btn_point02" id="basic_ok" style="cursor: pointer;"><?=lang('common.ok')?></a>
                    <a onclick="location.href='javascript:closeAlert()';" class="btn" style="cursor: pointer;"><?=lang('common.cancel')?></a>
                </div>
            </div>
        </div>

        <div class="alert_wrap basic_alert" id="basic_alert2">
            <div class="alert_bot">
                <p class="list_wrap" id="alert2_content"></p>
                <div class="btn_wrap">
                    <a class="btn btn_point02" id="basic2_ok" style="cursor: pointer;"><?=lang('common.ok')?></a>
                    <a class="btn" id="basic2_cancel" style="cursor: pointer;"><?=lang('common.cancel')?></a>
                </div>
            </div>
        </div>
        <div class="alert_wrap confirm_alert" id="confirm_alert" style="display: none;">
            <div class="alert_bot">
                <p class="question_ico" id="alert_content"><?=lang('common.game_select_amount')?></p>
                <div class="btn_wrap">
                    <a class="btn" id="confirm_ok" style="cursor: pointer;"><?=lang('common.ok')?></a>
                </div>
            </div>
        </div>
        <section id="container"  style="margin-top: 0px;">

            <!--//lnb -->
            <div class="content_wrap">
                <div class="subTitle_game">
                    <?php if($game_id == GAME_PBG_BALL) : ?>
                        <h2> <?=lang('common.powerball_pbg')?> </h2>
                    <?php elseif($game_id == GAME_BOGLE_BALL) : ?>
                        <h2> <?=lang('common.powerball_boggle')?> </h2>
                    <?php elseif($game_id == GAME_BOGLE_LADDER) : ?>
                        <h2> <?=lang('common.powerladder_boggle')?> </h2>
                    <?php elseif($game_id == GAME_EOS5_BALL) : ?>
                        <h2> <?=lang('common.powerball_eos5')?> </h2>
                    <?php elseif($game_id == GAME_EOS3_BALL) : ?>
                        <h2> <?=lang('common.powerball_eos3')?> </h2>
                    <?php elseif($game_id == GAME_COIN5_BALL) : ?>
                        <h2> <?=lang('common.powerball_coin5')?> </h2>
                    <?php elseif($game_id == GAME_COIN3_BALL) : ?>
                        <h2> <?=lang('common.powerball_coin3')?> </h2>
                    <?php elseif($game_id == GAME_SPKN_BALL) : ?>
                        <h2> <?=lang('common.powerball_spkn')?> </h2>
                    <?php else :?>
                        <h2> <?=lang('common.powerball_dhp')?> </h2>
                    <?php endif ?>
                    <div class="user-info-part"><?=lang('common.money')?>:<span id="h_money"><?=$user_money?></span> 
                        <?=lang('common.point')?>:<span id="h_point"><?=$user_point?></span>
                    </div>
                </div>
                <ul class="gamezone-menu result_menu">
                    <?php if(!$pbg_deny) :?>
                    <li class="dropdown">
                        <!-- <a class="<?=$gm_pbg?>" href="/mini?gm=PBG"><?=lang('common.game_pbg')?></a> -->
                        <button class="dropbtn <?=$gm_pbg?>" onclick="location.href=FURL+'/mini?gm=PBG'"><?=lang('common.game_pbg')?></button>
                    </li>
                    <?php endif ?>
                    <?php if(!$dhp_deny) :?>
                    <li class="dropdown">
                        <button class="dropbtn <?=$gm_dhp?>" onclick="location.href=FURL+'/mini?gm=DHP'"><?=lang('common.game_dhp')?></button>
                    </li>
                    <?php endif ?>
                    <?php if(!$spk_deny) :?>
                    <li class="dropdown">
                        <button class="dropbtn <?=$gm_spk?>" onclick="location.href=FURL+'/mini?gm=SPKN'"><?=lang('common.game_keno')?></button>
                    </li>
                    <?php endif ?>
                    <?php if(!$bpg_deny) :?>
                    <li class="dropdown">
                        <button class="dropbtn <?=$gm_bg?>"><?=lang('common.game_boggle')?></button>
                        <div class="dropdown-content">
                            <a class="<?=$gm_bgb?>" href="/mini?gm=BGB"><?=lang('common.powerball_boggle')?></a>
                            <a class="<?=$gm_bgl?>" href="/mini?gm=BGL"><?=lang('common.powerladder_boggle')?></a>
                        </div>
                    </li>
                    <?php endif ?>

                    <?php if(!$eos5_deny || !$eos3_deny) :?>
                    <li class="dropdown">
                        <button class="dropbtn <?=$gm_eos?>"><?=lang('common.game_eos')?></button>
                        <div class="dropdown-content">
                        <?php if(!$eos3_deny) :?>
                            <a class="<?=$gm_e3?>" href="/mini?gm=EOS3"><?=lang('common.powerball_eos3')?></a>
                        <?php endif ?>
                        <?php if(!$eos5_deny) :?>
                            <a class="<?=$gm_e5?>" href="/mini?gm=EOS5"><?=lang('common.powerball_eos5')?></a>
                        <?php endif ?>
                        </div>
                    </li>
                    <?php endif ?>

                    <?php if(!$coin5_deny || !$coin3_deny) :?>
                    <li class="dropdown">
                        <button class="dropbtn <?=$gm_ro?>"><?=lang('common.game_coin')?></button>
                        <div class="dropdown-content">
                        <?php if(!$coin3_deny) :?>
                            <a class="<?=$gm_r3?>" href="/mini?gm=COIN3"><?=lang('common.powerball_coin3')?></a>
                        <?php endif ?>
                        <?php if(!$coin5_deny) :?>
                            <a class="<?=$gm_r5?>" href="/mini?gm=COIN5"><?=lang('common.powerball_coin5')?></a>
                        <?php endif ?>
                        </div>
                    </li>
                    <?php endif ?>
                    
                    <li  class="dropdown">
                        <button class="dropbtn <?=$ls_rnd?>" onclick="location.href=FURL+'/rndlist?gm=<?=$gm_ref?>'" style="margin-right:3px"><?=lang('common.game_result')?></button>
                    </li>
                    <li  class="dropdown">
                        <button class="dropbtn <?=$ls_bet?>" onclick="location.href=FURL+'/betlist?gm=<?=$gm_ref?>'"><?=lang('common.game_history')?></button>
                    </li>
                </ul>
                <!-- <div class="content_inner" style="display:none;">
                    <p class="inspection_none">운영시간이 아닙니다.</p>
                </div> -->
                <div class="game_list" style="" id="<?=$game_id?>">
                </div>