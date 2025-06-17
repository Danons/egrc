
<div class="col-sm-12">
<?php 
$from = UI::createSelect('id_sasaran_parent',$risksasaranarr,$row['id_sasaran_parent'],$edited,$class='form-control select2',"style='width:auto; width:100%;' data-tags='true'");
echo UI::createFormGroup($from, $rules["id_sasaran_parent"], "id_sasaran_parent", "Sasaran Induk", false, 2);
?>


<?php
$from = UI::createTextBox('kode',$row['kode'],'20','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["kode"], "kode", "Kode", false, 2);
?>

<?php
$from = UI::createTextBox('nama',$row['nama'],'200','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Sasaran", false, 2);
?>

<?php 
$from = UI::createSelectMultiple('id_kpi[]',$riskkpiarr,$row['id_kpi'],$edited,$class='form-control select2',"style='width:auto; width:100%;' data-tags='true'");
echo UI::createFormGroup($from, $rules["id_kpi[]"], "id_kpi[]", "KPI Sasaran", false, 2);
?>

<?php /*
$from = UI::createTextArea('kpi',$row['kpi'],'','',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["kpi"], "kpi", "KPI Sasaran", false, 2); */
?>

<?php
$from = UI::createTextArea('kpi_deskripsi',$row['kpi_deskripsi'],'','',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["kpi_deskripsi"], "kpi_deskripsi", "Deskripsi KPI Sasaran", false, 2);
?>

<?php
$form = UI::createSelectMultiple('id_jabatan[]',$mtsdmjabatanarr,$row['id_jabatan'],$edited,$class='form-control select2',"style='width:100%'");
echo UI::createFormGroup($form, $rules["id_jabatan[]"], "id_jabatan[]", "PIC", false, 2);
?>

<?php 
$from = UI::createTextBox('tgl_mulai_efektif',($row['tgl_mulai_efektif']?$row['tgl_mulai_efektif']:date('Y-m-d')),'10','10',$edited,$class='form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tgl_mulai_efektif"], "tgl_mulai_efektif", "Tgl. Mulai Efektif", false, 2);
?>
				

<?php 
$from = UI::createTextBox('tgl_akhir_efektif',$row['tgl_akhir_efektif'],'10','10',$edited,$class='form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tgl_akhir_efektif"], "tgl_akhir_efektif", "Tgl. Akhir Efektif", false, 2);
?>


<?php
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, false, 2);
?>
</div>
