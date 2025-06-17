<?php

define('lang_Select', 'Select');
define('lang_Erase', 'Erase');
define('lang_Open', 'Open');
define('lang_Confirm_del', 'Are you sure you want to delete this file?');
define('lang_All', 'All');
define('lang_Files', 'Files');
define('lang_Images', 'Images');
define('lang_Archives', 'Archives');
define('lang_Error_Upload', 'The uploaded file exceeds the max size allowed.');
define('lang_Error_extension', 'File extension is not allowed.');
define('lang_Upload_file', 'Upload');
define('lang_Filter', 'Filter');
define('lang_Videos', 'Videos');
define('lang_Music', 'Music');
define('lang_New_Folder', 'New Folder');
define('lang_Folder_Created', 'Folder correctly created');
define('lang_Existing_Folder', 'Existing folder');
define('lang_Confirm_Folder_del', 'Are you sure to delete the folder and all the elements in it?');
define('lang_Return_Files_List', 'Return to files list');
define('lang_Preview', 'Preview');
define('lang_Download', 'Download');
define('lang_Insert_Folder_Name', 'Insert folder name:');
define('lang_Root', 'root');
define('lang_Send_File', 'Send File');

function mime($ext)
{
	$mimes = array(
		'hqx'	=>	'application/mac-binhex40',
		'cpt'	=>	'application/mac-compactpro',
		'csv'	=>	array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel'),
		'bin'	=>	'application/macbinary',
		'dms'	=>	'application/octet-stream',
		'lha'	=>	'application/octet-stream',
		'lzh'	=>	'application/octet-stream',
		'exe'	=>	array('application/octet-stream', 'application/x-msdownload'),
		'class'	=>	'application/octet-stream',
		'psd'	=>	'application/x-photoshop',
		'so'	=>	'application/octet-stream',
		'sea'	=>	'application/octet-stream',
		'dll'	=>	'application/octet-stream',
		'oda'	=>	'application/oda',
		'pdf'	=>	array('application/pdf', 'application/x-download'),
		'ai'	=>	'application/postscript',
		'eps'	=>	'application/postscript',
		'ps'	=>	'application/postscript',
		'smi'	=>	'application/smil',
		'smil'	=>	'application/smil',
		'mif'	=>	'application/vnd.mif',
		'xls'	=>	array('application/excel', 'application/vnd.ms-excel', 'application/msexcel'),
		'ppt'	=>	array('application/powerpoint', 'application/vnd.ms-powerpoint'),
		'wbxml'	=>	'application/wbxml',
		'wmlc'	=>	'application/wmlc',
		'dcr'	=>	'application/x-director',
		'dir'	=>	'application/x-director',
		'dxr'	=>	'application/x-director',
		'dvi'	=>	'application/x-dvi',
		'gtar'	=>	'application/x-gtar',
		'gz'	=>	'application/x-gzip',
		'php'	=>	'application/x-httpd-php',
		'php4'	=>	'application/x-httpd-php',
		'php3'	=>	'application/x-httpd-php',
		'phtml'	=>	'application/x-httpd-php',
		'phps'	=>	'application/x-httpd-php-source',
		'js'	=>	'application/x-javascript',
		'swf'	=>	'application/x-shockwave-flash',
		'sit'	=>	'application/x-stuffit',
		'tar'	=>	'application/x-tar',
		'tgz'	=>	array('application/x-tar', 'application/x-gzip-compressed'),
		'xhtml'	=>	'application/xhtml+xml',
		'xht'	=>	'application/xhtml+xml',
		'zip'	=>  array('application/x-zip', 'application/zip', 'application/x-zip-compressed'),
		'rar'	=>  array('application/x-rar-compressed', 'application/octet-stream'),
		'mid'	=>	'audio/midi',
		'midi'	=>	'audio/midi',
		'mpga'	=>	'audio/mpeg',
		'mp2'	=>	'audio/mpeg',
		'mp3'	=>	array('audio/mpeg', 'audio/mpg', 'audio/mpeg3', 'audio/mp3'),
		'aif'	=>	'audio/x-aiff',
		'aiff'	=>	'audio/x-aiff',
		'aifc'	=>	'audio/x-aiff',
		'ram'	=>	'audio/x-pn-realaudio',
		'rm'	=>	'audio/x-pn-realaudio',
		'rpm'	=>	'audio/x-pn-realaudio-plugin',
		'ra'	=>	'audio/x-realaudio',
		'rv'	=>	'video/vnd.rn-realvideo',
		'wav'	=>	array('audio/x-wav', 'audio/wave', 'audio/wav'),
		'bmp'	=>	array('image/bmp', 'image/x-windows-bmp'),
		'gif'	=>	'image/gif',
		'jpeg'	=>	array('image/jpeg', 'image/pjpeg'),
		'jpg'	=>	array('image/jpeg', 'image/pjpeg'),
		'jpe'	=>	array('image/jpeg', 'image/pjpeg'),
		'png'	=>	array('image/png',  'image/x-png'),
		'tiff'	=>	'image/tiff',
		'tif'	=>	'image/tiff',
		'css'	=>	'text/css',
		'html'	=>	'text/html',
		'htm'	=>	'text/html',
		'shtml'	=>	'text/html',
		'txt'	=>	'text/plain',
		'text'	=>	'text/plain',
		'log'	=>	array('text/plain', 'text/x-log'),
		'rtx'	=>	'text/richtext',
		'rtf'	=>	'text/rtf',
		'xml'	=>	'text/xml',
		'xsl'	=>	'text/xml',
		'mpeg'	=>	'video/mpeg',
		'mpg'	=>	'video/mpeg',
		'mpe'	=>	'video/mpeg',
		'qt'	=>	'video/quicktime',
		'mov'	=>	'video/quicktime',
		'avi'	=>	'video/x-msvideo',
		'movie'	=>	'video/x-sgi-movie',
		'doc'	=>	'application/msword',
		'docx'	=>	'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'xlsx'	=>	'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'word'	=>	array('application/msword', 'application/octet-stream'),
		'xl'	=>	'application/excel',
		'eml'	=>	'message/rfc822',
		'json' 	=> array('application/json', 'text/json'),
		'pptx'	=> 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
	);
	return $mimes[$ext];
}

function array_ramdom($list)
{
	if (!is_array($list)) return $list;

	$keys = array_keys($list);
	shuffle($keys);
	$random = array();
	foreach ($keys as $idkey) {
		$random[$idkey] = $list[$idkey];
	}
	return $random;
}

function replaceSingleQuote(&$val)
{
	if (is_array($val)) {
		foreach ($val as $k => $v) {
			$val[$k] = replaceSingleQuote($v);
		}
	} else {
		$val = str_replace("'", "''", $val);
	}
}

function StrDifTime($time1, $time2)
{
	list($t1) = explode(" ", $time1);
	list($t2) = explode(" ", $time2);
	list($hari1, $bulan1, $tahun1) = explode("-", $t1);
	list($hari2, $bulan2, $tahun2) = explode("-", $t2);

	$hari = $hari1 - $hari2;

	if ($hari1 == $hari2 && $bulan1 == $bulan2 && $tahun1 == $tahun2)
		return DifTime($time1, $time2, false) . " yang lalu";
	elseif ($bulan1 == $bulan2 && $tahun1 == $tahun2 && 3 >= $hari)
		return $hari . " hari yang lalu";
	else
		return Eng2Ind($time2);
}

function ReadMore($text = '', $urlreadmore = '#', $readmore = true)
{
	if (is_object($text))
		return $text;

	$str = '';
	$str .= strstr($text, '<br /><!-- pagebreak --><br />', true);
	if (!$str) {
		$str .= strstr($text, '<!-- pagebreak -->', true);
	}
	if (!$str) {
		$readmore = false;
		$str .= $text;
	}
	if ($readmore) {
		$str .= '<a title="Read more" href="' . $urlreadmore . '" class="more">Read more →</a>';
	}
	$str .= "<div style='clear:both'></div>";


	return $str;
}

function DifTime($time1, $time2)
{
	$time1 = strtotime($time1);
	$time2 = strtotime($time2);
	$time3 = $time1 - $time2;
	$jam = floor($time3 / 3600);
	$time3 = $time3 % 3600;
	$menit = floor($time3 / 60);
	$time3 = $time3 % 60;
	$detik = $time3;
	return $jam . ' jam, ' . $menit . ' menit, ' . $detik . ' detik';
}



function ReadMorePlain($text = '', $count_word = 10)
{
	//$text = str_replace(array('<h2>','</h2>','<p>','</p>','<strong>','</strong>', '<ol>', '</ol>', '<li>', '</li>', '<!-- pagebreak -->'),'',$text);
	//$text = str_replace(array('  ', '&nbsp;',"\r\t","\r\n"), ' ', $text);
	$text = strip_tags($text);
	$text_arr = explode(' ', $text);

	$i = 0;
	$str = '';
	foreach ($text_arr as $idkey => $value) {
		$i++;
		$str .= $value . ' ';
		if ($count_word == $i)
			break;
	}

	return $str;
}

#2012-01-01
function Eng2Ind($datetime, $is_time = true)
{
	$ci = get_instance();
	$exp = explode(" ", $datetime);
	$date = $datetime;
	$time = '';
	if (($exp) > 1) {
		$time = substr($exp[1], 0, 8);
		$date = $exp[0];
	}

	if (!$is_time)
		$time = '';

	$exp1 = explode("-", $date);
	$list_bulan = ListBulan();
	$date_format = $ci->config->item("date_format");
	// if ($date_format == "YYYY-MM-DD")
	return $exp1[2] . ' ' . $list_bulan[$exp1[1]] . ' ' . $exp1[0] . ' ' . $time;
	// else
	// 	return $exp1[0] . ' ' . $list_bulan[$exp1[1]] . ' ' . $exp1[2] . ' ' . $time;
}

function RevertDate($date)
{
	list($d, $m, $y) = explode("-", $date);
	return $y . "-" . $m . "-" . $d;
}

function ListBulan()
{
	return array(
		'01' => 'Januari',
		'02' => 'Februari',
		'03' => 'Maret',
		'04' => 'April',
		'05' => 'Mei',
		'06' => 'Juni',
		'07' => 'Juli',
		'08' => 'Agustus',
		'09' => 'September',
		'10' => 'Oktober',
		'11' => 'Nopember',
		'12' => 'Desember',
	);
}

function ListHari()
{
	return array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu');
}

function Hari($i)
{
	$hari_arr = ListHari();
	return $hari_arr[$i];
}

function DateHari($date)
{
	$time = strtotime($date);
	return Hari(date('w', $time));
}

function DateDiff($besar, $kecil)
{
	$a = strtotime($besar);
	$b = strtotime($kecil);
	$c = $a - $b;
	if ($c >= 86400) {
		$hari = floor($c / 86400) . ' hari ';
		$c = $c % 86400;
	}
	if ($c >= 3600) {
		$jam = floor($c / 3600) . ' jam ';
		$c = $c % 3600;
	}
	if ($c >= 60) {
		$menit = floor($c / 60) . ' menit ';
		$c = $c % 60;
	}
	return $hari . $jam . $menit;
}

function DNDcheck($mobileno)
{
	$mobileno = substr($mobileno, -10, 10);
	$url = "http://www.nccptrai.gov.in/nccpregistry/saveSearchSub.misc";
	$postString = "phoneno=" . $mobileno;
	$request = curl_init($url);
	curl_setopt($request, CURLOPT_HEADER, 0);
	//curl_setopt($request , CURLOPT_PROXY , '10.3.100.211:8080' );
	curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($request, CURLOPT_POST, 1);
	curl_setopt($request, CURLOPT_POSTFIELDS, $postString);
	curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE);
	$response = curl_exec($request);
	curl_close($request);

	return (is_int(strpos(strtolower(strip_tags($response)), "number is not")) ? false : true);
}

function filter_data($data)
{
	if ($data == NULL) return "<i>Unknown</i>";
	else return $data;
}

function nice_date($str, $option = NULL)
{
	// convert the date to unix timestamp
	list($date, $time) = explode(' ', $str);
	list($year, $month, $day) = explode('-', $date);
	list($hour, $minute, $second) = explode(':', $time);

	$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
	$now = time();
	$blocks = array(
		array('name' => lang('kalkun_year'), 'amount' => 60 * 60 * 24 * 365),
		array('name' => lang('kalkun_month'), 'amount' => 60 * 60 * 24 * 31),
		array('name' => lang('kalkun_week'), 'amount' => 60 * 60 * 24 * 7),
		array('name' => lang('kalkun_day'), 'amount' => 60 * 60 * 24),
		array('name' => lang('kalkun_hour'), 'amount' => 60 * 60),
		array('name' => lang('kalkun_minute'), 'amount' => 60),
		array('name' => lang('kalkun_second'), 'amount' => 1)
	);

	if ($timestamp > $now) $string_type = ' remaining';
	else $string_type = ' ' . lang('kalkun_ago');

	$diff = abs($now - $timestamp);

	if ($option == 'smsd_check') {
		return $diff;
	} else {
		if ($diff < 60) {
			return "Less than a minute ago";
		} else {
			$levels = 1;
			$current_level = 1;
			$result = array();
			foreach ($blocks as $block) {
				if ($current_level > $levels) {
					break;
				}
				if ($diff / $block['amount'] >= 1) {
					$amount = floor($diff / $block['amount']);
					$plural = '';
					//if ($amount>1) {$plural='s';} else {$plural='';}
					$result[] = $amount . ' ' . $block['name'] . $plural;
					$diff -= $amount * $block['amount'];
					$current_level += 1;
				}
			}
			$res = implode(' ', $result) . '' . $string_type;
			return $res;
		}
	}
}

function get_modem_status($status, $tolerant)
{
	// convert the date to unix timestamp
	list($date, $time) = explode(' ', $status);
	list($year, $month, $day) = explode('-', $date);
	list($hour, $minute, $second) = explode(':', $time);

	$timestamp = mktime($hour, $minute + $tolerant, $second, $month, $day, $year);
	$now = time();

	//$diff = abs($now-$timestamp);
	if ($timestamp > $now) {
		return "connect";
	} else {
		return "disconnect";
	}
}

function message_preview($str, $n)
{
	if (strlen($str) <= $n) return showtags($str);
	else return showtags(substr($str, 0, $n - 3)) . '&#8230;';
}

function showtags($msg)
{
	$msg = preg_replace("/</", "&lt;", $msg);
	$msg = preg_replace("/>/", "&gt;", $msg);
	return $msg;
}

function showmsg($msg)
{
	return nl2br(showtags($msg));
}

function compare_date_asc($a, $b)
{
	$date1 = strtotime($a['globaldate']);
	$date2 = strtotime($b['globaldate']);

	if ($date1 == $date2) return 0;
	return ($date1 < $date2) ? -1 : 1;
}

function compare_date_desc($a, $b)
{
	$date1 = strtotime($a['globaldate']);
	$date2 = strtotime($b['globaldate']);

	if ($date1 == $date2) return 0;
	return ($date1 > $date2) ? -1 : 1;
}

function check_delivery_report($report)
{
	if ($report == 'SendingError' or $report == 'Error' or $report == 'DeliveryFailed') : $status = lang('tni_msg_stat_fail');
	elseif ($report == 'SendingOKNoReport') : $status = lang('tni_msg_stat_oknr');
	elseif ($report == 'SendingOK') : $status = lang('tni_msg_stat_okwr');
	elseif ($report == 'DeliveryOK') : $status = lang('tni_msg_stat_deliv');
	elseif ($report == 'DeliveryPending') : $status = lang('tni_msg_stat_pend');
	elseif ($report == 'DeliveryUnknown') : $status = lang('tni_msg_stat_unknown');
	endif;

	return $status;
}

function simple_date($datetime)
{
	list($date, $time) = explode(' ', $datetime);
	list($year, $month, $day) = explode('-', $date);
	return $day . '/' . $month . '/' . $year . ' ' . $time;
}

function get_hour()
{
	for ($i = 0; $i < 24; $i++) {
		$hour = $i;
		if ($hour < 10) $hour = "0" . $hour;
		echo "<option value=\"" . $hour . "\">" . $hour . "</option>";
	}
}

function get_minute()
{
	for ($i = 0; $i < 60; $i = $i + 5) {
		$min = $i;
		if ($min < 10) $min = "0" . $min;
		echo "<option value=\"" . $min . "\">" . $min . "</option>";
	}
}

function is_ajax()
{
	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		return TRUE;
	} else {
		return FALSE;
	}
}

function rupiah($number)
{
	if (is_array($number)) return null;
	if (!$number) return 0;

	if (strlen(round($number)) === strlen($number))
		return number_format($number, 0, ",", ".");

	return number_format($number, 2, ",", ".");
}

function rupiahAngka($angka)
{
	if (!$angka) return 0;
	$rupiah = number_format($angka, 2, ",", ".");
	return "Rp " . $rupiah;
}

function terbilang($satuan)
{
	$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	if ($satuan < 0) {
		$add = " minus ";
		$satuan  = abs($satuan);
	}

	if ($satuan < 12)
		return $add . " " . $huruf[$satuan];
	elseif ($satuan < 20)
		return terbilang($satuan - 10) . " belas";
	elseif ($satuan < 100)
		return $add . terbilang($satuan / 10) . " puluh" . terbilang($satuan % 10);
	elseif ($satuan < 200)
		return "seratus" . terbilang($satuan - 100);
	elseif ($satuan < 1000)
		return $add . terbilang($satuan / 100) . " ratus" . terbilang($satuan % 100);
	elseif ($satuan < 2000)
		return $add . "seribu" . terbilang($satuan - 1000);
	elseif ($satuan < 1000000)
		return $add . terbilang($satuan / 1000) . " ribu" . terbilang($satuan % 1000);
	elseif ($satuan < 1000000000)
		return $add . terbilang($satuan / 1000000) . " juta" . terbilang($satuan % 1000000);
	elseif ($satuan >= 1000000000)
		return "Angka yang Anda masukkan terlalu besar";
}

function convert($size)
{
	$unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
	return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}
function memory_used()
{
	return convert(memory_get_usage(true)); // 123 kb
}
function rutime($ru, $rus, $index)
{
	return ($ru["ru_$index.tv_sec"] * 1000 + intval($ru["ru_$index.tv_usec"] / 1000))
		-  ($rus["ru_$index.tv_sec"] * 1000 + intval($rus["ru_$index.tv_usec"] / 1000));
}


function delete_folder($dir)
{
	if (!file_exists($dir)) return true;
	if (!is_dir($dir)) return unlink($dir);
	foreach (scandir($dir) as $item) {
		if ($item == '.' || $item == '..') continue;
		if (!delete_folder($dir . DIRECTORY_SEPARATOR . $item)) return false;
	}
	return rmdir($dir);
}

function create_img_gd($imgfile, $imgthumb, $newwidth, $newheight = "")
{

	$ci = get_instance();

	$ci->load->library("Imagelib", array('fileName' => $imgfile));
	// *** Resize to best fit then crop
	$ci->imagelib->resizeImage($newwidth, $newheight, 'crop');

	// *** Save resized image as a PNG
	$ci->imagelib->saveImage($imgthumb);
}

function makeSize($size)
{
	$units = array('B', 'KB', 'MB', 'GB', 'TB');
	$u = 0;
	while ((round($size / 1024) > 0) && ($u < 4)) {
		$size = $size / 1024;
		$u++;
	}
	return (number_format($size, 1, ',', '') . " " . $units[$u]);
}

function create_folder($path = false)
{
	$oldumask = umask(0);
	if ($path && !file_exists($path))
		mkdir($path, 0775); // or even 01777 so you get the sticky bit set

	umask($oldumask);
}

function Title($title = "", $add = true)
{
	$ci = get_instance();
	if ($title && $add)
		return $ci->config->item("title") . " | " . strip_tags($title);
	else if ($title && !$add)
		return strip_tags($title);
	else
		return $ci->config->item("title");
}


function FlashMsg()
{
	$ci = get_instance();
	if (Get($ci->ctrl . 'suc_msg')) {
		$ci->data['suc_msg'] = GetFlash($ci->ctrl . 'suc_msg');
	}
	if (Get($ci->ctrl . 'inf_msg')) {
		$ci->data['inf_msg'] = GetFlash($ci->ctrl . 'inf_msg');
	}
	if (Get($ci->ctrl . 'wrn_msg')) {
		$ci->data['wrn_msg'] = GetFlash($ci->ctrl . 'wrn_msg');
	}
	if (Get($ci->ctrl . 'err_msg')) {
		$ci->data['err_msg'] = GetFlash($ci->ctrl . 'err_msg');
	}

	if ($ci->data['suc_msg']) {
		echo '
		<div class="alert-dismissible alert alert-success" role="alert">
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		' . $ci->data['suc_msg'] . '
		</div>';
	}
	if ($ci->data['inf_msg']) {
		echo '
		<div class="alert-dismissible alert alert-info" role="alert">
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		' . $ci->data['inf_msg'] . '
		</div>';
	}
	if ($ci->data['wrn_msg']) {
		echo '
		<div class="alert-dismissible alert alert-warning" role="alert">
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		' . $ci->data['wrn_msg'] . '
		</div>';
	}
	if ($ci->data['err_msg']) {
		echo '
		<div class="alert-dismissible alert alert-danger" role="alert">
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		' . $ci->data['err_msg'] . '
		</div>';
	}

	if ($ci->data['err_msg'] or $ci->data['wrn_msg'] or $ci->data['inf_msg'] or $ci->data['suc_msg'])
		echo '<script>$(function(){sessionStorage.scrollTop=0;});</script>';
}

function SetFlash($idkey, $msg)
{
	$ci = get_instance();
	Set($ci->ctrl . $idkey, $msg);
}

function SetPage($keys, $val)
{
	$ci = get_instance();

	if (is_string($keys)) {
		$_SESSION[SESSION_APP][$ci->page_ctrl][$keys] = $val;
		return;
	}

	if (is_array($keys)) {
		foreach ($keys as $idkey => $value) {
			# code...
			$_SESSION[SESSION_APP][$ci->page_ctrl][$idkey] = $value;
		}
	}
}

function GetPage($idkey)
{
	$ci = get_instance();
	return $_SESSION[SESSION_APP][$ci->page_ctrl][$idkey];
}

function Set($keys, $val)
{
	if (is_string($keys)) {
		$_SESSION[SESSION_APP][$keys] = $val;
		return;
	}

	if (is_array($keys)) {
		foreach ($keys as $idkey => $value) {
			# code...
			$_SESSION[SESSION_APP][$idkey] = $value;
		}
	}
}

function Get($idkey)
{
	return $_SESSION[SESSION_APP][$idkey];
}

function GetFlash($idkey)
{
	$return = $_SESSION[SESSION_APP][$idkey];
	unset($_SESSION[SESSION_APP][$idkey]);
	return $return;
}

function CreateList($data)
{
	$ret = array();
	foreach ($data as $r) {
		$ret[$r['idkey']] = $r['val'];
	}
	return $ret;
}

function var2alias($str)
{
	$data = array(
		'panelbackend/risk_scorecard' => 'Kajian Risiko',
		'panelbackend/risk_risiko' => 'Risiko',
		'panelbackend/risk_sasaran_kegiatan' => 'Kegiatan',
		'panelbackend/risk_control' => "Kontrol",
		'panelbackend/risk_mitigasi' => "Mitigasi",
		'panelbackend/risk_evaluasi' => "Review"
	);

	if ($data[$str])
		return $data[$str];
	else
		return $str;
}

function Access($mode, $page = null)
{
	$ci = &get_instance();
	return $ci->Access($mode, $page);
}

function accessbystatus($id_status_pengajuan = null)
{
	$ci = &get_instance();
	$page_ctrl = "panelbackend/" . strstr($ci->ctrl, '_', true) . "_scorecard";

	if (Access("view_all", "main"))
		return true;

	#posisi user
	if (Access("pengajuan", $page_ctrl) && (in_array($id_status_pengajuan, [1, 4, 5, 6])) || !$id_status_pengajuan)
		return true;

	#posisi KOORDINATOR
	if (Access("penerusan", $page_ctrl) && in_array($id_status_pengajuan, [1, 2, 4, 6, 5]))
		return true;

	#posisi direksi
	if (Access("persetujuan", $page_ctrl) && in_array($id_status_pengajuan, [3, 7]))
		return true;

	if (Access("view_all", "main"))
		return true;

	return false;
}

function labelstatuspemeriksaan($id_status)
{
	$arr = array(
		'1' => '<div class="badge bg-danger">PERENCANAAN</div>',
		'2' => '<div class="badge bg-warning">PEMERIKSAAN</div>',
		'3' => '<div class="badge bg-warning">REVIU PENGENDALI TEKNIS</div>',
		'4' => '<div class="badge bg-warning">REVIU KOORDINATOR</div>',
		'5' => '<div class="badge bg-warning">KONFIRMASI AUDITEE</div>',
		'6' => '<div class="badge bg-success">MONEV</div>',
		'7' => '<div class="badge bg-primary">SELESAI</div>',
	);

	return $arr[$id_status];
}

function labelstatus($id_status_pengajuan)
{
	/*$arr = array(
		'1'=>'<span class="badge bg-light text-dark">DRAFT</span>',
		'2'=>'<span class="badge bg-warning">DIAJUKAN KE OWNER</span>',
		'3'=>'<span class="badge bg-light text-dark">DIKEMBALIKAN KE KOORDINATOR</span>',
		'4'=>'<span class="badge bg-success">DITERUSAN KE REVIEWER</span>',
		'5'=>'<span class="badge bg-warning">DIKEMBALIKAN KE OWNER</span>',
		'6'=>'<span class="badge bg-primary">DISETUJUI</span>',
	);*/
	$arr = array(
		'1' => '<div class="badge bg-dark text-light">DRAFT</div>',
		// '2' => '<div class="badge bg-warning">DIAJUKAN KE KOORDINATOR</div>',
		'2' => '<div class="badge bg-warning">DIAJUKAN KE PEMILIK RESIKO</div>',
		// '3' => '<div class="badge bg-success">DISETUJUI DAN DITERUSAN KE DIREKSI</div>',
		'3' => '<div class="badge bg-success">DISETUJUI DAN DITERUSAN KE KOORDINATOR</div>',
		'4' => '<div class="badge bg-danger">DIKEMBALIKAN</div>',
		'5' => '<div class="badge bg-primary">PROSES PENGENDALIAN LANJUTAN</div>',
		'6' => '<div class="badge bg-danger">DIKEMBALIKAN KE KOORDINATOR</div>',
		'7' => '<div class="badge bg-warning">EVALUASI DI KOORDINATOR</div>',
		'8' => '<div class="badge bg-warning">EVALUASI DI SPI</div>',
		'9' => '<div class="badge bg-warning">PERSETUJUAN DIREKSI</div>',
		'10' => '<div class="badge bg-warning">EVALUASI DI KOORDINATOR</div>',
	);

	return $arr[$id_status_pengajuan];
}

function labelstatusevaluasi($id_status_pengajuan)
{
	$arr = array(
		'' => '<span class="material-icons text-success">mode</span>',
		'0' => '<span class="material-icons text-success">mode</span>',
		'1' => '<span class="material-icons text-success">done</span>',
	);

	return $arr[$id_status_pengajuan];
}

function labelstatusrisiko($status_risiko)
{
	$arr = array(
		'0' => '<span class="badge bg-dark text-light">CLOSED</span>',
		'1' => '<span class="badge bg-success">OPEN</span>',
		'2' => '<span class="badge bg-warning">BERLANJUT</span>',
	);
	return $arr[$status_risiko];
}

function listefektifitas()
{
	$arr = array(
		'1' => 'Efektif',
		'2' => 'Tidak Efektif',
	);

	return $arr;
}

function labelefektifitas($status = null)
{
	$arr = array(
		'1' => '<span class="badge bg-primary">EFEKTIF</span>',
		'2' => '<span class="badge bg-danger">TIDAK EFEKTIF</span>',
	);
	if ($arr[$status])
		return $arr[$status];
	else
		return '';
}

function labelkonfirmasi($status = null)
{
	$arr = array(
		'0' => '<span class="badge bg-warning">DALAM KONFIRMASI</span>',
		'1' => '<span class="badge bg-success">DISETUJUI</span>',
		'2' => '<span class="badge bg-danger">DITOLAK</span>',
		'3' => '<span class="badge bg-info">DELIGASI</span>',
		'4' => '<span class="badge bg-info">APPROVAL</span>',
	);
	if ($arr[$status])
		return $arr[$status];
	else
		return '';
}

function labeltingkatrisiko($id_tingkat = null, $rowspan = 0, $colspan = 0)
{

	$ci = get_instance();
	$mtriskmatrixarr = $ci->data['mttingkatdampakarr1'];
	// dpr($mtriskmatrixarr);
	$risk_opp = $ci->data['risk_opp'];

	$strrowspan = "";
	if ($rowspan)
		$strrowspan = " rowspan = '$rowspan' ";

	$strcolspan = "";
	if ($colspan)
		$strcolspan = " rowspan = '$colspan' ";

	$matrixarr = array();
	foreach ($risk_opp as $d) {
		foreach ($mtriskmatrixarr as $r) {
			// $matrixarr[$r['id_kemungkinan'] . $r['id_dampak']] = "<td align='center' $strrowspan $strcolspan><label class='badge' style='background-color:$r[warna]; color:#000;'>{$r['rating_kemungkinan']}{$r['rating_dampak']}</label></td>";

			$nilai = abs($r['rating_kemungkinan'] * $r['rating_dampak'] * $d);
			if ($d === 1)
				$matrixarr[$r['id_kemungkinan'] * $r['id_dampak'] * $d] = "<td align='center' $strrowspan $strcolspan><label class='badge' style='background-color:$r[warna_peluang]; color:#fff;'>{$nilai}</label></td>";
			else
				$matrixarr[$r['id_kemungkinan'] * $r['id_dampak'] * $d] = "<td align='center' $strrowspan $strcolspan><label class='badge' style='background-color:$r[warna]; color:#fff;'>{$nilai}</label></td>";
		}
	}

	if ($matrixarr[$id_tingkat] == '-pilih-' or !$matrixarr[$id_tingkat])
		return "<td $strrowspan $strcolspan></td>";

	return $matrixarr[$id_tingkat];
}

function labeltingkatpeluang($id_tingkat = null, $rowspan = 0, $colspan = 0)
{

	$ci = get_instance();
	$mtriskmatrixarr = $ci->data['mttingkatdampakpeluangarr1'];
	// dpr($mtriskmatrixarr);
	$risk_opp = $ci->data['risk_opp'];

	$strrowspan = "";
	if ($rowspan)
		$strrowspan = " rowspan = '$rowspan' ";

	$strcolspan = "";
	if ($colspan)
		$strcolspan = " rowspan = '$colspan' ";

	$matrixarr = array();
	$d = 1;
	// foreach ($risk_opp as $d) {
	foreach ($mtriskmatrixarr as $r) {
		// $matrixarr[$r['id_kemungkinan'] . $r['id_dampak']] = "<td align='center' $strrowspan $strcolspan><label class='badge' style='background-color:$r[warna]; color:#000;'>{$r['rating_kemungkinan']}{$r['rating_dampak']}</label></td>";

		$nilai = abs($r['rating_kemungkinan'] * $r['rating_dampak'] * $d);
		// if ($d === 1)
		// 	$matrixarr[$r['id_kemungkinan'] * $r['id_dampak'] * $d] = "<td align='center' $strrowspan $strcolspan><label class='badge' style='background-color:$r[warna_peluang]; color:#fff;'>{$nilai}</label></td>";
		// else
			$matrixarr[$r['id_kemungkinan'] * $r['id_dampak'] * $d] = "<td align='center' $strrowspan $strcolspan><label class='badge' style='background-color:$r[warna]; color:#fff;'>{$nilai}</label></td>";
	}
	// }

	// dpr($mtriskmatrixarr);
	// dpr($matrixarr);

	if ($matrixarr[$id_tingkat] == '-pilih-' or !$matrixarr[$id_tingkat])
		return "<td $strrowspan $strcolspan></td>";

	return $matrixarr[$id_tingkat];
}



if (!function_exists('stats_standard_deviation')) {
	function stats_standard_deviation(array $a, $sample = false)
	{
		$n = count($a);
		if ($n === 0) {
			trigger_error("The array has zero elements", E_USER_WARNING);
			return false;
		}
		if ($sample && $n === 1) {
			trigger_error("The array has only 1 element", E_USER_WARNING);
			return false;
		}
		$mean = array_sum($a) / $n;
		$carry = 0.0;
		foreach ($a as $val) {
			$d = ((float) $val) - $mean;
			$carry += $d * $d;
		};
		if ($sample) {
			--$n;
		}
		return sqrt($carry / $n);
	}
}

function spacetext($text)
{
	$exp = explode(" ", $text);
	if (!is_array($exp))
		$exp = [$exp];
	$ret = "";
	foreach ($exp as $v) {
		if (strlen($v) > 6)
			$ret .= " " . substr($v, 0, 6) . " " . substr($v, 6, 100);
		else
			$ret .= " " . $v;
	}
	return trim($ret);
}

function labeltingkatrisikolabel($id_tingkat = null)
{

	$ci = get_instance();
	$mtriskmatrixarr = $ci->data['mttingkatdampakarr1'];

	$matrixarr = array();
	foreach ($mtriskmatrixarr as $r) {
		$matrixarr[$r['id_kemungkinan'] . $r['id_dampak']] = "<label class='badge' style='background-color:$r[warna]; color:#000;'>{$r['rating_kemungkinan']}{$r['rating_dampak']}</label>";
	}

	if ($matrixarr[$id_tingkat] == '-pilih-' or !$matrixarr[$id_tingkat])
		return "";

	return $matrixarr[$id_tingkat];
}

function dpr($arr, $die = false)
{
	echo "<pre>";
	var_dump($arr);
	echo "</pre>";
	if ($die)
		die();
}

function Rupiah2Number($str)
{
	return str_replace(",", ".", str_replace(".", "", $str));
}


function HitungCBA($nilai_cr, $nilai_rr, $revenue, $implement_cost, $is_debug = false)
{
	#cba = (revenue * rating current risk) - (revenue * rating residual risk) / biaya

	$baseline_cost = 0;
	$baseline_cost = (float)$revenue * (float)$nilai_cr;
	$residual_cost = 0;
	$residual_cost = (float)$revenue * (float)$nilai_rr;
	$benefit_cost = 0;
	$benefit_cost = $baseline_cost - $residual_cost;
	$cba = 0;

	if ($is_debug) {
		echo "baseline_cost $baseline_cost <br/>";
		echo "residual_cost $residual_cost <br/>";
		echo "benefit_cost $benefit_cost <br/>";
		echo "baseline_cost $baseline_cost <br/>";
		echo "implement_cost $implement_cost <br/>";
	}

	if (!(float)$implement_cost)
		return 0;
	else
		$cba = (float)$benefit_cost / (float)$implement_cost;

	return round($cba, 1);
}

function RefererPageCtrl()
{
	$exp = explode("/", str_replace(site_url(), "", $_SERVER['HTTP_REFERER']));
	return $page_ctrl = $exp[0] . "/" . $exp[1];
}


function waktu_lalu($timestamp, $now, $limit_jam = true)
{
	$nowtime = time();

	$timestamp = substr($timestamp, 0, 19);
	$now = substr($now, 0, 19);

	if ($now)
		$nowtime = strtotime($now);

	$selisih = $nowtime - strtotime($timestamp);

	$detik = $selisih;
	$menit = round($selisih / 60);
	$jam = round($selisih / 3600);
	$hari = round($selisih / 86400);
	$minggu = round($selisih / 604800);
	$bulan = round($selisih / 2419200);
	$tahun = round($selisih / 29030400);

	if ($limit_jam) {
		if ($detik <= 60) {
			$waktu = $detik . ' detik yang lalu';
		} else if ($menit <= 60) {
			$waktu = $menit . ' menit yang lalu';
		} else if ($jam <= 24) {
			list($tgl, $waktu) = explode(" ", $timestamp);

			if (!$waktu)
				$waktu = $jam . ' jam yang lalu';
		} else {
			list($tgl, $waktu) = explode(" ", $timestamp);
			$waktu = DateHari($tgl) . ", " . Eng2Ind(RevertDate($tgl)) . " " . $waktu;
		}
	} else {
		if ($detik <= 60) {
			$waktu = $detik . ' detik yang lalu';
		} else if ($menit <= 60) {
			$waktu = $menit . ' menit yang lalu';
		} else if ($jam <= 24) {
			$waktu = $jam . ' jam yang lalu';
		} else if ($hari <= 7) {
			$waktu = $hari . ' hari yang lalu';
		} else if ($minggu <= 4) {
			$waktu = $minggu . ' minggu yang lalu';
		} else if ($bulan <= 12) {
			$waktu = $bulan . ' bulan yang lalu';
		} else {
			$waktu = $tahun . ' tahun yang lalu';
		}
	}

	return $waktu;
}

function get_key($data, $col)
{
	if (!is_array($data))
		return array();

	$dataarr = array();
	foreach ($data as $r) {
		$dataarr[] = $r[$col];
	}

	return $dataarr;
}

function get_key_str($data, $col, $delimiter = ",")
{
	$r = get_key($data, $col);

	return implode($delimiter, $r);
}

function ext($file_name)
{
	$exp = explode(".", $file_name);

	return $exp[count($exp) - 1];
}

function is_count($d = array())
{
	if (!$d)
		return 0;

	return count($d);
}

function labelverified($row)
{
	$ret = null;
	if ($row['rekomendasi_is_verified'] == '1')
		$ret .= "<label class='badge bg-warning'>Reviu dan rekomendasi $row[rekomendasi_group]</label><label class='badge bg-success'><span class='bi bi-ok'></span> Verified</label>&nbsp;&nbsp;";
	elseif ($row['rekomendasi_is_verified'] == '2')
		$ret .= "<label class='badge bg-warning'>Reviu dan rekomendasi $row[rekomendasi_group]</label>&nbsp;&nbsp;";

	if ($row['review_is_verified'] == '1')
		$ret .= "<label class='badge bg-warning'>Reviu dan rekomendasi $row[review_group]</label><label class='badge bg-success'><span class='bi bi-ok'></span> Verified</label>&nbsp;&nbsp;";
	elseif ($row['review_is_verified'] == '2')
		$ret .= "<label class='badge bg-warning'>Reviu dan rekomendasi $row[review_group]</label>&nbsp;&nbsp;";

	if ($ret)
		$ret = "<br/>" . $ret;

	return $ret;
}

function script_name()
{
	return str_replace("index.php", "", $_SERVER['SCRIPT_NAME']);
}

function host()
{
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$domainName = $_SERVER['HTTP_HOST'] . '/';
	return $protocol . $domainName;
}

function file_manager_ext($param = "")
{
	$ext_img = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff');
	$ext_file = array('doc', 'docx', 'pdf', 'xls', 'xlsx', 'txt', 'csv', 'html', 'psd', 'sql', 'log', 'fla', 'xml', 'ade', 'adp', 'ppt', 'pptx');
	$ext_misc = array('zip', 'rar', 'gzip');
	$ext_video = array('mov', 'mpeg', 'mp4', 'avi', 'mpg', 'wma');
	$ext_music = array('mp3', 'm4a', 'ac3', 'aiff', 'mid');

	if (isset(${$param}))
		return ${$param};

	return array_merge($ext_img, $ext_file, $ext_misc, $ext_video, $ext_music);
}

function file_manager_image($param = "")
{
	$arr = array(
		'image_max_width' => 0,
		'image_max_height' => 0,
		'image_resizing' => false,
		'image_width' => 600,
		'image_height' => 0,
	);
	return $arr[$param];
}

function file_manager_permit($param = "")
{
	$arr = array(
		'delete_file' => true,
		'create_folder' => true,
		'delete_folder' => true,
		'upload_files' => true,
	);
	return $arr[$param];
}

function nl2brword($text = null)
{
	if ($text)
		return str_replace("\n", "<w:br/>", $text);
	else
		return null;
}

function jabatan_bawahan($id_jabatan, $id_unit = null)
{
	$ci = get_instance();
	$ret = array();

	$rows = $ci->conn->GetArray("select id_jabatan, id_unit from mt_sdm_jabatan where id_jabatan_parent = " . $ci->conn->escape($id_jabatan) . " and id_unit = " . $ci->conn->escape($id_unit));

	foreach ($rows as $r) {
		$ret[] = $r['id_jabatan'];
		$bawahan = jabatan_bawahan($r['id_jabatan'], $r['id_unit']);
		$ret = array_merge($ret, $bawahan);
	}
	return $ret;
}


function loop_periode($param, $fn, &$ret)
{
	$id_interval = $param['id_interval'];
	$tahun = $param['tahun'];
	$bulan = $param['bulan'];

	$ci = &get_instance();

	if (!$ci->data['mtintervalarr2'][$id_interval])
		$ci->data['mtintervalarr2'][$id_interval] = $ci->conn->GetRow("select * from mt_interval where id_interval = " . $ci->conn->escape($id_interval));

	$row = $ci->data['mtintervalarr2'][$id_interval];

	$konversi = $row['konversi_bulan'];

	if ($konversi) {
		if ($row['jenis'] == '1') {
			$jum_bulan = 12 / $konversi;
			for ($i = 0; $i < $jum_bulan; $i++) {
				$bulan = ($konversi * $i) + 1;
				$bulan = str_pad($bulan, 2, "0", STR_PAD_LEFT);
				$tgl = $tahun . "-"  . $bulan  . "-01";

				if ($jum_bulan == 12) {
					$listbulan = ListBulan();
				} elseif ($jum_bulan == 1) {
					$listbulan[$bulan] = $tahun;
				} else {
					$listbulan[$bulan] = ($i + 1);
				}

				$arr = $param;
				$arr['id_interval'] = $id_interval;
				$arr['tahun'] = $tahun;
				$arr['bulan'] = $bulan;
				$arr['tgl'] = $tgl;
				$arr['listbulan'] = $listbulan;

				$fn($arr, $ret);
			}
		} elseif ($tahun) {
			if ($bulan)
				$bulanarr = array($bulan => ListBulan()[str_pad($bulan, 2, "0", STR_PAD_LEFT)]);
			else
				$bulanarr = ListBulan();

			foreach ($bulanarr as $bln => $nama_bulan) {
				$day = cal_days_in_month(CAL_GREGORIAN, $bln, $tahun);
				$jum_day = $day / $konversi;

				$listbulan = array();

				for ($i = 1; $i <= $jum_day; $i++) {
					$hari = str_pad($i, 2, "0", STR_PAD_LEFT);
					$listbulan[$hari] = $hari . ' bulan ' . $bulanarr[$bln];
				}


				for ($i = 1; $i <= $jum_day; $i++) {
					$bulan1 = str_pad($bln, 2, "0", STR_PAD_LEFT);
					$hari = str_pad($i, 2, "0", STR_PAD_LEFT);
					$tgl =  $tahun . "-" . $bulan1 . "-" . $hari;

					$arr = $param;

					$arr = $param;
					$arr['id_interval'] = $id_interval;
					$arr['tahun'] = $tahun;
					$arr['bulan'] = $hari;
					$arr['tgl'] = $tgl;
					$arr['listbulan'] = $listbulan;

					$fn($arr, $ret);
				}
			}
		}
	}
}
