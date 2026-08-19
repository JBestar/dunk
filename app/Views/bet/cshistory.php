<?= $this->extend('/bet/history') ?>
<?= $this->section('history-active') ?><?=$game_name?><?= $this->endSection() ?>
<?= $this->section('history-title') ?>		
<?=$game_name?> 배팅내역
<?= $this->endSection() ?>
<?= $this->section('history_game_options') ?>	
	<?php if(!$cas_deny) :?>
		<select class="pbresult-game-select" id="pbhistory-game-select-id">
			<option value="-1">::업체선택::</option>	
			<?php foreach ($prds as $prd):?>
				<option value="<?=$prd->vendor_id?>"><?=$prd->name?></option>
			<?php endforeach;?>
		</select>
	<?php endif ?>   
<?= $this->endSection() ?>
<?= $this->section('history-bet-table-headers') ?>		
	<th>ID</th>
	<th>아이디</th>
	<th>배팅시간</th>
	<th>업체명</th>
	<th>게임종류</th>
	<th>게임방</th>
	<th>배팅금</th>
	<th>적중금</th>
	<th>배팅결과</th>
	<?php if($game_id == GAME_CASINO_EVOL) :?>
		<th>배팅선택</th>
	<?php endif ?>
	<th>포인트</th>
<?= $this->endSection() ?>
<?= $this->section('history_script') ?>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
    <script src="<?php echo site_furl('/assets/js/cshistory-script.js?v=2');?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/cshistory-script.js?t='.time());?>"></script>
<?php endif ?>
<?= $this->endSection() ?>