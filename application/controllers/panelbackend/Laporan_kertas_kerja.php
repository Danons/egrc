<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Laporan_kertas_kerja extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";
		$this->viewprint = "panelbackend/laporankertaskerjaprint";
		$this->viewindex = "panelbackend/laporankertaskerjaindex";

		if ($this->mode == 'print_detail') {
			$this->data['page_title'] = 'Laporan Kertas Kerja';
		} else {
			$this->data['page_title'] = 'Laporan Kertas Kerja';
		}

		$this->load->model("Risk_risikoModel", "model");
		$this->load->model("Risk_scorecardModel", "mscorecard");

		$this->load->model("Mt_risk_efektifitasModel", "mefektif");
		$this->data['efektifarr'] = $this->mefektif->GetCombo();

		$this->load->model("Mt_risk_efektif_mModel", "mefektifm");
		$this->data['efektifmarr'] = $this->mefektifm->GetCombo();
		// unset($this->data['efektifarr']['']);

		$this->load->model("Mt_sdm_unitModel", "unit");

		$this->data['unitarr'] = $this->unit->GetCombo();
		$this->data['unitarr'][''] = "Semua Unit";

		$this->load->model("Mt_status_progressModel", "mtprogress");
		$mtprogress = $this->mtprogress;
		$this->data['pregressarr'] = $mtprogress->GetCombo();

		$this->load->model("Mt_risk_tingkatModel", "mttingkat");
		$this->data['tingkatarr'] = $this->mttingkat->GetCombo();
		$this->data['jenisarr'] = array('0' => 'Semua', 'is_signifikan' => 'Semua Signifikan', '1' => 'Inheren Risk', '2' => 'Risiko Residual Saat Ini', '3' => 'Residual Setelah Evaluasi');
		// unset($this->data['tingkatarr']['']);
		krsort($this->data['tingkatarr']);
		$this->data['operasionalarr'] = $this->conn->GetList("select id_aspek_lingkungan as idkey, kode as val from mt_aspek_lingkungan where deleted_date is null");

		$this->load->model("Risk_sasaranModel", "sasaranstrategis");

		$this->load->model("Mt_risk_efektifitas_pengukuranModel", "pengukuran");
		$this->data['mtpengukuranarr'] = $this->pengukuran->GetCombo();

		// $this->load->model("Mt_risk_efektif_m_pengukuranModel", "pengukuranm");
		// $this->data['mtpengukuranmarr'] = $this->pengukuranm->GetCombo();

		// $this->load->model("Mt_risk_efektifitas_jawabanModel", "jawaban");
		// $this->data['mtjawabanarr'] = $this->jawaban->GetCombo();

		$this->load->model("Mt_risk_efektif_m_jawabanModel", "jawabanm");
		$this->data['mtjawabanmarr'] = $this->jawabanm->GetCombo();
		// unset($this->data['mtjawabanarr']['']);
		// $this->load->model("KpiModel", "kpi");
		// $this->data['kpiarr'] = $this->kpi->GetCombo();

		$this->load->model("Mt_risk_taksonomi_areaModel", 'taksonomiarea');
		$this->data['taksonomiareaarr'] = $this->taksonomiarea->GetCombo();

		$this->data['prioritas'] = $this->conn->GetList("select id_prioritas as idkey, nama val from mt_prioritas where deleted_date is null");


		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'treetable', 'select2'
		);
	}

	function Header()
	{
		$norowspan_mitigasi = array();
		$norowspan_kri = array();
		$norowspan_control = array();
		$return = array();


		$this->data['type_header'] = array(
			'id_pengukuran' => array(
				'list' => $this->data['mtpengukuranarr']
			),
			'id_pengukuranm' => array(
				'list' => $this->data['mtpengukuranmarr']
			),
			'kategori_kemungkinan' => array(
				'list' => array('' => '', '1' => 'Deskripsi Kualitatif', '2' => 'Probabilitas', '3' => 'Insiden Sebelumnya')
			),
			'waktu_pelaksanaan' => 'date',
			'biaya_mitigasi' => 'rupiah',
			'dampak_kuantitatif_inheren' => 'rupiah',
			'dampak_kuantitatif_current' => 'rupiah',
			'dampak_kuantitatif_residual' => 'rupiah',
			'level_risiko_inheren' => 'rating',
			'level_risiko_paskakontrol' => 'rating',
			'level_risiko_residual' => 'rating',
			'level_risiko_actual' => 'rating',
			'is_monitoring_rmtik' => 'check',
			'is_monitoring_p2k3' => 'check',
			'is_monitoring_fkap' => 'check',
		);

		// $return["kode_risiko"] = "Kode";
		$return["scorecard"] = "Unit";
		// $return["sasaran"] = "Sasaran Kerja";
		$return["sasaran"] = "Sasaran";
		$return["kegiatan"] = "Kegiatan";
		// $return["kpi"] = "KPI";
		// $return["nama_aktifitas"] = "Aktivitas";
		// $return["risiko"] = "Nama Risiko";
		// $return["taksonomi_objective"] = "Kategori Risiko";
		// $return["taksonomi_area"] = "Sub Kategori Risiko";

		$norowspan_kri[] = "nama_kri";
		$norowspan_kri[] = "formula_kri";
		$norowspan_penyebab[] = "penyebab";
		$norowspan_dampak[] = "dampak";

		$return["nama_kri"] = "Key Risk Indicator";
		$return["formula_kri"] = "Fomula KRI";
		// $return["is_rutin"] = "Rutin (R)/ Non Rutin (NR)";
		$return["penyebab"] = "Sumber Risiko / Peluang";
		// if ($this->get['rutin_non_rutin'] !== 'nonrutin')
		// 	$return["taksonomi_area_kode"] = "Kategori (K3, L, Q, C, O) ";
		// else {
		// 	$return["kategori_risiko"] = array(
		// 		"Kategori Risiko/Peluang" =>
		// 		array(
		// 			// "taksonomi_area_kode" => "Rutin (K3, L, Q, C, O)",
		// 			"taksonomi_area_kode" => "Non Rutin (FS, F, L, PI O)",
		// 			"id_kategori_proyek" => "Proyek (C, T, S, K3, L, SC,H, O)",
		// 		)
		// 	);
		// }
		// $return["level_risiko_inheren"] = "Dampak Kuantitatif";
		$return["dampak"] = "Dampak Risiko / Peluang";
		// $return["pemenuhan_kewajiban"] = "Pemenuhan Kewajiban";
		// $return["id_aspek_lingkungan"] = "Operasional (N, Ab, E)";
		$return["inheren_risk"] = array(
			"Risiko Inheren" =>
			array(
				// "is_opp_inherent" => "Risk (-1) / Opportunity (+1)",
				// "inheren_kemungkinan" => "Kemungkinan (K)",
				// "inheren_dampak" => "Dampak (D)",
				// "level_risiko_inheren" => "Nilai Risiko (KxD)",
				"inheren_kemungkinan" => "Kemungkinan",
				"inheren_dampak" => "Dampak",
				"level_risiko_inheren" => "Nilai Risiko/Peluang",
				// "is_signifikan_inherent" => "Signifikan?",
				"dampak_kuantitatif_inheren" => "Kuantifikasi (Rp)"
			)
		);

		$norowspan_control[] = 'nama_kontrol';
		$norowspan_control[] = 'nama_pengukuran';
		// $return['nama_mitigasi'] = "Rencana Pengendalian / Mitigasi Risiko";
		// $return['nama_kontrol'] = "Kontrol Eksisting";
		$return['nama_kontrol'] = "Pengendalian yang telah dilakukan";
		$return['nama_pengukuran'] = "Efektivitas Pengendalian Saat Ini";

		$return["current_risk"] = array(
			"Risiko Residual Saat Ini" => array(
				// "is_opp_inherent1" => "Risk (-1) / Opportunity (+1)",
				// "kemungkinan_paskakontrol" => "Kemungkinan (K)",
				// "dampak_paskakontrol" => "Dampak (D)",
				// "level_risiko_paskakontrol" => "Nilai Risiko (KxD)",
				"control_kemungkinan_penurunan" => "Kemungkinan",
				"control_dampak_penurunan" => "Dampak",
				"level_risiko_paskakontrol" => "Nilai Risiko/Peluang",
				// "is_signifikan_current" => "Signifikan?",
				"dampak_kuantitatif_current" => "Kuantifikasi (Rp)"
			)
		);

		$norowspan_mitigasi[] = 'nomor_mitigasi_lanjutan';
		$norowspan_mitigasi[] = 'mitigasi_lanjutan';
		$norowspan_mitigasi[] = 'nama_mitigasi';
		$norowspan_mitigasi[] = 'nama_mitigasi_berjalan';
		$norowspan_mitigasi[] = 'waktu_pelaksanaan';
		$norowspan_mitigasi[] = 'biaya_mitigasi';
		$norowspan_mitigasi[] = 'program_kerja';
		$norowspan_mitigasi[] = 'rencana';
		$norowspan_mitigasi[] = 'realisasi';
		$norowspan_mitigasi[] = 'devisiasi';
		$norowspan_mitigasi[] = 'satuan_mitigasi';
		$norowspan_mitigasi[] = 'penanggungjawab_mitigasi';

		// $return['nama_mitigasi'] = "Rencana Pengendalian / Mitigasi Risiko";
		// $return['nomor_mitigasi_lanjutan'] = "Nomor Tujuan Sasaran Program (TSP)";
		$return['mitigasi_lanjutan'] = "Pengendalian Lanjutan";
		// $return['prioritas_risiko'] = "Tingkat Prioritas Risiko / Peluang";
		// $return['integrasi_internal'] = "Integrasi Internal";
		// $return['integrasi_eksternal'] = "Integrasi Eksternal";
		// $return['nama_mitigasi_berjalan'] = "Pengendalian Risiko / Mitigasi Risiko / Program RKT";
		// $return["waktu_pelaksanaan"] = "Target Penyelesaian";
		// $return['biaya_mitigasi'] = "Biaya Pengendalian / Mitigasi Risiko";
		// $return["risk_owner"] = "Pemilik Risiko";

		// $return["realisasi_mitigasi"] = array(
		// 	"Realisasi Pengendalian / Mitigasi Risiko sd " . ListBulan()[date('m')] . ' ' . date('Y') => array(
		// 		"program_kerja" => "Program Kerja",
		// 		"rencana" => "Rencana",
		// 		"realisasi" => "Realisasi",
		// 		"devisiasi" => "Devisiasi",
		// 		"satuan_mitigasi" => "Satuan"
		// 	)
		// );

		$return["residual_risk"] = array(
			"Risiko Residual Setelah Evaluasi" => array(
				// "is_opp_inherent2" => "Risk (-1) / Opportunity (+1)",
				// "kemungkinan_actual" => "Kemungkinan (K)",
				// "dampak_actual" => "Dampak (D)",
				// "level_risiko_actual" => "Nilai Risiko (KxD)",

				// "kemungkinan_actual" => "Kemungkinan",
				// "dampak_actual" => "Dampak",
				// "level_risiko_actual" => "Nilai Risiko/Peluang",
				// "dampak_kuantitatif_residual" => "Kuantifikasi (Rp)"


				"kemungkinan_rdual" => "Kemungkinan",
				"dampak_rdual" => "Dampak",
				"level_risiko_residual" => "Nilai Risiko/Peluang",
				"dampak_kuantitatif_residual" => "Kuantifikasi (Rp)"
			)
		);

		// $return["penanggungjawab_mitigasi"] = "PIC";

		// $return["monitoring_mitigasi"] = array(
		// 	"Monitoring Pengendalian / Mitigasi Risiko" => array(
		// 		"hasil_mitigasi_terhadap_sasaran" => "Hasil Mitigasi Terhadap Sasaran Kerja",
		// 		"is_monitoring_rmtik" => "Divisi RMTIK",
		// 		"is_monitoring_p2k3" => "P2K3",
		// 		"is_monitoring_fkap" => "FKAP"
		// 	)
		// );

		$return["evaluasi_manajemen_risiko"] = array(
			"Evaluasi Manajemen Risiko" => array(
				// "capaian_mitigasi_evaluasi" => "Efektifitas Pengendalian Risiko",
				"hasil_mitigasi_terhadap_sasaran" => "Hasil Mitigasi Terhadap Sasaran Kerja",
				"penyesuaian_mitigasi" => "Rekomendasi",
				"status_risiko" => "Status"
			)
		);

		$this->data['norowspan_mitigasi'] = $norowspan_mitigasi;
		$this->data['norowspan_control'] = $norowspan_control;
		$this->data['norowspan_kri'] = $norowspan_kri;
		$this->data['norowspan_penyebab'] = $norowspan_penyebab;
		$this->data['norowspan_dampak'] = $norowspan_dampak;

		return $return;
	}

	function Index($page = 1)
	{
		if ($this->post['act'] == 'save_kolom') {
			if ($this->post['idtempletekolom']) {
				$this->conn->goUpdate("risk_kolom_laporan", [
					'nama' => $this->post['namatempletekolom'],
					'judul' => $this->post['judultempletekolom'],
					'kolom' => json_encode($this->post['header'])
				], "id_kolom_laporan = " . $this->conn->escape($this->post['idtempletekolom']));
			} else {
				$this->conn->goInsert("risk_kolom_laporan", [
					'nama' => $this->post['namatempletekolom'],
					'judul' => $this->post['judultempletekolom'],
					'kolom' => json_encode($this->post['header'])
				]);
			}
			redirect(current_url());
			exit();
		}

		if ($this->post['act'] == 'delete_kolom') {
			$this->conn->Execute("update risk_kolom_laporan set deleted_date = now() where  id_kolom_laporan = " . $this->conn->escape($this->post['id_kolom_laporan']));
			redirect(current_url());
			exit();
		}

		$this->data['row'] = $this->post;
		// if (!$this->access_role['view_all']) {
		// 	$this->data['row']['id_unit'] = $_SESSION[SESSION_APP]['id_unit'];
		// }
		$tahun = date('Y');
		$bulan = date('m');

		if ($this->data['row']['tahun'])
			$tahun = $this->data['row']['tahun'];

		if ($this->data['row']['bulan'])
			$bulan = $this->data['row']['bulan'];

		// $this->conn->debug=1;
		$this->data['rowscorecards'] = $this->mscorecard->GetList(null, null, 1, $tahun, false, $this->data['row']['id_unit'], $bulan);
		// dpr($this->data['rowscorecards'],1);
		$this->data['header'] = $this->Header();

		$rows = $this->conn->GetArray("select * from risk_kolom_laporan where deleted_date is null");
		$columnarr = array();
		foreach ($rows as $r) {
			$columnarr[$r['id_kolom_laporan']] = $r['kolom'];
			$this->data['templetelaporanarr'][$r['id_kolom_laporan']] = $r['nama'];
		}
		$this->data['row']['header'] = json_decode($columnarr[$this->post['id_kolom_laporan']], true);
		if ($this->post['id_kolom_laporan'])
			$this->data['row']['laporan'] = $this->conn->GetRow("select * from risk_kolom_laporan where deleted_date is null and id_kolom_laporan = " . $this->conn->escape($this->post['id_kolom_laporan']));;


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
		$this->data['buttonMenu'] .= UI::FormGroup(array(
			'form' => $form,
			'sm_label' => 2,
		));
		$this->View($this->viewindex);
	}

	public function go_print()
	{
		$id_unit = $this->get['id_unit'];
		if (!$this->access_role['view_all']) {
			$id_unit = $_SESSION[SESSION_APP]['id_unit'];
		}

		if (!$this->get['header'])
			$this->get['header'] = array();

		$this->data['no_header'] = true;
		$this->data['no_title'] = false;
		//$bulanarr = ListBulan();
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";

		if ($this->get['id_kolom_laporan']) {
			$this->data['no_title'] = true;
			$this->data['row']['laporan'] = $this->conn->GetRow("select * from risk_kolom_laporan where deleted_date is null and id_kolom_laporan = " . $this->conn->escape($this->get['id_kolom_laporan']));;
		}

		$this->data['page_title'] .= "<br/>" . $this->data['mtjeniskajianrisikoarr'][$this->get['id_kajian_risiko']];

		if ($this->get['id_scorecard'] == 1) {
			$this->conn->escape_string($this->get['id_scorecard']);
			$row_score = $this->conn->GetRow("select id_parent_scorecard, nama from risk_scorecard where deleted_date is null and id_scorecard in ('" . implode("','", $this->get['id_scorecard']) . "')");

			$id_parent_scorecard = $row_score['id_parent_scorecard'];
			$nama_scorecard = $row_score['nama'];

			if ($id_parent_scorecard) {
				$nama_parent = $this->conn->GetOne("select nama from risk_scorecard where deleted_date is null and id_scorecard = " . $this->conn->escape($id_parent_scorecard));
				if ($nama_parent)
					$this->data['page_title'] .= " " . $nama_parent . " ";
			}

			if ($nama_scorecard)
				$this->data['page_title'] .= " " . $nama_scorecard . " ";
		}

		$this->data['page_title'] .= " " . ($this->get['tahun'] ? " Tahun " . $this->get['tahun'] : "");
		$this->data['namaunit'] = $this->data['unitarr'][$id_unit];
		$this->data['page_title'] = strtoupper($this->data['page_title']);

		$this->data['warnarr'] = array();
		$rowswarna = $this->conn->GetArray("select k.rating k, d.rating d, t.warna, t.warna_peluang 
			from mt_risk_matrix mx 
			join mt_risk_kemungkinan k on mx.id_kemungkinan = k.id_kemungkinan
			join mt_risk_dampak d on mx.id_dampak = d.id_dampak
			join mt_risk_tingkat t on mx.id_tingkat = t.id_tingkat
			");
		// dpr($this->data['risk_opp']);
		// dpr($rowswarna, 1);
		foreach ($this->data['risk_opp'] as $f) {
			foreach ($rowswarna as $r) {
				if ($f < 1)
					$this->data['warnarr'][$f * $r['k'] * $r['d'] * 1.00] = $r['warna'];
				else
					$this->data['warnarr'][$f * $r['k'] * $r['d'] * 1.00] = $r['warna_peluang'];
			}
		}
		// dpr($this->data['warnarr']);

		$header = $this->Header();

		$this->data['header1']  = array();
		$this->data['header2']  = array();

		foreach ($header as $idkey => $value) {
			if (is_array($value)) {
				$label = key($value);
				$header1 = $value[$label];


				$colspan = 0;
				$kk = 0;
				foreach ($header1 as $k => $v) {

					if (!$this->get['header'][$k])
						continue;

					$this->data['header2'][$k]["label"] = $v;
					$this->data['header2'][$k]["rowspan"] = 1;
					$this->data['header2'][$k]["colspan"] = 1;
					$kk = $k;
					$colspan++;
				}

				if ($colspan == 1) {
					$this->data['header1'][$idkey]["label"] = $this->data['header2'][$kk]["label"];
					$this->data['header1'][$idkey]["rowspan"] = 2;
					$this->data['header1'][$idkey]["colspan"] = 1;
					unset($this->data['header2'][$kk]);
				} else  if ($colspan) {
					$this->data['header1'][$idkey]["label"] = $label;
					$this->data['header1'][$idkey]["rowspan"] = 1;
					$this->data['header1'][$idkey]["colspan"] = $colspan;
				}

				unset($this->get['header'][$idkey]);
			} else {
				if (!$this->get['header'][$idkey])
					continue;

				$this->data['header1'][$idkey]["label"] = $value;
				$this->data['header1'][$idkey]["rowspan"] = 2;
				$this->data['header1'][$idkey]["colspan"] = 1;
			}
		}

		$this->data['paramheader'] = array_keys($this->get['header']);
		/*	dpr($this->data['header1']);	
		dpr($this->data['header2']);	
		dpr($this->data['paramheader']);*/
		$param = $this->get;

		unset($param['header']);
		$param['all'] = (!(bool)$param['id_scorecard']);

		// $this->conn->debug = 1;
		$this->data['rows'] = $this->model->getListKertasKerja($param);
		// $this->data['rows'] = $this->set_data($this->data['rows']);
		// $this->data['rows'] = $this->set_data($this->data['rows']);
		// dpr($this->data['rows'], 1);

		// dpr($this->get['jenis'],1);
		if ($this->get['is_ttd'] == 1) {
			$this->data['jenis'] = $this->get['jenis'];
			$where = " 1=1 ";
			if ($param['jenis'] == "is_signifikan") {
				$where = " b.group_id <> 2 ";
			}
			foreach ($this->get['id_scorecard'] as $f) {
				$id_scorecard = $f;

				#nama dan jabatan
				// $penandatangan[$	f] = $this->conn->GetList("select b.group_id idkey, a.name val from public_sys_user a join public_sys_user_group b on a.user_id = b.user_id  where exists(select 1 from mt_sdm_jabatan c join risk_scorecard d on d.id_unit = c.id_unit where c.id_jabatan = b.id_jabatan and d.id_scorecard = " . $this->conn->escape($f) . ")");
				// $this->conn->debug=1;
				// $penanda = $this->conn->GetArray("
				// 	select b.group_id id, a.name nama,c.nama jabatan, a.user_id , b.id_jabatan
				// 	from public_sys_user a 
				// 		join public_sys_user_group b on a.user_id = b.user_id
				// 		join mt_sdm_jabatan c on c.id_jabatan = b.id_jabatan
				// 	where $where
				// 		and exists(
				// 			select 1
				// 			from risk_scorecard d
				// 			where d.owner = c.id_jabatan and d.id_scorecard = " . $this->conn->escape($f) . ")");

				$penanda = $this->conn->GetRow("
				select 
				id_scorecard as id,
				nama_user,
				id_user,
				nama_jabatan_user,
				id_jabatan_user,
				nama_owner,
				id_owner,
				nama_jabatan_owner,
				id_jabatan_owner,
				nama_upmr,
				id_upmr,
				nama_jabatan_upmr,
				id_jabatan_upmr
				from risk_scorecard
				where deleted_date is null and id_scorecard =
				" . $this->conn->escape($f));
				// dpr($penanda);

				foreach ($penanda as $in => $d) {
					// $penandatangan[$f][$d['id']] = $d;
					$penandatangan[$in] = $d;
				}

				#file qr
				// $file_qr[$f] = $this->conn->GetList("select jenis idkey, file_name val from risk_scorecard_files where id_scorecard = " . $this->conn->escape($f));
				$file_qr = $this->conn->GetList("select jenis idkey, file_name val from risk_scorecard_files where deleted_date is null and id_scorecard = " . $this->conn->escape($f));
			}
			// die;
			// dpr($file_qr);
			// $penandatangan = $this->get_last_parent($penandatangan);
			// dpr($penandatangan,1);

			// if ($penandatangan)
			// 	foreach ($penandatangan as $s => $g) {
			// 		foreach ($g as $z => $c) {
			// 			$pen[$z][$s] = $c;
			// 			foreach ($file_qr[$s] as $f => $d) {
			// 				if ($c['id'] == $f) {
			// 					$c['file'] = $d;
			// 					$pen[$z][$s] = $c;
			// 				}
			// 			}
			// 		}
			// 	}
			// $sum24 = 0;
			// $sumlast = 0;
			// if ($pen)
			// 	foreach ($pen as $f) {
			// 		foreach ($f as $b) {
			// 			if ($b['id'] == '24') {
			// 				$sum24++;
			// 			}
			// 			if ($b['last']) {
			// 				$sumlast++;
			// 			}
			// 		}
			// 	}
			// $this->data['colspan24'] = $sum24;
			// $this->data['colspanlast'] = $sumlast;
			$this->data['file_qr'] = $file_qr;
			$this->data['penandatangan'] = $penandatangan;
			$this->data['id_scorecard'] = $this->get['id_scorecard'];


			$id_jabatan = $this->conn->GetOne("select b.owner from risk_scorecard b where b.deleted_date is null and id_scorecard = " . $this->conn->escape($id_scorecard));
			// $this->conn->debug = 1;
			$parent = $this->getParent($id_jabatan);
			if ($parent)
				$this->data['tertinggi'] = $this->conn->GetRow("select a.nama_lengkap nama, b.nama as jabatan from mt_sdm_jabatan b left join mt_sdm_pegawai a on a.position_id = b.position_id  where b.deleted_date and b.id_jabatan = " . $this->conn->escape($parent));
			// dpr($tertinggi,1);

		}
		// dpr($id_scorecard);
		// $this->conn->debug=1;
		// $this->data['mtriskkemungkinan'] = $this->conn->GetArray("select * from mt_risk_kemungkinan order by id_kemungkinan desc");
		// $this->data['mtriskdampak'] = $this->conn->GetArray("select * from mt_risk_dampak order by id_dampak asc");
		// $this->data['mtriskmatrix'] = $this->conn->GetArray("select mrm.*, mrt.nama, mrt.warna
		// 	from mt_risk_matrix mrm
		// 	join mt_risk_tingkat mrt on mrt.id_tingkat = mrm.id_tingkat");
		$this->conn->debug = 1;

		$this->View($this->viewprint);
	}

	public function getParent($id)
	{
		if ($id) {
			$idd = $this->conn->GetOne("select id_jabatan_parent from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($id));
			$cek = $this->conn->GetOne("select 1 from mt_sdm_jabatan where deleted_date is null and lower(nama) like '%direktur%' and id_jabatan = " . $this->conn->escape($idd));

			if (!$cek) {
				$ret1 = $this->getParent($idd);
			} else {
				$ret1 = $idd;
			}
		}
		return ($ret1);
	}

	public function get_last_parent($data)
	{
		// dpr($data,1);
		$set = array();
		if ($data)
			foreach ($data as $a => $b) {
				foreach ($b as $c) {
					if ($c['id'] == 3) {
						$set[$a] = $c;
					}
				}
			}

		$arr = [];
		if ($set)
			foreach ($set as $g => $f) {
				$this->Get_parent($f['id_jabatan'], $arr, $g);
			}
		if ($arr) {
			foreach ($arr as $b => $c) {
				// $last[$b] = $this->conn->GetRow("
				// select *
				// from mt_sdm_jabatan a
				// where id_jabatan in (".implode(",",$c).")
				// and (id_jabatan_parent is null or exists
				// (select 1
				// from public_sys_user_group b
				// where b.id_jabatan = a.id_jabatan and group_id = 50))");
				$last[$b] = $this->conn->GetRow("
					select c.name nama, a.nama jabatan , b.group_id
					from 
					    public_sys_user c 
					    join public_sys_user_group b on c.user_id= b.user_id
						join mt_sdm_jabatan a on a.id_jabatan = b.id_jabatan
					where a.id_jabatan in (" . implode(",", $c) . ")
					and (id_jabatan_parent is null or exists
					(select 1
					from public_sys_user_group b
					where b.deleted_date is null and b.id_jabatan = a.id_jabatan and group_id = 50))");
			}
		}
		if ($data)
			foreach ($data as $d => &$e) {
				if ($last)
					foreach ($last as $f => $g) {
						if ($d == $f) {
							$e[$g['group_id']] = $g;
							$e[$g['group_id']]['last'] = true;
						}
					}
			}
		return $data;
	}

	public function Get_parent($id, &$arr, $idd)
	{
		if (!$id || in_array($id, $arr))
			return;

		$arr[$idd][] = $id;

		$sql = "select 
		distinct b.id_jabatan_parent
		from mt_sdm_jabatan b 
		where b.id_jabatan = " . $this->conn->escape($id);

		$rows = $this->conn->GetArray($sql);

		if ($rows) {
			foreach ($rows as $r) {
				$this->Get_parent($r['id_jabatan_parent'], $arr, $idd);
			}
		}
	}

	public function Get_parentbak($data, &$arr)
	{
		$arr = [];
		$arr[] = $data;
		foreach ($data as $c => $d) {
			// $id_jabatan = $id_jabatan ? $id_jabatan : $d['id_jabatan'];
			$id_jabatan = $d['id_jabatan'];
			$id_jabatan_parent = $this->conn->GetOne("select id_jabatan_parent from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($id_jabatan));
			// dpr($id_jabatan_parent);
			if ($id_jabatan_parent) {
				$cek = $this->conn->GetList("select * from public_sys_user_group where deleted_date is null and id_jabatan = " . $this->conn->escape($id_jabatan_parent));
				if (!$cek || $cek['group_id'] !== '50') {
					$dat[$c]['id_jabatan'] = $id_jabatan_parent;
					$this->Get_parent($dat);
				} else {
					$arr[$c] = $cek;
				}
			} else {
				// $arr[$c] = $this->conn->GetList("select * from public_sys_user_group where id_jabatan = " . $this->conn->escape($id_jabatan_parent));
			}
		}
		// dpr($data, 1);
	}

	// foreach ($dt as $f) {
	// 	$n = 0;
	// 	while ($n < 1) {
	// 		# ambil id_parent
	// 		$id_jabatan_parent = $this->conn->GetOne("select id_jabatan_parent from mt_sdm_jabatan where id_jabatan = " . $f['id_jabatan']);

	// 		if ($id_jabatan_parent) {
	// 			$parent = $this->conn->GetRow("select * from public_sys_user_group where id_jabatan = " . $id_jabatan_parent);
	// 			if ($parent['group_id'] == 50 || $parent['group_id'] = null && $parent['user_id']) {
	// 				dpr($n);
	// 				$n++;
	// 			} else {
	// 				$f['id_jabatan'] = $id_jabatan_parent;
	// 			}
	// 		}
	// 	}
	// 	dpr($parent, 1);
	// }
	public function set_data($data)
	{
		// dpr($data, 1);
		foreach ($data as &$f) {
			$f['prioritas_risiko'] = strtoupper($this->data['prioritas'][$f['id_prioritas']]);

			$integrasi_internal = $this->conn->GetList("select table_code idkey,'-'+table_desc val from mt_sdm_unit a where exists(select 1 from risk_integrasi_internal b where b.deleted_date is null trim(a.table_code) = trim(b.id_unit) and b.id_risiko = " . $this->conn->escape($f['id_risiko']) . ")");
			$f['integrasi_internal'] = implode('<br>', $integrasi_internal);

			$f['is_opp_inherent1'] = $f['is_opp_inherent'];
			$f['is_opp_inherent2'] = $f['is_opp_inherent'];
			$f['sasaran'] = $this->conn->GetOne("select nama from risk_sasaran a where a.deleted_date is null and exists(select 1 from risk_risiko b where  a.id_sasaran = b.id_sasaran and b.id_risiko = " . $this->conn->escape($f['id_risiko']) . ") ");


			$dampak[$f['id_risiko']] = $this->conn->GetArray("select nama from risk_dampak a where a.deleted_date is null and exists(select 1 from risk_risiko_dampak b where b.deleted_date is null and a.id_risk_dampak = b.id_risk_dampak and b.id_risiko = " . $this->conn->escape($f['id_risiko']) . ") ");
			$f['dampak'] = $this->conn->GetArray("select id_risk_dampak,nama from risk_dampak a where a.deleted_date is null and exists(select 1 from risk_risiko_dampak b where b.deleted_date is null and a.id_risk_dampak = b.id_risk_dampak and b.id_risiko = " . $this->conn->escape($f['id_risiko']) . ") ");
			$dampak = $this->conn->GetList("select id_risk_dampak idkey, nama val from risk_dampak a where a.deleted_date is null and exists(select 1 from risk_risiko_dampak b where a.id_risk_dampak = b.id_risk_dampak and b.id_risiko = " . $this->conn->escape($f['id_risiko']) . ") ");
			if ($dampak) {
				$nod = 1;
				foreach ($dampak as $v) {
					if (count($dampak)) {
						if ($v !== '' || $v !== null) {
							$dampak1[] = $nod . ". " . $v;
						}
					} else {
						if ($v !== '' || $v !== null) {
							$dampak1[] = $v;
						}
					}
					$nod++;
				}
			}
			unset($f['dampak']);
			if ($dampak1) {
				$f['dampak'] = implode('<br>', $dampak1);
			}
			unset($dampak1);


			$f['penyebab'] = $this->conn->GetArray("select id_risk_penyebab,nama from risk_penyebab a where a.deleted_date is null and exists(select 1 from risk_risiko_penyebab b where a.id_risk_penyebab = b.id_risk_penyebab and b.id_risiko = " . $this->conn->escape($f['id_risiko']) . ") ");
			$penyebab = $this->conn->GetList("select id_risk_penyebab idkey,nama val from risk_penyebab a where a.deleted_date is null and exists(select 1 from risk_risiko_penyebab b where a.id_risk_penyebab = b.id_risk_penyebab and b.id_risiko = " . $this->conn->escape($f['id_risiko']) . ") ");
			if ($penyebab) {
				$nop = 1;
				foreach ($penyebab as $b) {
					if (count($penyebab) > 1) {
						if ($b !== '' || $b !== null)
							$penyebab1[] = $nop . ". " . $b;
					} else {
						if ($b !== '' || $b !== null)
							$penyebab1[] = $b;
					}
					$nop++;
				}
			}

			unset($f['penyebab']);
			if ($penyebab1) {
				$f['penyebab'] = implode('<br>', $penyebab1);
			}
			unset($penyebab1);


			#controll
			$mitigasi = $this->conn->GetList("select id_control idkey, nama val from risk_control a where a.deleted_date is null exists(select 1 from risk_control_risiko b where b.id_control = a.id_control and id_risiko = " . $this->conn->escape($f['id_risiko']) . ")");

			if ($mitigasi) {
				$nom = 1;
				foreach ($mitigasi as $n) {
					if (count($mitigasi) > 1) {
						if ($n !== '' || $n !== null)
							$mitigasi1[] = $nom . ". " . $n;
					} else {
						if ($n !== '' || $n !== null)
							$mitigasi1[] = $n;
					}
					$nom++;
				}
			}
			unset($f['nama_kontrol']);
			if ($mitigasi1)
				$f['nama_kontrol'] = implode('<br>', $mitigasi1);
			unset($mitigasi1);

			if ($f['id_aspek_lingkungan']) {
				$f['id_aspek_lingkungan'] = $this->data['operasionalarr'][$f['id_aspek_lingkungan']];
			}



			# mitigasi
			$mitigasi_ke2 = $this->conn->GetList("select id_mitigasi idkey, nama val from risk_mitigasi a where a.deleted_date is null and exists(select 1 from risk_mitigasi_risiko b where b.id_mitigasi = a.id_mitigasi and id_risiko = " . $this->conn->escape($f['id_risiko']) . ")");
			if ($mitigasi_ke2) {
				$no2 = 1;
				foreach ($mitigasi_ke2 as $a) {
					if ($a !== '' || $a !== null)
						if (count($mitigasi) > 1) {
							$mitigasi2[] = $no2 . ". " . $a;
						} else {
							$mitigasi2[] = $a;
						}
					$no2++;
				}
			}

			$mitigasi_ke2 = $this->conn->GetArray("select nomor id, nama val from risk_mitigasi a where a.deleted_date is null and exists(select 1 from risk_mitigasi_risiko b where b.id_mitigasi = a.id_mitigasi and id_risiko = " . $this->conn->escape($f['id_risiko']) . ")");
			if ($mitigasi_ke2) {
				$no2 = 1;
				foreach ($mitigasi_ke2 as $d) {
					if ($d['val'] !== '' || $d['val'] !== null) {
						if (count($mitigasi_ke2) > 1)
							$mitigasi2[] = $no2 . ". " . $d['val'];
						else
							$mitigasi2[] = $d['val'];
					}
					$mitigasi2no[] =  $no2 . ". " . $d['id'];
					$no2++;
				}
			}
			unset($f['mitigasi_lanjutan']);
			if ($mitigasi2) {
				$f['mitigasi_lanjutan'] = implode('<br>', $mitigasi2);
			}
			unset($mitigasi2);
			unset($f['nomor_mitigasi_lanjutan']);
			if ($mitigasi2no) {
				$f['nomor_mitigasi_lanjutan'] = implode('<br>', $mitigasi2no);
			}
			unset($mitigasi2no);
		}

		// dpr($data, 1);
		return $data;
		dpr($data, 1);
	}

	public function set_databak($data)
	{
		foreach ($data as $f) {
			$id_risiko[] = $f['id_risiko'];
		}
		$id_risiko = implode(',', $id_risiko);
		die;
	}
}
