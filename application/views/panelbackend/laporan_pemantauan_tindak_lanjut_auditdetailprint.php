<div class="row" style=" border-bottom: 2px solid black; width: 100%; padding: 20px 0px;">
    <h1 style="font-size: 13px; font-weight: lighter; margin: 0px;">PERUMDA AIR MINUM TIRTA RAHARJA</h1>
    <h1 style="font-size: 13px; font-weight: lighter; margin-bottom: 30px;">SATUAN PENGAWASAN INTERN</h1>
    <h1 style="font-size: 15px; text-align: center;">LAPORAN PEMANTAUAN TINDAK LANJUT TEMUAN AUDIT</h1>
</div>



<div style=" width: 100%;">
    <h1 style="font-size: 15px; padding: 20px 40px ">informasi umum</h1>
    <div style="font-size: 15px; padding: 0px 40px">

        <table>
            <tr>
                <td>Nama Audit</td>
                <td>:</td>
                <td><?= $rowheader['nama'] ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td><?= $rowheader['lokasi'] ?></td>
            </tr>
        </table>

        <table style="width: 100%;margin-top: 20px;" class="tablebawah">
            <thead>
                <tr>
                    <th class="thead">No</th>
                    <th class="thead">No. LHE</th>
                    <th class="thead">Uraian Temuan</th>
                    <th class="thead">Rekomendasi</th>
                    <th class="thead">Tindak Lanjut</th>
                    <th class="thead">Keterangan</th>
            </thead>
            <tbody>
                <?php
                $no = 0;
                foreach ($listtemuan['rows'] as $r) {
                    $no++
                ?>
                    <tr>
                        <td class="childtable"><?= $no ?></td>
                        <td class="childtable"><?= $r['halaman_lhe'] ?></td>
                        <td class="childtable"><?= $r['judul_temuan'] ?></td>
                        <td class="childtable"><?= $r['rekomendasi'] ?></td>
                        <td class="childtable"><?= $r['rencana_tindakan_perbaikan'] ?></td>
                        <td class="childtable"><?= $r['keterangan'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div style=" width: 100%; padding: 10px 40px; justify-content: flex-end; display: flex;">
        <div style="display: flex; justify-content: center; flex-direction: column; text-align: center;">
            <p>Tim Audit</p>
            <p style="margin-top: 30px;"><?= $listtemuan['rows'][0]['nama_pereview']?></p>
        </div>
    </div>

    <div style=""></div>

    <style>
        .tablebawah,
        .childtable {
            vertical-align: top;
            border: 1px solid;
            text-align: center;
        }

        .thead {
            text-align: center;
            border: 1px solid;
        }

        .tablebawah {
            width: 100%;
            border-collapse: collapse;


        }

        * {
            margin: 0px;
        }

        .parentp>p {
            margin: 0px;
        }
    </style>