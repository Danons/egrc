<div class="col-sm-6">

<?php 
$from = UI::createSelect('id_penilaian',$penilaianarr,$row['id_penilaian'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_penilaian"], "id_penilaian", "Penilaian");
?>

<?php 
$from = UI::createTextArea('komentar',$row['komentar'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["komentar"], "komentar", "Komentar");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::createTextBox('nama',$row['nama'],'20','20',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>