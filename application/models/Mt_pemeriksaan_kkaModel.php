<?php class Mt_pemeriksaan_kkaModel extends _Model
{
	public $table = "mt_pemeriksaan_kka";
	public $pk = "id_kka";
	protected $label = "concat(nomor_kka,' ',nama)";
	function __construct()
	{
		parent::__construct();
	}
}
