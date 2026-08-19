<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	<!--Sub Navbar-->
	<div class = "sub-navbar" value="<?= $this->renderSection('bet-result-title')?>">
		<p><i class="glyphicon glyphicon-book"></i> 게임결과</p>
		<?php if(!$pbg_deny) :?>
			<a href="<?php echo siteFurl().'result/pbresult';?>" class="sub-navbar-a" >PBG</a>
		<?php endif ?>   
		<?php if(!$dhp_deny) :?>
			<a href="<?php echo site_furl('result/dpresult');?>" class="sub-navbar-a" >동행볼</a>
		<?php endif ?>  
		<?php if(!$spk_deny) :?>
			<a href="<?php echo site_furl('result//skresult');?>" class="sub-navbar-a" >스피드키노</a>
		<?php endif ?>   
		<?php if(!$bpg_deny) :?>
			<a href="<?php echo siteFurl().'result/bbresult';?>" class="sub-navbar-a" >보글볼</a>
			<a href="<?php echo siteFurl().'result/bsresult';?>" class="sub-navbar-a" >보사달</a>
		<?php endif ?>
		<?php if(!$eos5_deny) :?>
			<a href="<?php echo siteFurl().'result/e5result';?>" class="sub-navbar-a" >EOS5분</a>
		<?php endif ?>   
		<?php if(!$eos3_deny) :?>
			<a href="<?php echo siteFurl().'result/e3result';?>" class="sub-navbar-a" >EOS3분</a>
		<?php endif ?>
		<?php if(!$coin5_deny) :?>
			<a href="<?php echo siteFurl().'result/r5result';?>" class="sub-navbar-a" >코인5분</a>
		<?php endif ?>   
		<?php if(!$coin3_deny) :?>
			<a href="<?php echo siteFurl().'result/r3result';?>" class="sub-navbar-a" >코인3분</a>
		<?php endif ?>      
	</div>

	<div class="pbresult-list-panel">
		<h4><?= $this->renderSection('bet-result-title')?> 게임결과</h4>
		<div class="pbresult-list-div">
			<label>기간 선택</label>
			<input type="date" id="pbresult-datestart-input-id" value="<?php echo date('Y-m-d'); ?>">
            <label> ~ </label>
            <input type="date" id="pbresult-dateend-input-id" value="<?php echo date('Y-m-d'); ?>">
            <label><?= $this->renderSection('bet-result-round-name')?></label>
            <input type="number" id="pbresult-round-input-id" style="width:100px;" min="1">
			<select name="pbresult-number" class="pbresult-number-select" id="pbresult-number-select-id">
				<?php foreach($select_nums as $num=>$select):?>
					<option value="<?=$num?>" <?=$select?>><?=$num?>개</option>
				<?php endforeach?>
			</select>
			<button class="pbresult-list-view-but" id="pbresult-list-view-but-id">검색</button>  
            <?= $this->renderSection('bet-result-edit')?>
		</div>
		<Table class="user-table">
			<thead>
				<tr>
                    <?= $this->renderSection('bet-result-table-header')?>
				</tr>
			</thead>
			<tbody id="pbresult-list-table-id">

			</tbody>
		</Table>
		<div class="pbresult-list-page-div">
			<div class="pagination" id="list-page" style="display:none">
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
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
    <script src="<?php echo site_furl('/assets/js/page.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
<?php endif ?>

<?= $this->renderSection('bet-result-script')?>
<?= $this->endSection() ?>