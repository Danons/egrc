<?php
defined('BASEPATH') or exit('No direct script access allowed');

include "Dokumen.php";
class Dokumen_cl extends Dokumen
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function init()
    {
        $this->data['id_kategori'] = $this->id_kategori = 3;
        parent::init();
    }
}
