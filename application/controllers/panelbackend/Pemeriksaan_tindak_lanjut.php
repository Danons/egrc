<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Pemeriksaan_tindak_lanjut extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/pemeriksaan_tindak_lanjutlist";
		$this->viewdetail = "panelbackend/pemeriksaan_tindak_lanjutdetail";
		$this->viewprintdetail = "panelbackend/laporan_pemantauan_tindak_lanjut_auditdetailprint";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_pemeriksaan";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Tindak Lanjut';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Tindak Lanjut';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Tindak Lanjut';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Tindak Lanjut';
		}

		$this->load->model("Pemeriksaan_tindak_lanjutModel", "model");

		$this->load->model("Pemeriksaan_temuanModel", "pemeriksaantemuan");

		$this->load->model("Mt_periode_twModel", "mtperiodetw");
		$this->data['mtperiodetwarr'] = $this->mtperiodetw->GetCombo();

		$this->load->model("Mt_sdm_unitModel", "modelunit");
		$this->data['unitarr'] = $this->modelunit->GetCombo();

		$this->load->model("Mt_status_pemeriksaanModel", "mtstatuspemeriksaan");
		$this->data['statusarr'] = $this->mtstatuspemeriksaan->GetCombo();

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;

		$this->load->model("Pemeriksaan_tindak_lanjut_filesModel", "modelfile");
		$this->data['configfile'] = $this->config->item('file_upload_config');

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

		$this->plugin_arr = array(
			'datepicker', 'upload', 'tinymce'
		);

		$this->data['edited'] = 0;
		if ($this->access_role['tindaklanjut'])
			$this->data['edited'] = 1;

		if ($this->access_role['evaluasi']) {
			$this->data['editedauditor'] = 1;
		}

		$this->access_role['print_detail'] = $this->data['access_role']['print_detail'] = 1;
	}

	protected function _beforeDetail($id_pemeriksaan_temuan = null, $id = null)
	{
		$this->load->model("PemeriksaanModel", 'pemeriksaan');
		$this->data['rowheader2']  = $this->pemeriksaantemuan->GetByPk($id_pemeriksaan_temuan);
		$this->data['rowheader']  = $this->pemeriksaan->GetByPk($this->data['rowheader2']['id_pemeriksaan']);

		if ($this->data['rowheader']['user_id'] == $_SESSION[SESSION_APP]['user_id'])
			$this->access_role['tindaklanjut'] = 1;

		if (!$this->data['rowheader'])
			$this->NoData();

		$id_dokumen = $this->data['rowheader2']['id_dokumen'];
		if ($id_dokumen)
			$this->data['dokumenarr'][$id_dokumen] = $this->conn->GetOne("select concat(nomor_dokumen ,' ', nama) 
				from dokumen 
				where deleted_date is null and id_dokumen = " . $this->conn->escape($id_dokumen));

		$id_jenis_audit_eksternal = $this->data['rowheader']['id_jenis_audit_eksternal'];
		if ($id_jenis_audit_eksternal)
			$this->data['jeniseksternalarr'][$id_jenis_audit_eksternal] = $this->conn->GetOne("select nama 
				from mt_jenis_audit_eksternal 
				where deleted_date is null and id_jenis_audit_eksternal = " . $this->conn->escape($id_jenis_audit_eksternal));

		$id_subbid = $this->data['rowheader']['id_subbid'];
		if ($id_subbid)
			$this->data['subbidarr'][$id_subbid] = $this->conn->GetOne("select nama 
				from mt_sdm_subbid 
				where deleted_date is null and code = " . $this->conn->escape($id_subbid));

		$jenis = $this->data['rowheader']['jenis'];

		$this->data['jenis_title'] = " " . [
			"operasional" => "Operasional",
			"mutu" => "Mutu Internal",
			"penyuapan" => "Anti Penyuapan",
			"khusus" => "Khusus",
			"eksternal" => "Eksternal"
		][$jenis];


		$this->load->model("Pemeriksaan_temuan_filesModel", "modelfile1");

		if ($this->modelfile1) {
			if (!$this->data['rowheader2']['files']['id'] && $this->data['rowheader2']['id_pemeriksaan_temuan']) {
				$rows = $this->conn->GetArray("select *
				from {$this->modelfile1->table}
				where deleted_date is null and {$this->pemeriksaantemuan->pk} = " . $this->conn->escape($this->data['rowheader2']['id_pemeriksaan_temuan']));

				foreach ($rows as $r) {
					$this->data['rowheader2']['files']['id'][] = $r[$this->modelfile1->pk];
					$this->data['rowheader2']['files']['name'][] = $r['client_name'];
				}
			}
		}


		if ($this->access_role['evaluasi']) {
			$data_jabatan = $this->conn->GetRow("select * from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($_SESSION[SESSION_APP]['id_jabatan']));
			list($jenis_jabatan) = explode(".", $data_jabatan['position_id']);
			if ((in_array($jenis_jabatan, ["6"]) && $data_jabatan['id_subbid'] == $this->data['rowheader']['id_bidang']) || in_array($jenis_jabatan, ["2", "3", "4"]) || Access("view_all", "main"))
				$this->data['editedauditor'] = 1;
			else
				$this->data['editedauditor'] = 0;
		}

		$this->data['add_param'] .= $id_pemeriksaan_temuan;
	}

	public function Index($id_pemeriksaan_temuan = null, $page = 0)
	{
		$this->_beforeDetail($id_pemeriksaan_temuan);
		redirect("panelbackend/pemeriksaan_temuan/index/" . $this->data['rowheader2']['id_pemeriksaan']);
	}

	public function Detail($id_pemeriksaan_temuan = null, $id = null)
	{

		$this->_beforeDetail($id_pemeriksaan_temuan, $id);
		redirect("panelbackend/pemeriksaan_temuan/index/" . $this->data['rowheader2']['id_pemeriksaan']);
	}

	public function Delete($id_pemeriksaan_temuan = null, $id = null)
	{

		$this->model->conn->StartTrans();

		$this->_beforeDetail($id_pemeriksaan_temuan, $id);

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
			redirect("$this->page_ctrl/detail/$id_pemeriksaan_temuan/$id");
		} else {
			SetFlash('err_msg', "Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id_pemeriksaan_temuan/$id");
		}
	}

	public function Add($id_pemeriksaan_temuan = null)
	{
		$this->Edit($id_pemeriksaan_temuan);
	}

	public function Edit($id_pemeriksaan_temuan = null, $id = null)
	{

		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$id_periode_tw = $this->post['id_periode_tw'];
		$tahun = $this->post['tahun'];

		if (!$id_periode_tw) {
			$tgl_efektif = date('Y-m-d');
			list($tahun, $bln, $tgl) = explode("-", $tgl_efektif);
			$id_periode_tw = $this->conn->GetOne("select id_periode_tw from mt_periode_tw where deleted_date is null and '$bln' between bulan_mulai and bulan_akhir");
		}

		if (!$id_periode_tw) {
			$id = $this->conn->GetOne("select id_pemeriksaan_tindak_lanjut 
			from pemeriksaan_tindak_lanjut 
			where deleted_date is null and id_pemeriksaan_temuan = " . $this->conn->escape($id_pemeriksaan_temuan) . " 
			order by id_pemeriksaan_tindak_lanjut desc");
			$this->data['row'] = $this->model->GetByPk($id);
		} else {
			$id = $this->conn->GetOne("select id_pemeriksaan_tindak_lanjut 
			from pemeriksaan_tindak_lanjut 
			where deleted_date is null and id_pemeriksaan_temuan = " . $this->conn->escape($id_pemeriksaan_temuan) . " 
			and tahun = " . $this->conn->escape($tahun) . " 
			and id_periode_tw = " . $this->conn->escape($id_periode_tw));
			$this->data['row'] = $this->model->GetByPk($id);
			$this->data['row']['id_periode_tw'] = $id_periode_tw;
		}

		if ($this->data['row']) {
			$this->data['row']['tahun'] = $tahun;
			$this->data['row']['id_periode_tw'] = $id_periode_tw;
		}

		$this->_beforeDetail($id_pemeriksaan_temuan, $id);

		$this->data['idpk'] = $id;

		if (!$this->data['row'] && $id)
			$this->NoData();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");

		if ($this->post && $this->post['act'] <> 'set_periode') {
			if (!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id);

			$this->data['row'] = array_merge($this->data['row'], $record);
			$this->data['row'] = array_merge($this->data['row'], $this->post);
		}

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {

			$record['id_pemeriksaan_temuan'] = $id_pemeriksaan_temuan;

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
				redirect("$this->page_ctrl/index/$id_pemeriksaan_temuan");
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

		if ($ret && $this->post['tindaklanjutarr']) {
			$ret = $this->conn->Execute("update 
			pemeriksaan_tindak_lanjut_saran set deleted_date = now()
			where id_pemeriksaan_tindak_lanjut = " . $this->conn->escape($id));
			foreach ($this->post['tindaklanjutarr'] as $id_pemeriksaan_temuan_saran => $rincian_tindak_lanjut) {
				if (!$ret)
					break;
				$record = array();
				$record['id_pemeriksaan_temuan_saran'] = $id_pemeriksaan_temuan_saran;
				$record['rincian_tindak_lanjut'] = $rincian_tindak_lanjut;
				$record['id_pemeriksaan_tindak_lanjut'] = $id;

				$ret = $this->conn->goInsert("pemeriksaan_tindak_lanjut_saran", $record);
			}
		}

		if ($ret && $this->access_role['evaluasi']) {
			$row = $this->conn->GetRow("select * 
			from pemeriksaan_tindak_lanjut 
			where deleted_date is null and id_pemeriksaan_temuan = " . $this->conn->escape($this->data['rowheader2']['id_pemeriksaan_temuan']) . " 
			order by id_periode_tw desc");

			$ret = $this->conn->goUpdate("pemeriksaan_temuan", [
				"rincian_tindak_lanjut" => $row['rincian_tindak_lanjut'],
				"hasil_evaluasi" => $row['hasil_evaluasi'],
				"status" => $row['hasil_evaluasi'] == '2' ? 'Close' : 'Monev',
			], "id_pemeriksaan_temuan = " . $this->conn->escape($this->data['rowheader2']['id_pemeriksaan_temuan']));
		}

		return $ret;
	}

	protected function _afterUpdate($id)
	{
		return $this->_afterInsert($id);
	}

	protected function Record($id = null)
	{
		$this->post['status'] = $this->post['hasil_evaluasi'] <> '2' ? 0 : 1;

		$return = array();

		if ($this->access_role['tindaklanjut']) {
			$return = array(
				'id_pemeriksaan_temuan_temuan' => $this->post['id_pemeriksaan_temuan_temuan'],
				'id_periode_tw' => $this->post['id_periode_tw'],
				'tahun' => $this->post['tahun'],
				'rincian_tindak_lanjut' => $this->post['rincian_tindak_lanjut']
			);
		}

		if ($this->access_role['evaluasi']) {
			$return['hasil_evaluasi'] = $this->post['hasil_evaluasi'];
			$return['id_periode_tw'] = $this->post['id_periode_tw'];
			$return['tahun'] = $this->post['tahun'];
			$return['status'] = $this->post['status'];
			$return['kesimpulan'] = $this->post['kesimpulan'];
		}

		// if (!$this->data['row']) {
		// 	$return['tahun'] = date("Y");
		// 	$return['id_periode_tw'] = $this->conn->GetOne("select id_periode_tw from mt_periode_tw where '" . date('m') . "' between bulan_mulai and bulan_akhir");
		// }

		return $return;
	}

	protected function Rules()
	{
		return array(
			"id_periode_tw" => array(
				'field' => 'id_periode_tw',
				'label' => 'Periode TW',
				'rules' => "integer|max_length[10]",
			),
			"rincian_tindak_lanjut" => array(
				'field' => 'rincian_tindak_lanjut',
				'label' => 'Rincian Tindak Lanjut',
				'rules' => "",
			),
			"hasil_evaluasi" => array(
				'field' => 'hasil_evaluasi',
				'label' => 'Hasil Evaluasi',
				'rules' => "max_length[50]",
			),
			"status" => array(
				'field' => 'status',
				'label' => 'Status',
				'rules' => "integer|max_length[10]",
			),
		);
	}
}
