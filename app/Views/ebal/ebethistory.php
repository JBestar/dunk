<?= $this->extend('/ebal/ebal_cont') ?>
<?= $this->section('ebal-active') ?><?=$game_name?><?= $this->endSection() ?>
<?= $this->section('eval_content') ?>

	<div class="bet-panel">
		<div class="pbresult-list-div">
			<label>기간</label>
			<input type="text" id="pbhistory-datestart-input-id" class="date-hour" value="<?php echo date('Y-m-d')." 00:00"; ?>" readonly>
            <label> ~ </label>
            <input type="text" id="pbhistory-dateend-input-id" class="date-hour" value="<?php echo calcDate(1)." 00:00"; ?>" style="margin-right:10px" readonly>
			<!-- <label>추천인</label> -->
            <input type="text" placeholder="     추천인" class="pbresult-text-input" id="pbhistory-empid-input-id" >
			<!-- <label>아이디</label> -->
            <input type="text" placeholder="     아이디" class="pbresult-text-input" id="pbhistory-userid-input-id" >

			<input type="text" placeholder="            게임방" class="pbresult-text-input" id="pbhistory-room-input-id"  style="width:150px;">
			
            <select name="pbresult-number" class="pbresult-number-select" id="pbhistory-type-select-id" style="width:80px; margin-left:-5px;">
				<option value="-1">전체</option>
				<option value="0">누르기</option>
				<option value="1">넘기기</option>
			</select>
            <select name="pbresult-number" class="pbresult-number-select" id="pbhistory-state-select-id" style="width:80px; margin-left:10px;">
				<option value="0">정상</option>
				<option value="1">처리</option>
				<option value="2">미처리</option>
			</select>
            <select name="pbresult-number" class="pbresult-number-select" id="pbhistory-number-select-id" style="width:70px; margin-left:5px;">
				<?php foreach($select_nums as $num=>$select):?>
					<option value="<?=$num?>" <?=$select?>><?=$num?>개</option>
				<?php endforeach?>
			</select>
			<button class="pbresult-list-view-but" id="pbhistory-list-view-but-id">검색</button>
			

		</div>
		<Table class="bet-table">
			<thead>
				<tr  id="pbbet-table-head-id">
					<th>ID</th>
					<th>아이디</th>
					<th>배팅시간</th>
					<th>게임종류</th>
					<th>게임방</th>
					<th>요청금</th>
					<th>배팅금</th>
					<th>넘기기금</th>
					<th>배팅타입</th>
					<th>배팅선택</th>
					<th>경기결과</th>
					<th>적중금</th>
					<th>배팅결과</th>
					<th>포인트</th>
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
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
    <script src="<?php echo site_furl('/assets/js/page.js?t='.time());?>"></script>
    <script src="<?php echo site_furl('/assets/js/ebethistory-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/ebethistory-script.js?v=1');?>"></script>
<?php endif ?>

<?= $this->endSection() ?>