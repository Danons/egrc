<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Rtm extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/rtmlist";
		$this->viewdetail = "panelbackend/rtmdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah RTM';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit RTM';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail RTM';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar RTM';
		}

		$this->load->model("RtmModel", "model");

		$this->load->model("Mt_periode_twModel", "mtperiodetw");
		$this->data['mtperiodetwarr'] = $this->mtperiodetw->GetCombo();

		$this->load->model("Rtm_filesModel", "modelfile");
		$this->data['configfile'] = $this->config->item('file_upload_config');

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'upload'
		);
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'rtm_ke',
				'label' => 'RTM ke',
				'width' => "auto",
				'type' => "number",
			),
			array(
				'name' => 'tingkat',
				'label' => 'Tingkat',
				'width' => "auto",
				'type' => "varchar",
			),
			array(
				'name' => 'rkt',
				'label' => 'RKT',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['mtperiodetwarr'],
			),
			array(
				'name' => 'tahun',
				'label' => 'Tahun',
				'width' => "auto",
				'type' => "number",
			),
		);
	}

	protected function Record($id = null)
	{
		return array(
			'rtm_ke' => $this->post['rtm_ke'],
			'tingkat' => $this->post['tingkat'],
			'rkt' => $this->post['rkt'],
			'tahun' => $this->post['tahun'],
		);
	}

	protected function Rules()
	{
		return array(
			"rtm_ke" => array(
				'field' => 'rtm_ke',
				'label' => 'RTM ke',
				'rules' => "integer|max_length[10]",
			),
			"tingkat" => array(
				'field' => 'tingkat',
				'label' => 'Tingkat',
				'rules' => "max_length[45]",
			),
			"rkt" => array(
				'field' => 'rkt',
				'label' => 'RKT',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtperiodetwarr'])) . "]|max_length[10]",
			),
			"tahun" => array(
				'field' => 'tahun',
				'label' => 'Tahun',
				'rules' => "integer|max_length[10]",
			),
		);
	}

	protected function _afterInsert($id)
	{
		$ret = true;

		if ($this->modelfile) {
			if (!empty($this->post['files'])) {
				foreach ($this->post['files']['id'] as $k => $v) {
					$return = $this->_updateFiles(array($this->pk => $id), $v);

					$ret = $return['success'];
				}
			}
		}

		$deskripsi = "RTM ke " . $this->post['rtm_ke'];
		$deskripsi .= " Tingkat " . (["" => "", "Pusat" => "Pusat", "Wilayah" => "Wilayah"][$this->post['tingkat']]);
		$deskripsi .= " RKT " . $this->data['mtperiodetwarr'][$this->post['rkt']];
		$deskripsi .= " Tahun " . $this->post['tahun'];

		if ($ret) {
			$ownerarr = $this->conn->GetArray("select * from public_sys_user_group where deleted_date is null and group_id=24");

			foreach ($ownerarr as $r) {
				if (!$ret)
					break;

				$record = array(
					'page' => 'rtm_uraian',
					'untuk' => $r['id_jabatan'],
					'id_status_pengajuan' => 2,
					'deskripsi' => $deskripsi,
					'url' => "panelbackend/rtm_uraian/add"
				);

				$return = $this->InsertTask($record);

				$ret = $return['success'];
			}
		}

		return $ret;
	}
}
