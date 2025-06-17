<div class="col-sm-6">

<?php 
$from = UI::createTextNumber('id_pemeriksaan',$row['id_pemeriksaan'],'10','10',$edited,$class='form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
echo UI::createFormGroup($from, $rules["id_pemeriksaan"], "id_pemeriksaan", "Pemeriksaan");
?>

<?php 
$from = UI::createTextBox('jenis_dokumen_yang_dipinjam',$row['jenis_dokumen_yang_dipinjam'],'100','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["jenis_dokumen_yang_dipinjam"], "jenis_dokumen_yang_dipinjam", "Jenis Dokumen yang Dipinjam");
?>

<?php 
$from = UI::createTextBox('nomor_berkas',$row['nomor_berkas'],'100','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nomor_berkas"], "nomor_berkas", "Nomor Berkas");
?>

<?php 
$from = UI::createTextBox('nama_peminjam',$row['nama_peminjam'],'200','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama_peminjam"], "nama_peminjam", "Nama Peminjam");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::createTextArea('maksud_dan_keperluan',$row['maksud_dan_keperluan'],'','',$edited,$class='form-control ',"");
echo UI::createFormGroup($from, $rules["maksud_dan_keperluan"], "maksud_dan_keperluan", "Maksud DAN Keperluan");
?>

<?php 
$from = UI::createTextBox('tgl_peminjaman',$row['tgl_peminjaman'],'10','10',$edited,$class='form-control datepicker',"style='width:190px'");
echo UI::createFormGroup($from, $rules["tgl_peminjaman"], "tgl_peminjaman", "Tgl. Peminjaman");
?>

<?php 
$from = UI::createTextBox('tgl_pengembalian',$row['tgl_pengembalian'],'10','10',$edited,$class='form-control datepicker',"style='width:190px'");
echo UI::createFormGroup($from, $rules["tgl_pengembalian"], "tgl_pengembalian", "Tgl. Pengembalian");
?>

<?php 
$from = UI::createTextArea('keterangan',$row['keterangan'],'','',$edited,$class='form-control contents',"");
echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "Keterangan");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>