<div style="display: flex;">
    <span style="
    font-weight: bold;
    padding-top: 7px;
">Tgl. Efektif : </span>
    <input type="text" name="tgl_efektif" id="tgl_efektif" value="<?= $tgl_efektif ?>" class="form-control datepicker" maxlength="10" size="10" style="width:100px;display:inline" onchange="goSubmit('filter')" autocomplete="off">
</div>

<table class="table table-hover dataTable treetable">
    <tbody>
        <?php
        if ($rows)
            foreach ($rows as $r) {

                if (!$r['nama'])
                    $r['nama'] = '-';

                if (!$r['id_parent'] && $r['id'] <> 'A')
                    $r['id_parent'] = 'A';

        ?>
            <?php if ($r['navigasi'] == '1' or $r['navigasi'] == '2') { ?>
                <tr data-tt-id='<?= $r['id'] ?>' data-tt-parent-id='<?= $r['id_parent'] ?>' class="<?= ($r['owner'] == $owner or in_array($owner, $r['userarr'])) ? "bg-light-blue" : null ?>">
                    <td>
                        <b>
                            <span class="folder">
                                <a href="<?= site_url("panelbackend/risk_scorecard/index/$r[id_scorecard]") ?>"><?= $r['nama'] ?></a>
                            </span>
                        </b>
                    </td>
                    <td></td>
                </tr>
            <?php } else { ?>
                <tr data-tt-id='<?= $r['id'] ?>' data-tt-parent-id='<?= $r['id_parent'] ?>' class="<?= ($r['owner'] == $owner or in_array($owner, $r['userarr'])) ? "bg-light-blue" : null ?>">
                    <td>
                        <span class="file">
                            <a href="<?= site_url("panelbackend/risk_risiko/index/$r[id_scorecard]") ?>"><?= $r['nama'] ?></a>
                        </span>
                    </td>
                    <td style="text-align:right"><?= labelstatus($r['id_status_pengajuan']) ?></td>
                </tr>
            <?php } ?>
        <?php }
        else { ?>
            <center><i>Data belum ada isinya</i></center>
        <?php } ?>
    </tbody>
</table>