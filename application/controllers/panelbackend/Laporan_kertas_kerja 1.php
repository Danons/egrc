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
		// unset($this->data['tingkatarr']['']);
		krsort($this->data['tingkatarr']);

		$this->load->model("Risk_sasaranModel", "sasaranstrategis");

		$this->load->model("Mt_risk_efektifitas_pengukuranModel", "pengukuran");
		$this->data['mtpengukuranarr'] = $this->pengukuran->GetCombo();

		$this->load->model("Mt_risk_efektif_m_pengukuranModel", "pengukuranm");
		$this->data['mtpengukuranmarr'] = $this->pengukuranm->GetCombo();

		$this->load->model("Mt_risk_efektifitas_jawabanModel", "jawaban");
		$this->data['mtjawabanarr'] = $this->jawaban->GetCombo();

		$this->load->model("Mt_risk_efektif_m_jawabanModel", "jawabanm");
		$this->data['mtjawabanmarr'] = $this->jawabanm->GetCombo();
		// unset($this->data['mtjawabanarr']['']);

		$this->load->model("Mt_risk_taksonomi_objectiveModel", 'taksonomi');
		$this->data['taksonomiarr'] = $this->taksonomi->GetCombo();

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'treetable'
		);
	}

	function Header()
	{
		$norowspan_control = array();
		$return = array();


		$this->data['type_header'] = array(
			'id_pengukuran' => array(
				'list' => $this->data['mtpengukuranarr']
			),
			'id_pengukuranm' => array(
				'list' => $this->data['mtpengukuranmarr']
			),
			// 'capaian_mitigasi' => array(
			// 	'list' => $this->data['pregressarr']
			// ),
			'kategori_kemungkinan' => array(
				'list' => array('' => '', '1' => 'Deskripsi Kualitatif', '2' => 'Probabilitas', '3' => 'Insiden Sebelumnya')
			),
			'waktu_pelaksanaan' => 'date',
			'biaya_mitigasi' => 'rupiah',
			'level_risiko_inheren' => 'rating',
			'level_risiko_paskakontrol' => 'rating',
			'level_risiko_residual' => 'rating',
			'level_risiko_actual' => 'rating',
		);

		$return["scorecard"] = "Kajian Risiko";
		$return["kode_risiko"] = "Kode";
		$return["sasaran"] = "Sasaran Strategis";
		$return["kegiatan"] = "Sasaran Kegiatan";
		$return["taksonomi"] = "Taksonomi";
		/*"proses_bisnis"=>array(
				"Proses Bisnis"=>
				array(
					"kategori_proses"=>"Kategori",
					"kelompok_proses"=>"Kelompok Proses",
					"nama_proses"=>"Nama Proses",
					"aktifitas"=>"Aktivitas",
				)
			),*/
		$return["identifikasi_risiko"] = array(
			"Identifikasi Risiko" =>
			array(
				"risiko" => "Risiko",
				"penyebab" => "Penyebab",
				"dampak" => "Dampak"
			)
		);

		$return['risiko_induk'] = "Risiko Induk";
		$return["risk_owner"] = "Pemilik Risiko";
		$return["status_risiko"] = "Status Risiko";
		$return["risiko_inheren"] = array(
			"Inheren Risk" =>
			array(
				"inheren_kemungkinan" => "Tingkat Kemungkinan",
				"kategori_kemungkinan" => "Kriteria Kemungkinan",
				"inheren_dampak" => "Tingkat Dampak",
				"kategori_dampak" => "Kriteria Dampak",
				"level_risiko_inheren" => "Lavel Risiko"
			)
		);

		$return["pengendalian_risiko_saat_ini"] = array(
			"Pengendalian Risiko" => array(
				"nama_kontrol" => "Aktivitas yang sudah ada untuk Pencegahan dan Pemulihan",
				"control_menurunkan" => "Menurunkan Dampak atau Kemungkinan ?"
			)
		);

		$norowspan_control[] = "nama_kontrol";
		$norowspan_control[] = "control_menurunkan";

		unset($this->data['efektifarr']['']);
		foreach ($this->data['efektifarr'] as $idkey => $value) {
			$return['pengendalian_risiko_saat_ini']['Pengendalian Risiko']['efektif_' . $idkey] = $value;

			$this->data['type_header']['efektif_' . $idkey] = array(
				'list' => $this->data['mtjawabanarr']
			);

			$norowspan_control[] = 'efektif_' . $idkey;
		}

		$return['pengendalian_risiko_saat_ini']['Pengendalian Risiko']['id_pengukuran'] = "Control Efektif";

		$norowspan_control[] = "id_pengukuran";

		$return["risiko_paska_kontrol"] = array(
			"Control Risk" => array(
				"kemungkinan_paskakontrol" => "Tingkat Kemungkinan",
				"dampak_paskakontrol" => "Tingkat Dampak",
				"level_risiko_paskakontrol" => "Level Risiko"
			)
		);

		$return["risiko_evaluasi"] = array(
			"Actual Risiko" => array(
				"kemungkinan_actual" => "Tingkat Kemungkinan",
				"dampak_actual" => "Tingkat Dampak",
				"level_risiko_actual" => "Level Risiko"
			)
		);

		$mitigasi_risiko = array();
		$mitigasi_risiko["nama_mitigasi"] = "Rencana Penanganan Risiko (Mitigasi)";
		$mitigasi_risiko["mitigasi_menurunkan"] = "Menurunkan Dampak atau Kemungkinan ?";
		$mitigasi_risiko["waktu_pelaksanaan"] = "Due Date Mitigasi (Action&nbsp;Plan)";
		$mitigasi_risiko["biaya_mitigasi"] = "Biaya Mitigasi";
		$mitigasi_risiko["cba_mitigasi"] = "Cost Benefit Analysis (CBA) atas Rencana Penanganan Risiko";
		$mitigasi_risiko["penanggungjawab_mitigasi"] = "Penanggung Jawab Rencana Mitigasi";
		$mitigasi_risiko["capaian_mitigasi_progress"] = "Capaian / Progress Pelaksanaan Rencana Mitigasi";


		$norowspan_mitigasi = array();
		$norowspan_mitigasi[] = "nama_mitigasi";
		$norowspan_mitigasi[] = "mitigasi_menurunkan";
		$norowspan_mitigasi[] = "waktu_pelaksanaan";
		$norowspan_mitigasi[] = "biaya_mitigasi";
		$norowspan_mitigasi[] = "cba_mitigasi";
		$norowspan_mitigasi[] = "penanggungjawab_mitigasi";
		$norowspan_mitigasi[] = "capaian_mitigasi_progress";

		unset($this->data['efektifmarr']['']);
		foreach ($this->data['efektifmarr'] as $idkey => $value) {
			$mitigasi_risiko['efektifm_' . $idkey] = $value;

			$this->data['type_header']['efektifm_' . $idkey] = array(
				'list' => $this->data['mtjawabanmarr']
			);

			$norowspan_mitigasi[] = 'efektifm_' . $idkey;
		}

		$mitigasi_risiko['id_pengukuranm'] = "Mitigasi Efektif";
		$norowspan_mitigasi[] = "id_pengukuranm";
		$return['mitigasi_risiko']['Mitigasi Risiko'] = $mitigasi_risiko;


		$return["risiko_residual"] = array(
			"Target Residual" => array(
				"kemungkinan_rdual" => "Tingkat Kemungkinan",
				"dampak_rdual" => "Tingkat Dampak",
				"level_risiko_residual" => "Level Risiko"
			)
		);

		$return["capaian_mitigasi_evaluasi"] = "Progress Capaian Kinerja";
		$return["hambatan_kendala"] = "Hambatan / Kendala Pelaksanaan Tindakan Mitigasi / Capaian Kinerja / Isu";
		$return["penyesuaian_mitigasi"] = "Penyesuaian Tindakan Mitigasi (jika diperlukan)";

		$this->data['norowspan_control'] = $norowspan_control;
		$this->data['norowspan_mitigasi'] = $norowspan_mitigasi;
		$this->data['norowspan'] = array_merge($norowspan_control, $norowspan_mitigasi);

		$id_kajian_risiko = $this->data['row']['id_kajian_risiko'];

		if ($id_kajian_risiko) {
			if (!$this->mtkajianrisiko->isKegiatan($id_kajian_risiko))
				unset($return['kegiatan']);
		}
		return $return;
	}

	function Index($page = 1)
	{
		// unset($this->data['mtjeniskajianrisikoarr']['']);
		$this->data['mtjeniskajianrisikoarr'] = $this->data['mtjeniskajianrisikoarr'];

		$this->data['row'] = $this->post;

		/*if(!$this->data['row']['id_kajian_risiko'])
			$this->data['row']['id_kajian_risiko'] = key($this->data['mtjeniskajianrisikoarr']);*/
		$tahun = date('Y');

		if ($this->data['row']['tahun'])
			$tahun = $this->data['row']['tahun'];

		$this->data['sasaranarr'] = $this->sasaranstrategis->GetCombo(null, null, $tahun);
		// unset($this->data['sasaranarr']['']);
		$this->data['sasaranarr'] = $this->data['sasaranarr'];

		if ($this->data['row']['id_kajian_risiko'] == 'semua')
			unset($this->data['row']['id_kajian_risiko']);

		// if ($id_kajian_risiko = $this->data['row']['id_kajian_risiko']) {
		$this->data['rowscorecards'] = $this->mscorecard->GetList(null, null, 1, $tahun);
		// }

		$this->data['header'] = $this->Header();

		$this->View($this->viewindex);
	}

	public function go_print()
	{
		if (!$this->access_role['view_all']) {
			$this->get['id_unit'] = $_SESSION[SESSION_APP]['id_unit'];
		}

		if (!$this->get['header'])
			$this->get['header'] = array();

		$this->data['no_header'] = true;
		$this->data['no_title'] = true;
		//$bulanarr = ListBulan();
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";

		$this->data['page_title'] .= "<br/>" . $this->data['mtjeniskajianrisikoarr'][$this->get['id_kajian_risiko']];

		if ($this->get['id_scorecard'] == 1) {
			$this->conn->escape_string($this->get['id_scorecard']);
			$row_score = $this->conn->GetRow("select id_parent_scorecard, nama from risk_scorecard where deleted_date is not null and id_scorecard in ('" . implode("','", $this->get['id_scorecard']) . "')");

			$id_parent_scorecard = $row_score['id_parent_scorecard'];
			$nama_scorecard = $row_score['nama'];

			if ($id_parent_scorecard) {
				$nama_parent = $this->conn->GetOne("select nama from risk_scorecard where deleted_date is not null and id_scorecard = " . $this->conn->escape($id_parent_scorecard));
				if ($nama_parent)
					$this->data['page_title'] .= " " . $nama_parent . " ";
			}

			if ($nama_scorecard)
				$this->data['page_title'] .= " " . $nama_scorecard . " ";
		}

		$this->data['page_title'] .= " " . ($this->get['tahun'] ? " Tahun " . $this->get['tahun'] : "");
		$this->data['namaunit'] = $this->data['unitarr'][$this->get['id_unit']];

		$this->data['page_title'] = strtoupper($this->data['page_title']);

		$this->data['warnarr'] = array();
		$rowswarna = $this->conn->GetArray("select k.kode k, d.kode d, t.warna from mt_risk_matrix mx 
			join mt_risk_kemungkinan k on mx.id_kemungkinan = k.id_kemungkinan
			join mt_risk_dampak d on mx.id_dampak = d.id_dampak
			join mt_risk_tingkat t on mx.id_tingkat = t.id_tingkat
			where mx.deleted_date is null
			");
		foreach ($rowswarna as $r) {
			$this->data['warnarr'][$r['k'] . $r['d']] = $r['warna'];
		}

		$header = $this->Header();

		$this->data['header1']  = array();
		$this->data['header2']  = array();

		foreach ($header as $idkey => $value) {
			if (is_array($value)) {
				$label = key($value);
				$header1 = $value[$label];


				$colspan = 0;
				foreach ($header1 as $k => $v) {

					if (!$this->get['header'][$k])
						continue;

					$this->data['header2'][$k]["label"] = $v;
					$this->data['header2'][$k]["rowspan"] = 1;
					$this->data['header2'][$k]["colspan"] = 1;
					$colspan++;
				}

				if ($colspan) {
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

		$this->data['rows'] = $this->model->getListKertasKerja($param);

		$this->data['mtriskkemungkinan'] = $this->conn->GetArray("select * from mt_risk_kemungkinan where deleted_date is null and order by id_kemungkinan desc");
		$this->data['mtriskdampak'] = $this->conn->GetArray("select * from mt_risk_dampak where deleted_date is null and order by id_dampak asc");
		$this->data['mtriskmatrix'] = $this->conn->GetArray("select mrm.*, mrt.nama, mrt.warna
			from mt_risk_matrix mrm
			join mt_risk_tingkat mrt on mrt.id_tingkat = mrm.id_tingkaT where deleted_date is null");

		$this->View($this->viewprint);
	}
}
