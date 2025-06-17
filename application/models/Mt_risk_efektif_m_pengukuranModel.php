<?php class Mt_risk_efektif_m_pengukuranModel extends _Model{
	public $table = "mt_risk_efektif_m_pengukuran";
	public $pk = "id_pengukuran";
	public $label = "efektifitas_mitigasi";
	function __construct(){
		parent::__construct();
	}
}