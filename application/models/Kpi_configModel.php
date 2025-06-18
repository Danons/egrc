<?php class Kpi_configModel extends _Model
{
	public $table = "kpi_config";
	public $pk = "tahun";
	public $label = "";
	function __construct()
	{
		parent::__construct();
	}

	public function GetByPk($id_kpi = null, $tahun = null)
	{
		if (!$id_kpi || !$tahun) {
			return array();
		}
		$sql = "select * from " . $this->table . " 
		where id_kpi = " . $this->conn->qstr($id_kpi) . " 
		and tahun = " . $this->conn->qstr($tahun);

		$ret = $this->conn->GetRow($sql);

		if (!$ret)
			$ret = array();

		return $ret;
	}

	public function Update($arr_data = array(), $id_kpi = null, $tahun = null)
	{
		$return = false;

		$str_condition = "id_kpi = " . $this->conn->qstr($id_kpi) . " 
		and tahun = " . $this->conn->qstr($tahun);

		$sql = $this->conn->UpdateSQL($this->table, $arr_data, $str_condition);
		if ($sql) {
			$ret = $this->conn->Execute($sql);

			if ($ret) {
				$info_nama = 'Update <b>' . $arr_data['nama'] . '</b>  ';
				$return['success'] = "$info_nama berhasil.";
			}
		} else {
			$return['error'] = "Update gagal.";
		}

		return $return;
	}

	public function Update2($arr_data = array(), $id_kpi = null, $tahun = null)
	{
		$return = false;

		$str_condition = "id_kpi = " . $this->conn->qstr($id_kpi) . " 
		and tahun = " . $this->conn->qstr($tahun);

		$sql = $this->conn->UpdateSQL2($this->table, $arr_data, $str_condition);
		if ($sql) {
			$ret = $this->conn->Execute($sql);

			if ($ret) {
				$info_nama = 'Update <b>' . $arr_data['nama'] . '</b>  ';
				$return['success'] = "$info_nama berhasil.";
			}
		} else {
			$return['error'] = "Update gagal.";
		}

		return $return;
	}

	public function Delete($id_kpi = null, $tahun = null)
	{
		$return = false;

		$arr_data = $this->conn->GetRow("select * from " . $this->table . " 
		where id_kpi = " . $this->conn->qstr($id_kpi) . " 
		and tahun = " . $this->conn->qstr($tahun));

		// define sql
		$sql = "delete from " . $this->table . " 
		where id_kpi = " . $this->conn->qstr($id_kpi) . " 
		and tahun = " . $this->conn->qstr($tahun);

		$ret = $this->conn->Execute($sql);

		if ($ret) {
			$info_nama = 'Delete <b>' . $arr_data['nama'] . '</b>  ';
			$return['success'] = "$info_nama berhasil.";
		}

		return $return;
	}
}
