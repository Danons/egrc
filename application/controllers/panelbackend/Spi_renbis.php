<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Spi_renbis extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/spi_renbislist";
		$this->viewdetail = "panelbackend/spi_renbisdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_profil_spi";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Rencana Audit Jangka Panjang';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Rencana Audit Jangka Panjang';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Rencana Audit Jangka Panjang';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Rencana Audit Jangka Panjang';
		}

		$this->load->model("Spi_program_auditModel", "model");

		$this->load->model('Risk_risikoModel', 'risikoModel');
		$this->data['risikoArr'] = $this->risikoModel->GetCombo();


		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'select2'
		);
	}

	public function Index($page = 0)
	{
		$this->data['header'] = $this->Header();

		// $this->conn->debug = 1;
		$this->data['list'] = $this->_getList($page);

		$this->_setFilter("deleted_date is null ");
		$this->data['list']['rows'] = $this->conn->GetArray("select * from spi_program_audit where deleted_date is null and " . $this->_getFilter());

		// die();

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

	public function Detail($id = null)
	{

		$id = urldecode($id);
		$this->_beforeDetail($id);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$this->_onDetail($id);

		$this->_afterDetail($id);


		$this->View($this->viewdetail);
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

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}


	// $risikoarr = $this->conn->GetArray("select id_risiko as idkey, nama as val from risk_risiko");
	// 	dpr($risikoarr, 1);
	// 	// $this->data['risk_risikoarr'] =

	protected function Header()
	{
		return array(
			array(
				'name' => 'nama_audit',
				'label' => 'Auditi',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'tanggal_lhe',
				'label' => 'Tanggal LHE',
				'width' => 'auto',
				'type' => 'date',

			),
			array(
				'name' => 'id_risiko',
				'label' => 'Risk Risiko',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['risikoArr'],
			),
			array(
				'name' => 'frekuensi_audit',
				'label' => 'Frekuensi Audit',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'jenis_audit',
				'label' => 'Jenis Audit',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'tahun',
				'label' => 'Tahun',
				'width' => "auto",
				'type' => "varchar",
			),

		);
	}



	protected function Record($id = null)
	{

		return array(
			'nama_audit' => $this->post['nama_audit'],
			'id_risiko' => $this->post['id_risiko'],
			'tanggal_lhe' => $this->post['tanggal_lhe'],
			'jenis_audit' => $this->post['jenis_audit'],
			'frekuensi_audit' => $this->post['frekuensi_audit'],
			'tahun' => $this->post['tahun'][0],
		);
	}

	protected function Rules()
	{
		return array(
			"nama_audit" => array(
				'field' => 'nama_audit',
				'label' => 'Auditi',
				'rules' => "max_length[200]",
			),
			"frekuensi_audit" => array(
				'field' => 'frkuensi_audit',
				'label' => 'Frek Audit',
				'rules' => "max_length[200]",
			),
			"jenis_audit" => array(
				'field' => 'jenis_audit',
				'label' => 'Jenis Audit',
				'rules' => "max_length[200]",
			),

		);
	}
}
