<div class="widthmtrixopp tablematrikspeluang">
    <?php
    $rs_matrix = $this->data['mtoppmatrix'];
    $data = array(array());
    foreach ($rs_matrix as $k => $v) {
        $data[$v['id_dampak']][$v['id_kemungkinan']] = $v;
    }

    $rs = $this->data['rowspeluang'];
    $no = 1;
    $top_inheren = array();
    $top_paska_kontrol = array();
    $top_actual = array();
    $top_paska_mitigasi = array();
    $noarr = array();
    if ($rs)
        foreach ($rs as $r => $val) {
            if ($id_peluang_onlyone && $val['id_peluang'] != $id_peluang_onlyone) {
                $no++;
                continue;
            }

            $val['nama'] = strip_tags($val['nama']);
            $noarr[$no] = $val;
            $top_inheren[$val['inheren_dampak']][$val['inheren_kemungkinan']][] = $no;
            $top_paska_kontrol[$val['control_dampak_penurunan']][$val['control_kemungkinan_penurunan']][] = $no;
            $top_actual[$val['actual_dampak']][$val['actual_kemungkinan']][] = $no;
            $top_residual_target[$val['residual_target_dampak']][$val['residual_target_kemungkinan']][] = $no;

            $no++;
        }

    include "_matrixpeluang.php"; ?>
</div>
<div class="tablepeluang" style=" overflow: auto; display:none">
    <table class="tableku1 table table-bordered no-margin table-hover" id="export">
        <thead>
            <tr>
                <th style="width:1px;text-align:center;">NO</th>
                <th style="text-align:center;">DESKRIPSI PELUANG</th>
                <th style="text-align:center;">TINGKAT PELUANG</th>
                <th style="text-align:center;">KPI/SASARAN KERJA</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $rs = $this->data['rowspeluang'];
            $no = 1;
            if ($rs)
                foreach ($rs as $r) { ?>
                <tr>
                    <td><?= $no ?></td>
                    <td><a href="<?= site_url('panelbackend/opp_peluang/detail/' . $r['id_scorecard'] . '/' . $r['id_peluang']) ?>"><?= strip_tags($r['nama']) ?></a></td>
                    <?= labeltingkatrisiko($r['inheren_kemungkinan'] . $r['inheren_dampak']) ?>
                    <td><?= $r['kpi'] ?></td>
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
<!-- <div class="row"> -->
<!-- <div class="col-lg-12"> -->
<!-- </div> -->
<!-- </div> -->
<script type="text/javascript">
    function mtsizeopp(x) {
        console.log($(".widthmtrixopp").width());
        var scale = $(".widthmtrixopp").width() / 630;
        $("#divmatrixopp").css("transform", "scale(" + scale + ")");
        $("#divmatrixopp").css("transform-origin", "0 0");
        $(".htable").height($(".widthmtrixopp").height())
        $(".tablematrikspeluang").height($("#divmatrixopp").height() * scale);
    }
    $(function() {
        mtsizeopp(true);
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