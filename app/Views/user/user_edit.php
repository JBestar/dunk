<?= $this->extend('user/header') ?>
<?= $this->section('content') ?>
  	<!--Sub Navbar-->
	<div class = "sub-navbar">
		<?php if(is_null($objMember)) {  ?>
		<p><i class="glyphicon glyphicon-user"></i> 회원관리::<?= $this->renderSection('user-edit-title') ?> 등록</p>
		<?php } else {?>
			<p><i class="glyphicon glyphicon-user"></i> 회원관리::<?= $this->renderSection('user-edit-title') ?> 수정</p>
		<?php } ?>
		<p id="subnavbar-emplevel-p-id" hidden><?=$mb_level?></p>
		<?php if(is_null($objMember)) {  ?>
			<p id="subnavbar-fid-p-id" hidden>0</p>
		<?php } else {?>
			<p id="subnavbar-fid-p-id" hidden><?=$objMember->mb_fid?></p>
		<?php } ?>
	</div>
	<!--Site Setting-->
	<div class="useredit-panel">
		<!---->
		<?= $this->renderSection('user-edit-form-section0') ?>		
		<!---->
		<div class="useredit-text-div">
			<p><?= $this->renderSection('user-edit-title') ?> 아이디:</p> 
			<?php if(is_null($objMember)) {  ?>	
			<input type = "text"   id="useredit-id-input-id" class="english_s">
			<?php } else {?>
			<input type = "text"   id="useredit-id-input-id" class="english_s" value="<?=$objMember->mb_uid ?>" disabled>
			<?php } ?>
			<label>(4~16자 영문,숫자만 가능)</label>
		</div>
		<!---->
		<div class="useredit-text-div">
			<p><?= $this->renderSection('user-edit-title') ?> 닉네임:</p> 
			<?php if(is_null($objMember)) {  ?>	
			<input type = "text" id="useredit-nickname-input-id">
			<?php } else {?>
				<?php if($mb_level > LEVEL_COMPANY) {  ?>
				<input type = "text" id="useredit-nickname-input-id" value="<?=$objMember->mb_nickname?>" disabled>
				<?php } else {?>
				<input type = "text" id="useredit-nickname-input-id" value="<?=$objMember->mb_nickname?>" disabled>
				<?php } ?>		
			<?php } ?>
			<label>(길이 2~20자)</label>
		</div>
		<!---->
        <?php $this->renderSection('user-edit-check-level')  ?>
		<?php if ($mb_level >= LEVEL_ADMIN) {?>
		<div class="useredit-text-div">
			<p>비밀번호:</p> 
			<?php if(is_null($objMember)) {  ?>	
			<input type = "text" id="useredit-pwd-input-id" class="">
			<?php } else {?>
			<input type = "text" id="useredit-pwd-input-id" class="" value="<?=$objMember->mb_pwd?>" >
			<?php } ?>
			<label>(8~20자, 특수문자 한개 이상)</label>
		</div>
		<?php }?>
		<!---->
        <?= $this->renderSection('user-edit-form-section1') ?>
		<!---->
		<?php if ($mb_level >= LEVEL_ADMIN) {?>
		<div class="useredit-text-div">
			<p>핸드폰번호:</p> 
			<?php if(is_null($objMember)) {  ?>			
			<input type = "text" id="useredit-phone-input-id">
			<?php } else {?>
			<input type = "text" id="useredit-phone-input-id" value="<?=$objMember->mb_phone?>" >
			<?php } ?>
			<label>(-없이 숫자만입력)</label>
		</div>
		<!---->
		<div class="useredit-text-div">
			<p>은행명:</p> 
			<?php if(is_null($objMember)) {  ?>			
			<input type = "text" id="useredit-bankname-input-id">
			<?php } else if(is_null($objMember->mb_bank_name)) {  ?>
			<input type = "text" id="useredit-bankname-input-id">
			<?php } else {?>
			<input type = "text" id="useredit-bankname-input-id" value="<?=$objMember->mb_bank_name?>" >
			<?php } ?>
		</div>
		<!---->
		<div class="useredit-text-div">
			<p>예금주:</p> 
			<?php if(is_null($objMember)) {  ?>			
			<input type = "text" id="useredit-bankaccount-input-id">
			<?php } else if(is_null($objMember->mb_bank_own)) {  ?>
			<input type = "text" id="useredit-bankaccount-input-id">
			<?php } else {?>
			<input type = "text" id="useredit-bankaccount-input-id" value="<?=$objMember->mb_bank_own?>" >
			<?php } ?>
		</div>
		<!---->
		<div class="useredit-text-div">
			<p>계좌번호:</p> 
			<?php if(is_null($objMember) || is_null($objMember->mb_bank_num)) {  ?>						
			<input type = "text" id="useredit-bankserial-input-id">
			<?php } else {?>
			<input type = "text" id="useredit-bankserial-input-id" value="<?=$objMember->mb_bank_num?>" >
			<?php } ?>
			<label>(-없이 숫자만입력)</label>
		</div>
		<!---->
		<div class="useredit-text-div">
			<p>출금비번:</p> 
			<?php if(is_null($objMember) || is_null($objMember->mb_bank_pwd)) {  ?>	
			<input type = "text" id="useredit-bankpwd-input-id">
			<?php } else {?>
			<input type = "text" id="useredit-bankpwd-input-id" value="<?=$objMember->mb_bank_pwd?>">
			<?php } ?>
		</div>
		<?php }?>
        <?= $this->renderSection('user-edit-form-section2') ?>
		
		<?php if(!$pbg_deny || !$bpg_deny || !$coin5_deny || !$coin3_deny || !$eos5_deny || !$eos3_deny) :?>

			<div class="useredit-percent-div">
				<p>미니게임 단폴:</p> 
				
				<label> 배당율(%)</label>
				<?php if(is_null($objMember)) {  ?>
				<input type = "number" step="0.1" id="useredit-pbbetrate-input-id" value="0" >
				<?php } else {?>
				<input type = "number" step="0.1" id="useredit-pbbetrate-input-id" value="<?=$objMember->mb_game_pb_ratio?>">
				<?php } ?>

				<?php if(!$gameper_full) :?>
					<label> 누르기율(%)</label>
					<?php if(is_null($objMember)) {  ?>
					<input type = "number" step="1" id="useredit-pbbetpercent-input-id" value="100">
					<?php } else {?>
					<input type = "number" step="1" id="useredit-pbbetpercent-input-id" value="<?=$objMember->mb_game_pb_percent?>">
					<?php } ?>
				<?php endif?>
			</div>
			<div class="useredit-percent-div">
				<p>미니게임 조합:</p> 
				
				<label> 배당율(%)</label>
				<?php if(is_null($objMember)) {  ?>
				<input type = "number" step="0.1" id="useredit-pbbetrate2-input-id"  value="0">
				<?php } else {?>
				<input type = "number" step="0.1" id="useredit-pbbetrate2-input-id" value="<?=$objMember->mb_game_pb2_ratio?>">
				<?php } ?>

				<?php if(!$gameper_full) :?>
					<label> 누르기율(%)</label>
					<?php if(is_null($objMember)) {  ?>
					<input type = "number" step="1" id="useredit-pbbetpercent2-input-id" value="100">
					<?php } else {?>
					<input type = "number" step="1" id="useredit-pbbetpercent2-input-id" value="<?=$objMember->mb_game_pb2_percent?>">
					<?php } ?>
				<?php endif?>
			</div>
		<?php endif ?>   
		<?php if(isEBalMode() || !$evol_deny || !$cas_deny) :?>
			<div class="useredit-percent-div">
				<p>카지노:</p>			 
				<label> 배당율(%)</label>
				<?php if(is_null($objMember)) {  ?>
				<input type = "number" step="0.1" id="useredit-evbetrate-input-id" value="0" >
				<?php } else {?>
				<input type = "number" step="0.1" id="useredit-evbetrate-input-id" value="<?=$objMember->mb_game_cs_ratio?>">
				<?php } ?>
			</div>
		<?php endif ?>   
		<?php if(!$slot_deny) :?>
			<div class="useredit-percent-div">
				<p>슬롯:</p>			 
				<label> 배당율(%)</label>
				<?php if(is_null($objMember)) {  ?>
				<input type = "number" step="0.1" id="useredit-slbetrate-input-id" value="0" >
				<?php } else {?>
				<input type = "number" step="0.1" id="useredit-slbetrate-input-id" value="<?=$objMember->mb_game_sl_ratio?>">
				<?php } ?>
			</div>
		<?php endif ?>   
		<?php if(!$hold_deny) :?>
			<div class="useredit-percent-div">
				<p>홀덤:</p>			 
				<label> 배당율(%)</label>
				<?php if(is_null($objMember)) {  ?>
				<input type = "number" step="0.1" id="useredit-hlbetrate-input-id" value="0" >
				<?php } else {?>
				<input type = "number" step="0.1" id="useredit-hlbetrate-input-id" value="<?=$objMember->mb_game_hl_ratio?>">
				<?php } ?>
			</div>
		<?php endif ?> 
		<?php $this->renderSection('user-edit-check-level')  ?>
		<?php if ($mb_level >= LEVEL_ADMIN) { ?>
        
			<div class="useredit-percent-div">
				
				<?php if(isEBalMode()) :?>

					<?php if(!is_null($objMember) && $objMember->mb_state_view == 1) :  ?>
						<input type="checkbox" id="useredit-balance-check-id" style="zoom:140%; margin-top:4px; margin-right:0; width:20px;" checked>
					<?php else :  ?>
						<input type="checkbox" id="useredit-balance-check-id" style="zoom:140%; margin-top:4px; margin-right:0; width:20px;" >
					<?php endif ?>
					<p style="width:133px">바카라 누르기</p> 

					<label> 최소배팅금액</label>
					<?php if(is_null($objMember)) : ?>
						<input type = "text" id="useredit-rangemin-input-id" value="0">
					<?php else :?>
						<input type = "text" id="useredit-rangemin-input-id" value="<?=number_format($objMember->mb_range_min)?>" >
					<?php endif ?>

					<label> 최대배팅금액</label>
					<?php if(is_null($objMember)) :  ?>
						<input type = "text" id="useredit-rangemax-input-id" value="0">
					<?php else :?>
						<input type = "text" id="useredit-rangemax-input-id" value="<?=number_format($objMember->mb_range_max)?>" >
					<?php endif ?>
					
					<?php if($press_en > 0 ) :?>
						</div>
						<div class="useredit-percent-div">
							<p></p> 
							<?php if(!is_null($objMember) && $objMember->mb_press_active == 1) :  ?>
								<input type="checkbox" id="useredit-press-check-id" style="zoom:140%; margin-top:4px; margin-right:0; width:50px;" checked>
							<?php else :  ?>
								<input type="checkbox" id="useredit-press-check-id" style="zoom:140%; margin-top:4px; margin-right:0; width:50px;" >
							<?php endif ?>

							<?php if(is_null($objMember)) :  ?>
								<input type = "text" id="useredit-press-input-id" value="0" style="margin-right:5px">
							<?php else :?>
								<input type = "text" id="useredit-press-input-id" value="<?=number_format($objMember->mb_press_amount)?>" style="margin-right:5px">
							<?php endif ?>

							<?php if($press_en == 2 || $press_en == 4 || $press_en == 5 ) :?>
								<label>이하</label>
							<?php endif ?>
							<button class="pbresult-list-view-but" id="useredit-press-but-id"  style="margin-left:5px; padding:5px 10px;">전체 적용</button>  

					<?php endif ?>

					<?php if($follow_en > 0 ) :?>
						</div>
						<div class="useredit-percent-div">
							<?php if(!is_null($objMember) && $objMember->mb_follow_active == 1) :  ?>
								<input type="checkbox" id="useredit-follow-check-id" style="zoom:140%; margin-top:4px; margin-right:0; width:20px;" checked>
							<?php else :  ?>
								<input type="checkbox" id="useredit-follow-check-id" style="zoom:140%; margin-top:4px; margin-right:0; width:20px;" >
							<?php endif ?>
							<p style="width:133px">바카라 따라가기</p> 
							<label style="text-align:right; width:89px;"> 아이디</label>
							<?php if(is_null($objMember)) : ?>
							<input type = "text" id="useredit-follow-input-id" value="">
							<?php else :?>
							<input type = "text" id="useredit-follow-input-id" value="<?=$objMember->mb_follow_id?>">
							<?php endif ?>

							<label style="text-align:right; width:89px;"> 금액(%)</label>
							<select type="text" id="useredit-follow-percent-id" style="padding: 5px 5px; border: none; width:150px;">
							<?php $rates = followRates(); if (is_null($objMember)) : ?>
								<?php foreach($rates as $rate):?>
									<option value="<?=$rate?>" <?=$rate==100?'selected':''?>><?=$rate?> %</option>
								<?php endforeach?>
							<?php else :?>
								<?php foreach($rates as $rate):?>
									<option value="<?=$rate?>" <?=$objMember->mb_follow_percent==$rate?'selected':''?>><?=$rate?> %</option>
								<?php endforeach?>
							<?php endif?>
							</select>

					<?php endif ?>
				<?php else: ?>
					<?php if(!is_null($objMember) && $objMember->mb_state_delete == 1) :  ?>
						<input type="checkbox" id="useredit-offline-check-id" style="zoom:140%; margin-top:4px; width:20px; margin-right:5px; " checked>
					<?php else :  ?>
						<input type="checkbox" id="useredit-offline-check-id" style="zoom:140%; margin-top:4px; width:20px; margin-right:5px;" >
					<?php endif ?>
					<p>오프라인 유저</p> 
				<?php endif ?>

			</div>

			<div class="useredit-text-div" style="padding-top:10px">
				<p style="">메모:</p> 
				<textarea rows="10" id="useredit-memo-text-id" style="width:510px; resize: vertical;" ><?php if(!is_null($objMember)) : echo $objMember->mb_memo ?><?php endif ?></textarea>					
			</div>

			<!---->
			<p class="useredit-seperate-div"></p>
					
			<div class="useredit-text-div">
				<p>보유금액:</p> 
				<?php if(is_null($objMember)) {  ?>	
				<input type = "text" id="useredit-money-input-id">
				<label>원</label>
				<?php } else {?>
				<input type = "text" id="useredit-money-input-id" value="<?=num_format(allMoney($objMember), NUM_POINT_CNT)?>" disabled>
				<label>원</label>
				<?php } ?>
			</div>
			<!---->
			<div class="useredit-text-div">
				<p>보유포인트:</p> 
				<?php if(is_null($objMember)) {  ?>	
				<input type = "text" id="useredit-point-input-id">
				<label>P</label>
				<?php } else {?>
				<input type = "text" id="useredit-point-input-id" value="<?=num_format($objMember->mb_point, NUM_POINT_CNT)?>" disabled>
				<label>P&nbsp;</label>
				<button class="pbresult-list-view-but" id="useredit-change-point-id" style="margin:0px;">전환</button>  
				<button class="pbresult-list-view-but" id="useredit-withdraw-point-id">회수</button>  
				<?php } ?>
				
			</div>

			<?php if(!is_null($objMember)) :  ?>	
				<div class="useredit-text-div">
					<p>충환전금액:</p> 
					<input type = "text" id="useredit-transfer-input-id" value="0" >
					<label>원</label>
				</div>
				<div class="useredit-text-div">
					<button class="pbresult-money-but" style="margin-left:180px;" id="money_1">1만원</button>
					<button class="pbresult-money-but" id="money_2">3만원</button>  
					<button class="pbresult-money-but" id="money_3">5만원</button>  
					<button class="pbresult-money-but" id="money_4">10만원</button>  
					<button class="pbresult-money-but" id="money_5">50만원</button>  
					<button class="pbresult-money-but" id="money_6">100만원</button>  
					<button class="pbresult-money-but" id="money_9">전체</button>
					<button class="pbresult-money-but" id="money_0">정정</button>
				</div>
				<div class="useredit-text-div">
					<p></p> 
					<button class="pbresult-list-view-but" id="useredit-give-but-id" style="margin-left:0px; margin-right:0px; margin-top:10px;">지급</button>  
					<button class="pbresult-list-view-but" id="useredit-collect-but-id"  style="margin-left:5px;">회수</button>  
					<button class="pbresult-list-view-but" id="useredit-deposit-but-id" style="margin-left:65px; margin-right:0px;">직충전</button>  
					<button class="pbresult-list-view-but" id="useredit-withdraw-but-id"  style="margin-left:5px;">직환전</button>  
				</div>

			<?php endif ?>

		<?php } else {?>
		
			<?php if(!is_null($objMember)) {  ?>
				<p class="useredit-seperate-div">
				<div class="useredit-text-div">
					<p>보유금액:</p> 
					<input type = "text" id="useredit-money-input-id" value="<?=num_format(allMoney($objMember), NUM_POINT_CNT)?>"  disabled>
					<label>원</label>
				</div>
				<div class="useredit-text-div">
					<p>보유포인트:</p> 
					<input type = "text" id="useredit-point-input-id" value="<?=num_format($objMember->mb_point, NUM_POINT_CNT)?>" disabled>
					<label>P</label>
				</div>

				<?php if($trnas_en || $return_en) :  ?>

				<div class="useredit-text-div">
					<p>이동금액:</p> 
					<input type = "text" id="useredit-transfer-input-id" value="0" >
					<label>원</label>
					
					<?php if($trnas_en) :  ?>
						<button class="pbresult-list-view-but" id="useredit-transfer-but-id" style="margin-right:0px;">이동</button>  
					<?php endif ?>

					<?php if($return_en) :  ?>
						<button class="pbresult-list-view-but" id="useredit-return-but-id" style="margin-left:5px;">환수</button>  
					<?php endif ?>
				</div>
				<div class="useredit-text-div">
					<button class="pbresult-money-but" style="margin-left:180px;" id="money_1">1만원</button>
					<button class="pbresult-money-but" id="money_2">3만원</button>  
					<button class="pbresult-money-but" id="money_3">5만원</button>  
					<button class="pbresult-money-but" id="money_4">10만원</button>  
					<button class="pbresult-money-but" id="money_5">50만원</button>  
					<button class="pbresult-money-but" id="money_6">100만원</button>  
				</div>

				<?php endif ?>

			<?php } ?>
		<?php } ?>

		<?php if(is_null($objMember) || $objMember->mb_level < LEVEL_ADMIN) :  ?>
			<div class = "useredit-button-group" style="text-align:center;">
				<button class="useredit-cancel-button" id="useredit-cancel-btn-id" style="float:none;">닫기</button>
				<button class="useredit-ok-button"  id="useredit-save-btn-id"  style="float:none;">저장</button>
			</div>
		<?php else :  ?>
			<div class = "useredit-button-group" style="text-align:center;">
				<button class="useredit-cancel-button"  id="useredit-cancel-btn-id"  style="float:none;">닫기</button>
			</div>
		<?php endif ?>

	</div>

<!--main_navbar.php-->
</div>

<?= $this->renderSection('user-edit-script') ?>
<?= $this->endSection() ?>