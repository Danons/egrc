<div class="col-sm-6">

<?php 
if($row['id_kategori_parent'] or $this->post['id_kategori_parent']){
	if(!$row['id_kategori_parent'])
		$row['id_kategori_parent'] = $this->post['id_kategori_parent'];
	
$from = UI::createSelect('id_kategori_parent',$mtkategoriarr,$row['id_kategori_parent'],false,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_kategori_parent"], "id_kategori_parent", "Kategori Parent");
}
?>

<?php 
$from = UI::createTextBox('nama',$row['nama'],'200','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>
<?php 
if(!$row['id_kategori'])
	$row['is_aktif'] = 1;

$from = UI::createCheckBox('is_aktif',1,$row['is_aktif'], "Aktif",$edited,'iCheck-helper ',"");
echo UI::createFormGroup($from, $rules["is_aktif"], "is_aktif");
?>


</div>
<div class="col-sm-6">
				

<?php 
if($row['id_kategori_parent']){
	$from = UI::createSelect('id_kategori_jenis',$mtkategorijenisarr,$row['id_kategori_jenis'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
	echo UI::createFormGroup($from, $rules["id_kategori_jenis"], "id_kategori_jenis", "Kategori Jenis");

	$from = UI::createSelect('periode_penilaian',$mtperiodearr,$row['periode_penilaian'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
	echo UI::createFormGroup($from, $rules["periode_penilaian"], "periode_penilaian", "Periode Penilaian");
}
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>