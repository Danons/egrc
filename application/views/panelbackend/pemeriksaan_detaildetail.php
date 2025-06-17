<div class="row">
    <div class="col-sm-6">
        <?php
        // dpr($row);
        $from = UI::createTextArea('uraian', $row['uraian'], '', '', $edited, $class = 'form-control ', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["uraian"], "uraian", "Uraian", false, 4);

        $from = UI::createSelect("user_id", $pelaksanaarr, $row['user_id'], $edited, 'form-control ', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
        $from .= UI::createTextBox('nama_jabatan', $row['nama_jabatan'], '200', '100', $edited, 'form-control ', "style='width:100%' readonly");
        echo UI::createFormGroup($from, $rules["nama_jabatan"], "nama_jabatan", "Dilaksanakan Oleh", false, 4);

        $from = UI::createSelect('id_kka', $kkaarr, $row['id_kka'], $edited, 'form-control ', "style='width:100%' onchange='goSubmit(\"set_value\")'");
        echo UI::createFormGroup($from, $rules["id_kka"], "id_kka", "Nomor KKA", false, 4);
        if (!$edited) {
            $from = UI::createUploadMultiple("fileskka", $row['fileskka'], "panelbackend/mt_pemeriksaan_kka", $edited);
            echo UI::createFormGroup($from, $rules["file"], "fileskka", "File KKA", false, 4);
        }
        ?>
    </div>
    <div class="col-sm-6">
        <?php
        $from = UI::createTextBox('anggaran', $row['anggaran'], '200', '100', $edited, $class = 'form-control rupiah', "style='width:150px'");
        echo UI::createFormGroup($from, $rules["anggaran"], "anggaran", "Anggaran", false, 4);

        if (!$edited) {
            $from = UI::createTextBox('realisasi', $row['realisasi'], '200', '100', $edited, $class = 'form-control rupiah', "style='width:150px'");
            echo UI::createFormGroup($from, $rules["realisasi"], "realisasi", "Realisasi", false, 4);
        }

        $from = UI::createTextArea('detail_uraian', $row['detail_uraian'], '', '', $edited, $class = 'form-control contents', "");
        echo UI::createFormGroup($from, $rules["detail_uraian"], "detail_uraian", "Keterangan", false, 4);

        $from = UI::createUploadMultiple("files", $row['files'], $page_ctrl, true);
        echo UI::createFormGroup($from, $rules["file"], "file", "File LHE", false, 4);

        $from = UI::showButtonMode("save", null, $edited);
        echo UI::createFormGroup($from, null, null, null, false, 4);
        ?>
    </div>
</div>

<?php if (!$edited) { ?>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center <?= ($broadcrum1 && count($broadcrum1) > 1 ? "pt-1" : "pt-3") ?> pb-2 mb-3 ">
        <h4 class="h4">
            Reviu Supervisi
        </h4>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?= site_url('panelbackend/pemeriksaan_review_supervisi/add/' . $row['id_pemeriksaan'] . '/' . $row['id_pemeriksaan_detail']) ?>" class="btn btn-sm btn-primary">Add</a>
            <?php
            // if ($page_ctrl != "panelbackend/pemeriksaan_checklist") {
            //     // $buttonMenu = "";
            //     $buttonMenu .= UI::showButtonMode($mode, $row[$pk], $edited);
            //     if ($buttonMenu) {
            //         echo $buttonMenu;
            //     }
            // }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 1px;">No</th>
                        <th>Permasalahan/Komentar</th>
                        <th>Penyelesaian</th>
                        <!-- <th style="width: 1px;">Persetujuan</th> -->
                        <th style="width: 1px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($rowsreview as $r) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $r['permsalahan'] ?></td>
                            <td><?= $r['penyelesaian'] ?></td>
                            <!-- <td><?= UI::createCheckBox("is_persetujuan[$r[id_review_supervisi]]", 1, $r['is_persetujuan'], "Oke", false) ?></td> -->
                            <td>
                                <?= UI::startMenu() ?>
                                <li>
                                    <a href="javascript:void(0)" class=" dropdown-item " onclick="goEditReview('<?= $r['id_review_supervisi'] ?>')"><i class="bi bi-pencil"></i> Edit</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class=" dropdown-item " onclick="goDeleteReview('<?= $r['id_review_supervisi'] ?>')"><i class="bi bi-trash"></i> Delete</a>
                                </li>
                                <?= UI::closeMenu() ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <script>
                function goEditReview(id) {
                    window.location = "<?= base_url('panelbackend/pemeriksaan_review_supervisi/edit/' . $row['id_pemeriksaan'] . '/' . $row['id_pemeriksaan_detail']) ?>/" + id;
                }

                function goDeleteReview(id) {
                    if (confirm("Apakah Anda yakin akan menghapus ?")) {
                        window.location = "<?= base_url('panelbackend/pemeriksaan_review_supervisi/delete/' . $row['id_pemeriksaan'] . '/' . $row['id_pemeriksaan_detail']) ?>/" + id;
                    }
                }
            </script>
        </div>
    </div>


    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center <?= ($broadcrum1 && count($broadcrum1) > 1 ? "pt-1" : "pt-3") ?> pb-2 mb-3 ">
        <h4 class="h4">
            Temuan
        </h4>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?= site_url('panelbackend/pemeriksaan_temuan/add/' . $row['id_pemeriksaan'] . '/' . $row['id_pemeriksaan_detail']) ?>" class="btn btn-sm btn-primary">Add</a>
            <?php
            // if ($page_ctrl != "panelbackend/pemeriksaan_checklist") {
            //     // $buttonMenu = "";
            //     $buttonMenu .= UI::showButtonMode($mode, $row[$pk], $edited);
            //     if ($buttonMenu) {
            //         echo $buttonMenu;
            //     }
            // }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 1px;">No</th>
                        <th>Halaman LHE</th>
                        <th>Masalah yang dijumpai</th>
                        <th>Kondisi</th>
                        <th>Kriteria</th>
                        <th>Sebab</th>
                        <th>Akibat</th>
                        <th>Rekomendasi</th>
                        <th>Rencana Tindak Lanjut</th>
                        <th>Komentar Auditi</th>
                        <th>Komentar Pengawas</th>
                        <th>Keterangan</th>
                        <!-- <th style="width: 1px;"></th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($rowstemuan as $r) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $r['halaman_lhe'] ?></td>
                            <td><a href="<?= base_url('panelbackend/pemeriksaan_temuan/detail/' . $row['id_pemeriksaan'] . '/' . $row['id_pemeriksaan_detail'] . '/' . $r['id_pemeriksaan_temuan']) ?>"><?= $r['judul_temuan'] ?></a></td>
                            <td><?= $r['kondisi'] ?></td>
                            <td><?= $r['kriteria'] ?></td>
                            <td><?= $r['sebab'] ?></td>
                            <td><?= $r['akibat'] ?></td>
                            <td><?= $r['rekomendasi'] ?></td>
                            <td><?= $r['rencana_tindakan_perbaikan'] ?></td>
                            <td><?= $r['komentar_auditi'] ?></td>
                            <td><?= $r['komentar_pengawas'] ?></td>
                            <td><?= $r['keterangan'] ?></td>
                            <!-- <td>
                                <?= UI::startMenu() ?>
                                <li>
                                    <a href="javascript:void(0)" class=" dropdown-item " onclick="goEditReview('<?= $r['id_review_supervisi'] ?>')"><i class="bi bi-pencil"></i> Edit</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class=" dropdown-item " onclick="goDeleteReview('<?= $r['id_review_supervisi'] ?>')"><i class="bi bi-trash"></i> Delete</a>
                                </li>
                                <?= UI::closeMenu() ?>
                            </td> -->
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <!-- <script>
                function goEditTemuan(id) {
                    window.location = "<?= base_url('panelbackend/pemeriksaan_review_supervisi/edit/' . $row['id_pemeriksaan'] . '/' . $row['id_pemeriksaan_detail']) ?>/" + id;
                }

                function goDeleteTemuan(id) {
                    if (confirm("Apakah Anda yakin akan menghapus ?")) {
                        window.location = "<?= base_url('panelbackend/pemeriksaan_review_supervisi/delete/' . $row['id_pemeriksaan'] . '/' . $row['id_pemeriksaan_detail']) ?>/" + id;
                    }
                }
            </script> -->
        </div>
    </div>
<?php } ?>