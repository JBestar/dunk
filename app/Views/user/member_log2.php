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
		<p><i class="glyphicon glyphicon-user"></i> 회원관리::아이피관리</p>
	</div>
	<!--Site Setting-->
	<div class="user-panel">	
		<div>
			<select name="pbresult-level" class="pbresult-number-select" id="userpanel-type-select-id" stype="width:80px">
				<option value="3">아이피</option>
				<option value="0">아이디</option>
				<option value="1">닉네임</option>
				<option value="2">등록번호</option>
			</select>
            <input type="text" class="pbresult-text-input" style="width:150px;" id="userpanel-userid-input-id" >
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
					<th>닉네임</th>
					<th>등록번호</th>
					<th>접속IP</th>
					<th>접속시간</th>
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
    <script src="<?php echo site_furl('/assets/js/member_log2-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/member_log2-script.js?v=2');?>"></script>
<?php endif ?>
<?= $this->endSection() ?>