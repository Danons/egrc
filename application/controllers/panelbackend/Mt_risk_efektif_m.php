<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Mt_risk_efektif_m extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/mt_risk_efektif_mlist";
		$this->viewdetail = "panelbackend/mt_risk_efektif_mdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Kriteria';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Kriteria';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Kriteria';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Kriteria';
		}

		$this->data['width'] = "800px";

		$this->load->model("Mt_risk_efektif_mModel", "model");

		$this->load->model("Mt_risk_efektif_m_jawabanModel", "jawaban");
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
		);
	}

	protected function Record($id = null)
	{
		return array(
			'nama' => $this->post['nama'],
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

		$return = $this->conn->Execute("update mt_risk_efektif_m_bobot set deleted_date = now() where id_efektif_m = " . $this->conn->escape($id));

		if (is_array($this->post['bobot'])) {
			foreach ($this->post['bobot'] as $idkey => $value) {
				if ($return) {
					if (!$value)
						continue;

					$record = array();
					$record['id_efektif_m'] = $id;
					$record['id_efektif_m_jawaban'] = $idkey;
					$record['bobot'] = $value;
					$record['rekomendasi'] = $this->post['rekomendasi'][$idkey];

					$sql = $this->conn->InsertSQL("mt_risk_efektif_m_bobot", $record);

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
		$return = $this->model->Execute("update mt_risk_efektif_m_bobot set deleted_date = now() where id_efektif_m = " . $this->conn->escape($id));

		return $return;
	}


	protected function _afterDetail($id)
	{
		if (!($this->data['row']['bobot'])) {
			$bobotarr = array();
			$rekomendasiarr = array();

			$bobotarr = $this->conn->GetArray("select id_efektif_m_jawaban, bobot, rekomendasi from mt_risk_efektif_m_bobot where deleted_date is null and id_efektif_m = " . $this->conn->escape($id));

			foreach ($bobotarr as $idkey => $value) {
				$bobotarr[$value['id_efektif_m_jawaban']] = $value['bobot'];
				$rekomendasiarr[$value['id_efektif_m_jawaban']] = $value['rekomendasi'];
			}

			$this->data['row']['bobot'] = $bobotarr;
			$this->data['row']['rekomendasi'] = $rekomendasiarr;
		}
	}
}
