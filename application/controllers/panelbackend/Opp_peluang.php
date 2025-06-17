<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Opp_peluang extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/opp_peluanglist";
		$this->viewdetail = "panelbackend/opp_peluangdetailinline";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_peluang";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Identifikasi Peluang';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Identifikasi Peluang';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Identifikasi Peluang';
			$this->data['edited'] = false;
		} else {
			// $this->layout = "panelbackend/layout1";
			$this->data['notab'] = true;
			$this->data['page_title'] = 'Daftar Peluang';
		}

		$this->load->model("Opp_peluangModel", "model");
		$this->load->model("Opp_scorecardModel", 'oppscorecard');
		$this->load->model("Mt_opp_kelayakanModel", 'kelayakan');
		$this->data['kelayakanarr'] = $this->kelayakan->GetCombo();
		unset($this->data['kelayakanarr']['']);

		$this->load->model("Opp_peluang_filesModel", "modelfile");
		$this->load->model("Mt_opp_kriteria_dampakModel", 'kriteria');
		$this->data['kriteriaarr'] = $this->kriteria->GetCombo();
		$this->data['kriteriakemungkinanarr'] = array('' => '', '1' => 'Probabilitas', '2' => 'Deskripsi Kualitatif', '3' => 'Insiden Sebelumnya');

		// $this->load->model("Mt_opp_taksonomi_areaModel", 'taksonomiarea');
		// $this->data['taksonomiareaarr'] = $this->taksonomiarea->GetCombo();
		$this->load->model("KpiModel", 'kpi');
		$this->data['kpiarr'] = $this->kpi->GetCombo();
		$this->SetAccess('panelbackend/opp_scorecard');

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'upload', 'select2', 'tinymce'
		);
		$this->data['configfile'] = $this->config->item('file_upload_config');
	}

	protected function Header()
	{
		$return = array(
			array(
				'name' => 'nomor',
				'label' => 'Kode',
				'width' => "110px",
				'type' => "varchar2",
			)
		);

		if ($this->data['rowheader']['id_nama_proses']) {
			$return = array_merge($return, array(
				array(
					'name' => 'nama_aktifitas',
					'label' => 'Aktivitas',
					'width' => "auto",
					'type' => "varchar2",
				)
			));
		}

		$return = array_merge($return, array(
			array(
				'name' => 'nama',
				'label' => 'Nama Peluang',
				'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'inheren',
				'label' => 'Tingkat Peluang',
				'width' => "70px",
				'type' => "list",
				'value' => $this->data['mttingkatdampakpeluangarr'],
			),
		));

		$return = array_merge($return, array(
			array(
				'name' => 'status_peluang',
				'label' => 'Status Peluang',
				'width' => "70px",
				'type' => "list",
				'value' => array('' => '', '0' => 'Close', '1' => 'Open', '2' => 'Berlanjut'),
			)
		));


		// if ($this->Access('evaluasimitigasi', "panelbackend/opp_scorecard")) {
		// 	$return = array_merge($return, array(
		// 		array(
		// 			'name' => 'is_evaluasi_peluang',
		// 			'label' => 'Evaluasi Tahunan',
		// 			'width' => "50px",
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

				$id = $this->conn->GetOne("select id_aktifitas from mt_pb_aktifitas where deleted_date is null and nama = '{$record['nama']}'");
			}

			$this->post['id_aktifitas'] = $_POST['id_aktifitas'] = $id;

			$this->data['mtaktifitasarr'][$id] = $record['nama'];

			unset($this->data['mtaktifitasarr'][$record['nama']]);
		}
	}

	protected function Record($id = null)
	{
		$this->AddOption();
		$record =  array(
			'nama_kegiatan' => $this->post['nama_kegiatan'],
			'id_kategori' => $this->post['id_kategori'],
			'sub_tahapan_kegiatan' => $this->post['sub_tahapan_kegiatan'],
			// 'skor_inheren_dampak'=>$this->post['skor_inheren_kemungkinan'],
			'id_peluang_parent' => ($this->post['id_peluang_parent'] ? $this->post['id_peluang_parent'] : null),
			'id_peluang_parent_lain' => ($this->post['id_peluang_parent'] === '0' ? 1 : 0),
			'regulasi' => $this->post['regulasi'],
			// 'id_jabatan_bepeluang' => $this->post['id_jabatan_bepeluang'],
			'red_flag' => $this->post['red_flag'],

			'nama' => $this->post['nama'],
			'nama_aktifitas' => $this->post['nama_aktifitas'],
			'kode_aktifitas' => $this->post['kode_aktifitas'],
			'deskripsi' => $this->post['deskripsi'],
			'inheren_dampak' => $this->post['inheren_dampak'],
			'inheren_kemungkinan' => $this->post['inheren_kemungkinan'],
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
			// 'current_opp_dampak' => $this->post['current_opp_dampak'],
			// 'current_opp_kemungkinan' => $this->post['current_opp_kemungkinan'],
			'id_kriteria_dampak' => $this->post['id_kriteria_dampak'],
			'id_kriteria_kemungkinan' => $this->post['id_kriteria_kemungkinan'],
			'id_taksonomi' => $this->post['id_taksonomi'],
			'id_taksonomi_area' => $this->post['id_taksonomi_area'],
			'id_kpi' => $this->post['id_kpi'],
			'id_kelayakan' => $this->post['id_kelayakan'],
			'sasaran' => $this->post['sasaran'],
			'is_kerangka_acuan_kerja' => (int)$this->post['is_kerangka_acuan_kerja'],
			'anggaran_biaya' => Rupiah2Number($this->post['anggaran_biaya']),
			'target_penyelesaian' => $this->post['target_penyelesaian'],
		);

		if ($this->data['row']['status_peluang'] == 0 or $this->data['row']['status_peluang'] == 2 or $this->data['row']['tgl_close'])
			$record['tgl_close'] = $this->post['tgl_close'];

		if ($this->access_role['view_all'] && $id) {
			$record['nomor'] = $this->post['nomor'];
		}
		if ($this->access_role['view_all']) {
			$record['tgl_peluang'] = $this->post['tgl_peluang'];
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
			"nama" => array(
				'field' => 'nama',
				'label' => 'Nama',
				'rules' => "required|max_length[200]",
			),
			// "sasaran" => array(
			// 	'field' => 'sasaran',
			// 	'label' => 'Sasaran',
			// 	'rules' => "required|max_length[200]",
			// ),
			"deskripsi" => array(
				'field' => 'deskripsi',
				'label' => 'Deskripsi',
				'rules' => "max_length[4000]",
			),
			"inheren_dampak" => array(
				'field' => 'inheren_dampak',
				'label' => 'Tingkat Dampak',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtdampakpeluangarr'])) . "]|required",
			),
			// "id_kpi" => array(
			// 	'field' => 'id_kpi',
			// 	'label' => 'KPI',
			// 	'rules' => "in_list[" . implode(",", array_keys($this->data['kpiarr'])) . "]|required",
			// ),
			// "id_taksonomi_area" => array(
			// 	'field' => 'id_taksonomi_area',
			// 	'label' => 'Taksonomi',
			// 	'rules' => "in_list[" . implode(",", array_keys($this->data['taksonomiareaarr'])) . "]|required",
			// ),
			"inheren_kemungkinan" => array(
				'field' => 'inheren_kemungkinan',
				'label' => 'Tingkat Kemungkinan',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtkemungkinanpeluangarr'])) . "]|required",
			),
			"control_dampak_penurunan" => array(
				'field' => 'control_dampak_penurunan',
				'label' => 'Dampak',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtdampakpeluangarr'])) . "]",
			),
			"control_kemungkinan_penurunan" => array(
				'field' => 'control_kemungkinan_penurunan',
				'label' => 'Tingkat',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtkemungkinanpeluangarr'])) . "]",
			),
			"mitigasi_dampak_penurunan" => array(
				'field' => 'mitigasi_dampak_penurunan',
				'label' => 'Dampak',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtdampakpeluangarr'])) . "]",
			),
			"mitigasi_kemungkinan_penurunan" => array(
				'field' => 'mitigasi_kemungkinan_penurunan',
				'label' => 'Tingkat',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtkemungkinanpeluangarr'])) . "]",
			),
			// "current_opp_dampak" => array(
			// 	'field' => 'current_opp_dampak',
			// 	'label' => 'Dampak',
			// 	'rules' => "in_list[" . implode(",", array_keys($this->data['mtdampakpeluangarr'])) . "]",
			// ),
			// "current_opp_kemungkinan" => array(
			// 	'field' => 'current_opp_kemungkinan',
			// 	'label' => 'Tingkat',
			// 	'rules' => "in_list[" . implode(",", array_keys($this->data['mtkemungkinanpeluangarr'])) . "]",
			// ),
			/*"id_taksonomi"=>array(
				'field'=>'id_taksonomi',
				'label'=>'Taksonomi',
				'rules'=>"in_list[".implode(",", array_keys($this->data['taksonomiarr']))."]",
			),*/
			"residual_target_dampak" => array(
				'field' => 'residual_target_dampak',
				'label' => 'Dampak',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtdampakpeluangarr'])) . "]",
			),
			"residual_target_kemungkinan" => array(
				'field' => 'residual_target_kemungkinan',
				'label' => 'Tingkat',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtkemungkinanpeluangarr'])) . "]",
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
			"id_kriteria_dampak" => array(
				'field' => 'id_kriteria_dampak',
				'label' => 'Kategori',
				'rules' => "in_list[" . implode(",", array_keys($this->data['kriteriaarr'])) . "]|required",
			),
			// "id_kriteria_kemungkinan" => array(
			// 	'field' => 'id_kriteria_kemungkinan',
			// 	'label' => 'Kategori',
			// 	'rules' => "in_list[" . implode(",", array_keys($this->data['kriteriakemungkinanarr'])) . "]|required",
			// ),
			// "penyebab" => array(
			// 	'field' => 'penyebab',
			// 	'label' => 'Penyebab',
			// 	'rules' => "max_length[4000]|required",
			// ),
			"dampak" => array(
				'field' => 'dampak',
				'label' => 'Dampak',
				'rules' => "max_length[4000]|required",
			),
		);

		if ($this->data['rowheader']['jenis_sasaran'] != '2') {
			unset($return['id_kegiatan']);
		}

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
			$return['tgl_peluang'] = array(
				'field' => 'tgl_peluang',
				'label' => 'Tgl. Peluang',
				'rules' => "required",
			);
		}

		if ($this->access_role['rekomendasi']) {
			$return['rekomendasi_keterangan'] = array(
				'field' => 'rekomendasi_keterangan',
				'label' => 'Dasar Penetapan Peluang',
				'rules' => "required",
			);
		}

		if ($this->access_role['review']) {
			$return['review_kepatuhan'] = array(
				'field' => 'review_kepatuhan',
				'label' => 'Review Kepatuhan',
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

		if ($this->post['act'] == 'save_rekomendasi') {
			unset($return['id_kriteria_dampak']);
			unset($return['id_kriteria_kemungkinan']);
			unset($return['inheren_dampak']);
			unset($return['inheren_kemungkinan']);
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

		$this->viewlist = "panelbackend/opp_peluanglist";
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

		// dpr($this->access_role, 1);
		$this->_beforeDetail($id_scorecard);

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['peluang_sendiri'])
			$this->data['peluang_sendiri'] = $_SESSION[SESSION_APP][$this->page_ctrl]['peluang_sendiri'];

		if ($this->post['act'] == "filter_sendiri") {
			$_SESSION[SESSION_APP][$this->page_ctrl]['peluang_sendiri'] = $this->post['peluang_sendiri'];
			redirect(current_url());
		}

		$this->data['ischild'] = array(1, 2);

		if ($this->data['peluang_sendiri'])
			$this->_setFilter("id_scorecard = " . $this->conn->qstr($id_scorecard));
		else {
			$this->data['ischild'] = $ret = $this->oppscorecard->GetChild($id_scorecard);
			if (($ret))
				$this->_setFilter("id_scorecard in (" . implode(",", $ret) . ")");
			else
				$this->_setFilter("id_scorecard = " . $this->conn->qstr($id_scorecard));
		}

		if (1 /*!$_SESSION[SESSION_APP]['tgl_efektif']*/)
			$_SESSION[SESSION_APP]['tgl_efektif'] = date('Y-m-d');

		if ($_SESSION[SESSION_APP]['tgl_efektif']) {

			$this->data['tgl_efektif'] = $tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

			$this->_setFilter(" str_to_date('$tgl_efektif','%Y-%m-%d') between ifnull(tgl_peluang, str_to_date('$tgl_efektif','%Y-%m-%d')) and ifnull(tgl_close-1,str_to_date('$tgl_efektif','%Y-%m-%d'))");
		}

		$this->_setFilter(" deleted_date is null ");

		$this->data['list'] = $this->_getList($page);

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

		$this->View($this->viewlist);
	}

	public function Add($id_scorecard = null)
	{
		$this->Edit($id_scorecard);
	}

	protected function _onDetail($id = null, &$record = array())
	{
		// $this->_accessTask($this->data['rowheader'], $this->data['row'], $this->data['edited']);
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

	public function Edit($id_scorecard = null, $id = null)
	{
		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->_beforeDetail($id_scorecard, $id);
		$this->data['row'] = $this->data['rowheader1'] = $this->model->GetByPk($id);

		// if ($this->access_role['edit'] && $this->data['row']['is_lock'] == '1') {
		// 	$this->data['editedkri'] = true;
		// 	if ($this->post['act'] == 'save_kri') {
		// 		$ret = $this->_delSertKri($id);
		// 		if ($ret)
		// 			SetFlash("suc_msg", "Perubahan berhasil");
		// 		else
		// 			SetFlash("err_msg", "Perubahan gagal");

		// 		redirect("$this->page_ctrl/detail/$id_scorecard/$id");
		// 	}
		// }

		if (!$this->data['row'] && $id)
			$this->NoData();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");
		$kode_aktifitas = $this->data['row']['id_aktifitas'];
		if ($this->post && $this->post['act'] <> 'change') {

			if (!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id);

			$this->data['row'] = array_merge($this->data['row'], $record);
			$this->data['row'] = array_merge($this->data['row'], $this->post);
			if (!$this->data['mtaktifitasarr'][$this->data['row']['id_aktifitas']])
				$this->data['mtaktifitasarr'][$this->data['row']['id_aktifitas']] = $this->data['row']['id_aktifitas'];
		}

		if ($this->post['id_scorecard'] && $this->data['scorecardarr'][$this->post['id_scorecard']]) {
			$id_scorecard = $this->post['id_scorecard'];
			$record['id_jabatan'] = $_SESSION[SESSION_APP]['id_jabatan'];
		}

		if (!$id or $kode_aktifitas <> $this->data['row']['kode_aktifitas']) {
			$this->data['row']['nomor'] = $this->data['no_peluang'] = $this->model->getNomorPeluang(
				$this->data['rowheader']['id_unit'],
				$this->data['row']['id_kpi'],
				$this->data['row']['tgl_peluang'],
				false
			);
		}

		$this->_onDetail($id, $record);

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save' or $this->post['act'] == 'save_rekomendasi' or $this->post['act'] == 'save_review') {

			if ($this->data['row']['is_lock'] == '2' && $this->data['row']['review_is_verified'] <> '2' && $this->data['row']['rekomendasi_is_verified'] <> '2')
				$record['is_lock'] = 1;

			$record['id_scorecard'] = $id_scorecard;

			if (!$id)
				$record['nomor'] = $record['nomor_asli'] = $this->data['no_peluang'];

			if (!$record['tgl_peluang'] && !$id)
				$record['tgl_peluang'] = date('Y-m-d');


			if ($this->access_role['view_all'] && $id) {

				list($tgl, $bulan, $tahun) = explode("-", $record['tgl_peluang']);
				list($tgl, $bulan, $tahun1) = explode("-", $this->data['rowheader1']['tgl_peluang']);

				$id_peluang_sebelum = $this->data['rowheader1']['id_peluang_sebelum'];

				if ($tahun <> $tahun1 && $id_peluang_sebelum) {
					$nomor_asli = $this->conn->GetOne("select nomor_asli 
						from opp_peluang
						where deleted_date is null and date_format(tgl_peluang, '%Y') = " . $this->conn->escape($tahun) . "
						and id_peluang = " . $this->conn->escape($id_peluang_sebelum));

					if ($nomor_asli) {
						$record['nomor_asli'] = $nomor_asli;
					}
				}
			}

			$this->_isValid($record, true);

			$this->_beforeEdit($record, $id);

			$this->_setLogRecord($record, $id);

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
				}
			}

			// $this->model->conn->CompleteTrans();

			if ($return['success']) {
				$this->model->conn->trans_commit();

				$this->backtodraftpeluang($id);

				$this->_afterEditSucceed($id);

				SetFlash('suc_msg', $return['success']);
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

		$this->View($this->viewdetail);
	}

	public function Detail($id_scorecard = null, $id = null)
	{
		$this->_beforeDetail($id_scorecard, $id);

		$this->data['rowheader1'] = $this->data['row'] = $this->model->GetByPk($id);

		$this->_onDetail($id, $record);

		if (!$this->data['row'])
			$this->NoData();

		$this->_afterDetail($id);
		$this->View($this->viewdetail);
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
				$k = str_replace("_____", ".", $k);

				if (!($v === '' or $v === null or $v === false)) {
					replaceSingleQuote($v);
					replaceSingleQuote($k);

					$filter_arr[] = "$k='$v'";
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
				} else if ($v !== '') {
					$v = strtolower($v);
					$filter_arr[] = "lower($k) like '%$v%'";
				}
			}
		}

		$this->data['filter_arr'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_search'], $_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']);

		if (($filter_arr)) {
			$this->filter .= ' and ' . implode(' and ', $filter_arr);
		}

		return $this->filter;
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

	protected function _beforeDetail($id = null, $id_peluang = null)
	{

		if (!$id)
			redirect('panelbackend/opp_scorecard');

		#mengambil dari model karena sudah difilter sesuai akses
		$this->data['rowheader']  = $this->oppscorecard->GetByPk($id);
		if (!$this->data['rowheader'])
			$this->NoData();

		$owner = $this->data['rowheader']['owner'];

		if ($owner) {
			$this->data['ownerarr'][$owner] = $this->conn->GetOne("select concat(nama,' (',ifnull(id_unit,''),')') from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($owner));



			$bawahanarr = jabatan_bawahan($this->data['rowheader']['owner'], $this->data['rowheader']['id_unit']);

			$addfilter = "";

			if (count($bawahanarr) <= 100 && !empty($bawahanarr)) {
				$addfilter = " and id_jabatan in (" . implode(", ", $bawahanarr) . ")";
			}

			$this->data['pejabatarr'] = array('' => '') + $this->conn->GetList("select 
			id_jabatan as idkey, 
			concat(nama,' (',ifnull(id_unit,''),')') as val
			from mt_sdm_jabatan a
			where deleted_date is null and id_unit = " . $this->conn->escape($this->data['rowheader']['id_unit']) . $addfilter);

			$this->data['pejabatarr'][$owner] = $this->data['ownerarr'][$owner];


			$this->load->model("Risk_sasaranModel", "msasaran");

			$this->data['sasaranarr'] = $this->msasaran->GetCombo($owner);

			$this->data['sasaranarr'][$id_sasaran] = $this->msasaran->GetNama($id_sasaran);
		}

		$this->data['add_param'] .= $id;

		$this->data['files'] = array();
		$rows = $this->conn->GetArray("select id_scorecard_files as id, client_name as nama from opp_scorecard_files where deleted_date is null and id_scorecard = " . $this->conn->escape($id));
		foreach ($rows as $r) {
			$this->data['files']['name'][] = $r['nama'];
			$this->data['files']['id'][] = $r['id'];
		}
		$this->data['scorecardarr'] = $this->oppscorecard->GetComboChild($id);

		// $this->conn->debug = 1;
		$this->_getListTask($this->data['rowheader']);
		// dpr($this->data['task_scorecard'], 1);
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

		$this->conn->Execute("update opp_peluang_kelayakan set deleted_date = now() where id_peluang = " . $this->conn->escape($id));

		return true;
	}

	protected function _afterDetail($id)
	{

		$this->isLock();

		$this->data['editedheader1'] = $this->data['edited'];

		$this->data['rowheader1'] = $this->data['row'];

		$this->data['files'] = $this->modelfile->GArray('*', "where jenis='file' and id_peluang = " . $this->conn->escape($id));

		$this->data['filerekomendasi'] = $this->modelfile->GArray('*', "where jenis='filerekomendasi' and id_peluang = " . $this->conn->escape($id));

		foreach ($this->data['kelayakanarr'] as $k => $v) {
			if ($this->modelfile) {
				if (!$this->data['row']['filekelayakan' . $k]['id'] && $id) {
					$rows = $this->conn->GetArray("select *
				from {$this->modelfile->table}
				where deleted_date is null and jenis = 'filekelayakan" . $k . "' and {$this->model->pk} = " . $this->conn->escape($id));

					foreach ($rows as $r) {
						$this->data['row']['filekelayakan' . $k]['id'][] = $r[$this->modelfile->pk];
						$this->data['row']['filekelayakan' . $k]['name'][] = $r['client_name'];
					}
				}
			}
		}

		if (!$this->data['row']['id_kelayakan'])
			$this->data['row']['id_kelayakan'] = $this->conn->GetList("select id_kelayakan as idkey, 
			id_kelayakan as val 
			from opp_peluang_kelayakan where deleted_date is null and id_peluang = " . $this->conn->escape($id));

		if ($id) {
			$this->data['mode'] = 'edit_detail';
		}

		$this->data['peluangunitarr'] = array();
		if (trim($this->data['rowheader']['id_unit']) == '1') {
			$rows = $this->conn->GetArray("select a.*, b.nama as scorecard from opp_peluang a 
			join opp_scorecard b on a.id_scorecard = b.id_scorecard
			where deleted_date is null and trim(lower(b.id_unit)) <> '1'");
			foreach ($rows as $r) {
				$this->data['peluangunitarr'][$r['id_peluang']] = $r;
			}
		}

		if (!$this->data['row']['id_peluang_unit']) {
			$this->data['row']['id_peluang_unit'] = $this->conn->GetList("select 
			id_peluang_unit as idkey, id_peluang_unit as val 
			from opp_peluang_unit 
			where deleted_date is null and id_peluang = " . $this->conn->escape($id));
		}
	}

	private function _delSertPeluangUnit($id)
	{
		$return = $this->conn->Execute("update opp_peluang_unit set deleted_date = now() where id_peluang = " . $this->conn->escape($id));

		if (is_array($this->post['id_peluang_unit'])) {
			foreach ($this->post['id_peluang_unit'] as $idkey => $value) {
				if ($return) {
					if (!$value)
						continue;

					$record = array();
					$record['id_peluang'] = $id;
					$record['id_peluang_unit'] = $value;

					$sql = $this->conn->InsertSQL("opp_peluang_unit", $record);

					if ($sql) {
						$return = $this->conn->Execute($sql);
					}
				}
			}
		}

		return $return;
	}

	private function rentetan($id_peluang)
	{
		$data = array();
		$data['peluang'] = $this->conn->GetRow("select r.id_peluang_sebelum, r.id_scorecard, r.nomor, r.nama, 
		r.deskripsi, r.inheren_dampak, r.inheren_kemungkinan, r.control_dampak_penurunan, 
		r.control_kemungkinan_penurunan, r.penyebab, r.dampak, r.residual_target_dampak, 
		r.residual_target_kemungkinan, r.residual_dampak_evaluasi, r.residual_kemungkinan_evaluasi,
		r.progress_capaian_sasaran, r.progress_capaian_kinerja, r.hambatan_kendala, r.penyesuaian_tindakan_mitigasi, 
		r.status_peluang, r.status_keterangan, 
		kd.nama as nk, r.tgl_peluang, r.tgl_close
			from opp_peluang r
			left join mt_opp_kriteria_dampak kd on r.id_kriteria_dampak = kd.id_kriteria_dampak
			where r.deleted_date is null and r.id_peluang=" . $this->conn->escape($id_peluang) . "
			and (status_peluang = '0' or status_peluang = '2')");

		if (!$data['peluang'])
			return false;

		$data['scorecard'] = $this->conn->GetRow("select s.nama, s.scope, j.nama as nj, k.nama as nkr
			from opp_scorecard s
			left join mt_sdm_jabatan j on trim(s.owner) = trim(j.id_jabatan)
			left join opp_scorecard k on s.id_parent_scorecard = k.id_scorecard
			where s.deleted_date is null and s.id_scorecard = " . $this->conn->escape($data['peluang']['id_scorecard']));

		return $data;
	}

	private function peluangSebelum(&$ret, $id_peluang_sebelum)
	{
		if (!$id_peluang_sebelum)
			return;

		$peluang = $this->conn->GetRow("select id_peluang_sebelum 
			from opp_peluang 
			where deleted_date is null and id_peluang = " . $this->conn->escape($id_peluang_sebelum));

		if ($peluang['id_peluang_sebelum']) {
			$this->peluangSebelum($ret, $peluang['id_peluang_sebelum']);
			$retet = $this->rentetan($peluang['id_peluang_sebelum']);
			if ($retet)
				$ret[] = $retet;
		}
	}

	private function peluangSesudah(&$ret, $id_peluang_sebelum)
	{
		if (!$id_peluang_sebelum)
			return;

		$peluang = $this->conn->GetRow("select id_peluang 
			from opp_peluang 
			where deleted_date is null and id_peluang_sebelum = " . $this->conn->escape($id_peluang_sebelum));

		if ($peluang['id_peluang']) {
			$retet = $this->rentetan($peluang['id_peluang']);
			if ($retet) {
				$ret[] = $retet;
				$this->peluangSesudah($ret, $peluang['id_peluang']);
			}
		}
	}

	function log_history($id_peluang = null)
	{
		$this->data['width_page'] = "900px";
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";

		$this->data['excel'] = false;

		$this->data['page_title'] = 'Riwayat Peluang';

		$data = array();

		$this->peluangSebelum($data, $id_peluang);

		$retet = $this->rentetan($id_peluang);
		if ($retet)
			$data[] = $retet;

		$this->peluangSesudah($data, $id_peluang);

		$this->data['rows'] = $data;

		$this->View("panelbackend/opp_peluang_log");
	}

	protected function _afterUpdate($id)
	{
		$ret = $this->_afterInsert($id);

		return $ret;
	}

	protected function _afterInsert($id)
	{
		// $ret = $this->_delSertJabatan($id);
		$ret = $this->_delSertKelayakan($id);

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
			$this->oppchangelog($this->data['row'], $this->data['rowold']);
		}

		if ($ret)
			$ret = $this->_delSertPeluangUnit($id);


		if ($this->modelfile) {
			foreach ($this->data['kelayakanarr'] as $k1 => $v1) {
				if (!empty($this->post['filekelayakan' . $k1])) {
					foreach ($this->post['filekelayakan' . $k1]['id'] as $k => $v) {
						$return = $this->_updateFiles(array($this->pk => $id), $v);

						$ret = $return['success'];
					}
				}
			}
		}

		return $ret;
	}

	private function _delSertKelayakan($id)
	{
		$return = $this->conn->Execute("update opp_peluang_kelayakan set deleted_date = now() where id_peluang = " . $this->conn->escape($id));

		if (is_array($this->post['id_kelayakan'])) {
			foreach ($this->post['id_kelayakan'] as $idkey => $value) {
				if ($return) {
					if (!$value)
						continue;

					$record = array();
					$record['id_peluang'] = $id;
					$record['id_kelayakan'] = $value;

					$sql = $this->conn->InsertSQL("opp_peluang_kelayakan", $record);

					if ($sql) {
						$return = $this->conn->Execute($sql);
					}
				}
			}
		}

		return $return;
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

	function _deleteFile($id_peluang = null, $jenis = null)
	{
		$rows = $this->conn->GetArray("select * from opp_peluang_files where deleted_date is null and id_peluang = " . $this->conn->escape($id_peluang) . " and jenis = " . $this->conn->escape($jenis));

		$return = array('success' => true);

		foreach ($rows as $idkey => $value) {
			$id_file = $value['id_peluang_files'];
			$file_name = $value['file_name'];

			$return = $this->modelfile->Delete("id_peluang_files = " . $this->conn->escape($id_file));

			if ($return) {
				$full_path = $this->data['configfile']['upload_path'] . $file_name;
				unlink($full_path);
			}
		}

		return $return;
	}

	function _uploadFile($id_peluang = null, $jenis = 'file')
	{
		$return = array('success' => true);

		$cek = $this->conn->GetOne("select 1 from opp_peluang_files where deleted_date is null and jenis = '$jenis' and id_peluang = " . $this->conn->escape($id_peluang));

		if (!$_FILES[$jenis]['name'] && !$cek)
			return array('error' => "Lampiran wajib di isi.");

		if (!$_FILES[$jenis]['name'])
			return array('success' => "Update berhasil");

		$this->data['configfile']['file_name'] = 'efektifitas_peluang' . time() . $_FILES[$jenis]['name'];

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
			$record['id_peluang'] = $id_peluang;
			$record['jenis'] = $jenis;
			$ret = $this->modelfile->Insert($record);
			if (!$ret['success']) {
				unlink($upload_data['full_path']);
				$return = $ret;
			}
		}

		return $return;
	}

	function delete_file($id_peluang = null, $id_file = null)
	{
		$row = $this->model->GetByPk($id_peluang);
		$file_name = $this->modelfile->GetOne("select file_name from opp_peluang_files where deleted_date is null and id_peluang_files = " . $this->conn->escape($id_file));

		$return = $this->modelfile->Delete("id_peluang_files = " . $this->conn->escape($id_file));

		if ($return) {
			$full_path = $this->data['configfile']['upload_path'] . $file_name;
			unlink($full_path);

			SetFlash('suc_msg', $return['success']);
		} else {
			SetFlash('err_msg', "Data gagal didelete");
		}
		redirect("$this->page_ctrl/edit/$row[id_scorecard]/$id_peluang");
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

		$this->View("panelbackend/opp_peluang_proses");
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
				$ret = $this->conn->Execute("update opp_scorecard set proses = " . $this->conn->escape($upload_data['client_name']) . " where id_scorecard = " . $this->conn->escape($id));

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
}
