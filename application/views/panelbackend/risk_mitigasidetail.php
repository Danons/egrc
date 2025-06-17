<div class="row">
	<div class="col-sm-12">
		<?php
		$from = UI::createTextBox('no', $row['no'], '', '', $is_allow_edit_mitigasi, 'form-control ', "style='width:80px' readonly='readonly'");
		echo UI::createFormGroup($from, $rules["no"], "no", "No.", false, 2);

		$from = UI::createTextArea('nama', $row['nama'], '5', '', $is_allow_edit_mitigasi, 'form-control  contents-mini', "style='width:100%'");
		echo UI::createFormGroup($from, $rules["nama"], "nama", "Pengendalian / Mitigasi", false, 2);

		$from = UI::createSelect('penanggung_jawab', $penanggung_jawabarr, $row['penanggung_jawab'], $is_allow_edit_penanggung_jawab, 'form-control select2', 'form-control select2');
		echo UI::createFormGroup($from, $rules["penanggung_jawab"], "penanggung_jawab", "Penanggung Jawab", false, 2);

		$from = UI::createTextBox('biaya', ($is_allow_edit_mitigasi ? $row['biaya'] : rupiah($row['biaya'])), '10', '10', $is_allow_edit_mitigasi, 'form-control rupiah', "style='text-align:right;width:150px'");
		echo UI::createFormGroup($from, $rules["biaya"], "biaya", "Biaya Mitigasi", false, 2);

		if ($row['cba']) {
			$info_cba = UI::createInfo("info_cba", "Info CBA (Cost Baseline Analisis)", "<ul style='padding-left: 15px;'><li>CBA digunakan untuk acuan mitigasi yang dilaksanakan layak atau tidak.</li><li>Apabila CBA Ratio > 100% berarti penanganan risiko tersebut memiliki manfaat lebih besar daripada biaya sehingga layak untuk diterapkan.</li></ol>");
			$from = '<span class=" rupiah read_detail">' . (float)$row['cba'] . '%' . '</span>';
			echo UI::createFormGroup($from, $rules["cba"], "cba", "Cost Baseline Analisis" . $info_cba, false, 2);
		}

		$from = UI::createTextBox('start_date', $row['start_date'], '10', '10', $is_allow_edit_mitigasi, 'form-control datepicker', "style='width:150px'");
		echo UI::createFormGroup($from, $rules["start_date"], "start_date", "Start", false, 2);

		$from = UI::createTextBox('dead_line', $row['dead_line'], '10', '10', $is_allow_edit_mitigasi, 'form-control datepicker', "style='width:150px'");
		echo UI::createFormGroup($from, $rules["dead_line"], "dead_line", "Dead Line", false, 2);
		?>
		<!-- </div>
	<div class="col-sm-6"> -->
		<?php
		$from = UI::createTextNumber('status_progress', $row['status_progress'], '10', '10', $is_allow_edit_progress, 'form-control', "style='text-align:right;width:150px' min='0' max='100'");
		echo UI::createFormGroup($from, $rules["status_progress"], "status_progress", "Progress (%)", false, 2);

		$from = UI::createTextArea('program_kerja', $row['program_kerja'], '', '', $is_allow_edit_progress, 'form-control  contents-mini', "style='width:100%'");
		echo UI::createFormGroup($from, $rules["program_kerja"], "program_kerja", "Program Kerja", false, 2);

		// $from = UI::createTextBox('biaya', ($is_allow_edit_mitigasi ? $row['biaya'] : rupiah($row['biaya'])), '10', '10', $is_allow_edit_mitigasi, 'form-control rupiah', "style='text-align:right;width:150px'");
		// echo UI::createFormGroup($from, $rules["biaya"], "biaya", "Biaya Mitigasi", false, 2);

		$from = UI::createTextBox('rencana', $is_allow_edit_progress ? $row['rencana'] : rupiah($row['rencana']), '10', '10', $is_allow_edit_progress, 'form-control rupiah', "style='text-align:right;width:150px' min='0' max='100' onchange='goSubmit(\"set_value\")'");
		echo UI::createFormGroup($from, $rules["rencana"], "rencana", "Rencana", false, 2);

		$from = UI::createTextBox('realisasi', $is_allow_edit_progress ? $row['realisasi'] : rupiah($row['realisasi']), '10', '10', $is_allow_edit_progress, 'form-control rupiah', "style='text-align:right;width:150px' min='0' max='100' onchange='goSubmit(\"set_value\")'");
		echo UI::createFormGroup($from, $rules["realisasi"], "realisasi", "Realisasi", false, 2);

		$from = UI::createTextBox('devisiasi', $is_allow_edit_progress ? $row['devisiasi'] : rupiah($row['devisiasi']), '10', '10', $is_allow_edit_progress, 'form-control rupiah', "style='text-align:right;width:150px' min='0' max='100'");
		echo UI::createFormGroup($from, $rules["devisiasi"], "devisiasi", "Deviasi", false, 2);

		$from = UI::createTextBox('satuan', $row['satuan'], '10', '10', $is_allow_edit_progress, 'form-control', "style='text-align:right;width:150px'");
		echo UI::createFormGroup($from, $rules["satuan"], "satuan", "Satuan", false, 2);

		$from = UI::createUploadMultiple("files", $row['files'], $page_ctrl, $is_allow_edit_progress);
		echo UI::createFormGroup($from, $rules["file"], "file", "File Lampiran Progress", false, 2);

		$from = UI::showButtonMode("save", null, $is_allow_edit_progress);
		echo UI::createFormGroup($from, $edited, null, null, false, 2);
		?>
	</div>
</div>