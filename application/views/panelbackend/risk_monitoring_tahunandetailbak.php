<div class="row">
  <div class="col-sm-12">
    <?php
    $from = UI::createTextArea('progress_capaian_kinerja', $row['progress_capaian_kinerja'], '', '', $edited, $class = 'form-control', "");
    echo UI::createFormGroup($from, $rules["progress_capaian_kinerja"], "progress_capaian_kinerja", "Efektifitas Pengendalian Risiko", true);
    ?>
    <?php
    $from = UI::createTextArea('penyesuaian_tindakan_mitigasi', $row['penyesuaian_tindakan_mitigasi'], '', '', $edited, $class = 'form-control', "");
    echo UI::createFormGroup($from, $rules["penyesuaian_tindakan_mitigasi"], "penyesuaian_tindakan_mitigasi", "Rekomendasi", true);
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
    $from = UI::createSelect('residual_kemungkinan_evaluasi', $mtkemungkinanpeluangarr, $rowheader1['residual_kemungkinan_evaluasi'], $editedheader1, $class = 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["residual_kemungkinan_evaluasi"], "residual_kemungkinan_evaluasi", 'Kemungkinan <a data-bs-toggle="modal" href="javascript:void(0)" data-bs-target="#kriteriaKemungkinan"><span class="material-icons" data-bs-toggle="tooltip" data-bs-original-title="Kriteria" style="font-size:inherit">info</span></a>', false, 4, $editedheader1);
    ?>
    <?php
    $from = UI::createSelect('residual_dampak_evaluasi', $mtdampakpeluangarr, $rowheader1['residual_dampak_evaluasi'], $editedheader1, $class = 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["residual_dampak_evaluasi"], "residual_dampak_evaluasi", 'Dampak <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#kriteriaDampak"><span class="material-icons" style="font-size:inherit" data-bs-toggle="tooltip" data-bs-original-title="Kriteria">info</span></a>', false, 4, $editedheader1);
    ?>
    <?php
    $from = UI::createTextBox('dampak_kuantitatif_residual', $rowheader1['dampak_kuantitatif_residual'], '', '', $editedheader1, 'form-control rupiah');
    echo UI::createFormGroup($from, $rules["dampak_kuantitatif_residual"], "dampak_kuantitatif_residual", "Dampak Kuantitatif", false, 4, $editedheader1);
    ?>

    <?php
    $from = UI::createBerlanjut($rowheader1['status_risiko'], ($editedheader1 && $rowheader1['residual_dampak_evaluasi'] && $rowheader1['residual_kemungkinan_evaluasi']));
    echo UI::createFormGroup($from, $rules["status_risiko"], "status_risiko", "Status Risiko", false, 4, $editedheader1);
    ?>

    <?php
    if ($editedheader1) { ?>
      <?php
      $from = UI::showButtonMode("save", null, $edited);
      echo UI::createFormGroup($from, NULL, NULL, NULL, false, 4, $edited);
      ?>
    <?php } ?>
  </div>
</div>