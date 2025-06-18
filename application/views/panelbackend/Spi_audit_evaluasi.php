<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Spi_audit_evaluasi extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/spi_audit_evaluasilist";
		$this->viewdetail = "panelbackend/spi_audit_evaluasidetail";
		$this->viewprintdetail = "panelbackend/spi_audit_evaluasidetailprint";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Audit Evaluasi';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Audit Evaluasi';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Audit Evaluasi';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Audit Evaluasi';
		}

		$this->load->model("Spi_audit_evaluasiModel", "model");

		$this->access_role['print_detail'] = $this->data['acces_role']['print_detail'] = 1;

		$manajerarr = $this->conn->GetList("SELECT user_id AS idkey, NAME AS val FROM public_sys_user WHERE id_jabatan = 3267");
		$this->data['manajerarr'] = ['' => ''] + $manajerarr;


		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'tinymce', 'select2'
		);
	}

	public function Index($page = 0)
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

		$this->View($this->viewlist);
	}


	protected function Header()
	{
		return array(
			array(
				'name' => 'tanggal',
				'label' => 'Tanggal',
				'width' => "auto",
				'type' => "date",
			),
			array(
				'name' => 'nomor',
				'label' => 'Nomor',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'lampiran',
				'label' => 'Lampiran',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'hal',
				'label' => 'HAL',
				'width' => "auto",
				'type' => "varchar",
			),
			// array(
			// 	'name' => 'simpulan',
			// 	'label' => 'Simpulan',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// ),
			// array(
			// 	'name' => 'saran',
			// 	'label' => 'saran',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// ),
			// array(
			// 	'name' => 'dasar_tugas',
			// 	'label' => 'Dasar Tugas',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// ),
			// array(
			// 	'name' => 'dasar_evaluasi',
			// 	'label' => 'Dasar Evaluasi',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// ),
			// array(
			// 	'name' => 'cakupan_evaluasi',
			// 	'label' => 'Cakupan Evaluasi',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// ),
			// array(
			// 	'name' => 'informasi_umum',
			// 	'label' => 'Informasi Umum',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// ),
			// array(
			// 	'name' => 'hasil_evaluasi',
			// 	'label' => 'Hasil Evaluasi',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// ),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'tanggal' => $this->post['tanggal'],
			'nomor' => $this->post['nomor'],
			'lampiran' => $this->post['lampiran'],
			'hal' => $this->post['hal'],
			'simpulan' => $this->post['simpulan'],
			'saran' => $this->post['saran'],
			'dasar_tugas' => $this->post['dasar_tugas'],
			'dasar_evaluasi' => $this->post['dasar_evaluasi'],
			'cakupan_evaluasi' => $this->post['cakupan_evaluasi'],
			'informasi_umum' => $this->post['informasi_umum'],
			'hasil_evaluasi' => $this->post['hasil_evaluasi'],
			'id_manajer' => $this->post['id_manajer'],
		);
	}

	protected function Rules()
	{
		return array(
			"nomor" => array(
				'field' => 'nomor',
				'label' => 'Nomor',
				'rules' => "max_length[50]",
			),
			"lampiran" => array(
				'field' => 'lampiran',
				'label' => 'Lampiran',
				'rules' => "max_length[200]",
			),
			"hal" => array(
				'field' => 'hal',
				'label' => 'HAL',
				'rules' => "max_length[200]",
			),
			"simpulan" => array(
				'field' => 'simpulan',
				'label' => 'Simpulan',
				'rules' => "",
			),
			"saran" => array(
				'field' => 'saran',
				'label' => 'saran',
				'rules' => "",
			),
			"dasar_tugas" => array(
				'field' => 'dasar_tugas',
				'label' => 'Dasar Tugas',
				'rules' => "",
			),
			"dasar_evaluasi" => array(
				'field' => 'dasar_evaluasi',
				'label' => 'Dasar Evaluasi',
				'rules' => "",
			),
			"cakupan_evaluasi" => array(
				'field' => 'cakupan_evaluasi',
				'label' => 'Cakupan Evaluasi',
				'rules' => "",
			),
			"informasi_umum" => array(
				'field' => 'informasi_umum',
				'label' => 'Informasi Umum',
				'rules' => "",
			),
			"hasil_evaluasi" => array(
				'field' => 'hasil_evaluasi',
				'label' => 'Hasil Evaluasi',
				'rules' => "",
			),
		);
	}


	public function Edit($id = null)
	{

		$id = urldecode($id);
		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		// dpr($this->data['mode'], 1);

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

		// gausah di foreach langsung ambil id saja

		// $this->data['row']['acara'] = $this->conn->GetList('select id_acara as idkey, acara as val from id_acara where id_notulen = ' . $this->conn->escape($id));
		// $this->data['row']['id_peserta'] = $this->conn->GetList('select id_peserta as idkey, nama as val from peserta_rapat where id_notulen = ' . $this->conn->escape($id));
		// $this->data['row']['id_pembahasan'] = $this->conn->GetList('select id_peserta as idkey,nama as val from peserta_rapat where id_notulen' . $this->conn->escape($id));
		// dpr($this->data['row']['id_pembahasan']);
		// dpr($this->data['row'], 1);

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
					// foreach ($this->data['row']['acara'] as $r) {
					// 	$rec = array(
					// 		'id_notulen' => $id,
					// 		'acara' => $r
					// 	);
					// 	$sql = $this->conn->insertSQL('id_acara', $rec);
					// 	if ($sql) {
					// 		$this->conn->execute($sql);
					// 	}
					// }
					// // dpr($this->data['row']['id_peserta'], 1);
					// foreach ($this->data['row']['id_peserta'] as $r) {
					// 	$rec = array(
					// 		'id_notulen' => $id,
					// 		'nama' => $r
					// 	);
					// 	$sql = $this->conn->insertSQL('peserta_rapat', $rec);
					// 	if ($sql) {
					// 		$this->conn->execute($sql);
					// 	}
					// }


					// dpr($id, 1);
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

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	protected function _afterDetail($id)
	{
		// $this->data['editedheader1'] = $this->data['edited'];
		// dpr('test', 1);

		if ($this->modelfile) {
			if (!$this->data['row']['files']['id'] && $id) {
				$rows = $this->conn->GetArray("select *
				from {$this->modelfile->table}
				where {$this->model->pk} = " . $this->conn->escape($id));
				foreach ($rows as $r) {
					$this->data['row']['files']['id'][] = $r[$this->modelfile->pk];
					$this->data['row']['files']['name'][] = $r['client_name'];
				}
			}
		}
		if ($id) {
			$this->data['mode'] = 'detail';
		}
		if ($this->data['mode'] == 'detail') {

			// if (!$this->data['row']['acara']) {
			// 	$this->data['row']['acara'] = $this->conn->GetList('select id_acara as idkey, acara as val from id_acara where id_notulen = ' . $this->conn->escape($id));
			// }

			// if (!$this->data['row']['id_peserta']) {
			// 	$this->data['row']['id_peserta'] = $this->conn->GetList('select id_peserta as idkey, nama as val from peserta_rapat where id_notulen = ' . $this->conn->escape($id));
			// }
		}
	}


	public function printdetail($id, $idu = null)
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout5";
		$this->data['no_header'] = true;

		$this->data['row'] = $this->model->GetByPk($id);

		$this->_getDetailPrint($id);

		if (!$this->data['row'])
			$this->NoData();
		$hari = date("D", $this->data['row']['tanggal_rapat']);


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

		$this->data['row']['nama_manajer'] = $this->conn->GetRow("select name from public_sys_user where user_id =" . $this->conn->escape($this->data['row']['id_manajer']));



		$this->data['hari_ini'] = $hari_ini;

		$this->View($this->viewprintdetail);
	}
}
