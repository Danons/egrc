<?php class Pemeriksaan_tindak_lanjutModel extends _Model
{
	public $table = "pemeriksaan_tindak_lanjut";
	public $pk = "id_pemeriksaan_tindak_lanjut";
	public $label = "";
	function __construct()
	{
		parent::__construct();
	}
	public function GetByPk($id)
	{
		if (!$id) {
			return array();
		}
		$sql = "select * from " . $this->table . " where {$this->pk} = " . $this->conn->qstr($id);
		$ret = $this->conn->GetRow($sql);

		if (!$ret)
			$ret = array();
		else {
			$ret['tindaklanjutarr'] = $this->conn->GetList("select 
			id_pemeriksaan_temuan_saran as idkey, 
			rincian_tindak_lanjut as val 
			from pemeriksaan_tindak_lanjut_saran 
			where id_pemeriksaan_tindak_lanjut = " . $this->conn->escape($id));
			$rincian_tindak_lanjut = "<ol>";
			foreach ($ret['tindaklanjutarr'] as $k => $v) {
				$rincian_tindak_lanjut .= "<li>" . $v . "</li>";
			}
			$rincian_tindak_lanjut .= "</ol>";
			$ret['rincian_tindak_lanjut'] = $rincian_tindak_lanjut;
		}

		return $ret;
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
			foreach ($arr_return['rows'] as &$ret) {
				$ret['tindaklanjutarr'] = $this->conn->GetList("select 
				id_pemeriksaan_temuan_saran as idkey, 
				rincian_tindak_lanjut as val 
				from pemeriksaan_tindak_lanjut_saran 
				where id_pemeriksaan_tindak_lanjut = " . $this->conn->escape($ret['id_pemeriksaan_tindak_lanjut']));
				$rincian_tindak_lanjut = "<ol>";
				if ($ret['tindaklanjutarr'])
					foreach ($ret['tindaklanjutarr'] as $k => $v) {
						$rincian_tindak_lanjut .= "<li>" . $v . "</li>";
					}
				$rincian_tindak_lanjut .= "</ol>";
				$ret['rincian_tindak_lanjut'] = $rincian_tindak_lanjut;
			}
		}

		return $arr_return;
	}
}
