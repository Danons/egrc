<div class="row">
    <div class="col-sm-12">

        <?php
        //pemeriksaandetailarr
        // dpr($jenisakomodasiarr);
        $from = UI::createSelect("id_jenis", $jenisakomodasiarr, $row['id_jenis'], $edited);
        echo UI::createFormGroup($from, $rules["id_jenis"], "id_jenis", "Jenis Akomodasi", false, 2);

        $from = UI::createSelect("id_pemeriksaan_detail", $pemeriksaandetailarr, $row['id_pemeriksaan_detail'], $edited);
        echo UI::createFormGroup($from, $rules["id_pemeriksaan_detail"], "id_pemeriksaan_detail", "Uraian Pemeriksaan", false, 2);

        $from = UI::createTextBox('nama', $row['nama'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama", false, 2);
        ?>

        <?php
        $from = UI::createTextBox('nilai_realisasi', $row['nilai_realisasi'], '10', '10', $edited, $class = 'form-control rupiah', "style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
        echo UI::createFormGroup($from, $rules["nilai_realisasi"], "nilai_realisasi", "Nilai Realisasi", false, 2);
        ?>

        <?php
        $from = UI::showButtonMode("save", null, $edited);
        echo UI::createFormGroup($from, null, null, null, false, 2);
        ?>
    </div>
</div>