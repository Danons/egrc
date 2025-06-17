<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Risk_monitoring extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/risk_monitoringlist";
		$this->viewdetail = "panelbackend/risk_monitoringdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_scorecard";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Evaluasi Risiko';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Monitoring Pengendalian/Mitigasi Risiko';
			$this->data['edited'] = true;
			unset($this->access_role['lst']);
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Monitoring Pengendalian/Mitigasi Risiko';
			$this->data['edited'] = false;
			unset($this->access_role['lst']);
		} else {
			$this->data['page_title'] = 'Monitoring Pengendalian/Mitigasi Risiko';
		}

		$this->load->model("Risk_risikoModel", "model");

		$this->load->model("Mt_risk_kriteria_dampakModel", 'kriteria');
		$this->data['kriteriaarr'] = $this->kriteria->GetCombo();


		$this->SetAccess('panelbackend/risk_scorecard');

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'select2','tinymce'
		);

		$this->data['id_periode_tw_kri'] = 2;
		$this->data['tahun_kri'] = $this->data['thn'] = date("Y");
	}

	protected function Record($id = null)
	{

		$record =  array(
			'residual_dampak_evaluasi' => $this->post['residual_dampak_evaluasi'],
			'residual_kemungkinan_evaluasi' => $this->post['residual_kemungkinan_evaluasi'],
			'current_risk_dampak' => $this->post['residual_dampak_evaluasi'],
			'current_risk_kemungkinan' => $this->post['residual_kemungkinan_evaluasi'],
			'dampak_kuantitatif_residual' => Rupiah2Number($this->post['dampak_kuantitatif_residual']),
			// 'hasil_mitigasi_terhadap_sasaran' => $this->post['hasil_mitigasi_terhadap_sasaran'],


			// 'ket_monitoring_rmtik' => $this->post['ket_monitoring_rmtik'],
			// 'ket_monitoring_p2k3' => $this->post['ket_monitoring_p2k3'],
			// 'ket_monitoring_fkap' => $this->post['ket_monitoring_fkap'],
			// 'is_monitoring_rmtik' => (int)$this->post['is_monitoring_rmtik'],
			// 'is_monitoring_p2k3' => (int)$this->post['is_monitoring_p2k3'],
			// 'is_monitoring_fkap' => (int)$this->post['is_monitoring_fkap'],
		);

		if ($this->Access("pengajuan", "panelbackend/risk_scorecard"))
			$record['hasil_mitigasi_terhadap_sasaran'] = $this->post['hasil_mitigasi_terhadap_sasaran'];

		if ($this->Access("rmtik", "panelbackend/risk_monitoring")) {
			$record['ket_monitoring_rmtik'] = $this->post['ket_monitoring_rmtik'];
			$record['is_monitoring_rmtik'] = (int)$this->post['is_monitoring_rmtik'];
		}

		if ($this->Access("p2k3", "panelbackend/risk_monitoring")) {
			$record['ket_monitoring_p2k3'] = $this->post['ket_monitoring_p2k3'];
			$record['is_monitoring_p2k3'] = (int)$this->post['is_monitoring_p2k3'];
		}

		if ($this->Access("fkap", "panelbackend/risk_monitoring")) {
			$record['ket_monitoring_fkap'] = $this->post['ket_monitoring_fkap'];
			$record['is_monitoring_fkap'] = (int)$this->post['is_monitoring_fkap'];
		}

		if ($record['hasil_mitigasi_terhadap_sasaran'])
			$record['is_evaluasi_mitigasi'] = 1;

		if ($this->post['status_risiko'] !== "" && $this->post['status_risiko'] !== null) {
			$record = array();
			$record['status_risiko'] = $this->post['status_risiko'];
			$record['status_keterangan'] = $this->post['status_keterangan'];
		}

		return $record;
	}

	protected function Rules()
	{
		$return = array(
			"residual_dampak_evaluasi" => array(
				'field' => 'residual_dampak_evaluasi',
				'label' => 'Tingkat Dampak',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtdampakrisikoarr'])) . "]|required",
			),
			"residual_kemungkinan_evaluasi" => array(
				'field' => 'residual_kemungkinan_evaluasi',
				'label' => 'Tingkat Kemungkinan',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtkemungkinanrisikoarr'])) . "]|required",
			),
			"hasil_mitigasi_terhadap_sasaran" => array(
				'field' => 'hasil_mitigasi_terhadap_sasaran',
				'label' => 'Hasil Mitigasi Terhadap Sasaran',
				'rules' => "max_length[4000]|required",
			),
		);



		if (!$this->Access("pengajuan", "panelbackend/risk_scorecard"))
			unset($return['hasil_mitigasi_terhadap_sasaran']);

		return $return;
	}

	public function Index($id_scorecard = null, $id = null)
	{
		redirect("panelbackend/risk_monitoring/detail/$id_scorecard/$id");
	}

	public function Add($id_scorecard = null)
	{
		$this->Error403();
	}

	public function Edit($id_scorecard = null, $id = null)
	{

		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->_beforeDetail($id_scorecard, $id);
		$this->data['row'] = $this->model->GetByPk($id);

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
			$record['id_scorecard'] = $id_scorecard;

			$this->_isValid($record, true);

			$this->_beforeEdit($record, $id);

			$this->_setLogRecord($record, $id);

			if (strlen($this->post['status_risiko']) != 0) {
				if ($this->post['status_risiko'] == '0') {
					$record['tgl_close'] = date('Y-m-d');
					if ($this->post['tgl_close']) {
						$record['tgl_close'] = $this->post['tgl_close'];
						$record['status_risiko'] = '0';
						unset($record['tgl_risiko']);
					} else {
						SetFlash('err_msg', "Tgl. Close tidak boleh kosong");
						redirect(current_url());
					}
				} elseif ($this->post['status_risiko'] == '2') {
					$record['tgl_close'] = $this->post['tgl_risiko'];
					$record['status_risiko'] = '2';
					unset($record['tgl_risiko']);
					if (!$this->post['tgl_risiko']) {
						SetFlash('err_msg', "Tgl. Risiko tidak boleh kosong");
						redirect(current_url());
					}
				}
			}

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
				if ($this->data['id_risiko_new']) {
					$id = $this->data['id_risiko_new'];
					$this->ctrl = 'risk_risiko';
					$id_scorecard_new = $this->data['id_scorecard_new'];
					redirect("panelbackend/risk_risiko/detail/$id_scorecard_new/$id");
				} else {
					// $this->backtodraft($id_scorecard);
					redirect("$this->page_ctrl/detail/$id_scorecard/$id");
				}
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


	public function Detail($id_scorecard = null, $id = null)
	{
		$this->_beforeDetail($id_scorecard, $id);

		$this->data['row'] = $this->model->GetByPk($id);

		$this->data['rowheader1'] = $this->data['row'];

		$this->_onDetail($id);

		$this->isLock();

		if (!$this->data['row'])
			$this->NoData();

		if (!$this->data['row']['progress_capaian_kinerja'] && $this->access_role['edit']) {
			redirect("panelbackend/risk_monitoring/edit/$id_scorecard/$id");
			die();
		}

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	protected function _beforeDetail($id = null, $id_risiko = null)
	{

		if ($this->post['act'] == 'status_risiko') {
			if (!$this->Access('close', 'panelbackend/risk_risiko'))
				$this->Error403();

			$this->post['act'] = 'save';
			$this->Edit($id, $id_risiko);
			return;
		}

		#mengambil dari model karena sudah difilter sesuai akses
		$this->load->model("Risk_scorecardModel", 'riskscorecard');
		$this->data['rowheader']  = $this->riskscorecard->GetByPk($id);
		if (!$this->data['rowheader'])
			$this->NoData();

		$owner = $this->data['rowheader']['owner'];

		if ($owner) {
			$this->data['ownerarr'][$owner] = $this->conn->GetOne("select concat(nama,' (',ifnull(id_unit,''),')') from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($owner));

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

		$this->data['add_param'] .= $id . "/" . $id_risiko;
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

	protected function _afterUpdate($id)
	{
		$ret = $this->_afterInsert($id);

		return $ret;
	}

	protected function _afterInsert($id)
	{
		$ret = true;
		if ($this->Access('pengajuan', "panelbackend/risk_scorecard")) {
			$cek = (int)!$this->conn->GetOne("select 1 from risk_risiko 
			where deleted_date is null and id_scorecard = " . $this->conn->escape($this->data['row']['id_scorecard']) . " 
			and tgl_close is null
			and (is_evaluasi_mitigasi = 0 or is_evaluasi_mitigasi is null)");

			$this->conn->goUpdate(
				"risk_scorecard",
				['is_evaluasi_mitigasi' => $cek],
				"id_scorecard = " . $this->conn->escape($this->data['row']['id_scorecard'])
			);
		}

		return $ret;
	}

	protected function _afterDetail($id)
	{
		$this->data['editedheader1'] = $this->data['edited'];

		$this->data['rowheader1'] = $this->data['row'];

		if ($id) {
			$this->data['mode'] = 'edit_detail';
		}
	}

	protected function _onDetail($id = null, &$record = array())
	{
		$this->_accessTask($this->data['rowheader'], $this->data['row'], $this->data['edited']);
	}
}
