<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Penilaian_session extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/penilaian_sessionlist";
		$this->viewdetail = "panelbackend/penilaian_sessiondetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Sesi Penilaian';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Sesi Penilaian';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Sesi Penilaian';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Sesi Penilaian';
		}

		$this->load->model("Penilaian_sessionModel", "model");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'treetable'
		);
	}


	public function Index($page = 0)
	{
		// dpr($this->id_kategori, 1);
		$this->data['header'] = $this->Header();
		$this->_setFilter("id_kategori = " . $this->conn->escape($this->id_kategori));
		$this->data['list'] = $this->_getList($page);

		$this->data['page'] = $page;

		$param_paging = array(
			'base_url' => base_url("{$this->page_ctrl}/index"),
			'cur_page' => $page,
			'total_rows' => $this->data['list']['total'],
			'per_page' => $this->limit,
			'first_tag_open' => '<li class="page-item">',
			'first_tag_close' => '</li>',
			'last_tag_open' => '<li class="page-item">',
			'last_tag_close' => '</li>',
			'cur_tag_open' => '<li class="page-item active"><a href="#" class="page-link">',
			'cur_tag_close' => '</a></li>',
			'next_tag_open' => '<li class="page-item">',
			'next_tag_close' => '</li>',
			'prev_tag_open' => '<li class="page-item">',
			'prev_tag_close' => '</li>',
			'num_tag_open' => '<li class="page-item">',
			'num_tag_close' => '</li>',
			'anchor_class' => 'page-link',
			'attributes' => array('class' => 'page-link'),
		);
		$this->load->library('pagination');

		$paging = $this->pagination;

		$paging->initialize($param_paging);

		$this->data['paging'] = $paging->create_links();

		$this->data['limit'] = $this->limit;

		$this->data['limit_arr'] = $this->limit_arr;

		$this->View($this->viewlist);
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'nama',
				'label' => 'Nama',
				'width' => "auto",
				'type' => "varchar",
			),
			// array(
			// 	'name'=>'tgl', 
			// 	'label'=>'Tgl.', 
			// 	'width'=>"auto",
			// 	'type'=>"date",
			// ),
			// array(
			// 	'name'=>'page_ctrl', 
			// 	'label'=>'Page Ctrl', 
			// 	'width'=>"auto",
			// 	'type'=>"varchar",
			// ),
			// array(
			// 	'name'=>'id_kategori', 
			// 	'label'=>'Kategori', 
			// 	'width'=>"auto",
			// 	'type'=>"number",
			// ),
		);
	}

	protected function Record($id = null)
	{
		if (!$this->data['jenis_assessment_gcg']) {
			$this->data['jenis_assesment_gcg'] = 0;
		}
		return array(
			'nama' => $this->post['nama'],
			'tgl' => $this->post['tgl'],
			'tgl_selesai' => $this->post['tgl_selesai'],
			'target_lvl' => $this->post['target_lvl'],
			'page_ctrl' => $this->post['page_ctrl'],
			'id_kategori' => $this->data['id_kategori'],
			'jenis_assessment_gcg' => $this->data['jenis_assessment_gcg']
		);
	}

	protected function Rules()
	{
		return array(
			"nama" => array(
				'field' => 'nama',
				'label' => 'Nama',
				'rules' => "max_length[500]",
			),
			"page_ctrl" => array(
				'field' => 'page_ctrl',
				'label' => 'Page Ctrl',
				'rules' => "max_length[200]",
			),
			"id_kategori" => array(
				'field' => 'id_kategori',
				'label' => 'Kategori',
				'rules' => "integer|max_length[10]",
			),
		);
	}

	public function Delete($id_kategori = null, $id = null)
	{
		
		$id = urldecode($id);
		$this->model->conn->StartTrans();

		$this->_beforeDetail($id);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$return = $this->_beforeDelete($id);

		if ($return) {
			$return = $this->model->delete("$this->pk = " . $this->conn->qstr($id));
		}

		if ($return) {
			$return1 = $this->_afterDelete($id);
			if (!$return1)
				$return = false;
		}

		$this->model->conn->CompleteTrans();

		if ($return) {

			$this->log("menghapus", $this->data['row']);

			SetFlash('suc_msg', $return['success']);
			redirect("$this->page_ctrl");
		} else {
			SetFlash('err_msg', "Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id");
		}
	}
}
