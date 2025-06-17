<?= $edited ? UI::createTextArea('title', $row['title'], '1', '', $edited, 'form-control') : null ?>
<?= UI::createTextArea('contents', $row['contents'], '10', '', $edited, 'form-control contents') ?>
<br />
<?php
// dpr($tgl_efektif, 1);
/*
if ($edited) {
    // dpr($row['tgl_mulai_aktif']);
    if (!$row['tgl_mulai_aktif']) {
        $row['tgl_mulai_aktif'] = date('Y-m-d');
    }
    if ($this->data['page'] == "spi_achievement") {
        // dpr($filter_tahun, 1);
        $row['tgl_mulai_aktif'] = date('Y', strtotime($row['tgl_mulai_aktif']));
        $from = UI::createTextNumber('tgl_mulai_aktif', $row['tgl_mulai_aktif'], '10', '10', ($view_all && $edited), $class = 'form-control', "style='width:100px'");
        // $from .= "&nbsp;&nbsp;sd&nbsp;&nbsp;" . UI::createTextNumber('tgl_akhir_aktif', $row['tgl_akhir_aktif'], '10', '10', ($view_all && $edited), $class = 'form-control ', "style='width:100px'");
        echo UI::createFormGroup("<div class='d-flex'>" . $from . "</div>", $rules["tgl_akhir_aktif"], "tgl_akhir_aktif", "Tgl. Aktif", true);
    } else {
        $from = UI::createTextBox('tgl_mulai_aktif', $row['tgl_mulai_aktif'], '10', '10', ($view_all && $edited), $class = 'form-control datepicker', "style='width:100px'");
        $from .= "&nbsp;&nbsp;sd&nbsp;&nbsp;" . UI::createTextBox('tgl_akhir_aktif', $row['tgl_akhir_aktif'], '10', '10', ($view_all && $edited), $class = 'form-control datepicker', "style='width:100px'");
        echo UI::createFormGroup("<div class='d-flex'>" . $from . "</div>", $rules["tgl_akhir_aktif"], "tgl_akhir_aktif", "Tgl. Aktif", true);
    }
} */ ?>
<?= UI::showButtonMode("save", null, $edited) ?>