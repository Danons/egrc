<div class="col-sm-6">

    <?php
    $from = UI::createTextBox('tanggal_rapat', $row['tanggal_rapat'], '10', '10', $edited, $class = 'form-control datepicker', "style='width:100px'");
    echo UI::createFormGroup($from, $rules["tanggal_rapat"], "tanggal_rapat", "Tanggal Rapat");
    ?>

    <?php
    $from = UI::createTextBox('nama_rapat', $row['nama_rapat'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["nama_rapat"], "nama_rapat", "Nama Rapat");
    // dpr($mode);
    ?>

    <?php
    $from = UI::createTextBox('waktu_rapat', $row['waktu_rapat'], '50', '50', $edited, $class = 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["waktu_rapat"], "waktu_rapat", "Waktu Rapat");
    ?>


    <?php
    $no = 1;
    $from = function ($val = null, $edited, $k = 0, $ci, $no, $rows) {
        $from = null;
        if (count($rows) > 1) {
            $from .= "<td style='width:1px'>";
            $from .= $no;
            $from .= ". </td>";
        }
        $from .= "<td>";
        $from .= UI::createTextBox(
            "acara[$k]",
            $val,
            '',
            '',
            $edited,
            'form-control',
            "style='width:100%;' "
        );
        $from .= "</td>";

        if ($edited) {
            $from .= "<td style='position:relative; text-align:right; vertical-align:top; width:1px'>";
        }
        return $from;
    };

    // dpr($row['acara']);
    if (!$row['acara'])
        $row['acara'] = [[]];


    $from = "<table width='100%'>" . UI::AddFormTable('acara', $row['acara'], $from, $edited) . "</table>";
    echo UI::createFormGroup($from, $rules["acara"], "acara", "Acara");
    ?>

    <?php
    // dpr($row['acara']);
    // $from = UI::createTextBox('acara',$row['acara'],'200','100',$edited,$class='form-control ',"style='width:100%'");
    // echo UI::createFormGroup($from, $rules["acara"], "acara", "Acara");
    ?>

    <?php
    $from = UI::createTextBox('pimpinan_rapat', $row['pimpinan_rapat'], '', '', $edited, $class = 'form-control ', "style='width: 100%;'");
    echo UI::createFormGroup($from, $rules["pimpinan_rapat"], "pimpinan_rapat", "Pimpinan Rapat");
    ?>

    <?php
    $from = UI::createTextBox('notulis', $row['notulis'], '', '', $edited, $class = 'form-control ', "style='width: 100%;'");
    echo UI::createFormGroup($from, $rules["notulis"], "notulis", "Notulis");
    ?>

    <?php
    $from = UI::createSelect('id_jabatan_notulis', $jabatanarr, $row['id_jabatan_notulis'], $edited, $class = 'form-control id_jabatan_notulis', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["id_jabatan_notulis"], "id_jabatan_notulis", "Jabatan Notulis");
    // echo UI::createFormGroup($from, $rules["jabatan_notulis[]"], "jabatan_notulis[]", "Pengendali " . ($edited ? "<small><a href='javascript:void(0);' onclick='$(\".id_jabatanarr\").val(" . json_encode(array_keys($mtunitarr)) . ").change();'>Semua</a></small>" : null));
    ?>

</div>
<div class="col-sm-6">
    <?php
    $no = 1;
    $from = function ($val = null, $edited, $k = 0, $ci, $no, $rows) {
        $from = null;
        if (count($rows) > 1) {
            $from .= "<td style='width:1px'>";
            $from .= $no;
            $from .= ". </td>";
        }
        $from .= "<td>";
        $from .= UI::createTextBox(
            "id_peserta[$k]",
            $val,
            '',
            '',
            $edited,
            'form-control',
            "style='width:100%;' "
        );
        $from .= "</td>";

        if ($edited) {
            $from .= "<td style='position:relative; text-align:right; vertical-align:top; width:1px'>";
        }
        return $from;
    };

    if (!$row['id_peserta'])
        $row['id_peserta'] = [[]];

    $from = "<table width='100%'>" . UI::AddFormTable('id_peserta', $row['id_peserta'], $from, $edited) . "</table>";
    echo UI::createFormGroup($from, $rules["id_peserta"], "id_peserta", "Nama Peserta");
    ?>

    <?php
    $from = UI::createTextBox('kegiatan_rapat', $row['kegiatan_rapat'], '10', '10', $edited, $class = 'form-control ', "width:100%;");
    echo UI::createFormGroup($from, $rules["kegiatan_rapat"], "kegiatan_rapat", "Kegiatan Rapat");
    ?>
</div>
<div class="col-12">

    <?php
    $from = UI::createTextArea('pembukaan', $row['pembukaan'], '', '', $edited, $class = 'form-control contents', "style='width:70%;'");
    echo UI::createFormGroup($from, $rules["pembukaan"], "pembukaan", "Pembukaan", $onlyone, 2);
    ?>


    <?php
    $from = UI::createTextArea('pembahasan', $row['pembahasan'], '', '', $edited, $class = 'form-control contents-mini', "style='width:70%;'");
    echo UI::createFormGroup($from, $rules["pembahasan"], "pembahasan", "Pembahasan", $onlyone, 2);
    ?>


    <?php
    $from = UI::createTextArea('penutup', $row['penutup'], '', '', $edited, $class = 'form-control contents-mini', "style='width:70%;'");
    echo UI::createFormGroup($from, $rules["penutup"], "penutup", "Penutup", $onlyone, 2);
    ?>

    <?php
    $from = UI::createTextArea('kesimpulan', $row['kesimpulan'], '', '', $edited, $class = 'form-control contents-mini', "style='width:70%;'");
    echo UI::createFormGroup($from, $rules["kesimpulan"], "kesimpulan", "Kesimpulan", $onlyone, 2);
    ?>
</div>
<div class="col-6">

    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from);
    ?>
</div>