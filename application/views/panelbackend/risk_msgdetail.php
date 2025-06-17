
        <div class="container-fluid">
        <!-- <?php if($page_title){ ?>
            <div class="block-header">
                <h2>
        <?=$page_title?>
        <?php if($sub_page_title){ ?> <small><?=$sub_page_title?></small> <?php }?></h2>
            </div>
            <?php } ?> -->
            <!-- Basic Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <!-- <div class="card-header">
                              <?php if($page_title){ ?>
            <div class="block-header">
                <h2>
        <?=$page_title?>
        <?php if($sub_page_title){ ?> <small><?=$sub_page_title?></small> <?php }?></h2>
            </div>
            <?php } ?>
                        </div> -->
                        <div class="body table-responsive">

                      <?php  if(($_SESSION[SESSION_APP]['loginas'])){ ?>
                      <div class="alert alert-warning">
                          Anda sedang mengakses user lain. <a href="<?=site_url("panelbackend/home/loginasback")?>" class="alert-link">Kembali</a>.
                      </div>
                      <?php }?>

                      <?=FlashMsg()?>
<div class="row">
<div class="col-sm-12">

<?php 
$from = UI::createTextArea('msg',$row['msg'],'','',$edited,$class='form-control',"");
echo UI::createFormGroup($from, $rules["msg"], "msg", "ISI PENGUMUMAN", true);
?>				

<?php 
$from = UI::createSelect('open_evaluasi',array('-'=>'-kosong-','0'=>'Close','1'=>'Open'),$row['open_evaluasi'],$edited,'form-control',"onchange='goSubmit(\"set_value\")'",false);
echo UI::createFormGroup($from, $rules["open_evaluasi"], "open_evaluasi", "STATUS EVALUASI RISIKO <i style='font-weight: 100; font-size: 12px;'>Silahkan dikosongi apabila Anda hanya ingin memberi pengumuman saja</i>", true);
?>
<br/>
<?php   
require_once("_scorecardtree.php");
$form = "<span></span>";
foreach ($mtjeniskajianrisikoarr as $id_kajian_risiko => $label) {
  $rowscorecards = $rowscorecardsarr[$id_kajian_risiko];
  $sc = scorecardtree($rowscorecards, $row, $id_kajian_risiko,null, $label, true);
  if($sc)
    $form .= "<br/>".$sc;

}
echo UI::createFormGroup($form, $rules["id_scorecard"], "id_scorecard[]", "SCORECARD", true);
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>
</div>
                      <div style="clear: both;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<style type="text/css">
    /* table.dataTable {
    clear: both;
    margin-top: -15px !important;
    margin-bottom: 6px !important;
    max-width: none !important;
} */
</style>