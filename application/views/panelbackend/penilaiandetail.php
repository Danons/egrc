<div class="col-sm-6">

<?php 
$from = UI::createTextBox('tgl',$row['tgl'],'10','10',$edited,'form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tgl"], "tgl", "Tgl.");
?>

<?php 
$from = UI::createSelect('id_kriteria',$mtkriteriaarr,$row['id_kriteria'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_kriteria"], "id_kriteria", "Kriteria");
?>

<?php 
$from = UI::createTextBox('status',$row['status'],'1','1',$edited,'form-control rupiah ',"style='text-align:right; width:19px' min='0' max='10' step='1'");
echo UI::createFormGroup($from, $rules["status"], "status", "Status");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::createSelect('id_periode',$mtperiodearr,$row['id_periode'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_periode"], "id_periode", "Periode");
?>

<?php 
$from = UI::createSelect('id_unit',$mtunitarr,$row['id_unit'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_unit"], "id_unit", "Unit");
?>

<?php 
$from = UI::createTextBox('nama_periode',$row['nama_periode'],'50','50',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama_periode"], "nama_periode", "Nama Periode");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>