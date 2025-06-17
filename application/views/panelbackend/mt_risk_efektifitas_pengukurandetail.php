
<div class="row">
<div class="col-sm-6">

<?php 
$from = UI::createTextNumber('skor_bawah',$row['skor_bawah'],'10','10',$edited,$class='form-control ',"style='text-align:right; width:100%' min='0' max='10000000000' step='1'");
echo UI::createFormGroup($from, $rules["skor_bawah"], "skor_bawah", "Skor Bawah");
?>

<?php 
$from = UI::createTextNumber('skor_atas',$row['skor_atas'],'10','10',$edited,$class='form-control ',"style='text-align:right; width:100%' min='0' max='10000000000' step='1'");
echo UI::createFormGroup($from, $rules["skor_atas"], "skor_atas", "Skor Atas");
?>

<?php 
$from = UI::createTextBox('efektifitas',$row['efektifitas'],'100','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["efektifitas"], "efektifitas", "Efektifitas");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::createTextBox('diskripsi_kriteria',$row['diskripsi_kriteria'],'500','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["diskripsi_kriteria"], "diskripsi_kriteria", "Diskripsi Kriteria");
?>

<?php 
$from = UI::createTextNumber('faktor_terhadap_risiko',$row['faktor_terhadap_risiko'],'10','10',$edited,$class='form-control ',"style='text-align:right; width:100%' min='0' max='10000000000' step='1'");
echo UI::createFormGroup($from, $rules["faktor_terhadap_risiko"], "faktor_terhadap_risiko", "Faktor Terhadap Risiko");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>
</div>