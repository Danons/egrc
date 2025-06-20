<table class="table table-hover dataTable treetable">
    <thead>
        <?= UI::showHeader($header, $filter_arr, $list_sort, $list_order, true, false, false) ?>
    </thead>
    <tbody>
        <?php
        $i = $page;
        foreach ($list['rows'] as $rows) {
            $i++;
        ?>
            <tr data-tt-id='<?= $rows['id_checklist'] ?>' data-tt-parent-id='<?= $rows['id_checklist_parent'] ?>'>


            <?php
            foreach ($header as $rows1) {
                $val = $rows[$rows1['name']];
                if ($rows1['name'] == 'nama') {


                    if ($rows['level'] <> $rows['levelsdm'] && $rows['levelsdm']) {
                        $paddingleft = ($rows['levelsdm'] - $rows['level']) * 19;
                        echo "<td " . ($paddingleft ? "style='padding-left:calc(1em + " . $paddingleft . "px) !important'" : "") . "><a href='" . ($url = base_url($page_ctrl . "/detail/$rows[$pk]")) . "'>$val</a></td>";
                    } else {
                        echo "<td><a href='" . ($url = base_url($page_ctrl . "/detail/$rows[$pk]")) . "'>$val</a></td>";
                    }
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
            echo "<td style='text-align:left;width:1px' >";
            // echo $rows['level'];
            // echo "-";
            // echo $rows['levelsdm'];
            echo UI::showMenuMode('inlist', $rows[$pk]);
            echo "</td>";
            echo "</tr>";
        }
        if (!$list['rows']) {
            echo "<tr><td colspan='" . (count($header) + 2) . "'>Data kosong</td></tr>";
        }
            ?>
    </tbody>
</table>