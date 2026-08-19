<?= $this->extend('/home/conf_site') ?>
<?= $this->section('confsite-active') ?>도메인설정<?= $this->endSection() ?>

<?= $this->section('confsite-navbar') ?>
<?= $this->endSection() ?>

<?= $this->section('confsite-content') ?>
    <div>
        <label>도메인</label>
        <input type="text" class="pbresult-text-input" id="confsite-domain-input-id" style="width:150px; margin-right:0;">
        <button class="pbresult-list-view-but" id="confsite-list-add-but-id">추가</button>  
    </div>

    <Table class="user-table" style="margin-top: 15px; width:600px;">
        <thead>
            <tr>
                <th>번호</th>
                <th>도메인</th>
                <th>설정</th>
                <th>삭제</th>
            </tr>
        </thead>
        <tbody  id="confsite-table-data">
            
        </tbody>

    </Table>

<?= $this->endSection() ?>

<?= $this->section('confsite-script') ?>
    <?php if($_ENV['CI_ENVIRONMENT'] == ENV_DEVELOPMENT) :?>
        <script src="<?php echo site_furl('/assets/js/confdomain-script.js?t='.time());?>"></script>
    <?php else : ?>
        <script src="<?php echo site_furl('/assets/js/confdomain-script.js?v=1');?>"></script>
    <?php endif ?>
<?= $this->endSection() ?>