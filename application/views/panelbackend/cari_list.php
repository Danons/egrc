<table class="table table-hover dataTable">
    <thead>
        <tr>
            <th rowspan="2">No Risiko</th>
            <th rowspan="2">Nama Risiko</th>
            <th colspan="3" style="text-align: center;">Tingkat Risiko</th>
            <th rowspan="2">Status</th>
        </tr>
        <tr>
            <th>Inheren</th>
            <th>Current</th>
            <th>Targeted</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = $page;
        foreach ($list['rows'] as $rows) {
            $i++;
            echo "<tr>";
            echo "<td>";
            echo $rows['nomor'];
            echo "</td>";
            echo "<td>";
            echo "<a href='" . ($url = site_url("panelbackend/risk_risiko/detail/{$rows['id_scorecard']}/$rows[id_risiko]")) . "'>" . nl2br($rows['nama']) . "</a>";
            echo "</td>";
            echo labeltingkatrisiko($rows['inheren']);
            echo labeltingkatrisiko($rows['actual']);
            echo labeltingkatrisiko($rows['risidual']);
            echo "<td>";
            echo labelstatusrisiko($rows['status_risiko']);
            echo "</td>";
            echo "</tr>";
        }
        if (!$list['rows']) {
            echo "<tr><td colspan='6'>Data kosong</td></tr>";
        }
        ?>
    </tbody>
</table>
<?php echo $list['total'] > $limit || true ? UI::showPaging($paging, $page, $limit_arr, $limit, $list) : null ?>