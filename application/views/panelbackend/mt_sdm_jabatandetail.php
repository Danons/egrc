<div class="row">
    <div class="col-sm-6">

        <?php
        $from = UI::createSelect('id_jabatan_parent', $mtsdmjabatanarr, $row['id_jabatan_parent'], $edited, 'form-control select2', "data-ajax--data-type=\"json\" onchange='goSubmit(\"set_value\")' data-ajax--url=\"" . base_url('panelbackend/ajax/listjabatan') . "\" style='width:100%;'");
        echo UI::createFormGroup($from, $rules["id_jabatan_parent"], "id_jabatan_parent", "Jabatan Parent");
        ?>

        <?php
        // $from = UI::createSelect('id_dit_bid', $mtsdmditbidarr, $row['id_dit_bid'], $edited, 'form-control ', "onchange='goSubmit(\"set_value\")' style='width:100%;'");
        // echo UI::createFormGroup($from, $rules["id_dit_bid"], "id_dit_bid", "Direktorat");
        ?>

        <?php
        $from = UI::createSelect('id_unit', $mtsdmunitarr, $row['id_unit'], $edited, 'form-control ', "onchange='goSubmit(\"set_value\")' style='width:100%;'");
        echo UI::createFormGroup($from, $rules["id_unit"], "id_unit", "Unit");
        ?>

        <?php
        // $from = UI::createSelect('id_subbid', $mtsdmsubbidarr, $row['id_subbid'], $edited, 'form-control ', "onchange='goSubmit(\"set_value\")' style='width:100%;'");
        // echo UI::createFormGroup($from, $rules["id_subbid"], "id_subbid", "Bidang");
        ?>

        <?php
        $from = UI::createTextBox('position_id', $row['position_id'], '10', '10', $edited, 'form-control ', "style='width:100px'");
        echo UI::createFormGroup($from, $rules["position_id"], "position_id", "Kode Jabatan");
        $from = UI::createTextBox('nama', $row['nama'], '200', '100', $edited, 'form-control ', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
        ?>

        <?php
        $from = UI::createTextBox('tgl_mulai_efektif', $row['tgl_mulai_efektif'], '10', '10', $edited, 'form-control datepicker', "style='width:100px'");
        echo UI::createFormGroup($from, $rules["tgl_mulai_efektif"], "tgl_mulai_efektif", "Tgl. Mulai Efektif");
        ?>

        <?php
        $from = UI::createTextBox('tgl_akhir_efektif', $row['tgl_akhir_efektif'], '10', '10', $edited, 'form-control datepicker', "style='width:100px'");
        echo UI::createFormGroup($from, $rules["tgl_akhir_efektif"], "tgl_akhir_efektif", "Tgl. Akhir Efektif");
        ?>


        <?php
        $from = UI::createTextBox('urutan', $row['urutan'], '10', '10', $edited, 'form-control', "style='width:100px'");
        echo UI::createFormGroup($from, $rules["urutan"], "urutan", "Urutan");
        ?>
    </div>
    <div class="col-sm-6">


        <?php
        $from = UI::createSelect('id_sdm_level', $mtsdmlevelarr, $row['id_sdm_level'], $edited, 'form-control ', "style='width:100%;'");
        echo UI::createFormGroup($from, $rules["id_sdm_level"], "id_sdm_level", "Level");
        ?>

        <?php 
        $from = UI::createSelect('id_jenjang',$mtsdmjenjangarr,$row['id_jenjang'],$edited,'form-control ',"style='width:100%;'");
        echo UI::createFormGroup($from, $rules["id_jenjang"], "id_jenjang", "Jenjang");
        ?>
        <?php /*
$from = UI::createTextBox('superior_id',$row['superior_id'],'10','10',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["superior_id"], "superior_id", "Superior ID");
?>

<?php 
$from = UI::createSelect('id_kategori',$mtsdmkategoriarr,$row['id_kategori'],$edited,'form-control ',"style='width:100%;'");
echo UI::createFormGroup($from, $rules["id_kategori"], "id_kategori", "Kategori");
?>

<?php 
$from = UI::createSelect('id_tipe_unit',$mtsdmtipeunitarr,$row['id_tipe_unit'],$edited,'form-control ',"style='width:100%;'");
echo UI::createFormGroup($from, $rules["id_tipe_unit"], "id_tipe_unit", "Tipe Unit");
?>

<?php */
        ?>

        <?php

        $from = UI::showButtonMode("save", null, $edited);
        echo UI::createFormGroup($from);
        ?>
    </div>
</div>