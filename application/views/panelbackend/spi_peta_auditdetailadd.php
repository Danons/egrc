<div class="col-sm-12">

  <?php


  $form = "<table><tr>
	<td width='100px'>" . UI::createTextNumber('tahun', ($row['tahun'] ? $row['tahun'] : date('Y')), '4', '4', true, $class = 'form-control ') . "</td>
	</tr></table>";
  echo UI::FormGroup(array(
    'form' => $form,
    'sm_label' => 2,
    'label' => 'Tahun'
  ));
  ?>

  <?php
  // $form = UI::createSelect('id_sasaran', $sasaranarr, $row['id_sasaran'], true, $class = 'form-control select2', "style='width:100%;'");
  // echo UI::FormGroup(array(
  //   'form' => $form,
  //   'sm_label' => 2,
  //   'label' => 'Sasaran'
  // ));
  ?>

  <?php
  // $form = UI::createSelect('id_kajian_risiko',$mtjeniskajianrisikoarr,$row['id_kajian_risiko'],true,$class='form-control select2',"onchange='goSubmit(\"set_value\")'");
  // echo UI::FormGroup(array(
  // 	'form'=>$form,
  // 	'sm_label'=>2,
  // 	'label'=>'Kajian Risiko'
  // 	));
  ?>

  <div class="boxKajianResiko">

    <?php
    $form = require_once("_scorecard.php");


    echo UI::FormGroup(array(
      'form' => $form,
      'sm_label' => 2,
      'label' => 'Kajian Risiko',
    ));
    ?>

  </div>


  <?php
  $form = UI::createTextNumber('top', ($row['top'] ? $row['top'] : 10), '4', '4', true, $class = 'form-control ');
  echo UI::FormGroup(array(
    'form' => $form,
    'sm_label' => 2,
    'label' => 'Top'
  ));
  ?>

  <button onclick="goSubmit('set_value')" class="btn-success btn">Preview</button>
</div>

<!-- munculkan table -->
<!-- REEEEEEEPOOOOOOORRRRRRRTTTTTTT -->


<?php if ($report) { ?>
  <div class="row" style="margin: 0 auto; width:100%">
    <div class="col-lg-12" style="margin-left: 0px !important; padding-left: 0px !important">
      <table class="tableku1" id="export">
        <thead>
          <tr>
            <th style="text-align:center;background-color:#034485;color:#eee; width: 1%;" rowspan="2">NO</th>
            <th style="text-align:center;background-color:#034485;color:#eee; width: 30%;" rowspan="2">RISIKO</th>
            <th style="text-align:center;background-color:#034485;color:#eee; width: 20%;" rowspan="2">RISK OWNER</th>
            <th style="text-align:center;background-color:#034485;color:#eee; width: 5%;" colspan="<?= count($rating) ?>">LEVEL RISIKO</th>
            <th style="text-align:center;background-color:#034485;color:#eee; width: 30%;" rowspan="2">Nama Audit</th>
          </tr>
          <tr>
            <?php if ($rating['i']) { ?>
              <th style="width:75px;text-align:center;background-color:#034485;color:#fff;">Inheren Risk</th>
            <?php }
            if ($rating['a']) { ?>
              <th style="width:75px;text-align:center;background-color:#034485;color:#fff;">Residual Setelah Evaluasi</th>
            <?php }
            if ($rating['r']) { ?>
              <th style="width:75px;text-align:center;background-color:#034485;color:#fff;">TARGETED RESIDUAL RISK</th>
            <?php } ?>
          </tr>
        </thead>
      <?php } ?>

      <tbody>
        <?php
        $rs_matrix = $this->data['mtriskmatrix'];
        $data = array(array());
        foreach ($rs_matrix as $k => $v) {
          $data[$v['id_dampak']][$v['id_kemungkinan']] = $v;
        }

        $rs = $this->data['rows'];
        // dpr($rs);
        // die();
        $no = 1;
        $top_inheren = array();
        $top_paska_kontrol = array();
        $top_paska_mitigasi = array();
        foreach ($rs as $r => $val) {
          $top_inheren[$val['inheren_dampak']][$val['inheren_kemungkinan']][] = $no;
          $top_paska_kontrol[$val['control_dampak_penurunan']][$val['control_kemungkinan_penurunan']][] = $no;
          $top_actual[$val['actual_dampak']][$val['actual_kemungkinan']][] = $no;
          $top_residual_target[$val['residual_target_dampak']][$val['residual_target_kemungkinan']][] = $no;

          echo "<tr>";
          echo "<td style='text-align:center'>" . $no++ . "</td>";
          echo "<td><a style='text-decoration: none;' href='" . site_url("panelbackend/risk_risiko/detail/$val[id_scorecard]/$val[id_risiko]") . "' target='_BLANK'>$val[nama]</a></td>";
          echo "<td style='text-align:center; width:auto; padding:0;'>$val[risk_owner]</td>";


          if ($rating['a']) {

            echo  labeltingkatrisiko((float)$val['level_risiko_actual']);
          }
          // dpr($arrDetailAudit);
          // dpr($this->data['row']['nama_audit']);
          // dpr($rows['nama_audit']);
          echo '<td>' . UI::createTextArea("nama_audit[$val[id_risiko]]", $val['nama_audit'], '3', '1', $edited, 'form-control', "style='height:25px;'");

          echo "</td></tr>";
        }
        if (!($rs)) {
          echo "<tr><td colspan='8'>Data kosong</td></tr>";
        }
        ?>
      </tbody>
      </table>
      </br>
      <?php
      if ($edited) { ?>
        <?php
        $from = UI::showButtonMode("save", null, $edited);
        echo UI::createFormGroup($from, NULL, NULL, NULL, true, 4, $edited);
        ?>
      <?php } ?>
      <?php if ($report) { ?>
        <br />
    </div>
    <div class="col-lg-6" style="margin-right: 0px !important; padding-right: 0px !important">
    <?php } ?>
    </div>
    <div class="col-lg-12">

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

    .boxKajianResiko {
      height: 350px;
      overflow-y: scroll;
      overflow-x: hidden;
      margin-bottom: 10px;
    }
  </style>