<?php class PenilaianModel extends _Model{
	public $table = "penilaian";
	public $pk = "id_penilaian";
	public $label = "nama";
	function __construct(){
		parent::__construct();
	}
}