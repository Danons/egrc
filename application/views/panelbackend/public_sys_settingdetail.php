
<div class="row">
<div class="col-sm-6">

<?php 
$from = UI::createTextBox('nama',$row['nama'],'100','100',($edited && !$row[$pk]),$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>

<?php 
$from = UI::createTextArea('keterangan',$row['keterangan'],'','',$edited,$class='form-control',"");
echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "Keterangan");
?>

<?php 
$from = UI::createCheckBox('is_show',1,$row['is_show'],"Aktif",$edited,$class='iCheck-helper ',"");
echo UI::createFormGroup($from, $rules["is_show"], "is_show", null);
?>

</div>
<div class="col-sm-6">

<?php 
$from = UI::createTextArea('isi',$row['isi'],'','',$edited,$class='form-control',"");
echo UI::createFormGroup($from, $rules["isi"], "isi", "ISI");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>
</div>