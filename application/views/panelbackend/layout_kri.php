<div class="container-fluid content expanded">
    <div class="row">
        <nav class="d-md-block bg-primary text-white sidebar collapse">

            <div class="container-area-decoration" style="background-image: url('<?php echo base_url() ?>assets/images/decor.png');">

            </div>

            <div class="position-sticky pt-3">
                <?php

                $child_active = '';
                $pagetemp = '';
                if ($page_ctrl == 'panelbackend/risk_kri_hasil') {
                    $page_ctrl = "panelbackend/kri";
                    $pagetemp = base_url($page_ctrl);
                }

                $rowmenu = $this->auth->GetParentMenu($page_ctrl);
                ?>
                <h4 class="title-sidebar"><?= $rowmenu['label'] ?></h4>
                <?= $sidebarmenu = $this->auth->GetSideBar((int)$rowmenu['menu_id'], null, "<ul class=\"nav flex-column\">", $child_active, $pagetemp); ?>
                <br />
                <div class="icon-expand-minimize-sidebar" onclick="goToggle()">
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
                <div class="icon-expand-minimize-sidebar" onclick="goToggle()">
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
                        Target KRI Tahun <?= $rowheader['tahun'] ?>
                    </h4>
                </div>

                <div class="btn-toolbar mb-2 mb-md-0">
                    <button type="button" class="btn btn-sm btn-light" onclick="window.location='<?= site_url("panelbackend/kri/index") ?>';"><i class="bi bi-arrow-left"></i> Daftar Target KRI</button>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?php
                    $from = UI::createTextBox('namaunit', $rowheader['namaunit'], '200', '100', $editedheader, 'form-control ', "style='width:100%'");
                    echo UI::createFormGroup($from, $rules["namaunit"], "namaunit", "Nama Unit", false, 4);
                    ?>
                    <?php
                    $from = UI::createTextBox('namarisiko', $rowheader['namarisiko'], '200', '100', $editedheader, 'form-control ', "style='width:100%'");
                    echo UI::createFormGroup($from, $rules["namarisiko"], "namarisiko", "Risiko", false, 4);
                    ?>
                    <?php
                    $from = UI::createTextBox('nama', $rowheader['nama'], '200', '100', $editedheader, 'form-control ', "style='width:100%'");
                    echo UI::createFormGroup($from, $rules["nama"], "nama", "Indikator KRI", false, 4);
                    ?>
                    <?php
                    $from = UI::createTextBox('polaritas', $rowheader['polaritas'], '100', '100', $editedheader, 'form-control ', "");
                    echo UI::createFormGroup($from, $rules["polaritas"], "polaritas", "Polaritas", false, 4);
                    ?>
                </div>
                <div class="col-sm-6">
                    <?php
                    $from = UI::createTextBox('batas_bawah', $rowheader['batas_bawah'] . ($rowheader['batas_atas'] ? '-' . $rowheader['batas_atas'] : '') . ' ' . $rowheader['satuan'], '100', '100', $editedheader, 'form-control ', "");
                    echo UI::createFormGroup($from, $rules["batas_bawah"], "batas_bawah", "Threshold", false, 4);
                    ?>
                    <?php
                    $from = UI::createTextBox('target_mulai', $rowheader['target_mulai'] . ($rowheader['target_sampai'] ? '-' . $rowheader['target_sampai'] : '') . ' ' . $rowheader['satuan'], '100', '100', $editedheader, 'form-control ', "");
                    echo UI::createFormGroup($from, $rules["target_mulai"], "target_mulai", "Target", false, 4);
                    ?>
                    <?php
                    $from = UI::createTextBox('lastinput', ListBulan()[$rowheader['lastinput']], '100', '100', $editedheader, 'form-control ', "");
                    echo UI::createFormGroup($from, $rules["lastinput"], "lastinput", "Input Terakhir", false, 4);
                    ?>
                </div>
            </div>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center <?= ($broadcrum1 && count($broadcrum1) > 1 ? "pt-1" : "pt-3") ?> pb-2 mb-3 ">
                <div class="new-content-title">
                    <?php if ($page_title) { ?>
                        <h4 class="h4">
                            <?= $page_title ?>
                            <?php if ($sub_page_title) { ?><br /><small style="color: #6b778c; font-size: 14px; font-weight: 500;"><?= $sub_page_title ?></small> <?php } ?>
                        </h4>
                    <?php } ?>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <?php
                    $buttonMenu = "";
                    $buttonMenu = UI::showButtonMode($mode, $rowheader["id_pemeriksaan"]);
                    if ($buttonMenu) {
                        echo $buttonMenu;
                    }
                    ?>
                </div>
            </div>
            <div class="table-responsive row">
                <?php if ($_SESSION[SESSION_APP]['loginas']) { ?>
                    <div class="alert alert-warning">
                        Anda sedang mengakses user lain. <a href="<?= site_url("panelbackend/home/loginasback") ?>" class="alert-link">Kembali</a>.
                    </div>
                <?php } ?>

                <?= FlashMsg() ?>
                <div class="col-sm-12">
                    <?= $content1 ?>

                    <br />
                    <br />
                    <br />
                </div>
            </div>
        </main>
    </div>
</div>