<div class="row" style=" border-bottom: 2px solid black; width: 100%; padding: 20px 0px;">
    <h1 style="font-size: 13px; font-weight: lighter; margin: 0px;">PERUMDA AIR MINUM TIRTA RAHARJA</h1>
    <h1 style="font-size: 13px; font-weight: lighter; margin-bottom: 30px;">SATUAN PENGAWASAN INTERN</h1>
    <h1 style="font-size: 15px; text-align: center;">KONSEP TEMUAN DAN RENCANA TINDAK LANJUT</h1>
</div>
<div style=" width: 100%;">
    <div style="font-size: 15px; padding: 0px 10px; padding-top: 20px;">
        <table>

            <tr>
                <td>Nama Audit</td>
                <td>:</td>
                <td><?= $rowheader['nama'] ?></td>
            </tr>
            <tr>
                <td>Periode Auditi</td>
                <td>:</td>
                <td><?= $rowheader['tgl_mulai'] . "&nbsp;s/d&nbsp;" . $rowheader['tgl_selesai'] ?></td>
            </tr>
            <tr>
                <td>Nomor Surat Tugas</td>
                <td>:</td>
                <td><?= $rowheader['no_surat_tugas'] ?></td>
            </tr>
            <tr>
                <td>Nomor LHE</td>
                <td>:</td>
                <td><?= $listtemuan['rows'][0]? $listtemuan['rows'][0]['halaman_lhe'] : ''?></td>
            </tr>
            <tr>
                <td>Nomor Formulir Penyampaian</td>
                <td>:</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Disampaikan tanggal</td>
                <td>:</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Rapat Penutupan Audit</td>
                <td>:</td>
                <td>-</td>
            </tr>
        </table>

        <table class="tablebawah" style="width: 100%;margin-top: 20px;">
            <thead>
                <th class="thead  ">No</th>
                <th class=" thead">Kondisi</th>
                <th class=" thead">Kriteria</th>
                <th class=" thead">Sebab</th>
                <th class=" thead">Akibat</th>
                <th class=" thead">Rekomendasi</th>
                <th class=" thead">Rencana Tindak Lanjut</th>
                <th class=" thead">Komentar Auditi</th>
                <th class=" thead">Komentar Pengawas</th>
                <th class=" thead">Ket</th>
            </thead>
            <tbody>

                <?php
                $no = 0;
                foreach ($listtemuan['rows'] as $r) {
                    $no++
                ?>
                    <tr>
                        <td class="childtable"><?= $no ?></td>
                        <td class="childtable"><?= $r['kondisi'] ?></td>
                        <td class="childtable"><?= $r['kriteria'] ?></td>
                        <td class="childtable"><?= $r['sebab'] ?></td>
                        <td class="childtable"><?= $r['akibat'] ?></td>
                        <td class="childtable"><?= $r['rekomendasi'] ?></td>
                        <td class="childtable"><?= $r['rencana_tindakan_perbaikan'] ?></td>
                        <td class="childtable"><?= $r['komentar_auditi'] ?></td>
                        <td class="childtable"><?= $r['komentar_pengawas'] ?></td>
                        <td class="childtable"><?= $r['keterangan'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div style=" width: 100%; padding: 10px 10px; justify-content: space-around; display: flex;">
        <div style="display: flex; align-items: center; flex-direction: column;">
            <p>Pengendali Teknis,</p>
            <p style="margin-top: 30px;"><?= $rowheader['nama_pereview']?></p>
        </div>
        <div style="display: flex; align-items: center; flex-direction: column;">
            <p>Ketua Tim,</p>
            <p style="margin-top: 30px;"><?= $rowheader['nama_penanggung_jawab']?></p>
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