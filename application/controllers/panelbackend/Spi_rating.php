<?php
defined("BASEPATH") or exit("No direct script access allowed");

include APPPATH . "core/_adminController.php";
class Spi_rating extends _adminController
{
    public function __construct()
    {
        parent::__construct();
    }
    protected  function init()
    {
        parent::init();
        $this->viewlist = "panelbackend/spi_ratinglist";
        $this->template = "panelbackend/main";
        $this->layout = 'panelbackend/layout1';

        $this->data['page_title'] = '';


        $this->load->model('ConsultingModel', 'model');
        $this->pk = $this->model->pk;
        $this->data['pk'] = $this->pk;
        $this->plugin_arr = array(
            'upload',
        );
    }

    public function Index($page = 0)
    {
        $this->data['header'] = $this->Header();
        $this->data['nobutton'] = true;
        $this->data['list'] = $this->_getList($page);

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

        #get rating spi consulting

        $this->data['jumlah_data'] = $this->conn->GetRow('select count(*) as jumlah_data from public_sys_message where deleted_date is null and rating IS NOT null');

        $get_rating = $this->conn->GetArray('select rating from public_sys_message where deleted_date is null and rating IS NOT null');

        $jumlah_rating = 0;
        foreach ($get_rating as $gr) {
            $jumlah_rating += $gr['rating'];
        }

        $this->data['rating'] = round($jumlah_rating / (int)$this->data['jumlah_data']['jumlah_data'],2);
        if ($this->data['rating'] <= 2) {
            $teksRating = 'tidak puas';
        } elseif ($this->data['rating'] <= 3) {
            $teksRating = 'kurang puas';
        } elseif ($this->data['rating'] <= 4) {
            $teksRating = 'puas';
        } elseif ($this->data['rating'] > 4) {
            $teksRating = 'sangat puas';
        }
        $this->data['teksRating'] = $teksRating;


        #get rating survey per kegiatan
        $getDataKegiatanarr = $this->conn->GetArray('SELECT pq.jenis_jawaban,p.id_pemeriksaan,p.nama,qp.id_quisioner,pq.pertanyaan,pq.nilai,pq.id_quisioner_parent 
        FROM pemeriksaan p
        LEFT JOIN quisioner_pemeriksaan qp ON p.id_pemeriksaan = qp.id_pemeriksaan 
        LEFT JOIN penilaian_quisioner pq ON qp.id_quisioner = pq.id_quisioner where p.deleted_date is null and qp.id_quisioner is not null ORDER BY id_penilaian_quisioner desc');

        foreach ($getDataKegiatanarr as $t) {
            $getDataKegiatan[$t['id_pemeriksaan']][$t['id_quisioner']] = $t;
        }

        $jumlah_nilai_rating_kegiatan = array();
        foreach ($getDataKegiatan as $key1 => $val1) {
            foreach ($val1 as $key2 => $val2) {
                if ($val2['nilai']) {
                    if ($val2['id_quisioner_parent']) {
                        if ($val1[$val2['id_quisioner_parent']]['jenis_jawaban'] == 'yatidak' && $val1[$val2['id_quisioner_parent']]['nilai'] != 1) {
                            $jumlah_nilai_rating_kegiatan[$key1]['nama'] = $val2['nama'];
                            $jumlah_nilai_rating_kegiatan[$key1]['nilai'] += $val2['nilai'];
                            $jumlah_nilai_rating_kegiatan[$key1]['jumlah_data'] += 1;
                        };
                    } else {
                        $jumlah_nilai_rating_kegiatan[$key1]['nama'] = $val2['nama'];
                        $jumlah_nilai_rating_kegiatan[$key1]['nilai'] += $val2['nilai'];
                        $jumlah_nilai_rating_kegiatan[$key1]['jumlah_data'] += 1;
                    }
                }
            }
        }
        $this->data['rating_kegiatan'] = $jumlah_nilai_rating_kegiatan;

        #get rating tahunan
        $getDataTahunarr = $this->conn->GetArray('SELECT pq.jenis_jawaban,qt.tahun,qt.id_quisioner,pq.nilai,pq.pertanyaan,pq.nilai,pq.id_quisioner_parent  FROM quisioner_tahun qt 
        LEFT JOIN penilaian_quisioner pq ON qt.id_quisioner = pq.id_quisioner where pq.deleted_date is null ORDER BY id_penilaian_quisioner desc');

        foreach ($getDataTahunarr as $t) {
            $getDatatahun[$t['tahun']][$t['id_quisioner']] = $t;
        }

        $jumlah_nilai_rating_tahun = array();
        foreach ($getDatatahun as $key1 => $val1) {
            foreach ($val1 as $key2 => $val2) {
                if ($val2['nilai']) {
                    if ($val2['id_quisioner_parent']) {
                        if ($val1[$val2['id_quisioner_parent']]['jenis_jawaban'] == 'yatidak' && $val1[$val2['id_quisioner_parent']]['nilai'] != 1) {
                            $jumlah_nilai_rating_tahun[$key1]['tahun'] = $val2['tahun'];
                            $jumlah_nilai_rating_tahun[$key1]['nilai'] += $val2['nilai'];
                            $jumlah_nilai_rating_tahun[$key1]['jumlah_data'] += 1;
                        };
                    } else {
                        $jumlah_nilai_rating_tahun[$key1]['tahun'] = $val2['tahun'];
                        $jumlah_nilai_rating_tahun[$key1]['nilai'] += $val2['nilai'];
                        $jumlah_nilai_rating_tahun[$key1]['jumlah_data'] += 1;
                    }
                }
            }
        }

        $this->data['rating_tahun'] = $jumlah_nilai_rating_tahun;

        $this->View($this->viewlist);
    }
}
