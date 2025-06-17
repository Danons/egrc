<div class="col-sm-6">

<?php 
$from = UI::createCheckBox('is_ppd',1,$row['is_ppd'], "PPD",$edited,$class='iCheck-helper ',"");
echo UI::createFormGroup($from, $rules["is_ppd"], "is_ppd");
?>

<?php 
$from = UI::createTextBox('nama',$row['nama'],'45','45',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>