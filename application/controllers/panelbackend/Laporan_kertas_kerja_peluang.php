<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Laporan_kertas_kerja_peluang extends _adminController
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
        $this->viewprint = "panelbackend/laporankertaskerjaprintpeluang";
        $this->viewindex = "panelbackend/laporankertaskerjaindex";

        if ($this->mode == 'print_detail') {
            $this->data['page_title'] = 'Laporan Kertas Kerja';
        } else {
            $this->data['page_title'] = 'Laporan Kertas Kerja';
        }

        $this->load->model("Opp_peluangModel", "model");
        $this->load->model("Opp_scorecardModel", "mscorecard");

        $this->load->model("Mt_sdm_unitModel", "unit");

        $this->data['unitarr'] = $this->unit->GetCombo();
        $this->data['unitarr'][''] = "Semua Unit";

        $this->load->model("Mt_status_progressModel", "mtprogress");
        $mtprogress = $this->mtprogress;
        $this->data['pregressarr'] = $mtprogress->GetCombo();

        $this->load->model("Mt_opp_tingkatModel", "mttingkat");
        $this->data['tingkatarr'] = $this->mttingkat->GetCombo();

        $this->load->model("Mt_opp_kelayakanModel", "mtoppkelayakan");
        $this->data['kelayakanarr'] = $this->mtoppkelayakan->GetCombo();
        unset($this->data['kelayakanarr']['']);

        $this->data['jenisarr'] = array();
        krsort($this->data['tingkatarr']);
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
        $return = array();


        $this->data['type_header'] = array(
            'target_penyelesaian' => 'date',
            'anggaran_biaya' => 'rupiah',
            'level_peluang_inheren' => 'rating',
            'is_monitoring_fkap' => 'check',
            'is_kerangka_acuan_kerja' => 'check',
        );

        $return["kode_peluang"] = "Nomor Peluang";
        $return["sasaran"] = "Sasaran Kerja";
        $return["kpi"] = "KPI";
        $return["peluang"] = "Uraian";
        $return["inheren_opp"] = array(
            "Nilai" =>
            array(
                "inheren_kemungkinan" => "Kemungkinan (K)",
                "inheren_dampak" => "Dampak (D)",
                "level_peluang_inheren" => "Nilai Peluang (KxD)",
            )
        );
        $rtarr = array();
        foreach ($this->data['kelayakanarr'] as $k => $v) {
            $rtarr["layak_" . $k] = $v;
            $this->data['type_header']['layak_' . $k] = 'check';
        }
        $return["kelayakan"] = array("Studi Kelayakan (Feasibility Study)" => $rtarr);
        $return["is_kerangka_acuan_kerja"] = "Kerangka Acuan Kerja";
        $return["dampak"] = "Manfaat";
        $return["anggaran_biaya"] = "Anggaran Biaya";
        $return["target_penyelesaian"] = "Target Penyelesaian";
        $return["opp_owner"] = "PIC";
        $return["kategori_dampak"] = "Keterangan";

        $return["capaian_mitigasi_evaluasi"] = "Hasil Implementasi Peluang Terhadap Kinerja Perusahaan";
        $return["penyesuaian_mitigasi"] = "Rekomendasi";
        $return["status_peluang"] = "Status";

        return $return;
    }

    function Index($page = 1)
    {
        if ($this->post['act'] == 'save_kolom') {
            if ($this->post['idtempletekolom']) {
                $this->conn->goUpdate("opp_kolom_laporan", [
                    'nama' => $this->post['namatempletekolom'],
                    'judul' => $this->post['judultempletekolom'],
                    'kolom' => json_encode($this->post['header'])
                ], "id_kolom_laporan = " . $this->conn->escape($this->post['idtempletekolom']));
            } else {
                $this->conn->goInsert("opp_kolom_laporan", [
                    'nama' => $this->post['namatempletekolom'],
                    'judul' => $this->post['judultempletekolom'],
                    'kolom' => json_encode($this->post['header'])
                ]);
            }
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

        $rows = $this->conn->GetArray("select * from opp_kolom_laporan where deleted_date is null");
        $columnarr = array();
        foreach ($rows as $r) {
            $columnarr[$r['id_kolom_laporan']] = $r['kolom'];
            $this->data['templetelaporanarr'][$r['id_kolom_laporan']] = $r['nama'];
        }
        $this->data['row']['header'] = json_decode($columnarr[$this->post['id_kolom_laporan']], true);
        if ($this->post['id_kolom_laporan'])
            $this->data['row']['laporan'] = $this->conn->GetRow("select * from opp_kolom_laporan where deleted_date is not null and id_kolom_laporan = " . $this->conn->escape($this->post['id_kolom_laporan']));;


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
            $this->data['row']['laporan'] = $this->conn->GetRow("select * from opp_kolom_laporan where deleted_date is null and id_kolom_laporan = " . $this->conn->escape($this->get['id_kolom_laporan']));;
        }

        $this->data['page_title'] .= "<br/>" . $this->data['mtjeniskajianpeluangarr'][$this->get['id_kajian_peluang']];

        if ($this->get['id_scorecard'] == 1) {
            $this->conn->escape_string($this->get['id_scorecard']);
            $row_score = $this->conn->GetRow("select id_parent_scorecard, nama from opp_scorecard where deleted_date is null and id_scorecard in ('" . implode("','", $this->get['id_scorecard']) . "')");

            $id_parent_scorecard = $row_score['id_parent_scorecard'];
            $nama_scorecard = $row_score['nama'];

            if ($id_parent_scorecard) {
                $nama_parent = $this->conn->GetOne("select nama from opp_scorecard where deleted_date is null and id_scorecard = " . $this->conn->escape($id_parent_scorecard));
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
        $rowswarna = $this->conn->GetArray("select k.kode k, d.kode d, t.warna from mt_opp_matrix mx 
			join mt_opp_kemungkinan k on mx.id_kemungkinan = k.id_kemungkinan
			join mt_opp_dampak d on mx.id_dampak = d.id_dampak
			join mt_opp_tingkat t on mx.id_tingkat = t.id_tingkat
			where mx.deleted_date is null");
        foreach ($rowswarna as $r) {
            $this->data['warnarr'][$r['k'] * $r['d']] = $r['warna'];
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
        $param = $this->get;
        unset($param['header']);
        $param['all'] = (!(bool)$param['id_scorecard']);

        $this->data['rows'] = $this->model->getListKertasKerja($param);

        $this->data['mtoppkemungkinan'] = $this->conn->GetArray("select * from mt_opp_kemungkinan order by id_kemungkinan desc");
        $this->data['mtoppdampak'] = $this->conn->GetArray("select * from mt_opp_dampak order by id_dampak asc");
        $this->data['mtoppmatrix'] = $this->conn->GetArray("select mrm.*, mrt.nama, mrt.warna
			from mt_opp_matrix mrm
			join mt_opp_tingkat mrt on mrt.id_tingkat = mrm.id_tingkaT");

        $this->View($this->viewprint);
    }
}
