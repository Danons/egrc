<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . "libraries/JWT/JWT.php";
include APPPATH . "libraries/JWT/Key.php";

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class _Controller extends CI_Controller
{

	public $data = array();
	public $post = array();
	public $conn;
	public $model;
	public $ctrl;
	public $page_ctrl;
	public $method;
	public $mode;
	public $session;
	public $get = array();
	public $addbuttons = array();
	protected $xss_clean = true;
	protected $escape_html = false;
	public $url = "";
	public $urlaccess = "";
	public $viewpath = "";
	public $auth;
	public $private = true;
	static $referer = false;
	public $pk;
	public $limit = 5;
	public $limit_arr = array('5', '10', '15');
	public $arrNoquote = array();
	public $base;
	protected $layout = "";
	protected $viewdetail = "";
	protected $viewlist = "";
	protected $filter = " 1=1 ";
	public $access_role = array();
	public $page_escape = array();
	protected $jwt;
	protected $keyjwt = "PJBJ4y4";
	public $is_super_admin = false;
	protected $plugin_arr = array();

	public function __construct()
	{
		parent::__construct();

		$this->template = "main";
		$this->layout = "layout1";

		if ($_GET['sess_id_jabatan']) {
			$_SESSION[SESSION_APP]['id_jabatan'] = $_GET['sess_id_jabatan'];
			$_SESSION[SESSION_APP]['user_id'] = $_GET['sess_user_id'];
			$_SESSION[SESSION_APP]['id_unit'] = $_GET['sess_id_unit'];
			$_SESSION[SESSION_APP]['group_id'] = $_GET['sess_group_id'];
			$_SESSION[SESSION_APP]['view_all'] = $_GET['sess_view_all'];
		}

		$this->jwt = new JWT();

		$router = $this->router;

		$this->ctrl = $router->class;
		$this->page_ctrl = $router->directory . $router->class;
		$this->method = $router->method;
		$this->mode = $router->method;
		$this->data['ctrl'] = $this->ctrl;
		$this->data['method'] = $this->method;
		$this->data['page_ctrl'] = $this->page_ctrl;
		$this->data['mode'] = $this->mode;
		$this->data['base'] = $this->base = base_url();

		// $this->load->library('session');

		// $this->data['session'] = $this->session;
		session_start();

		$this->FilterRequest();

		if (ENVIRONMENT == 'production')
			ob_start();

		$this->load->database();
		$this->db->debug = 0;
		$this->conn = $this->db;

		if (ENVIRONMENT == 'production')
			ob_end_clean();

		// if($this->conn){
		// 	$date_format = $this->config->item("date_format");
		// 	$timestamp_format = $this->config->item("timestamp_format");
		// 	$this->conn->Execute("alter session set nls_date_format='$date_format'");
		// 	$this->conn->Execute("alter session set NLS_TIMESTAMP_FORMAT='$timestamp_format'");
		// }
	}

	protected function FilterRequest()
	{
		$this->post = $this->input->post(null, $this->xss_clean);
		$this->escape_html($this->post);
		$this->get = $this->input->get(null, $this->xss_clean);
		$this->escape_html($this->get);;
		$this->request = array_merge($this->post, $this->get);
	}

	protected function escape_html(&$data)
	{
		if (!$this->escape_html)
			return true;

		$temp = $data;
		if (is_array($temp)) {
			foreach ($temp as $idkey => $value) {
				$this->escape_html($value);
				$data[$idkey] = $value;
			}
		} else {
			$data = htmlspecialchars($temp);
		}
	}

	protected function SetToken()
	{
		$this->data['token_name'] = $_SESSION[SESSION_APP][$this->page_ctrl]['token_name'] = str_shuffle('aabcdefghijklmnopqrstuvwxyz');
		$this->data['token_value'] = $_SESSION[SESSION_APP][$this->page_ctrl]['token_value'] = md5(uniqid(rand(), true));
	}

	protected function CekToken()
	{
		$token_name = $_SESSION[SESSION_APP][$this->page_ctrl]['token_name'];
		$token_value = $_SESSION[SESSION_APP][$this->page_ctrl]['token_value'];
		if (!$this->post[$token_name] or $this->post[$token_name] != $token_value) {
			die("=))");
		}
	}

	protected function Plugin()
	{
		if (!($this->plugin_arr))
			return;

		$plugin = array();
		$plugin['select2'] .= '<link href="' . base_url() . 'assets/plugins/select2/select2.min.css" rel="stylesheet" />';
		$plugin['select2'] .= '<script src="' . base_url() . 'assets/plugins/select2/select2.min.js"></script>';
		$plugin['select2'] .= '<script>$(function(){
            $(".select2, select.form-control").select2({
                placeholder: \'Cari\',
                allowClear: true,
				escapeMarkup: function(markup) {
					return markup;
				},
				templateResult: function(data) {
					if(data.html)
						return data.html;
					else
						return data.text;
				}
            });
		});
		</script>';
		#datatable

		$plugin['datatable'] .= '<script src="' . base_url() . 'assets/plugins/jquery-datatable/jquery.dataTables.js"></script>';
		$plugin['datatable'] .= '<script src="' . base_url() . 'assets/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.min.js"></script>';
		// $plugin['datatable'] .= '<link href="'.base_url().'assets/plugins/jquery-datatable/jquery.dataTables.min.css" rel="stylesheet" />';
		// $plugin['datatable'] .= '<link href="'.base_url().'assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.min.css" rel="stylesheet" />';

		#treetable
		$plugin['treetable'] .= '<script src="' . base_url() . 'assets/plugins/treetable/jquery.treetable.js"></script>';
		$plugin['treetable'] .= '<link href="' . base_url() . 'assets/plugins/treetable/jquery.treetable.css" rel="stylesheet" />';
		$plugin['treetable'] .= '<link href="' . base_url() . 'assets/plugins/treetable/jquery.treetable.theme.default.css" rel="stylesheet" />';
		// $plugin['treetable'] .= '<link href="'.base_url().'assets/plugins/treetable/screen.css" rel="stylesheet" />';
		$plugin['treetable'] .= '<script>$(function(){
			$(".treetable").treetable({ expandable: true });
			if($(".treetable")){
				$(".treetable").each(function(){$(this).treetable("expandAll")});
			}
			$(".treetableclose").treetable({ expandable: true });
		});</script>';

		#date picker
		$plugin['datepicker'] .= '<script src="' . base_url() . 'assets/plugins/momentjs/moment.js"></script>';
		$date_format = $this->config->item("date_format");

		$plugin['datepicker'] .= '<script src="' . base_url() . 'assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>';

		$plugin['datepicker'] .= '<script>$(function(){
			$(".datepicker").attr("autocomplete","off");
			$(".datetimepicker").datepicker({
		        format: "yyyy-mm-dd HH:mm:ss",
		        clearButton: true,
		        weekStart: 1
		    });
		    $(".datepicker").datepicker({
		        format: "yyyy-mm-dd",
		        clearButton: true,
		        weekStart: 1,
		        time: false
		    });
		    $(".datepickerstart").datepicker({
		        format: "yyyy-mm-dd",
		        clearButton: true,
		        weekStart: 1,
		        time: false,
		        minDate : new Date()
		    });

	        $(".timepicker").datepicker({
		        format: "HH:mm",
		        clearButton: true,
		        date: false
		    });

		});</script>';

		$plugin['datepicker'] .= '<link href="' . base_url() . 'assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />';

		$plugin['jscolor'] = '<script src="' . base_url() . 'assets/plugins/color/jscolor.js"></script>';


		#upload
		$plugin['upload'] .= '<link rel="stylesheet" href="' . base_url() . 'assets/plugins/upload/css/jquery.fileupload.css" />';
		$plugin['upload'] .= '<script src="' . base_url() . 'assets/plugins/upload/js/vendor/jquery.ui.widget.js"></script>';
		$plugin['upload'] .= '<script src="' . base_url() . 'assets/plugins/upload/js/jquery.iframe-transport.js"></script>';
		$plugin['upload'] .= '<script src="' . base_url() . 'assets/plugins/upload/js/jquery.fileupload.js"></script>';
		$plugin['tinymce'] .= '<script src="' . base_url() . 'assets/plugins/tinymce/tinymce.min.js"></script>';

		$plugin_arr = array_unique(array_values($this->plugin_arr));
		foreach ($plugin_arr as $k => $v) {
			$this->data['add_plugin'] .= $plugin[$v] . "\n";
		}
	}

	protected function SetPlugin($str)
	{
		if (is_array($str))
			$this->plugin_arr = array_merge($this->plugin_arr, $str);
		else
			$this->plugin_arr[] = $str;
	}

	protected function Helper($filename)
	{
		$this->load->helper($filename);
	}

	protected function Library($filename)
	{
		$this->load->library($filename);
	}

	public function NoData($str = 'Data tidak ditemukan.')
	{
		echo "<h2 align='center' style='margin-top:20%;color:#444'>Informasi</h2>$str";
		exit();
	}

	public function Error404($str = '')
	{
		$this->data['error_str'] = $str;
		$this->view("error404");
		exit();
	}

	public function Error403($str = '')
	{
		$this->data['error_str'] = $str;
		$this->view("error403");
		exit();
	}

	//load view with template
	protected function View($view = '')
	{

		$this->Plugin();

		$this->data['content'] = $this->load->view($view, $this->data, TRUE);
		echo $this->load->view($this->template, $this->data, true);
	}

	//load view without template
	protected function PartialView($view = '', $string = false)
	{

		//$this->Plugin();

		if ($string)
			return $this->load->view($view, $this->data, true);
		else
			echo $this->load->view($view, $this->data, true);
	}

	protected function Rules()
	{
	}

	protected function log($action = "", $data = array(), $databefore = array())
	{
		if (!$action) $action = "mengakses";

		/*		$actarr = array();

		$rules = $this->Rules();
		foreach($data as $k=>$v){
			$label = $rules[$k]['label'];

			if(!$label)
				$label = $k;

			$actarr[] = $label." : ".
		}*/

		unset($data['act']);
		unset($data['go']);
		unset($data['idkey']);

		$record = array();
		$record['page'] = $this->page_ctrl;
		$record['activity'] = substr(json_encode($data), 0, 4000);
		$record['action'] = $action;
		$record['ip'] = $_SERVER['REMOTE_ADDR'];
		// $record['activity_time'] = "{{sysdate()}}";
		$record['activity_time'] = "{{now()}}";
		$record['user_id'] = $_SESSION[SESSION_APP]['user_id'];

		$this->conn->goInsert("public_sys_log", $record);
	}

	public $_content_type = "application/json";
	private $_code = 200;

	public function success($data)
	{
		$response = ["success" => true];
		if ($data)
			$response["data"] = $data;

		$this->response(["data" => $data]);
	}

	public function fail($msg = null, $data = array(), $status = 400)
	{
		$this->_code = $status;
		$response = ["error_code" => $status, "error_message" => $msg ? $msg : $this->get_status_message()];
		if ($data)
			$response["data"] = $data;

		$this->response($response, $status);
	}

	public function response($data, $status = 200)
	{
		$this->_code = ($status) ? $status : 200;
		$this->set_headers();
		echo json_encode($data);
		exit;
	}

	private function get_status_message()
	{
		$status = array(
			100 => 'Continue',
			101 => 'Switching Protocols',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			306 => '(Unused)',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported'
		);
		return ($status[$this->_code]) ? $status[$this->_code] : $status[500];
	}

	private function set_headers()
	{
		header("HTTP/1.1 " . $this->_code . " " . $this->get_status_message());
		header("Content-Type:" . $this->_content_type);
	}

	protected function isTrueMethod($method)
	{
		$this->is_rest = true;
		self::__construct(false);
		if ($method != $_SERVER['REQUEST_METHOD'])
			$this->fail("Method not found !", 404);
	}

	protected function isTrustMethod($method = null)
	{
		$this->is_rest = true;
		if ($method)
			$this->isTrueMethod($method);
		else
			self::__construct(false);

		$token = $this->input->get_request_header('authentication');

		if (!$token)
			$this->fail("No authentication", null, 401);

		$key = $this->keyjwt;
		$this->jwt::$leeway = 1800;
		try {
			$decoded = $this->jwt::decode($token, new Key($key, 'HS256'));
		} catch (\Exception $e) {
			$this->fail($e->getMessage(), null, 401);
		}

		self::__construct();

		return json_decode(json_encode($decoded), true);
	}

	protected function requestData()
	{
		return json_decode($this->input->__get("raw_input_stream"), true);
	}
}
