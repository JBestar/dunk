<?= $this->extend('/home/conf_site') ?>
<?= $this->section('confsite-active') ?>점검설정<?= $this->endSection() ?>

<?= $this->section('confsite-navbar') ?>
<?= $this->endSection() ?>

<?= $this->section('confsite-content') ?>

		<h4><i class="glyphicon glyphicon-hand-right"></i> 사이트점검설정</h4>						
		<div class="confsite-site-check-div">
			<label style="font-size: 14px; width:100px;"> 운영상태</label>			
			<select name="pbresult-number" class="pbresult-number-select" id="confsite-maintain-select-id" style="width: 200px;">
				<?php if(is_null($objConfig) || $objConfig->conf_active != 1) {  ?>
				<option value="0" selected>정상운영</option>
				<?php } else {?>
				<option value="0">정상운영</option>
				<?php } ?>
				<?php if(is_null($objConfig) || $objConfig->conf_active == 1) {  ?>
				<option value="1" selected>점검</option>
				<?php } else {?>
				<option value="1">점검</option>
				<?php } ?>
			</select>
		</div>
		<div class="confsite-site-text-div" style="margin-top: 10px;">
			<p style="font-size: 14px; padding:5px 0px;"> 점검내용</p>
			<textarea rows="16" id="confsite-maintain-text-id" style="width: 350px;"
			 ><?php if(!is_null($objConfig)) {  ?><?=$objConfig->conf_content?><?php } ?></textarea>					
		</div>

		<div class = "confsite-button-group">
			<button class="confsite-cancel-button" id="confsite-cancel-btn-id">취소</button>
			<button class="confsite-ok-button" id="confsite-ok-btn-id">저장</button>
		</div>
<?= $this->endSection() ?>

<?= $this->section('confsite-script') ?>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
    <script src="<?php echo site_furl('/assets/js/confmaintain-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/confmaintain-script.js?v=1');?>"></script>
<?php endif ?>
<?= $this->endSection() ?>