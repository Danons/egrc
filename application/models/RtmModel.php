<?php class RtmModel extends _Model
{
	public $table = "rtm";
	public $pk = "id_rtm";
	public $label = "rtm_ke";
	function __construct()
	{
		parent::__construct();
	}

	public function GetByPk($id)
	{
		if (!$id) {
			return array();
		}
		$sql = "select * from " . $this->table . " where {$this->pk} = " . $this->conn->qstr($id);
		$ret = $this->conn->GetRow($sql);

		if (!$ret)
			$ret = array();
		else if ($ret['rkt'])
			$ret['periode'] = $this->conn->GetOne("select nama from mt_periode_tw where id_periode_tw = " . $ret['rkt']);

		return $ret;
	}
}
