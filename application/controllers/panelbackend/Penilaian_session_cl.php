<?php
defined('BASEPATH') or exit('No direct script access allowed');

include "Penilaian_session.php";
class Penilaian_session_cl extends Penilaian_session
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function init()
    {
        parent::init();
        $this->data['id_kategori'] = $this->id_kategori = 3;
        $this->data['page_ctrl1'] = "panelbackend/penilaian_cl";
        $this->data['page_title'] = 'Assessment Capabilty Level';
    }
}
