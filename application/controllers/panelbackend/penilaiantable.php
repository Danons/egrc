<table class="table table-sticky table-bordered">
    <thead>
        <tr>

            <?php
            // dpr($level, 1);

            if ($id_kategori_jenis == 1 || $id_kategori_jenis == 2) { ?>
                <th colspan="2" rowspan="2">Indikator</th>
                <th colspan="2" rowspan="2">Paramater</th>
            <?php }
            if ($id_kategori_jenis == 1) { ?>
                <th rowspan="2">Bobot Par</th>
            <?php }
            if ($id_kategori_jenis == 1) { ?>
                <th colspan="4" rowspan="2">Faktor-faktor yang Diuji Kesesuaiannya (FUK)</th>
            <?php }
            if ($id_kategori_jenis == 2) { ?>
                <th colspan="2" rowspan="2">Faktor-faktor yang Diuji Kesesuaiannya (FUK)</th>
            <?php }
            if ($id_kategori_jenis == 2) { ?>
                <th rowspan="2">Level</th>
                <th colspan="2" rowspan="2">UP</th>
            <?php }
            if ($id_kategori_jenis == 3) { ?>
                <th rowspan="2" colspan="4">Key Process Area</th>
                <th rowspan="2">Level</th>
                <th rowspan="2" colspan="2">Uraian/Pernyataan</th>
                <th rowspan="2">Penjelasan Pernyataan</th>
                <th rowspan="2">Contoh Output/Infrastruktur</th>
                <th rowspan="2">Daftar Uji</th>
            <?php } ?>
            <th colspan="4" style="text-align: center;">Sumber Data</th>
            <th rowspan="2">Nilai</th>
            <?php if ($id_kategori_jenis == 1) { ?>
                <th rowspan="2">Skor</th>
            <?php } ?>
            <th width="4px" rowspan="2"></th>
            <th rowspan="2">AOI</th>
        </tr>
        <tr>
            <th>D</th>
            <th>K</th>
            <th>W</th>
            <th>O</th>
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

        function skoring($row, $is_admin, $id_kategori_jenis)
        {
            if ($row['id_kriteria']) {
                echo "<td rowspan='" . $row['rowspan'] . "' style='text-align:center;cursor:pointer;background:" . warnaskor($row['skor']) . ";' onclick='detail(" . $row['id_penilaian_periode'], ',' . $row['id_kriteria'] . ")'>";
                if ($row['skor'] === '')
                    echo "<b><i class=\"bi bi-pencil-square\"></i></b>";
                else
                    echo "<b>" . round($row['skor'], 2) . "</b>";

                // dpr($row);
                echo "</td>";
                if ($id_kategori_jenis == 1) {
                    echo "<td rowspan='" . $row['rowspan'] . "'>";
                    if ($row['skor'] !== '')
                        echo round(($row['skor'] * ($row['bobot'] / $row['rowspan'])), 2);
                    echo "</td>";
                }
            } else {
                echo "<td rowspan='" . $row['rowspan'] . "'>";
                echo "</td>";
                if ($id_kategori_jenis == 1) {
                    echo "<td rowspan='" . $row['rowspan'] . "'>";
                    echo "</td>";
                }
            }

            if ($is_admin) {
                // dpr($row);
        ?>
                <td align="center">
                    <?= UI::createCheckBox('is_aktif[' . $row['id_penilaian_periode'] . "]", 1, $row['is_aktif_penilaian'], '', true, "style='display:inline'", "onClick='change_aktif(this, {$row['id_penilaian_periode']})'") ?>
                </td>
        <?php }
        }

        function checkbox($is_check = false)
        {
            if ($is_check)
                echo '<i class="bi bi-check2"></i>';
            else
                echo '';
        }
        // dpr($arearr,1);
        function print_table($arearr, $level = 0, $id_kategori_jenis, $is_admin)
        {
            $level++;
            // dpr($arearr, 1);
            foreach ($arearr as $k => $r) {
                if ($k == 0 && $level > 1)
                    continue;

                switch ($level) {
                    case 1:
                        echo "<tr>";

                        echo "<td rowspan='" . $r['rowspan'] . "' class='fix'>";
                        echo $r['kode'];
                        echo "</td>";

                        echo "<td rowspan='" . $r['rowspan'] . "' class='fix'>";
                        echo nl2br($r['nama']);
                        echo "</td>";

                        $r1 = $r['sub1'][0];
                        // dpr($r1);


                        echo "<td rowspan='" . $r1['rowspan'] . "'>";
                        echo $r1['kode'];
                        echo "</td>";

                        echo "<td rowspan='" . $r1['rowspan'] . "'>";
                        echo nl2br($r1['nama']);
                        echo "</td>";

                        if ($id_kategori_jenis == 1) {
                            echo "<td rowspan='" . $r1['rowspan'] . "'>";
                            echo $r1['bobot'];
                            echo "</td>";
                        }

                        $r2 = $r1['sub2'][0];
                        if ($id_kategori_jenis == 3) {
                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            switch ($r2['kode_lvl']) {
                                case "2":
                                    echo "<span style='font-weight:bold;color:red'>";
                                    break;
                                case "3":
                                    echo "<span style='font-weight:bold;color:orange'>";
                                    break;
                                case "4":
                                    echo "<span style='font-weight:bold;color:green'>";
                                    break;
                                case "5":
                                    echo "<span style='font-weight:bold;color:blue'>";
                                    break;
                            }
                            echo $r2['kode_lvl'];
                            echo "</span>";
                            echo "</td>";
                        }

                        echo "<td rowspan='" . $r2['rowspan'] . "'>";
                        echo $r2['kode'];
                        echo "</td>";

                        echo "<td rowspan='" . $r2['rowspan'] . "'>";
                        echo nl2br($r2['nama']);
                        echo "</td>";

                        if ($id_kategori_jenis == 3) {
                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo nl2br($r2['keterangan']);
                            echo "</td>";

                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo nl2br($r2['keterangan1']);
                            echo "</td>";

                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo nl2br($r2['keterangan2']);
                            echo "</td>";
                        }

                        if (!$r2['sub3']) {
                            if ($id_kategori_jenis == 1) {
                                echo "<td rowspan='" . $r2['rowspan'] . "'>";
                                echo "</td>";
                                echo "<td rowspan='" . $r2['rowspan'] . "'>";
                                echo "</td>";
                            }
                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo checkbox($r2['d']);
                            echo "</td>";

                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo checkbox($r2['k']);
                            echo "</td>";

                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo checkbox($r2['w']);
                            echo "</td>";

                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo checkbox($r2['o']);
                            echo "</td>";

                            skoring($r2, $is_admin, $id_kategori_jenis);

                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo $r2['kesimpulan'];
                            echo "</td>";
                        }

                        if ($r2['sub3']) {
                            $r3 = $r2['sub3'][0];

                            if ($id_kategori_jenis == 2) {
                                echo "<td rowspan='" . $r3['rowspan'] . "'>";
                                switch ($r3['kode_lvl']) {
                                    case "2":
                                        echo "<span style='font-weight:bold;color:red'>";
                                        break;
                                    case "3":
                                        echo "<span style='font-weight:bold;color:orange'>";
                                        break;
                                    case "4":
                                        echo "<span style='font-weight:bold;color:green'>";
                                        break;
                                    case "5":
                                        echo "<span style='font-weight:bold;color:blue'>";
                                        break;
                                }
                                echo $r3['kode_lvl'];
                                echo "</span>";
                                echo "</td>";
                            }

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['kode'];
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo nl2br($r3['nama']);
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo checkbox($r3['d']);
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo checkbox($r3['k']);
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo checkbox($r3['w']);
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo checkbox($r3['o']);
                            echo "</td>";

                            skoring($r3, $is_admin, $id_kategori_jenis);

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['kesimpulan'];
                            echo "</td>";
                        }

                        echo "</tr>";

                        if ($r2['sub3'])
                            print_table($r2['sub3'], 3, $id_kategori_jenis, $is_admin);

                        if ($r1['sub2'])
                            print_table($r1['sub2'], 2, $id_kategori_jenis, $is_admin);

                        if ($r['sub1'])
                            print_table($r['sub1'], 1, $id_kategori_jenis, $is_admin);

                        break;
                    case 2:
                        echo "<tr>";

                        echo "<td rowspan='" . $r['rowspan'] . "'>";
                        echo $r['kode'];
                        echo "</td>";

                        echo "<td rowspan='" . $r['rowspan'] . "'>";
                        echo nl2br($r['nama']);
                        echo "</td>";


                        if ($id_kategori_jenis == 1) {
                            echo "<td rowspan='" . $r['rowspan'] . "'>";
                            echo $r['bobot'];
                            echo "</td>";
                        }

                        $r2 = $r['sub2'][0];

                        if ($id_kategori_jenis == 3) {
                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            switch ($r2['kode_lvl']) {
                                case "2":
                                    echo "<span style='font-weight:bold;color:red'>";
                                    break;
                                case "3":
                                    echo "<span style='font-weight:bold;color:orange'>";
                                    break;
                                case "4":
                                    echo "<span style='font-weight:bold;color:green'>";
                                    break;
                                case "5":
                                    echo "<span style='font-weight:bold;color:blue'>";
                                    break;
                            }
                            echo $r2['kode_lvl'];
                            echo "</span>";
                            echo "</td>";
                        }

                        echo "<td rowspan='" . $r2['rowspan'] . "'>";
                        echo $r2['kode'];
                        echo "</td>";

                        echo "<td rowspan='" . $r2['rowspan'] . "'>";
                        echo nl2br($r2['nama']);
                        echo "</td>";

                        if ($id_kategori_jenis == 3) {
                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo nl2br($r2['keterangan']);
                            echo "</td>";

                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo nl2br($r2['keterangan1']);
                            echo "</td>";

                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo nl2br($r2['keterangan2']);
                            echo "</td>";
                        }

                        if (!$r2['sub3']) {
                            if ($id_kategori_jenis == 1) {
                                echo "<td rowspan='" . $r2['rowspan'] . "'>";
                                echo "</td>";
                                echo "<td rowspan='" . $r2['rowspan'] . "'>";
                                echo "</td>";
                            }
                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo checkbox($r2['d']);
                            echo "</td>";

                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo checkbox($r2['k']);
                            echo "</td>";

                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo checkbox($r2['w']);
                            echo "</td>";

                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo checkbox($r2['o']);
                            echo "</td>";

                            skoring($r2, $is_admin, $id_kategori_jenis);

                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo $r2['kesimpulan'];
                            echo "</td>";
                        }


                        if ($r2['sub3']) {
                            $r3 = $r2['sub3'][0];

                            if ($id_kategori_jenis == 2) {
                                echo "<td rowspan='" . $r3['rowspan'] . "'>";
                                switch ($r3['kode_lvl']) {
                                    case "2":
                                        echo "<span style='font-weight:bold;color:red'>";
                                        break;
                                    case "3":
                                        echo "<span style='font-weight:bold;color:orange'>";
                                        break;
                                    case "4":
                                        echo "<span style='font-weight:bold;color:green'>";
                                        break;
                                    case "5":
                                        echo "<span style='font-weight:bold;color:blue'>";
                                        break;
                                }
                                echo $r3['kode_lvl'];
                                echo "</span>";
                                echo "</td>";
                            }

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['kode'];
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo nl2br($r3['nama']);
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo checkbox($r3['d']);
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo checkbox($r3['k']);
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo checkbox($r3['w']);
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo checkbox($r3['o']);
                            echo "</td>";

                            skoring($r3, $is_admin, $id_kategori_jenis);

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['kesimpulan'];
                            echo "</td>";
                        }

                        echo "</tr>";

                        if ($r2['sub3'])
                            print_table($r2['sub3'], 3, $id_kategori_jenis, $is_admin);

                        if ($r['sub2'])
                            print_table($r['sub2'], 2, $id_kategori_jenis, $is_admin);
                        break;
                    case 3:
                        echo "<tr>";

                        if ($id_kategori_jenis == 3) {
                            echo "<td rowspan='" . $r['rowspan'] . "'>";
                            switch ($r['kode_lvl']) {
                                case "2":
                                    echo "<span style='font-weight:bold;color:red'>";
                                    break;
                                case "3":
                                    echo "<span style='font-weight:bold;color:orange'>";
                                    break;
                                case "4":
                                    echo "<span style='font-weight:bold;color:green'>";
                                    break;
                                case "5":
                                    echo "<span style='font-weight:bold;color:blue'>";
                                    break;
                            }
                            echo $r['kode_lvl'];
                            echo "</span>";
                            echo "</td>";
                        }

                        echo "<td rowspan='" . $r['rowspan'] . "'>";
                        echo $r['kode'];
                        echo "</td>";

                        echo "<td rowspan='" . $r['rowspan'] . "'>";
                        echo nl2br($r['nama']);
                        echo "</td>";

                        if ($id_kategori_jenis == 3) {
                            echo "<td rowspan='" . $r['rowspan'] . "'>";
                            echo nl2br($r['keterangan']);
                            echo "</td>";

                            echo "<td rowspan='" . $r['rowspan'] . "'>";
                            echo nl2br($r['keterangan1']);
                            echo "</td>";

                            echo "<td rowspan='" . $r['rowspan'] . "'>";
                            echo nl2br($r['keterangan2']);
                            echo "</td>";
                        }

                        if (!$r['sub3']) {

                            if ($id_kategori_jenis == 1) {
                                echo "<td rowspan='" . $r['rowspan'] . "'>";
                                echo "</td>";
                                echo "<td rowspan='" . $r['rowspan'] . "'>";
                                echo "</td>";
                            }
                            echo "<td rowspan='" . $r['rowspan'] . "'>";
                            echo checkbox($r['d']);
                            echo "</td>";

                            echo "<td rowspan='" . $r['rowspan'] . "'>";
                            echo checkbox($r['k']);
                            echo "</td>";

                            echo "<td rowspan='" . $r['rowspan'] . "'>";
                            echo checkbox($r['w']);
                            // dpr($r['w']);
                            echo "</td>";

                            echo "<td rowspan='" . $r['rowspan'] . "'>";
                            echo checkbox($r['o']);
                            echo "</td>";

                            skoring($r, $is_admin, $id_kategori_jenis);

                            echo "<td rowspan='" . $r['rowspan'] . "'>";
                            echo $r['kesimpulan'];
                            echo "</td>";
                        }


                        if ($r['sub3']) {
                            $r3 = $r['sub3'][0];

                            if ($id_kategori_jenis == 2) {
                                echo "<td rowspan='" . $r3['rowspan'] . "'>";
                                switch ($r3['kode_lvl']) {
                                    case "2":
                                        echo "<span style='font-weight:bold;color:red'>";
                                        break;
                                    case "3":
                                        echo "<span style='font-weight:bold;color:orange'>";
                                        break;
                                    case "4":
                                        echo "<span style='font-weight:bold;color:green'>";
                                        break;
                                    case "5":
                                        echo "<span style='font-weight:bold;color:blue'>";
                                        break;
                                }
                                echo $r3['kode_lvl'];
                                echo "</span>";
                                echo "</td>";
                            }

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['kode'];
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo nl2br($r3['nama']);
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo checkbox($r3['d']);
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo checkbox($r3['k']);
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo checkbox($r3['w']);
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo checkbox($r3['o']);
                            echo "</td>";

                            skoring($r3, $is_admin, $id_kategori_jenis);

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['kesimpulan'];
                            echo "</td>";
                        }

                        echo "</tr>";

                        if ($r['sub3'])
                            print_table($r['sub3'], 3, $id_kategori_jenis, $is_admin);
                        break;
                    case 4:
                        echo "<tr>";

                        if ($id_kategori_jenis == 2) {
                            echo "<td rowspan='" . $r['rowspan'] . "'>";
                            switch ($r['kode_lvl']) {
                                case "2":
                                    echo "<span style='font-weight:bold;color:red'>";
                                    break;
                                case "3":
                                    echo "<span style='font-weight:bold;color:orange'>";
                                    break;
                                case "4":
                                    echo "<span style='font-weight:bold;color:green'>";
                                    break;
                                case "5":
                                    echo "<span style='font-weight:bold;color:blue'>";
                                    break;
                            }
                            echo $r['kode_lvl'];
                            echo "</span>";
                            echo "</td>";
                        }

                        echo "<td rowspan='" . $r['rowspan'] . "'>";
                        echo $r['kode'];
                        echo "</td>";

                        echo "<td rowspan='" . $r['rowspan'] . "'>";
                        echo nl2br($r['nama']);
                        echo "</td>";

                        echo "<td rowspan='" . $r['rowspan'] . "'>";
                        echo checkbox($r['d']);
                        echo "</td>";

                        echo "<td rowspan='" . $r['rowspan'] . "'>";
                        echo checkbox($r['k']);
                        echo "</td>";

                        echo "<td rowspan='" . $r['rowspan'] . "'>";
                        echo checkbox($r['w']);
                        echo "</td>";

                        echo "<td rowspan='" . $r['rowspan'] . "'>";
                        echo checkbox($r['o']);
                        echo "</td>";

                        skoring($r, $is_admin, $id_kategori_jenis);

                        echo "<td rowspan='" . $r['rowspan'] . "'>";
                        echo $r['kesimpulan'];
                        echo "</td>";

                        echo "</tr>";
                        break;
                }
            }
        }

        print_table($arearr, 0, $id_kategori_jenis, $is_admin); ?>
    </tbody>
</table>