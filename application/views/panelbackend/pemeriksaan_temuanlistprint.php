<h4>
    <div>SATUAN PENGAWASAN INTERN</div>
    <div>PERUM JASA TIRTA II</div>
</h4>
<br />
<h4 style="text-align: center;">
    <div style="text-decoration: underline;"><?= $label_desc ?></div>
    <div>Nomor : NO.<?= $label ?>. &nbsp;&nbsp; /SPI.DU/PW/&nbsp;&nbsp;&nbsp;&nbsp;/<?=date("Y")?></div>
</h4>
<br />
<table>
    <tr>
        <td>Objek Pemeriksaan</td>
        <td>&nbsp;:&nbsp;</td>
        <td><?= $unitarr[$rowheader['id_unit']] ?></td>
    </tr>
    <tr>
        <td>Kegiatan yang Diperiksa</td>
        <td>&nbsp;:&nbsp;</td>
        <td><?= $rowheader['nama'] ?></td>
    </tr>
    <tr>
        <td>Periode Objek Pemeriksaan</td>
        <td>&nbsp;:&nbsp;</td>
        <td><?= Eng2Ind($rowheader['tgl_mulai']) . ' sd ' . Eng2Ind($rowheader['tgl_selesai']) ?></td>
    </tr>
</table>
<br />

<div style="text-align:justify">
    Berdasarkan Surat Penugasan Direktur Utama Nomor : <?= $rowheader0['nomor_surat'] ?>
    tanggal <?= Eng2Ind($rowheader0['tanggal_surat']) ?>
    tentang <?= $rowheader0['deskripsi'] ?>
    dan Surat Perintah Tugas Pemeriksaan (SPTP) Nomor : <?= $rowheader['nomor_stp'] ?>
    tanggal <?= Eng2Ind($rowheader['tanggal_sptp']) ?> ,
    TIM SPI telah melaksanakan Pemeriksaan selama <?= $hari = trim(str_replace('hari', '', DateDiff($rowheader['tgl_selesai'], $rowheader['tgl_mulai']))) ?> (<?= terbilang($hari) ?>)
    hari kerja sejak tanggal <?= Eng2Ind($rowheader['tgl_mulai']) ?> s.d <?= Eng2Ind($rowheader['tgl_selesai']) ?> dengan hasil pemeriksaan sebagai berikut :
</div>
<?php
if ($listtemuan)
    foreach ($listtemuan as $bidang => $rs) { ?>
    <br />
    <b><?= strtoupper($bidang) ?></b>
    <br />
    <br />
    <ol class="ol">

        <?php
        if ($rs)
            foreach ($rs as $r) { ?>

            <li>
                <b><?= $r['judul_temuan'] ?></b>
                <br />

                <b>Kondisi :</b>
                <br />
                <?= $r['kondisi'] ?>

                <?php if ($r['jenis_temuan'] == "MAJOR" || $r['jenis_temuan'] == "Temuan") { ?>

                    <b>Kriteria :</b>
                    <br />
                    <?= $r['kriteria'] ?>

                    <b>Sebab :</b>
                    <br />
                    <?= $r['sebab'] ?>

                    <b>Akibat :</b>
                    <br />
                    <?= $r['akibat'] ?>

                    <b>Rekomendasi :</b>
                    <br />
                    <?= $r['rekomendasi'] ?>
                <?php } else { ?>
                    <b>Saran :</b>
                    <br />
                    <?= $r['rekomendasi'] ?>
                <?php } ?>
            </li>
        <?php } ?>
    </ol>
<?php } ?>
<br />
<br />
<div style="text-align: right;font-weight:bold">
    Jatiluhur, <?= Eng2Ind(date("Y-m-d")) ?><br />
    Penanggung Jawab Tim Audit,
    <br />
    <br />
    <br />
    <br />
    <span style="text-decoration: underline;"><?= $penanggung_jawab['nama'] ?></span><br />
    <?= $penanggung_jawab['jabatan'] ?>
</div>
<style>
    ol,
    ul {
        padding-inline-start: 15px;
        text-align: left;
    }

    ol.ol>li::marker {
        font-weight: bold;
    }
</style>