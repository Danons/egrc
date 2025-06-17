<table class="table table-striped table-hover dataTable">
    <thead>
        <tr>
            <th style="width:10px">No</th>
            <th>Permsalahan/Komentar</th>
            <th>Indeks KKA</th>
            <th>Penyelesaian</th>
            <th>Persetujuan</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1;
        foreach ($list['rows'] as $r) { ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $r['permsalahan'] ?></td>
                <td><?= $kkaarr[$r['id_kka']] ?></td>
                <td><?= $r['penyelesaian'] ?></td>
                <td><?= UI::createCheckBox("is_persetujuan[$r[id_review_supervisi]]", 1, $r['is_persetujuan'], "Oke", $this->access_role['edit'], '', 'onclick="goSubmitValue(\'save\','.$r['id_review_supervisi'].')"') ?></td>

                <td style='text-align:right'>
                    <?= UI::showMenuMode('inlist', $r[$pk]) ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>