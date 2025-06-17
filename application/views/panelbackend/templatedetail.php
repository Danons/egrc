<div class="col-sm-6">

    <?php
    $from = UI::createTextBox('no_template', $row['no_template'], '45', '45', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["no_template"], "no_template", "No. Template");
    ?>

    <?php
    $from = UI::createTextBox('nama', $row['nama'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
    ?>

    <?php
    $from = UI::createUpload("file", $row, $page_ctrl, $edited);
    echo UI::createFormGroup($from, $rules["file"], "file", "File Dokumen");
    ?>

    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from);
    ?>
</div>