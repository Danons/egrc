<div class="row">

    <div class="col-12">
        <div class="d-flex align-items-center pt-kor">

            <div class="pt-hrz sl_kpi">
                <div class="pt-text">
                    <div class="pt-hrz-icons" onclick='open_kpi(<?= json_encode($row) ?>)'></div>
                    <div class="text-center pt-hrz-titles">KPI</div>
                </div>
            </div>
            <div class="pt-hrz pt-hrz-arrow">
                <div class="pt-arrow">
                    <span class="material-icons">chevron_right</span>
                </div>
            </div>
            <div class="pt-hrz sl_korport">
                <div class="pt-text">
                    <div class="pt-hrz-icons" onclick='open_kor(<?= json_encode($row) ?>)'></div>
                    <div class="text-center pt-hrz-titles">Korporat</div>
                </div>
            </div>
            <div class="pt-hrz pt-hrz-arrow">
                <div class="pt-arrow">
                    <span class="material-icons">chevron_right</span>
                </div>
            </div>
            <div class="pt-hrz sl_direktorat">
                <div class="pt-text">
                    <div class="pt-hrz-icons" onclick='open_dir(<?= json_encode($row) ?>)'></div>
                    <div class="text-center pt-hrz-titles">Direktorat</div>
                </div>
            </div>
            <div class="pt-hrz pt-hrz-arrow">
                <div class="pt-arrow">
                    <span class="material-icons">chevron_right</span>
                </div>
            </div>
            <div class="pt-hrz sl_unit">
                <div class="pt-text">
                    <div class="pt-hrz-icons" onclick='open_unit(<?= json_encode($row) ?>)'></div>
                    <div class="text-center pt-hrz-titles">Unit</div>
                </div>
            </div>

        </div>
    </div>

    <div>
        <div class="row justify-content-center">
            <div id="kpi" name="kpi" class="col-lg-8">

                <?php
                $from = UI::createCheckBox('is_korporat', 1, $row['is_korporat'], "KPI Korporat", $edited, $class = 'iCheck-helper ', "onclick='goSubmit(\"set_value\")'");
                echo UI::createFormGroup($from, $rules["is_korporat"], "is_korporat", false, 2);
                ?>

                <?php
                if ($row['is_korporat']) {
                    $from = UI::createRadio("jenis_realisasi_korporat", $jenisrealisasiarr, $row['jenis_realisasi_korporat'], $edited, true);
                    echo UI::createFormGroup($from, $rules["jenis_realisasi_korporat"], "jenis_realisasi_korporat", "Perhitungan Korporat", false, 2);


                    $from = UI::createTextNumber('bobot', $row['bobot'], '10', '10', $edited, 'form-control ', "style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
                    echo UI::createFormGroup($from, $rules["bobot"], "bobot", "Bobot", false, 2);


                    $from = UI::createSelect('polarisasi', ["Maximize" => "Maximize", "Minimize" => "Minimize", "Stabilize" => "Stabilize"], $row['polarisasi'], $edited, 'form-control ');
                    echo UI::createFormGroup($from, $rules["polarisasi"], "polarisasi", "Polarisasi", false, 2);


                    $from = UI::createTextNumber('target', $row['target'], '10', '10', $edited, 'form-control ', "style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
                    echo UI::createFormGroup($from, $rules["target"], "target", "Target", false, 2);


                    $from = UI::createTextBox('satuan', $row['satuan'], '225', '100', $edited, 'form-control ', "style='width:100%'");
                    echo UI::createFormGroup($from, $rules["satuan"], "satuan", "Satuan", false, 2);


                    if ($row['jenis'] == 'Unit') {
                        if ($rowheader['is_direktorat'] || $rowheader['is_bersama'] || $rowheader['is_korporat']) {
                            $from = UI::createCheckBox('is_pic', 1, $row['is_pic'], '', $edited, '', "style='margin: 12px 0px;'");
                            echo UI::createFormGroup($from, $rules["is_pic"], "is_pic", "PIC Direktorat / Korporat", false, 2);
                        }
                    }


                    $from = UI::createTextArea('definisi', $row['definisi'], '', '', $edited, $class = 'form-control contents', "");
                    echo UI::createFormGroup($from, $rules["definisi"], "definisi", "Definisi", false, 2);


                    $from = UI::createTextArea('tujuan', $row['tujuan'], '', '', $edited, $class = 'form-control contents', "");
                    echo UI::createFormGroup($from, $rules["tujuan"], "tujuan", "Tujuan", false, 2);


                    $from = UI::createTextArea('formula', $row['formula'], '', '', $edited, $class = 'form-control contents', "");
                    echo UI::createFormGroup($from, $rules["formula"], "formula", "Formula", false, 2);
                }
                ?>

                <div style="list-style-type: none;margin: 0;padding: 0;overflow: hidden;" class="mt-5 mb-5">

                    <div style="float: left;">
                        <!-- <div class="nx-pr pre" onclick="previos('<?= base_url() . 'panelbackend/kpi_target_kor/add' ?>')"> previous</div> -->
                        <div class="nx-pr pre" onclick="previos('<?= base_url() . 'panelbackend/kpi_target_kor/add/0/' . $id_kpi . '/' . $tahun ?>')"> previous</div>
                    </div>
                    <div style="float: right;">
                        <button class="nx-pr next" onclick="goSubmit('save')"> next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // $('.sl_kpi').css('background-color', 'red');
    // $('.sl_korport').css('background-color', 'red');
    $('.sl_kpi').addClass('active');
    $('.sl_korport').addClass('active');

    function open_kpi(data) {
        window.location = '<?= base_url() . "panelbackend/kpi_target_kor/add/0/" ?>' + data.id_kpi + '/' + data.tahun;
    }

    function open_kor(data) {
        window.location = '<?= base_url() . "panelbackend/kpi_target_kor/add/2/" ?>' + data.id_kpi + '/' + data.tahun;
    }

    function open_dir(data) {
        window.location = '<?= base_url() . "panelbackend/kpi_target_kor/add/3/" ?>' + data.id_kpi + '/' + data.tahun;
    }

    function open_unit(data) {
        window.location = '<?= base_url() . "panelbackend/kpi_target_kor/add/4/" ?>' + data.id_kpi + '/' + data.tahun;
    }
</script>
<!-- <style>
    .pt-kor {
        list-style-type: none;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }

    .pt-hrz {
        float: right;
    }

    .pt-text {
        display: block;
        color: black;
        text-align: center;
        padding: 10% 80px;
        text-decoration: none;
    }

    .pt-arrow {
        display: block;
        color: black;
        text-align: center;
        padding: 10% 2px;
        text-decoration: none;
    }

    .nx-pr {
        border-radius: 15px;
        color: rgb(255, 255, 255);
        background-color: rgb(246, 78, 96);
        border-color: rgb(246, 78, 96);
        font-weight: 600 !important;
        text-transform: uppercase !important;
        padding-left: 2.25rem !important;
        padding-right: 2.25rem !important;
        padding-bottom: 1rem !important;
        padding-top: 1rem !important;
    }

    .nx-pr:hover {
        background-color: #dc3545;
    }
</style> -->