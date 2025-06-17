<table class="table table-bordered dataTable">
    <thead>
        <tr>
            <th colspan="4" style="text-align: center;">
                Kategori Risiko
                <!-- <br />
                <small><b>(RBS Level 0)</b></small> -->
            </th>
            <th colspan="4" style="text-align: center;">
                Sub Kategori
                <!-- <br />
                <small><b>(RBS Level 1)</b></small> -->
            </th>
            <!-- <th colspan="4" style="text-align: center;">
                RISIKO
                <br />
                <small><b>(RBS Level 2)</b></small>
            </th>
            <th style="text-align: center;">
                PENJELASAN
            </th> -->
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
                foreach ($rows[$r2['id_taksonomi_area']] as $k => $r) { ?>
                    <tr>

                        <?php if (!$i) { ?>
                            <td width="1px" <?= ($cot ? "rowspan='" . $cot . "'" : "") ?>>
                                <a title="Add Objective" href="<?= site_url("panelbackend/mt_risk_taksonomi_objective/add") ?>" class="btn  btn-sm btn-xs btn-danger"><span class="bi bi-plus"></span></a>
                            </td>
                        <?php } ?>

                        <?php if (!$i1) { ?>
                            <td width="1px" <?= ($co ? "rowspan='" . $co . "'" : "") ?>><?= $r1['kode'] ?></td>
                            <td <?= ($co ? "rowspan='" . $co . "'" : "") ?>><?= $r1['nama']?></td>
                            <td width="1px" <?= ($co ? "rowspan='" . $co . "'" : "") ?> style="padding:0px !important">
                                <?= UI::startMenu() ?>
                                <li><a href="<?= site_url("panelbackend/mt_risk_taksonomi_objective/edit/" . $r1['id_taksonomi_objective']) ?>">Edit</a></li>
                                <li><a href="<?= site_url("panelbackend/mt_risk_taksonomi_objective/delete/" . $r1['id_taksonomi_objective']) ?>">Delete</a></li>
                                <?= UI::closeMenu() ?>
                            </td>
                        <?php } ?>

                        <?php if (!$i1) { ?>
                            <td width="1px" <?= ($co ? "rowspan='" . $co . "'" : "") ?>>
                                <?php if ($r1['id_taksonomi_objective']) { ?>
                                    <a title="Add Area <?= ucwords(strtolower($r1['nama'])) ?>" href="<?= site_url("panelbackend/mt_risk_taksonomi_area/add/{$r1['id_taksonomi_objective']}") ?>" class="btn  btn-sm btn-xs btn-warning"><span class="bi bi-plus"></span></a>
                                <?php } ?>
                            </td>
                        <?php } ?>

                        <?php if (!$k) { ?>
                            <td width="1px" <?= ($ct ? "rowspan='" . $ct . "'" : "") ?>><?= $r2['kode'] ?></td>
                            <td <?= ($ct ? "rowspan='" . $ct . "'" : "") ?>><?= $r2['nama'] ?></td>
                            <td width="1px" <?= ($ct ? "rowspan='" . $ct . "'" : "") ?> style="padding:0px !important">
                                <?php if ($r2['kode']) { ?>
                                    <?= UI::startMenu() ?>
                                    <li><a href="<?= site_url("panelbackend/mt_risk_taksonomi_area/edit/{$r1['id_taksonomi_objective']}/" . $r2['id_taksonomi_area']) ?>">Edit</a></li>
                                    <li><a href="<?= site_url("panelbackend/mt_risk_taksonomi_area/delete/" . $r2['id_taksonomi_area']) ?>">Delete</a></li>
                                    <?= UI::closeMenu() ?>
                                <?php } ?>
                            </td>
                        <?php } ?>

                        <?php /* if (!$k) { ?>
                            <td width="1px" <?= ($ct ? "rowspan='" . $ct . "'" : "") ?>>
                                <?php if ($r2['kode']) { ?>
                                    <a title="Add Risiko <?= ucwords(strtolower($r2['nama'])) ?>" href="<?= site_url("panelbackend/mt_risk_taksonomi/add/{$r2['id_taksonomi_area']}") ?>" class="btn  btn-sm btn-xs btn-success"><span class="bi bi-plus"></span></a>
                                <?php } ?>
                            </td>
                        <?php } ?>

                        <td width="1px"><?= $r['kode'] ?></td>
                        <td><?= $r['nama'] ?></td>
                        <td width="1px" style="padding:0px !important">
                            <?php if ($r['id_taksonomi']) { ?>
                                <?= UI::startMenu() ?>
                                <li><a href="<?= site_url("panelbackend/mt_risk_taksonomi/edit/{$r2['id_taksonomi_area']}/" . $r['id_taksonomi']) ?>">Edit</a></li>
                                <li><a href="<?= site_url("panelbackend/mt_risk_taksonomi/delete/" . $r['id_taksonomi']) ?>">Delete</a></li>
                                <?= UI::closeMenu() ?>
                            <?php } ?>
                        </td>
                        <td><?= $r['penjelasan'] ?></td> */?>
                    </tr>
        <?php $i++;
                    $i1++;
                }
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
<script>
    function goSubmit2(v){
        console.log(v);
    }
</script>