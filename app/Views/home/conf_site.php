<?= $this->extend('header') ?>
<?= $this->section('content') ?>
<script src="<?php echo site_furl('/assets/js/summernote-lite.js');?>"></script>
<script src="<?php echo site_furl('/assets/js/summernote-ko-KR.js');?>"></script>
<link rel="stylesheet" href="<?php echo siteFurl().'assets/css/summernote-lite.css';?>">

    <!--Sub Navbar-->
    <div class="sub-navbar" value="<?= $this->renderSection('confsite-active') ?>">
        <p><i class="glyphicon glyphicon-cog"></i> 본사설정</p>
        <a href="<?php echo siteFurl().'home/conf_site';?>" class="sub-navbar-a">본사설정</a>
        <?php if(!$pbg_deny || !$bpg_deny || !$eos5_deny || !$eos3_deny) :?>
            <!-- <a href="<?php echo siteFurl().'home/conf_betsite';?>" class="sub-navbar-a">보험설정</a> -->
        <?php endif ?>   
        <a href="<?php echo siteFurl().'home/conf_maintain';?>" class="sub-navbar-a">점검설정</a>
		<a href="<?php echo siteFurl().'home/conf_sound';?>" class="sub-navbar-a" >알람설정</a>
		<a href="<?php echo siteFurl().'home/conf_message';?>" class="sub-navbar-a" >쪽지매크로</a>
		<a href="<?php echo siteFurl().'home/conf_domain';?>" class="sub-navbar-a" >도메인설정</a>
		<!-- <a href="<?php echo siteFurl().'home/conf_clean';?>" class="sub-navbar-a" >디비정리</a> -->

        <?= $this->renderSection('confsite-navbar') ?>

    </div>

	<!--Site Setting-->
	<div class="confsite-site-panel">
        <?= $this->renderSection('confsite-content') ?>
	</div>
<!--main_navbar.php-->
</div>
    <?= $this->renderSection('confsite-script') ?>

<?= $this->endSection() ?>