<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Spi_sasaran extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/spi_sasaranlist";
		$this->viewdetail = "panelbackend/spi_sasarandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_profil_spi";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Tujuan, Sasaran, Strategi pengawasan';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Tujuan, Sasaran, Strategi pengawasan';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Tujuan, Sasaran, Strategi pengawasan';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Tujuan, Sasaran, Strategi pengawasan';
		}

		$this->load->model("Spi_sasaranModel", "model");
		$this->load->model("Mt_sdm_jabatanModel", "mtjabatan");
		$this->data['jabatanarr'] = ['' => ''] + $this->mtjabatan->GetCombo();


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
				'name' => 'sasaran',
				'label' => 'TUJUAN, SASARAN, DAN STRATEGI',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'id_jabatan',
				'label' => 'PENANGGUNG JAWAB SASARAN DAN STRATEGI',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['jabatanarr'],
			),
			array(
				'name' => 'misi',
				'label' => 'MISI',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'keterangan',
				'label' => 'KETERANGAN',
				'width' => "auto",
				'type' => "text",
			),
			array(
				'name' => 'tahun',
				'label' => 'TAHUN',
				'width' => "auto",
				'type' => "number",
			),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'sasaran' => $this->post['sasaran'],
			'id_jabatan' => $this->post['id_jabatan'],
			'misi' => $this->post['misi'],
			'keterangan' => $this->post['keterangan'],
			'tahun' => $this->post['tahun'],
		);
	}

	protected function Rules()
	{
		return array(
			"sasaran" => array(
				'field' => 'sasaran',
				'label' => 'Sasaran',
				'rules' => "max_length[200]",
			),
			"misi" => array(
				'field' => 'misi',
				'label' => 'Misi',
				'rules' => "max_length[2000]",
			),
			"keterangan" => array(
				'field' => 'keterangan',
				'label' => 'Keterangan',
				'rules' => "max_length[10]",
			),
			"tahun" => array(
				'field' => 'tahun',
				'label' => 'Tahun',
				'rules' => "integer|max_length[10]",
			),
		);
	}
	
}
