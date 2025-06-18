<?php

use PhpOffice\Common\Drawing;

defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Risk_monitoring_bulanan extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/risk_monitoring_bulananlist";
		$this->viewdetail = "panelbackend/risk_monitoring_bulanandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_scorecard";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Evaluasi Risiko';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Monitoring Tindak Lanjut Penanganan';
			$this->data['edited'] = true;
			unset($this->access_role['lst']);
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Monitoring Tindak Lanjut Penanganan';
			$this->data['edited'] = false;
			unset($this->access_role['lst']);
		} else {
			$this->data['page_title'] = 'Monitoring Tindak Lanjut Penanganan';
		}

		$this->load->model("Risk_risikoModel", "model");

		$this->load->model("Mt_risk_kriteria_dampakModel", 'kriteria');
		$this->data['kriteriaarr'] = $this->kriteria->GetCombo();

		$this->load->model("Risk_mitigasi_filesModel", "modelfile");
		$this->data['configfile'] = $this->config->item('file_upload_config');

		$this->SetAccess('panelbackend/risk_scorecard');

		$this->load->model("Mt_risk_efektifitas_pengukuranModel", "pengukuran");
		$this->data['mtpengukuranarr'] = $this->pengukuran->GetCombo();

		$this->load->model("Risk_scorecardModel", "unit");
		$this->data['unitarr'] = $this->unit->GetCombo2();

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'select2', 'upload'
		);

		$this->periodeDefault();
		$this->data['page_title'] .= " Bulan " . ListBulan()[$this->data['bulan']] . " Tahun " . $this->data['tahun'];

		$this->data['acces_status'] = $this->Access('evaluasirisiko', 'panelbackend/risk_scorecard');
	}

	protected function Record($id = null)
	{
		//simpan resiko residual saat ini




		// simpan risiko residual setelah evaluasi
		$record =  array(
			// 'control_kemungkinan_penurunan' => $this->data['row']['control_kemungkinan_penurunan'],
			// 'control_dampak_penurunan' => $this->data['row']['control_dampak_penurunan'],
			'residual_dampak_evaluasi' => $this->post['residual_dampak_evaluasi'],
			'peristiwa_kerugian' => $this->post['peristiwa_kerugian'],
			'residual_kemungkinan_evaluasi' => $this->post['residual_kemungkinan_evaluasi'],
			// 'dampak_kuantitatif_residual' => Rupiah2Number($this->post['dampak_kuantitatif_residual']),
			'hasil_mitigasi_terhadap_sasaran' => $this->post['hasil_mitigasi_terhadap_sasaran'],
			'penyesuaian_tindakan_mitigasi' => $this->post['penyesuaian_tindakan_mitigasi'],
		);

		if ($record['status_risiko'])
			$record['is_evaluasi_mitigasi'] = 1;

		if ($this->post['status_risiko'] !== "" && $this->post['status_risiko'] !== null) {
			$record['status_risiko'] = $this->post['status_risiko'];
		}

		$this->post['dampak_kuantitatif_residual'] = $record['dampak_kuantitatif_residual'];

		// dpr($record,1);

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
			"peristiwa_kerugian" => array(
				'field' => 'peristiwa_kerugian',
				'label' => 'Peristiwa Kerugian',
				'rules' => "",
			),
			// "hasil_mitigasi_terhadap_sasaran" => array(
			// 	'field' => 'hasil_mitigasi_terhadap_sasaran',
			// 	'label' => 'Hasil Mitigasi Terhadap Sasaran',
			// 	'rules' => "max_length[4000]|",
			// ),
		);

		return $return;
	}

	public function Index($id_scorecard = null, $id = null)
	{
		// redirect("panelbackend/risk_monitoring_bulanan/detail/$id_scorecard/$id");
		redirect("panelbackend/risk_monitoring_bulanan/detail$id");
	}

	public function Add($id_scorecard = null)
	{
		$this->Error403();
	}

	// public function Edit($id_scorecard = null, $id = null)
	public function Edit($id = null)
	{

		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->data['row'] = $this->model->GetByPk($id);
		$id_scorecard = $this->data['row']['id_scorecard'];
		$this->_beforeDetail($id_scorecard, $id);

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
			$this->data['row'] = array_merge($this->data['row'], $this->post);
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

				$id_risiko_baru = $this->conn->GetRow("select id_risiko, id_scorecard from risk_risiko where deleted_date is null and id_risiko_sebelum = " . $this->conn->escape($id));
				if ($id_risiko_baru)
					redirect(base_url("panelbackend/risk_risiko/edit/$id_risiko_baru[id_scorecard]/$id_risiko_baru[id_risiko]"));
				else
					redirect("$this->page_ctrl/detail/$id");
				// if ($this->data['id_risiko_new']) {
				// 	$id = $this->data['id_risiko_new'];
				// 	$this->ctrl = 'risk_risiko';
				// 	$id_scorecard_new = $this->data['id_scorecard_new'];
				// 	redirect("panelbackend/risk_risiko/detail/$id_scorecard_new/$id");
				// } else {
				// $this->backtodraft($id_scorecard);
				// }
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
		// $this->_beforeDetail($id_scorecard, $id);

		$this->data['row'] = $this->model->GetByPk($id);

		$id_scorecard = $this->data['row']['id_scorecard'];
		$this->_beforeDetail($id_scorecard, $id);

		$this->data['rowheader1'] = $this->data['row'];

		$this->_onDetail($id);

		$this->isLock();

		if (!$this->data['row'])
			$this->NoData();

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	protected function _beforeDetail($id = null, $id_risiko = null)
	{

		// if ($this->post['act'] == 'status_risiko') {
		// 	if (!$this->Access('close', 'panelbackend/risk_risiko'))
		// 		$this->Error403();

		// 	$this->post['act'] = 'save';
		// 	$this->Edit($id, $id_risiko);
		// 	return;
		// }

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

			// $this->data['sasaranarr'] = $this->msasaran->GetCombo($owner);
			$this->data['sasaranarr'] = [];
			$this->data['sasaranarr'][$id_sasaran] = $this->msasaran->GetNama($id_sasaran);
		}

		// $this->data['add_param'] .= $id;
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

		// if ($this->post['status_risiko'] == '2' && $this->post['status_risiko'] == 2) {
		// 	return $this->RisikoBerlanjut($id);
		// }
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
		// if ($this->Access('pengajuan', "panelbackend/risk_scorecard")) {
		// 	$cek = $this->conn->GetOne("select 1 from risk_risiko 
		// 	where id_scorecard = " . $this->conn->escape($this->data['row']['id_scorecard']) . " 
		// 	and status_risiko = '0'");

		// 	$this->conn->goUpdate(
		// 		"risk_scorecard",
		// 		['is_evaluasi_mitigasi' => $cek],
		// 		"id_scorecard = " . $this->conn->escape($this->data['row']['id_scorecard'])
		// 	);
		// }
		if ($this->post['control']) {
			// $this->conn->debug = 1;
			// dpr($this->post['control'],1);
			$ret = $this->conn->Execute("delete from risk_control_risiko where id_risiko = " . $this->conn->escape($id));
			if ($ret) {
				unset($this->data['row']['control']);
			}

			foreach ($this->post['control'] as $g) {
				if (!$ret)
					break;
				$data_control = null;
				if (is_numeric($g['id_control']) || $g['id_control_bak']) {
					if ($g['id_control_bak']) {
						// $g['nama'] = $g['id_control'];
						$g['id_control'] = $g['id_control_bak'];
					}
					$data_control = $this->conn->GetRow("select * from risk_control where deleted_date is null and id_control = " . $this->conn->escape($g['id_control']));

					if ($data_control) {
						$g['id_control'] = $data_control['id_control'];
						$record2['id_pengukuran'] = $g['id_pengukuran'];
						$record2['menurunkan_dampak_kemungkinan'] = $g['menurunkan_dampak_kemungkinan'];
						$record2['status_progress'] = $g['status_progress'];
						// if ($g['id_control_bak'])
						// 	$record2['nama'] = $g['nama'];
						$this->conn->goUpdate("risk_control", $record2, "id_control = " . $this->conn->escape($data_control['id_control']));
						unset($record2);
					}
				} else if (!is_numeric($g['id_control']) && !$g['id_control_bak']) {
					// else (!$g['id_control']) {
					$record['nama'] = $g['nama'];
					$record['id_pengukuran'] = $g['id_pengukuran'];
					$record['menurunkan_dampak_kemungkinan'] = $g['menurunkan_dampak_kemungkinan'];
					$record['status_progress'] = $g['status_progress'];
					if ($record['nama'])
						$ret = $this->conn->goInsert("risk_control", $record);

					$g['id_control'] = $this->conn->GetOne("select max(id_control) from risk_control where deleted_date is null");
				} else {
					// $record['nama'] = $data_control['nama'];
					// $record2['id_pengukuran'] = $data_control['id_pengukuran'];
					// $this->conn->goUpdate("risk_control", $record2, "id_control = " . $this->conn->escape($id_control));
				}

				if ($g['id_control']) {
					$data['id_risiko'] = $id;
					$data['id_control'] = $g['id_control'];
					$ret = $this->conn->goInsert('risk_control_risiko', $data);
					unset($id_control);
					unset($data);
				}
				$this->data['row']['control'][$g['id_control']]['id_control'] = $g['id_control'];
			}
		} else {
			$ret = $this->conn->Execute("update risk_control_risiko where deleted_date is null and id_risiko = " . $this->conn->escape($id));
		}
		if ($ret)
			$ret = $this->_delSertMitigasi($id);

		if ($ret) {
			$tahun = $this->data['tahun'];
			$bulan = $this->data['bulan'];
			$id_periode_tw = $this->data['id_periode_tw'];

			$id_risiko_current = $this->conn->GetOne("select id_risiko_current 
		from risk_risiko_current 
		where deleted_date is null and tahun = " . $this->conn->escape($tahun) . " 
		and bulan = " . $this->conn->escape($bulan) . " 
		and id_risiko = " . $this->conn->escape($id));

			$record =  array(
				'id_dampak' => $this->post['residual_dampak_evaluasi'],
				'id_kemungkinan' => $this->post['residual_kemungkinan_evaluasi'],
				'hasil_mitigasi_terhadap_sasaran' => $this->post['hasil_mitigasi_terhadap_sasaran'],
				'penyesuaian_tindakan_mitigasi' => $this->post['penyesuaian_tindakan_mitigasi'],
			);

			$record['tahun'] = $tahun;
			$record['bulan'] = $bulan;
			$record['id_risiko'] = $id;
			$record['id_periode_tw'] = $id_periode_tw;
			if ($id_risiko_current) {
				$ret = $this->conn->goUpdate("risk_risiko_current", $record, "id_risiko_current = " . $this->conn->escape($id_risiko_current));
			} else {
				$ret = $this->conn->goInsert("risk_risiko_current", $record);
			}
		}

		if ($this->post['status_risiko'] == '2' && $this->post['status_risiko'] == 2) {
			return $this->RisikoBerlanjut($id);
		}

		return $ret;
	}

	protected function _delSertMitigasi($id)
	{

		$tahun = $this->data['tahun'];
		$bulan = $this->data['bulan'];
		$id_periode_tw = $this->data['id_periode_tw'];
		$ret = true;

		if ($this->post['act'] == 'save') {
			if ($this->post['mitigasi'])
				foreach ($this->post['mitigasi'] as $r) {
					if (!$ret)
						break;

					$record = array();
					$record['id_risiko'] = $id;
					$record['status_progress'] = $r['status_progress'];
					$record['start_date_realisasi'] = $r['start_date_realisasi'];
					$record['id_pengukuran'] = $r['id_pengukuran'];
					$record['end_date_realisasi'] = $r['end_date_realisasi'];

					$ret = $this->conn->goUpdate("risk_mitigasi", $record, "id_mitigasi = " . $this->conn->escape($r['id_mitigasi']));

					if ($ret) {
						$id_mitigasi_progress = $this->conn->GetOne("select id_mitigasi_progress 
						from risk_mitigasi_progress 
						where deleted_date is null and tahun = " . $this->conn->escape($tahun) . " 
						and bulan = " . $this->conn->escape($bulan) . " 
						and id_mitigasi = " . $this->conn->escape($r['id_mitigasi']));

						$record['tahun'] = $tahun;
						$record['bulan'] = $bulan;
						$record['id_periode_tw'] = $id_periode_tw;
						$record['id_mitigasi'] = $r['id_mitigasi'];
						$record['id_pengukuran'] = $r['id_pengukuran'];
						$record['id_risiko'] = $id;
						if ($id_mitigasi_progress) {
							$ret = $this->conn->goUpdate("risk_mitigasi_progress", $record, "id_mitigasi_progress = " . $this->conn->escape($id_mitigasi_progress));
						} else {
							$ret = $this->conn->goInsert("risk_mitigasi_progress", $record);
						}
					}
				}
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
		$tahun = $this->data['tahun'];
		$bulan = $this->data['bulan'];
		$this->_accessTask($this->data['rowheader'], $this->data['row'], $this->data['edited']);
		if (!$this->data['row']['control']) {
			$rows = $this->conn->GetArray("select * 
				from risk_control a
				where deleted_date is null and exists(select 1 from risk_control_risiko b where a.id_control = b.id_control and id_risiko = " . $this->conn->escape($id) . ")");

			$this->data['row']['control'] = array();
			foreach ($rows as $r) {
				$r['id'] = $r['id_control'];
				$this->data['row']['control'][$r['id_control']] = $r;
			}
		}


		if (!$this->data['row']['mitigasi']) {
			$rows = $this->conn->GetArray("select * 
				from risk_mitigasi a
				where a.deleted_date is null and exists(select 1 from risk_mitigasi_risiko b where a.id_mitigasi = b.id_mitigasi and id_risiko = " . $this->conn->escape($id) . ")");

			$this->data['row']['mitigasi'] = array();
			foreach ($rows as $r) {

				$id_mitigasi_progress = $this->conn->GetOne("select id_mitigasi_progress 
				from risk_mitigasi_progress 
				where deleted_date is null and tahun = " . $this->conn->escape($tahun) . " 
				and id_risiko = " . $this->conn->escape($id) . "
				and bulan = " . $this->conn->escape($bulan) . " 
				and id_mitigasi = " . $this->conn->escape($r['id_mitigasi']));

				$rows1 = $this->conn->GetArray("select *
				from {$this->modelfile->table}
				where deleted_date is null and id_mitigasi = " . $this->conn->escape($r['id_mitigasi']) . " 
				and id_mitigasi_progress = " . $this->conn->escape($id_mitigasi_progress));

				foreach ($rows1 as $r1) {
					$r['files']['id'][] = $r1[$this->modelfile->pk];
					$r['files']['name'][] = $r1['client_name'];
				}

				$this->data['row']['mitigasi'][] = $r;
			}
		}

		$totalefektifitaskemungkinan = 0;
		$totalefektifitasdampak = 0;
		foreach ($this->data['row']['control'] as $id_control => $g) {
			switch ($g['menurunkan_dampak_kemungkinan']) {
				case 'k':
					$totalefektifitaskemungkinan += (float)$g['id_pengukuran'];
					break;
				case 'd':
					$totalefektifitasdampak += (float)$g['id_pengukuran'];
					break;
				case 'kd':
					$totalefektifitaskemungkinan += floor((float)$g['id_pengukuran'] / 2);
					$totalefektifitasdampak += floor((float)$g['id_pengukuran'] / 2);
					break;
			}
		}

		$this->data['row']['control_kemungkinan_penurunan'] = $this->data['row']['inheren_kemungkinan'] - $totalefektifitaskemungkinan;
		$this->data['row']['control_dampak_penurunan'] = $this->data['row']['inheren_dampak'] - $totalefektifitasdampak;

		$record['control_kemungkinan_penurunan'] = $this->data['row']['control_kemungkinan_penurunan'];
		$record['control_dampak_penurunan'] = $this->data['row']['control_dampak_penurunan'];

		$totalefektifitaskemungkinan = 0;
		$totalefektifitasdampak = 0;
		foreach ($this->data['row']['mitigasi'] as $id_mitigasi => $g) {
			switch ($g['menurunkan_dampak_kemungkinan']) {
				case 'k':
					$totalefektifitaskemungkinan += (int)$g['id_pengukuran'];
					break;
				case 'd':
					$totalefektifitasdampak += (int)$g['id_pengukuran'];
					break;
				case 'kd':
					$totalefektifitaskemungkinan += floor((int)$g['id_pengukuran'] / 2);
					$totalefektifitasdampak += floor((int)$g['id_pengukuran'] / 2);
					break;
			}
		}

		// if ($this->data['row']['control_kemungkinan_penurunan'] && $totalefektifitaskemungkinan)
		$this->data['row']['residual_kemungkinan_evaluasi'] = $this->data['row']['control_kemungkinan_penurunan'] - $totalefektifitaskemungkinan;
		// if ($this->data['row']['control_dampak_penurunan'] && $totalefektifitasdampak)
		$this->data['row']['residual_dampak_evaluasi'] = $this->data['row']['control_dampak_penurunan'] - $totalefektifitasdampak;

		// dpr($this->data['row']);
		if ($this->data['row']['residual_kemungkinan_evaluasi'] < 1) {
			$this->data['err_msg'] = "Tingkat efektifitas kemungkinan melebihi batas mininal tingkat kemungkinan risiko residual setelah evaluasi";
		}
		if ($this->data['row']['residual_dampak_evaluasi'] < 1) {
			$this->data['err_msg'] = "Tingkat efektifitas dampak melebihi batas mininal tingkat dampak risiko residual setelah evaluasi";
		}
	}

	protected function _uploadFiles($jenis_file = null, $id = null)
	{
		$ret = true;
		$tahun = $this->data['tahun'];
		$bulan = $this->data['bulan'];
		$id_periode_tw = $this->data['id_periode_tw'];
		$name = $_FILES[$jenis_file]['name'];

		$this->data['configfile']['file_name'] = $jenis_file . time() . $name;

		$this->load->library('upload', $this->data['configfile']);

		if (!$this->upload->do_upload($jenis_file)) {
			$return = array('error' => "File $name gagal upload, " . strtolower(str_replace(array("<p>", "</p>"), "", $this->upload->display_errors())));
		} else {
			$upload_data = $this->upload->data();
			$jenis_file = str_replace("upload", "", $jenis_file);
			list($jenis_file, $id) = explode("_", $jenis_file);


			$id_mitigasi_progress = $this->conn->GetOne("select id_mitigasi_progress 
			from risk_mitigasi_progress 
			where deleted_date is null and tahun = " . $this->conn->escape($tahun) . " 
			and bulan = " . $this->conn->escape($bulan) . " 
			and id_mitigasi = " . $this->conn->escape($id));

			$record = array();
			$record['tahun'] = $tahun;
			$record['bulan'] = $bulan;
			$record['id_periode_tw'] = $id_periode_tw;
			$record['id_mitigasi'] = $id;
			if (!$id_mitigasi_progress) {
				$ret = $this->conn->goInsert("risk_mitigasi_progress", $record);
				$id_mitigasi_progress = $this->conn->GetOne("select id_mitigasi_progress 
				from risk_mitigasi_progress 
				where deleted_date is null and tahun = " . $this->conn->escape($tahun) . " 
				and bulan = " . $this->conn->escape($bulan) . " 
				and id_mitigasi = " . $this->conn->escape($id));
			}

			if ($ret) {
				$record = array();
				$record['client_name'] = $upload_data['client_name'];
				$record['file_name'] = $upload_data['file_name'];
				$record['file_type'] = $upload_data['file_type'];
				$record['file_size'] = $upload_data['file_size'];
				$record['jenis_file'] = $record['jenis'] = str_replace("upload", "", $jenis_file);
				$record['id_mitigasi'] = $id;
				$record['id_mitigasi_progress'] = $id_mitigasi_progress;

				$ret = $this->modelfile->Insert($record);
			}
			if ($ret['success']) {
				$return = array('file' => array("id" => $ret['data'][$this->modelfile->pk], "name" => $upload_data['client_name']));
			} else {
				unlink($upload_data['full_path']);
				$return = array('errors' => "File $name gagal upload");
			}
		}

		return $return;
	}

	private function RisikoBerlanjut($id)
	{

		$risiko = $this->conn->GetRow("select * 
			from risk_risiko 
			where deleted_date is null and id_risiko=" . $this->conn->escape($id));

		$id_scorecard = $this->post['id_scorecard_new'] ? $this->post['id_scorecard_new'] : $risiko['id_scorecard'];

		// dpr($this->post['id_scorecard_new']);
		// dpr($id_scorecard);
		// dpr($risiko,1);
		// if ($risiko['status_risiko'] == '2')
		// 	return true;

		$return['success'] = false;

		$this->load->model("Risk_risikoModel", "mrisiko");
		// $this->load->model("Risk_jabatan_berisikoModel", "mjabatanberisiko"); #
		// $this->load->model("Risk_risiko_kpiModel", "mrisikokpi"); #
		$this->load->model("Risk_risiko_currentModel", "mrisikocurrent"); #
		// $this->load->model("Risk_risiko_current_fr_dModel", "mrisikocurrentfrd"); #
		// $this->load->model("Risk_risiko_current_fr_kModel", "mrisikocurrentfrk"); #
		// $this->load->model("Risk_risiko_fr_dampakModel", "mrisikofrdampak"); #Risk_risiko_dampakModel
		// $this->load->model("Risk_risiko_fr_kemungkinanModel", "mrisikofrkemungkinan"); #
		$this->load->model("Risk_risiko_filesModel", "mrisikofiles");
		$this->load->model("Risk_controlModel", "mcontrol");
		// $this->load->model("Risk_control_efektifitasModel", "mcontrolefektifitas");
		// $this->load->model("Risk_control_efektifitas_filesModel", "mcontrolefektifitasfiles");
		$this->load->model("Risk_mitigasiModel", "mmitigasi");
		// $this->load->model("Risk_mitigasi_efektifModel", "mmitigasiefektif");
		// $this->load->model("Risk_mitigasi_efektif_mModel", "mmitigasiefektifm");
		$this->load->model("Risk_mitigasi_progressModel", "mmitigasiprogress"); #
		$this->load->model("Risk_mitigasi_filesModel", "mmitigasifiles");
		$this->load->model("Risk_kriModel", "mkri"); #
		$this->load->model("Risk_kri_hasilModel", "mkrihasil"); #

		// dpr($this->post,1);
		// if (($this->post['id_scorecard']) && is_array($this->post['id_scorecard'])) {

		$return['success'] = true;

		$control_dampak_penurunan = $risiko['residual_dampak_evaluasi'];
		$control_kemungkinan_penurunan = $risiko['residual_kemungkinan_evaluasi'];

		// foreach ($this->post['id_scorecard'] as $id_scorecard) {
		// if (!$return['success'])
		// break;

		$risiko['id_scorecard'] = $id_scorecard;

		list($tahun, $bulan, $tgl) = explode("-", $risiko['tgl_risiko']);

		$thnsekarang = date("Y");
		unset($risiko['id_risiko']);
		$this->_setLogRec($risiko);

		// $risiko['tgl_risiko'] = "01-01-" . ($thnsekarang == $tahun ? $thnsekarang + 1 : $thnsekarang);
		// $risiko['tgl_risiko'] = date('d-m-Y');
		$risiko['tgl_risiko'] = date('Y-m-d');

		// if ($this->post['tgl_risiko'])
		// 	$risiko['tgl_risiko'] = $this->post['tgl_risiko'];

		list($tgl, $bulan, $tahun1) = explode("-", $risiko['tgl_risiko']);

		$scorecard = $this->conn->GetRow("select * from risk_scorecard where deleted_date is null and id_scorecard = " . $this->conn->escape($id_scorecard));
		if ($scorecard['is_owner_in_risk']) {
			$riskowner = $risiko['riskowner'];
		} else {
			$riskowner = $scorecard['owner'];
		}


		// $karakter_sebelum_garis_miring = substr($risiko['nomor'], 0, strpos($risiko['nomor'], '/'));
		// dpr($karakter_sebelum_garis_miring,1);

		//if ($tahun == $tahun1) {
		// if ($tahun == $tahun1) {
		if ($tahun == $tahun1) {
			#risiko dilanjutkan ditahun yang sama
			$format = $this->mrisiko->getNomorRisiko($risiko['id_scorecard'], $risiko['id_sasaran_strategis'], $risiko['tgl_risiko'], $risiko['kode_aktifitas'], $risiko['id_taksonomi'], true, $riskowner);
			list($formatold, $nomorold) = explode("-", $risiko['nomor']);


			if ($format == $formatold . "-") {
				#apabila format sama maka perlu diberi antidartir
				list($no, $anti) = explode(".", $nomorold);
				$anti = (int)$anti + 1;
				$risiko['nomor'] = $formatold . "-" . $no . "." . $anti;
			} else {
				#apabila format berbeda maka perlu dibuatkan nomor urut sendiri
				$risiko['nomor'] = $risiko['nomor_asli'] = $this->mrisiko->getNomorRisiko($risiko['id_scorecard'], $risiko['id_sasaran_strategis'], $risiko['tgl_risiko'], $risiko['kode_aktifitas'], $risiko['id_taksonomi'], false, $riskowner);
			}
		} else
			$risiko['nomor'] = $risiko['nomor_asli'] = $this->mrisiko->getNomorRisiko($risiko['id_scorecard'], $risiko['id_sasaran_strategis'], $risiko['tgl_risiko'], $risiko['kode_aktifitas'], $risiko['id_taksonomi'], false, $riskowner);

		$risiko['id_status_pengajuan'] = 1;
		$risiko['control_dampak_penurunan'] = $control_dampak_penurunan;
		$risiko['control_kemungkinan_penurunan'] = $control_kemungkinan_penurunan;
		$risiko['id_risiko_sebelum'] = $id;
		$risiko['status_risiko'] = 1;
		// unset($risiko['status_risiko']);

		unset($risiko['residual_dampak_evaluasi']);
		unset($risiko['residual_kemungkinan_evaluasi']);
		unset($risiko['progress_capaian_kinerja']);
		unset($risiko['progress_capaian_sasaran']);
		unset($risiko['hambatan_kendala']);
		unset($risiko['tgl_close']);
		unset($risiko['skor_current_kemungkinan']);
		unset($risiko['skor_current_dampak']);
		unset($risiko['penyesuaian_tindakan_mitigasi']);

		// $this->conn->debug=1;
		// dpr($risiko,1);
		$return = $this->mrisiko->Insert($risiko);
		// dpr($return,1);

		$id_risiko = $return['data']['id_risiko'];
		$this->riskchangelog($return['data'], null, "Berlanjut ", "panelbackend/risk_risiko");

		$owner = $this->conn->GetOne("select owner from risk_scorecard where deleted_date is null and id_scorecard = " . $this->conn->escape($id_scorecard));

		$this->data['id_risiko_new'] = $id_risiko;
		$this->data['id_scorecard_new'] = $risiko['id_scorecard'];


		#jabatan berisiko
		// if ($return['success'] && $id_risiko) {
		// 	$jabatanberisikos = $this->conn->GetArray("select * from risk_jabatan_berisiko
		// 		where id_risiko = " . $this->conn->escape($id));

		// 	foreach ($jabatanberisikos as $jabatanberisiko) {
		// 		if (!$return['success'])
		// 			break;

		// 		$this->_setLogRec($jabatanberisiko);
		// 		$jabatanberisiko['id_risiko'] = $id_risiko;

		// 		$return = $this->mjabatanberisiko->Insert($jabatanberisiko);
		// 	}
		// }

		#risiko kpis
		if ($return['success'] && $id_risiko) {
			$risikokpis = $this->conn->GetArray("select * from risk_risiko_kpi 
						where deleted_date is null and id_risiko = " . $this->conn->escape($id));

			// dpr($risikokpis,1);
			if ($risikokpis)
				foreach ($risikokpis as $risikokpi) {
					if (!$return['success'])
						break;

					unset($risikokpi['id_risiko']);
					$this->_setLogRec($risikokpi);
					$risikokpi['id_risiko'] = $id_risiko;

					$return = $this->mrisikokpi->Insert($risikokpi);
				}
		}

		#risiko dampak
		if ($return['success'] && $id_risiko) {
			$risikofrdampaks = $this->conn->GetArray("select * from risk_risiko_dampak 
						where deleted_date is null and id_risiko = " . $this->conn->escape($id));

			foreach ($risikofrdampaks as $risikofrdampak) {
				if (!$return['success'])
					break;

				$this->_setLogRec($risikofrdampak);
				$risikofrdampak['id_risiko'] = $id_risiko;

				$return = $this->conn->goInsert("risk_risiko_dampak", $risikofrdampak);
				if ($return) {
					$return = array('success' => $return);
				}
			}
		}

		#risiko penyebab
		if ($return['success'] && $id_risiko) {
			$risikofrpenyebabs = $this->conn->GetArray("select * from risk_risiko_penyebab 
						where deleted_date is null and id_risiko = " . $this->conn->escape($id));

			if ($risikofrpenyebabs)
				foreach ($risikofrpenyebabs as $risikofrpenyebab) {
					if (!$return['success'])
						break;

					$this->_setLogRec($risikofrpenyebab);
					$risikofrpenyebab['id_risiko'] = $id_risiko;

					$return = $this->conn->goInsert("risk_risiko_penyebab", $risikofrpenyebab);
					if ($return) {
						$return = array('success' => $return);
					}
				}
			else {

				if ($return) {
					$return = array('success' => $return);
				}
			}
		}

		#Pengendalian Berjalan
		if ($return['success'] && $id_risiko) {
			$risikofrcontrols = $this->conn->GetArray("select * from risk_control_risiko 
						where deleted_date is null and id_risiko = " . $this->conn->escape($id));

			foreach ($risikofrcontrols as $risikofrcontrol) {
				if (!$return['success'])
					break;

				$this->_setLogRec($risikofrcontrol);
				$risikofrcontrol['id_risiko'] = $id_risiko;

				$return = $this->conn->goInsert("risk_control_risiko", $risikofrcontrol);
				if ($return) {
					$return = array('success' => $return);
				}
			}
		}

		#risiko kemungkinan
		if ($return['success'] && $id_risiko) {
			// $risikofrkemungkinans = $this->conn->GetArray("select * from risk_risiko_fr_kemungkinan 
			// 	where jenis <> 'control' and id_risiko = " . $this->conn->escape($id));

			// foreach ($risikofrkemungkinans as $risikofrkemungkinan) {
			// 	if (!$return['success'])
			// 		break;

			// 	$this->_setLogRec($risikofrkemungkinan);
			// 	$risikofrkemungkinan['id_risiko'] = $id_risiko;
			// 	if ($risikofrkemungkinan['jenis'] == 'current')
			// 		$risikofrkemungkinan['jenis'] = 'control';

			// 	$return = $this->mrisikofrkemungkinan->Insert($risikofrkemungkinan);
			// }
		}

		#current
		// if ($id_risiko && $return['success']) {
		// 	$currents = $this->conn->GetArray("select * from
		// 	risk_risiko_current 
		// 	where id_risiko=" . $this->conn->escape($id));

		// 	foreach ($currents as $current) {
		// 		if (!$return['success'])
		// 			break;

		// 		$id_risiko_current_old = $current['id_risiko_current'];

		// 		unset($current['id_risiko_current']);
		// 		$this->_setLogRec($current);
		// 		$current['id_risiko'] = $id_risiko;
		// 		$current['id_risiko_current_sebelum'] = $id_risiko_current_old;

		// 		$return = $this->mrisikocurrent->Insert($current);

		// 		$id_risiko_current =  $return['data']['id_risiko_current'];

		// 		if ($return['success'] && $id_risiko_current) {
		// 			$frds = $this->conn->GetArray("select * from risk_risiko_current_fr_d 
		// 				where id_risiko_current = " . $this->conn->escape($id_risiko_current_old));

		// 			foreach ($frds as $frd) {
		// 				if (!$return['success'])
		// 					break;

		// 				$this->_setLogRec($frd);
		// 				$frd['id_risiko_current'] = $id_risiko_current;

		// 				$return = $this->mrisikocurrentfrd->Insert($frd);
		// 			}
		// 		}

		// 		if ($return['success'] && $id_risiko_current) {
		// 			$frks = $this->conn->GetArray("select * from risk_risiko_current_fr_k 
		// 				where id_risiko_current = " . $this->conn->escape($id_risiko_current_old));

		// 			foreach ($frks as $frk) {
		// 				if (!$return['success'])
		// 					break;

		// 				$this->_setLogRec($frk);
		// 				$frk['id_risiko_current'] = $id_risiko_current;

		// 				$return = $this->mrisikocurrentfrk->Insert($frk);
		// 			}
		// 		}
		// 	}
		// }

		#risiko files
		if ($return['success'] && $id_risiko) {
			$risikofiles = $this->conn->GetArray("select * from risk_risiko_files 
						where deleted_date is null and id_risiko = " . $this->conn->escape($id));

			foreach ($risikofiles as $risikofile) {
				if (!$return['success'])
					break;

				unset($risikofile['id_risiko_files']);
				$this->_setLogRec($risikofile);
				$risikofile['id_risiko'] = $id_risiko;

				$return = $this->mrisikofiles->Insert($risikofile);
			}
		}
		// dpr($return['success'],1);

		#controls
		// if ($id_risiko && $return['success']) {
		// 	$controls = $this->conn->GetArray("select * from
		// 	risk_control 
		// 	where id_risiko=" . $this->conn->escape($id));

		// 	foreach ($controls as $control) {
		// 		if (!$return['success'])
		// 			break;

		// 		$id_control_old = $control['id_control'];

		// 		unset($control['id_control']);
		// 		$this->_setLogRec($control);
		// 		$control['id_risiko'] = $id_risiko;
		// 		$control['id_control_sebelum'] = $id_control_old;

		// 		$return = $this->mcontrol->Insert($control);

		// 		$id_control =  $return['data']['id_control'];

		// 		if ($return['success'] && $id_control) {
		// 			$efektifitass = $this->conn->GetArray("select * from risk_control_efektifitas 
		// 				where id_control = " . $this->conn->escape($id_control_old));

		// 			foreach ($efektifitass as $efektifitas) {
		// 				if (!$return['success'])
		// 					break;

		// 				$this->_setLogRec($efektifitas);
		// 				$efektifitas['id_control'] = $id_control;

		// 				$return = $this->mcontrolefektifitas->Insert($efektifitas);
		// 			}

		// 			if ($return['success'] && $id_control) {
		// 				$efektifitasfiles = $this->conn->GetArray("select *
		// 					from risk_control_efektifitas_files 
		// 					where id_control=" . $this->conn->escape($id_control_old));

		// 				foreach ($efektifitasfiles as $efektifitasfile) {
		// 					if (!$return['success'])
		// 						break;

		// 					unset($efektifitasfile['id_control_efektifitas_files']);
		// 					$this->_setLogRec($efektifitasfile);
		// 					$efektifitasfile['id_control'] = $id_control;

		// 					$return = $this->mcontrolefektifitasfiles->Insert($efektifitasfile);
		// 				}
		// 			}
		// 		}
		// 	}
		// }

		#mitigasi
		if ($id_risiko && $return['success']) {
			// $mitigasis = $this->conn->GetArray("select *
			// 	from risk_mitigasi_risiko 
			// 	where id_risiko=" . $this->conn->escape($id));
			// $this->conn->debug=1;
			$mitigasis = $this->conn->GetArray("select * 
						from risk_mitigasi_risiko
						where deleted_date is null and id_risiko = " . $this->conn->escape($id));

			foreach ($mitigasis as $mitigasi) {
				if (!$return['success'])
					break;

				$id_mitigasi_old = $mitigasi['id_mitigasi'];

				unset($mitigasi['id_risiko']);
				$this->_setLogRec($mitigasi);
				$mitigasi['id_risiko'] = $id_risiko;


				// $return = $this->mmitigasi->Insert($mitigasi);
				$return = $this->conn->goInsert("risk_mitigasi_risiko", $mitigasi);
				if ($return) {
					$return = array('success' => $return);
				}
			}
			// dpr($return,1)
			// $id_mitigasi =  $return['data']['id_mitigasi'];

			// if ($return['success'] && $id_mitigasi) {
			// 	$mitigasiefektifs = $this->conn->GetArray("select * from risk_mitigasi_efektif where id_mitigasi = " . $this->conn->escape($id_mitigasi_old));

			// 	foreach ($mitigasiefektifs as $mitigasiefektif) {
			// 		if (!$return['success'])
			// 			break;

			// 		$id_mitigasi_efektif_old = $mitigasiefektif['id_mitigasi_efektif'];

			// 		unset($mitigasiefektif['id_mitigasi_efektif']);
			// 		$this->_setLogRec($mitigasiefektif);
			// 		$mitigasiefektif['id_mitigasi'] = $id_mitigasi;

			// 		$return = $this->mmitigasiefektif->Insert($mitigasiefektif);
			// 		$id_mitigasi_efektif = $return['data']['id_mitigasi_efektif'];

			// 		if ($return['success'] && $id_mitigasi_efektif_old) {
			// 			$mitigasiefektifms = $this->conn->GetArray("select * from risk_mitigasi_efektif_m 
			// 			where id_mitigasi_efektif = " . $this->conn->escape($id_mitigasi_efektif_old));

			// 			foreach ($mitigasiefektifms as $mitigasiefektifm) {
			// 				if (!$return['success'])
			// 					break;

			// 				$this->_setLogRec($mitigasiefektifm);
			// 				$mitigasiefektifm['id_mitigasi_efektif'] = $id_mitigasi_efektif;

			// 				$return = $this->mmitigasiefektifm->Insert($mitigasiefektifm);
			// 			}
			// 		}
			// 	}
			// }

			// $progressarr = array();
			// if ($return['success'] && $id_mitigasi) {
			// 	$mitigasiprogresss = $this->conn->GetArray("select * from risk_mitigasi_progress 
			// 		where id_mitigasi = " . $this->conn->escape($id_mitigasi_old));

			// 	foreach ($mitigasiprogresss as $mitigasiprogress) {
			// 		if (!$return['success'])
			// 			break;

			// 		$id_mitigasi_progress_old = $mitigasiprogress['id_mitigasi_progress'];
			// 		unset($mitigasiprogress['id_mitigasi_progress']);
			// 		$this->_setLogRec($mitigasiprogress);
			// 		$mitigasiprogress['id_mitigasi'] = $id_mitigasi;

			// 		$return = $this->mmitigasiprogress->Insert($mitigasiprogress);

			// 		$progressarr[$id_mitigasi_progress_old] = $return['data']['id_mitigasi_progress'];
			// 	}
			// }

			// if ($return['success'] && $id_mitigasi) {
			// 	$mitigasifiles = $this->conn->GetArray("select * from risk_mitigasi_files 
			// 		where id_mitigasi = " . $this->conn->escape($id_mitigasi_old));

			// 	foreach ($mitigasifiles as $mitigasifile) {
			// 		if (!$return['success'])
			// 			break;

			// 		unset($mitigasifile['id_mitigasi_files']);
			// 		$this->_setLogRec($mitigasifile);
			// 		$mitigasifile['id_mitigasi'] = $id_mitigasi;
			// 		$mitigasifile['id_mitigasi_progress'] = $progressarr[$mitigasifile['id_mitigasi_progress']];

			// 		$return = $this->mmitigasifiles->Insert($mitigasifile);
			// 	}
			// }

		}

		# integrasi internal
		if ($return['success'] && $id_risiko) {
			$risikofrcontrols = $this->conn->GetArray("select * from risk_integrasi_internal
						where deleted_date is null and id_risiko = " . $this->conn->escape($id));

			foreach ($risikofrcontrols as $risikofrcontrol) {
				if (!$return['success'])
					break;

				$this->_setLogRec($risikofrcontrol);
				$risikofrcontrol['id_risiko'] = $id_risiko;

				$return = $this->conn->goInsert("risk_integrasi_internal", $risikofrcontrol);
				if ($return) {
					$return = array('success' => $return);
				}
			}
		}

		#kri
		if ($id_risiko && $return['success']) {
			$kris = $this->conn->GetArray("select * from
					risk_kri 
					where deleted_date is null and id_risiko=" . $this->conn->escape($id));
			// dpr($kris,1);

			foreach ($kris as $kri) {
				if (!$return['success'])
					break;

				$id_kri_old = $kri['id_kri'];

				unset($kri['id_kri']);
				$this->_setLogRec($kri);
				$kri['id_risiko'] = $id_risiko;
				$kri['id_kri_sebelum'] = $id_kri_old;

				$return = $this->mkri->Insert($kri);

				$id_kri =  $return['data']['id_kri'];

				if ($return['success'] && $id_kri) {
					$hasils = $this->conn->GetArray("select * from risk_kri_hasil 
								where deleted_date is null and id_kri = " . $this->conn->escape($id_kri_old));

					foreach ($hasils as $hasil) {
						if (!$return['success'])
							break;

						unset($hasil['id_kri_hasil']);
						$this->_setLogRec($hasil);
						$hasil['id_kri'] = $id_kri;

						$return = $this->mkrihasil->Insert($hasil);
					}
				}
			}
		}
		// }
		// }

		if ($return['success']) {
			$record = array('tgl_close' => date('Y-m-d'));
			$return = $this->model->Update($record, "$this->pk = " . $this->conn->qstr($id));
		}

		if (!$return['success']) {
			return false;
		}
		return (bool)$return['success'];
	}

	private function _setLogRec(&$record = array(), $is_edit = false)
	{

		unset($record['created_date']);
		unset($record['created_by']);
		unset($record['modified_date']);
		unset($record['modified_by']);
		unset($record['is_lock']);
		// unset($record['id_control']);
		// unset($record['id_risiko']);
		// unset($record['id_risiko_files']);
		// unset($record['id_mitigasi']);
		// unset($record['id_control_efektifitas_files']);
		// unset($record['id_mitigasi_files']);

		$this->_setLogRecord($record, $is_edit);
	}
}
