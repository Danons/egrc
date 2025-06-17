<?php
defined('BASEPATH') or exit('No direct script access allowed');

include "Pemeriksaan.php";
class Pemeriksaan_monitoring_evaluasi extends Pemeriksaan
{
	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/pemeriksaanlist";
		$this->viewdetail = "panelbackend/pemeriksaandetail";
		$this->viewprintrencanaaudit = "panelbackend/pemeriksaanprintrencanaaudit";
		$this->viewprintanggaranbiayaaudit = "panelbackend/pemeriksaanprintanggaranbiayaaudit";
		$this->viewprintrekapitulasiaudit = "panelbackend/viewprintrekapitulasiaudit";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Monitoring & Evaluasi';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Monitoring & Evaluasi';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->layout = "panelbackend/layout_pemeriksaan";
			$this->data['page_title'] = 'Detail Monitoring & Evaluasi';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Monitoring & Evaluasi';
		}

		if ($this->mode == 'monev') {
			$this->data['mode'] = $this->mode = 'index';
		}

		$this->load->model("PemeriksaanModel", "model");
		$this->load->model("Mt_sdm_unitModel", "modelunit");

		$this->load->model("Mt_sdm_subbidModel", "mtsdmsubbid");
		$this->load->model("Mt_sdm_jabatanModel", "modeljabatan");

		$this->data['unitarr'] = $this->modelunit->GetCombo();
		$this->data['jabatanarr'] = array('' => '') + $this->modeljabatan->GetCombo();

		$this->load->model("Mt_jenis_audit_eksternalModel", "mtjenisauditeksternal");
		$this->data['jeniseksternalarr'] = $this->mtjenisauditeksternal->GetCombo();

		$this->load->model("Mt_bidang_pemeriksaanModel", "mtbidangpemeriksaanarr");

		$this->load->model("Mt_status_pemeriksaanModel", "mtstatuspemeriksaan");
		$this->data['statusarr'] = $this->mtstatuspemeriksaan->GetCombo();

		$this->load->model("Public_sys_userModel", "usermodel");
		$this->data['userarr'] = $this->usermodel->GetCombo();

		$this->load->model("Risk_sasaranModel", "msasaran");
		$this->data['sasaranarr'] = $this->msasaran->GetCombo();

		$this->data['pelaksanaarr'] = $this->conn->GetList("select 
		user_id as idkey, name as val from public_sys_user a 
		where a.deleted_date is null and exists (
			select 1 from public_sys_user_group b 
			join public_sys_group_menu c on b.group_id = c.group_id 
			join public_sys_group_action d on c.group_menu_id = d.group_menu_id 
			join public_sys_action e on d.action_id = e.action_id 
			join public_sys_menu f on c.menu_id = f.menu_id
			where a.user_id = b.user_id and e.name='add' and f.url='panelbackend/pemeriksaan_temuan'
		)");
		// dpr($this->data['pelaksanaarr']);
		// die();

		$this->data['pimpinanarr'] = $this->conn->GetList("select 
		user_id as idkey, name as val from public_sys_user a 
		where a.deleted_date is null and exists (
			select 1 from public_sys_user_group b 
			join public_sys_group_menu c on b.group_id = c.group_id 
			join public_sys_group_action d on c.group_menu_id = d.group_menu_id 
			join public_sys_action e on d.action_id = e.action_id 
			join public_sys_menu f on c.menu_id = f.menu_id
			where a.user_id = b.user_id and e.name='pengawas' and f.url='panelbackend/pemeriksaan'
		)");

		$this->data['penanggungjawabarr'] = $this->conn->GetList("select 
		user_id as idkey, name as val from public_sys_user a 
		where a.deleted_date is null and exists (
			select 1 from public_sys_user_group b 
			join public_sys_group_menu c on b.group_id = c.group_id 
			join public_sys_group_action d on c.group_menu_id = d.group_menu_id 
			join public_sys_action e on d.action_id = e.action_id 
			join public_sys_menu f on c.menu_id = f.menu_id
			where a.user_id = b.user_id and e.name='penanggungjawab' and f.url='panelbackend/pemeriksaan'
		)");

		$this->load->model("Pemeriksaan_spnModel", "spn");
		$this->data['spnarr'] = $this->spn->GetCombo();

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker', 'select2', 'treetable'
		);
		$this->data['configfile'] = $this->config->item('file_upload_config');
	}

	
	public function Index($jenis = 'eksternal', $page = 0)
	{
		parent::Index($jenis,$page);
	}
}
