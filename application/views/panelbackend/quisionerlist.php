<div class="w-100 d-flex justify-content-start">
    <?php if($_SESSION[SESSION_APP]['view_all']){?>
    <div onClick='getRecap(<?= $id_kategori ?>, "get_detail")' class='btn btn-sm btn-primary'>Rekap Per Jabatan & Pegawai</div>
    <div onClick='getRecap(<?= $id_kategori ?>, "get_detail_all")' class='btn btn-sm btn-primary'>Rekap All</div>
<?php }?>
</div>
<table class="table table-striped table-hover dataTable">
    <tbody>
        <?php
        $i = $page;
        if ($list['rows'])
            foreach ($list['rows'] as $rows) {
                $i++;
                echo "<tr>";
                echo "<td>$i</td>";
                echo "<td>";
                // dpr($rows);
                $padding = 0;
                for ($l = 1; $l < $rows['level']; $l++)
                    $padding += 50;

                echo "<div style='padding-left:" . $padding . "px'>";
                echo $rows['pertanyaan'];
                if ($edited && $id_jabatan_filter) {
                    echo "<br/>";
                    switch ($rows['jenis_jawaban']) {
                        case "1sampai5":
                            echo UI::createRadio(
                                "nilai[" . $rows['id_quisioner'] . "]",
                                [
                                    "1" => "Sangat Kurang",
                                    "2" => "Kurang",
                                    "3" => "Cukup",
                                    "4" => "Baik",
                                    "5" => "Sangat Baik",
                                ],
                                $rows['nilai'],
                                $edited
                            );
                            break;
                        case "yatidak":
                            echo UI::createRadio(
                                "nilai[" . $rows['id_quisioner'] . "]",
                                [
                                    "5" => "Ya",
                                    "1" => "Tidak",
                                ],
                                $rows['nilai'],
                                $edited
                            );
                            break;
                        case "uraian":
                            echo UI::createTextArea(
                                "jawaban[" . $rows['id_quisioner'] . "]",
                                $rows['jawaban'],
                                '2',
                                '2',
                                $edited,
                                'form-control',
                                'style="width:100%"'
                            );
                            break;
                    }
                }
                echo "</div>";
                echo "</td>";
                echo "<td style='text-align:right'>
            " . UI::showMenuMode('inlist', $rows[$pk]) . "
            </td>";
                echo "</tr>";
            }
        if ($list['rows'] && !count($list['rows'])) {
            echo "<tr><td colspan='" . (count($header) + 2) . "'>Data kosong</td></tr>";
        }
        ?>
    </tbody>
</table>
<?php if ($edited && $id_jabatan_filter && (!$rows['id_jabatan'] || $rows['id_jabatan'] == $id_jabatan_filter || $this->view_all)) { ?>
    <div style="text-align:right">
        <?php if ($rows['id_jabatan']) { ?>
            <button type='button' onclick="if(confirm('Apa Anda yakin akan menghapus ?')){goSubmit('delete')}" class="btn btn-danger">Hapus</button>
        <?php } ?>
        <button type='button' onclick="goSubmit('save')" class="btn btn-primary">Simpan</button>
    </div>
<?php } ?>
<div class="modal fade" id="modaldetail" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document" style="width: 90%;max-width:1500px">
        <div class="modal-content">
            <div class="modal-header">
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
<script>
    const getRecap = (par1, par2) => {
        detail(par1, par2)
    }


    function detail(par1, par2, isrefresh) {

        $.ajax({
            type: "post",
            url: "<?= current_url() ?>",
            data: {
                act: par2,
                id_kategori: par1,
                // id_kriteria: id_kriteria,
            },
            success: function(ret) {
                $('#iddetail').html(ret);
            }
        });

        console.log(isrefresh)

        if (isrefresh == undefined)
            $('#modaldetail').modal('toggle');
    }
</script>