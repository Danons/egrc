<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Survey extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/surveylist";
		$this->viewdetail = "panelbackend/surveydetail";
		$this->viewprint = "panelbackend/surveyprint";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Risk & Underwriting Survey';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Risk & Underwriting Survey';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Risk & Underwriting Survey';
			$this->data['edited'] = false;
		} else {
			$this->layout = "panelbackend/layout2";
			$this->data['page_title'] = 'Daftar Risk & Underwriting Survey';
		}

		$this->data['width'] = "2800px";

		$this->load->model("SurveyModel", "model");
		$this->load->model("Mt_periode_twModel", "mtperiodetw");
		$this->data['mtperiodetwarr'] = $this->mtperiodetw->GetCombo();
		$this->load->model("Survey_filesModel", "modelfile");
		$this->load->model("Mt_pelaksana_surveyModel", "mtpelaksanasurvey");
		$this->data['mtpelaksanasurveyarr'] = $this->mtpelaksanasurvey->GetCombo();


		$this->load->model("Mt_jenis_surveyModel", "mtjenissurvey");
		$this->data['mtjenissurveyarr'] = $this->mtjenissurvey->GetCombo();
		$this->data['mtjenissurveyarr'][''] = '-Jenis Survey-';

		$this->load->model("Mt_sdm_unitModel", "mtsdmunit");
		$this->data['mtsdmunitarr'] = $this->mtsdmunit->GetCombo();

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'upload'
		);
		$this->data['configfile'] = $this->config->item('file_upload_config');
		$this->data['configfile']['allowed_types'] = 'jpg|png|jpeg';
		$this->config->set_item("file_upload_config", $this->data['configfile']);

		$tgl_efektif = date('Y-m-d');
		list($tahun, $bln, $tgl) = explode("-", $tgl_efektif);
		$this->data['id_periode_tw_current'] = $this->conn->GetOne("select id_periode_tw from mt_periode_tw where deleted_date is null and '$bln' between bulan_mulai and bulan_akhir");

		$this->access_role['list_print'] = true;
	}

	protected function Header()
	{

		if ($this->post['act'] == "set_filter") {
			$_SESSION[SESSION_APP][$this->page_ctrl]['id_periode_tw_filter'] = $this->post['id_periode_tw_filter'];
			$_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'] = $this->post['tahun_filter'];
			redirect(current_url());
		}

		$tgl_efektif = date('Y-m-d');
		// if ($_SESSION[SESSION_APP]['tgl_efektif']) {
		// 	$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		// }

		list($tahun, $bln, $tgl) = explode("-", $tgl_efektif);
		$tahun_current = $tahun;
		if ($_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'])
			$tahun = $_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'];

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['id_periode_tw_filter'])
			$id_periode_tw = $_SESSION[SESSION_APP][$this->page_ctrl]['id_periode_tw_filter'];

		if (!$id_periode_tw)
			$id_periode_tw = $this->data['id_periode_tw_current'];


		$_SESSION[SESSION_APP][$this->page_ctrl]['id_periode_tw_filter'] = $id_periode_tw;
		$_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'] = $tahun;

		$this->data['tahun_filter'] = $tahun;
		$this->data['id_periode_tw_filter'] = $id_periode_tw;

		// $this->_setFilter("tahun = " . $this->conn->escape($tahun) . " and id_periode_tw = " . $this->conn->escape($id_periode_tw));

		if ($tahun_current <> $tahun)
			$this->_setFilter("(date_format(tgl,'%Y') = " . $this->conn->escape($tahun) . "  or date_format(tgl_selesai,'%Y') = " . $this->conn->escape($tahun) . ")");
		else
			$this->_setFilter("((date_format(tgl,'%Y') < " . $this->conn->escape($tahun) . " and is_selesai <> 1) or date_format(tgl,'%Y') = " . $this->conn->escape($tahun) . "  or date_format(tgl_selesai,'%Y') = " . $this->conn->escape($tahun) . ")");

		$return = array(
			// array(
			// 	'name' => 'id_unit',
			// 	'label' => 'Distrik',
			// 	'width' => "auto",
			// 	'type' => "list",
			// 	'value' => $this->data['mtsdmunitarr'],
			// ),
			array(
				'name' => 'area',
				'label' => 'Area',
				'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'nomor_rekomendasi',
				'label' => 'Nomor Rekomendasi',
				'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'tgl',
				'label' => 'Tgl.',
				'width' => "auto",
				'type' => "date",
			),
			array(
				'name' => 'surveyor',
				'label' => 'Surveyor',
				'width' => "auto",
				'type' => "varchar2",
			),
			// array(
			// 	'name' => 'id_jenis_survey',
			// 	'label' => 'Jenis Survey',
			// 	'width' => "auto",
			// 	'type' => "list",
			// 	'value' => $this->data['mtjenissurveyarr'],
			// ),
			array(
				'name' => 'id_pelaksana_survey',
				'label' => 'Pelaksana Survey',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['mtpelaksanasurveyarr'],
			),
			array(
				'name' => 'kondisi_temuan',
				'label' => 'Kondisi Temuan',
				'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'uraian_rekomendasi',
				'label' => 'Uraian Rekomendasi',
				'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'tanggapan_manajemen',
				'label' => 'Tanggapan Manajemen',
				'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'rencana_tindak_lanjut',
				'label' => 'Rencana Tindak Lanjut',
				'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'realisasi_tindak_lanjut',
				'label' => 'Realisasi Tindak Lanjut',
				'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'is_selesai',
				'label' => 'Status Tindak Lanjut',
				'width' => "auto",
				'type' => "list",
				'value' => array('' => '-pilih-', '0' => 'Dalam Proses', '1' => 'Selesai'),
			),
		);

		if (!$this->access_role['view_all_unit']) {
			// unset($return[0]);
			$this->_setFilter("id_unit = " . $this->conn->escape($_SESSION[SESSION_APP]['id_unit']));
		}

		return $return;
	}

	protected function Record($id = null)
	{
		if (!$this->access_role['view_all_unit']) {
			$_POST['id_unit'] = $this->post['id_unit'] = $_SESSION[SESSION_APP]['id_unit'];
		}

		$return = array(
			'id_unit' => $this->post['id_unit'],
			'area' => $this->post['area'],
			'nomor_rekomendasi' => $this->post['nomor_rekomendasi'],
			'tgl' => $this->post['tgl'],
			'surveyor' => $this->post['surveyor'],
			'id_jenis_survey' => Rupiah2Number($this->post['id_jenis_survey']),
			'id_pelaksana_survey' => Rupiah2Number($this->post['id_pelaksana_survey']),
			'kondisi_temuan' => $this->post['kondisi_temuan'],
			'uraian_rekomendasi' => $this->post['uraian_rekomendasi'],
			'tanggapan_manajemen' => $this->post['tanggapan_manajemen'],
			'rencana_tindak_lanjut' => $this->post['rencana_tindak_lanjut'],
			'realisasi_tindak_lanjut' => $this->post['realisasi_tindak_lanjut'],
			'is_selesai' => (int)$this->post['is_selesai'],
		);

		if ($return['is_selesai'])
			$return['tgl_selesai'] = date("Y-m-d");

		return $return;
	}

	protected function _afterInsert($id)
	{
		return $this->_afterUpdate($id);
	}

	protected function _afterUpdate($id)
	{
		$ret = $this->conn->Execute("delete
		from survey_triwulan 
		where id_survey = " . $this->conn->escape($id) . "
		and id_periode_tw = " . $this->conn->escape($this->data['id_periode_tw_current']) . "
		and tahun = " . $this->conn->escape(date("Y")));

		if ($ret) {
			$record = $this->model->GetByPk($id);
			$record['id_periode_tw'] = $this->data['id_periode_tw_current'];
			$record['tahun'] = date("Y");
			$ret = $this->conn->goInsert("survey_triwulan", $record);
		}


		if ($this->modelfile && $ret) {
			if (!empty($this->post['files'])) {
				foreach ($this->post['files']['id'] as $k => $v) {
					$return = $this->_updateFiles(array($this->pk => $id), $v);

					$ret = $return['success'];
				}
			}
		}

		return $ret;
	}

	protected function Rules()
	{
		return array(
			"id_unit" => array(
				'field' => 'id_unit',
				'label' => 'Unit',
				'rules' => "required|in_list[" . implode(",", array_keys($this->data['mtsdmunitarr'])) . "]|max_length[18]",
			),
			"area" => array(
				'field' => 'area',
				'label' => 'Area',
				'rules' => "required|max_length[100]",
			),
			"tgl" => array(
				'field' => 'tgl',
				'label' => 'Tanggal',
				'rules' => "required|max_length[100]",
			),
			"nomor_rekomendasi" => array(
				'field' => 'nomor_rekomendasi',
				'label' => 'Nomor Rekomendasi',
				'rules' => "required|max_length[200]",
			),
			"surveyor" => array(
				'field' => 'surveyor',
				'label' => 'Surveyor',
				'rules' => "required|max_length[200]",
			),
			"id_jenis_survey" => array(
				'field' => 'id_jenis_survey',
				'label' => 'Jenis Survey',
				'rules' => "required|in_list[" . implode(",", array_keys($this->data['mtjenissurveyarr'])) . "]|max_length[10]",
			),
			"id_pelaksana_survey" => array(
				'field' => 'id_pelaksana_survey',
				'label' => 'Pelaksana Survey',
				'rules' => "required|in_list[" . implode(",", array_keys($this->data['mtpelaksanasurveyarr'])) . "]|max_length[10]",
			),
			"kondisi_temuan" => array(
				'field' => 'kondisi_temuan',
				'label' => 'Kondisi Temuan',
				'rules' => "required|max_length[4000]",
			),
			"uraian_rekomendasi" => array(
				'field' => 'uraian_rekomendasi',
				'label' => 'Uraian Rekomendasi',
				'rules' => "max_length[4000]",
			),
			"tanggapan_manajemen" => array(
				'field' => 'tanggapan_manajemen',
				'label' => 'Tanggapan Manajemen',
				'rules' => "max_length[4000]",
			),
			"rencana_tindak_lanjut" => array(
				'field' => 'rencana_tindak_lanjut',
				'label' => 'Rencana Tindak Lanjut',
				'rules' => "max_length[4000]",
			),
			"realisasi_tindak_lanjut" => array(
				'field' => 'realisasi_tindak_lanjut',
				'label' => 'Realisasi Tindak Lanjut',
				'rules' => "max_length[4000]",
			),
			"is_selesai" => array(
				'field' => 'is_selesai',
				'label' => 'IS Selesai',
				'rules' => "numeric|max_length[1]",
			),
		);
	}

	public function go_print()
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout2";
		$this->data['no_header'] = true;

		$this->data['header'] = $this->Header();

		$this->data['list'] = $this->_getListPrint();
		$this->data['tahun'] = $_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'];
		$this->data['id_periode_tw'] = $_SESSION[SESSION_APP][$this->page_ctrl]['id_periode_tw_filter'];
		$id_unit = $_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']['id_unit'];
		if (!$this->access_role['view_all_unit']) {
			$id_unit = $_SESSION[SESSION_APP]['id_unit'];
		}
		$this->data['nama_unit'] = $this->conn->GetOne("select table_desc from mt_sdm_unit where deleted_date is null and table_code = " . $this->conn->escape($id_unit));

		foreach ($this->data['list']['rows'] as &$r1) {
			$rows = $this->conn->GetArray("select *
			from {$this->modelfile->table}
			where deleted_date is null and {$this->model->pk} = " . $this->conn->escape($r1[$this->model->pk]));

			foreach ($rows as $r) {
				$r1['files'][] = $r;
			}
		}

		$this->View($this->viewprint);
	}

	protected function _beforeDelete($id = null)
	{
		$ret = true;
		if ($this->modelfile) {
			$ret = $this->conn->Execute("update {$this->modelfile->table} set deleted_date = now() where {$this->pk} = " . $this->conn->escape($id));
		}

		if ($ret) {
			$ret = $this->conn->Execute("update survey_triwulan set deleted_date = now() where id_survey = " . $this->conn->escape($id));
		}
		return $ret;
	}
}
