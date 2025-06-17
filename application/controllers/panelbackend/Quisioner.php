<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Quisioner extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/quisionerlist";
		$this->viewdetail = "panelbackend/quisionerdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_dokumen";
		$this->viewprint = "panelbackend/quisionerprint";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Quisioner';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Quisioner';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Quisioner';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Quisioner';
		}

		$this->load->model("QuisionerModel", "model");
		$this->load->model("Penilaian_quisionerModel", "penilaianquisioner");

		$this->load->model("Mt_sdm_jabatanModel", "mtjabatan");
		$this->data['jabatanarr'] = ['' => 'Filter Jabatan...'] + $this->mtjabatan->GetCombo();

		$this->load->model("Public_sys_userModel", "userModel");
		$this->data['userarr'] = ['' => 'Filter Pegawai...'] + $this->userModel->GetCombo();

		$this->load->model("Mt_kriteriaModel", "mtkriteria");
		$this->load->model("PemeriksaanModel", "pemeriksaan");

		$this->data['unitarr'] = $this->conn->Getlist("SELECT msj.id_jabatan as idkey, msu.table_desc as val FROM mt_sdm_jabatan msj LEFT JOIN mt_sdm_unit msu ON msj.id_unit = msu.table_code where msj.deleted_date is null");

		$this->data['jenisjawabanarr'] = [
			"" => "",
			"yatidak" => "Ya/Tidak",
			"1sampai5" => "1 Sampai 5",
			"uraian" => "Uraian"
		];

		$this->access_role['list_print'] = true;
		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'select2'
		);
	}

	protected function _onDetail($id = null)
	{
		if ($this->id_kategori == 4) {
			$this->data['pemeriksaanarr'] = ['' => ''] + $this->pemeriksaan->GetCombo();
		} elseif (!in_array($id_kategori, [4, 5])) {
			$jenis = "k";
			if ($this->data['row']['jenis_jawaban'] == 'uraian')
				$jenis = "w";
			$this->data['kriteriaarr'] = ['' => ''] + $this->mtkriteria->GetCombo($this->id_kategori, $jenis);
		}
		$this->data['quisionerarr'] = ['' => 'Pilih Quisioner Induk...'] + $this->model->GetCombo($this->id_kategori);

		return true;
	}

	protected function _beforeDetail($id_kategori = null)
	{
		if (!Access("gcg", "panelbackend/quisioner") && $id_kategori == 1) {
			redirect("panelbackend/quisioner/index/2");
		}
		if (!Access("risk", "panelbackend/quisioner") && $id_kategori == 2) {
			redirect("panelbackend/quisioner/index/3");
		}
		if (!Access("iacm", "panelbackend/quisioner") && $id_kategori == 3) {
			$this->Error403();
		}

		$this->id_kategori = $id_kategori;
		$this->data['id_kategori'] = $id_kategori;
		$this->data['add_param'] .= $id_kategori;
	}

	public function Index($id_kategori = null, $page = 0)
	{
		if (!$id_kategori) {
			redirect("panelbackend/quisioner/index/1");
			die();
		}

		$this->_beforeDetail($id_kategori);

		if ($this->post['act'] == 'set_filter') {
			$_SESSION[SESSION_APP][$this->page_ctrl]["id_jabatan_filter"] = $this->post['id_jabatan_filter'];
			$_SESSION[SESSION_APP][$this->page_ctrl]["id_user_filter"] = $this->post['id_user_filter'];
		}

		$id_jabatan_filter = $_SESSION[SESSION_APP][$this->page_ctrl]["id_jabatan_filter"];
		$id_user_filter = $_SESSION[SESSION_APP][$this->page_ctrl]["id_user_filter"];

		if (!$this->access_role['view_all']) {
			$id_jabatan_filter = $_SESSION[SESSION_APP]['id_jabatan'];
			$id_user_filter = $_SESSION[SESSION_APP]['user_id'];
		}


		$this->data['id_jabatan_filter'] = $id_jabatan_filter;
		$this->data['id_user_filter'] = $id_user_filter;


		$this->data['page_title'] .= "<br/>";
		if ($this->access_role['view_all'])
			$this->data['page_title'] .= UI::createSelect("id_jabatan_filter", $this->data['jabatanarr'], $id_jabatan_filter, $this->access_role['view_all'], "form-control ", "onchange=\"goSubmit('set_filter')\" style='width:200px; margin-left:5px;'");
		$this->data['page_title'] .= UI::createSelect("id_user_filter", $this->data['userarr'], $id_user_filter, $this->access_role['view_all'], 'form-control', "onchange=\"goSubmit('set_filter')\"style='width:200px;margin-left:5px;'");

		$this->data['edited'] = true;

		if ($id_jabatan_filter || $id_user_filter) {
			$this->access_role['add'] = false;
			$this->access_role['edit'] = false;
			$this->access_role['delete'] = false;
		}


		if ($id_jabatan_filter) {
			$id_penilaian_session = $this->conn->GetOne("select max(id_penilaian_session) from penilaian_session where deleted_date is null and id_kategori = " . $this->conn->escape($this->id_kategori));
			$arr_param = [
				'limit' => -1,
				'filter' => "deleted_date is null and id_jabatan = " . $this->conn->escape($id_jabatan_filter) . " and id_penilaian_session = " . $this->conn->escape($id_penilaian_session),
			];
			$this->data['list'] = $this->penilaianquisioner->SelectGrid($arr_param, "*");
			if ($this->access_role['view_all']) {
				$id_user_filter = null;
			}
		}

		if ($id_user_filter) {
			$id_penilaian_session = $this->conn->GetOne("select max(id_penilaian_session) from penilaian_session where id_kategori = " . $this->conn->escape($this->id_kategori));
			$arr_param = [
				'limit' => -1,
				'filter' => "deleted_date is null and id_user = " . $this->conn->escape($id_user_filter) . " and id_penilaian_session = " . $this->conn->escape($id_penilaian_session),
			];
			$this->data['list'] = $this->penilaianquisioner->SelectGrid($arr_param, "*");

			if ($id_penilaian_session) {
				$set_filter_penilaian_session_dan_id_kategori = "pq.id_penilaian_session = " . $this->conn->escape($id_penilaian_session);
			} else {
				$set_filter_penilaian_session_dan_id_kategori = "q.id_kategori = " . $this->conn->escape($id_kategori);
			}

			$this->data['list']['rows'] = $this->conn->GetArray("
			select pq.id_penilaian_quisioner,pq.id_jabatan,pq.id_user,COALESCE(pq.id_quisioner,q.id_quisioner) AS id_quisioner,
			COALESCE(pq.id_quisioner_parent,q.id_quisioner_parent) AS id_quisioner_parent, 
			COALESCE(pq.pertanyaan,q.pertanyaan) AS pertanyaan, 
			COALESCE(pq.jenis_jawaban,q.jenis_jawaban) AS jenis_jawaban, pq.jawaban,pq.id_penilaian_session,pq.nilai 
			from quisioner q LEFT JOIN penilaian_quisioner pq ON q.id_quisioner = pq.id_quisioner 
			AND pq.id_user = " . $this->conn->escape($id_user_filter) . " and " . $set_filter_penilaian_session_dan_id_kategori .
				" where 1=1 and q.deleted_date is null  AND q.id_kategori = " . $this->conn->escape($this->id_kategori) . " 
			 and exists (select 1 from quisioner_user where q.id_quisioner = quisioner_user.id_quisioner 
			and quisioner_user.id_user = " . $this->conn->escape($id_user_filter) . ")");

			if ($this->access_role['view_all']) {
				$id_jabatan_filter = null;
			}
		}

		$this->_setFilter("id_kategori = " . $this->conn->escape($this->id_kategori));
		if (!$this->data['list']['rows']) {
			if ($id_jabatan_filter && $this->access_role['view_all'])
				$this->_setFilter(" deleted_date is null and exists (select 1 
				from quisioner_jabatan 
				where quisioner.id_quisioner = quisioner_jabatan.id_quisioner 
				and quisioner_jabatan.id_jabatan = " . $this->conn->escape($id_jabatan_filter) . ")");

			if ($id_user_filter)
				$this->_setFilter("deleted_date is null and exists (select 1 
				from quisioner_user 
				where quisioner.id_quisioner = quisioner_user.id_quisioner 
				and quisioner_user.id_user = " . $this->conn->escape($id_user_filter) . ")");
			$arr_param = [
				'limit' => -1,
				'filter' => $this->_getFilter()
			];
			$this->data['list'] = $this->model->SelectGrid($arr_param, "*");
		}
		$this->idparr = [];
		$temp = $this->data['list']['rows'];
		foreach ($temp as $rw) {
			$this->idparr[$rw['id_quisioner']] = 1;
		}

		foreach ($temp as $rw) {
			if (!$this->idparr[$rw['id_quisioner_parent']]) {
				$this->_getParent($rw['id_quisioner_parent']);
			}
		}

		$i = 0;
		$this->GenerateTree($this->data['list']['rows'], "id_quisioner_parent", "id_quisioner", "pertanyaan", $rows, null, $i);

		$this->data['list']['rows'] = $rows;

		if ($id_jabatan_filter) {

			if ($this->post['act'] == 'delete') {

				$ret = $this->conn->Execute("update penilaian_quisioner set deleted_date = now() 
				where id_jabatan = " . $this->conn->escape($id_jabatan_filter) . " 
				and id_penilaian_session = " . $this->conn->escape($id_penilaian_session));

				if ($ret) {
					SetFlash("suc_msg", "Data terhapus");
					redirect(current_url());
				} else
					$this->data['err_msg'] = "Data gagal terhapus";
			}
			if ($this->post['act'] == 'save') {
				$ret = true;
				foreach ($this->data['list']['rows'] as &$r) {
					$r['id_jabatan'] = $id_jabatan_filter;
					$r['id_penilaian_session'] = $id_penilaian_session;
					$r['nilai_sebelum'] = $r['nilai'];
					$r['nilai'] = $this->post['nilai'][$r['id_quisioner']];
					$r['jawaban'] = $this->post['jawaban'][$r['id_quisioner']];
					$r['id_user'] = $id_user_filter;

					if (!$ret)
						continue;

						// cek apakah sudah pernah di jawab atau belum
					$cek = $this->conn->GetOne("select 1 from penilaian_quisioner 
					where deleted_date is null and id_quisioner = " . $this->conn->escape($r['id_quisioner']) . " 
					and id_jabatan = " . $this->conn->escape($r['id_jabatan']) . " 
					and id_penilaian_session = " . $this->conn->escape($r['id_penilaian_session']) . " and id_user = " . $this->conn->escape($r['id_user']));
					if ($cek) {
				

						// update jika sudah ada
						$ret = $this->conn->goUpdate("penilaian_quisioner", $r, "id_quisioner = " . $this->conn->escape($r['id_quisioner']) . " 
						and deleted_date is null and id_jabatan = " . $this->conn->escape($id_jabatan_filter) . " 
						and id_penilaian_session = " . $this->conn->escape($id_penilaian_session) . " and id_user = " . $this->conn->escape($id_user_filter));
					} else {
						
						// cek apakaha ada di quisioner
						// $this->conn->debug =1 ;
						$cek = $this->conn->GetOne("SELECT * FROM quisioner q LEFT JOIN quisioner_jabatan qj ON  q.id_quisioner = qj.id_quisioner WHERE q.deleted_date is null and qj.id_jabatan = " . $r['id_jabatan'] . " AND q.id_quisioner = " . $r['id_quisioner']);
						
						if (!$cek) {
							unset($r['id_jabatan']);
							$cek = $this->conn->GetOne("select 1 from penilaian_quisioner 
							where deleted_date is null and id_quisioner = " . $this->conn->escape($r['id_quisioner']) . "  
							and id_penilaian_session = " . $this->conn->escape($id_penilaian_session) . " and id_user = " . $this->conn->escape($id_user_filter));
							if ($cek) {
								$ret = $this->conn->goUpdate("penilaian_quisioner", $r, "id_quisioner = " . $this->conn->escape($r['id_quisioner']) . "  
								and deleted_date is null and id_penilaian_session = " . $this->conn->escape($id_penilaian_session) . " and id_user = " . $this->conn->escape($id_user_filter));
							}else{
								unset($r['id_penilaian_quisioner']);
								$ret = $this->conn->goInsert("penilaian_quisioner", $r);
							}
						} else {
							// dpr($r,1);
							unset($r['id_penilaian_quisioner']);
							$ret = $this->conn->goInsert("penilaian_quisioner", $r);
						}
					}
				}

				if ($ret) {
					SetFlash("suc_msg", "Data tersimpan");
					redirect(current_url());
				} else
					$this->data['err_msg'] = "Data gagal terimpan";
			}
		}
		if ($this->post['act'] == 'get_detail') {
			$this->get_detail($this->post['id_kategori']);
			exit();
		}
		if ($this->post['act'] == 'get_detail_all') {
			$this->get_detail_all($this->post['id_kategori']);
			exit();
		}

		$this->View($this->viewlist);
	}

	private function _getParent($id_quisioner_parent)
	{
		$row = $this->model->GetByPk($id_quisioner_parent);
		if ($row) {
			$this->data['list']['rows'][] = $row;
			$this->idparr[$id_quisioner_parent] = 1;

			if (!$this->idparr[$row['id_quisioner_parent']]) {
				$this->_getParent($row['id_quisioner_parent']);
			}
		}
	}

	function GenerateTree(&$row, $colparent, $colid, $collabel, &$return = array(), $valparent = null, &$i = 0, $level = 0, $not_id = null)
	{
		$level++;
		foreach ($row as $key => $value) {
			if ($not_id && $value[$colparent] == $not_id)
				continue;

			# code...
			if (trim($value[$colparent]) == trim($valparent)) {
				unset($row[$key]);

				$space = '';
				$value['level'] = $level;

				$value[$collabel] = $space . $value[$collabel];
				$return[$i] = $value;

				$i++;
				$this->GenerateTree($row, $colparent, $colid, $collabel, $return, $value[$colid], $i, $level, $not_id);
			}
		}

		if ($row && $level == 1 && !$not_id)
			$return = array_merge($return, $row);
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'nama_jabatan',
				'label' => 'Jabatan',
				'width' => "auto",
				'type' => "text"
			),
			array(
				'name' => 'unit_kerja',
				'label' => 'Unit Kerja',
				'width' => "auto",
				'type' => "text"
			),
			array(
				'name' => 'pegawai',
				'label' => 'Pegawai',
				'width' => "auto",
				'type' => "text"
			),
			array(
				'name' => 'pertanyaan',
				'label' => 'Pertanyaan',
				'width' => "auto",
				'type' => "text",
			),
			array(
				'name' => 'jenis_jawaban',
				'label' => 'Jenis',
				'width' => "170px",
				'type' => "list",
				'value' => $this->data['jenisjawabanarr'],
			),
			array(
				'name' => 'respon',
				'label' => 'Respon',
				'width' => "auto",
				'type' => "text"
			),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'id_kategori' => $this->id_kategori,
			'pertanyaan' => $this->post['pertanyaan'],
			'id_quisioner_parent' => $this->post['id_quisioner_parent'],
			'jenis_jawaban' => $this->post['jenis_jawaban'],
		);
	}

	protected function Rules()
	{
		return array(
			"jenis_jawaban" => array(
				'field' => 'jenis_jawaban',
				'label' => 'Jenis Jawaban',
				'rules' => "max_length[20]",
			),
			"id_kategori" => array(
				'field' => 'id_kategori',
				'label' => 'Kategori',
				'rules' => "integer|max_length[10]",
			),
		);
	}


	protected function _afterDetail($id)
	{
		if (!$this->data['row']['id_jabatanarr'])
			$this->data['row']['id_jabatanarr'] = $this->conn->GetList("select id_jabatan as val from quisioner_jabatan where deleted_date is null and id_quisioner = " . $this->conn->escape($id));

		if (!$this->data['row']['id_userarr'])
			$this->data['row']['id_userarr'] = $this->conn->GetList("select id_user as val from quisioner_user where deleted_date is null and id_quisioner = " . $this->conn->escape($id));

		if (!$this->data['row']['id_kriteriaarr'])
			$this->data['row']['id_kriteriaarr'] = $this->conn->GetList("select id_kriteria as val from quisioner_kriteria where deleted_date is null and id_quisioner = " . $this->conn->escape($id));

		if (!$this->data['row']['tahunarr'])
			$this->data['row']['tahunarr'] = $this->conn->GetList("select tahun as val from quisioner_tahun where deleted_date is null and id_quisioner = " . $this->conn->escape($id));

		if (!$this->data['row']['pemeriksaanarr'])
			$this->data['row']['pemeriksaanarr'] = $this->conn->GetList("select id_pemeriksaan as val from quisioner_pemeriksaan where deleted_date is null and id_quisioner = " . $this->conn->escape($id));
	}

	protected function _afterUpdate($id)
	{
		return $this->_afterInsert($id);
	}

	protected function _afterInsert($id = null)
	{
		$ret = true;

		if ($ret && $this->post['id_jabatanarr']) {
			$ret = $this->conn->Execute("update quisioner_jabatan set deleted_date = now() where id_quisioner = " . $this->conn->escape($id));
			foreach ($this->post['id_jabatanarr'] as $k => $v) {
				if (!$ret)
					break;

				$ret = $this->conn->goInsert("quisioner_jabatan", ['id_jabatan' => $v, "id_quisioner" => $id]);
				// dpr($v, 1);
				$getUserArr[$k] = $this->conn->GetList("SELECT psu.user_id as val, psu.user_id as idkey FROM public_sys_user psu LEFT JOIN mt_sdm_jabatan msj ON msj.id_jabatan = psu.id_jabatan WHERE psu.deleted_date is null and msj.id_jabatan = " . $v);
			}
			foreach ($getUserArr as $userarr) {
				foreach ($userarr as $key => $val) {
					$this->data['id_userarr'][$key] = $val;
				}
			}
		}

		if ($this->post['id_userarr']) {

			$postuserarr = $this->post['id_userarr'];
			if ($this->data['id_userarr']) {
				$this->post['id_userarr'] = $postuserarr + $this->data['id_userarr'];
			}
		} else {
			$this->post['id_userarr'] = $this->data['id_userarr'];
		}

		if ($ret && $this->post['id_userarr']) {
			$ret = $this->conn->Execute("delete from quisioner_user where id_quisioner = " . $this->conn->escape($id));
			foreach ($this->post['id_userarr'] as $k => $v) {
				if (!$ret)
					break;

				$ret = $this->conn->goInsert("quisioner_user", ['id_user' => $v, "id_quisioner" => $id]);
			}
		}

		if ($ret && $this->post['id_kriteriaarr']) {
			$ret = $this->conn->Execute("delete from quisioner_kriteria where id_kriteria = " . $this->conn->escape($id));
			foreach ($this->post['id_kriteriaarr'] as $k => $v) {
				if (!$ret)
					break;

				$ret = $this->conn->goInsert("quisioner_kriteria", ['id_kriteria' => $v, "id_quisioner" => $id]);
			}
		}

		if ($ret && $this->post['pemeriksaanarr']) {
			$ret = $this->conn->Execute("delete from quisioner_pemeriksaan where id_pemeriksaan = " . $this->conn->escape($id));
			foreach ($this->post['pemeriksaanarr'] as $k => $v) {
				if (!$ret)
					break;

				$ret = $this->conn->goInsert("quisioner_pemeriksaan", ['id_pemeriksaan' => $v, "id_quisioner" => $id]);
			}
		}

		if ($ret && $this->post['tahunarr']) {
			$ret = $this->conn->Execute("delete from quisioner_tahun where tahun = " . $this->conn->escape($id));
			foreach ($this->post['tahunarr'] as $k => $v) {
				if (!$ret)
					break;

				$ret = $this->conn->goInsert("quisioner_tahun", ['tahun' => $v, "id_quisioner" => $id]);
			}
		}
		return $ret;
	}

	public function Add($id_kategori = null)
	{
		$this->Edit($id_kategori);
	}

	public function Edit($id_kategori = null, $id = null)
	{

		$id = urldecode($id);
		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->_beforeDetail($id_kategori);

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
					redirect("$this->page_ctrl/detail/$id_kategori/$id");
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

	public function Detail($id_kategori = null, $id = null)
	{

		$id = urldecode($id);
		$this->_beforeDetail($id_kategori);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$this->_onDetail($id);

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	public function Delete($id_kategori = null, $id = null)
	{

		$id = urldecode($id);
		$this->model->conn->StartTrans();

		$this->_beforeDetail($id_kategori);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$return = $this->_beforeDelete($id);

		if ($return) {
			$return = $this->model->delete("$this->pk = " . $this->conn->qstr($id));
		}

		if ($return) {
			$return1 = $this->_afterDelete($id);
			if (!$return1)
				$return = false;
		}

		$this->model->conn->CompleteTrans();

		if ($return) {

			$this->log("menghapus", $this->data['row']);

			SetFlash('suc_msg', $return['success']);
			redirect("$this->page_ctrl/index/$id_kategori");
		} else {
			SetFlash('err_msg', "Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id_kategori/$id");
		}
	}


	public function go_print($id_kategori = null)
	{
		// $_SESSION[SESSION_APP][$this->page_ctrl]["id_jabatan_filter"]
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";
		$this->data['no_header'] = true;

		$this->data['header'] = $this->Header();

		$filter = "";
		if ($_SESSION[SESSION_APP][$this->page_ctrl]['id_jabatan_filter']) {
			$id_jabatan_filter = $_SESSION[SESSION_APP][$this->page_ctrl]['id_jabatan_filter'];
			$filter .= ' and msj.id_jabatan = ' . $this->conn->escape($id_jabatan_filter);
		}

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['id_user_filter']) {
			$id_user_filter = $_SESSION[SESSION_APP][$this->page_ctrl]['id_user_filter'];
			$filter .= " and psu.user_id = " . $this->conn->escape($id_user_filter);
		}

		$sql = "
		SELECT q.*,COALESCE(pq.nilai,pq.jawaban) as nilai,msj.nama as nama_jabatan ,msj.id_jabatan, psu.name as nama_user, psu.username as nipp
		FROM quisioner q left JOIN penilaian_quisioner pq ON pq.id_quisioner = q.id_quisioner 
		LEFT JOIN mt_sdm_jabatan msj ON pq.id_jabatan = msj.id_jabatan 
		LEFT JOIN public_sys_user psu ON pq.id_user = psu.user_id
		 where 1=1 and q.deleted_date is null and q.id_kategori = " . $this->conn->escape((int)$id_kategori) . $filter;

		$this->data['list']['rows'] = $this->conn->GetArray($sql);
		$this->View($this->viewprint);
	}
	private function get_detail($id_kategori)
	{
		$this->data['rows'] = $this->conn->GetArray("
		select pq.*,coalesce(pq.nilai,pq.jawaban) as nilai_jawaban, msj.nama AS nama_jabatan, 
		psu.name as nama_user from penilaian_quisioner pq LEFT JOIN quisioner q ON pq.id_quisioner = q.id_quisioner
		left JOIN mt_sdm_jabatan msj ON pq.id_jabatan = msj.id_jabatan 
		LEFT JOIN public_sys_user psu ON pq.id_user = psu.user_id WHERE pq.deleted_date is null and q.id_kategori = " . $this->conn->escape($id_kategori));

		$this->data['rows_user'] = $this->conn->GetArray("
		select pq.*,coalesce(nilai,jawaban) as nilai_jawaban from penilaian_quisioner pq 
		left join quisioner q ON pq.id_quisioner = q.id_quisioner where id_jabatan is null and deleted_date is null AND q.id_kategori = " . $this->conn->escape($id_kategori));


		$this->GenerateTree($this->data['rows'], "id_quisioner_parent", "id_quisioner", "pertanyaan", $rows, null, $i);
		$this->GenerateTree($this->data['rows_user'], "id_quisioner_parent", "id_quisioner", "pertanyaan", $rows_user, null, $i);

		// dpr($rows);
		$this->data['rows'] = $rows;
		$this->data['rows_user'] = $rows_user;

		// dpr($this->data['rows'], 1);

		$this->partialView('panelbackend/quisionerinput');
	}
	private function get_detail_all($id_kategori)
	{

		$this->data['rows'] = $this->conn->GetArray("
		select pq.*,coalesce(nilai,jawaban) as nilai_jawaban from penilaian_quisioner pq 
		left join quisioner q ON pq.id_quisioner = q.id_quisioner where pq.deleted_date is null AND q.id_kategori = " . $this->conn->escape($id_kategori));

		$this->GenerateTree($this->data['rows'], "id_quisioner_parent", "id_quisioner", "pertanyaan", $rows, null, $i);
		// $this->GenerateTree($this->data['rows_user'], "id_quisioner_parent", "id_quisioner", "pertanyaan", $rows_user, null, $i);
		$this->data['rows'] = $rows;
		// dpr($this->data['rows'], 1);
		$this->partialView('panelbackend/quisionerinputall');
	}
}
