<?php class Mt_pb_kategoriModel extends _Model{
	public $table = "mt_pb_kategori";
	public $pk = "id_kategori";
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