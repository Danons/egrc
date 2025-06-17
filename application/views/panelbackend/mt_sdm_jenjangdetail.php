
<div class="row">
<div class="col-sm-6">

<?php 
$from = UI::createTextBox('code',$row['code'],'20','20',$edited,$class='form-control ',"style='width:200px'");
echo UI::createFormGroup($from, $rules["code"], "code", "Code");
?>

<?php 
$from = UI::createTextBox('nama',$row['nama'],'200','100',$edited,$class='form-control ',"style='width:100%'");
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