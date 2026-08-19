<?= $this->extend('/ebal/ebal_cont') ?>
<?= $this->section('ebal-active') ?><?=$game_name?><?= $this->endSection() ?>
<?= $this->section('eval_content') ?>
	<div class="bet-panel">
		<div class="pbresult-list-div">
			<label>기간</label>
			<input type="text" id="pbhistory-datestart-input-id" class="date-hour" value="<?php echo date('Y-m-d')." 00:00"; ?>" readonly>
            <label> ~ </label>
            <input type="text" id="pbhistory-dateend-input-id" class="date-hour" value="<?php echo calcDate(1)." 23:00"; ?>" style="margin-right:10px" readonly>
            <input type="text" placeholder="     아이디" class="pbresult-text-input" id="pbhistory-userid-input-id" >
			<input type="text" placeholder="            게임방" class="pbresult-text-input" id="pbhistory-room-input-id"  style="width:150px;">
			<select class="pbresult-game-select" id="pbhistory-bet-select-id" style="width:100px;">
				<option value="0">::배팅선택::</option>	
				<option value="1">밸런스</option>
				<option value="2">팅김방지</option>
			</select>
			<select name="pbresult-number" class="pbresult-number-select" id="pbhistory-state-select-id" style="width:80px; margin-left:10px;">
				<option value="0">성공</option>
				<option value="1">실패</option>
			</select>
            <select name="pbresult-number" class="pbresult-number-select" id="pbhistory-number-select-id" style="width:70px; margin-left:10px;">
				<?php foreach($select_nums as $num=>$select):?>
					<option value="<?=$num?>" <?=$select?>><?=$num?>개</option>
				<?php endforeach?>
			</select>
			<button class="pbresult-list-view-but" id="pbhistory-list-view-but-id">검색</button>
		</div>
		<Table class="bet-table">
			<thead>
				<tr >
					<th>ID</th>
					<th>계정</th>
					<th>배팅시간</th>
					<th>게임종류</th>
					<th>게임방</th>
					<th>배팅종류</th>
					<th>유저배팅금합<br>플 / 뱅</th>
					<th>배팅금(차액)</th>
					<th>배팅선택</th>
					<th>경기결과</th>
					<th  id="pbbet-table-th-state-id">적중금</th>
					<th>보유금</th>
					<th>수익</th>
				</tr>
			</thead>
			<tbody id="pbbet-table-id">

			</tbody>
		</Table>
		<div class="pbresult-list-page-div">
			<p>총 배팅금: <span id="total-betmoney-id">0</span></p>
			<p>총 적중금: <span id="total-winmoney-id">0</span></p>
			<p>총 밸런스금: <span id="total-balmoney-id">0</span></p>
			<p>뱅커 밸런스금: <span id="total-bankermoney-id">0</span></p>
			<p>밸런스수익: <span id="total-profit-id">0</span></p>
			<p>팅김방지: <span id="total-conmoney-id">0</span></p>
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

<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/ebalhistory-script.js?v=1');?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?t='.time());?>"></script>
    <script src="<?php echo site_furl('/assets/js/ebalhistory-script.js?t='.time());?>"></script>
<?php endif ?>

<?= $this->endSection() ?>
