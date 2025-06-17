<div class="row table-responsive">
  <div class="col-sm-12">
    <table class="table table-hover dataTable">
      <thead>
        <?php
        $editedheader1 = ($editedheader1 && ($is_edit or !$rowheader1['is_lock'] or ($this->access_role['view_all'] or $this->access_role['view_all_unit'])));
        //showHeader($header, $filter_arr, $list_sort, $list_order, $is_filter=true, $is_sort = true, $is_no = true)
        ?>
        <?= UI::showHeader($header, $filter_arr, $list_sort, $list_order, (bool)($list['total'] > $limit), true, false) ?>
      </thead>
      <tbody>
        <?php
        $i = $page;
        $unlock = 0;
        foreach ($list['rows'] as $rows) {
          $i++;
          echo "<tr>";
          foreach ($header as $rows1) {
            $val = $rows[$rows1['name']];
            if ($rows1['name'] == 'nama') {
              echo "<td><a href='" . ($url = base_url($page_ctrl . "/detail/$rowheader1[id_risiko]/$rows[$pk]")) . "'>" . nl2br(strip_tags($val)) . "</a>";
              echo labelverified($rows);
              echo "</td>";
            } elseif ($rows1['name'] == 'isi') {
              echo "<td>" . ReadMore($val, $url) . "</td>";
            } elseif ($rows1['name'] == 'id_status_pengajuan') {
              echo "<td style='text-align:center;'>" . labelstatus($val) . "</td>";
            } elseif ($rows1['name'] == 'is_efektif') {
              echo "<td style='text-align:center;'>" . labelefektifitas($val) . "</td>";
            } else {
              switch ($rows1['type']) {
                case 'list':
                  echo "<td style='text-align:center'>" . $rows1["value"][$val] . "</td>";
                  break;
                case 'number':
                  echo "<td style='text-align:right'>$val</td>";
                  break;
                case 'date':
                  echo "<td>" . Eng2Ind($val, false) . "</td>";
                  break;
                case 'datetime':
                  echo "<td>" . Eng2Ind($val) . "</td>";
                  break;
                default:
                  echo "<td>" . nl2br(strip_tags($val)) . "</td>";
                  break;
              }
            }
          }
          if ($rows['is_lock'] != '1')
            $unlock = 1;

          if ((accessbystatus($rowheader['id_status_pengajuan']) && $rows['is_lock'] != '1') or ($this->access_role['view_all'] or $this->access_role['view_all_unit'])) {
            echo "<td style='text-align:right'>";
            echo UI::showMenuMode('inlist', $rows[$pk]);
            echo "</td>";
          } else {
            echo "<td></td>";
          }
          echo "</tr>";
        }
        if (!$list['rows']) {
          echo "<tr><td colspan='" . (count($header) + 2) . "'>Data kosong</td></tr>";
        }
        ?>
      </tbody>
    </table>
    <?php echo $list['total'] > $limit ? UI::showPaging($paging, $page, $limit_arr, $limit, $list) : null ?>
  </div>
</div>

<hr />

<div class="row">
  <div class="col-sm-6">
    <h4 class='h4'>Residual Saat Ini
      <?= UI::tingkatRisiko('control_kemungkinan_penurunan', 'control_dampak_penurunan', $rowheader1, $editedheader1); ?>
    </h4>
  </div>
  <div class="col-sm-6">
    <div class="d-flex flex-row-reverse">
      <?php if (!$editedheader1 && $this->access_role['edit']  && ($is_edit or !$rowheader1['is_lock'] or ($this->access_role['view_all'] or $this->access_role['view_all_unit']))) {
      ?>
        <a class="btn btn-sm" href="<?= site_url("panelbackend/risk_control/index/$rowheader1[id_risiko]/0/1") ?>">
          <i class="bi bi-pencil"></i> Edit
        </a>
      <?php } ?>
    </div>
  </div>
</div>

<?php
include "_kriteria.php";
?>

<div class="row">
  <div class="col-sm-6">
    <?php
    $from = UI::createSelect('control_kemungkinan_penurunan', $mtkemungkinanrisikoarr, $rowheader1['control_kemungkinan_penurunan'], $editedheader1, $class = 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["control_kemungkinan_penurunan"], "control_kemungkinan_penurunan", 'Kemungkinan <a data-bs-toggle="modal" href="javascript:void(0)" data-bs-target="#kriteriaKemungkinan"><span class="material-icons" data-bs-toggle="tooltip" data-bs-original-title="Kriteria" style="font-size:inherit">info</span></a>', false, 4, $editedheader1);
    ?>
  </div>
  <div class="col-sm-6">
  </div>
</div>

<div class="row">
  <div class="col-sm-6">
    <?php
    $from = UI::createSelect('control_dampak_penurunan', $mtdampakrisikoarr, $rowheader1['control_dampak_penurunan'], $editedheader1, $class = 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["control_dampak_penurunan"], "control_dampak_penurunan", 'Dampak <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#kriteriaDampak"><span class="material-icons" style="font-size:inherit" data-bs-toggle="tooltip" data-bs-original-title="Kriteria">info</span></a>', false, 4, $editedheader1);
    ?>
  </div>
</div>

<div class="row">
  <div class="col-sm-6">
    <?php
    $from = UI::createTextBox('dampak_kuantitatif_current', $rowheader1['dampak_kuantitatif_current'], '', '', $editedheader1, 'form-control rupiah');
    echo UI::createFormGroup($from, $rules["dampak_kuantitatif_current"], "dampak_kuantitatif_current", "Dampak Kuantitatif", false, 4, $editedheader1);
    ?>
  </div>
  <div class="col-sm-6">
  </div>
</div>

<div class="row">
  <div class="col-sm-6">
    <?php
    $from = UI::showButtonMode("save", null, $editedheader1, null, 'btn-sm', $access_role_risiko);
    echo UI::createFormGroup($from, NULL, NULL, NULL, false, 4, $editedheader1);
    ?>
  </div>
</div>