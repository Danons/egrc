<div class="row">
    <div class="col-auto d-flex">
        <?= UI::createTextNumber("tahun_filter", $tahun_filter, 4, 4, true, "form-control", "style='width:100px; display:inline' onchange='goSubmit(\"set_filter\")'") ?>
        &nbsp;
        <?php if ($view_all) {
        ?>
            <?= UI::createSelect("id_unit_filter", $mtsdmunitarr, $id_unit_filter, true, 'form-control', "style='max-width:550px !important; display:inline' onchange='goSubmit(\"set_filter\")'") ?>
        <?php } else { ?>
            <h5 style="display: inline; color: #fff; padding: 0px 10px;"><?= $mtsdmunitarr[$_SESSION[SESSION_APP]['id_unit']] ?></h5>
        <?php } /* ?>

        &nbsp;<?= (count($scorecardarr) > 2 ? UI::createSelect('id_scorecard_filter', $scorecardarr, $id_scorecard_filter, true, 'form-control select2', "style='width:180px !important; display:inline' onchange='goSubmit(\"set_filter\")'") : null) ?>
        &nbsp;
        */ ?>
    </div>
    <div class="col-auto d-inline ms-auto" style="font-size: 24px;"></div>
</div>
<br />
<table class="table table-bordered table-hover" style="width: 100% !important;">
    <thead>
        <tr>
            <th width="1">No</th>
            <th>Nomor</th>
            <th>Nama Dokumen</th>
            <th>Penilaian</th>
        </tr>
    </thead>
    <tbody>
        <?php

        $no = 1;
        foreach ($rows as $row) {
        ?>
            <tr class="tra" onclick="window.location='<?= site_url('panelbackend/comp_penilaian/index') . '&act=go_detail&id_dokumen_filter=' . $row['id_dokumen'] ?>'">
                <td><?= $no++ ?></td>
                <td><?= $row['nomor_dokumen'] ?></td>
                <td><?= $row['nama'] ?></td>
                <td style="text-align: center;"><?= $row['penilaian'] ?></td>
            </tr>
        <?php
        } ?>
    </tbody>
</table>
<style>
    .tra:hover {
        cursor: pointer;
    }
</style>