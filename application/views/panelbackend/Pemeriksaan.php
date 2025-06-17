<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Pemeriksaan extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/pemeriksaanlist";
		$this->viewdetail = "panelbackend/pemeriksaandetail";
		$this->viewprintrencanaaudit = "panelbackend/pemeriksaanprintrencanaaudit";
		$this->viewprintanggaranbiayaaudit = "panelbackend/pemeriksaanprintanggaranbiayaaudit";
		$this->viewprintrekapitulasiaudit = "panelbackend/viewprintrekapitulasiaudit";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah KAK Audit';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit KAK Audit';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->layout = "panelbackend/layout_pemeriksaan";
			$this->data['page_title'] = 'Detail KAK Audit';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Audit';
		}

		if ($this->mode == 'monev') {
			$this->data['mode'] = $this->mode = 'index';
		}

		$this->load->model("PemeriksaanModel", "model");
		$this->load->model("Mt_sdm_unitModel", "modelunit");

		$this->load->model("Mt_sdm_subbidModel", "mtsdmsubbid");
		$this->load->model("Mt_sdm_jabatanModel", "modeljabatan");

		$this->data['unitarr'] = $this->modelunit->GetCombo();
		$this->data['jabatanarr'] = array('' => '') + $this->modeljabatan->GetCombo();

		$this->load->model("Mt_jenis_audit_eksternalModel", "mtjenisauditeksternal");
		$this->data['jeniseksternalarr'] = $this->mtjenisauditeksternal->GetCombo();

		$this->load->model("Mt_bidang_pemeriksaanModel", "mtbidangpemeriksaanarr");

		$this->load->model("Mt_status_pemeriksaanModel", "mtstatuspemeriksaan");
		$this->data['statusarr'] = $this->mtstatuspemeriksaan->GetCombo();

		$this->load->model("Public_sys_userModel", "usermodel");
		$this->data['userarr'] = $this->usermodel->GetCombo();

		$this->load->model("Risk_sasaranModel", "msasaran");
		$this->data['sasaranarr'] = $this->msasaran->GetCombo();

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

		$this->load->model("Pemeriksaan_spnModel", "spn");
		$this->data['spnarr'] = $this->spn->GetCombo();

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'select2', 'treetable'
		);
		$this->data['configfile'] = $this->config->item('file_upload_config');
	}

	protected function _beforeDetail($jenis = null, $id = null)
	{
		$this->data['add_param'] .= $jenis;

		$this->data['jenis'] = $jenis;

		$this->data['jenis_title'] = [
			"operasional" => "Operasional",
			"mutu" => "Mutu Internal",
			"penyuapan" => "Anti Penyuapan",
			"khusus" => "Khusus",
			"eksternal" => "Eksternal"
		][$jenis];
		$this->data['page_title'] .= " " . $this->data['jenis_title'];

		// $this->conn->debug = 1;
		$this->data['bidangpemeriksaanarr'] = $this->conn->GetList("select id_bidang_pemeriksaan as idkey, nama as val from mt_bidang_pemeriksaan where deleted_date is null and jenis = " . $this->conn->escape($jenis));
		$this->data['bidangpemeriksaanarr'][''] = "Pilih bidang pemeriksaan";
		// dpr($this->data['bidangpemeriksaanarr'], 1);
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

		if ($this->data['row']['id_unit']) {
			$this->data['subbidarr'] = $this->mtsdmsubbid->GetComboUnit($this->data['row']['id_unit']);
		}

		if (!$this->data['row']['pemeriksaan_tim'])
			$this->data['row']['pemeriksaan_tim'] = $this->conn->GetArray("select * from pemeriksaan_tim where deleted_date is null and id_pemeriksaan = " . $this->conn->escape($id));

		$this->data['penanggungjawabarr'][$this->data['row']['id_penanggung_jawab']] = $this->data['row']['nama_penanggung_jawab'];
		$this->data['pimpinanarr'][$this->data['row']['id_penyusun']] = $this->data['row']['nama_penyusun'];
		$this->data['pelaksanaarr'][$this->data['row']['id_pereview']] = $this->data['row']['nama_pereview'];

		$this->data['rowheader'] = $this->data['row'];
	}

	public function Add($jenis = null)
	{
		$this->Edit($jenis);
	}

	protected function _onDetail($id = null)
	{
		if ($this->data['row']['id_unit']) {
			$this->data['userarr'] = $this->usermodel->GetCombo($this->data['row']['id_unit']);
		}
		return true;
	}

	public function Edit($jenis = null, $id = null)
	{

		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->_beforeDetail($jenis, $id);

		$this->data['idpk'] = $id;

		$this->data['row'] = $this->model->GetByPk($id);

		$this->data['row']['jenis'] = $jenis;

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
			// $this->conn->debug = 1;
			$record['jenis'] = $jenis;

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

				if ($jenis !== 'eksternal')
					$record['id_status'] = 1;
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
				redirect("$this->page_ctrl/detail/$jenis/$id");
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

	protected function _beforeDelete($id = null)
	{
		$ret = true;
		$files = [];
		if ($ret) {
			$rows = $this->conn->GetArray("select * from pemeriksaan_temuan where deleted_date is null and id_pemeriksaan = " . $this->conn->escape($id));
			foreach ($rows as $r) {
				if (!$ret)
					break;

				$rws = $this->conn->GetArray("select * 
				from pemeriksaan_temuan_files 
				where deleted_date is null and id_pemeriksaan_temuan = " . $this->conn->escape($r['id_pemeriksaan_temuan']));
				foreach ($rws as $r1) {
					if (!$ret)
						break;

					$files[] = $this->data['configfile']['upload_path'] . $r1['file_name'];
				}

				$rws = $this->conn->GetArray("select * 
				from pemeriksaan_tindak_lanjut 
				where deleted_date is null and id_pemeriksaan_temuan = " . $this->conn->escape($r['id_pemeriksaan_temuan']));
				foreach ($rws as $r1) {
					if (!$ret)
						break;


					$rws1 = $this->conn->GetArray("select * 
				from pemeriksaan_tindak_lanjut_files 
				where deleted_date is null and id_pemeriksaan_tindak_lanjut = " . $this->conn->escape($r2['id_pemeriksaan_tindak_lanjut']));
					foreach ($rws1 as $r2) {
						if (!$ret)
							break;

						$files[] = $this->data['configfile']['upload_path'] . $r2['file_name'];
					}


					if ($ret)
						$ret = $this->conn->Execute("update 
					 pemeriksaan_tindak_lanjut_saran set deleted_date = now() 
					where id_pemeriksaan_tindak_lanjut = " . $this->conn->escape($r1['id_pemeriksaan_tindak_lanjut']));
				}

				if ($ret)
					$ret = $this->conn->Execute("update 
				 pemeriksaan_temuan_saran set deleted_date = now() 
				where id_pemeriksaan_temuan = " . $this->conn->escape($r['id_pemeriksaan_temuan']));

				if ($ret)
					$ret = $this->conn->Execute("update  
				 pemeriksaan_temuan_diskusi set deleted_date = now() 
				where id_pemeriksaan_temuan = " . $this->conn->escape($r['id_pemeriksaan_temuan']));

				if ($ret)
					$ret = $this->conn->Execute("update  
				 pemeriksaan_temuan_files set deleted_date = now()
				where id_pemeriksaan_temuan = " . $this->conn->escape($r['id_pemeriksaan_temuan']));
			}

			if ($ret)
				$ret = $this->conn->Execute("update pemeriksaan_tim set deleted_date = now() where id_pemeriksaan = " . $this->conn->escape($id));

			if ($ret)
				$ret = $this->conn->Execute("update pemeriksaan_temuan set deleted_date = now() where id_pemeriksaan = " . $this->conn->escape($id));
		}

		if ($ret) {
			foreach ($files as $file) {
				unset($file);
			}
		}
		return $ret;
	}

	public function Delete($jenis = null, $id = null)
	{

		$this->model->conn->StartTrans();

		$this->_beforeDetail($id);

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
			redirect("$this->page_ctrl/index/$jenis");
		} else {
			SetFlash('err_msg', "Data gagal didelete");
			redirect("$this->page_ctrl/detail/$jenis/$id");
		}
	}

	public function Index_detail($jenis = null, $page = 0)
	{
		$this->viewlist = "panelbackend/pemeriksaandetaillist";
		$this->_beforeDetail($jenis);

		$this->data['header'] = $this->Header();

		$this->_setFilter("jenis = " . $this->conn->escape($jenis));

		$this->data['list'] = $this->_getList($page);

		$this->data['page'] = $page;

		$param_paging = array(
			'base_url' => base_url("{$this->page_ctrl}/index/$jenis"),
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

	public function Index($jenis = null, $page = 0)
	{
		// if ($jenis == 'eksternal') {
		// 	redirect("panelbackend/pemeriksaan/monev/eksternal");
		// }
		// $this->data['page_title'] = 'Temuan Audit';
		$this->_beforeDetail($jenis);
		$this->_filter();

		// $tahun = $this->data['tahun_filter'];
		// $id_periode_tw = $this->data['id_periode_tw_filter'];
		$id_spn = $this->data['id_spn_filter'];

		$addfilter = "";
		if ($id_spn) {
			$addfilter = " and a.id_spn = " . $this->conn->escape($id_spn);
		}

		if (!$this->Access("view_all", "main")) {
			$addfilter .= " and (a.id_unit = " . $this->conn->escape($_SESSION[SESSION_APP]['id_unit']) . " 
			or b.id_pic = " . $this->conn->escape($_SESSION[SESSION_APP]['id_jabatan']) . ")";
		}

		$rows = $this->conn->GetArray("select 
		a.id_unit, b.id_bidang_pemeriksaan, c.nama as bidang_pemeriksaan, 
		a.id_pemeriksaan, a.nama, 
		count(case when b.status<>'Close' and b.status is not null then 1 else null end) as jumlah_monev,
		count(case when b.status='Close' and b.status is not null then 1 else null end) as jumlah_close,
		count(b.id_pemeriksaan_temuan) as jumlah
		from pemeriksaan a 
		left join pemeriksaan_temuan b on b.is_disetujui = 1 and a.id_pemeriksaan = b.id_pemeriksaan 
		left join mt_bidang_pemeriksaan c on b.id_bidang_pemeriksaan = c.id_bidang_pemeriksaan
		where a.deleted_date is null and a.deleted_date is null and a.jenis = " . $this->conn->escape($this->data['jenis']) . " $addfilter 
		group by a.id_unit, b.id_bidang_pemeriksaan, c.nama, a.id_pemeriksaan, a.nama");

		// $rows = $this->conn->GetArray("select 
		// a.id_unit, b.id_bidang_pemeriksaan, c.nama as bidang_pemeriksaan, 
		// a.id_pemeriksaan, a.nama, 
		// count(case when b.status='Monev' then 1 else null end) as jumlah_monev,
		// count(case when b.status='Close' then 1 else null end) as jumlah_close,
		// count(case when b.status='Monev' or b.status='Close' then 1 else null end) as jumlah
		// from pemeriksaan a 
		// left join pemeriksaan_temuan b on a.id_pemeriksaan = b.id_pemeriksaan 
		// left join mt_bidang_pemeriksaan c on b.id_bidang_pemeriksaan = c.id_bidang_pemeriksaan
		// where a.jenis = " . $this->conn->escape($this->data['jenis']) . " 
		// and ((DATE_FORMAT(a.tgl_mulai,'%Y') = " . $this->conn->escape($tahun) . " 
		// and b.id_pemeriksaan_temuan is null) 
		// or (b.tahun = " . $this->conn->escape($tahun) . " and b.id_periode_tw = " . $this->conn->escape($id_periode_tw) . ")) $addfilter 
		// group by a.id_unit, b.id_bidang_pemeriksaan, c.nama, a.id_pemeriksaan, a.nama");

		$tgl_efektif = date('Y-m-d');

		list($tahun, $bln, $tgl) = explode("-", $tgl_efektif);
		$this->data['rows'] = array();
		$this->data['totalunit'] = array();
		foreach ($rows as $r) {
			$this->data['rows'][$r['id_unit']][$r['id_pemeriksaan']][$r['id_bidang_pemeriksaan']] = $r;
			$this->data['totalunit'][$r['id_unit']][$r['id_bidang_pemeriksaan']]['jumlah'] += $r['jumlah'];
			$this->data['totalunit'][$r['id_unit']][$r['id_bidang_pemeriksaan']]['jumlah_monev'] += $r['jumlah_monev'];
			$this->data['totalunit'][$r['id_unit']][$r['id_bidang_pemeriksaan']]['jumlah_close'] += $r['jumlah_close'];
		}
		// dpr($this->data['rows'], 1);
		$this->data['addbutton'] = UI::createSelect("id_spn_filter", $this->data['spnarr'], $this->data['id_spn_filter'], true, 'form-control', "style='width:200px !important; display:inline' onchange='goSubmit(\"set_filter\")'");
		$this->data['addbutton'] .= "&nbsp;<a target='_BLANK' href='" . site_url("panelbackend/pemeriksaan/printdetail/1/$jenis/$tahun") . "' class='btn btn-sm btn-primary'><i class='bi bi-printer'></i> Rencana Audit Dari Objek Audit</a>";
		$this->data['addbutton'] .= "&nbsp;<a target='_BLANK' href='" . site_url("panelbackend/pemeriksaan/printdetail/2/$jenis/$tahun") . "' class='btn btn-sm btn-primary'><i class='bi bi-printer'></i> Anggaran Biaya Audit</a>";
		$this->data['addbutton'] .= "&nbsp;<a target='_BLANK' href='" . site_url("panelbackend/pemeriksaan/go_print_lhp/$jenis") . "' class='btn btn-sm btn-primary'><i class='bi bi-printer'></i> Rekapitulasi Audit</a>";

		if ($jenis != 'eksternal') {
			$this->data['addbutton'] = UI::createSelect("id_spn_filter", $this->data['spnarr'], $this->data['id_spn_filter'], true, 'form-control', "style='width:200px !important; display:inline' onchange='goSubmit(\"set_filter\")'");
			$this->data['addbutton'] .= "&nbsp;<a target='_BLANK' href='" . site_url("panelbackend/pemeriksaan/printdetail/1/$jenis/$tahun") . "' class='btn btn-sm btn-primary'><i class='bi bi-printer'></i> Rencana Audit Dari Objek Audit</a>";
			$this->data['addbutton'] .= "&nbsp;<a target='_BLANK' href='" . site_url("panelbackend/pemeriksaan/printdetail/2/$jenis/$tahun") . "' class='btn btn-sm btn-primary'><i class='bi bi-printer'></i> Anggaran Biaya Audit</a>";
			$this->data['addbutton'] .= "&nbsp;<a target='_BLANK' href='" . site_url("panelbackend/pemeriksaan/go_print_lhp/$jenis") . "' class='btn btn-sm btn-primary'><i class='bi bi-printer'></i> Rekapitulasi Audit</a>";
		}
		// if ($this->Access("evaluasi", "panelbackend/pemeriksaan_tindak_lanjut"))
		// if ($this->Access("view_all", "main"))
		// 	if ($this->data['jenis'] != 'eksternal')
		// $this->data['addbutton'] .= "&nbsp;<a target='_BLANK' href='" . site_url("panelbackend/pemeriksaan/go_print/$jenis/lhp") . "' class='btn btn-sm btn-primary'><i class='bi bi-download'></i> LHP</a>";

		$this->View($this->viewlist);
	}

	public function printdetail($printpage, $jenis = 0, $tahun = null)
	{

		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout5";
		$this->data['no_header'] = true;

		$this->_beforeDetail($jenis);
		$this->_filter();


		$this->data['tahun'] = $tahun;
		if ($printpage != 3) {
			$id_spn = $this->data['id_spn_filter'];

			$addfilter = "";
			if ($id_spn) {
				$this->data['page_title'] .= "<br/><small style='color:#000'>" . $this->data['spnarr'][$id_spn] . "</small>";
				$addfilter = " and a.id_spn = " . $this->conn->escape($id_spn);
			}


			if (!$this->Access("view_all", "main")) {
				$addfilter .= " and (a.id_unit = " . $this->conn->escape($_SESSION[SESSION_APP]['id_unit']) . " 
				or b.id_pic = " . $this->conn->escape($_SESSION[SESSION_APP]['id_jabatan']) . ")";
			}

			$this->data['rows']['pemeriksaan'] = $this->conn->GetArray("select 
			a.id_unit, b.id_bidang_pemeriksaan, c.nama as bidang_pemeriksaan, 
			a.id_pemeriksaan, a.nama,a.tgl_mulai,a.tgl_selesai,a.nama_sasaran,a.tujuan,
			count(case when b.status<>'Close' and b.status is not null then 1 else null end) as jumlah_monev,
			count(case when b.status='Close' and b.status is not null then 1 else null end) as jumlah_close,
			count(b.id_pemeriksaan) as jumlah
			from pemeriksaan a 
			left join pemeriksaan_temuan b on b.is_disetujui = 1 and a.id_pemeriksaan = b.id_pemeriksaan 
			left join mt_bidang_pemeriksaan c on b.id_bidang_pemeriksaan = c.id_bidang_pemeriksaan
			where a.deleted_date is null and a.jenis = " . $this->conn->escape($this->data['jenis']) . " $addfilter 
			group by a.id_unit, b.id_bidang_pemeriksaan, c.nama, a.id_pemeriksaan, a.nama");

			foreach ($this->data['rows']['pemeriksaan'] as $r) {
				$getpetugas[$r['id_pemeriksaan']] = $this->conn->GetArray("select id_pemeriksaan,nama_user,nama_jabatan from pemeriksaan_detail where deleted_date is null and id_pemeriksaan = " . $this->conn->escape($r['id_pemeriksaan']));
				$getakomodasi[$r['id_pemeriksaan']] = $this->conn->GetArray("select id_pemeriksaan,nilai_realisasi,nama_jenis from pemeriksaan_anggaran_biaya where deleted_date is null and id_pemeriksaan = " . $this->conn->escape($r['id_pemeriksaan']));
			}
			// dpr($this->data['akomodasi']);
			foreach ($getakomodasi as $key => $val) {
				foreach ($val as $val1) {
					$this->data['akomodasi'][$key][$val1['nama_jenis']] = $val1['nilai_realisasi'];
				}
			}
			// dpr($this->data['akomodasi']);
			foreach ($getpetugas as $key => $r) {
				foreach ($r as $d) {
					$this->data['nama_petugas'][$key] .= $d['nama_user'] . '<br/>';
					$this->data['jabatan_petugas'][$key] .= $d['nama_jabatan'] . '<br/>';
				}
			}
		} else {

			$month = date("m");
			for ($x = 1; $x <= (int)$month; $x++) {
				$getarr[$x] = $this->conn->GetArray("SELECT * FROM pemeriksaan p LEFT JOIN pemeriksaan_anggaran_biaya pab ON  p.id_pemeriksaan = pab.id_pemeriksaan WHERE p.deleted_date is null and MONTH(tgl_selesai) = " . $this->conn->escape($x) . " and YEAR(tgl_selesai) = " . $this->conn->escape($tahun));
			}

			// dpr($getarr);
			foreach ($getarr as $key => $val) {
				$this->data['row'][$key] = array();
				foreach ($val as $key1 => $val1) {
					$this->data['row'][$key][$val1['nama_jenis']] += (int)$val1['nilai_realisasi'];
				}
			}
		}


		$this->_getDetailPrint($id_pemeriksaan);
		if ($printpage == 1) {
			$this->View($this->viewprintrencanaaudit);
		} elseif ($printpage == 2) {
			$this->View($this->viewprintanggaranbiayaaudit);
		} elseif ($printpage == 3) {
			$this->View($this->viewprintrekapitulasiaudit);
		}
	}


	public function go_print_lhp($jenis = null)
	{
		$this->_beforeDetail($jenis);
		$this->_filter();

		// $this->load->library("word");
		// $word = $this->word;
		// $template = "./assets/template/" . "tamplete_lhp.docx";

		// if (!file_exists($template))
		// 	die("File template tidak ditemukan" . $template);

		// $word->template($template);
		// $temp = $word->templateProcessor;
		// $phpWord = $word->phpword();

		// $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		// $section = $phpWord->addSection();

		// header('Content-Type: application/doc');
		// header("Content-Disposition: attachment; filename=\"" . 'LHP ' . date("d-m-Y") . '.docx' . "\"");
		$this->_setContent($writer, $section, $temp);
		// $word->download('LHP ' . date("d-m-Y") . '.docx');
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layoutprintdoc";
		$this->View($this->viewlist . 'printdoc');
	}

	private function _setContent(&$writer = null, &$section = null, &$temp = null)
	{

		$tahun = $this->data['tahun_filter'];

		$this->load->model("Pemeriksaan_temuanModel", "modeltemuan");

		$rows = $this->modeltemuan->SelectGrid(
			[
				'filter' => " ((jenis_temuan = 'Temuan' and is_disetujui='1')/* or jenis_temuan='Catatan'*/) 
				and tahun = " . $this->conn->qstr($tahun),
				'limit' => -1
			]
		);

		$this->data['rows'] = array();
		foreach ($rows['rows'] as $r) {
			$this->data['rows'][$r['id_pemeriksaan']][$r['id_bidang_pemeriksaan']][] = $r;
		}

		// $str = "<ol>";
		// foreach ($this->data['rows'] as $id_pemeriksaan => $rs) {
		// 	$rw = $this->model->GetByPk($id_pemeriksaan);
		// 	$str .= "<li>";
		// 	$str .= "<b>" . $this->data['unitarr'][$rw['id_unit']] . "</b>";
		// 	foreach ($rs as $id_bidang_pemeriksaan => $rss) {
		// 		$str .= "<br/><b>" . $this->data['bidangpemeriksaanarr'][$id_bidang_pemeriksaan] . "</b>";
		// 		foreach ($rss as $r) {
		// 			$str .= "<br/><br/>" . $r['judul_temuan'];
		// 			$str .= "<br/><br/><b>Kondisi</b>" . $r['kondisi'];
		// 			$str .= "<br/><b>Kriteria</b>" . $r['kriteria'];
		// 			$str .= "<br/><b>Sebab</b>" . $r['sebab'];
		// 			$str .= "<br/><b>Akibat</b>" . $r['akibat'];
		// 			$str .= "<br/><b>Rekomendasi</b>" . $r['rekomendasi'];
		// 			$tanggapan = implode(",", $this->conn->GetList("select 
		// 		id_pemeriksaan_temuan_diskusi as idkey, 
		// 		keterangan as val 
		// 		from pemeriksaan_temuan_diskusi 
		// 		where keterangan is not null and keterangan <> '' and id_pemeriksaan_temuan = " . $this->conn->escape($r['id_pemeriksaan_temuan'])));
		// 			$str .= "<br/><b>Tanggapan Penanggung Jawab Objek Pemeriksaan</b><br/>" . $tanggapan;
		// 			$kesimpulan = implode(",", $this->conn->GetList("select 
		// 		id_pemeriksaan_tindak_lanjut as idkey, 
		// 		kesimpulan as val 
		// 		from pemeriksaan_tindak_lanjut 
		// 		where kesimpulan is not null and kesimpulan <> '' and id_pemeriksaan_temuan = " . $this->conn->escape($r['id_pemeriksaan_temuan'])));
		// 			$str .= "<br/><b>Kesimpulan</b>" . $kesimpulan;
		// 		}
		// 	}
		// 	$str .= "</li>";
		// }
		// $str .= "</ol>";
		// 	echo $str;
		// 	// die();
		// 	$str = '<ol>
		// 	<li><b>Divisi Renstra, Manajemen Risiko & TIK</b><br /><b>Bidang Pendapatan</b><br /><br />Ada aplikasi yang tidak
		// 		digunakan<br /><br /><b>Kondisi</b>
		// 		<p>Parah</p>
		// 		<p></p><br /><b>Kriteria</b>
		// 		<p>kesiasiaan</p><br /><b>Sebab</b>
		// 		<p>Tidak sesuai dengan kebutuhan</p><br /><b>Akibat</b>
		// 		<p>pembengkakan anggaran</p><br /><b>Rekomendasi</b>
		// 		<ol>
		// 			<li>
		// 				<p>Dukungan atasan untuk menekan user terkait dalam penggunaan aplikasi</p>
		// 			</li>
		// 			<li>
		// 				<p>Dibuatkan tor secara mendetail</p>
		// 			</li>
		// 			<li>
		// 				<p>Diadakan weekly report</p>
		// 			</li>
		// 		</ol><br /><b>Tanggapan Penanggung Jawab Objek Pemeriksaan</b><br /><br /><b>Kesimpulan</b>
		// 	</li>
		// 	<li><b>Divisi Keuangan dan Akuntansi</b><br /><b>Bidang Pengadaan Barang/
		// 			Jasa</b><br /><br />asdasd<br /><br /><b>Kondisi</b>
		// 		<p>asd</p><br /><b>Kriteria</b><br /><b>Sebab</b>
		// 		<p>asd</p><br /><b>Akibat</b>
		// 		<p>asd</p><br /><b>Rekomendasi</b>
		// 		<ol></ol><br /><b>Tanggapan Penanggung Jawab Objek Pemeriksaan</b><br />testing<br /><b>Kesimpulan</b>
		// 	</li>
		// </ol>';
		// echo $str;
		// $temp->setHtmlBlockValue("uraian_hasil_pemeriksaan", $str);
	}

	public function go_print($jenis = null, $jenisprint = null)
	{
		if ($jenisprint == 'lhp') {
			$this->go_print_lhp($jenis);
			return;
		}
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";
		$this->data['no_header'] = true;
		$this->data['page_title'] = 'Rekap Laporan Hasil Pemeriksaan (LHP) Audit';
		$this->_beforeDetail($jenis);
		$this->_filter();

		$tahun = $this->data['tahun_filter'];
		$id_periode_tw = $this->data['id_periode_tw_filter'];
		$id_spn = $this->data['id_spn_filter'];

		$addfilter = "";
		if ($id_spn) {
			$this->data['page_title'] .= "<br/><small style='color:#000'>" . $this->data['spnarr'][$id_spn] . "</small>";
			$addfilter = " and a.id_spn = " . $this->conn->escape($id_spn);
		}


		if (!$this->Access("view_all", "main")) {
			$addfilter .= " and (a.id_unit = " . $this->conn->escape($_SESSION[SESSION_APP]['id_unit'])." 
			or b.id_pic = ".$this->conn->escape($_SESSION[SESSION_APP]['id_jabatan']).")";
		}

		$rows = $this->conn->GetArray("select 
			a.id_unit, b.id_bidang_pemeriksaan, c.nama as bidang_pemeriksaan, 
			a.id_pemeriksaan, a.nama, 
			count(case when b.status<>'Close' and b.id_pemeriksaan is not null then 1 else null end) as jumlah_monev,
			count(case when b.status='Close' and b.id_pemeriksaan is not null then 1 else null end) as jumlah_close,
			count(b.id_pemeriksaan) as jumlah
			from pemeriksaan a 
			left join pemeriksaan_temuan b on b.is_disetujui = 1 and a.id_pemeriksaan = b.id_pemeriksaan 
			left join mt_bidang_pemeriksaan c on b.id_bidang_pemeriksaan = c.id_bidang_pemeriksaan
			where a.deleted_date is null and a.jenis = " . $this->conn->escape($this->data['jenis']) . " $addfilter 
			group by a.id_unit, b.id_bidang_pemeriksaan, c.nama, a.id_pemeriksaan, a.nama");

		$this->data['rows'] = array();
		$this->data['totalunit'] = array();
		foreach ($rows as $r) {
			$this->data['rows'][$r['id_unit']][$r['id_pemeriksaan']][$r['id_bidang_pemeriksaan']] = $r;
			$this->data['totalunit'][$r['id_unit']][$r['id_bidang_pemeriksaan']]['jumlah'] += $r['jumlah'];
			$this->data['totalunit'][$r['id_unit']][$r['id_bidang_pemeriksaan']]['jumlah_monev'] += $r['jumlah_monev'];
			$this->data['totalunit'][$r['id_unit']][$r['id_bidang_pemeriksaan']]['jumlah_close'] += $r['jumlah_close'];
		}
		// dpr($this->data['rows'], 1);

		$this->View($this->viewlist . "print");
	}

	public function monev($jenis = null)
	{
		// $this->data['page_title'] = 'Monitoring & Evaluasi Audit';
		$this->_beforeDetail($jenis);
		$this->_filter();

		$tahun = $this->data['tahun_filter'];
		$id_periode_tw = $this->data['id_periode_tw_filter'];
		$id_spn = $this->data['id_spn_filter'];

		$addfilter = "";
		if ($id_spn) {
			$addfilter = " and a.id_spn = " . $this->conn->escape($id_spn);
		}


		if (!$this->Access("view_all", "main")) {
			$addfilter .= " and (a.id_unit = " . $this->conn->escape($_SESSION[SESSION_APP]['id_unit']) . " 
			or b.id_pic = " . $this->conn->escape($_SESSION[SESSION_APP]['id_jabatan']) . ")";
		}
		// $this->conn->debug = 1;

		$tahunperiode = $tahun . $id_periode_tw;

		$rows = $this->conn->GetArray("
		SELECT 
		case when concat(b.tahun,b.id_periode_tw) < " . $this->conn->escape($tahunperiode) . " then 1 else 0 end as is_sebelumnya,
		a.id_unit, a.id_subbid, d.nama as subbid, 
		a.id_pemeriksaan, a.nama, 
		count(b.id_pemeriksaan_temuan) as jumlah_temuan,
    sum(case when c.hasil_evaluasi=2 then 1 else 0 end) as jumlah_tindak_lanjut
FROM
    pemeriksaan a
        LEFT JOIN
    pemeriksaan_temuan b ON b.is_disetujui = 1 and a.id_pemeriksaan = b.id_pemeriksaan
        LEFT JOIN
    pemeriksaan_tindak_lanjut c ON b.id_pemeriksaan_temuan = c.id_pemeriksaan_temuan and concat(c.tahun,c.id_periode_tw) = " . $this->conn->escape($tahunperiode) . "
        LEFT JOIN
    mt_sdm_subbid d ON a.id_subbid = d.code

		where a.deleted_date is null and jenis = " . $this->conn->escape($this->data['jenis']) . " 

		and (
			(concat(b.tahun,b.id_periode_tw) = " . $this->conn->escape($tahunperiode) . ")
			or
			(concat(c.tahun,c.id_periode_tw) <= " . $this->conn->escape($tahunperiode) . ")
    		or 
			(concat(b.tahun,b.id_periode_tw) < " . $this->conn->escape($tahunperiode) . " and b.status <> 'Close')
		) 
		
		$addfilter 
		group by a.id_unit, a.id_subbid, d.nama, 
		a.id_pemeriksaan, a.nama,b.tahun,b.id_periode_tw");

		// dpr($rows, 1);

		$this->data['rows'] = array();
		$this->data['totalunit'] = array();
		foreach ($rows as $r) {
			$r['jumlah_sisa_temuan'] = ($r['jumlah_temuan'] - $r['jumlah_tindak_lanjut']);
			$this->data['rows'][$r['id_unit']][] = $r;

			$this->data['totalunit'][$r['id_unit']]['jumlah_temuan'] += $r['jumlah_temuan'];
			$this->data['totalunit'][$r['id_unit']]['jumlah_tindak_lanjut'] += $r['jumlah_tindak_lanjut'];
			$this->data['totalunit'][$r['id_unit']]['jumlah_sisa_temuan'] += ($r['jumlah_temuan'] - $r['jumlah_tindak_lanjut']);
		}
		$this->data['addbutton'] .= UI::createSelect("id_periode_tw_filter", $this->data['mtperiodetwarr'], $this->data['id_periode_tw_filter'], true, 'form-control me-2', "style='width:150px !important; display:inline' onchange='goSubmit(\"set_filter\")'");
		$this->data['addbutton'] .= "&nbsp;" . UI::createTextNumber("tahun_filter", $this->data['tahun_filter'], 4, 4, true, "form-control", "style='width:70px; display:inline' onchange='goSubmit(\"set_filter\")'");
		$this->data['addbutton'] .= "&nbsp;<a target='_BLANK' href='" . site_url("panelbackend/pemeriksaan/go_print_monev/operasional") . "' class='btn btn-sm btn-primary'><i class='bi bi-printer'></i> Print Monev</a>";

		$this->View("panelbackend/pemeriksaanmonevlist");
	}

	public function go_print_monev($jenis = null)
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";
		$this->data['no_header'] = true;
		$this->data['page_title'] = 'Monitoring & Evaluasi Audit';
		$this->_beforeDetail($jenis);
		$this->_filter();

		$tahun = $this->data['tahun_filter'];
		$id_periode_tw = $this->data['id_periode_tw_filter'];
		$id_spn = $this->data['id_spn_filter'];

		$addfilter = "";
		if ($id_spn) {
			$addfilter = " and a.id_spn = " . $this->conn->escape($id_spn);
		}


		if (!$this->Access("view_all", "main")) {
			$addfilter .= " and (a.id_unit = " . $this->conn->escape($_SESSION[SESSION_APP]['id_unit']) . " 
			or b.id_pic = " . $this->conn->escape($_SESSION[SESSION_APP]['id_jabatan']) . ")";
		}
		// $this->conn->debug = 1;
		$this->data['page_title'] .= "<br/><small style='color:#000'>" . $this->data['mtperiodetwarr'][$id_periode_tw] . " Tahun " . $tahun . "</small>";

		$tahunperiode = $tahun . $id_periode_tw;

		$rows = $this->conn->GetArray("SELECT 
		a.id_unit, a.id_subbid, d.nama as subbid, 
		a.id_pemeriksaan, a.nama, 
		count(b.id_pemeriksaan_temuan) as jumlah_temuan,
    sum(case when c.hasil_evaluasi=2 then 1 else 0 end) as jumlah_tindak_lanjut
FROM
    pemeriksaan a
        LEFT JOIN
    pemeriksaan_temuan b ON b.is_disetujui = 1 and a.id_pemeriksaan = b.id_pemeriksaan
        LEFT JOIN
    pemeriksaan_tindak_lanjut c ON b.id_pemeriksaan_temuan = c.id_pemeriksaan_temuan and concat(c.tahun,c.id_periode_tw) = " . $this->conn->escape($tahunperiode) . "
        LEFT JOIN
    mt_sdm_subbid d ON a.id_subbid = d.code

		where a.deleted_date is null and jenis = " . $this->conn->escape($this->data['jenis']) . " 

		and (
			(concat(b.tahun,b.id_periode_tw) = " . $this->conn->escape($tahunperiode) . ")
			or
			(concat(c.tahun,c.id_periode_tw) <= " . $this->conn->escape($tahunperiode) . ")
    		or 
			(concat(b.tahun,b.id_periode_tw) < " . $this->conn->escape($tahunperiode) . " and b.status <> 'Close')
		) 
		
		$addfilter 
		group by a.id_unit, a.id_subbid, d.nama, 
		a.id_pemeriksaan, a.nama");

		// dpr($rows, 1);

		$this->data['rows'] = array();
		$this->data['totalunit'] = array();
		foreach ($rows as $r) {
			$r['jumlah_sisa_temuan'] = ($r['jumlah_temuan'] - $r['jumlah_tindak_lanjut']);
			$this->data['rows'][$r['id_unit']][] = $r;

			$this->data['totalunit'][$r['id_unit']]['jumlah_temuan'] += $r['jumlah_temuan'];
			$this->data['totalunit'][$r['id_unit']]['jumlah_tindak_lanjut'] += $r['jumlah_tindak_lanjut'];
			$this->data['totalunit'][$r['id_unit']]['jumlah_sisa_temuan'] += ($r['jumlah_temuan'] - $r['jumlah_tindak_lanjut']);
		}

		$this->View("panelbackend/pemeriksaanmonevlistprint");
	}

	private function _filter()
	{
		$this->load->model("Mt_periode_twModel", "mtperiodetw");
		$this->load->model("Mt_sdm_unitModel", "mtsdmunit");
		$this->data['mtperiodetwarr'] = $this->mtperiodetw->GetCombo();
		$this->data['unitarr'] = $this->mtsdmunit->GetCombo();
		$this->data['mtperiodetwarr'][''] = 'Periode';
		$this->data['unitarr'][''] = 'Unit';

		$tgl_efektif = date('Y-m-d');

		list($tahun, $bln, $tgl) = explode("-", $tgl_efektif);
		$id_periode_tw = $id_periode_tw_current = $this->conn->GetOne("select id_periode_tw from mt_periode_tw where deleted_date is null and '$bln' between bulan_mulai and bulan_akhir");

		#filter
		if ($this->post['act'] == "set_filter") {
			if ($tahun >= $this->post['tahun_filter'] && $this->post['tahun_filter'])
				$_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'] = $this->post['tahun_filter'];

			if (!($tahun == $_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'] && $id_periode_tw_current < $this->post['id_periode_tw_filter']))
				$_SESSION[SESSION_APP][$this->page_ctrl]['id_periode_tw_filter'] = $this->post['id_periode_tw_filter'];

			$_SESSION[SESSION_APP][$this->page_ctrl]['id_spn_filter'] = $this->post['id_spn_filter'];

			redirect(current_url());
		}


		if ($_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'])
			$tahun = $_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'];

		$_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'] = $tahun;


		if ($_SESSION[SESSION_APP][$this->page_ctrl]['id_periode_tw_filter'])
			$id_periode_tw = $_SESSION[SESSION_APP][$this->page_ctrl]['id_periode_tw_filter'];

		$_SESSION[SESSION_APP][$this->page_ctrl]['id_periode_tw_filter'] = $id_periode_tw;

		// if (!$this->Access("view_all", "main"))
		// 	$id_spn = $_SESSION[SESSION_APP]['id_spn'];
		// else
		$id_spn = $_SESSION[SESSION_APP][$this->page_ctrl]['id_spn_filter'];

		$_SESSION[SESSION_APP][$this->page_ctrl]['id_spn_filter'] = $id_spn;

		$this->data['tahun_filter'] = $tahun;
		$this->data['id_periode_tw_filter'] = $id_periode_tw;
		$this->data['id_spn_filter'] = $id_spn;
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'id_unit',
				'label' => 'Unit',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['unitarr']
			),
			array(
				'name' => 'nama',
				'label' => 'Nama Kegiatan',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'jumlah_temuan',
				'label' => 'Σ Temuan',
				'width' => "auto",
				'type' => "number",
				'nofilter' => true
			),
			array(
				'name' => 'jumlah_tindak_lanjut',
				'label' => 'Σ Tindaklanjut (Penyelesaian)',
				'width' => "auto",
				'type' => "number",
				'nofilter' => true
			),
			array(
				'name' => 'jumlah_sisa_temuan',
				'label' => 'Σ Sisa Temuan',
				'width' => "auto",
				'type' => "number",
				'nofilter' => true
			),
			array(
				'name' => 'keterangan',
				'label' => 'Keterangan',
				'width' => "auto",
				'type' => "varchar",
				'nofilter' => true
			),
		);
	}

	protected function Record($id = null)
	{
		if ($this->post['act'] == 'set_value_spn') {
			$row_spn = $this->conn->GetRow("select * from pemeriksaan_spn where deleted_date is null 
			and id_spn = " . $this->conn->escape($this->post['id_spn']));

			$this->post['pemeriksaan_tim'] = $this->conn->GetArray("select * from pemeriksaan_spn_petugas 
			where deleted_date is null 
			and id_spn = " . $this->conn->escape($this->post['id_spn']));
			$this->post['tgl_mulai'] = $row_spn['periode_pemeriksaan_mulai'];
			$this->post['tgl_selesai'] = $row_spn['periode_pemeriksaan_selesai'];
			$this->post['id_penyusun'] = $row_spn['id_penyusun'];
			$this->post['id_pereview'] = $row_spn['id_pereview'];
			$this->post['keterangan'] = $row_spn['deskripsi'];
			$this->post['id_penanggung_jawab'] = $row_spn['id_penanggung_jawab'];
		}

		if ($this->post['id_penyusun']) {
			$r = $this->conn->GetRow("select a.* from mt_sdm_jabatan a 
		where a.deleted_date is null and exists(select 1 from public_sys_user_group b 
		where a.id_jabatan = b.id_jabatan and b.user_id = " . $this->post['id_penyusun'] . ")");
			$this->post['id_jabatan_penyesusun'] = $r['id_jabatan'];
			$this->post['nama_jabatan_penyusun'] = $r['nama'];
			$this->post['nama_penyusun'] = $this->data['pimpinanarr'][$this->post['id_penyusun']];
		}

		if ($this->post['id_sasaran']) {
			$r = $this->conn->GetRow("select * from risk_sasaran where deleted_date is null and id_sasaran = " . $this->conn->escape($this->post['id_sasaran']));
			$this->post['nama_sasaran'] = $r['nama'];
		}
		if ($this->post['id_penanggung_jawab']) {
			$r = $this->conn->GetRow("select a.* from mt_sdm_jabatan a 
		where deleted_date is null and exists(select 1 from public_sys_user_group b 
		where a.id_jabatan = b.id_jabatan and b.user_id = " . $this->post['id_penanggung_jawab'] . ")");
			$this->post['id_jabatan_penanggung_jawab'] = $r['id_jabatan'];
			$this->post['nama_jabatan_penanggung_jawab'] = $r['nama'];
			$this->post['nama_penanggung_jawab'] = $this->data['penanggungjawabarr'][$this->post['id_penanggung_jawab']];
		}

		if ($this->post['id_pereview']) {
			$r = $this->conn->GetRow("select a.* from mt_sdm_jabatan a 
		where a.deleted_date is null and exists(select 1 from public_sys_user_group b 
		where a.id_jabatan = b.id_jabatan and b.user_id = " . $this->post['id_pereview'] . ")");
			$this->post['id_jabatan_pereview'] = $r['id_jabatan'];
			$this->post['nama_jabatan_pereview'] = $r['nama'];
			$this->post['nama_pereview'] = $this->data['pelaksanaarr'][$this->post['id_pereview']];
		}

		if ($this->post['pemeriksaan_tim'])
			foreach ($this->post['pemeriksaan_tim'] as &$rr) {

				if ($rr['user_id']) {
					$r = $this->conn->GetRow("select a.* from mt_sdm_jabatan a 
			where a.deleted_date is null and exists(select 1 from public_sys_user_group b 
			where a.id_jabatan = b.id_jabatan and b.user_id = " . $rr['user_id'] . ")");
					$rr['id_jabatan'] = $r['id_jabatan'];
					$rr['nama_jabatan'] = $r['nama'];
					$rr['nama'] = $this->data['pelaksanaarr'][$rr['user_id']];
				}
			}

		$return = array(
			'nama_user' => $this->data['userarr'][$this->post['user_id']],
			'nomor_stp' => $this->post['nomor_stp'],
			'tanggal_sptp' => $this->post['tanggal_sptp'],
			'id_spn' => $this->post['id_spn'],
			'id_unit' => $this->post['id_unit'],
			'id_subbid' => $this->post['id_subbid'] ? $this->post['id_subbid'] : "{{null}}",
			'id_jenis_audit_eksternal' => $this->post['id_jenis_audit_eksternal'],
			'nama' => $this->post['nama'],
			'objeklainnya' => $this->post['objeklainnya'],
			'keterangan' => $this->post['keterangan'],
			'tujuan' => $this->post['tujuan'],
			'jenis_audit_eksternal' => $this->post['jenis_audit_eksternal'],
			'lokasi' => $this->post['lokasi'],
			'tgl_mulai' => $this->post['tgl_mulai'],
			'tgl_selesai' => $this->post['tgl_selesai'],

			'id_jabatan_penyesusun' => $this->post['id_jabatan_penyesusun'],
			'id_penyusun' => $this->post['id_penyusun'],
			'nama_penyusun' => $this->post['nama_penyusun'],
			'jabatan_penyesusun' => $this->post['jabatan_penyesusun'],
			'nama_jabatan_penyusun' => $this->post['nama_jabatan_penyusun'],

			'id_jabatan_pereview' => $this->post['id_jabatan_pereview'],
			'id_pereview' => $this->post['id_pereview'],
			'nama_pereview' => $this->post['nama_pereview'],
			'jabatan_pereview' => $this->post['jabatan_pereview'],
			'nama_jabatan_pereview' => $this->post['nama_jabatan_pereview'],

			'id_jabatan_penanggung_jawab' => $this->post['id_jabatan_penanggung_jawab'],
			'id_penanggung_jawab' => $this->post['id_penanggung_jawab'],
			'nama_penanggung_jawab' => $this->post['nama_penanggung_jawab'],
			'jabatan_penanggung_jawab' => $this->post['jabatan_penanggung_jawab'],
			'nama_jabatan_penanggung_jawab' => $this->post['nama_jabatan_penanggung_jawab'],

			'id_sasaran' => $this->post['id_sasaran'],
			'nama_sasaran' => $this->post['nama_sasaran'],
		);

		if ($this->data['jenis'] == 'eksternal') {
			$return['id_status'] = 6;
		}

		return $return;
	}

	protected function Rules()
	{
		$return = array(
			"id_unit" => array(
				'field' => 'id_unit',
				'label' => 'Unit',
				'rules' => "required|max_length[18]",
			),
			// "nomor_stp" => array(
			// 	'field' => 'nomor_stp',
			// 	'label' => 'Nomor SPTP',
			// 	'rules' => "required|max_length[200]",
			// ),
			// "tanggal_sptp" => array(
			// 	'field' => 'tanggal_sptp',
			// 	'label' => 'Tanggal SPTP',
			// 	'rules' => "required|max_length[200]",
			// ),
			"nama" => array(
				'field' => 'nama',
				'label' => 'Nama',
				'rules' => "required|max_length[200]",
			),
			"tgl_mulai" => array(
				'field' => 'tgl_mulai',
				'label' => 'Tgl. Mulai',
				'rules' => "required|max_length[200]",
			),
			"lokasi" => array(
				'field' => 'lokasi',
				'label' => 'Lokasi',
				'rules' => "required|max_length[500]",
			),
			"id_jabatan_penyesusun" => array(
				'field' => 'id_jabatan_penyesusun',
				'label' => 'Jabatan Penyesusun',
				'rules' => "integer|max_length[10]",
			),
			"id_spn" => array(
				'field' => 'id_spn',
				'label' => 'Surat Tugas',
				'rules' => "required",
			),
			"jabatan_penyesusun" => array(
				'field' => 'jabatan_penyesusun',
				'label' => 'Jabatan Penyesusun',
				'rules' => "max_length[200]",
			),
			"nama_jabatan_penyusun" => array(
				'field' => 'nama_jabatan_penyusun',
				'label' => 'Nama Jabatan Penyusun',
				'rules' => "required|max_length[200]",
			),
			"id_jabatan_pereview" => array(
				'field' => 'id_jabatan_pereview',
				'label' => 'Jabatan Pereview',
				'rules' => "integer|max_length[10]",
			),
			"jabatan_pereview" => array(
				'field' => 'jabatan_pereview',
				'label' => 'Jabatan Pereview',
				'rules' => "max_length[200]",
			),
			"nama_jabatan_pereview" => array(
				'field' => 'nama_jabatan_pereview',
				'label' => 'Nama Jabatan Pereview',
				'rules' => "required|max_length[200]",
			),
			"id_jabatan_penanggung_jawab" => array(
				'field' => 'id_jabatan_penanggung_jawab',
				'label' => 'Jabatan penanggung_jawab',
				'rules' => "integer|max_length[10]",
			),
			"jabatan_penanggung_jawab" => array(
				'field' => 'jabatan_penanggung_jawab',
				'label' => 'Jabatan penanggung_jawab',
				'rules' => "max_length[200]",
			),
			"nama_jabatan_penanggung_jawab" => array(
				'field' => 'nama_jabatan_penanggung_jawab',
				'label' => 'Nama Jabatan penanggung_jawab',
				'rules' => "required|max_length[200]",
			),
		);

		if ($this->data['row']['jenis'] == 'eksternal') {
			unset($return['id_spn']);
			unset($return['nama_jabatan_penyusun']);
			unset($return['nama_jabatan_pereview']);
			unset($return['nama_jabatan_penanggung_jawab']);
		}

		if ($this->data['row']['jenis'] == 'penyuapan' || $this->data['row']['jenis'] == 'mutu') {
			unset($return['nama_jabatan_penyusun']);
		}

		return $return;
	}

	public function Detail($jenis = null, $id = null)
	{
		if ($jenis == 'eksternal')
			redirect("panelbackend/pemeriksaan_temuan/index/" . $id);
		else
			redirect("panelbackend/pemeriksaan_detail/index/" . $id);
		// $id = urldecode($id);
		// $this->_beforeDetail($jenis, $id);

		// $this->data['row'] = $this->model->GetByPk($id);

		// if (!$this->data['row'])
		// 	$this->NoData();

		// $this->_onDetail($id);

		// $this->_afterDetail($id);

		// $this->View($this->viewdetail);
	}

	protected function _afterInsert($id)
	{
		$ret = true;

		if (!empty($this->post['pemeriksaan_tim'])) {
			if ($ret)
				$ret = $this->conn->Execute("delete from pemeriksaan_tim where id_pemeriksaan = " . $this->conn->escape($id));
			
				foreach ($this->post['pemeriksaan_tim'] as $r) {
				$r['id_pemeriksaan'] = $id;
				$ret = $this->conn->goInsert("pemeriksaan_tim", $r);
			}
		}

		return $ret;
	}

	protected function _afterUpdate($id)
	{
		return $this->_afterInsert($id);
	}
}
