<div class="col-sm-6">

<?php 
$from = UI::createTextBox('name',$row['name'],'100','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["name"], "name", "Name");
?>

<?php 
// $from = UI::createTextBox('visible',$row['visible'],'1','1',$edited,$class='form-control ',"style='width:10px'");
// echo UI::createFormGroup($from, $rules["visible"], "visible", "Visible");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>