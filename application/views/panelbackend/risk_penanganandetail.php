<?php
include "_kriteria.php";

if ($penyesuaian_tindakan_mitigasi = $rowheader1['risiko_old']['penyesuaian_tindakan_mitigasi'])
    $info_penyebab = UI::createInfo("info_penyebab", "Rekomendasi Risiko Sebelumnya", $penyesuaian_tindakan_mitigasi);
?>
<div class="row">
    <div class="col-sm-12">
        <table class="table table-hover table-inline mb-3">
            <thead>
                <tr>
                    <!-- <th width="1px">No. TSP</th> -->
                    <th>Pengendalian Lanjutan  <?= $info_penyebab ?></th>
                    <!-- <th>Penanganan/Pencegahan</th> -->
                    <!-- <th>Sasaran</th> -->
                    <th width="240px">Untuk Menurunkan</th>
                    <th style="width: 100px;">Tgl. Mulai</th>
                    <th style="width: 100px;">Tgl. Berakhir</th>
                    <th>Penanggung Jawab</th>
                    <!-- <th>Tingkat Prioritas</th> -->
                    <!-- <th>Biaya</th>
                    <?php if ($edited) { ?>
                        <th width="1px"></th>
                    <?php } ?> -->
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $from = function ($val = null, $edited, $k = 0, $ci) {
                    $penanggungjawabarr = $ci->data['penanggungjawabarr'];
                    $riskmitigasiarr = $ci->data['riskmitigasiarr'];
                    $riskmitigasisasaran = $ci->data['riskmitigasisasaran'];
                    $riskmitigasinomor = $ci->data['riskmitigasinomor'];
                    $id_risiko = $ci->data['row']['id_risiko'];
                    $penanganan_pencegahanarr = $ci->data['penanganan_pencegahanarr'];
                    $edit_m = $ci->data['edit_m'];
                    $from = null;
                    // $from .= "<td style='width: 15%;'>";
                    // $from .= UI::createTextBox("mitigasi[$k][nomor]", $riskmitigasinomor[$val['id_mitigasi']], '', '', $edited, 'form-control', "readonly");
                    // $from .= "</td>";
                    $from .= "<td>";
                    // $from .= $val['id_mitigasi'] && !$edited ? "<a href = '" . base_url("panelbackend/risk_penanganan_mitigasi/detail") . "/$id_risiko/$val[id_mitigasi]" . "'>" : '';
                    // dpr($edit_m);
                    if (is_array($edit_m) && $edit_m !== null) {
                        if (!in_array($val['id_control'] ? $val['id_control'] : $val['nama'], $edit_m)) {
                            $from .= UI::createSelect("mitigasi[$k][nama]", $riskmitigasiarr, $val['id_mitigasi'], $edited, 'form-control ', "style='width:100%;' data-tags='true' onchange=goSubmit('edit_mitigasi') data-ajax--data-type=\"json\" data-ajax--url=\"" . base_url('panelbackend/ajax/riskmitigasiarr') . "\"");
                        } else {
                            $from .= UI::createTextBox("mitigasi[$k][nama]", $riskmitigasiarr[$val['id_mitigasi']], '', '', $edited, "form-control onchange=goSubmit('set_value')");
                        }
                    } else $from .= UI::createSelect("mitigasi[$k][nama]", $riskmitigasiarr, $val['id_mitigasi'], $edited, 'form-control ', "style='width:100%;' data-tags='true' onchange=goSubmit('edit_mitigasi') data-ajax--data-type=\"json\" data-ajax--url=\"" . base_url('panelbackend/ajax/riskmitigasiarr') . "\"");
                    $from .= "</td>";
                    // $from .= "<td>";
                    // $from .= UI::createTextBox("mitigasi[$k][sasaran]", $riskmitigasisasaran[$val['id_mitigasi']], '', '', $edited, 'form-control');
                    // $from .= UI::createSelect("mitigasi[$k][penanganan_pencegahan]", $penanganan_pencegahanarr, $val['penanganan_pencegahan'], $edited, 'form-control');
                    // $from .= "</td>";
                    // $from .= "<td>";
                    // $from .= UI::createTextBox("mitigasi[$k][sasaran]", $val['sasaran'], '', '', $edited, 'form-control');
                    // $from .= "</td>";

                    $from .= "<td>";
                    $from .= UI::createSelect("mitigasi[$k][menurunkan_dampak_kemungkinan]", ["k" => "Kemungkinan", "d" => "Dampak", "kd" => "Kemungkinan / Dampak"], $val['menurunkan_dampak_kemungkinan'], $edited, 'form-control ', "style='width:100%;'");
                    $from .= "</td>";
                    $from .= "<td>";
                    $from .= UI::createTextBox("mitigasi[$k][start_date]", $val['start_date'], '', '', $edited, 'form-control datepicker');
                    $from .= "</td>";
                    $from .= "<td>";
                    $from .= UI::createTextBox("mitigasi[$k][end_date]", $val['end_date'], '', '', $edited, 'form-control datepicker');
                    $from .= "</td>";
                    $from .= "<td>";
                    $from .= UI::createSelect("mitigasi[$k][penanggung_jawab]", $penanggungjawabarr, $val['penanggung_jawab'], $edited, 'form-control ', "style='width:100%;'");
                    $from .= "</td>";


                    if ($edited) {
                        if (is_array($edit_m) && $edit_m !== null && (in_array($val['id_mitigasi'] ? $val['id_mitigasi'] : $val['nama'], $edit_m))) {
                            $from .= "</td>";
                            $from .= UI::createTextHidden("mitigasi[$k][id_mitigasi_bak]", $val['id_mitigasi'], $edited);
                            $from .= UI::createTextHidden("mitigasi[$k][edit]", $val['id_mitigasi'], $edited);

                            $from .= "<td style='position:relative; text-align:right'>";
                        } else {
                            $from .= "</td>";
                            // $from .= UI::createTextHidden("mitigasi[$k][id_mitigasi]", $val['id_mitigasi'] ? $val['id_mitigasi'] : $val['nama'], $edited);
                            // $from .= UI::createTextHidden("mitigasi[$k][id]", $val['id_mitigasi'], $edited);

                            $from .= "<td style='position:relative; text-align:right'>";
                        }
                    }

                    return $from;
                };

                if (!$row['mitigasi'])
                    $row['mitigasi'] = [[]];
                echo UI::AddFormTable('mitigasi', $row['mitigasi'], $from, $edited, $this, 1);
                // dpr($row['mitigasi']);
                // dpr($riskmitigasiarr);
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- <hr /> -->
<?php /*
<div class="row">
    <div class="col-sm-12">
        <?php
        if (!$edited) {
            // $from = '<span class="badge bg-success" style="background-color:' . $prioritaswarna[$row['id_prioritas']] . '">' . $prioritas[$row['id_prioritas']] . '</span>';
            $from = '<span class="badge" style="background-color:' . $prioritaswarna[$row['id_prioritas']] . '">' . $prioritas[$row['id_prioritas']] . '</span>';
        } else {
            $from =  '<span id="id_prioritas_form">' . UI::createSelect('id_prioritas', $prioritas, $row['id_prioritas'], $edited, $class = 'form-control select2', "style='width:100%;'") . '</span>';
        }
        echo UI::createFormGroup($from, $rules["id_prioritas"], "id_prioritas", 'Tingkat Prioritas', true, 2, $edited);
        ?>
        <?php
        $from = UI::createTextArea('integrasi_eksternal', $rowheader1['integrasi_eksternal'], '', '', $edited, 'form-control', "");
        echo UI::createFormGroup($from, $rules["integrasi_eksternal"], "integrasi_eksternal", 'Integrasi Eksternal', true, 2, $edited);

        ?>
    </div>
</div> */ ?>

<!-- <hr /> -->

<div class="row">
    <div class="col-sm-6">
        <h5 class='h5'>Target Residual
            <?= UI::tingkatRisiko('residual_target_kemungkinan', 'residual_target_dampak', $row, $edited); ?>
        </h5>
    </div>
    <div class="col-sm-6">
        <h5 class='h5'>Selera Risiko
            <?= UI::tingkatRisiko('selera_dampak', 'selera_kemungkinan', $row, $edited); ?>
        </h5>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
        // $from = UI::createSelect('risk_oppurtuniny', array('' => '', 'risk' => 'Risk', 'opportunity' => 'Opportunity'), $row['risk_oppurtuniny'], $edited, $class = 'form-control select2', "style='width:100%;'");
        // echo UI::createFormGroup($from, $rules["risk_oppurtuniny"], "risk_oppurtuniny", 'Risk/Opportunity <a data-bs-toggle="modal" href="javascript:void(0)" ><span class="material-icons" data-bs-toggle="tooltip" data-bs-original-title="Kriteria" style="font-size:inherit">info</span></a>', true, 4, $edited);
        ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <?php
        $from = UI::createSelect('residual_target_kemungkinan', $mtkemungkinanrisikoarr, $row['residual_target_kemungkinan'], $edited, $class = 'form-control ', "style='width:100%;'");
        echo UI::createFormGroup($from, $rules["residual_target_kemungkinan"], "residual_target_kemungkinan", 'Kemungkinan <a data-bs-toggle="modal" href="javascript:void(0)" data-bs-target="#kriteriaKemungkinan"><span class="material-icons" data-bs-toggle="tooltip" data-bs-original-title="Kriteria" style="font-size:inherit">info</span></a>', true, 4, $edited);
        ?>
    </div>
    <div class="col-sm-6">
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
        $from = UI::createSelect('residual_target_dampak', $mtdampakrisikoarr, $row['residual_target_dampak'], $edited, $class = 'form-control ', "style='width:100%;'");
        echo UI::createFormGroup($from, $rules["residual_target_dampak"], "residual_target_dampak", 'Dampak <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#kriteriaDampak"><span class="material-icons" style="font-size:inherit" data-bs-toggle="tooltip" data-bs-original-title="Kriteria">info</span></a>', true, 4, $edited);
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
        // $from = UI::createTextBox('dampak_kuantitatif_target', $row['dampak_kuantitatif_target'], '', '', $edited, 'form-control rupiah');
        // echo UI::createFormGroup($from, $rules["dampak_kuantitatif_target"], "dampak_kuantitatif_target", "Dampak Kuantitatif", true, 4, $edited);
        ?>
    </div>
    <div class="col-sm-6">
    </div>
</div>

<?php
if ($edited) { ?>
    <br />
    <div style="text-align: right;">
        <?php
        $from = UI::showButtonMode("save", null, $edited);
        echo UI::createFormGroup($from, NULL, NULL, NULL, true, 4, $edited);
        ?>
    </div>
<?php
}
?>

<style>
    <?php foreach ($prioritas as $k => $v) { ?>#id_prioritas_form .select2-selection--single:has(.select2-selection__rendered[title="<?= $v ?>"]) {
        background-color: <?= $prioritaswarna[$k] ?> !important;
    }

    <?php } ?>
</style>