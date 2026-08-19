<?= $this->extend('header') ?>
<?= $this->section('content') ?>
	<script src="<?php echo site_furl('/assets/js/summernote-lite.js');?>"></script>
	<script src="<?php echo site_furl('/assets/js/summernote-ko-KR.js');?>"></script>

	<link rel="stylesheet" href="<?php echo site_furl('/assets/css/summernote-lite.css');?>">
  	<!--Sub Navbar-->
	<div class = "sub-navbar">
		<?php if(is_null($objNotice)) {  ?>
			<p><i class="glyphicon glyphicon-info-sign"></i> 쪽지 작성</p>		
		<?php } else if($objNotice->notice_type == NOTICE_CUSTOMER) {  ?>
			<p><i class="glyphicon glyphicon-info-sign"></i> 고객문의 회답</p>
		<?php } else {?>
			<p><i class="glyphicon glyphicon-info-sign"></i> 쪽지 수정</p>
		<?php } ?>
		
		<?php if(is_null($objNotice)) {  ?>
			<p id="subnavbar-fid-p-id" hidden>0</p>		
			<p id="subnavbar-type-p-id" hidden>0</p>		
		<?php } else {?>
			<p id="subnavbar-fid-p-id" hidden><?=$objNotice->notice_fid?></p>
			<p id="subnavbar-type-p-id" hidden><?=$objNotice->notice_type?></p>
		<?php } ?>
	</div>
	<style>
	.main-container{
		min-width:1250px;
	}
	.useredit-panel{
		padding:30px 20px;
	}
	.useredit-text-div p{
		width:100px;	
	}
	.useredit-text-div input[type=checkbox] {
		zoom: 130%;
		margin-top: 5px;
		width:20px;
	}
	.useredit-text-div  .content {
		overflow: auto;
		resize: vertical;
	}
	</style>
	<!--Site Setting-->
	<div class="useredit-panel" style="height:100%;">
		<div style="float:left; width:700px; ">
				<div class="useredit-text-div">
					<p >발송자아이디:</p>
					<?php if(is_null($objNotice)) :  ?>
						<label for="notice-mbuid-input-id">전체는 '*'로 입력하세요</label>
						<input type = "text" id="notice-mbuid-input-id" style="width:250px;" value="<?=$strUserId ?>">
					<?php else :?>
						<input type = "text" id="notice-mbuid-input-id" style="width:250px; cursor:text" value="<?=$objNotice->notice_mb_uid?>" disabled>
						<?php if($objNotice->notice_type == NOTICE_CUSTOMER) :  ?>
							<span style="font-size:14px; margin-left:10px; position:relative; top:4px; ">닉네임:
								<a onclick="popupMemberUid('<?=$senderId?>')" style="color:blue; font-size:16px; cursor:pointer; margin-top:3px;"> <?=$senderName?></a>
							</span>
						<?php endif ?>

					<?php endif ?>
				</div>
				<!---->
				<div class="useredit-text-div">
					<p>발송(대기):</p>
					<?php if(is_null($objNotice)) :  ?>
					<input type="checkbox" id="notice-state-check-id" name="public" checked>
					<?php elseif($objNotice->notice_type == NOTICE_CUSTOMER) :  ?>	
					<input type="checkbox" id="notice-state-check-id" name="public" checked>
					<?php elseif($objNotice->notice_state_active == 0) :  ?>	
					<input type="checkbox" id="notice-state-check-id" name="public">
					<?php else :?>
					<input type="checkbox" id="notice-state-check-id" checked name="public">
					<?php endif ?>
					<label for="public" style="font-size:14px;">발송</label>

					<?php if(is_null($objNotice) || $objNotice->notice_type==NOTICE_MSG) : ?>
						<?php if (array_key_exists('app.lang', $_ENV) && $_ENV['app.lang'] > 0) : ?>
							<div style="float:right; width:50%;">
								<label style="float:left; margin-left:115px;">언어별 설정</label>
								<select name="lang" id="notice-lang-select-id" style="margin-left:5px; text-align:center; width:100px; padding:3px;">
									<option value="ko" > 한국어 </option>
									<option value="cn" > 중국어 </option>
								</select>
							</div>
						<?php endif ?>
					<?php endif ?>
				</div>
				<div class="useredit-text-div">
					<p>제목:</p>
					<?php if(is_null($objNotice)) :  ?>	
					<input type = "text" id="notice-title-input-id" style="width:550px;">
					<?php else :?>
					<input type = "text" id="notice-title-input-id" value="<?=$objNotice->notice_title?>" style="width:550px;">
					<?php endif ?>

					<?php if(is_null($objNotice) || $objNotice->notice_type==NOTICE_MSG) : ?>
						<?php if (array_key_exists('app.lang', $_ENV) && $_ENV['app.lang'] > 0) : ?>
							<?php if(is_null($objNotice)) :  ?>	
							<input type = "text" id="notice-title_cn-input-id" style="width:550px; display:none;">
							<?php else :?>
							<input type = "text" id="notice-title_cn-input-id" value="<?=$objNotice->notice_title_cn?>" style="width:550px; display:none;">
							<?php endif ?>
						<?php endif ?>
					<?php endif ?>

				</div>

				
			<?php if(!is_null($objNotice) && $objNotice->notice_type == NOTICE_CUSTOMER) :  ?>
				<div class="useredit-text-div">
					<p>문의내용:</p> 
					<div class="content" id="custom-content" style="white-space: pre-wrap; width:550px; background-color:white; padding:5px;" ><?=str_replace("&quot;", "'", $objNotice->notice_content)?></div>	
				</div>
			
				<div style="width:100%; clear:both;">
					<p style="width:100px; float:left; padding:5px;">회답내용:</p> 
					<form method="post" style="width:550px; float:left;background-color:white;">
					<textarea id="custom-answer" name="editordata"><?php if(!is_null($objNotice)) : ?><?=str_replace("&quot;", "'", $objNotice->notice_answer)?><?php endif ?></textarea>
					</form>	
				</div>
			<?php else :?>
				<div style="width:100%; clear:both;">
					<p style="width:100px; float:left; padding:5px;">쪽지내용:</p> 
					<form method="post" id="notice-form" style="width:550px;float:left;background-color:white;">
						<textarea id="notice-content" name="editordata"><?php if(!is_null($objNotice)) : ?><?=str_replace("&quot;", "'", $objNotice->notice_content)?><?php endif ?></textarea>
					</form>	
					<?php if (array_key_exists('app.lang', $_ENV) && $_ENV['app.lang'] > 0) : ?>
						<form method="post" id="notice-form_cn" style="width:550px;float:left;background-color:white;display:none;">
							<textarea id="notice-content_cn" name="editordata"><?php if(!is_null($objNotice)) : ?><?=str_replace("&quot;", "'", $objNotice->notice_content_cn)?><?php endif ?></textarea>
						</form>
					<?php endif ?>
				</div>
			<?php endif ?>

				<div class = "useredit-button-group" style="padding-left:100px">
					<button class="useredit-cancel-button" id="notice-cancel-btn-id">취소</button>
					<?php if(is_null($objNotice) || $objNotice->notice_type==NOTICE_MSG) :  ?>
					<button class="useredit-ok-button"  id="notice-save-btn-id">발송</button>			
					<?php else :?>
					<button class="useredit-ok-button"  id="notice-save-btn-id">회답</button>
					<?php endif ?>
					
				</div>
			</div>
			
			<div style="float:left;  width:450px;">

				<Table class="user-table" style="margin-top: 80px;">
					<thead>
						<tr>
							<th style="width:50px;"></th>
							<th>매크로제목</th>
						</tr>
					</thead>
					<tbody  id="confsite-table-data">
						
					</tbody>

				</Table>

				<div class="pbresult-list-page-div">
					
					<div class="pagination" id="list-page" style="display:none;">
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
		</div>


	</div>

<!--main_navbar.php-->
</div>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
    <script src="<?php echo site_furl('/assets/js/page.js?v=1');?>"></script>
    <script src="<?php echo site_furl('/assets/js/message_edit-script.js?v=1');?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/page.js?t='.time());?>"></script>
    <script src="<?php echo site_furl('/assets/js/message_edit-script.js?t='.time());?>"></script>
<?php endif ?>
<?= $this->endSection() ?>