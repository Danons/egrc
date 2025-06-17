<?php class SurveyModel extends _Model
{
	public $table = "survey";
	public $pk = "id_survey";
	public $label = "nama";
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
			$arr_return['rows'] = $this->conn->PageArray("
				select
				{$str_field}
				from
				" . $this->table . "
				{$str_condition}
				{$str_order} ", $arr_params['limit'], $arr_params['page']);
		}

		$this->_setPeriode($arr_return['rows']);

		$arr_return['total'] = static::GetOne("
			select
			count(*) as total
			from
			" . $this->table . "
			{$str_condition}
		");

		return $arr_return;
	}

	public function SelectGridPrint($arr_param = array(), $str_field = "*")
	{
		$arr_return = array();
		$arr_params = array(
			'order' => '',
			'filter' => ''
		);
		foreach ($arr_param as $idkey => $val) {
			$arr_params[$idkey] = $val;
		}

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

		$arr_return['rows'] = $this->conn->GetArray("
			select
			{$str_field}
			from
			" . $this->table . "
			{$str_condition}
			{$str_order} ");

		$this->_setPeriode($arr_return['rows']);

		$arr_return['total'] = static::GetOne("
			select
			count(*) as total
			from
			" . $this->table . "
			{$str_condition}
		");

		return $arr_return;
	}

	private function _setPeriode(&$rows = array())
	{

		$id_periode_tw = $_SESSION[SESSION_APP]['panelbackend/survey']['id_periode_tw_filter'];
		$tahun = $_SESSION[SESSION_APP]['panelbackend/survey']['tahun_filter'];

		foreach ($rows as &$r) {
			$r1 = $this->conn->GetRow("select * from survey_triwulan 
			where id_survey = " . $this->conn->escape($r['id_survey']) . "
			and tahun = " . $this->conn->escape($tahun) . "
			and id_periode_tw = " . $this->conn->escape($id_periode_tw));

			if ($r1)
				$r = $r1;
		}
	}
}
