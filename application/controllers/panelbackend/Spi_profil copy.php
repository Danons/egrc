<?php
defined("BASEPATH") or exit("NO direct script access allowed");

include APPPATH . "core/_adminController.php";
class Spi_profil extends _adminController
{
    public function __construct()
    {
        parent::__construct();
    }
    protected $xss_clean = false;
    protected $escape_html = false;


    protected  function init()
    {
        parent::init();
        $this->viewlist = "panelbackend/contentlist";
        $this->viewdetail = "panelbackend/contentdetail";
        $this->template = "panelbackend/main";
        $this->layout = 'panelbackend/layout_profil_spi';

        if ($this->mode == 'edit') {
            $this->data['page_title'] = 'Edit';
            $this->data['edited'] = true;
        } elseif ($this->mode == 'detail') {
            $this->data['page_title'] = 'Detail';
            $this->data['edited'] = false;
        }
        $this->data['mode'] = $this->mode = 'edit_detail';


        unset($this->access_role['list']);
        unset($this->access_role['index']);
        unset($this->access_role['lst']);

        $this->data['configfile'] = $this->config->item('file_upload_config');
        $this->data['configfile']['allowed_types'] = 'pdf';
        $this->config->set_item("file_upload_config", $this->data['configfile']);


        $this->load->model('ContentModel', 'model');
        $this->pk = $this->model->pk;
        $this->data['pk'] = $this->pk;
        $this->plugin_arr = array(
            'tinymce',
        );
    }
    public function Edit($page = null)
    {

        if ($page == 'spi_achievement') {
            $_SESSION[SESSION_APP][$this->page_ctrl]['filter_tahun'] == $this->data['filter_tahun'];
        }


        // dpr($_SESSION[SESSION_APP][$this->page_ctrl]['filter_tahun'], 1);
        $this->data['page'] = $page;
        // $this->conn->debug=1;
        // $this->conn->debug=1;
        if ($this->post['act'] == 'set_tgl_efektif')
            $_SESSION[SESSION_APP][$this->page_ctrl]['tgl_efektif'] = $this->post['tgl_efektif'];

        $tgl_efektif = date("Y-m-d");
        if ($_SESSION[SESSION_APP][$this->page_ctrl]['tgl_efektif'])
            $tgl_efektif = $_SESSION[SESSION_APP][$this->page_ctrl]['tgl_efektif'];

        $this->data['tgl_efektif'] = $tgl_efektif;

        $id = $this->conn->GetOne("select id_contents from contents 
        where deleted_date is null and page = " . $this->conn->escape($page) . " 
        and " . $this->conn->escape($tgl_efektif) . " between ifnull(tgl_mulai_aktif,'$tgl_efektif') and ifnull(tgl_akhir_aktif,'$tgl_efektif')");


        $id = urldecode($id);
        if ($this->post['act'] == 'reset') {
            redirect(current_url());
        }

        $this->_beforeDetail($id);

        $this->data['idpk'] = $id;

        $this->data['row'] = $this->model->GetByPk($id);
        // dpr($this->data['row'],1);
        if (!$this->data['row'] && $id)
            $this->NoData();
        // echo 'hello';
        // die();
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters("", "");

        if ($this->post && $this->post['act'] <> 'change') {
            if (!$this->data['row'])
                $this->data['row'] = array();

            $record = $this->Record($id);

            $this->data['row'] = array_merge($this->data['row'], $record);
            $this->data['row'] = array_merge($this->data['row'], $this->post);
        }

        $this->_onDetail($id);

        $this->data['rules'] = $this->Rules();

        ## EDIT HERE ##
        if ($this->post['act'] === 'save') {
            $record['page'] = $page;

            $this->_isValid($record, true);

            $this->_beforeEdit($record, $id);

            $this->_setLogRecord($record, $id);

            $this->model->conn->StartTrans();
            if (trim($this->data['row'][$this->pk]) == trim($id) && trim($id)) {

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

                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    echo json_encode(array("success" => true, "data" => array("key" => $this->pk, "val" => $id)));
                    exit();
                } else {
                    SetFlash('suc_msg', $return['success']);
                    redirect("$this->page_ctrl/detail/$page");
                }
            } else {
                $this->data['row'] = array_merge($this->data['row'], $record);
                $this->data['row'] = array_merge($this->data['row'], $this->post);

                $this->_afterEditFailed($id);

                $this->data['err_msg'] = "Data gagal disimpan";
            }
        }

        $this->_afterDetail($id);
        // dpr($this->layout, 1);
        $this->View($this->viewdetail);
    }
    public function Index($page = 0)
    {

        if (!$page) {
            redirect('panelbackend/spi_profil/detail/spi_visi_misi');
            die();
        }
    }

    public function Detail($page = null)
    {
        if (!$page) {
            redirect('panelbackend/spi_profil/detail/spi_visi_misi');
            die();
        }
        // dpr($this->post['tgl_efektif'], 1);

        if ($this->post['act'] == 'set_tgl_efektif')
            $_SESSION[SESSION_APP][$this->page_ctrl]['tgl_efektif'] = $this->post['tgl_efektif'];

        $tgl_efektif = date("Y-m-d");

        // dpr($page, 1);
        if ($page == 'spi_achievement') {
            if ($this->post['act'] == 'set_filter_tahun') {
                $_SESSION[SESSION_APP][$this->page_ctrl]['filter_tahun'] = $this->post['filter_tahun'];
            }
            if (!$_SESSION[SESSION_APP][$this->page_ctrl]['filter_tahun']) {
                list($tahun, $bln, $tgl) = explode("-", $tgl_efektif);
                $_SESSION[SESSION_APP][$this->page_ctrl]['filter_tahun'] = $tahun;
            }
            $filter_tahun = $_SESSION[SESSION_APP][$this->page_ctrl]['filter_tahun'];
            $this->data['filter_tahun'] = $filter_tahun;

            $id = $this->conn->GetOne("select id_contents from contents where deleted_date is null and page = " . $this->conn->escape($page) . " and year(tgl_mulai_aktif) = " . $this->conn->escape($filter_tahun));
            // dpr($id);
        } else {

            if (!$_SESSION[SESSION_APP][$this->page_ctrl]['tgl_efektif'])
                $_SESSION[SESSION_APP][$this->page_ctrl]['tgl_efektif'] = $tgl_efektif;
            if ($_SESSION[SESSION_APP][$this->page_ctrl]['tgl_efektif'])
                $tgl_efektif = $_SESSION[SESSION_APP][$this->page_ctrl]['tgl_efektif'];

            $this->data['tgl_efektif'] = $tgl_efektif;

            $id = $this->conn->GetOne("select id_contents from contents 
            where deleted_date is null and page = " . $this->conn->escape($page) . " 
            and " . $this->conn->escape($tgl_efektif) . " between ifnull(tgl_mulai_aktif,'$tgl_efektif') and ifnull(tgl_akhir_aktif,'$tgl_efektif')");
        }



        $this->data['page'] = $page;

        // dpr($id, 1);
        // $id = $this->conn->GetOne("select id_contents from contents where page = " . $this->conn->escape($page));
        $id = urldecode($id);
        $this->_beforeDetail($id);

        $this->data['row'] = $this->model->GetByPk($id);

        if (!$this->data['row']) {
            // $this->NoData();
            redirect('panelbackend/spi_profil/edit/' . $page);
            die();
        }
        // $this->NoData();

        $this->_onDetail($id);

        $this->_afterDetail($id);

        $this->View($this->viewdetail);
    }

    protected function _afterDetail($id)
    {
        // dpr($this->data['edited'],1);
        $this->data['pk'] = 'page';
        $this->data['page_title'] =  $this->data['row']['title'];
        if ($this->modelfile) {
            if (!$this->data['row']['files']['id'] && $id) {
                $rows = $this->conn->GetArray("select *
				from {$this->modelfile->table}
				where deleted_date is null and {$this->model->pk} = " . $this->conn->escape($id));

                foreach ($rows as $r) {
                    $this->data['row']['files']['id'][] = $r[$this->modelfile->pk];
                    $this->data['row']['files']['name'][] = $r['client_name'];
                }
            }
        }
        // dpr($this->data['page_title'], 1);
        if ($this->data['page'] == "spi_achievement") {
            $this->data['page_title'] .= "&nbsp;&nbsp;&nbsp;" . UI::createTextNumber("filter_tahun", $this->data['filter_tahun'], '', '', true, 'filter-title form-control', "style='width:100px' onchange='goSubmit(\"set_filter_tahun\")'");
        } else {
            $this->data['page_title'] .= "&nbsp;&nbsp;&nbsp;" . UI::createTextBox("tgl_efektif", $this->data['tgl_efektif'], '', '', true, 'datepicker filter-title form-control', "style='width:130px' onchange='goSubmit(\"set_tgl_efektif\")'");
        }
    }
    protected function Record($id = null)
    {
        return array(
            'tgl_mulai_aktif' => $this->post['tgl_mulai_aktif'],
            'tgl_akhir_aktif' => $this->post['tgl_akhir_aktif'],
            'contents' => $this->post['contents'],
            'title' => $this->post['title']
        );
    }

    protected function Rules()
    {
        return array(
            'contents' => array(
                'field'   => 'contents',
                'label'   => 'Content',
                'rules'   => 'required'
            ),
            'title' => array(
                'field'   => 'title',
                'label'   => 'Title',
                'rules'   => 'required'
            ),
        );
    }
}
