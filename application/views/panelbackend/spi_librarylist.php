<table class="table table-striped table-hover dataTable">
    <thead>
        <?= UI::showHeaderSpiLibrary($header, $filter_arr, $list_sort, $list_order); ?>
    </thead>
    <tbody>
        <?php
        $i = $page;
        foreach ($list['rows'] as $rows) {
            // dpr($rows);
            $i++;
            echo "<tr>";
            echo "<td>$i</td>";
            foreach ($header as $rows1) {
                $val = $rows[$rows1['name']];
        ?>
                <td>
                    <div style="display: flex; align-items: center; ">
                        <div id="miniIcon<?= $rows['id_dokumen'] ?>" onclick="toogleHidden(<?= $rows['id_dokumen'] ?>)" style="font-weight: bold; display: inline; font-size: larger; cursor: pointer; "><i class="bi bi-plus bolder"></i></div>
                        <p style="margin-bottom: 0px; font-size: 13px; color: gray;">
                            &nbsp;<?= $rows['nomor_dokumen'] ?>
                        </p>

                    </div>
                    <?= $val ?>
                    <div style="display: flex; align-items: center; ">
                        <p class="mb-0 bg-secondary rounded px-1 display: flex; align-items: center;" style="font-size: 14px;"><?= $rows['kategori_dokumen'] ?></p>
                    </div>
                    <div class="display-hidden" id="hidden-block<?= $rows['id_dokumen'] ?>" style="margin-top: 10px;">
                        <div>
                            <table>
                                <tr>
                                    <td>No. Dokumen</td>
                                    <td style="padding: 0px 10px;">:</td>
                                    <td><?= $rows['nomor_dokumen'] ?></td>
                                </tr>
                                <tr>
                                    <td>Judul Dokumen</td>
                                    <td style="padding: 0px 10px;">:</td>
                                    <td><?= $rows['judul_dokumen'] ?></td>
                                </tr>
                                <tr>
                                    <td>Kategori Dokumen</td>
                                    <td style="padding: 0px 10px;">:</td>
                                    <td><?= $rows['kategori_dokumen'] ?></td>
                                </tr>
                                <tr>
                                    <td>Tanggal Dokumen</td>
                                    <td style="padding: 0px 10px;">:</td>
                                    <td><?= Eng2Ind($rows['tanggal_dokumen'], false) ?></td>
                                </tr>
                                <tr>
                                    <td>Sumber Dokumen</td>
                                    <td style="padding: 0px 10px;">:</td>
                                    <td><?= $rows['sumber_dokumen'] ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
            <?php } ?>
            <td>
                <a href="<?= site_url($page_ctrl . "/open_file/" . $id_files[$rows['id_dokumen']]['id_dokumen']) ?>" class="btn btn-success"><i class="bi bi-eye"></i></a>
                <a href="<?= base_url() ?>panelbackend/spi_library/detail/<?= $rows['id_dokumen'] ?>" class="btn btn-primary"><i class="bi bi-pencil"></i></a>
                <a class="btn btn-danger" onclick="goDelete(<?= $rows['id_dokumen'] ?>)"><i class="bi bi-trash"></i></a>
            </td>
            </tr>
        <?php }
        if (!count($list['rows'])) {
            echo "<tr>
            <td colspan='" . (count($header) + 2) . "'>Data kosong</td>
        </tr>";
        }
        ?>
    </tbody>
</table>
<?= UI::showPaging($paging, $page, $limit_arr, $limit, $list) ?>

<style>
    .display-hidden {
        display: none;
    }

    .display-block {
        display: block;
    }

    .miniIcon {
        -webkit-text-stroke: 1px;
    }
</style>
<script>
    function update(value, newValue) {
        value = newValue;
        return value;
    }

    function state(value) {
        return [value, update];
    }

    let [active, setActive] = state(false)


    const toogleHidden = (id) => {
        active = setActive(active, !active)
        console.log(`hidden-block${id}`)
        let div = document.getElementById(`hidden-block${id}`)
        let miniIcon = document.getElementById(`miniIcon${id}`)
        if (active) {
            div.classList.add("display-block");
            div.classList.remove("display-hidden");
            miniIcon.innerHTML = '<i class="bi bi-dash"></i>'
        } else {
            div.classList.add("display-hidden");
            div.classList.remove("display-block");
            miniIcon.innerHTML = '<i class="bi bi-plus"></i>'
        }
    }

    function goDelete(id) {
        if (confirm("Apakah Anda yakin akan menghapus ?")) {
            window.location = `<?= base_url() ?>panelbackend/spi_library/delete/${id}`;
        }
    }
</script>