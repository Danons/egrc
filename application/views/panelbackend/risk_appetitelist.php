    <div class="fright">
        <input type="number" class="form-control" value="<?= $tahun ?>" name="tahun" id="tahun" style="width:80px; display:inline" onchange="goSubmit('set_value')" />
        <?php if ($this->access_role['edit']) { ?>
            <?php if ($edited) { ?>
                <button type="button" onclick="goSubmit('save')" class="btn btn-success">
                    <span class="bi bi-upload"></span> Save
                </button>
            <?php } else { ?>
                <a href="<?= site_url("panelbackend/risk_appetite/index/1") ?>" class="btn btn-primary">
                    <span class="bi bi-pencil"></span> Edit
                </a>
            <?php } ?>
        <?php } ?>
    </div>
    <div style="clear: both;"></div>
    <table class="table table-bordered dataTable">
        <thead>
            <tr>
                <th colspan="2" rowspan="2" style="text-align: center;">
                    Kategori Risiko
                </th>
                <th colspan="2" rowspan="2" style="text-align: center;">
                    Sub Kategori
                </th>
                <!-- <th colspan="2" rowspan="2" style="text-align: center;">
                    RISIKO
                    <br />
                    <small><b>(RBS Level 2)</b></small>
                </th> -->
                <th colspan="2" style="text-align: center;">
                    RISK APPETITE
                </th>
            </tr>
            <tr>
                <th style="text-align: center;">KEMUNGKINAN</th>
                <th style="text-align: center;">DAMPAK</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!$rowso) {
                $rowso = array("0" => array("id_taksonomi_objective" => "0"));
                $rowsa = array("0" => array(array("id_taksonomi_area" => "0")));
                $rows = array("0" => array(array("id_taksonomi" => "0")));
            }
            $cot = 0;
            foreach ($rowso as $k1 => $r1) {
                if ($rowsa[$r1['id_taksonomi_objective']])
                    foreach ($rowsa[$r1['id_taksonomi_objective']] as $c) {
                        if (!$rows[$c['id_taksonomi_area']])
                            $rows[$c['id_taksonomi_area']][] = array("id_taksonomi" => "0");

                        $cot += count($rows[$c['id_taksonomi_area']]);
                    }
                else {
                    $rowsa[$r1['id_taksonomi_objective']][] =
                        array("id_taksonomi_area" => "0" . $k1);
                    $rows["0" . $k1][] = array("id_taksonomi" => "0");
                    $cot++;
                }
            }

            $i = 0;
            foreach ($rowso as $k1 => $r1) {
                $co = 0;
                foreach ($rowsa[$r1['id_taksonomi_objective']] as $c) {
                    $co += count($rows[$c['id_taksonomi_area']]);
                }
                $i1 = 0;
                foreach ($rowsa[$r1['id_taksonomi_objective']] as $k2 => $r2) {
                    $ct = count($rows[$r2['id_taksonomi_area']]);
                    $i2 = 0;
                    // foreach ($rows[$r2['id_taksonomi_area']] as $k => $r) { 
            ?>
                    <tr>

                        <?php if (!$i1) { ?>
                            <td width="1px" <?= ($co ? "rowspan='" . $co . "'" : "") ?>><?= $r1['kode'] ?></td>
                            <td <?= ($co ? "rowspan='" . $co . "'" : "") ?>><?= $r1['nama'] ?></td>
                        <?php } ?>

                        <?php if (!$k) { ?>
                            <td width="1px" <?= ($ct ? "rowspan='" . $ct . "'" : "") ?>><?= $r2['kode'] ?></td>
                            <td <?= ($ct ? "rowspan='" . $ct . "'" : "") ?>><?= $r2['nama'] ?></td>
                        <?php } ?>

                        <!-- <td width="1px"><?= $r2['kode'] ?></td>
                            <td><?= $r2['nama'] ?></td> -->
                        <td>
                            <?= UI::createSelect('tingkat[' . $r2['id_taksonomi_area'] . '][id_kemungkinan]', $mtkemungkinanrisikoarr, $tingkat[$r2['id_taksonomi_area']]['id_kemungkinan'], $edited, 'form-control ', "style='width:100%;'") ?>
                        </td>
                        <td>
                            <?= UI::createSelect('tingkat[' . $r2['id_taksonomi_area'] . '][id_dampak]', $mtdampakrisikoarr, $tingkat[$r2['id_taksonomi_area']]['id_dampak'], $edited, 'form-control ', "style='width:100%;'") ?>
                        </td>
                    </tr>
            <?php $i++;
                    $i1++;
                    // }
                }
            }
            ?>
            <?php /* if ($this->access_role['add']) { ?>
            <tr>
                <td></td>
                <td></td>
                <td style="text-align: right;">
                    <a href="" class="btn  btn-sm btn-xs btn-primary"><span class="bi bi-plus"></span></a>
                </td>
                <td></td>
                <td></td>
                <td style="text-align: right;">
                    <a href="" class="btn  btn-sm btn-xs btn-primary"><span class="bi bi-plus"></span></a>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right;">
                    <a href="<?= site_url("panelbackend/mt_risk_taksonomi/add") ?>" class="btn  btn-sm btn-xs btn-primary"><span class="bi bi-plus"></span></a>
                </td>
            </tr>
        <?php } */ ?>
        </tbody>
    </table>

    <style>
        .table-bordered tbody tr td {
            padding: 5px !important;
        }
    </style>