<div class="container-fluid content">
    <div class="row">
        <?php // if (!$notab) { 
        ?>
        <nav class="d-md-block bg-light sidebar pt-3">
            <div class="title-sidebar">
                <div class="d-flex">
                    <b style="font-size: 16px; display:block">
                        <a style="text-decoration:none" href="<?= site_url('panelbackend/risk_risiko/index/' . $rowheader['id_scorecard']) ?>">
                            <?= $rowheader['nama'] ?>
                        </a>
                    </b>

                    <div class="dropdown ms-auto">
                        <a href="javascript:void(0)" class="dropdown" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="color:#0052cc;display:inline-block;">
                            <i class="bi bi-three-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php
                            if ($this->access_role['edit'] && Access('edit', 'panelbackend/risk_scorecard')) { ?>
                                <li><a href="<?= site_url('panelbackend/risk_scorecard/edit/' . (int)$rowheader['id_parent_scorecard'] . '/' . $rowheader['id_scorecard']) ?>" class="dropdown-item">Edit Kajian Risiko</a></li>
                            <?php } ?>
                            <?php
                            if (Access('delete', 'panelbackend/risk_scorecard')) { ?>
                                <li><a href="<?= site_url('panelbackend/risk_scorecard/delete/' . $rowheader['id_scorecard']) ?>" class="dropdown-item">Delete Kajian Risiko</a></li>
                            <?php } ?>
                            <?php if ($rowheader1['id_risiko']) { ?>
                                <li><a href="<?= site_url('panelbackend/risk_log_risiko/index/' . $rowheader1['id_risiko']) ?>" class="dropdown-item">Log History</a></li>
                                <li><a href="<?= site_url('panelbackend/risk_review/index/' . $rowheader1['id_risiko']) ?>" class="dropdown-item">Reviu / Diskusi</a></li>
                            <?php } ?>
                            <?php if ($rowheader1['id_risiko']) { ?>
                                <li><a target='_BLANK' href="<?= site_url('panelbackend/risk_risiko/log_history/' . $rowheader1['id_risiko']) ?>" class="dropdown-item">Arsip Risiko</a></li>
                            <?php } ?>
                            <?php if ($rowheader1['id_risiko']) { ?>
                                <li><a target='_BLANK' href="<?= site_url('panelbackend/risk_risiko/detail_risiko/' . $rowheader1['id_risiko']) ?>" class="dropdown-item">Detail Risiko</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <?php
                // dpr($this->access_role);

                ?>
                <small style="margin-top: 10px; display:block"><b>
                        <?php if ($rowheader['id_unit'] == '1') {
                            echo "Penanggung Jawab :";
                        } else {
                            echo "Pemilik Risiko :";
                        } ?>
                    </b><br /><?= $ownerarr[$rowheader['owner']] ?></small>


                <?php if ($rowheader1['id_risiko']) { ?>
                    <small style="margin-top: 10px; display:block"><b>Risiko : </b><br /><?= $rowheader1['nama'] ?></small>
                <?php } ?>
            </div>

            <?= $this->auth->GetTabScorecard($rowheader['id_scorecard'], $rowheader1['id_risiko'], $rowheader1['is_lock'] && !in_array($rowheader['id_status_pengajuan'], [1, 2, 3, 4]), $notab); ?>

            <small style="margin-top: 10px; display:block">
                <?= UI::createStatusPengajuan('scorecard', $rowheader, $mode == 'index' && $page_ctrl == 'panelbackend/risk_risiko' && $this->access_role['add']); ?>
            </small>

            <?php
            if ($rowheader1['id_risiko']) { ?><div class="footer row">
                    <div class="col-sm-12" style="margin-top: 25px;">
                        <small>
                            <?= UI::createStatusRisiko($rowheader1['status_risiko']) ?>
                        </small>
                    </div>
                </div>
            <?php } else if ($rowheader['update_terakhir']['id_risiko']) {
                echo "<small><br/><a style='text-decoration:none' href='" . site_url("panelbackend/risk_review/index/" . $rowheader['update_terakhir']['id_risiko']) . "'>Update terakhir <br/>" . Eng2Ind($rowheader['update_terakhir']['activity_time']) . "</a></small>";
            } ?>
            <br />
            <br />
            <div>
                <?php
                if ($menyetujui) { ?>
                    <div class="d-flex align-items-center justify-content-between">
                        <b style="color: #58B051;">Disetujui Oleh</b>
                        <i class="material-icons" style="font-size: 24px !important; color: #58B051; margin-right: 0;">done</i>
                    </div>
                    <!-- <br> -->
                    <?= $menyetujui['nama'] ?><br>
                    <?= $menyetujui['jabatan'] ?><br>
                    pada: <?= $menyetujui['date'] ?><br>
                <?php }
                ?>
            </div>
        </nav>
        <main class="ms-sm-auto main-content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center <?= ($broadcrum1 && count($broadcrum1) > 1 ? "pt-1" : "pt-3") ?> pb-2 mb-3 ">
                <div>
                    <?php
                    if (!$broadcrum)
                        $broadcrum = $rowheader['broadcrumscorecard'];

                    if (($broadcrum)) {
                        if (!$page_title) {
                            $sub_page_title = $page_title;
                            $page_title = $broadcrum[count($broadcrum) - 1]['label'];
                            unset($broadcrum[count($broadcrum) - 1]);
                        }

                        $broadcrum1 = array_merge(array(array('url' => site_url("panelbackend/risk_scorecard"), 'label' => '<span class="material-icons" style="font-size:19px !important;">home</span>')), $broadcrum);

                        if ($rowheader1['nama'])
                            $broadcrum1[] = array('url' => null, 'label' => $rowheader1['nama']);
                        else
                            $broadcrum1[] = array('url' => null, 'label' => $page_title);
                    }

                    if ($page_title) { ?>
                        <h4 class="h4">
                            <?= $page_title ?>
                            <?php if ($sub_page_title) { ?><br /><small style="color: #6b778c; font-size: 14px; font-weight: 500;"><?= $sub_page_title ?></small> <?php } ?>
                        </h4>
                    <?php } ?>

                    <?php
                    if ($broadcrum1) {
                    ?>
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb" style="margin-bottom: 0;">
                                    <?php
                                    foreach ($broadcrum1 as $v) {
                                        if ($v['url']) { ?>
                                            <li class="breadcrumb-item"><a href="<?= $v['url'] ?>"><?= $v['label'] ?></a></li>
                                        <?php } else { ?>
                                            <li class="breadcrumb-item"><?= $v['label'] ?></li>
                                    <?php }
                                    } ?>
                                </ol>
                            </nav>
                        </div>
                    <?php }
                    ?>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <?php
                    // $buttonMenu = "";
                    $buttonMenu .= UI::showButtonMode($mode, $row[$pk], $edited);
                    if ($buttonMenu) {
                        echo $buttonMenu;
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">

                    <?php if ($_SESSION[SESSION_APP]['loginas']) { ?>
                        <div class="alert alert-warning">
                            Anda sedang mengakses user lain. <a href="<?= site_url("panelbackend/home/loginasback") ?>" class="alert-link">Kembali</a>.
                        </div>
                    <?php } ?>

                    <?= FlashMsg() ?>
                    <?php if ($rowheader1['id_risiko'] && $this->page_ctrl <> 'panelbackend/risk_risiko') { ?>
                        <!-- <small style="margin-bottom: 10px;display:block;background: #f8f9fa;padding: 10px;border-radius: 5px;margin-left: -10px;margin-right: -10px;">
                            <b>Sasaran/ Kegiatan/ Proses </b><br /><?= $rowheader1['sasaran_aktivitas'] ?>
                            <b>Risiko </b><br /><?= $rowheader1['nama'] ?>
                            <br /><br /><b>Penyebab </b><br /><?= $rowheader1['penyebabstr'] ?>
                            <br /><br /><b>Dampak </b><br /><?= $rowheader1['dampakstr'] ?>
                        </small> -->
                    <?php } ?>

                    <?= $content1 ?>
                </div>
            </div>
        </main>
    </div>
</div>