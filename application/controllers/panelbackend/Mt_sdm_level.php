<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_sdm_level extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_sdm_levellist";
		$this->viewdetail = "panelbackend/mt_sdm_leveldetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Level Jabatan';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Level Jabatan';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Level Jabatan';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Level Jabatan';
		}

		$this->load->model("Mt_sdm_levelModel","model");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'level', 
				'label'=>'Level', 
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'nama', 
				'label'=>'Nama', 
				'width'=>"auto",
				'type'=>"varchar",
			),
			array(
				'name'=>'is_aktif', 
				'label'=>'Aktif', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>array(''=>'-pilih-','0'=>'Tidak','1'=>'Iya'),
			),
		);
	}

	protected function Record($id=null){
		return array(
			'level'=>$this->post['level'],
			'nama'=>$this->post['nama'],
			'is_aktif'=>(int)$this->post['is_aktif'],
		);
	}

	protected function Rules(){
		return array(
			"level"=>array(
				'field'=>'level', 
				'label'=>'Level', 
				'rules'=>"integer|max_length[10]",
			),
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"max_length[100]",
			),
			"is_aktif"=>array(
				'field'=>'is_aktif', 
				'label'=>'IS Aktif', 
				'rules'=>"integer|max_length[10]",
			),
		);
	}

}