<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Public_sys_user extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/public_sys_userlist";
		$this->viewdetail = "panelbackend/public_sys_userdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah User';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit User';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail User';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar User';
		}

		$this->load->model("Public_sys_userModel", "model");

		$this->load->model("Public_sys_groupModel", "publicsysgroup");
		$this->load->model("Mt_sdm_jabatanModel", "mtsdmjabatan");
		$publicsysgroup = $this->publicsysgroup;
		$rsmtsdmjabatan = $this->mtsdmjabatan->GArray();

		$rspublicsysgroup = $publicsysgroup->GArray();

		$publicsysgrouparr = array('' => '');
		foreach ($rspublicsysgroup as $row) {
			$publicsysgrouparr[$row['group_id']] = $row['name'];
		}
		$mtsdmjabatanarr = array('' => '');
		foreach ($rsmtsdmjabatan as $row) {
			$mtsdmjabatanarr[$row['id_jabatan']] = $row['nama'];
		}

		// dpr($mtsdmjabatanarr, 1);
		$this->data['publicsysgrouparr'] = $publicsysgrouparr;
		$this->data['mtsdmjabatanarr'] = $mtsdmjabatanarr;
		/*
		$this->load->model("Mt_sdm_jabatanModel","mtsdmjabatan");
		$this->data['jabatanarr'] = array(''=>'')+$this->mtsdmjabatan->GetCombo();*/


		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'select2', 'datepicker', 'upload'
		);
	}

	public function Index($page = 0)
	{
		$this->layout = "panelbackend/layout2";

		$this->data['header'] = $this->Header();

		$this->_setFilter('a.deleted_date is null ');
		// $this->conn->debug = 1;
		$this->data['list'] = $this->_getList($page);
		// die();


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

	protected function _afterDetail($id = null)
	{

		/*$nid = $this->data['row']['nid'];
		$this->load->model("Mt_sdm_karyawanModel","mtpegawai");
		$this->data['nidarr'][$nid] = $this->mtpegawai->GOne("nama","where nid = ".$this->conn->qstr($nid));*/

		if (!$this->data['row']['group'])
			$this->data['row']['group'] = $this->conn->GetArray("select * from public_sys_user_group where deleted_date is null and user_id = " . $this->conn->escape($id));

		if (!$this->data['row']['group'])
			$this->data['row']['group'][] = $this->data['row'];

		$this->data['jabatanarr'][''] = '';
		foreach ($this->data['row']['group'] as $r) {
			$this->data['jabatanarr'][$r['id_jabatan']] = $this->conn->GetOne("select nama from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($r['id_jabatan']));
		}
	}

	public function Edit($id = null)
	{

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

		// dpr($record, 1);
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

	protected function _afterUpdate($id)
	{
		return $this->_afterInsert($id);
	}

	protected function _afterInsert($id)
	{
		$ret = true;

		// if (!empty($this->post['group'])) {
		$ret = $this->conn->Execute("delete from public_sys_user_group where user_id =" . $this->conn->escape($id));

		foreach ($this->post['group'] as $k => $v) {
			if (!$ret)
				break;

			if (!$v['group_id'])
				continue;

			$ret = $this->conn->goInsert(
				"public_sys_user_group",
				array("user_id" => $id, "group_id" => $v['group_id'], "id_jabatan" => $v['id_jabatan'])
			);
		}
		// }

		return $ret;
	}

	protected function Header()
	{
		$this->_setFilter("is_manual = '1'");
		return array(
			array(
				'name' => 'username',
				'label' => 'NID/Username',
				'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'name',
				'label' => 'Name',
				'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'id_jabatan',
				'label' => 'Nama Jabatan',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['mtsdmjabatanarr'],
			),
			array(
				'name' => 'group_id',
				'label' => 'Group',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['publicsysgrouparr'],
			),

			/*	array(
				'name'=>'last_ip', 
				'label'=>'Last IP', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'last_login', 
				'label'=>'Last Login', 
				'width'=>"auto",
				'type'=>"number",
			),*/
			array(
				'name' => 'is_active',
				'label' => 'Active',
				'width' => "auto",
				'type' => "list",
				'value' => array('' => '-', '0' => 'Tidak', '1' => 'Iya'),
			),
		);
	}

	protected function Record($id = null)
	{
		$return = array(
			'nid' => $this->post['nid'],
			'email' => $this->post['email'],
			'username' => $this->post['username'],
			'group_id' => $this->post['group_id'],
			'id_jabatan' => $this->post['id_jabatan'],
			'name' => $this->post['name'],
			'last_ip' => $this->post['last_ip'],
			'last_login' => $this->post['last_login'],
			'tgl_mulai_aktif' => $this->post['tgl_mulai_aktif'],
			'tgl_selesai_aktif' => $this->post['tgl_selesai_aktif'],
			'is_notification' => (int)$this->post['is_notification'],
			'is_active' => 1
		);

		if (!$id or ($id && $this->post['password'])) {
			$return['password'] = sha1(md5($this->post['password']));
		}

		return $return;
	}

	protected function Rules()
	{
		$return = array(
			/*	"nid"=>array(
				'field'=>'nid', 
				'label'=>'NID', 
				'rules'=>"required|max_length[100]",
			),*/
			"username" => array(
				'field' => 'username',
				'label' => 'NID',
				'rules' => "is_unique[public_sys_user.username]|required|max_length[100]",
			),
			"email" => array(
				'field' => 'email',
				'label' => 'Email',
				'rules' => "required|valid_email|max_length[200]",
			),
			/*	"id_jabatan"=>array(
				'field'=>'id_jabatan', 
				'label'=>'Jabatan', 
				'rules'=>"required|in_list[".implode(",", array_keys($this->data['jabatanarr']))."]|max_length[10]",
			),*/
			// "group_id" => array(
			// 	'field' => 'group_id',
			// 	'label' => 'Group ID',
			// 	'rules' => "required|in_list[" . implode(",", array_keys($this->data['publicsysgrouparr'])) . "]|max_length[10]",
			// ),
			"name" => array(
				'field' => 'name',
				'label' => 'Name',
				'rules' => "required|max_length[200]",
			),
			"confirmpassword" => array(
				'field' => 'confirmpassword',
				'label' => 'Confirm Password',
				'rules' => "max_length[100]|matches[password]",
			),
		);

		if ($this->data['row'][$this->pk]) {
			$return["password"] = array(
				'field' => 'password',
				'label' => 'Password',
				'rules' => "max_length[100]",
			);
		} else {
			$return["password"] = array(
				'field' => 'password',
				'label' => 'Password',
				'rules' => "required|max_length[100]",
			);
		}

		return $return;
	}


	protected function _beforeDelete($id = null)
	{
		$cek = $this->conn->GetOne("select 1 from risk_task 
			where deleted_date is null and untuk = " . $this->conn->escape($id));

		if ($cek) {
			SetFlash('err_msg', "Data sudah terpakai di tabel task silahkan hubungi Admin Database apabila Anda ingin menghapus");
			redirect("$this->page_ctrl/detail/$id");
			die();
		}

		$cek = $this->conn->GetOne("select 1 from risk_log 
			where deleted_date is null and created_by = " . $this->conn->escape($id));

		if ($cek) {
			SetFlash('err_msg', "Data sudah terpakai di tabel log silahkan hubungi Admin Database apabila Anda ingin menghapus");
			redirect("$this->page_ctrl/detail/$id");
			die();
		}

		$cek = $this->conn->GetOne("select 1 from risk_msg_penerima 
			where deleted_date is null and id_user = " . $this->conn->escape($id));

		if ($cek) {
			SetFlash('err_msg', "Data sudah terpakai di tabel penerima pesan silahkan hubungi Admin Database apabila Anda ingin menghapus");
			redirect("$this->page_ctrl/detail/$id");
			die();
		}

		$cek = $this->conn->GetOne("select 1 from public_sys_user_group 
			where deleted_date is null and user_id = " . $this->conn->escape($id));

		if ($cek) {
			SetFlash('err_msg', "Silahkan hapus group usernya terlebih dahulu");
			redirect("$this->page_ctrl/detail/$id");
			die();
		}

		return true;
	}

	public function import_list()
	{

		$file_arr = array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'application/wps-office.xls', 'application/wps-office.xlsx');

		if (in_array($_FILES['importupload']['type'], $file_arr)) {

			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters("", "");

			$this->load->library('Factory');
			$inputFileType = Factory::identify($_FILES['importupload']['tmp_name']);
			$objReader = Factory::createReader($inputFileType);
			$excel = $objReader->load($_FILES['importupload']['tmp_name']);
			$sheet = $excel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			// $this->model->conn->StartTrans();

			#header export
			$header = array(
				array(
					'name' => $this->model->pk
				)
			);
			$header = array_merge($header, $this->HeaderExport());
			$header[] = array(
				'name' => 'password'
			);
			$header[] = array(
				'name' => 'tgl_mulai_aktif'
			);
			$header[] = array(
				'name' => 'tgl_selesai_aktif'
			);
			$header[] = array(
				'name' => 'email'
			);
			for ($row = 2; $row <= $highestRow; $row++) {
				$this->model->conn->StartTrans();

				$col = 'A';

				$record = array();
				foreach ($header as $r1) {
					if ($r1['type'] == 'list')
						$record[$r1['name']] = (string) $sheet->getCell($col . $row)->getValue();
					elseif ($r1['type'] == 'listinverst') {
						$rk = strtolower(trim((string) $sheet->getCell($col . $row)->getValue()));
						$arr = array();
						foreach ($r1['value'] as $key => $value) {
							$arr[strtolower(trim($value))] = $key;
						}
						$record[$r1['name_ori']] = (string) $arr[$rk];
					} else
						$record[$r1['name']] = $sheet->getCell($col . $row)->getValue();

					$col++;
				}

				$this->data['row'] = $record;

				$error = $this->_isValidImport1($record);
				if ($record['password'])
					$record['password'] = sha1(md5($record['password']));

				if ($record['is_active'] !== '1') {
					$record['tgl_selesai_aktif'] = $record['tgl_mulai_aktif'];
				} else {
					$record['tgl_selesai_aktif'] = $record['tgl_selesai_aktif'];
				}

				// dpr($record['is_active']);
				// dpr($record);
				// dpr($error);
				foreach ($record as $key => $val) {
					$record[$key] = trim($val);
					if ($key == 'id_jabatan' || $key == 'group_id')
						$record[$key] = (int)$val;
				}

				// dpr($record);
				if ($error) {
					// dpr($error);
					$return['error'] = $error;
				} else {
					if ($record[$this->model->pk]) {
						$return = $this->model->Update($record, $this->model->pk . "=" . $record[$this->model->pk]);
						$id = $record[$this->model->pk];

						if ($return['success']) {
							// $ret = $this->_afterUpdate($id);
							$cekgrup = $this->conn->GetArray("select * from public_sys_user_group where deleted_date is null and user_id = " . $this->conn->escape($id)
								. " and group_id = " . $this->conn->escape(trim($record['group_id'])) . " and id_jabatan = " . $this->conn->escape(trim($record['id_jabatan'])));

							// dpr($cekgrup, 1);
							if (!$cekgrup) {
								$ret = $this->conn->goInsert(
									"public_sys_user_group",
									array("user_id" => $id, "group_id" => trim($record['group_id']), "id_jabatan" => trim($record['id_jabatan']))
								);

								if (!$ret) {
									$return['success'] = false;
									$return['error'] = "Gagal update";
								}
							}
						}
					} else {
						// $this->conn->GetArray("select * from public_sys_user_group where user_id = " . $this->conn->escape($id));
						$cek = $this->conn->GetArray("select * from public_sys_user where deleted_date is null and username = " . $this->conn->escape($record['username']) . " limit 1");
						// dpr($cek);
						if (!$cek) {
							$return = $this->model->Insert($record);
							$id = $return['data'][$this->model->pk];
						} else {

							$id = $cek[0][$this->model->pk];
							// dpr($id);
							unset($record['user_id']);
							$return = $this->model->Update($record, $this->model->pk . "=" . $id);
							// dpr($return);
						}


						// $id = $return['data'][$this->model->pk];

						if ($return['success']) {

							$cekgrup = $this->conn->GetArray("select * from public_sys_user_group where deleted_date is null and user_id = " . $this->conn->escape($id)
								. " and group_id = " . $this->conn->escape(trim($record['group_id'])) . " and id_jabatan = " . $this->conn->escape(trim($record['id_jabatan'])));

							// if ($record['id_jabatan'] && $record['group_id'])
							if (!$cekgrup && $record['id_jabatan'] && $record['group_id']) {
								$ret = $this->conn->goInsert(
									"public_sys_user_group",
									array("user_id" => $id, "group_id" => (int)$record['group_id'], "id_jabatan" => (int)$record['id_jabatan'])
								);

								if (!$ret) {
									$return['success'] = false;
									$return['error'] = "Gagal insert";
								}
							}
						}
					}
				}

				if (!$return['error'] && $return['success']) {
					$this->model->conn->trans_commit();
					SetFlash('suc_msg', $return['success']);
				} else {
					$this->model->conn->trans_rollback();
					$return['error'] = "Gagal import. " . $return['error'];
					$return['success'] = false;
				}

				// if (!$return['success'])
				// 	break;
			}

			// if (!$return['error'] && $return['success']) {
			// 	$this->model->conn->trans_commit();
			// 	SetFlash('suc_msg', $return['success']);
			// } else {
			// 	$this->model->conn->trans_rollback();
			// 	$return['error'] = "Gagal import. " . $return['error'];
			// 	$return['success'] = false;
			// }
		} else {
			$return['error'] = "Format file tidak sesuai";
		}

		echo json_encode($return);
	}

	protected function _isValidImport1($record)
	{
		$this->data['rules'] = $this->Rules();

		$rules = array_values($this->data['rules']);

		unset($rules[0]['rules']);
		unset($rules[3]);
		// dpr($rules, 1);
		if ($record) {
			$this->form_validation->set_data($record);
		}

		$this->form_validation->set_rules($rules);
		// dpr($this->form_validation->run());
		if (count($rules) && $this->form_validation->run() == FALSE) {
			// dpr(validation_errors());
			return validation_errors();
		}
	}


	public function export_list()
	{
		$this->load->library('PHPExcel');
		$this->load->library('Factory');
		$excel = new PHPExcel();
		$excel->setActiveSheetIndex(0);
		$excelactive = $excel->getActiveSheet();


		#header export
		$header = array(
			array(
				'name' => $this->model->pk
			)
		);
		$header = array_merge($header, $this->HeaderExport());

		$header[] = array(
			'name' => 'password'
		);
		$header[] = array(
			'name' => 'tgl_mulai_aktif'
		);
		$header[] = array(
			'name' => 'tgl_selesai_aktif'
		);
		$header[] = array(
			'name' => 'email'
		);
		// $header[] = array(
		// 	'name' => 'confirmpassword'
		// );

		// dpr($header, 1);

		$row = 1;
		$col = null;
		foreach ($header as $r) {
			if (!$col)
				$col = 'A';
			else
				$col++;

			$excelactive->setCellValue($col . $row, $r['name']);
		}

		$excelactive->getStyle('A1:' . $col . $row)->getFont()->setBold(true);
		$excelactive
			->getStyle('A1:' . $col . $row)
			->getFill()
			->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			->getStartColor()
			->setARGB('6666ff');

		#data
		$respon = $this->model->SelectGrid(
			array(
				'limit' => -1,
				'order' => $this->_order(),
				'filter' => $this->_getFilter()
			)
		);
		$rows = $respon['rows'];

		// dpr($rows[0]);
		// dpr($header, 1);

		$row = 2;
		foreach ($rows as $r) {
			$col = 'A';
			foreach ($header as $r1) {
				if ($r1['type'] == 'listinverst') {
					$r[$r1['name']] = $r1['value'][$r[$r1['name_ori']]];
				}
				if ($r1['name'] !== 'password')
					$excelactive->setCellValue($col . $row, $r[$r1['name']]);
				$col++;
			}
			$row++;
		}


		$objWriter = Factory::createWriter($excel, 'Excel5');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $this->ctrl . date('Ymd') . '.xls"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
		exit();
	}

	/*
	public function Edit($id=null){

		if($this->post['act']=='reset'){
			redirect(current_url());
		}

		$this->_beforeDetail($id);

		$this->data['idpk'] = $id;

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'] && $id)
			$this->NoData();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("","");

		if($this->post && $this->post['act']<>'change'){
			if(!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id);

			$this->data['row'] = array_merge($this->data['row'],$record);
			$this->data['row'] = array_merge($this->data['row'],$this->post);
		}

		$this->data['jabatanarr'][$this->data['row']['id_jabatan']] = $this->conn->GetOne("select nama from mt_sdm_jabatan where id_jabatan = ".$this->conn->escape($this->data['row']['id_jabatan']));

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {

			$this->_isValid($record,false);

            $this->_beforeEdit($record,$id);

            $this->_setLogRecord($record,$id);

            $this->model->conn->StartTrans();
			if (trim($this->data['row'][$this->pk])==trim($id) && trim($id)) {

				$return = $this->_beforeUpdate($record, $id);

				if($return){
					$return = $this->model->Update($record, "$this->pk = ".$this->conn->qstr($id));
				}

				if ($return['success']) {

					$this->log("mengubah",$this->data['row']);

					$return1 = $this->_afterUpdate($id);

					if(!$return1){
						$return = false;
					}
				}
			}else {

				$return = $this->_beforeInsert($record);

				if($return){
					$return = $this->model->Insert($record);
					$id = $return['data'][$this->pk];
				}

				if ($return['success']) {

					$this->log("menambah",$record);

					$return1 = $this->_afterInsert($id);

					if(!$return1){
						$return = false;
					}
				}
			}

            $this->model->conn->CompleteTrans();

			if ($return['success']) {

				$this->_afterEditSucceed($id);

				SetFlash('suc_msg', $return['success']);
				redirect("$this->page_ctrl/detail/$id");

			} else {
				$this->data['row'] = array_merge($this->data['row'],$record);
				$this->data['row'] = array_merge($this->data['row'],$this->post);

				$this->_afterEditFailed($id);

				$this->data['err_msg'] = "Data gagal disimpan";
			}
		}

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}*/

	// public function Edit($id = null)
	// {
	// 	for ($row = 2; $row <= 10; $row++) {
	// 		$this->model->conn->StartTrans();

	// 		$return = $this->model->Insert($record);

	// 		$id = $return['data'][$this->model->pk];
	// 	}

	// 	if (!$return['error'] && $return['success']) {
	// 		$this->model->conn->trans_commit();
	// 		SetFlash('suc_msg', $return['success']);
	// 	} else {
	// 		$this->model->conn->trans_rollback();
	// 		$return['error'] = "Gagal import. " . $return['error'];
	// 		$return['success'] = false;
	// 	}
	// }

	protected function _getFilter()
	{
		$this->xss_clean = true;

		$this->FilterRequest();

		$filter_arr = array();

		if ($this->post['act'] == 'list_filter' && $this->post['list_filter']) {
			if (!$_SESSION[SESSION_APP][$this->page_ctrl]['list_filter']) {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'] = $this->post['list_filter'];
			} else {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'], $this->post['list_filter']);
			}
		}

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['list_filter']) {

			foreach ($_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'] as $r) {
				$idkey = $r['idkey'];
				$filter_arr1 = array();

				foreach ($r['values'] as $k => $v) {
					$k = str_replace("_____", ".", $k);

					replaceSingleQuote($v);
					replaceSingleQuote($k);
					if (!($v === '' or $v === null or $v === false))
						$filter_arr1[] = 'a.' . $idkey . " = '$v'";
				}

				$filter_str = implode(' or ', $filter_arr1);

				if ($filter_str) {
					$filter_arr[] = "($filter_str)";
				}
			}
		}

		if (!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']) {
			$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] = array();
		}

		if ($this->post['act'] == 'list_search' && $this->post['list_search_filter']) {
			if (!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']) {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] = $this->post['list_search_filter'];
			} else {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'], $this->post['list_search_filter']);
			}
		}

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']) {
			foreach ($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] as $k => $v) {
				$k = str_replace("_____", ".", $k);

				if (!($v === '' or $v === null or $v === false)) {
					replaceSingleQuote($v);
					replaceSingleQuote($k);
					if ($k == 'id_jabatan') {
						$k = "a.id_jabatan";
					}
					$filter_arr[] = "$k='$v'";
				}
			}
		}




		if (!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search']) {
			$_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] = array();
		}

		if ($this->post['act'] == 'list_search' && $this->post['list_search']) {

			if (!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search']) {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] = $this->post['list_search'];
			} else {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_search'], $this->post['list_search']);
			}
		}

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['list_search']) {
			foreach ($_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] as $k => $v) {
				$k = str_replace("_____", ".", $k);

				replaceSingleQuote($v);
				replaceSingleQuote($k);

				if (trim($v) !== '' && in_array($k, $this->arrNoquote)) {
					$filter_arr[] = "$k=$v";
				} else if ($v !== '') {
					$v = strtolower($v);
					$filter_arr[] = "lower($k) like '%$v%'";
				}
			}
		}

		$this->data['filter_arr'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_search'], $_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']);

		if (($filter_arr)) {
			$this->filter .= ' and ' . implode(' and ', $filter_arr);
		}

		return $this->filter;
	}
}
