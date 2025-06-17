<div class="modal fade" id="varmodal" tabindex="-1" role="dialog">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Perhitungan Nilai VAR</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="tablerisiko" class="table table-hovered">
                    <thead>
                        <tr>
                            <th style="width: 90px;">Tahun</th>
                            <th>Target</th>
                            <th>Realisasi</th>
                            <th>Deviasi</th>
                            <?php if ($edited) { ?>
                                <th></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody class="listadd">
                        <?php
                        if ($row['var'])
                            foreach ($row['var'] as $i => $r) { ?>
                            <tr class="listadd<?= $i ?>">
                                <td><?= UI::createTextNumber("var[$i][tahun]", $r['tahun'], '', '', $edited) ?></td>
                                <td><?= UI::createTextBox("var[$i][target]", ($edited ? $r['target'] : rupiah($r['target'])), '', '', $edited, 'form-control rupiah target', "onchange='hitung(\"listadd<?=$i?>\")'") ?></td>
                                <td><?= UI::createTextBox("var[$i][realisasi]", ($edited ? $r['realisasi'] : rupiah($r['realisasi'])), '', '', $edited, 'form-control rupiah realisasi', "onchange='hitung(\"listadd<?=$i?>\")'") ?></td>
                                <td><?= UI::createTextBox("var[$i][devisiasi]", ($edited ? ($r['realisasi'] - $r['target']) : rupiah(($r['realisasi'] - $r['target']))), '', '', $edited, 'form-control rupiah devisiasi', "readonly") ?></td>
                                <?php if ($edited) { ?>
                                    <td><button type="button" class="btn btn-danger btn-sm" onclick="$('.listadd<?= $i ?>').remove()">x</button></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php if ($edited) { ?>
                    <div style="text-align: right;"><button type="button" class="btn btn-sm" onclick="add()">Add</button></div>
                    <script>
                        var ipk = <?= (int)$i ?>;

                        function hitung(cls) {
                            var target = $("." + cls + " .target").valrupiah();
                            var realisasi = $("." + cls + " .realisasi").valrupiah();
                            $("." + cls + " .devisiasi").valrupiah(parseFloat(realisasi) - parseFloat(target));
                        }

                        function add() {
                            ipk++;
                            $(".listadd").append(
                                "<tr class='listadd" + ipk + "'>" +
                                "<td><input type='number' name='var[" + ipk + "][tahun]' class='form-control'></td>" +
                                "<td><input type='text' name='var[" + ipk + "][target]' class='form-control rupiah target' onchange='hitung(\"listadd" + ipk + "\")'></td>" +
                                "<td><input type='text' name='var[" + ipk + "][realisasi]' class='form-control rupiah realisasi' onchange='hitung(\"listadd" + ipk + "\")'></td>" +
                                "<td><input type='text' name='var[" + ipk + "][devisiasi]' class='form-control rupiah devisiasi' readonly></td>" +
                                "<td><button type='button' class='btn btn-danger' onclick='$(\".listadd" + ipk + "\").remove()'>x</button></td>" +
                                "</tr>"
                            );
                            rupiah();
                        }
                    </script>
                <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="goSubmit('set_value')">SET</button>
            </div>
        </div>
    </div>
</div>