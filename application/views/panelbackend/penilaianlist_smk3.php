<div class="row">
    <div class="breadcrumbs">
        <div class="pull-left">
            <?php if($page_title){ ?>
              <h1>
                  <?=$page_title?>
                  <?=$rowheader['nama']?>
              </h1>
            <?php } ?>
            <br/>
            <ol class="breadcrumb">
                <li>
                    <a href="<?=site_url("panelbackend/penilaian")?>">Kategori Penilaian</a>
                </li>
                <?php
                foreach($kategoriarr as $r){ 
                ?>
                <li>
                  <?=strtoupper($r['nama'])?>
                </li>
                <?php } ?>
            </ol>
        </div>
        <div class="pull-right">
        </div>
        <div style="clear: both;"></div>
    </div>

<?php
$is_admin = Access("edit","panelbackend/mt_kategori");
$iseditfile = $_SESSION[SESSION_APP]['login'];
if ($_SESSION[SESSION_APP]['id_unit']!=$this->data['id_unit'])
    $iseditfile=false;
if($is_admin)
    $iseditfile=true;

if(!$is_admin)
    $statusarr['1'] = 'Diajukan';
?>

<b>Unit : </b> 
<?=UI::createSelect('id_unit',$mtunitarr,$id_unit,true,'form-control ',"style='width:auto;display:inline;' onchange='goSubmit(\"set_filter\");'");?>
&nbsp;&nbsp;&nbsp;
<b>Jenis Periode : </b> 
<?=UI::createSelect('periode',$mtperiodearr,$periode,true,'form-control ',"style='width:auto;display:inline;' onchange='goSubmit(\"set_filter\");'");?>
&nbsp;&nbsp;&nbsp;

<?php if(count($periodedetailarr)>2){ ?>
<b><?=substr($mtperiodearr[$periode],0,-2)?> : </b> 
<?=UI::createSelect('bulan',$periodedetailarr,$bulan,true,'form-control ',"style='width:auto;display:inline;' onchange='goSubmit(\"set_filter\");'");?>
&nbsp;&nbsp;&nbsp;
<?php } ?>
<b>Tahun : </b> 
<?=UI::createTextNumber('tahun',$tahun,4,'',true,'form-control ',"style='width:80px;display:inline;' onchange='goSubmit(\"set_filter\");'",'');?>
&nbsp;&nbsp;&nbsp;
<b>Status : </b> 
<?=UI::createSelect('status1',array(''=>'-semua-','0'=>'Baru','1'=>'Diajukan','2'=>'Revisi','3'=>'Oke'),$status1,true,'form-control ',"style='width:auto;display:inline;' onchange='goSubmit(\"set_filter\");'");?>

<div style="clear:both;"></div>

<br/>

<?php  if(is_array($_SESSION[SESSION_APP]['loginas']) && count($_SESSION[SESSION_APP]['loginas'])){ ?>
<div class="alert alert-warning">
  Anda sedang mengakses user lain. <a href="<?=base_url("panelbackend/home/loginasback")?>" class="alert-link">Kembali</a>.
</div>
<?php }?>

<?=FlashMsg()?>
<?php
# 1 : admin, 2:proses user, 3:done
$status = 0;
if (isset($arearr))
foreach($arearr as $r){
    foreach ($r['sub'] as $r1) {
        foreach ($r1['lvl'] as $r2) {
            foreach($r2['bukti'] as $r3){
                if(!$status){
                    if($r3['status']==2 or !$r3['status']){
                        $status = 2;
                    }
                    elseif($r3['status']==1)
                        $status = 1;
                }

            }
        }
    }
}
?>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th width="10px">No</th>
            <th>Area</th>
            <th colspan="2">Sub Area</th>
            <th colspan="2">Uraian</th>
            <th colspan="2">Bukti Pendukung</th>
            <!-- <th>Periode</th> -->
            <th>Upload</th>

            <?php if($status){ ?>
                <th >Verifikasi</th>
            <?php } if($is_admin){ ?>
                <th>Aktif</th>
            <?php } ?>
            <?php if(!($status1!=='' && $status1!==null)){ ?>
                <th width="10px">Nilai</th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $total_nilaiarr = array();
        if (isset($arearr))
        foreach($arearr as $r)
        {
            $r1 = @$r['sub'][0];
            $r2 = @$r1['lvl'][0];
            $r3 = @$r2['bukti'][0];
        ?>
                <tr>
                    <td rowspan="<?= $r['rowspan'] ?>">
                        <?= $r['kode']; ?>
                    </td>
                    <td rowspan="<?= $r['rowspan'] ?>">
                        <?= $r['nama']; ?>
                    </td>
                    <td rowspan="<?= $r1['rowspan'] ?>">
                        <?= $r1['kode']; ?>
                    </td>
                    <td rowspan="<?= $r1['rowspan'] ?>"  width="100px">
                        <?= $r1['nama']; ?>
                    </td>
                    <td rowspan="<?= $r2['rowspan'] ?>">
                        <?= $r2['kode']; ?>
                    </td>
                    <td rowspan="<?= $r2['rowspan'] ?>">
                        <?= $r2['nama']; ?>
                    </td>
                    <td>
                        <?= $r3['kode']; ?>
                    </td>
                    <td>
                        <?= $r3['nama']; ?>
                    </td>
                   <!--  <td>
                        <?= $mtperiodearr[$r3['id_periode']]; ?>
                    </td> -->
                    <td>
                        <?=UI::createUploadMultiple("file_".$r3['id_penilaian'], $row['file_'.$r3['id_penilaian']], $page_ctrl, ($iseditfile && ($r3['status']=='2' or !$r3['status'])), "File PDF")?>
                    </td>
                    <?php if($status){ 
                        $rws = null;
                        if($r3['status']!='1')
                            $rws = $r3['komentar'][0];
                    ?>
                    <td>
                        <?= UI::createSelect('status['.$r3['id_penilaian']."]",$statusarr,$r3['status'],$is_admin,'form-control ',"style='width:100%;display:inline;' onClick='change_status(this, {$r3['id_penilaian']})'") ?>
                        <?=UI::createTextArea('keterangan['.$r3['id_penilaian']."]",$rws['komentar'],'','',$is_admin,'form-control',"placeholder='Keterangan tambahan...' onblur='chenge_keterangan(this, {$r3['id_penilaian']})' style='width:200px'")?>
                        <?=UI::createTextHidden('id_penilaian_komentar['.$r3['id_penilaian']."]",$rws['id_penilaian_komentar'],$is_admin)?>
                    </td>
                    <?php } if(!$is_admin && $r3['status']<>'3' && $r3['status']<>'1'){ ?>
                        <?=UI::createTextHidden('status['.$r3['id_penilaian']."]",1,!$is_admin)?>
                    <?php } ?>
                    <?php if($is_admin){ 
                    ?>
                        <td align="center">
                            <?= UI::createCheckBox('is_aktif['.$r3['id_penilaian']."]",1,$r3['is_aktif_penilaian'],'',true,"style='display:inline'","onClick='change_aktif(this, {$r3['id_penilaian']})'") ?>
                        </td>
                    <?php } ?>  
                    <?php if(!($status1!=='' && $status1!==null)){  $total_nilaiarr[] = (float)$r1['nilai_sub_area']; ?>
                        <td rowspan="<?= $r1['rowspan'] ?>" align="center">
                            <b><?=$r1['nilai_sub_area']; ?></b>
                        </td>
                    <?php } ?>
                </tr>
            <?php 
            if($r['sub'])
            foreach($r['sub'] as $i1=>$r1){ 
            $r2 = @$r1['lvl'][0];
            $r3 = @$r2['bukti'][0];
            if($i1>0){
            ?>
                <tr>
                    <td rowspan="<?= $r1['rowspan'] ?>">
                        <?= $r1['kode']; ?>
                    </td>
                    <td rowspan="<?= $r1['rowspan'] ?>">
                        <?= $r1['nama']; ?>
                    </td>
                    <td rowspan="<?= $r2['rowspan'] ?>">
                        <?= $r2['kode']; ?>
                    </td>
                    <td rowspan="<?= $r2['rowspan'] ?>">
                        <?= $r2['nama']; ?>
                    </td>
                    <td>
                        <?= $r3['kode']; ?>
                    </td>
                    <td>
                        <?= $r3['nama']; ?>
                    </td>
                    <!-- <td>
                        <?= $mtperiodearr[$r3['id_periode']]; ?>
                    </td> -->
                    <td>
                        <?=UI::createUploadMultiple("file_".$r3['id_penilaian'], $row['file_'.$r3['id_penilaian']], $page_ctrl, ($iseditfile && ($r3['status']=='2' or !$r3['status'])), "File PDF")?>
                    </td>
                    <?php if($status){ 
                        $rws = null;
                        if($r3['status']!='1')
                            $rws = $r3['komentar'][0];
                    ?>
                    <td>
                        <?= UI::createSelect('status['.$r3['id_penilaian']."]",$statusarr,$r3['status'],$is_admin,'form-control ',"style='width:100%;display:inline;' onClick='change_status(this, {$r3['id_penilaian']})'") ?>
                        <?=UI::createTextArea('keterangan['.$r3['id_penilaian']."]",$rws['komentar'],'','',$is_admin,'form-control',"placeholder='Keterangan tambahan...' onblur='chenge_keterangan(this, {$r3['id_penilaian']})' style='width:200px'")?>
                        <?=UI::createTextHidden('id_penilaian_komentar['.$r3['id_penilaian']."]",$rws['id_penilaian_komentar'],$is_admin)?>
                    </td>
                    <?php } if(!$is_admin && $r3['status']<>'3' && $r3['status']<>'1'){ ?>
                        <?=UI::createTextHidden('status['.$r3['id_penilaian']."]",1,!$is_admin)?>
                    <?php } ?>
                    <?php if($is_admin){ 
                    ?>
                        <td align="center">
                            <?= UI::createCheckBox('is_aktif['.$r3['id_penilaian']."]",1,$r3['is_aktif_penilaian'],'',true,"style='display:inline'","onClick='change_aktif(this, {$r3['id_penilaian']})'") ?>
                        </td>
                    <?php } ?>  
                    <?php if(!($status1!=='' && $status1!==null)){ $total_nilaiarr[] = (float)$r1['nilai_sub_area']; ?>
                        <td rowspan="<?= $r1['rowspan'] ?>" align="center">
                            <b><?=$r1['nilai_sub_area']; ?></b>
                        </td>
                    <?php } ?>
                </tr>
            <?php 
            }
            if(isset($r1['lvl']))
            foreach($r1['lvl'] as $i2=>$r2){ 
            $r3 = @$r2['bukti'][0];
            if($i2>0){
            ?>
                <tr>
                    <td rowspan="<?= $r2['rowspan'] ?>">
                        <?= $r2['kode']; ?>
                    </td>
                    <td rowspan="<?= $r2['rowspan'] ?>">
                        <?= $r2['nama']; ?>
                    </td>
                    <td>
                        <?= $r3['kode']; ?>
                    </td>
                    <td>
                        <?= $r3['nama']; ?>
                    </td>
                    <!-- <td>
                        <?= $mtperiodearr[$r3['id_periode']]; ?>
                    </td> -->
                    <td>
                        <?=UI::createUploadMultiple("file_".$r3['id_penilaian'], $row['file_'.$r3['id_penilaian']], $page_ctrl, ($iseditfile && ($r3['status']=='2' or !$r3['status'])), "File PDF")?>
                    </td>
                    <?php if($status){ 
                        $rws = null;
                        if($r3['status']!='1')
                            $rws = $r3['komentar'][0];
                    ?>
                    <td>
                        <?= UI::createSelect('status['.$r3['id_penilaian']."]",$statusarr,$r3['status'],$is_admin,'form-control ',"style='width:100%;display:inline;' onClick='change_status(this, {$r3['id_penilaian']})'") ?>
                        <?=UI::createTextArea('keterangan['.$r3['id_penilaian']."]",$rws['komentar'],'','',$is_admin,'form-control',"placeholder='Keterangan tambahan...' onblur='chenge_keterangan(this, {$r3['id_penilaian']})' style='width:200px'")?>
                        <?=UI::createTextHidden('id_penilaian_komentar['.$r3['id_penilaian']."]",$rws['id_penilaian_komentar'],$is_admin)?>
                    </td>
                    <?php } if(!$is_admin && $r3['status']<>'3' && $r3['status']<>'1'){ ?>
                        <?=UI::createTextHidden('status['.$r3['id_penilaian']."]",1,!$is_admin)?>
                    <?php } ?>
                    <?php if($is_admin){ 
                    ?>
                        <td align="center">
                            <?= UI::createCheckBox('is_aktif['.$r3['id_penilaian']."]",1,$r3['is_aktif_penilaian'],'',true,"style='display:inline'","onClick='change_aktif(this, {$r3['id_penilaian']})'") ?>
                        </td>
                    <?php } ?>  
                </tr>
            <?php 
            }
            if($r2['bukti'])
            foreach($r2['bukti'] as $i3=>$r3){ 
                if($i3>0){
            ?>
                <tr>
                    <td>
                        <?= $r3['kode']; ?>
                    </td>
                    <td>
                        <?= $r3['nama']; ?>
                    </td>
                    <!-- <td>
                        <?= $mtperiodearr[$r3['id_periode']]; ?>
                    </td> -->
                    <td>
                        <?=UI::createUploadMultiple("file_".$r3['id_penilaian'], $row['file_'.$r3['id_penilaian']], $page_ctrl, ($iseditfile && ($r3['status']=='2' or !$r3['status'])), "File PDF")?>
                    </td>
                    <?php if($status){ 
                        $rws = null;
                        if($r3['status']!='1')
                            $rws = $r3['komentar'][0];
                    ?>
                    <td>
                        <?= UI::createSelect('status['.$r3['id_penilaian']."]",$statusarr,$r3['status'],$is_admin,'form-control ',"style='width:100%;display:inline;' onClick='change_status(this, {$r3['id_penilaian']})'") ?>
                        <?=UI::createTextArea('keterangan['.$r3['id_penilaian']."]",$rws['komentar'],'','',$is_admin,'form-control',"placeholder='Keterangan tambahan...' onblur='chenge_keterangan(this, {$r3['id_penilaian']})' style='width:200px'")?>
                        <?=UI::createTextHidden('id_penilaian_komentar['.$r3['id_penilaian']."]",$rws['id_penilaian_komentar'],$is_admin)?>
                    </td>
                    <?php } if(!$is_admin && $r3['status']<>'3' && $r3['status']<>'1'){ ?>
                        <?=UI::createTextHidden('status['.$r3['id_penilaian']."]",1,!$is_admin)?>
                    <?php } ?>
                    <?php if($is_admin){ 
                    ?>
                        <td align="center">
                            <?= UI::createCheckBox('is_aktif['.$r3['id_penilaian']."]",1,$r3['is_aktif_penilaian'],'',true,"style='display:inline'","onClick='change_aktif(this, {$r3['id_penilaian']})'") ?>
                        </td>
                    <?php } ?>  
                </tr>
                <?php   }
                    } 
                } 
            }
        } 
        ?>
    </tbody>
</table>


<div style="float: right;">
<?php if($total_nilaiarr){ ?>
<b>
    Total Nilai : <?=round(array_sum($total_nilaiarr)/count($total_nilaiarr),2)?>
</b>
<?php }
if($status && $is_admin){ ?>
    <button type="button" onclick="if(confirm('Apakah Anda akan menyimpan ?')){goSubmit('ajukan')}" class="btn btn-success">Save</button>
<?php }elseif($status=='2'){ ?>
    <button type="button" onclick="if(confirm('Apakah Anda akan mengajukan ?')){goSubmit('ajukan')}" class="btn btn-success">Ajukan</button>
<?php } ?>
</div>

<?php if($is_admin){ ?>
    <script type="text/javascript">
        function change_aktif(t, id_penilaian){
            var v = $(t).is(':checked');
            console.log(v);
            $.ajax({
                type:'post',
                dataType:'json',
                url:"<?=current_url()?>",
                data:{act:"update_aktif", id_penilaian:id_penilaian,is_aktif:v},
                success:function(d){

                }
            });
        }
        function change_status(t, id_penilaian){
            return true;
            /*var v = $(t).val();
            $.ajax({
                type:'post',
                dataType:'json',
                url:"<?=current_url()?>",
                data:{act:"update_status", id_penilaian:id_penilaian,status:v},
                success:function(d){

                }
            });*/
        }
        function chenge_keterangan(t, id_penilaian){
            var v = $(t).val();
            var id_penilaian_komentar = $("#id_penilaian_komentar["+id_penilaian+"]").val();

            $.ajax({
                type:'post',
                dataType:'json',
                url:"<?=current_url()?>",
                data:{act:"update_keterangan", id_penilaian:id_penilaian,keterangan:v, id_penilaian_komentar:id_penilaian_komentar},
                success:function(d){
                    $("#id_penilaian_komentar["+id_penilaian+"]").val(d.id_penilaian_komentar)
                }
            });
        }
    </script>
<?php } ?>

<div style="clear: both;"></div>
</div>
<style type="text/css">
    table.dataTable {
    clear: both;
    margin-bottom: 6px !important;
    max-width: none !important;
}
.table th, .table td{
    padding:5px !important;
    font-size: 12px;
}
</style>

