<?php
date_default_timezone_set("Asia/Jakarta");
defined('BASEPATH') or exit('No direct script access allowed');

$config['base_url'] = (is_https() ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], basename($_SERVER['SCRIPT_FILENAME'])));
$config['index_page'] = '';
$config['uri_protocol']	= 'REQUEST_URI';
$config['url_suffix'] = '';
$config['language']	= 'indonesia';
$config['charset'] = 'UTF-8';
$config['enable_hooks'] = FALSE;
$config['subclass_prefix'] = '_';
$config['composer_autoload'] = FALSE;
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';
$config['allow_get_array'] = TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';
$config['log_threshold'] = 1;
$config['log_path'] = '';
$config['log_file_extension'] = '';
$config['log_file_permissions'] = 0644;
$config['log_date_format'] = 'Y-m-d H:i:s';
$config['error_views_path'] = '';
$config['cache_path'] = '';
$config['cache_query_string'] = FALSE;
$config['encryption_key'] = '';
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = NULL;
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = true;
$config['cookie_prefix']	= '';
$config['cookie_domain']	= '';
$config['cookie_path']		= null;
$config['cookie_secure']	= false;
$config['cookie_httponly'] 	= FALSE;
$config['standardize_newlines'] = FALSE;
$config['global_xss_filtering'] = FALSE;
$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array();
$config['compress_output'] = FALSE;
$config['time_reference'] = 'local';
$config['rewrite_short_tags'] = FALSE;
$config['proxy_ips'] = '';

$config['title'] = "MANAJEMEN RISIKO";
// $config['title'] = "Manajemen Risiko";
$config['copyright'] = 'Copyright &copy;' . date('Y');
$config['date_format'] = "%Y-%m-%d";
$config['timestamp_format'] = "%Y-%m-%d %T:%i:%s";

$config['company_name'] = "Perumda Trita Raharja";
$config['company_address'] = "Jl. Surabaya Solo No 99, Ngawi, Jawa Timur, Indonesia";
$config['company_telp'] = "(031) 8283180";
$config['company_email'] = "info@exacta.id";
$config['company_fax'] = "(031) 8283183";

$config['file_upload_config']['upload_path']          = './uploads/';
$config['file_upload_config']['allowed_types']        = 'rar|pdf|doc|docx|xls|xlsx|zip|ppt|pptx|jpg|png|jpeg';
$config['file_upload_config']['max_size']             = 10120; //kb

$config['file_upload_message_config'] = $config['file_upload_config'];
$config['file_upload_message_config']['max_size']     = 10000; //kb

$config['sso']['auth_page'] = false;

$config['email_config'] = array(
	'protocol' 	=> 'smtp',
	'smtp_host' 	=> 'smtp.gmail.com',
	'smtp_port' 	=> '587',
	'smtp_user' 	=> 'setting.smtp@gmail.com',
	'smtp_pass' 	=> 'ademinademin',
	'mailtype'  	=> 'html',
	'from'		=> 'setting.smtp@gmail.com',
	'fromlabel'	=> 'ERM PT EXACTA.ID',
	'reply_to'	=> 'setting.smtp@gmail.com',
	'smtp_crypto' => 'tls',
	'replylabel'	=> 'ERM PT EXACTA.ID',
	'charset'   	=> 'iso-8859-1',
	'extra'		=> '', //untuk penerima default
	'recipients'	=> '', //untuk testing penerima email
	'starttls'  => true,
	'newline'   => "\r\n"
);



#filenamager
$config['file_manager']['wallpaper_dir'] = "/ketonggo_project/ambapers_website/assets/fileupload/wallpaper/";
$config['file_manager']['merchandise_dir'] = "/ketonggo_project/ambapers_website/assets/fileupload/merchandise/";
$config['file_manager']['root'] = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
$config['file_manager']['upload_dir'] = "/ketonggo_project/ambapers_website/assets/fileupload/files/";
$config['file_manager']['thumbs_dir'] = "/ketonggo_project/ambapers_website/assets/fileupload/thumbs/";
$config['file_manager']['thumbs_ico'] = "/ketonggo_project/ambapers_website/assets/fileupload/ico/";
$config['file_manager']['max_size_upload'] = 100; #kb


$config['url_profil_pengawas'] = "http://192.168.0.50:8002/profil-pengawas?q=";
