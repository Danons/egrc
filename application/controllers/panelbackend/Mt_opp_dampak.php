<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_opp_dampak extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_opp_dampaklist";
		$this->viewdetail = "panelbackend/mt_opp_dampakdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Tingkat Dampak';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Tingkat Dampak';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Tingkat Dampak';
			$this->data['edited'] = false;
		}else{
			$this->data['page_title'] = 'Tingkat Dampak';
		}

		$this->load->model("Mt_opp_dampakModel","model");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
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
				'name'=>'rating',
				'label'=>'Nilai',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'nama',
				'label'=>'Tingkat Dampak',
				'width'=>"auto",
				'type'=>"varchar2",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'nama'=>$this->post['nama'],
			'kode'=>$this->post['kode'],
			'keterangan'=>$this->post['keterangan'],
			'rating'=>$this->post['rating'],
			'mulai'=>$this->post['mulai'],
			'sampai'=>$this->post['sampai'],
		);
	}

	protected function Rules(){
		return array(
			"nama"=>array(
				'field'=>'nama',
				'label'=>'Tingkat Dampak',
				'rules'=>"required|max_length[300]",
			),
			"kode"=>array(
				'field'=>'kode',
				'label'=>'Kode',
				'rules'=>"max_length[300]",
			),
			"rating"=>array(
				'field'=>'rating',
				'label'=>'Nilai',
				'rules'=>"required|numeric",
			),
			"keterangan"=>array(
				'field'=>'keterangan',
				'label'=>'Keterangan',
				'rules'=>"max_length[4000]",
			),
		);
	}

}
