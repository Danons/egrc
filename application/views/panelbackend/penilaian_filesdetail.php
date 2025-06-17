<div class="col-sm-6">

<?php 
$from = UI::createSelect('id_penilaian',$penilaianarr,$row['id_penilaian'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_penilaian"], "id_penilaian", "Penilaian");
?>

<?php 
$from = UI::createSelect('id_penilaian_detail',$penilaiandetailarr,$row['id_penilaian_detail'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_penilaian_detail"], "id_penilaian_detail", "Penilaian Detail");
?>

<?php 
$from = UI::createTextArea('file_name',$row['file_name'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["file_name"], "file_name", "File Name");
?>

<?php 
$from = UI::createTextBox('file_type',$row['file_type'],'200','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["file_type"], "file_type", "File Type");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::createTextBox('file_size',$row['file_size'],'10','10',$edited,'form-control rupiah ',"style='text-align:right; width:190px' min='0' max='10000000000' step='1'");
echo UI::createFormGroup($from, $rules["file_size"], "file_size", "File Size");
?>

<?php 
$from = UI::createTextArea('client_name',$row['client_name'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["client_name"], "client_name", "Client Name");
?>

<?php 
$from = UI::createTextBox('jenis_file',$row['jenis_file'],'100','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["jenis_file"], "jenis_file", "Jenis File");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>