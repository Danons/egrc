<table class="table table-striped table-hover dataTable">
    <thead>
        <tr>
            <th style="text-align: left;">Bulan</th>
            <th style="text-align: right;">Realisasi</th>
            <th style="text-align: left;">Tgl Input</th>
            <th style="text-align: left;">Tgl Update</th>
            <th style="width:1px;"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list['rows'] as $r) { ?>
            <tr>
                <td><?= ListBulan()[$r['bulan']] ?></td>
                <?php
                if ($rowheader['satuan'] != 'Waktu') { ?>
                    <td style="text-align: right;"><?= rupiah($r['nilai']) ?></td>
                <?php } else { ?>
                    <td style="text-align: right;"><?= rupiah($r['prosentase']) ?></td>
                <?php } ?>
                <td style="text-align: left;"><?= $r['created_date'] ?></td>
                <td style="text-align: left;"><?= $r['modified_date'] ?></td>
                <td><?= UI::showMenuMode('inlist', $r[$pk]) ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>


<div class="col-sm-12">
    <?php
    // $from = UI::createTextArea('analisa', $rowheader['analisa'], '', '', $editedanalisa, $class = 'form-control contents-mini', "");
    $from = UI::createTextArea('analisa', $rowheader['analisa'], '', '', $editedanalisa, $class = 'form-control contents', "");
    echo UI::createFormGroup($from, $rules["analisa"], "analisa", "Analisa", true);

    echo UI::createUpload("files_analisa", $row['files_analisa'], $page_ctrl, $editedanalisa);

    if ($editedanalisa) {
    ?>
        <button type="button" class="btn btn-success btn-sm" onclick="goSubmit('simpan_analisa')">Simpan</button>
    <?php
    }
    ?>

    <?php
    // $from = UI::createTextArea('evaluasi', $rowheader['evaluasi'], '', '', $editedevaluasi, $class = 'form-control contents-mini', "");
    $from = UI::createTextArea('evaluasi', $rowheader['evaluasi'], '', '', $editedevaluasi, $class = 'form-control contents', "");
    echo UI::createFormGroup($from, $rules["evaluasi"], "evaluasi", "Evaluasi PKRSM", true);

    echo UI::createUpload("files_evaluasi", $row['files_evaluasi'], $page_ctrl, $editedanalisa);

    if ($editedevaluasi) {
    ?>
        <button type="button" class="btn btn-success btn-sm" onclick="goSubmit('simpan_evaluasi')">Simpan</button>
    <?php
    }
    ?>
</div>