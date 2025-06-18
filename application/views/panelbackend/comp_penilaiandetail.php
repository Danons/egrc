<div class="col-sm-6">

<?php 
$from = UI::createSelect('id_comp_kebutuhan',$compkebutuhanarr,$row['id_comp_kebutuhan'],$edited,$class='form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_comp_kebutuhan"], "id_comp_kebutuhan", "Comp Kebutuhan");
?>

<?php 
$from = UI::createTextBox('periode_label',$row['periode_label'],'45','45',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["periode_label"], "periode_label", "Periode Label");
?>

<?php 
$from = UI::createSelect('id_status_penilaian',$mtstatuspenilaianarr,$row['id_status_penilaian'],$edited,$class='form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_status_penilaian"], "id_status_penilaian", "Status Penilaian");
?>

<?php 
$from = UI::createTextArea('keterangan',$row['keterangan'],'','',$edited,$class='form-control contents',"");
echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "Keterangan");
?>

<?php 
$from = UI::createSelect('id_jabatan_pereview',$mtsdmjabatanarr,$row['id_jabatan_pereview'],$edited,$class='form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_jabatan_pereview"], "id_jabatan_pereview", "Jabatan Pereview");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::createTextBox('nama_jabatan_pereview',$row['nama_jabatan_pereview'],'200','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama_jabatan_pereview"], "nama_jabatan_pereview", "Nama Jabatan Pereview");
?>

<?php 
$from = UI::createTextNumber('id_pereview',$row['id_pereview'],'10','10',$edited,$class='form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
echo UI::createFormGroup($from, $rules["id_pereview"], "id_pereview", "Pereview");
?>

<?php 
$from = UI::createTextBox('nama_pereview',$row['nama_pereview'],'200','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama_pereview"], "nama_pereview", "Ketua Tim");
?>

<?php 
$from = UI::createTextNumber('id_unit',$row['id_unit'],'10','10',$edited,$class='form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
echo UI::createFormGroup($from, $rules["id_unit"], "id_unit", "Unit");
?>

<?php 
$from = UI::createTextBox('tahun',$row['tahun'],'4','4',$edited,$class='form-control ',"style='width:76px'");
echo UI::createFormGroup($from, $rules["tahun"], "tahun", "Tahun");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>