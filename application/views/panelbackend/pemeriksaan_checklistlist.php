<table class="table table-hover dataTable treetable">
    <thead>
        <tr>
            <th>Jenis pekerjaan yang harus dilakukan</th>
            <th>Sudah/Belum</th>
            <th>%penyelesaian</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1;
        $indukarr = [];
        foreach ($list['rows'] as $r) {
            $indukarr[$r['id_checklist_parent']] = 1;
        }
        foreach ($list['rows'] as $r) { ?>
            <tr data-tt-id='<?= $r['id_checklist'] ?>' data-tt-parent-id='<?= $r['id_checklist_parent'] ?>'>
                <td><?= $r['nama'] ?></td>
                <?php if ($indukarr[$r['id_checklist']]) { ?>
                    <td></td>
                    <td></td>
                <?php } else { ?>
                    <td><?= UI::createCheckBox("is_oke[$r[id_checklist]]", 1, $row['is_oke'][$r['id_checklist']], "Sudah", $this->access_role['edit']) ?></td>
                    <td><?= UI::createTextNumber("penyelesaian[$r[id_checklist]]", $row['penyelesaian'][$r['id_checklist']], '', '', $this->access_role['edit']) ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </tbody>
</table>
<div style="text-align: right;">
    <a href="<?= base_url('panelbackend/pemeriksaan_checklist/go_print/' . $jenis_checklist . '/' . $id_pemeriksaan) ?>" class="btn btn-primary">Print</a>
    <button type="button" class="btn btn-primary" onclick="goSubmit('save')">
        Simpan
    </button>
</div>