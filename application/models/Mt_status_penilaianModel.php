<?php class Mt_status_penilaianModel extends _Model{
	public $table = "mt_status_penilaian";
	public $pk = "id_status_penilaian";
	public $label = "nama";
	function __construct(){
		parent::__construct();
	}
}