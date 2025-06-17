<?php class Penilaian_komentarModel extends _Model{
	public $table = "penilaian_komentar";
	public $pk = "id_penilaian_komentar";
	public $label = "nama";
	function __construct(){
		parent::__construct();
	}
}