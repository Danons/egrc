<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Laporan_danareksa extends _adminController
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
        $this->viewprint = "panelbackend/laporankertaskerjaprint";
        $this->viewindex = "panelbackend/laporankertaskerjaindex";

        if ($this->mode == 'print_detail') {
            $this->data['page_title'] = 'Laporan Dana Reksa';
        } else {
            $this->data['page_title'] = 'Laporan Dana Reksa';
        }

        $this->load->model("Risk_risikoModel", "model");
        $this->load->model("Risk_scorecardModel", "mscorecard");

        $this->load->model("Mt_risk_efektifitasModel", "mefektif");
        $this->data['efektifarr'] = $this->mefektif->GetCombo();

        $this->load->model("Mt_risk_efektif_mModel", "mefektifm");
        $this->data['efektifmarr'] = $this->mefektifm->GetCombo();
        // unset($this->data['efektifarr']['']);

        $this->load->model("Mt_sdm_unitModel", "unit");

        $this->data['unitarr'] = $this->unit->GetCombo();
        $this->data['unitarr'][''] = "Semua Unit";

        $this->load->model("Mt_status_progressModel", "mtprogress");
        $mtprogress = $this->mtprogress;
        $this->data['pregressarr'] = $mtprogress->GetCombo();

        $this->load->model("Mt_risk_tingkatModel", "mttingkat");
        $this->data['tingkatarr'] = $this->mttingkat->GetCombo();
        $this->data['jenisarr'] = array('0' => 'Semua', '1' => 'Inheren Risk', '2' => 'Residual Saat Ini', '3' => 'Residual Setelah Evaluasi');
        // unset($this->data['tingkatarr']['']);
        krsort($this->data['tingkatarr']);

        $this->load->model("Risk_sasaranModel", "sasaranstrategis");

        $this->load->model("Mt_risk_efektifitas_pengukuranModel", "pengukuran");
        $this->data['mtpengukuranarr'] = $this->pengukuran->GetCombo();

        $this->load->model("Mt_risk_efektif_m_pengukuranModel", "pengukuranm");
        $this->data['mtpengukuranmarr'] = $this->pengukuranm->GetCombo();

        $this->load->model("Mt_risk_efektifitas_jawabanModel", "jawaban");
        $this->data['mtjawabanarr'] = $this->jawaban->GetCombo();

        $this->load->model("Mt_risk_efektif_m_jawabanModel", "jawabanm");
        $this->data['mtjawabanmarr'] = $this->jawabanm->GetCombo();
        // unset($this->data['mtjawabanarr']['']);
        $this->load->model("KpiModel", "kpi");
        $this->data['kpiarr'] = $this->kpi->GetCombo();

        $this->pk = $this->model->pk;
        $this->data['pk'] = $this->pk;
        $this->plugin_arr = array(
            'datepicker', 'treetable', 'select2'
        );
    }

    function Header()
    {
        $norowspan_mitigasi = array();
        $norowspan_kri = array();
        $norowspan_control = array();
        $return = array();


        $this->data['type_header'] = array(
            'id_pengukuran' => array(
                'list' => $this->data['mtpengukuranarr']
            ),
            'id_pengukuranm' => array(
                'list' => $this->data['mtpengukuranmarr']
            ),
            'kategori_kemungkinan' => array(
                'list' => array('' => '', '1' => 'Deskripsi Kualitatif', '2' => 'Probabilitas', '3' => 'Insiden Sebelumnya')
            ),
            'waktu_pelaksanaan' => 'date',
            'biaya_mitigasi' => 'rupiah',
            'dampak_kuantitatif_inheren' => 'rupiah',
            'dampak_kuantitatif_current' => 'rupiah',
            'dampak_kuantitatif_residual' => 'rupiah',
            'level_risiko_inheren' => 'rating',
            'level_risiko_paskakontrol' => 'rating',
            'level_risiko_residual' => 'rating',
            'level_risiko_actual' => 'rating',
            'is_monitoring_rmtik' => 'check',
            'is_monitoring_p2k3' => 'check',
            'is_monitoring_fkap' => 'check',
        );

        $return["kode_risiko"] = "Nomor Risiko";
        $return["sasaran"] = "Sasaran";
        $return["kpi"] = "KPI";
        $return["nama_aktifitas"] = "Aktivitas";
        $return["risiko"] = "Peristiwa Risiko";
        $return["taksonomi_objective"] = "Kategori Risiko";
        $return["taksonomi_area"] = "Sub Kategori Risiko";

        $norowspan_kri[] = "nama_kri";
        $norowspan_kri[] = "formula_kri";

        $return["nama_kri"] = "KRI";
        $return["formula_kri"] = "Fomula KRI";
        $return["penyebab"] = "Penyebab Risiko";
        $return["dampak_kuantitatif_inheren"] = "Dampak Risiko Kuantitatif";
        $return["dampak"] = "Penjelasan Dampak Risiko";


        $norowspan_control[] = 'nama_kontrol';
        $norowspan_control[] = 'nama_pengukuran';
        $return['nama_kontrol'] = "Kontrol Eksisting";
        $return['nama_pengukuran'] = "Efektivitas Kontrol Eksisting";

        $return["dampak_inheren_risk"] = array(
            "Dampak Inheren" =>
            array(
                "nama_dampak_inheren" => "Deskripsi",
                "inheren_dampak" => "Tingkat Dampak",
            )
        );
        $return["kemungkinan_inheren_risk"] = array(
            "Kemungkinan Inheren" =>
            array(
                "nama_kemungkinan_inheren" => "Deskripsi",
                "inheren_kemungkinan" => "Tingkat Kemungkinan",
            )
        );
        $return['level_risiko_inheren'] = "Level Risiko Inheren";

        // $return["current_risk"] = array(
        //     "Residual Saat Ini" => array(
        //         "kemungkinan_paskakontrol" => "Kemungkinan (K)",
        //         "dampak_paskakontrol" => "Dampak (D)",
        //         "level_risiko_paskakontrol" => "Nilai Risiko (KxD)",
        //         "dampak_kuantitatif_current" => "Kuantifikasi (Rp)"
        //     )
        // );

        $norowspan_mitigasi[] = 'nama_mitigasi';
        $norowspan_mitigasi[] = 'nama_mitigasi_berjalan';
        $norowspan_mitigasi[] = 'waktu_pelaksanaan';
        $norowspan_mitigasi[] = 'biaya_mitigasi';
        $norowspan_mitigasi[] = 'program_kerja';
        $norowspan_mitigasi[] = 'rencana';
        $norowspan_mitigasi[] = 'realisasi';
        $norowspan_mitigasi[] = 'devisiasi';
        $norowspan_mitigasi[] = 'satuan_mitigasi';
        $norowspan_mitigasi[] = 'penanggungjawab_mitigasi';

        $return['nama_mitigasi'] = "Rencana Penanganan Risiko";
        $return["penanggungjawab_mitigasi"] = "PIC Penanganan Risiko";
        $return["hasil_mitigasi_terhadap_sasaran"] = "Output Rencana Penanganan";

        $return["batas_waktu_pelaksanaan"] = array(
            "Batas Waktu Pelaksanaan" => array(
                "start_date" => "Mulai (Bulan/Tahun)",
                "dead_line" => "Akhir (Bulan/Tahun)",
            )
        );

        $return["waktu_pelaksanaan"] = "Month to Date";
        $return['biaya_mitigasi'] = "Biaya Penanganan Risiko";


        $return['dampak_kuantitatif_residual'] = "Dampak Kuantitatif";
        $return["dampak1"] = "Penjelasan Dampak Risiko";

        $return["residual_risk_dampak"] = array(
            "Dampak Residual" => array(
                "nama_dampak_residual" => "Deskripsi",
                "dampak_actual" => "Tingkat Dampak",
            )
        );
        $return["residual_risk_kemungkinan"] = array(
            "Kemungkinan Residual" => array(
                "nama_kemungkinan_residual" => "Deskripsi",
                "kemungkinan_actual" => "Tingkat Kemungkinan",
            )
        );
        $return['level_risiko_actual'] = "Level Risiko Residual";

        $this->data['norowspan_mitigasi'] = $norowspan_mitigasi;
        $this->data['norowspan_control'] = $norowspan_control;
        $this->data['norowspan_kri'] = $norowspan_kri;

        return $return;
    }

    function Index($page = 1)
    {
        if ($this->post['act'] == 'save_kolom') {
            if ($this->post['idtempletekolom']) {
                $this->conn->goUpdate("risk_kolom_laporan", [
                    'nama' => $this->post['namatempletekolom'],
                    'judul' => $this->post['judultempletekolom'],
                    'kolom' => json_encode($this->post['header'])
                ], "id_kolom_laporan = " . $this->conn->escape($this->post['idtempletekolom']));
            } else {
                $this->conn->goInsert("risk_kolom_laporan", [
                    'nama' => $this->post['namatempletekolom'],
                    'judul' => $this->post['judultempletekolom'],
                    'kolom' => json_encode($this->post['header'])
                ]);
            }
            redirect(current_url());
            exit();
        }

        if ($this->post['act'] == 'delete_kolom') {
            $this->conn->Execute("update risk_kolom_laporan set deleted_date = now() where id_kolom_laporan = " . $this->conn->escape($this->post['id_kolom_laporan']));
            redirect(current_url());
            exit();
        }

        $this->data['row'] = $this->post;
        if (!$this->access_role['view_all']) {
            $this->data['row']['id_unit'] = $_SESSION[SESSION_APP]['id_unit'];
        }
        $tahun = date('Y');

        if ($this->data['row']['tahun'])
            $tahun = $this->data['row']['tahun'];

        $this->data['rowscorecards'] = $this->mscorecard->GetList(null, null, 1, $tahun, false, $this->data['row']['id_unit']);
        $this->data['header'] = $this->Header();

        $rows = $this->conn->GetArray("select * from risk_kolom_laporan where deleted_date is null");
        $columnarr = array();
        foreach ($rows as $r) {
            $columnarr[$r['id_kolom_laporan']] = $r['kolom'];
            $this->data['templetelaporanarr'][$r['id_kolom_laporan']] = $r['nama'];
        }
        $this->data['row']['header'] = json_decode($columnarr[$this->post['id_kolom_laporan']], true);
        if ($this->post['id_kolom_laporan'])
            $this->data['row']['laporan'] = $this->conn->GetRow("select * from risk_kolom_laporan where deleted_date is null and id_kolom_laporan = " . $this->conn->escape($this->post['id_kolom_laporan']));;

        $this->View($this->viewindex);
    }

    public function go_print()
    {
        if (!$this->access_role['view_all']) {
            $this->get['id_unit'] = $_SESSION[SESSION_APP]['id_unit'];
        }

        if (!$this->get['header'])
            $this->get['header'] = array();

        $this->data['no_header'] = true;
        $this->data['no_title'] = false;
        //$bulanarr = ListBulan();
        $this->template = "panelbackend/main3";
        $this->layout = "panelbackend/layout3";

        if ($this->get['id_kolom_laporan']) {
            $this->data['no_title'] = true;
            $this->data['row']['laporan'] = $this->conn->GetRow("select * from risk_kolom_laporan where deleted_date is null and id_kolom_laporan = " . $this->conn->escape($this->get['id_kolom_laporan']));;
        }

        $this->data['page_title'] .= "<br/>" . $this->data['mtjeniskajianrisikoarr'][$this->get['id_kajian_risiko']];

        if ($this->get['id_scorecard'] == 1) {
            $this->conn->escape_string($this->get['id_scorecard']);
            $row_score = $this->conn->GetRow("select id_parent_scorecard, nama from risk_scorecard where deleted_date is null and id_scorecard in ('" . implode("','", $this->get['id_scorecard']) . "')");

            $id_parent_scorecard = $row_score['id_parent_scorecard'];
            $nama_scorecard = $row_score['nama'];

            if ($id_parent_scorecard) {
                $nama_parent = $this->conn->GetOne("select nama from risk_scorecard where deleted_date is null and id_scorecard = " . $this->conn->escape($id_parent_scorecard));
                if ($nama_parent)
                    $this->data['page_title'] .= " " . $nama_parent . " ";
            }

            if ($nama_scorecard)
                $this->data['page_title'] .= " " . $nama_scorecard . " ";
        }

        $this->data['page_title'] .= " " . ($this->get['tahun'] ? " Tahun " . $this->get['tahun'] : "");
        $this->data['namaunit'] = $this->data['unitarr'][$this->get['id_unit']];
        $this->data['page_title'] = strtoupper($this->data['page_title']);

        $this->data['warnarr'] = array();
        $rowswarna = $this->conn->GetArray("select k.kode k, d.kode d, t.warna from mt_risk_matrix mx 
			join mt_risk_kemungkinan k on mx.id_kemungkinan = k.id_kemungkinan
			join mt_risk_dampak d on mx.id_dampak = d.id_dampak
			join mt_risk_tingkat t on mx.id_tingkat = t.id_tingkat
			");
        foreach ($rowswarna as $r) {
            $this->data['warnarr'][$r['k'] . $r['d']] = $r['warna'];
        }

        $header = $this->Header();

        $this->data['header1']  = array();
        $this->data['header2']  = array();

        foreach ($header as $idkey => $value) {
            if (is_array($value)) {
                $label = key($value);
                $header1 = $value[$label];


                $colspan = 0;
                $kk = 0;
                foreach ($header1 as $k => $v) {

                    if (!$this->get['header'][$k])
                        continue;

                    $this->data['header2'][$k]["label"] = $v;
                    $this->data['header2'][$k]["rowspan"] = 1;
                    $this->data['header2'][$k]["colspan"] = 1;
                    $kk = $k;
                    $colspan++;
                }

                if ($colspan == 1) {
                    $this->data['header1'][$idkey]["label"] = $this->data['header2'][$kk]["label"];
                    $this->data['header1'][$idkey]["rowspan"] = 2;
                    $this->data['header1'][$idkey]["colspan"] = 1;
                    unset($this->data['header2'][$kk]);
                } else  if ($colspan) {
                    $this->data['header1'][$idkey]["label"] = $label;
                    $this->data['header1'][$idkey]["rowspan"] = 1;
                    $this->data['header1'][$idkey]["colspan"] = $colspan;
                }

                unset($this->get['header'][$idkey]);
            } else {
                if (!$this->get['header'][$idkey])
                    continue;

                $this->data['header1'][$idkey]["label"] = $value;
                $this->data['header1'][$idkey]["rowspan"] = 2;
                $this->data['header1'][$idkey]["colspan"] = 1;
            }
        }

        $this->data['paramheader'] = array_keys($this->get['header']);
        /*	dpr($this->data['header1']);	
		dpr($this->data['header2']);	
		dpr($this->data['paramheader']);*/
        $param = $this->get;
        unset($param['header']);
        $param['all'] = (!(bool)$param['id_scorecard']);

        $this->data['rows'] = $this->model->getListKertasKerja($param);

        $this->data['mtriskkemungkinan'] = $this->conn->GetArray("select * from mt_risk_kemungkinan where deleted_date is null order by id_kemungkinan desc");
        $this->data['mtriskdampak'] = $this->conn->GetArray("select * from mt_risk_dampak where deleted_date is null order by id_dampak asc");
        $this->data['mtriskmatrix'] = $this->conn->GetArray("select mrm.*, mrt.nama, mrt.warna
			from mt_risk_matrix mrm
			join mt_risk_tingkat mrt on mrt.id_tingkat = mrm.id_tingkaT");

        $this->View($this->viewprint);
    }
}
