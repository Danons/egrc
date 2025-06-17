<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Sasaran_unit_kerja extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/sasaran_unit_kerjalist";
		$this->viewdetail = "panelbackend/sasaran_unit_kerjadetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_profil_kpi";

		$this->data['notab'] = true;

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Sasaran, Strategi Unit Kerja, Target';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Sasaran, Strategi Unit Kerja, Target';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Sasaran, Strategi Unit Kerja, Target';
			$this->data['edited'] = false;
		} elseif ($this->mode == 'index') {
			$this->data['edited'] = true;
			$this->data['page_title'] = 'Sasaran, Strategi Unit Kerja, Target';
		}

		$this->load->model("Risk_kpiModel", "riskkpi");
		$this->data['riskkpiarr'] = $this->riskkpi->GetCombo();
		unset($this->data['riskkpiarr']['']);

		$this->load->model("Mt_risk_taksonomi_objectiveModel", "risktaksonomi");
		$this->data['risktaksonomiobjectivearr'] = $this->risktaksonomi->GetCombo();

		$this->load->model("Risk_kegiatanModel", "model");

		$this->data['ownerarr'] = $this->conn->GetList("select a.id_scorecard AS idkey, a.nama AS val 
		from risk_scorecard a left join mt_sdm_jabatan s on a.owner = s.id_jabatan and a.deleted_date is null where 1=1 AND a.id_parent_scorecard IS NULL 
		order by a.navigasi, s.position_id, idkey");
		$this->data['ownerarr'][""] = 'pilih...';

		$this->data['deskripsiKpiArr'] = $this->conn->GetList("select id_kpi as idkey, deskripsi as val from risk_kpi where deleted_date is null");

		$this->load->model("Mt_sdm_unitModel", "munit");
		$this->data['unitarr'] = $this->munit->GetCombo();

		// if($this->access('view_all')){

		// 	$this->data['unitarr'] = $this->conn->GetList("select table_code as idkey, table_desc as val from mt_sdm_unit where deleted_date is null  ");
		// }else{
		// 	$this->data['unitarr'] = $this->conn->GetList("select table_code as idkey, table_desc as val from mt_sdm_unit where deleted_date is null and table_code = " . $_SESSION[SESSION_APP]['id_unit']);
		// }
		// $this->data['unitarr'][""] = 'pilih...';

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
			'id_unit' => $this->post['id_unit'],
			'owner' => $this->data['owner'],
			'id_scorecard' => $this->post['id_unit'],
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
			"id_unit" => array(
				'field' => 'id_unit',
				'label' => 'Unit',
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
	public function Index($page = 0)
	{
		// dpr($_SESSION[SESSION_APP],1);
		$this->_beforeDetail();
		if ($this->post['id_unit_filter'])
			$this->_setFilter("id_unit = " . $this->conn->escape($this->post['id_unit_filter']));

		$this->data['list'] = $this->_getList($page);
		$id_unit_filter = null;

		$this->data['header'] = $this->Header();
		$this->data['page'] = $page;
		$param_paging = array(
			'base_url' => base_url("{$this->page_ctrl}/index/"),
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

		$this->data['filter_arr']['id_unit_filter'] = '';

		$this->data['unitarr'][""] = 'pilih unit...';
		if ($this->access('view_all')) {
			$this->data['page_title'] .= "&nbsp;" . UI::createSelect("id_unit_filter", $this->data['unitarr'], $this->post['id_unit_filter'], true, "filter-title form-control", "style='width:300px; display:inline' onchange='goSubmit(\"set_value\")'");
		}

		// else{
		// 	$this->post['id_unit_filter'] = $_SESSION[SESSION_APP]['id_unit'];
		// }

		// if ($this->post['id_unit_filter']) {
		// 	$this->data['list']['rows'] = $this->conn->GetArray('select * from risk_kegiatan where deleted_date is null and id_unit = ' . $this->conn->escape($this->post['id_unit_filter']) . 'order by id_kegiatan desc');

		// }

		$this->View($this->viewlist);
	}

	public function Add()
	{

		$this->Edit();
	}

	public function Edit($id = null)
	{

		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->_beforeDetail();

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'] && $id)
			$this->NoData();


		if ($this->post['id_unit']) {
			$this->data['owner'] = $this->conn->GetOne("select owner from risk_scorecard where deleted_date is null and id_scorecard = " . $this->conn->escape($this->post['id_unit']));
		}

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

				redirect("$this->page_ctrl/index/");
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
					$record['id_unit_kerja'] = $this->post['id_unit'];

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
		$return = $this->conn->Execute("update risk_kegiatan_kpi set deleted_date = now() where id_kegiatan = " . $this->conn->escape($id));

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

	public function Detail($id = null)
	{

		$this->_beforeDetail();

		$this->data['row'] = $this->model->GetByPk($id);
		// dpr($this->data['row'], 1);

		if (!$this->data['row'])
			$this->NoData();

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	protected function _beforeDelete($id = null)
	{
		$cek = $this->conn->GetOne("select nomor from risk_risiko where deleted_date and id_kegiatan = " . $this->conn->escape($id));

		$return = $this->model->Execute("update risk_kegiatan_kpi set deleted_date = now() where id_kegiatan = " . $this->conn->escape($id));

		if ($cek) {
			SetFlash('err_msg', "Sasaran kegiatan sudah dipakai di risiko nomor " . $cek);
			redirect("$this->page_ctrl/detail//$id");
			die();
		}

		return true;
	}

	public function Delete($id = null)
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

	protected function _beforeDetail($id = null)
	{

		#mengambil dari model karena sudah difilter sesuai akses
		$this->load->model("Risk_scorecardModel", 'riskscorecard');
		$this->data['rowheader']  = $this->conn->GetArray('select * from risk_scorecard where deleted_date is null ');
		if (!$this->data['rowheader'])
			$this->NoData();


		$this->load->model("Risk_sasaranModel", "msasaran");

		$this->data['sasaranarr'] = $this->msasaran->GetCombo($owner);
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
