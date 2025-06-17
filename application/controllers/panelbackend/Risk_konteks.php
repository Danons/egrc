<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Risk_konteks extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/risk_kontekslist";
		$this->viewdetail = "panelbackend/risk_konteksdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Konteks';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Konteks';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Konteks';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Konteks';
		}

		$this->load->model("Risk_konteksModel","model");

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
				'type'=>"varchar",
			),
			array(
				'name'=>'deskripsi', 
				'label'=>'Deskripsi', 
				'width'=>"auto",
				'type'=>"varchar",
			),
			// array(
			// 	'name'=>'strength', 
			// 	'label'=>'Strength', 
			// 	'width'=>"auto",
			// 	'type'=>"varchar",
			// ),
			// array(
			// 	'name'=>'weakness', 
			// 	'label'=>'Weakness', 
			// 	'width'=>"auto",
			// 	'type'=>"varchar",
			// ),
			// array(
			// 	'name'=>'opportunity', 
			// 	'label'=>'Opportunity', 
			// 	'width'=>"auto",
			// 	'type'=>"text",
			// ),
			// array(
			// 	'name'=>'threat', 
			// 	'label'=>'Threat', 
			// 	'width'=>"auto",
			// 	'type'=>"text",
			// ),
			// array(
			// 	'name'=>'konteks_internal', 
			// 	'label'=>'Konteks Internal', 
			// 	'width'=>"auto",
			// 	'type'=>"text",
			// ),
			// array(
			// 	'name'=>'konteks_eksternal', 
			// 	'label'=>'Konteks Eksternal', 
			// 	'width'=>"auto",
			// 	'type'=>"text",
			// ),
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
			// 	'type'=>"timestamp",
			// ),
		);
	}

	protected function Record($id=null){
		return array(
			'nama'=>$this->post['nama'],
			'deskripsi'=>$this->post['deskripsi'],
			// 'strength'=>$this->post['strength'],
			// 'weakness'=>$this->post['weakness'],
			// 'opportunity'=>$this->post['opportunity'],
			// 'threat'=>$this->post['threat'],
			'konteks_internal'=>$this->post['konteks_internal'],
			'konteks_eksternal'=>$this->post['konteks_eksternal'],
			'tgl_mulai_efektif'=>$this->post['tgl_mulai_efektif'],
			'tgl_akhir_efektif'=>$this->post['tgl_akhir_efektif'],
		);
	}

	protected function Rules(){
		return array(
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"required|max_length[4000]",
			),
			"deskripsi"=>array(
				'field'=>'deskripsi', 
				'label'=>'Deskripsi', 
				'rules'=>"required|max_length[4000]",
			),
			"strength"=>array(
				'field'=>'strength', 
				'label'=>'Strength', 
				'rules'=>"max_length[4000]",
			),
			"weakness"=>array(
				'field'=>'weakness', 
				'label'=>'Weakness', 
				'rules'=>"max_length[4000]",
			),
			"opportunity"=>array(
				'field'=>'opportunity', 
				'label'=>'Opportunity', 
				'rules'=>"max_length[10]",
			),
			"threat"=>array(
				'field'=>'threat', 
				'label'=>'Threat', 
				'rules'=>"max_length[10]",
			),
			"konteks_internal"=>array(
				'field'=>'konteks_internal', 
				'label'=>'Konteks Internal', 
				'rules'=>"max_length[10]",
			),
			"konteks_eksternal"=>array(
				'field'=>'konteks_eksternal', 
				'label'=>'Konteks Eksternal', 
				'rules'=>"max_length[10]",
			),
			"tgl_akhir_efektif"=>array(
				'field'=>'tgl_akhir_efektif', 
				'label'=>'Tgl. Akhir Efektif', 
				'rules'=>"max_length[8]",
			),
			
		);
	}

}