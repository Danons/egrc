<?php
defined('BASEPATH') or exit('No direct script access allowed');

include "Penilaian.php";
class Penilaian_cl extends Penilaian
{
	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
        $this->data['id_kategori'] = $this->id_kategori = 3;
		$this->data['page_title'] = 'Assessment Capabilty Level';
		$this->viewadd = "cl";
	}

	public function Delete($id = null)
	{

		$id = urldecode($id);
		$this->model->conn->StartTrans();

		$this->_beforeDetail($id);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$return = $this->_beforeDelete($id);

		if ($return) {
			$return = $this->model->delete("$this->pk = " . $this->conn->qstr($id));
		}

		if ($return) {
			$return1 = $this->_afterDelete($id);
			if (!$return1)
				$return = false;
		}

		$this->model->conn->CompleteTrans();

		if ($return) {

			$this->log("menghapus", $this->data['row']);

			SetFlash('suc_msg', $return['success']);
			redirect("$this->page_ctrl");
		} else {
			SetFlash('err_msg', "Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id");
		}
	}
}
