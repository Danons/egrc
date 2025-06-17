<?php class Pemeriksaan_timModel extends _Model{
	public $table = "pemeriksaan_tim";
	public $pk = "id_pemeriksaan_tim";
	public $label = "nama";
	function __construct(){
		parent::__construct();
	}
}