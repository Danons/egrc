<?php
class Migration extends _Controller
{
    function __construct()
    {
        $this->xss_clean = true;
        parent::__construct();
        $this->load->helper("s");
    }

    function risiko()
    {

        $this->conn->Execute("DELETE FROM risk_risiko WHERE id_scorecard <> 12");
        $this->conn->Execute("update risk_risiko set is_lock = null WHERE id_scorecard = 12");

        $arr = [
            [
                "file" => "./manriskmigrasi/1.REGISTER RISK TIRTA RAHARJA UNIT KERJA SATUAN PENGAWASAN INTERN.xlsx",
                "id_scorecard" => 9,
            ],
            [
                "file" => "./manriskmigrasi/2. REGISTER RISK TIRTA RAHARJA UNIT KERJA PENELITIAN DAN PENGEMBANGAN.xlsx",
                "id_scorecard" => 5,
            ],
            [
                "file" => "./manriskmigrasi/3. REGISTER RISK TIRTA RAHARJA UNIT KERJA TEKNOLOGI DAN INFORMASI.xlsx",
                "id_scorecard" => 100,
            ],
            [
                "file" => "./manriskmigrasi/4. REGISTER RISK TIRTA RAHARJA UNIT KERJA PERENCANAAN TEKNIK.xlsx",
                "id_scorecard" => 41,
            ],
            [
                "file" => "./manriskmigrasi/5. REGISTER RISK TIRTA RAHARJA UNIT KERJA PRODUKSI.xlsx",
                "id_scorecard" => 42,
            ],
            [
                "file" => "./manriskmigrasi/6. REGISTER RISK TIRTA RAHARJA UNIT KERJA DISTRIBUSI DAN ATR.xlsx",
                "id_scorecard" => 34,
            ],
            [
                "file" => "./manriskmigrasi/7.REGISTER RISK TIRTA RAHARJA UNIT KERJA KEUANGAN.xlsx",
                "id_scorecard" => 39,
            ],
            [
                "file" => "./manriskmigrasi/8. REGISTER RISK TIRTA RAHARJA UNIT KERJA SUMBER DAYA MANUSIA - Administrasi.xlsx",
                "id_scorecard" => 91,
            ],
            [
                "file" => "./manriskmigrasi/8. REGISTER RISK TIRTA RAHARJA UNIT KERJA SUMBER DAYA MANUSIA - K3.xlsx",
                "id_scorecard" => 46,
            ],
            [
                "file" => "./manriskmigrasi/8. REGISTER RISK TIRTA RAHARJA UNIT KERJA SUMBER DAYA MANUSIA - Pengembangan.xlsx",
                "id_scorecard" => 45,
            ],
            [
                "file" => "./manriskmigrasi/9. REGISTER RISK TIRTA RAHARJA UNIT KERJA UMUM - ASET.xlsx",
                "id_scorecard" => 48,
            ],
            [
                "file" => "./manriskmigrasi/10. REGISTER RISK TIRTA RAHARJA UNIT KERJA UMUM - RUMAH TANGGA.xlsx",
                "id_scorecard" => 48,
            ],
            [
                "file" => "./manriskmigrasi/11. REGISTER RISK TIRTA RAHARJA UNIT KERJA UMUM - LOGISTIK.xlsx",
                "id_scorecard" => 47,
            ],
            [
                "file" => "./manriskmigrasi/12. REGISTER RISK TIRTA RAHARJA UNIT KERJA MUTU LAYANAN.xlsx",
                "id_scorecard" => 89,
            ],
            [
                "file" => "./manriskmigrasi/13.16 REGISTER RISK TIRTA RAHARJA UNIT KERJA WILAYAH 1-4.xlsx",
                "id_scorecard" => 101,
            ],
            [
                "file" => "./manriskmigrasi/13.16 REGISTER RISK TIRTA RAHARJA UNIT KERJA WILAYAH 1-4.xlsx",
                "id_scorecard" => 102,
            ],
            [
                "file" => "./manriskmigrasi/13.16 REGISTER RISK TIRTA RAHARJA UNIT KERJA WILAYAH 1-4.xlsx",
                "id_scorecard" => 103,
            ],
            [
                "file" => "./manriskmigrasi/13.16 REGISTER RISK TIRTA RAHARJA UNIT KERJA WILAYAH 1-4.xlsx",
                "id_scorecard" => 104,
            ],
            [
                "file" => "./manriskmigrasi/17. REGISTER RISK TIRTA RAHARJA UNIT KERJA SEKPER - ULP.xlsx",
                "id_scorecard" => 98,
            ],
            [
                "file" => "./manriskmigrasi/18. REGISTER RISK TIRTA RAHARJA UNIT KERJA SEKPER - HUMAS KESEKRETARIATAN.xlsx",
                "id_scorecard" => 44,
            ],
            [
                "file" => "./manriskmigrasi/19. REGISTER RISK TIRTA RAHARJA UNIT KERJA SEKPER - HUKUM & GCG.xlsx",
                "id_scorecard" => 43,
            ],
            [
                "file" => "./manriskmigrasi/20.REGISTER RISK TIRTA RAHARJA UNIT KERJA  LABORATORIUM.xlsx",
                "id_scorecard" => 99,
            ],
        ];
        // $id_sasaran = ;
        $id_scorecard = 58;

        foreach ($arr as $r) {
            $this->_risiko($r['id_scorecard'], $r['file']);
        }
    }

    function _risiko($id_scorecard, $file)
    {
        $this->conn->debug = 1;
        $this->load->model("Risk_risikoModel", "risiko");
        $this->load->model("Risk_kegiatanModel", "kegiatan");
        $this->data['responsearr'] = [
            "" => "",
            "Diabaikan" => "Menghindari (Avoidance)",
            "Dikendalikan" => "Mengurangi (Reduce)",
            "Dialihkan" => "Membagi (Share)",
            "Diterima" => "Menerima (Acceptance)",
        ];
        // $this->conn->Execute("delete from risk_sasaran");
        // $this->conn->Execute("delete from risk_scorecard_files");
        // $this->conn->Execute("delete from risk_task");
        // $this->conn->Execute("delete from risk_risiko_current");
        // $this->conn->Execute("delete from risk_risiko_penyebab");
        // $this->conn->Execute("delete from risk_risiko_dampak");
        // $this->conn->Execute("delete from risk_control_risiko");
        // $this->conn->Execute("delete from risk_mitigasi_risiko");
        // $this->conn->Execute("delete from risk_risiko");
        // $this->conn->Execute("delete from risk_mitigasi");
        // $this->conn->Execute("delete from risk_control");
        // $this->conn->Execute("delete from risk_penyebab");
        // $this->conn->Execute("delete from risk_dampak");

        // $rows = $this->conn->GetArray("select trim(replace(replace(replace(unit,':',''),'Dinas',''),'Group','')) unitstr, * 
        // from risk_risiko_temp where unit = 'Group Perencanaan & Pengembangan TI'");

        $this->data['rowheader'] = $this->conn->GetRow("select * 
        from risk_scorecard 
        where id_scorecard = " . $this->conn->escape($id_scorecard));


        $this->load->library('Factory');
        $inputFileType = Factory::identify($file);
        $objReader = Factory::createReader($inputFileType);
        $excel = $objReader->load($file);
        $sheet = $excel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        // $this->conn->StartTrans();

        for ($row = 9; $row <= $highestRow; $row++) {
            $kegiatan =  array(
                'nama' => (string) $sheet->getCell("C" . $row)->getValue(),
                'target_sasaran' => (string) $sheet->getCell("D" . $row)->getValue(),
                'owner' => $this->data['rowheader']['owner'],
                'id_scorecard' => $id_scorecard
            );

            if ($kegiatan['nama']) {
                $id_kegiatan = $this->conn->GetOne("select id_kegiatan 
                from risk_kegiatan 
                where nama like '%" . strtolower($kegiatan['nama']) . "%' 
                and id_scorecard = " . $this->conn->escape($id_scorecard));

                if (!$id_kegiatan) {
                    $id_kegiatan = $this->kegiatan->Insert($kegiatan)['data']['id_kegiatan'];
                }
            }


            $risiko =  array(
                'nama' => (string) $sheet->getCell("F" . $row)->getValue(),
                'deskripsi' => (string) $sheet->getCell("K" . $row)->getValue(),
                'id_kegiatan' => $id_kegiatan,
                'id_scorecard' => $id_scorecard,
            );
            $risiko['tgl_risiko'] = "2023-01-01";
            $risiko["nomor"] = $this->risiko->getNomorRisiko(
                $this->data['rowheader']['id_unit'],
                null,
                null,
                $risiko['tgl_risiko'],
                false
            );
            $risiko['is_opp_inherent'] = "-1";
            $risiko['is_opp_current'] = "-1";
            $risiko['is_opp_target'] = "-1";
            $risiko['is_opp_evaluasi'] = "-1";
            $risiko['inheren_dampak'] = (string) $sheet->getCell("N" . $row)->getValue();
            $risiko['inheren_kemungkinan'] = (string) $sheet->getCell("M" . $row)->getValue();
            $risiko['control_dampak_penurunan'] = (string) $sheet->getCell("T" . $row)->getValue();
            $risiko['control_kemungkinan_penurunan'] = (string) $sheet->getCell("S" . $row)->getValue();
            $risiko['residual_dampak_evaluasi'] = "{{null}}";
            $risiko['residual_kemungkinan_evaluasi'] = "{{null}}";
            $risiko['response'] = $this->data['responsearr'][(string) $sheet->getCell("V" . $row)->getValue()];
            // $risiko['id_status_pengajuan'] = '1';
            $risiko['is_lock'] = '1';

            $id_risiko = $this->conn->GetOne("select id_risiko 
            from risk_risiko 
            where nama like '%" . strtolower($risiko['nama']) . "%' 
            and id_scorecard = " . $this->conn->escape($id_scorecard) . " 
            and id_kegiatan = " . $this->conn->escape($id_kegiatan));

            if ($id_risiko) {
                $this->conn->goUpdate("risk_risiko", $risiko, "id_risiko = " . $id_risiko);
            } else {
                $this->conn->goInsert("risk_risiko", $risiko);
                $id_risiko = $this->conn->GetOne("select max(id_risiko) from risk_risiko");
            }

            $str = (string) $sheet->getCell("J" . $row)->getValue();
            $str = str_replace(["1. ", "2. ", "3. ", "4. ", "5. ", "\n -"], "\n", $str);
            $exp = explode("\n", $str);
            if (!is_array($exp))
                $exp = [$exp];

            foreach ($exp as $v) {
                if (!$v)
                    continue;

                $penyebab = [];
                $penyebab["nama"] = $v;
                // $id_risk_penyebab = $this->conn->GetOne("select id_risk_penyebab from risk_penyebab where trim(lower(nama)) =trim(lower('$penyebab[nama]'))");
                $id_risk_penyebab = $this->conn->GetOne("
                select id_risk_penyebab 
                from risk_penyebab 
                where 
                    trim(lower(nama)) = trim(lower('$penyebab[nama]')) or 
                    lower(nama) = lower('$penyebab[nama]')or 
                    nama = '$penyebab[nama]'
                ");
                if (!$id_risk_penyebab) {
                    $this->conn->goInsert("risk_penyebab", $penyebab);
                    // $id_risk_penyebab = $this->conn->GetOne("select id_risk_penyebab from risk_penyebab where trim(lower(nama)) =trim(lower('$penyebab[nama]'))");
                    $id_risk_penyebab = $this->conn->GetOne("
                    select id_risk_penyebab 
                    from risk_penyebab 
                    where 
                        trim(lower(nama)) = trim(lower('$penyebab[nama]')) or 
                        lower(nama) = lower('$penyebab[nama]')or 
                        nama = '$penyebab[nama]'
                    ");
                }

                $cek = $this->conn->GetOne("select 1 from risk_risiko_penyebab 
                where id_risiko = $id_risiko 
                and id_risk_penyebab = $id_risk_penyebab");

                if (!$cek) {
                    $risikopenyebab = [];
                    $risikopenyebab["id_risiko"] = $id_risiko;
                    $risikopenyebab["id_risk_penyebab"] = $id_risk_penyebab;
                    $this->conn->goInsert("risk_risiko_penyebab", $risikopenyebab);
                }
            }


            $str = (string) $sheet->getCell("L" . $row)->getValue();
            $str = str_replace(["1. ", "2. ", "3. ", "4. ", "5. ", "\n -"], "\n", $str);
            $exp = explode("\n", $str);
            if (!is_array($exp))
                $exp = [$exp];

            foreach ($exp as $v) {
                if (!$v)
                    continue;

                $dampak = [];
                $dampak["nama"] = $v;
                // $id_risk_dampak = $this->conn->GetOne("select id_risk_dampak from risk_dampak where trim(lower(nama)) =trim(lower('$dampak[nama]'))");
                $id_risk_dampak = $this->conn->GetOne("
                select id_risk_dampak 
                from risk_dampak 
                where 
                    trim(lower(nama)) = trim(lower('$dampak[nama]')) or
                    lower(nama) = lower('$dampak[nama]') or
                    nama = '$dampak[nama]'
                ");
                if (!$id_risk_dampak) {
                    $this->conn->goInsert("risk_dampak", $dampak);
                    // $id_risk_dampak = $this->conn->GetOne("select id_risk_dampak from risk_dampak where trim(lower(nama)) =trim(lower('$dampak[nama]'))");
                    $id_risk_dampak = $this->conn->GetOne("
                    select id_risk_dampak 
                    from risk_dampak 
                    where 
                        trim(lower(nama)) = trim(lower('$dampak[nama]')) or
                        lower(nama) = lower('$dampak[nama]') or
                        nama = '$dampak[nama]'
                    ");
                }


                $cek = $this->conn->GetOne("select 1 from risk_risiko_dampak 
                where id_risiko = $id_risiko 
                and id_risk_dampak = $id_risk_dampak");

                if (!$cek) {
                    $risikodampak = [];
                    $risikodampak["id_risiko"] = $id_risiko;
                    $risikodampak["id_risk_dampak"] = $id_risk_dampak;
                    $this->conn->goInsert("risk_risiko_dampak", $risikodampak);
                }
            }

            $str = (string) $sheet->getCell("P" . $row)->getValue();
            $str = str_replace(["1. ", "2. ", "3. ", "4. ", "5. ", "\n -"], "\n", $str);
            $exp = explode("\n", $str);
            if (!is_array($exp))
                $exp = [$exp];

            foreach ($exp as $v) {
                if (!$v)
                    continue;

                $kontrol = [];
                $kontrol["nama"] = $v;
                $kontrol["id_risiko"] = $id_risiko;
                // $id_control = $this->conn->GetOne("select id_control from risk_control where trim(lower(nama)) =trim(lower('$kontrol[nama]'))");
                $id_control = $this->conn->GetOne("
                select id_control 
                from risk_control 
                where 
                    trim(lower(nama)) = trim(lower('$kontrol[nama]')) or
                    lower(nama) = lower('$kontrol[nama]') or
                    nama = '$kontrol[nama]'
                ");
                if (!$id_control) {
                    $this->conn->goInsert("risk_control", $kontrol);
                    // $id_control = $this->conn->GetOne("select id_control from risk_control where trim(lower(nama)) =trim(lower('$kontrol[nama]'))");
                    $id_control = $this->conn->GetOne("
                    select id_control 
                    from risk_control 
                    where 
                        trim(lower(nama)) = trim(lower('$kontrol[nama]')) or
                        lower(nama) = lower('$kontrol[nama]') or
                        nama = '$kontrol[nama]'
                    ");
                }

                $cek = $this->conn->GetOne("select 1 from risk_control_risiko 
                where id_risiko = $id_risiko 
                and id_control = $id_control");

                if (!$cek) {
                    $risikokontrol = [];
                    $risikokontrol["id_risiko"] = $id_risiko;
                    $risikokontrol["id_control"] = $id_control;
                    $this->conn->goInsert("risk_control_risiko", $risikokontrol);
                }
            }



            $str = (string) $sheet->getCell("W" . $row)->getValue();
            $str = str_replace(["1. ", "2. ", "3. ", "4. ", "5. ", "\n -"], "\n", $str);
            $exp = explode("\n", $str);
            if (!is_array($exp))
                $exp = [$exp];

            foreach ($exp as $v) {
                if (!$v)
                    continue;

                $mitigasi = [];
                $mitigasi["nama"] = $v;
                $mitigasi["id_risiko"] = $id_risiko;
                // $id_mitigasi = $this->conn->GetOne("select id_mitigasi from risk_mitigasi where trim(lower(nama)) =trim(lower('$mitigasi[nama]'))");
                $id_mitigasi = $this->conn->GetOne("
                select id_mitigasi 
                from risk_mitigasi 
                where 
                    trim(lower(nama)) = trim(lower('$mitigasi[nama]')) or
                    lower(nama) = lower('$mitigasi[nama]') or
                    nama = '$mitigasi[nama]'
                ");
                if (!$id_mitigasi) {
                    $this->conn->goInsert("risk_mitigasi", $mitigasi);
                    $id_mitigasi = $this->conn->GetOne("select id_mitigasi from risk_mitigasi where trim(lower(nama)) =trim(lower('$mitigasi[nama]'))");
                }


                $cek = $this->conn->GetOne("select 1 from risk_mitigasi_risiko 
                where id_risiko = $id_risiko 
                and id_mitigasi = $id_mitigasi");

                if (!$cek) {
                    $risikomitigasi = [];
                    $risikomitigasi["id_risiko"] = $id_risiko;
                    $risikomitigasi["id_mitigasi"] = $id_mitigasi;
                    $this->conn->goInsert("risk_mitigasi_risiko", $risikomitigasi);
                }
            }

            $str = (string) $sheet->getCell("X" . $row)->getValue();
            if ($str) {
                $str = str_replace(["1. ", "2. ", "3. ", "4. ", "5. ", "\n -"], "\n", $str);
                $exp = explode("\n", $str);
                if (!is_array($exp))
                    $exp = [$exp];

                foreach ($exp as $v) {
                    if (!$v)
                        continue;

                    $mitigasi = [];
                    $mitigasi["nama"] = $v;
                    $mitigasi["id_risiko"] = $id_risiko;
                    // $id_mitigasi = $this->conn->GetOne("select id_mitigasi from risk_mitigasi where trim(lower(nama)) =trim(lower('$mitigasi[nama]'))");
                    $id_mitigasi = $this->conn->GetOne("
                select id_mitigasi 
                from risk_mitigasi 
                where 
                    trim(lower(nama)) = trim(lower('$mitigasi[nama]')) or
                    lower(nama) = lower('$mitigasi[nama]') or
                    nama = '$mitigasi[nama]'
                ");
                    if (!$id_mitigasi) {
                        $this->conn->goInsert("risk_mitigasi", $mitigasi);
                        $id_mitigasi = $this->conn->GetOne("select id_mitigasi from risk_mitigasi where trim(lower(nama)) =trim(lower('$mitigasi[nama]'))");
                    }


                    $cek = $this->conn->GetOne("select 1 from risk_mitigasi_risiko 
                    where id_risiko = $id_risiko 
                    and id_mitigasi = $id_mitigasi");

                    if (!$cek) {
                        $risikomitigasi = [];
                        $risikomitigasi["id_risiko"] = $id_risiko;
                        $risikomitigasi["id_mitigasi"] = $id_mitigasi;
                        $this->conn->goInsert("risk_mitigasi_risiko", $risikomitigasi);
                    }
                }
            }
        }
    }

    function risiko1()
    {

        $this->conn->debug = 1;
        $this->load->model("Risk_risikoModel", "risiko");
        // $this->conn->Execute("delete from risk_sasaran");
        // $this->conn->Execute("delete from risk_scorecard_files");
        // $this->conn->Execute("delete from risk_task");
        // $this->conn->Execute("delete from risk_risiko_current");
        // $this->conn->Execute("delete from risk_risiko_penyebab");
        // $this->conn->Execute("delete from risk_risiko_dampak");
        // $this->conn->Execute("delete from risk_control_risiko");
        // $this->conn->Execute("delete from risk_mitigasi_risiko");
        // $this->conn->Execute("delete from risk_risiko");
        // $this->conn->Execute("delete from risk_mitigasi");
        // $this->conn->Execute("delete from risk_control");
        // $this->conn->Execute("delete from risk_penyebab");
        // $this->conn->Execute("delete from risk_dampak");

        $rows = $this->conn->GetArray("select trim(replace(replace(replace(unit,':',''),'Dinas',''),'Group','')) unitstr, * 
        from risk_risiko_temp where unit = 'Group Perencanaan & Pengembangan TI'");
        dpr($rows, 1);

        foreach ($rows as $r) {
            $r['sasaran'] = str_replace("\n", " ", $r['sasaran']);
            $r['mitigasi'] = str_replace("\n", " ", $r['mitigasi']);

            $id_unit = $this->conn->GetOne("select table_code from mt_sdm_unit where trim(table_desc) = '$r[unitstr]'");
            if (!$id_unit)
                dpr($r['unitstr'] . " tidak ditemukan", 1);

            $id_scorecard = $this->conn->GetOne("select id_scorecard from risk_scorecard where navigasi = 0 and id_unit = '$id_unit'");
            if (!$id_scorecard)
                dpr($r['unitstr'] . " $id_unit tidak ditemukan", 1);


            $ret = $this->conn->goUpdate("risk_scorecard", array("id_status_pengajuan" => '1'), "id_scorecard = " . $id_scorecard);

            $sasaran = [];
            $sasaran["nama"] = $r['sasaran'];
            $id_sasaran = $this->conn->GetOne("select id_sasaran from risk_sasaran where trim(lower(nama)) =trim(lower('$sasaran[nama]'))");
            if (!$id_sasaran) {
                $this->conn->goInsert("risk_sasaran", $sasaran);
                $id_sasaran = $this->conn->GetOne("select id_sasaran from risk_sasaran where trim(lower(nama)) =trim(lower('$sasaran[nama]'))");
            }

            $risiko = [];
            $risiko['tgl_risiko'] = "2023-01-01";
            $risiko["nomor"] = $this->risiko->getNomorRisiko(
                $id_unit,
                null,
                null,
                $risiko['tgl_risiko'],
                false
            );
            $risiko['id_scorecard'] = $id_scorecard;
            $risiko['id_sasaran'] = $id_sasaran;
            $risiko['penyebab'] = $r['penyebab'];
            $risiko['dampak'] = $r['dampak'];
            $risiko['is_rutin'] = (trim($r['rutin']) == 'NR' ? 0 : 1);
            $risiko['id_taksonomi_area'] = $this->conn->GetOne("select id_taksonomi_area from mt_risk_taksonomi_area where kode = " . $this->conn->escape($r['kategori']));
            $risiko['regulasi'] = $r['kewajiban'];
            $risiko['id_aspek_lingkungan'] = $this->conn->GetOne("select id_aspek_lingkungan from mt_aspek_lingkungan where kode = " . $this->conn->escape($r['operasional']));
            $risiko['is_opp_inherent'] = $r['inn'];
            $risiko['is_opp_current'] = $r['cur'];
            $risiko['is_opp_target'] = $r['cur'];
            $risiko['is_opp_evaluasi'] = $r['res'];
            $risiko['is_signifikan_inherent'] = (trim($r['in_sig']) == 'S' ? 1 : 0);
            $risiko['is_signifikan_current'] = (trim($r['cur_sig']) == 'S' ? 1 : 0);
            $risiko['is_signifikan_evaluasi'] = (trim($r['res_sig']) == 'S' ? 1 : 0);
            $risiko['inheren_dampak'] = abs($r['in_dampak']) ? abs($r['in_dampak']) : null;
            $risiko['inheren_kemungkinan'] = abs($r['in_kem']) ? abs($r['in_kem']) : null;
            $risiko['control_dampak_penurunan'] = abs($r['cur_dampak']) ? abs($r['cur_dampak']) : null;
            $risiko['control_kemungkinan_penurunan'] = abs($r['cur_kem']) ? abs($r['cur_kem']) : null;
            $risiko['residual_dampak_evaluasi'] = abs($r['res_dampak']) ? abs($r['res_dampak']) : null;
            $risiko['residual_kemungkinan_evaluasi'] = abs($r['res_kem']) ? abs($r['res_kem']) : null;
            $risiko['id_status_pengajuan'] = '1';

            $this->conn->goInsert("risk_risiko", $risiko);
            $id_risiko = $this->conn->GetOne("select max(id_risiko) from risk_risiko");



            $str = $r['penyebab'];
            $str = str_replace(["1. ", "2. ", "3. ", "4. ", "5. ", "\n -"], "\n", $str);
            $exp = explode("\n", $str);
            if (!is_array($exp))
                $exp = [$exp];

            foreach ($exp as $v) {
                if (!$v)
                    continue;

                $penyebab = [];
                $penyebab["nama"] = $v;
                // $id_risk_penyebab = $this->conn->GetOne("select id_risk_penyebab from risk_penyebab where trim(lower(nama)) =trim(lower('$penyebab[nama]'))");
                $id_risk_penyebab = $this->conn->GetOne("
                select id_risk_penyebab 
                from risk_penyebab 
                where 
                    trim(lower(nama)) = trim(lower('$penyebab[nama]')) or 
                    lower(nama) = lower('$penyebab[nama]')or 
                    nama = '$penyebab[nama]'
                ");
                if (!$id_risk_penyebab) {
                    $this->conn->goInsert("risk_penyebab", $penyebab);
                    // $id_risk_penyebab = $this->conn->GetOne("select id_risk_penyebab from risk_penyebab where trim(lower(nama)) =trim(lower('$penyebab[nama]'))");
                    $id_risk_penyebab = $this->conn->GetOne("
                    select id_risk_penyebab 
                    from risk_penyebab 
                    where 
                        trim(lower(nama)) = trim(lower('$penyebab[nama]')) or 
                        lower(nama) = lower('$penyebab[nama]')or 
                        nama = '$penyebab[nama]'
                    ");
                }

                $risikopenyebab = [];
                $risikopenyebab["id_risiko"] = $id_risiko;
                $risikopenyebab["id_risk_penyebab"] = $id_risk_penyebab;
                $this->conn->goInsert("risk_risiko_penyebab", $risikopenyebab);
            }

            if ($id_risk_penyebab)
                $this->conn->goUpdate("risk_risiko", ["id_risk_penyebab" => $id_risk_penyebab], "id_risiko = " . $this->conn->escape($id_risiko));



            $str = $r['dampak'];
            $str = str_replace(["1. ", "2. ", "3. ", "4. ", "5. ", "\n -"], "\n", $str);
            $exp = explode("\n", $str);
            if (!is_array($exp))
                $exp = [$exp];

            foreach ($exp as $v) {
                if (!$v)
                    continue;

                $dampak = [];
                $dampak["nama"] = $v;
                // $id_risk_dampak = $this->conn->GetOne("select id_risk_dampak from risk_dampak where trim(lower(nama)) =trim(lower('$dampak[nama]'))");
                $id_risk_dampak = $this->conn->GetOne("
                select id_risk_dampak 
                from risk_dampak 
                where 
                    trim(lower(nama)) = trim(lower('$dampak[nama]')) or
                    lower(nama) = lower('$dampak[nama]') or
                    nama = '$dampak[nama]'
                ");
                if (!$id_risk_dampak) {
                    $this->conn->goInsert("risk_dampak", $dampak);
                    // $id_risk_dampak = $this->conn->GetOne("select id_risk_dampak from risk_dampak where trim(lower(nama)) =trim(lower('$dampak[nama]'))");
                    $id_risk_dampak = $this->conn->GetOne("
                    select id_risk_dampak 
                    from risk_dampak 
                    where 
                        trim(lower(nama)) = trim(lower('$dampak[nama]')) or
                        lower(nama) = lower('$dampak[nama]') or
                        nama = '$dampak[nama]'
                    ");
                }

                $risikodampak = [];
                $risikodampak["id_risiko"] = $id_risiko;
                $risikodampak["id_risk_dampak"] = $id_risk_dampak;
                $this->conn->goInsert("risk_risiko_dampak", $risikodampak);
            }

            if ($id_risk_dampak)
                $this->conn->goUpdate("risk_risiko", ["id_risk_dampak" => $id_risk_dampak], "id_risiko = " . $this->conn->escape($id_risiko));


            $str = $r['kontrol'];
            $str = str_replace(["1. ", "2. ", "3. ", "4. ", "5. ", "\n -"], "\n", $str);
            $exp = explode("\n", $str);
            if (!is_array($exp))
                $exp = [$exp];

            foreach ($exp as $v) {
                if (!$v)
                    continue;

                $kontrol = [];
                $kontrol["nama"] = $v;
                $kontrol["id_risiko"] = $id_risiko;
                // $id_control = $this->conn->GetOne("select id_control from risk_control where trim(lower(nama)) =trim(lower('$kontrol[nama]'))");
                $id_control = $this->conn->GetOne("
                select id_control 
                from risk_control 
                where 
                    trim(lower(nama)) = trim(lower('$kontrol[nama]')) or
                    lower(nama) = lower('$kontrol[nama]') or
                    nama = '$kontrol[nama]'
                ");
                if (!$id_control) {
                    $this->conn->goInsert("risk_control", $kontrol);
                    // $id_control = $this->conn->GetOne("select id_control from risk_control where trim(lower(nama)) =trim(lower('$kontrol[nama]'))");
                    $id_control = $this->conn->GetOne("
                    select id_control 
                    from risk_control 
                    where 
                        trim(lower(nama)) = trim(lower('$kontrol[nama]')) or
                        lower(nama) = lower('$kontrol[nama]') or
                        nama = '$kontrol[nama]'
                    ");
                }

                $risikokontrol = [];
                $risikokontrol["id_risiko"] = $id_risiko;
                $risikokontrol["id_control"] = $id_control;
                $this->conn->goInsert("risk_control_risiko", $risikokontrol);
            }



            $str = $r['mitigasi'];
            $str = str_replace(["1. ", "2. ", "3. ", "4. ", "5. ", "\n -"], "\n", $str);
            $exp = explode("\n", $str);
            if (!is_array($exp))
                $exp = [$exp];

            foreach ($exp as $v) {
                if (!$v)
                    continue;

                $mitigasi = [];
                $mitigasi["nama"] = $v;
                $mitigasi["id_risiko"] = $id_risiko;
                // $id_mitigasi = $this->conn->GetOne("select id_mitigasi from risk_mitigasi where trim(lower(nama)) =trim(lower('$mitigasi[nama]'))");
                $id_mitigasi = $this->conn->GetOne("
                select id_mitigasi 
                from risk_mitigasi 
                where 
                    trim(lower(nama)) = trim(lower('$mitigasi[nama]')) or
                    lower(nama) = lower('$mitigasi[nama]') or
                    nama = '$mitigasi[nama]'
                ");
                if (!$id_mitigasi) {
                    $this->conn->goInsert("risk_mitigasi", $mitigasi);
                    $id_mitigasi = $this->conn->GetOne("select id_mitigasi from risk_mitigasi where trim(lower(nama)) =trim(lower('$mitigasi[nama]'))");
                }

                $risikomitigasi = [];
                $risikomitigasi["id_risiko"] = $id_risiko;
                $risikomitigasi["id_mitigasi"] = $id_mitigasi;
                $this->conn->goInsert("risk_mitigasi_risiko", $risikomitigasi);
            }


            // die();
        }

        # insert penyebab yang gagal ter insert
        // $this->conn->Execute("INSERT into risk_risiko_penyebab (id_risiko,id_risk_penyebab) 
        // select a.id_risiko,c.id_risk_penyebab from risk_risiko a
        // left join risk_penyebab c on (c.nama =  a.penyebab OR LOWER(c.nama) =  LOWER(a.penyebab))
        //  where not exists(select 1 from risk_risiko_penyebab b where a.id_risiko = b.id_risiko) and penyebab is not null
        //  and c.id_risk_penyebab is not null");

        // # insert dampak yang gagal ter insert
        // $this->conn->Execute("INSERT into risk_risiko_dampak (id_risiko,id_risk_dampak) 
        // select a.id_risiko,c.id_risk_dampak from risk_risiko a
        // left join risk_dampak c on (c.nama =  a.dampak OR LOWER(c.nama) =  LOWER(a.dampak))
        //  where not exists(select 1 from risk_risiko_dampak b where a.id_risiko = b.id_risiko) and dampak is not null
        //  and c.id_risk_dampak is not null");
    }
}
