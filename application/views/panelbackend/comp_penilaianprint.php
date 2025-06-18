<b>
    Tahun : <?= $tahun_filter ?><br />
    Unit :
    <?php if ($view_all) {
    ?>
        <?= $mtsdmunitarr[$id_unit_filter] ?>
    <?php } else { ?>
        <?= $mtsdmunitarr[$_SESSION[SESSION_APP]['id_unit']] ?>
    <?php } ?>
</b>
<br />
<table class="tableku tableku1">
    <thead>
        <tr>
            <th width="1">No</th>
            <th>Nomor</th>
            <th>Nama Dokumen</th>
            <th>Penilaian</th>
        </tr>
    </thead>
    <tbody>
        <?php

        $no = 1;
        foreach ($rows as $row) {
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['nomor_dokumen'] ?></td>
                <td><?= $row['nama'] ?></td>
                <td style="text-align: center;"><?= $row['penilaian'] ?></td>
            </tr>
        <?php
        } ?>
    </tbody>
</table>