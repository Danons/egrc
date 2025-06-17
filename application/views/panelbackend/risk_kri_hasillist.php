<table class="table table-striped table-hover dataTable">
    <thead>
        <tr>
            <th style="text-align: left;">Bulan</th>
            <th style="text-align: right;">Realisasi</th>
            <th style="text-align: left;">Tgl Input</th>
            <th style="text-align: left;">Tgl Update</th>
            <th style="width:1px;"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list['rows'] as $r) { ?>
            <tr>
                <td><?= ListBulan()[$r['bulan']] ?></td>
                <td style="text-align: right;"><?= $r['nilai'] ?></td>
                <td style="text-align: left;"><?= $r['created_date'] ?></td>
                <td style="text-align: left;"><?= $r['modified_date'] ?></td>
                <td><?= UI::showMenuMode('inlist', $r[$pk]) ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>