<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Risk_kegiatan extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/risk_kegiatanlist";
		$this->viewdetail = "panelbackend/risk_kegiatandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_scorecard";

		$this->data['notab'] = true;

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Sasaran Kegiatan';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Sasaran Kegiatan';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Sasaran Kegiatan';
			$this->data['edited'] = false;
		} elseif ($this->mode == 'index') {
			$this->data['edited'] = true;
			$this->data['page_title'] = 'Daftar Sasaran Kegiatan';
		}

		$this->load->model("Risk_kpiModel", "riskkpi");
		$this->data['riskkpiarr'] = $this->riskkpi->GetCombo();
		unset($this->data['riskkpiarr']['']);

		$this->load->model("Mt_risk_taksonomi_objectiveModel", "risktaksonomi");
		$this->data['risktaksonomiobjectivearr'] = $this->risktaksonomi->GetCombo();

		$this->load->model("Risk_kegiatanModel", "model");

		$this->data['deskripsiKpiArr'] = $this->conn->GetList("select id_kpi as idkey, deskripsi as val from risk_kpi where deleted_date is null");

		$this->SetAccess('panelbackend/risk_scorecard');

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'select2'
		);
	}

	protected function Record($id = null)
	{
		$this->AddOption();
		$record =  array(
			'nama' => $this->post['nama'],
			'deskripsi' => $this->post['deskripsi'],
			'target_sasaran' => $this->post['target_sasaran'],
			'kpi' => $this->post['kpi'],
			'owner' => $this->data['rowheader']['owner'],
			'id_scorecard' => $this->data['rowheader']['id_scorecard'],
			'kpi_deskripsi' => $this->post['kpi_deskripsi'],
			'id_sasaran' => $this->post['id_sasaran'],
			'id_risk_taksonomi_objective' => $this->post['id_risk_taksonomi_objective'],
			'tujuan_kegiatan' => $this->post['tujuan_kegiatan'],
			'keselarasan' => $this->post['keselarasan'],
		);

		return $record;
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'id_sasaran',
				'label' => 'Sasaran',
				'width' => "300px",
				'type' => "list",
				'value' => $this->data['sasaranarr'],
			),
			array(
				'name' => 'nama',
				'label' => 'Kegiatan',
				'width' => "auto"
			),
			array(
				'name' => 'keselarasan',
				'label' => 'Keselarasan dengan tujuan/sasaran strategis',
				'width' => "150px",
				'type' => "list",
				"value" => ["" => "", "Selaras" => "Selaras", "Tidak Selaras" => "Tidak Selaras"]
			),
			array(
				'name' => 'id_risk_taksonomi_objective',
				'label' => 'Kategori',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['risktaksonomiobjectivearr'],
			),
			array(
				'name' => 'target_sasaran',
				'label' => 'Target Sasaran',
				'width' => "auto"
			),
		);
	}

	protected function Rules()
	{
		return array(
			"name" => array(
				'field' => 'nama',
				'label' => 'Nama',
				'rules' => "required|max_length[200]",
			),
			"id_sasaran" => array(
				'field' => 'id_sasaran',
				'label' => 'Sasaran',
				'rules' => "required",
			),
			"target_sasaran" => array(
				'field' => 'target_sasaran',
				'label' => 'Target Sasaran',
				'rules' => "required",
			),
			"tujuan_kegiatan" => array(
				'field' => 'tujuan_kegiatan',
				'label' => 'Tujuan Kegiatan',
				'rules' => "required",
			),
			"keselarasan" => array(
				'field' => 'keselarasan',
				'label' => 'Keselarasan',
				'rules' => "required",
			),
			"id_risk_taksonomi_objective" => array(
				'field' => 'id_risk_taksonomi_objective',
				'label' => 'Kategori',
				'rules' => "required",
			),
			"id_kpi[]" => array(
				'field' => 'id_kpi[]',
				'label' => 'KPI',
				'rules' => "required",
			),
			"kpi_deskripsi" => array(
				'field' => 'kpi_deskripsi',
				'label' => 'Deskripsi KPI',
				'rules' => "max_length[500]",
			),
			"deskripsi" => array(
				'field' => 'deskripsi',
				'label' => 'Deskripsi',
				'rules' => "max_length[4000]",
			),
			"id_sasaran" => array(
				'field' => 'id_sasaran',
				'label' => 'Sasaran',
				'rules' => "required|in_list[" . implode(",", array_keys($this->data['sasaranarr'])) . "]",
			),
		);
	}

	public function inlistjabatan($str)
	{
		$result = $this->mjabatan->GetCombo($str);

		if (!$result[$str]) {
			$this->form_validation->set_message('inlistjabatan', 'Jabatan tidak ditemukan');
			return FALSE;
		}

		return true;
	}
	public function Index($id_scorecard = null, $page = 0)
	{
		$this->_beforeDetail($id_scorecard);
		$this->_setFilter("deleted_date is null and id_unit = " . $this->conn->qstr($this->data['rowheader']['id_unit']));
		$this->data['list'] = $this->_getList($page);

		$this->data['header'] = $this->Header();
		$this->data['page'] = $page;
		$param_paging = array(
			'base_url' => base_url("{$this->page_ctrl}/index/$id_scorecard"),
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

	public function Add($id_scorecard = null)
	{

		$this->Edit($id_scorecard);
	}

	public function Edit($id_scorecard = null, $id = null)
	{

		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->_beforeDetail($id_scorecard);

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

		if ($this->post['id_kpi']) {
			$this->data['row']['kpi_deskripsi'] = "";
			$no = 0;
			foreach ($this->post['id_kpi'] as $kpi) {
				$no++;
				$this->data['row']['kpi_deskripsi'] .= $no . ". " . $this->data['deskripsiKpiArr'][$kpi] . "&nbsp;&#13;&#10;";
			}
		}

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {
			$this->_isValid($record, true);

			$this->_beforeEdit($record, $id);

			$this->_setLogRecord($record, $id);

			$this->model->conn->StartTrans();
			if ($this->data['row'][$this->pk] == $id && $id) {

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

				$id_sasaran = $this->data['row']['id_sasaran'];

				redirect("$this->page_ctrl/index/$id_scorecard");
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

	protected function AddOption()
	{

		if (($this->post['id_kpi'])) {
			foreach ($this->post['id_kpi'] as $k => $value) {
				$v = $value;

				$ada = $this->data['riskkpiarr'][$v];
				if (!$ada && $v) {
					$record = array();
					$record['nama'] = $v;

					$sql = $this->conn->InsertSQL("risk_kpi", $record);
					$this->conn->Execute($sql);

					$this->post['id_kpi'][$k] = $_POST['id_kpi'][$k] = $id = $this->conn->GetOne("select id_kpi from risk_kpi where deleted_date is null and nama = '{$record['nama']}'");

					$this->data['riskkpiarr'][$id] = $record['nama'];
				}
			}
		}
	}

	protected function _afterUpdate($id)
	{
		$ret = $this->_delSertKpi($id);

		return $ret;
	}

	protected function _afterInsert($id)
	{
		$ret = $this->_delSertKpi($id);

		return $ret;
	}

	private function _delSertKpi($id)
	{
		$return = $this->conn->Execute("update risk_kegiatan_kpi where deleted_date is null and id_kegiatan = " . $this->conn->escape($id));

		if (is_array($this->post['id_kpi'])) {
			foreach ($this->post['id_kpi'] as $key => $value) {
				if ($return) {
					if (!$value)
						continue;

					$record = array();
					$record['id_kegiatan'] = $id;
					$record['id_kpi'] = $value;

					$sql = $this->conn->InsertSQL("risk_kegiatan_kpi", $record);

					if ($sql) {
						$return = $this->conn->Execute($sql);
					}
				}
			}
		}
		return $return;
	}

	public function Detail($id_scorecard = null, $id = null)
	{

		$this->_beforeDetail($id_scorecard);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	protected function _beforeDelete($id_scorecard = null, $id = null)
	{
		$cek = $this->conn->GetOne("select nomor from risk_risiko where deleted_date is null and id_kegiatan = " . $this->conn->escape($id));

		$return = $this->model->Execute("update risk_kegiatan_kpi where id_kegiatan = " . $this->conn->escape($id));

		if ($cek) {
			SetFlash('err_msg', "Sasaran kegiatan sudah dipakai di risiko nomor " . $cek);
			redirect("$this->page_ctrl/detail/$id_scorecard/$id");
			die();
		}

		return true;
	}

	public function Delete($id_scorecard = null, $id = null)
	{

		$this->model->conn->StartTrans();

		$this->_beforeDetail($id_scorecard);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$return = $this->_beforeDelete($id_scorecard, $id);

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
			redirect("$this->page_ctrl/index/$id_scorecard/$id");
		} else {
			SetFlash('err_msg', "Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id_scorecard/$id");
		}
	}

	protected function _beforeDetail($id = null)
	{

		if (!$id)
			redirect('panelbackend/risk_scorecard/daftarscorecard');

		#mengambil dari model karena sudah difilter sesuai akses
		$this->load->model("Risk_scorecardModel", 'riskscorecard');
		$this->data['rowheader']  = $this->riskscorecard->GetByPk($id);
		// dpr($this->data['rowheader'], 1);
		if (!$this->data['rowheader'])
			$this->NoData();

		$owner = $this->data['rowheader']['owner'];
		// dpr($owner, 1);

		if ($owner) {
			$this->data['ownerarr'][$owner] = $this->conn->GetOne("select concat(a.nama,' (',ifnull(b.table_desc,''),')') from mt_sdm_jabatan a left join mt_sdm_unit b on a.id_unit = b.table_code where a.deleted_date is null and id_jabatan = " . $this->conn->escape($owner));
		}

		$this->load->model("Risk_sasaranModel", "msasaran");

		$this->data['sasaranarr'] = $this->msasaran->GetCombo($owner);

		$this->data['add_param'] .= $id . '/' . $id_sasaran;
	}

	protected function _afterDetail($id = null)
	{
		// if (!$this->data['row'])
		// 	$this->data['row'] = $this->data['row'];

		if ($this->data['row']['id_sasaran'])
			$id_sasaran = $this->data['row']['id_sasaran'];

		$this->data['sasaranarr'][$id_sasaran] = $this->msasaran->GetNama($id_sasaran);

		if (!($this->data['row']['id_kpi'])) {
			$id_kpiarr = array();

			$mtsdmkpiarr = $this->conn->GetArray("select id_kpi from risk_kegiatan_kpi where deleted_date is null and id_kegiatan = " . $this->conn->escape($id));

			foreach ($mtsdmkpiarr as $key => $value) {
				$id_kpiarr[] = $value['id_kpi'];
			}

			$this->data['row']['id_kpi'] = $id_kpiarr;
		}
	}
}
