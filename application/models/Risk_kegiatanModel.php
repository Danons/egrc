<?php class Risk_kegiatanModel extends _Model
{
	public $table = "risk_kegiatan";
	public $pk = "id_kegiatan";
	function __construct()
	{
		parent::__construct();
	}


	function SqlCombo($id_sasaran = null, $q = null)
	{
		$where = ' where 1=1 and deleted_date is null';

		if ($id_sasaran)
			$where .= " and id_sasaran = " . $this->conn->escape($id_sasaran);

		if ($q)
			$where .= " and  lower({$this->label}) like '%$q%'";


		if (!$this->ci->access_role['view_all']) {
			$id_unit = $_SESSION[SESSION_APP]['id_unit'];
			$where .= " and id_unit = " . $this->conn->escape($id_unit);
		}

		return "select {$this->pk} as idkey, {$this->label} as val from {$this->table} $where order by idkey";
	}

	function GetCombo($id_sasaran = null, $q = null)
	{

		if (!$id_sasaran)
			return array();

		$q = strtolower($q);

		$sql = $this->SqlCombo($id_sasaran, $q);
		$rows = $this->conn->GetArray($sql);
		$data = array('' => '');
		foreach ($rows as $r) {
			$data[$r['key']] = $r['val'];
		}
		return $data;
	}
}
