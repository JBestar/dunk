<?= $this->extend('/home/conf_game') ?>
<?= $this->section('confgame-active') ?><?=$game_name?><?= $this->endSection() ?>
<?= $this->section('confgame-content') ?>
		<!---->
		<h4><i class="glyphicon glyphicon-hand-right"></i> <?=$game_name?> 게임관련 설정</h4>	
		<div class="confsite-game-text-div">
			<p>유저 게임승인:</p> 
			<input type="checkbox" id="confpb-bet-check-id" style="zoom:120%; margin-top:4px;">
			<label style="font-size:13px; font-weight:normal; padding-top:0px;"> 유저게임승인</label>
		</div>
		<div class="confsite-game-text-div">
			<p> 운영상태</p>			
			<select class="pbresult-number-select" id="confpb-maintain-select-id" style="margin-left:0px; width: 200px;">
				<option value="0">운영</option>
				<option value="1">점검</option>
			</select>
		</div>
		<div class="confsite-game-text-div">
			<p> 노출상태</p>			
			<select class="pbresult-number-select" id="confpb-display-select-id" style="margin-left:0px; width: 200px;">
				<option value="0">보이기</option>
				<option value="1">감추기</option>
			</select>
		</div>
		<div class="confsite-game-text-div">
			<p>에이젼트 코드:</p> 
			<input type = "text" class="conf-text-input" style="min-width:200px;"  id="confpb-agent-code-id" disabled>
			
		</div>
		<div class="confsite-game-text-div">
			<p>에이젼트 보유알:</p> 
			<input type = "text" class="conf-text-input" style="min-width:200px;" id="confpb-agent-egg-id"  disabled>
			<button class="refresh_btn" title="조회" id="refresh_egg" style="margin-left:5px; margin-top:5px;"></button>
			<label style="font-size:13px; font-weight:normal; padding-top:0px; color:red;" id="err_msg"></label> 
		</div>
		<div class="confsite-game-text-div">
			<p>회원 보유알:</p> 
			<input type = "text" class="conf-text-input" style="min-width:200px;" id="confpb-user-egg-id"  disabled>
			<button class="refresh_btn" title="조회" id="refresh_useregg" style="margin-left:5px; margin-top:5px;"></button>
			<button class="recovery_btn" title="회수" id="recovery_useregg" style="margin-left:10px; margin-top:2px;"></button>
		</div>
		<div class="confsite-game-text-div">
			<p>에이젼트 페이지:</p>
			<?php if($game_id == GAME_CASINO_EVOL) :?>
				<button class="confsite-cancel-button" id="confsite-agent-btn-id" style="margin-bottom:20px; width:200px;">바로 가기</button>
			<?php endif ?>
		</div>
		
		<div class = "confsite-button-group" style="margin-top:50px;">
			<button class="confsite-cancel-button" id="confsite-cancel-btn-id">취소</button>
			<button class="confsite-ok-button"  id="confsite-ok-btn-id">저장</button>
		</div>
	
<?= $this->endSection() ?>

<?= $this->section('confgame-script') ?>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
    <script src="<?php echo site_furl('/assets/js/confcs-script.js?t='.time());?>"></script>
    <script src="<?php echo site_furl('/assets/js/confev-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/confcs-script.js?v=3');?>"></script>
    <script src="<?php echo site_furl('/assets/js/confev-script.js?v=1');?>"></script>
<?php endif ?>
<?= $this->endSection() ?>