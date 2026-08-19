<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title><?=$site_name?> 관리자</title>
    <?php if(array_key_exists('app.logo', $_ENV)) :?> 
        <link rel="shortcut icon" href="<?php echo site_furl('/favicon_'.$_ENV['app.logo'].'.ico?v=1');?>">
    <?php else: ?>
        <link rel="shortcut icon" href="<?php echo site_furl('/favicon_am.ico?v=1');?>">
    <?php endif ?>

	<!-- Bootstrap 3.3.7 -->
  	<link rel="stylesheet" href="<?php echo site_furl('assets/bootstrap/css/bootstrap.min.css');?>">
	<link rel="stylesheet" href="<?php echo site_furl('assets/css/pages.style.min.css');?>">
  	
  	<!-- JQuery 3.4.1 -->
	<script src="<?php echo site_furl('assets/js/jquery-3.4.1.min.js'); ?>"></script>
	<script src="<?php echo site_furl('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
	<script>const FURL = "<?=$_ENV['app.furl']?>" </script>
	<script src="<?php echo site_furl('assets/js/util.js?v=1'); ?>"></script>
</head>
<body>
	<style>
		.login-background-panel{
			background-image: url('<?php echo siteFurl();?>/assets/image/register-bg.jpg');
			background-position: 50%;
			background-repeat: no-repeat;
			background-size: cover;
			width: 100% !important;
			height: 100vh !important;
			display: table;
			position: absolute;
		}
	</style>
	<div class="login-background-panel">
		<div class="login-modal-container">
			<div class="login-modal-panel" id="login-modal-panel-id">
				<h4>Login</h4>
				<p>환영합니다, 당신의 계정으로 로그인 부탁드립니다.</p>
				<div>
					<input type = "text" class="login-id-input" id="login-user-input-id" placeholder="ID">
				</div>
				<div>
					<input type = "password" class="login-password-input" id="login-pwd-input-id" placeholder="Password">
				</div>
				<div>					
					<button class="login-ok-button" id="login-okbtn-id">로그인</button>
				</div>
			</div>
		</div>
	</div>
	<?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
		<script src="<?php echo site_furl('/assets/js/pages-script.js?t='.time());?>"></script>
	<?php else : ?>
		<script src="<?php echo site_furl('/assets/js/pages-script.js?v=1');?>"></script>
	<?php endif ?>
</body>
</html>