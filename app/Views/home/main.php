<!DOCTYPE html>
<html lang="ko">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title><?=$site_name?></title>
        <link rel="shortcut icon" href="<?php echo site_furl('favicon_'.$_ENV['app.logo'].'.ico?v=1'); ?>">

        <?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
            <link rel="stylesheet" href="<?php echo site_furl('css/a.min.css?v=7'); ?>" />
        <?php else : ?>
            <link rel="stylesheet" href="<?php echo site_furl('css/a.min.css?v='.time()); ?>" />
        <?php endif ?>
        
        <link rel="stylesheet" href="<?php echo site_furl('css/jquery-ui.css?ver=1'); ?>" />

        <script type="text/javascript" src="<?php echo site_furl('js/jquery-1.12.4.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo site_furl('js/jquery-ui.js?v=1'); ?>"></script>
        <script type="text/javascript" src="<?php echo site_furl('js/jquery-form/jquery.form.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo site_furl('js/jquery-form/jquery.validate.js'); ?>"></script>

        <link rel="stylesheet" href="<?php echo site_furl('js/sweet/sweetalert2.min.css'); ?>" />
        <script type="text/javascript" src="<?php echo site_furl('js/sweet/sweetalert2.min.js'); ?>"></script>

        <link rel="stylesheet" type="text/css" href="<?php echo site_furl('js/semantic-ui/semantic.css'); ?>" />
        <link rel="stylesheet" href="<?php echo site_furl('css/bootstrap.min.css?ver=1'); ?>" />
        <script type="text/javascript" src="<?php echo site_furl('js/bootstrap.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo site_furl('js/semantic-ui/semantic.js?v=2'); ?>"></script>
        <script src="<?php echo site_furl('js/worker.js?v=1'); ?>"></script>
        <script src="<?php echo site_furl('js/odometer/odometer.js?v=1'); ?>"></script>
        <link rel="stylesheet" href="<?php echo site_furl('js/odometer/odometer.css?v=1'); ?>" />
        <link rel="stylesheet" href="<?php echo site_furl('css/real-time-table.css?v=15'); ?>" />
        <script src="<?php echo site_furl('js/real-time-table.js?v=1'); ?>"></script>
        <script type="text/javascript" src="<?php echo site_furl('js/vue.js'); ?>"></script>
        <!--순서중요-->
        <?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
            <script type="text/javascript" src="<?php echo site_furl('js/script.php.js?ver=4'); ?>"></script>
            <script type="text/javascript" src="<?php echo site_furl('js/lib.js?ver=1'); ?>"></script>
            <script type="text/javascript" src="<?php echo site_furl('js/common.js?ver=3'); ?>"></script>
            <script type="text/javascript" src="<?php echo site_furl('js/SLB.js?ver=4'); ?>"></script>
            <script type="text/javascript" src="<?php echo site_furl('js/main.js?ver=7'); ?>"></script>
            <link rel="stylesheet" type="text/css" href="<?php echo site_furl('/css/devel.css?v=3'); ?>" />
            <script type="text/javascript" src="<?php echo site_furl('js/toaster.js?v=1'); ?>"></script>
        <?php else : ?>
            <script type="text/javascript" src="<?php echo site_furl('js/script.php.js?ver='.time() ); ?>"></script>
            <script type="text/javascript" src="<?php echo site_furl('js/lib.js?ver=1'); ?>"></script>
            <script type="text/javascript" src="<?php echo site_furl('js/common.js?ver='.time() ); ?>"></script>
            <script type="text/javascript" src="<?php echo site_furl('js/SLB.js?ver='.time() ); ?>"></script>
            <script type="text/javascript" src="<?php echo site_furl('js/main.js?ver='.time() ); ?>"></script>
            <link rel="stylesheet" type="text/css" href="<?php echo site_furl('/css/devel.css?v='.time() ); ?>" />
            <script type="text/javascript" src="<?php echo site_furl('js/toaster.js?v='.time() ); ?>"></script>
        <?php endif ?>

        <!-- JS FILES -->
        <link rel="stylesheet" type="text/css" href="<?php echo site_furl('/js/uikit/uikit.min.css'); ?>" />
        <script src="<?php echo site_furl('js/uikit/uikit.min.js'); ?>"></script>
        <script src="<?php echo site_furl('js/uikit/uikit-icons.min.js'); ?>"></script>

        <script src="<?php echo site_furl('js/jquery.bgswitcher.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo site_furl('js/jquery-ui/marquee.js'); ?>"></script>

    <?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
        <link rel="stylesheet" type="text/css" href="<?php echo site_furl('/css/a.custom.css?ver=7'); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo site_furl('/css/c.custom.css?ver=6'); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo site_furl('/css/darkmode.css?ver=4'); ?>" />
    <?php else : ?>
        <link rel="stylesheet" type="text/css" href="<?php echo site_furl('/css/a.custom.css?ver='.time()); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo site_furl('/css/c.custom.css?ver='.time()); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo site_furl('/css/darkmode.css?ver='.time()); ?>" />
    <?php endif ?>

    
        <script>
            const FURL = "<?=$_ENV['app.furl']?>";
            const GAME_CASINO_EVOL = <?=GAME_CASINO_EVOL?>;
            const GAME_CASINO_KGON = <?=GAME_CASINO_KGON?>;
            const GAME_CASINO_STAR = <?=GAME_CASINO_STAR?>;
            const GAME_CASINO_RAVE = <?=GAME_CASINO_RAVE?>;
            const GAME_CASINO_TREEM = <?=GAME_CASINO_TREEM?>;
            const GAME_CASINO_SIGMA = <?=GAME_CASINO_SIGMA?>;

            var langMessage = {
                administrator_ask : '<?=lang('common.administrator_ask')?>', 
                ask_quick : '<?=lang('common.ask_quick')?>',
                cancel : '<?=lang('common.cancel')?>',
                change_point_request : '<?=lang('common.change_point_request')?>',
                change_point_result : '<?=lang('common.change_point_result')?>',
                customer_ask : '<?=lang('common.customer_ask')?>', 
                deposit_account_answer : '<?=lang('common.deposit_account_answer')?>',
                deposit_account_ask : '<?=lang('common.deposit_account_ask')?>',
                deposit_account_check : '<?=lang('common.deposit_account_check')?>',
                deposit_account_request : '<?=lang('common.deposit_account_request')?>',
                deposit_success : '<?=lang('common.deposit_success')?>',
                id_input : '<?=lang('common.id_input')?>',
                id_input_4 : '<?=lang('common.id_input_4')?>',
                id_input_16 : '<?=lang('common.id_input_16')?>',
                inspection : '<?=lang('common.inspection')?>',
                message_to_read : '<?=lang('common.message_to_read')?>',
                nickname_input : '<?=lang('common.nickname_input')?>',
                ok : '<?=lang('common.ok')?>',
                password_change_ok : '<?=lang('common.password_change_ok')?>',
                password_input : '<?=lang('common.password_input')?>', 
                password_input_4 : '<?=lang('common.password_input_4')?>', 
                password_current_input : '<?=lang('common.password_current_input')?>',
                password_new_input : '<?=lang('common.password_new_input')?>',
                password_verify : '<?=lang('common.password_verify')?>',
                password_verify_c : '<?=lang('common.password_verify_c')?>',
                request_amount_10th : '<?=lang('common.request_amount_10th')?>',
                request_amount_input : '<?=lang('common.request_amount_input')?>',
                security_character_input : '<?=lang('common.security_character_input')?>',
                signup_complete : '<?=lang('common.signup_complete')?>',
                signup_permit : '<?=lang('common.signup_permit')?>',
                thanks : '<?=lang('common.thanks')?>',
                updating : '<?=lang('common.updating')?>',
                withdrawal_bank_select : '<?=lang('common.withdrawal_bank_select')?>',
                withdrawal_number_input : '<?=lang('common.withdrawal_number_input')?>',
                withdrawal_owner_input : '<?=lang('common.withdrawal_owner_input')?>',
                withdrawal_password_input : '<?=lang('common.withdrawal_password_input')?>',
                withdrawal_success : '<?=lang('common.withdrawal_success')?>',
            };
           
            function showAlert(msg, type=1){
                <?php if($_ENV['app.home'] > 0) :?>
                    if(!toaster)
                        alert(msg);
                    else if(type == 0)
                        toaster.error(msg);
                    else if(type == 2)
                        toaster.info(msg);
                    else if(type == 3)
                        toaster.warning(msg);
                    else toaster.success(msg);
                <?php else : ?>
                    alert(msg);
                <?php endif ?>
            }
        </script>  

        <style>
            @media screen and (min-width:680px) { 
                ::-webkit-scrollbar {width:10px; height:3px; }
                ::-webkit-scrollbar-track {background:#1e1e1e; border-radius:2px 2px 0 0; }
                ::-webkit-scrollbar-thumb {background:#383838; border-radius:2px; }
                ::-webkit-scrollbar-thumb:hover {background:#383838; }
            }
            .ui-resizable-se {
                right : 0px;
                bottom: -15px;
            }
            .ui.form input[type="date"]{
                color:#eeeeee;
                background:#24425b;
            }
            .ui.form input[type="text"], .ui.form input[type="password"], .ui.form input[type="number"]{
                color:#eeeeee;
                background:#24425b;
            }
            
            .MainMenu-open-wrapper .MainMenu-LogoSlogan {
                background-image: url(/images/main/sample2.logo_<?=$_ENV['app.logo']."_".$_ENV['app.home']?>.png?v=3.5);
            }

            <?php if(array_key_exists('app.tree', $_ENV) && $_ENV['app.tree'] == 1) :?>
                <?php if($_ENV['app.home'] == 2) :?>
                    .SeoPage {
                        background-color: #0d1b2f;/*#35465d;*/
                    }
                    .games-page .categories-wrapper, .SeoPage .categories-wrapper {
                        background-image: linear-gradient(360deg, #041a2c, #041624, #041a2c);
                        margin-top:16px;
                        <?php if(is_login(true)) :?>
                            /* box-shadow:#465f80 10px 80px 80px 60px; */
                            box-shadow:  #3b4756 10px 80px 80px 60px;
                        <?php endif?>
                    }         
                    .scroll_area{
                        background-image: linear-gradient(360deg, #00111c, #03121c, #041a29);
                        color: #f7ffb1;
                    }
                <?php else:?>
                    .games-page .categories-wrapper, .SeoPage .categories-wrapper {
                        background-image: linear-gradient(360deg,#2b2b2b, #232323, #262626);
                        margin-top:16px;
                    }         
                            
                    .scroll_area{
                        background-image: linear-gradient(360deg,#3f3f3f, #333333, #2b2b2b);
                    } 
                <?php endif?>
                <?php if(!is_login(true)) :?>
                .SeoPage {
                    background-repeat:repeat;
                    background-image: url(/images/main/sample2.main_bg.jpg?v=3);
                }
                <?php endif?>
                .MainMenu-open-wrapper.js-is-game-open .MainMenu-LogoSlogan, .MainMenu-open-wrapper.js-sticky .MainMenu-LogoSlogan {
                    top: 8px;
                    width: 150px;
                }
                .MainMenu-open-wrapper.js-is-game-open, .MainMenu-open-wrapper.js-sticky{
                    background-color: #000000;
                }
                .MainMenu-open-wrapper .MainMenu-LogoSlogan-mobile:before,
                .MainMenu-open-wrapper .MainMenu-LogoSlogan-mobile:after{
                    background-color: #000000;
                }
                
                @media only screen and (max-width: 850px) {
                    .MainMenu-open-wrapper.js-is-game-open .MainMenu-LogoSlogan, 
                    .MainMenu-open-wrapper.js-sticky .MainMenu-LogoSlogan {
                        top:11px;
                        width:100px;
                        left: 90px;
                    }
                }
            <?php endif ?>

            @media only screen and (max-width: 850px) {
                .MainMenu-open-wrapper .MainMenu-LogoSlogan-mobile:before{
                    height: 0px;
                }
            }
            @media only screen and (min-width: 800px) {
                .btn-tiny .txt_cash{
                    display:block;
                } 
                .btn-tiny .icon_cash{
                    display:none;
                }
            }
            @media only screen and (max-width: 800px) {
                .btn-tiny .txt_cash{
                    display:none;
                } 
                .btn-tiny .icon_cash{
                    display:block;
                }
            }

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
                background-color: var(--bar-bg-color);
                border: solid 1px #333;
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
                color: white;
                background-color: black;
                padding: 2px 5px 2px 5px;
                display: block;
                outline: 0px;
                border: none;
                width: 100%;
                font-size: 14px;
                text-align:center;
            }

            .main-navbar-dropdown-container button:hover {
                background-color: #868686;
            }

            .btn {
                color: #fff;
                background: #365a92;/*#38383a*/
                padding:8px 0;
            }
            .btn:hover {
                background: #2e456a;/*4f4f52*/
                color:#eee;
            }
            #_btn_user_money ._has_cash, #_btn_user_point ._has_point{
                color:#ff9600;
            }
            .pop_layer {
                position: fixed;
                width: 370px;
                height: auto;
                z-index: 1100;
            }

            .pop_layer .pop_container {
                position: relative;
            }

            .pop_layer .pop_container .pop_top {
                height: 50px;
                padding: 0 21px;
                overflow: hidden;
                border-radius: 10px 10px 0 0;
                background: #1c355b; /*#ffcc00;*/
            }

            .pop_layer .pop_container .pop_top .tit {
                line-height: 50px;
                font-size: 20px;
                font-weight: 600;
            }

            .pop_layer .pop_container .pop_con {
                max-height: 530px;
                overflow-y: auto;
            }

            .pop_layer .pop_container .pop_con .txt {
                text-align: center;
                font-size: 14px;
                line-height: 30px;
                font-weight: 600;
                padding: 40px;
            }

            .pop_layer .pop_container .pop_con .txt span {
                font-size: 14px;
                vertical-align: top;
            }

            .pop_layer .pop_container .pop_con .txt .underline {
                text-decoration: underline;
            }

            .pop_layer .pop_container .pop_con img {
                width: 100%;
            }

            .pop_layer .pop_container .pop_close {
                position: absolute;
                top: 18px;
                right: 21px;
                width: 10px;
                height: 10px;
                background: url(/images/common/pop_close.png) no-repeat left top;
            }

            .pop_layer .check {
                text-align: right;
                margin: 0 0;
                padding: 5px 10px;
            }

            .pop_layer .btn_wrap {
                margin-top: 0;
                overflow: hidden;
                border-radius: 0 0 10px 10px;
            }

            .pop_layer .btn_wrap .btn {
                width: 50%;
                border-radius: 0;
                margin-left: 0;
            }

            .pop_layer .btn_wrap button:first-child {
                float: left;
            }

            .pop_layer .btn_wrap button:last-child {
                float: right;
            }

            #layer1, #layer3, #layer4, #layer5, #layer6, #layer7{
                top: 50%;
                left: 50%;
                color:white;
                z-index: 990;
            }

            <?php if( count($boards) == 1 ) :?>
                #layer4
                {
                    margin-left: -185px;
                    margin-top: -280px;
                }
                
                @media screen and (max-width: 800px){
                    #layer1, #layer3, #layer4, #layer5, #layer6, #layer7  {
                        margin-left: -185px;
                    }
                }
            <?php elseif( count($boards) == 2 ) :?>
                #layer4
                {
                    margin-left: -375px;
                    margin-top: -280px;
                    z-index: 999;
                }
                #layer5
                {
                    margin-left: 5px;
                    margin-top: -280px;
                    z-index: 998;
                }
                @media screen and (max-width: 800px){
                    #layer1, #layer3, #layer4, #layer5, #layer6, #layer7  {
                        margin-left: -185px;
                    }
                }
            <?php elseif( count($boards) == 3 ) :?>
                #layer4
                {
                    margin-left: -560px;
                    margin-top: -280px;
                    z-index: 999;
                }
                #layer5
                {
                    margin-left: -185px;
                    margin-top: -280px;
                    z-index: 998;
                }
                #layer6
                {
                    margin-left: 190px;
                    margin-top: -280px;
                    z-index: 997;
                }
                
                @media screen and (max-width: 1120px){
                    #layer1, #layer3, #layer4, #layer5, #layer6, #layer7  {
                        margin-left: -185px;
                    }
                }
            <?php else :?>
                #layer4
                {
                    margin-left: -375px;
                    margin-top: -280px;
                    z-index: 999;
                }
                #layer5
                {
                    margin-left: 5px;
                    margin-top: -280px;
                    z-index: 998;
                }
                #layer6
                {
                    margin-left: -755px;
                    margin-top: -280px;
                    z-index: 997;
                }
                #layer7
                {
                    margin-left: 385px;
                    margin-top: -280px;
                    z-index: 996;
                }
                #layer1
                {
                    margin-left: -755px; 
                    margin-top: -280px; 
                    z-index: 995;
                }
                #layer3
                {
                    margin-left: 385px; 
                    margin-top: -280px;  
                    z-index: 994;
                }
            
                @media screen and (max-width: 1500px){
                    #layer4, #layer7  {
                        margin-left: -560px;
                        margin-top: -280px;
                    }
                    #layer5, #layer1  {
                        margin-left: -185px;
                        margin-top: -280px;
                    }
                    #layer6, #layer3  {
                        margin-left: 190px;
                        margin-top: -280px;
                    }
                }

                @media screen and (max-width: 1120px){
                    #layer1, #layer4, #layer6  {
                        margin-left: -375px;
                        margin-top: -280px;
                    }
                    #layer3, #layer5, #layer7  {
                        margin-left: 5px;
                        margin-top: -280px;
                    }
                }
                
                @media screen and (max-width: 800px){
                    #layer1, #layer3, #layer4, #layer5, #layer6, #layer7  {
                        margin-left: -185px;
                    }
                }
            <?php endif ?>

            @media screen and (max-height: 700px){
                #layer1, #layer3, #layer4, #layer5, #layer6, #layer7  {
                    margin-top: -260px;
                }
                .pop_layer .pop_container .pop_con {
                    max-height: 520px;
                    padding-bottom:35px;
                    border-radius:0 0 0 10px;
                }
                .pop_layer .btn_wrap{
                    margin-top:-35px;
                    opacity: 0.99;
                }
                .btn {
                    background: rgba(54, 90, 146, 0.8);
                }
            }
            
            @media screen and (max-height: 650px){
                #layer1, #layer3, #layer4, #layer5, #layer6, #layer7  {
                    margin-top: -235px;
                }
                .pop_layer .pop_container .pop_con {
                    max-height: 460px;
                }
            }

            @media screen and (max-height: 600px){
                #layer1, #layer3, #layer4, #layer5, #layer6, #layer7  {
                    margin-top: -215px;
                }
                .pop_layer .pop_container .pop_con {
                    max-height: 420px;
                }
            }

            @media screen and (max-height: 550px){
                #layer1, #layer3, #layer4, #layer5, #layer6, #layer7  {
                    margin-top: -190px;
                }
                .pop_layer .pop_container .pop_con {
                    max-height: 370px;
                }
            }
            
            @media screen and (max-height: 500px){
                #layer1, #layer3, #layer4, #layer5, #layer6, #layer7  {
                    margin-top: -165px;
                }
                .pop_layer .pop_container .pop_con {
                    max-height: 320px;
                }
            }
            .pop_layer h1, .pop_layer h2, .pop_layer h3, .pop_layer h4, .pop_layer h5, .pop_layer h6{
                color:white;
                line-height:0.2;
                margin-bottom:10px;
                margin-top:10px;
            }
            .pop_layer .pop_container .pop_con {
                background: #333;
            }
            @media only screen and (max-width: 727px){
                .ui.stackable.grid > .wide.column{
                    width: 100%;
                }
            }

            <?php if($_ENV['app.home'] > 0) :?>
                <?php if(is_login(true)) :?>
                    .MainMenu-Left {
                        margin: 5px 2px 0 2px;
                        width: calc(100% - 4px);
                    }
                <?php else : ?>
                    .MainMenu-Left {
                        margin: 10px 7% 0 7%;
                        width: 85%;
                    }
                <?php endif ?>
                
                <?php if($_ENV['app.home'] == 2) :?>

                    .MainMenu-open-wrapper.js-sticky{
                        width:100%;
                        height: 70px;
                        background-color: rgb(0 25 40);
                        box-shadow: #8db0df 1px 10px 10em;
                    }
                    .PaymentIconsContainer {
                        background-image: linear-gradient(90deg, #696f76, #555b60, #696f76);
                    }
                    .MainMenu-ActionsContainer button{
                        color:  #00fffc;
                    }
                    .Footer {
                        background-color: #484848;
                        opacity: 1;
                        color: #adadad;
                    }
                    .pop_layer .pop_container .pop_top{
                        background: #486f67;
                    }
                    .btn {
                        background:#6e807c;
                    }
                    .uk-card-media-top{
                        border-radius:20px;
                        box-shadow: 0px 0px 3px 3px #fefeff59, inset 0 0 6px 1px #ffffffbf;
                    }

                <?php else  :?>
                    .MainMenu-open-wrapper.js-sticky{
                        width:100%;
                        height: 70px;
                        background-color: var(--grey-300);
                        box-shadow: #ffb08e 1px -1px 0.8em;
                    }
                    .PaymentIconsContainer {
                    background-image: linear-gradient(90deg,#2f3031, #2f3031, #2f3031);
                    }
                    .MainMenu-ActionsContainer button{
                        color: #ffb08e;
                    }
                    .pop_layer .pop_container .pop_top{
                        background: #112035;
                    }
                    .btn {
                        background: #1b1f25;
                    }
                    .uk-card-media-top{
                        border-radius:20px;
                        box-shadow: 0px 0px 3px 3px #fefeff59, inset 0 0 6px 1px #ffffffbf;
                    }
                <?php endif ?>
                form.ui.form{
                    border:1px solid #ffb08e;
                }
                .logo_icon{
                    width: 150px;
                    margin-top: 55px;
                    margin-right:0px;
                }
                .MainMenu-top-wrapper {
                    position: relative;
                }
                .MainMenu-open-wrapper {
                    position: inherit;
                    height:0px;
                }
                .SeoPage .MainBanner-container .BannerSlider-list .BannerSlider-bgDesktop .bg-img {
                    background-size: cover;
                    background-position: 0% 50%
                }

                #MainMenu {
                    background: linear-gradient(180deg,#292929 ,#2b2b2b);
                }
                .MainMenu-play a {
                    background: #161616;
                }
                .MainMenu-play a:hover {
                    background: #000000;
                    border-color: #f0bf39;
                }
                #_btn_user_money ._has_cash, #_btn_user_point ._has_point{
                    color:#ffff00;
                }
                #_btn_user_money{
                    margin-left:60px;
                    cursor: default;
                }
                .MainMenu-ActionsContainer .btn-register{
                    margin-right: 3px;
                }
                .MainMenu-ActionsContainer .btn-logo{
                    margin-right:60px;
                }

                .MainMenu-ActionsContainer .btn-box{
                    float:right; 
                    display: inline-block;
                    width:110px;
                    padding: 7px 0 8px 0;
                    border: #69583a;
                    color: #fff4d5;
                    font-size:20px;
                    font-family: 'Noto Sans KR Regular';
                    font-weight: 900;
                    background: linear-gradient(180deg, #ffbe05, #735300);
                    margin-top: 3px;
                    margin-left: 5px;
                }
                #lang-button{
                    z-index: 2;
                    position: absolute;
                    right: 10px;
                    margin-top: 24px;
                    width: 80px;
                    padding: 0 5px;
                    color: #ffff00;
                    font-size: 14px;
                    background: none;
                    height: 22px;
                    border: none;
                    text-align: center;
                }   

                @media (max-width: 1800px) {
                    .MainMenu-ActionsContainer .btn-register{
                        margin-right: 1px;
                    }
                    .MainMenu-ActionsContainer .btn-logo{
                        margin-right:20px;
                    }
                    #_btn_user_money{
                        margin-left:20px;
                    }
                }
                @media (max-width: 760px) {
                    #_btn_user_money{
                        margin-left:0px;
                    }
                    .MainMenu-ActionsContainer .btn-logo {
                        margin-right: 5px;
												margin-left:40px;
                    }
                    .MainMenu-ActionsContainer .btn-box{
                        width:80px;
                    }
                    .logo_icon{
                        width:120px;
                        margin-top:40px;
                    }
                    .MainMenu-Left{
                        margin: 10px 5px 0 0px;
                    }
                    #lang-button{
                        margin-top: 30px;
                        right:0px;
                    }
                }
                @media (max-width: 400px) {
                    .MainMenu-ActionsContainer .btn-box{
                        width:60px;
                        font-size:16px;
                        margin-top:5px;
                    }
                    <?php if(!is_login(true)) :?>
                    .SeoPage {
                        background-image: url(/images/main/sample2.main_bg.jpg?v=1);
                    }
                    <?php endif?>
                    .BannerSlider-list{
                        width:0px;
                    }
                }

                
                .btn:hover {
                    background: #5f5f5f;
                    color: #eee;
                }
                .uk-modal-dialog {
                    background: #232323;
                }
                .uk-modal-footer, .uk-modal-header, .SLB_caption {
                    background: #2f3031;
                }
                .ui.form input[type="text"], .ui.form input[type="password"], .ui.form input[type="number"] {
                    color: #eeeeee;
                    background: #494949;
                }
                .ui.inverted.blue.buttons .button, .ui.inverted.blue.button {
                    background-color: transparent;
                    -webkit-box-shadow: 0px 0px 0px 2px #9b9b9b inset;
                    box-shadow: 0px 0px 0px 2px #9b9b9b inset;
                    color: #adadad;
                }
                .ui.inverted.blue.buttons .button:hover, .ui.inverted.blue.button:hover {
                    background-color: #6d7477;
                }
                .ui.button {
                    background-color: #4b4b4b;
                    color: #FFFFFF;
                }
                .ui.button:active, .ui.active.button:active,.ui.button:hover {
                    background-color: #6b6b6b;
                }
                #dashboard, #SLB_content {
                    background: #000000;
                }
            <?php endif ?>

        </style>
    </head>
    <body>
        <div class="main-navbar-dropdown-container" id="main-navbar-dropdown-container-id" style="display: none;">
            <div class="main-navbar-dropdown-div"> 
                <button id="main-navbar-dropdown-ko-id"><image src="<?php echo site_furl('/images/common/ko.png?v=1'); ?>" style="width:22px; margin-top:-2px;">&nbsp;&nbsp;<span id="lang-ko"><?=lang('common.lang_korean')?></span></button>
            </div>
            <div class="main-navbar-dropdown-div"> 
                <button id="main-navbar-dropdown-cn-id"><image src="<?php echo site_furl('/images/common/cn.png?v=1'); ?>" style="width:22px; margin-top:-2px;">&nbsp;&nbsp;<span id="lang-cn"><?=lang('common.lang_chinese')?></span></button>
            </div>
        </div>
        <div
            id="SLB_film"
            onclick="SLB();"
            style="z-index: 1000; position: absolute; display: none; width: 100%; height: 100%; background-color: #000000; filter: Alpha(opacity=60); opacity: 0.6; -moz-opacity: 0.6; -webkit-opacity: 0.6; "
        ></div>
        <div class="ui centered doubling stackable grid">
            <div id="SLB_wide" class="six wide column">
                <div id="SLB_content" style="z-index: 99999; position: absolute; background-color: #000000; filter: Alpha(opacity=97); opacity: 0.97; -moz-opacity: 0.97; -webkit-opacity: 0.97; "></div>
            </div>
        </div>
        <div id="SLB_loading"></div>

        <div id="wrapper" data-login="<?=is_login(true)?1:0?>" >
            <input type="checkbox" id="MainMenu-controller" />
            <label class="MainMenu-open burger at-hamburger-menu-button" for="MainMenu-controller" style="position:absolute">
                <div class="line"></div>
            </label>

            <div class="MainMenu-top-wrapper">
                <div class="MainMenu-open-wrapper <?= $_ENV['app.home'] > 0 ? "js-sticky":"" ?>"  >
                    <?php if(($_ENV['app.home'] == 0)) :?>
                        <a href="/" class="MainMenu-LogoSlogan-mobile" style="display: none;"></a>
                        <a href="/" class="MainMenu-LogoSlogan">
                            <div class="star-logo">
                                <img src="<?php echo site_furl('/images/common/star1.png');?>">
                                <img src="<?php echo site_furl('/images/common/star4.png');?>">
                            </div>
                            <div class="MainMenu-LogoSlogan-wrapper"></div>
                        </a>
                    <?php endif ?>

                    <div class="MainMenu-ActionsContainer">
                        
                        <div class="MainMenu-Left">

                            <?php if($_ENV['app.home'] > 0) :?>
                                <a href="/" class="js-register-open btn-register btn-tiny btn-logo" style=" <?=!is_login(true)?"float:left;":""?>">
                                    <span> <img src="<?php echo site_furl('/images/main/sample2.logo_'.$_ENV['app.logo'].'_'.$_ENV['app.home'].'.png?v=3.0'); ?>" class="logo_icon" /> </span>
                                </a>
                                <?php if(!is_login(true)) :?>
                                    <button class="js-login-open btn-login btn-register btn-box" style="" onclick="showAgentCheckModal();">
                                        <span>JOIN</span>
                                    </button>
                                    <button class="js-login-open btn-login btn-register btn-box" style="" onclick="showLoginModal();"  
                                        <?php if(array_key_exists('app.lang', $_ENV) && intval($_ENV['app.lang']) > 0 ) :?>
                                            style="margin-top:27px;"
                                        <?php endif ?>
                                    >
                                        <span>LOGIN</span>
                                    </button>
                                <?php endif?>
                            <?php endif ?>

                            <?php if(is_login(true)) :?>
                                <?php if ($apps_enable && (!array_key_exists('app.hold', $_ENV) || $_ENV['app.hold'] != 1) ):?>
                                    <button class="js-register-open btn-register btn-tiny at-main-register-button" id="_btn_app"  onclick="$('html, body').animate({scrollTop : 450}, 300); showTabMenu('auto');">
                                        <span style="padding:0px;"> <img src="<?php echo site_furl('/images/common/logo_app.gif');?>" class="app_icon" /> </span>
                                    </button>
                                <?php endif?>
                                <?php if(!$user_off) :?>
                                    <button class="js-register-open btn-register btn-tiny btn-secondary at-main-register-button" id="_btn_charge"  onclick="requestCharge();"><i class="ui cloud download icon"></i><span><?=lang('common.deposit')?></span></button>
                                    <button class="js-register-open btn-register btn-tiny btn-secondary at-main-register-button" id="_btn_discharge"  onclick="requestWithdraw();"><i class="ui cloud upload icon"></i><span><?=lang('common.withdrawal')?></span></button>
                                <?php endif?>
                            
                                <!-- <button class="js-register-open btn-register btn-tiny btn-secondary at-main-register-button" onclick="requestAccount()"><i class="ui question circle icon"></i><span><?=lang('common.ask_account')?></span></button> -->
                                <button class="js-register-open btn-register btn-tiny btn-secondary at-main-register-button" id="_btn_memo" onclick="SLB_POPUP(FURL + '/mypage', 'my_memo')">
                                    <i class="ui comment outline icon"></i><span><?=lang('common.message')?><span id="memo_count"></span></span>
                                </button>
                                <button class="js-register-open btn-register btn-tiny btn-secondary at-main-register-button" id="_btn_notice" onclick="SLB_POPUP(FURL + '/mypage', 'notice')"><i class="ui bullhorn icon"></i><span><?=lang('common.notice')?></span></button>
                                <button class="js-register-open btn-register btn-tiny btn-secondary at-main-register-button" id="_btn_info" onclick="SLB_POPUP(FURL + '/mypage', '')"><i class="ui user icon"></i><span><?=lang('common.myinfo')?></span></button>
                                <button class="js-register-open btn-register btn-tiny btn-secondary at-main-register-button" id="_btn_qna" onclick="SLB_POPUP(FURL + '/mypage', 'my_qna')">
                                    <i class="ui comment alternate icon"></i><span><?=lang('common.customer')?><span id="answered_count"></span></span>
                                </button>

                                <?php if($part_en) :?>
                                <button class="js-register-open btn-register btn-tiny btn-secondary at-main-register-button" id="_btn_partener" onclick="window.open('about:blank').location.href=FURL+'/home/pt_login'"><i class="ui users icon"></i><span><?=lang('common.partener')?></span></button>
                                <?php endif?>
                                
                                <?php if($_ENV['app.home'] > 0) :?>
                                    <button class="js-register-open btn-register btn-tiny at-main-register-button" id="_btn_user_money" onclick="" style="">
                                        <span class="txt_cash" style="padding:12px 0px;"><?=lang('common.money')?></span> 
                                        <span class="icon_cash" style="padding:12px 0px; width:24px; "><img src="<?php echo site_furl('/images/common/won.png?v=1'); ?>"></span>
                                        <span class="_has_cash" style="padding:12px 3px;"><?=number_format($user_money)?></span>
                                    </button>
                                
                                    <button class="js-register-open btn-register btn-tiny at-main-register-button" id="_btn_user_point" onclick="changePoint();" style="margin-left:10px">
                                        <span class="txt_cash" style="padding:12px 0px;"><?=lang('common.point')?></span> 
                                        <span class="icon_cash" style="padding:12px 0px; width:24px; "><img src="<?php echo site_furl('/images/common/point.png?v=1'); ?>"></span>
                                        <span class="_has_point" style="padding:12px 3px;"  ><?=number_format($user_point)?></span>
                                    </button>

                                    <button class="js-register-open btn-register btn-tiny btn-secondary at-main-register-button" id="_btn_logout" onclick="location.href='/home/logout'">   
                                        <span><?=lang('common.logout')?></span>     
                                    </button>
                                <?php endif ?>

                            <?php endif ?>

                        </div>
                        <?php if($_ENV['app.home'] == 0) :?>
                            <div class="MainMenu-Right">
                                <?php if(is_login(true)) :?>
                                
                                    <button class="js-register-open btn-register btn-tiny at-main-register-button" id="_btn_user_money" onclick="" style="margin-right:0px">
                                        <span class="txt_cash" style="padding:12px 0px;"><?=lang('common.money')?></span> 
                                        <span class="icon_cash" style="padding:12px 0px; width:24px; "><img src="<?php echo site_furl('/images/common/won.png?v=1'); ?>"></span>
                                        <span class="_has_cash" style="padding:12px 3px;"><?=number_format($user_money)?></span>
                                    </button>
                                
                                    <button class="js-register-open btn-register btn-tiny at-main-register-button" id="_btn_user_point" onclick="changePoint();" style="margin-left:10px">
                                        <span class="txt_cash" style="padding:12px 0px;"><?=lang('common.point')?></span> 
                                        <span class="icon_cash" style="padding:12px 0px; width:24px; "><img src="<?php echo site_furl('/images/common/point.png?v=1'); ?>"></span>
                                        <span class="_has_point" style="padding:12px 3px;"  ><?=number_format($user_point)?></span>
                                    </button>
                                <?php endif ?>
                            </div>
                        <?php endif ?>

                        <?php if(array_key_exists('app.lang', $_ENV) && intval($_ENV['app.lang']) > 0 ) :?>
                            <button name="lang" id="lang-button" is="ms-dropdown" >
                            <?php if($lang == "cn") :?>
                                <image id="lang-img" src="<?php echo site_furl('/images/common/cn.png?v=1'); ?>" style="width:22px; margin-top:-2px;">&nbsp;&nbsp;<span id="lang-code" ><?=lang('common.lang_chinese')?></span>
                            <?php else :?>
                                <image id="lang-img" src="<?php echo site_furl('/images/common/ko.png?v=1'); ?>" style="width:22px; margin-top:-2px;">&nbsp;&nbsp;<span id="lang-code" ><?=lang('common.lang_korean')?></span>
                            <?php endif ?>
                            </button>
                        <?php endif ?>
                        <?php if($_ENV['app.home'] == 0) :?>
                            <?php if(!is_login(true)) :?>
                                <button class="js-login-open btn-login btn-tiny btn-secondary at-login-button" style="z-index:1;" onclick="showAgentCheckModal();">
                                    <span><?=lang('common.signup')?></span>
                                </button>
                                <button class="js-login-open btn-login btn-tiny btn-secondary at-login-button" onclick="showLoginModal();"  
                                    <?php if(array_key_exists('app.lang', $_ENV) && intval($_ENV['app.lang']) > 0 ) :?>
                                        style="margin-top:27px;"
                                    <?php endif ?>
                                >
                                    <span><?=lang('common.login')?></span>
                                </button>
                            <?php else :?>
                                <button class="js-login-open btn-login btn-tiny btn-secondary at-login-button" id="_btn_logout" onclick="location.href='/home/logout'"
                                <?php if(array_key_exists('app.lang', $_ENV) && intval($_ENV['app.lang']) > 0 ) :?>
                                    style="margin-top:27px;"
                                <?php endif ?>
                                    >   
                                    <span><?=lang('common.logout')?></span>     
                                </button>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>
            </div>

            <div id="MainMenu" class="MainMenu">
                <div class="MainMenu-wrapper">
                    <div class="MainMenu-logo">
                        <?php if(!is_login(true)) :?>
                            <div class="MainMenu-play" onclick="showAgentCheckModal();">
                                <a href="#" class="js-register-open btn-primary btn-normal"><span><?=lang('common.signup')?></span></a>
                            </div>
                        <?php else :?>
                            <div class="MainMenu-play" onclick="SLB_POPUP(FURL + '/mypage')">
                                <a href="#" class="js-register-open btn-primary btn-normal">
                                    [<?=$user_name?>] <?=lang('common.welcome')?> <br />
                                    <?=lang('common.money')?> : <span class="_has_cash"><?=$user_money?></span><br />
                                    <?=lang('common.point')?> : <span class="_has_point"><?=$user_point?></span>
                                </a>
                            </div>
                        <?php endif ?>
                    </div>

                    <ul class="menu menu--main MainMenu-List">
                        <?php if(!is_login(true)) :?>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="showLoginModal();">
                            <a><i class="ui sign in icon"></i> <?=lang('common.login')?></a>
                        </li>
                        <?php endif ?>
                        
                        <?php if (!$hold_deny):?>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="showTabMenu('holdem');">
                            <a><i class="ui hospital symbol icon"></i> <?=lang('common.holdem')?></a>
                        </li>
                        <?php endif ?>

                        <?php if (!$evol_deny || !$cas_deny):?>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="showTabMenu('live-casino');">
                            <a><i class="ui life ring icon"></i> <?=lang('common.casino')?></a>
                        </li>
                        <?php endif ?>

                        <?php if (!$slot_deny):?>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="showTabMenu('slots');">
                            <a><i class="ui hockey puck icon"></i> <?=lang('common.slot')?></a>
                        </li>
                        <?php endif ?>

                        <?php if(!$bpg_deny || !$eos5_deny || !$eos3_deny || !$coin5_deny || !$coin3_deny || !$pbg_deny || !$dhp_deny) :?>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="showTabMenu('mini');">
                            <a><i class="ui bowling ball icon"></i> <?=lang('common.mini_games')?></a>
                        </li>
                        <?php endif ?>

                        <?php if ($apps_enable):?>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="showTabMenu('auto');">
                            <a><i class="ui life ring outline icon"></i> <?=lang('common.auto_app')?></a>
                        </li>
                        <?php endif ?>
                        <?php if(!$user_off) :?>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="requestCharge();">
                            <a><i class="ui cloud download icon"></i> <?=lang('common.deposit')?></a>
                        </li>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="requestWithdraw();">
                            <a><i class="ui cloud upload icon"></i> <?=lang('common.withdrawal')?></a>
                        </li>
                        <?php endif ?>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="requestAccount()">
                            <a><i class="ui question circle icon"></i> <?=lang('common.ask_account')?></a>
                        </li>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="SLB_POPUP(FURL + '/mypage', 'my_memo')">
                            <a><i class="ui comment outline icon"></i> <?=lang('common.message')?></a>
                        </li>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="SLB_POPUP(FURL + '/mypage', 'notice')">
                            <a><i class="ui bullhorn icon"></i> <?=lang('common.notice')?></a>
                        </li>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="SLB_POPUP(FURL + '/mypage', '')">
                            <a><i class="ui user icon"></i> <?=lang('common.myinfo')?></a>
                        </li>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="SLB_POPUP(FURL + '/mypage', 'my_qna')">
                            <a><i class="ui comment alternate icon"></i> <?=lang('common.customer')?><span id="answered_count"></span></a>
                        </li>
                        <?php if(is_login(true)) :?>

                            <?php if($part_en) :?>
                            <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="window.open('about:blank').location.href='/home/pt_login'">
                                <a><i class="ui users icon"></i> <?=lang('common.partener')?></a>
                            </li>
                            <?php endif ?>

                            <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="location.href='/home/logout'">
                                <a><i class="ui sign out icon"></i> <?=lang('common.logout')?></a>
                            </li>
                        <?php endif ?>
                    </ul>
                </div>
            </div>
            
            <div class="MainContent" id="js-main-content">
                <div class="MainContentPage">
                    <div class="SeoPage">
                        <section class="MainBanner-container banner-top">
                            <div class="container-max">
                                <div class="BannerSlider-container">
                                    <div id="main-banner-carousel" class="BannerSlider-list carousel slide js-banner-slider">
                                        <div class="carousel-inner" role="listbox">
                                            <div class="item active">
                                                <div class="block block--cta-block block--cta-block--category-video-slots block--category-video-slots" data-banner-id="category_video_slots">
                                                    <div class="BannerSlider-bg">
                                                        <div class="BannerSlider-bgDesktop">
                                                            <div class="bg-img field_decoupled_block_bg_image_category_video_slots" style=""></div>
                                                        </div>
                                                        <div class="star-container">
                                                            <img src="<?php echo site_furl('/images/common/star1.png'); ?>">
                                                            <img src="<?php echo site_furl('/images/common/star2.png'); ?>">
                                                            <img src="<?php echo site_furl('/images/common/star3.png'); ?>">
                                                            <img src="<?php echo site_furl('/images/common/star4.png'); ?>">
                                                        </div>
                                                        <div class="BannerItem-container">
                                                            <div class="BannerItem-content">
                                                                <div class="wrap">
                                                                    <div class="cont">
                                                                        <div class="field field--text-long">
                                                                        <?php if(!array_key_exists('main.welcome', $_ENV) || $_ENV['main.welcome'] != 0) :?>
                                                                            <h1><?=lang('common.welcome_to')?>.</h1>
                                                                            <div class="text">
                                                                                <?=lang('common.welcome_casino')?>
                                                                            </div>
                                                                        <?php endif ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>												
                        <!-- 메인 텍스트공지 마퀴-->
                        <div class="scroll_area">
                            <div class="scroll_text"><?=$notice_main?>
                            </div>
                        </div>
                        
                        <script type="text/javascript">
                           $(".BannerSlider-bgDesktop .field_decoupled_block_bg_image_category_video_slots").bgswitcher({
                                <?php if($_ENV['app.name'] == APP_HERMES) :?>
                                    images: ["<?php echo site_furl('/images/main/banner31.png?v=4'); ?>", "<?php echo site_furl('/images/main/banner32.png?v=4'); ?>"],
                                    effect: "clip",
                                <?php elseif($_ENV['app.name'] == APP_ATM || $_ENV['app.name'] == APP_FUN || $_ENV['app.name'] == APP_DUNK) :?>
                                    images: ["<?php echo site_furl('/images/main/banner61.png?v=4'); ?>", "<?php echo site_furl('/images/main/banner62.png?v=4'); ?>", "<?php echo site_furl('/images/main/banner63.png?v=4'); ?>"],
                                    effect: "clip",
                                <?php elseif($_ENV['app.name'] == APP_DOLPHIN) :?>
                                    images: ["<?php echo site_furl('/images/main/banner51_'.$_ENV['app.home'].'.png?v=4.5'); ?>", "<?php echo site_furl('/images/main/banner52_'.$_ENV['app.home'].'.png?v=4.5'); ?>", "<?php echo site_furl('/images/main/banner53_'.$_ENV['app.home'].'.png?v=4.5'); ?>"],
                                    effect: "clip",
                                <?php else: ?>
                                    images: ["<?php echo site_furl('/images/main/banner1.png'); ?>", "<?php echo site_furl('/images/main/banner2.png'); ?>", "<?php echo site_furl('/images/main/banner3.png'); ?>", "<?php echo site_furl('/images/main/banner4.png'); ?>"],
                                    effect: "clip",
                                <?php endif ?>
                                
                            });

                            $(".scroll_text").marquee();

                            function showTabMenu(menu) {

                                if(!check_login()){
                                    return;
                                }

                                $("#live-casino").hide();
                                $("#mini").hide();
                                $("#slots").hide();
                                $("#auto").hide();
                                $("#holdem").hide();

                                if($("#holdem").length > 0)
                                    $("#img_casino").attr("src", "<?php echo site_furl('/images/common/tab_casino_mid.png?v=1'); ?>");
                                else
                                    $("#img_casino").attr("src", "<?php echo site_furl('/images/common/tab_casino.png?v=1'); ?>");

                                if($("#auto").length > 0)
                                    $("#img_mini").attr("src", "<?php echo site_furl('/images/common/tab_mini.png?v=1'); ?>");
                                else 
                                    $("#img_mini").attr("src", "<?php echo site_furl('/images/common/tab_mini_rit.png?v=1'); ?>");
                                
                                $("#img_slots").attr("src", "<?php echo site_furl('/images/common/tab_slot.png?v=1'); ?>");
                                $("#img_auto").attr("src", "<?php echo site_furl('/images/common/tab_auto.png?v=1'); ?>");
                                $("#img_holdem").attr("src", "<?php echo site_furl('/images/common/tab_holdem.png?v=1'); ?>");

                                if (menu == "slots") {
                                    $("#slots").fadeIn("slow");
                                    $("#img_slots").attr("src", "<?php echo site_furl('/images/common/tab_slot_select.png?v=1'); ?>");
                                } else if (menu == "mini") {
                                    $("#mini").fadeIn("slow");
                                    if($("#auto").length > 0)
                                        $("#img_mini").attr("src", "<?php echo site_furl('/images/common/tab_mini_select.png?v=1'); ?>");
                                    else 
                                        $("#img_mini").attr("src", "<?php echo site_furl('/images/common/tab_mini_select_rit.png?v=1'); ?>");
                                } else if (menu == "auto") {
                                    $("#auto").fadeIn("slow");
                                    $("#img_auto").attr("src", "<?php echo site_furl('/images/common/tab_auto_select.png?v=1'); ?>");
                                } else if (menu == "holdem") {
                                    $("#holdem").fadeIn("slow");
                                    $("#img_holdem").attr("src", "<?php echo site_furl('/images/common/tab_holdem_select.png?v=1'); ?>");
                                } else {
                                    $("#live-casino").fadeIn("slow");
                                    if($("#holdem").length > 0)
                                        $("#img_casino").attr("src", "<?php echo site_furl('/images/common/tab_casino_select_mid.png?v=1'); ?>");
                                    else
                                        $("#img_casino").attr("src", "<?php echo site_furl('/images/common/tab_casino_select.png?v=1'); ?>");
                                }
                            }

                            $("#MainMenu .MainMenu-play, #MainMenu .MainMenu-item").click(function(e) {
                                $("#MainMenu-controller").prop("checked", false);
                            });
                        </script>
                        <div class="seoCategoryPage-category categories-wrapper js-seo-category-page-categories">
                            <div class="categories categories-desktop js-games-categories-slider slick-initialized slick-slider">
                                <div class="ui two column centered grid">
                                    <div class="five column centered row">
                                        <?php if (!$hold_deny):?>
                                            <div class="column first">
                                                <img src="<?php echo site_furl('/images/common/tab_holdem_select.png?v=1');?>" onclick="javascript:showTabMenu('holdem');" id="img_holdem" style="cursor: pointer;" />
                                            </div>
                                        <?php endif ?>
                                        <?php if (!$evol_deny || !$cas_deny):?>
                                            <div class="column first">
                                                <img 
                                                    <?php if (!$hold_deny):?>
                                                        src="<?php echo site_furl('/images/common/tab_casino_mid.png?v=1');?>" 
                                                    <?php else :?>
                                                        src="<?php echo site_furl('/images/common/tab_casino_select.png?v=1');?>" 
                                                    <?php endif ?>
                                                    onclick="javascript:showTabMenu('live-casino');" id="img_casino" style="cursor: pointer;" />
                                            </div>
                                        <?php endif ?>
                                        <?php if (!$slot_deny):?>
                                            <div class="column first">
                                                <img src="<?php echo site_furl('/images/common/tab_slot.png?v=1');?>" onclick="javascript:showTabMenu('slots');" id="img_slots" style="cursor: pointer;" />
                                            </div>
                                        <?php endif ?>
                                        
                                        <?php if(!$bpg_deny || !$eos5_deny || !$eos3_deny || !$coin5_deny || !$coin3_deny || !$pbg_deny || !$dhp_deny) :?>
                                        <div class="column first">
                                            <img     
                                            <?php if ($apps_enable):?>
                                                src="<?php echo site_furl('/images/common/tab_mini.png?v=1');?>" 
                                            <?php else:?>
                                                src="<?php echo site_furl('/images/common/tab_mini_rit.png?v=1');?>" 
                                            <?php endif ?>
                                            onclick="javascript:showTabMenu('mini');" id="img_mini" style="cursor: pointer;" />
                                        </div>
                                        <?php endif ?>
                                        <?php if ($apps_enable):?>
                                            <div class="column first">
                                                <img src="<?php echo site_furl('/images/common/tab_auto.png?v=1');?>" onclick="javascript:showTabMenu('auto');" id="img_auto" style="cursor: pointer;" />
                                            </div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php if( array_key_exists('main.jackpot', $_ENV) && $_ENV['main.jackpot'] == 1 && !is_login(true)) :?>
                        <div class="jackpot-container justify-content-end">
                            <img class="golden-bull" src="<?php echo site_furl('/images/jackpot/jackpot.png'); ?>" alt="">
                            <div class="jackpot-amount">
                                <img src="<?php echo site_furl('/images/jackpot/won-sign.png'); ?>" alt="" style="display:inline-block;">
                                <div id="jackpot" class=""></div>
                            </div>
                        </div>

                        <script>
                            function playJackpot(){
                                var jackpot = Date.now() / 32897
                                var el = document.querySelector('#jackpot');

                                od = new Odometer({
                                el: el,
                                auto: false,
                                selector: '.jackpot',
                                value: jackpot,
                                format: '(,ddd).dd',
                                });

                                setInterval(function(){
                                    jackpot += 32423.372 
                                    od.update(jackpot)

                                    if(jackpot > 61233000) {
                                        jackpot -= 9334909
                                    }
                                }, 5000);

                                setInterval(function(){
                                    reqExchanges();
                                }, 300000);
                                
                            }
                            playJackpot();

                        </script>

                        <div class="autoscroller">
                            <div class="autoscroller-d d-flex" >
                                <!--                                 
                                <div class="ps-card flex-1 flex-column ps-realtime">
                                    <div class="scroller-header" ><?=lang('common.realtime')?><?=lang('common.deposit')?></div>
                                    <div class="ps-card-body">
                                        <div class="Loop autoscroll-container">
                                            <div class="inner" id="recentCharges1">
                                                <?php if(count($charges) >= 8) :?>
                                                    <?php for ($i=0; $i<4; $i++):?>
                                                        <div class="item d-flex justify-content-between" >
                                                            <span class="item-username "><?=$charges[$i]->uid?></span>
                                                            <span class="item-amount">
                                                                <span><?=$charges[$i]->amount?></span>
                                                                <span><?=lang('common.won')?></span>
                                                            </span>
                                                            <span class="item-date "><?=$charges[$i]->time?></span>
                                                        </div>
                                                    <?php endfor; ?>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                        <div class="Loop autoscroll-container">
                                            <div class="inner" id="recentCharges2">
                                                <?php if(count($charges) >= 8) :?>
                                                    <?php for ($i=4; $i<8; $i++):?>
                                                        <div class="item d-flex justify-content-between" >
                                                            <span class="item-username "><?=$charges[$i]->uid?></span>
                                                            <span class="item-amount">
                                                                <span><?=$charges[$i]->amount?></span>
                                                                <span><?=lang('common.won')?></span>
                                                            </span>
                                                            <span class="item-date "><?=$charges[$i]->time?></span>
                                                        </div>
                                                    <?php endfor; ?>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="ps-card flex-1 flex-column ps-realtime">
                                    <div class="scroller-header"><?=lang('common.realtime')?><?=lang('common.withdrawal')?></div>
                                    <div class="ps-card-body">
                                        <div class="Loop autoscroll-container">
                                            <div class="inner" id="recentDischars1">
                                                <?php if(count($dischars) >= 8) :?>
                                                    <?php for ($i=0; $i<4; $i++):?>
                                                        <div class="item d-flex justify-content-between" >
                                                            <span class="item-username "><?=$dischars[$i]->uid?></span>
                                                            <span class="item-amount">
                                                                <span><?=$dischars[$i]->amount?></span>
                                                                <span><?=lang('common.won')?></span>
                                                            </span>
                                                            <span class="item-date "><?=$dischars[$i]->time?></span>
                                                        </div>
                                                    <?php endfor; ?>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                        <div class="Loop autoscroll-container">
                                            <div class="inner" id="recentDischars2">
                                                <?php if(count($dischars) >= 8) :?>
                                                    <?php for ($i=4; $i<8; $i++):?>
                                                        <div class="item d-flex justify-content-between" >
                                                            <span class="item-username "><?=$dischars[$i]->uid?></span>
                                                            <span class="item-amount">
                                                                <span><?=$dischars[$i]->amount?></span>
                                                                <span><?=lang('common.won')?></span>
                                                            </span>
                                                            <span class="item-date "><?=$dischars[$i]->time?></span>
                                                        </div>
                                                    <?php endfor; ?>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
																
                            </div>
                        </div>
                    <?php else: ?>

                        <?php if (!$slot_deny):?>
                        <section class="uk-section" id="slots"  style="display: none;">
                            <div class="uk-container">
                                <h2 style="margin-bottom: 20px; text-align: center;">SLOT GAMES
                                    <div class="star-title">
                                        <img src="<?php echo site_furl('/images/common/star1.png'); ?>">
                                        <img src="<?php echo site_furl('/images/common/star2.png'); ?>">
                                        <img src="<?php echo site_furl('/images/common/star3.png'); ?>">
                                        <img src="<?php echo site_furl('/images/common/star4.png'); ?>">
                                        <img src="<?php echo site_furl('/images/common/star1.png'); ?>">
                                    </div>
                                </h2>
                                <div class="uk-grid uk-grid-small uk-child-width-1-3 uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-5@xl uk-grid-match" data-uk-lightbox="toggle:a.uk-position-cover" data-uk-grid="">
                                    <!-- item -->
                                    <?php foreach ($slot_plus as $item):?>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-card-media-top">
                                                    <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                        <?php if( array_key_exists('game.img_suf', $_ENV)) :?>
                                                            <img src="<?php echo site_furl('/images/slot/'.$item->img.'_'.$_ENV['game.img_suf'].'.png'); ?>" />
                                                        <?php else :?>
                                                            <img src="<?php echo site_furl('/images/slot/'.$item->img.'.png'); ?>" />
                                                        <?php endif ?>
                                                        <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                            <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                                <button class="uk-button uk-button-primary openGameBtn uk-first-column" id="playBtn" title="<?=$item->name_kr?>" data-cid="<?=$item->code?>" data-cname="<?=$item->name_kr?>"
                                                                    <?php if($item->maintain==1) :?>
                                                                        data-onoff="off"><?=lang('common.inspection')?>
                                                                    <?php else :?>
                                                                        data-onoff="on">Play
                                                                    <?php endif ?>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand uk-first-column">
                                                            <?php if(array_key_exists('game.img_suf', $_ENV)) :?>
                                                                <span class="game_title <?=$item->maintain==1?'gray':'blue'?>">&nbsp;&nbsp;</span>
                                                            <?php else :?>
                                                                <!-- <span class="game_title <?=$item->maintain==1?'gray':'blue'?>"><?=$item->name_kr?></span> -->
                                                                <span class="game_title <?=$item->maintain==1?'gray':'blue'?>">&nbsp;&nbsp;</span>
                                                            <?php endif ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    <!-- item -->
                                </div>
                            </div>
                        </section>
                        <?php endif ?>

                        <?php if (!$evol_deny || !$cas_deny):?>
                        <section class="uk-section" id="live-casino"  
                            <?php if (!$hold_deny):?>
                                style="display: none;"
                            <?php endif ?>
                            >

                            <div class="uk-container">
                                <h2 style="margin-bottom: 20px; text-align: center;">
                                    LIVE CASINO
                                    <div class="star-title">
                                        <img src="<?php echo site_furl('/images/common/star1.png'); ?>">
                                        <img src="<?php echo site_furl('/images/common/star2.png'); ?>">
                                        <img src="<?php echo site_furl('/images/common/star3.png'); ?>">
                                        <img src="<?php echo site_furl('/images/common/star4.png'); ?>">
                                        <img src="<?php echo site_furl('/images/common/star1.png'); ?>">
                                    </div>
                                </h2>
                                <div class="uk-grid uk-grid-small uk-child-width-1-3 uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-5@xl uk-grid-match uk-grid-stack" data-uk-lightbox="toggle:a.uk-position-cover" data-uk-grid="">
                                    <!-- item -->
                                    <?php foreach ($cas_evol as $item):?>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-card-media-top">
                                                    <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                        <?php if( array_key_exists('game.img_suf', $_ENV)) :?>
                                                            <img src="<?php echo site_furl('/images/casino/'.$item->img.'_'.$_ENV['game.img_suf'].'.png'); ?>" />
                                                        <?php else :?>
                                                            <img src="<?php echo site_furl('/images/casino/'.$item->img.'.png'); ?>" />
                                                        <?php endif ?>
                                                        <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                            <div data-uk-margin="" class="uk-transition-slide-bottom-small">

                                                                <button class="uk-button uk-button-primary playBtn" id="playBtn" data-cid="<?=$item->cas_id?>" data-gameid="<?=$item->cat?>" 
                                                                    <?php if($item->maintain==1) :?>
                                                                        data-onoff="off"><?=lang('common.inspection')?>
                                                                    <?php else :?>
                                                                        data-onoff="on">Play
                                                                    <?php endif ?>
                                                                </button>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand">
                                                            <?php if(array_key_exists('game.img_suf', $_ENV)) :?>
                                                                <span class="game_title <?=$item->maintain==1?'gray':'blue'?>">&nbsp;&nbsp;</span>
                                                            <?php else :?>
                                                                <!-- <span class="game_title <?=$item->maintain==1?'gray':'blue'?>"><?=$item->name?></span> -->
                                                                <span class="game_title <?=$item->maintain==1?'gray':'blue'?>">&nbsp;&nbsp;</span>
                                                            <?php endif ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                    <?php foreach ($cas_kgon as $item):?>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-card-media-top">
                                                    <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                        
                                                        <?php if( $item->name!="") :?>
                                                            <video poster="<?php echo site_furl('/images/casino/'.$item->img.'.png?v=1'); ?>" class="" width="100%" height="" autoplay="" loop="" muted="" playsinline="" webkit-playsinline="">
                                                                <source src="<?php echo site_furl('/images/casino/'.$item->img.'.png?v=1'); ?>" type="video/png">
                                                                <source src="<?php echo site_furl('/images/casino/'.$item->img.'.mp4?v=3'); ?>" type="video/mp4">
                                                            </video>
                                                        <?php else : ?>
                                                            <?php if( array_key_exists('game.img_suf', $_ENV)) :?>
                                                                <img src="<?php echo site_furl('/images/casino/'.$item->img.'_'.$_ENV['game.img_suf'].'.png'); ?>" />
                                                            <?php else :?>
                                                                <img src="<?php echo site_furl('/images/casino/'.$item->img.'.png?v=2'); ?>" />
                                                            <?php endif ?>
                                                        <?php endif ?>

                                                        <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                            <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                                <button class="uk-button uk-button-primary playBtn" id="playBtn" data-cid="<?=$item->cas_id?>" data-gameid="<?=$item->cat?>"
                                                                    <?php if($item->maintain==1) :?>
                                                                        data-onoff="off"><?=lang('common.inspection')?>
                                                                    <?php else :?>
                                                                        data-onoff="on">Play
                                                                    <?php endif ?>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand">
                                                            <?php if(array_key_exists('game.img_suf', $_ENV)) :?>
                                                                <span class="game_title <?=$item->maintain==1?'gray':'blue'?>"> </span>
                                                            <?php else :?>
                                                                <!-- <span class="game_title <?=$item->maintain==1?'gray':'blue'?>"><?=$item->name?></span> -->
                                                                <span class="game_title <?=$item->maintain==1?'gray':'blue'?>"> </span>
                                                            <?php endif ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                    <!-- item -->
                                </div>
                            </div>
                        </section>
                        <?php endif ?>

                        <?php if(!$bpg_deny || !$eos5_deny || !$eos3_deny || !$coin5_deny || !$coin3_deny || !$pbg_deny || !$dhp_deny) :?>
                        <section class="uk-section" id="mini" style="display: none;">
                            <div class="uk-container">
                                <h2 style="margin-bottom: 20px; text-align: center;">MINI GAMES
                                    <div class="star-title">
                                        <img src="<?php echo site_furl('/images/common/star1.png'); ?>">
                                        <img src="<?php echo site_furl('/images/common/star2.png'); ?>">
                                        <img src="<?php echo site_furl('/images/common/star3.png'); ?>">
                                        <img src="<?php echo site_furl('/images/common/star4.png'); ?>">
                                        <img src="<?php echo site_furl('/images/common/star1.png'); ?>">
                                    </div>
                                </h2>
                                <div class="uk-grid uk-grid-small uk-child-width-1-3 uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-5@xl uk-grid-match" data-uk-lightbox="toggle:a.uk-position-cover" data-uk-grid="">
                                    <!-- item -->
                                     <?php if(!$dhp_deny) :?>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-card-media-top">
                                                    <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                        <img src="<?php echo site_furl('/images/mini/btn_dhp.png?v=2'); ?>" />
                                                        <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                            <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                                <button class="uk-button uk-button-primary openGameBtn uk-first-column" id="playBtn" title="동행파워볼" data-onoff="on" data-cid="DHP">Play </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand uk-first-column">
                                                                <span class="game_title blue">&nbsp;&nbsp;</span>
                                                                <!-- <span class="game_title blue"><?=lang('common.powerball_dhp')?></span> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    <?php if(!$pbg_deny) :?>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-card-media-top">
                                                    <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                        <img src="<?php echo site_furl('/images/mini/btn_pbg.png?v=1'); ?>" />
                                                        <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                            <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                                <button class="uk-button uk-button-primary openGameBtn uk-first-column" id="playBtn" title="PBG파워볼" data-onoff="on" data-cid="PBG">Play </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand uk-first-column">
                                                                <span class="game_title blue">&nbsp;&nbsp;</span>
                                                                <!-- <span class="game_title blue"><?=lang('common.powerball_pbg')?></span> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    <?php if(!$evp_deny) :?>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-card-media-top">
                                                    <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                        <img src="<?php echo site_furl('/images/mini/btn_evp.png?v=2'); ?>" />
                                                        <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                            <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                                <button class="uk-button uk-button-primary openGameBtn uk-first-column" id="playBtn" title="에볼루션파워볼" data-onoff="on" data-cid="EVP">Play </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand uk-first-column">
                                                                <span class="game_title blue">&nbsp;&nbsp;</span>
                                                                <!-- <span class="game_title blue"><?=lang('common.powerball_evol')?></span> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    <?php if(!$spk_deny) :?>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-card-media-top">
                                                    <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                        <img src="<?php echo site_furl('/images/mini/btn_spk.png?v=2'); ?>" />
                                                        <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                            <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                                <button class="uk-button uk-button-primary openGameBtn uk-first-column" id="playBtn" title="스피드키노" data-onoff="on" data-cid="SPKN">Play </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand uk-first-column">
                                                                <span class="game_title blue">&nbsp;&nbsp;</span>
                                                                <!-- <span class="game_title blue"><?=lang('common.powerball_spkn')?></span> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    <?php if(!$bpg_deny) :?>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-card-media-top">
                                                    <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                        <?php if( array_key_exists('game.img_suf', $_ENV)) :?>
                                                            <img src="<?php echo site_furl('/images/mini/btn_bgb_'.$_ENV['game.img_suf'].'.png'); ?>" />
                                                        <?php else :?>
                                                            <img src="<?php echo site_furl('/images/mini/btn_bgb.png?v=2'); ?>" />
                                                        <?php endif ?>
                                                        <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                            <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                                <button class="uk-button uk-button-primary openGameBtn uk-first-column" id="playBtn" title="보글파워볼" data-onoff="on" data-cid="BGB">Play </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand uk-first-column">
                                                                <span class="game_title blue">&nbsp;&nbsp;</span>
                                                                <!-- <span class="game_title blue"><?=lang('common.powerball_boggle')?></span> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-card-media-top">
                                                    <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                        <?php if( array_key_exists('game.img_suf', $_ENV)) :?>
                                                            <img src="<?php echo site_furl('/images/mini/btn_bgl_'.$_ENV['game.img_suf'].'.png'); ?>" />
                                                        <?php else :?>
                                                            <img src="<?php echo site_furl('/images/mini/btn_bgl.png?v=2'); ?>" />
                                                        <?php endif ?>
                                                        <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                            <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                                <button class="uk-button uk-button-primary openGameBtn uk-first-column" id="playBtn" title="보글사다리" data-onoff="on"  data-cid="BGL">Play </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand uk-first-column">
                                                                <span class="game_title blue">&nbsp;&nbsp;</span>
                                                                <!-- <span class="game_title blue"><?=lang('common.powerladder_boggle')?></span> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    <?php if(!$eos5_deny) :?>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-card-media-top">
                                                    <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                        <img src="<?php echo site_furl('/images/mini/btn_eos5.png?v=2'); ?>" />
                                                        <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                            <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                                <button class="uk-button uk-button-primary openGameBtn uk-first-column" id="playBtn" title="EOS5분파워볼" data-onoff="on"  data-cid="EOS5">Play </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand uk-first-column">
                                                                <span class="game_title blue">&nbsp;&nbsp;</span>
                                                                <!-- <span class="game_title blue"><?=lang('common.powerball_eos5')?></span> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    <?php if(!$eos3_deny) :?>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-card-media-top">
                                                    <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                        <img src="<?php echo site_furl('/images/mini/btn_eos3.png?v=2'); ?>" />
                                                        <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                            <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                                <button class="uk-button uk-button-primary openGameBtn uk-first-column" id="playBtn" title="EOS3분파워볼" data-onoff="on" data-cid="EOS3">Play </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand uk-first-column">
                                                                <span class="game_title blue">&nbsp;&nbsp;</span>
                                                                <!-- <span class="game_title blue"><?=lang('common.powerball_eos3')?></span> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    <?php if(!$coin5_deny) :?>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-card-media-top">
                                                    <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                        <img src="<?php echo site_furl('/images/mini/btn_coin5.png?v=2'); ?>" />
                                                        <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                            <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                                <button class="uk-button uk-button-primary openGameBtn uk-first-column" id="playBtn" title="코인5분파워볼" data-onoff="on" data-cid="COIN5">Play </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand uk-first-column">
                                                                <span class="game_title blue">&nbsp;&nbsp;</span>
                                                                <!-- <span class="game_title blue"><?=lang('common.powerball_coin5')?></span> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    <?php if(!$coin3_deny) :?>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-card-media-top">
                                                    <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                        <img src="<?php echo site_furl('/images/mini/btn_coin3.png?v=2'); ?>" />
                                                        <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                            <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                                <button class="uk-button uk-button-primary openGameBtn uk-first-column" id="playBtn" title="코인3분파워볼" data-onoff="on" data-cid="COIN3">Play </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand uk-first-column">
                                                                <span class="game_title blue">&nbsp;&nbsp;</span>
                                                                <!-- <span class="game_title blue"><?=lang('common.powerball_coin3')?></span> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    
                                    <?php if($_ENV['app.home'] > 0) :?>
                                        
                                        <!-- <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-card-media-top">
                                                    <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                        <img src="<?php echo site_furl('/images/mini/btn_pb.png'); ?>" />
                                                        <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                            <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                                <button class="uk-button uk-button-primary openGameBtn uk-first-column" id="playBtn" title="파워볼" data-onoff="off"  data-cid="PBG">Play </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand uk-first-column">
                                                            <span class="game_title blue">&nbsp;&nbsp;</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-card-media-top">
                                                    <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                        <img src="<?php echo site_furl('/images/mini/btn_ang.png'); ?>" />
                                                        <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                            <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                                <button class="uk-button uk-button-primary openGameBtn uk-first-column" id="playBtn" title="ANGELS" data-onoff="off"  data-cid="ANG">Play </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand uk-first-column">
                                                            <span class="game_title blue">&nbsp;&nbsp;</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                    <?php endif ?>

                                    <!-- item -->
                                </div>
                            </div>
                        </section>
                        <?php endif ?>
                        
                        <?php if (!$hold_deny):?>
                        <section class="uk-section" id="holdem">
                            <div class="uk-container">
                                <h2 style="margin-bottom: 20px; text-align: center;">HOLDEM GAME
                                    <div class="star-title">
                                        <img src="<?php echo site_furl('/images/common/star1.png'); ?>">
                                        <img src="<?php echo site_furl('/images/common/star2.png'); ?>">
                                        <img src="<?php echo site_furl('/images/common/star3.png'); ?>">
                                        <img src="<?php echo site_furl('/images/common/star4.png'); ?>">
                                        <img src="<?php echo site_furl('/images/common/star1.png'); ?>">
                                    </div>
                                </h2>
                                <div class="uk-grid uk-grid-small uk-child-width-1-3 uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-5@xl uk-grid-match uk-flex-center" data-uk-lightbox="toggle:a.uk-position-cover" data-uk-grid="">
                                    <!-- item -->
                                    <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-card-media-top">
                                                    <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                        <img src="<?php echo site_furl('/images/mini/btn_hold.png?v=1'); ?>" />
                                                        <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                            <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                                <button class="uk-button uk-button-primary openGameBtn uk-first-column" id="playBtn" title="<?=lang('common.holdem')?>" data-onoff="on" data-cid="Holdem">Play </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand uk-first-column uk-flex uk-flex-center">
                                                            <span class="game_title blue"><?=lang('common.holdem')?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <!-- item -->
                                </div>
                            </div>
                        </section>
                        <?php endif ?>

                        <?php if($apps_enable):?>
                        <!-- Auto Apps -->
                        <section class="uk-section" id="auto" style="display: none;">
                            <div class="uk-container">
                                <h2 style="margin-bottom: 20px; text-align: center;">
                                    AUTO APPS
                                    <div class="star-title">
                                        <img src="<?php echo site_furl('/images/common/star1.png'); ?>">
                                        <img src="<?php echo site_furl('/images/common/star2.png'); ?>">
                                        <img src="<?php echo site_furl('/images/common/star3.png'); ?>">
                                        <img src="<?php echo site_furl('/images/common/star4.png'); ?>">
                                        <img src="<?php echo site_furl('/images/common/star1.png'); ?>">
                                    </div>
                                </h2>
                                <div class="uk-grid uk-grid-small uk-child-width-1-3 uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-5@xl uk-grid-match uk-grid-stack" data-uk-lightbox="toggle:a.uk-position-cover" data-uk-grid="">
                                    <!-- item -->
                                    <?php foreach ($apps_auto as $item):?>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-card-media-top">
                                                    <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                        <img src="<?php echo site_furl('/images/app/'.$item->ename.'.png?v=2'); ?>" />
                                                        <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                            <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                                <button class="uk-button uk-button-primary playBtn" id="playBtn" data-name="<?=$item->name?>" data-path="<?=$item->path?>" data-act="<?=$item->act?>">Download</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand">
                                                            <span class="game_title blue"><?=$item->name?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <!-- item -->
                                    <?php endforeach ?>
                                </div>
                            </div>
                        </section>
                        <?php endif ?>
                    </div>
                <?php endif?>
                </div>
            </div>
            <section class="FooterSection">
                <div class="PaymentIconsContainer standard">
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--visa"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--mastercard"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--maestro"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--neteller"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--skrill"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--trustly"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--sofort"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--giropay"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--paysafecard"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--boku"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--euteller"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--ecopayz"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--interac"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--idebit"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--instadebit"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--easyeft2"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--jeton"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--paypal"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--ecobanq"></use>
                        </svg>
                    </div>

                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--applepay"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--astropay"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--neosurf"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--ezeewallet"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--bank-lm"></use>
                        </svg>
                    </div>
                </div>

                <div class="Footer">
                    <div class="Footer-wrapper">
                    <span  style="font-size:17px; padding:10px;">Copyright 2022 <span style="color:white"><?=$site_name?></span>. All right reserved.</span>
                    </div>
                </div>

            </section>
        </div>

        <?php if($_ENV['app.home'] == 0) :?>

        <!--MODAL-->
        <div id="loginModal" uk-modal class="uk-modal">
            <div class="uk-modal-dialog">
                <!-- <form class="ui form equal width" name="formLogin" id="formLogin" autocomplete="off"> -->
                    <div class="uk-modal-header">
                        <h2 class="uk-modal-title"><?=lang('common.login')?></h2>
                    </div>
                    <button class="uk-button uk-modal-close-default uk-icon uk-close" uk-close></button>
                    <div class="uk-modal-body">
                        <div class="ui fields">
                            <div class="field required">
                                <label><?=lang('common.id')?> </label>
                                <input type="text" name="userid" id="userid" placeholder="userid" onKeyDown="onEnter();"/>
                            </div>

                            <div class="field required">
                                <label><?=lang('common.password')?> </label>
                                <input type="password" name="passwd" id="passwd" placeholder="password" onKeyDown="onEnter();"/>
                            </div>
                            <input type="text" name="ip" class="ip_addr" ip="ip"  hidden/>
                        </div>
                    </div>
                    <div class="uk-modal-footer">
                        <button class="ui primary submit button" type="submit" onclick="login();"><?=lang('common.log_in')?></button>
                        <div class="ui uk-modal-close button"><?=lang('common.cancel')?></div>
                    </div>
                <!-- </form> -->
            </div>
        </div>
        <?php else :?>
        
        <style>
            .modal-container {
                position: fixed;
                top: 0;
                left:0;
                height: 100vh;
                width: 100vw;
                opacity: 0;
                transition: 0.3s;
                z-index: -1;
                display: none;
                pointer-events: none;
            }

            .modal-container * {
                transition: 0.3s;
                /* z-index: -1; */
            }


            .modal-container.show {
                opacity: 1;
                z-index: 100;
                display: block;
                pointer-events: initial;
            }
            #overlay, .overlay {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 100vw;
                background-color: #000;
                opacity: 0.8;
                transition: 0.3s;
                z-index: -1;
            }
            
            .modal-wrapper {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: var(--grey-400);
                /* box-shadow: var(--box-shadow-500); */
                /* border-radius: 16px; */
                z-index: 0;
                overflow-y: auto;
            }
            .login-modal {
                background-color: #292e2f;
                padding: 20px;
                border-radius: 0px;
                box-shadow: 0;
                width: 100%;
                max-width: 720px;
                min-width: 250px;
                color:var(--grey-10);
            }
            @media (min-width: 481px){
                .login-modal {
                    height: 480px;
                }
                .modal-wrapper {
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    /* overflow-x: hidden; */
                    overflow-y: unset;
                }
            }
            .fs-icon {
                display: block;
                height: 24px;
                width: 24px;
                background-repeat: no-repeat;
                background-size: cover;
            }

            .fs-icon.x-mark {
                background-image: url(/images/login/x-mark.svg);
                cursor: pointer;
            }
            .x-mark.spin:hover {
                animation: 1s spin;
            }
            .fs-icon.user {
                background-image: url(/images/login/user.svg);
            }
            .fs-icon.password {
                background-image: url(/images/login/password.svg);
            }
            .px-48 {
                height: 48px;
                width: 48px;
            }
            .login-modal .fs-icon.x-mark {
                position: absolute;
                right: 20px;
                top: 20px;
                cursor: pointer;
            }
            .login-modal input {
                width: 100%;
                background-color: #141414;
                color: #fefefe;
                display: block;
                box-sizing: border-box;

                padding: 16px;
                font-size: 14px;
                border-radius: 6px;
                border: 1px solid var(--grey-300);
                transition: all 0.1s linear;
            }
            .login-modal input:focus {
                border: 1px solid #DBB997;
                box-shadow: 0 0 0 1px #DBB997;
            }

            .login-modal button {
                width: 100%;
            }
            .login-image img {
                position: absolute;
                bottom: 0;
            <?php if($_ENV['app.home'] == 2 || $_ENV['app.home'] == 3) :?>
                left: -60px;
            <?php else :?>
                left: -120px;
            <?php endif ?>
                z-index: -1;
            }
            @media (max-width: 470px) {
                .login-image {
                    display: none;
                }
            }
            .login-form-container .login-title {
                font-size: 28px;
                text-align:center;
            }
            .login-form {
                width: 100%;
                box-sizing: border-box;
                padding: 20px;
            }
            .login-form .input-group:first-child {
                border-top: 1px solid #6c6d6d;
                padding-top: 20px;
            }
            .login-modal .input-group {
                width: 100%;
            }
            .login-modal .input-group span {
                display: inline-block;
                margin-bottom: 8px;
            }
            .login-modal .btn {
                /* height: 36px; */
                padding: 12px 16px;
                border-radius: 4px;
                color: var(--text-color-dark);
                background-color: #ffb08e;
                /* background: linear-gradient(180deg, #994131 0%, #FFB08E 36.98%, #F7BCA3 49.48%, #994131 100%); */
                border-color: #ffb08e;
                border: 0;
                border-style: solid;
                cursor: pointer;
                line-height: 14px;
                font-weight: bold;
                transition: none;
            }
            .login-modal .btn-login {
                font-family: "Noto Sans Kr", sans-seri f;
                color: #333;
                font-size: 18px;
                font-weight: 700;
            }

        </style>


        <div class="modal-container" id="loginModal">
            <div id="overlay" onclick="hideLoginModal();"></div>
            <div class="modal-wrapper login-modal d-flex">
                <a type="button" onclick="hideLoginModal();"> 
                    <i class="fs-icon x-mark spin px-48"></i>
                </a>
                <div class="login-image flex-1 web">
                    <?php if($_ENV['app.home'] == 2 || $_ENV['app.home'] == 3) :?>
                        <img src="<?php echo site_furl('/images/login/login-img2.png');?>" style="width:440px; height:550px;" />
                    <?php else : ?>
                        <img src="<?php echo site_furl('/images/login/login-img.png'); ?>" alt="" />
                    <?php endif ?>

                </div>

                <div class="login-form-container flex-1 d-flex flex-column gap-16 justify-content-center align-items-center">
                    <!-- <form style="width:100%;" name="formLogin" id="formLogin" autocomplete="off"> -->
                        <div class="login-title"><?=lang('common.login')?></div>
                        <div class="login-form d-flex flex-column gap-16">
                            <div class="input-group mb-4">
                                <span class="d-flex align-items-center">
                                    <i class="fs-icon user d-inline-block me-1"></i>
                                    <?=lang('common.id')?>
                                </span>
                                <div class="field required">
                                    <input type="text" name="userid" id="userid" placeholder="<?=lang('common.id')?>" style="width:100%;" onKeyDown="onEnter();"/>
                                </div>
                            </div>
                            <div class="input-group mb-4">
                                <span>
                                    <i class="fs-icon password d-inline-block me-1" style="position:relative; top:5px;"></i>
                                    <?=lang('common.password')?>
                                </span>
                                <div class="field required">
                                    <input type="password" name="passwd" id="passwd" placeholder="<?=lang('common.password')?>" style="width:100%;" onKeyDown="onEnter();"/>
                                </div>
                            </div>
                            <?php if(strlen($captcha) > 0) :?>
                            <div class="input-group mb-4">
                                <img id="image_id" name="<?=$captcha?>" src="<?php echo site_furl('/download/captcha/'.$captcha.'.jpg'); ?>" style="width: 100%; height: 30px; margin-bottom: 10px; border-radius: 0.25rem; background-color: beige;" >
                                <div class="field required">
                                    <input name="captchacode" id="captchacode" placeholder="<?=lang('common.security_character')?>" maxlength="10" type="text" id="captchacode" style="width: 100%; margin-bottom:10px;" onKeyDown="onEnter();">
                                </div>
                            </div>
                            <?php endif ?>
                            <input type="text" name="ip" class="ip_addr" id="ip_addr"  hidden/>
                            <input type="text" name="captchasrc" id="captchasrc" value="<?=$captcha?>" hidden/>
                            <div class="button-group">
                                <button class="btn btn-login ng-scope" type="submit"  onclick="login();"><?=lang('common.login')?></button>
                            </div>
                        </div>
                    <!-- </form> -->
                </div>
            </div>
        </div>
        <?php endif ?>

        <div id="agentCheckModal" uk-modal class="uk-modal">
            <div class="uk-modal-dialog">
                <form class="ui form equal width" name="agentCheckForm" id="agentCheckForm" autocomplete="off">
                    <div class="uk-modal-header">
                        <h2 class="uk-modal-title"><?=lang('common.recommender_in')?></h2>
                    </div>
                    <button class="uk-button uk-modal-close-default uk-icon uk-close" uk-close></button>
                    <div class="uk-modal-body">
                        <div class="ui fields">
                            <div class="field required">
                                <label><?=lang('common.recommender_id')?> </label>
                                <input type="text" name="recommender_id" id="recommender_id" placeholder="<?=lang('common.recommender_id')?>" value="" />
                            </div>
                        </div>
                    </div>
                    <div class="uk-modal-footer">
                        <button class="ui primary submit button" type="button"><?=lang('common.next')?></button>
                        <div class="ui uk-modal-close button"><?=lang('common.cancel')?></div>
                    </div>
                </form>
            </div>
        </div>
        <div id="registModal" uk-modal class="uk-modal">
            <div class="uk-modal-dialog">
                <form class="ui form equal width" name="fregisterform" id="fregisterform" autocomplete="off">
                    <div class="uk-modal-header">
                        <h2 class="uk-modal-title"><?=lang('common.signup_info')?></h2>
                    </div>
                    <button class="uk-button uk-modal-close-default uk-icon uk-close" uk-close></button>
                    <div class="uk-modal-body">
                        <div class="fields">
                            <div class="twelve wide field">
                                <label><?=lang('common.id_user')?> </label>
                                <input type="text" name="userid" id="userid" placeholder="<?=lang('common.4to16')?>, <?=lang('common.english')?> <?=lang('common.or')?> <?=lang('common.number')?>" minlength="4" maxlength="16" />
                                <input type="text" name="ip" class="ip_addr" hidden/>
                            </div>
                            <div class="two wide field">
                                <label>&nbsp;</label>
                                <button class="ui teal button" type="button" onclick="checkDupUserid();"><?=lang('common.duplicate_check')?></button>
                            </div>
                        </div>

                        <div class="two fields">
                            <div class="field">
                                <label><?=lang('common.password')?> </label>
                                <input type="password" name="passwd" id="passwd" placeholder="<?=lang('common.8to20')?>, <?=lang('common.special_chars')?>" />
                            </div>
                            <div class="field">
                                <label><?=lang('common.confirm_password')?> </label>
                                <input type="password" name="passwd_re" id="passwd_re" placeholder="confirm password" />
                            </div>
                        </div>

                        <div class="fields">
                            <div class="twelve wide field">
                                <label><?=lang('common.nickname')?> </label>
                                <input type="text" name="nickname" id="nickname" placeholder="<?=lang('common.3to20')?>" minlength="3" maxlength="20" />
                            </div>
                            <div class="two wide field">
                                <label>&nbsp;</label>
                                <button class="ui teal button" type="button" onclick="checkDupNickname();"><?=lang('common.duplicate_check')?></button>
                            </div>
                        </div>

                        <div class="two fields">
                            <div class="field required">
                                <label><?=lang('common.withdrawal_bank')?> </label>
                                <select name="bank_name" id="bank_name">
                                    <option value=""><?=lang('common.select_bank')?></option>
                                    <option value="국민은행">국민은행</option>
                                    <option value="농협">농협</option>
                                    <option value="우리은행">우리은행</option>
                                    <option value="신한은행">신한은행</option>
                                    <option value="하나은행">하나은행</option>
                                    <option value="외환은행">외환은행</option>
                                    <option value="기업은행">기업은행</option>
                                    <option value="제일은행">제일은행</option>
                                    <option value="부산은행">부산은행</option>
                                    <option value="제주은행">제주은행</option>
                                    <option value="경남은행">경남은행</option>
                                    <option value="광주은행">광주은행</option>
                                    <option value="전북은행">전북은행</option>
                                    <option value="대구은행">대구은행</option>
                                    <option value="시티은행">시티은행</option>
                                    <option value="우체국">우체국</option>
                                    <option value="상호저축은행">상호저축은행</option>
                                    <option value="수협">수협</option>
                                    <option value="산업은행">산업은행</option>
                                    <option value="신협중앙회">신협중앙회</option>
                                    <option value="새마을금고">새마을금고</option>
                                    <option value="메리츠증권">메리츠증권</option>
                                    <option value="대우증권">대우증권</option>
                                    <option value="동양종금">동양종금</option>
                                    <option value="삼성생명">삼성생명</option>
                                    <option value="미래에셋">미래에셋</option>
                                    <option value="현대증권">현대증권</option>
                                    <option value="삼성증권">삼성증권</option>
                                    <option value="한국투자증권">한국투자증권</option>
                                    <option value="우리투자증권">우리투자증권</option>
                                    <option value="하이투자증권">하이투자증권</option>
                                    <option value="HMC투자증권">HMC투자증권</option>
                                    <option value="대신증권">대신증권</option>
                                    <option value="한화증권">한화증권</option>
                                    <option value="신한금융투자">신한금융투자</option>
                                    <option value="카카오뱅크">카카오뱅크</option>
                                    <option value="K뱅크">K뱅크</option>
                                    <option value="유안타증권">유안타증권</option>
                                </select>
                            </div>
                            <div class="field required">
                                <label><?=lang('common.withdrawal_owner')?></label>
                                <input type="text" id="bank_owner" name="bank_owner" placeholder="Bank Owner Name" />
                            </div>
                        </div>

                        <div class="two fields">
                            <div class="field required">
                                <label><?=lang('common.withdrawal_number')?></label>
                                <input type="text" id="bank_account" name="bank_account" value="" size="10" maxlength="30" pattern="[0-9\-]+" />
                            </div>
                            <div class="field required">
                                <label><?=lang('common.withdrawal_password')?></label>
                                <input type="text" id="refund_password" name="refund_password" value="" />
                            </div>
                        </div>
                        
                        <div class="field">
                            <label><?=lang('common.recommender')?> </label>
                            <input type="text" name="agent_id" id="agent_id" class="frm_input required" readonly="readonly" />
                        </div>

                        <div class="field">
                            <label><?=lang('common.phone_number')?></label>
                            <input type="text" id="phone" name="phone" placeholder="000-0000-0000" />
                        </div>
                    </div>
                    <div class="uk-modal-footer">
                        <button class="ui primary button" type="submit"><?=lang('common.sign_up')?></button>
                        <div class="ui uk-modal-close button" onclick='document.getElementById("agentCheckForm").reset();document.getElementById("fregisterform").reset();'><?=lang('common.cancel')?></div>
                    </div>
                </form>
            </div>
        </div>

        <div id="vue_modal">
            <div id="request_charge" uk-modal class="uk-modal">
                <div class="uk-modal-dialog">
                    <form name="chargeForm" id="chargeForm" class="ui form equal width">
                        <div class="uk-modal-header"><h3 class="uk-modal-title"><i class="ui cloud download icon"></i> <?=lang('common.deposit_request')?></h3></div>
                        <button class="uk-button uk-modal-close-default uk-icon uk-close" uk-close></button>
                        <div class="uk-modal-body">
                            <div class="field required">
                                <label><?=lang('common.deposit_amount_in')?></label>
                                <div class="ui input"><input type="number" name="cash" id="cash" placeholder="<?=lang('common.request_deposit_msg')?>" step="10000" /></div>
                                <div style="padding-top: 5px; text-align:right;">
                                    <button type="button" onclick="setMoneyField('cash',10000)" class="ui inverted blue mini button"><?=lang('common.10_thousands')?></button> <button type="button" onclick="setMoneyField('cash',50000)" class="ui inverted blue mini button"><?=lang('common.50_thousands')?></button>
                                    <button type="button" onclick="setMoneyField('cash',100000)" class="ui inverted blue mini button"><?=lang('common.100_thousands')?></button> <button type="button" onclick="setMoneyField('cash',500000)" class="ui inverted blue mini button"><?=lang('common.500_thousands')?></button>
                                    <button type="button" onclick="setMoneyField('cash',1000000)" class="ui inverted blue mini button"><?=lang('common.1_million')?></button> <button type="button" onclick="setMoneyField('cash',0)" class="ui inverted blue mini button"><?=lang('common.reenter')?></button>
                                </div>
                            </div>
                            <div class="field required">
                                <label><?=lang('common.depositor')?></label>
                                <div class="ui input">
                                    <input type="text" name="req_name_replaced" v-model="myInfo.user_bank_own" placeholder="<?=lang('common.depositor')?>" readonly="readonly" /> 
                                </div>
                            </div>
                        </div>
                        <div class="uk-modal-footer">
                            <div class="ui primary submit button"><?=lang('common.deposit_request_to')?></div>
                            <div class="ui uk-modal-close button"><?=lang('common.cancel')?></div>
                        </div>
                    </form>
                    <button class="uk-button uk-modal-close-default uk-icon uk-close" uk-close></button>
                </div>
            </div>
            <div id="request_exchange" uk-modal class="uk-modal">
                <div class="uk-modal-dialog">
                    <form name="exchangeForm" id="exchangeForm" class="ui form equal width">
                        <div class="uk-modal-header"><h3 class="uk-modal-title"><i class="ui cloud upload icon"></i> <?=lang('common.withdrawal_request')?></h3></div>
                        <button class="uk-button uk-modal-close-default uk-icon uk-close" uk-close></button>
                        <div class="uk-modal-body">
                            <div class="inline field">
                                <label><?=lang('common.current_money')?></label>
                                <div class="ui teal label"><span class="_has_cash">{{myInfo.user_money}}</span> <?=lang('common.won')?></div>
                            </div>

                            <div class="inline field required">
                                <label><?=lang('common.withdrawal_amount_in')?></label>
                                <div class="ui input"><input type="number" name="cash" id="cash_out" placeholder="<?=lang('common.request_withdrawal_msg')?>" step="10000" /></div>
                                <div style="padding-top: 5px; text-align:right;">
                                    <button type="button" onclick="setMoneyField('cash_out',10000)" class="ui inverted blue mini button"><?=lang('common.10_thousands')?></button> <button type="button" onclick="setMoneyField('cash_out',50000)" class="ui inverted blue mini button"><?=lang('common.50_thousands')?></button>
                                    <button type="button" onclick="setMoneyField('cash_out',100000)" class="ui inverted blue mini button"><?=lang('common.100_thousands')?></button> <button type="button" onclick="setMoneyField('cash_out',500000)" class="ui inverted blue mini button"><?=lang('common.500_thousands')?></button>
                                    <button type="button" onclick="setMoneyField('cash_out',1000000)" class="ui inverted blue mini button"><?=lang('common.1_million')?></button> <button type="button" onclick="setMoneyField('cash_out',0)" class="ui inverted blue mini button"><?=lang('common.reenter')?></button>
                                </div>
                            </div>
                            <h4 class="ui dividing teal header"><?=lang('common.withdrawal_information')?></h4>
                            <div class="inline field">
                                <label style="min-width:80px; margin-right:0px;"><?=lang('common.account_owner')?></label>
                                <div class="ui input"><input type="text" readonly="readonly" v-model="myInfo.user_bank_own" /> </div>
                            </div>
                            <div class="inline field">
                                <label style="min-width:80px; margin-right:0px;"><?=lang('common.bank_name')?></label>
                                <div class="ui input"><input type="text" readonly="readonly" v-model="myInfo.user_bank_name" /> </div>
                            </div>
                            <div class="inline field">
                                <label style="min-width:80px; margin-right:0px;"><?=lang('common.account_number')?></label>
                                <div class="ui input"><input type="text" readonly="readonly" v-model="myInfo.user_bank_num" /> </div>
                            </div>
                            
                            <div class="inline field">
                                <label style="min-width:80px; margin-right:0px;"><?=lang('common.withdrawal_pwd')?></label>
                                <div class="ui input"><input type="text" name="bank_passwd" id="bank_passwd" /></div>
                            </div>
                        </div>
                        <div class="uk-modal-footer">
                            <div class="ui primary submit button"><?=lang('common.withdrawal_request_to')?></div>
                            <div class="ui uk-modal-close button"><?=lang('common.cancel')?></div>
                        </div>
                    </form>
                    <button class="uk-button uk-modal-close-default uk-icon uk-close" uk-close></button>
                </div>
            </div>
            <div id="change_pwd" uk-modal class="uk-modal">
                <div class="uk-modal-dialog">
                    <form name="chgpwdForm" id="chgpwdForm" class="ui form equal width">
                        <div class="uk-modal-header"><h3 class="uk-modal-title"><?=lang('common.password_change')?></h3></div>
                        <button class="uk-button uk-modal-close-default uk-icon uk-close" uk-close></button>
                        <div class="uk-modal-body">
                            <div class="field required">
                                <label><?=lang('common.password_current')?></label>
                                <div class="ui input">
                                    <input type="text" name="pwd_old" id="pwd_old" placeholder="<?=lang('common.password_current')?>" /> 
                                </div>
                            </div>
                            <div class="field required">
                                <label><?=lang('common.password_new')?></label>
                                <div class="ui input">
                                    <input type="text" name="pwd_new" id="pwd_new" placeholder="<?=lang('common.password_new')?>" /> 
                                </div>
                            </div>
                        </div>
                        <div class="uk-modal-footer">
                            <div class="ui primary submit button"><?=lang('common.change')?></div>
                            <div class="ui uk-modal-close button"><?=lang('common.cancel')?></div>
                        </div>
                    </form>
                    <button class="uk-button uk-modal-close-default uk-icon uk-close" uk-close></button>
                </div>
            </div>
        </div>
        
        <script>
            setLogCookie('lang', '<?=$lang?>', 30);
            function SLB_POPUP(page, tab) {
                if(!check_login()){
                    return;
                }
                if (typeof tab == "undefined") tab = "";
                UIkit.modal("#request_charge").hide();
                UIkit.modal("#request_exchange").hide();

                SLB(page + "?tab=" + tab, {
                    width: "ten wide column",
                    caption: "<i class='user icon'></i><?=$user_id?> (<?=$user_name?>)",
                });
            }

            // validate rule check
            var validationLoginRules = {
                userid: {
                    identifier: "userid",
                    rules: [
                        {
                            type: "empty",
                            prompt: langMessage.id_input,
                        },
                        // {
                        //     type: "minLength[2]",
                        // },
                        // {
                        //     type: "maxLength[16]",
                        // },
                    ],
                },
                passwd: {
                    identifier: "passwd",
                    rules: [
                        {
                            type: "empty",
                            prompt: langMessage.password_input
                        },
                    ],
                },
                captchacode: {
                    identifier: "captchacode",
                    rules: [
                        {
                            type: "empty",
                            prompt: langMessage.security_character_input
                        },
                    ],
                },
            };
            // $("#formLogin").form({
            //     fields: validationLoginRules,
            //     inline: true,
            //     on: "submit",
            //     onSuccess: function (event) {
            //         return true;
            //     },
            // });

            // $("#formLogin").ajaxForm({
            //     dataType: "json",
            //     url: FURL + "/api/login",
            //     type: "POST",
            //     data: $(this).serialize(),
            //     beforeSubmit: function () {
            //         //return $('#formLogin').valid();
            //     },
            //     success: function (response) {
            //         if (response.status == 'success') {
            //             setLogCookie('logged', 'yes', 0);
            //             location.reload();
            //             // window.location.href = "/home";
            //         } else if (response.status == 'fail') {
            //             if(response.code == 11){  //보안코드
            //                 $("#loginModal input[name=captchacode]").val('');
            //             } else {
            //                 $("#loginModal input[name=userid]").val('');
            //                 $("#loginModal input[name=passwd]").val('');
            //             }
            //             showAlert(response.msg, 0);
            //         }

            //     },
            //     error: function(request, status, error) {
            //         // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            //     }
            // });
            function onEnter()
            {
                if( window.event.keyCode == 13 ) login();
            }

            function login(){
                let user_id = $("#loginModal #userid").val();
                let user_pw = $("#loginModal #passwd").val();
                let captchacode = $("#loginModal #captchacode").val();
                let captchasrc = $("#loginModal #captchasrc").val();
                let ip = $("#loginModal #ip_addr").val();

                if (user_id == "") {
                    showAlert(langMessage.id_input);
                    return;
                }

                if (user_pw == "") {
                    showAlert(langMessage.password_input);
                    return;
                }

                if (captchacode == "") {
                    showAlert(langMessage.security_character_input, 0);
                    return;
                }

                var data = {
                    'userid': user_id,
                    'passwd': user_pw,
                    'captchacode': captchacode,
                    'captchasrc': captchasrc,
                    'ip':ip,
                };

                $.ajax({
                    type: "POST",
                    url: FURL + "/api/login",
                    dataType: "json",
                    data:  data,
                    success: function (response) {
                        if (response.status == "success") {
                            setLogCookie('logged', 'yes', 0);
                            location.reload();
                        }  else if (response.status == 'fail') {
                            if(response.code == 11){  //보안코드
                                $("#loginModal input[name=captchacode]").val('');
                            } else {
                                $("#loginModal input[name=userid]").val('');
                                $("#loginModal input[name=passwd]").val('');
                            }
                            showAlert(response.msg, 0);
                        }
                    },
                    error: function(request, status, error) {
                        // confirmAlert(langMessage.administrator_ask+"\n" + request.status, 'reloadPage()');
                    }
                });
            }
            var validationRegRules = {
                userid: {
                    identifier: "userid",
                    rules: [
                        {
                            type: "empty",
                            prompt: langMessage.id_input,
                        },
                        {
                            type: "minLength[4]",
                            prompt: langMessage.id_input_4,
                        },
                        {
                            type: "maxLength[16]",
                            prompt: langMessage.id_input_16,
                        },
                    ],
                },
                passwd: {
                    identifier: "passwd",
                    rules: [
                        {
                            type: "empty",
                            prompt: langMessage.password_input
                        },
                        {
                            type: "minLength[4]",
                            prompt: langMessage.password_input_4,
                        },
                    ],
                },
                passwd_re: {
                    identifier: "passwd_re",
                    rules: [
                        {
                            type: "empty",
                            prompt: langMessage.password_verify,
                        },
                        // {
                        //     type: "match[passwd]",
                        //     prompt: langMessage.password_verify_c,
                        // },
                    ],
                },
                name: {
                    identifier: "name",
                    rules: [
                        {
                            type: "empty",
                        },
                    ],
                },
                nickname: {
                    identifier: "nickname",
                    rules: [
                        {
                            type: "empty",
                            prompt: langMessage.nickname_input,
                        },
                    ],
                },
                bank_name: {
                    identifier: "bank_name",
                    rules: [
                        {
                            type: "empty",
                            prompt: langMessage.withdrawal_bank_select,
                        },
                    ],
                },
                bank_owner: {
                    identifier: "bank_owner",
                    rules: [
                        {
                            type: "empty",
                            prompt: langMessage.withdrawal_owner_input,
                        },
                    ],
                },
                bank_account: {
                    identifier: "bank_account",
                    rules: [
                        {
                            type: "empty",
                            prompt: langMessage.withdrawal_number_input,
                        },
                    ],
                },
                refund_password: {
                    identifier: "refund_password",
                    rules: [
                        {
                            type: "empty",
                            prompt: langMessage.withdrawal_password_input,
                        },
                    ],
                },
                agent_id: {
                    identifier: "agent_id",
                    rules: [
                        {
                            type: "empty",
                        },
                    ],
                },
                phone: {
                    identifier: "phone",
                    rules: [
                        {
                            type: "empty",
                            prompt: langMessage.phone_number_input,
                        },
                    ],
                },
            };
            $("#fregisterform").form({
                fields: validationRegRules,
                inline: true,
                on: "submit",
                onSuccess: function (event) {
                    let passwd = $("#fregisterform #passwd").val();
                    let passwd_re = $("#fregisterform #passwd_re").val();
                    if(passwd != passwd_re){
                        showAlert(langMessage.password_verify_c);
                        return false;
                    }
                    return true;
                },
            });
            $("#fregisterform").ajaxForm({
                dataType: "json",
                type: "POST",
                url: FURL + "/api/register",
                data: $(this).serialize(),
                beforeSubmit: function () {
                    return $('#fregisterform').valid();
                },
                success: function (response) {
                    if (response.status == "success") {
                        showAlert(langMessage.signup_complete+"\n"+langMessage.signup_permit+"\n"+langMessage.thanks);
                        setTimeout(() => {
                            location.reload();
                        }, 5000);
                        // showLoginModal();
                    } else {
                        showAlert(response.msg, 0);
                    }
                },
            });
            $("#agentCheckForm").form({
                fields: validationRegRules,
                inline: true,
                on: "submit",
                onSuccess: function (event) {
                    return true;
                },
            });
            $("#agentCheckForm").ajaxForm({
                dataType: "json",
                type: "POST",
                url: FURL + "/api/check_proposer",
                data: $(this).serialize(),
                beforeSubmit: function () {},
                success: function (response) {
                    if (response.status == "success") {
                        UIkit.modal("#registModal").show();
                        $("#fregisterform #agent_id").val($("#recommender_id").val());
                    } else {
                        showAlert(response.msg, 0);
                    }
                },
            });


            function eventControl() {
                return true;
            }

            function onWindowScroll(){
                if ($(window).scrollTop() > 0) {
                    $(".MainMenu-open-wrapper").addClass("js-sticky");
                    $(".MainMenu-open-wrapper .MainMenu-LogoSlogan-mobile").show();
                    $(".MainMenu-open-wrapper .star-logo").hide();
                } else {
                    <?php if($_ENV['app.name'] == APP_HERMES) :?>
                        $(".MainMenu-open-wrapper").addClass("js-sticky");
                        $(".MainMenu-open-wrapper .MainMenu-LogoSlogan-mobile").show();
                    <?php elseif($_ENV['app.home'] > 0) :?>
                        $(".MainMenu-open-wrapper").addClass("js-sticky");
                    <?php else :?>
                        $(".MainMenu-open-wrapper").removeClass("js-sticky");
                        $(".MainMenu-open-wrapper .MainMenu-LogoSlogan-mobile").hide();
                    <?php endif ?>
                    $(".MainMenu-open-wrapper .star-logo").show();
                }
            }

            $(document).ready(function () {
                document.onselectstart = eventControl;
                document.oncontextmenu = eventControl;

                onWindowScroll();
                $(window).scroll(function () {
                    onWindowScroll();
                });

                // LoadUserHasInfo();
                $(".dropdown").dropdown({
                    action: "select",
                });

                $.getJSON("https://ipv4.seeip.org/jsonip",
                    function(json) {
                        // console.log("ip2="+json.ip);
                        if(json.ip !== undefined && json.ip.length > 0){
                            $(".ip_addr").val(json.ip);
                            console.log("seeip="+json.ip);
                        }
                    }
                );

                $.get("https://v4.ident.me/",
                    function(data) {
                        // console.log("ip1="+json.ip);
                        if(data.length > 0){
                            $(".ip_addr").val(data.trim());
                            console.log("ident="+data);
                        }
                    }
                );

                $("#slots .openGameBtn").click(function () {
                        if(!checkUnread())
                            return;
                        let onoff = $(this).data("onoff");
                        let game_id = $(this).data("cid");
                        let message = langMessage.inspection; 
                        if (onoff == "on") {
                            openSlotGame(game_id, $(this).data("cname"));
                        } else {
                            showAlert(message, 2);
                        }
                });

                $("#casinos .openGameBtn, #live-casino .playBtn").click(function () {
                    if(!checkUnread())
                        return;
                    let onoff = $(this).data("onoff");
                    if (onoff == "on") {
                        openCasinoGame($(this).data("cid"), $(this).data("gameid"));
                    } else {
                        showAlert(langMessage.inspection, 2);
                    }
                });
                $("#holdem .openGameBtn, #holdem .playBtn").click(function () {
                    if(!check_login()){
                        return;
                    }
                    if(!checkUnread())
                        return;
                    let onoff = $(this).data("onoff");
                    if (onoff == "on") {
                        window.open(FURL + "/holdem", "games", "width=1200, height=800, left=100, top=50");
                    } else {
                        showAlert(langMessage.inspection, 2);
                    }
                });
                $("#auto .openGameBtn, #auto .playBtn").click(function () {
                    if(!check_login()){
                        return;
                    }
                    let name = $(this).data("name");
                    let path = $(this).data("path");
                    let act = $(this).data("act");

                    if(act == 0){
                        showAlert(langMessage.customer_ask, 3);
                    }
                    else if(name.length > 0 && path.length > 0){
                        if(confirm("'" + name + "'을 다운하시겠습니까?")){
                            window.open(FURL + "/" + path);
                        }
                    }
                });

                $("#mini .openGameBtn").click(function () {
                    if(!check_login()){
                        return;
                    }
                    if(!checkUnread())
                        return;
                    let onoff = $(this).data("onoff");
                    if (onoff == "on") {
                        window.open(FURL + "/mini?gm="+$(this).data("cid"), "games", "width=1200, height=800, left=100, top=50");
                    } else {
                        showAlert(langMessage.updating, 2);
                    }
                });

                // validate rule check
                var validationChgRules = {
                    cash: {
                        identifier: "cash",
                        rules: [
                            {
                                type: "empty",
                                prompt: langMessage.request_amount_input,
                            },
                            {
                                type: "minLength[5]",
                                prompt: langMessage.request_amount_10th,
                            },
                        ],
                    },
                    point: {
                        identifier: "point",
                        rules: [
                            {
                                type: "empty",
                                prompt: "요청하실 롤링금을 숫자로 입력해주세요",
                            },
                            {
                                type: "minLength[5]",
                                prompt: langMessage.request_amount_10th,
                            },
                        ],
                    },
                    req_name: {
                        identifier: "req_name",
                        rules: [
                            {
                                type: "empty",
                                prompt: "입금하실분의 명칭을 입력해주세요",
                            },
                        ],
                    },
                    bank_passwd: {
                        identifier: "bank_passwd",
                        rules: [
                            {
                                type: "empty",
                                prompt: langMessage.withdrawal_password_input,
                            },
                        ],
                    },
                    pwd_old: {
                        identifier: "pwd_old",
                        rules: [
                            {
                                type: "empty",
                                prompt: langMessage.password_current_input,
                            }
                        ],
                    },
                    pwd_new: {
                        identifier: "pwd_new",
                        rules: [
                            {
                                type: "empty",
                                prompt: langMessage.password_new_input,
                            },
                            {
                                type: "minLength[3]",
                                prompt: "최소 3글자 이상 입력해주세요",
                            },
                        ],
                    },
                };

                $("#chargeForm").form({
                    fields: validationChgRules,
                    inline: true,
                    on: "submit",
                    onSuccess: function (event) {
                        return true;
                    },
                });

                $("#chargeForm").ajaxForm({
                    dataType: "json",
                    type: "POST",
                    url: FURL + "/api/register_charge",
                    data: $(this).serialize(),
                    beforeSubmit: function () {
                        return $("#chargeForm").valid();
                    },
                    success: function (response) {
                        // console.log(response);
                        if (response.status == "success") {
                            showAlert(langMessage.deposit_success);
                            UIkit.modal("#request_charge").hide();
                        } else if (response.status == "fail") {
                            showAlert(response.msg, 0);
                        } else if (response.status == "logout") {
                            reloadPage();
                        }
                    },
                    error: function(request, status, error) {
                        // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                    }
                });

                $("#exchangeForm").form({
                    fields: validationChgRules,
                    inline: true,
                    on: "submit",
                    onSuccess: function (event) {
                        return true;
                    },
                });

                $("#exchangeForm").ajaxForm({
                    dataType: "json",
                    type: "POST",
                    url: FURL + "/api/register_exchange",
                    data: $(this).serialize(),
                    beforeSubmit: function () {
                        return $("#exchangeForm").valid();
                    },
                    success: function (response) {
                        // console.log(response);
                        if (response.status == "success") {
                            showAlert(langMessage.withdrawal_success);
                            session_check();
                            UIkit.modal("#request_exchange").hide();
                        } else if (response.status == "fail") {
                            showAlert(response.msg, 0);
                        } else if (response.status == "logout") {
                            reloadPage();
                        }
                    },
                    error: function(request, status, error) {
                        // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                    }
                });

                $("#chgpwdForm").form({
                    fields: validationChgRules,
                    inline: true,
                    on: "submit",
                    onSuccess: function (event) {
                        return true;
                    },
                });
                //비밀번호 변경요청
                $("#chgpwdForm").ajaxForm({
                    dataType: "json",
                    type: "POST",
                    url: FURL + "/api/change_pass",
                    data: $(this).serialize(),
                    beforeSubmit: function () {
                        return $("#chgpwdForm").valid();
                    },
                    success: function (response) {
                        // console.log(response);
                        if (response.status == "success") {
                            showAlert(langMessage.password_change_ok);
                        } else if (response.status == "fail") {
                            showAlert(response.msg, 0);
                        } else if (response.status == "logout") {
                            reloadPage();
                        }
                    },
                    error: function(request, status, error) {
                        // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                    }
                });
            });

            function requestAccount() {
                if(!check_login()){
                    return;
                }

                let title = "["+langMessage.ask_quick+"] "+langMessage.deposit_account_request;
                let content = ""+langMessage.ask_quick+" : "+langMessage.deposit_account_request;
                if (confirm(langMessage.deposit_account_ask) == false) return false;

                $.post(
                    "/api/request_account3",
                    {
                        title: title,
                        content: content,
                    },
                    function (response) {
                        if (response.status == "success") {
                            showAlert(langMessage.deposit_account_answer+"\n\n"+langMessage.deposit_account_check, 3);
                        } else {
                            showAlert(response.message, 0);
                        }
                    },
                    "json"
                );
            }

            function showAgentCheckModal() {
                $("#agentCheckModal input[name=recommender_id]").val('');
                SLB(); 
                UIkit.modal("#agentCheckModal").show();
            }
            
            function showLoginModal() {
                $("#loginModal input[name=userid]").val('');
                $("#loginModal input[name=passwd]").val('');
                SLB(); 
                if($(".modal-container").length > 0)
                    $("#loginModal").addClass("show");
                else
                    UIkit.modal("#loginModal").show();
            }

            function hideLoginModal() {
                $("#loginModal").removeClass("show");
            }
            function requestCharge() {
                if(!check_login()){
                    return;
                }
                SLB(); 
                $("#cash").val('');
                UIkit.modal("#request_charge").show();
            }

            function requestWithdraw() {
                if(!check_login()){
                    return;
                }
                SLB(); 
                objMain.getMyInfo();
                $("#cash_out").val('');
                $("#bank_passwd").val('');
                UIkit.modal("#request_exchange").show();
            }

            function changePwd() {
                if(!check_login()){
                    return;
                }
                UIkit.modal("#change_pwd").show();
            }

            function changePoint() {

                <?php if($_ENV['app.home'] > 0) :?>
                    SLB_POPUP(FURL + '/mypage', 'my_point');
                <?php else: ?>
                    if($("#_btn_user_point ._has_point").text().length < 2){
                        return;
                    }

                    UIkit.modal.confirm(langMessage.change_point_request, {labels: {'ok': langMessage.ok, 'cancel': langMessage.cancel}}).then(
                        function () {
                            $.ajax({
                                dataType: "json",
                                type: "POST",
                                url: FURL + "/api/change_point",
                                success: function (response) {
                                    if (response.status == "success") {
                                        showAlert(langMessage.change_point_result);
                                        session_check();
                                    } else {
                                        // showAlert(response.msg, 0);
                                    }
                                },
                            });
                        },
                        function () {
                            //취소
                        }
                    );
                <?php endif ?>

            }

            var objMain = new Vue({
                el: "#vue_modal",
                data: {
                    myInfo: [],
                },
                methods: {
                    getMyInfo: function () {
                        $.get(
                            FURL + "/api/myinfo",
                            function (response) {
                                // console.log(response);
                                if (response.status == "success") {
                                    objMain.myInfo = response.data;
                                }  else if (response.status == "logout") {
                                    // console.log("myinfo logout");
                                }
                            },
                            "json"
                        );
                    },
                },
                mounted: function () {
                    this.getMyInfo();
                },
                filters: {
                    number_format: function (value) {
                        return new Intl.NumberFormat("ko-KR", {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0,
                        }).format(value);
                    },
                    number_format2: function (value) {
                        return new Intl.NumberFormat("ko-KR", {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2,
                        }).format(value);
                    },
                },
            });
        </script>


    <?php if( count($boards) > 4 ) :?>

        <div id="layer1" class="pop_layer" style="display:none;">
            <div class="pop_container">
                <div class="pop_top">
                    <p class="tit"><?=$boards[4]->notice_title?></p>
                </div>
                <div class="pop_con" style="background:<?=$boards[4]->notice_color?>">
                    <!-- white-space: pre-wrap;  -->
                    <div style="text-align:center; font-size:13px; line-height:30px;"><?=$boards[4]->notice_content?>
                    </div>
                </div>
                <button type="button" class="pop_close"><span class="ir_wa"></span></button>
            </div>
            <div class="btn_wrap">
                <button type="button" class="btn" id="btn_close_oneday_layer1"><?=lang('common.close_day')?></button>
                <button type="button" class="btn btn_red pop_close"><?=lang('common.close')?></button>
            </div>
        </div>
    <?php endif ?>
        
    <?php if( count($boards) > 5 ) :?>

        <div id="layer3" class="pop_layer" style="display:none;">
            <div class="pop_container">
                <div class="pop_top">
                    <p class="tit"><?=$boards[5]->notice_title?></p>
                </div>
                <div class="pop_con" style="background:<?=$boards[5]->notice_color?>">
                    <div style="text-align:center; font-size:13px; line-height:30px;"><?=$boards[5]->notice_content?>
                    </div>
                </div>
                <button type="button" class="pop_close"><span class="ir_wa"></span></button>
            </div>
            <div class="btn_wrap">
                <button type="button" class="btn" id="btn_close_oneday_layer3"><?=lang('common.close_day')?></button>
                <button type="button" class="btn btn_red pop_close"><?=lang('common.close')?></button>
            </div>
        </div>
    <?php endif ?>

	<?php if( count($boards) > 0 ) :?>
        
        <div id="layer4" class="pop_layer" style="display:none;">
            <div class="pop_container">
                <div class="pop_top">
                    <p class="tit"><?=$boards[0]->notice_title?></p>
                </div>
                <div class="pop_con" style="background:<?=$boards[0]->notice_color?>">
                    <!-- white-space: pre-wrap;  -->
                    <div style="text-align:center; font-size:13px; line-height:30px;"><?=$boards[0]->notice_content?>
                    </div>
                </div>
                <button type="button" class="pop_close"><span class="ir_wa"></span></button>
            </div>
            <div class="btn_wrap">
                <button type="button" class="btn" id="btn_close_oneday_layer4"><?=lang('common.close_day')?></button>
                <button type="button" class="btn btn_red pop_close"><?=lang('common.close')?></button>
            </div>
        </div>

    <?php endif ?>

    
	<?php if( count($boards) > 1 ) :?>
        
        <div id="layer5" class="pop_layer" style="display:none;">
            <div class="pop_container">
                <div class="pop_top">
                    <p class="tit"><?=$boards[1]->notice_title?></p>
                </div>
                <div class="pop_con" style="background:<?=$boards[1]->notice_color?>">
                    <!-- white-space: pre-wrap;  -->
                    <div style="text-align:center; font-size:13px; line-height:30px;"><?=$boards[1]->notice_content?>
                    </div>
                </div>
                <button type="button" class="pop_close"><span class="ir_wa"></span></button>
            </div>
            <div class="btn_wrap">
                <button type="button" class="btn" id="btn_close_oneday_layer5"><?=lang('common.close_day')?></button>
                <button type="button" class="btn btn_red pop_close"><?=lang('common.close')?></button>
            </div>
        </div>

    <?php endif ?>

	<?php if( count($boards) > 2 ) :?>
        
        <div id="layer6" class="pop_layer" style="display:none;">
            <div class="pop_container">
                <div class="pop_top">
                    <p class="tit"><?=$boards[2]->notice_title?></p>
                </div>
                <div class="pop_con" style="background:<?=$boards[2]->notice_color?>">
                    <!-- white-space: pre-wrap;  -->
                    <div style="text-align:center; font-size:13px; line-height:30px;"><?=$boards[2]->notice_content?>
                    </div>
                </div>
                <button type="button" class="pop_close"><span class="ir_wa"></span></button>
            </div>
            <div class="btn_wrap">
                <button type="button" class="btn" id="btn_close_oneday_layer6"><?=lang('common.close_day')?></button>
                <button type="button" class="btn btn_red pop_close"><?=lang('common.close')?></button>
            </div>
        </div>

    <?php endif ?>

	<?php if( count($boards) > 3 ) :?>
        
        <div id="layer7" class="pop_layer" style="display:none;">
            <div class="pop_container">
                <div class="pop_top">
                    <p class="tit"><?=$boards[3]->notice_title?></p>
                </div>
                <div class="pop_con" style="background:<?=$boards[3]->notice_color?>">
                    <!-- white-space: pre-wrap;  -->
                    <div style="text-align:center; font-size:13px; line-height:30px;"><?=$boards[3]->notice_content?>
                    </div>
                </div>
                <button type="button" class="pop_close"><span class="ir_wa"></span></button>
            </div>
            <div class="btn_wrap">
                <button type="button" class="btn" id="btn_close_oneday_layer7"><?=lang('common.close_day')?></button>
                <button type="button" class="btn btn_red pop_close"><?=lang('common.close')?></button>
            </div>
        </div>

    <?php endif ?>
    </body>
</html>
