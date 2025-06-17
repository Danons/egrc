<table class="table table-bordered">
    <thead>
        <tr>
            <th style="width:10px" rowspan="2">NO</th>
            <th rowspan="2">ASPEK</th>
            <th style="text-align: center;" colspan="3">LEVEL 2</th>
            <th style="text-align: center;" colspan="3">LEVEL 3</th>
            <th style="text-align: center;" colspan="3">LEVEL 4</th>
            <th style="text-align: center;" colspan="3">LEVEL 5</th>
            <th style="text-align: center;" rowspan="2">LEVEL</th>
        </tr>
        <tr>
            <th>Y/S/T</th>
            <th>Total</th>
            <th>Nilai</th>

            <th>Y/S/T</th>
            <th>Total</th>
            <th>Nilai</th>

            <th>Y/S/T</th>
            <th>Total</th>
            <th>Nilai</th>

            <th>Y/S/T</th>
            <th>Total</th>
            <th>Nilai</th>
        </tr>
    </thead>
    <tbody>
        <?php $kode_aspek = null;

        function warnaskor($skor)
        {
            if ($skor == null)
                return "#fff";

            if ($skor >= 100)
                return "green; color:white";
            elseif ($skor >= 70)
                return "#80ff00";
            elseif ($skor >= 50)
                return "yellow";
            elseif ($skor > 20)
                return "#ff8000";
            else
                return "#ff0000";
        }

        $total_yst2 = 0;
        $total_nilai2 = 0;

        $total_yst3 = 0;
        $total_nilai3 = 0;

        $total_yst4 = 0;
        $total_nilai4 = 0;

        $total_yst5 = 0;
        $total_nilai5 = 0;

        $no = 1;
        // dpr($rows);
        if ($rows)
            foreach ($rows as $r) {
                $level = 1;
                $total_yst2 += $r['yst2'];
                $total_nilai2 += $r['nilai2'];

                $total_yst3 += $r['yst3'];
                $total_nilai3 += $r['nilai3'];

                $total_yst4 += $r['yst4'];
                $total_nilai4 += $r['nilai4'];

                $total_yst5 += $r['yst5'];
                $total_nilai5 += $r['nilai5'];
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $r['nama'] ?></td>

                <td style="text-align: center;"><?= $r['yst2'] ?></td>
                <td style="text-align: center;"><?= $r['nilai2'] ?></td>
                <td style="text-align: center;"><?= $r['yst2'] == $r['level2'] ? 'Y' : 'T' ?></td>
                <?php
                if ($r['yst2'] == $r['level2'] && $level == 1)
                    $level = 2;
                ?>

                <td style="text-align: center;"><?= $r['yst3'] ?></td>
                <td style="text-align: center;"><?= $r['nilai3'] ?></td>
                <td style="text-align: center;"><?= $r['yst3'] == $r['level3'] ? 'Y' : 'T' ?></td>
                <?php
                if ($r['yst3'] == $r['level3'] && $level == 2)
                    $level = 3;
                ?>

                <td style="text-align: center;"><?= $r['yst4'] ?></td>
                <td style="text-align: center;"><?= $r['nilai4'] ?></td>
                <td style="text-align: center;"><?= $r['yst4'] == $r['level4'] ? 'Y' : 'T' ?></td>
                <?php
                if ($r['yst4'] == $r['level4'] && $level == 3)
                    $level = 4;
                ?>

                <td style="text-align: center;"><?= $r['yst5'] ?></td>
                <td style="text-align: center;"><?= $r['nilai5'] ?></td>
                <td style="text-align: center;"><?= $r['yst5'] == $r['level5'] ? 'Y' : 'T' ?></td>
                <?php
                if ($r['yst5'] == $r['level5'] && $level == 4)
                    $level = 5;
                ?>

                <td style="text-align: center;">LEVEL <?= $level ?></td>
            </tr>
        <?php $kode_aspek = $r['kode_aspek'];
            } ?>
        <tr>
            <th colspan="2"><b>Total</b></th>
            <td style="text-align: center;"><?= $total_yst2 ?></td>
            <td style="text-align: center;"><?= round($total_nilai2, 2) ?></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"><?= $total_yst3 ?></td>
            <td style="text-align: center;"><?= round($total_nilai3, 2) ?></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"><?= $total_yst4 ?></td>
            <td style="text-align: center;"><?= round($total_nilai4, 2) ?></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"><?= $total_yst5 ?></td>
            <td style="text-align: center;"><?= round($total_nilai5, 2) ?></td>
            <td style="text-align: center;"></td>
        </tr>
    </tbody>
</table>