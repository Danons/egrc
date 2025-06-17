<?php class Risk_sasaranModel extends _Model
{
	public $table = "risk_sasaran";
	public $pk = "id_sasaran";
	function __construct()
	{
		parent::__construct();
	}

	function GetNama($id_sasaran = null)
	{
		return $this->conn->GetOne("select nama from risk_sasaran where id_sasaran = " . $this->conn->escape($id_sasaran));
	}

	function SqlCombo($owner = null, $tgl_efektif = null, $tahun = null)
	{
		$filter = "";
		/*if($owner)
			$filter = " and p.id_jabatan = ".$this->conn->escape($owner);*/

		if ($tahun)
			$filter .= " and '$tahun' between ifnull(date_format(tgl_mulai_efektif,'%Y'),'$tahun') and ifnull(date_format(tgl_akhir_efektif,'%Y'),'$tahun')";
		elseif ($tgl_efektif)
			$filter .= " and '$tgl_efektif' between ifnull(tgl_mulai_efektif,'$tgl_efektif') and ifnull(tgl_akhir_efektif,'$tgl_efektif')";

		return "select s.{$this->pk} as id, concat(ifnull(s.kode,''),' ', s.{$this->label}) as nama, id_sasaran_parent as id_parent from {$this->table} s
		left join risk_sasaran_pic p on s.id_sasaran = p.id_sasaran  where 1=1 $filter and s.deleted_date is null order by id";
	}

	function GetCombo($owner = null, $tgl_efektif = null, $tahun = null)
	{
		if (!$tgl_efektif)
			$tgl_efektif = date("Y-m-d");

		if ($_SESSION[SESSION_APP]['tgl_efektif']) {
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		}

		$sql = $this->SqlCombo($owner, $tgl_efektif, $tahun);
		$rows = $this->conn->GetArray($sql);

		if (!$rows)
			return array();

		$ret = array();
		$this->GenerateTree($rows, "id_parent", "id", "nama", $ret, null);

		$data = array('' => '-pilih-');
		foreach ($ret as $r) {
			$data[$r['id']] = $r['nama'];
		}

		if (($data) == 1)
			return array();

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

		$arr_return['total'] = static::GetOne("select
			count(*) as total
			from
			" . $this->table . "
			{$str_condition}
		");

		$ret = array();
		$this->GenerateSort($arr_return['rows'], "id_sasaran_parent", "id_sasaran", "", $ret);
		$arr_return['rows'] = $ret;

		return $arr_return;
	}
}
