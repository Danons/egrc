<?php
$inheren = $total['inheren'];
$control = $total['control'];

$tingkatrisikowarnaarr = array();
$tingkatrisikonamaarr = array();
$actual = array();
foreach ($tingkatrisikoarr as $r1) {
    $tingkatrisikowarnaarr[] = $r1['warna'];
    $tingkatrisikonamaarr[] = $r1['nama'];
    $actual[] = $total['actual'][$r1['nama']];
}

$residual = array();
foreach ($tingkatrisikoarr as $r1) {
    $residual[] = $total['current'][$r1['nama']];
}

$tingkatpeluangwarnaarr = array();
$tingkatpeluangnamaarr = array();
$inherenpeluang = array();
foreach ($tingkatpeluangarr as $r1) {
    $tingkatpeluangwarnaarr[] = $r1['warna'];
    $tingkatpeluangnamaarr[] = $r1['nama'];
    $inherenpeluang[] = $totalpeluang['inheren'][$r1['nama']];
}

$efektifitas = array();
foreach ($pengukuranrow as $r1) {
    $pengukuranrowefektifitas[] = $r1['efektifitas'];
    $pengukuranrowwarna[] = $r1['warna'];
    $efektifitas[] = $total['efektifitas'][$r1['efektifitas']];
}

// dpr($pertanyaan_survey, 1);
function getWarna($id_kategori, $pertanyaan)
{
    if ($pertanyaan[$id_kategori]['total_pertanyaan']) {
        $pertanyaan[$id_kategori]['jumlah_sempurna'] =  $pertanyaan[$id_kategori]['total_pertanyaan'] * 5;
        $rata_rata = $pertanyaan[$id_kategori]['total_nilai'] / $pertanyaan[$id_kategori]['jumlah_sempurna'] * 100;
    } else {
        $rata_rata = 0;
    }

    if (!$rata_rata) {
        $ret = "<span style=''>Tidak Ada</span>";
    } elseif ($rata_rata <= 20) {
        $ret = "<span style='color:red'>Sangat Kurang</span>";
    } elseif ($rata_rata <= 40) {
        $ret = "<span style='color:orange'>Kurang</span>";
    } elseif ($rata_rata <= 60) {
        $ret = "<span style='color:yellow'>Cukup</span>";
    } elseif ($rata_rata <= 80) {
        $ret = "<span style='color:green'>Baik</span>";
    } else {
        $ret = "<span style='color:blue'>Sangat Baik</span>";
    }
    return $ret;
}

?>
<script src="<?= base_url('assets/plugins/chartjs/Chart.min.js') ?>"></script>


<?php if (/*Access("index", "panelbackend/penilaian")*/true) { ?>
    <div class="row mb-1">
        <div class="col-sm-12">
            <div class="card mb-4">
                <h5 class="card-header">
                    <div class="row">
                        <!-- <div class="col-auto me-auto d-flex">Rekap Penilaian <?= $nama_gcg ?></div> -->

                        <div class="col-auto me-auto d-flex"> <span style="line-height: 1.9;">Assessment GCG</span>&nbsp;<?php echo (count($penilaian_session_arr) >= 1 ? UI::createSelect('id_penilaian_session', array('' => 'Pilih ...') + $penilaian_session_arr, $id_penilaian_session, true, 'form-control select2 me-2', "style='width:500px !important; display:inline' onchange='goSubmit(\"set_id_penilaian_session\")'") : null) ?></div>
                    </div>
                </h5>
                <div class="card-body" style="position: relative;">
                    <div class="row">
                        <div class="col-sm-8">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="width:10px">NO</th>
                                        <th rowspan="2">ASPEK</th>
                                        <th style="text-align: center;">BOBOT</th>
                                        <th style="text-align: center;">SKOR</th>
                                        <th style="text-align: center;">CAPAIAN %</th>
                                        <th style="text-align: center;width:100px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $kode_aspek = null;

                                    function keteranganskor($skor)
                                    {
                                        if ($skor == null)
                                            return "";

                                        if ($skor >= 90)
                                            return "Sangat Baik";
                                        elseif ($skor >= 75)
                                            return "Baik";
                                        elseif ($skor >= 60)
                                            return "Cukup";
                                        elseif ($skor >= 50)
                                            return "Kurang";
                                        else
                                            return "Sangat Kurang";
                                    }
                                    function warnaskor($skor)
                                    {
                                        if ($skor == null)
                                            return "#fff";

                                        if ($skor >= 90)
                                            return "#0d6efd; color:white";
                                        elseif ($skor >= 75)
                                            return "#80ff00";
                                        elseif ($skor >= 60)
                                            return "yellow";
                                        elseif ($skor > 50)
                                            return "#ff8000";
                                        else
                                            return "#ff0000";
                                    }
                                    $total_bobot = 0;
                                    $total_skor = 0;
                                    $bobots = [];
                                    $skor_bobots = [];
                                    foreach ($rekapaspek as $r) {
                                        $total_bobot += $r['bobot'];
                                        $total_skor += $r['skor_bobot'];
                                        $labels[] = $r['aspek'];
                                        $bobots[] = $r['bobot'];
                                        $skor_bobots[] = $r['skor_bobot'];
                                    ?>
                                        <tr>
                                            <td><?= $r['kode_aspek'] ?></td>
                                            <td><?= $r['aspek'] ?></td>
                                            <td style="text-align: center;"><?= @rupiah($r['bobot']) ?></td>
                                            <td style="text-align: center;"><?= @rupiah($r['skor_bobot']) ?></td>
                                            <td style="text-align: center; background:<?= @warnaskor($persent = round($r['skor_bobot'] / $r['bobot'] * 100, 2)) ?>"><?= @rupiah($persent) ?></td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: <?= $persent ?>%" aria-valuenow="<?= $persent ?>" aria-valuemin="0" aria-valuemax="100"><?= $persent ?>%</div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php $kode_aspek = $r['kode_aspek'];
                                    } ?>
                                    <tr>
                                        <td colspan="2"><b>Total</b></td>
                                        <td style="text-align: center;"><b><?= @rupiah($total_bobot) ?></b></td>
                                        <td style="text-align: center;"><b><?= @rupiah($total_skor) ?></b></td>
                                        <td style="text-align: center; background:<?= @warnaskor($persent = round($total_skor / $total_bobot * 100, 2)) ?>"><b><?= is_nan($total_skor / $total_bobot * 100) ? 0 : @rupiah($total_skor / $total_bobot * 100) ?></b></td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: <?= $persent ?>%" aria-valuenow="<?= $persent ?>" aria-valuemin="0" aria-valuemax="100"><?= is_nan($persent) ? 0 :$persent ?>%</div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Kategori</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: center; background:<?= @warnaskor($persent = round($total_skor / $total_bobot * 100, 2)) ?>"><b><?= keteranganskor($total_skor / $total_bobot * 100) ?></b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-4">
                            <canvas id="chartGCG"></canvas>
                            <script>
                                $(function() {
                                    const data = {
                                        labels: <?= json_encode($labels) ?>.map(function(v) {
                                            return v.split(" ");
                                        }),
                                        datasets: [{
                                                label: 'Bobot',
                                                data: <?= json_encode($bobots) ?>,
                                                borderColor: "red",
                                                // backgroundColor: "red"
                                                // backgroundColor: Utils.transparentize(Utils.CHART_COLORS.red, 0.5),
                                            },
                                            {
                                                label: 'Skor',
                                                data: <?= json_encode($skor_bobots) ?>,
                                                borderColor: "blue",
                                                // backgroundColor: "blue",
                                                // backgroundColor: Utils.transparentize(Utils.CHART_COLORS.blue, 0.5),
                                            }
                                        ]
                                    };

                                    const config = {
                                        type: 'radar',
                                        data: data,
                                        options: {
                                            responsive: true,
                                            plugins: {
                                                title: {
                                                    display: false,
                                                    text: ''
                                                }
                                            }
                                        },
                                    };


                                    const myChartrtm = new Chart(
                                        document.getElementById('chartGCG'), config
                                    );
                                })
                            </script>
                        </div>
                    </div>
                    <h5 class="mb-0">Hasil Quisioner <?= getWarna(1, $pertanyaan) ?></h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-1">

        <div class="col-sm-12">
            <div class="card mb-4">
                <h5 class="card-header">
                    <div class="row">
                        <div class="col-auto me-auto d-flex">Capability Level SPI</div>
                    </div>
                </h5>
                <div class="card-body" style="position: relative;">
                    <div class="row">
                        <div class="col-sm-8">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width:10px" rowspan="2">NO</th>
                                        <th rowspan="2">ASPEK</th>
                                        <!-- <th style="text-align: center;" colspan="3">LEVEL 2</th>
                                    <th style="text-align: center;" colspan="3">LEVEL 3</th>
                                    <th style="text-align: center;" colspan="3">LEVEL 4</th>
                                    <th style="text-align: center;" colspan="3">LEVEL 5</th> -->
                                        <th style="text-align: center;" rowspan="2">LEVEL</th>
                                    </tr>
                                    <tr>
                                        <!-- <th>Y/S/T</th>
                                    <th>Total</th>
                                    <th>Nilai</th>

                                    <th>Y/S/T</th>
                                    <th>Total</th>
                                    <th>Nilai</th>

                                    <th>Y/S/T</th>
                                    <th>Total</th>
                                    <th>Nilai</th>

                                    <th>Y/S/T</th>
                                    <th>Total</th>
                                    <th>Nilai</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $kode_aspek = null;

                                    $total_yst2 = 0;
                                    $total_nilai2 = 0;

                                    $total_yst3 = 0;
                                    $total_nilai3 = 0;

                                    $total_yst4 = 0;
                                    $total_nilai4 = 0;

                                    $total_yst5 = 0;
                                    $total_nilai5 = 0;

                                    $no = 1;
                                    // dpr($rowscls);
                                    $levelarr = [];
                                    if ($rowscls)
                                        foreach ($rowscls as $r) {
                                            $level = 1;
                                            $total_yst2 += $r['yst2'];
                                            $total_nilai2 += $r['nilai2'];

                                            $total_yst3 += $r['yst3'];
                                            $total_nilai3 += $r['nilai3'];

                                            $total_yst4 += $r['yst4'];
                                            $total_nilai4 += $r['nilai4'];

                                            $total_yst5 += $r['yst5'];
                                            $total_nilai5 += $r['nilai5'];
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $r['nama'] ?></td>

                                            <!-- <td style="text-align: center;"><?= $r['yst2'] ?></td>
                                        <td style="text-align: center;"><?= $r['nilai2'] ?></td>
                                        <td style="text-align: center;"><?= $r['yst2'] == $r['level2'] ? 'Y' : 'T' ?></td> -->
                                            <?php
                                            if ($r['yst2'] == $r['level2'] && $level == 1)
                                                $level = 2;
                                            ?>

                                            <!-- <td style="text-align: center;"><?= $r['yst3'] ?></td>
                                        <td style="text-align: center;"><?= $r['nilai3'] ?></td>
                                        <td style="text-align: center;"><?= $r['yst3'] == $r['level3'] ? 'Y' : 'T' ?></td> -->
                                            <?php
                                            if ($r['yst3'] == $r['level3'] && $level == 2)
                                                $level = 3;
                                            ?>

                                            <!-- <td style="text-align: center;"><?= $r['yst4'] ?></td>
                                        <td style="text-align: center;"><?= $r['nilai4'] ?></td>
                                        <td style="text-align: center;"><?= $r['yst4'] == $r['level4'] ? 'Y' : 'T' ?></td> -->
                                            <?php
                                            if ($r['yst4'] == $r['level4'] && $level == 3)
                                                $level = 4;
                                            ?>

                                            <!-- <td style="text-align: center;"><?= $r['yst5'] ?></td>
                                        <td style="text-align: center;"><?= $r['nilai5'] ?></td>
                                        <td style="text-align: center;"><?= $r['yst5'] == $r['level5'] ? 'Y' : 'T' ?></td> -->
                                            <?php
                                            if ($r['yst5'] == $r['level5'] && $level == 4)
                                                $level = 5;
                                            ?>

                                            <td style="text-align: center;">LEVEL <?= $level ?></td>
                                        </tr>
                                    <?php $kode_aspek = $r['kode_aspek'];
                                            $levelarr[] = $level;
                                        } ?>
                                    <tr>
                                        <th colspan="2"><b>Total</b></th>
                                        <!-- <td style="text-align: center;"><?= $total_yst2 ?></td>
                                    <td style="text-align: center;"><?= round($total_nilai2, 2) ?></td>
                                    <td style="text-align: center;"></td>
                                    <td style="text-align: center;"><?= $total_yst3 ?></td>
                                    <td style="text-align: center;"><?= round($total_nilai3, 2) ?></td>
                                    <td style="text-align: center;"></td>
                                    <td style="text-align: center;"><?= $total_yst4 ?></td>
                                    <td style="text-align: center;"><?= round($total_nilai4, 2) ?></td>
                                    <td style="text-align: center;"></td>
                                    <td style="text-align: center;"><?= $total_yst5 ?></td>
                                    <td style="text-align: center;"><?= round($total_nilai5, 2) ?></td> -->
                                        <td style="text-align: center;font-weight:bold !important;font-size:18px !important;    text-transform: uppercase;">
                                        <?php
                                        if($levelarr){
                                            echo "Level "; 
                                            echo $leveltotal = floor(array_sum($levelarr) / count($levelarr));
                                            echo "(" . [
                                                "1" => "Initial",
                                                "2" => "Infrastructure",
                                                "3" => "Integrated",
                                                "4" => "Managed",
                                                "5" => "Optimizing"
                                            ][$leveltotal] . ")";
                                        }
                                                    ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-4">
                            <canvas id="chartCL"></canvas>
                            <script>
                                $(function() {
                                    const labelss = <?= json_encode($labelcl) ?>.map(function(v) {
                                        return v.split("\n");
                                    });
                                    console.log(labelss)
                                    const data = {
                                        labels: labelss,
                                        datasets: [{
                                                label: 'Level',
                                                data: <?= json_encode($nilaicl) ?>,
                                                borderColor: "red",
                                                // backgroundColor: "red"
                                                // backgroundColor: Utils.transparentize(Utils.CHART_COLORS.red, 0.5),
                                            },
                                            // {
                                            //     label: 'Skor',
                                            //     data: <?= json_encode($skor_bobots) ?>,
                                            //     borderColor: "blue",
                                            //     // backgroundColor: "blue",
                                            //     // backgroundColor: Utils.transparentize(Utils.CHART_COLORS.blue, 0.5),
                                            // }
                                        ]
                                    };

                                    const config = {
                                        type: 'radar',
                                        data: data,
                                        options: {
                                            scales: {
                                                r: {
                                                    // pointLabels: {
                                                    //     callback: (label) => {
                                                    //         return label.split(" ").join("\n");
                                                    //     },
                                                    // },
                                                    suggestedMin: 0,
                                                    suggestedMax: 5,
                                                    min: 0,
                                                    max: 5,
                                                    ticks: {
                                                        precision: 0,
                                                        beginAtZero: true,
                                                        userCallback: function(label, index, labels) {
                                                            // when the floored value is the same as the value we have a whole number
                                                            if (Math.floor(label) === label) {
                                                                return label;
                                                            }

                                                        },
                                                    }
                                                }
                                            },
                                            responsive: true,
                                            plugins: {
                                                legend: {
                                                    display: false
                                                },
                                                title: {
                                                    display: false,
                                                    text: ''
                                                }
                                            }
                                        },
                                    };


                                    const myChartrtm = new Chart(
                                        document.getElementById('chartCL'), config
                                    );
                                })
                            </script>
                        </div>
                    </div>
                </div>
                <h5 class="mb-1 ms-1">Hasil Quisioner <?= getWarna(3, $pertanyaan) ?></h5>
            </div>
        </div>
    </div>
<?php } ?>

<?php if ($pertanyaan_survey || $pertanyaan_survey) { ?>
    <div class="card w-full mb-4 p-1">
        <div class="row">
            <?php if ($pertanyaan_survey) { ?>
                <div class="col-6">
                    <h5>Hasil Quisioner Survey Kepuasan Auditee Per Kegiatan <?= getWarna(4, $pertanyaan_survey) ?></h5>
                </div>
            <?php } ?>
            <?php if ($pertanyaan_survey) { ?>
                <div class="col-6">
                    <h5>Hasil Quisioner Survey Kepuasan Auditee Per Tahunan <?= getWarna(5, $pertanyaan_survey) ?></h5>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>

<div class="row mb-1">
    <div class="col-sm-12">
        <div class="card mb-4">
            <h5 class="card-header">
                <div class="row">
                    <div class="col-auto me-auto d-flex">Maturity Level Manajemen Risiko</div>
                </div>
            </h5>
            <div class="card-body" style="position: relative;">
                <div class="row">
                    <div class="col-sm-8">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:10px" rowspan="2">NO</th>
                                    <th rowspan="2">ASPEK</th>
                                    <!-- <th style="text-align: center;" colspan="3">LEVEL 2</th>
                                    <th style="text-align: center;" colspan="3">LEVEL 3</th>
                                    <th style="text-align: center;" colspan="3">LEVEL 4</th>
                                    <th style="text-align: center;" colspan="3">LEVEL 5</th> -->
                                    <th style="text-align: center;" rowspan="2">LEVEL</th>
                                </tr>
                                <tr>
                                    <!-- <th>Y/S/T</th>
                                    <th>Total</th>
                                    <th>Nilai</th>

                                    <th>Y/S/T</th>
                                    <th>Total</th>
                                    <th>Nilai</th>

                                    <th>Y/S/T</th>
                                    <th>Total</th>
                                    <th>Nilai</th>

                                    <th>Y/S/T</th>
                                    <th>Total</th>
                                    <th>Nilai</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php $kode_aspek = null;

                                $total_yst2 = 0;
                                $total_nilai2 = 0;

                                $total_yst3 = 0;
                                $total_nilai3 = 0;

                                $total_yst4 = 0;
                                $total_nilai4 = 0;

                                $total_yst5 = 0;
                                $total_nilai5 = 0;

                                $no = 1;
                                // dpr($rowsmls);
                                $levelarr = [];
                                if ($rowsmls)
                                    foreach ($rowsmls as $r) {
                                        $level = 1;
                                        $total_yst2 += $r['yst2'];
                                        $total_nilai2 += $r['nilai2'];

                                        $total_yst3 += $r['yst3'];
                                        $total_nilai3 += $r['nilai3'];

                                        $total_yst4 += $r['yst4'];
                                        $total_nilai4 += $r['nilai4'];

                                        $total_yst5 += $r['yst5'];
                                        $total_nilai5 += $r['nilai5'];
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $r['nama'] ?></td>

                                        <!-- <td style="text-align: center;"><?= $r['yst2'] ?></td>
                                        <td style="text-align: center;"><?= $r['nilai2'] ?></td>
                                        <td style="text-align: center;"><?= $r['yst2'] == $r['level2'] ? 'Y' : 'T' ?></td> -->
                                        <?php
                                        if ($r['yst2'] == $r['level2'] && $level == 1)
                                            $level = 2;
                                        ?>

                                        <!-- <td style="text-align: center;"><?= $r['yst3'] ?></td>
                                        <td style="text-align: center;"><?= $r['nilai3'] ?></td>
                                        <td style="text-align: center;"><?= $r['yst3'] == $r['level3'] ? 'Y' : 'T' ?></td> -->
                                        <?php
                                        if ($r['yst3'] == $r['level3'] && $level == 2)
                                            $level = 3;
                                        ?>

                                        <!-- <td style="text-align: center;"><?= $r['yst4'] ?></td>
                                        <td style="text-align: center;"><?= $r['nilai4'] ?></td>
                                        <td style="text-align: center;"><?= $r['yst4'] == $r['level4'] ? 'Y' : 'T' ?></td> -->
                                        <?php
                                        if ($r['yst4'] == $r['level4'] && $level == 3)
                                            $level = 4;
                                        ?>

                                        <!-- <td style="text-align: center;"><?= $r['yst5'] ?></td>
                                        <td style="text-align: center;"><?= $r['nilai5'] ?></td>
                                        <td style="text-align: center;"><?= $r['yst5'] == $r['level5'] ? 'Y' : 'T' ?></td> -->
                                        <?php
                                        if ($r['yst5'] == $r['level5'] && $level == 4)
                                            $level = 5;
                                        ?>

                                        <td style="text-align: center;">LEVEL <?= $level ?></td>
                                    </tr>
                                <?php $levelarr[] = $level;
                                        $kode_aspek = $r['kode_aspek'];
                                    } ?>
                                <tr>
                                    <th colspan="2"><b>Total</b></th>
                                    <!-- <td style="text-align: center;"><?= $total_yst2 ?></td>
                                    <td style="text-align: center;"><?= round($total_nilai2, 2) ?></td>
                                    <td style="text-align: center;"></td>
                                    <td style="text-align: center;"><?= $total_yst3 ?></td>
                                    <td style="text-align: center;"><?= round($total_nilai3, 2) ?></td>
                                    <td style="text-align: center;"></td>
                                    <td style="text-align: center;"><?= $total_yst4 ?></td>
                                    <td style="text-align: center;"><?= round($total_nilai4, 2) ?></td>
                                    <td style="text-align: center;"></td>
                                    <td style="text-align: center;"><?= $total_yst5 ?></td>
                                    <td style="text-align: center;"><?= round($total_nilai5, 2) ?></td> -->
                                    <td style="text-align: center;font-weight:bold !important;font-size:18px !important;    text-transform: uppercase;">
                                    <?php    
                                                if($levelarr){

                                                    echo "Level ";
                                                    echo $leveltotal = floor(array_sum($levelarr) / count($levelarr));
                                                    echo "(" . [
                                                        "1" => "Initial or Ad-Hoc",
                                                        "2" => "Managed",
                                                        "3" => "Defined",
                                                        "4" => "Predictable",
                                                        "5" => "Optimizing"
                                                    ][$leveltotal] . ")";
                                                }
                                                ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-4">
                        <canvas id="chartML"></canvas>
                        <script>
                            $(function() {
                                const data = {
                                    labels: <?= json_encode($labelml) ?>.map(function(v) {
                                        return v.split("\n");
                                    }),
                                    datasets: [{
                                            label: 'Level',
                                            data: <?= json_encode($nilaiml) ?>,
                                            borderColor: "red",
                                            // backgroundColor: "red"
                                            // backgroundColor: Utils.transparentize(Utils.CHART_COLORS.red, 0.5),
                                        },
                                        // {
                                        //     label: 'Skor',
                                        //     data: <?= json_encode($skor_bobots) ?>,
                                        //     borderColor: "blue",
                                        //     // backgroundColor: "blue",
                                        //     // backgroundColor: Utils.transparentize(Utils.CHART_COLORS.blue, 0.5),
                                        // }
                                    ]
                                };

                                const config = {
                                    type: 'radar',
                                    data: data,
                                    options: {
                                        scales: {
                                            r: {
                                                // pointLabels: {
                                                //     callback: (label) => {
                                                //         return label.split(" ").join("\n");
                                                //     },
                                                // },
                                                suggestedMin: 0,
                                                suggestedMax: 5,
                                                min: 0,
                                                max: 5,
                                                ticks: {
                                                    precision: 0,
                                                    beginAtZero: true,
                                                    userCallback: function(label, index, labels) {
                                                        // when the floored value is the same as the value we have a whole number
                                                        if (Math.floor(label) === label) {
                                                            return label;
                                                        }

                                                    },
                                                }
                                            }
                                        },
                                        responsive: true,
                                        plugins: {
                                            legend: {
                                                display: false
                                            },
                                            title: {
                                                display: false,
                                                text: ''
                                            }
                                        }
                                    },
                                };


                                const myChartrtm = new Chart(
                                    document.getElementById('chartML'), config
                                );
                            })
                        </script>
                    </div>
                </div>
            </div>
            <h5 class="mb-1 ms-1">Hasil Quisioner <?= getWarna(2, $pertanyaan) ?></h5>
        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-auto me-auto d-flex">
        <?php //echo (count($scorecardarr) > 1 ? UI::createSelect('id_scorecard_filter', array('' => '-Pilih kajian risiko-') + $scorecardarr, $id_scorecard_filter, true, 'form-control select2 me-2', "style='width:200px !important; display:inline' onchange='goSubmit(\"set_filter\")'") : null) 
        ?>
        <?php echo (count($unitarr) > 1 ? UI::createSelect('id_unit_filter', array('' => '-Pilih Unit-') + $unitarr, $id_unit_filter, true, 'form-control select2 me-2', "style='width:500px !important; display:inline' onchange='goSubmit(\"set_filter\")'") : null) ?>
        <?php // UI::createSelect("id_kpi_filter", array('' => '-KPI-') + $kpiarr, $id_kpi_filter, true, 'form-control', "style='max-width:600px !important; display:inline' onchange='goSubmit(\"set_filter\")'") 
        ?>
    </div>
    <div class="col-auto d-flex">
        <?= UI::createSelect("id_periode_tw_filter", $mtperiodetwarr, $id_periode_tw_filter, true, 'form-control me-2', "style='width:200px !important; display:inline' onchange='goSubmit(\"set_filter\")'")
        ?>
        <?php // UI::createTextNumber("tahun_filter", $tahun_filter, 4, 4, true, "form-control", "style='width:80px; display:inline' onchange='goSubmit(\"set_filter\")'") 
        ?>
    </div>
</div>
<?php if (Access("index", "panelbackend/risk_scorecard")) { ?>
    <div class="row mb-1">
        <div class="col-sm-7">
            <div class="card mb-4">
                <h5 class="card-header">
                    <div class="row">
                        <div class="col-auto me-auto d-flex">Top 10 Risiko </div>
                        <div class="mx-0 form-switch px-0 col-auto">
                            <input class="form-check-input" style="margin-top:2px;" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                        </div>
                    </div>
                </h5>
                <div class="card-body">
                    <?php
                    $is_css = false;
                    include "laporanriskprofileprint1.php";
                    ?>
                </div>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="card mb-4">
                <h5 class="card-header">Grafik Tingkat Risiko</h5>
                <div class="card-body" style="position: relative;">
                    <div class="row">
                        <div class="col-sm-6">
                            <div style="margin:auto; width:70%"><canvas id="myChart"></canvas></div>
                            <center style="font-weight: 500;">Residual Saat Ini</center>
                        </div>
                        <span class="material-icons panah panah1">
                            forward
                        </span>
                        <div class="col-sm-6">
                            <div style="margin:auto; width:70%"><canvas id="myChart1"></canvas></div>
                            <center style="font-weight: 500;">Residual Setelah Evaluasi</center>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <hr />
                            <div class="d-flex" style="zoom: 0.8;">
                                <?php
                                foreach ($tingkatrisikoarr as $r) { ?>
                                    <span class="d-flex">
                                        <div style="margin-top:3px;border-radius:15px;height:15px;width:15px;margin-right:5px;background-color:<?= $r['warna'] ?>;"></div>
                                        <?= $r['nama'] ?>
                                        &nbsp;
                                        &nbsp;
                                    </span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <h5 class="card-header">Grafik Pengendalian & Mitigasi</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div style="margin:auto; width:70%"><canvas id="myChart2"></canvas></div>
                            <center style="font-weight: 500;">Efektifitas Control</center>
                        </div>
                        <div class="col-sm-6">
                            <div style="margin:auto; width:70%">
                                <div class="RadialProgress RadialProgress1" role="progressbar" aria-valuenow="<?= round($total['total_progress']) ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <center style="font-weight: 500;">Progress Mitigasi</center>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <hr />
                            <div class="d-flex flex-wrap" style="zoom: 0.8;">
                                <?php
                                foreach ($pengukuranrow as $r) { ?>
                                    <span class="d-flex">
                                        <div style="margin-top:3px;border-radius:15px;height:15px;width:15px;margin-right:5px;background-color:<?= $r['warna'] ?>;"></div>
                                        <?= $r['efektifitas'] ?>
                                        &nbsp;
                                        &nbsp;
                                    </span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (Access("index", "panelbackend/opp_scorecard")) { ?>
    <div class="row mb-1">
        <div class="col-sm-7">
            <div class="card mb-4">
                <h5 class="card-header">
                    <div class="row">
                        <div class="col-auto me-auto d-flex">Top 10 Peluang </div>
                        <div class="mx-0 form-switch px-0 col-auto">
                            <input class="form-check-input" style="margin-top:2px;" type="checkbox" role="switch" id="flexSwitchCheckDefaultPeluang">
                        </div>
                    </div>
                </h5>
                <div class="card-body">
                    <?php
                    $is_css = false;
                    include "laporanoppprofileprint1.php";
                    ?>
                </div>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="card mb-4">
                <h5 class="card-header">Grafik Peluang</h5>
                <div class="card-body" style="position: relative;">
                    <div class="row">
                        <div class="col-sm-6">
                            <div style="margin:auto; width:70%"><canvas id="myChart4"></canvas></div>
                            <center style="font-weight: 500;">Tingkat Peluang</center>
                        </div>
                        <div class="col-sm-6">
                            <!-- <div style="margin:auto; width:70%"><canvas id="myChart5"></canvas></div>
                            <center style="font-weight: 500;">KPI</center> -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <hr />
                            <div class="d-flex" style="zoom: 0.8;">
                                <?php
                                foreach ($tingkatpeluangarr as $r) { ?>
                                    <span class="d-flex">
                                        <div style="margin-top:3px;border-radius:15px;height:15px;width:15px;margin-right:5px;background-color:<?= $r['warna'] ?>;"></div>
                                        <?= $r['nama'] ?>
                                        &nbsp;
                                        &nbsp;
                                    </span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php /*

<?php if (Access("index", "panelbackend/comp_kepatuhan")) { ?>
    <div class="row mb-1">
        <div class="col-sm-12">
            <div class="card mb-4">
                <h5 class="card-header">
                    <div class="row">
                        <div class="col-auto me-auto d-flex">Tingkat Kepatuhan Divisi/Unit Terhadap Aturan yang Ada</div>
                    </div>
                </h5>
                <div class="card-body" style="position: relative;">
                    <div class="row">
                        <div class="col-sm-12" style="text-align: center;">
                            <canvas id="myChart7" style="width: 100%; height:300px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (Access("index", "panelbackend/pemeriksaan")) { ?>
    <?php foreach ($jenisauditarr as $jenis => $labeljenis) { ?>
        <div class="row mb-1">
            <div class="col-sm-12">
                <div class="card mb-4">
                    <h5 class="card-header">
                        <div class="row">
                            <div class="col-auto me-auto d-flex">Monitoring Tindak Lanjut Rekomendasi Audit <?= $labeljenis ?></div>
                        </div>
                    </h5>
                    <div class="card-body" style="position: relative;">
                        <div class="row">
                            <div class="col-sm-12" style="text-align: center;">
                                <canvas id="myChart<?= $jenis ?>" style="width: 100%; height:300px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>


<div class="modal fade" id="detailkpi" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">KPI Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
*/ ?>
<script>
    <?php if (Access("index", "panelbackend/kpi_target_unit") || Access("index", "panelbackend/kpi_target_dir") || Access("index", "panelbackend/kpi_target_kor")) { ?>

        // function resizeRadialProgress0() {
        //     $(".RadialProgress0").css('block-size', $("#myChart6").width())
        //     $(".RadialProgress0").css('inline-size', $("#myChart6").width())
        // }
    <?php } ?>
    $(function() {
        resizeRadialProgress();
        // resizeRadialProgress0();
        $(".tablematriks").show();
        $(".tablerisiko").hide();
    })
    $('#flexSwitchCheckDefault').on('change', function() {
        var val = this.checked ? this.value : '';
        if (val == 'on') {
            $(".tablematriks").hide();
            $(".tablerisiko").show();
        } else {
            $(".tablematriks").show();
            $(".tablerisiko").hide();
        }
    });
    $('#flexSwitchCheckDefaultPeluang').on('change', function() {
        var val = this.checked ? this.value : '';
        if (val == 'on') {
            $(".tablematrikspeluang").hide();
            $(".tablepeluang").show();
        } else {
            $(".tablematrikspeluang").show();
            $(".tablepeluang").hide();
        }
    });

    const DATA_COUNT = 5;
    const NUMBER_CFG = {
        count: DATA_COUNT,
        min: 0,
        max: 100
    };

    const dataactual = {
        labels: <?= json_encode($tingkatrisikonamaarr) ?>,
        datasets: [{
            label: 'Dataset 1',
            data: <?= json_encode($actual) ?>,
            backgroundColor: <?= json_encode($tingkatrisikowarnaarr) ?>,
        }]
    };

    const datacurrent = {
        labels: <?= json_encode($tingkatrisikonamaarr) ?>,
        datasets: [{
            label: 'Dataset 1',
            data: <?= json_encode($residual) ?>,
            backgroundColor: <?= json_encode($tingkatrisikowarnaarr) ?>,
        }]
    };

    const efektifitas = {
        labels: <?= json_encode($pengukuranrowefektifitas) ?>,
        datasets: [{
            label: 'Dataset 1',
            data: <?= json_encode($efektifitas) ?>,
            backgroundColor: <?= json_encode($pengukuranrowwarna) ?>,
        }]
    };

    <?php if (Access("index", "panelbackend/risk_scorecard")) { ?>

        function resizeRadialProgress() {
            $(".RadialProgress1").css('block-size', $("#myChart2").width())
            $(".RadialProgress1").css('inline-size', $("#myChart2").width())
        }
        const myChart = new Chart(
            document.getElementById('myChart'), {
                type: 'doughnut',
                data: datacurrent,
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
        const myChart1 = new Chart(
            document.getElementById('myChart1'), {
                type: 'doughnut',
                data: dataactual,
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
        const myChart2 = new Chart(
            document.getElementById('myChart2'), {
                type: 'doughnut',
                data: efektifitas,
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


        const datainheren = {
            labels: <?= json_encode($tingkatpeluangnamaarr) ?>,
            datasets: [{
                label: 'Dataset 1',
                data: <?= json_encode($inherenpeluang) ?>,
                backgroundColor: <?= json_encode($tingkatpeluangwarnaarr) ?>,
            }]
        };

        const myChart4 = new Chart(
            document.getElementById('myChart4'), {
                type: 'doughnut',
                data: datainheren,
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

    <?php } ?>

    function getRandomColor() {
        var letters = '0123456789ABCDEF'.split('');
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    // const datkpi = {
    //     labels: <?= json_encode($kor_kpi['label']) ?>,
    //     datasets: [{
    //         label: 'Dataset 1',
    //         data: <?= json_encode($kor_kpi['jumlah']) ?>,
    //         datakpi: <?= json_encode($kor_kpi['id']) ?>,
    //         backgroundColor: <?= json_encode($kor_kpi['label']) ?>.map(() => getRandomColor()),
    //     }]
    // };

    <?php if (Access("index", "panelbackend/opp_scorecard")) { ?>
        const datapeluang = {
            labels: <?= json_encode($totalpeluang['nkpi']) ?>,
            datasets: [{
                label: 'Dataset 1',
                data: <?= json_encode($totalpeluang['jkpi']) ?>,
                backgroundColor: <?= $totalpeluang['jkpi'] ? json_encode($totalpeluang['jkpi']) : '[]' ?>.map(() => getRandomColor()),
            }]
        };

        // const myChart5 = new Chart(
        //     document.getElementById('myChart5'), {
        //         type: 'doughnut',
        //         data: datapeluang,
        //         options: {
        //             responsive: true,
        //             plugins: {
        //                 legend: {
        //                     display: false,
        //                     position: 'bottom',
        //                 },
        //                 title: {
        //                     display: false,
        //                     text: 'Chart.js Doughnut Chart'
        //                 }
        //             }
        //         },
        //     }
        // );
    <?php } ?>
    <?php if (Access("index", "panelbackend/kpi_target_unit") || Access("index", "panelbackend/kpi_target_dir") || Access("index", "panelbackend/kpi_target_kor")) { ?>

        const myChart6 = new Chart(
            document.getElementById('myChart6'), {
                type: 'doughnut',
                data: datkpi,
                options: {
                    onClick: (e, activeEls) => {
                        let datasetIndex = activeEls[0].datasetIndex;
                        let dataIndex = activeEls[0].index;
                        let datasetLabel = e.chart.data.datasets[datasetIndex].label;
                        let dataKpi = e.chart.data.datasets[datasetIndex].datakpi;
                        let value = e.chart.data.datasets[datasetIndex].data[dataIndex];
                        let label = e.chart.data.labels[dataIndex];

                        console.log(dataKpi[dataIndex]);
                        console.log("In click", datasetLabel, label, value);

                        $("#detailkpi").modal("show");
                        $("#detailkpi .modal-title").html(label);

                        $.ajax({
                            url: "<?= base_url('panelbackend/ajax/listkpichildkorporat/') ?>" + dataKpi[dataIndex] + "/" + <?= $tahun_filter ?>,
                            success: function(response) {
                                $("#detailkpi .modal-body").html(response);
                            }
                        })
                    },
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
    <?php } ?>
</script>
<style>
    /* ! my new style */
    .panah {
        position: absolute;
        z-index: 10;
        text-align: center;
        display: block;
        font-size: 36px !important;
        text-shadow: unset !important;
        color: unset !important;
        -webkit-background-clip: unset !important;
        background-clip: unset !important;
        background-position: center;
        background-size: 70%;
        background-repeat: no-repeat;
        font-size: 65px !important;
        justify-content: center;
        align-items: center;
        color: #0747a6 !important;
        filter: drop-shadow(0px 3px 5px rgba(0, 0, 0, 0.2));
    }

    @media (max-width: 991px) {
        .panah {
            display: none;
        }
    }

    .panah1 {
        -webkit-animation: myrighta 4s;
        animation: myrighta 4s;
        top: 37%;
        right: 0;
        line-height: 0;
    }

    @-webkit-idkeyframes myrighta {
        0% {
            opacity: 0;
            right: 20%;
        }

        45% {
            opacity: 0;
            right: 10%;
        }

        70% {
            right: -5%;
            opacity: 1;
        }
    }

    @idkeyframes myrighta {
        0% {
            opacity: 0;
            right: 20%;
        }

        45% {
            opacity: 0;
            right: 10%;
        }

        70% {
            right: -5%;
            opacity: 1;
        }
    }

    .listdoc {
        padding: 10px;
    }

    .listdoc a {
        text-decoration: none;
    }

    .listdoc a:hover {
        text-decoration: underline;
    }
</style>
<script src="<?php echo base_url() ?>assets/plugins/knob/jquery.knob.js"></script>


<script>
    $(function() {
        /* jQueryKnob */

        $(".knob").knob({
            /*change : function (value) {
             //console.log("change : " + value);
             },
             release : function (value) {
             console.log("release : " + value);
             },
             cancel : function () {
             console.log("cancel : " + this.value);
             },*/
            // draw: function() {

            //     // "tron" case
            //     if (this.$.data('skin') == 'tron') {

            //         var a = this.angle(this.cv) // Angle
            //             ,
            //             sa = this.startAngle // Previous start angle
            //             ,
            //             sat = this.startAngle // Start angle
            //             ,
            //             ea // Previous end angle
            //             , eat = sat + a // End angle
            //             ,
            //             r = true;

            //         this.g.lineWidth = this.lineWidth;

            //         this.o.cursor &&
            //             (sat = eat - 0.3) &&
            //             (eat = eat + 0.3);

            //         if (this.o.displayPrevious) {
            //             ea = this.startAngle + this.angle(this.value);
            //             this.o.cursor &&
            //                 (sa = ea - 0.3) &&
            //                 (ea = ea + 0.3);
            //             this.g.beginPath();
            //             this.g.strokeStyle = this.previousColor;
            //             this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
            //             this.g.stroke();
            //         }

            //         this.g.beginPath();
            //         this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
            //         this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
            //         this.g.stroke();

            //         this.g.lineWidth = 2;
            //         this.g.beginPath();
            //         this.g.strokeStyle = this.o.fgColor;
            //         this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
            //         this.g.stroke();

            //         return false;
            //     }
            // }
        });
        /* END JQUERY KNOB */

        //INITIALIZE SPARKLINE CHARTS
        // $(".sparkline").each(function() {
        //     var $this = $(this);
        //     $this.sparkline('html', $this.data());
        // });

        /* SPARKLINE DOCUMENTAION EXAMPLES http://omnipotent.net/jquery.sparkline/#s-about */
        // drawDocSparklines();
        // drawMouseSpeedDemo();

    });
    $(function() {
        $('input.knob').attr('readonly', 'readonly');
    })
</script>

<!-- <script>
    $(document).ready(function() {
        scrollToTop()
    })
    function scrollToTop () {
        setTimeout(() => {
            window.scrollTo(0, 0);
        }, 2000)
    }
</script> -->