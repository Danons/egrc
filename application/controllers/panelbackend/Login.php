<?php
class Login extends _Controller
{
	function __construct()
	{
		$this->xss_clean = true;
		parent::__construct();

		$sql = "select * from public_sys_setting where deleted_date is null";
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
		$this->helper("s");
	}
	function Index()
	{
		$this->helper("s");

		if ($_SESSION[SESSION_APP]['login']) {
			$this->log("Login");
			if ($_SESSION[SESSION_APP]['curr_page']) {
				redirect($_SESSION[SESSION_APP]['curr_page']);
			} else {
				redirect('panelbackend/home/strategis');
			}
		} else {
			$this->SetToken();
			$this->PartialView('panelbackend/login');
		}
	}
	function Auth()
	{
		// print_r('cek token dari setToken');
		// print_r($this->data);

		// $this->CekToken();

		$this->load->model("AuthModel", "auth");

		$ret = json_encode($this->auth->Login($this->post['username'], $this->post['password']));
		$ret1 = json_decode($ret, true);
		// dpr($ret1, 1);

		if (!$ret1['error']) {
			if ($this->post['remember-me'])
				setcookie("username", $this->post['username'], time() + 60 * 60 * 24 * 30);
			else {
				if (isset($_COOKIE["username"])) {
					setcookie("username", "");
				}
			}
		}

		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			echo $ret;
		} else {
			$ret1 = json_decode($ret, true);
			if ($ret1['error']) {
				SetFlash('err_msg', $ret1['error']);
				redirect("panelbackend/login");
			} else
				redirect($_SESSION[SESSION_APP]['curr_page']);
		}

		$this->log("Login");
	}

	function Logout()
	{
		$this->log("Login Out");
		$_SESSION[SESSION_APP]['login'] = false;
		unset($_SESSION[SESSION_APP]);
		redirect('panelbackend');
	}

	function Akses()
	{
		if (!$_SESSION[SESSION_APP]['login'] or !$_SESSION[SESSION_APP]['akses'])
			redirect("panelbackend");

		if ($this->post['act'] == 'set_akses' && $this->post['idkey'] !== null) {
			$this->load->model("AuthModel", "auth");
			$this->auth->SetLogin($_SESSION[SESSION_APP]['akses'][$this->post['idkey']]);
			redirect('panelbackend');
		}

		$this->PartialView("panelbackend/login_akses");
	}
}
