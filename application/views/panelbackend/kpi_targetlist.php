<?php
/*
$edited = true;
$arr_elem =  array();
//$arr_elem[] = UI::createTextBox('tahun', $row['tahun'], '5', '5', $edited, $class = 'form-control ', "style='width:95px'");
$arr_elem[] = array('width' => '2', 'elem' => UI::createSelect('tahun', $tahunarr, $row['tahun'], $edited, $class = 'form-control ', 'width:100%'));
$arr_elem[] = array('width' => '2', 'elem' => UI::createSelect('id_unit', $unitarr, $row['id_unit'], $edited, $class = 'form-control '));
$arr_elem[] = array('width' => '2', 'elem' => UI::createSelect('id_kpi', $kpiarr, $row['id_kpi'], $edited, $class = 'form-control '));
$arr_elem[] = array('width' => '1', 'elem' => UI::createTextNumber('bobot', $row['bobot'], '1', '1', $edited, $class = 'form-control ', "style='text-align:right;width:70%' min='0' max='100' step='any'"));
$arr_elem[] = array('width' => '2', 'elem' => UI::createSelect('polarisasi', ["Maximize" => "Maximize", "Minimize" => "Minimize", "Stabilize" => "Stabilize"], $row['polarisasi'], $edited, $class = 'form-control '));
$arr_elem[] = array('width' => '1', 'elem' => UI::createTextNumber('target', $row['target'], '10', '10', $edited, $class = 'form-control ', "style='text-align:right; ' min='0' max='100' step='any'"));
$arr_elem[] = array('width' => '1', 'elem' => UI::createTextBox('satuan', $row['satuan'], '225', '100', $edited, $class = 'form-control '));
//$arr_elem[] = '<button type="button" class="btn btn-sm btn-primary" onclick="goSave()" style="width:100px" >Simpan</button>';

$arr_label = array('Pilih Tahun', 'Pilih Unit Kerja', 'Pilih Item KPI', 'Bobot', 'Polaritas', 'Target', 'Satuan');
*/
?>

<div class="row mb-3">
    <div class="col-auto me-auto d-flex">
        <?= UI::createSelect("list_search_filter[id_unit]", $unitarr, $filter_arr["id_unit"], true, 'form-control', "style='max-width:1000px;display:inline;'") ?>
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
<table class="table table-hover dataTable treetable table-bordered">
    <thead>
        <tr>
            <th rowspan="2" style="width: 150px;">No</th>
            <th rowspan="2">Indikator KPI</th>
            <th rowspan="2">Bobot</th>
            <th rowspan="2">Polaritas</th>
            <th rowspan="2">Target</th>
            <th rowspan="2">Satuan</th>
            <th colspan="3">Realisasi</th>
            <th rowspan="2">Input Terakhir</th>
            <th rowspan="2"></th>
        </tr>
        <tr>
            <th>Nilai</th>
            <th>%</th>
            <th>Bobot</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $id_unit = null;
        $totalbobot = 0;
        $totalbobotrealisasi = 0;
        $no = 0;
        foreach ($rows as $row) {
            if ($id_unit && $id_unit <> $row['id_unit']) {
                $no = 0;
        ?>

                <tr>
                    <td colspan="2"><b>Total</b></td>
                    <td style="text-align: right;"><b><?= $totalbobot ?></b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align: right;"><b><?= $totalbobotrealisasi ?></b></td>
                    <td></td>
                    <td></td>
                </tr>

        <?php
                $totalbobot = 0;
                $totalbobotrealisasi = 0;
            }

            $id_unit = $row['id_unit'];
            $i++;
            echo "<tr data-tt-id='" . $row['id_kpi'] . "' data-tt-parent-id='" . $row['id_parent'] . "'>";
            if ($row['isfolder']) {
                echo "<td colspan='11'><span class=\"folder\"><b>$row[nama]</b></span></td>";
                echo "</tr>";
                continue;
            } else {
                $no++;
                echo "<td><span class=\"file\">$no</span></td>";
            }

            foreach ($header as $row1) {
                $val = $row[$row1['name']];
                if ($row1['name'] == 'nama') {
                    echo "<td><a href='" . ($url = base_url($page_ctrl . "/detail/$row[$pk]")) . "'>$val</a></td>";
                } else {
                    switch ($row1['type']) {
                        case 'list':
                            echo "<td style='text-align:center'><div class='badge bg-warning text-light'> " . $row1["value"][$val] . "</div></td>";
                            break;
                        case 'number':
                            echo "<td style='text-align:right'>" . ($val ? rupiah($val) : null) . "</td>";
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

            $totalbobot += $row['bobot'];
            $totalbobotrealisasi += (float)$row['realbobot'];
            if (!$row['isfolder']) {
                echo "<td style='text-align:right;width:1px'>
    	" . UI::showMenuMode('inlist', $row[$pk]) . "
    	</td>";
            }
            echo "</tr>";
        } ?>
        <?php
        if (!($rows)) {
            echo "<tr><td colspan='" . (count($header) + 2) . "'>Data kosong</td></tr>";
        } else { ?>
            <tr>
                <td colspan="2"><b>Total</b></td>
                <td style="text-align: right;"><b><?= $totalbobot ?></b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right;"><b><?= $totalbobotrealisasi ?></b></td>
                <td></td>
                <td></td>
            </tr>
        <?php }
        ?>
    </tbody>
</table>


<?php /*
<div class="row">
    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200 dark:border-dark-5">
        <h2 class="font-small text-base mr-auto">
            Tambah Target KPI Unit Kerja
        </h2>
    </div>
    <div>
        <div class="row col-md-12 col-xl-12 g-1" style="margin-bottom: 100px;">
            <?php
            foreach ($arr_elem as $idx => $elem) {
                echo '<div class="col-md-' . $elem['width'] . ' col-xl-' . $elem['width'] . ' col-sm-12">';
                echo '<div class="form-group">
                        <label for="example-label" class="mb-2">' . $arr_label[$idx] . '</label>
                        ' . $elem['elem'] . '
                    </div>
                </div>';
            } ?>
            <div class="col-md-1 col-xl-1">
                <div class="form-group">
                    <label for="example-label" class="mb-2">&nbsp;</label>
                    <button class="btn btn-success" type='button' onclick="goSaveInline()">Simpan</button>
                </div>
            </div>
        </div>

    </div>
</div>


<script>
    function goSaveInline() {
        $(".btn-save").attr("disabled", "disabled");
        $("#act").val('save');
        $("#main_form").submit();
    }
</script> */ ?>