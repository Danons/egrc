<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center <?= ($broadcrum1 && count($broadcrum1) > 1 ? "pt-1" : "pt-3") ?> pb-2 mb-3 ">
    <div class="new-content-title">
        <h4 class="h4"><?= $page_title ?></h4>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">

        <?php
        $buttonMenu = "";
        $buttonMenu = UI::showButtonMode($mode, $row[$pk]);
        if ($buttonMenu) {
            echo $buttonMenu;
        }
        ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">

        <?php
        if (!$row['id_bidang_pemeriksaan'])
            $row['id_bidang_pemeriksaan'] = $id_bidang_pemeriksaan_anggota;

        $bidangarr1 = $bidangarr;
        unset($bidangarr1['']);
        if ($bidangarr1) {
            $from = UI::createSelect("id_bidang_pemeriksaan", $bidangarr, $row['id_bidang_pemeriksaan'], !$id_bidang_pemeriksaan_anggota && $edited, "form-control");
            echo UI::createFormGroup($from, $rules["id_bidang_pemeriksaan"], "id_bidang_pemeriksaan", "Bidang", true);
        }

        $bidangdivisiarr1 = $bidangdivisiarr;
        unset($bidangdivisiarr1['']);
        if ($bidangdivisiarr1) {
            $from = UI::createSelect("id_bidang", $bidangdivisiarr, $row['id_bidang'], $edited, "form-control");
            echo UI::createFormGroup($from, $rules["id_bidang"], "id_bidang", "Bidang Divisi", true);
        }
        if ($rowheader['jenis'] != 'eksternal') {
            $from = UI::createTextBox('halaman_lhe', $row['halaman_lhe'], '', '', $edited, 'form-control', "");
            echo UI::createFormGroup($from, $rules["halaman_lhe"], "halaman_lhe", "Halaman LHE", true);
        }

        $from = UI::createTextArea('judul_temuan', $row['judul_temuan'], '', '', $edited, 'form-control', "");
        echo UI::createFormGroup($from, $rules["judul_temuan"], "judul_temuan", "Masalah yang dijumpai", true);
        ?>

        <?php
        if ($rowheader['jenis'] == 'mutu' || $rowheader['jenis'] == 'penyuapan') {
            $from = UI::createSelect("id_dokumen", $dokumenarr, $row['id_dokumen'], $edited, "form-control");
            echo UI::createFormGroup($from, $rules["id_dokumen"], "id_dokumen", "Dokumen", true);


            $from = UI::createTextArea('klausul', $row['klausul'], '', '', $edited, 'form-control contents-mini', "");
            echo UI::createFormGroup($from, $rules["klausul"], "klausul", "Klausul", true);

            $from = UI::createTextArea('referensi', $row['referensi'], '', '', $edited, 'form-control contents-mini', "");
            echo UI::createFormGroup($from, $rules["referensi"], "referensi", "Referensi", true);
        }
        $from = UI::createTextArea('kondisi', $row['kondisi'] . $row['kondisi1'], '', '', $edited, 'form-control contents', "");
        echo UI::createFormGroup($from, $rules["kondisi"], "kondisi", "Kondisi", true);
        ?>

        <input name="jenis_temuan" id="jenis_temuan" type="hidden" value="<?= $row['jenis_temuan'] ?>">
        <?php

        if ($row['jenis_temuan'] == "MAJOR" || $row['jenis_temuan'] == 'Temuan') {
            $from = UI::createTextArea('kriteria', $row['kriteria'], '', '', $edited, 'form-control contents-mini', "");
            echo UI::createFormGroup($from, $rules["kriteria"], "kriteria", "Kriteria", true);

            $from = UI::createTextArea('sebab', $row['sebab'], '', '', $edited, 'form-control contents-mini', "");
            echo UI::createFormGroup($from, $rules["sebab"], "sebab", "Sebab", true);
        ?>

            <?php
            $from = UI::createTextArea('akibat', $row['akibat'], '', '', $edited, 'form-control contents-mini', "");
            echo UI::createFormGroup($from, $rules["akibat"], "akibat", "Akibat", true);

            $from = "<div class='d-flex'><div>" .
                UI::createTextBox('nilai_kerugian', $row['nilai_kerugian'], '10', '10', $edited, 'form-control rupiah', "style='width:200px; display:inline;'")
                . "</div><div>" .
                UI::createTextBox('satuan', $row['satuan'], '10', '10', $edited, 'form-control', "style='width:200px; display:inline;' placeholder='Satuan'")
                . "</div></div>";
            echo UI::createFormGroup($from, $rules["nilai_kerugian"], "nilai_kerugian", "Nilai Kerugian", true);
            ?>

        <?php
            $labelsaran = "Rekomendasi";
            // $from = UI::createTextArea('rekomendasi', $row['rekomendasi'], '', '', $edited, 'form-control contents-mini', "");
            // echo UI::createFormGroup($from, $rules["rekomendasi"], "rekomendasi", "Rekomendasi", true);
        } else {
            $labelsaran = "Saran";
            // $from = UI::createTextArea('saran', $row['saran'], '', '', $edited, 'form-control contents-mini', "");
            // echo UI::createFormGroup($from, $rules["saran"], "saran", "Saran", true);
        }

        $from = function ($val = null, $edited, $k = 0, $ci) {
            $ci = get_instance();
            $from = null;
            $from .= "<td>";
            $from .= UI::createTextArea("saranarr[$k][deskripsi]", $val['deskripsi'], '', '', $edited, 'form-control contents-mini');
            $from .= UI::createSelect("saranarr[$k][id_jabatan]", ['' => 'Pilih PIC'] + $ci->data['jabatanarr'], $val['id_jabatan'], $edited);
            if ($edited) {
                $from .= "</td>";
                $from .= UI::createTextHidden("saranarr[$k][id_pemeriksaan_temuan_saran]", $val['id_pemeriksaan_temuan_saran'], $edited);

                $from .= "<td style='position:relative; text-align:right; width:0px;vertical-align:top;'>";
            }

            return $from;
        };

        $from = "<table width='100%'>" . UI::AddFormTable('saranarr', $row['saranarr'],  $from, $edited, $this) . "</table>";
        echo UI::createFormGroup($from, $rules['saranarr[]'], "saranarr[]", $labelsaran, true);

        ?>

        <?php
        $from = UI::createTextArea('rencana_tindakan_perbaikan', $row['rencana_tindakan_perbaikan'], '', '', $edited, 'form-control contents-mini', "");
        echo UI::createFormGroup($from, $rules["rencana_tindakan_perbaikan"], "rencana_tindakan_perbaikan", "Rencana Tindak Lanjut", true);
        ?>

        <?php
        $from = UI::createTextArea('keterangan', $row['keterangan'], '', '', $edited, 'form-control contents-mini', "");
        echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "Keterangan", true);
        ?>

        <?php /*
    $from = UI::createTextBox('tgl_klarifikasi', $row['tgl_klarifikasi'], '10', '10', $edited, 'form-control datepicker', "style='width:190px'");
    echo UI::createFormGroup($from, $rules["tgl_klarifikasi"], "tgl_klarifikasi", "Tgl. Klarifikasi");
    ?>

    <?php
    $from = UI::createTextNumber('id_jabatan_auditor', $row['id_jabatan_auditor'], '10', '10', $edited, 'form-control ', "style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
    echo UI::createFormGroup($from, $rules["id_jabatan_auditor"], "id_jabatan_auditor", "Jabatan Auditor");
    ?>

    <?php
    $from = UI::createTextBox('jabatan_auditor', $row['jabatan_auditor'], '100', '100', $edited, 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["jabatan_auditor"], "jabatan_auditor", "Jabatan Auditor");
    ?>

    <?php
    $from = UI::createTextBox('nama_jabatan_auditor', $row['nama_jabatan_auditor'], '100', '100', $edited, 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["nama_jabatan_auditor"], "nama_jabatan_auditor", "Nama Jabatan Auditor");
    ?>

    <?php
    $from = UI::createTextNumber('id_jabatan_auditee', $row['id_jabatan_auditee'], '10', '10', $edited, 'form-control ', "style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
    echo UI::createFormGroup($from, $rules["id_jabatan_auditee"], "id_jabatan_auditee", "Jabatan Auditee");
    ?>

    <?php
    $from = UI::createTextBox('jabatan_auditee', $row['jabatan_auditee'], '100', '100', $edited, 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["jabatan_auditee"], "jabatan_auditee", "Jabatan Auditee");
    ?>

    <?php
    $from = UI::createTextBox('nama_jabatan_auditee', $row['nama_jabatan_auditee'], '100', '100', $edited, 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["nama_jabatan_auditee"], "nama_jabatan_auditee", "Nama Jabatan Auditee"); */
        ?>


        <?php
        // $from = UI::createTextBox('tmt', $row['tmt'], '10', '10', $edited, 'form-control datepicker', "style='width:100px; display:inline;'");
        // echo UI::createFormGroup($from, $rules["tmt"], "tmt", "TMT. Pelaksanaan Monitoring", true);
        if ($row['target_penyelesaian'] && !$edited) {
            $from = UI::createTextBox('target_penyelesaian', $row['target_penyelesaian'], '10', '10', $edited, 'form-control datepicker', "style='width:100px; display:inline;'");
            echo UI::createFormGroup($from, $rules["target_penyelesaian"], "target_penyelesaian", "Target Penyelesaian", true);
        }

        // $from = UI::createCheckBox("is_disetujui", 1, $row['is_disetujui'], null, $edited);
        // echo UI::createFormGroup($from, $rules["is_disetujui"], "is_disetujui", "Disetujui", true);

        $from = UI::createUploadMultiple("files", $row['files'], $page_ctrl, $edited);
        echo UI::createFormGroup($from, $rules["file"], "file", "Lampiran", true);
        ?>
        <?php
        $from = UI::showButtonMode("save", null, $edited);
        echo UI::createFormGroup($from, null, null, null, true);
        ?>
    </div>
</div>
<?php if (!$edited /*&& $rowheader['id_status'] == 5*/) { ?>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center <?= ($broadcrum1 && count($broadcrum1) > 1 ? "pt-1" : "pt-3") ?> pb-2 mb-3 ">
        <div class="new-content-title">
            <h4 class="h4">Tanggapan</h4>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0"></div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?php
            foreach ($rowspesan as $r) {
                $from = UI::createTextArea('keterangan', $r['keterangan'], '', '', false, 'form-control');

                $from .= "<br/>" . UI::createUploadMultiple("files_tanggapan", $row['files_tanggapan'][$r['id_pemeriksaan_temuan_diskusi']], $page_ctrl, false);
                echo UI::createFormGroup($from, null, null, $r['created_by_desc'] . " <i>(" . Eng2Ind($r['created_date']) . ")</i>", true, 2);
            }

            // if ($row['status'] == 'Pemeriksaan' || $row['status'] == 'Tanggapan') {

            if ($this->post['status'])
                $row['status'] = $this->post['status'];

            if ($this->post['keterangan'])
                $rowketerangan['keterangan'] = $this->post['keterangan'];

            if (in_array($row['status'], array('Close', 'Monev'))) {
                $edit = false;
                $this->access_role['feedback'] = false;
            } else {
                $edit = true;
                $this->access_role['feedback'] = true;
            }

            // $edit = true;

            $from = UI::createTextArea('keterangan', $rowketerangan['keterangan'], '', '', $edit, 'form-control', "");
            echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "Pesan", true, 2);
            $statusarr = ["Monev" => "Tindak Lanjuti", "Tolak" => "Tolak"];
            // if ($row['status'] == 'Pemeriksaan')
            //     $statusarr['Pemeriksaan'] = 'Pemeriksaan';
            // if ($row['status'] == 'Tanggapan')
            //     $statusarr['Tanggapan'] = 'Tanggapan';

            // $from = UI::createRadio("status", $statusarr, $row['status'], $this->access_role['feedback'], false, "form-control", "onclick='goSubmit(\"set_value\")'");
            // echo UI::createFormGroup($from, $rules["status"], "status", "", true, 2);

            if ($row['status'] == "Pemeriksaan") {
                $from = UI::createTextBox('target_penyelesaian', $row['target_penyelesaian'], '10', '10', $this->access_role['feedback'], 'form-control datepicker', "style='width:100px; display:inline;'");
                echo UI::createFormGroup($from, $rules["target_penyelesaian"], "target_penyelesaian", "Target Penyelesaian", true, 2);
            }

            $from = UI::createUploadMultiple("files_tanggapan", [], $page_ctrl, $edit);
            echo UI::createFormGroup($from, $rules["file"], "file", "Lampiran Tanggapan", true);

            if ($edit)
                echo "<button onclick='goSubmit(\"kirim_feedback\")' class='btn btn-sm btn-primary'>Kirim</button>";
            // }
            ?>
        </div>
    </div>
<?php } ?>