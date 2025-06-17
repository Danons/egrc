<?php
defined('BASEPATH') or exit('No direct script access allowed');

include "Penilaian_session.php";
class Penilaian_session_gcg extends Penilaian_session
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function init()
    {
        parent::init();
        $this->data['jenis_assessment_gcg'] = $_SESSION[SESSION_APP][$this->page_ctrl]['jenis_assessment_gcg'];
        $this->data['id_kategori'] = $this->id_kategori = 1;
        $this->data['page_ctrl1'] = "panelbackend/penilaian_gcg";
        // $this->data['page_title'] = 'Assessment GCG';
    }

    public function Index($jenis_assessment_gcg = null, $page = 0)
    {
        $this->data['header'] = $this->Header();
        $this->_beforeDetail($jenis_assessment_gcg);

        if ($jenis_assessment_gcg == 1) {
            $this->data['page_title'] = 'Diagnostik Assessment';
        } elseif ($jenis_assessment_gcg == 2) {
            $this->data['page_title'] = 'Self Assessment';
        } elseif ($jenis_assessment_gcg == 3) {
            $this->data['page_title'] = 'Assessment';
        }


        $_SESSION[SESSION_APP][$this->page_ctrl]['jenis_assessment_gcg'] = $jenis_assessment_gcg;
        $this->_setFilter("id_kategori = " . $this->conn->escape($this->id_kategori) . " and jenis_assessment_gcg = " . $this->conn->escape($jenis_assessment_gcg));
        $this->data['list'] = $this->_getList($page);

        $this->data['page'] = $page;

        // dpr($this->data['jenis_assessment_gcg']);
        // dpr($_SESSION[SESSION_APP], 1);

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
    protected function _beforeDetail($jenis_assessment_gcg = null)
	{
		// $this->id_kategori = $id_kategori;
		// $this->data['jenis_'] = $id_kategori;
		$this->data['add_param'] .= $jenis_assessment_gcg;
		// $this->data['kriteriaarr'] = ['' => ''] + $this->mtkriteria->GetCombo($this->id_kategori, 'd');
	}

    public function Edit($jenis_assessment_gcg= null,$id = null)
    {

        $id = urldecode($id);
        if ($this->post['act'] == 'reset') {
            redirect(current_url());
        }

        $this->_beforeDetail($jenis_assessment_gcg);

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
                    redirect("$this->page_ctrl/index/" . $this->data['jenis_assessment_gcg']);
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
    public function Add($jenis_assessment_gcg= null)
	{
		$this->Edit($jenis_assessment_gcg);
	}

    public function Delete($jenis_assessment_gcg = null,$id = null)
	{

		$id = urldecode($id);
		$this->model->conn->StartTrans();

		$this->_beforeDetail($id);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$return = $this->_beforeDelete($id);

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
			redirect("$this->page_ctrl");
		} else {
			SetFlash('err_msg', "Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id");
		}
	}

    public function Detail($jenis_assessment_gcg = null ,$id = null)
	{

		$id = urldecode($id);
		$this->_beforeDetail($id);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$this->_onDetail($id);

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}
}
