<div class="col-sm-6">
<?php
$from = UI::createSelect('id_kategori',$mtkategoriaarr,$this->post['id_kategori'],true,'form-control ',"style='width:auto; max-width:100%;display:inline;' onchange='goSubmit(\"set_value\");'");
echo UI::createFormGroup($from, $rules["id_kategori"], "Kategori", "Kategori");
?>
<?php 
$from = UI::createSelect('id_kriteria1',$mtkriteriaarr,$row['id_kriteria1'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_kriteria1"], "id_kriteria1", "Kriteria1");
?>


</div>
<div class="col-sm-6">
<?php
$from = UI::createSelect('id_kategori2',$mtkategoriaarr2,$this->post['id_kategori2'],true,'form-control ',"style='width:auto; max-width:100%;display:inline;' onchange='goSubmit(\"set_value\");'");
echo UI::createFormGroup($from, $rules["id_kategori2"], "Kategori", "Kategori");
?>
<?php 
$from = UI::createSelect('id_kriteria2',$mtkriteriaarr2,$row['id_kriteria2'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_kriteria2"], "id_kriteria2", "Kriteria2");
?>				

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>