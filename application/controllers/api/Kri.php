<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Kri extends _adminController
{
    public $page_escape = array('api/kri');

    public function __construct()
    {
    }

    public function one()
    {
        $this->isPrivateMethod("GET");
        $this->load->model("Risk_risikoModel", "model");
        $data = $this->periodetw->GetCombo();

        unset($data['']);
        $this->success($data);
    }


    public function Index($page = 0)
    {
        $this->isPrivateMethod("GET");
        $param = $this->requestData();
        $this->load->model("Risk_risikoModel", "model");

        $nama_risiko = $param['nama_risiko'];
        $nama_kri = $param['nama_kri'];

        $filter = "";

        if ($nama_risiko) {
            $filter .= " and lower(nama_risiko) like '%" . strtolower($nama_risiko) . "%'";
        }

        if ($nama_kri) {
            $filter .= " and lower(nama) like '%" . strtolower($nama_kri) . "%'";
        }

        if (!$this->Access("view_all"))
            $id_unit = $_SESSION[SESSION_APP]['id_unit'];
        if ($param['id_unit'])
            $id_unit = $param['id_unit'];

        if ($id_unit)
            $filter .= " and rs.id_unit = " . $this->conn->escape($id_unit);

        $arr_params = array(
            'page' => 0,
            'limit' => $param['limit'] ? $param['limit'] : 50,
            'where' => '',
            'tahun' => $param['limit'] ? $param['limit'] : date("Y"),
            'order' => '',
            'filter' => $filter
        );

        $list = $this->model->SelectGridRisk(
            $arr_params
        );

        foreach ($list['rows'] as &$r) {
            $r['nama_kri'] = $r['nama'];
            unset($r['nama']);
            unset($r['id_risiko1']);
            unset($r['id_scorecard']);
            unset($r['id_tingkat']);
            unset($r['id_kemungkinan']);
            unset($r['id_dampak']);
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

        $this->load->model("Risk_kri_hasilModel", "model");

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters("", "");

        $record = $this->Record();
        $id_kri = $record['id_kri'];
        $tahun = $record['tahun'];

        $idt = $this->conn->GetOne("select id_kri_hasil from risk_kri_hasil 
            where deleted_date is null and bulan = " . $this->conn->escape($record['bulan']) . " 
            and id_kri = " . $this->conn->escape($id_kri) . " 
            and tahun = " . $this->conn->escape($tahun));

        if ($idt)
            $id = $idt;

        $this->_isValid($record, true);

        $this->_setLogRecord($record, $id);

        $this->model->conn->StartTrans();
        if (trim($id)) {
            $return = $this->model->Update($record, "id_kri_hasil = " . $this->conn->qstr($id));
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
            'id_kri' => $param['id_kri'],
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
            "id_kri" => array(
                'field' => 'id_kri',
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
