
<div class="row">
<div class="col-sm-6">
<?php 
// $from = UI::createTextBox('kode_distrik',$row['kode_distrik'],'18','18',$edited,$class='form-control ',"style='width:180px'");
// echo UI::createFormGroup($from, $rules["kode_distrik"], "kode_distrik", "Kode Distrik");
?>

<?php 
$from = UI::createTextBox('table_code',$row['table_code'],'18','18',$edited,$class='form-control ',"style='width:180px'");
echo UI::createFormGroup($from, $rules["table_code"], "table_code", "Kode");
?>

<?php 
$from = UI::createTextBox('table_desc',$row['table_desc'],'100','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["table_desc"], "table_desc", "Nama");
?>

<?php 
$from = UI::createCheckBox('is_aktif',1,$row['is_aktif'],null,$edited,$class='iCheck-helper ',"style='margin:10px 0px'");
echo UI::createFormGroup($from, $rules["is_aktif"], "is_aktif", "Active");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>
</div>