<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_sdm_sub_unit extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_sdm_sub_unitlist";
		$this->viewdetail = "panelbackend/mt_sdm_sub_unitdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Sub Unit';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Sub Unit';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Sub Unit';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Sub Unit';
		}

		$this->data['width'] = "800px";

		$this->load->model("Mt_sdm_sub_unitModel","model");
		$this->load->model("Mt_sdm_unitModel","mtsdmunit");
		$this->data['mtsdmunitarr'] = $this->mtsdmunit->GetCombo();

		
		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'id_unit', 
				'label'=>'Unit', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtsdmunitarr'],
			),
			array(
				'name'=>'nama', 
				'label'=>'Nama', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'id_unit'=>$this->post['id_unit'],
			'nama'=>$this->post['nama'],
		);
	}

	protected function Rules(){
		return array(
			"id_unit"=>array(
				'field'=>'id_unit', 
				'label'=>'Unit', 
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtsdmunitarr']))."]|max_length[18]",
			),
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"max_length[200]",
			),
		);
	}

}