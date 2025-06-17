<center>
	<h5>Rekap Quisioner</h5>
</center>
<div>
	<?php
	foreach ($rows as $row) {
		$jabatan[$row['id_jabatan']] = $row['nama_jabatan'];
		$jumlah_pertanyaan[$row['id_jabatan']]['yatidak']['total'] = 0;
		$jumlah_pertanyaan[$row['id_jabatan']]['yatidak']['nilai'][1] = 0;
		$jumlah_pertanyaan[$row['id_jabatan']]['yatidak']['nilai'][5] = 0;
		$jumlah_pertanyaan[$row['id_jabatan']]['1sampai5']['total'] = 0;
		$jumlah_pertanyaan[$row['id_jabatan']]['1sampai5']['nilai'][1] = 0;
		$jumlah_pertanyaan[$row['id_jabatan']]['1sampai5']['nilai'][2] = 0;
		$jumlah_pertanyaan[$row['id_jabatan']]['1sampai5']['nilai'][3] = 0;
		$jumlah_pertanyaan[$row['id_jabatan']]['1sampai5']['nilai'][4] = 0;
		$jumlah_pertanyaan[$row['id_jabatan']]['1sampai5']['nilai'][5] = 0;
	}

	foreach ($rows as $row1) {
		foreach ($jabatan as $id_jabatan => $nama_jabatan) {
			if ($row1['id_jabatan'] == $id_jabatan) {
				$pertanyaan[$id_jabatan][$row1['id_quisioner']]['pertanyaan'] = $row1['pertanyaan'];
				$pertanyaan[$id_jabatan][$row1['id_quisioner']]['level'] = $row1['level'];
				$pertanyaan[$id_jabatan][$row1['id_quisioner']]['jenis_jawaban'] = $row1['jenis_jawaban'];
				if ($row1['jenis_jawaban'] == 'yatidak') {
					$jumlah_pertanyaan[$id_jabatan]['yatidak']['total']++;
					if ($row1['nilai_jawaban'] == 1) {
						$jumlah_pertanyaan[$id_jabatan]['yatidak']['nilai'][1] += 1;
						$pertanyaan[$id_jabatan][$row1['id_quisioner']]['jumlah_jawaban']['tidak'][$row1['id_user']] = $row1['nilai_jawaban'];
					} elseif ($row1['nilai_jawaban'] == 5) {
						$jumlah_pertanyaan[$id_jabatan]['yatidak']['nilai'][5] += 5;
						$pertanyaan[$id_jabatan][$row1['id_quisioner']]['jumlah_jawaban']['ya'][$row1['id_user']] = $row1['nilai_jawaban'];
					}
				} elseif ($row1['jenis_jawaban'] == '1sampai5') {
					$jumlah_pertanyaan[$id_jabatan]['1sampai5']['total']++;
					if ($row1['nilai_jawaban'] == 1) {
						$jumlah_pertanyaan[$id_jabatan]['1sampai5']['nilai'][1] += 1;
						$pertanyaan[$id_jabatan][$row1['id_quisioner']]['jumlah_jawaban'][1][$row1['id_user']] = $row1['nilai_jawaban'];
					} elseif ($row1['nilai_jawaban'] == 2) {
						$jumlah_pertanyaan[$id_jabatan]['1sampai5']['nilai'][2] += 2;
						$pertanyaan[$id_jabatan][$row1['id_quisioner']]['jumlah_jawaban'][2][$row1['id_user']] = $row1['nilai_jawaban'];
					} elseif ($row1['nilai_jawaban'] == 3) {
						$jumlah_pertanyaan[$id_jabatan]['1sampai5']['nilai'][3] += 3;
						$pertanyaan[$id_jabatan][$row1['id_quisioner']]['jumlah_jawaban'][3][$row1['id_user']] = $row1['nilai_jawaban'];
					} elseif ($row1['nilai_jawaban'] == 4) {
						$jumlah_pertanyaan[$id_jabatan]['1sampai5']['nilai'][4] += 4;
						$pertanyaan[$id_jabatan][$row1['id_quisioner']]['jumlah_jawaban'][4][$row1['id_user']] = $row1['nilai_jawaban'];
					} elseif ($row1['nilai_jawaban'] == 5) {
						$jumlah_pertanyaan[$id_jabatan]['1sampai5']['nilai'][5] += 5;
						$pertanyaan[$id_jabatan][$row1['id_quisioner']]['jumlah_jawaban'][5][$row1['id_user']] = $row1['nilai_jawaban'];
					}
				} else {
					$pertanyaan[$id_jabatan][$row1['id_quisioner']]['jumlah_jawaban'][$row1['id_user']] = $row1['nilai_jawaban'];
				}
			}
		}
	}
	?>



	<?php
	#pertanyaan pegawai
	// dpr($jumlah_pertanyaan, 1);
	if ($rows_user)
		foreach ($rows_user as $row) {
			$user[$row['id_user']] = $row['id_user'];
		}

	if ($rows_user)
		foreach ($rows_user as $row1) {
			foreach ($user as $id_user => $id_user) {
				if ($row1['id_user'] == $id_user) {
					$pertanyaan_user[$id_user][$row1['id_quisioner']]['pertanyaan'] = $row1['pertanyaan'];
					$pertanyaan_user[$id_user][$row1['id_quisioner']]['level'] = $row1['level'];
					$pertanyaan_user[$id_user][$row1['id_quisioner']]['jenis_jawaban'] = $row1['jenis_jawaban'];
					if ($row1['jenis_jawaban'] == 'yatidak') {
						if ($row1['nilai_jawaban'] == 1) {
							$pertanyaan_user[$id_user][$row1['id_quisioner']]['jumlah_jawaban']['tidak'][$row1['id_user']] = $row1['nilai_jawaban'];
						} elseif ($row1['nilai_jawaban'] == 5) {
							$pertanyaan_user[$id_user][$row1['id_quisioner']]['jumlah_jawaban']['ya'][$row1['id_user']] = $row1['nilai_jawaban'];
						}
					} elseif ($row1['jenis_jawaban'] == '1sampai5') {
						if ($row1['nilai_jawaban'] == 1) {
							$pertanyaan_user[$id_user][$row1['id_quisioner']]['jumlah_jawaban'][1][$row1['id_user']] = $row1['nilai_jawaban'];
						} elseif ($row1['nilai_jawaban'] == 2) {
							$pertanyaan_user[$id_user][$row1['id_quisioner']]['jumlah_jawaban'][2][$row1['id_user']] = $row1['nilai_jawaban'];
						} elseif ($row1['nilai_jawaban'] == 3) {
							$pertanyaan_user[$id_user][$row1['id_quisioner']]['jumlah_jawaban'][3][$row1['id_user']] = $row1['nilai_jawaban'];
						} elseif ($row1['nilai_jawaban'] == 4) {
							$pertanyaan_user[$id_user][$row1['id_quisioner']]['jumlah_jawaban'][4][$row1['id_user']] = $row1['nilai_jawaban'];
						} elseif ($row1['nilai_jawaban'] == 5) {
							$pertanyaan_user[$id_user][$row1['id_quisioner']]['jumlah_jawaban'][5][$row1['id_user']] = $row1['nilai_jawaban'];
						}
					} else {
						$pertanyaan_user[$id_user][$row1['id_quisioner']]['jumlah_jawaban'][$row1['id_user']] = $row1['nilai_jawaban'];
					}
				}
			}
		}

	?>


	<?php
	#tampilka pertanyaan jabatan
	foreach ($pertanyaan as $id_jabatan => $rows_pertanyaan) {
		echo "<hr/>";
	?>
		<h5 class="fw-semibold text-dark"><?= $jabatan[$id_jabatan] ?></h5>
		<?php foreach ($rows_pertanyaan as $pertanyaan1) {

			$margin = 0;
			for ($l = 1; $l < $pertanyaan1['level']; $l++) {
				$margin += 30;
			}
			if ($pertanyaan1['level'] == 1)
				echo "<hr/>";
			echo "<p class='mb-0' style='margin-left:" . $margin . "px;'>" . $pertanyaan1['pertanyaan'] . "</p>";
			if ($pertanyaan1['jenis_jawaban'] == 'yatidak') { ?>
				<div class='' style="margin-left: <?= $margin ?>px;">
					<table>
						<tr class='m-0'>
							<td class='m-0 fw-bold text-dark'><small>Ya</small></td>
							<td class='ps-2 fw-bold text-dark'><small>Tidak</small></td>
						</tr>
						<tr>
							<td class="m-0"><small><?= $pertanyaan1['jumlah_jawaban']["ya"] ? count($pertanyaan1['jumlah_jawaban']["ya"]) : '0' ?></small></td>
							<td class="ps-3"><small><?= $pertanyaan1['jumlah_jawaban']["tidak"] ? count($pertanyaan1['jumlah_jawaban']["tidak"]) : '0' ?></small></td>
						</tr>
					</table>
				</div>

			<?php } elseif ($pertanyaan1['jenis_jawaban'] == '1sampai5') { ?>

				<div class='' style="margin-left: <?= $margin ?>px;">
					<table>
						<tr class='m-0'>
							<td class='m-0 fw-bold text-dark'><small>Sangat Kurang</small></td>
							<td class='ps-3 fw-bold text-dark'><small>Kurang</small></td>
							<td class='ps-3 fw-bold text-dark'><small>Cukup</small></td>
							<td class='ps-3 fw-bold text-dark'><small>Baik</small></td>
							<td class='ps-3 fw-bold text-dark'><small>Sangat Baik</small></td>
						</tr>
						<tr>
							<td class="m-0"><small><?= $pertanyaan1['jumlah_jawaban'][1] ? count($pertanyaan1['jumlah_jawaban'][1]) : '0' ?></small></td>
							<td class="ps-3"><small><?= $pertanyaan1['jumlah_jawaban'][2] ? count($pertanyaan1['jumlah_jawaban'][2]) : '0' ?></small></td>
							<td class="ps-3"><small><?= $pertanyaan1['jumlah_jawaban'][3] ? count($pertanyaan1['jumlah_jawaban'][3]) : '0' ?></small></td>
							<td class="ps-3"><small><?= $pertanyaan1['jumlah_jawaban'][4] ? count($pertanyaan1['jumlah_jawaban'][4]) : '0' ?></small></td>
							<td class="ps-3"><small><?= $pertanyaan1['jumlah_jawaban'][5] ? count($pertanyaan1['jumlah_jawaban'][5]) : '0' ?></small></td>
						</tr>
					</table>
				</div>
				<?php } elseif ($pertanyaan1['jenis_jawaban'] == 'uraian') {
				echo "<div style='margin-left:" . $margin . "px;'>";
				foreach ($pertanyaan1['jumlah_jawaban'] as $key => $uraian) { ?>

					<p class="m-0 fw-bold text-dark"><small><?= $userarr[$key] ?> :</small></p>
					<p class="m-0"><small><?= $uraian ?></small></p>


		<?php
				}
				echo '</div>';
			}

			echo "</div>";
		}

		if ($jumlah_pertanyaan[$id_jabatan]['yatidak']['total'] != 0) {
			$total_yatidak = (($jumlah_pertanyaan[$id_jabatan]['yatidak']['nilai'][1] + $jumlah_pertanyaan[$id_jabatan]['yatidak']['nilai'][5])) / $jumlah_pertanyaan[$id_jabatan]['yatidak']['total'];
		} else {
			$total_yatidak = 0;
		}
		if ($jumlah_pertanyaan[$id_jabatan]['1sampai5']['total']) {
			$total_1sampai5 = ($jumlah_pertanyaan[$id_jabatan]['1sampai5']['nilai'][1] + $jumlah_pertanyaan[$id_jabatan]['1sampai5']['nilai'][2] + $jumlah_pertanyaan[$id_jabatan]['1sampai5']['nilai'][3] + $jumlah_pertanyaan[$id_jabatan]['1sampai5']['nilai'][4] + $jumlah_pertanyaan[$id_jabatan]['1sampai5']['nilai'][5]) / $jumlah_pertanyaan[$id_jabatan]['1sampai5']['total'] * 2 * 10;
		} else {
			$total_1sampai5 = 0;
		}
		?>
</div>
<div class="mt-2" style="">



	<?php
		if ($total_1sampai5) {
	?>

		<h5 class="m-0">Nilai Quisioner <?= round($total_1sampai5, 2) ?> <?php
																		if ($total_1sampai5 <= 20) {
																			echo "<span style='color:red'>Sangat Kurang</span>";
																		} elseif ($total_1sampai5 <= 40) {
																			echo "<span style='color:orange'>Kurang</span>";
																		} elseif ($total_1sampai5 <= 60) {
																			echo "<span style='color:yellow'>Cukup</span>";
																		} elseif ($total_1sampai5 <= 80) {
																			echo "<span style='color:green'>Baik</span>";
																		} elseif ($total_1sampai5 <= 100) {
																			echo "<span style='color:blue'>Sangat Baik</span>";
																		}
																		?>
		</h5>
	<?php } ?>


</div>

<?php } ?>


<?php
if ($pertanyaan_user)
	foreach ($pertanyaan_user as $id_user => $rows_pertanyaan) {
		echo "<hr/>";
?>
	<h5 class="fw-semibold text-dark"><?= $userarr[$id_user] ?></h5>
	<?php foreach ($rows_pertanyaan as $pertanyaan1) {

			$margin = 0;
			for ($l = 1; $l < $pertanyaan1['level']; $l++) {
				$margin += 30;
			}
			if ($pertanyaan1['level'] == 1)
				echo "<hr/>";
			echo "<p class='mb-0' style='margin-left:" . $margin . "px;'>" . $pertanyaan1['pertanyaan'] . "</p>";
			if ($pertanyaan1['jenis_jawaban'] == 'yatidak') { ?>
			<div class='' style="margin-left: <?= $margin ?>px;">
				<table>
					<tr class='m-0'>
						<td class='m-0 fw-bold text-dark'><small>Ya</small></td>
						<td class='ps-2 fw-bold text-dark'><small>Tidak</small></td>
					</tr>
					<tr>
						<td class="m-0"><small><?= $pertanyaan1['jumlah_jawaban']["ya"] ? count($pertanyaan1['jumlah_jawaban']["ya"]) : '0' ?></small></td>
						<td class="ps-3"><small><?= $pertanyaan1['jumlah_jawaban']["tidak"] ? count($pertanyaan1['jumlah_jawaban']["tidak"]) : '0' ?></small></td>
					</tr>
				</table>
			</div>

		<?php } elseif ($pertanyaan1['jenis_jawaban'] == '1sampai5') { ?>

			<div class='' style="margin-left: <?= $margin ?>px;">
				<table>
					<tr class='m-0'>
						<td class='m-0 fw-bold text-dark'><small>Sangat Kurang</small></td>
						<td class='ps-3 fw-bold text-dark'><small>Kurang</small></td>
						<td class='ps-3 fw-bold text-dark'><small>Cukup</small></td>
						<td class='ps-3 fw-bold text-dark'><small>Baik</small></td>
						<td class='ps-3 fw-bold text-dark'><small>Sangat Baik</small></td>
					</tr>
					<tr>
						<td class="m-0"><small><?= $pertanyaan1['jumlah_jawaban'][1] ? count($pertanyaan1['jumlah_jawaban'][1]) : '0' ?></small></td>
						<td class="ps-3"><small><?= $pertanyaan1['jumlah_jawaban'][2] ? count($pertanyaan1['jumlah_jawaban'][2]) : '0' ?></small></td>
						<td class="ps-3"><small><?= $pertanyaan1['jumlah_jawaban'][3] ? count($pertanyaan1['jumlah_jawaban'][3]) : '0' ?></small></td>
						<td class="ps-3"><small><?= $pertanyaan1['jumlah_jawaban'][4] ? count($pertanyaan1['jumlah_jawaban'][4]) : '0' ?></small></td>
						<td class="ps-3"><small><?= $pertanyaan1['jumlah_jawaban'][5] ? count($pertanyaan1['jumlah_jawaban'][5]) : '0' ?></small></td>
					</tr>
				</table>
			</div>
			<?php } elseif ($pertanyaan1['jenis_jawaban'] == 'uraian') {
				echo "<div style='margin-left:" . $margin . "px;'>";
				foreach ($pertanyaan1['jumlah_jawaban'] as $key => $uraian) { ?>

				<p class="m-0 fw-semibold text-dark"><small><?= $userarr[$key] ?> :</small></p>
				<p class="m-0"><small><?= $uraian ?></small></p>


	<?php
				}
				echo '</div>';
			}
		} ?>
<?php } ?>

</div>