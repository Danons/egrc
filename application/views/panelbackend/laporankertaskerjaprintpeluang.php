<script src="<?php echo base_url() ?>assets/template/backend/plugins/jquery/jquery.min.js"></script>
<script src="<?php echo base_url() ?>assets/template/backend/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<table class="tableku1" id="export" border="1" style="border: 0px;">
    <thead style="border: 0px;">
        <?php if ($row['laporan']) { ?>
            <tr>
                <th colspan="<?= count($header1) + 2 ?>" style="border:0px"></th>
                <th colspan="2" style="text-align: left;border:0px">
                    <?= $row['laporan']['nama'] ?>
                    <br />
                    Formulir No. : F-Pros-56
                </th>
            </tr>
            <tr>
                <th colspan="<?= count($header1) + 4 ?>" style="border:0px"><b>
                        <?= $row['laporan']['judul'] ?>
                        <br />
                        <?php if ($namaunit !== 'Semua Unit') {
                            echo strtoupper($namaunit) . ' - ';
                        } ?>
                        Manajemen Risiko
                    </b></th>
            </tr>
        <?php } ?>
        <tr>
            <th class="bg-blue" style="" rowspan="2">No</th>
            <?php foreach ($header1 as $idkey => $r) { ?>
                <th class="bg-blue" style="" rowspan="<?= $r['rowspan'] ?>" colspan="<?= $r['colspan'] ?>"><?= $r['label'] ?></th>
            <?php } ?>
        </tr>
        <tr>
            <?php foreach ($header2 as $idkey => $r) { ?>
                <th class="bg-blue" style="" rowspan="<?= $r['rowspan'] ?>" colspan="<?= $r['colspan'] ?>"><?= $r['label'] ?></th>
            <?php } ?>
        </tr>
        <tr>
            <th class="bg-blue" style=""></th>
            <?php $no = 1;
            foreach ($header1 as $idkey => $r) {
                for ($ij = 0; $ij < $r['colspan']; $ij++) { ?>
                    <th class="bg-blue" style=""><?= $no++ ?></th>
            <?php }
            } ?>
        </tr>
    </thead>
    <tbody>
        <?php

        if (in_array('level_peluang_inheren', $paramheader))
            $rating['i'] = 1;

        if (in_array('level_peluang_paskakontrol', $paramheader))
            $rating['c'] = 1;

        if (in_array('level_peluang_residual', $paramheader))
            $rating['r'] = 1;

        $rs = $this->data['rows'];

        $rowsmitigasi = array();
        $rowscontrol = array();
        $rowskri = array();

        foreach ($rs as $r) {
            $rowsmitigasi[$r['id_peluang']][$r['id_mitigasi']] = $r;
            $rowscontrol[$r['id_peluang']][$r['id_control']] = $r;
            $rowskri[$r['id_peluang']][$r['id_kri']] = $r;
        }

        if ($rowscontrol) {
            $rs = array();
            foreach ($rowscontrol as $id_peluang => $row) {
                if (count($row) > count($rowsmitigasi[$id_peluang])) {
                    foreach ($row as $id_control => $r) {
                        $r['id_control'] = $id_control;

                        $t = @each($rowsmitigasi[$id_peluang]);
                        $r1 = $t['value'];
                        foreach ($norowspan_mitigasi as $k1 => $v2) {
                            $r[$v2] = $r1[$v2];
                        }
                        $r['id_mitigasi'] = $r1['id_mitigasi'];

                        $t = @each($rowskri[$id_peluang]);
                        $r1 = $t['value'];
                        foreach ($norowspan_kri as $k1 => $v2) {
                            $r[$v2] = $r1[$v2];
                        }
                        $r['id_kri'] = $r1['id_kri'];

                        $rs[] = $r;
                    }
                } else if (count($rowskri[$id_peluang]) > count($rowsmitigasi[$id_peluang])) {
                    foreach ($rowskri[$id_peluang] as $id_kri => $r) {
                        $r['id_kri'] = $id_kri;

                        $t = @each($rowsmitigasi[$id_peluang]);
                        $r1 = $t['value'];
                        foreach ($norowspan_mitigasi as $k1 => $v2) {
                            $r[$v2] = $r1[$v2];
                        }
                        $r['id_mitigasi'] = $r1['id_mitigasi'];

                        $t = @each($row);
                        $r1 = $t['value'];
                        foreach ($norowspan_control as $k1 => $v2) {
                            $r[$v2] = $r1[$v2];
                        }
                        $r['id_control'] = $r1['id_control'];

                        $rs[] = $r;
                    }
                } else {
                    foreach ($rowsmitigasi[$id_peluang] as $id_mitigasi => $r) {
                        $r['id_mitigasi'] = $id_mitigasi;

                        $t = @each($row);
                        $r1 = $t['value'];
                        if ($norowspan_control)
                            foreach ($norowspan_control as $k1 => $v2) {
                                $r[$v2] = $r1[$v2];
                            }
                        $r['id_control'] = $r1['id_control'];

                        $t = @each($rowskri[$id_peluang]);
                        $r1 = $t['value'];
                        if ($norowspan_kri)
                            foreach ($norowspan_kri as $k1 => $v2) {
                                $r[$v2] = $r1[$v2];
                            }
                        $r['id_kri'] = $r1['id_kri'];

                        $rs[] = $r;
                    }
                }
            }
        }

        if ($aksesview) {
            $skipcal = array(
                'kode_peluang',
                'opp_owner',
                'status_peluang',
                'level_peluang_inheren',
                'level_peluang_paskakontrol',
                'level_peluang_actual_real',
                'level_peluang_actual',
                'level_peluang_residual',
                'id_pengukuran',
                'id_pengukuranm',
                'cba_mitigasi'
            );

            #menghitung kelengkapan data
            $temparr = array();
            $rt = array();
            $ri = array();
            foreach ($rs as $r) {
                // $paramfix1 = $paramfix;
                $paramfix1 = $paramheader;

                if (!in_array($r['id_tingkat_agregasi_peluang'], array("2", "3", "4")))
                    unset($paramfix1['peluang_induk']);
                else if ($r['id_peluang_parent_lain'])
                    $r['peluang_induk'] = "Risiko lainnya";

                foreach ($paramfix1 as $k1 => $k) {
                    if (in_array($k, $skipcal))
                        continue;

                    if (in_array($k, $norowspan_kri)) {
                        $rt[$r['id_peluang']]['kri'][$r['id_kri']][$k] = $r[$k];
                        if ($r[$k])
                            $ri[$r['id_peluang']]['kri'][$r['id_kri']][$k] = $r[$k];
                    } elseif (in_array($k, $norowspan_control)) {
                        $rt[$r['id_peluang']]['control'][$r['id_control']][$k] = $r[$k];
                        if ($r[$k])
                            $ri[$r['id_peluang']]['control'][$r['id_control']][$k] = $r[$k];
                    } elseif (in_array($k, $norowspan_mitigasi)) {
                        $rt[$r['id_peluang']]['mitigasi'][$r['id_mitigasi']][$k] = $r[$k];
                        if ($r[$k])
                            $ri[$r['id_peluang']]['mitigasi'][$r['id_mitigasi']][$k] = $r[$k];
                    } else {
                        $rt[$r['id_peluang']]['peluang'][$k] = $r[$k];
                        if ($r[$k])
                            $ri[$r['id_peluang']]['peluang'][$k] = $r[$k];
                    }
                }
                $temparr[$r['id_peluang']] = $rt[$r['id_peluang']];
            }
        }

        $rowspan = array();
        foreach ($rs as $r) {
            $rowspan[$r['id_peluang']]++;
        }


        $no = 1;
        if (!$paramheader) $paramheader = array();
        $id_peluang = 0;

        $top_inheren = array();
        $top_paska_kontrol = array();
        $top_paska_mitigasi = array();

        foreach ($rs as $r => $val) {

            $rp = $rowspan[$val['id_peluang']];

            $is_first = false;
            echo "<tr>";
            if ($id_peluang != $val['id_peluang']) {
                $is_first = true;
                $top_inheren[$val['inheren_dampak1']][$val['inheren_kemungkinan1']][] = $no;
                $top_paska_kontrol[$val['control_dampak_penurunan']][$val['control_kemungkinan_penurunan']][] = $no;
                $top_actual[$val['current_opp_dampak']][$val['current_opp_kemungkinan']][] = $no;
                $top_residual_target[$val['residual_target_dampak']][$val['residual_target_kemungkinan']][] = $no;
                echo "<td style='text-align:center' rowspan='$rp' valign='top'>" . $no++ . "</td>";
            }

            foreach ($paramheader as $k1 => $k) {
                $rp1 = $rp;

                if ($norowspan)
                    if (in_array($k, $norowspan))
                        $rp1 = 0;

                if ($rp1 && $id_peluang == $val['id_peluang'])
                    continue;

                $addrowspan = "";

                if ($rp1)
                    $addrowspan = "rowspan='$rp1'";

                $addrowspan .= " valign='top'";
                $v = $val[$k];
                if ($v == null) {
                    if ($k == "peluang_induk") {
                        if ($val['id_peluang_parent_lain']) {
                            echo "<td $addrowspan>Risiko lainnya</td>";
                        } else {
                            echo "<td $addrowspan></td>";
                        }
                    } elseif ($k == "biaya_mitigasi") {
                        echo "<td $addrowspan>" . rupiahAngka((float)$v) . "</td>";
                    } else {
                        echo "<td $addrowspan></td>";
                    }
                } elseif ($type_header[$k]) {
                    if (is_array($type_header[$k])) {
                        if ($list = $type_header[$k]['list']) {
                            echo "<td $addrowspan>" . $list[$v] . "</td>";
                        }
                    } else {
                        $type = $type_header[$k];
                        if ($type == 'date') {
                            echo "<td $addrowspan>" . Eng2Ind($v) . "</td>";
                        } elseif ($type == 'rupiah') {
                            echo "<td $addrowspan>" . rupiahAngka((float)$v) . "</td>";
                        } elseif ($type == 'rating') {
                            echo "<td $addrowspan style='background-color:$warnarr[$v]'>$v</td>";
                        } elseif ($type == 'check') {
                            echo "<td $addrowspan style='font-weight:bold; text-align:center;'>" . ($v ? '✓' : null) . "</td>";
                        } else {
                            echo "<td $addrowspan>$v</td>";
                        }
                    }
                } else {
                    if ($k == 'hasil_kri') {
                        $nilai = $v;

                        $warna = '#fff';
                        if (isset($nilai) && $nilai !== '') {
                            if ($val['polaritas'] == '+') {
                                if ($nilai < $val['batas_bawah']) {
                                    $warna = 'red';
                                } elseif ($nilai > $val['batas_atas'] && $val['batas_atas']) {
                                    $warna = 'red';
                                } elseif ($nilai >= $target_mulai && (!$target_sampai || $target_sampai == $target_mulai)) {
                                    $warna = 'green';
                                } elseif ($nilai >= $target_mulai && $nilai <= $target_sampai) {
                                    $warna = 'green';
                                } else {
                                    $warna = 'yellow';
                                }
                            } else {
                                if ($nilai > $val['batas_bawah']) {
                                    $warna = 'red';
                                } elseif ($nilai < $val['batas_atas'] && $val['batas_atas']) {
                                    $warna = 'red';
                                } elseif ($nilai <= $target_mulai && (!$target_sampai || $target_sampai == $target_mulai)) {
                                    $warna = 'green';
                                } elseif ($nilai <= $target_mulai && $nilai >= $target_sampai) {
                                    $warna = 'green';
                                } else {
                                    $warna = 'yellow';
                                }
                            }
                        }
                        echo "<td $addrowspan style='background-color:$warna;'>$v</td>";
                    } elseif ($k == 'status_peluang') {
                        if ($v == '0') {
                            echo "<td $addrowspan style='background-color:#ddd;'>Close</td>";
                        } else {
                            echo "<td $addrowspan style='background-color:#58b051;'>Open</td>";
                        }
                    } else if ($k == "peluang_induk") {
                        if ($val['id_peluang_parent_lain']) {
                            echo "<td $addrowspan>Risiko lainnya</td>";
                        } else {
                            echo "<td $addrowspan>$v</td>";
                        }
                    } else
                        echo "<td $addrowspan>$v</td>";
                }
            }

            if ($aksesview & $is_first) {
                echo "<td style='text-align:center' rowspan='$rp' valign='top'>";
                $rr = $temparr[$val['id_peluang']];
                $t = 0;
                $i = 0;
                if ($rr['peluang'])
                    foreach ($rr['peluang'] as $kb => $vb) {
                        $t++;
                        if ($vb !== null && $vb !== "")
                            $i++;
                    }
                if ($rr['kri'])
                    foreach ($rr['kri'] as $rws) {
                        foreach ($rws as $kb => $vb) {
                            $t++;
                            if ($vb !== null && $vb !== "")
                                $i++;
                        }
                    }
                if ($rr['control'])
                    foreach ($rr['control'] as $rws) {
                        foreach ($rws as $kb => $vb) {
                            $t++;
                            if ($vb !== null && $vb !== "")
                                $i++;
                        }
                    }
                if ($rr['mitigasi'])
                    foreach ($rr['mitigasi'] as $rws) {
                        foreach ($rws as $kb => $vb) {
                            $t++;
                            if ($vb !== null && $vb !== "")
                                $i++;
                        }
                    }
                echo round(($i / $t) * 100, 2);
                echo "</td>";
            }

            echo "</tr>";
            $id_peluang = $val['id_peluang'];
        }
        if (!isset($rs)) {
            echo "<tr><td colspan='" . (count($paramheader) + 1) . "'>Data kosong</td></tr>";
        }
        ?>

    </tbody>
</table>

<?php
//include "_matrix.php"; 
?>

<style>
    h4 small {
        color: #ccc;
    }
</style>