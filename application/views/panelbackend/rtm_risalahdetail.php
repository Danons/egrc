<div class="row">
    <div class="col-sm-12">
        <b style="display: flex;">
            RTM KE &nbsp;&nbsp;<?= UI::createSelect('id_rtm', $rtmarr, $row['id_rtm'], true, '', "style='width:100px;' onchange='goSubmit(\"set_value\")'"); ?>
        </b>
        <br />
        <div class="alert alert-info" role="alert">
            <b>RAPAT TINJAUAN MANAJEMEN : <?= $rtmarr[$rtm['id_rtm']] ?> </b><br />
            <?= $rtm['tingkat'] ?><br />
            PADA <?= $rtm['periode'] ?> TAHUN <?= $rtm['tahun'] ?>
        </div>
    </div>

</div>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
            URAIAN PERMASALAHAN
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">
            RENCANA PENYELESAIAN
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">
            EVALUASI PROGRESS TINDAK LANJUT
        </button>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

        <div class="row">
            <div class="col-sm-12">

                <?php
                $from = UI::createSelect('id_jenis_rtm_parent', $mtjenisrtmarr, $row['id_jenis_rtm_parent'], $edited, $class = 'form-control ', "style='width:auto; max-width:100%;' onchange='goSubmit(\"set_value\")'");
                echo UI::createFormGroup($from, $rules["id_jenis_rtm_parent"], "id_jenis_rtm_parent", "Jenis RTM", false, 2);
                ?>

                <?php
                if ($mtjenisrtmarrsub && count($mtjenisrtmarrsub) > 1) {
                    $from = UI::createSelect('id_jenis_rtm', $mtjenisrtmarrsub, $row['id_jenis_rtm'], $edited, $class = 'form-control ', "style='width:auto; max-width:100%;'");
                    echo UI::createFormGroup($from, $rules["id_jenis_rtm"], "id_jenis_rtm", "Sub Jenis RTM", false, 2);
                }
                ?>

                <?php
                $from = UI::createTextArea('uraian', $row['uraian'], '', '', $edited, $class = 'form-control contents', "");
                echo UI::createFormGroup($from, $rules["uraian"], "uraian", "Uraian Permasalahan", false, 2);
                ?>

                <?php
                $from = UI::createTextArea('analisis', $row['analisis'], '', '', $edited, $class = 'form-control contents', "");
                echo UI::createFormGroup($from, $rules["analisis"], "analisis", "Analisis Penyebab", false, 2);
                ?>

                <?php
                $from = UI::showButtonMode("save", null, $edited);
                echo UI::createFormGroup($from, null, null, null, false, 2);;
                ?>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

        <div class="row">
            <div class="col-sm-12">

                <?php
                $from = UI::createTextArea('uraian_rencana', $row['uraian_rencana'], '', '', $edited, $class = 'form-control contents', "");
                echo UI::createFormGroup($from, $rules["uraian_rencana"], "uraian_rencana", "Uraian Rencana", false, 2);
                ?>

                <?php
                $from = UI::createTextArea('uraian_target', $row['uraian_target'], '', '', $edited, $class = 'form-control contents', "");
                echo UI::createFormGroup($from, $rules["uraian_target"], "uraian_target", "Target Waktu", false, 2);
                ?>

                <?php
                $from = UI::createSelectMultiple('id_unit[]', $unitarr, $row['id_unit'], $edited, $class = 'form-control id_unit', "style='width:100%;'");
                echo UI::createFormGroup($from, $rules["id_unit[]"], "id_unit[]", "PIC " . ($edited ? "<small><a href='javascript:void(0);' onclick='$(\".id_unit\").val(" . json_encode(array_keys($unitarr)) . ").change();'>Semua</a></small>" : null), false, 2);
                ?>

                <?php
                $from = UI::createTextArea('keterangan_pic', $row['keterangan_pic'], '', '', $edited, $class = 'form-control contents', "");
                echo UI::createFormGroup($from, $rules["keterangan_pic"], "keterangan_pic", "Keterangan PIC", false, 2);
                ?>

                <?php
                $from = UI::showButtonMode("save", null, $edited);
                echo UI::createFormGroup($from, null, null, null, false, 2);
                ?>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/plugins/chartjs/Chart.min.js') ?>"></script>

    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
        <div class="row">
            <div class="col-sm-12">


                <?php
                // dpr($row['progress'], 1);
                // $from = UI::createCheckBox('is_grafik', 1, $row['is_grafik'], "Grafik", $edited, $class = 'iCheck-helper ', "");
                // echo UI::createFormGroup($from, $rules["is_grafik"], "is_grafik", false, false, 2);

                $no = 1;
                $from = function ($val = null, $edited, $k = 0, $ci) {

                    $from = null;
                    $from .= "<td>";
                    $from .= UI::createTextBox("progress[$k][target]", $val['target'], '', '', $edited, 'form-control rupiah');
                    $from .= "</td>";
                    $from .= "<td>";
                    $from .= UI::createTextBox("progress[$k][realisasi]", $val['realisasi'], '', '', $edited, 'form-control rupiah');
                    $from .= "</td>";
                    $from .= "<td>";
                    $from .= UI::createTextBox("progress[$k][competitor]", $val['competitor'], '', '', $edited, 'form-control rupiah');
                    $from .= "</td>";
                    $from .= "<td>";
                    $from .= UI::createTextNumber("progress[$k][tahun]", $val['tahun'], '', '', $edited, 'form-control');

                    if ($edited) {
                        $from .= "</td>";
                        $from .= UI::createTextHidden("progress[$k][id_rtm_progress]", $val['id_rtm_progress'], $edited);

                        $from .= "<td style='position:relative; text-align:right'>";
                    }

                    return $from;
                };

                $from = (($row['is_grafik'] && !$edited) ? '<canvas id="myChart" style="height:100px;width:100%"></canvas>' : '') . "<table width='100%'><thead><tr><th>Target</th><th>Realisasi</th><th>Kompetitor</th><th>Tahun</th></tr></thead>" . UI::AddFormTable('progress', $row['progress'],  $from, $edited, $this) . "</table>";
                echo UI::createFormGroup($from, $rules['progress[]'], "progress[]", "Tambah Grafik ?" . UI::createCheckBox('is_grafik', 1, $row['is_grafik'], "jika ada", $edited, $class = 'iCheck-helper ', ""), false, 2);
                ?>

                <?php if ($row['is_grafik'] && !$edited) { ?>
                    <script>
                        const myChart = new Chart(
                            document.getElementById('myChart'), {
                                type: 'line',
                                data: {
                                    labels: <?= json_encode(array_column($row['progress'], 'tahun')) ?>,
                                    datasets: [{
                                            label: 'Target',
                                            data: <?= json_encode(array_column($row['progress'], 'target')) ?>,
                                            borderColor: 'green',
                                            backgroundColor: 'white',
                                            order: 2
                                        }, {
                                            label: 'Realisasi',
                                            data: <?= json_encode(array_column($row['progress'], 'realisasi')) ?>,
                                            borderColor: 'yellow',
                                            backgroundColor: 'white',
                                            order: 1
                                        },
                                        {
                                            label: 'Kompetitor',
                                            data: <?= json_encode(array_column($row['progress'], 'competitor')) ?>,
                                            borderColor: 'white',
                                            backgroundColor: 'red',
                                            type: 'bar',
                                            order: 0
                                        }
                                    ]
                                },
                                height: '50',
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: {
                                            display: true,
                                            position: 'top',
                                        },
                                        title: {
                                            display: false,
                                            text: 'Chart.js Doughnut Chart'
                                        }
                                    }
                                },
                            }
                        );
                    </script>
                <?php } ?>
                <?php
                $from = UI::createTextArea('tindak_lanjut', $row['tindak_lanjut'], '', '', $edited, $class = 'form-control contents', "");
                echo UI::createFormGroup($from, $rules["tindak_lanjut"], "tindak_lanjut", "Tindak Lanjut", false, 2);
                ?>


                <?php
                $from = UI::createTextArea('tindak_lanjut_rencana_penyelesaian', $row['tindak_lanjut_rencana_penyelesaian'], '', '', $edited, $class = 'form-control contents', "");
                echo UI::createFormGroup($from, $rules["tindak_lanjut_rencana_penyelesaian"], "tindak_lanjut_rencana_penyelesaian", "Rencana Penyelesaian", false, 2);
                ?>

                <?php
                $from = UI::createTextArea('tindak_lanjut_realisasi_penyelesaian', $row['tindak_lanjut_realisasi_penyelesaian'], '', '', $edited, $class = 'form-control contents', "");
                echo UI::createFormGroup($from, $rules["tindak_lanjut_realisasi_penyelesaian"], "tindak_lanjut_realisasi_penyelesaian", "Realisasi Penyelesaian", false, 2);
                ?>

                <?php
                $from = UI::createRadio("status", ["0" => "Open", "1" => "Close"], $row['status'], $edited && Access("evaluasi", "panelbackend/rtm_risalah"));
                echo UI::createFormGroup($from, $rules["status"], "status", "Status", false, 2);
                ?>

                <?php
                /*
                $from = UI::createCheckBox('is_risalah', 1, $row['is_risalah'], "Risalah", $edited, $class = 'iCheck-helper ', "");
                echo UI::createFormGroup($from, $rules["is_risalah"], "is_risalah");
                ?>

                <?php
                $from = UI::createCheckBox('is_tindak_lanjut', 1, $row['is_tindak_lanjut'], "Tindak Lanjut", $edited, $class = 'iCheck-helper ', "");
                echo UI::createFormGroup($from, $rules["is_tindak_lanjut"], "is_tindak_lanjut");*/

                $from = UI::createUploadMultiple("files", $row['files'], $page_ctrl, $edited);
                echo UI::createFormGroup($from, $rules["file"], "file", "Lampiran", false, 2);
                ?>

                <?php
                $from = UI::showButtonMode("save", null, $edited);
                echo UI::createFormGroup($from, null, null, null, false, 2);;
                ?>
            </div>
        </div>
    </div>
</div>

<script>
    <?php //if ($this->post['act'] == 'add_progress' || strstr($this->post['act'], 'remove_progress') !== false) { 
    ?>
    $(function() {
        $("#contact-tab").click();
    })
    <?php //} 
    ?>
</script>