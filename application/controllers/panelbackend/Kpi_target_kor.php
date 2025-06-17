<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Kpi_target_kor extends _adminController
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function init()
    {
        parent::init();

        $this->viewlist = "panelbackend/kpi_target_korlist";
        $this->viewdetail = "panelbackend/kpi_targetkordetail";
        $this->template = "panelbackend/main";
        $this->layout = "panelbackend/layout1";

        if ($this->mode == 'add') {
            $this->data['page_title'] = 'Tambah Target KPI';
            $this->data['edited'] = true;
        } elseif ($this->mode == 'edit') {
            $this->data['page_title'] = 'Edit Target KPI';
            $this->data['edited'] = true;
        } elseif ($this->mode == 'detail') {
            $this->data['page_title'] = 'Detail Target KPI';
            $this->data['edited'] = false;
        } else {
            $this->data['page_title'] = 'KPI Korporat';
        }

        $this->load->model("Kpi_targetModel", "model");

        $this->load->model("KpiModel", "kpi");
        $this->data['kpiarr'] = $this->kpi->GetCombo();

        $this->load->model("Mt_sdm_dit_bidModel", "dept");
        $this->data['deptarr'] = $this->dept->GetCombo();

        $this->load->model("Mt_sdm_unitModel", "unit");
        $this->data['unitarr'] = $this->unit->GetCombo();
        $this->data['unitarr'][''] = 'Pilih Unit';

        $tahun = array(null => 'Pilih Tahun', date('Y') => date('Y'), date('Y') - 1 => date('Y') - 1, date('Y') - 2 => date('Y') - 2);

        $this->data['tahunarr'] = $tahun;
        $this->data['jenisrealisasiarr'] = array('akumulatif' => 'Akumulatif', 'progresif' => 'Progresif', 'average' => 'Average');

        $jenis = array(
            null => 'Pilih Jenis',
            'Unit' => 'Unit',
            'Direktorat' => 'Direktorat',
            'Korporat' => 'Korporat',
        );
        $this->data['jenisarr'] = $jenis;

        $this->pk = $this->model->pk;
        $this->data['pk'] = $this->pk;
        $this->plugin_arr = array(
            'select2', 'treetable', 'tinymce', 'upload'
        );

        $this->access_role['list_print'] = true;
    }


    protected function _getList($page = 0)
    {
        $this->_resetList();

        $this->arrNoquote = $this->model->arrNoquote;

        $filter = $this->_getFilter();
        $param = array(
            'page' => $page,
            'limit' => $this->_limit(),
            'order' => $this->_order(),
            'nama' => $_SESSION[SESSION_APP][$this->page_ctrl]['list_search']['nama'],
            'tahun' => $_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']['tahun'],
        );

        if ($this->post['act'] && $this->post['act'] <> 'save' && $this->post['act'] <> 'set_value') {

            if ($this->data['add_param']) {
                $add_param = '/' . $this->data['add_param'];
            }
            redirect(str_replace(strstr(current_url(), "/index$add_param/$page"), "/index{$add_param}", current_url()));
        }
        // $this->conn->debug = 1;
        $respon = $this->model->SelectGridKorporat(
            $param
        );
        // dpr($respon, 1);

        return $respon;
    }

    public function _save_kpi()
    {
        #save kpi
        $dt_kp = true;
        if ($this->post['act'] == 'save') {
            $cek = $this->conn->GetArray("select * from kpi where deleted_date is null and id_kpi  = " . $this->conn->escape($this->post['nama']));

            # jika kpi belum ada
            if (!$cek) {
                // $this->conn->debug = 1;
                $dt_kp = false;

                if (!(int)$this->post['id_parent']) {
                    $id_parent = null;
                } else {
                    $id_parent = $this->post['id_parent'];
                }
                $record = array(
                    'id_parent' => $id_parent,
                    'kode' => $this->post['kode'],
                    'urutan' => $this->post['urutan'],
                    'nama' => $this->post['nama'],
                );

                $return = $this->modelkpi->Insert($record);
                $id = $return['data'][$this->pk];

                #save kpi config (save tahun)
                if ($return['data']['id_kpi']) {

                    $id_kpi = $return['data']['id_kpi'];

                    $tahun1 = $this->post['tahun'];
                    $record = array(
                        'tahun' => $this->post['tahun'],
                        'id_kpi' => $id_kpi,
                    );

                    $return1 = $this->modelkpiconfig->Insert($record);

                    if ($return1['success']) {

                        $tahun = date("Y");
                        $row = $this->modelkpiconfig->GetByPk($id_kpi, $tahun);

                        $return3 = $this->modelkpi->Update($row, "id_kpi = " . $this->conn->escape($id_kpi));
                    }
                }

                if ($return['success']) {
                    $dt_kp = true;
                    // redirect($this->page_ctrl . "/add/2/" . $return['data']['id_kpi'] . "/" . $tahun1);
                }
            } else { #jika kpi sudah ada
                $id_kpi = $this->post['nama'];

                $tahun1 = $this->post['tahun'];
                $record = array(
                    'tahun' => $this->post['tahun'],
                    'id_kpi' => $id_kpi,
                );

                $cek2 = $this->conn->GetArray('select * from kpi_config where deleted_date is null and id_kpi = ' . $this->conn->escape($id_kpi) . ' and tahun = ' . $this->conn->escape($record['tahun']));
                if ($cek2) {
                    redirect($this->page_ctrl . "/add/2/" . $record['id_kpi'] . "/" . $record['tahun']);
                } else {
                    $return = $this->modelkpiconfig->Insert($record);
                }

                if ($return['success']) {

                    $tahun = date("Y");
                    $row = $this->modelkpiconfig->GetByPk($id_kpi, $tahun);

                    $return1 = $this->modelkpi->Update($row, "id_kpi = " . $this->conn->escape($id_kpi));
                }
            }
        }

        if ($this->post['act'] == 'save' && $dt_kp) {
            if ($return['data']['id_kpi'])
                redirect($this->page_ctrl . "/add/2/" . $return['data']['id_kpi'] . "/" . $tahun1);
            else
                redirect($this->page_ctrl . "/add/2");
        }
    }

    public function _save_korporat($id_kpi = null, $tahun = null, $ada = null, $id_kpi_target = null)
    {
        $dt_ko = true;
        if ($this->post['act'] == 'save' && $this->post['is_korporat'] == '1') {
            $dt_ko = false;

            # save jenis_realisasi_korporat & is_korporat pada table kpi_config
            $record2 = array(
                'jenis_realisasi_korporat' => $this->post['jenis_realisasi_korporat'],
                'is_korporat' => $this->post['is_korporat'],
            );

            if ($id_kpi && $tahun) {
                $update = $this->modelkpiconfig->Update2($record2, $id_kpi, $tahun);
            }

            # save data korporat ke table kpi_target
            // $record = array(
            //     'id_kpi' => $id_kpi,
            //     'tahun' => $tahun,
            //     'satuan' => $this->post['satuan'],
            //     // 'jenis' => $this->post['jenis'],
            //     'jenis' => 'Korporat',
            //     'bobot' => $this->post['bobot'],
            //     'polarisasi' => $this->post['polarisasi'],
            //     'target' => $this->post['target'],

            //     'definisi' => $_POST['definisi'],
            //     'tujuan' => $_POST['tujuan'],
            //     'formula' => $_POST['formula'],
            // );
            $record = array(
                'id_dit_bid' => $this->post['id_dit_bid'],
                'id_unit' => $this->post['id_unit'],
                'satuan' => $this->post['satuan'],
                'jenis' => 'Korporat',
                'bobot' => $this->post['bobot'],
                'bobot1' => $this->post['bobot1'],
                'polarisasi' => $this->post['polarisasi'],
                'target' => $this->post['target'],
                'analisa' => $this->post['analisa'],
                'is_pic' => (int)$this->post['is_pic'],

                'definisi' => $_POST['definisi'],
                'tujuan' => $_POST['tujuan'],
                'formula' => $_POST['formula'],
            );
            // dpr($ada);
            // dpr($record,1);
            if ($update['success']) {
                if ($ada) {
                    $return = $this->model->Update($record, $this->pk . "=" . $ada['id_kpi_target']);
                } else {
                    $record2 = array(
                        'id_kpi' => $id_kpi,
                        'tahun' => $tahun,
                    );
                    if ($record && $record2)
                        $record = array_merge($record2, $record);
                    $return = $this->model->Insert($record);
                }
            }
            if ($return['success']) {
                $dt_ko = true;
            }
        }
        if ($this->post['act'] == 'save' && $dt_ko) {
            // $chack = $this->conn->GetRow("select * from kpi_config where id_kpi = " . $this->conn->escape($id_kpi) . " and tahun = " . $this->conn->escape($tahun));
            // if ($chack['is_direktorat'] == 1) {
            //     redirect($this->page_ctrl . "/add/3/" . $id_kpi . "/" . $tahun);
            // } else {
            redirect($this->page_ctrl . "/add/3/" . $id_kpi . "/" . $tahun . "/2/" . $id_kpi_target);
            // }
        }
    }

    public function _save_direktorat($id_kpi = null, $tahun = null, $is_masing2 = null, $jenis_direktorat = null, $id_kpi_target = null)
    {
        # save direktorat
        // $this->conn->debug = 1;
        if ($this->post['act'] == 'save') {

            # save jenis_realisasi_direktorat & is_korporat pada table kpi_config
            if ($jenis_direktorat) {
                $record2 = array(
                    'jenis_realisasi_direktorat' => $this->post['jenis_realisasi_direktorat'],
                    'is_direktorat' => 1,
                );
                if ($id_kpi && $tahun && $record2) {
                    $update = $this->modelkpiconfig->Update2($record2, $id_kpi, $tahun);
                }

                # save data korporat ke table kpi_target
                $record = array(
                    'id_kpi' => $id_kpi,
                    'tahun' => $tahun,
                    'id_dit_bid' => $this->post['id_dit_bid'],
                    'id_unit' => $this->post['id_unit'],
                    'satuan' => $this->post['satuan'],
                    'jenis' => 'Direktorat',
                    'bobot' => $this->post['bobot'],
                    'bobot1' => $this->post['bobot1'],
                    'polarisasi' => $this->post['polarisasi'],
                    'target' => $this->post['target'],
                    'analisa' => $this->post['analisa'],
                    'is_pic' => (int)$this->post['is_pic'],

                    'definisi' => $_POST['definisi'],
                    'tujuan' => $_POST['tujuan'],
                    'formula' => $_POST['formula'],
                );

                if ($update['success']) {
                    if ($id_kpi_target) {
                        $return = $this->model->Update($record, $this->pk . "=" . $id_kpi_target);
                    } else {
                        $return = $this->model->Insert($record);
                    }
                }
                // dpr($return, 1);
            }
            if ($is_masing2 == 1)
                redirect($this->page_ctrl . "/add/3/" . $id_kpi . "/" . $tahun . '/12');
        }
        if ($this->post['act'] == 'save' || ($is_masing2 !== '1' && $return)) {
            redirect($this->page_ctrl . "/add/4/" . $id_kpi . "/" . $tahun);
        }
    }

    public function _save_unit($id_kpi = null, $tahun = null, $data2 = null)
    {
        if ($this->post['act'] == 'save') {

            #save pada kpi_cofig
            // $record2 = array(
            //     'jenis_realisasi' => $this->post['jenis_realisasi'],
            // );
            // if ($id_kpi && $tahun) {
            //     $update = $this->modelkpiconfig->Update($record2, $id_kpi, $tahun);
            // }

            #save pada kpi_target
            $record = array(
                'id_kpi' => $id_kpi,
                'tahun' => $tahun,
                'id_dit_bid' => $this->post['id_dit_bid'],
                'id_unit' => $this->post['id_unit'],
                'satuan' => $this->post['satuan'],
                'jenis' => 'Unit',
                'bobot' => $this->post['bobot'],
                'bobot1' => $this->post['bobot1'],
                'polarisasi' => $this->post['polarisasi'],
                'target' => $this->post['target'],
                'analisa' => $this->post['analisa'],
                'is_pic' => (int)$this->post['is_pic'],

                'definisi' => $_POST['definisi'],
                'tujuan' => $_POST['tujuan'],
                'formula' => $_POST['formula'],
            );
            // if ($update['success']) {
            if ($data2['id_kpi_target']) {
                $return = $this->model->Update($record, $this->pk . "=" . $data2['id_kpi_target']);
            } else {
                $return = $this->model->Insert($record);
            }
            // }

            if ($return['success']) {
                redirect($this->page_ctrl . "/add/4/" . $id_kpi . "/" . $tahun);
                // redirect($this->page_ctrl);
            }
        }
        if ($this->post['act'] == 'save2') {
            redirect($this->page_ctrl);
        }
        if ($this->post['act'] == 'selesai') {
            redirect($_SESSION[SESSION_APP]['edit_korporat_direktorat_unit']);
            // redirect($this->page_ctrl);
        }
    }

    public function srt_spase($data)
    {
        // $data_awal = $data;
        // $data_proses = preg_replace('!\s+!', ' ', $data);
        return preg_replace('!\s+!', ' ', $data);
    }

    public function add_kpi($id = null, $id_kpi = null, $tahun = null, $is_masing2 = null, $id_kpi_target = null)
    {

        if ($this->post['act'] == 'set_value') {
            unset($id);
            unset($id_kpi);
            unset($tahun);
        }
        #data previos
        if ($id == '0' && $id_kpi && $tahun) {
            $data_kpi = $this->conn->GetRow("select * from kpi where deleted_date is null and id_kpi = " . $this->conn->escape($id_kpi));
            $tahun = array('tahun' => $tahun);
            $this->data['row'] = array_merge($data_kpi, $tahun);
        }

        $this->data['kpiarr2'] = array(
            "" => "-pilih-"
        );
        #data pemiliham kpi
        $kpiarr = $this->conn->GetArray("select id_kpi, nama from kpi where deleted_date is null and id_parent is not null");

        foreach ($kpiarr as $f) {
            $this->data['kpiarr2'][$f['id_kpi']] = $f['nama'];
        }
        foreach ($this->data['kpiarr2'] as $r => $s) {
            $data_kpi1[$r] = $this->srt_spase($s);
        }
        $this->data['kpiarr2'] = $data_kpi1;

        # seting parent
        if ($this->post['act'] == 'set_value') {
            $id_kpi = $this->post['nama'];
            $row = $this->conn->GetRow("select * from kpi where deleted_date is null and id_kpi = " . $this->conn->escape($id_kpi));

            $this->data['row'] = $row;

            if (!$row) {
                $row['id_parent'] = $id_kpi;
                $mrg[$id_kpi] = $id_kpi;
            }
            if ($mrg)
                $this->data['kpiarr2'] = array_merge($this->data['kpiarr2'], $mrg);

            $this->data['row']['nama'] = $this->post['nama'];
        }
        if (!$this->data['row']['tahun']) {
            $this->data['row']['tahun'] = $_SESSION[SESSION_APP][$_SESSION[SESSION_APP]['edit_korporat_direktorat_unit']]['list_search_filter']['tahun'];
        }

        if ($id_kpi && $tahun) {
            $this->data['row']['nama'] = $this->data['row']['id_kpi'];
        }
        if (!$this->data['row']['tahun']) {
            $this->data['row']['tahun'] = $tahun;
        }
        if (!$this->data['row']['id_kpi']) {
            $this->data['row']['id_kpi'] = $id_kpi;
        }
    }

    public function add_kor($id = null, $id_kpi = null, $tahun = null, $is_masing2 = null, $id_kpi_target = null)
    {

        #update data korporat ke kpi_config
        $dt_sv = false;
        if ($this->post['act'] == 'set_value') {
            $record = array(
                'is_korporat' => $this->post['is_korporat'],
            );
            $update = $this->modelkpiconfig->Update2($record, $id_kpi, $tahun);


            $this->data['row']['is_korporat'] = $this->post['is_korporat'];
            $dt_sv = true;
        }

        #menampilkan data untuk previos
        if (!$id_kpi_target) {
            $data = $this->conn->GetRow("select * from kpi_config where deleted_date is null and id_kpi = " . $this->conn->escape($id_kpi) . " and tahun = " . $this->conn->escape($tahun));
            $data2 = $this->conn->GetRow("select * from kpi_target where deleted_date is null and jenis = 'Korporat' and id_kpi = " . $this->conn->escape($id_kpi) . " and tahun = " . $this->conn->escape($tahun));

            if ($data && $data2)
                $this->data['row'] = $ada = array_merge($data, $data2);
        } else {
            $this->data['row'] = $data2 = $this->conn->GetRow("select * from kpi_target a join kpi_config b on a.id_kpi = b.id_kpi and a.tahun = b.tahun where a.deleted_date is null and a.id_kpi_target = " . $this->conn->escape($id_kpi_target));
        }
        if (!$this->data['row']['tahun']) {
            $this->data['row']['tahun'] = $tahun;
        }
        if (!$this->data['row']['id_kpi']) {
            $this->data['row']['id_kpi'] = $id_kpi;
        }
        // dpr($this->data['row'],1);
    }

    public function add_dir($id = null, $id_kpi = null, $tahun = null, $is_masing2 = null, $id_kpi_target = null)
    {
    }

    public function add_unit($id = null, $id_kpi = null, $tahun = null, $is_masing2 = null, $id_kpi_target = null)
    {

        $cek = $this->conn->GetRow("select * from kpi_config where deleted_date is null and jenis_realisasi is not null and id_kpi = " . $this->conn->escape($id_kpi));
        if ($cek) {
            $this->data['row']['jenis_realisasi'] = $cek['jenis_realisasi'];
            // $this->data['masing2'] = true;
        }
        if ($is_masing2) {
            $this->data['tutup_list'] = true;
        }
        $this->data['rowstarget'] = $this->conn->GetArray("
            select a.*, c.table_desc as nama 
            from kpi_target a 
            left join mt_sdm_unit c on a.id_unit = c.table_code
            where a.deleted_date is null and (a.jenis='Unit' or a.jenis is null)
            and a.tahun = " . $this->conn->escape($tahun) . " 
            and a.id_kpi = " . $this->conn->escape($id_kpi));
        if ($this->post['act'] == 'set_value') {

            $record = array(
                'jenis_realisasi' => $this->post['jenis_realisasi'],
            );
            $update = $this->modelkpiconfig->Update2($record, $id_kpi, $tahun);


            $this->data['row']['jenis_realisasi'] = $this->post['jenis_realisasi'];
            unset($this->data['masing2']);
        }
        if ($id_kpi_target) {
            $this->data['row'] = $data2 = $this->conn->GetRow("select * from kpi_target a join kpi_config b on a.id_kpi = b.id_kpi and a.tahun = b.tahun where a.deleted_date is null and a.id_kpi_target = " . $this->conn->escape($id_kpi_target));
        }
        if (!$this->data['row']['id_unit']) {
            $this->data['row']['id_unit'] = $_SESSION[SESSION_APP][$_SESSION[SESSION_APP]['edit_korporat_direktorat_unit']]['list_search_filter']['id_unit'];
        }
        if (!$this->data['row']['tahun']) {
            $this->data['row']['tahun'] = $tahun;
        }
        if (!$this->data['row']['id_kpi']) {
            $this->data['row']['id_kpi'] = $id_kpi;
        }
    }

    public function Add($id = null, $id_kpi = null, $tahun = null, $is_masing2 = null, $id_kpi_target = null)
    {
        $this->load->model("KpiModel", "modelkpi");
        $this->load->model("Kpi_configModel", "modelkpiconfig");

        $this->data['id_kpi'] = $id_kpi;
        $this->data['tahun'] = $tahun;
        $this->data['masing2'] = $is_masing2 ? true : false;
        $this->data['masing2'] = $id == 3 ? $is_masing2 : $this->data['masing2'];
        # halaman pertama seting kpi
        if (!$id || $id == '0') {

            $this->viewdetail = "panelbackend/kpi_targetkoradd";
            # function data kpi
            $this->add_kpi($id, $id_kpi, $tahun, $is_masing2, $id_kpi_target);
            # function save kpi
            $this->_save_kpi();
        }

        # halaman ke-2 tambah korporat
        if ($id == 2) {

            $this->viewdetail = "panelbackend/kpi_targetkoradd2";
            # function data korporat
            $this->add_kor($id, $id_kpi, $tahun, $is_masing2, $id_kpi_target);
            # function utuk save data kpi
            $data2 = $this->conn->GetRow("select * from kpi_target where deleted_date is null and jenis = 'Korporat' and id_kpi = " . $this->conn->escape($id_kpi) . " and tahun = " . $this->conn->escape($tahun));
            $this->_save_korporat($id_kpi, $tahun, $data2, $id_kpi_target);
        }

        # halaman ke-3 tambah direktorat
        if ($id == 3) {

            if (!$id_kpi_target) {
                $data_config = $this->conn->GetOne("select is_bersama from kpi_config where deleted_date is null and id_kpi = " . $this->conn->escape($id_kpi) . " and tahun = " . $this->conn->escape($tahun));
                if ($data_config) {
                    $this->data['id_kpi_target'] = $id_kpi_target = $this->conn->GetOne("select id_kpi_target from kpi_target where deleted_date is null and id_kpi = " . $this->conn->escape($id_kpi) . " and tahun = " . $this->conn->escape($tahun) . " and jenis = 'Direktorat'");
                    redirect(base_url("/panelbackend/kpi_target_kor/add/3/$id_kpi/$tahun/2/$id_kpi_target"));
                }
            }
            $this->viewdetail = "panelbackend/kpi_targetkoradd3";
            # function data directorat
            $this->add_dir($id, $id_kpi, $tahun, $is_masing2, $id_kpi_target);

            if ($id_kpi && $tahun) {
                $data = $this->conn->GetArray("select * from kpi_config where deleted_date is null and (is_bersama is not null or is_direktorat is not null) and id_kpi = " . $this->conn->escape($id_kpi) . " and tahun = " . $this->conn->escape($tahun));
                foreach ($data as $r) {
                    $this->data['row']['jenis_direktorat'] = $r['is_bersama'] ? 2 : 1;
                    $this->data['row']['jenis_realisasi_direktorat'] = $r['jenis_realisasi_direktorat'];
                }
            }

            #update data directorat ke kpi_config
            if ($this->post['act'] == 'set_value') {
                unset($is_masing2);
                if ($this->post['jenis_direktorat'] == 1) {
                    $this->post['is_direktorat'] = 1;
                } elseif ($this->post['jenis_direktorat'] == 2) {
                    $this->post['is_bersama'] = 1;
                }
                $record = array(
                    'is_direktorat' => $this->post['is_direktorat'],
                    'is_bersama' => $this->post['is_bersama'],
                );
                $update = $this->modelkpiconfig->Update2($record, $id_kpi, $tahun);


                $this->data['row']['jenis_direktorat'] = $this->post['jenis_direktorat'];
                $this->data['masing2'] = $this->post['jenis_direktorat'] == 2 ? false : $this->data['masing2'];
            }

            if ($is_masing2) {
                $jenis = $this->conn->GetRow("select * from kpi_config where deleted_date is null and id_kpi = " . $this->conn->escape($id_kpi) . " and tahun = " . $this->conn->escape($tahun));
                if ($jenis['is_bersama'] || $jenis['is_direktorat']) {
                    $this->data['row'] = $jenis;
                    if ($is_masing2 == '12' || $is_masing2 == '2') {
                        if ($jenis['is_bersama'] == '1') {
                            $r['jenis_direktorat'] = 2;
                        } else if ($jenis['is_direktorat'] == '1') {
                            $r['jenis_direktorat'] = 1;
                        } else {
                            $r['jenis_direktorat'] = null;
                        }
                        $this->data['row'] = array_merge($this->data['row'], $r);
                    }
                }
            }
            #data directorat
            if ($this->post['jenis_direktorat'] == 1 || $is_masing2 == '12' || $this->data['row']['jenis_direktorat'] == 1) {
                $this->data['rowstarget'] = $this->conn->GetArray("
                select a.id_dit_bid as id, 'A' as id_parent, 
                case when c.nama is null then 'Bersama' else c.nama end as nama,
                a.*
                from kpi_target a 
                left join mt_sdm_dit_bid c on a.id_dit_bid = c.code
                where a.deleted_date is null and a.jenis='Direktorat' 
                and a.tahun = " . $this->conn->escape($tahun) . " 
                and a.id_kpi = " . $this->conn->escape($id_kpi));
            }
            $jenis_direktorat = $this->post['jenis_direktorat'] ? $this->post['jenis_direktorat'] : $is_masing2;

            if ($id_kpi_target) {
                $row = $this->conn->GetArray("select * from kpi_target a join kpi_config b on b.id_kpi = a.id_kpi and b.tahun = a.tahun where a.deleted_date is null and id_kpi_target = " . $this->conn->escape($id_kpi_target));
                foreach ($row as $r) {
                    $this->data['row'] = $r;
                }
                $this->data['row']['jenis_direktorat'] = $jenis_direktorat;
                // dpr($this->data['row'],1);
                if ($this->data['row']['is_bersama']) {
                    unset($this->data['masing2']);
                }
                // unset($this->data['row']['is_bersama']);
            }
            if (!$this->data['row']['id_dit_bid']) {
                $this->data['row']['id_dit_bid'] = $_SESSION[SESSION_APP][$_SESSION[SESSION_APP]['edit_korporat_direktorat_unit']]['list_search_filter']['id_dit_bid'];
            }
            if (!$this->data['row']['tahun']) {
                $this->data['row']['tahun'] = $tahun;
            }
            if (!$this->data['row']['id_kpi']) {
                $this->data['row']['id_kpi'] = $id_kpi;
            }
            # function save directorat
            $this->_save_direktorat($id_kpi, $tahun, $is_masing2, $jenis_direktorat, $id_kpi_target);
        }

        # halaman ke-4 tambah unit
        if ($id == 4) {

            $this->viewdetail = "panelbackend/kpi_targetkoradd4";
            # fuction data unit
            $this->add_unit($id, $id_kpi, $tahun, $is_masing2, $id_kpi_target);
            # function save unit
            $data2 = $this->conn->GetRow("select * from kpi_target a join kpi_config b on a.id_kpi = b.id_kpi and a.tahun = b.tahun where a.deleted_date is null and a.id_kpi_target = " . $this->conn->escape($id_kpi_target));
            $this->_save_unit($id_kpi, $tahun, $data2);
        }

        $this->View($this->viewdetail);
    }

    public function Add_bak($id = null)
    {
        $this->data['halaman'] = 1;
        if ($this->post['act'] === 'save') {
            $record = array(
                'id_dit_bid' => $this->post['id_dit_bid'],
                'id_unit' => $this->post['id_unit'],
                'satuan' => $this->post['satuan'],
                'jenis' => $this->post['jenis'],
                'bobot' => $this->post['bobot'],
                'bobot1' => $this->post['bobot1'],
                'polarisasi' => $this->post['polarisasi'],
                'target' => $this->post['target'],
                'analisa' => $this->post['analisa'],
                'is_pic' => (int)$this->post['is_pic'],

                'definisi' => $_POST['definisi'],
                'tujuan' => $_POST['tujuan'],
                'formula' => $_POST['formula'],
            );

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
                } else {
                    $return = true;
                }
            }
            if ($return) {
                $_SESSION[SESSION_APP][$this->page_ctrl]['halaman'] = '2';
                redirect($this->page_ctrl . "/add");
            }
        }

        if ($_SESSION[SESSION_APP][$this->page_ctrl]['halaman']) {
            $id = $this->conn->GetOne("select max(b.id_kpi_target) from kpi_target b where b.deleted_date is null");
            $this->data['row'] = $this->model->GetByPk($id);
        }

        $this->viewdetail = "panelbackend/kpi_targetkoradd";
        $this->View($this->viewdetail);
    }

    public function Index($page = 0)
    {
        $_SESSION[SESSION_APP]['edit_korporat_direktorat_unit'] = 'panelbackend/kpi_target_kor';
        if (!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']['tahun'])
            $_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']['tahun'] = date('Y');


        # save realisasi
        if ($this->post['act'] == 'save_realisasi') {
            $this->load->model("Kpi_target_realisasiModel", "kpi_target_realisasi");

            $cek_id = $this->conn->GetOne("select id_kpi_target_realisasi from kpi_target_realisasi 
			where deleted_date is null and bulan = " . $this->conn->escape($this->post['bulan']) . " 
			and id_kpi_target = " . $this->conn->escape($this->post['id_kpi_target']));


            $record = array();
            $record['bulan'] = $this->post['bulan'];
            $record['nilai'] = $this->post['nilai1'] ? $this->post['nilai1'] : $this->post['nilai2'];
            $record['prosentase'] = $this->post['prosentase'];
            $record['id_kpi_target'] = $this->post['id_kpi_target'];


            if ($cek_id) {
                $return = $this->kpi_target_realisasi->Update($record, "id_kpi_target_realisasi = " . $this->conn->qstr($cek_id));
            } else {
                $return = $this->kpi_target_realisasi->Insert($record);
            }

            $id_kpi_target = $return['data']['id_kpi_target'] ? $return['data']['id_kpi_target'] : $record['id_kpi_target'];
            # mengirim notivikasi
            if ($return) {
                // $data_not = $this->conn->GetArray("select * from public_sys_user_group where group_id in(4)");
                // $data_not = $this->conn->GetArray("
                // select 
                // distinct g.*
                // from
                // 	public_sys_user_group g
                // 	left join public_sys_group a on g.group_id = g.group_id
                // 	left join public_sys_group_menu b on a.group_id = b.group_id
                // 	left join public_sys_menu c on b.menu_id = c.menu_id
                // 	left join public_sys_action d on d.menu_id = c.menu_id
                // where a.group_id = 4 and c.menu_id = 941
                // /*where d.name = 'view_all' and a.group_id = 4*/");
                // foreach ($data_not as $r) {
                //     $record2 = array(
                //         'page' => 'unit',
                //         'untuk' => $r['id_jabatan'],
                //         'id_status_pengajuan' => 2,
                //         // 'deskripsi' => "telah menambahkan realisasi",
                //         'deskripsi' => $this->conn->GetOne("select nama from kpi a where exists( select 1 from kpi_target b where a.id_kpi = b.id_kpi and id_kpi_target = " . $this->conn->escape($id_kpi_target) . ")"),
                //         'url' => "panelbackend/kpi_target_kor/detail/$id_kpi_target",
                //     );

                //     $re = $this->InsertTask($record2);
                // }

                $nama = $this->conn->GetOne("select nama from kpi a where a.deleted_date is null and exists( select 1 from kpi_target b where a.id_kpi = b.id_kpi and id_kpi_target = " . $this->conn->escape($id_kpi_target) . ")");
                $record2 = array(
                    'page' => 'realisasi',
                    'id_status_pengajuan' => 8,
                    'deskripsi' => "KPI " . $nama . " telah ditambahkan realisasi ",
                    'url' => "panelbackend/kpi_target_realisasi/index/$id_kpi_target",
                );

                $re = $this->InsertTask($record2);
            }
            if ($return['success']) {

                $this->_afterEditSucceed($id);

                SetFlash('suc_msg', $return['success']);
            } else {
                $this->data['row'] = array_merge($this->data['row'], $record);
                $this->data['row'] = array_merge($this->data['row'], $this->post);

                $this->_afterEditFailed($id);

                $this->data['err_msg'] = "Data gagal disimpan";
            }
        }
        $this->data['header'] = $this->Header();
        $this->data['rows'] = $this->_getList($page);
        // dpr($this->data['rows'],1);

        foreach ($this->data['rows'] as $r) {
            if ($r['id_kpi_target']) {
                $this->data['rowheader'][$r['id_kpi_target']]  = $this->model->GetByPk($r['id_kpi_target']);
            }
        }

        foreach ($this->data['rows'] as &$k) {
            if ($k['id_kpi_target']) {
                $data = $this->conn->GetRow("select a.definisi, a.tujuan, a.formula from kpi_target a where a.deleted_date is null and id_kpi_target = " . $this->conn->escape($k['id_kpi_target']));

                $kdefinisi = str_replace('"', "&quot;", $data['definisi']);
                $ktujuan = str_replace('"', "&quot;", $data['tujuan']);
                $kformula = str_replace('"', "&quot;", $data['formula']);


                $k['definisi'] = str_replace("'", "&middot;", $kdefinisi);
                $k['tujuan'] = str_replace("'", "&middot;", $ktujuan);
                $k['formula'] = str_replace("'", "&middot;", $kformula);
            }
        }
        foreach ($this->data['rows'] as &$l) {
            $l['title'] = "Definisi : " . $l['definisi'] . "<br> Tujuan : " . $l['tujuan'] . "<br> Formula : " . $l['formula'];
        }
        $this->data['page_title'] .= " Tahun " . UI::createTextNumber("list_search_filter[tahun]", $this->data['filter_arr']["tahun"], 4, 4, true, 'form-control', "style='max-width: 87px;display:inline;line-height: 1;font-size: inherit;font-weight: inherit;font-family: inherit;padding: .1rem .375rem !important;'");

        $this->View($this->viewlist);
    }

    public function go_print()
    {
        $this->template = "panelbackend/main3";
        $this->layout = "panelbackend/layout3";
        $this->data['no_header'] = true;
        $this->viewprint = "panelbackend/kpi_target_unitprint";

        $this->data['header'] = $this->Header();
        $this->data['rows'] = $this->_getList(0);
        $this->data['page_title'] .= " Tahun " . $this->data['filter_arr']["tahun"];

        $this->View($this->viewprint);
    }

    protected function _afterDetail($id)
    {
        if ($this->data['row']['id_kpi']) {
            $this->data['rowheader'] = $this->kpi->GetByPk($this->data['row']['id_kpi']);
        }
        if ($this->modelfile) {
            if (!$this->data['row']['files']['id'] && $id) {
                $rows = $this->conn->GetArray("select *
				from {$this->modelfile->table}
				where deleted_date is null and {$this->model->pk} = " . $this->conn->escape($id));

                foreach ($rows as $r) {
                    $this->data['row']['files']['id'][] = $r[$this->modelfile->pk];
                    $this->data['row']['files']['name'][] = $r['client_name'];
                }
            }
        }
    }

    protected function Header()
    {
        return array(
            array(
                'name' => 'nama',
                'label' => 'Indikator KPI',
                'type' => "list",
                'nofilter' => true,
            ),

            # lama
            // array(
            //     'name' => 'bobot',
            //     'label' => 'Bobot',
            //     'width' => "80px",
            //     'type' => "number",
            //     'nofilter' => true,
            // ),
            // array(
            //     'name' => 'polarisasi',
            //     'label' => 'Polarisasi',
            //     'width' => "auto",
            //     'type' => "varchar",
            //     'nofilter' => true,
            // ),
            // array(
            //     'name' => 'target',
            //     'label' => 'Target',
            //     'width' => "80px",
            //     'type' => "number",
            //     'nofilter' => true,
            // ),
            // array(
            //     'name' => 'satuan',
            //     'label' => 'Satuan',
            //     'width' => "auto",
            //     'type' => "varchar",
            //     'nofilter' => true,
            // ),
            # lama end

            # baru
            array(
                'name' => 'satuan',
                'label' => 'Satuan',
                'width' => "auto",
                'type' => "varchar",
                'nofilter' => true,
            ),
            array(
                'name' => 'target',
                'label' => 'Target',
                'width' => "80px",
                'type' => "number",
                'nofilter' => true,
            ),
            array(
                'name' => 'polarisasi',
                'label' => 'Polarisasi',
                'width' => "auto",
                'type' => "varchar",
                'nofilter' => true,
            ),
            array(
                'name' => 'bobot',
                'label' => 'Bobot',
                'width' => "80px",
                'type' => "number",
                'nofilter' => true,
            ),
            # baru end
            array(
                'name' => 'totrealisasi',
                'label' => 'Total Realisasi',
                'width' => "80px",
                'type' => "number",
                'nofilter' => true,
            ),
            array(
                'name' => 'prostarget',
                'label' => '% thdp Realisasi',
                'width' => "80px",
                'type' => "number",
                'nofilter' => true,
            ),
            array(
                'name' => 'realbobot',
                'label' => 'Realisasi Skor Bobot',
                'width' => "80px",
                'type' => "number",
                'nofilter' => true,
            ),
            array(
                'name' => 'lastinput',
                'label' => 'Input Terakhir',
                'width' => "auto",
                'type' => "list",
                'value' => ListBulan(),
                'nofilter' => true,
            ),
        );
    }
    protected function Rules()
    {
        return array(
            "bobot1" => array(
                'field' => 'bobot1',
                'label' => 'Bobot',
                'rules' => "required",
            ),
        );
    }

    protected function Record($id = null)
    {
        return array(
            // 'id_dit_bid' => $this->post['id_dit_bid'],
            // 'id_unit' => $this->post['id_unit'],
            // 'id_kpi' => $this->post['id_kpi'],
            // 'satuan' => $this->post['satuan'],
            // 'bobot' => $this->post['bobot'],
            'bobot1' => $this->post['bobot1'],
            // 'polarisasi' => $this->post['polarisasi'],
            // 'target' => $this->post['target'],
            // 'analisa' => $this->post['analisa'],
            // 'is_pic' => (int)$this->post['is_pic'],
            // 'tahun' => $this->post['tahun'],
        );
    }
}
