<div class="col-sm-6">

    <?php
    $from = UI::createTextBox('kode', $row['kode'], '300', '100', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["kode"], "kode", "Kode");
    ?>

    <?php
    $from = UI::createTextNumber('rating', $row['rating'], '300', '100', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["rating"], "rating", "Nilai");
    ?>

    <?php
    $from = UI::createTextBox('nama', $row['nama'], '300', '100', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["nama"], "nama", "Dampak Risiko");
    ?>

    <?php
    $from = UI::createTextArea('keterangan', $row['keterangan'], '', '', $edited, $class = 'form-control', "");
    echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "Keterangan");
    ?>

</div>
<div class="col-sm-6">

    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from);
    ?>
</div>