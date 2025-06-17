<?php
$is_monev = false;
if ($listtemuan['rows'])
    foreach ($listtemuan['rows'] as $r) {
        if ($r['status'] == 'Monev')
            $is_monev = true;
    } ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center <?= ($broadcrum1 && count($broadcrum1) > 1 ? "pt-1" : "pt-3") ?> pb-2 mb-3 ">
    <div class="new-content-title">
        <h4 class="h4">Hasil
            <div style="display: none;">
                <?php
                if ($rowheader['jenis'] == 'operasional') {
                    echo UI::createSelect("jenis_temuan", $jenistemuanarr, $_SESSION[SESSION_APP][$rowheader['jenis'] . 'jenis'], true, 'form-control', 'style="display:inline; width:180px;" onchange="goSubmit(\'setjenistemuan\')"');
                } else if ($rowheader['jenis'] == 'mutu' || $rowheader['jenis'] == 'penyuapan') {
                    echo UI::createSelect("jenis_temuan", $jenistemuanarr, $_SESSION[SESSION_APP][$rowheader['jenis'] . 'jenis'], true, 'form-control', 'style="display:inline; width:180px;" onchange="goSubmit(\'setjenistemuan\')"');
                } else {
                    echo 'Temuan';
                }
                ?>
            </div>
            <?php
            $bidangarr1 = $bidangarr;
            unset($bidangarr1['']);
            if ($bidangarr1)
                echo UI::createSelect("id_bidang_pemeriksaan_filter", $bidangarr, $_SESSION[SESSION_APP][$page_ctrl]['id_bidang_pemeriksaan_filter'], true, 'form-control', 'style="display:inline; width:400px;" onchange="goSubmit(\'filter\')"');
            ?>
        </h4>
    </div>

    <div class="btn-toolbar mb-2 mb-md-0">
        <?php
        $is_anggota = false;
        foreach ($rowheader['pemeriksaan_tim'] as $r) {
            if ($r['user_id'] == $_SESSION[SESSION_APP]['user_id'])
                $is_anggota = true;
        }
        ?>
        <?php /*if ($jumlahtemuan && $rowheader['jenis'] != 'eksternal') {
            if ($_SESSION[SESSION_APP][$rowheader['jenis'] . 'jenis'] == 'Catatan') { ?>
                <a class='btn btn-primary btn-sm' target="_BLANK" href="<?= site_url("panelbackend/pemeriksaan_temuan/go_print/dtp/$rowheader[id_pemeriksaan]/" . $_SESSION[SESSION_APP][$rowheader['jenis'] . 'jenis']) ?>"><i class="bi bi-printer"></i> Print DCP</a>
            <?php } else { ?>
                <a class='btn btn-primary btn-sm' target="_BLANK" href="<?= site_url("panelbackend/pemeriksaan_temuan/go_print/dtp/$rowheader[id_pemeriksaan]/" . $_SESSION[SESSION_APP][$rowheader['jenis'] . 'jenis']) ?>"><i class="bi bi-printer"></i> Print DTP</a>
        <?php }
        }*/
        if ($rowheader['jenis'] != 'eksternal') { ?>
            <a class='btn btn-primary btn-sm' target="_BLANK" href="<?= site_url("panelbackend/pemeriksaan_temuan/printdetail/1/konsep/$rowheader[id_pemeriksaan]/" . $_SESSION[SESSION_APP][$rowheader['jenis'] . 'jenis']) ?>">
                <i class="bi bi-printer"></i> Konsep Temuan dan Rencana Tindak Lanjut
            </a>
            <a class='btn btn-primary btn-sm' target="_BLANK" href="<?= site_url("panelbackend/pemeriksaan_temuan/printdetail/2/konsep/$rowheader[id_pemeriksaan]/" . $_SESSION[SESSION_APP][$rowheader['jenis'] . 'jenis']) ?>">
                <i class="bi bi-printer"></i> Pemantauan Tindak Lanjut Temuan Auditee
            </a>
            <a class='btn btn-primary btn-sm' target="_BLANK" href="<?= site_url("panelbackend/pemeriksaan_temuan/printdetail/3/konsep/$rowheader[id_pemeriksaan]/" . $_SESSION[SESSION_APP][$rowheader['jenis'] . 'jenis']) ?>">
                <i class="bi bi-printer"></i> BA Temuan Belum Ditindaklanjuti
            </a>
            <?php if ($rowheader['id_status'] == 6) { ?>
                <!-- <a class='btn btn-primary btn-sm' target="_BLANK" href="<?= site_url("panelbackend/pemeriksaan_temuan/go_print/lhp/$rowheader[id_pemeriksaan]") ?>"><i class="bi bi-printer"></i> Print LHP</a> -->
            <?php }
        } else {

            if (Access("add", "panelbackend/pemeriksaan_temuan")) { ?>
                <a class='btn btn-primary btn-sm' href="<?= site_url("panelbackend/pemeriksaan_temuan/add/$rowheader[id_pemeriksaan]") ?>">Tambah Temuan</a>
        <?php }
        } ?>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th style="width: 1px;">No</th>
            <?php
            if ($rowheader['jenis'] != 'eksternal') { ?>
                <th>Halaman LHE</th>
            <?php } ?>
            <th>Masalah yang dijumpai</th>
            <th>Kondisi</th>
            <th>Kriteria</th>
            <th>Sebab</th>
            <th>Akibat</th>
            <th>Rekomendasi</th>
            <th>Rencana Tindak Lanjut</th>
            <?php
            if ($rowheader['jenis'] != 'eksternal') { ?>
                <th>Komentar Auditee</th>
                <th>Komentar Pengawas</th>
            <?php } ?>
            <th>Keterangan</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1;
        // dpr($listtemuan, 1);
        foreach ($listtemuan['rows'] as $r) { ?>
            <tr>
                <td><?= $no++ ?></td>
                <?php
                if ($rowheader['jenis'] != 'eksternal') { ?>
                    <td><?= $r['halaman_lhe'] ?></td>
                <?php } ?>
                <td><a href="<?= base_url('panelbackend/pemeriksaan_temuan/detail/' . $r['id_pemeriksaan'] . '/' . (int)$r['id_pemeriksaan_detail'] . '/' . $r['id_pemeriksaan_temuan']) ?>"><?= $r['judul_temuan'] ?></a></td>
                <td><?= $r['kondisi'] ?></td>
                <td><?= $r['kriteria'] ?></td>
                <td><?= $r['sebab'] ?></td>
                <td><?= $r['akibat'] ?></td>
                <td><?= $r['rekomendasi'] ?></td>
                <td><?= $r['rencana_tindakan_perbaikan'] ?></td>
                <?php
                if ($rowheader['jenis'] != 'eksternal') { ?>
                    <td><?= $r['komentar_auditi'] ?></td>
                    <td><?= $r['komentar_pengawas'] ?></td>
                <?php } ?>
                <td><?= $r['keterangan'] ?></td>
                <?php /*
                            <!-- <td>
                                <?= UI::startMenu() ?>
                                <li>
                                    <a href="javascript:void(0)" class=" dropdown-item " onclick="goEditReview('<?= $r['id_review_supervisi'] ?>')"><i class="bi bi-pencil"></i> Edit</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class=" dropdown-item " onclick="goDeleteReview('<?= $r['id_review_supervisi'] ?>')"><i class="bi bi-trash"></i> Delete</a>
                                </li>
                                <?= UI::closeMenu() ?>
                            </td> --> */ ?>
                <td>

                    <?php
                    if ($rowheader['id_status'] == 6 && $r['status'] == 'Monev') { ?>
                        <?php if ($r['tindaklanjutterakhir']) {
                            $rt = $r['tindaklanjutterakhir']; ?>
                            <small>Tindak lanjut terakhir <?= $mtperiodetwarr[$rt['id_periode_tw']] ?> Tahun <?= $rt['tahun'] ?> <?= ['2' => 'Sesuai', '1' => 'Potensi Sesuai', '0' => "Belum Sesuai"][$rt['hasil_evaluasi']] ?></small>
                        <?php } ?>

                        <a class="btn btn-sm btn-warning" href="<?= site_url("panelbackend/pemeriksaan_tindak_lanjut/edit/$r[id_pemeriksaan_temuan]") ?>">Tindak Lanjut</a>

                        <?php }
                    // if ($r['is_disetujui'])
                    echo "<label class='badge bg-success'>" . $r['status'] . "</label>";

                    if ($this->access_role['setujui']) {
                        if (!strlen($r['is_disetujui'])) { ?>
                            <button class="btn btn-success btn-sm" onclick="goSubmitValue('setujui','<?= $r['id_pemeriksaan_temuan'] ?>')"><i class="bi bi-check2"></i> Setujui</button>
                            <button class="btn btn-danger btn-sm" onclick="goSubmitValue('close','<?= $r['id_pemeriksaan_temuan'] ?>')"><i class="bi bi-x-lg"></i> Close</button>
                        <?php } else { ?>
                            <button class="btn btn-warning btn-sm" onclick="goSubmitValue('batal','<?= $r['id_pemeriksaan_temuan'] ?>')"><i class="bi bi-x-lg"></i> Batal</button>
                        <?php }
                    }
                    if ($rowheader['id_status'] == 5) { ?>
                        <a class="btn btn-sm btn-warning" href="<?= site_url("panelbackend/pemeriksaan_temuan/detail/$r[id_pemeriksaan]/$r[id_pemeriksaan_detail]/$r[id_pemeriksaan_temuan]") ?>">Tanggapan</a>
                    <?php }
                    // if (!($r['id_pemeriksaan_temuan'] && $id_bidang_pemeriksaan_anggota && $r['created_by'] <> $_SESSION[SESSION_APP]['user_id'])) {
                    //     echo UI::startMenu('inlist');
                    //     echo UI::getMenu('edit', $r['id_pemeriksaan_temuan'], '', '', false, "window.location='" . site_url("panelbackend/pemeriksaan_temuan/edit/$r[id_pemeriksaan]/" . $r['id_pemeriksaan_temuan']) . "'");
                    //     echo UI::getMenu('delete', $r['id_pemeriksaan_temuan'], $add, $class, false, "window.location='" . site_url("panelbackend/pemeriksaan_temuan/delete/$r[id_pemeriksaan]/" . $r['id_pemeriksaan_temuan']) . "'");
                    //     echo UI::closeMenu();
                    // }
                    ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>