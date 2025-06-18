<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Opp_evaluasi extends _adminController
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function init()
    {
        parent::init();
        $this->viewlist = "panelbackend/opp_evaluasilist";
        $this->viewdetail = "panelbackend/opp_evaluasidetail";
        $this->template = "panelbackend/main";
        $this->layout = "panelbackend/layout_peluang";

        if ($this->mode == 'add') {
            $this->data['page_title'] = 'Evaluasi Peluang';
            $this->data['edited'] = true;
        } elseif ($this->mode == 'edit') {
            $this->data['page_title'] = 'Evaluasi Peluang';
            $this->data['edited'] = true;
            unset($this->access_role['lst']);
        } elseif ($this->mode == 'detail') {
            $this->data['page_title'] = 'Evaluasi Peluang';
            $this->data['edited'] = false;
            unset($this->access_role['lst']);
        } else {
            $this->data['page_title'] = 'Evaluasi Peluang';
        }

        $this->load->model("Opp_peluangModel", "model");

        $this->load->model("Mt_opp_kriteria_dampakModel", 'kriteria');
        $this->data['kriteriaarr'] = $this->kriteria->GetCombo();


        $this->SetAccess('panelbackend/opp_scorecard');

        $this->pk = $this->model->pk;
        $this->data['pk'] = $this->pk;
        $this->plugin_arr = array(
            'datepicker', 'select2'
        );

        $this->data['id_periode_tw_kri'] = 2;
        $this->data['tahun_kri'] = $this->data['thn'] = date("Y");
    }

    protected function Record($id = null)
    {

        $record =  array(
            'progress_capaian_kinerja' => $this->post['progress_capaian_kinerja'],
            'progress_capaian_sasaran' => $this->post['progress_capaian_sasaran'],
            'penyesuaian_tindakan_mitigasi' => $this->post['penyesuaian_tindakan_mitigasi'],
            'hambatan_kendala' => $this->post['hambatan_kendala'],
            'residual_dampak_evaluasi' => $this->post['residual_dampak_evaluasi'],
            'residual_kemungkinan_evaluasi' => $this->post['residual_kemungkinan_evaluasi'],
            'current_opp_dampak' => $this->post['residual_dampak_evaluasi'],
            'current_opp_kemungkinan' => $this->post['residual_kemungkinan_evaluasi'],
            'dampak_kuantitatif_residual' => Rupiah2Number($this->post['dampak_kuantitatif_residual']),
        );

        if ($record['progress_capaian_kinerja'])
            $record['is_evaluasi_peluang'] = 1;

        if ($this->post['status_peluang'] !== "" && $this->post['status_peluang'] !== null) {
            $record['status_peluang'] = $this->post['status_peluang'];
        }

        return $record;
    }

    protected function Rules()
    {
        $return = array(
            "progress_capaian_kinerja" => array(
                'field' => 'progress_capaian_kinerja',
                'label' => 'Progress Capaian Kinerja',
                'rules' => "required|max_length[200]",
            ),
            "penyesuaian_tindakan_mitigasi" => array(
                'field' => 'penyesuaian_tindakan_mitigasi',
                'label' => 'Penyesuaian Tindakan Mitigasi',
                'rules' => "max_length[4000]|required",
            ),
        );

        return $return;
    }

    public function Index($id_scorecard = null, $id = null)
    {
        redirect("panelbackend/opp_evaluasi/detail/$id_scorecard/$id");
    }

    public function Add($id_scorecard = null)
    {
        $this->Error403();
    }

    public function Edit($id_scorecard = null, $id = null)
    {

        if ($this->post['act'] == 'reset') {
            redirect(current_url());
        }

        $this->_beforeDetail($id_scorecard, $id);
        $this->data['row'] = $this->model->GetByPk($id);

        if (!$this->data['row'] && $id)
            $this->NoData();

        $this->data['rowheader1'] = $this->data['row'];

        // $this->isLock();

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters("", "");

        if ($this->post && $this->post['act'] <> 'change' && $this->post['act'] <> 'set_tgl_peluang') {
            if (!$this->data['row'])
                $this->data['row'] = array();

            $record = $this->Record($id);


            $this->data['row'] = array_merge($this->data['row'], $record);
        }

        $this->_onDetail($id, $record);
        $this->data['rules'] = $this->Rules();

        ## EDIT HERE ##
        if ($this->post['act'] === 'save') {
            $record['id_scorecard'] = $id_scorecard;

            $this->_isValid($record, true);

            $this->_beforeEdit($record, $id);

            $this->_setLogRecord($record, $id);

            $this->model->conn->StartTrans();
            if ($this->data['row'][$this->pk]) {

                $return = $this->_beforeUpdate($record, $id);

                if ($return) {
                    $return = $this->model->Update($record, "$this->pk = " . $this->conn->qstr($id));
                }

                if ($return['success']) {

                    $this->log("mengubah", $this->data['row']);

                    $return1 = $this->_afterUpdate($id);

                    if (!$return1) {
                        $return = false;
                    }
                }
            } else {

                $is_insert = true;

                $return = $this->_beforeInsert($record);

                if ($return) {
                    $return = $this->model->Insert($record);
                    $id = $return['data'][$this->pk];
                }

                if ($return['success']) {

                    $this->log("menambah", $record);

                    $return1 = $this->_afterInsert($id);

                    if (!$return1) {
                        $return = false;
                    }
                }
            }

            if ($return['success']) {

                $this->model->conn->trans_commit();

                $this->_afterEditSucceed($id);

                SetFlash('suc_msg', $return['success']);
                if ($this->data['id_peluang_new']) {
                    $id = $this->data['id_peluang_new'];
                    $this->ctrl = 'opp_peluang';
                    $id_scorecard_new = $this->data['id_scorecard_new'];
                    redirect("panelbackend/opp_peluang/detail/$id_scorecard_new/$id");
                } else {
                    redirect("$this->page_ctrl/detail/$id_scorecard/$id");
                }
            } else {

                $this->model->conn->trans_rollback();

                $this->data['row'] = array_merge($this->data['row'], $record);
                $this->data['row'] = array_merge($this->data['row'], $this->post);

                $this->_afterEditFailed($id);

                $this->data['err_msg'] = "Data gagal disimpan";
            }
        }

        $this->_afterDetail($id);

        $this->View($this->viewdetail);
    }


    public function Detail($id_scorecard = null, $id = null)
    {
        $this->_beforeDetail($id_scorecard, $id);

        $this->data['row'] = $this->model->GetByPk($id);

        $this->data['rowheader1'] = $this->data['row'];

        $this->_onDetail($id);

        // $this->isLock();

        if (!$this->data['row'])
            $this->NoData();

        if (!$this->data['row']['progress_capaian_kinerja'] && $this->access_role['edit']) {
            redirect("panelbackend/opp_evaluasi/edit/$id_scorecard/$id");
            die();
        }

        $this->_afterDetail($id);

        $this->View($this->viewdetail);
    }

    public function Delete($id_scorecard = null, $id = null)
    {

        $this->model->conn->StartTrans();

        $this->_beforeDetail($id_scorecard, $id);

        $this->data['row'] = $this->model->GetByPk($id);

        $this->data['rowheader1'] = $this->data['row'];

        $this->_onDetail($id);

        if (!$this->data['row'])
            $this->NoData();

        $return = $this->_beforeDelete($id);

        if (!$this->access_role['delete'])
            $this->Error403();

        if ($return) {
            $return = $this->model->delete("$this->pk = " . $this->conn->qstr($id));
        }

        if ($return) {
            $return1 = $this->_afterDelete($id);
            if (!$return1)
                $return = false;
        }

        $this->model->conn->CompleteTrans();

        if ($return) {

            $this->log("menghapus", $this->data['row']);

            SetFlash('suc_msg', $return['success']);
            redirect("$this->page_ctrl/index/$id_scorecard");
        } else {
            SetFlash('err_msg', "Data gagal didelete");
            redirect("$this->page_ctrl/detail/$id_scorecard/$id");
        }
    }

    protected function _beforeDetail($id = null, $id_peluang = null)
    {

        #mengambil dari model karena sudah difilter sesuai akses
        $this->load->model("Opp_scorecardModel", 'oppscorecard');
        $this->data['rowheader']  = $this->oppscorecard->GetByPk($id);
        if (!$this->data['rowheader'])
            $this->NoData();

        $owner = $this->data['rowheader']['owner'];

        if ($owner) {
            $this->data['ownerarr'][$owner] = $this->conn->GetOne("select concat(nama,' (',ifnull(id_unit,''),')') from mt_sdm_jabatan where deleted_date is null and id_jabatan = " . $this->conn->escape($owner));
        }

        $this->data['add_param'] .= $id . "/" . $id_peluang;
    }

    protected function _beforeUpdate($record = array(), $id = null)
    {

        $row = $this->model->GetByPk($id);

        $this->oppchangelog($record, $row);

        return true;
    }

    protected function _beforeInsert($record = array())
    {
        $this->oppchangelog($record);
        return true;
    }

    protected function _afterDetail($id)
    {
        $this->data['editedheader1'] = $this->data['edited'];

        $this->data['rowheader1'] = $this->data['row'];

        $tgl_peluang = $this->data['rowheader1']['tgl_peluang'];

        if ($this->post['tgl_peluang'])
            $tgl_peluang = $this->post['tgl_peluang'];

        // $this->conn->debug = 1;
        $this->data['scorecardarr'] = $this->oppscorecard->GetCombo(null, null, $tgl_peluang, $this->data['rowheader']['id_parent_scorecard']);

        // dpr($this->data['scorecardarr'],1);

        $id_peluang = $this->data['rowheader1']['id_peluang'];

        if ($id) {
            $this->data['mode'] = 'edit_detail';
        }
    }

    protected function _onDetail($id = null, &$record = array())
    {
        if ($this->data['rowheader1']['oppowner'] && $this->data['rowheader']['is_owner_in_opp'])
            $this->data['rowheader']['owner'] = $this->data['rowheader1']['oppowner'];

        $owner = $this->data['rowheader']['owner'];

        if ($owner) {
            $this->data['ownerarr'][$owner] = $this->conn->GetOne("select concat(a.nama,' (',ifnull(b.table_desc,''),')') from mt_sdm_jabatan a left join mt_sdm_unit b on a.id_unit = b.table_code where a.deleted_date is null and id_jabatan = " . $this->conn->escape($owner));
        }
    }

    protected function _beforeEdit(&$record = array(), $id)
    {
        $this->_validAccessTask($this->data['rowheader'], $this->data['rowheader1'], $this->data['edited']);
        return true;
    }

    protected function _afterUpdate($id)
    {
        $ret = $this->_afterInsert($id);

        return $ret;
    }

    protected function _afterInsert($id)
    {
        $ret = true;

        if ($this->Access('evaluasimitigasi', "panelbackend/opp_scorecard")) {
            $cek = (int)!$this->conn->GetOne("select 1 from opp_peluang 
			where deleted_date is null and id_scorecard = " . $this->conn->escape($this->data['row']['id_scorecard']) . " 
			and tgl_close is null 
			and (is_evaluasi_peluang = 0 or is_evaluasi_peluang is null)");

            $this->conn->GoUpdate(
                "opp_scorecard",
                ['is_evaluasi_peluang' => $cek],
                "id_scorecard = " . $this->conn->escape($this->data['row']['id_scorecard'])
            );
        }

        return $ret;
    }

    private function _setLogRec(&$record = array(), $is_edit = false)
    {

        unset($record['created_date']);
        unset($record['created_by']);
        unset($record['modified_date']);
        unset($record['modified_by']);
        unset($record['is_lock']);
        unset($record['id_control']);
        unset($record['id_peluang']);
        unset($record['id_peluang_files']);
        unset($record['id_mitigasi']);
        unset($record['id_control_efektifitas_files']);
        unset($record['id_mitigasi_files']);

        $this->_setLogRecord($record, $is_edit);
    }
}
