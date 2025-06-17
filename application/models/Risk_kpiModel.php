<?php class Risk_kpiModel extends _Model
{
	public $table = "risk_kpi";
	public $pk = "id_kpi";
	function __construct()
	{
		parent::__construct();
	}
	function SqlCombo()
	{
		$where = ' where 1=1 and deleted_date is null';

		if (!$this->ci->access_role['view_all']) {
			$id_unit = $_SESSION[SESSION_APP]['id_unit'];
			$where .= " and id_unit_kerja = " . $this->conn->escape($id_unit);
		}
		
		return "select {$this->pk} as idkey, {$this->label} as val from {$this->table} $where order by idkey";
	}

	// function GetComboUnit()
	// {
	// 	$sql = $this->SqlCombo();
	// 	$rows = $this->conn->GetArray($sql);
	// 	$data = array('' => '');
	// 	foreach ($rows as $r) {
	// 		$data[trim($r['idkey'])] = $r['val'];
	// 	}
	// 	return $data;
	// }

	// function SqlComboUnit()
	// {
	// 	return "select {$this->pk} as idkey, {$this->label} as val from {$this->table} where order by idkey";
	// }
}
