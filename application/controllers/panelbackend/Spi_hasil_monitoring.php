<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Spi_hasil_monitoring extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/spi_hasil_monitoringlist";
		$this->viewdetail = "panelbackend/spi_hasil_monitoringdetail";
		$this->viewprintdetail = "panelbackend/spi_audit_evaluasidetailprint";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Catatan Audit Evaluasi';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Catatan Audit Evaluasi';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Catatan Audit Evaluasi';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Catatan Audit Evaluasi';
		}

		$this->load->model("Spi_audit_evaluasiModel", "model");

		$manajerarr = $this->conn->GetList("SELECT user_id AS idkey, NAME AS val FROM public_sys_user WHERE deleted_date is null and id_jabatan = 3267");
		$this->data['manajerarr'] = ['' => ''] + $manajerarr;

		$this->data['statusarr'] = array('' => '-pilih-', '2' => 'Revisi',  '4' => "Benar");

		$this->access_role['catatan'] = 1;
		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'tinymce', 'select2'
		);
	}

	public function Index($page = 0)
	{
		$this->data['header'] = $this->Header();

		$this->_setFilter("status in (1,3)");
		$this->data['list'] = $this->_getList($page);

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

	protected function Header()
	{
		return array(
			array(
				'name' => 'tanggal',
				'label' => 'Tanggal',
				'width' => "auto",
				'type' => "date",
			),
			array(
				'name' => 'nomor',
				'label' => 'Nomor',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'catatan',
				'label' => 'Catatan',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'status',
				'label' => 'Status',
				'width' => "auto",
				'type' => "list",
				'value' => array('' => '-pilih-', '1' => 'Draft', '3' => 'Sudah di Revisi'),
			),
			array(
				'name' => 'dokumen',
				'label' => 'Dokumen',
				'width' => "auto",
				'type' => "varchar",
				'filter' => false
			),
			// array(
			// 	'name' => 'simpulan',
			// 	'label' => 'Simpulan',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// ),
			// array(
			// 	'name' => 'saran',
			// 	'label' => 'saran',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// ),
			// array(
			// 	'name' => 'dasar_tugas',
			// 	'label' => 'Dasar Tugas',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// ),
			// array(
			// 	'name' => 'dasar_evaluasi',
			// 	'label' => 'Dasar Evaluasi',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// ),
			// array(
			// 	'name' => 'cakupan_evaluasi',
			// 	'label' => 'Cakupan Evaluasi',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// ),
			// array(
			// 	'name' => 'informasi_umum',
			// 	'label' => 'Informasi Umum',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// ),
			// array(
			// 	'name' => 'hasil_evaluasi',
			// 	'label' => 'Hasil Evaluasi',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// ),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'catatan' => $this->post['catatan'],
			'status' => $this->post['status'],
		);
	}

	protected function Rules()
	{
		return array(
			"nomor" => array(
				'field' => 'nomor',
				'label' => 'Nomor',
				'rules' => "max_length[50]",
			),
			"lampiran" => array(
				'field' => 'lampiran',
				'label' => 'Lampiran',
				'rules' => "max_length[200]",
			),
			"hal" => array(
				'field' => 'hal',
				'label' => 'Hal',
				'rules' => "max_length[200]",
			),
			"simpulan" => array(
				'field' => 'simpulan',
				'label' => 'Simpulan',
				'rules' => "",
			),
			"saran" => array(
				'field' => 'saran',
				'label' => 'saran',
				'rules' => "",
			),
			"dasar_tugas" => array(
				'field' => 'dasar_tugas',
				'label' => 'Dasar Tugas',
				'rules' => "",
			),
			"dasar_evaluasi" => array(
				'field' => 'dasar_evaluasi',
				'label' => 'Dasar Evaluasi',
				'rules' => "",
			),
			"cakupan_evaluasi" => array(
				'field' => 'cakupan_evaluasi',
				'label' => 'Cakupan Evaluasi',
				'rules' => "",
			),
			"informasi_umum" => array(
				'field' => 'informasi_umum',
				'label' => 'Informasi Umum',
				'rules' => "",
			),
			"hasil_evaluasi" => array(
				'field' => 'hasil_evaluasi',
				'label' => 'Hasil Evaluasi',
				'rules' => "",
			),
		);
	}


	public function Edit($id = null)
	{

		$id = urldecode($id);
		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->_beforeDetail($id);

		$this->data['idpk'] = $id;

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'] && $id)
			$this->NoData();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");

		if ($this->post && $this->post['act'] <> 'change') {
			if (!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id);

			$this->data['row'] = array_merge($this->data['row'], $record);
			$this->data['row'] = array_merge($this->data['row'], $this->post);
		}

		$this->_onDetail($id);

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {

			$this->_isValid($record, true);

			$this->_beforeEdit($record, $id);

			$this->_setLogRecord($record, $id);

			$this->model->conn->StartTrans();
			if (trim($this->data['row'][$this->pk]) == trim($id) && trim($id)) {

				$return = $this->_beforeUpdate($record, $id);

				if ($return) {
					$return = $this->model->Update($record, "$this->pk = " . $this->conn->qstr($id));
				}

				if ($return['success']) {

					$this->log("mengubah", $this->data['row']);

					$return1 = $this->_afterUpdate($id);

					if (!$return1) {
						$return = false;
					}
				}
			} else {

				$return = $this->_beforeInsert($record);

				if ($return) {
					$return = $this->model->Insert($record);
					$id = $return['data'][$this->pk];
				}

				if ($return['success']) {

					$this->log("menambah", $record);

					$return1 = $this->_afterInsert($id);

					if (!$return1) {
						$return = false;
					}
				}
			}

			$this->model->conn->CompleteTrans();

			if ($return['success']) {

				$this->_afterEditSucceed($id);

				if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
					echo json_encode(array("success" => true, "data" => array("key" => $this->pk, "val" => $id)));
					exit();
				} else {
					SetFlash('suc_msg', $return['success']);
					redirect("$this->page_ctrl/detail/$id");
				}
			} else {
				$this->data['row'] = array_merge($this->data['row'], $record);
				$this->data['row'] = array_merge($this->data['row'], $this->post);

				$this->_afterEditFailed($id);

				$this->data['err_msg'] = "Data gagal disimpan";
			}
		}

		// dpr($this->data['buttonMenu'], 1);
		$this->_afterDetail($id);
		$this->View($this->viewdetail);
	}

	private function _setHari($hari = null)
	{
		switch ($hari) {
			case 'Sun':
				return $hari_ini = "Minggu";
				break;

			case 'Mon':
				return $hari_ini = "Senin";
				break;

			case 'Tue':
				return $hari_ini = "Selasa";
				break;

			case 'Wed':
				return $hari_ini = "Rabu";
				break;

			case 'Thu':
				return $hari_ini = "Kamis";
				break;

			case 'Fri':
				return $hari_ini = "Jumat";
				break;

			case 'Sat':
				return $hari_ini = "Sabtu";
				break;

			default:
				return $hari_ini = "Tidak di ketahui";
				break;
		}
	}
}
