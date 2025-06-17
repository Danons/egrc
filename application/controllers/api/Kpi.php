<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Kpi extends _adminController
{
    public $page_escape = array('api/kpi');

    public function __construct()
    {
    }

    public function Index($page = 0)
    {
        $this->isPrivateMethod("GET");
        $param = $this->requestData();
        $this->load->model("Risk_risikoModel", "model");

        $nama_kpi = $param['nama_kpi'];

        $filter = "";

        if ($nama_kpi) {
            $filter .= " and lower(nama) like '%" . strtolower($nama_kpi) . "%'";
        }

        if (!$this->Access("view_all"))
            $id_unit = $_SESSION[SESSION_APP]['id_unit'];
        if ($param['id_unit'])
            $id_unit = $param['id_unit'];

        if ($id_unit)
            $filter .= " and c.id_unit = " . $this->conn->escape($id_unit);

        $arr_params = array(
            'page' => 0,
            'limit' => $param['limit'] ? $param['limit'] : 50,
            'where' => '',
            'tahun' => $param['limit'] ? $param['limit'] : date("Y"),
            'order' => '',
            'filter' => $filter
        );

        $list = $this->model->SelectGridKpi(
            $arr_params
        );

        foreach ($list['rows'] as &$r) {
            $r['nama_kpi'] = $r['nama'];
            unset($r['nama']);
            unset($r['id_risiko1']);
        }

        $this->success($list);
    }

    protected function _isValid($record = array(), $show_error = true)
    {
        if (!is_array($this->data['rules']))
            return;

        $rules = array_values($this->data['rules']);

        $this->form_validation->set_rules($rules);

        if (count($rules) && $this->form_validation->run() == FALSE) {
            $this->fail(validation_errors());
        }
    }

    public function realisasi()
    {
        $this->isPrivateMethod("POST");

        $this->load->model("Kpi_target_realisasiModel", "model");

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters("", "");

        $record = $this->Record();
        $id_kpi = $record['id_kpi'];
        $tahun = $record['tahun'];

        $idt = $this->conn->GetOne("select id_kpi_target_realisasi from kpi_target_realisasi a
            where bulan = " . $this->conn->escape($record['bulan']) . " 
            and exists (select 1 from kpi_target b 
            where a.deleted_date is null and a.deleted_date is null and a.id_kpi_target = b.id_kpi_target 
            and b.tahun = " . $this->conn->escape($tahun) . " 
            and b.id_kpi = " . $this->conn->escape($id_kpi) . ")");

        if ($idt)
            $id = $idt;

        $this->_isValid($record, true);

        $this->_setLogRecord($record, $id);

        $this->model->conn->StartTrans();
        if (trim($id)) {
            $return = $this->model->Update($record, "id_kpi_target_realisasi = " . $this->conn->qstr($id));
        } else {
            $return = $this->model->Insert($record);
        }
        $this->model->conn->CompleteTrans();

        if ($return['success']) {
            $this->success($return['success']);
        } else {
            $this->fail("Gagal");
        }
    }

    protected function Record($id = null)
    {
        $param = $this->requestData();
        return array(
            'nilai' => $param['nilai'],
            'id_kpi' => $param['id_kpi'],
            'tahun' => $param['tahun'],
            'bulan' => $param['bulan'],
        );
    }

    protected function Rules()
    {
        return array(
            "nilai" => array(
                'field' => 'nilai',
                'label' => 'Nilai',
                'rules' => "required|numeric|max_length[10]",
            ),
            "id_kpi" => array(
                'field' => 'id_kpi',
                'label' => 'KRI',
                'rules' => "required|numeric|max_length[10]",
            ),
            "tahun" => array(
                'field' => 'tahun',
                'label' => 'Tahun',
                'rules' => "required|numeric|max_length[10]",
            ),
            "bulan" => array(
                'field' => 'bulan',
                'label' => 'Bulan',
                'rules' => "required|max_length[2]",
            ),
        );
    }
}
