<?php class Public_sys_userModel extends _Model
{
	public $table = "public_sys_user";
	public $pk = "user_id";
	public $label = "name";
	function __construct()
	{
		parent::__construct();
	}

	function SqlCombo($id_unit = null)
	{
		$where = " where 1=1 ";
		if ($id_unit) {
			$where .= " and exists (select 1 
			from public_sys_user_group 
			where public_sys_user.user_id = public_sys_user_group.user_id
			and exists (select 1 from mt_sdm_jabatan 
				where public_sys_user_group.id_jabatan = mt_sdm_jabatan.id_jabatan 
				and mt_sdm_jabatan.id_unit = " . $this->conn->escape($id_unit) . "
			))";
		}
		return "select {$this->pk} as idkey, {$this->label} as val from {$this->table} $where order by idkey";
	}

	function GetCombo($id_unit = null)
	{
		$sql = $this->SqlCombo($id_unit);
		$rows = $this->conn->GetArray($sql);
		$data = array('' => '-pilih-');
		foreach ($rows as $r) {
			$data[trim($r['idkey'])] = $r['val'];
		}
		return $data;
	}

	public function SelectGrid1($arr_param = array(), $str_field = "a.*, b.nama")
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

		$str_condition = "";
		$str_order = "";
		if (!empty($arr_params['filter'])) {
			$str_condition = "where " . $arr_params['filter'];
		}
		if (!empty($arr_params['order'])) {
			$str_order = "order by " . $arr_params['order'];
		} elseif ($this->order_default) {
			$str_order = "order by " . $this->order_default;
		}

		if (version_compare($this->conn->version(), '12.1', '>=')) {
			$this->conn->order_by($str_order);
			#$str_order = "";
		}

		if ($arr_params['limit'] === -1) {
			$arr_return['rows'] = $this->conn->GetArray("
				select
				{$str_field}
				from
				" . $this->table . " a
				join mt_sdm_jabatan b on a.id_jabatan = b.id_jabatan
				{$str_condition}
				{$str_order} ");
		} else {
			$arr_return['rows'] = $this->conn->PageArray("
				select
				{$str_field}
				from
				" . $this->table . " a
				join mt_sdm_jabatan b on a.id_jabatan = b.id_jabatan
				{$str_condition}
				{$str_order} ", $arr_params['limit'], $arr_params['page']);
		}

		$arr_return['total'] = static::GetOne("
			select
			count(*) as total
			from
			" . $this->table . " a
			join mt_sdm_jabatan b on a.id_jabatan = b.id_jabatan
			{$str_condition}
		");

		return $arr_return;
	}

	public function SelectGrid($arr_param = array(), $str_field = "a.*, b.nama, b.id_unit")
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

		$str_condition = "";
		$str_order = "";
		if (!empty($arr_params['filter'])) {
			$str_condition = "where " . $arr_params['filter'];
		}
		if (!empty($arr_params['order'])) {
			$str_order = "order by " . $arr_params['order'];
		} elseif ($this->order_default) {
			$str_order = "order by " . $this->order_default;
		}

		if (version_compare($this->conn->version(), '12.1', '>=')) {
			$this->conn->order_by($str_order);
			#$str_order = "";
		}

		if ($arr_params['limit'] === -1) {
			$arr_return['rows'] = $this->conn->GetArray("
				select
				{$str_field}
				from (
				select
				ifnull(b.user_id, a.user_id) user_id,
				ifnull(b.group_id, a.group_id) group_id,
				ifnull(b.id_jabatan, a.id_jabatan) id_jabatan,
				a.deleted_date,
				a.username,
				a.name,
				a.last_ip,
				a.last_login,
				a.is_active,
				a.password,
				a.nid,
				a.is_notification,
				a.email,
				a.is_manual,
				a.tgl_mulai_aktif,
				a.tgl_selesai_aktif
				from public_sys_user a
				left join public_sys_user_group b on a.user_id = b.user_id
				) a
				join mt_sdm_jabatan b on a.id_jabatan = b.id_jabatan
				{$str_condition}
				{$str_order} ");
		} else {
			$arr_return['rows'] = $this->conn->PageArray("
				select
				{$str_field}
				from (
				select
				ifnull(b.user_id, a.user_id) user_id,
				ifnull(b.group_id, a.group_id) group_id,
				ifnull(b.id_jabatan, a.id_jabatan) id_jabatan,
				a.username,
				a.name,
				a.deleted_date,
				a.last_ip,
				a.last_login,
				a.is_active,
				a.password,
				a.nid,
				a.is_notification,
				a.email,
				a.is_manual,
				a.tgl_mulai_aktif,
				a.tgl_selesai_aktif
				from public_sys_user a
				left join public_sys_user_group b on a.user_id = b.user_id
				) a
				join mt_sdm_jabatan b on a.id_jabatan = b.id_jabatan
				{$str_condition}
				{$str_order} ", $arr_params['limit'], $arr_params['page']);
		}

		$arr_return['total'] = static::GetOne("
			select
			count(*) as total
			from (
				select
				ifnull(b.user_id, a.user_id) user_id,
				ifnull(b.group_id, a.group_id) group_id,
				ifnull(b.id_jabatan, a.id_jabatan) id_jabatan,
				a.username,
				a.deleted_date,
				a.name,
				a.last_ip,
				a.last_login,
				a.is_active,
				a.password,
				a.nid,
				a.is_notification,
				a.email,
				a.is_manual,
				a.tgl_mulai_aktif,
				a.tgl_selesai_aktif
				from public_sys_user a
				left join public_sys_user_group b on a.user_id = b.user_id
				) a
			join mt_sdm_jabatan b on a.id_jabatan = b.id_jabatan
			{$str_condition}
		");

		return $arr_return;
	}
}
