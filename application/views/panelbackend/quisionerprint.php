<table class="tableku" border="1">
    <thead>
        <tr>
            <th rowspan='2' style="width:10px">No</th>
            <input type='hidden' name='list_sort' id='list_sort'>
            <input type='hidden' name='list_order' id='list_order'>
            <?php foreach ($header as $rows) {
                if ($rows['type'] == 'list' or $rows['type'] == 'implodelist') {
                    if ($rows['label'] == 'Pegawai' || $rows['label'] == 'Respon') {
                        echo "<th colspan='2'style='text-align:center; style='max-width:$rows[width]'>$rows[label]</th>";
                    } else {
                        echo "<th rowspan='2' style='max-width:$rows[width]'>$rows[label]</th>";
                    }
                } else {
                    if ($rows['label'] == 'Pegawai' || $rows['label'] == 'Respon') {
                        echo "<th colspan='2' style='text-align:center; style='max-width:$rows[width]'>$rows[label]</th>";
                    } else {
                        echo "<th rowspan='2' style='text-align:center; max-width:$rows[width];'>$rows[label]</th>";
                    }
                }
            }
            ?>
        </tr>
        <tr>
            <th style='text-align:center;'>NIPP</th>
            <th style='text-align:center;'>Nama</th>
            <th style='text-align:center;'>Nilai</th>
            <th style='text-align:center;'>Kategori</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = $page;
        $yatidak = array(
            1 => 'Tidak',
            5 => 'Iya',
        );
        $sampai5 = array(
            1 => 'Sangat Kurang',
            2 => 'Kurang',
            3 => 'Cukup',
            4 => 'Baik',
            5 => 'Sangat Baik',
        );
        foreach ($list['rows'] as $rows) {

            $i++;
            echo "<tr>";
            echo "<td align='center'>$i</td>";
            foreach ($header as $rows1) {
                $val = $rows[$rows1['name']];
                switch ($rows1['type']) {
                    case 'list':
                        if ($rows1['name'] == 'id_status')
                            echo "<td><span class='badge bg-{$rows['status']}'>" . $rows1["value"][$val] . "</span></td>";
                        else
                            echo "<td>" . $rows1["value"][$val] . "</td>";
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
                        if ($rows1['name'] == 'nilai') {
                            echo "<td class='text-center'>$val</td>";
                        } elseif ($rows1['name'] == 'pegawai') {
                            echo "<td>" . $rows['nipp'] . "</td>";
                            echo "<td>" . $rows['nama_user'] . "</td>";
                        } elseif ($rows1['name'] == 'unit_kerja') {
                            echo "<td>" . $unitarr[$rows['id_jabatan']] . "</td>";
                        } elseif ($rows1['name'] == 'respon') {
                            if ($rows['jenis_jawaban'] == 'uraian') {
                                echo "<td colspan='2'>$rows[nilai]</td>";
                            } else {
                                echo "<td>$rows[nilai]</td>";
                                if ($rows['jenis_jawaban'] == 'yatidak') {
                                    echo "<td>" . $yatidak[$rows['nilai']] . "</td>";
                                } elseif ($rows['jenis_jawaban'] == '1sampai5') {
                                    echo "<td>" . $sampai5[$rows['nilai']] . "</td>";
                                }
                            }
                        } else {
                            echo "<td>$val</td>";
                        }
                        break;
                }
            }
            echo "</tr>";
        }
        if (!count($list['rows'])) {
            echo "<tr><td colspan='" . (count($header) + 1) . "'>Data kosong</td></tr>";
        }
        ?>
        <!--<tfoot>
            <tr>
                <td colspan="4" style="text-align: center;"><b>Total</b></td>
                <?php foreach ($tot as $k => $v) { ?>
                    <td style="text-align: right;"><b><?= (($k <> 'harga_jual') ? rupiah($v) : null) ?></b></td>
                <?php } ?>
            </tr>
        </tfoot>-->
    </tbody>
</table>