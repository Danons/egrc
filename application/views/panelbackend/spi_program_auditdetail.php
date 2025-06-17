<div class="col-sm-6">

    <?php
    $from = UI::createTextBox('nama_audit', $row['nama_audit'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["nama_audit"], "nama_audit", "Nama Auditi");
    ?>

    <?php
    $from = UI::createTextNumber('id_risk_risiko', $row['id_risk_risiko'], '10', '10', $edited, $class = 'form-control ', "style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
    echo UI::createFormGroup($from, $rules["id_risk_risiko"], "id_risk_risiko", "Risk Risiko");
    ?>

    <?php
    $from = UI::createTextBox('sarana_kendaraan', $row['sarana_kendaraan'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["sarana_kendaraan"], "sarana_kendaraan", "Sarana Kendaraan");
    ?>

    <?php
    $from = UI::createTextBox('sarana_lainnya', $row['sarana_lainnya'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["sarana_lainnya"], "sarana_lainnya", "Sarana Lainnya");
    ?>

    <?php
    $from = UI::createTextNumber('dana_sppd', $row['dana_sppd'], '10', '10', $edited, $class = 'form-control ', "style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
    echo UI::createFormGroup($from, $rules["dana_sppd"], "dana_sppd", "Dana Sppd");
    ?>

    <?php
    $from = UI::createTextNumber('dana_lainnya', $row['dana_lainnya'], '10', '10', $edited, $class = 'form-control ', "style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
    echo UI::createFormGroup($from, $rules["dana_lainnya"], "dana_lainnya", "Dana Lainnya");
    ?>

</div>
<div class="col-sm-6">


    <?php
    $from = UI::createTextArea('lain-lain', $row['lain-lain'], '', '', $edited, $class = 'form-control contents', "");
    echo UI::createFormGroup($from, $rules["lain-lain"], "lain-lain", "Lain-lain");
    ?>

    <?php
    $from = UI::createTextNumber('tahun', $row['tahun'], '10', '10', $edited, $class = 'form-control ', "style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
    echo UI::createFormGroup($from, $rules["tahun"], "tahun", "Tahun");
    ?>

    <?php
    $from = UI::createTextBox('jenis_audit', $row['jenis_audit'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["jenis_audit"], "jenis_audit", "Jenis Audit");
    ?>

    <?php
    $from = UI::createTextBox('frekuensi_audit', $row['frekuensi_audit'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["frekuensi_audit"], "frekuensi_audit", "Frekuensi Audit");
    ?>

    <?php
    $from = UI::createTextBox('minggu_mulai', $row['minggu_mulai'], '50', '50', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["minggu_mulai"], "minggu_mulai", "Minggu Mulai");
    ?>

    <?php
    $from = UI::createTextBox('minggu_selesai', $row['minggu_selesai'], '50', '50', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["minggu_selesai"], "minggu_selesai", "Minggu Selesai");
    ?>

    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from);
    ?>
</div>