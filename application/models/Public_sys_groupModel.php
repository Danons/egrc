<?php class Public_sys_groupModel extends _Model{
	public $table = "public_sys_group";
	public $pk = "group_id";
	public $label = "name";
	function __construct(){
		parent::__construct();
	}

	public function GetMenu($group_id=null){
		if(!$group_id)
			return array();

		$rows = $this->conn->GetArray("SELECT a.menu_id as id, a.parent_id as \"_parentId\", a.label as text, a.state, a.sort, MAX(a.checked) AS checked, iconcls as \"iconCls\"
		FROM (
		SELECT c.menu_id  as menu_id, c.parent_id, c.label, c.state, c.sort, 0 AS checked, iconcls
		FROM public_sys_menu c UNION
		SELECT concat(d.menu_id,'_',d.action_id), d.menu_id, d.name, NULL, NULL, 0 AS checked, NULL AS iconcls
		FROM public_sys_action d UNION
		SELECT a.menu_id , a.parent_id, a.label, a.state, a.sort, 1 AS checked, iconcls
		FROM public_sys_group_menu b
		LEFT JOIN public_sys_menu a ON b.menu_id=a.menu_id
		WHERE b.group_id=$group_id UNION
		SELECT concat(d.menu_id,'_',d.action_id), d.menu_id, d.name, NULL, NULL, 1 AS checked, NULL AS iconcls
		FROM public_sys_group_menu a
		LEFT JOIN public_sys_group_action b ON a.group_menu_id = b.group_menu_id
		LEFT JOIN public_sys_action d ON b.action_id=d.action_id
		WHERE d.action_id IS NOT NULL AND a.group_id=$group_id
		)a
		GROUP BY a.menu_id, a.parent_id, a.label, a.state, a.sort, a.iconcls order by a.sort");

		$ret = array();
		$i = 0;

		// dpr($rows,1);

		$this->GenerateSort($rows, "_parentid", "id", "text", $ret, "", $i);

		return $ret;
	}

	public function GetMenu1(){

		$rows = $this->conn->GetArray("SELECT c.*
		FROM public_sys_menu c 
		where visible = 1 
		order by sort");

		$ret = array();
		$i = 0;

		// dpr($rows,1);

		$this->GenerateSort($rows, "parent_id", "menu_id", "label", $ret, "", $i);

		return $ret;
	}


	function GenerateSort(&$row, $colparent, $colid, $collabel, &$return=array(), $valparent=null, &$i=0, $level=0, $is_space = false, $idarr = array()){

		$level++;
		foreach ($row as $idkey => $value) {
			# code...
			if(trim($value[$colparent])==trim($valparent) && ($value[$colparent] or $valparent===null or $valparent==="")){

				$value[$collabel] = $space.$value[$collabel];
				$value['level'] = $level;
				$return[$i]=$value;

				$i++;
				$this->GenerateSort($row, $colparent, $colid, $collabel, $return, $value[$colid], $i, $level, $is_space);
			}
		}
	}
}