<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Mt_sdm_unit extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/mt_sdm_unitlist";
		$this->viewdetail = "panelbackend/mt_sdm_unitdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Unit';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Unit';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Unit';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Unit';
		}

		$this->data['width'] = "800px";

		$this->load->model("Mt_sdm_unitModel", "model");
		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'upload', 'treetable', 'select2'
		);
	}

	public function Index($page = 0)
	{
		$this->layout = "panelbackend/layout2";

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

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {

			$this->_isValid($record, false);

			$this->_beforeEdit($record, $id);

			$this->_setLogRecord($record, $id);

			$this->model->conn->StartTrans();
			if (trim($id)) {

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
					$id = $record[$this->pk];
				}

				if ($return['success']) {

					$this->log("menambah", $record);

					$return1 = $this->_afterInsert($id);

					if (!$return1) {
						$return = false;
					}
				}
			}

			$id = urlencode($record[$this->pk]);

			$this->model->conn->CompleteTrans();

			if ($return['success']) {

				$this->_afterEditSucceed($id);

				SetFlash('suc_msg', $return['success']);
				redirect("$this->page_ctrl/detail/$id");
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
			// array(
			// 	'name'=>'kode_distrik', 
			// 	'label'=>'Kode Distrik', 
			// 	'width'=>"auto",
			// 	'type'=>"char",
			// ),
			array(
				'name' => 'table_code',
				'label' => 'Kode',
				'width' => "auto",
				'type' => "char",
			),
			array(
				'name' => 'table_desc',
				'label' => 'Nama',
				'width' => "auto",
				'type' => "char",
			),
			array(
				'name' => 'is_aktif',
				'label' => 'Aktif',
				'width' => "auto",
				'type' => "list",
				'value' => ['' => '', '1' => 'Aktif', '0' => 'Non Aktif']
			),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'table_code' => $this->post['table_code'],
			'table_desc' => $this->post['table_desc'],
			'kode_distrik' => $this->post['kode_distrik'],
			'is_aktif' => (int)$this->post['is_aktif'],
		);
	}

	protected function Rules()
	{
		return array(
			"table_code" => array(
				'field' => 'table_code',
				'label' => 'Table Code',
				'rules' => "required|is_unique[mt_sdm_unit.table_code]|max_length[18]",
			),
			"table_desc" => array(
				'field' => 'table_desc',
				'label' => 'Table Desc',
				'rules' => "required|max_length[100]",
			),
		);
	}

	public function HeaderExport()
	{
		return array(
			array(
				'name' => 'table_desc',
				'label' => 'Nama',
				'width' => "auto",
				'type' => "char",
			),
			array(
				'name' => 'is_aktif',
				'label' => 'Aktif',
				'width' => "auto",
				'type' => "list",
				'value' => ['' => '', '1' => 'Aktif', '0' => 'Non Aktif']
			),
		);
	}

	public function import_list()
	{

		$file_arr = array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'application/wps-office.xls', 'application/wps-office.xlsx');

		// $this->conn->debug=1;
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
					'name' => "table_code"
				)
			);
			$header = array_merge($header, $this->HeaderExport());

			for ($row = 2; $row <= (int)$highestRow; $row++) {
				// dpr($highestRow);

				$col = 'A';
				$record = array();
				foreach ($header as $r1) {
					if ($sheet->getCell($col . $row)->getValue() !== null)
						if ($r1['type'] == 'list')
							$record[$r1['name']] = (string) $sheet->getCell($col . $row)->getValue();
						elseif ($r1['type'] == 'listinverst') {
							$rk = strtolower(trim((string) $sheet->getCell($col . $row)->getValue()));
							$arr = array();
							foreach ($r1['value'] as $idkey => $value) {
								$arr[strtolower(trim($value))] = $idkey;
							}
							$record[$r1['name_ori']] = (string) $arr[$rk];
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
							$record[$r1['name']] = (string)$sheet->getCell($col . $row)->getValue();

					$col++;
				}

				// dpr($record);
				$this->data['row'] = $record;

				// $error = $this->_isValidImport($record);
				$error = $this->_isValid($record, false);
				if ($error) {
					$return['error'] = $error;
				} else {
					$old = $this->conn->GetOne("select * from mt_sdm_unit where deleted_date is null and " . $this->model->pk . '=' . $this->conn->escape((string)$record[$this->model->pk]));
					// if ($record[$this->model->pk]) {
					if ($old) {
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


			if (!$return['error'] && $return['success']) {
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
		// die;

		echo json_encode($return);
	}
}
