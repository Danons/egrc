<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Risk_sort extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/risk_risiko_sortlist";
		$this->viewdetail = "panelbackend/risk_risiko_sortdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout2";
		$this->data['page_title'] = 'Urutan Tingkat Risiko';

		$this->load->model("Risk_risikoModel","model");
		$this->load->model("Risk_scorecardModel","modelscorecard");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	public function Index($page=0){

		$tgl_efektif = date('Y-m-d');
		$top = 10;
		$id_kajian_risiko = null;
		$id_scorecard = null;
		$order = null;
		$id_scorecardarr = array();

		if($this->post['top']){
			$this->UpdateConfig("risk_top_risiko", $this->post['top']);
		}

		if($this->post['order']){
			$this->UpdateConfig("risk_order_risiko", $this->post['order']);
		}

		if($this->post['id_scorecard'])
			$_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard'] = $this->post['id_scorecard'];

		if($this->post['id_kajian_risiko']){
			if($this->post['id_kajian_risiko']<>$_SESSION[SESSION_APP][$this->page_ctrl]['id_kajian_risiko'])
				unset($_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard']);
			
			$_SESSION[SESSION_APP][$this->page_ctrl]['id_kajian_risiko'] = $this->post['id_kajian_risiko'];
		}

		if($this->post['act']=='sort_up')
			$this->conn->Execute("update risk_risiko set urutan = coalesce(urutan,0)-1 where id_risiko = ".$this->conn->escape($this->post['idkey']));

		if($this->post['act']=='sort_down')
			$this->conn->Execute("update risk_risiko set urutan = coalesce(urutan,0)+1 where id_risiko = ".$this->conn->escape($this->post['idkey']));

		if($this->post)
			redirect(current_url());

		if($_SESSION[SESSION_APP]['tgl_efektif'])
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

		if($this->config->item('risk_top_risiko'))
			$top = $this->config->item('risk_top_risiko');

		if($this->config->item('risk_order_risiko'))
			$order = $this->config->item('risk_order_risiko');
		else
			$order = "c";

		if($_SESSION[SESSION_APP][$this->page_ctrl]['id_kajian_risiko']){
			$id_kajian_risiko = $_SESSION[SESSION_APP][$this->page_ctrl]['id_kajian_risiko'];

			$this->data['scorecardarr'] = $this->model->GetComboDashboard($id_kajian_risiko);
		}

		if($_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard']){
			$id_scorecard = $_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard'];
			$id_scorecardarr = $this->modelscorecard->GetChild($_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard']);
		}elseif($id_kajian_risiko){
			$id_scorecardarr = $this->modelscorecard->GetCombo(null, null, null, $id_kajian_risiko);
			$id_scorecardarr = array_keys($id_scorecardarr);
		}

		$this->data['id_kajian_risiko'] = $id_kajian_risiko;
		$this->data['id_scorecard'] = $id_scorecard;
		$this->data['top'] = $top;
		$this->data['order'] = $order;

		$this->data['mtriskmatrix'] = $this->conn->GetArray("select mrm.*, mrt.nama, mrt.warna
			from mt_risk_matrix mrm
			join mt_risk_tingkat mrt on mrt.id_tingkat = mrm.id_tingkat where mrm.deleted_date is null");

		$this->load->model("Risk_risikoModel","model");

		list($tgl, $bln, $thn) = explode("-",$tgl_efektif);
		$param = array(
			"rating"=>"icr",
			"id_kajian_risiko"=>$id_kajian_risiko,
			"top"=>$top,
			"all"=>false,
			"id_scorecard"=>$id_scorecardarr,
			"tahun"=>$thn,
			"bulan"=>$bln,
			"order"=>$order
		);

		foreach (str_split($param['rating']) as $idkey => $value) {
			$this->data['rating'][$value] = 1;
		}

		$this->data['rows'] = $this->model->getListRiskProfile($param);

		$this->View($this->viewlist);
	}
}