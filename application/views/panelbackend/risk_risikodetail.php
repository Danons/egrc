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
    <div class="row">
      <div class="col-sm-4">
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

        echo UI::createFormGroup($from, $rules["tgl_risiko"], "tgl_risiko", 'Tgl. Risiko <a href="javascript:void(0)" ><span class="material-icons" style="font-size:inherit" data-bs-toggle="tooltip" data-bs-original-title="Kriteria">info</span></a>', true, 6, $editedheader1);
        ?>
      </div>
      <div class="col-sm-4">
        <?php

        if (($rowheader1['tgl_close'] or $row['status_risiko'] == 0 or $row['status_risiko'] == 2) && $row['id_risiko']) {
          $tgl_close = $rowheader1['tgl_close'];
          if ($this->access_role['view_all'] && $editedheader1) {
            $from = UI::createTextBox('tgl_close', $tgl_close, '', '', $editedheader1, $class = 'form-control datepicker');
          } else {
            $from = "<span class='read_detail'>" . Eng2Ind($tgl_close) . "</span>";
          }

          echo UI::createFormGroup($from, $rules["tgl_close"], "tgl_close", 'Tgl. Close <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#tgl"><span class="material-icons" style="font-size:inherit" data-bs-toggle="tooltip" data-bs-original-title="Kriteria">info</span></a>', true, 6, $editedheader1);
        }
        ?>
      </div>
      <div class="col-sm-4">
      </div>
    </div>
    <?php
    if ($rowheader1['nomor']) {
      $no_risiko = $rowheader1['nomor'];
    }

    if ($this->access_role['view_all']) {
      $from = UI::createTextBox('nomor', $no_risiko, '', '', $editedheader1, $class = 'form-control ');
    } else {
      $from = "<span class='read_detail'>" . $no_risiko . "</span>";
    }

    echo UI::createFormGroup($from, $rules["nomor"], "nomor", 'Kode <a href="javascript:void(0)" ><span class="material-icons" style="font-size:inherit" data-bs-toggle="tooltip" data-bs-original-title="Kriteria">info</span></a>', true, 2, $editedheader1);
    ?>
    <?php

    // $from = UI::createSelect('id_kegiatan', $kegiatanarr, $rowheader1['id_kegiatan'], $editedheader1, $class = 'form-control ', "style='width:100%;'onchange='goSubmit(\"set_value\")' data-tags='true' data-ajax--data-type=\"json\" data-ajax--url=\"" . base_url('panelbackend/ajax/kegiatanarr') . "\"");
    if ($editedheader1)
      $edit_sasaran = true;
    if ($rowheader['rutin_non_rutin'] == 'nonrutin') {
      $edit_sasaran = false;
      // $rowheader1['id_kegiatan'] = $rowheader['id_kegiatan_proyek'];
    }
    $from = UI::createSelect('id_kegiatan', $kegiatanarr, $rowheader1['id_kegiatan'], $edit_sasaran, $class = 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["id_kegiatan"], "id_kegiatan", 'Kegiatan <a href="javascript:void(0)" ><span class="material-icons" style="font-size:inherit" data-bs-toggle="tooltip" data-bs-original-title="Kriteria">info</span></a>', true, 2, $editedheader1);


    $fromn = UI::createTextArea('nama', $rowheader1['nama'], '1', '', $editedheader1, $class = 'form-control', "style='width:100%'");
    echo UI::createFormGroup($fromn, $rules["nama"], "nama", 'Nama Risiko <a href="javascript:void(0)" ><span class="material-icons" style="font-size:inherit" data-bs-toggle="tooltip" data-bs-original-title="Kriteria">info</span></a>  ', true, 6, $editedheader1);

    $from = UI::createTextArea('deskripsi', $rowheader1['deskripsi'], '1', '', $editedheader1, $class = 'form-control', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["deskripsi"], "deskripsi", 'Peristiwa Risiko <a href="javascript:void(0)" ><span class="material-icons" style="font-size:inherit" data-bs-toggle="tooltip" data-bs-original-title="Kriteria">info</span></a>', true, 6, $editedheader1);
    ?>


    <?php

    ?>
    <?php
    if ($id_tingkat_agregasi_risiko_parent) {
      // dpr($risikoindukarr, 1);
      $from = UI::createSelect('id_risiko_parent', $risikoindukarr, $rowheader1['id_risiko_parent'], $edited, $class = 'form-control ', "style='width:100%;' ");
      echo UI::createFormGroup($from, $rules["id_risiko_parent"], "id_risiko_parent", "Risiko " . $nama_tingkat_agregasi_risiko_parent, true, 2, $edited);
    }


    if ($rowheader['rutin_non_rutin'] == 'nonrutin') {
      $from = UI::createTextArea('proyek_terkait', $rowheader1['proyek_terkait'], '2', '', $editedheader1, $class = 'form-control', "style='width:100%'");
      echo UI::createFormGroup($from, $rules["proyek_terkait"], "proyek_terkait", 'Proses Proyek Terkait <a href="javascript:void(0)" ><span class="material-icons" style="font-size:inherit" data-bs-toggle="tooltip" data-bs-original-title="Kriteria">info</span></a>', true, 6, $editedheader1);
    }

    if ($id_tingkat_agregasi_risiko_child) {
      $no = 1;
      $from = function ($val = null, $edited, $k = 0, $ci, $no, $rows) {
        $risikobawaharr = $ci->data['risikobawaharr'];
        $id_tingkat_agregasi_risiko_child = $ci->data['id_tingkat_agregasi_risiko_child'];
        $from = null;
        if (count($rows) > 1) {
          $from .= "<td style='width:1px'>";
          $from .= $no;
          $from .= ". </td>";
        }
        $from .= "<td>";
        $from .= UI::createSelect(
          "risikobawah[$k]",
          $risikobawaharr,
          $val,
          $edited,
          'form-control ',
          "style='width:100%;' "
        );
        $from .= "</td>";

        if ($edited) {
          $from .= "<td style='position:relative; text-align:right; vertical-align:top; width:1px'>";
        }
        $ci->data['test'] = $val;
        return $from;
      };


      if (!$row['risikobawah'])
        $row['risikobawah'] = [[]];

      $from = "<table width='100%'>" . UI::AddFormTable('risikobawah', $row['risikobawah'], $from, $editedheader1, $this) . "</table>";
      echo UI::createFormGroup($from, $rules["risikobawah"], "risikobawah", "Risiko " . $nama_tingkat_agregasi_risiko_child, true, 2, $editedheader1);
    }

    // if ($edited || $rowheader1['id_kpi']) {
    //   $from = UI::createSelect('id_kpi', $kpiarr, $rowheader1['id_kpi'], $editedheader1, $class = 'form-control ', "style='width:100%;' onchange='goSubmit(\"post\")'");
    //   echo UI::createFormGroup($from, $rules["id_kpi"], "id_kpi", "KPI", true, 2, $editedheader1);
    // }
    if ($rowheader1['kpi']) {
      $from = "";
      foreach ($rowheader1['kpi'] as $rk) {
        $idkpi = $rk['id_kpi'];
        $from .= UI::createCheckBox("id_kpi[$idkpi]", $idkpi, $rowheader1['id_kpi'][$idkpi], $rk['nama'], $editedheader1);
      }

      echo UI::createFormGroup($from, $rules["id_kpi[]"], "id_kpi[]", "KPI", true, 4, $editedheader1);
    }

    // $from = UI::createTextArea('nama_aktifitas', $rowheader1['nama_aktifitas'], '5', '', $editedheader1, $class = 'form-control', "");
    // echo UI::createFormGroup($from, $rules["nama_aktifitas"], "nama_aktifitas", "Aktifitas", true, 2, $editedheader1);
    ?>

    <?php

    // $from = UI::createTextArea('deskripsi', $rowheader1['deskripsi'], '', '', $editedheader1, $class = 'form-control', "");
    // echo UI::createFormGroup($from, $rules["deskripsi"], "deskripsi", "Deskripsi", true, 2, $editedheader1);

    // $from = UI::createRadio("is_rutin", ["0" => "Non Rutin", "1" => "Rutin"], $row['is_rutin'], $editedheader1);
    // echo UI::createFormGroup($from, $rules["is_rutin"], "is_rutin", "Jenis", true, 2, $editedheader1);

    ?>

    <?php
    // $from = UI::createTextArea('nama', $rowheader1['nama'], '5', '', $editedheader1, $class = 'form-control ');
    // echo UI::createFormGroup($from, $rules["nama"], "nama", "Peristiwa Risiko", true, 2, $editedheader1);

    // if (in_array($rowheader['id_tingkat_agregasi_risiko'], array("2", "3", "4"))) {

    //   if ($rowheader1['id_risiko_parent_lain'])
    //     $rowheader1['id_risiko_parent'] = '0';

    //   $from = UI::createSelect('id_risiko_parent', $risikoindukarr + array('0' => 'Risiko Lainnya'), $rowheader1['id_risiko_parent'], $editedheader1, $class = 'form-control ', "style='width:100%;'");
    //   echo UI::createFormGroup($from, $rules["id_risiko_parent"], "id_risiko_parent", "Risiko Induk", true, 2, $editedheader1);
    // }
    ?>

    <?php

    // $from = UI::createTextArea('penyebab', $rowheader1['penyebab'], '5', '', $editedheader1, $class = 'form-control', "");

    $no = 1;
    $from = function ($val = null, $edited, $k = 0, $ci, $no, $rows) {
      $riskpenyebabarr = $ci->data['riskpenyebabarr'];
      $edit_m = $ci->data['edit_p'];
      $from = null;
      if (count($rows) > 1) {
        $from .= "<td style='width:1px'>";
        $from .= $no;
        $from .= ". </td>";
      }
      if ($k == null) {
        $k = $val['id_risk_penyebab'];
      }
      $from .= "<td>";
      // $from .= UI::createSelect("penyebab[$k][nama]", $riskpenyebabarr, $val['id_risk_penyebab'], $edited, 'form-control ', "style='width:100%;' data-tags='true' onchange=goSubmit('set_penyebab')");
      // $from .= UI::createSelect("id_risk_penyebab[$k][nama]", $riskpenyebabarr, $val['id_risk_penyebab'], $edited, 'form-control ', "style='width:100%;' data-tags='true'  data-tags='true' data-ajax--data-type=\"json\" data-ajax--url=\"" . base_url('panelbackend/ajax/riskpenyebab') . "\"");

      // if (is_array($edit_m) && $edit_m !== null) {
      //   if (!in_array($val['id_risk_penyebab'] ? $val['id_risk_penyebab'] : $val['nama'], $edit_m)) {
      //     $from .= UI::createSelect("id_risk_penyebab[$k][nama]", $riskpenyebabarr, $val['id_risk_penyebab'], $edited, 'form-control ', "style='width:100%;' data-tags='true'  data-tags='true'   data-ajax--data-type=\"json\" data-ajax--url=\"" . base_url('panelbackend/ajax/riskpenyebab') . "\"");
      //   } else {
      //     $from .= UI::createTextBox("id_risk_penyebab[$k][nama]", $riskpenyebabarr[$val['id_risk_penyebab'] ? $val['id_risk_penyebab'] : $val['nama']], '', '', $edited, "form-control  ");
      //   }
      // } else $from .= UI::createSelect("id_risk_penyebab[$k][nama]", $riskpenyebabarr, $val['id_risk_penyebab'], $edited, 'form-control ', "style='width:100%;' data-tags='true'  data-tags='true'   data-ajax--data-type=\"json\" data-ajax--url=\"" . base_url('panelbackend/ajax/riskpenyebab') . "\"");
      $from .= UI::createSelect("penyebab[$k][id_risk_penyebab]", $riskpenyebabarr, $val['id_risk_penyebab'], $edited, 'form-control ', "style='width:100%;' data-tags='true'  data-tags='true'   data-ajax--data-type=\"json\" data-ajax--url=\"" . base_url('panelbackend/ajax/riskpenyebab') . "\"");
      $from .= "</td>";

      if ($edited) {
        $from .= "</td>";
        // $from .= UI::createTextHidden("id_risk_penyebab[$k][id_risk_penyebab]", $val['id_risk_penyebab'], $edited);
        // if (is_array($edit_m) && $edit_m !== null)
        //   if (in_array($val['id_risk_penyebab'] ? $val['id_risk_penyebab'] : $val['nama'], $edit_m)) {
        //     $from .= UI::createTextHidden("id_risk_penyebab[$k][edit]", $val['id_risk_penyebab'], $edited);
        //   }

        $from .= "<td style='position:relative; text-align:right; vertical-align:top; width:1px'>";
      }

      return $from;
    };

    // dpr($row['penyebab']);
    if (!$row['penyebab'])
      $row['penyebab'] = [[]];
    $from = "<table width='100%'>" . UI::AddFormTable('penyebab', $row['penyebab'], $from, $editedheader1, $this) . "</table>";
    echo UI::createFormGroup($from, $rules["penyebab"], "penyebab", "Penyebab", true, 2, $editedheader1);

    // $from = UI::createSelect('id_risk_penyebab', $riskpenyebabarr, $rowheader1['id_risk_penyebab'], $editedheader1, $class = 'form-control ', "style='width:100%;' data-tags='true'");
    // echo UI::createFormGroup($from, $rules["id_risk_penyebab"], "id_risk_penyebab", "Sumber Risiko / Peluang", true, 2, $editedheader1);

    // $from = UI::createSelect('id_kategori', $kategoriarr, $rowheader1['id_kategori'], $editedheader1, $class = 'form-control ', "style='width:100%;' onchange='tampil_op()'");
    // echo UI::createFormGroup($from, $rules["id_kategori"], "id_kategori", "Kategori", true, 2, $editedheader1);

    ?>


    <?php
    // if ($row['id_taksonomi_area'] == '89') {
    $from = UI::createSelect('id_aspek_lingkungan', $operasionalarr, $rowheader1['id_aspek_lingkungan'], $editedheader1, $class = 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["id_aspek_lingkungan"], "id_aspek_lingkungan", "Internal/Eksternal", true, 2, $editedheader1);
    // }
    ?>
    <?php
    // $from = UI::createTextArea('dampak', $rowheader1['dampak'], '5', '', $editedheader1, $class = 'form-control', "");

    $no = 1;
    $from = function ($val = null, $edited, $k = 0, $ci, $no, $rows) {
      $riskdampakarr = $ci->data['riskdampakarr'];
      $edit_d = $ci->data['edit_d'];
      $from = null;
      if (count($rows) > 1) {
        $from .= "<td style='width:1px'>";
        $from .= $no;
        $from .= ". </td>";
      }
      if ($k == null) {
        $k = $val['id_risk_dampak'];
      }
      $from .= "<td>";
      // if(!$riskdampakarr[$val['id_risk_dampak']]){
      // $riskdampakarr[$val['id_risk_dampak']] = $val['id_risk_dampak'];
      // }
      // $from .= UI::createSelect("dampak[$k][nama]", $riskdampakarr, $val['id_risk_dampak'], $edited, 'form-control ', "style='width:100%;' data-tags='true' onchange=goSubmit('set_dampak')");
      // $from .= UI::createSelect("id_risk_dampak[$k][nama]", $riskdampakarr, $val['id_risk_dampak'] ? $val['id_risk_dampak'] : $val['dampak'], $edited, 'form-control ', "style='width:100%;' data-tags='true'   data-tags='true' data-ajax--data-type=\"json\" data-ajax--url=\"" . base_url('panelbackend/ajax/riskdampakarr') . "\" ");
      // if (is_array($edit_d) && $edit_d !== null) {
      //   if (!in_array($val['id_risk_dampak'] ? $val['id_risk_dampak'] : $val['nama'], $edit_d)) {
      //     $from .= UI::createSelect("id_risk_dampak[$k][nama]", $riskdampakarr, $val['id_risk_dampak'], $edited, 'form-control ', "style='width:100%;' data-tags='true'  data-tags='true'   data-ajax--data-type=\"json\" data-ajax--url=\"" . base_url('panelbackend/ajax/riskpenyebab') . "\"");
      //   } else {
      //     $from .= UI::createTextBox("id_risk_dampak[$k][nama]", $riskdampakarr[$val['id_risk_dampak'] ? $val['id_risk_dampak'] : $val['nama']], '', '', $edited, "form-control  ");
      //   }
      // } else $from .= UI::createSelect("id_risk_dampak[$k][nama]", $riskdampakarr, $val['id_risk_dampak'] ? $val['id_risk_dampak'] : $val['dampak'], $edited, 'form-control ', "style='width:100%;' data-tags='true'    data-tags='true' data-ajax--data-type=\"json\" data-ajax--url=\"" . base_url('panelbackend/ajax/riskdampakarr') . "\" ");
      // $from .= UI::createSelect("id_risk_dampak[$k][nama]", $riskdampakarr, $val['id_risk_dampak'], $edited, 'form-control ', "style='width:100%;' data-tags='true'  data-tags='true'   data-ajax--data-type=\"json\" data-ajax--url=\"" . base_url('panelbackend/ajax/riskpenyebab') . "\"");
      $from .= UI::createSelect("dampak[$k][id_risk_dampak]", $riskdampakarr, $val['id_risk_dampak'], $edited, 'form-control ', "style='width:100%;' data-tags='true'  data-tags='true'   data-ajax--data-type=\"json\" data-ajax--url=\"" . base_url('panelbackend/ajax/riskdampakarr') . "\"");
      $from .= "</td>";

      if ($edited) {
        $from .= "</td>";
        // $from .= UI::createTextHidden("id_risk_dampak[$k][id_risk_dampak]", $val['id_risk_dampak'], $edited);
        // if (is_array($edit_d) && $edit_d !== null)
        //   if (in_array($val['id_risk_dampak'] ? $val['id_risk_dampak'] : $val['nama'], $edit_d)) {
        //     $from .= UI::createTextHidden("id_risk_dampak[$k][edit]", $val['id_risk_dampak'], $edited);
        //   }
        $from .= "<td style='position:relative; text-align:right; vertical-align:top; width:1px'>";
      }

      return $from;
    };
    // dpr($row['dampak']);
    if (!$row['dampak'])
      $row['dampak'] = [[]];
    $from = "<table width='100%'>" . UI::AddFormTable('dampak', $row['dampak'], $from, $editedheader1, $this) . "</table>";
    echo UI::createFormGroup($from, $rules["dampak"], "dampak", "Dampak", true, 2, $editedheader1);

    // $from = UI::createSelect('id_risk_dampak', $riskdampakarr, $rowheader1['id_risk_dampak'], $editedheader1, $class = 'form-control ', "style='width:100%;' data-tags='true'");
    // echo UI::createFormGroup($from, $rules["id_risk_dampak"], "id_risk_dampak", "Dampak Risiko / Peluang", true, 2, $editedheader1);
    ?>

    <?php
    // $from = UI::createTextArea('regulasi', $rowheader1['regulasi'], '1', '', $editedheader1, $class = 'form-control', "");
    // echo UI::createFormGroup($from, $rules["regulasi"], "regulasi", "Pemenuhan Kewajiban", true, 2, $editedheader1);
    ?>

    <?php
    $from = UI::createSelectMultiple('integrasi_internal[]', $integrasiinternal, $row['integrasi_internal'], $edited, 'form-control select2', "style='width:100%' data-ajax--data-type=\"json\" data-ajax--url=\"" . base_url('panelbackend/ajax/list_unit') . "\"");
    echo UI::createFormGroup($from, $rules["integrasi_internal"], "integrasi_internal", 'Integrasi Internal', true, 2, $edited);
    ?>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <h5 class='h5'>Key Risk Indicator (KRI)
    </h5>
  </div>
  <?php /*<div class="col-sm-6"><?php if ($this->access_role['editkri'] && $row) { ?>
      <a class="btn btn-warning" href="<?= site_url("panelbackend/risk_risiko/edit/$row[id_scorecard]/$row[id_risiko]") ?>">Edit</a>
    <?php } elseif ($editedkri && !$edited) { ?>
      <button class="btn btn-success" type="button" onclick="goSubmit('save_kri')">Simpan</button>
    <?php } ?>
  </div> */ ?>
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
          <th rowspan="2">Keterangan</th>
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
    <br />
  </div>
</div>
<div class="row">
  <div class="col-sm-12">
    <h5 class='h5'>Selera Risiko
      <?= UI::tingkatRisiko('selera_kemungkinan', 'selera_dampak', $row, $edited); ?>
    </h5>
  </div>
</div>

<div class="row" style="display: none;">
  <div class="col-sm-6">
    <?php
    $row['is_opp_inherent'] = -1;
    // $from = UI::createSelect('is_opp_inherent', array('-1' => 'Risk', '1' => 'Opportunity'), $row['is_opp_inherent'], $edited, $class = 'form-control select2', "style='width:100%;'");
    $from = UI::createRadio('is_opp_inherent', array('-1' => 'Risk', '1' => 'Opportunity'), $row['is_opp_inherent'], $edited, $class = 'form-control select2', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["is_opp_inherent"], "is_opp_inherent", 'Risk/Opportunity', true, 4, $edited);
    ?>
  </div>
</div>
<div class="row">
  <div class="col-sm-6">
    <?php
    $from = UI::createSelect('selera_kemungkinan', $mtkemungkinanrisikoarr, $row['selera_kemungkinan'], $edited, $class = 'form-control ', "style='width:100%;' onchange='set_signi()'");
    echo UI::createFormGroup($from, $rules["selera_kemungkinan"], "selera_kemungkinan", 'Kemungkinan <a data-bs-toggle="modal" href="javascript:void(0)" data-bs-target="#kriteriaKemungkinan"><span class="material-icons" data-bs-toggle="tooltip" data-bs-original-title="Kriteria" style="font-size:inherit">info</span></a>', true, 4, $edited);
    ?>
  </div>
  <div class="col-sm-6">
    <?php
    $from = UI::createSelect('id_kriteria_kemungkinan', $kriteriakemungkinanarr, $row['id_kriteria_kemungkinan'], $edited, $class = 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["id_kriteria_kemungkinan"], "id_kriteria_kemungkinan", "Kriteria", true, 3, $edited);
    ?>

  </div>
</div>
<div class="row">
  <div class="col-sm-6">
    <?php
    $from = UI::createSelect('selera_dampak', $mtdampakrisikoarr, $row['selera_dampak'], $edited, $class = 'form-control ', "style='width:100%;' onchange='set_signi()'");
    echo UI::createFormGroup($from, $rules["selera_dampak"], "selera_dampak", 'Dampak <a href="javascript:void(0)" ><span class="material-icons" style="font-size:inherit" data-bs-toggle="tooltip" data-bs-original-title="Kriteria">info</span></a>', true, 4, $edited);
    ?>
  </div>
  <div class="col-sm-6">
    <?php
    $from = UI::createSelect('id_kriteria_dampak', $kriteriaarr, $row['id_kriteria_dampak'], $edited, $class = 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["id_kriteria_dampak"], "id_kriteria_dampak", 'Kriteria', true, 3, $edited);
    ?>
  </div>
</div>
<?php

/*
<hr />
<?php
include "_kriteria.php";
?>

<?php 
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
    echo UI::createFormGroup($from, $rules["inheren_kemungkinan"], "inheren_kemungkinan", 'Kemungkinan <a data-bs-toggle="modal" href="javascript:void(0)" data-bs-target="#kriteriaKemungkinan"><span class="material-icons" data-bs-toggle="tooltip" data-bs-original-title="Kriteria" style="font-size:inherit">info</span></a>', true, 2, $editedheader1);
    ?>
  </div>
  <div class="col-sm-6">
    <?php
    $from = UI::createSelect('id_kriteria_kemungkinan', $kriteriakemungkinanarr, $rowheader1['id_kriteria_kemungkinan'], $editedheader1, $class = 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["id_kriteria_kemungkinan"], "id_kriteria_kemungkinan", "Kriteria", true, 3, $editedheader1);
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

      echo UI::createFormGroup($from, $rules["file"], "file", "Lampiran " . $label, true, 3, $editedheader1);
    }*/
/*
?>
</div>
</div>
<div class="row">
  <div class="col-sm-6">
    <?php
    $from = UI::createSelect('inheren_dampak', $mtdampakrisikoarr, $rowheader1['inheren_dampak'], $editedheader1, $class = 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["inheren_dampak"], "inheren_dampak", 'Dampak <a href="javascript:void(0)" ><span class="material-icons" style="font-size:inherit" data-bs-toggle="tooltip" data-bs-original-title="Kriteria">info</span></a>', true, 2, $editedheader1);
    ?>
  </div>
  <div class="col-sm-6">
    <?php
    $from = UI::createSelect('id_kriteria_dampak', $kriteriaarr, $rowheader1['id_kriteria_dampak'], $editedheader1, $class = 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["id_kriteria_dampak"], "id_kriteria_dampak", 'Kriteria', true, 3, $editedheader1);
    ?>
  </div>
</div>

<div class="row">
  <div class="col-sm-6">
    <?php
    $from = UI::createTextBox('dampak_kuantitatif_inheren', $rowheader1['dampak_kuantitatif_inheren'], '', '', $editedheader1, 'form-control rupiah');
    echo UI::createFormGroup($from, $rules["dampak_kuantitatif_inheren"], "dampak_kuantitatif_inheren", "Dampak Kuantitatif <a href='javascript:void(0)' data-bs-toggle='modal' data-bs-target='#varmodal'>VAR</a>", true, 2, $editedheader1);
    include "_var.php";
    ?>
  </div>
  <div class="col-sm-6">
  </div>
</div> */ ?>

<?php
if ($edited) { ?>
  <br />
  <div style="text-align: right;">
    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from, NULL, NULL, NULL, true, 2, $edited);
    ?>
  </div>
<?php
}
?>

<script type="text/javascript">
  function set_signi() {
    // console.log($('#inheren_dampak').val() * $('#inheren_kemungkinan').val());
    // if ($('#inheren_dampak').val() * $('#inheren_kemungkinan').val() >= (5))
    if ($('#inheren_dampak').val() * $('#inheren_kemungkinan').val() >= (<?= $this->config->item("batas_nilai_signifikan") ?>))
      $('#is_signifikan_inherent').prop("checked", true);
    else
      $('#is_signifikan_inherent').prop("checked", false);
  }
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
<script>
  $('#aspek_lingkungan').hide()

  function tampil_op() {
    console.log($('#id_kategori').val());
    if ($('#id_kategori').val() == 2) {
      $('#aspek_lingkungan').show()
    } else {

      $('#aspek_lingkungan').hide()
    }
  }
</script>