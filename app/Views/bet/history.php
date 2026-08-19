<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	<!--Sub Navbar-->
	<div class = "sub-navbar" value="<?= $this->renderSection('history-active') ?>">
		<p><i class="glyphicon glyphicon-book"></i> 배팅내역</p>
		<?php if(!$hold_deny) :?>
			<a href="<?php echo site_furl('bet/hlhistory');?>" class="sub-navbar-a" >홀덤</a>
		<?php endif ?> 
		<?php if(!$evol_deny || !$cas_deny ) :?>
			<a href="<?php echo site_furl('bet/cshistory');?>" class="sub-navbar-a" >카지노</a>
		<?php endif ?>
		<?php if(isEBalMode()) :?>
			<a href="<?php echo site_furl('bet/evhistory');?>" class="sub-navbar-a" >에볼루션</a>
		<?php endif ?>
		<?php if(isPBalMode()) :?>
			<a href="<?php echo site_furl('bet/prhistory');?>" class="sub-navbar-a" >프라그마틱</a>
		<?php endif ?> 
		<?php if(!$slot_deny) :?>
			<?php if($mb_level >= LEVEL_ADMIN) :  ?>
				<?php if($_ENV['app.type'] == APP_TYPE_1 || $_ENV['app.type'] == APP_TYPE_3) :?>
					<a href="<?php echo site_furl('bet/xslhistory');?>" class="sub-navbar-a" >정품슬롯</a>
				<?php endif ?>
				<?php if($_ENV['app.type'] == APP_TYPE_1 || $_ENV['app.type'] == APP_TYPE_2) :?>
					<a href="<?php echo site_furl('bet/fslhistory');?>" class="sub-navbar-a" >네츄럴슬롯</a>
				<?php endif ?>
				<?php if($_ENV['app.type'] == APP_TYPE_1) :?>
					<a href="<?php echo site_furl('bet/slhistory');?>" class="sub-navbar-a" >슬롯</a>
				<?php endif ?>
			<?php else: ?>
				<a href="<?php echo site_furl('bet/slhistory');?>" class="sub-navbar-a" >슬롯</a>
			<?php endif ?>
		<?php endif ?>   
		<?php if(!$pbg_deny) :?>
			<a href="<?php echo site_furl('bet/pbhistory');?>" class="sub-navbar-a" >PBG</a>
		<?php endif ?>
		<?php if(!$dhp_deny) :?>
			<a href="<?php echo site_furl('bet/dphistory');?>" class="sub-navbar-a" >동행볼</a>
		<?php endif ?>  
		<?php if(!$spk_deny) :?>
			<a href="<?php echo site_furl('bet/skhistory');?>" class="sub-navbar-a" >스피드키노</a>
		<?php endif ?>  
		<?php if(!$bpg_deny) :?>
			<a href="<?php echo site_furl('bet/bbhistory');?>" class="sub-navbar-a" >보글볼</a>
			<a href="<?php echo site_furl('bet/bshistory');?>" class="sub-navbar-a" >보사달</a>
		<?php endif ?>   
		<?php if(!$eos5_deny) :?>
			<a href="<?php echo site_furl('bet/e5history');?>" class="sub-navbar-a" >EOS5분</a>
		<?php endif ?>  
		<?php if(!$eos3_deny) :?>
			<a href="<?php echo site_furl('bet/e3history');?>" class="sub-navbar-a" >EOS3분</a>
		<?php endif ?>  
		<?php if(!$coin5_deny) :?>
			<a href="<?php echo site_furl('bet/r5history');?>" class="sub-navbar-a" >코인5분</a>
		<?php endif ?>  
		<?php if(!$coin3_deny) :?>
			<a href="<?php echo site_furl('bet/r3history');?>" class="sub-navbar-a" >코인3분</a>
		<?php endif ?> 
	</div>

	<div class="bet-panel">
        <h4>
            <?= $this->renderSection('history-title') ?>
        </h4>
		
		<div class="pbresult-list-div">
			<label>기간</label>
			<input type="text" id="pbhistory-datestart-input-id" class="date-hour" value="<?php echo date('Y-m-d')." 00:00"; ?>" readonly>
            <label> ~ </label>
            <input type="text" id="pbhistory-dateend-input-id" class="date-hour" value="<?php echo calcDate(1)." 00:00"; ?>" style="margin-right:10px" readonly>
            <?= $this->renderSection('history-add-round-search') ?>
            <?php if($mb_level >= LEVEL_ADMIN) {  ?>
			<!-- <label>추천인</label> -->
            <input type="text" placeholder="     추천인" class="pbresult-text-input" id="pbhistory-empid-input-id" >
			<?php } ?>
			<!-- <label>아이디</label> -->
            <input type="text" placeholder="     아이디" class="pbresult-text-input" id="pbhistory-userid-input-id" >

            <?= $this->renderSection('history_game_options') ?>

			<select name="pbresult-number" class="pbresult-number-select" id="pbhistory-number-select-id" style="width:70px; margin-left:10px;">
				<?php foreach($select_nums as $num=>$select):?>
					<option value="<?=$num?>" <?=$select?>><?=$num?>개</option>
				<?php endforeach?>
			</select>
			<button class="pbresult-list-view-but" id="pbhistory-list-view-but-id">검색</button>
		</div>
		<Table class="bet-table">
			<thead>
				<tr>
                    <?= $this->renderSection('history-bet-table-headers') ?>
				</tr>
			</thead>
			<tbody id="pbbet-table-id">

			</tbody>
		</Table>
		<div class="pbresult-list-page-div">
			<p>배팅금: <span id="total-betmoney-id">0</span></p>
			<p>적중금: <span id="total-winmoney-id">0</span></p>
			<p>미적중금: <span id="total-lossmoney-id">0</span></p>
			<p>당첨금: <span id="total-benefit-id">0</span></p>
			<?php if($mb_level >= LEVEL_ADMIN) :  ?>
			<?= $this->renderSection('history-bet-sum') ?>
			<?php endif ?>
			<div class="pagination"  id="list-page"  style="display:none">
				<button class="list-page-button" id="page-first"  onclick="firstPage()"><<</button>
				<button class="list-page-button" id="page-prev"  onclick="prevPage()"><</button>
				<div class="pagination-div" id="pagination-num">
					<button class="active">1</button>
					<button class="">2</button>						
				</div>
				<button class="list-page-button" id="page-next"  onclick="nextPage()">></button>
				<button class="list-page-button" id="page-last"  onclick="lastPage()">>></button>
			</div>			
	
		</div>
	</div>

	
<!--main_navbar.php-main-container-->
</div>
<script> var mGameId = <?=$game_id?>; </script>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?t='.time());?>"></script>
<?php endif ?>

<?= $this->renderSection('history_script') ?>
<?= $this->endSection() ?>