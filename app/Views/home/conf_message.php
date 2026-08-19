<?= $this->extend('/home/conf_site') ?>
<?= $this->section('confsite-active') ?>쪽지매크로<?= $this->endSection() ?>

<?= $this->section('confsite-navbar') ?>
<?= $this->endSection() ?>

<?= $this->section('confsite-content') ?>
	<!--Sub Navbar-->
	<h4><i class="glyphicon glyphicon-hand-right"></i> 쪽지매크로 등록</h4>

<!--Site Setting-->
	<div class="useredit-panel">

		<div style="width:100%; clear:both;">
			<div style="width:100%; clear:both;">
				<p style="width:100px; float:left; padding:5px;">매크로제목:</p> 
				<input type = "text" id="macro-title" style="width:600px; float:left;background-color:white;">
				<button class="useredit-ok-button" id="notice-save-btn-id">등록</button>
			</div>
			<div style="width:100%; clear:both;">
				<p style="width:100px; float:left; padding:5px;">매크로내용:</p> 

				<form method="post" style="width:600px; float:left;background-color:white;">
				<textarea id="macro-content" name="editordata"></textarea>
				</form>	
				<button class="useredit-ok-button" name="" id="notice-modify-btn-id" style="display:none; background-color:#ffaf3d;">수정</button>
			</div>
		</div>

		<Table class="user-table" style="margin-top: 15px; width:850px;">
			<thead>
				<tr>
					<th>번호</th>
					<th>제목</th>
					<th>매크로내용</th>
					<th></th>
				</tr>
			</thead>
			<tbody  id="confsite-table-data">
				
			</tbody>

		</Table>

		<div class="pbresult-list-page-div"  style="width:850px;">
			
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

<?= $this->endSection() ?>

<?= $this->section('confsite-script') ?>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
    <script src="<?php echo site_furl('/assets/js/page.js?t='.time());?>"></script>
    <script src="<?php echo site_furl('/assets/js/conf_message-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/conf_message-script.js?v=1');?>"></script>
<?php endif ?>
<?= $this->endSection() ?>
