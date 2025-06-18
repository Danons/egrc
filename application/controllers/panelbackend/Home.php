<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Home extends _adminController
{
	public $limit = 5;
	public $limit_arr = array('5', '10', '30', '50', '100');

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();

		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";
		$this->data['hide_tgl_efektif'] = true;

		$this->plugin_arr = array(
			'select2'
		);
	}

	function Index($page = null)
	{
		// $this->conn->debug = 1;
		$this->data['is_home_tr'] = true;
		$this->data['page_title'] = "Dashboard";
		#load
		$this->load->model("Risk_risikoModel", "model");
		$this->load->model("kpi_targetModel", "modelkpitarget");
		$this->load->model("Opp_peluangModel", "modelpeluang");
		$this->load->model("Risk_scorecardModel", "modelscorecard");
		$this->load->model("Opp_scorecardModel", "modelscorecardpeluang");
		$this->load->model("Mt_risk_efektifitas_pengukuranModel", "pengukuran");
		$this->data['pengukuranrow'] = $this->pengukuran->GArray();

		if ($this->post['act'] == 'only_one')
			$this->data['id_risiko_onlyone'] = $this->post['idkey'];

		#deklarasi
		$bulan = null;
		// $id_taksonomi_objective = null;
		$id_kpi = null;

		$tgl_efektif = date('Y-m-d');

		$top = $this->config->item('risk_top_risiko');
		if (!$top)
			$top = 10;

		$order = $this->config->item('risk_order_risiko');
		if (!$order)
			$order = 'c';

		/**
		 * 
		if ($_SESSION[SESSION_APP]['tgl_efektif']) {
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		}
		 */


		list($tahun, $bln, $tgl) = explode("-", $tgl_efektif);
		$id_periode_tw = $id_periode_tw_current = $this->conn->GetOne("select id_periode_tw from mt_periode_tw where deleted_date is null and  '$bln' between bulan_mulai and bulan_akhir");

		$bulan = $bln;


		#filter
		if ($this->post['act'] == "set_filter") {
			// dpr('test', 1);
			if ($tahun >= $this->post['tahun_filter'] && $this->post['tahun_filter'])
				$_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'] = $this->post['tahun_filter'];

			if (!($tahun == $_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'] && $id_periode_tw_current < $this->post['id_periode_tw_filter']))
				$_SESSION[SESSION_APP][$this->page_ctrl]['id_periode_tw_filter'] = $this->post['id_periode_tw_filter'];

			$_SESSION[SESSION_APP][$this->page_ctrl]['id_kpi_filter'] = $this->post['id_kpi_filter'];
			$_SESSION[SESSION_APP][$this->page_ctrl]['top_filter'] = $this->post['top_filter'];
			$_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard_filter'] = $this->post['id_scorecard_filter'];
			$_SESSION[SESSION_APP][$this->page_ctrl]['id_unit_filter'] = $this->post['id_unit_filter'];

			redirect(current_url());
		}


		if ($_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'])
			$tahun = $_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'];

		$_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'] = $tahun;


		if ($_SESSION[SESSION_APP][$this->page_ctrl]['id_periode_tw_filter'])
			$id_periode_tw = $_SESSION[SESSION_APP][$this->page_ctrl]['id_periode_tw_filter'];

		$_SESSION[SESSION_APP][$this->page_ctrl]['id_periode_tw_filter'] = $id_periode_tw;

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['id_kpi_filter'])
			$id_kpi = $_SESSION[SESSION_APP][$this->page_ctrl]['id_kpi_filter'];

		$_SESSION[SESSION_APP][$this->page_ctrl]['id_kpi_filter'] = $id_kpi;


		if ($_SESSION[SESSION_APP][$this->page_ctrl]['top_filter'])
			$top = $_SESSION[SESSION_APP][$this->page_ctrl]['top_filter'];

		$_SESSION[SESSION_APP][$this->page_ctrl]['top_filter'] = $top;

		$id_scorecard = $_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard_filter'];


		if (!$this->Access("view_all", "main"))
			$id_unit = $_SESSION[SESSION_APP]['id_unit'];
		else
			$id_unit = $_SESSION[SESSION_APP][$this->page_ctrl]['id_unit_filter'];

		$_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard_filter'] = $id_scorecard;
		$_SESSION[SESSION_APP][$this->page_ctrl]['id_unit_filter'] = $id_unit;

		$this->data['tahun_filter'] = $tahun;
		$this->data['id_periode_tw_filter'] = $id_periode_tw;
		$this->data['id_kpi_filter'] = $id_kpi;
		$this->data['top_filter'] = $top;
		$this->data['id_scorecard_filter'] = $id_scorecard;
		$this->data['id_unit_filter'] = $id_unit;

		// $this->data['page_title'] .= " Tahun " . UI::createTextNumber("tahun_filter", $tahun, 4, 4, true, "form-control", "style='max-width: 87px;display:inline;line-height: 1;font-size: inherit;font-weight: inherit;font-family: inherit;padding: .1rem .375rem !important;' onchange='goSubmit(\"set_filter\")'");

		if ($id_periode_tw_current <> $id_periode_tw) {
			$bulan = $this->conn->GetOne("select bulan_akhir from mt_periode_tw where deleted_date is null and  id_periode_tw = " . $this->conn->escape($id_periode_tw));
		}

		#combo
		// $this->data['taksonomiarr'] = $this->conn->GetList("select id_taksonomi_objective as idkey, nama as val from mt_risk_taksonomi_objective order by idkey");

		$this->load->model("KpiModel", "kpi");
		$this->data['kpiarr'] = $this->kpi->GetCombo();
		$this->load->model("Mt_periode_twModel", "mtperiodetw");
		$this->load->model("Mt_sdm_unitModel", "mtsdmunit");
		$this->data['mtperiodetwarr'] = $this->mtperiodetw->GetCombo();
		$this->data['unitarr'] = $this->mtsdmunit->GetCombo();

		#list
		$param = array(
			"id_kpi" => $id_kpi,
			"rating" => "ca",
			"top" => $top,
			"all" => 1,
			"id_unit" => $id_unit,
			"tahun" => $tahun,
			"bulan" => $bulan,
			"order" => $order
		);

		foreach (str_split($param['rating']) as $idkey => $value) {
			$this->data['rating'][$value] = 1;
		}

		// $this->conn->debug = 1;
		$where = array();
		$this->data['rows'] = $this->model->getListRiskProfile($param, $where);

		$this->data['total'] = $this->model->getCountAll($param, $where);


		$order = 'i';

		#list
		$param = array(
			"id_kpi" => $id_kpi,
			"rating" => "i",
			"top" => $top,
			"id_unit" => $id_unit,
			"all" => 1,
			"tahun" => $tahun,
			"bulan" => $bulan,
			"order" => $order
		);

		foreach (str_split($param['rating']) as $idkey => $value) {
			$this->data['ratingpeluang'][$value] = 1;
		}

		//$where = array();
		$this->data['rowspeluang'] = $this->modelpeluang->getListOppProfile($param, $where);

		$this->data['totalpeluang'] = $this->modelpeluang->getCountAll($param, $where);
		// dpr($this->data['rowspeluang']);
		// dpr($this->data['totalpeluang'], 1);

		// $where = array('tahun' => $tahun, 'filter' => '1=1');
		// $this->data['allkpi'] = $this->modelkpitarget->SelectGridKorporat($where);

		// $allkpi = $this->data['allkpi'];

		// $i = 0;
		// $tot_real_kor = 0;
		// $pembagian_kpi = array();
		// $kor_kpi = array();
		// $$tot_bobot_parent = array();
		// foreach ($allkpi as $key => $value) {
		// 	if ($value['id_parent'])
		// 		$tot_bobot_parent[$value['id_parent']]['bobot'] += $value['bobot'];
		// 	if ($value['realbobot'] > 0)
		// 		$tot_real_kor += $value['realbobot'];
		// }

		// foreach ($allkpi as $key => $value) {
		// 	if ($value['isfolder']) {
		// 		$kor_kpi['label'][] = $value['nama'];
		// 		$kor_kpi['id'][] = $value['id_kpi'];
		// 		$kor_kpi['jumlah'][] = $tot_bobot_parent[$value['id_kpi']]['bobot'];
		// 	}
		// }

		// $this->data['pros_kor'] = $tot_real_kor;
		// $this->data['kor_kpi'] = $kor_kpi;

		// $this->data['allkpi'] = $this->modelkpitarget->SelectGridDirektorat($param);

		// $allkpi = $this->data['allkpi'];
		// $i = 0;

		// foreach ($allkpi as $key => $value) {

		// 	$tot_real[$value['direktorat']]['nama'] = $value['direktorat'];
		// 	if (!$value['is_bersama'] && $value['realbobot'] > 0)
		// 		$tot_real[$value['direktorat']]['pros'] += $value['realbobot'];
		// 	else if ($value['realbobot'] > 0)
		// 		$tot_real['bersama']['pros'] += $value['realbobot'];
		// 	if (!$value['id_parent'])
		// 		$kpi_kor[] = $value['id_kpi'];
		// }

		// $this->data['tot_real'] = $tot_real;

		// $kpiunit = $this->modelkpitarget->SelectGrid($where);

		// $rawsql = "select id_unit,round(avg(coalesce(id_status_penilaian,0)/3*100)) kepatuhan 
		// from comp_penilaian
		// where tahun='" . $tahun . "'
		// group by id_unit
		// order by id_unit";

		// $data_kepatuhan = $this->conn->getRows($rawsql);

		// $this->data['totalkepatuhan'] = array();
		$unitarr = $this->data['unitarr'];

		unset($unitarr['']);
		// $kepatuhanarr = array();
		// foreach ($data_kepatuhan as $r) {
		// 	$kepatuhanarr[$r['id_unit']] = $r;
		// }

		// foreach ($unitarr as $k => $v) {
		// 	$this->data['totalkepatuhan']['label'][] = substr($v, 0, 15) . (strlen($v) > 15 ? '...' : '');
		// 	$this->data['totalkepatuhan']['jumlah'][] = $kepatuhanarr[$k]['kepatuhan'];
		// }

		$tahunperiode = $tahun . $id_periode_tw;

		$addfilter = "";
		if ($id_unit) {
			$addfilter = " and a.id_unit = " . $this->conn->escape($id_unit);
		}

		$rows = $this->conn->GetArray("SELECT 
		a.jenis, a.id_unit, 
		count(b.id_pemeriksaan_temuan) as jumlah_temuan,
    sum(case when c.status=0 then 1 else 0 end) as jumlah_tindak_lanjut
FROM
    pemeriksaan a
        LEFT JOIN
    pemeriksaan_temuan b ON a.id_pemeriksaan = b.id_pemeriksaan
        LEFT JOIN
    pemeriksaan_tindak_lanjut c ON b.id_pemeriksaan_temuan = c.id_pemeriksaan_temuan and concat(c.tahun,c.id_periode_tw) = " . $this->conn->escape($tahunperiode) . "
        LEFT JOIN
    mt_sdm_subbid d ON a.id_subbid = d.code

		where a.deleted_date is null and  (
			(concat(b.tahun,b.id_periode_tw) = " . $this->conn->escape($tahunperiode) . ")
			or
			(concat(c.tahun,c.id_periode_tw) <= " . $this->conn->escape($tahunperiode) . ")
    		or 
			(concat(b.tahun,b.id_periode_tw) < " . $this->conn->escape($tahunperiode) . " and b.status <> 'Close')
		) 
		
		$addfilter 
		group by a.id_unit,a.jenis");

		$totalaudit = array();
		foreach ($rows as $r) {
			$totalaudit[$r['jenis']][$r['id_unit']] = $r;
		}
		$this->data['jenisauditarr'] = array(
			'operasional' => 'Operasional',
			'mutu' => 'Mutu',
			'penyuapan' => 'Penyuapan',
			'khusus' => 'Khusus',
			'eksternal' => 'Eksternal'
		);
		$this->data['totalaudit'] = array();
		foreach ($unitarr as $k => $v) {
			foreach ($this->data['jenisauditarr'] as $jenis => $labeljenis) {
				$this->data['totalaudit'][$jenis]['label'][] = substr($v, 0, 15) . (strlen($v) > 15 ? '...' : '');
				$total = $totalaudit[$jenis][$k]['jumlah_temuan'];
				$jumlahtemuan = $totalaudit[$jenis][$k]['jumlah_tindak_lanjut'];;
				$jumlahtindaklanjut = $total - $jumlahtemuan;
				$this->data['totalaudit'][$jenis]['jumlahtemuan'][] = $jumlahtemuan;
				$this->data['totalaudit'][$jenis]['jumlahtindaklanjut'][] = $jumlahtindaklanjut;
			}
		}

		// $addfilter = "";

		// if ($id_unit)
		// 	$addfilter = " and exists (select 1 from dokumen_jabatan where dokumen.id_dokumen = dokumen_jabatan.id_dokumen and dokumen_jabatan.id_jabatan = " . $this->conn->escape($id_unit) . ")";

		// $this->data['dokumenbaru'] = $this->conn->GetArray("select * 
		// from dokumen where is_aktif = 1 
		// and tgl_disahkan is not null 
		// $addfilter
		// order by tgl_disahkan desc 
		// limit 6");


		$this->load->model("Penilaian_periodeModel", "modelpenilaianperiode");
		// $this->conn->debug = 1;
		$tgl = $tahun . "-01-01";
		$this->data['penilaian_session_arr'] = $this->conn->GetList("select 
		id_penilaian_session as idkey, nama as val
		from penilaian_session 
		where  deleted_date is null and id_kategori = 1 and date_format(tgl,'%Y') = " . $this->conn->escape($tahun) . "  and jenis_assessment_gcg != 1");

		if ($this->post['act'] == 'set_id_penilaian_session' && $this->post['id_penilaian_session'] != '') {
			$id_penilaian_session = $this->post['id_penilaian_session'];
			$this->data['id_penilaian_session'] = $id_penilaian_session;
		} else {
			$id_penilaian_session = $this->conn->GetOne("select 
			max(id_penilaian_session) 
			from penilaian_session 
			where deleted_date is null and date_format(tgl,'%Y') = " . $this->conn->escape($tahun) . " 
			and id_kategori=1 and jenis_assessment_gcg != 1");
		}

		// $this->conn->debug = 1;
		// $this->data['rekapaspek'] = [];
		$this->data['rekapaspek'] = $this->modelpenilaianperiode->rekapAspek(null, $tahun . '-01-01', $id_penilaian_session);
		// $this->data['rekapaspek'] = $this->modelpenilaianperiode->rekapAspek($id_unit, $tahun . '-01-01', $id_penilaian_session);
		// dpr($this->data['rekapaspek'], 1);
		$this->data['nama_gcg'] = $this->conn->GetOne("select nama from penilaian_session where deleted_date is null and  id_penilaian_session = " . $this->conn->escape($id_penilaian_session));

		// $this->conn->debug = 1;
		$id_penilaian_session = $this->conn->GetOne("select 
		max(id_penilaian_session) 
		from penilaian_session 
		where deleted_date is null and  date_format(tgl,'%Y') = " . $this->conn->escape($tahun) . " 
		and id_kategori=2");
		//$tgl_penilaian, $id_penilaian_session, $id_kategori_jenis
		$this->data['rowsmls'] = $rowsmls = $this->modelpenilaianperiode->getKesimpulan($tgl, $id_penilaian_session, 2);
		$rowsml = $this->modelpenilaianperiode->getKesimpulanLevel($tgl, $id_penilaian_session, 2, $rowsmls);
		$this->data['nama_ml'] = $this->conn->GetOne("select nama from penilaian_session where deleted_date is null and  id_penilaian_session = " . $this->conn->escape($id_penilaian_session));

		$this->data['labelml'] = [];
		$this->data['nilaiml'] = [];
		foreach ($rowsml as  $rl) {
			$this->data['labelml'][] = str_replace(" ", "\n", $rl['nama']);
			$this->data['nilaiml'][] = $rl['level'];
		}

		$id_penilaian_session = $this->conn->GetOne("select 
		max(id_penilaian_session) 
		from penilaian_session 
		where deleted_date is null and  date_format(tgl,'%Y') = " . $this->conn->escape($tahun) . " 
		and id_kategori=3");
		$this->data['rowscls'] = $rowscls = $this->modelpenilaianperiode->getKesimpulan($tgl, $id_penilaian_session, 3);
		$rowscl = $this->modelpenilaianperiode->getKesimpulanLevel($tgl, $id_penilaian_session, 3, $rowscls);
		$this->data['nama_cl'] = $this->conn->GetOne("select nama from penilaian_session where deleted_date is null and  id_penilaian_session = " . $this->conn->escape($id_penilaian_session));

		$this->data['labelcl'] = [];
		$this->data['nilaicl'] = [];
		foreach ($rowscl as  $rl) {
			$this->data['labelcl'][] = str_replace(" ", "\n", $rl['nama']);
			$this->data['nilaicl'][] = $rl['level'];
		}
		// dpr($rowscl, 1);

		// $this->load->model("RtmModel", "rtm");
		// $this->data['rtmarr'] = $this->rtm->GetCombo();
		// unset($this->data['rtmarr']['']);
		// $this->data['rtm_permasalahan'] = [];
		// $this->data['rtm_tindaklanjut'] = [];
		// foreach ($this->data['rtmarr'] as $k => &$v) {
		// 	$v = "RTM KE " . $v;
		// 	$this->data['rtm_permasalahan'][] = $this->conn->GetOne("select count(1) 
		// 	from rtm_uraian_link a 
		// 	where exists (select 1 from rtm_uraian b where a.id_rtm_uraian = b.id_rtm_uraian and b.is_risalah = 1) 
		// 	and status = 0 and id_rtm = " . $this->conn->escape($k));
		// 	$this->data['rtm_tindaklanjut'][] = $this->conn->GetOne("select count(1) 
		// 	from rtm_uraian_link a 
		// 	where exists (select 1 from rtm_uraian b where a.id_rtm_uraian = b.id_rtm_uraian and b.is_risalah = 1) 
		// 	and status = 1 and id_rtm = " . $this->conn->escape($k));
		// }

		// $this->data['totalrtm'] = [];
		// foreach ($unitarr as $k => $v) {
		// 	$this->data['totalrtm']['labels'][] = substr($v, 0, 15) . (strlen($v) > 15 ? '...' : '');
		// 	$this->data['totalrtm']['jumlahtemuan'][] = $this->conn->GetOne("select count(1) 
		// 	from rtm_uraian b 
		// 	left join rtm_uraian_link a on a.id_rtm_uraian = b.id_rtm_uraian and exists (select 1 from rtm c where a.id_rtm = c.id_rtm 
		// 	and c.tahun = " . $this->conn->escape($tahun) . " 
		// 	and c.rkt = " . $this->conn->escape($id_periode_tw) . ") 
		// 	and exists (select 1 from rtm_urian_unit d
		// 	where d.id_rtm_uraian = a.id_rtm_uraian
		// 	and d.id_unit = " . $this->conn->escape($v) . ")
		// 	where b.is_risalah = 1
		// 	and (a.status = 0 or a.status is null)
		// 	");

		// 	$this->data['totalrtm']['jumlahtindaklanjut'][] = $this->conn->GetOne("select count(1) 
		// 	from rtm_uraian_link a 
		// 	where exists (select 1 from rtm_uraian b where a.id_rtm_uraian = b.id_rtm_uraian and b.is_risalah = 1) 
		// 	and status = 1 
		// 	and exists (select 1 from rtm c where a.id_rtm = c.id_rtm 
		// 	and c.tahun = " . $this->conn->escape($tahun) . " 
		// 	and c.rkt = " . $this->conn->escape($id_periode_tw) . ") 
		// 	and exists (select 1 from rtm_urian_unit d
		// 	where d.id_rtm_uraian = a.id_rtm_uraian
		// 	and d.id_unit = " . $this->conn->escape($v) . ")");
		// }

		// $this->data['rowcompliance'] = $this->conn->GetArray("select su.table_desc, 
		// count( distinct concat(du.id_dokumen, cp.id_comp_kebutuhan)) as jum_di_isi, 
		// count( distinct du.id_dokumen) as jum_dok, 
		// count(distinct concat(du.id_dokumen, cp.id_comp_kebutuhan,cp.id_status_penilaian)) as jum_di_nilai 
		// from dokumen_jabatan du
		// join comp_kebutuhan ck on du.id_dokumen = ck.id_dokumen
		// left join (
		// 	select min(id_status_penilaian) id_status_penilaian, tahun, id_unit, id_comp_kebutuhan 
		// 	from comp_penilaian where tahun = " . $this->conn->escape($tahun) . "
		// 	group by tahun, id_unit, id_comp_kebutuhan
		// ) cp on du.id_unit = cp.id_unit and ck.id_comp_kebutuhan = cp.id_comp_kebutuhan 
		// join mt_sdm_unit su on su.table_code = du.id_unit
		// group by su.table_desc");


		$this->data['quisioner'] = $this->conn->GetArray("
		select pq.*,ps.id_kategori, coalesce(pq.nilai,pq.jawaban) as nilai_jawaban, msj.nama AS nama_jabatan, 
		psu.name as nama_user from penilaian_quisioner pq left join penilaian_session as ps on pq.id_penilaian_session = ps.id_penilaian_session   left JOIN mt_sdm_jabatan msj ON pq.id_jabatan = msj.id_jabatan 
		LEFT JOIN public_sys_user psu ON pq.id_user = psu.user_id where pq.deleted_date is null ");

		$this->data['quisioner_survey_kegiatan'] = $this->conn->GetArray("SELECT * from quisioner q LEFT JOIN penilaian_quisioner pq ON q.id_quisioner = pq.id_quisioner 
		WHERE q.deleted_date is null and  q.id_kategori = 4");

		$this->data['quisioner_survey_tahunan'] = $this->conn->GetArray("SELECT * from quisioner q LEFT JOIN penilaian_quisioner pq ON q.id_quisioner = pq.id_quisioner 
		WHERE q.deleted_date is null and  q.id_kategori = 5");

		$quisioner = array();
		if ($this->data['quisioner']) {
			foreach ($this->data['quisioner'] as $a) {
				$quisioner[$a['id_kategori']][$a['id_penilaian_quisioner']] = $a;
			}
		}

		foreach ($quisioner as $key => $val) {

			foreach ($val as $pertanyaan) {
				if ($key == $pertanyaan['id_kategori']) {
					if (is_numeric($pertanyaan['nilai_jawaban'])) {
						$this->data['pertanyaan'][$key]['total_pertanyaan'] += 1;
						$this->data['pertanyaan'][$key]['total_nilai'] += $pertanyaan['nilai_jawaban'];
					}
				}
			}
		}

		$quisioner_survey_kegiatan = array();
		if ($this->data['quisioner_survey_kegiatan']) {
			foreach ($this->data['quisioner_survey_kegiatan'] as $a) {
				$quisioner_survey_kegiatan[$a['id_kategori']][$a['id_penilaian_quisioner']] = $a;
			}
		}

		$quisioner_survey_tahunan = array();
		if ($this->data['quisioner_survey_tahunan']) {
			foreach ($this->data['quisioner_survey_tahunan'] as $a) {
				$quisioner_survey_tahunan[$a['id_kategori']][$a['id_penilaian_quisioner']] = $a;
			}
		}

		foreach ($quisioner_survey_kegiatan as $key_survey => $val_survey) {

			foreach ($val_survey as $pertanyaan) {
				if ($key_survey == $pertanyaan['id_kategori']) {
					if (is_numeric($pertanyaan['nilai']) && $pertanyaan['jenis_jawaban'] == "1sampai5") {
						$this->data['pertanyaan_survey_kegiatan'][$key_survey]['total_pertanyaan'] += 1;
						$this->data['pertanyaan_survey_kegiatan'][$key_survey]['total_nilai'] += $pertanyaan['nilai'];
					}
				}
			}
		}

		foreach ($quisioner_survey_tahunan as $key_survey => $val_survey) {

			foreach ($val_survey as $pertanyaan) {
				if ($key_survey == $pertanyaan['id_kategori']) {
					if (is_numeric($pertanyaan['nilai']) && $pertanyaan['jenis_jawaban'] == "1sampai5") {
						$this->data['pertanyaan_survey_tahunan'][$key_survey]['total_pertanyaan'] += 1;
						$this->data['pertanyaan_survey_tahunan'][$key_survey]['total_nilai'] += $pertanyaan['nilai'];
					}
				}
			}
		}
		$this->data['page_title'] .= " " . UI::createTextNumber("tahun_filter", $this->data['tahun_filter'], 4, 4, true, "filter-title form-control", "style='width:70px; display:inline' onchange='goSubmit(\"set_filter\")'");
		$this->View("panelbackend/home");
	}

	function msg($id_msg = null)
	{
		$this->conn->Execute("update risk_msg_penerima set is_read = '1' 
			where id_msg = " . $this->conn->escape($id_msg) . " 
			and id_user = " . $this->conn->escape($_SESSION[SESSION_APP]['user_id']));

		redirect("panelbackend/home");
	}

	function task($id_task = null)
	{
		$row = $this->conn->GetRow("select * from risk_task where deleted_date is null and id_task = " . $this->conn->escape($id_task));

		$url = $row['url'];

		// if (strstr($url, 'risk_risiko') !== false)
		$status = 1;

		// if (strstr($url, 'risk_control') !== false)
		// 	$status = 2;

		// if (strstr($url, 'risk_mitigasi') !== false)
		// 	$status = 3;

		// if (strstr($url, 'risk_evaluasi') !== false)
		// 	$status = 4;

		$this->conn->Execute("update risk_task set status = '$status' where id_task = " . $this->conn->escape($id_task));

		redirect($url);
	}

	function Loginasback()
	{
		if (!$_SESSION[SESSION_APP]['loginas'])
			redirect('panelbackend');

		$loginas = $_SESSION[SESSION_APP]['loginas'];
		unset($_SESSION[SESSION_APP]);
		$_SESSION[SESSION_APP] = $loginas;

		redirect('panelbackend');
	}

	function Profile()
	{
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";
		$this->access_role['save'] = 1;
		$this->access_role['batal'] = 1;
		$this->data['page_title'] = 'Profile';

		$this->load->model("Public_sys_userModel", "model");
		$this->load->model("Mt_sdm_jabatanModel", "mjabatan");
		$this->load->library('form_validation');

		$id = $_SESSION[SESSION_APP]['user_id'];
		$this->data['edited'] = true;

		$this->data['row'] = $this->model->GetByPk($id);
		$this->data['row']['id_jabatan'] = $_SESSION[SESSION_APP]['id_jabatan'];
		if (!$this->data['row'] && $id)
			$this->NoData();

		$this->data['jabatanarr'] = $this->mjabatan->GetCombo();
		// dpr($_SESSION[SESSION_APP], 1);
		// $this->mjabatan->GetComboLost($this->data['row']['id_jabatan'], $this->data['jabatanarr']);

		$this->data['rules'] = array(
			'nama' => array(
				'field'   => 'name',
				'label'   => 'Nama',
				'rules'   => 'required'
			),
			'email' => array(
				'field'   => 'email',
				'label'   => 'Email',
				'rules'   => 'valid_email|required'
			),
			'confirmpassword' => array(
				'field'   => 'confirmpassword',
				'label'   => 'Password',
				'rules'   => 'callback_checkconfirm'
			),
			'oldpassword' => array(
				'field'	=> 'oldpassword',
				'label' => 'Password lama',
				'rules' => 'callback_checkoldpassword'
			)
		);

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {

			$valid = $this->_isValidProfile();
			if (!$valid) {
				$this->View('panelbackend/profile');
				return;
			}

			$record = array();
			$record['name'] = $this->post['name'];
			$record['email'] = $this->post['email'];
			$record['is_notification'] = (int)$this->post['is_notification'];

			if (!empty($this->post['password'])) {
				$record['password'] = sha1(md5($this->post['password']));
			}

			$this->_setLogRecord($record, $id);

			if ($id) {
				$return = $this->model->Update($record, "user_id = $id");
				if ($return) {
					SetFlash('suc_msg', $return['success']);
					redirect("panelbackend/home/profile");
				} else {
					$this->data['row'] = $record;
					$this->data['err_msg'] = "Data gagal diubah";
				}
			}
		}

		$this->View('panelbackend/profile');
	}

	public function checkoldpassword($str)
	{
		if (!$this->post['password'])
			return true;

		if ($this->data['row']['password'] <> sha1(md5($str))) {
			$this->form_validation->set_message('checkoldpassword', 'Password lama salah');
			return FALSE;
		}

		return true;
	}

	public function checkconfirm($str)
	{
		if (!$this->post['password'])
			return true;

		if ($str <> $this->post['password']) {
			$this->form_validation->set_message('checkconfirm', 'Konfirmasi password salah');
			return FALSE;
		}

		return true;
	}

	function _isValidProfile()
	{

		$rules = array_values($this->data['rules']);

		$this->form_validation->set_rules($rules);

		$error_msg = '';
		if ($this->form_validation->run() == FALSE) {
			$error_msg .= validation_errors();
		}

		if ($error_msg) {
			if (!$this->data['row'])
				$this->data['row'] = array();

			$this->data['row'] = array_merge($this->data['row'], $this->post);
			return false;
		}

		return true;
	}

	function wf()
	{
		$full_path = FCPATH . "assets/doc/WF.pdf";

		header("Content-Type: application/pdf");
		header("Content-Disposition: inline; filename='wf.pdf'");
		echo file_get_contents($full_path);
		die();
	}

	function ug()
	{
		// if ($_SESSION[SESSION_APP]['group_id'] == 1)
		// 	$full_path = FCPATH . "assets/doc/UGA.pdf";
		// elseif ($_SESSION[SESSION_APP]['group_id'] == 2)
		// 	$full_path = FCPATH . "assets/doc/UGU.pdf";
		// elseif ($_SESSION[SESSION_APP]['group_id'] == 3)
		// 	$full_path = FCPATH . "assets/doc/UGC.pdf";
		// elseif ($_SESSION[SESSION_APP]['group_id'] == 24)
		// 	$full_path = FCPATH . "assets/doc/UGO.pdf";
		// elseif ($_SESSION[SESSION_APP]['group_id'] == 25)
		// 	$full_path = FCPATH . "assets/doc/UGVK.pdf";
		// elseif ($_SESSION[SESSION_APP]['group_id'] == 26)
		// 	$full_path = FCPATH . "assets/doc/UGIO.pdf";
		// elseif ($_SESSION[SESSION_APP]['group_id'] == 27)
		// 	$full_path = FCPATH . "assets/doc/UGVU.pdf";
		// elseif ($_SESSION[SESSION_APP]['group_id'] == 28)
		// 	$full_path = FCPATH . "assets/doc/UGAS.pdf";
		// elseif ($_SESSION[SESSION_APP]['group_id'] == 46)
		// 	$full_path = FCPATH . "assets/doc/UGCC.pdf";
		// else
		$full_path = FCPATH . "assets/doc/UG.pdf";

		header("Content-Type: application/pdf");
		header("Content-Disposition: inline; filename=ug.pdf");
		echo file_get_contents($full_path);
		die();
	}

	public function Cari($page = 0)
	{
		$this->limit = 50;
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";
		$this->data['page_title'] = 'Pencarian Risiko "' . $_SESSION[SESSION_APP]['cari_risiko'] . '"';

		$this->load->model("Risk_risikoModel", "model");

		$this->data['header'] = $this->Header();

		$this->_setFilter("lower(nama) like '%" . strtolower($_SESSION[SESSION_APP]['cari_risiko']) . "%'");

		$this->data['list'] = $this->_getList($page);

		$this->data['page'] = $page;

		$param_paging = array(
			'base_url' => base_url("{$this->page_ctrl}/cari"),
			'cur_page' => $page,
			'total_rows' => $this->data['list']['total'],
			'per_page' => $this->limit,
			'first_tag_open' => '<li class="page-item">',
			'first_tag_close' => '</li>',
			'last_tag_open' => '<li class="page-item">',
			'last_tag_close' => '</li>',
			'cur_tag_open' => '<li class="page-item active"><a href="#" class="page-link">',
			'cur_tag_close' => '</a></li>',
			'next_tag_open' => '<li class="page-item">',
			'next_tag_close' => '</li>',
			'prev_tag_open' => '<li class="page-item">',
			'prev_tag_close' => '</li>',
			'num_tag_open' => '<li class="page-item">',
			'num_tag_close' => '</li>',
			'anchor_class' => 'page-link',
			'attributes' => array('class' => 'page-link'),
		);
		$this->load->library('pagination');

		$paging = $this->pagination;

		$paging->initialize($param_paging);

		$this->data['paging'] = $paging->create_links();

		$this->data['limit'] = $this->limit;

		$this->data['limit_arr'] = $this->limit_arr;

		$this->View("panelbackend/cari_list");
	}
}
