<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	<!--Sub Navbar
	<i class="glyphicon glyphicon-resize-horizontal"></i>
	-->
	<div class = "sub-navbar">
		<p> 사이트 <i class="glyphicon glyphicon-transfer"></i> 게임사 머니이동내역</p>
	</div>
	<!--Site Setting-->
	<div class="bank-panel">	
		
		<div class="pbresult-list-div">
			<label>기간</label>
			<input type="date" id="transfer-datestart-input-id"  value="<?php echo date('Y-m-d');?>">
            <label> ~ </label>
            <input type="date" id="transfer-dateend-input-id"  value="<?php echo date('Y-m-d'); ?>">
            <label>아이디</label>
            <input type="text" class="pbresult-text-input" id="transfer-userid-input-id" >

            <select class="pbresult-game-select" id="transfer-type-select-id">
				<option value="0">::분류::</option>
				<?php if(!$evol_deny  || !$cas_deny) :?>
					<option value="1">사이트 => 카지노</option>
					<option value="2">카지노 => 사이트</option>
				<?php endif ?>   
				<?php if(!$slot_deny) :?>
					<option value="3">사이트 => 슬롯</option>
					<option value="4">슬롯 => 사이트</option>
				<?php endif ?>
				<?php if(!$hold_deny) :?>
					<option value="5">사이트 => 홀덤</option>
					<option value="6">홀덤 => 사이트</option>
				<?php endif ?>
					<option value="7">정산회수</option>
			</select>

			<select name="pbresult-number" class="pbresult-number-select" id="transfer-number-select-id">
				<?php foreach($select_nums as $num=>$select):?>
					<option value="<?=$num?>" <?=$select?>><?=$num?>개</option>
				<?php endforeach?>
			</select>
			<button class="pbresult-list-view-but" id="transfer-list-view-but-id">검색</button>
		</div>


		<Table class="user-table">
			<thead>
				<tr>
					<th>번호</th>
					<th>아이디</th>
					<!-- <th>현재금액</th> -->
					<th>구분</th>
					<th>이동금액</th>
					<th>이동전 사이트머니</th>
					<th>이동후 사이트머니</th>
					<th>이동전 게임사머니</th>
					<th>이동후 게임사머니</th>
					<th>일짜</th>
					
				</tr>
			</thead >
			<tbody  id="transfer-table-id">
				
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
<script>
	const TRANS_SITE_EVOL = <?=TRANS_SITE_EVOL?>; // 1
	const TRANS_EVOL_SITE = <?=TRANS_EVOL_SITE?>; // 2
	const TRANS_SITE_KGON = <?=TRANS_SITE_KGON?>; // 3
	const TRANS_KGON_SITE = <?=TRANS_KGON_SITE?>; // 4
	const TRANS_SITE_PLUS = <?=TRANS_SITE_PLUS?>; // 5
	const TRANS_PLUS_SITE = <?=TRANS_PLUS_SITE?>; // 6
	const TRANS_SITE_GSPL = <?=TRANS_SITE_GSPL?>; // 7
	const TRANS_GSPL_SITE = <?=TRANS_GSPL_SITE?>; // 8
	const TRANS_SITE_GOLD = <?=TRANS_SITE_GOLD?>; // 9
	const TRANS_GOLD_SITE = <?=TRANS_GOLD_SITE?>; // 10
	const TRANS_SITE_STAR = <?=TRANS_SITE_STAR?>; // 11
	const TRANS_STAR_SITE = <?=TRANS_STAR_SITE?>; // 12
	const TRANS_SITE_HOLD = <?=TRANS_SITE_HOLD?>; // 13
	const TRANS_HOLD_SITE = <?=TRANS_HOLD_SITE?>; // 14
	const TRANS_SITE_RAVE = <?=TRANS_SITE_RAVE?>; // 15
	const TRANS_RAVE_SITE = <?=TRANS_RAVE_SITE?>; // 16
	const TRANS_SITE_TREEM = <?=TRANS_SITE_TREEM?>; // 17
	const TRANS_TREEM_SITE = <?=TRANS_TREEM_SITE?>; // 18
	const RECOVER_EGG = <?=RECOVER_EGG?>; // 19
	const TRANS_SITE_SIGMA = <?=TRANS_SITE_SIGMA?>; // 20
	const TRANS_SIGMA_SITE = <?=TRANS_SIGMA_SITE?>; // 21

</script>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/transfer-script.js?v=3');?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?t='.time());?>"></script>
    <script src="<?php echo site_furl('/assets/js/transfer-script.js?t='.time());?>"></script>
<?php endif ?>
<?= $this->endSection() ?>