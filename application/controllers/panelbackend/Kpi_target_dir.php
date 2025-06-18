<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Kpi_target_dir extends _adminController
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function init()
    {
        parent::init();

        $this->viewlist = "panelbackend/kpi_target_dirlist";
        $this->viewdetail = "panelbackend/kpi_targetdirdetail";
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
            $this->data['page_title'] = 'KPI Direktorat';
        }

        $this->load->model("Kpi_targetModel", "model");

        $this->load->model("KpiModel", "kpi");
        $this->data['kpiarr'] = $this->kpi->GetCombo();

        $this->load->model("Mt_sdm_dit_bidModel", "dir");
        $this->data['dirarr'] = $this->dir->GetCombo();
        $this->data['dirarr'][''] = 'Pilih Direktorat';

        $this->load->model("Mt_sdm_unitModel", "unit");
        $this->data['unitarr'] = $this->unit->GetCombo();
        $this->data['unitarr'][''] = 'Pilih Unit';

        $tahun = array(null => 'Pilih Tahun', date('Y') => date('Y'), date('Y') - 1 => date('Y') - 1, date('Y') - 2 => date('Y') - 2);

        $this->data['tahunarr'] = $tahun;

        $this->pk = $this->model->pk;
        $this->data['pk'] = $this->pk;
        $this->plugin_arr = array(
            'select2', 'treetable'
        );

        $this->access_role['list_print'] = true;
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
            'id_dit_bid' => $_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']['id_dit_bid'],
        );

        if ($this->post['act'] && $this->post['act'] <> 'save' && $this->post['act'] <> 'set_value') {

            if ($this->data['add_param']) {
                $add_param = '/' . $this->data['add_param'];
            }
            redirect(str_replace(strstr(current_url(), "/index$add_param/$page"), "/index{$add_param}", current_url()));
        }

        $respon = $this->model->SelectGridDirektorat(
            $param
        );

        return $respon;
    }

    public function Index($page = 0)
    {
        $_SESSION[SESSION_APP]['edit_korporat_direktorat_unit'] = 'panelbackend/kpi_target_dir';
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

        foreach ($this->data['rows'] as $r) {
            if ($r['id_kpi_target']) {
                $this->data['rowheader'][$r['id_kpi_target']]  = $this->model->GetByPk($r['id_kpi_target']);
            }
        }

        foreach ($this->data['rows'] as &$k) {
            if ($k['id_kpi_target']) {
                $data = $this->conn->GetRow("select a.definisi, a.tujuan, a.formula from kpi_target a where deleted_date is null and id_kpi_target = " . $this->conn->escape($k['id_kpi_target']));

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

    public function add()
    {
        redirect(base_url('panelbackend/kpi_target_kor/add'));
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
            #lama end

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
            "bobot2" => array(
                'field' => 'bobot2',
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
            'bobot2' => $this->post['bobot2'],
            // 'polarisasi' => $this->post['polarisasi'],
            // 'target' => $this->post['target'],
            // 'analisa' => $this->post['analisa'],
            // 'is_pic' => (int)$this->post['is_pic'],
            // 'tahun' => $this->post['tahun'],
        );
    }
}
