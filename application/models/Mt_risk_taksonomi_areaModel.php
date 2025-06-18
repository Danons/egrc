<?php class Mt_risk_taksonomi_areaModel extends _Model
{
	public $table = "mt_risk_taksonomi_area";
	public $pk = "id_taksonomi_area";
	public $label = "kode + ' | ' + nama";
	function __construct()
	{
		parent::__construct();
	}

	function SqlCombo()
	{
		return "select {$this->pk} as idkey, {$this->label} as val from {$this->table} order by idkey";
	}

	function GetCombo_jenis()
	{
		$sql = "select * from mt_risk_taksonomi_area";
		$rows = $this->conn->GetArray($sql);
		// dpr($rows,1);
		$data['rutin'] = array('' => '-pilih-');
		$data['nonrutin'] = array('' => '-pilih-');
		$data['proyek'] = array('' => '-pilih-');
		foreach ($rows as $r) {
			$data['all'][$r['id_taksonomi_area']] = $r['kode']." | ".$r['nama'];
			if ($r['jenis'] == 'rutin' || (strstr($r['jenis'], '/') && (strstr($r['jenis'], "/rutin/") || (strstr($r['jenis'], "rutin")))))
				$data['rutin'][$r['id_taksonomi_area']] = $r['kode']." | ".$r['nama'];
			if (strstr($r['jenis'], "nonrutin"))
				$data['nonrutin'][$r['id_taksonomi_area']] = $r['kode']." | ".$r['nama'];
			if (strstr($r['jenis'], "proyek"))
				$data['proyek'][$r['id_taksonomi_area']] = $r['kode']." | ".$r['nama'];
		}
		return $data;
	}

	public function GetByPk($id)
	{
		if (!$id) {
			return array();
		}

		// if ($_SESSION[SESSION_APP]['id_owner_sso']) {
		// 	$sql = "select * from " . $this->table . " where {$this->pk} = " . $this->conn->qstr($id) . " and id_owner_sso = " . $this->conn->qstr($_SESSION[SESSION_APP]['id_owner_sso']);
		// } else {
		$sql = "select * from " . $this->table . " where {$this->pk} = " . $this->conn->qstr($id);
		// }

		$ret = $this->conn->GetRow($sql);
		$ret['jenis'] = explode("/", $ret['jenis']);

		if (!$ret)
			$ret = array();

		return $ret;
	}
}
