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
    <link rel="stylesheet" href="/css/login/common.css?v=1">
    <link rel="stylesheet" href="/css/login/content.css?v=1">
    <link rel="stylesheet" href="/css/login/style.css?v=1">
    <link rel="stylesheet" href="/css/login/main.css?v=1">

    <script type="text/javascript" src="/js/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="/js/jquery-ui.js?v=1"></script>

    <title><?=$site_name?></title>
</head>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
<body oncontextmenu="return false" ondragstart="return false" onselectstart="return false">
<?php else : ?>
<body>
<?php endif ?>
    <div class="alert_wrap basic_alert" id="basic_alert" style="">
        <div class="alert_bot">
            <p class="question_ico" style="white-space:pre-line;" id="alert_content"></p>
            <div class="btn_wrap">
                <a onclick="location.href='javascript:okAlert()';" class="btn btn_red" id="basic_ok" style="cursor: pointer"><?=lang('common.ok')?></a>
                <a onclick="location.href='javascript:closeAlert()';" class="btn" style="cursor: pointer"><?=lang('common.cancel')?></a>
            </div>
        </div>
    </div>

    <div class="alert_wrap confirm_alert" id="confirm_alert" style="">
        <div class="alert_bot">
            <p class="question_ico" style="white-space:pre-line;" id="alert_content"></p>
            <div class="btn_wrap">
                <a onclick="location.href='javascript:closeAlert()';" class="btn" id="confirm_ok" style="cursor: pointer"><?=lang('common.ok')?></a>
            </div>
        </div>
    </div>

    <div class="login-box">
        <h2>IP <?=lang('common.login')?></h2>
        <form>
          <div class="user-box">
            <input type="text" name="" required="" id="user_id">
            <label><?=lang('common.id')?></label>
          </div>
          <div class="user-box">
            <input type="password" name="" required="" id="user_pw">
            <label><?=lang('common.password')?></label>
          </div>
          <div class="user-box">
            <input type="text" name="" required="" id="ip_addr">
            <label><?=lang('common.ip')?></label>
          </div>
          <div class="user-box" style="text-align:center;">
            <a href="javascript: void(0);" style="width:120px;" id="btnLogin">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <?=lang('common.login')?>
              </a>
          </div>
        </form>
      </div>

    <?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
        <script src="/js/loginip.js?v=2"></script>
    <?php else : ?>
        <script src="/js/loginip.js?t=<?=time()?>"></script>
    <?php endif ?>
</body>

</html>