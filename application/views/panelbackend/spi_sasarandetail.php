<div class="col-sm-12">

    <?php
    $from = UI::createTextArea('sasaran', $row['sasaran'], '', '', $edited, $class = 'form-control ', "");
    echo UI::createFormGroup($from, $rules["sasaran"], "sasaran", "Tujuan, Sasaran, Dan Strategi");
    ?>

    <?php
    $from = UI::createSelect('id_jabatan', $jabatanarr, $row['id_jabatan'], $edited, $class = 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["id_jabatan"], "id_jabatan", "Penanggung Jawab Sasaran Dan Strategi", false);
    ?>

    <?php
    $from = UI::createTextArea('misi', $row['misi'], '', '', $edited, $class = 'form-control ', "");
    echo UI::createFormGroup($from, $rules["misi"], "misi", "Misi");
    ?>


    <?php
    $from = UI::createTextArea('keterangan', $row['keterangan'], '', '', $edited, $class = 'form-control contents', "");
    echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "Keterangan");
    ?>

    <?php
    $from = UI::createTextNumber('tahun', $row['tahun'], '10', '10', $edited, $class = 'form-control ', "style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
    echo UI::createFormGroup($from, $rules["tahun"], "tahun", "Tahun");
    ?>

    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from);
    ?>
</div>