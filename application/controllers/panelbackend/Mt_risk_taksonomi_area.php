<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Mt_risk_taksonomi_area extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/mt_risk_taksonomi_arealist";
		$this->viewdetail = "panelbackend/mt_risk_taksonomi_areadetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Taksonomi Area';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Taksonomi Area';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Taksonomi Area';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Taksonomi Area';
		}

		$this->data['width'] = "1000px";

		$this->load->model("Mt_risk_taksonomi_areaModel", "model");
		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker'
		);
	}

	public function Add($id_taksonomi_objective = null)
	{
		$this->Edit($id_taksonomi_objective);
	}

	protected function _beforeDetail($id_taksonomi_objective = null, $id = null)
	{
		$this->data['add_param'] .= $id_taksonomi_objective;
	}

	public function Edit($id_taksonomi_objective = null, $id = null)
	{

		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->_beforeDetail($id_taksonomi_objective, $id);

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

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {
			$record['id_taksonomi_objective'] = $id_taksonomi_objective;
			$record['jenis'] = $this->conn->GetOne("select jenis from mt_risk_taksonomi_objective where deleted_date is null and id_taksonomi_objective = " . $this->conn->escape($id_taksonomi_objective));

			$this->_isValid($record, false);

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

				SetFlash('suc_msg', $return['success']);
				redirect("$this->page_ctrl/detail/$id");
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

	public function Delete($id = null)
	{

		$this->model->conn->StartTrans();

		$this->data['row'] = $this->model->GetByPk($id);

		$this->_beforeDetail($this->data['row']['id_taksonomi_objective'], $id);

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

		if ($return) {

			$this->log("menghapus", $this->data['row']);

			SetFlash('suc_msg', $return['success']);
			redirect("$this->page_ctrl");
		} else {
			SetFlash('err_msg', "Data gagal didelete");
			redirect("$this->page_ctrl/detail/" . $this->data['row']['id_taksonomi_objective'] . "/$id");
		}
	}

	protected function Header()
	{
		redirect("panelbackend/mt_risk_taksonomi");
	}

	public function Detail($id = null)
	{
		redirect("panelbackend/mt_risk_taksonomi");;
	}

	protected function Record($id = null)
	{
		return array(
			'kode' => $this->post['kode'],
			'nama' => $this->post['nama'],
			'keterangan' => $this->post['keterangan'],
			'tgl_mulai_efektif' => $this->post['tgl_mulai_efektif'],
			'tgl_akhir_efektif' => $this->post['tgl_akhir_efektif'],
			'jenis' => implode("/", $this->post['jenis']),
		);
	}

	protected function Rules()
	{
		return array(
			"kode" => array(
				'field' => 'kode',
				'label' => 'Kode',
				'rules' => "required|max_length[5]",
			),
			"nama" => array(
				'field' => 'nama',
				'label' => 'Nama',
				'rules' => "required|max_length[200]",
			),
		);
	}
}
