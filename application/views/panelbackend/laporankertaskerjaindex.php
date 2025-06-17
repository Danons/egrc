<div class="col-sm-12">

	<?php
	if (!$row['bulan'])
		$row['bulan'] = date('m');

	$form = "<table><tr>
	<td width='200px'>" . UI::createSelect('bulan', ListBulan(), $row['bulan'], true, 'form-control', "style='width:100%;' onchange='goSubmit(\"set_value\")'") . "</td><td width='20px'></td>
	<td width='100px'>" . UI::createTextNumber('tahun', ($row['tahun'] ? $row['tahun'] : (!$this->post ? date('Y') : null)), '4', '4', true, $class = 'form-control ', " onchange='goSubmit(\"set_value\")'") . "</td>
	</tr></table>";
	echo UI::FormGroup(array(
		'form' => $form,
		'sm_label' => 2,
		'label' => 'Tahun'
	));
	?>

	<?php
	// if (!$row['bulan_input'])
	// 	$row['bulan_input'] = date('m');

	// $form = "<table><tr>
	// <td width='200px'>" . UI::createSelect('bulan_input', ListBulan(), $row['bulan_input'], true, 'form-control', "style='width:100%;'") . "</td><td width='20px'></td>
	// <td width='100px'>" . UI::createTextNumber('tahun_input', ($row['tahun_input'] ? $row['tahun_input'] : (!$this->post ? date('Y') : null)), '4', '4', true, $class = 'form-control ', " onchange='goSubmit(\"set_value\")'") . "</td>
	// </tr></table>";
	// echo UI::FormGroup(array(
	// 	'form' => $form,
	// 	'sm_label' => 2,
	// 	'label' => 'Tahun'
	// ));
	?>

	<?php
	if (!$this->access_role['view_all']) {
		$row['id_unit'] = $_SESSION[SESSION_APP]['id_unit'];
		$unitarr = array($row['id_unit'] => $unitarr[$row['id_unit']]);
	}
	$form = UI::createSelect('id_unit', $unitarr, $row['id_unit'], true, $class = 'form-control ', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
	echo UI::FormGroup(array(
		'form' => $form,
		'sm_label' => 2,
		'label' => 'Unit'
	));
	?>

	<?php
	$form = null;
	if ($jenisarr) {
		$form = UI::createSelect('jenis', $jenisarr, $row['jenis'], true, $class = 'form-control ');
		$form1 = UI::createSelect('tingkat', $tingkatarr, $row['tingkat'], true, $class = 'form-control ', "style='width:200px;'");
		echo UI::FormGroup(array(
			'form' => "<table><tr><td style='width:150px'>" . $form . "</td><td style='width:10px'></td>
		<td style='width:200px; " . (($row['jenis']) ? "" : "display:none") . "' class='tdjenis'>" . $form1 . "</td></tr></table>",
			'sm_label' => 2,
			'label' => 'Tingkat'
		));
	} else {
		$form = UI::createSelect('tingkat', array('' => 'Semua') + $tingkatarr, $row['tingkat'], true, $class = 'form-control ', "style='width:200px;'");
		echo UI::FormGroup(array(
			'form' => $form,
			'sm_label' => 2,
			'label' => 'Tingkat'
		));
	}
	?>

	<?php
	// $form = UI::createSelect('id_kpi', $kpiarr, $row['id_kpi'], true, $class = 'form-control ', "style='width:100%;'");
	// echo UI::FormGroup(array(
	// 	'form' => $form,
	// 	'sm_label' => 2,
	// 	'label' => 'KPI'
	// ));

	if ($this->page_ctrl == 'panelbackend/laporan_kertas_kerja_peluang') {

		$from = "";
		foreach ($kelayakanarr as $k => $v) {
			$from .= UI::createCheckBox('id_kelayakan[' . $k . ']', $k, $row['id_kelayakan'][$k], $v, true) . "<br/>";
		}

		echo UI::FormGroup(array(
			'form' => $from,
			'sm_label' => 2,
			'label' => 'Studi Kelayakan (Feasibility Study)'
		));
	}

	$form = require_once("_scorecard.php");

	echo UI::FormGroup(array(
		'form' => $form,
		'sm_label' => 2,
		'label' => 'Area Risiko'
	));
	?>

	<?php
	$form = $templetelaporanarr ? UI::createSelect("id_kolom_laporan", array('' => '-pilih-') + $templetelaporanarr, $row['id_kolom_laporan'], true, 'form-control', 'onchange="goSubmit(\'set_value\')"') : null;
	if ($row['id_kolom_laporan']) {
		$form .= "<a href='javascript:void(0)' style='color:orange; font-size:12px' onclick='editTemplete()'>Edit Templete</a>&nbsp;";
		$form .= "&nbsp;<a href='javascript:void(0)' style='color:red; font-size:12px' onclick='if(confirm(\"Apakah Anda yakin ?\")){goSubmit(\"delete_kolom\")}'>Delete Templete</a>";
	}
	$form .= require_once("_columns.php");

	$form .= "<a href='javascript:void(0)' style='font-size:12px' onclick='addTemplete()'>Simpan kolom sebagai templete</a>";
	echo UI::FormGroup(array(
		'form' => $form,
		'sm_label' => 2,
		'label' => 'Kolom'
	));
	?>
	<!-- 
	<?php
	$form = null;
	if ($jenisarr) {
		$form = UI::createSelect('jenis', $jenisarr, $row['jenis'], true, $class = 'form-control ');
		$form1 = UI::createSelect('tingkat', $tingkatarr, $row['tingkat'], true, $class = 'form-control ', "style='width:200px;'");
		echo UI::FormGroup(array(
			'form' => "<table><tr><td style='width:150px'>" . $form . "</td><td style='width:10px'></td>
		<td style='width:200px; " . (($row['jenis']) ? "" : "display:none") . "' class='tdjenis'>" . $form1 . "</td></tr></table>",
			'sm_label' => 2,
			'label' => 'Tingkat'
		));
	} else {
		$form = UI::createSelect('tingkat', array('' => 'Semua') + $tingkatarr, $row['tingkat'], true, $class = 'form-control ', "style='width:200px;'");
		echo UI::FormGroup(array(
			'form' => $form,
			'sm_label' => 2,
			'label' => 'Tingkat'
		));
	}
	?> -->

	<?php
	$form = '<button type="button" class="btn  btn-sm btn-primary" onclick="goPrint()" ><span class="bi bi-printer"></span> Print</button>
	<script>
	function goPrint(){
		//melepas pembatasan unit
		// if($("#id_unit").val()!=""){
		$("#act").val("list_search");
		window.open("' . base_url($this->page_ctrl . "/go_print" . $add_param) . '/?"+$("#main_form").serialize(),"_blank");
		// }else{
		// 	alert("Unit wajib di isi");
		// }
	}
	</script>';

	echo UI::FormGroup(array(
		'form' => $form,
		'sm_label' => 2,
	));
	?>

</div>
<script type="text/javascript">
	$("#jenis").change(function() {
		if ($(this).val() != '0') {
			$(".tdjenis").show();
		} else {
			$(".tdjenis").hide();
		}
		if ($(this).val() == 'is_signifikan') {
			$(".tdjenis").hide();
		}
	});

	function editTemplete() {
		$("#namatempletekolom").val('<?= $row['laporan']['nama'] ?>');
		$("#idtempletekolom").val('<?= $row['id_kolom_laporan'] ?>');
		$("#judultempletekolom").text('<?= $row['laporan']['judul'] ?>');
		$("#savekolom").modal("show");
	}

	function addTemplete() {
		$("#namatempletekolom").val('');
		$("#idtempletekolom").val('');
		$("#judultempletekolom").text('');
		$("#savekolom").modal("show");
	}
</script>


<div class="modal fade" id="savekolom" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Simpan Kolom</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<b><small>Nama Template</small></b>
				<input type="text" name="namatempletekolom" id="namatempletekolom" class="form-control" placeholder="Nama template..." />
				<input type="hidden" name="idtempletekolom" id="idtempletekolom" />
				<br />
				<b><small>Judul Laporan</small></b>
				<textarea class="form-control" name="judultempletekolom" id="judultempletekolom" placeholder="Judul laporan..."></textarea>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary btn-sm" onclick="goSubmit('save_kolom')">SIMPAN</button>
			</div>
		</div>
	</div>
</div>