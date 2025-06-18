<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Risk_appetite extends _adminController
{

	public $limit = -1;
	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/risk_appetitelist";
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
			$this->data['page_title'] = 'Setting Risk Appetite';
		}

		$this->data['width'] = "800px";

		$this->load->model("Mt_risk_taksonomiModel", "model");
		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	public function Index($edited = 0)
	{
		if ($edited && $this->access_role['edit'])
			$this->data['edited'] = true;

		if ($this->post['act'] == "set_value") {
			$_SESSION[SESSION_APP][$this->page_ctrl]['tahun'] = $this->post['tahun'];
			redirect(current_url());
		}

		$this->layout = "panelbackend/layout1";
		$this->data['rowso'] = $this->conn->GetArray("select * from mt_risk_taksonomi_objective where deleted_date is null");

		// $rowsa = $this->conn->GetArray("select * from mt_risk_taksonomi_area order by regexp_substr(kode, '[^.]+', 1, 1), convert(regexp_substr(kode, '[^.]+', 1, 2),UNSIGNED INTEGER)");
		$rowsa = $this->conn->GetArray("select * from mt_risk_taksonomi_area where deleted_date is not null and order by substring(kode,1,1), cast(substring(kode,2, 1) as integer)");
		foreach ($rowsa as $r) {
			$this->data['rowsa'][$r['id_taksonomi_objective']][] = $r;
		}

		// $rows = $this->conn->GetArray("select * from mt_risk_taksonomi where is_aktif = 1 order by regexp_substr(kode, '[^.]+', 1, 1), convert(regexp_substr(kode, '[^.]+', 1, 2),UNSIGNED INTEGER), convert(regexp_substr(kode, '[^.]+', 1, 3),UNSIGNED INTEGER)");
		$rows = $this->conn->GetArray("select * from mt_risk_taksonomi where deleted_date is null and is_aktif = 1 order by substring(kode,1,1), cast(substring(kode,2, 1) as integer), cast(substring(kode,3, 1) as integer)");
		foreach ($rows as $r) {
			$this->data['rows'][$r['id_taksonomi_area']][] = $r;
		}

		$this->data['tahun'] = date("Y");
		if ($_SESSION[SESSION_APP][$this->page_ctrl]['tahun'])
			$this->data['tahun'] = $_SESSION[SESSION_APP][$this->page_ctrl]['tahun'];

		$this->data['tingkat'] = $this->post['tingkat'];
		$tahun = $this->data['tahun'];

		if ($this->post['act'] == "save") {
			$ret = true;
			foreach ($this->post['tingkat'] as $id_taksonomi_area => $p) {
				if(!$p['id_kemungkinan'] or !$p['id_dampak'])
					continue;
					
				if (!$ret)
					break;

				$record = array(
					"id_taksonomi_area" => $id_taksonomi_area,
					"id_dampak" => $p['id_dampak'],
					"id_kemungkinan" => $p['id_kemungkinan'],
					"tahun" => $this->data['tahun']
				);

				$cek = $this->conn->GetOne("select 1 from mt_risk_taksonomi_appetite where deleted_date is null and tahun = " . $this->conn->escape($tahun) . " and id_taksonomi_area = " . $this->conn->escape($id_taksonomi_area));
				if ($cek) {
					$ret = $this->conn->goUpdate("mt_risk_taksonomi_appetite", $record, "id_taksonomi_area = " . $this->conn->escape($id_taksonomi_area) . " and tahun = " . $this->conn->escape($tahun));
				} else {
					$ret = $this->conn->goInsert("mt_risk_taksonomi_appetite", $record);
				}
			}

			if($ret){
				SetFlash("suc_msg", "Data berhasil disimpan");
				redirect("panelbackend/risk_appetite");
			}else
				$this->data['err_msg'] = "Data gagal disimpan";
		}

		if (!$this->data['tingkat']) {
			$rows = $this->conn->GetArray("select * from mt_risk_taksonomi_appetite where deleted_date is null and tahun = " . $this->conn->escape($this->data['tahun']));
			foreach ($rows as $r) {
				$this->data['tingkat'][$r['id_taksonomi_area']] = $r;
			}
		}

		$this->View($this->viewlist);
	}
}
