<?php class Mt_status_pemeriksaanModel extends _Model{
	public $table = "mt_status_pemeriksaan";
	public $pk = "id_status_pemeriksaan";
	public $label = "nama";
	function __construct(){
		parent::__construct();
	}
}