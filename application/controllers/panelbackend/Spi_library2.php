<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Spi_library extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/spi_librarylist";
		$this->viewdetail = "panelbackend/spi_librarydetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah SPI Library';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit SPI Library';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail SPI Library';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar SPI Library';
		}

		$this->load->model("Spi_libraryModel", "model");
		$this->pk = $this->model->pk;

		$this->load->model("Spi_libraryModel", "modelfile");


		$this->load->model('Spi_kategori_arsipModel', 'kategoriDokumenModel');
		$this->data['kategoriDokumenArr'] = $this->kategoriDokumenModel->GetCombo();

		$this->data['configfile'] = $this->config->item('file_upload_config');
		// $this->data['configfile']['allowed_types'] = 'pdf';
		$this->config->set_item("file_upload_config", $this->data['configfile']);

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'select2', 'upload'
		);
	}

	public function Index($page = 0)
	{
		$this->data['header'] = $this->Header();

		$this->data['list'] = $this->_getList($page);

		$this->data['page'] = $page;


		$param_paging = array(
			'base_url' => base_url("{$this->page_ctrl}/index"),
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
		foreach ($this->data['list']['rows'] as $row) {
			// dpr($row, 1);
			$this->data['id_files'][$row['id_dokumen']] = $this->conn->GetRow("SELECT id_dokumen FROM spi_library_files WHERE deleted_date is null and id_dokumen =" . $row['id_dokumen']);
		};
		// dpr($this->data['id_files'], 1);
		$this->View($this->viewlist);
	}

	public function Edit($id = null)
	{

		$id = urldecode($id);
		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->_beforeDetail($id);

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
			// dpr($this->post, 1);

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
					redirect("$this->page_ctrl/detail/$id");
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



	protected function Header()
	{
		return array(
			array(
				'name' => 'judul_dokumen',
				'label' => 'Judul Dokumen',
				'width' => "auto",
				'type' => "varchar",
			),
		);
	}

	protected function Record($id = null)
	{
		$recordKategori = $this->conn->GetRow('select id as id_kategori, nama_kategori_arsip as nama_kategori 
		from tr_kategori_arsip_spi where id = ' . $this->conn->escape($this->post['id_kategori_dokumen']));
		return array(
			'nomor_dokumen' => $this->post['nomor_dokumen'],
			'tanggal_dokumen' => $this->post['tanggal_dokumen'],
			'judul_dokumen' => $this->post['judul_dokumen'],
			'id_kategori_dokumen' => $recordKategori['id_kategori'],
			'kategori_dokumen' => $recordKategori['nama_kategori'],
			'sumber_dokumen' => $this->post['sumber_dokumen'],
			'uraian_dokumen' => $this->post['uraian_dokumen'],
			'id_file' => $this->data['id_file'],
		);
	}

	protected function _afterDetail($id)
	{
		if ($this->modelfile) {
			if (!$this->data['row']['files']['id'] && $id) {
				$rows = $this->conn->GetArray("select *
				from {$this->modelfile->table}
				where {$this->model->pk} = " . $this->conn->escape($id));

				foreach ($rows as $r) {
					$this->data['row']['files']['id'][] = $r[$this->modelfile->pk];
					$this->data['row']['files']['name'][] = $r['client_name'];
				}
			}
		}
	}

	protected function Rules()
	{
		return array(

			"judul_dokumen" => array(
				'field' => 'judul_dokumen',
				'label' => 'Judul Dokumen',
				'rules' => "max_length[100]",
			),
			"id_kategori_dokumen" => array(
				'field' => 'id_kategori_dokumen',
				'label' => 'Kategori Dokumen',
				'rules' => "max_length[50]",
			),
			"kategori_dokumen" => array(
				'field' => 'kategori_dokumen',
				'label' => 'Kategori Dokumen',
				'rules' => "max_length[100]",
			),
			"sumber_dokumen" => array(
				'field' => 'sumber_dokumen',
				'label' => 'Sumber Dokumen',
				'rules' => "max_length[100]",
			),
			"uraian_dokumen" => array(
				'field' => 'uraian_dokumen',
				'label' => 'Uraian Dokumen',
				'rules' => "",
			),
		);
	}

	public function Detail($id = null)
	{

		$id = urldecode($id);
		$this->_beforeDetail($id);

		$getFile = $this->conn->GetRow("SELECT client_name as name, id_dokumen FROM spi_library_files where id_dokumen = " . $this->conn->escape($id) . " ORDER BY id_dokumen_files DESC LIMIT 1");
		$this->data['row'] = $this->model->GetByPk($id);
		$this->data['row']['file']['name'] = $getFile['name'];
		$this->data['row']['file']['id'] = $getFile['id_dokumen'];

		if (!$this->data['row'])
			$this->NoData();

		$this->_onDetail($id);

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	protected function _afterInsert($id)
	{
		$ret = true;

		if ($this->modelfile) {
			if (!empty($this->post['file'])) {
				// $row = $this->conn->GetRow("select * from spi_library_files where id_dokumen = " . $id);
				// if ($row) {
				// 	$sql = "delete from spi_library_files where id_dokumen =" . $id;
				// 	$ret = $this->conn->Execute($sql);
				// }
				$return = $this->_updateFiles(array($this->pk => $id), $this->post['file']['id']);
				$ret = $return['success'];
			}
		}

		return $ret;
	}



	function upload_file($id = null)
	{

		$jenis_file = key($_FILES);

		$ret = $this->_uploadFiles($jenis_file, $id);

		echo json_encode($ret);
	}


	function delete_file($id = null)
	{
		$ret = $this->_deleteFiles($this->post['id']);

		echo json_encode($ret);
	}

	function open_file($id = null, $nameid = null)
	{
		$this->_openFiles($id, $nameid);
	}

	protected function _updateFiles($record = array(), $id = null)
	{
		return $this->modelfile->Update($record, $this->modelfile->pk . "=" . $this->conn->escape($id));
	}

	protected function _deleteFiles($id)
	{
		$row = $this->modelfile->GetByPk($id);

		if (!$row)
			$this->Error404();

		$file_name = $row['file_name'];

		$return = $this->modelfile->Delete($this->modelfile->pk . " = " . $this->conn->escape($id));

		if ($return) {
			$full_path = $this->data['configfile']['upload_path'] . $file_name;
			@unlink($full_path);

			return array("success" => true);
		} else {
			return array("error" => "File " . $row['client_name'] . " gagal dihapus");
		}
	}

	protected function _openFiles($id = null, $nameid = null)
	{
		$row = $this->conn->GetRow("select * from spi_library_files where id_dokumen = " . $id);
		if (!$row) {
			$row = $this->conn->GetRow("select * from spi_library_files where id_dokumen_files = " . $id);
		}
		if ($row) {
			$full_path = $this->data['configfile']['upload_path'] . $row['file_name'];
			$str = file_get_contents($full_path);
			header("Content-Type: {$row['file_type']}");
			header("Content-Disposition: inline; filename=\"{$row['client_name']}\"");
			header('Content-length: ' . strlen($str));
			echo $str;
			die();
		} else {
			$this->Error404();
		}
	}

	protected function _uploadFiles($jenis_file = null, $id = null)
	{
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

		// dpr($return, 1);
		return $return;
	}
}
