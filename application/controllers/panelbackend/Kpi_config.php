<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Kpi_config extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/kpi_configlist";
		$this->viewdetail = "panelbackend/kpi_configdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Setting Tahunan';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Setting Tahunan';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Setting Tahunan';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Setting Tahunan';
		}

		$this->load->model("Kpi_configModel", "model");
		$this->load->model("KpiModel", "modelkpi");
		$this->data['jenisrealisasiarr'] = array('akumulatif' => 'Akumulatif', 'progresif' => 'Progresif', 'average' => 'Average');

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'treetable'
		);
	}

	protected function Header()
	{
		return array(
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
				'label' => 'Jenrealiasi',
				'width' => "auto",
				'type' => "list",
				'value' => array('' => '-pilih-', '0' => 'Tidak', '1' => 'Iya'),
			),
			array(
				'name' => 'jenis_realisasi_direktorat',
				'label' => 'Jenrealisasi Direktorat',
				'width' => "auto",
				'type' => "list",
				'value' => array('' => '-pilih-', '0' => 'Tidak', '1' => 'Iya'),
			),
			array(
				'name' => 'jenis_realisasi_korporat',
				'label' => 'Jenrealisasi Korporat',
				'width' => "auto",
				'type' => "list",
				'value' => array('' => '-pilih-', '0' => 'Tidak', '1' => 'Iya'),
			),
		);
	}

	protected function Record($id = null)
	{
		if ($this->post['jenis_direktorat'] == 1)
			$this->post['is_direktorat'] = 1;
		elseif ($this->post['jenis_direktorat'] == 2)
			$this->post['is_bersama'] = 1;

		return array(
			'tahun' => $this->post['tahun'],
			'is_bersama' => (int)$this->post['is_bersama'],
			'is_direktorat' => (int)$this->post['is_direktorat'],
			'is_korporat' => (int)$this->post['is_korporat'],
			'jenis_realisasi' => $this->post['jenis_realisasi'],
			'jenis_realisasi_direktorat' => $this->post['jenis_realisasi_direktorat'],
			'jenis_realisasi_korporat' => $this->post['jenis_realisasi_korporat'],
		);
	}

	protected function Rules()
	{
		return array(
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
			"jenis_realisasi" => array(
				'field' => 'jenis_realisasi',
				'label' => 'Jenis Realiasi',
				'rules' => "max_length[45]",
			),
			"jenis_realisasi_direktorat" => array(
				'field' => 'jenis_realisasi_direktorat',
				'label' => 'Jenis Realisasi Direktorat',
				'rules' => "max_length[45]",
			),
			"jenis_realisasi_korporat" => array(
				'field' => 'jenis_realisasi_korporat',
				'label' => 'Jenis Realisasi Korporat',
				'rules' => "max_length[45]",
			),
			"tahun" => array(
				'field' => 'tahun',
				'label' => 'Tahun',
				'rules' => "required|max_length[45]",
			),
		);
	}


	public function Index($id_kpi = null, $tahun = null)
	{
		redirect("panelbackend/kpi/detail/$id_kpi");
	}

	protected function _beforeDetail($id_kpi = null, $tahun = null)
	{
		$this->data['add_param'] .= $id_kpi;
		if ($tahun)
			$this->data['add_param'] .= "/" . $tahun;

		$this->data['rowheader'] = $this->modelkpi->GetByPk($id_kpi);
	}

	public function Add($id_kpi = null, $tahun = null)
	{
		$this->Edit($id_kpi, $tahun);
	}

	public function Edit($id_kpi = null, $tahun = null)
	{
		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		if ($this->post['tahun'])
			$tahun = $this->post['tahun'];

		$this->_beforeDetail($id_kpi, $tahun);

		$this->data['row'] = $this->model->GetByPk($id_kpi, $tahun);

		$isadd = false;
		if (!$this->data['row'])
			$isadd = true;

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");

		if ($this->post && $this->post['act'] <> 'change') {
			if (!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id_kpi, $tahun);

			$this->data['row'] = array_merge($this->data['row'], $record);
			$this->data['row'] = array_merge($this->data['row'], $this->post);
		}

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {
			$record['id_kpi'] = $id_kpi;

			$this->_isValid($record, true);

			$this->_beforeEdit($record, $id_kpi, $tahun);

			$this->_setLogRecord($record, $id_kpi, $tahun);

			$this->model->conn->StartTrans();
			if (!$isadd) {

				$return = $this->_beforeUpdate($record, $id_kpi, $tahun);

				if ($return) {
					$return = $this->model->Update($record, $id_kpi, $tahun);
				}

				if ($return['success']) {

					$this->log("mengubah", $this->data['row']);

					$return1 = $this->_afterUpdate($id_kpi, $tahun);

					if (!$return1) {
						$return = false;
					}
				}
			} else {

				$return = $this->_beforeInsert($record);

				if ($return) {
					$return = $this->model->Insert($record);
				}

				if ($return['success']) {

					$this->log("menambah", $record);

					$return1 = $this->_afterInsert($id_kpi, $tahun);

					if (!$return1) {
						$return = false;
					}
				}
			}

			$this->model->conn->CompleteTrans();

			if ($return['success']) {

				$this->_afterEditSucceed($id_kpi, $tahun);

				SetFlash('suc_msg', $return['success']);
				redirect("$this->page_ctrl/detail/$id_kpi/$tahun");
			} else {
				$this->data['row'] = array_merge($this->data['row'], $record);
				$this->data['row'] = array_merge($this->data['row'], $this->post);

				$this->_afterEditFailed($id_kpi, $tahun);

				$this->data['err_msg'] = "Data gagal disimpan";
			}
		}

		$this->_afterDetail($id_kpi, $tahun);

		$this->View($this->viewdetail);
	}

	public function Detail($id_kpi = null, $tahun = null)
	{

		$this->_beforeDetail($id_kpi, $tahun);

		$this->data['row'] = $this->model->GetByPk($id_kpi, $tahun);

		if (!$this->data['row'])
			$this->NoData();

		$this->_afterDetail($id_kpi, $tahun);

		$this->View($this->viewdetail);
	}

	public function Delete($id_kpi = null, $tahun = null)
	{

		$this->model->conn->StartTrans();

		$this->_beforeDetail($id_kpi, $tahun);

		$this->data['row'] = $this->model->GetByPk($id_kpi, $tahun);

		if (!$this->data['row'])
			$this->NoData();

		$return = $this->_beforeDelete($id_kpi, $tahun);

		if ($return) {
			$return = $this->model->delete($id_kpi, $tahun);
		}

		if ($return) {
			$return1 = $this->_afterDelete($id_kpi, $tahun);
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
			redirect("$this->page_ctrl/detail/$id_kpi/$tahun");
		}
	}

	protected function _afterInsert($id_kpi = null, $tahun = null)
	{
		$ret = true;

		$tahun = date("Y");
		$row = $this->model->GetByPk($id_kpi, $tahun);

		$return = $this->modelkpi->Update($row, "id_kpi = " . $this->conn->escape($id_kpi));
		$ret = $return['success'];

		return $ret;
	}

	protected function _afterUpdate($id_kpi = null, $tahun = null)
	{
		return $this->_afterInsert($id_kpi, $tahun);
	}

	protected function _afterDetail($id_kpi = null, $tahun = null)
	{
		if (!$this->data['edited']) {
			$rowk = $this->conn->GetRow("select 'A' as id, 
			'Korporat' as nama, 
			a.* 
			from kpi_target a 
			where a.deleted_date is null and a.jenis='Korporat' 
			and a.tahun = " . $this->conn->escape($tahun) . " 
			and a.id_kpi = " . $this->conn->escape($id_kpi));

			$rowsd = $this->conn->GetArray("select a.id_dit_bid as id, 'A' as id_parent, 
			case when c.nama is null then 'Bersama' else c.nama end as nama,
			a.*
			from kpi_target a 
			left join mt_sdm_dit_bid c on a.id_dit_bid = c.code
			where a.deleted_date is null and a.jenis='Direktorat' 
			and a.tahun = " . $this->conn->escape($tahun) . " 
			and a.id_kpi = " . $this->conn->escape($id_kpi));
			$rowsdarr = array();
			foreach ($rowsd as $r) {

				if ($this->data['row']['is_bersama']) {
					$r['id'] = $r['id_dit_bid'] = 'bersama';
				}

				$rowsdarr[$r['id_dit_bid']] = $r;
			}

			$rowsu = $this->conn->GetArray("select a.*, c.table_desc as nama 
			from kpi_target a 
			left join mt_sdm_unit c on a.id_unit = c.table_code
			where a.deleted_date is null and (a.jenis='Unit' or a.jenis is null)
			and a.tahun = " . $this->conn->escape($tahun) . " 
			and a.id_kpi = " . $this->conn->escape($id_kpi));

			$rowsuarr = array();
			foreach ($rowsu as $r) {
				if ($this->data['row']['is_bersama']) {
					$id_dit_bid = 'bersama';
				} else {
					$id_dit_bid = $this->conn->GetOne("select id_dit_bid from mt_sdm_jabatan where deleted_date is null and id_unit = '" . $r['id_unit'] . "' and id_dit_bid is not null");
				}

				$r['id'] = $r['id_unit'];
				$r['id_parent'] = $id_dit_bid;
				$rowsuarr[$id_dit_bid][] = $r;
			}

			$rows = array();
			if ($rowk)
				$rows[] = $rowk;

			foreach ($rowsuarr as $k => $rs) {
				$isnoparent = true;
				if ($rowsdarr[$k]) {
					$isnoparent = false;
					$rows[] = $rowsdarr[$k];
					unset($rowsdarr[$k]);
				}

				foreach ($rs as $r) {
					if ($isnoparent)
						$r['id_parent'] = 'A';

					$rows[] = $r;
				}
			}

			foreach ($rowsdarr as $k => $r) {
				$rows[] = $r;
			}

			$this->data['rowstarget'] = $rows;
		}

		return true;
	}
}
