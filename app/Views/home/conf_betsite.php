<?= $this->extend('/home/conf_site') ?>
<?= $this->section('confsite-active') ?>보험설정<?= $this->endSection() ?>

<?= $this->section('confsite-navbar') ?>
<?= $this->endSection() ?>

<?= $this->section('confsite-content') ?>
		<!---->
		<h4><i class="glyphicon glyphicon-hand-right"></i> 미니게임 보험계정</h4>
		<div class="confsite-game-text-div">
			<p>보험배팅승인:</p> 
			<input type="checkbox" id="confpb-bet-check-id" style="zoom:120%; margin-top:0px;"><label style="font-size:14px; font-weight:normal; padding-top:0px;"> 배팅승인</label>
		</div>
		<div class="confsite-game-text-div">
			<p> 보험타입</p>			
			<select name="pbresult-number" class="" id="conf-bettype-select-id" style="padding:5px; width: 200px; margin-left:0px;">
				<option value="0" >OUR</option>
				<option value="1" >GALAXY</option>
				<option value="2" >WOORI</option>
				<option value="3" >BEST</option>
				<option value="4" >샵</option>
			</select>
		</div>
		<div class="confsite-game-text-div">
			<p>도메인 주소:</p> 
			<input type = "text" class="conf-text-input" style="min-width:200px;" id="conf-betsite-input-id">
		</div>
		<div class="confsite-game-text-div">
			<p>계정 아이디:</p> 
			<input type = "text" class="conf-text-input"  style="min-width:200px;" id="conf-userid-input-id">
		</div>
		
		<div class="confsite-game-text-div">
			<p id="conf-pw-label">계정 비밀번호:</p> 
			<input type = "text" class="conf-text-input"  style="min-width:200px;" id="conf-userpwd-input-id">
		</div>
		<div class = "confsite-button-group">
			<button class="confsite-cancel-button"  id="confsite-cancel-btn-id">취소</button>
			<button class="confsite-ok-button" id="confsite-ok-btn-id">저장</button>
		</div>
		
<?= $this->endSection() ?>

<?= $this->section('confsite-script') ?>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
    <script src="<?php echo site_furl('/assets/js/conf_betsite-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/conf_betsite-script.js?v=1');?>"></script>
<?php endif ?>
<?= $this->endSection() ?>