<?php class Mt_sdm_jabatanModel extends _Model
{
	public $table = "mt_sdm_jabatan";
	public $pk = "id_jabatan";
	public $order_default = "levelsdm asc, -urutan desc, position_id";
	function __construct()
	{
		parent::__construct();
	}

	public function SelectGrid($arr_param = array(), $str_field = "a.*")
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
			#			$str_order = "";
		}

		if ($arr_params['limit'] === -1 || $arr_params['limit'] === "-1") {
			$rows = $this->conn->GetArray("
				select
				id_jabatan as id, 
				id_jabatan_parent as id_parent, 
				b.level as levelsdm,
				{$str_field}
				from
				" . $this->table . " a 
				left join mt_sdm_level b on a.id_sdm_level = b.id_sdm_level
				{$str_condition}
				{$str_order} ");
		} else {
			$rows = $this->conn->PageArray(
				"
				select
				id_jabatan as id, 
				id_jabatan_parent as id_parent, 
				b.level as levelsdm,
				{$str_field}
				from
				" . $this->table . " a
				left join mt_sdm_level b on a.id_sdm_level = b.id_sdm_level
				{$str_condition}
				{$str_order} ",
				$arr_params['limit'],
				$arr_params['page']
			);
		}

		$arr_return['rows'] = array();
		$id_parent = null;
		$this->GenerateSort($rows, "id_parent", "id", "nama", $arr_return['rows'], $id_parent);

		$arr_return['total'] = static::GetOne("
			select
			count(*) as total
			from
			" . $this->table . " a
			{$str_condition}
		");

		return $arr_return;
	}


	public function SelectGrid1($arr_param = array(), $str_field = "*")
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

		if (!empty($arr_params['filter'])) {
			$str_condition = "where " . $arr_params['filter'];
		}

		$str_order = "";

		if (!empty($arr_params['order'])) {
			$str_order = "order by " . $arr_params['order'];
		} elseif ($this->order_default) {
			$str_order = "order by " . $this->order_default;
		}


		if (version_compare($this->conn->version(), '12.1', '>=')) {
			$this->conn->order_by($str_order);
			#			$str_order = "";
		}

		if (1 /*!$_SESSION[SESSION_APP]['tgl_efektif']*/)
			$tgl_efektif = date('Y-m-d');
		else
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

		$rows = $this->conn->GetArray("
			select id_jabatan as id, 
			id_jabatan_parent as id_parent, 
			a.*
			from mt_sdm_jabatan a
			{$str_condition}
			order by urutan");

		$arr_return['rows'] = $rows;

		// $arr_return['rows'] = array();

		// $this->GenerateSort($rows, "id_parent", "id", "nama", $arr_return['rows'], $id_parent);

		return $arr_return;
	}

	public function GetCombo($idkey = null, $q = null, $tgl_efektif = null)
	{

		$tgl_efektif = date('Y-m-d');


		if ($_SESSION[SESSION_APP]['tgl_efektif']) {
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		}

		$id_parent = null;

		$q = strtolower($q);

		$sql = "select d.*
		from mt_sdm_jabatan d
		where '$tgl_efektif' between ifnull(d.tgl_mulai_efektif,'$tgl_efektif')and ifnull(d.tgl_akhir_efektif,'$tgl_efektif')";

		if ($q)
			$sql .= " and  lower(d.nama) like '%$q%'";

		if ($idkey)
			$sql .= " and id_jabatan = " . $this->conn->escape($idkey);

		$sql .= " order by urutan";

		$rows = $this->conn->GetArray($sql);

		$ret = array();

		$this->GenerateTree($rows, "id_jabatan_parent", "id_jabatan", "nama", $ret, $id_parent);

		$return = array();
		foreach ($ret as $r) {
			$return[$r['id_jabatan']] = str_replace(" &amp; ", " ", $r['nama']) . " (" . trim($r['id_unit']) . ")";
		}

		return $return;
	}
}
