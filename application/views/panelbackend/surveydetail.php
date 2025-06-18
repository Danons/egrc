<div class="row">
<div class="col-sm-6">

<?php
if ($this->access_role['view_all_unit']) {
    $from = UI::createSelect('id_unit', $mtsdmunitarr, $row['id_unit'], $edited, 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["id_unit"], "id_unit", "Distrik");
}
?>

<?php 
$from = UI::createTextBox('area',$row['area'],'100','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["area"], "area", "Area");
?>

<?php 
$from = UI::createTextBox('nomor_rekomendasi',$row['nomor_rekomendasi'],'200','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nomor_rekomendasi"], "nomor_rekomendasi", "Nomor Rekomendasi");
?>

<?php 
$from = UI::createTextBox('tgl',$row['tgl'],'10','10',$edited,'form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tgl"], "tgl", "Tgl.");
?>

<?php 
$from = UI::createTextBox('surveyor',$row['surveyor'],'200','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["surveyor"], "surveyor", "Surveyor");
?>

<?php 
$from = UI::createSelect('id_jenis_survey',$mtjenissurveyarr,$row['id_jenis_survey'],$edited,'form-control ',"style='width:100%;'");
echo UI::createFormGroup($from, $rules["id_jenis_survey"], "id_jenis_survey", "Jenis Survey");
?>

<?php 
$from = UI::createSelect('id_pelaksana_survey',$mtpelaksanasurveyarr,$row['id_pelaksana_survey'],$edited,'form-control ',"style='width:100%;'");
echo UI::createFormGroup($from, $rules["id_pelaksana_survey"], "id_pelaksana_survey", "Pelaksana Survey");
?>

<?php 
$from = UI::createTextArea('kondisi_temuan',$row['kondisi_temuan'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["kondisi_temuan"], "kondisi_temuan", "Kondisi Temuan");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::createTextArea('uraian_rekomendasi',$row['uraian_rekomendasi'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["uraian_rekomendasi"], "uraian_rekomendasi", "Uraian Rekomendasi");
?>

<?php 
$from = UI::createTextArea('tanggapan_manajemen',$row['tanggapan_manajemen'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["tanggapan_manajemen"], "tanggapan_manajemen", "Tanggapan Manajemen");
?>

<?php 
$from = UI::createTextArea('rencana_tindak_lanjut',$row['rencana_tindak_lanjut'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["rencana_tindak_lanjut"], "rencana_tindak_lanjut", "Rencana Tindak Lanjut");
?>

<?php 
$from = UI::createTextArea('realisasi_tindak_lanjut',$row['realisasi_tindak_lanjut'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["realisasi_tindak_lanjut"], "realisasi_tindak_lanjut", "Realisasi Tindak Lanjut");
?>

<?php 
$from = UI::createCheckBox('is_selesai',1,$row['is_selesai'], "Selesai",$edited,'iCheck-helper ',"onclick='goSubmit(\"set_value\")'");
if($row['is_selesai']){
    $from .= UI::createUploadMultiple("files", $row['files'], $page_ctrl, $edited);
}
echo UI::createFormGroup($from, $rules["is_selesai"], "is_selesai");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>
</div>