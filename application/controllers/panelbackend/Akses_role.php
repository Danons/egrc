<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Akses_role extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";
		$this->viewindex = "panelbackend/aksesroleindex";

		if ($this->mode == 'print_detail') {
			$this->data['page_title'] = 'Akses Role';
		} else {
			$this->data['page_title'] = 'Akses Role';
		}

		$this->load->model("Public_sys_groupModel", "model");
		$this->data['grouparr'] = $this->model->GetCombo();

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'treetable'
		);

		unset($this->access_role['add']);
	}

	function Index($page = 1)
	{

		if ($this->post['group_id'])
			$_SESSION[SESSION_APP]['group_id1'] = $this->post['group_id'];

		$this->data['group_id'] = $_SESSION[SESSION_APP]['group_id1'];

		$this->data['fiturs'] = $this->model->GetMenu($this->data['group_id']);

		if ($this->post['act'] == 'save') {

			if (isset($this->post['group_id'])) {
				$respon = $this->conn->Execute("delete from public_sys_group_menu where group_id=" . $this->post['group_id']);
				if (isset($this->post['fiturs'])) {
					foreach ($this->post['fiturs'] as $k => $v) {
						if (!$respon)
							break;

						$value = explode("_", $v);
						if (count($value) != 2) {
							$respon = $this->conn->goInsert("public_sys_group_menu", array('group_id' => $this->post['group_id'], 'menu_id' => $v));
						} else {
							$group_menu_id = $this->conn->GetOne("select group_menu_id from public_sys_group_menu where deleted_date is null and group_id={$this->post['group_id']} and menu_id=$value[0]");

							if (!$group_menu_id) {
								$respon = $this->conn->goInsert("public_sys_group_menu", array('group_id' => $this->post['group_id'], 'menu_id' => $value[0]));
								$group_menu_id = $this->conn->GetOne("select group_menu_id from public_sys_group_menu where deleted_date is null and group_id={$this->post['group_id']} and menu_id=$value[0]");
							}

							if ($respon)
								$respon = $this->conn->goInsert("public_sys_group_action", array('group_menu_id' => $group_menu_id, 'action_id' => $value[1]));
						}
					}
				}
			}

			if ($respon)
				SetFlash("suc_msg", "Menu berhasil diupdate");
			else
				SetFlash("err_msg", "Menu gagal diupdate");

			redirect(current_url());
		}

		$this->View($this->viewindex);
	}

	function go_print($id = 1)
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";
		$this->data['no_header'] = true;
		$this->data['fiturs'] = $this->model->GetMenu1();
		$this->View($this->viewindex . "1");
	}
}
