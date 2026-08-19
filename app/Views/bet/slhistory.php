<?= $this->extend('/bet/history') ?>
<?= $this->section('history-active') ?><?=$game_name?><?= $this->endSection() ?>
<?= $this->section('history-title') ?><?=$game_name?> 배팅내역<?= $this->endSection() ?>
<?= $this->section('history_game_options') ?>	
	<select class="pbresult-game-select" id="pbhistory-game-select-id">
		<option value="0">::업체선택::</option>	
		<?php foreach ($prds as $prd):?>
			<option value="<?=$prd->code?>"><?=$prd->name_kr.($prd->code==215?"(NEW)":"")?></option>
		<?php endforeach;?>
	</select>
<?= $this->endSection() ?>
<?= $this->section('history-bet-table-headers') ?>		
	<th>번호</th>
	<th>아이디</th>
	<th>배팅시간</th>
	<th>업체명</th>
	<th>게임종류</th>
	<th>배팅금</th>
	<th>적중금</th>
	<th>배팅결과</th>
	<th>포인트</th>
<?= $this->endSection() ?>
<?= $this->section('history-bet-sum') ?>		
	<p>공배팅: <span id="total-blank-id">0</span></p>
<?= $this->endSection() ?>
<?= $this->section('history_script') ?>
<script>var mGameId = <?=$game_id?></script>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
    <script src="<?php echo site_furl('/assets/js/slhistory-script.js?v=2');?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/slhistory-script.js?t='.time());?>"></script>
<?php endif ?>
<?= $this->endSection() ?>