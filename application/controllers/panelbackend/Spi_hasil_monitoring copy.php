<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Spi_hasil_monitoring extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/spi_hasil_monitoringlist";
		$this->viewdetail = "panelbackend/spi_hasil_monitoringdetail";
		$this->viewprintdetail = "panelbackend/spi_hasil_monitoringdetailprint";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Hasil Monitoring';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Hasil Monitoring';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Hasil Monitoring';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Hasil Monitoring';
		}

		$this->load->model("Spi_hasil_monitoringModel", "model");
		$this->load->model("public_sys_userModel", "user");
		$this->data['userarr'] = ['' => ''] + $this->user->GetCombo();
		$this->access_role['print_detail'] = $this->data['acces_role']['print_detail'] = 1;


		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'tinymce', 'select2'
		);
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'kepada',
				'label' => 'Kepada',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'dari',
				'label' => 'Dari',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'nomor',
				'label' => 'Nomor',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'perihal',
				'label' => 'Perihal',
				'width' => "auto",
				'type' => "varchar",
			),
			// array(
			// 	'name' => 'dasar_tugas',
			// 	'label' => 'Dasar Tugas',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// ),
			// array(
			// 	'name' => 'dasar_hukum',
			// 	'label' => 'Dasar Hukum',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// ),
			// array(
			// 	'name' => 'data_fakta',
			// 	'label' => 'Data Fakta',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// ),
			// array(
			// 	'name' => 'pembahasan',
			// 	'label' => 'Pembahasan',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// ),
			// array(
			// 	'name' => 'kesimpulan',
			// 	'label' => 'Kesimpulan',
			// 	'width' => "auto",
			// 	'type' => "varchar",
			// ),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'kepada' => $this->post['kepada'],
			'dari' => $this->post['dari'],
			'nomor' => $this->post['nomor'],
			'perihal' => $this->post['perihal'],
			'dasar_tugas' => $this->post['dasar_tugas'],
			'dasar_hukum' => $this->post['dasar_hukum'],
			'data_fakta' => $this->post['data_fakta'],
			'pembahasan' => $this->post['pembahasan'],
			'kesimpulan' => $this->post['kesimpulan'],
			'id_penyusun' => $this->post['id_penyusun'],
			'tanggal' => $this->post['tanggal'],
		);
	}

	protected function Rules()
	{
		return array(
			"kepada" => array(
				'field' => 'kepada',
				'label' => 'Kepada',
				'rules' => "max_length[100]",
			),
			"dari" => array(
				'field' => 'dari',
				'label' => 'Dari',
				'rules' => "max_length[100]",
			),
			"nomor" => array(
				'field' => 'nomor',
				'label' => 'Nomor',
				'rules' => "max_length[100]",
			),
			"perihal" => array(
				'field' => 'perihal',
				'label' => 'Perihal',
				'rules' => "max_length[100]",
			),
			"dasar_tugas" => array(
				'field' => 'dasar_tugas',
				'label' => 'Dasar Tugas',
				'rules' => "",
			),
			"dasar_hukum" => array(
				'field' => 'dasar_hukum',
				'label' => 'Dasar Hukum',
				'rules' => "",
			),
			"data_fakta" => array(
				'field' => 'data_fakta',
				'label' => 'Data Fakta',
				'rules' => "",
			),
			"pembahasan" => array(
				'field' => 'pembahasan',
				'label' => 'Pembahasan',
				'rules' => "",
			),
			"kesimpulan" => array(
				'field' => 'kesimpulan',
				'label' => 'Kesimpulan',
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
		// $this->data['row']['id_pembahasan'] = $this->conn->GetList('select id_pembahasan as idkey, pembahasan as val from pembahasan_rapat where id_notulen = ' . $this->conn->escape($id));

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
				where deleted_date is null and {$this->model->pk} = " . $this->conn->escape($id));
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
		$this->data['row']['penyusun'] = $this->conn->GetRow("SELECT psu.name AS nama,msj.nama AS jabatan FROM public_sys_user psu LEFT JOIN spi_hasil_monitoring hm ON hm.id_penyusun = psu.user_id 
		LEFT JOIN mt_sdm_jabatan msj ON psu.id_jabatan = msj.id_jabatan WHERE deleted_date is null and hm.id_monitoring = " . $this->conn->escape($id));


		$this->data['hari_ini'] = $hari_ini;

		$this->View($this->viewprintdetail);
	}
}
