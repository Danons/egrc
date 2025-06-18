<div class="col-sm-9">
    <?php
    $from = UI::createTextArea('catatan', $row['catatan'], '', '4', $edited, $class = 'form-control contents-mini', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["catatan"], "catatan", "Catatan", false, 3);
    ?>

    <?php
    $from = UI::createSelect('status', $statusarr, $row['status'], $edited, $class = 'form-control', "style='width:100%'");
    echo UI::createFormGroup($from, $rules['status'], "status", "Status", false, 3);
    ?>

    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from, null, null, null, false, 3);
    ?>
</div>