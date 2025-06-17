<div class="row" style=" width: 100%; padding: 5px 0px;">
    <div style="margin: 20px; border-bottom: 2px solid black;">
        <div class="" style="display: flex; justify-content: center; gap: 20px;">
            <img src="../../../assets/images/logo.png" alt="logo1" class="img-fluid" style="width: 115px;">
            <div style="display: flex; flex-direction: column; justify-content: center;">
                <h1 class="font-weight-bold" style="font-size: 20px; margin: 0;">PERUMDA AIR MINUM TIRTA RAHARJA</h1>
                <p class="" style="font-size: 15px;  margin: 0; font-weight: bold;">SATUAN PENGAWAS INTERN</p>
            </div>
        </div>
        <p class="text-center" style="font-size:  15px;">Dengan Pelayanan Prima Menjadi Perumda Air Minum Termaju, Dinamis, dan Berkelanjutan</p>
    </div>
</div>
<div class="row" style="margin-top: 20px; width: 100%;">
    <h1 class="text-center" style="font-size: 20px; margin: 0;">Notulen</h1>
    <table style="margin: 20px  ; ">
        <tr>
            <td style="font-size:  15px; vertical-align:top; width: 140px;">Rapat</td>
            <td style="font-size:  15px;vertical-align:top">:</td>
            <td style="font-size:  15px; vertical-align:top"><?= $row['nama_rapat']; ?></td>
        </tr>
        <tr>
            <td style="font-size:  15px; vertical-align:top">Hari / Tanggal</td>
            <td style="font-size:  15px; vertical-align:top">:</td>
            <td style="font-size:  15px; vertical-align:top"><?= $hari_ini . "&nbsp;" . Eng2Ind($row['tanggal_rapat']); ?></td>
        </tr>
        <tr>
            <td style="font-size:  15px; vertical-align:top">Waktu Rapat</td>
            <td style="font-size:  15px; vertical-align:top">:</td>
            <td style="font-size:  15px; vertical-align:top"><?= $row['waktu_rapat']; ?> WIB</td>
        </tr>
        <tr>
            <td style="font-size:  15px; vertical-align:top">Acara</td>
            <td style="font-size:  15px; vertical-align:top">:</td>
            <td style="font-size:  15px; vertical-align:top"><?= $resultAcaraArr ?></td>
        </tr>
        <tr>
            <td style="font-size:  15px; vertical-align:top">Pimpnan Rapat</td>
            <td style="font-size:  15px; vertical-align:top">:</td>
            <td style="font-size:  15px; vertical-align:top"><?= $row['pimpinan_rapat'] ?></td>
        </tr>
        <tr>
            <td style="font-size:  15px; vertical-align:top">Notulis</td>
            <td style="font-size:  15px; vertical-align:top">:</td>
            <td style="font-size:  15px; vertical-align:top"><?= $row['notulis'] ?></td>
        </tr>
        <tr>
            <td style="font-size:  15px; vertical-align:top">Peserta Rapat</td>
            <td style="font-size:  15px; vertical-align:top">:</td>
            <td style="font-size:  15px; vertical-align:top"><?= $resultPesertaArr ?></td>
        </tr>
        <tr>
            <td style="font-size:  15px; vertical-align:top">Kegiatan Rapat</td>
            <td style="font-size:  15px; vertical-align:top">:</td>
            <td style="font-size:  15px; vertical-align:top"><?= $row['kegiatan_rapat'] ?></td>
        </tr>
        <tr>
            <td style="font-size:  15px; vertical-align:top">Pembukaan</td>
            <td style="font-size:  15px; vertical-align:top">:</td>
            <td style="font-size:  15px; vertical-align:top" class="parentp"><?= $row['pembukaan'] ?></td>
        </tr>
        <tr>
            <td style="font-size:  15px; vertical-align:top">Pembahasan</td>
            <td style="font-size:  15px; vertical-align:top">:</td>
            <td style="font-size:  15px; vertical-align:top" class="parentp"><?= $row['pembahasan'] ?></td>
        </tr>
        <tr>
            <td style="font-size:  15px; vertical-align:top">Penutup</td>
            <td style="font-size:  15px; vertical-align:top">:</td>
            <td style="font-size:  15px; vertical-align:top" class="parentp"><?= $row['penutup'] ?></td>
        </tr>
        <tr>
            <td style="font-size:  15px; vertical-align:top">Kesimpulan</td>
            <td style="font-size:  15px; vertical-align:top">:</td>
            <td style="font-size:  15px; vertical-align:top" class="parentp"><?= $row['kesimpulan'] ?></td>
        </tr>
    </table>
    <div class="" style="width:100%; justify-content: center; display: flex;">
        <div class="parentp" style="flex-direction: column; width:100%; justify-content: center; display: flex; align-items: center;">
            <p class="text-center" style="font-size:  15px;">mengetahui,</p>
            <p class="text-center" style="font-size:  15px; margin-bottom: 40px;">Pimpinan Rapat</p>
            <p class="text-center" style="font-size:  15px;"><?= $row['pimpinan_rapat'] ?></p>
        </div>
        <div class="parentp" style="flex-direction: column; width:100%; justify-content: center; display: flex; align-items: center;">
            <p class="text-center" style="font-size:  15px;">Notulis,</p>
            <p class="text-center" style="font-size:  15px; margin-bottom: 40px;"><?= $jabatannotulis['nama'] ?></p>
            <p class="text-center" style="font-size:  15px;"><?= $row['notulis'] ?></p>
        </div>
    </div>
</div>

<style>
    * {
        margin: 0px;
    }

    .parentp>p {
        margin: 0px;
    }
</style>