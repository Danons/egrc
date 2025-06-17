<?php if (($_SESSION[SESSION_APP]['loginas'])) { ?>
    <div class="alert alert-warning">
        Anda sedang mengakses user lain. <a href="<?= site_url("panelbackend/home/loginasback") ?>" class="alert-link">Kembali</a>.
    </div>
<?php } ?>
<div class="row">
    <?php
    unset($mtjeniskajianrisikoarr['']);
    foreach ($mtjeniskajianrisikoarr as $k => $v) {
        if (in_array($k, $kajiankuarr)) { ?>
            <div class="col-md-4 col-sm-6 no-padding">
                <a href="<?= site_url('panelbackend/risk_scorecard/index/' . $k) ?>" class="a-area-folder-risk-register">
                    <div class="area-folder-risk-register p-4">
                        <img src="<?= base_url('assets/images/folderopen.png') ?>" class="img-responsive" alt="Risk Register">
                        <h4 class="text-center"><?= strtoupper($v) ?></h4>
                    </div>
                </a>
            </div>
        <?php } else { ?>
            <div class="col-md-4 col-sm-6 no-padding">
                <a href="<?= site_url('panelbackend/risk_scorecard/index/' . $k) ?>" class="a-area-folder-risk-register">
                    <div class="area-folder-risk-register p-4">
                        <img src="<?= base_url('assets/images/folderopen.png') ?>" class="img-responsive" alt="Risk Register">
                        <h4 class="text-center"><?= strtoupper($v) ?></h4>
                    </div>
                </a>
            </div>
        <?php } ?>

    <?php } ?>
</div>