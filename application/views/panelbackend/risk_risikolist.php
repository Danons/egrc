<div class="row table-responsive">
    <div class="col-sm-12">
        <table>
            <tr>
                <!-- <td style="background-color: #ddd;">&nbsp;<?= UI::createCheckBox("list_search_filter[is_draft]", 1, $filter_arr['is_draft'], "Draft") ?></td>
                <td style="background-color: #ddd;">&nbsp;<?= UI::createCheckBox("list_search_filter[is_risk]", 1, $filter_arr['is_draft'], "Risiko") ?></td>
                <td style="background-color: #ddd;">&nbsp;<?= UI::createCheckBox("list_search_filter[is_draft]", 1, $filter_arr['is_draft'], "Peluang") ?></td> -->
                <td style="background-color: #ddd;">&nbsp;<?= UI::createCheckBox("list_search_filter[is_draft]", 1, $filter_arr['is_draft'], "Draft") ?>&nbsp;&nbsp;</td>
                <!-- <td style="width: 150px;"><?= UI::createSelect("list_search_filter[is_opp_inherent]", ["" => "Risiko/Peluang", "1" => "Peluang", "-1" => "Risiko"], $filter_arr['is_opp_inherent']) ?></td> -->
                <!-- <td>&nbsp;&nbsp;&nbsp;<?= UI::createCheckBox("list_search_filter[is_signifikan]", 1, $filter_arr['is_signifikan'], "Risiko/Peluang Signifikan") ?></td> -->
            </tr>
        </table>
        <table class="table table-hover dataTable">
            <thead>
                <?= UI::showHeader($header, $filter_arr, $list_sort, $list_order, ($list['total'] > $limit || true)) ?>
            </thead>
            <tbody>
                <?php
                $i = $page;
                foreach ($list['rows'] as $rows) {
                    $i++;
                    if ($rows['is_lock'] == '1')
                        echo "<tr>";
                    else
                        echo "<tr style='background-color:#ddd'>";

                    echo "<td>$i</td>";
                    foreach ($header as $rows1) {
                        $val = $rows[$rows1['name']];
                        if ($rows1['name'] == 'dampak') {
                            echo "<td><a href='" . ($url = site_url("panelbackend/risk_risiko/detail/{$rows['id_scorecard']}/$rows[$pk]")) . "'>" . nl2br(strip_tags($val)) . "</a>";
                            echo labelverified($rows);
                            echo "</td>";
                        } elseif ($rows1['name'] == 'isi') {
                            echo "<td>" . ReadMore($val, $url) . "</td>";
                        } elseif ($rows1['name'] == 'id_status_pengajuan') {
                            echo "<td style='text-align:center'>" . labelstatus($val) . "</td>";
                        } elseif ($rows1['name'] == 'is_evaluasi_mitigasi') {
                            // echo "<td style='text-align:center'><a href='" . site_url("panelbackend/risk_monitoring_bulanan/detail/" . $rows['id_scorecard'] . "/" . $rows['id_risiko']) . "'>" . labelstatusevaluasi($val) . "</a></td>";
                            echo "<td style='text-align:center'><a href='" . site_url("panelbackend/risk_monitoring_bulanan/detail/" . $rows['id_risiko']) . "'>" . labelstatusevaluasi($val) . "</a></td>";
                        } elseif ($rows1['name'] == 'is_evaluasi_risiko') {
                            echo "<td style='text-align:center'><a href='" . site_url("panelbackend/risk_evaluasi/detail/" . $rows['id_risiko']) . "'>" . labelstatusevaluasi($val) . "</a></td>";
                        } elseif ($rows1['name'] == 'status_risiko') {
                            echo "<td style='text-align:center'>" . labelstatusrisiko($val) . "</td>";
                        } elseif ($rows1['name'] == 'inheren' or $rows1['name'] == 'control' or $rows1['name'] == 'actual' or $rows1['name'] == 'risidual') {
                            echo labeltingkatrisiko($val);
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
                                    echo "<td>" . nl2br(strip_tags($val)) . "</td>";
                                    break;
                            }
                        }
                    }

                    if (((accessbystatus($rowheader['id_status_pengajuan']) && !$rows['is_lock'] && $rows['id_scorecard'] == $this->data['rowheader']['id_scorecard']) or $this->access_role['view_all']) & $rows['status_risiko'] == '1') {
                        echo "<td style='text-align:right'>";
                        echo UI::showMenuMode('inlist', $rows[$pk]);
                        echo "</td>";
                    } else {
                        echo "<td></td>";
                    }
                    echo "</tr>";
                }
                if (!$list['rows']) {
                    echo "<tr><td colspan='" . (count($header) + 2) . "'>Data kosong</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <?php echo $list['total'] > $limit || true ? UI::showPaging($paging, $page, $limit_arr, $limit, $list) : null ?>
    </div>
</div>