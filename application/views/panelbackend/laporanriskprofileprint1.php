  <div class="widthmtrix tablematriks">
    <?php
    $rs_matrix = $this->data['mtriskmatrix'];
    $data = array(array());
    foreach ($rs_matrix as $k => $v) {
      $data[$v['id_dampak']][$v['id_kemungkinan']] = $v;
    }

    $rs = $this->data['rows'];
    $no = 1;
    $top_inheren = array();
    $top_paska_kontrol = array();
    $top_actual = array();
    $top_paska_mitigasi = array();
    $noarr = array();
    if ($rs)
      foreach ($rs as $r => $val) {
        if ($id_risiko_onlyone && $val['id_risiko'] != $id_risiko_onlyone) {
          $no++;
          continue;
        }
        $val['nama'] = strip_tags($val['nama']);
        $noarr[$no] = $val;
        $top_inheren[$val['inheren_dampak']][$val['inheren_kemungkinan']][] = $no;
        $top_paska_kontrol[$val['control_dampak_penurunan']][$val['control_kemungkinan_penurunan']][] = $no;
        // $top_actual[$val['actual_dampak']][$val['actual_kemungkinan']][] = $no;
        $top_actual[$val['residual_dampak_evaluasi']][$val['residual_kemungkinan_evaluasi']][] = $no;
        $top_residual_target[$val['residual_target_dampak']][$val['residual_target_kemungkinan']][] = $no;

        $no++;
      }

    // dpr($rs, 1);

    include "_matrix.php"; ?>
  </div>
  <div class="tablerisiko" style=" overflow: auto; display:none">
    <table class="tableku1 table table-bordered no-margin table-hover" id="export">
      <thead>
        <tr>
          <th style="width:1px;text-align:center;">NO</th>
          <th style="text-align:center;">UNIT</th>
          <th style="text-align:center;">DESKRIPSI RISIKO</th>
          <th style="text-align:center;">Residual Saat Ini</th>
          <th style="text-align:center;">Residual Setelah Evaluasi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $rs = $this->data['rows'];
        $no = 1;
        if ($rs)
          // dpr($rs);
          foreach ($rs as $r) { ?>
          <tr>
            <td><?= $no ?></td>
            <td><?= $r['unit'] ?></td>
            <td><a href="<?= site_url('panelbackend/risk_risiko/detail/' . $r['id_scorecard'] . '/' . $r['id_risiko']) ?>"><?= ($r['nama']) ?></a></td>
            <?= labeltingkatrisiko((float)$r['control_kemungkinan_penurunan'] * (float)$r['control_dampak_penurunan'] * (float)$r['is_opp_inherent']) ?>
            <?= labeltingkatrisiko((float)$r['residual_kemungkinan_evaluasi'] * (float)$r['residual_dampak_evaluasi'] * (float)$r['is_opp_inherent']) ?>
          </tr>
        <?php $no++;
          }
        if (!($rs)) {
          echo "<tr><td colspan='3' style='background-color: white;'>Data kosong</td></tr>";
        }
        ?>
      </tbody>
    </table>
    <!-- <div class="table-responsive">
    </div> -->
  </div>
  <!-- <div style="clear: both;"></div> -->
  <!-- <div class="row"> -->
  <!-- <div class="col-lg-12"> -->
  <!-- </div> -->
  <!-- </div> -->
  <script type="text/javascript">
    function mtsize(x) {
      console.log($(".widthmtrix").width());
      var scale = $(".widthmtrix").width() / 630;
      $("#divmatrix").css("transform", "scale(" + scale + ")");
      $("#divmatrix").css("transform-origin", "0 0");
      $(".htable").height($(".widthmtrix").height());
      $(".tablematriks").height($("#divmatrix").height() * scale);
    }
    $(function() {
      mtsize(true);
      $('.textmore').each(function() {
        var textmore = $(this).html();
        var loop = textmore.split(" ");
        var str1 = '';
        var str2 = '';
        for (var i = 0; i < loop.length; i++) {
          if (i <= 4)
            str1 += loop[i] + ' ';
          else
            str2 += loop[i] + ' ';
        }

        $(this).html(str1 + '<span class="morehide" style="display:none">' + str2 + '</span>');
      });
    })
  </script>

  <style>
    #container {
      width: 100%;
      font-size: 14px;
      font-family: Arial, Helvetica, sans-serif;
    }

    /* .h4,
  .h5,
  .h6,
  h4,
  h5,
  h6,
  hr {
    margin-top: 5px;
    margin-bottom: 5px;
  } */

    .tableku1 thead {
      page-break-before: always;
    }

    .css_only_one td {
      font-weight: bold !important;
    }

    .tableku1 {
      width: 100%;
    }

    .tableku1 td {
      padding: 10px 5px !important;
      vertical-align: top;
      /*font-size: 12px !important;*/
    }

    .tableku1 thead th {
      padding: 10px 5px !important;
      text-align: center;
    }

    .tableku1 th {
      padding: 0px 3px !important;
      text-align: center;
      /*font-size: 12px !important;*/
    }

    .btn-show {
      float: right;
      height: 0px;
      margin-top: -1px;
    }

    .btn-hide {
      float: right;
      height: 15px;
    }
  </style>