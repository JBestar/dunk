<?= $this->extend('bet/realtime') ?>
<?= $this->section('realtime-title') ?><?=$game_name?><?= $this->endSection() ?>
<?= $this->section('realtime-table-header') ?>
	<th>번호</th>
	<th>경기시간</th>
	<th>회차</th>
	<th>배팅</th>
	<th>배당율</th>
	<th>금액</th>
	<th>밸런스금액</th>		
<?= $this->endSection() ?>
<?= $this->section('realtime-script') ?>
	<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
		<script src="<?php echo site_furl('/assets/js/psrealtime-script.js?v=1');?>"></script>
	<?php else : ?>
		<script src="<?php echo site_furl('/assets/js/psrealtime-script.js?t='.time());?>"></script>
	<?php endif ?>
<?= $this->endSection() ?>
