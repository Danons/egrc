<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Risk_penanganan extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/risk_penangananlist";
		$this->viewdetail = "panelbackend/risk_penanganandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_scorecard";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Pengendalian Lanjutan';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Pengendalian Lanjutan';
			$this->data['edited'] = true;
			unset($this->access_role['lst']);
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Pengendalian Lanjutan';
			$this->data['edited'] = false;
			unset($this->access_role['lst']);
		} else {
			$this->data['page_title'] = 'Pengendalian Lanjutan';
		}

		$this->load->model("Risk_risikoModel", "model");

		$this->load->model("Mt_risk_efektifitas_pengukuranModel", "pengukuran");
		$this->data['mtpengukuranarr'] = $this->pengukuran->GetCombo();

		$this->load->model("Mt_sdm_jabatanModel", "mtsdmjabatan");

		$this->data['mtsdmjabatanarr'] = $this->mtsdmjabatan->GetCombo();

		$this->load->model("Risk_mitigasiModel", "riskmitigasi");

		// $this->data['riskmitigasiarr'] = $this->riskmitigasi->GetCombo();
		$this->data['riskmitigasisasaran'] = $this->conn->GetList("select id_mitigasi as idkey, sasaran val from risk_mitigasi where deleted_date is null");
		$this->data['riskmitigasinomor'] = $this->conn->GetList("select id_mitigasi as idkey, nomor val from risk_mitigasi where deleted_date is null");
		// $this->data['integrasiinternal'] = $this->conn->GetList("select table_code as idkey, table_desc val from mt_sdm_unit");
		$this->data['prioritas'] = $this->conn->GetList("select id_prioritas as idkey, nama val from mt_prioritas where deleted_date is null");
		$this->data['prioritaswarna'] = $this->conn->GetList("select id_prioritas as idkey, warna val from mt_prioritas where deleted_date is null");
		$this->SetAccess('panelbackend/risk_scorecard');

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'select2', 'tinymce'
		);

		$this->data['id_periode_tw'] = 2;
		$this->data['tahun'] = $this->data['thn'] = date("Y");

		// $riskmitigasiarr = $this->conn->GetArray("select * from risk_mitigasi");
		// foreach($riskmitigasiarr as $f){
		// 	$this->data['riskmitigasiarr'][$f['id_mitigasi']]['nama'] = $f['nama'];
		// 	$this->data['riskmitigasiarr'][$f['id_mitigasi']]['nomor'] = $f['nomor'];
		// 	$this->data['riskmitigasiarr'][$f['id_mitigasi']]['sasaran'] = $f['sasaran'];

		// }

		// $this->data['penanggungjawabarr'] = ["" => ""] + $this->conn->GetList("select 
		// id_jabatan as idkey, 
		// concat(nama,' (',coalesce(id_unit,''),')')  as val
		// from mt_sdm_jabatan a
		// where exists (select 1 from public_sys_user_group b where a.id_jabatan = b.id_jabatan and b.group_id = 24) ");
		$this->data['penanggungjawabarr'] = ["" => ""] + $this->conn->GetList("select 
		id_jabatan as idkey, 
		concat(nama,' (',coalesce(id_unit,''),')')  as val
		from mt_sdm_jabatan a
		where a.deleted_date is null and exists (select 1 from public_sys_user_group b where a.id_jabatan = b.id_jabatan and b.group_id = 24) ");
		// $this->data['edit_m'] = [[]];
	}

	protected function Record($id = null)
	{
		// dpr($this->post);
		if ($this->post['mitigasi']) {
			foreach ($this->post['mitigasi'] as &$r) {
				// dpr($r);
				$r['biaya'] = Rupiah2Number($r['biaya']);


				// $r['id_mitigasi'] = $r['id_mitigasi_bak'] ? $r['id_mitigasi_bak'] : $r['id_mitigasi'];
				// if (is_numeric($r['nama']) && !$r['id_mitigasi_bak']) {
				// 	$r['id_mitigasi'] = $r['nama'];
				// }
				// $r['id'] = $r['nama'];
				// if (!$r['id_mitigasi'])
				// 	$r['id_mitigasi'] = $r['id'];

				if (is_numeric($r['nama'])) {
					$i = $r['nama'];
				}
				if (is_numeric($r['id_mitigasi'])) {
					$i = $r['id_mitigasi'];
				}
				if (is_numeric($r['id'])) {
					$i = $r['id'];
				}
				if (is_numeric($r['id_mitigasi_bak'])) {
					$i = $r['id_mitigasi_bak'];
				}
				// $r['id_mitigasi'] = $r['id_mitigasi_bak'] = $r['id'] = $i;
				$r['id_mitigasi'] = $r['id_mitigasi_bak'] = $r['id'] = $i ? $i : $r['nama'];

				// $mitigasi[$r['id_mitigasi']] = $r;
				unset($i);
				unset($r);
			}
			// die;
			// dpr($mitigasi,1);
			// $this->post['mitigasi'] = $mitigasi;
		}
		// dpr($this->post['mitigasi'], 1);
		if ($this->post['mitigasi']) {
			foreach ($this->post['mitigasi'] as $r) {
				// 	if (!$r['id_mitigasi'])
				// 		$r['id_mitigasi'] = $r['nama'];
				$mitigasi[$r['id_mitigasi']] = $r;
			}
			// $this->post['mitigasi'] = $mitigasi;
		}

		$record =  array(
			'residual_target_kemungkinan' => $this->post['residual_target_kemungkinan'],
			'residual_target_dampak' => $this->post['residual_target_dampak'],
			// 'dampak_kuantitatif_target' => Rupiah2Number($this->post['dampak_kuantitatif_target']),
			'integrasi_eksternal' => $this->post['integrasi_eksternal'],
			'id_prioritas' => $this->post['id_prioritas'],
			'mitigasi' => $this->post['mitigasi'],
		);

		$this->post['dampak_kuantitatif_target'] = $record['dampak_kuantitatif_target'];

		return $record;
	}

	protected function Rules()
	{
		$return = array(
			// "residual_target_dampak" => array(
			// 	'field' => 'residual_target_dampak',
			// 	'label' => 'Tingkat Dampak',
			// 	'rules' => "in_list[" . implode(",", array_keys($this->data['mtdampakrisikoarr'])) . "]|required",
			// ),
			// "residual_target_kemungkinan" => array(
			// 	'field' => 'residual_target_kemungkinan',
			// 	'label' => 'Tingkat Kemungkinan',
			// 	'rules' => "in_list[" . implode(",", array_keys($this->data['mtkemungkinanrisikoarr'])) . "]|required",
			// ),
		);

		return $return;
	}

	public function Index($id = null)
	{
		redirect("panelbackend/risk_penanganan/detail/$id");
	}

	public function Add($id_scorecard = null)
	{
		$this->Error403();
	}

	public function save_mitigasi($id)
	{
		$ret = true;
		// $this->conn->debug=1;
		// dpr($this->post,1);
		$ret = $this->conn->Execute("delete from risk_mitigasi_risiko where id_risiko = " . $this->conn->escape($id));
		if ($ret) {
			unset($this->data['row']['mitigasi']);
		}
		foreach ($this->post['mitigasi'] as $f) {
			if (!$ret)
				break;
			if (is_numeric($f['id_mitigasi']) || $f['id_mitigasi_bak']) {
				if (is_numeric($f['id_mitigasi_bak'])) {
					// $f['nama'] = $f['id_mitigasi'];
					$f['id_mitigasi'] = $f['id_mitigasi_bak'];
				}
				if (is_numeric($f['id_mitigasi']))
					$id_mitigasi = $this->conn->GetOne("select id_mitigasi from risk_mitigasi where deleted_date is null and id_mitigasi = " . $this->conn->escape($f['id_mitigasi']));
				if ($id_mitigasi) {
					$rec['nomor'] = $f['nomor'];
					$rec['sasaran'] = $f['sasaran'];
					$rec['start_date'] = $f['start_date'];
					$rec['end_date'] = $f['end_date'];
					$rec['penanggung_jawab'] = $f['penanggung_jawab'];
					$rec['menurunkan_dampak_kemungkinan'] = $f['menurunkan_dampak_kemungkinan'];
					$rec['penanganan_pencegahan'] = $f['penanganan_pencegahan'];
					$tr = $this->conn->goUpdate("risk_mitigasi", $rec, "id_mitigasi = " . $this->conn->escape($id_mitigasi));
					unset($rec);
				}
			}

			// if (!$id_mitigasi && !$f['id_mitigasi_bak']) {
			if (!$id_mitigasi) {
				$nomor_mitigasi = $this->riskmitigasi->getNomorRisiko(
					$this->data['rowheader']['id_unit'],
					$this->data['row']['id_taksonomi_area'],
					$this->data['row']['id_kpi'],
					$this->data['row']['tgl_risiko'],
					false
				);
				if (strlen($f['id_mitigasi']) < 1) {
					$f['id_mitigasi'] = '.';
				}
				$rec = array(
					'nama' => $f['id_mitigasi'],
					'nomor' => $nomor_mitigasi,
					'sasaran' => $f['sasaran'],
					'start_date' => $f['start_date'],
					'menurunkan_dampak_kemungkinan' => $f['menurunkan_dampak_kemungkinan'],
					'end_date' => $f['end_date'],
					'penanggung_jawab' => $f['penanggung_jawab'],
					'penanganan_pencegahan' => $f['penanganan_pencegahan'],
				);

				if ($rec['nama'] !== '' || $rec['nama'] !== null) {
					$ret = $this->conn->goInsert("risk_mitigasi", $rec);
				}
				unset($rec);
				if ($ret)
					$id_mitigasi = $this->conn->GetOne("select max(id_mitigasi) from risk_mitigasi where deleted_date is null");
			}

			if ($id_mitigasi) {
				$record = array(
					"id_risiko" => $id,
					"id_mitigasi" => $id_mitigasi,
				);
				$ret = $this->conn->goInsert('risk_mitigasi_risiko', $record);
				unset($id_mitigasi);
			}
			$this->data['row']['mitigasi'][$id_mitigasi]['id_mitigasi'] = $id_mitigasi;
		}
		// die;
		return $ret;
		// redirect(current_url());
	}

	public function Edit($id = null)
	{

		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->data['row'] = $this->model->GetByPk($id);

		$this->_beforeDetail($id);

		if ($this->post['act'] == 'set_value') {
			// $this->save_mitigasi($id);
		}

		if (!$this->data['row'] && $id)
			$this->NoData();

		// dpr($this->data['row']['id_scorecard'],1);
		$data_baru = false;
		if (!$this->data['row']['residual_target_kemungkinan']) {
			$data_baru = true;
		}
		$this->data['rowheader1'] = $this->data['row'];

		$this->isLock();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");

		if ($this->post && $this->post['act'] <> 'change') {
			if (!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id);
			// dpr($record);
			// dpr($this->post);


			$this->data['row'] = array_merge($this->data['row'], $this->post);
			$this->data['row'] = array_merge($this->data['row'], $record);
			// dpr($this->data['row'], 1);
		}

		$this->_onDetail($id, $record);

		if (strstr($this->post['act'], 'save_mitigasi')) {
			// dpr($this->post['mitigasi'], 1);
			foreach ($this->post['mitigasi'] as $g) {
				if ($g['edit']) {
					if (!is_numeric($g['id_mitigasi'])) {
						$g['id_mitigasi'] = $g['id_mitigasi_bak'];
					}
					if (is_numeric($g['id_mitigasi'])) {
						$r = array(
							'nama' => $g['nama'],
							'sasaran' => $g['sasaran'],
						);
						$this->conn->goUpdate("risk_mitigasi", $r, "id_mitigasi = " . $this->conn->escape($g['id_mitigasi']));
					}
				}
			}
			// dpr($this->post['mitigasi'],1);
			// redirect(current_url());
		}

		if (strstr($this->post['act'], 'edit_mitigasi')) {
			// dpr($this->post,1);
			$this->data['edit_m'][str_replace('edit_mitigasi_', '', $this->post['act'])] = str_replace('edit_mitigasi_', '', $this->post['act']);
			foreach ($this->data['row']['mitigasi'] as &$g) {
				if ($g['nama'] == str_replace('edit_mitigasi_', '', $this->post['act'])) {
					$g['edit'] = 'edit';
				}
			}
			// $this->data['row']['mitigasi'][str_replace('edit_mitigasi_', '', $this->post['act'])]['edit'] = 'edit';
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

				if ($data_baru) {
					$id_scorecard = $this->data['row']['id_scorecard'];
					$msg =  '
					<script>$(function(){
					  swal({
							title: "Data berhasil disimpan",
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
				}
				SetFlash('suc_msg', $return['success'] . $msg);
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


	public function Detail($id = null, $id_mitigasi = null)
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

		if (!$this->data['row']['integrasi_internal'])
			$this->data['row']['integrasi_internal'] = $this->conn->GetList("select id_unit as idkey, id_unit as val from risk_integrasi_internal where deleted_date is null and id_risiko = " . $this->conn->escape($id));

		if ($id) {
			$this->data['mode'] = 'edit_detail';
		}
		// dpr($this->data['row']['mitigasi'],1);
		if ($this->data['row']['mitigasi']) {
			$this->data['riskmitigasiarr'] = array('' => '-pilih-');
			foreach ($this->data['row']['mitigasi'] as $g) {
				if (is_numeric($g['id_mitigasi'])) {
					// $this->conn->debug=1;
					$this->data['riskmitigasiarr'][$g['id_mitigasi']] = $nama_mitigasi = $this->conn->GetOne("select nama from risk_mitigasi where deleted_date is null and id_mitigasi = " . $this->conn->escape($g['id_mitigasi']));
					if (!$nama_mitigasi) {
						$this->data['riskmitigasiarr'][$g['id_mitigasi']] = $g['nama'];
					}
				} else {
					$this->data['riskmitigasiarr'][$g['id_mitigasi']] = $g['id_mitigasi'];
				}
			}
		}
		// dpr($this->data['row']);
		// dpr($this->data['riskmitigasiarr'], 1);
		// dpr($this->data['row']['mitigasi'],1);
		if ($this->data['row']['integrasi_internal']) {
			foreach ($this->data['row']['integrasi_internal'] as $b) {
				$this->data['integrasiinternal'][$b] = $this->conn->GetOne("select table_desc from mt_sdm_unit where deleted_date is null and table_code = " . $this->conn->escape($b));
			}
		}
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
			$ret = $this->save_mitigasi($id);;
		if ($ret)
			$ret = $this->_delSertControl($id);

		if ($ret) {
			$this->riskchangelog($this->data['row'], $this->data['rowold']);
		}
		return $ret;
	}

	protected function _delSertControl($id)
	{
		$ret = true;

		if ($this->post['act'] == 'save') {
			// $id_mitigasi_arr = array(0);
			// if ($this->post['mitigasi'])
			// 	foreach ($this->post['mitigasi'] as $r) {
			// 		if (!$ret)
			// 			break;

			// 		$record = array();
			// 		$record['id_risiko'] = $id;
			// 		$record['nama'] = $r['nama'];
			// 		$record['biaya'] = $r['biaya'];
			// 		$record['dead_line'] = $r['dead_line'];
			// 		$record['penanggung_jawab'] = $r['penanggung_jawab'];
			// 		$record['id_prioritas'] = $r['id_prioritas'];
			// 		$record['nomor'] = $r['nomor'];
			// 		$record['sasaran'] = $r['sasaran'];

			// 		if ($r['id_mitigasi']) {
			// 			$ret = $this->conn->goUpdate("risk_mitigasi", $record, "id_mitigasi = " . $this->conn->escape($r['id_mitigasi']));
			// 		} else {
			// 			$ret = $this->conn->goInsert("risk_mitigasi", $record);
			// 			$r['id_mitigasi'] = $this->conn->GetOne("select max(id_mitigasi) from risk_mitigasi where id_risiko = " . $this->conn->escape($id));
			// 		}

			// 		if ($ret)
			// 			$id_mitigasi_arr[] = $r['id_mitigasi'];
			// 	}

			// if ($ret) {
			// 	$rows = $this->conn->GetArray("select id_mitigasi from risk_mitigasi where id_risiko = " . $this->conn->escape($id) . " and id_mitigasi not in (" . implode(",", $id_mitigasi_arr) . ")");

			// 	foreach ($rows as $r) {
			// 		if (!$ret)
			// 			break;

			// 		$ret = $this->conn->Execute("update from risk_mitigasi set deleted_date = now() where id_mitigasi = " . $this->conn->escape($r['id_mitigasi']));
			// 	}
			// }

			if ($ret && $this->post['integrasi_internal']) {
				$ret = $this->conn->Execute("deleted from risk_integrasi_internal where id_risiko = " . $this->conn->escape($id));
				foreach ($this->post['integrasi_internal'] as $d) {
					if (!$ret)
						break;
					$rec = array(
						"id_risiko" => $id,
						"id_unit" => $d,
					);
					$ret = $this->conn->goInsert("risk_integrasi_internal", $rec);
				}
			}
		}
		return $ret;
	}

	protected function _onDetail($id = null, &$record = array())
	{
		$this->_accessTask($this->data['rowheader'], $this->data['row'], $this->data['edited']);

		if (!$this->data['row']['mitigasi']) {
			$rows = $this->conn->GetArray("select * 
				from risk_mitigasi a
				where deleted_date is null and is_control <>1 and exists(select 1 from risk_mitigasi_risiko b where a.id_mitigasi = b.id_mitigasi and id_risiko = " . $this->conn->escape($id) . ")");


			$this->data['row']['mitigasi'] = array();
			foreach ($rows as $r) {
				$r['id'] = $r['id_mitigasi'];
				$this->data['row']['mitigasi'][$r['id_mitigasi']] = $r;
			}
			foreach ($this->data['row']['mitigasi'] as &$f) {
				$f['prioritasarr'] = $this->data['prioritas'];
			}
		}
	}
}
