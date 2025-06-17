<div class="row" style="margin: 0px auto; width:100%">
  <div class="col-lg-12" style="margin-top:-7px">
    <table class="tableku1 table table-bordered" id="export" style="margin-top:15px;">
      <thead>
        <tr>
          <th style="width:1px;text-align:center;background-color:#034485;color:#eee;" rowspan="2">NO</th>
          <th style="text-align:center;background-color:#034485;color:#eee; width: 30%;" rowspan="2">RISIKO</th>
          <th style="text-align:center;background-color:#034485;color:#eee; width: 20%;" rowspan="2">RISK OWNER</th>
          <th style="text-align:center;background-color:#034485;color:#eee; width: 20%;" colspan="<?= count($rating) ?>">LEVEL RISIKO</th>
          <th style="text-align:center;background-color:#034485;color:#eee; width: 30%;" rowspan="2">Nama Audit</th>
        </tr>
        <tr>
          <th style="width:75px;text-align:center;background-color:#034485;color:#eee ;">TARGETED RESIDUAL RISK</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $rs_matrix = $this->data['mtriskmatrix'];
        $data = array(array());
        foreach ($rs_matrix as $k => $v) {
          $data[$v['id_dampak']][$v['id_kemungkinan']] = $v;
        }

        $rs = $this->data['rows'];
        // dpr($rs);
        $no = 1;

        foreach ($rs as $r => $val) {
          $top_residual_target[$val['residual_target_dampak']][$val['residual_target_kemungkinan']][] = $no;

          echo "<tr>";
          echo "<td style='text-align:center'>" . $no++ . "</td>";
          echo "<td><a href='" . site_url("panelbackend/risk_risiko/detail/$val[id_scorecard]/$val[id_risiko]") . "' target='_BLANK'>$val[nama]</a></td>";
          echo "<td style='text-align:center'>$val[risk_owner]</td>";

          if ($rating['r']) {
            $bg = $data[$val['residual_target_dampak']][$val['residual_target_kemungkinan']]['warna'];
            echo "<td align='center' style='background-color:$bg;color:#333 !important;' >$val[level_residual_evaluasi]</td>";
          }


          $from = UI::createTextArea("nama_audit[$val[id_risiko]]", $row['nama_audit'][$val['id_risiko']], '3', '5', $edited, 'form-control contents-mini', "");
          echo UI::createFormGroup("<td>" . $from . "</td>", $rules["nama_audit"], "nama_audit", $label = null, true);

          echo "</tr>";
        }
        if (!($rs)) {
          echo "<tr><td colspan='8'>Data kosong</td></tr>";
        }
        ?>
      </tbody>
    </table>

    <?php
    if ($edited) { ?>
      <?php
      $from = UI::showButtonMode("save", null, $edited);
      echo UI::createFormGroup($from, NULL, NULL, NULL, true, 4, $edited);
      ?>
    <?php } ?>

    <br />
  </div>


</div>
<style>
  .notshow {
    margin-top: 10px;
  }

  @media print {
    .notshow {
      display: none;
    }

    body {
      margin: 0px;
      padding: 10px 5px;
    }

    html {
      margin: 0px;
      padding: 0px;
    }
  }

  #container {
    width: 100%;
    font-size: 14px;
    font-family: Arial, Helvetica, sans-serif;
  }

  <?php if ($report) { ?>td,
  th {
    padding: 3px;
    font-size: 12px;
    vertical-align: text-center;
  }

  <?php } else { ?><?php } ?>

  /* .h4, .h5, .h6, h4, h5, h6, hr {
    margin-top: 5px;
    margin-bottom: 5px;
} */
  .tableku {
    margin-top: 20px;
    width: 100%;
    border: 1px solid #555;
  }

  .tableku td {
    border: 1px solid #555;
    padding: 0px 3px;
    vertical-align: top;
  }

  .tableku thead th {
    border: 1px solid #555;
    border-bottom: 2px solid #555;
    padding: 0px 3px;
  }

  .tableku th {
    border: 1px solid #555;
    padding: 0px 3px;
  }

  .tableku thead,
  .tableku1 thead {
    border: 1px solid #555;
    page-break-before: always;
  }

  .tableku1 {
    margin-top: 10px;
    width: 100%;
    border: 1px solid #555;
  }

  .tableku1 td {
    border: 1px solid #555 !important;
    padding: 3px 5px;
    vertical-align: top;
    font-size: 12px !important;
  }

  .tableku1 thead th {
    border: 1px solid #555;
    border-bottom: 2px solid #555;
    padding: 3px 5px;
    text-align: center;
  }

  .tableku1 th {
    border: 1px solid #555;
    padding: 0px 3px;
    text-align: center;
    font-size: 12px !important;
  }
</style>