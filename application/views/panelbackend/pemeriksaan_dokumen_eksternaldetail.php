<div class="col-sm-6">

    <?php
    $from = UI::createTextBox('nomor_dokumen', $row['nomor_dokumen'], '300', '100', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["nomor_dokumen"], "nomor_dokumen", "Nomor Dokumen");
    ?>

    <?php
    $from = UI::createTextBox('nama', $row['nama'], '300', '100', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama Dokumen");
    ?>
    <?php
    $from = UI::createTextArea('keterangan', $row['keterangan'], '', '', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "Keterangan Dokumen");
    ?>


    <?php
    $from = UI::createUpload("file", $row, $page_ctrl, $edited);
    echo UI::createFormGroup($from, $rules["file"], "file", "File Dokumen");
    ?>




</div>
<div class="col-sm-6">

    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from);
    ?>
</div>