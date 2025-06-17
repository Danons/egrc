<div class="col-sm-6">

    <?php
    $from = UI::createSelect('id_kpi', $kpiarr, $row['id_kpi'], $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["id_kpi"], "id_kpi", "KPI");
    ?>

    <?php
    $from = UI::createTextBox('polaritas_minimal', $row['polaritas_minimal'], '50', '50', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["polaritas_minimal"], "polaritas_minimal", "Polaritas Minimal");
    ?>

    <?php
    $from = UI::createTextBox('polaritas_maksimal', $row['polaritas_maksimal'], '50', '50', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["polaritas_maksimal"], "polaritas_maksimal", "Polaritas Maksimal");
    ?>

</div>
<div class="col-sm-6">


    <?php
    $from = UI::createTextNumber('nilai', $row['nilai'], '10', '10', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["nilai"], "nilai", "Nilai");
    ?>

    <?php
    $from = UI::createTextNumber('satuan', $row['satuan'], '10', '10', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["satuan"], "satuan", "Satuan");
    ?>

    <?php
    $from = UI::createTextNumber('tahun', $row['tahun'], '10', '10', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["tahun"], "tahun", "Tahun");
    ?>

    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from);
    ?>
</div>