<table class="table table-striped table-hover dataTable table-bordered">
    <thead>
        <tr>
            <th rowspan="2" style="width: 1px;">No</th>
            <th rowspan="2">Uraian</th>
            <th rowspan="2">Dilaksanakan Oleh</th>
            <th rowspan="2">Nomor KKA</th>
            <th colspan="2" style="text-align: center;">Waktu Audit</th>
            <th rowspan="2"></th>
        </tr>
        <tr>
            <th style="text-align:right">Anggaran</th>
            <th style="text-align:right">Realisasi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1;
        foreach ($list['rows'] as $r) { ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $r['uraian'] ?></td>
                <td><?= $r['nama_user'] ?> - <?= $r['nama_jabatan'] ?></td>
                <td><?= $kkaarr[$r['id_kka']] ?></td>
                <td style="text-align: right;"><?= rupiah($r['anggaran']) ?></td>
                <td style="text-align: right;"><?= rupiah($r['realisasi_anggaran']) ?></td>
                <td style='text-align:right'>
                    <?= UI::showMenuMode('inlist', $r[$pk]) ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>