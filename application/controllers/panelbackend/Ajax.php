<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Ajax extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	public function notif()
	{
		$data = $this->auth->GetTask();
		echo json_encode($data);
	}

	public function matrix()
	{
		$this->data['ajax'] = true;
		$this->load->model("Risk_risikoModel", "model");
		$params = $this->get;
		foreach (str_split($params['rating']) as $idkey => $value) {
			$this->data['rating'][$value] = 1;
		}
		$params['top'] = 10;
		$this->data['rows'] = $this->model->getListRiskProfile($params);
		$this->PartialView("panelbackend/matrixprint");
	}

	public function risikosasaran($kode = null, $idKajianRisiko = null, $id_scorecard = null)
	{
		$this->load->model("Risk_risikoModel", 'risikosasaran');
		$this->load->model("Risk_scorecardModel", "modelscorecard");

		$mtjeniskajianrisikoarr = $this->data['mtjeniskajianrisikoarr'];
		unset($mtjeniskajianrisikoarr['']);

		if (!$idKajianRisiko)
			$idKajianRisiko = array_keys($mtjeniskajianrisikoarr)[0];

		if ($_SESSION[SESSION_APP]['tgl_efektif'])
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		else
			$tgl_efektif = date('Y-m-d');


		$idSasaranStrategis = $this->conn->GetOne("select id_sasaran 
			from risk_sasaran 
			where deleted_date is null and kode = " . $this->conn->escape(trim($kode)) . "
			and '$tgl_efektif' between coalesce(tgl_mulai_efektif,sysdate()) and coalesce(tgl_akhir_efektif,sysdate())");

		if ($idKajianRisiko)
			$scorecardarr = $this->risikosasaran->GetComboDashboard($idKajianRisiko, $tgl_efektif);

		if ($id_scorecard)
			$id_scorecardarr = $this->modelscorecard->GetChild($id_scorecard);

		$data = $this->risikosasaran->GetRisikoBySasaran($idKajianRisiko, $idSasaranStrategis, $id_scorecardarr);

		echo "<script>function callRisiko(reset) {
		if(reset!=1){
        	var id_scorecard = \$('#id_scorecard').val();
		}
        else{
        	var id_scorecard = 0;
        }
        var id_kajian_risiko = \$('#id_kajian_risiko').val();
      
  $.ajax({
    dataType: 'html',
    url:'" . site_url("panelbackend/ajax/risikosasaran") . "/$kode/'+id_kajian_risiko+'/'+id_scorecard,
    success:function(response) {
      $('#datarisikostrategis').html(response);
    }
  })
}</script>";

		echo "Kajian Risiko : " . UI::createSelect('id_kajian_risiko', $mtjeniskajianrisikoarr, $idKajianRisiko, true, 'form-control select2', "onchange='callRisiko(1)' style='width:auto'");
		if (is_array($scorecardarr) && count($scorecardarr))
			echo "Risk Profile : " . UI::createSelect('id_scorecard', $scorecardarr, ($scorecardarr[$id_scorecard] ? $id_scorecard : null), true, 'form-control select2', "onchange='callRisiko()' style='width:auto'");

		echo "<table class='table table-stripped'>
			<thead>
				<tr>
					<th class='bg-blue' style='background-color:#034485 !important;text-align:left' width='10'>No</th>
					<th class='bg-blue' style='background-color:#034485 !important;text-align:left'>Nama Risiko</th>
					<th class='bg-blue' style='background-color:#034485 !important;text-align:left'>Penyebab Risiko</th>
					<th class='bg-blue' style='background-color:#034485 !important;text-align:left'>Dampak Risiko</th>
					<th class='bg-blue' style='background-color:#034485 !important;text-align:left'>Inheren Risk</th>
					<th class='bg-blue' style='background-color:#034485 !important;text-align:left'>Residual Saat Ini</th>
					<th class='bg-blue' style='background-color:#034485 !important;text-align:left'>Target Residual</th>
				</tr>
			</thead>
			<tbody>";
		$no = 1;
		foreach ($data as $r => $val) {
			echo "<tr>";
			echo "<td>" . $no++ . "</td>";
			echo "<td ><a href='" . site_url("panelbackend/risk_risiko/detail/$val[id_scorecard]/$val[id_risiko]") . "' target='_BLANK'>$val[nama]</a></td>";
			echo "<td >" . nl2br($val['penyebab_risiko']) . "</td>";
			echo "<td >" . nl2br($val['dampak_risiko']) . "</td>";
			echo labeltingkatrisiko($val['inheren']);
			echo labeltingkatrisiko($val['control']);
			echo labeltingkatrisiko($val['residual']);

			echo "</tr>";
		}
		if (!$data) {
			echo "<tr><td  colspan='7'>Data kosong</td></tr>";
		};
		echo "
			<tbody>
			</table>";
	}


	public function set_toggle()
	{
		$_SESSION[SESSION_APP]['toggle'] = $this->get['collapse'];
	}

	public function listsasaran($id_sasaran = null)
	{
		$data = array("results" => array());

		$q = $_GET['q'];

		if ($q) {
			$this->load->model("Risk_kegiatanModel", 'model');

			$result = $this->model->GetCombo($id_sasaran, $q);

			$arr = array();

			foreach ($result as $idkey => $value) {
				$arr[] = array("id" => $idkey, "text" => $value);
			}

			$data['results'] = $arr;
		}

		echo json_encode($data);
	}

	public function listjabatan($jabatan = null, $subdit = null)
	{
		$data = array("results" => array());

		$q = $_GET['q'];

		if ($q) {
			$this->load->model("Mt_sdm_jabatanModel", 'mjabatan');

			$result = $this->mjabatan->GetCombo(null, $q);

			$arr = array();

			foreach ($result as $idkey => $value) {
				$arr[] = array("id" => $idkey, "text" => $value);
			}

			$data['results'] = $arr;
		}

		echo json_encode($data);
	}
	public function risksasaranarr()
	{
		$data = array("results" => array());

		$q = strtolower($_GET['q']);

		// $this->conn->debug = 1;

		if ($q) {
			// $this->load->model("Risk_sasaranModel", 'risksasaran');
			// $result = $this->risksasaran->GetCombo(null, $q);
			$result = $this->conn->GetList("select id_sasaran idkey, nama val from risk_sasaran where deleted_date is null and lower(nama) like '%$q%'");

			$arr = array();

			foreach ($result as $idkey => $value) {
				$arr[] = array("id" => $idkey, "text" => $value);
			}

			$data['results'] = $arr;
		}

		// dpr($data,1);

		echo json_encode($data);
	}
	public function riskpenyebab()
	{
		$data = array("results" => array());

		$q = $_GET['q'];

		if ($q) {

			$result = $this->conn->GetList("select id_risk_penyebab as idkey, nama as val from risk_penyebab where deleted_date is null and lower(nama) like '%$q%'");

			$arr = array();

			foreach ($result as $idkey => $value) {
				$arr[] = array("id" => $idkey, "text" => $value);
			}

			$data['results'] = $arr;
		}

		echo json_encode($data);
	}

	public function riskdampakarr()
	{
		$data = array("results" => array());

		$q = $_GET['q'];

		if ($q) {

			$result = $this->conn->GetList("select id_risk_dampak as idkey, nama as val from risk_dampak where deleted_date is null and lower(nama) like '%$q%'");

			$arr = array();

			foreach ($result as $idkey => $value) {
				$arr[] = array("id" => $idkey, "text" => $value);
			}

			$data['results'] = $arr;
		}

		echo json_encode($data);
	}

	public function riskcontrol()
	{
		$data = array("results" => array());

		$q = $_GET['q'];

		if ($q) {

			$result = $this->conn->GetList("select id_control as idkey , nama as val from risk_control where deleted_date is null and lower(nama) like '%$q%'");

			$arr = array();

			foreach ($result as $idkey => $value) {
				$arr[] = array("id" => $idkey, "text" => $value);
			}

			$data['results'] = $arr;
		}

		echo json_encode($data);
	}

	public function riskrisiko($id_tingkat_agregasi_risiko = null)
	{
		// $this->conn->debug = 1;
		$data = array("results" => array());

		$q = strtolower($_GET['q']);

		if ($q) {
			$result = $this->conn->GetArray("select rr.*, 
			rs.nama as sasaran 
			from (select 
			rr.*
			from risk_risiko rr 
			join risk_scorecard rsc on rr.id_scorecard = rsc.id_scorecard
			where
			rr.deleted_date is null and 
			rr.status_risiko = '1' 
			and rsc.id_tingkat_agregasi_risiko = " . $this->conn->escape($id_tingkat_agregasi_risiko) . " ) rr 
			join risk_sasaran rs on rr.id_sasaran = rs.id_sasaran
			where 
			(
				exists (
					select 1 from risk_risiko_penyebab rrp 
					where rrp.deleted_date is null and rr.id_risiko = rrp.id_risiko 
					and exists (
						select 1 from risk_penyebab rp 
						where rp.deleted_date is null and rrp.id_risk_penyebab = rp.id_risk_penyebab 
						and lower(rp.nama) like '%$q%'
					)
				)
				or
				exists (
					select 1 from risk_risiko_dampak rrp 
					where rrp.deleted_date is null and rr.id_risiko = rrp.id_risiko 
					and exists (
						select 1 from risk_dampak rp 
						where rp.deleted_date is null and rrp.id_risk_dampak = rp.id_risk_dampak 
						and lower(rp.nama) like '%$q%'
					)
				)
				or 
				lower(rs.nama) like '%$q%'
			) 
			");

			$arr = array();

			foreach ($result as $r) {
				$value = "<b>Sasaran : </b>";
				$value .= $r['sasaran'] . "<br>";

				$value .= "<b>Penyebab : </b>";
				$arr1 = $this->conn->GetList("select rp.id_risk_penyebab as idkey, rp.nama as val
				from risk_penyebab rp
				where rp.deleted_date is null and exists (
					select 1 from risk_risiko_penyebab rrp 
					where rrp.deleted_date is null and rp.id_risk_penyebab = rrp.id_risk_penyebab
					and rrp.id_risiko = " . $this->conn->escape($r['id_risiko']) . "
				)");
				$value .= implode("<br>", $arr1) . "<br>";

				$value .= "<b>Dampak : </b>";
				$arr1 = $this->conn->GetList("select rp.id_risk_dampak as idkey, rp.nama as val
				from risk_dampak rp
				where rp.deleted_date is null and exists (
					select 1 from risk_risiko_dampak rrp 
					where rrp.deleted_date is null and rp.id_risk_dampak = rrp.id_risk_dampak
					and rrp.id_risiko = " . $this->conn->escape($r['id_risiko']) . "
				)");
				$value .= implode("<br>", $arr1);

				$arr[] = array("id" => $r['id_risiko'], "html" => $value, "text" => $value);
			}

			$data['results'] = $arr;
		}

		echo json_encode($data);
	}

	public function riskmitigasiarr()
	{
		$data = array("results" => array());

		$q = $_GET['q'];

		if ($q) {

			$result = $this->conn->GetList("select id_mitigasi as idkey , nama as val from risk_mitigasi where deleted_date is null and lower(nama) like '%$q%'");

			$arr = array();

			foreach ($result as $idkey => $value) {
				$arr[] = array("id" => $idkey, "text" => $value);
			}

			$data['results'] = $arr;
		}

		echo json_encode($data);
	}

	public function list_unit()
	{
		$data = array("results" => array());

		$q = $_GET['q'];

		if ($q) {

			$result = $this->conn->GetList("select table_code as idkey, table_desc val from mt_sdm_unit where deleted_date is null and lower(table_desc) like '%$q%'");

			$arr = array();

			foreach ($result as $idkey => $value) {
				$arr[] = array("id" => $idkey, "text" => $value);
			}

			$data['results'] = $arr;
		}

		echo json_encode($data);
	}

	public function listjabatandirektorat($jabatan = null, $subdit = null)
	{
		$data = array("results" => array());

		$q = $_GET['q'];

		if ($q) {
			$this->load->model("Mt_sdm_jabatanModel", 'mjabatan');

			$result = $this->mjabatan->GetComboDirektorat(null, $q);

			$arr = array();

			foreach ($result as $idkey => $value) {
				$arr[] = array("id" => $idkey, "text" => $value);
			}

			$data['results'] = $arr;
		}

		echo json_encode($data);
	}

	public function listpegawai($jabatan = null, $subdit = null)
	{
		$data = array("results" => array());

		$q = $this->conn->escape_str(strtolower($_GET['q']));
		$jabatan = $this->conn->escape(trim(urldecode($jabatan)));
		$subdit = $this->conn->escape(trim(urldecode($subdit)));

		if ($q) {
			$sql = "select nid as id, nama as text
				from mt_sdm_karyawan
				where deleted_date is null and  1=1 ";

			$page_ctrl = $_SERVER['HTTP_REFERER'];

			$sql .= " and lower(nama) like '%$q%' limit 10";

			$data['results'] = $this->conn->GetArray($sql);
		}

		echo json_encode($data);
	}

	public function listrisikounit()
	{
		$q = $_GET['q'];
		$rows = $this->conn->GetArray("select * from risk_risiko a 
			where a.deleted_date is null and exists(select 1 from risk_scorecard b where b.deleted_date is null and a.id_scorecard = b.id_scorecard and trim(lower(b.id_unit)) <> '1')
			and lower(a.nama) like '%" . strtolower($q) . "%'");

		echo json_encode($rows);
	}

	public function listkpichildkorporat($id_parent = null, $tahun = null)
	{
		$this->load->model("Kpi_targetModel", "kpimodel");
		$rows = $this->kpimodel->SelectGridKorporat(["id_parent" => $id_parent, "tahun" => $tahun]);

		// dpr($rows);
		echo "<table style='width:100%'>";
		foreach ($rows as $r) {

			if ($r['isfolder'])
				continue;

			echo "<tr>";
			echo "<td>" . $r['nama'] . "</td>";
			echo "</tr>";
			echo "</tr>";
			$persen = $r['prostarget'];

			echo "<tr>";
			echo "<td style='font-size:12px;'><b>Target : " . rupiah($r['target']) . " Realisasi : " . rupiah($r['totrealisasi']) . " " . ($r['satuan']) . "</b></td>";
			echo "</tr>";

			echo "<tr>";
			echo "<td style='padding-bottom:10px;'>";
			echo '<div class="progress">
				<div class="progress-bar" role="progressbar" style="width: ' . $persen . '%" aria-valuenow="' . $persen . '" aria-valuemin="0" aria-valuemax="100">' . $persen . '%</div>
				</div>';
			$rowsrisiko = $this->conn->GetArray("select * from risk_risiko 
				where deleted_date is null and id_kpi = " . $this->conn->escape($r['id_kpi']) . " 
				and DATE_FORMAT(tgl_risiko,'%Y') = " . $this->conn->escape($tahun));

			if ($rowsrisiko) {
				echo "<table style='display:none' id='tbkpi" . $r['id_kpi'] . "'><tr><th colspan='2'>Risiko</th></tr>";
				foreach ($rowsrisiko as $rr) {
					echo "<tr><td><a href='" . site_url("panelbackend/risk_risiko/detail/" . $rr['id_scorecard'] . "/" . $rr['id_risiko']) . "' target='_blank'>" . $rr['nama'] . "</a></td>";
					echo labeltingkatrisiko($rr['residual_kemungkinan_evaluasi'] . $rr['residual_dampak_evaluasi']);
					echo "</tr>";
				}
				echo "</table>";
				echo "<a href='javascript:void(0)' style='font-size:12px' onclick='$(\"#tbkpi" . $r['id_kpi'] . "\").toggle()'>" . count($rowsrisiko) . " Risiko</a>";
			}
			echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
	}

	public function listkpitarget($departemen = null, $tahun = null)
	{

		$this->load->model("Kpi_targetModel", "model");

		$list = $this->model->get_datatables();
		$list = json_decode(json_encode($list), true);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $item) {
			var_dump($list);
			$no++;
			$row = array();
			$row[] = $item->id_kpi;
			// add html for action
			$data[] = $row;
		}


		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->model->count_all(),
			"recordsFiltered" => $this->model->count_filtered(),
			"data" => $data,
		);

		// output to json format
		echo json_encode($output);
	}
}
