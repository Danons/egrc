<table class="table table-striped table-hover dataTable">
    <thead>
        <?= UI::showHeader($header, $filter_arr, $list_sort, $list_order) ?>
    </thead>
    <tbody>
        <?php
        $i = $page;
        foreach ($list['rows'] as $rows) {
            $i++;
            // dpr($header, 1);
            echo "<tr>";
            echo "<td>$i</td>";
            foreach ($header as $rows1) {
                $val = $rows[$rows1['name']];
                if ($rows1['name'] == 'name' or $rows1['name'] == 'code') {
                    echo "<td><a href='" . ($url = base_url($page_ctrl . "/detail/$rows[$pk]")) . "'>$val</a></td>";
                } elseif ($rows1['name'] == 'isi') {
                    echo "<td>" . ReadMore($val, $url) . "</td>";
                } elseif ($rows1['name'] == 'dokumen') {
                    echo "<td><a href='" . base_url('panelbackend/spi_audit_evaluasi/go_print/' . $rows['id_audit_evaluasi']) . "' class='btn btn-primary btn-sm'>Lihat Dokumen</a></td>";
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
    	" . UI::showMenuMode('catatan', $rows[$pk]) . "
    	</td>";
            echo "</tr>";
        }
        if (!count($list['rows'])) {
            echo "<tr><td colspan='" . (count($header) + 2) . "'>Data kosong</td></tr>";
        }
        ?>
    </tbody>
</table>
<?= UI::showPaging($paging, $page, $limit_arr, $limit, $list) ?>