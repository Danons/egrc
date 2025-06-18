<div class="col-sm-12">

    <?php
    if ($row['jenis'] != 'eksternal') {
        $from = UI::createSelect('id_spn', $spnarr, $row['id_spn'], $edited, 'form-control ', "style='width:100%' onchange='goSubmit(\"set_value_spn\")'");
        echo UI::createFormGroup($from, $rules["id_spn"], "id_spn", "Surat Tugas", false, 2);
    }
    if ($row['jenis'] != 'eksternal') {
        $from = UI::createSelect('id_sasaran', $sasaranarr, $row['id_sasaran'], $edited, 'form-control ', "style='width:100%' onchange='goSubmit(\"set_value\")'");
        echo UI::createFormGroup($from, $rules["id_sasaran"], "id_sasaran", "Sasaran", false, 2);
    }
    // dpr($row['id_sasaran']);

    // $from .= UI :: create
    // $from = UI::createTextBox('nomor_stp', $row['nomor_stp'], '200', '100', $edited, 'form-control ', "style='width:100%'");
    // echo UI::createFormGroup($from, $rules["nomor_stp"], "nomor_stp", "Nomor " . ($row['jenis'] == 'eksternal' ? 'SPTME' : 'SPTP'), false, 2);

    // $from = UI::createTextBox('tanggal_sptp', $row['tanggal_sptp'], '10', '10', $edited, 'form-control datepicker', "style='width:100px; display:inline;'");
    // echo UI::createFormGroup($from, $rules["tanggal_sptp"], "tanggal_sptp", "Tanggal " . ($row['jenis'] == 'eksternal' ? 'SPTME' : 'SPTP'), false, 2);


    $from = UI::createSelect('id_unit', $unitarr, $row['id_unit'], $edited, 'form-control ', "style='width:100%' onchange='goSubmit(\"set_value\")'");
    echo UI::createFormGroup($from, $rules["id_unit"], "id_unit", "Nama Objek Pemeriksaan", false, 2);

    if ($row['jenis'] == 'penyuapan') {
        $from = UI::createTextBox('objeklainnya', $row['objeklainnya'], '200', '100', $edited, 'form-control ', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["objeklainnya"], "objeklainnya", "Objek Pemeriksaan Lainnya", false, 2);
    }

    if ($row['jenis'] == 'khusus') {
        if (!$userarr[$row['user_id']]) {
            $userarr[$row['user_id']] = $row['nama_user'];
        }
        $from = UI::createSelect('user_id', $userarr, $row['user_id'], $edited, 'form-control ', "style='width:100%' onchange='goSubmit(\"set_value\")'");
        echo UI::createFormGroup($from, $rules["user_id"], "user_id", "Nama Orang", false, 2);
    }
    ?>

    <?php
    // dpr($row, 1);
    // $from = UI::createSelect('id_subbid', $subbidarr, $row['id_subbid'], $edited, 'form-control ', "style='width:100%'");
    // echo UI::createFormGroup($from, $rules["id_subbid"], "id_subbid", "Bidang", false, 2);

    $from = UI::createTextBox('nama', $row['nama'], '200', '100', $edited, 'form-control ', "style='width:100%'");
    echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama Kegiatan / Program / Bidang", false, 2);

    if ($row['jenis'] == 'eksternal') {
        // $from = UI::createSelect('id_jenis_audit_eksternal', $jeniseksternalarr, $row['id_jenis_audit_eksternal'], $edited, 'form-control ', "style='width:100%'");
        // echo UI::createFormGroup($from, $rules["id_jenis_audit_eksternal"], "id_jenis_audit_eksternal", "Jenis Audit Eksternal", false, 2);
        $from = UI::createTextBox('jenis_audit_eksternal', $row['jenis_audit_eksternal'], '200', '100', $edited, 'form-control ', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["jenis_audit_eksternal"], "jenis_audit_eksternal", "Jenis Audit Eksternal", false, 2);
    }
    ?>

    <?php
    $from = UI::createTextArea('lokasi', $row['lokasi'], '', '', $edited, 'form-control ', "");
    echo UI::createFormGroup($from, $rules["lokasi"], "lokasi", "Lokasi", false, 2);
    ?>

    <?php
    $from = UI::createTextBox('tgl_mulai', $row['tgl_mulai'], '10', '10', $edited, 'form-control datepicker', "style='width:100px; display:inline;'");
    $from .= "&nbsp;s/d&nbsp;" . UI::createTextBox('tgl_selesai', $row['tgl_selesai'], '10', '10', $edited, 'form-control datepicker', "style='width:100px; display:inline;'");
    echo UI::createFormGroup($from, $rules["tgl_mulai"], "tgl_mulai", "Periode Pemeriksaan", false, 2);
    ?>

    <?php
    $from = UI::createTextArea('tujuan', $row['tujuan'], '', '', $edited, 'form-control ', "");
    echo UI::createFormGroup($from, $rules["tujuan"], "tujuan", "Tujuan", false, 2);

    $from = UI::createTextArea('keterangan', $row['keterangan'], '', '', $edited, 'form-control ', "");
    echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "Keterangan", false, 2);
    ?>

    <?php
    /* if ($rowheader['jenis'] == 'mutu' || $rowheader['jenis'] == 'penyuapan') {
        $from = UI::createTextBox('nama_jabatan_penyusun', $row['nama_jabatan_penyusun'], '200', '100', $edited, 'form-control ', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["nama_jabatan_penyusun"], "nama_jabatan_penyusun", "Auditor", false, 2);
        
        $from = UI::createTextBox('nama_jabatan_pereview', $row['nama_jabatan_pereview'], '200', '100', $edited, 'form-control ', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["nama_jabatan_pereview"], "nama_jabatan_pereview", "Ketua Tim Audit", false, 2);
    } else { */

    if ($row['jenis'] != 'eksternal') {
        if (!($row['jenis'] == 'penyuapan' || $row['jenis'] == 'mutu')) {
            if (!$pimpinanarr[$row['id_penyusun']])
                $pimpinanarr[$row['id_penyusun']] = $row['nama_penyusun'];

            $from = UI::createSelect("id_penyusun", $pimpinanarr, $row['id_penyusun'], $edited, 'form-control ', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
            $from .= UI::createTextBox('nama_jabatan_penyusun', $row['nama_jabatan_penyusun'], '200', '100', $edited, 'form-control ', "style='width:100%' readonly");
            echo UI::createFormGroup($from, $rules["nama_jabatan_penyusun"], "nama_jabatan_penyusun", "Koordinator", false, 2);
        }
        if (!$pelaksanaarr[$row['id_pereview']])
            $pelaksanaarr[$row['id_pereview']] = $row['nama_pereview'];

        // dpr($pelaksanaarr);

        $from = UI::createSelect("id_pereview", $pelaksanaarr, $row['id_pereview'], $edited, 'form-control ', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
        $from .= UI::createTextBox('nama_jabatan_pereview', $row['nama_jabatan_pereview'], '200', '100', $edited, 'form-control ', "style='width:100%' readonly");
        echo UI::createFormGroup($from, $rules["nama_jabatan_pereview"], "nama_jabatan_pereview", "Pengendali Teknis", false, 2);

        $from = UI::createSelect("id_penanggung_jawab", $penanggungjawabarr, $row['id_penanggung_jawab'], $edited, 'form-control ', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
        $from .= UI::createTextBox('nama_jabatan_penanggung_jawab', $row['nama_jabatan_penanggung_jawab'], '200', '100', $edited, 'form-control ', "style='width:100%' readonly");
        echo UI::createFormGroup($from, $rules["nama_jabatan_penanggung_jawab"], "nama_jabatan_penanggung_jawab", "Ketua Tim", false, 2);

        /*} */


        $no = 1;
        $from = function ($val = null, $edited, $k = 0, $ci) {
            if (!$ci->data['pelaksanaarr'][$val['user_id']])
                $ci->data['pelaksanaarr'][$val['user_id']] = $val['nama'];

            $from = null;
            $from .= "<td>";
            $from .= UI::createSelect("pemeriksaan_tim[$k][user_id]", $ci->data['pelaksanaarr'], $val['user_id'], $edited, 'form-control ', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
            $from .= "</td>";
            $from .= "<td>";
            $from .= UI::createTextBox("pemeriksaan_tim[$k][nama_jabatan]", $val['nama_jabatan'], '', '', $edited, 'form-control', "readonly");
            // if ($ci->data['bidangpemeriksaanarr']) {
            //     $from .= "</td>";
            //     $from .= "<td>";
            //     $from .= UI::createSelect("pemeriksaan_tim[$k][id_bidang_pemeriksaan]", $ci->data['bidangpemeriksaanarr'], $val['id_bidang_pemeriksaan'], $edited, 'form-control ', "style='width:100%;'");
            // }
            if ($edited) {
                $from .= "</td>";
                $from .= UI::createTextHidden("pemeriksaan_tim[$k][id_pemeriksaan_tim]", $val['id_pemeriksaan_tim'], $edited);
                $from .= UI::createTextHidden("pemeriksaan_tim[$k][nama]", $val['nama'], $edited);
                $from .= UI::createTextHidden("pemeriksaan_tim[$k][id_jabatan]", $val['id_jabatan'], $edited);

                $from .= "<td style='position:relative; text-align:right'>";
            }

            return $from;
        };

        $from = "<table width='100%'>" . UI::AddFormTable('pemeriksaan_tim', $row['pemeriksaan_tim'],  $from, $edited, $this) . "</table>";
        echo UI::createFormGroup($from, $rules['pemeriksaan_tim[]'], "pemeriksaan_tim[]", "Auditor", false, 2);
    }
    ?>


    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from, null, null, null, false, 2);
    ?>
</div>