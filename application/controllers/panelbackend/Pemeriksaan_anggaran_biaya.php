<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Pemeriksaan_anggaran_biaya extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/pemeriksaan_anggaran_biayalist";
		$this->viewdetail = "panelbackend/pemeriksaan_anggaran_biayadetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_pemeriksaan";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Biaya Audit';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Biaya Audit';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Biaya Audit';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Biaya Audit';
		}

		$this->load->model("Pemeriksaan_anggaran_biayaModel", "model");

		$this->load->model("Mt_pemeriksaan_jenis_akomodasiModel", "jenisakomodasi");
		$this->data['jenisakomodasiarr'] = $this->jenisakomodasi->GetCombo();


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


		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'select2'
		);
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'nama',
				'label' => 'Nama',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'nilai_realisasi',
				'label' => 'Nilai Realisasi',
				'width' => "auto",
				'type' => "number",
			),
		);
	}

	protected function Record($id = null)
	{
		$jenis = $this->conn->GetRow('select * from mt_pemeriksaan_jenis_akomodasi where deleted_date is null and id_jenis = ' . $this->conn->escape($this->post['id_jenis']));
		return array(
			'id_jenis' => $jenis['id_jenis'],
			'nama_jenis' => $jenis['nama_jenis'],
			'id_pemeriksaan_detail' => $this->post['id_pemeriksaan_detail'],
			'nama' => $this->post['nama'],
			'nilai_realisasi' => Rupiah2Number($this->post['nilai_realisasi']),
		);
	}

	protected function Rules()
	{
		return array(
			"nama" => array(
				'field' => 'nama',
				'label' => 'Nama',
				'rules' => "max_length[200]",
			),
			"nilai_realisasi" => array(
				'field' => 'nilai_realisasi',
				'label' => 'Nilai Realisasi',
				'rules' => "numeric|max_length[10]",
			),
		);
	}


	public function Index($id_pemeriksaan = null, $page = 0)
	{
		redirect("panelbackend/pemeriksaan_detail/index/$id_pemeriksaan");
		return;
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

		$this->_onDetail($id);

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

		$this->_onDetail($id);

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {

			$this->data['row']['id_pemeriksaan'] = $record['id_pemeriksaan'] = $id_pemeriksaan;
			// dpr($this->data['row'], 1);

			$this->_isValid($record, true);

			$this->_beforeEdit($record, $id);

			$this->_setLogRecord($record, $id);

			$this->model->conn->StartTrans();
			$isupdate = false;
			if (trim($this->data['row'][$this->pk]) == trim($id) && trim($id)) {
				$isupdate = true;
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
				if ($isupdate)
					redirect("$this->page_ctrl/detail/$id_pemeriksaan/$id");
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

	protected function _afterInsert($id)
	{
		$ret = true;

		if ($this->modelfile) {
			if (!empty($this->post['files'])) {
				foreach ($this->post['files']['id'] as $k => $v) {
					$return = $this->_updateFiles(array($this->pk => $id), $v);

					$ret = $return['success'];
				}
			}
		}

		return $ret;
	}

	protected function _onDetail($id = null)
	{
		$this->data['pemeriksaandetailarr'] = $this->conn->GetList("select id_pemeriksaan_detail as idkey, uraian as val
			from pemeriksaan_detail 
			where deleted_date is null and id_pemeriksaan= " . $this->conn->escape($this->data['rowheader']['id_pemeriksaan']));

		return true;
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
		return $ret;
	}
}
