<div class="row">
    <div class="col-sm-6">

        <?php
        $from = UI::createTextNumber("nama", $rowheader['nama'], '', '', false, 'form-control', "style='width:80px'");
        echo UI::createFormGroup($from, $rules["nama"], "nama", 'KPI');

        if (!$row['tahun'])
            $row['tahun'] = date("Y");
        $from = UI::createTextNumber("tahun", $row['tahun'], '', '', $edited, 'form-control', "style='width:80px'");
        echo UI::createFormGroup($from, $rules["tahun"], "tahun", 'Tahun');

        $from = UI::createCheckBox('is_korporat', 1, $row['is_korporat'], "KPI Korporat", $edited, $class = 'iCheck-helper ', "onclick='goSubmit(\"set_value\")'");
        echo UI::createFormGroup($from, $rules["is_korporat"], "is_korporat");

        if ($row['is_korporat']) {
            $from = UI::createRadio("jenis_realisasi_korporat", $jenisrealisasiarr, $row['jenis_realisasi_korporat'], $edited, true);
            echo UI::createFormGroup($from, $rules["jenis_realisasi_korporat"], "jenis_realisasi_korporat", "Perhitungan Korporat");
        }

        $row['jenis_direktorat'] = null;
        if ($row['is_bersama'])
            $row['jenis_direktorat'] = 2;
        elseif ($row['is_direktorat'])
            $row['jenis_direktorat'] = 1;

        $from = UI::createSelect("jenis_direktorat", ["" => "Tidak ada", "1" => "Masing-masing", "2" => "Bersama"], $row['jenis_direktorat'], $edited, "form-control", "onchange='goSubmit(\"set_value\")'");
        echo UI::createFormGroup($from, $rules["jenis_direktorat"], "jenis_direktorat", 'KPI Direktorat');

        if ($row['jenis_direktorat']) {
            $from = UI::createRadio("jenis_realisasi_direktorat", $jenisrealisasiarr, $row['jenis_realisasi_direktorat'], $edited, true);
            echo UI::createFormGroup($from, $rules["jenis_realisasi_direktorat"], "jenis_realisasi_direktorat", "Perhitungan Direktorat");
        }


        $from = UI::createRadio("jenis_realisasi", $jenisrealisasiarr, $row['jenis_realisasi'], $edited, true);
        echo UI::createFormGroup($from, $rules["jenis_realisasi"], "jenis_realisasi", 'Perhitungan Unit');

        $from = UI::showButtonMode("save", null, $edited);
        echo UI::createFormGroup($from);
        ?>
    </div>
</div>

<?php if (!$edited) { ?>
    <div class="row">
        <div class="col-sm-12">
            <br />
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 ">
                <div class="new-content-title">
                    <h4 class="h4">Target KPI</h4>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="<?= site_url("panelbackend/kpi_target/add/$row[id_kpi]/$row[tahun]") ?>" class="btn btn-sm btn-primary">Tambah Baru</a>
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
                        if ($row['is_direktorat'] || $row['is_bersama'] || $row['is_korporat']) { ?>
                            <th>PIC</th>
                        <?php } ?>
                        <th></th>
                    </tr>
                </thead>
                <?php if ($rowstarget)
                    foreach ($rowstarget as $r) {
                        echo "<tr data-tt-id='" . $r['id'] . "' data-tt-parent-id='" . $r['id_parent'] . "'>";
                ?>
                    <td><a href="<?= site_url("panelbackend/kpi_target/detail/$row[id_kpi]/$r[tahun]/$r[id_kpi_target]") ?>"><?= $r['nama'] ?></a></td>
                    <td style="text-align: right;"><?= $r['bobot'] ?></td>
                    <td><?= $r['polarisasi'] ?></td>
                    <td style="text-align: right;"><?= rupiah($r['target']) ?></td>
                    <td><?= $r['satuan'] ?></td>
                    <?php
                        if ($row['is_direktorat'] || $row['is_bersama'] || $row['is_korporat']) { ?>
                        <td><?= ["0" => "Tidak", "1" => "Ya"][$r['is_pic']] ?></td>
                    <?php } ?>
                    <td>
                        <?= UI::startMenu() ?>
                        <li><a class=" dropdown-item " href="<?= site_url("panelbackend/kpi_target/edit/$row[id_kpi]/$r[tahun]/$r[id_kpi_target]") ?>">Edit</a></li>
                        <li><a class=" dropdown-item " href="<?= site_url("panelbackend/kpi_target/delete/$row[id_kpi]/$r[tahun]/$r[id_kpi_target]") ?>">Delete</a></li>
                        <?= UI::closeMenu() ?>
                    </td>
                    </tr>
                <?php } ?>
            </table>

        </div>
    </div>
<?php } ?>