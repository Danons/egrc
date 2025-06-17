<div class="col-sm-6">
    <?php
    $from = UI::createSelect('tahun', $tahunarr, $row['tahun'], $edited, 'form-control ', 'width:100%');
    echo UI::createFormGroup($from, $rules["tahun"], "tahun", "Tahun");
    ?>


    <?php
    $from = UI::createSelect('id_unit', $unitarr, $row['id_unit'], $edited, 'form-control ');
    echo UI::createFormGroup($from, $rules["id_unit"], "id_unit", "Unit");
    ?>

    <?php
    $from = UI::createSelect('id_kpi', $kpiarr, $row['id_kpi'], $edited, 'form-control ', "style='width:auto; max-width:100%;' onchange='goSubmit(\"set_value\")'");
    echo UI::createFormGroup($from, $rules["id_kpi"], "id_kpi", "KPI");
    ?>

    <?php
    $from = UI::createTextNumber('bobot', $row['bobot'], '10', '10', $edited, 'form-control ', "style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
    echo UI::createFormGroup($from, $rules["bobot"], "bobot", "Bobot");
    ?>

    <?php
    $from = UI::createSelect('polarisasi', ["Maximize" => "Maximize", "Minimize" => "Minimize", "Stabilize" => "Stabilize"], $row['polarisasi'], $edited, 'form-control ');
    echo UI::createFormGroup($from, $rules["polarisasi"], "polarisasi", "Polarisasi");
    ?>

</div>
<div class="col-sm-6">

    <?php
    $from = UI::createTextNumber('target', $row['target'], '10', '10', $edited, 'form-control ', "style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
    echo UI::createFormGroup($from, $rules["target"], "target", "Target");
    ?>

    <?php
    $from = UI::createTextBox('satuan', $row['satuan'], '225', '100', $edited, 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["satuan"], "satuan", "Satuan");
    ?>


    <?php
    if ($rowheader['is_direktorat'] || $rowheader['is_bersama'] || $rowheader['is_korporat']) {
        $from = UI::createCheckBox('is_pic', 1, $row['is_pic'], '', $edited, '', "style='margin: 12px 0px;'");
        echo UI::createFormGroup($from, $rules["is_pic"], "is_pic", "PIC");
    }
    ?>
    <?php
    // $from = UI::createTextArea('analisa', $row['analisa'], '', '', $edited, 'form-control contents', "");
    // echo UI::createFormGroup($from, $rules["analisa"], "analisa", "Analisa");
    ?>

    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from);
    ?>
</div>