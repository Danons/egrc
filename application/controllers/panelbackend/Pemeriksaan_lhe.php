<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Pemeriksaan_lhe extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/pemeriksaan_lhelist";
		$this->viewdetail = "panelbackend/pemeriksaan_lhedetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_pemeriksaan";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Pendahuluan';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Pendahuluan';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Pemeriksaan Detail';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Pendahuluan';
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

		$this->load->model("Pemeriksaan_detailModel", "model");

		$this->load->model("PemeriksaanModel", "pemeriksaan");

		$this->load->model("Mt_sdm_unitModel", "modelunit");
		$this->data['unitarr'] = $this->modelunit->GetCombo();

		$this->load->model("Mt_pemeriksaan_kkaModel", "modelkka");
		$this->data['kkaarr'] = $this->modelkka->GetCombo();

		$this->load->model("Mt_bidang_pemeriksaanModel", "modelbidang");
		$this->data['bidangarr'] = $this->modelbidang->GetCombo();

		$this->data['uraianarr'] = array(
			"Pendahuluan :" => "Pendahuluan :",
			"Tujuan Pemeriksaan :" => "Tujuan Pemeriksaan :",
			"Langkah-Langkah Kerja :" => "Langkah-Langkah Kerja :"
		);

		$this->data['pelaksanaarr'] = $this->conn->GetList("select 
		user_id as idkey, name as val from public_sys_user a 
		where deleted_date is null and exists (
			select 1 from public_sys_user_group b 
			join public_sys_group_menu c on b.group_id = c.group_id 
			join public_sys_group_action d on c.group_menu_id = d.group_menu_id 
			join public_sys_action e on d.action_id = e.action_id 
			join public_sys_menu f on c.menu_id = f.menu_id
			where a.user_id = b.user_id and e.name='add' and f.url='panelbackend/pemeriksaan_temuan'
		)");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'select2'
		);
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'uraian',
				'label' => 'Uraian',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'rencana',
				'label' => 'Rencana',
				'width' => "auto",
				'type' => "date",
			),
			array(
				'name' => 'realisasi',
				'label' => 'Realisasi',
				'width' => "auto",
				'type' => "date",
			),
			array(
				'name' => 'catatan',
				'label' => 'Catatan',
				'width' => "auto",
				'type' => "text",
			),
		);
	}

	public function Index($id_pemeriksaan = null, $page = 0)
	{
		$this->_beforeDetail($id_pemeriksaan);
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

	protected function Record($id = null)
	{
		$record = array(
			'user_id' => $this->post['user_id'],
			'uraian' => $this->post['uraian'],
			'id_kka' => $this->post['id_kka'],
			'anggaran' => Rupiah2Number($this->post['anggaran']),
			'detail_uraian' => $this->post['detail_uraian']
		);


		$r = $this->conn->GetRow("select a.* from mt_sdm_jabatan a 
		where deleted_date is null and exists(select 1 from public_sys_user_group b 
		where a.id_jabatan = b.id_jabatan and b.user_id = " . $record['user_id'] . ")");
		$record['id_jabatan'] = $r['id_jabatan'];
		$record['nama_jabatan'] = $r['nama'];
		$record['nama_user'] = $this->data['pelaksanaarr'][$record['user_id']];

		return $record;
	}

	protected function Rules()
	{
		return array(
			"uraian" => array(
				'field' => 'uraian',
				'label' => 'Uraian',
				'rules' => "required|max_length[200]",
			),
			// "detail_uraian" => array(
			// 	'field' => 'detail_uraian',
			// 	'label' => 'Detail Uraian',
			// 	'rules' => "required|max_length[4000]",
			// ),
			"rencana" => array(
				'field' => 'rencana',
				'label' => 'Rencana',
				'rules' => "",
			),
			"realisasi" => array(
				'field' => 'realisasi',
				'label' => 'Realisasi',
				'rules' => "",
			),
			"catatan" => array(
				'field' => 'catatan',
				'label' => 'Catatan',
				'rules' => "",
			),
		);
	}

	protected function _beforeDetail($id_pemeriksaan = null)
	{
		$this->load->model("PemeriksaanModel", 'pemeriksaan');
		$this->data['rowheader']  = $this->pemeriksaan->GetByPk($id_pemeriksaan);

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

		$this->data['add_param'] .= $id_pemeriksaan;
	}

	public function Delete($id_pemeriksaan = null, $id = null)
	{

		$this->model->conn->StartTrans();

		$this->_beforeDetail($id_pemeriksaan);

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
			redirect("$this->page_ctrl/index/$id_pemeriksaan");
		} else {
			SetFlash('err_msg', "Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id_pemeriksaan/$id");
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

	public function Add($id_pemeriksaan = null)
	{
		$this->Edit($id_pemeriksaan);
	}

	public function Edit($id_pemeriksaan = null, $id = null)
	{

		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->_beforeDetail($id_pemeriksaan);

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

			$this->data['row']['id_pemeriksaan'] = $record['id_pemeriksaan'] = $id_pemeriksaan;

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

	public function go_print($page = 0, $id_pemeriksaan = null)
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";
		$this->data['no_header'] = true;

		$this->_beforeDetail($id_pemeriksaan);

		$this->load->model("Pemeriksaan_temuanModel", "modeltemuan");

		if ($page == 'lhp') {
			$this->data['page_title'] = 'Laporan Hasil Pemeriksaan';
		} else {
			$this->data['page_title'] = 'Daftar Temuan Pemeriksaan';
		}

		$this->data['page_title'] .= $this->data['jenis_title'];

		$this->data['listtemuan'] = $this->modeltemuan->SelectGrid(
			[
				'filter' => ($page == 'lhp' ? "is_disetujui='1' and " : null) . "id_pemeriksaan = " . $this->conn->qstr($id_pemeriksaan),
				'limit' => -1
			]
		);

		$this->View($this->viewlist . 'print');
	}

	protected function _afterInsert($id)
	{
		$ret = true;

		$id_pemeriksaan_tim = $this->conn->GetOne("select id_pemeriksaan_tim 
		from pemeriksaan_tim 
		where deleted_date is null and id_pemeriksaan = " . $this->conn->escape($this->data['row']['id_pemeriksaan']) . " 
		and user_id = " . $this->conn->escape($this->data['row']['user_id']));

		$record = [];
		$record['user_id'] = $this->data['row']['user_id'];
		$record['nama'] = $this->data['row']['nama_user'];
		$record['nama_jabatan'] = $this->data['row']['nama_jabatan'];
		$record['id_jabatan'] = $this->data['row']['id_jabatan'];
		$record['id_pemeriksaan'] = $this->data['row']['id_pemeriksaan'];
		if ($id_pemeriksaan_tim) {
			$ret = $this->conn->goUpdate("pemeriksaan_tim", $record, "id_pemeriksaan_tim = " . $this->conn->escape($id_pemeriksaan_tim));
		} else {
			$ret = $this->conn->goInsert("pemeriksaan_tim", $record);
		}

		return $ret;
	}

	protected function _afterUpdate($id)
	{
		return $this->_afterInsert($id);
	}

	protected function _beforeDelete($id = null)
	{
		$ret = true;
		if ($this->modelfile) {
			$ret = $this->conn->Execute("update {$this->modelfile->table} set deleted_date = now() where {$this->pk} = " . $this->conn->escape($id));
		}
		if ($ret) {
			$ret = $this->conn->Execute("update 
			from pemeriksaan_tim set deleted_date = now() 
			where id_pemeriksaan = " . $this->conn->escape($this->data['row']['id_pemeriksaan']) . " 
			and user_id = " . $this->conn->escape($this->data['row']['user_id']));
		}
		return $ret;
	}
}
