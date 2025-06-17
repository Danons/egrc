<?php class Rtm_uraianModel extends _Model
{
	public $table = "rtm_uraian";
	public $pk = "id_rtm_uraian";
	public $label = "";
	function __construct()
	{
		parent::__construct();
	}

	public function SelectGrid($arr_param = array(), $str_field = "*")
	{
		$arr_return = array();
		$arr_params = array(
			'page' => 0,
			'limit' => 50,
			'order' => '',
			'filter' => ''
		);
		foreach ($arr_param as $idkey => $val) {
			$arr_params[$idkey] = $val;
		}

		$arr_params['page'] = ($arr_params['page'] / $arr_params['limit']) + 1;

		$str_condition = "";
		$str_order = "";
		if (!empty($arr_params['filter'])) {
			$str_condition = "where " . $arr_params['filter'];
		}
		if (!empty($arr_params['order'])) {
			$str_order = "order by " . $arr_params['order'];
		} elseif ($this->order_default) {
			$str_order = "order by " . $this->order_default;
		}

		if (version_compare($this->conn->version(), '12.1', '>=')) {
			$this->conn->order_by($str_order);
			#$str_order = "";
		}

		if ($arr_params['limit'] === -1) {
			$arr_return['rows'] = $this->conn->GetArray("
				select
				{$str_field}
				from
				" . $this->table . "
				{$str_condition}
				{$str_order} ");
		} else {
			$arr_return['rows'] = $this->conn->PageArray(
				"
				select
				{$str_field}
				from
				" . $this->table . "
				{$str_condition}
				{$str_order} ",
				$arr_params['limit'],
				$arr_params['page']
			);
		}

		$arr_return['total'] = static::GetOne("
			select
			count(*) as total
			from
			" . $this->table . "
			{$str_condition}
		");

		if ($arr_return['rows']) {
			foreach ($arr_return['rows'] as &$r) {
				$r['id_rtm'] = $this->conn->GetOne("select max(id_rtm) from rtm_uraian_link where id_rtm_uraian = " . $this->conn->escape($r['id_rtm_uraian']));
				$r['status'] = $this->conn->GetOne("select status from rtm_uraian_link where id_rtm_uraian = " . $this->conn->escape($r['id_rtm_uraian']) . " and id_rtm=" . $this->conn->escape($r['id_rtm']));
				$r['picstr'] = implode(",", $this->conn->GetList("select b.table_code as idkey, b.table_desc as val 
				from rtm_urian_unit a 
				join mt_sdm_unit b on a.id_unit = b.table_code
				where a.id_rtm_uraian = " . $this->conn->escape($r['id_rtm_uraian'])));
			}
		}

		return $arr_return;
	}
}
