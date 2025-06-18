<div class="row">
    <div class="col-sm-12">

        <?php
        $from = UI::createTextBox('kpi', $rowheader['nama'], '225', '100', false, 'form-control ', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["kpi"], "kpi", "KPI", false, 2);
        ?>
        <?php
        $from = UI::createTextBox('tahun', $rowheader['tahun'], '225', '100', false, 'form-control ', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["tahun"], "tahun", "Tahun", false, 2);
        ?>

        <?php
        $arr = ['Unit' => 'Unit'];
        if ($rowheader['is_direktorat'] || $rowheader['is_bersama']) {
            $arr['Direktorat'] = "Direktorat";
        }
        if ($rowheader['is_korporat']) {
            $arr['Korporat'] = "Korporat";
        }

        if (!$row['jenis'])
            $row['jenis'] = array_keys($arr)[0];

        $from = UI::createSelect('jenis', $arr, $row['jenis'], $edited, 'form-control ', "onchange='goSubmit(\"set_value\")'");
        echo UI::createFormGroup($from, $rules["jenis"], "jenis", "Jenis", false, 2);

        if ($row['jenis'] == 'Direktorat' && !$rowheader['is_bersama']) {
            $from = UI::createSelect('id_dit_bid', $deptarr, $row['id_dit_bid'], $edited, 'form-control ');
            echo UI::createFormGroup($from, $rules["id_dit_bid"], "id_dit_bid", "Direktorat", false, 2);
        }

        if ($row['jenis'] == 'Unit') {
            $from = UI::createSelect('id_unit', $unitarr, $row['id_unit'], $edited, 'form-control ');
            echo UI::createFormGroup($from, $rules["id_unit"], "id_unit", "Unit", false, 2);
        }
        ?>

        <?php
        $from = UI::createTextNumber('bobot', $row['bobot'], '10', '10', $edited, 'form-control ', "style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
        echo UI::createFormGroup($from, $rules["bobot"], "bobot", "Bobot", false, 2);
        ?>

        <?php
        $from = UI::createSelect('polarisasi', ["Maximize" => "Maximize", "Minimize" => "Minimize", "Stabilize" => "Stabilize"], $row['polarisasi'], $edited, 'form-control ');
        echo UI::createFormGroup($from, $rules["polarisasi"], "polarisasi", "Polarisasi", false, 2);
        ?>

        <?php
        $from = UI::createTextNumber('target', $row['target'], '10', '10', $edited, 'form-control ', "style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
        echo UI::createFormGroup($from, $rules["target"], "target", "Target", false, 2);
        ?>

        <?php
        $from = UI::createTextBox('satuan', $row['satuan'], '225', '100', $edited, 'form-control ', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["satuan"], "satuan", "Satuan", false, 2);
        ?>


        <?php
        if ($row['jenis'] == 'Unit') {
            if ($rowheader['is_direktorat'] || $rowheader['is_bersama'] || $rowheader['is_korporat']) {
                $from = UI::createCheckBox('is_pic', 1, $row['is_pic'], '', $edited, '', "style='margin: 12px 0px;'");
                echo UI::createFormGroup($from, $rules["is_pic"], "is_pic", "PIC Direktorat / Korporat", false, 2);
            }
        }
        ?>

        <?php
        $from = UI::createTextArea('definisi', $row['definisi'], '', '', $edited, $class = 'form-control contents', "");
        echo UI::createFormGroup($from, $rules["definisi"], "definisi", "Definisi", false, 2);
        ?>

        <?php
        $from = UI::createTextArea('tujuan', $row['tujuan'], '', '', $edited, $class = 'form-control contents', "");
        echo UI::createFormGroup($from, $rules["tujuan"], "tujuan", "Tujuan", false, 2);
        ?>

        <?php
        $from = UI::createTextArea('formula', $row['formula'], '', '', $edited, $class = 'form-control contents', "");
        echo UI::createFormGroup($from, $rules["formula"], "formula", "Formula", false, 2);
        ?>

        <?php
        $from = UI::showButtonMode("save", null, $edited);
        echo UI::createFormGroup($from, null, null, null, false, 2);
        ?>
    </div>
</div>