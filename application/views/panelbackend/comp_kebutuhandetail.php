<div class="row">
    <div class="col-sm-12">

        <?php
        $from = UI::createSelect('id_dokumen', $dokumenarr, $row['id_dokumen'], $edited, $class = 'form-control ', "style='width:auto; max-width:100%;'");
        echo UI::createFormGroup($from, $rules["id_dokumen"], "id_dokumen", "SOP", false, 2);
        ?>


        <?php
        $from = UI::createTextArea('nama', $row['nama'], '', '', $edited, $class = 'form-control contents', "");
        echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama Kebutuhan", false, 2);
        ?>

        <?php
        $from = UI::createSelect('id_interval', $mtintervalarr, $row['id_interval'], $edited, $class = 'form-control ', "style='width:auto; max-width:100%;' onchange='goSubmit(\"set_value\")'");
        echo UI::createFormGroup($from, $rules["id_interval"], "id_interval", "Interval", false, 2);
        ?>


        <?php
        $from = UI::createCheckBox('is_file', 1, $row['is_file'], "File", $edited, $class = 'iCheck-helper ', "onclick='goSubmit(\"set_value\")'");
        echo UI::createFormGroup($from, $rules["is_file"], "is_file", null, false, 2);
        ?>

        <?php
        if (!$row['is_file']) {
            $from = UI::createTextArea('url', $row['url'], '', '', $edited, $class = 'form-control contents', "");
            $from .= "<small><i>
            <b>Variabel : </b><br/>
            - tw : Triwulan<br/>
            - thn : Tahun<br/>
            - smt : Semester<br/>
            - bln : Bulan<br/>
            <br/>
            Contoh : http://manrisk.id/laporan_triwulan?tw={tw}&thn={thn}<br/>
            </i></small>";
            echo UI::createFormGroup($from, $rules["url"], "url", "URL", false, 2);

            if (!$row['konversi_bulan'])
                $periodes = 0;
            else
                $periodes = (int)(12 / $row['konversi_bulan']);

            if ($periodes > 1) {
                $from = "<table><tr><th>Label</th><th>Kode</th></tr>";

                for ($i = 1; $i <= $periodes; $i++) {
                    if ($periodes == 1)
                        $i = date("Y");

                    $from .= "<tr><td>" . $mtintervalarr[$row['id_interval']] . " " . $i . "</td><td>" . UI::createTextBox("mapping[$i]", $row['mapping'][$i], '', '', $edited, 'form-control', "style='width:100px'") . "</td></tr>";
                }
                $from .= "</table>";
                echo UI::createFormGroup($from, $rules["mapping"], "mapping", "Mapping Variable", false, 2);
            }
        }
        ?>

        <?php
        $from = UI::showButtonMode("save", null, $edited);
        echo UI::createFormGroup($from, null, null, null, false, 2);
        ?>
    </div>
</div>