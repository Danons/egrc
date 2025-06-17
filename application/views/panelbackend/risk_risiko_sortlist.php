<div class="container-fluid">

        <?php if($page_title){ ?>
            <div class="block-header">
                <h2>
        <?=$page_title?>
        <?php if($sub_page_title){ ?> <small><?=$sub_page_title?></small> <?php }?></h2>
            </div>
            <?php } ?>
            <!-- Basic Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">

<div class="col-sm-5">
<?php
$form = UI::createSelect('id_kajian_risiko',$mtjeniskajianrisikoarr,$id_kajian_risiko,true,$class='form-control select2',"onchange='goSubmit(\"set_value\")'");
echo UI::FormGroup(array(
    'form'=>$form,
    'sm_label'=>4,
    'label'=>'Kajian Risiko'
    ));
?>
</div>
<?php if(($scorecardarr)){ ?>
<div class="col-sm-5">
<?php
$form = UI::createSelect('id_scorecard',$scorecardarr,$id_scorecard,true,$class='form-control select2',"onchange='goSubmit(\"set_value\")'");
echo UI::FormGroup(array(
    'form'=>$form,
    'sm_label'=>4,
    'label'=>'Risk Profile'
    ));
?>
</div>
<?php } ?>
<div class="col-sm-2">

<?php 
$form = UI::createTextNumber('top',$top,'4','4',true,$class='form-control ',"onchange='goSubmit(\"set_value\")'");
echo UI::FormGroup(array(
    'form'=>$form,
    'sm_label'=>3,
    'label'=>'Top'
    ));
?>
</div>
                          <div style="clear: both;"></div>
                        </div>

                        <div class="body table-responsive">

                      <?php  if(($_SESSION[SESSION_APP]['loginas'])){ ?>
                      <div class="alert alert-warning">
                          Anda sedang mengakses user lain. <a href="<?=site_url("panelbackend/home/loginasback")?>" class="alert-link">Kembali</a>.
                      </div>
                      <?php }?>

                      <?=FlashMsg()?>

<table class="table table-bordered table-hover dataTable">
  <thead>
    <tr>
      <th style="width:1px;text-align:center;background-color:#034485;color:#eee;" rowspan="2">NO</th>
      <th  style="text-align:center;background-color:#034485;color:#eee;" rowspan="2">RISIKO</th>
      <th  style="text-align:center;background-color:#034485;color:#eee;" rowspan="2">RISK OWNER</th>
      <th  style="text-align:center;background-color:#034485;color:#eee;" colspan="<?=count($rating)?>">LEVEL RISIKO</th>
      <th style="width:50px;text-align:center;background-color:#034485;color:#eee;" rowspan="2"></th>
    </tr>
    <tr>
      <?php if($rating['i']){ ?>
      <th style="width:75px;text-align:center;background-color:#034485;color:#eee; cursor: pointer" onclick="setOrder('i')" <?php if($order=='i'){echo "class='sorting_desc'";}?> >Inheren Risk</th>
      <?php } if($rating['c']){ ?>
      <th  style="width:75px;text-align:center;background-color:#034485;color:#eee; cursor: pointer" onclick="setOrder('c')" <?php if($order=='c'){echo "class='sorting_desc'";}?> >Residual Saat Ini</th>
      <?php } if($rating['r']){ ?>
      <th  style="width:75px;text-align:center;background-color:#034485;color:#eee ; cursor: pointer" onclick="setOrder('r')" <?php if($order=='r'){echo "class='sorting_desc'";}?> >Target Residual</th>
      <?php } ?>
    </tr>
    </thead>
  <tbody>
    <?php
    $rs_matrix = $this->data['mtriskmatrix'];
    $data = array(array());
    foreach($rs_matrix as $k => $v){
      $data[$v['id_dampak']][$v['id_kemungkinan']] = $v;
    }

    $rs = $this->data['rows'];
    $no=1;
    $top_inheren = array();
    $top_paska_kontrol = array();
    $top_paska_mitigasi = array();
    foreach($rs as $r => $val){
      $top_inheren[$val['inheren_dampak']][$val['inheren_kemungkinan']][] = $no;
      $top_paska_kontrol[$val['control_dampak_penurunan']][$val['control_kemungkinan_penurunan']][] = $no;
      $top_residual_target[$val['residual_target_dampak']][$val['residual_target_kemungkinan']][] = $no;
      
      echo "<tr>";
      echo "<td style='text-align:center'>".$no++."</td>";
      echo "<td><a href='".site_url("panelbackend/risk_risiko/detail/$val[id_scorecard]/$val[id_risiko]")."' target='_BLANK'>$val[nama]</a></td>";
      echo "<td style='text-align:center'>$val[risk_owner]</td>";

      if($rating['i']){
        $bg = $data[$val['inheren_dampak']][$val['inheren_kemungkinan']]['warna'];
        echo "<td align='center' style='background-color:$bg;color:#333 !important;' class='bg-$bg'>$val[level_risiko_inheren]</td>";
      }

      if($rating['c']){
        $bg = $data[$val['control_dampak_penurunan']][$val['control_kemungkinan_penurunan']]['warna'];
        echo "<td align='center' style='background-color:$bg;color:#333 !important;' class='bg-$bg'>$val[level_risiko_control]</td>";
      }

      if($rating['r']){
        $bg = $data[$val['residual_target_dampak']][$val['residual_target_kemungkinan']]['warna'];
        echo "<td align='center' style='background-color:$bg;color:#333 !important;' class='bg-$bg'>$val[level_residual_evaluasi]</td>";
      }

      echo "<td align='center'>

            <a href=\"javascript:void(0)\" onclick=\"$('#idkey').val($val[id_risiko]); goSubmit('sort_up');\">
                <span class=\"bi bi-chevron-up\"></span>
            </a>
            <a href=\"javascript:void(0)\" onclick=\"$('#idkey').val($val[id_risiko]); goSubmit('sort_down');\">
                <span class=\"bi bi-chevron-down\"></span>
            </a>

      </td>";

      echo "</tr>";
    }
    if(!($rs)){
        echo "<tr><td colspan='8'>Data kosong</td></tr>";
    }
    ?>
    </tbody>
  </table>

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
<input type="hidden" name="order" id="order" value="<?=$order?>">
<script type="text/javascript">
  function setOrder(order){
    $("#order").val(order);
    goSubmit('set_order');
  }
</script>