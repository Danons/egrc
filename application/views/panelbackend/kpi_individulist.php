<table class="table table-striped table-hover dataTable">
    <thead>
        <?= UI::showHeader($header, $filter_arr, $list_sort, $list_order) ?>
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
                if ($rows1['name'] == 'name' or $rows1['name'] == 'code') {
                    echo "<td><a href='" . ($url = base_url($page_ctrl . "/detail/$rows[$pk]")) . "'>$val</a></td>";
                } elseif ($rows1['name'] == 'isi') {
                    echo "<td>" . ReadMore($val, $url) . "</td>";
                } elseif ($rows1['name'] == 'is_setuju_langsung') {
                    if ($val == 1) {
                        $color = 'bg-warning';
                    } elseif ($val == 2) {
                        $color = 'bg-success';
                    } elseif ($val == 3) {
                        $color = 'bg-danger';
                    }
                    // if()
                    if ($rows['status'] == 2 || $_SESSION['SESSION_APP_EGRC']['view_all']) {
                        echo "<td><div onClick='setuju(" . $rows['id_kpi'] . ",`_langsung`)' class='rounded text-center text-dark px-3 btn btn-sm " . $color . " ' >" . $rows1["value"][$val] . "</div></td>";
                    } else {
                        echo "<td><div class='rounded text-center text-dark px-3 btn btn-sm not-allowed " . $color . " ' style='cursor:not-allowed' >" . $rows1["value"][$val] . "</div></td>";
                    }
                } elseif ($rows1['name'] == 'is_setuju_tidak_langsung') {
                    if ($val == 1) {
                        $color = 'bg-warning';
                    } elseif ($val == 2) {
                        $color = 'bg-success';
                    } elseif ($val == 3) {
                        $color = 'bg-danger';
                    }
                    if ($rows['status'] == 1 || $_SESSION['SESSION_APP_EGRC']['view_all']) {
                        echo "<td><div onClick='setuju(" . $rows['id_kpi'] . ",`_tidak_langsung`)' class='rounded text-center text-dark px-3 btn btn-sm " . $color . " ' >" . $rows1["value"][$val] . "</div></td>";
                    } else {
                        echo "<td><div class='rounded text-center px-3 text-dark btn btn-sm not-allowed " . $color . " ' style='cursor:not-allowed'>" . $rows1["value"][$val] . "</div></td>";
                    }
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
    	" . UI::showMenuMode('inlist', $rows[$pk]) . "
    	</td>";
            echo "</tr>";
        }
        // if (!count($list['rows'])) {
        //     echo "<tr><td colspan='" . (count($header) + 2) . "'>Data kosong</td></tr>";
        // }
        // 
        ?>
    </tbody>
</table>
<style>
    .not-allowed {
        cursor: not-allowed;
    }
</style>
<script>
    const setuju = (id, param) => {


        document.getElementById('test').innerHTML = `<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pilih...</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            <?php
            echo UI::createSelect('is_setuju`+param+`[`+id+`]', $statusArr, $row['is_setuju`+param+`'], true, $class = 'form-control ', "style='width:100%;'");
            ?>

           
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onClick="goSubmit('set_value')">Save</button>
            </div>
        </div>
    </div>
</div>`
        const myModal = new bootstrap.Modal('#exampleModal');
        myModal.show();
    }
</script>
<div id="test"></div>

<?= UI::showPaging($paging, $page, $limit_arr, $limit, $list) ?>