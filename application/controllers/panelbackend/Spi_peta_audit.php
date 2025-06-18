<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Spi_peta_audit extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewdetailadd = "panelbackend/spi_peta_auditdetailadd";
		$this->viewdetail = "panelbackend/spi_peta_auditdetailedit";
		$this->viewlist = "panelbackend/spi_peta_auditlist";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_profil_spi";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambahkan audit';
			$this->data['edited'] = true;
		} else if ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Audit';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Audit';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Audit';
		}

		$this->load->model("Spi_program_auditModel", "model");

		$this->load->model("Risk_risikoModel", "Resiko");
		$this->data['Risikoarr'] = [' ' => ' '] + $this->Resiko->GetCombo();

		$this->load->model("Mt_risk_tingkatModel", 'mrt');
		$this->data['mrtarr'] = $this->mrt->getcombo();

		$this->load->model("Risk_scorecardModel", "mscorecard");

		$this->load->model("Risk_sasaranModel", "sasaranstrategis");

		$this->load->model("Mt_sdm_jabatanModel", "mtjabatan");
		$this->data['jabatanarr'] = ['' => ''] + $this->mtjabatan->GetCombo();

		$this->load->model("Mt_bidang_pemeriksaanModel", "mtbidangpemeriksaanarr");

		$this->data['pelaksanaarr'] = $this->conn->GetList("select 
		user_id as idkey, name as val from public_sys_user a 
		where exists (
			select 1 from public_sys_user_group b 
			join public_sys_group_menu c on b.group_id = c.group_id 
			join public_sys_group_action d on c.group_menu_id = d.group_menu_id 
			join public_sys_action e on d.action_id = e.action_id 
			join public_sys_menu f on c.menu_id = f.menu_id
			where b.deleted_date is null and a.user_id = b.user_id and e.name='add' and f.url='panelbackend/pemeriksaan_temuan'
		)");
		// dpr($this->data['pelaksanaarr']);
		// die();

		$this->data['pimpinanarr'] = $this->conn->GetList("select 
		user_id as idkey, name as val from public_sys_user a 
		where exists (
			select 1 from public_sys_user_group b 
			join public_sys_group_menu c on b.group_id = c.group_id 
			join public_sys_group_action d on c.group_menu_id = d.group_menu_id 
			join public_sys_action e on d.action_id = e.action_id 
			join public_sys_menu f on c.menu_id = f.menu_id
			where b.deleted_date is null and a.user_id = b.user_id and e.name='pengawas' and f.url='panelbackend/pemeriksaan'
		)");

		$this->data['penanggungjawabarr'] = $this->conn->GetList("select 
		user_id as idkey, name as val from public_sys_user a 
		where exists (
			select 1 from public_sys_user_group b 
			join public_sys_group_menu c on b.group_id = c.group_id 
			join public_sys_group_action d on c.group_menu_id = d.group_menu_id 
			join public_sys_action e on d.action_id = e.action_id 
			join public_sys_menu f on c.menu_id = f.menu_id
			where b.deleted_date is null and a.user_id = b.user_id and e.name='penanggungjawab' and f.url='panelbackend/pemeriksaan'
		)");


		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'treetable', 'select2'
		);

		$this->data['excel'] = false;
	}

	public function Header()

	{
		return array(
			array(
				'name' => 'nama_audit',
				'label' => 'Nama Audit Instansi Kegiatan, Program, dll',
				'width' => "auto",
				'type' => "varchar",
				// 'value' => $this->data['nama_audit'],
			),

			array(
				'name' => 'besaran_risiko',
				'label' => 'Residual Setelah Evaluasi',
				'width' => "80px",
				'type' => "number",
				'nofilter' => true,
				// 'value' => 'test',
			),

		);
	}

	protected function Record($id = null)
	{
		if ($this->post['id_penyusun']) {
			$r = $this->conn->GetRow("select a.* from mt_sdm_jabatan a 
		where exists(select 1 from public_sys_user_group b 
		where b.deleted_date is null and a.id_jabatan = b.id_jabatan and b.user_id = " . $this->post['id_penyusun'] . ")");
			$this->post['id_jabatan_penyesusun'] = $r['id_jabatan_penyesusun'];
			$this->post['nama_jabatan_penyusun'] = $r['nama'];
			$this->post['nama_penyusun'] = $this->data['pimpinanarr'][$this->post['id_penyusun']];
		}

		if ($this->post['id_penanggung_jawab']) {
			$r = $this->conn->GetRow("select a.* from mt_sdm_jabatan a 
		where exists(select 1 from public_sys_user_group b 
		where b.deleted_date is null and a.id_jabatan = b.id_jabatan and b.user_id = " . $this->post['id_penanggung_jawab'] . ")");
			$this->post['id_jabatan_penanggung_jawab'] = $r['id_jabatan_penanggung_jawab'];
			$this->post['nama_jabatan_penanggung_jawab'] = $r['nama'];
			$this->post['nama_penanggung_jawab'] = $this->data['penanggungjawabarr'][$this->post['id_penanggung_jawab']];
		}

		if ($this->post['id_pereview']) {
			$r = $this->conn->GetRow("select a.* from mt_sdm_jabatan a 
		where exists(select 1 from public_sys_user_group b 
		where b.deleted_date is null and a.id_jabatan = b.id_jabatan and b.user_id = " . $this->post['id_pereview'] . ")");
			$this->post['id_jabatan_pereview'] = $r['id_jabatan_pereview'];
			$this->post['nama_jabatan_pereview'] = $r['nama'];
			$this->post['nama_pereview'] = $this->data['pelaksanaarr'][$this->post['id_pereview']];
		}


		if ($this->post['pemeriksaan_tim'])
			foreach ($this->post['pemeriksaan_tim'] as &$rr) {

				if ($rr['user_id']) {
					$r = $this->conn->GetRow("select a.* from mt_sdm_jabatan a 
			where exists(select 1 from public_sys_user_group b 
			where b.deleted_date is null and a.id_jabatan = b.id_jabatan and b.user_id = " . $rr['user_id'] . ")");
					$rr['id_jabatan'] = $r['id_jabatan'];
					$rr['nama_jabatan'] = $r['nama'];
					$rr['nama'] = $this->data['pelaksanaarr'][$rr['user_id']];
				}
			}


		$return = array(
			'nama_audit' => $this->post['nama_audit'],
			'tenaga_pengawas' => $this->post['tenaga_pengawas'],
			'sekretariat_spi' => $this->post['sekretariat_spi'],
			'sarana_kendaraan' => $this->post['sarana_kendaraan'],
			'sarana_lainnya' => $this->post['sarana_lainnya'],
			'dana_sppd' => $this->post['dana_sppd'],
			'dana_lainnya' => $this->post['dana_lainnya'],
			'lain_lain' => $this->post['lain_lain'],
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
		);

		if ($this->data['jenis'] == 'eksternal') {
			$return['id_status'] = 6;
		}

		return $return;
	}

	protected function Rules()
	{
		return array(
			// 'nama_audit' => array(
			// 	'field'   => 'nama_audit',
			// 	'label'   => 'Nama Audit',
			// 	'rules'   => ''
			// ),
		);
	}

	function Index($page = 0)
	{
		$this->data['header'] = $this->Header();

		$this->data['list'] = $this->_getList($page);

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

		$param = array(
			'page' => $page,
			'limit' => $this->_limit(),
			'order' => $this->_order(),
			'filter' => $this->_getFilter()
		);

		// dpr($param);
		// die();
		$this->data['arrDetailAudit'] = $this->model->selectGrid($param);

		$this->View($this->viewlist);
	}

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


	public function Add($id = null)
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
			$ret = true;
			foreach ($this->post['nama_audit'] as $id_risiko => $nama_audit) {
				if (!$ret) {
					break;
				}
				if ($nama_audit) {
					$id_program_audit = $this->conn->getOne(
						"select id_program_audit 
					from spi_program_audit where deleted_date is null and id_risiko = $id_risiko and tahun = " . $this->post['tahun']
					);
					if ($id_program_audit) {
						$ret = $this->conn->goupdate(
							'spi_program_audit',
							[
								'id_risiko' => $id_risiko,
								'nama_audit' => $nama_audit,
								'tahun' => $this->post['tahun']
							],
							"id_program_audit = $id_program_audit"
						);
					} else {

						$ret = $this->conn->goInsert(
							'spi_program_audit',
							[
								'id_risiko' => $id_risiko,
								'nama_audit' => $nama_audit,
								'tahun' => $this->post['tahun']
							]
						);
					}
				}
			}
			$return['success'] = $ret;
			$this->model->conn->CompleteTrans();

			if ($return['success']) {

				$this->_afterEditSucceed($id);

				if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
					echo json_encode(array("success" => true, "data" => array("key" => $this->pk, "val" => $id)));
					exit();
				} else {
					SetFlash('suc_msg', $return['success']);
					redirect("$this->page_ctrl");
				}
			} else {
				$this->data['row'] = array_merge($this->data['row'], $record);
				$this->data['row'] = array_merge($this->data['row'], $this->post);

				$this->_afterEditFailed($id);

				$this->data['err_msg'] = "Data gagal disimpan";
			}
		}


		$this->data['row'] = $this->post;

		$tahun = date('Y');

		if ($this->data['row']['tahun'])
			$tahun = $this->data['row']['tahun'];

		$this->data['sasaranarr'] = $this->sasaranstrategis->GetCombo(null, null, $tahun);

		if ($this->data['row']['id_kajian_risiko'] == 'semua')
			unset($this->data['row']['id_kajian_risiko']);

		$this->data['rowscorecards'] = $this->mscorecard->GetList(null, null, 1, $tahun);



		$this->data['report'] = 1;


		$param = $this->post;
		$param['rating'] = 'a';

		foreach (str_split($param['rating']) as $idkey => $value) {
			$this->data['rating'][$value] = 1;
		}

		$this->data['rows'] = $this->Resiko->getListRiskProfile($param);

		$this->data['mtriskkemungkinan'] = $this->conn->GetArray("select * from mt_risk_kemungkinan where deleted_date is null order by id_kemungkinan desc");
		$this->data['mtriskdampak'] = $this->conn->GetArray("select * from mt_risk_dampak where deleted_date is null order by id_dampak asc");
		$this->data['mtriskmatrix'] = $this->conn->GetArray("select mrm.*, mrt.nama, mrt.warna
			from mt_risk_matrix mrm
			join mt_risk_tingkat mrt on mrt.id_tingkat = mrm.id_tingkat where mrm.deleted_date is null");
		$this->data['row'] = $this->post;
		// dpr($this->data['rows']);

		foreach ($this->data['rows'] as &$ar) {
			$ar['nama_audit'] = $this->conn->getOne("select nama_audit from spi_program_audit where 1=1 and deleted_date is null and id_risiko = $ar[id_risiko] and tahun =  " . $this->post['tahun']);
		}
		// dpr($this->data["rows"]);
		// $param = array(
		// 	'page' => $page,
		// 	'limit' => $this->_limit(),
		// 	'order' => $this->_order(),
		// 	'filter' => $this->_getFilter()
		// );
		// $this->data['arrDetailAudit'] = $this->model->selectGrid($param);
		// dpr($this->data['arrDetailAudit']);


		$this->_afterDetail($id);

		$this->View($this->viewdetailadd);
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
		$param = array(
			'page' => 0,
			'limit' => $this->_limit(),
			'order' => $this->_order(),
			'filter' => $this->_getFilter()
		);

		// dpr($param);
		// die();
		$this->data['arrDetailAudit'] = $this->model->selectGrid($param);
		foreach ($this->data['arrDetailAudit']['rows'] as &$ada) {
			if ($ada['id_program_audit'] == $this->data['row']['id_program_audit']) {
				$this->data['level_risiko_actual'] = $ada['level_risiko_actual'];
			}
		}
		// dpr($this->data['arrDetailAudit']);
		// dpr($this->data['row']['id_program_audit']);


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
		if (!$this->data['row']['pemeriksaan_tim'])
			$this->data['row']['pemeriksaan_tim'] = $this->conn->GetArray("select * from pemeriksaan_tim where deleted_date is null and id_pemeriksaan = " . $this->conn->escape($id));
		$param = array(
			'page' => 0,
			'limit' => $this->_limit(),
			'order' => $this->_order(),
			'filter' => $this->_getFilter()
		);

		// dpr($param);
		// die();
		$this->data['arrDetailAudit'] = $this->model->selectGrid($param);
		foreach ($this->data['arrDetailAudit']['rows'] as &$ada) {
			if ($ada['id_program_audit'] == $this->data['row']['id_program_audit']) {
				$this->data['level_risiko_actual'] = $ada['level_risiko_actual'];
			}
		}

		foreach ($this->data['row']['pemeriksaan_tim'] as $key => $p) {
			// dpr($p);
			// $this->data['row']['pemeriksaan_tim'][$key]['nama'] .= "&nbsp;";
			$this->data['row']['pemeriksaan_tim'][$key]['nama_jabatan'] = '&nbsp;' . $p['nama_jabatan'] . '<br>';
		}

		// dpr($this->data['row']['pemeriksaan_tim'], 1);
	}
}
