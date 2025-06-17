<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Kpi extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/kpilist";
		$this->viewdetail = "panelbackend/kpidetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah KPI';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit KPI';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail KPI';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'KPI';
		}

		$this->load->model("KpiModel", "model");

		$this->load->model("KpiModel", "kpi");
		$this->data['kpiarr'] = $this->kpi->GetCombo();
		$this->data['jenisrealisasiarr'] = array('akumulatif' => 'Akumulatif', 'progresif' => 'Progresif', 'average' => 'Average');

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'treetable', 'select2'
		);

		// dpr($this->access_role, 1);
	}

	protected function _getList($page = 0)
	{
		$this->_resetList();

		$this->arrNoquote = $this->model->arrNoquote;

		$param = array(
			'page' => $page,
			'limit' => $this->_limit(),
			'order' => $this->_order(),
			'filter' => $this->_getFilter()
		);

		if ($this->post['act'] && $this->post['act'] <> 'save' && $this->post['act'] <> 'set_value') {

			if ($this->data['add_param']) {
				$add_param = '/' . $this->data['add_param'];
			}
			redirect(str_replace(strstr(current_url(), "/index$add_param/$page"), "/index{$add_param}", current_url()));
		}

		$respon = $this->model->SelectGrid(
			$param
		);

		return $respon;
	}

	public function Index($page = 0)
	{
		$this->data['header'] = $this->Header();

		$this->data['rows'] = $this->_getList($page);

		$this->View($this->viewlist);
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'kode',
				'label' => 'Kode',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'nama',
				'label' => 'Nama',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'is_bersama',
				'label' => 'Bersama',
				'width' => "auto",
				'type' => "list",
				'value' => array('' => '-pilih-', '0' => 'Tidak', '1' => 'Iya'),
			),
			array(
				'name' => 'is_direktorat',
				'label' => 'Direktorat',
				'width' => "auto",
				'type' => "list",
				'value' => array('' => '-pilih-', '0' => 'Tidak', '1' => 'Iya'),
			),
			array(
				'name' => 'is_korporat',
				'label' => 'Korporat',
				'width' => "auto",
				'type' => "list",
				'value' => array('' => '-pilih-', '0' => 'Tidak', '1' => 'Iya'),
			),
			array(
				'name' => 'jenis_realisasi',
				'label' => 'Jenis Realisasi',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['jenisrealisasiarr'],
			),
			// array(
			// 	'name'=>'is_nilai_akhir', 
			// 	'label'=>'Nilai Akhir', 
			// 	'width'=>"auto",
			// 	'type'=>"list",
			// 	'value'=>array(''=>'-pilih-','0'=>'Tidak','1'=>'Iya'),
			// ),
		);
	}

	protected function Record($id = null)
	{
		if ($this->post['jenis_direktorat'] == 1)
			$this->post['is_direktorat'] = 1;
		elseif ($this->post['jenis_direktorat'] == 2)
			$this->post['is_bersama'] = 1;

		return array(
			'id_parent' => $this->post['id_parent'],
			'kode' => $this->post['kode'],
			'urutan' => $this->post['urutan'],
			'nama' => $this->post['nama'],
			// 'jenis_realisasi' => $this->post['jenis_realisasi'],
			// 'is_bersama' => (int)$this->post['is_bersama'],
			// 'is_direktorat' => (int)$this->post['is_direktorat'],
			// 'is_korporat' => (int)$this->post['is_korporat'],
			// 'is_nilai_akhir' => (int)$this->post['is_nilai_akhir'],
		);
	}

	protected function Rules()
	{
		return array(
			"id_parent" => array(
				'field' => 'id_parent',
				'label' => 'Parent',
				'rules' => "in_list[" . implode(",", array_keys($this->data['kpiarr'])) . "]|max_length[10]",
			),
			"nama" => array(
				'field' => 'nama',
				'label' => 'Nama',
				'rules' => "max_length[225]",
			),
			"is_bersama" => array(
				'field' => 'is_bersama',
				'label' => 'IS Bersama',
				'rules' => "integer|max_length[10]",
			),
			"is_direktorat" => array(
				'field' => 'is_direktorat',
				'label' => 'IS Direktorat',
				'rules' => "integer|max_length[10]",
			),
			"is_korporat" => array(
				'field' => 'is_korporat',
				'label' => 'IS Korporat',
				'rules' => "integer|max_length[10]",
			),
			"is_nilai_akhir" => array(
				'field' => 'is_nilai_akhir',
				'label' => 'IS Nilai Akhir',
				'rules' => "integer|max_length[10]",
			),
		);
	}

	protected function _afterDetail($id = null)
	{
		if (!$this->data['edited']) {
			$this->data['rowstahunan'] = $this->conn->GetArray("select * from kpi_config where deleted_date is null and id_kpi = " . $this->conn->escape($id));
		}

		return true;
	}
}
