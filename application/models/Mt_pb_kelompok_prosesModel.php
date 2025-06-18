<?php class Mt_pb_kelompok_prosesModel extends _Model{
	public $table = "mt_pb_kelompok_proses";
	public $pk = "id_kelompok_proses";
	public $label = "nama";
	function __construct(){
		parent::__construct();
	}

	function SqlCombo(){

		if (1 /*!$_SESSION[SESSION_APP]['tgl_efektif']*/)
			$_SESSION[SESSION_APP]['tgl_efektif'] = date('Y-m-d');

		if($_SESSION[SESSION_APP]['tgl_efektif']){
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		}

		return "select {$this->pk} as idkey, {$this->label} as val from {$this->table} where '$tgl_efektif' between ifnull(tgl_mulai_efektif, '$tgl_efektif')and ifnull(tgl_akhir_efektif,'$tgl_efektif') order by idkey";
	}
}