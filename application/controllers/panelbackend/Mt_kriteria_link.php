<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_kriteria_link extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_kriteria_linklist";
		$this->viewdetail = "panelbackend/mt_kriteria_linkdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Kriteria Link';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Kriteria Link';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Kriteria Link';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Kriteria Link';
		}

		$this->data['width'] = "800px";

		$this->load->model("Mt_kriteria_linkModel","model");
		$this->load->model("Mt_kriteriaModel","mtkriteria");

		$this->load->model("Mt_kategoriModel","mtkategori");
		$this->data['mtkategoriaarr'] = $this->mtkategori->GetCombo2();
		$this->data['mtkategoriaarr2'] = $this->mtkategori->GetCombo2();


		$this->data['mtkriteriaarr'] = array();
		$this->data['mtkriteriaarr2'] = array();
		
		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'select2'
		);
	}

	

	public function Edit($id=null){
		if($this->post['act']=='save_file'){
			$this->_uploadFile($id);
		}

		if($this->post['act']=='reset'){
			redirect(current_url());
		}

		$this->_beforeDetail($id);

		$this->data['idpk'] = $id;

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['rowheader'] && !$this->data['row'] && $id)
			$this->NoData();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("","");

		if(count($this->post) && $this->post['act']<>'change'){
			if(!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id);

			$this->data['row'] = array_merge($this->data['row'],$record);
			$this->data['row'] = array_merge($this->data['row'],$this->post);
		}

		$this->_onDetail($id);

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {

			$this->_isValid($record,true);

            $this->conn->StartTrans();

            $return['success'] = $this->_beforeEdit($record,$id);

            $this->_setLogRecord($record,$id);

            if($return['success']){
				if (trim($this->data['row'][$this->pk])==trim($id) && trim($id)) {

					$ret = $this->_beforeUpdate($record, $id);

					if($ret)
						$return['success'] = "Berhasil update";

					if($return){
						$return = $this->model->Update($record, "$this->pk = ".$this->conn->qstr($id));
					}

					if ($return['success']) {

						$this->log("mengubah ".$record['nama']);

						$return1 = $this->_afterUpdate($id);

						if(!$return1){
							$return = false;
						}
					}
				}else {

					$ret = $this->_beforeInsert($record);

					if($ret)
						$return['success'] = "Berhasil insert";

					if($return){
						$return = $this->model->Insert($record);
						$id = $return['data'][$this->pk];
					}

					if ($return['success']) {

						$this->log("menambah ".$record['nama']);

						$return1 = $this->_afterInsert($id);

						if(!$return1){
							$return = false;
						}
					}
				}
			}

			if ($return['success']) {
				$this->conn->trans_commit();

				$this->_onSuccess($id);
				$this->_setGo($id);

				$this->_afterEditSucceed($id);

				SetFlash('suc_msg', $return['success']);
				redirect("$this->page_ctrl/detail/$id");

			} else {
				$this->conn->trans_rollback();
				$this->data['row'] = array_merge($this->data['row'],$record);
				$this->data['row'] = array_merge($this->data['row'],$this->post);

				$this->_afterEditFailed($id);

				$this->data['err_msg'] = "Data gagal disimpan";
			}
		}

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	protected function _beforeDetail($id_kategori=null){
		if($this->post['id_kategori'])
			$this->data['mtkriteriaarr'] = $this->mtkriteria->GetCombo2($this->post['id_kategori']);
		if($this->post['id_kategori2'])
			$this->data['mtkriteriaarr2'] = $this->mtkriteria->GetCombo2($this->post['id_kategori2']);

	}

	
	protected function Header(){
		return array(
			array(
				'name'=>'id_kategori1', 
				'field' => 'b_____id_kategori',
				'label'=>'Kategori1', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtkategoriaarr'],
			),
			array(
				'name'=>'kriteria1', 
				'label'=>'Kriteria1', 
				'width'=>"auto",
			),
			array(
				'name'=>'id_kategori2', 
				'field' => 'c_____id_kategori',
				'label'=>'Kategori2', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtkategoriaarr'],
			),
			array(
				'name'=>'kriteria2', 
				'label'=>'Kriteria2', 
				'width'=>"auto",
			),
		);
	}

	protected function Record($id=null){
		// if($this->post['act']=='save')
		// dpr($this->post,1);
		return array(
			'id_kriteria1'=>($this->post['id_kriteria1']),
			'id_kriteria2'=>($this->post['id_kriteria2']),
		);
	}

	protected function Rules(){
		return array(
			"id_kriteria1"=>array(
				'field'=>'id_kriteria1', 
				'label'=>'Kriteria1', 
				'rules'=>"required|in_list[".implode(",", array_keys($this->data['mtkriteriaarr']))."]|max_length[10]",
			),
			"id_kriteria2"=>array(
				'field'=>'id_kriteria2', 
				'label'=>'Kriteria2', 
				'rules'=>"required|in_list[".implode(",", array_keys($this->data['mtkriteriaarr2']))."]|max_length[10]",
			),
		);
	}

	

	protected function _getFilter(){
		$this->xss_clean = true;

		$this->FilterRequest();

		$filter_arr = array();

		if($this->post['act']=='list_filter' && $this->post['list_filter']){
			if(!$_SESSION[SESSION_APP][$this->page_ctrl]['list_filter']){
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'] = $this->post['list_filter'];
			}else{
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'],$this->post['list_filter']);

			}
		}

		if($_SESSION[SESSION_APP][$this->page_ctrl]['list_filter']){

			foreach ($_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'] as $r){
				$key = $r['key'];
				$filter_arr1 = array();

				foreach($r['values'] as $k=>$v){
					$k=str_replace("_____", ".", $k);

					replaceSingleQuote($v);
					replaceSingleQuote($k);
					if(!($v==='' or $v===null or $v===false))
						$filter_arr1[] = 'a.'.$key ." = '$v'";
				}

				$filter_str = implode(' or ',$filter_arr1);

				if($filter_str){
					$filter_arr[]="($filter_str)";
				}
			}
		}

		if(!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']){
			$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] = array();
		}

		if($this->post['act']=='list_search' && $this->post['list_search_filter']){
			if(!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']){
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] = $this->post['list_search_filter'];
			}else{
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'],$this->post['list_search_filter']);

			}
		}

		if($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']){
			foreach ($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] as $k=>$v){
				$k=str_replace("_____", ".", $k);

				if(!($v==='' or $v===null or $v===false)){
					replaceSingleQuote($v);
					replaceSingleQuote($k);

					$filter_arr[]="trim($k)=trim('$v')";
				}
			}
		}




		if(!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search']){
			$_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] = array();
		}

		if($this->post['act']=='list_search' && $this->post['list_search']){

			if(!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search']){
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] = $this->post['list_search'];
			}else{
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_search'],$this->post['list_search']);

			}
		}

		if($_SESSION[SESSION_APP][$this->page_ctrl]['list_search']){
			foreach ($_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] as $k=>$v){
				$k=str_replace("_____", ".", $k);

				replaceSingleQuote($v);
				replaceSingleQuote($k);

				if($k == 'kriteria1') {
					$filter_arr[]="lower(b.kode||' '||b.nama) like '%$v%'";
				} elseif($k == 'kriteria2') {
					$filter_arr[]="lower(c.kode||' '||c.nama) like '%$v%'";
				} else if(trim($v)!=='' && in_array($k, $this->arrNoquote)){
					$filter_arr[]="trim($k)=trim($v)";
				}else if($v!==''){
					$v = str_replace(" ","%",strtolower($v));
					$filter_arr[]="lower($k) like '%$v%'";
				}
			}
		}

		$this->data['filter_arr'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_search'],$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']);

		if(count($filter_arr)){
			$this->filter .= ' and '.implode(' and ', $filter_arr);
		}

		return $this->filter;
	}
}