<?php 
class Pemeriksaan_dokumen_eksternalModel extends _Model{
    public $table = 'pemeriksaan_dokumen_eksternal';
    public $pk = 'id_dokumen_aksternal';
    function __construct(){
        parent::__construct();
    }
}