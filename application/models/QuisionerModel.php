<?php class QuisionerModel extends _Model
{
	public $table = "quisioner";
	public $pk = "id_quisioner";
	public $label = "pertanyaan";
	function __construct()
	{
		parent::__construct();
	}

	function SqlCombo($id_ketegori = null)
	{
		$add_filter = "";
		if ($id_ketegori)
			$add_filter = " where id_kategori = " . $this->conn->escape($id_ketegori);

		return "select {$this->pk} as idkey, {$this->label} as val from {$this->table} $add_filter order by idkey";
	}

	function GetCombo($id_ketegori = null)
	{
		$sql = $this->SqlCombo($id_ketegori);
		$rows = $this->conn->GetArray($sql);
		$data = array('' => '');
		foreach ($rows as $r) {
			$data[trim($r['idkey'])] = $r['val'];
		}
		return $data;
	}
}
