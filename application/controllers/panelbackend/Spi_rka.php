<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Spi_rka extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/spi_rkalist";
		$this->viewdetail = "panelbackend/spi_rkadetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_profil_spi";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah SPI RKA';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit SPI RKA';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail SPI RKA';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar SPI RKA';
		}

		$this->load->model("Spi_rkaModel", "model");

		$this->load->model("Risk_kpiModel", "kpimodel");
		$this->data['kpiarr'] = $this->kpimodel->GetCombo();

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
				'name' => 'id_kpi',
				'label' => 'KPI',
				'width' => "auto",
				'type' => "number",
			),
			array(
				'name' => 'polaritas_minimal',
				'label' => 'Polaritas Minimal',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'polaritas_maksimal',
				'label' => 'Polaritas Maksimal',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'nilai',
				'label' => 'Nilai',
				'width' => "auto",
				'type' => "number",
			),
			array(
				'name' => 'satuan',
				'label' => 'Satuan',
				'width' => "auto",
				'type' => "number",
			),
			array(
				'name' => 'tahun',
				'label' => 'Tahun',
				'width' => "auto",
				'type' => "number",
			),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'id_kpi' => $this->post['id_kpi'],
			'polaritas_minimal' => $this->post['polaritas_minimal'],
			'polaritas_maksimal' => $this->post['polaritas_maksimal'],
			'nilai' => $this->post['nilai'],
			'satuan' => $this->post['satuan'],
			'tahun' => $this->post['tahun'],
		);
	}

	protected function Rules()
	{
		return array(
			"id_kpi" => array(
				'field' => 'id_kpi',
				'label' => 'KPI',
				'rules' => "integer|max_length[10]",
			),
			"polaritas_minimal" => array(
				'field' => 'polaritas_minimal',
				'label' => 'Polaritas Minimal',
				'rules' => "max_length[50]",
			),
			"polaritas_maksimal" => array(
				'field' => 'polaritas_maksimal',
				'label' => 'Polaritas Maksimal',
				'rules' => "max_length[50]",
			),
			"nialai" => array(
				'field' => 'nilai',
				'label' => 'Nilai',
				'rules' => "integer|max_length[10]",
			),
			"satuan" => array(
				'field' => 'satuan',
				'label' => 'Satuan',
				'rules' => "integer|max_length[10]",
			),
			"tahun" => array(
				'field' => 'tahun',
				'label' => 'Tahun',
				'rules' => "integer|max_length[10]",
			),
		);
	}
}
