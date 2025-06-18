<?php class Mt_sdm_unitModel extends _Model
{
	public $table = "mt_sdm_unit";
	public $pk = "table_code";
	public $label = "table_desc";
	function __construct()
	{
		parent::__construct();
	}

	function GetCombo($skip = false)
	{
		$sql = $this->SqlCombo($skip);
		$rows = $this->conn->GetArray($sql);
		$data = array('' => '');
		foreach ($rows as $r) {
			$data[trim($r['idkey'])] = $r['val'];
		}
		return $data;
	}

	function SqlCombo($skip = false)
	{
		$addfilter = "";
		if (!$this->ci->Access("view_all", "main") && !$skip)
			$addfilter = " and table_code = " . $this->conn->escape($_SESSION[SESSION_APP]['id_unit']);

		return "select {$this->pk} as idkey, {$this->label} as val from {$this->table} where deleted_date is null and is_aktif = '1' $addfilter order by idkey";
	}

	// function SqlComboUnit($unit = null)
	// {
	// 	return "select {$this->pk} as idkey, {$this->label} as val from {$this->table} order by idkey";
	// }

	// function GetComboUnit($unit = null)
	// {
	// 	$sql = $this->SqlComboUnit($unit);
	// 	$rows = $this->conn->GetArray($sql);
	// 	$data = array('' => '');
	// 	foreach ($rows as $r) {
	// 		$data[trim($r['idkey'])] = $r['val'];
	// 	}
	// 	return $data;
	// }
}
