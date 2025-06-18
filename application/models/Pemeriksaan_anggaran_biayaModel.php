<?php class Pemeriksaan_anggaran_biayaModel extends _Model{
	public $table = "pemeriksaan_anggaran_biaya";
	public $pk = "id_pemeriksaan_anggaran_biaya";
	public $label = "nama";
	function __construct(){
		parent::__construct();
	}
}