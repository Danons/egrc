<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Mt_risk_taksonomi extends _adminController
{

	public $limit = -1;
	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/mt_risk_taksonomilist";
		$this->viewdetail = "panelbackend/mt_risk_taksonomidetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Taksonomi Risiko';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Taksonomi Risiko';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Taksonomi Risiko';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Taksonomi';
		}

		$this->data['width'] = "800px";

		unset($this->access_role['add']);

		$this->load->model("Mt_risk_taksonomiModel", "model");
		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'kode',
				'label' => 'Kode',
				'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'nama',
				'label' => 'Nama',
				'width' => "auto",
				'type' => "varchar2",
			),
		);
	}

	public function Detail($id = null)
	{
		redirect("panelbackend/mt_risk_taksonomi");;
	}

	protected function Record($id = null)
	{
		return array(
			'kode' => $this->post['kode'],
			'penjelasan' => $this->post['penjelasan'],
			'nama' => $this->post['nama'],
			'is_regulasi' => (int)$this->post['is_regulasi'],
			'is_aktif' => (int)$this->post['is_aktif'],
		);
	}

	protected function Rules()
	{
		return array(
			"kode" => array(
				'field' => 'kode',
				'label' => 'Kode',
				'rules' => "required|max_length[20]",
			),
			"nama" => array(
				'field' => 'nama',
				'label' => 'Nama',
				'rules' => "required|max_length[200]",
			),
		);
	}


	public function Index($page = 0)
	{
		$this->layout = "panelbackend/layout1";
		$where = " where 1=1 and deleted_date is null ";
		// if (!$this->post['jenis'] && $_SESSION[SESSION_APP][$this->page_ctrl]['jenis'])
		// 	$this->post['jenis'] = $_SESSION[SESSION_APP][$this->page_ctrl]['jenis'];
		// if ($this->post['act'] == 'set_value' && $this->post['jenis']) {
		// if ($this->post['jenis']) {
		if ($this->post)
			$_SESSION[SESSION_APP][$this->page_ctrl]['jenis'] = $this->post['jenis'];

		$jenis = $_SESSION[SESSION_APP][$this->page_ctrl]['jenis'];

		// if ($jenis == 'rutin')
		// 	$jenis = "
		// 		jenis = 'rutin' or 
		// 		(jenis like '%/%' and (jenis like '%/rutin/%' or jenis like '%rutin%' ))";
		// else if ($jenis == 'nonrutin')
		// 	$jenis = " b.jenis like '%nonrutin%'";
		// elseif ($jenis == 'proyek')

		// $jenis = " b.jenis like '%$jenis%'";
		if ($jenis == 'rutin')
			$where .= " and jenis = 'rutin' or 
				(jenis like '%/%' and (jenis like '%/rutin/%' or jenis like '%rutin%' ))";
		elseif ($jenis)
			$where .= " and jenis like '%$jenis%'";

		$where .= " and deleted_date is null";

		// 	$_SESSION[SESSION_APP][$this->page_ctrl]['jenis'] = $this->post['jenis'];
		// }
		$this->data['rowso'] = $this->conn->GetArray("select * from mt_risk_taksonomi_objective a  $where");

		// $rowsa = $this->conn->GetArray("select * from mt_risk_taksonomi_area order by regexp_substr(kode, '[^.]+', 1, 1), CONVERT(regexp_substr(kode, '[^.]+', 1, 2),UNSIGNED INTEGER)");
		// $rowsa = $this->conn->GetArray("select * from mt_risk_taksonomi_area order by substring(kode,1,1), cast(substring(kode,2, 1) as integer)");
		$rowsa = $this->conn->GetArray("select * from mt_risk_taksonomi_area where deleted_date is null and order by substring(kode,1,1)");
		foreach ($rowsa as $r) {
			$this->data['rowsa'][$r['id_taksonomi_objective']][] = $r;
		}

		// $rows = $this->conn->GetArray("select * from mt_risk_taksonomi where is_aktif = 1 order by regexp_substr(kode, '[^.]+', 1, 1), CONVERT(regexp_substr(kode, '[^.]+', 1, 2),UNSIGNED INTEGER), CONVERT(regexp_substr(kode, '[^.]+', 1, 3),UNSIGNED INTEGER)");
		// $rows = $this->conn->GetArray("select * from mt_risk_taksonomi where is_aktif = 1 order by substring(kode,1,1), cast(substring(kode,2, 1) as integer), cast(substring(kode,3, 1) as integer)");
		$rows = $this->conn->GetArray("select * from mt_risk_taksonomi where deleted_date is null and is_aktif = 1 order by substring(kode,1,1)");
		foreach ($rows as $r) {
			$this->data['rows'][$r['id_taksonomi_area']][] = $r;
		}
		// $this->data['page_title'] .= UI::createSelect('jenis', $this->data['jenisrunitnonnurinarr'], $_SESSION[SESSION_APP][$this->page_ctrl]['jenis'], true, 'form-control select2 ', ' " onchange=\'goSubmit("set_value")\'');


		$this->View($this->viewlist);
	}

	public function Add($id_taksonomi_area = null)
	{
		$this->Edit($id_taksonomi_area);
	}

	protected function _beforeDetail($id_taksonomi_area = null, $id = null)
	{
		$this->data['add_param'] .= $id_taksonomi_area;
	}

	public function Edit($id_taksonomi_area = null, $id = null)
	{

		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->_beforeDetail($id_taksonomi_area, $id);

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
			$record['id_taksonomi_area'] = $id_taksonomi_area;

			$this->_isValid($record, false);

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
				redirect("$this->page_ctrl/detail/$id");
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

	public function Delete($id = null)
	{

		$this->model->conn->StartTrans();

		$this->data['row'] = $this->model->GetByPk($id);

		$this->_beforeDetail($this->data['row']['id_taksonomi_area'], $id);

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
			redirect("$this->page_ctrl/detail/" . $this->data['row']['id_taksonomi_area'] . "/$id");
		}
	}
}
