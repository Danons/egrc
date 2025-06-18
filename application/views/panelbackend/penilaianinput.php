<center>
	<h5><?= $rowkriteria['nama'] ?></h5>
</center>
<table style="width: 100%;">
	<?php foreach ($rows as $r) { ?>
		<?php if ($rowkriteria['d']) { ?>
			<tr>
				<td colspan="3"><br />
					<?php if ($rowkriteria['nilai_sebelum']) { ?>
						<h5>Dokumen <span style="margin-left: 300px;">Nilai Dokumen Sebelumnya : <?= $rowkriteria['nilai_sebelum']['d'] ? $rowkriteria['nilai_sebelum']['d'] : '0' ?></span></h5>
					<?php } else { ?>
						<h5>Dokumen</h5>
					<?php } ?>
					<?php
					$arr = [];
					if ($r['dokumen']) {
						foreach ($r['dokumen'] as $r1) {
							$arr[] = '<a target="_BLANK" href="' . site_url("panelbackend/penilaian_" . $this->viewadd . "/open_file/" . $r1['id_dokumen_files']) . '">' . $r1['client_name'] . '</a>';
						}
						echo implode("<br/>", $arr);
					} else echo "<i>Tidak ada</i>"; ?>
				</td>
			</tr>

			<tr>
				<td style="width:100px">
					<?= UI::createSelect(
						'skor_d[' . $r['id_penilaian'] . "]",
						$id_kategori_jenis == 1 ? [
							"0" => "0",
							"0.25" => "0.25",
							"0.5" => "0.5",
							"0.75" => "0.75",
							"1" => "1",
						] : [
							"" => "-",
							"0" => "Tidak",
							"0.5" => "Sebagian",
							"1" => "Ya",
						],
						$r['skor_d'],
						$is_admin,
						'form-control'
					) ?>
				</td>
				<td>
					<?= UI::createTextArea('simpulan_d[' . $r['id_penilaian'] . "]", $r['simpulan_d'], '1', '50', $is_admin, 'form-control', "placeholder='Area Of Improvement...' onblur='chenge_keterangan(this, {$r['id_penilaian']}, \$(\"#id_penilaian_komentar_$r[id_penilaian]\"))' style='width:100%'") ?>
				</td>
				<td>
					<?= UI::createTextArea('saran_d[' . $r['id_penilaian'] . "]", $r['saran_d'], '1', '50', $is_admin, 'form-control', "placeholder='Usulan Saran...' onblur='chenge_keterangan(this, {$r['id_penilaian']}, \$(\"#id_penilaian_komentar_$r[id_penilaian]\"))' style='width:100%'") ?>
				</td>
			</tr>
		<?php } ?>
		<?php if ($rowkriteria['k']) { ?>
			<tr>
				<td colspan="3"><br />
					<?php if ($rowkriteria['nilai_sebelum']) { ?>
						<h5>Kuisioner <span style="margin-left: 300px;">Nilai Kuisioner Sebelumnya : <?= $rowkriteria['nilai_sebelum']['k'] ?  $rowkriteria['nilai_sebelum']['k'] : '0' ?></span></h5>
					<?php } else { ?>
						<h5>Kuisioner</h5>
					<?php } ?>
					<?php
					if ($r['quisioner']['k'])
						foreach ($r['quisioner']['k'] as $r1) {
							$margin = 0;
							for ($l = 1; $l < $r1['level']; $l++) {
								$margin += 30;
							}
					?>
						<?php if ($r1['level'] == 1) { ?>
							<hr />
						<?php } ?>
						<div style="margin-left:<?= $margin ?>px">
							<?= $r1['pertanyaan'] ?><br />
							<?php if ($r1['1sampai5']) { ?>
								<table>
									<tr>
										<th><small>Sangat Kurang&nbsp;&nbsp;</small></th>
										<th><small>Kurang&nbsp;&nbsp;</small></th>
										<th><small>Cukup&nbsp;&nbsp;</small></th>
										<th><small>Baik&nbsp;&nbsp;</small></th>
										<th><small>Sangat Baik</small></th>
									</tr>
									<!-- <tr>
									<td> <?= $r1['1sampai5'][1] / $r1['total'] * 100 ?>%</td>
									<td><?= $r1['1sampai5'][2] / $r1['total'] * 100 ?>%</td>
									<td><?= $r1['1sampai5'][3] / $r1['total'] * 100 ?>%</td>
									<td><?= $r1['1sampai5'][4] / $r1['total'] * 100 ?>%</td>
									<td><?= $r1['1sampai5'][5] / $r1['total'] * 100 ?>%</td>
								</tr> -->
									<tr>
										<td><?= (int)$r1['1sampai5'][1] ?></td>
										<td><?= (int)$r1['1sampai5'][2] ?></td>
										<td><?= (int)$r1['1sampai5'][3] ?></td>
										<td><?= (int)$r1['1sampai5'][4] ?></td>
										<td><?= (int)$r1['1sampai5'][5] ?></td>
									</tr>
								</table>
							<?php } ?>

							<?php if ($r1['yatidak']) { ?>
								<table>
									<tr>
										<th><small>Tidak&nbsp;&nbsp;</small></th>
										<th><small>Ya</small></th>
									</tr>
									<!-- <tr>
									<td><?= $r1['yatidak'][1] / $r1['total'] * 100 ?>%</td>
									<td><?= $r1['yatidak'][5] / $r1['total'] * 100 ?>%</td>
								</tr> -->
									<tr>
										<td><?= (int)$r1['yatidak'][1] ?></td>
										<td><?= (int)$r1['yatidak'][5] ?></td>
									</tr>
								</table>
							<?php } ?>
						</div>
					<?php
						}
					else echo "<i>Tidak ada</i>"; ?>
					<hr />
					<?php $totalnilai = $r['quisioner']['totalnilai'] / $r['quisioner']['totalsemua'] * 100; ?>
					<?php
					$totalnilaisebelum = $r['quisioner']['totalnilaisebelum'] / $r['quisioner']['totalsemuasebelum'] * 100;
					?>
					<h5>Nilai Kuisioner : <?= $totalnilai ?>
						<?php
						if ($totalnilai < 25)
							echo "<span style='color:red'>Sangat Kurang</span>";
						elseif ($totalnilai < 50)
							echo "<span style='color:orange'>Kurang</span>";
						elseif ($totalnilai < 75)
							echo "<span style='color:yellow'>Cukup</span>";
						elseif ($totalnilai < 85)
							echo "<span style='color:green'>Baik</span>";
						else
							echo "<span style='color:blue'>Sangat Baik</span>";

						if ($r['skor_k'] === null) {
							if ($id_kategori_jenis == 1) {
								if ($totalnilai < 25)
									$r['skor_k'] = "0";
								elseif ($totalnilai < 50)
									$r['skor_k'] = "0.25";
								elseif ($totalnilai < 75)
									$r['skor_k'] = "0.5";
								elseif ($totalnilai < 85)
									$r['skor_k'] = "0.75";
								else
									$r['skor_k'] = "1";
							} else {
								if ($totalnilai < 25)
									$r['skor_k'] = "0";
								elseif ($totalnilai < 75)
									$r['skor_k'] = "0.5";
								else
									$r['skor_k'] = "1";
							}
						}
						?>

						<span style="margin-left: 100px;">Nilai Kuisioner Sebelum : <?= $totalnilaisebelum ?>
							<?php
							if ($totalnilai < 25)
								echo "<span style='color:red'>Sangat Kurang</span>";
							elseif ($totalnilai < 50)
								echo "<span style='color:orange'>Kurang</span>";
							elseif ($totalnilai < 75)
								echo "<span style='color:yellow'>Cukup</span>";
							elseif ($totalnilai < 85)
								echo "<span style='color:green'>Baik</span>";
							else
								echo "<span style='color:blue'>Sangat Baik</span>";

							if ($r['skor_k'] === null) {
								if ($id_kategori_jenis == 1) {
									if ($totalnilai < 25)
										$r['skor_k'] = "0";
									elseif ($totalnilai < 50)
										$r['skor_k'] = "0.25";
									elseif ($totalnilai < 75)
										$r['skor_k'] = "0.5";
									elseif ($totalnilai < 85)
										$r['skor_k'] = "0.75";
									else
										$r['skor_k'] = "1";
								} else {
									if ($totalnilai < 25)
										$r['skor_k'] = "0";
									elseif ($totalnilai < 75)
										$r['skor_k'] = "0.5";
									else
										$r['skor_k'] = "1";
								}
							}
							?>
						</span>
					</h5>
				</td>
			</tr>

			<tr>
				<td style="width:100px">
					<?= UI::createSelect(
						'skor_k[' . $r['id_penilaian'] . "]",
						$id_kategori_jenis == 1 ? [
							"0" => "0",
							"0.25" => "0.25",
							"0.5" => "0.5",
							"0.75" => "0.75",
							"1" => "1",
						] : [
							"" => "-",
							"0" => "Tidak",
							"0.5" => "Sebagian",
							"1" => "Ya",
						],
						$r['skor_k'],
						$is_admin,
						'form-control'
					) ?>
				</td>
				<td>
					<?= UI::createTextArea('simpulan_k[' . $r['id_penilaian'] . "]", $r['simpulan_k'], '1', '50', $is_admin, 'form-control', "placeholder='Keterangan tambahan...' onblur='chenge_keterangan(this, {$r['id_penilaian']}, \$(\"#id_penilaian_komentar_$r[id_penilaian]\"))' style='width:100%'") ?>
				</td>
				<td>
					<?= UI::createTextArea('saran_k[' . $r['id_penilaian'] . "]", $r['saran_k'], '1', '50', $is_admin, 'form-control', "placeholder='Keterangan tambahan...' onblur='chenge_keterangan(this, {$r['id_penilaian']}, \$(\"#id_penilaian_komentar_$r[id_penilaian]\"))' style='width:100%'") ?>
				</td>
			</tr>
		<?php } ?>
		<?php if ($rowkriteria['w']) { ?>
			<tr>
				<td colspan="3"><br />
					<?php if ($rowkriteria['nilai_sebelum']) { ?>
						<h5>Wawancara <span style="margin-left: 300px;">Nilai Wawancara Sebelumnya : <?= $rowkriteria['nilai_sebelum']['w'] ? $rowkriteria['nilai_sebelum']['w'] : '0' ?></span></h5>
					<?php } else { ?>
						<h5>Wawancara</h5>
					<?php } ?>
					<?php
					if ($r['quisioner']['w'])
						foreach ($r['quisioner']['w'] as $r1) { ?>
						<?= $r1['pertanyaan'] ?><br />
						<ol>
							<?php foreach ($r1['jawaban'] as $jawaban) { ?>
								<li><?= $jawaban ?></li>
							<?php } ?>
						</ol>
						<hr />
					<?php }
					else echo "<i>Tidak ada</i>"; ?>
				</td>
			</tr>

			<tr>
				<td style="width:100px">
					<?= UI::createSelect(
						'skor_w[' . $r['id_penilaian'] . "]",
						$id_kategori_jenis == 1 ? [
							"0" => "0",
							"0.25" => "0.25",
							"0.5" => "0.5",
							"0.75" => "0.75",
							"1" => "1",
						] : [
							"" => "-",
							"0" => "Tidak",
							"0.5" => "Sebagian",
							"1" => "Ya",
						],
						$r['skor_w'],
						$is_admin,
						'form-control'
					) ?>
				</td>
				<td>
					<?= UI::createTextArea('simpulan_w[' . $r['id_penilaian'] . "]", $r['simpulan_w'], '1', '50', $is_admin, 'form-control', "placeholder='Keterangan tambahan...' onblur='chenge_keterangan(this, {$r['id_penilaian']}, \$(\"#id_penilaian_komentar_$r[id_penilaian]\"))' style='width:100%'") ?>
				</td>
				<td>
					<?= UI::createTextArea('saran_w[' . $r['id_penilaian'] . "]", $r['saran_w'], '1', '50', $is_admin, 'form-control', "placeholder='Keterangan tambahan...' onblur='chenge_keterangan(this, {$r['id_penilaian']}, \$(\"#id_penilaian_komentar_$r[id_penilaian]\"))' style='width:100%'") ?>
				</td>
			</tr>
		<?php } ?>
		<?php if ($rowkriteria['o']) { ?>
			<tr>
				<td colspan="3"><br />
					<?php if ($rowkriteria['nilai_sebelum']) { ?>
						<h5>Observasi <span style="margin-left: 300px;">Nilai Observasi Sebelumnya : <?= $rowkriteria['nilai_sebelum']['o'] ? $rowkriteria['nilai_sebelum']['o'] : '0' ?></span></h5>
					<?php } else { ?>
						<h5>Observasi</h5>
					<?php } ?>
				</td>
			</tr>

			<tr>
				<td style="width:100px">
					<?= UI::createSelect(
						'skor_o[' . $r['id_penilaian'] . "]",
						$id_kategori_jenis == 1 ? [
							"0" => "0",
							"0.25" => "0.25",
							"0.5" => "0.5",
							"0.75" => "0.75",
							"1" => "1",
						] : [
							"" => "-",
							"0" => "Tidak",
							"0.5" => "Sebagian",
							"1" => "Ya",
						],
						$r['skor_o'],
						$is_admin,
						'form-control'
					) ?>
				</td>
				<td>
					<?= UI::createTextArea('simpulan_o[' . $r['id_penilaian'] . "]", $r['simpulan_o'], '1', '50', $is_admin, 'form-control', "placeholder='Keterangan tambahan...' onblur='chenge_keterangan(this, {$r['id_penilaian']}, \$(\"#id_penilaian_komentar_$r[id_penilaian]\"))' style='width:100%'") ?>
				</td>
				<td>
					<?= UI::createTextArea('saran_o[' . $r['id_penilaian'] . "]", $r['saran_o'], '1', '50', $is_admin, 'form-control', "placeholder='Keterangan tambahan...' onblur='chenge_keterangan(this, {$r['id_penilaian']}, \$(\"#id_penilaian_komentar_$r[id_penilaian]\"))' style='width:100%'") ?>
				</td>
			</tr>
		<?php } ?>
		<?php /* <tr>
			<th>Penilaian</th>
			<th>AOI</th>
			<th>Saran</th>
		</tr>
		<tr>
			<td style="width:100px">
				<?= UI::createSelect(
					'skor_f[' . $r['id_penilaian'] . "]",
					$id_kategori_jenis == 1 ? [
						"0" => "0",
						"0.25" => "0.25",
						"0.5" => "0.5",
						"0.75" => "0.75",
						"1" => "1",
					] : [
						"" => "-",
						"0" => "Tidak",
						"0.5" => "Sebagian",
						"1" => "Ya",
					],
					$r['skor_f'],
					$is_admin,
					'form-control'
				) ?>
			</td>
			<td>
				<?= UI::createTextArea('simpulan_f[' . $r['id_penilaian'] . "]", $r['simpulan_f'], '1', '50', $is_admin, 'form-control', "placeholder='Keterangan tambahan...' onblur='chenge_keterangan(this, {$r['id_penilaian']}, \$(\"#id_penilaian_komentar_$r[id_penilaian]\"))' style='width:100%'") ?>
			</td>
			<td>
				<?= UI::createTextArea('saran_f[' . $r['id_penilaian'] . "]", $r['saran_f'], '1', '50', $is_admin, 'form-control', "placeholder='Keterangan tambahan...' onblur='chenge_keterangan(this, {$r['id_penilaian']}, \$(\"#id_penilaian_komentar_$r[id_penilaian]\"))' style='width:100%'") ?>
			</td>
			<?php if ($is_admin) { ?>
				<td style="text-align: right;">
					<button type="button" class="btn btn-primary btn-sm" onclick="update_penilaian(<?= $r['id_penilaian'] ?>)">SAVE</button>
				</td>
			<?php } ?>
		</tr> */ ?>
		<tr>
			<?php if ($is_admin) { ?>
				<td style="text-align: right;" colspan="3">
					<button type="button" class="btn btn-primary btn-sm" onclick="update_penilaian(<?= $r['id_penilaian'] ?>)">SAVE</button>
				</td>
			<?php } ?>
		</tr>
	<?php } ?>
</table>
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
			$("#btnsavemodalkriteria").html('');
			$("#btnsavemodalkriteria").show();
		<?php } else { ?>
			$("#btnsavemodalkriteria").html('');
			$("#btnsavemodalkriteria").hide();
		<?php } ?>
	});
</script>