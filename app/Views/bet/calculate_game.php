<?= $this->extend('/bet/calculate') ?>
<?= $this->section('calculate-active') ?><?=$game_name?><?= $this->endSection() ?>
<?= $this->section('calculate-title') ?>
<?=$game_name?> 정산내역
<?= $this->endSection() ?>
<?= $this->section('calculate-table-header') ?>
	<th></th>
	<th>ID</th>
	<th>닉네임</th>
	<th>보유금</th>
	<th>포인트</th>
	<th>충전｜환전</th>
	<th>충환손익</th>
	<th>지급｜회수</th>
	<th>배팅</th>
	<th>적중</th>
	<th>배팅손익</th>
	<th>수수료</th>
	<th>최종손익</th>				
<?= $this->endSection() ?>
<?= $this->section('calculate-script') ?>

<?= $this->endSection() ?>
