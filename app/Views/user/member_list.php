<?= $this->extend('header') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?php echo site_furl('assets/css/app.css?v=3');?>">
	
	<!--Sub Navbar-->
	<div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-user"></i> 회원관리</p>
		<?php if($mb_level >= LEVEL_MASTER && $adm_fid > 0) :  ?>
			<a onclick="popupMemberEdit(<?=$adm_fid?>);" class="user-panel-add-a" style="margin-left:5px; cursor:pointer;">관리자정보</a>
			<a onclick="requestAutoStop();" class="user-panel-add-a" style="margin-left:5px; cursor:pointer;">자동차단</a>
		<?php endif ?>
	</div>
	<style>
	body {
		overflow-x: hidden;
	}
	button.refresh_btn {
		float:right;
		margin-left: 1px;
		margin-right: 2px;
	}
	.user-table td{
		border-right:solid 0.4px #888;
	}
	.user-table input{
		width:50px;
		text-align:center;		
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
		/* background:#00ffff; */
	}
	</style>
	<!--Site Setting-->
	<div class="user-panel">	
		<div>
			<?php if($mb_level >= LEVEL_ADMIN) :  ?>
				<a href="" onclick="popupMemberEdit(0);" class="user-panel-add-a" >회원 등록</a>
			<?php endif ?>
			<select name="pbresult-level" class="pbresult-number-select" id="userpanel-type-select-id" style="width:100px;">
				<option value="0">아이디</option>
				<option value="1">닉네임</option>
				<option value="2">등록번호</option>
				<?php if($mb_level >= LEVEL_ADMIN) :  ?>
					<option value="3">예금주</option>
				<?php endif ?>
			</select>
            <input type="text" class="pbresult-text-input" id="userpanel-userid-input-id" style="width:150px" value= "<?=$emp_uid ?>" >
			<select name="pbresult-level" class="pbresult-number-select" id="userpanel-mode-select-id" style="width:70px; margin-left:3px;">
				<option value="1">포함</option>
				<option value="0" <?=strlen($emp_uid)>0?"selected":"" ?>>일치</option>
			</select>
			<button class="pbresult-list-view-but" id="userpanel-list-view-but-id" style="margin-left:1px;">검색</button>  
			<?php if($mb_level >= LEVEL_ADMIN) :  ?>
				<button class="pbresult-list-view-but" id="userpanel-list-open-but-id" style="margin-right:0px;">펼치기</button>  
				<button class="pbresult-list-view-but" id="userpanel-list-close-but-id">감추기</button>  
			<?php endif ?>

		</div>		
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
					<th>등록<span style="word-break: keep-all;">번호</span></th>
					<th>닉네임</th>
					<th>Lv</th>
					<th>현재금액</th>
					<th>포인트</th>
					<th>배당율<span style="word-break: keep-all;">(%)</span></th>
					<?php if ($mb_level >= LEVEL_ADMIN) :?>	
						<?php if(!$slot_deny) :?>
							<th style="width:130px;">공배팅</th>
						<?php endif ?> 
						<th>접속IP</th>
						<th>수정</th>
						<th>설정</th>
					<?php else: ?>
						<th>이동 / 환수</th>
					<?php endif ?>
				</tr>
			</thead>
			<tbody  id="user-member-table-id">
				
			</tbody>

		</Table>

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
    <script src="<?php echo site_furl('/assets/js/member_list-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/member_common-script.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/member_util-script.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/member_list-script.js?v=2');?>"></script>
<?php endif ?>

<div class="row" style="font-size:12px;">
	<div id="charge_modal" class="modal fade in" tabindex="-2" role="dialog" aria-hidden="false" style="display: none; padding-right: 17px; color:#333;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h2 class="modal-title"><span class="c_type_forced">강제충전</span></h2>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
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
							<div class="row">
								<!-- <div class="col-md-2"></div> -->
								<div style="text-align:right; padding-right:15px;">
									<a onclick="tr_price(10000)" class="btn btn-primary btn-charge"><span class="c_type">1만원</span></a>
									<a onclick="tr_price(50000)" class="btn btn-primary btn-charge"><span class="c_type">5만원</span></a>
									<a onclick="tr_price(100000)" class="btn btn-primary btn-charge"><span class="c_type">10만원</span></a>
									<a onclick="tr_price(500000)" class="btn btn-primary btn-charge"><span class="c_type">50만원</span></a>
									<a onclick="tr_price(1000000)" class="btn btn-primary btn-charge"><span class="c_type">100만원</span></a>
									<a onclick="tr_price(0)" class="btn btn-primary btn-charge"><span class="c_type">정정</span></a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer" style="padding: 10px !important;">
					<a id="btn-charge-apply" onclick="reqMemGive()" class="btn btn-primary btn-charge"><span class="c_type">이동</span></a>
					<a id="btn-discharge-apply" onclick="reqMemWithdraw()" class="btn btn-primary btn-discharge"><span class="c_type">환수</span></a>
					<a data-dismiss="modal" class="btn btn-warning"  onclick="closeChargeDlg();">취소 </a>
				</div>
			</div>
		</div>
	</div>
</div>





<?= $this->endSection() ?>