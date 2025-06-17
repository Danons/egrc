<?php class Mt_kategoriModel extends _Model{
	public $table = "mt_kategori";
	public $pk = "id_kategori";
	public $order_default = "id_kategori asc";
	public $label = "nama";
	function __construct(){
		parent::__construct();
	}

	function SqlCombo2(){
		return "select {$this->pk} as key, {$this->label} as val from {$this->table} where id_kategori_jenis is not null order by key";
	}

	function GetCombo2(){
		$sql = $this->SqlCombo2();
		$rows = $this->conn->GetArray($sql);
		$data = array(''=>'-pilih-');
		foreach ($rows as $r) {
			$data[trim($r['key'])] = $r['val'];
		}
		return $data;
	}
	
	function GetList(){

		$sql = "select id_kategori as id, nama, id_kategori_parent as id_parent, a.* from {$this->table} a order by id_kategori asc";

		$rows = $this->conn->GetArray($sql);

		$ret = array();
		$this->GenerateSort($rows, "id_parent", "id", "nama", $ret);

		return $ret;
	}

	function GenerateSort(&$row, $colparent, $colid, $collabel, &$return=array(), $valparent=null, &$i=0, $level=0, $is_space = false, $nobefore=""){

		$noarr = array('A',1,'a');

		$no = $noarr[$level];
		$level++;
		foreach ($row as $key => $value) {
			# code...
			if(trim($value[$colparent])==trim($valparent)){
				$space = '';
				if($is_space){
					for($k=1; $k<$level; $k++){
						$space .='&nbsp;➥&nbsp;';
					}
				}

				$value[$collabel] = $space.$value[$collabel];
				$return[$i]=$value;

				if(!$nobefore)
					$nobefore1 = $no.'.';
				else
					$nobefore1 = $nobefore.$no.'.';

				$space = '';
				for($k=1; $k<$level; $k++){
					$space .='&nbsp;&nbsp;&nbsp;';
				}

				if($level==1){
					$return[$i]['no'] = "<b>".$space.($no++)."</b>";
				}else{
					$return[$i]['no'] = $space.$no++;
				}
				$return[$i]['space'] = $space;
				$return[$i]['level'] = $level;

				$i++;

				$temp = $row;
				unset($temp[$key]);

				$this->GenerateSort($temp, $colparent, $colid, $collabel, $return, $value[$colid], $i, $level, $is_space, $nobefore1);
				$row = $temp;
			}
		}

		if($row && $level==1)
			$return = array_merge($return, $row);
	}
}