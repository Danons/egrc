<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_jenis_audit_eksternal extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_jenis_audit_eksternallist";
		$this->viewdetail = "panelbackend/mt_jenis_audit_eksternaldetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Jenis Audit Eksternal';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Jenis Audit Eksternal';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Jenis Audit Eksternal';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Jenis Audit Eksternal';
		}

		$this->load->model("Mt_jenis_audit_eksternalModel","model");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'nama', 
				'label'=>'Nama', 
				'width'=>"auto",
				'type'=>"varchar",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'nama'=>$this->post['nama'],
		);
	}

	protected function Rules(){
		return array(
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"required|max_length[200]",
			),
		);
	}

}