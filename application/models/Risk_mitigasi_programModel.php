<?php class Risk_mitigasi_programModel extends _Model{
	public $table = "risk_mitigasi_program";
	public $pk = "id_mitigasi_program";
	function __construct(){
		parent::__construct();
	}

	public function GetByPk($id)
	{
		if (!$id) {
			return array();
		}

		$where = "";
		if (!$this->ci->access_role['view_all']) {
			$id_jabatan = $_SESSION[SESSION_APP]['id_jabatan'];
			$where .= " and (";
			if ($this->scorecardstr)
				$where .= " r.id_scorecard in ({$this->scorecardstr}) or ";
			$where .= " exists(select 1 from risk_mitigasi rm where r.id_risiko = rm.id_risiko and (penanggung_jawab = " . $this->conn->escape($id_jabatan) . " or penanggung_jawab = " . $this->conn->escape($_SESSION[SESSION_APP]['owner_jabatan']) . " or interdependent_delegasi = " . $this->conn->escape($id_jabatan) . ")))";
		}

		$sql = "select * from risk_risiko r where id_risiko = " . $this->conn->qstr($id) . $where;
		$ret = $this->conn->GetRow($sql);

		if (!$ret)
			$ret = array();

		$ret['is_finish'] = $this->CekFinish($id);
		$ret['id_unit'] = $this->conn->GetOne("select id_unit from risk_scorecard where id_scorecard = " . $this->conn->escape($ret['id_scorecard']));

		if ($ret['id_risiko_sebelum'])
			$ret['risiko_old'] = $this->conn->GetRow("select hambatan_kendala from risk_risiko where id_risiko = " . $this->conn->escape($ret['id_risiko_sebelum']));

		return $ret;
	}

	private function CekFinish($id)
	{

		return (int)$this->conn->GetOne("
			select s.open_evaluasi
			from risk_scorecard s
			join risk_risiko r on s.id_scorecard = r.id_scorecard
			where id_risiko = " . $this->conn->escape($id) . "
		");
		/*$open_evaluasi = $this->config->item('open_evaluasi');

		if($open_evaluasi)
			return 1;

		return 0;*/

		/*$cek = $this->conn->GetOne("
			select count(1) 
			from risk_mitigasi r
			join mt_status_progress p on r.id_status_progress = p.id_status_progress
			where id_risiko = ".$this->conn->escape($id)
		);

		if(!$cek)
			return 0;

		return !$this->conn->GetOne("
			select count(1) 
			from risk_mitigasi r
			join mt_status_progress p on r.id_status_progress = p.id_status_progress
			where id_risiko = ".$this->conn->escape($id)."
			and p.prosentase <>  100"
		);*/
	}
}