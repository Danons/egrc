<?php
defined("BASEPATH") or exit("NO direct script access allowed");

include APPPATH . "core/_adminController.php";
class Spi_profil_pengawas extends _adminController
{
    public function __construct()
    {
        parent::__construct();
    }
    protected $xss_clean = false;
    protected $escape_html = false;


    protected  function init()
    {
        parent::init();
        $this->viewlist = "panelbackend/spi_profil_pengawaslist";
        $this->viewdetail = "panelbackend/spi_profil_pengawasdetail";
        $this->template = "panelbackend/main";
        $this->layout = 'panelbackend/layout_profil_spi';

        if ($this->mode == 'edit') {
            $this->data['page_title'] = 'Edit';
            $this->data['edited'] = true;
        } elseif ($this->mode == 'detail') {
            $this->data['page_title'] = 'Detail';
            $this->data['edited'] = false;
        }
        $this->data['page_title'] = 'Profil Pengawas';
        $this->data['mode'] = $this->mode = 'edit_detail';
    }

    public function Index($nid = null)
    {
        if ($this->post['act'] == 'set_nid')
            $_SESSION[SESSION_APP][$this->page_ctrl]['nid'] = $this->post['idkey'];

        $this->data['nid'] = $_SESSION[SESSION_APP][$this->page_ctrl]['nid'];

        $this->data['pengawasarr'] = $this->conn->GetList("select ifnull(a.nid, a.username) as idkey, a.name as val
        from ( select ifnull(b.user_id, a.user_id) user_id, 
        ifnull(b.group_id, a.group_id) group_id, 
        ifnull(b.id_jabatan, a.id_jabatan) id_jabatan, 
        a.username, a.name, a.last_ip, a.last_login, a.is_active, 
        a.password, a.nid, a.is_notification, a.email, a.is_manual, 
        a.tgl_mulai_aktif, a.tgl_selesai_aktif 
        from public_sys_user a 
        left join public_sys_user_group b on a.user_id = b.user_id where a.deleted_date IS NULL AND NOW() 
		  BETWEEN  IFNULL(a.tgl_mulai_aktif,NOW()) AND ifnull(a.tgl_selesai_aktif,NOW()) and is_active = 1  and b.group_id = 52) a 
        join mt_sdm_jabatan b on a.id_jabatan = b.id_jabatan 
        left join mt_sdm_level c on b.id_sdm_level = c.id_sdm_level
        where 1=1 and user_id <> 1 /*and lower(b.nama) like '%pengawas%' */
        order by c.level asc");

        $urlconfig = $this->config->item("url_profil_pengawas");

        if ($this->data['nid']) {
            // dpr($urlconfig . $this->data['nid'], 1);

            $contents = @file_get_contents($urlconfig . $this->data['nid']);

            if ($contents) {
                $doc = new DomDocument;
                $doc->validateOnParse = true;
                $doc->loadHtml($contents);
                $xpath = new DOMXpath($doc);
                $elements = $xpath->query("*//div[@class='portlet-body']");
                $output = "";
                foreach ($elements as $element) {
                    $nodes = $element->childNodes;
                    foreach ($nodes as $node) {
                        $output .= $doc->saveHTML($node);
                    }
                }
                // dpr($output, 1);
                $this->data['contentprofil'] = $output;
            } else {
                $this->data['contentprofil'] = "<i>Data dengan NIPP " . $this->data['nid'] . " tidak ditemukan silahkan cek di Pengaturan > User</i>";
            }
        }


        $this->View($this->viewlist);
    }

    private function clean($text)
    {
        $clean = html_entity_decode(trim(str_replace(';', '-', preg_replace('/\s+/S', " ", strip_tags($text))))); // remove everything
        return $clean;
        echo '\n'; // throw a new line
    }
}
