<?php class Penilaian_filesModel extends _Model{
	public $table = "penilaian_files";
	public $pk = "id_penilaian_files";
	public $label = "nama";
	function __construct(){
		parent::__construct();
	}
}