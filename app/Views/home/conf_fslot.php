<?= $this->extend('/home/conf_game') ?>
<?= $this->section('confgame-active') ?><?=$game_name?><?= $this->endSection() ?>
<?= $this->section('confgame-content') ?>
	 <style>
		tbody .act{
			width:100px;
		}
	</style>
		<!---->
		<h4 style="font-size:16px; font-weight:bold;"><i class="glyphicon glyphicon-hand-right"></i> 게임별 설정</h4>	
		<div>
			<label>게임업체</label>
			<select class="pbresult-game-select" id="confsite-prd-select-id">
				<?php foreach ($game_prds as $prd):?>
					<?php if($prd->code == 215) {$prd->name_kr=$prd->name_kr."(NEW)"; }?>
					<?php if($_ENV['app.type'] == APP_TYPE_2) :?>
						<option value="<?=$prd->code?>"><?=$prd->name_kr?></option>
					<?php elseif($prd->ref_code > 0):?>
						<option value="<?=$prd->code?>"><?=$prd->name_kr?></option>
					<?php endif ?>
				<?php endforeach;?>
			</select>
			<label style="margin-left:20px;">게임명</label>
			<input type="text" class="pbresult-text-input" id="confsite-game-input-id" style="width:150px; margin-right:0;">
			<select name="pbresult-number" class="pbresult-number-select" id="confsite-number-select-id">
				<?php foreach($select_nums as $num=>$select):?>
					<option value="<?=$num?>" <?=$select?>><?=$num?>개</option>
				<?php endforeach?>
			</select>
			<button class="pbresult-list-view-but" id="confsite-list-view-but-id">검색</button>  
		</div>
		
		<Table class="user-table" style="margin-top: 15px;">
			<thead>
				<tr>
					<th>번호</th>
					<th></th>
					<th>게임업체</th>
					<th>게임명(ko)</th>
					<th>게임명(en)</th>
					<th>노출상태</th>
					<th>운영상태</th>
					<th>API타입</th>
				</tr>
			</thead>
			<tbody  id="confsite-table-data">
				
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

		<h4 style="font-size:16px; font-weight:bold;"><i class="glyphicon glyphicon-hand-right"></i> 게임 설정</h4>	
		<div class="confsite-game-text-div">
			<p>유저 게임승인:</p> 
			<input type="checkbox" id="confpb-bet-check-id" style="zoom:120%; margin-top:4px;">
			<label style="font-size:13px; font-weight:normal; padding-top:0px;"> 유저게임승인</label>
		</div>
		<div class="confsite-game-text-div">
			<p>에이젼트 코드:</p> 
			<input type = "text" class="conf-text-input" style="min-width:200px;"  id="confpb-agent-code-id" disabled>
		</div>
		<div class="confsite-game-text-div">
			<p>에이젼트 보유알:</p> 
			<input type = "text" class="conf-text-input" style="min-width:200px;" id="confpb-agent-egg-id"  disabled>
			<button class="refresh_btn" title="조회" id="refresh_egg" style="margin-left:5px; margin-top:5px;"></button>
			<label style="font-size:13px; font-weight:normal; padding-top:0px; color:red;" id="err_msg"></label> 
		</div>
		<div class="confsite-game-text-div">
			<p>회원 보유알:</p> 
			<input type = "text" class="conf-text-input" style="min-width:200px;" id="confpb-user-egg-id"  disabled>
			<button class="refresh_btn" title="조회" id="refresh_useregg" style="margin-left:5px; margin-top:5px;"></button>
			<button class="recovery_btn" title="회수" id="recovery_useregg" style="margin-left:10px; margin-top:2px;"></button>
		</div>
		<!-- <div class="confsite-game-text-div">
			<p>에이젼트 페이지:</p>
			<button class="confsite-cancel-button" id="confsite-agent-btn-id" style="margin-bottom:20px; width:200px;">바로 가기</button>
		</div> -->
		
		<div class = "confsite-button-group" style="margin-top:20px;">
			<button class="confsite-cancel-button" id="confsite-cancel-btn-id">취소</button>
			<button class="confsite-ok-button"  id="confsite-ok-btn-id">저장</button>
		</div>
	<?= $this->endSection() ?>

<?= $this->section('confgame-script') ?>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
    <script src="<?php echo site_furl('/assets/js/confcs-script.js?t='.time());?>"></script>
    <script src="<?php echo site_furl('/assets/js/confsl-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/confcs-script.js?v=3');?>"></script>
    <script src="<?php echo site_furl('/assets/js/confsl-script.js?v=1');?>"></script>
<?php endif ?>
<?= $this->endSection() ?>