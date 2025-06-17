<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Pemeriksaan_review_supervisi extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/pemeriksaan_review_supervisilist";
		$this->viewdetail = "panelbackend/pemeriksaan_review_supervisidetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_pemeriksaan";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Reviu Supervisi';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Reviu Supervisi';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Reviu Supervisi';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Reviu Supervisi';
		}

		$this->data['pelaksanaarr'] = $this->conn->GetList("select 
		user_id as idkey, name as val from public_sys_user a 
		where a.deleted_date is null and exists (
			select 1 from public_sys_user_group b 
			join public_sys_group_menu c on b.group_id = c.group_id 
			join public_sys_group_action d on c.group_menu_id = d.group_menu_id 
			join public_sys_action e on d.action_id = e.action_id 
			join public_sys_menu f on c.menu_id = f.menu_id
			where a.user_id = b.user_id and e.name='add' and f.url='panelbackend/pemeriksaan_temuan'
		)");
		// dpr($this->data['pelaksanaarr']);
		// die();

		$this->data['pimpinanarr'] = $this->conn->GetList("select 
		user_id as idkey, name as val from public_sys_user a 
		where a.deleted_date is null and exists (
			select 1 from public_sys_user_group b 
			join public_sys_group_menu c on b.group_id = c.group_id 
			join public_sys_group_action d on c.group_menu_id = d.group_menu_id 
			join public_sys_action e on d.action_id = e.action_id 
			join public_sys_menu f on c.menu_id = f.menu_id
			where a.user_id = b.user_id and e.name='pengawas' and f.url='panelbackend/pemeriksaan'
		)");

		$this->data['penanggungjawabarr'] = $this->conn->GetList("select 
		user_id as idkey, name as val from public_sys_user a 
		where a.deleted_date is null and exists (
			select 1 from public_sys_user_group b 
			join public_sys_group_menu c on b.group_id = c.group_id 
			join public_sys_group_action d on c.group_menu_id = d.group_menu_id 
			join public_sys_action e on d.action_id = e.action_id 
			join public_sys_menu f on c.menu_id = f.menu_id
			where a.user_id = b.user_id and e.name='penanggungjawab' and f.url='panelbackend/pemeriksaan'
		)");


		$this->load->model("Mt_pemeriksaan_kkaModel", "modelkka");
		$this->data['kkaarr'] = $this->modelkka->GetCombo();
		$this->load->model("Pemeriksaan_review_supervisiModel", "model");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'permsalahan',
				'label' => 'Permsalahan',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'id_kka',
				'label' => 'KKA',
				'width' => "auto",
				'type' => "number",
			),
			array(
				'name' => 'penyelesaian',
				'label' => 'Penyelesaian',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'is_persetujuan',
				'label' => 'Persetujuan',
				'width' => "auto",
				'type' => "list",
				'value' => array('' => '-pilih-', '0' => 'Tidak', '1' => 'Iya'),
			),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'permsalahan' => $this->post['permsalahan'],
			'id_kka' => $this->post['id_kka'],
			'penyelesaian' => $this->post['penyelesaian'],
			// 'is_persetujuan' => (int)$this->post['is_persetujuan'],
		);
	}

	protected function Rules()
	{
		return array(
			"permsalahan" => array(
				'field' => 'permsalahan',
				'label' => 'Permsalahan',
				'rules' => "required|max_length[2000]",
			),
			// "id_kka" => array(
			// 	'field' => 'id_kka',
			// 	'label' => 'KKA',
			// 	'rules' => "required|integer|max_length[10]",
			// ),
			"penyelesaian" => array(
				'field' => 'penyelesaian',
				'label' => 'Penyelesaian',
				'rules' => "max_length[2000]",
			),
			"is_persetujuan" => array(
				'field' => 'is_persetujuan',
				'label' => 'IS Persetujuan',
				'rules' => "integer|max_length[10]",
			),
		);
	}


	protected function _beforeDetail($id_pemeriksaan = null, $id_pemeriksaan_detail = null)
	{
		$this->load->model("PemeriksaanModel", 'pemeriksaan');
		$this->data['rowheader']  = $this->pemeriksaan->GetByPk($id_pemeriksaan);

		$this->load->model("Pemeriksaan_detailModel", 'pemeriksaan_detail');
		$this->data['rowheader1']  = $this->pemeriksaan_detail->GetByPk($id_pemeriksaan_detail);

		if (!$this->data['rowheader'])
			$this->NoData();

		$jenis = $this->data['rowheader']['jenis'];

		$this->data['jenis_title'] = " " . [
			"operasional" => "Operasional",
			"mutu" => "Mutu Internal",
			"penyuapan" => "Anti Penyuapan",
			"khusus" => "Khusus",
			"eksternal" => "Eksternal"
		][$jenis];

		$this->data['add_param'] .= $id_pemeriksaan . "/" . $id_pemeriksaan_detail;
	}

	public function Index($id_pemeriksaan = null, $id_pemeriksaan_detail = null, $page = 0)
	{
		redirect("panelbackend/pemeriksaan_detail/detail/$id_pemeriksaan/$id_pemeriksaan_detail");
		return;
		$this->_beforeDetail($id_pemeriksaan);

		if ($this->post['act'] == 'save') {
			// $this->conn->debug = 1;
			$id_review_supervisi = $this->post['idkey'];
			$is_persetujuan = $this->post['is_persetujuan'][$id_review_supervisi];
			$this->model->Update(["is_persetujuan" => (int)$is_persetujuan], "id_review_supervisi = " . $this->conn->escape($id_review_supervisi));

			// dpr($this->post,1);
		}
		$this->limit = -1;
		$this->data['header'] = $this->Header();

		$this->_setFilter("id_pemeriksaan = " . $this->conn->qstr($id_pemeriksaan));
		$this->data['list'] = $this->_getList($page);

		$this->data['page'] = $page;

		$param_paging = array(
			'base_url' => base_url("{$this->page_ctrl}/index/$id_pemeriksaan"),
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

	public function Delete($id_pemeriksaan = null, $id_pemeriksaan_detail = null, $id = null)
	{

		$this->model->conn->StartTrans();

		$this->_beforeDetail($id_pemeriksaan, $id_pemeriksaan_detail, $id);

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
			redirect("$this->page_ctrl/index/$id_pemeriksaan/$id_pemeriksaan_detail");
		} else {
			SetFlash('err_msg', "Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id_pemeriksaan/$id_pemeriksaan_detail/$id");
		}
	}

	public function Detail($id_pemeriksaan = null, $id = null)
	{
		$this->_beforeDetail($id_pemeriksaan);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	public function Add($id_pemeriksaan = null, $id_pemeriksaan_detail = null)
	{
		$this->Edit($id_pemeriksaan, $id_pemeriksaan_detail, 0);
	}

	public function Edit($id_pemeriksaan = null, $id_pemeriksaan_detail = null, $id = null)
	{

		if ($id_pemeriksaan_detail && !$id && $id !== 0) {
			$id = $id_pemeriksaan_detail;
			$id_pemeriksaan_detail = null;
		}

		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->_beforeDetail($id_pemeriksaan, $id_pemeriksaan_detail);

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

			$record['id_pemeriksaan'] = $id_pemeriksaan;
			$record['id_pemeriksaan_detail'] = $id_pemeriksaan_detail;
			if ($id_pemeriksaan_detail)
				$record['id_kka'] = $this->data['rowheader1']['id_kka'];

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
				if ($id_pemeriksaan_detail)
					redirect("panelbackend/pemeriksaan_detail/detail/$id_pemeriksaan/$id_pemeriksaan_detail");
				else
					redirect("$this->page_ctrl/index/$id_pemeriksaan");
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

	public function go_print($id_pemeriksaan = null)
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout_print_pemeriksaan";
		$this->data['no_header'] = true;

		$this->_beforeDetail($id_pemeriksaan);

		$this->data['page_title'] = "LEMBAR REVIU SUPERVISI";

		$this->data['list'] = $this->model->SelectGrid(
			[
				'filter' => "id_pemeriksaan = " . $this->conn->escape($id_pemeriksaan),
				'limit' => -1
			]
		);

		$this->View($this->viewlist . 'print');
	}
}
