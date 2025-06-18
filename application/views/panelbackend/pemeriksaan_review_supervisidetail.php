

<div class="col-sm-12">

    <?php
    $from = UI::createTextArea('permsalahan', $row['permsalahan'], '', '', $edited, $class = 'form-control ', "");
    echo UI::createFormGroup($from, $rules["permsalahan"], "permsalahan", "Permasalahan", false, 2);
    ?>

    <?php
    if (!$rowheader1['id_pemeriksaan_detail']) {
        $from = UI::createSelect('id_kka', $kkaarr, $row['id_kka'], $edited, 'form-control ', "style='width:100%' onchange='goSubmit(\"set_value\")'");
        echo UI::createFormGroup($from, $rules["id_kka"], "id_kka", "Nomor KKA", false, 2);
    }
    ?>



    <?php
    $from = UI::createTextArea('penyelesaian', $row['penyelesaian'], '', '', $edited, $class = 'form-control ', "");
    echo UI::createFormGroup($from, $rules["penyelesaian"], "penyelesaian", "Penyelesaian", false, 2);
    ?>

    <?php
    // $from = UI::createCheckBox('is_persetujuan', 1, $row['is_persetujuan'], "Persetujuan", $edited, $class = 'iCheck-helper ', "");
    // echo UI::createFormGroup($from, $rules["is_persetujuan"], "is_persetujuan");
    ?>

    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from, null, null, null, false, 2);
    ?>
</div>