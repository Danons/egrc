<div class="col-sm-6">

    <?php
    $from = UI::createTextArea('nama', $row['nama'], '', '', $edited, $class = 'form-control ', "");
    echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
    ?>



    <?php
    $from = UI::createTextBox('tgl', $row['tgl'], '10', '10', $edited, $class = 'form-control datepicker', "style='width:100px;'");
    echo UI::createFormGroup($from, $rules["tgl"], "tgl", "Tgl. Mulai");
    ?>

    <?php
    $from = UI::createTextBox('tgl_selesai', $row['tgl'], '10', '10', $edited, $class = 'form-control datepicker', "style='width:100px'");
    echo UI::createFormGroup($from, $rules["tgl_selesai"], "tgl_selesai", "Tgl. Selesai");
    ?>


    <?php
    if (in_array($this->id_kategori, [2, 3])) {
        $from = UI::createTextNumber(
            'target_lvl',
            $row['target_lvl'],
            '10',
            '10',
            $edited,
            $class = 'form-control',
            "style='width:100px'"
        );
        echo UI::createFormGroup($from, $rules["target_lvl"], "target_lvl", "Target Level");
    }
    ?>

</div>
<div class="col-sm-6">


    <?php /*
$from = UI::createTextBox('page_ctrl',$row['page_ctrl'],'200','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["page_ctrl"], "page_ctrl", "Page Ctrl");
?>

<?php 
$from = UI::createTextNumber('id_kategori',$row['id_kategori'],'10','10',$edited,$class='form-control ',"style='text-align:right; width:190px' min='0' max='10000000000' step='any'");
echo UI::createFormGroup($from, $rules["id_kategori"], "id_kategori", "Kategori");*/
    ?>

    <?php
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from);
    ?>
</div>