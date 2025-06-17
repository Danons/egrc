<div class="container-fluid content">
    <div class="row">
        <?php //if (!$notab) { 
        ?>
        <nav class="d-md-block bg-light sidebar pt-3">
            <div class="title-sidebar">
                <div class="d-flex">
                    <b style="font-size: 16px; display:block">
                        <a style="text-decoration:none" href="<?= site_url('panelbackend/opp_peluang/index/' . $rowheader['id_scorecard']) ?>">
                            <?= $rowheader['nama'] ?>
                        </a>
                    </b>

                    <div class="dropdown ms-auto">
                        <a href="javascript:void(0)" class="dropdown" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="color:#0052cc;display:inline-block;">
                            <i class="bi bi-three-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php
                            if ($this->access_role['edit'] && Access('edit', 'panelbackend/opp_scorecard')) { ?>
                                <li><a href="<?= site_url('panelbackend/opp_scorecard/edit/' . (int)$rowheader['id_parent_scorecard'] . '/' . $rowheader['id_scorecard']) ?>" class="dropdown-item">Edit Kajian Peluang</a></li>
                            <?php } ?>
                            <?php
                            if (Access('delete', 'panelbackend/opp_scorecard')) { ?>
                                <li><a href="<?= site_url('panelbackend/opp_scorecard/delete/' . $rowheader['id_scorecard']) ?>" class="dropdown-item">Delete Kajian Peluang</a></li>
                            <?php } ?>
                            <?php /* if ($rowheader1['id_peluang']) { ?>
                                <li><a href="<?= site_url('panelbackend/opp_log_peluang/index/' . $rowheader1['id_peluang']) ?>" class="dropdown-item">Log History</a></li>
                                <li><a href="<?= site_url('panelbackend/opp_review/index/' . $rowheader1['id_peluang']) ?>" class="dropdown-item">Review / Diskusi</a></li>
                            <?php } ?>
                            <?php if ($rowheader1['id_peluang']) { ?>
                                <li><a target='_BLANK' href="<?= site_url('panelbackend/opp_peluang/log_history/' . $rowheader1['id_peluang']) ?>" class="dropdown-item">Arsip Peluang</a></li>
                            <?php } */ ?>
                            <?php if ($rowheader1['id_peluang']) { ?>
                                <li><a target='_BLANK' href="<?= site_url('panelbackend/opp_peluang/detail_peluang/' . $rowheader1['id_peluang']) ?>" class="dropdown-item">Detail Peluang</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>

                <small style="margin-top: 10px; display:block"><b>Owner : </b><br /><?= $ownerarr[$rowheader['owner']] ?></small>
                <small style="margin-top: 10px; display:block">
                    <?= UI::createStatusPengajuan('scorecard', $rowheader, $mode == 'index' && $page_ctrl == 'panelbackend/opp_peluang' && $this->access_role['add']); ?>
                </small>
                <?php if ($rowheader1['id_peluang']) { ?>
                    <small style="margin-top: 10px; display:block"><b>Peluang : </b><br /><?= $rowheader1['nama'] ?></small>
                <?php } ?>
            </div>
            <?= $this->auth->GetTabScorecardPeluang($rowheader['id_scorecard'], $rowheader1['id_peluang'], !in_array($rowheader['id_status_pengajuan'], [1, 2, 3, 4]), $notab); ?>
            <?php if ($rowheader1['id_peluang']) { ?>
                <div class="footer row">
                    <div class="col-sm-12">
                        <small>
                            <?= UI::createStatusPeluang($rowheader1['status_peluang']) ?>
                        </small>
                    </div>
                </div>
            <?php } ?>
            <br />
        </nav>
        <main class="ms-sm-auto main-content">
            <?php /*} else { ?>
                <main class="ms-sm-auto main-content" style="width: 100%;">
                    <?php }*/
            if (!$broadcrum)
                $broadcrum = $rowheader['broadcrumscorecard'];

            if (($broadcrum)) {
                if (!$page_title) {
                    $sub_page_title = $page_title;
                    $page_title = $broadcrum[count($broadcrum) - 1]['label'];
                    unset($broadcrum[count($broadcrum) - 1]);
                }

                $broadcrum1 = array_merge(array(array('url' => site_url("panelbackend/opp_scorecard"), 'label' => '<span class="material-icons" style="font-size:19px !important;">home</span>')), $broadcrum);

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
                                <li><?= $rowheader1['nama'] ?></li>
                            </ol>
                        </nav>
                    </div>
            <?php }
            } ?>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center <?= ($broadcrum1 && count($broadcrum1) > 1 ? "pt-1" : "pt-3") ?> pb-2 mb-3 ">
                <div>
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
                    $buttonMenu = UI::showButtonMode($mode, $row[$pk], $edited);
                    if ($buttonMenu) {
                        echo $buttonMenu;
                    }
                    ?>
                </div>
            </div>
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