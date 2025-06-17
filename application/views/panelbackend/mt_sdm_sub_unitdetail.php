
<div class="row">
<div class="col-sm-6">

<?php 
$from = UI::createSelect('id_unit',$mtsdmunitarr,$row['id_unit'],$edited,'form-control ',"style='width:100%;'");
echo UI::createFormGroup($from, $rules["id_unit"], "id_unit", "Unit");
?>

<?php 
$from = UI::createTextBox('nama',$row['nama'],'200','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>
</div>