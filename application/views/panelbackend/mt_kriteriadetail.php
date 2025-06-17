<div class="col-sm-12">

    <?php
    // $from = UI::createTextNumber('id_kategori',$row['id_kategori'],'10','10',$edited,'form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
    // echo UI::createFormGroup($from, $rules["id_kategori"], "id_kategori", "Kategori");
    ?>

    <?php
    $from = UI::createTextBox('kode', $row['kode'], '20', '20', $edited, 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["kode"], "kode", "Kode", true, 2);
    ?>

    <?php
    $from = UI::createTextArea('nama', $row['nama'], '', '', $edited, 'form-control ', "");
    echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama", true, 2);
    ?>

    <?php
    // $from = UI::createCheckBox('is_upload',1,$row['is_upload'], "Upload",$edited,'iCheck-helper ',"");
    // echo UI::createFormGroup($from, $rules["is_upload"], "is_upload");
    ?>

    <?php
    // $from = UI::createTextNumber('id_kriteria_parent',$row['id_kriteria_parent'],'10','10',$edited,'form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
    // echo UI::createFormGroup($from, $rules["id_kriteria_parent"], "id_kriteria_parent", "Kriteria Parent");
    ?>

    <?php
    // $from = UI::createCheckBox('is_aktif',1,$row['is_aktif'], "Aktif",$edited,'iCheck-helper ',"");
    // echo UI::createFormGroup($from, $rules["is_aktif"], "is_aktif");
    ?>

    <?php
    // $from = UI::createTextNumber('id_interval',$row['id_interval'],'10','10',$edited,'form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
    // echo UI::createFormGroup($from, $rules["id_interval"], "id_interval", "Interval");
    ?>

    <?php
    // $from = UI::createTextNumber('bobot',$row['bobot'],'10','10',$edited,'form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
    // echo UI::createFormGroup($from, $rules["bobot"], "bobot", "Bobot");
    ?>

    <!-- </div>
<div class="col-sm-6"> -->


    <?php
    // $from = UI::createTextNumber('tahun',$row['tahun'],'10','10',$edited,'form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
    // echo UI::createFormGroup($from, $rules["tahun"], "tahun", "Tahun");
    ?>

    <?php
    // $from = UI::createSelect('id_unit',$mtsdmunitarr,$row['id_unit'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
    // echo UI::createFormGroup($from, $rules["id_unit"], "id_unit", "Unit");
    ?>

    <?php
    // $from = UI::createTextNumber('id_kriteria_before',$row['id_kriteria_before'],'10','10',$edited,'form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
    // echo UI::createFormGroup($from, $rules["id_kriteria_before"], "id_kriteria_before", "Kriteria Before");
    ?>

    <?php
    // $from = UI::createTextNumber('id_kriteria_parent1',$row['id_kriteria_parent1'],'10','10',$edited,'form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
    // echo UI::createFormGroup($from, $rules["id_kriteria_parent1"], "id_kriteria_parent1", "Kriteria Parent1");
    ?>

    <?php
    // $from = UI::createTextNumber('d',$row['d'],'10','10',$edited,'form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
    // echo UI::createFormGroup($from, $rules["d"], "d", "D");
    ?>

    <?php
    // $from = UI::createTextNumber('k',$row['k'],'10','10',$edited,'form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
    // echo UI::createFormGroup($from, $rules["k"], "k", "K");
    ?>

    <?php
    // $from = UI::createTextNumber('w',$row['w'],'10','10',$edited,'form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
    // echo UI::createFormGroup($from, $rules["w"], "w", "W");
    ?>

    <?php
    // $from = UI::createTextNumber('o',$row['o'],'10','10',$edited,'form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
    // echo UI::createFormGroup($from, $rules["o"], "o", "O");
    ?>

    <?php
    $from = UI::showButtonMode("save", $row['id_kriteria'], $edited);
    echo UI::createFormGroup($from);
    ?>
</div>