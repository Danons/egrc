<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Kpi_individu extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/kpi_individulist";
		$this->viewdetail = "panelbackend/kpi_individudetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_profil_kpi";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah KPI Individu';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit KPI Individu';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail KPI Individu';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar KPI Individu';
		}

		$this->load->model("Kpi_individuModel", "model");

		$this->load->model("public_sys_userModel", 'modelUser');
		if ($this->Access("view_all")) {
			$this->data['userArr'] = ['' => 'pilih..'] + $this->modelUser->GetCombo();
		} else {
			$this->data['userArr'] = $this->conn->GetList("select user_id as idkey, name as val from public_sys_user where deleted_date is null and user_id = " . $_SESSION['SESSION_APP_EGRC']['user_id']);
		}
		$this->load->model("mt_kategori_kpi_individuModel", 'modelKategori');
		$this->data['kategoriArr'] = $this->modelKategori->GetCombo();
		$this->load->model("Mt_target_kpiModel", "targetModel");
		$this->data['targetArr'] = $this->targetModel->GetCombo();

		$this->data['statusArr'] = array('' => 'pilih...', '1' => 'Draft', '2' => 'Setuju', '3' => 'Tidak Setuju');

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'select2'
		);
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'pegawai',
				'label' => 'Pegawai',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'target',
				'label' => 'Target',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'kategori',
				'label' => 'Kategori',
				'width' => "auto",
				'type' => "varchar",
				// 'value' => $this->data['kategoriArr'],
			),
			array(
				'name' => 'is_setuju_langsung',
				'label' => 'Setuju Atasan Langsung',
				'width' => "auto",
				'type' => "list",
				'value' => array('' => '-pilih-', '1' => 'Draft', '2' => 'Setuju', '3' => 'Tidak Setuju'),
			),
			array(
				'name' => 'is_setuju_tidak_langsung',
				'label' => 'Setuju Atasan Tidak Langsung',
				'width' => "auto",
				'type' => "list",
				'value' => array('' => '-pilih-', '1' => 'Draft', '2' => 'Setuju', '3' => 'Tidak Setuju'),
			),
		);
	}

	protected function Record($id = null)
	{
		$pegawai = $this->conn->GetOne('select name from public_sys_user where deleted_date is null and user_id = ' . $this->conn->escape($this->post['id_pegawai']));
		$kategori = $this->conn->GetOne('select nama from mt_kategori_kpi_individu where deleted_date is null and id_kategori = ' . $this->conn->escape($this->post['id_kategori']));
		if (!$this->post['is_setuju']) {
			$this->post['is_setuju'] = 1;
		}
		return array(
			'pegawai' => $pegawai,
			'target' => $this->post['target'],
			'id_pegawai' => $this->post['id_pegawai'],
			'kategori' => $kategori,
			'id_kategori' => $this->post['id_kategori'],
			'is_setuju_langsung' => $this->post['is_setuju'],
			'is_setuju_tidak_langsung' => $this->post['is_setuju'],
		);
	}

	protected function Rules()
	{
		return array(
			"pegawai" => array(
				'field' => 'pegawai',
				'label' => 'Pegawai',
				'rules' => "max_length[100]",
			),
			"id_pegawai" => array(
				'field' => 'id_pegawai',
				'label' => 'Pegawai',
				'rules' => "integer|max_length[10]",
			),
			"kategori" => array(
				'field' => 'kategori',
				'label' => 'Kategori',
				'rules' => "max_length[50]",
			),
			"is_setuju" => array(
				'field' => 'is_setuju',
				'label' => 'IS Setuju',
				'rules' => "integer|max_length[10]",
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

			// dpr($this->post['target'], 1);

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

	public function Index($page = 0)
	{
		$this->data['header'] = $this->Header();

		if ($this->post['act'] = "set_value") {
			if ($this->post['is_setuju_langsung']) {
				foreach ($this->post['is_setuju_langsung'] as $id => $val) {
					$arr = ["is_setuju_langsung" => $val];
					$ret = $this->model->Update($arr, "id_kpi = " . $id);
				}
			}
			if ($this->post['is_setuju_tidak_langsung']) {
				foreach ($this->post['is_setuju_tidak_langsung'] as $id => $val) {
					$arr = ["is_setuju_tidak_langsung" => $val];
					$ret = $this->model->Update($arr, "id_kpi = " . $id);
				}
			}
		}

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
}
