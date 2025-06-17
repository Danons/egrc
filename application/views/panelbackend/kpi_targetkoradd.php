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
                // $from = UI::createTextArea('nama', $row['nama'], '3', '100', $edited, $class = 'form-control ', "style='width:100%'");
                $from = UI::createSelect('nama', $kpiarr2, $row['nama'], $edited, $class = 'form-control ', "style='width:auto; max-width:100%;'  data-tags='true' onchange='goSubmit(\"set_value\")'");
                echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama", false, 2);
                ?>

                <?php
                $from = UI::createSelect('id_parent', $kpiarr, $row['id_parent'], $edited, $class = 'form-control ', "style='width:auto; max-width:100%;'");
                echo UI::createFormGroup($from, $rules["id_parent"], "id_parent", "Parent", false, 2);
                ?>

                <?php
                $from = UI::createTextBox('kode', $row['kode'], '225', '100', $edited, $class = 'form-control ', "style='width:100%'");
                echo UI::createFormGroup($from, $rules["kode"], "kode", "Kode", false, 2);
                ?>

                <?php
                $from = UI::createTextNumber('urutan', $row['urutan'], '225', '100', $edited, $class = 'form-control ', "style='width:100%'");
                echo UI::createFormGroup($from, $rules["urutan"], "urutan", "Urutan", false, 2);
                ?>

                <?php
                if (!$row['tahun'])
                    $row['tahun'] = date("Y");
                $from = UI::createTextNumber("tahun", $row['tahun'], '', '', $edited, 'form-control', "style='width:80px'");
                echo UI::createFormGroup($from, $rules["tahun"], "tahun", 'Tahun', false, 2);
                ?>

                <div style="list-style-type: none;margin: 0;padding: 0;overflow: hidden;" class="mt-5 mb-5">
                    <div style="float: right;">
                        <!-- <div class="nx-pr next" value="detail_kpi" current="kpi"> next</div> -->
                        <button class="nx-pr next" onclick="goSubmit('save')"> next</button>
                        <!-- <div class="next" value="detail_kpi" current="kpi" style="color: rgb(255, 255, 255); background-color: rgb(246, 78, 96); border-color: rgb(246, 78, 96); font-weight: 600 !important; text-transform: uppercase !important; padding-left: 2.25rem !important; padding-right: 2.25rem !important; padding-bottom: 1rem !important; padding-top: 1rem !important;"> next</div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    // $('.sl_kpi').css('background-color', 'red');
    $('.sl_kpi').addClass('active');


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
    // // $('#kpi').hide();
    // $('#detail_kpi').hide();
    // $('#unit_kerja').hide();
    // $('#review_submit').hide();
    // var next = '';
    // var current = '';
    // // tombol next
    // $('.next').click(function() {
    //     next = $(this).attr('value');
    //     current = $(this).attr('current');
    //     $('#' + next).show();
    //     $('#' + current).hide();
    //     $('.sl_' + next).css('background-color', 'red');
    // })
    // // tombol kembali
    // $('.pre').click(function() {
    //     next = $(this).attr('value');
    //     current = $(this).attr('current');
    //     $('#' + next).show();
    //     $('#' + current).hide();
    //     $('.sl_' + current).css('background-color', '');
    // })

    // // save
    // var halaman = '<?= $_SESSION[SESSION_APP][$this->page_ctrl]['halaman'] ?>';
    // if (halaman == '2') {
    //     $('#detail_kpi').show();
    //     $('#kpi').hide();
    //     $('.sl_korport').css('background-color', 'red');
    // }
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