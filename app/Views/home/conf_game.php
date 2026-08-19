<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	  <!--Sub Navbar-->
	<div class = "sub-navbar" value="<?= $this->renderSection('confgame-active') ?>">
		<p><i class="glyphicon glyphicon-play-circle"></i> 게임설정</p>
		<?php if(!$cas_deny) :?>
			<a href="<?php echo siteFurl().'home/conf_casino';?>" class="sub-navbar-a" >정품카지노</a>
		<?php endif ?>
		<?php if(!$evol_deny ) :?>
			<a href="<?php echo siteFurl().'home/conf_evol';?>" class="sub-navbar-a " >에볼루션</a>
		<?php endif ?>   
		<?php if(!$slot_deny) :?>
			<?php if($_ENV['app.type'] == APP_TYPE_1 || $_ENV['app.type'] == APP_TYPE_3) :?>
				<a href="<?php echo siteFurl().'home/conf_slot_1';?>" class="sub-navbar-a">정품슬롯</a>
			<?php endif ?>
			<?php if($_ENV['app.type'] == APP_TYPE_1 || $_ENV['app.type'] == APP_TYPE_2) :?>
				<a href="<?php echo siteFurl().'home/conf_slot_2';?>" class="sub-navbar-a">네츄럴슬롯</a>
			<?php endif ?>
		<?php endif ?>  
		<?php if(!$hold_deny) :?>
			<a href="<?php echo siteFurl().'home/conf_hold';?>" class="sub-navbar-a" >홀덤</a>
		<?php endif ?>
		<?php if(!$pbg_deny) :?>
			<a href="<?php echo siteFurl().'home/conf_powerball';?>" class="sub-navbar-a" >PBG</a>
		<?php endif ?>  
		<?php if(!$dhp_deny) :?>
			<a href="<?php echo site_furl('home/conf_dhpball');?>" class="sub-navbar-a" >동행볼</a>
		<?php endif ?>  
		<?php if(!$spk_deny) :?>
			<a href="<?php echo site_furl('home/conf_speedkeno');?>" class="sub-navbar-a" >스피드키노</a>
		<?php endif ?>    
    	<?php if(!$bpg_deny) :?>
			<a href="<?php echo siteFurl().'home/conf_bogleball';?>" class="sub-navbar-a" >보글볼</a>
			<a href="<?php echo siteFurl().'home/conf_bogleladder';?>" class="sub-navbar-a " >보사달</a>
		<?php endif ?>   
		<?php if(!$eos5_deny) :?>
			<a href="<?php echo site_furl('home/conf_eos5ball');?>" class="sub-navbar-a" >EOS5분</a>
   		<?php endif ?>  
		<?php if(!$eos3_deny) :?>
			<a href="<?php echo site_furl('home/conf_eos3ball');?>" class="sub-navbar-a" >EOS3분</a>
   		<?php endif ?>  
		<?php if(!$coin5_deny) :?>
			<a href="<?php echo site_furl('home/conf_coin5ball');?>" class="sub-navbar-a" >코인5분</a>
   		<?php endif ?>  
		<?php if(!$coin3_deny) :?>
			<a href="<?php echo site_furl('home/conf_coin3ball');?>" class="sub-navbar-a" >코인3분</a>
   		<?php endif ?>  
		 
	</div>
	<!--Site Setting-->
	<div class="confsite-game-panel"  id="<?=$game_id?>">
		<?= $this->renderSection('confgame-content') ?>
	</div>
<!--main_navbar.php-->
</div>

<script>
	const GAME_CASINO_KGON = <?=GAME_CASINO_KGON?>;
	const GAME_CASINO_EVOL = <?=GAME_CASINO_EVOL?>;
	const GAME_SLOT_THEPLUS = <?=GAME_SLOT_THEPLUS?>;
	const GAME_SLOT_GSPLAY = <?=GAME_SLOT_GSPLAY?>;
	const GAME_SLOT_GOLD = <?=GAME_SLOT_GOLD?>;
	const GAME_SLOT_KGON = <?=GAME_SLOT_KGON?>;
	const GAME_SLOT_STAR = <?=GAME_SLOT_STAR?>;
	const GAME_CASINO_STAR = <?=GAME_CASINO_STAR?>;
	const GAME_SLOT_RAVE = <?=GAME_SLOT_RAVE?>;
	const GAME_CASINO_RAVE = <?=GAME_CASINO_RAVE?>;
	const GAME_SLOT_TREEM = <?=GAME_SLOT_TREEM?>;
	const GAME_CASINO_TREEM = <?=GAME_CASINO_TREEM?>;
	const GAME_SLOT_SIGMA = <?=GAME_SLOT_SIGMA?>;
	const GAME_CASINO_SIGMA = <?=GAME_CASINO_SIGMA?>;
</script>

<?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
    <script src="<?php echo site_furl('/assets/js/page.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
<?php endif ?>
<?= $this->renderSection('confgame-script') ?>

<?= $this->endSection() ?>