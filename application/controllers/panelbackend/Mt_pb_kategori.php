<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_pb_kategori extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_pb_kategorilist";
		$this->viewdetail = "panelbackend/mt_pb_kategoridetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Kategori Proses Bisnis';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Kategori Proses Bisnis';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Kategori Proses Bisnis';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Kategori Proses Bisnis';
		}

		$this->data['width'] = "800px";

		$this->load->model("Mt_pb_kategoriModel","model");
		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker'
		);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'nama', 
				'label'=>'Nama', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			// array(
			// 	'name'=>'tgl_mulai_efektif', 
			// 	'label'=>'Tgl. Mulai Efektif', 
			// 	'width'=>"auto",
			// 	'type'=>"date",
			// ),
			// array(
			// 	'name'=>'tgl_akhir_efektif', 
			// 	'label'=>'Tgl. Akhir Efektif', 
			// 	'width'=>"auto",
			// 	'type'=>"date",
			// ),
			// array(
			// 	'name'=>'kode', 
			// 	'label'=>'Kode', 
			// 	'width'=>"auto",
			// 	'type'=>"varchar2",
			// ),
		);
	}

	protected function Record($id=null){
		return array(
			'nama'=>$this->post['nama'],
			'tgl_mulai_efektif'=>$this->post['tgl_mulai_efektif'],
			'tgl_akhir_efektif'=>$this->post['tgl_akhir_efektif'],
			'kode'=>$this->post['kode'],
		);
	}

	protected function Rules(){
		return array(
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"required|max_length[200]",
			),
			"kode"=>array(
				'field'=>'kode', 
				'label'=>'Kode', 
				'rules'=>"max_length[20]",
			),
		);
	}

}