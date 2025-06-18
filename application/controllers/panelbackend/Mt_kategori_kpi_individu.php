<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_kategori_kpi_individu extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_kategori_kpi_individulist";
		$this->viewdetail = "panelbackend/mt_kategori_kpi_individudetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Kategori KPI Individu';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Kategori KPI Individu';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Kategori KPI Individu';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Kategori KPI Individu';
		}

		$this->load->model("Mt_kategori_kpi_individuModel","model");

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