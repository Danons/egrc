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
<ul class="nav nav-tabs d-flex flex-nowrap " style="width: 100%;overflow-y:hidden; overflow-x: scroll; height:auto;">
    <?php $no = 1;
    foreach ($parentarr as $k => $v) { ?>
        <li class="nav-item d-flex align-items-center mb-1">
            <a class="nav-link d-flex text-nowrap py-2 <?= $id_parent == $k ? 'active' : '' ?>" aria-current="page" href="#" onclick="goSubmitValue('set_parent',<?= $k ?>)"><?= ($no++) . ". " . $v ?></a>
            <?php if ($id_parent == $k) {
                if ($this->access_role['edit']) { ?>
                    <a href="javascript:void(0)" style="padding:.5rem 1rem; margin-left:-25px; z-index:1" onclick="goSubmitModal('<?= site_url("panelbackend/mt_kriteria/edit/$id_kategori/$k") ?>','edit', 'Ubah Kriteria')">
                        <i class="bi bi-pencil-square"></i>
                    </a>
            <?php }
                /*if ($this->access_role['delete']) { ?>
                    <a href="<?= site_url("panelbackend/mt_kriteria/delete/$id_kategori/$k") ?>" onclick="if(confirm('Apakah Anda yakin akan menghapus ?')){return true;}else{return false;}" style="padding:.5rem 1rem; margin-left:-25px; z-index:1">
                        <i class="bi bi-x-square"></i>
                    </a>
            <?php }*/
            } ?>
        </li>
    <?php } ?>
</ul>
<?php if ($this->access_role['edit']) { ?>

    <a class="nav-link ps-0" aria-current="page" href="javascript:void(0)" onclick="goSubmitModal('<?= site_url("panelbackend/mt_kriteria/add/$id_kategori") ?>','edit', 'Tambah Kriteria')">
        <i class="bi bi-plus-square-fill"></i>
    </a>

<?php } ?>
<table class="table table-sticky table-bordered">
    <thead>
        <tr>
            <?php if ($id_kategori_jenis == 1 || $id_kategori_jenis == 2) { ?>
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
            <!-- <th>Unit</th> -->
            <th width="4px" rowspan="2"></th>
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
        // dpr($arearr,1);
        function print_table($arearr, $level = 0, $id_kategori_jenis, &$total_bobot)
        {
            $level++;
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

                        echo "<td rowspan='" . $r1['rowspan'] . "'>";
                        echo $r1['kode'];
                        echo "</td>";

                        echo "<td rowspan='" . $r1['rowspan'] . "'>";
                        echo nl2br($r1['nama']);
                        echo "</td>";

                        if ($id_kategori_jenis == 1) {
                            echo "<td rowspan='" . $r1['rowspan'] . "'>";
                            echo $r1['bobot'];
                            $total_bobot += $r1['bobot'];
                            echo "</td>";
                        }

                        $r2 = $r1['sub2'][0];
                        if ($id_kategori_jenis == 3) {
                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo $r2['kode_lvl'];
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
                            echo $r2['d'];
                            echo "</td>";

                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo $r2['k'];
                            echo "</td>";

                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo $r2['w'];
                            echo "</td>";

                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo $r2['o'];
                            echo "</td>";
                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo $r2['is_aktif'];
                            echo "</td>";
                        }

                        if ($r2['sub3']) {
                            $r3 = $r2['sub3'][0];

                            if ($id_kategori_jenis == 2) {
                                echo "<td rowspan='" . $r3['rowspan'] . "'>";
                                echo $r3['kode_lvl'];
                                echo "</td>";
                            }

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['kode'];
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo nl2br($r3['nama']);
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['d'];
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['k'];
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['w'];
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['o'];
                            echo "</td>";
                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['is_aktif'];
                            echo "</td>";
                        }

                        echo "</tr>";

                        if ($r2['sub3'])
                            print_table($r2['sub3'], 3, $id_kategori_jenis, $total_bobot);

                        if ($r1['sub2'])
                            print_table($r1['sub2'], 2, $id_kategori_jenis, $total_bobot);

                        if ($r['sub1'])
                            print_table($r['sub1'], 1, $id_kategori_jenis, $total_bobot);

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
                            $total_bobot += $r['bobot'];
                            echo "</td>";
                        }

                        $r2 = $r['sub2'][0];

                        if ($id_kategori_jenis == 3) {
                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo $r2['kode_lvl'];
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
                            echo $r2['d'];
                            echo "</td>";

                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo $r2['k'];
                            echo "</td>";

                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo $r2['w'];
                            echo "</td>";

                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo $r2['o'];
                            echo "</td>";
                            echo "<td rowspan='" . $r2['rowspan'] . "'>";
                            echo $r2['is_aktif'];
                            echo "</td>";
                        }


                        if ($r2['sub3']) {
                            $r3 = $r2['sub3'][0];

                            if ($id_kategori_jenis == 2) {
                                echo "<td rowspan='" . $r3['rowspan'] . "'>";
                                echo $r3['kode_lvl'];
                                echo "</td>";
                            }

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['kode'];
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo nl2br($r3['nama']);
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['d'];
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['k'];
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['w'];
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['o'];
                            echo "</td>";
                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['is_aktif'];
                            echo "</td>";
                        }

                        echo "</tr>";

                        if ($r2['sub3'])
                            print_table($r2['sub3'], 3, $id_kategori_jenis, $total_bobot);

                        if ($r['sub2'])
                            print_table($r['sub2'], 2, $id_kategori_jenis, $total_bobot);
                        break;
                    case 3:
                        echo "<tr>";

                        if ($id_kategori_jenis == 3) {
                            echo "<td rowspan='" . $r['rowspan'] . "'>";
                            echo $r['kode_lvl'];
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
                            echo $r['d'];
                            echo "</td>";

                            echo "<td rowspan='" . $r['rowspan'] . "'>";
                            echo $r['k'];
                            echo "</td>";

                            echo "<td rowspan='" . $r['rowspan'] . "'>";
                            echo $r['w'];
                            echo "</td>";

                            echo "<td rowspan='" . $r['rowspan'] . "'>";
                            echo $r['o'];
                            echo "</td>";
                            echo "<td rowspan='" . $r['rowspan'] . "'>";
                            echo $r['is_aktif'];
                            echo "</td>";
                        }


                        if ($r['sub3']) {
                            $r3 = $r['sub3'][0];

                            if ($id_kategori_jenis == 2) {
                                echo "<td rowspan='" . $r3['rowspan'] . "'>";
                                echo $r3['kode_lvl'];
                                echo "</td>";
                            }

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['kode'];
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo nl2br($r3['nama']);
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['d'];
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['k'];
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['w'];
                            echo "</td>";

                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['o'];
                            echo "</td>";
                            echo "<td rowspan='" . $r3['rowspan'] . "'>";
                            echo $r3['is_aktif'];
                            echo "</td>";
                        }

                        echo "</tr>";

                        if ($r['sub3'])
                            print_table($r['sub3'], 3, $id_kategori_jenis, $total_bobot);
                        break;
                    case 4:
                        echo "<tr>";

                        if ($id_kategori_jenis == 2) {
                            echo "<td rowspan='" . $r['rowspan'] . "'>";
                            echo $r['kode_lvl'];
                            echo "</td>";
                        }

                        echo "<td rowspan='" . $r['rowspan'] . "'>";
                        echo $r['kode'];
                        echo "</td>";

                        echo "<td rowspan='" . $r['rowspan'] . "'>";
                        echo nl2br($r['nama']);
                        echo "</td>";

                        echo "<td rowspan='" . $r['rowspan'] . "'>";
                        echo $r['d'];
                        echo "</td>";

                        echo "<td rowspan='" . $r['rowspan'] . "'>";
                        echo $r['k'];
                        echo "</td>";

                        echo "<td rowspan='" . $r['rowspan'] . "'>";
                        echo $r['w'];
                        echo "</td>";

                        echo "<td rowspan='" . $r['rowspan'] . "'>";
                        echo $r['o'];
                        echo "</td>";
                        echo "<td rowspan='" . $r['rowspan'] . "'>";
                        echo $r['is_aktif'];
                        echo "</td>";

                        echo "</tr>";
                        break;
                }
            }
        }

        print_table($arearr, 0, $id_kategori_jenis, $total_bobot); ?>

        <?php if ($id_kategori_jenis == 1) { ?>
            <tr>
                <td colspan="4" style="text-align:right;font-weight:bold">Total</td>
                <td style="text-align:right;font-weight:bold"><?= $total_bobot ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>


<div class="modal fade" id="modaldetail" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Unsur Pemenuhan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="iddetail">
            </div>
            <div class="modal-footer">
                <!-- <button type="button" style="display: none;" id="btnback" class="btn btn-link waves-effect" onclick="backDetail()">BACK</button> -->
                <button type="button" id="btnsave" class="btn btn-primary waves-effect" onclick="goSubmit('save_attribute')">Simpan</button>
                <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function attribute(id_kriteria) {
        var datap = {
            act: 'get_attribute',
            id_kriteria: id_kriteria,
        };
        $('#modaldetail').modal('toggle');
        $.ajax({
            type: "post",
            url: "<?= current_url() ?>",
            data: datap,
            success: function(ret) {
                $('#iddetail').html(ret);
            }
        });
    }
</script>