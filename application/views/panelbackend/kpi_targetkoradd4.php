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
                if (!$masing2) {
                    $from = UI::createRadio("jenis_realisasi", $jenisrealisasiarr, $row['jenis_realisasi'], $edited, true, "form-control", "onchange='goSubmit(\"set_value\")'");
                    echo UI::createFormGroup($from, $rules["jenis_realisasi"], "jenis_realisasi", 'Perhitungan Unit', false, 2);
                }
                ?>

                <?php if ($row['jenis_realisasi'] && !$tutup_list) { ?>
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 ">
                        <div class="new-content-title">
                            <h4 class="h4">Direktorat</h4>
                        </div>
                        <!-- <div class="btn-toolbar mb-2 mb-md-0" onchange="goSubmit('set_value')"> -->
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <?php $url = $row['jenis_realisasi'] ?>
                            <a href="<?= site_url("$this->page_ctrl/add/4/$id_kpi/$tahun/12") ?>" class="btn btn-sm btn-primary">Tambah Baru</a>
                            <!-- <a href="" class="btn btn-sm btn-primary">Tambah Baru</a> -->
                        </div>
                    </div>
                    <table class="table treetable">
                        <thead>
                            <tr>
                                <th>Unit</th>
                                <th style="text-align: right;">Bobot</th>
                                <th>Polarisasi</th>
                                <th style="text-align: right;">Target</th>
                                <th>Satuan</th>
                                <?php
                                if (!($row['is_direktorat'] || $row['is_bersama'] || $row['is_korporat'])) { ?>
                                    <th>PIC</th>
                                <?php } ?>
                                <!-- <th></th> -->
                            </tr>
                        </thead>
                        <?php if ($rowstarget)
                            foreach ($rowstarget as $r) {
                                echo "<tr data-tt-id='" . $r['id'] . "' data-tt-parent-id='" . $r['id_parent'] . "'>";
                        ?>
                            <!-- <td><a href="<?= site_url("panelbackend/kpi_target/detail/$r[id_kpi]/$r[tahun]/$r[id_kpi_target]") ?>"><?= $r['nama'] ?></a></td> -->
                            <td><a href="javascript:void(0)"><?= $r['nama'] ?></a></td>
                            <td style="text-align: right;"><?= $r['bobot'] ?></td>
                            <td><?= $r['polarisasi'] ?></td>
                            <td style="text-align: right;"><?= rupiah($r['target']) ?></td>
                            <td><?= $r['satuan'] ?></td>
                            <?php
                                if (!($row['is_direktorat'] || $row['is_bersama'] || $row['is_korporat'])) { ?>
                                <td><?= ["0" => "Tidak", "1" => "Ya"][$r['is_pic']] ?></td>
                            <?php } ?>

                            <td style="text-align:right;width:1px">
                                <div class="dropdown" style="display:inline">
                                    <a href="javascript:void(0)" class="dropdown" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#0052cc;padding: 5px;line-height:1.5;display:inline-block;">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenu2">
                                        <li><a href="javascript:void(0)" class=" dropdown-item " onclick="goEdit('<?= $r['id_kpi'] ?>','<?= $r['tahun'] ?>','<?= $url ?>','<?= $r['id_kpi_target'] ?>')"><i class="bi bi-pencil"></i> Edit</a> </li>
                                        <!-- <li><a href="javascript:void(0)" class=" dropdown-item " onclick="goDelete('<?= $row[$pk] ?>')"><i class="bi bi-trash"></i> Delete</a> </li> -->
                                    </ul>
                                </div>
                            </td>
                            <!-- <td>
                                <?= UI::startMenu() ?>
                                <li><a class=" dropdown-item " href="<?= site_url("panelbackend/kpi_target/edit/$row[id_kpi]/$r[tahun]/$r[id_kpi_target]") ?>">Edit</a></li>
                                <li><a class=" dropdown-item " href="<?= site_url("panelbackend/kpi_target/delete/$row[id_kpi]/$r[tahun]/$r[id_kpi_target]") ?>">Delete</a></li>
                                <?= UI::closeMenu() ?>
                            </td> -->
                            </tr>
                        <?php } ?>
                    </table>
                <?php } ?>
                <?php

                if ($masing2) {

                    $from = UI::createSelect('id_unit', $unitarr, $row['id_unit'], $edited, 'form-control ');
                    echo UI::createFormGroup($from, $rules["id_unit"], "id_unit", "Unit", false, 2);


                    $from = UI::createTextNumber('bobot', $row['bobot'], '10', '10', $edited, 'form-control ', "style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
                    echo UI::createFormGroup($from, $rules["bobot"], "bobot", "Bobot", false, 2);


                    $from = UI::createSelect('polarisasi', ["Maximize" => "Maximize", "Minimize" => "Minimize", "Stabilize" => "Stabilize"], $row['polarisasi'], $edited, 'form-control ');
                    echo UI::createFormGroup($from, $rules["polarisasi"], "polarisasi", "Polarisasi", false, 2);


                    $from = UI::createTextNumber('target', $row['target'], '10', '10', $edited, 'form-control ', "style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
                    echo UI::createFormGroup($from, $rules["target"], "target", "Target", false, 2);


                    $from = UI::createTextBox('satuan', $row['satuan'], '225', '100', $edited, 'form-control ', "style='width:100%'");
                    echo UI::createFormGroup($from, $rules["satuan"], "satuan", "Satuan", false, 2);


                    $from = UI::createCheckBox('is_pic', 1, $row['is_pic'], '', $edited, '', "style='margin: 12px 0px;'");
                    echo UI::createFormGroup($from, $rules["is_pic"], "is_pic", "PIC Direktorat / Korporat", false, 2);


                    $from = UI::createTextArea('definisi', $row['definisi'], '', '', $edited, $class = 'form-control contents', "");
                    echo UI::createFormGroup($from, $rules["definisi"], "definisi", "Definisi", false, 2);


                    $from = UI::createTextArea('tujuan', $row['tujuan'], '', '', $edited, $class = 'form-control contents', "");
                    echo UI::createFormGroup($from, $rules["tujuan"], "tujuan", "Tujuan", false, 2);


                    $from = UI::createTextArea('formula', $row['formula'], '', '', $edited, $class = 'form-control contents', "");
                    echo UI::createFormGroup($from, $rules["formula"], "formula", "Formula", false, 2);

                    if ($masing2) {
                        $from = UI::showButtonMode("save", null, $edited);
                        echo UI::createFormGroup($from, null, null, null, false, 2);
                    }
                }
                ?>

                <?php if (!$masing2) { ?>
                    <div style="list-style-type: none;margin: 0;padding: 0;overflow: hidden;" class="mt-5 mb-5">

                        <div style="float: left;">
                            <div class="nx-pr pre" onclick="previos('<?= base_url() . 'panelbackend/kpi_target_kor/add/3/' . $id_kpi . '/' . $tahun ?>')"> previous</div>
                        </div>
                        <div style="float: right;">
                            <!-- <div class="nx-pr next" value="detail_kpi" current="kpi"> next</div> -->
                            <button class="nx-pr next" onclick="goSubmit('selesai')"> selesai</button>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script>
    // $('.sl_kpi').css('background-color', 'red');
    // $('.sl_korport').css('background-color', 'red');
    // $('.sl_direktorat').css('background-color', 'red');
    // $('.sl_unit').css('background-color', 'red');
    $('.sl_kpi').addClass('active');
    $('.sl_korport').addClass('active');
    $('.sl_direktorat').addClass('active');
    $('.sl_unit').addClass('active');

    function goEdit(id, tahun, url, id_kpi_target) {
        window.location = '<?= base_url() . "panelbackend/kpi_target_kor/add/4/" ?>' + id + '/' + tahun + '/' + 12 + '/' + id_kpi_target;
    }

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