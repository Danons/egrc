<div class="col-sm-6">

<?php 
$from = UI::createTextBox('nama',$row['nama'],'300','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>

<?php 
$from = UI::createTextArea('keterangan',$row['keterangan'],'','',$edited,$class='form-control',"");
echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "Keterangan");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>