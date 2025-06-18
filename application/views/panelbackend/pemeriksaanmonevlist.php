<?php /*<div class="row">
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
*/ ?>
<ul class="nav nav-tabs">
    <?php //if ($jenis != 'eksternal') { ?>
        <li class="nav-item">
            <a class="nav-link" href="<?= site_url("panelbackend/pemeriksaan/index/$jenis") ?>">Rekap Laporan Hasil Pemeriksaan (LHP)</a>
        </li>
    <?php //} ?>
    <li class="nav-item">
        <a class="nav-link active" href="#">Monitoring & Evaluasi</a>
    </li>
</ul>


<table class="table table-striped table-hover dataTable treetableclose mt-3">
    <thead>
        <tr>
            <th>Unit/Bidang/Kegiatan</th>
            <th>Σ Temuan</th>
            <th>Σ Tindaklanjut (Penyelesaian)</th>
            <th>Σ Sisa Temuan</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $id_unit => $rs) { ?>
            <tr data-tt-id='unit<?= $id_unit ?>' data-tt-parent-id=''>
                <td><b><?= $unitarr[$id_unit] ?></b></td>
                <td><b><?= $totalunit[$id_unit]['jumlah_temuan'] ?></b></td>
                <td><b><?= $totalunit[$id_unit]['jumlah_tindak_lanjut'] ?></b></td>
                <td><b><?= $totalunit[$id_unit]['jumlah_sisa_temuan'] ?></b></td>
            </tr>
            <?php foreach ($rs as $r) { ?>
                <tr data-tt-id='kegiatan<?= $r['id_pemeriksaan'] ?>' data-tt-parent-id='unit<?= $id_unit ?>'  style='<?= $r['is_sebelumnya'] ? 'background-color:#ffe7e7' : '' ?>'>
                    <td><a href='<?= ($url = base_url($page_ctrl . "/detail/$jenis/$r[$pk]")) ?>'><?= $r['nama'] ?></a></td>
                    <td><?= $r['jumlah_temuan'] ?></td>
                    <td><?= $r['jumlah_tindak_lanjut'] ?></td>
                    <td><?= $r['jumlah_sisa_temuan'] ?></td>
                </tr>
        <?php }
        } ?>
    </tbody>
</table>

<table>
    <tr><td style="background-color: #ffe7e7;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;Temuan sebelumnya</td></tr>
</table>