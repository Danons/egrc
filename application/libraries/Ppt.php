<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


require_once  APPPATH.'libraries/PhpOffice/Autoloader.php';
// use PhpOffice\PhpPresentation\Autoloader as Autoloader;
use PhpOffice\Autoloader as Autoloader;
Autoloader::register();

class Ppt extends Autoloader {
	public $templateProcessor=null;
	private $filetemp=null;
	function template($filetemp=null){
		$this->filetemp = $filetemp;
		$this->templateProcessor = new \PhpOffice\PhpPresentation\Slide($filetemp);
	}

	function phpppt(){
		return new \PhpOffice\PhpPresentation\PhpPresentation();
	}
	function style(){
		return new \PhpOffice\PhpPresentation\Style\Alignment();
	}

	function download($filename=null){
		if(!$filename)
			$filename = $this->filetemp;
		
		$file = $this->templateProcessor->save();
		// ob_clean();

		header("Content-Description: File Transfer");
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Expires: 0');

		echo file_get_contents($file);
		unlink($file);
	}	
}