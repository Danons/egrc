<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Pemeriksaan_memo extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/pemeriksaan_memolist";
		$this->viewdetail = "panelbackend/pemeriksaan_memodetail";
		$this->viewprintdetail = "panelbackend/pemeriksaan_memo_printdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Pemeriksaan Memo';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Pemeriksaan Memo';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Pemeriksaan Memo';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Pemeriksaan Memo';
		}

		$this->load->model("Pemeriksaan_memoModel", "model");

		$this->load->model('public_sys_userModel', "userModel");
		$this->data['userarr'] = ['' => ''] + $this->userModel->GetCombo();

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'select2'
		);
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'dari',
				'label' => 'Dari',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'ke',
				'label' => 'ke',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'tempat',
				'label' => 'tempat',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'direksi',
				'label' => 'direksi',
				'width' => "auto",
				'type' => "varchar",
			),
			// array(
			// 	'name' => 'isi',
			// 	'label' => 'ISI',
			// 	'width' => "auto",
			// 	'type' => "text",
			// ),
			array(
				'name' => 'tanggal_surat',
				'label' => 'Tanggal Surat',
				'width' => "auto",
				'type' => "date",
			),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'dari' => $this->post['dari'],
			'ke' => $this->post['ke'],
			'isi' => $this->post['isi'],
			'tanggal_surat' => $this->post['tanggal_surat'],
			'tempat' => $this->post['tempat'],
			'direksi' => $this->post['direksi'],
		);
	}

	protected function Rules()
	{
		return array(
			"dari" => array(
				'field' => 'dari',
				'label' => 'Dari',
				'rules' => "required",
			),
			"ke" => array(
				'field' => 'ke',
				'label' => 'ke',
				'rules' => "required",
			),
			"isi" => array(
				'field' => 'isi',
				'label' => 'ISI',
				'rules' => "required",
			),
		);
	}

	public function printdetail($id, $id_pemeriksaan = null)
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout5";
		$this->data['no_header'] = true;

		$this->data['row'] = $this->model->GetByPk($id);

		$this->_getDetailPrint($id);

		if (!$this->data['row'])
			$this->NoData();

		$this->View($this->viewprintdetail);
	}
}
