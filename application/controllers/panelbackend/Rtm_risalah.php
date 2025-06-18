<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Rtm_risalah extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/rtm_risalahlist";
		$this->viewdetail = "panelbackend/rtm_risalahdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Risalah RTM';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Tindak Lanjut Risalah RTM';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Risalah RTM';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Risalah RTM';
		}

		$this->load->model("Rtm_uraianModel", "model");
		$this->load->model("RtmModel", "rtm");
		$this->data['rtmarr'] = $this->rtm->GetCombo();

		$this->load->model("Mt_jenis_rtmModel", "mtjenisrtm");
		$this->data['mtjenisrtmarr'] = $this->mtjenisrtm->GetComboP();

		$this->load->model("Mt_sdm_unitModel", "unit");
		$this->data['unitarr'] = $this->unit->GetCombo();
		unset($this->data['unitarr']['']);


		$this->load->model("Rtm_uraian_filesModel", "modelfile");
		$this->data['configfile'] = $this->config->item('file_upload_config');

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'tinymce', 'select2', 'upload'
		);
	}

	public function Index($page = 0)
	{

		#unit
		if ($this->request['id_jenis_rtm'] !== null)
			$_SESSION[SESSION_APP][$this->page_ctrl]['id_jenis_rtm'] = $this->request['id_jenis_rtm'];
		if ($_SESSION[SESSION_APP][$this->page_ctrl]['id_jenis_rtm'] !== null)
			$this->data['id_jenis_rtm'] = $_SESSION[SESSION_APP][$this->page_ctrl]['id_jenis_rtm'];
		if ($this->request['id_rtm'] !== null)
			$_SESSION[SESSION_APP][$this->page_ctrl]['id_rtm'] = $this->request['id_rtm'];
		if ($_SESSION[SESSION_APP][$this->page_ctrl]['id_rtm'] !== null)
			$this->data['id_rtm'] = $_SESSION[SESSION_APP][$this->page_ctrl]['id_rtm'];
		if ($this->request['id_unit'] !== null)
			$_SESSION[SESSION_APP][$this->page_ctrl]['id_unit'] = $this->request['id_unit'];
		if ($_SESSION[SESSION_APP][$this->page_ctrl]['id_unit'] !== null)
			$this->data['id_unit'] = $_SESSION[SESSION_APP][$this->page_ctrl]['id_unit'];
		if ($this->request['status'] !== null)
			$_SESSION[SESSION_APP][$this->page_ctrl]['status'] = $this->request['status'];
		if ($_SESSION[SESSION_APP][$this->page_ctrl]['status'] !== null)
			$this->data['status'] = $_SESSION[SESSION_APP][$this->page_ctrl]['status'];

		if (!$this->Access("view_all", "main"))
			$this->data['id_unit'] = $_SESSION[SESSION_APP]['id_unit'];

		$id_rtm = $this->data['id_rtm'];
		$id_unit = $this->data['id_unit'];
		$status = $this->data['status'];
		$id_jenis_rtm = $this->data['id_jenis_rtm'];

		if ($id_unit) {
			$this->_setFilter("exists (select 1 
			from rtm_urian_unit 
			where deleted_date is null and rtm_uraian.id_rtm_uraian = rtm_urian_unit.id_rtm_uraian 
			and rtm_urian_unit.id_unit = " . $this->conn->escape($id_unit) . ")");
		}

		if ($id_rtm) {
			$id_rtm_max = $this->conn->GetOne("select max(id_rtm) from rtm");
			if ($id_rtm == $id_rtm_max) {
				$this->_setFilter("(exists (select 1 
				from rtm_uraian_link 
				where deleted_date is null and rtm_uraian.id_rtm_uraian = rtm_uraian_link.id_rtm_uraian 
				and rtm_uraian_link.id_rtm = " . $this->conn->escape($id_rtm) . ") or status = 0)");
			} else {
				$this->_setFilter("exists (select 1 
				from rtm_uraian_link 
				where deleted_date is null and rtm_uraian.id_rtm_uraian = rtm_uraian_link.id_rtm_uraian 
				and rtm_uraian_link.id_rtm = " . $this->conn->escape($id_rtm) . ")");
			}
		}

		if ($id_jenis_rtm) {
			$this->_setFilter("id_jenis_rtm_parent = " . $this->conn->escape($id_jenis_rtm));
		}

		if ($status !== null && $status !== '') {
			$this->_setFilter("status = " . $status);
			// dpr("status = " . $status, 1);
		}

		if ($this->request['act'] == 'set_filter')
			redirect(current_url());

		$this->data['addbutton'] .= UI::createSelect('id_rtm', ["" => "Pilih RTM"] + $this->data['rtmarr'], $this->data['id_rtm'], true, 'form-control ', "style='width:100px;display:inline;' onchange='goSubmit(\"set_filter\");'") . " &nbsp;&nbsp;&nbsp;";
		$this->data['addbutton'] .= UI::createSelect('id_jenis_rtm', ["" => "Pilih jenis RTM"] + $this->data['mtjenisrtmarr'], $this->data['id_jenis_rtm'], true, 'form-control ', "style='width:200px;display:inline;' onchange='goSubmit(\"set_filter\");'") . " &nbsp;&nbsp;&nbsp;";
		$this->data['addbutton'] .= UI::createSelect('id_unit', ["" => "Pilih unit"] + $this->data['unitarr'], $this->data['id_unit'], true, 'form-control ', "style='width:200px;display:inline;' onchange='goSubmit(\"set_filter\");'") . " &nbsp;&nbsp;&nbsp;";
		$this->data['addbutton'] .= UI::createSelect('status', ["" => "Pilih status"] + array('0' => 'Open', '1' => 'Close'), $this->data['status'], true, 'form-control ', "style='width:120px;display:inline;' onchange='goSubmit(\"set_filter\");'") . " &nbsp;&nbsp;&nbsp;";
		$this->data['addbutton'] .= "<a href='" . site_url("panelbackend/rtm_risalah/go_print") . "' target='_BLANK' class='btn btn-primary'>Print</a>";

		$this->_setFilter("is_risalah = 1");
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

		$this->View($this->viewlist);
	}


	public function go_print()
	{
		if ($_SESSION[SESSION_APP][$this->page_ctrl]['id_rtm'] !== null)
			$this->data['id_rtm'] = $_SESSION[SESSION_APP][$this->page_ctrl]['id_rtm'];

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['id_unit'] !== null)
			$this->data['id_unit'] = $_SESSION[SESSION_APP][$this->page_ctrl]['id_unit'];

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['status'] !== null)
			$this->data['status'] = $_SESSION[SESSION_APP][$this->page_ctrl]['status'];

		if (!$this->Access("view_all", "main"))
			$this->data['id_unit'] = $_SESSION[SESSION_APP]['id_unit'];

		$id_rtm = $this->data['id_rtm'];
		$id_unit = $this->data['id_unit'];
		$status = $this->data['status'];

		$this->data['rtm'] = $this->rtm->GetByPk($id_rtm);

		if ($id_unit) {
			$this->_setFilter("exists (select 1 
			from rtm_urian_unit 
			where deleted_date is null and rtm_uraian.id_rtm_uraian = rtm_urian_unit.id_rtm_uraian 
			and rtm_urian_unit.id_unit = " . $this->conn->escape($id_unit) . ")");
		}

		if ($id_rtm) {
			$id_rtm_max = $this->conn->GetOne("select max(id_rtm) from rtm");
			if ($id_rtm == $id_rtm_max) {
				$this->_setFilter("(exists (select 1 
				from rtm_uraian_link 
				where deleted_date is null and rtm_uraian.id_rtm_uraian = rtm_uraian_link.id_rtm_uraian 
				and rtm_uraian_link.id_rtm = " . $this->conn->escape($id_rtm) . ") or status = 0)");
			} else {
				$this->_setFilter("exists (select 1 
				from rtm_uraian_link 
				where deleted_date is null and rtm_uraian.id_rtm_uraian = rtm_uraian_link.id_rtm_uraian 
				and rtm_uraian_link.id_rtm = " . $this->conn->escape($id_rtm) . ")");
			}
		}

		if ($status !== null && $status !== '') {
			$this->_setFilter("status = " . $status);
			// dpr("status = " . $status, 1);
		}

		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";
		$this->data['no_header'] = true;
		$this->data['no_title'] = true;
		$this->viewprint = $this->viewlist . 'print';

		$this->data['header'] = $this->Header();

		$this->data['list'] = $this->_getListPrint();

		$rows = $this->data['list']['rows'];
		$rowst = [];
		foreach ($rows as $r) {
			$row = $this->conn->GetRow("select * from rtm_uraian_link 
			where deleted_date is null and id_rtm_uraian =" . $this->conn->escape($r['id_rtm_uraian']) . " 
			and id_rtm = " . $this->conn->escape($id_rtm));
			$r['tindak_lanjut'] = $row['tindak_lanjut'];
			$r['tindak_lanjut_rencana_penyelesaian'] = $row['tindak_lanjut_rencana_penyelesaian'];
			$r['tindak_lanjut_realisasi_penyelesaian'] = $row['tindak_lanjut_realisasi_penyelesaian'];
			$r['status'] = $row['status'];
			$r['picstr'] = implode(",", $this->conn->GetList("select b.table_code as idkey, b.table_desc as val 
				from rtm_urian_unit a 
				join mt_sdm_unit b on a.id_unit = b.table_code
				where a.deleted_date is null and a.id_rtm_uraian = " . $this->conn->escape($r['id_rtm_uraian'])));
			$rowst[$r['id_jenis_rtm_parent']][] = $r;
		}

		$this->data['list']['rows'] = $rowst;

		$rows = $this->conn->GetArray("select * from rtm where deleted_date is null and id_rtm < " . $this->conn->escape($id_rtm) . " order by id_rtm desc");
		$id_rtmb1 = $rows[0]['id_rtm'];
		$this->data['rowsb'] = [];
		if ($id_rtmb1) {
			$this->data['rowsb'] = $this->conn->GetArray("select 
			b.id_jenis_rtm_parent,
			count(1) as jp, 
			count(case when a.status = 0 then 1 else null end) j0, 
			count(case when a.status = 1 then 1 else null end) j1  
			from rtm_uraian_link a 
			join rtm_uraian b on a.id_rtm_uraian = b.id_rtm_uraian
			where a.deleted_date is null and a.id_rtm = $id_rtmb1 
			group by b.id_jenis_rtm_parent");
		}
		$id_rtmb2 = $rows[1]['id_rtm'];
		if ($id_rtmb2) {
			$row = $this->conn->GetRow("select count(1) as jp, count(case when status = 0 then 1 else null end) j0, count(case when status = 1 then 1 else null end) j1  from rtm_urian_link where deleted_date is null and id_rtm = $id_rtmb2 group by id_rtm");
			$this->data['jp'] = $row['jp'];
			$this->data['j0'] = $row['j0'];
			$this->data['j1'] = $row['j1'];
		}

		$this->View($this->viewprint);
	}

	protected function _onDetail($id = null)
	{
		$this->max_id_rtm = $this->conn->GetOne("select max(id_rtm) from rtm_uraian_link where deleted_date is null and id_rtm_uraian = " . $this->conn->escape($id));
		if (!$this->data['row']['id_rtm'])
			$this->data['row']['id_rtm'] = $this->max_id_rtm;

		if (!$this->data['row']['id_rtm'])
			$this->max_id_rtm = $this->data['row']['id_rtm'] = $this->conn->GetOne("select max(id_rtm) from rtm where deleted_date is null ");

		if ($this->data['row']['id_jenis_rtm_parent'])
			$this->data['mtjenisrtmarrsub'] = $this->mtjenisrtm->GetComboP($this->data['row']['id_jenis_rtm_parent']);

		if ($this->data['row']['id_rtm']) {
			$this->data['rtm'] = $this->rtm->GetByPk($this->data['row']['id_rtm']);
			$row = $this->conn->GetRow("select * from rtm_uraian_link where deleted_date is null and id_rtm_uraian = " . $this->conn->escape($id) . " and id_rtm=" . $this->conn->escape($this->data['row']['id_rtm']));
			$this->data['row']['status'] = $row['status'];
			$this->data['row']['tindak_lanjut'] = $row['tindak_lanjut'];
			$this->data['row']['tindak_lanjut_rencana_penyelesaian'] = $row['tindak_lanjut_rencana_penyelesaian'];
			$this->data['row']['tindak_lanjut_realisasi_penyelesaian'] = $row['tindak_lanjut_realisasi_penyelesaian'];
		}

		if (!$this->data['row']['id_unit'])
			$this->data['row']['id_unit'] = $this->conn->GetList("select id_unit as idkey, id_unit as val from rtm_urian_unit where deleted_date is null and id_rtm_uraian = " . $this->conn->escape($id));

		if (!$this->data['row']['progress'])
			$this->data['row']['progress'] = $this->conn->GetArray("select * from rtm_progress where deleted_date is null and id_rtm_uraian = " . $this->conn->escape($id));


		return true;
	}

	protected function _afterDetail($id)
	{
		if ($this->modelfile) {
			if (!$this->data['row']['files']['id'] && $id) {
				$rows = $this->conn->GetArray("select *
				from {$this->modelfile->table}
				where deleted_date is null and {$this->model->pk} = " . $this->conn->escape($id) . " 
				and id_rtm = " . $this->conn->escape($this->data['row']['id_rtm']));

				foreach ($rows as $r) {
					$this->data['row']['files']['id'][] = $r[$this->modelfile->pk];
					$this->data['row']['files']['name'][] = $r['client_name'];
				}
			}
		}
	}

	protected function _afterInsert($id)
	{
		$ret = true;

		if ($this->modelfile) {
			if (!empty($this->post['files'])) {
				foreach ($this->post['files']['id'] as $k => $v) {
					$return = $this->_updateFiles(array($this->pk => $id, "id_rtm" => $this->data['row']['id_rtm']), $v);

					$ret = $return['success'];
				}
			}
		}

		if ($this->post['id_unit'] && $ret) {
			$ret = $this->conn->Execute("update rtm_urian_unit set deleted_date = now() where id_rtm_uraian = " . $this->conn->escape($id));
			foreach ($this->post['id_unit'] as $id_unit) {
				if (!$ret)
					break;
				$ret = $this->conn->goInsert("rtm_urian_unit", ["id_unit" => $id_unit, "id_rtm_uraian" => $id]);
			}
		}

		if ($this->post['progress'] && $ret) {
			$idarr = [];
			foreach ($this->post['progress'] as $r) {
				if (!$ret)
					break;

				$r['id_rtm_uraian'] = $id;

				if ($r['id_rtm_progress'])
					$ret = $this->conn->goUpdate("rtm_progress", $r, "id_rtm_progress= " . $this->conn->escape($r['id_rtm_progress']));
				else {
					$ret = $this->conn->goInsert("rtm_progress", $r);
					$r['id_rtm_progress'] = $this->conn->GetOne("select max(id_rtm_progress) from rtm_progress where deleted_date is null and id_rtm_uraian = " . $this->conn->escape($id));
				}

				$idarr[] = $r['id_rtm_progress'];
			}

			if ($ret)
				$ret = $this->conn->Execute("update rtm_progress set deleted_date = now() where id_rtm_uraian = " . $this->conn->escape($id) . " and id_rtm_progress not in (" . implode(",", $idarr) . ")");
		}

		if ($ret) {
			$cek = $this->conn->GetOne("select 1 from rtm_uraian_link where deleted_date is null and id_rtm_uraian = " . $this->conn->escape($id) . " and id_rtm = " . $this->conn->escape($this->post['id_rtm']));
			if ($cek) {
				$ret = $this->conn->goUpdate("rtm_uraian_link", [
					"id_rtm_uraian" => $id,
					"id_rtm" => $this->post['id_rtm'],
					"tindak_lanjut" => $this->post['tindak_lanjut'],
					"tindak_lanjut_rencana_penyelesaian" => $this->post['tindak_lanjut_rencana_penyelesaian'],
					"tindak_lanjut_realisasi_penyelesaian" => $this->post['tindak_lanjut_realisasi_penyelesaian'],
					"status" => $this->post['status']
				], "id_rtm_uraian = " . $this->conn->escape($id) . " and id_rtm = " . $this->conn->escape($this->post['id_rtm']));
			} else {
				$ret = $this->conn->goInsert("rtm_uraian_link", [
					"id_rtm_uraian" => $id,
					"id_rtm" => $this->post['id_rtm'],
					"tindak_lanjut" => $this->post['tindak_lanjut'],
					"tindak_lanjut_rencana_penyelesaian" => $this->post['tindak_lanjut_rencana_penyelesaian'],
					"tindak_lanjut_realisasi_penyelesaian" => $this->post['tindak_lanjut_realisasi_penyelesaian'],
					"status" => $this->post['status']
				]);
			}
		}

		return $ret;
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'uraian',
				'label' => 'Uraian',
				'width' => "auto",
				'type' => "longblob",
			),
			array(
				'name' => 'analisis',
				'label' => 'Analisis',
				'width' => "auto",
				'type' => "longblob",
			),
			array(
				'name' => 'uraian_rencana',
				'label' => 'Uraian Rencana',
				'width' => "auto",
				'type' => "longblob",
			),
			array(
				'name' => 'uraian_target',
				'label' => 'Uraian Target',
				'width' => "auto",
				'type' => "longblob",
			),
			array(
				'name' => 'picstr',
				'label' => 'PIC',
				'width' => "auto",
			),
			array(
				'name' => 'status',
				'label' => 'Status',
				'width' => "auto",
				'type' => "list",
				'value' => array('0' => 'Open', '1' => 'Close'),
			),
			array(
				'name' => 'id_rtm',
				'label' => 'RTM Ke',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['rtmarr'],
			),
			// array(
			// 	'name'=>'tindak_lanjut', 
			// 	'label'=>'Tindak Lanjut', 
			// 	'width'=>"auto",
			// 	'type'=>"longblob",
			// ),
			// array(
			// 	'name'=>'tindak_lanjut_rencana_penyelesaian', 
			// 	'label'=>'Tindak Lanjut Rencana Penyelesaian', 
			// 	'width'=>"auto",
			// 	'type'=>"longblob",
			// ),
			// array(
			// 	'name'=>'tindak_lanjut_realisasi_penyelesaian', 
			// 	'label'=>'Tindak Lanjut Realisasi Penyelesaian', 
			// 	'width'=>"auto",
			// 	'type'=>"longblob",
			// ),
			// array(
			// 	'name'=>'id_jenis_rtm', 
			// 	'label'=>'Jenrtm', 
			// 	'width'=>"auto",
			// 	'type'=>"list",
			// 	'value'=>array(''=>'-pilih-','0'=>'Tidak','1'=>'Iya'),
			// ),
			// array(
			// 	'name'=>'id_jenis_rtm_parent', 
			// 	'label'=>'Jenis RTM Parent', 
			// 	'width'=>"auto",
			// 	'type'=>"list",
			// 	'value'=>$this->data['mtjenisrtmarr'],
			// ),
			// array(
			// 	'name'=>'is_risalah', 
			// 	'label'=>'Risalah', 
			// 	'width'=>"auto",
			// 	'type'=>"list",
			// 	'value'=>array(''=>'-pilih-','0'=>'Tidak','1'=>'Iya'),
			// ),
			// array(
			// 	'name'=>'is_tindak_lanjut', 
			// 	'label'=>'Tindak Lanjut', 
			// 	'width'=>"auto",
			// 	'type'=>"list",
			// 	'value'=>array(''=>'-pilih-','0'=>'Tidak','1'=>'Iya'),
			// ),
		);
	}

	protected function Record($id = null)
	{
		if ($this->post['progress'])
			foreach ($this->post['progress'] as &$r) {
				$r['target'] = Rupiah2Number($r['target']);
				$r['realisasi'] = Rupiah2Number($r['realisasi']);
				$r['competitor'] = Rupiah2Number($r['competitor']);
			}
		$return = array(
			'uraian' => $this->post['uraian'],
			'analisis' => $this->post['analisis'],
			'uraian_rencana' => $this->post['uraian_rencana'],
			'uraian_target' => $this->post['uraian_target'],
			'keterangan_pic' => $this->post['keterangan_pic'],
			'id_jenis_rtm' => $this->post['id_jenis_rtm'],
			'id_jenis_rtm_parent' => $this->post['id_jenis_rtm_parent'],
			// 'is_risalah' => (int)$this->post['is_risalah'],
			// 'is_tindak_lanjut' => (int)$this->post['is_tindak_lanjut'],
			'is_grafik' => (int)$this->post['is_grafik'],
		);

		if ($this->post['id_rtm'] == $this->max_id_rtm) {
			$return['status'] = $this->post['status'];
			$return['tindak_lanjut'] = $this->post['tindak_lanjut'];
			$return['tindak_lanjut_rencana_penyelesaian'] = $this->post['tindak_lanjut_rencana_penyelesaian'];
			$return['tindak_lanjut_realisasi_penyelesaian'] = $this->post['tindak_lanjut_realisasi_penyelesaian'];
		}


		if (!Access("evaluasi", "panelbackend/rtm_risalah"))
			unset($return['status']);

		return $return;
	}

	protected function Rules()
	{
		return array(
			"uraian" => array(
				'field' => 'uraian',
				'label' => 'Uraian',
				'rules' => "",
			),
			"analisis" => array(
				'field' => 'analisis',
				'label' => 'Analisis',
				'rules' => "",
			),
			"uraian_rencana" => array(
				'field' => 'uraian_rencana',
				'label' => 'Uraian Rencana',
				'rules' => "",
			),
			"uraian_target" => array(
				'field' => 'uraian_target',
				'label' => 'Uraian Target',
				'rules' => "",
			),
			"keterangan_pic" => array(
				'field' => 'keterangan_pic',
				'label' => 'Keterangan PIC',
				'rules' => "",
			),
			"status" => array(
				'field' => 'status',
				'label' => 'Status',
				'rules' => "integer",
			),
			"tindak_lanjut" => array(
				'field' => 'tindak_lanjut',
				'label' => 'Tindak Lanjut',
				'rules' => "",
			),
			"tindak_lanjut_rencana_penyelesaian" => array(
				'field' => 'tindak_lanjut_rencana_penyelesaian',
				'label' => 'Tindak Lanjut Rencana Penyelesaian',
				'rules' => "",
			),
			"tindak_lanjut_realisasi_penyelesaian" => array(
				'field' => 'tindak_lanjut_realisasi_penyelesaian',
				'label' => 'Tindak Lanjut Realisasi Penyelesaian',
				'rules' => "",
			),
			"id_jenis_rtm" => array(
				'field' => 'id_jenis_rtm',
				'label' => 'Jenis RTM',
				// 'rules' => "in_list[" . implode(",", array_keys($this->data['mtjenisrtmarrsub'])) . "]",
			),
			"id_jenis_rtm_parent" => array(
				'field' => 'id_jenis_rtm_parent',
				'label' => 'Jenis RTM Parent',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtjenisrtmarr'])) . "]",
			),
			"is_risalah" => array(
				'field' => 'is_risalah',
				'label' => 'IS Risalah',
				'rules' => "integer",
			),
			"is_tindak_lanjut" => array(
				'field' => 'is_tindak_lanjut',
				'label' => 'IS Tindak Lanjut',
				'rules' => "integer",
			),
		);
	}
}
