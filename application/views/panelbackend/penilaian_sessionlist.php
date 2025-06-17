<table class="table table-hover dataTable treetable">
    <tbody>
        <?php
        if ($list['rows'])
            foreach ($list['rows'] as $r) {

                if (!$r['nama'])
                    $r['nama'] = '-';

        ?>
            <tr>
                <td>
                    <span class="file">
                        <a href="<?= site_url($page_ctrl1 . "/index/$r[id_penilaian_session]") ?>"><?= $r['nama'] ?></a>
                    </span>
                </td>
                <td style="text-align:right"><?= labelstatus($r['id_status_pengajuan']) ?></td>
            </tr>
        <?php }
        else { ?>
            <center><i>Data belum ada isinya</i></center>
        <?php } ?>
    </tbody>
</table>