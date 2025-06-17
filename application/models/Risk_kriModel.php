<?php class Risk_kriModel extends _Model
{
	public $table = "risk_kri";
	public $pk = "id_kri";
	function __construct()
	{
		parent::__construct();
	}


	public function GetByPk($id)
	{
		if (!$id) {
			return array();
		}
		$sql = "select * from " . $this->table . " where {$this->pk} = " . $this->conn->qstr($id);
		$ret = $this->conn->GetRow($sql);
		if (!$ret)
			$ret = array();
		else {
			$row = $this->conn->GetRow("select * from risk_risiko where id_risiko = " . $this->conn->escape($ret['id_risiko']));
			$ret['namarisiko'] = $row['nama'];
			$ret['id_scorecard'] = $row['id_scorecard'];
			$rdampak = $this->conn->GetArray("select * 
				from risk_dampak a 
				where exists (
					select 1 
					from risk_risiko_dampak b 
					where a.id_risk_dampak = b.id_risk_dampak
					and b.id_risiko = " . $this->conn->escape($ret['id_risiko']) . "
				)");

			$expdampak = [];
			if (count($rdampak) > 1) {
				$no = 0;
				foreach ($rdampak as $r1) {
					$no++;
					$expdampak[] = $no . ". " . $r1['nama'];
				}
			} else {
				$expdampak[] = $rdampak[0]['nama'];
			}

			$ret['dampakstr'] = implode("<br/>", $expdampak);


			$rpenyebab = $this->conn->GetArray("select * 
			from risk_penyebab a 
			where exists (
				select 1 
				from risk_risiko_penyebab b 
				where a.id_risk_penyebab = b.id_risk_penyebab
				and b.id_risiko = " . $this->conn->escape($ret['id_risiko']) . "
			)");

			$exppenyebab = [];
			if (count($rpenyebab) > 1) {
				$no = 0;
				foreach ($rpenyebab as $r1) {
					$no++;
					$exppenyebab[] = $no . ". " . $r1['nama'];
				}
			} else {
				$exppenyebab[] = $rpenyebab[0]['nama'];
			}
			$ret['penyebabstr'] = implode("<br/>", $exppenyebab);


			$row = $this->conn->GetRow("select * from risk_scorecard where id_scorecard = " . $this->conn->escape($row['id_scorecard']));
			$ret['id_unit'] = $row['id_unit'];
			$ret['namaunit'] = $this->conn->GetOne("select table_desc from mt_sdm_unit where table_code = " . $this->conn->escape($row['id_unit']));
		}

		return $ret;
	}
}
