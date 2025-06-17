<div class="col-sm-6">

    <?php
    $from = UI::createTextBox('nama', $row['nama'], '100', '100', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
    ?>

    <?php
    $from = UI::createSelect(
        'id_tingkat_agregasi_risiko_parent',
        $agregasiarr,
        $row['id_tingkat_agregasi_risiko_parent'],
        $edited,
        $class = 'form-control ',
        "style='text-align:right; width:76px' min='0' max='10000' step='any'"
    );
    echo UI::createFormGroup($from, $rules["id_tingkat_agregasi_risiko_parent"], "id_tingkat_agregasi_risiko_parent", "Tingkat Agregasi Risiko Parent");
    ?>



    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from, null, null, null);
    ?>
</div>