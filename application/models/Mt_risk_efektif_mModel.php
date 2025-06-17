<?php class Mt_risk_efektif_mModel extends _Model{
	public $table = "mt_risk_efektif_m";
	public $pk = "id_efektif_m";
	public $label = "nama";
	function __construct(){
		parent::__construct();
	}

	public function getKetEfektifitas()
	{
		$sql = "select * from mt_risk_efektif_m";

		$ret = $this->conn->GetArray($sql);

		if(!$ret)
			$ret = array();
		else{
			foreach($ret as &$v){
				$v['jawabanarr'] = $this->conn->GetList("select 
				a.id_efektif_m_jawaban as idkey, c.nama as val
				from mt_risk_efektif_m_bobot a
				join mt_risk_efektif_m_jawaban c on a.id_efektif_m_jawaban = c.id_efektif_m_jawaban
				where id_efektif_m = ".$this->conn->escape($v['id_efektif_m'])."
				order by idkey");
			}
		}

		return $ret;
	}
}