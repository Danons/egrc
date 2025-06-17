<div style="margin-left: 10%;margin-right: 10%;">
    <div id="datatable">
        <table class="tableku" border="1">
            <?php
            if (!$header)
                $header = array(array(), array());
            ?>
            <?php if ($no_header && !$no_title) { ?>
                <tr>
                    <td rowspan="3">
                        <h5 style="text-align: center;">
                            <!-- <i class="home-logo" style="background-image: url(<?php echo base_url() ?>assets/images/kti-ori.png); width: 135px; height: 33px;"></i> -->
                            <i class="home-logo" style="background-image: url(<?php echo base_url() ?>assets/images/rima-b.png); width: 135px; height: 33px;"></i>
                        </h5>
                    </td>
                    <td rowspan="3">
                        <h4 style="padding:10px;text-align: center;">
                            <b><?= $page_title ?></b>
                        </h4>
                    </td>
                    <td> -
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="<?= count($header) ?>">
                    Halaman : 1 dari 1
                </td>
            </tr>
            <tr>
                <td colspan="<?= count($header) ?>">
                    <b>
                        Form: TSP_Rev1 <br>
                        Rencana Tujuan Sasaran Program
                    </b>
                </td>
            </tr>
        </table>
    </div>
    <br />
    <div class="row">
        <div class="col-sm-12">
            <div id="datatable">
                <table>
                    <tr>
                        <td>Ref.No.</td>
                        <td>:</td>
                        <td><?= $row['nomor_mitigasi'] ?></td>
                    </tr>
                    <tr>
                        <td>Kegiatan</td>
                        <td>:</td>
                        <td><?= $row['nama_kegiatan'] ?></td>
                    </tr>
                    <tr>
                        <td>Tujuan</td>
                        <td>:</td>
                        <td><?= $row['nama_mitigasi'] ?></td>
                    </tr>
                    <tr>
                        <td>Sasaran</td>
                        <td>:</td>
                        <td><?= $row['sasaran_mitigasi'] ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <br />
    <!-- <br /> -->
    <div class="row">
        <div class="col-sm-12">
            <div id="datatable"> Program
                <table class="tableku" border="1">
                    <tr>
                        <td style="text-align: center;" rowspan="2">No</td>
                        <td style="text-align: center;" rowspan="2">Program</td>
                        <!-- <td style="text-align: center;" rowspan="2">Tgl. Mulai</td> -->
                        <!-- <td style="text-align: center;" rowspan="2">Tgl. Selesai</td> -->
                        <td style="text-align: center;" colspan="<?= count(ListBulan()) ?>">Tahun <?=date('Y')?></td>
                        <td style="text-align: center;" rowspan="2">PIC</td>
                        <td style="text-align: center;" rowspan="2">Perkiraan Biaya (Rp)</td>
                    </tr>
                    <tr>
                        <?php foreach (ListBulan() as $b => $u) { ?>
                            <td style="background-color: yellow;"><?= $b ?></td>
                        <?php } ?>
                    </tr>
                    <?php $no = 1;
                    foreach ($row['mitigasi'] as $f) { ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= UI::createTextBox("nama", $f['nama'], '', '', $edited, 'form-control') ?></td>
                            <?php foreach (ListBulan() as $b => $u) {
                                if ($f['bulan'][$b])
                                    echo '<td style="background-color: yellow;"></td>';
                                else {
                                    echo '<td></td>';
                                }
                            } ?>
                            <!-- <td><?= UI::createTextBox("start_date", $f['start_date'], '', '', $edited, 'form-control datepicker') ?></td>
                            <td><?= UI::createTextBox("end_date", $f['end_date'], '', '', $edited, 'form-control datepicker') ?></td> -->
                            <td><?= UI::createSelect("penanggung_jawab", $penanggungjawabarr, $f['penanggung_jawab'], $edited, 'form-control ', "style='width:100%;'") ?></td>
                            <td><?= UI::createTextBox("biaya", rupiah($f['biaya']), '', '', $edited, 'form-control rupiah') ?></td>
                        </tr>
                    <?php $no++;
                    } ?>
                </table>
            </div>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-sm-12">Cilegon, <?= UI::createTextBox("start_date", date('Y-m-d'), '', '', $edited, 'form-control datepicker') ?>
        </div>
    </div><br />
    <div class="row">
        <div class="col-sm-12">
            <div id="datatable">
                <table class="tableku" border="1">
                    <tr>
                        <td style="text-align: center;">Dibuat oleh :</td>
                        <td style="text-align: center;">Diperiksa oleh:</td>
                        <td style="text-align: center;">Disetujui Oleh :</td>
                    </tr>
                    <tr>
                        <td style="padding: 10% 5% 0% 5%; text-align: center;"><ins><?= $rowheader['atasan0']['nama'] ?></ins><br><?= $rowheader['atasan0']['nama'] ?></td>
                        <td style="padding: 10% 5% 0% 5%; text-align: center;"><ins><?= $rowheader['atasan1']['nama'] ?></ins><br><?= $rowheader['atasan1']['jabatan'] ?></td>
                        <td style="padding: 10% 5% 0% 5%; text-align: center;"><ins><?= $rowheader['atasan2']['nama'] ?></ins><br><?= $rowheader['atasan2']['jabatan'] ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
</div>