<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Template extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/templatelist";
		$this->viewdetail = "panelbackend/templatedetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Template';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Template';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Template';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Template';
		}

		$this->load->model("TemplateModel", "model");

		$this->data['configfile'] = $this->config->item('file_upload_config');
		$this->data['configfile']['allowed_types'] = 'xlsx|xls|doc|docx';
		$this->config->set_item("file_upload_config", $this->data['configfile']);

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'upload'
		);
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'no_template',
				'label' => 'No Template',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'nama',
				'label' => 'Nama Template',
				'width' => "auto",
				'type' => "varchar",
			),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'no_template' => $this->post['no_template'],
			'nama' => $this->post['nama'],
		);
	}

	protected function Rules()
	{
		return array(
			"no_template" => array(
				'field' => 'no_template',
				'label' => 'No Template',
				'rules' => "max_length[45]",
			),
			"nama" => array(
				'field' => 'nama',
				'label' => 'Nama Template',
				'rules' => "max_length[200]",
			),
		);
	}


	public function Add()
	{
		$this->Edit($this->post['file']['id']);
	}

	protected function _afterDetail($id)
	{
		$this->data['row']['id'] = $this->data['row'][$this->model->pk];
		$this->data['row']['name'] = $this->data['row']['client_name'];
	}

	protected function _afterInsert($id)
	{
		return true;
	}

	protected function _openFiles($id = null, $nameid = null)
	{
		$row = $this->model->GetByPk($id);
		if ($row) {
			if ($row['file_url']) {
				// redirect("https://dsmt.jasatirta2.co.id/Documents/viewpdf/" . $row['file_url']);
				redirect("https://dsmt.jasatirta2.co.id/assets/uploads/" . $row['file_url']);
				die();
			} else {
				$full_path = $this->data['configfile']['upload_path'] . $row['file_name'];
				$str = file_get_contents($full_path);
				header("Content-Type: {$row['file_type']}");
				header("Content-Disposition: inline; filename=\"{$row['client_name']}\"");
				header('Content-length: ' . strlen($str));
				echo $str;
				die();
			}
		} else {
			$this->Error404();
		}
	}

	protected function _deleteFiles($id)
	{
		$row = $this->model->GetByPk($id);

		if (!$row)
			$this->Error404();

		$file_name = $row['file_name'];

		$record = array();
		$record['client_name'] = "";
		$record['file_name'] = "";
		$record['file_type'] = "";
		$record['file_size'] = "";
		$record['jenis_file'] = "";
		$return = $this->model->Update($record, $this->model->pk . "=" . $this->conn->escape($id));

		if ($return) {
			$full_path = $this->data['configfile']['upload_path'] . $file_name;
			@unlink($full_path);

			return array("success" => true);
		} else {
			return array("error" => "File " . $row['client_name'] . " gagal dihapus");
		}
	}

	protected function _uploadFiles($jenis_file = null, $id = null)
	{

		$name = $_FILES[$jenis_file]['name'];

		$this->data['configfile']['file_name'] = $jenis_file . time() . $name;

		$this->load->library('upload', $this->data['configfile']);

		if (!$this->upload->do_upload($jenis_file)) {
			$return = array('error' => "File $name gagal upload, " . strtolower(str_replace(array("<p>", "</p>"), "", $this->upload->display_errors())));
		} else {
			$upload_data = $this->upload->data();

			$record = array();
			$record['client_name'] = $upload_data['client_name'];
			$record['file_name'] = $upload_data['file_name'];
			$record['file_type'] = $upload_data['file_type'];
			$record['file_size'] = $upload_data['file_size'];
			$record['jenis_file'] = $record['jenis'] = str_replace("upload", "", $jenis_file);
			$record[$this->pk] = $id;

			if ($record[$this->pk])
				$ret = $this->model->Update($record, $this->model->pk . "=" . $this->conn->escape($id));
			else
				$ret = $this->model->Insert($record);

			if ($ret['success']) {
				$return = array('file' => array("id" => $ret['data'][$this->model->pk], "name" => $upload_data['client_name']));
			} else {
				unlink($upload_data['full_path']);
				$return = array('errors' => "File $name gagal upload");
			}
		}

		return $return;
	}
}
