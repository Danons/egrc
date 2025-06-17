<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Mt_sdm_jabatan extends _adminController
{
	public $limit = -1;
	public $limit_arr = array('100', '500', '1000', '2000', '-1' => 'Semua');
	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/mt_sdm_jabatanlist";
		$this->viewdetail = "panelbackend/mt_sdm_jabatandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Jabatan';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Jabatan';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Jabatan';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Jabatan';
		}

		$this->data['width'] = "2400px";

		$this->load->model("Mt_sdm_jabatanModel", "model");
		$this->load->model("Mt_sdm_unitModel", "mtsdmunit");
		$this->data['mtsdmunitarr'] = $this->mtsdmunit->GetCombo();
		// if (!$this->data['edited'])
		// 	unset($this->data['mtsdmunitarr']['']);


		$this->load->model("Mt_sdm_jabatanModel", "mtsdmjabatan");
		$this->data['mtsdmjabatanarr'] = $this->mtsdmjabatan->GetCombo();

		$this->load->model("Mt_sdm_jenjangModel", "mtsdmjenjang");
		$this->data['mtsdmjenjangarr'] = $this->mtsdmjenjang->GetCombo();

		$this->load->model("Mt_sdm_levelModel", "mtsdmlevel");
		$this->data['mtsdmlevelarr'] = $this->mtsdmlevel->GetCombo();


		$this->load->model("Mt_sdm_kategoriModel", "mtsdmkategori");
		$this->data['mtsdmkategoriarr'] = $this->mtsdmkategori->GetCombo();


		$this->load->model("Mt_sdm_dit_bidModel", "mtsdmditbid");
		$this->data['mtsdmditbidarr'] = $this->mtsdmditbid->GetCombo();


		$this->load->model("Mt_sdm_subbidModel", "mtsdmsubbid");
		$this->data['mtsdmsubbidarr'] = $this->mtsdmsubbid->GetCombo();


		$this->load->model("Mt_sdm_tipe_unitModel", "mtsdmtipeunit");
		$this->data['mtsdmtipeunitarr'] = $this->mtsdmtipeunit->GetCombo();

		$this->data['configfile'] = $this->config->item('file_upload_config');

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->SetPlugin(array(
			'datepicker', 'upload', 'treetable', 'select2'
		));
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'nama',
				'field' => 'a_____nama',
				'label' => 'Nama',
				'width' => "auto",
				'type' => "varchar2",
			),
			// array(
			// 	'name' => 'position_id',
			// 	'label' => 'Kode Jabatan',
			// 	'width' => "auto",
			// 	'type' => "char",
			// ),
			array(
				'name' => 'id_unit',
				'label' => 'Unit',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['mtsdmunitarr'],
			),
			array(
				'name' => 'id_sdm_level',
				'field' => 'a_____id_sdm_level',
				'label' => 'Level',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['mtsdmlevelarr'],
			),
			// array(
			// 	'name' => 'tgl_mulai_efektif',
			// 	'label' => 'Tgl. Mulai Efektif',
			// 	'width' => "auto",
			// 	'type' => "date",
			// ),
			// array(
			// 	'name' => 'tgl_akhir_efektif',
			// 	'label' => 'Tgl. Akhir Efektif',
			// 	'width' => "auto",
			// 	'type' => "date",
			// ),
			array(
				'name' => 'id_jenjang',
				'label' => 'Jenjang',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['mtsdmjenjangarr'],
			),
			/*,
			array(
				'name'=>'id_jabatan_parent', 
				'label'=>'Jabatan Parent', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtsdmjabatanarr'],
			),
			array(
				'name'=>'superior_id', 
				'label'=>'Superior ID', 
				'width'=>"auto",
				'type'=>"char",
			),*/
			/*array(
				'name'=>'id_kategori', 
				'label'=>'Kategori', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtsdmkategoriarr'],
			),
			array(
				'name'=>'id_tipe_unit', 
				'label'=>'Tipe Unit', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtsdmtipeunitarr'],
			),
			array(
				'name'=>'id_dit_bid', 
				'label'=>'DIT BID', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtsdmditbidarr'],
			),
			array(
				'name'=>'id_subbid', 
				'label'=>'Subbid', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtsdmsubbidarr'],
			),*/
		);
	}

	protected function Record($id = null)
	{
		if ($this->post['id_jabatan_parent'])
			$this->post['superior_id'] = $this->conn->GetOne("select position_id from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->post['id_jabatan_parent']);

		$return = array(
			'nama' => $this->post['nama'],
			'id_sdm_level' => $this->post['id_sdm_level'],
			'id_unit' => $this->post['id_unit'] ? $this->post['id_unit'] : ($this->post['act'] == 'save' ? "{{null}}" : null),
			'position_id' => $this->post['position_id'],
			'tgl_mulai_efektif' => $this->post['tgl_mulai_efektif'],
			'tgl_akhir_efektif' => $this->post['tgl_akhir_efektif'],
			'id_jabatan_parent' => $this->post['id_jabatan_parent'] ? $this->post['id_jabatan_parent'] : ($this->post['act'] == 'save' ? "{{null}}" : null),
			'superior_id' => $this->post['superior_id'],
			'id_kategori' => $this->post['id_kategori'],
			'id_jenjang' => $this->post['id_jenjang'] ? $this->post['id_jenjang'] : "{{null}}",
			'id_tipe_unit' => $this->post['id_tipe_unit'],
			'urutan' => $this->post['urutan'],
			'id_dit_bid' => $this->post['id_dit_bid'] ? $this->post['id_dit_bid'] : "{{null}}",
			'id_subbid' => $this->post['id_subbid'] ? $this->post['id_subbid'] : "{{null}}",
		);

		return $return;
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

		if (!$this->data['row']['id_unit'] && $this->data['row']['id_jabatan_parent']) {
			// $this->data['row']['id_dit_bid'] = $this->conn->GetOne("select id_dit_bid from mt_sdm_jabatan where id_jabatan = " . $this->conn->escape($this->data['row']['id_jabatan_parent']));
			$this->data['row']['id_unit'] = $this->conn->GetOne("select id_unit from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($this->data['row']['id_jabatan_parent']));
		}

		if (!$id) {
			if ($this->data['row']['id_dit_bid']) {
				$nama = $this->conn->GetOne("select nama from mt_sdm_dit_bid where deleted_date is null and code = " . $this->conn->escape($this->data['row']['id_dit_bid']));
				$this->data['row']['nama'] = str_replace("DIREKTORAT", "DIREKTUR", strtoupper($nama));
			}
			if ($this->data['row']['id_unit']) {
				$nama = $this->conn->GetOne("select table_desc from mt_sdm_unit where deleted_date is null and table_code = " . $this->conn->escape($this->data['row']['id_unit']));
				$this->data['row']['nama'] = "KEPALA " . strtoupper($nama);
			}
			if ($this->data['row']['id_subbid']) {
				$nama = $this->conn->GetOne("select nama from mt_sdm_subbid where deleted_date is null and code = " . $this->conn->escape($this->data['row']['id_subbid']));
				$this->data['row']['nama'] = "KEPALA BIDANG " . str_replace("BIDANG", "", strtoupper($nama));
			}
		}
	}

	protected function _onDetail($id = null)
	{
		$id_jabatan_parent = $this->data['row']['id_jabatan_parent'];
		// $this->data['mtsdmjabatanarr'][$id_jabatan_parent] = $this->conn->GetOne("select concat(nama,' (',coalesce(id_unit,''),')') from mt_sdm_jabatan where id_jabatan = " . $this->conn->escape($id_jabatan_parent));
		$this->data['mtsdmjabatanarr'][$id_jabatan_parent] = $this->conn->GetOne("select concat(nama,' (',coalesce(id_unit,''),')') from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($id_jabatan_parent));
	}

	protected function Rules()
	{
		return array(
			"nama" => array(
				'field' => 'nama',
				'label' => 'Nama',
				'rules' => "required|max_length[200]",
			),
			"id_unit" => array(
				'field' => 'id_unit',
				'label' => 'Unit',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtsdmunitarr'])) . "]|max_length[18]",
			),
			"position_id" => array(
				'field' => 'position_id',
				'label' => 'Kode Jabatan',
				'rules' => "required",
			),
			"id_jabatan_parent" => array(
				'field' => 'id_jabatan_parent',
				'label' => 'Jabatan Parent',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtsdmjabatanarr'])) . "]|max_length[10]",
			),
			"superior_id" => array(
				'field' => 'superior_id',
				'label' => 'Superior ID',
				'rules' => "",
			),
			"id_kategori" => array(
				'field' => 'id_kategori',
				'label' => 'Kategori',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtsdmkategoriarr'])) . "]|max_length[20]",
			),
			"id_jenjang" => array(
				'field' => 'id_jenjang',
				'label' => 'Jenjang',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtsdmjenjangarr'])) . "]|max_length[20]",
			),
			"id_tipe_unit" => array(
				'field' => 'id_tipe_unit',
				'label' => 'Tipe Unit',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtsdmtipeunitarr'])) . "]|max_length[20]",
			),
			"id_dit_bid" => array(
				'field' => 'id_dit_bid',
				'label' => 'DIT BID',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtsdmditbidarr'])) . "]|max_length[20]",
			),
			"id_subbid" => array(
				'field' => 'id_subbid',
				'label' => 'Subbid',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtsdmsubbidarr'])) . "]|max_length[20]",
			),
		);
	}

	public function Index($page = 0)
	{
		$this->layout = "panelbackend/layout2";

		if (!$_SESSION[SESSION_APP]['filter_tgl_efektif'])
			$_SESSION[SESSION_APP]['filter_tgl_efektif'] = date('Y-m-d');

		if ($this->post['act'] == 'filter') {
			$_SESSION[SESSION_APP]['filter_tgl_efektif'] = $this->post['tgl_efektif'];
			redirect(current_url());
			die();
		}

		$this->data['tgl_efektif'] = $tgl_efektif = $_SESSION[SESSION_APP]['filter_tgl_efektif'];

		$this->_setFilter(" '$tgl_efektif' between coalesce(a.tgl_mulai_efektif, '$tgl_efektif')and coalesce(a.tgl_akhir_efektif,'$tgl_efektif') ");

		// parent::Index($page);

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

		$this->View($this->viewlist);
	}

	protected function _beforeDelete($id = null)
	{

		if (!$this->access_role['delete'])
			return false;

		// $cek = $this->conn->GetOne("select s.nama
		// 	from RISK_SASARAN_STRATEGIS_PIC sp
		// 	join risk_sasaran s on sp.id_sasaran = s.id_sasaran
		// 	where sp.ID_JABATAN = " . $this->conn->escape($id));

		// if ($cek) {
		// 	SetFlash('err_msg', "Data tidak bisa dihapus karena jabatan sudah menjadi PIC di sasaran strategis " . $cek);
		// 	redirect("$this->page_ctrl/detail/$id");
		// 	die();
		// }

		// $cek = $this->conn->GetOne("select s.nama
		// 	from RISK_SASARAN_KEGIATAN s
		// 	where OWNER = " . $this->conn->escape($id));

		// if ($cek) {
		// 	SetFlash('err_msg', "Data tidak bisa dihapus karena jabatan sudah menjadi PIC di sasaran kegiatan " . $cek);
		// 	redirect("$this->page_ctrl/detail/$id");
		// 	die();
		// }

		// $cek = $this->conn->GetOne("select username
		// 	from PUBLIC_SYS_USER 
		// 	where ID_JABATAN = " . $this->conn->escape($id));

		// if ($cek) {
		// 	SetFlash('err_msg', "Data tidak bisa dihapus karena jabatan sudah ada di user dengan username " . $cek);
		// 	redirect("$this->page_ctrl/detail/$id");
		// 	die();
		// }

		// $cek = $this->conn->GetRow("select m.nama, n.nama as risiko
		// 	from RISK_MITIGASI m
		// 	join risk_risiko n on m.id_risiko = n.id_risiko
		// 	where PENANGGUNG_JAWAB = " . $this->conn->escape($id));

		// if (($cek)) {
		// 	SetFlash('err_msg', "Data tidak bisa dihapus karena jabatan sudah menjadi penanggung jawab mitigasi dengan nama kegiatan " . $cek['nama'] . ", di risiko " . $cek['risiko'] . " (silahkan dicari di status risiko)");
		// 	redirect("$this->page_ctrl/detail/$id");
		// 	die();
		// }

		// $cek = $this->conn->GetOne("select nama
		// 	from RISK_SCORECARD 
		// 	where OWNER = " . $this->conn->escape($id));

		// if ($cek) {
		// 	SetFlash('err_msg', "Data tidak bisa dihapus karena jabatan sudah menjadi owner di scorecard " . $cek);
		// 	redirect("$this->page_ctrl/detail/$id");
		// 	die();
		// }

		$ret = $this->conn->Execute("update RISK_TASK set deleted_date = now() where UNTUK = " . $this->conn->escape($id));

		return $ret;
	}

	public function HeaderExport()
	{
		return array(
			array(
				'name' => 'nama',
				'label' => 'Nama',
				'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'id_unit',
				'label' => 'Unit',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['mtsdmunitarr'],
			),
			array(
				'name' => 'position_id',
				'label' => 'Kode Jabatan',
				'width' => "auto",
				'type' => "char",
			),
			array(
				'name' => 'superior_id',
				'label' => 'Jabatan Parent',
				'width' => "auto",
				'type' => "char",
			),
			// array(
			// 	'name' => 'id_kategori',
			// 	'label' => 'Kategori',
			// 	'width' => "auto",
			// 	'type' => "list",
			// 	'value' => $this->data['mtsdmkategoriarr'],
			// ),
			// array(
			// 	'name' => 'id_jenjang',
			// 	'label' => 'Jenjang',
			// 	'width' => "auto",
			// 	'type' => "list",
			// 	'value' => $this->data['mtsdmjenjangarr'],
			// ),
			// array(
			// 	'name' => 'id_tipe_unit',
			// 	'label' => 'Tipe Unit',
			// 	'width' => "auto",
			// 	'type' => "list",
			// 	'value' => $this->data['mtsdmtipeunitarr'],
			// ),
			// array(
			// 	'name' => 'id_dit_bid',
			// 	'label' => 'DIT BID',
			// 	'width' => "auto",
			// 	'type' => "list",
			// 	'value' => $this->data['mtsdmditbidarr'],
			// ),
			// array(
			// 	'name' => 'id_subbid',
			// 	'label' => 'Subbid',
			// 	'width' => "auto",
			// 	'type' => "list",
			// 	'value' => $this->data['mtsdmsubbidarr'],
			// ),
			array(
				'name' => 'id_sdm_level',
				'label' => 'Level',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['mtsdmlevelarr'],
			),
			array(
				'name' => 'tgl_mulai_efektif',
				'label' => 'Tgl. Mulai Efektif',
				'width' => "auto",
				'type' => "date",
			),
			array(
				'name' => 'tgl_akhir_efektif',
				'label' => 'Tgl. Akhir Efektif',
				'width' => "auto",
				'type' => "date",
			),
			array(
				'name' => 'urutan',
				'label' => 'Urutan',
				'width' => "auto",
				'type' => "char",
			),
		);
	}

	public function import_list()
	{

		$file_arr = array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'application/wps-office.xls', 'application/wps-office.xlsx');

		// $this->conn->debug = 1;
		if (in_array($_FILES['importupload']['type'], $file_arr)) {

			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters("", "");

			$this->load->library('Factory');
			$inputFileType = Factory::identify($_FILES['importupload']['tmp_name']);
			$objReader = Factory::createReader($inputFileType);
			$excel = $objReader->load($_FILES['importupload']['tmp_name']);
			$sheet = $excel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$this->model->conn->StartTrans();

			#header export
			$header = array(
				array(
					'name' => $this->model->pk
				)
			);
			$header = array_merge($header, $this->HeaderExport());

			for ($row = 2; $row <= $highestRow; $row++) {

				$col = 'A';
				$record = array();
				foreach ($header as $r1) {
					if ($sheet->getCell($col . $row)->getValue() !== null)
						if ($r1['type'] == 'list')
							$record[$r1['name']] = (string)$sheet->getCell($col . $row)->getValue();
						elseif ($r1['type'] == 'listinverst') {
							$rk = strtolower(trim((string)$sheet->getCell($col . $row)->getValue()));
							$arr = array();
							foreach ($r1['value'] as $idkey => $value) {
								$arr[strtolower(trim($value))] = $idkey;
							}
							$record[$r1['name_ori']] = (string)$arr[$rk];
						} else if ($r1['type'] == 'date') {
							// dpr($sheet->getCell($col . $row)->getValue());
							$excelDate = strtotime(str_replace('/', '-', $sheet->getCell($col . $row)->getValue()));
							// dpr($excelDate);
							$UNIX_DATE = date("Y-m-d", $excelDate);
							// dpr($UNIX_DATE,1);
							if ($excelDate) {
								// $record[$r1['name']] = gmdate("Y-m-d", $UNIX_DATE);
								$record[$r1['name']] = $UNIX_DATE;
							}
						} else
							$record[$r1['name']] = $sheet->getCell($col . $row)->getValue();

					$col++;
				}
				// dpr($record);

				$this->data['row'] = $record;

				$error = $this->_isValidImport($record);
				if ($error) {
					$return['error'] = $error;
				} else {
					if ($record[$this->model->pk]) {
						$id = $record[$this->model->pk];
						unset($record[$this->model->pk]);
						$return = $this->model->Update($record, $this->model->pk . "=" . $this->conn->escape($id));
						$id = $record[$this->model->pk];

						if ($return['success']) {
							$ret = $this->_afterUpdate($id);

							if (!$ret) {
								$return['success'] = false;
								$return['error'] = "Gagal update";
							}
						}
					} else {
						$return = $this->model->Insert($record);
						$id = $return['data'][$this->model->pk];

						if ($return['success']) {
							$ret = $this->_afterInsert($id);

							if (!$ret) {
								$return['success'] = false;
								$return['error'] = "Gagal insert";
							}
						}
					}
				}

				if (!$return['success'])
					break;
			}
			// die;
			// dpr(in_array($_FILES['importupload']['type'], $file_arr),1);


			if (!$return['error'] && $return['success']) {

				// $this->conn->Execute("update mt_sdm_jabatan set id_jabatan_parent = (select id_jabatan from mt_sdm_jabatan b where b.position_id = mt_sdm_jabatan.superior_id)");
				$this->conn->Execute("
				UPDATE mt_sdm_jabatan a
				INNER JOIN mt_sdm_jabatan b ON a.superior_id = b.position_id
				SET a.id_jabatan_parent = b.id_jabatan
				");

				$this->model->conn->trans_commit();
				SetFlash('suc_msg', "Import sukses");
			} else {
				$this->model->conn->trans_rollback();
				$return['error'] = "Gagal import. " . $return['error'];
				$return['success'] = false;
			}
		} else {
			$return['error'] = "Format file tidak sesuai";
		}

		echo json_encode($return);
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

}
