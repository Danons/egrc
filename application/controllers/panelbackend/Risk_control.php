<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Risk_control extends _adminController
{
	public $limit = 100;

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/risk_controllist";
		$this->viewdetail = "panelbackend/risk_controldetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_scorecard";

		// unset($this->access_role['add']);

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Kontrol';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Kontrol';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Kontrol';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Analisis';
			$this->data['edited'] = true;
		}

		$this->load->model("Risk_controlModel", "model");

		$this->load->model("Risk_control_efektifitas_filesModel", "modelfile");
		$this->data['configfile'] = $this->config->item('file_upload_config');

		$this->load->model("Mt_intervalModel", "mtinterval");
		$mtinterval = $this->mtinterval;
		$this->data['mtintervalarr'] = $mtinterval->GetCombo();

		$this->load->model("Mt_risk_efektifitasModel", "mtefektifitas");
		$mtefektifitas = $this->mtefektifitas;
		$this->data['mtefektifitasarr'] = $mtefektifitas->getKetEfektifitas();

		$this->load->model("Mt_risk_efektifitas_pengukuranModel", "pengukuran");
		$this->data['mtpengukuranarr'] = $this->pengukuran->GetCombo();
		$this->data['rowspengukuran'] = $this->pengukuran->GArray();
		// unset($this->data['mtpengukuranarr']['']);

		$this->load->model("Mt_risk_efektifitas_jawabanModel", "jawaban");
		$this->data['mtjawabanarr'] = $this->jawaban->GetCombo();
		unset($this->data['mtjawabanarr']['']);

		$this->SetAccess(array('panelbackend/risk_scorecard', 'panelbackend/risk_risiko'));

		$this->load->model("DokumenModel", "dokumen");
		$this->data['dokumenarr'] = $this->dokumen->GetCombo();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array('select2');
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'no',
				'label' => 'No',
				'width' => "18px",
				'nofilter' => true,
				'type' => "numeric",
			),
			array(
				'name' => 'nama',
				'label' => 'Pengendalian Risiko Berjalan',
				'width' => "auto",
				'type' => "varchar2",
			),
			// array(
			// 	'name' => 'id_dokumen',
			// 	'label' => 'Dokumen',
			// 	'width' => "auto",
			// 	'type' => "list",
			// 	'value' => $this->data['dokumenarr'],
			// ),
			array(
				'name' => 'id_pengukuran',
				'label' => 'Efektif ?',
				'width' => "120px",
				'type' => "list",
				'value' => $this->data['mtpengukuranarr'],
			),
		);
	}

	protected function Record($id = null)
	{
		$record = array(
			'no' => $this->post['no'],
			'nama' => $this->post['nama'],
			'deskripsi' => $this->post['deskripsi'],
			'id_efektifitas' => $this->post['id_efektifitas'],
			'id_control_parent' => $this->post['id_control_parent'],
			'id_interval' => $this->post['id_interval'],
			'id_dokumen' => $this->post['id_dokumen'],
			'id_pengukuran' => $this->post['id_pengukuran'],
			'menurunkan_dampak_kemungkinan' => $this->post['menurunkan_dampak_kemungkinan'],
			'remark' => $this->post['remark'],
		);

		if (!$this->access_role['view_all'] && $this->data['row']['is_lock'] == '1') {
			unset($record['nama']);
			unset($record['deskripsi']);
			unset($record['id_interval']);
			unset($record['menurunkan_dampak_kemungkinan']);
			unset($this->post['nama']);
			unset($this->post['deskripsi']);
			unset($this->post['id_interval']);
			unset($this->post['menurunkan_dampak_kemungkinan']);
		}


		if ($this->access_role['rekomendasi']) {
			$record['rekomendasi_keterangan'] = $this->post['rekomendasi_keterangan'];

			if ($this->data['row']['is_lock'] == 1)
				$record['is_lock'] = 2;

			$record['rekomendasi_is_verified'] = 2;
			$record['rekomendasi_nid'] = $_SESSION[SESSION_APP]['nid'];
			$record['rekomendasi_jabatan'] = $_SESSION[SESSION_APP]['id_jabatan'];
			$record['rekomendasi_group'] = $_SESSION[SESSION_APP]['nama_group'];
			$record['rekomendasi_date'] = "{{sysdate()}}";
		}

		if ($this->access_role['review']) {
			$record['review_kepatuhan'] = $this->post['review_kepatuhan'];

			if ($this->data['row']['is_lock'] == 1)
				$record['is_lock'] = 2;

			$record['review_is_verified'] = 2;
			$record['review_nid'] = $_SESSION[SESSION_APP]['nid'];
			$record['review_jabatan'] = $_SESSION[SESSION_APP]['id_jabatan'];
			$record['review_group'] = $_SESSION[SESSION_APP]['nama_group'];
			$record['review_date'] = "{{sysdate()}}";
		}

		return $record;
	}

	protected function Rules()
	{
		$return = array(
			"nama" => array(
				'field' => 'nama',
				'label' => 'Nama',
				'rules' => "required|max_length[4000]",
			),
			// "id_interval" => array(
			// 	'field' => 'id_interval',
			// 	'label' => 'Interval',
			// 	'rules' => "required|in_list[" . implode(",", array_keys($this->data['mtintervalarr'])) . "]",
			// ),
			// "id_control_parent" => array(
			// 	'field' => 'id_control_parent',
			// 	'label' => 'Sub Dari',
			// 	'rules' => "in_list[" . implode(",", array_keys($this->data['mtcontrolarr'])) . "]",
			// ),
			// "menurunkan_dampak_kemungkinan" => array(
			// 	'field' => 'menurunkan_dampak_kemungkinan',
			// 	'label' => 'Menurunkan',
			// 	'rules' => "required|in_list[" . implode(",", array_keys($this->data['menurunkanrr'])) . "]",
			// ),
			"remark" => array(
				'field' => 'remark',
				'label' => 'Remark',
				'rules' => "max_length[4000]",
			),
		);

		if ($this->access_role['review']) {
			$return['review_kepatuhan'] = array(
				'field' => 'review_kepatuhan',
				'label' => 'Reviu Kepatuhan',
				'rules' => "required",
			);
		}

		if ($this->access_role['rekomendasi']) {
			$return['rekomendasi_keterangan'] = array(
				'field' => 'rekomendasi_keterangan',
				'label' => 'Dasar Penetapan Risiko',
				'rules' => "required",
			);
		}

		if ($this->data['edited'] && $this->data['row']['is_lock'] && !$this->access_role['view_all'])
			$return = true;

		return $return;
	}

	public function Index($id_risiko = null, $page = 0, $edited = 0)
	{

		if ($this->post['act'] == 'reset') {
			redirect("panelbackend/risk_control/index/$id_risiko/0");
		}

		$this->data['is_edit'] = $this->conn->GetOne("select 1 from risk_control where deleted_date is null and is_lock = '0' and id_risiko = " . $this->conn->escape($id_risiko));

		$this->_beforeDetail($id_risiko, null, $edited);

		if ($this->data['is_edit'] && $edited)
			$this->data['editedheader1'] = 1;

		if ($this->data['editedheader1'] && !$edited)
			$this->data['editedheader1'] = 0;

		$this->_setFilter("id_risiko = " . $this->conn->qstr($id_risiko));
		$this->data['list'] = $this->_getList($page);

		$this->data['header'] = $this->Header();
		$this->data['page'] = $page;
		$param_paging = array(
			'base_url' => base_url("{$this->page_ctrl}/index/$id_risiko"),
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

		$this->isLock();

		$this->View($this->viewlist);
	}
	public function Add($id_risiko = null)
	{
		$this->Edit($id_risiko);
	}

	public function Edit($id_risiko = null, $id = null)
	{

		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->_beforeDetail($id_risiko, $id);
		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'] && $id)
			$this->NoData();

		if ($this->data['row']['no'] == null)
			$this->data['row']['no'] = $this->model->GetNo($id_risiko);


		if ($this->post && $this->post['act'] <> 'change') {
			if (!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id);

			$this->data['row'] = array_merge($this->data['row'], $record);
			$this->data['row'] = array_merge($this->data['row'], $this->post);
		}

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save' or $this->post['act'] == 'save_rekomendasi' or $this->post['act'] == 'save_review') {

			if ($this->data['row']['is_lock'] == '2' && $this->data['row']['review_is_verified'] <> '2' && $this->data['row']['rekomendasi_is_verified'] <> '2')
				$record['is_lock'] = 1;

			$this->data['row']['id_risiko'] = $record['id_risiko'] = $id_risiko;

			$this->_isValid($record, false);

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
				$is_insert = 1;
				$return = $this->_beforeInsert($record);

				if ($return) {
					$return = $this->model->Insert($record);
					$id = $return['data'][$this->pk];
					$this->data['row'][$this->pk] = $id;
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
				$is_lock_local = !$this->access_role['view_all'] && $this->data['row']['is_lock'] == '1';

				if (!$is_lock_local)
					$this->backtodraft($id_risiko);

				$addmsg = "";

				if ($is_insert) {
					$addmsg = '
					<script>$(function(){
						swal({
					        title: "Data berhasil disimpan",
					        text: "Apakah Anda ingin menambah kontrol lagi ?",
					        type: "success",
					        showCancelButton: true,
					        confirmButtonColor: "#2b982b",
					        confirmButtonText: "Iya",
					        cancelButtonText: "Tidak",
					        cancelButtonColor: "#%d6B55",
					        closeOnConfirm: false
					    }, function (isConfirm) {
					    	if(isConfirm){
						        window.location = "' . site_url("panelbackend/risk_control/add/$id_risiko") . '";
						    }else{
					       	 window.location = "' . site_url("panelbackend/risk_control/index/$id_risiko/0/1") . '";
						    }
					    });
					})</script>';
				}

				SetFlash('suc_msg', $return['success'] . $addmsg);
				redirect("$this->page_ctrl/detail/$id_risiko/$id");
			} else {


				$this->model->conn->trans_rollback();

				$this->data['row'] = array_merge($this->data['row'], $record);
				$this->data['row'] = array_merge($this->data['row'], $this->post);

				$this->_afterEditFailed($id);

				$this->data['err_msg'] .= "Data gagal disimpan";
			}
		}

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	public function Detail($id_risiko = null, $id = null)
	{

		$this->_beforeDetail($id_risiko, $id);

		$this->data['row'] = $this->model->GetByPk($id);
		if (!$this->data['row'])
			$this->NoData();

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	public function Delete($id_risiko = null, $id = null)
	{

		$this->model->conn->StartTrans();

		$this->_beforeDetail($id_risiko);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$return = $this->_beforeDelete($id);

		if (!$this->access_role['delete'])
			$this->Error403();

		if ($this->data['row']['id_mitigasi_sumber'] && $return) {
			$return = $this->conn->Execute("update risk_mitigasi 
				set is_control=0
				where id_mitigasi = " . $this->conn->escape($this->data['row']['id_mitigasi_sumber']));
		}

		if ($return) {
			$return = $this->model->delete("$this->pk = " . $this->conn->qstr($id));
		}

		if ($return) {
			$return1 = $this->_afterDelete($id);
			if (!$return1)
				$return = false;
		}

		if ($return) {

			$this->backtodraft($id_risiko);

			$this->log("menghapus", $this->data['row']);

			$this->conn->trans_commit();

			SetFlash('suc_msg', $return['success']);
			redirect("$this->page_ctrl/index/$id_risiko");
		} else {

			$this->conn->trans_rollback();

			SetFlash('err_msg', "Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id_risiko/$id");
		}
	}



	protected function _afterUpdate($id)
	{
		return $this->_afterInsert($id);
	}

	protected function _afterInsert($id)
	{
		$ret = true;
		$id_control = $id;
		$id_risiko = $this->data['row']['id_risiko'];

		// $suc = $this->actionEfektifitas($id_control, $id_risiko);
		// if (!$suc['success'])
		// 	$ret = false;

		// if ($ret && $this->access_role['rekomendasi']) {
		// 	$suc = $this->_uploadFile($id, null, 'filerekomendasi');

		// 	$ret = $suc['success'];

		// 	if (!$ret) {
		// 		$this->data['err_msg'] .= $ret['error'];
		// 		return false;
		// 	}
		// }

		if ($ret) {
			$this->riskchangelog($this->data['row'], $this->data['rowold']);
		}

		return (bool)$ret;
	}

	private function actionEfektifitas($id_control, $id_risiko)
	{
		$this->load->model("Risk_control_efektifitasModel", "controlefektif");

		$suc = array('success' => 1);


		$totaljawaban = 0;
		unset($this->data['mtefektifitasarr']['']);
		foreach ($this->data['mtefektifitasarr'] as $idkey => $value) {
			/*		if($this->data['row']['is_lock'] && $this->data['edited'] && !$this->access_role['view_all'] && !$value['need_lampiran'])
				continue;*/

			if (!$suc['success'])
				break;

			$record = array();
			$record['id_control'] = $id_control;
			$record['id_efektifitas'] = $value['id_efektifitas'];
			$record['id_jawaban'] = (int)$this->post['efektif'][$value['id_efektifitas']]['id_jawaban'];

			unset($_SESSION[SESSION_APP]['efektif'][$value['id_efektifitas']]['keterangan']);

			$record['keterangan'] = null;

			if ($value['need_explanation'] && $record['id_jawaban'] <> '3') {
				$record['keterangan'] = $this->post['efektif'][$value['id_efektifitas']]['keterangan'];


				if (!$record['keterangan']) {
					$_SESSION[SESSION_APP]['efektif'][$value['id_efektifitas']]['keterangan'] = "Penjelasan efektifitas wajib di isi.";
					return false;
				}
			}


			$totaljawaban += $this->conn->GetOne("select bobot from mt_risk_efektifitas_bobot where deleted_date is null and id_efektifitas = " . $this->conn->escape($record['id_efektifitas']) . " and id_efektifitas_jawaban = " . $this->conn->escape($record['id_jawaban']));

			$cek = $this->conn->GetOne("select 1 from risk_control_efektifitas where  deleted_date is null and  id_control = " . $this->conn->escape($id_control) . " and id_efektifitas = " . $this->conn->escape($value['id_efektifitas']));

			if ($cek)
				$suc = $this->controlefektif->Update($record, "id_control = " . $this->conn->escape($id_control) . " and id_efektifitas = " . $this->conn->escape($value['id_efektifitas']));
			else
				$suc = $this->controlefektif->Insert($record);

			unset($_SESSION[SESSION_APP]['efektif'][$value['id_efektifitas']]['lampiran']);

			if ($suc['success'] && $value['need_lampiran'] && $record['id_jawaban'] <> '3') {
				$suc = $this->_uploadFile($id_control, $value['id_efektifitas'], 'file');

				if (!$suc['success']) {
					$_SESSION[SESSION_APP]['efektif'][$value['id_efektifitas']]['lampiran'] = $suc['error'];
					return false;
				}
			} else {
				$suc = $this->_deleteFile($id_control, $value['id_efektifitas']);
			}
		}

		if ($suc['success']) {
			$id_pengukuran = $this->conn->GetOne("select id_pengukuran from mt_risk_efektifitas_pengukuran where  deleted_date is null and  $totaljawaban between skor_bawah and skor_atas");

			$r = array("id_pengukuran" => $id_pengukuran);

			$this->data['id_pengukuran'] = $id_pengukuran;
			$this->data['row']['id_pengukuran'] = $id_pengukuran;

			$suc = $this->model->Update($r, "$this->pk = " . $this->conn->qstr($id_control));
		}

		return $suc;
	}

	function actionSimpanRisiko($id_control, $id)
	{
		if (!$this->Access("edit", 'panelbackend/risk_risiko'))
			$this->Error403();

		$this->load->model("Risk_risikoModel", "risiko");

		$record = array(
			'control_dampak_penurunan' => $this->post['control_dampak_penurunan'],
			'control_kemungkinan_penurunan' => $this->post['control_kemungkinan_penurunan'],
			// 'skor_control_dampak' => $this->post['skor_control_dampak'],
			// 'skor_control_kemungkinan' => $this->post['skor_control_kemungkinan'],
			// 'pengendalian_risiko_berjalan' => $this->post['pengendalian_risiko_berjalan'],
			// 'target_penyelesaian' => $this->post['target_penyelesaian'],
			// 'anggaran_biaya' => Rupiah2Number($this->post['anggaran_biaya']),
			// 'id_interval_anggaran' => $this->post['id_interval_anggaran'],
			'dampak_kuantitatif_current' => Rupiah2Number($this->post['dampak_kuantitatif_current']),
			// 'id_interval_kuantifikasi' => $this->post['id_interval_kuantifikasi'],
		);

		if (!is_array($this->data['rules']))
			return;

		$rules = array_values($this->data['rules']);

		$this->form_validation->set_rules($rules);

		if (count($rules) && $this->form_validation->run() == FALSE) {
			$this->data['err_msg'] = validation_errors();

			$this->data['rowheader1'] = array_merge($this->data['rowheader1'], $record);

			$this->_afterDetail($id_control);

			$this->View($this->viewlist);
			exit();
		}

		$record1 = $record;

		$this->riskchangelog($record1, $this->data['rowheader1']);

		$return = $this->risiko->Update($record, "id_risiko = " . $this->conn->qstr($id));


		if ($return['success']) {

			$this->backtodraft($id);

			if ($this->data['rowheader']['id_nama_proses']) {
				SetFlash('suc_msg', "Data berhasil disimpan");
				redirect(current_url());
			} else {
				SetFlash('suc_msg', "Data berhasil disimpan");
				redirect("panelbackend/risk_control/index/$id");
			}
		} else {
			SetFlash('err_msg', "Data gagal disimpan");
			redirect(current_url());
		}

		die();
	}


	protected function _beforeDetail($id = null, $id_control = null, $is_edit = false)
	{
		#mengambil dari model karena sudah difilter sesuai akses
		$this->load->model("Risk_risikoModel", 'riskrisiko');
		$this->data['rowheader1']  = $this->riskrisiko->GetByPk($id);

		if (!$this->data['rowheader1'])
			$this->NoData();

		$id_scorecard = $this->data['rowheader1']['id_scorecard'];

		$this->load->model("Risk_scorecardModel", 'riskscorecard');
		$this->data['rowheader']  = $this->riskscorecard->GetByPk($id_scorecard);

		if (!$this->data['rowheader'])
			$this->NoData();

		$this->_accessTask($this->data['rowheader'], $this->data['rowheader1'], $this->data['edited']);

		$this->data['editedheader1'] = $this->data['edited'];

		if ($is_edit) {
			$this->data['rules'] = array(
				// "pengendalian_risiko_berjalan" => array(
				// 	'field' => 'pengendalian_risiko_berjalan',
				// 	'label' => 'Pengendalian Risiko Berjalan',
				// 	'rules' => "required",
				// ),
				// "target_penyelesaian" => array(
				// 	'field' => 'target_penyelesaian',
				// 	'label' => 'Target Penyelesaian',
				// 	'rules' => "required",
				// ),
				// "anggaran_biaya" => array(
				// 	'field' => 'anggaran_biaya',
				// 	'label' => 'Anggaran Biaya',
				// 	'rules' => "required",
				// ),
				// "id_interval_anggaran" => array(
				// 	'field' => 'id_interval_anggaran',
				// 	'label' => 'Interval Anggaran',
				// 	'rules' => "required",
				// ),
				// "kuantifikasi" => array(
				// 	'field' => 'kuantifikasi',
				// 	'label' => 'Kuantifikasi',
				// 	'rules' => "required",
				// ),
				// "id_interval_kuantifikasi" => array(
				// 	'field' => 'id_interval_kuantifikasi',
				// 	'label' => 'Interval Kuantifikasi',
				// 	'rules' => "required",
				// ),
				// "kuantifikasi" => array(
				// 	'field' => 'kuantifikasi',
				// 	'label' => 'Kuantifikasi',
				// 	'rules' => "required",
				// ),
				"control_kemungkinan_penurunan" => array(
					'field' => 'control_kemungkinan_penurunan',
					'label' => 'Kemungkinan',
					'rules' => "required",
				),
				"control_dampak_penurunan" => array(
					'field' => 'control_dampak_penurunan',
					'label' => 'Dampak',
					'rules' => "required",
				),
			);
		}

		if ($this->post['act'] == 'save' && $is_edit)
			$this->actionSimpanRisiko($id_control, $id);

		$owner = $this->data['rowheader']['owner'];

		if ($owner) {
			$this->data['ownerarr'][$owner] = $this->conn->GetOne("select concat(nama,' (',coalesce(id_unit,''),')') from mt_sdm_jabatan where  deleted_date is null and  id_jabatan = " . $this->conn->escape($owner));
		}

		$this->data['mtcontrolarr'] = $this->model->GetCombo($id, $id_control);

		$this->data['add_param'] .= $id;
	}

	protected function _beforeUpdate($record = array(), $id = null)
	{
		$this->data['rowold'] = $this->model->GetByPk($id);
		$rowsefektif = $this->conn->GetArray("select * from risk_control_efektifitas where  deleted_date is null and  id_control = " . $this->conn->escape($id));
		$this->data['rowold']['efektif'] = array();
		foreach ($rowsefektif as $r) {
			$this->data['rowold']['efektif'][$r['id_efektifitas']] = $r;
		}

		return true;
	}

	protected function _beforeInsert($record = array())
	{
		return true;
	}

	protected function _afterDetail($id)
	{
		$this->isLock();


		if ($this->post['act'] == 'set_val') {
			$this->data['row'] = array_merge($this->data['row'], $this->post);
		}

		$this->data['filerekomendasi'] = $this->modelfile->GArray('*', "where jenis='filerekomendasi' and id_control = " . $this->conn->escape($id));
	}

	protected function _beforeEdit(&$record = array(), $id)
	{
		$this->_validAccessTask($this->data['rowheader'], $this->data['rowheader1'], $this->data['edited']);

		$this->isLock();

		return true;
	}

	protected function _beforeDelete($id = null)
	{
		$this->_validAccessTask($this->data['rowheader'], $this->data['rowheader1'], $this->data['edited']);

		$this->isLock();

		$return  = $this->conn->Execute("update risk_control_efektifitas_files set deleted_date = now() where id_control = " . $this->conn->escape($id));

		if ($return)
			$return = $this->conn->Execute("update risk_control_efektifitas set deleted_date = now() where id_control = " . $this->conn->escape($id));

		return $return;
	}


	function _deleteFile($id_control = null, $id_efektifitas = null)
	{
		$rows = $this->conn->GetArray("select * from risk_control_efektifitas_files where  deleted_date is null and  id_control = " . $this->conn->escape($id_control) . " and id_efektifitas = " . $this->conn->escape($id_efektifitas));

		$return = array('success' => true);

		foreach ($rows as $idkey => $value) {
			$id_file = $value['id_control_efektifitas_files'];
			$file_name = $this->modelfile->GetOne("select file_name from risk_control_efektifitas_files where  deleted_date is null and  id_control_efektifitas_files = " . $this->conn->escape($id_file));

			$return = $this->modelfile->Delete("id_control_efektifitas_files = " . $this->conn->escape($id_file));

			if ($return) {
				$full_path = $this->data['configfile']['upload_path'] . $file_name;
				@unlink($full_path);
			}
		}

		return $return;
	}

	function _uploadFile($id_control = null, $id_efektifitas = null, $jenis = "file")
	{
		$return = array('success' => true);

		if (!$id_efektifitas) {
			$cek = $this->conn->GetOne("select 1 from risk_control_efektifitas_files where  deleted_date is null and  jenis = '$jenis' and id_control = " . $this->conn->escape($id_control));
		} else {
			$cek = $this->conn->GetOne("select 1 from risk_control_efektifitas_files where  deleted_date is null and  id_control = " . $this->conn->escape($id_control) . " and id_efektifitas = " . $this->conn->escape($id_efektifitas));
		}

		if (!$_FILES[$jenis]['name'] && !$cek)
			return array('error' => "Lampiran wajib di isi");

		if (!$_FILES[$jenis]['name'])
			return array('success' => "Update berhasil");

		$this->data['configfile']['file_name'] = 'efektifitas_risiko' . time() . $_FILES[$jenis]['name'];

		$this->load->library('upload', $this->data['configfile']);

		if (!$this->upload->do_upload($jenis)) {
			$return = array('error' => $this->upload->display_errors());
		} else {
			$upload_data = $this->upload->data();
			$return = array('success' => "Upload " . $upload_data['client_name'] . " berhasil");

			$record = array();
			$record['client_name'] = $upload_data['client_name'];
			$record['file_name'] = $upload_data['file_name'];
			$record['file_type'] = $upload_data['file_type'];
			$record['file_size'] = $upload_data['file_size'];
			$record['id_control'] = $id_control;
			$record['jenis'] = $jenis;
			$record['id_efektifitas'] = $id_efektifitas;
			$ret = $this->modelfile->Insert($record);
			if (!$ret['success']) {
				unlink($upload_data['full_path']);
				$return = $ret;
			}
		}

		return $return;
	}

	function delete_file($id_risiko = null, $id = null, $id_file = null)
	{
		$file_name = $this->modelfile->GetOne("select file_name from risk_control_efektifitas_files where  deleted_date is null and  id_control_efektifitas_files = " . $this->conn->escape($id_file));

		$return = $this->modelfile->Delete("id_control_efektifitas_files = " . $this->conn->escape($id_file));

		if ($return) {
			$full_path = $this->data['configfile']['upload_path'] . $file_name;
			unlink($full_path);

			SetFlash('suc_msg', $return['success']);
		} else {
			SetFlash('err_msg', "Data gagal didelete");
		}
		redirect("$this->page_ctrl/edit/$id_risiko/$id");
	}

	function preview_file($id)
	{
		$row = $this->modelfile->GetByPk($id);
		if ($row) {
			$full_path = $this->data['configfile']['upload_path'] . $row['file_name'];
			header("Content-Type: {$row['file_type']}");
			header("Content-Disposition: inline; filename='{$row['client_name']}'");
			echo file_get_contents($full_path);
			die();
		} else {
			$this->Error404();
		}
	}
}
