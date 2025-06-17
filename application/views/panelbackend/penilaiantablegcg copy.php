<table class="table table-bordered">
    <thead>
        <tr>
            <!-- <th colspan="2">Aspek</th> -->
            <th colspan="2">Indikator</th>
            <th>Bobot Par</th>
            <th colspan="2">Paramater</th>
            <th colspan="2">Faktor-faktor yang Diuji Kesesuaiannya (FUK)</th>
            <th colspan="2">Unsur Pemenuhan (UP)</th>

            <th>Input/ Upload</th>
            <?php if ($is_admin) { ?>
                <!-- <th>Aktif</th> -->
            <?php } ?>
            <?php if (!($status1 !== '' && $status1 !== null)) { ?>
                <th>Kesimpulan Keseluruhan</th>
                <th width="10px">Skor Rata-rata</th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php
        function warnaskor($skor)
        {
            if ($skor == null)
                return "#fff";

            if ($skor >= 1)
                return "green; color:white";
            elseif ($skor >= 0.7)
                return "#80ff00";
            elseif ($skor >= 0.5)
                return "yellow";
            elseif ($skor > 0.2)
                return "#ff8000";
            else
                return "#ff0000";
        }
        $warnaarr = array(
            '100' => '#ff4545',
            '1' => '#15c115',
            '2' => 'yellow',
        );
        $total_kriteria = 0;
        $total_nilaiarr = array();
        if (isset($arearr))
            foreach ($arearr as $r) {
                $total_nilaiareaarr = array();
                $total_kriteria++;
                $r1 = @$r['sub1'][0];
                $r2 = @$r1['sub2'][0];
                $r3 = @$r2['sub3'][0];
                $r4 = @$r3['sub4'][0];
        ?>
            <tr>
                <td>
                    <b><?= nl2br($r['kode']); ?></b>
                </td>
                <td colspan="11">
                    <b><?= nl2br($r['nama']); ?></b>
                </td>
            </tr>
            <tr>
                <td rowspan="<?= $r1['rowspan'] ?>">
                    <b><?= nl2br($r1['kode']); ?></b>
                </td>
                <td rowspan="<?= $r1['rowspan'] ?>">
                    <?= nl2br($r1['nama']); ?>
                </td>

                <td rowspan="<?= $r2['rowspan'] ?>">
                    <b><?= rupiah($r2['bobot']); ?></b>
                </td>
                <td rowspan="<?= $r2['rowspan'] ?>">
                    <b><?= nl2br($r2['kode']); ?></b>
                </td>
                <td rowspan="<?= $r2['rowspan'] ?>">
                    <?= nl2br($r2['nama']); ?>
                </td>

                <td rowspan="<?= $r3['rowspan'] ?>">
                    <b><?= nl2br($r3['kode']); ?></b>
                </td>
                <td rowspan="<?= $r3['rowspan'] ?>">
                    <?= nl2br($r3['nama']); ?>
                </td>

                <td>
                    <b><?= nl2br($r4['kode']); ?></b>
                </td>
                <td style="<?php if ($is_admin && $r4['is_attr']) { ?>background:#00cdff<?php } ?>">
                    <?= nl2br($r4['nama']); ?>
                    <?php if ($r4['link']) { ?>
                        <div class="btn-group">
                            <a href="javascript:void(0)" data-toggle="dropdown" data-close-others="true" aria-expanded="false"><i class="glyphicon glyphicon-link"></i></a>
                            <ul class="dropdown-menu-v2" style="padding: 10px">
                                <?php foreach ($r4['link'] as $rl) { ?>
                                    <li style="color:#fff"><b><?= $rl['kode'] ?></b> <?= $rl['nama'] ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>
                </td>
                <?php if (!$r4['id_unit']) { ?>
                    <td <?= ($is_admin) ? 'colspan="4"' : "colspan='3'" ?>></td>
                <?php } else { ?>
                    <td align="center" data-bs-toggle='tooltip' title="PIC : <?= $mtunitarr[$r4['id_unit']] ?>" style='background-color: <?= $warnaarr[$r4['belum']] ?>'>
                        <button type="button" class="btn btn-sm btn-primary" style="padding:0 .25rem; margin:0px" onclick="detail(<?= $r4['id_penilaian_periode'] ?>,<?= $r4['id_kriteria'] ?>)">
                            <i class="bi bi-list-check"></i>
                        </button>
                        <b><?= $statusarr[$r4['belum']] ?></b>
                    </td>
                    <?php if ($is_admin) {
                    ?>
                        <!-- <td align="center">
                            <?= UI::createCheckBox('is_aktif[' . $r4['id_penilaian_periode'] . "]", 1, $r4['is_aktif_penilaian'], '', true, "style='display:inline'", "onClick='change_aktif(this, {$r4['id_penilaian_periode']})'") ?>
                        </td> -->
                    <?php } ?>
                    <?php
                    if ($r1['is_aktif_penilaian']) {
                        $total_nilaiarr[] = (float)$r1['nilai_sub1_area'];
                        $total_nilaiareaarr[] = (float)$r1['nilai_sub1_area'];
                    }
                    ?>
                    <!-- <td rowspan="<?= $r1['rowspan'] ?>" align="center">
                    <b><?= round($r1['nilai_sub1_area'], 2); ?></b>
                </td> -->
                    <td>
                        <b><?= $r4['kesimpulan']; ?></b>
                    </td>
                    <td align="center" style="background: <?= warnaskor($r4['skor']) ?>;">
                        <b><?= round($r4['skor'], 2); ?></b>
                    </td>
                <?php } ?>
            </tr>
            <?php
                if ($r['sub1'])
                    foreach ($r['sub1'] as $i1 => $r1) {
                        $r2 = @$r1['sub2'][0];
                        $r3 = @$r2['sub3'][0];
                        $r4 = @$r3['sub4'][0];
                        if ($i1 > 0) {
                            $total_kriteria++;
            ?>
                    <tr>
                        <td rowspan="<?= $r1['rowspan'] ?>">
                            <b><?= nl2br($r1['kode']); ?></b>
                        </td>
                        <td rowspan="<?= $r1['rowspan'] ?>">
                            <?= nl2br($r1['nama']); ?>
                        </td>

                        <td rowspan="<?= $r2['rowspan'] ?>">
                            <b><?= rupiah($r2['bobot']); ?></b>
                        </td>
                        <td rowspan="<?= $r2['rowspan'] ?>">
                            <b><?= nl2br($r2['kode']); ?></b>
                        </td>
                        <td rowspan="<?= $r2['rowspan'] ?>">
                            <?= nl2br($r2['nama']); ?>
                        </td>

                        <td rowspan="<?= $r3['rowspan'] ?>">
                            <b><?= nl2br($r3['kode']); ?></b>
                        </td>
                        <td rowspan="<?= $r3['rowspan'] ?>">
                            <?= nl2br($r3['nama']); ?>
                        </td>

                        <td>
                            <b><?= nl2br($r4['kode']); ?></b>
                        </td>
                        <td style="<?php if ($is_admin && $r4['is_attr']) { ?>background:#00cdff<?php } ?>">
                            <?= nl2br($r4['nama']); ?>
                            <?php if ($r4['link']) { ?>
                                <div class="btn-group">
                                    <a href="javascript:void(0)" data-toggle="dropdown" data-close-others="true" aria-expanded="false"><i class="glyphicon glyphicon-link"></i></a>
                                    <ul class="dropdown-menu-v2" style="padding: 10px">
                                        <?php foreach ($r4['link'] as $rl) { ?>
                                            <li style="color:#fff"><b><?= $rl['kode'] ?></b> <?= $rl['nama'] ?></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            <?php } ?>
                        </td>
                        <?php if (!$r4['id_unit']) { ?>
                            <td <?= ($is_admin) ? 'colspan="4"' : "colspan='3'" ?>></td>
                        <?php } else { ?>
                            <td align="center" data-bs-toggle='tooltip' title="PIC : <?= $mtunitarr[$r4['id_unit']] ?>" style='background-color: <?= $warnaarr[$r4['belum']] ?>'>
                                <button type="button" class="btn btn-sm btn-primary" style="padding:0 .25rem; margin:0px" onclick="detail(<?= $r4['id_penilaian_periode'] ?>,<?= $r4['id_kriteria'] ?>)">
                                    <i class="bi bi-list-check"></i>
                                </button>
                                <b><?= $statusarr[$r4['belum']] ?></b>
                            </td>
                            <?php if ($is_admin) {
                            ?>
                                <!-- <td align="center">
                                    <?= UI::createCheckBox('is_aktif[' . $r4['id_penilaian_periode'] . "]", 1, $r4['is_aktif_penilaian'], '', true, "style='display:inline'", "onClick='change_aktif(this, {$r4['id_penilaian_periode']})'") ?>
                                </td> -->
                            <?php } ?>
                            <?php
                                if ($r1['is_aktif_penilaian']) {
                                    $total_nilaiarr[] = (float)$r1['nilai_sub1_area'];
                                    $total_nilaiareaarr[] = (float)$r1['nilai_sub1_area'];
                                }
                            ?>
                            <!-- <td rowspan="<?= $r1['rowspan'] ?>" align="center">
                            <b><?= round($r1['nilai_sub1_area'], 2); ?></b>
                        </td> -->
                            <td>
                                <b><?= $r4['kesimpulan']; ?></b>
                            </td>
                            <td align="center" style="background: <?= warnaskor($r4['skor']) ?>;">
                                <b><?= round($r4['skor'], 2); ?></b>
                            </td>
                        <?php } ?>
                    </tr>
                    <?php
                        }
                        if (isset($r1['sub2']))
                            foreach ($r1['sub2'] as $i2 => $r2) {
                                $r3 = @$r2['sub3'][0];
                                $r4 = @$r3['sub4'][0];
                                if ($i2 > 0) {
                                    $total_kriteria++;
                    ?>
                        <tr>
                            <td rowspan="<?= $r2['rowspan'] ?>">
                                <b><?= rupiah($r2['bobot']); ?></b>
                            </td>
                            <td rowspan="<?= $r2['rowspan'] ?>">
                                <b><?= nl2br($r2['kode']); ?></b>
                            </td>
                            <td rowspan="<?= $r2['rowspan'] ?>">
                                <?= nl2br($r2['nama']); ?>
                            </td>

                            <td rowspan="<?= $r3['rowspan'] ?>">
                                <b><?= nl2br($r3['kode']); ?></b>
                            </td>
                            <td rowspan="<?= $r3['rowspan'] ?>">
                                <?= nl2br($r3['nama']); ?>
                            </td>

                            <td>
                                <b><?= nl2br($r4['kode']); ?></b>
                            </td>
                            <td style="<?php if ($is_admin && $r4['is_attr']) { ?>background:#00cdff<?php } ?>">
                                <?= nl2br($r4['nama']); ?>
                                <?php if ($r4['link']) { ?>
                                    <div class="btn-group">
                                        <a href="javascript:void(0)" data-toggle="dropdown" data-close-others="true" aria-expanded="false"><i class="glyphicon glyphicon-link"></i></a>
                                        <ul class="dropdown-menu-v2" style="padding: 10px">
                                            <?php foreach ($r4['link'] as $rl) { ?>
                                                <li style="color:#fff"><b><?= $rl['kode'] ?></b> <?= $rl['nama'] ?></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                <?php } ?>
                            </td>
                            <?php if (!$r4['id_unit']) { ?>
                                <td <?= ($is_admin) ? 'colspan="4"' : "colspan='3'" ?>></td>
                            <?php } else { ?>
                                <td align="center" data-bs-toggle='tooltip' title="PIC : <?= $mtunitarr[$r4['id_unit']] ?>" style='background-color: <?= $warnaarr[$r4['belum']] ?>'>
                                    <button type="button" class="btn btn-sm btn-primary" style="padding:0 .25rem; margin:0px" onclick="detail(<?= $r4['id_penilaian_periode'] ?>,<?= $r4['id_kriteria'] ?>)">
                                        <i class="bi bi-list-check"></i>
                                    </button>
                                    <b><?= $statusarr[$r4['belum']] ?></b>
                                </td>
                                <?php if ($is_admin) {
                                ?>
                                    <!-- <td align="center">
                                        <?= UI::createCheckBox('is_aktif[' . $r4['id_penilaian_periode'] . "]", 1, $r4['is_aktif_penilaian'], '', true, "style='display:inline'", "onClick='change_aktif(this, {$r4['id_penilaian_periode']})'") ?>
                                    </td> -->
                                <?php } ?>
                                <td>
                                    <b><?= $r4['kesimpulan']; ?></b>
                                </td>
                                <td align="center" style="background: <?= warnaskor($r4['skor']) ?>;">
                                    <b><?= round($r4['skor'], 2); ?></b>
                                </td>
                            <?php } ?>
                        </tr>
                        <?php
                                }
                                if ($r2['sub3'])
                                    foreach ($r2['sub3'] as $i3 => $r3) {
                                        $r4 = @$r3['sub4'][0];
                                        if ($i3 > 0) {
                        ?>
                            <tr>

                                <td rowspan="<?= $r3['rowspan'] ?>">
                                    <b><?= nl2br($r3['kode']); ?></b>
                                </td>
                                <td rowspan="<?= $r3['rowspan'] ?>">
                                    <?= nl2br($r3['nama']); ?>
                                </td>

                                <td>
                                    <b><?= nl2br($r4['kode']); ?></b>
                                </td>
                                <td style="<?php if ($is_admin && $r4['is_attr']) { ?>background:#00cdff<?php } ?>">
                                    <?= nl2br($r4['nama']); ?>
                                    <?php if ($r4['link']) { ?>
                                        <div class="btn-group">
                                            <a href="javascript:void(0)" data-toggle="dropdown" data-close-others="true" aria-expanded="false"><i class="glyphicon glyphicon-link"></i></a>
                                            <ul class="dropdown-menu-v2" style="padding: 10px">
                                                <?php foreach ($r4['link'] as $rl) { ?>
                                                    <li style="color:#fff"><b><?= $rl['kode'] ?></b> <?= $rl['nama'] ?></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                </td>
                                <?php if (!$r4['id_unit']) { ?>
                                    <td <?= ($is_admin) ? 'colspan="4"' : "colspan='3'" ?>></td>
                                <?php } else { ?>
                                    <td align="center" data-bs-toggle='tooltip' title="PIC : <?= $mtunitarr[$r4['id_unit']] ?>" style='background-color: <?= $warnaarr[$r4['belum']] ?>'>
                                        <button type="button" class="btn btn-sm btn-primary" style="padding:0 .25rem; margin:0px" onclick="detail(<?= $r4['id_penilaian_periode'] ?>,<?= $r4['id_kriteria'] ?>)">
                                            <i class="bi bi-list-check"></i>
                                        </button>
                                        <b><?= $statusarr[$r4['belum']] ?></b>
                                    </td>
                                    <?php if ($is_admin) {
                                    ?>
                                        <!-- <td align="center">
                                            <?= UI::createCheckBox('is_aktif[' . $r4['id_penilaian_periode'] . "]", 1, $r4['is_aktif_penilaian'], '', true, "style='display:inline'", "onClick='change_aktif(this, {$r4['id_penilaian_periode']})'") ?>
                                        </td> -->
                                    <?php } ?>
                                    <td>
                                        <b><?= $r4['kesimpulan']; ?></b>
                                    </td>
                                    <td align="center" style="background: <?= warnaskor($r4['skor']) ?>;">
                                        <b><?= round($r4['skor'], 2); ?></b>
                                    </td>
                                <?php } ?>
                            </tr>
                            <?php   }
                                        if ($r3['sub4'])
                                            foreach ($r3['sub4'] as $i4 => $r4) {
                                                if ($i4 > 0) {
                            ?>
                                <tr>


                                    <td>
                                        <b><?= nl2br($r4['kode']); ?></b>
                                    </td>
                                    <td style="<?php if ($is_admin && $r4['is_attr']) { ?>background:#00cdff<?php } ?>">
                                        <?= nl2br($r4['nama']); ?>
                                        <?php if ($r4['link']) { ?>
                                            <div class="btn-group">
                                                <a href="javascript:void(0)" data-toggle="dropdown" data-close-others="true" aria-expanded="false"><i class="glyphicon glyphicon-link"></i></a>
                                                <ul class="dropdown-menu-v2" style="padding: 10px">
                                                    <?php foreach ($r4['link'] as $rl) { ?>
                                                        <li style="color:#fff"><b><?= $rl['kode'] ?></b> <?= $rl['nama'] ?></li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        <?php } ?>
                                    </td>
                                    <?php if (!$r4['id_unit']) { ?>
                                        <td <?= ($is_admin) ? 'colspan="4"' : "colspan='3'" ?>></td>
                                    <?php } else { ?>
                                        <td align="center" data-bs-toggle='tooltip' title="PIC : <?= $mtunitarr[$r4['id_unit']] ?>" style='background-color: <?= $warnaarr[$r4['belum']] ?>'>
                                            <button type="button" class="btn btn-sm btn-primary" style="padding:0 .25rem; margin:0px" onclick="detail(<?= $r4['id_penilaian_periode'] ?>,<?= $r4['id_kriteria'] ?>)">
                                                <i class="bi bi-list-check"></i>
                                            </button>
                                            <b><?= $statusarr[$r4['belum']] ?></b>
                                        </td>
                                        <?php if ($is_admin) {
                                        ?>
                                            <!-- <td align="center">
                                                <?= UI::createCheckBox('is_aktif[' . $r4['id_penilaian_periode'] . "]", 1, $r4['is_aktif_penilaian'], '', true, "style='display:inline'", "onClick='change_aktif(this, {$r4['id_penilaian_periode']})'") ?>
                                            </td> -->
                                        <?php } ?>
                                        <td>
                                            <b><?= $r4['kesimpulan']; ?></b>
                                        </td>
                                        <td align="center" style="background: <?= warnaskor($r4['skor']) ?>;">
                                            <b><?= round($r4['skor'], 2); ?></b>
                                        </td>
                                    <?php } ?>
                                </tr>
        <?php   }
                                            }
                                    }
                            }
                    }
            }
        ?>
    </tbody>
</table>


<!-- <div style="float: right;">
    <?php if ($total_nilaiarr) { ?>
        <b>

            <?php
            echo 'Nilai :' . $nilai = round((array_sum($total_nilaiarr) / $total_kriteria) * 100, 2);
            ?>
        </b>
    <?php } ?>
</div> -->