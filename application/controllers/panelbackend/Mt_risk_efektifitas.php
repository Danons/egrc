<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Mt_risk_efektifitas extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/mt_risk_efektifitaslist";
		$this->viewdetail = "panelbackend/mt_risk_efektifitasdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Efektifitas Kontrol';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Efektifitas Kontrol';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Efektifitas Kontrol';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Efektifitas Kontrol';
		}

		$this->load->model("Mt_risk_efektifitasModel", "model");

		$this->load->model("Mt_risk_efektifitas_jawabanModel", "jawaban");
		$this->data['mtjawabanarr'] = $this->jawaban->GetCombo();

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'nama',
				'label' => 'Nama',
				'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'need_lampiran',
				'label' => 'Lampiran ?',
				'width' => "auto",
				'type' => "list",
				'value' => array('' => '-pilih-', '0' => 'Tidak', '1' => 'Iya'),
			),
			array(
				'name' => 'need_explanation',
				'label' => 'Penjelasan ?',
				'width' => "auto",
				'type' => "list",
				'value' => array('' => '-pilih-', '0' => 'Tidak', '1' => 'Iya'),
			),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'nama' => $this->post['nama'],
			'need_lampiran' => (int)$this->post['need_lampiran'],
			'need_explanation' => (int)$this->post['need_explanation'],
		);
	}

	protected function Rules()
	{
		return array(
			"nama" => array(
				'field' => 'nama',
				'label' => 'Nama',
				'rules' => "required|max_length[200]",
			),
		);
	}

	protected function _afterUpdate($id)
	{
		$ret = $this->_delSertbobot($id);
		return $ret;
	}

	protected function _afterInsert($id)
	{
		$ret = $this->_delSertbobot($id);
		return $ret;
	}

	private function _delSertbobot($id)
	{

		$return = $this->conn->Execute("update mt_risk_efektifitas_bobot set deleted_date = now() where id_efektifitas = " . $this->conn->escape($id));

		if (is_array($this->post['bobot'])) {
			foreach ($this->post['bobot'] as $idkey => $value) {
				if ($return) {
					if (!$value)
						continue;

					$record = array();
					$record['id_efektifitas'] = $id;
					$record['id_efektifitas_jawaban'] = $idkey;
					$record['bobot'] = $value;

					$sql = $this->conn->InsertSQL("mt_risk_efektifitas_bobot", $record);

					if ($sql) {
						$return = $this->conn->Execute($sql);
					}
				}
			}
		}

		return $return;
	}

	protected function _beforeDelete($id = null)
	{
		$return = $this->model->Execute("update mt_risk_efektifitas_bobot set deleted_date = now() where id_efektifitas = " . $this->conn->escape($id));

		return $return;
	}


	protected function _afterDetail($id)
	{
		if (!($this->data['row']['bobot'])) {
			$bobotarr = array();

			$bobotarr = $this->conn->GetArray("select id_efektifitas_jawaban, bobot from mt_risk_efektifitas_bobot where deleted_date is null id_efektifitas = " . $this->conn->escape($id));

			foreach ($bobotarr as $idkey => $value) {
				$bobotarr[$value['id_efektifitas_jawaban']] = $value['bobot'];
			}

			$this->data['row']['bobot'] = $bobotarr;
		}
	}
}
