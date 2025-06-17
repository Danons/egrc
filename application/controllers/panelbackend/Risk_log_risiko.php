<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Risk_log_risiko extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/risk_loglist";
		$this->viewdetail = "panelbackend/risk_logdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_scorecard";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Risk LOG';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Risk LOG';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Risk LOG';
			$this->data['edited'] = false;
		}else{
			$this->data['page_title'] = 'Daftar Risk LOG';
		}

		$this->load->model("Risk_logModel","model");

		$this->load->model("Public_sys_groupModel","publicsysgroup");
		$publicsysgroup = $this->publicsysgroup;
		$rspublicsysgroup = $publicsysgroup->GArray();

		$publicsysgrouparr = array(''=>'');
		foreach($rspublicsysgroup as $row){
			$publicsysgrouparr[$row['group_id']] = $row['name'];
		}

		$this->data['publicsysgrouparr'] = $publicsysgrouparr;

		$this->SetAccess(array('panelbackend/risk_scorecard','panelbackend/risk_risiko'));

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'nama_user',
				'field'=>'nama_user',
				'label'=>'User',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'group_id',
				'field'=>'group_id',
				'label'=>'Akses',
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['publicsysgrouparr'],
			),
			array(
				'name'=>'activity_time',
				'field'=>'t.activity_time',
				'label'=>'Waktu',
				'width'=>"auto",
				'type'=>"date",
			),
			array(
				'name'=>'deskripsi',
				'label'=>'Aktivitas',
				'width'=>"auto",
				'type'=>"varchar2",
			),
		);
	}

	public function Index($id_risiko=null, $page=0){

		$this->_beforeDetail($id_risiko);
		$this->_setFilter("id_risiko = ".$this->conn->qstr($id_risiko));
		$this->data['list']=$this->_getList($page);
		$this->data['header']=$this->Header();
		$this->data['page']=$page;
		$param_paging = array(
			'base_url'=>base_url("{$this->page_ctrl}/index/$id_risiko"),
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
	public function Add($id_scorecard = null){
		$this->Error404();
	}

	public function Edit($id_scorecard=null, $id=null){
		$this->Error404();
	}


	public function Detail($id_scorecard=null, $id=null){
		$this->Error404();
	}

	public function Delete($id_scorecard=null, $id=null){
		$this->Error404();
	}

	protected function _beforeDetail($id = NULL, $id1 = NULL){
		#mengambil dari model karena sudah difilter sesuai akses
		$this->load->model("Risk_risikoModel",'riskrisiko');
		$this->data['rowheader1']  = $this->riskrisiko->GetByPk($id);

		if(!$this->data['rowheader1'])
			$this->NoData();

		$id_scorecard = $this->data['rowheader1']['id_scorecard'];

		$this->load->model("Risk_scorecardModel",'riskscorecard');
		$this->data['rowheader']  = $this->riskscorecard->GetByPk($id_scorecard);

		if(!$this->data['rowheader'])
			$this->NoData();

		$owner = $this->data['rowheader']['owner'];

		if($owner){
			$this->data['ownerarr'][$owner] = $this->conn->GetOne("select concat(nama,' (',coalesce(id_unit,''),')') from mt_sdm_jabatan where deleted_date is null and id_jabatan = ".$this->conn->escape($owner));
		}

		$this->data['add_param'] .= $id;
	}


	protected function _getFilter()
	{
		$this->xss_clean = true;

		$this->FilterRequest();

		$filter_arr = array();

		if ($this->post['act'] == 'list_filter' && $this->post['list_filter']) {
			if (!$_SESSION[SESSION_APP][$this->page_ctrl]['list_filter']) {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'] = $this->post['list_filter'];
			} else {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'], $this->post['list_filter']);
			}
		}

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['list_filter']) {

			foreach ($_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'] as $r) {
				$idkey = $r['idkey'];
				$filter_arr1 = array();

				foreach ($r['values'] as $k => $v) {
					$k = str_replace("_____", ".", $k);

					replaceSingleQuote($v);
					replaceSingleQuote($k);
					if (!($v === '' or $v === null or $v === false))
						$filter_arr1[] = 'a.' . $idkey . " = '$v'";
				}

				$filter_str = implode(' or ', $filter_arr1);

				if ($filter_str) {
					$filter_arr[] = "($filter_str)";
				}
			}
		}

		if (!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']) {
			$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] = array();
		}

		if ($this->post['act'] == 'list_search' && $this->post['list_search_filter']) {
			if (!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']) {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] = $this->post['list_search_filter'];
			} else {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'], $this->post['list_search_filter']);
			}
		}

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']) {
			foreach ($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] as $k => $v) {
				$k = str_replace("_____", ".", $k);

				if (!($v === '' or $v === null or $v === false)) {
					replaceSingleQuote($v);
					replaceSingleQuote($k);

					$filter_arr[] = "$k='$v'";
				}
			}
		}




		if (!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search']) {
			$_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] = array();
		}

		if ($this->post['act'] == 'list_search' && $this->post['list_search']) {

			if (!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search']) {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] = $this->post['list_search'];
			} else {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_search'], $this->post['list_search']);
			}
		}

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['list_search']) {
			foreach ($_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] as $k => $v) {
				$k = str_replace("_____", ".", $k);

				if($k=="nama_user"){
					$k = "coalesce(t.user_name, u.name)";
				}

				replaceSingleQuote($v);
				replaceSingleQuote($k);

				if (trim($v) !== '' && in_array($k, $this->arrNoquote)) {
					$filter_arr[] = "$k=$v";
				} else if ($v !== '') {
					$v = strtolower($v);
					$filter_arr[] = "lower($k) like '%$v%'";
				}
			}
		}

		$this->data['filter_arr'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_search'], $_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']);

		if (($filter_arr)) {
			$this->filter .= ' and ' . implode(' and ', $filter_arr);
		}

		return $this->filter;
	}
}
