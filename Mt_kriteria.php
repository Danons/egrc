<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Mt_kriteria extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/mt_kriterialist";
		$this->viewdetail = "panelbackend/mt_kriteriadetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Kriteria';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			unset($this->access_role['add']);
			$this->data['page_title'] = 'Detail Kriteria';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Kriteria';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Kriteria';
		}

		$this->data['width'] = "1800px";

		$this->load->model("Mt_kriteriaModel", "model");
		$this->load->model("Mt_kategoriModel", "mtkategori");
		$this->data['mtkategoriarr'] = $this->mtkategori->GetCombo();

		$this->load->model("Mt_sdm_unitModel", "mtunit");
		$this->data['mtunitarr'] = $this->mtunit->GetCombo();
		unset($this->data['mtunitarr']['']);


		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'upload', 'datepicker'
		);
	}

	public function Index($id_kategori = null)
	{
		if (!$id_kategori) {
			$id_kategori = 1;
		}
		$this->_beforeDetail($id_kategori);
		$this->data['parentarr'] = $this->model->getComboParent($id_kategori);

		if ($this->post['act'] == 'set_parent')
			$_SESSION[SESSION_APP][$this->page_ctrl][$id_kategori]['id_parent'] = $this->post['idkey'];

		if (!$_SESSION[SESSION_APP][$this->page_ctrl][$id_kategori]['id_parent'])
			$_SESSION[SESSION_APP][$this->page_ctrl][$id_kategori]['id_parent'] = array_values($this->data['parentarr'])[0];

		$this->data['id_parent'] = $_SESSION[SESSION_APP][$this->page_ctrl][$id_kategori]['id_parent'];
		$this->data['arearr'] = $this->model->get_kriteria($_SESSION[SESSION_APP][$this->page_ctrl][$id_kategori]['id_parent']);
		// $this->data['arearr'] = $this->model->get_kriteria($_SESSION[SESSION_APP][$this->page_ctrl]['id_parent']);
		$this->data['tahun'] = date('Y');
		$this->data['addbutton'] = UI::createExportImport();
		$this->data['nobutton'] = true;
		$this->View($this->viewlist);
	}

	private function kategoriarr($id_kategori = null)
	{
		$row = $this->conn->GetRow("select * from mt_kategori where id_kategori = " . $this->conn->escape($id_kategori));

		if ($row['id_kategori_parent'])
			$this->kategoriarr($row['id_kategori_parent']);

		$this->data['kategoriarr'][] = $row;
	}

	protected function _beforeDetail($id_kategori = null)
	{
		// $this->layout = "panelbackend/layout2";
		$this->kategoriarr($id_kategori);
		$this->data['id_kategori'] = $this->id_kategori = $id_kategori;
		$this->data['rowheader'] = $this->mtkategori->GetByPk($id_kategori);
		$this->data['id_kategori_jenis'] = $this->id_kategori_jenis = $this->data['rowheader']['id_kategori_jenis'];
		$this->data['page_title'] .= " " . $this->data['rowheader']['nama'];

		$this->data['add_param'] = $id_kategori;
	}

	protected function _onDetail($id = null)
	{
		// $this->data['rowsattribute'] = $this->conn->GetArray("select k1.* 
		// 	from kriteria_link1 kl
		// 	join mt_kriteria_attribute k1 on kl.id_kriteria2 = k1.id_kriteria
		// 	where kl.id_kriteria1 = " . $this->conn->escape($id) . " order by id_kriteria_attribute");

		// if (!$this->data['row']['kriteria_detail']) {
		// 	$rows = $this->conn->GetArray("select k1.* 
		// 		from kriteria_link1 kl
		// 		join mt_kriteria_detail k1 on kl.id_kriteria2 = k1.id_kriteria
		// 		where kl.id_kriteria1 = " . $this->conn->escape($id));

		// 	$this->data['row']['kriteria_detail'] = array();
		// 	foreach ($rows as $r) {
		// 		$rs = $this->conn->GetArray("select * from mt_kriteria_detail_attribute where id_kriteria_detail = " . $this->conn->escape($r['id_kriteria_detail']));

		// 		foreach ($rs as $r1) {
		// 			$r[$r1['id_kriteria_attribute']] = $r1['isi'];
		// 		}
		// 		$this->data['row']['kriteria_detail'][] = $r;
		// 	}
		// }
	}

	public function Add($id_kategori = null)
	{
		$this->Edit($id_kategori);
	}

	public function Edit($id_kategori = null, $id = null)
	{
		if ($this->post['act'] == 'save_file') {
			$this->_uploadFile($id);
		}

		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->_beforeDetail($id_kategori);

		$this->data['idpk'] = $id;

		$this->data['row'] = $this->model->GetByPk($id);


		if (!$this->data['rowheader'] && !$this->data['row'] && $id)
			$this->NoData();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");

		if (count($this->post) && $this->post['act'] <> 'change' && $this->post['act'] <> 'edit') {
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
			// $this->conn->debug = 1;
			$record['id_kategori'] = $id_kategori;

			$this->_isValid($record);

			$this->conn->StartTrans();

			$return['success'] = $this->_beforeEdit($record, $id);

			$this->_setLogRecord($record, $id);

			// dpr($record,1);
			if ($return['success']) {
				if (trim($this->data['row'][$this->pk]) == trim($id) && trim($id)) {

					$ret = $this->_beforeUpdate($record, $id);

					if ($ret)
						$return['success'] = "Berhasil update";

					if ($return) {
						$return = $this->model->Update($record, "$this->pk = " . $this->conn->qstr($id));
					}

					if ($return['success']) {

						$this->log("mengubah " . $record['nama']);

						$return1 = $this->_afterUpdate($id);

						if (!$return1) {
							$return = false;
						}
					}
				} else {

					$ret = $this->_beforeInsert($record);

					if ($ret)
						$return['success'] = "Berhasil insert";

					if ($return) {
						$return = $this->model->Insert($record);
						$id = $return['data'][$this->pk];
					}

					if ($return['success']) {

						$this->log("menambah " . $record['nama']);

						$return1 = $this->_afterInsert($id);

						if (!$return1) {
							$return = false;
						}
					}
				}
			}

			// dpr($return, 1);

			if ($return['success']) {
				$this->conn->trans_commit();

				// $this->_onSuccess($id);
				// $this->_setGo($id);

				$this->_afterEditSucceed($id);

				if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
					echo json_encode(array("success" => true, "data" => array("key" => $this->pk, "val" => $id)));
					exit();
				} else {
					SetFlash('suc_msg', $return['success']);
					redirect("$this->page_ctrl/index/$id_kategori");
				}
			} else {
				$this->conn->trans_rollback();
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
				'name' => 'kode',
				'label' => 'Kode',
				'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'nama',
				'label' => 'Nama',
				'width' => "auto",
				'type' => "varchar2",
			),
		);
	}

	protected function Record($id = null)
	{
		// return $this->data['row'];
		return array(
			// 'id_kategori' => $this->post['id_kategori'],
			'kode' => $this->post['kode'],
			'nama' => $this->post['nama'],
			// 'is_upload' => (int)$this->post['is_upload'],
			// 'id_kriteria_parent' => $this->post['id_kriteria_parent'],
			// 'is_aktif' => (int)$this->post['is_aktif'],
			// 'id_interval' => $this->post['id_interval'],
			// 'bobot' => $this->post['bobot'],
			// 'tahun' => $this->post['tahun'],
			// 'id_unit' => $this->post['id_unit'],
			// 'id_kriteria_before' => $this->post['id_kriteria_before'],
			// 'id_kriteria_parent1' => $this->post['id_kriteria_parent1'],
			// 'd' => $this->post['d'],
			// 'k' => $this->post['k'],
			// 'w' => $this->post['w'],
			// 'o' => $this->post['o'],
		);
	}

	protected function _afterUpdate($id)
	{
		$ret = true;

		// foreach ($this->post['kriteria_detail'] as $r) {
		// 	if (!$ret)
		// 		break;

		// 	if (!$r['id_kriteria_detail']) {
		// 		$ret = $this->conn->goInsert("mt_kriteria_detail", array("id_kriteria" => $id));
		// 		$r['id_kriteria_detail'] = $this->conn->GetOne("select max(id_kriteria_detail) from mt_kriteria_detail where id_kriteria = " . $this->conn->escape($id));
		// 	}

		// 	if ($ret)
		// 		$ret = $this->conn->Execute("delete from mt_kriteria_detail_attribute where id_kriteria_detail = " . $this->conn->escape($r['id_kriteria_detail']));

		// 	if ($ret) {
		// 		foreach ($this->data['rowsattribute'] as $r1) {
		// 			if (!$ret)
		// 				break;

		// 			$ret = $this->conn->goInsert("mt_kriteria_detail_attribute", array("id_kriteria_detail" => $r['id_kriteria_detail'], "id_kriteria_attribute" => $r1['id_kriteria_attribute'], "isi" => $r[$r1['id_kriteria_attribute']]));
		// 		}
		// 	}
		// }

		return $ret;
	}

	protected function Rules()
	{
		return array(
			/*"id_kategori"=>array(
				'field'=>'id_kategori', 
				'label'=>'Kategori', 
				'rules'=>"required|in_list[".implode(",", array_keys($this->data['mtkategoriarr']))."]|max_length[10]",
			),*/
			"kode" => array(
				'field' => 'kode',
				'label' => 'Kode',
				'rules' => "required|max_length[20]",
			),
			"nama" => array(
				'field' => 'nama',
				'label' => 'Nama',
				'rules' => "required|max_length[2000]",
			),/*
			"kode_lvl"=>array(
				'field'=>'kode_lvl', 
				'label'=>'Kode LVL', 
				'rules'=>"max_length[20]",
			),
			"nama_lvl"=>array(
				'field'=>'nama_lvl', 
				'label'=>'Nama LVL', 
				'rules'=>"max_length[2000]",
			),
			"is_upload"=>array(
				'field'=>'is_upload', 
				'label'=>'IS Upload', 
				'rules'=>"max_length[1]",
			),
			"is_aktif"=>array(
				'field'=>'is_aktif', 
				'label'=>'IS Aktif', 
				'rules'=>"required|max_length[1]",
			),
			"id_periode"=>array(
				'field'=>'id_periode', 
				'label'=>'Periode', 
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtperiodearr']))."]|max_length[10]",
			),*/
		);
	}

	protected function _isValidImport($record)
	{
		$this->data['rules'] = $this->Rules();

		$rules = array_values($this->data['rules']);

		if ($record) {
			$this->form_validation->set_data($record);
		}

		$this->form_validation->set_rules($rules);

		if (count($rules) && $this->form_validation->run() == FALSE) {
			return validation_errors();
		}
	}

	private function _updateCild($id_kriteria)
	{
		$rows = $this->conn->GetArray("select id_kriteria from mt_kriteria where id_kriteria_parent = " . $this->conn->escape($id_kriteria));
		foreach ($rows as $r) {
			$this->_updateCild($r['id_kriteria']);
			$this->conn->Execute("delete from mt_kriteria where is_aktif = 0 and id_kriteria = " . $this->conn->escape($r['id_kriteria']));
			$this->conn->goUpdate("mt_kriteria", ["is_aktif" => 0], "id_kriteria = " . $this->conn->escape($r['id_kriteria']));
		}
	}

	public function import_list($id_kategori = null)
	{
		$file_arr = array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'application/wps-office.xls', 'application/wps-office.xlsx');

		if (in_array($_FILES['importupload']['type'], $file_arr)) {

			$this->_beforeDetail($id_kategori);

			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters("", "");

			$this->load->library('Factory');
			$inputFileType = Factory::identify($_FILES['importupload']['tmp_name']);
			$objReader = Factory::createReader($inputFileType);
			$excel = $objReader->load($_FILES['importupload']['tmp_name']);
			$sheet = $excel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			// $this->conn->debug = 1;
			$rows = array();
			$this->conn->StartTrans();


			$header = $this->HeaderExport();
			$id_area = null;
			$id_kategori_area = null;
			$id_lvl = null;
			$id_bukti = null;
			$id_parrent = $_SESSION[SESSION_APP][$this->page_ctrl][$id_kategori]['id_parent'];
			// $this->conn->goUpdate(
			// 	"mt_kriteria",
			// 	array("is_aktif" => 0),
			// 	"id_kriteria_parent = " . $this->conn->escape($id_parrent) . "
			// 	and id_kategori = " . $this->conn->escape($id_kategori)
			// );
			$this->_updateCild($id_parrent);

			for ($row = 2; $row <= $highestRow; $row++) {
				$id_kriteria_parent = $id_parrent;
				$record = array();
				$record['id_interval'] = 20;
				$record['id_kategori'] = $id_kategori;
				$record['id_kriteria_parent'] = $id_kriteria_parent;
				$record['kode'] = (string)$sheet->getCell('A' . $row)->getValue();
				$record['nama'] = (string)$sheet->getCell('B' . $row)->getValue();
				$record['is_aktif'] = 1;
				if ($record['kode']) {
					$id_kriteria = $this->conn->GetOne("select id_kriteria 
					from mt_kriteria 
					where id_kategori = " . $this->conn->escape($id_kategori) . "
					and id_kriteria_parent = " . $this->conn->escape($id_kriteria_parent) . " 
					and kode = " . $this->conn->escape($record['kode']));
					if ($id_kriteria)
						$this->conn->goUpdate("mt_kriteria", $record, "id_kriteria=" . $this->conn->escape($id_kriteria));
					else
						$id_kriteria = $this->model->tambah_kriteria($record);

					$id_kriteria_parent1 = $id_kriteria;
				}

				$record = array();
				$record['id_interval'] = 20;
				$record['id_kategori'] = $id_kategori;
				$record['id_kriteria_parent'] = $id_kriteria_parent1;
				$record['kode'] = (string)$sheet->getCell('C' . $row)->getValue();
				$record['nama'] = (string)$sheet->getCell('D' . $row)->getValue();
				if ($this->id_kategori_jenis == 1)
					$record['bobot'] = (string)$sheet->getCell('E' . $row)->getValue();
				$record['is_aktif'] = 1;
				if ($record['kode']) {
					$id_kriteria = $this->conn->GetOne("select id_kriteria 
					from mt_kriteria 
					where id_kategori = " . $this->conn->escape($id_kategori) . "
					and id_kriteria_parent = " . $this->conn->escape($id_kriteria_parent1) . " 
					and kode = " . $this->conn->escape($record['kode']));
					if ($id_kriteria)
						$this->conn->goUpdate("mt_kriteria", $record, "id_kriteria=" . $this->conn->escape($id_kriteria));
					else
						$id_kriteria = $this->model->tambah_kriteria($record);

					$id_kriteria_parent2 = $id_kriteria;
				}

				$record = array();
				$record['id_interval'] = 20;
				$record['id_kategori'] = $id_kategori;
				$record['id_kriteria_parent'] = $id_kriteria_parent2;
				$record['is_aktif'] = 1;
				if ($this->id_kategori_jenis == 1) {
					$record['kode'] = (string)$sheet->getCell('F' . $row)->getValue();
					$record['bobot'] = "{{null}}";
					$record['nama'] = (string)$sheet->getCell('G' . $row)->getValue();
					if (!(string)$sheet->getCell('H' . $row)->getValue()) {
						$record['d'] = (string)$sheet->getCell('J' . $row)->getValue();
						$record['k'] = (string)$sheet->getCell('K' . $row)->getValue();
						$record['w'] = (string)$sheet->getCell('L' . $row)->getValue();
						$record['o'] = (string)$sheet->getCell('M' . $row)->getValue();
						$record['is_aktif'] = (string)$sheet->getCell('N' . $row)->getValue();
					}
				} elseif ($this->id_kategori_jenis == 2) {
					$record['kode'] = (string)$sheet->getCell('E' . $row)->getValue();
					$record['nama'] = (string)$sheet->getCell('F' . $row)->getValue();
				} elseif ($this->id_kategori_jenis == 3) {
					$record['kode_lvl'] = (string)$sheet->getCell('E' . $row)->getValue();
					$record['kode'] = (string)$sheet->getCell('F' . $row)->getValue();
					$record['nama'] = (string)$sheet->getCell('G' . $row)->getValue();
					$record['keterangan'] = (string)$sheet->getCell('H' . $row)->getValue();
					$record['keterangan1'] = (string)$sheet->getCell('I' . $row)->getValue();
					$record['keterangan2'] = (string)$sheet->getCell('J' . $row)->getValue();
					$record['d'] = (string)$sheet->getCell('K' . $row)->getValue();
					$record['k'] = (string)$sheet->getCell('L' . $row)->getValue();
					$record['w'] = (string)$sheet->getCell('M' . $row)->getValue();
					$record['o'] = (string)$sheet->getCell('N' . $row)->getValue();
					$record['is_aktif'] = (string)$sheet->getCell('O' . $row)->getValue();
				}
				if ($record['kode']) {
					$id_kriteria = $this->conn->GetOne("select id_kriteria 
					from mt_kriteria 
					where id_kategori = " . $this->conn->escape($id_kategori) . "
					and id_kriteria_parent = " . $this->conn->escape($id_kriteria_parent2) . " 
					and kode = " . $this->conn->escape($record['kode']));
					if ($id_kriteria)
						$this->conn->goUpdate("mt_kriteria", $record, "id_kriteria=" . $this->conn->escape($id_kriteria));
					else
						$id_kriteria = $this->model->tambah_kriteria($record);

					$id_kriteria_parent3 = $id_kriteria;
				}


				$record = array();
				$record['id_interval'] = 20;
				$record['id_kategori'] = $id_kategori;
				$record['id_kriteria_parent'] = $id_kriteria_parent3;
				$record['is_aktif'] = 1;
				if ($this->id_kategori_jenis == 2 || $this->id_kategori_jenis == 1) {
					if ($this->id_kategori_jenis == 2)
						$record['kode_lvl'] = (string)$sheet->getCell('G' . $row)->getValue();
					$record['kode'] = (string)$sheet->getCell('H' . $row)->getValue();
					$record['nama'] = (string)$sheet->getCell('I' . $row)->getValue();
					$record['d'] = (string)$sheet->getCell('J' . $row)->getValue();
					$record['k'] = (string)$sheet->getCell('K' . $row)->getValue();
					$record['w'] = (string)$sheet->getCell('L' . $row)->getValue();
					$record['o'] = (string)$sheet->getCell('M' . $row)->getValue();
					$record['is_aktif'] = (string)$sheet->getCell('N' . $row)->getValue();
				}
				if ($record['kode']) {
					$id_kriteria = $this->conn->GetOne("select id_kriteria 
					from mt_kriteria 
					where id_kategori = " . $this->conn->escape($id_kategori) . "
					and id_kriteria_parent = " . $this->conn->escape($id_kriteria_parent3) . " 
					and kode = " . $this->conn->escape($record['kode']));
					if ($id_kriteria)
						$this->conn->goUpdate("mt_kriteria", $record, "id_kriteria=" . $this->conn->escape($id_kriteria));
					else
						$id_kriteria = $this->model->tambah_kriteria($record);
				}
			}

			if ($id_kriteria) {
				$return['success'] = "Import berhasil";
				$this->model->conn->trans_commit();
				SetFlash('suc_msg', $return['success']);
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

	public function HeaderExport()
	{
		$ret = [];
		if ($this->id_kategori_jenis == 1 || $this->id_kategori_jenis == 2) {
			$ret = array(
				array(
					'name' => 'no_indikator',
					'label' => 'No.',
				),
				array(
					'name' => 'indikator',
					'label' => 'Indikator',
				),
				array(
					'name' => 'no_paramater',
					'label' => 'No. ',
				),
				array(
					'name' => 'uraian_paramater',
					'label' => 'Paramater',
				)
			);
		}
		if ($this->id_kategori_jenis == 1) {
			$ret[] =
				array(
					'name' => 'bobot_paramater',
					'label' => 'Bobot Paramater',
				);
		}
		if ($this->id_kategori_jenis == 1 || $this->id_kategori_jenis == 2) {
			$ret[] =
				array(
					'name' => 'no_sub_paramater',
					'label' => 'No. ',
				);
			$ret[] =
				array(
					'name' => 'sub_paramater',
					'label' => 'FUK',
				);
		}
		if ($this->id_kategori_jenis == 1) {
			$ret[] =
				array(
					'name' => 'no_sub_fuk',
					'label' => 'No. ',
				);
			$ret[] =
				array(
					'name' => 'sub_fuk',
					'label' => 'Sub FUK',
				);
		}
		if ($this->id_kategori_jenis == 2) {
			$ret[] =
				array(
					'name' => 'level',
					'label' => 'Level',
				);
		}
		if ($this->id_kategori_jenis == 2) {
			$ret[] =
				array(
					'name' => 'no_up',
					'label' => 'No. ',
				);
			$ret[] =
				array(
					'name' => 'up',
					'label' => 'UP',
				);
		}
		if ($this->id_kategori_jenis == 3) {
			$ret = array(
				array(
					'name' => 'no_key_process_area',
					'label' => 'No.',
				),
				array(
					'name' => 'key_process_area',
					'label' => 'Key Process Area',
				),
				array(
					'name' => 'no_penjelasan_key_process_area',
					'label' => 'No. ',
				),
				array(
					'name' => 'penjelasan_key_process_area',
					'label' => 'Penjelasan Key Process Area',
				),
				array(
					'name' => 'level',
					'label' => 'Level',
				),
				array(
					'name' => 'no_uraian',
					'label' => 'No. ',
				),
				array(
					'name' => 'uraian',
					'label' => 'Uraian/Pernyataan',
				),
				array(
					'name' => 'penjelasan',
					'label' => 'Penjelasan Pernyataan',
				),
				array(
					'name' => 'contoh_output',
					'label' => 'Contoh Output/Infrastruktur',
				),
				array(
					'name' => 'daftar_uji',
					'label' => 'Daftar Uji',
				)
			);
		}
		$ret[] =
			array(
				'name' => 'd',
				'label' => 'D',
			);
		$ret[] =
			array(
				'name' => 'k',
				'label' => 'K',
			);
		$ret[] =
			array(
				'name' => 'w',
				'label' => 'W',
			);
		$ret[] =
			array(
				'name' => 'o',
				'label' => 'O',
			);

		$ret[] = array(
			'name' => 'aktif',
			'label' => 'Aktif',
		);
		// dpr($ret, 1);
		return $ret;
	}

	public function export_list($id_kategori = null)
	{
		$this->load->library('PHPExcel');
		$this->load->library('Factory');
		$excel = new PHPExcel();
		$excel->setActiveSheetIndex(0);
		$excelactive = $excel->getActiveSheet();

		$this->_beforeDetail($id_kategori);

		#header export

		$header = $this->HeaderExport();

		$row = 1;
		$col = null;

		foreach ($header as $r) {
			if (!$col)
				$col = 'A';
			else
				$col++;

			$excelactive->setCellValue($col . $row, $r['label']);
		}

		$excelactive->getStyle('A1:' . $col . $row)->getFont()->setBold(true);
		$excelactive
			->getStyle('A1:' . $col . $row)
			->getFill()
			->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			->getStartColor()
			->setARGB('6666ff');

		#data
		$this->load->model('model');
		$arearr = $this->model->get_kriteria($_SESSION[SESSION_APP][$this->page_ctrl][$id_kategori]['id_parent']);

		$row++;
		if (isset($arearr))
			foreach ($arearr as $r) {
				$excelactive->setCellValue("A" . $row, $r['kode']);
				$excelactive->setCellValue("B" . $row, $r['nama']);

				if ($r['sub1'])
					foreach ($r['sub1'] as  $r1) {
						$excelactive->setCellValue("C" . $row, $r1['kode']);
						$excelactive->setCellValue("D" . $row, $r1['nama']);
						if ($this->id_kategori_jenis == 1)
							$excelactive->setCellValue("E" . $row, $r1['bobot']);

						if ($r1['sub2'])
							foreach ($r1['sub2'] as  $r2) {
								if ($this->id_kategori_jenis == 1) {
									$excelactive->setCellValue("F" . $row, $r2['kode']);
									$excelactive->setCellValue("G" . $row, $r2['nama']);
									if ($r2['sub3']) {
										foreach ($r2['sub3'] as  $r3) {
											$excelactive->setCellValue("H" . $row, $r3['kode']);
											$excelactive->setCellValue("I" . $row, $r3['nama']);
											$excelactive->setCellValue("J" . $row, $r3['d']);
											$excelactive->setCellValue("K" . $row, $r3['k']);
											$excelactive->setCellValue("L" . $row, $r3['w']);
											$excelactive->setCellValue("M" . $row, $r3['o']);
											$excelactive->setCellValue("N" . $row, $r3['is_aktif']);
											$row++;
										}
									} else {
										$excelactive->setCellValue("J" . $row, $r2['d']);
										$excelactive->setCellValue("K" . $row, $r2['k']);
										$excelactive->setCellValue("L" . $row, $r2['w']);
										$excelactive->setCellValue("M" . $row, $r2['o']);
										$excelactive->setCellValue("N" . $row, $r2['is_aktif']);
										$row++;
									}
								} else if ($this->id_kategori_jenis == 2) {
									$excelactive->setCellValue("E" . $row, $r2['kode']);
									$excelactive->setCellValue("F" . $row, $r2['nama']);
									if ($r2['sub3']) {
										foreach ($r2['sub3'] as  $r3) {
											$excelactive->setCellValue("G" . $row, $r2['kode_lvl']);
											$excelactive->setCellValue("H" . $row, $r3['kode']);
											$excelactive->setCellValue("I" . $row, $r3['nama']);
											$excelactive->setCellValue("J" . $row, $r3['d']);
											$excelactive->setCellValue("K" . $row, $r3['k']);
											$excelactive->setCellValue("L" . $row, $r3['w']);
											$excelactive->setCellValue("M" . $row, $r3['o']);
											$excelactive->setCellValue("N" . $row, $r3['is_aktif']);
											$row++;
										}
									}
								} else if ($this->id_kategori_jenis == 3) {
									$excelactive->setCellValue("E" . $row, $r2['kode_lvl']);
									$excelactive->setCellValue("F" . $row, $r2['kode']);
									$excelactive->setCellValue("G" . $row, $r2['nama']);
									$excelactive->setCellValue("H" . $row, $r2['keterangan']);
									$excelactive->setCellValue("I" . $row, $r2['keterangan1']);
									$excelactive->setCellValue("J" . $row, $r2['keterangan2']);
									$excelactive->setCellValue("K" . $row, $r2['d']);
									$excelactive->setCellValue("L" . $row, $r2['k']);
									$excelactive->setCellValue("M" . $row, $r2['w']);
									$excelactive->setCellValue("N" . $row, $r2['o']);
									$excelactive->setCellValue("O" . $row, $r2['is_aktif']);
									$row++;
								}
							}
						else
							$row++;
					}
				else
					$row++;
			}


		$objWriter = Factory::createWriter($excel, 'Excel5');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . 'Kriteria' . date('Ymd') . '.xls"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
		exit();
	}

	public function Delete($id_kategori = null, $id = null)
	{

		$id = urldecode($id);
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
		} else {
			SetFlash('err_msg', "Data gagal didelete");
		}

		redirect("$this->page_ctrl/index/$id_kategori");
	}
}
