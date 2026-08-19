<?= $this->extend('header') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?php echo site_furl('assets/css/app.css?v=3');?>">
	
	<!--Sub Navbar-->
	<div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-user"></i> 매장전용</p>
	</div>
	<style>
		.user-table{
			min-width:1800px;		
		}
		.user-table td{
			border-right:solid 0.4px #888;
		}
		.user-table input{
			width:50px;
			text-align:center;		
		}
		button.refresh_btn{
			margin-right: 2px;
			margin-left: 1px;
			position: relative;
			float:right;
		}
		html, body {
			background: none repeat scroll 0 0 #fff;
			overflow-x: hidden;
			-webkit-font-smoothing: antialiased;
			min-height: 100%;
		}
		.modal{
			background: rgb(0 0 0 / 50%);
			overflow-y: auto;
		}
		tr.hidden{
			display: none;
		}
		tr button[id^=exp-btn_]{
			border:none;
		}
		tr button.expand{
			color:blue;
		}
		.user-table thead tr:nth-child(2) th{
			background-color: #555;
			padding:5px 3px;
		}
	</style>
	<!--Site Setting-->
	<div class="user-panel">	
		<div>
			<?php if($mb_level >= LEVEL_ADMIN) {  ?>
			<a href="javascript:popupMemberEdit(0);" class="user-panel-add-a" >회원 등록</a>
			<?php } ?>
			<!-- <label>추천인</label>
            <input type="text" class="pbresult-text-input" id="userpanel-empid-input-id" value= "<?=$emp_uid?>"> -->
			<select name="pbresult-level" class="pbresult-number-select" id="userpanel-type-select-id" stlpe="width:100px">
				<option value="0">아이디</option>
				<option value="1">닉네임</option>
				<option value="2">등록번호</option>
				<option value="3">예금주</option>
			</select>
            <input type="text" class="pbresult-text-input" id="userpanel-userid-input-id" value= "<?=$emp_uid?>">
			<select name="pbresult-level" class="pbresult-number-select" id="userpanel-mode-select-id" style="width:70px; margin-left:3px;">
				<option value="0">일치</option>
				<option value="1">포함</option>
			</select>
			<button class="pbresult-list-view-but" id="userpanel-list-view-but-id" style="margin-left:1px;">검색</button>  
			<button class="pbresult-list-view-but" id="userpanel-list-open-but-id" style="margin-right:0px;">펼치기</button>  
			<button class="pbresult-list-view-but" id="userpanel-list-close-but-id">감추기</button>  
		</div>	
		<div style="position: relative; overflow: auto; width: 100%;">	
			<Table class="user-table" style="margin-top: 15px;">
				<thead>
					<tr>
						<th></th>
						<th>파트너1</th>
						<th>파트너2</th>
						<th>파트너3</th>
						<th>파트너4</th>
						<th>파트너5</th>
						<th>추천인</th>
						<th>등록번호</th>
						<th>닉네임</th>
						<th>Lv</th>
						<th>충전</th>
						<th>환전</th>
						<th>연락처</th>
						<th>보유금액</th>
						<?php if ($mb_level >= LEVEL_ADMIN) {?>	
						<th>알회수</th>
						<?php } ?>
						<th>포인트</th>
						<th>총배팅금</th>
						<th>총획득금</th>
						<th>총롤링금</th>
						<th>환전롤링금</th>
						<th>등록날짜</th>
						<th>최근접속</th>
						<th>수정</th>
						<?php if(!$slot_deny) :?>
							<th>공배팅</th>
						<?php endif ?> 
						<th>로그아웃</th>
					</tr>
					<tr style="border-color: #fff;">
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>

						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th id="sumOfMoney">0</th>
						<?php if ($mb_level >= LEVEL_ADMIN) {?>	
						<th></th>
						<?php } ?>
						<th id="sumOfPoint"></th>
						<th id="sumOfBet"></th>
						<th id="sumOfWin"></th>
						<th id="sumOfRwPt"></th>
						<th id="sumOfChgpt"></th>
						<th></th>
						<th></th>
						<th></th>
						<?php if(!$slot_deny) :?>
							<th></th>
						<?php endif ?> 
						<th></th>
					</tr>
				</thead>
				<tbody  id="user-member-table-id">
					
				</tbody>

			</Table>
		</div>
		<div class="pbresult-list-page-div">
			
			<div class="pagination" id="list-page" style="display:none">
				<button class="list-page-button" id="page-first"  onclick="firstPage()"><<</button>
				<button class="list-page-button" id="page-prev"  onclick="prevPage()"><</button>
				<div class="pagination-div" id="pagination-num">
					<button class="active">1</button>
					<button class="">2</button>						
				</div>
				<button class="list-page-button" id="page-next"  onclick="nextPage()">></button>
				<button class="list-page-button" id="page-last"  onclick="lastPage()">>></button>
			</div>			
	
		</div>

	</div>


<!--main_navbar.php-main-container-->
</div>
	
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
    <script src="<?php echo site_furl('/assets/js/page.js?t='.time());?>"></script>
    <script src="<?php echo site_furl('/assets/js/member_common-script.js?t='.time());?>"></script>
    <script src="<?php echo site_furl('/assets/js/member_util-script.js?t='.time());?>"></script>
    <script src="<?php echo site_furl('/assets/js/member_class-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/member_common-script.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/member_util-script.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/member_class-script.js?v=1');?>"></script>
<?php endif ?>


<div class="row" style="font-size:12px;">
	<div id="edit_member_modal" class="modal fade in" tabindex="-2" role="dialog" aria-hidden="false" style="display:none; color:#333;">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h2 class="modal-title"><span id="type">회원정보 추가</span></h2>
				</div>
				<div class="modal-body">
					<div class="row">
						<form name="create_member_form" id="member_form">
							<div class="row" style="margin-bottom: 10px;">일반정보 :</div>
							
							<div class="col-md-12">
								<div class="row">
									
									<div class="col-md-2">추천인 :</div>
									<div class="col-md-4">
										<input id="partner_id" type="text" value="" class="form-control" />
										<input id="user_fid" type="hidden" class="form-control" disabled="" value="" />
									</div>
									<?php if(!array_key_exists('app.ebal', $_ENV) || $_ENV['app.ebal'] < 1 ) :?>
									<div class="col-md-2">
										<input type="checkbox" id="offline_user" style="zoom:110%; width 20px; margin-top:10px;"/>
										오프라인유저
									</div>
									<?php endif ?>

								</div>
							</div>
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-2">회원 ID:</div>
									<div class="col-md-4">
										<input id="user_uid" type="text" placeholder="" class="form-control english_s" />
									</div>
									<div class="col-md-2">닉네임 :</div>
									<div class="col-md-4">
										<input id="user_name" type="text" placeholder="" class="form-control" />
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-2">비밀번호 :</div>
									<div class="col-md-4">
										<input id="user_password" type="text" placeholder="" class="form-control" />
									</div>
									<div class="col-md-2">연락처 :</div>
									<div class="col-md-4">
										<input id="user_phone" type="text" placeholder="" class="form-control" />
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<div class="row">
									<div class="col-md-2">상태:</div>
									<div class="col-md-2">
										<select id="user_status">
											<option value="2">대기 </option>
											<option value="0">차단 </option>
											<option value="1">승인 </option>
										</select>
									</div>
									<div class="col-md-2">Lv</div>
									<div class="col-md-2">
										<select id="user_level">
											<?php for ($lv = 1; $lv <= 10 ; $lv ++) { ?>
												<option value="<?=$lv?>">Lv <?=$lv?></option>
											<?php }?>
										</select>
									</div>
									<div class="col-md-2">색깔 :</div>
									<div class="col-md-2">
										<input id="user_color" type="color" value="0" class="form-control" style="padding:2px ;"/>
									</div>
								</div>
							</div>
					<?php if(!$pbg_deny || !$bpg_deny || !$eos5_deny || !$eos3_deny || !$coin5_deny || !$coin3_deny) :?>
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-2">미니게임 단폴 배당율 (%)</div>
									<div class="col-md-2">
										<input type="number" id="pb_ratio" step="0.01" class="form-control" value="0.00" min="0" />
									</div>
									<div class="col-md-2">미니게임 조합 배당율 (%)</div>
									<div class="col-md-2">
										<input type="number" id="pb2_ratio" step="0.01" class="form-control" value="0.00" min="0" />
									</div>
								</div>
							</div>
						<?php if(!$gameper_full) :?>
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-2">미니게임 단폴 누름율 (%)</div>
									<div class="col-md-2">
										<input type="number" id="pb_percent" class="form-control" value="100" min="0" />
									</div>
									<div class="col-md-2">미니게임 조합 누름율 (%)</div>
									<div class="col-md-2">
										<input type="number" id="pb2_percent" class="form-control" value="100" min="0" />
									</div>
								</div>
							</div>
						<?php endif ?>
					<?php endif ?>
							<div class="col-md-12">
								<div class="row">
								<?php if(isEBalMode() || !$evol_deny || !$cas_deny) :?>
									<div class="col-md-2">카지노 배당율 (%)</div>
									<div class="col-md-2">
										<input type="number" id="cs_ratio" step="0.01" class="form-control" value="0.00" min="0" />
									</div>
								<?php endif ?>
								<?php if(!$slot_deny) :?>
									<div class="col-md-2">슬롯 배당율 (%)</div>
									<div class="col-md-2">
										<input type="number" id="sl_ratio" step="0.01" class="form-control" value="0.00" min="0" />
									</div>
								<?php endif ?>
								<?php if(!$hold_deny) :?>
									<div class="col-md-2">홀덤 배당율 (%)</div>
									<div class="col-md-2">
										<input type="number" id="hl_ratio" step="0.01" class="form-control" value="0.00" min="0" />
									</div>
								<?php endif ?>
								</div>
							</div>
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-2">메모:</div>
									<div class="col-md-10">
										<textarea rows="10" id="memo" style="width:100%; resize: vertical;" class="form-control"></textarea>					
									</div>
								</div>
							</div>

							<div class="row">은행정보 :</div>
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-2">은행명 :</div>
											<div class="col-md-4">
												<input id="bank_name" type="text" placeholder="" class="form-control" />
											</div>

											<div class="col-md-2">예금주 :</div>
											<div class="col-md-4">
												<input id="bank_owner" type="text" placeholder="" class="form-control" />
											</div>
										</div>
									</div>
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-2">계좌번호 :</div>
											<div class="col-md-4">
												<input id="bank_number" type="text" placeholder="" class="form-control" />
											</div>

											<div class="col-md-2">출금비번 :</div>
											<div class="col-md-4">
												<input id="bank_password" type="text" placeholder="" class="form-control" />
											</div>
										</div>
									</div>
									
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="modal-footer" style="padding: 10px !important;">
					<a id="btn-mem-apply" onclick="memSaveApply();" class="btn btn-primary">추가 </a>
					<a data-dismiss="modal" class="btn btn-warning"  onclick="closeMemEditDlg();">취소 </a>
				</div>
			</div>
		</div>
	</div>


	<div id="charge_modal" class="modal fade in" tabindex="-2" role="dialog" aria-hidden="false" style="display: none; padding-right: 17px; color:#333;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h2 class="modal-title"><span class="c_type_forced">강제충전</span></h2>
				</div>
				<div class="modal-body">
					<div class="row">
						<div>
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-4"><span class="c_type_admin">충전해주는 업체</span></div>
									<div class="col-md-8">
										<input id="charge_admin_name" type="text" placeholder="" class="form-control" value="총본사" disabled="" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">닉네임 :</div>
									<div class="col-md-8">
										<input id="charge_user_name" type="text" placeholder="" class="form-control" disabled="" />
										<input id="charge_user_id" type="hidden" placeholder="" class="form-control" disabled="" value="" />
										<input id="charge_user_fid" type="hidden" placeholder="" class="form-control" disabled="" value="" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">금액 :</div>
									<div class="col-md-8">
										<input id="charge_user_money" type="text" placeholder="" class="form-control" disabled="" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-4"><span class="c_type_money">충전금액</span></div>
									<div class="col-md-8">
										<input id="charge_money" type="text" placeholder="" class="form-control" />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer" style="padding: 10px !important;">
					<a id="btn-charge-apply" onclick="reqMemCharge()" class="btn btn-primary btn-charge"><span class="c_type">충전</span></a>
					<a id="btn-discharge-apply" onclick="reqMemDischarge()" class="btn btn-primary btn-discharge"><span class="c_type">환전</span></a>
					<a data-dismiss="modal" class="btn btn-warning"  onclick="closeChargeDlg();">취소 </a>
				</div>
			</div>
		</div>
	</div>
</div>

<?= $this->endSection() ?>