<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Dokumen extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/dokumenlist";
		$this->viewdetail = "panelbackend/dokumendetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_dokumen";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Dokumen';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Dokumen';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Dokumen';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Dokumen';
		}

		$this->load->model("DokumenModel", "model");
		$this->load->model("Dokumen_filesModel", "modelfile");

		$this->load->model("Mt_jenis_dokumenModel", "mtjenisdokumen");
		$this->data['mtjenisdokumenarr'] = $this->mtjenisdokumen->GetCombo();
		$this->data['mtjenisdokumenarr'][''] = "Jenis dokumen";

		$this->load->model("Mt_ppdModel", "mtppd");
		$this->data['mtppdarr'] = $this->mtppd->GetCombo();

		$this->load->model("Mt_sdm_unitModel", "mtunit");
		$this->data['mtunitarr'] = $this->mtunit->GetCombo();
		// unset($this->data['mtunitarr']['']);

		// $this->id_kategori = 1;
		$this->load->model("Mt_sdm_jabatanModel", "mtjabatan");
		$this->data['jabatanarr'] = ['' => ''] + $this->mtjabatan->GetCombo();

		$this->load->model("Mt_kriteriaModel", "mtkriteria");
		// unset($this->data['mtunitarr']['']);

		$this->data['configfile'] = $this->config->item('file_upload_config');
		// $this->data['configfile']['allowed_types'] = 'pdf';
		$this->config->set_item("file_upload_config", $this->data['configfile']);

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'upload', 'select2'
		);
	}

	protected function _beforeDetail($id_kategori = null)
	{

		if (!Access("gcg", "panelbackend/dokumen") && $id_kategori == 1) {
			redirect("panelbackend/dokumen/index/2");
		}
		if (!Access("risk", "panelbackend/dokumen") && $id_kategori == 2) {
			redirect("panelbackend/dokumen/index/3");
		}
		if (!Access("iacm", "panelbackend/dokumen") && $id_kategori == 3) {
			$this->Error403();
		}

		$this->id_kategori = $id_kategori;
		$this->data['id_kategori'] = $id_kategori;
		$this->data['add_param'] .= $id_kategori;
		$this->data['kriteriaarr'] = ['' => ''] + $this->mtkriteria->GetCombo($this->id_kategori, 'd');
	}

	public function Index($id_kategori = null, $page = 0)
	{
		if (!$id_kategori) {
			redirect("panelbackend/dokumen/index/1");
			die();
		}

		$this->_beforeDetail($id_kategori);
		$this->data['header'] = $this->Header();

		$this->_setFilter("id_kategori = " . $this->conn->escape($this->id_kategori));

		$this->data['list'] = $this->_getList($page);

		$this->data['draft'] = $this->conn->GetOne("SELECT COUNT(dv.status) AS STATUS FROM dokumen_versi dv LEFT JOIN dokumen d ON dv.id_dokumen = d.id_dokumen WHERE dv.deleted_date is null and dv.status = 'draft' AND d.id_kategori = " . $this->conn->escape($id_kategori) . " and d.deleted_date is null");
		$this->data['setujui'] = $this->conn->GetOne("SELECT COUNT(dv.status) AS STATUS FROM dokumen_versi dv LEFT JOIN dokumen d ON dv.id_dokumen = d.id_dokumen WHERE dv.deleted_date is null and dv.status = 'setujui' AND d.id_kategori = " . $this->conn->escape($id_kategori) . " and d.deleted_date is null");
		$this->data['revisi'] = $this->conn->GetOne("SELECT COUNT(dv.status) AS STATUS FROM dokumen_versi dv LEFT JOIN dokumen d ON dv.id_dokumen = d.id_dokumen WHERE dv.deleted_date is null and dv.status = 'revisi' AND d.id_kategori = " . $this->conn->escape($id_kategori) . " and d.deleted_date is null");

		$this->data['page'] = $page;

		$param_paging = array(
			'base_url' => base_url("{$this->page_ctrl}/index/" . $id_kategori),
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


		// $this->data['page_title'] .= " " . UI::createSelect("list_search_filter[id_jenis_dokumen]", $this->data['mtjenisdokumenarr'], $this->data['filter_arr']["id_jenis_dokumen"], true, 'form-control', "style='max-width: 250px;display:inline;line-height: 1;font-size: inherit;font-weight: inherit;font-family: inherit;padding: .1rem .375rem !important;'");

		$this->View($this->viewlist);
	}

	public function Add($id_kategori = null)
	{
		$this->Edit($id_kategori);
	}

	public function Edit($id_kategori = null, $id = null)
	{

		$id = urldecode($id);
		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->_beforeDetail($id_kategori);

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

		$this->_onDetail($id);

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {

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

			$this->model->conn->CompleteTrans();

			if ($return['success']) {

				$this->_afterEditSucceed($id);

				if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
					echo json_encode(array("success" => true, "data" => array("key" => $this->pk, "val" => $id)));
					exit();
				} else {
					SetFlash('suc_msg', $return['success']);
					redirect("$this->page_ctrl/detail/$id_kategori/$id");
				}
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

	public function Detail($id_kategori = null, $id = null)
	{

		$id = urldecode($id);
		$this->_beforeDetail($id_kategori);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$this->_onDetail($id);

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	public function Delete($id_kategori = null, $id = null)
	{

		$id = urldecode($id);
		$this->model->conn->StartTrans();

		$this->_beforeDetail($id_kategori);

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
			redirect("$this->page_ctrl/index/$id_kategori");
		} else {
			SetFlash('err_msg', "Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id_kategori/$id");
		}
	}

	protected function Rules()
	{
		return array(
			"is_aktif" => array(
				'field' => 'is_aktif',
				'label' => 'IS Aktif',
				'rules' => "integer|max_length[10]",
			),
			"is_approved" => array(
				'field' => 'is_approved',
				'label' => 'IS Approved',
				'rules' => "integer|max_length[10]",
			),
			"nomor_dokumen" => array(
				'field' => 'nomor_dokumen',
				'label' => 'Nomor Dokumen',
				'rules' => "max_length[45]",
			),
			"nama" => array(
				'field' => 'nama',
				'label' => 'Nama',
				'rules' => "max_length[500]",
			),
			"id_jenis_dokumen" => array(
				'field' => 'id_jenis_dokumen',
				'label' => 'Jenis Dokumen',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtjenisdokumenarr'])) . "]|max_length[10]",
			),
			"id_diupload_oleh" => array(
				'field' => 'id_diupload_oleh',
				'label' => 'Diupload Oleh',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtppdarr'])) . "]|max_length[10]",
			),
			"keterangan" => array(
				'field' => 'keterangan',
				'label' => 'Keterangan',
				'rules' => "",
			),
		);
	}

	protected function Header()
	{
		return array(
			// array(
			// 	// 'name' => 'nomor_dokumen',
			// 	// 'label' => 'Nomor Dokumen',
			// 	// 'width' => "auto",
			// 	// 'type' => "varchar",
			// ),
			array(
				'name' => 'nama',
				'label' => 'Nama Dokumen',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'id_unit',
				'label' => 'Unit',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['mtunitarr'],
			),
			array(
				'name' => 'id_jabatan',
				'label' => 'PIC',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['jabatanarr'],
			),
			array(
				'name' => 'status',
				'label' => 'Status',
				'width' => "auto",
				'type' => "text",
			),
			// array(
			// 	'name' => 'tgl_disahkan',
			// 	'label' => 'Tgl. Disahkan',
			// 	'width' => "auto",
			// 	'type' => "date",
			// ),
			// array(
			// 	'name' => 'keterangan',
			// 	'label' => 'Keterangan',
			// 	'width' => "auto",
			// 	'type' => "text",
			// ),
			// array(
			// 	'name' => 'is_aktif',
			// 	'label' => 'Aktif',
			// 	'width' => "80px",
			// 	'type' => "list",
			// 	'value' => array('' => '-pilih-', '0' => 'Tidak', '1' => 'Iya'),
			// ),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'is_aktif' => (int)$this->post['is_aktif'],
			'is_approved' => (int)$this->post['is_approved'],
			'nomor_dokumen' => $this->post['nomor_dokumen'],
			'nama' => $this->post['nama'],
			'id_jenis_dokumen' => $this->post['id_jenis_dokumen'],
			'tgl_upload' => $this->post['tgl_upload'],
			'tgl_disahkan' => $this->post['tgl_disahkan'],
			'id_diupload_oleh' => $this->post['id_diupload_oleh'],
			'keterangan' => $this->post['keterangan'],
			'id_unit' => $this->post['id_unit'],
			'id_kategori' => $this->id_kategori,
			'id_jabatan' => $this->post['id_jabatan'],
		);
	}

	protected function _afterDetail($id)
	{
		$this->data['rowversi'] = $rows = $this->conn->GetArray("select *
		from dokumen_versi
		where deleted_date is null and id_dokumen = " . $this->conn->escape($id));

		foreach ($this->data['rowversi'] as &$r1) {
			$rows = $this->conn->GetArray("select * from penilaian_detail a 
			where exists (select 1 from penilaian_dokumen b where b.deleted_date is null and a.id_penilaian = b.id_penilaian 
			and b.id_dokumen_versi = " . $this->conn->escape($r1['id_dokumen_versi']) . ") and a.deleted_date is null");
			$arr = [];
			foreach ($rows as $r11) {
				if ($r11['simpulan'])
					$arr[] = "<b>Area Of Improvment : </b>" . $r11['simpulan'] . "<br/><b>Saran : </b>" . $r11['saran'];
			}
			$r1['oai'] = implode("<br/><br/>", $arr);

			$rowfiles = [];
			$rows = $this->conn->GetArray("select * 
			from dokumen_files 
			where id_dokumen_versi = " . $this->conn->escape($r1['id_dokumen_versi']) . " 
			order by client_name");
			foreach ($rows as $r) {
				$rowfiles['id'][] = $r[$this->modelfile->pk];
				$rowfiles['name'][] = $r['client_name'];
			}
			$r1['rowfiles'] = $rowfiles;
		}

		// if ($this->modelfile) {
		// 	if (!$this->data['row']['files']['id'] && $id) {

		// 		foreach ($rows as $r) {
		// 			$this->data['row']['files']['id'][] = $r[$this->modelfile->pk];
		// 			$this->data['row']['files']['name'][] = $r['client_name'];
		// 		}
		// 	}
		// }

		$this->data['row']['id'] = $this->data['row'][$this->model->pk];
		$this->data['row']['name'] = $this->data['row']['client_name'];
		// dpr($this->data['row']['id_jabatanarr']);

		if (!$this->data['row']['id_jabatanarr'])
			$this->data['row']['id_jabatanarr'] = $this->conn->GetList("select id_jabatan as val from dokumen_jabatan where deleted_date is null and id_dokumen = " . $this->conn->escape($id));

		if (!$this->data['row']['id_kriteriaarr'])
			$this->data['row']['id_kriteriaarr'] = $this->conn->GetList("select id_kriteria as val from dokumen_kriteria where deleted_date is null and id_dokumen = " . $this->conn->escape($id));
	}

	protected function _afterUpdate($id)
	{
		return $this->_afterInsert($id);
	}

	protected function _afterInsert($id = null)
	{
		// $this->conn->debug = 1;
		$ret = true;

		// if ($this->modelfile) {
		// 	if (!empty($this->post['files'])) {
		// 		$id_dokumen_files = $this->post['files']['id'];
		// 		$return = $this->_updateFiles([
		// 			$this->pk => $id,
		// 			'status' => 'Draft',
		// 			'catatan_ajuan' => $this->post['catatan_ajuan']
		// 		], $id_dokumen_files);

		// 		$ret = $return['success'];
		// 	}
		// }


		if ($this->modelfile) {
			if (!empty($this->post['files'])) {
				// dpr($this->post['files'], 1);
				if ($ret) {
					$ret = $this->conn->goInsert("dokumen_versi", [
						"id_dokumen" => $id,
						"status" => "Draft",
						"catatan_ajuan" => $this->post['catatan_ajuan'],
					]);
					$id_dokumen_versi = $this->conn->GetOne("select max(id_dokumen_versi)
					from dokumen_versi 
					where deleted_date is null and id_dokumen = " . $this->conn->escape($id));

					if ($ret)
						foreach ($this->post['files']['id'] as $k => $v) {
							if (!$ret)
								break;

							$return = $this->_updateFiles(array(
								$this->pk => $id,
								"id_dokumen_versi" => $id_dokumen_versi
							), $v);

							$ret = $return['success'];
						}
				}
			}
		}

		if ($ret && $this->post['status']) {
			foreach ($this->post['status'] as $id_dokumen_versi => $status) {
				if (!$ret)
					break;

				$record = [
					'status' => $status,
					'catatan_revisi' => $this->post['catatan_revisi'][$id_dokumen_versi]
				];
				$ret = $this->conn->goUpdate("dokumen_versi", $record, 'id_dokumen = ' . $id);
				$ret = true;
			}
		}

		if ($ret && $this->post['id_jabatanarr']) {
			$ret = $this->conn->Execute("delete from dokumen_jabatan where id_dokumen = " . $this->conn->escape($id));
			foreach ($this->post['id_jabatanarr'] as $k => $v) {
				if (!$ret)
					break;

				$ret = $this->conn->goInsert("dokumen_jabatan", ['id_jabatan' => $v, "id_dokumen" => $id]);
			}
		}

		if ($ret && $this->post['id_kriteriaarr']) {
			$ret = $this->conn->Execute("delete from dokumen_kriteria where id_kriteria = " . $this->conn->escape($id));
			foreach ($this->post['id_kriteriaarr'] as $k => $v) {
				if (!$ret)
					break;

				$ret = $this->conn->goInsert("dokumen_kriteria", ['id_kriteria' => $v, "id_dokumen" => $id]);
			}
		}
		return $ret;
	}

	// protected function _openFiles($id = null, $nameid = null)
	// {
	// 	// $row = $this->model->GetByPk($id);
	// 	// if ($row) {
	// 	// 	if ($row['file_url']) {
	// 	// 		// redirect("https://dsmt.jasatirta2.co.id/Documents/viewpdf/" . $row['file_url']);
	// 	// 		//assets/uploads/file_1673237240.pdf >> ini ga per
	// 	// 		redirect("https://dsmt.jasatirta2.co.id/assets/uploads/" . $row['file_url']);
	// 	// 		die();
	// 	// 	} else {
	// 	// 		$full_path = $this->data['configfile']['upload_path'] . $row['file_name'];
	// 	// 		$str = file_get_contents($full_path);
	// 	// 		header("Content-Type: {$row['file_type']}");
	// 	// 		header("Content-Disposition: inline; filename=\"{$row['client_name']}\"");
	// 	// 		header('Content-length: ' . strlen($str));
	// 	// 		echo $str;
	// 	// 		die();
	// 	// 	}
	// 	// } else {
	// 	// 	$this->Error404();
	// 	// }
	// 	$row = $this->model->GetByPk($id);
	// 	if ($row) {
	// 		if ($row['file_url']) {
	// 			// $file_url = "https://dsmt.jasatirta2.co.id/assets/uploads/" . $row['file_url'];
	// 			redirect("https://dsmt.jasatirta2.co.id/assets/uploads/" . $row['file_url']);
	// 			die();
	// 		} else {
	// 			$file_url = base_url() . 'uploads/' . $row['file_name'];
	// 		}
	// 	} else {
	// 		$this->Error404();
	// 	}

	// 	$this->data['url'] = $file_url;
	// 	// if ($this->Access("view_all")) {
	// 	// 	$this->data['punya_acces'] = true;
	// 	// } else {
	// 	// 	$this->data['punya_acces'] = false;
	// 	// }


	// 	unset($this->data['page_title']);
	// 	$this->viewlist = "panelbackend/pdf_read";
	// 	$this->template = "panelbackend/main_pdf";
	// 	$this->layout = "panelbackend/layout_pdf";
	// 	$this->View($this->viewlist);
	// }

	protected function _uploadFiles($jenis_file = null, $id = null)
	{

		// dpr($id, 1);
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
			$record['folder_name'] = $this->post[$record['jenis_file'] . "folder"];
			if ($record['folder_name'])
				$record['client_name'] = $record['folder_name'];
			$record[$this->pk] = $id;

			$ret = $this->modelfile->Insert($record);
			if ($ret['success']) {
				$return = array('file' => array("id" => $ret['data'][$this->modelfile->pk], "name" => $record['client_name']));
			} else {
				unlink($upload_data['full_path']);
				$return = array('errors' => "File $name gagal upload");
			}
		}

		return $return;
	}
}
