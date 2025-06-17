<div class="col-sm-6">

<?php 
$from = UI::createTextBox('skor_bawah',$row['skor_bawah'],'10','10',$edited,'form-control rupiah ',"style='text-align:right; width:100%' min='0' max='10000000000' step='1'");
echo UI::createFormGroup($from, $rules["skor_bawah"], "skor_bawah", "Skor Bawah");
?>

<?php 
$from = UI::createTextBox('skor_atas',$row['skor_atas'],'10','10',$edited,'form-control rupiah ',"style='text-align:right; width:100%' min='0' max='10000000000' step='1'");
echo UI::createFormGroup($from, $rules["skor_atas"], "skor_atas", "Skor Atas");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::createTextBox('efektifitas_mitigasi',$row['efektifitas_mitigasi'],'100','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["efektifitas_mitigasi"], "efektifitas_mitigasi", "Efektifitas Mitigasi");
?>

<?php 
$from = UI::createTextBox('diskripsi_kriteria',$row['diskripsi_kriteria'],'500','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["diskripsi_kriteria"], "diskripsi_kriteria", "Diskripsi Kriteria");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>