<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Risk_mitigasi extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/risk_mitigasilist";
		$this->viewdetail = "panelbackend/risk_mitigasidetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_scorecard";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Mitigasi';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Mitigasi';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Mitigasi';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Mitigasi';
			$this->data['edited'] = true;
		}

		$this->load->model("Risk_mitigasiModel", "model");

		$this->load->model("Risk_mitigasi_filesModel", "modelfile");
		$this->data['configfile'] = $this->config->item('file_upload_config');

		$this->SetAccess(array('panelbackend/risk_scorecard', 'panelbackend/risk_risiko'));

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'select2', 'upload', 'tinymce'
		);

		$this->load->model("Mt_sdm_jabatanModel", "mtsdmjabatan");

		$this->data['penanggung_jawabarr'] = $this->conn->GetList("select 
				id_jabatan as idkey, 
				concat(nama,' (',coalesce(id_unit,''),')')  as val
				from mt_sdm_jabatan a
				where deleted_date is null and exists (select 1 from public_sys_user b where a.id_jabatan = b.id_jabatan and b.group_id = 26) ");


		// $this->data['revenue'] = $this->conn->GetOne("select revenue from mt_revenue where tahun = '" . date('Y') . "'");
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
				'name' => 'nama_aktifitas',
				'field' => 'm_____nama',
				'label' => 'Pengendalian / Mitigasi',
				'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'dead_line',
				'label' => 'Dead Line',
				'width' => "auto",
				'type' => "date",
			),
			array(
				'name' => 'nama_pic',
				'label' => 'Penanggung Jawab',
				'width' => "auto",
				'type' => "varchar2",
				'field' => "j_____nama"
			),
			array(
				'name' => 'cba',
				'label' => 'CBA',
				'add_label' => UI::createInfo("info_cba", "Info CBA (Cost Baseline Analisis)", "<ul style='padding-left: 15px;'><li>CBA digunakan untuk acuan mitigasi yang dilaksanakan layak atau tidak.</li><li>Apabila CBA Ratio > 100% berarti penanganan risiko tersebut memiliki manfaat lebih besar daripada biaya sehingga layak untuk diterapkan.</li><li>cba = (dampak finansial * rating inheren risk) - (dampak finansial * rating current risk) / biaya</li></ol>", 'model-xs', 1),
				'width' => "80px",
				'type' => "numeric"
			),
			array(
				'name' => 'status_progress',
				'label' => 'Progress (%)',
				'width' => "auto",
				'type' => "numeric",
			),
		);
	}

	protected function Record($id = null)
	{
		$this->post['rencana'] = Rupiah2Number($this->post['rencana']);
		$this->post['realisasi'] = Rupiah2Number($this->post['realisasi']);
		$this->post['biaya'] = Rupiah2Number($this->post['biaya']);
		$this->post['devisiasi'] = $this->post['rencana'] && $this->post['realisasi'] ? ($this->post['rencana']) - ($this->post['realisasi']) : 0;
		$record = array(
			'no' => $this->post['no'],
			'nama' => $this->post['nama'],
			'penanggung_jawab' => $this->data['row']['penanggung_jawab'],
			'program_kerjan' => $this->post['program_kerjan'],
			'menurunkan_dampak_kemungkinan' => $this->post['menurunkan_dampak_kemungkinan'],
			'remark' => $this->post['remark'],
			'start_date' => $this->post['start_date'],
			'biaya' => $this->post['biaya'],
			'dead_line' => $this->post['dead_line'],
			'is_efektif' => (int) $this->post['is_efektif'],

			'status_progress' => $this->post['status_progress'],
			'program_kerja' => $this->post['program_kerja'],
			'rencana' => $this->post['rencana'],
			'realisasi' => $this->post['realisasi'],
			'devisiasi' => $this->post['devisiasi'],
			'satuan' => $this->post['satuan'],
		);

		if (!$this->data['is_allow_edit_progress']) {
			unset($record['status_progress']);
			unset($record['program_kerja']);
			unset($record['rencana']);
			unset($record['realisasi']);
			unset($record['devisiasi']);
			unset($record['satuan']);
			unset($record['remark']);
		}

		if (!$this->data['is_allow_edit_mitigasi']) {
			unset($record['nama']);
			unset($record['penanggung_jawab']);
			unset($record['menurunkan_dampak_kemungkinan']);
			unset($record['biaya']);
			unset($record['revenue']);
			unset($record['start_date']);
			unset($record['dead_line']);
			unset($record['is_efektif']);
		}

		if (!$this->post['biaya'])
			unset($record['biaya']);

		if (!$this->post['revenue'])
			unset($record['revenue']);

		return $record;
	}

	protected function Rules()
	{
		$return = array(
			"nama" => array(
				'field' => 'nama',
				'label' => 'Nama Aktivitas Mitigasi',
				'rules' => "required|max_length[4000]",
			),
			"dead_line" => array(
				'field' => 'dead_line',
				'label' => 'Dead Line',
				'rules' => "required|date",
			),
			"rating" => array(
				'field' => 'rating',
				'label' => 'Rating',
				'rules' => "numeric",
			),
			"biaya" => array(
				'field' => 'biaya',
				'label' => 'Biaya',
				'rules' => "required|numeric",
			),
			"remark" => array(
				'field' => 'remark',
				'label' => 'Remark',
				'rules' => "max_length[4000]",
			),
		);

		if (!$this->config->item("mitigasi_biaya_required")) {
			unset($return['biaya']);
			unset($return['revenue']);
		}

		if (!$this->data['is_allow_edit_progress']) {
			unset($return['status_progress']);
		}

		if (!$this->data['is_allow_edit_mitigasi']) {
			unset($return['nama']);
			unset($return['menurunkan_dampak_kemungkinan']);
			unset($return['remark']);
			unset($return['biaya']);
			unset($return['revenue']);
			unset($return['dead_line']);
		}

		return $return;
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

	public function Index($id_risiko = null, $page = 0, $edited = 0)
	{

		if ($this->post['act'] == 'reset') {
			redirect("panelbackend/risk_mitigasi/index/$id_risiko/0");
		}

		if ($this->post['act'] == 'jadikan_control' && $this->post['idkey']) {
			$this->jadikan_control($this->post['idkey']);
		}


		$this->data['is_edit'] = $this->conn->GetOne("select 1 from risk_mitigasi where deleted_date is null and is_lock = '0' and id_risiko = " . $this->conn->escape($id_risiko));


		$this->_beforeDetail($id_risiko);

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

	private function isAllowEditMitigasi()
	{
		if (!$this->data['rowmitigasi'])
			$this->data['rowmitigasi'] = $this->data['row'];

		if ($this->data['mode'] != 'edit' && $this->mode != 'add')
			return false;

		if ($this->access_role['edit'] && !isset($this->data['rowmitigasi']))
			return true;

		if ($this->access_role['view_all'])
			return true;

		if ($this->data['rowmitigasi']['is_lock'] == '1')
			return false;

		if (
			$this->data['rowmitigasi']['penanggung_jawab'] == $_SESSION[SESSION_APP]['owner_jabatan']
			||
			$this->data['rowmitigasi']['penanggung_jawab'] == $_SESSION[SESSION_APP]['id_jabatan']
		)
			return true;

		return false;
	}

	private function isAllowEditProgress()
	{
		if (!$this->data['rowmitigasi'])
			$this->data['rowmitigasi'] = $this->data['row'];

		if ($this->data['mode'] != 'edit' && $this->mode != 'add')
			return false;

		if ($this->access_role['edit'] && !isset($this->data['rowmitigasi']))
			return true;

		if ($this->access_role['view_all'])
			return true;

		if (
			$this->data['rowmitigasi']['penanggung_jawab'] == $_SESSION[SESSION_APP]['owner_jabatan']
			||
			$this->data['rowmitigasi']['penanggung_jawab'] == $_SESSION[SESSION_APP]['id_jabatan']
		)
			return true;

		return false;
	}

	public function Edit($id_risiko = null, $id = null)
	{
		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$action_approve = false;
		if ($this->post['act'] == 'approve_mitigasi') {
			$this->post['act'] = 'save';
			$action_approve = true;
		}

		$action_ajukan = false;
		if ($this->post['act'] == 'ajukan_mitigasi') {
			$this->post['act'] = 'save';
			$action_ajukan = true;
		}

		$this->_beforeDetail($id_risiko, $id, true);
		$this->data['row'] = $this->data['rowmitigasi'] = $this->model->GetByPk($id);

		if (!$this->data['row'] && $id)
			$this->NoData();

		if ($this->data['row']['no'] == null)
			$this->data['row']['no'] = $this->model->GetNo($id_risiko);

		if (!$this->data['row']['penanggung_jawab'])
			$this->data['row']['penanggung_jawab'] = $this->data['rowheader']['owner'];

		$this->data['rowmitigasi'] = $this->data['row'];

		$this->data['is_allow_edit_progress'] = $this->isAllowEditProgress();
		$this->data['is_allow_edit_mitigasi'] = $this->isAllowEditMitigasi();

		if (
			$this->data['is_allow_edit_progress'] or
			$this->data['is_allow_edit_mitigasi'] or
			$action_approve or
			$action_ajukan
		) {
			$this->access_role['edit'] = true;
			$this->data['edited'] = true;
			$this->access_role_custom['panelbackend/risk_risiko']['edit'] = true;
		} else {
			$this->access_role['edit'] = false;
			$this->access_role_custom['panelbackend/risk_risiko']['edit'] = false;
			$this->data['edited'] = false;
		}

		if ($this->data['row']['status_konfirmasi'] == '1') {
			$this->access_role['delete'] = false;
		}

		if (!$this->data['is_allow_edit_mitigasi'] && !$this->data['is_allow_edit_progress'] && !$this->data['is_allow_edit_mitigasi']) {
			$this->access_role['edit'] = true;
			$this->data['edited'] = true;
		}

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");

		if ($this->post && $this->post['act'] <> 'change') {
			if (!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id);

			$this->data['row'] = array_merge($this->data['row'], $record);

			if ($this->post['biaya'])
				$this->post['biaya'] = Rupiah2Number($this->post['biaya']);
			else
				unset($this->post['biaya']);

			// if ($this->post['revenue'])
			// 	$this->post['revenue'] = Rupiah2Number($this->post['revenue']);
			// else
			// 	unset($this->post['revenue']);

			$this->data['row'] = array_merge($this->data['row'], $this->post);
		}
		$this->_onDetail($id, $record);


		$this->data['rules'] = $this->Rules();

		$record['penanggung_jawab'] = $this->data['row']['penanggung_jawab'];

		$this->data['rowmitigasi'] = $this->data['row'];

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {

			$this->data['row']['id_risiko'] = $record['id_risiko'] = $id_risiko;
			$record['status_konfirmasi'] = 1;

			$nilai_cr = (float) $this->data['rowheader1']['rating_kemungkinancr'] * (float) $this->data['rowheader1']['rating_dampakcr'];
			$nilai_rr = (float) $this->data['rowheader1']['rating_tingkatrisikors'] * (float) $this->data['rowheader1']['rating_dampakrisikors'];

			if ($nilai_rr) {
				$revenue = $this->data['rowheader1']['dampak_kuantitatif'];
				$implement_cost = $this->data['row']['biaya'];
				$rs_cba = HitungCBA($nilai_cr, $nilai_rr, $revenue, $implement_cost);
				$cba = (float) $rs_cba;

				$record['cba'] = $cba;
			}

			$this->_isValid($record, false);
			$this->_beforeEdit($record, $id);

			$this->_setLogRecord($record, $id);

			$this->model->conn->StartTrans();
			if ($this->data['row'][$this->pk]) {

				$return = $this->_beforeUpdate($record, $id);

				if ($action_ajukan)
					$record['status_konfirmasi'] = 4;

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
				}
				if ($return['success']) {

					$this->log("menambah", $record);

					$return1 = $this->_afterInsert($id);

					if (!$return1) {
						$return = false;
					}
				}
			}

			// $this->model->conn->CompleteTrans();


			if ($return['success']) {
				$this->conn->trans_commit();

				$this->_afterEditSucceed($id);

				if ($this->data['is_allow_edit_mitigasi'])
					$this->backtodraft($id_risiko);

				if ($action_ajukan) {

					$record = array(
						'page' => 'mitigasi',
						'deskripsi' => "Risiko yang Anda delegasikan sudah di disesuaikan silahkan cek",
						'id_risiko' => $id_risiko,
						'id_mitigasi' => $id,
						'untuk' => $this->data['row']['penanggung_jawab'],
						'url' => "panelbackend/risk_mitigasi/edit/$id_risiko/$id"
					);

					$this->InsertTask($record);
				}

				$addmsg = "";

				if ($is_insert) {
					$addmsg = '
					<script>$(function(){
						swal({
					        title: "Data berhasil disimpan",
					        text: "Apakah Anda ingin menambah mitigasi lagi ?",
					        type: "success",
					        showCancelButton: true,
					        confirmButtonColor: "#2b982b",
					        confirmButtonText: "Iya",
					        cancelButtonText: "Tidak",
					        cancelButtonColor: "#%d6B55",
					        closeOnConfirm: false
					    }, function (isConfirm) {
					    	if(isConfirm){
						        window.location = "' . site_url("panelbackend/risk_mitigasi/add/$id_risiko") . '";
						    }else{
					       	 window.location = "' . site_url("panelbackend/risk_mitigasi/index/$id_risiko/0/1") . '";
						    }
					    });
					})</script>';
				}

				SetFlash('suc_msg', $return['success'] . $addmsg);
				redirect("$this->page_ctrl/detail/$id_risiko/$id");
			} else {
				$this->conn->trans_rollback();

				$this->data['row'] = array_merge($this->data['row'], $record);
				$this->data['row'] = array_merge($this->data['row'], $this->post);

				$this->_afterEditFailed($id);

				$this->data['err_msg'] .= "Data gagal disimpan";
			}
		}

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	protected function _onDetail($id = null, &$record = array())
	{


		$pejabat = $this->data['row']['penanggung_jawab'];

		if (!$this->data['penanggung_jawabarr'][$pejabat]) {
			$nama = $this->conn->GetOne("select concat(nama,' (',coalesce(id_unit,''),')') from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($pejabat));

			$this->data['penanggung_jawabarr'][$pejabat] = $nama;
		}


		$pejabat = $this->data['rowheader']['owner'];

		if (!$this->data['penanggung_jawabarr'][$pejabat]) {
			$nama = $this->conn->GetOne("select concat(nama,' (',coalesce(id_unit,''),')') from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($pejabat));

			$this->data['penanggung_jawabarr'][$pejabat] = $nama;
		}
	}

	protected function _afterEditSucceed($id = null)
	{
		return true;
	}

	public function Delete($id_risiko = null, $id = null)
	{

		$this->model->conn->StartTrans();

		$this->_beforeDetail($id_risiko, $id);

		$this->data['row'] = $this->data['rowheader'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$this->isLock();

		$return = $this->_beforeDelete($id);

		if (!$this->access_role['delete'])
			$this->Error403();

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

	public function Detail($id_risiko = null, $id = null)
	{

		$this->_beforeDetail($id_risiko, $id);

		$this->data['row'] = $this->model->GetByPk($id);

		$this->_onDetail($id, $record);

		if (!$this->data['row'])
			$this->NoData();

		$this->data['rowmitigasi'] = $this->data['row'];

		if ($this->data['row']['status_konfirmasi'] == '1') {
			$this->access_role['delete'] = false;
		}

		if ($this->access_role['view_all']) {
			$this->access_role['delete'] = true;
			$this->access_role['edit'] = true;
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

		if ($this->modelfile) {
			if (!empty($this->post['files'])) {
				foreach ($this->post['files']['id'] as $k => $v) {
					$return = $this->_updateFiles(array($this->pk => $id), $v);

					$ret = $return['success'];
				}
			}
		}

		$id_risiko = $this->data['rowheader1']['id_risiko'];

		if ($_FILES['file']['name']) {
			$suc = $this->_uploadFile($id_risiko, $id, 'file');

			$ret = $suc['success'];

			if (!$ret) {
				$this->data['err_msg'] .= $suc['error'];
				return false;
			}
		}

		if ($ret) {
			$this->riskchangelog($this->data['row'], $this->data['rowold']);
		}

		return $ret;
	}

	function actionSimpanRisiko($id_mitigasi, $id)
	{
		if (!$this->Access("edit", 'panelbackend/risk_risiko'))
			$this->Error403();

		$this->load->model("Risk_risikoModel", "risiko");

		$record = array(
			'control_dampak_penurunan' => $this->post['control_dampak_penurunan'],
			'control_kemungkinan_penurunan' => $this->post['control_kemungkinan_penurunan'],
			'dampak_kuantitatif_current' => Rupiah2Number($this->post['dampak_kuantitatif_current']),
			// 'skor_control_dampak' => $this->post['skor_control_dampak'],
			// 'skor_control_kemungkinan' => $this->post['skor_control_kemungkinan'],
			// 'residual_target_kemungkinan' => $this->post['residual_target_kemungkinan'],
			// 'residual_target_dampak' => $this->post['residual_target_dampak'],
			// "skor_target_kemungkinan" => $this->post['skor_target_kemungkinan'],
			// "skor_target_dampak" => $this->post['skor_target_dampak'],
		);

		$record1 = $record;

		$this->riskchangelog($record1, $this->data['rowheader1']);

		$return = $this->risiko->Update($record, "id_risiko = " . $this->conn->qstr($id));

		if ($return['success'])
			$return = $this->HitungCBAMitigasi($id);

		if ($return['success']) {

			$this->backtodraft($id);

			SetFlash('suc_msg', "Data berhasil disimpan, untuk selanjutnya silahkan diajukan");
			redirect("panelbackend/risk_mitigasi/index/$id");
		} else {
			SetFlash('err_msg', "Data gagal disimpan");
			redirect(current_url());
		}

		die();
	}

	private function HitungCBAMitigasi($id_risiko = null)
	{

		$this->load->model("Risk_risikoModel", 'riskrisiko');

		$this->data['rowheader1']  = $this->riskrisiko->GetRatingDKRisiko($id_risiko);

		$nilai_cr = (float) $this->data['rowheader1']['rating_kemungkinancr'] * (float) $this->data['rowheader1']['rating_dampakcr'];

		$nilai_rr = (float) $this->data['rowheader1']['rating_tingkatrisikors'] * (float) $this->data['rowheader1']['rating_dampakrisikors'];

		//$revenue = $this->data['revenue'];

		$rows = $this->conn->GetArray("select * from risk_mitigasi where deleted_date is null and id_risiko = " . $this->conn->escape($id_risiko));

		$ret = array("success" => true);
		foreach ($rows as $row) {
			if (!$ret['success'])
				return false;

			$implement_cost = $row['biaya'];

			$revenue = $this->data['rowheader1']['dampak_kuantitatif_inheren'];

			$rs_cba = HitungCBA($nilai_cr, $nilai_rr, $revenue, $implement_cost);
			$cba = (float) $rs_cba;

			// dpr($nilai_cr);
			// dpr($nilai_rr);
			// dpr($revenue);
			// dpr($implement_cost);
			// dpr($cba, 1);
			$record = array();
			$record['cba'] = $cba;
			// $record['revenue'] = $revenue;

			$ret = $this->model->Update($record, "$this->pk = " . $this->conn->qstr($row['id_mitigasi']));
		}

		return $ret;
	}

	protected function _beforeDetail($id = null, $id_mitigasi = null, $is_edit = false)
	{
		#mengambil dari model karena sudah difilter sesuai akses
		$this->load->model("Risk_risikoModel", 'riskrisiko');
		$this->data['rowheader1']  = $this->riskrisiko->GetRatingDKRisiko($id);
		if (!$this->data['rowheader1'])
			$this->NoData();

		$id_scorecard = $this->data['rowheader1']['id_scorecard'];

		$this->load->model("Risk_scorecardModel", 'riskscorecard');
		$this->data['rowheader']  = $this->riskscorecard->GetByPk($id_scorecard);

		if (!$this->data['rowheader'])
			$this->NoData();

		$this->_accessTask($this->data['rowheader'], $this->data['rowheader1'], $this->data['edited']);

		$this->data['editedheader1'] = $this->data['edited'];

		if ($this->post['act'] == 'save' && !$is_edit)
			$this->actionSimpanRisiko($id_mitigasi, $id);

		$owner = $this->data['rowheader']['owner'];

		if ($owner) {
			$this->data['ownerarr'][$owner] = $this->conn->GetOne("select concat(nama,' (',coalesce(id_unit,''),')') from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($owner));
		}

		$row = $this->conn->GetRow("select id_kemungkinan as appetite_kemungkinan, id_dampak as appetite_dampak from mt_risk_taksonomi_appetite where deleted_date is null and id_taksonomi = " . $this->conn->escape($this->data['rowheader1']['id_taksonomi']) . " and tahun = " . $this->conn->escape(date('Y', strtotime($this->data['rowheader1']['tgl_risiko']))));

		if ($row) {
			$this->data['rowheader1']['appetite_kemungkinan'] = $row['appetite_kemungkinan'];
			$this->data['rowheader1']['appetite_dampak'] = $row['appetite_dampak'];
		}

		$this->data['add_param'] .= $id;


		if ($this->data['rowheader']['owner']) {

			$bawahanarr = jabatan_bawahan($this->data['rowheader']['owner'], $this->data['rowheader']['id_unit']);

			$addfilter = "";

			if (count($bawahanarr) <= 100 && !empty($bawahanarr)) {
				$addfilter = " and id_jabatan in (" . implode(", ", $bawahanarr) . ")";
			}

			$this->data['penanggung_jawabarr'] += $this->conn->GetList("select 
			id_jabatan as idkey, 
			concat(nama,' (',coalesce(id_unit,''),')') as val
			from mt_sdm_jabatan a
			where deleted_date is null and exists (select 1 from public_sys_user b where a.id_jabatan = b.id_jabatan and b.group_id = 2) 
			and id_unit = " . $this->conn->escape($this->data['rowheader']['id_unit']) . $addfilter);
		}
	}

	protected function _beforeUpdate($record = array(), $id = null)
	{
		$this->data['rowold'] = $this->model->GetByPk($id);

		if ($_POST['filesold'])
			$this->data['rowold']['files'] = $_POST['filesold'];

		if ($this->post['residual']) {
			$id_risiko = $record['id_risiko'];

			$record = array(
				'mitigasi_dampak_penurunan' => $this->post['mitigasi_dampak_penurunan'],
				'mitigasi_kemungkinan_penurunan' => $this->post['mitigasi_kemungkinan_penurunan'],
			);
			$ret = $this->riskrisiko->Update($record, "id_risiko = " . $this->conn->qstr($id_risiko));

			return (bool) $ret['success'];
		}

		return true;
	}

	protected function _beforeInsert($record = array())
	{
		return true;
	}

	protected function _afterDetail($id)
	{
		if ($this->modelfile) {
			if (!$this->data['row']['files']['id'] && $id) {
				$rows = $this->conn->GetArray("select *
				from {$this->modelfile->table}
				where deleted_date is null and {$this->model->pk} = " . $this->conn->escape($id));

				foreach ($rows as $r) {
					$this->data['row']['files']['id'][] = $r[$this->modelfile->pk];
					$this->data['row']['files']['name'][] = $r['client_name'];
				}
			}
		}

		$this->isLock();
	}


	protected function _beforeEdit(&$record = array(), $id)
	{
		$this->isLock();

		$this->_validAccessTask($this->data['rowheader'], $this->data['rowheader1'], $this->data['edited']);

		return true;
	}

	protected function _beforeDelete($id = null)
	{

		$this->isLock();

		$this->_validAccessTask($this->data['rowheader'], $this->data['rowheader1'], $this->data['edited']);

		$this->isLock();

		$return = $this->conn->Execute("update risk_task where deleted_date is null and id_mitigasi = " . $this->conn->escape($id));

		$return = $this->conn->Execute("update risk_mitigasi_files where deleted_date is null and id_mitigasi = " . $this->conn->escape($id));

		$return = $this->conn->Execute("update risk_control where deleted_date is null and ID_MITIGASI_SUMBER = " . $this->conn->escape($id));

		return $return;
	}

	function _uploadFile($id_risiko = null, $id = null, $jenis = "file")
	{

		if (!$_FILES[$jenis]['name'])
			return array('success' => true);

		$return = array('success' => true);

		$this->data['configfile']['file_name'] = 'mitigasi' . time() . $_FILES[$jenis]['name'];
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
			$record['jenis'] = $jenis;

			$record['id_mitigasi'] = $id;
			$ret = $this->modelfile->Insert($record);
			if (!$ret['success']) {
				unlink($upload_data['full_path']);
				$return = $ret;
			}
		}

		return $return;
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

	private function jadikan_control($id_mitigasi)
	{

		$this->conn->StartTrans();

		$mitigasi = $this->conn->GetRow("select * from risk_mitigasi where deleted_date is null and id_mitigasi = " . $this->conn->escape($id_mitigasi));

		if (!$this->Access('add', 'panelbackend/risk_control') or $mitigasi['status_progress'] != '100' or $mitigasi['status_konfirmasi'] == '0') {
			$this->conn->trans_rollback();
			SetFlash('err_msg', "Move to control gagal");
			redirect(current_url());
			return;
		}

		$ret = $this->conn->goUpdate("risk_mitigasi", array("is_control" => 1), "id_mitigasi = " . $this->conn->escape($id_mitigasi));

		$record = array(
			'id_risiko' => $mitigasi['id_risiko'],
			'nama' => $mitigasi['nama'],
			'deskripsi' => $this->post['deskripsi'],
			'is_efektif' => '1',
			'id_interval' => '6',
			'id_mitigasi_sumber' => $mitigasi['id_mitigasi'],
			'menurunkan_dampak_kemungkinan' => $mitigasi['menurunkan_dampak_kemungkinan']
		);

		$this->load->model("Risk_controlModel", 'mcontrol');

		if ($ret) {
			$return = $this->mcontrol->Insert($record);
			$ret = $id_control = $return['data']['id_control'];
		}

		if ($ret) {
			SetFlash('suc_msg', "Move to control berhasil");
			$this->conn->trans_commit();
			$id_risiko = $mitigasi['id_risiko'];
			$this->backtodraft($id_risiko);
			redirect("panelbackend/risk_control/detail/$id_risiko/$id_control");
		} else {
			$this->conn->trans_rollback();
			SetFlash('err_msg', "Move to control gagal");
			redirect(current_url());
		}
	}
}
