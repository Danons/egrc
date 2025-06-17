<div class="container-fluid">
    <?php /*<div class="block-header">
        <h2>
<?=$page_title?>
<?php if($sub_page_title){ ?> <small><?=$sub_page_title?></small> <?php }?></h2>
    </div>*/ ?>
    <!-- Basic Table -->
    <?php
    $modeheader = $mode;
    if (!$editedheader) {
        $modeheader = 'detail';
    }
    $is_readmore_scorecard = false;
    if ($page_ctrl != 'panelbackend/risk_scorecard')
        $is_readmore_scorecard = true;
    ?>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <!-- 
                    <h1>
                        <?= $rowheader['nama'] ?>
                    </h1>
 -->
                    <div class="float-right" style="top: 17px;position: absolute;right: 70px;">

                        <?php if (!$notab) { ?>
                            &nbsp;&nbsp;&nbsp; <a href="<?= site_url('panelbackend/risk_risiko/index/' . $rowheader['id_scorecard']) ?>" class='btn  btn-sm btn-xs btn-default'><span class="bi bi-list"></span> List Risiko</a>
                        <?php } ?>
                    </div>
                    <small>
                        <ol class="breadcrumb no-padding" style="padding-left: 0px;font-size: 12px;margin-top: 0px;
    margin-bottom: 10px;">
                            <li>
                                <?php $id_kajian_risiko = $rowheader['id_kajian_risiko']; ?>
                                <a style="color: #999" href="<?= site_url("panelbackend/risk_scorecard/index/$id_kajian_risiko") ?>"><?= $mtjeniskajianrisikoarr[$rowheader['id_kajian_risiko']] ?></a>
                            </li>
                            <?php if (($rowheader['broadcrumscorecard'])) { ?>
                                <?php foreach ($rowheader['broadcrumscorecard'] as $k => $v) { ?>
                                    <li><a style="color: #999" href="<?= site_url("panelbackend/risk_scorecard/index/$id_kajian_risiko/$k") ?>"><?= $v ?></a></li>
                                <?php } ?>
                            <?php } ?>
                        </ol>
                    </small>
                    <small>
                        Owner : <?= $ownerarr[$rowheader['owner']] ?>
                    </small><br />
                    <small>
                        Scope : <?= $rowheader['scope'] ?>
                        <?php
                        if ((!$rowheader['scope'] or trim($rowheader['scope']) == '-') && $this->access_role['edit'] && Access('edit', 'panelbackend/risk_scorecard')) { ?>
                            <a href="<?= site_url('panelbackend/risk_scorecard/edit/' . $rowheader['id_kajian_risiko'] . '/' . $rowheader['id_scorecard']) ?>" class="  btn-sm btn btn-xs btn-default">Isi Scope</a>
                        <?php } ?>
                    </small>

                    <ul class="header-dropdown">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <?php
                                if ($this->access_role['edit'] && Access('edit', 'panelbackend/risk_scorecard')) { ?>
                                    <li><a href="<?= site_url('panelbackend/risk_scorecard/edit/' . $rowheader['id_kajian_risiko'] . '/' . $rowheader['id_scorecard']) ?>" class="  btn-sm waves-block">Edit Kajian Risiko</a></li>
                                <?php } ?>
                                <?php
                                if (Access('delete', 'panelbackend/risk_scorecard')) { ?>
                                    <li><a href="<?= site_url('panelbackend/risk_scorecard/delete/' . $rowheader['id_kajian_risiko'] . '/' . $rowheader['id_scorecard']) ?>" class="  btn-sm waves-block">Delete Kajian Risiko</a></li>
                                <?php } ?>
                                <?php if ($rowheader1['id_risiko']) { ?>
                                    <li><a href="<?= site_url('panelbackend/risk_log_risiko/index/' . $rowheader1['id_risiko']) ?>" class="  btn-sm waves-block">Log History</a></li>
                                    <li><a href="<?= site_url('panelbackend/risk_review/index/' . $rowheader1['id_risiko']) ?>" class="  btn-sm waves-block">Reviu / Diskusi</a></li>
                                <?php } ?>
                                <?php if ($rowheader1['id_risiko']) { ?>
                                    <li><a target='_BLANK' href="<?= site_url('panelbackend/risk_risiko/log_history/' . $rowheader1['id_risiko']) ?>" class="  btn-sm waves-block">Arsip Risiko</a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body" style="padding: 0px;">
                    <div id="wizard_horizontal" role="application" class="wizard clearfix">
                        <div class="steps clearfix">
                            <?= FlashMsg() ?>
                            <?php //if(!$notab){ 
                            ?>
                            <?= $this->auth->GetTabScorecard($mode, $rowheader['id_scorecard'], $rowheader1['id_risiko'], $rowheader1['is_finish'], $rowheader['id_nama_proses'], $rowheader['is_info'], $notab); ?>
                            <?php //} 
                            ?>
                        </div>
                    </div>
                </div>
                <?= $content1; ?>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    /* table.dataTable {
        clear: both;
        margin-top: -15px !important;
        margin-bottom: 6px !important;
        max-width: none !important;
    } */
</style>