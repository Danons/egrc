<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Spi_program_audit extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/spi_program_auditlist";
		$this->viewdetail = "panelbackend/spi_program_auditdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah SPI Program Audit';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit SPI Program Audit';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail SPI Program Audit';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar SPI Program Audit';
		}

		$this->load->model("Spi_program_auditModel", "model");

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
				'name' => 'nama_audit',
				'label' => 'Nama Auditi',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'id_risk_risiko',
				'label' => 'Risk Risiko',
				'width' => "auto",
				'type' => "number",
			),
			array(
				'name' => 'sarana_kendaraan',
				'label' => 'Sarana Kendaraan',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'sarana_lainnya',
				'label' => 'Sarana Lainnya',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'dana_sppd',
				'label' => 'Dana Sppd',
				'width' => "auto",
				'type' => "number",
			),
			array(
				'name' => 'dana_lainnya',
				'label' => 'Dana Lainnya',
				'width' => "auto",
				'type' => "number",
			),
			array(
				'name' => 'lain-lain',
				'label' => 'Lain-lain',
				'width' => "auto",
				'type' => "text",
			),
			array(
				'name' => 'tahun',
				'label' => 'Tahun',
				'width' => "auto",
				'type' => "number",
			),
			array(
				'name' => 'jenis_audit',
				'label' => 'Jenaudit',
				'width' => "auto",
				'type' => "list",
				'value' => array('' => '-pilih-', '0' => 'Tidak', '1' => 'Iya'),
			),
			array(
				'name' => 'frekuensi_audit',
				'label' => 'Frekuensi Audit',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'minggu_mulai',
				'label' => 'Minggu Mulai',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'minggu_selesai',
				'label' => 'Minggu Selesai',
				'width' => "auto",
				'type' => "varchar",
			),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'nama_audit' => $this->post['nama_audit'],
			'id_risk_risiko' => $this->post['id_risk_risiko'],
			'sarana_kendaraan' => $this->post['sarana_kendaraan'],
			'sarana_lainnya' => $this->post['sarana_lainnya'],
			'dana_sppd' => $this->post['dana_sppd'],
			'dana_lainnya' => $this->post['dana_lainnya'],
			'lain-lain' => $this->post['lain-lain'],
			'tahun' => $this->post['tahun'],
			'jenis_audit' => $this->post['jenis_audit'],
			'frekuensi_audit' => $this->post['frekuensi_audit'],
			'minggu_mulai' => $this->post['minggu_mulai'],
			'minggu_selesai' => $this->post['minggu_selesai'],
		);
	}

	protected function Rules()
	{
		return array(
			"nama_audit" => array(
				'field' => 'nama_audit',
				'label' => 'Nama Auditi',
				'rules' => "max_length[200]",
			),
			"id_risk_risiko" => array(
				'field' => 'id_risk_risiko',
				'label' => 'Risk Risiko',
				'rules' => "integer|max_length[10]",
			),
			"sarana_kendaraan" => array(
				'field' => 'sarana_kendaraan',
				'label' => 'Sarana Kendaraan',
				'rules' => "max_length[200]",
			),
			"sarana_lainnya" => array(
				'field' => 'sarana_lainnya',
				'label' => 'Sarana Lainnya',
				'rules' => "max_length[200]",
			),
			"dana_sppd" => array(
				'field' => 'dana_sppd',
				'label' => 'Dana Sppd',
				'rules' => "numeric|max_length[10]",
			),
			"dana_lainnya" => array(
				'field' => 'dana_lainnya',
				'label' => 'Dana Lainnya',
				'rules' => "numeric|max_length[10]",
			),
			"lain-lain" => array(
				'field' => 'lain-lain',
				'label' => 'Lain-lain',
				'rules' => "max_length[10]",
			),
			"tahun" => array(
				'field' => 'tahun',
				'label' => 'Tahun',
				'rules' => "integer|max_length[10]",
			),
			"jenis_audit" => array(
				'field' => 'jenis_audit',
				'label' => 'Jenis Audit',
				'rules' => "max_length[200]",
			),
			"frekuensi_audit" => array(
				'field' => 'frekuensi_audit',
				'label' => 'Frekuensi Audit',
				'rules' => "max_length[200]",
			),
			"minggu_mulai" => array(
				'field' => 'minggu_mulai',
				'label' => 'Minggu Mulai',
				'rules' => "max_length[50]",
			),
			"minggu_selesai" => array(
				'field' => 'minggu_selesai',
				'label' => 'Minggu Selesai',
				'rules' => "max_length[50]",
			),
		);
	}
}
