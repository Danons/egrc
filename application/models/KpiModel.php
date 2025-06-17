<?php class KpiModel extends _Model
{
	public $table = "kpi";
	public $pk = "id_kpi";
	public $label = "nama";
	function __construct()
	{
		parent::__construct();
	}

	function SqlCombo()
	{
		$addfilter = "";
		if (!$this->ci->access_role['view_all']) {
			$id_unit = $_SESSION[SESSION_APP]['id_unit'];
			$addfilter = " where exists (select 1 from kpi_target 
			where kpi.id_kpi = kpi_target.id_kpi 
			and kpi_target.id_unit = " . $this->conn->escape($id_unit) . ")";
		}

		return "select *
		from {$this->table} 
		$addfilter";
	}

	function GetCombo()
	{
		$sql = $this->SqlCombo();
		$rows = $this->conn->GetArray($sql);

		$ret = array();
		$id_parent = null;
		$this->GenerateTree($rows, "id_parent", "id_kpi", "nama", $ret, $id_parent);
		$data = array('' => '-pilih-');
		foreach ($ret as $r) {
			$data[$r['id_kpi']] = $r['nama'];
		}
		return $data;
	}

	public function SelectGrid($arr_param = array(), $str_field = "*")
	{
		$return = array();
		$arr_params = array(
			'filter' => ''
		);
		foreach ($arr_param as $idkey => $val) {
			$arr_params[$idkey] = $val;
		}

		$str_condition = "";
		if (!empty($arr_params['filter'])) {
			$str_condition = "where " . $arr_params['filter'];
		}

		$rows = $this->conn->GetArray("
			select
			{$str_field}
			from
			" . $this->table . "
			{$str_condition} 
			order by urutan");

		$ret = array();
		$i = null;
		$this->GenerateSort($rows, "id_parent", "id_kpi", "nama", $ret, null, $i);
		return $ret;
	}

	function GetComboUnit($id_unit, $tahun, $id_kpi = null)
	{
		if (!$tahun)
			$tahun = date("Y");

		if ($id_unit == 1) {
			$sql = "select a.* from kpi a 
			where exists(select 1 from kpi_target b where a.id_kpi = b.id_kpi 
			and b.jenis = 'Korporat'
			and b.tahun = " . $this->conn->escape($tahun) . ")";
		} else if ($id_unit) {
			$sql = "select a.* from kpi a 
			where exists(select 1 from kpi_target b where a.id_kpi = b.id_kpi 
			and b.id_unit = " . $this->conn->escape($id_unit) . " 
			and b.tahun = " . $this->conn->escape($tahun) . ")";
		} else {
			$sql = "select a.* from kpi a 
			where exists(select 1 from kpi_target b where a.id_kpi = b.id_kpi 
			and b.tahun = " . $this->conn->escape($tahun) . ")";
		}

		$rows = $this->conn->GetArray($sql);

		if (!$rows) {
			$sql = "select a.* from kpi a 
			where exists(select 1 from kpi_target b where a.id_kpi = b.id_kpi 
			and b.id_unit = " . $this->conn->escape($id_unit) . ")";

			$rows = $this->conn->GetArray($sql);
		}

		$ret = array();
		$id_parent = null;
		$this->GenerateTree($rows, "id_parent", "id_kpi", "nama", $ret, $id_parent);
		$data = array('' => '-pilih-');
		foreach ($ret as $r) {
			$data[$r['id_kpi']] = $r['nama'];
		}

		if ($id_kpi)
			$data[$id_kpi] = $this->conn->GetOne("select nama from kpi where id_kpi = " . $this->conn->escape($id_kpi));

		return $data;
	}
}
