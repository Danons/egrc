<div class="col-sm-12">

    <?php
    $from = UI::createSelect('id_quisioner_parent', $quisionerarr, $row['id_quisioner_parent'], $edited, $class = 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["id_quisioner_parent"], "id_quisioner_parent", "Quisioner Induk", false, 2);
    ?>

    <?php
    $from = UI::createTextArea('pertanyaan', $row['pertanyaan'], '', '', $edited, $class = 'form-control contents', "");
    echo UI::createFormGroup($from, $rules["pertanyaan"], "pertanyaan", "Pertanyaan", false, 2);
    ?>

    <?php
    // $from = UI::createTextBox('jenis_jawaban', $row['jenis_jawaban'], '20', '20', $edited, $class = 'form-control ', "style='width:200px'");
    // echo UI::createFormGroup($from, $rules["jenis_jawaban"], "jenis_jawaban", "Jenis Jawaban");
    ?>

    <?php
    $from = UI::createSelect('jenis_jawaban', $jenisjawabanarr, $row['jenis_jawaban'], $edited, $class = 'form-control ', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
    echo UI::createFormGroup($from, $rules["jenis_jawaban"], "jenis_jawaban", "Jenis Jawaban", false, 2);
    ?>

    <?php
    $from = UI::createSelectMultiple('id_jabatanarr[]', $jabatanarr, $row['id_jabatanarr'], $edited, $class = 'form-control', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["id_jabatanarr[]"], "id_jabatanarr[]", "Responden Jabatan", false, 2);
    // echo UI::createFormGroup($from, $rules["id_jabatanarr[]"], "id_jabatanarr[]", "Pengendali " . ($edited ? "<small><a href='javascript:void(0);' onclick='$(\".id_jabatanarr\").val(" . json_encode(array_keys($mtunitarr)) . ").change();'>Semua</a></small>" : null));
    ?>


    <?php
    $from = UI::createSelectMultiple('id_userarr[]', $userarr, $row['id_userarr'], $edited, $class = 'form-control', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["id_userarr[]"], "id_userarr[]", "Responden Pegawai", false, 2);
    // echo UI::createFormGroup($from, $rules["id_jabatanarr[]"], "id_jabatanarr[]", "Pengendali " . ($edited ? "<small><a href='javascript:void(0);' onclick='$(\".id_jabatanarr\").val(" . json_encode(array_keys($mtunitarr)) . ").change();'>Semua</a></small>" : null));
    ?>

    <?php
    if ($id_kategori == 4) {
        $from = UI::createSelectMultiple('pemeriksaanarr[]', $pemeriksaanarr, $row['pemeriksaanarr'], $edited, $class = 'form-control', "style='width:100%;'");
        echo UI::createFormGroup($from, $rules["pemeriksaanarr[]"], "pemeriksaanarr[]", "Kegiatan Audit", false, 2);
    } elseif ($id_kategori == 5) {
        $from = UI::createTextNumber('tahunarr[]', $row['tahunarr'], 4, 4, $edited, $class = 'form-control', "style='width:100%;' data-tags='true'");
        echo UI::createFormGroup($from, $rules["tahunarr[]"], "tahunarr[]", "Tahun", false, 2);
    } else {
        $from = UI::createSelectMultiple('id_kriteriaarr[]', $kriteriaarr, $row['id_kriteriaarr'], $edited, $class = 'form-control', "style='width:100%;'");
        echo UI::createFormGroup($from, $rules["id_kriteriaarr[]"], "id_kriteriaarr[]", "Paramater", false, 2);
        // echo UI::createFormGroup($from, $rules["id_jabatanarr[]"], "id_jabatanarr[]", "Pengendali " . ($edited ? "<small><a href='javascript:void(0);' onclick='$(\".id_jabatanarr\").val(" . json_encode(array_keys($mtunitarr)) . ").change();'>Semua</a></small>" : null));
    }
    ?>

    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from, null, null, null, false, 2);
    ?>
</div>