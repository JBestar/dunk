<?= $this->extend('result/bet_change') ?>
<?= $this->section('bet-change-title') ?><?=$game_name?><?= $this->endSection() ?>
<?= $this->section('bet-change-table-header') ?>
	<th>번호</th>
	<th>배팅시간</th>
	<th>회차</th>
	<th>아이디</th>
	<th>구분</th>
	<th>배팅금액</th>
	<th>배당율</th>
	<th>배팅선택</th>
	<th>경기결과</th>
	<th>당첨금액</th>
	<th>배팅결과</th>
	<th>처리</th>
<?= $this->endSection() ?>
<?= $this->section('bet-change-script') ?>
	<?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
		<script src="<?php echo site_furl('/assets/js/knbet_change-script.js?t='.time());?>"></script>
	<?php else : ?>
		<script src="<?php echo site_furl('/assets/js/knbet_change-script.js?v=1');?>"></script>
	<?php endif ?>
<?= $this->endSection() ?>
