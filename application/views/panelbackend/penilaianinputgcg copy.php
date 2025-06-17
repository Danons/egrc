<center>
	<h5><?= $rowkriteria['nama'] ?></h5>
</center>
<table class="table table-bordered">
	<?php foreach ($rows as $r) { ?>
		<tr>
			<td style="text-align: center; vertical-align: middle;" rowspan="2"><b><?= $r['tgl_label'] ?></b></td>
			<td colspan="3" style="border-right:#fff;">
				<?= UI::createUploadMultiple("file_" . $r['id_penilaian'], $row['file_' . $r['id_penilaian']], $page_ctrl, ($edited && ($r['status'] == '2' or !$r['status'])), "File PDF", false, "detail(" . $row['id_penilaian_periode'] . "," . $row['id_kriteria'] . ",1)") ?>
			</td>
			<td style="text-align: right;border-left:#fff;">
				<?php if (!$is_admin) { ?>
					<?php if ($row['file_' . $r['id_penilaian']] && $r['status'] == '0') { ?>
						<button type='button' class="btn btn-success btn-sm" onclick="ajukan(<?= $r['id_penilaian'] ?>, <?= $row['id_penilaian_periode'] ?>, <?= $row['id_kriteria'] ?>)">AJUKAN</button>
					<?php } ?>
				<?php } else { ?>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td>
				<?= UI::createSelect('status[' . $r['id_penilaian'] . "]", $statusarr, $r['status'], $is_admin, 'form-control ', "style='width:100%;display:inline;' onchange='change_status(this, {$r['id_penilaian']})'") ?>
			</td>
			<td colspan="3">
				<?= UI::createTextArea('keterangan[' . $r['id_penilaian'] . "]", $r['komentar'], '1', '50', $is_admin, 'form-control', "placeholder='Keterangan tambahan...' onblur='chenge_keterangan(this, {$r['id_penilaian']}, \$(\"#id_penilaian_komentar_$r[id_penilaian]\"))' style='width:100%'") ?>
				<?= UI::createTextHidden('id_penilaian_komentar_' . $r['id_penilaian'], $r['id_penilaian_komentar'], $is_admin) ?>
			</td>
		</tr>

		<tr>
			<th>Jenis</th>
			<th style="width: 100px;">Skor</th>
			<th style="width: 120px;">Tanggal</th>
			<th>Kesimpulan</th>
			<th>Referensi KK Dukungan</th>
		</tr>

		<tr>
			<td>
				Rapat Direksi
			</td>
			<td>
				<?= UI::createTextNumber('skor_rd[' . $r['id_penilaian'] . "]", $r['skor_rd'], '1', '1', $is_admin, 'form-control', 'min="0" step="0.01" max="1"') ?>
			</td>
			<td>
				<?= UI::createTextBox('tgl_rd[' . $r['id_penilaian'] . "]", $r['tgl_rd'], '100', '100', $is_admin, 'form-control datepicker') ?>
			</td>
			<td>
				<?= UI::createTextArea('simpulan_rd[' . $r['id_penilaian'] . "]", $r['simpulan_rd'], '1', '50', $is_admin, 'form-control', "placeholder='Keterangan tambahan...' onblur='chenge_keterangan(this, {$r['id_penilaian']}, \$(\"#id_penilaian_komentar_$r[id_penilaian]\"))' style='width:100%'") ?>
			</td>
			<td>
				<?= UI::createTextArea('saran_rd[' . $r['id_penilaian'] . "]", $r['saran_rd'], '1', '50', $is_admin, 'form-control', "placeholder='...' onblur='chenge_keterangan(this, {$r['id_penilaian']}, \$(\"#id_penilaian_komentar_$r[id_penilaian]\"))' style='width:100%'") ?>
			</td>
		</tr>

		<tr>
			<td>
				Kuisioner
			</td>
			<td>
				<?= UI::createTextNumber('skor_k[' . $r['id_penilaian'] . "]", $r['skor_k'], '1', '1', $is_admin, 'form-control', 'min="0" step="0.01" max="1"') ?>
			</td>
			<td>
				<?= UI::createTextBox('tgl_k[' . $r['id_penilaian'] . "]", $r['tgl_k'], '100', '100', $is_admin, 'form-control datepicker') ?>
			</td>
			<td>
				<?= UI::createTextArea('simpulan_k[' . $r['id_penilaian'] . "]", $r['simpulan_k'], '1', '50', $is_admin, 'form-control', "placeholder='Keterangan tambahan...' onblur='chenge_keterangan(this, {$r['id_penilaian']}, \$(\"#id_penilaian_komentar_$r[id_penilaian]\"))' style='width:100%'") ?>
			</td>
			<td>
				<?= UI::createTextArea('saran_k[' . $r['id_penilaian'] . "]", $r['saran_k'], '1', '50', $is_admin, 'form-control', "placeholder='Keterangan tambahan...' onblur='chenge_keterangan(this, {$r['id_penilaian']}, \$(\"#id_penilaian_komentar_$r[id_penilaian]\"))' style='width:100%'") ?>
			</td>
		</tr>

		<tr>
			<td>
				Wawancara
			</td>
			<td>
				<?= UI::createTextNumber('skor_w[' . $r['id_penilaian'] . "]", $r['skor_w'], '1', '1', $is_admin, 'form-control', 'min="0" step="0.01" max="1"') ?>
			</td>
			<td>
				<?= UI::createTextBox('tgl_w[' . $r['id_penilaian'] . "]", $r['tgl_w'], '100', '100', $is_admin, 'form-control datepicker') ?>
			</td>
			<td>
				<?= UI::createTextArea('simpulan_w[' . $r['id_penilaian'] . "]", $r['simpulan_w'], '1', '50', $is_admin, 'form-control', "placeholder='Keterangan tambahan...' onblur='chenge_keterangan(this, {$r['id_penilaian']}, \$(\"#id_penilaian_komentar_$r[id_penilaian]\"))' style='width:100%'") ?>
			</td>
			<td>
				<?= UI::createTextArea('saran_w[' . $r['id_penilaian'] . "]", $r['saran_w'], '1', '50', $is_admin, 'form-control', "placeholder='Keterangan tambahan...' onblur='chenge_keterangan(this, {$r['id_penilaian']}, \$(\"#id_penilaian_komentar_$r[id_penilaian]\"))' style='width:100%'") ?>
			</td>
		</tr>

		<tr>
			<td>
				Observasi
			</td>
			<td>
				<?= UI::createTextNumber('skor_o[' . $r['id_penilaian'] . "]", $r['skor_o'], '1', '1', $is_admin, 'form-control') ?>
			</td>
			<td>
				<?= UI::createTextBox('tgl_o[' . $r['id_penilaian'] . "]", $r['tgl_o'], '100', '100', $is_admin, 'form-control datepicker') ?>
			</td>
			<td>
				<?= UI::createTextArea('simpulan_o[' . $r['id_penilaian'] . "]", $r['simpulan_o'], '1', '50', $is_admin, 'form-control', "placeholder='Keterangan tambahan...' onblur='chenge_keterangan(this, {$r['id_penilaian']}, \$(\"#id_penilaian_komentar_$r[id_penilaian]\"))' style='width:100%'") ?>
			</td>
			<td>
				<?= UI::createTextArea('saran_o[' . $r['id_penilaian'] . "]", $r['saran_o'], '1', '50', $is_admin, 'form-control', "placeholder='Keterangan tambahan...' onblur='chenge_keterangan(this, {$r['id_penilaian']}, \$(\"#id_penilaian_komentar_$r[id_penilaian]\"))' style='width:100%'") ?>
			</td>
		</tr>
		<?php if ($is_admin) { ?>
			<tr>
				<td colspan="5" style="text-align: right;">
					<button type="button" class="btn btn-primary btn-sm" onclick="update_penilaian(<?= $r['id_penilaian'] ?>)">SAVE</button>
				</td>
			</tr>
		<?php } ?>

	<?php } ?>
</table>
<small>Jika <b>Tanggal</b> kosong maka skor tidak ikut dihitung</small>
<?php
echo UI::createTextHidden("id_penilaian_periode", $row['id_penilaian_periode'], $edited);
echo UI::createTextHidden("id_kriteria", $row['id_kriteria'], $edited);
?>

<script>
	$(function() {
		<?php if ($edited && $rows) {
			$date_format = $this->config->item("date_format"); ?>
			$(".datepicker").datepicker({
				format: "yyyy-mm-dd"
			});
			// $("#btnsave").show();
			$("#btnsave").hide();
		<?php } else { ?>
			$("#btnsave").hide();
		<?php } ?>
		$("#btnback").hide();
	});
</script>