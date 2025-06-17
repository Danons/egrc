<?php class Penilaian_sessionModel extends _Model{
	public $table = "penilaian_session";
	public $pk = "id_penilaian_session";
	public $label = "nama";
	function __construct(){
		parent::__construct();
	}
}