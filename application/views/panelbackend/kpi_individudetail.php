<div class="col-sm-8">

    <?php
    // $from = UI::createSelect('id_pegawai', $userArr, $row['id_pegawai'], '100', '100', $edited, $class = 'form-control ', "style='width:100%'");
    // echo UI::createFormGroup($from, $rules["id_pegawai"], "id_pegawai", "Pegawai");
    ?>

    <?php
    $from = UI::createSelect('id_pegawai', $userArr, $row['id_pegawai'], $edited, $class = 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["id_pegawai"], "id_pegawai", "Pegawai");
    ?>


    <?php
    $from = UI::createTextArea('target', $row['target'], '2', '', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["target"], "target", "Target");
    ?>

</div>
<div class="col-sm-8">


    <?php
    $from = UI::createSelect('id_kategori', $kategoriArr, $row['id_kategori'], $edited, $class = 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["id_kategori"], "id_kategori", "Kategori");
    ?>


    <?php
    // $from = UI::createTextBox('kategori',$row['kategori'],50,50,$edited,$class = 'form-control', "style='width:100%'");
    // echo UI::createFormGroup($from,$rules['kategor'],"kategori",'Kategori')
    ?>

    <?php
    if ($_SESSION["SESSION_APP_EGRC"]["view_all"]) {
        $from = UI::createSelect('is_setuju', $statusArr, $row['is_setuju'], $edited, $class = 'form-control ', "style='width:100%;'");
        echo UI::createFormGroup($from, $rules["is_setuju"], "is_setuju", "Status");
    }
    ?>

    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from);
    ?>
</div>