<div class="row">
    <div class="col-sm-12">

        <?php
        $from = UI::createSelect('id_parent', $kpiarr, $row['id_parent'], $edited, $class = 'form-control ', "style='width:auto; max-width:100%;' onchange='goSubmit(\"set_value\")'");
        echo UI::createFormGroup($from, $rules["id_parent"], "id_parent", "Parent", false, 2);
        ?>

        <?php
        $from = UI::createTextBox('kode', $row['kode'], '225', '100', $edited, $class = 'form-control ', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["kode"], "kode", "Kode", false, 2);
        ?>

        <?php
        $from = UI::createTextArea('nama', $row['nama'], '3', '100', $edited, $class = 'form-control ', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama", false, 2);
        ?>

        <?php
        $from = UI::createTextNumber('urutan', $row['urutan'], '225', '100', $edited, $class = 'form-control ', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["urutan"], "urutan", "Urutan", false, 2);
        ?>

        <?php
        $from = UI::showButtonMode("save", null, $edited);
        echo UI::createFormGroup($from, null, null, null, false, 2);
        ?>
    </div>
</div>
<?php if (!$edited && $row['id_parent']) { ?>
    <div class="row">
        <div class="col-sm-12">
            <br />
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 ">
                <div class="new-content-title">
                    <h4 class="h4">Setting Tahunan</h4>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="<?= site_url("panelbackend/kpi_config/add/$row[id_kpi]") ?>" class="btn btn-sm btn-primary">Tambah Baru</a>
                </div>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Tahun</th>
                        <th>KPI Korporat</th>
                        <th>Perhitungan Korporat</th>
                        <th>KPI Direktorat</th>
                        <th>Perhitungan Direktorat</th>
                        <th>Perhitungan Unit</th>
                        <th></th>
                    </tr>
                </thead>
                <?php if ($rowstahunan)
                    foreach ($rowstahunan as $r) {
                        $r['jenis_direktorat'] = null;
                        if ($r['is_bersama'])
                            $r['jenis_direktorat'] = 2;
                        elseif ($r['is_direktorat'])
                            $r['jenis_direktorat'] = 1;
                ?>
                    <tr>
                        <td><a href="<?= site_url("panelbackend/kpi_config/detail/$row[id_kpi]/$r[tahun]") ?>"><?= $r['tahun'] ?></a></td>
                        <td><?= ["" => "Tidak", "1" => "Ya"][$r['is_korporat']] ?></td>
                        <td><?= $jenisrealisasiarr[$r['jenis_realisasi_korporat']] ?></td>
                        <td><?= ["" => "Tidak ada", "1" => "Masing-masing", "2" => "Bersama"][$r['jenis_direktorat']] ?></td>
                        <td><?= $jenisrealisasiarr[$r['jenis_realisasi_direktorat']] ?></td>
                        <td><?= $jenisrealisasiarr[$r['jenis_realisasi']] ?></td>
                        <td>
                            <?= UI::startMenu() ?>
                            <li><a class=" dropdown-item " href="<?= site_url("panelbackend/kpi_config/edit/$row[id_kpi]/$r[tahun]") ?>">Edit</a></li>
                            <li><a class=" dropdown-item " href="<?= site_url("panelbackend/kpi_config/delete/$row[id_kpi]/$r[tahun]") ?>">Delete</a></li>
                            <?= UI::closeMenu() ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
<?php } ?>