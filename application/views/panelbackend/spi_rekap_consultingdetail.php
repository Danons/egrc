<div class="col-sm-12">

    <?php
    $from = UI::createTextBox('tanggal', $row['tanggal'], '100', '100', $edited, $class = 'form-control datepicker', " onchange='goSubmit(\"set_value\")'");
    echo UI::createFormGroup($from, $rules["tanggal"], "tanggal", 'Tgl. Rapat <a href="javascript:void(0)" ><span class="material-icons" style="font-size:inherit" data-bs-toggle="tooltip" data-bs-original-title="Kriteria">info</span></a>', false, 2);
    ?>


    <!-- waktu mulai -->
    <?php
    $from = '<div class="d-flex align-items-center">';
    $from .= UI::createTextBox('waktu_mulai', $row['waktu_mulai'], '100', '100', $edited, $class = 'form-control');
    $from .= "&nbsp;s/d&nbsp;";
    $from .= UI::createTextBox('waktu_selesai', $row['waktu_selesai'], '100', '100', $edited, $class = 'form-control');
    $from .= "<span>&nbspWIB</span>";
    $from .= '</div>';
    echo UI::createFormGroup($from, null, "waktu", "Waktu", false, 2);
    ?>

    <?php
    $from = UI::createSelect('id_unit_kerja', $unitKerjaArr, $row['id_unit_kerja'], $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["id_unit_kerja"], "id_unit_kerja", "Unit Kerja / Jabatan", false, 2);


    // $from = UI::createSelect('id_unit_kerja', $unitKerjaArr, $row['id_unit_kerja'], $edited, $class = 'form-control ', "style='width:100%'");
    // echo UI::createFormGroup($from, $rules["id_unit_kerja"], "id_unit_kerja", "Unit Kerja / Jabatan", false, 2);
    ?>


    <?php
    $from = UI::createTextBox('contact_person', $row['contact_person'], '100', '100', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["contact_person"], "contact_person", "Contact Person", false, 2);
    ?>

    <?php
    $from = UI::createTextBox('nomor_telpon', $row['nomor_telpon'], '100', '100', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["nomor_telpon"], "nomor_telpon", "No. Telpon / HP", false, 2);
    ?>
    <?php
    $from = UI::createTextArea('uraian_layanan', $row['uraian_layanan'], '', '', $edited, $class = 'form-control contents', "");
    echo UI::createFormGroup($from, $rules["uraian_layanan"], "uraian_layanan", "Uraian Layanan Konsultasi", false, 2);
    ?>

</div>
<div class="col-sm-12">


    <?php
    $from = UI::createTextArea('pendapat_spi', $row['pendapat_spi'], '', '', $edited, $class = 'form-control contents', "");
    echo UI::createFormGroup($from, $rules["pendapat_spi"], "pendapat_spi", "Opini / Saran Pendapat SPI", false, 2);
    ?>

    <?php
    $from = UI::createTextArea('dokumen_disampaikan', $row['dokumen_disampaikan'], '', '', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["dokumen_disampaikan"], "dokumen_disampaikan", "Dokumen Yang Disampaikan", false, 2);
    ?>

    <?php
    $from = UI::createTextBox('pengawas', $row['pengawas'], '100', '100', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["pengawas"], "pengawas", "Pengawas", false, 2);
    ?>


    <?php
    // $from = UI::createSelect('pengawas', $userarr, $row['pengawas'], $edited, $class = 'form-control ', "style='width:100%'");
    // echo UI::createFormGroup($from, $rules["pengawas"], "pengawas", "Pengawas");
    ?>

    <?php
    $from = UI::createTextArea('keterangan', $row['keterangan'], '', '', $edited, $class = 'form-control contents', "");
    echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "Keterangan", false, 2);
    ?>

    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from, null, null, null, false, 2);
    ?>
</div>