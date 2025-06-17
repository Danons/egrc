<table width="100%">
    <tr>
        <td></td>
        <td width="300px">
            Lampiran : 1<br />
            No. Formulir : F-Pros.04-01
        </td>
    </tr>
</table>

<b>
    DAFTAR PERMASALAHAN<br />
    RAPAT TINJAUAN MANAJEMEN KE : <?= $rtm['rtm_ke'] ?><br />
    TINGKAT : <?= strtoupper($rtm['tingkat']) ?><br />
    Pada RKT <?= $rtm['rkt'] ?> Tahun <?= $rtm['tahun'] ?>
</b>

<table class="tableku1">
    <thead>
        <tr>
            <th rowspan="2" style="width: 1px;">No</th>
            <th rowspan="2">Uraian Permasalahan Bidang</th>
            <th rowspan="2">Tindaklanjut</th>
            <th colspan="3">Rencana Penyelesaian</th>
            <th colspan="2">Status</th>
        </tr>
        <tr>
            <th>Rencana</th>
            <th>Realisasi</th>
            <th>Penanggung Jawab (PIC)</th>
            <th style="width: 1px;">O</th>
            <th style="width: 1px;">C</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1.</td>
            <td><u><b>Status Tindakan Dari RTM Sebelumnya</b></u></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>1.1</td>
            <td>
                <table class="tableku1">
                    <tr>
                        <th rowspan="2" colspan="2">Bidang</th>
                        <th rowspan="2">Jumlah Permasalahan</th>
                        <th colspan="2">Status</th>
                    </tr>
                    <tr>
                        <th>O</th>
                        <th>C</th>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td>Status Tindakan RTM sebelumnya</td>
                        <td><?= (int)$jp ?></td>
                        <td><?= (int)$j0 ?></td>
                        <td><?= (int)$j1 ?></td>
                    </tr>
                    <?php $no2 = 2;
                    if ($rowsb)
                        foreach ($rowsb as $r) { ?>
                        <tr>
                            <td><?= $no2++ ?>.</td>
                            <td><?= $mtjenisrtmarr[$r['id_jenis_rtm_parent']] ?></td>
                            <td><?= (int)$r['jp'] ?></td>
                            <td><?= (int)$r['j0'] ?></td>
                            <td><?= (int)$r['j1'] ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </td>
            <td>Rincian daftar evaluasi progres tindak lanjut RTM Ke-<?= $rtm['rtm_ke'] ?> terlampir.</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <?php $no = 2;
        foreach ($list['rows'] as $id_jenis_rtm_parent => $rs) { ?>
            <tr>
                <td><?= $no++ ?>.</td>
                <td><u><b><?= $mtjenisrtmarr[$id_jenis_rtm_parent] ?></b></u></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php $no1 = 1;
            foreach ($rs as $r) { ?>
                <tr>
                    <td><?= $no - 1 ?>.<?= $no1++ ?>.</td>
                    <td><?= $r['uraian'] ?></td>
                    <td><?= $r['tindak_lanjut'] ?></td>
                    <td><?= $r['tindak_lanjut_rencana_penyelesaian'] ?></td>
                    <td><?= $r['tindak_lanjut_realisasi_penyelesaian'] ?></td>
                    <td><?= $r['picstr'] ?></td>
                    <td><?= $r['status'] == 0 ? 'O' : '' ?></td>
                    <td><?= $r['status'] == 1 ? 'C' : '' ?></td>
                </tr>
            <?php } ?>
        <?php } ?>
    </tbody>
</table>
<style>
    .tableku>tbody>tr>td {
        border: 1px solid #fff !important;
        padding: 0px 0px;
        vertical-align: top;
    }

    .tableku1 td {
        border: 1px solid #555;
        border-top: #fff !important;
        border-bottom: #fff !important;
        padding: 3px 5px;
        vertical-align: top;
    }
</style>