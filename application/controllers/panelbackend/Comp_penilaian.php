<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Comp_penilaian extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/comp_penilaianlist";
		$this->viewdetail = "panelbackend/comp_penilaianlistdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Penilaian';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Penilaian';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Penilaian';
			$this->data['edited'] = false;
		} else {
			$this->data['edited'] = true;
			$this->data['page_title'] = 'Daftar Penilaian';
		}

		$this->load->model("Comp_penilaianModel", "model");

		$this->load->model("Comp_kebutuhanModel", "compkebutuhan");
		$this->data['compkebutuhanarr'] = $this->compkebutuhan->GetCombo();

		$this->load->model("DokumenModel", "dokumen");
		$this->data['dokumenarr'] = $this->dokumen->GetCombo();
		$this->data['dokumenarr'][''] = 'Pilih dokumen';

		$this->load->model("Mt_status_penilaianModel", "mtstatuspenilaian");
		$this->data['mtstatuspenilaianarr'] = $this->mtstatuspenilaian->GetCombo();

		$this->load->model("Mt_sdm_unitModel", "mtsdmunit");
		$this->data['mtsdmunitarr'] = $this->mtsdmunit->GetCombo();
		$this->data['mtsdmunitarr'][''] = 'Pilih unit';

		$this->load->model("Mt_sdm_jabatanModel", "mtsdmjabatan");
		$this->data['mtsdmjabatanarr'] = $this->mtsdmjabatan->GetCombo();

		$this->load->model("Comp_penilaian_filesModel", "modelfile");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'upload', 'select2'
		);
		$this->data['configfile'] = $this->config->item('file_upload_config');
		$this->data['configfile']['max_size'] = 1000000;
		$this->config->set_item("file_upload_config", $this->data['configfile']);

		unset($this->access_role['add']);
		// unset($this->access_role['edit']);

		$this->access_role['list_print'] = 1;
		$this->access_role['print_detail'] = 1;
	}

	public function Index($page = 0)
	{
		if (!$this->access_role['view_all']) {
			$id_unit = $_SESSION[SESSION_APP]['id_unit'];
		}

		$tgl_efektif = $this->data['tgl_efektif'];
		list($tahun, $bln, $tgl) = explode("-", $tgl_efektif);


		#filter
		if ($this->post['act'] == "set_filter") {
			$_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'] = round($this->post['tahun_filter']);
			$_SESSION[SESSION_APP][$this->page_ctrl]['id_unit_filter'] = $this->post['id_unit_filter'];

			redirect(current_url());
		}

		if ($this->get['act'] == "go_detail") {
			$_SESSION[SESSION_APP][$this->page_ctrl]['id_dokumen_filter'] = $this->get['id_dokumen_filter'];
			redirect("panelbackend/comp_penilaian/detail");
			die();
		}

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'])
			$tahun = $_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'];

		$_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'] = $tahun;

		if (!$this->Access("view_all", "main"))
			$id_unit = $_SESSION[SESSION_APP]['id_unit'];
		else
			$id_unit = $_SESSION[SESSION_APP][$this->page_ctrl]['id_unit_filter'];

		$this->data['tahun_filter'] = $tahun;
		$this->data['id_unit_filter'] = $id_unit;

		$this->data['rows'] = $this->conn->GetArray("select a.id_dokumen, a.nomor_dokumen, a.nama
		from dokumen a 
		where a.deleted_date is null and exists (
			select 1 from dokumen_unit b 
			where b.deleted_date is null and a.id_dokumen = b.id_dokumen and b.id_unit = " . $this->conn->escape($id_unit) . "
		) and a.is_aktif = 1 and exists (
			select 1 from comp_kebutuhan c 
			where c.deleted_date is null and c.id_dokumen = a.id_dokumen
		)
		group by a.id_dokumen, a.nomor_dokumen, a.nama");

		foreach ($this->data['rows'] as &$r) {


			$rw = $this->conn->GetRow("select 
			count(id_status_penilaian) as jumlah,
			sum(id_status_penilaian) as total
			from comp_penilaian a 
			where a.deleted_date is null and tahun = " . $this->conn->escape($tahun) . " 
			and exists (select 1 from comp_kebutuhan b 
			where b.deleted_date is null and a.id_comp_kebutuhan = b.id_comp_kebutuhan 
			and b.id_dokumen = " . $this->conn->escape($r['id_dokumen']) . ")
			and id_unit = " . $this->conn->escape($id_unit));

			if ($rw['jumlah'])
				$r['penilaian'] = 100 * (($rw['total'] - $rw['jumlah']) / ($rw['jumlah'] * 2));
		}

		$this->data['mtsdmunitarr'] = array('' => 'Unit') + $this->conn->GetList("select 
		table_code as idkey, table_desc val 
		from mt_sdm_unit where deleted_date is null");

		$this->View($this->viewlist);
	}

	public function go_print()
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";
		$this->data['no_header'] = true;

		if (!$this->access_role['view_all']) {
			$id_unit = $_SESSION[SESSION_APP]['id_unit'];
		}

		$tgl_efektif = $this->data['tgl_efektif'];
		list($tahun, $bln, $tgl) = explode("-", $tgl_efektif);

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'])
			$tahun = $_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'];

		if (!$this->Access("view_all", "main"))
			$id_unit = $_SESSION[SESSION_APP]['id_unit'];
		else
			$id_unit = $_SESSION[SESSION_APP][$this->page_ctrl]['id_unit_filter'];

		$this->data['tahun_filter'] = $tahun;
		$this->data['id_unit_filter'] = $id_unit;

		$this->data['rows'] = $this->conn->GetArray("select a.id_dokumen, a.nomor_dokumen, a.nama
		from dokumen a 
		where a.deleted_date is null and exists (
			select 1 from dokumen_unit b 
			where b.deleted_date is null and a.id_dokumen = b.id_dokumen and b.id_unit = " . $this->conn->escape($id_unit) . "
		) and a.is_aktif = 1 and exists (
			select 1 from comp_kebutuhan c 
			where c.deleted_date is null and c.id_dokumen = a.id_dokumen
		)
		group by a.id_dokumen, a.nomor_dokumen, a.nama");

		foreach ($this->data['rows'] as &$r) {


			$rw = $this->conn->GetRow("select 
			count(id_status_penilaian) as jumlah,
			sum(id_status_penilaian) as total
			from  comp_penilaian a 
			where a.deleted_date is null and tahun = " . $this->conn->escape($tahun) . " 
			and exists (select 1 from comp_kebutuhan b 
			where b.deleted_date is null and a.id_comp_kebutuhan = b.id_comp_kebutuhan 
			and b.id_dokumen = " . $this->conn->escape($r['id_dokumen']) . ")
			and id_unit = " . $this->conn->escape($id_unit));

			if ($rw['jumlah'])
				$r['penilaian'] = 100 * (($rw['total'] - $rw['jumlah']) / ($rw['jumlah'] * 2));
		}

		$this->data['mtsdmunitarr'] = array('' => 'Unit') + $this->conn->GetList("select 
		table_code as idkey, table_desc val 
		from mt_sdm_unit where deleted_date is null");

		$this->View("panelbackend/comp_penilaianprint");
	}

	public function Detail($id = 0)
	{
		if (!$this->access_role['view_all']) {
			$id_unit = $_SESSION[SESSION_APP]['id_unit'];
		}

		$tgl_efektif = $this->data['tgl_efektif'];
		list($tahun, $bln, $tgl) = explode("-", $tgl_efektif);


		#filter
		if ($this->post['act'] == "set_filter") {
			$_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'] = round($this->post['tahun_filter']);
			$_SESSION[SESSION_APP][$this->page_ctrl]['id_unit_filter'] = $this->post['id_unit_filter'];
			$_SESSION[SESSION_APP][$this->page_ctrl]['id_dokumen_filter'] = $this->post['id_dokumen_filter'];

			redirect(current_url());
		}


		if ($_SESSION[SESSION_APP][$this->page_ctrl]['id_dokumen_filter'])
			$id_dokumen = $_SESSION[SESSION_APP][$this->page_ctrl]['id_dokumen_filter'];

		$_SESSION[SESSION_APP][$this->page_ctrl]['id_dokumen_filter'] = $id_dokumen;

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'])
			$tahun = $_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'];

		$_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'] = $tahun;

		if (!$this->Access("view_all", "main"))
			$id_unit = $_SESSION[SESSION_APP]['id_unit'];
		else
			$id_unit = $_SESSION[SESSION_APP][$this->page_ctrl]['id_unit_filter'];

		if (!$id_unit)
			$id_unit = $this->post['id_unit_filter'];

		$id_dokumen = $_SESSION[SESSION_APP][$this->page_ctrl]['id_dokumen_filter'];

		$this->data['id_dokumen_filter'] = $id_dokumen;
		$this->data['tahun_filter'] = $tahun;
		$this->data['id_unit_filter'] = $id_unit;

		if ($this->post['act'] == 'save') {
			$ret = true;
			foreach ($this->post['penilaian'] as $id_comp_kebutuhan => $rs) {
				foreach ($rs as $id_unit1 => $rs1) {
					foreach ($rs1 as $i => $rs2) {
						foreach ($rs2 as $tahun => $v) {
							if (!$ret)
								break;

							$record = array();
							$record['id_comp_kebutuhan'] = $id_comp_kebutuhan;
							$record['id_unit'] = $id_unit;
							$record['periode_label'] = $i;
							$record['tahun'] = $tahun;
							$record['id_status_penilaian'] = $v['id_status_penilaian'];
							$record['keterangan'] = $v['keterangan'];

							$id_comp_penilaian = $this->conn->GetOne("select id_comp_penilaian from comp_penilaian 
							where deleted_date is null and id_comp_kebutuhan = " . $this->conn->escape($id_comp_kebutuhan) . " 
							and id_unit = " . $this->conn->escape($id_unit) . " 
							and periode_label = " . $this->conn->escape($i));

							if (!$id_comp_penilaian) {
								$response = $this->model->Insert($record);
								$ret = $response['success'];
								$id_comp_penilaian = $response['data']['id_comp_penilaian'];
							} else {
								$response = $this->model->Update($record, "id_comp_penilaian = $id_comp_penilaian");
								$ret = $response['success'];
							}
						}
					}
				}
			}
		}

		$this->data['rows'] = $this->conn->GetArray("select a.*,
		b.nomor_dokumen, b.nama as nama_dokumen, a.nama as nama_kebutuhan, d.konversi_bulan, d.nama nama_periode
		from comp_kebutuhan a 
		join dokumen b on a.id_dokumen = b.id_dokumen 
		join mt_interval d on a.id_interval = d.id_interval
		where a.deleted_date is null and b.id_dokumen = " . $this->conn->escape($id_dokumen) . "
		order by a.id_dokumen, a.id_comp_kebutuhan");

		$this->data['mtsdmunitarr'] = array('' => 'Unit') + $this->conn->GetList("select table_code as idkey, table_desc val 
		from mt_sdm_unit a
		where a.deleted_date is null and exists(select 1 from dokumen_unit b 
		where b.deleted_date is null and b.id_dokumen = " . $this->conn->escape($id_dokumen) . " and a.table_code = b.id_unit) ");

		$rows = $this->conn->GetArray("select * 
		from comp_penilaian a 
		where a.deleted_date is null and tahun = " . $this->conn->escape($tahun) . " 
		and exists (select 1 from comp_kebutuhan b 
		where a.deleted_date is null and a.id_comp_kebutuhan = b.id_comp_kebutuhan 
		and b.id_dokumen = " . $this->conn->escape($id_dokumen) . ")
		and id_unit = " . $this->conn->escape($id_unit));

		$this->data['rowspenilaian'] = array();
		foreach ($rows as $r) {
			$rws = $this->conn->GetArray("select *
			from {$this->modelfile->table}
			where deleted_date is null and {$this->model->pk} = " . $this->conn->escape($r['id_comp_penilaian']));

			$files = array();
			foreach ($rws as $r1) {
				$files['id'][] = $r1[$this->modelfile->pk];
				$files['name'][] = $r1['client_name'];
			}

			$r['files'] = $files;
			$this->data['rowspenilaian'][$r['id_comp_kebutuhan']][$r['periode_label']] = $r;
		}

		$row = $this->conn->GetRow("select 
		count(id_status_penilaian) as jumlah,
		sum(id_status_penilaian) as total
		from comp_penilaian a 
		where a.deleted_date is null and tahun = " . $this->conn->escape($tahun) . " 
		and exists (select 1 from comp_kebutuhan b 
		where b.deleted_date is null and a.id_comp_kebutuhan = b.id_comp_kebutuhan 
		and b.id_dokumen = " . $this->conn->escape($id_dokumen) . ")
		and id_unit = " . $this->conn->escape($id_unit));

		if ($row['jumlah'])
			$this->data['score'] = 100 * (($row['total'] - $row['jumlah']) / ($row['jumlah'] * 2));

		$this->View($this->viewdetail);
	}



	public function printdetail($id = null)
	{
		$this->data['page_title'] = "Detail Penilaian";
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";
		$this->data['no_header'] = true;

		if (!$this->access_role['view_all']) {
			$id_unit = $_SESSION[SESSION_APP]['id_unit'];
		}

		$tgl_efektif = $this->data['tgl_efektif'];
		list($tahun, $bln, $tgl) = explode("-", $tgl_efektif);

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['id_dokumen_filter'])
			$id_dokumen = $_SESSION[SESSION_APP][$this->page_ctrl]['id_dokumen_filter'];

		$_SESSION[SESSION_APP][$this->page_ctrl]['id_dokumen_filter'] = $id_dokumen;

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'])
			$tahun = $_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'];

		$_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'] = $tahun;

		if (!$this->Access("view_all", "main"))
			$id_unit = $_SESSION[SESSION_APP]['id_unit'];
		else
			$id_unit = $_SESSION[SESSION_APP][$this->page_ctrl]['id_unit_filter'];

		$id_dokumen = $_SESSION[SESSION_APP][$this->page_ctrl]['id_dokumen_filter'];

		$this->data['id_dokumen_filter'] = $id_dokumen;
		$this->data['tahun_filter'] = $tahun;
		$this->data['id_unit_filter'] = $id_unit;

		if ($this->post['act'] == 'save') {
			$ret = true;
			foreach ($this->post['penilaian'] as $id_comp_kebutuhan => $rs) {
				foreach ($rs as $id_unit => $rs1) {
					foreach ($rs1 as $i => $rs2) {
						foreach ($rs2 as $tahun => $v) {
							if (!$ret)
								break;

							$record = array();
							$record['id_comp_kebutuhan'] = $id_comp_kebutuhan;
							$record['id_unit'] = $id_unit;
							$record['periode_label'] = $i;
							$record['tahun'] = $tahun;
							$record['id_status_penilaian'] = $v['id_status_penilaian'];
							$record['keterangan'] = $v['keterangan'];

							$id_comp_penilaian = $this->conn->GetOne("select id_comp_penilaian from comp_penilaian 
							where deleted_date is null and id_comp_kebutuhan = " . $this->conn->escape($id_comp_kebutuhan) . " 
							and id_unit = " . $this->conn->escape($id_unit) . " 
							and periode_label = " . $this->conn->escape($i));

							if (!$id_comp_penilaian) {
								$response = $this->model->Insert($record);
								$ret = $response['success'];
								$id_comp_penilaian = $response['data']['id_comp_penilaian'];
							} else {
								$response = $this->model->Update($record, "id_comp_penilaian = $id_comp_penilaian");
								$ret = $response['success'];
							}
						}
					}
				}
			}
		}

		$this->data['rows'] = $this->conn->GetArray("select a.*,
		b.nomor_dokumen, b.nama as nama_dokumen, a.nama as nama_kebutuhan, d.konversi_bulan, d.nama nama_periode
		from comp_kebutuhan a 
		join dokumen b on a.id_dokumen = b.id_dokumen  and a.deleted_date is null
		join mt_interval d on a.id_interval = d.id_interval
		where b.id_dokumen = " . $this->conn->escape($id_dokumen) . "
		order by a.id_dokumen, a.id_comp_kebutuhan");

		$this->data['mtsdmunitarr'] = array('' => 'Unit') + $this->conn->GetList("select table_code as idkey, table_desc val 
		from mt_sdm_unit a
		where a.deleted_date is null and exists(select 1 from dokumen_unit b 
		where b.deleted_date is null and b.id_dokumen = " . $this->conn->escape($id_dokumen) . " and a.table_code = b.id_unit) ");

		$rows = $this->conn->GetArray("select * 
		from comp_penilaian a 
		where a.deleted_date is null and tahun = " . $this->conn->escape($tahun) . " 
		and exists (select 1 from comp_kebutuhan b 
		where b.deleted_date is null and a.id_comp_kebutuhan = b.id_comp_kebutuhan 
		and b.id_dokumen = " . $this->conn->escape($id_dokumen) . ")
		and id_unit = " . $this->conn->escape($id_unit));

		$this->data['rowspenilaian'] = array();
		foreach ($rows as $r) {
			$rws = $this->conn->GetArray("select *
			from {$this->modelfile->table}
			where deleted_date is null and {$this->model->pk} = " . $this->conn->escape($r['id_comp_penilaian']));

			$files = array();
			foreach ($rws as $r1) {
				$files['id'][] = $r1[$this->modelfile->pk];
				$files['name'][] = $r1['client_name'];
			}

			$r['files'] = $files;
			$this->data['rowspenilaian'][$r['id_comp_kebutuhan']][$r['periode_label']] = $r;
		}

		$row = $this->conn->GetRow("select 
		count(id_status_penilaian) as jumlah,
		sum(id_status_penilaian) as total
		from comp_penilaian a 
		where a.deleted_date is null and tahun = " . $this->conn->escape($tahun) . " 
		and exists (select 1 from comp_kebutuhan b 
		where b.deleted_date is null and a.id_comp_kebutuhan = b.id_comp_kebutuhan 
		and b.id_dokumen = " . $this->conn->escape($id_dokumen) . ")
		and id_unit = " . $this->conn->escape($id_unit));

		if ($row['jumlah'])
			$this->data['score'] = 100 * (($row['total'] - $row['jumlah']) / ($row['jumlah'] * 2));

		$this->View("panelbackend/comp_penilaiandetailprint");
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'id_comp_kebutuhan',
				'label' => 'Comp Kebutuhan',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['compkebutuhanarr'],
			),
			array(
				'name' => 'periode_label',
				'label' => 'Periode Label',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'id_status_penilaian',
				'label' => 'Status Penilaian',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['mtstatuspenilaianarr'],
			),
			array(
				'name' => 'keterangan',
				'label' => 'Keterangan',
				'width' => "auto",
				'type' => "text",
			),
			array(
				'name' => 'id_jabatan_pereview',
				'label' => 'Jabatan Pereview',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['mtsdmjabatanarr'],
			),
			array(
				'name' => 'nama_jabatan_pereview',
				'label' => 'Nama Jabatan Pereview',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'id_pereview',
				'label' => 'Pereview',
				'width' => "auto",
				'type' => "number",
			),
			array(
				'name' => 'nama_pereview',
				'label' => 'Nama Pereview',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'id_unit',
				'label' => 'Unit',
				'width' => "auto",
				'type' => "number",
			),
			array(
				'name' => 'tahun',
				'label' => 'Tahun',
				'width' => "auto",
				'type' => "varchar",
			),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'id_comp_kebutuhan' => $this->post['id_comp_kebutuhan'],
			'periode_label' => $this->post['periode_label'],
			'id_status_penilaian' => $this->post['id_status_penilaian'],
			'keterangan' => $this->post['keterangan'],
			'id_jabatan_pereview' => $this->post['id_jabatan_pereview'],
			'nama_jabatan_pereview' => $this->post['nama_jabatan_pereview'],
			'id_pereview' => $this->post['id_pereview'],
			'nama_pereview' => $this->post['nama_pereview'],
			'id_unit' => $this->post['id_unit'],
			'tahun' => $this->post['tahun'],
		);
	}

	protected function Rules()
	{
		return array(
			"id_comp_kebutuhan" => array(
				'field' => 'id_comp_kebutuhan',
				'label' => 'Comp Kebutuhan',
				'rules' => "in_list[" . implode(",", array_keys($this->data['compkebutuhanarr'])) . "]|max_length[10]",
			),
			"periode_label" => array(
				'field' => 'periode_label',
				'label' => 'Periode Label',
				'rules' => "max_length[45]",
			),
			"id_status_penilaian" => array(
				'field' => 'id_status_penilaian',
				'label' => 'Status Penilaian',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtstatuspenilaianarr'])) . "]|max_length[10]",
			),
			"keterangan" => array(
				'field' => 'keterangan',
				'label' => 'Keterangan',
				'rules' => "",
			),
			"id_jabatan_pereview" => array(
				'field' => 'id_jabatan_pereview',
				'label' => 'Jabatan Pereview',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtsdmjabatanarr'])) . "]|max_length[10]",
			),
			"nama_jabatan_pereview" => array(
				'field' => 'nama_jabatan_pereview',
				'label' => 'Nama Jabatan Pereview',
				'rules' => "max_length[200]",
			),
			"id_pereview" => array(
				'field' => 'id_pereview',
				'label' => 'Pereview',
				'rules' => "integer|max_length[10]",
			),
			"nama_pereview" => array(
				'field' => 'nama_pereview',
				'label' => 'Nama Pereview',
				'rules' => "max_length[200]",
			),
			"id_unit" => array(
				'field' => 'id_unit',
				'label' => 'Unit',
				'rules' => "integer|max_length[10]",
			),
			"tahun" => array(
				'field' => 'tahun',
				'label' => 'Tahun',
				'rules' => "max_length[4]",
			),
		);
	}

	protected function _uploadFiles($jenis_file = null, $id = null)
	{

		$name = $_FILES[$jenis_file]['name'];

		$this->data['configfile']['file_name'] = $jenis_file . time() . $name;

		$this->load->library('upload', $this->data['configfile']);

		if (!$this->upload->do_upload($jenis_file)) {
			$return = array('error' => "File $name gagal upload, " . strtolower(str_replace(array("<p>", "</p>"), "", $this->upload->display_errors())));
		} else {
			$upload_data = $this->upload->data();
			$jenis_file = str_replace("upload", "", $jenis_file);
			list($jenis_file, $id_comp_kebutuhan, $id_unit, $periode_label, $tahun) = explode("_", $jenis_file);

			if (!$this->Access("view_all", "main"))
				$id_unit = $_SESSION[SESSION_APP]['id_unit'];
			else
				$id_unit = $_SESSION[SESSION_APP][$this->page_ctrl]['id_unit_filter'];

			$id_comp_penilaian = $this->conn->GetOne("select id_comp_penilaian from comp_penilaian 
			where deleted_date is null and id_comp_kebutuhan = " . $this->conn->escape($id_comp_kebutuhan) . " 
			and id_unit = " . $this->conn->escape($id_unit) . " 
			and periode_label = " . $this->conn->escape($periode_label));

			if (!$id_comp_penilaian) {
				$record = array();
				$record['id_comp_kebutuhan'] = $id_comp_kebutuhan;
				$record['periode_label'] = $periode_label;
				$record['id_unit'] = $id_unit;
				$record['tahun'] = $tahun;
				$response = $this->model->Insert($record);
				$id_comp_penilaian = $response['data']['id_comp_penilaian'];
			}

			$record = array();
			$record['client_name'] = $upload_data['client_name'];
			$record['id_comp_penilaian'] = $id_comp_penilaian;
			$record['file_name'] = $upload_data['file_name'];
			$record['file_type'] = $upload_data['file_type'];
			$record['file_size'] = $upload_data['file_size'];
			$record['jenis_file'] = $record['jenis'] = $jenis_file;

			$ret = $this->modelfile->Insert($record);
			if ($ret['success']) {
				$return = array('file' => array("id" => $ret['data'][$this->modelfile->pk], "name" => $upload_data['client_name']));
			} else {
				unlink($upload_data['full_path']);
				$return = array('errors' => "File $name gagal upload");
			}
		}

		return $return;
	}
}
