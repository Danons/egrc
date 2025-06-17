<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Risk_analisis extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/risk_analisislist";
		$this->viewdetail = "panelbackend/risk_analisisdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_scorecard";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Analisis & Evaluasi';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Analisis & Evaluasi';
			$this->data['edited'] = true;
			unset($this->access_role['lst']);
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Analisis & Evaluasi';
			$this->data['edited'] = false;
			unset($this->access_role['lst']);
		} else {
			$this->data['page_title'] = 'Analisis & Evaluasi';
		}

		$this->load->model("Risk_risikoModel", "model");

		$this->data['responsearr'] = [
			"" => "",
			"Menghindari (Avoidance)" => "Menghindari (Avoidance)",
			"Mengurangi (Reduce)" => "Mengurangi (Reduce)",
			"Membagi (Share)" => "Membagi (Share)",
			"Menerima (Acceptance)" => "Menerima (Acceptance)",
		];

		// $this->data['riskresiko'] = array("" => "") + $this->conn->GetList("select id_control as idkey , nama as val from risk_control");

		$this->load->model("Mt_risk_kriteria_dampakModel", 'kriteria');
		$this->data['kriteriaarr'] = $this->kriteria->GetCombo();
		$this->data['kriteriakemungkinanarr'] = array('' => '', '1' => 'Probabilitas', '2' => 'Deskripsi Kualitatif', '3' => 'Insiden Sebelumnya');


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

		// $this->data['edit_m'] = [[]];
	}

	protected function Record($id = null)
	{
		if ((int)$this->post['inheren_kemungkinan'] * (int)$this->post['inheren_dampak'] >= $this->config->item("batas_nilai_signifikan")) {
			$this->post['is_signifikan_inherent'] = 1;
		} else {
			$this->post['is_signifikan_inherent'] = 0;
		}
		if ((int)$this->post['control_kemungkinan_penurunan'] * (int)$this->post['control_dampak_penurunan'] >= $this->config->item("batas_nilai_signifikan")) {
			$this->post['is_signifikan_current'] = 1;
		} else {
			$this->post['is_signifikan_current'] = 0;
		}

		// dpr($this->post, 1);
		// dpr($this->post['control'], 1);
		if (!strstr($this->post['act'], 'remove_control')) {
			if ($this->post['control']) {
				foreach ($this->post['control'] as $h => &$g) {
					// if (is_numeric($g['nama'])) {
					$g['id_control'] = $g['nama'];
					$g['id'] = $g['nama'];
					// }

				}
				// foreach ($this->post['control'] as $j) {
				// 	// if (!$this->post['act'] == 'remove_control_' . $j['id_control']) {
				// 	$contrl[$j['id_control']] = $j;
				// 	// }
				// }
				// $this->post['control'] = $contrl;
			}
		}
		$record =  array(
			'inheren_dampak' => $this->post['inheren_dampak'],
			'inheren_kemungkinan' => $this->post['inheren_kemungkinan'],
			'dampak_kuantitatif_inheren' => Rupiah2Number($this->post['dampak_kuantitatif_inheren']),
			'control_kemungkinan_penurunan' => $this->post['control_kemungkinan_penurunan'],
			'control_dampak_penurunan' => $this->post['control_dampak_penurunan'],
			// 'dampak_kuantitatif_current' => Rupiah2Number($this->post['dampak_kuantitatif_current']),
			'id_kriteria_dampak' => $this->post['id_kriteria_dampak'],
			'id_kriteria_kemungkinan' => $this->post['id_kriteria_kemungkinan'],
			// 'is_accept' => (int)$this->post['is_accept'],
			'is_accept' => (int)$this->post['is_signifikan_inherent'],
			'is_signifikan_inherent' => (int)$this->post['is_signifikan_inherent'],
			'is_signifikan_current' => (int)$this->post['is_signifikan_current'],
			'response' => $this->post['response'],
			'is_opp_inherent' => (int)$this->post['is_opp_inherent'],
			'is_opp_current' => (int)$this->post['is_opp_inherent'],
			'is_opp_target' => (int)$this->post['is_opp_inherent'],
			'is_opp_evaluasi' => (int)$this->post['is_opp_inherent'],
			'control' => $this->post['control'],
		);

		$this->post['dampak_kuantitatif_current'] = $record['dampak_kuantitatif_current'];
		$this->post['dampak_kuantitatif_inheren'] = $record['dampak_kuantitatif_inheren'];

		return $record;
	}

	protected function Rules()
	{
		$return = array(
			"inheren_dampak" => array(
				'field' => 'inheren_dampak',
				'label' => 'Tingkat Dampak',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtdampakrisikoarr'])) . "]|required",
			),
			"inheren_kemungkinan" => array(
				'field' => 'inheren_kemungkinan',
				'label' => 'Tingkat Kemungkinan',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtkemungkinanrisikoarr'])) . "]|required",
			),
			"control_dampak_penurunan" => array(
				'field' => 'control_dampak_penurunan',
				'label' => 'Tingkat Dampak',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtdampakrisikoarr'])) . "]|required",
			),
			"control_kemungkinan_penurunan" => array(
				'field' => 'control_kemungkinan_penurunan',
				'label' => 'Tingkat Kemungkinan',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtkemungkinanrisikoarr'])) . "]|required",
			),
			// "is_opp_inherent" => array(
			// 	'field' => 'is_opp_inherent',
			// 	'label' => 'Risk/Opportunity',
			// 	'rules' => "required",
			// ),
		);

		return $return;
	}

	public function Index($id = null)
	{
		redirect("panelbackend/risk_analisis/detail/$id");
	}

	public function Add()
	{
		$this->Error403();
	}

	public function Edit($id = null)
	{

		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->data['row'] = $this->model->GetByPk($id);
		if (!$this->data['row']['is_opp_inherent'])
			$this->data['row']['is_opp_inherent'] = 100;

		$this->_beforeDetail($id);

		if ($this->post['act'] == 'jadikan_control' && $this->post['idkey']) {
			$this->jadikan_control($this->post['idkey'], $id);
		}
		if (!$this->data['row'] && $id)
			$this->NoData();

		$this->data['rowheader1'] = $this->data['row'];

		$this->isLock();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");

		if ($this->post && $this->post['act'] <> 'change') {
			if (!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id);

			$this->data['row'] = array_merge($this->data['row'], $record);
			$this->data['row'] = array_merge($this->data['row'], $this->post);
		}

		if ($this->post['act'] == 'set_value') {
			// dpr($this->post,1);

			// $ret = $this->conn->Execute("delete from risk_control_risiko where id_risiko = " . $this->conn->escape($id));

			// foreach ($this->post['control'] as $g) {
			// 	if (!$ret)
			// 		break;
			// 	$data_control = null;
			// 	if (is_numeric($g['id_control'])) {
			// 		$data_control = $this->conn->GetRow("select * from risk_control where id_control = " . $this->conn->escape($g['id_control']));
			// 		if ($data_control)
			// 			$g['id_control'] = $data_control['id_control'];
			// 	} else if (!is_numeric($g['id_control'])) {
			// 		// else (!$g['id_control']) {
			// 		$record['nama'] = $g['id_control'];
			// 		$record['id_pengukuran'] = $g['id_pengukuran'];
			// 		$ret = $this->conn->goInsert("risk_control", $record);
			// 		$g['id_control'] = $this->conn->GetOne("select max(id_control) from risk_control");
			// 	} else {
			// 		// $record['nama'] = $data_control['nama'];
			// 		// $record['id_pengukuran'] = $data_control['id_pengukuran'];
			// 		// $ret = $this->conn->goUpdate("risk_control", $record, "id_control = " . $this->conn->escape($id_control));
			// 	}

			// 	if ($g['id_control']) {
			// 		$data['id_risiko'] = $id;
			// 		$data['id_control'] = $g['id_control'];
			// 		$ret = $this->conn->goInsert('risk_control_risiko', $data);
			// 		unset($id_control);
			// 		unset($data);
			// 	}
			// }

			// redirect(current_url());
		}

		$this->_onDetail($id, $record);

		if (strstr($this->post['act'], 'save_control')) {
			foreach ($this->post['control'] as $g) {
				if ($g['edit'] && is_numeric($g['id_control'])) {
					$r = array(
						'menurunkan_dampak_kemungkinan' => $g['menurunkan_dampak_kemungkinan'],
						'nama' => $g['nama'],
						'id_pengukuran' => $g['id_pengukuran'],
						'status_progress' => $g['status_progress'],
					);

					$this->conn->goUpdate("risk_control", $r, "id_control = " . $this->conn->escape($g['id_control']));
				}
			}
			// redirect(current_url());
		}
		if (strstr($this->post['act'], 'edit_control')) {
			$this->data['edit_m'][str_replace('edit_control_', '', $this->post['act'])] = str_replace('edit_control_', '', $this->post['act']);

			foreach ($this->data['row']['control'] as &$g) {
				if ($g['nama'] == str_replace('edit_control_', '', $this->post['act'])) {
					$g['edit'] = 'edit';
				}
			}
			// $this->data['row']['control'][str_replace('edit_control_', '', $this->post['act'])]['edit'] = 'edit';
		}
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

				$this->backtodraft($id);

				$this->_afterEditSucceed($id);

				if ($record['is_signifikan_current']) {
					$this->ctrl = "risk_penanganan";
					SetFlash('suc_msg', "Silahkan Menambah Pengendalian Lanjutan");
					redirect(base_url("panelbackend/risk_penanganan/edit/$id"));
				} else {
					$id_scorecard = $this->data['row']['id_scorecard'];
					$msg =  '
					<script>$(function(){
					  swal({
							title: "Risiko/Peluang Tidak Signifikan",
							text: "Apakah Anda ingin menambah risiko/peluang lagi ?",
							type: "success",
							showCancelButton: true,
							confirmButtonColor: "#2b982b",
							confirmButtonText: "Iya",
							cancelButtonText: "Tidak",
							cancelButtonColor: "#DD6B55",
							closeOnConfirm: false
						}, function (isConfirm) {
						  if(isConfirm){
							  window.location = "' . site_url("panelbackend/risk_risiko/add/$id_scorecard") . '";
						  }else{
							  window.location = "' . site_url("panelbackend/risk_risiko/index/$id_scorecard") . '";
						  }
						});
					})</script>';
					SetFlash('suc_msg', $return['success'] . $msg);
					redirect("$this->page_ctrl/detail/$id");
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

	protected function _afterUpdate($id)
	{
		$ret = $this->_afterInsert($id);

		return $ret;
	}

	protected function _afterInsert($id)
	{
		$ret = true;

		if ($ret)
			$ret = $this->save_ddl($id);
		if ($ret)
			$ret = $this->_delSertControl($id);

		if ($ret) {
			$this->riskchangelog($this->data['row'], $this->data['rowold']);
		}
		return $ret;
	}

	public function save_ddl($id)
	{
		$ret = true;
		if ($this->post['control']) {
			// $this->conn->debug = 1;
			// dpr($this->post['control'],1);
			$ret = $this->conn->Execute("update risk_control_risiko set deleted_date = now() where id_risiko = " . $this->conn->escape($id));
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
			$ret = $this->conn->Execute("update risk_control_risiko set deleted_date = now() where id_risiko = " . $this->conn->escape($id));
		}
		return $ret;
	}
	protected function _delSertControl($id)
	{
		$ret = true;

		// if ($this->post['act'] == 'save') {
		// 	$id_control_arr = array(0);
		// 	if ($this->post['control'])
		// 		foreach ($this->post['control'] as $r) {
		// 			if (!$ret)
		// 				break;

		// 			$record = array();
		// 			$record['id_risiko'] = $id;
		// 			$record['nama'] = $r['nama'];
		// 			$record['id_pengukuran'] = $r['id_pengukuran'];

		// 			if ($r['id_control']) {
		// 				$ret = $this->conn->goUpdate("risk_control", $record, "id_control = " . $this->conn->escape($r['id_control']));
		// 			} else {
		// 				$ret = $this->conn->goInsert("risk_control", $record);
		// 				$r['id_control'] = $this->conn->GetOne("select max(id_control) from risk_control where id_risiko = " . $this->conn->escape($id));
		// 			}

		// 			if ($ret)
		// 				$id_control_arr[] = $r['id_control'];
		// 		}

		// 	if ($ret) {
		// 		$rows = $this->conn->GetArray("select id_control from risk_control where id_risiko = " . $this->conn->escape($id) . " and id_control not in (" . implode(",", $id_control_arr) . ")");

		// 		foreach ($rows as $r) {
		// 			if (!$ret)
		// 				break;

		// 			$ret = $this->conn->Execute("delete from risk_control where id_control = " . $this->conn->escape($r['id_control']));
		// 		}
		// 	}
		// }
		return $ret;
	}

	public function Detail($id = null)
	{

		$this->data['row'] = $this->model->GetByPk($id);

		$this->_beforeDetail($id);

		if ($this->post['act'] == 'jadikan_control' && $this->post['idkey']) {
			$this->jadikan_control($this->post['idkey'], $id);
		}
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
			// 	// $this->data['ownerarr'][$owner] = $this->conn->GetOne("select concat(nama,' (',coalesce(id_unit,''),')') from mt_sdm_jabatan where id_jabatan = " . $this->conn->escape($owner));
			$this->data['ownerarr'][$owner] = $this->conn->GetOne("select concat(nama,' (',coalesce(id_unit,''),')') from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($owner));

			// 	$this->load->model("Risk_kegiatanModel", 'kegiatan');

			// 	if ($this->post['id_sasaran'])
			// 		$id_sasaran = $this->post['id_sasaran'];
			// 	elseif ($id_risiko)
			// 		$id_sasaran = $this->conn->GetOne("select id_sasaran from risk_risiko where id_risiko = " . $this->conn->escape($id_risiko));

			// 	$this->data['mtkegiatanarr'] = $this->kegiatan->GetCombo($id_sasaran);

			// 	$this->load->model("Risk_sasaranModel", "msasaran");

			// 	$this->data['sasaranarr'] = $this->msasaran->GetCombo($owner);

			// 	$this->data['sasaranarr'][$id_sasaran] = $this->msasaran->GetNama($id_sasaran);
		}

		// $this->data['add_param'] .= $id_risiko;
	}

	protected function _beforeUpdate($record = array(), $id = null)
	{

		// $row = $this->model->GetByPk($id);
		$this->data['rowold'] = $this->model->GetByPk($id);

		// $this->riskchangelog($record, $row);

		return true;
	}

	protected function _beforeInsert($record = array())
	{
		// $this->riskchangelog($record);
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

		$this->data['rowheader1'] = $this->data['row'];

		if ($id) {
			$this->data['mode'] = 'edit_detail';
		}
		if ($this->data['row']['control']) {
			$this->data['riskresiko'] = array('' => '-pilih-');
			foreach ($this->data['row']['control'] as $b) {
				if (is_numeric($b['id_control'])) {
					$this->data['riskresiko'][$b['id_control']] = $this->conn->GetOne("select nama from risk_control where deleted_date is null id_control = " . $this->conn->escape($b['id_control']));
				} else {
					$this->data['riskresiko'][$b['id_control']] = $b['id_control'];
				}
			}
		}
		$this->data['control_post'] = $this->post['control'];
		if ($this->post['control'])
			foreach ($this->data['riskresiko'] as $j => &$g) {
				// if (!is_numeric($this->post['control'][$j]['nama']) && ($this->post['control'][$j]['nama'] !== $g))
				// $g = $this->post['control'][$j]['nama'];
			}
	}

	protected function _onDetail($id = null, &$record = array())
	{
		$this->_accessTask($this->data['rowheader'], $this->data['row'], $this->data['edited']);

		if (!$this->data['row']['control']) {
			$rows = $this->conn->GetArray("select * 
				from risk_control a
				where deleted_date is null and exists(select 1 from risk_control_risiko b where b.deleted_date is null and a.id_control = b.id_control and id_risiko = " . $this->conn->escape($id) . ")");

			$this->data['row']['control'] = array();
			foreach ($rows as $r) {
				$r['id'] = $r['id_control'];
				$this->data['row']['control'][$r['id_control']] = $r;
			}
		}

		$id_risiko_sebelum = $this->conn->GetOne("select id_risiko_sebelum from risk_risiko where deleted_date is null and id_risiko = " . $this->conn->escape($id));
		if ($id_risiko_sebelum) {
			// $mitigasi_sebelum = $this->conn->GetArray("select * from risk_mitigasi a where status_progress = 100 and is_control != 1 and id_mitigasi in (select id_mitigasi from risk_mitigasi_risiko where id_risiko = " . $this->conn->escape($id) . ")");
			$mitigasi_sebelum = $this->conn->GetArray("select * from risk_mitigasi a where a.deleted_date is null and status_progress = 100 and is_control != 1 and id_mitigasi in (select id_mitigasi from risk_mitigasi_risiko where deleted_date is null and id_risiko = " . $this->conn->escape($id_risiko_sebelum) . ")");
			foreach ($mitigasi_sebelum as $f) {
				$this->data['mitigasiarr'][$f['id_mitigasi']] = $f['nama'];
				$this->data['row']['mitigasi'][$f['id_mitigasi']] = $f;
				$this->data['row']['mitigasi'][$f['id_mitigasi']]['id_risiko_baru'] = $id;
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

		// if ($totalefektifitaskemungkinan && $this->data['row']['inheren_kemungkinan'])
		$this->data['row']['control_kemungkinan_penurunan'] = (int)$this->data['row']['inheren_kemungkinan'] - (int)$totalefektifitaskemungkinan;

		// if ($totalefektifitasdampak && $this->data['row']['inheren_dampak'])
		$this->data['row']['control_dampak_penurunan'] = (int)$this->data['row']['inheren_dampak'] - (int)$totalefektifitasdampak;

		if ($this->data['row']['control_kemungkinan_penurunan'] < 1) {
			$this->data['err_msg'] = "Tingkat efektifitas kemungkinan melebihi batas minimal tingkat kemungkinan risiko residual saat ini";
		}
		if ($this->data['row']['control_dampak_penurunan'] < 1) {
			$this->data['err_msg'] = "Tingkat efektifitas dampak melebihi batas minimal tingkat dampak risiko residual saat ini";
		}
		// dpr($id_risiko_sebelum,1);
	}

	private function jadikan_control($id_mitigasi, $id)
	{

		$this->conn->StartTrans();

		$mitigasi = $this->conn->GetRow("select * from risk_mitigasi where deleted_date is null and id_mitigasi = " . $this->conn->escape($id_mitigasi));

		// if (!$this->Access('add', 'panelbackend/risk_control') or $mitigasi['status_progress'] != '100' or $mitigasi['status_konfirmasi'] == '0') {
		if (!$this->Access('add', 'panelbackend/risk_analisis') or $mitigasi['status_progress'] != '100') {
			$this->conn->trans_rollback();
			SetFlash('err_msg', "Move to control gagal");
			redirect(current_url());
			return;
		}

		$ret = $this->conn->goUpdate("risk_mitigasi", array("is_control" => 1), "id_mitigasi = " . $this->conn->escape($id_mitigasi));


		$record = array(
			// 'id_risiko' => $mitigasi['id_risiko'],
			'nama' => $mitigasi['nama'],
			'deskripsi' => $this->post['deskripsi'],
			'is_efektif' => '1',
			// 'id_interval' => '6',
			'id_mitigasi_sumber' => $mitigasi['id_mitigasi'],
			'menurunkan_dampak_kemungkinan' => $mitigasi['menurunkan_dampak_kemungkinan']
		);

		$this->load->model("Risk_controlModel", 'mcontrol');

		if ($ret) {
			$return = $this->mcontrol->Insert($record);
			$ret = $id_control = $return['data']['id_control'];
		}
		if ($ret) {
			$rec = array(
				'id_risiko' => $id,
				'id_control' => $id_control,
			);
			$ret = $this->conn->goInsert("risk_control_risiko", $rec);
		}

		if ($ret) {
			SetFlash('suc_msg', "Move to control berhasil");
			$this->conn->trans_commit();
			$this->backtodraft($id_risiko);
			redirect("panelbackend/risk_analisis/detail/$id");
		} else {
			$this->conn->trans_rollback();
			SetFlash('err_msg', "Move to control gagal");
			redirect(current_url());
		}
	}
}
