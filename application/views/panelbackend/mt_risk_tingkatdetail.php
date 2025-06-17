<div class="row">
    <div class="col-sm-6">

        <?php
        $from = UI::createTextBox('nama', $row['nama'], '20', '20', $edited, $class = 'form-control ', "style='width:200px'");
        echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
        ?>

        <?php
        $row['warna'] = str_replace("#", "", $row['warna']);
        $from = UI::createTextBox('warna', $row['warna'], '20', '20', $edited, $class = 'form-control jscolor', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["warna"], "warna", "Warna");
        ?>


        <?php
        $from = UI::createTextArea('penanganan', $row['penanganan'], '', '', $edited, $class = 'form-control', "");
        echo UI::createFormGroup($from, $rules["penanganan"], "penanganan", "Keterangan");
        ?>



    </div>
    <div class="col-sm-6">

        <?php /*
$from = UI::createTextBox('nama_peluang',$row['nama_peluang'],'20','20',$edited,$class='form-control ',"style='width:200px'");
echo UI::createFormGroup($from, $rules["nama_peluang"], "nama_peluang", "Nama Peluang");*/
        ?>

        <?php
        /*
$row['warna_peluang'] = str_replace("#", "", $row['warna_peluang']);
$from = UI::createTextBox('warna_peluang',$row['warna_peluang'],'20','20',$edited,$class='form-control jscolor',"style='width:100%'");
echo UI::createFormGroup($from, $rules["warna_peluang"], "warna_peluang", "Warna Peluang");
?>

<?php 
$from = UI::createTextArea('penanganan_peluang',$row['penanganan_peluang'],'','',$edited,$class='form-control',"");
echo UI::createFormGroup($from, $rules["penanganan_peluang"], "penanganan_peluang", "Penanganan Peluang");
*/
        ?>

        <?php
        $from = UI::showButtonMode("save", null, $edited);
        echo UI::createFormGroup($from);
        ?>
    </div>
</div>