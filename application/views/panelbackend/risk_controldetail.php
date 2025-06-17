<?php
if (($this->access_role['view_all'] or $this->access_role['view_all_unit']) && $row['is_lock'] == '1' && $this->access_role['edit']) {
?>
  <button type="button" class="btn  btn-sm btn-warning" onclick="goSubmitValue('unlock',<?= $row[$pk] ?>)"><span class="bi bi-lock"></span> Unlock</button>
<?php
}

$is_lock_local = !$this->access_role['view_all'] && $row['is_lock'] == '1';
?>
<div class="row">
  <div class="col-sm-12">
    <?php
    $from = UI::createTextBox('no', $row['no'], '', '', $edited, $class = 'form-control ', "style='width:80px' readonly='readonly'");
    echo UI::createFormGroup($from, $rules["no"], "no", "No.", false, 2);
    ?>

    <?php
    $from = UI::createTextArea('nama', $row['nama'], '', '', $edited, $class = 'form-control contents-mini', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["nama"], "nama", "Pengendalian Risiko Berjalan", false, 2);
    ?>

    <?php
    $from = UI::createTextArea('remark', $row['remark'], '', '', $edited, $class = 'form-control contents-mini', "");
    echo UI::createFormGroup($from, $rules["remark"], "remark", "Keterangan", false, 2);
    ?>

    <?php
    /*$from = UI::createSelect('id_control_parent',$mtcontrolarr,$row['id_control_parent'],$edited,$class='form-control ',"style='width:100%;'");
echo UI::createFormGroup($from, $rules["id_control_parent"], "id_control_parent", "Sub Dari");*/
    ?>

    <?php
    // $from = UI::createSelect('id_dokumen', $dokumenarr, $row['id_dokumen'], $edited, $class = 'form-control ', "style='width:100%;'");
    // echo UI::createFormGroup($from, $rules["id_dokumen"], "id_dokumen", "Dokumen (DSM)", false, 2);
    ?>

    <?php
    // if ($row['id_pengukuran'])
    $from = UI::createSelect('id_pengukuran', $mtpengukuranarr, $row['id_pengukuran'], $edited, $class = 'form-control ', "style='width:100%;'");
    // else
    //   $from = "<i><small>Otomatis</small></i>";
    echo UI::createFormGroup($from, $rules["id_pengukuran"], "id_pengukuran", "Efektif ?", false, 2);
    ?>

    <?php
    // $from = UI::createSelect('id_interval', $mtintervalarr, $row['id_interval'], $edited, $class = 'form-control ', "style='width:100%;'");
    // echo UI::createFormGroup($from, $rules["id_interval"], "id_interval", "Interval");
    ?>

    <?php
    // $from = UI::createRadio('menurunkan_dampak_kemungkinan', $menurunkanrr, $row['menurunkan_dampak_kemungkinan'], $edited);
    // echo UI::createFormGroup($from, $rules["menurunkan_dampak_kemungkinan"], "menurunkan_dampak_kemungkinan", "Untuk menurunkan ?");
    ?>

    <?php
    if ($row['id_mitigasi_sumber']) {
      $from = '<a href="' . site_url('panelbackend/risk_mitigasi/detail/' . $rowheader1['id_risiko'] . '/' . $row['id_mitigasi_sumber']) . '"><span class="badge bg-primary">DARI MITIGASI</span></a>';
      echo UI::createFormGroup($from, null, null, null, false, 2);
    }
    ?>
    <!-- </div>
  <div class="col-sm-6"> -->
    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from, null, null, null, false, 2);
    ?>

  </div>
</div>

<script type="text/javascript">
  function checkMe() {
    if (confirm("Are you sure")) {
      alert("Clicked Ok");
      return true;
    } else {
      alert("Clicked Cancel");
      return false;
    }
  }
</script>