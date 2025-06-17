<div class="row">
  <div class="col-sm-12">
    <?php
    ?>

    <?php
    $from = UI::createTextArea('progress_capaian_kinerja', $row['progress_capaian_kinerja'], '', '', $edited, $class = 'form-control', "");
    echo UI::createFormGroup($from, $rules["progress_capaian_kinerja"], "progress_capaian_kinerja", "Hasil Implementasi Peluang Terhadap Kinerja Perusahaan", true);
    ?>

    <?php
    $from = UI::createTextArea('penyesuaian_tindakan_mitigasi', $row['penyesuaian_tindakan_mitigasi'], '', '', $edited, $class = 'form-control', "");
    echo UI::createFormGroup($from, $rules["penyesuaian_tindakan_mitigasi"], "penyesuaian_tindakan_mitigasi", "Rekomendasi", true);
    ?>

    <?php
    $from = UI::createBerlanjut($rowheader1['status_peluang'], $edited, 'status_peluang');
    echo UI::createFormGroup($from, $rules["status_peluang"], "status_peluang", "Status Peluang", true);
    ?>

    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from, NULL, NULL, NULL, true, 4, $edited);
    ?>
  </div>
</div>