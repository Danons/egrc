<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_jenis_rtm extends _adminController{

	public $limit = -1;
	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_jenis_rtmlist";
		$this->viewdetail = "panelbackend/mt_jenis_rtmdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Jenis RTM';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Jenis RTM';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Jenis RTM';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Jenis RTM';
		}

		$this->load->model("Mt_jenis_rtmModel","model");
		$this->data['mtjenisrtmarr'] = $this->model->GetComboP();

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'treetable'
		);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'jenis_masalah', 
				'label'=>'Jenis Masalah', 
				'width'=>"auto",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'id_jenis_rtm_parent'=>$this->post['id_jenis_rtm_parent'],
			'jenis_masalah'=>$this->post['jenis_masalah'],
		);
	}

	protected function Rules(){
		return array(
			"id_jenis_rtm_parent"=>array(
				'field'=>'id_jenis_rtm_parent', 
				'label'=>'Jenis RTM Parent', 
				'rules'=>"integer|max_length[10]",
			),
			"jenis_masalah"=>array(
				'field'=>'jenis_masalah', 
				'label'=>'Jenis Masalah', 
				'rules'=>"required|max_length[200]",
			),
		);
	}

}