<div class="row">
    <div class="col-sm-12">

        <div class="float-left">
            <?php if ($this->access_role['sync']) { ?>
                <a href="javascript:void(0)" id="btnSync" class="btn btn-success" onclick="sync(this)"><span class="bi bi-arrow-repeat"></span> Sync</a>

                <script type="text/javascript">
                    function sync(e) {
                        if ($(e).attr("disabled")) {
                            alert("Sedang proses !");
                            return;
                        }

                        $(e).attr("disabled", "disabled");
                        $(e).html("Loading ...");
                        $.ajax({
                            'url': "<?= site_url("panelbackend/mt_sdm_jabatan_sync") ?>",
                            'data': {
                                'act': 'sync'
                            },
                            'type': 'POST',
                            'dataType': 'json',
                            'success': function(data) {
                                if (data.success) {
                                    swal({
                                        title: "Berhasil",
                                        text: "Sinkronisasi berhasil",
                                        type: "success",
                                        showCancelButton: false,
                                        confirmButtonColor: "#2b982b",
                                        confirmButtonText: "Ok",
                                        cancelButtonText: "Tidak",
                                        cancelButtonColor: "#DD6B55",
                                        closeOnConfirm: false
                                    }, function(isConfirm) {
                                        window.location = "<?= site_url("panelbackend/mt_sdm_jabatan_sync") ?>";
                                    });
                                } else {
                                    $(e).removeAttr("disabled");
                                    $(e).html(" Sync");
                                    swal({
                                        title: "Sinkronisasi Gagal",
                                        text: "Coba lagi ?",
                                        type: "error",
                                        // showCancelButton: true,
                                        // confirmButtonColor: "#2b982b",
                                        // confirmButtonText: "Iya",
                                        // cancelButtonText: "Tidak",
                                        // cancelButtonColor: "#DD6B55",
                                        // closeOnConfirm: true
                                    }, function(isConfirm) {
                                        // if(isConfirm){
                                        //     setTimeout(sync($("#btnSync")), 3000);
                                        // }else{
                                        // }
                                    });
                                }
                            },
                            'error': function(err, err1) {
                                $(e).removeAttr("disabled");
                            }
                        });
                    }
                </script>
            <?php } ?>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">

        <?php if (($_SESSION[SESSION_APP]['loginas'])) { ?>
            <div class="alert alert-warning">
                Anda sedang mengakses user lain. <a href="<?= site_url("panelbackend/home/loginasback") ?>" class="alert-link">Kembali</a>.
            </div>
        <?php } ?>

        <?= FlashMsg() ?>

        <table class="table table-hover dataTable tree-table">
            <thead>
                <?= UI::showHeader($header, $filter_arr, $list_sort, $list_order, false, false, false) ?>
            </thead>
            <tbody>
                <?php
                $i = $page;
                foreach ($list['rows'] as $rows) {
                    $i++;
                ?>

                    <tr data-tt-id='<?= $rows['id'] ?>' data-tt-parent-id='<?= $rows['id_parent'] ?>'>

                    <?php
                    foreach ($header as $rows1) {
                        $val = $rows[$rows1['name']];
                        if ($rows1['name'] == 'nama') {
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
                    echo "<td style='text-align:left'>";
                    echo UI::showMenuMode('inlist', $rows[$pk]);
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
<style type="text/css">
    /* table.dataTable {
    clear: both;
    margin-top: -15px !important;
    margin-bottom: 6px !important;
    max-width: none !important;
} */
</style>