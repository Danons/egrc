<div class="col-sm-6">

  <?php
  // dpr($risikoarr);
  $from = UI::createTextDate('tanggal_lhe', $row['tanggal_lhe'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
  echo UI::createFormGroup($from, $rules["tanggal_lhe"], "tanggal_lhe", "Tanggal Lhe");
  ?>

  <?php
  // dpr($risikoarr);
  $from = UI::createTextBox('nama_audit', $row['nama_audit'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
  echo UI::createFormGroup($from, $rules["nama_audit"], "nama_audit", "Auditi");
  ?>

  <!-- <?php
        // $from = UI::createTextBox('nama_audit',$row['nama_audit'],'200','100',$edited,$class='form-control ',"style='width:100%'");
        // echo UI::createFormGroup($from, $rules["nama_audit"], "nama_audit", "Tanggal LHE Terakhir");
        ?> -->
  <?php
  $from = UI::createSelect('id_risiko', $risikoArr, $row['id_risiko'], $edited, $class = 'form-control ', "style='width:100%;'");
  echo UI::createFormGroup($from, $rules["id_risiko"], "id_risiko", "Risiko", false);
  ?>

  <?php
  $from = UI::createTextBox('frekuensi_audit', $row['frekuensi_audit'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
  echo UI::createFormGroup($from, $rules["frekuensi_audit"], "frekuensi_audit", "Frekuensi Audit");
  ?>

  <?php
  $from = UI::createTextBox('jenis_audit', $row['jenis_audit'], '200', '100', $edited, $class = 'form-control ', "style='width:100%'");
  echo UI::createFormGroup($from, $rules["jenis_audit"], "jenis_audit", "Jenis Audit");
  ?>


  <?php
  $no = 1;
  $from = function ($val = null, $edited, $k = 0, $ci) {
    $from = null;
    $from .= "<td style='width:100px'>";
    $from .= UI::createTextNumber("tahun[$k]", $val, '', '', $edited, 'form-control', 'style="width:100%"');
    $from .= "</td>";

    if ($edited) {
      $from .= "</td>";
      $from .= "<td style='position:relative; text-align:left; width1' >";
    }

    // if ($edited) {
    //   $from .= "</td>";
    //   $from .= "<td style='position:relative; text-align:left; width:1'>";
    // }

    return $from;
  };

  $from = UI::AddFormTable('tahun', $row['tahun'], $from, $edited, $this);
  echo UI::createFormGroup("<table>" . $from . "</table>", $rules["tahun"], "tahun", "Tahun");
  ?>
</div>

<div class="mt-2 col-sm-6">
  <?php
  $from = UI::showButtonMode("save", null, $edited);
  echo UI::createFormGroup($from);
  ?>
</div>