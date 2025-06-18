<div class="col-sm-6">

<?php 
$from = UI::createTextBox('target',$row['target'],'250','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["target"], "target", "Target");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>