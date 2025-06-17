<?php
if (!$top_inheren)
  $top_inheren = array();

foreach ($top_inheren as $idkey => $value) {
  foreach ($value as $k => $v) {
    $top_inheren[$idkey][$k] = array_unique($v);
  }
}

if (!$top_paska_kontrol)
  $top_paska_kontrol = array();

foreach ($top_paska_kontrol as $idkey => $value) {
  foreach ($value as $k => $v) {
    $top_paska_kontrol[$idkey][$k] = array_unique($v);
  }
}
if (!$top_actual)
  $top_actual = array();

foreach ($top_actual as $idkey => $value) {
  foreach ($value as $k => $v) {
    $top_actual[$idkey][$k] = array_unique($v);
  }
}
if (!$top_residual_target)
  $top_residual_target = array();

foreach ($top_residual_target as $idkey => $value) {
  foreach ($value as $k => $v) {
    $top_residual_target[$idkey][$k] = array_unique($v);
  }
}

$rs_matrix = $this->data['mtoppmatrix'];
$data = array(array());
foreach ($rs_matrix as $k => $v) {
  $data[$v['id_dampak']][$v['id_kemungkinan']] = $v;
}

$rs_dampak = $this->data['mtoppdampak'];
$rs_kemungkinan = $this->data['mtoppkemungkinan'];

?>


<table style="width: 630px;" id="divmatrixopp">
  <tr>
    <td style="width: max-content;">
      <table class="tbmatrix" style="width: 500px;">
        <?php
        $iii = 0;
        $tooltip1 = array();
        echo "<tr>";
        echo "<tr>";
        echo "<td rowspan='5' style='font-weight:bold;position:relative;width:25px'><div style='position:absolute;right: 19px;top: 260px;width: 0px;-ms-transform: rotate(-90deg);-webkit-transform: rotate(-90deg);transform: rotate(-90deg);height: 0px;word-wrap: normal;'>TINGKAT&nbsp;KEMUNGKINAN</div></td>";
        foreach ($rs_kemungkinan as $r_k => $val_k) {
          echo "<td align='center' style='width: 25px;text-align:center;vertical-align:middle;font-weight:bold'>$val_k[nama]</td>";
          echo "<td style='width: 25px;text-align:center;font-weight:bold;vertical-align:middle'>$val_k[rating]</td>";
          foreach ($rs_dampak as $r_d => $val_d) {
            $iii++;
            $bg = $data[$val_d['id_dampak']][$val_k['id_kemungkinan']]['warna'];
            $css = $data[$val_d['id_dampak']][$val_k['id_kemungkinan']]['css'];
            $tingkat_peluang = "";
            // $tingkat_peluang = $data[$val_d['id_dampak']][$val_k['id_kemungkinan']]['nama'];
            $nokotak = 0;
            $maxdot = 8;
            $div = "";
            $div1 = "";
            if ($top_inheren[$val_d['id_dampak']][$val_k['id_kemungkinan']] && $ratingpeluang['i']) {
              foreach ($top_inheren[$val_d['id_dampak']][$val_k['id_kemungkinan']] as $n) {
                $nokotak++;
                if ($id_peluang_onlyone)
                  $cee = 'dot zoom-1';
                else
                  $cee = 'dot';

                $d = "<div title=\"" . $noarr[$n]['nama'] . "\" style='font-size: 10px;width:17px;height:17px;float:left;padding:1px;margin:2px;background-color:#333;border:1px solid black' class='$cee'><a style='color:#fff;font-weight:bold;' href='" . site_url("panelbackend/opp_peluang/detail/" . $noarr[$n]['id_scorecard'] . "/" . $noarr[$n]['id_peluang']) . "' target='_BLANK'>$n</a></div>";
                if ($nokotak > $maxdot)
                  $div1 .= $d;
                else
                  $div .= $d;
              }
            }
            if ($top_paska_kontrol[$val_d['id_dampak']][$val_k['id_kemungkinan']] && $ratingpeluang['c']) {
              foreach ($top_paska_kontrol[$val_d['id_dampak']][$val_k['id_kemungkinan']] as $n) {
                $nokotak++;
                if ($id_peluang_onlyone)
                  $cee = 'dot zoom-2';
                else
                  $cee = 'dot zoom-loop';

                $d = "<div title=\"" . $noarr[$n]['nama'] . "\" style='font-size: 10px;width:17px;height:17px;float:left;padding:1px;margin:2px;background-color:#666;border:1px solid black'  class='$cee'><a style='color:#fff;font-weight:bold;' href='" . site_url("panelbackend/opp_peluang/detail/" . $noarr[$n]['id_scorecard'] . "/" . $noarr[$n]['id_peluang']) . "' target='_BLANK'>$n</a></div>";
                if ($nokotak > $maxdot)
                  $div1 .= $d;
                else
                  $div .= $d;
              }
            }
            if ($top_actual[$val_d['id_dampak']][$val_k['id_kemungkinan']] && $ratingpeluang['a']) {
              foreach ($top_actual[$val_d['id_dampak']][$val_k['id_kemungkinan']] as $n) {
                $nokotak++;
                if ($id_peluang_onlyone)
                  $cee = 'dot zoom-2';
                else
                  $cee = 'dot zoom-loop';

                $d = "<div title=\"" . $noarr[$n]['nama'] . "\" style='font-size: 10px;width:17px;height:17px;float:left;padding:1px;margin:2px;background-color:#fff;border:1px solid black'  class='$cee'><a style='font-weight:bold;' href='" . site_url("panelbackend/opp_peluang/detail/" . $noarr[$n]['id_scorecard'] . "/" . $noarr[$n]['id_peluang']) . "' target='_BLANK'>$n</a></div>";
                if ($nokotak > $maxdot)
                  $div1 .= $d;
                else
                  $div .= $d;
              }
            }

            echo "<td class='bg-$bg' style='border:1px solid #555;background-color:$bg; padding:1px;$css' height='75px' width='75px' align='center' valign='middle'><div style='position:relative;height:75px;width:75px; vertical-align:middle; text-align:center;padding:30px 0px;'>$tingkat_peluang
                  <div style='position:absolute;top:5px;right:5px;'>";
            echo $div;
            if ($div1) {
              $div1 .= "<div style='clear:both'></div>";
              echo '<div style="font-size: 10px;width:17px;height:17px;float:left;padding:1px;margin:2px;background-color:blue;border:1px solid bluecursor: pointer;" rel="tooltip" title="' . $div1 . '" data-html="true" data-placement="right" class="light-tooltip more' . $iii . '" data-container=".more' . $iii . '">+</div>';

              $tooltip1[$val_k['kode'] . $val_d['kode']]['bg'] = $bg;
              $tooltip1[$val_k['kode'] . $val_d['kode']]['div'] = $div1;
            }
            echo "</div></div></td>";
          }
          echo "</tr>";
        }
        echo "</tr>";
        echo "<tr>";
        echo "<td colspan='3' rowspan='3'></td>";
        foreach ($rs_dampak as $r_d => $val_d) {
          echo "<td style='font-weight:bold;text-align:center'>$val_d[rating]</td>";
        }
        echo "</tr>";
        echo "<tr>";
        foreach ($rs_dampak as $r_d => $val_d) {
          echo "<td style='font-weight:bold;text-align:center;vertical-align:middle'>$val_d[nama]</td>";
        }
        echo "</tr>";
        echo "<tr>";
        echo "<td colspan='5' style='font-weight:bold;text-align:center'>TINGKAT DAMPAK</td>";
        echo "</tr>";
        ?>
      </table>
    </td>
    <td style="vertical-align: top;">
      <table style="margin-left:10px; margin-right:0px; background:#fff; width:120px;">
        <tbody>
          <tr>
            <td colspan="2" style="padding-bottom:10px;font-weight:500">Tingkat Peluang</td>
          </tr>
          <?php
          rsort($tingkatpeluangarr);
          foreach ($tingkatpeluangarr as $r) { ?>
            <tr>
              <td style="width: max-content; vertical-align: top; padding-bottom:10px;">
                <div style="height:17px;width:17px;margin-right:10px;background-color:<?= $r['warna'] ?>;"></div>
              </td>
              <td style="font-size: 12px; padding-bottom:10px;"><?= $r['nama'] ?></td>
            </tr>
          <?php } ?>
          <tr>
            <td colspan="2" style="padding-bottom:10px;font-weight:500"></td>
          </tr>
          <tr>
            <td colspan="2" style="padding-bottom:10px;font-weight:500">Level Peluang</td>
          </tr>
          <?php if ($ratingpeluang['i']) { ?>
            <tr>
              <td style="width: max-content; vertical-align: top; padding-bottom:10px;">
                <div style="height:17px;width:17px;margin-right:10px;background-color:#333;border:2px solid #333;"></div>
              </td>
              <td style="font-size: 12px; padding-bottom:10px;">Tingkat Peluang</td>
            </tr>

          <?php }
          if ($ratingpeluang['c']) { ?>
            <tr>
              <td style="width: max-content; vertical-align: top; padding-bottom:10px;">
                <div style="height:17px;width:17px;margin-right:10px;background-color:#666;border:2px solid #666;"></div>
              </td>
              <td style="font-size: 12px; padding-bottom:10px;">Current opp</td>
            </tr>

          <?php }
          if ($ratingpeluang['a']) { ?>
            <tr>
              <td style="width: max-content; vertical-align: top; padding-bottom:10px;">
                <div style="height:17px;width:17px;margin-right:10px;background-color:#fff;border:2px solid #ddd;"></div>
              </td>
              <td style="font-size: 12px; padding-bottom:10px;">Residual opp</td>
            </tr>
          <?php } ?>

        </tbody>
      </table>
    </td>
  </tr>
</table>


<style>
  .tbmatrix {
    background-color: #fff;
  }

  .tbmatrix,
  .tbmatrix tr,
  .tbmatrix tr td,
  .tbmatrix tr th {
    border: 1px solid #dfdfdf !important;
    font-size: 11px;
    vertical-align: middle;
    padding: 5px;
    font-family: 'Lato', Arial, Tahoma, sans-serif !important;
  }

  .tbmatrix tr td div.zoom-loop {
    color: #000 !important;
  }
</style>