<?= $this->extend('/home/conf_site') ?>
<?= $this->section('confsite-active') ?>디비정리<?= $this->endSection() ?>

<?= $this->section('confsite-navbar') ?>
<?= $this->endSection() ?>

<?= $this->section('confsite-content') ?>



    <div class = "confsite-button-group">
        <button class="confsite-cancel-button" id="confsite-cancel-btn-id">취소</button>
        <button class="confsite-ok-button" id="confsite-ok-btn-id">저장</button>
    </div>
<?= $this->endSection() ?>

<?= $this->section('confsite-script') ?>
    <?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
        <script src="<?php echo site_furl('/assets/js/confclean-script.js?t='.time());?>"></script>
    <?php else : ?>
        <script src="<?php echo site_furl('/assets/js/confclean-script.js?v=1');?>"></script>
    <?php endif ?>
<?= $this->endSection() ?>