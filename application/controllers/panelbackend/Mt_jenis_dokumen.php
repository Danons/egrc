<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_jenis_dokumen extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_jenis_dokumenlist";
		$this->viewdetail = "panelbackend/mt_jenis_dokumendetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Jenis Dokumen';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Jenis Dokumen';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Jenis Dokumen';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Jenis Dokumen';
		}

		$this->load->model("Mt_jenis_dokumenModel","model");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'is_ppd', 
				'label'=>'PPD', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>array(''=>'-pilih-','0'=>'Tidak','1'=>'Iya'),
			),
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
			'is_ppd'=>(int)$this->post['is_ppd'],
			'nama'=>$this->post['nama'],
		);
	}

	protected function Rules(){
		return array(
			"is_ppd"=>array(
				'field'=>'is_ppd', 
				'label'=>'IS PPD', 
				'rules'=>"integer|max_length[10]",
			),
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"max_length[45]",
			),
		);
	}

}