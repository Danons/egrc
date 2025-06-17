<?php if (!$editedheader1 && $this->access_role['edit']  && ($is_edit or !$rowheader1['is_lock'] or ($this->access_role['view_all'] or $this->access_role['view_all_unit']))) {
    ?>
      <div style="position: absolute;top: 100px;right: 20px;">
        <a class="btn btn-sm btn-warning" href="<?= site_url("panelbackend/risk_control/index/$rowheader1[id_risiko]/0/1") ?>">
          <i class="bi bi-pencil"></i> Ubah
        </a>
      </div>
    <?php } ?>
    
    <div class="row">
      <div class="col-sm-6">
        <?php
        $from = UI::createTextArea('pengendalian_risiko_berjalan', $rowheader1['pengendalian_risiko_berjalan'], '', '', $editedheader1, 'form-control ', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["pengendalian_risiko_berjalan"], "pengendalian_risiko_berjalan", "Pengendalian Risiko Berjalan");
        ?>
    
        <?php
        $from = UI::createTextArea('target_penyelesaian', $rowheader1['target_penyelesaian'], '', '', $editedheader1, 'form-control', "");
        echo UI::createFormGroup($from, $rules["target_penyelesaian"], "target_penyelesaian", "Target Penyelesaian");
        ?>
      </div>
      <div class="col-sm-6">
    
        <?php
        $from =
          "<div class='row'><div class='col-sm-6'>" .
          UI::createTextBox('anggaran_biaya', ($editedheader1 ? $rowheader1['anggaran_biaya'] : rupiah($rowheader1['anggaran_biaya'])), '10', '10', $editedheader1, 'form-control rupiah', "style='text-align:right'") .
          "</div><div class='col-sm-2'>/</div><div class='col-sm-4'>" .
          UI::createSelect('id_interval_anggaran', $mtintervalarr, $rowheader1['id_interval_anggaran'], $editedheader1, 'form-control ', "style='width:100%;'") .
          "</div></div>";
        echo UI::createFormGroup($from, $rules["anggaran_biaya"], "anggaran_biaya", "Anggaran Biaya");
        ?>
    
        <?php
        $from =
          "<div class='row'><div class='col-sm-6'>" .
          UI::createTextBox('kuantifikasi', ($editedheader1 ? $rowheader1['kuantifikasi'] : rupiah($rowheader1['kuantifikasi'])), '10', '10', $editedheader1, 'form-control rupiah', "style='text-align:right'") .
          "</div><div class='col-sm-2'>/</div><div class='col-sm-4'>" .
          UI::createSelect('id_interval_kuantifikasi', $mtintervalarr, $rowheader1['id_interval_kuantifikasi'], $editedheader1, 'form-control ', "style='width:100%;'") .
          "</div></div>";
        echo UI::createFormGroup($from, $rules["kuantifikasi"], "kuantifikasi", "Kuantifikasi");
        ?>
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
    </div>
    
    <div class="row">
      <div class="col-sm-6">
        <?php
        $from = UI::createSelect('control_dampak_penurunan', $mtdampakrisikoarr, $rowheader1['control_dampak_penurunan'], $editedheader1, $class = 'form-control ', "style='width:100%;'");
        echo UI::createFormGroup($from, $rules["control_dampak_penurunan"], "control_dampak_penurunan", 'Dampak <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#kriteriaDampak"><span class="material-icons" style="font-size:inherit" data-bs-toggle="tooltip" data-bs-original-title="Kriteria">info</span></a>', false, 4, $editedheader1);
        ?>
      </div>
    </div>
    
    
    <?php
    if ($editedheader1) { ?>
      <div class="row">
        <div class="col-sm-6">
          <?php
          $from = UI::showButtonMode("save", null, $editedheader1, null, "btn-sm", $access_role_risiko);
          echo UI::createFormGroup($from, NULL, NULL, NULL, false, 4, $editedheader1);
          ?>
        </div>
      </div>
    <?php } ?>