<?= $this->extend('/ebal/ebal_cont') ?>
<?= $this->section('ebal-active') ?><?=$game_name?><?= $this->endSection() ?>
<?= $this->section('eval_content') ?>

	<div class="confsite-site-panel">

		<h4><i class="glyphicon glyphicon-hand-right"></i> 누르기 변동내역</h4>
		<div class="pbresult-list-div" style="padding-left:20px;">
			<label>기간</label>
			<input type="text" id="pbhistory-datestart-input-id" class="date-hour" value="<?php echo date('Y-m-d')." 00:00"; ?>" readonly>
			<label> ~ </label>
			<input type="text" id="pbhistory-dateend-input-id" class="date-hour" value="<?php echo calcDate(1)." 00:00"; ?>" style="margin-right:10px" readonly>
			<input type="text" placeholder="     아이디" class="pbresult-text-input" id="pbhistory-userid-input-id" >
			<select name="pbresult-number" class="pbresult-number-select" id="pbhistory-type-select-id" style="width:70px; margin-left:-5px;">
				<option value="0">전체</option>
				<option value="1">수동</option>
				<option value="2">자동</option>
			</select>
			<select name="pbresult-number" class="pbresult-number-select" id="pbhistory-number-select-id" style="width:70px; margin-left:0px;">
				<?php foreach($select_nums as $num=>$select):?>
					<option value="<?=$num?>" <?=$select?>><?=$num?>개</option>
				<?php endforeach?>
			</select>
			<button class="pbresult-list-view-but" id="pbhistory-list-view-but-id">검색</button>
		</div>
		<Table class="bet-table">
			<thead>
				<tr>
					<th>ID</th>
					<th>아이디</th>
					<th>변동상태</th>
					<th>변동타입</th>
					<th>변동시간</th>
					<th>설명</th>
				</tr>
			</thead>
			<tbody id="pbbet-table-id">

			</tbody>
		</Table>
		<div class="pbresult-list-page-div">
			<div class="pagination"  id="list-page"  style="display:none">
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
    <script src="<?php echo site_furl('/assets/js/conf_epresslog-script.js?v=1');?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?t='.time());?>"></script>
    <script src="<?php echo site_furl('/assets/js/conf_epresslog-script.js?t='.time());?>"></script>
<?php endif ?>

<?= $this->endSection() ?>
