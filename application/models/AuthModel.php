<?php class AuthModel extends _Model
{
	function __construct()
	{
		parent::__construct();
	}
	public function Login($user = "", $pass = "", $email = null)
	{
		if ($_SESSION[SESSION_APP][$user]['gagal'] >= 5) {
			$t = (time() - $_SESSION[SESSION_APP]['t']) / 60;
			if ($t <= 5)
				return array('error' => 'User ' . $user . ' sudah gagal login 5 kali, silahkan tunggu ' . ceil(5 - $t) . " menit lagi");
		}

		$username = $this->conn->qstr($user);
		$password = $this->conn->qstr(sha1(md5($pass)));
		// $data = $this->GetRow("
		// select * from public_sys_user
		// where username=$username and password=$password
		// and is_active = '1' 
		// and sysdate() between coalesce(tgl_mulai_aktif, sysdate()-1) and coalesce(tgl_selesai_aktif, sysdate()+1)
		// ");
		// $data = $this->GetRow("
		// select * from public_sys_user
		// where username=$username and password=$password
		// and is_active = '1' 
		// and GETDATE() between coalesce(tgl_mulai_aktif, GETDATE()-1) and coalesce(tgl_selesai_aktif, GETDATE()+1)
		// ");
		// $this->conn->debug=1;
		$data = $this->GetRow("
		select * from public_sys_user
		where deleted_date is null and username=$username and password=$password
		and is_active = '1' 
		and sysdate() between ifnull(tgl_mulai_aktif, sysdate()-1) and ifnull(tgl_selesai_aktif, sysdate()+1)
		");
		if ($data) {
			$dataarr = $this->conn->GetArray("select b.user_id, b.username, concat(b.name , '-' , d.nama) as name, b.nid, b.email, b.is_manual, b.is_notification,
				c.group_id, c.name as nama_group,
				d.id_jabatan from public_sys_user_group a
				join public_sys_user b on a.user_id = b.user_id
				join public_sys_group c on a.group_id = c.group_id
				join mt_sdm_jabatan d on a.id_jabatan = d.id_jabatan
				where a.deleted_date is null and a.user_id = " . $this->conn->escape($data['user_id']));

			if ($dataarr) {
				if (count($dataarr) > 1) {
					$_SESSION[SESSION_APP]['login'] = true;

					$_SESSION[SESSION_APP]['akses'] = $dataarr;

					return array('success' => 'login berhasil');
				} else {
					$this->SetLogin($dataarr[0]);
					return array('success' => 'login berhasil');
				}
			} else {
				$this->SetLogin($data);

				return array('success' => 'login berhasil');
			}
		}
		$_SESSION[SESSION_APP][$user]['gagal'] = (int)$_SESSION[SESSION_APP][$user]['gagal'] + 1;
		$_SESSION[SESSION_APP]['t'] = time();
		if ($_SESSION[SESSION_APP][$user]['gagal'] == 5) {
			return array('error' => 'User ' . $user . ' sudah gagal login 5 kali, silahkan tunggu beberapa saat lagi');
		} else {
			return array('error' => 'login gagal');
		}
	}
	public function LoginAs($user_id = "", $id_jabatan = "", $group_id = "")
	{
		$user_id = $this->conn->qstr($user_id);
		$data = $this->GetRow("
		select * from public_sys_user
		where deleted_date is null and user_id=$user_id
		and is_active = '1'
		");
		if ($data) {

			$loginas = $_SESSION[SESSION_APP];
			unset($_SESSION[SESSION_APP]);
			$_SESSION[SESSION_APP]['loginas'] = $loginas;


			$dataarr = $this->conn->GetArray("select b.user_id, b.username, concat(b.name , '-' ,d.nama) as name, b.nid, b.email, b.is_manual, b.is_notification,
				c.group_id, c.name as nama_group,
				d.id_jabatan from public_sys_user_group a
				join public_sys_user b on a.user_id = b.user_id
				join public_sys_group c on a.group_id = c.group_id
				join mt_sdm_jabatan d on a.id_jabatan = d.id_jabatan
				where a.deleted_date is null and a.user_id = " . $this->conn->escape($data['user_id']));

			if ($dataarr) {
				if (count($dataarr) > 1) {
					$_SESSION[SESSION_APP]['akses'] = $dataarr;
				}
			}

			if ($id_jabatan)
				$data['id_jabatan'] = $id_jabatan;

			if ($group_id)
				$data['group_id'] = $group_id;

			$this->SetLogin($data);

			return array('success' => 'login success');
		}
		return array('error' => 'login filed');
	}

	public function SetLogin($data = array(), $tokenarr = array())
	{
		$data = (array)$data;

		$data['login'] = true;
		unset($data['password']);



		if ($data['KODE_GROUP']) {
			$data['group_id'] = $data['KODE_GROUP'];
		}

		$data['nama_group'] = $this->conn->GetOne("select name from public_sys_group where deleted_date is null and group_id=" . $this->conn->escape($data['group_id']));

		$group_id = $data['group_id'];

		if ($data['PEGAWAI']) {
			$data['name'] = $data['PEGAWAI'];
		}

		$data['id_unit'] = $this->conn->GetOne("select id_unit from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($data['id_jabatan']));

		$temp = $data;
		foreach ($temp as $k => $v) {
			$k = strtolower($k);
			$data[$k] = $v;
			$_SESSION[SESSION_APP][$k] = $v;
		}

		foreach ($tokenarr as $k => $v) {
			$_SESSION[SESSION_APP][$k] = $v;
		}

		if ($data['id_unit']) {

			// $this->conn->debug = 1;

			// $jabatanchild = $this->GetChildJabatan($data['id_jabatan']);

			// if (!$jabatanchild)
			// 	$jabatanchild = array();

			// $_SESSION[SESSION_APP]['child_jabatan'] = array_unique($jabatanchild);

			// dpr($_SESSION[SESSION_APP]);
			$_SESSION[SESSION_APP]['owner_jabatan'] = $this->GetParentJabatan($data['id_jabatan']);
			// dpr($_SESSION[SESSION_APP]['owner_jabatan']);
			// $this->conn->GetOne("select 
			// 	a.owner
			// 	from risk_scorecard a 
			// 	where a.id_unit = " . $this->conn->escape($data['id_unit']));
		}
		// dpr($_SESSION[SESSION_APP], 1);


		$menuarr = $this->GetMenuArr(null, false);
		$_SESSION[SESSION_APP]['menu'] = $menuarr;

		$menu_id1 = $this->conn->GetOne("select menu_id from public_sys_menu where deleted_date is null and url = 'main'");
		$_SESSION[SESSION_APP]['view_all'] =  $this->conn->GetOne("select 1 FROM
			    public_sys_group_menu c
			        left join
			    public_sys_group_action a ON a.group_menu_id = c.group_menu_id
			        LEFT JOIN
			    public_sys_action b ON a.action_id = b.action_id
			WHERE c.deleted_date is null and c.group_id = '$group_id' AND c.menu_id='$menu_id1' and b.name = 'view_all'");

		$datenow = $this->conn->sysTimeStamp;
		$this->conn->Execute("
		update public_sys_user
		set last_ip = '{$_SERVER['REMOTE_A%dR']}', last_login = $datenow
		where username = '{$data['username']}'");
	}

	private $statusaccess = [
		"pengajuan" => [4, 5, 6],
		"penerusan" => [4, 2, 6, 5],
		"persetujuan" => [3, 7, 9]
	];
	public function SqlTask()
	{

		$id_jabatan = $_SESSION[SESSION_APP]['id_jabatan'];
		$owner_jabatan = $_SESSION[SESSION_APP]['owner_jabatan'];
		$user_id = $_SESSION[SESSION_APP]['user_id'];
		$id_unit = $_SESSION[SESSION_APP]['id_unit'];

		if (!$id_jabatan)
			$id_jabatan = '0';
		if (!$owner_jabatan)
			$owner_jabatan = '0';

		$filterstatusarr = array();
		#notif risiko untuk USER
		if ($this->ci->Access("pengajuan", "panelbackend/risk_scorecard")) {
			$filterstatusarr[] = " (t.id_status_pengajuan in (" . implode(",", $this->statusaccess['pengajuan']) . ") and t.page in ('risk_scorecard','scorecard'))";
		}
		#notif risiko untuk OWNER
		if ($this->ci->Access("penerusan", "panelbackend/risk_scorecard")) {
			$filterstatusarr[] = " (t.id_status_pengajuan in (" . implode(",", $this->statusaccess['penerusan']) . ") and t.page in ('risk_scorecard','scorecard'))";
		}
		#notif risiko untuk USER
		if ($this->ci->Access("pengajuan", "panelbackend/opp_scorecard")) {
			$filterstatusarr[] = " (t.id_status_pengajuan in (" . implode(",", $this->statusaccess['pengajuan']) . ") and t.page in ('opp_scorecard','scorecard'))";
		}
		#notif risiko untuk OWNER
		if ($this->ci->Access("penerusan", "panelbackend/opp_scorecard")) {
			$filterstatusarr[] = " (t.id_status_pengajuan in (" . implode(",", $this->statusaccess['penerusan']) . ") and t.page in ('opp_scorecard','scorecard'))";
		}
		// if ($this->ci->Access("evaluasimitigasi", "panelbackend/risk_scorecard")) {
		// 	$filterstatusarr[] = " (t.id_status_pengajuan in (7,10) and t.page in ('risk_scorecard','scorecard'))";
		// }
		// #notif risiko SPI
		// if ($this->ci->Access("evaluasirisiko", "panelbackend/risk_scorecard")) {
		// 	$filterstatusarr[] = " (t.id_status_pengajuan in (8) and t.page in ('risk_scorecard','scorecard'))";
		// }
		#notif risiko untuk KOORDINATOR
		if ($this->ci->Access("persetujuan", "panelbackend/risk_scorecard")) {
			$filterstatusarr[] = " (t.id_status_pengajuan in (" . implode(",", $this->statusaccess['persetujuan']) . ") and t.page in ('risk_scorecard','scorecard'))";
		}
		if ($this->ci->Access("persetujuan", "panelbackend/opp_scorecard")) {
			$filterstatusarr[] = " (t.id_status_pengajuan in (" . implode(",", $this->statusaccess['persetujuan']) . ") and t.page in ('opp_scorecard','scorecard'))";
		}

		$wherearr = array();
		if (($filterstatusarr)) {
			$wherearr[] = " (" . implode(" or ", $filterstatusarr) . ")";
		}

		if (!$this->ci->access_role['view_all']) {
			#akses semua unit
			if ($this->ci->access_role['view_all_unit'] && count($filterstatusarr)) {
				$wherearr[] = " (s.id_unit = " . $this->conn->escape($id_unit) . " 
				or o.id_unit = " . $this->conn->escape($id_unit) . ")";
			} else {
				#akses spesifik unit
				$wherearr[] = " (
				s.owner in (" . $this->conn->escape($id_jabatan) . "," . $this->conn->escape($owner_jabatan) . ") 
				or o.owner in (" . $this->conn->escape($id_jabatan) . "," . $this->conn->escape($owner_jabatan) . ")
				)";
			}
		} elseif (!count($filterstatusarr)) {
			#akses spesifik unit
			$wherearr[] = " (
			s.owner in (" . $this->conn->escape($id_jabatan) . "," . $this->conn->escape($owner_jabatan) . ") 
			or o.owner in (" . $this->conn->escape($id_jabatan) . "," . $this->conn->escape($owner_jabatan) . ")
			)";
		}

		$wherearr[] = " t.created_by <> " . $this->conn->escape($user_id);

		$where = " and (" . implode(" and ", $wherearr) . " or (untuk_user = " . $this->conn->escape($user_id) . " or untuk = " . $this->conn->escape($id_jabatan) . " or untuk = " . $this->conn->escape($owner_jabatan) . "))";

		$sql = " ,1 as asd
		from risk_task t
		left join risk_scorecard s on t.id_scorecard = s.id_scorecard
		left join opp_scorecard o on t.id_scorecard_peluang = o.id_scorecard
		left join pemeriksaan pks on t.id_pemeriksaan = pks.id_pemeriksaan
		left join public_sys_user u on t.created_by = u.user_id
		left join public_sys_group g on t.group_id = g.group_id
		where 1=1 and t.is_pending != '1' " . $where;

		return $sql;
	}

	public function PenerimaByStatus(
		$id_status_pengajuan = null,
		$untuk = null,
		$id_jabatan = null
	) {

		$where = "";

		if ($untuk) {
			$where .= " and j.id_jabatan = " . $this->conn->escape($untuk);
		} else {
			// $row_dir = $this->GetScorecard($id_risiko);

			// $bawahanstr = " and 1<>1 ";

			// if ($row_dir['owner']) {

			// $bawahanarr = $this->conn->GetListStr("select id_jabatan as val
			// from risk_scorecard_user 
			// where id_scorecard = " . $this->conn->escape($row_dir['id_scorecard']));

			// }

			#notif untuk USER
			$wherearr = [];
			if (in_array($id_status_pengajuan, $this->statusaccess['pengajuan'])) {
				$bawahanarr = $this->GetChildJabatanOwner($id_jabatan);

				if (!$bawahanarr)
					$bawahanarr = [0];

				$wherearr[] = "(a.name = 'pengajuan' and j.id_jabatan in (" . implode(",", $bawahanarr) . "))";
			}
			// $where .= " and (a.name='pengajuan')";

			#notif untuk OEWNER
			if (in_array($id_status_pengajuan, $this->statusaccess['penerusan']))
				$wherearr[] = "(a.name = 'penerusan' and j.id_jabatan=" . $this->conn->escape($id_jabatan) . ")";
			// $where .= " and (a.name='penerusan')";

			#notif untuk KOORDINATOR
			if (in_array($id_status_pengajuan, $this->statusaccess['persetujuan']))
				$wherearr[] = "(a.name = 'persetujuan' and a.name='view_all_unit')";
			// $where .= " and a.name='persetujuan'";

			$where .= " and (" . implode(" or ", $wherearr) . ")";
			// $where .= " and a.name in ('" . implode("','", $namearr) . "') 
			// and (j.id_unit=" . $this->conn->escape($id_unit) . " or a.name='view_all_unit' $bawahanstr)";
		}

		$user_id = $_SESSION[SESSION_APP]['user_id'];

		$where .= " and u.user_id <> " . $this->conn->escape($user_id);

		$return = $this->conn->GetArray("select distinct j.id_jabatan, j.position_id, coalesce(p.email, u.email) as email
				from public_sys_group g
				join public_sys_group_menu gm on g.group_id=gm.group_id
				join public_sys_group_action ga on gm.group_menu_id = ga.group_menu_id
				join public_sys_menu m on gm.menu_id = m.menu_id
				join public_sys_action a on ga.action_id = a.action_id
				left join public_sys_user_group sug on g.group_id = sug.group_id
				join public_sys_user u on u.user_id = sug.user_id
				join mt_sdm_jabatan j on coalesce(sug.id_jabatan, u.id_jabatan) = j.id_jabatan
				left join mt_sdm_pegawai p on j.position_id = p.position_id and u.is_manual = '0'
				where g.deleted_date is null and m.url = 'panelbackend/risk_scorecard' 
				and u.is_notification = '1' 
				and coalesce(p.email, u.email) is not null 
				and u.is_active = '1'
				and j.tgl_akhir_efektif is null
				$where");

		// dpr($return,1);

		return $return;
	}

	public function PenerimaByStatusAudit($id_pemeriksaan, $id_status = null, $untuk = null)
	{
		$where = "";

		if ($untuk) {
			$where .= " and j.id_jabatan = " . $this->conn->escape($untuk);
		} else {
			if ($id_status == 2) { #untuk ketua
				$where .= " and m.url='panelbackend/pemeriksaan_temuan' 
				and a.name='add' 
				and exists (select 1 from pemeriksaan pks where pks.deleted_date is null and pks.id_pereview = u.user_id)";
			} elseif ($id_status == 3) { #untuk pengawas
				$where .= " and m.url='panelbackend/pemeriksaan' 
				and a.name='pengawas' 
				and exists (select 1 from pemeriksaan pks where pks.deleted_date is null and pks.id_penyusun = u.user_id)";
			} elseif ($id_status == 4) { #untuk penanggungjawab
				$where .= " and m.url='panelbackend/pemeriksaan' 
				and a.name='penanggungjawab'";
			} elseif ($id_status == 5) { #untuk auditee
				$where .= " and m.url='panelbackend/pemeriksaan_tindak_lanjut' 
				and a.name='add' 
				and exists (select 1 from pemeriksaan pks where pks.deleted_date is null and pks.id_unit = j.id_unit)";
			} elseif ($id_status == 6 || $id_status == 7) { #untuk pelaporan
				$where .= " and m.url='panelbackend/pemeriksaan' 
				and a.name='go_print_monev'";
			}
		}

		$user_id = $_SESSION[SESSION_APP]['user_id'];

		$where .= " and u.user_id <> " . $this->conn->escape($user_id);
		// $this->conn->debug = 1;
		$return = $this->conn->GetArray("select distinct j.id_jabatan, j.position_id, coalesce(p.email, u.email) as email
				from public_sys_group g
				join public_sys_group_menu gm on g.group_id=gm.group_id
				join public_sys_group_action ga on gm.group_menu_id = ga.group_menu_id
				join public_sys_menu m on gm.menu_id = m.menu_id
				join public_sys_action a on ga.action_id = a.action_id
				join public_sys_user u on g.group_id = u.group_id
				left join public_sys_user_group sug on u.user_id = sug.user_id
				join mt_sdm_jabatan j on coalesce(sug.id_jabatan, u.id_jabatan) = j.id_jabatan
				left join mt_sdm_pegawai p on j.position_id = p.position_id and u.is_manual = '0'
				where u.is_notification = '1' 
				and coalesce(p.email, u.email) is not null 
				and u.is_active = '1'
				and g.deleted_date is null and j.tgl_akhir_efektif is null
				$where");

		// dpr($return,1);

		return $return;
	}


	public function GetTask()
	{
		$sql = $this->SqlTask();

		$sql_content = "select * from (select 
		t.id_task,
		ifnull(s.nama, o.nama) as nama_scorecard, 
		t.id_status_pengajuan, 
		pks.nama as nama_pemeriksaan, 
		t.url, 
		t.status,
		t.deskripsi, 
		t.page,
		u.name as nama_user, 
		g.name as nama_group, 
		date_format(t.created_date,'%Y-%m-%d %T:%i:%s') as created_date, 
		date_format(sysdate(),'%Y-%m-%d %T:%i:%s') as n 
		" . $sql . " order by id_task desc) a limit 10";

		// echo $sql_content;
		// die();

		$rows = $this->conn->GetArray($sql_content);

		/*
		1:draft
		2:diajukan ke owner
		3:diteruskan ke reviewer
		4:dikembalikan
		5:disetujui
		6:menunggu konfirmasi
		*/
		$iconarr = array(
			'' => "flag",
			'1' => "short-text",
			'2' => "flag",
			'3' => "flag",
			'4' => "backspace",
			'5' => "done",
			'6' => "flag",
		);

		$bgarr = array(
			'' => "warning",
			'1' => "dark",
			'2' => "warning",
			'3' => "warning",
			'4' => "danger",
			'5' => "success",
			'6' => "warning",
		);

		$iconarr1 = array(
			'' => "flag",
			'1' => "backspace",
			'2' => "backspace",
			'3' => "flag",
			'4' => "flag",
			'5' => "flag",
			'6' => "check",
			'7' => "done",
		);

		$bgarr1 = array(
			'' => "warning",
			'1' => "danger",
			'2' => "danger",
			'3' => "warning",
			'4' => "warning",
			'5' => "warning",
			'6' => "success",
			'7' => "primary",
		);

		$content = array();
		foreach ($rows as $r) {

			if ($r['page'] == 'pemeriksaan') {
				if ((int)$r['status'] == 0)
					$info = "<b>" . ($r['nama_pemeriksaan'] ?  $r['nama_pemeriksaan'] . "<br/>" : "") . "<i>" . $r['deskripsi'] . "</i></b>";
				else
					$info = ($r['nama_pemeriksaan'] ? $r['nama_pemeriksaan'] . "<br/>" : "") . "<i>" . $r['deskripsi'] . "</i>";

				$content[] = array(
					'bg' => $bgarr1[$r['id_status_pengajuan']],
					'icon' => $iconarr1[$r['id_status_pengajuan']],
					'info' => $info,
					'time' => waktu_lalu($r['created_date'], $r['n']),
					'url' => "panelbackend/home/task/$r[id_task]",
					'user' => ucwords(strtolower($r['nama_user'])) . " (" . ucwords(strtolower($r['nama_group'])) . ")"
				);
			} else {
				if ($r['page'] == 'rtm_uraian') {
					$r['nama_scorecard'] = "RTM Baru";
				}
				if ((int)$r['status'] == 0)
					$info = "<b>" . ($r['nama_scorecard'] ?  $r['nama_scorecard'] . "<br/>" : "") . "<i>" . $r['deskripsi'] . "</i></b>";
				else
					$info = ($r['nama_scorecard'] ? $r['nama_scorecard'] . "<br/>" : "") . "<i>" . $r['deskripsi'] . "</i>";

				$content[] = array(
					'bg' => $bgarr[$r['id_status_pengajuan']],
					'icon' => $iconarr[$r['id_status_pengajuan']],
					'info' => $info,
					'time' => waktu_lalu($r['created_date'], $r['n']),
					'url' => "panelbackend/home/task/$r[id_task]",
					'user' => ucwords(strtolower($r['nama_user'])) . " (" . ucwords(strtolower($r['nama_group'])) . ")"
				);
			}
		}

		$sql_count = "select count(1) " . $sql . " and t.status = '0' ";

		$count = $this->conn->GetOne($sql_count);


		$data = array(
			'count' => $count,
			'content' => $content
		);

		return $data;
	}

	public function GetScorecard($id_risiko)
	{
		return $this->conn->GetRow("select s.owner, r.id_scorecard, s.id_unit
				from risk_risiko r
				join risk_scorecard s on r.id_scorecard = s.id_scorecard
				where r.deleted_date is null and r.id_risiko=" . $this->conn->escape($id_risiko));
	}


	public function GetParentJabatan($idjabatan)
	{
		if ($idjabatan) {
			$owner = $this->conn->GetOne("select 
			owner
			from risk_scorecard
			where deleted_date is null and owner = " . $this->conn->escape($idjabatan));

			if (!$owner) {
				$idjabatan = $this->conn->GetOne("select id_jabatan_parent 
					from mt_sdm_jabatan
					where deleted_date is null and id_jabatan = " . $this->conn->escape($idjabatan));

				$idjabatan = $this->GetParentJabatan($idjabatan);
			}
		}

		return $idjabatan;
	}

	public function GetChildJabatan($idjabatan = null)
	{
		$jabatan = array();

		if ($idjabatan) {
			$jabatan[] = $idjabatan;


			$rowschild = $this->conn->GetArray("select id_jabatan 
				from mt_sdm_jabatan
				where deleted_date is null and id_jabatan_parent = " . $this->conn->escape($idjabatan));

			foreach ($rowschild as $r) {
				$ret1 = $this->GetChildJabatan($r['id_jabatan']);

				$jabatan[] = $r['id_jabatan'];
				$jabatan = array_merge($jabatan, $ret1);
			}
		}

		return $jabatan;
	}
	public function GetChildJabatanOwner($idjabatan = null)
	{
		$jabatan = array();

		if ($idjabatan) {
			$rowschild = $this->conn->GetArray("select id_jabatan 
				from mt_sdm_jabatan
				where deleted_date is null and id_jabatan_parent = " . $this->conn->escape($idjabatan));

			foreach ($rowschild as $r) {
				$cek = $this->conn->GetOne("select 1 
				from public_sys_user_group a 
				where exists (select 1 
					from public_sys_group_menu b 
					join public_sys_group_action c on b.group_menu_id = c.group_menu_id 
					join public_sys_action d on c.action_id = d.action_id
					where  b.group_id = a.group_id and d.name = 'pengajuan') 
				and b.deleted_date is null and id_jabatan = " . $this->conn->escape($r['id_jabatan']));

				if ($cek)
					$jabatan[] = $r['id_jabatan'];
			}

			if (!count($jabatan)) {
				foreach ($rowschild as $r) {
					$ret1 = $this->GetChildJabatanOwner($r['id_jabatan']);

					$jabatan[] = $r['id_jabatan'];
					$jabatan = array_merge($jabatan, $ret1);
				}
			}
		}

		return $jabatan;
	}

	public function GetChildScorecard($idscorecard = null)
	{
		$scorecard = array();

		if ($idscorecard) {
			$scorecard[] = $idscorecard;


			$rowschild = $this->conn->GetArray("select id_scorecard 
				from risk_scorecard
				where deleted_date is null and ID_PARENT_SCORECARD = " . $this->conn->escape($idscorecard));

			foreach ($rowschild as $r) {
				$ret1 = $this->GetChildScorecard($r['id_scorecard']);

				$scorecard[] = $r['id_scorecard'];
				$scorecard = array_merge($scorecard, $ret1);
			}
		}

		return $scorecard;
	}

	public function GetSideBar(
		$idparent = null,
		$data = null,
		$ul = "<ul class=\"nav flex-column\">",
		&$child_active = '',
		$fulluri = null
	) {


		if ($idparent === 0)
			return null;

		// $this->conn->debug = 1;
		if (!$data) {
			$start = true;
			$data = $this->GetMenuArr($idparent);
		}

		// var_dump($idparent);
		// var_dump($data);
		// die;

		$ret = "";
		if ($data) {

			if (!$fulluri)
				$fulluri = current_url();

			$ret .= "\n $ul \n ";
			foreach ($data as $row) {

				$url = $row['url'];
				// if (!$ischild)
				$icon = $row['icon'];

				if (!$icon)
					$icon = "folder";

				$active = "";
				$arr = array();
				// if (strstr($url, "pemeriksaan") === false)
				// 	$arr = array('/index', '/detail', '/edit', '/add', '/daftar');

				// if (strstr($url, "mt_kriteria") === false)
				// 	$arr = array('/index', '/detail', '/edit', '/add', '/daftar');

				$str = $fulluri;

				foreach ($arr as $v) {

					$tempstr = $str;
					$str = strstr($str, $v, true);

					if (!$str)
						$str = $tempstr;
				}

				$sub_pr = array_keys($this->_subRisiko());
				$str = str_replace($sub_pr, 'risk_scorecard', $str);

				$str = str_replace([
					"spi_sasaran", "spi_renbis", "spi_pkpt", "spi_rka",
					"spi_peta_audit"
				], 'spi_profil', $str);

				$find = $url;

				foreach ($arr as $v) {

					$tempstr = $find;
					$find = strstr($find, $v, true);

					if (!$find)
						$find = $tempstr;
				}

				if (!$find)
					$find = $url;

				if (trim(strtolower($str)) == trim(strtolower($find)))
					$child_active = $active = "active";

				$child_active1 = '';
				$sub = '';
				if (($row['sub']))
					$sub = $this->GetSideBar(null, $row['sub'], "", $child_active1, $fulluri);

				if ($sub) {
					if ($child_active1)
						$child_active = $child_active1;

					if ($child_active1)
						$ret .= "<li class=\"nav-item $child_active1\">\n";
					else
						$ret .= "<li class=\"nav-item collapsed $child_active1\">\n";

					$ret .= "<a href='" . $url . "' class='nav-link  $active'>\n";
					// $ret .= "<i class='material-icons icon-material-icon'>$icon</i>\n";
					if (strstr($sub, "<span class='item-menu-expanded-new'>-</span>") !== false) {
						$ret .= "<span class='item-menu-expanded-new'><b style='color:#4f9fc4'>" . $row['label'] . "</b></span>\n";
					} else {
						$ret .= "<span class='item-menu-expanded-new'><b>" . $row['label'] . "</b></span>\n";
					}
					$ret .= "</a>\n";
					// if (strstr($sub, "<span class='item-menu-expanded-new'>-</span>") === false) {
					if ($child_active1)
						$ret .= "<ul class=\"nav flex-column\">";
					else
						$ret .= "<ul class=\"nav flex-column collapsed\">";

					$ret .= $sub;
					// }
					// $ret .= "<hr/>";
				} else {
					$ret .= "<li class=\"nav-item\">\n";
					$ret .= "<a href='" . $url . "' class='nav-link  $active'>\n";
					$ret .= "<i class='material-icons icon-material-icon'>$icon</i>\n";
					$ret .= "<span class='item-menu-expanded-new'>" . $row['label'] . "</span>\n";
					$ret .= "</a>\n";
				}
				$ret .= "</li>\n";
			}

			if ($start) {
				$ret .= '';
				// $ret.='<li><hr/>
				// <div class="copyright oke">
				// 	<small>
				// 		<center>'.config_item('copyright').'</center>
				// 	</small>
				// </div><br/></li>';
			}

			$ret .= "</ul>";
		}
		// $ret .= "<hr/>";
		return $ret;
	}

	public function GetParentMenu($url, $parent_id = null)
	{
		if ($url) {
			$rowmenu = $this->conn->GetRow("select a.* 
		from public_sys_menu a 
		join public_sys_menu b on b.parent_id = a.menu_id
		where a.deleted_date is null and b.url = " . $this->conn->escape($url) . " 
		and b.visible = '1'");
			if (!$rowmenu) {
				$url = "main";
				$rowmenu = $this->conn->GetRow("select a.* 
				from public_sys_menu a 
				where a.deleted_date is null and a.url = " . $this->conn->escape($url));
			}
		} else
			$rowmenu = $this->conn->GetRow("select a.* 
	from public_sys_menu a 
	where a.deleted_date is null and a.menu_id = " . $this->conn->escape($parent_id) . " 
	and a.visible = 1");

		if ($rowmenu['parent_id']) {
			$rowmenu = $this->GetParentMenu(null, $rowmenu['parent_id']);
		}


		return $rowmenu;
	}

	public function GetMenu($data = null, $ul = "<ul class=\"navbar-nav me-auto mb-2 mb-md-0\">", &$child_active = '', $ischild = false)
	// public function GetMenu($data = null, $ul = "<ul class=\"list\"> <li class=\"header\">MAIN NAVIGATION</li>", &$child_active = '', $ischild = false)
	{



		if (!$data) {
			$start = true;
			// $this->conn->debug = 1;
			$data = $_SESSION[SESSION_APP]['menu'];
			// $data = $this->GetMenuArr(null, false);

			// dpr($data,1);
		}

		$ret = "";
		if ($data) {
			$fulluri = current_url();
			$ret .= "\n $ul \n ";
			foreach ($data as $row) {

				$url = $row['url'];
				if (!$ischild)
					$icon = $row['icon'];

				$active = "";
				$arr = array('/index', '/detail', '/edit', '/add', '/daftar');
				$str = $fulluri;

				foreach ($arr as $v) {

					$tempstr = $str;
					$str = strstr($str, $v, true);

					if (!$str)
						$str = $tempstr;
				}

				$sub_pr = array_keys($this->_subRisiko());
				$str = str_replace($sub_pr, 'risk_scorecard', $str);

				$find = $url;

				foreach ($arr as $v) {

					$tempstr = $find;
					$find = strstr($find, $v, true);

					if (!$find)
						$find = $tempstr;
				}

				if (!$find)
					$find = $url;


				if (trim(strtolower($str)) == trim(strtolower($find)))
					$child_active = $active = "active";

				$child_active1 = '';
				$sub = '';
				if (($row['sub']))
					$sub = $this->GetMenu($row['sub'], "<ul class=\"ml-menu\">", $child_active1, 1);


				// if ($child_active1)
				// dpr($child_active1);


				if ($sub) {
					if ($child_active1)
						$child_active = $child_active1;

					$ret .= "<li class=\"nav-item $child_active1\">\n";
					$ret .= "<a class=\"nav-link menu-toggle $child_active1\" href='" . $url . "' data-nama-menu='" . $row['label'] . "'>" . $row['label'] . "</a>\n";
					$ret .= $sub;
				} else {
					$ret .= "<li class=\"nav-item $active\">\n";
					$ret .= "<a class=\"nav-link $active\" href='" . $url . "' data-nama-menu='" . $row['label'] . "'>" . $row['label'];

					if (trim($row['label']) == 'Risk Register' && !$active)
						$ret .= '&nbsp;&nbsp;<span class="bi bi-arrow-left tunjuk"></span>';

					$ret .= "</a>\n";
				}
				$ret .= "</li>\n";
			}

			if ($start) {
				$ret .= '';
				// $ret.='<li><hr/>
				// <div class="copyright oke">
				// 	<small>
				// 		<center>'.config_item('copyright').'</center>
				// 	</small>
				// </div><br/></li>';
			}

			$ret .= "</ul>";
		}
		return $ret;
	}

	private function _subRisiko($id_scorecard = null, $id_risiko = null, $is_monitoring = false)
	{
		$ci = get_instance();
		$rowheader = $ci->data['rowheader1'];
		$rowheader0 = $ci->data['rowheader'];
		// dpr($rowheader0, 1);
		if ($id_risiko) {
			$return = [
				"risk_assessment" => [
					"label" => "Penilaian Risiko",
					"sub" => [
						"risk_risiko" => array("url" => site_url("panelbackend/risk_risiko/detail/$id_scorecard/$id_risiko"), "class" => "done", "label" => "Identifikasi"),
						"risk_analisis" => array("url" => site_url("panelbackend/risk_analisis/detail/$id_risiko"), "label" => "Analisis", 'class' => 'done'),
						// "risk_evaluasi" => array("url" => site_url("panelbackend/risk_evaluasi/detail/$id_risiko"), "label" => "Evaluasi", 'class' => 'done'),
						// "risk_control" => array("url" => site_url("panelbackend/risk_control/index/$id_risiko"), "label" => "Analisis", 'class' => 'done'),
					]
				],
				"risk_penanganan" => array("url" => site_url("panelbackend/risk_penanganan/detail/$id_risiko"), "label" => "Rencana Tindak Pengendalian", 'class' => 'done'),
				"risk_monitoring_bulanan" => array("url" => site_url("panelbackend/risk_monitoring_bulanan/detail/$id_risiko"), "label" => "Monitoring", 'class' => 'done'),
				"risk_review" => array("url" => site_url("panelbackend/risk_review/index/$id_risiko"), "label" => "Diskusi & Riwayat", 'class' => 'done'),
			];
			// if (!$rowheader['is_signifikan_current']) {
			// 	unset($return['risk_penanganan']);
			// 	// unset($return['risk_monitoring_bulanan']);
			// } else 

			if ($rowheader0['id_status_pengajuan'] == 1 || !$rowheader['is_lock']) {
				unset($return['risk_monitoring_bulanan']);
				// unset($return['risk_assessment']['sub']['risk_evaluasi']);
				// $return['risk_assessment']['sub']['risk_monitoring_tahunan'] = array("url" => site_url("panelbackend/risk_monitoring_tahunan/detail/$id_scorecard/$id_risiko"), "label" => "Evaluasi", 'class' => 'done');
			}
		} else {
			$return = [
				"risk_assessment" => [
					"label" => "Penilaian Risiko",
					"sub" => [
						"risk_risiko" => array("url" => site_url("panelbackend/risk_risiko/index/$id_scorecard"), "class" => "done", "label" => "Identifikasi"),
					]
				]
			];
		}

		return $return;
	}

	public function GetTabScorecard($id = null, $id_risiko = null, $is_monitoring = false, $is_notab = true)
	{
		$ci = get_instance();
		$rowheader = $ci->data['rowheader1'];
		$ret = "";
		// if ($is_notab)
		// 	return null;

		$data = array(
			"risk_kegiatan" => array("url" => site_url("panelbackend/risk_kegiatan/index/$id"), "class" => "done", "label" => "Daftar Kegiatan"),
			"risk_risiko" => array("url" => site_url("panelbackend/risk_risiko/index/$id"), "class" => "done", "label" => "Daftar Risiko")
		);

		if (!$is_notab) {
			$data = $data + $this->_subRisiko($id, $id_risiko, $is_monitoring);
		}

		$fulluri = current_url();

		$ret .= "<ul class=\"nav flex-column\">";

		// if (!$is_notab) {
		// 	$ret .= "<li class=\"nav-item\">\n";
		// 	$ret .= "<a href='" . site_url("panelbackend/risk_risiko/index/" . $rowheader['id_scorecard']) . "' class='nav-link'>\n";
		// 	$ret .= "<i class='material-icons icon-material-icon'>arrow_back</i> Daftar Risiko/Peluang</a>\n";
		// 	$ret .= "</li>\n";
		// }

		$i = 0;
		foreach ($data as $k => $row) {

			$active = "";

			if ($row['sub']) {
				$row['url'] = $row['sub'][array_keys($row['sub'])[0]]['url'];
				$url = trim($row['url'], "/");
				$ret .= "<li class=\"nav-item\">\n";
				$ret .= "<a href='" . $url . "' class='nav-link  $active'>\n";
				$ret .= "<i class='material-icons icon-material-icon'>description</i>";
				$ret .= $row['label'];
				$ret .= "</a>\n";
				$ret .= "<ul class=\"nav flex-column\">";
				foreach ($row['sub'] as $r) {
					$active = "";
					$url = trim($r['url'], "/");

					$str = str_replace(array('edit', 'detail', 'index', 'add'), '', $fulluri);

					$find = str_replace(array('edit', 'detail', 'index', 'add'), '', $url);
					list($http, $find, $buang) = explode("//", $find);

					if ($r['class'] == "disabled")
						$active = "disabled";

					if (strpos($str, $find) !== false && !(strpos($str, "proses") !== false && $k == 'risk_risiko'))
						$active = "active";

					if ($fulluri == $url)
						$active = "active";

					$ret .= "<li class=\"nav-item\">\n";
					$ret .= "<a href='" . $url . "' class='nav-link  $active'>\n";
					$ret .= "<i class='material-icons icon-material-icon'>chevron_right</i>";
					$ret .= $r['label'];
					$ret .= "</a>\n";
					$ret .= "</li>\n";
				}
				$ret .= "</ul>";
				$ret .= "</li>\n";
			} else {
				$url = trim($row['url'], "/");

				$str = str_replace(array('edit', 'detail', 'index', 'add'), '', $fulluri);

				$find = str_replace(array('edit', 'detail', 'index', 'add'), '', $url);
				list($http, $find, $buang) = explode("//", $find);

				if ($row['class'] == "disabled")
					$active = "disabled";

				if (strpos($str, $find) !== false && !(strpos($str, "proses") !== false && $k == 'risk_risiko'))
					$active = "active";

				if ($fulluri == $url)
					$active = "active";

				$ret .= "<li class=\"nav-item\">\n";
				$ret .= "<a href='" . $url . "' class='nav-link  $active'>\n";
				$ret .= "<i class='material-icons icon-material-icon'>description</i>";
				$ret .= $row['label'];
				$ret .= "</a>\n";
				$ret .= "</li>\n";
			}
			$i++;
		}

		$ret .= "</ul>";
		return $ret;
	}

	public function GetTabProfilSpi()
	{
		$ci = get_instance();
		$find = $ci->page_ctrl;
		$ret = "";
		// if ($is_notab)
		// 	return null;
		$data = array(
			"panelbackend/spi_profil/detail/spi_visi_misi" => array(
				"url" => site_url("panelbackend/spi_profil/detail/spi_visi_misi"),
				"class" => "done",
				"label" => "Visi & Misi, Core Values, Code of Ethics "
			),
			"panelbackend/spi_sasaran" => array(
				"url" => site_url("panelbackend/spi_sasaran"),
				"class" => "done",
				"label" => "Tujuan, Sasaran, Strategi pengawasan"
			),
			"panelbackend/spi_profil/detail/spi_piagam_audit_internal" => array(
				"url" => site_url("panelbackend/spi_profil/detail/spi_piagam_audit_internal"),
				"class" => "done",
				"label" => "Piagam Audit Internal"
			),
			"panelbackend/spi_profil/detail/spi_struktur_organisasi" => array(
				"url" => site_url("panelbackend/spi_profil/detail/spi_struktur_organisasi"),
				"class" => "done",
				"label" => "Struktur Organisasi dan Tugas Fungsi"
			),
			"panelbackend/spi_profil_pengawas" => array(
				"url" => site_url("panelbackend/spi_profil_pengawas"),
				"class" => "done",
				"label" => "Profil Pengawas"
			),
			"panelbackend/spi_profil/detail/spi_achievement" => array(
				"url" => site_url("panelbackend/spi_profil/detail/spi_achievement"),
				"class" => "done",
				"label" => "Achievement"
			),
			"panelbackend/spi_peta_audit" => array(
				"url" => site_url("panelbackend/spi_peta_audit"),
				"class" => "done",
				"label" => "Audit Universe"
			),
			"panelbackend/spi_renbis" => array(
				"url" => site_url("panelbackend/spi_renbis"),
				"class" => "done",
				"label" => "Rencana SPI Jangka Panjang"
			),
			"panelbackend/spi_pkpt" => array(
				"url" => site_url("panelbackend/spi_pkpt"),
				"class" => "done",
				"label" => "Rencana Kerja Audit Tahunan"
			),
			"panelbackend/spi_rka" => array(
				"url" => site_url("panelbackend/spi_rka"),
				"class" => "done",
				"label" => "Rencana Kerja Anggaran"
			),

		);

		if ($find == 'panelbackend/spi_profil')
			$find .= "/detail/" . $ci->data['page'];

		$fulluri = current_url();

		$ret .= "<ul class=\"nav flex-column\">";

		$i = 0;
		foreach ($data as $k => $row) {

			$active = "";

			$url = trim($row['url'], "/");

			if (strpos($url, $find) !== false)
				$active = "active";

			if ($fulluri == $url)
				$active = "active";

			$ret .= "<li class=\"nav-item\">\n";
			$ret .= "<a href='" . $url . "' class='nav-link  $active'>\n";
			$ret .= "<i class='material-icons icon-material-icon'>description</i>";
			$ret .= $row['label'];
			$ret .= "</a>\n";
			$ret .= "</li>\n";
			$i++;
		}

		$ret .= "</ul>";
		return $ret;
	}
	public function GetTabProfilkpi()
	{
		$ci = get_instance();
		$find = $ci->page_ctrl;
		$ret = "";
		// if ($is_notab)
		// 	return null;
		$data = array(
			"panelbackend/kpi_profil/detail/kpi_visi_misi_corevalues" => array(
				"url" => site_url("panelbackend/kpi_profil/detail/kpi_visi_misi_corevalues"),
				"class" => "done",
				"label" => "Visi, Misi, Core Values, & Arah Kebijakan"
			),
			"panelbackend/kpi_profil/detail/sasaran_target_strategi_kpi_perusahaan" => array(
				"url" => site_url("panelbackend/kpi_profil/detail/sasaran_target_strategi_kpi_perusahaan"),
				"class" => "done",
				"label" => "Sasaran, Indikasi Program, Target Perusahaan"
			),
			"panelbackend/kpi_profil/detail/sasaran_unit_kerja" => array(
				"url" => site_url("panelbackend/sasaran_unit_kerja"),
				"class" => "done",
				"label" => "Sasaran, Indikasi Program, Target Unit Kerja"
			),
			"panelbackend/kpi_profil/detail/kontrak_kinerja_dewan_pengawas" => array(
				"url" => site_url("panelbackend/kpi_profil/detail/kontrak_kinerja_dewan_pengawas"),
				"class" => "done",
				"label" => "Kontrak Kinerja Dewan Pengawas"
			),
			"panelbackend/kpi_profil/detail/kontrak_kinerja_direksi" => array(
				"url" => site_url("panelbackend/kpi_profil/detail/kontrak_kinerja_direksi"),
				"class" => "done",
				"label" => "Kontrak Kinerja Direksi"
			),

			"panelbackend/kpi_profil/detail/kpi_perusahaan" => array(
				"url" => site_url("panelbackend/kpi_profil/detail/kpi_perusahaan"),
				"class" => "done",
				"label" => "KPI Perusahaan"
			),
			"panelbackend/risk_kpi" => array(
				"url" => site_url("panelbackend/risk_kpi/"),
				"class" => "done",
				"label" => "KPI Unit Kerja"
			),
			"panelbackend/kpi_individu" => array(
				"url" => site_url("panelbackend/kpi_individu/"),
				"class" => "done",
				"label" => "KPI Individu"
			),
			// "panelbackend/risk_kpi" => array(
			// 	"url" => site_url("panelbackend/risk_kpi/"),
			// 	"class" => "done",
			// 	"label" => "KPI Unit Kerja"
			// ),
		);

		if ($find == 'panelbackend/kpi_profil')
			$find .= "/detail/" . $ci->data['page'];

		$fulluri = current_url();

		$ret .= "<ul class=\"nav flex-column\">";

		$i = 0;
		foreach ($data as $k => $row) {

			$active = "";

			$url = trim($row['url'], "/");

			if (strpos($url, $find) !== false)
				$active = "active";

			if ($fulluri == $url)
				$active = "active";

			$ret .= "<li class=\"nav-item\">\n";
			$ret .= "<a href='" . $url . "' class='nav-link  $active'>\n";

			// if ($row['label'] == 'KPI Unit Kerja') {
			// 	$ret .= "&nbsp;&nbsp;&nbsp;&nbsp;<i class='material-icons icon-material-icon'>description</i>";
			// } elseif ($row['label'] == 'KPI Individu') {
			// 	$ret .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class='material-icons icon-material-icon'>description</i>";
			// } else {
			// 	$ret .= "<i class='material-icons icon-material-icon'>description</i>";
			// }

			$ret .= "<i class='material-icons icon-material-icon'>description</i>";


			$ret .= $row['label'];
			$ret .= "</a>\n";
			$ret .= "</li>\n";
			$i++;
		}

		$ret .= "</ul>";
		return $ret;
	}


	public function GetTabAssessment()
	{
		$ci = get_instance();
		$find = $ci->page_ctrl;
		$ret = "";

		$data = array(
			$find . "/index/1" => array(
				"url" => site_url($find . "/index/1"),
				"class" => "done",
				"label" => "GCG"
			),
			$find . "/index/2" => array(
				"url" => site_url($find . "/index/2"),
				"class" => "done",
				"label" => "Risk Management"
			),
			$find . "/index/3" => array(
				"url" => site_url($find . "/index/3"),
				"class" => "done",
				"label" => "IACM"
			),

		);

		if (!Access("gcg", $find)) {
			unset($data[$find . "/index/1"]);
		}
		if (!Access("risk", $find)) {
			unset($data[$find . "/index/2"]);
		}

		if ($find == "panelbackend/quisioner") {
			$data[$find . "/index/4"] = array(
				"url" => site_url($find . "/index/4"),
				"class" => "done",
				"label" => "Survey Kepuasan Auditee Per Kegiatan "
			);
			$data[$find . "/index/5"] = array(
				"url" => site_url($find . "/index/5"),
				"class" => "done",
				"label" => "Survey Kepuasan Auditee Tahunan"
			);
		}
		if (!Access("iacm", $find)) {
			unset($data[$find . "/index/3"]);
			unset($data[$find . "/index/4"]);
			unset($data[$find . "/index/5"]);
		}

		$find .= "/index/" . $ci->id_kategori;

		$fulluri = current_url();

		$ret .= "<ul class=\"nav flex-column\">";

		$i = 0;
		foreach ($data as $k => $row) {

			$active = "";

			$url = trim($row['url'], "/");

			if (strpos($url, $find) !== false)
				$active = "active";

			if ($fulluri == $url)
				$active = "active";

			$ret .= "<li class=\"nav-item\">\n";
			$ret .= "<a href='" . $url . "' class='nav-link  $active'>\n";
			$ret .= "<i class='material-icons icon-material-icon'>description</i>";
			$ret .= $row['label'];
			$ret .= "</a>\n";
			$ret .= "</li>\n";
			$i++;
		}

		$ret .= "</ul>";
		return $ret;
	}

	public function GetPemeriksaan()
	{
		$ci = get_instance();
		$find = $ci->page_ctrl;
		$method = $ci->method;
		$jenis = $ci->data['rowheader']['jenis'];
		$jenis_checklist = $ci->data['jenis_checklist'];
		$id_pemeriksaan = $ci->data['rowheader']['id_pemeriksaan'];
		$ret = "";

		$data = array(
			// "panelbackend/pemeriksaan/detail/$jenis/$id_pemeriksaan" => array(
			// 	"url" => site_url("panelbackend/pemeriksaan/detail/$jenis/$id_pemeriksaan"),
			// 	"class" => "done",
			// 	"label" => "Detail Pemeriksaan"
			// ),
			"panelbackend/pemeriksaan_detail/index/$id_pemeriksaan" => array(
				"url" => site_url("panelbackend/pemeriksaan_detail/index/$id_pemeriksaan"),
				"class" => "done",
				"label" => "KAK & Biaya Audit"
			),
			"lampiran" => array(
				"class" => "done",
				"label" => "Lampiran",
				"sub" => array(
					"panelbackend/pemeriksaan_detail/go_print/$id_pemeriksaan" => array(
						"url" => site_url("panelbackend/pemeriksaan_detail/go_print/$id_pemeriksaan"),
						"class" => "done",
						"isblank" => true,
						"label" => "Pendahuluan"
					),
					"anelbackend/pemeriksaan_checklist/index/perencanaan" => array(
						"url" => site_url("panelbackend/pemeriksaan_checklist/index/perencanaan/$id_pemeriksaan"),
						"class" => "done",
						"label" => "Checklist Perencanaan"
					),
					"panelbackend/pemeriksaan_review_supervisi" => array(
						"url" => site_url("panelbackend/pemeriksaan_review_supervisi/go_print/$id_pemeriksaan"),
						"class" => "done",
						"isblank" => true,
						"label" => "Reviu Supervisi"
					),
					// "panelbackend/pemeriksaan_lhe/index/$id_pemeriksaan" => array(
					// 	"url" => site_url("panelbackend/pemeriksaan_lhe/index/$id_pemeriksaan"),
					// 	"class" => "done",
					// 	"label" => "LHE"
					// ),
					"panelbackend/pemeriksaan_checklist/index/penyelesaian" => array(
						"url" => site_url("panelbackend/pemeriksaan_checklist/index/penyelesaian/$id_pemeriksaan"),
						"class" => "done",
						"label" => "Checklist Penyelesaian"
					),
					"panelbackend/pemeriksaan_temuan/go_print/konsep/$id_pemeriksaan" => array(
						"url" => site_url("panelbackend/pemeriksaan_temuan/go_print/konsep/$id_pemeriksaan"),
						"class" => "done",
						"isblank" => true,
						"label" => "Reviu Konsep Laporan"
					),
					"panelbackend/pemeriksaan_checklist/index/laporan" => array(
						"url" => site_url("panelbackend/pemeriksaan_checklist/index/laporan/$id_pemeriksaan"),
						"class" => "done",
						"label" => "Checklist Penyelesaian Laporan"
					),
					"panelbackend/pemeriksaan_temuan/index/$id_pemeriksaan" => array(
						"url" => site_url("panelbackend/pemeriksaan_temuan/index/$id_pemeriksaan"),
						"class" => "done",
						"label" => "Temuan & Tindak Lanjut"
					),
				)
			)
		);

		// $find .= "/index/" . $ci->id_kategori;

		$fulluri = current_url();

		if ($find == "panelbackend/pemeriksaan_checklist") {
			$find .= "/" . $method . "/" . $jenis_checklist;
		}

		$ret .= "<ul class=\"nav flex-column\">";
		$i = 0;
		foreach ($data as $k => $row) {

			$active = "";

			$url = trim($row['url'], "/");

			if (strpos($url, $find . "/") !== false)
				$active = "active";

			if ($fulluri == $url)
				$active = "active";

			$ret .= "<li class=\"nav-item\">\n";
			$ret .= "<a href='" . $url . "' class='nav-link  $active'>\n";
			$ret .= "<i class='material-icons icon-material-icon'>description</i>";
			$ret .= $row['label'];
			$ret .= "</a>\n";
			if ($row['sub'])
				foreach ($row['sub'] as $r) {
					$active = "";
					$url = trim($r['url'], "/");

					if (strpos($url, $find . "/") !== false)
						$active = "active";

					$ret .= "<li class=\"nav-item\">\n";
					$add = null;
					if ($r['isblank'])
						$add = "target='BLANK'";
					$ret .= "<a href='" . $url . "' $add class='nav-link  $active'>\n";
					$ret .= "<i class='material-icons icon-material-icon'>chevron_right</i>";
					$ret .= $r['label'];
					$ret .= "</a>\n";
					$ret .= "</li>\n";
				}
			$ret .= "</li>\n";
			$i++;
		}

		$ret .= "</ul>";
		return $ret;
	}

	public function GetTabPenilaian($id_penilaian_session = null, $id_kategori_jenis = null)
	{
		$ci = get_instance();
		$find = $ci->page_ctrl . "/" . $ci->method;
		$ret = "";
		// if ($is_notab)
		// 	return null;
		$data = [];
		if ($id_kategori_jenis == 1) {
			$data = array(
				"penilaian/index" => array(
					"url" => site_url("panelbackend/penilaian_gcg/index/$id_penilaian_session"),
					"class" => "done",
					"label" => "Penilaian"
				),
				"penilaian/rekap_aspek" => array(
					"url" => site_url("panelbackend/penilaian_gcg/rekap_aspek/$id_penilaian_session"),
					"class" => "done",
					"label" => "Rekap Aspek"
				),
				"penilaian/rekap_indikator" => array(
					"url" => site_url("panelbackend/penilaian_gcg/rekap_indikator/$id_penilaian_session"),
					"class" => "done",
					"label" => "Rekap Indikator"
				),
				"penilaian/rekap_paramater" => array(
					"url" => site_url("panelbackend/penilaian_gcg/rekap_paramater/$id_penilaian_session"),
					"class" => "done",
					"label" => "Rekap Paramater"
				),
			);
		}
		if ($id_kategori_jenis == 2) {
			$data = array(
				"penilaian/index" => array(
					"url" => site_url("panelbackend/penilaian_ml/index/$id_penilaian_session"),
					"class" => "done",
					"label" => "Penilaian"
				),
				"penilaian/simpulan" => array(
					"url" => site_url("panelbackend/penilaian_ml/simpulan/$id_penilaian_session"),
					"class" => "done",
					"label" => "Simpulan"
				),
			);
		}
		if ($id_kategori_jenis == 3) {
			$data = array(
				"penilaian/index" => array(
					"url" => site_url("panelbackend/penilaian_cl/index/$id_penilaian_session"),
					"class" => "done",
					"label" => "Penilaian"
				),
				"penilaian/simpulan" => array(
					"url" => site_url("panelbackend/penilaian_cl/simpulan/$id_penilaian_session"),
					"class" => "done",
					"label" => "Simpulan"
				),
			);
		}

		$data['penilaian/oai'] = array(
			"url" => site_url("panelbackend/penilaian_" . $ci->viewadd . "/oai/$id_penilaian_session"),
			"class" => "done",
			"label" => "Area Of Improvement"
		);

		$fulluri = current_url();

		$ret .= "<ul class=\"nav flex-column\">";

		$i = 0;
		foreach ($data as $k => $row) {

			$active = "";

			$url = trim($row['url'], "/");

			if (strpos($url, $find) !== false)
				$active = "active";

			if ($fulluri == $url)
				$active = "active";

			$ret .= "<li class=\"nav-item\">\n";
			$ret .= "<a href='" . $url . "' class='nav-link  $active'>\n";
			$ret .= "<i class='material-icons icon-material-icon'>description</i>";
			$ret .= $row['label'];
			$ret .= "</a>\n";
			$ret .= "</li>\n";
			$i++;
		}

		$ret .= "</ul>";
		return $ret;
	}

	private function _subPeluang($id_scorecard = null, $id_peluang = null, $finish = false)
	{

		if ($id_peluang) {
			$return = array(
				"opp_peluang" => array("url" => site_url("panelbackend/opp_peluang/detail/$id_scorecard/$id_peluang"), "class" => "done", "label" => "Identifikasi"),
			);

			if ($finish) {
				$return += array(
					"opp_evaluasi" => array("url" => site_url("panelbackend/opp_evaluasi/detail/$id_scorecard/$id_peluang"), "label" => "Evaluasi", 'class' => 'done')
				);
			} else {
				$return += array(
					"opp_evaluasi" => array("url" => site_url("panelbackend/opp_evaluasi/detail/$id_scorecard/$id_peluang"), "label" => "Evaluasi", 'class' => 'done')
				);
			}
		} else {
			$return = array(
				"opp_peluang" => array("url" => site_url("panelbackend/opp_peluang/index/$id_scorecard"), "class" => "done", "label" => "Identifikasi"),
				"opp_evaluasi" => array("url" => site_url("panelbackend/opp_evaluasi/detail/$id_scorecard/$id_peluang"), "label" => "Evaluasi", 'class' => 'disabled')
			);
		}

		return $return;
	}

	public function GetTabScorecardPeluang($id = null, $id_peluang = null, $is_finish = false, $is_notab = true)
	{
		$ret = "";
		if ($is_notab)
			return null;

		if (!$is_notab) {
			$data = $this->_subPeluang($id, $id_peluang, $is_finish);
		} else {
			$data = array(
				"opp_peluang" => array("url" => site_url("panelbackend/opp_peluang/index/$id"), "class" => "done", "label" => "Peluang")
			);
		}

		$fulluri = current_url();

		$ret .= "<ul class=\"nav flex-column\">";
		$i = 0;
		foreach ($data as $k => $row) {

			$url = trim($row['url'], "/");

			$active = "";

			$str = str_replace(array('edit', 'detail', 'index', 'add'), '', $fulluri);

			$find = str_replace(array('edit', 'detail', 'index', 'add'), '', $url);
			list($http, $find, $buang) = explode("//", $find);

			if ($row['class'] == "disabled")
				$active = "disabled";

			if (strpos($str, $find) !== false && !(strpos($str, "proses") !== false && $k == 'opp_peluang'))
				$active = "active";

			if ($fulluri == $url)
				$active = "active";

			$ret .= "<li class=\"nav-item\">\n";
			$ret .= "<a href='" . $url . "' class='nav-link  $active'>\n";
			$ret .= "<i class='material-icons icon-material-icon'>description</i>\n";
			$ret .= $row['label'];
			$ret .= "</a>\n";
			$ret .= "</li>\n";
			$i++;
		}

		$ret .= "</ul>";
		return $ret;
	}

	private function GetMenuArr($parent_id = null, $is_getchild = true)
	{
		$group_id = $_SESSION[SESSION_APP]['group_id'];
		$user_id = $_SESSION[SESSION_APP]['user_id'];
		$filter = ($parent_id == null) ? 'b.parent_id is null' : 'b.parent_id = ' . $parent_id;
		if (!$is_getchild) {
			$filter .= " and coalesce(url,'') <> 'main'";
		}
		if ($user_id == 1) {
			$strSQL = " SELECT b.*
						FROM public_sys_menu b
						WHERE b.deleted_date is null and visible = '1' and $filter
						ORDER BY b.sort";
		} else {
			$filter .= " and a.group_id =" . $group_id;
			$strSQL = "	SELECT b.*
						FROM public_sys_group_menu a
						LEFT JOIN public_sys_menu b ON a.menu_id = b.menu_id
						WHERE b.deleted_date is null and b.visible = '1' and $filter
						ORDER BY b.sort";
		}
		$data = $this->GetArray($strSQL);

		$ret = array();
		if ($data) {
			foreach ($data as $row) {
				//if($row['label']=='Setting')
				//	$ret=array_merge($ret,$this->GetMenuCmsArr());

				$url = '#';
				if ($row['url'] != '') {
					$url = base_url($row['url']);
				}

				$icon = 'folder';
				if ($row['iconcls']) {
					$icon = $row['iconcls'];
				}

				$sub = array();
				$sub = $this->GetMenuArr($row['menu_id'], $is_getchild);

				if ($is_getchild)
					$ret[] = array(
						"label" => $row["label"],
						"icon" => $icon,
						"url" => $url,
						"sub" => $sub,
					);
				else {
					if ($url == "#")
						$url = $sub[0]['url'];

					$ret[] = array(
						"label" => $row["label"],
						"icon" => $icon,
						"url" => $url,
					);
				}
			}
		}
		return $ret;
	}

	private function GetMenuCmsArr($parent = false)
	{
		if (!$parent) {
			$param = "where parent_halaman is null and deleted_date is null and";
		} else {
			$param = "where parent_halaman = '$parent' and deleted_date is null and";
		}
		$data = $this->GetArray("select * from contents_page_halaman $param order by urutan");

		$ret = array();
		if ($data) {

			foreach ($data as $idkey => $value) {
				$icon = 'file';

				$sub = array();
				# code...
				switch ($value['jenis']) {
					case 1:
						$sub = $this->GetMenuCmsArr($value['halaman']);
						$url = "#";
						break;
					case 2:
						$url = site_url("panelbackend/pageone/index/{$value['halaman']}");
						break;
					case 3:
						$url = site_url("panelbackend/page/index/{$value['halaman']}");;
						break;
					case 4:
						$url = site_url("panelbackend/{$value['halaman']}");
						break;
				}

				$ret[] = array(
					"label" => $value['nama'],
					"icon" => $icon,
					"url" => $url,
					"sub" => $sub,
				);
			}
		}
		return $ret;
	}

	public function GetAction($url, $type)
	{
		$group_id = $_SESSION[SESSION_APP]['group_id'];
		$user_id = $_SESSION[SESSION_APP]['user_id'];
		if ($user_id == 1) {
			$strSQL = "
				SELECT b.name
				from public_sys_action b
				LEFT JOIN public_sys_menu d ON b.menu_id=d.menu_id
				WHERE b.deleted_date is null and type = '$type' and b.visible = '1' AND d.url='$url'";
		} else {
			$strSQL = "
				SELECT b.name
				FROM public_sys_group_action a
				LEFT JOIN public_sys_action b ON a.action_id=b.action_id
				LEFT JOIN public_sys_group_menu c ON a.group_menu_id=c.group_menu_id
				LEFT JOIN public_sys_menu d ON c.menu_id=d.menu_id
				WHERE a.deleted_date is null and type = '$type'  and b.visible = '1' AND c.group_id = $group_id AND d.url='$url'";
		}

		$respons = $this->GetArray($strSQL);
		$respon = array();
		foreach ($respons as $row) {
			$respon[] = $row['name'];
		}
		return $respon;
	}

	public function GetAccessRole($url = "")
	{
		$user_id = $_SESSION[SESSION_APP]['user_id'];
		$group_id = $_SESSION[SESSION_APP]['group_id'];
		$menu_id = $this->conn->GetOne("select menu_id from public_sys_menu where deleted_date is null and url = " . $this->conn->escape($url));
		/*	$menu_id1 = $this->conn->GetOne("select menu_id from public_sys_menu where url = 'panelbackend/risk_scorecard'");*/
		$return = array();

		if ($user_id == 1) {
			$sql = "
			SELECT
			    /*coalesce(b.name,'index') as name*/
			    coalesce(b.name,'index') as name
			FROM
			    public_sys_group_menu c
			        left join
			    public_sys_group_action a ON a.group_menu_id = c.group_menu_id
			        LEFT JOIN
			    public_sys_action b ON a.action_id = b.action_id
			WHERE c.menu_id='$menu_id'";
		} else {
			if ($group_id) {
				$sql = "
			SELECT
			    /*coalesce(b.name,'index') as name*/
			    coalesce(b.name,'index') as name
			FROM
			    public_sys_group_menu c
			        left join
			    public_sys_group_action a ON a.group_menu_id = c.group_menu_id
			        LEFT JOIN
			    public_sys_action b ON a.action_id = b.action_id
			WHERE c.group_id = '$group_id' AND c.menu_id='$menu_id'";
			} else {
				$sql = "
			SELECT
			    /*coalesce(b.name,'index') as name*/
			    coalesce(b.name,'index') as name
			FROM
			    public_sys_group_menu c
			        left join
			    public_sys_group_action a ON a.group_menu_id = c.group_menu_id
			        LEFT JOIN
			    public_sys_action b ON a.action_id = b.action_id
			WHERE /*c.group_id = '$group_id' AND*/
			 c.menu_id='$menu_id'";
			}
		}

		$data = $this->GetArray($sql);
		foreach ($data as $idkey => $value) {
			# code...
			$return[$value['name']] = 1;
		}

		if ($return['index'] && $url == 'panelbackend/risk_scorecard')
			$return['daftarscorecard'] = 1;

		if (($return)) {

			$return['index'] = 1;
			$return['view_all'] = $_SESSION[SESSION_APP]['view_all'];
			$return['detail'] = 1;
			$return['lst'] = 1;
			$return['reset'] = 1;
			$return['preview_file'] = 1;
			$return['open_file'] = 1;
			$return['preview'] = 1;
			$return['print'] = 1;
			$return['selesai'] = 1;
			$return['go_print'] = 1;
			$return['printdetail'] = 1;
			$return['detail_risiko'] = 1;
			$return['detail_peluang'] = 1;
			$return['open_filem'] = 1;
			$return['upload_filem'] = 1;
			$return['upload_file'] = 1;
			$return['delete_file'] = 1;
			$return['delete_filem'] = 1;
			if ($return['add'] or $return['edit']) {
				$return['save'] = 1;
				$return['batal'] = 1;
				$return['import'] = 1;
				$return['download_template'] = 1;
				$return['import_list'] = 1;
				$return['eksport_list'] = 1;
				$return['export_list'] = 1;
			}

			if ($return['delete']) {
				$return['delete_file'] = 1;
			}
		}

		return $return;
	}

	public function statistikVisitor($limit = 30)
	{
		$sql = "select * from (select *
		from contents_statistik_pengunjung
		where deleted_date is null
		order by tanggal desc limit $limit) a order by tanggal asc";
		$rows = $this->conn->GetArray($sql);

		$data = array();
		$ticks = array();
		foreach ($rows as $idkey => $value) {
			# code...
			$data[] = array($idkey, $value['jumlah']);
			$ticks[] = array($idkey, Eng2Ind($value['tanggal']));
		}

		$ret['data'] = json_encode($data);
		$ret['ticks'] = json_encode($ticks);
		return $ret;
	}
}
