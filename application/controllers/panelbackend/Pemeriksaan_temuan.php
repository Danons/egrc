<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Pemeriksaan_temuan extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/pemeriksaan_temuanlist";
		$this->viewdetail = "panelbackend/pemeriksaan_temuandetail";
		$this->viewprintdetail = "panelbackend/laporan_konsep_temuan_dan_rencana_tindak_lanjut";
		$this->viewprintdetailpemantauan = "panelbackend/laporan_pemantauan_tindak_lanjut_auditdetailprint";
		$this->viewprintdetailba = "panelbackend/laporan_ba_temuan_belum_ditindak_lanjutiprintdetail";
		$this->viewprintdetailtindaklanjut = "panelbackend/laporan_tindak_lanjutanprintdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_pemeriksaan";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah ';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit ';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail ';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Hasil ';
		}

		$this->load->model("Pemeriksaan_temuanModel", "model");

		$this->load->model("Pemeriksaan_temuan_filesModel", "modelfile");
		$this->data['configfile'] = $this->config->item('file_upload_config');

		$this->load->model("PemeriksaanModel", "pemeriksaan");
		$this->load->model("DokumenModel", "dokumen");
		$this->data['dokumenarr'] = $this->dokumen->GetCombo();
		$this->load->model("Pemeriksaan_detailModel", "pemeriksaandetail");

		$this->load->model("Mt_periode_twModel", "mtperiodetw");
		$this->data['mtperiodetwarr'] = $this->mtperiodetw->GetCombo();

		$this->load->model("Mt_sdm_unitModel", "modelunit");
		$this->data['unitarr'] = $this->modelunit->GetCombo();
		$this->load->model("Mt_sdm_jabatanModel", "modeljabatan");
		$this->data['jabatanarr'] = $this->modeljabatan->GetCombo();

		$this->load->model("Mt_bidang_pemeriksaanModel", "modelbidang");
		$this->data['bidangarr'] = $this->modelbidang->GetCombo();

		$this->load->model("Mt_status_pemeriksaanModel", "mtstatuspemeriksaan");
		$this->data['statusarr'] = $this->mtstatuspemeriksaan->GetCombo();
		$this->load->model("Pemeriksaan_spnModel", "spn");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;

		$this->load->model("Mt_pemeriksaan_kkaModel", "modelkka");
		$this->data['kkaarr'] = $this->modelkka->GetCombo();

		$this->load->model("Pemeriksaan_temuan_filesModel", "modelfile");
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
			'datepicker', 'upload', 'select2', 'tinymce'
		);
	}

	protected function _beforeDetail($id_pemeriksaan = null, $id_pemeriksaan_detail = null, $id = null)
	{
		$this->load->model("PemeriksaanModel", 'pemeriksaan');
		$this->data['rowheader']  = $this->pemeriksaan->GetByPk($id_pemeriksaan);
		$this->data['rowheader0']  = $this->spn->GetByPk($this->data['rowheader']['id_spn']);

		if ($id_pemeriksaan_detail) {
			$this->load->model("Pemeriksaan_detailModel", 'pemeriksaan_detail');
			$this->data['rowheader1']  = $this->pemeriksaan_detail->GetByPk($id_pemeriksaan_detail);
		}
		if (!$this->data['rowheader'])
			$this->NoData();

		$this->data['page_title'] .= $_SESSION[SESSION_APP][$this->data['rowheader']['jenis'] . 'jenis'];

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

		$this->data['add_param'] .= $id_pemeriksaan . "/" . $id_pemeriksaan_detail;


		if ($jenis == 'operasional') {
			$this->data['jenistemuanarr'] = ["" => "Semua", "Temuan" => "Temuan", "Catatan" => "Catatan"];
		} else if ($jenis == 'mutu' || $jenis == 'penyuapan') {
			$this->data['jenistemuanarr'] = ["" => "Semua", "OBSERVASI" => "OBSERVASI", "MINOR" => "MINOR", "MAJOR" => "MAJOR"];
		}

		// dpr($rowheader['id_status']);
		// dpr($rowheader['id_pereview']);
		// dpr($_SESSION[SESSION_APP]['user_id']);
		// dpr((($rowheader['id_pereview'] == $_SESSION[SESSION_APP]['user_id'])));
		// dpr($rowheader['id_status'] == 2 && (($rowheader['id_penanggung_jawab'] == $_SESSION[SESSION_APP]['user_id'])));


		if ($this->post['act'] == 'kirim_feedback') {
			if ($this->post['keterangan']) {
				$rec = [
					'keterangan' => $this->post['keterangan'], 'id_pemeriksaan_temuan' => $id
				];

				if (!Access("view_all", "main"))
					$rec['is_auditi'] = 1;

				$ret = $this->conn->goInsert("pemeriksaan_temuan_diskusi", $rec);
				$id_pemeriksaan_temuan_diskusi = $this->conn->GetOne("select max(id_pemeriksaan_temuan_diskusi) 
				from pemeriksaan_temuan_diskusi 
				where id_pemeriksaan_temuan = " . $this->conn->escape($id));

				if ($ret && $this->post['target_penyelesaian']) {
					$this->conn->goUpdate("pemeriksaan_temuan", ['target_penyelesaian' => $this->post['target_penyelesaian']], "id_pemeriksaan_temuan = " . $this->conn->escape($id));
				}

				if (!empty($this->post['files_tanggapan']) && $ret) {
					foreach ($this->post['files_tanggapan']['id'] as $k => $v) {
						$return = $this->_updateFiles(array($this->pk => $id, "id_pemeriksaan_temuan_diskusi" => $id_pemeriksaan_temuan_diskusi), $v);

						$ret = $return['success'];
					}
				}
			}
			redirect(current_url());
			die();
		}

		$this->data['rowspesan'] = $this->conn->GetArray("select * 
		from pemeriksaan_temuan_diskusi 
		where deleted_date is null and id_pemeriksaan_temuan = " . $this->conn->escape($id) . " 
		order by created_date asc");

		$this->data['bidangarr1'] = [];
		foreach ($this->data['rowheader']['pemeriksaan_tim'] as $r) {
			if ($this->mode != 'add') {
				$this->data['bidangarr1'][$r['id_bidang_pemeriksaan']] = $this->data['bidangarr'][$r['id_bidang_pemeriksaan']];
			} else if (($r['user_id'] == $_SESSION[SESSION_APP]['user_id']  && !$this->Access("add", "panelbackend/pemeriksaan")) || Access("view_all", "main")) {
				$this->data['bidangarr1'][$r['id_bidang_pemeriksaan']] = $this->data['bidangarr'][$r['id_bidang_pemeriksaan']];
			}
		}

		// if ($this->data['bidangarr1']) {
		$this->data['bidangarr'] = ["" => ""] + $this->data['bidangarr1'];
		// }


		if (!$this->data['bidangarr1'] && !Access("index", "panelbackend/loginas")) {
			$this->data['access_role']['edit'] = $this->access_role['edit'] = false;
			$this->data['access_role']['delete'] = $this->access_role['delete'] = false;
			$this->data['access_role']['add'] = $this->access_role['add'] = false;
			$this->data['edited'] = false;
		}

		$this->data['bidangdivisiarr'] = ['' => ''] + $this->conn->GetList("select code idkey, nama val 
		from mt_sdm_subbid a 
		where deleted_date is null and exists (select 1 
		from mt_sdm_jabatan b 
		where a.code = b.id_subbid 
		and SUBSTRING_INDEX(position_id,'.',1) in ('2','3','4','6') 
		and b.id_unit = " . $this->conn->escape($this->data['rowheader']['id_unit']) . ")");


		$this->data['listtask'] = $this->conn->GetArray("select t.created_date, t.deskripsi, 
		t.id_status_pengajuan, u.name as nama_user, g.name as nama_group
		from risk_task t
		join public_sys_user u on t.created_by = u.user_id
		join public_sys_group g on t.group_id = g.group_id
		where t.deleted_date is null and (page = 'pemeriksaan')
		and id_pemeriksaan =" . $this->conn->escape($id_pemeriksaan) . "
		and t.id_status_pengajuan is not null
		and t.is_pending != '1'
		order by id_task desc");


		$this->data['penanggung_jawab'] = $this->conn->GetRow("select 
		e.name as nama, f.nama as jabatan
		from public_sys_user_group a 
		join public_sys_user e on a.user_id = e.user_id
		join mt_sdm_jabatan f on a.id_jabatan = f.id_jabatan
		where a.deleted_date is null and exists (
			select 1 from public_sys_group_action b 
			join public_sys_group_menu c on b.group_menu_id = c.group_menu_id
			join public_sys_action d on b.action_id = d.action_id
			where a.group_id = c.group_id and d.name = 'penanggungjawab'
		)");
	}

	public function Index($id_pemeriksaan = null, $id_pemeriksaan_detail = null, $page = 0)
	{
		if ($id_pemeriksaan && $id_pemeriksaan_detail) {
			redirect("panelbackend/pemeriksaan_detail/detail/$id_pemeriksaan/$id_pemeriksaan_detail");
			return;
		}
		unset($this->access_role['add']);
		// if($_POST){
		// 	dpr($_POST,1);
		// }
		$this->_beforeDetail($id_pemeriksaan, $id_pemeriksaan_detail);

		if ($this->post['act'] == 'setujui' && $this->access_role['setujui']) {
			// $this->conn->debug = 1;
			$ret = $this->conn->goUpdate("pemeriksaan_temuan", ["is_disetujui" => 1, 'status' => 'Pemeriksaan'], "id_pemeriksaan_temuan = " . $this->conn->escape($this->post['idkey']));
			// dpr($ret, 1);
			redirect(current_url());
			exit();
		}

		if ($this->post['act'] == 'close' && $this->access_role['setujui']) {
			// $this->conn->debug = 1;
			$ret = $this->conn->goUpdate("pemeriksaan_temuan", ["is_disetujui" => "0", 'status' => 'Close'], "id_pemeriksaan_temuan = " . $this->conn->escape($this->post['idkey']));
			// dpr($ret, 1);
			redirect(current_url());
			exit();
		}

		if ($this->post['act'] == 'batal' && $this->access_role['setujui']) {
			// $this->conn->debug = 1;
			$ret = $this->conn->goUpdate("pemeriksaan_temuan", ["is_disetujui" => "{{null}}", 'status' => 'Pemeriksaan'], "id_pemeriksaan_temuan = " . $this->conn->escape($this->post['idkey']));
			// dpr($ret, 1);
			redirect(current_url());
			exit();
		}

		if ($this->post['act'] == 'setjenistemuan') {
			$_SESSION[SESSION_APP][$this->data['rowheader']['jenis'] . 'jenis'] = $this->post['jenis_temuan'];
		}

		if ($this->post['act'] == 'filter') {
			$_SESSION[SESSION_APP][$this->page_ctrl]['id_bidang_pemeriksaan_filter'] = $this->post['id_bidang_pemeriksaan_filter'];
		}

		if (!$_SESSION[SESSION_APP][$this->data['rowheader']['jenis'] . 'jenis']) {

			// if ($this->data['rowheader']['jenis'] == 'operasional') {
			// 	$_SESSION[SESSION_APP][$this->data['rowheader']['jenis'] . 'jenis'] = 'Temuan';
			// } else if ($this->data['rowheader']['jenis'] == 'mutu' || $this->data['rowheader']['jenis'] == 'penyuapan') {
			// 	$_SESSION[SESSION_APP][$this->data['rowheader']['jenis'] . 'jenis'] = 'OBSERVASI';
			// }else{
			// 	$_SESSION[SESSION_APP][$this->data['rowheader']['jenis'] . 'jenis'] = 'Temuan';
			// }
		}

		$addfilter = "";
		// $addfilter = " and jenis_temuan = 'Temuan'";
		if ($_SESSION[SESSION_APP][$this->data['rowheader']['jenis'] . 'jenis']) {
			$addfilter = " and jenis_temuan = " . $this->conn->escape($_SESSION[SESSION_APP][$this->data['rowheader']['jenis'] . 'jenis']);
		}

		$addfilter1 = "";
		if ($_SESSION[SESSION_APP][$this->page_ctrl]['id_bidang_pemeriksaan_filter'])
			$addfilter1 = " and id_bidang_pemeriksaan = " . $this->conn->escape($_SESSION[SESSION_APP][$this->page_ctrl]['id_bidang_pemeriksaan_filter']);


		// if ($_SESSION[SESSION_APP]['id_jabatan']) {
		// 	$data_jabatan = $this->conn->GetRow("select * from mt_sdm_jabatan where id_jabatan = " . $this->conn->escape($_SESSION[SESSION_APP]['id_jabatan']));
		// 	// list($jenis_jabatan) = explode(".", $data_jabatan['position_id']);
		// 	// if ($jenis_jabatan >= '6') {
		// 	// 	$addfilter1 .= " and id_bidang = " . $this->conn->escape($data_jabatan['id_subbid']);
		// 	// }
		// }

		// $this->conn->debug = 1;
		$this->data['listtemuan'] = $this->model->SelectGrid(
			[
				'filter' => "id_pemeriksaan = " . $this->conn->qstr($id_pemeriksaan) . $addfilter . $addfilter1,
				'limit' => -1
			]
		);



		#KIRIM NOTIFIKASII

		$rowheader = $this->data['rowheader'];

		$is_allowajuan = false;
		if ($rowheader['id_status'] == 2 && (($rowheader['id_penanggung_jawab'] == $_SESSION[SESSION_APP]['user_id'] && Access("add", "panelbackend/pemeriksaan_temuan")) or Access("index", "panelbackend/loginas"))) {
			$this->data['is_allowajukanpengawas'] = true;
			$is_allowajuan = true;
		} elseif ($rowheader['id_status'] == 3 && (($rowheader['id_pereview'] == $_SESSION[SESSION_APP]['user_id'] && Access("pengawas", "panelbackend/pemeriksaan"))  or Access("index", "panelbackend/loginas"))) {
			$this->data['is_allowajukanpenanggungjawab'] = true;
			$is_allowajuan = true;
		} elseif ($rowheader['id_status'] == 4 && $rowheader['id_penyusun'] == $_SESSION[SESSION_APP]['user_id'] &&  Access("penanggungjawab", "panelbackend/pemeriksaan")) {
			$this->data['is_allowkonfirmasiauditee'] = true;
			$is_allowajuan = true;
		} elseif ($rowheader['id_status'] == 5 && $rowheader['id_unit'] == $_SESSION[SESSION_APP]['id_unit'] &&  Access("tindaklanjut", "panelbackend/pemeriksaan_tindak_lanjut")) {
			$this->data['is_allowtindaklanjut'] = true;
			$is_allowajuan = true;
		} else if ($rowheader['id_status'] == 1 && $rowheader['jenis'] == 'eksternal') {
			$this->data['is_allowkonfirmasiauditee'] = true;
			$is_allowajuan = true;
		}

		if (!$is_allowajuan)
			$this->access_role['setujui'] = false;

		if ($this->post['act'] == 'kirimajuan' && $is_allowajuan) {
			// $this->conn->debug = 1;
			// dpr($this->post);
			$untuk = null;
			$untuk_user = null;
			if ($this->post['idkey'] == 2) {
				$untuk_user = $rowheader['id_penanggung_jawab'];
			}
			if ($this->post['idkey'] == 3) {
				$untuk_user = $rowheader['id_pereview'];
			}
			if ($this->post['idkey'] == 4) {
				$untuk_user = $rowheader['id_penyusun'];
			}
			if ($this->post['idkey'] == 5) {
				$untuk = $this->conn->GetOne("select min(id_jabatan) 
				from mt_sdm_jabatan a
				where deleted_date is null 
				and exists (select 1 from public_sys_user_group b 
				where a.id_jabatan = b.id_jabatan and b.group_id = 24) 
				and id_unit = " . $this->conn->escape($rowheader['id_unit']));
			}
			if ($this->post['idkey'] == 6) {
				$untuk_user = $rowheader['id_penanggung_jawab'];
			}

			$record = array(
				'page' => 'pemeriksaan',
				'untuk_user' => $untuk_user,
				'untuk' => $untuk,
				'id_status_pengajuan' => $this->post['idkey'],
				'id_pemeriksaan' => $id_pemeriksaan,
				'deskripsi' => $this->post['keteranganajuan'],
				'url' => "panelbackend/pemeriksaan_temuan/index/" . $id_pemeriksaan
			);

			// dpr($record, 1);

			$return = $this->InsertTask($record);

			
			if ($return['success'] && $this->post['idkey'] == 6) {
				foreach ($this->data['listtemuan']['rows'] as $rowtemuan)
			// 	dpr($rowtemuan);
			// dpr($rowtemuan['saranarr'],1);
					foreach ($rowtemuan['saranarr'] as $rsaran) {
						
						if (!$return['success'])
							break;

						$record = array(
							'page' => 'pemeriksaan',
							'untuk' => $rsaran['id_jabatan'],
							'id_status_pengajuan' => $this->post['idkey'],
							'id_pemeriksaan' => $id_pemeriksaan,
							'deskripsi' => "Ditunjuk sebagai PIC tindak lanjut " . $rsaran['deskripsi'] . " audit di temuan " . $rowtemuan['judul_temuan'],
							'url' => "panelbackend/pemeriksaan_tindak_lanjut/edit/" . $rowtemuan['id_pemeriksaan_temuan']
						);

						// dpr($record, 1);

						
						$return = $this->InsertTask($record);
					}
			}

			if ($return['success']) {
				$this->conn->goUpdate("pemeriksaan", ["id_status" => $this->post['idkey']], "id_pemeriksaan = " . $this->conn->escape($id_pemeriksaan));
				if ($record['id_status_pengajuan'] == 6) {
					$this->conn->goUpdate("pemeriksaan_temuan", ["status" => "Monev"], "id_pemeriksaan = " . $this->conn->escape($id_pemeriksaan) . " and is_disetujui='1'");
				}
			}

			redirect(current_url());
		}

		#KIRIM NOTIFIKASII




		// dpr($this->data['listtemuan'],1);

		$this->data['jumlahjenis'] = $this->conn->GetList("select jenis_temuan idkey, sum(1) as val 
		from pemeriksaan_temuan 
		where deleted_date is null and id_pemeriksaan = " . $this->conn->escape($id_pemeriksaan) . " 
		group by jenis_temuan");
		$jumlahsemua = 0;
		foreach ($this->data['jumlahjenis'] as $v) {
			$jumlahsemua += $v;
		}
		$this->data['jumlahjenis'][''] = $jumlahsemua;

		foreach ($this->data['listtemuan']['rows'] as &$r) {
			$r['tindaklanjutterakhir'] = $this->conn->GetRow("select * 
			from pemeriksaan_tindak_lanjut 
			where deleted_date is null and id_pemeriksaan_temuan = " . $this->conn->escape($r['id_pemeriksaan_temuan']) . " 
			order by id_pemeriksaan_tindak_lanjut desc");
		}

		// dpr($this->data['listtemuan'], 1);
		// $this->conn->debug = 1;

		$this->data['jumlahtemuan'] = $this->conn->GetOne("select count(1) from pemeriksaan_temuan 
		where deleted_date is null and id_pemeriksaan = " . $this->conn->escape($id_pemeriksaan) . $addfilter1);

		// dpr($this->data['jumlahtemuan'],1);

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

	public function Detail($id_pemeriksaan = null, $id_pemeriksaan_detail = null, $id = null)
	{
		$this->_beforeDetail($id_pemeriksaan, $id_pemeriksaan_detail, $id);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$this->_onDetail($id);

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	protected function _onDetail($id = null)
	{
		if ($id && $this->data['id_bidang_pemeriksaan_anggota'] && $this->data['row']['created_by'] <> $_SESSION[SESSION_APP]['user_id'] && !Access("index", "panelbackend/loginas")) {
			$this->data['access_role']['edit'] = $this->access_role['edit'] = false;
			$this->data['access_role']['delete'] = $this->access_role['delete'] = false;
			$this->data['edited'] = false;
		}

		return true;
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

		$this->_beforeDetail($id_pemeriksaan, $id_pemeriksaan_detail, $id);

		$this->data['idpk'] = $id;

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'] && $id)
			$this->NoData();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");

		if ($this->post && $this->post['act'] <> 'change') {
			if (!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id, $id_pemeriksaan_detail);

			$this->data['row'] = array_merge($this->data['row'], $this->post);
			$this->data['row'] = array_merge($this->data['row'], $record);
		}

		$this->data['rules'] = $this->Rules();

		$this->_onDetail($id);

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {

			$record['id_pemeriksaan'] = $id_pemeriksaan;
			$record['id_pemeriksaan_detail'] = $id_pemeriksaan_detail;

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


	protected function _afterDetail($id)
	{
		if ($this->modelfile) {
			if (!$this->data['row']['files']['id'] && $id) {
				$rows = $this->conn->GetArray("select *
				from {$this->modelfile->table}
				where deleted_date is null and {$this->model->pk} = " . $this->conn->escape($id));

				foreach ($rows as $r) {
					if ($r['jenis'] !== 'files_tanggapan') {
						$this->data['row']['files']['id'][] = $r[$this->modelfile->pk];
						$this->data['row']['files']['name'][] = $r['client_name'];
					} else {
						$this->data['row']['files_tanggapan'][$r['id_pemeriksaan_temuan_diskusi']]['id'][] = $r[$this->modelfile->pk];
						$this->data['row']['files_tanggapan'][$r['id_pemeriksaan_temuan_diskusi']]['name'][] = $r['client_name'];
					}
				}
			}
		}

		if ($this->data['row']['jenis_temuan'] !== 'Catatan')
			if (!$_SESSION[SESSION_APP][$this->data['rowheader']['jenis'] . 'jenis']) {
				if ($this->data['rowheader']['jenis'] == 'operasional') {
					$this->data['row']['jenis_temuan'] = 'Temuan';
				} else if ($this->data['rowheader']['jenis'] == 'mutu' || $this->data['rowheader']['jenis'] == 'penyuapan') {
					$this->data['row']['jenis_temuan'] = 'OBSERVASI';
				} else {
					$this->data['row']['jenis_temuan'] = 'Temuan';
				}
			} else
				$this->data['row']['jenis_temuan'] = $_SESSION[SESSION_APP][$this->data['rowheader']['jenis'] . 'jenis'];
	}

	protected function Record($id = null, $id_pemeriksaan_detail = null)
	{
		// dpr(strlen($_POST['kondisi']), 1);
		if ($this->data['id_bidang_pemeriksaan_anggota'])
			$_POST['id_bidang_pemeriksaan'] = $this->post['id_bidang_pemeriksaan'] = $this->data['id_bidang_pemeriksaan_anggota'];

		if ($id_pemeriksaan_detail) {

			$get_pemeriksaan_detail = $this->conn->GetRow('select id_kka,nama_user,user_id,id_jabatan,nama_jabatan from pemeriksaan_detail where id_pemeriksaan_detail = ' . $id_pemeriksaan_detail);
			$this->post['id_kka'] = $get_pemeriksaan_detail['id_kka'];
			$this->post['id_jabatan'] = $get_pemeriksaan_detail['id_jabatan'];
			$this->post['nama_jabatan'] = $get_pemeriksaan_detail['nama_jabatan'];
			$this->post['user_id'] = $get_pemeriksaan_detail['user_id'];
			$this->post['nama_user'] = $get_pemeriksaan_detail['nama_user'];
			// dpr($get_pemeriksaan_detail,1);

		}



		$return = array(
			'judul_temuan' => $this->post['judul_temuan'],
			'halaman_lhe' => $this->post['halaman_lhe'],
			// 'kondisi' => substr($_POST['kondisi'], 0, 65535),
			'kondisi' => $_POST['kondisi'],
			// 'kondisi1' => substr($_POST['kondisi'], 65535 - strlen($_POST['kondisi'])),
			'id_jabatan' => $this->post['id_jabatan'],
			'nama_jabatan' => $this->post['nama_jabatan'],
			'user_id' => $this->post['user_id'],
			'nama_user' => $this->post['nama_user'],

			'id_kka' => $this->post['id_kka'],
			'tmt' => $this->post['tmt'],
			'kriteria' => $this->post['kriteria'],
			'sebab' => $this->post['sebab'],
			'keterangan' => $this->post['keterangan'],
			'akibat' => $this->post['akibat'],
			'rekomendasi' => $this->post['rekomendasi'],
			'saran' => $this->post['saran'],
			'id_pemeriksaan' => $this->post['id_pemeriksaan'],
			'id_pemeriksaan_detail' => $this->post['id_pemeriksaan_detail'],
			'id_bidang_pemeriksaan' => $this->post['id_bidang_pemeriksaan'],
			'tgl_klarifikasi' => $this->post['tgl_klarifikasi'],
			'id_jabatan_auditor' => $this->post['id_jabatan_auditor'],
			'jabatan_auditor' => $this->post['jabatan_auditor'],
			'nama_jabatan_auditor' => $this->post['nama_jabatan_auditor'],
			'id_jabatan_auditee' => $this->post['id_jabatan_auditee'],
			'jabatan_auditee' => $this->post['jabatan_auditee'],
			'nama_jabatan_auditee' => $this->post['nama_jabatan_auditee'],
			'id_dokumen' => $this->post['id_dokumen'],
			'klausul' => $this->post['klausul'],
			'jenis_temuan' => $this->post['jenis_temuan'] ? $this->post['jenis_temuan'] : $_SESSION[SESSION_APP][$this->data['rowheader']['jenis'] . 'jenis'],
			'referensi' => $this->post['referensi'],
			'penyebab_ketidak_sesuaian' => $this->post['penyebab_ketidak_sesuaian'],
			'rencana_tindakan_perbaikan' => $this->post['rencana_tindakan_perbaikan'],
			'target_penyelesaian' => $this->post['target_penyelesaian'],
			'satuan' => $this->post['satuan'],
			'nilai_kerugian' => Rupiah2Number($this->post['nilai_kerugian']),
		);

		if ($this->post['jenis_temuan'] !== 'Catatan')
			if (!$_SESSION[SESSION_APP][$this->data['rowheader']['jenis'] . 'jenis']) {
				if ($this->data['rowheader']['jenis'] == 'operasional') {
					$return['jenis_temuan'] = 'Temuan';
				} else if ($this->data['rowheader']['jenis'] == 'mutu' || $this->data['rowheader']['jenis'] == 'penyuapan') {
					$return['jenis_temuan'] = 'OBSERVASI';
				} else {
					$return['jenis_temuan'] = 'Temuan';
				}
			}

		if (!$id) {
			if ($this->data['rowheader']['jenis'] == 'khusus' || $_SESSION[SESSION_APP][$this->data['rowheader']['jenis'] . 'jenis'] == 'Catatan') {
				$return['status'] = "Close";
				$return['is_disetujui'] = 0;
			} else {
				$return['status'] = "Pemeriksaan";
				// $return['is_disetujui'] = 1;
			}
		}

		if (!$this->data['row']) {
			$return['tahun'] = date("Y");
			$return['id_periode_tw'] = $this->conn->GetOne("select id_periode_tw from mt_periode_tw where deleted_date is null and '" . date('m') . "' between bulan_mulai and bulan_akhir");
		}

		if ($this->data['rowheader']['jenis'] == 'eksternal') {
			// "is_disetujui" => 1, 'status' => 'Monev'
			$return['is_disetujui'] = 1;
			$return['status'] = 'Monev';
		}

		return $return;
	}

	protected function Rules()
	{
		// dpr(strlen($_POST['kondisi']), 1);
		$return = array(
			"judul_temuan" => array(
				'field' => 'judul_temuan',
				'label' => 'Judul',
				'rules' => "max_length[4000]|required",
			),
			"kondisi" => array(
				'field' => 'kondisi',
				'label' => 'Kondisi',
				// 'rules' => "max_length[131070]",
			),
			"kriteria" => array(
				'field' => 'kriteria',
				'label' => 'Kriteria',
				'rules' => "max_length[65535]",
			),
			"sebab" => array(
				'field' => 'sebab',
				'label' => 'Sebab',
				'rules' => "max_length[65535]",
			),
			"akibat" => array(
				'field' => 'akibat',
				'label' => 'Akibat',
				'rules' => "max_length[65535]",
			),
			"rekomendasi" => array(
				'field' => 'rekomendasi',
				'label' => 'Rekomendasi',
				'rules' => "max_length[65535]",
			),
			"saran" => array(
				'field' => 'saran',
				'label' => 'Saran',
				'rules' => "max_length[65535]",
			),
			"id_jabatan_auditor" => array(
				'field' => 'id_jabatan_auditor',
				'label' => 'Jabatan Auditor',
				'rules' => "integer|max_length[65535]",
			),
			"id_bidang_pemeriksaan" => array(
				'field' => 'id_bidang_pemeriksaan',
				'label' => 'Bidang Pemeriksaan',
				'rules' => "integer|required",
			),
			"jabatan_auditor" => array(
				'field' => 'jabatan_auditor',
				'label' => 'Jabatan Auditor',
				'rules' => "max_length[100]",
			),
			"nama_jabatan_auditor" => array(
				'field' => 'nama_jabatan_auditor',
				'label' => 'Nama Jabatan Auditor',
				'rules' => "max_length[100]",
			),
			"id_jabatan_auditee" => array(
				'field' => 'id_jabatan_auditee',
				'label' => 'Jabatan Auditee',
				'rules' => "integer|max_length[65535]",
			),
			"jabatan_auditee" => array(
				'field' => 'jabatan_auditee',
				'label' => 'Jabatan Auditee',
				'rules' => "max_length[100]",
			),
			"nama_jabatan_auditee" => array(
				'field' => 'nama_jabatan_auditee',
				'label' => 'Nama Jabatan Auditee',
				'rules' => "max_length[100]",
			),
		);

		$bidangarr1 = $this->data['bidangarr'];
		unset($bidangarr1['']);
		if (!$bidangarr1) {
			unset($return['id_bidang_pemeriksaan']);
		}
		return $return;
	}

	public function go_print_lhp($page = 0, $id_pemeriksaan = null, $jenis = null)
	{

		$this->View($this->viewlist . 'lhpprint');
	}

	// public function go_print($page = 0, $id_pemeriksaan = null, $jenis = null)
	// {
	// 	if ($page == 'lhp') {
	// 		$this->go_print_lhp();
	// 		return;
	// 	}
	// 	$this->template = "panelbackend/main3";
	// 	$this->layout = "panelbackend/layoutprint";
	// 	$this->data['no_header'] = true;

	// 	$this->_beforeDetail($id_pemeriksaan);

	// 	if ($page == 'lhp') {
	// 		$this->data['page_title'] = 'Laporan Hasil Pemeriksaan';
	// 	} else {
	// 		$this->data['page_title'] = 'Daftar Temuan Pemeriksaan';
	// 	}

	// 	$this->data['page_title'] .= $this->data['jenis_title'];

	// 	$addfilter = " and jenis_temuan = " . $this->conn->escape($jenis);
	// 	if ($jenis == 'Temuan' || $jenis == 'MAJOR') {
	// 		$this->data['label'] = "DTP";
	// 		$this->data['label_desc'] = "DAFTAR TEMUAN PEMERIKSAAN";
	// 	} else {
	// 		$this->data['label'] = "DCP";
	// 		$this->data['label_desc'] = "DAFTAR CATATAN PEMERIKSAAN";
	// 	}
	// 	$rows = $this->model->SelectGrid(
	// 		[
	// 			'filter' => ($page == 'lhp' ? "is_disetujui='1' and " : null) .
	// 				($jenis == 'Temuan' || $jenis == 'MAJOR' ? " jenis_temuan in ('Temuan','MAJOR') and " : " jenis_temuan not in ('Temuan','MAJOR') and ") . "id_pemeriksaan = " . $this->conn->qstr($id_pemeriksaan) . $addfilter,
	// 			'limit' => -1
	// 		]
	// 	);

	// 	$this->data['listtemuan'] = array();
	// 	foreach ($rows['rows'] as $r) {
	// 		$bidang = $this->data['bidangarr'][$r['id_bidang_pemeriksaan']];
	// 		$this->data['listtemuan'][$bidang][] = $r;
	// 	}

	// 	$this->View($this->viewlist . 'print');
	// }

	public function go_print($page = 0, $id_pemeriksaan = null)
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout_print_pemeriksaan";
		$this->data['no_header'] = true;

		$this->_beforeDetail($id_pemeriksaan);



		if ($page == 'konsep')
			$this->data['page_title'] = "REVIU KONSEP LAPORAN<br/>Pengendali Teknis";


		// $this->conn->debug =1;
		$this->data['list']['rows'] = $this->conn->GetArray("
		select a.*,b.nama_user,b.nama_jabatan,date_format(c.created_date,'%Y-%m-%d') as tgl_ttd from pemeriksaan_temuan a 
		left join pemeriksaan_detail b on a.id_pemeriksaan_detail = b.id_pemeriksaan_detail 
		left join risk_task c on a.id_pemeriksaan = c.id_pemeriksaan and c.id_status_pengajuan = 5 
		where a.deleted_date is null and a.id_pemeriksaan =  " . $this->conn->escape($id_pemeriksaan));


		if ($this->data['list']['rows']) {
			foreach ($this->data['list']['rows'] as &$ret) {
				$ret['saranarr'] = $this->conn->GetArray("select * 
				from pemeriksaan_temuan_saran 
				where id_pemeriksaan_temuan = " . $this->conn->escape($ret['id_pemeriksaan_temuan']));
				$sasaran = "<ol>";
				if ($ret['saranarr'])
					foreach ($ret['saranarr'] as $r) {
						$sasaran .= "<li>" . $r['deskripsi'] . "</li>";
					}
				$sasaran .= "</ol>";
				$ret['sasaran'] = $sasaran;
				$ret['rekomendasi'] = $sasaran;

				$rowsd = $this->conn->GetArray("select * from pemeriksaan_temuan_diskusi 
				where id_pemeriksaan_temuan = " . $this->conn->escape($ret['id_pemeriksaan_temuan']));

				$str = "<ol>";
				$stra = "<ol>";
				foreach ($rowsd as $r) {
					if (!$r['is_auditi'])
						$str .= "<li>" . $r['keterangan'] . "</li>";
					else
						$stra .= "<li>" . $r['keterangan'] . "</li>";
				}
				$str .= "</ol>";
				$stra .= "</ol>";

				$ret['komentar_pengawas'] = $str;
				$ret['komentar_auditi'] = $stra;
			}
		}
		// $this->data['list'] = $this->model->SelectGrid(
		// 	[
		// 		'filter' => "id_pemeriksaan = " . $this->conn->escape($id_pemeriksaan),
		// 		'limit' => -1
		// 	]
		// );

		// dpr($this->data['list']['rows'],1);

		$this->View($this->viewlist . $page . 'print');
	}



	public function printdetail($printpage, $page = 0, $id_pemeriksaan = null)
	{

		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout5";
		$this->data['no_header'] = true;
		$this->data['rowheader']  = $this->pemeriksaan->GetByPk($id_pemeriksaan);

		$addfilter = "";
		// $addfilter = " and jenis_temuan = 'Temuan'";
		if ($_SESSION[SESSION_APP][$this->data['rowheader']['jenis'] . 'jenis']) {
			$addfilter = " and jenis_temuan = " . $this->conn->escape($_SESSION[SESSION_APP][$this->data['rowheader']['jenis'] . 'jenis']);
		}

		$addfilter1 = "";
		if ($_SESSION[SESSION_APP][$this->page_ctrl]['id_bidang_pemeriksaan_filter'])
			$addfilter1 = " and id_bidang_pemeriksaan = " . $this->conn->escape($_SESSION[SESSION_APP][$this->page_ctrl]['id_bidang_pemeriksaan_filter']);

		// $this->conn->debug = 1;
		// $this->data['listtemuan'] = $this->model->SelectGrid(
		// 	[
		// 		'filter' => "id_pemeriksaan = " . $this->conn->qstr($id_pemeriksaan) . $addfilter . $addfilter1,
		// 		'limit' => -1
		// 	]
		// );

		$this->data['listtemuan']['rows'] = $this->conn->GetArray("
		select a.*,b.nama_user,b.nama_jabatan,c.nama_pereview from pemeriksaan_temuan a 
		left join pemeriksaan_detail b on a.id_pemeriksaan_detail = b.id_pemeriksaan_detail 
		left join pemeriksaan c on a.id_pemeriksaan = c.id_pemeriksaan
		where a.deleted_date is null and a.id_pemeriksaan =  " . $this->conn->escape($id_pemeriksaan));

		$this->data['tgl_ttd'] = $this->conn->GetOne("
		select date_format(c.created_date,'%Y-%m-%d') as tgl_ttd from pemeriksaan_temuan a 
		left join pemeriksaan_detail b on a.id_pemeriksaan_detail = b.id_pemeriksaan_detail 
		left join risk_task c on a.id_pemeriksaan = c.id_pemeriksaan and c.id_status_pengajuan = 5 
		where a.deleted_date is null and a.id_pemeriksaan =  " . $this->conn->escape($id_pemeriksaan));




		if ($this->data['listtemuan']['rows']) {
			foreach ($this->data['listtemuan']['rows'] as &$ret) {
				$ret['saranarr'] = $this->conn->GetArray("select * 
				from pemeriksaan_temuan_saran 
				where id_pemeriksaan_temuan = " . $this->conn->escape($ret['id_pemeriksaan_temuan']));
				$sasaran = "<ol>";
				if ($ret['saranarr'])
					foreach ($ret['saranarr'] as $r) {
						$sasaran .= "<li>" . $r['deskripsi'] . "</li>";
					}
				$sasaran .= "</ol>";
				$ret['sasaran'] = $sasaran;
				$ret['rekomendasi'] = $sasaran;

				$rowsd = $this->conn->GetArray("select * from pemeriksaan_temuan_diskusi 
				where id_pemeriksaan_temuan = " . $this->conn->escape($ret['id_pemeriksaan_temuan']));

				$str = "<ol>";
				$stra = "<ol>";
				foreach ($rowsd as $r) {
					if (!$r['is_auditi'])
						$str .= "<li>" . $r['keterangan'] . "</li>";
					else
						$stra .= "<li>" . $r['keterangan'] . "</li>";
				}
				$str .= "</ol>";
				$stra .= "</ol>";

				$ret['komentar_pengawas'] = $str;
				$ret['komentar_auditi'] = $stra;
			}
		}


		$this->data['pimpinan_auditi'] = $this->conn->GetOne("select name from public_sys_user where id_jabatan = (select min(id_jabatan) 
			from mt_sdm_jabatan a
			where deleted_date is null 
			and exists (select 1 from public_sys_user_group b 
			where a.id_jabatan = b.id_jabatan and b.group_id = 24) 
			and id_unit = " . $this->conn->escape($this->data['rowheader']['id_unit']) . ")");
		// foreach ($this->data['listtemuan']['rows'] as &$r) {
		// 	$r['tindaklanjutterakhir'] = $this->conn->GetRow("select * 
		// 	from pemeriksaan_tindak_lanjut 
		// 	where deleted_date is null and id_pemeriksaan_temuan = " . $this->conn->escape($r['id_pemeriksaan_temuan']) . " 
		// 	order by id_pemeriksaan_tindak_lanjut desc");
		// }

		// dpr($this->data['listtemuan']['rows'],1);

		// $this->data['row'] = $this->model->GetByPk($id);
		$this->data['listba'] = $this->conn->GetArray("select 
		a.id_pemeriksaan_detail,a.uraian,b.id_bidang_pemeriksaan, c.nama as bidang_pemeriksaan, 
		(case when b.status<>'Close' and b.status is not null then b.nilai_kerugian else null END) AS nilai_kerugian_monev,
		   (case when b.status='Close' and b.status is not null then b.nilai_kerugian else null end) as nilai_kerugian_close,
		   count(case when b.status<>'Close' and b.status is not null then 1 else null end) as jumlah_monev,
		   count(case when b.status='Close' and b.status is not null then 1 else null end) as jumlah_close,
		   count(b.id_pemeriksaan_temuan) as jumlah,
		   b.nilai_kerugian AS nilai_jumlah
		   from pemeriksaan_detail a 
		   left join pemeriksaan_temuan b on b.is_disetujui = 1 and a.id_pemeriksaan_detail = b.id_pemeriksaan_detail 
		   left join mt_bidang_pemeriksaan c on b.id_bidang_pemeriksaan = c.id_bidang_pemeriksaan
		   where a.deleted_date is null and b.deleted_date is NULL AND c.deleted_date IS NULL AND a.id_pemeriksaan = " . $this->conn->escape($id_pemeriksaan) . " 
		   group BY  a.id_pemeriksaan_detail,b.id_bidang_pemeriksaan,a.uraian,b.status,b.nilai_kerugian");

		$this->data['manajer_spi'] = $this->conn->GetOne('SELECT name FROM public_sys_user WHERE id_jabatan = 3267');

		$this->_getDetailPrint($id_pemeriksaan);

		// if (!$this->data['row'])
		// 	$this->NoData();
		if ($printpage == 1) {
			$this->View($this->viewprintdetail);
		} elseif ($printpage == 2) {
			$this->View($this->viewprintdetailpemantauan);
		} elseif ($printpage == 3) {
			$this->View($this->viewprintdetailba);
		} elseif ($printpage == 4) {
			$this->view($this->viewprintdetailtindaklanjut);
		}
	}

	public function printdetailtindaklanjut($page = 0, $id_pemeriksaan = null)
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout5";
		$this->data['no_header'] = true;
		$this->data['rowheader']  = $this->pemeriksaan->GetByPk($id_pemeriksaan);

		$addfilter = "";
		// $addfilter = " and jenis_temuan = 'Temuan'";
		if ($_SESSION[SESSION_APP][$this->data['rowheader']['jenis'] . 'jenis']) {
			$addfilter = " and jenis_temuan = " . $this->conn->escape($_SESSION[SESSION_APP][$this->data['rowheader']['jenis'] . 'jenis']);
		}

		$addfilter1 = "";
		if ($_SESSION[SESSION_APP][$this->page_ctrl]['id_bidang_pemeriksaan_filter'])
			$addfilter1 = " and id_bidang_pemeriksaan = " . $this->conn->escape($_SESSION[SESSION_APP][$this->page_ctrl]['id_bidang_pemeriksaan_filter']);

		// $this->conn->debug = 1;
		$this->data['listtemuan'] = $this->model->SelectGrid(
			[
				'filter' => "id_pemeriksaan = " . $this->conn->qstr($id_pemeriksaan) . $addfilter . $addfilter1,
				'limit' => -1
			]
		);

		// $this->data['row'] = $this->model->GetByPk($id);

		$this->_getDetailPrint($id_pemeriksaan);

		// if (!$this->data['row'])
		// 	$this->NoData();

		$this->View($this->viewprintdetailtindaklanjut);
	}

	protected function _afterInsert($id)
	{
		$ret = true;
		if ($ret)
			$ret = $this->_afterUpdate($id);

		if ($ret && $this->data['rowheader']['jenis'] != 'eksternal') {
			$ret = $this->conn->goUpdate("pemeriksaan", ["id_status" => 2], "id_pemeriksaan = " . $this->conn->escape($this->data['rowheader']['id_pemeriksaan']));
		}

		return $ret;
	}

	protected function _afterUpdate($id)
	{
		$ret = true;

		// $this->conn->debug=1;
		if ($this->modelfile) {
			if (!empty($this->post['files'])) {
				foreach ($this->post['files']['id'] as $k => $v) {
					$return = $this->_updateFiles(array($this->pk => $id), $v);

					$ret = $return['success'];
				}
			}
		}

		if ($ret) {
			if ($this->post['saranarr']) {
				$saranarr = $this->conn->GetList("select 
				id_pemeriksaan_temuan_saran as idkey, id_pemeriksaan_temuan_saran as val 
				from pemeriksaan_temuan_saran 
				where deleted_date is null and id_pemeriksaan_temuan = " . $this->conn->escape($id));
				foreach ($this->post['saranarr'] as $r) {
					$r['id_pemeriksaan_temuan'] = $id;
					if ($r['id_pemeriksaan_temuan_saran']) {
						$ret = $this->conn->goUpdate("pemeriksaan_temuan_saran", $r, "id_pemeriksaan_temuan_saran = " . $this->conn->escape($r['id_pemeriksaan_temuan_saran']) . " and id_pemeriksaan_temuan = " . $this->conn->escape($r['id_pemeriksaan_temuan']));
						unset($saranarr[$r['id_pemeriksaan_temuan_saran']]);
					} else {
						$ret = $this->conn->goInsert("pemeriksaan_temuan_saran", $r);
					}
				}

				if ($saranarr && $ret) {
					$ret = $this->conn->Execute("delete from pemeriksaan_temuan_saran
					where id_pemeriksaan_temuan = " . $this->conn->escape($id) . "
					and id_pemeriksaan_temuan_saran in (" . implode(",", $saranarr) . ")");
				}
			}
		}

		// dpr($ret);
		// dpr($this->post['files'],1);
		return $ret;
	}

	protected function _beforeDelete($id = null)
	{
		$ret = true;
		if ($this->modelfile)
			$ret = $this->conn->Execute("update {$this->modelfile->table} set deleted_date = now() where {$this->pk} = " . $this->conn->escape($id));

		$rows = $this->conn->GetArray("select * from pemeriksaan_tindak_lanjut where deleted_date is null and {$this->pk} = " . $this->conn->escape($id));
		foreach ($rows as $r) {
			if (!$ret)
				break;

			$ret = $this->conn->Execute("update pemeriksaan_tindak_lanjut_saran set deleted_date = now() where id_pemeriksaan_tindak_lanjut = " . $this->conn->escape($r['id_pemeriksaan_tindak_lanjut']));

			if ($ret)
				$ret = $this->conn->Execute("update pemeriksaan_tindak_lanjut_files set deleted_date = now() where id_pemeriksaan_tindak_lanjut = " . $this->conn->escape($r['id_pemeriksaan_tindak_lanjut']));
		}

		if ($ret)
			$ret = $this->conn->Execute("update pemeriksaan_temuan_saran set deleted_date = now() where {$this->pk} = " . $this->conn->escape($id));

		if ($ret)
			$ret = $this->conn->Execute("update pemeriksaan_temuan_diskusi set deleted_date = now() where {$this->pk} = " . $this->conn->escape($id));

		return $ret;
	}
}
