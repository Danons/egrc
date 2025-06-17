<div style="margin: 0px 20px; width: 100%;">
    <div style="width: 100%; ">
        <div style="margin: 30px 0px;  width: 100%; display: flex; align-items: center; flex-direction: column;">
            <h1 style="font-size: 12px;">SURAT TUGAS</h1>
            <h1 style="font-size: 12px;">Nomor &#58; <?= $row['nomor_surat'] ?></h1>
        </div>
        <p style="font-size: 12px;">Manajer Senior Satuan Pengawasan Intern Perusahaan Umum Daerah Air Minum Tirta Raharja dengan ini : </p>
        <h1 style="font-size: 12px; text-align: center;">MENUGASKAN</h1>
        <div>
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th class="thead">NO</th>
                        <th class="thead">NAMA</th>
                        <th class="thead">NIPP</th>
                        <th class="thead">JABATAN/PERAN</th>
                    </tr>
                </thead>
                <?php  $no = 1;?>
                <tr>
                    <td class="childtable"><?= $no++?></td>
                    <td class="childtable"><?= $rows['nama_penyusun']?></td>
                    <td class="childtable"><?= $rows['nipp_penyusun']?></td>
                    <td class="childtable"><?= $rows['nama_jabatan_penyusun']?>/Koordinator</td>
                </tr>
                <tr>
                    <td class="childtable"><?= $no++?></td>
                    <td class="childtable"><?= $rows['nama_pereview']?></td>
                    <td class="childtable"><?= $rows['nipp_pereview']?></td>
                    <td class="childtable"><?= $rows['nama_jabatan_pereview']?>/Pengendali Teknis</td>
                </tr>
                <tr>
                    <td class="childtable"><?= $no++?></td>
                    <td class="childtable"><?= $rows['nama_penanggung_jawab']?></td>
                    <td class="childtable"><?= $rows['nipp_penanggung_jawab']?></td>
                    <td class="childtable"><?= $rows['nama_jabatan_penanggung_jawab']?>/Ketua</td>
                </tr>
                <?php
               
                foreach ($row['petugas'] as $p) {
                    ?>
                    <tr>
                        <td class="childtable" style="width: 5px;"><?=  $no++ ?></td>
                        <td class="childtable" style="width: 40%;"><?= $p['nama'] ?></td>
                        <td class="childtable" style="width: 20%;"><?= $p['nipp'] ?></td>
                        <td class="childtable" style="width: 40%;"><?= $p['nama_jabatan'] ?>/Auditor</td>
                    </tr>
                <?php } ?>
            </table>
            <p style="margin-top: 12px;">untuk Membuat<?= "&nbsp;" . $row['deskripsi'] . "&nbsp;"; ?>Penugasan ini dilaksanakan selama<?= ("&nbsp;" . (strtotime($row['periode_pemeriksaan_selesai']) - strtotime($row['periode_pemeriksaan_mulai'])) / 86400) . "&nbsp;" ?>hari kerja terhitung mulai tanggal<?= "&nbsp;" . Eng2Ind($row['periode_pemeriksaan_mulai'], false) ?></p>
            <p style="margin-top: 12px;">Segala pengeluaran akibat dikeluarkannya Surat Tugas ini, dibebankan kepada anggaran operasional Perusahaan Umum Daerah Air Minum Tirta Raharja.</p>
            <div style=" width: 100%; padding: 10px 40px; justify-content: flex-end; display: flex;">
                <div style="display: flex; justify-content: center; flex-direction: column; text-align: center;">
                    <p style="margin: 0px;">Di tetapkan di Cimahi </p>
                    <p style="margin: 0px;">Pada tanggal<?= "&nbsp;" . $row['tempat'] . ',&nbsp;' . Eng2Ind($row['tanggal_surat'], false) ?></p>
                    <p>MANAJER SENIOR SPI,,</p>
                    <p style="margin-top: 30px;"><?= $manajerspi ?></p>
                </div>
            </div>
            <p style="margin: 20px 0px 0px 0px ;">Tembusan di sampaikan kepada yth :</p>
            <p>Direktur Utama Perumda Air Minum Tirta Raharja sebagai laporan.</p>
        </div>
    </div>
    <p style="margin: 10px 0px 0px 0px ;">Layanan Pengaduan/Hotline:</p>
    <p>Apabila Pengawas Unit Kerja SPI Perumda Air Minum Tirta Raharja menerima/meminta gratifikasi dan suap, dapat dilaporkan melalui mekanisme penyampaian pengaduan pada wbs@tirtaraharja.co.id.</p>
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
</style>