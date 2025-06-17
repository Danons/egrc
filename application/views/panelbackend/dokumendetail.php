<div class="row">
    <div class="col-sm-12">

        <?php
        /*$from = UI::createCheckBox('is_aktif', 1, $row['is_aktif'], "Aktif", $edited, $class = 'iCheck-helper ', "");
        echo UI::createFormGroup($from, $rules["is_aktif"], "is_aktif");
        ?>

        <?php
        $from = UI::createCheckBox('is_approved', 1, $row['is_approved'], "Approved", $edited, $class = 'iCheck-helper ', "");
        echo UI::createFormGroup($from, $rules["is_approved"], "is_approved");*/
        ?>

        <?php
        // $from = UI::createTextBox('nomor_dokumen', $row['nomor_dokumen'], '45', '45', $edited, $class = 'form-control ', "style='width:100%'");
        // echo UI::createFormGroup($from, $rules["nomor_dokumen"], "nomor_dokumen", "Nomor Dokumen");
        ?>

        <?php
        $from = UI::createTextArea('nama', $row['nama'], '', '', $edited, $class = 'form-control ', "");
        echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama Dokumen", false, 2);
        ?>



        <?php
        $from = UI::createSelect('id_unit', $mtunitarr, $row['id_unit'], $edited, $class = 'form-control ', "style='width:100%;'");
        echo UI::createFormGroup($from, $rules["id_unit"], "id_unit", "Unit", false, 2);
        ?>

        <?php
        $from = UI::createSelect('id_jabatan', $jabatanarr, $row['id_jabatan'], $edited, $class = 'form-control ', "style='width:100%;'");
        echo UI::createFormGroup($from, $rules["id_jabatan"], "id_jabatan", "PIC", false, 2);
        ?>

        <?php
        $from = UI::createSelectMultiple('id_jabatanarr[]', $jabatanarr, $row['id_jabatanarr'], $edited, $class = 'form-control id_jabatanarr', "style='width:100%;'");
        echo UI::createFormGroup($from, $rules["id_jabatanarr[]"], "id_jabatanarr[]", "Pengendali", false, 2);
        // echo UI::createFormGroup($from, $rules["id_jabatanarr[]"], "id_jabatanarr[]", "Pengendali " . ($edited ? "<small><a href='javascript:void(0);' onclick='$(\".id_jabatanarr\").val(" . json_encode(array_keys($mtunitarr)) . ").change();'>Semua</a></small>" : null));
        ?>

        <?php
        $from = UI::createSelectMultiple('id_kriteriaarr[]', $kriteriaarr, $row['id_kriteriaarr'], $edited, $class = 'form-control id_jabatanarr', "style='width:100%;'");
        echo UI::createFormGroup($from, $rules["id_kriteriaarr[]"], "id_kriteriaarr[]", "Paramater", false, 2);
        // echo UI::createFormGroup($from, $rules["id_jabatanarr[]"], "id_jabatanarr[]", "Pengendali " . ($edited ? "<small><a href='javascript:void(0);' onclick='$(\".id_jabatanarr\").val(" . json_encode(array_keys($mtunitarr)) . ").change();'>Semua</a></small>" : null));
        ?>

        <?php
        // $from = UI::createSelect('id_jenis_dokumen', $mtjenisdokumenarr, $row['id_jenis_dokumen'], $edited, $class = 'form-control ', "style='width:100%;'");
        // echo UI::createFormGroup($from, $rules["id_jenis_dokumen"], "id_jenis_dokumen", "Jenis Dokumen");
        ?>

        <?php
        // $from = UI::createTextBox('tgl_upload', $row['tgl_upload'], '10', '10', $edited, $class = 'form-control datepicker', "style='width:190px'");
        // echo UI::createFormGroup($from, $rules["tgl_upload"], "tgl_upload", "Tgl. Upload");
        ?>

        <?php
        // $from = UI::createTextBox('tgl_disahkan', $row['tgl_disahkan'], '10', '10', $edited, $class = 'form-control datepicker', "style='width:190px'");
        // echo UI::createFormGroup($from, $rules["tgl_disahkan"], "tgl_disahkan", "Tgl. Disahkan");
        ?>

        <?php
        // $from = UI::createUploadMultiple("file", $row['files'], $page_ctrl, $edited);
        $from = "<table class='table' style='margin-top:-8px'><tr>
            <th>Nama Dokumen</th>
            <th>Status</th>
            <th>Catatan Ajuan</th>
            <th>Catatan Revisi</th>
            <th>Area Of Improvement</th>
        </tr>";
        foreach ($rowversi as $i => $r) {
            $ra = $r;
            $from .= "<tr>";
            $from .= "<td>";
            $arr = [];
            foreach ($r['rowfiles']['name'] as $if => $client_name) {
                $id_dokumen_files = $r['rowfiles']['id'][$if];
                $str = "";
                $str .= "<a href='" . site_url($page_ctrl . "/open_file/$id_dokumen_files") . "' target='_BLANK'>";
                $str .= $client_name;
                $str .= "</a>";
                $arr[] = $str;
            }
            $from .= implode("<br/>", $arr);
            $from .= "</td>";
            $from .= "<td>";
            if (count($rowversi) - 1 == $i && $this->access_role['add'] && $edited) {
                if ($row['status'][$r['id_dokumen_versi']])
                    $r['status'] = $row['status'][$r['id_dokumen_versi']];
                $from .= UI::createSelect("status[" . $r['id_dokumen_versi'] . "]", ["Draft" => "Draft", "Setujui" => "Setujui", "Revisi" => "Revisi"], $r['status'], $edited, "form-control", "onchange='goSubmit(\"set_value\")' style='width:120px'");
            } else {
                $from .= $r['status'];
            }
            $from .= "</td>";
            $from .= "<td>";
            $from .= $r['catatan_ajuan'];
            $from .= "</td>";
            $from .= "<td>";

            if (count($rowversi) - 1 == $i && $this->access_role['add'] && $edited && $r['status'] == 'Revisi') {
                if ($row['catatan_revisi'][$r['id_dokumen_versi']])
                    $r['catatan_revisi'] = $row['catatan_revisi'][$r['id_dokumen_versi']];
                $from .= UI::createTextArea("catatan_revisi[" . $r['id_dokumen_versi'] . "]", $r['catatan_revisi'], '', '', $edited);
            } else {
                $from .= $r['catatan_revisi'];
            }
            $from .= "</td>";
            $from .= "<td>";
            $from .= $r['oai'];
            $from .= "</td>";
            $from .= "</tr>";
        }
        if ($this->access_role['upload'] && $edited && (($ra['status'] == 'Setujui' && $ra['oai']) || !$ra || $ra['status'] == 'Revisi')) {
            $from .= "<tr>";
            $from .= "<td>";
            if (!$row['jenis'])
                $row['jenis'] = 'file';

            $from .= UI::createRadio("jenis", ["file" => "File", "folder" => "Folder"], $row['jenis'], $edited, false, "form-control", "onclick='goSubmit(\"set_value\")'");

            if ($row['jenis'] == 'folder')
                $from .= UI::createUploadDirectory("files", $row['files'], $page_ctrl, $edited);
            else
                $from .= UI::createUploadMultiple("files", $row['files'], $page_ctrl, $edited);

            $from .= "</td>";
            $from .= "<td>";
            $from .= "Draft";
            $from .= "</td>";
            $from .= "<td>";
            $from .= UI::createTextArea("catatan_ajuan", $row['catatan_ajuan'], '', '', $edited);
            $from .= "</td>";
            $from .= "<td>";
            // $from .= UI::createTextArea("catatan_revisi", $row['catatan_revisi'], '', '', $edited);
            $from .= "</td>";
            $from .= "<td>";
            $from .= "</td>";
            $from .= "</tr>";
        }
        $from .= "</table>";
        echo UI::createFormGroup($from, $rules["files"], "files", "File Dokumen", false, 2);
        ?>

        <?php
        // $from = UI::createTextArea('keterangan', $row['keterangan'], '', '', $edited, $class = 'form-control contents', "");
        // echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "Keterangan");
        ?>

        <?php
        $from = UI::showButtonMode("save", null, $edited);
        echo UI::createFormGroup($from, null, null, null, false, 2);
        ?>
    </div>
</div>