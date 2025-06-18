<b>
    <small>Aspek</small>
</b>
<br />
<?= $rowheader1['kode'] ? $rowheader1['kode'] . " " : "" ?><?= $rowheader1['nama'] ?>
<br />
<br />
<b>
    <small>Indikator</small>
</b>
<br />
<?= $rowheader2['kode'] ? $rowheader2['kode'] . " " : "" ?><?= $rowheader2['nama'] ?>
<br />
<br />
<b>
    <small>Paramater</small>
</b>
<br />
<?= $rowheader3['kode'] ? $rowheader3['kode'] . " " : "" ?><?= $rowheader3['nama'] ?>
<br />
<br />
<b>
    <small>Faktor-faktor yang Diuji Kesesuaiannya (FUK)</small>
</b>
<br />
<?= $rowheader4['kode'] ? $rowheader4['kode'] . " " : "" ?><?= $rowheader4['nama'] ?>
<br />
<br />

<b>Tahun : <?= $tahun ?></b>
<hr />
<table class="table table-bordered">
    <thead>
        <tr>
            <th style="width:10px">Kode</th>
            <th style="width:400px">Unsur Pemenuhan (UP)</th>
            <th>Unit</th>
            <th style="width:10px"></th>
        </tr>
    </thead>
    <tbody>
        <input type="hidden" name="id_kriteria" value="<?= $this->post['id_kriteria'] ?>" />
        <?php foreach ($rows as $i => $r) { ?>
            <tr id="tr<?= $i ?>">
                <input type="hidden" name="detail[<?= $i ?>][id_kriteria]" value="<?= $r['id_kriteria'] ?>" />
                <td><input class="form-control" style="width: 100%;" type="text" name="detail[<?= $i ?>][kode]" value="<?= $r['kode'] ?>" /></td>
                <td>
                    <textarea rows="3" class="form-control" style="width: 100%;" name="detail[<?= $i ?>][nama]"><?= $r['nama'] ?></textarea>
                </td>
                <td>
                    <?= UI::createSelect("detail[$i][id_unit]", ["" => ""] + $mtunitarr, $r['id_unit'], true) ?>
                </td>
                <td>
                    <button type='button' class="btn btn-sm btn-danger" onclick="$('#tr<?= $i ?>').remove()"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<div style="text-align: right;"><button class="btn btn-sm btn-primary" type='button' onclick="goSubmitAjax('<?= site_url('panelbackend/mt_kriteria') ?>', 'add_attribute', '#iddetail')">Add</button></div>