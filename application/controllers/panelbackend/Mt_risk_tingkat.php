<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_risk_tingkat extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_risk_tingkatlist";
		$this->viewdetail = "panelbackend/mt_risk_tingkatdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Pengendalian Risiko';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Pengendalian Risiko';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Pengendalian Risiko';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Pengendalian Risiko';
		}

		$this->load->model("Mt_risk_tingkatModel","model");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->SetPlugin('jscolor');
	}

	protected function Header(){
		return array(
			array(
				'name'=>'nama', 
				'label'=>'Nama', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'warna', 
				'label'=>'Warna', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'penanganan', 
				'label'=>'Keterangan', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			// array(
			// 	'name'=>'nama_peluang', 
			// 	'label'=>'Nama Peluang', 
			// 	'width'=>"auto",
			// 	'type'=>"varchar2",
			// ),
			// array(
			// 	'name'=>'warna_peluang', 
			// 	'label'=>'Warna Peluang', 
			// 	'width'=>"auto",
			// 	'type'=>"varchar2",
			// ),
			// array(
			// 	'name'=>'penanganan_peluang', 
			// 	'label'=>'Penanganan Peluang', 
			// 	'width'=>"auto",
			// 	'type'=>"varchar2",
			// ),
		);
	}

	protected function Record($id=null){
		return array(
			'nama'=>$this->post['nama'],
			'warna'=>"#".$this->post['warna'],
			'penanganan'=>$this->post['penanganan'],
			'nama_peluang'=>$this->post['nama_peluang'],
			'warna_peluang'=>"#".$this->post['warna_peluang'],
			'penanganan_peluang'=>$this->post['penanganan_peluang'],
		);
	}

	protected function Rules(){
		return array(
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"max_length[20]",
			),
			"warna"=>array(
				'field'=>'warna', 
				'label'=>'Warna', 
				'rules'=>"max_length[20]",
			),
			"penanganan"=>array(
				'field'=>'penanganan', 
				'label'=>'Penanganan', 
				'rules'=>"max_length[4000]",
			),
		);
	}

}