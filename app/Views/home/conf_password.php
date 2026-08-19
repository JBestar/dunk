<?= $this->extend('header') ?>
<?= $this->section('content') ?>

<!--Sub Navbar-->
<div class="sub-navbar">
	<p><i class="glyphicon glyphicon-lock"></i> 기본설정::정보변경</p>
</div>
<!--Site Setting-->
<div class="confsite-password-panel">
	<?php if ($mb_level >= LEVEL_ADMIN) {  ?>
	<div class="confsite-password-text-div" style="margin-top:20px">
		<p>접속 아이피검사:</p>
		<input type="checkbox" id="confsite-ip-check-id" style="width:20px; zoom:120%;">
		<p style="width:200px;">로그인시 아이피 검사</p>
	</div>
	<div class="confsite-password-text-div">
		<p>접속 아이피:</p> 
		<input type="text" id="confsite-ip-input-id" placeholder="X.X.X.X" pattern="^([0-9a-fA-F]{1,4}:){7}[0-9a-fA-F]{1,4}$" style="width:120px; margin-right:5px;">
		<input type="text" id="confsite-ip2-input-id" placeholder="X.X.X.X" pattern="^([0-9a-fA-F]{1,4}:){7}[0-9a-fA-F]{1,4}$" style="width:120px; margin-right:5px;">
		<input type="text" id="confsite-ip3-input-id" placeholder="X.X.X.X" pattern="^([0-9a-fA-F]{1,4}:){7}[0-9a-fA-F]{1,4}$" style="width:120px; margin-right:5px;">
		<input type="text" id="confsite-ip4-input-id" placeholder="X.X.X.X" pattern="^([0-9a-fA-F]{1,4}:){7}[0-9a-fA-F]{1,4}$" style="width:120px; margin-right:5px;">
		<input type="text" id="confsite-ip5-input-id" placeholder="X.X.X.X" pattern="^([0-9a-fA-F]{1,4}:){7}[0-9a-fA-F]{1,4}$" style="width:120px; margin-right:5px;">
	</div>
	<div class="confsite-password-text-div">
		<p>보유금액:</p> <input type="text" id="confsite-egg-input-id" disabled>
		<button class="recovery_btn" title="알회수" id="recovery_useregg" style="margin-left:10px; margin-top:2px; display:none;"></button>

		<input type="number" id="confsite-money-input-id" placeholder="금액입력" style="width:115px; margin-left:5px;">
		<button class="pbresult-list-view-but" id="confsite-give-but-id" style="margin-left:5px; margin-right:0px; margin-top:-2px;">지급</button>  
		<button class="pbresult-list-view-but" id="confsite-collect-but-id"  style="margin-left:5px; margin-top:-2px;">회수</button> 
	</div>
	<?php } ?>
	<div class="confsite-password-text-div" style="margin-top:20px">
		<p>현재 비밀번호:</p> <input type="password" id="confsite-password-input-id">
	</div>
	<div class="confsite-password-text-div">
		<p>새 비밀번호:</p> <input type="password" id="confsite-password-new-input-id" class="">
	</div>
	<div class="confsite-password-text-div">
		<p>새 비밀번호 확인:</p> <input type="password" id="confsite-password-newok-input-id" class="">
	</div>
	<div class="confsite-button-group">
		<button class="confsite-cancel-button" id="confsite-cancel-btn-id">취소</button>
		<button class="confsite-ok-button" id="confsite-ok-btn-id">저장</button>
	</div>
</div>



<!--main_navbar.php-->
</div>

<?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
    <script src="<?php echo site_furl('/assets/js/member_common-script.js?t='.time());?>"></script>
    <script src="<?php echo site_furl('/assets/js/confpwd-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/member_common-script.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/confpwd-script.js?v=2');?>"></script>
<?php endif ?>
<?= $this->endSection() ?>