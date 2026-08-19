<?= $this->extend('header') ?>
<?= $this->section('content') ?>

<!--Sub Navbar-->
<div class="sub-navbar">
	<p><i class="glyphicon glyphicon-lock"></i> 기본설정::따라가기</p>
</div>
<!--Site Setting-->
<div class="confsite-password-panel">
	<div class="confsite-password-text-div" style="margin-top:20px">
		<p>따라가기 상태:</p>
		<input type="checkbox" id="confsite-follow-check-id" style="width:20px; zoom:120%;" <?= $mb_follow_check? "checked":""?>>
		<p style="width:200px;">승인</p>
	</div>

	<div class="confsite-password-text-div" style="margin-top:20px;">
		<p>따라가기 아이디:</p> <input type="text" id="confsite-follow-input-id" style="width:150px;" value="<?= $mb_follow_id?>">
	</div>

	<div class="confsite-password-text-div" style="margin-top:20px">
		<p>따라가기 금액(%):</p> 
		<select type="text" id="confsite-follow-percent-id" style="padding: 5px 5px; border: none; width:150px;">
			<?php $rates = followRates(); foreach($rates as $rate):?>
				<option value="<?=$rate?>" <?=$mb_follow_percent==$rate?'selected':''?>><?=$rate?> %</option>
			<?php endforeach?>
		</select>
	</div>

	<div class="confsite-button-group">
		<button class="confsite-cancel-button" id="confsite-cancel-btn-id">취소</button>
		<button class="confsite-ok-button" id="confsite-ok-btn-id">저장</button>
	</div>
</div>


<!--main_navbar.php-->
</div>

<?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
    <script src="<?php echo site_furl('/assets/js/conf_follow-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/conf_follow-script.js?v=1');?>"></script>
<?php endif ?>
<?= $this->endSection() ?>