<div class="row" style="width: 100%; margin: 20px 20px 0px 20px;">
    <h1 style="font-size: 13px; font-weight: lighter; margin: 0px;">PERUMDA AIR MINUM TIRTA RAHARJA</h1>
    <h1 style="font-size: 13px; font-weight: lighter; margin-bottom: 30px;">SATUAN PENGAWASAN INTERN</h1>
    <h1 style="font-size: 20px; text-align: center;">ANGGARAN BIAYA AUDIT</h1>
    <h1 style="font-size: 20px; text-align: center;">TAHUN<?= '&nbsp;' . $tahun ?></h1>
    <table class="tableutama" style="width: 100%; margin-top: 30px; font-size: 15px;">
        <thead>
            <tr>
                <td class="thead" style="width: 4%;" rowspan="2">No Urut</td>
                <td class="thead" style="width: 13%;" rowspan="2">Nama Auditi</td>
                <td class="thead" style="width: 13%;" rowspan="2">Tujuan</td>
                <td class="thead" style="width: 13%;" rowspan="2">Petugas</td>
                <td class="thead" style="width: 13%;" rowspan="2">Jabatan</td>
                <td class="thead" style="width: 13%;" rowspan="2">Hari</td>
                <td class="thead" style="width: 18%;" colspan="4">Akomodasi</td>
                <td class="thead" style="width: 13%;" rowspan="2">Jumlah</td>
            </tr>
            <tr>
                <td class="thead">Transportasi</td>
                <td class="thead">Konsumsi</td>
                <td class="thead">Penginapan</td>
                <td class="thead">SPPD</td>
            </tr>
        </thead>
        <tbody>

            <?php
            $no = 0;
            // dpr($akomodasi);
            foreach ($rows['pemeriksaan'] as $r) {
                $jumlah = $akomodasi[$r['id_pemeriksaan']]['transportasi'] + $akomodasi[$r['id_pemeriksaan']]['konsumsi'] + $akomodasi[$r['id_pemeriksaan']]['penginapan'] + $akomodasi[$r['id_pemeriksaan']]['sppd'];
                $jumlahtransportasi += $akomodasi[$r['id_pemeriksaan']]['transportasi'];
                $jumlahkonsumsi += $akomodasi[$r['id_pemeriksaan']]['konsumsi'];
                $jumlahpenginapan += $akomodasi[$r['id_pemeriksaan']]['penginapan'];
                $jumlahsppd += $akomodasi[$r['id_pemeriksaan']]['sppd'];

                $no++; ?>
                <tr>
                    <td class="childtable"><?= $no ?></td>
                    <td class="childtable"><?= $r['nama'] ?></td>
                    <td class="childtable"><?= $r['tujuan'] ?></td>

                    <td class="childtable"><?= $nama_petugas[$r['id_pemeriksaan']] ?></td>
                    <td class="childtable"><?= $jabatan_petugas[$r['id_pemeriksaan']] ?></td>

                    <td class="childtable"><?= ((strtotime($r['tgl_selesai']) - strtotime($r['tgl_mulai'])) / 86400) . "&nbsp;" ?>hari</td>

                    <td class='childtable'><?= $akomodasi[$r['id_pemeriksaan']]['transportasi'] ?></td>
                    <td class='childtable'><?= $akomodasi[$r['id_pemeriksaan']]['konsumsi'] ?></td>
                    <td class='childtable'><?= $akomodasi[$r['id_pemeriksaan']]['penginapan'] ?></td>
                    <td class='childtable'><?= $akomodasi[$r['id_pemeriksaan']]['sppd'] ?></td>

                    <td class="childtable"><?= $jumlah ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td class="childtable" colspan="6">Jumlah</td>
                <td class="childtable"><?= $jumlahtransportasi ?></td>
                <td class="childtable"><?= $jumlahkonsumsi ?></td>
                <td class="childtable"><?= $jumlahpenginapan ?></td>
                <td class="childtable"><?= $jumlahsppd ?></td>
                <td class="childtable"><?= $jumlahtransportasi + $jumlahkonsumsi + $jumlahpenginapan + $jumlahsppd ?></td>
            </tr>
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