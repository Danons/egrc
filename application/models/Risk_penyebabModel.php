<?php class Risk_penyebabModel extends _Model{
	public $table = "risk_penyebab";
	public $pk = "id_risk_penyebab";
	function __construct(){
		parent::__construct();
	}
	
	function GetCombo()
	{
		$sql = $this->SqlCombo();
		$rows = $this->conn->GetArray($sql);
		$data = array('' => '-pilih-');
		foreach ($rows as $r) {
			$data[trim($r['idkey'])] = $r['val'];
		}
		return $data;
	}
}