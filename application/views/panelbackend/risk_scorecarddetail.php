<?php
$from = UI::createSelect('id_parent_scorecard', $scorecardarr, $rowheader['id_parent_scorecard'], $editedheader, $class = 'form-control ', "style='width:100%;'");
echo UI::createFormGroup($from, $rules["id_parent_scorecard"], "id_parent_scorecard", "Induk Risk Register", false, 2, $editedheader);

if ($rowheader['navigasi'] === null)
    $rowheader['navigasi'] = 0;

$from = UI::createSelect('navigasi', array('1' => 'Folder', '0' => 'Risiko'), $rowheader['navigasi'], $editedheader, 'form-control ', "style='width:100%;' onchange='goSubmit()'");
echo UI::createFormGroup($from, $rules["navigasi"], "navigasi", "Navigasi", false, 2, $editedheader);

if (!$rowheader['navigasi']) {
    // $from = UI::createSelect('rutin_non_rutin', $runitnonnurinarr, $row['rutin_non_rutin'], $editedheader, 'form-control select2', "onchange='goSubmit(\"set_value\")' style='width:200px'");
    // echo UI::createFormGroup($from, $rules["rutin_non_rutin"], "rutin_non_rutin", "Rutin / Non-Rutin", false, 2, $editedheader);

    $from = UI::createSelect('id_tingkat_agregasi_risiko', $agregasiarr, $rowheader['id_tingkat_agregasi_risiko'], $editedheader, $class = 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["id_tingkat_agregasi_risiko"], "id_tingkat_agregasi_risiko", "Agregasi", false, 2, $editedheader);

    // $from = UI::createSelect('id_konteks', $konteksarr, $rowheader['id_konteks'], $editedheader, $class = 'form-control ', "style='width:100%;'");
    // echo UI::createFormGroup($from, $rules["id_konteks"], "id_konteks", "Konteks", false, 2, $editedheader);

    $from = UI::createSelect('id_unit', $unitarr, $rowheader['id_unit'], $editedheader, $class = 'form-control ', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
    echo UI::createFormGroup($from, $rules["id_unit"], "id_unit", "Unit", false, 2, $editedheader);

    $from = UI::createSelect('owner', $ownerarr, $rowheader['owner'], $editedheader, 'form-control select2');
    echo UI::createFormGroup($from, $rules["owner"], "owner", "Risk Owner", false, 2, $editedheader);

    // $from = UI::createSelectMultiple('user[]', $userarr, $row['user'], $edited, $class = 'form-control select2', "style='width:auto; width:100%;' data-tags='true'");
    // echo UI::createFormGroup($from, $rules["user[]"], "user[]", "Risk User", false, 2);
}
?>

<?php
$from = UI::createTextBox('nama', $rowheader['nama'], '', '', $editedheader, $class = 'form-control', "");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama", false, 2, $editedheader);
?>

<?php
if (!$rowheader['navigasi']) {
    //     $from = UI::createTextArea('scope', $rowheader['scope'], '', '', $editedheader, $class = 'form-control', "");
    //     echo UI::createFormGroup($from, $rules["scope"], "scope", "Scope", false, 2, $editedheader);

    //     $from = UI::createCheckBox("is_kegiatan", 1, $rowheader['is_kegiatan'], "Sasaran kegiatan", $editedheader);
    //     echo UI::createFormGroup($from, $rules["is_kegiatan"], "is_kegiatan", "", false, 2, $editedheader);

    //     $from = UI::createCheckBox("is_info", 1, $rowheader['is_info'], "Tampilkan informasi data pendukung", $editedheader);
    //     echo UI::createFormGroup($from, $rules["is_info"], "is_info", "", false, 2, $editedheader);

    if ($row['rutin_non_rutin'] == 'nonrutin') {

        $from = UI::createTextBox('jenis_proyek', $row['jenis_proyek'], '', '', $editedheader, $class = 'form-control', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["jenis_proyek"], "jenis_proyek", "Jenis Proyek", false, 2, $editedheader);

        // $from = UI::createTextBox('id_sasaran_proyek', $row['id_sasaran_proyek'], '', '', $editedheader, $class = 'form-control', "style='width:100%'");
        // echo UI::createFormGroup($from, $rules["id_sasaran_proyek"], "id_sasaran_proyek", "Objektif/Sasaran Proyek", false, 2, $editedheader);

        $from = UI::createSelect('id_sasaran_proyek', $risksasaranarr, $row['id_sasaran_proyek'], $editedheader, 'form-control select2', "style='width:100%;' data-tags='true' data-ajax--data-type=\"json\" data-ajax--url=\"" . base_url('panelbackend/ajax/risksasaranarr') . "\"");
        echo UI::createFormGroup($from, $rules["id_sasaran_proyek"], "id_sasaran_proyek", "Objektif/Sasaran Proyek", false, 2, $editedheader);

        $from = UI::createTextBox('biaya_proyek', $row['biaya_proyek'], '', '', $editedheader, $class = 'form-control rupiah', "style='width:100%'");
        echo UI::createFormGroup($from, $rules["biaya_proyek"], "biaya_proyek", "Total Biaya Proyek (IDR)", false, 2, $editedheader);

        $from = UI::createTextBox('tgl_mulai', $row['tgl_mulai'], '', '', $editedheader, $class = 'form-control datepicker', "style='width:100px'");
        echo UI::createFormGroup($from, $rules["tgl_mulai"], "tgl_mulai", "Tgl. Dimulai Proyek", false, 2, $editedheader);

        $from = UI::createTextBox('tgl_selesai', $row['tgl_selesai'], '', '', $editedheader, $class = 'form-control datepicker', "style='width:100px'");
        echo UI::createFormGroup($from, $rules["tgl_selesai"], "tgl_selesai", "Tgl. Selesai Proyek", false, 2, $editedheader);
    }
}
?>

<?php
if ($editedheader) {
    $from = UI::createTextBox('tgl_mulai_efektif', ($row['tgl_mulai_efektif'] ? $row['tgl_mulai_efektif'] : date('Y-m-d')), '', '', $editedheader, $class = 'form-control datepicker', "style='width:100px'");
    echo UI::createFormGroup($from, $rules["tgl_mulai_efektif"], "tgl_mulai_efektif", "Tgl. Mulai Efektif", false, 2, $editedheader);
?>


<?php
    $from = UI::createTextBox('tgl_akhir_efektif', $row['tgl_akhir_efektif'], '', '', $editedheader, $class = 'form-control datepicker', "style='width:100px'");
    echo UI::createFormGroup($from, $rules["tgl_akhir_efektif"], "tgl_akhir_efektif", "Tgl. Akhir Efektif", false, 2, $editedheader);
}
?>

<?php
// $form = UI::createSelectMultiple('id_jabatan[]', $mtsdmjabatanarr, $row['id_jabatan'], $edited, $class = 'form-control select2', "data-ajax--data-type=\"json\" data-ajax--url=\"" . base_url('panelbackend/ajax/listjabatan') . "\" style='width:100%;'");
// echo UI::createFormGroup($form, $rules["id_jabatan[]"], "id_jabatan[]", "Bisa Dilihat Oleh ?", false, 2, $edited);
?>

<?php
$from = UI::showButtonMode("save", $rowheader[$pk], $editedheader, null, "btn-sm", $this->access_role_custom['panelbackend/risk_scorecard']);
echo UI::createFormGroup($from, null, null, null, false, 2);
?>