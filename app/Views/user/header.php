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
$adminBodyClasses[] = 'admin-popup-layout';
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

	<link rel="stylesheet" href="<?php echo site_furl('assets/css/main.style.css?v=28');?>">
	<?php if($adminUseModernSkin || $adminResponsive) :?>
		<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
			<link rel="stylesheet" href="<?php echo site_furl('assets/css/admin.skin.css?v=2');?>">
		<?php else : ?>
			<link rel="stylesheet" href="<?php echo site_furl('assets/css/admin.skin.css?t='.time());?>">
		<?php endif ?>
	<?php endif ?>

	<!-- JQuery 1.12.1 -->
	<link rel="stylesheet" href="<?php echo site_furl('assets/js/jquery-ui-1.12.1.min.css'); ?>">
	<script src="<?php echo site_furl('assets/js/jquery-1.12.4.min.js'); ?>"></script>
	<script src="<?php echo site_furl('assets/js/jquery-ui-1.12.1.min.js'); ?>"></script>
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

</head>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
<body class="<?=htmlspecialchars($adminBodyClass, ENT_QUOTES, 'UTF-8')?>" oncontextmenu="return false" ondragstart="return true" onselectstart="return true">
<?php else : ?>
<body class="<?=htmlspecialchars($adminBodyClass, ENT_QUOTES, 'UTF-8')?>">
<?php endif ?>

<style>
	
	.swal2-popup.swal2-toast .swal2-title{
		font-size:1.5em;
	}
	.swal2-popup.swal2-toast .swal2-close{
		font-size:3.5em;
	}
	.useredit-panel{
		min-height:660px;
	}
</style>
<div class="main-container" style="margin-left:0px; padding:0px; min-width:800px;" id="main-container-id">
<div class="loading" style="display: none;">
	<div class="load lds-ellipsis"></div>
</div>
<?php $this->renderSection('content') ?>
<?= $this->include('footer')?>
