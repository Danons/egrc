
<div class="row">
<div class="col-sm-6">

<?php 
$from = UI::createSelect('id_kemungkinan',$mtkemungkinanrisikoarr,$row['id_kemungkinan'],!$row['id_kemungkinan']&&$edited,$class='form-control ',"style='width:100%;'");
echo UI::createFormGroup($from, $rules["id_kemungkinan"], "id_kemungkinan", "Kemungkinan");
?>

<?php 
$from = UI::createSelect('id_dampak',$mtdampakrisikoarr,$row['id_dampak'],!$row['id_dampak']&&$edited,$class='form-control ',"style='width:100%;'");
echo UI::createFormGroup($from, $rules["id_dampak"], "id_dampak", "Dampak");
?>

<?php 
$from = UI::createSelect('id_tingkat',$mttingkatarr,$row['id_tingkat'],$edited,$class='form-control ',"style='width:100%;'");
echo UI::createFormGroup($from, $rules["id_tingkat"], "id_tingkat", "Tingkat Dampak");
?>

<?php 
list($css1, $css2) = explode(";",$row['css']);
if($css2){
	$row['css'] = $css1.';';
	$row['css1'] = $css2.';';
}
$from = UI::createSelect('css',array(
	''=>'',
	'border-left:7px dotted #000 !important;'=>'Kiri',
	'border-right:7px dotted #000 !important;'=>'Kanan',
	'border-top:7px dotted #000 !important;'=>'Atas',
	'border-bottom:7px dotted #000 !important;'=>'Bawah',
),$row['css'],$edited,$class='form-control ',"style='width:100%;'");
echo UI::createFormGroup($from, $rules["css"], "css", "Border Matriks 1");
?>

<?php 
$from = UI::createSelect('css1',array(
	''=>'',
	'border-left:7px dotted #000 !important;'=>'Kiri',
	'border-right:7px dotted #000 !important;'=>'Kanan',
	'border-top:7px dotted #000 !important;'=>'Atas',
	'border-bottom:7px dotted #000 !important;'=>'Bawah',
),$row['css1'],$edited,$class='form-control ',"style='width:100%;'");

if($edited)
	$from .= "<small>Border matriks menunjukan tingkat risiko yang tidak bisa ditoleransi, hal ini mempengaruhi pada saat akan melakukan close risiko</small>";

echo UI::createFormGroup($from, $rules["css1"], "css1", "Border Matriks 2");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>
</div>