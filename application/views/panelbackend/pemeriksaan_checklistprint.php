<table style="width: 100%;">
    <tr>
        <td>
            <br />
            <center>
                <b><?= $page_title ?></b>
            </center>
            <br />
        </td>
    </tr>
</table>
<table class="tableku1">
    <thead>
        <tr>
            <th style="width: 1px;">No</th>
            <th>Jenis pekerjaan yang harus dilakukan</th>
            <th style="width: 1px;">Sudah/Belum</th>
            <th style="width: 1px;">%penyelesaian</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1;
        $indukarr = [];
        foreach ($list['rows'] as $r) {
            $indukarr[$r['id_checklist_parent']] = 1;
        }
        foreach ($list['rows'] as $r) { ?>
            <tr data-tt-id='<?= $r['id_checklist'] ?>' data-tt-parent-id='<?= $r['id_checklist_parent'] ?>'>
                <td><?= $no++ ?></td>
                <?php
                $padding = 30 * ($r['level'] - 1);
                ?>
                <td style="padding-left: <?= $padding ?>px;"><?= $r['nama'] ?></td>
                <?php if ($indukarr[$r['id_checklist']]) { ?>
                    <td></td>
                    <td></td>
                <?php } else { ?>
                    <td style="text-align: center;"><?= ["0" => "Belum", "1" => "Sudah"][$row['is_oke'][$r['id_checklist']]] ?></td>
                    <td><?= $row['penyelesaian'][$r['id_checklist']] ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </tbody>
</table>

<br />
<br />
<table align="right">
    <tr>
        <td style="text-align: center;">
            Dibuat tanggal :<br />
            Pengendali Teknis<br />
            <br />
            <br />
            <br />
            (<?= $rowheader['nama_pereview'] ?>)
            <br />
            <?= $rowheader['nama_jabatan_pereview'] ?>
        </td>
    </tr>
</table>
<br />
<br />
<br />
<br />