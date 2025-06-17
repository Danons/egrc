<?php class Mt_risk_taksonomiModel extends _Model
{
	public $table = "mt_risk_taksonomi";
	public $pk = "id_taksonomi";
	public $label = "concat(kode,' ',nama)";
	function __construct()
	{
		parent::__construct();
	}

	function SqlCombo()
	{
		return "SELECT
		a.id_taksonomi AS idkey,
		concat(a.kode, ' ', a.nama) AS val
	FROM
		mt_risk_taksonomi        a
		LEFT JOIN mt_risk_taksonomi_area   b ON a.id_taksonomi_area = b.id_taksonomi_area
	WHERE
		a.is_aktif = 1
	ORDER BY
		b.id_taksonomi_objective,
		regexp_substr(b.kode,'[^.]+',1,1),
		CONVERT(regexp_substr(b.kode,'[^.]+',1,2),UNSIGNED INTEGER),
		regexp_substr(a.kode,'[^.]+',1,1),
		CONVERT(regexp_substr(a.kode,'[^.]+',1,2),UNSIGNED INTEGER),
		CONVERT(regexp_substr(a.kode,'[^.]+',1,3),UNSIGNED INTEGER)";
	}
}
