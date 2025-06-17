<div class="col-sm-12">

    <?php
    $from = UI::createSelect('jenis', $jenisarr, $row['jenis'], $edited, $class = 'form-control ', "style='width:auto; max-width:100%;'");
    echo UI::createFormGroup($from, $rules["jenis"], "jenis", "Jenis", false, 2);
    ?>

    <?php
    $from = UI::createSelect('id_checklist_parent', $mtpemeriksaanchecklistarr, $row['id_checklist_parent'], $edited, $class = 'form-control ', "style='width:auto; max-width:100%;'");
    echo UI::createFormGroup($from, $rules["id_checklist_parent"], "id_checklist_parent", "Checklist Parent", false, 2);
    ?>

    <?php
    $from = UI::createTextArea('nama', $row['nama'], '', '', $edited, $class = 'form-control ', "");
    echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama", false, 2);
    ?>



    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from);
    ?>
</div>