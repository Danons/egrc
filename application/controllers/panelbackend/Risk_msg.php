<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Risk_msg extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/risk_msglist";
		$this->viewdetail = "panelbackend/risk_msgdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout2";

		$this->data['page_title'] = 'Tambah Pengumuman Baru';
		$this->data['edited'] = true;

		$this->load->model("Risk_msgModel", "model");
		$this->load->model("Risk_scorecardModel", "mscorecard");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	public function Index($page = 0)
	{

		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

		$this->_beforeDetail($id);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'] && $id)
			$this->NoData();

		unset($this->data['mtjeniskajianrisikoarr']['']);

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");


		if ($this->post && $this->post['act'] <> 'change') {
			if (!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id);

			$this->data['row'] = array_merge($this->data['row'], $record);
			$this->data['row'] = array_merge($this->data['row'], $this->post);
		}

		$want_evaluasi = false;

		if ($this->data['row']['open_evaluasi'] == '0' or $this->data['row']['open_evaluasi'] == '1')
			$want_evaluasi = $this->data['row']['open_evaluasi'];



		if (1 /*!$_SESSION[SESSION_APP]['tgl_efektif']*/)
			$_SESSION[SESSION_APP]['tgl_efektif'] = date('Y-m-d');

		$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

		foreach ($this->data['mtjeniskajianrisikoarr'] as $id_kajian_risiko => $label) {
			$this->data['rowscorecardsarr'][$id_kajian_risiko] = $this->mscorecard->GetList($id_kajian_risiko, $tgl_efektif, null, true, null, $want_evaluasi);
		}

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {

			$this->_isValid($record, false);

			$this->_beforeEdit($record, $id);

			$this->_setLogRecord($record, $id);

			$this->model->conn->StartTrans();


			$return = $this->_beforeInsert($record);

			if ($return) {
				$return = $this->model->Insert($record);
				$id = $return['data'][$this->pk];
				$msg = $return['data']["msg"];
			}

			if ($return['success']) {

				$this->log("menambah", $record);
				$return1 = $this->_afterInsert($id, $msg);
				if (!$return1) {
					$return = false;
				}
			}

			$this->model->conn->CompleteTrans();

			if ($return['success']) {

				$this->_afterEditSucceed($id);

				SetFlash('suc_msg', "Pengumuman telah di publish");
				redirect("$this->page_ctrl/index");
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

	protected function _afterInsert($id = null, $msg = null)
	{
		$return = $this->conn->Execute("update risk_msg_penerima where deleted_date is null and id_msg = " . $this->conn->escape($id));

		if (!($this->post['id_scorecard']) or !$id or !$msg)
			return false;

		$this->conn->escape_string($this->post['id_scorecard']);

		$idscorecardarr = implode(",", $this->post['id_scorecard']);

		if ($this->data['row']['open_evaluasi'] == '0' or $this->data['row']['open_evaluasi'] == '1') {
			$return = $this->conn->Execute("update risk_scorecard 
				set open_evaluasi = " . $this->conn->escape($this->data['row']['open_evaluasi']) . "
				where id_scorecard in ($idscorecardarr)");
		}

		#mengambil data LDAP
		$pejabatarr = $this->conn->GetArray("select 
			u.user_id, coalesce(p.email, u.email) as email 
			from public_sys_user u
			left join public_sys_user_group sug on u.user_id = sug.user_id
			join risk_scorecard sc on coalesce(sug.id_jabatan, u.id_jabatan) = sc.owner
			join mt_sdm_jabatan j on coalesce(sug.id_jabatan, u.id_jabatan) = j.id_jabatan
			left join mt_sdm_pegawai p on j.position_id = p.position_id
			where u.deleted_date is null and sc.id_scorecard in ($idscorecardarr) 
			and u.is_manual = 0
			and u.is_active = 1
			and coalesce(p.email, u.email) is not null and is_notification = '1'");


		#mengambil data LDAP
		$pejabatarr1 = $this->conn->GetArray("select 
			u.user_id, coalesce(p.email, u.email) as email 
			from public_sys_user u
			left join public_sys_user_group sug on u.user_id = sug.user_id
			join risk_scorecard_user sc on coalesce(sug.id_jabatan, u.id_jabatan) = sc.id_jabatan
			join mt_sdm_jabatan j on coalesce(sug.id_jabatan, u.id_jabatan) = j.id_jabatan
			left join mt_sdm_pegawai p on j.position_id = p.position_id
			where u.deleted_date is null and sc.id_scorecard in ($idscorecardarr) 
			and u.is_manual = 0
			and u.is_active = 1
			and coalesce(p.email, u.email) is not null and is_notification = '1'");

		$pejabatarr2 = $this->conn->GetArray("select 
			u.user_id, u.email as email 
			from public_sys_user u
			left join public_sys_user_group sug on u.user_id = sug.user_id
			join risk_scorecard sc on coalesce(sug.id_jabatan, u.id_jabatan) = sc.owner
			join mt_sdm_jabatan j on coalesce(sug.id_jabatan, u.id_jabatan) = j.id_jabatan
			where u.deleted_date is null and sc.id_scorecard in ($idscorecardarr) 
			and u.is_manual = 1
			and u.is_active = 1
			and u.email is not null and is_notification = '1'");

		$pejabatarr3 = $this->conn->GetArray("select 
			u.user_id, u.email as email 
			from public_sys_user u
			left join public_sys_user_group sug on u.user_id = sug.user_id
			join risk_scorecard_user sc on coalesce(sug.id_jabatan, u.id_jabatan) = sc.id_jabatan
			join mt_sdm_jabatan j on coalesce(sug.id_jabatan, u.id_jabatan) = j.id_jabatan
			where u.deleted_date is null and sc.id_scorecard in ($idscorecardarr) 
			and u.is_manual = 1
			and u.is_active = 1
			and u.email is not null and is_notification = '1'");

		if (!is_array($pejabatarr))
			$pejabatarr = array();

		if (!is_array($pejabatarr1))
			$pejabatarr1 = array();

		if (!is_array($pejabatarr2))
			$pejabatarr2 = array();

		if (!is_array($pejabatarr3))
			$pejabatarr3 = array();

		$pejabatarr = array_merge($pejabatarr, $pejabatarr1);
		$pejabatarr = array_merge($pejabatarr, $pejabatarr2);
		$pejabatarr = array_merge($pejabatarr, $pejabatarr3);

		if (is_array($pejabatarr)) {
			$emails = array();
			foreach ($pejabatarr as $r) {
				if (!$return)
					break;

				if ($r['user_id'] && $r['email']) {
					$cek = $this->conn->GetOne("select 1 
						from risk_msg_penerima 
						where deleted_date is null and id_msg = " . $this->conn->escape($id) . " 
						and id_user = " . $this->conn->escape($r['user_id']));

					if ($cek)
						continue;

					$emails[] = $r['email'];

					$record = array();
					$record['id_msg'] = $id;
					$record['id_user'] = $r['user_id'];

					$sql = $this->conn->InsertSQL("risk_msg_penerima", $record);

					if ($sql) {
						$return = $this->conn->Execute($sql);
					}
				}
			}

			if ($return)
				$this->SendEmailNotif1($emails, $msg, $id);
		}

		return $return;
	}
	protected function SendEmailNotif1($recipientsarr, $isi = "", $id_msg)
	{

		if (!is_array($recipientsarr))
			$recipientsarr = array($recipientsarr);

		$recipientsarr = array_unique($recipientsarr);

		$body = "Salam
		<br/>
		<br/>
		<b>Pengumuman Manajemen Risiko : </b><br/>
		<i>" . $isi . "</i>
		<br/>" .
			"<a href='" . site_url("panelbackend/home/msg/$id_msg") . "'>BUKA APLIKASI</a>";

		$this->curl("panelbackend/publ1c/send_email", array(
			'subject' => "Pengumuman Manajemen Risiko",
			'body' => $body,
			'recipients' => implode(",", $recipientsarr),
		));
	}

	protected function Record($id = null)
	{
		return array(
			'msg' => $this->post['msg']
		);
	}

	protected function Rules()
	{
		return array(
			"msg" => array(
				'field' => 'msg',
				'label' => 'Pengumuman',
				'rules' => "required|max_length[4000]",
			),
			"id_scorecard" => array(
				'field' => 'id_scorecard[]',
				'label' => 'Kajian Risiko',
				'rules' => "required",
			),
			"open_evaluasi" => array(
				'field' => 'open_evaluasi',
				'label' => 'Buka Evaluasi Risiko',
				'rules' => "max_length[1]",
			),
		);
	}

	public function Detail($id = null)
	{
		die();
	}

	public function Edit($id = null)
	{
		die();
	}

	public function Add()
	{
		die();
	}

	public function Delete($id = null)
	{
		die();
	}
}
