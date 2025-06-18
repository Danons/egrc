<table class="tableku">

    <tr>
        <td style="width: 140px;">Nama Objek Audit </td>
        <td style="width:1px">:</td>
        <td><?= $rowheader['nama_unit'] ?></td>
        <td style="width: 100px;">Nomor KKA </td>
        <td style="width:1px">:</td>
        <td></td>
    </tr>
    <tr>
        <td>Kegiatan yang di audit </td>
        <td>:</td>
        <td><?= $rowheader['nama'] ?></td>
        <td>Disusun oleh </td>
        <td>:</td>
        <td><?= $rowheader['nama_penyusun'] . " - " . $rowheader['nama_jabatan_penyusun'] ?></td>
    </tr>
    <tr>
        <td>Lokasi </td>
        <td>:</td>
        <td><?= $rowheader['lokasi'] ?></td>
        <td>Tanggal & Paraf </td>
        <td>:</td>
        <td></td>
    </tr>
    <tr>
        <td>Periode yang diaudit </td>
        <td>:</td>
        <td><?= Eng2Ind($rowheader['tgl_mulai']) . "-" . Eng2Ind($rowheader['tgl_selesai']) ?></td>
        <td>Direview oleh </td>
        <td>:</td>
        <td><?= $rowheader['nama_pereview'] . " - " . $rowheader['nama_jabatan_pereview'] ?></td>
    </tr>
    <tr>
        <td> </td>
        <td></td>
        <td></td>
        <td>Tanggal & Paraf </td>
        <td>:</td>
        <td></td>
    </tr>
    <tr>
        <td colspan="6">
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
            <th rowspan="2" style="width: 1px;">No</th>
            <th rowspan="2">Uraian</th>
            <th rowspan="2">Dilaksanakan Oleh</th>
            <th rowspan="2">Nomor KKA</th>
            <th colspan="2" style="text-align: center;">Waktu Audit</th>
            <th rowspan="2" style="width:10px">Keterangan</th>
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
                <td>
                    <?= $r['keterangan'] ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>