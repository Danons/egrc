<div class="row">
    <div class="col-sm-9">

        <?php
        $from = UI::createSelect('id_unit_kerja', $unitKerjaArr, $row['id_unit_kerja'], $edited, $class = 'form-control ', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["id_unit_kerja"], "id_unit_kerja", "Unit Kerja", false, 3);
        ?>
        <?php
        $from = UI::createTextBox('nama', $row['nama'], '300', '100', $edited, $class = 'form-control ', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama", false, 3);
        ?>

        <?php
        $from = UI::createTextArea('deskripsi', $row['deskripsi'], '0', '0', $edited, $class = 'form-control ', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["deskripsi"], "deskripsi", "Deskripsi", false, 3);
        ?>

        <?php
        // dpr($row['petugas']);
        $from = UI::showButtonMode("save", null, $edited);
        echo UI::createFormGroup($from, null, null, null, false, 3);
        ?>
    </div>
</div>