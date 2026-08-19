<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	
	<!--Sub Navbar-->
	<div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-user"></i> 회원관리</p>
		<?php if($mb_level >= LEVEL_MASTER && $adm_fid > 0) :  ?>
			<a onclick="popupMemberEdit(<?=$adm_fid?>);" class="user-panel-add-a" style="margin-left:5px; cursor:pointer;">관리자정보</a>
		<?php endif ?>
	</div>
	<style>
			
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
	</style>
	<!--Site Setting-->
	<div class="user-panel">	
		<div>
			<?php if($mb_level >= LEVEL_ADMIN) :  ?>
				<a href="" onclick="popupMemberEdit(0);" class="user-panel-add-a" >회원 등록</a>
			<?php endif ?>
			<label>추천인</label>
            <input type="text" class="pbresult-text-input" id="userpanel-empid-input-id" value= "<?=$emp_uid ?>">
			<label>아이디</label>
            <input type="text" class="pbresult-text-input" id="userpanel-userid-input-id" >
			
			<select name="pbresult-level" class="pbresult-number-select" id="userpanel-state-select-id" stype="width:80px">
				<option value="-1">::전체::</option>
				<option value="1">승인</option>
				<option value="0">차단</option>
				<option value="2">대기</option>

			</select>

			<select name="pbresult-level" class="pbresult-number-select" id="userpanel-level-select-id">
				<option value="0">:: Lv::</option>
				<option value="1"> Lv 1 </option>
				<option value="2"> Lv 2 </option>
				<option value="3"> Lv 3 </option>
				<option value="4"> Lv 4 </option>
				<option value="5"> Lv 5 </option>
				<option value="6"> Lv 6 </option>
				<option value="7"> Lv 7 </option>
				<option value="8"> Lv 8 </option>
				<option value="9"> Lv 9 </option>
				<option value="10"> Lv 10 </option>
			</select>
			<select name="pbresult-number" class="pbresult-number-select" id="userpanel-number-select-id">
				<?php foreach($select_nums as $num=>$select):?>
					<option value="<?=$num?>" <?=$select?>><?=$num?>개</option>
				<?php endforeach?>
			</select>
			<button class="pbresult-list-view-but" id="userpanel-list-view-but-id">검색</button>  
		</div>		
		<Table class="user-table" style="margin-top: 15px;">
			<thead>
				<tr>
					<th>번호</th>
					<th>추천인</th>
					<th>아이디</th>
					<th>닉네임</th>
					<th>등록번호</th>
					<th>Lv</th>
					<th>현재금액</th>
					<th>포인트</th>
					<?php if ($mb_level >= LEVEL_ADMIN) {?>	
						<?php if(!$slot_deny) :?>
							<th style="width:130px;">공배팅</th>
						<?php endif ?> 
					<th>접속IP</th>
					<?php } ?>
					<th>승인</th>
					<?php if ($mb_level >= LEVEL_ADMIN) {?>	
					<th>게임별설정</th>
					<?php } ?>
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
    <script src="<?php echo site_furl('/assets/js/member-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/member_common-script.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/member-script.js?v=2');?>"></script>
<?php endif ?>

<?= $this->endSection() ?>