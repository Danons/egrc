<div class="row">
    <div class="col-sm-12">

        <?php
        $from = UI::createTextBox('nomor_surat', $row['nomor_surat'], '45', '45', $edited, 'form-control ', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["nomor_surat"], "nomor_surat", "Nomor Surat", false, 2);

        $from = UI::createTextBox('tanggal_surat', $row['tanggal_surat'], '45', '45', $edited, 'form-control datepicker', "style='width:100'");
        echo UI::createFormGroup($from, $rules["tanggal_surat"], "tanggal_surat", "Tanggal", false, 2);
        ?>

        <?php
        $from = UI::createTextBox('periode_pemeriksaan_mulai', $row['periode_pemeriksaan_mulai'], '10', '10', $edited, 'form-control datepicker', "style='width:48%; display:inline;'");
        $from .= "&nbsp;s/d&nbsp;" . UI::createTextBox('periode_pemeriksaan_selesai', $row['periode_pemeriksaan_selesai'], '10', '10', $edited, 'form-control datepicker', "style='width:48%; display:inline;'");
        echo UI::createFormGroup($from, $rules["periode_pemeriksaan_mulai"], "periode_pemeriksaan_mulai", "Periode Pemeriksaan", false, 2);
        ?>

        <?php
        $from = UI::createTextArea('deskripsi', $row['deskripsi'], '', '', $edited, 'form-control contents', "");
        echo UI::createFormGroup($from, $rules["deskripsi"], "deskripsi", "Deskripsi", false, 2);
        ?>

        <?php

        // dpr($row['petugas']);


        if (!$pimpinanarr[$row['id_penyusun']])
            $pimpinanarr[$row['id_penyusun']] = $row['nama_penyusun'];

        $from = UI::createSelect("id_penyusun", ['' => ''] + $pimpinanarr, $row['id_penyusun'], $edited, 'form-control ', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
        $from .= UI::createTextBox('nama_jabatan_penyusun', $row['nama_jabatan_penyusun'], '200', '100', $edited, 'form-control ', "style='width:100%' readonly");
        echo UI::createFormGroup($from, $rules["nama_jabatan_penyusun"], "nama_jabatan_penyusun", "Koordinator", false, 2);

        $from = UI::createSelect("id_pereview", ['' => ''] + $pelaksanaarr, $row['id_pereview'], $edited, 'form-control ', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
        $from .= UI::createTextBox('nama_jabatan_pereview', $row['nama_jabatan_pereview'], '200', '100', $edited, 'form-control ', "style='width:100%' readonly");
        echo UI::createFormGroup($from, $rules["nama_jabatan_pereview"], "nama_jabatan_pereview", "Pengendali Teknis", false, 2);

        $from = UI::createSelect("id_penanggung_jawab", ['' => ''] + $penanggungjawabarr, $row['id_penanggung_jawab'], $edited, 'form-control ', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
        $from .= UI::createTextBox('nama_jabatan_penanggung_jawab', $row['nama_jabatan_penanggung_jawab'], '200', '100', $edited, 'form-control ', "style='width:100%' readonly");
        echo UI::createFormGroup($from, $rules["nama_jabatan_penanggung_jawab"], "nama_jabatan_penanggung_jawab", "Ketua", false, 2);

        $no = 1;
        $from = function ($val = null, $edited, $k = 0, $ci) {

            if (!$ci->data['pelaksanaarr'][$val['user_id']])
                $ci->data['pelaksanaarr'][$val['user_id']] = $val['nama'];

            $from = null;
            $from .= "<td>";
            $from .= UI::createSelect("petugas[$k][user_id]", $ci->data['pelaksanaarr'], $val['user_id'], $edited, 'form-control ', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
            $from .= "</td>";
            $from .= "<td>";
            $from .= UI::createTextBox("petugas[$k][nama_jabatan]", $val['nama_jabatan'], '', '', $edited, 'form-control', "readonly");
            if ($edited) {
                $from .= "</td>";
                $from .= UI::createTextHidden("petugas[$k][id_petugas]", $val['id_petugas'], $edited);
                $from .= UI::createTextHidden("petugas[$k][nama]", $val['nama'], $edited);
                $from .= UI::createTextHidden("petugas[$k][id_jabatan]", $val['id_jabatan'], $edited);

                $from .= "<td style='position:relative; text-align:right'>";
            }

            return $from;
        };

        $from = "<table width='100%'>" . UI::AddFormTable('petugas', $row['petugas'], $from, $edited, $this) . "</table>";
        echo UI::createFormGroup($from, $rules['petugas[]'], "petugas[]", "Auditor", false, 2);

        ?>


        <?php
        // dpr($row['petugas']);
        $from = UI::showButtonMode("save", null, $edited);
        echo UI::createFormGroup($from, null, null, null, false, 2);
        ?>
    </div>
</div>