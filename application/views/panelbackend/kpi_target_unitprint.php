<table class="tableku tableku1">
    <thead>
        <tr>
            <th rowspan="2" style="width: 10px;">No</th>
            <th rowspan="2">Indikator KPI</th>
            <th rowspan="2">Bobot</th>
            <th rowspan="2">Polaritas</th>
            <th rowspan="2">Target</th>
            <th rowspan="2">Satuan</th>
            <th colspan="3">Realisasi</th>
            <th rowspan="2">Input Terakhir</th>
        </tr>
        <tr>
            <th>Nilai</th>
            <th>%</th>
            <th>Bobot</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $id_unit = null;
        $totalbobot = 0;
        $totalbobotrealisasi = 0;
        $no = 0;
        foreach ($rows as $row) {
            if ($id_unit && $id_unit <> $row['id_unit']) {
                $no = 0;
        ?>

                <tr>
                    <td colspan="2"><b>Total</b></td>
                    <td style="text-align: right;"><b><?= $totalbobot ?></b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align: right;"><b><?= $totalbobotrealisasi ?></b></td>
                    <td></td>
                </tr>

        <?php
                $totalbobot = 0;
                $totalbobotrealisasi = 0;
            }
            $id_unit = $row['id_unit'];
            $i++;
            echo "<tr data-tt-id='" . $row['id_kpi'] . "' data-tt-parent-id='" . $row['id_parent'] . "'>";
            if ($row['isfolder']) {
                echo "<td colspan='10'><span class=\"folder\"><b>$row[nama]</b></span></td>";
                echo "</tr>";
                continue;
            } else {
                $no++;
                echo "<td><span class=\"file\">$no</span></td>";
            }

            foreach ($header as $row1) {
                $val = $row[$row1['name']];
                if ($row1['name'] == 'nama') {
                    echo "<td><a href='" . ($url = base_url($page_ctrl . "/detail/$row[$pk]")) . "'>$val</a></td>";
                } else {
                    switch ($row1['type']) {
                        case 'list':
                            echo "<td>" . $row1["value"][$val] . "</td>";
                            break;
                        case 'number':
                            echo "<td style='text-align:right'>" . rupiah($val) . "</td>";
                            break;
                        case 'date':
                            echo "<td>" . Eng2Ind($val, false) . "</td>";
                            break;
                        case 'datetime':
                            echo "<td>" . Eng2Ind($val) . "</td>";
                            break;
                        default:
                            echo "<td>$val</td>";
                            break;
                    }
                }
            }

            $totalbobot += $row['bobot'];
            $totalbobotrealisasi += (float)$row['realbobot'];
            echo "</tr>";
        } ?>
        <?php
        if (!($rows)) {
            echo "<tr><td colspan='" . (count($header) + 1) . "'>Data kosong</td></tr>";
        } else { ?>
            <tr>
                <td colspan="2"><b>Total</b></td>
                <td style="text-align: right;"><b><?= $totalbobot ?></b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right;"><b><?= $totalbobotrealisasi ?></b></td>
                <td></td>
            </tr>
        <?php }
        ?>
    </tbody>
</table>