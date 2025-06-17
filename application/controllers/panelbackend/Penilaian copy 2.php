<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Penilaian extends _adminController
{

	public $viewprint = "panelbackend/penilaianprint";
	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/penilaianlist";
		$this->viewdetail = "panelbackend/mt_kriteriadetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_penilaian";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Kriteria';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Kriteria';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Kriteria';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Penilaian GCG';
		}

		$this->data['width'] = "1800px";

		$this->load->model("Penilaian_periodeModel", "model");
		$this->load->model("Mt_kriteriaModel", "mkriteria");
		$this->load->model("Penilaian_sessionModel", "penilaiansession");
		$this->load->model("Mt_kategoriModel", "mtkategori");
		$this->load->model("Penilaian_filesModel", "modelfile");

		// $this->data['mtkategoriarr'] = $this->mtkategori->GetCombo();

		$this->load->model("Mt_intervalModel", "mtinterval");
		// $this->data['mtperiodearr2'] = $this->mtperiode->GetCombo2();
		$this->data['mtintervalarr'] = $this->mtinterval->GetCombo();

		$this->load->model("Mt_sdm_unitModel", "mtunit");
		$this->data['mtunitarr'] = $this->mtunit->GetCombo();


		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'upload', 'datepicker', 'treetable'
		);

		$this->data['configfile'] = $this->config->item('file_upload_config');

		$this->data['statusarr'] = array('' => '', '1' => 'Diajukan', '2' => 'Revisi', '3' => 'Oke');
		$tahun = date("Y");
		if ($_SESSION[SESSION_APP][$this->page_ctrl]['tahun'])
			$tahun = $_SESSION[SESSION_APP][$this->page_ctrl]['tahun'];
		$tgl = $tahun . "-01-01";
		$this->data['id_penilaian_session'] = $this->conn->GetOne("select 
		id_penilaian_session 
		from penilaian_session 
		where date_format(tgl,'%Y-%m-%d') = " . $this->conn->escape($tgl) . " 
		and page_ctrl = " . $this->conn->escape($this->page_ctrl));
	}

	private function _generate($tahun, $id_kategori)
	{
		// PROSES INPUT PERIODE PENILAIAN
		$id_parent = $this->data['id_parent'];
		$arearr = $this->mkriteria->get_kriteria($id_parent);

		// dpr($arearr, 1);
		if (!$arearr) {
			SetFlash("err_msg", "Tentukan dahulu kriteria GCG pada tahun $tahun");
			redirect(current_url());
		}
		$this->conn->StartTrans();
		$ret = true;
		foreach ($arearr as $r) {
			if (!$ret)
				break;

			foreach ($r['sub1'] as $r1) {
				if (!$ret)
					break;

				foreach ($r1['sub2'] as $r2) {
					if (!$ret)
						break;

					foreach ($r2['sub3'] as $r3) {
						if (!$ret)
							break;

						if ($r3['sub4'])
							foreach ($r3['sub4'] as $r4) {
								if (!$ret)
									break;


								// if ($this->data['id_unit'])
								// 	$this->data['mtunitarr'] = array($this->data['id_unit'] => $this->data['id_unit']);

								// unset($this->data['mtunitarr']['']);

								// foreach ($this->data['mtunitarr'] as $k => $v) {
								// 	if (!$ret)
								// 		break;

								$record = array();
								$record['id_interval'] = $r4['id_interval'];
								$record['id_kriteria'] = $r4['id_kriteria'];
								$record['is_aktif'] = 1;
								$record['id_unit'] = $r4['id_unit'];
								$record['nilai_target'] = $this->post['nilai_target'];
								$record['tgl_penilaian'] = $this->data['tgl'];
								$record['tgl_next'] = $this->data['tgl_next'];
								$record['id_penilaian_session'] = $this->data['id_penilaian_session'];

								$id_penilaian_periode = $this->conn->GetOne("select id_penilaian_periode from penilaian_periode
											where /*id_unit = " . $this->conn->escape($record['id_unit']) . "
											and*/ date_format(tgl_penilaian,'%Y-%m-%d') = " . $this->conn->escape($record['tgl_penilaian']) . "
											and id_penilaian_session = " . $this->conn->escape($this->data['id_penilaian_session']) . "
											and id_kriteria = " . $this->conn->escape($record['id_kriteria']) . "
											and id_interval = " . $this->conn->escape($record['id_interval']));

								if (!$id_penilaian_periode) {
									$ret = $this->conn->goInsert('penilaian_periode', $record);

									$id_penilaian_periode = $this->conn->GetOne("select id_penilaian_periode from penilaian_periode
											where /*id_unit = " . $this->conn->escape($record['id_unit']) . "
											and*/ date_format(tgl_penilaian,'%Y-%m-%d') = " . $this->conn->escape($record['tgl_penilaian']) . "
											and id_penilaian_session = " . $this->conn->escape($this->data['id_penilaian_session']) . "
											and id_kriteria = " . $this->conn->escape($record['id_kriteria']) . "
											and id_interval = " . $this->conn->escape($record['id_interval']));

									$arr = array();
									loop_periode(
										array('tahun' => $tahun, 'id_interval' => $r4['id_interval']),
										function ($arr, &$ret) {
											$ret[$arr['tgl']] = $arr['listbulan'][$arr['bulan']];
										},
										$arr
									);

									if ($r4['is_upload'])
										foreach ($arr as $k => $v) {
											if (!$ret)
												break;

											if (strtotime($k) >= strtotime($this->data['tgl']) && strtotime($k) < strtotime($this->data['tgl_next'])) {
												$record1 = array();
												$record1['tgl'] = $k;
												$record1['tgl_label'] = $v;
												$record1['status'] = 0;
												$record1['id_penilaian_periode'] = $id_penilaian_periode;
												$ret = $this->conn->goInsert("penilaian", $record1);
											}
										}
								}
								// }
							}
						else {

							$record = array();
							$record['id_interval'] = $r3['id_interval'];
							$record['id_kriteria'] = $r3['id_kriteria'];
							$record['is_aktif'] = 1;
							$record['id_unit'] = $r3['id_unit'];
							$record['nilai_target'] = $this->post['nilai_target'];
							$record['tgl_penilaian'] = $this->data['tgl'];
							$record['tgl_next'] = $this->data['tgl_next'];
							$record['id_penilaian_session'] = $this->data['id_penilaian_session'];

							$id_penilaian_periode = $this->conn->GetOne("select id_penilaian_periode from penilaian_periode
											where /*id_unit = " . $this->conn->escape($record['id_unit']) . "
											and*/ date_format(tgl_penilaian,'%Y-%m-%d') = " . $this->conn->escape($record['tgl_penilaian']) . "
											and id_penilaian_session = " . $this->conn->escape($this->data['id_penilaian_session']) . "
											and id_kriteria = " . $this->conn->escape($record['id_kriteria']) . "
											and id_interval = " . $this->conn->escape($record['id_interval']));

							if (!$id_penilaian_periode) {
								$ret = $this->conn->goInsert('penilaian_periode', $record);
							}
						}
					}
				}
			}
		}

		if ($ret) {
			$this->model->conn->trans_commit();
			SetFlash('suc_msg', "Sukses");
		} else {
			$this->model->conn->trans_rollback();
			SetFlash('err_msg', "Gagal");
		}

		redirect(current_url());
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

		if ($this->request['act'] == 'set_filter')
			redirect(current_url());
	}

	private function get_detail()
	{
		$id_kriteria = $this->post['id_kriteria'];
		$id_penilaian_periode = $this->post['id_penilaian_periode'];

		$this->data['row'] = $row = $this->model->GetByPk($id_penilaian_periode);
		$this->data['rowkriteria'] = $rowkriteria = $this->mkriteria->GetByPk($row['id_kriteria']);

		$this->data['id_interval'] = $id_interval = $row['id_interval'];

		if (!$this->data['mtperiodearr2'][$id_interval])
			$this->data['mtperiodearr2'][$id_interval] = $this->conn->GetRow("select * from mt_interval where id_interval = " . $this->conn->escape($id_interval));

		$this->data['konversi'] = $konversi = $this->data['mtperiodearr2'][$id_interval]['konversi'];

		$this->data['is_admin'] = $is_admin = Access("edit", "panelbackend/mt_kriteria");
		$edited = $_SESSION[SESSION_APP]['login'];
		if ($_SESSION[SESSION_APP]['id_unit'] != $row['id_unit'])
			$edited = false;
		if ($is_admin)
			$edited = true;

		$edited = ($edited && ($row['status'] == '2' or !$row['status']));

		$this->data['edited'] = $edited;

		if ($edited && $this->post['act1'] == 'generate_tgl') {
			$record1 = array();
			$record1['tgl'] = date('d-m-Y');
			$record['status'] = 0;
			$record1['tgl_label'] = date('d ') . ListBulan()[str_pad(date('m'), 2, "0", STR_PAD_LEFT)] . date(' Y');
			$record1['id_penilaian_periode'] = $id_penilaian_periode;
			$ret = $this->conn->goInsert("penilaian", $record1);
		}

		$this->data['rows'] = $this->conn->GetArray("select * 
			from penilaian 
			where id_penilaian_periode = " . $this->conn->escape($id_penilaian_periode) . " 
			and tgl <= sysdate()
			order by tgl asc");

		$rows = $this->conn->GetArray("select pf.*, 
		pl.id_penilaian1 as id_penilaian_link
		from penilaian_link pl
		join penilaian_files pf on pl.id_penilaian2 = pf.id_penilaian
		where pl.id_penilaian_periode1 = " . $this->conn->escape($id_penilaian_periode));

		foreach ($rows as $r) {
			$this->data['row']['file_' . $r['id_penilaian_link']]['name'][] = $r['client_name'];
			$this->data['row']['file_' . $r['id_penilaian_link']]['id'][] = $r[$this->modelfile->pk];
		}

		// $attribute = $this->conn->GetOne("select 1 
		// 	from kriteria_link1 kl
		// 	join mt_kriteria_attribute k1 on kl.id_kriteria2 = k1.id_kriteria
		// 	where kl.id_kriteria1 = " . $this->conn->escape($id_kriteria) . " 
		// 	order by id_kriteria_attribute");

		foreach ($this->data['rows'] as &$r) {
			$row = $this->conn->GetRow("select * from penilaian_komentar where id_penilaian = " . $this->conn->escape($r['id_penilaian']));

			$r['id_penilaian_komentar'] = $row['id_penilaian_komentar'];
			$r['komentar'] = $row['komentar'];

			$rows = $this->conn->GetArray("select * from penilaian_detail where id_penilaian = " . $this->conn->escape($r['id_penilaian']));
			foreach ($rows as $rr) {
				foreach ($rr as $k => $v) {
					$r[$k . "_" . $rr['jenis']] = $v;
				}
			}
			// $r['attribute'] = $attribute;
		}

		$this->PartialView("panelbackend/penilaian_lk3input");
	}


	public function rekap_paramater($id_kategori = null)
	{
		$id_kategori = 1;
		$this->_filter();
		$this->_beforeDetail($id_kategori);
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

		$this->data['rows'] = [];
		if ($this->data['tgl']) {
			$this->data['rows'] = $this->model->rekapPar($this->data['id_unit'], $this->data['tgl'], $this->data['id_penilaian_session']);
		}

		// $this->data['nobutton'] = true;
		// $this->data['buttonMenu'] = "";
		// $this->data['buttonMenu'] .= UI::createSelect('id_unit', $this->data['mtunitarr'], $this->data['id_unit'], true, 'form-control ', "style='width:auto;display:inline;' onchange='goSubmit(\"set_filter\");'");
		// $this->data['buttonMenu'] .= UI::createTextNumber('tahun', $this->data['tahun'], 4, '', true, 'form-control ', "style='width:80px;display:inline;' onchange='goSubmit(\"set_filter\");'", '');
		// if ($this->data['periodedetailarr']) {
		// 	// $this->data['buttonMenu'] .= "<label class='control-label'>" . substr($this->data['mtperiodearr'][$id_interval], 0, -2) . " : &nbsp;";
		// 	$this->data['buttonMenu'] .= UI::createSelect('tgl', $this->data['periodedetailarr'], $this->data['tgl'], true, 'form-control ', "style='width:auto;display:inline;' onchange='goSubmit(\"set_filter\");'");
		// }

		$this->View("panelbackend/penilaian_rekap_paramater" . $this->viewadd);
	}

	public function rekap_indikator($id_kategori = null)
	{
		$id_kategori = 1;
		$this->_filter();
		$this->_beforeDetail($id_kategori);
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

		$this->data['rows'] = [];
		if ($this->data['tgl']) {
			$this->data['rows'] = $this->model->rekapIndikator($this->data['id_unit'], $this->data['tgl'], $this->data['id_penilaian_session']);
		}

		// $this->data['nobutton'] = true;
		// $this->data['buttonMenu'] = "";
		// $this->data['buttonMenu'] .= UI::createSelect('id_unit', $this->data['mtunitarr'], $this->data['id_unit'], true, 'form-control ', "style='width:auto;display:inline;' onchange='goSubmit(\"set_filter\");'");
		// $this->data['buttonMenu'] .= UI::createTextNumber('tahun', $this->data['tahun'], 4, '', true, 'form-control ', "style='width:80px;display:inline;' onchange='goSubmit(\"set_filter\");'", '');
		// if ($this->data['periodedetailarr']) {
		// 	// $this->data['buttonMenu'] .= "<label class='control-label'>" . substr($this->data['mtperiodearr'][$id_interval], 0, -2) . " : &nbsp;";
		// 	$this->data['buttonMenu'] .= UI::createSelect('tgl', $this->data['periodedetailarr'], $this->data['tgl'], true, 'form-control ', "style='width:auto;display:inline;' onchange='goSubmit(\"set_filter\");'");
		// }

		$this->View("panelbackend/penilaian_rekap_indikator" . $this->viewadd);
	}

	public function rekap_aspek($id_kategori = null)
	{
		$id_kategori = 1;
		$this->_filter();
		$this->_beforeDetail($id_kategori);
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

		$this->data['rows'] = [];
		if ($this->data['tgl']) {
			$this->data['rows'] = $this->model->rekapAspek($this->data['id_unit'], $this->data['tgl'], $this->data['id_penilaian_session']);
		}

		// $this->data['nobutton'] = true;
		// $this->data['buttonMenu'] = "";
		// $this->data['buttonMenu'] .= UI::createSelect('id_unit', $this->data['mtunitarr'], $this->data['id_unit'], true, 'form-control ', "style='width:auto;display:inline;' onchange='goSubmit(\"set_filter\");'");
		// $this->data['buttonMenu'] .= UI::createTextNumber('tahun', $this->data['tahun'], 4, '', true, 'form-control ', "style='width:80px;display:inline;' onchange='goSubmit(\"set_filter\");'", '');
		// if ($this->data['periodedetailarr']) {
		// 	// $this->data['buttonMenu'] .= "<label class='control-label'>" . substr($this->data['mtperiodearr'][$id_interval], 0, -2) . " : &nbsp;";
		// 	$this->data['buttonMenu'] .= UI::createSelect('tgl', $this->data['periodedetailarr'], $this->data['tgl'], true, 'form-control ', "style='width:auto;display:inline;' onchange='goSubmit(\"set_filter\");'");
		// }

		$this->View("panelbackend/penilaian_rekap_aspek" . $this->viewadd);
	}

	public function Index($id_penilaian_session = null)
	{
		$is_admin = Access("edit", "panelbackend/mt_kriteria");
		$this->_filter();
		$this->_beforeDetail($id_penilaian_session);
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

		// if ($this->post['act'] == 'get_attribute') {
		// 	$this->get_attribute();
		// 	exit();
		// }

		if ($this->post['act'] == 'get_detail') {
			$this->get_detail();
			exit();
		}

		if ($this->request['act'] == 'update_aktif' && $is_admin) {
			// $this->conn->debug = 1;
			$this->conn->goUpdate("penilaian_periode", array('is_aktif' => (int)($this->request['is_aktif'] == 'true')), "id_penilaian_periode = " . $this->conn->escape($this->request['id_penilaian_periode']));
			exit();
		}

		if ($this->request['act'] == 'update_keterangan' && $is_admin) {
			$id_penilaian_komentar = $this->request['id_penilaian_komentar'];
			$this->_upsertKomentar($this->request['keterangan'], $this->request['id_penilaian'], $id_penilaian_komentar);
			exit();
		}

		if ($this->request['act'] == 'update_status') {
			$ret = array("success" => $this->update_status($this->request['id_penilaian'], (int)$this->request['status']));

			echo json_encode($ret);
			exit();
		}

		if ($this->request['act'] == 'update_penilaian' && $is_admin) {
			$ret = array("success" => $this->update_penilaian($this->request['id_penilaian'], $this->request));

			echo json_encode($ret);
			exit();
		}


		if ($this->post['act'] == 'downloadall') {
			$this->downloadall();
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

		if ($this->data['tgl']) {
			$tgl_penilaian = $this->data['tgl'];
			// $this->conn->debug = 1;
			$this->data['arearr'] = $this->model->get_kriteria_penilaian($id_kategori, $this->data['id_unit'], $tgl_penilaian, $this->data['id_penilaian_session']);
			// echo json_encode($this->data['arearr']);
			// die();
			// dpr($this->data['arearr'], 1);

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
						where /*id_unit = " . $this->conn->escape($id_unit) . "
						and*/ id_kategori = " . $this->conn->escape($id_kategori) . "
						and id_penilaian_session = " . $this->conn->escape($this->data['id_penilaian_session']) . "
						and date_format(tgl_penilaian,'%Y-%m-%d') = " . $this->conn->escape($tgl_penilaian));

					if (!$this->data['nilai_target'])
						$this->data['nilai_target'] = $nilai_target;

					$cek = !$nilai_target;

					if ($cek)
						break;
				}

				$this->data['is_generate'] = $cek;
			}
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
			$this->PartialView("panelbackend/penilaian_lk3table");
			exit();
		}

		$this->access_role['add'] = false;
		// $this->data['nobutton'] = true;
		$this->data['buttonMenu'] = "";
		// $this->data['buttonMenu'] .= UI::createSelect('id_unit', $this->data['mtunitarr'], $this->data['id_unit'], true, 'form-control ', "style='width:auto;display:inline;' onchange='goSubmit(\"set_filter\");'");
		// $this->data['buttonMenu'] .= UI::createTextNumber('tahun', $this->data['tahun'], 4, '', true, 'form-control ', "style='width:80px;display:inline;' onchange='goSubmit(\"set_filter\");'", '');
		// if ($this->data['periodedetailarr']) {
		// 	// $this->data['buttonMenu'] .= "<label class='control-label'>" . substr($this->data['mtperiodearr'][$id_interval], 0, -2) . " : &nbsp;";
		// 	$this->data['buttonMenu'] .= UI::createSelect('tgl', $this->data['periodedetailarr'], $tgl, true, 'form-control ', "style='width:auto;display:inline;' onchange='goSubmit(\"set_filter\");'");
		// }
		// if ($this->data['rowheader']['id_kategori_jenis'] != '3') {
		// 	$this->data['buttonMenu'] .= "<label class='control-label' style='align-self: center'>&nbsp;&nbsp;&nbsp;Target Nilai : </label>";
		// 	if ($is_admin && $this->data['is_generate']) {
		// 		$this->data['buttonMenu'] .= UI::createTextNumber('nilai_target', $nilai_target, 4, '', $this->data['is_generate'], 'form-control ', "style='width:80px;display:inline;'");
		// 	} else {
		// 		$this->data['buttonMenu'] .=  '<div style="display: inline; width:80px">' . $nilai_target . '</div>';
		// 	}
		// }

		if ($is_admin && $this->data['is_generate'] && $this->data['id_parent']) {
			// 		$this->data['buttonMenu'] .= "&nbsp;&nbsp; <button class=\"btn btn-primary\" type=\"button\" onclick=\"if($('#nilai_target').val()==''){alert('Target nilai harus diisi');}else{if(confirm('Apakah Anda akan me-generate penilaian pada " . $this->data['mtunitarr'][$id_unit] . "')){goSubmit('generate')}}\">
			// 	<i class=\"glyphicon glyphicon-refresh\"></i>
			// 	Generate
			// </button>";
			$this->data['buttonMenu'] .= "&nbsp;&nbsp; <button class=\"btn btn-primary\" type=\"button\" onclick=\"if(confirm('Apakah Anda akan me-generate')){goSubmit('generate')}\">
			<i class=\"glyphicon glyphicon-refresh\"></i>
			Generate
			</button>";
		} else {
			$this->data['buttonMenu'] .= "&nbsp;&nbsp;<a target='_blank' href='" . site_url("panelbackend/penilaian/go_print") . "' class=\"btn btn-sm btn-primary\"><i class='bi bi-printer'></i> Print</a>";
			$this->data['buttonmenu'] .= "&nbsp;&nbsp;<a href=\"javascript:void(0)\" onclick=\"goSubmit('downloadall')\" class=\"btn btn-sm btn-primary\"><span class=\"glyphicon glyphicon-download\"></span> Download All Files</a>";
		}
		$this->View($this->viewlist . $this->viewadd);
	}


	public function go_print($id_kategori = null)
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";
		$this->data['no_header'] = true;
		$id_kategori = 2;
		$this->_filter();
		$this->_beforeDetail($id_kategori);
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

		if ($this->data['tgl']) {
			$tgl_penilaian = $this->data['tgl'];

			$this->data['arearr'] = $this->model->get_kriteria_penilaian($id_kategori, $this->data['id_unit'], $tgl_penilaian);
			// echo json_encode($this->data['arearr']);
			// die();
			// dpr($this->data['arearr'], 1);

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
						where id_unit = " . $this->conn->escape($id_unit) . "
						and id_kategori = " . $this->conn->escape($id_kategori) . "
						and id_penilaian_session = " . $this->conn->escape($this->data['id_penilaian_session']) . "
						and date_format(tgl_penilaian,'%Y-%m-%d') = " . $this->conn->escape($tgl_penilaian));

					if (!$this->data['nilai_target'])
						$this->data['nilai_target'] = $nilai_target;

					$cek = !$nilai_target;

					if ($cek)
						break;
				}

				$this->data['is_generate'] = $cek;
			}
		}

		if ($this->data['is_generate'])
			$this->data['mtunitarr'][''] = 'Semua unit';

		$this->data['background'] = array(
			'hitam' => '#333',
			'merah' => '#ff4545',
			'biru' => '#3f89ff',
		);
		$this->View($this->viewprint . $this->viewadd);
	}

	private function delTree($dir)
	{
		$files = array_diff(scandir($dir), array('.', '..'));
		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : @unlink("$dir/$file");
		}
		return @rmdir($dir);
	}

	private function downloadall()
	{
		$tgl_penilaian = $this->data['tgl'];
		$id_unit = $this->data['id_unit'];
		$id_kategori = $this->data['id_kategori'];

		if ($this->data['periodedetailarr'])
			$interval = $this->data['tahun'] . '-' . $this->data['periodedetailarr'][$tgl_penilaian];
		else
			$interval = $this->data['tahun'];

		$unit = $this->data['mtunitarr'][$id_unit];
		$kategori = $this->data['mtkategoriarr'][$id_kategori];

		if (!file_exists("temp"))
			mkdir("temp");

		$name_folder = strtoupper($kategori) . '-' . $unit . '-' . $interval;
		$folder = "temp/$name_folder";

		if (file_exists($folder))
			$this->delTree($folder);

		mkdir($folder);

		$where = " and pp.is_aktif = 1 ";

		if (strlen($tgl_penilaian) == 10)
			$where .= " AND DATE_FORMAT(pp.tgl_penilaian,'%Y-%m-%d')=" . $this->conn->escape($tgl_penilaian);

		if ($id_unit)
			$where .= " and k.id_unit = " . $this->conn->escape($id_unit);

		$rows = $this->conn->GetArray("select 
		k.id_kriteria,
		concat(k3.kode,'.',k2.kode,'.',k1.kode,'.',k.kode) kode, 
		pp.id_penilaian_periode
		from penilaian_periode pp
		left join mt_kriteria k on k.id_kriteria = pp.id_kriteria
		left join mt_kriteria k1 on k1.id_kriteria = k.id_kriteria_parent
		left join mt_kriteria k2 on k2.id_kriteria = k1.id_kriteria_parent
		left join mt_kriteria k3 on k3.id_kriteria = k2.id_kriteria_parent
		left join mt_kategori kg on k.id_kategori = kg.id_kategori
		where 1=1
		$where 
		and id_penilaian_session = " . $this->conn->escape($this->data['id_penilaian_session']) . "						
		and k.id_kategori = " . $this->conn->escape($id_kategori) . "
		ORDER BY k.kode+0 ASC, k.id_kriteria asc");

		foreach ($rows as $r) {
			$kode = $r['kode'];
			$id_penilaian_periode = $r['id_penilaian_periode'];

			// $is_attr = $this->conn->GetOne("select 1 from mt_kriteria_attribute where id_kriteria = " . $this->conn->escape($r['id_kriteria']));

			$rs = $this->conn->GetArray("select * from penilaian where id_penilaian_periode = " . $this->conn->escape($id_penilaian_periode));

			if ($rs) {
				if (!file_exists($folder . "/" . $kode))
					mkdir($folder . "/" . $kode);
			}

			foreach ($rs as $r1) {
				$tgl_label = "Periode-" . $r1['tgl_label'];

				$id_penilaian = $r1['id_penilaian'];

				$rs1 = $this->conn->GetArray("select pf.*, 
					pl.id_penilaian1 as id_penilaian_link
					from penilaian_link pl
					join penilaian_files pf on pl.id_penilaian2 = pf.id_penilaian
					where pl.id_penilaian1 = " . $this->conn->escape($id_penilaian));

				if ($rs1) {
					if (!file_exists($folder . "/" . $kode . "/" . $tgl_label))
						mkdir($folder . "/" . $kode . "/" . $tgl_label);

					foreach ($rs1 as $r2) {
						$full_path = $this->data['configfile']['upload_path'] . $r2['file_name'];
						$folder1 = $folder . "/" . $kode . "/" . $tgl_label;

						$fs = explode("/", $r2['client_name']);
						$client_name = $fs[count($fs) - 1];
						unset($fs[count($fs) - 1]);

						if ($fs)
							foreach ($fs as $k => $v) {
								$folder1 .= "/" . $v;

								if (!file_exists($folder1))
									mkdir($folder1);
							}

						if (file_exists($full_path)) {
							file_put_contents($folder1 . "/" . $client_name, file_get_contents($full_path));
						}
					}
				}
			}
			// }
		}

		$filename = $this->zipfile($folder);

		if ($filename && file_exists($filename)) {
			// header("Content-Type: application/zip");
			// header("Content-Disposition: inline; filename=".'"'.$name_folder.'.zip"');

			// $file = fopen($filename, "r");
			// while(! feof($file)) {
			//   $line = fgets($file);
			//   echo $line;
			// }
			// fclose($file);
			redirect("$folder.zip");
			die();
		} else {
			$this->data['err_msg'] = "cannot open $filename";
		}
	}

	private function zipfile($folder)
	{
		$zip = new ZipArchive();
		$filename = "$folder.zip";
		@unlink($filename);

		if ($zip->open($filename, ZipArchive::CREATE) !== TRUE) {
			$this->data['err_msg'] = "cannot open $filename";
			return false;
		} else {
			$this->filezip($zip, $folder);
			$zip->close();
			// $this->delTree($folder);

			return $filename;
		}
	}

	private function filezip($zip, $folder)
	{
		foreach (glob($folder . '/*') as $node) {
			if (!is_dir($node))
				$zip->addFile($node, str_replace("temp/", "", $node));
			else
				$this->filezip($zip, $node);
		}
	}

	protected function _beforeDetail($id_penilaian_session = null, $id = null)
	{
		dpr('testtign', 1);
		$this->data['rowheader1'] = $this->penilaiansession->GetByPk($id_penilaian_session);
		$id_kategori = $this->data['rowheader1']['id_kategori'];
		if ($id_kategori <> $this->id_kategori) {
			$this->Error404();
		}

		// $this->kategoriarr($id_kategori);
		$this->data['id_kategori'] = $this->id_kategori = $id_kategori;
		$this->data['rowheader'] = $this->mtkategori->GetByPk($id_kategori);
		$this->data['add_param'] = $id_penilaian_session;
		$this->id_kategori_jenis = $this->data['id_kategori_jenis'] = $this->data['rowheader']['id_kategori_jenis'];

		$this->data['broadcrum'] = [];
		$this->data['broadcrum'][] = [
			"url" => site_url("panelbackend/penilaian_" . $this->viewadd . "/index/$id_penilaian_session"),
			"label" => $this->data['rowheader1']['nama']
		];
	}

	protected function _uploadFiles($jenis_file = null, $id = null)
	{
		$name = $_FILES[$jenis_file]['name'];
		$jenis_file1 = str_replace("upload", "", $jenis_file);
		$this->data['configfile']['file_name'] = $jenis_file . time() . $name;

		$this->load->library('upload', $this->data['configfile']);

		if (!$this->upload->do_upload($jenis_file)) {
			$return = array('error' => "File $name gagal upload, " . strtolower(str_replace(array("<p>", "</p>"), "", $this->upload->display_errors())));
		} else {
			$upload_data = $this->upload->data();

			$record = array();

			list($jenis, $id_penilaian, $nourut, $id_penilaian_detail) = explode("_", $jenis_file1);

			if ($this->post['folder'])
				$upload_data['client_name'] = $this->post['folder'];

			$record['client_name'] = $upload_data['client_name'];
			$record['file_name'] = $upload_data['file_name'];
			$record['file_type'] = $upload_data['file_type'];
			$record['file_size'] = $upload_data['file_size'];
			$record['jenis_file'] = $jenis_file;
			$record['id_penilaian'] = $id_penilaian;
			$record['id_penilaian_detail'] = $id_penilaian_detail;

			$ret = $this->modelfile->Insert($record);

			if ($ret['success']) {
				$cek = $this->conn->GetOne("select status from penilaian where id_penilaian = " . $this->conn->escape($id_penilaian) . " and status = 2");

				if ($cek)
					$ret = $this->update_status($id_penilaian, 0);

				$return = array('file' => array("id" => $ret['data'][$this->modelfile->pk], "name" => $upload_data['client_name']));
			} else {
				unlink($upload_data['full_path']);
				$return = array('errors' => "File $name gagal upload");
			}
		}

		return $return;
	}

	private function update_status($id_penilaian, $status)
	{

		$rows = $this->conn->GetArray("select pl.id_penilaian2 as id_penilaian 
			from penilaian_link pl
			where pl.id_penilaian1 = " . $this->conn->escape($id_penilaian));

		$ret = true;
		foreach ($rows as $r) {
			$id_penilaian = $r['id_penilaian'];
			if (!$ret)
				break;

			$ret = $this->conn->goUpdate("penilaian", array('status' => (int)$status), "id_penilaian = " . $this->conn->escape($id_penilaian));
		}

		return $ret;
	}

	private function update_penilaian($id_penilaian, $record)
	{

		$rows = $this->conn->GetArray("select pl.id_penilaian2 as id_penilaian 
			from penilaian_link pl
			where pl.id_penilaian1 = " . $this->conn->escape($id_penilaian));

		$ret = true;
		foreach ($rows as $r) {
			$id_penilaian = $r['id_penilaian'];
			if (!$ret)
				break;

			$ret = $this->conn->goUpdate("penilaian", array('status' => (int)$record['status']), "id_penilaian = " . $this->conn->escape($id_penilaian));
		}

		if ($ret)
			$ret = $this->_upsertDetail($id_penilaian, 'rd', $record);

		if ($ret)
			$ret = $this->_upsertDetail($id_penilaian, 'k', $record);

		if ($ret)
			$ret = $this->_upsertDetail($id_penilaian, 'w', $record);

		if ($ret)
			$ret = $this->_upsertDetail($id_penilaian, 'o', $record);

		return $ret;
	}

	private function _upsertDetail($id_penilaian, $jenis = null, $record = array())
	{
		$r = [
			"id_penilaian" => $id_penilaian,
			"jenis" => $jenis,
			"skor" => (float)$record['skor_' . $jenis],
			"tgl" => $record['tgl_' . $jenis],
			"simpulan" => $record['simpulan_' . $jenis],
			"saran" => $record['saran_' . $jenis],
		];

		$id_penilaian_detail = $this->conn->GetOne("select 
		id_penilaian_detail 
		from penilaian_detail 
		where jenis = " . $this->conn->escape($jenis) . " 
		and id_penilaian = " . $this->conn->escape($id_penilaian));

		if (!$id_penilaian_detail) {
			return $this->conn->goInsert("penilaian_detail", $r);
		} else {
			return $this->conn->goUpdate("penilaian_detail", $r, "id_penilaian_detail = " . $this->conn->escape($id_penilaian_detail));
		}
	}

	private function _upsertKomentar($keterangan, $id_penilaian, $id_penilaian_komentar)
	{
		$record = array("id_penilaian" => $id_penilaian, "komentar" => $keterangan);

		if ($id_penilaian_komentar)
			$this->conn->goUpdate("penilaian_komentar", $record, "id_penilaian_komentar = " . $this->conn->escape($id_penilaian_komentar));
		else {
			$this->conn->goInsert("penilaian_komentar", $record);
			$id_penilaian_komentar = $this->conn->GetOne("select max(id_penilaian_komentar) from penilaian_komentar where id_penilaian = " . $this->conn->escape($id_penilaian));
		}

		$ret = array("id_penilaian_komentar" => $id_penilaian_komentar);

		echo json_encode($ret);
		exit();
	}
}
