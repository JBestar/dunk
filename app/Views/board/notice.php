<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	<!--Sub Navbar-->
	<div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-info-sign"></i> 게시판::공지사항</p>
	</div>

	<div class="user-panel">
		<div style="min-height:30px;">
			<a href="<?php echo siteFurl().'board/notice_edit/0';?>" class="user-panel-add-a" >공지사항 새로 등록</a>
		</div>
		<Table class="user-table" id="notice-table-id">
			<tr>
				<th>번호</th>
				<th>분류</th>
				<th>공개</th>
				<th>공지타입</th>
				<th>제목</th>
				<th>등록일</th>
				<th>등록자</th>
				<th>처리</th>				
			</tr>
			<?php $i=1; foreach ($arrNotice as $obNotice):?>
			<tr>
				<td><?=$i++?></td>
				<td>
					<?php if($obNotice->notice_type == 1) {  ?>
						사이트공지
					<?php } else if($obNotice->notice_type == 2){?>
						게시판
					<?php } else {?>
						고객센터
					<?php } ?>	
				</td>
				<td>
					<?php if($obNotice->notice_state_active == 1) {  ?>
					<button name="<?=$obNotice->notice_fid?>" class="button-active">공개</button>
					<?php } else {?>
					<button name="<?=$obNotice->notice_fid?>" >비공개</button>
					<?php } ?>
				</td>
				<td>
					<?php if($obNotice->notice_read_count > 0) {  ?>
					<button name="<?=$obNotice->notice_fid?>" class="button-active <?=$obNotice->notice_state_active==1?'popup':''?>">팝업</button>
					<?php } else {?>
					<button name="<?=$obNotice->notice_fid?>" >일반</button>
					<?php } ?>
				</td>
				<td><?=$obNotice->notice_title?></td>
				<td><?=$obNotice->notice_time_create?></td>
				<td><?=$obNotice->notice_mb_uid?></td>
				<td>
					<a href="<?php echo siteFurl().'board/notice_edit/'.$obNotice->notice_fid;?>" >수정</a>
					<button name="<?=$obNotice->notice_fid?>">삭제</button>
				</td>
			</tr>
			<?php endforeach;?>
		</Table>

	</div>


<!--main_navbar.php-main-container-->
</div>

<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
    <script src="<?php echo site_furl('/assets/js/notice-script.js?v=1');?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/notice-script.js?t='.time());?>"></script>
<?php endif ?>
<?= $this->endSection() ?>