<b>
    Tahun : <?= $tahun_filter ?><br />
    Unit :
    <?php if ($view_all) {
    ?>
        <?= $mtsdmunitarr[$id_unit_filter] ?>
    <?php } else { ?>
        <?= $mtsdmunitarr[$_SESSION[SESSION_APP]['id_unit']] ?>
    <?php } ?>
    <br />
    Dokumen : <?= $dokumenarr[$id_dokumen_filter] ?>
</b>
<br />
<table class="tableku tableku1">
    <thead>
        <tr>
            <th width="1">No</th>
            <th>Periode</th>
            <th>Kebutuhan</th>
            <th>Penilaian</th>
        </tr>
    </thead>
    <tbody>
        <?php

        $no = 1;
        foreach ($rows as $row) {
            $periodes = 12 / $row['konversi_bulan'];
            for ($i = 1; $i <= $periodes; $i++) {
                if ($periodes == 1) $i = $tahun_filter;
                $rowp = $rowspenilaian[$row['id_comp_kebutuhan']][$i];
        ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['periode_label'] ? $row['periode_label'] : $row['nama_periode'] ?> <?= $i ?></td>
                    <td>
                        <?= $row['nama_kebutuhan'] ?>
                    </td>
                    <?php
                    $addcolor = null;
                    if ($rowp['id_status_penilaian'] == 1)
                        $addcolor = "background:red";
                    if ($rowp['id_status_penilaian'] == 2)
                        $addcolor = "background:yellow";
                    if ($rowp['id_status_penilaian'] == 3)
                        $addcolor = "background:green";
                    ?>
                    <td style="padding: 5px !important; width:200px; <?= $addcolor ?>">
                        <?= $mtstatuspenilaianarr[$rowp['id_status_penilaian']] ?>
                        <?= $rowp['keterangan'] ? "<br/>" . $rowp['keterangan'] : "" ?>
                    </td>
                </tr>
        <?php }
        } ?>
    </tbody>
</table>