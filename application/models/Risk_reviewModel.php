<?php class Risk_reviewModel extends _Model{
	public $table = "risk_review";
	public $pk = "id_review";
	function __construct(){
		parent::__construct();
	}

	public function SelectGrid($arr_param=array(), $str_field="*")
	{
		$arr_return = array();
		$arr_params = array(
			'page' => 0,
			'limit' => 50,
			'order' => '',
			'filter' => ''
		);
		foreach($arr_param as $idkey=>$val){
			$arr_params[$idkey]=$val;
		}

		$arr_params['page'] = ($arr_params['page']/$arr_params['limit'])+1;

		$str_condition = "";
		$str_order = "";
		if(!empty($arr_params['filter']))
		{
			$str_condition = "where ".$arr_params['filter'];
		}
		if(!empty($arr_params['order']))
		{
			$str_order = "order by ".$arr_params['order'];
		}elseif($this->order_default){
			$str_order = "order by ".$this->order_default;
		}
		
		if (version_compare($this->conn->version(), '12.1', '>='))
		{
			$this->conn->order_by($str_order);
#			$str_order = "";
		}

		$table = "(
			select review, id_review, created_by, created_date, group_id, id_risiko from risk_review
			union 
			select deskripsi, null, created_by, activity_time, group_id, id_risiko from risk_log)";

		if($arr_params['limit']===-1){
			$arr_return['rows'] = $this->conn->GetArray("
				select t.review, t.id_review, t.created_by, date_format(t.created_date,'%Y-%m-%d %T:ii:%s') as created_date, u.name as nama_user, g.name as nama_group
				from 
				".$table." t
				join public_sys_user u on t.created_by = u.user_id
				join public_sys_group g on t.group_id = g.group_id
				{$str_condition} 
				order by t.created_date desc");
		}else{
			$arr_return['rows'] = $this->conn->PageArray("
				select t.review, t.id_review, t.created_by, date_format(t.created_date,'%Y-%m-%d %T:ii:%s') as created_date, u.name as nama_user, g.name as nama_group
				from 
				".$table." t
				{$str_condition} 
				order by t.created_date desc ",$arr_params['limit'],$arr_params['page']
			);
		}
		
		$arr_return['total'] = static::GetOne("
			select 
			count(*) as total 
			from 
			".$this->table."
			{$str_condition}
		");
		
		return $arr_return;
	}
}
