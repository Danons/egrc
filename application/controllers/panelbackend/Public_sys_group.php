<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Public_sys_group extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/public_sys_grouplist";
		$this->viewdetail = "panelbackend/public_sys_groupdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Group';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Group';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Group';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Group';
		}

		$this->load->model("Public_sys_groupModel", "model");

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
				'name' => 'name',
				'label' => 'Name',
				'width' => "auto",
				'type' => "varchar",
			),
			// array(
			// 	'name' => 'visible',
			// 	'label' => 'Visible',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// ),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'name' => $this->post['name'],
			'visible' => 1,
		);
	}

	protected function Rules()
	{
		return array(
			"name" => array(
				'field' => 'name',
				'label' => 'Name',
				'rules' => "required|max_length[100]",
			),
			"visible" => array(
				'field' => 'visible',
				'label' => 'Visible',
				'rules' => "max_length[1]",
			),
		);
	}
}
