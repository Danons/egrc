<?php class Mt_kriteriaModel extends _Model
{
	public $table = "mt_kriteria";
	public $pk = "id_kriteria";
	public $label = "nama";
	function __construct()
	{
		parent::__construct();
	}

	function GetCombo2($id_kategori = null)
	{
		// $this->conn->debug=1;
		if ($id_kategori)
			$where = "and id_kategori=$id_kategori";
		$sql = "select {$this->pk} as key, kode||' - '||nama as val from {$this->table} where deleted_date is null and is_upload = 1 $where order by key";
		$rows = $this->conn->GetArray($sql);
		$data = array('' => '-pilih-');
		foreach ($rows as $r) {
			$data[trim($r['key'])] = $r['val'];
		}
		// dpr($data,1);
		return $data;
	}

	function GetCombo($id_kategori = null, $kode = false)
	{
		// $this->conn->debug=1;
		if ($id_kategori)
			$where = "and a.id_kategori=$id_kategori";

		if ($kode)
			$where .= " and a.$kode = 1";

		$sql = "select a.id_kriteria as idkey, concat(
			case when e.kode is not null then concat(e.kode,'.') else '' end,
			case when d.kode is not null then concat(d.kode,'.') else '' end,
			case when c.kode is not null then concat(c.kode,'.') else '' end,
			case when b.kode is not null then concat(b.kode,'.') else '' end,
			case when a.kode is not null then concat(a.kode,'.') else '' end
		,' - ',a.nama) as val 
		from mt_kriteria a
		left join mt_kriteria b on a.id_kriteria_parent = b.id_kriteria
		left join mt_kriteria c on b.id_kriteria_parent = c.id_kriteria
		left join mt_kriteria d on c.id_kriteria_parent = d.id_kriteria
		left join mt_kriteria e on d.id_kriteria_parent = e.id_kriteria
		where 1 = 1 and a.deleted_date is null 
		$where order by idkey";

		$rows = $this->conn->GetArray($sql);
		$data = array('' => '-pilih-');
		foreach ($rows as $r) {
			$data[trim($r['idkey'])] = $r['val'];
		}
		// dpr($data,1);
		return $data;
	}

	public function getComboParent($id_kategori = null, $id_parent = null)
	{
		$add_filter = " and deleted_date is null and id_kriteria_parent is null";
		if ($id_parent)
			$add_filter = " and id_kriteria_parent = " . $this->conn->escape($id_parent);

		return $this->conn->GetList("select id_kriteria as idkey, nama as val
		from mt_kriteria 
		where deleted_date is null and id_kategori = " . $this->conn->escape($id_kategori) . $add_filter . " order by id_kriteria asc");
	}

	public function get_kriteria($id_kriteria_parent = null, $level = 0, $is_aktif = false)
	{
		$add_filter = "";
		if ($is_aktif) {
			$add_filter = " and k.is_aktif = '1' ";
		}
		$level++;
		$ret = [];
		$rows = $this->conn->GetArray("SELECT * 
		FROM mt_kriteria k
		WHERE k.deleted_date is null and id_kriteria_parent = " . $this->conn->escape($id_kriteria_parent) . $add_filter . " 
		ORDER by 
		case when kode REGEXP '^[0-9]+$' then cast(kode AS UNSIGNED) else null end, 
		case when kode REGEXP '^[0-9]+$' then null else kode end,
		id_kriteria asc");
		if ($rows)
			foreach ($rows as $r) {
				$r['link'] = $this->conn->GetArray("select * 
				from kriteria_link 
				where deleted_date is null and id_kriteria1 = " . $this->conn->escape($r['id_kriteria']));
				$sub = $this->get_kriteria(($r['id_kriteria']), $level, $is_aktif);
				$rowspan = 0;
				if ($sub)
					foreach ($sub as $rs)
						$rowspan = $rowspan + $rs['rowspan'];
				else
					$rowspan++;

				$r['rowspan'] = $rowspan;
				$r['sub' . $level] = $sub;
				$ret[] = $r;
			}

		return $ret;
	}


	public function get_kriteria_parent($id_kriteria)
	{
		if (!$id_kriteria)
			return array();

		$ret1 = $this->conn->GetRow("select * from mt_kriteria where deleted_date is null and id_kriteria = " . $this->conn->escape($id_kriteria));

		$ret2 = $this->get_kriteria_parent($ret1['id_kriteria_parent']);

		$ret = array();
		if ($ret2)
			foreach ($ret2 as $r1)
				$ret[$r1['id_kriteria']] = $r1;

		$ret[$ret1['id_kriteria']] = $ret1;

		return $ret;
	}

	public function get_kriteria1($id_kategori = null, $tahun = null, $id_interval = null)
	{

		if ($id_interval)
			$add_filter = " and k.id_interval = " . $this->conn->escape($id_interval);

		if ($tahun)
			$add_filter .= " and k.tahun = " . $this->conn->escape($tahun);

		$rows = $this->conn->GetArray("SELECT k.id_kriteria as id_kriteria_link, k1.* 
			from mt_kriteria k
			join kriteria_link kl on k.id_kriteria = kl.id_kriteria1
			join mt_kriteria k1 on kl.id_kriteria2 = k1.id_kriteria
			WHERE k.deleted_date is null and k.id_kategori = " . $this->conn->escape($id_kategori) .
			$add_filter);

		$rowslink = array();
		foreach ($rows as $r) {
			$rowslink[$r['id_kriteria_link']][] = $r;
		}

		$rows1 = $this->conn->GetArray("SELECT * 
			FROM mt_kriteria k
			WHERE k.deleted_date is null and k.is_aktif = '1' and  id_kategori = " . $this->conn->escape($id_kategori) . $add_filter . " 
			ORDER BY kode+0 ASC, id_kriteria asc");

		$rows = array();
		foreach ($rows1 as $r) {
			$rows2 = $this->get_kriteria_parent($r['id_kriteria_parent']);

			$rows[$r['id_kriteria']] = $r;

			if ($rows2)
				foreach ($rows2 as $r1) {
					if (!$rows[$r1['id_kriteria']])
						$rows[$r1['id_kriteria']] = $r1;
				}
		}

		$a = array();
		foreach ($rows as $dokumen) {
			$a[$dokumen['id_kriteria_parent']][] = $dokumen;
			sort($a[$dokumen['id_kriteria_parent']]);
		}

		$arearr = array();
		if (isset($a['']))
			foreach ($a[''] as $r) {
				$r['sub1'] = array();

				if (empty($r['rowspan']))
					$r['rowspan'] = 0;

				if (isset($a[$r['id_kriteria']]))
					foreach ($a[$r['id_kriteria']] as $r1) {

						if (empty($r['rowspan']))
							$r['rowspan'] = 0;

						if (empty($r1['rowspan']))
							$r1['rowspan'] = 0;

						if (isset($a[$r1['id_kriteria']]))
							foreach ($a[$r1['id_kriteria']] as $r2) {

								$r2['sub3'] = array();

								if (empty($r['rowspan']))
									$r['rowspan'] = 0;

								if (empty($r1['rowspan']))
									$r1['rowspan'] = 0;

								if (empty($r2['rowspan']))
									$r2['rowspan'] = 0;

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
		// dpr($arearr, 1);
		return $arearr;
	}

	public function tambah_kriteria($data) //edit
	{

		$ret = $this->conn->goInsert('mt_kriteria', $data);
		if (!$ret)
			return false;

		return $this->conn->GetOne("select max(id_kriteria) as id_kriteria from mt_kriteria where deleted_date is null and id_kategori = " . $data['id_kategori']);
	}
}
