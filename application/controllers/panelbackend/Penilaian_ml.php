<?php
defined('BASEPATH') or exit('No direct script access allowed');

include "Penilaian.php";
class Penilaian_ml extends Penilaian
{
	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->data['id_kategori'] = $this->id_kategori = 2;
		$this->data['page_title'] = 'Assessment Maturity Level';
		$this->viewadd = "ml";
		$this->viewprint = "panelbackend/penilaianprintml";
	}

	public function go_print($id_kategori = null)
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";
		$this->data['no_header'] = true;
		$id_kategori = 2;
		$this->_filter();
		$is_admin = Access("edit", "panelbackend/mt_kriteria");
		$this->_filter();
		$this->_beforeDetail($id_kategori);
		$this->data['parentarr'] = $this->mkriteria->getComboParent($this->id_kategori);
		$id_kategori = $this->id_kategori;
		if ($this->post['act'] == 'set_parent')
			$_SESSION[SESSION_APP][$this->page_ctrl][$id_kategori]['id_parent'] = $this->post['idkey'];

		if (!$_SESSION[SESSION_APP][$this->page_ctrl][$id_kategori]['id_parent'])
			$_SESSION[SESSION_APP][$this->page_ctrl][$id_kategori]['id_parent'] = array_values($this->data['parentarr'])[0];

		$this->data['id_parent'] = $_SESSION[SESSION_APP][$this->page_ctrl][$id_kategori]['id_parent'];

		$tahun = $this->data['tahun'];
		$bulan = $this->data['bulan'];

		$this->data['id_interval'] = $this->data['rowheader']['periode_penilaian'];

		#mengambil list interval
		$this->data['periodedetailarr'] = array();
		if ($this->data['id_interval']) {
			loop_periode(
				array('tahun' => $tahun, 'id_interval' => $this->data['id_interval'], "bulan" => $bulan),
				function ($arr, &$ret) {
					$ret[$arr['tgl']] = $arr['listbulan'][$arr['bulan']];
				},
				$this->data['periodedetailarr']
			);

			if (count($this->data['periodedetailarr']) == 1)
				$this->data['periodedetailarr'] = array();
		}

		if ($this->data['periodedetailarr'])
			$this->data['periodedetailarr'] = array('' => '-pilih-') + $this->data['periodedetailarr'];
		else
			$this->data['tgl'] = $tahun . "-01-01";

		if ($this->request['act'] == 'update_penilaian' && $is_admin) {
			$ret = array("success" => $this->update_penilaian($this->request['id_penilaian'], $this->request));

			echo json_encode($ret);
			exit();
		}
		if ($this->post['act'] == 'generate') {
			$break = false;
			#mengambil tgl berikutnya untuk keperluan generate
			if ($this->data['periodedetailarr'])
				foreach ($this->data['periodedetailarr'] as $tgl => $label) {

					$this->data['tgl_next'] = $tgl;

					if ($break)
						break;

					if ($tgl == $this->data['tgl'])
						$break = true;
				}

			if (!$this->data['tgl_next'] or $this->data['tgl_next'] == $this->data['tgl'])
				$this->data['tgl_next'] = ($tahun + 1) . "-01-01";

			$this->_generate($tahun, $id_kategori);
			exit();
		}

		if ($this->data['tgl'] && $this->data['id_parent']) {
			$tgl_penilaian = $this->data['tgl'];
			// $this->conn->debug = 1;
			$this->data['arearr'] = $this->model->get_kriteria_penilaian(
				$this->data['id_parent'],
				$id_kategori,
				$this->data['id_unit'],
				$tgl_penilaian,
				$this->data['id_penilaian_session'],
				// $this->data['rowheader1']['target_lvl']
				$this->data['level']
			);

			#mengecek untuk yang belum digenerate
			if (!$this->data['arearr']) {
				$cek = false;
				if ($this->data['id_unit'])
					$unitarr = array($this->data['id_unit'] => $this->data['id_unit']);
				else
					$unitarr = $this->data['mtunitarr'];

				unset($unitarr['']);
				foreach ($unitarr as $id_unit => $label) {

					$nilai_target = $this->conn->GetOne("select ifnull(nilai_target,5) 
						from penilaian_periode pp 
						join mt_kriteria k on pp.id_kriteria = k.id_kriteria
						where pp.deleted_date is null and /*id_unit = " . $this->conn->escape($id_unit) . "
						and*/ id_kategori = " . $this->conn->escape($id_kategori) . "
						and id_penilaian_session = " . $this->conn->escape($this->data['id_penilaian_session']) . "
						and date_format(tgl_penilaian,'%Y-%m-%d') = " . $this->conn->escape($tgl_penilaian));

					if (!$this->data['nilai_target'])
						$this->data['nilai_target'] = $nilai_target;

					$cek = !$nilai_target;

					if ($cek)
						break;
				}

				// $this->data['is_generate'] = $cek;
			}

			$this->data['is_generate'] = !($this->data['arearr']);
		}

		if ($this->post['act'] == 'delete_all' && $this->data['arearr']) {
			$this->conn->StartTrans();
			$ret = $this->_deleteAll($this->data['arearr']);
			if ($ret)
				$this->conn->trans_commit();
			else
				$this->conn->trans_rollback();

			redirect(current_url());
		}

		if (!$this->data['nilai_target'])
			$this->data['nilai_target'] = $this->data['arearr'][0]['sub'][0]['lvl'][0]['bukti'][0]['nilai_target'];

		$iseditfile = $_SESSION[SESSION_APP]['login'];
		$id_interval = $this->data['id_interval'];
		$tgl = $this->data['tgl'];

		if ($_SESSION[SESSION_APP]['id_unit'] != $this->data['id_unit'])
			$iseditfile = false;
		if ($is_admin)
			$iseditfile = true;

		if ($this->data['is_generate'])
			$this->data['mtunitarr'][''] = 'Semua unit';

		$this->data['background'] = array(
			'hitam' => '#333',
			'merah' => '#ff4545',
			'biru' => '#3f89ff',
		);

		$this->data['is_admin'] = $is_admin;
		$this->data['iseditfile'] = $iseditfile;

		if ($this->post['act'] == 'content-table') {
			$this->PartialView("panelbackend/penilaiantable");
			exit();
		}

		$this->access_role['add'] = false;
		// $this->data['nobutton'] = true;
		$this->data['buttonMenu'] = "";
		// $this->data['buttonMenu'] .= UI::createSelect('id_unit', $this->data['mtunitarr'], $this->data['id_unit'], true, 'form-control ', "style='width:auto;display:inline;' onchange='goSubmit(\"set_filter\");'");
		if ($this->id_kategori_jenis <> 1)
			$this->data['buttonMenu'] .= "<b>Filter Level : </b> &nbsp;" . UI::createTextNumber('level', $this->data['level'], 4, '', true, 'form-control ', "style='width:80px;display:inline;' onchange='goSubmit(\"set_filter\");'", '');

		$this->View($this->viewprint);
	}

	private function _filter()
	{
		$this->data['tahun'] = date('Y');
		$this->data['bulan'] = date('m');


		if (!Access("edit", "panelbackend/mt_kriteria")) {
			$this->data['id_unit'] = $_SESSION[SESSION_APP]['id_unit'];
		}


		#pop up
		if ($this->request['idx'] !== null)
			$_SESSION[SESSION_APP][$this->page_ctrl]['idx'] = $this->request['idx'];
		$this->data['idx'] = $_SESSION[SESSION_APP][$this->page_ctrl]['idx'];


		#unit
		if ($this->request['id_unit'] !== null)
			$_SESSION[SESSION_APP][$this->page_ctrl]['id_unit'] = $this->request['id_unit'];
		if ($_SESSION[SESSION_APP][$this->page_ctrl]['id_unit'] !== null)
			$this->data['id_unit'] = $_SESSION[SESSION_APP][$this->page_ctrl]['id_unit'];


		#tahun
		if ($this->request['tahun'] !== null)
			$_SESSION[SESSION_APP][$this->page_ctrl]['tahun'] = $this->request['tahun'];
		if ($_SESSION[SESSION_APP][$this->page_ctrl]['tahun'])
			$this->data['tahun'] = $_SESSION[SESSION_APP][$this->page_ctrl]['tahun'];

		#bulan
		if ($this->request['bulan'] !== null)
			$_SESSION[SESSION_APP][$this->page_ctrl]['bulan'] = $this->request['bulan'];
		if ($_SESSION[SESSION_APP][$this->page_ctrl]['bulan'] !== null)
			$this->data['bulan'] = $_SESSION[SESSION_APP][$this->page_ctrl]['bulan'];

		#tgl
		if ($this->request['tgl'] !== null)
			$_SESSION[SESSION_APP][$this->page_ctrl]['tgl'] = $this->request['tgl'];
		if ($_SESSION[SESSION_APP][$this->page_ctrl]['tgl'] !== null)
			$this->data['tgl'] = $_SESSION[SESSION_APP][$this->page_ctrl]['tgl'];

		if ($this->request['level'] !== null)
			$_SESSION[SESSION_APP][$this->page_ctrl]['level'] = $this->request['level'];
		if ($_SESSION[SESSION_APP][$this->page_ctrl]['level'] !== null)
			$this->data['level'] = $_SESSION[SESSION_APP][$this->page_ctrl]['level'];

		if ($this->request['act'] == 'set_filter')
			redirect(current_url());
	}
}
