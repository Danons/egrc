<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . "core/_adminController.php";
class Rtm_uraian extends _adminController
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();
		$this->viewlist = "panelbackend/rtm_uraianlist";
		$this->viewdetail = "panelbackend/rtm_uraiandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Bahan RTM';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Bahan RTM';
			$this->data['edited'] = true;
		} elseif ($this->mode == 'detail') {
			$this->data['page_title'] = 'Detail Bahan RTM';
			$this->data['edited'] = false;
		} else {
			$this->data['page_title'] = 'Daftar Bahan RTM';
		}

		$this->load->model("Rtm_uraianModel", "model");
		$this->load->model("RtmModel", "rtm");
		$this->data['rtmarr'] = $this->rtm->GetCombo();

		$this->load->model("Mt_jenis_rtmModel", "mtjenisrtm");
		$this->data['mtjenisrtmarr'] = $this->mtjenisrtm->GetComboP();

		$this->load->model("Mt_sdm_unitModel", "unit");
		$this->data['unitarr'] = $this->unit->GetCombo();
		unset($this->data['unitarr']['']);


		$this->load->model("Rtm_uraian_filesModel", "modelfile");
		$this->data['configfile'] = $this->config->item('file_upload_config');

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'tinymce', 'select2', 'upload'
		);
	}

	public function Index($jenis = null, $page = 0)
	{
		#unit
		if ($this->request['id_unit'] !== null)
			$_SESSION[SESSION_APP][$this->page_ctrl]['id_unit'] = $this->request['id_unit'];
		if ($_SESSION[SESSION_APP][$this->page_ctrl]['id_unit'] !== null)
			$this->data['id_unit'] = $_SESSION[SESSION_APP][$this->page_ctrl]['id_unit'];


		if (!$this->Access("view_all", "main"))
			$this->data['id_unit'] = $_SESSION[SESSION_APP]['id_unit'];

		$id_unit = $this->data['id_unit'];

		if ($id_unit) {
			$this->_setFilter("exists (select 1 
			from rtm_urian_unit 
			where deleted_date is null and rtm_uraian.id_rtm_uraian = rtm_urian_unit.id_rtm_uraian 
			and rtm_urian_unit.id_unit = " . $this->conn->escape($id_unit) . ")");
		}

		if ($this->request['act'] == 'set_filter')
			redirect(current_url());

		$this->data['addbutton'] .= UI::createSelect('id_unit', ["" => ""] + $this->data['unitarr'], $this->data['id_unit'], true, 'form-control ', "style='width:300px;display:inline;' onchange='goSubmit(\"set_filter\");'") . " &nbsp;&nbsp;&nbsp;";

		if (!$jenis) {
			redirect("panelbackend/rtm_uraian/index/baru");
			die();
		}
		$this->data['jenis'] = $jenis;


		if ($jenis == 'baru') {
			if ($this->post['act'] == 'setujui') {
				$id_rtm_uraian = $this->post['idkey'];
				$this->conn->goUpdate("rtm_uraian", ["is_risalah" => 1], "id_rtm_uraian = " . $this->conn->escape($id_rtm_uraian));
				redirect("panelbackend/rtm_uraian/index/$jenis");
				die();
			}
			if ($this->post['act'] == 'tolak') {
				$id_rtm_uraian = $this->post['idkey'];
				$this->conn->goUpdate("rtm_uraian", ["is_risalah" => 0], "id_rtm_uraian = " . $this->conn->escape($id_rtm_uraian));
				redirect("panelbackend/rtm_uraian/index/$jenis");
				die();
			}
			$this->_setFilter("(is_risalah is null)");
			$this->data['page_title'] = "BAHAN RTM (BARU)";
		} elseif ($jenis == 'open') {
			unset($this->access_role['add']);
			$this->_setFilter("(is_risalah = 1 and is_tindak_lanjut = 0 and (status=0 or status is null))");
			$this->data['page_title'] = "PERMASALAHAN YANG MASIH OPEN DI RTM SEBELUMNYA";
		} elseif ($jenis == 'ditolak') {
			unset($this->access_role['add']);
			$this->_setFilter("(is_risalah = 0)");
			$this->data['page_title'] = "BAHAN YANG TIDAK DISETUJUI";
		}


		$this->data['header'] = $this->Header();

		$this->data['list'] = $this->_getList($page);

		$this->data['page'] = $page;

		$param_paging = array(
			'base_url' => base_url("{$this->page_ctrl}/index/$jenis"),
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

	protected function _onDetail($id = null)
	{
		if (!$this->data['row']['id_rtm'])
			$this->data['row']['id_rtm'] = $this->conn->GetOne("select max(id_rtm) from rtm_uraian_link where deleted_date is null and id_rtm_uraian = " . $this->conn->escape($id));

		if (!$this->data['row']['id_rtm'])
			$this->data['row']['id_rtm'] = $this->conn->GetOne("select max(id_rtm) from rtm where deleted_date is null ");

		if ($this->data['row']['id_jenis_rtm_parent'])
			$this->data['mtjenisrtmarrsub'] = $this->mtjenisrtm->GetComboP($this->data['row']['id_jenis_rtm_parent']);

		if ($this->data['row']['id_rtm']) {
			$this->data['rtm'] = $this->rtm->GetByPk($this->data['row']['id_rtm']);
			$this->data['row']['status'] = $this->conn->GetOne("select status from rtm_uraian_link where deleted_date is null and id_rtm_uraian = " . $this->conn->escape($id) . " and id_rtm=" . $this->conn->escape($this->data['row']['id_rtm']));
		}

		if (!$this->data['row']['id_unit'])
			$this->data['row']['id_unit'] = $this->conn->GetList("select id_unit as idkey, id_unit as val from rtm_urian_unit where deleted_date is null and id_rtm_uraian = " . $this->conn->escape($id));

		if (!$this->data['row']['progress'])
			$this->data['row']['progress'] = $this->conn->GetArray("select * from rtm_progress where deleted_date is null and id_rtm_uraian = " . $this->conn->escape($id));


		return true;
	}

	protected function _afterDetail($id)
	{
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
		if (!$edited)
			$this->data['addbutton'] .= '<button type="button" class="btn btn-sm btn-success" onclick="goSubmit(\'print_ppt\')"><i class="bi bi-download"></i> PPT</button>';

		if ($this->post['act'] == 'print_ppt') {
			$this->print_ppt($this->data['row'], $this->data['rtm']);
		}
	}

	protected function displayShapeInfo1($oShape, $data)
	{
		if ($oShape instanceof \PhpOffice\PhpPresentation\Shape\RichText) {
			// membuat text tengah
			// $oShape->getActiveParagraph()->getAlignment()->setHorizontal(\PhpOffice\PhpPresentation\Style\Alignment::HORIZONTAL_CENTER);

			foreach ($oShape->getParagraphs() as $oParagraph) {
				foreach ($oParagraph->getRichTextElements() as $oRichText) {
					foreach ($data as $d => $a) {

						if (!$oRichText instanceof \PhpOffice\PhpPresentation\Shape\RichText\BreakElement) {

							$str = $oRichText->getText();
							$dd = '${' . $d . '}';
							if (strstr($str, $dd) !== false) {

								$srt2 = strip_tags(str_replace($dd, $a, $str));
								if ($srt2) {
									$oRichText->setText($srt2);
								}
							}
						}
					}
				}
			}
		}
	}

	public function olah_data($data, $rtm)
	{

		$dt['jenis_rtm'] = $this->data['mtjenisrtmarr'][$data['id_jenis_rtm_parent']];
		$dt['rtm_ke'] = $this->data['rtmarr'][$data['id_rtm']];
		$dt['tingkat_up'] = strtoupper($rtm['tingkat']);

		$time = strtotime($data['created_date']);
		$newformat = date('Y-m-d', $time);
		$dt['tgl'] = Eng2Ind($newformat);

		$statsusarr = array('0' => 'Open', '1' => 'Close');
		$dt['statusarr'] = $statsusarr[$data['status']];

		foreach ($data['id_unit'] as $id_u) {
			$dt['data_pic'] = $this->data['unitarr'][$id_u];
		}
		$data_akhir = array_merge($dt, $data, $rtm);
		// dpr($data_akhir, 1);
		return ($data_akhir);
	}

	public function print_ppt($row, $rtm)
	{
		# 1. atur data
		$data = $this->olah_data($row, $rtm);
		$this->load->library("ppt");
		$phpPresentation = new \PhpOffice\PhpPresentation\PhpPresentation();
		// $phpPresentationStle = new \PhpOffice\PhpPresentation\Style\Alignment();
		// $phpPresentation->getLayout()->setDocumentLayout(['cx' => 1280, 'cy' => 700], true)
		$phpPresentation->getLayout()->setDocumentLayout(['cx' => 1300, 'cy' => 700], true)
			->setCX(1300,  PhpOffice\PhpPresentation\DocumentLayout::UNIT_PIXEL)
			->setCY(700,  PhpOffice\PhpPresentation\DocumentLayout::UNIT_PIXEL);

		# 2. load file
		// 2.1 atur pilihan file
		$template = 'Sample_12.pptx';
		$this->data['configfile'] = $this->config->item('file_upload_config');
		$full_path = $this->data['configfile']['upload_path'] . $template;

		// 2.2 load file setelah dipilih
		$pptReader = \PhpOffice\PhpPresentation\IOFactory::createReader('PowerPoint2007');
		$oPHPPresentation = $pptReader->load($full_path);


		# 3. edit ppt
		foreach ($oPHPPresentation->getAllSlides() as $oSlide) {
			foreach ($oSlide->getShapeCollection() as $oShape) {
				if ($oShape instanceof \PhpOffice\PhpPresentation\Shape\Group) {
					foreach ($oShape->getShapeCollection() as $oShapeChild) {
						$this->displayShapeInfo1($oShapeChild, $data);
					}
				} else {
					$this->displayShapeInfo1($oShape, $data);
				}
			}
			// $phpPresentation->addExternalSlide($oSlide);
		}
		// die;
		// $phpPresentation->removeSlideByIndex(0);


		# 4. save / download file ppt
		// $writer = new \PhpOffice\PhpPresentation\Writer\PowerPoint2007($phpPresentation);
		$writer = new \PhpOffice\PhpPresentation\Writer\PowerPoint2007($oPHPPresentation);

		$date = date('d-m-Y');
		$filename = 'name_ppt-' . $date;

		header('Content-Type: application/vnd.ms-powerpoint');
		header('Content-Disposition: attachment;filename="' . $filename . '.pptx"');
		header('Cache-Control: max-age=0');

		$writer->save("php://output");
		// dpr($phpPresentation, 1);


		// die;


		# for print / download
		/*
		// $currentSlide = $phpPresentation->getActiveSlide();
		$phpPresentation->removeSlideByIndex(0);
		$currentSlide = $phpPresentation->createSlide();


		// make shape
		$shape = $currentSlide->createRichTextShape()
			->setHeight(500)
			->setWidth(900)
			->setOffsetX(10)
			->setOffsetY(10);

		// set position text on shape
		$shape->getActiveParagraph()->getAlignment()->setHorizontal($phpPresentationStle::HORIZONTAL_LEFT);

		// make taxt
		foreach ($row as $p => $r) {
			if (in_array($p, array('id_jenis_rtm_parent', 'id_jenis_rtm', 'uraian', 'analisis'))) {
				$text[] = $p . ": \n" . $r;
				// $textRun = $shape->createTextRun(strip_tags($p) . ': ' . strip_tags($r));
				// $textRun->getFont()->setSize(18);
			}
		}
		$text = implode("\n", $text);
		$textRun = $shape->createTextRun(strip_tags($p) . ': ' . strip_tags($text));
		$textRun->getFont()->setSize(18);

		// for create new slide
		$currentSlide = $phpPresentation->createSlide();
		$shape = $currentSlide->createRichTextShape();
		$shape->setHeight(500)
			->setWidth(900)
			->setOffsetX(10)
			->setOffsetY(10);
		$shape->getActiveParagraph()->getAlignment()->setHorizontal($phpPresentationStle::HORIZONTAL_LEFT);

		foreach ($row as $p => $r) {
			if (in_array($p, array('uraian_rencana', 'uraian_target', 'id_unit', 'keterangan_pic'))) {
				$text2[] = $p . ": \n" . $r;
			}
		}
		$text2 = implode("\n", $text2);
		$textRun = $shape->createTextRun(strip_tags($p) . ': ' . strip_tags($text2));
		$textRun->getFont()->setSize(18);
		// dpr($text,1);

		$writer = new \PhpOffice\PhpPresentation\Writer\PowerPoint2007($phpPresentation);


		$filename = 'simple';

		header('Content-Type: application/vnd.ms-powerpoint');
		header('Content-Disposition: attachment;filename="' . $filename . '.pptx"');
		header('Cache-Control: max-age=0');

		$writer->save("php://output");
		*/
	}
	protected function _afterInsert($id)
	{
		$ret = true;

		if ($this->modelfile) {
			if (!empty($this->post['files'])) {
				foreach ($this->post['files']['id'] as $k => $v) {
					$return = $this->_updateFiles(array($this->pk => $id), $v);

					$ret = $return['success'];
				}
			}
		}

		if ($this->post['id_unit'] && $ret) {
			$ret = $this->conn->Execute("update rtm_urian_unit where deleted_date is null and id_rtm_uraian = " . $this->conn->escape($id));
			foreach ($this->post['id_unit'] as $id_unit) {
				if (!$ret)
					break;
				$ret = $this->conn->goInsert("rtm_urian_unit", ["id_unit" => $id_unit, "id_rtm_uraian" => $id]);
			}
		}

		if ($this->post['progress'] && $ret) {
			$idarr = [];
			foreach ($this->post['progress'] as $r) {
				if (!$ret)
					break;

				$r['id_rtm_uraian'] = $id;

				if ($r['id_rtm_progress'])
					$ret = $this->conn->goUpdate("rtm_progress", $r, "id_rtm_progress= " . $this->conn->escape($r['id_rtm_progress']));
				else {
					$ret = $this->conn->goInsert("rtm_progress", $r);
					$r['id_rtm_progress'] = $this->conn->GetOne("select max(id_rtm_progress) from rtm_progress where deleted_date is null and id_rtm_uraian = " . $this->conn->escape($id));
				}

				$idarr[] = $r['id_rtm_progress'];
			}

			if ($ret)
				$ret = $this->conn->Execute("update rtm_progress where deleted_date is null and id_rtm_uraian = " . $this->conn->escape($id) . " and id_rtm_progress not in (" . implode(",", $idarr) . ")");
		}

		if ($ret) {
			$cek = $this->conn->GetOne("select 1 from rtm_uraian_link where deleted_date is null and id_rtm_uraian = " . $this->conn->escape($id) . " and id_rtm = " . $this->conn->escape($this->post['id_rtm']));
			if ($cek) {
				$ret = $this->conn->goUpdate("rtm_uraian_link", ["id_rtm_uraian" => $id, "id_rtm" => $this->post['id_rtm'], "status" => $this->post['status']], "id_rtm_uraian = " . $this->conn->escape($id) . " and id_rtm = " . $this->conn->escape($this->post['id_rtm']));
			} else {
				$ret = $this->conn->goInsert("rtm_uraian_link", ["id_rtm_uraian" => $id, "id_rtm" => $this->post['id_rtm'], "status" => $this->post['status']]);
			}
		}

		return $ret;
	}

	protected function Header()
	{
		return array(
			array(
				'name' => 'uraian',
				'label' => 'Uraian',
				'width' => "auto",
				'type' => "longblob",
			),
			array(
				'name' => 'analisis',
				'label' => 'Analisis',
				'width' => "auto",
				'type' => "longblob",
			),
			array(
				'name' => 'uraian_rencana',
				'label' => 'Uraian Rencana',
				'width' => "auto",
				'type' => "longblob",
			),
			array(
				'name' => 'uraian_target',
				'label' => 'Uraian Target',
				'width' => "auto",
				'type' => "longblob",
			),
			array(
				'name' => 'picstr',
				'label' => 'PIC',
				'width' => "auto",
			),
			array(
				'name' => 'status',
				'label' => 'Status',
				'width' => "auto",
				'type' => "list",
				'value' => array('0' => 'Open', '1' => 'Close'),
			),
			array(
				'name' => 'id_rtm',
				'label' => 'RTM Ke',
				'width' => "auto",
				'type' => "list",
				'value' => $this->data['rtmarr'],
			),
			// array(
			// 	'name'=>'tindak_lanjut', 
			// 	'label'=>'Tindak Lanjut', 
			// 	'width'=>"auto",
			// 	'type'=>"longblob",
			// ),
			// array(
			// 	'name'=>'tindak_lanjut_rencana_penyelesaian', 
			// 	'label'=>'Tindak Lanjut Rencana Penyelesaian', 
			// 	'width'=>"auto",
			// 	'type'=>"longblob",
			// ),
			// array(
			// 	'name'=>'tindak_lanjut_realisasi_penyelesaian', 
			// 	'label'=>'Tindak Lanjut Realisasi Penyelesaian', 
			// 	'width'=>"auto",
			// 	'type'=>"longblob",
			// ),
			// array(
			// 	'name'=>'id_jenis_rtm', 
			// 	'label'=>'Jenrtm', 
			// 	'width'=>"auto",
			// 	'type'=>"list",
			// 	'value'=>array(''=>'-pilih-','0'=>'Tidak','1'=>'Iya'),
			// ),
			// array(
			// 	'name'=>'id_jenis_rtm_parent', 
			// 	'label'=>'Jenis RTM Parent', 
			// 	'width'=>"auto",
			// 	'type'=>"list",
			// 	'value'=>$this->data['mtjenisrtmarr'],
			// ),
			// array(
			// 	'name'=>'is_risalah', 
			// 	'label'=>'Risalah', 
			// 	'width'=>"auto",
			// 	'type'=>"list",
			// 	'value'=>array(''=>'-pilih-','0'=>'Tidak','1'=>'Iya'),
			// ),
			// array(
			// 	'name'=>'is_tindak_lanjut', 
			// 	'label'=>'Tindak Lanjut', 
			// 	'width'=>"auto",
			// 	'type'=>"list",
			// 	'value'=>array(''=>'-pilih-','0'=>'Tidak','1'=>'Iya'),
			// ),
		);
	}

	protected function Record($id = null)
	{
		if ($this->post['progress'])
			foreach ($this->post['progress'] as &$r) {
				$r['target'] = Rupiah2Number($r['target']);
				$r['realisasi'] = Rupiah2Number($r['realisasi']);
				$r['competitor'] = Rupiah2Number($r['competitor']);
			}
		$return = array(
			'uraian' => $this->post['uraian'],
			'analisis' => $this->post['analisis'],
			'uraian_rencana' => $this->post['uraian_rencana'],
			'uraian_target' => $this->post['uraian_target'],
			'keterangan_pic' => $this->post['keterangan_pic'],
			'status' => $this->post['status'],
			'tindak_lanjut' => $this->post['tindak_lanjut'],
			'tindak_lanjut_rencana_penyelesaian' => $this->post['tindak_lanjut_rencana_penyelesaian'],
			'tindak_lanjut_realisasi_penyelesaian' => $this->post['tindak_lanjut_realisasi_penyelesaian'],
			'id_jenis_rtm' => $this->post['id_jenis_rtm'],
			'id_jenis_rtm_parent' => $this->post['id_jenis_rtm_parent'],
			// 'is_risalah' => (int)$this->post['is_risalah'],
			'is_tindak_lanjut' => (int)$this->post['is_tindak_lanjut'],
			'is_grafik' => (int)$this->post['is_grafik'],
		);

		if (!$id && !Access("evaluasi", "panelbackend/rtm_risalah"))
			$this->post['status'] = $return['status'] = 0;

		return $return;
	}

	protected function Rules()
	{
		return array(
			"uraian" => array(
				'field' => 'uraian',
				'label' => 'Uraian',
				'rules' => "",
			),
			"analisis" => array(
				'field' => 'analisis',
				'label' => 'Analisis',
				'rules' => "",
			),
			"uraian_rencana" => array(
				'field' => 'uraian_rencana',
				'label' => 'Uraian Rencana',
				'rules' => "",
			),
			"uraian_target" => array(
				'field' => 'uraian_target',
				'label' => 'Uraian Target',
				'rules' => "",
			),
			"keterangan_pic" => array(
				'field' => 'keterangan_pic',
				'label' => 'Keterangan PIC',
				'rules' => "",
			),
			"status" => array(
				'field' => 'status',
				'label' => 'Status',
				'rules' => "integer",
			),
			"tindak_lanjut" => array(
				'field' => 'tindak_lanjut',
				'label' => 'Tindak Lanjut',
				'rules' => "",
			),
			"tindak_lanjut_rencana_penyelesaian" => array(
				'field' => 'tindak_lanjut_rencana_penyelesaian',
				'label' => 'Tindak Lanjut Rencana Penyelesaian',
				'rules' => "",
			),
			"tindak_lanjut_realisasi_penyelesaian" => array(
				'field' => 'tindak_lanjut_realisasi_penyelesaian',
				'label' => 'Tindak Lanjut Realisasi Penyelesaian',
				'rules' => "",
			),
			"id_jenis_rtm" => array(
				'field' => 'id_jenis_rtm',
				'label' => 'Jenis RTM',
				// 'rules' => "in_list[" . implode(",", array_keys($this->data['mtjenisrtmarrsub'])) . "]",
			),
			"id_jenis_rtm_parent" => array(
				'field' => 'id_jenis_rtm_parent',
				'label' => 'Jenis RTM Parent',
				'rules' => "in_list[" . implode(",", array_keys($this->data['mtjenisrtmarr'])) . "]",
			),
			"is_risalah" => array(
				'field' => 'is_risalah',
				'label' => 'IS Risalah',
				'rules' => "integer",
			),
			"is_tindak_lanjut" => array(
				'field' => 'is_tindak_lanjut',
				'label' => 'IS Tindak Lanjut',
				'rules' => "integer",
			),
		);
	}
}
