<?= $this->extend('/ebal/ebal_cont') ?>
<?= $this->section('ebal-active') ?><?=$game_name?><?= $this->endSection() ?>
<?= $this->section('eval_content') ?>

	<div class="bet-panel">
		<div class="pbresult-list-div">
			
			<label style="font-size:18px; font-weight:normal">
				에볼루션 실시간배팅
			</label>

			<div style="float:right">
				<!-- <label id="ebal-balance-id" style="color:#ea564b; font-size:16px; padding:5px 20px; display:none;">보유알</label> -->
				<button class="pbresult-list-view-but" id="ebal-start-but-id" style="margin-right:0px; " disabled>시작</button>
				<button class="pbresult-list-view-but" id="ebal-stop-but-id" style="margin-right:3px; " disabled>정지</button>
			</div>
		</div>
		<Table class="bet-table">
			<thead>
				<tr>
					<th>번호</th>
					<th>방</th>
					<th>배팅</th>
					<th>금액</th>
					<th>밸런스</th>
				</tr>
			</thead>
			<tbody id="pbbet-table-id">

			</tbody>
		</Table>
		
	</div>

<!--main_navbar.php-main-container-->
</div>

<?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
    <script src="<?php echo site_furl('/assets/js/eordroom-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/eordroom-script.js?v=1');?>"></script>
<?php endif ?>

<?= $this->endSection() ?>
