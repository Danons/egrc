<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Mt_pemeriksaan_checklist extends _adminController
{

	public $limit = -1;
	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/mt_pemeriksaan_checklistlist";
		$this->viewdetail = "panelbackend/mt_pemeriksaan_checklistdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Checklist Pemeriksaan ';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Checklist Pemeriksaan ';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Checklist Pemeriksaan ';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Checklist Pemeriksaan ';
		}

		$this->load->model("Mt_pemeriksaan_checklistModel", "model");

		$this->load->model("Mt_pemeriksaan_checklistModel", "mtpemeriksaanchecklist");
		$this->data['mtpemeriksaanchecklistarr'] = $this->mtpemeriksaanchecklist->GetCombo();

		$this->data['jenisarr'] = [
			'' => '',
			'Perencanaan' => 'Perencanaan',
			'Penyelesaian' => 'Penyelesaian',
			'Laporan' => 'Laporan'
		];

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'treetable'
		);
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'nama',
				'label' => 'Nama',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'jenis',
				'label' => 'Jenis',
				'width' => "auto",
				'type' => "varchar",
			),
			// array(
			// 	'name' => 'id_checklist_parent',
			// 	'label' => 'Checklist Parent',
			// 	'width' => "auto",
			// 	'type' => "list",
			// 	'value' => $this->data['mtpemeriksaanchecklistarr'],
			// ),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'nama' => $this->post['nama'],
			'id_checklist_parent' => $this->post['id_checklist_parent'],
			'jenis' => $this->post['jenis'],
		);
	}

	protected function Rules()
	{
		return array(
			"nama" => array(
				'field' => 'nama',
				'label' => 'Nama',
				'rules' => "required|max_length[500]",
			),
			"id_checklist_parent" => array(
				'field' => 'id_checklist_parent',
				'label' => 'Checklist Parent',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtpemeriksaanchecklistarr'])) . "]|max_length[10]",
			),
			"jenis" => array(
				'field' => 'jenis',
				'label' => 'Jenis',
				'rules' => "max_length[50]",
			),
		);
	}
}
