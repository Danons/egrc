<?php class Mt_jenis_rtmModel extends _Model
{
	public $table = "mt_jenis_rtm";
	public $pk = "id_jenis_rtm";
	public $label = "jenis_masalah";
	function __construct()
	{
		parent::__construct();
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
			{$str_condition} ");

		$ret = array();
		$i = null;
		$this->GenerateSort($rows, "id_jenis_rtm_parent", "id_jenis_rtm", "jenis_masalah", $ret, null, $i);
		return $ret;
	}

	function SqlComboP($id_jenis_rtm_parent = null)
	{
		$filter = "where id_jenis_rtm_parent is null";
		if ($id_jenis_rtm_parent)
			$filter = "where id_jenis_rtm_parent = $id_jenis_rtm_parent";

		return "select {$this->pk} as idkey, {$this->label} as val from {$this->table} $filter order by idkey";
	}

	function GetComboP($id_jenis_rtm_parent = null)
	{
		$sql = $this->SqlComboP($id_jenis_rtm_parent);
		$rows = $this->conn->GetArray($sql);
		$data = array('' => '-pilih-');
		foreach ($rows as $r) {
			$data[trim($r['idkey'])] = $r['val'];
		}
		return $data;
	}
}
