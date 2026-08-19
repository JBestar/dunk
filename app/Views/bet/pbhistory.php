<?= $this->extend('/bet/history') ?>
<?= $this->section('history-active') ?><?=$game_name?><?= $this->endSection() ?>
<?= $this->section('history-title') ?><?=$game_name?> 배팅내역<?= $this->endSection() ?>
<?= $this->section('history_game_options') ?>		
	<select class="pbresult-game-select" id="pbhistory-game-select-id" style="width:120px;">
		<option value="0">::배팅선택::</option>	
		<option value="1">파워볼 단폴</option>
		<option value="2">파워볼 조합</option>
		<option value="3">일반볼 대중소</option>
		<option value="4">파워볼 3조합</option>
		<option value="5">파워볼 숫자</option>
	</select>
<?= $this->endSection() ?>
<?= $this->section('history-add-round-search') ?>
	<!-- <label></label> -->
	<input type="text" placeholder="    회차" class="pbresult-text-input" id="pbhistory-roundid-input-id"  style="width:70px;">
<?= $this->endSection() ?>
<?= $this->section('history-bet-table-headers') ?>
	<th>번호</th>
	<th>배팅시간</th>
	<th>회차</th>
	<th>아이디</th>
	<th>구분</th>
	<th>배팅금</th>
	<th>배당율</th>
	<th>배팅선택</th>
	<th>경기결과</th>
	<th>적중금</th>
	<th>배팅결과</th>
	<th>포인트</th>
	
<?= $this->endSection() ?>
<?= $this->section('history_script') ?>
	<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
		<script src="<?php echo site_furl('/assets/js/pbhistory-script.js?v=1');?>"></script>
	<?php else : ?>
		<script src="<?php echo site_furl('/assets/js/pbhistory-script.js?t='.time());?>"></script>
	<?php endif ?>
<?= $this->endSection() ?>