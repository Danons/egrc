<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Risk_kri_hasil extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/risk_kri_hasillist";
		$this->viewdetail = "panelbackend/risk_kri_hasildetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_kri";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah KRI Hasil';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit KRI Hasil';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail KRI Hasil';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'KRI Hasil';
		}

		$this->load->model("Risk_kri_hasilModel", "model");

		$this->load->model("Risk_kriModel", "riskkri");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker'
		);
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'tahun',
				'label' => 'Tahun',
				'width' => "auto",
				'type' => "number",
			),
			array(
				'name' => 'bulan',
				'label' => 'Bulan',
				'width' => "auto",
				'type' => "list",
				'value' => ListBulan()
			),
			array(
				'name' => 'nilai',
				'label' => 'Nilai',
				'width' => "auto",
				'type' => "number",
			),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'nilai' => $this->post['nilai'],
			'id_kri' => $this->post['id_kri'],
			'id_periode_tw' => $this->post['id_periode_tw'],
			'create_date' => $this->post['create_date'],
			'target_mulai' => $this->post['target_mulai'],
			'target_sampai' => $this->post['target_sampai'],
			'batas_atas' => $this->post['batas_atas'],
			'batas_bawah' => $this->post['batas_bawah'],
			'tahun' => $this->post['tahun'],
			'bulan' => $this->post['bulan'],
		);
	}

	protected function Rules()
	{
		return array(
			"nilai" => array(
				'field' => 'nilai',
				'label' => 'Nilai',
				'rules' => "numeric|max_length[10]",
			),
			"id_periode_tw" => array(
				'field' => 'id_periode_tw',
				'label' => 'Periode TW',
				'rules' => "integer|max_length[10]",
			),
			"create_date" => array(
				'field' => 'create_date',
				'label' => 'Create Date',
				'rules' => "",
			),
			"target_mulai" => array(
				'field' => 'target_mulai',
				'label' => 'Target Mulai',
				'rules' => "numeric|max_length[10]",
			),
			"target_sampai" => array(
				'field' => 'target_sampai',
				'label' => 'Target Sampai',
				'rules' => "numeric|max_length[10]",
			),
			"batas_atas" => array(
				'field' => 'batas_atas',
				'label' => 'Batas Atas',
				'rules' => "numeric|max_length[10]",
			),
			"batas_bawah" => array(
				'field' => 'batas_bawah',
				'label' => 'Batas Bawah',
				'rules' => "numeric|max_length[10]",
			),
			"tahun" => array(
				'field' => 'tahun',
				'label' => 'Tahun',
				'rules' => "numeric|max_length[10]",
			),
			"bulan" => array(
				'field' => 'bulan',
				'label' => 'Bulan',
				'rules' => "max_length[2]",
			),
		);
	}

	protected function _beforeDetail($id_kri = null, $tahun = null, $id_kri_hasil = null)
	{
		$this->data['rowheader']  = $this->riskkri->GetByPk($id_kri);
		$this->data['rowheader']['tahun'] = $tahun;

		if (!$this->data['rowheader'])
			$this->NoData();

		$r = $this->data['rowheader'];
		$rw = $this->conn->GetRow("select * from risk_kri_hasil 
			where deleted_date is null and tahun = " . $this->conn->escape($tahun) . " 
			and id_kri = " . $this->conn->escape($r['id_kri']) . " 
			and bulan = (select max(bulan) from risk_kri_hasil 
			where tahun = " . $this->conn->escape($tahun) . " 
			and id_kri = " . $this->conn->escape($r['id_kri']) . ")");
		$this->data['rowheader']['lastinput'] = $rw['bulan'];

		$this->data['add_param'] .= $id_kri . "/" . $tahun;
	}

	public function Index($id_kri = null, $tahun = null, $page = 0)
	{
		$this->_beforeDetail($id_kri, $tahun);
		$this->_setFilter("id_kri = " . $this->conn->qstr($id_kri));
		$this->_setFilter("tahun = " . $this->conn->qstr($tahun));
		$this->data['editedevaluasi'] = true;
		$this->data['editedanalisa'] = true;

		$param = array(
			'page' => $page,
			'limit' => -1,
			'order' => $this->_order(),
			'filter' => $this->_getFilter()
		);

		$this->data['list'] = $this->model->SelectGrid(
			$param
		);

		$this->View($this->viewlist);
	}

	public function Delete($id_kri = null, $tahun = null, $id = null)
	{

		$this->model->conn->StartTrans();

		$this->_beforeDetail($id_kri, $tahun, $id);

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
			redirect("$this->page_ctrl/index/$id_kri/$tahun");
		} else {
			SetFlash('err_msg', "Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id_kri/$tahun/$id");
		}
	}

	public function Detail($id_kri = null, $tahun = null, $id = null)
	{

		$this->_beforeDetail($id_kri, $tahun, $id);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	public function Add($id_kri = null, $tahun = null)
	{
		$this->Edit($id_kri, $tahun);
	}

	public function Edit($id_kri = null, $tahun = null, $id = null)
	{

		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->_beforeDetail($id_kri, $tahun, $id);

		$this->data['idpk'] = $id;

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'] && $id)
			$this->NoData();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");

		if ($this->post && $this->post['act'] <> 'change') {
			if (!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id);

			$this->data['row'] = array_merge($this->data['row'], $record);
			$this->data['row'] = array_merge($this->data['row'], $this->post);
		}

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {

			$idt = $this->conn->GetOne("select id_kri_hasil from risk_kri_hasil 
				where deleted_date is null and bulan = " . $this->conn->escape($record['bulan']) . " 
				and id_kri = " . $this->conn->escape($id_kri) . " 
				and tahun = " . $this->conn->escape($tahun));

			if ($idt)
				$this->data['row'][$this->pk] = $id = $idt;

			$record['id_kri'] = $id_kri;
			$record['tahun'] = $tahun;

			$this->_isValid($record, true);

			$this->_beforeEdit($record, $id);

			$this->_setLogRecord($record, $id);

			$this->model->conn->StartTrans();
			if (trim($this->data['row'][$this->pk]) == trim($id) && trim($id)) {

				$return = $this->_beforeUpdate($record, $id);

				if ($return) {
					$return = $this->model->Update($record, "$this->pk = " . $this->conn->qstr($id));
				}

				if ($return['success']) {

					$this->log("mengubah", $this->data['row']);

					$return1 = $this->_afterUpdate($id);

					if (!$return1) {
						$return = false;
					}
				}
			} else {

				$return = $this->_beforeInsert($record);

				if ($return) {
					$return = $this->model->Insert($record);
					$id = $return['data'][$this->pk];
				}

				if ($return['success']) {

					$this->log("menambah", $record);

					$return1 = $this->_afterInsert($id);

					if (!$return1) {
						$return = false;
					}
				}
			}

			$this->model->conn->CompleteTrans();

			if ($return['success']) {

				$this->_afterEditSucceed($id);

				SetFlash('suc_msg', $return['success']);
				redirect("$this->page_ctrl/index/$id_kri/$tahun");
			} else {
				$this->data['row'] = array_merge($this->data['row'], $record);
				$this->data['row'] = array_merge($this->data['row'], $this->post);

				$this->_afterEditFailed($id);

				$this->data['err_msg'] = "Data gagal disimpan";
			}
		}

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}
}
