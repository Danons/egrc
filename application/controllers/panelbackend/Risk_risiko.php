<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Risk_risiko extends _adminController
{
	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/risk_risikolist";
		$this->viewdetail = "panelbackend/risk_risikodetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_scorecard";
		$this->viewprint = "panelbackend/listprint";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Identifikasi Risiko';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Identifikasi Risiko';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Identifikasi Risiko';
			$this->data['edited'] = false;
		} else {
			$this->data['notab'] = true;
			$this->data['page_title'] = 'Daftar Risiko/Peluang';
		}

		$this->load->model("Risk_risikoModel", "model");
		$this->load->model("Risk_scorecardModel", 'riskscorecard');
		/* proyek terkait list
		$this->data['scorecardarrlist'] = $this->riskscorecard->GetCombo2(null, null, null, null, " rutin_non_rutin = 'nonrutin'");
		*/

		$this->load->model("Risk_risiko_filesModel", "modelfile");
		$this->load->model("Mt_risk_kriteria_dampakModel", 'kriteria');

		$this->data['kriteriaarr'] = $this->kriteria->GetCombo();
		$this->data['kriteriakemungkinanarr'] = array('' => '', '1' => 'Probabilitas', '2' => 'Deskripsi Kualitatif', '3' => 'Insiden Sebelumnya');

		$this->load->model("Mt_aspek_lingkunganModel", 'aspek_lingkungan');
		$this->data['aspek_lingkunganarr'] = $this->aspek_lingkungan->GetCombo();

		// $this->data['kategoriarr'] = array("" => 'Pilih') +  $this->conn->GetList("select id_kategori as idkey, kode+' '+nama as val from mt_pb_kategori");

		$this->data['operasionalarr'] = array("" => 'Pilih') + $this->conn->GetList("select id_aspek_lingkungan as idkey, concat(kode,' ',nama) as val from mt_aspek_lingkungan where deleted_date is null");

		$this->load->model("Mt_risk_taksonomi_areaModel", 'taksonomiarea');
		// $this->data['taksonomiareaarr'] = $this->taksonomiarea->GetCombo();
		$this->data['taksonomiareaarr'] = $this->taksonomiarea->GetCombo_jenis();
		$this->load->model("KpiModel", 'kpi');

		$this->load->model("Risk_sasaranModel", 'risksasaran');
		// $this->data['risksasaranarr'] = $this->risksasaran->GetCombo();

		$this->load->model("Risk_penyebabModel", 'riskpenyebab');
		// $this->data['riskpenyebabarr'] = $this->riskpenyebab->GetCombo();

		$this->load->model("Risk_dampakModel", 'riskdampak');
		// $this->data['riskdampakarr'] = $this->riskdampak->GetCombo();

		$this->SetAccess('panelbackend/risk_scorecard');
		$this->load->helper("form");

		$this->load->model("Mt_risk_efektifitas_pengukuranModel", "pengukuran");
		$this->data['mtpengukuranarr'] = $this->pengukuran->GetCombo();
		unset($this->data['mtpengukuranarr']['']);

		$this->data['kriteriakemungkinanarr'] = array('' => '', '1' => 'Probabilitas', '2' => 'Deskripsi Kualitatif', '3' => 'Insiden Sebelumnya');
		$this->data['prioritas'] = $this->conn->GetList("select id_prioritas as idkey, nama val from mt_prioritas where deleted_date is null");
		$this->data['prioritaswarna'] = $this->conn->GetList("select id_prioritas as idkey, warna val from mt_prioritas where deleted_date is null");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'upload', 'select2'
		);
		$this->data['configfile'] = $this->config->item('file_upload_config');

		// $this->access_role['list_print'] = true;
	}

	protected function Header()
	{
		$return = array(
			array(
				'name' => 'id_kegiatan',
				'label' => 'Kegiatan',
				// 'width' => "auto",
				'type' => "list",
				'value' => $this->data['kegiatanarr'],
			),
			array(
				'name' => 'nomor',
				'label' => 'Kode',
				// // 'width' => "110px",
				'type' => "varchar2",
			)
		);

		if ($this->data['rowheader']['id_nama_proses']) {
			$return = array_merge($return, array(
				array(
					'name' => 'nama_aktifitas',
					'label' => 'Aktivitas',
					// 'width' => "auto",
					'type' => "varchar2",
				)
			));
		}

		$return = array_merge($return, array(
			array(
				'name' => 'nama',
				'label' => 'Nama Risiko',
				// 'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'sumber',
				'label' => 'Penyebab',
				// 'width' => "auto",
				'type' => "varchar2",
				'noorder' => true,
			),
			array(
				'name' => 'dampak',
				'label' => 'Dampak',
				// 'width' => "auto",
				'type' => "varchar2",
				'noorder' => true,
			),
			array(
				'name' => 'inheren',
				'label' => 'Inherent',
				// // 'width' => "70px",
				'type' => "list",
				'value' => $this->data['mttingkatdampakarr'],
			),
			array(
				'name' => 'control',
				'label' => 'Residual Saat Ini',
				// // 'width' => "70px",
				'type' => "list",
				'value' => $this->data['mttingkatdampakarr'],
			),
			array(
				'name' => 'actual',
				'label' => 'Residual Setelah Evaluasi',
				// // 'width' => "70px",
				'type' => "list",
				'value' => $this->data['mttingkatdampakarr'],
			)
		));

		// if (!$this->data['rowheader']['id_nama_proses']) {
		// 	$return = array_merge($return, array(
		// 		array(
		// 			'name' => 'risidual',
		// 			'label' => 'Risidual Risk',
		// 			// 'width' => "70px",
		// 			'type' => "list",
		// 			'value' => $this->data['mttingkatdampakarr'],
		// 		),
		// 	));
		// }
		// $return = array_merge($return, array(
		// 	array(
		// 		'name' => 'status_risiko',
		// 		'label' => 'Status',
		// 		'width' => "100px",
		// 		'type' => "list",
		// 		'value' => array(
		// 			'' => '',
		// 			// '0' => 'Close', 
		// 			'1' => 'Open', '2' => 'Berlanjut'
		// 		),
		// 	)
		// ));

		// if ($this->Access('pengajuan', "panelbackend/risk_scorecard") && $this->data['rowheader']['id_status_pengajuan'] == 5) {
		// 	$return = array_merge($return, array(
		// 		array(
		// 			'name' => 'is_evaluasi_mitigasi',
		// 			'label' => 'Monitoring',
		// 			// 'width' => "75px",
		// 			'type' => "list",
		// 			'value' => array('' => 'Belum', '0' => 'Belum', '1' => 'Sudah'),
		// 		)
		// 	));
		// }

		// if ($this->Access('evaluasimitigasi', "panelbackend/risk_scorecard")) {
		// 	$return = array_merge($return, array(
		// 		array(
		// 			'name' => 'is_evaluasi_risiko',
		// 			'label' => 'Evaluasi',
		// 			// 'width' => "70px",
		// 			'type' => "list",
		// 			'value' => array('' => 'Belum', '0' => 'Belum', '1' => 'Sudah'),
		// 		)
		// 	));
		// }

		return $return;
	}

	private function AddOption()
	{
		$ada = $this->data['mtaktifitasarrtemp'][$this->post['id_aktifitas']];
		if (!$ada && $this->post['id_aktifitas']) {
			$record = array();
			$record['nama'] = $this->post['id_aktifitas'];
			$record['id_nama_proses'] = $this->data['rowheader']['id_nama_proses'];
			$id = $this->conn->GetOne("select id_aktifitas from mt_pb_aktifitas where deleted_date is null and nama = '{$record['nama']}'");

			if (!$id) {
				$sql = $this->conn->InsertSQL("mt_pb_aktifitas", $record);
				$this->conn->Execute($sql);

				$id = $this->conn->GetOne("select id_aktifitas from mt_pb_aktifitas where deletd_date is null and nama = '{$record['nama']}'");
			}

			$this->post['id_aktifitas'] = $_POST['id_aktifitas'] = $id;

			$this->data['mtaktifitasarr'][$id] = $record['nama'];

			unset($this->data['mtaktifitasarr'][$record['nama']]);
		}
	}

	protected function Record($id = null)
	{
		if ($this->post['var']) {
			$total = 0;
			$jumlah = 0;
			$arr = array();
			foreach ($this->post['var'] as &$r) {
				$r['target'] = Rupiah2Number($r['target']);
				$r['realisasi'] = Rupiah2Number($r['realisasi']);
				$r['devisiasi'] = $r['realisasi'] - $r['target'];
				$arr[] = $r['devisiasi'];
				$total += $r['devisiasi'];
				$jumlah++;
			}
			$std = stats_standard_deviation($arr);
			$avg = $total / $jumlah;
			$sqrt = sqrt($jumlah);
			//=+E20+(1.645*(E21/E24))
			$var = +$avg + (1.645 * ($std / $sqrt));

			$this->post['dampak_kuantitatif_inheren'] = $var;
		}
		if ($this->data['rowheader']['rutin_non_rutin'] == 'nonrutin') {
			$this->post['id_sasaran'] = $this->data['rowheader']['id_sasaran_proyek'];
		}
		$this->AddOption();
		$record =  array(
			// 'nama_kegiatan' => $this->post['nama_kegiatan'],
			'selera_dampak' => $this->post['selera_dampak'],
			'selera_kemungkinan' => $this->post['selera_kemungkinan'],
			'id_kategori' => $this->post['id_kategori'],
			// 'sub_tahapan_kegiatan' => $this->post['sub_tahapan_kegiatan'],
			// 'skor_inheren_dampak'=>$this->post['skor_inheren_kemungkinan'],
			// 'id_risiko_parent' => ($this->data['rowheader1']['id_risiko_parent'] ? $this->data['rowheader1']['id_risiko_parent'] : null),
			'id_risiko_parent' => ($this->post['id_risiko_parent'] ? $this->post['id_risiko_parent'] : null),
			// 'id_risiko_parent_lain' => ($this->post['id_risiko_parent'] === '0' ? 1 : 0),
			'regulasi' => $this->post['id_taksonomi_area'] == 89 ? $this->post['regulasi'] : null,
			// 'id_jabatan_berisiko' => $this->post['id_jabatan_berisiko'],
			'red_flag' => $this->post['red_flag'],

			'nama' => $this->post['nama'],
			// 'nama' => "coba",
			'nama_aktifitas' => $this->post['nama_aktifitas'],
			'kode_aktifitas' => $this->post['kode_aktifitas'],
			'deskripsi' => $this->post['deskripsi'],
			// 'inheren_dampak' => $this->post['inheren_dampak'],
			// 'inheren_kemungkinan' => $this->post['inheren_kemungkinan'],
			'residual_target_dampak' => $this->post['residual_target_dampak'],
			'residual_target_kemungkinan' => $this->post['residual_target_kemungkinan'],
			'penyebab' => $this->post['penyebab'],
			'dampak' => $this->post['dampak'],
			'id_kegiatan' => $this->post['id_kegiatan'],
			'id_sasaran' => $this->post['id_sasaran'],
			'control_dampak_penurunan' => $this->post['control_dampak_penurunan'],
			'control_kemungkinan_penurunan' => $this->post['control_kemungkinan_penurunan'],
			'mitigasi_dampak_penurunan' => $this->post['mitigasi_dampak_penurunan'],
			'mitigasi_kemungkinan_penurunan' => $this->post['mitigasi_kemungkinan_penurunan'],
			// 'current_risk_dampak' => $this->post['current_risk_dampak'],
			// 'current_risk_kemungkinan' => $this->post['current_risk_kemungkinan'],
			'id_kriteria_dampak' => $this->post['id_kriteria_dampak'],
			'id_kriteria_kemungkinan' => $this->post['id_kriteria_kemungkinan'],
			'id_taksonomi' => $this->post['id_taksonomi'],
			'id_taksonomi_area' => $this->post['id_taksonomi_area'],
			'id_aspek_lingkungan' => $this->post['id_aspek_lingkungan'],
			// 'id_risk_penyebab' => $this->post['id_risk_penyebab'],
			// 'id_kpi' => $this->post['id_kpi'],
			'sasaran' => $this->post['sasaran'],
			'id_risk_penyebab' => $this->post['id_risk_penyebab'],
			'id_risk_dampak' => $this->post['id_risk_dampak'],
			'is_rutin' => $this->post['is_rutin'],
			'id_kategori_proyek' => $this->post['id_kategori_proyek'],
			'proyek_terkait' => $this->post['proyek_terkait'],
			// 'dampak_kuantitatif_inheren' => Rupiah2Number($this->post['dampak_kuantitatif_inheren']),
		);

		if ($this->data['row']['status_risiko'] == 0 or $this->data['row']['status_risiko'] == 2 or $this->data['row']['tgl_close'])
			$record['tgl_close'] = $this->post['tgl_close'];

		if (!$record['id_sasaran'])
			$record['id_sasaran'] = $this->conn->GetOne("select id_sasaran from risk_kegiatan where deleted_date is null and id_kegiatan = " . $this->conn->escape($record['id_kegiatan']));

		if ($this->access_role['view_all'] && $id) {
			$record['nomor'] = $this->post['nomor'];
		}
		if ($this->access_role['view_all']) {
			$record['tgl_risiko'] = $this->post['tgl_risiko'];
		}

		if ($this->data['rowheader']['id_nama_proses'])
			$record['id_aktifitas'] = $this->post['id_aktifitas'];

		if ($this->access_role['rekomendasi']) {
			$record['rekomendasi_keterangan'] = $this->post['rekomendasi_keterangan'];

			if ($this->data['row']['is_lock'] == 1)
				$record['is_lock'] = 2;

			$record['rekomendasi_is_verified'] = 2;
			$record['rekomendasi_nid'] = $_SESSION[SESSION_APP]['nid'];
			$record['rekomendasi_jabatan'] = $_SESSION[SESSION_APP]['id_jabatan'];
			$record['rekomendasi_group'] = $_SESSION[SESSION_APP]['nama_group'];
			$record['rekomendasi_date'] = "{{sysdate()}}";
		}

		if ($this->access_role['review']) {
			$record['review_kepatuhan'] = $this->post['review_kepatuhan'];

			if ($this->data['row']['is_lock'] == 1)
				$record['is_lock'] = 2;

			$record['review_is_verified'] = 2;
			$record['review_nid'] = $_SESSION[SESSION_APP]['nid'];
			$record['review_jabatan'] = $_SESSION[SESSION_APP]['id_jabatan'];
			$record['review_group'] = $_SESSION[SESSION_APP]['nama_group'];
			$record['review_date'] = "{{sysdate()}}";
		}

		return $record;
	}

	protected function Rules()
	{
		$return = array(
			// "nama" => array(
			// 	'field' => 'nama',
			// 	'label' => 'Nama',
			// 	'rules' => "required|max_length[200]",
			// ),
			"id_kegiatan" => array(
				'field' => 'id_kegiatan',
				'label' => 'Kegiatan',
				'rules' => "required|max_length[200]",
			),
			"deskripsi" => array(
				'field' => 'deskripsi',
				'label' => 'Deskripsi',
				'rules' => "max_length[4000]",
			),
			// "inheren_dampak" => array(
			// 	'field' => 'inheren_dampak',
			// 	'label' => 'Tingkat Dampak',
			// 	'rules' => "in_list[" . implode(",", array_keys($this->data['mtdampakrisikoarr'])) . "]|required",
			// ),
			"id_kpi" => array(
				'field' => 'id_kpi',
				'label' => 'KPI',
				'rules' => "in_list[" . implode(",", array_keys($this->data['kpiarr'])) . "]",
			),
			"id_taksonomi_area" => array(
				'field' => 'id_taksonomi_area',
				'label' => 'Taksonomi',
				// 'rules' => "in_list[" . implode(",", array_keys($this->data['taksonomiareaarr'])) . "]|required",
			),
			// "inheren_kemungkinan" => array(
			// 	'field' => 'inheren_kemungkinan',
			// 	'label' => 'Tingkat Kemungkinan',
			// 	'rules' => "in_list[" . implode(",", array_keys($this->data['mtkemungkinanrisikoarr'])) . "]|required",
			// ),
			"control_dampak_penurunan" => array(
				'field' => 'control_dampak_penurunan',
				'label' => 'Dampak',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtdampakrisikoarr'])) . "]",
			),
			"control_kemungkinan_penurunan" => array(
				'field' => 'control_kemungkinan_penurunan',
				'label' => 'Tingkat',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtkemungkinanrisikoarr'])) . "]",
			),
			"mitigasi_dampak_penurunan" => array(
				'field' => 'mitigasi_dampak_penurunan',
				'label' => 'Dampak',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtdampakrisikoarr'])) . "]",
			),
			"mitigasi_kemungkinan_penurunan" => array(
				'field' => 'mitigasi_kemungkinan_penurunan',
				'label' => 'Tingkat',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtkemungkinanrisikoarr'])) . "]",
			),
			// "current_risk_dampak" => array(
			// 	'field' => 'current_risk_dampak',
			// 	'label' => 'Dampak',
			// 	'rules' => "in_list[" . implode(",", array_keys($this->data['mtdampakrisikoarr'])) . "]",
			// ),
			// "current_risk_kemungkinan" => array(
			// 	'field' => 'current_risk_kemungkinan',
			// 	'label' => 'Tingkat',
			// 	'rules' => "in_list[" . implode(",", array_keys($this->data['mtkemungkinanrisikoarr'])) . "]",
			// ),
			/*"id_taksonomi"=>array(
				'field'=>'id_taksonomi',
				'label'=>'Taksonomi',
				'rules'=>"in_list[".implode(",", array_keys($this->data['taksonomiarr']))."]",
			),*/
			"residual_target_dampak" => array(
				'field' => 'residual_target_dampak',
				'label' => 'Dampak',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtdampakrisikoarr'])) . "]",
			),
			"residual_target_kemungkinan" => array(
				'field' => 'residual_target_kemungkinan',
				'label' => 'Tingkat',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtkemungkinanrisikoarr'])) . "]",
			),
			// "id_sasaran" => array(
			// 	'field' => 'id_sasaran',
			// 	'label' => 'Kegiatan',
			// 	'rules' => "in_list[" . implode(",", array_keys($this->data['sasaranarr'])) . "]|required",
			// ),
			// "id_kegiatan" => array(
			// 	'field' => 'id_kegiatan',
			// 	'label' => 'Kegiatan',
			// 	'rules' => "in_list[" . implode(",", array_keys($this->data['mtkegiatanarr'])) . "]|required",
			// ),
			// "id_kriteria_dampak" => array(
			// 	'field' => 'id_kriteria_dampak',
			// 	'label' => 'Kategori',
			// 	'rules' => "in_list[" . implode(",", array_keys($this->data['kriteriaarr'])) . "]|required",
			// ),
			// "id_kriteria_kemungkinan" => array(
			// 	'field' => 'id_kriteria_kemungkinan',
			// 	'label' => 'Kategori',
			// 	'rules' => "in_list[" . implode(",", array_keys($this->data['kriteriakemungkinanarr'])) . "]|required",
			// ),
			"penyebab" => array(
				'field' => 'penyebab[]',
				// 'field' => 'id_risk_penyebab[]',
				'label' => 'Sumber',
				'rules' => "max_length[4000]|required",
				// 'rules' => "max_length[4000]|required|in_list[" . implode(",", array_keys($this->data['riskdampakarr'])) . "]",
			),
			"dampak" => array(
				'field' => 'dampak[]',
				// 'field' => 'id_risk_dampak[]',
				'label' => 'Dampak',
				'rules' => "max_length[4000]|required",
			),
		);

		// if ($this->data['rowheader']['jenis_sasaran'] != '2') {
		// 	unset($return['id_kegiatan']);
		// }

		if ($this->data['is_regulasi']) {
			$return['regulasi'] = array(
				'field' => 'regulasi',
				'label' => 'Regulasi',
				'rules' => "required",
			);
		}

		if ($this->access_role['view_all']) {
			$return['nomor'] = array(
				'field' => 'nomor',
				'label' => 'Nomor',
				'rules' => "required",
			);
			$return['tgl_risiko'] = array(
				'field' => 'tgl_risiko',
				'label' => 'Tgl. Risiko',
				'rules' => "required",
			);
		}

		if ($this->access_role['rekomendasi']) {
			$return['rekomendasi_keterangan'] = array(
				'field' => 'rekomendasi_keterangan',
				'label' => 'Dasar Penetapan Risiko',
				'rules' => "required",
			);
		}

		if ($this->access_role['review']) {
			$return['review_kepatuhan'] = array(
				'field' => 'review_kepatuhan',
				'label' => 'reviu Kepatuhan',
				'rules' => "required",
			);
		}

		if ($this->data['rowheader']['id_nama_proses']) {
			unset($return['id_kegiatan']);
			unset($return['id_sasaran']);

			$return['kode_aktifitas'] = array(
				'field' => 'kode_aktifitas',
				'label' => 'Kode Aktivitas',
				'rules' => "required",
			);

			$return['nama_aktifitas'] = array(
				'field' => 'nama_aktifitas',
				'label' => 'Nama Aktivitas',
				'rules' => "required",
			);

			/*$return['id_aktifitas'] = array(
				'field'=>'id_aktifitas', 
				'label'=>'id_aktifitas', 
				'rules'=>"required|in_list[".implode(",", array_keys($this->data['mtaktifitasarr']))."]",
			);*/
		}
		// dpr($this->data['row'],1);
		// if (in_array($this->data['row']['id_taksonomi_area'], array('89', '88'))) {
		// 	if ($this->data['row']['id_taksonomi_area'] == '89') {
		// 		$return['id_aspek_lingkungan'] = array(
		// 			'field' => 'id_aspek_lingkungan',
		// 			'label' => 'Operasional',
		// 			'rules' => "required",
		// 		);
		// 	}
		// 	$return['regulasi'] = array(
		// 		'field' => 'regulasi',
		// 		'label' => 'Pemenuhan Kewajiban',
		// 		'rules' => "required",
		// 	);
		// }

		if ($this->post['act'] == 'save_rekomendasi') {
			unset($return['id_kriteria_dampak']);
			unset($return['id_kriteria_kemungkinan']);
			unset($return['inheren_dampak']);
			unset($return['inheren_kemungkinan']);
		}
		// dpr($this->data['rowheader']['rutin_non_rutin'],1);
		if ($this->data['rowheader']['rutin_non_rutin'] == 'nonrutin') {
			unset($return["id_sasaran"]);
			unset($return["sasaran"]);
		}

		return $return;
	}

	public function inlistjabatan($str)
	{
		$result = $this->mjabatan->GetCombo($str);

		if (!$result[$str]) {
			$this->form_validation->set_message('inlistjabatan', 'Bidang tidak ditemukan');
			return FALSE;
		}

		return true;
	}

	public function Listdata($page = 0)
	{

		$this->viewlist = "panelbackend/risk_risikolist";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		$this->data['list'] = $this->_getList($page);
		$this->data['header'] = $this->Header();
		$this->data['page'] = $page;
		$param_paging = array(
			'base_url' => base_url("{$this->page_ctrl}/index"),
			'cur_page' => $page,
			'total_rows' => $this->data['list']['total'],
			'per_page' => $this->limit,
			'first_tag_open' => '<li class="page-item">',
			'first_tag_close' => '</li>',
			'last_tag_open' => '<li class="page-item">',
			'last_tag_close' => '</li>',
			'cur_tag_open' => '<li class="page-item active"><a href="#" class="page-link">',
			'cur_tag_close' => '</a></li>',
			'next_tag_open' => '<li class="page-item">',
			'next_tag_close' => '</li>',
			'prev_tag_open' => '<li class="page-item">',
			'prev_tag_close' => '</li>',
			'num_tag_open' => '<li class="page-item">',
			'num_tag_close' => '</li>',
			'anchor_class' => 'page-link',
			'attributes' => array('class' => 'page-link'),
		);
		$this->load->library('pagination');

		$paging = $this->pagination;

		$paging->initialize($param_paging);

		$this->data['paging'] = $paging->create_links();

		$this->data['limit'] = $this->limit;

		$this->data['limit_arr'] = $this->limit_arr;

		$this->View($this->viewlist);
	}

	public function Index($id_scorecard = null, $page = 0)
	{

		$this->_beforeDetail($id_scorecard);

		// dpr($this->access_role, 1);
		if ($_SESSION[SESSION_APP][$this->page_ctrl]['risiko_sendiri'])
			$this->data['risiko_sendiri'] = $_SESSION[SESSION_APP][$this->page_ctrl]['risiko_sendiri'];

		if ($this->post['act'] == "filter_sendiri") {
			$_SESSION[SESSION_APP][$this->page_ctrl]['risiko_sendiri'] = $this->post['risiko_sendiri'];
			redirect(current_url());
		}

		$this->data['ischild'] = array(1, 2);

		if ($this->data['risiko_sendiri'])
			$this->_setFilter("id_scorecard = " . $this->conn->qstr($id_scorecard));
		else {
			$this->data['ischild'] = $ret = $this->riskscorecard->GetChild($id_scorecard);
			if (($ret))
				$this->_setFilter("id_scorecard in (" . implode(",", $ret) . ")");
			else
				$this->_setFilter("id_scorecard = " . $this->conn->qstr($id_scorecard));
		}

		if (1 /*!$_SESSION[SESSION_APP]['tgl_efektif']*/)
			$_SESSION[SESSION_APP]['tgl_efektif'] = date('Y-m-d');

		if ($_SESSION[SESSION_APP]['tgl_efektif']) {

			$this->data['tgl_efektif'] = $tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

			$this->_setFilter(" str_to_date('$tgl_efektif','%Y-%m-%d') between ifnull(tgl_risiko, str_to_date('$tgl_efektif','%Y-%m-%d')) and ifnull(tgl_close-1,str_to_date('$tgl_efektif','%Y-%m-%d'))");
		}

		if ($this->data['filter_arr']['status_risiko'] == null) {
			$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']['status_risiko'] = 1;
			$this->data['filter_arr']['status_risiko'] = $_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']['status_risiko'];
		}
		$this->_setFilter('  deleted_date is null ');
		// $this->conn->debug = 1;
		$this->data['list'] = $this->_getList($page);
		// die();
		// dpr($this->data['list'], 1);
		foreach ($this->data['list']['rows'] as &$f) {
			// $f['sasaran'] = $this->data['risksasaranarr'][$f['id_sasaran']];
			// $f['sumber'] = $this->data['riskpenyebabarr'][$f['id_risk_penyebab']];
			// $f['dampak'] = $this->data['riskdampakarr'][$f['id_risk_dampak']];

			$penyebab = $this->conn->GetArray("select * from risk_penyebab b where b.deleted_date is null and exists(select 1 from risk_risiko_penyebab a where a.id_risk_penyebab = b.id_risk_penyebab and a.id_risiko = " . $f['id_risiko'] . ")");
			if ($penyebab)
				foreach ($penyebab as $k) {
					$penyebab2[$k['id_risk_penyebab']] = $k['nama'];
				}

			$dampak = $this->conn->GetArray("select * from risk_dampak b where b.deleted_date is null and exists(select 1 from risk_risiko_dampak a where a.id_risk_dampak = b.id_risk_dampak and a.id_risiko =  " . $f['id_risiko'] . ")");
			if ($dampak)
				foreach ($dampak as $g) {
					$dampak2[$g['id_risk_dampak']] = $g['nama'];
				}

			// $f['sasaran'] = $this->conn->GetOne("select nama from risk_sasaran where id_sasaran = " . $this->conn->escape($f['id_sasaran']));
			unset($f['sumber']);
			if ($penyebab2)
				$f['sumber'] = implode(', ', $penyebab2);
			unset($f['dampak']);
			if ($dampak2)
				$f['dampak'] = implode(', ', $dampak2);
			unset($penyebab);
			unset($penyebab2);
			unset($dampak);
			unset($dampak2);
		}

		$this->data['header'] = $this->Header();
		$this->data['page'] = $page;
		$param_paging = array(
			'base_url' => base_url("{$this->page_ctrl}/index/$id_scorecard"),
			'cur_page' => $page,
			'total_rows' => $this->data['list']['total'],
			'per_page' => $this->limit,
			'first_tag_open' => '<li class="page-item">',
			'first_tag_close' => '</li>',
			'last_tag_open' => '<li class="page-item">',
			'last_tag_close' => '</li>',
			'cur_tag_open' => '<li class="page-item active"><a href="#" class="page-link">',
			'cur_tag_close' => '</a></li>',
			'next_tag_open' => '<li class="page-item">',
			'next_tag_close' => '</li>',
			'prev_tag_open' => '<li class="page-item">',
			'prev_tag_close' => '</li>',
			'num_tag_open' => '<li class="page-item">',
			'num_tag_close' => '</li>',
			'anchor_class' => 'page-link',
			'attributes' => array('class' => 'page-link'),
		);
		$this->load->library('pagination');

		$paging = $this->pagination;

		$paging->initialize($param_paging);

		$this->data['paging'] = $paging->create_links();

		$this->data['limit'] = $this->limit;

		$this->data['limit_arr'] = $this->limit_arr;

		// dpr($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']['is_signifikan'],1);
		// $print = $_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']['is_signifikan'] ? "Print Signifikan" : "Print IRR";
		// $this->data['buttonMenu'] .= "<button type='button' class='btn btn-sm btn-primary' onclick=\"goPrint('')\"><i class='bi bi-printer'></i> $print</button>" .
		// 	'<script>
		// 		function goPrint(){
		//     	    // window.open("' . base_url($ci->page_ctrl . "/panelbackend/risk_risiko/go_print") . '/"+id+$("#main_form").serialize(),"_blank");
		//     	    window.open("' . base_url($ci->page_ctrl . "/panelbackend/risk_risiko/go_print/$id_scorecard") . '/?"+$("#main_form").serialize(),"_blank");
		// 		}
		// 	</script>';
		$this->View($this->viewlist);
	}

	public function go_print($id_scorecard = null)
	{
		$rutin = $this->conn->GetOne("select rutin_non_rutin from risk_scorecard where deleted_date is null and id_scorecard = " . $this->conn->escape($id_scorecard));
		// $url = base_url("/panelbackend/laporan_kertas_kerja/go_print/?act=list_search&go=&idkey=&cari_risiko=&id_unit=&id_kpi=&id_scorecard%5B$id_scorecard%5D=$id_scorecard&idrootheader%5B0%5D=on&header%5Bscorecard%5D=scorecard&header%5Bsasaran%5D=sasaran&header%5Bkpi%5D=kpi&header%5Bnama_aktifitas%5D=nama_aktifitas&header%5Brisiko%5D=risiko&header%5Btaksonomi_objective%5D=taksonomi_objective&header%5Btaksonomi_area%5D=taksonomi_area&header%5Bnama_kri%5D=nama_kri&header%5Bformula_kri%5D=formula_kri&header%5Bis_rutin%5D=is_rutin&header%5Bpenyebab%5D=penyebab&header%5Btaksonomi_area_kode%5D=taksonomi_area_kode&header%5Bdampak%5D=dampak&header%5Bpemenuhan_kewajiban%5D=pemenuhan_kewajiban&header%5Bid_aspek_lingkungan%5D=id_aspek_lingkungan&header%5Binheren_risk%5D=inheren_risk&header%5Bis_opp_inherent%5D=is_opp_inherent&header%5Binheren_kemungkinan%5D=inheren_kemungkinan&header%5Binheren_dampak%5D=inheren_dampak&header%5Blevel_risiko_inheren%5D=level_risiko_inheren&header%5Bis_signifikan_inherent%5D=is_signifikan_inherent&header%5Bdampak_kuantitatif_inheren%5D=dampak_kuantitatif_inheren&header%5Bnama_kontrol%5D=nama_kontrol&header%5Bnama_pengukuran%5D=nama_pengukuran&header%5Bcurrent_risk%5D=current_risk&header%5Bis_opp_inherent1%5D=is_opp_inherent1&header%5Bcontrol_kemungkinan_penurunan%5D=control_kemungkinan_penurunan&header%5Bcontrol_dampak_penurunan%5D=control_dampak_penurunan&header%5Blevel_risiko_paskakontrol%5D=level_risiko_paskakontrol&header%5Bis_signifikan_current%5D=is_signifikan_current&header%5Bdampak_kuantitatif_current%5D=dampak_kuantitatif_current&header%5Bmitigasi_lanjutan%5D=mitigasi_lanjutan&header%5Bnama_mitigasi_berjalan%5D=nama_mitigasi_berjalan&header%5Bwaktu_pelaksanaan%5D=waktu_pelaksanaan&header%5Bbiaya_mitigasi%5D=biaya_mitigasi&header%5Brisk_owner%5D=risk_owner&header%5Brealisasi_mitigasi%5D=realisasi_mitigasi&header%5Bprogram_kerja%5D=program_kerja&header%5Brencana%5D=rencana&header%5Brealisasi%5D=realisasi&header%5Bdevisiasi%5D=devisiasi&header%5Bsatuan_mitigasi%5D=satuan_mitigasi&header%5Bresidual_risk%5D=residual_risk&header%5Bis_opp_inherent2%5D=is_opp_inherent2&header%5Bkemungkinan_rdual%5D=kemungkinan_actual&header%5Bdampak_rdual%5D=dampak_actual&header%5Blevel_risiko_residual%5D=level_risiko_actual&header%5Bdampak_kuantitatif_residual%5D=dampak_kuantitatif_residual&header%5Bpenanggungjawab_mitigasi%5D=penanggungjawab_mitigasi&header%5Bmonitoring_mitigasi%5D=monitoring_mitigasi&header%5Bhasil_mitigasi_terhadap_sasaran%5D=hasil_mitigasi_terhadap_sasaran&header%5Bis_monitoring_rmtik%5D=is_monitoring_rmtik&header%5Bis_monitoring_p2k3%5D=is_monitoring_p2k3&header%5Bis_monitoring_fkap%5D=is_monitoring_fkap&header%5Bevaluasi_manajemen_risiko%5D=evaluasi_manajemen_risiko&header%5Bcapaian_mitigasi_evaluasi%5D=capaian_mitigasi_evaluasi&header%5Bpenyesuaian_mitigasi%5D=penyesuaian_mitigasi&header%5Bstatus_risiko%5D=status_risiko&jenis=0&tingkat=&namatempletekolom=&idtempletekolom=&judultempletekolom=");
		// $url = base_url("panelbackend/laporan_kertas_kerja/go_print/?act=list_search&go=&idkey=&cari_risiko=&id_unit=&id_kpi=&id_scorecard%5B$id_scorecard%5D=$id_scorecard&header%5Bscorecard%5D=scorecard&header%5Bis_rutin%5D=is_rutin&header%5Bpenyebab%5D=penyebab&header%5Btaksonomi_area_kode%5D=taksonomi_area_kode&header%5Bdampak%5D=dampak&header%5Bpemenuhan_kewajiban%5D=pemenuhan_kewajiban&header%5Bid_aspek_lingkungan%5D=id_aspek_lingkungan&header%5Binheren_risk%5D=inheren_risk&header%5Bis_opp_inherent%5D=is_opp_inherent&header%5Binheren_kemungkinan%5D=inheren_kemungkinan&header%5Binheren_dampak%5D=inheren_dampak&header%5Blevel_risiko_inheren%5D=level_risiko_inheren&header%5Bis_signifikan_inherent%5D=is_signifikan_inherent&header%5Bnama_kontrol%5D=nama_kontrol&header%5Bcurrent_risk%5D=current_risk&header%5Bis_opp_inherent1%5D=is_opp_inherent1&header%5Bcontrol_kemungkinan_penurunan%5D=control_kemungkinan_penurunan&header%5Bcontrol_dampak_penurunan%5D=control_dampak_penurunan&header%5Blevel_risiko_paskakontrol%5D=level_risiko_paskakontrol&header%5Bis_signifikan_current%5D=is_signifikan_current&header%5Bmitigasi_lanjutan%5D=mitigasi_lanjutan&header%5Bresidual_risk%5D=residual_risk&header%5Bis_opp_inherent2%5D=is_opp_inherent2&header%5Bkemungkinan_rdual%5D=kemungkinan_actual&header%5Bdampak_rdual%5D=dampak_actual&header%5Blevel_risiko_residual%5D=level_risiko_actual&jenis=0&tingkat=&namatempletekolom=&idtempletekolom=&judultempletekolom=");
		$url = base_url("panelbackend/laporan_kertas_kerja/go_print/?act=list_search&go=&idkey=&cari_risiko=&id_unit=&id_kpi=&id_scorecard%5B$id_scorecard%5D=$id_scorecard&header%5Bsasaran%5D=sasaran&header%5Bis_rutin%5D=is_rutin&header%5Bpenyebab%5D=penyebab&header%5Btaksonomi_area_kode%5D=taksonomi_area_kode&header%5Bdampak%5D=dampak&header%5Bpemenuhan_kewajiban%5D=pemenuhan_kewajiban&header%5Bid_aspek_lingkungan%5D=id_aspek_lingkungan&header%5Binheren_risk%5D=inheren_risk&header%5Bis_opp_inherent%5D=is_opp_inherent&header%5Binheren_kemungkinan%5D=inheren_kemungkinan&header%5Binheren_dampak%5D=inheren_dampak&header%5Blevel_risiko_inheren%5D=level_risiko_inheren&header%5Bis_signifikan_inherent%5D=is_signifikan_inherent&header%5Bnama_kontrol%5D=nama_kontrol&header%5Bcurrent_risk%5D=current_risk&header%5Bis_opp_inherent1%5D=is_opp_inherent1&header%5Bcontrol_kemungkinan_penurunan%5D=control_kemungkinan_penurunan&header%5Bcontrol_dampak_penurunan%5D=control_dampak_penurunan&header%5Blevel_risiko_paskakontrol%5D=level_risiko_paskakontrol&header%5Bis_signifikan_current%5D=is_signifikan_current&header%5Bmitigasi_lanjutan%5D=mitigasi_lanjutan&header%5Bresidual_risk%5D=residual_risk&header%5Bis_opp_inherent2%5D=is_opp_inherent2&header%5Bkemungkinan_rdual%5D=kemungkinan_actual&header%5Bdampak_rdual%5D=dampak_actual&header%5Blevel_risiko_residual%5D=level_risiko_actual&jenis=0&tingkat=&namatempletekolom=&idtempletekolom=&judultempletekolom=&is_ttd=1");
		if ($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']['is_signifikan'])
			$url = base_url("panelbackend/laporan_kertas_kerja/go_print/?act=list_search&go=&idkey=&cari_risiko=&id_unit=&id_kpi=&id_scorecard%5B$id_scorecard%5D=$id_scorecard&header%5Bscorecard%5D=scorecard&header%5Bsasaran%5D=sasaran&header%5Bpenyebab%5D=penyebab&header%5Bdampak%5D=dampak&header%5Blevel_risiko_inheren%5D=level_risiko_inheren&header%5Bnomor_mitigasi_lanjutan%5D=nomor_mitigasi_lanjutan&header%5Bmitigasi_lanjutan%5D=mitigasi_lanjutan&header%5Bprioritas_risiko%5D=prioritas_risiko&header%5Bintegrasi_internal%5D=integrasi_internal&header%5Bintegrasi_eksternal%5D=integrasi_eksternal&jenis=is_signifikan&tingkat=&namatempletekolom=&idtempletekolom=&judultempletekolom=&is_ttd=1");
		// dpr($url,1);
		if ($rutin == 'nonrutin') {
			$url = base_url("panelbackend/laporan_kertas_kerja/go_print/?act=list_search&go=&idkey=&cari_risiko=&id_unit=&id_scorecard%5B$id_scorecard%5D=$id_scorecard&header%5Bsasaran%5D=sasaran&header%5Bis_rutin%5D=is_rutin&header%5Bpenyebab%5D=penyebab&header%5Bkategori_risiko%5D=kategori_risiko&header%5Btaksonomi_area_kode%5D=taksonomi_area_kode&header%5Bid_kategori_proyek%5D=id_kategori_proyek&header%5Bdampak%5D=dampak&header%5Bpemenuhan_kewajiban%5D=pemenuhan_kewajiban&header%5Bid_aspek_lingkungan%5D=id_aspek_lingkungan&header%5Bis_opp_inherent%5D=is_opp_inherent&header%5Binheren_kemungkinan%5D=inheren_kemungkinan&header%5Binheren_dampak%5D=inheren_dampak&header%5Blevel_risiko_inheren%5D=level_risiko_inheren&header%5Bis_signifikan_inherent%5D=is_signifikan_inherent&header%5Bnama_kontrol%5D=nama_kontrol&header%5Bis_opp_inherent1%5D=is_opp_inherent1&header%5Bcontrol_kemungkinan_penurunan%5D=control_kemungkinan_penurunan&header%5Bcontrol_dampak_penurunan%5D=control_dampak_penurunan&header%5Blevel_risiko_paskakontrol%5D=level_risiko_paskakontrol&header%5Bis_signifikan_current%5D=is_signifikan_current&header%5Bmitigasi_lanjutan%5D=mitigasi_lanjutan&header%5Bis_opp_inherent2%5D=is_opp_inherent2&header%5Bkemungkinan_rdual%5D=kemungkinan_actual&header%5Bdampak_rdual%5D=dampak_actual&header%5Blevel_risiko_residual%5D=level_risiko_actual&jenis=0&tingkat=&namatempletekolom=&idtempletekolom=&judultempletekolom=&is_ttd=1&rutin_non_rutin=nonrutin");
			if ($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']['is_signifikan'])
				$url = base_url("panelbackend/laporan_kertas_kerja/go_print/?act=list_search&go=&idkey=&cari_risiko=&id_unit=&id_kpi=&id_scorecard%5B$id_scorecard%5D=$id_scorecard&header%5Bscorecard%5D=scorecard&header%5Bsasaran%5D=sasaran&header%5Bpenyebab%5D=penyebab&header%5Bdampak%5D=dampak&header%5Blevel_risiko_inheren%5D=level_risiko_inheren&header%5Bnomor_mitigasi_lanjutan%5D=nomor_mitigasi_lanjutan&header%5Bmitigasi_lanjutan%5D=mitigasi_lanjutan&header%5Bprioritas_risiko%5D=prioritas_risiko&header%5Bintegrasi_internal%5D=integrasi_internal&header%5Bintegrasi_eksternal%5D=integrasi_eksternal&jenis=is_signifikan&tingkat=&namatempletekolom=&idtempletekolom=&judultempletekolom=&is_ttd=1");
		}
		redirect($url);
	}

	public function go_print_bak($id_scorecard = null)
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout2";
		$this->viewprint = "panelbackend/risk_risikoprint";
		$this->data['no_header'] = true;
		$this->data['width_page'] = "1200px";
		$this->limit = -1;

		$this->_beforeDetail($id_scorecard);

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['risiko_sendiri'])
			$this->data['risiko_sendiri'] = $_SESSION[SESSION_APP][$this->page_ctrl]['risiko_sendiri'];

		if ($this->post['act'] == "filter_sendiri") {
			$_SESSION[SESSION_APP][$this->page_ctrl]['risiko_sendiri'] = $this->post['risiko_sendiri'];
			redirect(current_url());
		}

		$this->data['ischild'] = array(1, 2);

		if ($this->data['risiko_sendiri'])
			$this->_setFilter("id_scorecard = " . $this->conn->qstr($id_scorecard));
		else {
			$this->data['ischild'] = $ret = $this->riskscorecard->GetChild($id_scorecard);
			if (($ret))
				$this->_setFilter("id_scorecard in (" . implode(",", $ret) . ")");
			else
				$this->_setFilter("id_scorecard = " . $this->conn->qstr($id_scorecard));
		}

		if (1 /*!$_SESSION[SESSION_APP]['tgl_efektif']*/)
			$_SESSION[SESSION_APP]['tgl_efektif'] = date('Y-m-d');

		if ($_SESSION[SESSION_APP]['tgl_efektif']) {

			$this->data['tgl_efektif'] = $tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

			$this->_setFilter(" str_to_date('$tgl_efektif','%Y-%m-%d') between ifnull(tgl_risiko, str_to_date('$tgl_efektif','%Y-%m-%d')) and ifnull(tgl_close-1,str_to_date('$tgl_efektif','%Y-%m-%d'))");
		}

		$temp = $this->_getList();

		foreach ($temp['rows'] as $r) {
			$rwc = $this->conn->GetArray("select * 
			from risk_control 
			where deleted_date is null and id_risiko = " . $this->conn->escape($r['id_risiko']));
			$rwm = $this->conn->GetArray("select * 
			from risk_mitigasi 
			where deleted_date is null and is_control <> '1' and id_risiko = " . $this->conn->escape($r['id_risiko']));

			$c = count($rwc);
			if ($c < count($rwm)) {
				$c = count($rwm);
			}

			for ($i = 0; $i < $c; $i++) {
				$rw = array(
					"i" => $i,
					"c" => $c,
					"nama_risiko" => $r['nama'],
					"nama_control" => $rwc[$i]['nama'],
					"nama_mitigasi" => $rwm[$i]['nama'],
				);
				$this->data['rows'][] = $rw;
			}
		}

		$r = $this->conn->GetRow("select * from public_sys_setting where deleted_date is null and nama = 'KABIDMRA'");
		$nama = $this->conn->GetOne("select nama_lengkap from mt_sdm_pegawai where deleted_date is null and position_id = " . $this->conn->escape($r['isi']));

		if (!$nama)
			$nama = "MOCHA%mAD AGUSTIAN";
		if (!$r['keterangan'])
			$r['keterangan'] = "Kepala Bidang Manajemen Risiko & Asuransi";

		$this->data['kabidmra']['nama'] = strtoupper($nama);
		$this->data['kabidmra']['jabatan'] = strtoupper($r['keterangan']);

		$r = $this->conn->GetRow("select * from public_sys_setting where deleted_date is null and nama = 'KSMRK'");
		$nama = $this->conn->GetOne("select nama_lengkap from mt_sdm_pegawai where deleted_date is null and position_id = " . $this->conn->escape($r['isi']));

		if (!$nama)
			$nama = "PURWONO JATI AGUNG";
		if (!$r['keterangan'])
			$r['keterangan'] = "Kepala Satuan Manajemen Risiko & Kepatuhan";

		$this->data['ksmrk']['nama'] = strtoupper($nama);
		$this->data['ksmrk']['jabatan'] = strtoupper($r['keterangan']);

		$r = $this->conn->GetRow("select * from public_sys_setting where deleted_date is null and nama = 'KABIDKEP'");
		$nama = $this->conn->GetOne("select nama_lengkap from mt_sdm_pegawai where deleted_date is null and position_id = " . $this->conn->escape($r['isi']));

		if (!$nama)
			$nama = "DYAH MAYA SAFITHRI";
		if (!$r['keterangan'])
			$r['keterangan'] = "Kepala Bidang Kepatuhan";

		$this->data['kabidkep']['nama'] = strtoupper($nama);
		$this->data['kabidkep']['jabatan'] = strtoupper($r['keterangan']);

		$this->View($this->viewprint);
	}

	public function Add($id_scorecard = null)
	{
		$this->Edit($id_scorecard);
	}

	protected function _onDetail($id = null, &$record = array())
	{
		#aturan agregasi
		if (in_array($this->data['id_tingkat_agregasi_risiko_parent'], array("1", "2", "3", "4"))) {


			if ($_SESSION[SESSION_APP]['tgl_efektif']) {
				$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
			} else {
				$tgl_efektif = $this->data['tgl_efektif'];
			}

			$filter = "'$tgl_efektif' between coalesce(tgl_risiko, '$tgl_efektif') and coalesce(tgl_close - interval '1' day,'$tgl_efektif')";

			$filter1 = "";
			if ($this->data['id_tingkat_agregasi_risiko_parent'] == 2)
				$filter1 .= " and rs.id_unit = " . $this->conn->escape($this->data['rowheader']['id_unit']);
			// else if ($this->data['id_tingkat_agregasi_risiko_parent'] == 3)
			// 	// $filter1 .= " and rs.id_unit = '01'";

			$filter .= " and exists (
				select 1 from risk_scorecard rs 
				where rs.deleted_date is null and r.id_scorecard = rs.id_scorecard 
				and  '$tgl_efektif' between coalesce(rs.tgl_mulai_efektif, '$tgl_efektif') and coalesce(rs.tgl_akhir_efektif - interval '1' day,'$tgl_efektif')
				$filter1
				and id_tingkat_agregasi_risiko = "
				. ($this->data['id_tingkat_agregasi_risiko_parent'])
				. ")";

			$this->data['risikoindukarr'] = array("" => "") + $this->conn->GetList("select 
			  id_risiko as idkey, nama as val 
			  from risk_risiko r where r.deleted_date is null and $filter");
		}



		if (in_array($this->data['id_tingkat_agregasi_risiko_child'], array("1", "2", "3", "4"))) {

			if ($_SESSION[SESSION_APP]['tgl_efektif']) {
				$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
			} else {
				$tgl_efektif = $this->data['tgl_efektif'];
			}

			$filter = "'$tgl_efektif' between coalesce(tgl_risiko, '$tgl_efektif') and coalesce(tgl_close - interval '1' day,'$tgl_efektif')";

			$filter1 = "";
			if ($this->data['id_tingkat_agregasi_risiko_child'] == 3)
				$filter1 = " and rs.id_unit = " . $this->conn->escape($this->data['rowheader']['id_unit']);

			$filter .= " and exists (
				select 1 from risk_scorecard rs 
				where rs.deleted_date is null and r.id_scorecard = rs.id_scorecard 
				and  '$tgl_efektif' between coalesce(rs.tgl_mulai_efektif, '$tgl_efektif') and coalesce(rs.tgl_akhir_efektif - interval '1' day,'$tgl_efektif')
				$filter1
				and id_tingkat_agregasi_risiko = "
				. ($this->data['id_tingkat_agregasi_risiko_child'])
				. ")";

			$this->data['risikobawaharr'] = array("" => "") + $this->conn->GetList("select 
			  id_risiko as idkey, nama as val 
			  from risk_risiko r where r.deleted_date is null and $filter");
		}

		$id_unit = $this->data['rowheader']['id_unit'];
		$id_scorecard = $this->data['rowheader']['id_scorecard'];
		$id_kpi = $this->data['row']['id_kpi'];
		list($tahun) = explode("-", $this->data['row']['tgl_risiko']);
		if (!$tahun)
			$tahun = date("Y");

		$this->data['kpiarr'] = $this->kpi->GetCombo($id_unit, $tahun, $id_kpi);
		$this->data['is_regulasi'] = $this->conn->GetOne("select is_regulasi from mt_risk_taksonomi where deleted_date is null and id_taksonomi = " . $this->conn->escape($this->data['row']['id_taksonomi']));

		if (!$this->data['row']['kri']) {
			$rows = $this->conn->GetArray("select * 
				from risk_kri
				where deleted_date is null and id_risiko = " . $this->conn->escape($id));

			$this->data['row']['kri'] = array();
			foreach ($rows as $r) {
				$this->data['row']['kri'][] = $r;
			}
		}
		$id_sasaran = $this->data['row']['id_sasaran'];

		if (is_numeric($id_sasaran)) {
			$this->data['row']['kpi'] = $this->conn->GetArray("select 
			k.* 
			from risk_sasaran_kpi s join risk_kpi k on s.id_kpi = k.id_kpi 
			where s.deleted_date is null and id_sasaran = " . $this->conn->escape($id_sasaran));
		}
		$this->_accessTask($this->data['rowheader'], $this->data['row'], $this->data['edited']);
	}

	protected function _beforeUpdate($record = array(), $id = null)
	{
		$this->data['rowold'] = $this->model->GetByPk($id);
		return true;
	}

	protected function _beforeInsert($record = array())
	{


		return true;
	}
	public function save_dampak($id)
	{
		if ($this->post['dampak']) {
			$ret = $this->conn->Execute("update risk_risiko_dampak set deleted_date = now() where id_risiko = " . $this->conn->escape($id));
			unset($this->data['row']['dampak']);
			foreach ($this->post['dampak'] as $k) {
				if (!$ret)
					break;
				if (is_numeric($k['id_risk_dampak'])) {
					$id_risk_dampak = $this->conn->GetOne("select id_risk_dampak from risk_dampak where deleted_date is null and id_risk_dampak = " . $this->conn->escape($k['id_risk_dampak']));
				}

				if (!$id_risk_dampak) {
					$record = array(
						"nama" => $k['id_risk_dampak'],
					);
					$ret = $this->conn->goInsert('risk_dampak', $record);
					if ($ret) {
						$id_risk_dampak = $this->conn->GetOne("select max(id_risk_dampak) from risk_dampak where deleted_date is null ");
					}
					unset($record);
				}
				if ($id_risk_dampak) {
					$this->data['row']['dampak'][$id_risk_dampak]['id_risk_dampak'] = $id_risk_dampak;
					$record = array(
						"id_risiko" => $id,
						"id_risk_dampak" => $id_risk_dampak,
					);
					$ret = $this->conn->goInsert("risk_risiko_dampak", $record);
					unset($record);
					unset($id_risk_dampak);
				}
			}
		}
		return $ret;
		// redirect(current_url());
	}

	public function save_penyebab($id)
	{
		if ($this->post['penyebab']) {
			$ret = $this->conn->Execute("update risk_risiko_penyebab set deleted_date = now()  where id_risiko = " . $this->conn->escape($id));
			unset($this->data['row']['penyebab']);
			foreach ($this->post['penyebab'] as $k) {
				if (!$ret)
					break;
				if (is_numeric($k['id_risk_penyebab'])) {
					$id_risk_penyebab = $this->conn->GetOne("select id_risk_penyebab from risk_penyebab where deleted_date is null and id_risk_penyebab = " . $this->conn->escape($k['id_risk_penyebab']));
				}

				if (!$id_risk_penyebab) {
					$record = array(
						"nama" => $k['id_risk_penyebab'],
					);
					$ret = $this->conn->goInsert('risk_penyebab', $record);
					if ($ret) {
						$id_risk_penyebab = $this->conn->GetOne("select max(id_risk_penyebab) from risk_penyebab where deleted_date is null");
					}
					unset($record);
				}
				if ($id_risk_penyebab) {
					$this->data['row']['penyebab'][$id_risk_penyebab]['id_risk_penyebab'] = $id_risk_penyebab;
					$record = array(
						"id_risiko" => $id,
						"id_risk_penyebab" => $id_risk_penyebab,
					);
					$ret = $this->conn->goInsert("risk_risiko_penyebab", $record);
					// dpr($ret);
					unset($record);
					unset($id_risk_penyebab);
				}
			}
		}
		return $ret;
		// redirect(current_url());
	}

	public function save_proyek_terkait($id)
	{
		$ret = true;
		/* proyek terkait list
		if ($this->post['id_proyek_terkait']) {
			$ret = $this->conn->Execute("delete from risk_risiko_proyek_terkait where id_risiko = " . $this->conn->escape($id));
			foreach ($this->post['id_proyek_terkait'] as $g) {
				$rec = array(
					"id_risiko" => $id,
					"id_scorecard" => $g,
				);
				$ret = $this->conn->goInsert("risk_risiko_proyek_terkait", $rec);
			}
		}
		*/
		return $ret;
	}

	public function Edit($id_scorecard = null, $id = null)
	{
		// dpr($this->post['selera_kemungkinan'], 1);
		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}
		$this->_beforeDetail($id_scorecard, $id);
		$this->data['row'] = $this->data['rowheader1'] = $this->model->GetByPk($id);
		// dpr($this->data['row'], 1);

		if ($this->access_role['edit'] && $this->data['row']['is_lock'] == '1') {
			$this->data['editedkri'] = true;
			if ($this->post['act'] == 'save_kri') {
				$ret = $this->_delSertKri($id);
				if ($ret)
					SetFlash("suc_msg", "Perubahan berhasil");
				else
					SetFlash("err_msg", "Perubahan gagal");

				redirect("$this->page_ctrl/detail/$id_scorecard/$id");
			}
		}
		if ($this->post['act'] == 'set_dampak') {
			$this->save_dampak($id);
		}

		if (strstr($this->post['act'], 'remove_dampak')) {
			// $this->conn->Execute("delete from risk_risiko_dampak where id_risiko = " . $this->conn->escape($id) . " and id_risk_dampak = " . $this->conn->escape(str_replace('remove_dampak_', '', $this->post['act'])));
			// redirect(current_url());
		}

		if ($this->post['act'] == 'set_penyebab') {
			$this->save_penyebab($id);
		}

		if (strstr($this->post['act'], 'remove_penyebab')) {
			// $this->conn->Execute("delete from risk_risiko_penyebab where id_risiko = " . $this->conn->escape($id) . " and id_risk_penyebab = " . $this->conn->escape(str_replace('remove_penyebab_', '', $this->post['act'])));
			// redirect(current_url());
		}

		if (!$this->data['row'] && $id)
			$this->NoData();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");
		$kode_aktifitas = $this->data['row']['id_aktifitas'];
		if ($this->post && $this->post['act'] <> 'change') {
			// foreach($this->post['id_risk_penyebab'] as $h => $g){
			// 	if($g['nama']){
			// 		dpr($h);
			// 		// unset($this->post['id_risk_penyebab'][$h]);
			// 	}
			// }
			// dpr($this->post);

			if (!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id);

			// $penyebab = $this->post['id_risk_penyebab'];
			// unset($this->post['penyebab']);
			// if ($penyebab)
			// 	foreach ($penyebab as $f) {
			// 		if($f['nama'])
			// 		$this->post['penyebab'][$f['nama']]['id_risk_penyebab'] = $f['nama'];
			// 	}
			// $dampak = $this->post['id_risk_dampak'];
			// unset($this->post['dampak']);
			// if ($dampak)
			// 	foreach ($dampak as $f) {
			// 		if($f['nama'])
			// 		$this->post['dampak'][$f['nama']]['id_risk_dampak'] = $f['nama'];
			// 	}
			$this->data['row'] = array_merge($this->data['row'], $record);
			$this->data['row'] = array_merge($this->data['row'], $this->post);
			if (!$this->data['mtaktifitasarr'][$this->data['row']['id_aktifitas']])
				$this->data['mtaktifitasarr'][$this->data['row']['id_aktifitas']] = $this->data['row']['id_aktifitas'];

			// dpr($record,1);
			// dpr($this->data['row'],1);
		}

		if ($this->post['id_scorecard'] && $this->data['scorecardarr'][$this->post['id_scorecard']]) {
			$id_scorecard = $this->post['id_scorecard'];
			$record['id_jabatan'] = $_SESSION[SESSION_APP]['id_jabatan'];
		}

		if (!$id or $kode_aktifitas <> $this->data['row']['kode_aktifitas']) {
			$this->data['row']['nomor'] = $this->data['no_risiko'] = $this->model->getNomorRisiko(
				$this->data['rowheader']['id_unit'],
				$this->data['row']['id_taksonomi_area'],
				$this->data['row']['id_kpi'],
				$this->data['row']['tgl_risiko'],
				false
			);
		}

		$this->_onDetail($id, $record);

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##)
		if ($this->post['act'] === 'save' or $this->post['act'] == 'save_rekomendasi' or $this->post['act'] == 'save_review') {
			// dpr($this->post['selera_dampak'], 1);

			// dpr($this->data['rowheader1'], 1);
			// $this->conn->debug = 1;
			if ($this->data['row']['is_lock'] == '2' && $this->data['row']['review_is_verified'] <> '2' && $this->data['row']['rekomendasi_is_verified'] <> '2')
				$record['is_lock'] = 1;

			$record['id_scorecard'] = $id_scorecard;

			if (!$id)
				$record['nomor'] = $record['nomor_asli'] = $this->data['no_risiko'];

			if (!$record['tgl_risiko'] && !$id)
				$record['tgl_risiko'] = date('Y-m-d');


			if ($this->access_role['view_all'] && $id) {

				list($tgl, $bulan, $tahun) = explode("-", $record['tgl_risiko']);
				list($tgl, $bulan, $tahun1) = explode("-", $this->data['rowheader1']['tgl_risiko']);

				$id_risiko_sebelum = $this->data['rowheader1']['id_risiko_sebelum'];

				if ($tahun <> $tahun1 && $id_risiko_sebelum) {
					$nomor_asli = $this->conn->GetOne("select nomor_asli 
						from risk_risiko
						where deleted_date is null and date_format(tgl_risiko, '%Y') = " . $this->conn->escape($tahun) . "
						and id_risiko = " . $this->conn->escape($id_risiko_sebelum));

					if ($nomor_asli) {
						$record['nomor_asli'] = $nomor_asli;
					}
				}
			}

			# master sasaran
			// if (is_numeric($record['id_sasaran'])) {
			// 	$id_sasaran = $this->conn->GetOne("select id_sasaran from risk_sasaran where id_sasaran = " . $this->conn->escape($this->post['id_sasaran']));
			// }
			// if (!$id_sasaran) {
			// 	$rec = array(
			// 		'nama' => $record['id_sasaran'],
			// 	);
			// 	$red = $this->risksasaran->Insert($rec);
			// 	if ($red['success']) {
			// 		$record['id_sasaran'] = $this->data['row']['id_sasaran'] = $red['data']['id_sasaran'];
			// 	}
			// }

			if ($this->data['row']['risikobawah']) {
				$id_risiko_saatini = $this->data['row'][$this->pk];
				$id_child = $this->data['row']['risikobawah'];
				foreach ($id_child as $ic) {
					// dpr($ic, 1);
					$rec = array(
						'id_risiko_parent' => $id_risiko_saatini,
					);

					if ($ic)
						$ret = $this->model->Update($rec, 'id_risiko = ' . $ic);
				}
			}
			// dpr($ret, 1);



			# master penyebab
			if (is_numeric($record['id_risk_penyebab'])) {
				$id_risk_penyebab = $this->conn->GetOne("select id_risk_penyebab from risk_penyebab where deleted_date is null and id_risk_penyebab = " . $this->conn->escape($this->post['id_risk_penyebab']));
			}
			if (!$id_risk_penyebab) {
				$rec = array(
					'nama' => $record['id_risk_penyebab'],
				);
				$red = $this->riskpenyebab->Insert($rec);
				// dpr($ret,1);
				if ($red['success']) {
					unset($record['penyebab']);
					$record['id_risk_penyebab'] = $this->data['row']['id_risk_penyebab'] = $red['data']['id_risk_penyebab'];
				}
			}

			# master sasatan
			if (is_numeric($record['id_risk_dampak'])) {
				$id_risk_dampak = $this->conn->GetOne("select id_risk_dampak from risk_dampak where deleted_date is null and id_risk_dampak = " . $this->conn->escape($this->post['id_risk_dampak']));
			}
			if (!$id_risk_dampak) {
				$rec = array(
					'nama' => $record['id_risk_dampak'],
				);
				// $this->conn->debug = 1;
				$red = $this->riskdampak->Insert($rec);
				if ($red['success']) {
					$record['id_risk_dampak'] = $this->data['row']['id_risk_dampak'] = $red['data']['id_risk_dampak'];
				}
			}

			$this->_isValid($record, true);

			$this->_beforeEdit($record, $id);

			$this->_setLogRecord($record, $id);

			$rederec = false;
			$this->model->conn->StartTrans();
			if ($this->data['row'][$this->pk]) {

				$return = $this->_beforeUpdate($record, $id);

				if ($return) {
					$return = $this->model->Update($record, "$this->pk = " . $this->conn->qstr($id));
				}

				if ($return['success']) {

					$row = $this->data['row'];
					unset($row['kpi']);
					unset($row['kpi_kegiatan']);

					$this->log("mengubah", $row);

					$return1 = $this->_afterUpdate($id);

					if (!$return1) {
						$return = false;
					}
				}
			} else {

				$is_insert = true;


				$return = $this->_beforeInsert($record);
				if ($return) {
					$return = $this->model->Insert($record);
					$id = $return['data'][$this->pk];
					$this->data['row'][$this->pk] = $id;
				}

				if ($return['success']) {

					$this->log("menambah", $record);

					$return1 = $this->_afterInsert($id);

					if (!$return1) {
						$return = false;
					}
					$rederec = true;
				}
			}
			// dpr($return, 1);

			if ($return['success']) {
				$this->model->conn->trans_commit();
				// if ($is_insert)
				$this->backtodraft($id);

				$this->_afterEditSucceed($id);

				SetFlash('suc_msg', $return['success']);
				if ($rederec)
					redirect(base_url("panelbackend/risk_analisis/edit/$id"));
				else
					redirect("$this->page_ctrl/detail/$id_scorecard/$id");
			} else {
				$this->model->conn->trans_rollback();
				$this->data['row'] = array_merge($this->data['row'], $record);
				$this->data['row'] = array_merge($this->data['row'], $this->post);

				$this->_afterEditFailed($id);

				$this->data['err_msg'] .= " Data gagal disimpan.";
			}
		}

		$this->_afterDetail($id);
		// dpr($this->data['rowheader1'], 1);

		$this->View($this->viewdetail);
	}

	public function Detail($id_scorecard = null, $id = null)
	{
		$this->_beforeDetail($id_scorecard, $id);

		$this->data['rowheader1'] = $this->data['row'] = $this->model->GetByPk($id);
		// dpr($this->data['rowheader1']['id_risiko_parent'], 1);

		$this->_onDetail($id, $record);

		if (!$this->data['row'])
			$this->NoData();

		$this->_afterDetail($id);

		// $this->data['buttonMenu'] .= "<button type='button' class='btn btn-sm btn-primary' onclick=\"goPrint('')\"><i class='bi bi-printer'></i> Print</button>" .
		// 	'<script>
		// 		function goPrint(){
		//     	    window.open("' . base_url($ci->page_ctrl . "/panelbackend/risk_risiko/go_print_nonrutin/$id") . '/?"+$("#main_form").serialize(),"_blank");
		// 		}
		// 	</script>';
		$this->View($this->viewdetail);
	}

	public function go_print_nonrutin($id)
	{
		$this->data['page_title'] = '';
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout5";
		$this->data['no_header'] = true;

		$this->data['row'] = $this->model->GetByPk($id);
		// $this->conn->debug=1;
		if ($this->data['row']['id_kategori_proyek'])
			$this->data['row']['area_dampak'] = $this->conn->GetOne("select kode+' '+nama from mt_risk_taksonomi_area where deleted_date is null and id_taksonomi_area = " . $this->data['row']['id_kategori_proyek']);
		$this->data['row']['mitigasi'] = $this->conn->GetArray("select * from risk_mitigasi a where a.deleted_date is null and exists(select 1 from risk_mitigasi_risiko b where a.id_mitigasi = b.id_mitigasi and b.id_risiko = " . $this->conn->escape($id) . ")");
		$this->data['row']['control'] = $this->conn->GetList("select id_control idkey, nama val from risk_control a where a.deleted_date is null and exists(select 1 from risk_control_risiko b where a.id_control = b.id_control and b.id_risiko = " . $this->conn->escape($id) . ")");


		$this->data['rowheader']  = $this->riskscorecard->GetByPk($this->data['row']['id_scorecard']);
		if ($this->data['rowheader']['id_sasaran_proyek'])
			$this->data['rowheader']['sasaran'] = $this->conn->GetOne("select nama from risk_sasaran where deleted_date is null and id_sasaran = " . $this->conn->escape($this->data['rowheader']['id_sasaran_proyek']));
		$this->data['rowheader']['risk_owner'] = $this->conn->GetOne("select nama from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($this->data['rowheader']['owner']));

		// dpr($this->data['row'],1);
		// dpr($this->data['rowheader'],1);


		$this->_afterDetail($id);

		$this->viewprint = "panelbackend/print_nonrutin";
		$this->View($this->viewprint);
	}

	public function Delete($id_scorecard = null, $id = null)
	{

		// $this->conn->debug = 1;
		$this->model->conn->StartTrans();

		$this->_beforeDetail($id_scorecard, $id);

		$this->data['rowheader1'] = $this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$return = $this->_beforeDelete($id);

		if ($return) {
			$return = $this->model->delete("$this->pk = " . $this->conn->qstr($id));
		}

		if ($return) {
			$return1 = $this->_afterDelete($id);
			if (!$return1)
				$return = false;
		}

		$this->model->conn->CompleteTrans();

		// dpr($return,1);
		if ($return) {

			$this->log("menghapus", $this->data['row']);

			SetFlash('suc_msg', $return['success']);
			redirect("$this->page_ctrl/index/$id_scorecard");
		} else {
			SetFlash('err_msg', "Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id_scorecard/$id");
		}
	}

	protected function _beforeDetail($id = null, $id_risiko = null)
	{
		if (!$id)
			redirect('panelbackend/risk_scorecard');

		

		if (is_numeric($this->get['is_ttd'])) {
			// $data = $this->conn->GetRow("select * from risk_scorecard where id_scorecard = " . $this->conn->escape($id));
			$user_penyetuju = $this->conn->GetRow(
				"
			select 
			nama_user nama,
			nama_jabatan_user jabatan
			from risk_scorecard 
			where 
				deleted_date is null
				and id_scorecard = " . $this->conn->escape($id) . "
				and id_user = " . $this->conn->escape($this->get['is_ttd'])
			);
			$owner_penyetuju = $this->conn->GetRow(
				"
			select 
			nama_owner nama,
			nama_jabatan_owner jabatan
			from risk_scorecard 
			where 
				deleted_date is null
				and id_scorecard = " . $this->conn->escape($id) . "
				and id_owner = " . $this->conn->escape($this->get['is_ttd'])
			);
			$upmr_penyetuju = $this->conn->GetRow(
				"
			select 
			nama_upmr nama,
			nama_jabatan_upmr jabatan
			from risk_scorecard 
			where 
				deleted_date is null
				and id_scorecard = " . $this->conn->escape($id) . "
				and id_upmr = " . $this->conn->escape($this->get['is_ttd'])
			);
			if ($user_penyetuju)
				$this->data['menyetujui'] = $user_penyetuju;
			if ($owner_penyetuju)
				$this->data['menyetujui'] = $owner_penyetuju;
			if ($upmr_penyetuju)
				$this->data['menyetujui'] = $upmr_penyetuju;
			// if ($data['id_status_pengajuan'] !== 1 || $data['id_status_pengajuan'] !== 6) {
			// 	$this->data['menyetujui'] = $this->conn->GetRow("
			// 	select a.name nama, c.nama jabatan
			// 	from public_sys_user a
			// 	    join public_sys_user_group b on b.user_id = a.user_id
			// 	    join mt_sdm_jabatan c on c.id_jabatan = b.id_jabatan
			// 	where b.group_id = " . $this->conn->escape($this->get['is_ttd']) . " and 
			// 	    c.id_unit = 
			// 	        (select id_unit
			// 	         from risk_scorecard
			// 	         where id_scorecard = " . $this->conn->escape($id) . ")");
			// }
			if ($this->data['menyetujui'])
				$this->data['menyetujui']['date'] = date('H:i / d-m-Y ', strtotime($this->conn->GetOne("select created_date from risk_scorecard_files where deleted_date is null and id_scorecard = " . $this->conn->escape($id) . " and jenis = " . $this->conn->escape($this->get['is_ttd']))));
		}
		#mengambil dari model karena sudah difilter sesuai akses
		$this->data['rowheader']  = $this->riskscorecard->GetByPk($id);
		if (!$this->data['rowheader'])
			$this->NoData();

		$owner = $this->data['rowheader']['owner'];

		$this->data['kegiatanarr'] = ["" => ""] + $this->conn->GetList("select id_kegiatan as idkey, 
		nama as val from risk_kegiatan 
		where deleted_date is null and id_unit = " . $this->conn->escape($this->data['rowheader']['id_unit']));


		if ($owner) {
			$this->data['ownerarr'][$owner] = $this->conn->GetOne("select concat(nama,' (',coalesce(id_unit,''),')') from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($owner));

			// $bawahanarr = jabatan_bawahan($this->data['rowheader']['owner'], $this->data['rowheader']['id_unit']);

			// $addfilter = "";

			// if (count($bawahanarr) <= 100 && !empty($bawahanarr)) {
			// 	$addfilter = " and id_jabatan in (" . implode(", ", $bawahanarr) . ")";
			// }

			// $this->data['pejabatarr'] = array('' => '') + $this->conn->GetList("select 
			// id_jabatan as idkey, 
			// concat(nama,' (',coalesce(id_unit,''),')') as val
			// from mt_sdm_jabatan a
			// where id_unit = " . $this->conn->escape($this->data['rowheader']['id_unit']) . $addfilter);

			// $this->data['pejabatarr'][$owner] = $this->data['ownerarr'][$owner];



			// $this->load->model("Risk_kegiatanModel", 'kegiatan');

			// if ($this->post['id_sasaran']) {
			// 	if (gettype($this->post['id_sasaran']) !== 'string')
			// 		$id_sasaran = $this->conn->GetOne("select id_kegiatan from risk_kegiatan where id_sasaran = " . $this->conn->escape($this->post['id_sasaran']));
			// 	// $id_sasaran = $this->post['id_sasaran'];
			// } elseif ($id_risiko)
			// 	$id_sasaran = $this->conn->GetOne("select id_sasaran from risk_risiko where id_risiko = " . $this->conn->escape($id_risiko));

			// $this->data['mtkegiatanarr'] = $this->kegiatan->GetCombo($id_sasaran);

			// // $this->load->model("Risk_sasaranModel", "msasaran");

			// // $this->data['sasaranarr'] = $this->msasaran->GetCombo($owner);

			// // $this->data['sasaranarr'][$id_sasaran] = $this->msasaran->GetNama($id_sasaran);
		}

		$this->data['add_param'] .= $id;

		$this->data['files'] = array();
		$rows = $this->conn->GetArray("select id_scorecard_files as id, client_name as nama from risk_scorecard_files where deleted_date is null and id_scorecard = " . $this->conn->escape($id));
		foreach ($rows as $r) {
			$this->data['files']['name'][] = $r['nama'];
			$this->data['files']['id'][] = $r['id'];
		}
		$this->data['scorecardarr'] = $this->riskscorecard->GetComboChild($id);

		$id_tingkat_agregasi_risiko = $this->data['rowheader']["id_tingkat_agregasi_risiko"];

		$row = $this->conn->GetRow("select 
		* 
		from mt_tingkat_agregasi_risiko 
		where deleted_date is null and id_tingkat_agregasi_risiko = " . $this->conn->escape($id_tingkat_agregasi_risiko));

		$this->data['id_tingkat_agregasi_risiko_parent'] = $row['id_tingkat_agregasi_risiko_parent'];
		if ($this->data['id_tingkat_agregasi_risiko_parent'])
			$this->data['nama_tingkat_agregasi_risiko_parent'] = $this->conn->GetOne("select nama from mt_tingkat_agregasi_risiko 
			where deleted_date is null and id_tingkat_agregasi_risiko = " . $this->conn->escape($this->data['id_tingkat_agregasi_risiko_parent']));

		$row = $this->conn->GetRow("select 
		* 
		from mt_tingkat_agregasi_risiko 
		where deleted_date is null and id_tingkat_agregasi_risiko_parent = " . $this->conn->escape($id_tingkat_agregasi_risiko));
		$this->data['id_tingkat_agregasi_risiko_child'] = $row['id_tingkat_agregasi_risiko'];
		$this->data['nama_tingkat_agregasi_risiko_child'] = $row['nama'];

		$this->_validAccessTask($this->data['rowheader'], $this->data['rowheader1'], $this->data['edited']);
		$this->_getListTask($this->data['rowheader']);
	}

	protected function _beforeEdit(&$record = array(), $id)
	{
		$this->_validAccessTask($this->data['rowheader'], $this->data['rowheader1'], $this->data['edited']);

		$this->isLock();

		return true;
	}

	protected function _beforeDelete($id = null, $r = null)
	{
		$this->_validAccessTask($this->data['rowheader'], $this->data['rowheader1'], $this->data['edited']);

		$this->isLock();

		// $this->conn->debug = 1;

		if ($this->access_role['delete']) {

			$this->conn->Execute("update risk_risiko set id_risiko_parent = null where id_risiko_parent = " . $this->conn->escape($id));

			$rows = $this->conn->GetRows("select * from risk_risiko_files where deleted_date is null and id_risiko = " . $this->conn->escape($id));
			foreach ($rows as $r) {
				$full_path = $this->data['configfile']['upload_path'] . $r['file_name'];
				unlink($full_path);

				$this->conn->Execute("update risk_risiko_files set deleted_date = now() where id_risiko_files = " . $this->conn->escape($r['id_risiko_files']));
			}

			$this->conn->Execute("update risk_risiko_history set deleted_date = now() where id_risiko = " . $this->conn->escape($id));

			$this->conn->Execute("update risk_var set deleted_date = now() where id_risiko = " . $this->conn->escape($id));

			$this->conn->Execute("update risk_task set deleted_date = now() where id_risiko = " . $this->conn->escape($id));

			$this->conn->Execute("update risk_review set deleted_date = now() where id_risiko = " . $this->conn->escape($id));

			$rows = $this->conn->GetRows("select id_mitigasi from risk_mitigasi_risiko where deleted_date is null and id_risiko = " . $this->conn->escape($id));
			foreach ($rows as $r) {
				$this->conn->Execute("update risk_task set deleted_date = now() where id_mitigasi = " . $this->conn->escape($r['id_mitigasi']));
				$this->conn->Execute("update risk_control set deleted_date = now() where ID_MITIGASI_SUMBER = " . $this->conn->escape($r['id_mitigasi']));
				$this->conn->Execute("update risk_mitigasi_files set deleted_date = now() where id_mitigasi = " . $this->conn->escape($r['id_mitigasi']));
			}

			$this->conn->Execute("update risk_mitigasi_risiko set deleted_date = now() where id_risiko = " . $this->conn->escape($id));

			$rows = $this->conn->GetRows("select id_control from risk_control_risiko where deleted_date is null and id_risiko = " . $this->conn->escape($id));

			foreach ($rows as $r) {
				$this->conn->Execute("update risk_control_efektifitas_files set deleted_date = now() where id_control = " . $this->conn->escape($r['id_control']));
				$this->conn->Execute("update risk_control_efektifitas set deleted_date = now() where id_control = " . $this->conn->escape($r['id_control']));
			}

			$this->conn->Execute("update risk_control_risiko set deleted_date = now() where id_risiko = " . $this->conn->escape($id));
			$this->conn->Execute("update risk_log set deleted_date = now() where id_risiko = " . $this->conn->escape($id));

			$rows = $this->conn->GetRows("select id_risiko_current from risk_risiko_current where deleted_date is null and id_risiko = " . $this->conn->escape($id));
			foreach ($rows as $r) {
				$this->conn->Execute("update risk_risiko_current_fr_d set deleted_date = now()  where id_risiko_current = " . $this->conn->escape($r['id_risiko_current']));
				$this->conn->Execute("update risk_risiko_current_fr_k set deleted_date = now()  where id_risiko_current = " . $this->conn->escape($r['id_risiko_current']));
			}
			$this->conn->Execute("update risk_risiko_current set deleted_date = now() where id_risiko = " . $this->conn->escape($id));


			$rows = $this->conn->GetRows("select id_kri from risk_kri where deleted_date is null and id_risiko = " . $this->conn->escape($id));
			foreach ($rows as $r) {
				$this->conn->Execute("update risk_kri_hasil set deleted_date = now() where id_kri = " . $this->conn->escape($r['id_kri']));
			}
			$this->conn->Execute("update risk_kri set deleted_date = now() where id_risiko = " . $this->conn->escape($id));
		}

		// dpr(1,1);

		return true;
	}

	protected function _afterDetail($id)
	{

		$this->isLock();

		$this->data['editedheader1'] = $this->data['edited'];

		$this->data['rowheader1'] = $this->data['row'];

		$this->data['files'] = $this->modelfile->GArray('*', "where jenis='file' and id_risiko = " . $this->conn->escape($id));

		$this->data['filerekomendasi'] = $this->modelfile->GArray('*', "where jenis='filerekomendasi' and id_risiko = " . $this->conn->escape($id));

		if ($id) {
			$this->data['mode'] = 'edit_detail';
		}

		// if (!$this->data['row']['id_risiko_unit']) {
		// 	$this->data['row']['id_risiko_unit'] = $this->conn->GetList("select 
		// 	id_risiko_unit as idkey, id_risiko_unit as val 
		// 	from risk_risiko_unit 
		// 	where id_risiko = " . $this->conn->escape($id));
		// }

		// $this->data['risikobawaharr'] = array();
		if (!$this->data['row']['risikobawah']) {
			$this->data['row']['risikobawah'] = $this->conn->GetList("select id_risiko as val 
			from risk_risiko 
			where deleted_date is null and id_risiko_parent = " . $this->conn->escape($id));
		}

		if (!$this->data['row']['integrasi_internal'])
			$this->data['row']['integrasi_internal'] = $this->conn->GetList("select id_unit as idkey, id_unit as val from risk_integrasi_internal where deleted_date is null and id_risiko = " . $this->conn->escape($id));

		if ($this->data['row']['integrasi_internal']) {
			foreach ($this->data['row']['integrasi_internal'] as $b) {
				$this->data['integrasiinternal'][$b] = $this->conn->GetOne("select table_desc from mt_sdm_unit where deleted_date is null and table_code = " . $this->conn->escape($b));
			}
		}

		if ($this->data['row']['risikobawah']) {
			foreach ($this->data['row']['risikobawah'] as $id_risiko_child) {
				// $sasaran = $this->conn->GetOne("select nama from risk_sasaran rs 
				// where exists (select 1 from risk_risiko rr
				// where rs.id_sasaran = rr.id_sasaran 
				// and rr.id_risiko = " . $this->conn->escape($id_risiko_child) . ")");

				// $value = "<b>sasaran : </b>";
				// $value .= $sasaran . "<br>";

				// $value .= "<b>Penyebab : </b>";
				// $arr1 = $this->conn->GetList("select rp.id_risk_penyebab as idkey, rp.nama as val
				// from risk_penyebab rp
				// where exists (
				// 	select 1 from risk_risiko_penyebab rrp 
				// 	where rp.id_risk_penyebab = rrp.id_risk_penyebab
				// 	and rrp.id_risiko = " . $this->conn->escape($id_risiko_child) . "
				// )");
				// $value .= implode("<br>", $arr1) . "<br>";

				// $value .= "<b>Dampak : </b>";
				// $arr1 = $this->conn->GetList("select rp.id_risk_dampak as idkey, rp.nama as val
				// from risk_dampak rp
				// where exists (
				// 	select 1 from risk_risiko_dampak rrp 
				// 	where rp.id_risk_dampak = rrp.id_risk_dampak
				// 	and rrp.id_risiko = " . $this->conn->escape($id_risiko_child) . "
				// )");

				$arr1 = $this->conn->GetList("SELECT id_risiko AS idkey, nama AS val FROM risk_risiko WHERE deleted_date is null and id_risiko = " . $this->conn->escape($id_risiko_child));

				$value = implode("<br>", $arr1);

				$this->data['risikobawaharr'][$id_risiko_child] = $value;
			}
		}

		// dpr($this->data['rowheader1']['id_risiko_parent'], 1);
		$id_risiko_parent = $this->data['row']['id_risiko_parent'];
		if ($id_risiko_parent) {
			// $sasaran = $this->conn->GetOne("select nama from risk_sasaran rs 
			// where exists (select 1 from risk_risiko rr
			// where rs.id_sasaran = rr.id_sasaran 
			// and rr.id_risiko = " . $this->conn->escape($id_risiko_parent) . ")");

			// $value = "<b>Sasaran : </b>";
			// $value .= $sasaran . "<br>";

			// $value .= "<b>Penyebab : </b>";
			// $arr1 = $this->conn->GetList("select rp.id_risk_penyebab as idkey, rp.nama as val
			// from risk_penyebab rp
			// where exists (
			// 	select 1 from risk_risiko_penyebab rrp 
			// 	where rp.id_risk_penyebab = rrp.id_risk_penyebab
			// 	and rrp.id_risiko = " . $this->conn->escape($id_risiko_parent) . "
			// )");
			// $value .= implode("<br>", $arr1) . "<br>";

			// $value .= "<b>Dampak : </b>";
			// dpr($id_risiko_parent, 1);

			$arr1 = $this->conn->GetList("SELECT id_risiko AS idkey, nama AS val FROM risk_risiko WHERE deleted_date is null and id_risiko = " . $this->conn->escape($id_risiko_parent));
			$value = implode("<br>", $arr1);
			$this->data['risikoindukarr'][$id_risiko_parent] = $value;
			// dpr($this->data['risikoindukarr'][$id_risiko_parent], 1);
		}

		if (!$this->data['row']['var']) {
			$this->data['row']['var'] = $this->conn->GetArray("select * from risk_var where deleted_date is null and jenis='inheren' and id_risiko = " . $this->conn->escape($id));
			foreach ($this->data['row']['var'] as &$r) {
				$r['devisiasi'] = $r['target'] - $r['realisasi'];
			}
		}

		// unset($this->data['row']['dampak']);
		if (!$this->data['row']['dampak']) {
			$data_dampak = $this->conn->GetArray("select * from risk_dampak a where a.deleted_date is null and exists(select 1 from risk_risiko_dampak b where b.id_risk_dampak = a.id_risk_dampak and b.id_risiko = " . $this->conn->escape($id) . " )");

			$this->data['row']['dampak'] = array();
			foreach ($data_dampak as $f) {
				$this->data['row']['dampak'][$f['id_risk_dampak']] = $f;
			}
		}
		// unset($this->data['row']['penyebab']);
		if (!$this->data['row']['penyebab']) {
			$data_penyebab = $this->conn->GetArray("select * from risk_penyebab a where a.deleted_date is null and exists(select 1 from risk_risiko_penyebab b where b.id_risk_penyebab = a.id_risk_penyebab and b.id_risiko = " . $this->conn->escape($id) . " )");

			$this->data['row']['penyebab'] = array();
			foreach ($data_penyebab as $f) {
				$this->data['row']['penyebab'][$f['id_risk_penyebab']] = $f;
			}
		}

		if ($this->post && !$id) {
			$this->data['row'] = array_merge($this->data['row'], $this->post);
		}
		if ($this->data['row']['id_sasaran']) {
			if (is_numeric($this->data['row']['id_sasaran']))
				$this->data['risksasaranarr'] = $this->conn->GetList("select id_sasaran idkey, nama val from risk_sasaran where deleted_date is null and id_sasaran = " . $this->conn->escape($this->data['row']['id_sasaran']));
			else
				$this->data['risksasaranarr'] = array($this->data['row']['id_sasaran'] => $this->data['row']['id_sasaran']);
		}

		if ($this->data['row']['dampak']) {
			// $this->conn->debug=1;
			$this->data['riskdampakarr'] = array('' => '-pilih-');
			foreach ($this->data['row']['dampak'] as $n) {
				if (is_numeric($n['id_risk_dampak']))
					$this->data['riskdampakarr'][$n['id_risk_dampak']] = $this->conn->GetOne("select nama from risk_dampak where deleted_date is null and id_risk_dampak = " . $this->conn->escape($n['id_risk_dampak']));
				else
					$this->data['riskdampakarr'][$n['id_risk_dampak']] = $n['id_risk_dampak'];
			}
			// dpr($this->data['riskdampakarr'],1);
		}

		if ($this->data['row']['penyebab']) {
			$this->data['riskpenyebabarr'] = array('' => '-pilih-');
			foreach ($this->data['row']['penyebab'] as $g) {
				if (is_numeric($g['id_risk_penyebab']))
					$this->data['riskpenyebabarr'][$g['id_risk_penyebab']] = $this->conn->GetOne("select nama from risk_penyebab where deleted_date is null and id_risk_penyebab = " . $this->conn->escape($g['id_risk_penyebab']));
				else
					$this->data['riskpenyebabarr'][$g['id_risk_penyebab']] = $g['id_risk_penyebab'];
			}
		}

		#set sasaran jika non rutin
		if ($this->data['rowheader']['rutin_non_rutin'] == 'nonrutin') {
			$this->data['row']['id_sasaran'] = $this->data['rowheader']['id_sasaran_proyek'];
		}
	}

	private function rentetan($id_risiko)
	{
		$data = array();
		$data['risiko'] = $this->conn->GetRow("select r.is_opp_inherent,r.is_rutin,r.id_aspek_lingkungan,r.id_taksonomi_area, r.hasil_mitigasi_terhadap_sasaran, r.integrasi_eksternal, r.id_prioritas, r.dampak_kuantitatif_inheren,r.id_kriteria_kemungkinan, r.id_risiko_sebelum, r.id_scorecard, r.nomor, r.nama, r.deskripsi, r.inheren_dampak, r.inheren_kemungkinan, r.control_dampak_penurunan, r.control_kemungkinan_penurunan, r.penyebab, r.dampak, r.residual_target_dampak, r.residual_target_kemungkinan, r.residual_dampak_evaluasi, r.residual_kemungkinan_evaluasi,r.progress_capaian_sasaran, r.progress_capaian_kinerja, r.hambatan_kendala, r.penyesuaian_tindakan_mitigasi, r.status_risiko, r.status_keterangan, sk.nama as nsk, sk.kpi as ksk, ss.nama as nss, ss.kpi as kss, kd.nama as nk, r.tgl_risiko, r.tgl_close
			from risk_risiko r
			left join risk_sasaran sk on r.id_kegiatan = sk.id_sasaran
			left join risk_sasaran ss on r.id_sasaran = ss.id_sasaran
			left join mt_risk_kriteria_dampak kd on r.id_kriteria_dampak = kd.id_kriteria_dampak
			where r.deleted_date is null and r.id_risiko=" . $this->conn->escape($id_risiko) . "
			and (status_risiko = '0' or status_risiko = '2')");

		$data_unit = $this->conn->GetList("select table_code as idkey, table_desc as val from mt_sdm_unit a where deleted_date is null and exists(select 1 from risk_integrasi_internal b where a.table_code = b.id_unit and b.id_risiko = " . $this->conn->escape($id_risiko) . ")");
		if ($data['risiko'])
			$data['risiko']['integrasi_internal'] = implode(', ', $data_unit);
		if ($data['risiko']) {
			#penyebab
			$penyebab = $this->conn->GetArray("select * from risk_penyebab a where a.deleted_date is null and exists(select 1 from risk_risiko_penyebab b where b.id_risk_penyebab = a.id_risk_penyebab and b.id_risiko = " . $this->conn->escape($id_risiko) . ")");
			$penyebabarr = array();
			if ($penyebab)
				foreach ($penyebab as $a) {
					$penyebabarr[] = $a['nama'];
				}
			if ($penyebabarr) {
				unset($data['risiko']['penyebab']);
				$data['risiko']['penyebab'] = implode(", ", $penyebabarr);
			}

			#dampak
			$dampak = $this->conn->GetArray("select * from risk_dampak a where a.deleted_date is null and exists(select 1 from risk_risiko_dampak b where b.id_risk_dampak = a.id_risk_dampak and b.id_risiko = " . $this->conn->escape($id_risiko) . ")");
			if ($dampak)
				foreach ($dampak as $b) {
					$dampakarr[] = $b['nama'];
				}
			if ($dampakarr) {
				unset($data['risiko']['dampak']);
				$data['risiko']['dampak'] = implode(', ', $dampakarr);
			}
		}

		if (!$data['risiko'])
			return false;

		$data['scorecard'] = $this->conn->GetRow("select s.nama, s.scope, j.nama as nj, k.nama as nkr
			from risk_scorecard s
			/*left join mt_sdm_jabatan j on trim(s.owner) = trim(j.id_jabatan)*/
			left join mt_sdm_jabatan j on s.owner = j.id_jabatan
			left join risk_scorecard k on s.id_parent_scorecard = k.id_scorecard
			where s.deleted_date is null and s.id_scorecard = " . $this->conn->escape($data['risiko']['id_scorecard']));

		$data['control'] = $this->conn->GetArray("select c.nama,c.id_pengukuran, c.deskripsi, c.remark, c.is_efektif, c.menurunkan_dampak_kemungkinan, i.nama as interval
			from risk_control c
			left join mt_interval i on c.id_interval = i.id_interval
			where c.deleted_date is null and exists(select 1 from risk_control_risiko o where o.id_control = c.id_control and o.id_risiko=" . $this->conn->escape($id_risiko) . ")
			order by c.nama asc");

		// $this->conn->debug=1;
		$data['mitigasi'] = $this->conn->GetArray("select m.nama,m.sasaran, m.deskripsi, m.dead_line, m.menurunkan_dampak_kemungkinan, m.biaya, m.revenue, m.is_efektif, m.cba, j.nama as jabatan, 
		coalesce(status_progress, p.prosentase)+'% '+p.nama as status_progress
			from risk_mitigasi m
			left join mt_sdm_jabatan j on m.penanggung_jawab = j.id_jabatan
			left join mt_status_progress p on m.id_status_progress = p.id_status_progress
			where deleted_date is null and exists( select 1 from risk_mitigasi_risiko om where om.id_mitigasi = m.id_mitigasi and id_risiko=" . $this->conn->escape($id_risiko) . ") and is_control = '0' order by m.nama asc");
		// dpr($data['mitigasi'],1);

		return $data;
	}

	private function risikoSebelum(&$ret, $id_risiko_sebelum)
	{
		if (!$id_risiko_sebelum)
			return;

		$risiko = $this->conn->GetRow("select id_risiko_sebelum 
			from risk_risiko 
			where deleted_date is null and id_risiko = " . $this->conn->escape($id_risiko_sebelum));

		if ($risiko['id_risiko_sebelum']) {
			$this->risikoSebelum($ret, $risiko['id_risiko_sebelum']);
			$retet = $this->rentetan($risiko['id_risiko_sebelum']);
			if ($retet)
				$ret[] = $retet;
		}
	}

	private function risikoSesudah(&$ret, $id_risiko_sebelum)
	{
		if (!$id_risiko_sebelum)
			return;

		$risiko = $this->conn->GetRow("select id_risiko 
			from risk_risiko 
			where deleted_date is null and id_risiko_sebelum = " . $this->conn->escape($id_risiko_sebelum));

		if ($risiko['id_risiko']) {
			$retet = $this->rentetan($risiko['id_risiko']);
			if ($retet) {
				$ret[] = $retet;
				$this->risikoSesudah($ret, $risiko['id_risiko']);
			}
		}
	}

	function log_history($id_risiko = null)
	{
		$this->data['width_page'] = "900px";
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";

		$this->data['excel'] = false;

		$this->data['page_title'] = 'Riwayat Risiko / Peluang';

		$data = array();

		$this->risikoSebelum($data, $id_risiko);

		$retet = $this->rentetan($id_risiko);
		if ($retet)
			$data[] = $retet;

		$this->risikoSesudah($data, $id_risiko);

		$this->data['rows'] = $data;

		$this->View("panelbackend/risk_risiko_log");
	}

	protected function _afterUpdate($id)
	{
		$ret = $this->_afterInsert($id);

		return $ret;
	}


	protected function _delSertKri($id)
	{
		$ret = true;

		if ($this->post['act'] == 'save' || $this->post['act'] == 'save_kri') {
			$id_kri_arr = array(0);
			if ($this->post['kri'])
				foreach ($this->post['kri'] as $r) {
					if (!$ret)
						break;

					$record = array();
					$record['id_risiko'] = $id;
					$record['satuan'] = $r['satuan'];
					$record['target_mulai'] = $r['target_mulai'];
					$record['target_sampai'] = $r['target_sampai'];
					$record['batas_bawah'] = $r['batas_bawah'];
					$record['batas_atas'] = $r['batas_atas'];
					$record['polaritas'] = $r['polaritas'];
					$record['nama'] = $r['nama'];
					$record['keterangan'] = $r['keterangan'];

					if ($r['id_kri']) {
						$ret = $this->conn->goUpdate("risk_kri", $record, "id_kri = " . $this->conn->escape($r['id_kri']));

						$id_periode_tw = $this->data['id_periode_tw_risk'];
						$record1 = array();
						$record1['target_mulai'] = $record['target_mulai'];
						$record1['target_sampai'] = $record['target_sampai'];
						$record1['batas_bawah'] = $record['batas_bawah'];
						$record1['batas_atas'] = $record['batas_atas'];
						$record1['id_kri'] = $r['id_kri'];
						$record1['id_periode_tw'] = $id_periode_tw;
						$record1['tahun'] = $this->data['tahun_risk'];
						$id_kri_hasil = $this->conn->GetOne("select 
						id_kri_hasil 
						from risk_kri_hasil 
						where deleted_date is null and id_kri = " . $this->conn->escape($r['id_kri']) . " 
						and id_periode_tw = " . $this->conn->escape($id_periode_tw) . " 
						and tahun = " . $this->conn->escape($this->data['tahun_risk']));

						if ($id_kri_hasil) {
							$ret = $this->conn->goUpdate("risk_kri_hasil", $record1, "id_kri_hasil = " . $this->conn->escape($id_kri_hasil));
						} else {
							$ret = $this->conn->goInsert("risk_kri_hasil", $record1);
						}
					} else {
						$ret = $this->conn->goInsert("risk_kri", $record);
						$r['id_kri'] = $this->conn->GetOne("select max(id_kri) from risk_kri where deleted_date is null and id_risiko = " . $this->conn->escape($id));
					}

					if ($ret) {
						$record1 = array();
						// $record1['nilai'] = $v;
						$record1['id_kri'] = $r['id_kri'];
						$record1['id_periode_tw'] = $id_periode_tw;
						$record1['tahun'] = $this->data['tahun_risk'];
						$id_kri_hasil = $this->conn->GetOne("select 
						id_kri_hasil 
						from risk_kri_hasil 
						where deleted_date is null and id_kri = " . $this->conn->escape($r['id_kri']) . " 
						and id_periode_tw = " . $this->conn->escape($id_periode_tw) . " 
						and tahun = " . $this->conn->escape($this->data['tahun_risk']));

						if ($id_kri_hasil) {
							$ret = $this->conn->goUpdate("risk_kri_hasil", $record1, "id_kri_hasil = " . $this->conn->escape($id_kri_hasil));
						} else {
							$ret = $this->conn->goInsert("risk_kri_hasil", $record1);
						}
					}

					if ($ret)
						$id_kri_arr[] = $r['id_kri'];
				}

			if ($ret) {
				$rows = $this->conn->GetArray("select id_kri from risk_kri where deleted_date is null and id_risiko = " . $this->conn->escape($id) . " and id_kri not in (" . implode(",", $id_kri_arr) . ")");

				foreach ($rows as $r) {
					if (!$ret)
						break;

					$ret = $this->conn->Execute("update risk_kri_hasil set deleted_date = now() where id_kri = " . $this->conn->escape($r['id_kri']));

					if ($ret)
						$ret = $this->conn->Execute("update risk_kri set deleted_date = now() where id_kri = " . $this->conn->escape($r['id_kri']));
				}
			}

			$this->post['act'] = 'save';
		}

		return $ret;
	}

	private function _delSertVar($id)
	{
		$ret = $this->conn->Execute("update risk_var set deleted_date = now() where jenis='inheren' and id_risiko = " . $this->conn->escape($id));
		if ($this->post['var'])
			foreach ($this->post['var'] as $r) {
				if (!$ret)
					break;

				$r['id_risiko'] = $id;
				$r['jenis'] = 'inheren';
				$ret = $this->conn->goInsert("risk_var", $r);
			}
		return $ret;
	}
	private function _delSertKpi($id)
	{
		$return = $this->conn->Execute("update risk_risiko_kpi set deleted_date = now() where id_risiko = " . $this->conn->escape($id));

		if (is_array($this->post['id_kpi'])) {
			foreach ($this->post['id_kpi'] as $key => $value) {
				if ($return) {
					if (!$value)
						continue;

					$record = array();
					$record['id_risiko'] = $id;
					$record['id_kpi'] = $value;

					$sql = $this->conn->InsertSQL("risk_risiko_kpi", $record);

					if ($sql) {
						$return = $this->conn->Execute($sql);
					}
				}
			}
		}
		return $return;
	}
	protected function _afterInsert($id)
	{
		// dpr($this->data['rowheader1']['id_risiko_parent'], 1);
		$ret = true;

		if ($ret)
			$ret = $this->_delSertKpi($id);

		if ($ret)
			$ret = $this->save_penyebab($id);

		if ($ret)
			$ret = $this->save_dampak($id);

		if ($ret)
			$ret = $this->save_proyek_terkait($id);

		if ($ret)
			$ret = $this->_delSertKri($id);

		if ($ret)
			$ret = $this->_delSertVar($id);

		if ($ret)
			$ret = $this->_delSertRisikoBawah($id);

		if ($ret && $this->post['id_kriteria_kemungkinan'] == 3) {
			$suc = $this->_uploadFile($id, 'file');

			$ret = $suc['success'];

			if (!$ret) {
				$this->data['err_msg'] .= $ret['error'];
				return false;
			}
		} else if ($ret) {
			$suc = $this->_deleteFile($id, 'file');

			$ret = $suc['success'];
		}

		if ($ret && $this->access_role['rekomendasi']) {
			$suc = $this->_uploadFile($id, 'filerekomendasi');

			$ret = $suc['success'];

			if (!$ret) {
				$this->data['err_msg'] .= $ret['error'];
				return false;
			}
		}

		if ($ret) {
			$this->riskchangelog($this->data['row'], $this->data['rowold']);
		}

		if ($ret && $this->post['integrasi_internal']) {
			$ret = $this->conn->Execute("update risk_integrasi_internal set deleted_date = now() where id_risiko = " . $this->conn->escape($id));
			foreach ($this->post['integrasi_internal'] as $d) {
				if (!$ret)
					break;
				$rec = array(
					"id_risiko" => $id,
					"id_unit" => $d,
				);
				$ret = $this->conn->goInsert("risk_integrasi_internal", $rec);
			}
		}

		return $ret;
	}

	private function _delSertRisikoBawah($id)
	{
		$return = $this->conn->Execute("update risk_risiko set id_risiko_parent = null where id_risiko_parent = " . $this->conn->escape($id));

		if (is_array($this->post['risikobawah'])) {
			foreach ($this->post['risikobawah'] as $idkey => $id_risiko) {
				if ($return) {

					$record = array();
					$record['id_risiko_parent'] = $id;

					$return = $this->conn->goUpdate("risk_risiko", $record, "id_risiko = " . $this->conn->escape($id_risiko));
				}
			}
		}

		return $return;
	}

	private function _delSertJabatan($id)
	{
		// $return = $this->conn->Execute("delete from risk_jabatan_berisiko where id_risiko = " . $this->conn->escape($id));

		// if (is_array($this->post['id_jabatan_berisiko'])) {
		// 	foreach ($this->post['id_jabatan_berisiko'] as $idkey => $value) {
		// 		if ($return) {
		// 			if (!$value)
		// 				continue;

		// 			$record = array();
		// 			$record['id_risiko'] = $id;
		// 			$record['id_jabatan'] = $value;

		// 			$sql = $this->conn->InsertSQL("risk_jabatan_berisiko", $record);

		// 			if ($sql) {
		// 				$return = $this->conn->Execute($sql);
		// 			}
		// 		}
		// 	}
		// }

		// return $return;
	}

	protected function _getList($page = 0)
	{
		$this->_resetList();

		$this->arrNoquote = $this->model->arrNoquote;

		$param = array(
			'page' => $page,
			'limit' => $this->_limit(),
			'order' => $this->_order(),
			'filter' => $this->_getFilter()
		);

		if ($this->post['act']) {

			if ($this->data['add_param']) {
				$add_param = '/' . $this->data['add_param'];
			}
		}

		$respon = $this->model->SelectGrid(
			$param
		);

		return $respon;
	}


	protected function _getFilter()
	{
		$this->xss_clean = true;

		$this->FilterRequest();

		$filter_arr = array();

		if ($this->post['act'] == 'list_filter' && $this->post['list_filter']) {
			if (!$_SESSION[SESSION_APP][$this->page_ctrl]['list_filter']) {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'] = $this->post['list_filter'];
			} else {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'], $this->post['list_filter']);
			}
		}

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['list_filter']) {

			foreach ($_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'] as $r) {
				$idkey = $r['idkey'];
				$filter_arr1 = array();

				foreach ($r['values'] as $k => $v) {
					$k = str_replace("_____", ".", $k);

					replaceSingleQuote($v);
					replaceSingleQuote($k);
					if (!($v === '' or $v === null or $v === false))
						$filter_arr1[] = 'a.' . $idkey . " = '$v'";
				}

				$filter_str = implode(' or ', $filter_arr1);

				if ($filter_str) {
					$filter_arr[] = "($filter_str)";
				}
			}
		}

		if (!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']) {
			$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] = array();
		}

		if ($this->post['act'] == 'list_search' && $this->post['list_search_filter']) {
			if (!$this->post['list_search_filter']['is_draft']) {
				unset($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']['is_draft']);
			}
			if (!$this->post['list_search_filter']['is_signifikan']) {
				unset($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']['is_signifikan']);
			}
			if (!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']) {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] = $this->post['list_search_filter'];
			} else {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'], $this->post['list_search_filter']);
			}
		}

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']) {
			foreach ($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] as $k => $v) {
				if ($k == "is_draft") {
					$k = "is_lock";
					$v = 0;
				}
				if (!($v === '' or $v === null or $v === false)) {
					if ($k == 'is_signifikan') {
						$filter_arr[] = "abs(
						coalesce(coalesce(
							(a.residual_kemungkinan_evaluasi*a.residual_dampak_evaluasi*(case when is_opp_inherent is null then 1 else is_opp_inherent end)), 
							(a.control_kemungkinan_penurunan*a.control_dampak_penurunan*(case when is_opp_inherent is null then 1 else is_opp_inherent end))
						),0)
					)>=5";
					} elseif ($k == "inheren") {
						$filter_arr[] = "exists (select 1 from mt_risk_matrix b 
					where b.deleted_date is null and a.inheren_kemungkinan = b.id_kemungkinan 
					and a.inheren_dampak = b.id_dampak
					and b.id_tingkat = " . $this->conn->escape($v) . ")";
					} elseif ($k == "control") {
						$filter_arr[] = "exists (select 1 from mt_risk_matrix b 
					where b.deleted_date is null and a.control_kemungkinan_penurunan = b.id_kemungkinan 
					and a.control_dampak_penurunan = b.id_dampak
					and b.id_tingkat = " . $this->conn->escape($v) . ")";
					} elseif ($k == "actual") {
						$filter_arr[] = "exists (select 1 from mt_risk_matrix b 
					where b.deleted_date is null and a.residual_kemungkinan_evaluasi = b.id_kemungkinan 
					and a.residual_dampak_evaluasi = b.id_dampak
					and b.id_tingkat = " . $this->conn->escape($v) . ")";
					} else {
						$k = str_replace("_____", ".", $k);

						replaceSingleQuote($v);
						replaceSingleQuote($k);

						$filter_arr[] = "$k='$v'";
					}
				}
			}
		}




		if (!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search']) {
			$_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] = array();
		}

		if ($this->post['act'] == 'list_search' && $this->post['list_search']) {

			if (!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search']) {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] = $this->post['list_search'];
			} else {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_search'], $this->post['list_search']);
			}
		}

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['list_search']) {
			foreach ($_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] as $k => $v) {
				$k = str_replace("_____", ".", $k);

				replaceSingleQuote($v);
				replaceSingleQuote($k);

				if (trim($v) !== '' && in_array($k, $this->arrNoquote)) {
					$filter_arr[] = "$k=$v";
				} else if ($v !== '' && $k !== 'sumber' && $k !== 'dampak' && $k !== 'sasaran') {
					$v = strtolower($v);
					$filter_arr[] = "lower($k) like '%$v%'";
				} else if ($v !== '' && $k == 'sumber') {
					$filter_arr[] = " id_risiko in(select id_risiko from risk_risiko_penyebab a where a.deleted_date is null and exists(select 1 from risk_penyebab b where a.id_risk_penyebab = b.id_risk_penyebab and lower(nama) like '%$v%' ))";
				} else if ($v !== '' && $k == 'dampak') {
					$filter_arr[] = " id_risiko in(select id_risiko from risk_risiko_dampak a where a.deleted_date is null and exists(select 1 from risk_dampak b where a.id_risk_dampak = b.id_risk_dampak and lower(nama) like '%$v%' ))";
				} else if ($v !== '' && $k == 'sasaran') {
					$filter_arr[] = " id_risiko in(select id_risiko from risk_risiko a where a.deleted_date is null and exists(select 1 from risk_sasaran b where a.id_sasaran = b.id_sasaran and lower(nama) like '%$v%' ))";
				}
			}
		}

		$this->data['filter_arr'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_search'], $_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']);

		if (($filter_arr)) {
			$this->filter .= ' and ' . implode(' and ', $filter_arr);
		}

		return $this->filter;
	}

	function _deleteFile($id_risiko = null, $jenis = null)
	{
		$rows = $this->conn->GetArray("select * from risk_risiko_files where deleted_date is null and id_risiko = " . $this->conn->escape($id_risiko) . " and jenis = " . $this->conn->escape($jenis));

		$return = array('success' => true);

		foreach ($rows as $idkey => $value) {
			$id_file = $value['id_risiko_files'];
			$file_name = $value['file_name'];

			$return = $this->modelfile->Delete("id_risiko_files = " . $this->conn->escape($id_file));

			if ($return) {
				$full_path = $this->data['configfile']['upload_path'] . $file_name;
				unlink($full_path);
			}
		}

		return $return;
	}

	function _uploadFile($id_risiko = null, $jenis = 'file')
	{
		$return = array('success' => true);

		$cek = $this->conn->GetOne("select 1 from risk_risiko_files where deleted_date is null and jenis = '$jenis' and id_risiko = " . $this->conn->escape($id_risiko));

		if (!$_FILES[$jenis]['name'] && !$cek)
			return array('error' => "Lampiran wajib di isi.");

		if (!$_FILES[$jenis]['name'])
			return array('success' => "Update berhasil");

		$this->data['configfile']['file_name'] = 'efektifitas_risiko' . time() . $_FILES[$jenis]['name'];

		$this->load->library('upload', $this->data['configfile']);

		if (!$this->upload->do_upload($jenis)) {
			$return = array('error' => $this->upload->display_errors());
		} else {
			$upload_data = $this->upload->data();
			$return = array('success' => "Upload " . $upload_data['client_name'] . " berhasil");

			$record = array();
			$record['client_name'] = $upload_data['client_name'];
			$record['file_name'] = $upload_data['file_name'];
			$record['file_type'] = $upload_data['file_type'];
			$record['file_size'] = $upload_data['file_size'];
			$record['id_risiko'] = $id_risiko;
			$record['jenis'] = $jenis;
			$ret = $this->modelfile->Insert($record);
			if (!$ret['success']) {
				unlink($upload_data['full_path']);
				$return = $ret;
			}
		}

		return $return;
	}

	function delete_file($id_risiko = null, $id_file = null)
	{
		$row = $this->model->GetByPk($id_risiko);
		$file_name = $this->modelfile->GetOne("select file_name from risk_risiko_files where deleted_date is null and id_risiko_files = " . $this->conn->escape($id_file));

		$return = $this->modelfile->Delete("id_risiko_files = " . $this->conn->escape($id_file));

		if ($return) {
			$full_path = $this->data['configfile']['upload_path'] . $file_name;
			unlink($full_path);

			SetFlash('suc_msg', $return['success']);
		} else {
			SetFlash('err_msg', "Data gagal didelete");
		}
		redirect("$this->page_ctrl/edit/$row[id_scorecard]/$id_risiko");
	}

	function preview_file($id)
	{
		$row = $this->modelfile->GetByPk($id);
		if ($row) {
			$full_path = $this->data['configfile']['upload_path'] . $row['file_name'];
			header("Content-Type: {$row['file_type']}");
			header("Content-Disposition: inline; filename='{$row['client_name']}'");
			echo file_get_contents($full_path);
			die();
		} else {
			$this->Error404();
		}
	}


	public function proses($id_scorecard = null, $id = null)
	{
		if ($this->post['act'] == 'save_file') {
			$this->_uploadFileProses($id_scorecard);
			die();
		}

		$this->_beforeDetail($id_scorecard, $id);

		if ($id) {
			$this->data['row'] = $this->model->GetByPk($id);

			if (!$this->data['row'])
				$this->NoData();

			$this->data['notab'] = false;
		} else {
			$this->data['notab'] = true;
		}

		$this->_afterDetail($id);

		$this->View("panelbackend/risk_risiko_proses");
	}

	function _uploadFileProses($id = null)
	{
		$this->conn->debug = 1;
		if (!$_FILES['proses']['name'])
			return true;

		$return = array('success' => true);

		if ($_FILES['proses']['name']) {

			$this->data['configfile']['file_name'] = "scorecard_proses" . $id;
			$this->data['configfile']['allowed_types'] = "pdf";
			$this->load->library('upload', $this->data['configfile']);
			$this->upload->overwrite = true;

			if (!$this->upload->do_upload('proses')) {
				$return = array('error' => $this->upload->display_errors());
			} else {
				$upload_data = $this->upload->data();
				$return = array('success' => "Upload " . $upload_data['client_name'] . " berhasil");

				$record = array();
				$ret = $this->conn->Execute("update risk_scorecard set proses = " . $this->conn->escape($upload_data['client_name']) . " where id_scorecard = " . $this->conn->escape($id));

				if (!$ret) {
					@unlink($upload_data['full_path']);
					$return["success"] = false;
					$return["error"] = "Upload berhasil";
				}
			}
		}

		if ($return['success']) {
			SetFlash('suc_msg', $return['success']);
		} else {
			SetFlash('err_msg', $return['error']);
		}

		redirect(current_url());
	}

	function detail_risiko($id_risiko)
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";
		$this->viewprint = "panelbackend/detailrisiko";
		$this->data['no_header'] = true;
		$this->data['no_title'] = true;

		$tahun = $this->data['tahun'];
		$id_periode_tw = $this->data['id_periode_tw'];

		$sql = "
		select
		concat(rr1.nomor ,' ', rr1.nama) as risiko_induk,
		rs.nama as scorecard,
		rr.id_risiko,
		rr.dampak_kuantitatif_inheren,
		rr.dampak_kuantitatif_current,
		rr.dampak_kuantitatif_residual,
		rr.status_risiko,
		rr.nomor as kode_risiko,
		rto.nama as taksonomi_objective,
		rta.nama as taksonomi_area,
		rr.nama as risiko,
		rr.sasaran,
		rr.penyebab as penyebab,
		rr.dampak as dampak,
		mdj.nama as risk_owner,
		rr.inheren_kemungkinan as inheren_kemungkinan1,
		rr.inheren_dampak as inheren_dampak1,
		mrki.kode as inheren_kemungkinan,
		rr.id_kriteria_kemungkinan as kategori_kemungkinan,
		mrdi.kode as inheren_dampak,
		mrkd.nama as kategori_dampak,
		concat(mrki.kode , mrdi.kode) as level_risiko_inheren,
		rc.id_control,
		rc.id_pengukuran,
		rc.nama as nama_kontrol,
		rc.menurunkan_dampak_kemungkinan as control_menurunkan,
		rc.is_efektif as control_efektif,
		rr.control_kemungkinan_penurunan,
		rr.control_dampak_penurunan,
		mrkc.kode as kemungkinan_paskakontrol,
		mrdc.kode as dampak_paskakontrol,
		concat(mrkc.kode , mrdc.kode) as level_risiko_paskakontrol,
		rm.id_mitigasi,
		rm.nama as nama_mitigasi,
		case when rm.status_progress > 0 and rm.status_progress is not null then rm.nama else null end as nama_mitigasi_berjalan,
		rm.menurunkan_dampak_kemungkinan as mitigasi_menurunkan,
		rm.dead_line as waktu_pelaksanaan,
		rm.biaya as biaya_mitigasi,
		rm.cba as cba_mitigasi,
		msj.nama as penanggungjawab_mitigasi,
		rm.id_status_progress as capaian_mitigasi,
		concat(rm.status_progress , '%') as capaian_mitigasi_progress,
		rr.residual_target_kemungkinan,
		rr.residual_target_dampak,
		
		mrka.kode as kemungkinan_actual,
		mrda.kode as dampak_actual,
		concat(mrka.kode , mrda.kode) as level_risiko_actual,

		rm.is_efektif as mitigasi_efektif,
		rme.id_pengukuran as id_pengukuranm,
		rme.id_mitigasi_efektif,
		mrkrsd.kode as kemungkinan_rdual,
		mrdrsd.kode as dampak_rdual,
		concat(mrkrsd.kode , mrdrsd.kode) as level_risiko_residual,
		rr.progress_capaian_kinerja as capaian_mitigasi_evaluasi,
		rr.hambatan_kendala as hambatan_kendala,
		rr.penyesuaian_tindakan_mitigasi as penyesuaian_mitigasi,
		concat(rr.kode_aktifitas,' ',rr.nama_aktifitas) as aktifitas,
		rr.sub_tahapan_kegiatan,
		rr.skor_inheren_kemungkinan,
		rr.skor_inheren_dampak,
		rr.skor_control_kemungkinan,
		rr.skor_control_dampak,
		coalesce(rrc.skor_kemungkinan, rr.skor_current_kemungkinan) skor_current_kemungkinan,
		coalesce(rrc.skor_dampak, rr.skor_current_dampak) skor_current_dampak,
		rr.skor_target_kemungkinan,
		rr.skor_target_dampak,
		rrc.id_risiko_current,

		
		kri.id_kri,
		kri.nama as nama_kri,
		kri.keterangan as formula_kri,
		kri.polaritas,
		kri.satuan,
		kri.batas_bawah,
		kri.batas_atas,
		kri.target_mulai,
		kri.target_sampai,
		rm.program_kerja,
		rm.rencana,
		rm.realisasi,
		rm.devisiasi,
		rm.satuan satuan_mitigasi,
		rr.hasil_mitigasi_terhadap_sasaran, 
		rr.is_monitoring_rmtik, 
		rr.is_monitoring_p2k3, 
		rr.is_monitoring_fkap, 
		rr.ket_monitoring_rmtik, 
		rr.ket_monitoring_p2k3, 
		rr.ket_monitoring_fkap, 
		rr.is_evaluasi_mitigasi, 
		rr.is_evaluasi_risiko,
		rr.nama_aktifitas,
		kpi.nama namakpi

		from risk_risiko rr
		left join risk_scorecard rs on rr.id_scorecard = rs.id_scorecard
		left join mt_sdm_jabatan mdj on trim(mdj.id_jabatan) = trim(rs.owner)
		left join risk_risiko_current rrc on rr.id_risiko = rrc.id_risiko 
		and rrc.tahun = " . $this->conn->escape($tahun) . " and rrc.id_periode_tw = " . $this->conn->escape($id_periode_tw) . "
		left join mt_risk_kemungkinan mrka on mrka.id_kemungkinan = coalesce(rrc.id_kemungkinan, coalesce(rr.residual_kemungkinan_evaluasi, rr.control_kemungkinan_penurunan))
		left join mt_risk_dampak mrda on mrda.id_dampak = coalesce(rrc.id_dampak, coalesce(rr.residual_dampak_evaluasi,rr.control_dampak_penurunan))
		left join mt_risk_kemungkinan mrki on mrki.id_kemungkinan = rr.inheren_kemungkinan
		left join mt_risk_dampak mrdi on mrdi.id_dampak = rr.inheren_dampak
		left join risk_control rc on rc.id_risiko = rr.id_risiko
		left join mt_risk_kemungkinan mrkc on mrkc.id_kemungkinan = rr.control_kemungkinan_penurunan
		left join mt_risk_dampak mrdc on mrdc.id_dampak = rr.control_dampak_penurunan
		left join risk_mitigasi rm on rm.id_risiko = rr.id_risiko and rm.is_control <> '1'
		left join risk_mitigasi_efektif rme on rm.id_mitigasi = rme.id_mitigasi 
		and rme.tahun = " . $this->conn->escape($tahun) . " and rme.id_periode_tw = " . $this->conn->escape($id_periode_tw) . "
		left join mt_sdm_jabatan msj on msj.id_jabatan = rm.penanggung_jawab
		left join mt_risk_kemungkinan mrkrsd on mrkrsd.id_kemungkinan = rr.residual_target_kemungkinan
		left join mt_risk_dampak mrdrsd on mrdrsd.id_dampak = rr.residual_target_dampak
		left join mt_risk_kriteria_dampak mrkd on mrkd.id_kriteria_dampak = rr.id_kriteria_dampak
		left join mt_risk_taksonomi_area rta on rta.id_taksonomi_area = rr.id_taksonomi_area
		left join mt_risk_taksonomi_objective rto on rta.id_taksonomi_objective = rto.id_taksonomi_objective
		left join risk_risiko rr1 on rr.id_risiko_parent = rr1.id_risiko
		left join risk_kri kri on rr.id_risiko = kri.id_risiko
		left join kpi kpi on rr.id_kpi = kpi.id_kpi
		";

		$rows = $this->conn->GetArray($sql . " where rr.id_risiko = " . $this->conn->escape($id_risiko));
		$this->data['list']['rows'] = $rows;
		$this->data['row'] = $rows[0];
		$this->data['header'] = array_keys($rows[0]);

		$this->View($this->viewprint);
	}
}
