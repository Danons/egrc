<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Penilaian_komentar extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/penilaian_komentarlist";
		$this->viewdetail = "panelbackend/penilaian_komentardetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Penilaian Komentar';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Penilaian Komentar';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Penilaian Komentar';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Penilaian Komentar';
		}

		$this->data['width'] = "800px";

		$this->load->model("Penilaian_komentarModel","model");
		$this->load->model("PenilaianModel","penilaian");
		$this->data['penilaianarr'] = $this->penilaian->GetCombo();

		
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
				'name'=>'komentar', 
				'label'=>'Komentar', 
				'width'=>"auto",
				'type'=>"varchar2",
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
			'id_penilaian'=>Rupiah2Number($this->post['id_penilaian']),
			'komentar'=>$this->post['komentar'],
			'nama'=>$this->post['nama'],
		);
	}

	protected function Rules(){
		return array(
			"id_penilaian"=>array(
				'field'=>'id_penilaian', 
				'label'=>'Penilaian', 
				'rules'=>"in_list[".implode(",", array_keys($this->data['penilaianarr']))."]|max_length[10]",
			),
			"komentar"=>array(
				'field'=>'komentar', 
				'label'=>'Komentar', 
				'rules'=>"max_length[2000]",
			),
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"max_length[20]",
			),
		);
	}

}