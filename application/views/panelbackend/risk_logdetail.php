
<div class="row">
<div class="col-sm-6">

<?php 
$from = UI::createSelect('id_scorecard',$riskscorecardarr,$row['id_scorecard'],$edited,$class='form-control ',"style='width:100%;'");
echo UI::createFormGroup($from, $rules["id_scorecard"], "id_scorecard", "Kajian Risiko");
?>

<?php 
$from = UI::createSelect('id_risiko',$riskrisikoarr,$row['id_risiko'],$edited,$class='form-control ',"style='width:100%;'");
echo UI::createFormGroup($from, $rules["id_risiko"], "id_risiko", "Risiko");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::createTextArea('deskripsi',$row['deskripsi'],'','',$edited,$class='form-control',"");
echo UI::createFormGroup($from, $rules["deskripsi"], "deskripsi", "Deskripsi");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>
</div>