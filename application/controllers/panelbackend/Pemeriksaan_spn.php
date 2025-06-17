<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Pemeriksaan_spn extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/pemeriksaan_spnlist";
		$this->viewdetail = "panelbackend/pemeriksaan_spndetail";
		$this->viewprintdetail = "panelbackend/pemeriksaan_spn_printdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Surat Tugas';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Surat Tugas';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Surat Tugas';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Surat Tugas';
		}

		$this->load->model("Pemeriksaan_spnModel", "model");


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
			'datepicker', 'select2'
		);
		$this->access_role['print_detail'] = $this->data['access_role']['print_detail'] = 1;
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'nomor_surat',
				'label' => 'Nomor Surat',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'tanggal_surat',
				'label' => 'Tanggal Surat',
				'width' => "auto",
				'type' => "date",
			),
			array(
				'name' => 'deskripsi',
				'label' => 'Deskripsi',
				'width' => "auto",
				'type' => "text",
			),
		);
	}

	protected function Record($id = null)
	{

		if ($this->post['petugas'])
			foreach ($this->post['petugas'] as &$rr) {

				if ($rr['user_id']) {
					$r = $this->conn->GetRow("select a.* from mt_sdm_jabatan a 
			where a.deleted_date is null and exists(select 1 from public_sys_user_group b 
			where a.id_jabatan = b.id_jabatan and b.user_id = " . $rr['user_id'] . ")");
					$rr['id_jabatan'] = $r['id_jabatan'];
					$rr['nama_jabatan'] = $r['nama'];
					$rr['nama'] = $this->data['pelaksanaarr'][$rr['user_id']];
				}
			}


			if ($this->post['id_penyusun']) {
				$r = $this->conn->GetRow("select a.* from mt_sdm_jabatan a
			where a.deleted_date is null and exists(select 1 from public_sys_user_group b 
			where a.id_jabatan = b.id_jabatan and b.user_id = " . $this->post['id_penyusun'] . ")");
				$this->post['id_jabatan_penyesusun'] = $r['id_jabatan'];
				$this->post['nama_jabatan_penyusun'] = $r['nama'];
				$this->post['nama_penyusun'] = $this->data['pimpinanarr'][$this->post['id_penyusun']];
				$this->post['nipp_penyusun'] = $this->conn->GetOne('select username from public_sys_user where user_id = ' . $this->post['id_penyusun']);
			}
	
			// if ($this->post['id_sasaran']) {
			// 	$r = $this->conn->GetRow("select * from risk_sasaran where deleted_date is null and id_sasaran = " . $this->conn->escape($this->post['id_sasaran']));
			// 	$this->post['nama_sasaran'] = $r['nama'];
			// }
			if ($this->post['id_penanggung_jawab']) {
				$r = $this->conn->GetRow("select a.* from mt_sdm_jabatan a 
			where deleted_date is null and exists(select 1 from public_sys_user_group b 
			where a.id_jabatan = b.id_jabatan and b.user_id = " . $this->post['id_penanggung_jawab'] . ")");
				$this->post['id_jabatan_penanggung_jawab'] = $r['id_jabatan'];
				$this->post['nama_jabatan_penanggung_jawab'] = $r['nama'];
				$this->post['nipp_penanggung_jawab'] =  $this->conn->GetOne('select username from public_sys_user where user_id = ' . $this->post['id_penanggung_jawab']);
				$this->post['nama_penanggung_jawab'] = $this->data['penanggungjawabarr'][$this->post['id_penanggung_jawab']];

			}
	
			if ($this->post['id_pereview']) {
				$r = $this->conn->GetRow("select a.* from mt_sdm_jabatan a 
			where a.deleted_date is null and exists(select 1 from public_sys_user_group b 
			where a.id_jabatan = b.id_jabatan and b.user_id = " . $this->post['id_pereview'] . ")");
				$this->post['id_jabatan_pereview'] = $r['id_jabatan'];
				$this->post['nama_jabatan_pereview'] = $r['nama'];
				$this->post['nipp_pereview'] = $this->conn->GetOne('select username from public_sys_user where user_id = ' . $this->post['id_pereview']);
				$this->post['nama_pereview'] = $this->data['pelaksanaarr'][$this->post['id_pereview']];
			}



		return array(
			'nomor_surat' => $this->post['nomor_surat'],
			'tanggal_surat' => $this->post['tanggal_surat'],
			'periode_pemeriksaan_mulai' => $this->post['periode_pemeriksaan_mulai'],
			'periode_pemeriksaan_selesai' => $this->post['periode_pemeriksaan_selesai'],
			'deskripsi' => $this->post['deskripsi'],

			'id_jabatan_penyesusun' => $this->post['id_jabatan_penyesusun'],
			'id_penyusun' => $this->post['id_penyusun'],
			'nama_penyusun' => $this->post['nama_penyusun'],
			'jabatan_penyesusun' => $this->post['jabatan_penyesusun'],
			'nama_jabatan_penyusun' => $this->post['nama_jabatan_penyusun'],
			'nipp_penyusun' => $this->post['nipp_penyusun'],

			'id_jabatan_pereview' => $this->post['id_jabatan_pereview'],
			'id_pereview' => $this->post['id_pereview'],
			'nama_pereview' => $this->post['nama_pereview'],
			'jabatan_pereview' => $this->post['jabatan_pereview'],
			'nama_jabatan_pereview' => $this->post['nama_jabatan_pereview'],
			'nipp_pereview' => $this->post['nipp_pereview'],

			'id_jabatan_penanggung_jawab' => $this->post['id_jabatan_penanggung_jawab'],
			'id_penanggung_jawab' => $this->post['id_penanggung_jawab'],
			'nama_penanggung_jawab' => $this->post['nama_penanggung_jawab'],
			'jabatan_penanggung_jawab' => $this->post['jabatan_penanggung_jawab'],
			'nama_jabatan_penanggung_jawab' => $this->post['nama_jabatan_penanggung_jawab'],
			'nipp_penanggung_jawab' => $this->post['nipp_penanggung_jawab'],

			// 'id_petugas' => $this->post[] 
		);
	}

	protected function Rules()
	{
		return array(
			"nomor_surat" => array(
				'field' => 'nomor_surat',
				'label' => 'Nomor Surat',
				'rules' => "required|max_length[45]",
			),
			"tanggal_surat" => array(
				'field' => 'tanggal_surat',
				'label' => 'Tanggal Surat',
				'rules' => "required|max_length[45]",
			),
			"periode_pemeriksaan_mulai" => array(
				'field' => 'periode_pemeriksaan_mulai',
				'label' => 'Periode Pemeriksaan Mulai',
				'rules' => "required",
			),
			"periode_pemeriksaan_selesai" => array(
				'field' => 'periode_pemeriksaan_selesai',
				'label' => 'Periode Pemeriksaan Selesai',
				'rules' => "required",
			),
			"deskripsi" => array(
				'field' => 'deskripsi',
				'label' => 'Deskripsi',
				'rules' => "max_length[4000]",
			),
		);
	}

	public function printdetail($id, $id_pemeriksaan = null)
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout5";
		$this->data['no_header'] = true;

		$this->data['row'] = $this->model->GetByPk($id);
		if (!$this->data['row']['petugas']) {
			$this->data['row']['petugas'] = $this->conn->GetArray('select * from pemeriksaan_spn_petugas where deleted_date is null and id_spn =' . $this->conn->escape($id));
		}
		foreach ($this->data['row']['petugas'] as $p) {
			$this->data['nipp'][$p['user_id']] = $this->conn->GetRow("select nid from public_sys_user where deleted_date is null and user_id = " . $this->conn->escape($p['user_id']));
		}

		$this->_getDetailPrint($id);
		$this->data['rows'] =  $this->conn->GetRow('select * from pemeriksaan_spn where id_spn = ' . $this->conn->escape($id));
		$this->data['manajerspi'] = $this->conn->GetOne("select a.name from public_sys_user a left join public_sys_user_group b 
		on a.user_id = b.user_id left join mt_sdm_jabatan c on a.id_jabatan = c.id_jabatan where 1=1 and a.deleted_date is null AND b.id_jabatan='3267'");

		if (!$this->data['row'])
			$this->NoData();

		$this->View($this->viewprintdetail);
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
					// foreach ($this->post['petugas'] as $p) {
					// 	$getnipp = $this->conn->GetRow("select nid from public_sys_user where deleted_date is null and user_id = " . $this->conn->escape($p['user_id']));
					// 	$rec = [
					// 		'id_spn' => $id,
					// 		'user_id' => $p['user_id'],
					// 		'nama_jabatan' => $p['nama_jabatan'],
					// 		'nama' => $p['nama'],
					// 		'id_jabatan' => $p['id_jabatan'],
					// 		'nipp' => $getnipp['nid']
					// 	];
					// 	$sql = $this->conn->insertSQL("pemeriksaan_spn_petugas", $rec);
					// 	if ($sql) {
					// 		$this->conn->Execute($sql);
					// 	}
					// }
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
		if (!$this->data['row']['petugas'])
			$this->data['row']['petugas'] = $this->conn->GetArray("select * from pemeriksaan_spn_petugas where deleted_date is null and id_spn = " . $this->conn->escape($id));
		foreach ($this->data['row']['petugas'] as $key => $p) {
			// dpr($p);
			// $this->data['row']['petugas'][$key]['nama'] .= "&nbsp;";
			$this->data['row']['petugas'][$key]['nama_jabatan'] = '&nbsp;' . $p['nama_jabatan'];
		}
		// dpr($this->data['row']['petugas'], 1);
	}

	public function Detail($id = null)
	{

		$id = urldecode($id);
		$this->_beforeDetail($id);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$this->_onDetail($id);

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	protected function _afterInsert($id)
	{
		$ret = true;


		if(!empty($this->post['petugas'])){
			
			$ret = $this->conn->Execute("delete from pemeriksaan_spn_petugas where id_spn = " . $this->conn->escape($id));
			
			foreach ($this->post['petugas'] as $p) {

				if(!$ret){
					break;
				}
				// $this->conn->debug = 1;
				$getnipp = $this->conn->GetRow("select username from public_sys_user where deleted_date is null and user_id = " . $this->conn->escape($p['user_id']));
				// dpr($getnipp,1);
				$rec = [
					'id_spn' => $id,
					'user_id' => $p['user_id'],
					'nama_jabatan' => $p['nama_jabatan'],
					'nama' => $p['nama'],
					'id_jabatan' => $p['id_jabatan'],
					'nipp' => $getnipp['username']
				];
				$sql = $this->conn->insertSQL("pemeriksaan_spn_petugas", $rec);
				if ($sql) {
					
					$ret = $this->conn->Execute($sql);
				}
			}
		}

		// if (!empty($this->post['pemeriksaan_tim'])) {
		// 	if ($ret)
		// 		$ret = $this->conn->Execute("update pemeriksaan_tim set deleted_date = now() where id_pemeriksaan = " . $this->conn->escape($id));
		// 	foreach ($this->post['pemeriksaan_tim'] as $r) {
		// 		$r['id_pemeriksaan'] = $id;
		// 		$ret = $this->conn->goInsert("pemeriksaan_tim", $r);
		// 	}
		// }

		return $ret;
	}
}
