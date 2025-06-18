<table class="tableku">

    <tr>
        <td style="width: 140px;">No. Surat Tugas </td>
        <td style="width:1px">:</td>
        <td><?= $rowheader['no_surat_tugas'] ?></td>
        <td>Ketua Tim </td>
        <td>:</td>
        <td><?= $rowheader['nama_penanggung_jawab'] . " - " . $rowheader['nama_jabatan_penanggung_jawab'] ?></td>
    </tr>
    <tr>
        <td style="width: 140px;">Nama Objek Audit </td>
        <td style="width:1px">:</td>
        <td><?= $rowheader['nama_unit'] ?></td>
        <td> </td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>Kegiatan yang di audit </td>
        <td>:</td>
        <td><?= $rowheader['nama'] ?></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>Lokasi </td>
        <td>:</td>
        <td><?= $rowheader['lokasi'] ?></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>Periode yang diaudit </td>
        <td>:</td>
        <td><?= Eng2Ind($rowheader['tgl_mulai']) . "-" . Eng2Ind($rowheader['tgl_selesai']) ?></td>
        <td></td>
        <td></td>
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
            <th style="width:1px">No</th>
            <th>Halaman LHE</th>
            <th>Masalah yang dijumpai</th>
            <th>Nomor KKA</th>
            <th>Penyelesaian Masalah</th>
            <th>Dilakukan Oleh</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1;
        foreach ($list['rows'] as $r) { ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $r['halaman_lhe'] ?></td>
                <td><?= $r['judul_temuan'] ?></td>
                <td><?= $kkaarr[$r['id_kka']] ?></td>
                <td><?= $r['rencana_tindakan_perbaikan'] ?></td>
                <td><?= $r['nama_user'] . "-" . $r['nama_jabatan'] ?></td>
                <td><?= $r['keterangan'] ?></td>

            </tr>
        <?php } ?>
    </tbody>
</table>
<table class="tableku">

    <tr>
        <td style="width: 250px;"><b>Pengendali Teknis,</b></td>
        <td style="width:1px"></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td style="width: 140px;"><br /><br /><br /><br /><b><?= $rowheader['nama_pereview'] ?><br /><?= $rowheader['nama_jabatan_pereview'] ?> </b></td>
        <td style="width:1px"></td>
        <td></td>
        <td> </td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td style="width: 140px;">Tanggal : <?= Eng2Ind($list['rows'][0]['tgl_ttd'])?></td>
        <td style="width:1px"></td>
        <td></td>
        <td> </td>
        <td></td>
        <td></td>
    </tr>
</table>