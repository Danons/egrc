<div class="col-sm-6">

  <?php
  $from = UI::createTextBox('nama_audit', $row['nama_audit'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
  echo UI::createFormGroup($from, $rules["nama_audit"], "nama_audit", "Auditi");
  ?>

  <?php
  // dpr($risk_risikoarr);
  $from = UI::createSelect('id_risk_risiko', $risk_risikoarr, $row['id_risk_risiko'], $edited, $class = 'form-control ', "style='width:100%;'");
  echo UI::createFormGroup($from, $rules["id_risk_risiko"], "id_risk_risiko", "Risiko", false);
  ?>

  <?php
  $from = UI::createTextBox('pengawas', $row['pengawas'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
  echo UI::createFormGroup($from, $rules["pengawas"], "pengawas", "Nama Pengawas");
  ?>

  <?php
  $from = UI::createSelect('id_jabatan', $jabatanarr, $row['id_jabatan'], $edited, $class = 'form-control ', "style='width:100%;'");
  echo UI::createFormGroup($from, $rules["id_jabatan"], "id_jabatan", "Jabatan", false);
  ?>

  <?php
  $from = UI::createTextNumber('biaya', $row['biaya'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
  echo UI::createFormGroup($from, $rules["biaya"], "biaya", "Biaya");
  ?>

  <?php
  $from = UI::createTextBox('keterangan', $row['keterangan'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
  echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "keterangan");
  ?>

</div>

<div class="mt-2 col-sm-6">
  <?php
  $from = UI::showButtonMode("save", null, $edited);
  echo UI::createFormGroup($from);
  ?>
</div>