<?php class TemplateModel extends _Model{
	public $table = "template";
	public $pk = "id_template";
	public $label = "nama";
	function __construct(){
		parent::__construct();
	}
}