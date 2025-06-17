<div class="row tr-kri-filter">
    <div class="col-auto d-flex">
        <?= UI::createTextNumber("tahun_filter", $tahun_filter, 4, 4, true, "form-control", "style='width:100px; display:inline' onchange='goSubmit(\"set_filter\")'") ?>
        &nbsp;
        <?php if ($view_all) {
            $mtsdmunitarr[''] = '-Unit-';
        ?>
            <?= UI::createSelect("id_unit_filter", $mtsdmunitarr, $id_unit_filter, true, 'form-control', "style='width:150px !important; display:inline' onchange='goSubmit(\"set_filter\")'") ?>
        <?php } else { ?>
            <h5 style="display: inline; color: #fff; padding: 0px 10px;"><?= $mtsdmunitarr[$_SESSION[SESSION_APP]['id_unit']] ?></h5>
        <?php } /* ?>

        &nbsp;<?= (count($scorecardarr) > 2 ? UI::createSelect('id_scorecard_filter', $scorecardarr, $id_scorecard_filter, true, 'form-control select2', "style='width:180px !important; display:inline' onchange='goSubmit(\"set_filter\")'") : null) ?>
        &nbsp;
        */ ?>
        <?= UI::createTextBox("nama_filter", $nama_filter, 400, 400, true, 'form-control', "placeholder='Nama Risiko...' style='max-width:300px;display:inline;' onchange='goSubmit(\"set_filter\")'")
        ?>
    </div>
    <?php
    // unset($tingkatArr[""]);
    // dpr($tingkatArr, 1)
    ?>
</div>
<div class="mt-2 d-flex justify-content-end" style="width: 100%;">
    <div class="ms-auto">
        &nbsp;<?= UI::createCheckBox('is_tinggi_filter', 1, $is_tinggi_filter, "Tinggi", true, null, 'onclick="goSubmit(\'set_filter\')"') ?>
        &nbsp;<?= UI::createCheckBox('is_menengah_tinggi_filter', 1, $is_menengah_tinggi_filter, "Menengah - Tinggi", true, null, 'onclick="goSubmit(\'set_filter\')"') ?>
        &nbsp;<?= UI::createCheckBox('is_menengah_filter', 1, $is_menengah_filter, "Menengah", true, null, 'onclick="goSubmit(\'set_filter\')"') ?>
        &nbsp;<?= UI::createCheckBox('is_menengah_rendah_filter', 1, $is_menengah_rendah_filter, "Menengah - Rendah", true, null, 'onclick="goSubmit(\'set_filter\')"') ?>
        &nbsp;<?= UI::createCheckBox('is_rendah_filter', 1, $is_rendah_filter, "Rendah", true, null, 'onclick="goSubmit(\'set_filter\')"') ?>
    </div>
</div>
<br />
<?php /*
<div class="row clearfix" style="position: relative;">
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <div class="card">
            <h5 class="card-header" style="text-align: center">
                Triwulan I
            </h5>
            <div class="card-body">
                <canvas id="myChart1"></canvas>
                <!-- <div id="donut_chart1" class="dashboard-donut-chart"></div> -->
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <div class="card">
            <h5 class="card-header" style="text-align: center">
                Triwulan II
            </h5>
            <div class="card-body">
                <canvas id="myChart2"></canvas>
                <!-- <div id="donut_chart2" class="dashboard-donut-chart"></div> -->
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <div class="card">
            <h5 class="card-header" style="text-align: center">
                Triwulan III
            </h5>
            <div class="card-body">
                <canvas id="myChart3"></canvas>
                <!-- <div id="donut_chart3" class="dashboard-donut-chart"></div> -->
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <div class="card">
            <h5 class="card-header" style="text-align: center">
                Triwulan IV
            </h5>
            <div class="card-body">
                <canvas id="myChart4"></canvas>
                <!-- <div id="donut_chart4" class="dashboard-donut-chart"></div> -->
            </div>
        </div>
    </div>
</div>

<br /> */ ?>
<!-- Basic Table -->
<script src="<?= base_url('assets/plugins/chartjs/Chart.min.js') ?>"></script>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <?php if ($_SESSION[SESSION_APP]['loginas']) { ?>
            <div class="alert alert-warning">
                Anda sedang mengakses user lain. <a href="<?= site_url("panelbackend/home/loginasback") ?>" class="alert-link">Kembali</a>.
            </div>
        <?php } ?>

        <?= FlashMsg() ?>
        <table class="table table-bordered" style="width: 100% !important;">
            <thead>
                <tr>
                    <th rowspan="2" width="1px">No</th>
                    <!-- <th rowspan="2">ID Risiko</th> -->
                    <th rowspan="2">Nama Risiko</th>
                    <!-- <th rowspan="2">Residual Setelah Evaluasi</th> -->
                    <th rowspan="2">KRI</th>
                    <!-- <th rowspan="2" width="30px">Stn</th> -->
                    <!-- <th rowspan="2" width="30px">Plt</th> -->
                    <th rowspan="2" width='15px'>Ambang Batas</th>
                    <th rowspan="2" width='15px'>Batas Normal</th>
                    <th colspan="<?= count(ListBulan()) * 2 ?>" width='15px'>Realisasi Bulan ke </th>
                    <th rowspan="2"></th>
                </tr>
                <tr>
                    <?php
                    foreach (ListBulan() as $k => $v) { ?>
                        <th colspan="2"><?= $k ?></th>
                    <?php } ?>
                    <!-- <th>Nilai</th>
                    <th>Warna</th> -->
                    <!-- <th colspan="2">TW I</th>
                    <th colspan="2">TW II</th>
                    <th colspan="2">TW III</th>
                    <th colspan="2">TW IV</th> -->
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;

                $tw1 = array();
                $tw2 = array();
                $tw3 = array();
                $tw4 = array();
                foreach ($list['rows'] as $r) {
                ?>
                    <tr>
                        <td rowspan="2"><?= $no++ ?></td>
                        <!-- <td style="text-align: center;"><?= $r['nomor'] ?></td> -->
                        <td rowspan="2"><a href="<?= site_url("panelbackend/risk_risiko/detail/$r[id_scorecard]/$r[id_risiko1]") ?>"><?= labeltingkatrisikolabel($r['id_kemungkinan'] . $r['id_dampak']); ?> <?= $r['nama_risiko'] ?></a></td>
                        <!-- <?= labeltingkatrisiko($r['id_kemungkinan'] . $r['id_dampak'], 2); ?> -->
                        <td rowspan="2"><a href="<?= site_url("panelbackend/kri/detail/$r[id_kri]/$tahun_filter") ?>"><?= $r['nama'] ?></a></td>
                        <!-- <td rowspan="2" style="text-align: center;"><?= $r['satuan'] ?></td> -->
                        <!-- <td rowspan="2" style="text-align: center;"><?= $r['polaritas'] ?></td> -->
                        <td rowspan="2" style="text-align: center;"><?= $r['batas_bawah'] ?><?= ($r['batas_atas'] ? '-' . $r['batas_atas'] : null) ?> <?= $r['satuan'] ?></td>
                        <td rowspan="2" style="text-align: center;"><?= $r['target_mulai'] ?><?= ($r['target_sampai'] ? '-' . $r['target_sampai'] : null) ?> <?= $r['satuan'] ?></td>
                        <?php
                        $from = "";
                        if ($r['polaritas'] == '+') {
                            $batas_bawah = $r['batas_bawah'];
                            $batas_atas = $r['batas_atas'];
                            $target_mulai = $r['target_mulai'];
                            $target_sampai = $r['target_sampai'];
                        } else {
                            $batas_bawah = $r['batas_atas'];
                            $batas_atas = $r['batas_bawah'];
                            $target_mulai = $r['target_sampai'];
                            $target_sampai = $r['target_mulai'];
                        }

                        $nilaiarr = array();
                        $baarr = array();
                        $bbarr = array();
                        $nilain = null;
                        $max = $batas_atas;
                        $min = $batas_bawah;
                        $warnaarr = array();
                        $tgaarr = array();
                        $tgbarr = array();

                        foreach (ListBulan() as $k => $v) {
                            $baarr[] = $batas_atas;
                            $bbarr[] = $batas_bawah;
                            $tgaarr[] = $target_sampai;
                            $tgbarr[] = $target_mulai;

                            $nilai = $r['nilai' . $k];

                            if ($max == null || $nilai > $max)
                                $max = $nilai;

                            if ($min == null || $nilai < $min)
                                $min = $nilai;

                            if ($nilai !== null)
                                $nilain = $nilai;

                            $nilaiarr[] = $nilain;

                            $warna = 'white';
                            if (isset($nilai) && $nilai !== '') {
                                // if ($r['polaritas'] == '+') {
                                if ($nilai < $batas_bawah) {
                                    $warna = 'red';
                                } elseif ($nilai > $batas_atas && $batas_atas) {
                                    $warna = 'red';
                                } elseif ($nilai >= $target_mulai && (!$target_sampai || $target_sampai == $target_mulai)) {
                                    $warna = 'green';
                                } elseif ($nilai >= $target_mulai && $nilai <= $target_sampai) {
                                    $warna = 'green';
                                } else {
                                    $warna = 'yellow';
                                }
                                // } else {
                                //     if ($nilai > $r['batas_bawah']) {
                                //         $warna = 'red';
                                //     } elseif ($nilai < $r['batas_atas'] && $r['batas_atas']) {
                                //         $warna = 'red';
                                //     } elseif ($nilai <= $target_mulai && (!$target_sampai || $target_sampai == $target_mulai)) {
                                //         $warna = 'green';
                                //     } elseif ($nilai <= $target_mulai && $nilai >= $target_sampai) {
                                //         $warna = 'green';
                                //     } else {
                                //         $warna = 'yellow';
                                //     }
                                // }
                            }

                            if ($warna)
                                $tw1[$warna]++;

                            $warnaarr[] = $warna;
                            // $from .= "<td>";
                            // $from .= ListBulan()[$r['bulan']];
                            // $from .= "</td>";
                            $from .= "<td width='15px'>";
                            $from .= $nilai;
                            $from .= "</td>";
                            $from .= "<td style='background-color:$warna'>";
                            $from .= "</td>";
                        }
                        echo $from;
                        ?>
                        <td rowspan="2"><a class="btn btn-warning btn-sm" style="padding: 0px 2px;" href="<?= site_url("panelbackend/kri/detail/$r[id_kri]/$tahun_filter") ?>"><i class="bi bi-arrow-right-square"></i> Realisasi</a></td>
                    </tr>

                    <tr>
                        <td colspan="24">
                            <canvas id="myChart<?= $no ?>" height="100">
                            </canvas>
                            <br />
                        </td>
                    </tr>
                    <script>
                        const myChart<?= $no ?> = new Chart(
                            document.getElementById('myChart<?= $no ?>'), {
                                type: 'line',
                                data: {
                                    labels: [
                                        "Januari",
                                        "Februari",
                                        "Maret",
                                        "April",
                                        "Mei",
                                        "Juni",
                                        "Juli",
                                        "Agustus",
                                        "September",
                                        "Oktober",
                                        "November",
                                        "Desember",
                                    ],
                                    datasets: [
                                        <?php if ($nilain) { ?> {
                                                label: 'Nilai',
                                                data: [<?= implode(",", $nilaiarr) ?>],
                                                borderColor: 'black',
                                                backgroundColor: [<?= "'" . implode("','", $warnaarr) . "'" ?>],
                                                fill: false
                                            },
                                        <?php } ?>
                                        <?php if ($batas_atas) { ?> {
                                                label: 'Ambang Batas',
                                                data: [<?= implode(",", $baarr) ?>],
                                                borderColor: 'red',
                                                backgroundColor: '#ff000075',
                                                fill: {
                                                    above: 'red',
                                                    target: {
                                                        value: <?= (float)$max ?>
                                                    }
                                                }
                                            },
                                        <?php } ?> {
                                            label: 'Ambang Batas',
                                            data: [<?= implode(",", $bbarr) ?>],
                                            borderColor: 'red',
                                            backgroundColor: '#ff000075',
                                            fill: {
                                                target: {
                                                    value: <?= (float)$min ?>
                                                }
                                            }
                                        },


                                        <?php if ($target_sampai) { ?> {
                                                label: 'Batas Normal',
                                                data: [<?= implode(",", $tgaarr) ?>],
                                                borderColor: 'green',
                                                backgroundColor: '#ffff0057',
                                                fill: {
                                                    above: 'red',
                                                    target: {
                                                        value: <?= (float)$max ?>
                                                    }
                                                }
                                            },
                                        <?php } ?> {
                                            label: 'Batas Normal',
                                            data: [<?= implode(",", $tgbarr) ?>],
                                            borderColor: 'green',
                                            backgroundColor: '#ffff0057',
                                            fill: {
                                                target: {
                                                    value: <?= (float)$min ?>
                                                }
                                            }
                                        },
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: {
                                            display: false,
                                        },
                                    },
                                },
                            }
                        );
                    </script>
                <?php } ?>
            </tbody>
        </table>

        <?php echo $list['total'] > $limit || true ? UI::showPaging($paging, $page, $limit_arr, $limit, $list) : null ?>

        <div style="clear: both;"></div>
    </div>
</div>
<?php
$label_warna = array("green" => "Hijau", "yellow" => "Kuning", "red" => "Merah", 'white' => "Kosong");
?>
<style>
    .table>:not(caption)>*>* {
        padding: 0.1rem 0.2rem;
        background-color: var(--bs-table-bg);
        border-bottom-width: 1px;
        box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
    }
</style>
<script type="text/javascript">
    // $(function() {
    //     $('#tablekri').DataTable();
    // })

    /*
    const tw1 = {
        labels: <?= json_encode(array_keys($tw1)) ?>,
        datasets: [{
            label: 'Dataset 1',
            data: <?= json_encode(array_values($tw1)) ?>,
            backgroundColor: <?= json_encode(array_keys($tw1)) ?>,
        }]
    };
    //https://www.chartjs.org/docs/latest/samples/area/line-datasets.html
    const myChart1 = new Chart(
        document.getElementById('myChart1'), {
            type: 'doughnut',
            data: tw1,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                        position: 'bottom',
                    },
                    title: {
                        display: false,
                        text: 'Chart.js Doughnut Chart'
                    }
                }
            },
        }
    );

    const tw2 = {
        labels: <?= json_encode(array_keys($tw2)) ?>,
        datasets: [{
            label: 'Dataset 1',
            data: <?= json_encode(array_values($tw2)) ?>,
            backgroundColor: <?= json_encode(array_keys($tw2)) ?>,
        }]
    };

    const myChart2 = new Chart(
        document.getElementById('myChart2'), {
            type: 'doughnut',
            data: tw2,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                        position: 'bottom',
                    },
                    title: {
                        display: false,
                        text: 'Chart.js Doughnut Chart'
                    }
                }
            },
        }
    );

    const tw3 = {
        labels: <?= json_encode(array_keys($tw3)) ?>,
        datasets: [{
            label: 'Dataset 1',
            data: <?= json_encode(array_values($tw3)) ?>,
            backgroundColor: <?= json_encode(array_keys($tw3)) ?>,
        }]
    };

    const myChart3 = new Chart(
        document.getElementById('myChart3'), {
            type: 'doughnut',
            data: tw3,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                        position: 'bottom',
                    },
                    title: {
                        display: false,
                        text: 'Chart.js Doughnut Chart'
                    }
                }
            },
        }
    );
    const tw4 = {
        labels: <?= json_encode(array_keys($tw4)) ?>,
        datasets: [{
            label: 'Dataset 1',
            data: <?= json_encode(array_values($tw4)) ?>,
            backgroundColor: <?= json_encode(array_keys($tw4)) ?>,
        }]
    };

    const myChart4 = new Chart(
        document.getElementById('myChart4'), {
            type: 'doughnut',
            data: tw4,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                        position: 'bottom',
                    },
                    title: {
                        display: false,
                        text: 'Chart.js Doughnut Chart'
                    }
                }
            },
        }
    );
    */
</script>
<style>
    .card-body {
        background-color: #f3f3f3;
    }

    g[aria-labelledby="id-66-title"],
    g[aria-labelledby="id-142-title"],
    g[aria-labelledby="id-218-title"],
    g[aria-labelledby="id-294-title"],
    g[aria-labelledby="id-145-title"],
    g[aria-labelledby="id-224-title"],
    g[aria-labelledby="id-303-title"] {
        display: none;
    }

    .dataTables_length,
    .dataTables_info {
        float: left;
    }

    .dataTables_filter,
    .dataTables_paginate {
        float: right;
    }

    .paginate_button {
        text-decoration: none !important;
        cursor: pointer;
        padding: 6px 12px;
        margin-left: -1px;
        line-height: 1.42857143;
    }

    .paginate_button.current {
        background-color: #0099BC;
        color: #fff;
    }

    .heightsmall th {
        height: 20px !important;
        padding: 0px !important;
    }

    table.dataTable thead>tr>th {
        padding-right: 1px;
    }

    .table-bordered thead tr th {
        padding: 1px;
    }

    /* table.dataTable {
        clear: both;
        margin-top: -15px !important;
        margin-bottom: 6px !important;
        max-width: none !important;
    } */
    .filter-header [type="checkbox"]+label:before {
        border: 2px solid #fff;
    }

    .filter-header [type="checkbox"]:checked+label:before {
        top: -4px;
        left: -5px;
        width: 12px;
        height: 22px;
        border-top: 2px solid transparent;
        border-left: 2px solid transparent;
        border-right: 2px solid #fff;
        border-bottom: 2px solid #fff;
        -webkit-transform: rotate(40deg);
        transform: rotate(40deg);
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        -webkit-transform-origin: 100% 100%;
        transform-origin: 100% 100%;
    }

    .filter-header label {
        padding: 0px 5px 0px 20px !important;
    }
</style>