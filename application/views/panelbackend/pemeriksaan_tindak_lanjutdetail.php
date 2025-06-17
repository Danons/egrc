<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center <?= ($broadcrum1 && count($broadcrum1) > 1 ? "pt-1" : "pt-3") ?> pb-2 mb-3 ">
    <div class="new-content-title">
        <h4 class="h4">Hasil Audit</h4>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a class='btn btn-primary btn-sm' target="_BLANK" href="<?= site_url("panelbackend/pemeriksaan_temuan/printdetail/4/konsep/$rowheader[id_pemeriksaan]/" . $_SESSION[SESSION_APP][$rowheader['jenis'] . 'jenis']) ?>">
            <i class="bi bi-printer"></i> Tindak Lanjut Temuan Audit
        </a>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">


        <?php
        echo '<div style="display:block"><b>' . $rowheader2['judul_temuan'] . '</b>' . ($rowheader2['detail_uraian'] ? '<br/>' : '') . $rowheader2['detail_uraian'] . "</div>";
        echo '<br/>';
        echo UI::createFormGroup($rowheader2['kondisi'] . $rowheader2['kondisi1'], null, null, "Kondisi", true);
        echo UI::createFormGroup($rowheader2['kriteria'], null, null, "Kriteria", true);
        $labelsaran = "";
        if ($rowheader2['jenis_temuan'] == "MAJOR" || $rowheader2['jenis_temuan'] == "Temuan") {
            echo UI::createFormGroup($rowheader2['sebab'], null, null, "Sebab", true);
            echo UI::createFormGroup($rowheader2['akibat'], null, null, "Akibat", true);
            echo UI::createFormGroup($rowheader2['rekomendasi'], null, null, "Rekomendasi", true);
            $labelsaran = "Rekomendasi";
        } else {
            echo UI::createFormGroup($rowheader2['saran'], null, null, "Saran", true);
            $labelsaran = "Saran";
        }
        $from = UI::createUploadMultiple("files11", $rowheader2['files'], $page_ctrl, $editedheader1);
        echo UI::createFormGroup($from, $rules["file11"], "file11", "Lampiran", true);
        echo UI::createFormGroup($rowheader2['status'], null, null, "Status", true);
        ?>
    </div>
</div>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center <?= ($broadcrum1 && count($broadcrum1) > 1 ? "pt-1" : "pt-3") ?> pb-2 mb-3 ">
    <div class="new-content-title">
        <h4 class="h4">Tindak Lanjut Hasil Audit</h4>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a class='btn btn-light btn-sm' href="<?= site_url("panelbackend/pemeriksaan_temuan/index/$rowheader[id_pemeriksaan]") ?>"><i class="bi bi-arrow-left"></i> Daftar Hasil Audit</a>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <?php
        // $edited = true;
        // $editedauditor = true;
        $from = UI::createTextNumber("tahun", $row['tahun'], '', '', true, 'form-control', "style='width:100px'");
        echo UI::createFormGroup($from, $rules["tahun"], "tahun", "Tahun", true);
        $from = UI::createSelect('id_periode_tw', $mtperiodetwarr, $row['id_periode_tw'], true, 'form-control ', "style='width:100%' onchange='goSubmit(\"set_periode\")'");
        echo UI::createFormGroup($from, $rules["id_periode_tw"], "id_periode_tw", "Periode Triwulan", true);
        ?>

        <?php
        // $from = UI::createTextArea('rincian_tindak_lanjut', $row['rincian_tindak_lanjut'], '', '', $edited, 'form-control contents', "");
        // echo UI::createFormGroup($from, $rules["rincian_tindak_lanjut"], "rincian_tindak_lanjut", "Rincian Tindak Lanjut", true);

        foreach ($rowheader2['saranarr'] as $r) {
            $from =  $r['deskripsi'];
            $from .= "<br/><b>PIC : $r[nama_pic]</b>";
            echo UI::createTextHidden('pic_tindak_lanjut[' . $r['id_pemeriksaan_temuan_saran'] . ']', $r['id_jabatan'], $edited && $r['id_jabatan'] == $_SESSION[SESSION_APP]['id_jabatan']);
            echo UI::createFormGroup($from, $rules['tindaklanjutarr[]'], "tindaklanjutarr[]", $labelsaran, true);
            $from = UI::createTextArea('tindaklanjutarr[' . $r['id_pemeriksaan_temuan_saran'] . ']', $row['tindaklanjutarr'][$r['id_pemeriksaan_temuan_saran']], '', '', $edited && $r['id_jabatan'] == $_SESSION[SESSION_APP]['id_jabatan'], 'form-control contents', "");
            echo UI::createFormGroup($from, $rules['tindaklanjutarr[]'], "tindaklanjutarr[]", "Tindak Lanjut", true)."<hr/>";
        }
        ?>


        <?php
        // if ($row['status'] === null)
        //     $row['status'] = 1;

        // $from = UI::createRadio("status", ['1' => 'Open', '0' => "Close"], $row['status'], $edited);
        // echo UI::createFormGroup($from, $rules["status"], "status", "Status", true);

        $from = UI::createUploadMultiple("files", $row['files'], $page_ctrl, $edited);
        echo UI::createFormGroup($from, $rules["file"], "file", "Lampiran", true);
        ?>

        <?php
        if (($row['hasil_evaluasi'] !== null && $row['hasil_evaluasi'] !== '') || $editedauditor) {
            $from = UI::createRadio("hasil_evaluasi", ['2' => 'Sesuai', '1' => 'Potensi Sesuai', '0' => "Belum Sesuai"], $row['hasil_evaluasi'], $editedauditor);
            echo UI::createFormGroup($from, $rules["hasil_evaluasi"], "hasil_evaluasi", "Hasil Evaluasi", true);
        ?>

        <?php
            $from = UI::createTextArea('kesimpulan', $row['kesimpulan'], '', '', $editedauditor, 'form-control contents', "");
            echo UI::createFormGroup($from, $rules["kesimpulan"], "kesimpulan", "Kesimpulan", true);
        }
        ?>

        <?php
        $from = UI::showButtonMode("save", null, $edited || $editedauditor);
        echo UI::createFormGroup($from, null, null, null, true);
        ?>
    </div>
</div>