<?= $this->extend('/home/conf_site') ?>
<?= $this->section('confsite-active') ?>본사설정<?= $this->endSection() ?>

<?= $this->section('confsite-navbar') ?>
	<?php if($mb_level > LEVEL_MASTER) :  ?>
        <button class="sub-navbar-but" style="display: block;" id="cleanDb-but" onclick="cleanDb(1);">디비초기화</button>
	<?php endif ?>
	<?php if($mb_level >= LEVEL_ADMIN && isEBalMode()) :  ?>
		<div style="float:right;">
			<input type="date" id="conf-dbclean-input-id" style="margin-right:5px; padding:2px;" value="<?php echo calcDate(-7);?>">
			<button class="sub-navbar-but" style="display: block; margin-right:10px;" id="deleteDb-but" onclick="cleanDb(2);">이전 내역정리</button>
		</div>
	<?php endif ?>
	
<?= $this->endSection() ?>

<?= $this->section('confsite-content') ?>

	<?php if (array_key_exists('app.lang', $_ENV) && $_ENV['app.lang'] > 0) : ?>
		<h4>
			<i class="glyphicon glyphicon-hand-right"></i> 언어별 설정			
			<select name="lang" id="confsite-lang-select-id" style="margin-left:30px; text-align:center; width:100px; padding:3px;">
				<option value="ko" > 한국어 </option>
				<option value="cn" > 중국어 </option>
			</select>
		</h4>
	<?php endif ?>
	<!---->
	<h4><i class="glyphicon glyphicon-hand-right"></i> 사이트설정</h4>
	<div class="confsite-site-text-div">
		<p>사이트명:</p>
		<input type="text" id="confsite-sitename-input-id" value="<?=$arrConfig[CONF_SITENAME-1]->conf_content?>">
	</div>
	<div class="confsite-site-text-div">
		<p>도메인명:</p>
		<input type="text" id="confsite-domainname-input-id" value="<?=$arrConfig[CONF_DOMAIN-1]->conf_content?>">
	</div>
	<div class="confsite-site-text-div">
		<?php if(intval($arrConfig[CONF_API_VACC-1]->conf_active) == STATE_ACTIVE) :  ?>
			<p>가상계좌API:</p>
			<input type="text" style="width:20%; margin-right:1px;" placeholder="URL" id="confsite-bankapi-url-id" value="<?=explode("#", trim($arrConfig[CONF_API_VACC-1]->conf_content))[0]?>">
			<input type="text" style="width:25%; " placeholder="API-KEY" id="confsite-bankapi-key-id" value="<?=explode("#", trim($arrConfig[CONF_API_VACC-1]->conf_content))[1]?>">
		<?php else :?>
			<p>입금통장:</p>
			<input type="text" style="width:20%; margin-right:1px;" placeholder="은행명" id="confsite-bankname-input-id" value="<?=explode("#", trim($arrConfig[CONF_CHARGEINFO-1]->conf_content))[0]?>">
			<input type="text" style="width:25%; margin-right:1px;" placeholder="예금주" id="confsite-bankown-input-id" value="<?=explode("#", trim($arrConfig[CONF_CHARGEINFO-1]->conf_content))[1]?>">
			<input type="text" style="width:25%; " placeholder="계좌번호" id="confsite-banknum-input-id" value="<?=explode("#", trim($arrConfig[CONF_CHARGEINFO-1]->conf_content))[2]?>">
		<?php endif ?>
		<?php if(array_key_exists('app.sess_act', $_ENV) && $_ENV['app.sess_act'] == 1) :?>
			<button class="confsite-cancel-button" style="margin-left:5px; padding:3px 10px; " id="confsite-charge-reset-id">리셋</button>
		<?php endif ?>
	</div>

	<?php if($arrConfig[CONF_CHARGE_URL-1]->conf_active == 1) :  ?>
	<div class="confsite-site-text-div">
		<p>충전주소:</p>
		<input type="text" style="width:20%;" id="confsite-chargeurl-input-id" value="<?=$arrConfig[CONF_CHARGE_URL-1]->conf_content?>">
	</div>
	<?php endif ?>

	<?php if($arrConfig[CONF_TELE_ID-1]->conf_active == 1) :  ?>
	<div class="confsite-site-text-div">
		<p>텔레아이디:</p>
		<input type="text" style="width:20%;" id="confsite-teleid-input-id" value="<?=$arrConfig[CONF_TELE_ID-1]->conf_content?>">
	</div>
	<?php endif ?>

	<h4><i class="glyphicon glyphicon-hand-right"></i> 공지사항 </h4>
	<div class="confsite-site-check-div">
		<?php if($arrConfig[CONF_NOTICE_MAIN-1]->conf_active != 1) : ?>
		<input type="checkbox" id="confsite-mainnotice-check-id">
		<?php else :?>
		<input type="checkbox" id="confsite-mainnotice-check-id" checked>				
		<?php endif ?>

		<label for="confsite-mainnotice-check-id"> 회원로그인시 메인공지사항</label>			
	</div>
	<div class="confsite-site-text-div">
		<textarea rows="" id="confsite-mainnotice-text-id"><?=$arrConfig[CONF_NOTICE_MAIN-1]->conf_content?></textarea>	
		<?php if (array_key_exists('app.lang', $_ENV) && $_ENV['app.lang'] > 0) : ?>
			<textarea rows="" id="confsite-mainnotice_cn-text-id" style="display:none;"><?=$arrConfig[CONF_NOTICE_MAIN-1]->conf_content_cn?></textarea>					
		<?php endif ?>

	</div>
	<div class="confsite-site-check-div" style="margin-top:15px">
		<?php if ($arrConfig[CONF_NOTICE_BANK-1]->conf_active != 1) : ?>
		<input type="checkbox" id="confsite-deposite-check-id">
		<?php else :?>
		<input type="checkbox" id="confsite-deposite-check-id" checked>
		<?php endif ?>
		<label for="confsite-deposite-check-id"> 회원로그인시 충환전공지사항</label>
		
		<span style="float:right; margin-right:19%; ">
			<label> 배경색</label>
			<?php if (strlen($arrConfig[CONF_NOTICE_BANK-1]->conf_idx) > 3) : ?>
				<input type="color" value="<?php echo $arrConfig[CONF_NOTICE_BANK-1]->conf_idx; ?>" id="confsite-deposite-color-id" style="padding:0; width:80px;">
			<?php else :?>
				<input type="color" value="#2A2A2A" id="confsite-deposite-color-id" style="padding:0; width:80px;">
			<?php endif ?>
		</span>
	</div>
	<style>
		#confsite-deposite-id .note-editing-area, #confsite-urgentnotice-id .note-editing-area{
			color:white;
		}
	</style>
	<div class="width:100%; clear:both; ">
		<?php if (strlen($arrConfig[CONF_NOTICE_BANK-1]->conf_idx) > 3) : ?>
			<form method="post" id="confsite-deposite-id" style="width:80%; margin-left:20px; background-color:<?php echo $arrConfig[CONF_NOTICE_BANK-1]->conf_idx; ?>;">
		<?php else :?>
			<form method="post" id="confsite-deposite-id" style="width:80%; margin-left:20px; background-color:#2A2A2A;">
		<?php endif ?>
			<textarea id="confsite-deposite-text-id" name="editordata"><?=$arrConfig[CONF_NOTICE_BANK-1]->conf_content?></textarea>
		</form>
		<?php if (array_key_exists('app.lang', $_ENV) && $_ENV['app.lang'] > 0) : ?>
			<?php if (strlen($arrConfig[CONF_NOTICE_BANK-1]->conf_idx) > 3) : ?>
				<form method="post" id="confsite-deposite_cn-id" style="display:none; width:80%; margin-left:20px; background-color:<?php echo $arrConfig[CONF_NOTICE_BANK-1]->conf_idx; ?>;">
			<?php else :?>
				<form method="post" id="confsite-deposite_cn-id" style="display:none; width:80%; margin-left:20px; background-color:#2A2A2A;">
			<?php endif ?>
					<textarea id="confsite-deposite_cn-text-id" name="editordata"><?=$arrConfig[CONF_NOTICE_BANK-1]->conf_content_cn?></textarea>					
				</form>
		<?php endif ?>
	</div>
	
	<div class="confsite-site-check-div">
		<?php if ($arrConfig[CONF_NOTICE_URGENT-1]->conf_active != 1) : ?>
			<input type="checkbox" id="confsite-urgentnotice-check-id">
		<?php else :?>
			<input type="checkbox" id="confsite-urgentnotice-check-id" checked>
		<?php endif ?>
		<label for="confsite-urgentnotice-check-id"> 회원로그인시 긴급공지사항</label>
		<span style="float:right; margin-right:19%; ">
			<label> 배경색</label>
		<?php if (strlen($arrConfig[CONF_NOTICE_URGENT-1]->conf_idx) > 3) : ?>
			<input type="color" value="<?php echo $arrConfig[CONF_NOTICE_URGENT-1]->conf_idx; ?>" id="confsite-urgentnotice-color-id" style="padding:0; width:80px;">
		<?php else :?>
			<input type="color" value="#2A2A2A" id="confsite-urgentnotice-color-id" style="padding:0; width:80px;">
		<?php endif ?>
		</span>
	</div>
	<div class="width:100%; clear:both; ">
		<?php if (strlen($arrConfig[CONF_NOTICE_URGENT-1]->conf_idx) > 3) : ?>
			<form method="post"  id="confsite-urgentnotice-id" style="width:80%; margin-left:20px; background-color:<?php echo $arrConfig[CONF_NOTICE_URGENT-1]->conf_idx; ?>;">
		<?php else :?>
			<form method="post"  id="confsite-urgentnotice-id" style="width:80%; margin-left:20px; background-color:#2A2A2A;">
		<?php endif ?>
			<textarea id="confsite-urgentnotice-text-id" name="editordata"><?=$arrConfig[CONF_NOTICE_URGENT-1]->conf_content?></textarea>
		</form>

		<?php if (array_key_exists('app.lang', $_ENV) && $_ENV['app.lang'] > 0) : ?>
			<?php if (strlen($arrConfig[CONF_NOTICE_URGENT-1]->conf_idx) > 3) : ?>
				<form method="post"  id="confsite-urgentnotice_cn-id" style="display:none; width:80%; margin-left:20px; background-color:<?php echo $arrConfig[CONF_NOTICE_URGENT-1]->conf_idx; ?>;">
			<?php else :?>
				<form method="post"  id="confsite-urgentnotice_cn-id" style="display:none; width:80%; margin-left:20px; background-color:#2A2A2A;">
			<?php endif ?>
				<textarea id="confsite-urgentnotice_cn-text-id" name="editordata"><?=$arrConfig[CONF_NOTICE_URGENT-1]->conf_content_cn?></textarea>					
			</form>
		<?php endif ?>

	</div>
	
	<?php if(!array_key_exists('app.site', $_ENV) || $_ENV['app.site'] == 0 ) :?>
	<h4><i class="glyphicon glyphicon-hand-right"></i> 충환전안내</h4>						
	<div class="confsite-site-check-div">
		<label>- 충전안내 및 주의사항</label>
	</div>
	<div class="width:100%; clear:both; ">
		<form method="post" style="width:80%; margin-left:20px; background-color:white;">
			<textarea id="confsite-chargemanual-text-id" name="editordata"><?=$arrConfig[CONF_CHARGE_MANUAL-1]->conf_content?></textarea>
		</form>
	</div>
	<div class="confsite-site-check-div">
		<label>- 환전안내 및 주의사항</label>
	</div>
	<div class="width:100%; clear:both; ">
		<form method="post" style="width:80%; margin-left:20px; background-color:white;">
			<textarea id="confsite-discharmanual-text-id" name="editordata"><?=$arrConfig[CONF_DISCHA_MANUAL-1]->conf_content?></textarea>
		</form>
	</div>
	<?php endif ?>

	<!---->
	<h4><i class="glyphicon glyphicon-hand-right"></i> 계좌문의 매크로</h4>
	<div class="width:100%; clear:both; ">
		<form method="post" id="confsite-bankmacro-id" style="width:80%; margin-left:20px; background-color:white;">
			<textarea id="confsite-bankmacro-text-id" name="editordata"><?=$arrConfig[CONF_CHARGEMACRO-1]->conf_content?></textarea>
		</form>
		<?php if (array_key_exists('app.lang', $_ENV) && $_ENV['app.lang'] > 0) : ?>
			<form method="post" id="confsite-bankmacro_cn-id" style="width:80%; margin-left:20px; background-color:white; display:none;">
				<textarea id="confsite-bankmacro_cn-text-id" name="editordata"><?=$arrConfig[CONF_CHARGEMACRO-1]->conf_content_cn?></textarea>					
			</form>
		<?php endif ?>
	</div>
	<h4><i class="glyphicon glyphicon-hand-right"></i> 회원이용정책</h4>
	<div class="confsite-site-check-div">
		<?php if ($arrConfig[CONF_MULTI_LOGIN-1]->conf_active != 1) : ?>
			<input type="checkbox" id="confsite-multilog-check-id">
		<?php else :?>
			<input type="checkbox" id="confsite-multilog-check-id" checked>
		<?php endif ?>
		<label for="confsite-multilog-check-id"> 중복로그인 허용</label>
	</div>
	<div class="confsite-site-check-div">
		<?php if ($arrConfig[CONF_TRANS_DENY-1]->conf_active == 1) : ?>
			<input type="checkbox" id="confsite-transdeny-check-id">
		<?php else :?>
			<input type="checkbox" id="confsite-transdeny-check-id" checked>
		<?php endif ?>
		<label for="confsite-transdeny-check-id"> 하부회원 머니이동 허용</label>
	</div>
	<div class="confsite-site-check-div" style="padding-left:50px;">
		<?php if ($arrConfig[CONF_TRANS_LV1-1]->conf_active != 1) : ?>
			<input type="checkbox" id="confsite-translv1-check-id">
		<?php else :?>
			<input type="checkbox" id="confsite-translv1-check-id" checked>
		<?php endif ?>
		<label for="confsite-translv1-check-id"> 1단계만 허용</label>
	</div>
	<div class="confsite-site-check-div">
		<?php if ($arrConfig[CONF_RETURN_DENY-1]->conf_active == 1) : ?>
			<input type="checkbox" id="confsite-returndeny-check-id" >
		<?php else :?>
			<input type="checkbox" id="confsite-returndeny-check-id" checked>
		<?php endif ?>
		<label for="confsite-returndeny-check-id"> 하부회원 머니환수 허용</label>
	</div>
	<div class="confsite-site-check-div" style="padding-left:50px;">
		<?php if ($arrConfig[CONF_RETURN_LV1-1]->conf_active != 1) : ?>
			<input type="checkbox" id="confsite-returnlv1-check-id">
		<?php else :?>
			<input type="checkbox" id="confsite-returnlv1-check-id" checked>
		<?php endif ?>
		<label for="confsite-returnlv1-check-id"> 1단계만 허용</label>
	</div>

	<?php if(array_key_exists('app.tree', $_ENV) && $_ENV['app.tree'] == 1) {
		 $arrInfo = explode('#', $arrConfig[CONF_DELAY_PLAY-1]->conf_idx);
		 if(count($arrInfo) < 2){
			$arrInfo[0] = 0;
			$arrInfo[1] = 0;
		 }
		?>
		<div class="confsite-site-check-div">
			<label> &nbsp;&nbsp;유저&nbsp;&nbsp; </label>
			<input type="number" style="width:75px; margin-left:10px;" placeholder="" id="confsite-userout-input-id" value="<?=$arrInfo[0]?>">
			<label> 분후 자동로그아웃</label>
		</div>
		<div class="confsite-site-check-div">
			<label> 관리자 </label>
			<input type="number" style="width:75px; margin-left:10px;" placeholder="" id="confsite-adminout-input-id" value="<?=$arrInfo[1]?>">
			<label> 분후 자동로그아웃</label>
		</div>
	<?php } ?>

	<?php if(array_key_exists('app.tree', $_ENV) && $_ENV['app.tree'] == 1) :  ?>
	<h4><i class="glyphicon glyphicon-hand-right"></i> 은행이용정책</h4>
	<div class="confsite-site-check-div">
		<?php if (count( explode("#", trim($arrConfig[CONF_CHARGE_MANUAL-1]->conf_idx)) ) >= 2 ) : ?>
			<input type="checkbox" id="confsite-exchange-check-id" <?=explode("#", trim($arrConfig[CONF_CHARGE_MANUAL-1]->conf_idx))[0] == 1 ? "checked":""?>>
			<label for="confsite-exchange-check-id"> 환전시간설정</label>
			<input type="number" style="width:105px; margin-left:10px;" placeholder="환전시간" id="confsite-exchange-input-id" value="<?=explode("#", trim($arrConfig[CONF_CHARGE_MANUAL-1]->conf_idx))[1]?>">
		<?php else:?>
			<input type="checkbox" id="confsite-exchange-check-id" >
			<label for="confsite-exchange-check-id"> 환전시간설정</label>
			<input type="number" style="width:105px; margin-left:10px;" placeholder="환전시간" id="confsite-exchange-input-id" value="">
		<?php endif?>
		<label> 시간</label>
	</div>
	<div class="confsite-site-check-div">
		<?php if (count( explode("#", trim($arrConfig[CONF_DISCHA_MANUAL-1]->conf_idx)) ) >= 3 ) : ?>
			<input type="checkbox" id="confsite-bank-check-id" <?=explode("#", trim($arrConfig[CONF_DISCHA_MANUAL-1]->conf_idx))[0] == 1 ? "checked":""?>>
			<label for="confsite-bank-check-id"> 은행점검시간</label>
			<input type="time" id="confsite-bankstart-input-id" style="margin-left:10px;"   value="<?=explode("#", trim($arrConfig[CONF_DISCHA_MANUAL-1]->conf_idx))[1]?>">
			<label> ~ </label>
			<input type="time" id="confsite-bankend-input-id"  value="<?=explode("#", trim($arrConfig[CONF_DISCHA_MANUAL-1]->conf_idx))[2]?>">
		<?php else :?>
			<input type="checkbox" id="confsite-bank-check-id" >
			<label for="confsite-bank-check-id"> 은행점검시간</label>
			<input type="time" id="confsite-bankstart-input-id" style="margin-left:10px;"   value="">
			<label> ~ </label>
			<input type="time" id="confsite-bankend-input-id"  value="">
		<?php endif?>
	</div>
	<?php endif ?>

	<?php if(isEBalMode() && array_key_exists('app.sess_act', $_ENV) && $_ENV['app.sess_act'] == 1) :?>
	<h4><i class="glyphicon glyphicon-hand-right"></i> 유저페이지 설정</h4>
	<div class="confsite-site-check-div">
		<input type="checkbox" id="confsite-autoapps-check-id" <?=$arrConfig[CONF_AUTOAPPS-1]->conf_idx==1?'checked':''?> onchange="onChangeElement();">
		<label for="confsite-multilog-check-id"> 오토앱 메뉴 보이기</label>
	</div>
		<?php $arrInfo = explode(';', $arrConfig[CONF_AUTOAPPS-1]->conf_content);
		foreach($arrInfo as $objInfo){
			$info = explode('#', $objInfo);
			if(count($info) < 2)
				continue;
			$app = new \stdClass();
			$app->ename = $info[0];
			$app->name = $info[1];
			$app->path = $info[2];
			$app->act = 1;
			if(count($info) > 3){
				$app->act = intval($info[3]);
			}
			?>

		<div class="confsite-site-check-div" style="padding-left:50px;">
			<input type="checkbox" name="auto-apps"  data-ename="<?=$app->ename?>" data-name="<?=$app->name?>" data-path="<?=$app->path?>" <?=$app->act==1?'checked':''?> >
			<label > <?=$app->name?> 승인</label>
		</div>
		<?php } ?>
	<?php endif ?>

	<div class="confsite-button-group">
		<button class="confsite-cancel-button" id="confsite-cancel-btn-id">취소</button>
		<button class="confsite-ok-button" id="confsite-ok-btn-id">저장</button>
	</div>
<?= $this->endSection() ?>

<?= $this->section('confsite-script') ?>
	<?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
		<script src="<?php echo site_furl('/assets/js/confsite-script.js?t='.time());?>"></script>
	<?php else : ?>
		<script src="<?php echo site_furl('/assets/js/confsite-script.js?v=1');?>"></script>
	<?php endif ?>
<?= $this->endSection() ?>
