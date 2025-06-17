<table class="table table-bordered">
    <thead>
        <tr>
            <th style="width:10px">NO</th>
            <th rowspan="2">ASPEK</th>
            <th style="text-align: center;">BOBOT</th>
            <th style="text-align: center;">SKOR</th>
            <th style="text-align: center;">CAPAIAN %</th>
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

        $total_bobot = 0;
        $total_skor_bobot = 0;
        foreach ($rows as $r) {
            $total_bobot += $r['bobot'];
            $total_skor_bobot += $r['skor_bobot']; ?>
            <tr>
                <td><?= $r['kode_aspek'] ?></td>
                <td><?= $r['aspek'] ?></td>
                <td style="text-align: center;"><?= rupiah($r['bobot']) ?></td>
                <td style="text-align: center;"><?= rupiah($r['skor_bobot']) ?></td>
                <td style="text-align: center; background:<?= @warnaskor($r['skor_bobot'] / $r['bobot'] * 100) ?>"><?= @rupiah($r['skor_bobot'] / $r['bobot'] * 100) ?></td>
            </tr>
        <?php $kode_aspek = $r['kode_aspek'];
        } ?>
        <tr>
            <th colspan="2"><b>Total</b></th>
            <td style="text-align: center;"><?= round($total_bobot, 2) ?></td>
            <td style="text-align: center;"><?= round($total_skor_bobot, 2) ?></td>
            <td style="text-align: center;"><?php echo round($total_skor_bobot / $total_bobot * 100, 2) 
                                            ?></td>
        </tr>
    </tbody>
</table>