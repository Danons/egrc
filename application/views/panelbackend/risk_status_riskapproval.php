<div class="container-fluid">
    <?php /*
    <div style="width:33%;float:left;" <?php if (!$tipe) { ?>class="info-box-flat-active" <?php } else { ?>class="info-box-flat" <?php } ?>>
        <div class="content">
            <div class="text">
                <a href="<?= site_url('panelbackend/risk_status_risk/index/0') ?>">
                    APPROVAL
                </a>
            </div>
        </div>
    </div>
    <div style="width:33%;float:left;" <?php if ($tipe == 1) { ?>class="info-box-flat-active" <?php } else { ?>class="info-box-flat" <?php } ?>>
        <div class="content">
            <div class="text">
                <a href="<?= site_url('panelbackend/risk_status_risk/index/1') ?>">
                    RISIKO INTERNAL
                </a>
            </div>
        </div>
    </div>
    <div style="width:33%;float:left;" <?php if ($tipe == 2) { ?>class="info-box-flat-active" <?php } else { ?>class="info-box-flat" <?php } ?>>
        <div class="content">
            <div class="text">
                <a href="<?= site_url('panelbackend/risk_status_risk/index/2') ?>">
                    RISIKO INTERDEPENDENT
                </a>
            </div>
        </div>
    </div>
    <div style="clear: both;"></div>
    <br /> */ ?>
    <!-- Basic Table -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body table-responsive">

                    <?php if (($_SESSION[SESSION_APP]['loginas'])) { ?>
                        <div class="alert alert-warning">
                            Anda sedang mengakses user lain. <a href="<?= site_url("panelbackend/home/loginasback") ?>" class="alert-link">Kembali</a>.
                        </div>
                    <?php } ?>

                    <?= FlashMsg() ?>


                    <div style="text-align: right;">
                        <button type="button" onclick="if(confirm('Apa Anda yakin ?')){goSubmit('save')}" class="btn-success btn btn-lg">
                            <span class="bi bi-upload"></span>
                            Simpan
                        </button>
                    </div>


                    <table class="table table-bordered table-hover dataTable">
                        <thead>
                            <tr>
                                <th style='vertical-align: middle; text-align:center' rowspan="2">Nama Risiko</th>
                                <th style='vertical-align: middle; text-align:center' rowspan="2">Dari</th>
                                <th style='vertical-align: middle; text-align:center'>
                                    Aksi
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="radio" name="id_risikocoll" id="id_risikocoll_2" value="2"><label for="id_risikocoll_2"> <span style="color:red">Dikembalikan</span></label>&nbsp;&nbsp;
                                    <input type="radio" name="id_risikocoll" id="id_risikocoll_1" value="1"><label for="id_risikocoll_1"> <span style="color:green">Disetujui</span></label><br />
                                    <button type="button" class="btn btn-xs btn-default" onclick="$('input[name=\'id_risikocoll\']').prop('checked', false); $('input[name^=\'id_risiko\']').prop('checked', false)">clear</button>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = $page;
                            foreach ($list['rows'] as $rows) {
                                $i++;
                                echo "<tr>";
                                echo "<td style='vertical-align: middle;'><a href='" . ($url = site_url("panelbackend/risk_risiko/detail/{$rows['id_scorecard']}/$rows[id_risiko]")) . "'>$rows[nama]</a></td>";
                                echo "<td>$rows[dari]</td>";
                                echo "<td>" . UI::createRadio("id_risiko[$rows[id_risiko]]", array("2" => "<span style='color:red'>Dikembalikan</span>", "1" => "<span style='color:green'>Disetujui</span>"), null, true, false, "form-control checkirrisk");
                            ?>
                                <br /><button type="button" class="btn btn-xs btn-default" onclick="$('input[name=\'id_risiko[<?= $rows['id_risiko'] ?>]\']').prop('checked', false)">clear</button>
                            <?php
                                echo "</td>";
                                echo "</tr>";
                            }
                            if (!$list['rows']) {
                                echo "<tr><td colspan='" . (count($header) + 2) . "'>Data kosong</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>








                    <?php echo $list['total'] > $limit || true ? UI::showPaging($paging, $page, $limit_arr, $limit, $list) : null ?>

                    <div style="clear: both;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $("input[name='id_risikocoll']").change(function() {
            console.log($("input[name*='id_risiko']"));
            $("input[name*='id_risiko'][value=" + $(this).val() + "]").prop('checked', true);
        });
    })
</script>
<style type="text/css">
    table.dataTable {
        clear: both;
        margin-top: 10px !important;
        /* margin-bottom: 6px !important;
        max-width: none !important; */
    }
</style>