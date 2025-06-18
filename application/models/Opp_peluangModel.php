<?php class Opp_peluangModel extends _Model
{
	public $table = "opp_peluang";
	public $pk = "id_peluang";
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

		$ret = $this->conn->GetListStr("select 
			id_scorecard as val
			from opp_scorecard rs where rs.deleted_date is null");
		// $id_jabatan = $_SESSION[SESSION_APP]['id_jabatan'];
		// $id_unit = $_SESSION[SESSION_APP]['id_unit'];

		// if (!$id_jabatan)
		// 	$id_jabatan = '0';

		// if (!$id_unit)
		// 	$id_unit = '0';

		// $add_str = "";

		// if ($this->ci->Access("view_all_unit", "panelbackend/opp_scorecard"))
		// 	$add_str = "id_unit = " . $this->conn->escape($id_unit) . " or ";

		// $ret = $this->conn->GetListStr("select 
		// 	id_scorecard as val
		// 	from opp_scorecard rs
		// 	where (
		// 	$add_str
		// 	owner = " . $this->conn->escape($id_jabatan) . "
		// 	or 
		// 	exists (select 1 from opp_scorecard_user rsu where rs.id_scorecard = rsu.id_scorecard and rsu.id_jabatan = " . $this->conn->escape($id_jabatan) . ")
		// 	or 
		// 	exists (select 1 from opp_scorecard_view rsv where rs.id_scorecard = rsv.id_scorecard and rsv.id_jabatan = " . $this->conn->escape($id_jabatan) . ")
		// 	/*or navigasi in (1,2)*/
		// )");

		// if (!$ret)
		// 	$ret = '0';

		return $ret;
	}

	public function SelectGrid($arr_param = array(), $str_field = "
	r.*, 
	coalesce(r.inheren_kemungkinan*r.inheren_dampak,0) as inheren, 
	concat(ifnull(r.control_kemungkinan_penurunan,0),ifnull(r.control_dampak_penurunan,0)) as control, 
	concat(ifnull(r.residual_kemungkinan_evaluasi,0),ifnull(r.residual_dampak_evaluasi,0)) as actual, 
	concat(ifnull(r.residual_target_kemungkinan,0),ifnull(r.residual_target_dampak,0)) as risidual, 
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

		$str_condition = " where 1=1 /*and (status_peluang !='2' or status_peluang is null) */";
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
			$id_jabatan = $_SESSION[SESSION_APP]['id_jabatan'];
			#untuk interdependent
			$where .= " and (r.id_scorecard in ({$this->scorecardstr}))";
		}

		$sql = "select
				{$str_field}
				from
				" . $this->table . " r
				left join opp_scorecard s on r.id_scorecard = s.id_scorecard
				where 1=1 and r.deleted_date is null $where";

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
	concat(ifnull(r.inheren_kemungkinan,0),ifnull(r.inheren_dampak,0)) as inheren, 
	concat(ifnull(r.residual_kemungkinan_evaluasi,0),ifnull(r.residual_dampak_evaluasi,0)) as actual, 
	concat(ifnull(r.control_kemungkinan_penurunan,0),ifnull(r.control_dampak_penurunan,0)) as control, 
	concat(ifnull(r.residual_target_kemungkinan,0),ifnull(r.residual_target_dampak,0)) as risidual, 
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
			} else {
				$str_condition1 .= " and s.id_scorecard in ({$this->scorecardstr})";
			}
		}
		// if ($arr_params['tipe'] == 2) {

		// 	$sql = "select
		// 		{$str_field}, m.nama as nama_mitigasi, m.id_mitigasi, m.status_konfirmasi
		// 		from
		// 		" . $this->table . " r
		// 		left join opp_scorecard s on r.id_scorecard = s.id_scorecard
		// 		join opp_mitigasi m on r.id_peluang = m.id_peluang and m.is_control <> '1'
		// 		where s.owner <> m.penanggung_jawab and status_peluang = '1' $str_condition1";
		// } else

		if ($arr_params['tipe'] == 1) {

			$sql = "select
				{$str_field}
				from
				" . $this->table . " r
				left join opp_scorecard s on r.id_scorecard = s.id_scorecard
				where 1=1 and status_peluang = '1' $str_condition1";
		} else {
			$sql = "select
				{$str_field}
				from
				" . $this->table . " r
				left join opp_scorecard s on r.id_scorecard = s.id_scorecard
				where 1=1 and status_peluang = '1' and s.id_status_pengajuan = 3 $str_condition1";
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
		// if (!$this->ci->access_role['view_all']) {
		// 	$id_jabatan = $_SESSION[SESSION_APP]['id_jabatan'];
		// 	$where .= " and (r.id_scorecard in ({$this->scorecardstr}) or exists(select 1 from opp_mitigasi rm where r.id_peluang = rm.id_peluang and (penanggung_jawab = " . $this->conn->escape($id_jabatan) . " or penanggung_jawab = " . $this->conn->escape($_SESSION[SESSION_APP]['owner_jabatan']) . " or interdependent_delegasi = " . $this->conn->escape($id_jabatan) . ")))";
		// }

		$sql = "select * from " . $this->table . " r where r.deleted_date is null and {$this->pk} = " . $this->conn->qstr($id) . $where;
		$ret = $this->conn->GetRow($sql);

		if (!$ret)
			$ret = array();

		$ret['is_finish'] = $this->CekFinish($id);
		$ret['id_unit'] = $this->conn->GetOne("select id_unit from opp_scorecard where deleted_date is null and id_scorecard = " . $this->conn->escape($ret['id_scorecard']));

		if ($ret['id_peluang_sebelum'])
			$ret['peluang_old'] = $this->conn->GetRow("select hambatan_kendala from opp_peluang where deleted_date is null and id_peluang = " . $this->conn->escape($ret['id_peluang_sebelum']));

		return $ret;
	}

	public function getListKertasKerja($param = array(), &$wherearr = array())
	{
		if ($wherearr)
			list($where, $id_periode_tw, $tahun) = $wherearr;
		else
			list($where, $id_periode_tw, $tahun) = $this->getWhere($param);


		$sql = "
		select 
		rr.sasaran,
		rr.anggaran_biaya,
		rr.target_penyelesaian,
		rr.deskripsi,
		rr.is_kerangka_acuan_kerja,
		rs.nama as scorecard, rr.id_peluang, rr.status_peluang, 
		rr.nomor as kode_peluang, 
		rt.nama as taksonomi, 
		rr.nama as peluang, rr.penyebab as penyebab, 
		rr.dampak as dampak, mdj.nama as opp_owner, 
		rr.inheren_kemungkinan as inheren_kemungkinan1, 
		rr.inheren_dampak as inheren_dampak1, mrki.kode as inheren_kemungkinan, 
		rr.id_kriteria_kemungkinan as kategori_kemungkinan, 
		mrdi.kode as inheren_dampak, mrkd.nama as kategori_dampak, 
		(mrki.id_kemungkinan*mrdi.id_dampak) as level_peluang_inheren, 
		rr.progress_capaian_kinerja as capaian_mitigasi_evaluasi, rr.hambatan_kendala as hambatan_kendala, 
		rr.penyesuaian_tindakan_mitigasi as penyesuaian_mitigasi, concat(rr.kode_aktifitas,' ',rr.nama_aktifitas) as aktifitas, 
		pk.nama as alur_proses_bisnis, rr.sub_tahapan_kegiatan, /*msj1.nama as pejabat_bepeluang,*/ rr.skor_inheren_kemungkinan, 
		rr.skor_inheren_dampak, rr.skor_control_kemungkinan, rr.skor_control_dampak, rr.skor_target_kemungkinan, 
		rr.skor_target_dampak 
		from opp_peluang rr 
		left join opp_scorecard rs on rr.id_scorecard = rs.id_scorecard 
		left join mt_pb_kategori pk on rr.id_kategori = pk.id_kategori 
		left join mt_sdm_jabatan mdj on trim(mdj.id_jabatan) = trim(rs.owner)  
		left join mt_opp_kemungkinan mrki on mrki.id_kemungkinan = rr.inheren_kemungkinan 
		left join mt_opp_dampak mrdi on mrdi.id_dampak = rr.inheren_dampak 
		left join mt_opp_kriteria_dampak mrkd on mrkd.id_kriteria_dampak = rr.id_kriteria_dampak 
		left join mt_risk_taksonomi rt on rt.id_taksonomi = rr.id_taksonomi 
		";

		if ($param['jenis'] && $param['tingkat']) {
			switch ($param['jenis']) {
				case '1':
					$sql .= "join mt_opp_matrix mx 
					on mx.id_dampak = rr.inheren_dampak 
					and mx.id_kemungkinan = rr.inheren_kemungkinan
					and mx.id_tingkat = " . $this->conn->escape($param['tingkat']);
					break;
				case '2':
					$sql .= "join mt_opp_matrix mx 
					on mx.id_dampak = rr.control_dampak_penurunan 
					and mx.id_kemungkinan = rr.control_kemungkinan_penurunan
					and mx.id_tingkat = " . $this->conn->escape($param['tingkat']);
					break;
				case '3':
					$sql .= "join mt_opp_matrix mx 
					on mx.id_dampak = rr.residual_target_dampak 
					and mx.id_kemungkinan = rr.residual_target_kemungkinan
					and mx.id_tingkat = " . $this->conn->escape($param['tingkat']);
					break;
			}
		}

		$sql .= " where  rr.deleted_date is null " . $where;
		$sql .= " order by rs.owner, rr.id_peluang";

		$ret = $this->conn->GetRows($sql);

		if (!$ret)
			$ret = array();

		foreach ($ret as &$r) {
			$rows = $this->conn->GetArray("select * from 
			opp_peluang_kelayakan 
			where deleted_date is null and id_peluang = " . $this->conn->escape($r['id_peluang']));
			foreach ($rows as $r1) {
				$r["layak_" . $r1['id_kelayakan']] = 1;
			}
		}

		return $ret;
	}

	private function _getChildKpi($id_kpi)
	{
		$arr = [];
		$arr[] = $id_kpi;

		$rows = $this->conn->GetArray("select * from kpi where deleted_date is null and id_parent = " . $this->conn->escape($id_kpi));
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

	public function getWhere($param = array())
	{

		$bulan = $param['bulan'];
		$tahun = $param['tahun'];
		$tahun_input = $param['tahun_input'];
		$bulan_input = $param['bulan_input'];
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

		// $param['bulan'] = $bulan;
		// $param['tahun'] = $tahun;
		// $param['tahun_input'] = $tahun_input;
		// $param['bulan_input'] = $bulan_input;
		$wherearr1 = array();
		$wherearr = array();

		$wherearr1[] = " rr.is_lock = '1'";
		$wherearr1[] = "rr.status_peluang in ('1','0')";
		if (!$this->ci->access_role['view_all'] && $this->scorecardstr)
			$wherearr1[] = " rr.id_scorecard in ({$this->scorecardstr})";

		if (!$param['all']) {
			if (!$param['id_scorecard'])
				$param['id_scorecard'] = array(0);

			// if ($param['id_kajian_peluang']) {
			$this->conn->escape_string($param['id_scorecard']);
			$wherearr[] = "rs.id_scorecard in ('" . implode("','", $param['id_scorecard']) . "')";
			// }
		} elseif ($param['id_scorecard'])
			$wherearr[] = "rs.id_scorecard = " . $this->conn->escape($param['id_scorecard']);

		// if ($param['id_kajian_peluang'])
		// 	$wherearr[] = "rs.id_kajian_peluang = " . $this->conn->escape($param['id_kajian_peluang']);

		// if ($param['id_tingkat_agregasi_peluang'])
		// 	$wherearr[] = "rs.id_tingkat_agregasi_peluang = " . $this->conn->escape($param['id_tingkat_agregasi_peluang']);

		if ($param['id_taksonomi_objective'])
			$wherearr1[] = "exists (select 1 from mt_risk_taksonomi_area mrt join mt_risk_taksonomi_objective mrta on mrt.id_taksonomi_objective = mrta.id_taksonomi_objective where mrt.deleted_date is null and rr.id_taksonomi_area = mrt.id_taksonomi_area and mrta.id_taksonomi_objective = " . $this->conn->escape($param['id_taksonomi_objective']) . ")";


		if ($param['bulan'] && $param['tanggal']) {
			$tgl1 = $param['tahun'] . $param['bulan'] . $param['tanggal'];
			$wherearr1[] = "DATE_FORMAT(ifnull(rr.tgl_peluang,str_to_date('$tgl1','%Y%m%d')),'%Y%m%d') <= " . $this->conn->escape($tgl1) . " and DATE_FORMAT(ifnull(rr.tgl_close,str_to_date('$tgl1','%Y%m%d')),'%Y%m%d') >= " . $this->conn->escape($tgl1);
		} elseif ($param['bulan']) {
			$blnthn = $param['tahun'] . $param['bulan'];
			$wherearr1[] = "DATE_FORMAT(ifnull(rr.tgl_peluang,str_to_date('$blnthn" . "01" . "','%Y%m%d')),'%Y%m') <= " . $this->conn->escape($blnthn) . " and DATE_FORMAT(ifnull(rr.tgl_close,str_to_date('$blnthn" . "01" . "','%Y%m%d')),'%Y%m') >= " . $this->conn->escape($blnthn);
		} else if ($param['tahun']) {
			$thn1 = $param['tahun'];
			$wherearr1[] = "DATE_FORMAT(ifnull(rr.tgl_peluang,str_to_date('$thn1" . "01" . "" . "01" . "','%Y%m%d')),'%Y') <= " . $this->conn->escape($param['tahun']) . " and DATE_FORMAT(ifnull(rr.tgl_close,str_to_date('$thn1" . "01" . "" . "01" . "','%Y%m%d')),'%Y') >= " . $this->conn->escape($param['tahun']);
		}

		if ($param['bulan_input'] && $param['tanggal_input']) {
			$tgl1 = $param['tahun_input'] . $param['bulan_input'] . $param['tanggal'];
			$wherearr1[] = "DATE_FORMAT(ifnull(rr.tgl_peluang,str_to_date('$tgl1','%Y%m%d')),'%Y%m%d') = " . $this->conn->escape($tgl1);
		} elseif ($param['bulan_input']) {
			$blnthn = $param['tahun_input'] . $param['bulan_input'];
			$wherearr1[] = "DATE_FORMAT(ifnull(rr.tgl_peluang,str_to_date('$blnthn" . "01" . "','%Y%m%d')),'%Y%m') = " . $this->conn->escape($blnthn);
		} else if ($param['tahun_input']) {
			$thn1 = $param['tahun_input'];
			$wherearr1[] = "DATE_FORMAT(ifnull(rr.tgl_peluang,str_to_date('$thn1" . "01" . "" . "01" . "','%Y%m%d')),'%Y') = " . $this->conn->escape($thn1);
		}

		if (!$param['tahun'] && !$param['bulan'] && !$param['tanggal'] && !$param['tahun_input'] && !$param['bulan_input'] && !$param['tanggal_input']) {
			$wherearr1[] = "rr.status_peluang = '1'";
		}

		$id_periode_tw = $this->conn->GetOne("select id_periode_tw from mt_periode_tw where deleted_date is null and '{$bulan}' between bulan_mulai and bulan_akhir");

		if ($param['id_sasaran'])
			$wherearr1[] = "rr.id_sasaran = " . $this->conn->escape($param['id_sasaran']);

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
				// from opp_scorecard rs
				// where " . implode(" and ", $wherearr));

				// if ($list_str_scr)
				// 	$wherearr1[] = " rr.id_scorecard in ($list_str_scr) ";
				$wherearr1[] = " exists (select 1 from (select rs.id_scorecard from opp_scorecard rs where rs.deleted_date is null and " . implode(" and ", $wherearr) . ") a where rr.id_scorecard = a.id_scorecard) ";
			}

			// $sqlstr1 = implode(" union ", $this->conn->GetList("select 
			// ' select '|| max(id_peluang) || ' as id_peluang from dual ' as val
			// from opp_peluang rr
			// join opp_scorecard rs on rr.id_scorecard = rs.id_scorecard
			// where " . implode(" and ", array_merge($wherearr, $wherearr1)) . "
			// group by nomor_asli, rr.id_scorecard"));

			// if ($sqlstr1)
			// 	$wherearr[] = " exists (select 1 from ($sqlstr1) rsub where rsub.id_peluang = rr.id_peluang) ";
			// else
			// 	$wherearr[] = "1<>1";
			// $wherearr[] = "exists (select 1 from (select 
			// max(id_peluang) id_peluang
			// from opp_peluang rr
			// join opp_scorecard rs on rr.id_scorecard = rs.id_scorecard
			// where " . implode(" and ", array_merge($wherearr, $wherearr1)) . "
			// group by nomor_asli, rr.id_scorecard) rsub where rsub.id_peluang = rr.id_peluang)";
		}
		/**
		 * select * from 
			opp_peluang_kelayakan 
			where id_peluang = 
		 */
		if ($param['id_kelayakan']) {
			$wherearr[] = " exists (select 1 
			from opp_peluang_kelayakan opk 
			where rr.deleted_date is null and rr.id_peluang = opk.id_peluang 
			and opk.id_kelayakan in (" . (implode(",", $param['id_kelayakan'])) . "))";
		}

		$wherearr = array_merge($wherearr, $wherearr1);
		$where = "";
		if (($wherearr))
			$where .= " and " . implode(" and ", $wherearr);

		// dpr($where,1);
		return array($where, $id_periode_tw, $tahun);
	}

	public function getListOppProfile($param = array(), &$wherearr = array())
	{
		if ($wherearr)
			list($where, $id_periode_tw, $tahun) = $wherearr;
		else
			list($where, $id_periode_tw, $tahun) = $this->getWhere($param);

		$filarr['i'] = "mrdi.rating is not null and mrki.rating is not null";
		$filarr['c'] = "mrdc.rating is not null and mrkc.rating is not null";
		$filarr['a'] = "mrda.rating is not null and mrka.rating is not null";
		$filarr['r'] = "mrdr.rating is not null and mrkr.rating is not null";

		#pengurutan tingkat matrix menggunakan perkalian
		// $arr['i'] = "mrdi.rating * mrki.rating desc NULLS LAST";
		// $arr['c'] = "mrdc.rating * mrkc.rating desc NULLS LAST";
		// $arr['a'] = "mrda.rating * mrka.rating desc NULLS LAST";
		// $arr['r'] = "mrdr.rating * mrkr.rating desc NULLS LAST";

		$arr['i'] = "mrdi.rating * mrki.rating desc, mrdi.rating desc, mrki.rating desc";
		$arr['c'] = "mrdc.rating * mrkc.rating desc, mrdc.rating desc, mrkc.rating desc";
		$arr['a'] = "mrda.rating * mrka.rating desc, mrda.rating desc, mrka.rating desc";
		$arr['r'] = "mrdr.rating * mrkr.rating desc, mrdr.rating desc, mrkr.rating desc";

		$sql = "select
			rr.*,
			ifnull(rr.residual_kemungkinan_evaluasi, rr.control_kemungkinan_penurunan) as actual_kemungkinan,
			ifnull(rr.residual_dampak_evaluasi,rr.control_dampak_penurunan) as actual_dampak,
			concat(mrki.kode , mrdi.kode) as level_peluang_inheren,
			concat(mrkc.kode , mrdc.kode) as level_peluang_control,
			concat(mrka.kode , mrda.kode) as level_peluang_actual,
			concat(mrkr.kode , mrdr.kode) as level_residual_evaluasi,
			msj.nama as opp_owner,
			msu.table_desc as unit,
			kpi.nama as kpi
			from opp_peluang rr
			left join opp_scorecard rs on rr.id_scorecard = rs.id_scorecard
			left join mt_opp_kemungkinan mrki on mrki.id_kemungkinan = rr.inheren_kemungkinan
			left join mt_opp_dampak mrdi on mrdi.id_dampak = rr.inheren_dampak
			left join mt_opp_kemungkinan mrkc on mrkc.id_kemungkinan = rr.control_kemungkinan_penurunan
			left join mt_opp_kemungkinan mrka on mrka.id_kemungkinan = ifnull(rr.residual_kemungkinan_evaluasi, rr.control_kemungkinan_penurunan)
			left join mt_opp_dampak mrda on mrda.id_dampak = ifnull(rr.residual_dampak_evaluasi, rr.control_dampak_penurunan)
			left join mt_opp_dampak mrdc on mrdc.id_dampak = rr.control_dampak_penurunan
			left join mt_opp_kemungkinan mrkr on mrkr.id_kemungkinan = rr.residual_target_kemungkinan
			left join mt_opp_dampak mrdr on mrdr.id_dampak = rr.residual_target_dampak
			left join mt_sdm_jabatan msj on msj.id_jabatan = rs.owner 
			left join mt_sdm_unit msu on msj.id_unit = msu.table_code 
			left join kpi on rr.id_kpi = kpi.id_kpi ";

		$sql .= " where  1=1 " . $where;

		if (!$param['top'])
			$param['top'] = 10;


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

		if (!($orderrating))
			return array();


		$sql .= " order by " . implode(",", $orderrating) . ", ifnull(rr.urutan,0) asc";

		$sql = "select * from($sql) a limit " . (int)$param['top'];

		$ret = $this->conn->GetRows($sql);

		if (!$ret)
			$ret = array();

		return $ret;
	}

	public function getCountAll($param = array(), &$wherearr = array())
	{
		if ($wherearr)
			list($where, $id_periode_tw, $tahun) = $wherearr;
		else
			list($where, $id_periode_tw, $tahun) = $this->getWhere($param);

		$tikatarr = $this->conn->GetList("select id_tingkat as idkey, nama as val from mt_opp_tingkat where deleted_date is null order by idkey desc");

		$sql = "select count(rr.id_peluang) as val, m.id_tingkat as idkey
		from opp_peluang rr
		left join opp_scorecard rs on rr.id_scorecard = rs.id_scorecard
		join mt_opp_matrix m on rr.inheren_kemungkinan = m.id_kemungkinan and rr.inheren_dampak = m.id_dampak 
		where 1=1 and rr.deleted_date is null  " . $where . "
		group by m.id_tingkat";

		$rows = $this->conn->GetList($sql);
		$ret = array();
		$ret['total_inheren'] = 0;
		foreach ($tikatarr as $k => $v) {
			$ret['inheren'][$v] = (int)$rows[$k];
			$ret['total_inheren'] += $rows[$k];
		}

		// $sql = "select count(rr.id_peluang) as val, m.id_tingkat as idkey
		// from opp_peluang rr
		// left join opp_scorecard rs on rr.id_scorecard = rs.id_scorecard
		// join mt_opp_matrix m on rr.control_kemungkinan_penurunan = m.id_kemungkinan and rr.control_dampak_penurunan = m.id_dampak 
		// where 1=1 " . $where . "
		// group by m.id_tingkat";

		// $rows = $this->conn->GetList($sql);
		// $ret['total_control'] = 0;
		// foreach ($tikatarr as $k => $v) {
		// 	$ret['control'][$v] = (int)$rows[$k];
		// 	$ret['total_control'] += $rows[$k];
		// }

		// $sql = "select count(rr.id_peluang) as val, m.id_tingkat as idkey
		// from opp_peluang rr
		// left join opp_scorecard rs on rr.id_scorecard = rs.id_scorecard
		// join mt_opp_matrix m on ifnull(rr.residual_kemungkinan_evaluasi,rr.control_kemungkinan_penurunan) = m.id_kemungkinan and ifnull(rr.residual_dampak_evaluasi,rr.control_dampak_penurunan) = m.id_dampak 
		// where 1=1 " . $where . "
		// group by m.id_tingkat";

		// $rows = $this->conn->GetList($sql);
		// $ret['total_actual'] = 0;
		// foreach ($tikatarr as $k => $v) {
		// 	$ret['actual'][$v] = (int)$rows[$k];
		// 	$ret['total_actual'] += $rows[$k];
		// }

		// $sql = "select count(rr.id_peluang) as val, m.id_tingkat as idkey
		// from opp_peluang rr
		// left join opp_scorecard rs on rr.id_scorecard = rs.id_scorecard
		// join mt_opp_matrix m on rr.residual_target_kemungkinan = m.id_kemungkinan and rr.residual_target_dampak = m.id_dampak 
		// where 1=1 " . $where . "
		// group by m.id_tingkat";

		// $rows = $this->conn->GetList($sql);
		// $ret['total_residual'] = 0;
		// foreach ($tikatarr as $k => $v) {
		// 	$ret['residual'][$v] = (int)$rows[$k];
		// 	$ret['total_residual'] += $rows[$k];
		// }


		$sql = "select count(rr.id_peluang) as jumlah, kpi.nama as kpi
		from opp_peluang rr
		left join opp_scorecard rs on rr.id_scorecard = rs.id_scorecard
		left join kpi on rr.id_kpi = kpi.id_kpi
		where 1=1 and rr.deleted_date is null  " . $where . "
		group by rr.id_kpi";

		$rows = $this->conn->GetArray($sql);
		foreach ($rows as $r) {
			$ret['jkpi'][] = $r['jumlah'];
			$ret['nkpi'][] = $r['kpi'];
		}

		return $ret;
	}

	private function CekFinish($id)
	{

		return (int)$this->conn->GetOne("
			select s.open_evaluasi
			from opp_scorecard s
			join opp_peluang r on s.id_scorecard = r.id_scorecard
			where s.deleted_date is null and id_peluang = " . $this->conn->escape($id) . "
		");
		/*$open_evaluasi = $this->config->item('open_evaluasi');

		if($open_evaluasi)
			return 1;

		return 0;*/

		/*$cek = $this->conn->GetOne("
			select count(1) 
			from opp_mitigasi r
			join mt_status_progress p on r.id_status_progress = p.id_status_progress
			where id_peluang = ".$this->conn->escape($id)
		);

		if(!$cek)
			return 0;

		return !$this->conn->GetOne("
			select count(1) 
			from opp_mitigasi r
			join mt_status_progress p on r.id_status_progress = p.id_status_progress
			where id_peluang = ".$this->conn->escape($id)."
			and p.prosentase <>  100"
		);*/
	}

	private function _getKodeKPI($id_kpi = null)
	{
		$row = $this->conn->GetRow("select * from kpi where deleted_date is null and id_kpi = " . $this->conn->escape($id_kpi));
		if ($row['kode'])
			return $row['kode'];
		else if ($row['id_parent'])
			return $this->_getKodeKPI($row['id_parent']);
	}

	//for membuat no peluang otomatis berdasarkan kajian peluang
	public function getNomorPeluang($id_unit = null, $id_kpi = null, $tgl_peluang = null, $isformat = false)
	{
		if (!$tgl_peluang)
			$tgl_peluang = date("Y-m-d");

		/*
		2.04/AP/01/2020/001
		2.04 : Unit Kerja
		AP : analisis peluang
		01 : KPI
		2020 : tahun
		001 : nomor urut
		*/

		$format = $id_unit;
		$format .= '/AP';
		$format .= '/' . $this->_getKodeKPI($id_kpi);
		$format .= '/' . substr($tgl_peluang, 0, 4) . '/';

		if ($isformat)
			return $format;

		$autoincrement = "select max(ifnull(nomor_asli, nomor)) as nomor from opp_peluang where deleted_date is null and ifnull(nomor_asli, nomor) like '$format%'";

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

	// get combo untuk kajian peluang operasional
	function GetComboDashboard($id_kajian_peluang = null, $tgl_efektif = null, $id_tingkat_agregasi_peluang = null)
	{
		if (!$tgl_efektif)
			$tgl_efektif = date("Y-m-d");

		if ($_SESSION[SESSION_APP]['tgl_efektif']) {
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		}

		if ($tgl_efektif)
			$filter = " and '$tgl_efektif' between ifnull(tgl_mulai_efektif,'$tgl_efektif')and ifnull(tgl_akhir_efektif,'$tgl_efektif')";

		if ($this->scorecardstr)
			$filter .= " and id_scorecard in ({$this->scorecardstr})";

		if ($id_tingkat_agregasi_peluang)
			$filter .= " and id_tingkat_agregasi_peluang = " . $this->conn->escape($id_tingkat_agregasi_peluang);

		// if ($id_kajian_peluang == 'semua')
		$sql = "select id_scorecard, nama from opp_scorecard where deleted_date is null and navigasi = 0 " . $filter . " order by id_scorecard";
		// else
		// 	$sql = "select id_scorecard, nama from opp_scorecard where navigasi = 0 and id_kajian_peluang = " . $this->conn->escape($id_kajian_peluang) . $filter . " order by id_scorecard";

		$rows = $this->conn->GetArray($sql);

		$data = array('' => '-Sub kategori-');
		foreach ($rows as $r) {
			$data[$r['id_scorecard']] = $r['nama'];
		}
		return $data;
	}
}
