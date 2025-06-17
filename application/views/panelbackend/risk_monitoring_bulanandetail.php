<div class="row">
  <div class="col-sm-12">
    <?php
    // $from = UI::createTextArea('sasaran', $row['sasaran'], '', '', false);
    // echo UI::createFormGroup($from, $rules["sasaran"], "sasaran", "Sasaran", true);
    ?>
    <?php
    $from = UI::createTextArea('peristiwa_kerugian', $row['peristiwa_kerugian'], '', '', $edited && Access("pengajuan", "panelbackend/risk_scorecard"), 'form-control contents-mini', "");
    echo UI::createFormGroup($from, $rules["peristiwa_kerugian"], "peristiwa_kerugian", "Peristiwa Kerugian", true);
    ?>

  </div>
</div>
<div class="row">
  <div class="col-sm-12">
    <h5 class='h5'>Pengendalian Berjalan
    </h5>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <table class="table table-hover table-inline mb-3">
      <thead>
        <tr>
          <th width="1px">No</th>
          <th>Nama Pengendalian</th>
          <th width="240px">Untuk Menurunkan</th>
          <th width="10px">Efektifitas</th>
          <th width="10px">Pelaksanaan</th>
          <?php if ($edited) { ?>
            <th width="1px"></th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 1;
        $from = function ($val = null, $edited, $k = 0, $ci, $no) {
          $mtpengukuranarr = $ci->data['mtpengukuranarr'];
          $riskresiko = $ci->data['riskresiko'];
          $edit_m = $ci->data['edit_m'];
          $from = null;
          $from .= "<td>";
          $from .= $no;
          $from .= "</td>";
          $from .= "<td>";
          // $from .= UI::createTextBox("control[$k][nama]", $val['nama'], '', '', $edited, 'form-control');
          // $from .= UI::createSelect("control[$k][nama]", $riskresiko, $val['id_control'], $edited, 'form-control ', "style='width:100%;' data-tags='true' onchange=goSubmit('set_value')");
          // $k = $val['id_control'] ? $val['id_control'] : $val['nama'];
          $from .= UI::createTextHidden("control[$k][nama]", $val['nama'], $edited);

          $from .= $val['nama'];
          $from .= "</td>";
          $from .= "<td>";
          $from .= UI::createTextHidden("control[$k][menurunkan_dampak_kemungkinan]", $val['menurunkan_dampak_kemungkinan'], $edited);
          $from .= ["k" => "Kemungkinan", "d" => "Dampak", "kd" => "Kemungkinan / Dampak"][$val['menurunkan_dampak_kemungkinan']];
          $from .= "</td>";
          $from .= "<td>";
          // $from .= UI::createSelect("control[$k][id_pengukuran]", $mtpengukuranarr, $val['id_pengukuran'], $edited, 'form-control ', "style='width:100%;' onchange=goSubmit('set_value')");
          $from .= UI::createSelect("control[$k][id_pengukuran]", $mtpengukuranarr, $val['id_pengukuran'], $edited, 'form-control', "style='width:100%;' onchange=goSubmit('set_value')");
          $from .= "</td>";
          $from .= "<td>";
          $from .= UI::createTextNumber("control[$k][status_progress]", $val['status_progress'], '', '', $edited, 'form-control');

          if ($edited) {
            if (is_array($edit_m) && $edit_m !== null && (in_array($val['id_control'] ? $val['id_control'] : $val['nama'], $edit_m))) {
              $from .= "</td>";
              $from .= UI::createTextHidden("control[$k][id_control_bak]", $val['id_control'], $edited);
              $from .= UI::createTextHidden("control[$k][edit]", $val['id_control'], $edited);

              $from .= "<td style='position:relative; text-align:right'>";
              // } else {$from .= "</td>";
              //     $from .= UI::createTextHidden("control[$k][id_control]", $val['id_control'] ? $val['id_control'] : $val['nama'], $edited);
              //     $from .= UI::createTextHidden("control[$k][id]", $val['id_control'] ? $val['id_control'] : $val['nama'], $edited);

              //     $from .= "<td style='position:relative; text-align:right'>";
              // }
            } else {
              $from .= "</td>";
              $from .= UI::createTextHidden("control[$k][id_control]", $val['id_control'] ? $val['id_control'] : $val['nama'], $edited);
              $from .= UI::createTextHidden("control[$k][id]", $val['id_control'] ? $val['id_control'] : $val['nama'], $edited);

              $from .= "<td style='position:relative; text-align:right'>";
            }
          }

          return $from;
        };
        // dpr($from);
        if (!$row['control'])
          $row['control'] = [[]];
        echo UI::AddFormTable('control', $row['control'], $from, $edited, $this, 1);
        // dpr($row['control']);
        // dpr($riskresiko);
        // dpr($control_post);
        ?>
      </tbody>
    </table>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <h5 class='h5'>Pengendalian Lanjutan
    </h5>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <?php
    if (true) { ?>
      <table class="table table-hover table-inline mb-3">
        <thead>
          <tr>
            <th width="1px">No</th>
            <th>Tindak Penanganan</th>
            <th style="width: 100px;">Tgl. Mulai</th>
            <th style="width: 100px;">Tgl. Berakhir</th>
            <th style="width:80px">Progress (%)</th>
            <th width="10px">Efektifitas</th>
            <th style="width:150px">Lampiran</th>
            <?php if ($edited) { ?>
              <th width="1px"></th>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          $from = function ($val = null, $edited, $k = 0, $ci) {
            $mtpengukuranarr = $ci->data['mtpengukuranarr'];
            $edited = $ci->data['edited'];

            $page_ctrl = $ci->data['page_ctrl'];
            $from = null;
            $from .= "<td>";
            $from .= $k + 1;
            $from .= "</td>";
            $from .= "<td>";
            $from .= UI::createTextHidden("mitigasi[$k][menurunkan_dampak_kemungkinan]", $val['menurunkan_dampak_kemungkinan'], $edited);
            $from .= UI::createTextHidden("mitigasi[$k][nama]", $val['nama'], $edited);
            $from .= UI::createTextHidden("mitigasi[$k][id_mitigasi]", $val['id_mitigasi'], $edited);
            $from .= $val['nama'];
            $from .= "</td>";
            $from .= "<td>";
            $from .= UI::createTextBox("mitigasi[$k][start_date_realisasi]", $val['start_date_realisasi'], '', '', $edited, 'form-control datepicker');
            $from .= "</td>";
            $from .= "<td>";
            $from .= UI::createTextBox("mitigasi[$k][end_date_realisasi]", $val['end_date_realisasi'], '', '', $edited, 'form-control datepicker');
            $from .= "</td>";
            $from .= "<td>";
            $from .= UI::createTextNumber("mitigasi[$k][status_progress]", $val['status_progress'], '', '', $edited, 'form-control');
            $from .= "</td>";
            $from .= "<td>";
            $from .= UI::createSelect("mitigasi[$k][id_pengukuran]", $mtpengukuranarr, $val['id_pengukuran'], $edited, 'form-control ', "style='width:100%;' onchange=goSubmit('set_value')");
            $from .= "</td>";
            $from .= "<td>";
            $from .= UI::createUploadMultiple("files_" . $val['id_mitigasi'], $val['files'], "panelbackend/risk_monitoring_bulanan", $edited);

            if ($edited) {
              $from .= "</td>";
              $from .= UI::createTextHidden("mitigasi[$k][id_mitigasi]", $val['id_mitigasi'], $edited);

              $from .= "<td style='position:relative; text-align:right'>";
            }

            return $from;
          };

          echo UI::AddFormTable('mitigasi', $row['mitigasi'], $from, false, $this);
          ?>
        </tbody>
      </table>
    <?php } ?>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <?php
    // $from = UI::createTextArea('sasaran', $row['sasaran'], '', '', false);
    // echo UI::createFormGroup($from, $rules["sasaran"], "sasaran", "Sasaran", true);
    ?>
    <?php
    $from = UI::createTextArea('hasil_mitigasi_terhadap_sasaran', $row['hasil_mitigasi_terhadap_sasaran'], '', '', $edited && Access("pengajuan", "panelbackend/risk_scorecard"), 'form-control contents-mini', "");
    echo UI::createFormGroup($from, $rules["hasil_mitigasi_terhadap_sasaran"], "hasil_mitigasi_terhadap_sasaran", "Hasil Penanganan Terhadap Sasaran", true);
    ?>
    <?php
    $from = UI::createTextArea('penyesuaian_tindakan_mitigasi', $row['penyesuaian_tindakan_mitigasi'], '', '', $edited, $class = 'form-control', "");
    echo UI::createFormGroup($from, $rules["penyesuaian_tindakan_mitigasi"], "penyesuaian_tindakan_mitigasi", "Rekomendasi", true);
    ?>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <h5 class='h5'>Risiko Residual Setelah Evaluasi
      <?= UI::tingkatRisiko('residual_kemungkinan_evaluasi', 'residual_dampak_evaluasi', $rowheader1, $edited); ?>
    </h5>
  </div>
</div>

<?php
include "_kriteria.php";
?>

<div class="row">
  <div class="col-sm-6">
    <?php
    // $from = UI::createSelect('risk_oppurtuniny', array('' => '', 'risk' => 'Risk', 'opportunity' => 'Opportunity'), $row['risk_oppurtuniny'], $edited, $class = 'form-control select2', "style='width:100%;'");
    // echo UI::createFormGroup($from, $rules["risk_oppurtuniny"], "risk_oppurtuniny", 'Risk/Opportunity <a data-bs-toggle="modal" href="javascript:void(0)" data-bs-target="#kriteriaKemungkinan"><span class="material-icons" data-bs-toggle="tooltip" data-bs-original-title="Kriteria" style="font-size:inherit">info</span></a>', true, 4, $edited);
    ?>
    <?php
    $from = UI::createSelect('residual_kemungkinan_evaluasi', $mtkemungkinanrisikoarr, $rowheader1['residual_kemungkinan_evaluasi'], $edited, 'form-control ', "style='width:100%;' onchange=goSubmit('set_value')");
    echo UI::createFormGroup($from, $rules["residual_kemungkinan_evaluasi"], "residual_kemungkinan_evaluasi", 'Kemungkinan <a data-bs-toggle="modal" href="javascript:void(0)" data-bs-target="#kriteriaKemungkinan"><span class="material-icons" data-bs-toggle="tooltip" data-bs-original-title="Kriteria" style="font-size:inherit">info</span></a>', true, 4, $edited);
    ?>
    <?php
    $from = UI::createSelect('residual_dampak_evaluasi', $mtdampakrisikoarr, $rowheader1['residual_dampak_evaluasi'], $edited, 'form-control ', "style='width:100%;' onchange=goSubmit('set_value')");
    echo UI::createFormGroup($from, $rules["residual_dampak_evaluasi"], "residual_dampak_evaluasi", 'Dampak <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#kriteriaDampak"><span class="material-icons" style="font-size:inherit" data-bs-toggle="tooltip" data-bs-original-title="Kriteria">info</span></a>', true, 4, $edited);
    ?>
    <?php
    // $from = UI::createTextBox('dampak_kuantitatif_residual', $rowheader1['dampak_kuantitatif_residual'], '', '', $edited, 'form-control rupiah');
    // echo UI::createFormGroup($from, $rules["dampak_kuantitatif_residual"], "dampak_kuantitatif_residual", "Dampak Kuantitatif", true, 4, $edited);
    ?>

  </div>
</div>
<div class="row">
  <div class="col-sm-12">
    <?php
    if ($acces_status) {
      $statusarr = array('1' => '<b>Open</b><br/><small>Risiko/Peluang masih dipantau</small>', '2' => '<b>Berlanjut</b><br/><small>Pengendalian Lanjutan sudah selesai, namun Risiko/Peluang masih belum bisa diterima</small>',);
      // dpr((int)$rowheader1['is_opp_inherent']);
      // dpr((int)$rowheader1['residual_dampak_evaluasi']);
      // dpr((int)$rowheader1['residual_kemungkinan_evaluasi']);
      // dpr((int)$rowheader1['is_opp_inherent'] * (int)$rowheader1['residual_dampak_evaluasi'] * (int)$rowheader1['residual_kemungkinan_evaluasi']);
      // if (((int)$rowheader1['is_opp_inherent'] * (int)$rowheader1['residual_dampak_evaluasi'] * (int)$rowheader1['residual_kemungkinan_evaluasi']) > -1 * ($this->config->item("batas_nilai_signifikan"))) {
      //   $statusarr['0'] = '<b>Close</b><br/><small>Risiko/Peluang sudah bisa diterima</small>';
      // }
      // $from = UI::createBerlanjut($rowheader1['status_risiko'], ($editedheader1 && $rowheader1['residual_dampak_evaluasi'] && $rowheader1['residual_kemungkinan_evaluasi']));
      // echo UI::createFormGroup($from, $rules["status_risiko"], "status_risiko", "Status Risiko", true, 4, $editedheader1);

      $from = UI::createRadio('status_risiko', $statusarr, $row['status_risiko'], $edited, $class = 'form-control select2', "style='width:100%;' ", "onchange=goSubmit('set_value')");
      echo UI::createFormGroup($from, $rules["status_risiko"], "status_risiko", 'Status Risiko', true, 4, $edited);

      if ($row['status_risiko'] == 2) {
        $from = UI::createSelect('id_scorecard_new', $unitarr, $row['id_scorecard'], $edited, $class = 'form-control ', "style='width:30%;'");
        echo UI::createFormGroup($from, $rules["id_scorecard"], "id_scorecard", "Pilih IRR", true, 2, $editedheader1);
      }
    }
    ?>

    <?php
    if ($edited) { ?>
      <?php
      $from = UI::showButtonMode("save", null, $edited);
      echo UI::createFormGroup($from, NULL, NULL, NULL, true, 4, $edited);
      ?>
    <?php } ?>
  </div>
  <div class="col-sm-6">
    <?php // echo UI::createBerlanjut($rowheader1['status_risiko'], ($rowheader1['residual_dampak_evaluasi'] && $rowheader1['residual_kemungkinan_evaluasi'])) 
    ?>
  </div>
</div>