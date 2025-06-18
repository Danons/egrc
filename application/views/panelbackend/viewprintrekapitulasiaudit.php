<div class="row" style="width: 100%; margin: 20px 20px 0px 20px;">
    <h1 style="font-size: 13px; font-weight: lighter; margin: 0px;">PERUMDA AIR MINUM TIRTA RAHARJA</h1>
    <h1 style="font-size: 13px; font-weight: lighter; margin-bottom: 30px;">SATUAN PENGAWASAN INTERN</h1>
    <h1 style="font-size: 20px; text-align: center;">REKAPITULASI BIAYA AUDIT</h1>
    <h1 style="font-size: 20px; text-align: center;">TAHUN<?= '&nbsp;' . $tahun ?></h1>

    <table class="tableutama" style="width: 100%; margin-top: 30px; font-size: 15px;">
        <thead>

            <tr>
                <td class="thead" style="width: 10%;" rowspan="2">Bulan</td>
                <td class="thead" style="width: 70%;" colspan="4">Akomodasi</td>
                <td class="thead" style="width: 20%;" rowspan="2">Jumlah</td>
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
            // dpr($row);
            $bulan = ['januari', 'februari', 'maret', 'april', 'mei', 'juli', 'juni', 'agustus', 'september', 'oktober', 'nopember', 'desember'];
            foreach ($bulan as $key => $bulan) {
                foreach ($row as $key1 => $r) {
                    if ($key + 1 == $key1) {
            ?>
                        <tr>
                            <td class="childtable"><?= $bulan ?></td>
                            <td class="childtable"><?= $r['transportasi'] ?></td>
                            <td class="childtable"><?= $r['konsumsi'] ?></td>
                            <td class="childtable"><?= $r['penginapan'] ?></td>
                            <td class="childtable"><?= $r['sppd'] ?></td>
                            <td class="childtable"><?= $r['transportasi'] + $r['konsumsi'] + $r['penginapan'] + $r['sppd']  ?></td>
                        </tr>



            <?php }
                }
            } ?>
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