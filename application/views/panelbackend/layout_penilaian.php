<div class="container-fluid content">
    <div class="row">
        <nav class="d-md-block bg-light sidebar pt-3">
            <div class="title-sidebar">
                <div class="d-flex">
                    <b style="font-size: 16px; display:block">
                        <?= $rowheader1['nama'] ?>
                        <?php if ($rowheader1['target_lvl']) { ?>
                            <br /><small><b>Target Level <?= $rowheader1['target_lvl'] ?></b></small>
                        <?php } ?>
                    </b>

                    <div class="dropdown ms-auto">
                        <a href="javascript:void(0)" class="dropdown" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="color:#0052cc;display:inline-block;">
                            <i class="bi bi-three-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php
                            if ($this->access_role['edit'] && Access('edit', 'panelbackend/penilaian_session_' . $this->viewadd)) { ?>

                                <li><a href="<?= site_url('panelbackend/penilaian_session_' . $this->viewadd . '/edit/' . $rowheader1["id_kategori"] . "/" . $rowheader1['id_penilaian_session']) ?>" class="dropdown-item">Edit</a></li>
                            <?php } ?>
                            <?php
                            if (Access('delete', 'panelbackend/penilaian_session_' . $this->viewadd)) { ?>
                                <li><a href="<?= site_url('panelbackend/penilaian_session_' . $this->viewadd . '/delete/' . $rowheader1["id_kategori"] . "/" . $rowheader1['id_penilaian_session']) ?>" class="dropdown-item">Delete</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?= $this->auth->GetTabPenilaian($rowheader1['id_penilaian_session'], $rowheader['id_kategori_jenis']); ?>
        </nav>
        <main class="ms-sm-auto main-content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center <?= ($broadcrum1 && count($broadcrum1) > 1 ? "pt-1" : "pt-3") ?> pb-2 mb-3 ">
                <div>
                    <?php
                    if (($broadcrum)) {
                        if (!$page_title) {
                            $sub_page_title = $page_title;
                            $page_title = $broadcrum[count($broadcrum) - 1]['label'];
                            unset($broadcrum[count($broadcrum) - 1]);
                        }

                        $broadcrum1 = array_merge(array(array('url' => site_url("panelbackend/penilaian_session_" . $this->viewadd .  ($this->viewadd == 'gcg' ? "/index/" . $rowheader1['jenis_assessment_gcg'] : "")), 'label' => '<span class="material-icons" style="font-size:19px !important;">home</span>')), $broadcrum);

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