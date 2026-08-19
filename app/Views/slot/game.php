
<html><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
    <meta name="referrer" content="no-referrer">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="/assets/js/lib/jquery-3.3.1.min.js"></script>

  </head>

<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
    <body oncontextmenu="return false" ondragstart="return false" onselectstart="return false" class="theme01 slot_black" style="height: 100%; overflow: hidden; margin: 0px;">
<?php else : ?>
    <body class="theme01 slot_black"  style="height: 100%; overflow: hidden; margin: 0px;">
<?php endif ?>
        <!-- <iframe type="text/html" id="game_area" src="<?=$launch_url?>" 
            width="100%" height="100%" allow="autoplay; fullscreen" 
            style="width:100vw; height:100vh; border: none;" 
            referrerpolicy="no-referrer" frameborder="0" allowfullscreen="" webkitallowfullscreen="" 
            mozallowfullscreen="" oallowfullscreen="" msallowfullscreen=""></iframe> -->

        <iframe type="text/html" id="game_area" src="<?=$launch_url?>" 
            width="100%" height="100%" allow="autoplay; fullscreen;" style="border: none;" 
            referrerpolicy="no-referrer" frameborder="0" allowfullscreen="true" webkitallowfullscreen="true" 
            mozallowfullscreen="true" msallowfullscreen="true">
        </iframe>

    </body>
</html>