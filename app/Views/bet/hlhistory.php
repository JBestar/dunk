<?= $this->extend('/bet/history') ?>
<?= $this->section('history-active') ?><?=$game_name?><?= $this->endSection() ?>
<?= $this->section('history-title') ?><?=$game_name?> 배팅내역<?= $this->endSection() ?>
<?= $this->section('history-bet-table-headers') ?>		
	<th>번호</th>
	<th>아이디</th>
	<th>등록번호</th>
	<th>배팅시간</th>
	<th>배팅금</th>
	<th>승리금액</th>
	<th>배팅결과</th>
	<th>포인트</th>
<?php if($mb_level >= LEVEL_ADMIN+1) :  ?>
	<th>플레1</th>
	<th>플레2</th>
	<th>플레3</th>
	<th>플레4</th>
	<th>플레5</th>
	<th>플레6</th>
	<th>플레7</th>
	<th>플레8</th>
	<th>플레9</th>
	<th>커뮤니티</th>
<?php endif ?>
<?= $this->endSection() ?>
<?= $this->section('history_script') ?>
<script>var mGameId = <?=$game_id?></script>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
    <script src="<?php echo site_furl('/assets/js/hlhistory-script.js?v=1');?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/hlhistory-script.js?t='.time());?>"></script>
<?php endif ?>
<?= $this->endSection() ?>