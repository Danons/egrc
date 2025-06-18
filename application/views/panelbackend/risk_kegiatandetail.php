  <div class="row">
    <div class="col-sm-12">

      <?php
      $from = UI::createSelect('id_sasaran', $sasaranarr, $row['id_sasaran'], $edited, $class = 'form-control ', "style='width:100%;'");
      echo UI::createFormGroup($from, $rules["id_sasaran"], "id_sasaran", "Sasaran", false, 2, $edited);
      ?>

      <?php
      $from = UI::createTextBox('nama', $row['nama'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
      echo UI::createFormGroup($from, $rules["name"], "nama", "Nama Sasaran Kegiatan", false, 2);
      ?>

      <?php
      $from = UI::createTextArea('deskripsi', $row['deskripsi'], '', '', $edited, $class = 'form-control', "");
      echo UI::createFormGroup($from, $rules["deskripsi"], "deskripsi", "Deskripsi Sasaran Kegiatan", false, 2);
      ?>
      <?php
      $from = UI::createTextArea('target_sasaran', $row['target_sasaran'], '', '', $edited, $class = 'form-control', "");
      echo UI::createFormGroup($from, $rules["target_sasaran"], "target_sasaran", "Target Sasaran", false, 2);
      ?>


      <?php
      $from = UI::createSelectMultiple('id_kpi[]', $riskkpiarr, $row['id_kpi'], $edited, $class = 'form-control select2', "style='width:auto; width:100%;' data-tags='true' onchange='goSubmit(\"set_value\")'");
      echo UI::createFormGroup($from, $rules["id_kpi[]"], "id_kpi[]", "KPI", false, 2);
      ?>

      <?php

      ?>

      <?php
      $from = UI::createTextArea('kpi_deskripsi', $row['kpi_deskripsi'], '3', '', $edited, $class = 'form-control ', "style='width:100%'");
      echo UI::createFormGroup($from, $rules["kpi_deskripsi"], "kpi_deskripsi", "Deskripsi KPI", false, 2);
      ?>

      <?php
      $from = UI::createTextArea('tujuan_kegiatan', $row['tujuan_kegiatan'], '', '', $edited, $class = 'form-control', "");
      echo UI::createFormGroup($from, $rules["tujuan_kegiatan"], "tujuan_kegiatan", "Tujuan Kegiatan", false, 2);
      ?>

      <?php
      $from = UI::createSelect('keselarasan', ["" => "", "Selaras" => "Selaras", "Tidak Selaras" => "Tidak Selaras"], $row['keselarasan'], $edited, $class = 'form-control ', "style='width:100%;'");
      echo UI::createFormGroup($from, $rules["keselarasan"], "keselarasan", "Keselarasan dengan tujuan/sasaran strategis", false, 2, $edited);
      ?>

      <?php
      $from = UI::createSelect('id_risk_taksonomi_objective', $risktaksonomiobjectivearr, $row['id_risk_taksonomi_objective'], $edited, $class = 'form-control ', "style='width:100%;'");
      echo UI::createFormGroup($from, $rules["id_risk_taksonomi_objective"], "id_risk_taksonomi_objective", "Kategori", false, 2, $edited);
      ?>

      <?php
      $from = UI::showButtonMode("save", null, $edited);
      echo UI::createFormGroup($from, null, null, null, null, 2);
      ?>
    </div>
  </div>