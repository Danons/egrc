<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_risk_efektif_m_pengukuran extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_risk_efektif_m_pengukuranlist";
		$this->viewdetail = "panelbackend/mt_risk_efektif_m_pengukurandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Pengukuran';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Pengukuran';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Pengukuran';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Pengukuran';
		}

		$this->data['width'] = "800px";

		$this->load->model("Mt_risk_efektif_m_pengukuranModel","model");
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
				'name'=>'efektifitas_mitigasi', 
				'label'=>'Efektifitas Mitigasi', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'diskripsi_kriteria', 
				'label'=>'Diskripsi Kriteria', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'skor_bawah'=>Rupiah2Number($this->post['skor_bawah']),
			'skor_atas'=>Rupiah2Number($this->post['skor_atas']),
			'efektifitas_mitigasi'=>$this->post['efektifitas_mitigasi'],
			'diskripsi_kriteria'=>$this->post['diskripsi_kriteria'],
		);
	}

	protected function Rules(){
		return array(
			"skor_bawah"=>array(
				'field'=>'skor_bawah', 
				'label'=>'Skor Bawah', 
				'rules'=>"required|numeric|max_length[10]",
			),
			"skor_atas"=>array(
				'field'=>'skor_atas', 
				'label'=>'Skor Atas', 
				'rules'=>"numeric|max_length[10]",
			),
			"efektifitas_mitigasi"=>array(
				'field'=>'efektifitas_mitigasi', 
				'label'=>'Efektifitas Mitigasi', 
				'rules'=>"required|max_length[100]",
			),
			"diskripsi_kriteria"=>array(
				'field'=>'diskripsi_kriteria', 
				'label'=>'Diskripsi Kriteria', 
				'rules'=>"required|max_length[500]",
			),
		);
	}

}