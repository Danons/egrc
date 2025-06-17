  <table class="table table-hover dataTable">
      <thead>
          <tr>
              <!-- <th width="30" rowspan="2">Kode</th> -->
              <th width="30%" rowspan="2">Nilai Dampak</th>
              <?php
                $rowskategori = $this->conn->GetArray("select * from mt_risk_dampak where deleted_date is null order by id_dampak");
                foreach ($rowskategori as $r) {
                ?>
                  <th align="center"><?= $r['rating'] ?></th>
              <?php } ?>
              <th rowspan="2"></th>
          </tr>
          <tr>
              <?php
                $rowskategori = $this->conn->GetArray("select * from mt_risk_dampak where deleted_date is null order by id_dampak");
                foreach ($rowskategori as $r) {
                ?>
                  <th align="center"><?= $r['nama'] ?></th>
              <?php } ?>
          </tr>
      </thead>
      <tbody>
          <?php
            $i = $page;
            foreach ($list['rows'] as $rows) {
                $i++;
            ?>

              <tr>

                  <?php
                    // echo "<td>$rows[kode]</td>";
                    echo "<td>$rows[nama]</td>";
                    foreach ($rowskategori as $r) {

                        echo "<td>";
                        echo nl2br($rows[$r['id_dampak']]);
                        echo "</td>";
                    } ?>
              <?php
                echo "<td style='text-align:right'>
        " . UI::showMenuMode('inlist', $rows[$pk]) . "
        </td>";
                echo "</tr>";
            }
            if (!$list['rows']) {
                echo "<tr><td colspan='" . (count($header) + 2) . "'>Data kosong</td></tr>";
            }
                ?>
      </tbody>
  </table>
  <?php echo $list['total'] > $limit || true ? UI::showPaging($paging, $page, $limit_arr, $limit, $list) : null ?>