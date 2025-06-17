<div class="col-sm-6">

<?php 
$from = UI::createTextNumber('level',$row['level'],'10','10',$edited,$class='form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
echo UI::createFormGroup($from, $rules["level"], "level", "Level");
?>

<?php 
$from = UI::createTextBox('nama',$row['nama'],'100','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::createCheckBox('is_aktif',1,$row['is_aktif'], "Aktif",$edited,$class='iCheck-helper ',"");
echo UI::createFormGroup($from, $rules["is_aktif"], "is_aktif");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>