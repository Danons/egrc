<?php
$modeheader1 = $mode;
if (!$editedheader1) {
  $modeheader1 = 'detail';
}
$is_readmore_risiko = false;
if (!accessbystatus($rowheader1['id_status_pengajuan']) && $page_ctrl != 'panelbackend/risk_risiko')
  $is_readmore_risiko = true;
?>
<div class="row">
  <div class="col-sm-12">

    <?php


    if ($rowheader1['tgl_risiko']) {
      $tgl_risiko = $rowheader1['tgl_risiko'];
    } elseif (!$id) {
      $tgl_risiko = date("Y-m-d");
    }

    if ($this->access_role['view_all'] && $editedheader1) {
      $from = UI::createTextBox('tgl_risiko', $tgl_risiko, '', '', $editedheader1, $class = 'form-control datepicker', "onchange='goSubmit(\"set_value\")'");
    } else {
      $from = "<span class='read_detail'>" . Eng2Ind($tgl_risiko) . "</span>";
    }

    echo UI::createFormGroup($from, $rules["tgl_risiko"], "tgl_risiko", "Tgl. Risiko", false, 2, $editedheader1);

    if (($rowheader1['tgl_close'] or $row['status_risiko'] == 0 or $row['status_risiko'] == 2) && $row['id_risiko']) {
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
      $no_risiko = $rowheader1['nomor'];
    }

    if ($this->access_role['view_all']) {
      $from = UI::createTextBox('nomor', $no_risiko, '', '', $editedheader1, $class = 'form-control ');
    } else {
      $from = "<span class='read_detail'>" . $no_risiko . "</span>";
    }

    echo UI::createFormGroup($from, $rules["nomor"], "nomor", "Kode", false, 2, $editedheader1);
    ?>

    <?php
    if (strtolower(trim($rowheader['id_unit'])) == '1') {
      $from = "";
      if ($row['id_risiko_unit']) {
        $from .= "<ol>";
        foreach ($row['id_risiko_unit'] as $v) {
          $r = $risikounitarr[$v];
          $from .= "<li><a href='" . site_url("panelbackend/risk_risiko/detail/$r[id_scorecard]/$r[id_risiko]") . "' target='_blank'>" . $r['nomor'] . " PIC : " . $r['nama_pic'] . "</a></li>";
        }
        $from .= "</ol>";
      }
      if ($editedheader1) {
        $from .= "<a href='javascript:void(0)' data-bs-toggle='modal' data-bs-target='#risikounit'><span class='material-icons'>edit</span></a>";
    ?>
        <div class="modal fade" id="risikounit" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Risiko Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <input type="text" class="form-control" id="caririsiko" onkeyup="filterrisiko()" placeholder="Search for names.." title="Type in a name">
                <table id="tablerisiko" class="table table-hovered">
                  <thead>
                    <tr>
                      <th></th>
                      <th width="200px">Nomor</th>
                      <th>Unit</th>
                      <th>Nama Risko</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($risikounitarr as $r) { ?>
                      <tr>
                        <td><?= UI::createCheckBox("id_risiko_unit[" . $r['id_risiko'] . "]", $r['id_risiko'], $row['id_risiko_unit'][$r['id_risiko']], null, $editedheader1) ?></td>
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
          function filterrisiko() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("caririsiko");
            filter = input.value.toUpperCase();
            console.log(filter)
            table = document.getElementById("tablerisiko");
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
      echo UI::createFormGroup($from, $rules["id_risiko_unit"], "id_risiko_unit", "Risiko Unit", false, 2, $editedheader1);
    } ?>
    <?php
    $from = UI::createSelect('id_kpi', $kpiarr, $rowheader1['id_kpi'], $editedheader1, $class = 'form-control ', "style='width:100%;' onchange='goSubmit(\"post\")'");
    echo UI::createFormGroup($from, $rules["id_kpi"], "id_kpi", "KPI", false, 2, $editedheader1);

    $from = UI::createTextArea('sasaran', $rowheader1['sasaran'], '5', '', $editedheader1, $class = 'form-control  contents-mini', "");
    echo UI::createFormGroup($from, $rules["sasaran"], "sasaran", "Sasaran", false, 2, $editedheader1);

    // dpr($rowheader1,1);

    $from = UI::createTextArea('nama_aktifitas', $rowheader1['nama_aktifitas'], '5', '', $editedheader1, $class = 'form-control  contents-mini', "");
    echo UI::createFormGroup($from, $rules["nama_aktifitas"], "nama_aktifitas", "Aktifitas", false, 2, $editedheader1);
    ?>

    <?php
    $from = UI::createSelect('id_taksonomi_area', $taksonomiareaarr, $rowheader1['id_taksonomi_area'], $editedheader1, $class = 'form-control ', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
    echo UI::createFormGroup($from, $rules["id_taksonomi_area"], "id_taksonomi_area", "Taksonomi", false, 2, $editedheader1);

    // $from = UI::createTextArea('deskripsi', $rowheader1['deskripsi'], '', '', $editedheader1, $class = 'form-control', "");
    // echo UI::createFormGroup($from, $rules["deskripsi"], "deskripsi", "Deskripsi", false, 2, $editedheader1);
    ?>

  </div>
  <div class="col-sm-12">

    <?php
    $from = UI::createTextArea('nama', $rowheader1['nama'], '5', '', $editedheader1, $class = 'form-control  contents-mini');
    echo UI::createFormGroup($from, $rules["nama"], "nama", "Peristiwa Risiko", false, 2, $editedheader1);

    // if (in_array($rowheader['id_tingkat_agregasi_risiko'], array("2", "3", "4"))) {

    //   if ($rowheader1['id_risiko_parent_lain'])
    //     $rowheader1['id_risiko_parent'] = '0';

    //   $from = UI::createSelect('id_risiko_parent', $risikoindukarr + array('0' => 'Risiko Lainnya'), $rowheader1['id_risiko_parent'], $editedheader1, $class = 'form-control ', "style='width:100%;'");
    //   echo UI::createFormGroup($from, $rules["id_risiko_parent"], "id_risiko_parent", "Risiko Induk", false, 2, $editedheader1);
    // }

    ?>

    <?php
    if ($hambatan_kendala = $rowheader1['risiko_old']['hambatan_kendala'])
      $info_penyebab = UI::createInfo("info_penyebab", "Hambatan & Kendala Sebelumnya", $hambatan_kendala);

    $from = UI::createTextArea('penyebab', $rowheader1['penyebab'], '5', '', $editedheader1, $class = 'form-control  contents-mini', "");
    echo UI::createFormGroup($from, $rules["penyebab"], "penyebab", "Penyebab" . $info_penyebab, false, 2, $editedheader1);
    ?>

    <?php
    $from = UI::createTextArea('dampak', $rowheader1['dampak'], '5', '', $editedheader1, $class = 'form-control  contents-mini', "");
    echo UI::createFormGroup($from, $rules["dampak"], "dampak", "Dampak", false, 2, $editedheader1);
    ?>
  </div>
</div>
<hr />

<div class="row">
  <div class="col-sm-6">
    <h4 class='h4'>Key Risk Indicator (KRI)
    </h4>
  </div>
  <div class="col-sm-6"><?php if ($this->access_role['editkri'] && $row) { ?>
      <a class="btn btn-warning" href="<?= site_url("panelbackend/risk_risiko/edit/$row[id_scorecard]/$row[id_risiko]") ?>">Edit</a>
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
          <th rowspan="2" width="120px">Polaritas</th>
          <th rowspan="2" width="80px">Satuan</th>
          <th colspan="2">Ambang Batas</th>
          <th colspan="2">Batas Normal</th>
          <th rowspan="2">Formula KRI</th>
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
          $from .= "<td>";
          $from .= UI::createTextArea("kri[$k][keterangan]", $val['keterangan'], '', '', $edited, 'form-control');

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
<hr />
<?php
include "_kriteria.php";
?>

<div class="row">
  <div class="col-sm-6">
    <h4 class='h4'>Inheren Risk
      <?= UI::tingkatRisiko('inheren_kemungkinan', 'inheren_dampak', $rowheader1, $editedheader1); ?>
    </h4>
  </div>
  <div class="col-sm-6">
  </div>
</div>

<div class="row">
  <div class="col-sm-6">
    <?php
    $from = UI::createSelect('inheren_kemungkinan', $mtkemungkinanrisikoarr, $rowheader1['inheren_kemungkinan'], $editedheader1, $class = 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["inheren_kemungkinan"], "inheren_kemungkinan", 'Kemungkinan <a data-bs-toggle="modal" href="javascript:void(0)" data-bs-target="#kriteriaKemungkinan"><span class="material-icons" data-bs-toggle="tooltip" data-bs-original-title="Kriteria" style="font-size:inherit">info</span></a>', false, 4, $editedheader1);
    ?>
  </div>
  <div class="col-sm-6">
    <?php
    $from = UI::createSelect('id_kriteria_kemungkinan', $kriteriakemungkinanarr, $rowheader1['id_kriteria_kemungkinan'], $editedheader1, $class = 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["id_kriteria_kemungkinan"], "id_kriteria_kemungkinan", "Kriteria", false, 3, $editedheader1);
    ?>
    <?php /*
    $from = "";
    if ($rowheader1['id_kriteria_kemungkinan'] == 3) {
      foreach ($files as $k => $r) {
        $from .= UI::InputFile(
          array(
            "nameid" => "file",
            "edit" => count($files) > 1 && $edited,
            "nama_file" => $r['client_name'],
            "url_preview" => site_url("panelbackend/risk_risiko/preview_file/" . $r['id_risiko_files']),
            "url_delete" => site_url("panelbackend/risk_risiko/delete_file/" . $row['id_risiko'] . '/' . $r['id_risiko_files']),
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
    $from = UI::createSelect('inheren_dampak', $mtdampakrisikoarr, $rowheader1['inheren_dampak'], $editedheader1, $class = 'form-control ', "style='width:100%;'");
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

<div class="row">
  <div class="col-sm-6">
    <?php
    $from = UI::createTextBox('dampak_kuantitatif_inheren', $rowheader1['dampak_kuantitatif_inheren'], '', '', $editedheader1, 'form-control rupiah');
    echo UI::createFormGroup($from, $rules["dampak_kuantitatif_inheren"], "dampak_kuantitatif_inheren", "Dampak Kuantitatif <a href='javascript:void(0)' data-bs-toggle='modal' data-bs-target='#varmodal'>VAR</a>", false, 4, $editedheader1);
    include "_var.php";
    ?>
  </div>
  <div class="col-sm-6">
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

    function goAddRisiko() {
      window.location = "<?= site_url("panelbackend/risk_risiko/add/" . $rowheader['id_scorecard']) ?>";
    }
  <?php } ?>

  <?php if ($this->access_role['edit']) { ?>

    function goEditRisiko() {
      window.location = "<?= site_url("panelbackend/risk_risiko/edit/" . $rowheader['id_scorecard'] . "/" . $rowheader1['id_risiko']) ?>";
    }
  <?php } ?>

  <?php if ($this->access_role['delete']) { ?>

    function goDeleteRisiko() {
      if (confirm("Apakah Anda yakin akan menghapus ?")) {
        window.location = "<?= site_url("panelbackend/risk_risiko/delete/" . $rowheader['id_scorecard'] . "/" . $rowheader1['id_risiko']) ?>";
      }
    }
  <?php } ?>

  function goListRisiko() {
    window.location = "<?= site_url("panelbackend/risk_risiko/index/" . $rowheader['id_scorecard']) ?>";
  }
</script>