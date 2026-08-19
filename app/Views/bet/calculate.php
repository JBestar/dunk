<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	<!--Sub Navbar-->
	<div class = "sub-navbar" value="<?= $this->renderSection('calculate-active') ?>">
		<p><i class="glyphicon glyphicon-tag"></i> 정산</p>

			<?php if(!array_key_exists('app.hold', $_ENV) || $_ENV['app.hold'] == 0) :?>
				<a href="<?php echo site_furl('bet/allcalculate');?>" class="sub-navbar-a" >전체</a>
			<?php endif ?> 
			<?php if(!$hold_deny) :?>
			<a href="<?php echo site_furl('bet/hlcalculate');?>" class="sub-navbar-a" >홀덤</a>
			<?php endif ?> 
			<?php if(!$evol_deny || !$cas_deny ) :?>
			<a href="<?php echo site_furl('bet/cscalculate');?>" class="sub-navbar-a" >카지노</a>
			<?php endif ?>   
			<?php if(isEBalMode()) :?>
			<a href="<?php echo site_furl('bet/evcalculate');?>" class="sub-navbar-a" >에볼루션</a>
			<?php endif ?> 
			<?php if(isPBalMode()) :?>
			<a href="<?php echo site_furl('bet/prcalculate');?>" class="sub-navbar-a" >프라그마틱</a>
			<?php endif ?> 
			<?php if(!$slot_deny) :?>
				<?php if($mb_level >= LEVEL_ADMIN) :  ?>
					<?php if($_ENV['app.type'] == APP_TYPE_1 || $_ENV['app.type'] == APP_TYPE_3) :?>
						<a href="<?php echo site_furl('bet/xslcalculate');?>" class="sub-navbar-a" >정품슬롯</a>
					<?php endif ?>
					<?php if($_ENV['app.type'] == APP_TYPE_1 || $_ENV['app.type'] == APP_TYPE_2) :?>
						<a href="<?php echo site_furl('bet/fslcalculate');?>" class="sub-navbar-a" >네츄럴슬롯</a>
					<?php endif ?>
					<?php if($_ENV['app.type'] == APP_TYPE_1) :?>
						<a href="<?php echo site_furl('bet/slcalculate');?>" class="sub-navbar-a" >슬롯</a>
					<?php endif ?>
				<?php else: ?>
					<a href="<?php echo site_furl('bet/slcalculate');?>" class="sub-navbar-a" >슬롯</a>
				<?php endif ?>
			<?php endif ?>   
			<?php if(!$pbg_deny) :?>
				<a href="<?php echo site_furl('bet/pbcalculate');?>" class="sub-navbar-a" >PBG</a>
			<?php endif ?>  
			<?php if(!$dhp_deny) :?>
				<a href="<?php echo site_furl('bet/dpcalculate');?>" class="sub-navbar-a" >동행볼</a>
			<?php endif ?>
			<?php if(!$spk_deny) :?>
				<a href="<?php echo site_furl('bet/skcalculate');?>" class="sub-navbar-a" >스피드키노</a>
			<?php endif ?>
			<?php if(!$bpg_deny) :?>
				<a href="<?php echo site_furl('bet/bbcalculate');?>" class="sub-navbar-a" >보글볼</a>
				<a href="<?php echo site_furl('bet/bscalculate');?>" class="sub-navbar-a" >보사달</a>
			<?php endif ?>   
			<?php if(!$eos5_deny) :?>
				<a href="<?php echo site_furl('bet/e5calculate');?>" class="sub-navbar-a" >EOS5분</a>
			<?php endif ?>
			<?php if(!$eos3_deny) :?>
				<a href="<?php echo site_furl('bet/e3calculate');?>" class="sub-navbar-a" >EOS3분</a>
			<?php endif ?>
			<?php if(!$coin5_deny) :?>
				<a href="<?php echo site_furl('bet/r5calculate');?>" class="sub-navbar-a" >코인5분</a>
			<?php endif ?>
			<?php if(!$coin3_deny) :?>
				<a href="<?php echo site_furl('bet/r3calculate');?>" class="sub-navbar-a" >코인3분</a>
			<?php endif ?>
	</div>

	<div class="bet-panel">
		<h4>
			<?= $this->renderSection("calculate-title")?>
		</h4>
		
		<div class="pbresult-list-div">
			<label>기간</label>
			<input type="text" id="calculate-datestart-input-id" class="date-hour" value="<?php echo date('Y-m-d')." 00:00"; ?>" readonly>
            <label> ~ </label>
            <input type="text" id="calculate-dateend-input-id" class="date-hour" value="<?php echo calcDate(1)." 00:00"; ?>" readonly>
			<button class="pbresult-list-view-but" id="calculate-list-view-but-id">검색</button>
		</div>
		<Table class="calculate-table" id="calculate-table-id">
			<thead>
				<tr>
					<?= $this->renderSection("calculate-table-header")?>			
				</tr>
			</thead>
			<tbody id="calculate-table-tbody-id">

			</tbody>
		</Table>

	</div>


<!--main_navbar.php-main-container-->
</div>
<script> var mGameId = <?=$game_id?>; </script>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
	<script src="<?php echo site_furl('/assets/js/calculate-script.js?v=2');?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?t='.time());?>"></script>
	<script src="<?php echo site_furl('/assets/js/calculate-script.js?t='.time());?>"></script>
<?php endif ?>

<?= $this->renderSection("calculate-script")?>
<?= $this->endSection() ?>