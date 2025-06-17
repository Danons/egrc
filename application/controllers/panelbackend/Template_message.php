<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Template_message extends _adminController
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function init()
    {
        parent::init();
        $this->viewlist = "panelbackend/template_messagelist";
        $this->viewdetail = "panelbackend/template_messagedetail";
        $this->template = "panelbackend/main";
        $this->layout = "panelbackend/layout1";

        if ($this->mode == 'add') {
            $this->data['page_title'] = 'Tambah Template Pesan';
            $this->data['edited'] = true;
        } elseif ($this->mode == 'edit') {
            $this->data['page_title'] = 'Edit Template Pesan';
            $this->data['edited'] = true;
        } elseif ($this->mode == 'detail') {
            $this->data['page_title'] = 'Detail Template Pesan';
            $this->data['edited'] = false;
        } else {
            $this->data['page_title'] = 'Daftar Template Pesan';
        }
        $this->load->model("Template_messageModel", "model");
        $this->pk = $this->model->pk;
        $this->data['pk'] = $this->pk;
        $this->plugin_arr = array(
            'tinymce',
        );
    }

    public function Index($page = 0)
    {
        $this->data['header'] = $this->Header();

        $this->data['list'] = $this->_getList($page);

        $this->data['is_userarr'] = $this->conn->GetArray('select is_user,id_message from mt_message_template where deleted_date is null');
        foreach ($this->data['is_userarr'] as $ur) {
            $this->data['isuserarr'][$ur['id_message']] = $ur['is_user'];
        }

        $this->data['page'] = $page;

        $param_paging = array(
            'base_url' => base_url("{$this->page_ctrl}/index"),
            'cur_page' => $page,
            'total_rows' => $this->data['list']['total'],
            'per_page' => $this->limit,
            'first_tag_open' => '<li class="page-item">',
            'first_tag_close' => '</li>',
            'last_tag_open' => '<li class="page-item">',
            'last_tag_close' => '</li>',
            'cur_tag_open' => '<li class="page-item active"><a href="#" class="page-link">',
            'cur_tag_close' => '</a></li>',
            'next_tag_open' => '<li class="page-item">',
            'next_tag_close' => '</li>',
            'prev_tag_open' => '<li class="page-item">',
            'prev_tag_close' => '</li>',
            'num_tag_open' => '<li class="page-item">',
            'num_tag_close' => '</li>',
            'anchor_class' => 'page-link',
            'attributes' => array('class' => 'page-link'),
        );
        $this->load->library('pagination');

        $paging = $this->pagination;

        $paging->initialize($param_paging);

        $this->data['paging'] = $paging->create_links();

        $this->data['limit'] = $this->limit;

        $this->data['limit_arr'] = $this->limit_arr;

        $this->View($this->viewlist);
    }

    protected function Record($id = null)
    {
        return array(
            'msg' => $this->post['msg'],
            'is_user' => $this->post['is_user'],
        );
    }

    protected function Rules()
    {
        return array(
            'msg' => array(
                'field'   => 'msg',
                'rules'   => 'required'
            ),
        );
    }

    protected function Header()
    {
        return array(
            array(
                'name' => 'msg',
                'label' => 'Pesan',
                'width' => "auto"
            ),
            array(
                'name' => 'is_user',
                'label' => 'untuk',
                'width' => "auto"
            ),
        );
    }




    public function Edit($id = null)
    {
        $this->data['arrIs_user'] = array(
            '' => 'template untuk',
            0 => 'admin',
            1 => 'user',
            2 => 'bot',
        );
        $id = urldecode($id);
        if ($this->post['act'] == 'reset') {
            redirect(current_url());
        }

        $this->_beforeDetail($id);

        $this->data['idpk'] = $id;

        $this->data['row'] = $this->model->GetByPk($id);

        if (!$this->data['row'] && $id)
            $this->NoData();

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
                    if ($this->post['is_user'] == 2) {

                        $return = $this->model->Update($record, ' is_user = 2');
                        $id = $return['data'][$this->pk];
                    } else {

                        $return = $this->model->Insert($record);
                        $id = $return['data'][$this->pk];
                    }
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
                    redirect("$this->page_ctrl/detail/$id");
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
}
