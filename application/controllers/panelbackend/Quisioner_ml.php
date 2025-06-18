<?php
defined('BASEPATH') or exit('No direct script access allowed');

include "Quisioner.php";
class Quisioner_ml extends Quisioner
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function init()
    {
        $this->data['id_kategori'] = $this->id_kategori = 2;
        parent::init();
    }
}
