<?php class Mt_jenis_dokumenModel extends _Model{
	public $table = "mt_jenis_dokumen";
	public $pk = "id_jenis_dokumen";
	public $label = "nama";
	function __construct(){
		parent::__construct();
	}
}