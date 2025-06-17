<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Kri extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/krilist";
		$this->viewdetail = "panelbackend/kridetail";
		$this->viewprint = "panelbackend/kriprint";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Risiko';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Risiko';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Risiko';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Key Risk Indicator';
		}

		$this->load->model("Risk_risikoModel", "model");

		$this->load->model("Mt_risk_tingkatModel", "modelTingkat");
		$this->data['tingkatArr'] = $this->modelTingkat->GetCombo();

		$this->data['hide_tgl_efektif'] = true;

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datatable'
		);

		$this->load->model("Mt_sdm_unitModel", "mtsdmunit");
		$this->data['mtsdmunitarr'] = $this->mtsdmunit->GetCombo();

		$this->access_role['list_print'] = true;
	}

	public function Detail($id = null, $tahun = null)
	{
		if (!$tahun)
			$tahun = $_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'];

		redirect("panelbackend/risk_kri_hasil/index/$id/$tahun");
	}

	public function Index($page = 0)
	{

		$this->load->model("Risk_scorecardModel", "modelscorecard");
		$bulan = null;
		$id_kajian_risiko = 3;

		$tgl_efektif = $this->data['tgl_efektif'];
		list($tahun, $bln, $tgl) = explode("-", $tgl_efektif);


		#filter
		if ($this->post['act'] == "set_filter") {
			$_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'] = round($this->post['tahun_filter']);
			$_SESSION[SESSION_APP][$this->page_ctrl]['id_unit_filter'] = $this->post['id_unit_filter'];
			$_SESSION[SESSION_APP][$this->page_ctrl]['nama_filter'] = $this->post['nama_filter'];
			$_SESSION[SESSION_APP][$this->page_ctrl]['id_kajian_risiko_filter'] = $this->post['id_kajian_risiko_filter'];
			$_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard_filter'] = $this->post['id_scorecard_filter'];
			$_SESSION[SESSION_APP][$this->page_ctrl]['is_tinggi_filter'] = $this->post['is_tinggi_filter'];
			$_SESSION[SESSION_APP][$this->page_ctrl]['is_menengah_tinggi_filter'] = $this->post['is_menengah_tinggi_filter'];
			$_SESSION[SESSION_APP][$this->page_ctrl]['is_menengah_filter'] = $this->post['is_menengah_filter'];
			$_SESSION[SESSION_APP][$this->page_ctrl]['is_menengah_rendah_filter'] = $this->post['is_menengah_rendah_filter'];
			$_SESSION[SESSION_APP][$this->page_ctrl]['is_rendah_filter'] = $this->post['is_rendah_filter'];

			redirect(current_url());
		}

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['id_kajian_risiko_filter'])
			$id_kajian_risiko = $_SESSION[SESSION_APP][$this->page_ctrl]['id_kajian_risiko_filter'];

		$_SESSION[SESSION_APP][$this->page_ctrl]['id_kajian_risiko_filter'] = $id_kajian_risiko;

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'])
			$tahun = $_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'];

		$_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'] = $tahun;

		$id_unit = $_SESSION[SESSION_APP][$this->page_ctrl]['id_unit_filter'];
		$nama = $_SESSION[SESSION_APP][$this->page_ctrl]['nama_filter'];
		$id_scorecard = $_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard_filter'];
		$is_tinggi = $_SESSION[SESSION_APP][$this->page_ctrl]['is_tinggi_filter'];
		$is_menengah_tinggi = $_SESSION[SESSION_APP][$this->page_ctrl]['is_menengah_tinggi_filter'];
		$is_menengah = $_SESSION[SESSION_APP][$this->page_ctrl]['is_menengah_filter'];
		$is_menengah_rendah = $_SESSION[SESSION_APP][$this->page_ctrl]['is_menengah_rendah_filter'];
		$is_rendah = $_SESSION[SESSION_APP][$this->page_ctrl]['is_rendah_filter'];

		if (!$this->access_role['view_all']) {
			$id_unit = $_SESSION[SESSION_APP]['id_unit'];
		}

		$this->data['id_kajian_risiko_filter'] = $id_kajian_risiko;
		$this->data['tahun_filter'] = $tahun;
		$this->data['id_unit_filter'] = $id_unit;
		$this->data['nama_filter'] = $nama;
		$this->data['id_scorecard_filter'] = $id_scorecard;
		$this->data['is_tinggi_filter'] = $is_tinggi;
		$this->data['is_menengah_tinggi_filter'] = $is_menengah_tinggi;
		$this->data['is_menengah_filter'] = $is_menengah;
		$this->data['is_menengah_rendah_filter'] = $is_menengah_rendah;
		$this->data['is_rendah_filter'] = $is_rendah;


		// $this->data['kategoriarr'] = $this->conn->GetList("select id_kajian_risiko as idkey, nama as val from mt_risk_kajian_risiko order by idkey");
		// $this->data['scorecardarr'] = $this->model->GetComboDashboard(null, "15-" . ($bulan ? $bulan : $bln) . "-" . $tahun, null, $id_unit);
		$this->data['scorecardarr'] = array('' => '-Pilih kajian risiko-') + $this->modelscorecard->GetComboChild();

		$id_scorecardarr = array();
		if ($id_scorecard) {
			// $this->data['scorecardsubarr'] = $scorecardsubarr = $this->modelscorecard->GetComboChild($id_scorecard);
			// if ($scorecardsubarr[$id_scorecard_sub] && $id_scorecard_sub) {
			// 	$id_scorecardarr = $this->modelscorecard->GetChild($id_scorecard_sub);
			// } else {
			$id_scorecardarr = $this->modelscorecard->GetChild($id_scorecard);
			// }
		} else {
			$id_scorecardarr = array_keys($this->data['scorecardarr']);
		}

		$params = array(
			// "id_kajian_risiko" => $id_kajian_risiko,
			"id_unit" => $id_unit,
			"id_scorecard" => $id_scorecardarr,
			"tahun" => $tahun
		);

		list($where, $id_periode_tw, $tahun) = $this->model->getWhere($params);

		$tingkatarr = array();

		if ($is_tinggi)
			$tingkatarr[] = 5;
		if ($is_menengah_tinggi)
			$tingkatarr[] = 4;
		if ($is_menengah)
			$tingkatarr[] = 3;
		if ($is_menengah_rendah)
			$tingkatarr[] = 2;
		if ($is_rendah)
			$tingkatarr[] = 1;

		if ($tingkatarr) {
			$where .= " and rm.id_tingkat in(" . implode(",", $tingkatarr) . ")";
		}

		if ($id_unit) {
			$where .= " and rs.id_unit = " . $this->conn->escape($id_unit);
		}

		$where .= " and rr.status_risiko in ('1') and lower(rr.nama) like '%" . strtolower($nama) . "%'";

		$param = array(
			'where' => $where,
			'id_periode_tw' => $id_periode_tw,
			'tahun' => $tahun,
			'page' => $page,
			'limit' => $this->_limit(),
			'order' => $this->_order(),
			'filter' => $this->_getFilter()
		);

		$param['filter'] = str_replace("1=1", "", $param['filter']);

		// dpr($where, 1);

		$this->data['list'] = $this->model->SelectGridRisk(
			$param
		);

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

		$this->View($this->viewlist);
	}



	protected function _order()
	{

		if ($this->post['act'] == 'list_sort' && $this->post['list_sort']) {

			$_SESSION[SESSION_APP][$this->page_ctrl]['list_order'] = $this->post['list_order'];
			$_SESSION[SESSION_APP][$this->page_ctrl]['list_sort'] = $this->post['list_sort'];
		}

		$order = "";

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['list_sort']) {
			$order .= $_SESSION[SESSION_APP][$this->page_ctrl]['list_sort'];
		}

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['list_order'] && $order) {
			$order .= ' ' . $_SESSION[SESSION_APP][$this->page_ctrl]['list_order'];
		}

		$this->data['list_sort'] = $_SESSION[SESSION_APP][$this->page_ctrl]['list_sort'];
		$this->data['list_order'] = $_SESSION[SESSION_APP][$this->page_ctrl]['list_order'];

		replaceSingleQuote($this->list_order);

		if ($this->list_order && $order)
			$this->list_order .= ", " . $order;
		elseif ($order)
			$this->list_order = $order;

		if ($this->list_order)
			return $this->list_order;

		return null;
	}

	public function go_print()
	{
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";
		$this->data['no_header'] = true;
		$this->data['no_title'] = true;


		$this->load->model("Risk_scorecardModel", "modelscorecard");
		$bulan = null;
		$id_kajian_risiko = 3;
		if (!$this->access_role['view_all']) {
			$id_unit = $_SESSION[SESSION_APP]['id_unit'];
		}

		$tgl_efektif = $this->data['tgl_efektif'];
		list($tahun, $bln, $tgl) = explode("-", $tgl_efektif);


		#filter

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['id_kajian_risiko_filter'])
			$id_kajian_risiko = $_SESSION[SESSION_APP][$this->page_ctrl]['id_kajian_risiko_filter'];

		if ($_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'])
			$tahun = $_SESSION[SESSION_APP][$this->page_ctrl]['tahun_filter'];

		$id_unit = $_SESSION[SESSION_APP][$this->page_ctrl]['id_unit_filter'];
		$id_scorecard = $_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard_filter'];
		$is_tinggi = $_SESSION[SESSION_APP][$this->page_ctrl]['is_tinggi_filter'];
		$is_menengah_tinggi = $_SESSION[SESSION_APP][$this->page_ctrl]['is_menengah_tinggi_filter'];
		$is_menengah = $_SESSION[SESSION_APP][$this->page_ctrl]['is_menengah_filter'];
		$is_menengah_rendah = $_SESSION[SESSION_APP][$this->page_ctrl]['is_menengah_rendah_filter'];
		$is_rendah = $_SESSION[SESSION_APP][$this->page_ctrl]['is_rendah_filter'];

		$this->data['id_unit_filter'] = $id_unit;
		$this->data['tahun_filter'] = $tahun;
		$this->data['id_kajian_risiko_filter'] = $id_kajian_risiko;
		$this->data['id_scorecard_filter'] = $id_scorecard;


		$tingkatarr = array();

		if ($is_tinggi)
			$tingkatarr[] = 'Tinggi';
		if ($is_menengah_tinggi)
			$tingkatarr[] = 'Menengah - Tinggi';
		if ($is_menengah)
			$tingkatarr[] = 'Menengah';
		if ($is_menengah_rendah)
			$tingkatarr[] = 'Menengah - Rendah';
		if ($is_rendah)
			$tingkatarr[] = 'Rendah';

		$this->data['tingkatarr'] = $tingkatarr;
		// $this->data['kategoriarr'] = $this->conn->GetList("select id_kajian_risiko as idkey, nama as val from mt_risk_kajian_risiko order by idkey");
		$this->data['scorecardarr'] = $this->model->GetComboDashboard($id_kajian_risiko, "15-" . ($bulan ? $bulan : $bln) . "-" . $tahun, null, $id_unit);

		$id_scorecardarr = array();
		$id_scorecard_sub = null;
		if ($id_scorecard) {
			$this->data['scorecardsubarr'] = $scorecardsubarr = $this->modelscorecard->GetComboChild($id_scorecard);
			if ($scorecardsubarr[$id_scorecard_sub] && $id_scorecard_sub) {
				$id_scorecardarr = $this->modelscorecard->GetChild($id_scorecard_sub);
			} else {
				$id_scorecardarr = $this->modelscorecard->GetChild($id_scorecard);
			}
		} else {
			$id_scorecardarr = array_keys($this->data['scorecardarr']);
		}

		$params = array(
			"id_kajian_risiko" => $id_kajian_risiko,
			"id_scorecard" => $id_scorecardarr,
			"tahun" => $tahun
		);

		list($where, $id_periode_tw, $tahun) = $this->model->getWhere($params);

		$tingkatarr = array();

		if ($is_tinggi)
			$tingkatarr[] = 5;
		if ($is_menengah_tinggi)
			$tingkatarr[] = 4;
		if ($is_menengah)
			$tingkatarr[] = 3;
		if ($is_menengah_rendah)
			$tingkatarr[] = 2;
		if ($is_rendah)
			$tingkatarr[] = 1;


		if ($tingkatarr) {
			$where .= " and rm.id_tingkat in(" . implode(",", $tingkatarr) . ")";
		}

		$rows = $this->conn->GetArray("select 
        rr.nomor, rr.id_risiko as id_risiko1, rk.*, rr.id_scorecard, rm.id_tingkat, rr.nama as nama_risiko,rm.id_kemungkinan, rm.id_dampak
        from risk_risiko rr
		left join risk_kri rk on rk.id_risiko = rr.id_risiko
        join risk_scorecard rs on rr.id_scorecard = rs.id_scorecard
		left join risk_risiko_current rc on rr.id_risiko = rc.id_risiko 
		and rc.tahun = " . $this->conn->escape($tahun) . " and rc.id_periode_tw = " . $this->conn->escape($id_periode_tw) . "

		left join mt_risk_matrix rm on rm.id_kemungkinan = ifnull(rc.id_kemungkinan, ifnull(rr.residual_kemungkinan_evaluasi, rr.control_kemungkinan_penurunan))
		and rm.id_dampak = ifnull(rc.id_dampak, ifnull(rr.residual_dampak_evaluasi, rr.control_dampak_penurunan))
		
        where 1=1 rr.deleted_date is not null " . $where . "");

		foreach ($rows as &$r) {
			$rws = $this->conn->GetArray("select * from risk_kri_hasil 
			where deleted_date is null and tahun = " . $this->conn->escape($tahun) . " 
			and id_kri = " . $this->conn->escape($r['id_kri']));
			foreach ($rws as $rw)
				$r['nilai' . $rw['bulan']] = $rw['nilai'];
		}

		$this->data['rows'] = $rows;

		$this->View($this->viewprint);
	}
}
