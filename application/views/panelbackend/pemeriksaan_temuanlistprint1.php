<div class="row">
    <div class="col-sm-12">

        <?php
        $from = UI::createSelect('id_unit', $unitarr, $rowheader['id_unit'], $editedheader, 'form-control ', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["id_unit"], "id_unit", "Nama Objek Pemeriksaan", false, 2);

        if ($rowheader['jenis'] == 'penyuapan') {
            $from = UI::createTextBox('objeklainnya', $rowheader['objeklainnya'], '200', '100', $editedheader, 'form-control ', "style='width:100%'");
            echo UI::createFormGroup($from, $rules["objeklainnya"], "objeklainnya", "Objek Pemeriksaan Lainnya", false, 2);
        }
        ?>

        <?php
        $from = UI::createSelect('id_subbid', $subbidarr, $rowheader['id_subbid'], $editedheader, 'form-control ', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["id_subbid"], "id_subbid", "Bidang", false, 2);

        $from = UI::createTextBox('nama', $rowheader['nama'], '200', '100', $editedheader, 'form-control ', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama Kegiatan / Program / Bidang", false, 2);

        if ($row['jenis'] == 'eksternal') {
            $from = UI::createSelect('id_jenis_audit_eksternal', $jeniseksternalarr, $row['id_jenis_audit_eksternal'], $edited, 'form-control ', "style='width:100%'");
            echo UI::createFormGroup($from, $rules["id_jenis_audit_eksternal"], "id_jenis_audit_eksternal", "Jenis Audit Eksternal", false, 2);
        }

        ?>

        <?php
        $from = UI::createTextArea('lokasi', $rowheader['lokasi'], '', '', $editedheader, 'form-control ', "");
        echo UI::createFormGroup($from, $rules["lokasi"], "lokasi", "Lokasi", false, 2);
        ?>

        <?php
        $from = UI::createTextBox('tgl_mulai', $rowheader['tgl_mulai'], '10', '10', $editedheader, 'form-control datepicker', "style='width:100px; display:inline;'");
        $from .= "&nbsp;s/d&nbsp;" . UI::createTextBox('tgl_selesai', $rowheader['tgl_selesai'], '10', '10', $editedheader, 'form-control datepicker', "style='width:100px; display:inline;'");
        echo UI::createFormGroup($from, $rules["tgl_mulai"], "tgl_mulai", "Periode Pemeriksaan", false, 2);
        ?>

        <?php
        if ($rowheader['jenis'] == 'mutu' || $rowheader['jenis'] == 'penyuapan') {
            $from = UI::createTextBox('nama_jabatan_penyusun', $rowheader['nama_jabatan_penyusun'], '200', '100', $editedheader, 'form-control ', "style='width:100%'");
            echo UI::createFormGroup($from, $rules["nama_jabatan_penyusun"], "nama_jabatan_penyusun", "Auditor", false, 2);
        ?>

        <?php
            $from = UI::createTextBox('nama_jabatan_pereview', $rowheader['nama_jabatan_pereview'], '200', '100', $editedheader, 'form-control ', "style='width:100%'");
            echo UI::createFormGroup($from, $rules["nama_jabatan_pereview"], "nama_jabatan_pereview", "Ketua Tim Audit", false, 2);
        } else {
            $from = UI::createTextBox('nama_jabatan_penyusun', $rowheader['nama_jabatan_penyusun'], '200', '100', $editedheader, 'form-control ', "style='width:100%'");
            echo UI::createFormGroup($from, $rules["nama_jabatan_penyusun"], "nama_jabatan_penyusun", "Pengawas", false, 2);
        ?>

        <?php
            $from = UI::createTextBox('nama_jabatan_pereview', $rowheader['nama_jabatan_pereview'], '200', '100', $editedheader, 'form-control ', "style='width:100%'");
            echo UI::createFormGroup($from, $rules["nama_jabatan_pereview"], "nama_jabatan_pereview", "Ketua Tim", false, 2);
        }
        ?>
    </div>
</div>
<hr />
<h4 style="padding:0px;text-align: center;">
    <b>Hasil Audit</b>
</h4>
<?php $no = 1;
if ($listtemuan['rows'])
    foreach ($listtemuan['rows'] as $r) { ?>
    <div class="row listdetail">
        <div class="col-sm-12">
            <?php
            echo UI::createFormGroup("<b>" . $r['jenis_temuan'] . "</b>", null, null, "&nbsp;", false, 2);
            $r['jenis_temuan'] = ["Catatan" => "MINOR", "Temuan" => "MAJOR"][$r['jenis_temuan']];

            $judultemuan = '<b>' . $r['judul_temuan'] . '</b><br />' . $r['detail_uraian'] . '';

            echo UI::createFormGroup($judultemuan, null, null, "Judul", false, 2);
            echo UI::createFormGroup($r['kondisi'], null, null, "Kondisi", false, 2);
            echo UI::createFormGroup($r['kriteria'], null, null, "Kriteria", false, 2);
            if ($r['jenis_temuan'] == "MAJOR") {
                echo UI::createFormGroup($r['sebab'], null, null, "Sebab", false, 2);
                echo UI::createFormGroup($r['akibat'], null, null, "Akibat", false, 2);
                echo UI::createFormGroup($r['rekomendasi'], null, null, "Rekomendasi", false, 2);
            } else {
                echo UI::createFormGroup($r['saran'], null, null, "Saran", false, 2);
            }
            ?>
        </div>
    </div>
<?php } ?>