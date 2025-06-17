<?php class Risk_taskModel extends _Model
{
	public $table = "risk_task";
	public $pk = "id_task";
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


		$ci = $this->ci;
		$sql = $ci->auth->SqlTask();

		$sql_content = "select 
		t.id_task,
		t.status,
		s.nama as nama_scorecard, 
		t.id_status_pengajuan, 
		t.url, 
		t.deskripsi, 
		u.name as nama_user, 
		g.name as nama_group, 
		date_format(t.created_date,'%Y-%m-%d %T:%i:%s') as created_date, date_format(sysdate(),'%Y-%m-%d %T:%i:%s') as n
		" . $sql . " ";

		$this->conn->order_by("order by id_task desc");
		$rows = $this->conn->PageArray(
			$sql_content,
			$arr_params['limit'],
			$arr_params['page']
		);


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

		$content = array();
		foreach ($rows as $r) {
			if (!$r['status'])
				$info = "<b>" . $r['nama_scorecard'] . "</b><br/><i>" . $r['deskripsi'] . "</i>";
			else
				$info = $r['nama_scorecard'] . "<br/><i>" . $r['deskripsi'] . "</i>";

			$content[] = array(
				'bg' => $bgarr[$r['id_status_pengajuan']],
				'icon' => $iconarr[$r['id_status_pengajuan']],
				'info' => $info,
				'time' => waktu_lalu($r['created_date'], $r['n']),
				'url' => "panelbackend/home/task/$r[id_task]",
				'user' => ucwords(strtolower($r['nama_user'])) . " (" . ucwords(strtolower($r['nama_group'])) . ")"
			);
		}

		$sql_count = "select count(1) " . $sql;

		$arr_return['rows'] = $content;
		$arr_return['total'] = static::GetOne($sql_count);

		return $arr_return;
	}
}
