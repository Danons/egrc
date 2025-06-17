<?php
$modeheader1 = $mode;
if (!$editedheader1) {
  $modeheader1 = 'detail';
}
$is_readmore_peluang = false;
if (!accessbystatus($rowheader1['id_status_pengajuan']) && $page_ctrl != 'panelbackend/opp_peluang')
  $is_readmore_peluang = true;
?>
<div class="row">
  <div class="col-sm-12">

    <?php


    if ($rowheader1['tgl_peluang']) {
      $tgl_peluang = $rowheader1['tgl_peluang'];
    } elseif (!$id) {
      $tgl_peluang = date("Y-m-d");
    }

    if ($this->access_role['view_all'] && $editedheader1) {
      $from = UI::createTextBox('tgl_peluang', $tgl_peluang, '', '', $editedheader1, $class = 'form-control datepicker', "onchange='goSubmit(\"set_value\")'");
    } else {
      $from = "<span class='read_detail'>" . Eng2Ind($tgl_peluang) . "</span>";
    }

    echo UI::createFormGroup($from, $rules["tgl_peluang"], "tgl_peluang", "Tgl. Peluang", false, 2, $editedheader1);

    if (($rowheader1['tgl_close'] or $row['status_peluang'] == 0 or $row['status_peluang'] == 2) && $row['id_peluang']) {
      $tgl_close = $rowheader1['tgl_close'];
      if ($this->access_role['view_all'] && $editedheader1) {
        $from = UI::createTextBox('tgl_close', $tgl_close, '', '', $editedheader1, $class = 'form-control datepicker');
      } else {
        $from = "<span class='read_detail'>" . Eng2Ind($tgl_close) . "</span>";
      }

      echo UI::createFormGroup($from, $rules["tgl_close"], "tgl_close", "Tgl. Close", false, 2, $editedheader1);
    }
    ?>

    <?php
    if ($rowheader1['nomor']) {
      $no_peluang = $rowheader1['nomor'];
    }

    if ($this->access_role['view_all']) {
      $from = UI::createTextBox('nomor', $no_peluang, '', '', $editedheader1, $class = 'form-control ');
    } else {
      $from = "<span class='read_detail'>" . $no_peluang . "</span>";
    }

    echo UI::createFormGroup($from, $rules["nomor"], "nomor", "Kode", false, 2, $editedheader1);
    ?>


    <?php
    if (strtolower(trim($rowheader['id_unit'])) == '1') {
      $from = "";
      if ($row['id_peluang_unit']) {
        $from .= "<ol>";
        foreach ($row['id_peluang_unit'] as $v) {
          $r = $peluangunitarr[$v];
          $from .= "<li>" . $r['nomor'] . "</li>";
        }
        $from .= "</ol>";
      }
      if ($editedheader1) {
        $from .= "<a href='javascript:void(0)' data-bs-toggle='modal' data-bs-target='#peluangunit'><span class='material-icons'>edit</span></a>";
    ?>
        <div class="modal fade" id="peluangunit" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Peluang Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <input type="text" class="form-control" id="caripeluang" onkeyup="filterpeluang()" placeholder="Search for names.." title="Type in a name">
                <table id="tablepeluang" class="table table-hovered">
                  <thead>
                    <tr>
                      <th></th>
                      <th width="200px">Nomor</th>
                      <th>Unit</th>
                      <th>Nama Peluang</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($peluangunitarr as $r) { ?>
                      <tr>
                        <td><?= UI::createCheckBox("id_peluang_unit[" . $r['id_peluang'] . "]", $r['id_peluang'], $row['id_peluang_unit'][$r['id_peluang']], null, $editedheader1) ?></td>
                        <td><?= $r['nomor'] ?></td>
                        <td><?= $r['scorecard'] ?></td>
                        <td><?= strip_tags($r['nama']) ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="goSubmit('set_value')">SET</button>
              </div>
            </div>
          </div>
        </div>

        <script type="text/javascript">
          function filterpeluang() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("caripeluang");
            filter = input.value.toUpperCase();
            console.log(filter)
            table = document.getElementById("tablepeluang");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
              tds = tr[i].getElementsByTagName("td");
              var match = false;
              for (j = 0; j < tds.length; j++) {
                td = tds[j];
                if (td) {
                  txtValue = td.textContent || td.innerText;
                  if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    match = true;
                  }
                }
              }

              if (match)
                tr[i].style.display = "";
              else
                tr[i].style.display = "none";
            }
          }
        </script>
    <?php
      }
      echo UI::createFormGroup($from, $rules["id_peluang_unit"], "id_peluang_unit", "Peluang Unit", false, 2, $editedheader1);
    } ?>
    <?php
    // dpr($rowheader1,1);
    $from = UI::createSelect('id_sasaran', $sasaranarr, $rowheader1['id_sasaran'], $editedheader1, $class = 'form-control ', "style='width:100%;' onchange='goSubmit(\"post\")'");
    echo UI::createFormGroup($from, $rules["id_sasaran"], "id_sasaran", "Sasaran", false, 2, $editedheader1);

    // $from = UI::createTextArea('sasaran', $rowheader1['sasaran'], '5', '', $editedheader1, $class = 'form-control  contents-mini', "");
    // echo UI::createFormGroup($from, $rules["sasaran"], "sasaran", "Sasaran", false, 2, $editedheader1);
    ?>

    <?php
    // $from = UI::createSelect('id_taksonomi_area', $taksonomiareaarr, $rowheader1['id_taksonomi_area'], $editedheader1, $class = 'form-control ', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
    // echo UI::createFormGroup($from, $rules["id_taksonomi_area"], "id_taksonomi_area", "Taksonomi", false, 2, $editedheader1);

    // if ($is_regulasi) {
    //   $from = UI::createTextArea('regulasi', $rowheader1['regulasi'], '', '', $editedheader1, $class = 'form-control', "");
    //   echo UI::createFormGroup($from, $rules["regulasi"], "regulasi", "Regulasi", false, 2, $editedheader1);
    // }

    // if (in_array($rowheader['id_tingkat_agregasi_peluang'], array("2", "3", "4"))) {

    //   if ($rowheader1['id_peluang_parent_lain'])
    //     $rowheader1['id_peluang_parent'] = '0';

    //   $from = UI::createSelect('id_peluang_parent', $peluangindukarr + array('0' => 'Peluang Lainnya'), $rowheader1['id_peluang_parent'], $editedheader1, $class = 'form-control ', "style='width:100%;'");
    //   echo UI::createFormGroup($from, $rules["id_peluang_parent"], "id_peluang_parent", "Peluang Induk", false, 2, $editedheader1);
    // }

    $from = UI::createTextArea('nama', $rowheader1['nama'], '5', '', $editedheader1, $class = 'form-control  contents-mini');
    echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama Peluang", false, 2, $editedheader1);
    ?>
  </div>
  <div class="col-sm-12">
    <?php
    // if ($hambatan_kendala = $rowheader1['peluang_old']['hambatan_kendala'])
    //   $info_penyebab = UI::createInfo("info_penyebab", "Hambatan & Kendala Sebelumnya", $hambatan_kendala);

    // $from = UI::createTextArea('penyebab', $rowheader1['penyebab'], '5', '', $editedheader1, $class = 'form-control', "");
    // echo UI::createFormGroup($from, $rules["penyebab"], "penyebab", "Penyebab" . $info_penyebab, false, 2, $editedheader1);
    ?>

    <?php
    $from = "";
    foreach ($kelayakanarr as $k => $v) {
      $from .= UI::createCheckBox('id_kelayakan[' . $k . ']', $k, $row['id_kelayakan'][$k], $v, $edited);
      $from .= UI::createUploadMultiple("filekelayakan" . $k, $row['filekelayakan' . $k], $page_ctrl, $edited) . "<hr/>";
    }
    echo UI::createFormGroup($from, $rules["id_kelayakan"], "id_kelayakan", "Studi Kelayakan (Feasibility Study)", false, 2, $editedheader1);

    // $from = UI::createCheckBox('is_kerangka_acuan_kerja', 1, $row['is_kerangka_acuan_kerja'], "Kerangka Acuan Kerja", $edited);
    // echo UI::createFormGroup($from, $rules["is_kerangka_acuan_kerja"], "is_kerangka_acuan_kerja", null, false, 2, $editedheader1);
    ?>


    <?php
    $from = UI::createTextArea('dampak', $rowheader1['dampak'], '5', '', $editedheader1, $class = 'form-control contents-mini', "");
    echo UI::createFormGroup($from, $rules["dampak"], "dampak", "Manfaat", false, 2, $editedheader1);
    ?>

    <?php
    $from = UI::createTextBox('anggaran_biaya', $rowheader1['anggaran_biaya'], '', '', $editedheader1, 'form-control rupiah');
    echo UI::createFormGroup($from, $rules["anggaran_biaya"], "anggaran_biaya", "Anggaran Biaya", false, 2, $editedheader1);
    ?>

    <?php
    $from = UI::createTextBox('target_penyelesaian', $rowheader1['target_penyelesaian'], '', '', $editedheader1, $class = 'form-control datepicker');
    echo UI::createFormGroup($from, $rules["target_penyelesaian"], "target_penyelesaian", "Target Penyelesaian", false, 2, $editedheader1);

    ?>

    <?php
    $from = UI::createTextArea('deskripsi', $rowheader1['deskripsi'], '', '', $editedheader1, $class = 'form-control contents-mini', "");
    echo UI::createFormGroup($from, $rules["deskripsi"], "deskripsi", "Keterangan", false, 2, $editedheader1);
    ?>
  </div>
</div>
<hr />

<?php /*
<div class="row">
  <div class="col-sm-6">
    <h4 class='h4'>idkey Risk Indicator (KRI)
    </h4>
  </div>
  <div class="col-sm-6"><?php if ($this->access_role['editkri'] && $row) { ?>
      <a class="btn btn-warning" href="<?= site_url("panelbackend/opp_peluang/edit/$row[id_scorecard]/$row[id_peluang]") ?>">Edit</a>
    <?php } elseif ($editedkri && !$edited) { ?>
      <button class="btn btn-success" type="button" onclick="goSubmit('save_kri')">Simpan</button>
    <?php } ?>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <table class="table table-hover table-inline">
      <thead>
        <tr>
          <th rowspan="2" width="1px">No</th>
          <th rowspan="2">KRI</th>
          <th rowspan="2">Polaritas</th>
          <th rowspan="2" width="80px">Satuan</th>
          <th colspan="2">Threshold</th>
          <th colspan="2">Target</th>
          <!-- <th rowspan="2">Keterangan</th> -->
          <?php if ($edited) { ?>
            <th rowspan="2" width="1px"></th>
          <?php } ?>
        </tr>
        <tr>
          <th width="80px">Bawah</th>
          <th width="80px">Atas*</th>
          <th width="80px">Mulai</th>
          <th width="80px">Sampai*</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 1;
        $from = function ($val = null, $edited, $k = 0, $ci) {
          $id_periode_tw = $ci->data['id_periode_tw'];
          $from = null;
          $from .= "<td>";
          $from .= $k + 1;
          $from .= "</td>";
          $from .= "<td>";
          $from .= UI::createTextBox("kri[$k][nama]", $val['nama'], '', '', $edited, 'form-control');
          $from .= "</td>";
          $from .= "<td>";
          $from .= UI::createSelect("kri[$k][polaritas]", array('+' => 'Positif', '-' => 'Negatif'), $val['polaritas'], $edited, 'form-control ', "style='width:100%;'");
          $from .= "</td>";
          $from .= "<td>";
          $from .= UI::createTextBox("kri[$k][satuan]", $val['satuan'], '', '', $edited, 'form-control');
          $from .= "</td>";
          $from .= "<td>";
          $from .= UI::createTextNumber("kri[$k][batas_bawah]", $val['batas_bawah'], '', '', $edited, 'form-control', '', '.01');
          $from .= "</td>";
          $from .= "<td>";
          $from .= UI::createTextNumber("kri[$k][batas_atas]", $val['batas_atas'], '', '', $edited, 'form-control', '', '.01');
          $from .= "</td>";
          $from .= "<td>";
          $from .= UI::createTextNumber("kri[$k][target_mulai]", $val['target_mulai'], '', '', $edited, 'form-control', '', '.01');
          $from .= "</td>";
          $from .= "<td>";
          $from .= UI::createTextNumber("kri[$k][target_sampai]", $val['target_sampai'], '', '', $edited, 'form-control', '', '.01');
          if ($edited) {
            $from .= "</td>";
            // $from .= "<td>";
            // $from .= UI::createTextBox("kri[$k][keterangan]", $val['keterangan'], '', '', $edited, 'form-control');
            // $from .= "</td>";
            $from .= UI::createTextHidden("kri[$k][id_kri]", $val['id_kri'], $edited);

            $from .= "<td style='position:relative; text-align:right'>";
          }

          return $from;
        };

        echo UI::AddFormTable('kri', $row['kri'], $from, $editedkri || $edited, $this);
        ?>
      </tbody>
    </table>
    <?php if ($editedkri || $edited) { ?>
      <div>
        <small style="color:orange;">NB : <br />* silahkan dikosongi apabila memang tidak ada</small>
      </div>
    <?php } ?>
  </div>
</div>
<hr /> */ ?>
<?php
include "_kriteria.php";
?>

<div class="row">
  <div class="col-sm-6">
    <h4 class='h4'>Tingkat Peluang
      <?= UI::tingkatPeluang('inheren_kemungkinan', 'inheren_dampak', $rowheader1, $editedheader1); ?>
    </h4>
  </div>
  <div class="col-sm-6">
  </div>
</div>

<div class="row">
  <div class="col-sm-6">
    <?php
    $from = UI::createSelect('inheren_kemungkinan', $mtkemungkinanpeluangarr, $rowheader1['inheren_kemungkinan'], $editedheader1, $class = 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["inheren_kemungkinan"], "inheren_kemungkinan", 'Kemungkinan <a data-bs-toggle="modal" href="javascript:void(0)" data-bs-target="#kriteriaKemungkinan"><span class="material-icons" data-bs-toggle="tooltip" data-bs-original-title="Kriteria" style="font-size:inherit">info</span></a>', false, 4, $editedheader1);
    ?>
  </div>
  <div class="col-sm-6">
    <?php /*
    $from = UI::createSelect('id_kriteria_kemungkinan', $kriteriakemungkinanarr, $rowheader1['id_kriteria_kemungkinan'], $editedheader1, $class = 'form-control ', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
    echo UI::createFormGroup($from, $rules["id_kriteria_kemungkinan"], "id_kriteria_kemungkinan", "Kriteria", false, 3, $editedheader1);
    ?>
    <?php
    $from = "";
    if ($rowheader1['id_kriteria_kemungkinan'] == 3) {
      foreach ($files as $k => $r) {
        $from .= UI::InputFile(
          array(
            "nameid" => "file",
            "edit" => count($files) > 1 && $edited,
            "nama_file" => $r['client_name'],
            "url_preview" => site_url("panelbackend/opp_peluang/preview_file/" . $r['id_peluang_files']),
            "url_delete" => site_url("panelbackend/opp_peluang/delete_file/" . $row['id_peluang'] . '/' . $r['id_peluang_files']),
          )
        );
      }

      if ($edited) {
        $from .= UI::InputFile(
          array(
            "nameid" => "file",
            "edit" => $edited,
            "extarr" => explode("|", $configfile['allowed_types'] . "<br/> Ukuran Maksimal " . (round($configfile['max_size'] / 1000)) . " mb"),
          )
        );
      } else
        $from .= "</span>";

      if ($edited)
        $label = "<span style='color:red'>*</span> ";
      else
        $label = "<span style='margin-top:5px; display:block'>";

      echo UI::createFormGroup($from, $rules["file"], "file", "Lampiran " . $label, false, 3, $editedheader1);
    }*/
    ?>
  </div>
</div>

<div class="row">
  <div class="col-sm-6">
    <?php
    $from = UI::createSelect('inheren_dampak', $mtdampakpeluangarr, $rowheader1['inheren_dampak'], $editedheader1, $class = 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["inheren_dampak"], "inheren_dampak", 'Dampak <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#kriteriaDampak"><span class="material-icons" style="font-size:inherit" data-bs-toggle="tooltip" data-bs-original-title="Kriteria">info</span></a>', false, 4, $editedheader1);
    ?>
  </div>
  <div class="col-sm-6">
    <?php
    $from = UI::createSelect('id_kriteria_dampak', $kriteriaarr, $rowheader1['id_kriteria_dampak'], $editedheader1, $class = 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["id_kriteria_dampak"], "id_kriteria_dampak", 'Kriteria', false, 3, $editedheader1);
    ?>
  </div>
</div>

<?php
if ($edited) { ?>
  <br />
  <div class="row">
    <div class="col-sm-6">
      <?php
      $from = UI::showButtonMode("save", null, $edited);
      echo UI::createFormGroup($from, NULL, NULL, NULL, false, 4, $edited);
      ?></div>
  </div>
<?php
}
?>

<script type="text/javascript">
  <?php if ($this->access_role['add']) { ?>

    function goAddPeluang() {
      window.location = "<?= site_url("panelbackend/opp_peluang/add/" . $rowheader['id_scorecard']) ?>";
    }
  <?php } ?>

  <?php if ($this->access_role['edit']) { ?>

    function goEditPeluang() {
      window.location = "<?= site_url("panelbackend/opp_peluang/edit/" . $rowheader['id_scorecard'] . "/" . $rowheader1['id_peluang']) ?>";
    }
  <?php } ?>

  <?php if ($this->access_role['delete']) { ?>

    function goDeletePeluang() {
      if (confirm("Apakah Anda yakin akan menghapus ?")) {
        window.location = "<?= site_url("panelbackend/opp_peluang/delete/" . $rowheader['id_scorecard'] . "/" . $rowheader1['id_peluang']) ?>";
      }
    }
  <?php } ?>

  function goListPeluang() {
    window.location = "<?= site_url("panelbackend/opp_peluang/index/" . $rowheader['id_scorecard']) ?>";
  }
</script>