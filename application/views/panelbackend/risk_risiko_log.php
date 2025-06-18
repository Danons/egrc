<?php
foreach ($rows as $i => $row) {
  $scorecard = $row['scorecard'];
  $risiko = $row['risiko'];
  $control = $row['control'];
  $mitigasi = $row['mitigasi'];

?>
  <hr />
  <br />
  <?php if ($i == 0) { ?>
    <h5><b><?= $scorecard['nkr'] ?></b></h5>
    <h5><b><?= $scorecard['nama'] ?></b></h5>
    <small><b>Owner : </b><?= $scorecard['nj'] ?></small><br />
    <!-- <small><b>Scope : </b><?= $scorecard['scope'] ?></small><br /> -->
  <?php } ?>
  <br />
  <br />
  <h4 style="background-color: #034485 !important; color: #fff; padding:5px; text-align:center;">IDENTIFIKASI RISIKO/ PELUANG</h4>
  <table border="0" width="100%">
    <tr>
      <td valign="top" width="50%" style="margin:0px;padding: 0px;">
        <table class="table">
          <tr>
            <td><b>Sasaran/ Kegiatan/ Proses</b></td>
            <td><?= $risiko['nss'] ?></td>
          </tr>
          <?php if ($risiko['nsk']) { ?>
            <tr>
              <td><b>Sasaran Kegiatan</b></td>
              <td><?= $risiko['nsk'] ?></td>
            </tr>
            <tr>
              <td><b>KPI</b></td>
              <td><?= $risiko['ksk'] ?></td>
            </tr>
          <?php } else { ?>
            <tr>
              <td><b>KPI</b></td>
              <td><?= $risiko['kss'] ?></td>
            </tr>
          <?php } ?>
        </table>
      </td>
      <td valign="top" width="50%" style="margin:0px;padding: 0px;">
        <table class="table">
          <tr>
            <td><b>Tgl. Risiko</b></td>
            <td><?= Eng2Ind($risiko['tgl_risiko']) ?></td>
          </tr>
          <tr>
            <td><b>Nomor Risiko</b></td>
            <td><?= $risiko['nomor'] ?></td>
          </tr>
          <tr>
            <td><b>Jenis</b></td>
            <td><?= ["0" => "Non Rutin", "1" => "Rutin"][$risiko['is_rutin']] ?></td>
          </tr>
          <tr>
            <td><b>Kategori</b></td>
            <td><?= $taksonomiareaarr[$risiko['id_taksonomi_area']] ?></td>
          </tr>
          <tr>
            <td><b>Sumber Risiko / Peluang</b></td>
            <td><?= $risiko['penyebab'] ?></td>
          </tr>
          <tr>
            <td><b>Dampak</b></td>
            <td><?= $risiko['dampak'] ?></td>
          </tr>
          <tr>
            <td><b>Operasional</b></td>
            <td><?= $operasionalarr[$risiko['id_aspek_lingkungan']] ?></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>


  <br />
  <br />
  <h4 style="background-color: #034485 !important; color: #fff; padding:5px; text-align:center;">ANALISIS RISIKO / PELUANG</h4>

  <table class="table">
    <tr>
      <td colspan="4"><b><u>RISIKO INHEREN</u></b></td>
    </tr>
    <tr>
      <th>Risk(1)/Opportunity(-1)</th>
      <th>Kemungkinan</th>
      <th>Dampak</th>
      <th>Tingkat</th>
      <th>Dampak Kuantitatif</th>
      <th>Kriteria</th>
      <th>Kriteria</th>
    </tr>
    <tr>
      <td><?= $risiko['is_opp_inherent'] ?></td>
      <td><?= $mtkemungkinanrisikoarr[$risiko['inheren_kemungkinan']] ?></td>
      <td><?= $mtdampakrisikoarr[$risiko['inheren_dampak']] ?></td>
      <td><?= UI::tingkatRisiko('inheren_kemungkinan', 'inheren_dampak', $risiko, false); ?></td>
      <td><?= $risiko['dampak_kuantitatif_inheren'] ?></td>
      <td><?= $kriteriakemungkinanarr[$risiko['id_kriteria_kemungkinan']] ?></td>
      <td><?= $risiko['nk'] ?></td>
      <!-- <td><?= UI::tingkatRisiko('inheren_kemungkinan', 'inheren_dampak', $risiko, false, false) ?></td> -->
    </tr>
  </table>
  <br />
  <table class="table">
    <tr>
      <th>Pengendalian Berjalan</th>
      <!-- <th>K/D</th>
                <th>Interval</th> -->
      <th>Efektifitas</th>
    </tr>
    <?php
    foreach ($control as $c) { ?>
      <tbody>
        <tr>
          <td><?= $c['nama'] ?></td>
          <td><?= $mtpengukuranarr[$c['id_pengukuran']] ?></td>
          <!-- <td><?= $c['interval'] ?></td>
          <?php if ($c['is_efektif'] == 2) { ?>
            <td><?php echo "Tidak Efektif"; ?></td>
          <?php } else if ($c['is_efektif'] == 1) { ?>
            <td><?php echo "Efektif"; ?></td>
          <?php } ?> -->
        </tr>
      </tbody>
    <?php } ?>
  </table>

  <br />
  <br />
  <table class="table">
    <tr>
      <td colspan="3"><b><u>Risiko Residual Saat Ini</u></b></td>
    </tr>
    <tr>
      <!-- <th>Kemungkinan</th>
        <th>Dampak</th>
        <th>Tingkat</th> -->
      <th>Risk(1)/Opportunity(-1)</th>
      <th>Kemungkinan</th>
      <th>Dampak</th>
      <th>Tingkat</th>
    </tr>
    <tr>
      <td><?= $risiko['is_opp_inherent'] ?></td>
      <td><?= $mtkemungkinanrisikoarr[$risiko['control_kemungkinan_penurunan']] ?></td>
      <td><?= $mtdampakrisikoarr[$risiko['control_dampak_penurunan']] ?></td>
      <td><?= UI::tingkatRisiko('control_kemungkinan_penurunan', 'control_dampak_penurunan', $risiko, false) ?></td>
    </tr>
  </table>

  <br />
  <br />
  <h4 style="background-color: #034485 !important; color: #fff; padding:5px; text-align:center;">PENGENDALIAN LANJUTAN</h4>
  <table class="table">
    <tr>
      <th>Pengendalian Lanjutan </th>
      <th>Sasaran</th>
      <!-- <th>Deadline</th> -->
      <!-- <th>Penanggung Jawab</th> -->
      <!-- <th>K/D</th> -->
      <!-- <th>Biaya Mitigasi</th> -->
      <!-- <th>CBA</th> -->
      <!-- <th>Progress</th> -->
    </tr>
    <tbody>
      <?php
      foreach ($mitigasi as $m) { ?>
        <tr>
          <td><?= $m['nama'] ?></td>
          <td><?= $m['sasaran'] ?></td>
          <!-- <td><?= $m['jabatan'] ?></td>
          <td><?= $m['menurunkan_dampak_kemungkinan'] ?></td>
          <td><?= rupiah($m['biaya']) ?></td>
          <td><?= $m['cba'] ?> %</td>
          <td><?= $m['status_progress'] ?></td> -->
        </tr>
      <?php } ?>
    </tbody>
  </table>
  <br />
  <br />
  <table class="table">
    <tr>
      <td width="30%"><b>Tingkat Prioritas</b></td>
      <td><span class="badge" style="background-color:<?= $prioritaswarna[$risiko['id_prioritas']] ?>"><?= $prioritas[$risiko['id_prioritas']] ?></span></td>
    </tr>
    <tr>
      <td width="30%"><b>Integrasi Internal</b></td>
      <td><?= $risiko['integrasi_internal'] ?></td>
    </tr>
    <tr>
      <td width="30%"><b>Integrasi Eksternal</b></td>
      <td><?= $risiko['integrasi_eksternal'] ?></td>
    </tr>
  </table>
  <br />
  <br />
  <table class="table">
    <tr>
      <td colspan="3"><b><u>Target Residual</u></b></td>
    </tr>
    <tr>
      <th>Risk(1)/Opportunity(-1)</th>
      <th>Kemungkinan</th>
      <th>Dampak</th>
      <th>Tingkat</th>
    </tr>
    <tr>
      <td><?= $risiko['is_opp_inherent'] ?></td>
      <td><?= $mtkemungkinanrisikoarr[$risiko['residual_target_kemungkinan']] ?></td>
      <td><?= $mtdampakrisikoarr[$risiko['residual_target_dampak']] ?></td>
      <td><?= UI::tingkatRisiko('residual_target_kemungkinan', 'residual_target_dampak', $risiko, false) ?></td>
    </tr>
  </table>

  <br />
  <br />
  <h4 style="background-color: #034485 !important; color: #fff; padding:5px; text-align:center;">EVALUASI RISIKO / PELUANG</h4>

  <table class="table">
    <!-- <tr>
      <td><b>Progress Capaian Kinerja</b></td>
      <td><?= $risiko['progress_capaian_kinerja'] ?></td>
    </tr>
    <tr>
      <td width="30%"><b>Hambatan/Kendala</b></td>
      <td><?= $risiko['hambatan_kendala'] ?></td>
    </tr>
    <tr>
      <td><b>Penyesuaian Tindakan Mitigasi</b></td>
      <td><?= $risiko['penyesuaian_tindakan_mitigasi'] ?></td>
    </tr> -->
    <!-- <tr>
      <td><b>Hasil Penanganan Terhadap Sasaran</b></td>
      <td><?= $risiko['hasil_mitigasi_terhadap_sasaran'] ?></td>
    </tr> -->
    <tr>
      <td width="30%"><b>Hasil Penanganan Terhadap Sasaran</b></td>
      <td><?= $risiko['hasil_mitigasi_terhadap_sasaran'] ?></td>
    </tr>
    <tr>
      <td width="30%"><b>Rekomendasi</b></td>
      <td><?= $risiko['penyesuaian_tindakan_mitigasi'] ?></td>
    </tr>
  </table>

  <table class="table">
    <tr>
      <td colspan="3"><b><u>RISIDUAL RISK HASIL EVALUASI</u></b></td>
    </tr>
    <tr>
      <th>Risk(1)/Opportunity(-1)</th>
      <th>Kemungkinan</th>
      <th>Dampak</th>
      <th>Tingkat</th>
    </tr>
    <tr>
      <td><?= $risiko['is_opp_inherent'] ?></td>
      <td><?= $mtkemungkinanrisikoarr[$risiko['residual_kemungkinan_evaluasi']] ?></td>
      <td><?= $mtdampakrisikoarr[$risiko['residual_dampak_evaluasi']] ?></td>
      <td><?= UI::tingkatRisiko('residual_kemungkinan_evaluasi', 'residual_dampak_evaluasi', $risiko, false) ?></td>
    </tr>
  </table>
  <br />
  <?php
  $label_close = "";
  switch ($risiko['status_risiko']) {
    case '0':
      $label_close = "<span class='badge bg-light text-dark'>CLOSED</span><br/><br/>RISIKO SUDAH SELESAI";
      break;
    case '2':
      $label_close = "<span class='badge bg-warning'>BERLANJUT</span><br/><br/>STATUS RISIKO MASIH PERLU DI LAKUKAN KONTROL DAN MITIGASI";
      break;

    default:

      if ($risiko['id_risiko_sebelum']) {
        $label_close = "<span class='badge bg-success'>BERLANJUT</span><br/><br/>STATUS RISIKO MASIH PERLU DI LAKUKAN KONTROL DAN MITIGASI";
      } else {
        $label_close = "<span class='badge bg-success'>OPEN</span>";
      }
      break;
  }
  ?>
  <center>
    <h4><?= $label_close ?></h4>
  </center>
  <center>TGL. PROSES : <?= strtoupper(Eng2Ind($risiko['tgl_close'])) ?></center>
  <br />
  <br />
  <style type="text/css">
    .table {
      margin-bottom: -1px;
    }
  </style>
<?php
}
