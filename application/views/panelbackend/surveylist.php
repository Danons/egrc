<div class="container-fluid">
    <?php if ($page_title) { ?>
        <div class="block-header">
            <div class="float-left">
                <h2>
                    <?= $page_title ?>
                </h2>
            </div>
            <div class="float-right">
                <?= UI::createSelect("list_search_filter[id_jenis_survey]", $mtjenissurveyarr, $filter_arr['id_jenis_survey'], true, 'form-control', "style='width:200px !important; display:inline' onchange='goSubmit(\"list_search\")'") ?>
                <?php if ($this->access_role['view_all_unit']) {
                    $mtsdmunitarr[''] = '-Unit-';
                ?>
                    <?= UI::createSelect("list_search_filter[id_unit]", $mtsdmunitarr, $filter_arr['id_unit'], true, 'form-control', "style='width:200px !important; display:inline' onchange='goSubmit(\"list_search\")'") ?>
                <?php } else { ?>
                    <h5 style="display: inline; color: #fff; padding: 0px 10px;"><?= $mtsdmunitarr[$_SESSION[SESSION_APP]['id_unit']] ?></h5>
                <?php } ?>
                <?= UI::createSelect("id_periode_tw_filter", $mtperiodetwarr, $id_periode_tw_filter, true, 'form-control', "style='width:120px !important; display:inline' onchange='goSubmit(\"set_filter\")'") ?>
                <?= UI::createTextNumber("tahun_filter", $tahun_filter, 4, 4, true, "form-control", "style='width:80px; display:inline; height: 30px !important;' onchange='goSubmit(\"set_filter\")'") ?>
                <?= UI::showButtonMode($mode, $row[$pk]) ?>
            </div>
            <div style="clear: both;"></div>
        </div>
    <?php } ?>
    <!-- Basic Table -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body table-responsive">

                    <?php if ($_SESSION[SESSION_APP]['loginas']) { ?>
                        <div class="alert alert-warning">
                            Anda sedang mengakses user lain. <a href="<?= site_url("panelbackend/home/loginasback") ?>" class="alert-link">Kembali</a>.
                        </div>
                    <?php } ?>

                    <?= FlashMsg() ?>

                    <table class="table table-hover dataTable">
                        <thead>
                            <?= UI::showHeader($header, $filter_arr, $list_sort, $list_order, ($list['total']>$limit || true)) ?>
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
                                        if ($add_param)
                                            echo "<td><a href='" . ($url = base_url($page_ctrl . "/detail/$add_param/$rows[$pk]")) . "'>$val</a></td>";
                                        else
                                            echo "<td><a href='" . ($url = base_url($page_ctrl . "/detail/$rows[$pk]")) . "'>$val</a></td>";
                                    } elseif ($rows1['name'] == 'isi') {
                                        echo "<td>" . ReadMore($val, $url) . "</td>";
                                    } else {
                                        switch ($rows1['type']) {
                                            case 'list':
                                                echo "<td>" . $rows1["value"][$val] . "</td>";
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
                                echo "<td style='text-align:right'>
    	" . ($id_periode_tw_current == $id_periode_tw_filter && $tahun_filter == date("Y") ? UI::showMenuMode('inlist', $rows[$pk]) : null) . "
    	</td>";
                                echo "</tr>";
                            }
                            if (!count($list['rows'])) {
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
    </div>
</div>