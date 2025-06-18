<div class="row" style=" border-bottom: 2px solid black; width: 100%; padding: 20px 0px;">
    <h1 style="font-size: 15px; text-align: center;">LAPORAN TINDAK LANJUT TEMUAN AUDIT</h1>
    <h1 style="font-size: 15px; text-align:center;">Nomor Surat : <?= $rowheader['no_surat_tugas'] ?></h1>
</div>

<div style=" width: 100%;">


    <h1 style="font-size: 15px; padding: 20px 20px ">informasi umum</h1>

    <div style="width: 100%; display: flex; padding: 20px 20px;  font-size: 15px; row-gap: 20px;">
        <div style="width: 50%;">
            <table>
                <tr>
                    <td>Instansi/Unit</td>
                    <td>:</td>
                    <td><?= $rowheader['nama_unit'] ?></td>
                </tr>
                <tr>
                    <td>Bagian/Kegiatan yang diaudit</td>
                    <td>:</td>
                    <td>Distribusi dan ATR</td>
                </tr>
                <tr>
                    <td>No & Tgl Laporan Audit</td>
                    <td>:</td>
                    <td>12w3dr4/24 feb 2023</td>
                </tr>
                <tr>
                    <td>No. Formulir Penyampaian</td>
                    <td>:</td>
                    <td>1204</td>
                </tr>
                <tr>
                    <td>No. Temuan</td>
                    <td>:</td>
                    <td><?= $rowheader['judul_temuan'] ?></td>
                </tr>
                <tr>
                    <td>No. Rekomendasi</td>
                    <td>:</td>
                    <td>19364825</td>
                </tr>
            </table>
        </div>
        <div style="width: 50%;">
            <table style="margin-right: 10px;">
                <tr>
                    <td>Tanggal</td>
                    <td>:</td>
                    <td>12 feb 2023</td>
                </tr>
                <tr>
                    <td>Perihal</td>
                    <td>:</td>
                    <td>Pengadaan Bantuan Air Bersih</td>
                </tr>
                <tr>
                    <td>Eksemplar</td>
                    <td>:</td>
                    <td>test exemplar</td>
                </tr>
            </table>
        </div>
    </div>

</div>
<div style=" width: 100%; padding: 10px 20px;">
    <p>Tindak lanjut yang telah di lakukan :</p>
    <p>test</p>
</div>
<div style=" width: 100%; padding: 10px 20px;">
    <table>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td>08 feb 2023</td>
        </tr>
    </table>
</div>
<div style=" width: 100%; padding: 10px 20px; justify-content: space-around; display: flex;">
    <div style="display: flex; justify-content: center; flex-direction: column;">
        <p>Pimpinan Audit</p>
        <p style="margin-top: 30px;">Annisa Nurfauzi</p>
    </div>
    <div style="display: flex; justify-content: center; flex-direction: column;">
        <p>Pengendali Teknis</p>
        <p style="margin-top: 30px;">Annisa Nurfauzi</p>
    </div>
</div>

<div style=""></div>

<style>
    * {
        margin: 0px;
    }

    .parentp>p {
        margin: 0px;
    }
</style>