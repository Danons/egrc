<?php class Penilaian_periodeModel extends _Model
{
	public $table = "penilaian_periode";
	public $pk = "id_penilaian_periode";
	public $label = "nama";
	function __construct()
	{
		parent::__construct();
	}

	public function get_kriteria_parent($id_kriteria)
	{
		if (!$id_kriteria)
			return array();

		$ret1 = $this->conn->GetRow("select * from mt_kriteria where id_kriteria = " . $this->conn->escape($id_kriteria) . " order by id_kriteria asc");

		$ret2 = $this->get_kriteria_parent($ret1['id_kriteria_parent']);

		$ret = array();
		if ($ret2)
			foreach ($ret2 as $r1)
				$ret[$r1['id_kriteria']] = $r1;

		$ret[$ret1['id_kriteria']] = $ret1;

		return $ret;
	}

	public function get_kriteria_penilaian($id_kategori = null, $id_unit = null, $tgl_penilaian = null, $id_penilaian_session = null)
	{

		$rows = $this->conn->GetArray("select k.id_kriteria as id_kriteria_link, k1.* 
			from mt_kriteria k
			join kriteria_link kl on k.id_kriteria = kl.id_kriteria1
			join mt_kriteria k1 on kl.id_kriteria2 = k1.id_kriteria
			where k.is_upload = 1 and k.id_kategori = " . $this->conn->escape($id_kategori) . " 
			order by k.id_kriteria asc");


		$rowslink = array();
		foreach ($rows as $r) {

			// $r['is_attr'] = $this->conn->GetOne("select 1 from mt_kriteria_attribute where id_kriteria = " . $this->conn->escape($r['id_kriteria']));

			$rowslink[$r['id_kriteria_link']][] = $r;
		}

		if (!Access("edit", "panelbackend/mt_kriteria")) {
			$where = " and pp.is_aktif = 1 ";
		}

		if (strlen($tgl_penilaian) == 10)
			$where .= " AND DATE_FORMAT(pp.tgl_penilaian,'%Y-%m-%d')=" . $this->conn->escape($tgl_penilaian);

		if ($id_unit) {
			$where .= " and k.id_unit = " . $this->conn->escape($id_unit);
		}

		$rows1 = $this->conn->GetArray("select k.*, pp.id_penilaian_periode, pp.is_aktif as is_aktif_penilaian, pp.nilai_target, kg.id_kategori_jenis
		from penilaian_periode pp
		left join mt_kriteria k on k.id_kriteria = pp.id_kriteria
		left join mt_kategori kg on k.id_kategori = kg.id_kategori
		where 1=1
		$where
		and id_penilaian_session = " . $this->conn->escape($id_penilaian_session) . "
		and k.id_kategori = " . $this->conn->escape($id_kategori) . "
		ORDER BY k.id_kriteria asc");

		$id_kategori_jenis = $rows1[0]['id_kategori_jenis'];

		$rows = array();
		foreach ($rows1 as $r) {
			$rows2 = $this->get_kriteria_parent($r['id_kriteria_parent']);

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
		$arearr = array();
		if (isset($a['']))
			foreach ($a[''] as $r) {
				$r['sub1'] = array();

				if (empty($r['rowspan']))
					$r['rowspan'] = 0;

				#loop sub1area / aspek
				if (isset($a[$r['id_kriteria']]))
					foreach ($a[$r['id_kriteria']] as $r1) {

						if (empty($r['rowspan']))
							$r['rowspan'] = 0;

						if (empty($r1['rowspan']))
							$r1['rowspan'] = 0;

						#loop level / warna / uraian smk3
						if (isset($a[$r1['id_kriteria']]))
							foreach ($a[$r1['id_kriteria']] as $r2) {

								$r2['sub3'] = array();

								if (empty($r['rowspan']))
									$r['rowspan'] = 0;

								if (empty($r1['rowspan']))
									$r1['rowspan'] = 0;

								if (empty($r2['rowspan']))
									$r2['rowspan'] = 0;

								#loop sub3 pendukung / urian proper
								if (isset($a[$r2['id_kriteria']]))
									foreach ($a[$r2['id_kriteria']] as $r3) {

										$r3['sub4'] = array();

										if (empty($r['rowspan']))
											$r['rowspan'] = 0;

										if (empty($r1['rowspan']))
											$r1['rowspan'] = 0;

										if (empty($r2['rowspan']))
											$r2['rowspan'] = 0;

										if (empty($r3['rowspan']))
											$r3['rowspan'] = 0;

										if (isset($a[$r3['id_kriteria']]))
											foreach ($a[$r3['id_kriteria']] as $r4) {

												$r4['link'] = $rowslink[$r4['id_kriteria']];
												$belum = 100;

												#cek status penilaian
												if ($r4['is_aktif_penilaian']) {
													$belum = $this->conn->GetOne("select max(status) from penilaian where status in (0,1,2) 
				                					and id_penilaian_periode = " . $this->conn->escape($r4['id_penilaian_periode']));

													if ($belum === '0')
														$belum = 100;

													if (!$belum) {
														$row = $this->conn->GetRow("select avg(skor) skor,  
													GROUP_CONCAT(simpulan) kesimpulan 
													from penilaian_detail a 
													where exists (select 1 from penilaian b 
													where a.id_penilaian = b.id_penilaian 
													and b.id_penilaian_periode = " . $this->conn->escape($r4['id_penilaian_periode']) . ") 
													and tgl is not null");

														$r4['skor'] = $row['skor'];
														$r4['kesimpulan'] = $row['kesimpulan'];
													}
												}

												$r4['belum'] = $belum;


												$r3['sub4'][] = $r4;

												$r['rowspan']++;
												$r1['rowspan']++;
												$r2['rowspan']++;
												$r3['rowspan']++;
											}
										else {
											$r['rowspan']++;
											$r1['rowspan']++;
											$r2['rowspan']++;
											$r3['rowspan']++;
										}
										$r2['sub3'][] = $r3;
									}
								else {
									$r['rowspan']++;
									$r1['rowspan']++;
									$r2['rowspan']++;
								}

								$r1['sub2'][] = $r2;
							}
						else {
							$r['rowspan']++;
							$r1['rowspan']++;
						}

						$r['sub1'][] = $r1;
					}
				else {
					$r['rowspan']++;
				}

				$arearr[] = $r;
			}

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

	private function table_rekap($id_unit, $tgl_penilaian, $id_penilaian_session)
	{
		$addfilter = "";
		if ($id_unit) {
			$addfilter = " and b.id_unit = " . $this->conn->escape($id_unit);
		}
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
			select d.id_kriteria, d.bobot, avg(skor) as skor, d.bobot*avg(skor) as skor_bobot from (
			select a.id_kriteria, avg(skor) skor
			from penilaian_periode a 
			left join penilaian b on a.id_penilaian_periode = b.id_penilaian_periode 
			and b.status = 3 and b.tgl_label = " . $this->conn->escape($tahun) . "
			left join penilaian_detail c on b.id_penilaian = c.id_penilaian and c.tgl is not null 
			where a.tgl_penilaian = " . $this->conn->escape($tgl_penilaian) . " 
			and id_penilaian_session = " . $this->conn->escape($id_penilaian_session) . "
			group by a.id_kriteria) a
			join mt_kriteria b on a.id_kriteria = b.id_kriteria
			join mt_kriteria c on b.id_kriteria_parent = c.id_kriteria
			join mt_kriteria d on c.id_kriteria_parent = d.id_kriteria
			where 1=1 $addfilter  
			group by d.id_kriteria, d.bobot) a
		join mt_kriteria b on a.id_kriteria = b.id_kriteria
		join mt_kriteria c on b.id_kriteria_parent = c.id_kriteria
		join mt_kriteria d on c.id_kriteria_parent = d.id_kriteria";
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
		a.indikator";
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
		a.aspek";
		return $this->conn->GetArray($sql);
	}
}
