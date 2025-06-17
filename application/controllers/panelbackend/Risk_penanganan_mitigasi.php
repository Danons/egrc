<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Risk_penanganan_mitigasi extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/risk_penangananlist";
		$this->viewdetail = "panelbackend/risk_penanganan_mitigasidetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_scorecard";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'RENCANA TUJUAN SASARAN PROGRAM (TSP)';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'RENCANA TUJUAN SASARAN PROGRAM (TSP)';
			$this->data['edited'] = true;
			unset($this->access_role['lst']);
		} elseif ($this->mode == 'edit_nonrutin') {
			$this->data['page_title'] = 'RENCANA TUJUAN SASARAN PROGRAM (TSP)';
			$this->data['edited'] = true;
			unset($this->access_role['lst']);
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'RENCANA TUJUAN SASARAN PROGRAM (TSP)';
			$this->data['edited'] = false;
			unset($this->access_role['lst']);
		} else {
			$this->data['page_title'] = 'RENCANA TUJUAN SASARAN PROGRAM (TSP)';
		}

		// $this->load->model("Risk_mitigasiModel", "model");
		$this->load->model("Risk_mitigasi_programModel", "modelmitigasiprigram");
		$this->load->model("Risk_risikoModel", "model");
		$this->load->model("Risk_scorecardModel", 'riskscorecard');

		$this->load->model("Mt_risk_efektifitas_pengukuranModel", "pengukuran");
		$this->data['mtpengukuranarr'] = $this->pengukuran->GetCombo();

		$this->SetAccess('panelbackend/risk_scorecard');

		$this->pk = $this->model->pk;
		// $this->data['pk'] = $this->pk;
		$this->data['pk'] = 'id_mitigasi_program';
		$this->plugin_arr = array(
			'datepicker', 'select2', 'tinymce'
		);

		$this->data['id_periode_tw'] = 2;
		$this->data['tahun'] = $this->data['thn'] = date("Y");

		// $this->data['penanggungjawabarr'] = ["" => ""] + $this->conn->GetList("select 
		// id_jabatan as idkey, 
		// concat(nama,' (',coalesce(id_unit,''),')')  as val
		// from mt_sdm_jabatan a
		// where exists (select 1 from public_sys_user_group b where a.id_jabatan = b.id_jabatan and b.group_id = 24) ");
		$this->data['penanggungjawabarr'] = ["" => ""] + $this->conn->GetList("select 
		id_jabatan as idkey, 
		concat(nama,' (',coalesce(id_unit,''),')')  as val
		from mt_sdm_jabatan a
		where exists (select 1 from public_sys_user_group b where b.deleted_date is null and a.id_jabatan = b.id_jabatan and b.group_id = 24) ");
	}

	protected function Record($id = null, $id_mitigasi_arr = null)
	{
		$record = array();
		if ($this->post['act'] == 'save') {
			foreach ($this->post['mitigasi'] as $f) {
				if (!$f['id_mitigasi'])
					$f['id_mitigasi'] = $id_mitigasi_arr;
				$f['biaya'] = Rupiah2Number($f['biaya']);
				$f['id_risiko'] = $id;
				$f['penanganan_pencegahan'] = $f['penanganan_pencegahan'];
				$f['tgl_penyelesaiaan'] = $f['tgl_penyelesaiaan'];
				$f['sumber_daya'] = $f['sumber_daya'];
				$record[] = $f;
			}
			foreach ($record as &$r) {
				$this->_setLogRecord($r, $id);
			}
		}
		if ($this->post['act'] !== 'save') {
			foreach ($this->post['mitigasi'] as $f) {
				if (!$f['id_mitigasi'])
					$f['id_mitigasi'] = $id_mitigasi_arr;
				$f['biaya'] = Rupiah2Number($f['biaya']);
				$f['id_risiko'] = $id;
				$f['penanganan_pencegahan'] = $f['penanganan_pencegahan'];
				$f['tgl_penyelesaiaan'] = $f['tgl_penyelesaiaan'];
				$f['sumber_daya'] = $f['sumber_daya'];
				$record[] = $f;
			}
		}

		return $record;
	}

	protected function Rules()
	{
		// $return = array(
		// 	"residual_target_dampak" => array(
		// 		'field' => 'residual_target_dampak',
		// 		'label' => 'Tingkat Dampak',
		// 		'rules' => "in_list[" . implode(",", array_keys($this->data['mtdampakrisikoarr'])) . "]|required",
		// 	),
		// 	"residual_target_kemungkinan" => array(
		// 		'field' => 'residual_target_kemungkinan',
		// 		'label' => 'Tingkat Kemungkinan',
		// 		'rules' => "in_list[" . implode(",", array_keys($this->data['mtkemungkinanrisikoarr'])) . "]|required",
		// 	),
		// );

		return $return;
	}

	public function Index($id = null)
	{
		redirect("panelbackend/risk_penanganan/detail/$id");
	}

	public function Add($id_scorecard = null)
	{
		$this->Error403();
	}
	public function check_rutin($id, $id_mitigasi_arr)
	{
		$is_rutin = $this->conn->GetOne("select rutin_non_rutin from risk_scorecard a where exists(select 1 from risk_risiko b where b.deleted_date is null and a.id_scorecard = b.id_scorecard and b.id_risiko = " . $this->conn->escape($id) . ")");
		if ($is_rutin == 'nonrutin') {
			redirect(base_url("panelbackend/risk_penanganan_mitigasi/edit_nonrutin/$id/$id_mitigasi_arr"));
		}
	}

	public function Edit_nonrutin($id = null, $id_mitigasi_arr = null)
	{

		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->data['row'] = $this->model->GetByPk($id);

		$this->_beforeDetail($id, $id_mitigasi_arr);

		if (!$this->data['row'] && $id)
			$this->NoData();

		$this->data['rowheader1'] = $this->data['row'];

		$this->isLock();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");

		if ($this->post && $this->post['act'] <> 'change') {
			if (!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id, $id_mitigasi_arr);


			$this->data['row']['mitigasi'] = $record;
			// if ($record && $this->data['row'])
			// 	$this->data['row'] = array_merge($this->data['row'], $record);
			// $this->data['row'] = array_merge($this->data['row'], $this->post);
		}

		$this->_onDetail($id, $id_mitigasi_arr, $record);
		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {
			$this->_isValid($record, true);

			$this->_beforeEdit($record, $id);

			// $this->_setLogRecord($record, $id);

			$this->modelmitigasiprigram->conn->StartTrans();
			foreach ($record as $re) {
				// if ($this->data['row'][$this->pk]) {
				if ($re['id_mitigasi_program']) {
					$idd = $re['id_mitigasi_program'];
					unset($re['id_mitigasi_program']);

					$return = $this->_beforeUpdate($re, $id);


					if ($return) {
						$return = $this->modelmitigasiprigram->Update($re, "id_mitigasi_program = " . $this->conn->qstr($idd));
					}

					if ($return['success']) {

						$this->log("mengubah", $this->data['row']);

						$return1 = $this->_afterUpdate($id);

						if (!$return1) {
							$return = false;
						}
					}
				} else {

					$is_insert = true;

					$return = $this->_beforeInsert($re);

					if ($return) {
						$return = $this->modelmitigasiprigram->Insert($re);
					}

					if ($return['success']) {

						$this->log("menambah", $re);

						$return1 = $this->_afterInsert($id);

						if (!$return1) {
							$return = false;
						}
					}
				}
			}

			if ($return['success']) {

				$this->modelmitigasiprigram->conn->trans_commit();

				$this->_afterEditSucceed($id);

				SetFlash('suc_msg', $return['success']);
				redirect("$this->page_ctrl/detail/$id/$id_mitigasi_arr");
			} else {

				$this->modelmitigasiprigram->conn->trans_rollback();

				$this->data['row'] = array_merge($this->data['row'], $record);
				$this->data['row'] = array_merge($this->data['row'], $this->post);

				$this->_afterEditFailed($id);

				$this->data['err_msg'] = "Data gagal disimpan";
			}
		}

		$this->_afterDetail($id, $id_mitigasi_arr);

		// $this->data['buttonMenu'] = "<button type='button' class='btn btn-sm btn-warning' onclick=\"goDetail('')\"><i class='bi bi-eye'></i> Detil NonRutin</button" ;
		$this->data['buttonMenu'] = "<button type='button' class='btn btn-sm btn-warning' onclick='goDetail()'><i class='bi bi-eye'></i> Detil NonRutin</button>" .
			'<script>
				function goDetail(){
		    	    window.open("' . base_url($ci->page_ctrl . "/panelbackend/risk_penanganan_mitigasi/detail_nonrutin/$id") . '/?"+$("#main_form").serialize(),"_blank");
				}
			</script>';
		$this->data['mode'] = 'edit_nonrutin';
		$this->View($this->viewdetail);
	}

	public function Edit($id = null, $id_mitigasi_arr = null)
	{
		// $this->check_rutin($id, $id_mitigasi_arr);

		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->data['row'] = $this->model->GetByPk($id);

		$this->_beforeDetail($id, $id_mitigasi_arr);

		if (!$this->data['row'] && $id)
			$this->NoData();

		$this->data['rowheader1'] = $this->data['row'];

		$this->isLock();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");

		if ($this->post && $this->post['act'] <> 'change') {
			if (!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id, $id_mitigasi_arr);


			$this->data['row']['mitigasi'] = $record;
			$this->data['row']['keterangan'] = $this->post['keterangan'];
			// if ($record && $this->data['row'])
			// 	$this->data['row'] = array_merge($this->data['row'], $record);
			// $this->data['row'] = array_merge($this->data['row'], $this->post);
		}

		$this->_onDetail($id, $id_mitigasi_arr, $record);
		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {
			$this->_isValid($record, true);

			$this->_beforeEdit($record, $id);

			// $this->_setLogRecord($record, $id);

			$this->modelmitigasiprigram->conn->StartTrans();
			foreach ($record as $re) {
				// if ($this->data['row'][$this->pk]) {
				if ($re['id_mitigasi_program']) {
					$idd = $re['id_mitigasi_program'];
					unset($re['id_mitigasi_program']);

					$return = $this->_beforeUpdate($re, $id);


					if ($return) {
						$return = $this->modelmitigasiprigram->Update($re, "id_mitigasi_program = " . $this->conn->qstr($idd));
					}

					if ($return['success']) {

						$this->log("mengubah", $this->data['row']);

						$return1 = $this->_afterUpdate($id, $id_mitigasi_arr);

						if (!$return1) {
							$return = false;
						}
					}
				} else {

					$is_insert = true;

					$return = $this->_beforeInsert($re);

					if ($return) {
						$return = $this->modelmitigasiprigram->Insert($re);
					}

					if ($return['success']) {

						$this->log("menambah", $re);

						$return1 = $this->_afterInsert($id, $id_mitigasi_arr);

						if (!$return1) {
							$return = false;
						}
					}
				}
			}

			if ($return['success']) {

				$this->modelmitigasiprigram->conn->trans_commit();

				$this->_afterEditSucceed($id);

				SetFlash('suc_msg', $return['success']);
				redirect("$this->page_ctrl/detail/$id/$id_mitigasi_arr");
			} else {

				$this->modelmitigasiprigram->conn->trans_rollback();

				$this->data['row'] = array_merge($this->data['row'], $record);
				$this->data['row'] = array_merge($this->data['row'], $this->post);

				$this->_afterEditFailed($id);

				$this->data['err_msg'] = "Data gagal disimpan";
			}
		}

		$this->_afterDetail($id, $id_mitigasi_arr);

		$this->View($this->viewdetail);
	}


	public function Detail($id = null, $id_mitigasi_arr = null)
	{

		$this->data['row'] = $this->model->GetByPk($id);

		$this->_beforeDetail($id, $id_mitigasi_arr);

		$this->data['rowheader1'] = $this->data['row'];

		$this->_onDetail($id, $id_mitigasi_arr);

		$this->isLock();
		if (!$this->data['row'])
			$this->NoData();

		$this->_afterDetail($id, $id_mitigasi_arr);

		$this->View($this->viewdetail);
	}

	public function detail_nonrutin($id = null, $id_mitigasi_arr = null)
	{

		$this->data['row'] = $this->model->GetByPk($id);

		$this->_beforeDetail($id, $id_mitigasi_arr);

		$this->data['rowheader1'] = $this->data['row'];

		$this->_onDetail($id, $id_mitigasi_arr);

		$this->isLock();
		if (!$this->data['row'])
			$this->NoData();

		$this->_afterDetail($id, $id_mitigasi_arr);

		$this->data['buttonMenu'] .= "<button type='button' class='btn btn-sm btn-primary' onclick=\"goPrint('')\"><i class='bi bi-printer'></i> Print</button>" .
			'<script>
				function goPrint(){
		    	    window.open("' . base_url($ci->page_ctrl . "/panelbackend/risk_penanganan_mitigasi/go_print_nonrutin/$id/$id_mitigasi_arr") . '/?"+$("#main_form").serialize(),"_blank");
				}
			</script>';
		$this->View($this->viewdetail);
	}

	public function go_print_nonrutin($id, $id_mitigasi = null)
	{
		$this->data['page_title'] = '';
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout5";
		$this->data['no_header'] = true;

		$this->data['row'] = $this->model->GetByPk($id);
		// $this->conn->debug=1;
		if ($this->data['row']['id_kategori_proyek'])
			$this->data['row']['area_dampak'] = $this->conn->GetOne("select kode+' '+nama from mt_risk_taksonomi_area where deleted_date is null and id_taksonomi_area = " . $this->data['row']['id_kategori_proyek']);
		// $this->data['row']['mitigasi'] = $this->conn->GetArray("select * from risk_mitigasi a where exists(select 1 from risk_mitigasi_risiko b where a.id_mitigasi = b.id_mitigasi and b.id_risiko = " . $this->conn->escape($id) . ")");
		$this->data['row']['mitigasi'] = $this->conn->GetArray("select * from risk_mitigasi a where deleted_date is null and id_mitigasi = " . $this->conn->escape($id_mitigasi));
		$this->data['row']['control'] = $this->conn->GetList("select id_control idkey, nama val from risk_control a where exists(select 1 from risk_control_risiko b where b.deleted_date is null and a.id_control = b.id_control and b.id_risiko = " . $this->conn->escape($id) . ")");
		$this->data['row']['program'] = $this->conn->GetArray("select * from risk_mitigasi_program where deleted_date is null and id_risiko = " . $this->conn->escape($id) . " and id_mitigasi = ".$this->conn->escape($id_mitigasi)." order by id_mitigasi,id_mitigasi_program");
		foreach ($this->data['row']['program'] as &$g) {
			$g['pic'] = $this->conn->GetOne("select nama from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($g['penanggung_jawab']));
		}
		$this->data['row']['tgl_penyelesaiaan'] = $this->conn->GetOne("select max(end_date)tgl_penyelesaiaan from risk_mitigasi_program where deleted_date is null and id_risiko = " . $this->conn->escape($id));
		$this->data['row']['keterangan'] = $this->conn->GetOne("select keterangan from risk_mitigasi_risiko  where deleted_date is null and id_risiko = " . $this->conn->escape($id) . " and id_mitigasi = " . $this->conn->escape($id_mitigasi));


		$this->data['rowheader']  = $this->riskscorecard->GetByPk($this->data['row']['id_scorecard']);
		if ($this->data['rowheader']['id_sasaran_proyek'])
			$this->data['rowheader']['sasaran'] = $this->conn->GetOne("select nama from risk_sasaran where deleted_date is null and id_sasaran = " . $this->conn->escape($this->data['rowheader']['id_sasaran_proyek']));
		$this->data['rowheader']['risk_owner'] = $this->conn->GetOne("select nama from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($this->data['rowheader']['owner']));

		// dpr($this->data['row'],1);
		// dpr($this->data['rowheader'],1);
		foreach($this->data['row']['program'] as $gp){
			$pic[$gp['pic']] =  $gp['pic'];
		}
		$this->data['row']['pic'] = $pic;
		// dpr($this->data['row'],1);


		$this->_afterDetail($id);

		$this->viewprint = "panelbackend/print_nonrutin";
		$this->View($this->viewprint);
	}

	protected function _beforeDetail($id_risiko = null, $id_mitigasi_arr = null)
	{
		$id = $this->data['row']['id_scorecard'];
		$this->data['row']['id_mitigasi_program'] = $id_mitigasi_arr;

		$data = $this->conn->GetRow("select nama as nama_mitigasi, nomor as nomor_mitigasi, sasaran as sasaran_mitigasi from risk_mitigasi where deleted_date is null and id_mitigasi = " . $this->conn->escape($id_mitigasi_arr));
		$data['nama_kegiatan'] = $this->conn->GetOne("select nama as nama_kegiatan from risk_sasaran a where exists(select 1 from risk_risiko b where b.deleted_date is null and a.id_sasaran = b.id_sasaran and b.id_risiko = " . $this->conn->escape($id_risiko) . " )");
		$this->data['row'] = array_merge($this->data['row'], $data);


		#mengambil dari model karena sudah difilter sesuai akses
		$this->load->model("Risk_scorecardModel", 'riskscorecard');
		$this->data['rowheader']  = $this->riskscorecard->GetByPk($id);
		if (!$this->data['rowheader'])
			$this->NoData();

		$owner = $this->data['rowheader']['owner'];

		if ($owner) {
			// $this->data['ownerarr'][$owner] = $this->conn->GetOne("select concat(nama,' (',coalesce(id_unit,''),')') from mt_sdm_jabatan where id_jabatan = " . $this->conn->escape($owner));
			$this->data['ownerarr'][$owner] = $this->conn->GetOne("select concat(nama,' (',coalesce(id_unit,''),')') from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($owner));

			$this->load->model("Risk_kegiatanModel", 'kegiatan');

			if ($this->post['id_sasaran'])
				$id_sasaran = $this->post['id_sasaran'];
			elseif ($id_risiko)
				$id_sasaran = $this->conn->GetOne("select id_sasaran from risk_risiko where deleted_date is null and id_risiko = " . $this->conn->escape($id_risiko));

			$this->data['mtkegiatanarr'] = $this->kegiatan->GetCombo($id_sasaran);

			$this->load->model("Risk_sasaranModel", "msasaran");

			$this->data['sasaranarr'] = $this->msasaran->GetCombo($owner);

			$this->data['sasaranarr'][$id_sasaran] = $this->msasaran->GetNama($id_sasaran);
		}

		$this->data['add_param'] .= $id_risiko;
	}

	protected function _afterDetail($id, $id_mitigasi_arr = null)
	{
		$this->data['editedheader1'] = $this->data['edited'];

		$this->data['rowheader1'] = $this->data['row'];

		if ($id) {
			$this->data['mode'] = 'edit_detail';
		}
		if (!$this->data['edited']) {
			if ($this->data['rowheader']['rutin_non_rutin'] !== 'nonrutin') {
				if($this->access_role['go_print'])
				$this->data['buttonMenu'] .= "<button type='button' class='btn btn-sm btn-primary' onclick=\"goPrint('$id','$id_mitigasi_arr')\"><i class='bi bi-printer'></i> Print</button>" .
					'<script>
					function goPrint(id,id_m){
		    		    window.open("' . base_url($ci->page_ctrl . "/panelbackend/risk_penanganan_mitigasi/go_print") . '/"+id+"/"+id_m,"_blank");
					}
				</script>';
			} else {
				if($this->access_role['go_print_nonrutin'])
				$this->data['buttonMenu'] .= "<button type='button' class='btn btn-sm btn-primary' onclick=\"goPrint('')\"><i class='bi bi-printer'></i> Print</button>" .
					'<script>
					function goPrint(){
						window.open("' . base_url($ci->page_ctrl . "/panelbackend/risk_penanganan_mitigasi/go_print_nonrutin/$id/$id_mitigasi_arr") . '/?"+$("#main_form").serialize(),"_blank");
					}
				</script>';
			}
		}
	}

	protected function _afterUpdate($id, $id_mitigasi_arr = null)
	{
		$ret = $this->_afterInsert($id, $id_mitigasi_arr);

		return $ret;
	}

	protected function _afterInsert($id, $id_mitigasi_arr = null)
	{
		$ret = true;
		if ($this->post['keterangan']) {
			$ret = $this->conn->goUpdate("risk_mitigasi_risiko", array("keterangan" => $this->post['keterangan']), " id_risiko = " . $this->conn->escape($id) . " and id_mitigasi = " . $this->conn->escape($id_mitigasi_arr));
		}

		return $ret;
	}

	protected function _onDetail($id = null, $id_mitigasi_arr = null, &$record = array())
	{
		$this->_accessTask($this->data['rowheader'], $this->data['row'], $this->data['edited']);
		$this->data['bulan'] = bulannum();

		if (!$this->data['row']['mitigasi']) {
			// $rows = $this->conn->GetArray("select * 
			// 	from risk_mitigasi
			// 	where id_risiko = " . $this->conn->escape($id));
			$rows = $this->conn->GetArray("select * 
				from risk_mitigasi_program
				where deleted_date is null and id_mitigasi = " . $this->conn->escape($id_mitigasi_arr) . "
				and id_risiko = " . $this->conn->escape($id) . "
			");

			$this->data['prioritas'] = $this->conn->GetList("select id_prioritas as idkey, nama+' '+warna val from mt_prioritas where deleted_date is null and");

			$this->data['row']['mitigasi'] = array();
			foreach ($rows as $r) {
				$this->data['row']['mitigasi'][] = $r;
			}
			foreach ($this->data['row']['mitigasi'] as &$f) {
				$f['prioritasarr'] = $this->data['prioritas'];
			}
		}
		if (!$this->data['row']['keterangan'])
			$this->data['row']['keterangan'] = $this->conn->GetOne("select keterangan from risk_mitigasi_risiko  where deleted_date is null and id_risiko = " . $this->conn->escape($id) . " and id_mitigasi = " . $this->conn->escape($id_mitigasi_arr));
	}

	public function go_print($id = null, $id_mitigasi_arr = null)
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout5";
		$this->viewprint = "panelbackend/risk_penanganan_mitigasidetail_print";
		$this->data['no_header'] = true;

		$this->data['row'] = $this->model->GetByPk($id);
		$data = $this->conn->GetRow("select nama as nama_mitigasi, nomor as nomor_mitigasi, sasaran as sasaran_mitigasi from risk_mitigasi where deleted_date is null and id_mitigasi = " . $this->conn->escape($id_mitigasi_arr));
		$data['nama_kegiatan'] = $this->conn->GetOne("select nama as nama_kegiatan from risk_sasaran a where exists(select 1 from risk_risiko b where b.deleted_date is null and a.id_sasaran = b.id_sasaran and b.id_risiko = " . $this->conn->escape($id) . " )");
		$this->data['row'] = array_merge($this->data['row'], $data);
		$this->data['bulan'] = ListBulan();

		$this->_beforeDetail($id, $id_mitigasi_arr);
		$this->_onDetail($id, $id_mitigasi_arr);

		if ($this->data['rowheader']['owner']) {
			#nama dan jabatan sendiri
			$this->data['rowheader']['atasan0'] = $this->conn->GetRow("
			select a.nama jabatan, c.name nama
			from 
			    mt_sdm_jabatan a 
			    join public_sys_user_group b on b.id_jabatan =a.id_jabatan 
			    join public_sys_user c on c.user_id = b.user_id
			where a.deleted_date is null and a.id_jabatan = " . $this->conn->escape($this->data['rowheader']['owner']));

			#nama dan jabatan atasan
			$this->data['rowheader']['atasan1'] = $this->conn->GetRow("
			select a.nama jabatan, c.name nama
			from 
			    mt_sdm_jabatan a 
			    join public_sys_user_group b on b.id_jabatan =a.id_jabatan 
			    join public_sys_user c on c.user_id = b.user_id
			where a.deleted_date is null and a.id_jabatan = (select id_jabatan_parent
			from mt_sdm_jabatan where id_jabatan = " . $this->conn->escape($this->data['rowheader']['owner']) . ")");

			#nama dan jabatan atasan dari atasan
			if ($this->data['rowheader']['atasan1'])
				$this->data['rowheader']['atasan2'] = $this->conn->GetRow("
				select a.nama jabatan, c.name nama
				from 
				    mt_sdm_jabatan a 
				    join public_sys_user_group b on b.id_jabatan =a.id_jabatan 
				    join public_sys_user c on c.user_id = b.user_id
				where a.deleted_date is null and a.id_jabatan = (select id_jabatan_parent
				from mt_sdm_jabatan where id_jabatan = " . $this->conn->escape($this->data['rowheader']['atasan1']['id_jabatan']) . ")");
		}
		$this->data['row']['mitigasi'] = $this->atur_tgl($this->data['row']['mitigasi']);
		$this->View($this->viewprint);
	}
	public function atur_tgl($data)
	{
		foreach ($data as $g) {
			$date[$g['id_mitigasi_program']]['start'] = $g['start_date'];
			$date[$g['id_mitigasi_program']]['end'] = $g['end_date'];
		}

		if ($data)
			foreach ($date as &$g) {
				$start = date("m", strtotime($g['start']));
				$end = date("m", strtotime($g['end']));
				$now_m = date('m');
				foreach (ListBulan() as $b => $u) {
					$current = date('m', mktime(0, 0, 0, $b, 10));
					if (($current >= $start) && ($current <= $end)) {
						$g['bulan'][$b] = $b;
					}
				}
			}

		foreach ($data as &$f) {
			$f['bulan'] = $date[$f['id_mitigasi_program']]['bulan'];
		}

		return $data;
	}
}
