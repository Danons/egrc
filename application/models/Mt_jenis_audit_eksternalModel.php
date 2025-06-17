<?php class Mt_jenis_audit_eksternalModel extends _Model{
	public $table = "mt_jenis_audit_eksternal";
	public $pk = "id_jenis_audit_eksternal";
	public $label = "nama";
	function __construct(){
		parent::__construct();
	}
}