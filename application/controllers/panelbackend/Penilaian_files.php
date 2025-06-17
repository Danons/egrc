<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Penilaian_files extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/penilaian_fileslist";
		$this->viewdetail = "panelbackend/penilaian_filesdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Penilaian Files';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Penilaian Files';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Penilaian Files';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Penilaian Files';
		}

		$this->data['width'] = "1400px";

		$this->load->model("Penilaian_filesModel","model");
		$this->load->model("PenilaianModel","penilaian");
		$this->data['penilaianarr'] = $this->penilaian->GetCombo();

		
		$this->load->model("Penilaian_detailModel","penilaiandetail");
		$this->data['penilaiandetailarr'] = $this->penilaiandetail->GetCombo();

		
		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'id_penilaian', 
				'label'=>'Penilaian', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['penilaianarr'],
			),
			array(
				'name'=>'id_penilaian_detail', 
				'label'=>'Penilaian Detail', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['penilaiandetailarr'],
			),
			array(
				'name'=>'file_name', 
				'label'=>'File Name', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'file_type', 
				'label'=>'File Type', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'file_size', 
				'label'=>'File Size', 
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'client_name', 
				'label'=>'Client Name', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'jenis_file', 
				'label'=>'Jenfile', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>array(''=>'-pilih-','0'=>'Tidak','1'=>'Iya'),
			),
		);
	}

	protected function Record($id=null){
		return array(
			'id_penilaian'=>Rupiah2Number($this->post['id_penilaian']),
			'id_penilaian_detail'=>Rupiah2Number($this->post['id_penilaian_detail']),
			'file_name'=>$this->post['file_name'],
			'file_type'=>$this->post['file_type'],
			'file_size'=>Rupiah2Number($this->post['file_size']),
			'client_name'=>$this->post['client_name'],
			'jenis_file'=>$this->post['jenis_file'],
		);
	}

	protected function Rules(){
		return array(
			"id_penilaian"=>array(
				'field'=>'id_penilaian', 
				'label'=>'Penilaian', 
				'rules'=>"in_list[".implode(",", array_keys($this->data['penilaianarr']))."]|max_length[10]",
			),
			"id_penilaian_detail"=>array(
				'field'=>'id_penilaian_detail', 
				'label'=>'Penilaian Detail', 
				'rules'=>"in_list[".implode(",", array_keys($this->data['penilaiandetailarr']))."]|max_length[10]",
			),
			"file_name"=>array(
				'field'=>'file_name', 
				'label'=>'File Name', 
				'rules'=>"max_length[1000]",
			),
			"file_type"=>array(
				'field'=>'file_type', 
				'label'=>'File Type', 
				'rules'=>"max_length[200]",
			),
			"file_size"=>array(
				'field'=>'file_size', 
				'label'=>'File Size', 
				'rules'=>"numeric|max_length[10]",
			),
			"client_name"=>array(
				'field'=>'client_name', 
				'label'=>'Client Name', 
				'rules'=>"max_length[1000]",
			),
			"jenis_file"=>array(
				'field'=>'jenis_file', 
				'label'=>'Jenis File', 
				'rules'=>"max_length[100]",
			),
		);
	}

}