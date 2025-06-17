<?php
include "_kriteria.php";
?>

<div class="row">
    <div class="col-sm-12">
        <h5 class='h5'>Risiko Inheren

            <?= UI::tingkatRisiko('inheren_kemungkinan', 'inheren_dampak', $row, $edited); ?>
        </h5>
    </div>
</div>

<div class="row" style="display: none;">
    <div class="col-sm-6">
        <?php
        $row['is_opp_inherent'] = -1;
        // $from = UI::createSelect('is_opp_inherent', array('-1' => 'Risk', '1' => 'Opportunity'), $row['is_opp_inherent'], $edited, $class = 'form-control select2', "style='width:100%;'");
        $from = UI::createRadio('is_opp_inherent', array('-1' => 'Risk', '1' => 'Opportunity'), $row['is_opp_inherent'], $edited, $class = 'form-control select2', "style='width:100%;'");
        echo UI::createFormGroup($from, $rules["is_opp_inherent"], "is_opp_inherent", 'Risk/Opportunity', true, 4, $edited);
        ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <?php
        $from = UI::createSelect('inheren_kemungkinan', $mtkemungkinanrisikoarr, $row['inheren_kemungkinan'], $edited, $class = 'form-control ', "style='width:100%;' onchange='set_signi()'");
        echo UI::createFormGroup($from, $rules["inheren_kemungkinan"], "inheren_kemungkinan", 'Kemungkinan <a data-bs-toggle="modal" href="javascript:void(0)" data-bs-target="#kriteriaKemungkinan"><span class="material-icons" data-bs-toggle="tooltip" data-bs-original-title="Kriteria" style="font-size:inherit">info</span></a>', true, 4, $edited);
        ?>
    </div>
    <div class="col-sm-6">
        <?php
        $from = UI::createSelect('id_kriteria_kemungkinan', $kriteriakemungkinanarr, $row['id_kriteria_kemungkinan'], $edited, $class = 'form-control ', "style='width:100%;'");
        echo UI::createFormGroup($from, $rules["id_kriteria_kemungkinan"], "id_kriteria_kemungkinan", "Kriteria", true, 3, $edited);
        ?>

    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <?php
        $from = UI::createSelect('inheren_dampak', $mtdampakrisikoarr, $row['inheren_dampak'], $edited, $class = 'form-control ', "style='width:100%;' onchange='set_signi()'");
        echo UI::createFormGroup($from, $rules["inheren_dampak"], "inheren_dampak", 'Dampak <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#kriteriaDampak"><span class="material-icons" style="font-size:inherit" data-bs-toggle="tooltip" data-bs-original-title="Kriteria">info</span></a>', true, 4, $edited);
        ?>
    </div>
    <div class="col-sm-6">
        <?php
        $from = UI::createSelect('id_kriteria_dampak', $kriteriaarr, $row['id_kriteria_dampak'], $edited, $class = 'form-control ', "style='width:100%;'");
        echo UI::createFormGroup($from, $rules["id_kriteria_dampak"], "id_kriteria_dampak", 'Kriteria', true, 3, $edited);
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
        $from = UI::createTextBox('dampak_kuantitatif_inheren', $row['dampak_kuantitatif_inheren'], '', '', $edited, 'form-control rupiah');
        // echo UI::createFormGroup($from, $rules["dampak_kuantitatif_inheren"], "dampak_kuantitatif_inheren", "Dampak Kuantitatif <a href='javascript:void(0)' data-bs-toggle='modal' data-bs-target='#varmodal'>VAR</a>", true, 4, $edited);
        echo UI::createFormGroup($from, $rules["dampak_kuantitatif_inheren"], "dampak_kuantitatif_inheren", "Dampak Kuantitatif", true, 4, $edited);
        // include "_var.php";
        ?>
    </div>
</div>
<div class="row" style="display: none;">
    <div class="col-sm-6">
        <?php
        $from = UI::createCheckBox("is_signifikan_inherent", 1, $row['is_signifikan_inherent'], "Signifikan", $edited, "", ' onclick="return false;" disabled');
        echo UI::createFormGroup($from, $rules["is_signifikan_inherent"], "is_signifikan_inherent", "", true, 3, $edited);
        ?>
    </div>
</div>
<!-- <hr /> -->
<?php if ($row['mitigasi']) { ?>
    <div class="row">
        <div class="col-sm-12">
            <h5 class='h5'>Pengendalian Lanjutan Sebelumnya
            </h5>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <table class="table table-hover table-inline mb-3">
                <thead>
                    <tr>
                        <th width="1px">No</th>
                        <th>Nama Pengendalian</th>
                        <!-- <th width="175px">Jadikan Pengendalian Berjalan</th> -->
                        <th width="175px">Aksi</th>
                        <?php if ($edited) { ?>
                            <th width="1px"></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $from = function ($val = null, $edited, $k = 0, $ci, $no) {
                        $mitigasiarr = $ci->data['mitigasiarr'];
                        $from = null;
                        $from .= "<td>";
                        $from .= $no;
                        $from .= "</td>";
                        $from .= "<td>";
                        // $from .= UI::createTextBox("control[$k][nama]", $val['nama'], '', '', $edited, 'form-control');
                        // $from .= UI::createSelect("control[$k][nama]", $riskresiko, $val['id_control'], $edited, 'form-control ', "style='width:100%;' data-tags='true' onchange=goSubmit('set_value')");
                        $from .= UI::createSelect("mitigasi[$k][id_mitigasi]", $mitigasiarr, $val['id_mitigasi'] ? $val['id_mitigasi'] : $val['nama'], false, 'form-mitigasi ', "style='width:100%;' ");
                        $from .= "</td>";
                        $from .= "<td>";
                        // $from .= UI::createSelect("control[$k][id_pengukuran]", $mtpengukuranarr, $val['id_pengukuran'], $edited, 'form-control ', "style='width:100%;' onchange=goSubmit('set_value')");
                        // $from .= UI::createSelect("control[$k][id_pengukuran]", $mtpengukuranarr, $val['id_pengukuran'], $edited, 'form-control ', "style='width:100%;' onchange=goSubmit('set_value')");
                        $from .= "<button type='button' class='btn btn-warning btn-xs' onclick=\"goSubmitValue2('jadikan_control',$val[id_mitigasi],$val[id_risiko_baru])\"><span class='bi bi-share'></span> Move to Control</button>";

                        if ($edited) {
                            $from .= "</td>";
                            // $from .= UI::createTextHidden("control[$k][id_control]", $val['id_control'], $edited);

                            $from .= "<td style='position:relative; text-align:right'>";
                        }

                        return $from;
                    };
                    // if (!$row['control'])
                    //     $row['control'] = [[]];
                    echo UI::AddFormTable('mitigasi', $row['mitigasi'], $from, false, $this);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>

<div class="row">
    <div class="col-sm-12">
        <h5 class='h5'>Pengendalian Berjalan
        </h5>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-hover table-inline mb-3">
            <thead>
                <tr>
                    <th width="1px">No</th>
                    <th>Nama Pengendalian</th>
                    <th width="240px">Untuk Menurunkan</th>
                    <th width="10px">Efektifitas</th>
                    <th width="10px">Pelaksanaan</th>
                    <?php if ($edited) { ?>
                        <th width="1px"></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $from = function ($val = null, $edited, $k = 0, $ci, $no) {
                    $mtpengukuranarr = $ci->data['mtpengukuranarr'];
                    $riskresiko = $ci->data['riskresiko'];
                    $edit_m = $ci->data['edit_m'];
                    $from = null;
                    $from .= "<td>";
                    $from .= $no;
                    $from .= "</td>";
                    $from .= "<td>";
                    // $from .= UI::createTextBox("control[$k][nama]", $val['nama'], '', '', $edited, 'form-control');
                    // $from .= UI::createSelect("control[$k][nama]", $riskresiko, $val['id_control'], $edited, 'form-control ', "style='width:100%;' data-tags='true' onchange=goSubmit('set_value')");
                    // $k = $val['id_control'] ? $val['id_control'] : $val['nama'];
                    if (is_array($edit_m) && $edit_m !== null) {
                        if (!in_array($val['id_control'] ? $val['id_control'] : $val['nama'], $edit_m)) {
                            $from .= UI::createSelect("control[$k][nama]", $riskresiko, $val['id_control'] ? $val['id_control'] : $val['nama'], $edited, 'form-control ', "style='width:100%;' data-tags='true' onchange=goSubmit('set_value') data-ajax--data-type=\"json\" data-ajax--url=\"" . base_url('panelbackend/ajax/riskcontrol') . "\"");
                        } else {
                            $from .= UI::createTextBox("control[$k][nama]", $riskresiko[$val['id_control'] ? $val['id_control'] : $val['nama']], '', '', $edited, "form-control onchange=goSubmit('set_value')");
                        }
                    } else $from .= UI::createSelect("control[$k][nama]", $riskresiko, $val['id_control'] ? $val['id_control'] : $val['nama'], $edited, 'form-control ', "style='width:100%;' data-tags='true' onchange=goSubmit('set_value') data-ajax--data-type=\"json\" data-ajax--url=\"" . base_url('panelbackend/ajax/riskcontrol') . "\"");
                    $from .= "</td>";
                    $from .= "<td>";
                    $from .= UI::createSelect("control[$k][menurunkan_dampak_kemungkinan]", ["k" => "Kemungkinan", "d" => "Dampak", "kd" => "Kemungkinan / Dampak"], $val['menurunkan_dampak_kemungkinan'], $edited, 'form-control ', "style='width:100%;' onchange=goSubmit('set_value')");
                    $from .= "</td>";
                    $from .= "<td>";
                    // $from .= UI::createSelect("control[$k][id_pengukuran]", $mtpengukuranarr, $val['id_pengukuran'], $edited, 'form-control ', "style='width:100%;' onchange=goSubmit('set_value')");
                    $from .= UI::createSelect("control[$k][id_pengukuran]", $mtpengukuranarr, $val['id_pengukuran'], $edited, 'form-control ', "style='width:100%;' onchange=goSubmit('set_value')");
                    $from .= "</td>";
                    $from .= "<td>";
                    $from .= UI::createTextNumber("control[$k][status_progress]", $val['status_progress'], '', '', $edited, 'form-control');

                    if ($edited) {
                        if (is_array($edit_m) && $edit_m !== null && (in_array($val['id_control'] ? $val['id_control'] : $val['nama'], $edit_m))) {
                            $from .= "</td>";
                            $from .= UI::createTextHidden("control[$k][id_control_bak]", $val['id_control'], $edited);
                            $from .= UI::createTextHidden("control[$k][edit]", $val['id_control'], $edited);

                            $from .= "<td style='position:relative; text-align:right'>";
                            // } else {$from .= "</td>";
                            //     $from .= UI::createTextHidden("control[$k][id_control]", $val['id_control'] ? $val['id_control'] : $val['nama'], $edited);
                            //     $from .= UI::createTextHidden("control[$k][id]", $val['id_control'] ? $val['id_control'] : $val['nama'], $edited);

                            //     $from .= "<td style='position:relative; text-align:right'>";
                            // }
                        } else {
                            $from .= "</td>";
                            $from .= UI::createTextHidden("control[$k][id_control]", $val['id_control'] ? $val['id_control'] : $val['nama'], $edited);
                            $from .= UI::createTextHidden("control[$k][id]", $val['id_control'] ? $val['id_control'] : $val['nama'], $edited);

                            $from .= "<td style='position:relative; text-align:right'>";
                        }
                    }

                    return $from;
                };
                // dpr($from);
                if (!$row['control'])
                    $row['control'] = [[]];

                echo UI::AddFormTable('control', $row['control'], $from, $edited, $this, 1);
                // dpr($row['control']);
                // dpr($riskresiko);
                // dpr($control_post);
                ?>
            </tbody>
        </table>
    </div>
</div>
<!-- <hr /> -->

<div class="row">
    <div class="col-sm-6">
        <h5 class='h5'>Risiko Residual Saat Ini
            <?= UI::tingkatRisiko('control_kemungkinan_penurunan', 'control_dampak_penurunan', $row, $edited); ?>
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
        // $from = UI::createSelect('is_opp_inherent1', array('' => '', 'risk' => 'Risk', 'opportunity' => 'Opportunity'), $row['is_opp_inherent1'], $edited, $class = 'form-control ', "style='width:100%;'");
        // echo UI::createFormGroup($from, $rules["is_opp_inherent1"], "is_opp_inherent1", 'Risk/Opportunity <a data-bs-toggle="modal" href="javascript:void(0)" data-bs-target="#kriteriaKemungkinan"><span class="material-icons" data-bs-toggle="tooltip" data-bs-original-title="Kriteria" style="font-size:inherit">info</span></a>', true, 4, $edited);
        ?>
    </div>
    <div class="col-sm-6">
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <?php
        $from = UI::createSelect('control_kemungkinan_penurunan', $mtkemungkinanrisikoarr, $row['control_kemungkinan_penurunan'], $edited, $class = 'form-control ', "style='width:100%;' onchange='set_signi_res()'");
        echo UI::createFormGroup($from, $rules["control_kemungkinan_penurunan"], "control_kemungkinan_penurunan", 'Kemungkinan <a data-bs-toggle="modal" href="javascript:void(0)" data-bs-target="#kriteriaKemungkinan"><span class="material-icons" data-bs-toggle="tooltip" data-bs-original-title="Kriteria" style="font-size:inherit">info</span></a>', true, 4, $edited);
        ?>
    </div>
    <div class="col-sm-6">
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
        $from = UI::createSelect('control_dampak_penurunan', $mtdampakrisikoarr, $row['control_dampak_penurunan'], $edited, $class = 'form-control ', "style='width:100%;' onchange='set_signi_res()'");
        echo UI::createFormGroup($from, $rules["control_dampak_penurunan"], "control_dampak_penurunan", 'Dampak <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#kriteriaDampak"><span class="material-icons" style="font-size:inherit" data-bs-toggle="tooltip" data-bs-original-title="Kriteria">info</span></a>', true, 4, $edited);
        ?>
    </div>
</div>

<!-- selera kemungkinan -->
<!-- <div class="row">
    <div class="col-sm-12">
        <h5 class='h5'>Risiko Selera
            <?= UI::tingkatRisiko('selera_kemungkinan', 'selera_dampak', $row, $edited); ?>
        </h5>
    </div>
</div> -->

<div class="row">
    <div class="col-sm-6">
        <?php
        // $from = UI::createTextBox('dampak_kuantitatif_current', $row['dampak_kuantitatif_current'], '', '', $edited, 'form-control rupiah');
        // echo UI::createFormGroup($from, $rules["dampak_kuantitatif_current"], "dampak_kuantitatif_current", "Dampak Kuantitatif", true, 4, $edited);
        ?>
    </div>
    <div class="col-sm-6">
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div style="display: none;">
            <?php
            $from = UI::createCheckBox("is_signifikan_current", 1, $row['is_signifikan_current'], "Signifikan", $edited, '', 'disabled');
            echo UI::createFormGroup($from, $rules["is_accept"], "is_signifikan_current", "", true);
            ?>
        </div>
        <?php
        $from = UI::createSelect('response', $responsearr, $row['response'], $edited, $class = 'form-control ', "style='width:100%;' onchange='set_signi_res()'");
        echo UI::createFormGroup($from, $rules["response"], "response", 'Response', true, 4, $edited);
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

<script>
    // $(function(){
    //     $('#is_signifikan_current').attr('readonly', 'readonly');
    // })
    function set_signi() {
        // console.log($('#inheren_dampak').val() * $('#inheren_kemungkinan').val());
        // if ($('#inheren_dampak').val() * $('#inheren_kemungkinan').val() >= (5))
        if ($('#inheren_dampak').val() * $('#inheren_kemungkinan').val() >= (<?= $this->config->item("batas_nilai_signifikan") ?>))
            $('#is_signifikan_inherent').prop("checked", true);
        else
            $('#is_signifikan_inherent').prop("checked", false);
    }

    function set_signi_res() {
        // console.log($('#control_kemungkinan_penurunan').val() * $('#control_dampak_penurunan').val());
        // if ($('#control_kemungkinan_penurunan').val() * $('#control_dampak_penurunan').val() >= (5))
        if ($('#control_kemungkinan_penurunan').val() * $('#control_dampak_penurunan').val() >= (<?= $this->config->item("batas_nilai_signifikan") ?>))
            $('#is_signifikan_current').prop("checked", true);
        else
            $('#is_signifikan_current').prop("checked", false);
    }
</script>