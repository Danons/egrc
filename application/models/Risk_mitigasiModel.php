<?php class Risk_mitigasiModel extends _Model
{
	public $table = "risk_mitigasi";
	public $pk = "id_mitigasi";
	public $order_default = "m.no, m.nama asc";

	function __construct()
	{
		parent::__construct();
	}

	public function GetNo($id_risiko = null)
	{
		return $this->conn->GetOne("select coalesce(max(no),0)+1 from {$this->table} where id_risiko = " . $this->conn->escape($id_risiko));
	}

	public function SelectGrid($arr_param = array(), $str_field = "m.*,m.nama as nama_aktifitas, j.nama as nama_pic")
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

		$str_condition = "where 1=1 and is_control <> '1' ";
		$str_order = "";
		if (!empty($arr_params['filter'])) {
			$str_condition .= " and " . $arr_params['filter'];
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

		if ($arr_params['limit'] === -1) {
			$arr_return['rows'] = $this->conn->GetArray("
				select
				{$str_field}
				from
				" . $this->table . " m
				left join mt_sdm_jabatan j on trim(m.penanggung_jawab) = trim(j.id_jabatan)
				{$str_condition}
				{$str_order} ");
		} else {
			$arr_return['rows'] = $this->conn->PageArray(
				"
				select
				{$str_field}
				from
				" . $this->table . " m
				left join mt_sdm_jabatan j on trim(m.penanggung_jawab) = trim(j.id_jabatan)
				{$str_condition}
				{$str_order} ",
				$arr_params['limit'],
				$arr_params['page']
			);
		}

		$arr_return['total'] = static::GetOne("
			select
			count(*) as total
			from
			" . $this->table . " m
				join mt_sdm_jabatan j on trim(m.penanggung_jawab) = trim(j.id_jabatan)
			{$str_condition}
		");

		return $arr_return;
	}

	public function getNomorRisiko($id_unit = null, $id_taksonomi = null, $id_kpi = null, $tgl_risiko = null, $isformat = false)
	{
		if (!$tgl_risiko)
			$tgl_risiko = date("Y-m-d");

		/*
		2.04/A1/01/2020/001
		2.04 : Unit Kerja
		A1 : taksonomi risiko
		01 : KPI
		2020 : tahun
		001 : nomor urut
		*/
		$y = date('y');
		$format = "KTI/P$y/";

		// $format = $id_unit;
		// if (!isset($id_taksonomi)) {
		// 	$format .= '/' . $this->conn->GetOne("select kode 
		// from mt_risk_taksonomi_area 
		// where id_taksonomi_area = " . $this->conn->escape($id_taksonomi));
		// }
		// $format .= '/' . $this->_getKodeKPI($id_kpi);
		// $format .= '/' . $this->conn->GetOne("select kode 
		// from kpi 
		// where id_kpi = " . $this->conn->escape($id_kpi));
		// $format .= '/' . substr($tgl_risiko, 0, 4) . '/';
		// $format .= substr($tgl_risiko, 0, 4) . '/';

		if ($isformat)
			return $format;

		// $nomor_dr_unit = $this->conn->GetOne("select max(nomor) nomor from (SELECT
		// 	RIGHT(nomor, locate('/', REVERSE(nomor)) - 1) unit, 
		// 	CONVERT(numeric, SUBSTRING(REPLACE(nomor, 'KTI/',''), locate('/', REPLACE(nomor, 'KTI/','')) + 1, locate('/', REPLACE(nomor, 'KTI/',''), locate('/', nomor) + 1) - locate('/', REPLACE(nomor, 'KTI/','')) - 1)) as nomor
		// FROM risk_mitigasi where RIGHT(nomor, locate('/', REVERSE(nomor)) - 1) = " . $this->conn->escape($id_unit) . ")a");
		$nomor_dr_unit = $this->conn->GetOne("
			select max(no) no from (select REPLACE(no_kti, '/" . $id_unit . "', '') AS no from (SELECT  nomor,
				CASE 
					WHEN locate('/', nomor) > 0 THEN RIGHT(nomor, locate('/', REVERSE(nomor)) - 1)
					ELSE ''
				END AS unit ,
				REPLACE(nomor, '$format', '') AS no_kti
			FROM risk_mitigasi 
			WHERE nomor IS NOT NULL) a where unit = " . $this->conn->escape($id_unit) . ") m
			");

		// dpr($nomor_dr_unit,1);
		// $autoincrement = "select max(coalesce(nomor_asli, nomor)) as nomor from risk_risiko where coalesce(nomor_asli, nomor) like '$format%'";

		// $formatAutoIncrement = $this->conn->GetOne($autoincrement);

		// $nomor = trim(str_replace($format, '', $formatAutoIncrement));
		// $cek = explode(".", $nomor);

		// if (($cek) > 1)
		// 	$nomor = $cek[0];

		$nomor = (int)$nomor_dr_unit;

		$nomor++;
		$ret = $format . str_pad($nomor, 2, '0', STR_PAD_LEFT) . "/" . $id_unit;

		return $ret;
	}
	public function SelectGridOverdue($arr_param = array(), $str_field = "m.*,m.nama as nama_aktifitas, j.nama as nama_pic, r.nomor as kode_risiko, r.nama as nama_risiko, r.id_scorecard")
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

		$str_condition = "where 1=1 and is_control <> '1' and dead_line<=sysdate() and (id_status_progress <> 4 or status_progress <> 100)";
		$str_order = "";
		if (!empty($arr_params['filter'])) {
			$str_condition .= " and " . $arr_params['filter'];
		}
		if (!empty($arr_params['order'])) {
			$str_order = "order by r.id_risiko, " . $arr_params['order'];
		} else {
			$str_order = "order by r.id_risiko";
		}
		if ($arr_params['limit'] === -1) {
			$arr_return['rows'] = $this->conn->GetArray("
				select
				{$str_field}
				from
				" . $this->table . " m
				join mt_sdm_jabatan j on trim(m.penanggung_jawab) = trim(j.id_jabatan)
				join risk_risiko r on m.id_risiko = r.id_risiko
				{$str_condition}
				{$str_order} ");
		} else {
			$arr_return['rows'] = $this->conn->PageArray(
				"
				select
				{$str_field}
				from
				" . $this->table . " m
				join mt_sdm_jabatan j on trim(m.penanggung_jawab) = trim(j.id_jabatan)
				join risk_risiko r on m.id_risiko = r.id_risiko
				join risk_scorecard s on r.id_scorecard = s.id_scorecard
				{$str_condition}
				{$str_order} ",
				$arr_params['limit'],
				$arr_params['page']
			);
		}

		$arr_return['total'] = static::GetOne("
			select
			count(*) as total
			from
			" . $this->table . " m
				join mt_sdm_jabatan j on trim(m.penanggung_jawab) = trim(j.id_jabatan)
				join risk_risiko r on m.id_risiko = r.id_risiko
				join risk_scorecard s on r.id_scorecard = s.id_scorecard
			{$str_condition}
		");

		return $arr_return;
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
