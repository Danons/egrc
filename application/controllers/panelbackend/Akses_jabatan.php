<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Akses_jabatan extends _adminController
{

	public $limit = -1;
	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/akses_jabatanlist";
		$this->viewdetail = "panelbackend/akses_jabatandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";
		$this->viewdetail = "panelbackend/akses_jabatan_prints";

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

		$this->load->model("Temp_pegawaiModel", "model");

		$this->load->model("Public_sys_groupModel", "group");
		$this->data['grouparr'] = $this->group->GetCombo();

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker'
		);

		$this->access_role['list_print'] = true;
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'nama_jabatan',
				'label' => 'Nama Jabatan',
				'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'nik',
				'label' => 'NIK',
				'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'nama',
				'label' => 'Nama',
				'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'nama_unitkerja',
				'label' => 'Nama Unitkerja',
				'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'nama_sub_unitkerja',
				'label' => 'Nama Sub Unitkerja',
				'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'nama_bidang',
				'label' => 'Nama Bidang',
				'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'nama_sub_bidang',
				'label' => 'Nama Sub Bidang',
				'width' => "auto",
				'type' => "varchar2",
			),
		);
	}

	public function Index($page = 0)
	{

		if ($this->post['group_id'])
			$_SESSION[SESSION_APP][$this->page_ctrl]['group_id'] = $this->post['group_id'];

		$this->data['row']['group_id'] = $_SESSION[SESSION_APP][$this->page_ctrl]['group_id'];

		$this->data['header'] = $this->Header();

		$this->_setFilter("kode_jabatan in (select position_id from mt_sdm_jabatan where deleted_date is null and position_id is not null)");

		$this->data['list'] = $this->_getList($page);

		$ret = true;

		if ($this->access_role['save'] && $this->post['act'] == 'save') {

			if (!$this->post['group_id'])
				$ret = false;

			foreach ($this->data['list']['rows'] as $r) {
				if (!$ret)
					break;

				$nik = $r['nik'];
				$group_id = $this->post['group_id'];

				$record = array();
				$record['username'] = $nik;
				$record['name'] = $r['nama'];
				$record['email'] = $r['email'];
				$record['nid'] = $nik;
				$record['is_manual'] = 1;
				$record['is_active'] = 1;
				$record['is_notification'] = 1;
				$record['tgl_mulai_aktif'] = date("Y-m-d");

				$user_id = $this->conn->GetOne("select user_id from public_sys_user where deleted_date is null and nid = " . $this->conn->escape($nik));
				if (!$user_id) {
					$record['password'] = sha1(md5($record['nid']));
					$ret = $this->conn->goInsert("public_sys_user", $record);
					$user_id = $this->conn->GetOne("user_id from public_sys_user where deleted_date is null and nid = " . $this->conn->escape($nik));
				} else {
					$ret = $this->conn->goUpdate("public_sys_user", $record, "user_id = " . $this->conn->escape($user_id));
				}

				if ($user_id) {
					$id_jabatan = $this->conn->GetOne("select id_jabatan 
					from mt_sdm_jabatan 
					where deleted_date is null and position_id = " . $this->conn->escape($r['kode_jabatan']));

					if (!$this->post['nik'][$nik]) {
						$this->conn->Execute("update public_sys_user_group set deleted_date = now()  
						where user_id = " . $this->conn->escape($user_id) . " 
						and group_id = " . $this->conn->escape($group_id) . " 
						and id_jabatan = " . $this->conn->escape($id_jabatan));
					} else {
						$cek = $this->conn->GetOne("select 1 from public_sys_user_group 
						where deleted_date is null user_id = " . $this->conn->escape($user_id) . " 
						and group_id = " . $this->conn->escape($group_id) . " 
						and id_jabatan = " . $this->conn->escape($id_jabatan));
						if (!$cek) {
							$this->conn->Execute("update public_sys_user_group set deleted_date = now()  
							where user_id = " . $this->conn->escape($user_id) . " 
							and group_id = " . $this->conn->escape($group_id));

							$this->conn->goInsert(
								"public_sys_user_group",
								[
									"user_id" => $user_id,
									"group_id" => $group_id,
									"id_jabatan" => $id_jabatan
								]
							);
						}
					}
				}
			}

			if ($ret)
				SetFlash('suc_msg', "Sukses");
			else
				SetFlash('err_msg', "Gagal");

			redirect(current_url());
		}


		$this->data['row']['nik'] = $this->post['nik'];

		if (empty($this->data['row']['nik']) or $this->post['act'] == 'set_value') {
			$this->data['row']['group_id'] = $_SESSION[SESSION_APP][$this->page_ctrl]['group_id'];
			$this->data['row']['nik'] = array();
			$rows = $this->conn->GetArray("select b.nid as nik 
			from public_sys_user_group a 
			join public_sys_user b on b.user_id = a.user_id
			where a.deleted_date is null and a.group_id = " . $this->conn->escape($this->data['row']['group_id']));
			foreach ($rows as $r) {
				$this->data['row']['nik'][$r['nik']] = 1;
			}
		}

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

	public function go_print()
	{
		if (!$_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'] && !$_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] && !$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']) {
			$this->_setFilter("1=2");
		}

		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";
		$this->data['no_header'] = true;

		$this->data['page_title'] = 'Akses ' . $this->data['grouparr'][$_SESSION[SESSION_APP][$this->page_ctrl]['group_id']];

		$this->data['row']['group_id'] = $_SESSION[SESSION_APP][$this->page_ctrl]['group_id'];
		$this->data['row']['nik'] = array();
		$rows = $this->conn->GetArray("select nik from public_sys_user where deleted_date and is_manual = 0 and is_active=1 and group_id = " . $this->conn->escape($this->data['row']['group_id']));
		foreach ($rows as $r) {
			$this->data['row']['nik'][$r['nik']] = 1;
		}

		$this->data['header'] = $this->Header();

		$this->data['list'] = $this->_getListPrint();

		$this->View($this->viewprint);
	}
}
