<div style="display: flex;">
    <b>User&nbsp;Group&nbsp;:&nbsp;</b>
    <?php
    echo UI::createSelect('group_id', $grouparr, $row['group_id'], true, 'form-control', 'onchange="goSubmit(\'set_value\')"');
    ?>
    &nbsp;&nbsp;
    <?php if ($this->access_role['save']) { ?>
        <button type="button" class="btn-save btn btn-sm btn-success" onclick="goSave()"><span class="bi bi-upload"></span> Save</button>
        <script>
            function goSave() {
                $(".btn-save").attr("disabled", "disabled");
                $("#act").val('save');
                $("#main_form").submit();
            }
        </script>
    <?php } ?>
</div>
<table class="table table-hover">
    <thead>
        <?= UI::showHeaderCheck($header, $filter_arr, $list_sort, $list_order) ?>
    </thead>
    <tbody>
        <?php
        $i = $page;
        foreach ($list['rows'] as $rows) {
            $i++;
            echo "<tr>";
            echo "<td>";
            echo UI::createCheckBox("nik[$rows[$pk]]", 1, $row['nik'][$rows[$pk]], null, true, 'checkone', 'onclick="checkone(this)"');
            echo "</td>";
            foreach ($header as $rows1) {
                $val = $rows[$rows1['name']];
                if ($rows1['name'] == 'isi') {
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
            echo "<td style='text-align:right'></td>";
            echo "</tr>";
        }
        if (!count($list['rows'])) {
            echo "<tr><td colspan='" . (count($header) + 2) . "'>Data kosong</td></tr>";
        }
        ?>
    </tbody>
</table>

<script type="text/javascript">
    function checkAll(e) {
        if ($(e).is(":checked")) {
            $(".checkone").prop("checked", true);
        } else {
            $(".checkone").prop("checked", false);
        }
    }
</script>