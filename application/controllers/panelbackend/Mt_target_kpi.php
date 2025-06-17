<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_target_kpi extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_target_kpilist";
		$this->viewdetail = "panelbackend/mt_target_kpidetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Target KPI';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Target KPI';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Target KPI';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Target KPI';
		}

		$this->load->model("Mt_target_kpiModel","model");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'target', 
				'label'=>'Target', 
				'width'=>"auto",
				'type'=>"varchar",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'target'=>$this->post['target'],
		);
	}

	protected function Rules(){
		return array(
			"target"=>array(
				'field'=>'target', 
				'label'=>'Target', 
				'rules'=>"required|max_length[250]",
			),
		);
	}

}