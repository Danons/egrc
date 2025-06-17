<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Risk_monitoring_tahunan extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/risk_monitoring_tahunanlist";
		$this->viewdetail = "panelbackend/risk_monitoring_tahunandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_scorecard";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Evaluasi Risiko';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Evaluasi Risiko';
			$this->data['edited'] = true;
			unset($this->access_role['lst']);
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Evaluasi Risiko';
			$this->data['edited'] = false;
			unset($this->access_role['lst']);
		} else {
			$this->data['page_title'] = 'Evaluasi Risiko';
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
			'progress_capaian_kinerja' => $this->post['progress_capaian_kinerja'],
			'progress_capaian_sasaran' => $this->post['progress_capaian_sasaran'],
			'penyesuaian_tindakan_mitigasi' => $this->post['penyesuaian_tindakan_mitigasi'],
			'hambatan_kendala' => $this->post['hambatan_kendala'],
			// 'residual_dampak_evaluasi' => $this->post['residual_dampak_evaluasi'],
			// 'residual_kemungkinan_evaluasi' => $this->post['residual_kemungkinan_evaluasi'],
			// 'current_risk_dampak' => $this->post['residual_dampak_evaluasi'],
			// 'current_risk_kemungkinan' => $this->post['residual_kemungkinan_evaluasi'],
			// 'dampak_kuantitatif_residual' => Rupiah2Number($this->post['dampak_kuantitatif_residual']),
		);

		if ($record['progress_capaian_kinerja'])
			$record['is_evaluasi_risiko'] = 1;

		if ($this->post['status_risiko'] !== "" && $this->post['status_risiko'] !== null) {
			$record['status_risiko'] = $this->post['status_risiko'];
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
			"progress_capaian_kinerja" => array(
				'field' => 'progress_capaian_kinerja',
				'label' => 'Progress Capaian Kinerja',
				'rules' => "required|max_length[200]",
			),
			/*"progress_capaian_sasaran"=>array(
				'field'=>'progress_capaian_sasaran',
				'label'=>'Progress Capaian Sasaran',
				'rules'=>"required|max_length[200]",
			),*/
			"penyesuaian_tindakan_mitigasi" => array(
				'field' => 'penyesuaian_tindakan_mitigasi',
				'label' => 'Penyesuaian Tindakan Mitigasi',
				'rules' => "max_length[4000]|required",
			),
			// "hambatan_kendala" => array(
			// 	'field' => 'hambatan_kendala',
			// 	'label' => 'Hambatan Kendala',
			// 	'rules' => "max_length[4000]|required",
			// ),
		);

		return $return;
	}

	public function Index($id_scorecard = null, $id = null)
	{
		redirect("panelbackend/risk_monitoring_tahunan/detail/$id_scorecard/$id");
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
			redirect("panelbackend/risk_monitoring_tahunan/edit/$id_scorecard/$id");
			die();
		}

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	protected function _beforeDetail($id = null, $id_risiko = null)
	{

		#mengambil dari model karena sudah difilter sesuai akses
		$this->load->model("Risk_scorecardModel", 'riskscorecard');
		$this->data['rowheader']  = $this->riskscorecard->GetByPk($id);
		if (!$this->data['rowheader'])
			$this->NoData();

		$owner = $this->data['rowheader']['owner'];

		if ($owner) {
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
		$rows = $this->conn->GetArray("select * 
		from risk_kri
		where deleted_date is null and id_risiko = " . $this->conn->escape($id));

		$this->data['row']['kri'] = array();
		foreach ($rows as $r) {
			if ($this->data['tahun_kri'] == date("Y") - 1 && $this->data['id_periode_tw_kri'] == '4') {
				$rs = $this->conn->GetArray("select * from risk_kri_hasil 
				where deleted_date is null and ((id_periode_tw = " . $this->conn->escape($this->data['id_periode_tw_kri']) . " 
				and tahun = " . $this->conn->escape($this->data['tahun_kri']) . ") 
				or (tahun = " . $this->conn->escape(date("Y")) . " and id_periode_tw <> " . $this->conn->escape($this->data['id_periode_tw_kri']) . "))
				and id_kri = " . $this->conn->escape($r['id_kri']));
				foreach ($rs as $r1) {
					$r['hasil'][$r1['id_periode_tw']] = $r1;
				}
			} else {
				$rs = $this->conn->GetArray("select * from risk_kri_hasil where deleted_date is null and tahun = " . $this->conn->escape($this->data['tahun_kri']) . " and id_kri = " . $this->conn->escape($r['id_kri']));

				foreach ($rs as $r1) {
					$r['hasil'][$r1['id_periode_tw']] = $r1;
				}
			}

			$this->data['row']['kri'][] = $r;
		}
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
		$ret = $this->_delSertKri($id);

		if ($this->Access('evaluasimitigasi', "panelbackend/risk_scorecard")) {
			$cek = (int)!$this->conn->GetOne("select 1 from risk_risiko 
			where deleted_date is null and id_scorecard = " . $this->conn->escape($this->data['row']['id_scorecard']) . " 
			and tgl_close is null 
			and (is_evaluasi_risiko = 0 or is_evaluasi_risiko is null)");

			$this->conn->GoUpdate(
				"risk_scorecard",
				['is_evaluasi_risiko' => $cek],
				"id_scorecard = " . $this->conn->escape($this->data['row']['id_scorecard'])
			);
		}

		return $ret;
	}

	protected function _delSertKri($id)
	{
		$ret = true;

		if ($this->post['kri']) {
			$id_periode_tw_current = $this->data['id_periode_tw_kri'];

			foreach ($this->post['kri'] as $r) {
				if (!$ret)
					break;

				$id_kri = $r['id_kri'];

				foreach ($r['hasil'] as $id_periode_tw => $nilai) {
					if (!$this->access_role['edit_kri_all'] && $id_periode_tw <> $id_periode_tw_current)
						continue;

					if ($this->data['tahun_kri'] != date("Y") && $id_periode_tw == 4)
						$thn = $this->data['tahun_kri'];
					else
						$thn = date("Y");

					$record1 = array();
					$record1['nilai'] = $nilai['nilai'];
					$record1['id_kri'] = $id_kri;
					$record1['id_periode_tw'] = $id_periode_tw;
					$record1['tahun'] = $thn;
					$id_kri_hasil = $this->conn->GetOne("select 
				id_kri_hasil 
				from risk_kri_hasil 
				where deleted_date is null and id_kri = " . $this->conn->escape($id_kri) . " 
				and id_periode_tw = " . $this->conn->escape($id_periode_tw) . " 
				and tahun = " . $this->conn->escape($thn));

					if ($id_kri_hasil) {
						$ret = $this->conn->goUpdate("risk_kri_hasil", $record1, "id_kri_hasil = " . $this->conn->escape($id_kri_hasil));
					} else {
						$ret = $this->conn->goInsert("risk_kri_hasil", $record1);
					}

					if ($ret) {
						$record = array();
						$record['keterangan'] = $r['keterangan'];
						$ret = $this->conn->goUpdate("risk_kri", $record, "id_kri = " . $this->conn->escape($id_kri));
					}
				}
			}
		}

		return $ret;
	}
}
