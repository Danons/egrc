<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Risk_sasaran extends _adminController
{

	public $limit_arr = array('10', '30', '50', '100', '500', '1000');
	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/risk_sasaranlist";
		$this->viewdetail = "panelbackend/risk_sasarandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Sasaran';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Sasaran';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Sasaran';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Sasaran';
		}

		$this->load->model("Risk_sasaranModel", "model");

		$this->load->model("Mt_sdm_jabatanModel", "mtsdmjabatan");

		$this->data['mtsdmjabatanarr'] = $this->mtsdmjabatan->GetCombo();
		$this->data['risksasaranarr'] = $this->model->GetCombo();

		$this->load->model("Risk_kpiModel", "riskkpi");

		$this->data['riskkpiarr'] = $this->riskkpi->GetCombo();
		unset($this->data['riskkpiarr']['']);

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker',
			'treetable',
			'select2'
		);
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'kode',
				'label' => 'Kode',
				'width' => "200px",
				'type' => "varchar2",
			),
			array(
				'name' => 'nama',
				'label' => 'Sasaran',
				'width' => "auto",
				'type' => "varchar2",
			),
		);
	}

	protected function Record($id = null)
	{
		$this->AddOption();
		$record = array(
			'kode' => $this->post['kode'],
			'nama' => $this->post['nama'],
			'id_sasaran_parent' => $this->post['id_sasaran_parent'],
			// 'kpi' => $this->post['kpi'],
			'kpi_deskripsi' => $this->post['kpi_deskripsi'],
			'tgl_mulai_efektif' => $this->post['tgl_mulai_efektif'],
			'tgl_akhir_efektif' => $this->post['tgl_akhir_efektif'],
		);

		return $record;
	}

	protected function AddOption()
	{

		if (($this->post['id_kpi'])) {
			foreach ($this->post['id_kpi'] as $k => $value) {
				$v = $value;

				$ada = $this->data['riskkpiarr'][$v];
				if (!$ada && $v) {
					$record = array();
					$record['nama'] = $v;

					$sql = $this->conn->InsertSQL("risk_kpi", $record);
					$this->conn->Execute($sql);

					$this->post['id_kpi'][$k] = $_POST['id_kpi'][$k] = $id = $this->conn->GetOne("select id_kpi from risk_kpi where deleted_date is null and nama = '{$record['nama']}'");

					$this->data['riskkpiarr'][$id] = $record['nama'];
				}
			}
		}
	}

	protected function Rules()
	{
		return array(
			"nama" => array(
				'field' => 'nama',
				'label' => 'Nama',
				'rules' => "required|max_length[200]",
			),
			"kode" => array(
				'field' => 'kode',
				'label' => 'Kode',
				'rules' => "required|max_length[20]",
			),
			/*"id_kpi[]"=>array(
				'field'=>'id_kpi[]',
				'label'=>'KPI',
				'rules'=>"required",
			),*/
			"kpi_deskripsi" => array(
				'field' => 'kpi_deskripsi',
				'label' => 'Deskripsi KPI',
				'rules' => "max_length[500]",
			),
			"tgl_mulai_efektif" => array(
				'field' => 'tgl_mulai_efektif',
				'label' => 'Tgl. Mulai Efektif',
				'rules' => "required",
			),
			/*"id_jabatan[]"=>array(
				'field'=>'id_jabatan[]',
				'label'=>'Jabatan',
				'rules'=>"required|callback_inlistjabatan",
			),*/
		);
	}
	public function Index($page = 0)
	{
		if (1 /*!$_SESSION[SESSION_APP]['tgl_efektif']*/)
			$_SESSION[SESSION_APP]['tgl_efektif'] = date('Y-m-d');

		if ($_SESSION[SESSION_APP]['tgl_efektif']) {

			$this->data['tgl_efektif'] = $tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

			$this->_setFilter(" '$tgl_efektif' between coalesce(tgl_mulai_efektif, '$tgl_efektif')and coalesce(tgl_akhir_efektif,'$tgl_efektif') ");
		}

		$this->data['header'] = $this->Header();

		$this->_setFilter(' deleted_date is null');

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

	public function inlistjabatan($str)
	{
		$result = $this->mjabatan->GetCombo($str);

		if (!$result[$str]) {
			$this->form_validation->set_message('inlistjabatan', 'Jabatan tidak ditemukan');
			return FALSE;
		}

		return true;
	}

	protected function _beforeDetail1($id, &$owner = null)
	{
		#mengambil dari model karena sudah difilter sesuai akses
		$this->load->model("Risk_scorecardModel", 'riskscorecard');
		$this->data['rowheader']  = $this->riskscorecard->GetByPk($id);

		if (!$this->data['rowheader'])
			$this->Error403();

		$owner = $this->data['rowheader']['owner'];

		if ($owner) {
			$this->data['ownerarr'][$owner] = $this->conn->GetOne("select concat(nama,' (',coalesce(id_unit,''),')') from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($owner));
			$this->load->model("Risk_sasaranModel", "msasaran");
			$this->data['sasaranarr'] = $this->msasaran->GetCombo($owner);
		}

		$this->data['add_param'] .= $id;
	}

	public function Index2($id_scorecard = null, $page = 0)
	{
		$this->layout = "panelbackend/layout_scorecard";
		$this->viewlist = "panelbackend/risk_sasaranlist1";

		$this->_beforeDetail1($id_scorecard, $owner);

		$sasaranstr = '';
		if ($this->data['sasaranarr']) {
			$sasaranstr = implode("','", array_keys($this->data['sasaranarr']));
		}

		$this->_setFilter("id_sasaran in ('$sasaranstr')");

		$this->data['header'] = $this->Header();

		$this->data['list'] = $this->_getList($page);

		$this->data['page'] = $page;

		$param_paging = array(
			'base_url' => base_url("{$this->page_ctrl}/index2/" . $is_scorecard),
			'cur_page' => $page,
			'total_rows' => $this->data['list']['total'],
			'per_page' => $this->limit,
			'first_tag_open' => '<li>',
			'first_tag_close' => '</li>',
			'last_tag_open' => '<li>',
			'last_tag_close' => '</li>',
			'cur_tag_open' => '<li class="active"><a href="#">',
			'cur_tag_close' => '</a></li>',
			'next_tag_open' => '<li>',
			'next_tag_close' => '</li>',
			'prev_tag_open' => '<li>',
			'prev_tag_close' => '</li>',
			'num_tag_open' => '<li>',
			'num_tag_close' => '</li>',
			'anchor_class' => 'pagination__page',

		);
		$this->load->library('pagination');

		$paging = $this->pagination;

		$paging->initialize($param_paging);

		$this->data['paging'] = $paging->create_links();

		$this->data['limit'] = $this->limit;

		$this->data['limit_arr'] = $this->limit_arr;

		$this->View($this->viewlist);
	}

	protected function _afterUpdate($id)
	{
		$ret = $this->_delSertDirektorat($id);

		if ($ret)
			$ret = $this->_delSertKpi($id);

		return $ret;
	}

	protected function _afterInsert($id)
	{
		$ret = $this->_delSertDirektorat($id);

		if ($ret)
			$ret = $this->_delSertKpi($id);

		return $ret;
	}

	private function _delSertDirektorat($id)
	{
		$return = $this->conn->Execute("update risk_sasaran_pic set deleted_date = now() where deleted_date is null and id_sasaran = " . $this->conn->escape($id));

		if (is_array($this->post['id_jabatan'])) {
			foreach ($this->post['id_jabatan'] as $idkey => $value) {
				if ($return) {
					if (!$value)
						continue;

					$record = array();
					$record['id_sasaran'] = $id;
					$record['id_jabatan'] = $value;

					$sql = $this->conn->InsertSQL("risk_sasaran_pic", $record);

					if ($sql) {
						$return = $this->conn->Execute($sql);
					}
				}
			}
		}
		return $return;
	}

	private function _delSertKpi($id)
	{
		$return = $this->conn->Execute("update risk_sasaran_kpi set deleted_date = now() where deleted_date is null and id_sasaran = " . $this->conn->escape($id));

		if (is_array($this->post['id_kpi'])) {
			foreach ($this->post['id_kpi'] as $idkey => $value) {
				if ($return) {
					if (!$value)
						continue;

					$record = array();
					$record['id_sasaran'] = $id;
					$record['id_kpi'] = $value;

					$sql = $this->conn->InsertSQL("risk_sasaran_kpi", $record);

					if ($sql) {
						$return = $this->conn->Execute($sql);
					}
				}
			}
		}
		return $return;
	}

	protected function _beforeDelete($id = null)
	{
		$return = $this->model->Execute("update risk_sasaran_pic set deleted_date = now() where id_sasaran = " . $this->conn->escape($id));
		$return = $this->model->Execute("update risk_sasaran_kpi set deleted_date = now() where id_sasaran = " . $this->conn->escape($id));

		return $return;
	}


	protected function _afterDetail($id)
	{
		if (!($this->data['row']['id_jabatan'])) {
			$id_jabatanarr = array();

			$mtsdmjabatanarr = $this->conn->GetArray("select id_jabatan from risk_sasaran_pic where deleted_date is null and id_sasaran = " . $this->conn->escape($id));

			foreach ($mtsdmjabatanarr as $idkey => $value) {
				$id_jabatanarr[] = $value['id_jabatan'];
			}

			$this->data['row']['id_jabatan'] = $id_jabatanarr;
		}

		if (($this->data['row']['id_jabatan'])) {

			$id_mtsdmjabatanarr = $this->data['row']['id_jabatan'];
			$id_jabatanstr = "'" . implode("','", $id_mtsdmjabatanarr) . "'";

			$mtsdmjabatanarr = $this->conn->GetArray("select * from mt_sdm_jabatan where deleted_date is null and id_jabatan in ($id_jabatanstr)");
			foreach ($mtsdmjabatanarr as $r) {
				$this->data['mtsdmjabatanarr'][$r['id_jabatan']] = $r['nama'] . ' (' . $r['id_unit'] . ')';
			}
		}

		if (!($this->data['row']['id_kpi'])) {
			$id_kpiarr = array();

			$mtsdmkpiarr = $this->conn->GetArray("select id_kpi from risk_sasaran_kpi where deleted_date is null and id_sasaran = " . $this->conn->escape($id));

			foreach ($mtsdmkpiarr as $idkey => $value) {
				$id_kpiarr[] = $value['id_kpi'];
			}

			$this->data['row']['id_kpi'] = $id_kpiarr;
		}
	}
}
