<div class="row">
    <div class="col-auto d-flex">
        <?= UI::createTextNumber("tahun_filter", $tahun_filter, 4, 4, true, "form-control", "style='width:100px; display:inline' onchange='goSubmit(\"set_filter\")'") ?>
        &nbsp;
        <?= UI::createSelect("id_dokumen_filter", $dokumenarr, $id_dokumen_filter, true, 'form-control', "style='max-width:350px !important; display:inline' onchange='goSubmit(\"set_filter\")'") ?>
        &nbsp;
        <?php if ($view_all && $id_dokumen_filter) {
        ?>
            <?= UI::createSelect("id_unit_filter", $mtsdmunitarr, $id_unit_filter, true, 'form-control', "style='max-width:250px !important; display:inline' onchange='goSubmit(\"set_filter\")'") ?>
        <?php } else { ?>
            <h5 style="display: inline; color: #fff; padding: 0px 10px;"><?= $mtsdmunitarr[$_SESSION[SESSION_APP]['id_unit']] ?></h5>
        <?php } /* ?>

        &nbsp;<?= (count($scorecardarr) > 2 ? UI::createSelect('id_scorecard_filter', $scorecardarr, $id_scorecard_filter, true, 'form-control select2', "style='width:180px !important; display:inline' onchange='goSubmit(\"set_filter\")'") : null) ?>
        &nbsp;
        */ ?>
    </div>
    <div class="col-auto d-inline ms-auto" style="font-size: 24px;">Score : <?= round($score) ?></div>
</div>
<br />
<table class="table table-bordered" style="width: 100% !important;">
    <thead>
        <tr>
            <th width="1">No</th>
            <th>Periode</th>
            <th>Kebutuhan</th>
            <th>Penilaian</th>
        </tr>
    </thead>
    <tbody>
        <?php

        $no = 1;
        foreach ($rows as $row) {
            $periodes = 12 / $row['konversi_bulan'];
            for ($i = 1; $i <= $periodes; $i++) {
                if ($periodes == 1) $i = $tahun_filter;
                $rowp = $rowspenilaian[$row['id_comp_kebutuhan']][$i];
        ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['periode_label'] ? $row['periode_label'] : $row['nama_periode'] ?> <?= $i ?></td>
                    <td>
                        <?= $row['nama_kebutuhan'] ?>
                        <?php if ($row['is_file']) {
                            echo UI::createUploadMultiple("files_" . $row['id_comp_kebutuhan'] . "_" . $id_unit_filter . "_" . $i . "_" . $tahun_filter, $rowp['files'], $this->page_ctrl, $this->access_role['edit']);
                        } else {
                            $url = $row['url'];
                            $url = str_replace("{thn}", $tahun_filter, $url);
                            $url = str_replace("{unt}", $id_unit_filter, $url);
                            if ($periodes > 1) {
                                $ir = json_decode($row['mapping'], true)[$i];
                                $url = str_replace("{tw}", $ir, $url);
                                $url = str_replace("{smt}", $ir, $url);
                                $url = str_replace("{bln}", $ir, $url);
                            }
                            echo "<a target='_BLANK' class='btn btn-primary btn-sm' href='" . ($url) . "'>Buka</a>";
                        } ?>
                    </td>
                    <?php
                    $addcolor = null;
                    if ($rowp['id_status_penilaian'] == 1)
                        $addcolor = "background:red";
                    if ($rowp['id_status_penilaian'] == 2)
                        $addcolor = "background:yellow";
                    if ($rowp['id_status_penilaian'] == 3)
                        $addcolor = "background:green";
                    ?>
                    <td style="padding: 5px !important; width:200px; <?= $addcolor ?>">
                        <?= UI::createSelect(
                            "penilaian[$row[id_comp_kebutuhan]][$id_unit_filter][$i][$tahun_filter][id_status_penilaian]",
                            $mtstatuspenilaianarr,
                            $rowp['id_status_penilaian'],
                            $this->access_role['penilaian'],
                            'form-control',
                            "onchange='goSubmitAjax(\"" . current_url() . "\", \"save\")'"
                        ) ?>
                        <?= UI::createTextArea(
                            "penilaian[$row[id_comp_kebutuhan]][$id_unit_filter][$i][$tahun_filter][keterangan]",
                            $rowp['keterangan'],
                            1,
                            '',
                            $this->access_role['penilaian'],
                            'form-control',
                            "onchange='goSubmitAjax(\"" . current_url() . "\", \"save\")' placeholder='Keterangan'"
                        ) ?>
                    </td>
                </tr>
        <?php }
        } ?>
    </tbody>
</table>