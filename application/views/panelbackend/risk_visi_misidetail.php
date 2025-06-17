
<?php /*
            <div class="table-responsive">
<?php if(!$edited){ ?>
<div class="row">
<div style="color: #fff;
background: #034485;
position: relative;
padding: 5px;
min-height: 110px;">
<div style="position: absolute;
left: 0;
width: 0;
height: 0;
border-style: solid;
border-width: 109px 510px 0 0;
border-color: white transparent transparent transparent;
top: 0;"></div>
<center>
<h3>Visi :</h3>
<h5 style="max-width: 500px">"<?=$row['visi']?>"</h5>
</center>
<div style="position: absolute;
width: 0;
height: 0;
border-style: solid;
border-width: 0 510px 109px 0;
border-color: transparent white transparent transparent;
right: 0;
top: 0;"></div>
</div>

<div style="clear: both;"></div>
<br/>
<?php
if(file_exists(APPPATH."/views/panelbackend/_strategimap".$id_strategi_map.".php")){
  include APPPATH."/views/panelbackend/_strategimap".$id_strategi_map.".php";
}else{
  include APPPATH."/views/panelbackend/_strategimap.php";
}
?>
  </div>
      </div>
  <!-- modal untuk nama risiko berdasarkan sasaran strategi -->
  <div class="modal fade" id="risikostrategis" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="risikostrategislabel">Daftar Risiko</h4>
            </div>
            <div class="modal-body">
              <div id="datarisikostrategis">

              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary  btn-sm" data-bs-dismiss="modal">CLOSE</button>
            </div>
          </div>
      </div>
  </div>
<hr/>
<script type="text/javascript">
  $(function(){
    function callRisiko(id_sasaran, id_kajian_risiko) {
      if(id_kajian_risiko==undefined)
        id_kajian_risiko = 0;
      
  $.ajax({
    dataType: 'html',
    url:"<?=site_url("panelbackend/ajax/risikosasaran")?>/"+id_sasaran+'/'+id_kajian_risiko,
    success:function(response) {
      $('#datarisikostrategis').html(response);
    }
  })
}

$(function(){
  $('*[data-bs-target="#risikostrategis"]').click(function(){
    var id = $(this).attr('id');
    callRisiko(id);
  });
});
});
</script>
<?php } */?>
<div class="row">
<div class="col-sm-12">

<?php
$from = UI::createTextArea('visi',$row['visi'],'','',$edited,$class='form-control',"style='height:110px'");
echo UI::createFormGroup($from, $rules["visi"], "visi", "Visi", false, 2);
?>

<?php
$from = UI::createTextArea('misi',$row['misi'],'','',$edited,$class='form-control',"style='height:110px'");
echo UI::createFormGroup($from, $rules["misi"], "misi", "Misi", false, 2);
?>
<hr/>

<?php
$from = UI::createTextArea('konteks_internal',$row['konteks_internal'],'','',$edited,$class='form-control',"style='height:110px'");
echo UI::createFormGroup($from, $rules["konteks_internal"], "konteks_internal", "Konteks Internal", false, 2);
?>

<?php
$from = UI::createTextArea('konteks_eksternal',$row['konteks_eksternal'],'','',$edited,$class='form-control',"style='height:110px'");
echo UI::createFormGroup($from, $rules["konteks_eksternal"], "konteks_eksternal", "Konteks Eksternal", false, 2);
?>
<hr/>

<?php
$from = UI::createTextArea('strength',$row['strength'],'','',$edited,$class='form-control',"");
echo UI::createFormGroup($from, $rules["strength"], "strength", "Strength", false, 2);
?>

<?php
$from = UI::createTextArea('weakness',$row['weakness'],'','',$edited,$class='form-control',"");
echo UI::createFormGroup($from, $rules["weakness"], "weakness", "Weakness", false, 2);
?>

<?php
$from = UI::createTextArea('opportunity',$row['opportunity'],'','',$edited,$class='form-control',"");
echo UI::createFormGroup($from, $rules["opportunity"], "opportunity", "Opportunity", false, 2);
?>

<?php
$from = UI::createTextArea('threat',$row['threat'],'','',$edited,$class='form-control',"");
echo UI::createFormGroup($from, $rules["threat"], "threat", "Threat", false, 2);
?>
<?php 

if($view_all && $edited){
$from = UI::createTextBox('tgl_mulai_efektif',($row['tgl_mulai_efektif']?$row['tgl_mulai_efektif']:date('Y-m-d')),'10','10',($view_all && $edited),$class='form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tgl_mulai_efektif"], "tgl_mulai_efektif", "Tgl. Mulai Efektif", false, 2, ($view_all && $edited));

$from = UI::createTextBox('tgl_akhir_efektif',$row['tgl_akhir_efektif'],'10','10',($view_all && $edited),$class='form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tgl_akhir_efektif"], "tgl_akhir_efektif", "Tgl. Akhir Efektif", false, 2, ($view_all && $edited));
}
?>

<?php /*
$from = UI::createSelect('unit',$mtunitarr,$row['unit'],$edited,$class='form-control ',"style='width:100%;'");
echo UI::createFormGroup($from, $rules["unit"], "unit", "Unit", false, 2);
*/?>

<?php
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, false, 2);
?>
</div>
</div>
