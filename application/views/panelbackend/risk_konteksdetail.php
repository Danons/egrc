<div class="col-sm-6">

<?php 
$from = UI::createTextArea('nama',$row['nama'],'','',$edited,$class='form-control ',"");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>

<?php 
$from = UI::createTextArea('deskripsi',$row['deskripsi'],'','',$edited,$class='form-control ',"");
echo UI::createFormGroup($from, $rules["deskripsi"], "deskripsi", "Deskripsi");
?>

<?php 
$from = UI::createTextArea('strength',$row['strength'],'','',$edited,$class='form-control ',"");
echo UI::createFormGroup($from, $rules["strength"], "strength", "Strength");
?>

<?php 
$from = UI::createTextArea('weakness',$row['weakness'],'','',$edited,$class='form-control ',"");
echo UI::createFormGroup($from, $rules["weakness"], "weakness", "Weakness");
?>

<?php 
$from = UI::createTextArea('opportunity',$row['opportunity'],'','',$edited,$class='form-control contents',"");
echo UI::createFormGroup($from, $rules["opportunity"], "opportunity", "Opportunity");
?>

<?php 
$from = UI::createTextArea('threat',$row['threat'],'','',$edited,$class='form-control contents',"");
echo UI::createFormGroup($from, $rules["threat"], "threat", "Threat");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::createTextArea('konteks_internal',$row['konteks_internal'],'','',$edited,$class='form-control contents',"");
echo UI::createFormGroup($from, $rules["konteks_internal"], "konteks_internal", "Konteks Internal");
?>

<?php 
$from = UI::createTextArea('konteks_eksternal',$row['konteks_eksternal'],'','',$edited,$class='form-control contents',"");
echo UI::createFormGroup($from, $rules["konteks_eksternal"], "konteks_eksternal", "Konteks Eksternal");
?>

<?php 
$from = UI::createTextBox('tgl_mulai_efektif',$row['tgl_mulai_efektif'],'10','10',$edited,$class='form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tgl_mulai_efektif"], "tgl_mulai_efektif", "Tgl. Mulai Efektif");
?>

<?php 
$from = UI::createTextBox('tgl_akhir_efektif',$row['tgl_akhir_efektif'],'20','20',$edited,$class='form-control datetimepicker',"style='width:200px'");
echo UI::createFormGroup($from, $rules["tgl_akhir_efektif"], "tgl_akhir_efektif", "Tgl. Akhir Efektif");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>