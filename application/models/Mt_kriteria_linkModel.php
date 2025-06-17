<?php class Mt_kriteria_linkModel extends _Model{
	public $table = "mt_kriteria_link";
	public $pk = "id_kriteria1";
	function __construct(){
		parent::__construct();
	}

	public function SelectGrid($arr_param=array(), $str_field="a.*, b.kode||' '||b.nama as kriteria1, c.kode||' '||c.nama as kriteria2, b.id_kategori as id_kategori1, c.id_kategori as id_kategori2")
	{
		// $this->conn->debug = true;
		$arr_return = array();
		$arr_params = array(
			'page' => 0,
			'limit' => 50,
			'order' => '',
			'filter' => ''
		);
		foreach($arr_param as $key=>$val){
			$arr_params[$key]=$val;
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
		if($arr_params['limit']===-1){
			$arr_return['rows'] = $this->conn->GetArray("
				select
				{$str_field}
				from
				".$this->table." a
				{$str_condition}
				{$str_order} ");
		}else{
			$arr_return['rows'] = $this->conn->PageArray("
				select
				{$str_field}
				from
				".$this->table." a
				join mt_kriteria b on a.id_kriteria1 = b.id_kriteria
				join mt_kriteria c on a.id_kriteria2 = c.id_kriteria
				{$str_condition}
				{$str_order} ",$arr_params['limit'],$arr_params['page']
			);
		}

		$arr_return['total'] = static::GetOne("
			select
			count(*) as total
			from
			".$this->table." a
			
			join mt_kriteria b on a.id_kriteria1 = b.id_kriteria
			join mt_kriteria c on a.id_kriteria2 = c.id_kriteria
			{$str_condition}
		");

		// dpr($arr_params, 1);

		return $arr_return;
	}
}