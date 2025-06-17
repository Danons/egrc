<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Comp_kebutuhan extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/comp_kebutuhanlist";
		$this->viewdetail = "panelbackend/comp_kebutuhandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Kebutuhan';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Kebutuhan';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Kebutuhan';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Kebutuhan';
		}

		$this->load->model("Comp_kebutuhanModel", "model");

		$this->load->model("DokumenModel", "dokumen");
		$this->data['dokumenarr'] = $this->dokumen->GetCombo();



		$this->load->model("Mt_intervalModel", "mtinterval");
		$this->data['mtintervalarr'] = $this->mtinterval->GetCombo();



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
				'name' => 'id_dokumen',
				'label' => 'Dokumen',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['dokumenarr'],
			),
			array(
				'name' => 'nama',
				'label' => 'Nama',
				'width' => "auto",
				'type' => "text",
			),
			array(
				'name' => 'id_interval',
				'label' => 'Interval',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['mtintervalarr'],
			),
			array(
				'name' => 'is_file',
				'label' => 'File',
				'width' => "auto",
				'type' => "list",
				'value' => array('' => '-pilih-', '0' => 'Tidak', '1' => 'Iya'),
			),
			array(
				'name' => 'url',
				'label' => 'URL',
				'width' => "auto",
				'type' => "text",
			),
		);
	}

	protected function Record($id = null)
	{
		$this->post['mapping'] = json_encode($this->post['mapping']);
		return array(
			'nama' => $this->post['nama'],
			'id_interval' => $this->post['id_interval'],
			'id_dokumen' => $this->post['id_dokumen'],
			'is_file' => (int)$this->post['is_file'],
			'url' => $this->post['url'],
			'mapping' => $this->post['mapping'],
		);
	}

	protected function Rules()
	{
		return array(
			"nama" => array(
				'field' => 'nama',
				'label' => 'Nama',
				'rules' => "",
			),
			"id_interval" => array(
				'field' => 'id_interval',
				'label' => 'Interval',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtintervalarr'])) . "]|max_length[10]",
			),
			"id_dokumen" => array(
				'field' => 'id_dokumen',
				'label' => 'Dokumen',
				'rules' => "in_list[" . implode(",", array_keys($this->data['dokumenarr'])) . "]|max_length[10]",
			),
			"is_file" => array(
				'field' => 'is_file',
				'label' => 'IS File',
				'rules' => "integer|max_length[10]",
			),
			"url" => array(
				'field' => 'url',
				'label' => 'URL',
				'rules' => "",
			),
		);
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

		if ($this->data['row']['id_interval']) {
			$this->data['row']['konversi_bulan'] = $this->conn->GetOne("select konversi_bulan 
			from mt_interval 
			where deleted_date is null and id_interval = " . $this->conn->escape($this->data['row']['id_interval']));
		}

		$this->data['row']['mapping'] = json_decode($this->data['row']['mapping'], true);
	}

	protected function _beforeDelete($id = null)
	{
		$ret = true;
		if ($this->modelfile) {
			$ret = $this->conn->Execute("update {$this->modelfile->table} set deleted_date = now() where {$this->pk} = " . $this->conn->escape($id));
		}

		if ($ret) {
			$ret = $this->conn->Execute("update comp_penilaian set deleted_date = now() where id_comp_kebutuhan = " . $this->conn->escape($id));
		}
		return $ret;
	}
}
