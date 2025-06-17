<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link <?= $jenis == 'baru' ? 'active' : '' ?>" href="<?= site_url("panelbackend/rtm_uraian/index/baru") ?>">
            BAHAN RTM (BARU)
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link <?= $jenis == 'open' ? 'active' : '' ?>" href="<?= site_url("panelbackend/rtm_uraian/index/open") ?>">
            PERMASALAHAN YANG MASIH OPEN DI RTM SEBELUMNYA
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link <?= $jenis == 'ditolak' ? 'active' : '' ?>" href="<?= site_url("panelbackend/rtm_uraian/index/ditolak") ?>">
            BAHAN YANG TIDAK DISETUJUI
        </a>
    </li>
</ul>

<table class="table table-striped table-hover dataTable">
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Uraian Permasalahan Bidang</th>
            <th rowspan="2">Analisis/Penyebab</th>
            <th colspan="3">Rencana Penyelesaian</th>
            <th rowspan="2">Status</th>
            <th rowspan="2">RTM Ke</th>
            <th rowspan="2"></th>
        </tr>
        <tr>
            <th>Uraian</th>
            <th>Target Waktu</th>
            <th>Penanggung Jawab (PIC)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = $page;
        foreach ($list['rows'] as $rows) {
            $i++;
            echo "<tr>";
            echo "<td>$i</td>";
            foreach ($header as $rows1) {
                $val = $rows[$rows1['name']];
                if ($rows1['name'] == 'uraian' or $rows1['name'] == 'code') {
                    echo "<td><a href='" . ($url = base_url($page_ctrl . "/detail/$rows[$pk]")) . "'>$val</a></td>";
                } elseif ($rows1['name'] == 'isi') {
                    echo "<td>" . ReadMore($val, $url) . "</td>";
                } else {
                    switch ($rows1['type']) {
                        case 'list':
                            echo "<td>" . $rows1["value"][$val] . "</td>";
                            break;
                        case 'number':
                            echo "<td style='text-align:right'>$val</td>";
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
            echo "<td style='text-align:right'>";
            if ($jenis == 'baru' && Access("menyetujui", "panelbackend/rtm_uraian")) {
                echo "<button type='button' class='btn btn-sm btn-danger' onclick='if(confirm(\"Apakah akan menolak ?\")){goSubmitValue(\"tolak\"," . $rows['id_rtm_uraian'] . ")}'>Tolak</button>";
                echo "<button type='button' class='btn btn-sm btn-success' onclick='if(confirm(\"Apakah akan menyetujui ?\")){goSubmitValue(\"setujui\"," . $rows['id_rtm_uraian'] . ")}'>Setujui</button>";
            }
            echo UI::showMenuMode('inlist', $rows[$pk]);
            echo "</td>";
            echo "</tr>";
        }
        if (!count($list['rows'])) {
            echo "<tr><td colspan='" . (count($header) + 2) . "'>Data kosong</td></tr>";
        }
        ?>
    </tbody>
</table>
<?= UI::showPaging($paging, $page, $limit_arr, $limit, $list) ?>