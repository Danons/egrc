<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_kriteria extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_kriterialist";
		$this->viewdetail = "panelbackend/mt_kriteriadetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Kriteria';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Kriteria';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Kriteria';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Kriteria';
		}

		$this->load->model("Mt_kriteriaModel","model");

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
				'name'=>'id_kategori', 
				'label'=>'Kategori', 
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'kode', 
				'label'=>'Kode', 
				'width'=>"auto",
				'type'=>"varchar",
			),
			array(
				'name'=>'nama', 
				'label'=>'Nama', 
				'width'=>"auto",
				'type'=>"varchar",
			),
			array(
				'name'=>'is_upload', 
				'label'=>'Upload', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>array(''=>'-pilih-','0'=>'Tidak','1'=>'Iya'),
			),
			array(
				'name'=>'id_kriteria_parent', 
				'label'=>'Kriteria Parent', 
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'is_aktif', 
				'label'=>'Aktif', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>array(''=>'-pilih-','0'=>'Tidak','1'=>'Iya'),
			),
			array(
				'name'=>'id_interval', 
				'label'=>'Interval', 
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'bobot', 
				'label'=>'Bobot', 
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'tahun', 
				'label'=>'Tahun', 
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'id_unit', 
				'label'=>'Unit', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtsdmunitarr'],
			),
			array(
				'name'=>'id_kriteria_before', 
				'label'=>'Kriteria Before', 
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'id_kriteria_parent1', 
				'label'=>'Kriteria Parent1', 
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'d', 
				'label'=>'D', 
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'k', 
				'label'=>'K', 
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'w', 
				'label'=>'W', 
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'o', 
				'label'=>'O', 
				'width'=>"auto",
				'type'=>"number",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'id_kategori'=>$this->post['id_kategori'],
			'kode'=>$this->post['kode'],
			'nama'=>$this->post['nama'],
			'is_upload'=>(int)$this->post['is_upload'],
			'id_kriteria_parent'=>$this->post['id_kriteria_parent'],
			'is_aktif'=>(int)$this->post['is_aktif'],
			'id_interval'=>$this->post['id_interval'],
			'bobot'=>$this->post['bobot'],
			'tahun'=>$this->post['tahun'],
			'id_unit'=>$this->post['id_unit'],
			'id_kriteria_before'=>$this->post['id_kriteria_before'],
			'id_kriteria_parent1'=>$this->post['id_kriteria_parent1'],
			'd'=>$this->post['d'],
			'k'=>$this->post['k'],
			'w'=>$this->post['w'],
			'o'=>$this->post['o'],
		);
	}

	protected function Rules(){
		return array(
			"id_kategori"=>array(
				'field'=>'id_kategori', 
				'label'=>'Kategori', 
				'rules'=>"integer|max_length[10]",
			),
			"kode"=>array(
				'field'=>'kode', 
				'label'=>'Kode', 
				'rules'=>"max_length[20]",
			),
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"max_length[2000]",
			),
			"is_upload"=>array(
				'field'=>'is_upload', 
				'label'=>'IS Upload', 
				'rules'=>"max_length[1]",
			),
			"id_kriteria_parent"=>array(
				'field'=>'id_kriteria_parent', 
				'label'=>'Kriteria Parent', 
				'rules'=>"integer|max_length[10]",
			),
			"is_aktif"=>array(
				'field'=>'is_aktif', 
				'label'=>'IS Aktif', 
				'rules'=>"max_length[1]",
			),
			"id_interval"=>array(
				'field'=>'id_interval', 
				'label'=>'Interval', 
				'rules'=>"integer|max_length[10]",
			),
			"bobot"=>array(
				'field'=>'bobot', 
				'label'=>'Bobot', 
				'rules'=>"numeric|max_length[10]",
			),
			"tahun"=>array(
				'field'=>'tahun', 
				'label'=>'Tahun', 
				'rules'=>"integer|max_length[10]",
			),
			"id_unit"=>array(
				'field'=>'id_unit', 
				'label'=>'Unit', 
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtsdmunitarr']))."]|max_length[18]",
			),
			"id_kriteria_before"=>array(
				'field'=>'id_kriteria_before', 
				'label'=>'Kriteria Before', 
				'rules'=>"integer|max_length[10]",
			),
			"id_kriteria_parent1"=>array(
				'field'=>'id_kriteria_parent1', 
				'label'=>'Kriteria Parent1', 
				'rules'=>"integer|max_length[10]",
			),
			"d"=>array(
				'field'=>'d', 
				'label'=>'D', 
				'rules'=>"integer|max_length[10]",
			),
			"k"=>array(
				'field'=>'k', 
				'label'=>'K', 
				'rules'=>"integer|max_length[10]",
			),
			"w"=>array(
				'field'=>'w', 
				'label'=>'W', 
				'rules'=>"integer|max_length[10]",
			),
			"o"=>array(
				'field'=>'o', 
				'label'=>'O', 
				'rules'=>"integer|max_length[10]",
			),
		);
	}

}