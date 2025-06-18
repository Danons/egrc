<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Area of Improvement</th>
            <th>Usulan Saran</th>
            <th>PIC</th>
            <th>Dokumen</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1;
        if ($rows)
            foreach ($rows as $r) { ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $r['oai'] ?></td>
                <td><?= $r['saran'] ?></td>
                <td><?= $r['pic'] ?></td>
                <td>
                    <?php
                    if ($r['id_dokumen']) {
                        echo "<a href='" . site_url("panelbackend/dokumen/detail/" . $r['id_dokumen']) . "'>" . $r['nama_dokumen'] . "</a>";
                    } else {
                        // dpr($r['quisioner']);
                    ?>

                        <?php
                        if ($r['quisioner']['k'])
                            foreach ($r['quisioner']['k'] as $r1) { ?>
                            <?= $r1['pertanyaan'] ?><br />
                            <?php if ($r1['1sampai5']) { ?>
                                <table>
                                    <tr>
                                        <th>&nbsp;Sangat Kurang</th>
                                        <th>&nbsp;Kurang</th>
                                        <th>&nbsp;Cukup</th>
                                        <th>&nbsp;Baik</th>
                                        <th>&nbsp;Sangat Baik</th>
                                    </tr>
                                    <tr>
                                        <td> &nbsp;<?= $r1['1sampai5'][1] / $r1['total'] * 100 ?>%</td>
                                        <td>&nbsp;<?= $r1['1sampai5'][2] / $r1['total'] * 100 ?>%</td>
                                        <td>&nbsp;<?= $r1['1sampai5'][3] / $r1['total'] * 100 ?>%</td>
                                        <td>&nbsp;<?= $r1['1sampai5'][4] / $r1['total'] * 100 ?>%</td>
                                        <td>&nbsp;<?= $r1['1sampai5'][5] / $r1['total'] * 100 ?>%</td>
                                    </tr>
                                    <!-- <tr>
                                        <td>&nbsp;<?= (int)$r1['1sampai5'][1] ?>/<?= $r1['total'] ?></td>
                                        <td>&nbsp;<?= (int)$r1['1sampai5'][2] ?>/<?= $r1['total'] ?></td>
                                        <td>&nbsp;<?= (int)$r1['1sampai5'][3] ?>/<?= $r1['total'] ?></td>
                                        <td>&nbsp;<?= (int)$r1['1sampai5'][4] ?>/<?= $r1['total'] ?></td>
                                        <td>&nbsp;<?= (int)$r1['1sampai5'][5] ?>/<?= $r1['total'] ?></td>
                                    </tr> -->
                                </table>
                            <?php } ?>

                            <?php if ($r1['yatidak']) { ?>
                                <table>
                                    <tr>
                                        <th>&nbsp;Tidak</th>
                                        <th>&nbsp;Ya</th>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;<?= $r1['1sampai5'][1] / $r1['total'] * 100 ?>%</td>
                                        <td>&nbsp;<?= $r1['1sampai5'][5] / $r1['total'] * 100 ?>%</td>
                                    </tr>
                                    <!-- <tr>
                                        <td>&nbsp;<?= (int)$r1['1sampai5'][1] ?>/<?= $r1['total'] ?></td>
                                        <td>&nbsp;<?= (int)$r1['1sampai5'][5] ?>/<?= $r1['total'] ?></td>
                                    </tr> -->
                                </table>
                            <?php } ?>
                            <hr />
                        <?php }

                        if ($r['quisioner']['w'])
                            foreach ($r['quisioner']['w'] as $r1) { ?>
                            <?= $r1['pertanyaan'] ?><br />
                            <ol>
                                <?php foreach ($r1['jawaban'] as $jawaban) { ?>
                                    <li><?= $jawaban ?></li>
                                <?php } ?>
                            </ol>
                            <hr />
                    <?php }
                    }
                    ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>