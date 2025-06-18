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
        } elseif ($this->mode == 'add') {
            $this->data['page_title'] = 'Edit';
            $this->data['edited'] = true;
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
        $this->data['page'] = $page;
        if ($page)
            $this->data['periodearr'] = $this->conn->GetList("select id_contents as idkey, tgl_mulai_aktif val from contents where deleted_date is null and page = " . $this->conn->escape($page));
        if ($this->post['act'] == 'set_periode') {
            $_SESSION[SESSION_APP][$this->page_ctrl]['periode'] = $this->post['periode'];
            redirect(current_url());
        }

        // dpr(array_key_last($this->data['periodearr']),1);
        $periode = array_key_last($this->data['periodearr']);
        if ($_SESSION[SESSION_APP][$this->page_ctrl]['periode'])
            $periode = $_SESSION[SESSION_APP][$this->page_ctrl]['periode'];

        $this->data['periode'] = $periode;
        $id = $periode;
        /*
		$id = $this->conn->GetOne("select id_contents from contents 
		where page = " . $this->conn->escape($page) . " 
		and " . $this->conn->escape($periode) . " between ifnull(tgl_mulai_aktif,'$periode') and ifnull(tgl_akhir_aktif,'$periode')");
*/
        // dpr($id,1);
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

        $this->View($this->viewdetail);
    }

    public function Add($page = null)
    {
        $this->data['page'] = $page;
        if ($this->post && $this->post['act'] <> 'change') {
            if (!$this->data['row'])
                $this->data['row'] = array();

            $record = $this->Record($id);

            $this->data['row'] = array_merge($this->data['row'], $record);
            $this->data['row'] = array_merge($this->data['row'], $this->post);
        }

        ## EDIT HERE ##
        if ($this->post['act'] === 'save') {
            $record['page'] = $page;
            $record['tgl_mulai_aktif'] = date('Y-m-d');

            $this->_isValid($record, true);

            $this->_beforeEdit($record, $id);

            $this->_setLogRecord($record, $id);

            $this->model->conn->StartTrans();
            // $this->conn->debug = 1;
            $return = $this->_beforeInsert($record);

            if ($return) {
                $last_id = $this->conn->GetOne("select max(id_contents) from contents where deleted_date is null and page = " . $this->conn->escape($page));
                // $return = $this->model->Update($record, "$this->pk = " . $this->conn->qstr($id));
                $return = $this->model->update(
                    ['tgl_akhir_aktif' => $record['tgl_mulai_aktif']],
                    "$this->pk = " . $this->conn->qstr($last_id)
                );
            }
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


            // die;
            if ($return['success']) {

                $this->model->conn->CompleteTrans();
                $this->_afterEditSucceed($id);
                $_SESSION[SESSION_APP][$this->page_ctrl]['periode'] = $id;
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

        $this->data['pk'] = 'page';
        switch ($page) {
            case 'spi_visi_misi':
                $this->data['page_title'] = "Visi & Misi, Core Values, Code of Ethics";
                break;
            case 'spi_piagam_audit_internal':
                $this->data['page_title'] = "Piagam Audit Internal";
                break;
            case 'spi_struktur_organisasi':
                $this->data['page_title'] = "Struktur Organisasi dan Fungsi";
                break;
            case 'spi_achievement':
                $this->data['page_title'] = "Achievement";
                break;
            default:
                $this->data['page_title'] = "Tambah";
                break;
        }

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
        $this->data['page'] = $page;
        if ($page)
            $this->data['periodearr'] = $this->conn->GetList("select id_contents as idkey, tgl_mulai_aktif val from contents where deleted_date is null and page = " . $this->conn->escape($page));
        if ($this->post['act'] == 'set_periode')
            $_SESSION[SESSION_APP][$this->page_ctrl]['periode'] = $this->post['periode'];

        // dpr($this->post['periode']);
        if (!$_SESSION[SESSION_APP][$this->page_ctrl]['periode'])
            $periode = array_key_last($this->data['periodearr']);
        // dpr($_SESSION[SESSION_APP][$this->page_ctrl]['periode'], 1);
        if ($_SESSION[SESSION_APP][$this->page_ctrl]['periode']) {
            $cek = $this->conn->GetOne("select page from contents where deleted_date is null and id_contents = " . $this->conn->escape($_SESSION[SESSION_APP][$this->page_ctrl]['periode']));
            if ($cek == $page)
                $periode = $_SESSION[SESSION_APP][$this->page_ctrl]['periode'];
            else
                $periode = array_key_last($this->data['periodearr']);
            // dpr($periode, 1);
        }

        $this->data['periode'] = $periode;
        $id = $periode;
        // dpr($periode, 1);
        /*
		$id = $this->conn->GetOne("select id_contents from contents 
		where page = " . $this->conn->escape($page) . " 
		and " . $this->conn->escape($periode) . " between ifnull(tgl_mulai_aktif,'$periode') and ifnull(tgl_akhir_aktif,'$periode')");
*/
        // dpr($id,1);
        $id = urldecode($id);
        $this->_beforeDetail($id);

        $this->data['row'] = $this->model->GetByPk($id);

        if (!$this->data['row']) {
            redirect('panelbackend/content/edit/' . $page);
            die();
        }
        // $this->NoData();

        $this->_onDetail($id);
        // dpr($this->data['row'], 1);

        $this->_afterDetail($id, $page);

        $this->View($this->viewdetail);
    }



    protected function _afterDetail($id, $page = null)
    {
        // dpr($page, 1);
        $this->data['pk'] = 'page';
        // $this->data['page_title'] =  $this->data['row']['title'];
        switch ($page) {
            case 'spi_visi_misi':
                $this->data['page_title'] = "Visi & Misi, Core Values, Code of Ethics";
                break;
            case 'spi_piagam_audit_internal':
                $this->data['page_title'] = "Piagam Audit Internal";
                break;
            case 'spi_struktur_organisasi':
                $this->data['page_title'] = "Struktur Organisasi dan Tugas Fungsi";
                break;
            case 'spi_achievement':
                $this->data['page_title'] = "Achievement";
                break;
            default:
                $this->data['page_title'] = "Tambah";
                break;
        }
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

        // $this->data['page_title'] .= UI::createTextBox("tgl_efektif", $this->data['tgl_efektif'], '', '', true, 'datepicker filter-title form-control', "style='width:130px' onchange='goSubmit(\"set_tgl_efektif\")'");
        $this->data['page_title'] .= UI::createSelect("periode", $this->data['periodearr'], $id, true, '', "style='width:230px' onchange='goSubmit(\"set_periode\")'");

        if ($this->access_role['add'] && $this->data['edited'] == false)
            $this->data['addbutton'] = '<button type="button" class="btn btn-sm btn-primary" onclick="goAdd(\'' . $page . '\',\'' . base_url() . '\')">Tambah Baru</button>
		<script>
		function goAdd(page,base_url){
			window.location = base_url+"panelbackend/spi_profil/add/"+page;
		}
		</script>';
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
