<?php class DokumenModel extends _Model
{
	public $table = "dokumen";
	public $pk = "id_dokumen";
	public $label = "concat(nomor_dokumen,' ',nama)";
	function __construct()
	{
		parent::__construct();
	}

	function SqlCombo()
	{
		return "select {$this->pk} as idkey, {$this->label} as val from {$this->table} where is_aktif=1 order by idkey";
	}

	function GetCombo()
	{
		$sql = $this->SqlCombo();
		$rows = $this->conn->GetArray($sql);
		$data = array('' => '-pilih-');
		foreach ($rows as $r) {
			$data[trim($r['idkey'])] = $r['val'];
		}
		return $data;
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
			$str_condition = "and " . $arr_params['filter'];
		}

		if (!$this->ci->access_role['view_all']) {
			$id_unit = $_SESSION[SESSION_APP]['id_unit'];
			$str_condition .= " and id_unit = " . $this->conn->escape($id_unit);
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
			SELECT d.*, dv.status FROM dokumen d 
			LEFT JOIN dokumen_versi dv ON d.id_dokumen = dv.id_dokumen 
			where d.deleted_date is null 
				{$str_condition}
				{$str_order} ");
		} else {
			$arr_return['rows'] = $this->conn->PageArray(
				"
				SELECT d.*, dv.status FROM dokumen d 
				LEFT JOIN dokumen_versi dv ON d.id_dokumen = dv.id_dokumen 
				where d.deleted_date is null 
				{$str_condition}
				{$str_order} ",
				$arr_params['limit'],
				$arr_params['page']
			);
			// $arr_return['rows'] = $this->conn->PageArray(
			// 	"
			// 	select
			// 	{$str_field}
			// 	from
			// 	" . $this->table . " 
			// 	{$str_condition}
			// 	{$str_order} ",
			// 	$arr_params['limit'],
			// 	$arr_params['page']
			// );
		}

		$arr_return['total'] = static::GetOne("
		SELECT count(*) FROM dokumen d LEFT JOIN dokumen_versi dv ON d.id_dokumen = dv.id_dokumen where d.deleted_date is null 
			{$str_condition}
		");
		// $arr_return['total'] = static::GetOne("
		// 	select
		// 	count(*) as total
		// 	from
		// 	" . $this->table . "  
		// 	{$str_condition}
		// ");

		return $arr_return;
	}
}
