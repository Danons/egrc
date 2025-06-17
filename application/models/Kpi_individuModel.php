<?php class Kpi_individuModel extends _Model
{
	public $table = "kpi_individu";
	public $pk = "id_kpi";
	public $label = "";
	function __construct()
	{
		parent::__construct();
	}

	public function SelectGrid($arr_param = array(), $str_field = "*")
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
			$str_condition = "and " . $arr_params['filter'];
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
			if (Access("view_all")) {

				$arr_return['rows'] = $this->conn->GetArray("
				select
				{$str_field}
				from
				" . $this->table . " where deleted_date is null 
				{$str_condition}
				{$str_order} ");
			} else {

				$arr_return['rows'] = $this->conn->GetArray(
					"		
			SELECT *, 1 as status FROM kpi_individu where id_pegawai IN 
			(SELECT psu.user_id from mt_sdm_jabatan msj 
			LEFT JOIN public_sys_user psu ON msj.id_jabatan = psu.id_jabatan WHERE id_jabatan_parent IN 
			(SELECT id_jabatan FROM mt_sdm_jabatan WHERE id_jabatan_parent = " . $_SESSION['SESSION_APP_EGRC']['id_jabatan'] . ") and psu.user_id IS NOT NULL " . $str_condition . " " . $str_order . ") and deleted_date is null 
			
			UNION ALL 
		
			SELECT *, 2 as status FROM kpi_individu WHERE id_pegawai IN (SELECT psu.user_id FROM mt_sdm_jabatan msj 
			LEFT JOIN public_sys_user psu ON msj.id_jabatan = psu.id_jabatan WHERE id_jabatan_parent = " . $_SESSION['SESSION_APP_EGRC']['id_jabatan'] . " " . $str_condition . " " . $str_order . ") and deleted_date is null
			
			UNION ALL
			
			SELECT *, 3 as status FROM kpi_individu WHERE deleted_date is null and id_pegawai = " . $_SESSION['SESSION_APP_EGRC']['user_id'] . " " . $str_condition . " and deleted_date is null  " . $str_order
				);
			}
		} else {
			if (Access("view_all")) {
				$arr_return['rows'] = $this->conn->PageArray(
					"
				select
				{$str_field}
				from
				" . $this->table . " where deleted_date is null 
				{$str_condition}
				{$str_order} ",
					$arr_params['limit'],
					$arr_params['page']
				);
			} else {
				$arr_return['rows'] = $this->conn->PageArray(

					"
				SELECT *, 1 as status FROM kpi_individu where id_pegawai IN 
			(SELECT psu.user_id from mt_sdm_jabatan msj 
			LEFT JOIN public_sys_user psu ON msj.id_jabatan = psu.id_jabatan WHERE id_jabatan_parent IN 
			(SELECT id_jabatan FROM mt_sdm_jabatan WHERE id_jabatan_parent = " . $_SESSION['SESSION_APP_EGRC']['id_jabatan'] . ") and psu.user_id IS NOT NULL " . $str_condition . " " . $str_order . ") and deleted_date is null
			
			UNION ALL 
		
			SELECT *, 2 as status FROM kpi_individu WHERE id_pegawai IN (SELECT psu.user_id FROM mt_sdm_jabatan msj 
			LEFT JOIN public_sys_user psu ON msj.id_jabatan = psu.id_jabatan WHERE id_jabatan_parent = " . $_SESSION['SESSION_APP_EGRC']['id_jabatan'] . " " . $str_condition . " " . $str_order . ") and deleted_date is null
			
			UNION ALL
			
			SELECT *, 3 as status FROM kpi_individu WHERE id_pegawai = " . $_SESSION['SESSION_APP_EGRC']['user_id'] . " " . $str_condition . " and deleted_date is null " . $str_order,
					$arr_params['limit'],
					$arr_params['page']
				);
			}
		}

		if (Access('view_all')) {
			$arr_return['total'] = static::GetOne("
			select
			count(*) as total
			from
			" . $this->table . " where deleted_date is null  
			{$str_condition}
		");
		} else {

			$arr_return['total'] = static::GetOne(
				"		
			select count(*) from (SELECT *, 1 as status FROM kpi_individu where id_pegawai IN 
			(SELECT psu.user_id from mt_sdm_jabatan msj 
			LEFT JOIN public_sys_user psu ON msj.id_jabatan = psu.id_jabatan WHERE id_jabatan_parent IN 
			(SELECT id_jabatan FROM mt_sdm_jabatan WHERE id_jabatan_parent = " . $_SESSION['SESSION_APP_EGRC']['id_jabatan'] . ") and psu.user_id IS NOT NULL " . $str_condition .  ") and deleted_date is null
			
			UNION ALL 
		
			SELECT *, 2 as status FROM kpi_individu WHERE id_pegawai IN (SELECT psu.user_id FROM mt_sdm_jabatan msj 
			LEFT JOIN public_sys_user psu ON msj.id_jabatan = psu.id_jabatan WHERE id_jabatan_parent = " . $_SESSION['SESSION_APP_EGRC']['id_jabatan'] . " " . $str_condition . ") and deleted_date is null
			
			UNION ALL
			
			SELECT *, 3 as status FROM kpi_individu WHERE deleted_date is null and id_pegawai = " . $_SESSION['SESSION_APP_EGRC']['user_id'] . " " . $str_condition . ") as a"
			);
		}

		return $arr_return;
	}
}
