<?php
$adminEnv = static function (array $keys, $default = '') {
	foreach ($keys as $key) {
		if (array_key_exists($key, $_ENV)) {
			return $_ENV[$key];
		}
	}
	return $default;
};

$adminSkin = strtolower(trim((string)$adminEnv(['admin.skin', 'admin.Skin', 'admin.sKin', 'ADMIN_SKIN'], 'default')));
if (!in_array($adminSkin, ['default', 'synadmin'], true)) {
	$adminSkin = 'default';
}

$adminSkinMode = strtolower(trim((string)$adminEnv(['admin.skin.mode', 'admin.sKin.mode', 'admin.Skin.mode', 'admin.Skin.Mode', 'ADMIN_SKIN_MODE'], 'dark')));
if ($adminSkinMode === 'white') {
	$adminSkinMode = 'light';
}
if ($adminSkinMode === 'black') {
	$adminSkinMode = 'dark';
}
if (!in_array($adminSkinMode, ['dark', 'light'], true)) {
	$adminSkinMode = 'dark';
}

$adminResponsive = in_array(strtolower(trim((string)($_ENV['admin.responsive'] ?? '0'))), ['1', 'true', 'yes', 'on'], true);
$adminUseModernSkin = $adminSkin === 'synadmin';
$adminResponsive = $adminResponsive || $adminUseModernSkin;

$adminBodyClasses = [];
if ($adminUseModernSkin) {
	$adminBodyClasses[] = 'admin-skin-synadmin';
	$adminBodyClasses[] = 'admin-skin-' . $adminSkinMode;
}
if ($adminResponsive) {
	$adminBodyClasses[] = 'admin-responsive';
}
$adminBodyClass = implode(' ', $adminBodyClasses);
?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<?php if($adminResponsive) :?>
		<meta content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=yes" name="viewport">
	<?php endif ?>
	<title><?=$site_name?> 관리자</title>
    
	<?php if(array_key_exists('app.logo', $_ENV)) :?> 
        <link rel="shortcut icon" href="<?php echo site_furl('/favicon_'.$_ENV['app.logo'].'.ico?v=1');?>">
    <?php else: ?>
        <link rel="shortcut icon" href="<?php echo site_furl('/favicon_am.ico?v=1');?>">
    <?php endif ?>
	<!-- Bootstrap 3.3.7 -->
  	<link rel="stylesheet" href="<?php echo site_furl('assets/bootstrap/css/bootstrap.min.css');?>">
	<style>
		:root {
			<?php if(array_key_exists('css.main-bg-color', $_ENV)) :?> 
				--main-bg-color: <?=$_ENV['css.main-bg-color']?>;
			<?php else: ?>
				--main-bg-color: white;
			<?php endif ?>
			
			<?php if(array_key_exists('css.bar-bg-color', $_ENV)) :?> 
				--bar-bg-color: <?=$_ENV['css.bar-bg-color']?>;
			<?php else: ?>
				--bar-bg-color: #dee1e6;
			<?php endif ?>

			<?php if(array_key_exists('css.menu-bg-color', $_ENV)) :?> 
				--menu-bg-color: <?=$_ENV['css.menu-bg-color']?>;
			<?php else: ?>
				--menu-bg-color: #dee1e6;
			<?php endif ?>
			
			<?php if(array_key_exists('css.dropdown-font-color', $_ENV)) :?> 
				--dropdown-font-color: <?=$_ENV['css.dropdown-font-color']?>;
			<?php else: ?>
				--dropdown-font-color: #0017b9;
			<?php endif ?>

			<?php if(array_key_exists('css.menu-font-color', $_ENV)) :?> 
				--menu-font-color: <?=$_ENV['css.menu-font-color']?>;
			<?php else: ?>
				--menu-font-color: black;
			<?php endif ?>

			<?php if(array_key_exists('css.scroll-bg-color', $_ENV)) :?> 
				--scroll-bg-color: <?=$_ENV['css.scroll-bg-color']?>;
			<?php else: ?>
				--scroll-bg-color: #4f5377;
			<?php endif ?>

			--bar-font-color: black;
			--span-font-color: #0090ff;
			--main-button-color: #ffda3d;
			<?php if(array_key_exists('css.table-th-color', $_ENV)) :?> 
				--table-th-color: <?=$_ENV['css.table-th-color']?>;
			<?php else: ?>
				--table-th-color: #333;
			<?php endif ?>
		}
	</style>
	<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
		<link rel="stylesheet" href="<?php echo site_furl('assets/css/main.style.css?v=28');?>">
    <?php else : ?>
		<link rel="stylesheet" href="<?php echo site_furl('assets/css/main.style.css?t='.time());?>">
    <?php endif ?>
	<?php if($adminUseModernSkin || $adminResponsive) :?>
		<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
			<link rel="stylesheet" href="<?php echo site_furl('assets/css/admin.skin.css?v=2');?>">
		<?php else : ?>
			<link rel="stylesheet" href="<?php echo site_furl('assets/css/admin.skin.css?t='.time());?>">
		<?php endif ?>
	<?php endif ?>

	<!-- JQuery 1.12.1 -->
	<link rel="stylesheet" href="<?php echo site_furl('assets/js/jquery-ui-1.12.1.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo site_furl('assets/js/jquery.datetimepicker.min.css'); ?>">
	<script src="<?php echo site_furl('assets/js/jquery-1.12.4.min.js'); ?>"></script>
	<script src="<?php echo site_furl('assets/js/jquery-ui-1.12.1.min.js'); ?>"></script>
	<script src="<?php echo site_furl('assets/js/jquery.datetimepicker.full.min.js'); ?>"></script>
	<script src="<?php echo site_furl('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
	<link rel="stylesheet" href="<?php echo site_furl('assets/js/sweet/sweetalert2.min.css'); ?>" />
	<script type="text/javascript" src="<?php echo site_furl('assets/js/sweet/sweetalert2.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_furl('assets/js/toaster.js?v=1'); ?>"></script>
	<script>
        const FURL = "<?=$_ENV['app.furl']?>" 
        var mLevelType = 0;
        <?php if(array_key_exists('app.level_type', $_ENV) && $_ENV['app.level_type'] > 0) :  ?>
            mLevelType = <?=$_ENV['app.level_type']?>;
        <?php endif ?>
    </script>

    <script src="<?php echo site_furl('assets/js/common.js?v=5');?>"></script>
	<script src="<?php echo site_furl('assets/js/worker.js?v=1');?>"></script>

	<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
        <script src="<?php echo site_furl('assets/js/main-script.js?v=1');?>"></script>
    <?php else : ?>
        <script src="<?php echo site_furl('assets/js/main-script.js?t='.time());?>"></script>
    <?php endif ?>
	<?php if($adminResponsive) :?>
		<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
			<script src="<?php echo site_furl('assets/js/admin.skin.js?v=1');?>"></script>
		<?php else : ?>
			<script src="<?php echo site_furl('assets/js/admin.skin.js?t='.time());?>"></script>
		<?php endif ?>
	<?php endif ?>

</head>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
<body class="<?=htmlspecialchars($adminBodyClass, ENT_QUOTES, 'UTF-8')?>" oncontextmenu="return false" ondragstart="return true" onselectstart="return true">
<?php else : ?>
<body class="<?=htmlspecialchars($adminBodyClass, ENT_QUOTES, 'UTF-8')?>">
<?php endif ?>
<div class="main-navbar-dropdown-container" id="main-navbar-dropdown-container-id" style="display: none;">
  <div class="main-navbar-dropdown-div"> 
  <button id="main-navbar-dropdown-logout-id"><span class="glyphicon glyphicon-log-out"></span>&nbsp&nbsp로그아웃</button>
  </div>
</div>
<?php if($adminResponsive) :?>
	<button type="button" class="admin-sidebar-toggle" id="admin-sidebar-toggle" aria-label="Toggle menu" aria-expanded="false">
		<span class="glyphicon glyphicon-menu-hamburger"></span>
	</button>
<?php endif ?>
<style>
		.swal2-popup.swal2-toast .swal2-title{
			font-size:1.5em;
		}
		.swal2-popup.swal2-toast .swal2-close{
			font-size:3.5em;
		}
		.user-table a.link-member, .bet-table a.link-member {
			color: blue;
			border: none;
			background-color: transparent;
			box-shadow: none;
			font-weight: lighter;
            cursor:pointer;
		}

		.user-table a.link-member:hover, .bet-table a.link-member:hover {
			text-decoration: underline;
			color: blue;
		}
				
		button.refresh_btn {
			border:0;
			padding:0;
			box-shadow:none;
			width: 18px;
			height: 18px;
			vertical-align: top;
			margin-left: 5px;
			background: url(<?php echo site_furl('/assets/image/refresh_btn.png');?>) no-repeat left top;
		}

		button.refresh_btn.refresh{
			background: url(<?php echo site_furl('/assets/image/refresh_btn.gif');?>) no-repeat left top;
			/* animation: rotate_img 1s linear infinite;transform-origin: 50% 50%; */
		}

        	
		button.recovery_btn {
			border:0;
			padding:0;
			box-shadow:none;
			width: 22px;
			height: 22px;
			vertical-align: top;
			margin-left: 5px;
			background: url(<?php echo site_furl('/assets/image/recovery_btn.png');?>) no-repeat left top;
		}

		button.refresh_btn:hover, button.recovery_btn:hover{
			border:1px;
			box-shadow: 1px 1px 1px 0px rgb(0 0 0 / 50%);
		}
        
		button.recovery_btn.refresh{
			animation: rotate_img 1s linear infinite;transform-origin: 50% 50%;
		}

        @keyframes rotate_img{
            100% {
                transform: rotate(-360deg);
            }
        }

	</style>
<?= $this->include('include/sidebar')?>
<?= $this->include('include/main_navbar')?>
<?php $this->renderSection('content') ?>
<?= $this->include('footer')?>
