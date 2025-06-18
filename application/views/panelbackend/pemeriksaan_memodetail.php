<div class="col-sm-6">
    <?php
    $from = UI::createTextBox('tanggal_surat', $row['tanggal_surat'], '', '', $edited, $class = 'form-control datepicker', "style='width:190px'");
    echo UI::createFormGroup($from, $rules["tanggal_surat"], "tanggal_surat", "Tanggal Surat");
    ?>

    <?php
    $from = UI::createTextBox('dari', $row['dari'], '', '', $edited, $class = 'form-control ', "");
    echo UI::createFormGroup($from, $rules["dari"], "dari", "Dari");
    ?>

    <?php
    $from = UI::createTextBox('ke', $row['ke'], '', '', $edited, $class = 'form-control ', "");
    echo UI::createFormGroup($from, $rules["ke"], "ke", "ke");
    ?>

</div>
<div class="col-sm-6">




    <?php
    $from = UI::createTextBox('tempat', $row['tempat'], '', '', $edited, $class = 'form-control ', "");
    echo UI::createFormGroup($from, $rules["tempat"], "tempat", "Tempat");
    ?>

    <?php
    $from = UI::createSelect('direksi', $userarr, $row['direksi'], $edited, $class = 'form-control ', "");
    echo UI::createFormGroup($from, $rules["direksi"], "direksi", "Direksi");
    ?>
</div>
<div class="col-sm-12">

    <?php
    $from = UI::createTextArea('isi', $row['isi'], '', '', $edited, $class = 'form-control contents', "");
    echo UI::createFormGroup($from, $rules["isi"], "isi", "ISI", false, 2);
    ?>



    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from, null, null, null, false, 2);
    ?>
</div>