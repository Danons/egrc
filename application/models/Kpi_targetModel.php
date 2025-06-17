<?php class Kpi_targetModel extends _Model
{
	public $table = "kpi_target";
	public $pk = "id_kpi_target";
	public $label = "nama_kpi";
	function __construct()
	{
		parent::__construct();
	}

	public function GetByPk($id)
	{
		if (!$id) {
			return array();
		}
		// $sql = "select a.evaluasi, a.analisa, c.table_desc as namaunit, a.id_kpi_target, ifnull(b.id_parent, a.id_unit) id_parent, a.id_unit,a.tahun,b.nama as nama,a.id_kpi,satuan,bobot,polarisasi,target,
		// lastinput,totrealisasi,round((totrealisasi/target) * 100,2) prostarget,round(bobot * (totrealisasi/target),2) realbobot 
		// from kpi_target a 
		// left join (
		// 	select tahun,id_kpi_target,
		// sum(nilai) as totrealisasi,max(bulan) as lastinput 
		// from kpi_target_realisasi a 
		// join kpi_target b USING (id_kpi_target) 
		// where b.id_kpi_target = " . $this->conn->qstr($id) . "
		// GROUP by tahun,id_kpi_target 
		// ) d on a.tahun = d.tahun and d.id_kpi_target = a.id_kpi_target 
		// left join kpi b on a.id_kpi = b.id_kpi 
		// left join mt_sdm_unit c on a.id_unit = c.table_code
		// where a.id_kpi_target = " . $this->conn->qstr($id);
		$sql = "select a.*, 
		realisasi * 100 as prostarget, 
		realisasi * bobot as realbobot from (
		select c.table_desc as namaunit, a.is_pic, a.id_kpi_target, a.jenis,
		ifnull(b.id_parent, a.id_unit) id_parent, 
		b.id_parent as id_parent_ori,
		a.id_unit,a.id_dit_bid,a.tahun,b.nama as nama,
		a.id_kpi,satuan,bobot,bobot1,bobot2,polarisasi,target,
		lastinput,totrealisasi,
		a.definisi,
		a.tujuan,
		a.formula,
		case 
		when a.polarisasi = 'Maximize' then case when (case when target=0 then (totrealisasi+1)/(target+1) else totrealisasi/target end) > 1.1 then 1.1 else round((case when target=0 then (totrealisasi+1)/(target+1) else totrealisasi/target end),2) end
		when a.polarisasi = 'Minimize' then case when (case when target=0 then (target+1)/(totrealisasi+1) else target/totrealisasi end) > 1.1 then 1.1 else round((case when target=0 then (target+1)/(totrealisasi+1) else target/totrealisasi end),2) end
		when a.polarisasi = 'Stabilize' then 
			round(((case when target-(abs((100-totrealisasi)-100))=target/100 then 110
			else (100 + (target-(abs((100-totrealisasi)-100)) * ((10/100)/(target/100)))) end)/100),2)
		end realisasi, d.jenis_realisasi, a.evaluasi, a.analisa
		from kpi_target a 
		left join (select
		a.tahun, a.id_kpi_target, a.jenis_realisasi, a.nilai, a.lastinput, 
		max(case when a.jenis_realisasi = 'progresif' 
		then b.nilai else a.nilai end) as totrealisasi 
		from (
			select tahun,id_kpi_target,c.jenis_realisasi,
		case 
		when c.jenis_realisasi = 'akumulatif' then sum(ifnull(nilai,0)) 
		when c.jenis_realisasi = 'average' then avg(ifnull(nilai,0)) 
		else max(ifnull(nilai,0)) 
		end as nilai,
		max(bulan) as lastinput 
		from kpi_target_realisasi a 
		join kpi_target b USING (id_kpi_target) 
		join kpi c on b.id_kpi = c.id_kpi
		where b.id_kpi_target = " . $this->conn->qstr($id) . "
		GROUP by c.jenis_realisasi, tahun, id_kpi_target ) a 
		join kpi_target_realisasi b on a.id_kpi_target = b.id_kpi_target and b.bulan = a.lastinput 
		group by a.tahun, a.id_kpi_target, a.jenis_realisasi, a.nilai, a.lastinput
		) d on a.tahun = d.tahun and d.id_kpi_target = a.id_kpi_target 
		left join kpi b on a.id_kpi = b.id_kpi 
		left join mt_sdm_unit c on a.id_unit = c.table_code
		where a.id_kpi_target = " . $this->conn->qstr($id) . " ) a
		order by id_parent,id_kpi";
		$ret = $this->conn->GetRow($sql);

		// echo $sql;
		// dpr($ret, 1);

		if (!$ret)
			$ret = array();

		return $ret;
	}

	public function SelectGridDirektorat($arr_param = array(), $str_field = "*")
	{
		$arr_params = array(
			'filter' => ''
		);
		foreach ($arr_param as $idkey => $val) {
			$arr_params[$idkey] = $val;
		}

		$tahun = $arr_param['tahun'];
		$nama = strtolower($arr_param['nama']);
		$id_dit_bid = $arr_param['id_dit_bid'];

		$str_condition = "";
		$tahun = $arr_param['tahun'];
		if ($tahun)
			$str_condition .= " and b.tahun = " . $this->conn->escape($tahun);

		if ($nama)
			$str_condition .= " and lower(d.nama) like '%$nama%'";

		if ($id_dit_bid) {
			$str_condition .= " and (c.is_bersama = 1 or 
				(c.is_direktorat = 1 and b.id_dit_bid = " . $this->conn->escape($id_dit_bid) . ")
			)";
		} else {
			$str_condition .= " and (c.is_bersama = 1 or c.is_direktorat = 1)";
		}


		$arrparams = array();
		$arrparams['filter'] = "b.is_pic = 1 and tahun = " . $this->conn->escape($tahun);
		if ($id_dit_bid)
			$arrparams['filter'] .= " and (c.is_bersama = 1 or (c.is_direktorat = 1 and exists (select 1 from mt_sdm_jabatan z where b.id_unit = z.id_unit and z.id_dit_bid = " . $this->conn->escape($id_dit_bid) . "))) ";
		else
			$arrparams['filter'] .= " and (c.is_bersama = 1 or c.is_direktorat = 1) ";

		$rowsunit = $this->SelectGrid($arrparams);

		$tempreal = array();
		foreach ($rowsunit as $rr) {
			if ($rr['id_kpi_ori'] && $rr['tahun']) {
				if ($rr['is_bersama'])
					$id_dit_bid1 = "bersama";
				else
					$id_dit_bid1 = $this->conn->GetOne("select id_dit_bid from mt_sdm_jabatan where id_unit = " . $this->conn->escape($rr['id_unit']));

				$tempreal[$id_dit_bid1][$rr['id_kpi_ori']][$rr['tahun']][] = $rr;
			}
		}

		// dpr($tempreal, 1);

		$sql = "select a.* from (SELECT 
		a.id_dit_bid,
		a.satuan,
		a.bobot,
		a.polarisasi,
		a.target,
		c.nama as direktorat,
		a.is_pic,
		a.tahun,
		a.id_kpi_target,
		a.jenis_realisasi,
		a.is_bersama,
		a.nama,
		concat(a.id_dit_bid,'-',a.id_parent) id_parent, 
		a.id_parent as id_parent_ori,
		concat(a.id_dit_bid,'-',a.id_kpi) as id_kpi,
		a.id_kpi as id_kpi_ori,
		urutan
	FROM
		(SELECT 
			b.id_dit_bid,
			b.satuan,
            b.bobot,
            b.polarisasi,
            b.target,
            b.is_pic,
			b.tahun,
			b.id_kpi_target,
			c.jenis_realisasi,
			c.is_bersama,
			d.id_kpi,
			d.id_parent,
			d.nama,
			d.urutan
		FROM
		kpi_target b
		JOIN kpi_config c ON b.id_kpi = c.id_kpi
			AND b.tahun = c.tahun
		JOIN kpi d ON c.id_kpi = d.id_kpi 
		where b.jenis='Direktorat' $str_condition 
		GROUP BY c.jenis_realisasi ,
			b.id_dit_bid,
			c.is_bersama , 
			b.tahun, 
			b.id_kpi_target, 
			b.satuan,
            b.bobot,
            b.polarisasi,
            b.target,
            b.is_pic
		) a
			left join mt_sdm_dit_bid c on a.id_dit_bid = c.code
	GROUP BY a.id_dit_bid,
		a.satuan,
		a.bobot,
		a.polarisasi,
		a.target,
		a.is_pic,
		a.tahun,
		a.id_kpi_target,
		a.jenis_realisasi,
		a.is_bersama,
		a.id_kpi,
		a.id_parent,
		a.nama) a order by id_dit_bid, urutan, ifnull(id_parent,id_kpi)";

		$rows = $this->conn->GetArray($sql);
		$kpiarr = array();
		foreach ($rows as &$rw) {
			if ($rw['id_kpi_ori'] && $rw['tahun']) {
				$id_dit_bid1 = $rw['id_dit_bid'];
				if ($rw['is_bersama'])
					$id_dit_bid1 = "bersama";

				$rreal = $tempreal[$id_dit_bid1][$rw['id_kpi_ori']][$rw['tahun']];
				// dpr($rreal, 1);
				if ($rreal) {
					if (count($rreal) > 1) {
						if ($rw['jenis_realisasi'] == 'akumulatif') {
							$totrealisasi = 0;
							$totpersen_realisasi = 0;
							$isnull = false;
							foreach ($rreal as $rwt) {
								$totrealisasi += $rwt['totrealisasi'];
								if (!$isnull)
									$isnull = ($rwt['totpersen_realisasi'] === null || $rwt['totpersen_realisasi'] === "");
								$totpersen_realisasi += $rwt['totpersen_realisasi'];
								if (!$rw['lastinput']) {
									$rw['lastinput'] = $rwt['lastinput'];
								} else if ($rwt['lastinput'] > $rw['lastinput']) {
									$rw['lastinput'] = $rwt['lastinput'];
								}
							}

							$rw['totrealisasi'] = $totrealisasi;
							if ($isnull)
								$rw['totpersen_realisasi'] = null;
							else
								$rw['totpersen_realisasi'] = $totpersen_realisasi;
						} elseif ($rw['jenis_realisasi'] == 'average') {
							$totrealisasi = 0;
							$totpersen_realisasi = 0;
							foreach ($rreal as $rwt) {
								$totrealisasi += $rwt['totrealisasi'];
								$totpersen_realisasi += $rwt['totpersen_realisasi'];
								if (!$rw['lastinput']) {
									$rw['lastinput'] = $rwt['lastinput'];
								} else if ($rwt['lastinput'] > $rw['lastinput']) {
									$rw['lastinput'] = $rwt['lastinput'];
								}
							}

							$rw['totrealisasi'] = $totrealisasi / count($rreal);
							$rw['totpersen_realisasi'] = $totpersen_realisasi / count($rreal);
						} elseif ($rw['jenis_realisasi'] == 'progresif') {
							foreach ($rreal as $rwt) {
								if (!$rw['lastinput']) {
									$rw['lastinput'] = $rwt['lastinput'];
									$rw['totrealisasi'] = $rwt['totrealisasi'];
									$rw['totpersen_realisasi'] = $rwt['totpersen_realisasi'];
								} else if ($rwt['lastinput'] > $rw['lastinput']) {
									$rw['lastinput'] = $rwt['lastinput'];
									$rw['totrealisasi'] = $rwt['totrealisasi'];
									$rw['totpersen_realisasi'] = $rwt['totpersen_realisasi'];
								}
							}
						}
					} else {
						$rw['totrealisasi'] = $rreal[0]['totrealisasi'];
						$rw['lastinput'] = $rreal[0]['lastinput'];
						$rw['totpersen_realisasi'] = $rreal[0]['totpersen_realisasi'];
					}
				}
			}

			if ($rw['id_parent_ori'])
				$kpiarr[$rw['id_unit']][] = $rw['id_parent_ori'];


			$totalrealisasi = $rw['totrealisasi'];
			$target = $rw['target'];
			$bobot = $rw['bobot'];
			$satuan = $rw['satuan'];
			$polarisasi = $rw['polarisasi'];

			if (!$rw['lastinput'] && !(!$target && (strtolower($polarisasi) == 'minimize' || strtolower($polarisasi) == 'stabilize')))
				continue;

			if (strlen($rw['totpersen_realisasi']) && !$totalrealisasi) {
				$rw['prostarget'] = $rw['totpersen_realisasi'];
				$rw['totrealisasi'] = null;
			} else {
				if (!$target && !$totalrealisasi && (strtolower($polarisasi) == 'minimize' || strtolower($polarisasi) == 'stabilize')) {
					$rw['prostarget'] = 110;
				} else {
					if (strtolower($polarisasi) == 'maximize') {
						if ($target == 0) {
							$rw['prostarget'] = (((float)$totalrealisasi + 1) / ((float)$target + 1)) * 100;
						} else {
							$rw['prostarget'] = ((float)$totalrealisasi / (float)$target) * 100;
						}
					} elseif (strtolower($polarisasi) == 'minimize') {
						if ($totalrealisasi == 0) {
							$rw['prostarget'] = (((float)$target + 1) / ((float)$totalrealisasi + 1)) * 100;
						} else {
							$rw['prostarget'] = ((float)$target / (float)$totalrealisasi) * 100;
						}
					} elseif (strtolower($polarisasi) == 'stabilize') {
						$x = $target - (abs((100 - (float)$totalrealisasi) - 100));
						$y = (10 / 100) / ((float)$target / 100);
						if ($x == ((float)$target / 100)) {
							$rw['prostarget'] = 110;
						} else {
							$rw['prostarget'] = 100 + $x * $y;
						}
					}
				}
			}

			if ($rw['prostarget'] > 110)
				$rw['prostarget'] = 110;

			$rw['realbobot'] = ($rw['prostarget'] / 100) * $bobot;

			$rw['realbobot'] = round($rw['realbobot'], 2);
			$rw['prostarget'] = round($rw['prostarget'], 2);
		}

		$rowst = array();
		foreach ($rows as &$r) {
			if ($r['is_bersama']) {
				$r['id_parent'] = 'bersama';
			} else if ($r['id_dit_bid']) {
				$r['id_parent'] = $r['id_dit_bid'];
				$rowst[$r['id_dit_bid']] = ['id_kpi' => $r['id_dit_bid'], 'id_parent' => null, 'nama' => $r['direktorat']];
			}
		}
		$rows[] = ['id_kpi' => 'bersama', 'id_parent' => null, 'nama' => 'KPI Bersama'];
		foreach ($rowst as $r1) {
			$rows[] = $r1;
		}

		foreach ($rows as &$r) {
			$isprosen = $this->conn->GetOne("select 1 from kpi_target_realisasi 
			where prosentase is not null 
			and id_kpi_target = " . $this->conn->escape($r['id_kpi_target']));
			if ($isprosen) {
				$r['realisasiarr'] = $this->conn->GetList("select bulan idkey, prosentase val 
				from kpi_target_realisasi 
				where id_kpi_target = " . $this->conn->escape($r['id_kpi_target']) . " 
				order by idkey");
			} else {
				$r['realisasiarr'] = $this->conn->GetList("select bulan idkey, nilai val 
				from kpi_target_realisasi 
				where id_kpi_target = " . $this->conn->escape($r['id_kpi_target']) . " 
				order by idkey");
			}
			$r['bulanarr'] = [];
			foreach ($r['realisasiarr'] as $k => $v) {
				$r['bulanarr'][] = ListBulan()[$k];
			}
		}

		$ret = array();
		$i = null;
		$this->GenerateSort($rows, "id_parent", "id_kpi", "nama", $ret, null, $i);
		return $ret;
	}

	public function SelectGrid($arr_param = array(), $str_field = "*")
	{
		$return = array();
		$arr_params = array(
			'filter' => ''
		);
		foreach ($arr_param as $idkey => $val) {
			$arr_params[$idkey] = $val;
		}

		$str_condition = "";
		if (!empty($arr_params['filter'])) {
			$str_condition = " and " . $arr_params['filter'];
			$str_condition = str_replace("tahun", "b.tahun", $str_condition);
		}

		// $this->conn->debug = 1;
		$sql = "select a.* from (SELECT 
		a.id_unit,
		a.satuan,
		a.bobot,
		a.polarisasi,
		a.target,
		a.is_pic,
		a.tahun,
		a.id_kpi_target,
		a.jenis_realisasi,
		a.is_bersama,
		a.nilai,
		a.lastinput,
		a.nama,
		concat(a.id_unit,'-',a.id_parent) id_parent, 
		a.id_parent as id_parent_ori,
		concat(a.id_unit,'-',a.id_kpi) as id_kpi,
		a.id_kpi as id_kpi_ori,
		MAX(CASE
			WHEN a.jenis_realisasi = 'progresif' THEN b.nilai
			ELSE a.nilai
		END) AS totrealisasi,
		MAX(CASE
			WHEN a.jenis_realisasi = 'progresif' THEN b.prosentase
			ELSE a.prosentase
		END) AS totpersen_realisasi,
		urutan
	FROM
		(SELECT 
			b.id_unit,
			b.satuan,
            b.bobot,
            b.polarisasi,
            b.target,
            b.is_pic,
			b.tahun,
			b.id_kpi_target,
			c.jenis_realisasi,
			c.is_bersama,
			CASE
				WHEN c.jenis_realisasi = 'akumulatif' THEN SUM(IFNULL(nilai, 0))
				WHEN c.jenis_realisasi = 'average' THEN AVG(IFNULL(nilai, 0))
				ELSE MAX(IFNULL(nilai, 0))
			END AS nilai,
			CASE
				WHEN c.jenis_realisasi = 'akumulatif' THEN SUM(IFNULL(prosentase, null))
				WHEN c.jenis_realisasi = 'average' THEN AVG(IFNULL(prosentase, 0))
				ELSE MAX(IFNULL(prosentase, 0))
			END AS prosentase,
			MAX(bulan) AS lastinput,
			d.id_kpi,
			d.id_parent,
			d.nama,
			d.urutan
		FROM
		kpi_target b
		left JOIN kpi_target_realisasi a ON a.id_kpi_target = b.id_kpi_target
		JOIN kpi_config c ON b.id_kpi = c.id_kpi
			AND b.tahun = c.tahun
		JOIN kpi d ON c.id_kpi = d.id_kpi 
		where b.id_unit is not null $str_condition 
		GROUP BY c.jenis_realisasi,c.is_bersama , 
			b.id_unit,
			b.tahun, 
			b.id_kpi_target, 
			b.satuan,
            b.bobot,
            b.polarisasi,
            b.target,
            b.is_pic
		) a
			left JOIN
		kpi_target_realisasi b ON a.id_kpi_target = b.id_kpi_target
			AND b.bulan = a.lastinput
	GROUP BY a.id_unit,
		a.satuan,
		a.bobot,
		a.polarisasi,
		a.target,
		a.is_pic,
		a.tahun,
		a.id_kpi_target,
		a.jenis_realisasi,
		a.is_bersama,
		a.nilai,
		a.lastinput,
		a.id_kpi,
		a.id_parent,
		a.nama) a order by id_unit, urutan, ifnull(id_parent,id_kpi) asc";

		$rows = $this->conn->GetArray($sql);

		// dpr($rows,1);

		$kpiarr = array();
		foreach ($rows as &$rw) {

			if ($rw['id_parent_ori'])
				$kpiarr[$rw['id_unit']][] = $rw['id_parent_ori'];

			$totalrealisasi = $rw['totrealisasi'];
			$target = $rw['target'];
			$bobot = $rw['bobot'];
			$satuan = $rw['satuan'];
			$polarisasi = $rw['polarisasi'];


			if (!$rw['lastinput'] && !(!$target && (strtolower($polarisasi) == 'minimize' || strtolower($polarisasi) == 'stabilize')))
				continue;

			/**
			 * 
			case 
			when a.polarisasi = 'Maximize' then 
				case when (case when target=0 then (totrealisasi+1)/(target+1) else totrealisasi/target end) > 1.1 
				then 1.1 
				else round((case when target=0 then (totrealisasi+1)/(target+1) else totrealisasi/target end),2) 
				end
			when a.polarisasi = 'Minimize' then 
				case when (case when target=0 then (target+1)/(totrealisasi+1) else target/totrealisasi end) > 1.1 
				then 1.1 else round((case when target=0 then (target+1)/(totrealisasi+1) else target/totrealisasi end),2) end
			when a.polarisasi = 'Stabilize' then 
				round(((case when target-(abs((100-totrealisasi)-100))=target/100 then 110
				else (100 + (target-(abs((100-totrealisasi)-100)) * ((10/100)/(target/100)))) end)/100),2)
			end realisasi
			 */

			if (strlen($rw['totpersen_realisasi']) && !$totalrealisasi) {
				$rw['prostarget'] = $rw['totpersen_realisasi'];
				$rw['totrealisasi'] = null;
			} else {
				if (!$target && !$totalrealisasi && (strtolower($polarisasi) == 'minimize' || strtolower($polarisasi) == 'stabilize')) {
					$rw['prostarget'] = 110;
				} else {
					if (strtolower($polarisasi) == 'maximize') {
						if ($target == 0) {
							$rw['prostarget'] = (((float)$totalrealisasi + 1) / ((float)$target + 1)) * 100;
						} else {
							$rw['prostarget'] = ((float)$totalrealisasi / (float)$target) * 100;
						}
					} elseif (strtolower($polarisasi) == 'minimize') {
						if ($totalrealisasi == 0) {
							$rw['prostarget'] = (((float)$target + 1) / ((float)$totalrealisasi + 1)) * 100;
						} else {
							$rw['prostarget'] = ((float)$target / (float)$totalrealisasi) * 100;
						}
					} elseif (strtolower($polarisasi) == 'stabilize') {
						$x = $target - (abs((100 - (float)$totalrealisasi) - 100));
						$y = (10 / 100) / ((float)$target / 100);
						if ($x == ((float)$target / 100)) {
							$rw['prostarget'] = 110;
						} else {
							$rw['prostarget'] = 100 + $x * $y;
						}
					}
				}
			}

			if ($rw['prostarget'] > 110)
				$rw['prostarget'] = 110;

			$rw['realbobot'] = ($rw['prostarget'] / 100) * $bobot;

			$rw['realbobot'] = round($rw['realbobot'], 2);
			$rw['prostarget'] = round($rw['prostarget'], 2);
		}

		if ($kpiarr) {
			$rowst0 = array();
			$rowst1 = array();
			$rowst2 = array();
			$rowst3 = array();
			foreach ($kpiarr as $k => $v) {
				$rows[] = $this->conn->GetRow("select table_code as id_kpi, table_code as id_unit, null as id_parent, table_desc as nama 
					from mt_sdm_unit where table_code in ('" . $k . "')");

				$kpistr = implode(",", $v);
				$rows1 = $this->conn->GetArray("select concat('$k-',id_kpi) as id_kpi, id_kpi as id_kpi_ori, id_parent as id_parent_ori, concat('$k-',id_parent) as id_parent, nama from kpi where id_kpi in (" . $kpistr . ")");
				$kpiarr1 = array();
				foreach ($rows1 as $r) {
					$r['id_unit'] = $k;
					$r['id_parent'] = $k;
					// if ($r['id_parent_ori'])
					// 	$kpiarr1[] = $r['id_parent_ori'];
					// else {
					// 	$r['id_parent'] = $k;
					// }

					$rowst0[] = $r;
				}

				// if ($kpiarr1) {
				// 	$kpistr = implode(",", array_unique($kpiarr1));
				// 	$rows1 = $this->conn->GetArray("select concat('$k-',id_kpi) as id_kpi, id_kpi as id_kpi_ori, id_parent as id_parent_ori, concat('$k-',id_parent) as id_parent, nama from kpi where id_kpi in (" . $kpistr . ")");
				// 	$kpiarr1 = array();
				// 	foreach ($rows1 as $r) {
				// 		$r['id_unit'] = $k;
				// 		if ($r['id_parent_ori'])
				// 			$kpiarr1[] = $r['id_parent_ori'];
				// 		else {
				// 			$r['id_parent'] = $k;
				// 		}
				// 		$rowst1[] = $r;
				// 	}

				// 	if ($kpiarr1) {
				// 		$kpistr = implode(",", array_unique($kpiarr1));
				// 		$rows1 = $this->conn->GetArray("select concat('$k-',id_kpi) as id_kpi, id_kpi as id_kpi_ori, id_parent as id_parent_ori, concat('$k-',id_parent) as id_parent, nama from kpi where id_kpi in (" . $kpistr . ")");
				// 		$kpiarr1 = array();
				// 		foreach ($rows1 as $r) {
				// 			$r['id_unit'] = $k;
				// 			if ($r['id_parent_ori'])
				// 				$kpiarr1[] = $r['id_parent_ori'];
				// 			else {
				// 				$r['id_parent'] = $k;
				// 			}
				// 			$rowst2[] = $r;
				// 		}

				// 		if ($kpiarr1) {
				// 			$kpistr = implode(",", array_unique($kpiarr1));
				// 			$rows1 = $this->conn->GetArray("select concat('$k-',id_kpi) as id_kpi, id_kpi as id_kpi_ori, id_parent as id_parent_ori, concat('$k-',id_parent) as id_parent, nama from kpi where id_kpi in (" . $kpistr . ")");
				// 			$kpiarr1 = array();
				// 			foreach ($rows1 as $r) {
				// 				$r['id_unit'] = $k;
				// 				if ($r['id_parent_ori'])
				// 					$kpiarr1[] = $r['id_parent_ori'];
				// 				else {
				// 					$r['id_parent'] = $k;
				// 				}
				// 				$rowst3[] = $r;
				// 			}
				// 		}
				// 	}
				// }
			}


			if ($rowst3)
				foreach ($rowst3 as $r) {
					$rows[] = $r;
				}
			if ($rowst2)
				foreach ($rowst2 as $r) {
					$rows[] = $r;
				}
			if ($rowst1)
				foreach ($rowst1 as $r) {
					$rows[] = $r;
				}
			if ($rowst0)
				foreach ($rowst0 as $r) {
					$rows[] = $r;
				}
		}

		foreach ($rows as &$r) {
			$isprosen = $this->conn->GetOne("select 1 from kpi_target_realisasi 
			where prosentase is not null 
			and id_kpi_target = " . $this->conn->escape($r['id_kpi_target']));
			if ($isprosen) {
				$r['realisasiarr'] = $this->conn->GetList("select bulan idkey, prosentase val 
				from kpi_target_realisasi 
				where id_kpi_target = " . $this->conn->escape($r['id_kpi_target']) . " 
				order by idkey");
			} else {
				$r['realisasiarr'] = $this->conn->GetList("select bulan idkey, nilai val 
				from kpi_target_realisasi 
				where id_kpi_target = " . $this->conn->escape($r['id_kpi_target']) . " 
				order by idkey");
			}
			$r['bulanarr'] = [];
			foreach ($r['realisasiarr'] as $k => $v) {
				$r['bulanarr'][] = ListBulan()[$k];
			}
		}

		$ret = array();
		$i = null;
		$this->GenerateSort($rows, "id_parent", "id_kpi", "nama", $ret, null, $i);

		return $ret;
	}


	public function SelectGridKorporat($arr_param = array(), $str_field = "*")
	{
		$arr_params = array(
			'filter' => ''
		);
		foreach ($arr_param as $idkey => $val) {
			$arr_params[$idkey] = $val;
		}

		$tahun = $arr_param['tahun'];
		$nama = strtolower($arr_param['nama']);
		$id_dit_bid = $arr_param['id_dit_bid'];

		$str_condition = "";
		$tahun = $arr_param['tahun'];

		if ($arr_param['id_parent'])
			$str_condition .= " and d.id_parent = " . $this->conn->escape($arr_param['id_parent']);

		if ($tahun)
			$str_condition .= " and b.tahun = " . $this->conn->escape($tahun);

		if ($nama)
			$str_condition .= " and lower(d.nama) like '%$nama%'";

		$str_condition .= " and c.is_korporat = 1";


		$arrparams = array();
		$arrparams['filter'] = "c.is_korporat = 1 and b.is_pic = 1 and tahun = " . $this->conn->escape($tahun);
		$rowsunit = $this->SelectGrid($arrparams);

		$tempreal = array();
		foreach ($rowsunit as $rr) {
			if ($rr['id_kpi_ori'] && $rr['tahun']) {
				$tempreal[$rr['id_kpi_ori']][$rr['tahun']][] = $rr;
			}
		}

		$sql = "select a.* from (SELECT 
		a.satuan,
		a.bobot,
		a.polarisasi,
		a.target,
		a.is_pic,
		a.tahun,
		a.id_kpi_target,
		a.jenis_realisasi,
		a.is_bersama,
		a.nama,
		a.id_parent, 
		a.id_parent as id_parent_ori,
		a.id_kpi,
		a.id_kpi as id_kpi_ori,
		urutan
	FROM
		(SELECT 
			b.satuan,
            b.bobot,
            b.polarisasi,
            b.target,
            b.is_pic,
			b.tahun,
			b.id_kpi_target,
			c.jenis_realisasi,
			c.is_bersama,
			d.id_kpi,
			d.id_parent,
			d.nama,
			d.urutan
		FROM
		kpi_target b
		JOIN kpi_config c ON b.id_kpi = c.id_kpi
			AND b.tahun = c.tahun
		JOIN kpi d ON c.id_kpi = d.id_kpi 
		where b.jenis='Korporat' $str_condition 
		GROUP BY c.jenis_realisasi ,
			c.is_bersama , 
			b.tahun, 
			b.id_kpi_target, 
			b.satuan,
            b.bobot,
            b.polarisasi,
            b.target,
            b.is_pic
		) a
	GROUP BY 
		a.satuan,
		a.bobot,
		a.polarisasi,
		a.target,
		a.is_pic,
		a.tahun,
		a.id_kpi_target,
		a.jenis_realisasi,
		a.is_bersama,
		a.id_kpi,
		a.id_parent,
		a.nama) a order by urutan, ifnull(id_parent,id_kpi)";

		$rows = $this->conn->GetArray($sql);
		// dpr($rows, 1);
		$kpiarr = array();
		foreach ($rows as &$rw) {
			if ($rw['id_kpi_ori'] && $rw['tahun']) {
				$rreal = $tempreal[$rw['id_kpi_ori']][$rw['tahun']];
				if ($rreal) {
					if (count($rreal) > 1) {
						if ($rw['jenis_realisasi'] == 'akumulatif') {
							$totrealisasi = 0;
							$totpersen_realisasi = 0;
							$isnull = false;
							foreach ($rreal as $rwt) {
								$totrealisasi += $rwt['totrealisasi'];
								if (!$isnull)
									$isnull = ($rwt['totpersen_realisasi'] === null || $rwt['totpersen_realisasi'] === "");
								$totpersen_realisasi += $rwt['totpersen_realisasi'];
								if (!$rw['lastinput']) {
									$rw['lastinput'] = $rwt['lastinput'];
								} else if ($rwt['lastinput'] > $rw['lastinput']) {
									$rw['lastinput'] = $rwt['lastinput'];
								}
							}

							$rw['totrealisasi'] = $totrealisasi;
							if ($isnull)
								$rw['totpersen_realisasi'] = null;
							else
								$rw['totpersen_realisasi'] = $totpersen_realisasi;
						} elseif ($rw['jenis_realisasi'] == 'average') {
							$totrealisasi = 0;
							$totpersen_realisasi = 0;
							foreach ($rreal as $rwt) {
								$totrealisasi += $rwt['totrealisasi'];
								$totpersen_realisasi += $rwt['totpersen_realisasi'];
								if (!$rw['lastinput']) {
									$rw['lastinput'] = $rwt['lastinput'];
								} else if ($rwt['lastinput'] > $rw['lastinput']) {
									$rw['lastinput'] = $rwt['lastinput'];
								}
							}

							$rw['totrealisasi'] = $totrealisasi / count($rreal);
							$rw['totpersen_realisasi'] = $totpersen_realisasi / count($rreal);
						} elseif ($rw['jenis_realisasi'] == 'progresif') {
							foreach ($rreal as $rwt) {
								if (!$rw['lastinput']) {
									$rw['lastinput'] = $rwt['lastinput'];
									$rw['totrealisasi'] = $rwt['totrealisasi'];
									$rw['totpersen_realisasi'] = $rwt['totpersen_realisasi'];
								} else if ($rwt['lastinput'] > $rw['lastinput']) {
									$rw['lastinput'] = $rwt['lastinput'];
									$rw['totrealisasi'] = $rwt['totrealisasi'];
									$rw['totpersen_realisasi'] = $rwt['totpersen_realisasi'];
								}
							}
						}
					} else {
						$rw['totrealisasi'] = $rreal[0]['totrealisasi'];
						$rw['lastinput'] = $rreal[0]['lastinput'];
						$rw['totpersen_realisasi'] = $rreal[0]['totpersen_realisasi'];
					}
				}
			}

			if ($rw['id_parent_ori'])
				$kpiarr[$rw['id_parent_ori']] = $rw['id_parent_ori'];

			$totalrealisasi = $rw['totrealisasi'];
			$target = $rw['target'];
			$bobot = $rw['bobot'];
			$satuan = $rw['satuan'];
			$polarisasi = $rw['polarisasi'];

			if (!$rw['lastinput'] && !(!$target && (strtolower($polarisasi) == 'minimize' || strtolower($polarisasi) == 'stabilize')))
				continue;

			if (strlen($rw['totpersen_realisasi']) && !$totalrealisasi) {
				$rw['prostarget'] = $rw['totpersen_realisasi'];
				$rw['totrealisasi'] = null;
			} else {
				if (!$target && !$totalrealisasi && (strtolower($polarisasi) == 'minimize' || strtolower($polarisasi) == 'stabilize')) {
					$rw['prostarget'] = 110;
				} else {
					if (strtolower($polarisasi) == 'maximize') {
						if ($target == 0) {
							$rw['prostarget'] = (((float)$totalrealisasi + 1) / ((float)$target + 1)) * 100;
						} else {
							$rw['prostarget'] = ((float)$totalrealisasi / (float)$target) * 100;
						}
					} elseif (strtolower($polarisasi) == 'minimize') {
						if ($totalrealisasi == 0) {
							$rw['prostarget'] = (((float)$target + 1) / ((float)$totalrealisasi + 1)) * 100;
						} else {
							$rw['prostarget'] = ((float)$target / (float)$totalrealisasi) * 100;
						}
					} elseif (strtolower($polarisasi) == 'stabilize') {
						$x = $target - (abs((100 - (float)$totalrealisasi) - 100));
						$y = (10 / 100) / ((float)$target / 100);
						if ($x == ((float)$target / 100)) {
							$rw['prostarget'] = 110;
						} else {
							$rw['prostarget'] = 100 + $x * $y;
						}
					}
				}
			}

			if ($rw['prostarget'] > 110)
				$rw['prostarget'] = 110;

			$rw['realbobot'] = ($rw['prostarget'] / 100) * $bobot;

			$rw['realbobot'] = round($rw['realbobot'], 2);
			$rw['prostarget'] = round($rw['prostarget'], 2);
		}


		if ($kpiarr) {
			$kpistr = implode(",", $kpiarr);
			$rows1 = $this->conn->GetArray("select id_kpi, 
			id_kpi as id_kpi_ori, id_parent as id_parent_ori, null as id_parent, 
			nama from kpi where id_kpi in (" . $kpistr . ") order by ifnull(kpi.id_parent,kpi.id_kpi) asc");
			foreach ($rows1 as $r) {
				$rows[] = $r;
			}
		}


		foreach ($rows as &$r) {
			$isprosen = $this->conn->GetOne("select 1 from kpi_target_realisasi 
			where prosentase is not null 
			and id_kpi_target = " . $this->conn->escape($r['id_kpi_target']));
			if ($isprosen) {
				$r['realisasiarr'] = $this->conn->GetList("select bulan idkey, prosentase val 
				from kpi_target_realisasi 
				where id_kpi_target = " . $this->conn->escape($r['id_kpi_target']) . " 
				order by idkey");
			} else {
				$r['realisasiarr'] = $this->conn->GetList("select bulan idkey, nilai val 
				from kpi_target_realisasi 
				where id_kpi_target = " . $this->conn->escape($r['id_kpi_target']) . " 
				order by idkey");
			}
			$r['bulanarr'] = [];
			foreach ($r['realisasiarr'] as $k => $v) {
				$r['bulanarr'][] = ListBulan()[$k];
			}
		}

		$ret = array();
		$i = null;
		$this->GenerateSort($rows, "id_parent", "id_kpi", "nama", $ret, null, $i);
		return $ret;
	}

	function SqlCombo()
	{
		$where = ' where 1=1 ';

		$sql = "select {$this->pk} as idkey,concat(COALESCE(tahun,''),'-',COALESCE(id_subbid,''),'-',COALESCE(b.nama,'')) as val from {$this->table}";
		$sql .= " join kpi b using (id_kpi) ";

		$sql .= "$where order by idkey";
		return $sql;
	}



	// start datatables
	var $column_search = array('tahun', 'table_Desc', 'code'); //set column field database for datatable searchable
	var $order = array('id_kpi_target' => 'asc'); // default order 

	private function _get_datatables_query()
	{
		$this->db->select('*');
		$this->db->from('kpi_target');
		// $this->db->join('mt_sdm_dit_bid', 'code=id_dit_bid');
		$this->db->join('mt_sdm_unit', 'table_code=id_subbid');
		$this->db->join('kpi kpi', 'kpi.id_kpi=kpi_target.id_kpi');
		$this->db->join('kpi parentkpi', 'parentkpi.id_kpi = kpi.id_parent', 'left');

		$i = 0;
		foreach ($this->column_search as $item) { // loop column 
			if (@$_POST['search']['value']) { // if datatable send POST for search
				if ($i === 0) { // first loop
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}
				if (count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		if (isset($_POST['order'])) { // here order processing
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	function get_datatables()
	{
		$this->_get_datatables_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();

		$str = $this->db->last_query();

		echo "<pre>";
		print_r($query);
		return $query->result();
	}
	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}
	function count_all()
	{
		$this->db->select("*");
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
	// end datatables
}
