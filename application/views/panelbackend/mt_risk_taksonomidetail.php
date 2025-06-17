<div class="col-sm-6">

<?php 
$from = UI::createTextBox('kode',$row['kode'],'20','20',$edited,$class='form-control ',"style='width:200px'");
echo UI::createFormGroup($from, $rules["kode"], "kode", "Kode");
?>

<?php 
$from = UI::createTextBox('nama',$row['nama'],'200','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>


<?php 
$from = UI::createTextBox('penjelasan',$row['penjelasan'],'','',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["penjelasan"], "penjelasan", "Penjelasan");
?>

<?php
$from = UI::createCheckBox('is_regulasi',1,$row['is_regulasi'],"Regulasi ?",$edited);
echo UI::createFormGroup($from, $rules["is_regulasi"], "is_regulasi", "");
?>

<?php
if($row['is_aktif']===null)
	$row['is_aktif'] = 1;

$from = UI::createCheckBox('is_aktif',1,$row['is_aktif'],"Aktif ?",$edited);
echo UI::createFormGroup($from, $rules["is_aktif"], "is_aktif", "");
?>
</div>
<div class="col-sm-6">
				

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>