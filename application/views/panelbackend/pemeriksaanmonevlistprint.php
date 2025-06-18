<!-- <b>
    <?php if ($view_all) {
        if ($id_unit_filter) { ?>
            Unit : <?= $unitarr[$id_unit_filter] ?><br />
        <?php }
    } else { ?>
        Unit : <?= $unitarr[$_SESSION[SESSION_APP]['id_unit']] ?><br />
    <?php } ?>
    Periode : <?= $mtperiodetwarr[$id_periode_tw_filter] ?><br />
    Tahun : <?= $tahun_filter ?>
</b>
<br />
 -->


<table class="tableku tableku1">
    <thead>
        <tr>
            <th>Unit/Bidang/Kegiatan</th>
            <th>Σ Temuan</th>
            <th>Σ Tindaklanjut (Penyelesaian)</th>
            <th>Σ Sisa Temuan</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $id_unit => $rs) { ?>
            <tr data-tt-id='unit<?= $id_unit ?>' data-tt-parent-id=''>
                <td><b><?= $unitarr[$id_unit] ?></b></td>
                <td><b><?= $totalunit[$id_unit]['jumlah_temuan'] ?></b></td>
                <td><b><?= $totalunit[$id_unit]['jumlah_tindak_lanjut'] ?></b></td>
                <td><b><?= $totalunit[$id_unit]['jumlah_sisa_temuan'] ?></b></td>
            </tr>
            <?php foreach ($rs as $r) { ?>
                <tr data-tt-id='kegiatan<?= $r['id_pemeriksaan'] ?>' data-tt-parent-id='unit<?= $id_unit ?>'>
                    <td style="padding-left:40px"><?= $r['nama'] ?></td>
                    <td><?= $r['jumlah_temuan'] ?></td>
                    <td><?= $r['jumlah_tindak_lanjut'] ?></td>
                    <td><?= $r['jumlah_sisa_temuan'] ?></td>
                </tr>
        <?php }
        } ?>
    </tbody>
</table>