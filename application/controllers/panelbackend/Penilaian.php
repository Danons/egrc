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
		$this->load->model("Dokumen_filesModel", "modelfile");
		$this->load->model("Mt_kriteriaModel", "mkriteria");
		$this->load->model("Penilaian_sessionModel", "penilaiansession");
		$this->load->model("Mt_kategoriModel", "mtkategori");
		// $this->load->model("Penilaian_filesModel", "modelfile");

		// $this->data['mtkategoriarr'] = $this->mtkategori->GetCombo();

		$this->load->model("Mt_intervalModel", "mtinterval");
		// $this->data['mtperiodearr2'] = $this->mtperiode->GetCombo2();
		$this->data['mtintervalarr'] = $this->mtinterval->GetCombo();

		$this->load->model("Mt_sdm_unitModel", "mtunit");
		$this->data['mtunitarr'] = $this->mtunit->GetCombo();



		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'upload', 'datepicker'
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
		where deleted_date is null and date_format(tgl,'%Y-%m-%d') = " . $this->conn->escape($tgl) . " 
		and page_ctrl = " . $this->conn->escape($this->page_ctrl));
	}

	private function _generateSub($arearr, $tahun, $level = 0)
	{
		$level++;
		$ret = true;
		foreach ($arearr as $r) {
			if (!$ret)
				break;

			if ($r['sub' . $level]) {
				$ret = $this->_generateSub($r['sub' . $level], $tahun, $level);
			} else {
				$record = array();
				$record['id_interval'] = $r['id_interval'];
				$record['id_kriteria'] = $r['id_kriteria'];
				$record['is_aktif'] = 1;
				$record['id_unit'] = $r['id_unit'];
				$record['nilai_target'] = $this->post['nilai_target'];
				$record['tgl_penilaian'] = $this->data['tgl'];
				$record['tgl_next'] = $this->data['tgl_next'];
				$record['id_penilaian_session'] = $this->data['id_penilaian_session'];

				$id_penilaian_periode = $this->conn->GetOne("select id_penilaian_periode from penilaian_periode
							where deleted_date is null and  /*id_unit = " . $this->conn->escape($record['id_unit']) . "
							and*/ date_format(tgl_penilaian,'%Y-%m-%d') = " . $this->conn->escape($record['tgl_penilaian']) . "
							and id_penilaian_session = " . $this->conn->escape($this->data['id_penilaian_session']) . "
							and id_kriteria = " . $this->conn->escape($record['id_kriteria']) . "
							and id_interval = " . $this->conn->escape($record['id_interval']));

				if (!$id_penilaian_periode) {
					$ret = $this->conn->goInsert('penilaian_periode', $record);

					$id_penilaian_periode = $this->conn->GetOne("select id_penilaian_periode from penilaian_periode
							where  deleted_date is null and /*id_unit = " . $this->conn->escape($record['id_unit']) . "
							and*/ date_format(tgl_penilaian,'%Y-%m-%d') = " . $this->conn->escape($record['tgl_penilaian']) . "
							and id_penilaian_session = " . $this->conn->escape($this->data['id_penilaian_session']) . "
							and id_kriteria = " . $this->conn->escape($record['id_kriteria']) . "
							and id_interval = " . $this->conn->escape($record['id_interval']));

					$arr = array();
					loop_periode(
						array('tahun' => $tahun, 'id_interval' => $r['id_interval']),
						function ($arr, &$ret) {
							$ret[$arr['tgl']] = $arr['listbulan'][$arr['bulan']];
						},
						$arr
					);

					// if ($r['is_upload'])
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
			}
		}

		return $ret;
	}

	private function _generate($tahun, $id_kategori)
	{
		// PROSES INPUT PERIODE PENILAIAN
		$id_parent = $this->data['id_parent'];
		$arearr = $this->mkriteria->get_kriteria($id_parent, 0, true);

		// dpr($arearr, 1);
		if (!$arearr) {
			SetFlash("err_msg", "Tentukan dahulu kriteria GCG pada tahun $tahun");
			redirect(current_url());
		}
		$this->conn->StartTrans();
		$ret = $this->_generateSub($arearr, $tahun);

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
		if (!$this->data['tahun'])
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

	private function get_detail($id_penilaian_session_sebelummnya = null)
	{
		// $this->conn->debug = 1;
		$id_kriteria = $this->post['id_kriteria'];
		$id_penilaian_periode = $this->post['id_penilaian_periode'];


		$this->data['row'] = $row = $this->model->GetByPk($id_penilaian_periode);
		$this->data['rowkriteria'] = $rowkriteria = $this->mkriteria->GetByPk($row['id_kriteria']);

		if ($id_penilaian_session_sebelummnya) {
			$rowssebelum1 = $this->conn->GetArray("
			SELECT pp.id_kriteria , pd.skor, pd.jenis FROM penilaian_session ps LEFT JOIN 
			penilaian_periode pp ON ps.id_penilaian_session = pp.id_penilaian_session 
			AND ps.id_penilaian_session = " . $this->conn->escape($this->data['penilaiansessionsebelumnya']) . " 
			LEFT JOIN penilaian p ON pp.id_penilaian_periode = p.id_penilaian_periode 
			LEFT JOIN penilaian_detail pd ON p.id_penilaian = pd.id_penilaian where ps.deleted_date is null and pp.id_kriteria = " . $id_kriteria);
		}

		if ($rowssebelum1) {
			foreach ($rowssebelum1 as $r) {
				$rowkriteria['nilai_sebelum'][$r['jenis']] = $r['skor'];
				$this->data['rowkriteria']['nilai_sebelum'][$r['jenis']] = $r['skor'];
			}
		}

		$this->data['id_interval'] = $id_interval = $row['id_interval'];

		if (!$this->data['mtperiodearr2'][$id_interval])
			$this->data['mtperiodearr2'][$id_interval] = $this->conn->GetRow("select * from mt_interval where deleted_date is null and id_interval = " . $this->conn->escape($id_interval));

		$this->data['konversi'] = $konversi = $this->data['mtperiodearr2'][$id_interval]['konversi'];

		$this->data['is_admin'] = $is_admin = $this->access_role['edit'];
		$edited = $_SESSION[SESSION_APP]['login'];
		if ($_SESSION[SESSION_APP]['id_unit'] != $row['id_unit'])
			$edited = false;
		if ($is_admin)
			$edited = true;

		$edited = ($edited && ($row['status'] == '2' or !$row['status']));

		$this->data['edited'] = $edited;

		#untuk lampiran jika dibutuhkan
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
			where deleted_date is null and id_penilaian_periode = " . $this->conn->escape($id_penilaian_periode) . " 
			/*and tgl <= sysdate()*/
			order by tgl asc");

		// dpr($this->data['rows']);

		// $rows = $this->conn->GetArray("select pf.*, 
		// pl.id_penilaian1 as id_penilaian_link
		// from penilaian_link pl
		// join penilaian_files pf on pl.id_penilaian2 = pf.id_penilaian
		// where pl.id_penilaian_periode1 = " . $this->conn->escape($id_penilaian_periode));

		// foreach ($rows as $r) {
		// 	$this->data['row']['file_' . $r['id_penilaian_link']]['name'][] = $r['client_name'];
		// 	$this->data['row']['file_' . $r['id_penilaian_link']]['id'][] = $r[$this->modelfile->pk];
		// }

		// $attribute = $this->conn->GetOne("select 1 
		// 	from kriteria_link1 kl
		// 	join mt_kriteria_attribute k1 on kl.id_kriteria2 = k1.id_kriteria
		// 	where kl.id_kriteria1 = " . $this->conn->escape($id_kriteria) . " 
		// 	order by id_kriteria_attribute");
		foreach ($this->data['rows'] as &$r) {
			// $row = $this->conn->GetRow("select * from penilaian_komentar where id_penilaian = " . $this->conn->escape($r['id_penilaian']));

			// $r['id_penilaian_komentar'] = $row['id_penilaian_komentar'];
			// $r['komentar'] = $row['komentar'];

			$rows = $this->conn->GetArray("select * from penilaian_detail where deleted_date is null and id_penilaian = " . $this->conn->escape($r['id_penilaian']));
			foreach ($rows as $rr) {
				foreach ($rr as $k => $v) {
					$r[$k . "_" . $rr['jenis']] = $v;
				}
			}

			$rows = $this->conn->GetArray("select a.*
			from penilaian_quisioner a 
			where a.deleted_date is null and exists (select 1 from quisioner_kriteria b 
			where a.id_quisioner = b.id_quisioner 
			and b.id_kriteria = " . $this->conn->escape($row['id_kriteria']) . ") 
			and a.id_penilaian_session = " . $this->conn->escape($this->id_penilaian_session));

			$rws = [];
			foreach ($rows as $r1) {
				if ($r1['jenis_jawaban'] == 'uraian') {
					$rws['w'][$r1['id_quisioner']]['pertanyaan'] = $r1['pertanyaan'];
					$rws['w'][$r1['id_quisioner']]['jawaban'][] = $r1['jawaban'];
				} else {
					$rws['k'][$r1['id_quisioner']]['jenis_jawaban'] = $r1['jenis_jawaban'];
					$rws['k'][$r1['id_quisioner']]['id_quisioner'] = $r1['id_quisioner'];
					$rws['k'][$r1['id_quisioner']]['id_quisioner_parent'] = $r1['id_quisioner_parent'];
					$rws['k'][$r1['id_quisioner']]['pertanyaan'] = $r1['pertanyaan'];
					$rws['k'][$r1['id_quisioner']]['jawaban_nilai'][$r1['id_jabatan']] = $r1['nilai'];
					$rws['k'][$r1['id_quisioner']]['jawaban_nilai_sebelum'][$r1['id_jabatan']] = $r1['nilai_sebelum'];
					$rws['k'][$r1['id_quisioner']][$r1['jenis_jawaban']][$r1['nilai']]++;
					$rws['k'][$r1['id_quisioner']]['total']++;
				}
			}

			$idparr = [];
			$temp = $rws['k'];
			if ($temp)
				foreach ($temp as $rw) {
					$idparr[$rw['id_quisioner']] = 1;
				}
			// $this->conn->debug = 1;
			if ($temp)
				foreach ($temp as $rw) {
					if (!$idparr[$rw['id_quisioner_parent']]) {
						$this->_getParent($rw['id_quisioner_parent'], $rws['k'], $idparr);
					}
				}
			// dpr($rws['k']);
			$i = 0;
			$rows1 = [];
			$totalsemua = 0;
			$totalnilaijabatan = [];
			$this->GenerateTree(
				$rws['k'],
				"id_quisioner_parent",
				"id_quisioner",
				"pertanyaan",
				$rows1,
				null,
				$i,
				0,
				null,
				$totalsemua,
				$totalnilaijabatan,
				$totalsemuasebelum,
				$totalnilaijabatansebelum,
			);
			$totalnilai = 0;
			foreach ($totalnilaijabatan as $id_jabatan => $nilai) {
				$totalnilai += $nilai;
			}
			$totalnilaisebelum = 0;
			if ($totalnilaijabatansebelum) {
				foreach ($totalnilaijabatansebelum as $id_jabatan => $nilai) {
					$totalnilaisebelum += $nilai;
				}
			} else {
				$totalnilaisebelum = 0;
			}
			$rws['totalsemua'] = $totalsemua;
			$rws['totalnilai'] = $totalnilai;
			$rws['totalsemuasebelum'] = $totalsemuasebelum;
			$rws['totalnilaisebelum'] = $totalnilaisebelum;
			$rws['k'] = $rows1;

			$r['quisioner'] = $rws;

			$rows = $this->conn->GetArray("select * from dokumen_files a where a.deleted_date is null and  exists (
				select 1 from penilaian_dokumen b 
				where a.id_dokumen_versi = b.id_dokumen_versi 
				and b.id_penilaian = " . $r['id_penilaian'] . " 
			) order by client_name");

			if (!$rows)
				$rows = $this->conn->GetArray("select * from dokumen_files 
				where  deleted_date is null and  id_dokumen_versi in (select max(id_dokumen_versi) 
					from dokumen_versi a where exists (
						select 1 from dokumen_kriteria b where a.id_dokumen = b.id_dokumen 
						and b.id_kriteria = " . $row['id_kriteria'] . " 
					)) order by client_name");

			$r['dokumen'] = $rows;
			// $r['attribute'] = $attribute;
		}

		$this->PartialView("panelbackend/penilaianinput");
	}

	private function _getParent($id_quisioner_parent, &$rows, &$idparr)
	{
		if (!$id_quisioner_parent)
			return;

		$rws = $this->conn->GetArray("select a.*
		from penilaian_quisioner a 
		where a.deleted_date is null and  a.id_quisioner = " . $this->conn->escape($id_quisioner_parent));
		if ($rws) {
			$row = [];
			foreach ($rws as $r1) {
				$row = $r1;
				$row[$r1['jenis_jawaban']][$r1['nilai']]++;
				$row["jawaban_nilai"][$r1['id_jabatan']] = $r1['nilai'];
				$row['total']++;
			}

			$rows[$id_quisioner_parent] = $row;
			$idparr[$id_quisioner_parent] = 1;

			if (!$idparr[$row['id_quisioner_parent']]) {
				$this->_getParent($row['id_quisioner_parent'], $rows, $idparr);
			}
		}
	}

	function GenerateTree(
		&$row,
		$colparent,
		$colid,
		$collabel,
		&$return = array(),
		$valparent = null,
		&$i = 0,
		$level = 0,
		$not_id = null,
		&$totalsemua,
		&$totalnilaijabatan,
		&$totalsemuasebelum,
		&$totalnilaijabatansebelum
	) {
		$level++;
		if ($row)
			foreach ($row as $key => $value) {
				if ($not_id && $value[$colparent] == $not_id)
					continue;

				# code...
				if (trim($value[$colparent]) == trim($valparent)) {
					unset($row[$key]);

					$space = '';
					// for ($l = 0; $l < $level; $l++)
					// 	$space .= "&nbsp;";
					$value['level'] = $level;

					$value[$collabel] = $space . $value[$collabel];
					$return[$i] = $value;

					$i++;
					$totalnilaijabatan1 = [];
					$totalnilaijabatansebelum1 = [];
					$this->GenerateTree(
						$row,
						$colparent,
						$colid,
						$collabel,
						$return,
						$value[$colid],
						$i,
						$level,
						$not_id,
						$totalsemua,
						$totalnilaijabatan1,
						$totalsemuasebelum,
						$totalnilaijabatansebelum1,
					);

					// dpr($totalnilaijabatan1);
					// dpr($value['jenis_jawaban']);
					if (count($totalnilaijabatan1)) {
						foreach ($value['jawaban_nilai'] as $id_jabatan => $nilai) {
							if ($nilai == '5' || !$value['jenis_jawaban']) {
								$totalnilaijabatan[$id_jabatan] += $totalnilaijabatan1[$id_jabatan];
							}
						}
					} else if ($value['jenis_jawaban'] == '1sampai5') {
						foreach ($value['jawaban_nilai'] as $id_jabatan => $nilai) {
							if ($totalnilaijabatan[$id_jabatan] == null || $totalnilaijabatan[$id_jabatan])
								$totalnilaijabatan[$id_jabatan] = 0;
							if ($nilai === null || $nilai === '')
								$nilai = 0;

							$totalnilaijabatan[$id_jabatan] += $nilai;
							$totalsemua += 5;
						}
					}
					if (count($totalnilaijabatansebelum1)) {
						if ($value['jawaban_nilai_sebelum']) {
							foreach ($value['jawaban_nilai_sebelum'] as $id_jabatan => $nilai) {
								if ($nilai == '5' || !$value['jenis_jawaban']) {
									$totalnilaijabatansebelum[$id_jabatan] += $totalnilaijabatansebelum1[$id_jabatan];
								}
							}
						}
					} else if ($value['jenis_jawaban'] == '1sampai5') {
						foreach ($value['jawaban_nilai_sebelum'] as $id_jabatan => $nilai) {
							if (!$nilai)
								$nilai = 0;
							$totalnilaijabatansebelum[$id_jabatan] += $nilai;
							$totalsemuasebelum += 5;
						}
					}
				}
			}

		if ($row && $level == 1 && !$not_id)
			$return = array_merge($return, $row);
	}

	public function rekap_paramater($id_penilaian_session = null)
	{
		// $id_kategori = 1;
		$this->_beforeDetail($id_penilaian_session);
		$this->_filter();
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

	public function oai($id_penilaian_session = null)
	{
		$this->data['page_title'] = 'Simpulan';
		$this->_beforeDetail($id_penilaian_session);
		$this->_filter();
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

		// $this->conn->debug = 1;
		$this->data['rows'] = [];
		if ($this->data['tgl']) {
			$this->data['rows'] = $this->model->getOai($this->data['tgl'], $this->data['id_penilaian_session'], $this->id_kategori_jenis);
		}
		// dpr($this->data['rows'], 1);
		$this->View("panelbackend/penilaianoai");
	}


	public function simpulan($id_penilaian_session = null)
	{
		$this->data['page_title'] = 'Simpulan';
		$this->_beforeDetail($id_penilaian_session);
		$this->_filter();
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
			$this->data['rows'] = $this->model->getKesimpulan($this->data['tgl'], $this->data['id_penilaian_session'], $this->id_kategori_jenis);
		}

		$this->View("panelbackend/penilaiansimpulan");
	}

	public function rekap_indikator($id_penilaian_session = null)
	{
		// $id_kategori = 1;
		$this->_beforeDetail($id_penilaian_session);
		$this->_filter();
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

	public function rekap_aspek($id_penilaian_session = null)
	{
		// $id_kategori = 1;
		$this->_beforeDetail($id_penilaian_session);
		$this->_filter();
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

		if ($this->post['penilaiansessionsebelumnya'] !== null) {
			$_SESSION[SESSION_APP][$this->page_ctrl]['penilaiansessionsebelumnya'] = $this->post['penilaiansessionsebelumnya'];
		}
		$this->data['penilaiansessionsebelumnya'] = $_SESSION[SESSION_APP][$this->page_ctrl]['penilaiansessionsebelumnya'];

		if ($this->data['penilaiansessionsebelumnya']) {
			$this->data['idkategorisebelumnya'] = $this->conn->GetOne("select id_kategori from penilaian_session where deleted_date is null and  id_penilaian_session = " . $this->conn->escape($this->data['penilaiansessionsebelumnya']));
			$_SESSION[SESSION_APP][$this->page_ctrl]['idkategorisebelumnya'] = $this->data['idkategorisebelumnya'];
		}

		$is_admin = $this->access_role['edit'];
		$this->_beforeDetail($id_penilaian_session);
		$this->_filter();
		$this->data['parentarr'] = $this->mkriteria->getComboParent($this->id_kategori);
		$id_kategori = $this->id_kategori;
		if ($this->post['act'] == 'set_parent')
			$_SESSION[SESSION_APP][$this->page_ctrl][$id_kategori]['id_parent'] = $this->post['idkey'];

		if (!$_SESSION[SESSION_APP][$this->page_ctrl][$id_kategori]['id_parent'])
			$_SESSION[SESSION_APP][$this->page_ctrl][$id_kategori]['id_parent'] = array_values($this->data['parentarr'])[0];

		$this->data['id_parent'] = $_SESSION[SESSION_APP][$this->page_ctrl][$id_kategori]['id_parent'];

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['penilaiansessionsebelumnya']) {
			$this->data['penilaiansessionsebelumnya'] = $_SESSION[SESSION_APP][$this->page_ctrl]['penilaiansessionsebelumnya'];
		}


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
			$this->get_detail($this->data['penilaiansessionsebelumnya']);
			exit();
		}

		if ($this->request['act'] == 'update_aktif' && $is_admin) {
			$this->conn->debug = 1;
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

		if ($this->post['act'] == 'delete_all') {
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

		// if ($this->data['idkategorisebelumnya'])
		// 	dpr($this->data['idkategorisebelumnya'], 1);
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
				$this->data['level'],
				$this->data['penilaiansessionsebelumnya'],
				$this->data['idkategorisebelumnya']
			);
			// echo json_encode($this->data['arearr']);
			// die();
			if ($this->data['penilaiansessionsebelumnya']) {
				$arrsebelumnya = $this->conn->GetArray("
				SELECT pp.id_kriteria , pd.skor, pd.jenis FROM penilaian_session ps LEFT JOIN 
				penilaian_periode pp ON ps.id_penilaian_session = pp.id_penilaian_session 
				AND ps.id_penilaian_session = " . $this->conn->escape($this->data['penilaiansessionsebelumnya']) . " 
				LEFT JOIN penilaian p ON pp.id_penilaian_periode = p.id_penilaian_periode 
				LEFT JOIN penilaian_detail pd ON p.id_penilaian = pd.id_penilaian where ps.deleted_date is null and  pp.id_kriteria is not null");
			}

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
						where pp.deleted_date is null and  /*id_unit = " . $this->conn->escape($id_unit) . "
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
			if ($this->id_kategori_jenis <> 1) {
				$this->data['buttonMenu'] .= "&nbsp;&nbsp;<a target='_blank' href='" . site_url("panelbackend/penilaian_ml/go_print/2") . "' class=\"btn btn-sm btn-primary\"><i class='bi bi-printer'></i>Print</a>";
			} else {
				// dpr($this->data['penilaiansessionarr'], 1);
				$this->data['buttonMenu'] .= UI::createSelect('penilaiansessionsebelumnya', $this->data['penilaiansessionarr'], $this->data['penilaiansessionsebelumnya'], true, $class = 'form-control ', "style='width:300px;' onchange='goSubmit(\"set_filter\")'");
				$this->data['buttonMenu'] .= "&nbsp;&nbsp;<a target='_blank' href='" . site_url("panelbackend/penilaian_gcg/go_print/1") . "' class=\"btn btn-sm btn-primary\"><i class='bi bi-printer'></i>Print</a>";
			}
			$this->data['buttonMenu'] .= "&nbsp;&nbsp;<a href=\"javascript:void(0)\" onclick=\"goSubmit('downloadall')\" class=\"btn btn-sm btn-primary\"><span class=\"glyphicon glyphicon-download\"></span> Download All Files</a>";
		}

		$this->View($this->viewlist);
	}

	public function go_print($id_kategori = null)
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";
		$this->data['no_header'] = true;
		$id_kategori = 1;
		$this->_beforeDetail($id_kategori);
		$this->_filter();
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
						where pp.deleted_date is null and id_unit = " . $this->conn->escape($id_unit) . "
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
		pp.deleted_date is null and and id_penilaian_session = " . $this->conn->escape($this->data['id_penilaian_session']) . "						
		and k.id_kategori = " . $this->conn->escape($id_kategori) . "
		ORDER BY k.kode+0 ASC, k.id_kriteria asc");

		foreach ($rows as $r) {
			$kode = $r['kode'];
			$id_penilaian_periode = $r['id_penilaian_periode'];

			// $is_attr = $this->conn->GetOne("select 1 from mt_kriteria_attribute where id_kriteria = " . $this->conn->escape($r['id_kriteria']));

			$rs = $this->conn->GetArray("select * from penilaian where  deleted_date is null and id_penilaian_periode = " . $this->conn->escape($id_penilaian_periode));

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
					where  pl.deleted_date is null and  pl.id_penilaian1 = " . $this->conn->escape($id_penilaian));

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
		$this->id_penilaian_session = $this->data['id_penilaian_session'] = $id_penilaian_session;
		$this->data['rowheader1'] = $this->penilaiansession->GetByPk($id_penilaian_session);
		$this->data['tahun'] = explode("-", $this->data['rowheader1']['tgl'])[0];
		// dpr($this->data['rowheader1'], 1);
		$id_kategori = $this->data['rowheader1']['id_kategori'];
		// dpr($this->id_kategori, 1);
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
				$cek = $this->conn->GetOne("select status from penilaian where  deleted_date is null and  id_penilaian = " . $this->conn->escape($id_penilaian) . " and status = 2");

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
			where pl.deleted_date is null and pl.id_penilaian1 = " . $this->conn->escape($id_penilaian));

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
		// $this->conn->debug = 1;
		$record['status'] = 3;
		$record['tgl_f'] = date("Y-m-d");
		// $rows = $this->conn->GetArray("select pl.id_penilaian2 as id_penilaian 
		// 	from penilaian_link pl
		// 	where pl.id_penilaian1 = " . $this->conn->escape($id_penilaian));

		// $ret = true;
		// foreach ($rows as $r) {
		// 	$id_penilaian = $r['id_penilaian'];
		// 	if (!$ret)
		// 		break;

		$ret = $this->conn->goUpdate("penilaian", array('status' => (int)$record['status']), "id_penilaian = " . $this->conn->escape($id_penilaian));
		// }

		if ($ret) {
			$id_kriteria = $this->conn->GetOne("select id_kriteria from penilaian_periode a 
			where  a.deleted_date is null and  exists (select 1 from penilaian b 
			where a.id_penilaian_periode = b.id_penilaian_periode 
			and b.id_penilaian = " . $this->conn->escape($id_penilaian) . ")");

			$rows = $this->conn->GetArray("select * from dokumen_files 
			where  deleted_date is null and  id_dokumen_versi in (
			select max(id_dokumen_versi) 
				from dokumen_versi a where  a.deleted_date is null and  exists (
				select 1 from dokumen_kriteria b where a.id_dokumen = b.id_dokumen 
				b.deleted_date is null and and b.id_kriteria = " . $this->conn->escape($id_kriteria) . " 
			))");

			$ret = $this->conn->Execute("update penilaian_dokumen set deleted_date = now()  
			where id_penilaian = " . $this->conn->escape($id_penilaian));

			if ($ret) {
				foreach ($rows as $r) {
					if (!$ret)
						break;

					$ret = $this->conn->goInsert("penilaian_dokumen", ["id_penilaian" => $id_penilaian, "id_dokumen_versi" => $r['id_dokumen_versi']]);
				}
			}
		}

		// dpr($ret);

		// if ($ret)
		// 	$ret = $this->_upsertDetail($id_penilaian, 'f', $record);

		if ($ret && in_array('skor_d', array_keys($record)))
			$ret = $this->_upsertDetail($id_penilaian, 'd', $record);

		if ($ret && in_array('skor_k', array_keys($record)))
			$ret = $this->_upsertDetail($id_penilaian, 'k', $record);

		if ($ret && in_array('skor_w', array_keys($record)))
			$ret = $this->_upsertDetail($id_penilaian, 'w', $record);

		if ($ret && in_array('skor_o', array_keys($record)))
			$ret = $this->_upsertDetail($id_penilaian, 'o', $record);

		return $ret;
	}

	private function _upsertDetail($id_penilaian, $jenis = null, $record = array())
	{
		$r = [
			"id_penilaian" => $id_penilaian,
			"jenis" => $jenis,
			"skor" => $record['skor_' . $jenis] !== "" ? (float)$record['skor_' . $jenis] : "{{null}}",
			"tgl" => date("Y-m-d"),
			"simpulan" => $record['simpulan_' . $jenis],
			"saran" => $record['saran_' . $jenis],
		];

		$id_penilaian_detail = $this->conn->GetOne("select 
		id_penilaian_detail 
		from penilaian_detail 
		where deleted_date is null and  jenis = " . $this->conn->escape($jenis) . " 
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
			$id_penilaian_komentar = $this->conn->GetOne("select max(id_penilaian_komentar) from penilaian_komentar where  deleted_date is null and id_penilaian = " . $this->conn->escape($id_penilaian));
		}

		$ret = array("id_penilaian_komentar" => $id_penilaian_komentar);

		echo json_encode($ret);
		exit();
	}
}
