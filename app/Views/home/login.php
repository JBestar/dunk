<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta content="blendTrans(Duration=0.0)" http-equiv="Page-Enter">
    <meta content="blendTrans(Duration=0.0)" http-equiv="Page-Exit">
    <link rel="shortcut icon" href="/favicon_<?=$_ENV['app.logo']?>.ico?v=1">
    <!-- CSS  -->
    <link rel="stylesheet" href="/css/login/reset.css?v=1">
    <link rel="stylesheet" href="/css/login/common.css?v=1">
    <link rel="stylesheet" href="/css/login/content.css?v=1">
    <link rel="stylesheet" href="/css/login/style.css?v=1">
    <link rel="stylesheet" href="/css/login/login.css?v=1">

    <script type="text/javascript" src="/js/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="/js/jquery-ui.js?v=1"></script>

    <title><?=$site_name?></title>
    <script>
        const FURL = "<?=$_ENV['app.furl']?>";
        var langMessage = {
            account_number_input : '<?=lang('common.account_number_input')?>',
            administrator_ask : '<?=lang('common.administrator_ask')?>',
            bank_name_input : '<?=lang('common.bank_name_input')?>',
            cancel : '<?=lang('common.cancel')?>',
            id_available : '<?=lang('common.id_available')?>',
            id_deleted : '<?=lang('common.id_deleted')?>',
            login_id_input : '<?=lang('common.login_id_input')?>',
            login_name_input : '<?=lang('common.login_name_input')?>',
            login_rule : '<?=lang('common.login_rule')?>',
            nickname_available : '<?=lang('common.nickname_available')?>',
            nickname_input : '<?=lang('common.nickname_input')?>',
            ok : '<?=lang('common.ok')?>',
            password_input : '<?=lang('common.password_input')?>',
            password_match : '<?=lang('common.password_match')?>',
            password_rule : '<?=lang('common.password_rule')?>',
            phone_number_input : '<?=lang('common.phone_number_input')?>',
            recommender_input : '<?=lang('common.recommender_input')?>',
            signup_check : '<?=lang('common.signup_check')?>',
            signup_fail : '<?=lang('common.signup_fail')?>',
            withdrawal_password_input : '<?=lang('common.withdrawal_password_input')?>',
            withdrawal_rule : '<?=lang('common.withdrawal_rule')?>',
        };
    </script>  
    <style>
            .main-navbar-dropdown-container {
                position: fixed;
                top: 22px;
                right: 0px;
                width: 90px;
                display: none;
                overflow: auto;
                padding: 10px;
                z-index: 2001;
                
            }

            .main-navbar-dropdown-div {
                border: solid 1px #ac5b23;
                border-radius: 2px;
                z-index: 3;
                box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            }

            .main-navbar-dropdown-div::before {
                content: "";
                position: absolute;
                top: -8px;
                left: 80%;
                margin-left: -10px;
                border-width: 8px;
                border-style: solid;
                border-color: transparent transparent transparent transparent;
            }

            .main-navbar-dropdown-container button {
                text-align: left;
                color: #ffff00;
                background: none;
                padding: 2px 5px 4px 5px;
                display: block;
                outline: 0px;
                border: none;
                width: 100%;
                font-size: 14px;
                text-align:center;
            }

            .main-navbar-dropdown-container button:hover {
                /* color: white; */
            }
        </style>
</head>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
<body oncontextmenu="return false" ondragstart="return false" onselectstart="return false">
<?php else : ?>
<body>
<?php endif ?>
    <div class="main-navbar-dropdown-container" id="main-navbar-dropdown-container-id" style="display: none;">
        <div class="main-navbar-dropdown-div"> 
            <button id="main-navbar-dropdown-ko-id"><image src="/images/common/ko.png?v=1" style="width:22px;margin-top:-2px;">&nbsp;&nbsp;<span id="lang-ko"><?=lang('common.lang_korean')?></span></button>
        </div>
        <div class="main-navbar-dropdown-div"> 
            <button id="main-navbar-dropdown-cn-id"><image src="/images/common/cn.png?v=1" style="width:22px;margin-top:-2px;">&nbsp;&nbsp;<span id="lang-cn"><?=lang('common.lang_chinese')?></span></button>
        </div>
    </div>
    <div class="alert_wrap basic_alert" id="basic_alert">
        <div class="alert_bot">
            <p class="question_ico" style="white-space:pre-line;" id="alert_content"></p>
            <div class="btn_wrap">
                <a onclick="location.href='javascript:okAlert()';" class="btn btn_red" id="basic_ok" style="cursor: pointer"><?=lang('common.ok')?></a>
                <a onclick="location.href='javascript:closeAlert()';" class="btn" style="cursor: pointer"><?=lang('common.cancel')?></a>
            </div>
        </div>
    </div>

    <div class="alert_wrap confirm_alert" id="confirm_alert">
        <div class="alert_bot">
            <p class="question_ico" style="white-space:pre-line;" id="alert_content"></p>
            <div class="btn_wrap">
                <a onclick="location.href='javascript:closeAlert()';" class="btn" id="confirm_ok" style="cursor: pointer"><?=lang('common.ok')?></a>
            </div>
        </div>
    </div>

    <style>
        
        <?php if($_ENV['app.name'] == APP_PHANTOM) :?>
            #wrap.users_wrap {
                background:url(/images/login/login_bg_<?=strtolower($_ENV['app.logo'])?>.png?v=1) no-repeat center center;
                background-size: cover;
                padding-bottom: 0;
                overflow: hidden;
            }
        <?php elseif($_ENV['app.name'] == APP_BOLTON || $_ENV['app.name'] == APP_HERMES) :?>
            body {
                background:url(/images/login/login_bg_clip_<?=strtolower($_ENV['app.logo'])?>.png?v=3) repeat ;
                overflow: hidden;
            }
            #wrap.users_wrap {
                background:url(/images/login/login_bg_<?=strtolower($_ENV['app.logo'])?>.png?v=1) no-repeat center center;
                background-size: 100%;
                padding-bottom: 0;
                overflow: hidden;
            }
            @media screen and (max-width: 800px){
                #wrap.users_wrap {
                    background-size: 150%;
                }
            }
        <?php endif ?>

        <?php if( array_key_exists('login.dlg_border', $_ENV) ) : ?>

            .users_wrap .login_wrap{
                border: <?=$_ENV['login.dlg_border']?>;
                border-radius:5px;
            }
        <?php endif ?>

        <?php if( array_key_exists('login.type', $_ENV) && $_ENV['login.type'] == 2 ) : ?>
            
            .users_wrap .login_wrap{
                width:500px;
                margin-top: 115px;
                margin-left: -250px;
            }
            .users_wrap .login_wrap .login_area,
            .users_wrap .login_wrap .join_area
            {
                padding:20px 10px;
            }
            .users_wrap .login_wrap .join_area
            {
                padding-top:40px;
            }
            .users_wrap .login_wrap .login_area .login_con {
                padding-top: 0px;
            }
            .users_wrap .login_wrap input,
            .users_wrap .login_wrap .login_area input
            {
                padding: 0 10px 0 10px;
                border: 1px solid #404447;
                border-radius: 5px;
                background: #1e1f21;
                font-size: 16px;
                font-family: "ns";
                box-shadow: 0px 3px 5px #000 inset;
            }
            .users_wrap .login_wrap .login_area input{
                width: 140px;
            }
            .users_wrap .login_wrap .submit_btn,
            .users_wrap .login_wrap .join_btn
            {
                overflow: hidden;
                display: inline-block;
                width: 85px;
                height: 43px;
                line-height: 43px;
                font-size: 16px;
                font-weight: 600;
                text-align: center;
                vertical-align: middle;
                color: #fff;
                border-radius: 5px;
                margin-top: -5px;
                margin-left: 5px;
            }
            .users_wrap .join_area .prev_btn,
            .users_wrap .join_area .next_btn
            {
                width: 120px;
            }

            .users_wrap .login_wrap .submit_btn{
                background: linear-gradient(#df5e61, #a93a3e, #86181d);
                margin-left: 2px;
            }
            .users_wrap .login_wrap .submit_btn:hover {
                opacity: 0.8;
            }

            .users_wrap .login_wrap .join_btn{
                background: linear-gradient(#858585, #4d4d4d, #1e1e1e);
                margin-left: 2px;
            }
            .users_wrap .login_wrap .join_btn:hover {
                opacity: 0.8;
            }

            @media screen and (max-width: 679px){
                .users_wrap .login_wrap {
                    max-width: 400px;
                    margin-left:-200px;
                }

                .users_wrap .login_wrap .login_area .submit_btn{
                    margin-top: 5px;
                    width:140px;
                }
                .users_wrap .login_wrap .login_area .join_btn{
                    width:140px;
                    margin-left:0px;
                }
                .users_wrap .login_wrap input{
                    height:43px;
                }
                .users_wrap .login_wrap .login_area .login_con .id_area .id_ico,
                .users_wrap .login_wrap .login_area .login_con .password_area .password_ico {
                    top: 11px;
                }
            }
        <?php endif ?>

        <?php if( array_key_exists('login.dlg_opacity', $_ENV) ) : ?>
            .users_wrap .login_wrap{
                background:rgb(20, 21, 25, <?=$_ENV['login.dlg_opacity']?>);
            }
        <?php endif ?>
        
        <?php if( array_key_exists('login.btn_color', $_ENV) ) : ?>
            
            .users_wrap .login_wrap .submit_btn{
                background:  linear-gradient(to right, #9f2124, #991f20, #911e22, #86181d);
            }
           
            .users_wrap .login_wrap .submit_btn:hover {
                opacity: 0.8;
            }
        <?php endif ?>


        .users_border{
            position: absolute;
            top: 50%;
            left: 50%;
            z-index: -2;
            width: 1000px;
            height: 200px;
            margin-top: 75px;
            margin-left: -500px;
            border: 3px solid #fff8bd;
            border-radius: 0px;
            box-shadow: 10px 3px 10px #888;
            background:#000000;
        }

        <?php if($_ENV['app.name'] == APP_HERMES) :?>

            .users_border{
                box-shadow: 10px 3px 10px #fffb98;
            }
        <?php endif ?>

    </style>

    <div id="wrap" class="users_wrap">
        <?php if(array_key_exists('app.lang', $_ENV) && intval($_ENV['app.lang']) > 0 ) :?>
            <?php if(array_key_exists('app.lang', $_ENV) && intval($_ENV['app.lang']) > 0 ) :?>
                <button name="lang" id="lang-button" style="position:absolute; right:10px; margin-top:10px; width:80px; padding:0 5px; color: #ffff00; font-size:16px; background:none; height:22px; border:none; text-align:center;" is="ms-dropdown" >
                <?php if($lang == "cn") :?>
                    <image id="lang-img" src="/images/common/cn.png?v=1" style="width:22px;margin-top:-2px;">&nbsp;<span id="lang-code"><?=lang('common.lang_chinese')?></span>
                <?php else :?>
                    <image id="lang-img" src="/images/common/ko.png?v=1" style="width:22px;margin-top:-2px;">&nbsp;<span id="lang-code"><?=lang('common.lang_korean')?></span>
                <?php endif ?>
                </button>
            <?php endif ?>
        <?php endif ?>

        <div class="users_border">
        </div>
        
        <div class="login_wrap">
            <div class="login_area">
                <div class="login_con">
                    <input type="text" placeholder="User ID" id="user_id" class="english_p">
                    <input type="password" placeholder="Password" id="user_pw" class="english_s">
                    <input type="text" id="ip_addr" hidden>
                    <button type="button" class="submit_btn" id="btnLogin"><?=lang('common.login')?></button>
                    <button type="button" class="join_btn"><?=lang('common.signup')?></button>
                </div>
            </div>
            <!--//login_area -->
            <div class="join_area step01">
                <input type="text" name="proposer" id="proposer" placeholder="<?=lang('common.recommender_input')?>" class="english_p" style="width:250px;">
                <div class="btn_wrap">
                    <button type="button" class="prev_btn" value="BACK" title="BACK"><?=lang('common.login_back1')?></button>
                    <button type="button" class="next_btn join01_btn" value="START" title="START" id="btnCode"><?=lang('common.login_start')?></button>
                </div>
                <button type="button" class="join_close_btn"><span class="ir_pm"><?=lang('common.close')?></span></button>
            </div>

            <div class="join_area step02">
                <ul>
                    <li>
                        <p class="tit"><?=lang('common.id')?><span class="desc" id="id_desc">※ <?=lang('common.4to16')?>, <?=lang('common.english')?> <?=lang('common.or')?> <?=lang('common.number')?></span></p>
                        <input type="text" name="input_id" id="input_id" class="english" placeholder="<?=lang('common.4to16')?>, <?=lang('common.english')?> <?=lang('common.or')?> <?=lang('common.number')?>">
                    </li>
                    <li>
                        <p class="tit"><?=lang('common.nickname')?><span class="desc" id="nickname_desc">※ <?=lang('common.3to20')?></span></p>
                        <input type="text" name="input_nickname" id="input_nickname" class="korean" placeholder="<?=lang('common.3to20')?>">
                    </li>
                    <li>
                        <p class="tit"><?=lang('common.password')?><span class="desc">※ <?=lang('common.8to20')?>, <?=lang('common.special_chars')?></span></p>
                        <input type="text" name="input_pw" id="input_pw" class="english_s" autocomplete="off">
                    </li>
                    <li>
                        <p class="tit"><?=lang('common.confirm_password')?></p>
                        <input type="text" name="input_pw_check" id="input_pw_check" class="english_s" autocomplete="off" style="-webkit-text-security: disc;">
                    </li>
                </ul>
                <div class="btn_wrap">
                    <button type="button" class="prev_btn" value="BACK" title="BACK"><?=lang('common.login_back2')?></button>
                    <button type="button" class="next_btn" value="NEXT" title="NEXT" id="btn_next"><?=lang('common.login_next')?></button>
                </div>
                <button type="button" class="join_close_btn"><span class="ir_pm"><?=lang('common.close')?></span></button>
            </div>

            <div class="join_area step03">
                <ul>
                    <li>
                        <p class="tit"><?=lang('common.name')?><span class="desc">※ <?=lang('common.name_comment')?></span></p>
                        <input type="text" name="user_name" id="user_name">
                    </li>
                    <li>
                        <p class="tit"><?=lang('common.phone_number')?></p>
                        <input type="number" pattern="[0-9]*" inputmode="numeric" min="0" onkeydown="checkNumber(event)" style="ime-mode:disabled;" name="user_phone" id="user_phone" placeholder="<?=lang('common.phone_msg')?>">
                    </li>
                    <li>
                        <p class="tit"><?=lang('common.bank_name')?></p>
                        <input type="text" name="bank_name" id="bank_name">
                    </li>
                    <li>
                        <p class="tit"><?=lang('common.account_number')?></p>
                        <input type="number" pattern="[0-9]*" inputmode="numeric" min="0" onkeydown="checkNumber(event)" style="ime-mode:disabled;" name="bank_account" id="bank_account" placeholder="<?=lang('common.phone_msg')?>">
                    </li>
                    <li>
                        <p class="tit"><?=lang('common.withdrawal_password')?></p>
                        <input type="text" name="bank_pw" id="bank_pw" autocomplete="off" style="-webkit-text-security: disc;">
                    </li>
                </ul>
                <div class="btn_wrap">
                    <button type="button" class="prev_btn" value="BACK" title="BACK"><?=lang('common.login_back2')?></button>
                    <button type="button" class="next_btn" value="NEXT" title="NEXT" id="btn_done"><?=lang('common.login_next')?></button>
                </div>
                <button type="button" class="join_close_btn"><span class="ir_pm"><?=lang('common.close')?></span></button>
            </div>

            <div class="join_area step04">
                <p class="txt"><?=lang('common.signup_complete')?>.</p>
                <p class="desc"  id="join_commment"><span class="name" id="name_complete"></span><?=lang('common.signup_welcome')?>.<br> <?=lang('common.signup_permit')?>.<br> <?=lang('common.thanks')?>.
                </p>
                <button type="button" class="next_btn" value="LOGIN NOW" id="btn_login"><?=lang('common.login')?></button>
                <button type="button" class="join_close_btn"><span class="ir_pm"><?=lang('common.close')?></span></button>
            </div>
        </div>
        <!-- login_wrap -->

    </div>
    <?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
        <script src="/js/login.js?v=2"></script>
    <?php else : ?>
        <script src="/js/login.js?t=<?=time()?>"></script>
    <?php endif ?>
    <script>
        setLogCookie('lang', '<?=$lang?>', 30);
    </script>  
</body>

</html>