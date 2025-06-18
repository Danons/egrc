<div class="row">


    <div style="border-bottom: 1px solid rgb(235, 237, 243) !important;">
        <div style="display: flex; -webkit-box-pack: center; justify-content: center; -webkit-box-align: center; align-items: center;">
            <ul class="pt-kor">
                <li class="pt-hrz">
                    <div class="pt-text sl_review_submit">
                        <div>icon flaticon-truck</div>
                        <div>Reviu dan Submit</div>
                    </div>
                </li>
                <li class="pt-hrz">
                    <div class="pt-arrow">
                        <div>panah</div>
                    </div>
                </li>
                <li class="pt-hrz sl_unit_kerja">
                    <div class="pt-text">
                        <div>icon flaticon-globe</div>
                        <div>Pilih Unit Kerja</div>
                    </div>
                </li>
                <li class="pt-hrz">
                    <div class="pt-arrow">
                        <div>panah</div>
                    </div>
                </li>
                <li class="pt-hrz sl_detail_kpi">
                    <div class="pt-text">
                        <div>icon flaticon-list</div>
                        <div>Detail KPI</div>
                    </div>
                </li>
                <li class="pt-hrz">
                    <div class="pt-arrow">
                        <div>panah</div>
                    </div>
                </li>
                <li class="pt-hrz sl_pengaturan_kpi">
                    <div class="pt-text">
                        <div>icon flaticon-bus-stop</div>
                        <div>pengaturan kpi</div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div style="-webkit-box-pack: center !important; justify-content: center !important; padding-right: 2.5rem !important; padding-left: 2.5rem !important; margin-bottom: 3.75rem !important; margin-top: 3.75rem !important; box-sizing: border-box;">
        <div style="-webkit-box-flex: 0; max-width: 70%; flex: 0 0 58.3333%; position: relative; width: 100%; padding-right: 12.5px; padding-left: 12.5px; margin:auto;"><!--align="center"-->
            <div id="pengaturan_kpi" name="pengaturan_kpi" class="col-sm-12">

                <?php
                $from = UI::createSelect('kpi', $kpiarr, $row['kpi'], $edited, 'form-control ', "");
                echo UI::createFormGroup($from, $rules["kpi"], "kpi", "KPI", false, 2);
                ?>
                <?php
                // $from = UI::createTextBox('tahun', $rowheader['tahun'], '225', '100', false, 'form-control ', "style='width:100%'");
                // echo UI::createFormGroup($from, $rules["tahun"], "tahun", "Tahun", false, 2);

                $from = UI::createSelect('tahun', $tahunarr, $row['tahun'], $edited, 'form-control ', "");
                echo UI::createFormGroup($from, $rules["tahun"], "tahun", "Tahun", false, 2);
                ?>

                <?php

                $from = UI::createSelect('jenis', $jenisarr, $row['jenis'], $edited, 'form-control ', "");
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
                // $from = UI::showButtonMode("save", null, $edited);
                // echo UI::createFormGroup($from, null, null, null, false, 2);
                ?>
                <div style="list-style-type: none;margin: 0;padding: 0;overflow: hidden;">
                    <div style="float: right;">
                        <div class="nx-pr next" value="detail_kpi" current="pengaturan_kpi"> next</div>
                        <!-- <button class="nx-pr next" value="detail_kpi" current="pengaturan_kpi" onclick="goSubmit('save')"> next</button> -->
                        <!-- <div class="next" value="detail_kpi" current="pengaturan_kpi" style="color: rgb(255, 255, 255); background-color: rgb(246, 78, 96); border-color: rgb(246, 78, 96); font-weight: 600 !important; text-transform: uppercase !important; padding-left: 2.25rem !important; padding-right: 2.25rem !important; padding-bottom: 1rem !important; padding-top: 1rem !important;"> next</div> -->
                    </div>
                </div>
            </div>

            <div id="detail_kpi" name="detail_kpi" class="col-sm-12">

                <?php
                $from = UI::createTextBox('kpi1', null, '225', '100', false, 'form-control ', "style='width:100%'");
                echo UI::createFormGroup($from, $rules["kpi1"], "kpi1", "KPI", true, 2);
                ?>

                <?php
                $from = UI::createTextBox('tahun1', $rowheader['tahun'], '225', '100', false, 'form-control ', "style='width:100%'");
                echo UI::createFormGroup($from, $rules["tahun"], "tahun", "Tahun", true, 2);
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

                $from = UI::createSelect('jenis1', $arr, $row['jenis'], false, 'form-control ', "onchange='goSubmit(\"set_value\")'");
                echo UI::createFormGroup($from, $rules["jenis"], "jenis", "Jenis", true, 2);

                if ($row['jenis'] == 'Direktorat1' && !$rowheader['is_bersama']) {
                    $from = UI::createSelect('id_dit_bid', $deptarr, $row['id_dit_bid'], false, 'form-control ');
                    echo UI::createFormGroup($from, $rules["id_dit_bid"], "id_dit_bid", "Direktorat", true, 2);
                }

                if ($row['jenis'] == 'Unit') {
                    $from = UI::createSelect('id_unit1', $unitarr, $row['id_unit'], false, 'form-control ');
                    echo UI::createFormGroup($from, $rules["id_unit"], "id_unit", "Unit", true, 2);
                }
                ?>

                <?php
                $from = UI::createTextNumber('bobot1', $row['bobot'], '10', '10', false, 'form-control ', "style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
                echo UI::createFormGroup($from, $rules["bobot"], "bobot", "Bobot", true, 2);
                ?>

                <?php
                $from = UI::createSelect('polarisasi1', ["Maximize" => "Maximize", "Minimize" => "Minimize", "Stabilize" => "Stabilize"], $row['polarisasi'], false, 'form-control ');
                echo UI::createFormGroup($from, $rules["polarisasi"], "polarisasi", "Polarisasi", true, 2);
                ?>

                <?php
                $from = UI::createTextNumber('target1', $row['target'], '10', '10', false, 'form-control ', "style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
                echo UI::createFormGroup($from, $rules["target"], "target", "Target", true, 2);
                ?>

                <?php
                $from = UI::createTextBox('satuan1', $row['satuan'], '225', '100', false, 'form-control ', "style='width:100%'");
                echo UI::createFormGroup($from, $rules["satuan"], "satuan", "Satuan", true, 2);
                ?>


                <?php
                if ($row['jenis'] == 'Unit') {
                    if ($rowheader['is_direktorat'] || $rowheader['is_bersama'] || $rowheader['is_korporat']) {
                        $from = UI::createCheckBox('is_pic1', 1, $row['is_pic'], '', $edited, '', "style='margin: 12px 0px;'");
                        echo UI::createFormGroup($from, $rules["is_pic"], "is_pic", "PIC Direktorat / Korporat", true, 2);
                    }
                }
                ?>

                <?php
                $from = UI::createTextArea('definisi1', $row['definisi'], '', '', false, $class = 'form-control contents', "");
                echo UI::createFormGroup($from, $rules["definisi"], "definisi", "Definisi", true, 2);
                ?>

                <?php
                $from = UI::createTextArea('tujuan1', $row['tujuan'], '', '', false, $class = 'form-control contents', "");
                echo UI::createFormGroup($from, $rules["tujuan"], "tujuan", "Tujuan", true, 2);
                ?>

                <?php
                $from = UI::createTextArea('formula1', $row['formula'], '', '', false, $class = 'form-control contents', "");
                echo UI::createFormGroup($from, $rules["formula"], "formula", "Formula", true, 2);
                ?>


                <div style="list-style-type: none;margin: 0;padding: 0;overflow: hidden;">
                    <div style="float: left;">
                        <div class="nx-pr pre" value="pengaturan_kpi" current="detail_kpi"> previous</div>
                    </div>

                    <div style="float: right;">
                        <div class="nx-pr next" value="unit_kerja" current="detail_kpi"> next</div>
                    </div>
                </div>
            </div>
            <div id="unit_kerja" name="unit_kerja" class="col-sm-12">

                <div style="list-style-type: none;margin: 0;padding: 0;overflow: hidden;">
                    <div style="float: left;">
                        <div class="nx-pr pre" value="detail_kpi" current="unit_kerja"> previous</div>
                    </div>

                    <div style="float: right;">
                        <div class="nx-pr next" value="review_submit" current="unit_kerja"> next</div>
                    </div>
                </div>
            </div>
            <div id="review_submit" name="review_submit" class="col-sm-12">

                <div style="list-style-type: none;margin: 0;padding: 0;overflow: hidden;">
                    <div style="float: left;">
                        <div class="nx-pr pre" value="unit_kerja" current="review_submit" style="background-color: #ff89ba;"> previous</div>
                    </div>

                    <div style="float: right;">
                        <!-- <button class="nx-pr" onclick="goSubmit('save')"> next</button> -->
                        <!-- <div class="next" value="unit_kerja" current="detail_kpi"> next</div> -->
                    </div>
                </div>

                <?php
                // $from = UI::showButtonMode("save", null, $edited);
                // echo UI::createFormGroup($from, null, null, null, false, 2);
                ?>
            </div>
        </div>
    </div>
</div>
<script>
    $('.sl_pengaturan_kpi').css('background-color', 'red');

    // $('#pengaturan_kpi').hide();
    $('#detail_kpi').hide();
    $('#unit_kerja').hide();
    $('#review_submit').hide();
    var next = '';
    var current = '';
    // tombol next
    $('.next').click(function() {
        next = $(this).attr('value');
        current = $(this).attr('current');
        $('#' + next).show();
        $('#' + current).hide();
        $('.sl_' + next).css('background-color', 'red');
    })
    // tombol kembali
    $('.pre').click(function() {
        next = $(this).attr('value');
        current = $(this).attr('current');
        $('#' + next).show();
        $('#' + current).hide();
        $('.sl_' + current).css('background-color', '');
    })

    // save
    var halaman = '<?= $_SESSION[SESSION_APP][$this->page_ctrl]['halaman'] ?>';
    if (halaman == '2') {
        $('#detail_kpi').show();
        $('#pengaturan_kpi').hide();
        $('.sl_detail_kpi').css('background-color', 'red');
    }
</script>
<style>
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
</style>