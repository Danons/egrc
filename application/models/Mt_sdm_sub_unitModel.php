<?php class Mt_sdm_sub_unitModel extends _Model{
	public $table = "mt_sdm_sub_unit";
	public $pk = "id_sub_unit";
	public $label = "nama";
	function __construct(){
		parent::__construct();
	}


	function SqlCombo($id_unit = null){
		if($id_unit){
			return "select {$this->pk} as idkey, concat(b.table_desc ,' ', {$this->label}) as val 
			from {$this->table} a 
			join mt_sdm_unit b on a.id_unit = b.table_code  
			where id_unit = ".$this->conn->escape($id_unit)." order by idkey";
		
		}else{
			return "select {$this->pk} as idkey, concat(b.table_desc ,' ', {$this->label}) as val 
			from {$this->table} a 
			join mt_sdm_unit b on a.id_unit = b.table_code  
			order by idkey";
		
		}
	}

	function GetCombo($id_unit = null){
		$sql = $this->SqlCombo($id_unit);
		$rows = $this->conn->GetArray($sql);
		$data = array(''=>'-pilih-');
		foreach ($rows as $r) {
			$data[trim($r['idkey'])] = $r['val'];
		}
		return $data;
	}

}