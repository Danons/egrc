<?php class ReapModel extends _Model{
	public $table = "reap";
	public $pk = "id_reap";
	public $order_default = "risk";
	public $label = "nama";
	function __construct(){
		parent::__construct();
	}
}