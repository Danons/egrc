<?php class Mt_opp_matrixModel extends _Model{
	public $table = "mt_opp_matrix";
	public $pk = "id_dampak";
	public $order_default = "id_dampak desc, id_kemungkinan desc";
	function __construct(){
		parent::__construct();
	}

	function getMatrix(){
		$sql = "select m.*, td.nama, td.warna, rk.kode as kode_kemungkinan, rd.kode as kode_dampak, rk.rating as rating_kemungkinan, rd.rating as rating_dampak
		from mt_opp_matrix m
		join mt_opp_tingkat td on m.id_tingkat = td.id_tingkat
		join mt_opp_kemungkinan rk on m.id_kemungkinan = rk.id_kemungkinan
		join mt_opp_dampak rd on m.id_dampak = rd.id_dampak
		";

		return $this->conn->GetArray($sql);
	}

	function getTingkat(){
		$rs = $this->getMatrix();
		$arr = array();
		foreach ($rs as $r) {
			$arr[$r['id_kemungkinan']][$r['id_dampak']] = $r['id_tingkat'];
		}

		return $arr;
	}

	function minoppApertite(){
		return $this->GetOne("select min(id_tingkat) from mt_opp_matrix where css is not null");
	}

	public function GetByPk($id_kemungkinan=null, $id_dampak=null){
		if(!$id_kemungkinan or !$id_dampak){
			return array();
		}
		$sql = "select * from ".$this->table." where id_kemungkinan = ".$this->conn->escape($id_kemungkinan).
            	" and id_dampak = ".$this->conn->escape($id_dampak);
		$ret = $this->conn->GetRow($sql);

		if(!$ret)
			$ret = array();

		return $ret;
	}
}