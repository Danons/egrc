<?php
include "_kriteria.php";
?>

<div class="row">
    <div class="col-sm-6">
        <?php
        $from = UI::createTextArea('sasaran', $rowheader1['sasaran'], '5', '', false, $class = 'form-control', "");
        echo UI::createFormGroup($from, $rules["sasaran"], "sasaran", "Sasaran Kerja", false, 4, false);

        // dpr($rowheader1,1);
        $from = UI::createSelect('id_kpi', $kpiarr, $rowheader1['id_kpi'], false, $class = 'form-control ', "style='width:100%;' onchange='goSubmit(\"post\")'");
        echo UI::createFormGroup($from, $rules["id_kpi"], "id_kpi", "KPI", false, 4, false);

        $from = UI::createTextArea('nama_aktifitas', $rowheader1['nama_aktifitas'], '5', '', false, $class = 'form-control', "");
        echo UI::createFormGroup($from, $rules["nama_aktifitas"], "nama_aktifitas", "Aktifitas", false, 4, false);

        $from = "<div class='row'><div class='col-sm-4' style='padding-right: 0px !important;'>" . UI::createSelect('control_kemungkinan_penurunan', $mtkemungkinanrisikoarr, $row['control_kemungkinan_penurunan'], false, $class = 'form-control ', "style='width:100%;'") . "</div>";
        $from .= "<div class='col-sm-4' style='padding-right: 0px !important;'>" . UI::createSelect('control_dampak_penurunan', $mtdampakrisikoarr, $row['control_dampak_penurunan'], false, $class = 'form-control ', "style='width:100%;'") . "</div>";
        $from .= "<div class='col-sm-4' style='padding-right: 0px !important;'>" . UI::tingkatRisiko('control_kemungkinan_penurunan', 'control_dampak_penurunan', $row, false) . "</div></div>";

        echo UI::createFormGroup($from, $rules["nama"], "nama", "Residual Saat Ini");

        $from = "<div class='row'><div class='col-sm-4' style='padding-right: 0px !important;'>" . UI::createSelect('appetite_kemungkinan', $mtkemungkinanrisikoarr, $row['appetite_kemungkinan'], false, $class = 'form-control ', "style='width:100%;'") . "</div>";
        $from .= "<div class='col-sm-4' style='padding-right: 0px !important;'>" . UI::createSelect('appetite_dampak', $mtdampakrisikoarr, $row['appetite_dampak'], false, $class = 'form-control ', "style='width:100%;'") . "</div>";
        $from .= "<div class='col-sm-4' style='padding-right: 0px !important;'>" . UI::tingkatRisiko('appetite_kemungkinan', 'appetite_kemungkinan', $row, false) . "</div></div>";

        echo UI::createFormGroup($from, $rules["nama"], "nama", "Risk Appetite");

        // $from = "<div class='row'><div class='col-sm-4' style='padding-right: 0px !important;'>" . UI::createSelect('residual_kemungkinan_evaluasi', $mtkemungkinanrisikoarr, $row['residual_kemungkinan_evaluasi'], false, $class = 'form-control ', "style='width:100%;'") . "</div>";
        // $from .= "<div class='col-sm-4' style='padding-right: 0px !important;'>" . UI::createSelect('residual_dampak_evaluasi', $mtdampakrisikoarr, $row['residual_dampak_evaluasi'], false, $class = 'form-control ', "style='width:100%;'") . "</div>";
        // $from .= "<div class='col-sm-4' style='padding-right: 0px !important;'>" . UI::tingkatRisiko('residual_kemungkinan_evaluasi', 'residual_dampak_evaluasi', $row, false) . "</div></div>";
        // echo UI::createFormGroup($from, $rules["nama"], "nama", "Residual  Risk");
        ?>
    </div>
    <div class="col-sm-6">
        <?php
        $from = UI::createTextArea('benefit_potential', $row['benefit_potential'], '', '', $edited);
        echo UI::createFormGroup($from, $rules["benefit_potential"], "benefit_potential", "Potensi Manfaat");
        ?>
        <?php
        $from = UI::createCheckBox("is_accept", 1, $row['is_accept'], "Penanganan", $edited);
        echo UI::createFormGroup($from, $rules["is_accept"], "is_accept", "");
        ?>
    </div>
</div>

<?php
if ($edited) { ?>
    <br />
    <div style="text-align: right;">
        <?php
        $from = UI::showButtonMode("save", null, $edited);
        echo UI::createFormGroup($from, NULL, NULL, NULL, false, 4, $edited);
        ?>
    </div>
<?php
}
?>