<div class="col-sm-6">
    <?php
    // $from = UI::createSelect('id_kri', $riskkriarr, $row['id_kri'], $edited, $class = 'form-control ', "style='width:auto; max-width:100%;'");
    // echo UI::createFormGroup($from, $rules["id_kri"], "id_kri", "KRI");
    ?>


    <?php /*
$from = UI::createTextNumber('id_periode_tw',$row['id_periode_tw'],'10','10',$edited,$class='form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
echo UI::createFormGroup($from, $rules["id_periode_tw"], "id_periode_tw", "Periode TW");
?>

<?php 
$from = UI::createTextBox('create_date',$row['create_date'],'20','20',$edited,$class='form-control datetimepicker',"style='width:100%'");
echo UI::createFormGroup($from, $rules["create_date"], "create_date", "Create Date");
?>

<?php 
$from = UI::createTextNumber('target_mulai',$row['target_mulai'],'10','10',$edited,$class='form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
echo UI::createFormGroup($from, $rules["target_mulai"], "target_mulai", "Target Mulai");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::createTextNumber('target_sampai',$row['target_sampai'],'10','10',$edited,$class='form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
echo UI::createFormGroup($from, $rules["target_sampai"], "target_sampai", "Target Sampai");
?>

<?php 
$from = UI::createTextNumber('batas_atas',$row['batas_atas'],'10','10',$edited,$class='form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
echo UI::createFormGroup($from, $rules["batas_atas"], "batas_atas", "Batas Atas");
?>

<?php 
$from = UI::createTextNumber('batas_bawah',$row['batas_bawah'],'10','10',$edited,$class='form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
echo UI::createFormGroup($from, $rules["batas_bawah"], "batas_bawah", "Batas Bawah");
?>

<?php 
$from = UI::createTextNumber('tahun',$row['tahun'],'10','10',$edited,$class='form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
echo UI::createFormGroup($from, $rules["tahun"], "tahun", "Tahun");*/
    ?>

    <?php
    $from = UI::createSelect('bulan', ListBulan(), $row['bulan'], $edited, $class = 'form-control ', "style='width:auto; max-width:100%;'");
    echo UI::createFormGroup($from, $rules["bulan"], "bulan", "Bulan");
    ?>

    <?php
    $from = UI::createTextNumber('nilai', $row['nilai'], '10', '10', $edited, $class = 'form-control ', "style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
    echo UI::createFormGroup($from, $rules["nilai"], "nilai", "Nilai");
    ?>

    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from);
    ?>
</div>