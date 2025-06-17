<?php class Penilaian_periodeModel extends _Model
{
	public $table = "penilaian_periode";
	public $pk = "id_penilaian_periode";
	public $label = "nama";
	function __construct()
	{
		parent::__construct();
	}

	public function get_kriteria_parent($id_kriteria, $id_parent)
	{
		if (!$id_kriteria || $id_kriteria == $id_parent)
			return array();

		$ret1 = $this->conn->GetRow("select * from mt_kriteria where id_kriteria = " . $this->conn->escape($id_kriteria) . " order by id_kriteria asc");

		$ret2 = $this->get_kriteria_parent($ret1['id_kriteria_parent'], $id_parent);

		$ret = array();
		if ($ret2)
			foreach ($ret2 as $r1)
				$ret[$r1['id_kriteria']] = $r1;

		$ret[$ret1['id_kriteria']] = $ret1;

		return $ret;
	}

	private function _loopKriteria($arr, $id_parent, $level = 0, $bobot = 0)
	{
		$level++;
		$ret = [];
		// dpr($arr_sebelum, 1);

		if ($arr[$id_parent])
			foreach ($arr[$id_parent] as $r) {
				if ($bobot && !$r['bobot'])
					$r['bobot'] = $bobot / count($arr[$id_parent]);

				if ($arr[$r['id_kriteria']]) {
					$r['sub' . $level] = $this->_loopKriteria($arr, $r['id_kriteria'], $level, $r['bobot']);
				}
				$rowspan = 0;
				if ($r['sub' . $level])
					foreach ($r['sub' . $level] as $rs)
						$rowspan = $rowspan + $rs['rowspan'];
				else
					$rowspan++;

				$r['rowspan'] = $rowspan;


				#penilaian

				$row = $this->conn->GetRow("select avg(skor) skor,  
			GROUP_CONCAT(simpulan) kesimpulan 
			from penilaian_detail a 
			where exists (select 1 from penilaian b 
			where a.id_penilaian = b.id_penilaian 
			and b.id_penilaian_periode = " . $this->conn->escape($r['id_penilaian_periode']) . ") 
			and tgl is not null");

				$r['skor'] = $row['skor'];
				$r['kesimpulan'] = $row['kesimpulan'];

				$ret[] = $r;
			}
		// dpr($ret, 1);
		return $ret;
	}

	public function get_kriteria_penilaian(
		$id_parent = null,
		$id_kategori = null,
		$id_unit = null,
		$tgl_penilaian = null,
		$id_penilaian_session = null,
		$target_level = null,
		$id_penilaian_session_sebelumnya = null
	) {

		$rows = $this->conn->GetArray("select k.id_kriteria as id_kriteria_link, k1.* 
			from mt_kriteria k
			join kriteria_link kl on k.id_kriteria = kl.id_kriteria1
			join mt_kriteria k1 on kl.id_kriteria2 = k1.id_kriteria
			where k.is_upload = 1 and k.id_kategori = " . $this->conn->escape($id_kategori) . " 
			order by 
			case when k.kode REGEXP '^[0-9]+$' then cast(k.kode AS UNSIGNED) else null end, 
			case when k.kode REGEXP '^[0-9]+$' then null else k.kode end,
			k.id_kriteria asc");


		$rowslink = array();
		foreach ($rows as $r) {

			// $r['is_attr'] = $this->conn->GetOne("select 1 from mt_kriteria_attribute where id_kriteria = " . $this->conn->escape($r['id_kriteria']));

			$rowslink[$r['id_kriteria_link']][] = $r;
		}

		if (!Access("edit", "panelbackend/mt_kriteria")) {
			$where = " and pp.is_aktif = 1 ";
		}

		// if (strlen($tgl_penilaian) == 10)
		// 	$where .= " AND DATE_FORMAT(pp.tgl_penilaian,'%Y-%m-%d')=" . $this->conn->escape($tgl_penilaian);

		// if ($id_unit) {
		// 	$where .= " and k.id_unit = " . $this->conn->escape($id_unit);
		// }

		if ($target_level)
			$where .= " and k.kode_lvl = " . $this->conn->escape($target_level);

		// $this->conn->debug = 1;
		$rows1 = $this->conn->GetArray("select k.*, pp.id_penilaian_periode, pp.is_aktif as is_aktif_penilaian, pp.nilai_target, kg.id_kategori_jenis
		from penilaian_periode pp
		left join mt_kriteria k on k.id_kriteria = pp.id_kriteria
		left join mt_kriteria kk on kk.id_kriteria = k.id_kriteria_parent
		left join mt_kategori kg on k.id_kategori = kg.id_kategori
		where 1=1
		$where
		and id_penilaian_session = " . $this->conn->escape($id_penilaian_session) . "
		and k.id_kategori = " . $this->conn->escape($id_kategori) . "
		ORDER BY 
		case when kk.kode REGEXP '^[0-9]+$' then cast(kk.kode AS UNSIGNED) else null end, 
		case when k.kode REGEXP '^[0-9]+$' then null else k.kode end,
		k.id_kriteria asc");

		// dpr($rows1, 1);

		if ($id_penilaian_session_sebelumnya) {
			// dpr('test', 1);
			$rowssebelum1 = $this->conn->GetArray("
			SELECT pp.id_kriteria , pd.skor, pd.jenis FROM penilaian_session ps LEFT JOIN 
			penilaian_periode pp ON ps.id_penilaian_session = pp.id_penilaian_session 
			AND ps.id_penilaian_session = " . $this->conn->escape($this->data['penilaiansessionsebelumnya']) . " 
			LEFT JOIN penilaian p ON pp.id_penilaian_periode = p.id_penilaian_periode 
			LEFT JOIN penilaian_detail pd ON p.id_penilaian = pd.id_penilaian where pp.id_kriteria is not null");
		}

		$skor_sementara = array();
		if ($rowssebelum1) {
			foreach ($rows1 as $key => $r) {
				foreach ($rowssebelum1 as $r_sebelum) {
					if ($r['id_kriteria'] == $r_sebelum['id_kriteria']) {
						$rows1[$key]['skor_sebelumnya'][$r_sebelum['jenis']] = $r_sebelum['skor'];
						if ($r_sebelum['skor']) {
							$skor_sementara[$key] += $r_sebelum['skor'];
						}
						if ($skor_sementara[$key]) {
							$rows1[$key]['skor_sebelumnya']['skor_sebelumnya'] = $skor_sementara[$key] / 4;
						}
					}
				}
			}
		}
		// dpr($rows1);
		$id_kategori_jenis = $rows1[0]['id_kategori_jenis'];

		$rows = array();
		foreach ($rows1 as $r) {
			$rows2 = $this->get_kriteria_parent($r['id_kriteria_parent'], $id_parent);
			// dpr($rows1, 1);


			$rows[$r['id_kriteria']] = $r;

			if ($rows2)
				foreach ($rows2 as $r1) {
					if (!$rows[$r1['id_kriteria']])
						$rows[$r1['id_kriteria']] = $r1;
				}

			// $r['is_attr'] = $this->conn->GetOne("select 1 from mt_kriteria_attribute where id_kriteria = " . $this->conn->escape($r['id_kriteria']));

		}
		// dpr($rows, 1);


		$a = array();
		foreach ($rows as $dokumen) {
			$a[$dokumen['id_kriteria_parent']][] = $dokumen;
			// sort($a[$dokumen['id_kriteria_parent']]);
		}

		#loop area / kriteria
		$arearr = $this->_loopKriteria($a, $id_parent, 0, 0);
		return $arearr;
	}

	public function get_penilaian_files($id_kategori = null, $tahun = null, $bulan = null, $unit = null, $status = null, $periode = null)
	{

		if (!Access("edit", "panelbackend/mt_kriteria")) {
			$where = " and p.is_aktif = 1 ";
		}

		if ($status !== '' && $status !== null)
			$where .= " and p.status = " . $status;

		if ($periode)
			$where .= " and p.id_periode = " . $periode;

		if (strlen($bulan) == 10) {
			// $where1 = " and to_char(p1.tgl,'%Y-%m-%d')=".$this->conn->escape($bulan);
			$where .= " and to_char(p.tgl,'%Y-%m-%d')=" . $this->conn->escape($bulan);
		}

		$rows = $this->conn->GetArray("
		select pf.*, p1.id_penilaian as id_penilaian_link, pf.id_penilaian_detail
		from penilaian p1
		join mt_kriteria k on p1.id_kriteria = k.id_kriteria
		join kriteria_link1 kl on k.id_kriteria = kl.id_kriteria1
		join penilaian p on kl.id_kriteria2 = p.id_kriteria and p.tgl = p1.tgl
		join penilaian_files pf on p.id_penilaian = pf.id_penilaian
		where 
		pf.id_penilaian_detail is null
		$where
		and k.id_kategori = " . $this->conn->escape($id_kategori));

		return $rows;
	}

	public function getOai($tgl_penilaian, $id_penilaian_session, $id_kategori_jenis)
	{
		list($tahun) = explode("-", $tgl_penilaian);
		// $rows = $this->conn->GetArray("select * from penilaian_detail a 
		// where exists (select 1 from penilaian_dokumen b where a.id_penilaian = b.id_penilaian 
		// and b.id_dokumen_versi = " . $this->conn->escape($r1['id_dokumen_versi']) . ")");

		$rows = $this->conn->GetArray("select c.id_kriteria, g.nama as pic, a.simpulan as oai, a.saran, e.id_dokumen, f.nama as nama_dokumen 
		from penilaian_detail a 
		join penilaian b on a.id_penilaian = b.id_penilaian 
		join penilaian_periode c on b.id_penilaian_periode = c.id_penilaian_periode
		left join penilaian_dokumen d on a.id_penilaian = d.id_penilaian 
		left join dokumen_files e on d.id_dokumen_versi = e.id_dokumen_versi
		left join dokumen f on e.id_dokumen = f.id_dokumen
		left join mt_sdm_jabatan g on f.id_jabatan = g.id_jabatan
		WHERE id_penilaian_session = " . $this->conn->escape($id_penilaian_session) . " 
		and a.simpulan is not null and a.simpulan <> '' /*and b.tgl_label = " . $this->conn->escape($tahun) . "*/");

		foreach ($rows as &$r) {
			$rrws = $this->conn->GetArray("select * 
			from penilaian_quisioner a 
			where exists (select 1 from quisioner_kriteria b 
			where a.id_quisioner = b.id_quisioner 
			and b.id_kriteria = " . $this->conn->escape($r['id_kriteria']) . ") 
			and a.id_penilaian_session = " . $this->conn->escape($id_penilaian_session));
			$rws = [];
			foreach ($rrws as $r1) {
				if ($r1['jenis_jawaban'] == 'uraian') {
					$rws['w'][$r1['id_quisioner']]['pertanyaan'] = $r1['pertanyaan'];
					$rws['w'][$r1['id_quisioner']]['jawaban'][] = $r1['jawaban'];
				} else {
					$rws['k'][$r1['id_quisioner']]['pertanyaan'] = $r1['pertanyaan'];
					$rws['k'][$r1['id_quisioner']][$r1['jenis_jawaban']][$r1['nilai']]++;
					$rws['k'][$r1['id_quisioner']]['total']++;
				}
			}
			$r['quisioner'] = $rws;
		}

		return $rows;
	}


	public function getKesimpulanLevel($tgl_penilaian, $id_penilaian_session, $id_kategori_jenis, $rows = [])
	{
		if (!$rows)
			$rows = $this->getKesimpulan($tgl_penilaian, $id_penilaian_session, $id_kategori_jenis);

		$ret = [];
		foreach ($rows as $r) {
			$level = 1;
			if ($r['yst2'] == $r['level2'] && $level == 1)
				$level = 2;

			if ($r['yst3'] == $r['level3'] && $level == 2)
				$level = 3;

			if ($r['yst4'] == $r['level4'] && $level == 3)
				$level = 4;

			if ($r['yst5'] == $r['level5'] && $level == 4)
				$level = 5;

			$r['level'] = $level;
			$ret[] = $r;
		}

		return $ret;
	}

	public function getKesimpulan($tgl_penilaian, $id_penilaian_session, $id_kategori_jenis)
	{
		list($tahun) = explode("-", $tgl_penilaian);
		if ($id_kategori_jenis == 2) {
			$rows = $this->conn->GetArray("SELECT f.id_kriteria,f.nama, b.kode_lvl, a.id_penilaian_periode
			FROM penilaian_periode a
			JOIN mt_kriteria b ON a.id_kriteria = b.id_kriteria
			JOIN mt_kriteria c ON b.id_kriteria_parent = c.id_kriteria
			JOIN mt_kriteria d ON c.id_kriteria_parent = d.id_kriteria
			JOIN mt_kriteria e ON d.id_kriteria_parent = e.id_kriteria
			JOIN mt_kriteria f ON e.id_kriteria_parent = f.id_kriteria
			WHERE /*tgl_penilaian = " . $this->conn->escape($tgl_penilaian) . " and*/ a.deleted_date is null and id_penilaian_session = " . $this->conn->escape($id_penilaian_session));
		}
		if ($id_kategori_jenis == 3) {
			$rows = $this->conn->GetArray("SELECT e.id_kriteria,e.nama, b.kode_lvl, a.id_penilaian_periode
			FROM penilaian_periode a
			JOIN mt_kriteria b ON a.id_kriteria = b.id_kriteria
			JOIN mt_kriteria c ON b.id_kriteria_parent = c.id_kriteria
			JOIN mt_kriteria d ON c.id_kriteria_parent = d.id_kriteria
			JOIN mt_kriteria e ON d.id_kriteria_parent = e.id_kriteria
			WHERE /*tgl_penilaian = " . $this->conn->escape($tgl_penilaian) . " and*/ a.deleted_date is null and id_penilaian_session = " . $this->conn->escape($id_penilaian_session));
		}

		$ret = [];
		foreach ($rows as $r) {
			$sql = "select avg(skor) skor from penilaian b
			left join penilaian_detail c on b.id_penilaian = c.id_penilaian and c.tgl is not null
			where  b.id_penilaian_periode = " . $this->conn->escape($r['id_penilaian_periode']) . " 
			and b.status = 3 and skor is not null";
			$skor = $this->conn->GetOne($sql);

			$ret[$r['id_kriteria']]['nama'] = $r['nama'];
			if ($r['kode_lvl'] == '2') {
				$ret[$r['id_kriteria']]['level2']++;
				if ($skor !== "")
					$ret[$r['id_kriteria']]['yst2']++;

				$ret[$r['id_kriteria']]['nilai2'] += (float)$skor;
			}
			if ($r['kode_lvl'] == '3') {
				$ret[$r['id_kriteria']]['level3']++;
				if ($skor !== "")
					$ret[$r['id_kriteria']]['yst3']++;

				$ret[$r['id_kriteria']]['nilai3'] += (float)$skor;
			}
			if ($r['kode_lvl'] == '4') {
				$ret[$r['id_kriteria']]['level4']++;
				if ($skor !== "")
					$ret[$r['id_kriteria']]['yst4']++;

				$ret[$r['id_kriteria']]['nilai4'] += (float)$skor;
			}
			if ($r['kode_lvl'] == '5') {
				$ret[$r['id_kriteria']]['level5']++;
				if ($skor !== "")
					$ret[$r['id_kriteria']]['yst5']++;

				$ret[$r['id_kriteria']]['nilai5'] += (float)$skor;
			}
		}
		return $ret;
	}

	private function table_rekap($id_unit, $tgl_penilaian, $id_penilaian_session)
	{
		$addfilter = "";
		// if ($id_unit) {
		// 	$addfilter = " and b.id_unit = " . $this->conn->escape($id_unit);
		// }
		list($tahun) = explode("-", $tgl_penilaian);
		$sql = "select d.kode as kode_aspek,
		d.id_kriteria as id_kriteria_aspek, 
		d.nama as aspek, 
		c.kode as kode_indikator,
		c.id_kriteria as id_kriteria_indikator, 
		c.nama as indikator, 
		b.kode as kode_paramater,
		b.id_kriteria as id_kriteria_paramater, 
		b.nama as paramater, 
		a.* from (
			select 
			case when c.bobot is not null then c.id_kriteria ELSE d.id_kriteria END id_kriteria, 
			max(case when c.bobot is not null then 
				case when c.bobot < 0 then 0 ELSE c.bobot END 
				ELSE 
				case when d.bobot < 0 then 0 ELSE d.bobot END 
			END) bobot, 
			AVG(skor) AS skor, 
			max(case when c.bobot is not null then c.bobot ELSE d.bobot END) * AVG(skor) AS skor_bobot
			from (
			select a.id_kriteria, avg(ifnull(skor,0)) skor
			from penilaian_periode a 
			left join penilaian b on a.id_penilaian_periode = b.id_penilaian_periode 
			and b.status = 3 /*and b.tgl_label = " . $this->conn->escape($tahun) . "*/
			left join penilaian_detail c on b.id_penilaian = c.id_penilaian and c.tgl is not null 
			where a.is_aktif='1' /*and 
			a.tgl_penilaian = " . $this->conn->escape($tgl_penilaian) . "*/ 
			and id_penilaian_session = " . $this->conn->escape($id_penilaian_session) . "
			group by a.id_kriteria) a
			join mt_kriteria b on a.id_kriteria = b.id_kriteria
			JOIN mt_kriteria c ON b.id_kriteria_parent = c.id_kriteria
			JOIN mt_kriteria d ON c.id_kriteria_parent = d.id_kriteria
			where 1=1 $addfilter  
			GROUP BY case when c.bobot is not null then c.id_kriteria ELSE d.id_kriteria END, 
			case when c.bobot is not null then c.bobot ELSE d.bobot END) a
		join mt_kriteria b on a.id_kriteria = b.id_kriteria
		join mt_kriteria c on b.id_kriteria_parent = c.id_kriteria
		join mt_kriteria d on c.id_kriteria_parent = d.id_kriteria
		
		order by 
		case when d.kode REGEXP '^[0-9]+$' then cast(d.kode AS UNSIGNED) else null end, 
		case when d.kode REGEXP '^[0-9]+$' then null else d.kode end,
		case when c.kode REGEXP '^[0-9]+$' then cast(c.kode AS UNSIGNED) else null end, 
		case when c.kode REGEXP '^[0-9]+$' then null else c.kode end,
		case when b.kode REGEXP '^[0-9]+$' then cast(b.kode AS UNSIGNED) else null end, 
		case when b.kode REGEXP '^[0-9]+$' then null else b.kode end";
		return $sql;
	}

	public function rekapPar($id_unit, $tgl_penilaian, $id_penilaian_session)
	{
		$table = $this->table_rekap($id_unit, $tgl_penilaian, $id_penilaian_session);

		return $this->conn->GetArray($table);
	}

	public function rekapIndikator($id_unit, $tgl_penilaian, $id_penilaian_session)
	{
		$table = $this->table_rekap($id_unit, $tgl_penilaian, $id_penilaian_session);
		$sql = "select 
		a.id_kriteria_aspek, 
		a.kode_aspek,
		a.aspek,
		a.id_kriteria_indikator,
		a.kode_indikator,
		a.indikator,
		count(a.id_kriteria_paramater) as jumlah_paramater,
		sum(bobot) as bobot,
		sum(skor_bobot) as skor_bobot
		from ($table) a group by 
		a.id_kriteria_aspek, 
		a.kode_aspek,
		a.aspek,
		a.id_kriteria_indikator,
		a.kode_indikator,
		a.indikator
		order by
		case when a.kode_aspek REGEXP '^[0-9]+$' then cast(a.kode_aspek AS UNSIGNED) else null end, 
		case when a.kode_aspek REGEXP '^[0-9]+$' then null else a.kode_aspek end,
		case when a.kode_indikator REGEXP '^[0-9]+$' then cast(a.kode_indikator AS UNSIGNED) else null end, 
		case when a.kode_indikator REGEXP '^[0-9]+$' then null else a.kode_indikator end";
		return $this->conn->GetArray($sql);
	}

	public function rekapAspek($id_unit, $tgl_penilaian, $id_penilaian_session)
	{
		$table = $this->table_rekap($id_unit, $tgl_penilaian, $id_penilaian_session);
		$sql = "select 
		a.id_kriteria_aspek, 
		a.kode_aspek,
		a.aspek,
		sum(bobot) as bobot,
		sum(skor_bobot) as skor_bobot
		from ($table) a group by 
		a.id_kriteria_aspek, 
		a.kode_aspek,
		a.aspek
		order by
		case when a.kode_aspek REGEXP '^[0-9]+$' then cast(a.kode_aspek AS UNSIGNED) else null end, 
		case when a.kode_aspek REGEXP '^[0-9]+$' then null else a.kode_aspek end";
		return $this->conn->GetArray($sql);
	}
}
