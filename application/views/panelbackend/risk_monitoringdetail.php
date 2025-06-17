<div class="row">
  <div class="col-sm-12">
    <?php
    $from = UI::createTextArea('hasil_mitigasi_terhadap_sasaran', $row['hasil_mitigasi_terhadap_sasaran'], '', '', $edited && Access("pengajuan", "panelbackend/risk_scorecard"), 'form-control contents-mini', "");
    echo UI::createFormGroup($from, $rules["hasil_mitigasi_terhadap_sasaran"], "hasil_mitigasi_terhadap_sasaran", "Hasil Mitigasi Terhadap Sasaran", true);
    ?>

    <?php
    $from = UI::createCheckBox('is_monitoring_rmtik', 1, $row['is_monitoring_rmtik'], "Monitoring RMTIK", $edited && Access("rmtik", "panelbackend/risk_monitoring"), 'iCheck-helper ', "");
    $from .= UI::createTextArea('ket_monitoring_rmtik', $row['ket_monitoring_rmtik'], '', '', $edited && Access("rmtik", "panelbackend/risk_monitoring"), 'form-control contents-mini', "");
    echo $from;
    ?>
    <br />
    <?php
    $from = UI::createCheckBox('is_monitoring_p2k3', 1, $row['is_monitoring_p2k3'], "Monitoring P2K3", $edited && Access("p2k3", "panelbackend/risk_monitoring"), 'iCheck-helper ', "");
    $from .= UI::createTextArea('ket_monitoring_p2k3', $row['ket_monitoring_p2k3'], '', '', $edited && Access("p2k3", "panelbackend/risk_monitoring"), 'form-control contents-mini', "");
    echo $from;
    ?>
    <br />
    <?php
    $from = UI::createCheckBox('is_monitoring_fkap', 1, $row['is_monitoring_fkap'], "Monitoring FKAP", $edited && Access("fkap", "panelbackend/risk_monitoring"), 'iCheck-helper ', "");
    $from .= UI::createTextArea('ket_monitoring_fkap', $row['ket_monitoring_fkap'], '', '', $edited && Access("fkap", "panelbackend/risk_monitoring"), 'form-control contents-mini', "");
    echo $from;
    ?>

  </div>
</div>
<hr />

<div class="row">
  <div class="col-sm-6">
    <h4 class='h4'>Residual Setelah Evaluasi
      <?= UI::tingkatRisiko('residual_kemungkinan_evaluasi', 'residual_dampak_evaluasi', $rowheader1, $edited); ?>
    </h4>
  </div>
  <div class="col-sm-6">
  </div>
</div>

<?php
include "_kriteria.php";
?>

<div class="row">
  <div class="col-sm-6">
    <?php
    $from = UI::createSelect('residual_kemungkinan_evaluasi', $mtkemungkinanrisikoarr, $rowheader1['residual_kemungkinan_evaluasi'], $edited, 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["residual_kemungkinan_evaluasi"], "residual_kemungkinan_evaluasi", 'Kemungkinan <a data-bs-toggle="modal" href="javascript:void(0)" data-bs-target="#kriteriaKemungkinan"><span class="material-icons" data-bs-toggle="tooltip" data-bs-original-title="Kriteria" style="font-size:inherit">info</span></a>', false, 4, $edited);
    ?>
    <?php
    $from = UI::createSelect('residual_dampak_evaluasi', $mtdampakrisikoarr, $rowheader1['residual_dampak_evaluasi'], $edited, 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["residual_dampak_evaluasi"], "residual_dampak_evaluasi", 'Dampak <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#kriteriaDampak"><span class="material-icons" style="font-size:inherit" data-bs-toggle="tooltip" data-bs-original-title="Kriteria">info</span></a>', false, 4, $edited);
    ?>
    <?php
    $from = UI::createTextBox('dampak_kuantitatif_residual', $rowheader1['dampak_kuantitatif_residual'], '', '', $edited, 'form-control rupiah');
    echo UI::createFormGroup($from, $rules["dampak_kuantitatif_residual"], "dampak_kuantitatif_residual", "Dampak Kuantitatif", false, 4, $edited);
    ?>

    <?php
    if ($edited) { ?>
      <?php
      $from = UI::showButtonMode("save", null, $edited);
      echo UI::createFormGroup($from, NULL, NULL, NULL, false, 4, $edited);
      ?>
    <?php } ?>
  </div>
  <div class="col-sm-6">
    <?php // echo UI::createBerlanjut($rowheader1['status_risiko'], ($rowheader1['residual_dampak_evaluasi'] && $rowheader1['residual_kemungkinan_evaluasi'])) 
    ?>
  </div>
</div>