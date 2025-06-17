<div class="row">
    <div class="col-auto d-flex">
        <?php if ($view_all) {        ?>
            <?= UI::createSelect("id_unit_filter", $unitarr, $id_unit_filter, true, 'form-control', "style='width:500px !important; display:inline' onchange='goSubmit(\"set_filter\")'") ?>
        <?php } else { ?>
            <h5 style="display: inline; color: #fff; padding: 0px 10px;"><?= $unitarr[$_SESSION[SESSION_APP]['id_unit']] ?></h5>
        <?php }
        ?>
    </div>
    <div class="col-auto d-inline ms-auto">

        <?= UI::createSelect("id_periode_tw_filter", $mtperiodetwarr, $id_periode_tw_filter, true, 'form-control me-2', "style='max-width:150px !important; display:inline' onchange='goSubmit(\"set_filter\")'") ?>
        <?= UI::createTextNumber("tahun_filter", $tahun_filter, 4, 4, true, "form-control", "style='width:100px; display:inline' onchange='goSubmit(\"set_filter\")'") ?>

    </div>
</div>
<br />
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link" href="<?= site_url("panelbackend/pemeriksaan/index/$jenis") ?>">Pemeriksaan & Temuan</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="#">Monitoring & Evaluasi</a>
    </li>
</ul>