<div class="col-sm-6">

    <?php
    $from = UI::createTextBox('nomor_kka', $row['nomor_kka'], '300', '100', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["nomor_kka"], "nomor_kka", "Nomor KKA");
    ?>

    <?php
    $from = UI::createTextBox('nama', $row['nama'], '300', '100', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama KKA");
    ?>
    <?php
    $from = UI::createTextArea('keterangan', $row['keterangan'], '', '', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "Keterangan KKA");
    ?>

    <?php
    $from = UI::createUpload("file", $row, $page_ctrl, $edited);
    echo UI::createFormGroup($from, $rules["file"], "file", "File KKA");
    ?>




</div>
<div class="col-sm-6">

    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from);
    ?>
</div>