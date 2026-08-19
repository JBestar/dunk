<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	<style>
		.user-table a.link-member {
			color: blue;
			border: none;
			background-color: transparent;
			box-shadow: none;
			font-weight: lighter;
		}

		.user-table a.link-member:hover {
			text-decoration: underline;
			color: blue;
		}
	</style>
	<!--Sub Navbar-->
	<div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-user"></i> 회원관리::접속이력</p>
		<a href="<?php echo siteFurl().'user/member_connect';?>" class="sub-navbar-a" >실시간접속</a>
		<a href="<?php echo siteFurl().'user/member_log';?>" class="sub-navbar-a" >접속이력</a>
		<?php if(array_key_exists('app.sess_act', $_ENV) && $_ENV['app.sess_act'] == 1) :?>
			<a href="<?php echo siteFurl().'user/member_try';?>" class="sub-navbar-a active" >로그인이력</a>
		<?php endif ?>
	</div>
	<!--Site Setting-->
	<div class="user-panel">	
		<div>
			<select class="pbresult-number-select" id="userpanel-type-select-id" stype="width:80px">
				<option value="1">아이피</option>
				<option value="0">아이디</option>
			</select>
            <input type="text" class="pbresult-text-input" style="width:150px;" id="userpanel-userid-input-id" >
			<select class="pbresult-number-select" id="userpanel-result-select-id" style="width:80px; margin-left:5px; margin-right:10px;">
				<option value="-1">결과</option>
				<option value="0">성공</option>
				<option value="1">실패</option>
			</select>
			<label>일자</label>
			<input type="date" id="userpanel-datestart-input-id"  value="<?php echo date('Y-m-d'); ?>">
            <label> ~ </label>
            <input type="date" id="userpanel-dateend-input-id"  value="<?php echo date('Y-m-d'); ?>">
            
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
					<th>아이디</th>
					<?php if($mb_level > LEVEL_ADMIN + 1) :  ?>
						<th>비밀번호</th>
					<?php endif ?>
					<th>접속IP</th>
					<th>결과</th>
					<th>시간</th>
					<th>IP상태</th>
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
    <script src="<?php echo site_furl('/assets/js/member_try-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/member_try-script.js?v=1');?>"></script>
<?php endif ?>
<?= $this->endSection() ?>