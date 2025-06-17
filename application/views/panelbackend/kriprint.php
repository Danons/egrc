<br />

<script src="<?= base_url('assets/plugins/chartjs/Chart.min.js') ?>"></script>
<div style="font-weight: bold;">
    Pemantauan KRI terhadap
    <?= $kategoriarr[$id_kajian_risiko_filter] ?> <?= ($id_scorecard_filter ? $scorecardarr[$id_scorecard_filter] : null) ?>
    <?php
    if ($tingkatarr) {
        echo ' tingkat ';
        $tigkatlast = $tingkatarr[count($tingkatarr) - 1];
        unset($tingkatarr[count($tingkatarr) - 1]);
        if ($tingkatarr) {
            echo implode(", ", $tingkatarr) . " dan ";
        }
        echo $tigkatlast;
    }
    ?>
    <?php
    if ($id_unit_filter) {
        echo " unit " . $mtsdmunitarr[$id_unit_filter] . " ";
    }

    if ($tahun_filter) {
        echo " tahun " . $tahun_filter . " ";
    }
    ?>
    adalah sebagai berikut :
</div>
<br />
<table class="tableku" border="1" style="padding: 0px; margin:0px">
    <thead>
        <tr>
            <th rowspan="2" width="1px">No</th>
            <th rowspan="2">ID Risiko</th>
            <th rowspan="2">Nama Risiko</th>
            <th rowspan="2" width="30px">Residual Setelah Evaluasi</th>
            <th rowspan="2">KRI</th>
            <th rowspan="2" width="30px">Stn</th>
            <th rowspan="2" width="30px">Plt</th>
            <th rowspan="2" width="30px">Threshold</th>
            <th rowspan=" 2" width="30px">Target</th>
            <th colspan=" <?= count(ListBulan()) * 2 ?>" style="text-align: center;">Realisasi Bulan ke </th>
            <th rowspan="2">Formula</th>
        </tr>
        <tr>
            <?php
            foreach (ListBulan() as $k => $v) { ?>
                <th colspan="2" style="text-align: center;"><?= $k ?></th>
            <?php } ?>
            <!-- <th colspan="2">TW I</th>
                    <th colspan="2">TW II</th>
                    <th colspan="2">TW III</th>
                    <th colspan="2">TW IV</th> -->
        </tr>
        <tr class="heightsmall">
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <?php
            foreach (ListBulan() as $k => $v) { ?>
                <th></th>
                <th></th>
            <?php } ?>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1;
        foreach ($rows as $r) { ?>
            <tr>
                <td rowspan="2"><?= $no++ ?></td>
                <td rowspan="2" style="text-align: center;"><?= $r['nomor'] ?></td>
                <td rowspan="2"><a href="<?= site_url("panelbackend/risk_risiko/detail/$r[id_scorecard]/$r[id_risiko1]") ?>"><?= $r['nama_risiko'] ?></a></td>
                <?= labeltingkatrisiko($r['id_kemungkinan'] . $r['id_dampak'], 2); ?>
                <td rowspan="2"><a href="<?= site_url("panelbackend/kri/detail/$r[id_kri]/$tahun_filter") ?>"><?= $r['nama'] ?></a></td>
                <td rowspan="2" style="text-align: center;"><?= $r['satuan'] ?></td>
                <td rowspan="2" style="text-align: center;"><?= $r['polaritas'] ?></td>
                <td rowspan="2" style="text-align: center;"><?= $r['batas_bawah'] ?><?= ($r['batas_atas'] ? '-' . $r['batas_atas'] : null) ?></td>
                <td rowspan="2" style="text-align: center;"><?= $r['target_mulai'] ?><?= ($r['target_sampai'] ? '-' . $r['target_sampai'] : null) ?></td>
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
                    $from .= "<td>";
                    $from .= $nilai;
                    $from .= "</td>";
                    $from .= "<td style='background-color:$warna'>";
                    $from .= "</td>";
                }
                echo $from;
                ?>
                <td rowspan="2"><?= $r['ketarangan'] ?>
                    <!-- <td><a href="javascript:void(0)"><i class="bi bi-graph-down"></i></a></td> -->
            </tr>

            <tr>
                <td colspan="24">
                    <canvas id="myChart<?= $no ?>" height="100">
                    </canvas>
                    <center><b><?= $r['nama'] ?></b></center>
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

                myChart<?= $no ?>.height = 100;
            </script>
        <?php } ?>
    </tbody>
</table>

<style>
    .tableku td {
        padding: 3px !important;
    }
</style>