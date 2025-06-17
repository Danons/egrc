<div class="container-fluid content expanded">
    <div class="row">
        <nav class="d-md-block bg-primary text-white sidebar collapse">

            <div class="container-area-decoration" style="background-image: url('<?php echo base_url() ?>assets/images/decor.png');">

            </div>

            <div class="position-sticky pt-3">
                <?php
                $page_ctrl = "panelbackend/pemeriksaan/operasional";
                $rowmenu = $this->auth->GetParentMenu($page_ctrl);
                $child_active = '';
                $pagetemp = null;
                if ($rowheader['jenis'])
                    $pagetemp = base_url("panelbackend/pemeriksaan/index/" . $rowheader['jenis']);
                ?>
                <h4 class="title-sidebar"><?= $rowmenu['label'] ?></h4>
                <?= $sidebarmenu = $this->auth->GetSideBar((int)$rowmenu['menu_id'], null, "<ul class=\"nav flex-column\">", $child_active, $pagetemp); ?>

                <br />
                <div class="icon-expand-minimize-sidebar">
                    <div>
                        <i class="material-icons">remove</i>
                    </div>
                </div>

            </div>

        </nav>

        <div class="overlay-sidebar-mobile">
            <div class="sidebar-mobile">
                <h4 class="title-sidebar"><?= $rowmenu['label'] ?></h4>
                <?= $sidebarmenu; ?>

                <br />
                <div class="icon-expand-minimize-sidebar">
                    <div>
                        <i class="material-icons">remove</i>
                    </div>
                </div>
            </div>
            <div class="overlay-sidebar-right"></div>
        </div>

        <main class="ms-sm-auto main-content">

            <?php
            if (!$broadcrum)
                $broadcrum = $rowheader['broadcrumscorecard'];
            if (($broadcrum)) {
                if (!$page_title) {
                    $sub_page_title = $page_title;
                    $page_title = $broadcrum[count($broadcrum) - 1]['label'];
                    unset($broadcrum[count($broadcrum) - 1]);
                }

                $broadcrum1 = array_merge(array(array('url' => site_url($page_ctrl), 'label' => '<span class="material-icons" style="font-size:19px !important;">home</span>')), $broadcrum);

                $broadcrum1[] = array('url' => null, 'label' => null);
                if ($broadcrum1) {
            ?>
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb" style="margin-bottom: 0;">
                                <?php
                                foreach ($broadcrum1 as $v) { ?>
                                    <li class="breadcrumb-item"><a href="<?= $v['url'] ?>"><?= $v['label'] ?></a></li>
                                <?php } ?>
                            </ol>
                        </nav>
                    </div>
            <?php }
            } ?>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center <?= ($broadcrum1 && count($broadcrum1) > 1 ? "pt-1" : "pt-3") ?> pb-2 mb-3 ">
                <div class="new-content-title">
                    <div class="icon-expand-minimize-sidebar icon-expanded">
                        <div>
                            <i class="material-icons">apps</i>
                        </div>
                    </div>

                    <h4 class="h4">
                        Audit <?= $jenis_title ?>
                    </h4>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button type="button" class="btn btn-sm btn-light" onclick="window.location='<?= site_url("panelbackend/pemeriksaan/index/" . $rowheader['jenis']) ?>';"><i class="bi bi-arrow-left"></i> Daftar Pemeriksaan</button>
                </div>
            </div>

            <?php if ($_SESSION[SESSION_APP]['loginas']) { ?>
                <div class="alert alert-warning">
                    Anda sedang mengakses user lain. <a href="<?= site_url("panelbackend/home/loginasback") ?>" class="alert-link">Kembali</a>.
                </div>
            <?php } ?>
            <div class="row">
                <div class="col-sm-12">

                    <?php
                    echo "<div class='row'><div class='col-sm-6'>";
                    $from = $unitarr[$rowheader['id_unit']];
                    echo UI::createFormGroup($from, $rules["id_unit"], "id_unit", "Nama Objek Pemeriksaan", true, 2);

                    if ($rowheader['jenis'] == 'penyuapan') {
                        echo "</div><div class='col-sm-6'>";
                        $from = $rowheader['objeklainnya'];
                        echo UI::createFormGroup($from, $rules["objeklainnya"], "objeklainnya", "Objek Pemeriksaan Lainnya", true, 2);
                        echo "</div></div>";
                        echo "<div class='row'><div class='col-sm-12'>";
                    } else {
                        echo "</div><div class='col-sm-6'>";
                    }

                    $from = $rowheader['nama'];
                    echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama Kegiatan / Program / Bidang", true, 2);
                    echo "</div></div>";

                    if ($row['jenis'] == 'eksternal') {
                        $from = $jeniseksternalarr[$row['id_jenis_audit_eksternal']];
                        echo UI::createFormGroup($from, $rules["id_jenis_audit_eksternal"], "id_jenis_audit_eksternal", "Jenis Audit Eksternal", true, 2);
                    }

                    echo "<div class='row'><div class='col-sm-6'>";
                    $from = $rowheader['lokasi'];
                    echo UI::createFormGroup($from, $rules["lokasi"], "lokasi", "Lokasi", true, 2);
                    echo "</div><div class='col-sm-6'>";

                    $from = Eng2Ind($rowheader['tgl_mulai']) . "&nbsp;s/d&nbsp;" . Eng2Ind($rowheader['tgl_selesai']);
                    echo UI::createFormGroup($from, $rules["tgl_mulai"], "tgl_mulai", "Periode Pemeriksaan", true, 2);
                    echo "</div></div>";

                    if ($rowheader['jenis'] == 'mutu' || $rowheader['jenis'] == 'penyuapan') {
                        echo "<div class='row'><div class='col-sm-6'>";
                        $from = $rowheader['nama_pereview'] . "<br/>" . $rowheader['nama_jabatan_pereview'];
                        echo UI::createFormGroup($from, $rules["nama_jabatan_pereview"], "nama_jabatan_pereview", "Ketua Tim Audit", true, 2);
                        echo "</div><div class='col-sm-6'>";

                        $from =  $rowheader['nama_penyusun'] . "<br/>" . $rowheader['nama_jabatan_penyusun'];
                        echo UI::createFormGroup($from, $rules["nama_jabatan_penyusun"], "nama_jabatan_penyusun", "Auditor", true, 2);
                        echo "</div></div>";
                    } else {
                        echo "<div class='row'><div class='col-sm-6'>";
                        $from = $rowheader['nama_pereview'] . "<br/>" . $rowheader['nama_jabatan_pereview'];
                        echo UI::createFormGroup($from, $rules["nama_jabatan_pereview"], "nama_jabatan_pereview", "Ketua Tim", true, 2);
                        echo "</div><div class='col-sm-6'>";

                        $from = $rowheader['nama_penyusun'] . "<br/>" . $rowheader['nama_jabatan_penyusun'];
                        echo UI::createFormGroup($from, $rules["nama_jabatan_penyusun"], "nama_jabatan_penyusun", "Pengawas", true, 2);
                        echo "</div></div>";

                        $from = "<table>";
                        foreach ($rowheader['pemeriksaan_tim'] as $r) {
                            $from .= "<tr><td>" . $r['nama'] . " - </td>";
                            $from .= "<td>" . $r['nama_jabatan'] . " - </td>";
                            $from .= "<td>" . $r['nama_bidang'] . "</td></tr>";
                        }
                        $from .= "</table>";
                        echo UI::createFormGroup($from, $rules['pemeriksaan_tim[]'], "pemeriksaan_tim[]", "Anggota Tim", true, 2);
                    }

                    $from = labelstatuspemeriksaan($rowheader['id_status']);
                    echo UI::createFormGroup($from, $rules["id_status"], "id_status", "Status", true, 2);

                    ?>
                </div>
            </div>
                    <hr />
            <div class="table-responsive row">
                <div class="col-sm-12">
                    <?= FlashMsg() ?>

                    <?= $content1 ?>

                    <br />
                </div>
            </div>
        </main>
    </div>
</div>