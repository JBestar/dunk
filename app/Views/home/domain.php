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
        var langMessage = {
            cancel : '<?=lang('common.cancel')?>',
            ok : '<?=lang('common.ok')?>',
        };
    </script> 
    <style>
    </style>
</head>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
<body oncontextmenu="return false" ondragstart="return false" onselectstart="return false">
<?php else : ?>
<body>
<?php endif ?>
   
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
        
        body {
            background:url(/images/login/login_bg_clip.png?v=1) repeat ;
            overflow: hidden;
        }

        .users_border{
            position: absolute;
            top: 50%;
            left: 50%;
            z-index: 1;
            width: 600px;
            height: <?=$height?>px;
            margin-top: -<?=$height/2?>px;
            margin-left: -300px;
            border: 3px solid #fff8bd;
            border-radius: 0px;
            box-shadow: 10px 3px 10px #888;
            background:#000000;
        }

        .bodyHeadDesktop {
            text-align: center;
            color: #fff;
            display: block;
            padding-top: 30px;
        }
        .bodyHeadDesktop p {
            margin: 0;
            font-size: 24px;
            line-height:30px; 
            font-weight: 800;
        }
        .bodyHeadDesktop span {
            color: #f25a00;
        }
        .footerDesktop {
            display: block;
            padding-top: 30px;
            color: #fff;
            text-align: center;
            font-weight: 800;
        }
        .footerDesktop p {
            margin: 0;
            font-size: 20px;
            line-height: 30px; 
        }
        .footerDesktop a {
            color: #f15a00;
        }
        #id01 {
            padding-top: 30px;
            text-align:center;
        }
        #id01 p {
            width :400px;
            margin: 0 100px;
            font-size: 32px;
            text-align:center;
            margin-top: 5px;
            font-weight: 800;
            border-radius: 80px;
            padding: 20px 30px;
            margin-top: 10px;
            border: 1px solid #cdc7c7;
        }
        #id01 p a {
            color: gold!important;
        }
        #id01 a {
            cursor: pointer;
        }
        
        @media screen and (max-width: 680px){
            .users_border{
                width: 400px;
                margin-left: -200px;
            }
            #id01 p {
                width :300px;
                margin: 0 50px;
                font-size: 24px;
                margin-top: 5px;
            }
        }
    </style>

    <div id="wrap" class="users_wrap">
        <div class="users_border">
            <div class="bodyHeadDesktop">
                <p><?=$site_name?> 실시간 주소 현황</p>
                    <!-- <p><br></p><p>반드시 <span>캐시 삭제</span>를 해주시고 <span>새로고침</span>을 하셔야</p>
                <p>새 주소가 나타납니다</p> -->
            </div>
            <div id="id01">
                <?php foreach ($domains as $domain):?>
                    <p><a href="http://<?=$domain?>"><?=$domain?></a></p>
                <?php endforeach ?>
            </div>

            <div class="footerDesktop">
                <p>도메인 변경 시에도 항상 <a id="hangul02" href="http://<?=$check_domain?>"><?=$check_domain?></a> 확인하실 수 있습니다.</p>
            </div>
        </div>
    </div>

</body>

</html>