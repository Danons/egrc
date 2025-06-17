<div class="col-sm-6">
<?php 
$from = UI::createTextBox('kode',$row['kode'],'5','5',$edited,'form-control ',"style='width:50px'");
echo UI::createFormGroup($from, $rules["kode"], "kode", "Kode");
?>

<?php 
$from = UI::createTextBox('nama',$row['nama'],'200','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>

<?php 
$from = UI::createTextArea('keterangan',$row['keterangan'],'','',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "Keterangan");
?>

<!-- <?php
$from = UI::createSelectMultiple('jenis[]', $jenisrunitnonnurinarr, $row['jenis'], $edited, 'form-control select2');
echo UI::createFormGroup($from, $rules["jenis"], "jenis", "Rutin / Non-Rutin / Proyek");
?>

</div>
    <br>
    <br>
    <br> -->
<div class="col-sm-6">

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>