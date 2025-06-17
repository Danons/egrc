<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Spi_notulen extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/spi_notulenlist";
		$this->viewdetail = "panelbackend/spi_notulendetail";
		$this->viewprintdetail = "panelbackend/spi_notulendetailprint";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Notulen';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Notulen';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Notulen';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Notulen';
		}

		$this->load->model("Spi_notulenModel", "model");
		$this->load->model("Mt_sdm_jabatanModel", "mtjabatan");
		$this->data['jabatanarr'] = ['' => ''] + $this->mtjabatan->GetCombo();


		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'tinymce', 'select2'
		);

		$this->access_role['print_detail'] = $this->data['acces_role']['print_detail'] = 1;
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'nama_rapat',
				'label' => 'Nama Rapat',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'tanggal_rapat',
				'label' => 'Tanggal Rapat',
				'width' => "auto",
				'type' => "date",
			),
			array(
				'name' => 'waktu_rapat',
				'label' => 'Waktu Rapat',
				'width' => "auto",
				'type' => "varchar",
			),
			// array(
			// 	'name' => 'acara',
			// 	'label' => 'Acara',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// ),
			array(
				'name' => 'pimpinan_rapat',
				'label' => 'Pimpinan Rapat',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'notulis',
				'label' => 'Notulis',
				'width' => "auto",
				'type' => "varchar",
			),
			// array(
			// 	'name' => 'id_peserta',
			// 	'label' => 'Peserta Rapat',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// ),
			array(
				'name' => 'kegiatan_rapat',
				'label' => 'Kegiatan Rapat',
				'width' => "auto",
				'type' => "varchar",
			),
			// array(
			// 	'name' => 'pembukaan',
			// 	'label' => 'Pembukaan',
			// 	'width' => "auto",
			// 	'type' => "longtext",
			// ),
			// array(
			// 	'name' => 'pembahasan',
			// 	'label' => 'Pembahasan',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// ),
			// array(
			// 	'name' => 'penutup',
			// 	'label' => 'Penutup',
			// 	'width' => "auto",
			// 	'type' => "longtext",
			// ),
			// array(
			// 	'name' => 'kesimpulan',
			// 	'label' => 'Kesimpulan',
			// 	'width' => "auto",
			// 	'type' => "longtext",
			// ),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'nama_rapat' => $this->post['nama_rapat'],
			'tanggal_rapat' => $this->post['tanggal_rapat'],
			'waktu_rapat' => $this->post['waktu_rapat'],
			// 'acara' => $this->post['acara'],
			'pimpinan_rapat' => $this->post['pimpinan_rapat'],
			'notulis' => $this->post['notulis'],
			// 'id_peserta_rapat' => $this->post['id_peserta_rapat'],
			'kegiatan_rapat' => $this->post['kegiatan_rapat'],
			'pembukaan' => $this->post['pembukaan'],
			'pembahasan' => $this->post['pembahasan'],
			'penutup' => $this->post['penutup'],
			'kesimpulan' => $this->post['kesimpulan'],
			'id_jabatan_notulis' => $this->post['id_jabatan_notulis'],
		);
	}

	protected function Rules()
	{
		return array(
			"nama_rapat" => array(
				'field' => 'nama_rapat',
				'label' => 'Nama Rapat',
				'rules' => "max_length[200]",
			),
			"waktu_rapat" => array(
				'field' => 'waktu_rapat',
				'label' => 'Waktu Rapat',
				'rules' => "max_length[50]",
			),
			"acara" => array(
				'field' => 'acara',
				'label' => 'Acara',
				'rules' => "max_length[200]",
			),
			"pimpinan_rapat" => array(
				'field' => 'id_pimpinan_rapat',
				'label' => 'Pimpinan Rapat',
				'rules' => "max_length[200]",
			),
			"notulis" => array(
				'field' => 'notulis',
				'label' => 'Notulis',
				'rules' => "max_length[200]",
			),
			"id_peserta" => array(
				'field' => 'id_peserta',
				'label' => 'Peserta Rapat',
				'rules' => "max_length[200]",
			),
			"kegiatan_rapat" => array(
				'field' => 'kegiatan_rapat',
				'label' => 'Kegiatan Rapat',
				'rules' => "max_length[200]",
			),
			"pembukaan" => array(
				'field' => 'pembukaan',
				'label' => 'Pembukaan',

			),
			"pembahasan" => array(
				'field' => 'pembahasan',
				'label' => 'Pembahasan',

			),
			"penutup" => array(
				'field' => 'penutup',
				'label' => 'Penutup',

			),
			"kesimpulan" => array(
				'field' => 'kesimpulan',
				'label' => 'Kesimpulan',

			),
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

		// dpr($this->data['list']['rows'], 1);
		if ($this->data['list']['rows']) {

			foreach ($this->data['list']['rows'] as $row) {
				$this->data['acaraarr'][$row['id_notulen']] = $this->conn->GetList('select id_acara as idkey, acara as val from spi_notulen_acara where deleted_date is null and id_notulen = ' . $this->conn->escape($row['id_notulen']));
				$this->data['pesertaarr'][$row['id_notulen']] = $this->conn->GetList('select id_peserta as idkey, nama as val from spi_notulen_peserta where deleted_date is null and id_notulen = ' . $this->conn->escape($row['id_notulen']));
			}
			// dpr($this->data['acaraarr'], 1);


			foreach ($this->data['acaraarr'] as $key => $a) {
				$no = 0;
				foreach ($a as $val) {
					$no++;
					$this->data['resultAcaraArr'][$key] .= $val . '<br/>';
				}
			}

			if ($this->data['pesertaarr']) {

				foreach ($this->data['pesertaarr'] as $key => $a) {
					foreach ($a as $val) {
						$this->data['resultPesertaArr'][$key] .= $val . '<br/>';
					}
				}
			}
		}

		// dpr($this->data['resultPesertaArr'], 1);
		$this->View($this->viewlist);
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
		// $this->data['row']['id_pembahasan'] = $this->conn->GetList('select id_pembahasan as idkey, pembahasan as val from pembahasan_rapat where id_notulen = ' . $this->conn->escape($id));

		// dpr($this->data['row'], 1);

		$this->_onDetail($id);

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {
			// dpr($this->data['row'], 1);

			// if ($this->data['row']['id_peserta']) {
			// 	$id_peserta_saat_ini = $this->data['row']['id_notulen'];
			// 	$id_child = $this->data['row']['risikobawah'];
			// 	foreach ($id_child as $ic) {
			// 		// dpr($ic, 1);
			// 		$rec = array(
			// 			'id_risiko_parent' => $id_risiko_saatini,
			// 		);

			// 		$ret = $this->model->Update($rec, 'id_risiko = ' . $ic);
			// 	}
			// }

			$this->_isValid($record, true);

			$this->_beforeEdit($record, $id);

			$this->_setLogRecord($record, $id);

			$this->model->conn->StartTrans();
			if (trim($this->data['row'][$this->pk]) == trim($id) && trim($id)) {

				$return = $this->_beforeUpdate($record, $id);

				if ($return) {

					$return = $this->model->Update($record, "$this->pk = " . $this->conn->qstr($id));

					foreach ($this->data['row']['id_peserta'] as $key => $val) {
						$rec = array(
							'id_notulen' => $id,
							'nama' => $val
						);
						$sql = $this->conn->updateSQL('spi_notulen_peserta', $rec, "id_peserta =" . $this->conn->qstr($key));
						if ($sql) {
							$this->conn->execute($sql);
						}
					}
					foreach ($this->data['row']['acara'] as $key => $val) {
						$rec = array(
							'id_notulen' => $id,
							'acara' => $val
						);
						$sql = $this->conn->updateSQL('spi_notulen_acara', $rec, "id_acara =" . $this->conn->qstr($key));
						if ($sql) {
							$this->conn->execute($sql);
						}
					}
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
					foreach ($this->data['row']['acara'] as $r) {
						$rec = array(
							'id_notulen' => $id,
							'acara' => $r
						);
						$sql = $this->conn->insertSQL('spi_notulen_acara', $rec);
						if ($sql) {
							$this->conn->execute($sql);
						}
					}
					// dpr($this->data['row']['id_peserta'], 1);
					foreach ($this->data['row']['id_peserta'] as $r) {
						$rec = array(
							'id_notulen' => $id,
							'nama' => $r
						);
						$sql = $this->conn->insertSQL('spi_notulen_peserta', $rec);
						if ($sql) {
							$this->conn->execute($sql);
						}
					}


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
				where deleted_date is null and{$this->model->pk} = " . $this->conn->escape($id));
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

			if (!$this->data['row']['acara']) {
				$this->data['row']['acara'] = $this->conn->GetList('select id_acara as idkey, acara as val from spi_notulen_acara where deleted_date is null and id_notulen = ' . $this->conn->escape($id));
			}

			if (!$this->data['row']['id_peserta']) {
				$this->data['row']['id_peserta'] = $this->conn->GetList('select id_peserta as idkey, nama as val from spi_notulen_peserta where deleted_date is null and id_notulen = ' . $this->conn->escape($id));
			}
		}
	}

	public function printdetail($id, $idu = null)
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout5";
		$this->data['no_header'] = true;

		$this->data['row'] = $this->model->GetByPk($id);
		// dpr($this->data['row'], 1);

		$this->_getDetailPrint($id);

		if (!$this->data['row'])
			$this->NoData();
		// dpr($this->data['row'], 1);
		$this->data['row']['acara'] = $this->conn->GetList('select id_acara as idkey, acara as val from spi_notulen_acara where deleted_date is null and id_notulen = ' . $this->conn->escape($id));
		$this->data['row']['id_peserta'] = $this->conn->GetList('select id_peserta as idkey, nama as val from spi_notulen_peserta where deleted_date is null and id_notulen = ' . $this->conn->escape($id));
		// $this->data['row']['id_pembahasan'] = $this->conn->GetList('select id_pembahasan as idkey, pembahasan as val from pembahasan_rapat where id_notulen = ' . $this->conn->escape($id));

		$no = 0;
		foreach ($this->data['row']['acara'] as $val) {
			$no++;
			$this->data['resultAcaraArr'] .= " " . $no . ". " . $val . "<br/>";
		}
		$no = 0;
		foreach ($this->data['row']['id_peserta'] as $val) {
			$no++;
			$this->data['resultPesertaArr'] .= " " . $no . ". " . $val . "<br/>";
		}

		$hari = date("D", strtotime($this->data['row']['tanggal_rapat']));


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


		$this->data['hari_ini'] = $hari_ini;

		// foreach ($this->data['pesertaarr'] as $key => $a) {
		// 	foreach ($a as $val) {
		// 		$this->data['resultPesertaArr'][$key] .= $val . '<br/>';
		// 	}
		// }
		$this->data['jabatannotulis'] = $this->conn->GetRow('SELECT id_notulen,nama,id_jabatan FROM spi_notulen n LEFT JOIN mt_sdm_jabatan msj ON n.id_jabatan_notulis = msj.id_jabatan WHERE n.deleted_date is null and id_notulen =' . $id);
		$this->data['pagetitle'] = 'test';

		// dpr($this->data['header']);
		$this->View($this->viewprintdetail);
	}
}
