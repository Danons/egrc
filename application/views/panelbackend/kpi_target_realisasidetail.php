<div class="col-sm-6">

    <?php
    $from = UI::createSelect('bulan', ListBulan(), $row['bulan'], $edited, 'form-control ');
    echo UI::createFormGroup($from, $rules["bulan"], "bulan", "Bulan");
    ?>

</div>
<div class="col-sm-6">


    <?php
    if ($rowheader['satuan'] != 'Waktu') {
        $from = UI::createTextNumber('nilai', $row['nilai'], '10', '10', $edited, 'form-control ', "onchange='changeNilai()' style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
        echo UI::createFormGroup($from, $rules["nilai"], "nilai", "Nilai");
    } else {
        $from = UI::createSelect('nilai', ["" => ""] + ListBulan(), str_pad($row['nilai'],2,"0",STR_PAD_LEFT ), $edited, 'form-control ', "onchange='changeNilai()'");
        echo UI::createFormGroup($from, $rules["nilai"], "nilai", "Nilai");
    }
    ?>

    <?php
    $from = UI::createTextNumber('prosentase', $row['prosentase'], '10', '10', $edited, 'form-control ', "onchange='changeProsentase()' style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
    echo UI::createFormGroup($from, $rules["prosentase"], "prosentase", "Prosentase");
    ?>

    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from);
    ?>
</div>

<script>
    function changeNilai() {
        $("#prosentase").val("");
    }

    function changeProsentase() {
        // var prosentase = $("#prosentase").val();
        // var target = <?= $rowheader['target'] ?>;
        // var nilai = prosentase / 100 * target;
        // $("#nilai").val(nilai);
        $("#nilai").val("");
        $("#nilai").select2();
    }
</script>