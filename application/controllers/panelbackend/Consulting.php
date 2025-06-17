<?php
defined("BASEPATH") or exit("NO direct script access allowed");

include APPPATH . "core/_adminController.php";
class Consulting extends _adminController
{
    public function __construct()
    {
        parent::__construct();
    }


    protected  function init()
    {
        parent::init();
        $this->viewlist = "panelbackend/consultinglist";
        $this->viewdetail = "panelbackend/consultingdetail";
        $this->template = "panelbackend/main";
        $this->layout = 'panelbackend/layout1';
        $this->viewrating = 'pannelbackend/consultingrating';


        // $this->data['page_title'] = "tanya SDM";

        if ($this->mode == 'add') {
            $this->data['page_title'] = 'tambah pesan';
            $this->data['edited'] = true;
        } elseif ($this->mode == 'edit') {
            $this->data['page_title'] = 'edit pesan';
            $this->data['edited'] = true;
        } elseif ($this->mode == 'detail') {
            $this->data['page_title'] = 'Pesan Detail';
            $this->data['edited'] = true;
        } else {
            $this->data['page_title'] = 'Daftar Pesan';
        }

        $this->load->model("Public_sys_message_fileModel", "modelfilem");
        $this->data["configfilem"] = $this->config->item('file_upload_message_config');
        $this->data['configfile'] = $this->config->item('file_upload_config');


        $this->load->model("Template_messageModel", "templatemsg");
        $this->data['arrtemplate'] = $this->templatemsg->GetCombo();
        $this->data['arrtemplate'][''] = 'pilih template';

        // $this->data['configfile']['allowed_types'] = 'pdf';
        // $this->config->set_item("file_upload_config", $this->data['configfile']);

        $this->load->model("Mt_rating_spiModel", "rating_spi");
        $this->data['ratingarr'] = $this->rating_spi->GetCombo();



        $this->load->model("ConsultingModel", "model");
        $this->pk = $this->model->pk;
        $this->data['pk'] = $this->pk;
        $this->plugin_arr = array(
            'tinymce', 'upload', 'select2'
        );
    }

    protected function Header()
    {
        return array(
            array(
                'name' => 'nama_suplier',
                'label' => 'Suplier',
                'width' => "auto"
            ),
            array(
                'name' => 'nama',
                'label' => 'Nama User',
                'width' => "auto"
            ),
            array(
                'name' => 'msg',
                'label' => 'Pesan',
                'width' => "auto"
            ),
            array(
                'name' => 'time',
                'label' => 'Waktu',
                'width' => "auto"
            ),
        );
    }

    function open_filem($id = null, $nameid = null)
    {
        $this->_openFiles($id, $nameid);
    }

    protected function _openFiles($id = null, $nameid = null)
    {
        $row = $this->modelfilem->GetByPk($id);
        if ($row) {
            $full_path = $this->data['configfile']['upload_path'] . $row['file_name'];
            $str = file_get_contents($full_path);
            header("Content-Type: {$row['file_type']}");
            header("Content-Disposition: inline; filename=\"{$row['client_name']}\"");
            header('Content-length: ' . strlen($str));
            echo $str;
            die();
        } else {
            $this->Error404();
        }
    }

    public function Index($page = 0)
    {

        // dpr($_SESSION, 1);
        $this->data['totalLanjutan'] = 0;
        if ($this->access_role['view_all']) {
            $this->data['rows'] = $this->conn->GetArray("select * from public_sys_message where deleted_date is null and id_message_parent = 0 order by id_message desc");
        } else {
            $this->data['rows'] = $this->conn->GetArray("select * from public_sys_message where deleted_date is null and id_message_parent = 0 and id_user =  " . $this->conn->escape($_SESSION["SESSION_APP_EGRC"]["user_id"]) . " order by id_message desc");
        }
        if ($this->data['rows']) {
            foreach ($this->data['rows'] as $r) {

                $this->data['getArrMsg'] = $this->conn->GetArray("select * from public_sys_message where deleted_date and id_message_parent = " . $this->conn->escape($r['id_message']) . " and is_user <> 2");
                if (!$this->data['getArrMsg']) {
                    if ($r['id_message'] != $_SESSION['SESSION_APP_EGRC']['user_id']) {
                        if (time() - strtotime($r['time']) >  60 * 60 * 24) {
                            $this->data['totallebih24jam'][$r['id_message']] = $r['id_user'];
                        }
                        $this->data['arrMsgBelumBalas'][$r['id_message']] = $r['topik'];
                    }
                } else {
                    $this->data['arrLanjutan'][$r['id_message']] = $this->conn->GetArray("select MAX(id_message) AS id_terakhir  from public_sys_message where deleted_date is null and id_message_parent = " . $this->conn->escape($r['id_message']) .  " and is_user <> 2");
                }
            }


            if ($this->data['arrLanjutan']) {
                foreach ($this->data['arrLanjutan'] as $al => $val) {
                    $this->data['getMesaggeAkhir'][$al] = $this->conn->GetRow('select * from public_sys_message where deleted_date is null and id_message = ' . $this->conn->escape($val[0]['id_terakhir']));

                    if ($this->data['getMesaggeAkhir'][$al]['id_user'] != $_SESSION['SESSION_APP_EGRC']['user_id']) {
                        $this->data['resultLanjutan'][$al] = $this->data['getMesaggeAkhir'][$al];
                    }

                    $this->data['test24jam'][$al] = time() - strtotime($this->data['getMessageAkhir'][$al]['time']);
                    if (time() - strtotime($this->data['getMesaggeAkhir'][$al]['time']) >  60 * 60 * 24) {
                        $this->data['totallebih24jam'][$al] = $this->data['getMesaggeAkhir'][$al]['id_user'];
                    }
                }
            }
        }

        if($this->access('view_all')){
            $this->data['totalopenmsg'] = $this->conn->GetArray("select * from public_sys_message where deleted_date is null and id_message_parent = 0 and status = 1");
            $this->data['totalclosemsg'] = $this->conn->GetArray("select * from public_sys_message where deleted_date is null and id_message_parent = 0 and status = 2");
        }else{
            $this->data['totalopenmsg'] = $this->conn->GetArray("select * from public_sys_message where deleted_date is null and id_message_parent = 0 and status = 1 and id_user = " . $this->conn->escape($_SESSION[SESSION_APP]['id_user']));
            $this->data['totalopenmsg'] = $this->conn->GetArray("select * from public_sys_message where deleted_date is null and id_message_parent = 0 and status = 1 and id_user = " . $this->conn->escape($_SESSION[SESSION_APP]['id_user']));
        }

        $this->data['header'] = $this->Header();

        $this->data['list']['rows'] = $this->data['rows'];
        $this->data['list']['total'] = count($this->data['rows']);

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

    function Detail($id_message = null)
    {
        // dpr($_SESSION['SESSION_APP_EGRC']['user_id'], 1);
        unset($this->access_role['add']);
        $msgtopik = $this->conn->GetRow("select * from public_sys_message where deleted_date is null and id_message =" . $this->conn->escape($id_message));


        // rating
        // && $msgtopik['rating'] == null
        if ($msgtopik['status'] == 2 && $msgtopik['rating'] == null  && $msgtopik['id_user'] == $_SESSION['SESSION_APP_EGRC']['user_id']) {
            $this->data['test'] = true;

            if ($this->post['rating']) {
                $this->conn->goUpdate("public_sys_message", array('rating' => $this->post['rating']), "id_message = " . $this->conn->escape($id_message));
                $this->conn->goUpdate("spi_rekap_consulting", array('rating' => $this->post['rating']), "id_msg = " . $this->conn->escape($id_message));
            }
        }

        $recordStatus = array(
            'status' => (int)$this->post['status'],
        );


        if (!$this->access_role['view_all']) {
            $getTemplate = $this->conn->GetArray('select id_message,msg from mt_message_template where deleted_date is null and is_user = 1');
        } else {
            $getTemplate = $this->conn->GetArray('select id_message,msg from mt_message_template where deleted_date is null and is_user = 0');
        }
        $this->data['arrTemplate'][''] = 'pilih template';
        foreach ($getTemplate as $gt) {
            $this->data['arrTemplate'][$gt['id_message']] = $gt['msg'];
        }

        if ($this->post['status']) {
            $this->model->update($recordStatus, $this->model->pk . " = " . $id_message);

            $getFirstMsg = $this->conn->GetRow("select rating,status,nama,jabatan, cast(time as date) as tanggal, DATE_FORMAT(TIME, '%H:%i') AS waktu_mulai,msg from public_sys_message where deleted_date is null and id_Message = " . $id_message);
            // dpr($statusmsg, 1);
            if ($getFirstMsg['status'] == 2) {
                $getPendapatSPI = $this->conn->GetRow("SELECT msg,nama,DATE_FORMAT(TIME, '%H:%i') AS waktu_selesai FROM public_sys_message WHERE id_message_parent = " . $id_message . " AND is_user = 0 and deleted_date is null ORDER BY id_message  DESC LIMIT 1");
                $record = array(
                    "jabatan" => $getFirstMsg['jabatan'],
                    "tanggal" => $getFirstMsg['tanggal'],
                    "waktu_mulai" => $getFirstMsg['waktu_mulai'],
                    "tanggal" => $getFirstMsg['tanggal'],
                    "rating" => $getFirstMsg['rating'],
                    "contact_person" => $getFirstMsg['nama'],
                    "uraian_layanan" => $getFirstMsg['msg'],
                    "waktu_selesai" => $getPendapatSPI['waktu_selesai'],
                    "pengawas" => $getPendapatSPI['nama'],
                    "pendapat_spi" => $getPendapatSPI['msg'],
                    "id_msg" => $id_message,
                );
                $ret = $this->conn->goInsert("spi_rekap_consulting", $record);
            } elseif ($getFirstMsg['status'] == 1) {
                $cek = $this->conn->GetRow("select * from spi_rekap_consulting where deleted_date is null and id_msg = " . $id_message);
                if ($cek) {
                    $sql = $this->conn->Execute("update spi_rekap_consulting set deleted_date = now where id_msg = " . $id_message);
                }
            }
        } else {
            $this->post['status'] = $msgtopik['status'];
        }

        if ($this->post['templatemsg']) {
            $this->data['getTemplateMsg'] = $this->conn->GetRow('select msg from mt_message_template where deleted_date is null and id_message = ' . $this->conn->escape($this->post['templatemsg']));
        }


        $this->access_role['save'] = 1;
        $this->access_role['batal'] = 1;
        $this->data['rows'] = $this->conn->GetArray("SELECT * FROM public_sys_message WHERE deleted_date is null and id_Message = " . $this->conn->escape($id_message) .
            " union
        SELECT * FROM public_sys_message WHERE deleted_date is null and id_Message_parent = " . $this->conn->escape($id_message) .
            "order by id_message");

        foreach ($this->data['rows'] as $r) {
            $this->data['dataMsg'] = $this->conn->GetArray("select * from public_sys_message where deleted_date is null and id_user = " . $this->conn->escape($r['id_user']));
            foreach ($this->data['dataMsg'] as $dm) {
                $this->data['dataMsgFile'][$dm['id_message']] = $this->conn->GetArray("select id_message_files,client_name from public_sys_message_files where deleted_date is null and id_message = " . $dm['id_message']);
            }
        }
        $this->conn->goUpdate("public_sys_message", array('is_read' => 1), "id_message = " . $this->conn->escape($id_message));

        if ($this->data['rows'][count($this->data['rows']) - 1]['is_user']) {
            $this->data['menu'] .= '<button type="button" class="btn waves-effect  btn-sm btn-default" onclick="goSubmit(\'unread\')"><span class="glyphicon glyphicon-eye-close"></span> Unread</button>';

            if ($this->post['act'] == "unread") {
                $this->conn->goUpdate("public_sys_message", array('is_read' => 0), "id_suplier = " . $this->conn->escape($id_message));
                redirect(site_url("panelbackend/messages"));
            }
        }

        if ($this->post['act'] == 'save') {

            $rules = array();

            $rules[] = array(
                'field'    => 'msg',
                'label'    => 'Pesan',
                'rules'    => 'required',
            );
            // dpr($this->post, 1);
            $this->load->library('form_validation');
            $this->form_validation->set_rules($rules);

            $error_msg = '';

            if ($this->form_validation->run() == FALSE)
                $error_msg .= validation_errors();

            if ($error_msg) {
                if (!$this->data['row'])
                    $this->data['row'] = array();

                $this->data['row'] = $this->post;
                $this->data['err_msg'] = $error_msg;
            } else {
                $username = $this->data['dataMsg']['username'];
                $id_user = $_SESSION['SESSION_APP_EGRC']['user_id'];
                $arrname = $this->conn->GetRow('select name from public_sys_user where deleted_date is null and user_id = ' . $this->conn->escape($id_user));
                $name = $arrname['name'];
                $topik = $this->conn->GetRow('select topik from public_sys_message where deleted_date is null and id_message = ' . $this->conn->escape($id_message));
                $ret = $this->insertMsg($this->post['msg'], $topik['topik'], $id_user, $name, $id_message, 'testingemailal55@gmail.com');

                if ($ret) {
                    SetFlash('suc_msg', "Pesan terkirim");
                    redirect(current_url());
                }
            }
        }

        $this->View($this->viewdetail);
    }

    function Add()
    {
        $this->access_role['save'] = 1;
        $this->access_role['batal'] = 1;
        // $this->data['userArr'] = $this->conn->GetList("SELECT user_id as idkey,name AS val FROM public_sys_user");
        // unset($this->data['userArr']['']);
        // dpr($_SESSION);
        // die();
        $this->post['id_user'] = array($this->data['user_id']);

        if ($this->post['act'] == 'save') {

            $rules = array();

            $rules[] = array(
                'field'    => 'msg',
                'label'    => 'Pesan',
                'rules'    => 'required',
            );

            $this->load->library('form_validation');
            $this->form_validation->set_rules($rules);

            $error_msg = '';

            if ($this->form_validation->run() == FALSE)
                $error_msg .= validation_errors();

            if ($error_msg) {
                if (!$this->data['row'])
                    $this->data['row'] = array();

                $this->data['row'] = $this->post;
                $this->data['err_msg'] = $error_msg;
            } else {

                $id_user = $_SESSION['SESSION_APP_EGRC']['user_id'];
                $name = $this->conn->GetOne("select name from public_sys_user where deleted_date is null and user_id = " . $this->conn->escape($id_user));
                $username = $this->conn->GetOne("select username from public_sys_user where deleted_date is null and user_id = " . $this->conn->escape($id_user));
                $ret = $this->insertMsg($this->post['msg'], $this->post['topik'], $id_user, $name);



                if ($ret) {
                    $getnewmsg = $this->conn->GetRow('SELECT topik,id_message FROM public_sys_message WHERE deleted_date is null and id_message = (SELECT MAX(id_message) FROM public_sys_message)
                ');
                    if ($getnewmsg) {
                        $getTemplateBot = $this->conn->GetRow('select msg from mt_message_template where deleted_date is null and is_user = 2');
                        if (!$this->access_role['view_all']) {
                            $msgbot = $this->conn->goInsert(
                                "public_sys_message",
                                array(
                                    "msg" => $getTemplateBot['msg'],
                                    "id_user" => 0,
                                    "topik" => $getnewmsg['topik'],
                                    "status" => 0,
                                    "is_aktif" => 1,
                                    "is_user" => 2,
                                    'nama' => 'bot',
                                    'id_message_parent' => $getnewmsg['id_message'],
                                    "jabatan" => 'bot',
                                    "id_jabatan" => '0',
                                )
                            );
                        }
                        SetFlash('suc_msg', "Pesan terkirim");
                        // dpr(base_url("panelbackend/consulting/" . $getnewmsg['id_message']), 1);
                        redirect(base_url("panelbackend/consulting/detail/" . $getnewmsg['id_message']));
                    }
                }
            }
        }
        $this->View($this->viewdetail);
    }



    protected function Rules()
    {
        return array(
            'nama' => array(
                'field'   => 'nama',
                'label'   => 'Kategori',
                'rules'   => ''
            ),
        );
    }


    protected function _updateFilesm($record = array(), $id = null)
    {
        return $this->modelfilem->Update($record, $this->modelfilem->pk . "=" . $this->conn->escape($id));
    }
    function upload_filem($id = null)
    {
        $jenis_file = key($_FILES);

        $name = $_FILES[$jenis_file]['name'];

        $this->data['configfilem']['file_name'] = $jenis_file . time() . $name;
        // dpr($this->data['configfilem']);

        $this->load->library('upload', $this->data['configfilem']);

        if (!$this->upload->do_upload($jenis_file)) {
            $return = array('error' => "File $name gagal upload, " . strtolower(str_replace(array("<p>", "</p>"), "", $this->upload->display_errors())));
        } else {
            $upload_data = $this->upload->data();

            $record = array();
            $record['client_name'] = $upload_data['client_name'];
            $record['file_name'] = $upload_data['file_name'];
            $record['file_type'] = $upload_data['file_type'];
            $record['file_size'] = $upload_data['file_size'];
            $record['jenis_file'] = str_replace("upload", "", $jenis_file);
            $ret = $this->modelfilem->Insert($record);
            if ($ret['success']) {
                $return = array('file' => array("id" => $ret['data'][$this->modelfilem->pk], "name" => $upload_data['client_name']));
            } else {
                unlink($upload_data['full_path']);
                $return = array('errors' => "File $name gagal upload");
            }
        }

        echo json_encode($return);
    }

    function delete_filem($id = null)
    {

        $row = $this->modelfilem->GetByPk($id);

        if (!$row)
            $this->Error404();

        $file_name = $row['file_name'];

        $return = $this->modelfilem->Delete($this->modelfilem->pk . " = " . $this->conn->escape($id));

        if ($return) {
            $full_path = $this->data['configfile']['upload_path'] . $file_name;
            unlink($full_path);

            $return = array("success" => true);
        } else {
            $return = array("error" => "File " . $row['client_name'] . " gagal dihapus");
        }

        echo json_encode($return);
    }

    protected function insertMsg($msg, $topik, $id_user, $name, $id_message_parent = 0, $title = 'Notification')
    {
        // // open_filem;
        // if ($this->post['file_messages']) {
        //     $msg .= "<br/>";
        //     foreach ($this->post['file_messages']['id'] as $k => $id_message_files) {

        //         $msg .= "<br/><a href='" . site_url("consulting/open_file/" . $id_message_files) . "' class='bg-warning' target='_BLANK'>" . $this->post['file_messages']['name'][$k] . "</a>";
        //     }
        // }

        // $ret = $this->send_email($username, $msg, $title);
        // $ret = true;
        // // // print_r($ret);exit();
        $this->data['id_jabatan_user'] = $this->conn->GetRow('select * from public_sys_user where deleted_date is null and user_id = ' . $this->conn->escape($id_user));
        $this->data['nama_jabatan_user'] = $this->conn->GetRow('select nama from mt_sdm_jabatan where deleted_date is null and id_jabatan = ' . $this->conn->escape($this->data['id_jabatan_user']['id_jabatan']));
        if (!$this->access_role['view_all']) {
            $is_user = 1;
        } else {
            $is_user = 0;
        }

        if (true) {
            $ret = $this->conn->goInsert(
                "public_sys_message",
                array(
                    "msg" => $msg,
                    "id_user" => $id_user,
                    "topik" => $topik,
                    "status" => 1,
                    "is_aktif" => 1,
                    "is_user" => $is_user,
                    'nama' => $name,
                    'id_message_parent' => $id_message_parent,
                    "jabatan" => $this->data['nama_jabatan_user']['nama'],
                    "id_jabatan" => $this->data['id_jabatan_user']['id_jabatan']
                )
            );

            $id_message = $this->conn->GetOne("select max(id_message) from public_sys_message where deleted_date is null and id_user = " . $this->conn->escape($id_user));
            if ($this->post['file_messages'])
                foreach ($this->post['file_messages']['id'] as $k => $id_msg_files) {
                    $this->_updateFilesm(array("id_message" => $id_message), $id_msg_files);
                }
        }
        //print_r($ret);exit();
        return $ret;
    }
}
