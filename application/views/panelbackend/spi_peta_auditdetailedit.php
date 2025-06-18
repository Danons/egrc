<div class="col-sm-12">

  <?php
  $from = UI::createTextBox('nama_audit', $row['nama_audit'], '200', '100', $edited, $class = 'form-control', "style='width:100%;'");
  echo UI::createFormGroup($from, $rules["nama_audit"], "nama_audit", "Nama Audit Instansi Kegiatan, Program, dll", true, 2);
  ?>
  <?php
  $from = labeltingkatrisiko((float)$level_risiko_actual);
  echo UI::createFormGroup($from, $rules["besaran_risiko"], "besaran_risiko", "Besaran Risiko Audit", true, 2);
  ?>

  <?php
  if ($row['jenis'] != 'eksternal') {
    $from = UI::createSelect("id_penanggung_jawab", $penanggungjawabarr, $row['id_penanggung_jawab'], $edited, 'form-control', "onchange='goSubmit(\"set_value\")'");
    $from .= UI::createTextBox('nama_jabatan_penanggung_jawab', $row['nama_jabatan_penanggung_jawab'], '400', '500', $edited, 'form-control ', "style='width:100%' readonly");
    echo UI::createFormGroup($from, $rules["nama_jabatan_penanggung_jawab"], "nama_jabatan_penanggung_jawab", "Kesekretariatan", true, 2);

    if (!($row['jenis'] == 'penyuapan' || $row['jenis'] == 'mutu')) {
      if (!$pimpinanarr[$row['id_penyusun']])
        $pimpinanarr[$row['id_penyusun']] = $row['nama_penyusun'];

      $from = UI::createSelect("id_penyusun", $pimpinanarr, $row['id_penyusun'], $edited, 'form-control ', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
      $from .= UI::createTextBox('nama_jabatan_penyusun', $row['nama_jabatan_penyusun'], '200', '100', $edited, 'form-control ', "style='width:100%' readonly");
      echo UI::createFormGroup($from, $rules["nama_jabatan_penyusun"], "nama_jabatan_penyusun", "Pengendali Teknis", true, 2);
    }
    if (!$pelaksanaarr[$row['id_pereview']])
      $pelaksanaarr[$row['id_pereview']] = $row['nama_pereview'];

    // dpr($pelaksanaarr);

    $from = UI::createSelect("id_pereview", $pelaksanaarr, $row['id_pereview'], $edited, 'form-control ', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
    $from .= UI::createTextBox('nama_jabatan_pereview', $row['nama_jabatan_pereview'], '200', '100', $edited, 'form-control ', "style='width:100%' readonly");
    echo UI::createFormGroup($from, $rules["nama_jabatan_pereview"], "nama_jabatan_pereview", "Ketua", true, 2);
    /*} */
    $no = 1;
    $from = function ($val = null, $edited, $k = 0, $ci) {
      // dpr($val, 1);

      if (!$ci->data['pelaksanaarr'][$val['user_id']])
        $ci->data['pelaksanaarr'][$val['user_id']] = $val['nama'];

      $from = null;
      $from .= "<tr>";
      $from .= UI::createSelect("pemeriksaan_tim[$k][user_id]", $ci->data['pelaksanaarr'], $val['user_id'], $edited, 'form-control ', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
      $from .= "</tr>";
      $from .= "<tr>";
      $from .= UI::createTextBox("pemeriksaan_tim[$k][nama_jabatan]", $val['nama_jabatan'], '', '', $edited, 'form-control', "readonly");
      if ($ci->data['bidangpemeriksaanarr']) {
        $from .= "</tr>";
        $from .= "<tr>";
        $from .= UI::createSelect("pemeriksaan_tim[$k][id_bidang_pemeriksaan]", $ci->data['bidangpemeriksaanarr'], $val['id_bidang_pemeriksaan'], $edited, 'form-control ', "style='width:100%;'");
      }
      if ($edited) {
        $from .= "</tr>";
        $from .= UI::createTextHidden("pemeriksaan_tim[$k][id_pemeriksaan_tim]", $val['id_pemeriksaan_tim'], $edited);
        $from .= UI::createTextHidden("pemeriksaan_tim[$k][nama]", $val['nama'], $edited);
        $from .= UI::createTextHidden("pemeriksaan_tim[$k][id_jabatan]", $val['id_jabatan'], $edited);

        $from .= "<tr style='position:relative; text-align:right'>";
      }

      return $from;
    };

    $from = "<table width='100%'>" . UI::AddFormTable('pemeriksaan_tim', $row['pemeriksaan_tim'], $from, $edited, $this) . "</table>";
    echo UI::createFormGroup($from, $rules['pemeriksaan_tim[]'], "pemeriksaan_tim[]", "Auditor", true, 2);
  }
  // dpr($row['pemeriksaan_tim']);
  ?>

  <h5>Sarana Dan Prasarana Unit</h5>
  <?php
  $from = UI::createTextBox('sarana_kendaraan', $row['sarana_kendaraan'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
  echo UI::createFormGroup($from, $rules["sarana_kendaraan"], "sarana_kendaraan", "Kendaraan", true, 2);
  ?>
  <?php
  $from = UI::createTextBox('sarana_lainnya', $row['sarana_lainnya'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
  echo UI::createFormGroup($from, $rules["sarana_lainnya"], "sarana_lainnya", "Lainya", true, 2);
  ?>
  <h5>Dana Unit</h5>
  <?php
  $from = UI::createTextBox('dana_sppd', $row['dana_sppd'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
  echo UI::createFormGroup($from, $rules["dana_sppd"], "dana_sppd", "SPPD", true, 2);
  ?>
  <?php
  $from = UI::createTextBox('dana_lainnya', $row['dana_lainnya'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
  echo UI::createFormGroup($from, $rules["dana_lainnya"], "dana_lainnya", "Lainnya", true, 2);
  ?>
  <h5>Lain - Lain</h5>
  <?php
  $from = UI::createTextBox('lain_lain', $row['lain_lain'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
  echo UI::createFormGroup($from, $rules["lain_lain"], "lain_lain", "Lain-Lain", true, 2);
  ?>
</div>

<div class="mt-2 col-sm-12">
  <?php
  $from = UI::showButtonMode("save", null, $edited);
  echo UI::createFormGroup($from, null, null, null, null, 0);
  ?>
</div>