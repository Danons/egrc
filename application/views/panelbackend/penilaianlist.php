<style type="text/css">
    table.dataTable {
        clear: both;
        margin-bottom: 6px !important;
        max-width: none !important;
    }

    .table th,
    .table td {
        padding: 5px !important;
        font-size: 12px;
    }

    ::-webkit-scrollbar {
        height: 5px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: transparent;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
<div class="d-flex justify-content-between w-100">
    <ul class="nav nav-tabs d-flex flex-nowrap" style="overflow-x:scroll; overflow-y:hidden;">
        <?php $no = 1;
        // dpr($penilaiansessionarr);
        foreach ($parentarr as $k => $v) { ?>
            <li class="nav-item" style="display: flex; text-wrap:nowrap;">
                <?php if ($id_parent == $k) { ?>
                    <a class="nav-link <?= $id_parent == $k ? 'active' : '' ?>" aria-current="page" href="#" onclick="goSubmitValue('set_parent',<?= $k ?>)"><?= $v ?></a>
                <?php   } else {
                    if ($id_kategori_jenis == '3')
                        $v = 'Elemen';
                    else
                        $v = 'Aspek';
                ?>
                    <a class="nav-link <?= $id_parent == $k ? 'active' : '' ?>" aria-current="page" href="#" onclick="goSubmitValue('set_parent',<?= $k ?>)"><?= $v . ' ' . ($no) ?></a>
                <?php } ?>
            </li>
        <?php $no++;
        } ?>
    </ul>

    <?php
    if ($penilaiansessionsebelumnya) {
        echo "<h5>Di Bandingkan Dengan " . $penilaiansessionarr[$penilaiansessionsebelumnya] . "</h5>";
    }
    ?>

</div>

<div id="content-table">
    <?php include "penilaiantable.php"; ?>
</div>
<?php if ($is_admin) { ?>
    <script type="text/javascript">
        function change_aktif(t, id_penilaian_periode) {
            var v = $(t).is(':checked');
            console.log(v);
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: "<?= current_url() ?>",
                data: {
                    act: "update_aktif",
                    id_penilaian_periode: id_penilaian_periode,
                    is_aktif: v
                },
                success: function(d) {

                }
            });
        }
    </script>
<?php } ?>

<div class="modal fade" id="modaldetail" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document" style="width: 90%;max-width:1500px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    DETAIL KRITERIA
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="iddetail">
            </div>
            <div class="modal-footer">
                <!-- <button type="button" style="display: none;" id="btnback" class="btn btn-link waves-effect" onclick="backDetail()">BACK</button>
                <button type="button" id="btnsave" class="btn btn-primary waves-effect" onclick="goSubmitx1('save')">SAVE</button> -->
                <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">Close</button>
                <span id="btnsavemodalkriteria"></span>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function ajukan(id_penilaian, id_penilaian_periode, id_kriteria) {
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: "<?= current_url() ?>",
            data: {
                act: "update_status",
                id_penilaian: id_penilaian,
                status: 1
            },
            success: function(d) {
                var data = $("#main_form").serializeObject();
                data.act = 'content-table';

                $.ajax({
                    type: "post",
                    url: "<?= current_url() ?>",
                    data: data,
                    success: function(ret) {
                        $('#content-table').html(ret);
                    }
                });

                detail(id_penilaian_periode, id_kriteria, 1);
            }
        });
    }

    <?php if ($is_admin) { ?>


        function update_penilaian(id_penilaian) {
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: "<?= current_url() ?>",
                data: {
                    act: "update_penilaian",
                    id_penilaian: id_penilaian,
                    // status: $("#status\\[" + id_penilaian + "\\]").val(),

                    skor_d: $("#skor_d\\[" + id_penilaian + "\\]").val(),
                    tgl_d: $("#tgl_d\\[" + id_penilaian + "\\]").val(),
                    simpulan_d: $("#simpulan_d\\[" + id_penilaian + "\\]").val(),
                    saran_d: $("#saran_d\\[" + id_penilaian + "\\]").val(),

                    skor_k: $("#skor_k\\[" + id_penilaian + "\\]").val(),
                    tgl_k: $("#tgl_k\\[" + id_penilaian + "\\]").val(),
                    simpulan_k: $("#simpulan_k\\[" + id_penilaian + "\\]").val(),
                    saran_k: $("#saran_k\\[" + id_penilaian + "\\]").val(),

                    skor_w: $("#skor_w\\[" + id_penilaian + "\\]").val(),
                    tgl_w: $("#tgl_w\\[" + id_penilaian + "\\]").val(),
                    simpulan_w: $("#simpulan_w\\[" + id_penilaian + "\\]").val(),
                    saran_w: $("#saran_w\\[" + id_penilaian + "\\]").val(),

                    skor_o: $("#skor_o\\[" + id_penilaian + "\\]").val(),
                    tgl_o: $("#tgl_o\\[" + id_penilaian + "\\]").val(),
                    simpulan_o: $("#simpulan_o\\[" + id_penilaian + "\\]").val(),
                    saran_o: $("#saran_o\\[" + id_penilaian + "\\]").val(),


                    // skor_f: $("#skor_f\\[" + id_penilaian + "\\]").val(),
                    // simpulan_f: $("#simpulan_f\\[" + id_penilaian + "\\]").val(),
                    // saran_f: $("#saran_f\\[" + id_penilaian + "\\]").val(),
                },
                success: function(d) {
                    $(".alertpopup").remove();
                    if (d.success) {
                        $("#iddetail").prepend('<div style="display:none" class="alertpopup alert-dismissible alert alert-success" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>Data Tersimpan</div>');
                        $(".alertpopup").fadeIn(1000);
                    }

                    var data = $("#main_form").serializeObject();
                    data.act = 'content-table';

                    $.ajax({
                        type: "post",
                        url: "<?= current_url() ?>",
                        data: data,
                        success: function(ret) {
                            $('#content-table').html(ret);
                        }
                    });

                }
            });
        }

        function change_status(t, id_penilaian) {
            var v = $(t).val();
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: "<?= current_url() ?>",
                data: {
                    act: "update_status",
                    id_penilaian: id_penilaian,
                    status: v
                },
                success: function(d) {
                    var data = $("#main_form").serializeObject();
                    data.act = 'content-table';

                    $.ajax({
                        type: "post",
                        url: "<?= current_url() ?>",
                        data: data,
                        success: function(ret) {
                            $('#content-table').html(ret);
                        }
                    });
                }
            });
        }

        function chenge_keterangan(t, id_penilaian, elpk) {
            var v = $(t).val();
            var id_penilaian_komentar = $(elpk).val();

            $.ajax({
                type: 'post',
                dataType: 'json',
                url: "<?= current_url() ?>",
                data: {
                    act: "update_keterangan",
                    id_penilaian: id_penilaian,
                    keterangan: v,
                    id_penilaian_komentar: id_penilaian_komentar
                },
                success: function(d) {
                    $(elpk).val(d.id_penilaian_komentar)
                }
            });
        }
    <?php } ?>

    function detail(id_penilaian_periode, id_kriteria, isrefresh) {

        $.ajax({
            type: "post",
            url: "<?= current_url() ?>",
            data: {
                act: 'get_detail',
                id_penilaian_periode: id_penilaian_periode,
                id_kriteria: id_kriteria,
            },
            success: function(ret) {
                $('#iddetail').html(ret);
            }
        });

        if (isrefresh == undefined)
            $('#modaldetail').modal('toggle');
    }

    function attribute(id_penilaian, id_kriteria) {

        $.ajax({
            type: "post",
            url: "<?= current_url() ?>",
            data: {
                act: 'get_attribute',
                id_penilaian: id_penilaian,
                id_kriteria: id_kriteria,
            },
            success: function(ret) {
                $('#iddetail').html(ret);
            }
        });
    }

    <?php if ($_SESSION[SESSION_APP][$this->page_ctrl]['idx']) {

        list($id_penilaian, $id_kriteria) = explode("_", $_SESSION[SESSION_APP][$this->page_ctrl]['idx']);
        unset($_SESSION[SESSION_APP][$this->page_ctrl]['idx']);

    ?>

        $(function() {
            attribute(<?= $id_penilaian ?>, <?= $id_kriteria ?>);
            $('#modaldetail').modal('toggle');
        });

    <?php } ?>

    function goSubmitx(act, key) {
        $("#key").val(key);
        var data = $("#main_form").serializeObject();
        data.act = 'get_detail';
        data.act1 = act;

        $.ajax({
            type: "post",
            url: "<?= current_url() ?>",
            data: data,
            success: function(ret) {
                $('#iddetail').html(ret);
            }
        });
    }

    function goSubmitx1(act, key) {
        $("#key").val(key);
        var data = $("#main_form").serializeObject();
        data.act = 'get_attribute';
        data.act1 = act;

        $.ajax({
            type: "post",
            url: "<?= current_url() ?>",
            data: data,
            success: function(ret) {
                $('#iddetail').html(ret);
            }
        });
    }
</script>