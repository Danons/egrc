<script src="<?= base_url("assets/plugins/jquery-sparkline/jquery.sparkline.js"); ?>" type="text/javascript"></script>
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
        <?= UI::createTextBox("list_search[nama]", $filter_arr["nama"], 400, 400, true, 'form-control', "placeholder='Nama KPI...' style='max-width:500px;display:inline;'")
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
            <th rowspan="2" style="width: 130px;text-align: center;">No</th>
            <th rowspan="2" style="text-align: center;">Indikator KPI</th>
            <!-- lama -->
            <!-- <th rowspan="2">Bobot</th>
            <th rowspan="2">Polaritas</th>
            <th rowspan="2">Target</th>
            <th rowspan="2">Satuan</th> -->
            <!-- lama end -->

            <!-- baru -->
            <th rowspan="2" style="text-align: center;">Satuan</th>
            <th rowspan="2" style="text-align: center;">Target</th>
            <th rowspan="2" style="text-align: center;">Polaritas</th>
            <th rowspan="2" style="text-align: center;">Bobot</th>
            <!-- baru end -->

            <th colspan="3" style="text-align: center;">Realisasi</th>
            <th rowspan="2" style="text-align: center; width:75px">Input Terakhir</th>
            <th rowspan="2" style="text-align: center;"></th>
        </tr>
        <tr>
            <th style="text-align: center; width:75px">Nilai</th>
            <th style="text-align: center; width:75px">%</th>
            <th style="text-align: center; width:75px">Bobot</th>
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

            /*
            foreach ($header as $row1) {
                $val = $row[$row1['name']];
                if ($row1['name'] == 'nama') {
                    echo "<td><a href='" . ($url = base_url($page_ctrl . "/detail/$row[$pk]")) . "'>$val</a></td>";
                } else if (($row1['name'] == 'target' || $row1['name'] == 'totrealisasi') && $row['satuan'] == 'Waktu') {
                    echo "<td style='text-align:right'>" . ListBulan()[str_pad($row[$row1['name']], 2, '0', STR_PAD_LEFT)] . "</td>";
                } else {
                    switch ($row1['type']) {
                        case 'list':
                            echo "<td style='text-align:center'><div class='badge bg-warning text-light'> " . $row1["value"][$val] . "</div></td>";
                            break;
                        case 'number':
                            echo "<td style='text-align:right'>" . ($val !== null ? rupiah($val) : null) . "</td>";
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
            */

            echo "<td rowspan='2'><a href='" . ($url = base_url($page_ctrl . "/detail/$row[$pk]")) . "'>$row[nama]</a></td>";
            echo "<td rowspan='2'>$row[satuan]</td>";
            if ($row['satuan'] == 'Waktu') {
                echo "<td rowspan='2' style='text-align:right'>" . ListBulan()[str_pad($row['target'], 2, '0', STR_PAD_LEFT)] . "</td>";
            } else {
                echo "<td rowspan='2' style='text-align:right'>" . rupiah($row['target']) . "</td>";
            }
            echo "<td rowspan='2'>" . ucwords($row["polarisasi"]) . "</td>";
            echo "<td rowspan='2' style='text-align:right'>" . ($row['bobot'] !== null ? rupiah($row['bobot']) : null) . "</td>";
            if ($row['satuan'] == 'Waktu') {
                echo "<td style='text-align:right'>" . ListBulan()[str_pad($row['totrealisasi'], 2, '0', STR_PAD_LEFT)] . "</td>";
            } else {
                echo "<td style='text-align:right'>" . rupiah($row['totrealisasi']) . "</td>";
            }
            echo "<td style='text-align:right'>" . ($row['prostarget'] !== null ? rupiah($row['prostarget']) : null) . "</td>";
            echo "<td style='text-align:right'>" . ($row['realbobot'] !== null ? rupiah($row['realbobot']) : null) . "</td>";
            echo "<td style='text-align:center'><div class='badge bg-warning text-light'> " . ListBulan()[$row['lastinput']] . "</div></td>";

            $totalbobot += $row['bobot'];
            $totalbobotrealisasi += (float)$row['realbobot'];
            if (!$row['isfolder']) {
                //         echo "<td style='text-align:right;width:1px'>
                // " . UI::showMenuMode('inlist', $row[$pk]) . "
                // </td>";

            ?>
                <td style="text-align:right;width:1px" rowspan="2">
                    <div class="dropdown" style="display:inline">
                        <a href="javascript:void(0)" class="dropdown" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#0052cc;padding: 5px;line-height:1.5;display:inline-block;">
                            <i class="bi bi-three-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenu2">
                            <li><a href="javascript:void(0)" class=" dropdown-item " onclick='open_modal(<?= json_encode($data_modal[$row[$pk]]) ?>)'><i class="bi bi-plus"></i> Add New</a> </li>
                            <li><a href="javascript:void(0)" class=" dropdown-item " onclick="goEdit('<?= $row[$pk] ?>')"><i class="bi bi-pencil"></i> Edit</a> </li>
                            <li><a href="javascript:void(0)" class=" dropdown-item " onclick="goDelete('<?= $row[$pk] ?>')"><i class="bi bi-trash"></i> Delete</a> </li>
                        </ul>
                    </div>
                </td>
            <?php }
            echo "</tr>";
            echo "<tr data-tt-id='" . $row['id_kpi'] . "grafik' data-tt-parent-id='" . $row['id_parent'] . "'>";
            echo "<td></td>";
            echo "<td colspan='4' style='text-align:center'>";
            // dpr($row);
            if ($row['polarisasi'] == 'minimize') { ?>
                <div style="font-size: 32px;text-align:right">
                    <?php
                    if ($row['satuan'] == 'Waktu') {
                        echo ListBulan()[str_pad($row['totrealisasi'], 2, '0', STR_PAD_LEFT)];
                    } else {
                        echo rupiah($row['totrealisasi']);
                    }
                    ?>
                </div>
                <?php } else {
                if ($row['jenis_realisasi'] == 'progresif') {
                ?>
                    <div class="sparkline" data-type="line" data-spot-Radius="3" data-highlight-Spot-Color="#f39c12" data-highlight-Line-Color="#222" data-min-Spot-Color="#f56954" data-max-Spot-Color="#00a65a" data-spot-Color="#39CCCC" data-offset="90" data-width="300px" data-height="100px" data-line-Width='2' data-line-Color='#39CCCC' data-fill-Color='rgba(57, 204, 204, 0.08)'>
                        <?= implode(",", $row['realisasiarr']) ?>
                    </div>
                <?php
                }
                if ($row['jenis_realisasi'] == 'akumulatif') {
                ?>
                    <div class="sparkline" data-type="bar" data-width="97%" data-height="100px" data-bar-Width="14" data-bar-Spacing="7" data-bar-Color="#f39c12">
                        <?= implode(",", $row['realisasiarr']) ?>
                    </div>
        <?php
                }
            }
            echo "</td>";
            echo "</tr>";
        } ?>
        <?php
        if (!($rows)) {
            echo "<tr><td colspan='" . (count($header) + 2) . "'>Data kosong</td></tr>";
        } else { ?>
            <tr>
                <td colspan="2"><b>Total</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right;"><b><?= $totalbobot ?></b></td>
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
<div class="modal fade" id="fileModal" data-backdrop='false' tabindex="-1" role="dialog" aria-labelledby="fileModalLabel">
    <!-- <div class="modal-dialog" role="document"> -->
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h4 class="modal-title" id="fileModalLabel">
                    <div id="nama"></div>
                </h4>
            </div>
            <div class="modal-body">
                <div id="menutemplate"></div>
                <br />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default tutup" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="goSubmit('save_file')">Save changes</button>
            </div>
        </div>
    </div>
</div>

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
<script>
    $(".sparkline").each(function() {
        var $this = $(this);
        $this.sparkline('html', $this.data());
    });

    function open_modal(data) {
        $('#fileModalLabel').html(data.nama);
        $("#fileModal").modal('show');
        $('.tutup').click(function() {
            $("#fileModal").modal('hide');
        })
    }
</script>
<script>
    function goEdit(id) {
        window.location = "http://localhost/e-grc/panelbackend/kpi_target_unit/edit/" + id;
    }
</script>
<script>
    function goDelete(id) {
        if (confirm("Apakah Anda yakin akan menghapus ?")) {
            window.location = "http://localhost/e-grc/panelbackend/kpi_target_unit/delete/" + id;
        }
    }
</script>