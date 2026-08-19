<?= $this->extend('/ebal/ebal_cont') ?>
<?= $this->section('ebal-active') ?><?=$game_name?><?= $this->endSection() ?>
<?= $this->section('eval_content') ?>

	<div class="confsite-site-panel">
		<h4><i class="glyphicon glyphicon-hand-right"></i> 누르기 설정</h4>
		<div class="pbresult-list-div" style="padding-left:20px;" <?=$mb_level < LEVEL_ADMIN+2?'hidden':''?>>
			<input type="checkbox" id="confev-forcepress-check-id" style="zoom:130%; margin-top:0px;">
			<label style="font-size:14px; padding-left:3px; position:relative; top:-3px;"> 강제누르기</label>
		</div>
		<div class="pbresult-list-div" style="padding-left:20px;">
			<input type="checkbox" id="confev-autopress-check-id" style="zoom:130%; margin-top:0px;">
			<label style="font-size:14px; padding-left:3px; position:relative; top:-3px;"> 자동누르기</label>
			<label style="margin-left:40px; width:70px;">보유금액:</label> 
			<input type = "number" class="conf-text-input" style="width:80px;" id="confev-autopercent-input-id" min="0" step="1">&nbsp;&nbsp;%이하
		</div>

		<div class="pbresult-list-div" style="padding-left:20px;">
			<input type="checkbox" id="confev-failpress-check-id" style="zoom:130%; margin-top:0px;">
			<label style="font-size:14px; padding-left:3px; position:relative; top:-3px;"> 넘기기 실패시 누르기</label>
			<label style="margin-left:40px; width:70px;">베팅금액:</label> 
			<input type = "number" class="conf-text-input" style="width:80px;" id="confev-failamount-input-id" min="0" step="1000">&nbsp;&nbsp;이하
		
		</div>
		<div class="pbresult-list-div" style="padding-left:20px;">
		
			<button class="pbresult-list-view-but" id="confsite-ok-btn-id">저장</button>
			<button class="pbresult-list-view-but" id="confsite-cancel-btn-id">취소</button>
		</div>

		<h4><i class="glyphicon glyphicon-hand-right"></i> 누르기 실시간</h4>
		<Table class="bet-table">
			<thead>
				<tr>
					<th>번호</th>
					<th>아이디</th>
					<th>접속시간</th>
					<th>기준금액</th>
					<th>현재금액</th>
					<th>상태</th>
					<th>설정</th>
				</tr>
			</thead>
			<tbody id="pbbet-table-id">

			</tbody>
		</Table>
		
	</div>

<!--main_navbar.php-main-container-->
</div>

<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/conf_epress-script.js?v=1');?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?t='.time());?>"></script>
    <script src="<?php echo site_furl('/assets/js/conf_epress-script.js?t='.time());?>"></script>
<?php endif ?>

<?= $this->endSection() ?>
