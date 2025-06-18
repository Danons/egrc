<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Opp_scorecard extends _adminController
{
	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/opp_scorecardlist";
		$this->viewdetail = "panelbackend/opp_scorecarddetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Lingkup Kajian Peluang';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Ubah Lingkup Kajian Peluang';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Lingkup Kajian Peluang';
			$this->data['edited'] = false;
		} else {
			$this->mode = 'index';
			$this->data['mode'] = 'index';
			$this->data['page_title'] = 'Lingkup Kajian Peluang';
		}

		$this->load->model("Opp_scorecardModel", "model");
		$this->data['configfile'] = $this->config->item('file_upload_config');
		$this->load->model("Opp_scorecard_filesModel", "modelfile");


		$this->load->model("Mt_sdm_unitModel", "munit");
		$this->data['unitarr'] = $this->munit->GetCombo();

		$this->load->model("Mt_pb_nama_prosesModel", "mtpbnamaproses");
		$this->data['mtpbnamaprosesarr'] = $this->mtpbnamaproses->GetCombo();
		/*
		$this->load->model("Mt_sdm_jabatanModel","mtsdmjabatan");
		$this->data['mtsdmjabatanarr'] = $this->mtsdmjabatan->GetCombo();*/


		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'treetable', 'select2'
		);
	}

	public function Index($id_parent_scorecard = 0)
	{

		$this->_beforeDetail($id_parent_scorecard);
		if (1 /*!$_SESSION[SESSION_APP]['tgl_efektif']*/)
			$_SESSION[SESSION_APP]['tgl_efektif'] = date('Y-m-d');

		$this->data['tgl_efektif'] = $tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		// if($this->access('view_all')){
			$this->data['rows'] = $this->model->GetList($id_parent_scorecard, $tgl_efektif);
		// }else{
		// 	$this->data['rows'] = $this->model->GetList($id_parent_scorecard, $tgl_efektif, null,null,null,$_SESSION[SESSION_APP]['id_unit']);
		// }

		if ($id_parent_scorecard) {
			unset($this->data['page_title']);
			if ($this->access_role['view_all']) {
				$this->data['mode'] = 'add|edit|delete';
				$this->data['row'] = $this->model->GetByPk($id_parent_scorecard);
			}
		}

		$this->data['id_parent_scorecard'] = $id_parent_scorecard;

		$this->View($this->viewlist);
	}

	protected function Record($id = null)
	{

		if (1 /*!$_SESSION[SESSION_APP]['tgl_efektif']*/)
			$_SESSION[SESSION_APP]['tgl_efektif'] = date('Y-m-d');

		$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

		if (!$id && (!$this->post['nama'] || in_array($this->post['nama'], array_values($this->data['unitarr'])))) {
			$this->post['nama'] = $this->data['unitarr'][$this->post['id_unit']];
		}

		$record = array(
			'scope' => $this->post['scope'],
			'owner' => $this->post['owner'],
			'open_evaluasi' => (!$id ? 1 : $this->data['row']['open_evaluasi']),
			'nama' => $this->post['nama'],
			'id_unit' => $this->post['id_unit'],
			'tgl_mulai_efektif' => $this->post['tgl_mulai_efektif'],
			'tgl_akhir_efektif' => $this->post['tgl_akhir_efektif'],
			'id_parent_scorecard' => $this->post['id_parent_scorecard'],
			'id_tingkat_agregasi_risiko' => $this->post['id_tingkat_agregasi_risiko'],
			'id_nama_proses' => $this->post['id_nama_proses'],
			'is_aktif' => $this->post['is_aktif'],
			'navigasi' => (int)$this->post['navigasi'],
			'is_info' => (int)$this->post['is_info'],
			'is_kegiatan' => (int)$this->post['is_kegiatan'],
		);

		if (!$this->access_role['view_all']) {
			unset($record['owner']);
			unset($record['nama']);
			unset($record['id_unit']);
		}

		return $record;
	}

	protected function Rules()
	{
		$return = array(
			// "scope" => array(
			// 	'field' => 'scope',
			// 	'label' => 'Scope',
			// 	'rules' => "required|max_length[4000]",
			// ),
			"nama" => array(
				'field' => 'nama',
				'label' => 'Nama Risk Register',
				'rules' => "required|max_length[300]",
			),
			// "owner" => array(
			// 	'field' => 'owner',
			// 	'label' => 'Owner',
			// 	'rules' => "required|callback_inlistjabatan",
			// ),
			"id_unit" => array(
				'field' => 'id_unit',
				'label' => 'Unit',
				'rules' => "required|in_list[" . implode(",", array_keys($this->data['unitarr'])) . "]"
			),
			"tgl_mulai_efektif" => array(
				'field' => 'tgl_mulai_efektif',
				'label' => 'Tgl. Mulai Efektif',
				'rules' => "required",
			),
			"id_nama_proses" => array(
				'field' => 'id_nama_proses',
				'label' => 'Nama Proses',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtpbnamaprosesarr'])) . "]"
			),
		);

		if ($this->data['row']['navigasi']) {
			unset($return['owner']);
			unset($return['id_unit']);
			unset($return['scope']);
		}


		if (!$this->access_role['view_all']) {
			unset($return['owner']);
			unset($return['nama']);
			unset($return['id_unit']);
			unset($return['tgl_mulai_efektif']);
		}

		return $return;
	}

	public function inlistjabatan($str)
	{
		if (!$str)
			return true;

		$result = $this->mjabatan->GetCombo($str);

		if (!$result[$str]) {
			$this->form_validation->set_message('inlistjabatan', 'Jabatan tidak ditemukan');
			return FALSE;
		}

		return true;
	}
	public function Add($id_parent_scorecard = null)
	{
		$this->Edit($id_parent_scorecard);
	}

	public function Edit($id_parent_scorecard = null, $id = null)
	{

		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		if ($id) {
			unset($this->access_role['add']);
		}

		$this->_beforeDetail($id_parent_scorecard, $id);

		$this->data['row'] = $this->model->GetByPk($id);

		if (isset($this->post['id_parent_scorecard']))
			$this->data['row']['id_parent_scorecard'] = $id_parent_scorecard = (int)$this->post['id_parent_scorecard'];

		if ($this->data['row']['id_parent_scorecard'] <> $id_parent_scorecard && $this->data['row'])
			redirect("panelbackend/risk_scorecard/edit/" . $this->data['row']['id_parent_scorecard'] . '/' . $id);

		if ($this->data['row']['id_parent_scorecard'])
			$id_parent_scorecard = $this->data['row']['id_parent_scorecard'];

		if (($this->data['row']['id_jabatan'])) {

			$id_mtsdmjabatanarr = $this->data['row']['id_jabatan'];
			$id_jabatanstr = "'" . implode("','", $id_mtsdmjabatanarr) . "'";

			$mtsdmjabatanarr = $this->conn->GetArray("select * from mt_sdm_jabatan where deleted_date is null and id_jabatan in ($id_jabatanstr)");
			foreach ($mtsdmjabatanarr as $r) {
				$this->data['mtsdmjabatanarr'][$r['id_jabatan']] = $r['nama'];
			}
		}

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

			$rekom_nama = "";
			if ($this->data['row']['id_nama_proses']) {
				$kode = $this->conn->GetOne("select ifnull(a.kode,'')
					from mt_pb_nama_proses a 
					where a.deleted_date is null and a.id_nama_proses = " . $this->conn->escape($this->data['row']['id_nama_proses']));
				$rekom_nama = $kode . ' ' . $this->data['mtpbnamaprosesarr'][$this->data['row']['id_nama_proses']];
			}

			if ($this->data['row']['nama'] <> $rekom_nama && $rekom_nama)
				$this->data['row']['nama'] = $rekom_nama;
		}

		$record['id_parent_scorecard'] = $this->data['row']['id_parent_scorecard'] = $id_parent_scorecard;

		if (!$record['id_parent_scorecard'])
			$record['id_parent_scorecard'] = "{{null}}";
		// unset($record['id_parent_scorecard']);

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {

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

				$return = $this->_beforeInsert($record);

				// if ($return) {
				// 	if (1 /*!$_SESSION[SESSION_APP]['tgl_efektif']*/)
				// 		$_SESSION[SESSION_APP]['tgl_efektif'] = date('Y-m-d');

				// 	$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

				// 	$record['id_visi_misi'] = $this->conn->GetOne("select id_visi_misi from risk_visi_misi where str_to_date('$tgl_efektif','%Y-%m-%d') between ifnull(tgl_mulai_efektif,str_to_date('$tgl_efektif','%Y-%m-%d'))and ifnull(tgl_akhir_efektif,str_to_date('$tgl_efektif','%Y-%m-%d'))");

				// 	if (!$record['id_visi_misi']) {
				// 		$this->data['err_msg'] = "Visi misi pada tanggal efektif belum di isi. ";
				// 		$return = false;
				// 	} else
				// 		$return = true;
				// }

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

				if (!$this->post['navigasi']) {
					$this->ctrl = 'opp_peluang';
					SetFlash('suc_msg', $return['success']);
					redirect("panelbackend/opp_peluang/index/$id");
				} else {
					if ($id_parent_scorecard && !$this->data['row'][$this->pk]) {
						SetFlash('suc_msg', $return['success']);
						redirect("$this->page_ctrl/index/$id_parent_scorecard");
					} else {
						SetFlash('suc_msg', $return['success']);
						redirect("$this->page_ctrl/index/$id");
					}
				}
			} else {
				$this->data['row'] = array_merge($this->data['row'], $record);
				$this->data['row'] = array_merge($this->data['row'], $this->post);

				$this->_afterEditFailed($id);

				$this->data['err_msg'] .= "Data gagal disimpan";
			}
		}

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	protected function _beforeDelete($id_parent_scorecard = null, $id = null)
	{

		if (!$this->access_role['delete'])
			return false;

		$cek = $this->conn->GetOne("select 1 from opp_peluang where deleted_date is null and status_peluang <> '2' and id_scorecard = " . $this->conn->escape($id));

		if ($cek) {
			$this->ctrl = 'opp_peluang';
			SetFlash('err_msg', "Data tidak bisa dihapus karena masih ada risiko yang belum dihapus");
			redirect("$this->page_ctrl/detail/$id");
			die();
		}

		$cek = $this->conn->GetListStr("select 
			case when tgl_akhir_efektif is not null then 
			nama ||' yang 
			efektif '||tgl_mulai_efektif||' sampai '||tgl_akhir_efektif
			else
			nama end as val 
			from opp_scorecard where deleted_date is null and id_parent_scorecard = " . $this->conn->escape($id));

		if ($cek) {
			$this->ctrl = 'opp_peluang';
			SetFlash('err_msg', "Data tidak bisa dihapus karena masih ada scorecard " . $cek . " dibawahnya, silahkan hapus terlebih dahulu");
			redirect("$this->page_ctrl/detail/$id");
			die();
		}

		$id_scorecard = $id;

		$ret = $this->conn->Execute("update opp_log set deleted_date = now() where id_scorecard=" . $this->conn->escape($id));
		if ($ret) {
			$rowsrisiko = $this->conn->GetArray("select id_peluang from opp_peluang where deleted_date is null and status_peluang = '2' and id_scorecard = " . $this->conn->escape($id));

			foreach ($rowsrisiko as $ror) {
				if (!$ret)
					return $ret;

				$id = $ror['id_peluang'];

				$ret = $this->conn->Execute("update opp_peluang set deleted_date = now() where id_peluang = " . $this->conn->escape($id));
			}
		}

		if ($ret)
			$ret = $this->conn->Execute("update opp_scorecard_view set deleted_date = now() where id_scorecard = " . $this->conn->escape($id_scorecard));

		// $full_path = $this->data['configfile']['upload_path'] . "scorecard_proses" . $id . '.' . ext($row['proses']);
		// @unlink($full_path);

		// $full_path = $this->data['configfile']['upload_path'] . "scorecard_template_laporan" . $id . '.' . ext($row['template_laporan']);
		// @unlink($full_path);

		return $ret;
	}

	public function Delete($id = null)
	{

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
			redirect("$this->page_ctrl/index/" . $this->data['row']['id_parent_scorecard']);
		} else {
			$this->ctrl = 'opp_peluang';
			SetFlash('err_msg', "Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id");
		}
	}

	function _uploadFile($id = null)
	{
		if (!$_FILES['template_laporan']['name'])
			return true;

		$return = array('success' => true);

		if ($_FILES['template_laporan']['name']) {

			$this->data['configfile']['file_name'] = "scorecard_template_laporan" . $id;
			$this->data['configfile']['allowed_types'] = "doc|docx";
			$this->load->library('upload', $this->data['configfile']);
			$this->upload->overwrite = true;

			if (!$this->upload->do_upload('template_laporan')) {
				$return = array('error' => $this->upload->display_errors());
			} else {
				$upload_data = $this->upload->data();
				$return = array('success' => "Upload " . $upload_data['client_name'] . " berhasil");

				$record = array();
				$ret = $this->conn->Execute("update opp_scorecard set template_laporan = " . $this->conn->escape($upload_data['client_name']) . " where id_scorecard = " . $this->conn->escape($id));

				if (!$ret) {
					@unlink($upload_data['full_path']);
					$return["success"] = false;
					$return["error"] = "Upload berhasil";
				}
			}
		}

		if ($return['success']) {

			SetFlash('suc_msg', $return['success']);

			$this->post['act'] = 'save';

			return true;
		} else {
			SetFlash('err_msg', $return['error']);
			redirect(current_url());
		}
	}

	function preview_file($id, $is_pdf = false)
	{
		$row = $this->model->GetByPk($id);

		if ($is_pdf) {
			if (!$row['proses'])
				die();

			$full_path = $this->data['configfile']['upload_path'] . "scorecard_proses" . $id . '.' . ext($row['proses']);
			header("Content-Type: application/pdf");
			header("Content-Disposition: inline; filename='{$row['proses']}'");
			echo file_get_contents($full_path);

			die();
		}
		if (!$row['template_laporan'])
			$this->Error404();

		$full_path = $this->data['configfile']['upload_path'] . "scorecard_template_laporan" . $id . '.' . ext($row['template_laporan']);
		header("Content-Type: application/msword");
		header("Content-Disposition: inline; filename='{$row['template_laporan']}'");
		echo file_get_contents($full_path);
		die();
	}


	public function Detail($id_parent_scorecard = null, $id = null)
	{

		$this->_beforeDetail($id_parent_scorecard, $id);

		$this->data['row'] = $this->model->GetByPk($id);

		if ($this->data['row']['owner'])
			redirect('/panelbackend/opp_peluang/index/' . $id);
		else if ($this->data['row']['navigasi'])
			redirect('/panelbackend/opp_scorecard/index/' . $id);

		if (!$this->data['row'])
			$this->NoData();

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	protected function _afterDetail($id)
	{
		$this->data['editedheader'] = $this->data['edited'];

		$this->data['rowheader'] = $this->data['row'];

		if ($this->data['rowheader']['id_unit']) {
			$this->data['ownerarr'] = $this->conn->GetList("select 
				id_jabatan as idkey, 
				concat(nama,' (',ifnull(id_unit,''),')') as val
				from mt_sdm_jabatan a
				where a.deleted_date is null and /*exists (select 1 from public_sys_user b where a.id_jabatan = b.id_jabatan and b.group_id = 24 and b.deleted_date is null) 
				and*/ id_unit = " . $this->conn->escape($this->data['rowheader']['id_unit']));

			if (!$this->data['rowheader']['owner']) {
				$this->data['rowheader']['owner'] = array_keys($this->data['ownerarr'])[0];
			}

			if ($this->data['rowheader']['owner']) {

				$bawahanarr = jabatan_bawahan($this->data['rowheader']['owner'], $this->data['rowheader']['id_unit']);

				$addfilter = "";

				if (count($bawahanarr) <= 100 && !empty($bawahanarr)) {
					$addfilter = " and id_jabatan in (" . implode(", ", $bawahanarr) . ")";
				}

				$this->data['userarr'] = $this->conn->GetList("select 
				id_jabatan as idkey, 
				concat(nama,' (',ifnull(id_unit,''),')') as val
				from mt_sdm_jabatan a
				where a.deleted_date is null and exists (select 1 from public_sys_user b where a.id_jabatan = b.id_jabatan and b.group_id = 2 and b.deleted_date is null) 
				and id_unit = " . $this->conn->escape($this->data['rowheader']['id_unit']) . $addfilter);
			}
		}

		if ($this->data['rowheader']['owner'])
			$owner = $this->data['rowheader']['owner'];

		if ($owner)
			$this->data['ownerarr'][$owner] = $this->conn->GetOne("select concat(nama,' (',ifnull(id_unit,''),')') from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($owner));

		if (!($this->data['row']['id_jabatan'])) {
			$id_jabatanarr = array();

			$mtsdmjabatanarr = $this->conn->GetArray("select id_jabatan from opp_scorecard_view where deleted_date is null and id_scorecard = " . $this->conn->escape($id));

			foreach ($mtsdmjabatanarr as $idkey => $value) {
				$id_jabatanarr[] = $value['id_jabatan'];
			}

			$this->data['row']['id_jabatan'] = $id_jabatanarr;
		}

		if (($this->data['row']['id_jabatan'])) {

			$id_mtsdmjabatanarr = $this->data['row']['id_jabatan'];
			$id_jabatanstr = "'" . implode("','", $id_mtsdmjabatanarr) . "'";

			$mtsdmjabatanarr = $this->conn->GetArray("select * from mt_sdm_jabatan where deleted_date is null and id_jabatan in ($id_jabatanstr)");
			foreach ($mtsdmjabatanarr as $r) {
				$this->data['mtsdmjabatanarr'][$r['id_jabatan']] = $r['nama'] . ' (' . $r['id_unit'] . ')';
			}
		}

		if (!($this->data['row']['user'])) {
			$userarr = array();

			$mtsdmjabatanarr = $this->conn->GetArray("select id_jabatan from opp_scorecard_user where deleted_date is null and id_scorecard = " . $this->conn->escape($id));

			foreach ($mtsdmjabatanarr as $idkey => $value) {
				$userarr[] = $value['id_jabatan'];
			}

			$this->data['row']['user'] = $userarr;
		}

		if (($this->data['row']['user'])) {

			$id_mtsdmjabatanarr = $this->data['row']['user'];
			$userstr = "'" . implode("','", $id_mtsdmjabatanarr) . "'";

			$mtsdmjabatanarr = $this->conn->GetArray("select * from mt_sdm_jabatan where deleted_date is null and user in ($userstr)");
			foreach ($mtsdmjabatanarr as $r) {
				$this->data['userarr'][$r['id_jabatan']] = $r['nama'] . ' (' . $r['id_unit'] . ')';
			}
		}
	}

	protected function _beforeInsert($record = array())
	{
		$this->riskchangelog($record);

		return true;
	}

	protected function _beforeUpdate($record = array(), $id = null)
	{

		$row = $this->model->GetByPk($id);

		return $this->_beforeInsert($record);
	}

	protected function _beforeDetail($id_parent_scorecard = null, $id1 = null)
	{
		$this->data['scorecardarr'] = $this->model->GetCombo(null, null, null, null);

		$broadcrum = $this->model->GetComboParent($id_parent_scorecard);
		$this->data['broadcrum'] = $broadcrum;

		if ($id_parent_scorecard) {
			$this->data['page_title'] = str_replace("Lingkup", "Sub Lingkup", $this->data['page_title']);
		}

		$this->data['add_param'] .= $id_parent_scorecard;
	}

	protected function _afterUpdate($id)
	{
		$ret = $this->_delSertView($id);

		if ($ret)
			$ret = $this->_delSertUser($id);

		if ($ret)
			$ret = $this->_uploadFile($id);

		return $ret;
	}

	protected function _afterInsert($id)
	{
		$ret = $this->_afterUpdate($id);

		return $ret;
	}

	private function _delSertView($id)
	{
		$return = $this->conn->Execute("update opp_scorecard_view set deleted_date = now() where id_scorecard = " . $this->conn->escape($id));

		if (is_array($this->post['id_jabatan'])) {
			foreach ($this->post['id_jabatan'] as $idkey => $value) {
				if ($return) {
					if (!$value)
						continue;

					$record = array();
					$record['id_scorecard'] = $id;
					$record['id_jabatan'] = $value;

					$sql = $this->conn->InsertSQL("opp_scorecard_view", $record);

					if ($sql) {
						$return = $this->conn->Execute($sql);
					}
				}
			}
		}

		return $return;
	}

	private function _delSertUser($id)
	{
		$return = $this->conn->Execute("update opp_scorecard_user set deleted_date = now() where id_scorecard = " . $this->conn->escape($id));

		if (is_array($this->post['user'])) {
			foreach ($this->post['user'] as $idkey => $value) {
				if ($return) {
					if (!$value)
						continue;

					$record = array();
					$record['id_scorecard'] = $id;
					$record['id_jabatan'] = $value;

					$sql = $this->conn->InsertSQL("opp_scorecard_user", $record);

					if ($sql) {
						$return = $this->conn->Execute($sql);
					}
				}
			}
		}

		return $return;
	}
}
