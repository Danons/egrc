<?php
defined('BASEPATH') or exit('No direct script access allowed');

class _adminController extends _Controller
{
	public $viewprint = "panelbackend/listprint";
	public $access_role = array();
	public $access_role_custom = array();
	public $page_escape = array('panelbackend/login', 'panelbackend/publ1c', 'panelbackend/ajax', 'panelbackend/filemanager');
	public $is_administrator = false;
	public $is_coordinator = false;
	public $is_owner = false;
	public $is_review = false;
	public $is_bod = false;
	public $list_order = '';
	public $private = true;
	public $limit = 10;
	public $limit_arr = array('10', '30', '50', '100');
	public function __construct()
	{
		parent::__construct();

		$this->SetConfig();

		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";


		$this->load->model('AuthModel', 'auth');
		$this->load->library("UI");

		$this->sso = $this->config->item('sso');

		$this->helper("a");
		$this->helper("s");

		//$this->conn->debug = 1;

		if ($_GET['debug'] == '1') {
			$this->conn->debug = 1;
		}

		$this->SetPlugin('datepicker');

		$this->SetAccessRole();

		$this->init();

		$this->InitAdmin();

		$this->data['access_role'] = $this->access_role;
	}

	protected function SetConfig()
	{
		$sql = "select * from public_sys_setting where deleted_date is null ";
		$rows = $this->conn->GetArray($sql);

		$configarr = array();
		foreach ($rows as $r) {
			if (strstr($r['nama'], '.') !== false) {
				list($nama, $nama1) = explode(".", $r['nama']);
				$configarr[$nama][$nama1] = trim($r['isi']);
			} else {
				$configarr[$r['nama']] = trim($r['isi']);
			}
		}

		foreach ($configarr as $idkey => $value) {
			$this->config->set_item($idkey, $value);
		}


		$this->data['collapse'] = $configarr['collapse'];
	}

	protected function init()
	{
		$this->data['show_button'] = true;
		// $this->data['sekarang'] = $this->conn->GetOne("select sysdate()");
		$this->data['sekarang'] = $this->conn->GetOne("select now()");


		// dpr($_SESSION[SESSION_APP]['owner_jabatan'], 1);
		if ($_SESSION[SESSION_APP]['owner_jabatan'])
			$this->data['owner'] = $_SESSION[SESSION_APP]['owner_jabatan'];
		else
			$this->data['owner'] = '0';
	}


	protected function InitAdmin()
	{

		$this->data['listjk'] = array('' => '-pilih-', '1' => 'Laki-laki', '2' => 'Perempuan');

		$this->load->model("Mt_sdm_jabatanModel", 'mjabatan');

		$this->data['menurunkanrr'] = array('K' => 'Kemungkinan', 'D' => 'Dampak', 'K&D' => 'Kemungkinan & Dampak');

		$this->load->model("Mt_risk_dampakModel", "mtdampakrisiko");
		$mtdampakrisiko = $this->mtdampakrisiko;
		$this->data['mtdampakrisikoarr'] = $mtdampakrisiko->GetComboDK();

		$this->load->model("Mt_risk_kemungkinanModel", "mtkemungkinanrisiko");
		$mtkemungkinanrisiko = $this->mtkemungkinanrisiko;
		$this->data['mtkemungkinanrisikoarr'] = $mtkemungkinanrisiko->GetComboDK();

		$this->data['mtriskkemungkinan'] = $this->conn->GetArray("select * from mt_risk_kemungkinan where deleted_date is null order by rating desc");

		$this->data['mtriskdampak'] = $this->conn->GetArray("select * from mt_risk_dampak where deleted_date is null order by rating asc");

		$this->data['mtriskmatrix'] = $this->conn->GetArray("select 
		mrm.*, mrt.nama, mrt.warna
		from mt_risk_matrix mrm
		join mt_risk_tingkat mrt on mrt.id_tingkat = mrm.id_tingkat where mrm.deleted_date is null");

		$this->load->model("Mt_risk_matrixModel", "mtriskmatrix");
		$mtriskmatrix = $this->mtriskmatrix;
		$this->data['mtriskmatrixarr'] = $mtriskmatrix->getMatrix();
		$this->data['riskmatrixtingkat'] = $mtriskmatrix->getTingkat();
		$this->data['riskapertite'] = $mtriskmatrix->minRiskApertite();

		$this->load->model("Mt_risk_tingkatModel", 'mttingkatdampak');
		$this->data['tingkatrisikoarr'] = $this->mttingkatdampak->GArray();
		$this->data['mttingkatdampakarr'] = $this->mttingkatdampak->GetCombo();
		$this->data['mttingkatdampakarr1'] = $this->data['mtriskmatrixarr'];
		$this->data['risk_opp'] = array(-1, 1);



		$this->data['runitnonnurinarr'] = array("" => "", "rutin" => "Rutin", "nonrutin" => "Non Rutin");
		$this->data['jenisrunitnonnurinarr'] = array("" => "", "rutin" => "Rutin", "nonrutin" => "Non Rutin", "proyek" => "Proyek");
		$this->data['penanganan_pencegahanarr'] = array("" => "", "penanganan" => "Penanganan", "pencegahan" => "Pencegahan");

		// $this->load->model("Risk_sasaranModel", 'risksasaran');
		// $this->data['risksasaranarr'] = $this->risksasaran->GetCombo();

		// $this->load->model("Risk_penyebabModel", 'riskpenyebab');
		// $this->data['riskpenyebabarr'] = $this->riskpenyebab->GetCombo();

		// $this->load->model("Risk_dampakModel", 'riskdampak');
		// $this->data['riskdampakarr'] = $this->riskdampak->GetCombo();

		// $this->load->model("Risk_mitigasiModel", 'riskmitigasi');
		// $this->data['mitigasiarr'] = $this->riskmitigasi->GetCombo();

		// $this->load->model("Risk_controlModel", 'riskcontrol');
		// $this->data['riskcontrolarr'] = $this->riskcontrol->GetCombo();

		// $this->data['prioritaswarna'] = $this->conn->GetList("select id_prioritas as idkey, warna val from mt_prioritas");

		// $this->data['operasionalarr'] = array("" => 'Pilih') + $this->conn->GetList("select id_aspek_lingkungan as idkey, kode+' '+nama as val from mt_aspek_lingkungan");
		##PELUANG
		##PELUANG


		$this->load->model("Mt_opp_dampakModel", "mtdampakpeluang");
		$mtdampakpeluang = $this->mtdampakpeluang;
		$this->data['mtdampakpeluangarr'] = $mtdampakpeluang->GetComboDK();

		$this->load->model("Mt_opp_kemungkinanModel", "mtkemungkinanpeluang");
		$mtkemungkinanpeluang = $this->mtkemungkinanpeluang;
		$this->data['mtkemungkinanpeluangarr'] = $mtkemungkinanpeluang->GetComboDK();

		$this->data['mtoppkemungkinan'] = $this->conn->GetArray("select * from mt_opp_kemungkinan where deleted_date is null order by id_kemungkinan desc");

		$this->data['mtoppdampak'] = $this->conn->GetArray("select * from mt_opp_dampak where deleted_date is null order by id_dampak desc");

		$this->data['mtoppmatrix'] = $this->conn->GetArray("select mrm.*, mrt.nama, mrt.warna
			from mt_opp_matrix mrm
			join mt_opp_tingkat mrt on mrt.id_tingkat = mrm.id_tingkat where mrm.deleted_date is null");

		$this->load->model("Mt_opp_matrixModel", "mtoppmatrix");
		$mtoppmatrix = $this->mtoppmatrix;
		$this->data['mtoppmatrixarr'] = $mtoppmatrix->getMatrix();
		$this->data['oppmatrixtingkat'] = $mtoppmatrix->getTingkat();
		$this->data['oppapertite'] = $mtoppmatrix->minoppApertite();

		$this->load->model("Mt_opp_tingkatModel", 'mttingkatdampakopp');
		$this->data['tingkatpeluangarr'] = $this->mttingkatdampakopp->GArray();
		$this->data['mttingkatdampakpeluangarr'] = $this->mttingkatdampakopp->GetCombo();
		$this->data['mttingkatdampakpeluangarr1'] = $this->data['mtoppmatrixarr'];

		$this->load->model("Mt_status_pengajuanModel", "mtstatus");
		$this->data['mtstatusarr'] = $this->mtstatus->GetCombo();

		if (1 /*!$_SESSION[SESSION_APP]['tgl_efektif']*/)
			$_SESSION[SESSION_APP]['tgl_efektif'] = date('Y-m-d');

		$this->data['tgl_efektif'] = $_SESSION[SESSION_APP]['tgl_efektif'];

		if ($this->post['act'] == 'set_efektif') {
			$_SESSION[SESSION_APP]['tgl_efektif'] = $this->post['idkey'];
			$_SESSION[SESSION_APP][$this->page_ctrl]['tgl_efektif'] = $this->post['idkey'];
			redirect(current_url());
		}

		if (strstr($this->post['act'], 'task') !== false) {
			// $this->conn->debug=1;
			$page = str_replace("task", "", $this->post['act']);
			if ($page == 'mitigasi') {
				$this->_actionKonfirmasi();
			} else {
				// $this->_actionTaskRisiko();
				$this->_actionTaskScorecard();
			}
			// die;
		}

		if ($this->post['act'] == 'cari_risiko') {
			$_SESSION[SESSION_APP]['cari_risiko'] = $this->post['cari_risiko'];
			redirect("panelbackend/home/cari");
		}

		if ($this->post['act'] == 'unlock') {
			if (!$this->access_role['view_all'] && !$this->access_role['view_all_unit'])
				$this->Error403();

			$cek = $this->unlock();

			if ($cek)
				SetFlash('suc_msg', "Data telah di unlock");
			else
				SetFlash('err_msg', "Data gagal di unlock");

			redirect(current_url());
		}

		$this->data['tingkataggregasiarr'] = array(
			'' => '',
			'1' => 'RJPP',
			'2' => 'RKAP/RJPU',
			'3' => 'Unit/Divisi/Bidang/RKAU',
			'4' => 'Fungsi per unit'
		);

		$this->SetPlugin('datepicker');
	}

	protected function SetAccess($pagearr = array())
	{
		if (!is_array($pagearr))
			$pagearr = array($pagearr);

		foreach ($pagearr as $v) {
			$this->access_role_custom[$v] = $this->auth->GetAccessRole($v);
		}
	}

	protected function View($view = '')
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

			echo '<script>$(function(){rupiah();});</script>';

			$this->Plugin();

			echo $this->data['add_plugin'];

			echo FlashMsg();

			echo $this->load->view($view, $this->data, true);
			exit;
		} else {
			if (!empty($this->layout)) {
				$this->data['content1'] = $this->PartialView($view, true);
				parent::View($this->layout);
			} else {
				parent::View($view);
			}
		}
	}
	// set access for url and action
	protected function SetAccessRole()
	{
		// ceck referer from host or not
		if (
			static::$referer == true and
			str_replace('/', '', str_replace('panelbackend', '', str_replace('index.php', '', $_SERVER['HTTP_REFERER'])))
			<>
			str_replace('/', '', str_replace('panelbackend', '', str_replace('index.php', '', base_url())))
		) {

			$this->Error404();
			exit();
		}

		$this->access_role['view_all'] = $this->data['view_all'] = $this->Access("view_all", "main");
		$this->access_role['view_all_unit'] = $this->data['view_all_unit'] = $this->Access("view_all_unit", "main");

		if (in_array($this->page_ctrl, $this->page_escape))
			return true;

		// set private area
		if ($this->private) {
			// ceck login
			if (!$_SESSION[SESSION_APP]['login']) {
				$_SESSION[SESSION_APP]['curr_page'] = uri_string();
				redirect('panelbackend/login', 'client');
			}
		}

		if (!$_SESSION[SESSION_APP]['group_id'] && $_SESSION[SESSION_APP]['akses']) {
			redirect('panelbackend/login/akses', 'client');
		}


		if ($this->page_ctrl == 'panelbackend/home' && !empty($_SESSION[SESSION_APP]['user_id']))
			return true;

		$this->is_super_admin = false;

		if ($_SESSION[SESSION_APP]['user_id'] == 1)
			$this->is_super_admin = true;

		if ($this->page_ctrl == 'panelbackend/page' or $this->page_ctrl == 'panelbackend/pageone') {
			$this->access_role = $this->auth->GetAccessRole('panelbackend/page');
		} else {
			// $this->conn->debug = 1;
			$this->access_role = $this->auth->GetAccessRole($this->page_ctrl);
			// dpr($this->access_role, 1);
		}

		$this->access_role_custom[$this->page_ctrl] = $this->access_role;

		if (!$this->access_role[$this->mode]) {
			$str = '';

			//if (ENVIRONMENT == 'development')
			$str = "akses : " . print_r($this->access_role, true);

			$this->Error403($str);
			exit();
		}
	}

	protected function _getList($page = 0)
	{
		$this->_resetList();

		$this->arrNoquote = $this->model->arrNoquote;

		$param = array(
			'page' => $page,
			'limit' => $this->_limit(),
			'order' => $this->_order(),
			'filter' => $this->_getFilter()
		);

		if ($this->post['act'] && $this->post['act'] <> 'save' && $this->post['act'] <> 'set_value') {

			if ($this->data['add_param']) {
				$add_param = '/' . $this->data['add_param'];
			}
			redirect(str_replace(strstr(current_url(), "/index$add_param/$page"), "/index{$add_param}", current_url()));
		}

		$respon = $this->model->SelectGrid(
			$param
		);

		return $respon;
	}

	protected function _getListPrint()
	{
		$this->_resetList();

		$this->arrNoquote = $this->model->arrNoquote;

		$param = array(
			'order' => $this->_order(),
			'filter' => $this->_getFilter()
		);

		$respon = $this->model->SelectGridPrint($param);

		return $respon;
	}

	protected function _resetList()
	{
		if ($this->post['act'] == 'list_reset') {
			unset($_SESSION[SESSION_APP][$this->page_ctrl]['list_limit']);
			unset($_SESSION[SESSION_APP][$this->page_ctrl]['list_sort']);
			unset($_SESSION[SESSION_APP][$this->page_ctrl]['list_filter']);
			unset($_SESSION[SESSION_APP][$this->page_ctrl]['list_search']);
			unset($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']);
		}
	}

	protected function _limit()
	{
		if ($this->post['act'] == 'list_limit' && $this->post['list_limit']) {
			$_SESSION[SESSION_APP][$this->page_ctrl]['list_limit'] = $this->post['list_limit'];
		}

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['list_limit']) {
			$this->limit = $_SESSION[SESSION_APP][$this->page_ctrl]['list_limit'];
		}

		if ($this->limit == 'Semua')
			$this->limit = -1;

		return $this->limit;
	}

	protected function _order()
	{

		if ($this->post['act'] == 'list_sort' && $this->post['list_sort']) {

			$_SESSION[SESSION_APP][$this->page_ctrl]['list_order'] = $this->post['list_order'];
			$_SESSION[SESSION_APP][$this->page_ctrl]['list_sort'] = $this->post['list_sort'];
		}

		$order = "";

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['list_sort']) {
			$order .= $_SESSION[SESSION_APP][$this->page_ctrl]['list_sort'];
		}

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['list_order'] && $order) {
			$order .= ' ' . $_SESSION[SESSION_APP][$this->page_ctrl]['list_order'];
		}

		$this->data['list_sort'] = $_SESSION[SESSION_APP][$this->page_ctrl]['list_sort'];
		$this->data['list_order'] = $_SESSION[SESSION_APP][$this->page_ctrl]['list_order'];

		replaceSingleQuote($this->list_order);

		if ($this->list_order && $order)
			$this->list_order .= ", " . $order;
		elseif ($order)
			$this->list_order = $order;

		if (!$this->list_order) {
			if ($this->model->order_default)
				return $this->model->order_default;
			else
				return $this->model->pk . " desc ";
		}

		if ($this->list_order)
			return $this->list_order;

		return null;
	}

	protected function _setFilter($filter = '')
	{
		if ($filter) {
			$this->filter .= ' and ' . $filter;
		}
	}

	protected function _getFilter()
	{
		$this->xss_clean = true;

		$this->FilterRequest();

		$filter_arr = array();

		if ($this->post['act'] == 'list_filter' && $this->post['list_filter']) {
			if (!$_SESSION[SESSION_APP][$this->page_ctrl]['list_filter']) {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'] = $this->post['list_filter'];
			} else {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'], $this->post['list_filter']);
			}
		}

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['list_filter']) {

			foreach ($_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'] as $r) {
				$idkey = $r['idkey'];
				$filter_arr1 = array();

				foreach ($r['values'] as $k => $v) {
					$k = str_replace("_____", ".", $k);

					replaceSingleQuote($v);
					replaceSingleQuote($k);
					if (!($v === '' or $v === null or $v === false))
						$filter_arr1[] = 'a.' . $idkey . " = '$v'";
				}

				$filter_str = implode(' or ', $filter_arr1);

				if ($filter_str) {
					$filter_arr[] = "($filter_str)";
				}
			}
		}

		if (!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']) {
			$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] = array();
		}

		if ($this->post['act'] == 'list_search' && $this->post['list_search_filter']) {
			if (!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']) {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] = $this->post['list_search_filter'];
			} else {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'], $this->post['list_search_filter']);
			}
		}

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']) {
			foreach ($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] as $k => $v) {
				$k = str_replace("_____", ".", $k);

				if (!($v === '' or $v === null or $v === false)) {
					replaceSingleQuote($v);
					replaceSingleQuote($k);

					$filter_arr[] = "$k='$v'";
				}
			}
		}




		if (!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search']) {
			$_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] = array();
		}

		if ($this->post['act'] == 'list_search' && $this->post['list_search']) {

			if (!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search']) {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] = $this->post['list_search'];
			} else {
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_search'], $this->post['list_search']);
			}
		}

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['list_search']) {
			foreach ($_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] as $k => $v) {
				$k = str_replace("_____", ".", $k);

				replaceSingleQuote($v);
				replaceSingleQuote($k);

				if (trim($v) !== '' && in_array($k, $this->arrNoquote)) {
					$filter_arr[] = "$k=$v";
				} else if ($v !== '') {
					$v = strtolower($v);
					$filter_arr[] = "lower($k) like '%$v%'";
				}
			}
		}

		$this->data['filter_arr'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_search'], $_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']);

		if (($filter_arr)) {
			$this->filter .= ' and ' . implode(' and ', $filter_arr);
		}

		return $this->filter;
	}

	protected function _setLogRecord(&$array, $is_update = true)
	{
		$datenow = '{{' . $this->conn->sysTimeStamp . '}}';
		$user_id = $_SESSION[SESSION_APP]['user_id'];
		if (!$is_update) {
			$array['created_date'] = $datenow;
			$array['created_by'] = $user_id;
		}
		$array['modified_date'] = $datenow;
		$array['modified_by'] = $user_id;
	}


	public function go_print()
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";
		$this->data['no_header'] = true;

		$this->data['header'] = $this->Header();

		$this->data['list'] = $this->_getListPrint();

		$this->View($this->viewprint);
	}

	public function printdetail($id_pemeriksaan, $id = null)
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";
		$this->data['no_header'] = true;

		$this->data['row'] = $this->model->GetByPk($id);

		$this->_getDetailPrint($id);

		if (!$this->data['row'])
			$this->NoData();

		$this->View($this->viewprintdetail);
	}




	protected function _isValid($record = array(), $show_error = true)
	{
		if (!is_array($this->data['rules']))
			return;

		$rules = array_values($this->data['rules']);

		$this->form_validation->set_rules($rules);
		// dpr($this->form_validation->run());
		// die;

		if (count($rules) && $this->form_validation->run() == FALSE) {
			if ($show_error) {
				$this->data['err_msg'] = validation_errors();
			}

			$this->data['row'] = array_merge($this->data['row'], $record);

			$this->_afterDetail($this->data['row'][$this->pk]);

			$this->View($this->viewdetail);
			exit();
		}
	}

	protected function Halt($msg)
	{
		if ($msg) {

			if ($this->data['err_msg'])
				$this->data['err_msg'] .= "<br/>" . $msg;
			else
				$this->data['err_msg'] = $msg;

			$this->_afterDetail($this->data['row'][$this->pk]);

			$this->View($this->viewdetail);
			exit();
		}
	}

	protected function _getDetailPrint($id) {}

	public function Add()
	{
		$this->Edit();
	}

	public function Edit($id = null)
	{

		$id = urldecode($id);
		if ($this->post['act'] == 'reset') {
			redirect(current_url());
		}

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

	public function Detail($id = null)
	{

		$id = urldecode($id);
		$this->_beforeDetail($id);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$this->_onDetail($id);

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	public function Delete($id = null)
	{

		$id = urldecode($id);
		$this->model->conn->StartTrans();

		$this->_beforeDetail($id);

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
			redirect("$this->page_ctrl");
		} else {
			SetFlash('err_msg', "Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id");
		}
	}

	protected function _beforeEdit(&$record = array(), $id)
	{
		return true;
	}

	protected function _afterEditSucceed($id = null) {}

	protected function _afterEditFailed($id = null) {}

	protected function _beforeDetail($id = null) {}

	protected function _afterDetail($id)
	{
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
	}

	protected function _beforeDelete($id = null)
	{
		$ret = true;
		if ($this->modelfile) {
			$ret = $this->conn->Execute("update {$this->modelfile->table} set deleted_date = now() where deleted_date is null and {$this->pk} = " . $this->conn->escape($id));
		}
		return $ret;
	}

	protected function _afterDelete($id)
	{
		return true;
	}

	protected function _beforeUpdate($record = array(), $id = null)
	{
		return true;
	}

	protected function _afterInsert($id)
	{
		$ret = true;

		if ($this->modelfile) {
			if (!empty($this->post['files'])) {
				foreach ($this->post['files']['id'] as $k => $v) {
					$return = $this->_updateFiles(array($this->pk => $id), $v);

					$ret = $return['success'];
				}
			}
		}

		return $ret;
	}

	protected function _afterUpdate($id)
	{
		return $this->_afterInsert($id);
	}

	protected function _beforeInsert($id = null)
	{
		return true;
	}

	protected function _onDetail($id = null)
	{
		return true;
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'nama',
				'label' => 'Kategori',
				'width' => "auto"
			),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'nama' => $this->post['nama']
		);
	}

	protected function Rules()
	{
		return array(
			'nama' => array(
				'field'   => 'nama',
				'label'   => 'Kategori',
				'rules'   => 'required'
			),
		);
	}

	public function NoData($str = 'Data tidak ditemukan.')
	{
		$this->data['error_str'] = $str;
		$this->layout = "panelbackend/layout1";
		$this->view("panelbackend/error404");
		exit();
	}

	public function Error404($str = '')
	{
		$this->data['error_str'] = $str;
		$this->layout = "panelbackend/layout1";
		$this->view("panelbackend/error404");
		exit();
	}

	public function Error403($str = '')
	{
		$this->data['error_str'] = $str;
		$this->layout = "panelbackend/layout1";
		$this->view("panelbackend/error403");
		exit();
	}

	public function Delete_all()
	{

		$return = true;

		$this->model->conn->StartTrans();

		$rows = $this->model->GArray();

		foreach ($rows as $r) {
			if (!$return)
				break;

			$id = $r[$this->pk];

			$this->_beforeDetail($id);

			$this->data['row'] = $this->model->GetByPk($id);

			$this->_onDetail($id);

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

			if ($return)
				$this->log("menghapus $id");
		}

		$this->model->conn->CompleteTrans();

		if ($return) {
			SetFlash('suc_msg', $return['success']);
			redirect("$this->page_ctrl/index");
		} else {
			SetFlash('err_msg', "Data gagal didelete");
			redirect("$this->page_ctrl/index");
		}
	}


	protected function _isValidImport($record)
	{
		$this->data['rules'] = $this->Rules();

		$rules = array_values($this->data['rules']);

		if ($record) {
			$this->form_validation->set_data($record);
		}

		$this->form_validation->set_rules($rules);

		if (count($rules) && $this->form_validation->run() == FALSE) {
			return validation_errors();
		}
	}

	public function HeaderExport()
	{
		return $this->Header();
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
						$record[$r1['name']] = (string) $sheet->getCell($col . $row)->getValue();
					elseif ($r1['type'] == 'listinverst') {
						$rk = strtolower(trim((string) $sheet->getCell($col . $row)->getValue()));
						$arr = array();
						foreach ($r1['value'] as $idkey => $value) {
							$arr[strtolower(trim($value))] = $idkey;
						}
						$record[$r1['name_ori']] = (string) $arr[$rk];
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
				$this->model->conn->trans_commit();
				SetFlash('suc_msg', $return['success']);
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

	public function export_list()
	{
		$this->load->library('PHPExcel');
		$this->load->library('Factory');
		$excel = new PHPExcel();
		$excel->setActiveSheetIndex(0);
		$excelactive = $excel->getActiveSheet();


		#header export
		$header = array(
			array(
				'name' => $this->model->pk
			)
		);
		$header = array_merge($header, $this->HeaderExport());

		$row = 1;
		$col = null;

		foreach ($header as $r) {
			if (!$col)
				$col = 'A';
			else
				$col++;

			$excelactive->setCellValue($col . $row, $r['name']);
		}

		$excelactive->getStyle('A1:' . $col . $row)->getFont()->setBold(true);
		$excelactive
			->getStyle('A1:' . $col . $row)
			->getFill()
			->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			->getStartColor()
			->setARGB('6666ff');

		#data
		$respon = $this->model->SelectGrid(
			array(
				'limit' => -1,
				'order' => $this->_order(),
				'filter' => $this->_getFilter()
			)
		);
		$rows = $respon['rows'];

		$row = 2;
		foreach ($rows as $r) {
			$col = 'A';
			foreach ($header as $r1) {
				if ($r1['type'] == 'listinverst') {
					$r[$r1['name']] = $r1['value'][$r[$r1['name_ori']]];
				}
				$excelactive->setCellValue($col . $row, $r[$r1['name']]);
				$col++;
			}
			$row++;
		}


		$objWriter = Factory::createWriter($excel, 'Excel5');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $this->ctrl . date('Ymd') . '.xls"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
		exit();
	}

	protected function ReExecuteTask($id_risiko = null, $is_send = false)
	{
		if ($is_send) {
			$rows = $this->conn->GetRows("select * from risk_task where deleted_date is null and id_risiko=" . $this->conn->escape($id_risiko) . " and is_pending = '1' order by created_date");

			foreach ($rows as $record) {
				unset($record['is_pending']);
				unset($record['id_task']);

				$this->InsertTask($record);
			}
		}

		$this->conn->Execute("update risk_task set deleted_date = now() where id_risiko=" . $this->conn->escape($id_risiko) . " and is_pending = '1'");
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

	protected function InsertTask($record)
	{
		$this->load->model("Risk_taskModel", 'modeltask');

		$created_by = $record['created_by'];

		$this->_setLogRecord($record, null);

		if ($created_by)
			$record['created_by'] = $created_by;

		$record['group_id'] = $_SESSION[SESSION_APP]['group_id'];

		$return = $this->modeltask->Insert($record);

		// if ($return['success'] && !$record['is_pending'] && $record['id_risiko']) {
		$this->SendEmailNotif($record['deskripsi'], $record['id_scorecard'], $record['id_status_pengajuan'], $record['untuk'], $return['data']['id_task']);
		// } else if ($return['success'] && $record['id_pemeriksaan']) {
		// 	$this->SendEmailNotifAudit($record['deskripsi'], $record['id_pemeriksaan'], $record['id_status_pengajuan'], $record['untuk'], $return['data']['id_task']);
		// } else {
		// 	$this->SendEmailNotifDefault($record['deskripsi'], $record['id_status_pengajuan'], $record['untuk'], $return['data']['id_task']);
		// }

		return $return;
	}

	protected function SendEmailNotifDefault($deskripsi = "", $id_status_pengajuan = null, $untuk = null, $id_task = null)
	{

		$recipientsarr = array();

		$rows = $this->auth->PenerimaByStatusAudit(null, $id_status_pengajuan, $untuk);

		foreach ($rows as $r) {
			$recipientsarr[] = $r['email'];
		}

		$body = "Dengan hormat,
		<br/>
		<br/>" .
			($id_status_pengajuan ? "<b>Status Pengajuan : </b>" . strtolower($this->data['mtstatusarr'][$id_status_pengajuan]) : "") .
			"<br/><br/>
		<i>Keterangan : " . $deskripsi . "</i>
		<br/>Klik link berikut untuk melihat lebih detail : " .
			"<a href='" . site_url("panelbackend/home/task/$id_task") . "'>Selengkapnya</a>";

		$rdeskripsi = null;
		$subject = ($id_status_pengajuan ? strtoupper($this->data['mtstatusarr'][$id_status_pengajuan]) : $rdeskripsi);

		$this->curl("panelbackend/publ1c/send_email", array(
			'subject' => $subject,
			'body' => $body,
			'recipients' => implode(",", array_unique($recipientsarr)),
		));
	}

	protected function SendEmailNotifAudit($deskripsi = "", $id_pemeriksaan = null, $id_status_pengajuan = null, $untuk = null, $id_task = null)
	{

		$recipientsarr = array();

		$rows = $this->auth->PenerimaByStatusAudit(null, $id_status_pengajuan, $untuk);

		foreach ($rows as $r) {
			$recipientsarr[] = $r['email'];
		}

		$row = $this->conn->GetRow("select r.*
		from pemeriksaan r 
		where r.deleted_date is null and id_pemeriksaan = " . $this->conn->escape($id_pemeriksaan));

		$nama = $row['nama'];

		$body = "Dengan hormat,
		<br/>
		<br/>
		<b>Hasil Audit : </b> $nama<br/>" .
			($id_status_pengajuan ? "<b>Status Pengajuan : </b>" . strtolower($this->data['mtstatusarr'][$id_status_pengajuan]) : "") .
			"<br/><br/>";
		$body .= "<i>Keterangan : " . $deskripsi . "</i>";
		$body .= "<br/>Klik link berikut untuk melihat lebih detail : " .
			"<a href='" . site_url("panelbackend/home/task/$id_task") . "'>Selengkapnya</a>";

		$subject = ($id_status_pengajuan ? strtoupper($this->data['mtstatusarr'][$id_status_pengajuan]) : $deskripsi) . " # " . $nama;

		$this->curl("panelbackend/publ1c/send_email", array(
			'subject' => $subject,
			'body' => $body,
			'recipients' => implode(",", array_unique($recipientsarr)),
		));
	}

	protected function SendEmailNotif($deskripsi = "", $id_scorecard = null, $id_status_pengajuan = null, $untuk = null, $id_task = null)
	{
		// $this->conn->debug = 1;
		$recipientsarr = array();

		$row = $this->conn->GetRow("select s.* from risk_scorecard s
			where deleted_date is null and id_scorecard=" . $this->conn->escape($id_scorecard));

		$id_scorecard = $row['id_scorecard'];
		$nama = $row['nama'];
		$id_jabatan = $row['owner'];
		// $id_unit = $row['id_unit'];

		$rows = $this->auth->PenerimaByStatus($id_status_pengajuan, $untuk, $id_jabatan);

		foreach ($rows as $r) {
			$recipientsarr[] = $r['email'];
		}

		// dpr($recipientsarr);
		// dpr($rows,1);
		$body = "Dengan hormat,
		<br/>
		<br/>
		<b>IRR : </b> $nama<br/>" .
			($id_status_pengajuan ? "<b>Status Pengajuan : </b>" . strtolower($this->data['mtstatusarr'][$id_status_pengajuan]) : "") .
			"<br/><br/>
		<i>Keterangan : " . $deskripsi . "</i>
		<br/>Klik link berikut untuk melihat lebih detail : " .
			"<a href='" . site_url("panelbackend/home/task/$id_task") . "'>Selengkapnya</a>";

		$rdeskripsi = null;
		$subject = ($id_status_pengajuan ? strtoupper($this->data['mtstatusarr'][$id_status_pengajuan]) : $rdeskripsi) . " # " . $nama;

		$this->curl("panelbackend/publ1c/send_email", array(
			'subject' => $subject,
			'body' => $body,
			'recipients' => implode(",", array_unique($recipientsarr)),
		));
	}

	protected function _actionTaskScorecard($inline = false)
	{
		$page = strstr($this->ctrl, '_', true);
		$page_ctrl = "panelbackend/" . strstr($this->ctrl, '_', true) . "_scorecard";
		/**
		 * $this->post['keterangan']['risiko']
		 * $this->post['id']['risiko']
		 * $this->post['id_status_pengajuan']['risiko']
		 */

		$keterangan = $this->post['keterangan']['scorecard'];
		$id_scorecard = $this->post['id']['scorecard'];
		$id_status_pengajuant = $id_status_pengajuan = $this->post['id_status_pengajuan']['scorecard'];

		if (
			!($this->Access('pengajuan', $page_ctrl) && $id_status_pengajuan == '2')
			and
			!($this->Access('persetujuan', $page_ctrl) && ($id_status_pengajuan == '5' or $id_status_pengajuan == '4' or $id_status_pengajuan == '6'))
			and
			!($this->Access('penerusan', $page_ctrl) && ($id_status_pengajuan == '5' or $id_status_pengajuan == '4' or $id_status_pengajuan == '3'))

			and
			!($this->Access('pengajuan', $page_ctrl) && ($id_status_pengajuan == '7'))
			and
			!($this->Access('evaluasimitigasi', $page_ctrl) && ($id_status_pengajuan == '5' or $id_status_pengajuan == '8' or $id_status_pengajuan == '9' or $id_status_pengajuan == '10' or $id_status_pengajuan == '11'))
			and
			!($this->Access('evaluasirisiko', $page_ctrl) && ($id_status_pengajuan == '9' or $id_status_pengajuan == '10' or $id_status_pengajuan == '11'))
			and
			!($this->Access('persetujuan', $page_ctrl) && ($id_status_pengajuan == '10' or $id_status_pengajuan == '11'))
		) {
			if ($inline) {
				return array(false, "Anda tidak mempunyai akses");
			} else {
				SetFlash('err_msg', "Anda tidak mempunyai akses");
				redirect(current_url());
				die();
			}
		}

		if (!$keterangan or !$id_scorecard or !$id_status_pengajuan) {
			if ($inline) {
				return array(false, "Data tidak valid");
			} else {
				SetFlash('err_msg', "Data tidak valid");
				redirect(current_url());
				die();
			}
		} else {

			if ($id_status_pengajuan == 11) {
				$id_status_pengajuan = 5;
			}

			$this->conn->StartTrans();

			if ($page == 'risk') {
				$ret = $this->_actionTaskRisk($id_status_pengajuan, $id_scorecard, $keterangan, $id_status_pengajuant);
			} elseif ($page == 'opp') {
				$ret = $this->_actionTaskOpp($id_status_pengajuan, $id_scorecard, $keterangan, $id_status_pengajuant);
			}

			if ($id_status_pengajuan == 2) {

				$recore_ajuer = array(
					"id" => $_SESSION[SESSION_APP]['user_id'],
					"nama_user" => $_SESSION[SESSION_APP]['name'],
					"id_user" => $_SESSION[SESSION_APP]['user_id'],
					"nama_jabatan_user" => $this->conn->GetOne("select nama from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($_SESSION[SESSION_APP]['id_jabatan'])),
					"id_jabatan_user" => $_SESSION[SESSION_APP]['id_jabatan'],
					"nama_owner" => '0',
					"id_owner" => '0',
					"nama_jabatan_owner" => '0',
					"id_jabatan_owner" => '0',
					"nama_upmr" => '0',
					"id_upmr" => '0',
					"nama_jabatan_upmr" => '0',
					"id_jabatan_upmr" => '0',
				);
			}
			if ($id_status_pengajuan == 3) {

				$recore_ajuer = array(
					"id" => $_SESSION[SESSION_APP]['user_id'],
					"nama_owner" => $_SESSION[SESSION_APP]['name'],
					"id_owner" => $_SESSION[SESSION_APP]['user_id'],
					"nama_jabatan_owner" => $this->conn->GetOne("select nama from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($_SESSION[SESSION_APP]['id_jabatan'])),
					"id_jabatan_owner" => $_SESSION[SESSION_APP]['id_jabatan'],
				);
			}
			if ($id_status_pengajuan == 5) {

				$recore_ajuer = array(
					"id" => $_SESSION[SESSION_APP]['user_id'],
					"nama_upmr" => $_SESSION[SESSION_APP]['name'],
					"id_upmr" => $_SESSION[SESSION_APP]['user_id'],
					"nama_jabatan_upmr" => $this->conn->GetOne("select nama from mt_sdm_jabatan deleted_date is null and where id_jabatan = " . $this->conn->escape($_SESSION[SESSION_APP]['id_jabatan'])),
					"id_jabatan_upmr" => $_SESSION[SESSION_APP]['id_jabatan'],
				);
			}
			if ($id_status_pengajuan == 4) {

				$recore_ajuer = array(
					"nama_user" => '0',
					"id_user" => '0',
					"nama_jabatan_user" => '0',
					"id_jabatan_user" => '0',
					"nama_owner" => '0',
					"id_owner" => '0',
					"nama_jabatan_owner" => '0',
					"id_jabatan_owner" => '0',
					"nama_upmr" => '0',
					"id_upmr" => '0',
					"nama_jabatan_upmr" => '0',
					"id_jabatan_upmr" => '0',
				);
			}
			if ($recore_ajuer) {
				$this->load->model('Risk_scorecardModel', 'scorecarmodel');

				$return = $this->scorecarmodel->Update($recore_ajuer, $this->scorecarmodel->pk . " = " . $this->conn->qstr($id_scorecard));

				$ret = $return['success'];
			}
			// else
			// 	$ret = $this->_actionTaskOpp($id_status_pengajuan, $id_scorecard, $keterangan, $id_status_pengajuant);

			// if ($ret) {
			// 	$ret = $this->membuat_qr($id_scorecard, $id_status_pengajuan, $recore_ajuer);
			// }
			if ($ret) {
				$this->model->conn->trans_commit();

				if ($inline) {
					return array(true, "Task berhasil dikirim");
				} else {
					SetFlash('suc_msg', "Task berhasil dikirim");
					redirect(current_url());
					die();
				}
			} else {

				$this->model->conn->trans_rollback();
				if ($inline) {
					return array(false, "Task gagal dikirim");
				} else {
					SetFlash('err_msg', "Task gagal dikirim");
					redirect(current_url());
					die();
				}
			}
		}
	}

	public function membuat_qr($id, $data, $recore_ajuer)
	{

		// dpr($recore_ajuer, 1);
		$this->load->library('Qr');
		$date = date('YmdHis');

		// $isi = 'https://www.google.com';
		// $isi = base_url('panelbackend/risk_risiko/index/' . $id . "?is_ttd=$data");
		$isi = base_url('panelbackend/risk_risiko/index/' . $id . "?is_ttd=" . $recore_ajuer['id']);
		$penyimpanan = 'uploads/';
		$name_file = "1Qr$date.png";

		QRcode::png($isi, $penyimpanan . $name_file);
		$url = base_url("uploads/1Qr$date.png");
		// dpr($url);
		// die;
		// stream_context_set_default( [
		// 	'ssl' => [
		// 		'verify_peer' => false,
		// 		'verify_peer_name' => false,
		// 	],
		// ]);
		// die;
		// dpr($url);
		// die;
		// dpr($this->UR_exists($url),1);
		// if ($this->UR_exists($url)) {

		// $headers = get_headers($url, 1);
		$record = array();
		if ($headers['Accept-Ranges'] == 'bytes')
			$record['file_size'] = $headers['Content-Length'] / 1000;
		else {
			$record['file_size'] = $headers['Content-Length'];
		}
		if ($data == 2) {
			$this->conn->Execute("update risk_scorecard_files set deleted_date = now() where id_scorecard = " . $this->conn->escape($id));
			$app = '2';
		} else if ($data == 4) {
			$ret = $this->conn->Execute("update risk_scorecard_files set deleted_date = now() where id_scorecard = " . $this->conn->escape($id));
			return $ret;
		} else if ($data == 3) {
			$app = '24';
		} else if ($data == 5) {
			$app = '3';
		} else {
			$app = null;
		}
		$record['client_name'] = $name_file;
		$record['file_name'] = $name_file;
		$record['file_type'] = $headers['Content-Type'];
		$record['id_scorecard'] = $id;
		// $record['jenis'] = $app;
		$record['jenis'] = $recore_ajuer['id'];
		// dpr($record, 1);

		// $this->conn->debug = 1;
		// $id_scorecard_files = $this->conn->GetOne("select id_scorecard_files from risk_scorecard_files where id_scorecard = " . $this->conn->escape($id) . " and jenis = " . $this->conn->escape($app));
		$id_scorecard_files = $this->conn->GetOne("select id_scorecard_files from risk_scorecard_files where deleted_date is null and id_scorecard = " . $this->conn->escape($id) . " and jenis = " . $this->conn->escape($recore_ajuer['id']));
		if ($id_scorecard_files) {
			// $ret = $this->conn->goUpdate("risk_scorecard_files", $record, "id_scorecard_files = " . $this->conn->escape($id) . " and jenis = " . $this->conn->escape($app));
			$ret = $this->conn->goUpdate("risk_scorecard_files", $record, "id_scorecard_files = " . $this->conn->escape($id) . " and jenis = " . $this->conn->escape($recore_ajuer['id']));
		} else {
			$ret = $this->conn->goInsert("risk_scorecard_files", $record);
		}
		// dpr($record);
		// dpr($ret,1);
		// }
		return $ret;
	}
	public function UR_exists($url)
	{
		$headers = get_headers($url);
		return stripos($headers[0], "200 OK") ? true : false;
	}

	private function _actionTaskRisk($id_status_pengajuan, $id_scorecard, $keterangan, $id_status_pengajuant)
	{

		$ret = true;
		$record = array(
			'page' => 'risk_scorecard',
			'deskripsi' => $keterangan,
			'id_status_pengajuan' => $id_status_pengajuan,
			'id_scorecard' => $id_scorecard,
			'url' => "panelbackend/risk_risiko/index/$id_scorecard"
		);
		$return = $this->InsertTask($record);
		$ret = $return['success'];

		if ($ret) {

			$r = array('id_status_pengajuan' => $id_status_pengajuan);
			if ($id_status_pengajuan == 5) {
				$r['is_evaluasi_mitigasi'] = 0;
				$r['is_evaluasi_risiko'] = 0;
			} elseif ($id_status_pengajuan == 10) {
				$r['is_evaluasi_risiko'] = 0;
			}
			$this->_setLogRecord($r, $this->post['id']['scorecard']);
			$this->load->model('Risk_scorecardModel', 'modelpage');
			$return = $this->modelpage->Update($r, $this->modelpage->pk . " = " . $this->conn->qstr($id_scorecard));
			$ret = $return['success'];

			if ($id_status_pengajuan == 5 || $id_status_pengajuan == 10) {
				$rowsrisiko = $this->conn->GetArray("select id_risiko, status_risiko 
				from risk_risiko 
				where deleted_date is null and tgl_close is null 
				and id_scorecard = " . $this->conn->escape($id_scorecard));

				foreach ($rowsrisiko as $r) {
					if (!$ret)
						break;

					$id_risiko = $r['id_risiko'];

					if ($id_status_pengajuan == 5) {
						if ($ret) {
							$rec = ["is_lock" => 1, "is_evaluasi_mitigasi" => 0, "is_evaluasi_risiko" => 0];
							if ($id_status_pengajuant == 11 && !$r['status_risiko'])
								$rec['tgl_close'] = date("Y-m-d");

							$ret = $this->conn->goUpdate(
								"risk_risiko",
								$rec,
								"id_risiko = " . $this->conn->escape($id_risiko)
							);
						}

						if ($ret)
							$ret = $this->conn->Execute("update risk_control 
							set is_lock = 1
							where id_risiko = " . $this->conn->escape($id_risiko));
						if ($ret)
							$ret = $this->conn->Execute("update risk_mitigasi 
							set is_lock = 1
							where id_risiko = " . $this->conn->escape($id_risiko));
					} elseif ($id_status_pengajuan == 6) {
						$ret = $this->conn->Execute("update risk_risiko 
						set is_evaluasi_risiko = 0, is_evaluasi_mitigasi = 0, status_risiko = 1 
						where id_risiko = " . $this->conn->escape($id_risiko));
					}
				}
			}
		}
		return $ret;
	}

	private function _actionTaskOpp($id_status_pengajuan, $id_scorecard, $keterangan, $id_status_pengajuant)
	{
		$ret = true;
		$record = array(
			'page' => 'scorecard',
			'deskripsi' => $keterangan,
			'id_status_pengajuan' => $id_status_pengajuan,
			'id_scorecard_peluang' => $id_scorecard,
			'url' => "panelbackend/opp_peluang/index/$id_scorecard"
		);

		$return = $this->InsertTask($record);
		$ret = $return['success'];

		if ($ret) {

			$r = array('id_status_pengajuan' => $id_status_pengajuan);
			if ($id_status_pengajuan == 5) {
				$r['is_evaluasi_mitigasi'] = 1;
				$r['is_evaluasi_peluang'] = 0;
			} elseif ($id_status_pengajuan == 10) {
				$r['is_evaluasi_peluang'] = 0;
			}
			$this->_setLogRecord($r, $this->post['id']['scorecard']);
			$this->load->model('Opp_scorecardModel', 'modelpage');
			$return = $this->modelpage->Update($r, $this->modelpage->pk . " = " . $this->conn->qstr($id_scorecard));
			$ret = $return['success'];

			if ($id_status_pengajuan == 5 || $id_status_pengajuan == 10) {
				$rowspeluang = $this->conn->GetArray("select id_peluang, status_peluang
				from opp_peluang 
				where deleted_date is null and tgl_close is null 
				and id_scorecard = " . $this->conn->escape($id_scorecard));

				foreach ($rowspeluang as $r) {
					if (!$ret)
						break;

					$id_peluang = $r['id_peluang'];

					if ($id_status_pengajuan == 5) {
						if ($ret) {
							$rec = ["is_lock" => 1, "is_evaluasi_mitigasi" => 1, "is_evaluasi_peluang" => 0];
							if ($id_status_pengajuant == 11 && !$r['status_peluang'])
								$rec['tgl_close'] = date("Y-m-d");

							$ret = $this->conn->goUpdate(
								"opp_peluang",
								$rec,
								"id_peluang = " . $this->conn->escape($id_peluang)
							);
						}
					} elseif ($id_status_pengajuan == 10) {
						$ret = $this->conn->Execute("update opp_peluang 
						set is_evaluasi_peluang = 0 
						where id_peluang = " . $this->conn->escape($id_peluang));
					}
				}
			}
		}
		return $ret;
	}

	protected function _actionTaskRisiko($inline = false)
	{
		$page_ctrl = "panelbackend/risk_risiko";
		/**
		 * $this->post['keterangan']['risiko']
		 * $this->post['id']['risiko']
		 * $this->post['id_status_pengajuan']['risiko']
		 */

		$keterangan = $this->post['keterangan']['risiko'];
		$id_risiko = $this->post['id']['risiko'];
		$id_status_pengajuant = $id_status_pengajuan = $this->post['id_status_pengajuan']['risiko'];

		if (
			!($this->Access('pengajuan', $page_ctrl) && $id_status_pengajuan == '2')
			and
			!($this->Access('persetujuan', $page_ctrl) && ($id_status_pengajuan == '5' or $id_status_pengajuan == '4'))
			and
			!($this->Access('penerusan', $page_ctrl) && ($id_status_pengajuan == '4' or $id_status_pengajuan == '3'))
		) {
			if ($inline) {
				return array(false, "Anda tidak mempunyai akses");
			} else {
				SetFlash('err_msg', "Anda tidak mempunyai akses");
				redirect(current_url());
				die();
			}
		}

		if (!$keterangan or !$id_risiko or !$id_status_pengajuan) {
			if ($inline) {
				return array(false, "Data tidak valid");
			} else {
				SetFlash('err_msg', "Data tidak valid");
				redirect(current_url());
				die();
			}
		} else {
			$id_scorecard = $this->conn->GetOne("select id_scorecard from risk_risiko where deleted_date is null and id_risiko = " . $this->conn->escape($id_risiko));


			$this->conn->StartTrans();
			$is_pending = 0;

			//$this->ReExecuteTask($id_risiko);

			$cek = $this->conn->GetOne("select 1 
				from risk_risiko 
				where deleted_date is null and id_risiko = " . $this->conn->escape($id_risiko) . "
				and is_lock <> '1'");
			if ($cek) {
				$record = array(
					'page' => 'risiko',
					'deskripsi' => $keterangan,
					'id_status_pengajuan' => $id_status_pengajuan,
					'id_risiko' => $id_risiko,
					'is_pending' => $is_pending,
					'url' => "panelbackend/risk_risiko/detail/$id_scorecard/$id_risiko"
				);
			} else {
				$cek = $this->conn->GetOne("select 1 
					from risk_control
					where deleted_date is null and id_risiko = " . $this->conn->escape($id_risiko) . "
					and is_lock <> '1'");
				if ($cek) {
					$record = array(
						'page' => 'risiko',
						'deskripsi' => $keterangan,
						'id_status_pengajuan' => $id_status_pengajuan,
						'id_risiko' => $id_risiko,
						'is_pending' => $is_pending,
						'url' => "panelbackend/risk_control/index/$id_risiko"
					);
				} else {
					$cek = $this->conn->GetOne("select 1 
					from risk_mitigasi
					where deleted_date is null and id_risiko = " . $this->conn->escape($id_risiko) . "
					and is_lock <> '1'");

					if ($cek) {
						$record = array(
							'page' => 'risiko',
							'deskripsi' => $keterangan,
							'id_status_pengajuan' => $id_status_pengajuan,
							'id_risiko' => $id_risiko,
							'is_pending' => $is_pending,
							'url' => "panelbackend/risk_mitigasi/index/$id_risiko"
						);
					} elseif (strstr(current_url(), 'risk_evaluasi') !== false) {
						$record = array(
							'page' => 'risiko',
							'deskripsi' => $keterangan,
							'id_status_pengajuan' => $id_status_pengajuan,
							'id_risiko' => $id_risiko,
							'is_pending' => $is_pending,
							'url' => "panelbackend/risk_evaluasi/detail/$id_scorecard/$id_risiko"
						);
					} else {
						$record = array(
							'page' => 'risiko',
							'deskripsi' => $keterangan,
							'id_status_pengajuan' => $id_status_pengajuan,
							'id_risiko' => $id_risiko,
							'is_pending' => $is_pending,
							'url' => "panelbackend/risk_risiko/detail/$id_scorecard/$id_risiko"
						);
					}
				}
			}
			$return = $this->InsertTask($record);


			if ($return['success']) {

				$r = array('id_status_pengajuan' => $id_status_pengajuant);

				$this->_setLogRecord($r, $this->post['id']['risiko']);

				$this->load->model('Risk_risikoModel', 'modelpage');

				$this->modelpage->Update($r, $this->modelpage->pk . " = " . $this->conn->qstr($id_risiko));

				if ($id_status_pengajuan == 5) {
					$this->conn->Execute("update risk_risiko set is_lock = 1 where id_risiko = " . $this->conn->escape($id_risiko));
					$this->conn->Execute("update risk_control set is_lock = 1 where id_risiko = " . $this->conn->escape($id_risiko));
					$this->conn->Execute("update risk_mitigasi set is_lock = 1 where id_risiko = " . $this->conn->escape($id_risiko));
				} elseif ($id_status_pengajuan == 4 && $this->access_role['view_all']) {
					$this->conn->Execute("update risk_mitigasi set is_lock = 0 where id_risiko = " . $this->conn->escape($id_risiko));
				}

				$this->model->conn->CompleteTrans();

				if ($inline) {
					return array(true, "Task berhasil dikirim");
				} else {
					SetFlash('suc_msg', "Task berhasil dikirim");
					redirect(current_url());
					die();
				}
			} else {

				$this->model->conn->CompleteTrans();

				if ($inline) {
					return array(false, "Task gagal dikirim");
				} else {
					SetFlash('err_msg', "Task gagal dikirim");
					redirect(current_url());
					die();
				}
			}
		}
	}

	protected function _actionKonfirmasi($is_plain = null)
	{

		$this->load->library('form_validation');

		$id_risiko = $this->post['id']['risiko'];
		$id_mitigasi = $this->post['id']['mitigasi'];
		$id_status_pengajuan = $this->post['id_status_pengajuan']['mitigasi'];
		$keterangan = $this->post['keterangan']['mitigasi'];


		if (!$keterangan or !$id_mitigasi or !$id_status_pengajuan) {
			SetFlash('err_msg', "Data tidak valid");
			redirect(current_url());
			die();
		} else {

			$this->conn->StartTrans();

			if (!$id_risiko)
				$id_risiko = $this->conn->GetOne("select id_risiko from risk_mitigasi where deleted_date is null and id_mitigasi = " . $this->conn->escape($this->post['id']['mitigasi']));

			$record = array(
				'page' => 'mitigasi',
				'deskripsi' => $keterangan,
				'id_status_pengajuan' => $id_status_pengajuan,
				'id_risiko' => $id_risiko,
				'id_mitigasi' => $id_mitigasi,
				'url' => "panelbackend/risk_mitigasi/edit/$id_risiko/$id_mitigasi"
			);

			$return = $this->InsertTask($record);

			if ($return['success']) {

				$status_konfirmasi = 1;
				if ($id_status_pengajuan == 4) {
					$status_konfirmasi = 2;
				}

				$r = array('status_konfirmasi' => $status_konfirmasi);

				if ($status_konfirmasi == 1)
					$r['is_lock'] = 1;

				$this->_setLogRecord($r, $id_mitigasi);

				$this->load->model('Risk_mitigasiModel', 'modelpage');

				$return = $this->modelpage->Update($r, $this->modelpage->pk . " = " . $this->conn->qstr($id_mitigasi));
			}

			$this->conn->CompleteTrans();

			if ($is_plain) {
				return;
			}

			if ($return['success']) {

				SetFlash('suc_msg', "Task berhasil dikirim");
				redirect(current_url());
				die();
			} else {

				SetFlash('err_msg', "Task gagal dikirim");
				redirect(current_url());
				die();
			}
		}
	}

	protected function _getListTask($rowscorecard)
	{
		$ctrl = strstr($this->ctrl, '_', true);
		$id = $rowscorecard['id_scorecard'];

		if ($ctrl == 'risk') {
			$this->data['task_scorecard'] = $this->conn->GetArray("select t.created_date, t.deskripsi, 
				t.id_status_pengajuan, u.name as nama_user, g.name as nama_group
				from risk_task t
				join public_sys_user u on t.created_by = u.user_id
				join public_sys_group g on t.group_id = g.group_id
				where t.deleted_date is null and (page = 'risk_scorecard' or page = 'scorecard')
				and id_scorecard =" . $this->conn->escape($id) . "
				and t.id_status_pengajuan is not null
				and t.is_pending != '1'
				order by id_task desc");
		} else {
			$this->data['task_scorecard'] = $this->conn->GetArray("select t.created_date, t.deskripsi, 
				t.id_status_pengajuan, u.name as nama_user, g.name as nama_group
				from risk_task t
				join public_sys_user u on t.created_by = u.user_id
				join public_sys_group g on t.group_id = g.group_id
				where t.deleted_date is null and (page = 'risk_scorecard' or page = 'scorecard')
				and id_scorecard_peluang =" . $this->conn->escape($id) . "
				and t.id_status_pengajuan is not null
				and t.is_pending != '1'
				order by id_task desc");
		}
	}

	protected function _accessTask($rowscorecard, $rowsrisiko, &$edited)
	{
		$is_edit = accessbystatus($rowscorecard['id_status_pengajuan']);

		if ($is_edit && $this->access_role['view_all']) {
			return;
		}

		if ($is_edit && $this->access_role['view_all_unit'] && $_SESSION[SESSION_APP]['id_unit'] == $rowscorecard['id_unit']) {
			return;
		}

		if ((!$is_edit or ($rowsrisiko['status_risiko'] !== '1' && $rowsrisiko['id_risiko']))) {
			$this->access_role['edit'] = false;
			$this->access_role['delete'] = false;
			$this->access_role['add'] = false;
			$edited = false;
		}
	}

	protected function _validAccessTask($rowscorecard, $rowsrisiko, &$edited)
	{
		$this->_accessTask($rowscorecard, $rowsrisiko, $edited);

		if (!$this->access_role[$this->mode]) {
			$str = '';

			if (ENVIRONMENT == 'development')
				$str = "akses : " . print_r($this->access_role, true);

			$this->Error403($str);
			exit();
		}
	}

	protected function backtodraft($id_risiko = null)
	{
		if ($this->post['act'] != 'save')
			return false;

		$this->conn->goUpdate("risk_risiko", ["is_lock" => "0"], "id_risiko = " . $this->conn->escape($id_risiko));

		$id_scorecard = $this->conn->GetOne("select id_scorecard from risk_risiko where deleted_date is null and id_risiko = " . $this->conn->escape($id_risiko));

		$this->load->model("Risk_scorecardModel", "rscorecard");
		$row = $this->rscorecard->GetByPk($id_scorecard);
		if (($row)) {
			if (($row['id_status_pengajuan'] == '5' or $row['id_status_pengajuan'] == '4' or !$row['id_status_pengajuan']) && ($this->Access("pengajuan", 'panelbackend/risk_scorecard'))) {
				$record = array("id_status_pengajuan" => 1);
				$this->rscorecard->Update($record, "id_scorecard = " . $this->conn->qstr($id_scorecard));
			}
		}
	}

	protected function backtodraftpeluang($id_peluang = null)
	{
		if ($this->post['act'] != 'save')
			return false;


		$this->conn->goUpdate("opp_peluang", ["is_lock" => "0"], "id_peluang = " . $this->conn->escape($id_peluang));

		$id_scorecard = $this->conn->GetOne("select id_scorecard from opp_peluang where deleted_date is null and id_peluang = " . $this->conn->escape($id_peluang));

		$this->load->model("Opp_scorecardModel", "rscorecard");
		$row = $this->rscorecard->GetByPk($id_scorecard);
		if (($row)) {
			// $this->conn->debug = 1;
			if (($row['id_status_pengajuan'] == '5' or $row['id_status_pengajuan'] == '4' or !$row['id_status_pengajuan']) && ($this->Access("pengajuan", 'panelbackend/opp_scorecard'))) {
				$record = array("id_status_pengajuan" => 1);
				$this->rscorecard->Update($record, "id_scorecard = " . $this->conn->qstr($id_scorecard));
			}

			// dpr($row, 1);
		}
	}

	protected function LogColumns($column = null)
	{
		$kpiarr = array();

		if ($this->data['row']['kpi_kegiatan'])
			foreach ($this->data['row']['kpi_kegiatan'] as $r) {
				$kpiarr[$r['id_kpi']] = $r['nama'];
			}

		if ($this->data['row']['kpi'])
			foreach ($this->data['row']['kpi'] as $r) {
				$kpiarr[$r['id_kpi']] = $r['nama'];
			}

		$return = array(
			"no" => array(
				'label' => 'No.',
			),
			"nomor" => array(
				'label' => 'Nomor',
			),
			"regulasi" => array(
				'label' => 'Pemenuhan Kewajiban',
			),
			"id_taksonomi_area" => array(
				'label' => 'Kategori',
				'arr' => $this->data['taksonomiareaarr']
			),
			"is_opp_inherent" => array(
				'label' => 'Risk/Opportunity Risiko Inheren',
				'arr' => array('-1' => 'Risk', '1' => 'Opportunity'),
			),
			"nama_kegiatan" => array(
				'label' => 'Nama Kegiatan',
			),
			"hasil_mitigasi_terhadap_sasaran" => array(
				'label' => 'Hasil Penanganan Terhadap Sasaran',
			),
			"is_accept" => array(
				'label' => 'Signifikan Risiko Residual Saat Ini',
			),
			"is_signifikan_inherent" => array(
				'label' => 'Signifikan Risiko Inheren',
			),
			"integrasi_eksternal" => array(
				'label' => 'Integrasi Eksternal',
			),
			"id_prioritas" => array(
				'label' => 'Tingkat Prioritas',
				'ref' => ["tabel" => "mt_prioritas", "label" => "warna", "primary" => "id_prioritas"],
				// 'arr' => $this->data['prioritaswarna'],
			),
			"is_rutin" => array(
				'label' => 'Jenis',
				'arr' => ["0" => "Non Rutin", "1" => "Rutin"]
			),
			"id_kriteria_kemungkinan" => array(
				'label' => 'Kriteria',
				'arr' => array('' => '', '1' => 'Probabilitas', '2' => 'Deskripsi Kualitatif', '3' => 'Insiden Sebelumnya')
			),


			"id_kategori" => array(
				'label' => 'Alur Proses Bisnis',
				'arr' => $this->data['kategoriarr'],
			),
			"sub_tahapan_kegiatan" => array(
				'label' => 'Sub Tahapan Kegiatan',
			),
			"id_risiko_parent" => array(
				'label' => 'Risiko Induk',
				'arr' => ($this->data['risikoindukarr'] ? $this->data['risikoindukarr'] : array())
			),
			"red_flag" => array(
				'label' => 'Red Flag',
			),
			"id_taksonomi" => array(
				'label' => 'Taksonomi',
				'arr' => $this->data['taksonomiarr']
			),
			"id_status_pengajuan" => array(
				'label' => 'Status Pengajuan',
				'arr' => $this->data['mtstatusarr']
			),
			"tgl_risiko" => array(
				'label' => 'Tanggal Risiko',
			),
			"id_jabatan_berisiko" => array(
				'label' => 'Pejabat Berisiko',
				'arr' => $this->data['pejabatarr']
			),
			"id_kpi" => array(
				'label' => 'KPI',
				'arr' => $kpiarr
			),
			"id_risiko" => array(
				'label' => 'Id Risiko',
			),
			"id_control" => array(
				'label' => 'Id Control',
			),
			"skor_inheren_kemungkinan" => array(
				'label' => 'Skor Kemungkinan Inheren',
			),
			"skor_inheren_dampak" => array(
				'label' => 'Skor Dampak Inheren',
			),
			"skor_control_kemungkinan" => array(
				'label' => 'Skor Kemungkinan Control',
			),
			"skor_control_dampak" => array(
				'label' => 'Skor Dampak Control',
			),
			"skor_target_kemungkinan" => array(
				'label' => 'Skor Kemungkinan Target',
			),
			"skor_target_dampak" => array(
				'label' => 'Skor Dampak Target',
			),


			"nama" => array(
				'label' => 'Nama',
			),
			"deskripsi" => array(
				'label' => 'Deskripsi',
			),
			"inheren_dampak" => array(
				'label' => 'Dampak Risiko Inheren',
				'arr' => $this->data['mtdampakrisikoarr'],
			),
			"inheren_kemungkinan" => array(
				'label' => 'Probability Risiko Inheren',
				'arr' => $this->data['mtkemungkinanrisikoarr'],
			),
			"control_dampak_penurunan" => array(
				'label' => 'Dampak Risiko Residual Saat Ini',
				'arr' => $this->data['mtdampakrisikoarr'],
			),
			"control_kemungkinan_penurunan" => array(
				'label' => 'Probability Risiko Residual Saat Ini',
				'arr' => $this->data['mtkemungkinanrisikoarr'],
			),
			"penyebab" => array(
				'label' => 'Sumber Risiko / Peluang',
				'ref' => ["tabel" => "risk_penyebab", "label" => "nama", "primary" => "id_risk_penyebab"],
				// 'arr' => $this->data['riskpenyebabarr'],
			),
			"dampak" => array(
				'label' => 'Dampak',
				'ref' => ["tabel" => "risk_dampak", "label" => "nama", "primary" => "id_risk_dampak"],
				// 'arr' => $this->data['riskdampakarr'],
			),
			"id_sasaran" => array(
				'label' => 'Sasaran/ Kegiatan/ Proses',
				// 'arr' => $this->data['sasaranarr'],
				'ref' => ["tabel" => "risk_sasaran", "label" => "nama", "primary" => "id_sasaran"],
				// 'arr' => $this->data['risksasaranarr'],
			),
			"control" => array(
				'label' => 'Pengendalian Berjalan',
				'ref' => ["tabel" => "risk_control", "label" => "nama", "primary" => "id_control"],
				// 'arr' => $this->data['riskcontrolarr'],
			),
			"mitigasi" => array(
				'label' => 'Pengendalian Lanjutan ',
				'ref' => ["tabel" => "risk_mitigasi", "label" => "nama", "primary" => "id_mitigasi"]
				// 'arr' => $this->data['mitigasiarr'],
			),
			"id_aspek_lingkungan" => array(
				'label' => 'Operasional',
				'ref' => ["tabel" => "mt_aspek_lingkungan", "label" => "kode+' '+nama", "primary" => "id_aspek_lingkungan"]
				// 'arr' => $this->data['operasionalarr'],
			),
			"id_kegiatan" => array(
				'label' => 'Sasaran Kegiatan',
				'arr' => $this->data['mtkegiatanarr'],
			),
			"residual_target_dampak" => array(
				'label' => 'Dampak Target Residual',
				'arr' => $this->data['mtdampakrisikoarr'],
			),
			"residual_target_kemungkinan" => array(
				'label' => 'Probability Target Residual',
				'arr' => $this->data['mtkemungkinanrisikoarr'],
			),
			"id_kriteria_dampak" => array(
				'label' => 'Kriteria',
				'arr' => $this->data['kriteriaarr'],
			),
			"residual_dampak_evaluasi" => array(
				'label' => 'Dampak Risiko Residual Setelah Evaluasi',
				'arr' => $this->data['mtdampakrisikoarr'],
			),
			"residual_kemungkinan_evaluasi" => array(
				'label' => 'Probability Risiko Residual Setelah Evaluasi',
				'arr' => $this->data['mtkemungkinanrisikoarr'],
			),
			"progress_capaian_kinerja" => array(
				'label' => 'Progress Capaian Kinerja',
			),
			"penyesuaian_tindakan_mitigasi" => array(
				'label' => 'Rekomendasi',
			),
			"hambatan_kendala" => array(
				'label' => 'Hambatan Kendala',
			),
			"dead_line" => array(
				'label' => 'Dead Line',
			),
			"rating" => array(
				'label' => 'Rating',
			),
			"biaya" => array(
				'label' => 'Biaya',
			),
			"revenue" => array(
				'label' => 'Dasar Perhitungan Dampak Finansial',
			),
			"penanggung_jawab" => array(
				'label' => 'Penanggung Jawab',
				'arr' => $this->data['penanggung_jawabarr']
			),
			"id_status_progress" => array(
				'label' => 'Progress',
				'arr' => $this->data['pregressarr'],
			),
			"status_progress" => array(
				'label' => 'Progress',
			),
			"menurunkan_dampak_kemungkinan" => array(
				'label' => 'K/D',
				'arr' => $this->data['menurunkanrr'],
			),
			"remark" => array(
				'label' => 'Remark',
			),
			"is_efektif" => array(
				'label' => 'Efektifitas',
				'arr' => array('2' => 'Tidak Efektif', '1' => 'Efektif')
			),
			"id_interval" => array(
				'label' => 'Interval',
				'arr' => $this->data['mtintervalarr'],
			),
			"efektif" => array(
				'label' => 'Faktor Efektifitas',
				'arrarr' => $this->data['mtefektifitasarr'],
				'arr' => $this->data['mtjawabanarr'],
			),
			"id_pengukuran" => array(
				'label' => 'Tingkat Efektifitas',
				'arr' => $this->data['mtpengukuranarr'],
			),
			"files" => array(
				'label' => 'File',
			),
			"dampak_kuantitatif_inheren" => array(
				'label' => 'Dampak Kuantitatif',
			),
		);

		if (!$return[$column]) {
			$return[$column] = array('label' => $column);
		}

		return $return[$column];
	}

	protected function riskchangelog($new = array(), $old = array(), $str = '', $page_ctrl = '')
	{
		if (!$new['id_risiko'])
			$new['id_risiko'] = $this->data['row']['id_risiko'];

		if (!$page_ctrl)
			$page_ctrl = $this->page_ctrl;

		if (($this->ctrl == 'risk_control' or $this->ctrl == 'risk_mitigasi') && $this->method == 'index')
			$page_ctrl = "panelbackend/risk_risiko";

		unset($new['act']);

		$skiparr = array("modified_date", "is_signifikan_current", "is_opp_current", "dampak_kuantitatif_current", "is_opp_target", "is_opp_evaluasi", "id_risk_penyebab", "id_risk_dampak", "modified_by", "created_by", "created_date", "kpi_kegiatan", "kpi", "status_konfirmasi", "is_lock", "filesold");

		if (is_array($old) && is_array($new) && ($old)) {
			$str .= "";
			foreach ($new as $idkey => $newvalue) {
				if (in_array($idkey, $skiparr) or $newvalue === null)
					continue;

				$oldvalue = $old[$idkey];

				$col = $this->LogColumns($idkey);
				$key_alias = $col['label'];

				if (!$key_alias)
					continue;

				if ($oldvalue <> $newvalue) {

					$newvalue = $this->formatLog($idkey, $newvalue, $col);
					$oldvalue = $this->formatLog($idkey, $oldvalue, $col);

					if ($oldvalue <> $newvalue) {

						$str .= "\n<b>" . $key_alias . " </b>: " . trim($oldvalue) . " <b>menjadi</b> " . trim($newvalue);
					}
				}
			}

			if ($str) {
				$nama = $old['nama'];
				$str = "Mengubah data di " . var2alias($page_ctrl) . " $nama : " . $str;
			}
		} elseif (is_array($new)) {
			$str .= "Menambah data " . var2alias($page_ctrl);
			foreach ($new as $idkey => $value) {
				if (in_array($idkey, $skiparr))
					continue;

				if ($value) {
					$col = $this->LogColumns($idkey);
					$key_alias = $col['label'];

					if (!$key_alias)
						continue;

					$str1 = $this->formatLog($idkey, $value, $col);

					$str .= "\n<b>" . $key_alias . "</b> : " . trim($str1);
				}
			}
		}

		$str = trim($str, ',');

		$this->risklog($str, $page_ctrl, $new, $old);
	}

	protected function oppchangelog($new = array(), $old = array(), $str = '', $page_ctrl = '')
	{
		if (!$new['id_risiko'])
			$new['id_risiko'] = $this->data['row']['id_risiko'];

		if (!$page_ctrl)
			$page_ctrl = $this->page_ctrl;

		if (($this->ctrl == 'opp_control' or $this->ctrl == 'opp_mitigasi') && $this->method == 'index')
			$page_ctrl = "panelbackend/opp_peluang";

		unset($new['act']);

		$skiparr = array("modified_date", "is_signifikan_current", "is_opp_current", "dampak_kuantitatif_current", "is_opp_target", "is_opp_evaluasi", "id_risk_penyebab", "id_risk_dampak", "modified_by", "created_by", "created_date", "kpi_kegiatan", "kpi", "status_konfirmasi", "is_lock", "filesold");

		if (is_array($old) && is_array($new) && ($old)) {
			$str .= "";
			foreach ($new as $idkey => $newvalue) {
				if (in_array($idkey, $skiparr) or $newvalue === null)
					continue;

				$oldvalue = $old[$idkey];

				$col = $this->LogColumns($idkey);
				$key_alias = $col['label'];

				if (!$key_alias)
					continue;

				if ($oldvalue <> $newvalue) {

					$newvalue = $this->formatLog($idkey, $newvalue, $col);
					$oldvalue = $this->formatLog($idkey, $oldvalue, $col);

					if ($oldvalue <> $newvalue) {

						$str .= "\n<b>" . $key_alias . " </b>: " . trim($oldvalue) . " <b>menjadi</b> " . trim($newvalue);
					}
				}
			}

			if ($str) {
				$nama = $old['nama'];
				$str = "Mengubah data di " . var2alias($page_ctrl) . " $nama : " . $str;
			}
		} elseif (is_array($new)) {
			$str .= "Menambah data " . var2alias($page_ctrl);
			foreach ($new as $idkey => $value) {
				if (in_array($idkey, $skiparr))
					continue;

				if ($value) {
					$col = $this->LogColumns($idkey);
					$key_alias = $col['label'];

					if (!$key_alias)
						continue;

					$str1 = $this->formatLog($idkey, $value, $col);

					$str .= "\n<b>" . $key_alias . "</b> : " . trim($str1);
				}
			}
		}

		$str = trim($str, ',');

		$this->opplog($str, $page_ctrl, $new, $old);
	}

	protected function formatLog($idkey, $value = null, $col = array())
	{
		if (is_array($value)) {
			$arr = array();
			if ($idkey == "files") {
				foreach ($value['name'] as $val) {
					$arr[] = $val;
				}
				$str1 = implode(", ", $arr);
			} elseif ($idkey == 'efektif' && $this->page_ctrl == "panelbackend/risk_control") {
				foreach ($col['arrarr'] as $val) {
					$k = $val['id_efektifitas'];
					$nama = $val['nama'];
					$v = $value[$k];
					$t = $nama . " : <i>" . $col['arr'][$v['id_jawaban']];

					if ($val['need_explanation'] && $v['id_jawaban'] <> '3')
						$t .= ' (Penjalasan : ' . $v['keterangan'] . ')';

					if ($val['need_lampiran'] && $v['id_jawaban'] <> '3')
						$t .= ' (Lampiran : ' . $_FILES['file']['name'] . ')';

					$t .= "</i>";

					$arr[] = $t;
				}
				$str1 = "<br/>" . implode("<br/>", $arr);
			} elseif ($idkey == 'efektif' && $this->page_ctrl == "panelbackend/risk_mitigasi") {
				foreach ($col['arrarr'] as $val) {
					$k = $val['id_efektif_m'];
					$nama = $val['nama'];
					$v = $value[$k];
					$t = $nama . " : <i>" . $val['jawabanarr'][$v['id_efektif_m_jawaban']];

					$t .= "</i>";

					$arr[] = $t;
				}
				$str1 = "<br/>" . implode("<br/>", $arr);
			} elseif ($col['ref']) {

				if ($idkey == 'penyebab') $id = 'id_risk_penyebab';
				else if ($idkey == 'dampak') $id = 'id_risk_dampak';
				else if ($idkey == 'control') $id = 'id_control';
				else if ($idkey == 'mitigasi') $id = 'id_mitigasi';
				foreach ($value as $h => $v) {
					if ($id) {
						$arr[] = '-' . $this->conn->GetOne("select " . $col['ref']['label'] . " from " . $col['ref']['tabel'] . " where deleted_date is null and " . $col['ref']['primary'] . " = " . $this->conn->escape($v[$id]));
					} else {
						$arr[] = '-' . $this->conn->GetOne("select " . $col['ref']['label'] . " from " . $col['ref']['tabel'] . " where deleted_date is null and" . $col['ref']['primary'] . " = " . $this->conn->escape($h));
					}
				}
				$str1 = "<br/>" . implode("<br/>", $arr);
			} elseif ($col['arrarr']) {
				foreach ($value as $k => $v) {
					$arr[] = $col['arrarr'][$k]['nama'] . " : <i>" . $col['arrarr1'][$k][$v] . "</i>";
				}
				$str1 = "<br/>" . implode("<br/>", $arr);
			}
			// elseif (in_array($idkey, array('penyebab', 'dampak', 'control', 'mitigasi'))) {

			// 	if ($idkey == 'penyebab') $id = 'id_risk_penyebab';
			// 	else if ($idkey == 'dampak') $id = 'id_risk_dampak';
			// 	else if ($idkey == 'control') $id = 'id_control';
			// 	else if ($idkey == 'mitigasi') $id = 'id_mitigasi';
			// 	foreach ($value as $k => $v) {
			// 		$arr[] = '-' . $col['arr'][$v[$id]];
			// 	}
			// 	$str1 = "<br/>" . implode("<br/>", $arr);
			// }
			else {
				foreach ($value as $k => $v) {
					$arr[] = "<i>" . $col['arr'][$v] . "</i>";
				}
				$str1 = implode(", ", $arr);
			}
		} else {
			if ($col['arr'])
				$value = $col['arr'][$value];

			if ($value == '-pilih-' || !$value)
				$value = '(kosong)';

			if ($col['ref'] && $value !== '(kosong)') {
				$value = $this->conn->GetOne("select " . $col['ref']['label'] . " from " . $col['ref']['tabel'] . " where " . $col['ref']['primary'] . " = " . $this->conn->escape($value));
			}
			$str1 = "<i>" . $value . "</i>";
		}

		return $str1;
	}

	protected function risklog($act = "", $page_ctrl = '', $data = array(), $data_old = array())
	{
		if (!$page_ctrl)
			$page_ctrl = $this->page_ctrl;

		if (!$act)
			return;

		$record = array();

		if (!$data)
			$data = $this->data['row'];

		if (!$data['id_risiko'])
			$data['id_risiko'] = $this->data['row']['id_risiko'];

		if (!$data['id_risiko'])
			$data['id_risiko'] = $data_old['id_risiko'];

		if (in_array($page_ctrl, array(
			"panelbackend/risk_scorecard",
			"panelbackend/risk_risiko",
			"panelbackend/risk_sasaran_kegiatan",
		)) && !$data['id_risiko']) {
			$record['id_scorecard'] = $data['id_scorecard'];
		} else {
			$record['id_risiko'] = $data['id_risiko'];
		}

		$record['deskripsi'] = $act;
		// $record['activity_time'] = "{{sysdate()}}";
		$record['activity_time'] = "{{now()}}";
		$record['created_by'] = $_SESSION[SESSION_APP]['user_id'];
		$record['user_name'] = $_SESSION[SESSION_APP]['name'];
		$record['group_id'] = $_SESSION[SESSION_APP]['group_id'];

		// dpr($record,1);
		// $this->conn->debug = 1;
		$this->conn->goInsert("risk_log", $record);

		// dpr(1,1);
	}

	protected function opplog($act = "", $page_ctrl = '', $data = array(), $data_old = array())
	{
		if (!$page_ctrl)
			$page_ctrl = $this->page_ctrl;

		if (!$act)
			return;

		$record = array();

		if (!$data)
			$data = $this->data['row'];

		if (!$data['id_risiko'])
			$data['id_risiko'] = $this->data['row']['id_risiko'];

		if (!$data['id_risiko'])
			$data['id_risiko'] = $data_old['id_risiko'];

		if (in_array($page_ctrl, array(
			"panelbackend/opp_scorecard",
			"panelbackend/opp_peluang",
		)) && !$data['id_risiko']) {
			$record['id_scorecard'] = $data['id_scorecard'];
		} else {
			$record['id_risiko'] = $data['id_risiko'];
		}

		$record['deskripsi'] = $act;
		// $record['activity_time'] = "{{sysdate()}}";
		$record['activity_time'] = "{{now()}}";
		$record['created_by'] = $_SESSION[SESSION_APP]['user_id'];
		$record['user_name'] = $_SESSION[SESSION_APP]['name'];
		$record['group_id'] = $_SESSION[SESSION_APP]['group_id'];

		// dpr($record,1);
		// $this->conn->debug = 1;
		$this->conn->goInsert("opp_log", $record);

		// dpr(1,1);
	}

	function curl($q, $params = array())
	{
		$url = site_url($q);
		$url = str_replace($_SERVER['HTTP_HOST'], "127.0.0.1", $url);
		$url = str_replace("https", "http", $url);
		$param_str = http_build_query($params);

		$ch = curl_init();

		// echo $url.$param_str;
		// die();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($ch, CURLOPT_TIMEOUT, 2);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param_str);
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


	function Access($mode, $page = null, $isdebug = false)
	{
		if ($mode == 'view_all')
			return $_SESSION[SESSION_APP]['view_all'];

		if ($page) {
			if ($this->access_role_custom[$page])
				$access_role = $this->access_role_custom[$page];
			else {
				$this->access_role_custom[$page] = $this->auth->GetAccessRole($page);
				$access_role = $this->access_role_custom[$page];
			}
		} else {
			$access_role = $this->access_role;
		}

		if ($isdebug) {
			dpr($page);
			dpr($access_role, 1);
		}

		if ($access_role[$mode])
			return true;
		else
			return false;
	}

	function isLock()
	{
		if ($this->data['rowheader1']['status_risiko'] == '0' && $this->data['rowheader1']['tgl_close']) {
			$this->data['editedheader1'] = false;
			$this->data['edited'] = false;
			$this->access_role['edit'] = false;
			$this->access_role['delete'] = false;
			$this->access_role['add'] = false;
			$this->data['row']['is_lock'] = 0;
			return;
		}

		if ($this->access_role['view_all'] or $this->access_role['view_all_unit'])
			return;

		if ($this->ctrl == "risk_mitigasi" and $this->data['row']['is_lock'] == '1') {
			$this->access_role['delete'] = false;
			// } elseif ($this->data['row']['is_lock'] == '1' && $this->ctrl != "risk_evaluasi" && $this->ctrl != "risk_monitoring_bulanan") {
		} elseif ($this->data['row']['is_lock'] == '1' && $this->ctrl != "risk_monitoring_bulanan" && $this->ctrl != "risk_penanganan_mitigasi") {
			$this->data['edited'] = false;
			$this->access_role['edit'] = false;
			$this->access_role['delete'] = false;
		}

		if ($this->data['rowheader1']['is_lock'] == '1') {
			$this->data['editedheader1'] = false;
		}
	}

	function unlock()
	{
		$id = $this->post['idkey'];
		$record = array("is_lock" => 2);
		return $this->model->Update($record, "$this->pk = " . $this->conn->qstr($id));
	}

	protected function UpdateConfig($var, $val)
	{
		$this->conn->goUpdate("public_sys_setting", array('isi' => $val), "nama = " . $this->conn->escape($var));
	}

	function upload_file($id = null)
	{


		$jenis_file = key($_FILES);

		$ret = $this->_uploadFiles($jenis_file, $id);

		echo json_encode($ret);
	}


	function delete_file($id = null)
	{
		$ret = $this->_deleteFiles($this->post['id']);

		echo json_encode($ret);
	}

	function open_file($id = null, $nameid = null)
	{
		$this->_openFiles($id, $nameid);
	}

	protected function _updateFiles($record = array(), $id = null)
	{
		return $this->modelfile->Update($record, $this->modelfile->pk . "=" . $this->conn->escape($id));
	}

	protected function _deleteFiles($id)
	{
		$row = $this->modelfile->GetByPk($id);

		if (!$row)
			$this->Error404();

		$file_name = $row['file_name'];

		$return = $this->modelfile->Delete($this->modelfile->pk . " = " . $this->conn->escape($id));

		if ($return) {
			$full_path = $this->data['configfile']['upload_path'] . $file_name;
			@unlink($full_path);

			return array("success" => true);
		} else {
			return array("error" => "File " . $row['client_name'] . " gagal dihapus");
		}
	}

	protected function _openFiles($id = null, $nameid = null)
	{
		$row = $this->modelfile->GetByPk($id);
		if ($row) {
			$full_path = $this->data['configfile']['upload_path'] . $row['file_name'];
			$str = file_get_contents($full_path);
			header("Content-Type: {$row['file_type']}");
			header("Content-Disposition: inline; filename=\"{$row['client_name']}\"");
			header('Content-length: ' . strlen($str));
			echo $str;
			die();
		} else {
			$this->Error404();
		}
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

			$record = array();
			$record['client_name'] = $upload_data['client_name'];
			$record['file_name'] = $upload_data['file_name'];
			$record['file_type'] = $upload_data['file_type'];
			$record['file_size'] = $upload_data['file_size'];
			$record['jenis_file'] = $record['jenis'] = str_replace("upload", "", $jenis_file);
			$record['folder_name'] = $this->post[$record['jenis_file'] . "folder"];
			if ($record['folder_name'])
				$record['client_name'] = $record['folder_name'];
			$record[$this->pk] = $id;

			$ret = $this->modelfile->Insert($record);
			if ($ret['success']) {
				$return = array('file' => array("id" => $ret['data'][$this->modelfile->pk], "name" => $record['client_name']));
			} else {
				unlink($upload_data['full_path']);
				$return = array('errors' => "File $name gagal upload");
			}
		}

		return $return;
	}

	protected function isPrivateMethod($method = null)
	{
		$respons = $this->isTrustMethod($method);
		if (!$respons[SESSION_APP])
			$this->fail("Unauthorized", 401);

		$_SESSION[SESSION_APP] = $respons[SESSION_APP];

		self::__construct();
		return true;
	}

	protected function periodeDefault()
	{
		$tgl_efektif = date('Y-m-d');
		if ($_SESSION[SESSION_APP]['tgl_efektif']) {
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		}

		$this->data['tgl_efektif'] = $tgl_efektif;
		list($thn, $bln, $tgl) = explode("-", $tgl_efektif);

		$this->data['periode_tw'] = $this->conn->GetRow("select * from mt_periode_tw where '$bln' between bulan_mulai and bulan_akhir");
		$this->data['id_periode_tw'] = $this->data['periode_tw']['id_periode_tw'];
		$this->data['tahun'] = $thn;
		$this->data['bulan'] = $bln;
	}


	protected function send_email($username, $msg, $title = null, $bcc = null)
	{
		$this->data['msg'] = $msg;

		$this->data['title'] = $title = Title($title);
		//print_r($this->template_email);exit();
		if (!$this->template_email)
			$this->template_email = "panelbackend/template_email";

		$str = $this->PartialView($this->template_email, true);

		// $this->curl("panelbackend/publ1c/sendemail",
		return $this->_sendEmail(
			array(
				"subject" => $title,
				"body" => $str,
				"bcc" => $bcc,
				"recipients" => $username
			)
		);
	}
}
