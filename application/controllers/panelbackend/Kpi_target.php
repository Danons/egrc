<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Kpi_target extends _adminController
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function init()
    {
        parent::init();

        $this->viewlist = "panelbackend/kpi_targetlist";
        $this->viewdetail = "panelbackend/kpi_targetdetail";
        $this->template = "panelbackend/main";
        $this->layout = "panelbackend/layout1";

        if ($this->mode == 'add') {
            $this->data['page_title'] = 'Tambah Target KPI';
            $this->data['edited'] = true;
        } elseif ($this->mode == 'edit') {
            $this->data['page_title'] = 'Edit Target KPI';
            $this->data['edited'] = true;
        } elseif ($this->mode == 'detail') {
            $this->data['page_title'] = 'Detail Target KPI';
            $this->data['edited'] = false;
        } else {
            $this->data['page_title'] = 'KPI Unit';
        }

        $this->load->model("Kpi_targetModel", "model");

        $this->load->model("KpiModel", "kpi");

        $this->load->model("Mt_sdm_dit_bidModel", "dept");
        $this->data['deptarr'] = $this->dept->GetCombo();

        $this->load->model("Mt_sdm_unitModel", "unit");
        $this->data['unitarr'] = $this->unit->GetCombo();
        $this->data['unitarr'][''] = 'Pilih Unit';

        $tahun = array(null => 'Pilih Tahun', date('Y') => date('Y'), date('Y') - 1 => date('Y') - 1, date('Y') - 2 => date('Y') - 2);

        $this->data['tahunarr'] = $tahun;

        $this->pk = $this->model->pk;
        $this->data['pk'] = $this->pk;
        $this->plugin_arr = array(
            'select2', 'treetable', 'tinymce'
        );

        $this->access_role['list_print'] = true;
    }

    protected function Header()
    {
        return array(
            array(
                'name' => 'nama',
                'label' => 'Indikator KPI',
                'type' => "list",
                'nofilter' => true,
            ),
            array(
                'name' => 'bobot',
                'label' => 'Bobot',
                'width' => "80px",
                'type' => "number",
                'nofilter' => true,
            ),
            array(
                'name' => 'polarisasi',
                'label' => 'Polarisasi',
                'width' => "auto",
                'type' => "varchar",
                'nofilter' => true,
            ),
            array(
                'name' => 'target',
                'label' => 'Target',
                'width' => "80px",
                'type' => "number",
                'nofilter' => true,
            ),
            array(
                'name' => 'satuan',
                'label' => 'Satuan',
                'width' => "auto",
                'type' => "varchar",
                'nofilter' => true,
            ),
            array(
                'name' => 'totrealisasi',
                'label' => 'Total Realisasi',
                'width' => "80px",
                'type' => "number",
                'nofilter' => true,
            ),
            array(
                'name' => 'prostarget',
                'label' => '% thdp Realisasi',
                'width' => "80px",
                'type' => "number",
                'nofilter' => true,
            ),
            array(
                'name' => 'realbobot',
                'label' => 'Realisasi Skor Bobot',
                'width' => "80px",
                'type' => "number",
                'nofilter' => true,
            ),
            array(
                'name' => 'lastinput',
                'label' => 'Input Terakhir',
                'width' => "auto",
                'type' => "list",
                'value' => ListBulan(),
                'nofilter' => true,
            ),
        );
    }

    protected function Record($id = null)
    {
        return array(
            'id_dit_bid' => $this->post['id_dit_bid'],
            'id_unit' => $this->post['id_unit'],
            'satuan' => $this->post['satuan'],
            'jenis' => $this->post['jenis'],
            'bobot' => $this->post['bobot'],
            'bobot1' => $this->post['bobot1'],
            'polarisasi' => $this->post['polarisasi'],
            'target' => $this->post['target'],
            'analisa' => $this->post['analisa'],
            'is_pic' => (int)$this->post['is_pic'],

            // 'definisi' => $this->post['definisi'],
            // 'tujuan' => $this->post['tujuan'],
            // 'formula' => $this->post['formula'],
            'definisi' => $_POST['definisi'],
            'tujuan' => $_POST['tujuan'],
            'formula' => $_POST['formula'],
        );
    }

    protected function _isValid($record = array(), $show_error = true)
    {
        if (!is_array($this->data['rules']))
            return;

        $rules = array_values($this->data['rules']);

        $this->form_validation->set_rules($rules);

        if (count($rules) && $this->form_validation->run() == FALSE) {
            if ($show_error) {
                $this->data['err_msg'] = validation_errors();
            }

            $this->data['row'] = array_merge($this->data['row'], $record);

            $this->_afterDetail($this->data['row'][$this->pk]);

            $this->View($this->viewdetail);
            exit();
        }

        $id_unit = $record['id_unit'];
        $id_kpi = $record['id_kpi'];
        $tahun = $record['tahun'];

        $totalbobot = (int)$this->conn->GetOne("select sum(bobot) 
		from kpi_target 
		where deleted_date is null and id_unit = " . $this->conn->escape($id_unit) . " 
		and id_kpi <> " . $this->conn->escape($id_kpi) . "
		and tahun = " . $this->conn->escape($tahun)) + (int)$record['bobot'];

        if ($totalbobot > 100) {
            $this->data['err_msg'] = "Total bobot : " . $totalbobot . ", tidak boleh lebih 100";

            $this->data['row'] = array_merge($this->data['row'], $record);

            $this->_afterDetail($this->data['row'][$this->pk]);

            $this->View($this->viewdetail);
            exit();
        }
    }

    protected function Rules()
    {
        return array(
            "satuan" => array(
                'field' => 'satuan',
                'label' => 'Satuan',
                'rules' => "max_length[225]",
            ),
            "bobot" => array(
                'field' => 'bobot',
                'label' => 'Bobot',
                'rules' => "numeric|max_length[10]",
            ),
            "polarisasi" => array(
                'field' => 'polarisasi',
                'label' => 'Polarisasi',
                'rules' => "max_length[100]",
            ),
            "target" => array(
                'field' => 'target',
                'label' => 'Target',
                // 'rules' => "numeric|max_length[10]",
                'rules' => "numeric",
            ),
            "analisa" => array(
                'field' => 'analisa',
                'label' => 'Analisa',
                'rules' => "",
            ),
            "tahun" => array(
                'field' => 'tahun',
                'label' => 'Tahun',
                'rules' => "in_list[" . implode(",", array_keys($this->data['tahunarr'])) . "]|max_length[10]",
            ),
        );
    }

    public function Index($id_kpi = null, $tahun = null)
    {
        redirect("panelbackend/kpi_config/detail/$id_kpi/$tahun");
    }

    protected function _beforeDetail($id_kpi = null, $tahun = null, $id = null)
    {
        $this->data['add_param'] .= $id_kpi;
        if ($tahun)
            $this->data['add_param'] .= "/" . $tahun;

        $this->data['rowheader'] = $this->kpi->GetByPk($id_kpi);
        $this->data['rowheader']['tahun'] = $tahun;
    }

    public function Add($id_kpi = null, $tahun = null)
    {
        $this->Edit($id_kpi, $tahun);
    }
    public function data_add($id_kpi, $tahun)
    {
        $id_kpi_target = $this->conn->GetOne("select max(id_kpi_target) as id_kpi_target from kpi_target where deleted_date is null and id_kpi = " .$this->conn->escape($id_kpi));
        $data = $this->conn->GetRow("select definisi,tujuan,formula from kpi_target where deleted_date is null and id_kpi_target = " . $this->conn->escape($id_kpi_target));

        return $data;
    }

    public function Edit($id_kpi = null, $tahun = null, $id = null)
    {
        if ($this->post['act'] == 'reset') {
            redirect(current_url());
        }

        if ($this->post['tahun'])
            $tahun = $this->post['tahun'];

        $this->_beforeDetail($id_kpi, $tahun, $id);

        $this->data['row'] = $this->model->GetByPk($id);

        $isadd = false;
        if (!$this->data['row'])
            $isadd = true;

        if (!$id) {
            $this->data['row'] = $this->data_add($id_kpi, $tahun);
        }
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters("", "");

        if ($this->post && $this->post['act'] <> 'change') {
            if (!$this->data['row'])
                $this->data['row'] = array();

            $record = $this->Record($id);

            $this->data['row'] = array_merge($this->data['row'], $record);
            $this->data['row'] = array_merge($this->data['row'], $this->post);
        }

        $this->data['rules'] = $this->Rules();

        ## EDIT HERE ##
        if ($this->post['act'] === 'save') {
            $record['id_kpi'] = $id_kpi;
            $record['tahun'] = $tahun;

            $this->_isValid($record, true);

            $this->_beforeEdit($record, $id);

            $this->_setLogRecord($record, $id);

            $this->model->conn->StartTrans();
            if (!$isadd) {

                $return = $this->_beforeUpdate($record, $id);

                if ($return) {
                    $return = $this->model->Update($record, $this->pk . "=" . $id);
                }

                if ($return['success']) {

                    $this->log("mengubah", $this->data['row']);

                    $return1 = $this->_afterUpdate($id);

                    if (!$return1) {
                        $return = false;
                    }
                }
            } else {

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

            $this->model->conn->CompleteTrans();

            if ($return['success']) {

                $this->_afterEditSucceed($id);

                SetFlash('suc_msg', $return['success']);
                redirect("$this->page_ctrl/detail/$id_kpi/$tahun/$id");
            } else {
                $this->data['row'] = array_merge($this->data['row'], $record);
                $this->data['row'] = array_merge($this->data['row'], $this->post);

                $this->_afterEditFailed($id);

                $this->data['err_msg'] = "Data gagal disimpan";
            }
        }

        $this->_afterDetail($id_kpi, $tahun, $id);

        $this->View($this->viewdetail);
    }

    public function Detail($id_kpi = null, $tahun = null, $id = null)
    {

        $this->_beforeDetail($id_kpi, $tahun, $id);

        $this->data['row'] = $this->model->GetByPk($id);

        if (!$this->data['row'])
            $this->NoData();

        $this->_afterDetail($id_kpi, $tahun, $id);

        $this->View($this->viewdetail);
    }

    public function Delete($id_kpi = null, $tahun = null, $id = null)
    {

        $this->model->conn->StartTrans();

        $this->_beforeDetail($id_kpi, $tahun, $id);

        $this->data['row'] = $this->model->GetByPk($id);

        if (!$this->data['row'])
            $this->NoData();

        $return = $this->_beforeDelete($id);

        if ($return) {
            $return = $this->model->delete($this->pk . "=" . $id);
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
            redirect("$this->page_ctrl");
        } else {
            SetFlash('err_msg', "Data gagal didelete");
            redirect("$this->page_ctrl/detail/$id_kpi/$tahun/$id");
        }
    }
}
