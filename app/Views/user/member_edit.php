<?php echo $this->extend('user/user_edit'); ?>
<?php echo $this->section('user-edit-title'); ?>회원<?php echo $this->endSection(); ?>
<?php echo $this->section('user-edit-check-level'); ?>
<?php echo $this->endSection(); ?>
<?php echo $this->section('user-edit-form-section0'); ?>
<div class="useredit-text-div">
	<p>추천인:</p>
	<input type="text" id="useredit-sort-select-id" value="<?=$emp_uid; ?>" 
	<?= ($mb_level < LEVEL_ADMIN?'disabled':'')?>
	/>

</div>
<?php echo $this->endSection(); ?>
<?php echo $this->section('user-edit-form-section1'); ?>
<div class="useredit-text-div">
	<p>Lv:</p>
	<?php if ($mb_level >= LEVEL_ADMIN) {  ?>
	<select type="text" id="useredit-level-select-id">
	<?php } else { ?>
	<select type="text" id="useredit-level-select-id" disabled>
		<?php } ?>

		<?php foreach (range(1, 10) as $useLevel) { ?>
		<?php if (is_null($objMember) || ($objMember->mb_grade != $useLevel)) {  ?>
		<option value="<?php echo $useLevel; ?>">Lv <?php echo $useLevel; ?></option>
		<?php } else {?>
		<option value="<?php echo $useLevel; ?>" selected>Lv <?php echo $useLevel; ?></option>
		<?php } ?>
		<?php }?>
	</select>
</div>
<?php echo $this->endSection(); ?>
<?php echo $this->section('user-edit-form-section2'); ?>

<?php if ($mb_level >= LEVEL_ADMIN) :  ?>
	<div class="useredit-text-div">
		<p>색깔:</p>
		<?php if (is_null($objMember) || is_null($objMember->mb_color)) {  ?>
		<input type="color" value="#ffffff" id="useredit-color-input-id">
		<?php } else {?>
		<input type="color" value="<?php echo $objMember->mb_color; ?>" id="useredit-color-input-id">
		<?php } ?>
	</div>
<?php endif?>

<?php echo $this->endSection(); ?>
<?php echo $this->section('user-edit-script'); ?>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
    <script src="<?php echo site_furl('/assets/js/member_common-script.js?t='.time());?>"></script>
	<script src="<?php echo site_furl('/assets/js/member_edit-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('/assets/js/member_common-script.js?v=1');?>"></script>
	<script src="<?php echo site_furl('/assets/js/member_edit-script.js?v=1');?>"></script>
<?php endif ?>
<?php echo $this->endSection(); ?>