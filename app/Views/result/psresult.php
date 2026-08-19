<?= $this->extend('result/game_result')?>
<?= $this->section('bet-result-title') ?><?=$game_name?><?= $this->endSection() ?>
<?= $this->section('bet-result-round-name') ?>회차<?= $this->endSection() ?>
<?= $this->section('bet-result-edit') ?>
<!-- <a href="<?php echo siteFurl().'result/psresult_edit/0';?>" class="user-panel-add-a" >회차등록</a> -->
<?= $this->endSection() ?>
<?= $this->section('bet-result-table-header') ?>
	<th>추첨일</th>
	<th>회차</th>
	<th>좌우</th>
	<th>줄수</th>
	<th>홀짝</th>	
	<th>게임관리</th>			
<?= $this->endSection() ?>
<?= $this->section('bet-result-script') ?>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
	<script src="<?php echo site_furl('/assets/js/psresult-script.js?t='.time());?>"></script>
<?php else : ?>
	<script src="<?php echo site_furl('/assets/js/psresult-script.js?v=1');?>"></script>
<?php endif ?>
<?= $this->endSection() ?>