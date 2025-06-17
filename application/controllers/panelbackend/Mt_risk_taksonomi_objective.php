<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Mt_risk_taksonomi_objective extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/mt_risk_taksonomi_objectivelist";
		$this->viewdetail = "panelbackend/mt_risk_taksonomi_objectivedetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Taksonomi Objective';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Taksonomi Objective';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Taksonomi Objective';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Taksonomi Objective';
		}

		$this->data['width'] = "800px";

		$this->load->model("Mt_risk_taksonomi_objectiveModel", "model");
		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker'
		);
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
			// dpr($record,1);

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

	protected function _afterDetail($id)
	{
		if ($this->modelfile) {
			if (!$this->data['row']['files']['id'] && $id) {
				$rows = $this->conn->GetArray("select *
				from {$this->modelfile->table}
				where deleted_date is null and {$this->model->pk} = " . $this->conn->escape($id));

				foreach ($rows as $r) {
					$this->data['row']['files']['id'][] = $r[$this->modelfile->pk];
					$this->data['row']['files']['name'][] = $r['client_name'];
				}
			}
		}


		// $jenis = explode("/",$this->conn->GetOne("select jenis from mt_risk_taksonomi_area where id_taksonomi_objective = ".$this->conn->escape($this->data['row']['id_taksonomi_objective'])));

		$jenis = explode("/", $this->data['row']['jenis']);
		if (!is_array($jenis))
			$jenis = [$this->data['row']['jenis']];

		$this->data['row']['jenis'] = [];
		foreach ($jenis as $g) {
			$this->data['row']['jenis'][$g] = $g;
		}
	}
	protected function _afterInsert($id)
	{
		$ret = true;

		// if ($this->modelfile) {
		// 	if (!empty($this->post['files'])) {
		// 		foreach ($this->post['files']['id'] as $k => $v) {
		// 			$return = $this->_updateFiles(array($this->pk => $id), $v);

		// 			$ret = $return['success'];
		// 		}
		// 	}
		// }

		if ($this->post['jenis']) {
			$jenis = implode("/", $this->post['jenis']);

			$this->conn->goUpdate("mt_risk_taksonomi_area", ["jenis" => $jenis], "id_taksonomi_objective = " . $this->conn->escape($id));
		}

		return $ret;
	}
}
