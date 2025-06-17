<div class="container-fluid content">
    <div class="row">

        <nav class="d-md-block bg-light sidebar pt-3">
            <div class="title-sidebar">
                <div class="d-flex">
                    <b style="font-size: 16px; display:block">
                        <a style="text-decoration:none" href="<?= site_url('panelbackend/pemeriksaan_temuan/index/' . $rowheader['id_pemeriksaan']) ?>">
                            <?= $rowheader['nama_unit'] ?>
                        </a>
                    </b>

                    <?php
                    if (Access('edit', 'panelbackend/pemeriksaan') || Access('delete', 'panelbackend/pemeriksaan')) { ?>
                        <div class="dropdown ms-auto">
                            <a href="javascript:void(0)" class="dropdown" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="color:#0052cc;display:inline-block;">
                                <i class="bi bi-three-dots-vertical"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php
                                if (Access('edit', 'panelbackend/pemeriksaan')) { ?>
                                    <li><a href="<?= site_url('panelbackend/pemeriksaan/edit/' . $rowheader['jenis'] . '/' . $rowheader['id_pemeriksaan']) ?>" class="dropdown-item">Edit Pemeriksaan</a></li>
                                <?php }
                                if (Access('delete', 'panelbackend/pemeriksaan')) { ?>
                                    <li><a href="<?= site_url('panelbackend/pemeriksaan/delete/' . $rowheader['jenis'] . '/' .  $rowheader['id_pemeriksaan']) ?>" class="dropdown-item">Delete Pemeriksaan</a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <?php if ($rowheader['jenis'] != 'eksternal') { ?>
                <?= $this->auth->GetPemeriksaan(); ?>
            <?php } ?>
            <br />
            <?php
            echo "<div class='d-flex'>" . labelstatuspemeriksaan($rowheader['id_status']) . ($listtask ? " <a data-bs-toggle='tooltip' data-bs-original-title='Riwayat pengajuan' class='no-underline ms-auto' href='javascript:void(0);' onclick='$(\"#kettask\").toggle(100)'><i class='bi bi-clock-history'></i></a>" : null) . "</div>";
            if ($listtask) {
                echo "<div><div id='kettask' style='display:none; padding-top:0px;'>";
                foreach ($listtask as $r) {
                    $statusarr[$r['id_status_pengajuan']] = str_replace("Di", "Me", $statusarr[$r['id_status_pengajuan']]);

                    echo "<b>" . ucwords(strtolower($r['nama_user'])) . " (" . ucwords(strtolower($r['nama_group'])) . ")</b><br/>" . $statusarr[$r['id_status_pengajuan']] . "<br/><i>" . $r['deskripsi'] . "</i> <br/><span style='font-size:10px;color:#777'>" . $r['created_date'] . "</span><hr style='margin:5px 0px;'/>";
                }
                echo "</div></div>";
            }
            ?>
            <br />
            <?php
            if ($jumlahtemuan) {
                #posisi ketua
                if ($is_allowajukanpengawas) {
                    if (!($rowheader['jenis'] == 'mutu' || $rowheader['jenis'] == 'penyuapan')) { ?>
                        <a class='btn btn-warning btn-sm' href="javascript:void(0)" onclick="$('#idkey').val(3); $('#modalajuan').modal('show')">
                            Ajukan ke Pengendali Teknis <i class="bi bi-arrow-right"></i>
                        </a>
                    <?php } else { ?>
                        <a class='btn btn-warning btn-sm' href="javascript:void(0)" onclick="$('#idkey').val(4); $('#modalajuan').modal('show')">
                            Ajukan ke Koordinator <i class="bi bi-arrow-right"></i>
                        </a>
                    <?php }
                }
                #posisi pengawas
                else if ($is_allowajukanpenanggungjawab) { ?>
                    <a class='btn btn-danger btn-sm' href="javascript:void(0)" onclick="$('#idkey').val(2); $('#modalajuan').modal('show')">
                        <i class="bi bi-arrow-left"></i> Kembalikan ke Ketua Tim Audit
                    </a>

                    <?php if ($rowheader['jenis'] !== 'khusus') { ?>
                        <a class='btn btn-warning btn-sm' href="javascript:void(0)" onclick="$('#idkey').val(4); $('#modalajuan').modal('show')">
                            Ajukan ke Koordinator <i class="bi bi-arrow-right"></i>
                        </a>
                    <?php } else { ?>
                        <a class='btn btn-warning btn-sm' href="javascript:void(0)" onclick="$('#idkey').val(5); $('#modalajuan').modal('show')">
                            Ajukan ke Koordinator <i class="bi bi-arrow-right"></i>
                        </a>
                    <?php }
                }
                #posisi penanggung jawab
                else if ($is_allowkonfirmasiauditee) {
                    if (!($rowheader['jenis'] == 'mutu' || $rowheader['jenis'] == 'penyuapan')) { ?>
                        <a class='btn btn-danger btn-sm' href="javascript:void(0)" onclick="$('#idkey').val(3); $('#modalajuan').modal('show')">
                            <i class="bi bi-arrow-left"></i> Kembalikan ke Pengendali Teknis
                        </a>
                    <?php } else { ?>
                        <a class='btn btn-danger btn-sm' href="javascript:void(0)" onclick="$('#idkey').val(2); $('#modalajuan').modal('show')">
                            <i class="bi bi-arrow-left"></i> Kembalikan ke Ketua Tim Audit
                        </a>
                    <?php } ?>
                    <a class='btn btn-warning btn-sm' href="javascript:void(0)" onclick="$('#idkey').val(5); $('#modalajuan').modal('show')">
                        Tanggapan Auditee <i class="bi bi-arrow-right"></i>
                    </a>
                    <?php }
                #posisi auditee
                else if ($is_allowtindaklanjut) {
                    if ($rowheader['jenis'] !== 'khusus') { ?>
                        <!-- <a class='btn btn-danger btn-sm' href="javascript:void(0)" onclick="$('#idkey').val(2); $('#modalajuan').modal('show')">
                                    <i class="bi bi-arrow-left"></i> Kembalikan ke Ketua Tim Audit
                                </a> -->
                    <?php } else { ?>
                        <!-- <a class='btn btn-danger btn-sm' href="javascript:void(0)" onclick="$('#idkey').val(3); $('#modalajuan').modal('show')">
                                    <i class="bi bi-arrow-left"></i> Kembalikan ke Koordinator
                                </a> -->
                    <?php } ?>
                    <a class='btn btn-danger btn-sm' href="javascript:void(0)" onclick="$('#idkey').val(2); $('#modalajuan').modal('show')">
                        <i class="bi bi-arrow-left"></i> Kembalikan ke Ketua Tim Audit
                    </a>

                    <a class='btn btn-success btn-sm' href="javascript:void(0)" onclick="$('#idkey').val(6); $('#modalajuan').modal('show')">
                        <i class="bi bi-check2"></i> Ditindaklanjuti
                    </a>
                <?php } ?>

                <div class="modal fade" id="modalajuan" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Keterangan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <textarea class="form-control" name="keteranganajuan" id="keteranganajuan" placeholder="Keterangan..."></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary btn-sm" onclick="if(confirm('Pastikan semua data sudah benar !')){goSubmit('kirimajuan')}">KIRIM</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </nav>
        <main class="ms-sm-auto main-content">

            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="margin-bottom: 0;">
                        <li class="breadcrumb-item"><a href="<?= site_url() ?>"><span class="material-icons" style="font-size:19px !important;">home</span></a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url("panelbackend/pemeriksaan/index/" . $rowheader['jenis']) ?>">Audit <?= $jenis_title ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url("panelbackend/pemeriksaan_temuan/index/" . $rowheader['id_pemeriksaan']) ?>"><?= $rowheader['nama'] ?></a></li>
                        <li class="breadcrumb-item"><?= $page_title ?></li>

                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-sm-6">

                    <?php
                    if ($rowheader['jenis'] != 'eksternal') {
                        $spnarr[$rowheader['id_spn']] = $rowheader['no_surat_tugas'];
                        $from = UI::createSelect('id_spn', $spnarr, $rowheader['id_spn'], false, 'form-control ', "style='width:100%'");
                        echo UI::createFormGroup($from, $rules["id_spn"], "id_spn", "Surat Tugas", false, 4);
                    }

                    // $from = UI::createTextBox('nomor_stp', $rowheader['nomor_stp'], '200', '100', false, 'form-control ', "style='width:100%'");
                    // echo UI::createFormGroup($from, $rules["nomor_stp"], "nomor_stp", "Nomor " . ($rowheader['jenis'] == 'eksternal' ? 'SPTME' : 'SPTP'), false, 4);

                    // $from = UI::createTextBox('tanggal_sptp', $rowheader['tanggal_sptp'], '10', '10', false, 'form-control datepicker', "style='width:100px; display:inline;'");
                    // echo UI::createFormGroup($from, $rules["tanggal_sptp"], "tanggal_sptp", "Tanggal " . ($rowheader['jenis'] == 'eksternal' ? 'SPTME' : 'SPTP'), false, 4);


                    // $from = UI::createSelect('id_unit', $unitarr, $rowheader['id_unit'], false, 'form-control ', "style='width:100%' onchange='goSubmit(\"set_value\")'");
                    $from = UI::createTextBox('nama_unit', $rowheader['nama_unit'], '200', '100', false, 'form-control ', "style='width:100%'");
                    echo UI::createFormGroup($from, $rules["id_unit"], "id_unit", "Nama Objek Audit", false, 4);

                    if ($rowheader['jenis'] == 'penyuapan') {
                        $from = UI::createTextBox('objeklainnya', $rowheader['objeklainnya'], '200', '100', false, 'form-control ', "style='width:100%'");
                        echo UI::createFormGroup($from, $rules["objeklainnya"], "objeklainnya", "Objek Audit Lainnya", false, 4);
                    }

                    if ($rowheader['jenis'] == 'khusus') {
                        if (!$userarr[$rowheader['user_id']]) {
                            $userarr[$rowheader['user_id']] = $rowheader['nama_user'];
                        }
                        $from = UI::createSelect('user_id', $userarr, $rowheader['user_id'], false, 'form-control ', "style='width:100%' onchange='goSubmit(\"set_value\")'");
                        echo UI::createFormGroup($from, $rules["user_id"], "user_id", "Nama Orang", false, 4);
                    }
                    ?>

                    <?php
                    // dpr($row, 1);
                    // $from = UI::createSelect('id_subbid', $subbidarr, $rowheader['id_subbid'], false, 'form-control ', "style='width:100%'");
                    // echo UI::createFormGroup($from, $rules["id_subbid"], "id_subbid", "Bidang", false, 4);

                    $from = UI::createTextBox('nama', $rowheader['nama'], '200', '100', false, 'form-control ', "style='width:100%'");
                    echo UI::createFormGroup($from, $rules["nama"], "nama", "Kegiatan Yang Di Audit", false, 4);

                    if ($rowheader['jenis'] == 'eksternal') {
                        $from = UI::createSelect('id_jenis_audit_eksternal', $jeniseksternalarr, $rowheader['id_jenis_audit_eksternal'], false, 'form-control ', "style='width:100%'");
                        echo UI::createFormGroup($from, $rules["id_jenis_audit_eksternal"], "id_jenis_audit_eksternal", "Jenis Audit Eksternal", false, 4);
                    }
                    ?>

                    <?php

                    $from = UI::createTextArea('lokasi', $rowheader['lokasi'], '', '', false, 'form-control ', "");
                    echo UI::createFormGroup($from, $rules["lokasi"], "lokasi", "Lokasi", false, 4);
                    ?>

                    <?php
                    $from = UI::createTextBox('tgl_mulai', $rowheader['tgl_mulai'], '10', '10', false, 'form-control datepicker', "style='width:100px; display:inline;'");
                    $from .= "&nbsp;s/d&nbsp;" . UI::createTextBox('tgl_selesai', $rowheader['tgl_selesai'], '10', '10', false, 'form-control datepicker', "style='width:100px; display:inline;'");
                    echo UI::createFormGroup($from, $rules["tgl_mulai"], "tgl_mulai", "Periode Audit", false, 4);
                    ?>
                </div>
                <div class="col-sm-6">

                    <?php
                    $from = UI::createTextArea('tujuan', $rowheader['tujuan'], '', '', false, 'form-control ', "");
                    echo UI::createFormGroup($from, $rules["tujuan"], "tujuan", "Tujuan", false, 4);

                    $from = UI::createTextArea('keterangan', $rowheader['keterangan'], '', '', false, 'form-control ', "");
                    echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "Keterangan", false, 4);
                    ?>

                    <?php
                    /* if ($rowheader['jenis'] == 'mutu' || $rowheader['jenis'] == 'penyuapan') {
$from = UI::createTextBox('nama_jabatan_penyusun', $rowheader['nama_jabatan_penyusun'], '200', '100', false, 'form-control ', "style='width:100%'");
echo UI::createFormGroup($from, $rules["nama_jabatan_penyusun"], "nama_jabatan_penyusun", "Auditor", false, 4);

$from = UI::createTextBox('nama_jabatan_pereview', $rowheader['nama_jabatan_pereview'], '200', '100', false, 'form-control ', "style='width:100%'");
echo UI::createFormGroup($from, $rules["nama_jabatan_pereview"], "nama_jabatan_pereview", "Ketua Tim Audit", false, 4);
} else { */

                    if ($rowheader['jenis'] != 'eksternal') {
                        $from = UI::createSelect("id_penanggung_jawab", $penanggungjawabarr, $rowheader['id_penanggung_jawab'], false, 'form-control ', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
                        $from .= ' - ';
                        $from .= UI::createTextBox('nama_jabatan_penanggung_jawab', $rowheader['nama_jabatan_penanggung_jawab'], '200', '100', false, 'form-control space', "style='width:100%' readonly");
                        $from .= '/Ketua';
                        echo UI::createFormGroup($from, $rules["nama_jabatan_penanggung_jawab"], "nama_jabatan_penanggung_jawab", "Ketua", false, 4);

                        if (!$pelaksanaarr[$rowheader['id_pereview']])
                            $pelaksanaarr[$rowheader['id_pereview']] = $rowheader['nama_pereview'];

                        $from = UI::createSelect("id_pereview", $pelaksanaarr, $rowheader['id_pereview'], false, 'form-control ', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
                        $from .= ' - ';
                        $from .= UI::createTextBox('nama_jabatan_pereview', $rowheader['nama_jabatan_pereview'], '200', '100', false, 'form-control space', "style='width:100%;' readonly");
                        $from .= '/Pengendali Teknis';
                        echo UI::createFormGroup($from, $rules["nama_jabatan_pereview"], "nama_jabatan_pereview", "Pengendali Teknis", false, 4);

                        if (!($rowheader['jenis'] == 'penyuapan' || $rowheader['jenis'] == 'mutu')) {
                            if (!$pimpinanarr[$rowheader['id_penyusun']])
                                $pimpinanarr[$rowheader['id_penyusun']] = $rowheader['nama_penyusun'];

                            $from = UI::createSelect("id_penyusun", $pimpinanarr, $rowheader['id_penyusun'], false, 'form-control ', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
                            $from .= ' - ';
                            $from .= UI::createTextBox('nama_jabatan_penyusun', $rowheader['nama_jabatan_penyusun'], '200', '100', false, 'form-control space ', "style='width:100%' readonly");
                            $from .= '/Koordinator';
                            echo UI::createFormGroup($from, $rules["nama_jabatan_penyusun"], "nama_jabatan_penyusun", "Koordinator", false, 4);
                        }/*} */

                        /*
$no = 1;
$from = function ($val = null, false, $k = 0, $ci) {
    if (!$ci->data['pelaksanaarr'][$val['user_id']])
        $ci->data['pelaksanaarr'][$val['user_id']] = $val['nama'];

    $from = null;
    $from .= "<td>";
    $from .= UI::createSelect("pemeriksaan_tim[$k][user_id]", $ci->data['pelaksanaarr'], $val['user_id'], false, 'form-control ', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
    $from .= "</td>";
    $from .= "<td>";
    $from .= UI::createTextBox("pemeriksaan_tim[$k][nama_jabatan]", $val['nama_jabatan'], '', '', false, 'form-control', "readonly");
    if ($ci->data['bidangpemeriksaanarr']) {
        $from .= "</td>";
        $from .= "<td>";
        $from .= UI::createSelect("pemeriksaan_tim[$k][id_bidang_pemeriksaan]", $ci->data['bidangpemeriksaanarr'], $val['id_bidang_pemeriksaan'], false, 'form-control ', "style='width:100%;'");
    }
    if ($edited) {
        $from .= "</td>";
        $from .= UI::createTextHidden("pemeriksaan_tim[$k][id_pemeriksaan_tim]", $val['id_pemeriksaan_tim'], false);
        $from .= UI::createTextHidden("pemeriksaan_tim[$k][nama]", $val['nama'], false);
        $from .= UI::createTextHidden("pemeriksaan_tim[$k][id_jabatan]", $val['id_jabatan'], false);

        $from .= "<td style='position:relative; text-align:right'>";
    }

    return $from;
};

$from = "<table width='100%'>" . UI::AddFormTable('pemeriksaan_tim', $rowheader['pemeriksaan_tim'],  $from, false, $this) . "</table>";
echo UI::createFormGroup($from, $rules['pemeriksaan_tim[]'], "pemeriksaan_tim[]", "Auditor", false, 4);*/
                    }
                    ?>
                </div>
            </div>
            <?php if ($rowheader1['id_pemeriksaan_detail']) { ?>

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center <?= ($broadcrum1 && count($broadcrum1) > 1 ? "pt-1" : "pt-3") ?> pb-2 mb-3 ">

                    <h4 class="h4">Detail Pemeriksaan</h4>
                    <div class="btn-toolbar mb-2 mb-md-0">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <?php
                        $from = UI::createTextBox('uraian', $rowheader1['uraian'], '200', '100', false, $class = 'form-control ', "style='width:100%'");
                        echo UI::createFormGroup($from, $rules["uraian"], "uraian", "Uraian", false, 4);

                        $from = UI::createSelect("user_id", $pelaksanaarr, $rowheader1['user_id'], false, 'form-control ', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
                        $from .= UI::createTextBox('nama_jabatan', $rowheader1['nama_jabatan'], '200', '100', false, 'form-control ', "style='width:100%' readonly");
                        echo UI::createFormGroup($from, $rules["nama_jabatan"], "nama_jabatan", "Dilaksanakan Oleh", false, 4);

                        $from = UI::createSelect('id_kka', $kkaarr, $rowheader1['id_kka'], false, 'form-control ', "style='width:100%' onchange='goSubmit(\"set_value\")'");
                        echo UI::createFormGroup($from, $rules["id_kka"], "id_kka", "Nomor KKA", false, 4);
                        if (!false) {
                            $from = UI::createUploadMultiple("fileskka", $rowheader1['fileskka'], "panelbackend/mt_pemeriksaan_kka", false);
                            echo UI::createFormGroup($from, $rules["file"], "fileskka", "File KKA", false, 4);
                        }
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <?php
                        $from = UI::createTextBox('anggaran', $rowheader1['anggaran'], '200', '100', false, $class = 'form-control rupiah', "style='width:150px'");
                        echo UI::createFormGroup($from, $rules["anggaran"], "anggaran", "Anggaran", false, 4);

                        if (!false) {
                            $from = UI::createTextBox('realisasi', $rowheader1['realisasi'], '200', '100', false, $class = 'form-control rupiah', "style='width:150px'");
                            echo UI::createFormGroup($from, $rules["realisasi"], "realisasi", "Realisasi", false, 4);
                        }

                        $from = UI::createTextArea('detail_uraian', $rowheader1['detail_uraian'], '', '', false, $class = 'form-control contents', "");
                        echo UI::createFormGroup($from, $rules["detail_uraian"], "detail_uraian", "Keterangan", false, 4);

                        $from = UI::createUploadMultiple("files", $rowheader1['files'], "panelbackend/pemeriksaan_detail", false);
                        echo UI::createFormGroup($from, $rules["file"], "file", "File LHE", false, 4);
                        ?>
                    </div>
                </div>
            <?php } ?>
            <?php if ($page_ctrl != "panelbackend/pemeriksaan_temuan" && $page_ctrl != "panelbackend/pemeriksaan_tindak_lanjut") { ?>
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center <?= ($broadcrum1 && count($broadcrum1) > 1 ? "pt-1" : "pt-3") ?> pb-2 mb-3 ">

                    <?php
                    if ($page_title) { ?>
                        <h4 class="h4">
                            <?= $page_title ?>
                            <?php if ($sub_page_title) { ?><br /><small style="color: #6b778c; font-size: 14px; font-weight: 500;"><?= $sub_page_title ?></small> <?php } ?>
                        </h4>
                    <?php } ?>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <?php
                        if ($page_ctrl != "panelbackend/pemeriksaan_checklist") {
                            // $buttonMenu = "";
                            $buttonMenu .= UI::showButtonMode($mode, $row[$pk], $edited);
                            if ($buttonMenu) {
                                echo $buttonMenu;
                            }
                        }
                        ?>
                    </div>
                </div>
            <?php } ?>
            <div class="row">
                <?php if ($_SESSION[SESSION_APP]['loginas']) { ?>
                    <div class="alert alert-warning">
                        Anda sedang mengakses user lain. <a href="<?= site_url("panelbackend/home/loginasback") ?>" class="alert-link">Kembali</a>.
                    </div>
                <?php } ?>
                <?= FlashMsg() ?>
                <div class="col-sm-12">
                    <?= $content1 ?>
                </div>
            </div>
        </main>
    </div>
</div>
<style>

</style>