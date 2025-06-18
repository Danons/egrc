<?php class Risk_scorecardModel extends _Model
{
	public $table = "risk_scorecard";
	public $pk = "id_scorecard";
	private $scorecardstr = "";
	function __construct()
	{
		parent::__construct();

		// dpr($this->ci->access_role, 1);
		if (!$this->ci->access_role['view_all'])
			$this->scorecardstr = $this->GetScorecardstr();
	}

	private function GetScorecardstr()
	{
		// $ret = $this->conn->GetListStr("select 
		// 	id_scorecard as val
		// 	from risk_scorecard rs");

		$id_jabatan = $_SESSION[SESSION_APP]['id_jabatan'];
		$owner_jabatan = $_SESSION[SESSION_APP]['owner_jabatan'];
		$id_unit = $_SESSION[SESSION_APP]['id_unit'];
		// dpr($_SESSION[SESSION_APP], 1);

		if (!$id_jabatan)
			$id_jabatan = '0';

		if (!$id_unit)
			$id_unit = '0';

		$add_str = "";

		if ($this->ci->access_role['view_all_unit'] && !$this->ci->access_role['view_all'])
			$add_str = "id_unit = " . $this->conn->escape($id_unit) . " or ";

		// $this->conn->debug=1;
		$ret = $this->conn->GetListStr("select 
			id_scorecard as val
			from risk_scorecard rs
			where (
			$add_str
			owner = " . $this->conn->escape($id_jabatan) . "
			or 
			owner = " . $this->conn->escape($owner_jabatan) . "
			/*or 
			exists (select 1 from risk_scorecard_user rsu where rs.id_scorecard = rsu.id_scorecard and rsu.id_jabatan = " . $this->conn->escape($id_jabatan) . ")
			or 
			exists (select 1 from risk_scorecard_view rsv where rs.id_scorecard = rsv.id_scorecard and rsv.id_jabatan = " . $this->conn->escape($id_jabatan) . ")
			or 
			exists (
				select 1 from risk_risiko rr 
				where rs.id_scorecard = rr.id_scorecard 
				and exists(select 1 from risk_mitigasi rm where rr.id_risiko = rm.id_risiko and 
					(penanggung_jawab = " . $this->conn->escape($id_jabatan) . " or penanggung_jawab = " . $this->conn->escape($owner_jabatan) . " or interdependent_delegasi = " . $this->conn->escape($id_jabatan) . ")
				)
			)*/
			or (id_scorecard in (select id_scorecard from risk_risiko where id_risiko in (
                select id_risiko from risk_integrasi_internal  where id_unit=" . $this->conn->escape($id_unit) . "))
			or 
            	id_scorecard in (
                select id_scorecard from risk_risiko where id_risiko in(
				select id_risiko from risk_mitigasi_risiko where id_mitigasi in (select id_mitigasi from risk_mitigasi_program where penanggung_jawab = " . $this->conn->escape($id_jabatan) . "))
            )  )
		)");
		// dpr($ret,1);

		if (!$ret)
			$ret = '0';

		return $ret;
	}

	private function _getChildUnit($id_unit, &$arr)
	{
		if (!$id_unit || in_array($id_unit, $arr))
			return;

		$arr[] = $id_unit;

		$sql = "select 
		distinct b.id_unit
		from mt_sdm_jabatan b 
		join mt_sdm_jabatan c on b.id_jabatan_parent =c.id_jabatan
		where c.id_unit = " . $this->conn->escape($id_unit);

		$rows = $this->conn->GetArray($sql);

		if ($rows) {
			foreach ($rows as $r) {
				$this->_getChildUnit($r['id_unit'], $arr);
			}
		}
	}


	function GetList($id_parent_scorecard = null, $tgl_efektif = null, $is_no_window = false, $tahun = null, $want_evaluasi = false, $id_unit = null, $bulan = null)
	{
		if (!$tgl_efektif)
			$tgl_efektif = date('Y-m-d');

		$filter = "";
		if ($tahun && $bulan) {
			$bulantahun = $tahun . $bulan;
			$filter = " and '$bulantahun' between coalesce(date_format(a.tgl_mulai_efektif,'%Y%m'),'$bulantahun') and coalesce(date_format(a.tgl_akhir_efektif,'%Y%m'),'$bulantahun')";
		} else if ($tahun) {
			$filter = " and '$tahun' between coalesce(date_format(a.tgl_mulai_efektif,'%Y'),'$tahun') and coalesce(date_format(a.tgl_akhir_efektif,'%Y'),'$tahun')";
		} elseif ($tgl_efektif) {
			$filter = " and str_to_date('$tgl_efektif','%Y-%m-%d') between coalesce(a.tgl_mulai_efektif,str_to_date('$tgl_efektif','%Y-%m-%d')) and coalesce(a.tgl_akhir_efektif,str_to_date('$tgl_efektif','%Y-%m-%d'))";
		}
		if ($this->scorecardstr !== '')
			$filter .= " and a.id_scorecard in ({$this->scorecardstr})";

		if ($id_unit) {
			$id_unitarr = [];
			$this->_getChildUnit($id_unit, $id_unitarr);
			if ($id_unitarr) {
				$filter = " and a.id_unit in ('" . implode("','", $id_unitarr) . "')";
			} else {
				$filter = " and a.id_unit = " . $this->conn->escape($id_unit);
			}
			// $filter .= " and a.id_unit = " . $this->conn->escape($id_unit);
		}

		if ($id_parent_scorecard) {
			$filter .= " and id_parent_scorecard = " . $this->conn->escape($id_parent_scorecard);
		}

		// else {
		// 	$filter .= " and id_parent_scorecard is null ";
		// }

		$sql = "select a.navigasi, a.id_scorecard, a.nama, a.id_status_pengajuan,
			a.id_scorecard as id,
			a.id_parent_scorecard as id_parent, a.owner, a.open_evaluasi
			from 
			risk_scorecard a
			left join mt_sdm_jabatan s on a.owner = s.id_jabatan
			where 1=1 and a.deleted_date is null $filter";

		$sql .= "
		order by a.navigasi, s.position_id, id";

		// dpr($sql, 1);

		$rows = $this->conn->GetArray($sql);

		// $this->_getParent($rows);

		foreach ($rows as $k => $r) {
			$rows[$k]['userarr'] = $this->conn->GetList("select id_jabatan as idkey, id_jabatan as val from risk_scorecard_user where id_scorecard = " . $this->conn->escape($r['id_scorecard']));
		}

		$ret = array();
		$this->GenerateSort1($rows, "id_parent", "id", "nama", $ret, $id_parent_scorecard, $is_no_window);
		return $ret;
	}

	// private function _getParent(&$rows, $arr=[]){
	// 	if(!$arr)
	// 	foreach($rows as $r){
	// 	$arr[$r['id_scorecard']] = $r['id_parent_scorecard'];
	// }
	// 	foreach($rows)
	// }


	function GenerateSort1(&$row, $colparent, $colid, $collabel, &$return = array(), $valparent = null, $is_no_window = false, &$i = 0, $level = 0, $is_space = false, $idarr = array())
	{
		$level++;

		if ($idarr[$valparent])
			return;

		$idarr[$valparent] = 1;

		foreach ($row as $idkey => $value) {
			if ((int)trim($value[$colparent]) == (int)trim($valparent)) {
				unset($row[$idkey]);

				if ($idarr[$value[$colid]])
					$value[$colparent] = null;

				$return[$i] = $value;

				$i++;

				if ($value['navigasi'] <> '0' or $is_no_window)
					$this->GenerateSort1($row, $colparent, $colid, $collabel, $return, $value[$colid], $is_no_window, $i, $level, false, $idarr);
			}
		}

		if (!$valparent && $row) {
			foreach ($row as $k => $v) {
				$row[$k][$colparent] = null;
			}

			$return = array_merge($return, $row);
		}
	}

	public function GetByPk($id)
	{
		if (!$id) {
			return array();
		}

		$ret = parent::GetByPk($id);
		$where = "";
		if (!$this->ci->access_role['view_all']) {
			$id_jabatan = $_SESSION[SESSION_APP]['id_jabatan'];
			$where .= " and (s.id_scorecard in ({$this->scorecardstr}) 
			or 
			exists (select 1 from risk_risiko rr where s.id_scorecard = rr.id_scorecard 
			and exists(select 1 from risk_mitigasi rm where rr.id_risiko = rm.id_risiko and 
			(penanggung_jawab = " . $this->conn->escape($id_jabatan) . " or penanggung_jawab = " . $this->conn->escape($_SESSION[SESSION_APP]['owner_jabatan']) . " or interdependent_delegasi = " . $this->conn->escape($id_jabatan) . ")
			)))";
		}


		$ret['userarr'] = $this->conn->GetList("select id_jabatan as idkey, id_jabatan as val from risk_scorecard_user where id_scorecard = " . $this->conn->escape($id));

		if (!$this->ci->access_role['view_all']) {

			// $childarr = $_SESSION[SESSION_APP]['child_jabatan'];																																												
			$owner_jabatan = $_SESSION[SESSION_APP]['owner_jabatan'];
			$id_jabatan = $_SESSION[SESSION_APP]['id_jabatan'];

			if (empty($ret['userarr']))
				$ret['userarr'] = array();

			if (empty($childarr))
				$childarr = array();

			$owner = $ret['owner'];

			if (
				!($owner_jabatan == $owner || $id_jabatan == $owner)
				&& !in_array($id_jabatan, $ret['userarr'])
				&& !($this->ci->Access('view_all_unit', 'main')
					&& $_SESSION[SESSION_APP]['id_unit'] == $ret['id_unit'])
			) {
				$this->ci->data['edited'] = false;
				$this->ci->data['editedheader'] = false;
				$this->ci->data['editedheader1'] = false;
				$this->ci->access_role['add'] = false;
				$this->ci->access_role['edit'] = false;
				$this->ci->access_role['delete'] = false;
			}
		}

		$ret['broadcrumscorecard'] = $this->GetComboParent($ret['id_scorecard']);

		unset($ret['broadcrumscorecard'][$ret['id_scorecard']]);
		$owner = $ret['owner'];

		$ret['nama_pejabat'] = $this->conn->GetOne("select a.nama_lengkap 
		from mt_sdm_pegawai a 
		join mt_sdm_jabatan b on a.position_id = b.position_id
		where b.id_jabatan = " . $this->conn->escape($owner));


		$ret['update_terakhir'] = $this->GetRow("select a.* 
		from risk_log a 
		where exists (select 1 
			from risk_risiko b where a.id_risiko = b.id_risiko 
			and b.id_scorecard = " . $this->conn->escape($ret['id_scorecard']) . "
		) 
		order by activity_time desc limit 1");

		return $ret;
	}

	public function GetCombo($idkey = null, $q = null, $tgl_efektif = null, $id_parent_scorecard = null)
	{

		$tgl_efektif = date('Y-m-d');

		if ($_SESSION[SESSION_APP]['tgl_efektif']) {
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		}

		$id_parent = null;

		$sql = "select * from risk_scorecard 
		where navigasi = 1 and str_to_date('$tgl_efektif','%Y-%m-%d') 
		between ifnull(tgl_mulai_efektif,str_to_date('$tgl_efektif','%Y-%m-%d'))
		and ifnull(tgl_akhir_efektif,str_to_date('$tgl_efektif','%Y-%m-%d'))";

		if ($q)
			$sql .= " and  lower(nama) like '%$q%'";

		if ($idkey)
			$sql .= " and id_scorecard = " . $this->conn->escape($idkey);

		if ($id_parent_scorecard)
			$sql .= " and id_parent_scorecard = " . $this->conn->escape($id_parent_scorecard);


		if ($this->scorecardstr !== '')
			$sql .= " and id_scorecard in ({$this->scorecardstr})";

		$sql .= " order by id_parent_scorecard, id_scorecard";

		$rows = $this->conn->GetArray($sql);

		$ret = array();

		$this->GenerateTree($rows, "id_parent_scorecard", "id_scorecard", "nama", $ret, $id_parent);

		$return = array('' => '-pilih-');
		foreach ($ret as $r) {
			$return[$r['id_scorecard']] = str_replace(" &amp; ", " ", $r['nama']);
		}

		return $return;
	}
	public function GetCombo2($idkey = null, $q = null, $tgl_efektif = null, $id_parent_scorecard = null, $where = null)
	{

		$tgl_efektif = date('Y-m-d');

		if ($_SESSION[SESSION_APP]['tgl_efektif']) {
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		}

		$tgl_efektif = str_replace("-", "", $tgl_efektif);
		$id_parent = null;

		$sql = "select * from risk_scorecard 
		where navigasi = 1 and str_to_date('$tgl_efektif','%Y-%m-%d') 
		between coalesce(tgl_mulai_efektif,str_to_date('$tgl_efektif','%Y-%m-%d'))
		and coalesce(tgl_akhir_efektif,str_to_date('$tgl_efektif','%Y-%m-%d'))";

		if ($q)
			$sql .= " and lower(nama) like '%$q%'";

		if ($idkey)
			$sql .= " and id_scorecard = " . $this->conn->escape($idkey);

		if ($id_parent_scorecard)
			$sql .= " and id_parent_scorecard = " . $this->conn->escape($id_parent_scorecard);


		if ($where)
			$sql .= " and " . $where;

		if ($this->scorecardstr !== '')
			$sql .= " and id_scorecard in ({$this->scorecardstr})";

		$sql .= " order by id_parent_scorecard, id_scorecard";

		$rows = $this->conn->GetArray($sql);

		$ret = array();

		$this->GenerateTree($rows, "id_parent_scorecard", "id_scorecard", "nama", $ret, $id_parent);

		$return = array('' => '-pilih-');
		foreach ($ret as $r) {
			$return[$r['id_scorecard']] = str_replace(" &amp; ", " ", $r['nama']);
		}

		return $return;
	}

	public function GetChild($id_parent = null, $idarr = array())
	{

		$idarr[$id_parent] = 1;

		$tgl_efektif = date('Y-m-d');

		if ($_SESSION[SESSION_APP]['tgl_efektif']) {
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		}
		$ret = array();

		if ($id_parent) {
			$rows = $this->conn->GetArray("select id_scorecard 
			from risk_scorecard 
			where id_parent_scorecard = " . $this->conn->escape($id_parent) . " 
			and str_to_date('$tgl_efektif','%Y-%m-%d') 
			between ifnull(tgl_mulai_efektif,str_to_date('$tgl_efektif','%Y-%m-%d'))
			and ifnull(tgl_akhir_efektif,str_to_date('$tgl_efektif','%Y-%m-%d'))");

			$ret[] = $id_parent;
		} else
			$rows = $this->conn->GetArray("select id_scorecard 
			from risk_scorecard 
			where str_to_date('$tgl_efektif','%Y-%m-%d') 
			between ifnull(tgl_mulai_efektif,str_to_date('$tgl_efektif','%Y-%m-%d'))
			and ifnull(tgl_akhir_efektif,str_to_date('$tgl_efektif','%Y-%m-%d'))");

		if ($rows)
			foreach ($rows as $r) {
				if (!$idarr[$r['id_scorecard']]) {
					$ret1 = $this->GetChild($r['id_scorecard'], $idarr);
					$ret = array_merge($ret, $ret1);
				}
			}

		return $ret;
	}

	public function GetComboChild($id_parent = null, $is_tree = true)
	{
		$id_scorecardarr = $this->GetChild($id_parent);
		if (!$id_scorecardarr)
			return array();

		$addfilter = "";

		if ($this->scorecardstr)
			$addfilter = " and id_scorecard in ($this->scorecardstr)";

		$rows = $this->conn->GetArray("select 
			nama, 
			id_scorecard as id,
			id_parent_scorecard as id_parent
			from risk_scorecard 
			where id_scorecard in (" . implode(",", $id_scorecardarr) . ") 
			$addfilter");

		if (!$rows)
			return array();

		$ret = array();
		if ($is_tree)
			$this->GenerateTree($rows, "id_parent", "id", "nama", $ret, $id_parent);
		else
			$ret = $rows;

		$data = array('' => '-pilih-');
		foreach ($ret as $r) {
			$data[$r['id']] = $r['nama'];
		}
		unset($data[$id_parent]);

		if (($data) == 1)
			return array();

		return $data;
	}

	public function GetComboParent($id_child = null, $idarr = array())
	{
		$row = $this->conn->GetRow("select id_scorecard, navigasi, id_parent_scorecard, nama from risk_scorecard where id_scorecard = " . $this->conn->escape($id_child));
		if (!$row)
			return array();

		$idarr[$id_child] = 1;

		$ret = array();
		if ($row['id_parent_scorecard'] and !$idarr[$row['id_parent_scorecard']]) {
			$ret = $this->GetComboParent($row['id_parent_scorecard'], $idarr);
		}

		$ret[] = array(
			"url" => ($row['navigasi'] ? site_url("panelbackend/risk_scorecard/index/" . $id_child) : site_url("panelbackend/risk_risiko/index/" . $id_child)),
			"label" => $row['nama']
		);

		return $ret;
	}
}
