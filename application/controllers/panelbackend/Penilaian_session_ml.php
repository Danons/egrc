<?php
defined('BASEPATH') or exit('No direct script access allowed');

include "Penilaian_session.php";
class Penilaian_session_ml extends Penilaian_session
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function init()
    {
        parent::init();
        $this->data['id_kategori'] = $this->id_kategori = 2;
        $this->data['page_ctrl1'] = "panelbackend/penilaian_ml";
        $this->data['page_title'] = 'Assessment Maturity Level';
    }
}
