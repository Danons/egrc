<?php class Survey_filesModel extends _Model{
	public $table = "survey_files";
	public $pk = "id_survey_files";
	public $label = "nama";
	function __construct(){
		parent::__construct();
	}
}