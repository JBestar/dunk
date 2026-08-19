<?= $this->extend('user/header') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?php echo site_furl('assets/css/app.css?v=3');?>">
<link rel="stylesheet" href="<?php echo site_furl('assets/css/admin.css?v=1');?>">
  	
<style>
	.table-black input{
		min-width:150px;
	}
</style>

  	<!--Sub Navbar-->
	  <div class = "sub-navbar">
		<p><i class="glyphicon glyphicon-user"></i> <?= $master->mb_uid ?>(<?= $master->mb_nickname ?>) 회원을 따라가기</p>
		
	</div>
	<!--Site Setting-->
	<div class="useredit-panel">
		<table style="width: 100%;" class="table table-bordered table-black user_info">
			<thead>
				<tr>
					<th>번호</th>
					<th>아이디</th>
					<th>닉네임</th>
					<th>금액(%)</th>
				</tr>
			</thead>
			<tbody>
				<?php $rates = followRates(); $i=0; foreach($followers as $objMember) : ?>
				<tr>
					<td><?=++$i?></td>
					<td><?=$objMember->mb_uid?></td>
					<td><?=$objMember->mb_nickname?></td>
					<td>
						<select type="text" data-fid="<?=$objMember->mb_fid?>" data-master="<?=$master->mb_uid?>" >
								<?php foreach($rates as $rate):?>
									<option value="<?=$rate?>" <?=$objMember->mb_follow_percent==$rate?'selected':''?>><?=$rate?> %</option>
							<?php endforeach?>
						</select>
					</td>
				</tr>
				<?php endforeach?>

			</tbody>
		</table>
		<div class = "useredit-button-group" style="text-align:center;">
			<button class="useredit-cancel-button"  id="useredit-cancel-btn-id"  style="float:none;">닫기</button>
		</div>
	</div>


	<?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
    	<script src="<?php echo site_furl('/assets/js/member_common-script.js?t='.time());?>"></script>
		<script src="<?php echo site_furl('/assets/js/member_follow-script.js?t='.time());?>"></script>
	<?php else : ?>
    	<script src="<?php echo site_furl('/assets/js/member_common-script.js?v=1');?>"></script>
		<script src="<?php echo site_furl('/assets/js/member_follow-script.js?v=1');?>"></script>
	<?php endif ?>
<?= $this->endSection() ?>
