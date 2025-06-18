<div class="col-sm-6">

    <?php
    if ($row['catatan']) {
        $from = UI::createTextArea('catatan', $row['catatan'], '', '', $edit, $class = 'form-control contents-mini', "style='width:70%'");
        echo UI::createFormGroup($from, $rules["catatan"], "catatan", "Catatan", false, 4);
    }
    ?>

    <?php
    $from = UI::createTextBox('tanggal', $row['tanggal'], '10', '10', $edited, $class = 'form-control datepicker', "style='width:100px'");
    echo UI::createFormGroup($from, $rules["tanggal"], "tanggal", "Tanggal");
    ?>

    <?php
    $from = UI::createTextBox('nomor', $row['nomor'], '50', '50', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["nomor"], "nomor", "Nomor");
    ?>

    <?php
    $from = UI::createTextBox('lampiran', $row['lampiran'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["lampiran"], "lampiran", "Lampiran");

    ?>

    <?php
    $from = UI::createTextBox('hal', $row['hal'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["hal"], "hal", "HAL");
    ?>
</div>
<div class="col-12">


    <?php
    $from = UI::createTextArea('simpulan', $row['simpulan'], '', '', $edited, $class = 'form-control contents-mini', "style='width:70%'");
    echo UI::createFormGroup($from, $rules["simpulan"], "simpulan", "Simpulan", false, 2);
    ?>

    <?php
    $from = UI::createTextArea('saran', $row['saran'], '', '', $edited, $class = 'form-control contents-mini', "style='width:70%'");
    echo UI::createFormGroup($from, $rules["saran"], "saran", "Saran", false, 2);
    ?>


    <?php
    $from = UI::createTextArea('dasar_tugas', $row['dasar_tugas'], '', '', $edited, $class = 'form-control contents-mini', "style='width:70%'");
    echo UI::createFormGroup($from, $rules["dasar_tugas"], "dasar_tugas", "Dasar Tugas", false, 2);
    ?>


    <?php
    $from = UI::createTextArea('dasar_evaluasi', $row['dasar_evaluasi'], '', '', $edited, $class = 'form-control contents-mini', "style='width:70%'");
    echo UI::createFormGroup($from, $rules["dasar_evaluasi"], "dasar_evaluasi", "Dasar Evaluasi", false, 2);
    ?>

    <?php
    $from = UI::createTextArea('cakupan_evaluasi', $row['cakupan_evaluasi'], '', '', $edited, $class = 'form-control contents-mini', "style='width:70%'");
    echo UI::createFormGroup($from, $rules["cakupan_evaluasi"], "cakupan_evaluasi", "Cakupan Evaluasi", false, 2);
    ?>

    <?php
    $from = UI::createTextArea('informasi_umum', $row['informasi_umum'], '', '', $edited, $class = 'form-control contents-mini', "style='width:70%'");
    echo UI::createFormGroup($from, $rules["informasi_umum"], "informasi_umum", "Informasi Umum", false, 2);
    ?>

    <?php
    $from = UI::createTextArea('hasil_evaluasi', $row['hasil_evaluasi'], '', '', $edited, $class = 'form-control contents-mini', "style='width:70%'");
    echo UI::createFormGroup($from, $rules["hasil_evaluasi"], "hasil_evaluasi", "Hasil Evaluasi", false, 2);
    ?>


    <?php
    if ($row['status']) {
        $from = UI::createSelect('status', $statusarr, $row['status'], $edited, $class = 'form-control', "style='width:70%'");
        echo UI::createFormGroup($from, $rules['status'], "status", "Status", false, 2);
    }
    ?>
</div>
<div class="col-6">
    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from);
    ?>
</div>