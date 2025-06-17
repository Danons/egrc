<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Kpi_target_unit extends _adminController
{
	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();

		$this->viewlist = "panelbackend/kpi_target_unitlist";
		$this->viewdetail = "panelbackend/kpi_target_unitdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Target KPI';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Target KPI';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Target KPI';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'KPI Unit';
		}

		$this->load->model("Kpi_targetModel", "model");

		$this->load->model("KpiModel", "kpi");
		$this->data['kpiarr'] = $this->kpi->GetCombo();

		$this->load->model("Mt_sdm_dit_bidModel", "dept");
		$this->data['deptarr'] = $this->dept->GetCombo();

		$this->load->model("Mt_sdm_unitModel", "unit");
		$this->data['unitarr'] = $this->unit->GetCombo();
		$this->data['unitarr'][''] = 'Pilih Unit';

		$tahun = array(null => 'Pilih Tahun', date('Y') => date('Y'), date('Y') - 1 => date('Y') - 1, date('Y') - 2 => date('Y') - 2);

		$this->data['tahunarr'] = $tahun;

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'select2', 'treetable', 'tinymce', 'upload'
		);

		$this->access_role['list_print'] = true;
	}

	public function Detail($id = null)
	{
		redirect("panelbackend/kpi_target_realisasi/index/$id");
	}

	public function Index($page = 0)
	{
		$_SESSION[SESSION_APP]['edit_korporat_direktorat_unit'] = 'panelbackend/kpi_target_unit';
		if ($this->post['act'] == 'save') {
			$this->Add_inline();
		}

		# save realisasi
		if ($this->post['act'] == 'save_realisasi') {
			$this->load->model("Kpi_target_realisasiModel", "kpi_target_realisasi");

			$cek_id = $this->conn->GetOne("select id_kpi_target_realisasi from kpi_target_realisasi 
			where deleted_date is null and bulan = " . $this->conn->escape($this->post['bulan']) . " 
			and id_kpi_target = " . $this->conn->escape($this->post['id_kpi_target']));


			$record = array();
			$record['bulan'] = $this->post['bulan'];
			$record['nilai'] = $this->post['nilai1'] ? $this->post['nilai1'] : $this->post['nilai2'];
			$record['prosentase'] = $this->post['prosentase'];
			$record['id_kpi_target'] = $this->post['id_kpi_target'];


			if ($cek_id) {
				$return = $this->kpi_target_realisasi->Update($record, "id_kpi_target_realisasi = " . $this->conn->qstr($cek_id));
			} else {
				$return = $this->kpi_target_realisasi->Insert($record);
			}

			$id_kpi_target = $return['data']['id_kpi_target'] ? $return['data']['id_kpi_target'] : $record['id_kpi_target'];
			# mengirim notivikasi
			if ($return) {
				// $data_not = $this->conn->GetArray("select * from public_sys_user_group where group_id in(4)");
				// $data_not = $this->conn->GetArray("
				// select 
				// distinct g.*
				// from
				// 	public_sys_user_group g
				// 	left join public_sys_group a on g.group_id = g.group_id
				// 	left join public_sys_group_menu b on a.group_id = b.group_id
				// 	left join public_sys_menu c on b.menu_id = c.menu_id
				// 	left join public_sys_action d on d.menu_id = c.menu_id
				// where a.group_id = 4 and c.menu_id = 941
				// /*where d.name = 'view_all' and a.group_id = 4*/");
				// $nama = $this->conn->GetOne("select nama from kpi a where exists( select 1 from kpi_target b where a.id_kpi = b.id_kpi and id_kpi_target = " . $this->conn->escape($id_kpi_target) . ")");
				// $unit = $this->data['unitarr'][$this->conn->GetOne("select id_unit from kpi_target where id_kpi_target = " . $this->conn->escape($id_kpi_target))];
				// $this->conn->debug=1;
				// foreach ($data_not as $r) {
				// 	$record2 = array(
				// 		'page' => 'unit',
				// 		'untuk' => $r['id_jabatan'],
				// 		'id_status_pengajuan' => 2,
				// 		// 'deskripsi' => "telah menambahkan realisasi",
				// 		'deskripsi' => "KPI " . $nama . " di UNIT " . $unit . " telah ditambahkan realisasi ",
				// 		'url' => "panelbackend/kpi_target_realisasi/index/$id_kpi_target",
				// 	);

				// 	$re = $this->InsertTask($record2);
				// }
				// die;
				// dpr($nama,1);

				// $record2 = array(
				// 	'page' => 'realisasi',
				// 	'id_status_pengajuan' => 8,
				// 	'deskripsi' => "KPI " . $nama . " di UNIT " . $unit . " telah ditambahkan realisasi ",
				// 	'url' => "panelbackend/kpi_target_realisasi/index/$id_kpi_target",
				// );

				// $re = $this->InsertTask($record2);
			}

			if ($return['success']) {

				$this->_afterEditSucceed($id);

				SetFlash('suc_msg', $return['success']);
			} else {
				$this->data['row'] = array_merge($this->data['row'], $record);
				$this->data['row'] = array_merge($this->data['row'], $this->post);

				$this->_afterEditFailed($id);

				$this->data['err_msg'] = "Data gagal disimpan";
			}
		}


		if (!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']['tahun'])
			$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']['tahun'] = date('Y');

		if (!$this->Access("view_all", "main"))
			$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']['id_unit'] = $_SESSION[SESSION_APP]['id_unit'];

		$this->data['header'] = $this->Header();
		$this->data['rows'] = $this->_getList($page);

		foreach ($this->data['rows'] as $r) {
			if ($r['id_kpi_target']) {
				$this->data['rowheader'][$r['id_kpi_target']]  = $this->model->GetByPk($r['id_kpi_target']);
			}
		}
		foreach($this->data['rowheader'] as $m=> $mk){
			unset($mk['definisi']);
			unset($mk['tujuan']);
			unset($mk['formula']);
			unset($mk['evaluasi']);
			unset($mk['analisa']);
			$this->data['fo_modal'][$m] = $mk;
		}

		# data modal
		foreach ($this->data['rows'] as $kl) {
			if ($kl['id_kpi_target']) {
				// $this->data['data_modal'][$kl['id_kpi_target']]  = $this->model->GetByPk($kl['id_kpi_target']);
			}
		}

		foreach ($this->data['rows'] as &$k) {
			if ($k['id_kpi_target']) {
				$data = $this->conn->GetRow("select a.definisi, a.tujuan, a.formula from kpi_target a where a.deleted_date is null and id_kpi_target = " . $this->conn->escape($k['id_kpi_target']));
				// $k['definisi'] = strip_tags($data['definisi']);
				// $k['tujuan'] = strip_tags($data['tujuan']);
				// $k['formula'] = strip_tags($data['formula']);

				// $k['definisi'] = $data['definisi'];
				// $k['tujuan'] = $data['tujuan'];
				// $k['formula'] = $data['formula'];


				$kdefinisi = str_replace('"', "&quot;", $data['definisi']);
				$ktujuan = str_replace('"', "&quot;", $data['tujuan']);
				$kformula = str_replace('"', "&quot;", $data['formula']);


				$k['definisi'] = str_replace("'", "&middot;", $kdefinisi);
				$k['tujuan'] = str_replace("'", "&middot;", $ktujuan);
				$k['formula'] = str_replace("'", "&middot;", $kformula);
			}
		}
		foreach ($this->data['rows'] as &$l) {
			$l['title'] = "Definisi : " . $l['definisi'] . "<br> Tujuan : " . $l['tujuan'] . "<br> Formula : " . $l['formula'];
			// $l['title'] = "Definisi <b>: ". $l['definisi']."</b><br> Tujuan <b>: ".$l['tujuan'] . "</b><br> Formula <b>: ".$l['formula']."</b>";
		}
		// dpr($this->data['rows'],1);

		# input realisasi
		/*
		$this->data['editedevaluasi'] = true;
		$this->data['editedanalisa'] = true;

		if ($this->data['editedevaluasi']) {
			if ($this->post['act'] == 'simpan_evaluasi') {
				$ret = $this->conn->goUpdate("kpi_target", [
					'evaluasi' => $this->post['evaluasi']
				], "id_kpi_target = " . $this->conn->escape($id_kpi_target));

				if ($ret)
					SetFlash("suc_msg", "Berhasil update");
				else
					SetFlash("err_msg", "Berhasil update");

				redirect(current_url());
			}
		}

		if ($this->data['editedanalisa']) {
			if ($this->post['act'] == 'simpan_analisa') {
				$ret = $this->conn->goUpdate("kpi_target", [
					'analisa' => $this->post['analisa']
				], "id_kpi_target = " . $this->conn->escape($id_kpi_target));

				if ($ret)
					SetFlash("suc_msg", "Berhasil update");
				else
					SetFlash("err_msg", "Berhasil update");

				redirect(current_url());
			}
		}*/
		# input realisasi end


		$this->data['page_title'] .= " Tahun " . UI::createTextNumber("list_search_filter[tahun]", $this->data['filter_arr']["tahun"], 4, 4, true, 'form-control', "style='max-width: 87px;display:inline;line-height: 1;font-size: inherit;font-weight: inherit;font-family: inherit;padding: .1rem .375rem !important;'");

		$this->View($this->viewlist);
	}

	public function go_print()
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";
		$this->data['no_header'] = true;
		$this->viewprint = "panelbackend/kpi_target_unitprint";

		$this->data['header'] = $this->Header();
		$this->data['rows'] = $this->_getList(0);
		$this->data['page_title'] .= " Tahun " . $this->data['filter_arr']["tahun"];

		$this->View($this->viewprint);
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'nama',
				'label' => 'Indikator KPI',
				'type' => "list",
				'nofilter' => true,
			),

			# lama
			// array(
			// 	'name' => 'bobot',
			// 	'label' => 'Bobot',
			// 	'width' => "80px",
			// 	'type' => "number",
			// 	'nofilter' => true,
			// ),
			// array(
			// 	'name' => 'polarisasi',
			// 	'label' => 'Polarisasi',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// 	'nofilter' => true,
			// ),
			// array(
			// 	'name' => 'target',
			// 	'label' => 'Target',
			// 	'width' => "80px",
			// 	'type' => "number",
			// 	'nofilter' => true,
			// ),
			// array(
			// 	'name' => 'satuan',
			// 	'label' => 'Satuan',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// 	'nofilter' => true,
			// ),
			# lama end

			# baru
			array(
				'name' => 'satuan',
				'label' => 'Satuan',
				'width' => "auto",
				'type' => "varchar",
				'nofilter' => true,
			),
			array(
				'name' => 'target',
				'label' => 'Target',
				'width' => "80px",
				'type' => "number",
				'nofilter' => true,
			),
			array(
				'name' => 'polarisasi',
				'label' => 'Polarisasi',
				'width' => "auto",
				'type' => "varchar",
				'nofilter' => true,
			),
			array(
				'name' => 'bobot',
				'label' => 'Bobot',
				'width' => "80px",
				'type' => "number",
				'nofilter' => true,
			),
			# baru end

			array(
				'name' => 'totrealisasi',
				'label' => 'Total Realisasi',
				'width' => "80px",
				'type' => "number",
				'nofilter' => true,
			),
			array(
				'name' => 'prostarget',
				'label' => '% thdp Realisasi',
				'width' => "80px",
				'type' => "number",
				'nofilter' => true,
			),
			array(
				'name' => 'realbobot',
				'label' => 'Realisasi Skor Bobot',
				'width' => "80px",
				'type' => "number",
				'nofilter' => true,
			),
			array(
				'name' => 'lastinput',
				'label' => 'Input Terakhir',
				'width' => "auto",
				'type' => "list",
				'value' => ListBulan(),
				'nofilter' => true,
			),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'id_dit_bid' => $this->post['id_dit_bid'],
			'id_unit' => $this->post['id_unit'],
			'id_kpi' => $this->post['id_kpi'],
			'satuan' => $this->post['satuan'],
			'bobot' => $this->post['bobot'],
			'bobot1' => $this->post['bobot1'],
			'polarisasi' => $this->post['polarisasi'],
			'target' => $this->post['target'],
			'analisa' => $this->post['analisa'],
			'is_pic' => (int)$this->post['is_pic'],
			'tahun' => $this->post['tahun'],
		);
	}

	protected function _isValid($record = array(), $show_error = true)
	{
		if (!is_array($this->data['rules']))
			return;

		$rules = array_values($this->data['rules']);

		$this->form_validation->set_rules($rules);

		if (count($rules) && $this->form_validation->run() == FALSE) {
			if ($show_error) {
				$this->data['err_msg'] = validation_errors();
			}

			$this->data['row'] = array_merge($this->data['row'], $record);

			$this->_afterDetail($this->data['row'][$this->pk]);

			$this->View($this->viewdetail);
			exit();
		}

		$id_unit = $record['id_unit'];
		$id_kpi = $record['id_kpi'];
		$tahun = $record['tahun'];

		$totalbobot = (int)$this->conn->GetOne("select sum(bobot) 
		from kpi_target 
		where deleted_date is null and id_unit = " . $this->conn->escape($id_unit) . " 
		and id_kpi <> " . $this->conn->escape($id_kpi) . "
		and tahun = " . $this->conn->escape($tahun)) + (int)$record['bobot'];

		if ($totalbobot > 100) {
			$this->data['err_msg'] = "Total bobot : " . $totalbobot . ", tidak boleh lebih 100";

			$this->data['row'] = array_merge($this->data['row'], $record);

			$this->_afterDetail($this->data['row'][$this->pk]);

			$this->View($this->viewdetail);
			exit();
		}
	}

	protected function _afterDetail($id)
	{
		if ($this->data['row']['id_kpi']) {
			$this->data['rowheader'] = $this->kpi->GetByPk($this->data['row']['id_kpi']);
		}
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
	}

	public function Edit($id = null)
	{

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

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {

			$idt = $this->conn->GetOne("select id_kpi_target from kpi_target 
				where deleted_date is null and tahun = " . $this->conn->escape($record['tahun']) . " 
				and id_kpi = " . $this->conn->escape($record['id_kpi']) . " 
				and id_unit = " . $this->conn->escape($record['id_unit']));
			if ($idt)
				$this->data['row'][$this->pk] = $id = $idt;

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
				redirect("$this->page_ctrl/detail/$id");
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

	protected function Rules()
	{
		return array(
			// "id_dit_bid" => array(
			// 	'field' => 'id_dit_bid',
			// 	'label' => 'DIT BID',
			// 	'rules' => "in_list[" . implode(",", array_keys($this->data['deptarr'])) . "]|max_length[20]",
			// ),
			// "id_subbid" => array(
			// 	'field' => 'id_subbid',
			// 	'label' => 'Subbid',
			// 	'rules' => "in_list[" . implode(",", array_keys($this->data['unitarr'])) . "]|max_length[20]",
			// ),
			"id_kpi" => array(
				'field' => 'id_kpi',
				'label' => 'KPI',
				'rules' => "in_list[" . implode(",", array_keys($this->data['kpiarr'])) . "]|max_length[10]",
			),
			"satuan" => array(
				'field' => 'satuan',
				'label' => 'Satuan',
				'rules' => "max_length[225]",
			),
			"bobot" => array(
				'field' => 'bobot',
				'label' => 'Bobot',
				'rules' => "numeric|max_length[10]",
			),
			"polarisasi" => array(
				'field' => 'polarisasi',
				'label' => 'Polarisasi',
				'rules' => "max_length[100]",
			),
			"target" => array(
				'field' => 'target',
				'label' => 'Target',
				'rules' => "numeric",
			),
			"analisa" => array(
				'field' => 'analisa',
				'label' => 'Analisa',
				'rules' => "",
			),
			"tahun" => array(
				'field' => 'tahun',
				'label' => 'Tahun',
				'rules' => "in_list[" . implode(",", array_keys($this->data['tahunarr'])) . "]|max_length[10]",
			),
		);
	}

	protected function Add_inline($id = null)
	{
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

		$this->data['rules'] = $this->Rules();

		list($p_poster, $p_postmsg) = $this->isValidForm($record, true);

		## EDIT HERE ##
		if ($this->post['act'] === 'save' && !$p_poster) {

			$this->_beforeEdit($record, $id);

			$this->_setLogRecord($record, $id);

			$this->model->conn->StartTrans();

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

			$this->model->conn->CompleteTrans();

			if ($return['success']) {

				$this->_afterEditSucceed($id);
			} else {
				$this->data['row'] = array_merge($this->data['row'], $record);
				$this->data['row'] = array_merge($this->data['row'], $this->post);

				$this->_afterEditFailed($id);

				$this->data['err_msg'] = "Data gagal disimpan";
			}
		}

		if ($this->post['act'] === 'save' && !$p_poster) {
			SetFlash('suc_msg', $return['success']);
			redirect("$this->page_ctrl");
		} else {
			SetFlash('err_msg', $p_postmsg);
			redirect("$this->page_ctrl");
		}
	}

	protected function isValidForm($record = array(), $show_error = true)
	{
		if (!is_array($this->data['rules']))
			return;

		$rules = array_values($this->data['rules']);

		$this->form_validation->set_rules($rules);

		if (count($rules) && $this->form_validation->run() == FALSE) {
			if ($show_error) {
				$this->data['err_msg'] = validation_errors();
			}

			$this->data['row'] = array_merge($this->data['row'], $record);

			$this->_afterDetail($this->data['row'][$this->pk]);
			$p_poster = true;
			$p_postmsg = $this->data['err_msg'];
			return array($p_poster, $p_postmsg);
		}
	}

	public function Add() # function add sebelumnya dari admincontrol
	{
		redirect(base_url('panelbackend/kpi_target_kor/add'));
	}
}
