<?php class Mt_sdm_subbidModel extends _Model
{
	public $table = "mt_sdm_subbid";
	public $pk = "code";
	public $label = "nama";
	function __construct()
	{
		parent::__construct();
	}

	public function GetComboUnit($id_unit)
	{
		return array('' => '') + $this->conn->GetList("select code as idkey, nama as val from mt_sdm_subbid a 
		where exists (select 1 from mt_sdm_jabatan b 
		where a.code  = b.id_subbid and b.id_unit = " . $this->conn->escape($id_unit) . ")");
	}
}
