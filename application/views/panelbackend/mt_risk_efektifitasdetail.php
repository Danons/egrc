<div class="col-sm-6">

<?php 
$from = UI::createTextBox('nama',$row['nama'],'200','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>

<?php
$from = UI::createCheckBox('need_lampiran',1,$row['need_lampiran'],"Butuh Lampiran ?",$edited,$class='iCheck-helper ',"");
echo UI::createFormGroup($from, $rules["need_lampiran"], "need_lampiran");
?>

<?php
$from = UI::createCheckBox('need_explanation',1,$row['need_explanation'],"Butuh Penjelasan ?",$edited,$class='iCheck-helper ',"");
echo UI::createFormGroup($from, $rules["need_explanation"], "need_explanation");
?>

</div>
<div class="col-sm-6">
				

<?php
echo UI::createFormGroup("<b>BOBOT</b>");
unset($mtjawabanarr['']);
foreach ($mtjawabanarr as $idkey => $value) {
	//createTextArea($nameid,$value='',$rows='',$cols='',$edit=true,$class='form-control',$add='')
	$from = UI::createTextNumber("bobot[$idkey]",$row['bobot'][$idkey],'','',$edited,$class='form-control ',"style='width:100%' min='1'");
	echo UI::createFormGroup($from, $rules["bobot[$idkey]"], "bobot[$idkey]", $value);
}
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>