<?= $this->extend('/home/conf_site') ?>
<?= $this->section('confsite-active') ?>알람설정<?= $this->endSection() ?>

<?= $this->section('confsite-navbar') ?>
<?= $this->endSection() ?>

<?= $this->section('confsite-content') ?>
		<!---->
		<h4><i class="glyphicon glyphicon-hand-right"></i> 알람설정</h4>
		
		<div class="confother-sound-div">
			<label>신규회원 알림음:</label> 
			<select name="pbresult-number" class="pbresult-number-select" id="confsound-alarm1-select-id" style="width: 150px; float: left;">
				<option value="sound_reg.mp3">신규알림음</option>
				<?php foreach($sounds as $fName=>$sName):?>
					<option value="<?=$fName?>" ><?=$sName?></option>
				<?php endforeach?>	
			</select>
			<audio id="confsound-alarm1-audio-id" controls>
			  <source id="confsound-alarm1-source-id" >
			  Your browser does not support the audio element.
			</audio>
		</div>
		
		<div class="confother-sound-div">
			<label>머니충전 알림음:</label> 
			<select name="pbresult-number" class="pbresult-number-select" id="confsound-alarm2-select-id" style="width: 150px; float: left;">
				<option value="sound_dep.mp3">신규알림음</option>
				<?php foreach($sounds as $fName=>$sName):?>
					<option value="<?=$fName?>" ><?=$sName?></option>
				<?php endforeach?>	
			</select>
			<audio id="confsound-alarm2-audio-id" controls>
			  
			  <source id="confsound-alarm2-source-id" >
			  Your browser does not support the audio element.
			</audio>
			
		</div>

		<div class="confother-sound-div">
			<label>머니환전 알림음:</label> 
			<select name="pbresult-number" class="pbresult-number-select" id="confsound-alarm3-select-id" style="width: 150px; float: left;">
				<option value="sound_wit.mp3">신규알림음</option>
				<?php foreach($sounds as $fName=>$sName):?>
					<option value="<?=$fName?>" ><?=$sName?></option>
				<?php endforeach?>	
			</select>
			<audio id="confsound-alarm3-audio-id" controls>
			  
			  <source id="confsound-alarm3-source-id" >
			  Your browser does not support the audio element.
			</audio>
			
		</div>

		<div class="confother-sound-div">
			<label>새쪽지 알림음:</label> 
			<select name="pbresult-number" class="pbresult-number-select" id="confsound-alarm4-select-id" style="width: 150px; float: left;">
				<option value="sound_msg.mp3">신규알림음</option>
				<?php foreach($sounds as $fName=>$sName):?>
					<option value="<?=$fName?>" ><?=$sName?></option>
				<?php endforeach?>	
			</select>
			<audio id="confsound-alarm4-audio-id" controls>
			  
			  <source id="confsound-alarm4-source-id" >
			  Your browser does not support the audio element.
			</audio>
		</div>

		
		<div class="confother-sound-div">
			<label>에볼루션 알림음:</label> 
			<select name="pbresult-number" class="pbresult-number-select" id="confsound-alarm5-select-id" style="width: 150px; float: left;">
				<option value="sound_msg.mp3">신규알림음</option>
				<?php foreach($sounds as $fName=>$sName):?>
					<option value="<?=$fName?>" ><?=$sName?></option>
				<?php endforeach?>	
			</select>
			<audio id="confsound-alarm5-audio-id" controls>
			  
			  <source id="confsound-alarm5-source-id" >
			  Your browser does not support the audio element.
			</audio>
		</div>

		<div class = "confsite-button-group">
			<button class="confsite-cancel-button"  id="confsite-cancel-btn-id">취소</button>
			<button class="confsite-ok-button" id="confsite-ok-btn-id">저장</button>
		</div>
	
<?= $this->endSection() ?>

<?= $this->section('confsite-script') ?>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
    <script src="<?php echo site_furl('/assets/js/confsound-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/confsound-script.js?v=1');?>"></script>
<?php endif ?>
<?= $this->endSection() ?>