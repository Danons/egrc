<div class="col-sm-6">

<?php 
$from = UI::createTextNumber('id_checklist',$row['id_checklist'],'10','10',$edited,$class='form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
echo UI::createFormGroup($from, $rules["id_checklist"], "id_checklist", "Checklist");
?>

<?php 
$from = UI::createCheckBox('is_oke',1,$row['is_oke'], "OKE",$edited,$class='iCheck-helper ',"");
echo UI::createFormGroup($from, $rules["is_oke"], "is_oke");
?>

<?php 
$from = UI::createTextNumber('penyelesaian',$row['penyelesaian'],'10','10',$edited,$class='form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
echo UI::createFormGroup($from, $rules["penyelesaian"], "penyelesaian", "Penyelesaian");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::createTextBox('jenis',$row['jenis'],'50','50',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["jenis"], "jenis", "Jenis");
?>

<?php 
$from = UI::createTextArea('keterangan',$row['keterangan'],'','',$edited,$class='form-control contents',"");
echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "Keterangan");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>