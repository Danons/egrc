<?php /*<div class="row">
    <div class="col-auto d-flex">
        <?php if ($view_all) {        ?>
            <?= UI::createSelect("id_unit_filter", $unitarr, $id_unit_filter, true, 'form-control', "style='width:500px !important; display:inline' onchange='goSubmit(\"set_filter\")'") ?>
        <?php } else { ?>
            <h5 style="display: inline; color: #fff; padding: 0px 10px;"><?= $unitarr[$_SESSION[SESSION_APP]['id_unit']] ?></h5>
        <?php }
        ?>
    </div>
    <div class="col-auto d-inline ms-auto">

        <?= UI::createSelect("id_periode_tw_filter", $mtperiodetwarr, $id_periode_tw_filter, true, 'form-control me-2', "style='max-width:150px !important; display:inline' onchange='goSubmit(\"set_filter\")'") ?>
        <?= UI::createTextNumber("tahun_filter", $tahun_filter, 4, 4, true, "form-control", "style='width:100px; display:inline' onchange='goSubmit(\"set_filter\")'") ?>

    </div>
</div>
<br /> */ ?>
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link active" href="#">Rekap Laporan Hasil Pemeriksaan (LHP)</a>
    </li>
    <?php if ($jenis !== 'khusus' && Access("monev", "panelbackend/pemeriksaan")) { ?>
        <li class="nav-item">
            <a class="nav-link" href="<?= site_url("panelbackend/pemeriksaan/monev/$jenis") ?>">Monitoring & Evaluasi</a>
        </li>
    <?php } ?>
</ul>

<?php
unset($bidangpemeriksaanarr['']);
?>

<table class="table table-striped table-hover dataTable treetableclose table-bordered">
    <thead>
        <tr>
            <th rowspan="3">Unit/Bidang/Kegiatan</th>
            <?php if ($bidangpemeriksaanarr) { ?>
                <th colspan="<?= count($bidangpemeriksaanarr) * 3 ?>" style="text-align:center">Bidang Pemeriksaan</th>
            <?php } ?>
            <th rowspan="2" colspan="3">Jumlah</th>
            <th rowspan="3" style="width:0px"></th>
        </tr>
        <tr>
            <?php if ($bidangpemeriksaanarr) { ?>
                <?php
                foreach ($bidangpemeriksaanarr as $k => $v) { ?>
                    <th width="100px" colspan="3"><?= trim(str_replace("Bidang", "", $v)) ?></th>
            <?php }
            } ?>
        </tr>
        <tr>
            <?php if ($bidangpemeriksaanarr) { ?>
                <?php
                foreach ($bidangpemeriksaanarr as $k => $v) { ?>
                    <th width="10px">T</th>
                    <th width="10px">TL</th>
                    <th width="10px">S</th>
            <?php }
            } ?>
            <th width="10px">T</th>
            <th width="10px">TL</th>
            <th width="10px">S</th>
        </tr>
    </thead>
    <tbody>
        <?php $jumlaharr = [];
        foreach ($rows as $id_unit => $rs) { ?>
            <tr data-tt-id='unit<?= $id_unit ?>' data-tt-parent-id=''>
                <td><b><?= $unitarr[$id_unit] ?></b></td>
                <?php
                $jumlah = 0;
                $jumlah_close = 0;
                $jumlah_monev = 0;
                if ($bidangpemeriksaanarrs)
                    foreach ($bidangpemeriksaanarr as $k => $v) {
                        $jumlah += $totalunit[$id_unit][$k]['jumlah'];
                        $jumlah_close += $totalunit[$id_unit][$k]['jumlah_close'];
                        $jumlah_monev += $totalunit[$id_unit][$k]['jumlah_monev'];
                ?>
                    <td><b><?= $totalunit[$id_unit][$k]['jumlah'] ?></b></td>
                    <td><b><?= $totalunit[$id_unit][$k]['jumlah_close'] ?></b></td>
                    <td><b><?= $totalunit[$id_unit][$k]['jumlah_monev'] ?></b></td>
                <?php }
                else {
                    $k = null;
                    $jumlah += $totalunit[$id_unit][$k]['jumlah'];
                    $jumlah_close += $totalunit[$id_unit][$k]['jumlah_close'];
                    $jumlah_monev += $totalunit[$id_unit][$k]['jumlah_monev'];
                } ?>
                <td><b><?= $jumlah ?></b></td>
                <td><b><?= $jumlah_close ?></b></td>
                <td><b><?= $jumlah_monev ?></b></td>
                <td></td>
            </tr>
            <?php foreach ($rs as $id_pemeriksaan => $rws) {
                $r = $rws[array_keys($rws)[0]]; ?>
                <tr data-tt-id='kegiatan<?= $r['id_pemeriksaan'] ?>' data-tt-parent-id='unit<?= $id_unit ?>'>
                    <td><a href='<?= ($url = base_url($page_ctrl . "/detail/$jenis/$r[$pk]")) ?>'><?= $r['nama'] ?></a></td>
                    <?php
                    $jumlah = 0;
                    $jumlah_close = 0;
                    $jumlah_monev = 0;
                    if ($bidangpemeriksaanarr)
                        foreach ($bidangpemeriksaanarr as $k => $v) {
                            $jumlaharr[$k]['jumlah'] += $rws[$k]['jumlah'];
                            $jumlaharr[$k]['jumlah_close'] += $rws[$k]['jumlah_close'];
                            $jumlaharr[$k]['jumlah_monev'] += $rws[$k]['jumlah_monev'];
                            $jumlah += $rws[$k]['jumlah'];
                            $jumlah_close += $rws[$k]['jumlah_close'];
                            $jumlah_monev += $rws[$k]['jumlah_monev'];
                    ?>
                        <td><?= $rws[$k]['jumlah'] ?></td>
                        <td><?= $rws[$k]['jumlah_close'] ?></td>
                        <td><?= $rws[$k]['jumlah_monev'] ?></td>
                    <?php }
                    else {
                        $k = null;
                        $jumlaharr[$k]['jumlah'] += $rws[$k]['jumlah'];
                        $jumlaharr[$k]['jumlah_close'] += $rws[$k]['jumlah_close'];
                        $jumlaharr[$k]['jumlah_monev'] += $rws[$k]['jumlah_monev'];
                        $jumlah += $rws[$k]['jumlah'];
                        $jumlah_close += $rws[$k]['jumlah_close'];
                        $jumlah_monev += $rws[$k]['jumlah_monev'];
                    } ?>
                    <td><?= $jumlah ?></td>
                    <td><?= $jumlah_close ?></td>
                    <td><?= $jumlah_monev ?></td>
                    <td><?= UI::showMenuMode('inlist', $r[$pk]) ?></td>
                </tr>
        <?php }
        } ?>

        <tr>
            <td><b>Jumlah</b></td>
            <?php
            $jumlah = 0;
            $jumlah_close = 0;
            $jumlah_monev = 0;
            if ($bidangpemeriksaanarr)
                foreach ($bidangpemeriksaanarr as $k => $v) {
                    $jumlah += $jumlaharr[$k]['jumlah'];
                    $jumlah_close += $jumlaharr[$k]['jumlah_close'];
                    $jumlah_monev += $jumlaharr[$k]['jumlah_monev'];
            ?>
                <td><b><?= $jumlaharr[$k]['jumlah'] ?></b></td>
                <td><b><?= $jumlaharr[$k]['jumlah_close'] ?></b></td>
                <td><b><?= $jumlaharr[$k]['jumlah_monev'] ?></b></td>
            <?php }
            else {
                $k = null;
                $jumlah += $jumlaharr[$k]['jumlah'];
                $jumlah_close += $jumlaharr[$k]['jumlah_close'];
                $jumlah_monev += $jumlaharr[$k]['jumlah_monev'];
            } ?>
            <td><b><?= $jumlah ?></b></td>
            <td><b><?= $jumlah_close ?></b></td>
            <td><b><?= $jumlah_monev ?></b></td>
            <td></td>
        </tr>
    </tbody>
</table>