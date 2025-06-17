<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Spi_pkpt extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/spi_pkptlist";
		$this->viewdetail = "panelbackend/spi_pkptdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_profil_spi";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Rencana Kerja Audit Tahunan';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Rencana Kerja Audit Tahunan';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Rencana Kerja Audit Tahunan';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Rencana Kerja Audit Tahunan';
		}

		$this->load->model("Spi_program_auditModel", "model");

		$this->load->model('Risk_risikoModel', 'risk_risiko');
		$this->data['risk_risikoarr'] =  [' ' => ' '] + $this->risk_risiko->GetCombo();

		$this->load->model("Mt_sdm_jabatanModel", "mtjabatan");
		$this->data['jabatanarr'] = ['' => ''] + $this->mtjabatan->GetCombo();

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'select2'
		);
	}

	// public function Index($page = 0)
	// {
	// 	$this->data['header'] = $this->Header();

	// 	$this->data['list'] = $this->_getList($page);

	// 	$this->data['page'] = $page;

	// 	$param_paging = array(
	// 		'base_url' => base_url("{$this->page_ctrl}/index"),
	// 		'cur_page' => $page,
	// 		'total_rows' => $this->data['list']['total'],
	// 		'per_page' => $this->limit,
	// 		'first_tag_open' => '<li class="page-item">',
	// 		'first_tag_close' => '</li>',
	// 		'last_tag_open' => '<li class="page-item">',
	// 		'last_tag_close' => '</li>',
	// 		'cur_tag_open' => '<li class="page-item active"><a href="#" class="page-link">',
	// 		'cur_tag_close' => '</a></li>',
	// 		'next_tag_open' => '<li class="page-item">',
	// 		'next_tag_close' => '</li>',
	// 		'prev_tag_open' => '<li class="page-item">',
	// 		'prev_tag_close' => '</li>',
	// 		'num_tag_open' => '<li class="page-item">',
	// 		'num_tag_close' => '</li>',
	// 		'anchor_class' => 'page-link',
	// 		'attributes' => array('class' => 'page-link'),
	// 	);
	// 	if ($this->post['act'] == "set_filter") {
	// 		$_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'] = round($this->post['tahun_filter']);

	// 		redirect(current_url());
	// 	}
	// 	if ($_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'])
	// 		$tahun = $_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'];

	// 	$_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'] = $tahun;
	// 	$this->data['tahun_filter'] = $tahun;
	// 	$params = array(
	// 		"tahun" => $tahun
	// 	);

	// 	list($tahun) = $this->model->getWhere($params);
	// 	$param = array(
	// 		'tahun' => $tahun,
	// 		'page' => $page,
	// 		'limit' => $this->_limit(),
	// 		'order' => $this->_order(),
	// 		'filter' => $this->_getFilter()
	// 	);

	// 	$param['filter'] = str_replace("1=1", "", $param['filter']);

	// 	// dpr($where, 1);

	// 	$this->data['list'] = $this->model->SelectGridRisk(
	// 		$param
	// 	);

	// 	$this->data['page'] = $page;


	// 	$this->load->library('pagination');

	// 	$paging = $this->pagination;

	// 	$paging->initialize($param_paging);

	// 	$this->data['paging'] = $paging->create_links();

	// 	$this->data['limit'] = $this->limit;

	// 	$this->data['limit_arr'] = $this->limit_arr;

	// 	$this->View($this->viewlist);
	// }

	public function Index($page = 0)
	{
		$tahun = date('Y');
		if ($this->post['act'] == "set_filter") {
			$_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'] = round($this->post['tahun_filter']);

			redirect(current_url());
		}
		if ($_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'])
			$tahun = $_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'];

		$_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'] = $tahun;
		$this->data['tahun_filter'] = $tahun;

		$this->_setFilter("exists (select 1 from spi_program_audit 
		where deleted_date is null and id_program_audit 
		and tahun = " . $this->conn->escape($tahun) . ")");

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

	protected function Header()
	{
		return array(
			array(
				'name' => 'nama_audit',
				'label' => 'Auditi',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'id_risk_risiko',
				'label' => 'Risk Risiko',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['risk_risikoarr'],
			),
			array(
				'name' => 'pengawas',
				'label' => 'Nama Pengawas',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'jabatan',
				'label' => 'Jabatan',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['jabatanarr'],
			),
			array(
				'name' => 'biaya',
				'label' => 'Biaya',
				'width' => 'auto',
				'type' => 'varchar',

			),
			array(
				'name' => 'tanggal_lhe',
				'label' => 'Tanggal LHE',
				'width' => 'auto',
				'type' => 'varchar',

			),
			array(
				'name' => 'keterangan',
				'label' => 'keterangan',
				'width' => "auto",
				'type' => "varchar",
			),

		);
	}

	protected function Record($id = null)
	{
		return array(
			'nama_audit' => $this->post['nama_audit'],
			'id_risk_risiko' => $this->post['id_risk_risiko'],
			'jenis_audit' => $this->post['jenis_audit'],
			'frekuensi_audit' => $this->post['frekuensi_audit'],

		);
	}

	protected function Rules()
	{
		return array(
			"nama_audit" => array(
				'field' => 'nama_audit',
				'label' => 'Auditi',
				'rules' => "max_length[200]",
			),
			"frekuensi_audit" => array(
				'field' => 'frkuensi_audit',
				'label' => 'Frek Audit',
				'rules' => "max_length[200]",
			),
			"jenis_audit" => array(
				'field' => 'jenis_audit',
				'label' => 'Jenis Audit',
				'rules' => "max_length[200]",
			),

		);
	}
	protected function _afterInsert($id)
	{
		$ret = true;

		if ($ret)
			$ret = $this->_delSertTahun($id);
		return $ret;
	}

	private function _delSertTahun($id)
	{
		$return = $this->conn->Execute("update spi_program_audit_tahun where deleted_date is null and id_program_audit = " . $this->conn->escape($id));

		if (is_array($this->post['tahun'])) {
			foreach ($this->post['tahun'] as $key => $value) {
				if ($return) {
					if (!$value)
						continue;

					$record = array();
					$record['id_program_audit'] = $id;
					$record['tahun'] = $value;

					$sql = $this->conn->InsertSQL("spi_program_audit_tahun", $record);

					if ($sql) {
						$return = $this->conn->Execute($sql);
					}
				}
			}
		}
		return $return;
	}
	protected function _afterDetail($id = null)
	{
		if (!$this->data['row']['tahun']) {
			$data_tahun = $this->conn->GetArray("select * from spi_program_audit_tahun a where deleted_date is null and id_program_audit = " . $this->conn->escape($id));

			$this->data['row']['tahun'] = array();
			foreach ($data_tahun as $f) {
				$this->data['row']['tahun'][] = $f['tahun'];
			}
		}
	}
}
