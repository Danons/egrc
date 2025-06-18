<div style="width: 100%; font-family: 'Times New Roman', Times, serif;">
    <div style=" width: 100%; padding: 20px 0px;">
        <h1 style="font-size: 13px; font-weight: lighter; margin: 0px;">PERUMDA AIR MINUM TIRTA RAHARJA</h1>
        <h1 style="font-size: 13px; font-weight: lighter; margin-bottom: 30px;">SATUAN PENGAWASAN INTERN</h1>
        <div style="display: flex; flex-direction: column; align-items: center;">
            <h1 style="font-size: 15px; text-align: center;">BERITA ACARA PEMUTAKHIRAN DATA</h1>
            <h1 style="font-size: 15px; text-align: center;">Temuan Audit Belum Ditindaklanjuti</h1>
            <h1 style="font-size: 15px; text-align: center;">Sampai dengan Lebih dari 1 bulan</h1>
            <h1 style="font-size: 15px; text-align: center;">Pada Instansti <?= $rowheader['nama'] ?></h1>
        </div>
        <div style=" width: 100%; padding: 30px 20px 10px 20px; font-size: 13px;">
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pada hari ini, ...., tanggal.... telah dilakukan pemutakhiran data temuan audit yang belum di tindaklanjuti bulan..... s.d. .... oleh auditi yang di hadiri oleh : </p>
            <?php
            // dpr($rowheader);
            // dpr($listtemuan);

                                                                            use Random\Engine;

            $data = array(
                0 => '....',
                1 => '....',
                2 => '....',
            );
            $no = 0;
            foreach ($data as $d) {
                $no++; ?>
                <p style="margin: 0px;"><?= $no . ' ' . $d; ?></p>
            <?php
            } ?>
            <p style="margin-top: 10px;">Dalam proses pemutakhiran ini telah dilakukan rekonsiliasi dan pemutakhiran data atas temuan audit SPI dengan hasil sebagai berikut :</p>
        </div>
    </div>
    <div style=" width: 100%; padding: 10px 20px 10px 20px; font-size: 13px;">
        <table class="tablebawah" style="width: 100%;">
            <thead>
                <tr>
                    <td class="thead" rowspan="2" style="width: 1px;">no</td>
                    <td class="thead" rowspan="2">Uraian</td>
                    <td class="thead" colspan="2">Temuan sebelum pemutakhiran</td>
                    <td class="thead" colspan="2">Tindak Lanjut</td>
                    <td class="thead" colspan="2">Temuan Sebelum Pemutakhiran</td>
                </tr>
                <tr>
                    <td class="thead">Jumlah Temuan</td>
                    <td class="thead">Nilai (Rp)</td>
                    <td class="thead">Jumlah Temuan</td>
                    <td class="thead">Nilai (Rp)</td>
                    <td class="thead">Jumlah Temuan</td>
                    <td class="thead">Nilai (Rp)</td>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 0;
                foreach ($listba as $v) { ?>
                    <tr>
                        <td><?= $no +1?></td>
                        <td class="" style="border: 1px solid;"><?= $v['uraian'] ?></td>
                        <td class="childtable"><?= $v['jumlah_monev'] ?$v['jumlah_monev']:0 ?></td>
                        <td class="childtable"><?= $v['nilai_kerugian_monev'] ? $v['nilai_kerugian_monev'] : 0?></td>
                        <td class="childtable"><?= $v['jumlah_close'] ? $v['jumlah_close'] : 0 ?></td>
                        <td class="childtable"><?= $v['nilai_kerugian_close'] ? $v['nilai_kerugian_close'] : 0?></td>
                        <td class="childtable"><?= $v['jumlah'] ?  $v['jumlah'] : 0?></td>
                        <td class="childtable"><?= $v['nilai_jumlah'] ? $v['nilai_jumlah'] : 0?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div style=" width: 100%; padding: 10px 20px 10px 20px; font-size: 13px;">
        <p>Rincian temuan per LHE terdapat dalam lampiran berita acara ini dan merupakan satu kesatuan yang tidak dapat dipisahkan dengan Berita Acara ini. </p>
        <p>Demikian berita acara ini dibuat dengan sebenarnya untuk digunakan sebagaimana mestinya.</p>
    </div>
    <div style=" width: 100%; padding: 10px 10px; justify-content: space-around; display: flex;">
        <div style="display: flex; align-items: center; flex-direction: column;">
        <!-- risk owner dari unit tsb -->
            <p>Pimpinan Auditi,</p>
            <p style="margin-top: 55px;"><?= $pimpinan_auditi?></p>
        </div>
        <div style="display: flex; align-items: center; flex-direction: column;">
        <!-- tanyaa -->
            <p> <?= Eng2Ind($tgl_ttd)?></p>
            <p>Manajer Senior SPI,</p>
            <p style="margin-top: 30px;"><?= $manajer_spi?></p>
        </div>
    </div>
</div>
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
</style>