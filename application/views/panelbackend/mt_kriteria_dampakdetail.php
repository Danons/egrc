<div class="col-sm-6">

<?php 
$from = UI::createTextBox('nama',$row['nama'],'200','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>

<?php 
$from = UI::createSelect('id_induk',$mtkriteriadampakarr,$row['id_induk'],$edited,$class='form-control ',"style='width:100%;'");
echo UI::createFormGroup($from, $rules["id_induk"], "id_induk", "Induk");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>