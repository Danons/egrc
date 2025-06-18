<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Mt_sdm_jabatan_sync extends _adminController
{

	public $limit_arr = array('100', '500', '1000', '2000', '-1' => 'Semua');
	public $limit = 100;
	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/mt_sdm_jabatansynclist";
		$this->viewdetail = "panelbackend/mt_sdm_jabatandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Jabatan';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Jabatan';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Jabatan';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Jabatan';
		}

		$this->data['width'] = "2400px";

		$this->load->model("Mt_sdm_jabatanModel", "model");
		$this->load->model("Mt_sdm_unitModel", "mtsdmunit");
		$this->data['mtsdmunitarr'] = $this->mtsdmunit->GetCombo();

		/*
		$this->load->model("Mt_sdm_jabatanModel","mtsdmjabatan");
		$this->data['mtsdmjabatanarr'] = $this->mtsdmjabatan->GetCombo();
*/
		$this->data['mtsdmjabatanarr'] = array();

		$this->load->model("Mt_sdm_jenjangModel", "mtsdmjenjang");
		$this->data['mtsdmjenjangarr'] = $this->mtsdmjenjang->GetCombo();


		$this->load->model("Mt_sdm_kategoriModel", "mtsdmkategori");
		$this->data['mtsdmkategoriarr'] = $this->mtsdmkategori->GetCombo();


		$this->load->model("Mt_sdm_dit_bidModel", "mtsdmditbid");
		$this->data['mtsdmditbidarr'] = $this->mtsdmditbid->GetCombo();


		$this->load->model("Mt_sdm_subbidModel", "mtsdmsubbid");
		$this->data['mtsdmsubbidarr'] = $this->mtsdmsubbid->GetCombo();


		$this->load->model("Mt_sdm_tipe_unitModel", "mtsdmtipeunit");
		$this->data['mtsdmtipeunitarr'] = $this->mtsdmtipeunit->GetCombo();

		$this->data['configfile'] = $this->config->item('file_upload_config');

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->SetPlugin(array(
			'datepicker', 'upload'
		));
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'nama',
				'label' => 'Nama',
				'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'id_unit',
				'label' => 'Unit',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['mtsdmunitarr'],
			),
			/*array(
				'name'=>'position_id', 
				'label'=>'Position ID', 
				'width'=>"auto",
				'type'=>"char",
			),
			array(
				'name'=>'tgl_mulai_efektif', 
				'label'=>'Tgl. Mulai Efektif', 
				'width'=>"auto",
				'type'=>"date",
			),
			array(
				'name'=>'tgl_akhir_efektif', 
				'label'=>'Tgl. Akhir Efektif', 
				'width'=>"auto",
				'type'=>"date",
			),
			array(
				'name'=>'id_jabatan_parent', 
				'label'=>'Jabatan Parent', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtsdmjabatanarr'],
			),
			array(
				'name'=>'superior_id', 
				'label'=>'Superior ID', 
				'width'=>"auto",
				'type'=>"char",
			),*/
			/*array(
				'name'=>'id_kategori', 
				'label'=>'Kategori', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtsdmkategoriarr'],
			),
			array(
				'name'=>'id_jenjang', 
				'label'=>'Jenjang', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtsdmjenjangarr'],
			),
			array(
				'name'=>'id_tipe_unit', 
				'label'=>'Tipe Unit', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtsdmtipeunitarr'],
			),
			array(
				'name'=>'id_dit_bid', 
				'label'=>'DIT BID', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtsdmditbidarr'],
			),
			array(
				'name'=>'id_subbid', 
				'label'=>'Subbid', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtsdmsubbidarr'],
			),*/
		);
	}

	protected function Record($id = null)
	{
		return array(
			'nama' => $this->post['nama'],
			'id_unit' => $this->post['id_unit'],
			'position_id' => $this->post['position_id'],
			'tgl_mulai_efektif' => $this->post['tgl_mulai_efektif'],
			'tgl_akhir_efektif' => $this->post['tgl_akhir_efektif'],
			'id_jabatan_parent' => $this->post['id_jabatan_parent'],
			'superior_id' => $this->post['superior_id'],
			'id_kategori' => $this->post['id_kategori'],
			'id_jenjang' => $this->post['id_jenjang'],
			'id_tipe_unit' => $this->post['id_tipe_unit'],
			'id_dit_bid' => $this->post['id_dit_bid'],
			'id_subbid' => $this->post['id_subbid'],
		);
	}

	protected function _onDetail($id = null)
	{
		$id_jabatan_parent = $this->data['row']['id_jabatan_parent'];
		$this->data['mtsdmjabatanarr'][$id_jabatan_parent] = $this->conn->GetOne("select concat(nama,' (',ifnull(id_unit,''),')') from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($id_jabatan_parent));
	}

	protected function Rules()
	{
		return array(
			"nama" => array(
				'field' => 'nama',
				'label' => 'Nama',
				'rules' => "required|max_length[200]",
			),
			"id_unit" => array(
				'field' => 'id_unit',
				'label' => 'Unit',
				'rules' => "required|in_list[" . implode(",", array_keys($this->data['mtsdmunitarr'])) . "]|max_length[18]",
			),
			"position_id" => array(
				'field' => 'position_id',
				'label' => 'Position ID',
				'rules' => "",
			),
			"id_jabatan_parent" => array(
				'field' => 'id_jabatan_parent',
				'label' => 'Jabatan Parent',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtsdmjabatanarr'])) . "]|max_length[10]",
			),
			"superior_id" => array(
				'field' => 'superior_id',
				'label' => 'Superior ID',
				'rules' => "",
			),
			"id_kategori" => array(
				'field' => 'id_kategori',
				'label' => 'Kategori',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtsdmkategoriarr'])) . "]|max_length[20]",
			),
			"id_jenjang" => array(
				'field' => 'id_jenjang',
				'label' => 'Jenjang',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtsdmjenjangarr'])) . "]|max_length[20]",
			),
			"id_tipe_unit" => array(
				'field' => 'id_tipe_unit',
				'label' => 'Tipe Unit',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtsdmtipeunitarr'])) . "]|max_length[20]",
			),
			"id_dit_bid" => array(
				'field' => 'id_dit_bid',
				'label' => 'DIT BID',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtsdmditbidarr'])) . "]|max_length[20]",
			),
			"id_subbid" => array(
				'field' => 'id_subbid',
				'label' => 'Subbid',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtsdmsubbidarr'])) . "]|max_length[20]",
			),
		);
	}


	function curl($url, $params = array())
	{
		// $url = site_url($q);
		$param_str = http_build_query($params);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 200);
		curl_setopt($ch, CURLOPT_TIMEOUT, 200);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		// curl_setopt($ch, CURLOPT_POST, 1);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, $param_str);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_COOKIEJAR, '-');
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
		curl_setopt($ch, CURLOPT_COOKIESESSION, true);

		$result = curl_exec($ch);

		if ($result)
			file_put_contents('logs/curl', $result . "\n", FILE_APPEND);

		$info = curl_getinfo($ch);
		$err = curl_errno($ch);
		$msg = curl_error($ch);

		if (false) {
			echo $url;
			echo '<pre>PARAM :' . "\n";
			print_r($params);
			echo ' ===>' . $result . "\n";

			echo 'INFO : ' . "\n";
			print_r($info);
			echo 'ERR : ' . "\n";
			print_r($err);
			echo 'MSG : ' . "\n";
			print_r($msg);
			echo '</pre>';
		}

		curl_close($ch);

		return $result;
	}

	private function kategori()
	{
		$url = "";

		if ($this->config->item("url_kategori"))
			$url = $this->config->item("url_kategori");

		// $json = json_decode(file_get_contents($url), true);
		$json = json_decode($this->curl($url), true);

		if (!$json)
			return false;

		$ret = $this->conn->Execute("update mt_sdm_kategori set is_aktif = 0");

		foreach ($json as $r) {
			if (!$ret)
				break;

			$record = array(
				'code' => $r['KODE'],
				'nama' => $r['NAMA'],
				'is_aktif' => 1,
			);

			$cek = $this->conn->GetOne("select 1 from mt_sdm_kategori where deleted_date is null and code=" . $this->conn->escape($record['code']));

			if ($cek)
				$ret = $this->conn->goUpdate("mt_sdm_kategori", $record, "code=" . $this->conn->escape($record['code']));
			else
				$ret = $this->conn->goInsert("mt_sdm_kategori", $record);
		}

		return $ret;
	}

	private function jenjang()
	{
		$url = "";

		if ($this->config->item("url_jenjang"))
			$url = $this->config->item("url_jenjang");

		$json = json_decode($this->curl($url), true);

		if (!$json)
			return false;

		$ret = $this->conn->Execute("update mt_sdm_jenjang set is_aktif = 0");

		foreach ($json as $r) {
			if (!$ret)
				break;

			$record = array(
				'code' => $r['KODE'],
				'nama' => $r['NAMA'],
				'is_aktif' => 1,
			);

			$cek = $this->conn->GetOne("select 1 from mt_sdm_jenjang where deleted_date is null and code=" . $this->conn->escape($record['code']));

			if ($cek)
				$ret = $this->conn->goUpdate("mt_sdm_jenjang", $record, "code=" . $this->conn->escape($record['code']));
			else
				$ret = $this->conn->goInsert("mt_sdm_jenjang", $record);
		}

		return $ret;
	}

	private function tipe_unit()
	{
		$url = "";

		if ($this->config->item("url_tipe_unit"))
			$url = $this->config->item("url_tipe_unit");

		$json = json_decode($this->curl($url), true);

		if (!$json)
			return false;

		$ret = $this->conn->Execute("update mt_sdm_tipe_unit set is_aktif = 0");

		foreach ($json as $r) {
			if (!$ret)
				break;

			$record = array(
				'code' => $r['KODE'],
				'nama' => $r['NAMA'],
				'is_aktif' => 1,
			);

			$cek = $this->conn->GetOne("select 1 from mt_sdm_tipe_unit where deleted_date is null and code=" . $this->conn->escape($record['code']));

			if ($cek)
				$ret = $this->conn->goUpdate("mt_sdm_tipe_unit", $record, "code=" . $this->conn->escape($record['code']));
			else
				$ret = $this->conn->goInsert("mt_sdm_tipe_unit", $record);
		}

		return $ret;
	}

	private function unit()
	{
		$url = "";

		if ($this->config->item("url_unit"))
			$url = $this->config->item("url_unit");

		$json = json_decode($this->curl($url), true);

		if (!$json)
			return false;

		$ret = $this->conn->Execute("update mt_sdm_unit set is_aktif = 0");

		foreach ($json as $r) {
			if (!$ret)
				break;

			$record = array(
				'table_code' => $r['KODE'],
				'table_desc' => $r['NAMA'],
				'is_aktif' => 1,
			);

			$cek = $this->conn->GetOne("select 1 from mt_sdm_unit where deleted_date is null and table_code=" . $this->conn->escape($record['table_code']));

			if ($cek)
				$ret = $this->conn->goUpdate("mt_sdm_unit", $record, "table_code=" . $this->conn->escape($record['table_code']));
			else
				$ret = $this->conn->goInsert("mt_sdm_unit", $record);
		}

		return $ret;
	}

	private function dit_bid()
	{
		$url = "";

		if ($this->config->item("url_dit_bid"))
			$url = $this->config->item("url_dit_bid");

		$json = json_decode($this->curl($url), true);

		if (!$json)
			return false;

		$ret = $this->conn->Execute("update mt_sdm_dit_bid set is_aktif = 0");

		foreach ($json as $r) {
			if (!$ret)
				break;

			$record = array(
				'code' => $r['KODE'],
				'nama' => $r['NAMA'],
				'is_aktif' => 1,
			);

			$cek = $this->conn->GetOne("select 1 from mt_sdm_dit_bid where deleted_date is null and code=" . $this->conn->escape($record['code']));

			if ($cek)
				$ret = $this->conn->goUpdate("mt_sdm_dit_bid", $record, "code=" . $this->conn->escape($record['code']));
			else
				$ret = $this->conn->goInsert("mt_sdm_dit_bid", $record);
		}

		return $ret;
	}

	private function subbid()
	{
		$url = "";

		if ($this->config->item("url_subbid"))
			$url = $this->config->item("url_subbid");

		$json = json_decode($this->curl($url), true);

		if (!$json)
			return false;

		$ret = $this->conn->Execute("update mt_sdm_subbid set is_aktif = 0");

		foreach ($json as $r) {
			if (!$ret)
				break;

			$record = array(
				'code' => $r['KODE'],
				'nama' => $r['NAMA'],
				'is_aktif' => 1,
			);

			$cek = $this->conn->GetOne("select 1 from mt_sdm_subbid where deleted_date is null and code=" . $this->conn->escape($record['code']));

			if ($cek)
				$ret = $this->conn->goUpdate("mt_sdm_subbid", $record, "code=" . $this->conn->escape($record['code']));
			else
				$ret = $this->conn->goInsert("mt_sdm_subbid", $record);
		}

		return $ret;
	}

	private function jabatan()
	{
		$url = "";

		if ($this->config->item("url_jabatan"))
			$url = $this->config->item("url_jabatan");

		$json = json_decode($this->curl($url), true);

		if (!$json)
			return false;

		$ret = $this->conn->Execute("update mt_sdm_jabatan set tgl_akhir_efektif=sysdate() where tgl_akhir_efektif is null");

		foreach ($json as $r) {
			if (!$ret)
				break;

			if (!$r['NAMA_POSISI'])
				continue;

			$record = array(
				'nama' => trim($r['NAMA_POSISI']),
				'id_unit' => trim($r['KODE_UNIT']),
				'position_id' => trim($r['POSITION_ID']),
				'superior_id' => trim($r['SUPERIOR_ID']),
				'id_kategori' => trim($r['KODE_KATEGORI']),
				'id_jenjang' => trim($r['KODE_JENJANG_JABATAN']),
				'id_tipe_unit' => trim($r['KODE_KLASIFIKASI_UNIT']),
				'id_dit_bid' => trim($r['KODE_DITBID']),
				'id_subbid' => trim($r['KODE_BAGIAN']),
				'tgl_akhir_efektif' => "{{null}}",
			);

			$cek = $this->conn->GetOne("select id_jabatan from mt_sdm_jabatan where deleted_date is null and position_id=" . $this->conn->escape($record['position_id']));

			$this->_setLogRecord($record, $cek);

			if ($cek) {
				$ret = $this->conn->goUpdate("mt_sdm_jabatan", $record, "position_id=" . $this->conn->escape($record['position_id']));
			} else {
				$ret = $this->conn->goInsert("mt_sdm_jabatan", $record);
			}

			if ($ret && $r['NID']) {
				$cek = $this->conn->GetOne("select 1 from mt_sdm_pegawai where deleted_date is null and nid = " . $this->conn->escape(trim($r['NID'])));
				$record = array(
					'nid' => trim($r['NID']),
					'email' => trim($r['EMAIL']),
					'position_id' => trim($r['POSITION_ID']),
					'nama_lengkap' => trim($r['NAMA_LENGKAP']),
				);

				if ($cek) {
					$ret = $this->conn->goUpdate("mt_sdm_pegawai", $record, "nid = " . $this->conn->escape($record['nid']));
				} else {
					$ret = $this->conn->goInsert("mt_sdm_pegawai", $record);
				}
			}
		}

		if ($ret)
			$ret = $this->conn->Execute("update mt_sdm_jabatan a set id_jabatan_parent = (select id_jabatan from mt_sdm_jabatan b where b.deleted_date and b.position_id = a.superior_id) where a.deleted_date is null and a.tgl_akhir_efektif is null");

		return $ret;
	}

	private function Sync()
	{
		$ret = true;
		$this->conn->StartTrans();

		// kategori
		if ($ret)
			$ret = $this->kategori();
		// jenjang
		if ($ret)
			$ret = $this->jenjang();
		// tipe_unit
		if ($ret)
			$ret = $this->tipe_unit();
		// unit
		if ($ret)
			$ret = $this->unit();
		// dit_bid
		if ($ret)
			$ret = $this->dit_bid();
		// subbid
		if ($ret)
			$ret = $this->subbid();
		// jabatan

		if ($ret)
			$ret = $this->jabatan();

		if ($ret)
			$this->conn->trans_commit();
		else
			$this->conn->trans_rollback();

		return $ret;
	}

	public function Index($page = 0)
	{

		if ($this->post['act'] == 'sync') {
			// $this->conn->debug = 1;
			$sukses = $this->Sync();

			// dpr($sukses,1);

			if ($sukses)
				echo json_encode(array("success" => true));
			else
				echo json_encode(array("success" => false));

			die();
		}

		// $this->layout = "panelbackend/layout2";


		if (1 /*!$_SESSION[SESSION_APP]['tgl_efektif']*/)
			$_SESSION[SESSION_APP]['tgl_efektif'] = date('Y-m-d');

		if ($_SESSION[SESSION_APP]['tgl_efektif']) {

			$this->data['tgl_efektif'] = $tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

			$this->_setFilter(" '$tgl_efektif' between ifnull(a.tgl_mulai_efektif, '$tgl_efektif')and ifnull(a.tgl_akhir_efektif,'$tgl_efektif') ");
		}

		parent::Index($page);
	}

	protected function _beforeDelete($id = null)
	{

		if (!$this->access_role['delete'])
			return false;

		$cek = $this->conn->GetOne("select s.nama
			from RISK_SASARAN_STRATEGIS_PIC sp
			join risk_sasaran s on sp.id_sasaran = s.id_sasaran
			where sp.deleted_date is null and sp.ID_JABATAN = " . $this->conn->escape($id));

		if ($cek) {
			SetFlash('err_msg', "Data tidak bisa dihapus karena jabatan sudah menjadi PIC di sasaran strategis " . $cek);
			redirect("$this->page_ctrl/detail/$id");
			die();
		}

		$cek = $this->conn->GetOne("select s.nama
			from RISK_SASARAN_KEGIATAN s
			where deleted_date is null and OWNER = " . $this->conn->escape($id));

		if ($cek) {
			SetFlash('err_msg', "Data tidak bisa dihapus karena jabatan sudah menjadi PIC di sasaran kegiatan " . $cek);
			redirect("$this->page_ctrl/detail/$id");
			die();
		}

		$cek = $this->conn->GetOne("select username
			from PUBLIC_SYS_USER 
			where deleted_date is null and ID_JABATAN = " . $this->conn->escape($id));

		if ($cek) {
			SetFlash('err_msg', "Data tidak bisa dihapus karena jabatan sudah ada di user dengan username " . $cek);
			redirect("$this->page_ctrl/detail/$id");
			die();
		}

		$cek = $this->conn->GetRow("select m.nama, n.nama as risiko
			from RISK_MITIGASI m
			join risk_risiko n on m.id_risiko = n.id_risiko
			where m.deleted_date is null and PENANGGUNG_JAWAB = " . $this->conn->escape($id));

		if (($cek)) {
			SetFlash('err_msg', "Data tidak bisa dihapus karena jabatan sudah menjadi penanggung jawab mitigasi dengan nama kegiatan " . $cek['nama'] . ", di risiko " . $cek['risiko'] . " (silahkan dicari di status risiko)");
			redirect("$this->page_ctrl/detail/$id");
			die();
		}

		$cek = $this->conn->GetOne("select nama
			from RISK_SCORECARD 
			where deleted_date is null and OWNER = " . $this->conn->escape($id));

		if ($cek) {
			SetFlash('err_msg', "Data tidak bisa dihapus karena jabatan sudah menjadi owner di scorecard " . $cek);
			redirect("$this->page_ctrl/detail/$id");
			die();
		}

		$ret = $this->conn->Execute("update RISK_TASK set deleted_date = now() where UNTUK = " . $this->conn->escape($id));

		return $ret;
	}

	public function HeaderExport()
	{
		return array(
			array(
				'name' => 'nama',
				'label' => 'Nama',
				'width' => "auto",
				'type' => "varchar2",
			),
			array(
				'name' => 'id_unit',
				'label' => 'Unit',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['mtsdmunitarr'],
			),
			array(
				'name' => 'position_id',
				'label' => 'Position ID',
				'width' => "auto",
				'type' => "char",
			),
			array(
				'name' => 'superior_id',
				'label' => 'Superior ID',
				'width' => "auto",
				'type' => "char",
			),
			array(
				'name' => 'id_kategori',
				'label' => 'Kategori',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['mtsdmkategoriarr'],
			),
			array(
				'name' => 'id_jenjang',
				'label' => 'Jenjang',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['mtsdmjenjangarr'],
			),
			array(
				'name' => 'id_tipe_unit',
				'label' => 'Tipe Unit',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['mtsdmtipeunitarr'],
			),
			array(
				'name' => 'id_dit_bid',
				'label' => 'DIT BID',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['mtsdmditbidarr'],
			),
			array(
				'name' => 'id_subbid',
				'label' => 'Subbid',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['mtsdmsubbidarr'],
			),
			array(
				'name' => 'tgl_mulai_efektif',
				'label' => 'Tgl. Mulai Efektif',
				'width' => "auto",
				'type' => "date",
			),
			array(
				'name' => 'tgl_akhir_efektif',
				'label' => 'Tgl. Akhir Efektif',
				'width' => "auto",
				'type' => "date",
			),
		);
	}

	public function import_list()
	{

		$file_arr = array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'application/wps-office.xls', 'application/wps-office.xlsx');

		if (in_array($_FILES['importupload']['type'], $file_arr)) {

			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters("", "");

			$this->load->library('Factory');
			$inputFileType = Factory::identify($_FILES['importupload']['tmp_name']);
			$objReader = Factory::createReader($inputFileType);
			$excel = $objReader->load($_FILES['importupload']['tmp_name']);
			$sheet = $excel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$this->model->conn->StartTrans();

			#header export
			$header = array(
				array(
					'name' => $this->model->pk
				)
			);
			$header = array_merge($header, $this->HeaderExport());

			for ($row = 2; $row <= $highestRow; $row++) {

				$col = 'A';
				$record = array();
				foreach ($header as $r1) {
					if ($r1['type'] == 'list')
						$record[$r1['name']] = (string)$sheet->getCell($col . $row)->getValue();
					elseif ($r1['type'] == 'listinverst') {
						$rk = strtolower(trim((string)$sheet->getCell($col . $row)->getValue()));
						$arr = array();
						foreach ($r1['value'] as $idkey => $value) {
							$arr[strtolower(trim($value))] = $idkey;
						}
						$record[$r1['name_ori']] = (string)$arr[$rk];
					} else
						$record[$r1['name']] = $sheet->getCell($col . $row)->getValue();

					$col++;
				}

				$this->data['row'] = $record;

				$error = $this->_isValidImport($record);
				if ($error) {
					$return['error'] = $error;
				} else {
					if ($record[$this->model->pk]) {
						$return = $this->model->Update($record, $this->model->pk . "=" . $record[$this->model->pk]);
						$id = $record[$this->model->pk];

						if ($return['success']) {
							$ret = $this->_afterUpdate($id);

							if (!$ret) {
								$return['success'] = false;
								$return['error'] = "Gagal update";
							}
						}
					} else {
						$return = $this->model->Insert($record);
						$id = $return['data'][$this->model->pk];

						if ($return['success']) {
							$ret = $this->_afterInsert($id);

							if (!$ret) {
								$return['success'] = false;
								$return['error'] = "Gagal insert";
							}
						}
					}
				}

				if (!$return['success'])
					break;
			}


			if (!$return['error'] && $return['success']) {

				$this->conn->Execute("update mt_sdm_jabatan a set id_jabatan_parent = (select id_jabatan from mt_sdm_jabatan b where b.position_id = a.superior_id and b.deleted_date is null)");

				$this->model->conn->trans_commit();
				SetFlash('suc_msg', "Import sukses");
			} else {
				$this->model->conn->trans_rollback();
				$return['error'] = "Gagal import. " . $return['error'];
				$return['success'] = false;
			}
		} else {
			$return['error'] = "Format file tidak sesuai";
		}

		echo json_encode($return);
	}
}
