<?php
if ($this->access_role['view_all'] && $editedheader) {

    $from = UI::createSelect('id_parent_scorecard', $scorecardarr, $rowheader['id_parent_scorecard'], ($this->access_role['view_all'] && $editedheader), $class = 'form-control ', "style='width:100%;'");
    echo UI::createFormGroup($from, $rules["id_parent_scorecard"], "id_parent_scorecard", "Induk Risk Register", false, 2, $editedheader);

    if ($rowheader['navigasi'] === null)
        $rowheader['navigasi'] = 0;

    $from = UI::createSelect('navigasi', array('1' => 'Folder', '0' => 'Peluang'), $rowheader['navigasi'], ($this->access_role['view_all'] && $editedheader), 'form-control ', "style='width:100%;'' onchange='goSubmit()'");
    echo UI::createFormGroup($from, $rules["navigasi"], "navigasi", "Navigasi", false, 2, ($this->access_role['view_all'] && $editedheader));
?>

<?php
    if (!$rowheader['navigasi']) {
        $from = UI::createSelect('id_unit', $unitarr, $rowheader['id_unit'], ($this->access_role['view_all'] && $editedheader), $class = 'form-control ', "style='width:100%;'' onchange='goSubmit(\"set_value\")'");
        echo UI::createFormGroup($from, $rules["id_unit"], "id_unit", "Unit", false, 2, ($this->access_role['view_all'] && $editedheader));
    }
}
?>

<?php
if (!$rowheader['navigasi']) {
    $from = UI::createSelect('owner', $ownerarr, $rowheader['owner'], ($this->access_role['view_all'] && $editedheader), 'form-control select2', " onchange='goSubmit(\"set_value\")'");
    echo UI::createFormGroup($from, $rules["owner"], "owner", "Risk Owner", false, 2, ($this->access_role['view_all'] && $editedheader));

    //     $from = UI::createSelectMultiple('user[]', $userarr, $row['user'], $edited, $class = 'form-control select2', "style='width:auto; width:100%;' data-tags='true'");
    //     echo UI::createFormGroup($from, $rules["user[]"], "user[]", "Risk User", false, 2);
}
?>


<?php
$from = UI::createTextBox('nama', $rowheader['nama'], '', '', ($this->access_role['view_all'] && $editedheader), $class = 'form-control', "");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama", false, 2, ($this->access_role['view_all'] && $editedheader));
?>

<?php
// if (!$rowheader['navigasi']) {
//     $from = UI::createTextArea('scope', $rowheader['scope'], '', '', $editedheader, $class = 'form-control', "");
//     echo UI::createFormGroup($from, $rules["scope"], "scope", "Scope", false, 2, $editedheader);

//     $from = UI::createCheckBox("is_kegiatan", 1, $rowheader['is_kegiatan'], "Sasaran kegiatan", $editedheader);
//     echo UI::createFormGroup($from, $rules["is_kegiatan"], "is_kegiatan", "", false, 2, $editedheader);

//     $from = UI::createCheckBox("is_info", 1, $rowheader['is_info'], "Tampilkan informasi data pendukung", $editedheader);
//     echo UI::createFormGroup($from, $rules["is_info"], "is_info", "", false, 2, $editedheader);
// }
?>

<?php
if ($this->access_role['view_all'] && $editedheader) {
    $from = UI::createTextBox('tgl_mulai_efektif', ($row['tgl_mulai_efektif'] ? $row['tgl_mulai_efektif'] : date('Y-m-d')), '10', '10', ($this->access_role['view_all'] && $editedheader), $class = 'form-control datepicker', "style='width:100px'");
    echo UI::createFormGroup($from, $rules["tgl_mulai_efektif"], "tgl_mulai_efektif", "Tgl. Mulai Efektif", false, 2, ($this->access_role['view_all'] && $editedheader));
?>


<?php
    $from = UI::createTextBox('tgl_akhir_efektif', $row['tgl_akhir_efektif'], '10', '10', ($this->access_role['view_all'] && $editedheader), $class = 'form-control datepicker', "style='width:100px'");
    echo UI::createFormGroup($from, $rules["tgl_akhir_efektif"], "tgl_akhir_efektif", "Tgl. Akhir Efektif", false, 2, ($this->access_role['view_all'] && $editedheader));
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