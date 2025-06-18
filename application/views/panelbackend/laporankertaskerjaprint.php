<script src="<?php echo base_url() ?>assets/plugins/jquery/jquery.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<table class="tableku1" id="export" border="1" style="border: 0px;">
  <thead style="border: 0px;">
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
    // dpr($paramheader, 1);
    if (in_array('level_risiko_inheren', $paramheader))
      $rating['i'] = 1;

    if (in_array('level_risiko_paskakontrol', $paramheader))
      $rating['c'] = 1;

    if (in_array('level_risiko_residual', $paramheader))
      $rating['r'] = 1;

    $rs = $this->data['rows'];
    // dpr($this->data['rows']);

    $rowsmitigasi = array();
    $rowscontrol = array();
    $rowskri = array();

    foreach ($rs as $r) {

      $rowsmitigasi[$r['id_risiko']][$r['id_mitigasi']] = $r;
      $rowscontrol[$r['id_risiko']][$r['id_control']] = $r;
      $rowskri[$r['id_risiko']][$r['id_kri']] = $r;
    }

    if ($rowscontrol) {
      // dpr($rowscontrol);
      $rs = array();
      foreach ($rowscontrol as $id_risiko => $row) {
        if (count($row) > count($rowsmitigasi[$id_risiko])) {
          foreach ($row as $id_control => $r) {
            $r['id_control'] = $id_control;

            $t = @each($rowsmitigasi[$id_risiko]);
            $r1 = $t['value'];
            foreach ($norowspan_mitigasi as $k1 => $v2) {
              $r[$v2] = $r1[$v2];
            }
            $r['id_mitigasi'] = $r1['id_mitigasi'];

            $t = @each($rowskri[$id_risiko]);
            $r1 = $t['value'];
            foreach ($norowspan_kri as $k1 => $v2) {
              $r[$v2] = $r1[$v2];
            }
            $r['id_kri'] = $r1['id_kri'];

            $rs[] = $r;
          }
        } else if (count($rowskri[$id_risiko]) > count($rowsmitigasi[$id_risiko])) {
          foreach ($rowskri[$id_risiko] as $id_kri => $r) {
            $r['id_kri'] = $id_kri;

            $t = @each($rowsmitigasi[$id_risiko]);
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
          foreach ($rowsmitigasi[$id_risiko] as $id_mitigasi => $r) {
            $r['id_mitigasi'] = $id_mitigasi;

            $t = @each($row);
            $r1 = $t['value'];
            if ($norowspan_control)
              foreach ($norowspan_control as $k1 => $v2) {
                $r[$v2] = $r1[$v2];
              }
            $r['id_control'] = $r1['id_control'];

            $t = @each($rowskri[$id_risiko]);
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
        'kode_risiko',
        'risk_owner',
        'status_risiko',
        'level_risiko_inheren',
        'level_risiko_paskakontrol',
        'level_risiko_actual_real',
        'level_risiko_actual',
        'level_risiko_residual',
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

        if (!in_array($r['id_tingkat_agregasi_risiko'], array("2", "3", "4")))
          unset($paramfix1['risiko_induk']);
        else if ($r['id_risiko_parent_lain'])
          $r['risiko_induk'] = "Risiko lainnya";

        foreach ($paramfix1 as $k1 => $k) {
          if (in_array($k, $skipcal))
            continue;

          if (in_array($k, $norowspan_kri)) {
            $rt[$r['id_risiko']]['kri'][$r['id_kri']][$k] = $r[$k];
            if ($r[$k])
              $ri[$r['id_risiko']]['kri'][$r['id_kri']][$k] = $r[$k];
          } elseif (in_array($k, $norowspan_control)) {
            $rt[$r['id_risiko']]['control'][$r['id_control']][$k] = $r[$k];
            if ($r[$k])
              $ri[$r['id_risiko']]['control'][$r['id_control']][$k] = $r[$k];
          } elseif (in_array($k, $norowspan_mitigasi)) {
            $rt[$r['id_risiko']]['mitigasi'][$r['id_mitigasi']][$k] = $r[$k];
            if ($r[$k])
              $ri[$r['id_risiko']]['mitigasi'][$r['id_mitigasi']][$k] = $r[$k];
          } else {
            $rt[$r['id_risiko']]['risiko'][$k] = $r[$k];
            if ($r[$k])
              $ri[$r['id_risiko']]['risiko'][$k] = $r[$k];
          }
        }
        $temparr[$r['id_risiko']] = $rt[$r['id_risiko']];
      }
    }

    $rowspan = array();
    foreach ($rs as $r) {
      $rowspan[$r['id_risiko']]++;
    }


    $no = 1;
    if (!$paramheader) $paramheader = array();
    $id_risiko = 0;

    $top_inheren = array();
    $top_paska_kontrol = array();
    $top_paska_mitigasi = array();

    foreach ($rs as $r => $val) {

      $rp = $rowspan[$val['id_risiko']];

      $is_first = false;
      echo "<tr>";
      if ($id_risiko != $val['id_risiko']) {
        $is_first = true;
        $top_inheren[$val['inheren_dampak1']][$val['inheren_kemungkinan1']][] = $no;
        $top_paska_kontrol[$val['control_dampak_penurunan']][$val['control_kemungkinan_penurunan']][] = $no;
        $top_actual[$val['current_risk_dampak']][$val['current_risk_kemungkinan']][] = $no;
        $top_residual_target[$val['residual_target_dampak']][$val['residual_target_kemungkinan']][] = $no;
        echo "<td style='text-align:center' rowspan='$rp' valign='top'>" . $no++ . "</td>";
      }

      foreach ($paramheader as $k1 => $k) {
        $rp1 = $rp;

        if ($norowspan)
          if (in_array($k, $norowspan))
            $rp1 = 0;

        if ($rp1 && $id_risiko == $val['id_risiko'])
          continue;

        $addrowspan = "";

        if ($rp1)
          $addrowspan = "rowspan='$rp1'";

        $addrowspan .= " valign='top'";
        $v = $val[$k];
        if ($v == null) {
          if ($k == "risiko_induk") {
            if ($val['id_risiko_parent_lain']) {
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
              echo "<td $addrowspan style='background-color:" . $warnarr[(int)$v] . "'>" . $v * -1 . "</td>";
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
          } elseif ($k == 'status_risiko') {
            if ($v == '0') {
              echo "<td $addrowspan style='background-color:#ddd;'>Close</td>";
            } else {
              echo "<td $addrowspan style='background-color:#58b051;'>Open</td>";
            }
          } else if ($k == "risiko_induk") {
            if ($val['id_risiko_parent_lain']) {
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
        $rr = $temparr[$val['id_risiko']];
        $t = 0;
        $i = 0;
        if ($rr['risiko'])
          foreach ($rr['risiko'] as $kb => $vb) {
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
      $id_risiko = $val['id_risiko'];
    }
    if (!isset($rs)) {
      echo "<tr><td colspan='" . (count($paramheader) + 1) . "'>Data kosong</td></tr>";
    }
    ?>

  </tbody>
</table>

<?php if ($penandatangan) { ?>
  <table align="right" style="margin-top: 20px; border:1px solid #555;">
    <tr>
      <td style="border:1px solid #555;" colspan="<?= $colspan24 ?>">Dibuat Oleh:</td>
      <td style="border:1px solid #555;" colspan="2">Diperiksa Oleh:</td>
      <td style="border:1px solid #555;" colspan="<?= $colspanlast ?>">Disetujui Oleh:</td>
    </tr>
    <tr>
      <td style="border:1px solid #555;padding: 10px 50px 10px; text-align: center;">
        <i class="home-logo" style="background-image: url(<?= base_url() ?>uploads/<?= $file_qr[$penandatangan['id_user']] ?>); width: 100px; height: 100px;"></i>
        <br>
        <ins><?= $penandatangan['nama_user'] ?></ins><br><?= $penandatangan['nama_jabatan_user'] ?>
      </td>
      <td style="border:1px solid #555;padding: 10px 50px 10px; text-align: center;">
        <i class="home-logo" style="background-image: url(<?= base_url() ?>uploads/<?= $file_qr[$penandatangan['id_owner']] ?>); width: 100px; height: 100px;"></i>
        <br>
        <ins><?= $penandatangan['nama_owner'] ?></ins><br><?= $penandatangan['nama_jabatan_owner'] ?>
      </td>
      <td style="border:1px solid #555;padding: 10px 50px 10px; text-align: center;">
        <i class="home-logo" style="background-image: url(<?= base_url() ?>uploads/<?= $file_qr[$penandatangan['id_upmr']] ?>); width: 100px; height: 100px;"></i>
        <br>
        <ins><?= $penandatangan['nama_upmr'] ?></ins><br><?= $penandatangan['nama_jabatan_upmr'] ?>
      </td>

      <td style="border:1px solid #555;padding: 10px 50px 10px; text-align: center;">

        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <ins><?= $tertinggi['nama'] ?></ins><br><?= $tertinggi['jabatan'] ?>
      </td>
    </tr>
    <!-- <tr>
    <tr> -->

    <!-- <?php //foreach ($penandatangan as $v) {
          //foreach ($id_scorecard as $d) {
          //if ($v[$d]) { 
          ?>
            <td style="border:1px solid #555;padding: 10px 50px 10px; text-align: center;">
              <i class="home-logo" style="background-image: url(<?= base_url() ?>uploads/<?= $v[$d]['file'] ?>); width: 100px; height: 100px;"></i>
              <br>
              <ins><?= '.' // $v[$d]['nama'] 
                    ?></ins><br><?= '.' // $v[$d]['jabatan'] 
                                ?>
            </td>
      <?php // }
      // }
      // } 
      ?> -->

    <?php foreach ($penandatangan as $dpp => $dd) {
      // if ($dpp == 'id')
      //   dpr($file_qr[$dd]);
      // dpr($dd); 
    ?>
      <!-- <td style="border:1px solid #555;padding: 10px 50px 10px; text-align: center;">
          <i class="home-logo" style="background-image: url(<?= base_url() ?>uploads/<?= $v[$d]['file'] ?>); width: 100px; height: 100px;"></i>
          <br>
          <ins><?= $v[$d]['nama'] ?></ins><br><?= $v[$d]['jabatan'] ?>
        </td> -->
    <?php } ?>
    <!-- <td style="border:1px solid #555;padding: 100px 50px 10px; text-align: center;">paling atas</td> -->
    <!-- </tr> -->
    <!-- <tr> -->
  </table>
<?php } ?>
<?php
//include "_matrix.php"; 
?>

<style>
  h4 small {
    color: #ccc;
  }
</style>