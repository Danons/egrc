<div class="row table-responsive">
  <div class="col-sm-12">
    <table class="table table-hover dataTable">
      <thead>
        <?= UI::showHeader($header, $filter_arr, $list_sort, $list_order, (bool)($list['total'] > $limit || true), true, false) ?>
      </thead>
      <tbody>
        <?php
        $editedheader1 = ($editedheader1 && ($is_edit or !$rowheader1['is_lock'] or ($this->access_role['view_all'] or $this->access_role['view_all_unit'])));


        $i = $page;
        $unlock = 0;
        foreach ($list['rows'] as $rows) {
          $i++;
          echo "<tr>";
          foreach ($header as $rows1) {
            $val = $rows[$rows1['name']];
            if ($rows1['name'] == 'nama_aktifitas') {
              echo "<td><a href='" . ($url = base_url($page_ctrl . "/detail/$rowheader1[id_risiko]/$rows[$pk]")) . "'>" . nl2br(strip_tags($val)) . "</a><br/>";
              // echo labelkonfirmasi($rows['status_konfirmasi']);

              // echo labelverified($rows);

              echo "</td>";
            } elseif ($rows1['name'] == 'isi') {
              echo "<td>" . ReadMore($val, $url) . "</td>";
            } elseif ($rows1['name'] == 'cba') {
              echo "<td style='text-align:center'>" . ($val ? ((float)$val) . "%" : '-') . "</td>";
            } elseif ($rows1['name'] == 'id_status_pengajuan') {
              echo "<td style='text-align:center'>" . labelstatus($val) . "</td>";
            } elseif ($rows1['name'] == 'is_efektif') {
              echo "<td>" . labelefektifitas($val) . "</td>";
            } else {
              switch ($rows1['type']) {
                case 'list':
                  echo "<td style='text-align:center'>" . $rows1["value"][$val] . "</td>";
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
                  echo "<td>" . nl2br(strip_tags($val)) . "</td>";
                  break;
              }
            }
          }
          if ($rows['is_lock'] != '1')
            $unlock = 1;

          if (($pregressarr1[$rows['id_status_progress']] == '100' or $rows['status_progress'] == '100') && $this->access_role['add'] && $this->access_role['edit'] && $rows['id_mitigasi_sebelum']) {
            echo "<td style='text-align:left'>
            " . (($pregressarr1[$rows['id_status_progress']] == '100' or $rows['status_progress'] == '100') ? "<button type='button' class='btn btn-warning btn-xs' onclick=\"goSubmitValue('jadikan_control',$rows[id_mitigasi])\"><span class='bi bi-share'></span> Move to Control</button>" : "") . "
            </td>";
          } elseif ((accessbystatus($rowheader['id_status_pengajuan']) && $rows['is_lock'] != '1') or ($this->access_role['view_all'] or $this->access_role['view_all_unit'])) {
            echo "<td style='text-align:right'>";
            echo UI::showMenuMode('inlist', $rows[$pk]);
            echo "</td>";
          } else {
            echo "<td></td>";
          }
          echo "</tr>";
        }
        if (!$list['rows']) {
          echo "<tr><td colspan='" . (count($header) + 2) . "'>Data kosong</td></tr>";
        }
        ?>
      </tbody>
    </table>
    <?php echo $list['total'] > $limit || true ? UI::showPaging($paging, $page, $limit_arr, $limit, $list) : null ?>
  </div>
</div>