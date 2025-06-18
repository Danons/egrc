<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_aspek_lingkungan extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_aspek_lingkunganlist";
		$this->viewdetail = "panelbackend/mt_aspek_lingkungandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Prioritas';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Prioritas';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Prioritas';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Prioritas';
		}

		$this->load->model("Mt_aspek_lingkunganModel","model");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->SetPlugin('jscolor');
	}

	protected function Header(){
		return array(
			array(
				'name'=>'kode', 
				'label'=>'Kode', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'nama', 
				'label'=>'Nama', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'keterangan', 
				'label'=>'Keterangan', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'kode'=>$this->post['kode'],
			'nama'=>$this->post['nama'],
			'keterangan'=>$this->post['keterangan'],
		);
	}

	protected function Rules(){
		return array(
			"kode"=>array(
				'field'=>'kode', 
				'label'=>'Kode', 
				'rules'=>"max_length[20]",
			),
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"max_length[20]",
			),
			"keterangan"=>array(
				'field'=>'keterangan', 
				'label'=>'Keterangan', 
				'rules'=>"text",
			),
		);
	}

}