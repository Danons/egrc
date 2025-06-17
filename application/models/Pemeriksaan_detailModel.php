<?php class Pemeriksaan_detailModel extends _Model
{
	public $table = "pemeriksaan_detail";
	public $pk = "id_pemeriksaan_detail";
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

		foreach ($arr_return['rows'] as &$r) {
			$r['realisasi_anggaran'] = $this->conn->GetOne("select sum(nilai_realisasi) 
			from pemeriksaan_anggaran_biaya 
			where id_pemeriksaan_detail = " . $this->conn->escape($r['id_pemeriksaan_detail']));
		}

		return $arr_return;
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

			$rows = $this->conn->GetArray("select *
			from mt_pemeriksaan_kka
			where id_kka = " . $this->conn->escape($ret['id_kka']));

			foreach ($rows as $r) {
				$ret['fileskka']['id'][] = $r['id_kka'];
				$ret['fileskka']['name'][] = $r['client_name'];
			}

			$rows = $this->conn->GetArray("select *
			from pemeriksaan_detail_files
			where id_pemeriksaan_detail = " . $this->conn->escape($ret['id_pemeriksaan_detail']));

			foreach ($rows as $r) {
				$ret['files']['id'][] = $r['id_pemeriksaan_detail_files'];
				$ret['files']['name'][] = $r['client_name'];
			}

			$ret['realisasi'] = $this->conn->GetOne("select sum(nilai_realisasi) 
			from pemeriksaan_anggaran_biaya 
			where id_pemeriksaan_detail = " . $this->conn->escape($r['id_pemeriksaan_detail']));
		}

		return $ret;
	}
}
