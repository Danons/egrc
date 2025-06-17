	<?php
	defined('BASEPATH') or exit('No direct script access allowed');

	include APPPATH . "core/_adminController.php";
	class Risk_scorecard extends _adminController
	{
		public function __construct()
		{
			parent::__construct();
		}

		protected function init()
		{
			parent::init();
			$this->viewlist = "panelbackend/risk_scorecardlist";
			$this->viewdetail = "panelbackend/risk_scorecarddetail";
			$this->template = "panelbackend/main";
			$this->layout = "panelbackend/layout1";

			if ($this->mode == 'add') {
				$this->data['page_title'] = 'Tambah IRR';
				$this->data['edited'] = true;
			} elseif ($this->mode == 'edit') {
				$this->data['page_title'] = 'Ubah IRR';
				$this->data['edited'] = true;
			} elseif ($this->mode == 'detail') {
				$this->data['page_title'] = 'Integrated Risk Register (IRR)';
				$this->data['edited'] = false;
			} else {
				$this->mode = 'index';
				$this->data['mode'] = 'index';
				$this->data['page_title'] = 'Integrated Risk Register (IRR)';
			}

			$this->load->model("Risk_scorecardModel", "model");
			$this->data['configfile'] = $this->config->item('file_upload_config');
			$this->load->model("Risk_scorecard_filesModel", "modelfile");

			$this->load->model("Mt_sdm_unitModel", "munit");
			$this->data['unitarr'] = $this->munit->GetCombo();

			$this->load->model("Risk_konteksModel", "konteks");
			$this->data['konteksarr'] = $this->konteks->GetCombo();

			$this->load->model("Mt_pb_nama_prosesModel", "mtpbnamaproses");
			$this->data['mtpbnamaprosesarr'] = $this->mtpbnamaproses->GetCombo();

			$this->load->model("Mt_tingkat_agregasi_risikoModel", "tingkatagregasi");
			$this->data['agregasiarr'] = ['' => ''] + $this->tingkatagregasi->GetCombo();

			$this->load->model("Risk_sasaranModel", 'risksasaran');
			// $this->data['risksasaranarr'] = $this->risksasaran->GetCombo();

			$this->load->model("Risk_risikoModel", "riskrisiko");
			/*
		$this->load->model("Mt_sdm_jabatanModel","mtsdmjabatan");
		$this->data['mtsdmjabatanarr'] = $this->mtsdmjabatan->GetCombo();*/


			$this->pk = $this->model->pk;
			$this->data['pk'] = $this->pk;
			$this->plugin_arr = array(
				'datepicker',
				'treetable',
				'select2'
			);
			// $this->access_role['list_print'] = true;

			// dpr($this->access_role, 1);
		}

		public function Index($id_parent_scorecard = 0)
		{

			$this->_beforeDetail($id_parent_scorecard);
			// if (1 /*!$_SESSION[SESSION_APP]['tgl_efektif']*/)
			// 	$_SESSION[SESSION_APP]['tgl_efektif'] = date('Y-m-d');

			// $this->data['tgl_efektif'] = $tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

			if (!$_SESSION[SESSION_APP]['filter_tgl_efektif'])
				$_SESSION[SESSION_APP]['filter_tgl_efektif'] = date('Y-m-d');

			if ($this->post['act'] == 'filter') {
				$_SESSION[SESSION_APP]['filter_tgl_efektif'] = $this->post['tgl_efektif'];
				redirect(current_url());
				die();
			}

			$this->data['tgl_efektif'] = $tgl_efektif = $_SESSION[SESSION_APP]['filter_tgl_efektif'];

			// $this->conn->debug = 1;
			$this->data['rows'] = $this->model->GetList($id_parent_scorecard, $tgl_efektif);
			// $this->conn->debug = 1;
			// dpr($this->data['rows'], 1);

			if ($id_parent_scorecard) {
				unset($this->data['page_title']);
				if ($this->access_role['view_all']) {
					$this->data['mode'] = 'add|edit|delete|print';
					$this->data['row'] = $this->model->GetByPk($id_parent_scorecard);
				}
			}

			$this->data['id_parent_scorecard'] = $id_parent_scorecard;

			$this->View($this->viewlist);
		}

		protected function Record($id = null)
		{

			// if (!$id && (!$this->post['nama'] || in_array($this->post['nama'], array_values($this->data['unitarr'])))) {
			// 	$this->post['nama'] = $this->data['unitarr'][$this->post['id_unit']];
			// }

			if ($this->post['navigasi']) {
				$record = array(
					'navigasi' => (int)$this->post['navigasi'],
					'nama' => $this->post['nama'],
					'tgl_mulai_efektif' => $this->post['tgl_mulai_efektif'],
					'tgl_akhir_efektif' => $this->post['tgl_akhir_efektif'],
					'id_parent_scorecard' => $this->post['id_parent_scorecard'] ? $this->post['id_parent_scorecard'] : "{{null}}",

					'scope' => "{{null}}",
					'owner' => "{{null}}",
					'open_evaluasi' => "{{null}}",
					'id_unit' => "{{null}}",
					'id_konteks' => "{{null}}",
					'id_tingkat_agregasi_risiko' => "{{null}}",
					'id_nama_proses' => "{{null}}",
					'is_aktif' => "{{null}}",
					'is_info' => "{{null}}",
					'is_kegiatan' => "{{null}}",
					'rutin_non_rutin' => "{{null}}",
					'jenis_proyek' => "{{null}}",
					'id_sasaran_proyek' => "{{null}}",
					'biaya_proyek' => "{{null}}",
					'tgl_mulai' => "{{null}}",
					'tgl_selesai' => "{{null}}",
				);
			} else {
				$record = array(
					'scope' => $this->post['scope'],
					'owner' => $this->post['owner'],
					'open_evaluasi' => (!$id ? 1 : $this->data['row']['open_evaluasi']),
					'nama' => $this->post['nama'],
					'id_unit' => $this->post['id_unit'],
					'id_konteks' => $this->post['id_konteks'],
					'tgl_mulai_efektif' => $this->post['tgl_mulai_efektif'],
					'tgl_akhir_efektif' => $this->post['tgl_akhir_efektif'],
					'id_parent_scorecard' => $this->post['id_parent_scorecard'] ? $this->post['id_parent_scorecard'] : "{{null}}",
					'id_tingkat_agregasi_risiko' => $this->post['id_tingkat_agregasi_risiko'],
					'id_nama_proses' => $this->post['id_nama_proses'],
					'is_aktif' => $this->post['is_aktif'],
					'navigasi' => (int)$this->post['navigasi'],
					'is_info' => (int)$this->post['is_info'],
					'is_kegiatan' => (int)$this->post['is_kegiatan'],


					'rutin_non_rutin' => $this->post['rutin_non_rutin'],
					'jenis_proyek' => $this->post['jenis_proyek'],
					'id_sasaran_proyek' => $this->post['id_sasaran_proyek'],
					'biaya_proyek' => Rupiah2Number($this->post['biaya_proyek']),
					'tgl_mulai' => $this->post['tgl_mulai'],
					'tgl_selesai' => $this->post['tgl_selesai'],
				);
			}

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
				"owner" => array(
					'field' => 'owner',
					'label' => 'Owner',
					'rules' => "required|callback_inlistjabatan",
				),
				"id_unit" => array(
					'field' => 'id_unit',
					'label' => 'Unit',
					'rules' => "required|in_list[" . implode(",", array_keys($this->data['unitarr'])) . "]"
				),
				// "id_konteks" => array(
				// 	'field' => 'id_konteks',
				// 	'label' => 'Unit',
				// 	'rules' => "required|in_list[" . implode(",", array_keys($this->data['konteksarr'])) . "]"
				// ),
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
				unset($return['id_konteks']);
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

			if ($this->data['row']['id_parent_scorecard'] && $id_parent_scorecard && $this->data['row']['id_parent_scorecard'] <> $id_parent_scorecard && $this->data['row'])
				redirect("panelbackend/risk_scorecard/edit/" . $this->data['row']['id_parent_scorecard'] . '/' . $id);

			// if ($id_parent_scorecard == $id && $this->data['row'])
			// redirect("panelbackend/risk_scorecard/edit/0/" . $id);

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
					$kode = $this->conn->GetOne("select coalesce(a.kode,'')
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

			// dpr($this->post);
			// dpr($record,1);
			$this->data['rules'] = $this->Rules();

			## EDIT HERE ##
			if ($this->post['act'] === 'save') {

				// if (is_numeric($record['id_sasaran_proyek'])) {
				// 	$id_sasaran = $this->conn->GetOne("select id_sasaran from risk_sasaran where id_sasaran = " . $this->conn->escape($this->post['id_sasaran_proyek']));
				// }
				// if (!$id_sasaran) {
				// 	$rec = array(
				// 		'nama' => $record['id_sasaran_proyek'],
				// 	);
				// 	$red = $this->risksasaran->Insert($rec);
				// 	if ($red['success']) {
				// 		$record['id_sasaran_proyek'] = $this->data['row']['id_sasaran_proyek'] = $red['data']['id_sasaran'];
				// 	}
				// }

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

					// 	$record['id_konteks'] = $this->conn->GetOne("select id_konteks from risk_konteks where str_to_date('$tgl_efektif','%Y-%m-%d')  between coalesce(tgl_mulai_efektif,str_to_date('$tgl_efektif','%Y-%m-%d') )and coalesce(tgl_akhir_efektif,str_to_date('$tgl_efektif','%Y-%m-%d') )");

					// 	if (!$record['id_konteks']) {
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
						$this->ctrl = 'risk_risiko';
						SetFlash('suc_msg', $return['success']);
						redirect("panelbackend/risk_risiko/index/$id");
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

		protected function _beforeDelete($id = null)
		{
			if (!$this->access_role['delete'])
				return false;

			$id_scorecard = $id;
			// $cek = $this->conn->GetOne("select 1 from risk_risiko where status_risiko <> '2' and id_scorecard = " . $this->conn->escape($id));

			// if ($cek) {
			// 	$this->ctrl = 'risk_risiko';
			// 	SetFlash('err_msg', "Data tidak bisa dihapus karena masih ada risiko yang belum dihapus");
			// 	redirect("panelbackend/risk_risiko/index/$id");
			// 	die();
			// }

			// $cek = $this->conn->GetListStr("select 
			// 	case when tgl_akhir_efektif is not null then 
			// 	concat(nama ,' yang 
			// 	efektif ',tgl_mulai_efektif,' sampai ',tgl_akhir_efektif)
			// 	else
			// 	nama end as val 
			// 	from risk_scorecard where id_parent_scorecard = " . $this->conn->escape($id));

			// if ($cek) {
			// 	$this->ctrl = 'risk_risiko';
			// 	SetFlash('err_msg', "Data tidak bisa dihapus karena masih ada scorecard " . $cek . " dibawahnya, silahkan hapus terlebih dahulu");
			// 	redirect("panelbackend/risk_risiko/index/$id");
			// 	die();
			// }

			$id_scorecard = $id;

			$ret = $this->conn->Execute("update risk_log set deleted_date = now() where id_scorecard=" . $this->conn->escape($id));
			if ($ret) {
				$rowsrisiko = $this->conn->GetArray("select id_risiko from risk_risiko where deleted_date is null and status_risiko = '2' and id_scorecard = " . $this->conn->escape($id));

				foreach ($rowsrisiko as $ror) {
					if (!$ret)
						return $ret;

					$id = $ror['id_risiko'];

					$this->conn->Execute("update risk_risiko_history set deleted_date = now() where id_risiko = " . $this->conn->escape($id));


					$this->conn->Execute("update risk_review set deleted_date = now() where id_risiko = " . $this->conn->escape($id));

					$rows = $this->conn->GetRows("select id_mitigasi from risk_mitigasi  where deleted_date is null and id_risiko = " . $this->conn->escape($id));
					foreach ($rows as $r) {
						$this->conn->Execute("update risk_mitigasi_files set deleted_date = now() where id_mitigasi = " . $this->conn->escape($r['id_mitigasi']));
					}

					$this->conn->Execute("update risk_mitigasi set deleted_date = now() where id_risiko = " . $this->conn->escape($id));

					$rows = $this->conn->GetRows("select id_control from risk_control where deleted_date is null and id_risiko = " . $this->conn->escape($id));
					foreach ($rows as $r) {
						$this->conn->Execute("update risk_control_efektifitas_files set deleted_date = now() where id_control = " . $this->conn->escape($r['id_control']));
						$this->conn->Execute("update risk_control_efektifitas set deleted_date = now() where id_control = " . $this->conn->escape($r['id_control']));
					}

					$this->conn->Execute("update risk_control set deleted_date = now() where id_risiko = " . $this->conn->escape($id));
					$this->conn->Execute("update risk_log set deleted_date = now() where id_risiko = " . $this->conn->escape($id));
					$ret = $this->conn->Execute("update risk_risiko set deleted_date = now() where id_risiko = " . $this->conn->escape($id));
				}
			}

			if ($ret)
				$ret = $this->conn->Execute("update risk_scorecard_view set deleted_date = now() where id_scorecard = " . $this->conn->escape($id_scorecard));

			if ($ret)
				$ret = $this->conn->Execute("update risk_task set deleted_date = now() where id_scorecard = " . $this->conn->escape($id_scorecard));

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
				$this->ctrl = 'risk_risiko';
				SetFlash('err_msg', "Data gagal didelete");
				redirect("panelbackend/risk_risiko/index/$id");
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
					$ret = $this->conn->Execute("update risk_scorecard set template_laporan = " . $this->conn->escape($upload_data['client_name']) . " where id_scorecard = " . $this->conn->escape($id));

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
				redirect('/panelbackend/risk_risiko/index/' . $id);
			else if ($this->data['row']['navigasi'])
				redirect('/panelbackend/risk_scorecard/index/' . $id);

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
				concat(nama,' (',coalesce(id_unit,''),')') as val
				from mt_sdm_jabatan a
				where a.deleted_date is null and/*exists (select 1 from public_sys_user_group b where a.id_jabatan = b.id_jabatan and b.group_id = 24) 
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
				concat(nama,' (',coalesce(id_unit,''),')') as val
				from mt_sdm_jabatan a
				where a.deleted_date is null and exists (select 1 from public_sys_user_group b where a.id_jabatan = b.id_jabatan and b.group_id = 2) 
				and id_unit = " . $this->conn->escape($this->data['rowheader']['id_unit']) . $addfilter);
				}
			}

			if ($this->data['rowheader']['owner'])
				$owner = $this->data['rowheader']['owner'];

			if ($owner)
				$this->data['ownerarr'][$owner] = $this->conn->GetOne("select 
			concat(nama,' (',coalesce(id_unit,''),')') as val from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($owner));

			if (!($this->data['row']['id_jabatan'])) {
				$id_jabatanarr = array();

				$mtsdmjabatanarr = $this->conn->GetArray("select id_jabatan from risk_scorecard_view where deleted_date is null and id_scorecard = " . $this->conn->escape($id));

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

				$mtsdmjabatanarr = $this->conn->GetArray("select id_jabatan from risk_scorecard_user where deleted_date is null and id_scorecard = " . $this->conn->escape($id));

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

			if (is_numeric($this->data['row']['id_sasaran_proyek']))
				$this->data['risksasaranarr'] = $this->conn->Getlist("select id_sasaran idkey, nama val from risk_sasaran where deleted_date is null and id_sasaran = " . $this->conn->escape($this->data['row']['id_sasaran_proyek']));
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

			$this->data['add_param'] .= (int)$id_parent_scorecard;
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
			$return = $this->conn->Execute("update risk_scorecard_view set deleted_date = now() where id_scorecard = " . $this->conn->escape($id));

			if (is_array($this->post['id_jabatan'])) {
				foreach ($this->post['id_jabatan'] as $idkey => $value) {
					if ($return) {
						if (!$value)
							continue;

						$record = array();
						$record['id_scorecard'] = $id;
						$record['id_jabatan'] = $value;

						$sql = $this->conn->InsertSQL("risk_scorecard_view", $record);

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
			$return = $this->conn->Execute("update risk_scorecard_user set deleted_date = now() where id_scorecard = " . $this->conn->escape($id));

			if (is_array($this->post['user'])) {
				foreach ($this->post['user'] as $idkey => $value) {
					if ($return) {
						if (!$value)
							continue;

						$record = array();
						$record['id_scorecard'] = $id;
						$record['id_jabatan'] = $value;

						$sql = $this->conn->InsertSQL("risk_scorecard_user", $record);

						if ($sql) {
							$return = $this->conn->Execute($sql);
						}
					}
				}
			}

			return $return;
		}

		public function go_print($id_scorecard = null)
		{
			$id_unitarr = [];
			$unit = $this->conn->GetOne("select id_unit from risk_scorecard where deleted_date is null and id_scorecard = " . $this->conn->escape($id_scorecard));

			$this->riskrisiko->_getChildUnit($unit, $id_unitarr);
			// dpr($id_unitarr,1);

			if ($id_unitarr) {
				foreach ($id_unitarr as $g) {
					$id_un[] = "'" . $g . "'";
				}
			}
			if ($id_un)
				$id_scorecard = $this->conn->GetList("select id_scorecard idkey, id_scorecard val from risk_scorecard where deleted_date is null and id_unit in (" . implode(",", $id_un) . ")");

			foreach ($id_scorecard as $f) {
				$id_scor[] = "&id_scorecard%5B$f%5D=$f";
			}
			$id_scorecard_get = implode("", $id_scor);


			$url = base_url("panelbackend/laporan_kertas_kerja/go_print/?act=list_search&go=&idkey=&cari_risiko=&id_unit=&id_kpi=$id_scorecard_get&header%5Bsasaran%5D=sasaran&header%5Bis_rutin%5D=is_rutin&header%5Bpenyebab%5D=penyebab&header%5Btaksonomi_area_kode%5D=taksonomi_area_kode&header%5Bdampak%5D=dampak&header%5Bpemenuhan_kewajiban%5D=pemenuhan_kewajiban&header%5Bid_aspek_lingkungan%5D=id_aspek_lingkungan&header%5Binheren_risk%5D=inheren_risk&header%5Bis_opp_inherent%5D=is_opp_inherent&header%5Binheren_kemungkinan%5D=inheren_kemungkinan&header%5Binheren_dampak%5D=inheren_dampak&header%5Blevel_risiko_inheren%5D=level_risiko_inheren&header%5Bis_signifikan_inherent%5D=is_signifikan_inherent&header%5Bnama_kontrol%5D=nama_kontrol&header%5Bcurrent_risk%5D=current_risk&header%5Bis_opp_inherent1%5D=is_opp_inherent1&header%5Bcontrol_kemungkinan_penurunan%5D=control_kemungkinan_penurunan&header%5Bcontrol_dampak_penurunan%5D=control_dampak_penurunan&header%5Blevel_risiko_paskakontrol%5D=level_risiko_paskakontrol&header%5Bis_signifikan_current%5D=is_signifikan_current&header%5Bmitigasi_lanjutan%5D=mitigasi_lanjutan&header%5Bresidual_risk%5D=residual_risk&header%5Bis_opp_inherent2%5D=is_opp_inherent2&header%5Bkemungkinan_rdual%5D=kemungkinan_actual&header%5Bdampak_rdual%5D=dampak_actual&header%5Blevel_risiko_residual%5D=level_risiko_actual&jenis=0&tingkat=&namatempletekolom=&idtempletekolom=&judultempletekolom=&is_ttd=0");
			if ($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']['is_signifikan'])
				$url = base_url("panelbackend/laporan_kertas_kerja/go_print/?act=list_search&go=&idkey=&cari_risiko=&id_unit=&id_kpi=$id_scorecard_get&header%5Bscorecard%5D=scorecard&header%5Bsasaran%5D=sasaran&header%5Bpenyebab%5D=penyebab&header%5Bdampak%5D=dampak&header%5Blevel_risiko_inheren%5D=level_risiko_inheren&header%5Bnomor_mitigasi_lanjutan%5D=nomor_mitigasi_lanjutan&header%5Bmitigasi_lanjutan%5D=mitigasi_lanjutan&header%5Bprioritas_risiko%5D=prioritas_risiko&header%5Bintegrasi_internal%5D=integrasi_internal&header%5Bintegrasi_eksternal%5D=integrasi_eksternal&jenis=is_signifikan&tingkat=&namatempletekolom=&idtempletekolom=&judultempletekolom=&is_ttd=0");
			redirect($url);
		}
	}
