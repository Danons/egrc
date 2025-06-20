<?php /*<?php if ($this->access_role['access_approve']) { ?>
    <div style="width:33%;float:left;" <?php if (!$tipe) { ?>class="info-box-flat-active" <?php } else { ?>class="info-box-flat" <?php } ?>>
        <div class="content">
            <div class="text">
                <a href="<?= site_url('panelbackend/risk_status_risk/index/0') ?>">
                    APPROVAL
                </a>
            </div>
        </div>
    </div>
    <div style="width:33%;float:left;" <?php if ($tipe == 1) { ?>class="info-box-flat-active" <?php } else { ?>class="info-box-flat" <?php } ?>>
        <div class="content">
            <div class="text">
                <a href="<?= site_url('panelbackend/risk_status_risk/index/1') ?>">
                    RISIKO INTERNAL
                </a>
            </div>
        </div>
    </div>
    <div style="width:33%;float:left;" <?php if ($tipe == 2) { ?>class="info-box-flat-active" <?php } else { ?>class="info-box-flat" <?php } ?>>
        <div class="content">
            <div class="text">
                <a href="<?= site_url('panelbackend/risk_status_risk/index/2') ?>">
                    RISIKO INTERDEPENDENT
                </a>
            </div>
        </div>
    </div>
<?php } else { ?>

    <div style="width:50%;float:left;" <?php if ($tipe == 1) { ?>class="info-box-flat-active" <?php } else { ?>class="info-box-flat" <?php } ?>>
        <div class="content">
            <div class="text">
                <a href="<?= site_url('panelbackend/risk_status_risk/index/1') ?>">
                    RISIKO INTERNAL
                </a>
            </div>
        </div>
    </div>
    <div style="width:50%;float:left;" <?php if ($tipe == 2) { ?>class="info-box-flat-active" <?php } else { ?>class="info-box-flat" <?php } ?>>
        <div class="content">
            <div class="text">
                <a href="<?= site_url('panelbackend/risk_status_risk/index/2') ?>">
                    RISIKO INTERDEPENDENT
                </a>
            </div>
        </div>
    </div>
<?php } ?>
<div style="clear: both;"></div>
<br />*/ ?>
<!-- Basic Table -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="body table-responsive">

            <?php if (($_SESSION[SESSION_APP]['loginas'])) { ?>
                <div class="alert alert-warning">
                    Anda sedang mengakses user lain. <a href="<?= site_url("panelbackend/home/loginasback") ?>" class="alert-link">Kembali</a>.
                </div>
            <?php } ?>

            <?= FlashMsg() ?>

            <table class="table table-hover dataTable">
                <thead>
                    <?= UI::showHeader($header, $filter_arr, $list_sort, $list_order, ($list['total'] > $limit || true)) ?>
                </thead>
                <tbody>
                    <?php
                    $i = $page;
                    foreach ($list['rows'] as $rows) {
                        $i++;
                        echo "<tr>";
                        echo "<td>$i</td>";
                        foreach ($header as $rows1) {
                            $val = $rows[$rows1['name']];
                            if ($rows1['name'] == 'nama') {
                                echo "<td><a href='" . ($url = site_url("panelbackend/risk_risiko/detail/{$rows['id_scorecard']}/$rows[$pk]")) . "'>$val</a></td>";
                            } elseif ($rows1['name'] == 'nama_mitigasi') {
                                echo "<td><a href='" . ($url = site_url("panelbackend/risk_mitigasi/edit/{$rows['id_risiko']}/{$rows['id_mitigasi']}")) . "'>$val</a></td>";
                            } elseif ($rows1['name'] == 'isi') {
                                echo "<td>" . ReadMore($val, $url) . "</td>";
                            } elseif ($rows1['name'] == 'id_status_pengajuan') {
                                echo "<td style='text-align:center;'>" . labelstatus($val) . "</td>";
                            } elseif ($rows1['name'] == 'status_konfirmasi') {
                                echo "<td style='text-align:center;'> " . labelkonfirmasi($val) . "</td>";
                            } elseif ($rows1['name'] == 'inheren' or $rows1['name'] == 'control' or $rows1['name'] == 'actual' or $rows1['name'] == 'risidual') {
                                echo labeltingkatrisiko($val);
                            } else {
                                switch ($rows1['type']) {
                                    case 'list':
                                        echo "<td style='text-align:center;'>" . $rows1["value"][$val] . "</td>";
                                        break;
                                    case 'number':
                                        echo "<td style='text-align:right'>$val</td>";
                                        break;
                                    case 'date':
                                        echo "<td>" . Eng2Ind($val, false) . "</td>";
                                        break;
                                    case 'datetime':
                                        echo "<td>" . Eng2Ind($val) . "</td>";
                                        break;
                                    default:
                                        echo "<td>$val</td>";
                                        break;
                                }
                            }
                        }
                        echo "<td></td>";
                        echo "</tr>";
                    }
                    if (!$list['rows']) {
                        echo "<tr><td colspan='" . (count($header) + 2) . "'>Data kosong</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <?php echo $list['total'] > $limit || true ? UI::showPaging($paging, $page, $limit_arr, $limit, $list) : null ?>

            <div style="clear: both;"></div>
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