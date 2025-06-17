<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Kpi_target_realisasi extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/kpi_target_realisasilist";
		$this->viewdetail = "panelbackend/kpi_target_realisasidetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_kpi";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah KPI Realisasi';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit KPI Realisasi';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail KPI Realisasi';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Realisasi';
		}

		$this->load->model("Kpi_target_realisasiModel", "model");

		$this->load->model("Kpi_targetModel", "kpitarget");


		$this->data['configfile'] = $this->config->item('file_upload_config');
		// $this->data['configfile']['allowed_types'] = 'pdf';
		// $this->config->set_item("file_upload_config", $this->data['configfile']);

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'tinymce', 'upload', 'select2'
		);
	}

	protected function Record($id = null)
	{
		return array(
			'bulan' => $this->post['bulan'],
			'nilai' => $this->post['nilai'],
			'prosentase' => $this->post['prosentase'],
		);
	}

	protected function Rules()
	{
		return array(
			"bulan" => array(
				'field' => 'bulan',
				'label' => 'Bulan',
				'rules' => "required|max_length[2]",
			),
			// "nilai" => array(
			// 	'field' => 'nilai',
			// 	'label' => 'Nilai',
			// 	'rules' => "required|numeric",
			// ),
		);
	}

	protected function _beforeDetail($id_kpi_target = null, $id_kpi_target_realisasi = null)
	{
		$this->load->model("Kpi_targetModel", 'kpi_target');
		$this->data['rowheader']  = $this->kpi_target->GetByPk($id_kpi_target);

		if (!$this->data['rowheader'])
			$this->NoData();

		$this->data['add_param'] .= $id_kpi_target;
	}

	public function Index($id_kpi_target = null, $page = 0)
	{
		$this->_beforeDetail($id_kpi_target);
		$this->_setFilter("id_kpi_target = " . $this->conn->qstr($id_kpi_target));
		$this->data['editedevaluasi'] = true;
		$this->data['editedanalisa'] = true;

		if ($this->data['editedevaluasi']) {
			if ($this->post['act'] == 'simpan_evaluasi') {
				// $ret = $this->conn->goUpdate("kpi_target", [
				// 	'evaluasi' => $this->post['evaluasi']
				// ], "id_kpi_target = " . $this->conn->escape($id_kpi_target));
				$ret = $this->conn->goUpdate("kpi_target", [
					'evaluasi' => $_POST['evaluasi']
				], "id_kpi_target = " . $this->conn->escape($id_kpi_target));

				# mengirim notivikasi
				// if ($ret) {
				// $data_not = $this->conn->GetArray("select * from public_sys_user_group where group_id in(4)");
				// $data_not = $this->conn->GetArray("
				// select 
				// /*distinct g.* 
				// */ g.*
				// from
				// 	public_sys_user_group g
				// 	left join public_sys_group a on g.group_id = g.group_id
				// 	left join public_sys_group_menu b on a.group_id = b.group_id
				// 	left join public_sys_menu c on b.menu_id = c.menu_id
				// 	left join public_sys_action d on d.menu_id = c.menu_id
				// where d.name = 'view_all' and a.group_id = 4 and c.menu_id != 941");
				// $nama = $this->conn->GetOne("select nama from kpi a where exists( select 1 from kpi_target b where a.id_kpi = b.id_kpi and id_kpi_target = " . $this->conn->escape($id_kpi_target) . ")");
				// foreach ($data_not as $r) {
				// 	$record2 = array(
				// 		'page' => 'unit',
				// 		'untuk' => $r['id_jabatan'],
				// 		'id_status_pengajuan' => 2,
				// 		'deskripsi' => "telah menambahkan realisasi " . $nama,
				// 		'url' => "panelbackend/kpi_target_realisasi/index/$id_kpi_target",
				// 	);

				// 	$re = $this->InsertTask($record2);
				// }

				// $data = $this->conn->GetArray("
				// 		select id_jabatan from public_sys_user_group i
				// 		where exists(
				// 				select 1
				// 				from
				// 					public_sys_group_action a
				// 					join public_sys_action b on a.action_id = b.action_id
				// 					join public_sys_group_menu c on a.group_menu_id = c.group_menu_id
				// 				where i.group_id = c.group_id and b.menu_id = 941 and b.name = 'add' and a.action_id != 2220)
				// 				and exists(select 1 from mt_sdm_jabatan b where b.id_jabatan = i.id_jabatan and b.id_unit = " . $this->conn->escape($this->data['rowheader']['id_unit']) . ") group by id_jabatan
				// 		");
				// foreach ($data as $d) {
				// 	$record2 = array(
				// 		'page' => 'analisa_evaluasi',
				// 		'untuk' => $d['id_jabatan'],
				// 		'id_status_pengajuan' => 8,
				// 		'deskripsi' => $_SESSION[SESSION_APP]['nama_group'] . " menambahkan analisa/evaluasi di " . $nama,
				// 		'url' => "panelbackend/kpi_target_realisasi/index/$id_kpi_target",
				// 	);
				// 	$re = $this->InsertTask($record2);
				// }
				// $record2 = array(
				// 	'page' => 'analisa_evaluasi',
				// 	'untuk' => 0,
				// 	'id_status_pengajuan' => 8,
				// 	'deskripsi' => $_SESSION[SESSION_APP]['nama_group'] . " menambahkan analisa/evaluasi di " . $nama,
				// 	'url' => "panelbackend/kpi_target_realisasi/index/$id_kpi_target",
				// );

				// $re = $this->InsertTask($record2);
				// }

				if ($ret)
					SetFlash("suc_msg", "Berhasil update");
				else
					SetFlash("err_msg", "Berhasil update");

				redirect(current_url());
			}
		}

		if ($this->data['editedanalisa']) {
			if ($this->post['act'] == 'simpan_analisa') {
				// $ret = $this->conn->goUpdate("kpi_target", [
				// 	'analisa' => $this->post['analisa']
				// ], "id_kpi_target = " . $this->conn->escape($id_kpi_target));
				$ret = $this->conn->goUpdate("kpi_target", [
					'analisa' => $_POST['analisa']
				], "id_kpi_target = " . $this->conn->escape($id_kpi_target));

				if ($ret) {

					$nama = $this->conn->GetOne("select nama from kpi a where a.deleted_date is null and exists( select 1 from kpi_target b where a.id_kpi = b.id_kpi and id_kpi_target = " . $this->conn->escape($id_kpi_target) . ")");

					$data = $this->conn->GetArray("
						select id_jabatan from public_sys_user_group i
						where i.deleted_date is null and exists(
								select 1
								from
									public_sys_group_action a
									join public_sys_action b on a.action_id = b.action_id
									join public_sys_group_menu c on a.group_menu_id = c.group_menu_id
								where a.deleted_date is null and i.group_id = c.group_id and b.menu_id = 941 and b.name = 'add' and a.action_id != 2220)
								and exists(select 1 from mt_sdm_jabatan b where b.id_jabatan = i.id_jabatan and b.id_unit = " . $this->conn->escape($this->data['rowheader']['id_unit']) . ") group by id_jabatan
						");
					foreach ($data as $d) {
						$record2 = array(
							'page' => 'analisa_evaluasi',
							'untuk' => $d['id_jabatan'],
							'id_status_pengajuan' => 8,
							'deskripsi' => $_SESSION[SESSION_APP]['nama_group'] . " menambahkan analisa/evaluasi di " . $nama,
							'url' => "panelbackend/kpi_target_realisasi/index/$id_kpi_target",
						);
						$re = $this->InsertTask($record2);
					}
					// $record2 = array(
					// 	'page' => 'analisa_evaluasi',
					// 	'untuk' => 0,
					// 	'id_status_pengajuan' => 8,
					// 	'deskripsi' => $_SESSION[SESSION_APP]['nama_group'] . " menambahkan analisa/evaluasi di " . $nama,
					// 	'url' => "panelbackend/kpi_target_realisasi/index/$id_kpi_target",
					// );

					// $re = $this->InsertTask($record2);
				}

				if ($ret)
					SetFlash("suc_msg", "Berhasil update");
				else
					SetFlash("err_msg", "Berhasil update");

				redirect(current_url());
			}
		}

		$param = array(
			'page' => $page,
			'limit' => -1,
			'order' => $this->_order(),
			'filter' => $this->_getFilter()
		);

		$this->data['row'] = $this->data['rowheader'];
		$files = $this->conn->GetArray("select * from kpi_target_files where deleted_date is null and id_kpi_target = " . $this->conn->escape($id_kpi_target));

		foreach ($files as $r) {
			if ($r['jenis_file'] == 'files_analisa') {
				$this->data['row']['files_analisa']['id'] = $r['id_kpi_target_files'];
				$this->data['row']['files_analisa']['name'] = $r['client_name'];
			} else {
				$this->data['row']['files_evaluasi']['id'] = $r['id_kpi_target_files'];
				$this->data['row']['files_evaluasi']['name'] = $r['client_name'];
			}
		}
		$this->data['row']['id_kpi_target_realisasi'] = $this->data['row']['id_kpi_target'];

		$this->data['list'] = $this->model->SelectGrid(
			$param
		);

		$this->View($this->viewlist);
	}

	public function Delete($id_kpi_target = null, $id = null)
	{

		$this->model->conn->StartTrans();

		$this->_beforeDetail($id_kpi_target, $id);

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
			redirect("$this->page_ctrl/index/$id_kpi_target");
		} else {
			SetFlash('err_msg', "Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id_kpi_target/$id");
		}
	}

	public function Detail($id_kpi_target = null, $id = null)
	{

		$this->_beforeDetail($id_kpi_target, $id);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	public function Add($id_kpi_target = null)
	{
		$this->Edit($id_kpi_target);
	}

	public function Edit($id_kpi_target = null, $id = null)
	{

		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->_beforeDetail($id_kpi_target, $id);

		$this->data['idpk'] = $id;

		$this->data['row'] = $this->model->GetByPk($id);

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
		}

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {

			$idt = $this->conn->GetOne("select id_kpi_target_realisasi from kpi_target_realisasi 
				where deleted_date is null and bulan = " . $this->conn->escape($record['bulan']) . " 
				and id_kpi_target = " . $this->conn->escape($id_kpi_target));

			if ($idt)
				$this->data['row'][$this->pk] = $id = $idt;

			$record['id_kpi_target'] = $id_kpi_target;

			$this->_isValid($record, true);

			$this->_beforeEdit($record, $id);

			$this->_setLogRecord($record, $id);

			$this->model->conn->StartTrans();
			if (trim($this->data['row'][$this->pk]) == trim($id) && trim($id)) {

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

			# mengirim notivikasi
			// if ($return) {
			// 	// $data_not = $this->conn->GetArray("select * from public_sys_user_group where group_id in(4)");
			// 	$data_not = $this->conn->GetArray("
			// 		select 
			// 		g.*
			// 		from
			// 			public_sys_user_group g
			// 			left join public_sys_group a on g.group_id = g.group_id
			// 			left join public_sys_group_menu b on a.group_id = b.group_id
			// 			left join public_sys_menu c on b.menu_id = c.menu_id
			// 			left join public_sys_action d on d.menu_id = c.menu_id
			// 		where a.group_id = 4 and c.menu_id = 941
			// 		/*where d.name = 'view_all' and a.group_id = 4*/");
			// 	foreach ($data_not as $r) {
			// 		$record2 = array(
			// 			'page' => 'unit',
			// 			'untuk' => $r['id_jabatan'],
			// 			'id_status_pengajuan' => 2,
			// 			'deskripsi' => "telah menambahkan realisasi",
			// 			'url' => "panelbackend/penilaian",
			// 		);

			// 		$re = $this->InsertTask($record2);
			// 	}
			// }
			$this->model->conn->CompleteTrans();

			if ($return['success']) {

				$this->_afterEditSucceed($id);

				SetFlash('suc_msg', $return['success']);
				redirect("$this->page_ctrl/index/$id_kpi_target");
			} else {
				$this->data['row'] = array_merge($this->data['row'], $record);
				$this->data['row'] = array_merge($this->data['row'], $this->post);

				$this->_afterEditFailed($id);

				$this->data['err_msg'] = "Data gagal disimpan";
			}
		}

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	protected function _uploadFiles($jenis_file = null, $id = null)
	{
		$this->load->model("Kpi_targetModel", 'kpi_target');
		$this->load->model("Kpi_target_filesModel", 'kpi_target_file');
		$name = $_FILES[$jenis_file]['name'];

		$this->data['configfile']['file_name'] = $jenis_file . time() . $name;

		$this->load->library('upload', $this->data['configfile']);

		if (!$this->upload->do_upload($jenis_file)) {
			$return = array('error' => "File $name gagal upload, " . strtolower(str_replace(array("<p>", "</p>"), "", $this->upload->display_errors())));
		} else {
			$upload_data = $this->upload->data();

			$record = array();
			$record['client_name'] = $upload_data['client_name'];
			$record['file_name'] = $upload_data['file_name'];
			$record['file_type'] = $upload_data['file_type'];
			$record['file_size'] = $upload_data['file_size'];
			$record['jenis_file'] = $record['jenis'] = str_replace("upload", "", $jenis_file);
			$record['id_kpi_target'] = $id;

			// $this->conn->debug = 1;
			$cek = $this->conn->GetArray("select * from kpi_target_files where deleted_date is null and id_kpi_target = " . $this->conn->escape($id) . " and jenis_file = " . $this->conn->escape($record['jenis_file']));
			if ($cek) {
				$ret = $this->kpi_target_file->Update($record, $this->kpi_target->pk . "=" . $this->conn->escape($id));
				if ($ret) {
					$ret['data'][$this->kpi_target_file->pk] = $this->conn->GetOne("select id_kpi_target_files from kpi_target_files where deleted_date is null and id_kpi_target = " . $this->conn->escape($id) . " and jenis_file = " . $this->conn->escape($record['jenis_file']));
				}
			} else {
				$ret = $this->kpi_target_file->Insert($record);
			}

			if ($ret['success']) {
				$return = array('file' => array("id" => $ret['data'][$this->kpi_target_file->pk], "name" => $upload_data['client_name']));
			} else {
				unlink($upload_data['full_path']);
				$return = array('errors' => "File $name gagal upload");
			}
		}

		return $return;
	}

	protected function _deleteFiles($id)
	{
		$this->load->model("Kpi_target_filesModel", 'kpi_target_file');
		$row = $this->kpi_target_file->GetByPk($id);

		if (!$row)
			$this->Error404();

		$file_name = $row['file_name'];

		$return = $this->kpi_target_file->Delete($this->kpi_target_file->pk . " = " . $this->conn->escape($id));

		if ($return) {
			$full_path = $this->data['configfile']['upload_path'] . $file_name;
			@unlink($full_path);

			return array("success" => true);
		} else {
			return array("error" => "File " . $row['client_name'] . " gagal dihapus");
		}
	}
}
