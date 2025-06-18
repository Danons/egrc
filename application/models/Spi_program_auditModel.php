<?php class Spi_program_auditModel extends _Model
{
	public $table = "spi_program_audit";
	public $pk = "id_program_audit";
	public $label = "";
	function __construct()
	{
		parent::__construct();
	}


	public function SelectGridRisk($arr_param = array(), $str_field = "*")
	{
		$arr_return = array();
		$arr_params = array(
			'page' => 0,
			'limit' => 50,
			'where' => '',
			'tahun' => '',
			'id_periode_tw' => '',
			'order' => '',
			'filter' => ''
		);
		foreach ($arr_param as $idkey => $val) {
			$arr_params[$idkey] = $val;
		}

		$tahun = $arr_params['tahun'];
		$id_periode_tw = $arr_params['id_periode_tw'];
		$where = $arr_params['where'];

		$arr_params['page'] = ($arr_params['page'] / $arr_params['limit']) + 1;

		$str_condition = "";
		$str_order = "";
		if (!empty($arr_params['filter'])) {
			$str_condition = "where 1=1 " . $arr_params['filter'];
		}
		if (!empty($arr_params['order'])) {
			$str_order = "order by " . $arr_params['order'];
		}

		if (version_compare($this->conn->version(), '12.1', '>=')) {
			$this->conn->order_by($str_order);
			#$str_order = "";
		}

		$table = "(select * from spi_program_audit) a ";

		if ($arr_params['limit'] === -1) {
			$arr_return['rows'] = $this->conn->GetArray("
				select
				{$str_field}
				from
				" . $table . "
				{$str_condition}
				{$str_order} ");
		} else {
			$arr_return['rows'] = $this->conn->PageArray(
				"
				select
				{$str_field}
				from
				" . $table . "
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
			" . $table . "
			{$str_condition}
		");


		foreach ($arr_return['rows'] as &$r) {
			$rws = $this->conn->GetArray("select * from risk_kri_hasil 
			where tahun = " . $this->conn->escape($tahun) . " 
			and id_kri = " . $this->conn->escape($r['id_kri']));
			foreach ($rws as $rw)
				$r['nilai' . $rw['bulan']] = $rw['nilai'];
		}

		return $arr_return;
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
		$table = "select
		spa.nama_audit,
		spa.frekuensi_audit,
		spa.jenis_audit,
		spa.tahun,
		spa.id_program_audit,
		coalesce(rc.id_kemungkinan, coalesce(rr.residual_kemungkinan_evaluasi, 0)) as actual_kemungkinan,
		coalesce(rc.id_dampak, coalesce(rr.residual_dampak_evaluasi, 0)) as actual_dampak,

		mrka.rating * mrda.rating * rr.is_opp_inherent as level_risiko_actual
	
		from spi_program_audit spa 
		left join risk_risiko rr ON spa.id_risiko = rr.id_risiko
		left join risk_scorecard rs on rr.id_scorecard = rs.id_scorecard


		left join risk_risiko_current rc on rr.id_risiko = rc.id_risiko 
		and rc.tahun = 2023 and rc.id_periode_tw = 2
		left join mt_risk_kemungkinan mrka on mrka.id_kemungkinan = coalesce(rc.id_kemungkinan, coalesce(rr.residual_kemungkinan_evaluasi, rr.control_kemungkinan_penurunan))
		left join mt_risk_dampak mrda on mrda.id_dampak = coalesce(rc.id_dampak, coalesce(rr.residual_dampak_evaluasi, rr.control_dampak_penurunan))
		 where  1=1 and spa.deleted_date is null";
		if ($arr_params['limit'] === -1) {
			$arr_return['rows'] = $this->conn->GetArray("
				select
				{$str_field}
				from
				" . $table . "
				{$str_condition}
				{$str_order} ");
		} else {
			$arr_return['rows'] = $this->conn->PageArray(
				"
				select
				{$str_field}
				from
				($table) a
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

		return $arr_return;
	}
}
