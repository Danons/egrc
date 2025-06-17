
<div class="row">
<div class="col-sm-6">

<?php
$from = UI::createSelect('id_dampak',$mtkriteriadampakarr,$row['id_dampak'],($row['id_dampak']?false:$edited),$class='form-control ',"style='width:100%;'");
echo UI::createFormGroup($from, $rules["id_dampak"], "id_dampak", "Nama Kriteria Dampak Risiko", false, 2);
?>

<?php
$from = UI::createTextArea('keterangan',$row['keterangan'],'','',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "Keterangan", false, 2);
?>

</div>
<div class="col-sm-6">


<?php
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>
</div>
