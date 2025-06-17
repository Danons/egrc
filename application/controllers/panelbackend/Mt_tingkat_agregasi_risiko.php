<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Mt_tingkat_agregasi_risiko extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/mt_tingkat_agregasi_risikolist";
		$this->viewdetail = "panelbackend/mt_tingkat_agregasi_risikodetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Tingkat Agregasi Risiko';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Tingkat Agregasi Risiko';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Tingkat Agregasi Risiko';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Tingkat Agregasi Risiko';
		}

		$this->load->model("Mt_tingkat_agregasi_risikoModel", "model");

		$this->data['agregasiarr'] = ['' => ''] + $this->model->GetCombo();

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
				'name' => 'id_tingkat_agregasi_risiko_parent',
				'label' => 'Tingkat Agregasi Risiko Parent',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['agregasiarr']
			),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'nama' => $this->post['nama'],
			'id_tingkat_agregasi_risiko_parent' => $this->post['id_tingkat_agregasi_risiko_parent'],
		);
	}

	protected function Rules()
	{
		return array(
			"nama" => array(
				'field' => 'nama',
				'label' => 'Nama',
				'rules' => "max_length[100]",
			),
			"id_tingkat_agregasi_risiko_parent" => array(
				'field' => 'id_tingkat_agregasi_risiko_parent',
				'label' => 'Tingkat Agregasi Risiko Parent',
				'rules' => "integer|max_length[4]",
			),
		);
	}
}
