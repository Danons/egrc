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
            <th>Permasalahan/Kendala</th>
            <th>Indeks KKA</th>
            <th>Penyelesaian</th>
            <th>Persetujuan</th>
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
                <td><?= ["1" => "Oke", "" => ""][$r['is_persetujuan']] ?></td>
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
        <td style="width: 140px;">Tanggal :</td>
        <td style="width:1px"></td>
        <td></td>
        <td> </td>
        <td></td>
        <td></td>
    </tr>
</table>