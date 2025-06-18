<?php
class Mt_pemeriksaan_jenis_akomodasiModel extends _Model
{
    public $table = 'mt_pemeriksaan_jenis_akomodasi';
    public $pk = 'id_jenis';
    public $label = 'nama_jenis';
    function __construct()
    {
        parent::__construct();
    }
}
