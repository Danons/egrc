<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Mt_bidang_pemeriksaan extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/mt_bidang_pemeriksaanlist";
		$this->viewdetail = "panelbackend/mt_bidang_pemeriksaandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Bidang Pemeriksaan';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Bidang Pemeriksaan';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Bidang Pemeriksaan';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Bidang Pemeriksaan';
		}

		$this->load->model("Mt_bidang_pemeriksaanModel", "model");

		$this->data['jenispemeriksaanarr'] = [
			"" => "",
			"operasional" => "Operasional",
			"mutu" => "Mutu",
			"penyuapan" => "Penyuapan",
			"khusus" => "Khusus",
			"eksternal" => "Eksternal",
		];

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
				'type' => "varchar",
			),
			array(
				'name' => 'jenis',
				'label' => 'Jenis',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['jenispemeriksaanarr']
			),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'nama' => $this->post['nama'],
			'jenis' => $this->post['jenis'],
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
			"jenis" => array(
				'field' => 'jenis',
				'label' => 'Jenis',
				'rules' => "required|max_length[45]",
			),
		);
	}
}
