<?php
unset($bidangpemeriksaanarr['']);
?>

<table class="tableku tableku1">
    <thead>
        <tr>
            <th rowspan="3">Unit/Bidang/Kegiatan</th>
            <th colspan="<?= count($bidangpemeriksaanarr) * 3 ?>" style="text-align:center">Bidang Pemeriksaan</th>
            <th rowspan="2" colspan="3">Jumlah</th>
        </tr>
        <tr>
            <?php
            foreach ($bidangpemeriksaanarr as $k => $v) { ?>
                <th width="100px" colspan="3"><?= trim(str_replace("Bidang", "", $v)) ?></th>
            <?php } ?>
        </tr>
        <tr>
            <?php
            foreach ($bidangpemeriksaanarr as $k => $v) { ?>
                <th width="10px">T</th>
                <th width="10px">TL</th>
                <th width="10px">S</th>
            <?php } ?>
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
                if ($bidangpemeriksaanarr)
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
            </tr>
            <?php foreach ($rs as $id_pemeriksaan => $rws) {
                $r = $rws[array_keys($rws)[0]]; ?>
                <tr data-tt-id='kegiatan<?= $r['id_pemeriksaan'] ?>' data-tt-parent-id='unit<?= $id_unit ?>'>
                    <td style="padding-left:20px"><?= $r['nama'] ?></td>
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
        </tr>
    </tbody>
</table>