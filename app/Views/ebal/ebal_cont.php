<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	<!--Sub Navbar-->
	<div class = "sub-navbar" value="<?= $this->renderSection('ebal-active') ?>">
		<p><i class="glyphicon glyphicon-book"></i> <?=$game_title?></p>

      <?php if($game_id == GAME_AUTO_PRAG) :?>
            <a href="<?php echo site_furl('home/conf_pbal');?>" class="sub-navbar-a" >밸런스설정</a>
            <a href="<?php echo site_furl('home/conf_proom');?>" class="sub-navbar-a" >방설정</a>
            <?php if($evpress) :?>
                <a href="<?php echo site_furl('home/conf_ppress');?>" class="sub-navbar-a" >누르기설정</a>
                <a href="<?php echo site_furl('home/conf_ppresslog');?>" class="sub-navbar-a" >누르기내역</a>
            <?php endif ?>  
            <a href="<?php echo site_furl('bet/pbalhistory');?>" class="sub-navbar-a" >밸런스내역</a>
            <a href="<?php echo site_furl('bet/pbethistory');?>" class="sub-navbar-a" >배팅내역</a>
        <?php else :?>
            <a href="<?php echo site_furl('home/conf_ebal');?>" class="sub-navbar-a" >밸런스설정</a>
            <a href="<?php echo site_furl('home/conf_eroom');?>" class="sub-navbar-a" >방설정</a>
            <?php if($evpress) :?>
                <a href="<?php echo site_furl('home/conf_epress');?>" class="sub-navbar-a" >누르기설정</a>
                <a href="<?php echo site_furl('home/conf_epresslog');?>" class="sub-navbar-a" >누르기내역</a>
            <?php endif ?>  
            <a href="<?php echo site_furl('bet/ebalhistory');?>" class="sub-navbar-a" >밸런스내역</a>
            <a href="<?php echo site_furl('bet/ebethistory');?>" class="sub-navbar-a" >배팅내역</a>
        <?php endif ?>  

	</div>
    <script> var mGameId = <?=$game_id?>; </script>

    <?= $this->renderSection('eval_content') ?>
<?= $this->endSection() ?>