<?= $this->extend('/home/conf_game') ?>
<?= $this->section('confgame-active') ?><?=$game_name?><?= $this->endSection() ?>
<?= $this->section('confgame-content') ?>
	 	<!---->
		<h4><i class="glyphicon glyphicon-hand-right"></i> <?=$game_name?> 게임관련 설정</h4>	
		
		<div class="confsite-game-text-div">
			<p>유저 배팅승인:</p> 
			<input type="checkbox" id="confpb-bet-check-id"  style="zoom:120%; margin-top:4px;">
			<label style="font-size:13px; font-weight:normal; padding-top:0px;"> 유저배팅승인</label>
		</div>
		<div class="confsite-game-text-div">
			<p>배팅 마감시간:</p> 
			<input type = "number" class="conf-seconds-input" id="confpb-endsec-input-id"><label> 초</label>
		</div>
		
		<div class="confsite-game-text-div">
		<p style="font-size: 16px; font-weight: bold;"><?=$game_name?> 단폴</p>
  		</div>
		<div class="confsite-game-text-div">
			<p> 좌 :: 우 배당율</p> 
			<input type = "text" class="conf-text-input" id="confpb-ratio1-input-id">
		</div>
		<div class="confsite-game-text-div">
			<p> 3줄 :: 4줄 배당율</p> 
			<input type = "text" class="conf-text-input" id="confpb-ratio2-input-id">
		</div>
		<div class="confsite-game-text-div">
			<p> 홀 :: 짝 배당율</p> 
			<input type = "text" class="conf-text-input"  id="confpb-ratio3-input-id">
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>최소배팅금액:</p>
				<input type="number" class="conf-text-input" id="confpb-minmoney-input-id"><label> 원</label>
			</div>
			<div>
				<p>최대배팅금액:</p>
				<input type="number" class="conf-text-input" id="confpb-maxmoney-input-id"><label> 원</label>
  			</div>
  		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>최대적중금액:</p>
				<input type="number" class="conf-text-input" id="confpb-winmoney-input-id"><label> 원</label>
			</div>
			<?php if(!$gameper_full) :?>
			<div>
				<p>누르기율:</p> 
				<input type = "number" class="conf-text-input"  id="confpb-percent-input-id"><label> %</label>
			</div>
			<?php endif?>
		</div>
		<div class="confsite-game-text-div">
  			<p style="font-size: 16px; font-weight: bold;"><?=$game_name?> 조합</p>

  		</div>
		  <div class="confsite-game-text-div">
  			<div>
  				<p> 좌3 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio4-input-id">
  			</div>
  			<div>
  				<p> 좌4 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio5-input-id">
  			</div>
  		</div>
  		<div class="confsite-game-text-div">
  			<div>
  				<p> 우3 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio6-input-id">
  			</div>
  			<div>
  				<p> 우4 배당율</p>
  				<input type="text" class="conf-text-input" id="confpb-ratio7-input-id">
  			</div>
  		</div>
		  <div class="confsite-game-text-div">
			<div>
				<p>최소배팅금액:</p>
				<input type="number" class="conf-text-input" id="confpb-min2money-input-id"><label> 원</label>
			</div>
			<div>
				<p>최대배팅금액:</p>
				<input type="number" class="conf-text-input" id="confpb-max2money-input-id"><label> 원</label>
			</div>
  		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>최대적중금액:</p>
				<input type="number" class="conf-text-input" id="confpb-win2money-input-id"><label> 원</label>
			</div>
			<?php if(!$gameper_full) :?>
			<div>
				<p>누르기율:</p> 
				<input type = "number" class="conf-text-input"  id="confpb-percent2-input-id"><label> %</label>
			</div>
			<?php endif?>
		</div>
		<div class = "confsite-button-group" style="margin-top:50px;">
			<button class="confsite-cancel-button" id="confsite-cancel-btn-id">취소</button>
			<button class="confsite-ok-button"  id="confsite-ok-btn-id">저장</button>
		</div>
	<?= $this->endSection() ?>

<?= $this->section('confgame-script') ?>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
    <script src="<?php echo site_furl('/assets/js/confps-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/confps-script.js?v=1');?>"></script>
<?php endif ?>
<?= $this->endSection() ?>