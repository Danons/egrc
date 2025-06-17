<?php class Pemeriksaan_spnModel extends _Model{
	public $table = "pemeriksaan_spn";
	public $pk = "id_spn";
	public $label = "nomor_surat";
	function __construct(){
		parent::__construct();
	}
}