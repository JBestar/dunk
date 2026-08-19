<?= $this->extend('header') ?>
<?= $this->section('content') ?>
<!--Sub Navbar-->
	<div class = "sub-navbar">
	<?php if(is_null($objRound)) {  ?>
		<p><i class="glyphicon glyphicon-book"></i> 게임결과::<?=$game_name?> 회차 등록</p>
	<?php } else {?>
		<p><i class="glyphicon glyphicon-book"></i> 게임결과::<?=$game_name?> 회차 수정</p>
	<?php } ?>		

	<?php if(is_null($objRound)) {  ?>
		<p id="subnavbar-fid-p-id" hidden>0</p>
	<?php } else {?>
		<p id="subnavbar-fid-p-id" hidden><?=$objRound->round_fid?></p>
	<?php } ?>
	</div>
<!--Site Setting-->
<div class="useredit-panel">

		<div class="useredit-text-div">
			<p>게임일짜:</p> 
			<?php if(is_null($objRound)) {  ?>	
			<input type = "date"   id="pbresult_edit-rounddate-input-id" value="<?php echo date('Y-m-d'); ?>">
			<?php } else {?>
			<input type = "date"   id="pbresult_edit-rounddate-input-id" value="<?=$objRound->round_date?>" disabled>
			<?php } ?>
		</div>
		<div class="useredit-text-div">
			<p>게임회차:</p> 
			<?php if(is_null($objRound)) {  ?>	
			<input type = "number"   id="pbresult_edit-roundnum-input-id">
			<?php } else {?>
			<input type = "number"   id="pbresult_edit-roundnum-input-id" value="<?=$objRound->round_num?>" disabled>
			<?php } ?>
			<label>회차</label>
		</div>
		<div class="useredit-text-div">
			<p>좌우:</p> 
			<select type = "text" id="pbresult_edit-lr-select-id">
				<?php if(is_null($objRound)){  ?>
				<option value="X"> </option>	
				<option value="P">좌</option>
				<option value="B">우</option>
				<?php } else if($objRound->round_result_1 == 'P'){?>
					<option value="X"> </option>	
				 	<option value="P" selected>좌</option>
					<option value="B">우</option>
				<?php } else if($objRound->round_result_1 == 'B'){?>
					<option value="X"> </option>	
				 	<option value="P" >좌</option>
					<option value="B" selected>우</option>
				<?php } else {?>
					<option value="X" selected> </option>	
				 	<option value="P">좌</option>
					<option value="B">우</option>				 
				<?php } ?>
				
			</select>
		</div>
		<div class="useredit-text-div">
			<p>줄수:</p> 
			<select type = "text" id="pbresult_edit-34-select-id">
				<?php if(is_null($objRound)){  ?>
				<option value="X"> </option>	
				<option value="P">3줄</option>
				<option value="B">4줄</option>
				<?php } else if($objRound->round_result_2 == 'P'){?>
					<option value="X"> </option>	
				 	<option value="P" selected>3줄</option>
					<option value="B">4줄</option>
				<?php } else if($objRound->round_result_2 == 'B'){?>
					<option value="X"> </option>	
				 	<option value="P" >3줄</option>
					<option value="B" selected>4줄</option>
				<?php } else {?>
					<option value="X" selected> </option>	
				 	<option value="P">3줄</option>
					<option value="B">4줄</option>				 
				<?php } ?>
				
			</select>
		</div>
		<div class="useredit-text-div">
			<p>홀짝:</p> 
			<select type = "text" id="pbresult_edit-oe-select-id">
				<?php if(is_null($objRound)){  ?>
				<option value="X"> </option>	
				<option value="P">홀</option>
				<option value="B">짝</option>
				<?php } else if($objRound->round_result_3 == 'P'){?>
					<option value="X"> </option>	
				 	<option value="P" selected>홀</option>
					<option value="B">짝</option>
				<?php } else if($objRound->round_result_3 == 'B'){?>
					<option value="X"> </option>	
				 	<option value="P" >홀</option>
					<option value="B" selected>짝</option>
				<?php } else {?>
					<option value="X" selected> </option>	
				 	<option value="P">홀</option>
					<option value="B">짝</option>				 
				<?php } ?>
				
			</select>
		</div>
		<div class = "useredit-button-group">
			<button class="useredit-cancel-button" id="pbresult_edit-cancel-btn-id">취소</button>
			<button class="useredit-ok-button"  id="pbresult_edit-save-btn-id">저장</button>
		</div>


	</div>



<!--main_navbar.php-->
</div>
<script> var mGameId = <?=$game_id?>; </script>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
	<script src="<?php echo site_furl('/assets/js/psresult_edit-script.js?t='.time());?>"></script>
<?php else : ?>
	<script src="<?php echo site_furl('/assets/js/psresult_edit-script.js?v=1');?>"></script>
<?php endif ?>
<?= $this->endSection() ?>