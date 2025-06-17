<div class="row" style="width: 100%; margin: 20px 20px 0px 20px;">
    <h1 style="font-size: 13px; font-weight: lighter; margin: 0px;">PERUMDA AIR MINUM TIRTA RAHARJA</h1>
    <h1 style="font-size: 13px; font-weight: lighter; margin-bottom: 30px;">SATUAN PENGAWASAN INTERN</h1>
    <h1 style="font-size: 20px; text-align: center;">RENCANA AUDIT DILIHAT DARI OBJEK AUDIT</h1>
    <h1 style="font-size: 20px; text-align: center;">TAHUN<?= '&nbsp;' . $tahun ?></h1>

    <table class="tableutama" style="width: 100%; margin-top: 30px; font-size: 15px;">
        <thead>
            <tr>
                <th class="thead" style="width: 4%;">No Urut</th>
                <th class="thead" style="width: 32%;">Nama Auditi</th>
                <th class="thead" style="width: 32%;">Sasaran Audit</th>
                <th class="thead" style="width: 32%;">Waktu Pelaksanaan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 0;
            foreach ($rows['pemeriksaan'] as $r) {
                $no++;
            ?>
                <tr>
                    <td class="childtable"><?= $no ?></td>
                    <td class="childtable"><?= $r['nama'] ?></td>
                    <td class="childtable"><?= $r['nama_sasaran'] ?></td>
                    <td class="childtable"><?= Eng2Ind($r['tgl_mulai'], false) . '&nbsp;sampai&nbsp;' . Eng2Ind($r['tgl_selesai'], false) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<style>
    .tableutama,
    .childtable {
        vertical-align: top;
        border: 1px solid;
        text-align: center;
    }

    .thead {
        text-align: center;
        border: 1px solid;
    }

    .tableutama {
        width: 100%;
        border-collapse: collapse;
    }
</style>