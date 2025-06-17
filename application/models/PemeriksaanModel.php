<?php class PemeriksaanModel extends _Model
{
	public $table = "pemeriksaan";
	public $pk = "id_pemeriksaan";
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

		$arr_params['page'] = (((int)$arr_params['page'] / (int)$arr_params['limit'])) + 1;

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

		if ($arr_return['rows'])
			foreach ($arr_return['rows'] as &$r) {
				$rw = $this->conn->GetRow("select 
				sum(1) jumlah_temuan, 
				sum(case when status=0 then 1 else 0 end) jumlah_tindak_lanjut 
				from pemeriksaan_temuan 
				where id_pemeriksaan = " . $r['id_pemeriksaan']);

				$r['jumlah_temuan'] = $rw['jumlah_temuan'];
				$r['jumlah_tindak_lanjut'] = $rw['jumlah_tindak_lanjut'];
				$r['jumlah_sisa_temuan'] = (int)$rw['jumlah_temuan'] - (int)$rw['jumlah_tindak_lanjut'];
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

	public function GetByPk($id)
	{
		if (!$id) {
			return array();
		}
		$sql = "select * from " . $this->table . " where {$this->pk} = " . $this->conn->qstr($id);
		$ret = $this->conn->GetRow($sql);

		if (!$ret)
			$ret = array();

		if ($ret) {
			$ret['pemeriksaan_tim'] = $this->conn->GetArray("select a.*, 
			b.nama as nama_bidang 
			from pemeriksaan_tim a 
			left join mt_bidang_pemeriksaan b on a.id_bidang_pemeriksaan = b.id_bidang_pemeriksaan
			where a.id_pemeriksaan = " . $this->conn->escape($id));
			$ret['nama_unit'] = $this->conn->GetOne("select table_desc from mt_sdm_unit where table_code = " . $this->conn->escape($ret['id_unit']));
			$ret['no_surat_tugas'] = $this->conn->GetOne("select nomor_surat from pemeriksaan_spn where id_spn = " . $this->conn->escape($ret['id_spn']));
		}

		return $ret;
	}
}
