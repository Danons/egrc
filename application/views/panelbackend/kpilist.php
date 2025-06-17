<div class="row mb-3">
    <div class="col-auto me-auto d-flex">
    </div>
    <div class="col-auto d-flex">
        <?= UI::createTextBox("list_search[nama]", $filter_arr["nama"], 400, 400, true, 'form-control', "placeholder='Nama KPI...' style='max-width:600px;display:inline;'")
        ?>
    </div>
</div>
<script>
    $(function() {
        $("#main_form").submit(function() {
            if ($("#act").val() == '') {
                goSearch();
            }
        });
    });

    function goSearch() {
        $("#act").val('list_search');
        $("#main_form").submit();
    }

    $("#main_form select[name^='list_search_filter'], #main_form input[name^='list_search']").not("#list_limit").change(function() {
        $("#main_form").submit();
    });
</script>
<table class="table table-hover dataTable treetable">
    <thead>
        <?= UI::showHeader($header, $filter_arr, $list_sort, $list_order, false, false, false) ?>
    </thead>
    <tbody>
        <?php
        foreach ($rows as $row) {
            $i++;
            echo "<tr data-tt-id='" . $row['id_kpi'] . "' data-tt-parent-id='" . $row['id_parent'] . "'>";
            foreach ($header as $row1) {

                if ($row['isfolder'] && in_array($row1['name'], ['is_bersama', 'is_direktorat', 'is_korporat', 'jenis_realisasi'],)) {
                    echo "<td></td>";
                } else {
                    $val = $row[$row1['name']];
                    if ($row1['name'] == 'kode') {
                        if ($row['isfolder'])
                            echo "<td><span class=\"folder\"><b>$val</b></span></td>";
                        else
                            echo "<td><span class=\"file\">$val</span></td>";
                    } else if ($row1['name'] == 'nama' or $row1['name'] == 'code') {
                        if ($row['isfolder'])
                            echo "<td><b><a href='" . ($url = base_url($page_ctrl . "/detail/$row[$pk]")) . "'>$val</a></b></td>";
                        else
                            echo "<td><a href='" . ($url = base_url($page_ctrl . "/detail/$row[$pk]")) . "'>$val</a></td>";
                    } elseif ($row1['name'] == 'isi') {
                        echo "<td>" . ReadMore($val, $url) . "</td>";
                    } else {
                        switch ($row1['type']) {
                            case 'list':
                                echo "<td>" . $row1["value"][$val] . "</td>";
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
            }
            echo "<td style='text-align:right'>
    	" . UI::showMenuMode('inlist', $row[$pk]) . "
    	</td>";
            echo "</tr>";
        }
        if (!count($rows)) {
            echo "<tr><td colspan='" . (count($header) + 2) . "'>Data kosong</td></tr>";
        }
        ?>
    </tbody>
</table>