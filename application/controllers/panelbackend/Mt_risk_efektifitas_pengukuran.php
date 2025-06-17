<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_risk_efektifitas_pengukuran extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_risk_efektifitas_pengukuranlist";
		$this->viewdetail = "panelbackend/mt_risk_efektifitas_pengukurandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Pengukuran Efektifitas';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Pengukuran Efektifitas';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Pengukuran Efektifitas';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Pengukuran Efektifitas';
		}

		$this->data['width'] = "1000px";

		$this->load->model("Mt_risk_efektifitas_pengukuranModel","model");
		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'skor_bawah', 
				'label'=>'Skor Bawah', 
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'skor_atas', 
				'label'=>'Skor Atas', 
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'efektifitas', 
				'label'=>'Efektifitas', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'diskripsi_kriteria', 
				'label'=>'Diskripsi Kriteria', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'faktor_terhadap_risiko', 
				'label'=>'Faktor Terhadap Risiko', 
				'width'=>"auto",
				'type'=>"number",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'skor_bawah'=>$this->post['skor_bawah'],
			'skor_atas'=>$this->post['skor_atas'],
			'efektifitas'=>$this->post['efektifitas'],
			'diskripsi_kriteria'=>$this->post['diskripsi_kriteria'],
			'faktor_terhadap_risiko'=>$this->post['faktor_terhadap_risiko'],
		);
	}

	protected function Rules(){
		return array(
			"skor_bawah"=>array(
				'field'=>'skor_bawah', 
				'label'=>'Skor Bawah', 
				'rules'=>"required|integer|max_length[10]",
			),
			"skor_atas"=>array(
				'field'=>'skor_atas', 
				'label'=>'Skor Atas', 
				'rules'=>"integer|max_length[10]",
			),
			"efektifitas"=>array(
				'field'=>'efektifitas', 
				'label'=>'Efektifitas', 
				'rules'=>"required|max_length[100]",
			),
			"diskripsi_kriteria"=>array(
				'field'=>'diskripsi_kriteria', 
				'label'=>'Diskripsi Kriteria', 
				'rules'=>"required|max_length[500]",
			),
			"faktor_terhadap_risiko"=>array(
				'field'=>'faktor_terhadap_risiko', 
				'label'=>'Faktor Terhadap Risiko', 
				'rules'=>"required|numeric|max_length[10]",
			),
		);
	}

}