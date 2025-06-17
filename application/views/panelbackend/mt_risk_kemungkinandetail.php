<div class="row">
    <div class="col-sm-6">

        <?php
        $from = UI::createTextBox('kode', $row['kode'], '300', '100', $edited, $class = 'form-control ', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["kode"], "kode", "Kode");
        ?>


        <?php
        $from = UI::createTextNumber('rating', (float)$row['rating'], '300', '100', $edited, $class = 'form-control ', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["rating"], "rating", "Nilai");
        ?>

        <?php
        if ($_SESSION[SESSION_APP][$this->page_ctrl]['rutin_non_rutin']) {
            $from = UI::createTextBox('nama_non_rutin', $row['nama_non_rutin'], '300', '100', $edited, $class = 'form-control ', "style='width:100%'");
            echo UI::createFormGroup($from, $rules["nama_non_rutin"], "nama_non_rutin", "Tingkat Kemungkinan");
        ?>

            <?php
            $from = UI::createTextBox('probabilitas_non_rutin', $row['probabilitas_non_rutin'], '50', '50', $edited, $class = 'form-control ', "style='width:100%'");
            echo UI::createFormGroup($from, $rules["probabilitas_non_rutin"], "probabilitas_non_rutin", "Probabilitas");
            ?>

        <?php
            $from = UI::createTextArea('deskripsi_kualitatif_non_rutin', $row['deskripsi_kualitatif_non_rutin'], '', '', $edited, $class = 'form-control', "");
            echo UI::createFormGroup($from, $rules["deskripsi_kualitatif_non_rutin"], "deskripsi_kualitatif_non_rutin", "Kuantitatif (Probabilitas)");
        } else {
            $from = UI::createTextBox('nama', $row['nama'], '300', '100', $edited, $class = 'form-control ', "style='width:100%'");
            echo UI::createFormGroup($from, $rules["nama"], "nama", "Tingkat Kemungkinan");
        ?>

            <?php
            $from = UI::createTextBox('probabilitas', $row['probabilitas'], '50', '50', $edited, $class = 'form-control ', "style='width:100%'");
            echo UI::createFormGroup($from, $rules["probabilitas"], "probabilitas", "Kuantitatif (Probabilitas)");
            ?>

        <?php
            $from = UI::createTextArea('deskripsi_kualitatif', $row['deskripsi_kualitatif'], '', '', $edited, $class = 'form-control', "");
            echo UI::createFormGroup($from, $rules["deskripsi_kualitatif"], "deskripsi_kualitatif", "Kualitatif");
        }
        ?>

        <?php
        // $from = UI::createTextArea('insiden_sebelumnya',$row['insiden_sebelumnya'],'','',$edited,$class='form-control',"");
        // echo UI::createFormGroup($from, $rules["insiden_sebelumnya"], "insiden_sebelumnya", "Frekuensi");
        ?>

    </div>
    <div class="col-sm-6">
        <?php
        $from = UI::showButtonMode("save", null, $edited);
        echo UI::createFormGroup($from);
        ?>
    </div>
</div>