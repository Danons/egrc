<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Risiko extends _adminController
{
    public $page_escape = array('api/risiko');

    public function __construct()
    {
    }

    public function periode()
    {
        $this->isPrivateMethod("GET");
        $this->load->model("Mt_periode_twModel", 'periodetw');
        $data = $this->periodetw->GetCombo();

        unset($data['']);
        $this->success($data);
    }

    public function kajian_risiko()
    {
        $this->isPrivateMethod("GET");
        $data = $this->data['mtjeniskajianrisikoarr'];

        unset($data['']);
        $this->success($data);
    }

    public function unit()
    {
        $this->isPrivateMethod("GET");
        $this->load->model("Mt_sdm_unitModel", "unit");

        $data = $this->unit->GetCombo();

        if (!$this->Access("view_all")) {
            $row['id_unit'] = $_SESSION[SESSION_APP]['id_unit'];
            $data = array($row['id_unit'] => $data[$row['id_unit']]);
        }

        unset($data['']);
        $this->success($data);
    }

    public function sasaran_strategis()
    {
        $this->isPrivateMethod("GET");
        $this->load->model("Risk_sasaran_strategisModel", "sasaranstrategis");

        $tahun = date("Y");
        $param = $this->requestData();
        if ($param['tahun'])
            $tahun = $param['tahun'];

        $data = $this->sasaranstrategis->GetCombo(null, null, $tahun);

        unset($data['']);
        $this->success($data);
    }

    public function taksonomi()
    {
        $this->isPrivateMethod("GET");
        $this->load->model("Mt_risk_taksonomi_objectiveModel", 'taksonomi');
        $data = $this->taksonomi->GetCombo();
        unset($data['']);
        $this->success($data);
    }

    public function scorecard()
    {
        $this->isPrivateMethod("GET");
        $this->load->model("Risk_scorecardModel", "mscorecard");

        $tahun = date("Y");

        $param = $this->requestData();
        $id_kajian_risiko = $param['id_kajian_risiko'];
        $owner = $param['owner'];

        if ($param['tahun'])
            $tahun = $param['tahun'];

        if (!$this->Access("view_all"))
            $id_unit = $_SESSION[SESSION_APP]['id_unit'];
        if ($param['id_unit'])
            $id_unit = $param['id_unit'];

        $data = $this->mscorecard->GetList($id_kajian_risiko, null, null, 1, $tahun, false, $id_unit, $owner);
        $data = $this->genereteTree("", $data);

        $this->success($data);
    }

    public function owner()
    {
        $this->isPrivateMethod("GET");
        $this->load->model("Risk_scorecardModel", "mscorecard");

        $tahun = date("Y");

        $param = $this->requestData();
        $id_kajian_risiko = $param['id_kajian_risiko'];

        if ($param['tahun'])
            $tahun = $param['tahun'];

        if (!$this->Access("view_all"))
            $id_unit = $_SESSION[SESSION_APP]['id_unit'];
        if ($param['id_unit'])
            $id_unit = $param['id_unit'];

        $data = $this->mscorecard->GetListOwner($id_kajian_risiko, null, $tahun, $id_unit);

        $this->success($data);
    }

    private function genereteTree($idparent, $data)
    {
        $rows = array();
        foreach ($data as $r) {
            if ($r['id_parent'] == $idparent) {
                $rows[$r['id_scorecard']]['id_scorecard'] = $r['id_scorecard'];
                $rows[$r['id_scorecard']]['nama'] = $r['nama'];
                $child = $this->genereteTree($r['id_scorecard'], $data);

                if ($child)
                    $rows[$r['id_scorecard']]['child'] = $child;
            }
        }
        return $rows;
    }

    public function jenis_tingkat_risiko()
    {
        $this->isPrivateMethod("GET");
        $jenis = array('0' => 'Semua', '1' => 'Risiko Melekat/Awal', '2' => 'Risiko Aktual/Risiko Residu', '3' => 'Risiko Target');
        $this->load->model("Mt_risk_tingkatModel", "mttingkat");
        $tingkatarr = $this->mttingkat->GetCombo();
        unset($tingkatarr['']);

        $this->success(["jenis" => $jenis, "tingkat" => $tingkatarr]);
    }

    public function header()
    {
        $this->isPrivateMethod("GET");
        $this->response($this->_header());
    }

    private function _header()
    {
        $param = $this->requestData();
        $this->load->model("Mt_periode_twModel", 'periodetw');
        $this->data['periodetwarr'] = $this->periodetw->GetCombo();
        $this->load->model("Mt_risk_efektifitasModel", "mefektif");
        $this->data['efektifarr'] = $this->mefektif->GetCombo();
        $this->load->model("Mt_risk_efektif_mModel", "mefektifm");
        $this->data['efektifmarr'] = $this->mefektifm->GetCombo();
        $tgl_efektif = $this->data['tgl_efektif'];

        $this->data['tgl_efektif'] = $tgl_efektif;
        list($tgl, $bln, $thn) = explode("-", $tgl_efektif);
        $this->data['periode_tw'] = $this->conn->GetRow("select * from mt_periode_tw where deleted_date is null and '$bln' between bulan_mulai and bulan_akhir");
        $this->data['id_periode_tw'] = $this->data['periode_tw']['id_periode_tw'];

        $id_kajian_risiko = $param['id_kajian_risiko'];
        $norowspan_control = array();
        $return = array();

        $row = $this->conn->GetRow("select is_fraud, is_kri 
		from mt_risk_kajian_risiko
		where deleted_date is null and id_kajian_risiko = " . $this->conn->escape($id_kajian_risiko));

        $this->data['is_fraud'] = $row['is_froud'];
        $this->data['is_kri'] = $row['is_kri'];

        if ($this->data['is_fraud']) {

            $this->load->model("Mt_pb_kategoriModel", 'kategori');
            $this->data['kategoriarr'] = $this->kategori->GetCombo();

            $this->load->model("Mt_fraud_kategoriModel", 'fraudkategori');
            $this->data['fraudkategoriarr'] = $this->fraudkategori->GetCombo();

            $this->data['fraudkriteriakemungkinan'] = $this->conn->GetArray("select * 
				from mt_fraud_kemungkinan_kriteria where deketed_date is null ");

            $rows = $this->conn->GetArray("select a.*, b.level_kemungkinan
				from mt_fraud_kemungkinan a 
				join mt_fraud_kemungkinan_level b on a.id_fraud_kemungkinan_level = b.id_fraud_kemungkinan_level and a.deleted_date is null ");
            $this->data['fraudkemungkinan'] = array();
            foreach ($rows as $r) {
                if (!$this->data['fraudkemungkinan'][$r['id_fraud_kemungkinan_kriteria']])
                    $this->data['fraudkemungkinan'][$r['id_fraud_kemungkinan_kriteria']] = array("" => "");

                $this->data['fraudkemungkinan'][$r['id_fraud_kemungkinan_kriteria']][$r['id_fraud_kemungkinan']] = $r['nama'];
            }

            $this->data['fraudkriteriadampak'] = $this->conn->GetArray("select * 
				from mt_fraud_dampak_kriteria where deleted_date is null");

            $rows = $this->conn->GetArray("select a.*, b.level_dampak
				from mt_fraud_dampak a 
				join mt_fraud_dampak_level b on a.id_fraud_dampak_level = b.id_fraud_dampak_level && a.deleted_date is null");
            $this->data['frauddampak'] = array();
            foreach ($rows as $r) {
                if (!$this->data['frauddampak'][$r['id_fraud_dampak_kriteria']])
                    $this->data['frauddampak'][$r['id_fraud_dampak_kriteria']] = array("" => "");

                $this->data['frauddampak'][$r['id_fraud_dampak_kriteria']][$r['id_fraud_dampak']] = $r['nama'];
            }
        }

        $this->data['type_header'] = array(
            'id_pengukuran' => array(
                'list' => $this->data['mtpengukuranarr']
            ),
            'id_pengukuranm' => array(
                'list' => $this->data['mtpengukuranmarr']
            ),
            // 'capaian_mitigasi' => array(
            // 	'list' => $this->data['pregressarr']
            // ),
            'kategori_kemungkinan' => array(
                'list' => array('' => '', '1' => 'Deskripsi Kualitatif', '2' => 'Probabilitas', '3' => 'Insiden Sebelumnya')
            ),
            'waktu_pelaksanaan' => 'date',
            'biaya_mitigasi' => 'rupiah',
            'level_risiko_inheren' => 'rating',
            'level_risiko_paskakontrol' => 'rating',
            'level_risiko_residual' => 'rating',
            'level_risiko_actual' => 'rating',
            'level_risiko_actual_real' => 'rating',
        );

        $return["scorecard"] = "Scorecard";
        $return["kode_risiko"] = "Kode";
        $return["sasaran_strategis"] = "Sasaran Strategis";
        $return["sasaran_kegiatan"] = "Sasaran Kegiatan";
        if ($this->data['is_fraud']) {
            $return["alur_proses_bisnis"] = "Alur Proses Bisnis";
            $return["sub_tahapan_kegiatan"] = "Sub Tahapan Kegiatan";
        }
        $return["taksonomi"] = "Taksonomi";
        /*"proses_bisnis"=>array(
				"Proses Bisnis"=>
				array(
					"kategori_proses"=>"Kategori",
					"kelompok_proses"=>"Kelompok Proses",
					"nama_proses"=>"Nama Proses",
					"aktifitas"=>"Aktivitas",
				)
			),*/
        $return["identifikasi_risiko"] = array(
            "Identifikasi Risiko" =>
            array(
                "risiko" => "Risiko",
                "penyebab" => "Penyebab",
                "dampak" => "Dampak"
            )
        );

        $return['risiko_induk'] = "Risiko Induk";

        if ($this->data['is_fraud']) {
            $return["kategori_fraud"] = "Kategori Fraud";
            $return["jenis_fraud"] = "Jenis Fraud";
        }
        $return["risk_owner"] = "Pemilik Risiko";
        if ($this->data['is_fraud']) {
            $return["pejabat_berisiko"] = "Pejabat Berisiko/Pelaku";
            $return["red_flag"] = "Red Flag";
        }
        $return["status_risiko"] = "Status Risiko";
        if ($this->data['is_fraud']) {
            $faktor = array();
            foreach ($this->data['fraudkriteriakemungkinan'] as $r) {
                $faktor["faktor_inheren_kemungkinan_" . $r['id_fraud_kemungkinan_kriteria']] = $r['nama'];
            }
            $faktor["skor_inheren_kemungkinan"] = "Skor Tingkat Kemungkinan";
            $faktor["inheren_kemungkinan"] = "Level Tingkat Kemungkinan";
            $return["inheren_faktor_kemungkinan"]["Faktor Kemungkinan Inheren"] = $faktor;

            $faktor = array();
            foreach ($this->data['fraudkriteriadampak'] as $r) {
                $faktor["faktor_inheren_dampak_" . $r['id_fraud_dampak_kriteria']] = $r['nama'];
            }
            $faktor["skor_inheren_dampak"] = "Skor Tingkat Dampak";
            $faktor["inheren_dampak"] = "Level Tingkat Dampak";
            $return["inheren_faktor_dampak"]["Faktor Dampak Inheren"] = $faktor;
            $return["level_risiko_inheren"] = "Level Risiko Inheren";
        } else {
            $return["risiko_inheren"] = array(
                "Risiko Melekat/Awal" =>
                array(
                    "inheren_kemungkinan" => "Tingkat Kemungkinan",
                    "kategori_kemungkinan" => "Kriteria Kemungkinan",
                    "inheren_dampak" => "Tingkat Dampak",
                    "kategori_dampak" => "Kriteria Dampak",
                    "level_risiko_inheren" => "Level Risiko"
                )
            );
        }

        $norowspan_kri = array();

        if ($this->data['is_kri']) {
            $return['kri'] = array("KRI" => array(
                "nama_kri" => "Nama KRI",
                "polaritas" => "Plt",
                "satuan" => "Stn",
                "batas_atas" => "Batas Atas",
                "batas_bawah" => "Batas Bawah",
                "target_mulai" => "Target Mulai",
                "target_sampai" => "Target Sampai",
                "hasil_kri" => "Hasil " . $this->data['periodetwarr'][($this->data['row']['id_periode_tw'] ? $this->data['row']['id_periode_tw'] : $this->data['id_periode_tw'])],
            ));

            $norowspan_kri[] = "nama_kri";
            $norowspan_kri[] = "polaritas";
            $norowspan_kri[] = "satuan";
            $norowspan_kri[] = "batas_atas";
            $norowspan_kri[] = "batas_bawah";
            $norowspan_kri[] = "target_mulai";
            $norowspan_kri[] = "target_sampai";
            $norowspan_kri[] = "hasil_kri";
        }

        $return["pengendalian_risiko_saat_ini"] = array(
            "Pengendalian Risiko" => array(
                "nama_kontrol" => "Aktivitas yang sudah ada untuk Pencegahan dan Pemulihan",
                "control_menurunkan" => "Menurunkan Dampak atau Kemungkinan ?"
            )
        );

        $norowspan_control[] = "nama_kontrol";
        $norowspan_control[] = "control_menurunkan";

        unset($this->data['efektifarr']['']);
        foreach ($this->data['efektifarr'] as $key => $value) {
            $return['pengendalian_risiko_saat_ini']['Pengendalian Risiko']['efektif_' . $key] = $value;

            $this->data['type_header']['efektif_' . $key] = array(
                'list' => $this->data['mtjawabanarr']
            );

            $norowspan_control[] = 'efektif_' . $key;
        }

        $return['pengendalian_risiko_saat_ini']['Pengendalian Risiko']['id_pengukuran'] = "Control Efektif";

        $norowspan_control[] = "id_pengukuran";

        if ($this->data['is_fraud']) {
            $faktor = array();
            foreach ($this->data['fraudkriteriakemungkinan'] as $r) {
                $faktor["faktor_control_kemungkinan_" . $r['id_fraud_kemungkinan_kriteria']] = $r['nama'];
            }
            $faktor["skor_control_kemungkinan"] = "Skor Tingkat Kemungkinan";
            $faktor["kemungkinan_paskakontrol"] = "Level Tingkat Kemungkinan";
            $return["control_faktor_kemungkinan"]["Faktor Kemungkinan Control"] = $faktor;

            $faktor = array();
            foreach ($this->data['fraudkriteriadampak'] as $r) {
                $faktor["faktor_control_dampak_" . $r['id_fraud_dampak_kriteria']] = $r['nama'];
            }
            $faktor["skor_control_dampak"] = "Skor Tingkat Dampak";
            $faktor["dampak_paskakontrol"] = "Level Tingkat Dampak";
            $return["control_faktor_dampak"]["Faktor Dampak Control"] = $faktor;
            $return["level_risiko_paskakontrol"] = "Level Risiko Control";
        } else {
            $return["risiko_paska_kontrol"] = array(
                "Risiko Terkontrol" => array(
                    "kemungkinan_paskakontrol" => "Tingkat Kemungkinan",
                    "dampak_paskakontrol" => "Tingkat Dampak",
                    "level_risiko_paskakontrol" => "Level Risiko"
                )
            );
        }

        if ($this->data['is_fraud']) {
            $faktor = array();
            foreach ($this->data['fraudkriteriakemungkinan'] as $r) {
                $faktor["faktor_actual_kemungkinan_" . $r['id_fraud_kemungkinan_kriteria']] = $r['nama'];
            }
            $faktor["skor_current_kemungkinan_real"] = "Skor Tingkat Kemungkinan";
            $faktor["kemungkinan_actual_real"] = "Level Tingkat Kemungkinan";
            $return["current_faktor_kemungkinan_real"]["Faktor Kemungkinan Actual"] = $faktor;

            $faktor = array();
            foreach ($this->data['fraudkriteriadampak'] as $r) {
                $faktor["faktor_actual_dampak_" . $r['id_fraud_dampak_kriteria']] = $r['nama'];
            }
            $faktor["skor_current_dampak_real"] = "Skor Tingkat Dampak";
            $faktor["dampak_actual_real"] = "Level Tingkat Dampak";
            $return["current_faktor_dampak"]["Faktor Dampak Actual"] = $faktor;
            $return["level_risiko_actual_real"] = "Level Risiko Actual";
        } else {
            $return["risiko_evaluasi"] = array(
                "Actual Risiko" => array(
                    "kemungkinan_actual_real" => "Tingkat Kemungkinan",
                    "dampak_actual_real" => "Tingkat Dampak",
                    "level_risiko_actual_real" => "Level Risiko"
                )
            );
        }

        $mitigasi_risiko = array();
        $mitigasi_risiko["nama_mitigasi"] = "Rencana Penanganan Risiko (Mitigasi)";
        $mitigasi_risiko["mitigasi_menurunkan"] = "Menurunkan Dampak atau Kemungkinan ?";
        $mitigasi_risiko["waktu_pelaksanaan"] = "Due Date Mitigasi (Action&nbsp;Plan)";
        $mitigasi_risiko["biaya_mitigasi"] = "Biaya Mitigasi";
        $mitigasi_risiko["cba_mitigasi"] = "Cost Benefit Analysis (CBA) atas Rencana Penanganan Risiko";
        $mitigasi_risiko["penanggungjawab_mitigasi"] = "Penanggung Jawab Rencana Mitigasi";
        $mitigasi_risiko["capaian_mitigasi_progress"] = "Capaian / Progress Pelaksanaan Rencana Mitigasi";


        $norowspan_mitigasi = array();
        $norowspan_mitigasi[] = "nama_mitigasi";
        $norowspan_mitigasi[] = "mitigasi_menurunkan";
        $norowspan_mitigasi[] = "waktu_pelaksanaan";
        $norowspan_mitigasi[] = "biaya_mitigasi";
        $norowspan_mitigasi[] = "cba_mitigasi";
        $norowspan_mitigasi[] = "penanggungjawab_mitigasi";
        $norowspan_mitigasi[] = "capaian_mitigasi_progress";

        unset($this->data['efektifmarr']['']);
        foreach ($this->data['efektifmarr'] as $key => $value) {
            $mitigasi_risiko['efektifm_' . $key] = $value;

            $this->data['type_header']['efektifm_' . $key] = array(
                'list' => $this->data['mtjawabanmarr']
            );

            $norowspan_mitigasi[] = 'efektifm_' . $key;
        }

        $mitigasi_risiko['id_pengukuranm'] = "Mitigasi Efektif";
        $norowspan_mitigasi[] = "id_pengukuranm";
        $return['mitigasi_risiko']['Mitigasi Risiko'] = $mitigasi_risiko;

        if ($this->data['is_fraud']) {
            $faktor = array();
            foreach ($this->data['fraudkriteriakemungkinan'] as $r) {
                $faktor["faktor_mitigasi_kemungkinan_" . $r['id_fraud_kemungkinan_kriteria']] = $r['nama'];
            }
            $faktor["skor_target_kemungkinan"] = "Skor Tingkat Kemungkinan";
            $faktor["kemungkinan_rdual"] = "Level Tingkat Kemungkinan";
            $return["target_faktor_kemungkinan"]["Faktor Kemungkinan yang Ditargetkan"] = $faktor;

            $faktor = array();
            foreach ($this->data['fraudkriteriadampak'] as $r) {
                $faktor["faktor_mitigasi_dampak_" . $r['id_fraud_dampak_kriteria']] = $r['nama'];
            }
            $faktor["skor_target_dampak"] = "Skor Tingkat Dampak";
            $faktor["dampak_rdual"] = "Level Tingkat Dampak";
            $return["target_faktor_dampak"]["Faktor Dampak yang Ditargetkan"] = $faktor;
            $return["level_risiko_residual"] = "Level Risiko yang Ditargetkan";
        } else {
            $return["risiko_residual"] = array(
                "Target Residual" => array(
                    "kemungkinan_rdual" => "Tingkat Kemungkinan",
                    "dampak_rdual" => "Tingkat Dampak",
                    "level_risiko_residual" => "Level Risiko"
                )
            );
        }
        $return["capaian_mitigasi_evaluasi"] = "Progress Capaian Kinerja";
        $return["hambatan_kendala"] = "Hambatan / Kendala Pelaksanaan Tindakan Mitigasi / Capaian Kinerja / Isu";
        $return["penyesuaian_mitigasi"] = "Penyesuaian Tindakan Mitigasi (jika diperlukan)";

        $this->data['norowspan_kri'] = $norowspan_kri;
        $this->data['norowspan_control'] = $norowspan_control;
        $this->data['norowspan_mitigasi'] = $norowspan_mitigasi;
        $this->data['norowspan'] = array_merge($norowspan_control, $norowspan_mitigasi, $norowspan_kri);

        $id_kajian_risiko = $this->data['row']['id_kajian_risiko'];

        if ($id_kajian_risiko) {
            if (!$this->mtkajianrisiko->isKegiatan($id_kajian_risiko))
                unset($return['sasaran_kegiatan']);
        }

        return $return;
    }

    public function Index($page = 0)
    {
        $this->isPrivateMethod("GET");
        $this->load->model("Risk_risikoModel", "model");
        $header = $this->_header();
        $param = $this->requestData();
        $data = $this->model->getListKertasKerja($param);

        $this->data['mtriskkemungkinan'] = $this->conn->GetArray("select * from mt_risk_kemungkinan where deleted_date is null order by id_kemungkinan desc");
        $this->data['mtriskdampak'] = $this->conn->GetArray("select * from mt_risk_dampak where deleted_date is null order by id_dampak asc " );
        $this->data['mtriskmatrix'] = $this->conn->GetArray("select mrm.*, mrt.NAMA, mrt.WARNA
			from mt_risk_matrix mrm
			join MT_RISK_TINGKAT mrt on mrt.ID_TINGKAT = mrm.ID_TINGKAT and mrm.deleted_date si null ");
        $warnarr = array();
        $rowswarna = $this->conn->GetArray("select k.kode k, d.kode d, t.warna from mt_risk_matrix mx 
                join mt_risk_kemungkinan k on mx.id_kemungkinan = k.id_kemungkinan and k.deleted_date is null
                join mt_risk_dampak d on mx.id_dampak = d.id_dampak and d.deleted_date is null 
                join mt_risk_tingkat t on mx.id_tingkat = t.id_tingkat t.deleted_date is null
                ");
        foreach ($rowswarna as $r) {
            $warnarr[$r['k'] . $r['d']] = $r['warna'];
        }

        $response = array();
        foreach ($data as $r) {
            $row = array();
            foreach ($param['header'] as $v) {
                $row[$v] = $r[$v];
                $type = $this->data['type_header'][$v];
                if ($type) {
                    if (is_array($type)) {
                        $row[$v . "_desc"] = $type['list'][$r[$v]];
                    } elseif ($type == 'date') {
                        $row[$v . "_desc"] = Eng2Ind($r[$v]);
                    } elseif ($type == 'rupiah') {
                        $row[$v . "_desc"] = rupiahAngka((float)$r[$v]);
                    } elseif ($type == 'rating') {
                        $row[$v . "_warna"] = $warnarr[$r[$v]];
                    }
                }
            }
            $response[] = $row;
        }

        $this->success($response);
    }
}
