<div class="col-sm-12">

    <?php
    $from = UI::createTextNumber('rtm_ke', $row['rtm_ke'], '10', '10', $edited, $class = 'form-control ', "style='text-align:right; width:85px' min='0' max='10000000000' step='any'");
    echo UI::createFormGroup($from, $rules["rtm_ke"], "rtm_ke", "RTM ke", false, 2);
    ?>

    <?php
    $from = UI::createSelect('tingkat', ["" => "", "Pusat" => "Pusat", "Wilayah" => "Wilayah"], $row['tingkat'], $edited, $class = 'form-control ', "style='width:85px; max-width:100%;'");
    echo UI::createFormGroup($from, $rules["tingkat"], "tingkat", "Tingkat", false, 2);
    ?>

    <?php
    $from = UI::createSelect('rkt', $mtperiodetwarr, $row['rkt'], $edited, $class = 'form-control ', "style='width:auto; max-width:100%;'");
    echo UI::createFormGroup($from, $rules["rkt"], "rkt", "RKT", false, 2);
    ?>

    <?php
    $from = UI::createTextNumber('tahun', $row['tahun'], '10', '10', $edited, $class = 'form-control ', "style='text-align:right; width:85px' min='0' max='10000000000' step='any'");
    echo UI::createFormGroup($from, $rules["tahun"], "tahun", "Tahun", false, 2);

    $from = UI::createUploadMultiple("files", $row['files'], $page_ctrl, $edited);
    echo UI::createFormGroup($from, $rules["file"], "file", "Lampiran", false, 2);
    ?>

    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from, null, null, null, false, 2);
    ?>
</div>