<div class="row">
    <div class="col-sm-12">
        <?php
        $from = $row['nomor_mitigasi'];
        echo UI::createFormGroup($from, null, null, "Ref.No.", false, 1, false);
        $from = $row['nama_kegiatan'];
        echo UI::createFormGroup($from, null, null, "Kegiatan", false, 1, false);
        $from = $row['nama_mitigasi'];
        echo UI::createFormGroup($from, null, null, "Tujuan", false, 1, false);
        $from = $row['sasaran_mitigasi'];
        echo UI::createFormGroup($from, null, null, "Sasaran", false, 1, false);
        // dpr($rowheader);
        ?>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-sm-12">
        <table class="table table-hover table-inline" style="text-align: center;">
            <thead>
                <tr>
                    <th width="1px">No</th>
                    <th>Rencana Tindak Lanjut</th>
                    <?php if ($rowheader['rutin_non_rutin'] !== 'nonrutin') { ?>
                        <th width="120px">Tgl. Mulai</th>
                        <th width="120px">Tgl. Selesai</th>
                    <?php } ?>
                    <?php if ($rowheader['rutin_non_rutin'] == 'nonrutin') { ?>
                        <th>Untuk</th>
                    <?php } ?>
                    <th>PIC</th>
                    <?php if ($rowheader['rutin_non_rutin'] !== 'nonrutin') { ?>
                        <th width="150px">Perkiraan Biaya (Rp)</th>
                    <?php } ?>
                    <?php if ($rowheader['rutin_non_rutin'] == 'nonrutin') { ?>
                        <th>Target Penyelesaiaan</th>
                    <?php } ?>
                    <?php if ($edited) { ?>
                        <th width="1px"></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $from = function ($val = null, $edited, $k = 0, $ci) {
                    $penanggungjawabarr = $ci->data['penanggungjawabarr'];
                    $bulanarr = $ci->data['bulan'];
                    $penanganan_pencegahanarr = $ci->data['penanganan_pencegahanarr'];
                    $rowheader = $ci->data['rowheader'];
                    $from = null;
                    $from .= "<td>";
                    $from .= $k + 1;
                    $from .= "</td>";
                    $from .= "<td>";
                    $from .= UI::createTextBox("mitigasi[$k][nama]", $val['nama'], '', '', $edited, 'form-control');
                    $from .= "</td>";
                    if ($rowheader['rutin_non_rutin'] !== 'nonrutin') {
                        $from .= "<td>";
                        $from .= UI::createTextBox("mitigasi[$k][start_date]", $val['start_date'], '', '', $edited, 'form-control datepicker');
                        $from .= "</td>";
                        $from .= "<td>";
                        $from .= UI::createTextBox("mitigasi[$k][end_date]", $val['end_date'], '', '', $edited, 'form-control datepicker');
                        $from .= "</td>";
                    }
                    if ($rowheader['rutin_non_rutin'] == 'nonrutin') {
                        $from .= "<td>";
                        $from .= UI::createSelect("mitigasi[$k][penanganan_pencegahan]", $penanganan_pencegahanarr, $val['penanganan_pencegahan'], $edited, 'form-control ', "style='width:100%;'");
                        $from .= "</td>";
                    }
                    $from .= "<td>";
                    $from .= UI::createSelect("mitigasi[$k][penanggung_jawab]", $penanggungjawabarr, $val['penanggung_jawab'], $edited, 'form-control ', "style='width:100%;'");

                    if ($rowheader['rutin_non_rutin'] !== 'nonrutin') {
                        $from .= "<td>";
                        $from .= UI::createTextBox("mitigasi[$k][biaya]", $val['biaya'], '', '', $edited, 'form-control rupiah');
                        $from .= "</td>";
                    }
                    $from .= "</td>";
                    if ($rowheader['rutin_non_rutin'] == 'nonrutin') {
                        // $from .= "<td>";
                        // $from .= UI::createSelect("mitigasi[$k][penanganan_pencegahan]", $penanganan_pencegahanarr, $val['penanganan_pencegahan'], $edited, 'form-control ', "style='width:100%;'");
                        // $from .= "</td>";
                        $from .= "<td>";
                        $from .= UI::createTextBox("mitigasi[$k][end_date]", $val['end_date'], '', '', $edited, 'form-control datepicker');
                        $from .= "</td>";
                    }

                    if ($edited) {
                        $from .= "</td>";
                        $from .= UI::createTextHidden("mitigasi[$k][id_mitigasi_program]", $val['id_mitigasi_program'], $edited);

                        $from .= "<td style='position:relative; text-align:right'>";
                    }

                    return $from;
                };

                if (!$row['mitigasi'])
                    $row['mitigasi'] = [[]];

                echo UI::AddFormTable_cuntom('mitigasi', $row['mitigasi'], $from, $editedkri || $edited, $this, 22);
                ?>
            </tbody>
        </table>
    </div>
</div>
<hr />
<?php
$fromn = UI::createTextArea('keterangan', $row['keterangan'], '5', '', $editedheader1, $class = 'form-control', "style='width:100%'");
echo UI::createFormGroup($fromn, $rules["keterangan"], "keterangan", "Keterangan", true, 6, $editedheader1);
?>
<!--
<div class="row">
    <div class="col-sm-6">
        <h4 class='h4'>Target Residual
            <?= UI::tingkatRisiko('residual_target_kemungkinan', 'residual_target_dampak', $row, $edited); ?>
        </h4>
    </div>
    <div class="col-sm-6">
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
        $from = UI::createSelect('risk_oppurtuniny', array('' => '', 'risk' => 'Risk', 'opportunity' => 'Opportunity'), $row['risk_oppurtuniny'], $edited, $class = 'form-control select2', "style='width:100%;'");
        echo UI::createFormGroup($from, $rules["risk_oppurtuniny"], "risk_oppurtuniny", 'Risk/Opportunity <a data-bs-toggle="modal" href="javascript:void(0)" data-bs-target="#kriteriaKemungkinan"><span class="material-icons" data-bs-toggle="tooltip" data-bs-original-title="Kriteria" style="font-size:inherit">info</span></a>', false, 4, $edited);
        ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <?php
        $from = UI::createSelect('residual_target_kemungkinan', $mtkemungkinanrisikoarr, $row['residual_target_kemungkinan'], $edited, $class = 'form-control ', "style='width:100%;'");
        echo UI::createFormGroup($from, $rules["residual_target_kemungkinan"], "residual_target_kemungkinan", 'Kemungkinan <a data-bs-toggle="modal" href="javascript:void(0)" data-bs-target="#kriteriaKemungkinan"><span class="material-icons" data-bs-toggle="tooltip" data-bs-original-title="Kriteria" style="font-size:inherit">info</span></a>', false, 4, $edited);
        ?>
    </div>
    <div class="col-sm-6">
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
        $from = UI::createSelect('residual_target_dampak', $mtdampakrisikoarr, $row['residual_target_dampak'], $edited, $class = 'form-control ', "style='width:100%;'");
        echo UI::createFormGroup($from, $rules["residual_target_dampak"], "residual_target_dampak", 'Dampak <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#kriteriaDampak"><span class="material-icons" style="font-size:inherit" data-bs-toggle="tooltip" data-bs-original-title="Kriteria">info</span></a>', false, 4, $edited);
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
        // $from = UI::createTextBox('dampak_kuantitatif_target', $row['dampak_kuantitatif_target'], '', '', $edited, 'form-control rupiah');
        // echo UI::createFormGroup($from, $rules["dampak_kuantitatif_target"], "dampak_kuantitatif_target", "Dampak Kuantitatif", false, 4, $edited);
        ?>
    </div>
    <div class="col-sm-6">
    </div>
</div>
-->
<?php
if ($edited) { ?>
    <br />
    <div style="text-align: right;">
        <?php
        $from = UI::showButtonMode("save", '12/2', $edited);
        echo UI::createFormGroup($from, NULL, NULL, NULL, false, 4, $edited);
        ?>
    </div>
<?php
}
?>