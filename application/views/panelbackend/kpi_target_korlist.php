<script src="<?= base_url("assets/plugins/jquery-sparkline/jquery.sparkline.js"); ?>" type="text/javascript"></script>
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

<table class="table table-hover dataTable treetable table-bordered">
    <thead>
        <tr>
            <th rowspan="2" style="width: 150px;">No</th>
            <th rowspan="2">Indikator KPI</th>

            <!-- lama -->
            <!-- <th rowspan="2">Bobot</th>
            <th rowspan="2">Polaritas</th>
            <th rowspan="2">Target</th>
            <th rowspan="2">Satuan</th> -->
            <!-- lama end -->

            <!-- baru -->
            <th rowspan="2">Satuan</th>
            <th rowspan="2">Target</th>
            <th rowspan="2">Polaritas</th>
            <th rowspan="2">Bobot</th>
            <!-- baru end -->

            <th colspan="3">Realisasi</th>
            <th rowspan="2" style="text-align: center;">Grafik</th>
            <th rowspan="2">Input Terakhir</th>
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
            /*
            if ($id_unit && $id_unit <> $row['id_unit']) {
                $no = 0;
        ?>

                <tr>
                    <td><b>Total</b></td>
                    <td style="text-align: right;"><?= $totalbobot ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align: right;"><?= $totalbobotrealisasi ?></td>
                    <td></td>
                    <td></td>
                </tr>

        <?php
                $totalbobot = 0;
                $totalbobotrealisasi = 0;
            }
            */
            $i++;
            echo "<tr data-tt-id='" . $row['id_kpi'] . "' data-tt-parent-id='" . $row['id_parent'] . "'>";
            if ($row['isfolder']) {
                echo "<td colspan='10'><span class=\"folder\"><b>$row[nama]</b></span></td>";
                echo "</tr>";
                continue;
            } else {
                $no++;
                echo "<td><span class=\"file\">$no</span></td>";
            }

            // foreach ($header as $row1) {
            //     $val = $row[$row1['name']];
            //     if ($row1['name'] == 'nama') {
            //         echo "<td><a href='" . ($url = base_url($page_ctrl . "/detail/$row[$pk]")) . "'>$val</a></td>";
            //     } else if (($row1['name'] == 'target' || $row1['name'] == 'totrealisasi') && $row['satuan'] == 'Waktu') {
            //         echo "<td style='text-align:right'>" . ListBulan()[str_pad($row[$row1['name']], 2, '0', STR_PAD_LEFT)] . "</td>";
            //     } else {
            //         switch ($row1['type']) {
            //             case 'list':
            //                 echo "<td style='text-align:center'><div class='badge bg-warning text-light'> " . $row1["value"][$val] . "</div></td>";
            //                 break;
            //             case 'number':
            //                 echo "<td style='text-align:right'>" . rupiah($val) . "</td>";
            //                 break;
            //             case 'date':
            //                 echo "<td>" . Eng2Ind($val, false) . "</td>";
            //                 break;
            //             case 'datetime':
            //                 echo "<td>" . Eng2Ind($val) . "</td>";
            //                 break;
            //             default:
            //                 echo "<td>$val</td>";
            //                 break;
            //         }
            //     }
            // }

            echo "<td>
            <span class='d-inline-block' tabindex='0' data-bs-toggle='popover' data-bs-trigger='hover focus' data-bs-content='$row[title]'>
                <a id='nama' class='tip' href='" . ($url = base_url($page_ctrl . "/detail/$row[$pk]")) . "' >$row[nama]</a>
            </span>

            </td>";

            echo "<td>$row[satuan]</td>";
            if ($row['satuan'] == 'Waktu') {
                echo "<td style='text-align:right'>" . ListBulan()[str_pad($row['target'], 2, '0', STR_PAD_LEFT)] . "</td>";
            } else {
                echo "<td style='text-align:right'>" . rupiah($row['target']) . "</td>";
            }
            echo "<td>" . ucwords($row["polarisasi"]) . "</td>";
            echo "<td style='text-align:right'>" . ($row['bobot'] !== null ? rupiah($row['bobot']) : null) . "</td>";
            if ($row['satuan'] == 'Waktu') {
                echo "<td style='text-align:right'>" . ListBulan()[str_pad($row['totrealisasi'], 2, '0', STR_PAD_LEFT)] . "</td>";
            } else {
                echo "<td style='text-align:right'>" . rupiah($row['totrealisasi']) . "</td>";
            }
            echo "<td style='text-align:right'>" . ($row['prostarget'] !== null ? rupiah($row['prostarget']) : null) . "</td>";
            echo "<td style='text-align:right'>" . ($row['realbobot'] !== null ? rupiah($row['realbobot']) : 0) . "</td>";

            echo "<td style='text-align:center'>";
            if ($row['polarisasi'] == 'Minimize') { ?>
                <div style="font-size: 32px;text-align:right">
                    <?php
                    if ($row['satuan'] == 'Waktu') {
                        echo ListBulan()[str_pad($row['totrealisasi'], 2, '0', STR_PAD_LEFT)];
                    } else {
                        echo rupiah($row['totrealisasi']);
                    }
                    ?>
                </div>
                <?php } else { //dpr($row['jenis_realisasi']);
                if ($row['jenis_realisasi'] == 'progresif' && $row['satuan'] !== 'Waktu') {
                ?>
                    <div class="sparkline" data-type="line" target='<?= $row['target'] ?>' data-labels="<?= implode(",", $row['bulanarr']) ?>" data-spot-Radius="3" data-highlight-Spot-Color="#f39c12" data-highlight-Line-Color="#222" data-min-Spot-Color="#f56954" data-max-Spot-Color="#00a65a" data-spot-Color="#39CCCC" data-offset="90" data-width="220px" data-height="45px" data-line-Width='2' data-line-Color='#39CCCC' data-fill-Color='rgba(57, 204, 204, 0.08)'>
                        <?= implode(",", $row['realisasiarr']) ?>
                    </div>
                <?php

                } else if ($row['jenis_realisasi'] == 'progresif' && $row['satuan'] == 'Waktu') {
                ?>
                    <div class="sparkline" data-type="line" target='' data-labels="<?= implode(",", $row['bulanarr']) ?>" data-spot-Radius="3" data-highlight-Spot-Color="#f39c12" data-highlight-Line-Color="#222" data-min-Spot-Color="#f56954" data-max-Spot-Color="#00a65a" data-spot-Color="#39CCCC" data-offset="90" data-width="220px" data-height="45px" data-line-Width='2' data-line-Color='#39CCCC' data-fill-Color='rgba(57, 204, 204, 0.08)'>
                        <?= implode(",", $row['realisasiarr']) ?>
                    </div>
                <?php
                }
                if ($row['jenis_realisasi'] == 'akumulatif') {
                ?>
                    <div class="sparkline" data-type="bar" data-labels="<?= implode(",", $row['bulanarr']) ?>" data-width="220px" data-height="45px" data-bar-Width="13" data-bar-Spacing="5" data-bar-Color="#f39c12">
                        <?= implode(",", $row['realisasiarr']) ?>
                    </div>
                    <!-- <span id="normalline">
                        <canvas width="63" height="18" style="display: inline-block; width: 63px; height: 18px; vertical-align: top;"></canvas>
                    </span> -->
                <?php
                }
            }
            echo "</td>";

            echo "<td style='text-align:center'><div class='badge bg-warning text-light'> " . ListBulan()[$row['lastinput']] . "</div></td>";

            $totalbobot += $row['bobot'];
            $totalbobotrealisasi += (float)$row['realbobot'];

            if (!$row['isfolder']) {
                //         echo "<td style='text-align:right;width:1px'>
                // " . UI::showMenuMode('inlist', $row[$pk]) . "
                // </td>";

                ?>
                <td style="text-align:right;width:1px">
                    <div class="dropdown" style="display:inline">
                        <a href="javascript:void(0)" class="dropdown" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#0052cc;padding: 5px;line-height:1.5;display:inline-block;">
                            <i class="bi bi-three-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenu2">
                            <!-- <li><a href="javascript:void(0)" class=" dropdown-item " onclick='open_modal(<?= json_encode($data_modal[$row[$pk]]) ?>)'><i class="bi bi-plus"></i> Add New</a> </li> -->
                            <!-- <li><a href="javascript:void(0)" class=" dropdown-item " onclick='open_modal(<?= json_encode($rowheader[$row[$pk]]) ?>)'><i class="bi bi-plus"></i> Add Realisasi</a> </li> -->
                            <?php if (Access("add", "panelbackend/kpi_target_kor")) { ?>
                                <li><a href="javascript:void(0)" class=" dropdown-item " onclick="goEdit('<?= $row['id_kpi_ori'] ?>','<?= $row['tahun'] ?>','<?= $row['id_kpi_target'] ?>')"><i class="bi bi-pencil"></i> Edit</a> </li>
                            <?php } ?>
                            <!-- <li><a href="javascript:void(0)" class=" dropdown-item " onclick="goDelete('<?= $row[$pk] ?>')"><i class="bi bi-trash"></i> Delete</a> </li> -->
                        </ul>
                    </div>
                </td>
        <?php }
            echo "</tr>";

            $id_unit = $row['id_unit'];
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
            </tr>
        <?php }
        ?>
    </tbody>
</table>


<div class="modal fade" id="fileModal" data-backdrop='false' tabindex="-1" role="dialog" aria-labelledby="fileModalLabel">
    <div class="modal-dialog" style="min-width: 700px; width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="fileModalLabel">
                    <div id="nama"></div>
                </h4>
            </div>
            <div class="modal-body">
                <div id="menutemplate"></div>
                <br />
                <div class="col-sm-6">

                    <?php
                    $from = UI::createSelect('bulan', ListBulan(), null, true, 'form-control', "style='width:190px;'");
                    echo UI::createFormGroup($from, $rules["bulan"], "bulan", "Bulan");
                    ?>

                </div>
                <div class="col-sm-6">

                    <div id="angka">
                        <?php
                        $from = UI::createTextNumber('nilai1', null, '10', '10', true, 'form-control', "onchange='changeNilai()' style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
                        echo UI::createFormGroup($from, $rules["nilai"], "nilai", "Nilai");
                        ?>
                    </div>

                    <div id="waktu">
                        <?php
                        $from = UI::createSelect('nilai2', ["" => ""] + ListBulan(), null, true, 'form-control', "onchange='changeNilai()' style='width:190px;'");
                        echo UI::createFormGroup($from, $rules["nilai"], "nilai", "Nilai");
                        ?>
                    </div>

                    <input type="hidden" id="pres">
                    <div id="hidden">
                        <?php
                        echo UI::createTextNumber('id_kpi_target', null, '10', '10', true, 'form-control ', "")
                        ?>
                    </div>
                    <?php
                    $from = UI::createTextNumber('prosentase', null, '10', '10', true, 'form-control ', "/*onchange='changeProsentase()'*/ style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
                    echo UI::createFormGroup($from, $rules["prosentase"], "prosentase", "Prosentase");
                    ?>

                    <?php
                    // $from = UI::showButtonMode("save", null, true);
                    // echo UI::createFormGroup($from);
                    ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default tutup" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="goSubmit('save_realisasi')">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * menampilkan tooltip
     */
    // $('document').ready(function() {
    // $('#nama').ready(function() {
    //     // Tooltips
    //     $('.tip').each(function() {
    //         // console.log($('#' + $(this).data('tip')).html());
    //         $(this).tooltip({
    //             html: true,
    //             title: $('#' + $(this).data('tip')).html()
    //         });
    //     });
    // });
    /**
     * menampilkan tooltip end
     */

    $(".sparkline").each(function() {
        var $this = $(this);
        var target = $this.attr('target');
        var options = $this.data();
        if (target) {
            if (options.type == 'line') {
                options.normalRangeMin = 0;
                options.normalRangeMax = target;
                // options.normalRangeColor= '#7fff00'
            }
        }
        // console.log(options);
        // options.onmousemove = (event, datapoint) => {
        //     var svg = findClosest(event.target, "svg");
        //     var tooltip = svg.nextElementSibling;
        //     var date = (new Date(datapoint.date)).toUTCString().replace(/^.*?, (.*?) \d{2}:\d{2}:\d{2}.*?$/, "$1");

        //     tooltip.hidden = false;
        //     tooltip.textContent = `${date}: $${datapoint.value.toFixed(2)} USD`;
        //     tooltip.style.top = `${event.offsetY}px`;
        //     tooltip.style.left = `${event.offsetX + 20}px`;
        // }
        // options.onmouseout = () => {
        //     var svg = findClosest(event.target, "svg");
        //     var tooltip = svg.nextElementSibling;

        //     tooltip.hidden = true;
        // }

        $this.sparkline('html', options);
        // $this.sparkline('html', {
        //     type: 'line',
        //     width: '200px',
        //     height: '50px',
        //     fillColor: undefined,
        //     spotColor: undefined,
        //     highlightSpotColor: undefined,
        //     highlightLineColor: undefined,
        //     spotRadius: 3,
        //     normalRangeMin: 3,
        //     normalRangeMax: 7
        // });
    });
</script>
<script>
    function changeNilai() {
        $("#prosentase").val("");
    }

    function changeProsentase() {
        var prosentase = $("#prosentase").val();
        // var target = <?= $rowheader['target'] ?>;
        var target = $('#pres').val();
        var nilai = prosentase / 100 * target;
        $("#nilai").val(nilai);
    }

    function open_modal(data) {

        if (data.satuan !== 'Waktu') {
            // $('#waktu').remove();
            $('#waktu').hide();
            $('#angka').show();
        } else {
            // $('#angka').remove();
            $('#angka').hide();
            $('#waktu').show();
        }

        $('#fileModalLabel').html(data.nama);
        $("#fileModal").modal('show');
        $('.tutup').click(function() {
            $("#fileModal").modal('hide');
        })

        $('#pres').val(data.target);
        $('#hidden').hide();
        $('#id_kpi_target').val(data.id_kpi_target);
    }

    function goEdit(id, tahun, id_kpi_target) {
        window.location = '<?= base_url() . "panelbackend/kpi_target_kor/add/2/" ?>' + id + '/' + tahun + '/' + 0 + '/' + id_kpi_target;
    }

    function goDelete(id) {
        if (confirm("Apakah Anda yakin akan menghapus ?")) {
            window.location = '<?= base_url() . "panelbackend/kpi_target_kor/delete/" ?>' + id;
        }
    }
</script>

<script>
    /**
     * popover
     */


    //  seting element yang diizinkan pada popover
    var myDefaultAllowList = bootstrap.Popover.Default.allowList

    // To allow table elements
    myDefaultAllowList.table = ['style', 'border'];
    myDefaultAllowList.tr = [];
    myDefaultAllowList.td = [];
    myDefaultAllowList.th = [];
    myDefaultAllowList.div = [];
    myDefaultAllowList.tbody = [];
    myDefaultAllowList.thead = [];
    //  seting element yang diizinkan pada popover END


    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl, {
            html: true
        })
    })
    /**
     * popover end
     */
</script>
<style>
    .popover {
        /* max-width: 50%; */
        width: 100%;
    }
</style>