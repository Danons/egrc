<?php class Pemeriksaan_temuanModel extends _Model
{
	public $table = "pemeriksaan_temuan";
	public $pk = "id_pemeriksaan_temuan";
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
			$ret['saranarr'] = $this->conn->GetArray("select a.*, b.nama as nama_pic 
				from pemeriksaan_temuan_saran a 
				left join mt_sdm_jabatan b on a.id_jabatan = b.id_jabatan
			where id_pemeriksaan_temuan = " . $this->conn->escape($id));
			$sasaran = "<ol>";
			foreach ($ret['saranarr'] as $r) {
				$sasaran .= "<li>" . $r['deskripsi'] . "</li>";
			}
			$sasaran .= "</ol>";
			$ret['sasaran'] = $sasaran;
			$ret['rekomendasi'] = $sasaran;
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
				$ret['saranarr'] = $this->conn->GetArray("select a.*, b.nama as nama_pic 
				from pemeriksaan_temuan_saran a 
				left join mt_sdm_jabatan b on a.id_jabatan = b.id_jabatan
				where id_pemeriksaan_temuan = " . $this->conn->escape($ret['id_pemeriksaan_temuan']));
				$sasaran = "<ol>";
				if ($ret['saranarr'])
					foreach ($ret['saranarr'] as $r) {
						$sasaran .= "<li>" . $r['deskripsi'] . "</li>";
					}
				$sasaran .= "</ol>";
				$ret['sasaran'] = $sasaran;
				$ret['rekomendasi'] = $sasaran;

				$rowsd = $this->conn->GetArray("select * from pemeriksaan_temuan_diskusi 
				where id_pemeriksaan_temuan = " . $this->conn->escape($ret['id_pemeriksaan_temuan']));

				$str = "<ol>";
				$stra = "<ol>";
				foreach ($rowsd as $r) {
					if (!$r['is_auditi'])
						$str .= "<li>" . $r['keterangan'] . "</li>";
					else
						$stra .= "<li>" . $r['keterangan'] . "</li>";
				}
				$str .= "</ol>";
				$stra .= "</ol>";

				$ret['komentar_pengawas'] = $str;
				$ret['komentar_auditi'] = $stra;
			}
		}

		return $arr_return;
	}
}
