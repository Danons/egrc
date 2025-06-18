<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Pemeriksaan_checklist extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/pemeriksaan_checklistlist";
		$this->viewprint = "panelbackend/pemeriksaan_checklistprint";
		$this->viewdetail = "panelbackend/pemeriksaan_checklistdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_pemeriksaan";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Pemeriksaan Checklist';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Pemeriksaan Checklist';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Pemeriksaan Checklist';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Pemeriksaan Checklist';
		}

		$this->load->model("Pemeriksaan_checklistModel", "model");
		$this->load->model("Mt_pemeriksaan_checklistModel", "mtmodel");

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

		$this->access_role['print_detail'] = $this->data['acces_role']['print_detail'] = 1;
		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'treetable'
		);
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'id_checklist',
				'label' => 'Checklist',
				'width' => "auto",
				'type' => "number",
			),
			array(
				'name' => 'is_oke',
				'label' => 'OKE',
				'width' => "auto",
				'type' => "list",
				'value' => array('' => '-pilih-', '0' => 'Tidak', '1' => 'Iya'),
			),
			array(
				'name' => 'penyelesaian',
				'label' => 'Penyelesaian',
				'width' => "auto",
				'type' => "number",
			),
			array(
				'name' => 'jenis',
				'label' => 'Jenis',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'keterangan',
				'label' => 'Keterangan',
				'width' => "auto",
				'type' => "text",
			),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'id_checklist' => $this->post['id_checklist'],
			'is_oke' => (int)$this->post['is_oke'],
			'penyelesaian' => $this->post['penyelesaian'],
			'jenis' => $this->post['jenis'],
			'keterangan' => $this->post['keterangan'],
		);
	}

	protected function Rules()
	{
		return array(
			"id_checklist" => array(
				'field' => 'id_checklist',
				'label' => 'Checklist',
				'rules' => "required|integer|max_length[10]",
			),
			"is_oke" => array(
				'field' => 'is_oke',
				'label' => 'IS OKE',
				'rules' => "required|integer|max_length[10]",
			),
			"penyelesaian" => array(
				'field' => 'penyelesaian',
				'label' => 'Penyelesaian',
				'rules' => "required|integer|max_length[10]",
			),
			"jenis" => array(
				'field' => 'jenis',
				'label' => 'Jenis',
				'rules' => "max_length[50]",
			),
			"keterangan" => array(
				'field' => 'keterangan',
				'label' => 'Keterangan',
				'rules' => "max_length[10]",
			),
		);
	}


	public function Index($jenis_checklist = null, $id_pemeriksaan = null, $page = 0)
	{
		$this->_beforeDetail($jenis_checklist, $id_pemeriksaan);

		$this->data['jenis_checklist'] = $jenis_checklist;
		$this->data['id_pemeriksaan'] = $id_pemeriksaan;

		$this->data['list'] = $this->mtmodel->SelectGrid(["filter" => "jenis='" . ucwords($jenis_checklist) . "'"]);

		$this->data['row'] = [];
		$rows = $this->conn->GetArray("select * from pemeriksaan_checklist 
		where deleted_date is null and id_pemeriksaan = " . $this->conn->escape($id_pemeriksaan));
		foreach ($rows as  $r) {
			$this->data['row']['is_oke'][$r['id_checklist']] = $r['is_oke'];
			$this->data['row']['penyelesaian'][$r['id_checklist']] = $r['penyelesaian'];
		}

		if ($this->post['act'] == 'save') {
			$ret = true;
			$this->conn->StartTrans();
			foreach ($this->post['penyelesaian'] as $id_checklist => $is_oke) {
				if (!$ret)
					break;

				$record = [];
				$record['is_oke'] = (int)$this->post['is_oke'][$id_checklist];
				$record['id_checklist'] = $id_checklist;
				$record['id_pemeriksaan'] = $id_pemeriksaan;
				$record['penyelesaian'] = $this->post['penyelesaian'][$id_checklist];
				$record['jenis'] = $jenis_checklist;

				$id_pemeriksaan_checklist = $this->conn->GetOne("select id_pemeriksaan_checklist 
				from pemeriksaan_checklist 
				where deleted_date is null and id_pemeriksaan = " . $this->conn->escape($id_pemeriksaan) . " 
				and id_checklist = " . $this->conn->escape($id_checklist));

				if ($id_pemeriksaan_checklist) {
					$ret = $this->conn->goUpdate("pemeriksaan_checklist", $record, "id_pemeriksaan_checklist = $id_pemeriksaan_checklist");
				} else {
					$ret = $this->conn->goInsert("pemeriksaan_checklist", $record);

					$id_pemeriksaan_checklist = $this->conn->GetOne("select id_pemeriksaan_checklist 
					from pemeriksaan_checklist 
					where deleted_date is null and id_pemeriksaan = " . $this->conn->escape($id_pemeriksaan) . " 
					and id_checklist = " . $this->conn->escape($id_checklist));
				}
			}
			if ($ret) {
				SetFlash("suc_msg", "Data berhasil disimpan
				<script>
				$(function(){
					window.open('" . site_url("panelbackend/pemeriksaan_checklist/go_print/$jenis_checklist/$id_pemeriksaan") . "','_blank');
				});
				</script>");
				$this->conn->trans_commit();
				redirect(current_url());
				die();
			} else {
				$this->conn->trans_rollback();
				$this->data['err_msg'] = "Data gagal disimpan";
			}
			$this->data['row'] = $this->post;
		}
		$this->View($this->viewlist);
	}

	public function go_print($jenis_checklist = null, $id_pemeriksaan = null)
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout_print_pemeriksaan";
		$this->data['no_header'] = true;

		$this->_beforeDetail($jenis_checklist, $id_pemeriksaan);
		$this->data['page_title'] = "CHECK LIST<br/>";

		if ($jenis_checklist == 'perencanaan')
			$this->data['page_title'] .= "PENYELESAIAN PENUGASAN PERENCANAAN AUDIT";
		if ($jenis_checklist == 'penyelesaian')
			$this->data['page_title'] .= "PENYELESAIAN PENGUJIAN DAN EVALUASI";
		if ($jenis_checklist == 'laporan')
			$this->data['page_title'] .= "PENYELESAIAN LAPORAN";

		$this->data['list'] = $this->mtmodel->SelectGrid(["filter" => "jenis='" . ucwords($jenis_checklist) . "'"]);
		$this->data['row'] = [];
		$rows = $this->conn->GetArray("select * from pemeriksaan_checklist 
		where deleted_date is null and id_pemeriksaan = " . $this->conn->escape($id_pemeriksaan));
		foreach ($rows as  $r) {
			$this->data['row']['is_oke'][$r['id_checklist']] = $r['is_oke'];
			$this->data['row']['penyelesaian'][$r['id_checklist']] = $r['penyelesaian'];
		}

		$this->View($this->viewprint);
	}

	protected function _beforeDetail($jenis_checklist = null, $id_pemeriksaan = null)
	{
		$this->data['jenis_checklist'] = $jenis_checklist;
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

		$this->data['add_param'] .= $jenis_checklist . "/" . $id_pemeriksaan;
	}
}
