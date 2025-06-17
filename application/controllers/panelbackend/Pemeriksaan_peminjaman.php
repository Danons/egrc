<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Pemeriksaan_peminjaman extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/pemeriksaan_peminjamanlist";
		$this->viewdetail = "panelbackend/pemeriksaan_peminjamandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Tanda Terima Peminjaman Dokumen';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Tanda Terima Peminjaman Dokumen';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Tanda Terima Peminjaman Dokumen';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Tanda Terima Peminjaman Dokumen';
		}

		$this->load->model("Pemeriksaan_peminjamanModel","model");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker'
		);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'id_pemeriksaan', 
				'label'=>'Pemeriksaan', 
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'jenis_dokumen_yang_dipinjam', 
				'label'=>'Jendokumen yang Dipinjam', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>array(''=>'-pilih-','0'=>'Tidak','1'=>'Iya'),
			),
			array(
				'name'=>'nomor_berkas', 
				'label'=>'Nomor Berkas', 
				'width'=>"auto",
				'type'=>"varchar",
			),
			array(
				'name'=>'nama_peminjam', 
				'label'=>'Nama Peminjam', 
				'width'=>"auto",
				'type'=>"varchar",
			),
			array(
				'name'=>'maksud_dan_keperluan', 
				'label'=>'Maksud DAN Keperluan', 
				'width'=>"auto",
				'type'=>"varchar",
			),
			array(
				'name'=>'tgl_peminjaman', 
				'label'=>'Tgl. Peminjaman', 
				'width'=>"auto",
				'type'=>"date",
			),
			array(
				'name'=>'tgl_pengembalian', 
				'label'=>'Tgl. Pengembalian', 
				'width'=>"auto",
				'type'=>"date",
			),
			array(
				'name'=>'keterangan', 
				'label'=>'Keterangan', 
				'width'=>"auto",
				'type'=>"text",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'id_pemeriksaan'=>$this->post['id_pemeriksaan'],
			'jenis_dokumen_yang_dipinjam'=>$this->post['jenis_dokumen_yang_dipinjam'],
			'nomor_berkas'=>$this->post['nomor_berkas'],
			'nama_peminjam'=>$this->post['nama_peminjam'],
			'maksud_dan_keperluan'=>$this->post['maksud_dan_keperluan'],
			'tgl_peminjaman'=>$this->post['tgl_peminjaman'],
			'tgl_pengembalian'=>$this->post['tgl_pengembalian'],
			'keterangan'=>$this->post['keterangan'],
		);
	}

	protected function Rules(){
		return array(
			"id_pemeriksaan"=>array(
				'field'=>'id_pemeriksaan', 
				'label'=>'Pemeriksaan', 
				'rules'=>"integer|max_length[10]",
			),
			"jenis_dokumen_yang_dipinjam"=>array(
				'field'=>'jenis_dokumen_yang_dipinjam', 
				'label'=>'Jenis Dokumen yang Dipinjam', 
				'rules'=>"max_length[100]",
			),
			"nomor_berkas"=>array(
				'field'=>'nomor_berkas', 
				'label'=>'Nomor Berkas', 
				'rules'=>"max_length[100]",
			),
			"nama_peminjam"=>array(
				'field'=>'nama_peminjam', 
				'label'=>'Nama Peminjam', 
				'rules'=>"max_length[200]",
			),
			"maksud_dan_keperluan"=>array(
				'field'=>'maksud_dan_keperluan', 
				'label'=>'Maksud DAN Keperluan', 
				'rules'=>"max_length[500]",
			),
			"keterangan"=>array(
				'field'=>'keterangan', 
				'label'=>'Keterangan', 
				'rules'=>"",
			),
		);
	}

}