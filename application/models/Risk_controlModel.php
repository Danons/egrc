<?php class Risk_controlModel extends _Model{
	public $table = "risk_control";
	public $pk = "id_control";
	public $order_default = "id_risiko, no, nama asc";
	function __construct(){
		parent::__construct();
	}

	function SqlCombo($id_risiko=null, $id_control=null){
		$where = " where 1=1 ";

		if($id_risiko)
			$where .= " and id_risiko = ".$this->conn->escape($id_risiko);

		if($id_control)
			$where .= " and id_control <> ".$this->conn->escape($id_control);

		return "select {$this->pk} as idkey, {$this->label} as val from {$this->table} $where order by idkey";
	}

	function GetCombo($id_risiko=null, $id_control=null){
		$sql = $this->SqlCombo($id_risiko, $id_control);
		$rows = $this->conn->GetArray($sql);
		$data = array(''=>'-pilih-');
		foreach ($rows as $r) {
			$data[$r['idkey']] = $r['val'];
		}
		return $data;
	}

	public function GetNo($id_risiko=null){
		return $this->conn->GetOne("select coalesce(max(no),0)+1 from {$this->table} where id_risiko = ".$this->conn->escape($id_risiko));
	}
}