<div class="col-sm-6">

<?php 
$from = UI::createSelect('id_jenis_rtm_parent',$mtjenisrtmarr,$row['id_jenis_rtm_parent'],$edited,$class='form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_jenis_rtm_parent"], "id_jenis_rtm_parent", "Jenis RTM Parent");
?>

<?php 
$from = UI::createTextBox('jenis_masalah',$row['jenis_masalah'],'200','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["jenis_masalah"], "jenis_masalah", "Jenis Masalah");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>