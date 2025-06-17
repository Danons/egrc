<div style=" width: 100%; padding: 5px 0px; margin: 0px 75px; ">
    <div style="margin: 20px; border-bottom: 2px solid black;">
        <div class="" style="display: flex; justify-content: center; gap: 20px; ">
            <img src="../../../assets/images/logo.png" alt="logo1" class="img-fluid" style="width: 115px;">
            <div style="display: flex; flex-direction: column; justify-content: center;">
                <h1 class="font-weight-bold" style="font-size: 20px; margin: 0;">PERUMDA AIR MINUM TIRTA RAHARJA</h1>
                <p class="" style="font-size: 15px;  margin: 0; font-weight: bold;">SATUAN PENGAWAS INTERN</p>
            </div>
        </div>
        <p class="text-center" style="font-size: 15px;">Dengan Pelayanan Prima Menjadi Perumda Air Minum Termaju, Dinamis, dan Berkelanjutan</p>
    </div>
</div>
<div class="row" style=" width: 100%;">
    <div style="width: 100%;">
        <h1 style="font-weight: bold; text-align: center; font-size: 15px;">
            HASIL MONITORING</h1>
        <table style="margin: 20px  ;">
            <tr>
                <td style="font-size: 15px; vertical-align:top">Kepada</td>
                <td style="font-size: 15px;vertical-align:top">:</td>
                <td style="font-size: 15px; vertical-align:top"><?= $row['kepada']; ?></td>
            </tr>
            <tr>
                <td style="font-size: 15px; vertical-align:top">Dari</td>
                <td style="font-size: 15px; vertical-align:top">:</td>
                <td style="font-size: 15px; vertical-align:top"><?= $row['dari']; ?></td>
            </tr>
            <tr>
                <td style="font-size: 15px; vertical-align:top">Tanggal</td>
                <td style="font-size: 15px;vertical-align:top">:</td>
                <td style="font-size: 15px; vertical-align:top"><?= $hari_ini . "&nbsp;" . Eng2Ind($row['tanggal']) ?></td>
            </tr>
            <tr>
                <td style="font-size: 15px; vertical-align:top">Nomor</td>
                <td style="font-size: 15px; vertical-align:top">:</td>
                <td style="font-size: 15px; vertical-align:top"><?= $row['nomor']; ?></td>
            </tr>
        </table>
    </div>

    <div class="parentp" style="margin: 20px ; padding-left: 3px; font-size: 15px;">
        <p style="font-weight: bolder;">A. DASAR TUGAS</p>
        <?= $row['dasar_tugas'] ?>
        <p style="font-weight: bolder; margin-top: 5px;">B. DASAR HUKUM</p>
        <?= $row['dasar_hukum'] ?>
        <p style="font-weight: bolder; margin-top: 5px;">C. DATA DAN FAKTA</p>
        <?= $row['data_fakta'] ?>
        <p style="font-weight: bolder; margin-top: 5px;">D. PEMBAHASAN</p>
        <?= $row['pembahasan'] ?>
        <p style="font-weight: bolder; margin-top: 5px;">E. KESIMPULAN DAN USULAN SARAN TINDAK</p>
        <?= $row['kesimpulan'] ?>

    </div>

    <div style=" display: flex; justify-content: end; width: 100%;">
        <div style="display: flex; flex-direction: column; align-items: center; margin: 20px  ;">
            <p style=" font-size: 15px;">disusun oleh</p>
            <p style=" font-size: 15px;"><?= $row['penyusun']['jabatan'] ?></p>
            <p style=" font-size: 15px; margin-top: 30px;"><?= $row['penyusun']['nama'] ?></p>
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