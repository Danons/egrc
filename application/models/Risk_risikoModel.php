<?php

use function PHPSTORM_META\type;

class Risk_risikoModel extends _Model
{
	public $table = "risk_risiko";
	public $pk = "id_risiko";
	public $order_default = "id_scorecard, no, nama asc";
	private $scorecardstr = "";
	function __construct()
	{
		parent::__construct();

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

		if (!$id_jabatan)
			$id_jabatan = '0';

		if (!$id_unit)
			$id_unit = '0';

		$add_str = "";

		if ($this->ci->access_role['view_all_unit'] && !$this->ci->access_role['view_all'])
			$add_str = "id_unit = " . $this->conn->escape($id_unit) . " or ";

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
			)");

		if (!$ret)
			$ret = '0';

		return $ret;
	}

	public function SelectGridRisk($arr_param = array(), $str_field = "*")
	{
		$arr_return = array();
		$arr_params = array(
			'page' => 0,
			'limit' => 50,
			'where' => '',
			'tahun' => '',
			'id_periode_tw' => '',
			'order' => '',
			'filter' => ''
		);
		foreach ($arr_param as $idkey => $val) {
			$arr_params[$idkey] = $val;
		}

		$tahun = $arr_params['tahun'];
		$id_periode_tw = $arr_params['id_periode_tw'];
		$where = $arr_params['where'];

		$arr_params['page'] = ($arr_params['page'] / $arr_params['limit']) + 1;

		$str_condition = "";
		$str_order = "";
		if (!empty($arr_params['filter'])) {
			$str_condition = "where 1=1 " . $arr_params['filter'];
		}
		if (!empty($arr_params['order'])) {
			$str_order = "order by " . $arr_params['order'];
		}

		if (version_compare($this->conn->version(), '12.1', '>=')) {
			$this->conn->order_by($str_order);
			#$str_order = "";
		}

		$table = "(select /*top $arr_params[limit]*/
        rr.nomor, rr.id_risiko as id_risiko1, rk.*, 
		rr.id_scorecard, rm.id_tingkat, rm.id_kemungkinan, 
		rm.id_dampak, rr.nama as nama_risiko
        from risk_risiko rr
		left join risk_kri rk on rk.id_risiko = rr.id_risiko
        join risk_scorecard rs on rr.id_scorecard = rs.id_scorecard
		left join risk_risiko_current rc on rr.id_risiko = rc.id_risiko 
		and rc.tahun = " . $this->conn->escape($tahun) . " and rc.id_periode_tw = " . $this->conn->escape($id_periode_tw) . "

		left join mt_risk_matrix rm on rm.id_kemungkinan = coalesce(rc.id_kemungkinan, coalesce(rr.residual_kemungkinan_evaluasi, rr.control_kemungkinan_penurunan))
		and rm.id_dampak = coalesce(rc.id_dampak, coalesce(rr.residual_dampak_evaluasi, rr.control_dampak_penurunan))
		
        where 1=1 " . $where . ") a ";

		if ($arr_params['limit'] === -1) {
			$arr_return['rows'] = $this->conn->GetArray("
				select
				{$str_field}
				from
				" . $table . "
				{$str_condition}
				{$str_order} ");
		} else {
			// $arr_return['rows'] = $this->conn->PageArray(
			// 	"
			// 	select
			// 	{$str_field}
			// 	from
			// 	" . $table . "
			// 	{$str_condition}
			// 	{$str_order} ",
			// 	$arr_params['limit'],
			// 	$arr_params['page']
			// );
			$arr_return['rows'] = $this->conn->PageArray(
				"
				select
				{$str_field}
				from
				" . $table . "
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
			" . $table . "
			{$str_condition}
		");


		foreach ($arr_return['rows'] as &$r) {
			$rws = $this->conn->GetArray("select * from risk_kri_hasil 
			where tahun = " . $this->conn->escape($tahun) . " 
			and id_kri = " . $this->conn->escape($r['id_kri']));
			foreach ($rws as $rw)
				$r['nilai' . $rw['bulan']] = $rw['nilai'];
		}

		return $arr_return;
	}

	public function SelectGridKpi($arr_param = array(), $str_field = "*")
	{
		$arr_return = array();
		$arr_params = array(
			'page' => 0,
			'limit' => 50,
			'where' => '',
			'tahun' => '',
			'id_periode_tw' => '',
			'order' => '',
			'filter' => ''
		);
		foreach ($arr_param as $idkey => $val) {
			$arr_params[$idkey] = $val;
		}

		$tahun = $arr_params['tahun'];
		$where = $arr_params['where'];

		$arr_params['page'] = ($arr_params['page'] / $arr_params['limit']) + 1;

		$str_condition = "";
		$str_order = "";
		if (!empty($arr_params['filter'])) {
			$str_condition = " where 1=1 " . $arr_params['filter'];
		}
		if (!empty($arr_params['order'])) {
			$str_order = "order by " . $arr_params['order'];
		}

		if (version_compare($this->conn->version(), '12.1', '>=')) {
			$this->conn->order_by($str_order);
			#$str_order = "";
		}

		$table = "(select 
        a.id_kpi, c.id_kpi_target, a.nama, b.tahun, c.target, c.satuan, c.bobot, c.id_unit
        from kpi a
		join kpi_config b on a.id_kpi = b.id_kpi
		join kpi_target c on c.id_kpi = b.id_kpi and c.tahun = b.tahun
        where 1=1 and b.tahun = " . $this->conn->escape($tahun) . " " . $where . ") a ";

		if ($arr_params['limit'] === -1) {
			$arr_return['rows'] = $this->conn->GetArray("
				select
				{$str_field}
				from
				" . $table . "
				{$str_condition}
				{$str_order} ");
		} else {
			$arr_return['rows'] = $this->conn->PageArray(
				"
				select
				{$str_field}
				from
				" . $table . "
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
			" . $table . "
			{$str_condition}
		");


		foreach ($arr_return['rows'] as &$r) {
			$rws = $this->conn->GetArray("select * from kpi_target_realisasi 
			where id_kpi_target = " . $this->conn->escape($r['id_kpi_target']));
			foreach ($rws as $rw)
				$r['nilai' . $rw['bulan']] = $rw['nilai'];
		}

		return $arr_return;
	}

	public function SelectGrid($arr_param = array(), $str_field = "
	r.*, m.nama as nama_sasaran,
	/*concat(coalesce(r.inheren_kemungkinan,0),coalesce(r.inheren_dampak,0)) as inheren, 
	concat(coalesce(r.control_kemungkinan_penurunan,0),coalesce(r.control_dampak_penurunan,0)) as control, 
	concat(coalesce(r.residual_kemungkinan_evaluasi,0),coalesce(r.residual_dampak_evaluasi,0)) as actual, */
	coalesce((r.inheren_kemungkinan*r.inheren_dampak*(case when is_opp_inherent is null then 1 else is_opp_inherent end)),0) as inheren, 
	coalesce((r.control_kemungkinan_penurunan*r.control_dampak_penurunan*(case when is_opp_inherent is null then 1 else is_opp_inherent end)),0) as control, 
	coalesce((r.residual_kemungkinan_evaluasi*r.residual_dampak_evaluasi*(case when is_opp_inherent is null then 1 else is_opp_inherent end)),0) as actual, 
	concat(coalesce(r.residual_target_kemungkinan,0),coalesce(r.residual_target_dampak,0)) as risidual, 
	s.owner")
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

		$str_condition = " where 1=1 /*and (status_risiko !='2' or status_risiko is null) */";
		$str_order = "";
		if (!empty($arr_params['filter'])) {
			$str_condition .= " and " . $arr_params['filter'];
		}
		if (!empty($arr_params['order'])) {

			list($nama, $od) = explode(" ", $arr_params['order']);

			if ($nama == 'nama') {
				$arr_params['order'] = "CONVERT(regexp_substr($nama, '\d+'),UNSIGNED INTEGER) $od, $nama $od";
			}

			$str_order = "order by " . $arr_params['order'];
		} elseif ($this->order_default) {
			$str_order = "order by " . $this->order_default;
		}

		if (version_compare($this->conn->version(), '12.1', '>=')) {
			$this->conn->order_by($str_order);
			#$str_order = "";
		}

		$where = "";;

		if (!$this->ci->access_role['view_all']) {
			$where_unit = "";
			if ($_SESSION[SESSION_APP]['id_unit']) {
				$where_unit = " or r.id_risiko in (select id_risiko from risk_integrasi_internal  where id_unit= " . $this->conn->escape($_SESSION[SESSION_APP]['id_unit']) . " )";
			}
			if ($_SESSION[SESSION_APP]['id_jabatan']) {
				$where_unit .= "or r.id_risiko in( select id_risiko from risk_mitigasi_risiko where id_mitigasi in (select id_mitigasi from risk_mitigasi_program where penanggung_jawab = " . $this->conn->escape($_SESSION[SESSION_APP]['id_jabatan']) . "))";
			}
			$id_jabatan = $_SESSION[SESSION_APP]['id_jabatan'];
			#untuk interdependent
			$where .= " and (";
			if ($this->scorecardstr)
				$where .= " r.id_scorecard in ({$this->scorecardstr}) or ";
			$where .= " 
			exists(
			select 1 from risk_mitigasi rm where r.id_risiko = rm.id_risiko 
				and (penanggung_jawab = " . $this->conn->escape($id_jabatan) . " or penanggung_jawab = " . $this->conn->escape($_SESSION[SESSION_APP]['owner_jabatan']) . ")
			) $where_unit
		)";
		}

		$sql = "select
				{$str_field}
				from
				" . $this->table . " r
				left join risk_scorecard s on r.id_scorecard = s.id_scorecard
				left join risk_sasaran m on m.id_sasaran = r.id_sasaran
				where 1=1 $where";

		if ($arr_params['limit'] === -1) {
			$arr_return['rows'] = $this->conn->GetArray("
				select * from ($sql) a
				{$str_condition}
				{$str_order}");
		} else {
			$arr_return['rows'] = $this->conn->PageArray("
				select * from ($sql) a
				{$str_condition}
				{$str_order}", $arr_params['limit'], $arr_params['page']);
		}

		$arr_return['total'] = static::GetOne("
			select
			count(*) as total
			from
			($sql) a
			{$str_condition}
		");

		return $arr_return;
	}

	public function SelectGridStatus($arr_param = array(), $str_field = "
	r.*, 
	concat(coalesce(r.inheren_kemungkinan,0),coalesce(r.inheren_dampak,0)) as inheren, 
	concat(coalesce(r.residual_kemungkinan_evaluasi,0),coalesce(r.residual_dampak_evaluasi,0)) as actual, 
	concat(coalesce(r.control_kemungkinan_penurunan,0),coalesce(r.control_dampak_penurunan,0)) as control, 
	concat(coalesce(r.residual_target_kemungkinan,0),coalesce(r.residual_target_dampak,0)) as risidual, 
	s.owner")
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

		$str_condition = " where 1=1 ";
		$str_order = "";
		if (!empty($arr_params['filter'])) {
			$str_condition .= " and " . $arr_params['filter'];
		}
		if (!empty($arr_params['order'])) {

			list($nama, $od) = explode(" ", $arr_params['order']);

			if ($nama == 'nama') {
				$arr_params['order'] = "CONVERT(regexp_substr($nama, '\d+'),UNSIGNED INTEGER) $od, $nama $od";
			}

			$str_order = "order by " . $arr_params['order'];
		} elseif ($this->order_default) {
			$str_order = "order by " . $this->order_default;
		}

		if (version_compare($this->conn->version(), '12.1', '>=')) {
			$this->conn->order_by($str_order);
			#$str_order = "";
		}

		$str_condition1 = "";
		if (!$this->ci->access_role['view_all']) {
			$id_jabatan = $_SESSION[SESSION_APP]['id_jabatan'];
			$id_unit = $_SESSION[SESSION_APP]['id_unit'];

			if (!$id_jabatan)
				$id_jabatan = '0';

			if ($arr_params['tipe'] == 2) {
				$str_condition1 .= " and (m.penanggung_jawab = " . $this->conn->escape($id_jabatan) . " or m.penanggung_jawab = " . $this->conn->escape($_SESSION[SESSION_APP]['owner_jabatan']) . " or m.interdependent_delegasi = " . $this->conn->escape($id_jabatan) . ") and (m.status_konfirmasi != '2')";
				$str_condition1 .= " and not(s.id_status_pengajuan = '1' and m.status_konfirmasi='0') ";
			} else if ($this->scorecardstr) {
				$str_condition1 .= " and s.id_scorecard in ({$this->scorecardstr})";
			}
		}
		if ($arr_params['tipe'] == 2) {

			$sql = "select
				{$str_field}, m.nama as nama_mitigasi, m.id_mitigasi, m.status_konfirmasi
				from
				" . $this->table . " r
				left join risk_scorecard s on r.id_scorecard = s.id_scorecard
				join risk_mitigasi m on r.id_risiko = m.id_risiko and m.is_control <> '1'
				where s.owner <> m.penanggung_jawab and status_risiko = '1' $str_condition1";
		} elseif ($arr_params['tipe'] == 1) {

			$sql = "select
				{$str_field}
				from
				" . $this->table . " r
				left join risk_scorecard s on r.id_scorecard = s.id_scorecard
				where 1=1 and status_risiko = '1' $str_condition1";
		} else {
			$sql = "select
				{$str_field}
				from
				" . $this->table . " r
				left join risk_scorecard s on r.id_scorecard = s.id_scorecard
				where 1=1 and status_risiko = '1' and s.id_status_pengajuan = 3 $str_condition1";
		}

		if ($arr_params['limit'] === -1) {
			$arr_return['rows'] = $this->conn->GetArray("
				select * from ($sql) a
				{$str_condition}
				{$str_order}");
		} else {
			$arr_return['rows'] = $this->conn->PageArray("
				select * from ($sql) a
				{$str_condition}
				{$str_order}", $arr_params['limit'], $arr_params['page']);
		}

		$arr_return['total'] = static::GetOne("
			select
			count(*) as total
			from
			($sql) a
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
			$where_unit = "";
			if ($_SESSION[SESSION_APP]['id_unit']) {
				$where_unit = " or r.id_risiko in (select id_risiko from risk_integrasi_internal  where id_unit= " . $this->conn->escape($_SESSION[SESSION_APP]['id_unit']) . " )";
			}
			if ($_SESSION[SESSION_APP]['id_jabatan']) {
				$where_unit .= "or r.id_risiko in( select id_risiko from risk_mitigasi_risiko where id_mitigasi in (select id_mitigasi from risk_mitigasi_program where penanggung_jawab = " . $this->conn->escape($_SESSION[SESSION_APP]['id_jabatan']) . "))";
			}
			$id_jabatan = $_SESSION[SESSION_APP]['id_jabatan'];
			$where .= " and (";
			if ($this->scorecardstr)
				$where .= " r.id_scorecard in ({$this->scorecardstr}) or ";
			$where .= " exists(select 1 from risk_mitigasi rm where r.id_risiko = rm.id_risiko and (penanggung_jawab = " . $this->conn->escape($id_jabatan) . " or penanggung_jawab = " . $this->conn->escape($_SESSION[SESSION_APP]['owner_jabatan']) . " or interdependent_delegasi = " . $this->conn->escape($id_jabatan) . ")) $where_unit)";
		}

		$sql = "select r.*, ss.nama as sasaran_aktivitas from " . $this->table . " r 
		left join risk_kegiatan ss on r.id_kegiatan = ss.id_kegiatan
		where {$this->pk} = " . $this->conn->qstr($id) . $where;
		$ret = $this->conn->GetRow($sql);

		if (!$ret)
			$ret = array();

		$ret['is_finish'] = $this->CekFinish($id);
		$ret['id_unit'] = $this->conn->GetOne("select id_unit from risk_scorecard where id_scorecard = " . $this->conn->escape($ret['id_scorecard']));

		if ($ret['id_risiko_sebelum'])
			$ret['risiko_old'] = $this->conn->GetRow("select penyesuaian_tindakan_mitigasi from risk_risiko where id_risiko = " . $this->conn->escape($ret['id_risiko_sebelum']));

		$ret['dampak'] = $rdampak = $this->conn->GetArray("select * 
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

		$ret['penyebab'] = $rpenyebab = $this->conn->GetArray("select * 
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

		$id_sasaran = $ret['id_sasaran'];

		$ret['sasaran'] = $this->conn->GetOne("select nama from risk_sasaran where id_sasaran = " . $this->conn->escape($id_sasaran));

		$rowskpi = $this->conn->GetArray("select 
      		distinct k.* 
      		from risk_risiko_kpi s join risk_kpi k on s.id_kpi = k.id_kpi 
      		where id_risiko = " . $this->conn->escape($id));

		$ret['id_kpi'] = array();
		$idkpiarr = array();
		foreach ($rowskpi as $rkpi) {
			$ret['id_kpi'][$rkpi['id_kpi']] = $rkpi['id_kpi'];
			$idkpiarr[] = $rkpi['id_kpi'];
		}

		/* proyek terkait list

		$ret['id_proyek_terkait'] = array();
		$proyek_terkait = $this->conn->GetArray("select id_scorecard from risk_risiko_proyek_terkait b where b.id_risiko = ".$this->conn->escape($id));
		foreach($proyek_terkait as $h){
			$ret['id_proyek_terkait'][] = $h['id_scorecard'];
		}
		*/

		$addwhere = "";
		if (($idkpiarr)) {
			$addwhere = " or k.id_kpi in (" . implode(",", $idkpiarr) . ")";
		}

		// $this->conn->debug=1;
		$ret['kpi'] = $this->conn->GetArray("select 
      		distinct k.* 
      		from risk_sasaran_kpi s join risk_kpi k on s.id_kpi = k.id_kpi 
      		where (id_sasaran = " . $this->conn->escape($id_sasaran) . " $addwhere)");

		return $ret;
	}

	public function getListKertasKerja($param = array(), &$wherearr = array())
	{
		if ($wherearr)
			list($where, $id_periode_tw, $tahun, $bulan) = $wherearr;
		else
			list($where, $id_periode_tw, $tahun, $bulan) = $this->getWhere($param);

		// dpr($where, 1);
		$sql = "
		select
		concat(rr1.nomor ,' ', rr1.nama) as risiko_induk,
		rr.id_prioritas,
		rr.integrasi_eksternal,
		rr.id_risiko,
		rr.dampak_kuantitatif_inheren,
		rr.dampak_kuantitatif_current,
		rr.dampak_kuantitatif_residual,
		rr.status_risiko,
		case when rr.is_signifikan_inherent = 1 then 'S' else 'TS' end as is_signifikan_inherent,
		case when rr.is_signifikan_current = 1 then 'S' else 'TS' end as is_signifikan_current,
		case when rr.id_taksonomi_area = 89 then rr.id_aspek_lingkungan else '' end id_aspek_lingkungan,
		rr.nomor as kode_risiko,
		rto.nama as taksonomi_objective,
		rta.nama as taksonomi_area,
		rta.kode as taksonomi_area_kode,
		rr.regulasi as pemenuhan_kewajiban,
		rr.nama as risiko,
		rr.is_opp_inherent,
		case when rr.is_rutin is not null then 'R' else 'NR' end is_rutin_bak,
		s.nama as sasaran,
		rr.dampak as dampak1,
		mdj.nama as risk_owner,
		rr.inheren_kemungkinan as inheren_kemungkinan1,
		rr.inheren_dampak as inheren_dampak1,
		mrki.kode as inheren_kemungkinan,
		rr.id_kriteria_kemungkinan as kategori_kemungkinan,
		mrdi.kode as inheren_dampak,
		mrkd.nama as kategori_dampak,
		rr.is_opp_inherent * (mrki.rating) * (mrdi.rating) as level_risiko_inheren,
		rc.id_control,
		rc.id_pengukuran,
		eu.efektifitas as nama_pengukuran,
		rc.nama as nama_kontrol,
		rc.menurunkan_dampak_kemungkinan as control_menurunkan,
		rc.is_efektif as control_efektif,
		rr.control_kemungkinan_penurunan,
		rr.control_dampak_penurunan,
		mrkc.kode as kemungkinan_paskakontrol,
		mrdc.kode as dampak_paskakontrol,
		rr.is_opp_inherent * (mrkc.rating) * (mrdc.rating) as level_risiko_paskakontrol,
		rm.id_mitigasi,
		rm.nama as mitigasi_lanjutan,
		rm.menurunkan_dampak_kemungkinan as mitigasi_menurunkan,
		rm.dead_line as waktu_pelaksanaan,
		rm.start_date as waktu_pelaksanaan,
		rm.biaya as biaya_mitigasi,
		rm.cba as cba_mitigasi,
		msj.nama as penanggungjawab_mitigasi,
		rm.id_status_progress as capaian_mitigasi,
		concat(rm.status_progress , '%') as capaian_mitigasi_progress,
		rr.residual_target_kemungkinan,
		rr.residual_target_dampak,

		mrka.kode as kemungkinan_actual,
		mrda.kode as dampak_actual,
		rr.is_opp_inherent * (mrka.rating) * (mrda.rating) as level_risiko_actual,

		rm.is_efektif as mitigasi_efektif,
		mrka.kode as kemungkinan_rdual,
		mrda.kode as dampak_rdual,
		rr.is_opp_inherent * (mrda.rating) * (mrka.rating) as level_risiko_residual,

		rr.progress_capaian_kinerja as capaian_mitigasi_evaluasi,
		rr.hambatan_kendala as hambatan_kendala,
		rr.penyesuaian_tindakan_mitigasi as penyesuaian_mitigasi,
		concat(rr.kode_aktifitas,' ',rr.nama_aktifitas) as aktifitas,
		rr.sub_tahapan_kegiatan,
		rr.skor_inheren_kemungkinan,
		rr.skor_inheren_dampak,
		rr.skor_control_kemungkinan,
		rr.skor_control_dampak,
		coalesce(rrc.skor_kemungkinan, rr.skor_current_kemungkinan) skor_current_kemungkinan,
		coalesce(rrc.skor_dampak, rr.skor_current_dampak) skor_current_dampak,
		rr.skor_target_kemungkinan,
		rr.skor_target_dampak,
		rrc.id_risiko_current,


		kri.id_kri,
		kri.nama as nama_kri,
		kri.keterangan as formula_kri,
		kri.polaritas,
		kri.satuan,
		kri.batas_bawah,
		kri.batas_atas,
		kri.target_mulai,
		kri.target_sampai,
		rm.program_kerja,
		rm.rencana,
		rm.realisasi,
		rm.devisiasi,
		rm.satuan satuan_mitigasi,
		rr.hasil_mitigasi_terhadap_sasaran, 
		rr.is_monitoring_rmtik, 
		rr.is_monitoring_p2k3, 
		rr.is_monitoring_fkap, 
		rr.ket_monitoring_rmtik, 
		rr.ket_monitoring_p2k3, 
		rr.ket_monitoring_fkap, 
		rr.is_evaluasi_mitigasi, 
		rr.is_evaluasi_risiko,
		rr.nama_aktifitas,
		rr.id_unit,
		rm.nomor,

		mrdi.nama nama_dampak_inheren,
		mrki.nama nama_kemungkinan_inheren,
		mrda.nama nama_dampak_residual,
		mrka.nama nama_kemungkinan_residual,

		rp.id_risk_penyebab,
		rp.nama as penyebab,

		rd.id_risk_dampak,
		rd.nama as dampak,
		rr.scorecard,
		rk.nama as kegiatan,
		rr.is_rutin1 as is_rutin

		from (select rr.*,
		rs.id_unit,
		rs.nama as scorecard,
		rs.owner,
		case when rs.rutin_non_rutin is null or rs.rutin_non_rutin ='rutin' then 'R' else 'NR' end is_rutin1
		from risk_risiko rr
		left join risk_scorecard rs on rr.id_scorecard = rs.id_scorecard
		where  1=1 " . $where . ") rr
		left join risk_risiko rr1 on rr.id_risiko_parent = rr1.id_risiko
		left join risk_kegiatan rk on rr.id_kegiatan = rk.id_kegiatan
		left join risk_sasaran s on s.id_sasaran = rk.id_sasaran
		left join mt_risk_taksonomi_area rta on rta.id_taksonomi_area = rr.id_taksonomi_area
		left join mt_risk_taksonomi_objective rto on rta.id_taksonomi_objective = rto.id_taksonomi_objective
		left join mt_sdm_jabatan mdj on mdj.id_jabatan = rr.owner
		left join risk_risiko_current rrc on rr.id_risiko = rrc.id_risiko 
		and rrc.tahun = " . $this->conn->escape($tahun) . " and rrc.id_periode_tw = " . $this->conn->escape($bulan) . "
		left join mt_risk_kemungkinan mrka on mrka.id_kemungkinan = coalesce(rrc.id_kemungkinan, coalesce(rr.residual_kemungkinan_evaluasi, rr.control_kemungkinan_penurunan))
		left join mt_risk_dampak mrda on mrda.id_dampak = coalesce(rrc.id_dampak, coalesce(rr.residual_dampak_evaluasi,rr.control_dampak_penurunan))
		left join mt_risk_kemungkinan mrki on mrki.id_kemungkinan = rr.inheren_kemungkinan
		left join mt_risk_dampak mrdi on mrdi.id_dampak = rr.inheren_dampak
		left join risk_control_risiko rcr on rr.id_risiko = rcr.id_risiko
		left join risk_control rc on rc.id_control = rcr.id_control
		left join mt_risk_efektifitas_pengukuran eu on rc.id_pengukuran = eu.id_pengukuran
		left join mt_risk_kemungkinan mrkc on mrkc.id_kemungkinan = rr.control_kemungkinan_penurunan
		left join mt_risk_dampak mrdc on mrdc.id_dampak = rr.control_dampak_penurunan
		left join risk_mitigasi_risiko rmr on rr.id_risiko = rmr.id_risiko
		left join risk_mitigasi rm on rmr.id_mitigasi = rm.id_mitigasi and rm.is_control <> '1' 
		left join mt_sdm_jabatan msj on msj.id_jabatan = rm.penanggung_jawab
		left join mt_risk_kemungkinan mrkrsd on mrkrsd.id_kemungkinan = rr.residual_target_kemungkinan
		left join mt_risk_dampak mrdrsd on mrdrsd.id_dampak = rr.residual_target_dampak
		left join mt_risk_kriteria_dampak mrkd on mrkd.id_kriteria_dampak = rr.id_kriteria_dampak
		left join risk_kri kri on rr.id_risiko = kri.id_risiko
		left join risk_risiko_penyebab rrp on rr.id_risiko = rrp.id_risiko
		left join risk_penyebab rp on rp.id_risk_penyebab = rrp.id_risk_penyebab
		left join risk_risiko_dampak rrd on rr.id_risiko = rrd.id_risiko
		left join risk_dampak rd on rd.id_risk_dampak = rrd.id_risk_dampak
		";
		// $sql = "
		// select
		// concat(rr1.nomor ,' ', rr1.nama) as risiko_induk,
		// rs.nama as scorecard,
		// rr.id_prioritas,
		// rr.integrasi_eksternal,
		// rr.id_risiko,
		// rr.dampak_kuantitatif_inheren,
		// rr.dampak_kuantitatif_current,
		// rr.dampak_kuantitatif_residual,
		// rr.status_risiko,
		// case when rr.is_signifikan_inherent = 1 then 'S' else 'TS' end as is_signifikan_inherent,
		// case when rr.is_signifikan_current = 1 then 'S' else 'TS' end as is_signifikan_current,
		// case when rr.id_taksonomi_area = 89 then rr.id_aspek_lingkungan else '' end id_aspek_lingkungan,
		// rr.nomor as kode_risiko,
		// rto.nama as taksonomi_objective,
		// rta.nama as taksonomi_area,
		// rta.kode as taksonomi_area_kode,
		// rtap.kode as id_kategori_proyek,
		// rr.regulasi as pemenuhan_kewajiban,
		// rr.nama as risiko,
		// rr.is_opp_inherent,
		// case when rr.is_rutin is not null then 'R' else 'NR' end is_rutin_bak,
		// case when rs.rutin_non_rutin is null or rs.rutin_non_rutin ='rutin' then 'R' else 'NR' end is_rutin,
		// rr.sasaran,
		// sas.nama as penyebab,
		// dpk.nama as dampak,
		// rr.dampak as dampak1,
		// mdj.nama as risk_owner,
		// rr.inheren_kemungkinan as inheren_kemungkinan1,
		// rr.inheren_dampak as inheren_dampak1,
		// mrki.kode as inheren_kemungkinan,
		// rr.id_kriteria_kemungkinan as kategori_kemungkinan,
		// mrdi.kode as inheren_dampak,
		// mrkd.nama as kategori_dampak,
		// /*concat(mrki.kode , mrdi.kode) as level_risiko_inheren,*/
		// rr.is_opp_inherent * (mrki.rating) * (mrdi.rating) as level_risiko_inheren,
		// rc.id_control,
		// rc.id_pengukuran,
		// eu.efektifitas as nama_pengukuran,
		// rc.nama as nama_kontrol,
		// rc.menurunkan_dampak_kemungkinan as control_menurunkan,
		// rc.is_efektif as control_efektif,
		// rr.control_kemungkinan_penurunan,
		// rr.control_dampak_penurunan,
		// mrkc.kode as kemungkinan_paskakontrol,
		// mrdc.kode as dampak_paskakontrol,
		// /*concat(mrkc.kode , mrdc.kode) as level_risiko_paskakontrol,*/
		// rr.is_opp_inherent * (mrkc.rating) * (mrdc.rating) as level_risiko_paskakontrol,
		// rm.id_mitigasi,
		// rm.nama as nama_mitigasi,
		// case when rm.status_progress > 0 and rm.status_progress is not null then rm.nama else null end as nama_mitigasi_berjalan,
		// rm.menurunkan_dampak_kemungkinan as mitigasi_menurunkan,
		// rm.dead_line as waktu_pelaksanaan,
		// rm.start_date as waktu_pelaksanaan,
		// rm.biaya as biaya_mitigasi,
		// rm.cba as cba_mitigasi,
		// msj.nama as penanggungjawab_mitigasi,
		// rm.id_status_progress as capaian_mitigasi,
		// concat(rm.status_progress , '%') as capaian_mitigasi_progress,
		// rr.residual_target_kemungkinan,
		// rr.residual_target_dampak,

		// mrka.kode as kemungkinan_actual,
		// mrda.kode as dampak_actual,
		// /*concat(mrka.kode , mrda.kode) as level_risiko_actual,*/
		// rr.is_opp_inherent * (mrka.rating) * (mrda.rating) as level_risiko_actual,

		// rm.is_efektif as mitigasi_efektif,
		// rme.id_pengukuran as id_pengukuranm,
		// rme.id_mitigasi_efektif,
		// mrka.kode as kemungkinan_rdual,
		// mrda.kode as dampak_rdual,
		// /*concat(mrkrsd.kode , mrdrsd.kode) as level_risiko_residual,*/
		// rr.is_opp_inherent * (mrda.rating) * (mrka.rating) as level_risiko_residual,

		// rr.progress_capaian_kinerja as capaian_mitigasi_evaluasi,
		// rr.hambatan_kendala as hambatan_kendala,
		// rr.penyesuaian_tindakan_mitigasi as penyesuaian_mitigasi,
		// concat(rr.kode_aktifitas,' ',rr.nama_aktifitas) as aktifitas,
		// rr.sub_tahapan_kegiatan,
		// rr.skor_inheren_kemungkinan,
		// rr.skor_inheren_dampak,
		// rr.skor_control_kemungkinan,
		// rr.skor_control_dampak,
		// coalesce(rrc.skor_kemungkinan, rr.skor_current_kemungkinan) skor_current_kemungkinan,
		// coalesce(rrc.skor_dampak, rr.skor_current_dampak) skor_current_dampak,
		// rr.skor_target_kemungkinan,
		// rr.skor_target_dampak,
		// rrc.id_risiko_current,


		// kri.id_kri,
		// kri.nama as nama_kri,
		// kri.keterangan as formula_kri,
		// kri.polaritas,
		// kri.satuan,
		// kri.batas_bawah,
		// kri.batas_atas,
		// kri.target_mulai,
		// kri.target_sampai,
		// rm.program_kerja,
		// rm.rencana,
		// rm.realisasi,
		// rm.devisiasi,
		// rm.satuan satuan_mitigasi,
		// rr.hasil_mitigasi_terhadap_sasaran, 
		// rr.is_monitoring_rmtik, 
		// rr.is_monitoring_p2k3, 
		// rr.is_monitoring_fkap, 
		// rr.ket_monitoring_rmtik, 
		// rr.ket_monitoring_p2k3, 
		// rr.ket_monitoring_fkap, 
		// rr.is_evaluasi_mitigasi, 
		// rr.is_evaluasi_risiko,
		// rr.nama_aktifitas,
		// rs.id_unit,
		// kpi.nama kpi,
		// /*
		// dpkk.dampak, 
		// pybb.penyebab,
		// cntrl.nama_kontrol,
		// mtgs.mitigasi_lanjutan,
		// mtgs.nomor_mitigasi_lanjutan,
		// ssr.sasaran,
		// ing.integrasi_internal,*/

		// mrdi.nama nama_dampak_inheren,
		// mrki.nama nama_kemungkinan_inheren,
		// mrda.nama nama_dampak_residual,
		// mrka.nama nama_kemungkinan_residual

		// from risk_risiko rr
		// left join risk_scorecard rs on rr.id_scorecard = rs.id_scorecard
		// /*left join mt_sdm_jabatan mdj on trim(mdj.id_jabatan) = trim(rs.owner)*/
		// left join mt_sdm_jabatan mdj on mdj.id_jabatan = rs.owner
		// left join risk_risiko_current rrc on rr.id_risiko = rrc.id_risiko 
		// and rrc.tahun = " . $this->conn->escape($tahun) . " and rrc.id_periode_tw = " . $this->conn->escape($id_periode_tw) . "/*
		// left join mt_risk_kemungkinan mrka on mrka.id_kemungkinan = coalesce(rrc.id_kemungkinan, coalesce(rr.residual_kemungkinan_evaluasi, rr.control_kemungkinan_penurunan))
		// left join mt_risk_dampak mrda on mrda.id_dampak = coalesce(rrc.id_dampak, coalesce(rr.residual_dampak_evaluasi,rr.control_dampak_penurunan))*/
		// left join mt_risk_kemungkinan mrka on mrka.id_kemungkinan = rr.residual_kemungkinan_evaluasi
		// left join mt_risk_dampak mrda on mrda.id_dampak = rr.residual_dampak_evaluasi
		// left join mt_risk_kemungkinan mrki on mrki.id_kemungkinan = rr.inheren_kemungkinan
		// left join mt_risk_dampak mrdi on mrdi.id_dampak = rr.inheren_dampak
		// left join risk_control rc on rc.id_risiko = rr.id_risiko
		// left join mt_risk_kemungkinan mrkc on mrkc.id_kemungkinan = rr.control_kemungkinan_penurunan
		// left join mt_risk_dampak mrdc on mrdc.id_dampak = rr.control_dampak_penurunan

		// /*left join risk_mitigasi rm on rm.id_risiko = rr.id_risiko and rm.is_control <> '1'*/
		// left join (select a.id_risiko, b.id_mitigasi, b.nama, b.deskripsi, b.start_date, b.dead_line, b.end_date, b.id_status_progress, b.menurunkan_dampak_kemungkinan, b.biaya, b.revenue, b.is_efektif, b.progress_capaian_kinerja, b.hambatan_kendala, b.penyesuaian_tindakan_mitigasi, b.created_date, b.modified_date, b.created_by, b.modified_by, b.cba, b.is_control, b.penanggung_jawab, b.status_konfirmasi, b.is_lock, b.id_mitigasi_sebelum, b.rekomendasi_keterangan, b.rekomendasi_is_verified, b.rekomendasi_nid, b.rekomendasi_date, b.rekomendasi_jabatan, b.rekomendasi_group, b.review_nid, b.review_date, b.id_jabatan, b.review_jabatan, b.review_group, b.review_is_verified, b.no, b.review_kepatuhan, b.interdependent_delegasi, b.status_progress, b.id_pengukuran, b.remark, b.program_kerja, b.rencana, b.realisasi, b.devisiasi, b.satuan, b.anggaran, b.rab, b.target_penyelesaian, b.id_owner_sso, b.tujuan, b.integrasi_eksternal, b.id_prioritas, b.nomor, b.sasaran from risk_mitigasi_risiko a join risk_mitigasi b on a.id_mitigasi = b.id_mitigasi) rm on rm.id_risiko = rr.id_risiko and rm.is_control <> '1' 

		// left join (select c.id_risiko,d.* from risk_risiko_penyebab c join risk_penyebab d on c.id_risk_penyebab = d.id_risk_penyebab ) sas on sas.id_risiko = rr.id_risiko

		// left join (select f.*, e.id_risiko from risk_risiko_dampak e join risk_dampak f on f.id_risk_dampak = e.id_risk_dampak) dpk on dpk.id_risiko = rr.id_risiko

		// left join risk_mitigasi_efektif rme on rm.id_mitigasi = rme.id_mitigasi 
		// and rme.tahun = " . $this->conn->escape($tahun) . " and rme.id_periode_tw = " . $this->conn->escape($id_periode_tw) . "
		// left join mt_sdm_jabatan msj on msj.id_jabatan = rm.penanggung_jawab
		// left join mt_risk_kemungkinan mrkrsd on mrkrsd.id_kemungkinan = rr.residual_target_kemungkinan
		// left join mt_risk_dampak mrdrsd on mrdrsd.id_dampak = rr.residual_target_dampak
		// left join mt_risk_kriteria_dampak mrkd on mrkd.id_kriteria_dampak = rr.id_kriteria_dampak
		// left join mt_risk_taksonomi_area rta on rta.id_taksonomi_area = rr.id_taksonomi_area
		// left join mt_risk_taksonomi_area rtap on rtap.id_taksonomi_area = rr.id_kategori_proyek
		// left join mt_risk_taksonomi_objective rto on rta.id_taksonomi_objective = rto.id_taksonomi_objective
		// left join risk_risiko rr1 on rr.id_risiko_parent = rr1.id_risiko
		// left join risk_kri kri on rr.id_risiko = kri.id_risiko
		// left join kpi kpi on rr.id_kpi = kpi.id_kpi
		// left join mt_risk_efektifitas_pengukuran eu on rc.id_pengukuran = eu.id_pengukuran

		// /*
		// left join (SELECT b.id_risiko, STRING_AGG(CONCAT('-',nama), '<br> ') AS dampak FROM risk_dampak a join risk_risiko_dampak b on b.id_risk_dampak = a.id_risk_dampak group by b.id_risiko) dpkk on dpkk.id_risiko = rr.id_risiko
		// left join (select b.id_risiko, STRING_AGG(CONCAT('-',nama), '<br> ') AS penyebab from risk_penyebab a join risk_risiko_penyebab b on a.id_risk_penyebab = b.id_risk_penyebab group by b.id_risiko) pybb on pybb.id_risiko = rr.id_risiko
		// left join (select b.id_risiko, STRING_AGG(CONCAT('-',nama), '<br> ') AS nama_kontrol from risk_control a join risk_control_risiko b on b.id_control = a.id_control group by b.id_risiko) cntrl on cntrl.id_risiko = rr.id_risiko
		// left join (select b.id_risiko, STRING_AGG(CONCAT('-',nama), '<br> ') AS mitigasi_lanjutan, STRING_AGG(CONCAT('-',nomor), '<br> ') AS nomor_mitigasi_lanjutan from risk_mitigasi a join risk_mitigasi_risiko b on b.id_mitigasi = a.id_mitigasi group by b.id_risiko) mtgs on mtgs.id_risiko = rr.id_risiko	
		// left join (select b.id_risiko, STRING_AGG(CONCAT('-',a.nama), '<br> ') AS sasaran from risk_sasaran a join risk_risiko b on a.id_sasaran = b.id_sasaran group by  b.id_risiko) ssr on ssr.id_risiko = rr.id_risiko
		// left join (select b.id_risiko, STRING_AGG(CONCAT('-',a.table_desc), '<br> ') AS integrasi_internal from mt_sdm_unit a join risk_integrasi_internal b on trim(a.table_code) = trim(b.id_unit) group by  b.id_risiko) ing on ing.id_risiko = rr.id_risiko
		// */";
		$signifikan = ($this->config->item("batas_nilai_signifikan"));
		if ($param['jenis']) {
			if ($param['tingkat']) {
				switch ($param['jenis']) {
					case '1':
						$sql .= "join mt_risk_matrix mx 
					on mx.id_dampak = rr.inheren_dampak 
					and mx.id_kemungkinan = rr.inheren_kemungkinan
					and mx.id_tingkat = " . $this->conn->escape($param['tingkat']);
						break;
					case '2':
						$sql .= "join mt_risk_matrix mx 
					on mx.id_dampak = coalesce(rr.residual_dampak_evaluasi,rr.control_dampak_penurunan)
					and mx.id_kemungkinan = coalesce(rr.residual_kemungkinan_evaluasi, rr.control_kemungkinan_penurunan)
					and mx.id_tingkat = " . $this->conn->escape($param['tingkat']);
						break;
					case '3':
						$sql .= "join mt_risk_matrix mx 
					on mx.id_dampak = rr.residual_target_dampak 
					and mx.id_kemungkinan = rr.residual_target_kemungkinan
					and mx.id_tingkat = " . $this->conn->escape($param['tingkat']);
						break;
					case 'is_signifikan':
						$where .= "and abs(mrka.rating * mrda.rating)>=$signifikan";
						break;
				}
			} else {
				switch ($param['jenis']) {
					case 'is_signifikan':
						$where .= "and abs((mrki.rating) * (mrdi.rating))>=$signifikan";
						break;
				}
			}
		}

		// $sql .= " where  1=1 " . $where;
		$sql .= " order by rr.id_scorecard, rr.id_risiko";

		// dpr($sql,1);
		$ret = $this->conn->GetRows($sql);
		// dpr($ret,1);

		if (!$ret)
			$ret = array();

		// $temparr = array();
		// $temparrm = array();
		foreach ($ret as &$r) {
			$r['integrasi_internal'] = $this->conn->GetList("select 
			table_code as idkey, table_desc as val
			from mt_sdm_unit a 
			join risk_integrasi_internal b on trim(a.table_code) = trim(b.id_unit) 
			where b.id_risiko = " . $this->conn->escape($r['id_risiko']));
			// 	if ($r['id_control'] && !$temparr[$r['id_control']]) {
			// 		$temparr[$r['id_control']] = $this->conn->GetArray("select *
			// 		from risk_control_efektifitas ce
			// 		where ce.id_control = " . $this->conn->escape($r['id_control']));
			// 	}

			// 	if ($temparr[$r['id_control']])
			// 		foreach ($temparr[$r['id_control']]  as $r1) {
			// 			$r["efektif_" . $r1['id_efektifitas']] = $r1['id_jawaban'];
			// 		}

			// 	if ($r['id_mitigasi'] && !$temparr[$r['id_mitigasi']]) {
			// 		$temparrm[$r['id_mitigasi']] = $this->conn->GetArray("select *
			// 				from risk_mitigasi_efektif_m ce
			// 				where ce.id_mitigasi_efektif = " . $this->conn->escape($r['id_mitigasi_efektif']));
			// 	}

			// 	if ($temparrm[$r['id_mitigasi']])
			// 		foreach ($temparrm[$r['id_mitigasi']]  as $r1) {
			// 			$r["efektifm_" . $r1['id_efektif_m']] = $r1['id_efektif_m_jawaban'];
			// 		}
		}

		return $ret;
	}

	private function _getChildKpi($id_kpi)
	{
		$arr = [];
		$arr[] = $id_kpi;

		$rows = $this->conn->GetArray("select * from kpi where id_parent = " . $this->conn->escape($id_kpi));
		if ($rows) {
			foreach ($rows as $r) {
				$arr1 = $this->_getChildKpi($r['id_kpi']);
				foreach ($arr1 as $v) {
					$arr[] = $v;
				}
			}
		}

		return $arr;
	}

	// private function _getChildUnit($id_unit, &$arr)
	public function _getChildUnit($id_unit, &$arr)
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

	public function getWhere($param = array())
	{

		$bulan = $param['bulan'];
		$tahun = $param['tahun'];
		$tahun_input = $param['tahun_input'];
		$bulan_input = $param['bulan_input'];
		$is_risiko = $param['is_risiko'];
		if (!$bulan or !$tahun) {
			$tgl_efektif = date('Y-m-d');
			if ($_SESSION[SESSION_APP]['tgl_efektif']) {
				$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
			}
			list($tgl, $bulan1, $tahun1) = explode("-", $tgl_efektif);
			if (!$bulan)
				$bulan = $bulan1;
			if (!$tahun)
				$tahun = $tahun1;
		}
		if (!$bulan_input or !$tahun_input) {
			$tgl_efektif = date('Y-m-d');
			if ($_SESSION[SESSION_APP]['tgl_efektif']) {
				$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
			}
			list($tgl, $bulan_input1, $tahun_input1) = explode("-", $tgl_efektif);
			if (!$bulan_input)
				$bulan_input = $bulan_input1;
			if (!$tahun_input)
				$tahun_input = $tahun_input1;
		}

		$tgl_efektif = str_replace("-", "", $tgl_efektif);

		// $param['bulan'] = $bulan;
		// $param['tahun'] = $tahun;
		// $param['tahun_input'] = $tahun_input;
		// $param['bulan_input'] = $bulan_input;

		$wherearr1 = array();
		$wherearr = array();

		$wherearr1[] = " rr.is_lock = '1'";
		// $wherearr1[] = "rr.STATUS_RISIKO in ('1','0')";
		if (!$this->ci->access_role['view_all'] && $this->scorecardstr)
			$wherearr1[] = " rr.id_scorecard in ({$this->scorecardstr})";

		if (!$param['all']) {
			if (!$param['id_scorecard'])
				$param['id_scorecard'] = array(0);

			$this->conn->escape_string($param['id_scorecard']);
			$wherearr[] = "rs.id_scorecard in ('" . implode("','", array_filter($param['id_scorecard'])) . "')";
		} elseif ($param['id_scorecard'])
			$wherearr[] = "rs.id_scorecard = " . $this->conn->escape($param['id_scorecard']);

		if ($param['id_taksonomi_objective'])
			$wherearr1[] = "exists (select 1 from mt_risk_taksonomi_area mrt join mt_risk_taksonomi_objective mrta on mrt.id_taksonomi_objective = mrta.id_taksonomi_objective where rr.id_taksonomi_area = mrt.id_taksonomi_area and mrta.id_taksonomi_objective = " . $this->conn->escape($param['id_taksonomi_objective']) . ")";


		if ($param['bulan'] && $param['tanggal']) {
			$tgl1 = $param['tahun'] . $param['bulan'] . $param['tanggal'];
			$wherearr1[] = "DATE_FORMAT(ifnull(rr.tgl_risiko,str_to_date('$tgl1','%Y%m%d')),'%Y%m%d') <= " . $this->conn->escape($tgl1) . " and DATE_FORMAT(ifnull(rr.tgl_close,str_to_date('$tgl1','%Y%m%d')),'%Y%m%d') >= " . $this->conn->escape($tgl1);
		} elseif ($param['bulan']) {
			$blnthn = $param['tahun'] . $param['bulan'];
			$wherearr1[] = "DATE_FORMAT(ifnull(rr.tgl_risiko,str_to_date('$blnthn" . "01" . "','%Y%m%d')),'%Y%m') <= " . $this->conn->escape($blnthn) . " and DATE_FORMAT(ifnull(rr.tgl_close,str_to_date('$blnthn" . "01" . "','%Y%m%d')),'%Y%m') >= " . $this->conn->escape($blnthn);
		} else if ($param['tahun']) {
			$thn1 = $param['tahun'];
			$wherearr1[] = "DATE_FORMAT(ifnull(rr.tgl_risiko,str_to_date('$thn1" . "01" . "" . "01" . "','%Y%m%d')),'%Y') <= " . $this->conn->escape($param['tahun']) . " and DATE_FORMAT(ifnull(rr.tgl_close,str_to_date('$thn1" . "01" . "" . "01" . "','%Y%m%d')),'%Y') >= " . $this->conn->escape($param['tahun']);
		}

		if ($param['bulan_input'] && $param['tanggal_input']) {
			$tgl1 = $param['tahun_input'] . $param['bulan_input'] . $param['tanggal'];
			$wherearr1[] = "DATE_FORMAT(ifnull(rr.tgl_risiko,str_to_date('$tgl1','%Y%m%d')),'%Y%m%d') = " . $this->conn->escape($tgl1);
		} elseif ($param['bulan_input']) {
			$blnthn = $param['tahun_input'] . $param['bulan_input'];
			$wherearr1[] = "DATE_FORMAT(ifnull(rr.tgl_risiko,str_to_date('$blnthn" . "01" . "','%Y%m%d')),'%Y%m') = " . $this->conn->escape($blnthn);
		} else if ($param['tahun_input']) {
			$thn1 = $param['tahun_input'];
			$wherearr1[] = "DATE_FORMAT(ifnull(rr.tgl_risiko,str_to_date('$thn1" . "01" . "" . "01" . "','%Y%m%d')),'%Y') = " . $this->conn->escape($thn1);
		}

		if (!$param['tahun'] && !$param['bulan'] && !$param['tanggal'] && !$param['tahun_input'] && !$param['bulan_input'] && !$param['tanggal_input']) {
			$wherearr1[] = "rr.status_risiko = '1'";
		}

		$id_periode_tw = $this->conn->GetOne("select id_periode_tw from mt_periode_tw where '{$bulan}' between bulan_mulai and bulan_akhir");

		if ($param['id_sasaran'])
			$wherearr1[] = "rr.id_sasaran = " . $this->conn->escape($param['id_sasaran']);
		if ($param['is_risiko'])
			$wherearr1[] = "rr.is_opp_inherent = " . $this->conn->escape($param['is_risiko']);

		if ($param['id_kpi']) {
			$kpiarr = $this->_getChildKpi($param['id_kpi']);
			$this->conn->escape_string($kpiarr);
			$wherearr1[] = "rr.id_kpi in ('" . implode("','", $kpiarr) . "')";
		}

		if ($param['id_unit'])
			$wherearr[] = "rs.id_unit = " . $this->conn->escape($param['id_unit']);

		if (($wherearr1)) {

			if ($wherearr) {
				// $list_str_scr = $this->conn->GetListStr("select 
				// id_scorecard as val
				// from risk_scorecard rs
				// where " . implode(" and ", $wherearr));

				// if ($list_str_scr)
				// 	$wherearr1[] = " rr.id_scorecard in ($list_str_scr) ";
				$wherearr1[] = " exists (select 1 from (select rs.id_scorecard from risk_scorecard rs where " . implode(" and ", $wherearr) . ") a where rr.id_scorecard = a.id_scorecard) ";
			}

			// $sqlstr1 = implode(" union ", $this->conn->GetList("select 
			// ' select '|| max(id_risiko) || ' as id_risiko from dual ' as val
			// from risk_risiko rr
			// join risk_scorecard rs on rr.id_scorecard = rs.id_scorecard
			// where " . implode(" and ", array_merge($wherearr, $wherearr1)) . "
			// group by nomor_asli, rr.id_scorecard"));

			// if ($sqlstr1)
			// 	$wherearr[] = " exists (select 1 from ($sqlstr1) rsub where rsub.id_risiko = rr.id_risiko) ";
			// else
			// 	$wherearr[] = "1<>1";
			// $wherearr[] = "exists (select 1 from (select 
			// max(id_risiko) id_risiko
			// from risk_risiko rr
			// join risk_scorecard rs on rr.id_scorecard = rs.id_scorecard
			// where " . implode(" and ", array_merge($wherearr, $wherearr1)) . "
			// group by nomor_asli, rr.id_scorecard) rsub where rsub.id_risiko = rr.id_risiko)";
		}

		$wherearr = array_merge($wherearr, $wherearr1);
		$where = "";
		if (($wherearr))
			$where .= " and " . implode(" and ", $wherearr);

		if (isset($param['is_opp']))
			$where .= " and rr.is_opp_inherent = " . $param['is_opp'];

		$signifikan = ($this->config->item("batas_nilai_signifikan"));
		if ($param['is_signifikan'])
			$where .= " and abs(mrka.rating * mrda.rating * rr.is_opp_inherent)>=$signifikan";
		// dpr($where,1);
		return array($where, $id_periode_tw, $tahun);
	}

	public function getListRiskProfile($param = array(), &$wherearr = array())
	{

		if ($wherearr)
			list($where, $id_periode_tw, $tahun) = $wherearr;
		else
			list($where, $id_periode_tw, $tahun) = $this->getWhere($param);



		if (!$param['top'])
			$param['top'] = 10;

		$filarr['i'] = "mrdi.rating is not null and mrki.rating is not null";
		$filarr['c'] = "mrdc.rating is not null and mrkc.rating is not null";
		$filarr['a'] = "mrda.rating is not null and mrka.rating is not null";
		$filarr['r'] = "mrdr.rating is not null and mrkr.rating is not null";

		$arr['i'] = "abs(mrki.rating * mrdi.rating * rr.is_opp_inherent) desc, mrdi.rating desc, mrki.rating desc";
		$arr['c'] = "abs(mrkc.rating * mrdc.rating * rr.is_opp_inherent) desc, mrdc.rating desc, mrkc.rating desc";
		$arr['a'] = "abs(mrka.rating * mrda.rating * rr.is_opp_inherent) desc, mrda.rating desc, mrka.rating desc";
		$arr['r'] = "abs(mrkr.rating * mrdr.rating * rr.is_opp_inherent) desc, mrdr.rating desc, mrkr.rating desc";

		$sql = "select
			rr.*,
			coalesce(rc.id_kemungkinan, coalesce(rr.residual_kemungkinan_evaluasi, 0)) as actual_kemungkinan,
			coalesce(rc.id_dampak, coalesce(rr.residual_dampak_evaluasi, 0)) as actual_dampak,

			mrki.rating * mrdi.rating * rr.is_opp_inherent as level_risiko_inheren,
			mrkc.rating * mrdc.rating * rr.is_opp_inherent as level_risiko_control,
			mrka.rating * mrda.rating * rr.is_opp_inherent as level_risiko_actual,
			mrkr.rating * mrdr.rating * rr.is_opp_inherent as level_residual_evaluasi,

			msj.nama as risk_owner, 
			msu.table_desc as unit, 
			ss.nama as sasaran_aktivitas

			from risk_risiko rr
			left join risk_scorecard rs on rr.id_scorecard = rs.id_scorecard

			left join mt_risk_kemungkinan mrki on mrki.id_kemungkinan = rr.inheren_kemungkinan
			left join mt_risk_dampak mrdi on mrdi.id_dampak = rr.inheren_dampak

			left join risk_risiko_current rc on rr.id_risiko = rc.id_risiko 
			and rc.tahun = " . $this->conn->escape($tahun) . " and rc.id_periode_tw = " . $this->conn->escape($id_periode_tw) . "
			left join mt_risk_kemungkinan mrka on mrka.id_kemungkinan = coalesce(rc.id_kemungkinan, coalesce(rr.residual_kemungkinan_evaluasi, rr.control_kemungkinan_penurunan))
			left join mt_risk_dampak mrda on mrda.id_dampak = coalesce(rc.id_dampak, coalesce(rr.residual_dampak_evaluasi, rr.control_dampak_penurunan))

			left join mt_risk_kemungkinan mrkc on mrkc.id_kemungkinan = rr.control_kemungkinan_penurunan
			left join mt_risk_dampak mrdc on mrdc.id_dampak = rr.control_dampak_penurunan

			left join mt_risk_kemungkinan mrkr on mrkr.id_kemungkinan = rr.residual_target_kemungkinan
			left join mt_risk_dampak mrdr on mrdr.id_dampak = rr.residual_target_dampak

			left join mt_sdm_jabatan msj on msj.id_jabatan = rs.owner
			left join mt_sdm_unit msu on msj.id_unit = msu.table_code
			left join risk_sasaran ss on rr.id_sasaran = ss.id_sasaran";

		$sql .= " where  1=1 and rr.deleted_date is null  " . $where;

		if ($param['rating']) {
			$filterrating = array();
			foreach (str_split($param['rating']) as $k => $v) {
				if ($filarr[$v])
					$filterrating[] = $filarr[$v];

				break;
			}

			$sql .= " and " . implode(" and ", $filterrating);

			$orderrating = array();

			if ($param['order']) {
				$orderrating[] = $arr[$param['order']];
			} else {
				foreach (str_split($param['rating']) as $k => $v) {
					if ($arr[$v])
						$orderrating[] = $arr[$v];
				}
			}
		}
		// if ($param['rutin_non_rutin'])
		// 	$sql .= " and rs.rutin_non_rutin = " . $this->conn->escape($param['rutin_non_rutin']);

		if (!($orderrating))
			return array();


		$sql .= " order by " . implode(",", $orderrating) . ", ifnull(rr.urutan,0) asc";

		$sql = "select * from($sql) a limit " . (int)$param['top'];

		$ret = $this->conn->GetRows($sql);

		if (!$ret)
			$ret = array();
		else {
			foreach ($ret as &$r) {
				$rdampak = $this->conn->GetArray("select nama 
				from risk_dampak a 
				where exists (
					select 1 
					from risk_risiko_dampak b 
					where a.id_risk_dampak = b.id_risk_dampak
					and b.id_risiko = " . $this->conn->escape($r['id_risiko']) . "
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

				$r['dampak'] = implode("<br/>", $expdampak);
				if (!$r['nama'])
					$r['nama'] = implode(" <br/>", $expdampak);
			}
		}

		// dpr($ret,1);

		return $ret;
	}

	public function getCountAll($param = array(), &$wherearr = array())
	{
		if ($wherearr)
			list($where, $id_periode_tw, $tahun) = $wherearr;
		else
			list($where, $id_periode_tw, $tahun) = $this->getWhere($param);

		$tikatarr = $this->conn->GetList("select id_tingkat as idkey, nama as val from mt_risk_tingkat order by idkey desc");

		// $sql = "select count(rr.id_risiko) as val, m.id_tingkat as idkey
		// from risk_risiko rr
		// left join risk_scorecard rs on rr.id_scorecard = rs.id_scorecard
		// join mt_risk_matrix m on rr.inheren_kemungkinan = m.id_kemungkinan and rr.inheren_dampak = m.id_dampak 
		// where 1=1 " . $where . "
		// group by m.id_tingkat";

		// $rows = $this->conn->GetList($sql);
		// $ret = array();
		// $ret['total_inheren'] = 0;
		// foreach ($tikatarr as $k => $v) {
		// 	$ret['inheren'][$v] = (int)$rows[$k];
		// 	$ret['total_inheren'] += $rows[$k];
		// }

		// $sql = "select count(rr.id_risiko) as val, m.id_tingkat as idkey
		// from risk_risiko rr
		// left join risk_scorecard rs on rr.id_scorecard = rs.id_scorecard
		// join mt_risk_matrix m on rr.control_kemungkinan_penurunan = m.id_kemungkinan and rr.control_dampak_penurunan = m.id_dampak 
		// where 1=1 " . $where . "
		// group by m.id_tingkat";

		// $rows = $this->conn->GetList($sql);
		// $ret['total_control'] = 0;
		// foreach ($tikatarr as $k => $v) {
		// 	$ret['control'][$v] = (int)$rows[$k];
		// 	$ret['total_control'] += $rows[$k];
		// }

		#AKTUAL RISIKO
		$sql = "select 
		count(rr.id_risiko) as val, 
		m.id_tingkat, rs.id_scorecard, rs.nama
		from risk_risiko rr
		left join risk_scorecard rs on rr.id_scorecard = rs.id_scorecard
		
		left join risk_risiko_current rc on rr.id_risiko = rc.id_risiko 
		and rc.tahun = " . $this->conn->escape($tahun) . " and rc.id_periode_tw = " . $this->conn->escape($id_periode_tw) . "
		/* left join mt_risk_kemungkinan mrka on mrka.id_kemungkinan = coalesce(rc.id_kemungkinan, coalesce(rr.residual_kemungkinan_evaluasi, rr.control_kemungkinan_penurunan))
		 left join mt_risk_dampak mrda on mrda.id_dampak = coalesce(rc.id_dampak, coalesce(rr.residual_dampak_evaluasi, rr.control_dampak_penurunan))*/
		
		left join mt_risk_kemungkinan mrka on mrka.id_kemungkinan = coalesce(rc.id_kemungkinan, rr.residual_kemungkinan_evaluasi)
		left join mt_risk_dampak mrda on mrda.id_dampak = coalesce(rc.id_dampak, rr.residual_dampak_evaluasi)

		join mt_risk_matrix m on coalesce(rc.id_kemungkinan,rr.residual_kemungkinan_evaluasi) = m.id_kemungkinan and coalesce(rc.id_dampak, rr.residual_dampak_evaluasi) = m.id_dampak 
		where 1=1 and rr.deleted_date is null " . $where . "
		group by m.id_tingkat, rs.id_scorecard, rs.nama";

		$rows = $this->conn->GetArray($sql);
		$ret['total_actual'] = 0;
		foreach ($rows as $r) {
			$ret['actual'][$tikatarr[$r['id_tingkat']]] += (int)$r['val'];
			$ret['total_actual'] += (int)$r['val'];
			$ret['div_actual'][$r['id_scorecard']] += (int)$r['val'];
		}


		#CURRENT RISIKO
		$sql = "select count(rr.id_risiko) as val, m.id_tingkat, rs.id_scorecard, rs.nama
		from risk_risiko rr
		left join risk_scorecard rs on rr.id_scorecard = rs.id_scorecard

		left join risk_risiko_current rc on rr.id_risiko = rc.id_risiko 
		and rc.tahun = " . $this->conn->escape($tahun) . " and rc.id_periode_tw = " . $this->conn->escape($id_periode_tw) . "
		left join mt_risk_kemungkinan mrka on mrka.id_kemungkinan = coalesce(rc.id_kemungkinan, coalesce(rr.residual_kemungkinan_evaluasi, rr.control_kemungkinan_penurunan))
		left join mt_risk_dampak mrda on mrda.id_dampak = coalesce(rc.id_dampak, coalesce(rr.residual_dampak_evaluasi, rr.control_dampak_penurunan))

		join mt_risk_matrix m on rr.control_kemungkinan_penurunan = m.id_kemungkinan and rr.control_dampak_penurunan = m.id_dampak 
		where 1=1 and rr.deleted_date is null " . $where . "
		group by m.id_tingkat, rs.id_scorecard, rs.nama";

		$rows = $this->conn->GetArray($sql);
		$ret['total_current'] = 0;
		foreach ($rows as $r) {
			$ret['current'][$tikatarr[$r['id_tingkat']]] += (int)$r['val'];
			$ret['total_current'] += (int)$r['val'];
			$ret['div_current'][$r['id_scorecard']] += (int)$r['val'];
			$ret['scorecard_nama'][$r['id_scorecard']] = $r['nama'];
		}
		if (is_array($ret['div_current']))
			arsort($ret['div_current']);

		#EFEKTIFITAS PENGENDALIAN BERJALAN
		$sql = "select count(rct.id_control) as val, ep.efektifitas as idkey
		from risk_risiko rr
		left join risk_scorecard rs on rr.id_scorecard = rs.id_scorecard
		join risk_control_risiko rct1 on rr.id_risiko = rct1.id_risiko
		join risk_control rct on rct1.id_control = rct.id_control
		join mt_risk_efektifitas_pengukuran ep on rct.id_pengukuran = ep.id_pengukuran 

		left join risk_risiko_current rc on rr.id_risiko = rc.id_risiko 
		and rc.tahun = " . $this->conn->escape($tahun) . " and rc.id_periode_tw = " . $this->conn->escape($id_periode_tw) . "
		left join mt_risk_kemungkinan mrka on mrka.id_kemungkinan = coalesce(rc.id_kemungkinan, coalesce(rr.residual_kemungkinan_evaluasi, rr.control_kemungkinan_penurunan))
		left join mt_risk_dampak mrda on mrda.id_dampak = coalesce(rc.id_dampak, coalesce(rr.residual_dampak_evaluasi, rr.control_dampak_penurunan))

		where 1=1 and rr.deleted_date is null " . $where . "
		group by ep.efektifitas";

		$rows = $this->conn->GetList($sql);
		$ret['total_efektifitas'] = 0;
		foreach ($rows as $k => $v) {
			$ret['total_efektifitas'] += $v;
		}
		$ret['efektifitas'] = $rows;


		#PROGREM PENGENDALIAN LANJUTAN
		$sql = "select avg(coalesce(rm.status_progress,0)) as progress
		from risk_risiko rr
		left join risk_scorecard rs on rr.id_scorecard = rs.id_scorecard
		join risk_mitigasi rm on rr.id_risiko = rm.id_risiko

		left join risk_risiko_current rc on rr.id_risiko = rc.id_risiko 
		and rc.tahun = " . $this->conn->escape($tahun) . " and rc.id_periode_tw = " . $this->conn->escape($id_periode_tw) . "
		left join mt_risk_kemungkinan mrka on mrka.id_kemungkinan = coalesce(rc.id_kemungkinan, coalesce(rr.residual_kemungkinan_evaluasi, rr.control_kemungkinan_penurunan))
		left join mt_risk_dampak mrda on mrda.id_dampak = coalesce(rc.id_dampak, coalesce(rr.residual_dampak_evaluasi, rr.control_dampak_penurunan))

		where rm.is_control <> '1' and rr.deleted_date is null " . $where;
		$ret['total_progress'] = $this->conn->GetOne($sql);

		// $sql = "select count(1) as j, s.id_kajian_risiko
		// from risk_risiko rr
		// join risk_scorecard rs on rr.id_scorecard = rs.id_scorecard
		// where 1=1 " . $where . "
		// group by rs.id_kajian_risiko";

		// $rows = $this->conn->GetArray($sql);
		// $ret['total_risiko_kajian'] = array();
		// foreach ($rows as $r) {
		// 	$ret['total_risiko_kajian'][$r['id_kajian_risiko']] = $r['j'];
		// }

		return $ret;
	}

	function GetRisikoBySasaran($idKajianRisiko, $idSasaranStrategis = null, $id_scorecardarr = array())
	{
		$where = "";

		if (!$this->ci->access_role['view_all'] && $this->scorecardstr)
			$where .= " and rr.id_scorecard in ({$this->scorecardstr})";

		if (is_array($id_scorecardarr) && count($id_scorecardarr))
			$where .= " and rr.id_scorecard in (" . implode(",", $id_scorecardarr) . ")";

		$sql = "
		select /*rss.nama as sasaran,*/ rr.nama, rr.ID_SCORECARD, rr.ID_RISIKO, rr.penyebab as penyebab_risiko, rr.dampak as dampak_risiko, 
		concat(coalesce(rr.inheren_kemungkinan,0),coalesce(rr.inheren_dampak,0)) as inheren, 
		concat(coalesce(rr.control_kemungkinan_penurunan,0),coalesce(rr.control_dampak_penurunan,0)) as control, 
		concat(coalesce(rr.residual_target_kemungkinan,0),coalesce(rr.residual_target_dampak,0)) as risidual
		from risk_risiko rr
		left join risk_sasaran rss on rss.ID_SASARAN_STRATEGIS = rr.ID_SASARAN_STRATEGIS
		left join risk_scorecard rs on rs.ID_SCORECARD = rr.ID_SCORECARD
		where /*rs.id_kajian_risiko = " . $this->conn->escape($idKajianRisiko) . " and*/ rr.is_lock = '1' and rr.ID_SASARAN_STRATEGIS = " . $this->conn->escape($idSasaranStrategis) . $where;


		$ret = $this->conn->GetRows($sql);

		if (!$ret)
			$ret = array();

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

	public function GetRatingDKRisiko($id)
	{
		if (!$id) {
			return array();
		}
		$sql = "select r.*, tr.rating as rating_kemungkinancr, dr.rating as rating_dampakcr, mtr.rating as rating_tingkatrisikors, mdr.rating as rating_dampakrisikors
						from risk_risiko r
						left join mt_risk_kemungkinan tr on tr.id_kemungkinan = r.inheren_kemungkinan
						left join mt_risk_dampak dr on dr.id_dampak = r.inheren_kemungkinan
						left join mt_risk_kemungkinan mtr on mtr.id_kemungkinan = r.control_kemungkinan_penurunan
						left join mt_risk_dampak mdr on mdr.id_dampak = r.control_dampak_penurunan
						where r.id_risiko = " . $this->conn->escape($id);
		$ret = $this->conn->GetRow($sql);

		if (!$ret)
			$ret = array();

		$ret['is_finish'] = $this->CekFinish($id);

		$id_sasaran = $ret['id_sasaran'];
		$id_kegiatan = $ret['id_kegiatan'];

		$ret['kpi'] = $this->conn->GetOne("select kpi from risk_sasaran where id_sasaran = " . $this->conn->escape($id_sasaran));

		return $ret;
	}

	private function _getKodeKPI($id_kpi = null)
	{
		if (!isset($id_kpi)) {
			$row = $this->conn->GetRow("select * from kpi where id_kpi = " . $this->conn->escape($id_kpi));
		}
		if ($row['kode'])
			return $row['kode'];
		else if ($row['id_parent'])
			return $this->_getKodeKPI($row['id_parent']);
	}

	//for membuat no risiko otomatis berdasarkan kajian risiko
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

		$format = $id_unit;
		// if (!isset($id_taksonomi)) {
		// 	$format .= '/' . $this->conn->GetOne("select kode 
		// from mt_risk_taksonomi_area 
		// where id_taksonomi_area = " . $this->conn->escape($id_taksonomi));
		// }
		// $format .= '/' . $this->_getKodeKPI($id_kpi);
		// $format .= '/' . $this->conn->GetOne("select kode 
		// from kpi 
		// where id_kpi = " . $this->conn->escape($id_kpi));
		$format .= '/' . substr($tgl_risiko, 0, 4) . '/';
		// $format .= substr($tgl_risiko, 0, 4) . '/';

		if ($isformat)
			return $format;

		$autoincrement = "select max(coalesce(nomor_asli, nomor)) as nomor from risk_risiko where coalesce(nomor_asli, nomor) like '$format%'";

		$formatAutoIncrement = $this->conn->GetOne($autoincrement);

		$nomor = trim(str_replace($format, '', $formatAutoIncrement));
		$cek = explode(".", $nomor);

		if (($cek) > 1)
			$nomor = $cek[0];

		$nomor = (int)$nomor;

		$nomor++;

		$ret = $format . str_pad($nomor, 3, '0', STR_PAD_LEFT);

		return $ret;
	}

	// get combo untuk kajian risiko operasional
	function GetComboDashboard($id_kajian_risiko = null, $tgl_efektif = null, $id_tingkat_agregasi_risiko = null)
	{
		if (!$tgl_efektif)
			$tgl_efektif = date("Y-m-d");

		if ($_SESSION[SESSION_APP]['tgl_efektif']) {
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		}

		if ($tgl_efektif)
			$filter = " and '$tgl_efektif' between coalesce(tgl_mulai_efektif,'$tgl_efektif')and coalesce(tgl_akhir_efektif,'$tgl_efektif')";

		if ($this->scorecardstr)
			$filter .= " and id_scorecard in ({$this->scorecardstr})";

		if ($id_tingkat_agregasi_risiko)
			$filter .= " and id_tingkat_agregasi_risiko = " . $this->conn->escape($id_tingkat_agregasi_risiko);

		// if ($id_kajian_risiko == 'semua')
		$sql = "select id_scorecard, nama from risk_scorecard where navigasi = 0 " . $filter . " order by id_scorecard";
		// else
		// 	$sql = "select id_scorecard, nama from risk_scorecard where navigasi = 0 and id_kajian_risiko = " . $this->conn->escape($id_kajian_risiko) . $filter . " order by id_scorecard";

		$rows = $this->conn->GetArray($sql);

		$data = array('' => '-Sub kategori-');
		foreach ($rows as $r) {
			$data[$r['id_scorecard']] = $r['nama'];
		}
		return $data;
	}
}
