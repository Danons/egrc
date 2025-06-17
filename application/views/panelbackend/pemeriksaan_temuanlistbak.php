<?php /*if ($jenistemuanarr) { ?>
    <ul class="nav nav-tabs">
        <?php foreach ($jenistemuanarr as $k => $v) { ?>
            <?php if ($k == $_SESSION[SESSION_APP][$rowheader['jenis'] . 'jenis']) { ?>
                <li class="nav-item">
                    <a class="nav-link active" href="javascript:void(0)">
                        <?= $v ?> (<?= (int)$jumlahjenis[$k] ?>)
                    </a>
                </li>
            <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0)" onclick="$('#jenis_temuan').val('<?= $k ?>'); $('#jenis_temuan').change()">
                        <?= $v ?> (<?= (int)$jumlahjenis[$k] ?>)
                    </a>
                </li>
            <?php } ?>
        <?php } ?>
    </ul>
<?php } */ ?>
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
    <?php /*
    <div class="btn-toolbar mb-2 mb-md-0">
        <?php
        $is_anggota = false;
        foreach ($rowheader['pemeriksaan_tim'] as $r) {
            if ($r['user_id'] == $_SESSION[SESSION_APP]['user_id'])
                $is_anggota = true;
        }
        if ((Access("add", "panelbackend/pemeriksaan_temuan") && $is_anggota && in_array($rowheader['id_status'], [1, 2])) || Access("index", "panelbackend/loginas")) { ?>
            <a class='btn btn-primary btn-sm' href="<?= site_url("panelbackend/pemeriksaan_temuan/add/$rowheader[id_pemeriksaan]") ?>">Tambah <?= $_SESSION[SESSION_APP][$rowheader['jenis'] . 'jenis'] ?></a>
        <?php } ?>
        <?php if ($jumlahtemuan && $rowheader['jenis'] != 'eksternal') {
            if ($_SESSION[SESSION_APP][$rowheader['jenis'] . 'jenis'] == 'Catatan') { ?>
                <a class='btn btn-primary btn-sm' target="_BLANK" href="<?= site_url("panelbackend/pemeriksaan_temuan/go_print/dtp/$rowheader[id_pemeriksaan]/" . $_SESSION[SESSION_APP][$rowheader['jenis'] . 'jenis']) ?>"><i class="bi bi-printer"></i> Print DCP</a>
            <?php } else { ?>
                <a class='btn btn-primary btn-sm' target="_BLANK" href="<?= site_url("panelbackend/pemeriksaan_temuan/go_print/dtp/$rowheader[id_pemeriksaan]/" . $_SESSION[SESSION_APP][$rowheader['jenis'] . 'jenis']) ?>"><i class="bi bi-printer"></i> Print DTP</a>
        <?php }
        } ?>

        <?php if ($rowheader['id_status'] == 6) { ?>
            <!-- <a class='btn btn-primary btn-sm' target="_BLANK" href="<?= site_url("panelbackend/pemeriksaan_temuan/go_print/lhp/$rowheader[id_pemeriksaan]") ?>"><i class="bi bi-printer"></i> Print LHP</a> -->
        <?php } ?>
    </div> */ ?>
</div>


<?php $no = 1;
if ($listtemuan['rows'])
    foreach ($listtemuan['rows'] as $r) { ?>
    <div class="row ">
        <div class="col-sm-12" style="position: relative;">
            <div style="position:absolute; top:0px; right:0px">
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
                    <a class="btn btn-sm btn-warning" href="<?= site_url("panelbackend/pemeriksaan_temuan/detail/$r[id_pemeriksaan]/$r[id_pemeriksaan_temuan]") ?>">Tanggapan</a>
                <?php }
                if (!($r['id_pemeriksaan_temuan'] && $id_bidang_pemeriksaan_anggota && $r['created_by'] <> $_SESSION[SESSION_APP]['user_id'])) {
                    echo UI::startMenu('inlist');
                    echo UI::getMenu('edit', $r['id_pemeriksaan_temuan'], '', '', false, "window.location='" . site_url("panelbackend/pemeriksaan_temuan/edit/$r[id_pemeriksaan]/" . $r['id_pemeriksaan_temuan']) . "'");
                    echo UI::getMenu('delete', $r['id_pemeriksaan_temuan'], $add, $class, false, "window.location='" . site_url("panelbackend/pemeriksaan_temuan/delete/$r[id_pemeriksaan]/" . $r['id_pemeriksaan_temuan']) . "'");
                    echo UI::closeMenu();
                }
                ?>
            </div>
            <?php
            echo '<b style="width: 40%; display: block;" >' . $no . '. ' . '<a style="color: black; text-decoration: none;" href=' . site_url("panelbackend/pemeriksaan_temuan/detail/$r[id_pemeriksaan]/$r[id_pemeriksaan_temuan]") . '>' . $r['judul_temuan'] . '</a>' . '</b>' . ($r['detail_uraian'] ? '<br/>' : '') . $r['detail_uraian'];
            echo "<div style='clear:both'></div><br/>";
            if ($rowheader['jenis'] == 'mutu' || $rowheader['jenis'] == 'penyuapan') {
                echo UI::createFormGroup($r['klausul'], null, null, "Klausul", true, 4, false);
                echo UI::createFormGroup($r['referensi'], null, null, "Referensi", true, 4, false);
            }
            echo UI::createFormGroup($r['kondisi'] . $r['kondisi1'], null, null, "Kondisi", true, 4, false);
            echo UI::createFormGroup($r['kriteria'], null, null, "Kriteria", true, 4, false);
            if ($r['jenis_temuan'] == "MAJOR" || $r['jenis_temuan'] == "Temuan") {
                echo UI::createFormGroup($r['sebab'], null, null, "Sebab", true, 4, false);
                echo UI::createFormGroup($r['akibat'], null, null, "Akibat", true, 4, false);
                echo UI::createFormGroup($r['rekomendasi'], null, null, "Rekomendasi", true, 4, false);
            } else {
                echo UI::createFormGroup($r['rekomendasi'], null, null, "Saran", true, 4, false);
            }
            // echo UI::createFormGroup($r['status'], null, null, "Status", true, 4, false);
            ?>
        </div>
    </div>
<?php $no++;
    } ?>