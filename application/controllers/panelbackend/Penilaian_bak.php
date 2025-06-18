<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Penilaian extends _adminController{
	public $limit = -1;

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/penilaianlist_kategori";
		$this->viewdetail = "panelbackend/mt_kategoridetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Kategori';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Kategori';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Kategori';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Pilih Kategori';
		}

		$this->load->model("Mt_kategoriModel","model");
		$this->load->model("Mt_kategori_jenisModel","mtkategorijenis");
		$this->data['mtkategorijenisarr'] = $this->mtkategorijenis->GetCombo();

		
		$this->load->model("Mt_kategoriModel","mtkategori");
		$this->data['mtkategoriarr'] = $this->mtkategori->GetCombo();

		
		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	public function Add($id_kategori_parent=null){
		$this->post['id_kategori_parent'] = $id_kategori_parent;
		$this->Edit();
	}



	public function Detail( $id=null){

		$this->_beforeDetail($id);

		$this->data['row'] = $this->model->GetByPk($id);
		
		/*if($this->data['row']['id_kategori_jenis'] == 1)
			redirect("panelbackend/penilaian_smk3/index/".$id);
		if($this->data['row']['id_kategori_jenis'] == 2)*/
			redirect("panelbackend/penilaian_lk3/index/".$id);
		/*if($this->data['row']['id_kategori_jenis'] == 3)
			redirect("panelbackend/penilaian_proper/index/".$id);*/

		$this->_onDetail($id);

		if (!$this->data['row'] && !$this->data['rowheader'])
			$this->NoData();

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}


	function GenerateTree(&$row, $colparent, $colid, $collabel, &$return=array(), $valparent=null, &$i=0, $level=0, $not_id=null){
		$level++;
		foreach ($row as $key => $value) {
			if($not_id && $value[$colparent]==$not_id)
				continue;
			
			# code...
			if(trim($value[$colparent])==trim($valparent)){
				unset($row[$key]);

				$space = '';
				if($level==1)
					$value[$collabel] = "<b>".$value[$collabel]."</b>";

				$value[$collabel] = $space.$value[$collabel];
				$return[$i]=$value;

				$i++;
				$this->GenerateTree($row, $colparent, $colid, $collabel, $return, $value[$colid], $i, $level, $not_id);
			}
		}

		if($row && $level==1 && !$not_id)
			$return = array_merge($return, $row);
	}

	public function Index($page=0){
		$this->data['header']=$this->Header();

		$this->data['list']=$this->_getList($page);

		$rows = array();

		$i=0;
		$this->GenerateTree($this->data['list']['rows'], "id_kategori_parent", "id_kategori", "nama", $rows, null, $i);

		$this->data['list']['rows'] = $rows;

		$this->data['page']=$page;

		$param_paging = array(
			'base_url'=>base_url("{$this->page_ctrl}/index/".$this->data['add_param']),
			'cur_page'=>$page,
			'total_rows'=>$this->data['list']['total'],
			'per_page'=>$this->limit,
			'first_tag_open'=>'<li>',
			'first_tag_close'=>'</li>',
			'last_tag_open'=>'<li>',
			'last_tag_close'=>'</li>',
			'cur_tag_open'=>'<li class="active"><a href="#">',
			'cur_tag_close'=>'</a></li>',
			'next_tag_open'=>'<li>',
			'next_tag_close'=>'</li>',
			'prev_tag_open'=>'<li>',
			'prev_tag_close'=>'</li>',
			'num_tag_open'=>'<li>',
			'num_tag_close'=>'</li>',
			'anchor_class'=>'pagination__page',

		);
		$this->load->library('pagination');

		$paging = $this->pagination;

		$paging->initialize($param_paging);

		$this->data['paging']=$paging->create_links();

		$this->data['limit']=$this->limit;

		$this->data['limit_arr']=$this->limit_arr;

		$this->View($this->viewlist);
	}

	protected function Header(){
		return array(
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
			'id_kategori_parent'=>($this->post['id_kategori_parent']),
			'nama'=>$this->post['nama'],
			'is_aktif'=>(int)$this->post['is_aktif'],
			'id_kategori_jenis'=>($this->post['id_kategori_jenis']),
		);
	}

	protected function Rules(){
		return array(
			"id_kategori_parent"=>array(
				'field'=>'id_kategori_parent', 
				'label'=>'Kategori Parent', 
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtkategoriarr']))."]|max_length[10]",
			),
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"required|max_length[200]",
			),
			"is_aktif"=>array(
				'field'=>'is_aktif', 
				'label'=>'IS Aktif', 
				'rules'=>"required|max_length[1]",
			),
			"id_kategori_jenis"=>array(
				'field'=>'id_kategori_jenis', 
				'label'=>'Kategori Jenis', 
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtkategorijenisarr']))."]|max_length[10]",
			),
		);
	}

}