<?= $this->extend('/ebal/ebal_cont') ?>
<?= $this->section('ebal-active') ?><?=$game_name?><?= $this->endSection() ?>
<?= $this->section('eval_content') ?>
	<style>
		.confsite-game-text-div div{
			width:510px;			
		}
		.confsite-game-text-div p{
			width:150px;		
			margin:0 0 5px;	
		}
		.confsite-game-text-div label{
			margin-bottom:0px;
		}
		.confsite-game-text-div input[type="text"], .confsite-game-text-div input[type="number"]{
			min-width:200px;			
		}
	</style>
    <div class="confsite-site-panel">
    <h4><i class="glyphicon glyphicon-hand-right"></i> <?=$game_title?> 보험계정</h4>
	
	<?php if(isEBalMode()) :?>

		<div class="confsite-game-text-div">
			<div>
				<p>전체 보험배팅:</p> 
				<input type="checkbox" id="confev-bet-allcheck-id" style="zoom:120%; margin-top:0px;"><label style="font-size:14px; font-weight:normal; padding-top:0px;"> 배팅승인</label>
			</div>
			<div>
				<p> 배팅방식</p>			
				<select name="pbresult-number" class="" id="confev-betmode-select-id" style="padding:5px; width: 200px; margin-left:0px;">
					<option value="0" >기본배팅</option>
					<option value="1" >분산배팅</option>
				</select>
			</div>
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>최소배팅금액:</p> 
				<input type = "number" class="conf-text-input"  id="confev-betmin-input-id" min="0" step="1000"> 원
			</div>
			<div>
				<p>최대배팅금액:</p> 
				<input type = "number" class="conf-text-input"  id="confev-betmax-input-id" min="0" step="10000"> 원
				<?php if(array_key_exists('app.sess_act', $_ENV) && $_ENV['app.sess_act'] == 1) :?>
					<button class="confsite-cancel-button" style="margin-left:10px; padding:3px 10px; float:none;" id="confsite-betrange-reset-id">리셋</button>
				<?php endif ?>
			</div>
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>보험배팅마감:</p> 
				<input type = "number" class="conf-text-input"  id="confev-betend-input-id"> 초전
			</div>
			<div>
				<p>팅김방지 배팅간격:</p> 
				<input type = "number" class="conf-text-input" min="0" step="1" id="confev-conbet-input-id"> 분 (1~30)
			</div>
			
		</div>
		<div class="confsite-game-text-div">
			<div>
				<p>최대 접속자수:</p> 
				<input type = "number" class="conf-text-input" min="0" step="1" id="confev-maxuser-input-id"> 명 
				<div style="padding-left:150px;">
					(<span id="confev-conuser-span-id"> </span>
					<span id="confev-follower-span-id"> </span>)
				</div>
			</div>
			<?php if(array_key_exists('app.sess_act', $_ENV) && $_ENV['app.sess_act'] == 1) :?>
				<div>
					<p>전체 누르기:</p> 
					<button class="confsite-cancel-button" style="margin-left:0px; padding:3px 10px; float:none;" id="confsite-press-check-id">누르기체크</button>
					<button class="confsite-cancel-button" style="margin-left:10px; padding:3px 10px; float:none;" id="confsite-press-reset-id">누르기해제</button>
				</div>
			<?php endif ?>
		</div>
		<?php for($i = 1 ; $i <= $ebal_cnt ; $i ++):?>
			<!-- 보험계정 1 -->
			<div class="confsite-game-text-div">
				<p style="font-size: 15px; font-weight: bold; margin-bottom:1px; padding:0;">보험계정 <?=($i)?></p>
			</div>
			<div class="confsite-game-text-div">
				<div>
					<p style="padding:5px 5px 0 5px; margin:0;">보험배팅승인:</p> 
					<input type="checkbox" id="confev-bet-check-id<?=($i)?>" style="zoom:120%; margin-top:0px;">
					<label style="font-size:14px; font-weight:normal; padding-top:0px; position:relative; top:-3px;"> 배팅승인</label>
					<input type="checkbox" id="confev-signal-check-id<?=($i)?>" style="zoom:120%; margin-top:5px; margin-left:18px;">
					<label style="font-size:14px; font-weight:normal; padding-top:0px;"> 신호값용</label>
					
						<!-- <input type="checkbox" id="confev-multiroom-check-id<?=($i)?>" style="zoom:120%; margin-top:5px; margin-left:10px;" <?=$mb_level<LEVEL_ADMIN+2?'hidden':''?>>
						<label style="font-size:14px; font-weight:normal; padding-top:0px;" <?=$mb_level < LEVEL_ADMIN+2?'hidden':''?>> 멀티방</label> -->

						<select name="pbresult-number" class="" id="confev-multiroom-check-id<?=($i)?>" <?=$mb_level < LEVEL_ADMIN+2?'hidden':''?> style="padding:2px; width: 70px; margin-left:0px;">
							<option value="0" >개별방</option>
							<option value="1" >멀티방</option>
							<option value="2" >혼합방</option>
						</select>

				</div>
				<div>
					<p style="padding:5px 5px 0 5px; margin:0;">팅김방지 배팅:</p> 
					<input type="checkbox" id="confev-conbet-check-id<?=($i)?>" style="zoom:120%; margin-top:5px;">
					<label style="font-size:14px; font-weight:normal; padding-top:0px; position:relative; top:-4px;"> 배팅승인</label>
					<input type="checkbox" id="confev-conallbet-check-id<?=($i)?>" style="zoom:120%; margin-top:5px; margin-left:30px;" <?=$mb_level<LEVEL_ADMIN+2?'hidden':''?>>
					<label style="font-size:14px; font-weight:normal; padding-top:0px; position:relative; top:-4px;" <?=$mb_level < LEVEL_ADMIN+2?'hidden':''?>> 전체방</label>
				</div>
			</div>
			<div class="confsite-game-text-div">
				<div>
					<p> 보험타입</p>			
					<select name="pbresult-number" class="" id="confev-bettype-select-id<?=($i)?>" style="padding:5px; width: 200px; margin-left:0px;">
						<?php foreach($gamd_types as $type=>$name):?>
							<option value="<?=$type?>" ><?=$name?></option>
						<?php endforeach?>
					</select>
				</div>
				<div>
					<p>사이트 주소:</p> 
					<input type = "text" class="conf-text-input" id="confev-betsite-input-id<?=($i)?>">
				</div>
			</div>
			<div class="confsite-game-text-div">
				<div>
					<p>계정 아이디:</p> 
					<input type = "text" class="conf-text-input"  id="confev-userid-input-id<?=($i)?>">
				</div>
				<div>
					<p>계정 비밀번호:</p> 
					<input type = "text" class="conf-text-input"  id="confev-userpwd-input-id<?=($i)?>">
				</div>
				
			</div>
			<div class="confsite-game-text-div" id="confev-captcha-div-id<?=($i)?>" style="display:none;">
				<div>
					<p>로그인 코드:</p> 
					<img  id="confev-captcha-img-id<?=($i)?>" src="" style="width: 120px;">
					<input type = "text" style="margin-left:5px; min-width:50px; width:70px;" class="conf-text-input"  id="confev-captcha-input-id<?=($i)?>">
					<button class="confsite-cancel-button" style="margin-left:3px; padding:3px 10px; float:none;  margin-bottom:12px;" onclick="saveCaptcha(<?=($i)?>)">저장</button>
				</div>
			</div>
			<div class="confsite-game-text-div">
				<div>
					<p>보험알:</p> 
					<input type = "text" class="conf-text-input"  id="confev-balance-input-id<?=($i)?>" disabled> 
					<label style="font-size:14px; font-weight:normal; padding-top:0px; min-width:100px;"  id="confev-balance-label-id<?=($i)?>"></label>
				</div>
				<div>
					<p>배팅한도:</p> 
					<input type = "number" class="conf-text-input"  id="confev-balmax-input-id<?=($i)?>" min="0" step="1000">
				</div>
			</div>
		<?php endfor?>
	<?php endif ?>
		
		<div class = "confsite-button-group">
			<button class="confsite-cancel-button"  id="confsite-cancel-btn-id">취소</button>
			<button class="confsite-ok-button" id="confsite-ok-btn-id">저장</button>
		</div>
    </div>
<!--main_navbar.php-main-container-->
</div>

<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
    <script src="<?php echo site_furl('/assets/js/conf_ebal-script.js?v=2');?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/conf_ebal-script.js?t='.time());?>"></script>
<?php endif ?>

<?= $this->endSection() ?>
