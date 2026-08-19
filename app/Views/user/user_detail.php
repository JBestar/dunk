<?= $this->extend('user/header') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?php echo site_furl('assets/css/app.css?v=3');?>">
<link rel="stylesheet" href="<?php echo site_furl('assets/css/admin.css?v=1');?>">
  	

<style>
	.table-black input{
		min-width:150px;
	}
</style>

	<!--Sub Navbar-->
	<div class = "sub-navbar">
		<?php if(is_null($objMember)) {  ?>
		<p><i class="glyphicon glyphicon-user"></i> 회원관리::회원 등록</p>
		<?php } else {?>
			<p><i class="glyphicon glyphicon-user"></i> 회원관리::회원 수정</p>
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
		<table style="width: 100%; " class="table table-bordered table-black user_info">
		<tbody>
			<tr>
				<td>추천인 </td>
				<td><input type="text" id="useredit-sort-select-id" value="<?=$emp_uid; ?>" 
					<?= ($mb_level < LEVEL_ADMIN?'disabled':'')?>
					/>
				</td>
				<td>Lv </td>
				<td>
					<?php if ($mb_level >= LEVEL_ADMIN) :  ?>
					<select type="text" id="useredit-level-select-id">
					<?php else : ?>
					<select type="text" id="useredit-level-select-id" disabled>
					<?php endif ?>
						<?php foreach (range(1, 10) as $useLevel) { ?>
						<?php if (is_null($objMember) || ($objMember->mb_grade != $useLevel)) {  ?>
						<option value="<?php echo $useLevel; ?>">Lv <?php echo $useLevel; ?></option>
						<?php } else {?>
						<option value="<?php echo $useLevel; ?>" selected>Lv <?php echo $useLevel; ?></option>
						<?php } ?>
						<?php }?>
					</select>
				</td>
			</tr>

			<tr>
				<td>아이디(4~16자 영문,숫자만 가능) </td>
				<td>
					<?php if(is_null($objMember)) {  ?>	
					<input type = "text"   id="useredit-id-input-id" class="english_s">
					<?php } else {?>
					<input type = "text"   id="useredit-id-input-id" class="english_s" value="<?=$objMember->mb_uid ?>" disabled>
					<?php } ?>
				</td>
				<td>닉네임 (길이 2~20자) </td>
				<td>
					<?php if(is_null($objMember)) {  ?>	
					<input type = "text" id="useredit-nickname-input-id">
					<?php } else {?>
						<?php if($mb_level > LEVEL_COMPANY) {  ?>
						<input type = "text" id="useredit-nickname-input-id" value="<?=$objMember->mb_nickname?>" disabled>
						<?php } else {?>
						<input type = "text" id="useredit-nickname-input-id" value="<?=$objMember->mb_nickname?>" disabled>
						<?php } ?>		
					<?php } ?>
				</td>
			</tr>

			<tr>
				<td>비밀번호(8~20자, 특수문자 한개 이상) </td>
				<td>
					<?php if(is_null($objMember)) {  ?>	
					<input type = "text" id="useredit-pwd-input-id" class="">
					<?php } else {?>
					<input type = "text" id="useredit-pwd-input-id" class="" value="<?=$objMember->mb_pwd?>" >
					<?php } ?>
				</td>
				<td>핸드폰번호(-없이 숫자만입력) </td>
				<td>
					<?php if(is_null($objMember)) {  ?>			
					<input type = "text" id="useredit-phone-input-id">
					<?php } else {?>
					<input type = "text" id="useredit-phone-input-id" value="<?=$objMember->mb_phone?>" >
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td>은행명 </td>
				<td>
					<?php if(is_null($objMember)) {  ?>			
					<input type = "text" id="useredit-bankname-input-id">
					<?php } else if(is_null($objMember->mb_bank_name)) {  ?>
					<input type = "text" id="useredit-bankname-input-id">
					<?php } else {?>
					<input type = "text" id="useredit-bankname-input-id" value="<?=$objMember->mb_bank_name?>" >
					<?php } ?>
				</td>
				<td>예금주 </td>
				<td>
					<?php if(is_null($objMember)) {  ?>			
					<input type = "text" id="useredit-bankaccount-input-id">
					<?php } else if(is_null($objMember->mb_bank_own)) {  ?>
					<input type = "text" id="useredit-bankaccount-input-id">
					<?php } else {?>
					<input type = "text" id="useredit-bankaccount-input-id" value="<?=$objMember->mb_bank_own?>" >
					<?php } ?>
				</td>
			</tr>
			
			<tr>
				<td>계좌번호(-없이 숫자만입력) </td>
				<td>
					<?php if(is_null($objMember) || is_null($objMember->mb_bank_num)) {  ?>						
					<input type = "text" id="useredit-bankserial-input-id">
					<?php } else {?>
					<input type = "text" id="useredit-bankserial-input-id" value="<?=$objMember->mb_bank_num?>" >
					<?php } ?>
				</td>
				<td>출금비번 </td>
				<td>
					<?php if(is_null($objMember) || is_null($objMember->mb_bank_pwd)) {  ?>	
					<input type = "text" id="useredit-bankpwd-input-id">
					<?php } else {?>
					<input type = "text" id="useredit-bankpwd-input-id" value="<?=$objMember->mb_bank_pwd?>">
					<?php } ?>
				</td>
			</tr>
			
			<tr>
				<td>색깔 </td>
				<td>
					<?php if (is_null($objMember) || is_null($objMember->mb_color)) {  ?>
					<input type="color" value="#ffffff" id="useredit-color-input-id">
					<?php } else {?>
					<input type="color" value="<?php echo $objMember->mb_color; ?>" id="useredit-color-input-id">
					<?php } ?>
				</td>
				<?php if(!is_null($objMember)) :  ?>
				<td>계정상태</td>
				<td>
					<label style="font-size:16px;">
					<?php if ($objMember->mb_state_active == PERMIT_OK) :  ?>
						<span class="badge btn-success">승인</span>
					<?php elseif ($objMember->mb_state_active == PERMIT_CANCEL) :  ?>
						<span class="badge btn-default">차단</span>
					<?php elseif ($objMember->mb_state_active == PERMIT_REQ) :  ?>
						<span class="badge btn-primary">신청</span>
					<?php elseif ($objMember->mb_state_active == PERMIT_WAIT) :  ?>
						<span class="badge btn-primary">대기</span>
					<?php endif ?>
					</label>
				</td>
				<?php endif ?>

				<!-- <td>등록번호 </td>
				<td>
					<?php if (!is_null($objMember)) :  ?>
						<?=$objMember->mb_fid?>
					<?php endif ?>
				</td> -->
			</tr>
			<tr>
				<td>
					<?php if(!$hold_deny) :?>
					홀덤 배당율(%)<br> <br>
					<?php endif ?> 
					<?php if(isEBalMode() || !$evol_deny || !$cas_deny) :?>
					카지노 배당율(%)<br> <br>
					<?php endif ?> 
					<?php if(!$slot_deny) :?>
					슬롯 배당율(%)<br> <br>
					<?php endif ?> 
				</td>
				<td>
					<?php if(!$hold_deny) :?>
						<?php if(is_null($objMember)) :  ?>
						<input type = "number" step="0.1" id="useredit-hlbetrate-input-id" value="0" ><br>
						<?php else :?>
						<input type = "number" step="0.1" id="useredit-hlbetrate-input-id" value="<?=$objMember->mb_game_hl_ratio?>"><br>
						<?php endif ?>
						<br>
					<?php endif ?> 

					<?php if(isEBalMode() || !$evol_deny || !$cas_deny) :?>
						<?php if(is_null($objMember)) :  ?>
						<input type = "number" step="0.1" id="useredit-evbetrate-input-id" value="0" ><br>
						<?php else :?>
						<input type = "number" step="0.1" id="useredit-evbetrate-input-id" value="<?=$objMember->mb_game_cs_ratio?>"><br>
						<?php endif ?>
						<br>
					<?php endif ?>

					<?php if(!$slot_deny) :?>
						<?php if(is_null($objMember)) :  ?>
						<input type = "number" step="0.1" id="useredit-slbetrate-input-id" value="0" >
						<?php else :?>
						<input type = "number" step="0.1" id="useredit-slbetrate-input-id" value="<?=$objMember->mb_game_sl_ratio?>">
						<?php endif ?>
					<?php endif ?>
				</td>
				<td>
					<?php if(!$pbg_deny || !$bpg_deny || !$eos5_deny || !$coin5_deny) :?>
					미니게임 단폴 배당율(%)<br>  <br>
					미니게임 조합 배당율(%)<br>
					<?php endif ?> 
				</td>
				<td>
					<?php if(!$pbg_deny || !$bpg_deny || !$eos5_deny || !$coin5_deny) :?>
						<?php if(is_null($objMember)) :  ?>
						<input type = "number" step="0.1" id="useredit-pbbetrate-input-id" value="0" ><br><br>
						<input type = "number" step="0.1" id="useredit-pbbetrate2-input-id" value="0" >
						<?php else :?>
						<input type = "number" step="0.1" id="useredit-pbbetrate-input-id" value="<?=$objMember->mb_game_pb_ratio?>"><br><br>
						<input type = "number" step="0.1" id="useredit-pbbetrate2-input-id" value="<?=$objMember->mb_game_pb2_ratio?>">
						<?php endif ?>
					<?php endif ?> 
				</td>
			</tr>

			<?php if(isEBalMode()) :?>
			<tr>
				<td>바카라 배팅금액
				</td>
				<td colspan="3">
					최소
					<?php if(is_null($objMember)) : ?>
						<input type = "text" id="useredit-rangemin-input-id" style="min-width:100px; width:115px;" value="0">
					<?php else :?>
						<input type = "text" id="useredit-rangemin-input-id" style="min-width:100px; width:115px;" value="<?=number_format($objMember->mb_range_min)?>" >
					<?php endif ?>
					&nbsp;&nbsp;최대
					<?php if(is_null($objMember)) :  ?>
						<input type = "text" id="useredit-rangemax-input-id" style="min-width:100px; width:115px;" value="0">
					<?php else :?>
						<input type = "text" id="useredit-rangemax-input-id" style="min-width:100px; width:115px;" value="<?=number_format($objMember->mb_range_max)?>" >
					<?php endif ?>
					&nbsp;&nbsp;하부포함한도
					<?php if(is_null($objMember)) :  ?>
						<input type = "text" id="useredit-rangelimit-input-id" style="min-width:100px; width:115px;" value="0">
					<?php else :?>
						<input type = "text" id="useredit-rangelimit-input-id" style="min-width:100px; width:115px;" value="<?=number_format($objMember->mb_range_limit)?>" >
					<?php endif ?>
				</td>
			</tr>

			<tr>
				<td rowspan="<?=$press_en > 0?2:1?>">
				바카라 누르기 
				</td>
				<td>
					<?php if(!is_null($objMember) && $objMember->mb_state_view == 1) :  ?>
						<input type="checkbox" id="useredit-balance-check-id" style="zoom:100%; margin-top:4px; margin-right:0; width:20px;height:20px;" checked>
					<?php else :  ?>
						<input type="checkbox" id="useredit-balance-check-id" style="zoom:100%; margin-top:4px; margin-right:0; width:20px;height:20px;" >
					<?php endif ?>
				</td>
				<?php if(!is_null($objMember)) :  ?>
				<td>
				넘기기금액(%)
				</td>
				<td>
					<select type="text" id="useredit-overbet-percent-id" style="width:150px;">
						<?php $rates = overBetRates(); foreach($rates as $rate):?>
							<option value="<?=$rate?>" <?=$objMember->mb_overbet_percent==$rate?'selected':''?>><?=$rate?> % </option>
						<?php endforeach?>
					</select>
				</td>
				<?php endif ?>

			</tr>
			<?php if($press_en > 0 ) :?>
				<tr>
					<td>
						<?php if(!is_null($objMember) && $objMember->mb_press_active == 1) :  ?>
							<input type="checkbox" id="useredit-press-check-id" style="zoom:100%; margin-top:4px; margin-right:0; height:20px;" checked>
						<?php else :  ?>
							<input type="checkbox" id="useredit-press-check-id" style="zoom:100%; margin-top:4px; margin-right:0; height:20px;" >
						<?php endif ?>
					</td>
					<td colspan="2"  style="text-align:left; padding-left:25px;">
						<?php if(is_null($objMember)) :  ?>
						<input type = "text" id="useredit-press-input-id" value="0" style="margin-right:5px">
						<?php else :?>
						<input type = "text" id="useredit-press-input-id" value="<?=number_format($objMember->mb_press_amount)?>" style="margin-right:5px">
						<?php endif ?>
						<?php if($press_en == 2 || $press_en == 4 || $press_en == 5 ) :?>
							<label>이하</label>
						<?php endif ?>
						<?php if($press_en == 1 || $press_en == 2 || $press_en == 5 ) :?>
							<select type="text" id="useredit-press-count-id">
							<?php $counts = pressCounts(); if (is_null($objMember)) : ?>
								<?php foreach($counts as $count):?>
									<option value="<?=$count?>" <?=$count==1?'selected':''?>><?=$count==-1?'코인':$count.'회'?></option>
								<?php endforeach?>
							<?php else :?>
								<?php foreach($counts as $count):?>
									<option value="<?=$count?>" <?=$objMember->mb_press_count==$count?'selected':''?>><?=$count==-1?'코인':$count.'회'?></option>
								<?php endforeach?>
							<?php endif?>
							</select>
						<?php endif ?>
						<button class="pbresult-list-view-but" id="useredit-press-but-id"  style="margin-left:5px; padding:5px 10px;">전체 적용</button>  
					</td>
				</tr>
			<?php endif ?>
			<tr>
				<td>바카라 자동누르기</td>
					<td><?php if(!is_null($objMember) && $objMember->mb_pressat_active == 1) :  ?>
						<input type="checkbox" id="useredit-pressat-check-id" style="zoom:100%; margin-top:4px; margin-right:0; width:20px;height:20px;" checked>
					<?php else :  ?>
						<input type="checkbox" id="useredit-pressat-check-id" style="zoom:100%; margin-top:4px; margin-right:0; width:20px;height:20px;" >
					<?php endif ?>
				</td>
				<td colspan="2"  style="text-align:left; padding-left:25px;">
				<?php if(is_null($objMember)) :  ?>
					<input type = "text" id="useredit-pressat-input-id" value="0" style="margin-right:5px">
					<?php else :?>
					<input type = "text" id="useredit-pressat-input-id" value="<?=number_format($objMember->mb_pressat_amount)?>" style="margin-right:5px">
					<?php endif ?>
					<label style="width:48px;">이하</label>
					<button class="pbresult-list-view-but" id="useredit-pressat-but-id"  style="margin-left:5px; padding:5px 10px;">전체 적용</button>  
				</td>
			</tr>

				<?php if($follow_en > 0 ) :?>
				<tr>
					<td>
					바카라 따라가기 활성
					</td>
					<td>
						<?php if(!is_null($objMember) && $objMember->mb_follow_en == 1) :  ?>
							<input type="checkbox" id="useredit-followen-check-id" style="zoom:100%; margin-top:10px; margin-right:0; width:20px; height:20px; min-width:30px;" checked>
						<?php else :  ?>
							<input type="checkbox" id="useredit-followen-check-id" style="zoom:100%; margin-top:10px; margin-right:0; width:20px; height:20px; min-width:30px;" >
						<?php endif ?>
						<button class="pbresult-list-view-but" id="useredit-followen-but-id"  style="margin-left:5px; padding:5px 10px; position:relative; top:-6px;">전체 적용</button>  
					</td>
					<td>
					따라가기 아이디
					</td>
					<td>
						<?php if(is_null($objMember)) : ?>
						<input type = "text" id="useredit-follow-input-id" value="">
						<?php else :?>
						<input type = "text" id="useredit-follow-input-id" value="<?=$objMember->mb_follow_id?>">
						<?php endif ?>
					</td>
				</tr>

				<tr>
					<td>
					바카라 따라가기 승인
					</td>
					<td>
						<?php if(!is_null($objMember) && $objMember->mb_follow_active == 1) :  ?>
							<input type="checkbox" id="useredit-follow-check-id" style="zoom:100%; margin-top:4px; margin-right:0; width:20px; height:20px;" checked>
						<?php else :  ?>
							<input type="checkbox" id="useredit-follow-check-id" style="zoom:100%; margin-top:4px; margin-right:0; width:20px; height:20px;" >
						<?php endif ?>
					</td>
					<td>
					따라가기금액(%)
					</td>
					<td>
						<select type="text" id="useredit-follow-percent-id" style="width:150px;">
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
					</td>
				</tr>

				<?php endif ?>
			<!-- isEBalMode() EndIf -->
			<?php endif ?>

			<?php if(!is_null($objMember) && array_key_exists('app.sess_act', $_ENV) && $_ENV['app.sess_act'] == 1) :?>
				<tr>
					<td>오토앱 승인</td>
					<td colspan="3">
					<?php foreach($objMember->mb_autoapps as $app) { ?>
						<input type="checkbox" name="useredit-auto-apps" style="zoom:100%; width:20px; height:20px; min-width:20px;" <?=$app->act==1?'checked':''?> >
						<span style="margin-right:20px; position:relative; top:-5px;"><?=$app->name?></span>
					<?php } ?>
					</td>
				</tr>
				<tr>
					<td>입금통장</td>
					<td colspan="3">
						<input type="text" id="useredit-charge-bankname-id" placeholder="은행명" value="<?=$objMember->mb_charge_info[0]?>" style="width:100px;">
						<input type="text" id="useredit-charge-bankaccount-id" placeholder="예금주" value="<?=$objMember->mb_charge_info[1]?>" style="width:100px;">
						<input type="text" id="useredit-charge-bankserial-id" placeholder="계좌번호" value="<?=$objMember->mb_charge_info[2]?>" style="width:100px;">
					</td>
				</tr>
				<tr>
					<td>하부회원 머니환수 </td>
					<td>
						<input type="checkbox" id="useredit-transfer-subs-id"  style="zoom:100%; width:20px; height:20px; min-width:20px;" <?=$objMember->mb_transfer_subs==1?'checked':''?> >
					</td>
					<td>추천인 사용불가</td>
					<td>
					<input type="checkbox" id="useredit-recommender-deny-id"  style="zoom:100%; width:20px; height:20px; min-width:20px;" <?=$objMember->mb_recommender_deny==1?'checked':''?> >
					</td>
				</tr>
			<?php endif ?>
			<tr>
				<td>오프라인 유저 </td>
				<td>
					<?php if(!is_null($objMember) && $objMember->mb_state_delete == 1) :  ?>
						<input type="checkbox" id="useredit-offline-check-id" style="zoom:100%; width:20px; height:20px;" checked>
					<?php else :  ?>
						<input type="checkbox" id="useredit-offline-check-id" style="zoom:100%; width:20px; height:20px;" >
					<?php endif ?>
				</td>
				<td style="color:#f00;">테스트 유저 </td>
				<td>
					<?php if(!is_null($objMember) && $objMember->mb_state_test == 1) :  ?>
						<input type="checkbox" id="useredit-test-check-id" style="zoom:100%; width:20px; height:20px;" checked>
					<?php else :  ?>
						<input type="checkbox" id="useredit-test-check-id" style="zoom:100%; width:20px; height:20px;" >
					<?php endif ?>
				</td>
				
			</tr>

			<?php if(!is_null($objMember)) :  ?>
			<tr>
				<td>가입아이피 </td>
				<td>
					<?=$objMember->mb_ip_join?>
				</td>
				<td>최근접속아이피 </td>
				<td>
					<?=$objMember->mb_ip_last?>
				</td>
			</tr>
			<tr>
				<td>가입날짜 </td>
				<td>
					<?=$objMember->mb_time_join?>
				</td>
				<td>최근접속일 </td>
				<td>
					<?=$objMember->mb_time_last?>
				</td>
			</tr>
			<tr>
				<td>전일 입금액 </td>
				<td>
					<?=number_format($objMember->mb_charge_yest)?>
				</td>
				<td>전일 출금액 </td>
				<td>
					<?=number_format($objMember->mb_exchange_yest)?>
				</td>
			</tr>
			<tr>
				<td>당일 입금액 </td>
				<td>
					<?=number_format($objMember->mb_charge_today)?>
				</td>
				<td>당일 출금액 </td>
				<td>
					<?=number_format($objMember->mb_exchange_today)?>
				</td>
			</tr>
			<tr>
				<td>일주일 입금액 </td>
				<td>
					<?=number_format($objMember->mb_charge_week)?>
				</td>
				<td>일주일 출금액 </td>
				<td>
					<?=number_format($objMember->mb_exchange_week)?>
				</td>
			</tr>
			<tr>
				<td>당월 입금액 </td>
				<td>
					<?=number_format($objMember->mb_charge_month)?>
				</td>
				<td>당월 출금액 </td>
				<td>
					<?=number_format($objMember->mb_exchange_month)?>
				</td>
			</tr>
			<tr>
				<td>총 입금액 </td>
				<td>
					<?=number_format($objMember->mb_charge_total)?>
				</td>
				<td>총 출금액 </td>
				<td>
					<?=number_format($objMember->mb_exchange_total)?>
				</td>
			</tr>
			<?php endif?>
			<tr>
				<td>메모 
					<?php if(!is_null($objMember)) :  ?>
						<br>
						<button class="pbresult-list-view-but" onclick="goTabPage('/board/message_edit/0/<?=$objMember->mb_fid?>')" style="margin-left:0px; margin-right:0px; margin-top:10px; padding:5px 20px;">쪽지</button>  
					<?php endif?>
				</td>
				<td rowspan="3" colspan="3">
					<textarea rows="10" id="useredit-memo-text-id" style="width:510px; resize: vertical;" ><?php if(!is_null($objMember)) : echo $objMember->mb_memo ?><?php endif ?></textarea>					
				</td>
			</tr>
		</tbody>
		</table>

			<!---->
			<!-- <p class="useredit-seperate-div"></p> -->
					
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
					<p>변동금액:</p> 
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
					<button class="pbresult-list-view-but" id="useredit-give-but-id" style="margin-left:0px; margin-right:0px; margin-top:10px; padding:5px 20px;">지급</button>  
					<button class="pbresult-list-view-but" id="useredit-collect-but-id"  style="margin-left:5px; padding:5px 20px;">회수</button> 
					<button class="pbresult-list-view-but" id="useredit-deposit-but-id" style="margin-left:15px; margin-right:0px; padding:5px 15px;">직충전</button>  
					<button class="pbresult-list-view-but" id="useredit-withdraw-but-id"  style="margin-left:5px; padding:5px 15px;">직환전</button>  
					<button class="pbresult-list-view-but" id="useredit-change-money-id"  style="margin-left:15px; padding:5px 20px;">전환</button>  
				</div>
				<?php if(array_key_exists('app.sess_act', $_ENV) && $_ENV['app.sess_act'] == 1 && count($emps) > 0) :?>
					<div class="useredit-text-div" style=" margin-top:10px;">
						<p></p> 
						<label style="float:left; width:80px; text-align:center;">상부회원</label> 
						<select type="text" id="useredit-emp-select-id" style="width:185px; padding:7px 5px;">
							<?php foreach ($emps as $emp) : ?>
								<option value="<?=$emp->mb_uid?>"><?=$emp->mb_uid?>(<?=$emp->mb_nickname?>)</option>
							<?php endforeach?>
						</select>
						<button class="pbresult-list-view-but" id="useredit-return-emp-id"  style="margin-left:15px; padding:5px 20px;">환수</button>  
					</div>
				<?php endif ?>

			<?php endif ?>


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

<?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
    <script src="<?php echo site_furl('/assets/js/member_common-script.js?t='.time());?>"></script>
	<script src="<?php echo site_furl('/assets/js/member_edit-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/member_common-script.js?v=1');?>"></script>
	<script src="<?php echo site_furl('/assets/js/member_edit-script.js?v=1');?>"></script>
<?php endif ?>
<?= $this->endSection() ?>

