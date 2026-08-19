<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	<!--Sub Navbar-->
	<div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-minus-sign"></i> 충환전관리::환전</p>
	</div>
	<!--Site Setting-->
	<div class="bank-panel">		
		
		<div class="pbresult-list-div">
			<label>아이디</label>
            <input type="text" class="pbresult-text-input" id="withdraw-userid-input-id" >
			<label>요청일짜</label>
			<input type="date" id="withdraw-datestart-input-id"  value="<?php echo date('Y-m-d'); ?>">
            <label> ~ </label>
            <input type="date" id="withdraw-dateend-input-id"  value="<?php echo date('Y-m-d'); ?>">
            
			<select name="pbresult-number" class="pbresult-number-select" id="withdraw-number-select-id">
				<?php foreach($select_nums as $num=>$select):?>
					<option value="<?=$num?>" <?=$select?>><?=$num?>개</option>
				<?php endforeach?>
			</select>
			<button class="pbresult-list-view-but" id="withdraw-list-view-but-id">검색</button>
			<button class="pbresult-list-view-but" style="padding: 5px 10px; margin:0 5px 0 0; float:right;" id="withdraw-list-wait-but-id">대기</button>
			<button class="pbresult-list-view-but" style="padding: 5px 10px; margin:0 5px 0 0; float:right;" id="withdraw-list-refuse-but-id">거절</button>
			<button class="pbresult-list-view-but" style="padding: 5px 10px; margin:0 5px 0 0; float:right;" id="withdraw-list-permit-but-id">승인</button>
			<p style="font-size:14px; font-weight:bold; padding: 5px 10px; margin:2px 10px 0 0; float:right;">대기금액 합계: <span style="font-size:16px; color:#0090ff;" id="bank-withdraw-wait-id">0 원</span></p>
		</div>		
		<Table class="user-table">
			<thead>
				<tr>
					<th></th>
					<th>번호</th>
					<th>닉네임</th>
					<th>아이디</th>
					<th>현재금액</th>
					<th>요청일짜</th>
					<th>환전금액</th>
					<th>계좌정보</th>
					<th>전화번호</th>
					<th>상태</th>
					<th>승인일짜</th>
					<th>관리자</th>
					<th>설정</th>
				</tr>
			</thead>
			<tbody  id="bank-withdraw-table-id">
				
			</tbody>
		</Table>

		<div class="pbresult-list-page-div">
			<p >환전금액 합계: <span id="bank-withdraw-total-id"></span></p>
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

<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/withdraw-script.js?v=1');?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?t='.time());?>"></script>
    <script src="<?php echo site_furl('/assets/js/withdraw-script.js?t='.time());?>"></script>
<?php endif ?>
<?= $this->endSection() ?>