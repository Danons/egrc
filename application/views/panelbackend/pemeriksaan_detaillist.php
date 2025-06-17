<table class="table table-striped table-hover dataTable table-bordered">
    <thead>
        <tr>
            <th rowspan="2" style="width: 1px;">No</th>
            <th rowspan="2">Uraian</th>
            <th rowspan="2">Dilaksanakan Oleh</th>
            <th rowspan="2">Nomor KKA</th>
            <th colspan="2" style="text-align: center;">Waktu Audit</th>
            <th rowspan="2" style="width:10px"></th>
        </tr>
        <tr>
            <th style="text-align:right">Anggaran</th>
            <th style="text-align:right">Realisasi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1;
        foreach ($list['rows'] as $r) { ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><a href="<?= site_url("panelbackend/pemeriksaan_detail/detail/$r[id_pemeriksaan]/$r[id_pemeriksaan_detail]") ?>"><?= $r['uraian'] ?></a></td>
                <td><?= $r['nama_user'] ?> - <?= $r['nama_jabatan'] ?></td>
                <td><?= $kkaarr[$r['id_kka']] ?></td>
                <td style="text-align: right;"><?= rupiah($r['anggaran']) ?></td>
                <td style="text-align: right;"><?= rupiah($r['realisasi_anggaran']) ?></td>
                <td style='text-align:right'>
                    <?= UI::showMenuMode('inlist', $r[$pk]) ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>


<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 ">

    <h4 class="h4">Biaya Audit</h4>
    <div class="btn-toolbar mb-2 mb-md-0">
        <?php if ($this->access_role['add']) { ?>
            <button type="button" class="btn btn-sm btn-primary" onclick="goAddBiaya()">Tambah Baru</button>
            <script>
                function goAddBiaya() {
                    window.location = "<?= site_url("panelbackend/pemeriksaan_anggaran_biaya/add/$rowheader[id_pemeriksaan]") ?>";
                }
            </script>
        <?php } ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <table class="table table-striped table-hover dataTable table-bordered">
            <thead>
                <tr>
                    <th style="width:10px">No</th>
                    <th style="text-align:left; max-width:auto; cursor:pointer;">Nama</th>
                    <th style="text-align:right; max-width:auto; cursor:pointer;">Nilai Realisasi</th>
                    <th style="width:10px"></th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($listanggaran['rows'] as $rows) { ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $rows['nama'] ?></td>
                        <td style="text-align:right"><?= rupiah($rows['nilai_realisasi']) ?></td>
                        <td style="text-align:right">
                            <div class="dropdown" style="display:inline">
                                <a href="javascript:void(0)" class="dropdown" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="color:#0052cc;display:inline-block;">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenu2" style="margin-top:-20px">
                                    <?php if ($this->access_role['edit']) { ?>
                                        <li><a href="javascript:void(0)" class=" dropdown-item " onclick="goEditBiaya('<?= $rows['id_pemeriksaan_anggaran_biaya'] ?>')"><i class="bi bi-pencil"></i> Edit</a> </li>
                                    <?php }
                                    if ($this->access_role['delete']) { ?>
                                        <li><a href="javascript:void(0)" class=" dropdown-item " onclick="goDeleteBiaya('<?= $rows['id_pemeriksaan_anggaran_biaya'] ?>')"><i class="bi bi-trash"></i> Delete</a> </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php
                    $total += $rows['nilai_realisasi'];
                } ?>
                <tr>
                    <td colspan="2"><b>Total</b></td>
                    <td style="text-align:right"><?= rupiah($total) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    <?php if ($this->access_role['edit']) { ?>

        function goEditBiaya(id) {
            window.location = "<?= base_url("panelbackend/pemeriksaan_anggaran_biaya/edit/" . $rowheader['id_pemeriksaan']) ?>/" + id;
        }
    <?php }
    if ($this->access_role['delete']) { ?>

        function goDeleteBiaya(id) {
            if (confirm("Apakah Anda yakin akan menghapus ?")) {
                window.location = "<?= base_url("panelbackend/pemeriksaan_anggaran_biaya/delete/" . $rowheader['id_pemeriksaan']) ?>/" + id;
            }
        }
    <?php } ?>
</script>