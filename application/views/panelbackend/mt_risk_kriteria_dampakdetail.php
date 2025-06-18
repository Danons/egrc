
<div class="row">
<div class="col-sm-12">

<?php 
$from = UI::createSelect('id_induk',$mtkriteriadampakarr,(int)$row['id_induk'],$edited,$class='form-control ',"style='width:100%;'");
echo UI::createFormGroup($from, $rules["id_induk"], "id_induk", "Induk", false, 2);
?>

<?php 
// $from = UI::createTextBox('kode',$row['kode'],'200','100',$edited,$class='form-control ',"style='width:100%'");
// echo UI::createFormGroup($from, $rules["kode"], "kode", "Kode", false, 2);
?>

<?php 
$from = UI::createTextBox('nama',$row['nama'],'200','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Uraian", false, 2);
?>

<?php
unset($mtdampakrisikoarr['']);
foreach ($mtdampakrisikoarr as $idkey => $value) {
	//createTextArea($nameid,$value='',$rows='',$cols='',$edit=true,$class='form-control',$add='')
	$from = UI::createTextArea("keterangan[$idkey]",$row['keterangan'][$idkey],'','',$edited,$class='form-control ',"style='width:100%'");
	echo UI::createFormGroup($from, $rules["keterangan[$idkey]"], "keterangan[$idkey]", $value, false, 2);
}
?>

<?php
// $from = UI::createSelectMultiple('rutin_non_rutin[]', $runitnonnurinarr, $row['rutin_non_rutin'], $edited, 'form-control select2');
// echo UI::createFormGroup($from, $rules["rutin_non_rutin"], "rutin_non_rutin", "Rutin / Non-Rutin", false, 2);
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, false, 2);
?>
</div>
</div>