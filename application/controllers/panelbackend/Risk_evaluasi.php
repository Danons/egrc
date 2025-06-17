<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Risk_evaluasi extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/risk_evaluasilist";
		$this->viewdetail = "panelbackend/risk_evaluasidetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_scorecard";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Evaluasi';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Evaluasi';
			$this->data['edited'] = true;
			unset($this->access_role['lst']);
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Evaluasi';
			$this->data['edited'] = false;
			unset($this->access_role['lst']);
		} else {
			$this->data['page_title'] = 'Evaluasi';
		}

		$this->load->model("Risk_risikoModel", "model");

		$this->load->model("Mt_risk_kriteria_dampakModel", 'kriteria');
		$this->data['kriteriaarr'] = $this->kriteria->GetCombo();

		$this->load->model("Mt_risk_efektifitas_pengukuranModel", "pengukuran");
		$this->data['mtpengukuranarr'] = $this->pengukuran->GetCombo();

		$this->SetAccess('panelbackend/risk_scorecard');

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'select2', 'tinymce'
		);

		$this->data['id_periode_tw_kri'] = 2;
		$this->data['tahun_kri'] = $this->data['thn'] = date("Y");
	}

	protected function Record($id = null)
	{

		$record =  array(
			'benefit_potential' => $this->post['benefit_potential'],
			'is_accept' => (int)$this->post['is_accept']
		);

		return $record;
	}

	protected function Rules()
	{
		$return = array(
			"benefit_potential" => array(
				'field' => 'benefit_potential',
				'label' => 'Hasil Mitigasi Terhadap Sasaran',
				'rules' => "max_length[4000]|required",
			),
		);



		if (!$this->Access("pengajuan", "panelbackend/risk_scorecard"))
			unset($return['hasil_mitigasi_terhadap_sasaran']);

		return $return;
	}

	public function Index($id = null)
	{
		redirect("panelbackend/risk_evaluasi/detail/$id");
	}

	public function Add($id_scorecard = null)
	{
		$this->Error403();
	}

	public function Edit($id = null)
	{

		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->data['row'] = $this->model->GetByPk($id);

		$this->_beforeDetail($id);

		if (!$this->data['row'] && $id)
			$this->NoData();

		$this->data['rowheader1'] = $this->data['row'];

		$this->isLock();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");

		if ($this->post && $this->post['act'] <> 'change' && $this->post['act'] <> 'set_tgl_risiko') {
			if (!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id);


			$this->data['row'] = array_merge($this->data['row'], $record);
		}

		$this->_onDetail($id, $record);
		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {
			$this->_isValid($record, true);

			$this->_beforeEdit($record, $id);

			$this->_setLogRecord($record, $id);

			$this->model->conn->StartTrans();
			if ($this->data['row'][$this->pk]) {

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

				$is_insert = true;

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

			if ($return['success']) {

				$this->model->conn->trans_commit();

				$this->_afterEditSucceed($id);

				SetFlash('suc_msg', $return['success']);
				redirect("$this->page_ctrl/detail/$id");
			} else {

				$this->model->conn->trans_rollback();

				$this->data['row'] = array_merge($this->data['row'], $record);
				$this->data['row'] = array_merge($this->data['row'], $this->post);

				$this->_afterEditFailed($id);

				$this->data['err_msg'] = "Data gagal disimpan";
			}
		}

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}


	public function Detail($id = null)
	{

		$this->data['row'] = $this->model->GetByPk($id);

		$this->_beforeDetail($id);

		$this->data['rowheader1'] = $this->data['row'];

		$this->_onDetail($id);

		$this->isLock();
		if (!$this->data['row'])
			$this->NoData();

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	protected function _beforeDetail($id_risiko = null)
	{
		$id = $this->data['row']['id_scorecard'];
		#mengambil dari model karena sudah difilter sesuai akses
		$this->load->model("Risk_scorecardModel", 'riskscorecard');
		$this->data['rowheader']  = $this->riskscorecard->GetByPk($id);
		if (!$this->data['rowheader'])
			$this->NoData();

		$owner = $this->data['rowheader']['owner'];

		if ($owner) {
			// $this->data['ownerarr'][$owner] = $this->conn->GetOne("select concat(nama,' (',coalesce(id_unit,''),')') from mt_sdm_jabatan where id_jabatan = " . $this->conn->escape($owner));
			$this->data['ownerarr'][$owner] = $this->conn->GetOne("select concat(nama,' (',coalesce(id_unit,''),')') from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($owner));

			$this->load->model("Risk_kegiatanModel", 'kegiatan');

			if ($this->post['id_sasaran'])
				$id_sasaran = $this->post['id_sasaran'];
			elseif ($id_risiko)
				$id_sasaran = $this->conn->GetOne("select id_sasaran from risk_risiko where deleted_date is null and id_risiko = " . $this->conn->escape($id_risiko));

			$this->data['mtkegiatanarr'] = $this->kegiatan->GetCombo($id_sasaran);

			$this->load->model("Risk_sasaranModel", "msasaran");

			$this->data['sasaranarr'] = $this->msasaran->GetCombo($owner);

			$this->data['sasaranarr'][$id_sasaran] = $this->msasaran->GetNama($id_sasaran);
		}

		$this->data['add_param'] .= $id_risiko;
	}

	protected function _beforeUpdate($record = array(), $id = null)
	{

		$row = $this->model->GetByPk($id);

		$this->riskchangelog($record, $row);

		return true;
	}

	protected function _beforeInsert($record = array())
	{
		$this->riskchangelog($record);
		return true;
	}

	protected function _beforeEdit(&$record = array(), $id)
	{
		$this->_validAccessTask($this->data['rowheader'], $this->data['rowheader1'], $this->data['edited']);

		return true;
	}

	protected function _afterDetail($id)
	{
		$this->data['editedheader1'] = $this->data['edited'];


		// $this->conn->debug = 1;
		$row = $this->conn->GetRow("select id_kemungkinan as appetite_kemungkinan, id_dampak as appetite_dampak from mt_risk_taksonomi_appetite where deleted_date is null and id_taksonomi_area = " . $this->conn->escape($this->data['rowheader1']['id_taksonomi_area']) . " and tahun = " . $this->conn->escape(date('Y', strtotime($this->data['rowheader1']['tgl_risiko']))));

		// dpr($row, 1);
		if ($row) {
			$this->data['row']['appetite_kemungkinan'] = $row['appetite_kemungkinan'];
			$this->data['row']['appetite_dampak'] = $row['appetite_dampak'];
		}

		$this->data['rowheader1'] = $this->data['row'];

		if ($id) {
			$this->data['mode'] = 'edit_detail';
		}
	}

	protected function _onDetail($id = null, &$record = array())
	{
		$this->load->model("KpiModel", 'kpi');
		
		$id_unit = $this->data['rowheader']['id_unit'];
		$id_kpi = $this->data['row']['id_kpi'];
		list($tahun) = explode("-", $this->data['row']['tgl_risiko']);
		if (!$tahun)
			$tahun = date("Y");

		$this->data['kpiarr'] = $this->kpi->GetCombo($id_unit, $tahun, $id_kpi);
		$this->_accessTask($this->data['rowheader'], $this->data['row'], $this->data['edited']);
	}
}
