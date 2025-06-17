<div class="col-sm-12">

    <?php
    // dpr(['file']);
    $from = UI::createTextBox('nomor_dokumen', $row['nomor_dokumen'], '100', '100', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["nomor_dokumen"], "nomor_dokumen", "Nomor Dokumen", false, 2);
    ?>

    <?php
    $from = UI::createTextBox('tanggal_dokumen', $row['tanggal_dokumen'], '10', '10', $edited, $class = 'form-control datepicker', "style='width:100px'");
    echo UI::createFormGroup($from, $rules["tanggal_dokumen"], "tanggal_dokumen", "Tanggal Dokumen", false, 2);
    ?>

    <?php
    $from = UI::createTextBox('judul_dokumen', $row['judul_dokumen'], '100', '100', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["judul_dokumen"], "judul_dokumen", "Judul Dokumen", false, 2);
    ?>


</div>
<div class="col-sm-12">

    <?php
    unset($kategoriDokumenArr[0]);
    $from = UI::createSelect('id_kategori_dokumen', $kategoriDokumenArr, $row['id_kategori_dokumen'], $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["id_kategori_dokumen"], "id_kategori_dokumen", "Kategori Dokumen", false, 2);
    ?>

    <?php
    $from = UI::createTextBox('sumber_dokumen', $row['sumber_dokumen'], '100', '100', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["sumber_dokumen"], "sumber_dokumen", "Sumber Dokumen", false, 2);
    ?>

    <?php
    $from = UI::createTextArea('uraian_dokumen', $row['uraian_dokumen'], '', '', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["uraian_dokumen"], "uraian_dokumen", "Uraian Dokumen", false, 2);
    ?>

    <?php
    $from = UI::createUpload("files", $row['files'], $page_ctrl, $edited);
    echo UI::createFormGroup($from, $rules["files"], "files", "File Dokumen", false, 2);
    ?>

    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from, null, null, null, false, 2);
    ?>
</div>