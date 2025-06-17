<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Spi_rekap_consulting extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/spi_rekap_consultinglist";
		$this->viewdetail = "panelbackend/spi_rekap_consultingdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Konsultasi';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Konsultasi';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Konsultasi';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Konsultasi';
		}

		$this->load->model("Spi_rekap_consultingModel", "model");

		$this->load->model("Mt_sdm_unitModel", "unitModel");
		$this->data['unitKerjaArr'] = $this->unitModel->GetCombo();

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'select2'
		);

		$this->access_role['list_print'] = 1;
		$this->access_role['print_detail'] = 1;
	}

	protected function Header()
	{
		return array(

			array(
				'name' => 'tanggal',
				'label' => 'Tanggal',
				'width' => "auto",
				'type' => "datetime",
			),

			array(
				'name' => 'id_unit_kerja',
				'label' => 'Unit Kerja / Jabatan',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['unitKerjaArr'],
			),
			array(
				'name' => 'contact_person',
				'label' => 'Contact Person',
				'width' => "auto",
				'type' => "varchar",
			),

			// array(
			// 	'name' => 'pendapat_spi',
			// 	'label' => 'Pendapat SPI',
			// 	'width' => "auto",
			// 	'type' => "longtext",
			// ),
			array(
				'name' => 'pengawas',
				'label' => 'Pengawas',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'rating',
				'label' => 'Rating',
				'type' => "varchar",
			),
		);
	}

	protected function Record($id = null)
	{
		// dpr($tahun);
		return array(
			'tanggal' => $this->post['tanggal'],
			'waktu_mulai' => $this->post['waktu_mulai'],
			'waktu_selesai' => $this->post['waktu_selesai'],
			'id_unit_kerja' => $this->post['id_unit_kerja'],
			'contact_person' => $this->post['contact_person'],
			'nomor_telpon' => $this->post['nomor_telpon'],
			'uraian_layanan' => $this->post['uraian_layanan'],
			'pendapat_spi' => $this->post['pendapat_spi'],
			'dokumen_disampaikan' => $this->post['dokumen_disampaikan'],
			'pengawas' => $this->post['pengawas'],
			'keterangan' => $this->post['keterangan'],
		);
	}

	protected function Rules()
	{
		return array(
			"id_unit_kerja" => array(
				'field' => 'id_unit_kerja',
				'label' => 'Jabatan',
				'rules' => "max_length[100]",
			),
			"contact_person" => array(
				'field' => 'contact_person',
				'label' => 'Contact Person',
				'rules' => "max_length[100]",
			),
			"nomor_telpon" => array(
				'field' => 'nomor_telpon',
				'label' => 'Nomor Telpon',
				'rules' => "integer",
			),
			"uraian_layanan" => array(
				'field' => 'uraian_layanan',
				'label' => 'Uraian Layanan',
				'rules' => "",
			),
			"pendapat_spi" => array(
				'field' => 'pendapat_spi',
				'label' => 'Pendapat SPI',
				'rules' => "",
			),
			"dokumen_disampaikan" => array(
				'field' => 'dokumen_disampaikan',
				'label' => 'Dokumen Yang Disampaikan',
				'rules' => "max_length[100]",
			),
			"pengawas" => array(
				'field' => 'pengawas',
				'label' => 'Pengawas',
				'rules' => "max_length[100]",
			),
			"keterangan" => array(
				'field' => 'keterangan',
				'label' => 'Keterangan',
				'rules' => "",
			),
		);
	}

	public function Index($page = 0)
	{
		$this->data['header'] = $this->Header();

		$tgl_efektif = date('Y-m-d');
		list($tahun, $bln, $tgl) = explode("-", $tgl_efektif);

		if (!$this->post['tahun_filter']) {
			$this->post['tahun_filter'] = $tahun;
		}

		if ($tahun >= $this->post['tahun_filter'] || $this->post['tahun_filter'])
			$_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'] = $this->post['tahun_filter'];

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'])
			$tahun = $_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'];

		$this->_setFilter(' YEAR(tanggal) = ' . $this->conn->escape($tahun));
		$this->data['list'] = $this->_getList($page);

		$this->data['page'] = $page;

		$this->data['jabatan'] = $this->conn->GetList('select id_jabatan as idkey,nama as val from mt_sdm_jabatan where deleted_date is null');
		$this->data['user'] = $this->conn->GetList('select user_id as idkey,name as val from public_sys_user where deleted_date is null');



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

		foreach ($this->data['list']['rows'] as $r) {

			$hari = date("D", $r['tanggal']);


			switch ($hari) {
				case 'Sun':
					$hari_ini = "Minggu";
					break;

				case 'Mon':
					$hari_ini = "Senin";
					break;

				case 'Tue':
					$hari_ini = "Selasa";
					break;

				case 'Wed':
					$hari_ini = "Rabu";
					break;

				case 'Thu':
					$hari_ini = "Kamis";
					break;

				case 'Fri':
					$hari_ini = "Jumat";
					break;

				case 'Sat':
					$hari_ini = "Sabtu";
					break;

				default:
					$hari_ini = "Tidak di ketahui";
					break;
			}

			$this->data['hari_ini'][$r['id_konsultasi']] = $hari_ini;
		}

		$this->data['page_title'] .= " " . UI::createTextNumber("tahun_filter", $tahun, 4, 4, true, "filter-title form-control", "style='width:70px; display:inline' onchange='goSubmit(\"set_value\")'");
		// dpr($this->post['tahun_filter']);
		$this->View($this->viewlist);
	}

	public function go_print()
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";
		$this->data['no_header'] = true;


		$header = array(

			array(
				'name' => 'tanggal',
				'label' => 'Tanggal',
				'width' => "auto",
				'type' => "date",
			),


			array(
				'name' => 'jabatan',
				'label' => 'Unit Kerja / Jabatan',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'contact_person',
				'label' => 'Contact Person',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'uraian_layanan',
				'label' => 'Uraian Layanan',
				'width' => "auto",
				'type' => "longtext",
			),
			array(
				'name' => 'pendapat_spi',
				'label' => 'Pendapat SPI',
				'width' => "auto",
				'type' => "longtext",
			),
			array(
				'name' => 'pengawas',
				'label' => 'Pengawas',
				'width' => "auto",
				'type' => "varchar",
			),
		);

		$this->data['header'] = $header;
		$tahun = $_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'];
		$this->data['list']['rows'] = $this->conn->GetArray('select * from spi_rekap_consulting where deleted_date is null and YEAR(tanggal) = ' . $this->conn->escape($tahun));
		$this->data['page_title'] = "Daftar Konsultasi" . '&nbsp;' . $tahun;
		$this->View($this->viewprint);
	}
}
