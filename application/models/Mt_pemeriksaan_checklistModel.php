<?php class Mt_pemeriksaan_checklistModel extends _Model
{
	public $table = "mt_pemeriksaan_checklist";
	public $pk = "id_checklist";
	public $label = "nama";
	public $order_default = "id_checklist asc";
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
			$rows = $this->conn->GetArray("
				select
				{$str_field}
				from
				" . $this->table . "
				{$str_condition}
				{$str_order} ");
		} else {
			$rows = $this->conn->PageArray(
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

		$arr_return['rows'] = array();
		$id_parent = null;
		$this->GenerateSort($rows, "id_checklist_parent", "id_checklist", "nama", $arr_return['rows'], $id_parent);

		$arr_return['total'] = static::GetOne("
			select
			count(*) as total
			from
			" . $this->table . "
			{$str_condition}
		");

		// dpr($arr_return['rows'], 1);

		return $arr_return;
	}
}
